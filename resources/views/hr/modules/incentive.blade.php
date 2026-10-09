@extends('hr.layouts.app')

@section('title', $module['title'])

@section('content')
    @include('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Money',
        'heroIcon' => 'fa-solid fa-medal',
        'heroSummary' => 'Configurable incentive rules, calculations and payout approval.',
        'heroStats' => [
            ['label' => 'Pending', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pendingPayouts->count()],
            ['label' => 'My payouts', 'icon' => 'fa-regular fa-user', 'value' => $ownPayouts->count()],
            ['label' => 'Earned', 'icon' => 'fa-solid fa-indian-rupee-sign', 'value' => '₹' . number_format($ownPayouts->whereIn('status', ['paid', 'approved', 'included_in_payroll'])->sum('incentive_amount'), 0)],
        ],
    ])

    @php
        $isAdmin = in_array($role, ['super_admin', 'hr_admin'], true);
        $showPending = in_array($role, ['manager', 'super_admin', 'hr_admin'], true);
        $defaultTab = $isAdmin ? 'calculate' : ($showPending ? 'pending' : 'mine');
        $activeTab = request('tab', $defaultTab);
    @endphp

    <div class="content">
        <div class="tabs" id="incTabs">
            @if($isAdmin)
                <button type="button" class="tab @if($activeTab === 'calculate') active @endif" data-tab="calculate" onclick="incTab(this,'calculate')">Calculate</button>
                <button type="button" class="tab @if($activeTab === 'newrule') active @endif" data-tab="newrule" onclick="incTab(this,'newrule')">New rule</button>
                <button type="button" class="tab @if($activeTab === 'rules') active @endif" data-tab="rules" onclick="incTab(this,'rules')">Rules ({{ $rules->count() }})</button>
            @endif
            @if($showPending)
                <button type="button" class="tab @if($activeTab === 'pending') active @endif" data-tab="pending" onclick="incTab(this,'pending')">Pending approval @if($pendingPayouts->isNotEmpty())({{ $pendingPayouts->count() }})@endif</button>
            @endif
            <button type="button" class="tab @if($activeTab === 'mine') active @endif" data-tab="mine" onclick="incTab(this,'mine')">My payouts</button>
        </div>

        @if($isAdmin)
            <div class="tabpanel @if($activeTab === 'calculate') active @endif" data-tabpanel="calculate">
                <div class="card">
                            <div class="widget-head">
                                <div class="wh-ico"><i class="fa-solid fa-calculator"></i></div>
                                <div>
                                    <h3>Calculate incentives</h3>
                                    <p>Counts orders that are <b>{{ $eligibility['order_status'] }}</b> and payment <b>{{ $eligibility['payment_status'] }}</b>. Approved / paid payouts are never changed.</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('hr.incentive.calculate') }}">
                                @csrf
                                <div class="field-grid">
                                    <div class="field"><label>Month</label><input type="month" name="month" value="{{ $runMonth }}" max="{{ now()->format('Y-m') }}" required></div>
                                </div>
                                <div class="form-actions"><button type="submit" class="btn-primary">Calculate payouts</button></div>
                            </form>
                        </div>
            </div>

            <div class="tabpanel @if($activeTab === 'newrule') active @endif" data-tabpanel="newrule">
                <div class="card">
                            <div class="widget-head">
                                <div class="wh-ico"><i class="fa-solid fa-scale-balanced"></i></div>
                                <div>
                                    <h3>New incentive rule</h3>
                                    <p>Marginal slabs on monthly eligible net sales. "Own" = the person's sales, "Team" = override on everyone below them.</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('hr.incentive.rule.store') }}">
                                @csrf
                                <div class="field-grid">
                                    <div class="field full"><label>Rule name</label><input name="name" placeholder="e.g. FCO own sales - slab 4" required></div>
                                    <div class="field">
                                        <label>Designation</label>
                                        <select name="designation_code">
                                            <option value="">Any (use portal role below)</option>
                                            @foreach($designations as $d)
                                                <option value="{{ $d->identifier }}">{{ $d->c_designation }} ({{ $d->identifier }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label>Portal role</label>
                                        <select name="applies_to_role" required>
                                            <option value="employee">Employee</option>
                                            <option value="manager">Reporting Manager</option>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label>Basis</label>
                                        <select name="basis" required>
                                            <option value="own">Own sales</option>
                                            <option value="team">Team override</option>
                                        </select>
                                    </div>
                                    <div class="field"><label>Slab from (₹)</label><input type="number" step="0.01" min="0" name="slab_from" value="0"></div>
                                    <div class="field"><label>Slab to (₹, blank = no limit)</label><input type="number" step="0.01" min="0" name="slab_to"></div>
                                    <div class="field"><label>Incentive %</label><input type="number" step="0.01" min="0" max="100" name="incentive_percent"></div>
                                    <div class="field"><label>Flat amount (₹)</label><input type="number" step="0.01" min="0" name="flat_amount"></div>
                                    <div class="field"><label>Min. target achievement %</label><input type="number" step="0.1" min="0" name="min_achievement_pct" placeholder="blank = no gate"></div>
                                </div>
                                <div class="form-actions"><button type="submit" class="btn-primary">Save rule</button></div>
                            </form>
                        </div>
            </div>

            <div class="tabpanel @if($activeTab === 'rules') active @endif" data-tabpanel="rules">
                @if($rules->isNotEmpty())
                    <div class="table-card">
                            <div class="tc-body" style="overflow-x:auto;">
                                <table>
                                    <thead><tr><th>Rule</th><th>Who</th><th>Basis</th><th>Slab (₹)</th><th>Pays</th><th>Min. achievement</th><th>Status</th><th></th></tr></thead>
                                    <tbody>
                                        @foreach($rules as $r)
                                            <tr style="{{ $r->is_active ? '' : 'opacity:.55;' }}">
                                                <td><b>{{ $r->name }}</b></td>
                                                <td>{{ $r->designation_code ?: ucfirst($r->applies_to_role) }}</td>
                                                <td>{{ ($r->basis ?? 'own') === 'team' ? 'Team override' : 'Own sales' }}</td>
                                                <td>{{ number_format($r->slab_from ?? 0) }} &ndash; {{ $r->slab_to !== null ? number_format($r->slab_to) : 'no limit' }}</td>
                                                <td>{{ $r->incentive_percent !== null ? rtrim(rtrim(number_format($r->incentive_percent, 2), '0'), '.').'%' : '' }}{{ $r->flat_amount !== null ? ' + ₹'.number_format($r->flat_amount) : '' }}</td>
                                                <td>{{ $r->min_achievement_pct !== null ? rtrim(rtrim(number_format($r->min_achievement_pct, 1), '0'), '.').'%' : '—' }}</td>
                                                <td><span class="pill {{ $r->is_active ? 'pill-ok' : 'pill-muted' }}">{{ $r->is_active ? 'Active' : 'Off' }}</span></td>
                                                <td>
                                                    <form method="POST" action="{{ route('hr.incentive.rule.toggle', $r) }}">
                                                        @csrf
                                                        <button class="approve" type="submit">{{ $r->is_active ? 'Turn off' : 'Turn on' }}</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('hr.incentive.rule.destroy', $r) }}" style="margin-top:6px;" onsubmit="return confirm('Delete rule &quot;{{ addslashes($r->name) }}&quot;? Existing payouts keep their amounts. This cannot be undone.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="approve" type="submit" style="background:#fde8e8;color:#B42318;border-color:#f5c2c0;"><i class="fa-solid fa-trash"></i> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <p class="field-hint" style="margin-top:12px;"><i class="fa-solid fa-circle-info" style="color:var(--brand);margin-right:6px;"></i>Slabs are marginal: each rule pays its % only on the part of monthly sales that falls inside its slab.</p>
                            </div>
                        </div>
                @else
                    <div class="empty-widget">
                        <div class="ew-ico"><i class="fa-solid fa-scale-balanced"></i></div>
                        <b>No incentive rules yet</b>
                        <span>Create one under the New rule tab.</span>
                    </div>
                @endif
            </div>
        @endif

        @if($showPending)
            <div class="tabpanel @if($activeTab === 'pending') active @endif" data-tabpanel="pending">
                @if($pendingPayouts->isNotEmpty())
                    <div class="table-card">
                                <div class="tc-head">
                                    <h3><span class="wh-ico"><i class="fa-solid fa-hourglass-half"></i></span>Payouts pending approval</h3>
                                    <span class="pill pill-warn">{{ $pendingPayouts->count() }} waiting</span>
                                </div>
                                <div class="tc-body">
                                    <table>
                                        <thead><tr><th>Employee</th><th>Period</th><th>Basis</th><th>Base sales</th><th>Incentive</th><th></th></tr></thead>
                                        <tbody>
                                            @foreach($pendingPayouts as $p)
                                                <tr>
                                                    <td>
                                                        <div class="cell-emp">
                                                            <div class="av">{{ strtoupper(substr($p->employee->user->name,0,1)) }}</div>
                                                            <div><b>{{ $p->employee->user->name }}</b></div>
                                                        </div>
                                                    </td>
                                                    <td>{{ str_pad($p->month, 2, '0', STR_PAD_LEFT) }}/{{ $p->year }}</td>
                                                    <td><span class="pill pill-muted">{{ ($p->basis ?? 'own') === 'team' ? 'Team override' : 'Own sales' }}</span></td>
                                                    <td>₹{{ number_format($p->achieved_value,0) }}</td>
                                                    <td><b>₹{{ number_format($p->incentive_amount,0) }}</b></td>
                                                    <td>
                                                        <form method="POST" action="{{ route('hr.incentive.payout.approve', $p) }}">
                                                            @csrf
                                                            <button class="approve" type="submit">Approve</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <p class="field-hint" style="margin-top:12px;"><i class="fa-solid fa-circle-info" style="color:var(--brand);margin-right:6px;"></i>Approved payouts are included in the next payroll run.</p>
                                </div>
                            </div>
                @else
                    <div class="empty-widget">
                        <div class="ew-ico"><i class="fa-solid fa-hourglass-half"></i></div>
                        <b>Nothing waiting for approval</b>
                        <span>Payouts appear here after HR calculates incentives.</span>
                    </div>
                @endif
            </div>
        @endif

        <div class="tabpanel @if($activeTab === 'mine') active @endif" data-tabpanel="mine">
            @if($ownPayouts->isNotEmpty())
                <div class="table-card">
                            <div class="tc-head">
                                <h3><span class="wh-ico"><i class="fa-solid fa-medal"></i></span>Your incentive payouts</h3>
                                <span class="pill pill-muted">{{ $ownPayouts->count() }} total</span>
                            </div>
                            <div class="tc-body">
                                <table>
                                    <thead><tr><th>Period</th><th>Basis</th><th>Base sales</th><th>Incentive</th><th>Status</th></tr></thead>
                                    <tbody>
                                        @foreach($ownPayouts as $p)
                                            <tr>
                                                <td><b>{{ $p->month }}/{{ $p->year }}</b></td>
                                                <td>{{ ($p->basis ?? 'own') === 'team' ? 'Team override' : 'Own sales' }}</td>
                                                <td>₹{{ number_format($p->achieved_value,0) }}</td>
                                                <td>₹{{ number_format($p->incentive_amount,0) }}</td>
                                                <td>
                                                    @php $p2 = ['paid'=>'pill-ok','included_in_payroll'=>'pill-ok','approved'=>'pill-ok','pending'=>'pill-warn'][$p->status]; @endphp
                                                    <span class="pill {{ $p2 }}">{{ ucfirst(str_replace('_',' ',$p->status)) }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
            @else
                <div class="empty-widget">
                    <div class="ew-ico"><i class="fa-solid fa-medal"></i></div>
                    <b>No incentive payouts yet</b>
                    <span>Your approved incentives show up here and are paid with payroll.</span>
                </div>
            @endif
        </div>
    </div>

    <script>
    function incTab(btn, name) {
        const scope = document.querySelector('.content');
        scope.querySelectorAll(':scope > .tabs .tab').forEach(t => t.classList.remove('active'));
        scope.querySelectorAll(':scope > .tabpanel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        const panel = scope.querySelector('[data-tabpanel="' + name + '"]');
        if (panel) panel.classList.add('active');
        try { sessionStorage.setItem('incentiveTab', name); } catch (e) {}
    }
    (function () {
        // keep the same tab after an approve / calculate / save that reloads the page
        if (new URLSearchParams(location.search).has('tab')) return;
        let name = '';
        try { name = sessionStorage.getItem('incentiveTab') || ''; } catch (e) {}
        const btn = name && document.querySelector('#incTabs .tab[data-tab="' + name + '"]');
        if (btn) btn.click();
    })();
    </script>
@endsection