<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
    <link rel="shortcut icon" type="image/png" href="{{ asset('dist/images/logos/fav.png') }}" />
<title>Employee file — {{ $viewed->user->name ?? $viewed->employee_code }}</title>
<style>
    :root{--ink:#1c2a22;--muted:#5d6b63;--line:#dfe7e2;--brand:#1F5C2E;--bad:#b42318;--warn:#b54708;--ok:#067647}
    *{box-sizing:border-box}
    body{font:13px/1.5 system-ui,-apple-system,Segoe UI,Roboto,sans-serif;color:var(--ink);margin:0;background:#f5f7f6}
    .page{max-width:900px;margin:24px auto;background:#fff;padding:32px 36px;border:1px solid var(--line);border-radius:12px}
    h1{margin:0;font-size:22px} h2{font-size:14px;text-transform:uppercase;letter-spacing:.06em;color:var(--brand);margin:26px 0 8px;border-bottom:2px solid var(--line);padding-bottom:4px}
    .muted{color:var(--muted)} .row{display:flex;gap:14px;flex-wrap:wrap}
    .kv{display:grid;grid-template-columns:repeat(3,1fr);gap:10px 18px}
    .kv div small{display:block;color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:.04em}
    table{width:100%;border-collapse:collapse;margin-top:6px} th,td{text-align:left;padding:6px 8px;border-bottom:1px solid var(--line);vertical-align:top}
    th{font-size:11px;text-transform:uppercase;color:var(--muted);letter-spacing:.04em}
    .badge{display:inline-block;padding:2px 9px;border-radius:99px;font-size:11.5px;font-weight:600;background:#eef2f0}
    .bad{background:#fee4e2;color:var(--bad)} .warn{background:#fef0c7;color:var(--warn)} .ok{background:#dcfae6;color:var(--ok)}
    .box{border:1px solid var(--line);border-radius:10px;padding:12px 14px;margin:8px 0;background:#fafcfb;white-space:pre-line}
    .toolbar{max-width:900px;margin:16px auto 0;display:flex;justify-content:flex-end;gap:8px}
    .toolbar button,.toolbar a{padding:8px 14px;border:1px solid var(--line);background:#fff;border-radius:8px;cursor:pointer;text-decoration:none;color:var(--ink)}
    @media print{body{background:#fff}.page{border:0;margin:0;padding:0;max-width:none}.toolbar{display:none}h2{break-after:avoid}table{break-inside:auto}tr{break-inside:avoid}}
</style>
</head>
<body>
@php
    $p = $snapshot['profile'] ?? [];
    $perf = $snapshot['performance'] ?? [];
    $att = $snapshot['attendance'] ?? [];
    $pay = $snapshot['pay'] ?? [];
    $sales = $snapshot['sales'] ?? null;
    $open = $snapshot['open_items'] ?? [];
    $statusClass = ['active'=>'ok','on_notice'=>'warn','exited'=>'bad'][$viewed->employment_status] ?? '';
    $money = fn($v) => $v === null ? '—' : '₹'.number_format($v, 0);
@endphp

<div class="toolbar">
    <a href="{{ $backUrl ?? route('hr.records.index', ['employee' => $viewed->id]) }}">← Back</a>
    <button onclick="window.print()">Print / Save as PDF</button>
</div>

<div class="page">
    <div class="row" style="justify-content:space-between;align-items:flex-start">
        <div>
            <h1>{{ $viewed->user->name ?? '—' }}</h1>
            <div class="muted">{{ $viewed->employee_code }} · {{ $p['designation'] ?? ($viewed->designation->title ?? '—') }} · {{ $p['department'] ?? ($viewed->department->name ?? '—') }}</div>
        </div>
        <div style="text-align:right">
            <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_',' ',$viewed->employment_status)) }}</span>
            <div class="muted" style="margin-top:6px">{{ $isFormer ? 'Snapshot frozen '.($snapshot['captured_at'] ?? '') : 'Generated '.now()->format('d M Y, H:i') }}</div>
            <div class="muted">CONFIDENTIAL — HR use only</div>
        </div>
    </div>

    <h2>Employment details</h2>
    <div class="kv">
        <div><small>Joined</small>{{ $p['date_of_joining'] ?? '—' }}</div>
        <div><small>Last working day</small>{{ $isFormer ? ($p['last_working_day'] ?? '—') : '—' }}</div>
        <div><small>Tenure</small>{{ $p['tenure_text'] ?? '—' }}</div>
        <div><small>Reporting manager</small>{{ $p['reporting_manager'] ?? '—' }}</div>
        <div><small>Portal role</small>{{ $p['portal_role'] ?? '—' }}</div>
        <div><small>City</small>{{ $p['city'] ?? '—' }}</div>
        <div><small>Work email</small>{{ $p['work_email'] ?? '—' }}</div>
        <div><small>Personal email</small>{{ $p['personal_email'] ?? '—' }}</div>
        <div><small>Phone</small>{{ $p['phone'] ?? '—' }}</div>
    </div>

    @if($exit && $exit->status !== 'reinstated')
    <h2>Exit</h2>
    <div class="kv">
        <div><small>Type</small>{{ $exit->typeLabel() }} ({{ $exit->initiated_by === 'company' ? 'company initiated' : 'employee initiated' }})</div>
        <div><small>Notice given</small>{{ $exit->notice_date?->format('d M Y') ?? '—' }}</div>
        <div><small>Last working day</small>{{ $exit->last_working_day->format('d M Y') }}</div>
        <div><small>Notice served / required</small>{{ $exit->notice_served_days ?? '—' }} / {{ $exit->notice_period_days ?? '—' }} days{{ $exit->notice_waived ? ' (waived)' : '' }}</div>
        <div><small>Reason category</small>{{ $exit->reason_category ?? '—' }}</div>
        <div><small>Eligible for rehire</small>{{ ucfirst($exit->eligible_for_rehire) }}</div>
    </div>
    @if($exit->reason)<div class="box"><b>Reason:</b> {{ $exit->reason }}</div>@endif
    @if($exit->rehire_remarks)<div class="box"><b>Rehire remarks:</b> {{ $exit->rehire_remarks }}</div>@endif
    @if($exit->manager_remarks)<div class="box"><b>Manager / HR remarks:</b> {{ $exit->manager_remarks }}{{ $exit->overall_rating ? "\n".'Closing rating: '.$exit->overall_rating.' / 5' : '' }}</div>@endif
    @if($exit->exit_interview_done)<div class="box"><b>Exit interview:</b> {{ $exit->exit_interview_notes ?: 'Completed (no notes recorded).' }}</div>@else<p class="muted">Exit interview not done.</p>@endif
    <table>
        <thead><tr><th>Clearance item</th><th>Status</th></tr></thead>
        <tbody>
        @foreach(\App\Models\Hr\EmployeeExit::CLEARANCE_ITEMS as $k => $label)
            <tr><td>{{ $label }}</td><td>{!! !empty($exit->clearance[$k]) ? '<span class="badge ok">Done</span>' : '<span class="badge warn">Pending</span>' !!}</td></tr>
        @endforeach
        </tbody>
    </table>
    @if($exit->final_settlement_notes)<div class="box"><b>Final settlement:</b> {{ $exit->final_settlement_notes }}</div>@endif
    @if(!empty($open['direct_reports_at_exit']))<div class="box"><b>Team that reported to them at exit:</b> {{ implode(', ', $open['direct_reports_at_exit']) }}</div>@endif
    @endif

    <h2>Performance</h2>
    <div class="kv">
        <div><small>Average rating</small>{{ $perf['average_rating'] ?? '—' }}</div>
        <div><small>Latest rating</small>{{ $perf['latest_rating'] ?? '—' }}</div>
        <div><small>Best / lowest</small>{{ $perf['best_rating'] ?? '—' }} / {{ $perf['lowest_rating'] ?? '—' }}</div>
    </div>
    @if(!empty($perf['appraisals']))
    <table>
        <thead><tr><th>Cycle</th><th>Status</th><th>Rating</th><th>Manager review</th></tr></thead>
        <tbody>
        @foreach($perf['appraisals'] as $a)
            <tr>
                <td>{{ $a['cycle'] }}</td>
                <td>{{ ucfirst(str_replace('_',' ',$a['status'])) }}</td>
                <td>{{ $a['final_rating'] ?? '—' }}</td>
                <td>{{ $a['manager_review'] ?: '—' }}</td>
            </tr>
            @foreach($a['goals'] ?? [] as $g)
            <tr><td></td><td colspan="3" class="muted">Goal: {{ $g['goal_text'] }} ({{ $g['weight_percent'] }}%) — self {{ $g['self_rating'] ?? '—' }}, manager {{ $g['manager_rating'] ?? '—' }}</td></tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
    @else
        <p class="muted">No appraisals on record.</p>
    @endif

    <h2>Attendance &amp; leave (last 12 months)</h2>
    <div class="kv">
        <div><small>Present</small>{{ $att['present'] ?? 0 }}</div>
        <div><small>Late</small>{{ $att['late'] ?? 0 }} ({{ $att['total_late_minutes'] ?? 0 }} min)</div>
        <div><small>Half day</small>{{ $att['half_day'] ?? 0 }}</div>
        <div><small>Absent</small>{{ $att['absent'] ?? 0 }}</div>
        <div><small>On leave</small>{{ $att['on_leave'] ?? 0 }}</div>
        <div><small>Days recorded</small>{{ $att['days_recorded'] ?? 0 }}</div>
    </div>
    @if(!empty($snapshot['leave_12m']))
        <p class="muted" style="margin-top:8px">Approved leave: {{ collect($snapshot['leave_12m'])->map(fn($l) => $l['type'].' '.rtrim(rtrim(number_format($l['days'],2),'0'),'.').'d')->implode(' · ') }}</p>
    @endif

    <h2>Pay &amp; incentives</h2>
    <div class="kv">
        <div><small>Gross monthly (latest)</small>{{ $money($pay['gross_monthly_at_exit'] ?? null) }}</div>
        <div><small>Salary revisions</small>{{ $pay['salary_revisions'] ?? 0 }}</div>
        <div><small>Incentive paid (12 mo)</small>{{ $money($pay['incentive_paid_12m'] ?? null) }}</div>
    </div>

    @if($sales)
    <h2>Sales activity (SPC)</h2>
    <div class="kv">
        <div><small>Net sales, 12 mo</small>{{ $money($sales['net_sales_12m']) }} ({{ $sales['orders_12m'] }} orders)</div>
        <div><small>Net sales, lifetime</small>{{ $money($sales['net_sales_lifetime']) }} ({{ $sales['orders_lifetime'] }} orders)</div>
        <div><small>Leads handled</small>{{ $sales['leads_total'] }}</div>
    </div>
    @if(!empty($sales['leads_by_status']))
        <p class="muted" style="margin-top:8px">Leads by status: {{ collect($sales['leads_by_status'])->map(fn($n,$s) => "$s: $n")->implode(' · ') }}</p>
    @endif
    @endif

    <h2>Career timeline</h2>
    @if($history->isEmpty())
        <p class="muted">No history recorded.</p>
    @else
    <table>
        <thead><tr><th>Date</th><th>Event</th><th>Change</th><th>By</th><th>Remarks</th></tr></thead>
        <tbody>
        @foreach($history as $h)
            <tr>
                <td>{{ optional($h->effective_date)->format('d M Y') }}</td>
                <td>{{ \App\Models\Hr\EmployeeHistory::EVENTS[$h->event_type ?? 'change'][0] ?? 'Change' }}</td>
                <td>{{ $h->field_changed }}: {{ $h->old_value ?? '—' }} → {{ $h->new_value ?? '—' }}</td>
                <td>{{ $h->changedBy->name ?? '—' }}</td>
                <td>{{ $h->remarks }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif

    @if($exits->where('status','reinstated')->isNotEmpty())
    <h2>Earlier separations</h2>
    <table>
        <thead><tr><th>Type</th><th>Last day</th><th>Reason</th><th>Reinstated</th></tr></thead>
        <tbody>
        @foreach($exits->where('status','reinstated') as $x)
            <tr><td>{{ $x->typeLabel() }}</td><td>{{ $x->last_working_day->format('d M Y') }}</td><td>{{ $x->reason_category }} {{ $x->reason }}</td><td>{{ $x->reinstated_at?->format('d M Y') }}</td></tr>
        @endforeach
        </tbody>
    </table>
    @endif

    <h2>Documents on file</h2>
    @if(empty($snapshot['documents']))
        <p class="muted">None.</p>
    @else
        <p>{{ collect($snapshot['documents'])->map(fn($d) => $d['document_type'].' ('.$d['status'].')')->implode(' · ') }}</p>
    @endif
</div>
</body>
</html>
