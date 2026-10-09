@extends('hr.layouts.app')

@section('title', $module['title'])

@section('content')
    @php
        $isHr = $role === 'hr_admin' || $role === 'super_admin';
        $pillFor = fn ($st) => ['open' => 'pill-ok', 'pending_approval' => 'pill-warn', 'rejected' => 'pill-bad', 'closed' => 'pill-muted'][$st] ?? 'pill-muted';
    @endphp

    @include('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Growth',
        'heroIcon' => 'fa-solid fa-user-plus',
        'heroSummary' => 'Requisitions, candidate pipeline and onboarding checklists.',
        'heroStats' => [
            ['label' => 'Requisitions', 'icon' => 'fa-solid fa-folder-open', 'value' => $requisitions->count()],
            ['label' => 'Candidates', 'icon' => 'fa-solid fa-users', 'value' => $requisitions->sum(fn ($r) => $r->candidates->count())],
            ['label' => 'Onboarding', 'icon' => 'fa-solid fa-rocket', 'value' => $onboarding->count()],
        ],
    ])

    <div class="content">
        <div class="tabs" style="margin-top:32px;">
            <button type="button" class="tab {{ $activeTab === 'requisitions' ? 'active' : '' }}" data-tab="requisitions" onclick="recruitmentTab(this,'requisitions')">Requisitions @if($pendingCount)({{ $pendingCount }} pending)@endif</button>
            <button type="button" class="tab {{ $activeTab === 'candidates' ? 'active' : '' }}" data-tab="candidates" onclick="recruitmentTab(this,'candidates')">Candidates</button>
            <button type="button" class="tab {{ $activeTab === 'onboarding' ? 'active' : '' }}" data-tab="onboarding" onclick="recruitmentTab(this,'onboarding')">Onboarding ({{ $onboarding->count() }})</button>
            <button type="button" class="tab {{ $activeTab === 'history' ? 'active' : '' }}" data-tab="history" onclick="recruitmentTab(this,'history')">History ({{ $history->count() }})</button>
        </div>

        {{-- ================= Requisitions ================= --}}
        <div class="tabpanel {{ $activeTab === 'requisitions' ? 'active' : '' }}" data-tabpanel="requisitions">
            @if($isHr)
                <div class="card">
                    <div class="widget-head">
                        <div class="wh-ico"><i class="fa-solid fa-file-signature"></i></div>
                        <div>
                            <h3>New job requisition</h3>
                            <p>@if($canApprove)As Super Admin your requisition opens straight away.@else Sent to the Super Admin for approval. Candidates can be added once it is approved.@endif</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('hr.recruitment.requisition.store') }}">
                        @csrf
                        <div class="field-grid">
                            <div class="field full"><label>Job title</label><input name="title" placeholder="e.g. Senior Sales Executive" required></div>
                            <div class="field">
                                <label>Department</label>
                                <select name="department_id">
                                    <option value="">&mdash;</option>
                                    @foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>Designation</label>
                                <select name="designation_id">
                                    <option value="">&mdash;</option>
                                    @foreach($designations as $d)<option value="{{ $d->id }}">{{ $d->title }}</option>@endforeach
                                </select>
                            </div>
                            <div class="field"><label>Openings</label><input type="number" name="openings" value="1" min="1" required></div>
                        </div>
                        <div class="form-actions"><button type="submit" class="btn-primary">{{ $canApprove ? 'Create requisition' : 'Submit for approval' }}</button></div>
                    </form>
                </div>
            @endif

            <div class="table-card" style="margin-top:24px;">
                <div class="tc-head"><h3>All requisitions</h3></div>
                <div class="tc-body">
                    @if($requisitions->isEmpty())
                        <div class="empty-widget">
                            <div class="ew-ico"><i class="fa-solid fa-user-plus"></i></div>
                            <b>No requisitions yet</b>
                            <span>Create the first job requisition above.</span>
                        </div>
                    @else
                        <table>
                            <thead>
                                <tr>
                                    <th>Position</th><th>Department</th><th>Openings</th><th>Filled</th><th>Candidates</th>
                                    <th>Status</th><th>Raised by</th><th>Date</th><th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requisitions as $req)
                                    <tr>
                                        <td><b>{{ $req->title }}</b>@if($req->designation)<br><small>{{ $req->designation->title }}</small>@endif</td>
                                        <td>{{ $req->department->name ?? '—' }}</td>
                                        <td>{{ $req->openings }}</td>
                                        <td>{{ $req->candidates->where('stage', 'hired')->count() }} / {{ $req->openings }}</td>
                                        <td>{{ $req->candidates->count() }}</td>
                                        <td><span class="pill {{ $pillFor($req->status) }}">{{ ucfirst(str_replace('_', ' ', $req->status)) }}</span>
                                            @if($req->status === 'rejected' && $req->decision_remarks)<br><small>{{ $req->decision_remarks }}</small>@endif</td>
                                        <td>{{ $req->requestedBy->name ?? '—' }}</td>
                                        <td>{{ $req->created_at ? \Illuminate\Support\Carbon::parse($req->created_at)->format('d M Y') : '—' }}</td>
                                        <td style="white-space:nowrap;">
                                            @if($req->status === 'pending_approval' && $canApprove)
                                                <form method="POST" action="{{ route('hr.recruitment.requisition.decide', $req) }}" style="display:flex;gap:6px;align-items:center;">
                                                    @csrf
                                                    <input name="remarks" maxlength="255" placeholder="Remarks" style="width:130px;">
                                                    <button type="submit" name="decision" value="approve" class="btn-primary">Approve</button>
                                                    <button type="submit" name="decision" value="reject" class="btn-secondary" onclick="return confirm('Reject this requisition?')">Reject</button>
                                                </form>
                                            @elseif($req->status === 'open')
                                                <a href="{{ route('hr.recruitment.index', ['requisition' => $req->id, 'tab' => 'candidates']) }}">Candidates &rarr;</a>
                                            @elseif($req->status === 'closed')
                                                <a href="{{ route('hr.recruitment.index', ['requisition' => $req->id, 'tab' => 'candidates']) }}">View</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        {{-- ================= Candidates ================= --}}
        <div class="tabpanel {{ $activeTab === 'candidates' ? 'active' : '' }}" data-tabpanel="candidates">
            @if($requisitions->isEmpty())
                <div class="table-card"><div class="tc-body"><div class="empty-widget">
                    <div class="ew-ico"><i class="fa-solid fa-user-plus"></i></div>
                    <b>No requisitions yet</b>
                    <span>Create a requisition in the Requisitions tab to start the pipeline.</span>
                </div></div></div>
            @else
                <form method="GET" action="{{ route('hr.recruitment.index') }}" style="margin:0 0 20px;max-width:480px;" onchange="this.submit()">
                    <input type="hidden" name="tab" value="candidates">
                    <div class="field">
                        <label><i class="fa-solid fa-filter" style="color:var(--brand);margin-right:6px;font-size:11px;"></i>Requisition</label>
                        <select name="requisition">
                            @foreach($requisitions as $req)
                                <option value="{{ $req->id }}" @selected($selectedRequisition && $selectedRequisition->id === $req->id)>
                                    {{ $req->title }} &mdash; {{ ucfirst(str_replace('_', ' ', $req->status)) }} ({{ $req->candidates->where('stage', 'hired')->count() }}/{{ $req->openings }} hired)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @if($selectedRequisition)
                    @php $rs = $selectedRequisition->status; @endphp

                    @if($rs === 'open' && $isHr)
                        <div class="card">
                            <div class="widget-head">
                                <div class="wh-ico"><i class="fa-solid fa-user-plus"></i></div>
                                <div><h3>New candidate</h3><p>{{ $selectedRequisition->title }}</p></div>
                            </div>
                            <form method="POST" action="{{ route('hr.recruitment.candidate.store', $selectedRequisition) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="field-grid">
                                    <div class="field"><label>Name</label><input name="name" value="{{ old('name') }}" maxlength="150" required></div>
                                    <div class="field"><label>Phone</label><input name="phone" value="{{ old('phone') }}" maxlength="20" inputmode="tel" required></div>
                                    <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" maxlength="150"></div>
                                    <div class="field">
                                        <label>Source</label>
                                        <select name="source" required>
                                            <option value="">&mdash;</option>
                                            @foreach($sources as $key => $label)<option value="{{ $key }}" @selected(old('source') === $key)>{{ $label }}</option>@endforeach
                                        </select>
                                    </div>
                                    <div class="field full"><label>Resume (PDF, DOC or DOCX, up to 5 MB)</label><input type="file" name="resume" accept=".pdf,.doc,.docx"></div>
                                </div>
                                <div class="form-actions"><button type="submit" class="btn-primary">Add candidate</button></div>
                            </form>
                        </div>
                    @elseif($rs === 'pending_approval')
                        <div class="flash-errors" style="background:var(--warn-soft);color:#8A5A10;border-color:rgba(180,83,9,.2);">This requisition is waiting for Super Admin approval. Candidates can be added once it is open.</div>
                    @elseif($rs === 'rejected')
                        <div class="flash-errors">This requisition was rejected{{ $selectedRequisition->decision_remarks ? ': '.$selectedRequisition->decision_remarks : '.' }}</div>
                    @elseif($rs === 'closed')
                        <div class="flash" style="background:#EDF3EF;color:#52645B;border-color:rgba(18,58,40,.1);">Closed &mdash; all {{ $selectedRequisition->openings }} {{ $selectedRequisition->openings == 1 ? 'opening is' : 'openings are' }} filled.</div>
                    @endif

                    <div class="section-head" style="margin-top:24px;">
                        <h2><i class="fa-solid fa-arrow-down-short-wide"></i>Candidate pipeline &mdash; {{ $selectedRequisition->title }}</h2>
                        <span class="hint">Applied → Shortlisted → Interviewed → Offered → Hired</span>
                    </div>
                    <div class="pipeline">
                        @foreach(['applied'=>'Applied','shortlisted'=>'Shortlisted','interviewed'=>'Interviewed','offered'=>'Offered','hired'=>'Hired'] as $stage => $label)
                            <div class="pipe-col">
                                <div class="pipe-head">
                                    <span class="ph-dot" style="background:{{ ['applied'=>'#8FA79B','shortlisted'=>'#5E8D3D','interviewed'=>'#1D6FA5','offered'=>'#A16207','hired'=>'#0E5239'][$stage] }};"></span>
                                    <h4>{{ $label }}</h4>
                                    <span>{{ ($pipeline[$stage] ?? collect())->count() }}</span>
                                </div>
                                @foreach($pipeline[$stage] ?? [] as $c)
                                    <div class="pipe-card">
                                        <div class="pc-av">{{ strtoupper(substr($c->name,0,1)) }}</div>
                                        <div class="name">{{ $c->name }}</div>
                                        <div class="meta"><i class="fa-solid fa-link" style="font-size:9px;margin-right:4px;"></i>{{ $sources[$c->source] ?? ucfirst($c->source ?? 'Direct') }}</div>
                                        @if($c->phone)<div class="meta"><i class="fa-solid fa-phone" style="font-size:9px;margin-right:4px;"></i>{{ $c->phone }}</div>@endif
                                        @if($isHr && $c->resume_path)
                                            <div class="meta"><a href="{{ route('hr.recruitment.candidate.resume', $c) }}"><i class="fa-solid fa-file-arrow-down" style="font-size:9px;margin-right:4px;"></i>Resume</a></div>
                                        @endif
                                        @if($isHr && $stage !== 'hired' && $rs === 'open')
                                            @php $next = $stages[array_search($stage, $stages, true) + 1] ?? null; @endphp
                                            <form method="POST" action="{{ route('hr.recruitment.candidate.stage', $c) }}">
                                                @csrf
                                                <select name="stage" onchange="this.form.submit()">
                                                    <option value="{{ $stage }}" selected>{{ ucfirst($stage) }}</option>
                                                    @if($next)<option value="{{ $next }}">Move to {{ ucfirst($next) }}</option>@endif
                                                    <option value="rejected">Reject</option>
                                                </select>
                                            </form>
                                        @endif
                                        @if($stage === 'hired' && $isHr)
                                            @if($canCreateEmployee)
                                                <a href="{{ route('admin.employees.create', ['candidate' => $c->id, 'name' => $c->name, 'email' => $c->email]) }}" class="btn-primary" style="display:block;text-align:center;margin-top:8px;font-size:12px;padding:6px 8px;">Create employee &rarr;</a>
                                            @else
                                                <div class="meta" style="margin-top:6px;">Needs an admin with employee-create access.</div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                                @if(($pipeline[$stage] ?? collect())->isEmpty())
                                    <div style="text-align:center;padding:14px 6px;color:#A7C9B8;font-size:11.5px;"><i class="fa-regular fa-circle" style="opacity:.6;"></i></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        {{-- ================= Onboarding ================= --}}
        <div class="tabpanel {{ $activeTab === 'onboarding' ? 'active' : '' }}" data-tabpanel="onboarding">
            @forelse($onboarding as $oc)
                @php $items = $oc->checklistItems->sortBy('id')->values(); @endphp
                <div class="section-head" style="margin-top:{{ $loop->first ? '0' : '24px' }};">
                    <h2><i class="fa-solid fa-rocket"></i>{{ $oc->name }} &mdash; {{ $oc->requisition->title ?? 'Requisition' }}</h2>
                    <span class="hint">{{ $items->where('is_completed', true)->count() }} of {{ $items->count() }} steps done</span>
                </div>
                <div class="card">
                    <ul class="checklist">
                        @foreach($items as $item)
                            <li class="{{ $item->is_completed ? 'done' : '' }}">
                                @if($isHr)
                                    <form method="POST" action="{{ route('hr.recruitment.checklist.toggle', $item) }}">
                                        @csrf
                                        <button type="submit" class="num">{{ $item->is_completed ? '✓' : $loop->iteration }}</button>
                                    </form>
                                @else
                                    <span class="num">{{ $item->is_completed ? '✓' : $loop->iteration }}</span>
                                @endif
                                {{ $item->item }}
                            </li>
                        @endforeach
                    </ul>
                    @if($isHr)
                        <div class="form-actions" style="margin-top:14px;">
                            @if($canCreateEmployee)
                                <a href="{{ route('admin.employees.create', ['candidate' => $oc->id, 'name' => $oc->name, 'email' => $oc->email]) }}" class="btn-primary">Create employee &rarr;</a>
                            @else
                                <span class="hint">Needs an admin with employee-create access to create the employee.</span>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="table-card"><div class="tc-body"><div class="empty-widget">
                    <div class="ew-ico"><i class="fa-solid fa-rocket"></i></div>
                    <b>No one is onboarding</b>
                    <span>Hired candidates appear here until their employee profile is created.</span>
                </div></div></div>
            @endforelse
        </div>

        {{-- ================= History ================= --}}
        <div class="tabpanel {{ $activeTab === 'history' ? 'active' : '' }}" data-tabpanel="history">
            <div class="table-card">
                <div class="tc-head"><h3>Hired and added as employees</h3></div>
                <div class="tc-body">
                    @if($history->isEmpty())
                        <div class="empty-widget">
                            <div class="ew-ico"><i class="fa-solid fa-clock-rotate-left"></i></div>
                            <b>Nothing here yet</b>
                            <span>Hired candidates move here once their employee profile is created.</span>
                        </div>
                    @else
                        <table>
                            <thead><tr><th>Candidate</th><th>Position</th><th>Source</th><th>Employee</th><th>Hired on</th></tr></thead>
                            <tbody>
                                @foreach($history as $h)
                                    <tr>
                                        <td><b>{{ $h->name }}</b>@if($h->phone)<br><small>{{ $h->phone }}</small>@endif</td>
                                        <td>{{ $h->requisition->title ?? '—' }}</td>
                                        <td>{{ $sources[$h->source] ?? ucfirst($h->source ?? 'Direct') }}</td>
                                        <td>{{ $h->convertedEmployee->user->name ?? 'Employee #'.$h->converted_employee_id }}</td>
                                        <td>{{ $h->updated_at ? $h->updated_at->format('d M Y') : '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    function recruitmentTab(btn, name) {
        const scope = document.querySelector('.content');
        scope.querySelectorAll(':scope > .tabs .tab').forEach(t => t.classList.remove('active'));
        scope.querySelectorAll(':scope > .tabpanel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        scope.querySelectorAll('[data-tabpanel="' + name + '"]').forEach(p => p.classList.add('active'));
        // Keep the tab in the URL so forms that redirect back land on the same tab.
        const url = new URL(window.location);
        url.searchParams.set('tab', name);
        history.replaceState(null, '', url);
    }
    </script>
@endsection
