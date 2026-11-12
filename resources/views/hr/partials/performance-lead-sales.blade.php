{{--
    Team-lead sales performance: "My sales" and "My team's sales" cards, a chart,
    and Daily / Weekly / Monthly tabs.
    Expects: $leadSales (TeamLeadSalesService::forLead), null for people who aren't team leads.
--}}
@php
    $inrL = fn ($v) => \App\Services\Hr\PerformanceInsightsService::inr($v);
    $deltaL = function ($cur, $prev) {
        if ($prev <= 0) { return $cur > 0 ? ['new', 'up'] : ['—', 'flat']; }
        $d = ($cur - $prev) / $prev * 100;
        return [($d >= 0 ? '+' : '').number_format($d, 0).'%', $d >= 0 ? 'up' : 'down'];
    };
    $leadTabs = [
        'daily'   => ['label' => 'Daily',   'now' => 'Today',      'prev' => 'yesterday',  'chart' => 'Last 30 days'],
        'weekly'  => ['label' => 'Weekly',  'now' => 'This week',  'prev' => 'last week',  'chart' => 'Last 12 weeks (week starting Monday)'],
        'monthly' => ['label' => 'Monthly', 'now' => 'This month', 'prev' => 'last month', 'chart' => 'Last 12 months'],
    ];
@endphp

<style>
    .ls-tabs{display:flex;gap:6px;margin:0 0 16px;}
    .ls-tab{padding:7px 16px;border:1px solid var(--line);border-radius:99px;background:var(--surface);font:inherit;font-size:13px;cursor:pointer;color:var(--text-muted);}
    .ls-tab.active{background:var(--brand);border-color:var(--brand);color:#fff;font-weight:600;}
    .ls-panel{display:none;}
    .ls-panel.active{display:block;}
    .ls-delta{font-weight:600;margin-left:6px;}
    .ls-delta.up{color:#15803d;}
    .ls-delta.down{color:#b91c1c;}
    .ls-delta.flat{color:var(--text-muted);}
    .ls-legend{display:flex;gap:16px;font-size:12px;color:var(--text-muted);margin:4px 0 0;}
    .ls-legend i{display:inline-block;width:10px;height:10px;border-radius:3px;margin-right:6px;vertical-align:-1px;}
    .ls-chart{overflow-x:auto;padding-bottom:4px;}
    .ls-bars{display:flex;align-items:flex-end;gap:10px;height:190px;margin-top:14px;min-width:100%;}
    .ls-col{flex:1 0 34px;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;height:100%;gap:6px;}
    .ls-pair{display:flex;align-items:flex-end;gap:3px;height:150px;width:100%;justify-content:center;}
    .ls-pair i{display:block;width:44%;max-width:20px;border-radius:5px 5px 2px 2px;min-height:2px;}
    .ls-pair .own{background:linear-gradient(180deg,var(--brand-bright),var(--brand-strong));}
    .ls-pair .team{background:#94a3b8;}
    .ls-col small{font-size:10.5px;color:var(--text-muted);white-space:nowrap;}
</style>

<div class="section-head">
    <h2><i class="fa-solid fa-chart-column"></i>Sales performance</h2>
    <span class="hint">{{ $leadSales['team_size'] }} {{ \Illuminate\Support\Str::plural('person', $leadSales['team_size']) }} in your team</span>
</div>

@unless($leadSales['sales_linked'])
    <p class="pi-note">Your login is not linked to a sales account, so your own sales show as zero. Your team's sales still include everyone reporting to you.</p>
@endunless

<div class="ls-tabs">
    @foreach($leadTabs as $key => $t)
        <button type="button" class="ls-tab {{ $loop->first ? 'active' : '' }}" onclick="leadSalesTab(this,'{{ $key }}')">{{ $t['label'] }}</button>
    @endforeach
</div>

@foreach($leadTabs as $key => $t)
    @php
        $s = $leadSales[$key];
        $cur = $s['current'];
        $prev = $s['previous'];
        [$ownTxt, $ownCls] = $deltaL($cur['own'], $prev['own']);
        [$teamTxt, $teamCls] = $deltaL($cur['team'], $prev['team']);
        $max = max(array_merge(array_column($s['rows'], 'own'), array_column($s['rows'], 'team'), [0]));
        $h = fn ($v) => $max > 0 ? max(2, (int) round($v / $max * 100)) : 0;
    @endphp
    <div class="ls-panel {{ $loop->first ? 'active' : '' }}" data-ls-panel="{{ $key }}">
        <div class="kpi-row" style="margin-top:0;grid-template-columns:repeat(2,1fr);">
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">My sales &middot; {{ $t['now'] }}</span></div>
                <div class="kpi-val">{{ $inrL($cur['own']) }}</div>
                <div class="kpi-sub"><i class="fa-solid fa-receipt"></i>{{ $cur['own_orders'] }} orders<span class="ls-delta {{ $ownCls }}">{{ $ownTxt }}</span> vs {{ $t['prev'] }}</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">My team's sales &middot; {{ $t['now'] }}</span></div>
                <div class="kpi-val">{{ $inrL($cur['team']) }}</div>
                <div class="kpi-sub"><i class="fa-solid fa-people-group"></i>{{ $cur['team_orders'] }} orders<span class="ls-delta {{ $teamCls }}">{{ $teamTxt }}</span> vs {{ $t['prev'] }}</div>
            </div>
        </div>

        <div class="card" style="margin-top:18px;">
            <h3>{{ $t['label'] }} sales &mdash; {{ $t['chart'] }}</h3>
            <div class="ls-legend">
                <span><i style="background:var(--brand-strong);"></i>My sales</span>
                <span><i style="background:#94a3b8;"></i>My team's sales (including mine)</span>
            </div>
            <div class="ls-chart">
                <div class="ls-bars" style="min-width:{{ count($s['rows']) * 44 }}px;">
                    @foreach($s['rows'] as $r)
                        <div class="ls-col" title="{{ $r['label'] }}: mine {{ $inrL($r['own']) }} ({{ $r['own_orders'] }} orders) · team {{ $inrL($r['team']) }} ({{ $r['team_orders'] }} orders)">
                            <div class="ls-pair">
                                <i class="own" style="height:{{ $h($r['own']) }}%;"></i>
                                <i class="team" style="height:{{ $h($r['team']) }}%;"></i>
                            </div>
                            <small>{{ $r['label'] }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="table-card" style="margin-top:18px;">
            <div class="tc-body pi-scroll">
                <table>
                    <thead><tr><th>{{ $t['label'] === 'Daily' ? 'Day' : ($t['label'] === 'Weekly' ? 'Week starting' : 'Month') }}</th><th>My orders</th><th>My sales</th><th>Team orders</th><th>Team sales</th></tr></thead>
                    <tbody>
                        @foreach(array_reverse($s['rows']) as $r)
                            <tr>
                                <td><b>{{ $r['label'] }}</b></td>
                                <td>{{ $r['own_orders'] }}</td>
                                <td>{{ $inrL($r['own']) }}</td>
                                <td>{{ $r['team_orders'] }}</td>
                                <td><b>{{ $inrL($r['team']) }}</b></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endforeach

<script>
function leadSalesTab(btn, name) {
    const wrap = btn.closest('.ls-tabs').parentElement;
    wrap.querySelectorAll('.ls-tab').forEach(t => t.classList.remove('active'));
    wrap.querySelectorAll('.ls-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    wrap.querySelector('[data-ls-panel="' + name + '"]').classList.add('active');
}
</script>
