{{--
    "My performance": the signed-in person's own numbers for the picked month.
    Expects: $mineMonth (forEmployee), $mineReport (one report row), $month, $periodFrom, $periodTo.
--}}
@php
    $inrM = fn ($v) => \App\Services\Hr\PerformanceInsightsService::inr($v);
    $rateM = fn ($v) => $v === null ? '—' : rtrim(rtrim(number_format($v, 1), '0'), '.').'%';
    $trendMax = max(array_column($mineMonth['trend'], 'net_sales') ?: [0]);
@endphp

<div class="section-head">
    <h2><i class="fa-solid fa-chart-line"></i>My performance &mdash; {{ $month->format('F Y') }}</h2>
    <span class="hint">{{ $periodFrom->format('d M') }} &ndash; {{ $periodTo->format('d M Y') }}</span>
</div>

<div class="kpi-row" style="margin-top:0;">
    <div class="kpi-card">
        <div class="kpi-top"><span class="kpi-label">My sales</span></div>
        <div class="kpi-val">{{ $mineMonth['sales_linked'] ? $inrM($mineMonth['sales']['net_sales']) : '—' }}</div>
        <div class="kpi-sub"><i class="fa-solid fa-receipt"></i>{{ $mineMonth['sales']['orders'] }} orders &middot; {{ $mineMonth['sales']['customers'] }} customers</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-top"><span class="kpi-label">My attendance</span></div>
        <div class="kpi-val">{{ $rateM($mineMonth['attendance']['rate']) }}</div>
        <div class="kpi-sub"><i class="fa-regular fa-clock"></i>{{ $mineMonth['attendance']['late'] }} late &middot; {{ $mineMonth['attendance']['absent'] }} absent</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-top"><span class="kpi-label">New leads</span></div>
        <div class="kpi-val">{{ $mineMonth['leads']['total'] }}</div>
        <div class="kpi-sub"><i class="fa-solid fa-bullseye"></i>{{ $mineMonth['field_days'] }} field days</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-top"><span class="kpi-label">Incentive paid</span></div>
        <div class="kpi-val">{{ $inrM($mineMonth['incentives']['paid']) }}</div>
        <div class="kpi-sub"><i class="fa-solid fa-hourglass-half"></i>{{ $inrM($mineMonth['incentives']['awaiting_payment']) }} awaiting payment</div>
    </div>
</div>

@unless($mineMonth['sales_linked'])
    <p class="pi-note">Your login is not linked to a sales account, so sales and leads show as zero.</p>
@endunless

<div class="grid-2" style="margin-top:18px;">
    <div class="table-card">
        <div class="tc-body">
            <table>
                <thead><tr><th colspan="2">My monthly report</th></tr></thead>
                <tbody>
                    <tr><td>Total monthly sales value</td><td><b>{{ $mineReport && $mineReport['sales_linked'] ? $inrM($mineReport['sales']) : '—' }}</b></td></tr>
                    <tr><td>Leave (days)</td><td><b>{{ $mineReport['leave_days'] ?? 0 }}</b></td></tr>
                    <tr><td>WFH (days)</td><td><b>{{ $mineReport['wfh_days'] ?? 0 }}</b></td></tr>
                    <tr><td>Late</td><td><b>{{ $mineReport['late'] ?? 0 }}</b></td></tr>
                    <tr><td>Salary paid</td><td><b>{{ isset($mineReport['salary_paid']) && $mineReport['salary_paid'] !== null ? '₹'.number_format($mineReport['salary_paid']) : 'Not paid yet' }}</b></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3>My sales, last 6 months</h3>
        <div class="pi-bars">
            @foreach($mineMonth['trend'] as $m)
                <div class="pi-bar" title="{{ $m['orders'] }} orders">
                    <em>{{ $m['net_sales'] > 0 ? $inrM($m['net_sales']) : '' }}</em>
                    <i style="height:{{ $trendMax > 0 ? max(2, (int) round($m['net_sales'] / $trendMax * 100)) : 0 }}%;"></i>
                    <small>{{ $m['label'] }}</small>
                </div>
            @endforeach
        </div>
    </div>
</div>
