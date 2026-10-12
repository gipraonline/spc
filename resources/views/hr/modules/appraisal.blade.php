@extends('hr.layouts.app')

@section('title', $module['title'])

@section('content')
@include('hr.partials.topbar', [
    'title' => $module['title'],
    'eyebrow' => 'Growth',
    'heroIcon' => 'fa-solid fa-trophy',
    'heroSummary' => match ($role) {
        'employee' => 'Your sales, attendance, targets and monthly report.',
        'manager' => 'Your own numbers, plus your team\'s sales, attendance and monthly report.',
        default => 'Your own numbers, plus company sales, attendance and a monthly report for every employee.',
    },
    'heroStats' => [],
])

@php
    $inr = fn ($v) => \App\Services\Hr\PerformanceInsightsService::inr($v);
    $pct = fn ($v, $max) => $max > 0 ? max(2, (int) round($v / $max * 100)) : 0;
    $rate = fn ($v) => $v === null ? '—' : rtrim(rtrim(number_format($v, 1), '0'), '.').'%';

    $isHrRole = in_array($role, ['hr_admin', 'super_admin'], true);
    $hasOverview = (bool) ($team || $org);
    $hasReport = (bool) $report;
    $hasMine = (bool) $mineMonth;
    $available = array_keys(array_filter(['mine' => $hasMine, 'overview' => $hasOverview, 'report' => $hasReport, 'targets' => true]));
    $activeTab = in_array(request('tab'), $available, true) ? request('tab') : $available[0];
@endphp

<style>
    .pi-help{display:flex;gap:12px;align-items:flex-start;background:var(--brand-soft);border:1px solid var(--line);border-radius:14px;padding:14px 16px;margin:0 0 22px;font-size:13.5px;line-height:1.55;color:var(--brand-ink);}
    .pi-help i{margin-top:3px;color:var(--brand);}
    .pi-help ul{margin:6px 0 0 18px;padding:0;}
    .pi-month{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin:28px 0 0;}
    .pi-month input[type=month]{padding:8px 12px;border:1px solid var(--line);border-radius:10px;background:var(--surface);font:inherit;font-size:13px;}
</style>

<div class="content">

    @if($hasMine || $hasOverview || $hasReport)
        <form method="GET" action="{{ route('hr.appraisal.index') }}" class="pi-month">
            <input type="hidden" name="tab" id="pi-tab-field" value="{{ $activeTab }}">
            <label for="pi-month" style="font-size:12.5px;color:var(--text-muted);">Month</label>
            <input type="month" id="pi-month" name="month" value="{{ $month->format('Y-m') }}" max="{{ now()->format('Y-m') }}" onchange="this.form.submit()">
        </form>
    @endif

    <div class="tabs" style="margin-top:20px;">
        @if($hasMine)
            <button type="button" class="tab {{ $activeTab === 'mine' ? 'active' : '' }}" onclick="perfTab(this,'mine')">My performance</button>
        @endif
        @if($hasOverview)
            <button type="button" class="tab {{ $activeTab === 'overview' ? 'active' : '' }}" onclick="perfTab(this,'overview')">{{ $isHrRole ? 'Company' : 'My team' }}</button>
        @endif
        @if($hasReport)
            <button type="button" class="tab {{ $activeTab === 'report' ? 'active' : '' }}" onclick="perfTab(this,'report')">Employee report</button>
        @endif
        <button type="button" class="tab {{ $activeTab === 'targets' ? 'active' : '' }}" onclick="perfTab(this,'targets')">Targets</button>
    </div>

    {{-- ================= My performance ================= --}}
    @if($hasMine)
    <div class="tabpanel {{ $activeTab === 'mine' ? 'active' : '' }}" data-tabpanel="mine">
        <div class="pi-help">
            <i class="fa-solid fa-circle-info"></i>
            <div>
                Your own sales, attendance, leave, WFH, late marks and salary paid for the month picked above. Only you can see this page of numbers; your targets are in the Targets tab.
            </div>
        </div>
        @include('hr.partials.performance-mine')
    </div>
    @endif

    {{-- ================= Company / My team ================= --}}
    @if($hasOverview)
    <div class="tabpanel {{ $activeTab === 'overview' ? 'active' : '' }}" data-tabpanel="overview">
        <div class="pi-help">
            <i class="fa-solid fa-circle-info"></i>
            <div>
                {{ $isHrRole ? 'How the whole company is doing' : 'How your team is doing' }} in the month picked above.
                <ul>
                    <li><b>Sales</b> is the value of orders booked. Cancelled and rejected orders are left out.</li>
                    <li><b>Attendance</b> is the share of working days people were present.</li>
                    <li><b>Incentive paid</b> is incentive money already paid out. Money approved but not yet paid is shown under it.</li>
                </ul>
            </div>
        </div>
        @include('hr.partials.performance-insights')
    </div>
    @endif

    {{-- ================= Employee report ================= --}}
    @if($hasReport)
    <div class="tabpanel {{ $activeTab === 'report' ? 'active' : '' }}" data-tabpanel="report">
        <div class="pi-help">
            <i class="fa-solid fa-circle-info"></i>
            <div>
                One row per employee for the month picked above: sales, leave days, WFH days, late marks and the salary paid.
                {{ $isHrRole ? 'You see all active employees.' : 'You see the people who report to you.' }}
                Use <i>Download CSV</i> to open it in Excel.
            </div>
        </div>
        @include('hr.partials.performance-employee-report')
    </div>
    @endif

    {{-- ================= Targets ================= --}}
    <div class="tabpanel {{ $activeTab === 'targets' ? 'active' : '' }}" data-tabpanel="targets">
        <div class="pi-help">
            <i class="fa-solid fa-circle-info"></i>
            <div>
                @if($role === 'employee')
                    Goals your manager set for you and how much you have achieved so far. Green means reached, amber means close, red means behind.
                @elseif($role === 'manager')
                    See your own targets and set targets for the people who report to you. Leave a box empty to remove a target.
                @else
                    Set targets for any active employee. Leave a box empty to remove a target. Each person sees their own progress.
                @endif
                @if($targetPeriod)<br>Target period: <b>{{ $targetFrom->format('d M Y') }} &ndash; {{ $targetTo->format('d M Y') }}</b>@endif
            </div>
        </div>
        @include('hr.partials.performance-targets')
        @if(empty($mine['targets'] ?? []) && $targetEmployees->isEmpty())
            <div class="table-card"><div class="tc-body"><div class="empty-widget">
                <div class="ew-ico"><i class="fa-solid fa-bullseye"></i></div>
                <b>No targets yet</b>
                <span>Nothing has been set for you for this period.</span>
            </div></div></div>
        @endif
    </div>
</div>

<script>
function perfTab(btn, name) {
    const scope = document.querySelector('.content');
    scope.querySelectorAll(':scope > .tabs .tab').forEach(t => t.classList.remove('active'));
    scope.querySelectorAll(':scope > .tabpanel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    scope.querySelector('[data-tabpanel="' + name + '"]').classList.add('active');
    const field = document.getElementById('pi-tab-field');
    if (field) { field.value = name; }
    const url = new URL(window.location);
    url.searchParams.set('tab', name);
    history.replaceState(null, '', url);
}
</script>
@endsection
