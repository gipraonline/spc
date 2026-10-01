@extends('layouts.app')

@push('styles')
<style>
.fc{--sb:var(--brand,#5E8D3D);--sink:#1F3D14;--smut:#61756B;--sline:rgba(18,58,40,.12);}
.fc-head{display:flex;justify-content:space-between;align-items:flex-end;gap:14px;flex-wrap:wrap;margin-bottom:16px;}
.fc-head h2{margin:0;font-family:var(--font-head,'Kanit',sans-serif);font-size:22px;font-weight:600;color:var(--sink);}
.fc-head p{margin:4px 0 0;font-size:13px;color:var(--smut);}
.fc-filter{display:flex;gap:10px;flex-wrap:wrap;align-items:center;}
.fc-filter select{height:40px;border:1px solid #d5dde3;border-radius:10px;padding:0 12px;font-size:13.5px;background:#fff;}
.fc-filter button{height:40px;border:0;border-radius:10px;padding:0 16px;font-weight:600;color:#fff;background:linear-gradient(135deg,#7CA243,#1F5C2E);}
.fc-kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:16px;}
.fc-kpi{background:#fff;border:1px solid var(--sline);border-radius:14px;padding:14px 16px;}
.fc-kpi b{display:block;font-size:24px;color:var(--sink);}
.fc-kpi span{font-size:12px;color:var(--smut);}
.fc-kpi.bad b{color:#B42318;}.fc-kpi.warn b{color:#B36B00;}
.fc-cols{display:grid;grid-template-columns:repeat(auto-fit,minmax(290px,1fr));gap:14px;margin-bottom:18px;}
.fc-col,.fc-card{background:#fff;border:1px solid var(--sline);border-radius:16px;overflow:hidden;}
.fc-col h3,.fc-card h3{margin:0;padding:13px 16px;font-size:13px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--smut);border-bottom:1px solid var(--sline);display:flex;justify-content:space-between;}
.fc-col.o h3{color:#B42318;background:#fef3f2;}.fc-col.t h3{color:#B36B00;background:#fff8e6;}.fc-col.u h3{color:#1D6FA5;background:#eef6fc;}
.fc-item{display:block;padding:11px 16px;border-bottom:1px solid #f1f5f9;text-decoration:none;color:inherit;}
.fc-item:hover{background:#fafcf8;}
.fc-item b{color:var(--sink);font-size:14px;}
.fc-item small{display:block;color:var(--smut);font-size:12px;margin-top:2px;}
.fc-tag{float:right;font-size:11px;font-weight:700;border-radius:20px;padding:2px 9px;background:#eef2f6;color:#475569;}
.fc-tag.hi{background:#fde8e8;color:#B42318;}.fc-tag.md{background:#fff4dd;color:#B36B00;}
.fc-empty{padding:22px 16px;color:var(--smut);font-size:13px;text-align:center;}
.fc-more{padding:10px 16px;font-size:12px;color:var(--smut);background:#f8fafc;}
.fc-fun{padding:14px 16px;}
.fc-step{display:flex;align-items:center;gap:10px;margin:8px 0;font-size:13px;}
.fc-step .l{width:110px;color:var(--smut);flex-shrink:0;}
.fc-step .t{flex:1;height:22px;background:#eef5e6;border-radius:6px;overflow:hidden;}
.fc-step .t i{display:block;height:100%;background:linear-gradient(90deg,#7CA243,#1F5C2E);min-width:2px;}
.fc-step .n{width:48px;text-align:right;font-weight:700;color:var(--sink);}
.fc-tbl{width:100%;border-collapse:collapse;font-size:13.5px;}
.fc-tbl th{background:#f8fafc;color:#64748b;font-size:11px;text-transform:uppercase;letter-spacing:.06em;padding:11px 14px;text-align:left;}
.fc-tbl td{padding:11px 14px;border-top:1px solid #f1f5f9;}
.fc-grid2{display:grid;grid-template-columns:minmax(280px,1fr) 2fr;gap:14px;}
@media(max-width:900px){.fc-grid2{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')
@php
    $tagClass = fn ($p) => in_array(strtolower((string) $p), ['high', 'urgent'], true) ? 'hi' : (strtolower((string) $p) === 'medium' ? 'md' : '');
    $maxFun = max(1, collect($funnel['stages'])->max('n'));
    $columns = [
        ['key' => 'overdue', 'title' => 'Overdue', 'cls' => 'o'],
        ['key' => 'today', 'title' => 'Due today', 'cls' => 't'],
        ['key' => 'upcoming', 'title' => 'Next 7 days', 'cls' => 'u'],
    ];
@endphp
<div class="fc">
    <div class="fc-head">
        <div>
            <h2>Follow-up Cockpit</h2>
            <p>Who to call or visit next, and how leads become orders.</p>
        </div>
        <form method="GET" class="fc-filter">
            @if($advisors->count() > 1)
                <select name="fca" aria-label="Advisor">
                    <option value="">All advisors</option>
                    @foreach($advisors as $a)
                        <option value="{{ $a->id }}" @selected($fca === (int) $a->id)>{{ $a->name }}</option>
                    @endforeach
                </select>
            @endif
            <select name="days" aria-label="Funnel period">
                @foreach([7 => 'Last 7 days', 30 => 'Last 30 days', 90 => 'Last 90 days', 365 => 'Last 12 months'] as $d => $label)
                    <option value="{{ $d }}" @selected($days === $d)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit">Apply</button>
        </form>
    </div>

    <div class="fc-kpis">
        <div class="fc-kpi bad"><b>{{ $board['overdue']['total'] }}</b><span>Overdue follow-ups</span></div>
        <div class="fc-kpi warn"><b>{{ $board['today']['total'] }}</b><span>Due today</span></div>
        <div class="fc-kpi"><b>{{ $board['upcoming']['total'] }}</b><span>Coming in 7 days</span></div>
        <div class="fc-kpi"><b>{{ $board['unscheduled'] }}</b><span>Open leads with no follow-up date</span></div>
        <div class="fc-kpi"><b>{{ $funnel['conversion_pct'] !== null ? $funnel['conversion_pct'].'%' : '—' }}</b><span>Lead → order ({{ $days }} d)</span></div>
    </div>

    <div class="fc-cols">
        @foreach($columns as $c)
            @php $col = $board[$c['key']]; @endphp
            <div class="fc-col {{ $c['cls'] }}">
                <h3><span>{{ $c['title'] }}</span><span>{{ $col['total'] }}</span></h3>
                @forelse($col['rows'] as $r)
                    <a class="fc-item" href="{{ route('admin.leads.show', $r->id) }}">
                        @if($r->priority)<span class="fc-tag {{ $tagClass($r->priority) }}">{{ $r->priority }}</span>@endif
                        <b>{{ $r->name }}</b>
                        <small>
                            {{ $r->followup_type ?: 'Follow-up' }}
                            · {{ \Illuminate\Support\Carbon::parse($r->due_date)->format('d M') }}{{ $r->due_time ? ' '.\Illuminate\Support\Carbon::parse($r->due_time)->format('h:i A') : '' }}
                            @if($c['key'] === 'overdue' && $r->days_overdue) · {{ $r->days_overdue }}d late @endif
                            @if($r->fca && ($isSupervisor)) · {{ $r->fca }} @endif
                        </small>
                        @if($r->mobile)<small>{{ $r->mobile }} · {{ $r->status ?: 'New' }}</small>@endif
                    </a>
                @empty
                    <div class="fc-empty">Nothing here.</div>
                @endforelse
                @if($col['total'] > count($col['rows']))
                    <div class="fc-more">Showing first {{ count($col['rows']) }} of {{ $col['total'] }}. Use the advisor filter to narrow down.</div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="fc-grid2">
        <div class="fc-card">
            <h3><span>Lead funnel</span><span>{{ $from->format('d M') }} – {{ $to->format('d M') }}</span></h3>
            <div class="fc-fun">
                @foreach($funnel['stages'] as $st)
                    <div class="fc-step">
                        <span class="l">{{ $st['label'] }}</span>
                        <span class="t"><i style="width:{{ round($st['n'] / $maxFun * 100) }}%"></i></span>
                        <span class="n">{{ $st['n'] }}</span>
                    </div>
                @endforeach
                <div class="fc-step" style="margin-top:12px;"><span class="l" style="width:auto;">Lost / not interested: <b>{{ $funnel['lost'] }}</b></span></div>
                <p style="font-size:11.5px;color:var(--smut);margin:10px 0 0;">Orders are matched to leads by mobile number, on or after the lead date, excluding rejected/cancelled orders.</p>
            </div>
        </div>

        @if(count($team))
            <div class="fc-card" style="overflow-x:auto;">
                <h3><span>By advisor</span><span>most overdue first</span></h3>
                <table class="fc-tbl">
                    <thead><tr><th>Advisor</th><th>Open</th><th>Overdue</th><th>Today</th><th>New ({{ $days }} d)</th><th>Won</th></tr></thead>
                    <tbody>
                        @foreach($team as $t)
                            <tr>
                                <td><a href="{{ route('admin.leads.cockpit', ['fca' => $t['id'], 'days' => $days]) }}"><b>{{ $t['name'] }}</b></a></td>
                                <td>{{ $t['open'] }}</td>
                                <td style="{{ $t['overdue'] ? 'color:#B42318;font-weight:700;' : '' }}">{{ $t['overdue'] }}</td>
                                <td>{{ $t['today'] }}</td>
                                <td>{{ $t['created'] }}</td>
                                <td>{{ $t['won'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
