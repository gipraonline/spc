{{--
    Sales / activity targets on the Performance page.
    Expects: $mine (may contain 'targets'), $selectedCycle, $targetEmployees, $targetValues, $team (may have rows with target_pct).
    - Everyone with an employee record sees progress on THEIR OWN targets only.
    - Managers see/set targets for direct reports; HR & super admin for all active employees.
--}}
@php
    $inrT = fn ($v) => \App\Services\Hr\PerformanceInsightsService::inr($v);
    $metrics = \App\Models\Hr\SalesTarget::METRICS;
    $fmtT = fn ($row) => $row['money'] ? $inrT($row['actual']).' / '.$inrT($row['target']) : number_format($row['actual']).' / '.number_format($row['target']);
    $tone = fn ($pct) => $pct >= 100 ? 'var(--brand-strong)' : ($pct >= 70 ? '#d97706' : '#dc2626');
@endphp

@if($mine && !empty($mine['targets']))
    <div class="section-head" style="margin-top:28px;">
        <h2><i class="fa-solid fa-bullseye"></i>My targets</h2>
        <span class="hint">{{ $selectedCycle?->name }}</span>
    </div>
    <div class="card">
        @foreach($mine['targets'] as $row)
            <div class="pi-hrow">
                <span class="lbl" style="width:110px;">{{ $row['label'] }}</span>
                <span class="trk"><i style="width:{{ min(100, $row['pct']) }}%;background:{{ $tone($row['pct']) }};"></i></span>
                <span class="val" style="width:70px;">{{ $row['pct'] }}%</span>
            </div>
            <div class="hint" style="font-size:12px;color:var(--text-muted);margin:-4px 0 8px 120px;">{{ $fmtT($row) }}</div>
        @endforeach
    </div>
@endif

@if($targetEmployees->isNotEmpty() && $selectedCycle)
    <div class="section-head" style="margin-top:28px;">
        <h2><i class="fa-solid fa-flag-checkered"></i>Set targets &middot; {{ $selectedCycle->name }}</h2>
        <span class="hint">Leave a box empty to remove that target</span>
    </div>
    <div class="table-card">
        <div class="tc-body pi-scroll">
            <form method="POST" action="{{ route('hr.appraisal.targets.save') }}">
                @csrf
                <input type="hidden" name="appraisal_cycle_id" value="{{ $selectedCycle->id }}">
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th>
                            @foreach($metrics as $key => $m)<th>{{ $m['label'] }}</th>@endforeach
                            @if($team)<th>Sales achieved</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($targetEmployees as $te)
                            @php $teamRow = $team ? collect($team['rows'])->firstWhere('code', $te->employee_code) : null; @endphp
                            <tr>
                                <td><b>{{ $te->user->name ?? '—' }}</b><br><span class="hint" style="font-size:11.5px;color:var(--text-muted);">{{ $te->employee_code }}</span></td>
                                @foreach($metrics as $key => $m)
                                    <td><input type="number" min="0" step="{{ $m['money'] ? '1000' : '1' }}" name="targets[{{ $te->id }}][{{ $key }}]" value="{{ $targetValues[$te->id][$key] ?? '' }}" style="width:110px;padding:7px 9px;border:1px solid var(--line);border-radius:8px;font:inherit;font-size:13px;"></td>
                                @endforeach
                                @if($team)
                                    <td>
                                        @if($teamRow && $teamRow['target_pct'] !== null)
                                            <span class="pill {{ $teamRow['target_pct'] >= 100 ? 'pill-ok' : ($teamRow['target_pct'] >= 70 ? 'pill-warn' : 'pill-muted') }}">{{ $teamRow['target_pct'] }}%</span>
                                        @else — @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="form-actions" style="margin-top:14px;"><button type="submit" class="btn-primary">Save targets</button></div>
            </form>
            <p class="pi-note">Targets are for the whole cycle. Incentive rules with a minimum achievement use the monthly share (cycle target &divide; months in the cycle).</p>
        </div>
    </div>
@endif
