<?php

namespace App\Http\Controllers\Hr;

use App\Models\Hr\EmployeeDocument;
use App\Models\Hr\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Document Verification — the one place HR Admin / Super Admin review the
 * documents employees upload (Aadhar, PAN, certificates, ...).
 *
 * Access is enforced twice: the `hr.admin` route middleware (HR Admin and
 * Super Admin portal roles only) and abortUnlessModuleAllowed() against
 * config/hr_modules.php, so the two can never drift apart silently.
 *
 * Viewing is open to those roles (so the MD can see everything), but verifying or
 * rejecting needs the Spatie permission `document-verification.verify`.
 */
class DocumentVerificationController extends Controller
{
    private const MIME_BY_EXT = [
        'pdf' => 'application/pdf',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
    ];

    public function index(Request $request)
    {
        $module = $this->abortUnlessModuleAllowed('document-verification');

        // Landing from a notification: ?document=ID. Make sure that document
        // is actually visible, whatever its current status is.
        $focusId = $request->integer('document') ?: null;
        $focus = $focusId ? EmployeeDocument::find($focusId) : null;

        $status = $request->string('status')->toString();
        if ($status === '') {
            $status = $focus ? 'all' : 'pending';
        }
        $type = $request->string('type')->toString();
        $search = trim($request->string('q')->toString());

        $query = EmployeeDocument::with(['employee.user', 'employee.department', 'verifiedBy']);

        if (in_array($status, ['pending', 'verified', 'rejected'], true)) {
            $query->where('status', $status);
        }

        if ($type !== '') {
            $query->where('document_type', $type);
        }

        if ($search !== '') {
            $query->whereHas('employee', function ($e) use ($search) {
                $e->where('employee_code', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $documents = $query
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'rejected' THEN 2 ELSE 3 END")
            ->orderByDesc('uploaded_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $counts = EmployeeDocument::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('hr.modules.document-verification', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'document-verification',
            'documents' => $documents,
            'statusFilter' => $status,
            'typeFilter' => $type,
            'search' => $search,
            'focusId' => $focus?->id,
            'hasRemarks' => $this->hasRemarksColumn(),
            'documentTypes' => EmployeeDocument::query()->distinct()->orderBy('document_type')->pluck('document_type'),
            'counts' => [
                'pending' => (int) ($counts['pending'] ?? 0),
                'verified' => (int) ($counts['verified'] ?? 0),
                'rejected' => (int) ($counts['rejected'] ?? 0),
            ],
        ]));
    }

    /**
     * Stream the file inline so HR can look at it without downloading.
     * Files live on the private disk; the extension (and so the content type)
     * is one we wrote ourselves at upload time.
     */
    public function preview(EmployeeDocument $document)
    {
        $this->abortUnlessModuleAllowed('document-verification');

        $diskName = Storage::disk('local')->exists($document->file_path) ? 'local' : 'public';
        abort_unless(Storage::disk($diskName)->exists($document->file_path), 404, 'The uploaded file could not be found.');

        $ext = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
        $mime = self::MIME_BY_EXT[$ext] ?? null;
        abort_unless($mime, 415);

        $name = Str::slug($document->document_type).'-'.$document->employee_id.'.'.$ext;

        return response()->file(Storage::disk($diskName)->path($document->file_path), [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.$name.'"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    public function decide(Request $request, EmployeeDocument $document)
    {
        $this->abortUnlessModuleAllowed('document-verification');
        // Second lock behind the route's permission middleware: only roles holding
        // document-verification.verify (HR) can verify / reject. The MD can view only.
        abort_unless(
            auth()->user()?->can('document-verification.verify'),
            403,
            'You do not have permission to verify or reject documents.'
        );

        $data = $request->validate([
            'action' => 'required|in:verified,rejected',
            'remarks' => 'nullable|string|max:200|required_if:action,rejected',
        ], [
            'remarks.required_if' => 'Please give a short reason so the employee knows what to fix.',
        ]);

        $document->loadMissing('employee.user');
        $me = $this->currentUser();

        // Nobody signs off their own paperwork, even an admin.
        abort_if(
            $document->employee && (int) $document->employee->user_id === (int) $me->id,
            403,
            'You cannot verify or reject your own document.'
        );

        if ($document->status === $data['action']) {
            return back()->with('status', $document->document_type.' is already '.$data['action'].'.');
        }

        $update = [
            'status' => $data['action'],
            'verified_by' => $me->id,
            'verified_at' => now(),
        ];

        if ($this->hasRemarksColumn()) {
            $update['remarks'] = $data['action'] === 'rejected' ? trim($data['remarks']) : null;
        }

        $document->update($update);

        // Tell the employee, linking to the page where they can act on it.
        if ($document->employee && $document->employee->user) {
            $message = $data['action'] === 'verified'
                ? 'Your '.$document->document_type.' was verified.'
                : 'Your '.$document->document_type.' was rejected: '.trim($data['remarks']).' Please upload it again.';

            Notification::notify(
                $document->employee->user->id,
                'document_'.$data['action'],
                Str::limit($message, 250),
                route('hr.profile.index', [], false)
            );
        }

        // The "uploaded for verification" alert is now handled, so clear it
        // from every admin's bell instead of leaving stale unread badges.
        Notification::where('type', 'document_uploaded')
            ->where('link', 'like', '%document='.$document->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('status', $document->document_type.' for '.($document->employee->user->name ?? 'employee').' marked '.$data['action'].'.');
    }

    private function hasRemarksColumn(): bool
    {
        static $has = null;

        return $has ??= Schema::connection('spc_hr')->hasColumn('employee_documents', 'remarks');
    }
}
