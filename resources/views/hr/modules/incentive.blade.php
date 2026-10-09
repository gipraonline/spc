@extends('hr.layouts.app')

@section('title', $module['title'])

@section('content')
    @include('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Money',
        'heroIcon' => 'fa-solid fa-medal',
        'heroSummary' => 'Configurable commission rules, calculations and payout approval.',
        'heroStats' => [
            ['label' => 'Pending', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pendingPayouts->count()],
            ['label' => 'My payouts', 'icon' => 'fa-regular fa-user', 'value' => $ownPayouts->count()],
            ['label' => 'Earned', 'icon' => 'fa-solid fa-indian-rupee-sign', 'value' => '₹' . number_format($ownPayouts->whereIn('status', ['paid', 'approved', 'included_in_payroll'])->sum('incentive_amount'), 0)],
        ],
    ])

    <div class="content">
        <div class="grid-2">
            @if($role === 'super_admin' || $role === 'hr_admin')
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
            @endif

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
            @elseif($ownPayouts->isNotEmpty())
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
            @endif
        </div>

        @if($pendingPayouts->isNotEmpty() && $ownPayouts->isNotEmpty())
            <div class="section-head" style="margin-top:28px;">
                <h2><i class="fa-solid fa-medal"></i>Your incentive payouts</h2>
            </div>
            <div class="table-card">
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
        @endif


        @if(($role === 'super_admin' || $role === 'hr_admin') && $rules->isNotEmpty())
            <div class="section-head" style="margin-top:28px;">
                <h2><i class="fa-solid fa-scale-balanced"></i>Incentive rules</h2>
            </div>
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
        @endif

    </div>
@endsection