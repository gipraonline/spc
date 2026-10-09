<?php

namespace App\Http\Controllers\Hr;

use App\Models\Hr\Candidate;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use App\Models\Hr\JobRequisition;
use App\Models\Hr\OnboardingChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class RecruitmentController extends Controller
{
    /** Pipeline order. A candidate moves one step forward at a time. */
    public const STAGES = ['applied', 'shortlisted', 'interviewed', 'offered', 'hired'];

    public const SOURCES = ['referral' => 'Referral', 'portal' => 'Portal', 'walk-in' => 'Walk-in', 'agency' => 'Agency'];

    public function index(Request $request)
    {
        $module = $this->abortUnlessModuleAllowed('recruitment');

        $requisitions = JobRequisition::with(['department', 'designation', 'candidates'])
            ->orderByDesc('id')->get();

        $selectedRequisition = $request->filled('requisition')
            ? $requisitions->firstWhere('id', $request->integer('requisition'))
            : $requisitions->firstWhere('status', 'open') ?? $requisitions->first();

        $pipeline = [];
        if ($selectedRequisition) {
            foreach (self::STAGES as $stage) {
                $pipeline[$stage] = $selectedRequisition->candidates->where('stage', $stage)->values();
            }
        }

        // Hired candidates who already have an employee profile move to History.
        if ($selectedRequisition) {
            $pipeline['hired'] = $pipeline['hired']->whereNull('converted_employee_id')->values();
        }

        $onboarding = Candidate::with(['requisition', 'checklistItems'])
            ->where('stage', 'hired')->whereNull('converted_employee_id')
            ->orderByDesc('updated_at')->get();

        $history = Candidate::with(['requisition', 'convertedEmployee.user'])
            ->where('stage', 'hired')->whereNotNull('converted_employee_id')
            ->orderByDesc('updated_at')->get();

        $tab = $request->query('tab');
        $activeTab = in_array($tab, ['requisitions', 'candidates', 'onboarding', 'history'], true) ? $tab : 'requisitions';
        $webUser = auth('web')->user();

        return view('hr.modules.recruitment', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'recruitment',
            'departments' => Department::orderBy('name')->get(),
            'designations' => Designation::orderBy('title')->get(),
            'requisitions' => $requisitions,
            'selectedRequisition' => $selectedRequisition,
            'pipeline' => $pipeline,
            'onboarding' => $onboarding,
            'history' => $history,
            'activeTab' => $activeTab,
            'pendingCount' => $requisitions->where('status', 'pending_approval')->count(),
            'canCreateEmployee' => (bool) ($webUser && $webUser->can('employees.create')),
            'sources' => self::SOURCES,
            'stages' => self::STAGES,
            'canApprove' => $this->currentRole() === 'super_admin',
        ]));
    }

    /* ------------------------------------------------------------------ */
    /*  Requisitions: HR raises, Super Admin approves                      */
    /* ------------------------------------------------------------------ */

    public function storeRequisition(Request $request)
    {
        $this->abortUnlessModuleAllowed('recruitment');
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate([
            'title' => 'required|string|max:150',
            'department_id' => 'nullable|exists:spc_hr.departments,id',
            'designation_id' => 'nullable|exists:spc_hr.designations,id',
            'openings' => 'required|integer|min:1',
        ]);

        // A super admin raising a requisition is the approver, so it opens straight away.
        $selfApproved = $this->currentRole() === 'super_admin';

        $requisition = JobRequisition::create(array_merge($data, [
            'status' => $selfApproved ? 'open' : 'pending_approval',
            'approved_by' => $selfApproved ? $this->currentUser()->id : null,
            'approved_at' => $selfApproved ? now() : null,
            'requested_by' => $this->currentUser()->id,
            'created_at' => now(),
        ]));

        return redirect()->route('hr.recruitment.index', ['requisition' => $requisition->id, 'tab' => 'requisitions'])
            ->with('status', $selfApproved
                ? 'Requisition for "'.$requisition->title.'" is open.'
                : 'Requisition for "'.$requisition->title.'" sent to the Super Admin for approval.');
    }

    public function decideRequisition(Request $request, JobRequisition $requisition)
    {
        $this->abortUnlessModuleAllowed('recruitment');
        abort_unless($this->currentRole() === 'super_admin', 403, 'Only a Super Admin can approve requisitions.');

        $data = $request->validate([
            'decision' => 'required|in:approve,reject',
            'remarks' => 'nullable|string|max:255',
        ]);

        if ($requisition->status !== 'pending_approval') {
            return back()->with('error', 'This requisition has already been decided.');
        }

        $approve = $data['decision'] === 'approve';

        $requisition->update([
            'status' => $approve ? 'open' : 'rejected',
            'approved_by' => $this->currentUser()->id,
            'approved_at' => now(),
            'decision_remarks' => $data['remarks'] ?? null,
        ]);

        return redirect()->route('hr.recruitment.index', ['requisition' => $requisition->id, 'tab' => 'requisitions'])
            ->with('status', '"'.$requisition->title.'" '.($approve ? 'approved and opened for candidates.' : 'rejected.'));
    }

    /* ------------------------------------------------------------------ */
    /*  Candidates                                                         */
    /* ------------------------------------------------------------------ */

    public function storeCandidate(Request $request, JobRequisition $requisition)
    {
        $this->abortUnlessModuleAllowed('recruitment');
        abort_unless($this->isHrOrAbove(), 403);

        if ($requisition->status !== 'open') {
            return back()->with('error', 'Candidates can only be added to an open requisition.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'email' => 'nullable|email|max:150',
            'source' => ['required', Rule::in(array_keys(self::SOURCES))],
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ], [
            'phone.regex' => 'Enter a valid phone number.',
            'resume.mimes' => 'Resume must be a PDF, DOC or DOCX file.',
            'resume.max' => 'Resume must be 5 MB or smaller.',
        ]);

        $duplicate = Candidate::where('requisition_id', $requisition->id)
            ->where(function ($q) use ($data) {
                $q->where('phone', $data['phone']);
                if (! empty($data['email'])) {
                    $q->orWhere('email', $data['email']);
                }
            })->exists();

        if ($duplicate) {
            return back()->withInput()->with('error', 'A candidate with this phone or email is already on this requisition.');
        }

        $path = $request->hasFile('resume')
            ? $request->file('resume')->store('recruitment/resumes', 'local')
            : null;

        Candidate::create([
            'requisition_id' => $requisition->id,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'source' => $data['source'],
            'resume_path' => $path,
            'stage' => 'applied',
        ]);

        return redirect()->route('hr.recruitment.index', ['requisition' => $requisition->id, 'tab' => 'candidates'])
            ->with('status', $data['name'].' added to the pipeline.');
    }

    public function resume(Candidate $candidate)
    {
        $this->abortUnlessModuleAllowed('recruitment');
        abort_unless($this->isHrOrAbove(), 403);
        abort_unless($candidate->resume_path && Storage::disk('local')->exists($candidate->resume_path), 404);

        return Storage::disk('local')->download(
            $candidate->resume_path,
            str($candidate->name)->slug().'-resume.'.pathinfo($candidate->resume_path, PATHINFO_EXTENSION)
        );
    }

    public function updateCandidateStage(Request $request, Candidate $candidate)
    {
        $this->abortUnlessModuleAllowed('recruitment');
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate([
            'stage' => ['required', Rule::in([...self::STAGES, 'rejected'])],
        ]);

        $requisition = $candidate->requisition;
        $to = $data['stage'];

        if (! $requisition || $requisition->status !== 'open') {
            return back()->with('error', 'This requisition is not open, so its candidates can no longer be moved.');
        }
        if ($candidate->stage === 'hired') {
            return back()->with('error', $candidate->name.' is already hired.');
        }
        if ($to === $candidate->stage) {
            return back();
        }

        // One step forward at a time; rejecting is allowed from any stage.
        if ($to !== 'rejected') {
            $from = array_search($candidate->stage, self::STAGES, true);
            if ($from === false || array_search($to, self::STAGES, true) !== $from + 1) {
                return back()->with('error', 'A candidate moves one step at a time: '.implode(' → ', array_map('ucfirst', self::STAGES)).'.');
            }
        }

        $closed = false;

        DB::connection('spc_hr')->transaction(function () use ($candidate, $requisition, $to, &$closed) {
            $candidate->update(['stage' => $to]);

            if ($to !== 'hired') {
                return;
            }

            if ($candidate->checklistItems()->count() === 0) {
                foreach (['Offer accepted', 'ID proof & documents uploaded', 'Bank account details collected', 'Laptop & access provisioning', 'Day-1 orientation scheduled'] as $item) {
                    OnboardingChecklistItem::create(['candidate_id' => $candidate->id, 'item' => $item, 'is_completed' => false]);
                }
            }

            // Hires reached the number of openings: close the requisition.
            $hired = Candidate::where('requisition_id', $requisition->id)->where('stage', 'hired')->count();
            if ($hired >= $requisition->openings) {
                $requisition->update(['status' => 'closed', 'closed_at' => now()]);
                $closed = true;
            }
        });

        $message = $candidate->name.' moved to '.ucfirst($to).'.';
        if ($closed) {
            $message .= ' All '.$requisition->openings.' '.($requisition->openings == 1 ? 'opening is' : 'openings are').' filled, so the requisition is now closed.';
        }

        return back()->with('status', $message);
    }

    public function toggleChecklistItem(OnboardingChecklistItem $item)
    {
        $this->abortUnlessModuleAllowed('recruitment');
        abort_unless($this->isHrOrAbove(), 403);

        $newState = ! $item->is_completed;

        $item->update([
            'is_completed' => $newState,
            'completed_at' => $newState ? now() : null,
        ]);

        return back();
    }
}
