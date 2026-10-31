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
    $fmt = fn($n) => rtrim(rtrim(number_format($n, 1), '0'), '.') ?: '0';
    $canSeeAll = $role === 'hr_admin' || $role === 'super_admin';

    // Tabs this person can see: key => [label, icon, count badge]
    $tabs = [];
    if ($role !== 'super_admin') {
        $tabs['mine'] = ['My leave', 'fa-regular fa-calendar-days', $ownRequests->count()];
    }
    if ($role !== 'employee') {
        $tabs['approvals'] = ['Approvals', 'fa-solid fa-clipboard-check', $pendingApprovals->count() > 0 ? $pendingApprovals->count() : null];
    }
    if ($canSeeAll) {
        $tabs['register'] = ['Leave register', 'fa-solid fa-table-list', null];
    }

    // Open ?tab= if given. Applying a filter, paging or exporting keeps the
    // register open even if the tab param is missing.
    $requestedTab = request()->query('tab');
    $hasRegisterQuery = request()->hasAny(['dept', 'status', 'employee', 'date_from', 'date_to', 'page']);
    if (is_string($requestedTab) && isset($tabs[$requestedTab])) {
        $activeTab = $requestedTab;
    } elseif ($hasRegisterQuery && isset($tabs['register'])) {
        $activeTab = 'register';
    } else {
        $activeTab = array_key_first($tabs);
    }
@endphp

@include('hr.partials.topbar', [
    'title' => $module['title'],
    'eyebrow' => 'Workforce',
    'heroIcon' => 'fa-regular fa-calendar-days',
    'heroSummary' => 'Apply for leave, track balances, and approve your team\'s requests.',
    'heroStats' => $role !== 'super_admin' ? [
        ['label' => 'My requests', 'icon' => 'fa-regular fa-paper-plane', 'value' => $ownRequests->count()],
        ['label' => 'To approve', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pendingApprovals->count()],
        ['label' => 'Days left', 'icon' => 'fa-solid fa-scale-balanced', 'value' => rtrim(rtrim(number_format($leaveSummary->where('paid', true)->sum('remaining'),1),'0'),'.').'d'],
    ] : [
        ['label' => 'To approve', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pendingApprovals->count()],
    ],
])

<div class="content">
    {{-- ===== Tab bar (only when there is more than one tab) ===== --}}
    @if(count($tabs) > 1)
        <div class="ctabs" role="tablist" aria-label="Leave sections">
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

    {{-- ===== Tab 1: My leave (balances + apply form + own history) ===== --}}
    @if($role !== 'super_admin')
    <div class="cpanel" id="cpanel-mine" data-panel="mine" role="tabpanel" aria-labelledby="ctab-mine" @if($activeTab !== 'mine') hidden @endif>
        @if($leaveSummary->isNotEmpty())
        <div class="stat-tiles" style="grid-template-columns:repeat(auto-fit,minmax(170px,1fr));">
            @foreach($leaveSummary as $row)
            @if($row['unlimited'])
            <div class="ring-card" data-leave-card="{{ $row['type']->id }}">
                <div class="ring" style="--pct:100;"><b style="font-size:22px;">&infin;</b></div>
                <h4>{{ $row['type']->name }}</h4>
                <small>Unlimited &middot; {{ $fmt($row['used']) }}d taken{{ $row['pending'] > 0 ? ' · '.$fmt($row['pending']).'d pending' : '' }}</small>
            </div>
            @else
            @php
                // Headline = what you can still apply for: balance left minus days
                // already waiting for approval on pending requests.
                $avail = $row['available'];
                $pct = $row['entitled'] > 0 ? round($avail / $row['entitled'] * 100) : 0;
            @endphp
            <div class="ring-card" data-leave-card="{{ $row['type']->id }}" style="{{ $row['entitled'] <= 0 ? 'opacity:.6;' : '' }}">
                <div class="ring" style="--pct:{{ $pct }};"><b>{{ $fmt($avail) }}</b></div>
                <h4>{{ $row['type']->name }}</h4>
                @if($row['entitled'] <= 0)
                    <small>No days allocated</small>
                @else
                    <small>{{ $fmt($avail) }} of {{ $fmt($row['entitled']) }}d left{{ $row['pending'] > 0 ? ' · '.$fmt($row['pending']).'d awaiting approval' : '' }}</small>
                @endif
            </div>
            @endif
            @endforeach
        </div>
        @endif

        <div class="grid-2">
            <div class="card">
                <div class="widget-head">
                    <div class="wh-ico"><i class="fa-solid fa-plane-departure"></i></div>
                    <div>
                        <h3>Apply for leave</h3>
                        <p>Submitted requests are routed to your reporting manager.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('hr.leave.apply') }}">
                    @csrf
                    <div class="field-grid">
                        <div class="field">
                            <label>Leave type</label>
                            <select name="leave_type_id" id="leaveTypeSel" required>
                                @foreach($leaveSummary as $row)
                                <option value="{{ $row['type']->id }}" data-unlimited="{{ $row['unlimited'] ? 1 : 0 }}" data-available="{{ $row['unlimited'] ? '' : $row['available'] }}" @disabled(! $row['unlimited'] && $row['available'] <= 0)>
                                    {{ $row['type']->name }} &mdash;
                                    @if($row['unlimited']) unlimited
                                    @else {{ $fmt($row['available']) }}d available
                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field"><label>From</label><input type="date" name="start_date" id="leaveFrom" required></div>
                        <div class="field"><label>To</label><input type="date" name="end_date" id="leaveTo" required></div>
                        <div class="field full"><label>Reason</label><textarea name="reason"
                                placeholder="Brief reason"></textarea></div>
                    </div>
                    <p id="leaveHint" class="card-note" style="margin:12px 0 0;display:none;font-weight:600;"></p>
                    <div class="form-actions"><button type="submit" class="btn-primary">Submit leave request</button></div>
                </form>
            </div>

            <div class="table-card">
                <div class="tc-head">
                    <h3><span class="wh-ico"><i class="fa-regular fa-clock"></i></span>Your recent applications</h3>
                    <span class="pill pill-muted">{{ $ownRequests->count() }} total</span>
                </div>
                <div class="tc-body">
                    @if($ownRequests->isEmpty())
                    <div class="empty-widget">
                        <div class="ew-ico"><i class="fa-regular fa-folder-open"></i></div>
                        <b>No applications yet</b>
                        <span>Your leave requests will appear here.</span>
                    </div>
                    @else
                    <table>
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Dates</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ownRequests as $r)
                            <tr>
                                <td><b>{{ $r->leaveType->name }}</b> &middot; {{ rtrim(rtrim(number_format($r->days,1),'0'),'.') }}d</td>
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

    {{-- ===== Tab 2: Leave requests to approve ===== --}}
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
                    <span>No leave requests waiting for your approval.</span>
                </div>
                @else
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Dates</th>
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
                            <td>{{ $r->leaveType->name }} &middot; {{ rtrim(rtrim(number_format($r->days,1),'0'),'.') }}d</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($r->start_date)->format('M j, Y') }}&ndash;{{ \Illuminate\Support\Carbon::parse($r->end_date)->format('M j, Y') }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($r->reason ?: '—', 30) }}</td>
                            <td>
                                <div class="row-actions">
                                    <form method="POST" action="{{ route('hr.leave.decide', $r) }}">@csrf<input type="hidden" name="action" value="approve"><button class="approve" type="submit">Approve</button></form>
                                    <form method="POST" action="{{ route('hr.leave.decide', $r) }}">@csrf<input type="hidden" name="action" value="reject"><button class="reject" type="submit">Reject</button></form>
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

    {{-- ===== Tab 3: Organization leave register (HR Admin / Super Admin) ===== --}}
    @if($canSeeAll)
    <div class="cpanel" id="cpanel-register" data-panel="register" role="tabpanel" aria-labelledby="ctab-register" @if($activeTab !== 'register') hidden @endif>
        <p class="field-hint" style="margin:0 0 12px;">All leave requests with department, status &amp; date filters</p>
        <div class="stat-tiles">
            <div class="stat-tile"><div class="st-ico"><i class="fa-solid fa-layer-group"></i></div><div><b>{{ $leaveCounts['total'] }}</b><span>Total requests</span></div></div>
            <div class="stat-tile alt"><div class="st-ico"><i class="fa-solid fa-hourglass-half"></i></div><div><b>{{ $leaveCounts['pending'] }}</b><span>Pending</span></div></div>
            <div class="stat-tile"><div class="st-ico"><i class="fa-solid fa-circle-check"></i></div><div><b>{{ $leaveCounts['approved'] }}</b><span>Approved</span></div></div>
            <div class="stat-tile warn"><div class="st-ico"><i class="fa-solid fa-circle-xmark"></i></div><div><b>{{ $leaveCounts['rejected'] }}</b><span>Rejected</span></div></div>
        </div>

        <form method="GET" action="{{ route('hr.leave.index') }}" class="filters" style="display:flex;align-items:end;gap:10px;flex-wrap:wrap;margin:0 0 16px;">
            <input type="hidden" name="tab" value="register">
            <div class="field" style="min-width:190px;">
                <label for="leaveDeptFilter">Department</label>
                <select id="leaveDeptFilter" name="dept">
                    <option value="">All Departments</option>
                    @foreach($leaveDepartments as $department)
                    <option value="{{ $department->id }}" @selected((string) $leaveDeptFilter===(string) $department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field" style="min-width:160px;">
                <label for="leaveStatusFilter">Status</label>
                <select id="leaveStatusFilter" name="status">
                    <option value="all" @selected($leaveStatusFilter==='all')>All Statuses</option>
                    <option value="pending" @selected($leaveStatusFilter==='pending')>Pending</option>
                    <option value="approved" @selected($leaveStatusFilter==='approved')>Approved</option>
                    <option value="rejected" @selected($leaveStatusFilter==='rejected')>Rejected</option>
                </select>
            </div>
            <div class="field" style="min-width:210px;">
                <label for="leaveEmployeeFilter">Employee</label>
                <input id="leaveEmployeeFilter" name="employee" type="search" placeholder="Search employee..." value="{{ $leaveEmployeeFilter }}">
            </div>
            <div class="field" style="min-width:150px;">
                <label for="leaveDateFrom">From</label>
                <input id="leaveDateFrom" name="date_from" type="date" value="{{ $leaveDateFrom }}">
            </div>
            <div class="field" style="min-width:150px;">
                <label for="leaveDateTo">To</label>
                <input id="leaveDateTo" name="date_to" type="date" value="{{ $leaveDateTo }}">
            </div>
            <div class="field" style="min-width:150px;">
                <label for="leaveMonthFilter">Or pick a month</label>
                <input id="leaveMonthFilter" type="month" onchange="fillLeaveMonthRange(this.value)">
            </div>
            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn-primary">Apply</button>
                <button type="submit" name="export" value="csv" class="btn-secondary" style="display:inline-flex;align-items:center;gap:7px;"><i class="fa-solid fa-file-csv"></i>Export</button>
            </div>
        </form>

        <script>
        function fillLeaveMonthRange(value) {
            if (!value) return;
            const [year, month] = value.split('-').map(Number);
            const first = new Date(year, month - 1, 1);
            const last = new Date(year, month, 0);
            const fmt = d => d.toISOString().slice(0, 10);
            document.getElementById('leaveDateFrom').value = fmt(first);
            document.getElementById('leaveDateTo').value = fmt(last);
        }
        </script>

        <div class="table-card">
            <div class="tc-body" style="padding-top:6px;">
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequestsPage as $r)
                        <tr>
                            <td>
                                <div class="cell-emp">
                                    <div class="av">{{ strtoupper(substr($r->employee->user->name,0,1)) }}</div>
                                    <div><b>{{ $r->employee->user->name }}</b></div>
                                </div>
                            </td>
                            <td>{{ $r->employee->department->name ?? '—' }}</td>
                            <td>{{ $r->leaveType->name }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($r->start_date)->format('d M') }} &ndash; {{ \Illuminate\Support\Carbon::parse($r->end_date)->format('d M Y') }}</td>
                            <td>{{ rtrim(rtrim(number_format($r->days,1),'0'),'.') }}</td>
                            <td>
                                @php $p = ['approved'=>'pill-ok','pending'=>'pill-warn','rejected'=>'pill-bad','cancelled'=>'pill-muted'][$r->status] ?? 'pill-muted'; @endphp
                                <span class="pill {{ $p }}">{{ ucfirst($r->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6">
                            <div class="empty-widget">
                                <div class="ew-ico"><i class="fa-regular fa-calendar"></i></div>
                                <b>No leave requests found</b>
                                <span>Try adjusting the filters above.</span>
                            </div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
                @if($leaveRequestsPage)
                    {{ $leaveRequestsPage->appends(['tab' => 'register'])->links() }}
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<script>
(function () {
    const sel = document.getElementById('leaveTypeSel');
    const from = document.getElementById('leaveFrom');
    const to = document.getElementById('leaveTo');
    const hint = document.getElementById('leaveHint');
    if (!sel || !from || !to || !hint) return;

    const fmt = n => (Math.round(n * 10) / 10).toString();

    function update() {
        if (!from.value || !to.value) { hint.style.display = 'none'; return; }
        const days = Math.round((new Date(to.value) - new Date(from.value)) / 86400000) + 1;
        if (!(days >= 1)) { hint.style.display = 'none'; return; }

        const opt = sel.options[sel.selectedIndex];
        const name = opt.text.split('\u2014')[0].trim();
        hint.style.display = 'block';

        if (opt.dataset.unlimited === '1') {
            hint.style.color = 'var(--brand-strong)';
            hint.textContent = days + ' day' + (days === 1 ? '' : 's') + ' of ' + name + ' \u2014 no limit applies.';
            return;
        }

        const left = parseFloat(opt.dataset.available) - days;
        if (left < 0) {
            hint.style.color = 'var(--bad)';
            hint.textContent = days + ' day' + (days === 1 ? '' : 's') + ' requested but only ' + fmt(parseFloat(opt.dataset.available)) + ' of ' + name + ' available.';
        } else {
            hint.style.color = 'var(--brand-strong)';
            hint.textContent = days + ' day' + (days === 1 ? '' : 's') + ' of ' + name + ' \u2014 you\u2019ll have ' + fmt(left) + ' left after this.';
        }
    }

    [sel, from, to].forEach(el => el.addEventListener('change', update));
})();
</script>

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
