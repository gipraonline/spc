@extends('hr.layouts.app')

@section('title', $module['title'])

@section('content')
<style>
/* ---- Tabs ---- */
.ctabs {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin: 0 0 18px;
    border-bottom: 1px solid var(--line);
}

.ctab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 16px;
    margin-bottom: -1px;
    border: 0;
    border-bottom: 2px solid transparent;
    background: transparent;
    color: var(--text-muted);
    font-family: var(--font-body);
    font-size: 13.5px;
    font-weight: 500;
    cursor: pointer;
    white-space: nowrap;
    transition: color .15s, border-color .15s, background .15s;
}

.ctab i {
    font-size: 13px;
}

.ctab:hover {
    color: var(--brand-ink);
    background: var(--brand-softer);
}

.ctab:focus-visible {
    outline: 2px solid var(--brand-bright);
    outline-offset: -2px;
}

.ctab.is-active {
    color: var(--brand-ink);
    border-bottom-color: var(--brand);
    font-weight: 600;
}

.ctab .ctab-count {
    min-width: 20px;
    padding: 1px 7px;
    border-radius: 999px;
    background: var(--brand-softer);
    color: var(--brand-ink);
    font-size: 11.5px;
    font-weight: 600;
    text-align: center;
}

.cpanel[hidden] {
    display: none;
}

@media (max-width: 640px) {
    .ctabs {
        flex-wrap: nowrap;
        overflow-x: auto;
    }
}
</style>
@php
    $canSeeAll = $role === 'hr_admin' || $role === 'super_admin';

    // Tabs this person can see: key => [label, icon, count badge]
    $tabs = [];
    if ($role !== 'super_admin') {
        $tabs['request'] = ['My requests', 'fa-solid fa-house-laptop', $ownRequests->count()];
    }
    if ($role !== 'employee') {
        $tabs['approvals'] = ['Approvals', 'fa-solid fa-clipboard-check', $pendingApprovals->count() > 0 ? $pendingApprovals->count() : null];
    }
    if ($canSeeAll) {
        $tabs['all'] = ['All requests', 'fa-solid fa-table-list', null];
    }

    // Open ?tab= if given. Applying a filter, paging or exporting keeps the
    // register open even if the tab param is missing.
    $requestedTab = request()->query('tab');
    $hasRegisterQuery = request()->hasAny(['dept', 'status', 'employee', 'date_from', 'date_to', 'page']);
    if (is_string($requestedTab) && isset($tabs[$requestedTab])) {
        $activeTab = $requestedTab;
    } elseif ($hasRegisterQuery && isset($tabs['all'])) {
        $activeTab = 'all';
    } else {
        $activeTab = array_key_first($tabs);
    }
@endphp

@include('hr.partials.topbar', [
    'title' => $module['title'],
    'eyebrow' => 'Workforce',
    'heroIcon' => 'fa-solid fa-house-laptop',
    'heroSummary' => 'Request work from home and manage your team\'s requests.',
    'heroStats' => $role !== 'super_admin' ? [
        ['label' => 'My requests', 'icon' => 'fa-regular fa-paper-plane', 'value' => $ownRequests->count()],
        ['label' => 'To approve', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pendingApprovals->count()],
    ] : [
        ['label' => 'To approve', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pendingApprovals->count()],
        ['label' => 'All requests', 'icon' => 'fa-solid fa-layer-group', 'value' => $wfhCounts['total'] ?? 0],
    ],
])

<div class="content">
    {{-- ===== Tab bar (only when there is more than one tab) ===== --}}
    @if(count($tabs) > 1)
        <div class="ctabs" role="tablist" aria-label="Work from home sections">
            @foreach($tabs as $key => [$tabLabel, $tabIcon, $tabCount])
                <button type="button" class="ctab {{ $key === $activeTab ? 'is-active' : '' }}"
                    role="tab" id="ctab-{{ $key }}" data-tab="{{ $key }}"
                    aria-controls="cpanel-{{ $key }}" aria-selected="{{ $key === $activeTab ? 'true' : 'false' }}"
                    tabindex="{{ $key === $activeTab ? '0' : '-1' }}">
                    <i class="{{ $tabIcon }}"></i>{{ $tabLabel }}
                    @if($tabCount !== null)<span class="ctab-count">{{ $tabCount }}</span>@endif
                </button>
            @endforeach
        </div>
    @endif

    {{-- ===== Tab 1: My requests (request form + own history) ===== --}}
    @if($role !== 'super_admin')
    <div class="cpanel" id="cpanel-request" data-panel="request" role="tabpanel" aria-labelledby="ctab-request" @if($activeTab !== 'request') hidden @endif>
        <div class="grid-2">
            <div class="card">
                <div class="widget-head">
                    <div class="wh-ico"><i class="fa-solid fa-house-laptop"></i></div>
                    <div>
                        <h3>Request work from home</h3>
                        <p>Routed to your reporting manager for approval.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('hr.wfh.store') }}">
                    @csrf
                    <div class="field-grid">
                        <div class="field"><label>From</label><input type="date" name="start_date" required></div>
                        <div class="field"><label>To</label><input type="date" name="end_date" required></div>
                        <div class="field"><label>Location</label><input name="location" placeholder="e.g. Home — Kochi"></div>
                        <div class="field"><label>Contact number</label><input name="contact_number" placeholder="Reachable number"></div>
                        <div class="field full"><label>Reason</label><textarea name="reason" placeholder="Brief reason"></textarea></div>
                    </div>
                    <div class="form-actions"><button type="submit" class="btn-primary">Submit WFH request</button></div>
                </form>
            </div>

            <div class="table-card">
                <div class="tc-head">
                    <h3><span class="wh-ico"><i class="fa-regular fa-clock"></i></span>Your recent WFH requests</h3>
                    <span class="pill pill-muted">{{ $ownRequests->count() }} total</span>
                </div>
                <div class="tc-body">
                    @if($ownRequests->isEmpty())
                    <div class="empty-widget">
                        <div class="ew-ico"><i class="fa-regular fa-folder-open"></i></div>
                        <b>No WFH requests yet</b>
                        <span>Submit your first request from the form.</span>
                    </div>
                    @else
                    <table>
                        <thead>
                            <tr>
                                <th>Dates</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ownRequests as $r)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($r->start_date)->format('M j, Y') }}&ndash;{{ \Illuminate\Support\Carbon::parse($r->end_date)->format('M j, Y') }}</td>
                                <td>
                                    @php $p = ['approved'=>'pill-ok','pending'=>'pill-warn','rejected'=>'pill-bad','cancelled'=>'pill-muted'][$r->status] ?? 'pill-muted'; @endphp
                                    <span class="pill {{ $p }}">{{ $r->status === 'pending' ? 'Awaiting approval' : ucfirst($r->status) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== Tab 2: WFH requests to approve ===== --}}
    @if($role !== 'employee')
    <div class="cpanel" id="cpanel-approvals" data-panel="approvals" role="tabpanel" aria-labelledby="ctab-approvals" @if($activeTab !== 'approvals') hidden @endif>
        <p class="field-hint" style="margin:0 0 12px;">{{ $role === 'manager' ? 'From your direct reports' : 'Across the organization' }}</p>
        <div class="table-card">
            <div class="tc-head">
                <h3><span class="wh-ico"><i class="fa-solid fa-list-check"></i></span>Pending approvals</h3>
                <span class="pill {{ $pendingApprovals->isNotEmpty() ? 'pill-warn' : 'pill-ok' }}">{{ $pendingApprovals->count() }} waiting</span>
            </div>
            <div class="tc-body">
                @if($pendingApprovals->isEmpty())
                <div class="empty-widget">
                    <div class="ew-ico"><i class="fa-solid fa-circle-check"></i></div>
                    <b>All caught up</b>
                    <span>No WFH requests waiting for your approval.</span>
                </div>
                @else
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Dates</th>
                            <th>Location</th>
                            <th>Reason</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingApprovals as $r)
                        <tr>
                            <td>
                                <div class="cell-emp">
                                    <div class="av">{{ strtoupper(substr($r->employee->user->name,0,1).substr(strstr($r->employee->user->name,' ') ?: '',1,1)) }}</div>
                                    <div><b>{{ $r->employee->user->name }}</b><span>{{ $r->employee->department->name ?? '—' }}</span></div>
                                </div>
                            </td>
                            <td>{{ \Illuminate\Support\Carbon::parse($r->start_date)->format('M j, Y') }}&ndash;{{ \Illuminate\Support\Carbon::parse($r->end_date)->format('M j, Y') }}</td>
                            <td>{{ $r->location ?: '—' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($r->reason ?: '—', 30) }}</td>
                            <td>
                                <div class="row-actions">
                                    <form method="POST" action="{{ route('hr.wfh.decide', $r) }}">@csrf<input type="hidden" name="action" value="approve"><button class="approve" type="submit">Approve</button></form>
                                    <form method="POST" action="{{ route('hr.wfh.decide', $r) }}">@csrf<input type="hidden" name="action" value="reject"><button class="reject" type="submit">Reject</button></form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ===== Tab 3: All WFH requests (HR Admin / Super Admin) ===== --}}
    @if($canSeeAll)
    <div class="cpanel" id="cpanel-all" data-panel="all" role="tabpanel" aria-labelledby="ctab-all" @if($activeTab !== 'all') hidden @endif>
        <p class="field-hint" style="margin:0 0 12px;">Full register &mdash; filter by department, status, employee or date</p>
        <div class="stat-tiles">
            <div class="stat-tile"><div class="st-ico"><i class="fa-solid fa-layer-group"></i></div><div><b>{{ $wfhCounts['total'] }}</b><span>Total</span></div></div>
            <div class="stat-tile alt"><div class="st-ico"><i class="fa-solid fa-hourglass-half"></i></div><div><b>{{ $wfhCounts['pending'] }}</b><span>Pending</span></div></div>
            <div class="stat-tile"><div class="st-ico"><i class="fa-solid fa-circle-check"></i></div><div><b>{{ $wfhCounts['approved'] }}</b><span>Approved</span></div></div>
            <div class="stat-tile warn"><div class="st-ico"><i class="fa-solid fa-circle-xmark"></i></div><div><b>{{ $wfhCounts['rejected'] }}</b><span>Rejected</span></div></div>
        </div>

        <form method="GET" action="{{ route('hr.wfh.index') }}" class="filters" style="display:flex;align-items:end;gap:10px;flex-wrap:wrap;margin:0 0 16px;">
            <input type="hidden" name="tab" value="all">
            <div class="field" style="min-width:190px;">
                <label for="wfhDepartmentFilter">Department</label>
                <select id="wfhDepartmentFilter" name="dept">
                    <option value="">All Departments</option>
                    @foreach($wfhDepartments as $department)
                    <option value="{{ $department->id }}" @selected((string) $wfhDeptFilter===(string) $department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field" style="min-width:160px;">
                <label for="wfhStatusFilter">Status</label>
                <select id="wfhStatusFilter" name="status">
                    <option value="all" @selected($wfhStatusFilter==='all')>All Statuses</option>
                    <option value="pending" @selected($wfhStatusFilter==='pending')>Pending</option>
                    <option value="approved" @selected($wfhStatusFilter==='approved')>Approved</option>
                    <option value="rejected" @selected($wfhStatusFilter==='rejected')>Rejected</option>
                </select>
            </div>
            <div class="field" style="min-width:220px;">
                <label for="wfhEmployeeFilter">Employee</label>
                <input id="wfhEmployeeFilter" type="search" name="employee" placeholder="Search employee..." value="{{ $wfhEmployeeFilter }}">
            </div>
            <div class="field" style="min-width:150px;">
                <label for="wfhDateFrom">From</label>
                <input id="wfhDateFrom" name="date_from" type="date" value="{{ $wfhDateFrom }}">
            </div>
            <div class="field" style="min-width:150px;">
                <label for="wfhDateTo">To</label>
                <input id="wfhDateTo" name="date_to" type="date" value="{{ $wfhDateTo }}">
            </div>
            <div class="field" style="min-width:150px;">
                <label for="wfhMonthFilter">Or pick a month</label>
                <input id="wfhMonthFilter" type="month" onchange="fillWfhMonthRange(this.value)">
            </div>
            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn-primary">Apply</button>
                <button type="button" class="btn-secondary" onclick="exportWfhRequests()" style="display:inline-flex;align-items:center;gap:7px;"><i class="fa-solid fa-file-csv"></i>Export</button>
            </div>
        </form>

        <script>
        function fillWfhMonthRange(value) {
            if (!value) return;
            const [year, month] = value.split('-').map(Number);
            const first = new Date(year, month - 1, 1);
            const last = new Date(year, month, 0);
            const fmt = d => d.toISOString().slice(0, 10);
            document.getElementById('wfhDateFrom').value = fmt(first);
            document.getElementById('wfhDateTo').value = fmt(last);
        }
        </script>

        <div class="table-card">
            <div class="tc-body" style="padding-top:6px;">
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Dates</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wfhRequestsPage as $r)
                        <tr>
                            <td>
                                <div class="cell-emp">
                                    <div class="av">{{ strtoupper(substr($r->employee->user->name,0,1)) }}</div>
                                    <div><b>{{ $r->employee->user->name }}</b></div>
                                </div>
                            </td>
                            <td>{{ $r->employee->department->name ?? '—' }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($r->start_date)->format('d M') }} &ndash; {{ \Illuminate\Support\Carbon::parse($r->end_date)->format('d M Y') }}</td>
                            <td>{{ $r->location ?: '—' }}</td>
                            <td>
                                @php $p = ['approved'=>'pill-ok','pending'=>'pill-warn','rejected'=>'pill-bad','cancelled'=>'pill-muted'][$r->status] ?? 'pill-muted'; @endphp
                                <span class="pill {{ $p }}">{{ $r->status === 'pending' ? 'Awaiting approval' : ucfirst($r->status) }}</span>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($r->reason ?: '—', 30) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6">
                            <div class="empty-widget">
                                <div class="ew-ico"><i class="fa-solid fa-house-laptop"></i></div>
                                <b>No WFH requests match these filters</b>
                                <span>Try adjusting the filters above.</span>
                            </div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
                @if($wfhRequestsPage)
                    {{ $wfhRequestsPage->appends(['tab' => 'all'])->links() }}
                @endif
            </div>
        </div>

        <script>
        function exportWfhRequests() {
            const params = new URLSearchParams(window.location.search);
            params.set('dept', document.getElementById('wfhDepartmentFilter').value);
            params.set('status', document.getElementById('wfhStatusFilter').value);
            params.set('employee', document.getElementById('wfhEmployeeFilter').value);
            params.set('date_from', document.getElementById('wfhDateFrom').value);
            params.set('date_to', document.getElementById('wfhDateTo').value);
            params.set('export', 'csv');
            window.location.href = "{{ route('hr.wfh.index') }}?" + params.toString();
        }
        </script>
    </div>
    @endif

    <p class="access-note">
        Visible to:
        @foreach($module['roles'] as $r)
        {{ $roles[$r]['label'] }}{{ !$loop->last ? ', ' : '' }}
        @endforeach
    </p>
</div>

<script>
(function () {
    var tabs = Array.prototype.slice.call(document.querySelectorAll('.ctab'));
    var panels = Array.prototype.slice.call(document.querySelectorAll('.cpanel'));
    if (!tabs.length) return;

    function show(key, updateUrl) {
        tabs.forEach(function (t) {
            var on = t.getAttribute('data-tab') === key;
            t.classList.toggle('is-active', on);
            t.setAttribute('aria-selected', on ? 'true' : 'false');
            t.tabIndex = on ? 0 : -1;
        });
        panels.forEach(function (p) {
            p.hidden = p.getAttribute('data-panel') !== key;
        });
        if (updateUrl && window.history && window.history.replaceState) {
            var url = new URL(window.location.href);
            url.searchParams.set('tab', key);
            window.history.replaceState(null, '', url);
        }
    }

    tabs.forEach(function (t, i) {
        t.addEventListener('click', function () { show(t.getAttribute('data-tab'), true); });
        t.addEventListener('keydown', function (e) {
            var next = null;
            if (e.key === 'ArrowRight') next = tabs[(i + 1) % tabs.length];
            if (e.key === 'ArrowLeft') next = tabs[(i - 1 + tabs.length) % tabs.length];
            if (next) { e.preventDefault(); next.focus(); show(next.getAttribute('data-tab'), true); }
        });
    });
})();
</script>
@endsection