{{--
    Role-scoped performance & sales insights for the Performance page.
    Expects: $mine, $team, $org (each null when the role may not see it),
             $cycles, $selectedCycle, $periodFrom, $periodTo, $role.
    The controller only loads data a role is allowed to see, so a null
    block simply doesn't render.
--}}
@php
    $inr = fn ($v) => \App\Services\Hr\PerformanceInsightsService::inr($v);
    $pct = fn ($v, $max) => $max > 0 ? max(2, (int) round($v / $max * 100)) : 0;
    $rate = fn ($v) => $v === null ? '—' : rtrim(rtrim(number_format($v, 1), '0'), '.').'%';
    $statusLabel = fn ($s) => $s ? ucfirst(str_replace('_', ' ', $s)) : '—';
@endphp

<style>
    .pi-bars{display:flex;align-items:flex-end;gap:12px;height:150px;margin-top:14px;}
    .pi-bar{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;height:100%;gap:6px;min-width:0;}
    .pi-bar i{display:block;width:100%;max-width:44px;border-radius:8px 8px 3px 3px;background:linear-gradient(180deg,var(--brand-bright),var(--brand-strong));min-height:3px;}
    .pi-bar small{font-size:10.5px;color:var(--text-muted);white-space:nowrap;}
    .pi-bar em{font-style:normal;font-size:10.5px;font-weight:600;color:var(--brand-ink);white-space:nowrap;}
    .pi-hrow{display:flex;align-items:center;gap:10px;margin:10px 0;font-size:12.5px;}
    .pi-hrow .lbl{width:118px;flex-shrink:0;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
    .pi-hrow .trk{flex:1;height:9px;border-radius:99px;background:var(--brand-soft);overflow:hidden;}
    .pi-hrow .trk i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--brand-bright),var(--brand-strong));}
    .pi-hrow .val{width:64px;text-align:right;font-weight:600;color:var(--brand-ink);font-variant-numeric:tabular-nums;}
    .pi-period{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin:6px 0 0;}
    .pi-period select{padding:8px 12px;border:1px solid var(--line);border-radius:10px;background:var(--surface);font:inherit;font-size:13px;}
    .pi-note{font-size:12.5px;color:var(--text-muted);margin:12px 0 0;}
    .pi-scroll{overflow-x:auto;}
    @media (max-width:900px){.grid-2{grid-template-columns:1fr;}.kpi-row{grid-template-columns:repeat(2,1fr);}}
</style>

{{-- ============ Period selector ============ --}}
<div class="pi-period">
    <form method="GET" action="{{ route('hr.appraisal.index') }}" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <label for="pi-cycle" class="hint" style="font-size:12.5px;color:var(--text-muted);">Showing figures for</label>
        <select id="pi-cycle" name="cycle" onchange="this.form.submit()">
            @foreach($cycles as $c)
                <option value="{{ $c->id }}" @selected($selectedCycle && $selectedCycle->id === $c->id)>{{ $c->name }} ({{ ucfirst($c->status) }})</option>
            @endforeach
            @if($cycles->isEmpty())<option>Last 90 days</option>@endif
        </select>
        <span class="hint" style="font-size:12.5px;color:var(--text-muted);">{{ $periodFrom->format('d M Y') }} &ndash; {{ $periodTo->format('d M Y') }}</span>
    </form>
</div>

{{-- ============ My performance (every role with an employee record) ============ --}}
@if($mine)
    <div class="section-head">
        <h2><i class="fa-solid fa-chart-line"></i>My performance</h2>
        <span class="hint">Only you can see this</span>
    </div>

    <div class="kpi-row" style="margin-top:0;">
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Net sales</span></div>
            <div class="kpi-val">{{ $mine['sales_linked'] ? $inr($mine['sales']['net_sales']) : '—' }}</div>
            <div class="kpi-sub"><i class="fa-solid fa-receipt"></i>{{ $mine['sales']['orders'] }} orders &middot; {{ $mine['sales']['customers'] }} customers</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Avg. order value</span></div>
            <div class="kpi-val">{{ $mine['sales_linked'] ? $inr($mine['sales']['avg_order']) : '—' }}</div>
            <div class="kpi-sub"><i class="fa-solid fa-circle-check"></i>{{ $mine['sales']['approved'] }} approved &middot; {{ $mine['sales']['pending'] }} pending</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Attendance</span></div>
            <div class="kpi-val">{{ $rate($mine['attendance']['rate']) }}</div>
            <div class="kpi-sub"><i class="fa-regular fa-clock"></i>{{ $mine['attendance']['late'] }} late &middot; {{ $mine['attendance']['absent'] }} absent</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Incentive earned</span></div>
            <div class="kpi-val">{{ $inr($mine['incentives']['earned']) }}</div>
            <div class="kpi-sub"><i class="fa-solid fa-hourglass-half"></i>{{ $inr($mine['incentives']['pending']) }} pending approval</div>
        </div>
    </div>

    @unless($mine['sales_linked'])
        <p class="pi-note"><i class="fa-solid fa-circle-info"></i> Your HR record isn't linked to an SPC sales profile yet, so sales figures aren't available. Ask HR to link it.</p>
    @endunless

    <div class="grid-2" style="margin-top:18px;">
        <div class="card">
            <h3>Sales trend</h3>
            <p class="field-hint">Net sales over the last 6 months</p>
            @php $trendMax = max(array_column($mine['trend'], 'net_sales') ?: [0]); @endphp
            <div class="pi-bars">
                @foreach($mine['trend'] as $m)
                    <div class="pi-bar" title="{{ $m['orders'] }} orders">
                        <em>{{ $m['net_sales'] > 0 ? $inr($m['net_sales']) : '' }}</em>
                        <i style="height:{{ $pct($m['net_sales'], $trendMax) }}%;"></i>
                        <small>{{ $m['label'] }}</small>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <h3>Leads &amp; ratings</h3>
            <p class="field-hint">{{ $mine['leads']['total'] }} leads in this period</p>
            @php $leadMax = max($mine['leads']['by_status'] ?: [0]); @endphp
            @forelse(array_slice($mine['leads']['by_status'], 0, 5, true) as $status => $n)
                <div class="pi-hrow"><span class="lbl">{{ $status }}</span><span class="trk"><i style="width:{{ $pct($n, $leadMax) }}%"></i></span><span class="val">{{ $n }}</span></div>
            @empty
                <p class="pi-note">No leads logged in this period.</p>
            @endforelse

            @if(!empty($mine['ratings']))
                <p class="field-hint" style="margin-top:16px;">Appraisal rating history</p>
                @foreach($mine['ratings'] as $r)
                    <div class="pi-hrow"><span class="lbl">{{ $r['cycle'] }}</span><span class="trk"><i style="width:{{ $pct($r['rating'], 5) }}%"></i></span><span class="val">{{ number_format($r['rating'], 2) }}</span></div>
                @endforeach
            @endif
        </div>
    </div>
@endif

{{-- ============ My team (managers) ============ --}}
@if($team)
    <div class="section-head" style="margin-top:36px;">
        <h2><i class="fa-solid fa-people-group"></i>My team</h2>
        <span class="hint">{{ $team['size'] }} direct {{ \Illuminate\Support\Str::plural('report', $team['size']) }}</span>
    </div>

    @if($team['size'] === 0)
        <div class="card"><p class="pi-note" style="margin:0;">No one reports to you yet.</p></div>
    @else
        <div class="kpi-row" style="margin-top:0;">
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">Team net sales</span></div>
                <div class="kpi-val">{{ $inr($team['sales']['net_sales']) }}</div>
                <div class="kpi-sub"><i class="fa-solid fa-receipt"></i>{{ $team['sales']['orders'] }} orders</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">Team attendance</span></div>
                <div class="kpi-val">{{ $rate($team['attendance']['rate']) }}</div>
                <div class="kpi-sub"><i class="fa-regular fa-clock"></i>{{ $team['attendance']['late'] }} late &middot; {{ $team['attendance']['absent'] }} absent</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">Team leads</span></div>
                <div class="kpi-val">{{ $team['leads']['total'] }}</div>
                <div class="kpi-sub"><i class="fa-solid fa-bullseye"></i>this period</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">Team incentive</span></div>
                <div class="kpi-val">{{ $inr($team['incentives']['earned']) }}</div>
                <div class="kpi-sub"><i class="fa-solid fa-hourglass-half"></i>{{ $inr($team['incentives']['pending']) }} pending</div>
            </div>
        </div>

        <div class="table-card" style="margin-top:18px;">
            <div class="tc-body pi-scroll">
                <table>
                    <thead><tr><th>Employee</th><th>Orders</th><th>Net sales</th><th>Attendance</th><th>Latest rating</th><th>Appraisal</th></tr></thead>
                    <tbody>
                        @foreach($team['rows'] as $row)
                            <tr>
                                <td><b>{{ $row['name'] }}</b><br><span class="field-hint">{{ $row['code'] }}</span></td>
                                <td>{{ $row['sales_linked'] ? $row['orders'] : '—' }}</td>
                                <td><b>{{ $row['sales_linked'] ? $inr($row['net_sales']) : '—' }}</b></td>
                                <td>{{ $rate($row['attendance_rate']) }}</td>
                                <td>{{ $row['latest_rating'] ? number_format($row['latest_rating'], 2).' / 5' : '—' }}</td>
                                <td><span class="pill {{ $row['appraisal_status'] === 'completed' ? 'pill-ok' : 'pill-warn' }}">{{ $statusLabel($row['appraisal_status']) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endif

{{-- ============ Organisation (HR admin / super admin) ============ --}}
@if($org)
    <div class="section-head" style="margin-top:36px;">
        <h2><i class="fa-solid fa-building"></i>Organisation overview</h2>
        <span class="hint">{{ $role === 'super_admin' ? 'Org-wide, including franchises' : 'Org-wide people & performance' }}</span>
    </div>

    <div class="kpi-row" style="margin-top:0;">
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Headcount</span></div>
            <div class="kpi-val">{{ $org['headcount']['active'] }}</div>
            <div class="kpi-sub"><i class="fa-solid fa-user-clock"></i>{{ $org['headcount']['on_notice'] }} on notice</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Appraisal completion</span></div>
            <div class="kpi-val">{{ $org['appraisal']['completion_pct'] !== null ? $org['appraisal']['completion_pct'].'%' : '—' }}</div>
            <div class="kpi-sub"><i class="fa-solid fa-user-pen"></i>{{ $org['appraisal']['completed'] }} of {{ $org['appraisal']['total'] }} &middot; {{ $org['appraisal']['pending_manager'] }} awaiting review</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Average rating</span></div>
            <div class="kpi-val">{{ $org['appraisal']['avg_rating'] !== null ? number_format($org['appraisal']['avg_rating'], 2) : '—' }}</div>
            <div class="kpi-sub"><i class="fa-solid fa-star"></i>out of 5</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Net sales</span></div>
            <div class="kpi-val">{{ $inr($org['sales']['net_sales']) }}</div>
            <div class="kpi-sub"><i class="fa-solid fa-receipt"></i>{{ $org['sales']['orders'] }} orders &middot; {{ $inr($org['sales']['avg_order']) }} avg</div>
        </div>
    </div>

    <div class="kpi-row" style="margin-top:18px;">
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Attendance</span></div>
            <div class="kpi-val">{{ $rate($org['attendance']['rate']) }}</div>
            <div class="kpi-sub"><i class="fa-regular fa-clock"></i>{{ $org['attendance']['late'] }} late &middot; {{ $org['attendance']['absent'] }} absent</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Leads</span></div>
            <div class="kpi-val">{{ $org['leads']['total'] }}</div>
            <div class="kpi-sub"><i class="fa-solid fa-bullseye"></i>created this period</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Incentive cost</span></div>
            <div class="kpi-val">{{ $inr($org['incentives']['earned']) }}</div>
            <div class="kpi-sub"><i class="fa-solid fa-hourglass-half"></i>{{ $inr($org['incentives']['pending']) }} pending</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">GST / discounts</span></div>
            <div class="kpi-val">{{ $inr($org['sales']['gst']) }}</div>
            <div class="kpi-sub"><i class="fa-solid fa-tags"></i>{{ $inr($org['sales']['discount']) }} discounts</div>
        </div>
    </div>

    <div class="grid-2" style="margin-top:18px;">
        <div class="card">
            <h3>Sales trend</h3>
            <p class="field-hint">Organisation net sales, last 6 months</p>
            @php $orgMax = max(array_column($org['trend'], 'net_sales') ?: [0]); @endphp
            <div class="pi-bars">
                @foreach($org['trend'] as $m)
                    <div class="pi-bar" title="{{ $m['orders'] }} orders">
                        <em>{{ $m['net_sales'] > 0 ? $inr($m['net_sales']) : '' }}</em>
                        <i style="height:{{ $pct($m['net_sales'], $orgMax) }}%;"></i>
                        <small>{{ $m['label'] }}</small>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <h3>Rating distribution</h3>
            <p class="field-hint">{{ $selectedCycle?->name ?? 'Selected cycle' }} &middot; final ratings</p>
            @php $distMax = max($org['appraisal']['distribution'] ?: [0]); @endphp
            @foreach($org['appraisal']['distribution'] as $label => $n)
                <div class="pi-hrow"><span class="lbl">{{ $label }}</span><span class="trk"><i style="width:{{ $n ? $pct($n, $distMax) : 0 }}%"></i></span><span class="val">{{ $n }}</span></div>
            @endforeach

            @if(!empty($org['appraisal']['by_department']))
                <p class="field-hint" style="margin-top:16px;">Average rating by department</p>
                @foreach($org['appraisal']['by_department'] as $d)
                    <div class="pi-hrow"><span class="lbl">{{ $d['department'] }}</span><span class="trk"><i style="width:{{ $pct($d['avg_rating'], 5) }}%"></i></span><span class="val">{{ number_format($d['avg_rating'], 2) }}</span></div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="grid-2" style="margin-top:18px;">
        <div class="table-card">
            <div class="tc-body pi-scroll">
                <table>
                    <thead><tr><th colspan="3">Top sellers</th></tr><tr><th>Advisor</th><th>Orders</th><th>Net sales</th></tr></thead>
                    <tbody>
                        @forelse($org['top_sellers'] as $s)
                            <tr><td><b>{{ $s['name'] }}</b><br><span class="field-hint">{{ $s['code'] }}</span></td><td>{{ $s['orders'] }}</td><td><b>{{ $inr($s['net_sales']) }}</b></td></tr>
                        @empty
                            <tr><td colspan="3" class="field-hint">No sales recorded in this period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($role === 'super_admin')
            <div class="table-card">
                <div class="tc-body pi-scroll">
                    <table>
                        <thead><tr><th colspan="3">Franchise / store sales</th></tr><tr><th>Store</th><th>Orders</th><th>Net sales</th></tr></thead>
                        <tbody>
                            @forelse($org['franchises'] as $f)
                                <tr><td><b>{{ $f['name'] }}</b><br><span class="field-hint">{{ $f['code'] }}</span></td><td>{{ $f['orders'] }}</td><td><b>{{ $inr($f['net_sales']) }}</b></td></tr>
                            @empty
                                <tr><td colspan="3" class="field-hint">No franchise-attributed orders in this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card">
                <h3>Appraisal pipeline</h3>
                <p class="field-hint">{{ $selectedCycle?->name ?? 'Selected cycle' }}</p>
                @php $pipeMax = max($org['appraisal']['by_status'] ?: [0]); @endphp
                @forelse($org['appraisal']['by_status'] as $st => $n)
                    <div class="pi-hrow"><span class="lbl">{{ $statusLabel($st) }}</span><span class="trk"><i style="width:{{ $pct($n, $pipeMax) }}%"></i></span><span class="val">{{ $n }}</span></div>
                @empty
                    <p class="pi-note">No appraisals in this cycle.</p>
                @endforelse
            </div>
        @endif
    </div>
@endif
