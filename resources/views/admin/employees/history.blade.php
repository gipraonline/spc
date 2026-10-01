@extends('layouts.app')

@section('topbarTitle', 'Employee History')

@push('styles')
<style>
.eh { --brand:#4E7A33; --brand-strong:#0E5239; --brand-ink:#1F3D14; --brand-soft:#E4F3EB; --brand-softer:#F2F9F5;
      --line:rgba(18,58,40,.13); --line-soft:rgba(18,58,40,.07); --text:#22352C; --muted:#61756B;
      --ok:#116A38; --ok-bg:#DCF3E4; --warn:#9A5B0B; --warn-bg:#FCF0D8; --bad:#942B2B; --bad-bg:#FBE7E4;
      font-family:'Outfit',sans-serif; color:var(--text); padding:24px clamp(16px,3vw,32px) 12px; }
.eh h2,.eh h3,.eh h4 { font-family:'Kanit',sans-serif; color:var(--brand-ink); margin:0; }
.eh-top { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:16px; }
.eh-back { color:var(--brand); font-weight:600; font-size:13px; text-decoration:none; }
.eh-back:hover { text-decoration:underline; }
.eh-actions { display:flex; gap:8px; flex-wrap:wrap; }
.eh-btn { height:38px; padding:0 15px; border-radius:11px; border:1px solid var(--brand); background:var(--brand-softer); color:var(--brand);
          font:500 12.5px/1 'Outfit',sans-serif; cursor:pointer; display:inline-flex; align-items:center; gap:7px; text-decoration:none; }
.eh-btn:hover { background:var(--brand); color:#fff; }
.eh-btn-primary { background:linear-gradient(135deg,#5E8D3D,#1F5C2E); color:#fff; border-color:transparent; }
.eh-btn-primary:hover { filter:brightness(1.07); color:#fff; }
.eh-btn-danger { border-color:#C23A3A; background:#C23A3A; color:#fff; }
.eh-btn-danger:hover { filter:brightness(1.08); background:#C23A3A; color:#fff; }

.eh-hero { background:linear-gradient(135deg,#0E5239 0%,#1F5C2E 55%,#4E7A33 130%); color:#fff; border-radius:18px; padding:22px 26px;
           display:flex; gap:18px; align-items:center; flex-wrap:wrap; margin-bottom:16px; }
.eh-avatar { width:66px; height:66px; border-radius:18px; background:rgba(255,255,255,.18); border:2px solid rgba(255,255,255,.35);
             display:flex; align-items:center; justify-content:center; font:600 24px/1 'Kanit',sans-serif; flex-shrink:0; }
.eh-hero h2 { color:#fff; font-size:21px; }
.eh-hero .sub { color:rgba(255,255,255,.85); font-size:13.5px; margin-top:3px; }
.eh-chips { display:flex; gap:8px; flex-wrap:wrap; margin-top:10px; }
.eh-chip { display:inline-flex; align-items:center; gap:6px; height:26px; padding:0 11px; border-radius:999px; background:rgba(255,255,255,.16); font-size:12px; font-weight:500; }
.eh-status { margin-left:auto; text-align:right; }
.eh-pill { display:inline-block; padding:5px 13px; border-radius:999px; font-size:11.5px; font-weight:600; background:#eef2f0; color:#333; }
.eh-pill.ok { background:var(--ok-bg); color:var(--ok); } .eh-pill.warn { background:var(--warn-bg); color:var(--warn); } .eh-pill.bad { background:var(--bad-bg); color:var(--bad); }

.eh-alert { padding:11px 14px; border-radius:12px; margin-bottom:12px; font-size:13px; }
.eh-alert.ok { background:var(--ok-bg); color:var(--ok); } .eh-alert.warn { background:var(--warn-bg); color:var(--warn); } .eh-alert.bad { background:var(--bad-bg); color:var(--bad); }

.eh-kpis { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:12px; margin-bottom:16px; }
.eh-kpi { background:#fff; border:1px solid var(--line); border-radius:14px; padding:14px 16px; }
.eh-kpi small { display:block; font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); }
.eh-kpi b { display:block; font:600 20px/1.3 'Kanit',sans-serif; color:var(--brand-ink); margin-top:2px; }
.eh-kpi span { font-size:12px; color:var(--muted); }

.eh-tabs { display:flex; gap:6px; flex-wrap:wrap; border-bottom:1px solid var(--line); margin-bottom:16px; }
.eh-tab { border:0; background:none; padding:10px 16px; font:500 13px/1 'Outfit',sans-serif; color:var(--muted); cursor:pointer; border-bottom:3px solid transparent; }
.eh-tab.active { color:var(--brand); border-bottom-color:var(--brand); font-weight:600; }
.eh-panel { display:none; } .eh-panel.active { display:block; }

.eh-card { background:#fff; border:1px solid var(--line); border-radius:16px; padding:18px 20px; margin-bottom:14px; }
.eh-card h3 { font-size:14px; text-transform:uppercase; letter-spacing:.07em; color:var(--brand); margin-bottom:12px; }
.eh-kv { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:12px 20px; }
.eh-kv small { display:block; font-size:11px; text-transform:uppercase; letter-spacing:.05em; color:var(--muted); }
.eh-kv div { font-size:14px; color:var(--brand-ink); font-weight:500; word-break:break-word; }
.eh-note { background:var(--brand-softer); border:1px solid var(--line-soft); border-radius:12px; padding:11px 14px; margin-top:10px; font-size:13.5px; white-space:pre-line; }
.eh-muted { color:var(--muted); font-size:13px; }
.eh table { width:100%; border-collapse:collapse; font-size:13px; }
.eh th { text-align:left; padding:8px 10px; font-size:10.5px; letter-spacing:.07em; text-transform:uppercase; color:#5B6E63; background:#F7FBF8; border-bottom:1px solid var(--line-soft); }
.eh td { padding:9px 10px; border-bottom:1px solid var(--line-soft); vertical-align:top; }
.eh-scroll { overflow-x:auto; }

.eh-timeline { list-style:none; margin:0; padding:0 0 0 6px; }
.eh-timeline li { position:relative; padding:0 0 18px 26px; border-left:2px solid var(--line); margin-left:6px; }
.eh-timeline li:last-child { border-left-color:transparent; padding-bottom:0; }
.eh-timeline li::before { content:''; position:absolute; left:-7px; top:3px; width:12px; height:12px; border-radius:50%; background:var(--brand); border:2px solid #fff; box-shadow:0 0 0 2px var(--line); }
.eh-timeline li.exit::before { background:#C23A3A; } .eh-timeline li.notice::before,.eh-timeline li.status::before { background:#D9922B; }
.eh-timeline .when { font-size:12px; color:var(--muted); }
.eh-timeline .what { font-weight:600; color:var(--brand-ink); font-size:14px; }
.eh-timeline .chg { font-size:13px; }
.eh-timeline .rem { font-size:12.5px; color:var(--muted); margin-top:2px; }

.eh-fgrid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px 16px; }
.eh-field.full { grid-column:1/-1; }
.eh-field label { display:block; font-size:12px; font-weight:600; color:var(--muted); margin-bottom:4px; }
.eh-field input:not([type=checkbox]), .eh-field select, .eh-field textarea { width:100%; border:1px solid var(--line); border-radius:10px; padding:9px 11px; font:13px 'Outfit',sans-serif; background:#fff; }
.eh-field textarea { resize:vertical; }
.eh-check { display:flex !important; gap:8px; align-items:center; font-weight:400 !important; color:var(--text) !important; margin:4px 0 !important; }
.eh-hint { color:var(--muted); font-size:12px; }
.eh-form-actions { margin-top:16px; display:flex; gap:10px; }
@media (max-width:700px){ .eh-fgrid{grid-template-columns:1fr;} .eh-status{margin-left:0;text-align:left;} }
</style>
@endpush

@section('content')
@php
    $p = $snapshot['profile'] ?? [];
    $perf = $snapshot['performance'] ?? [];
    $att = $snapshot['attendance'] ?? [];
    $pay = $snapshot['pay'] ?? [];
    $sales = $snapshot['sales'] ?? null;
    $open = $snapshot['open_items'] ?? [];
    $money = fn ($v) => $v === null ? '—' : '₹'.number_format($v, 0);

    $name = $viewed->user->name ?? $master->c_employee_name ?? $viewed->employee_code;
    $initials = collect(preg_split('/\s+/', trim($name)))->filter()->take(2)->map(fn ($w) => strtoupper(substr($w, 0, 1)))->implode('');
    $status = $viewed->employment_status;
    $statusClass = ['active' => 'ok', 'on_notice' => 'warn', 'exited' => 'bad'][$status] ?? '';
    $statusLabel = ucfirst(str_replace('_', ' ', $status));

    $exitRow = $exit; // latest exit that is not reinstated
    $pending = $exitRow && $exitRow->reason_category === \App\Services\Hr\EmployeeExitService::PENDING_DETAILS;
    $pastExits = $exits->where('status', 'reinstated');
    $canRecord = ! $exitRow && $status === 'active' && ! $viewingSelf;
    $openTab = $errors->any() && old('exit_type') ? 'exit' : ($pending ? 'exit' : 'overview');
@endphp

<div class="eh">
    <div class="eh-top">
        <a href="{{ route('admin.employees.index') }}" class="eh-back"><i class="fa-solid fa-arrow-left"></i> Back to Employee Records</a>
        <div class="eh-actions">
            <a class="eh-btn" target="_blank" href="{{ route('admin.employees.history.file', $viewed) }}"><i class="fa-solid fa-print"></i>Print / Save as PDF</a>
            <a class="eh-btn" href="{{ route('admin.employees.history.register') }}"><i class="fa-solid fa-file-csv"></i>Exit register (CSV)</a>
            @if($canRecord)
                <button type="button" class="eh-btn eh-btn-danger" data-bs-toggle="modal" data-bs-target="#recordExitModal"><i class="fa-solid fa-person-walking-arrow-right"></i>Record resignation / termination</button>
            @endif
        </div>
    </div>

    @foreach(['success' => 'ok', 'status' => 'ok', 'warning' => 'warn', 'error' => 'bad'] as $key => $cls)
        @if(session($key))<div class="eh-alert {{ $cls }}">{{ session($key) }}</div>@endif
    @endforeach
    @if($errors->any())
        <div class="eh-alert bad">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>
    @endif

    <div class="eh-hero">
        <div class="eh-avatar">{{ $initials ?: '?' }}</div>
        <div>
            <h2>{{ $name }}</h2>
            <div class="sub">{{ $p['designation'] ?? ($viewed->designation->title ?? '—') }} · {{ $p['department'] ?? ($viewed->department->name ?? '—') }}</div>
            <div class="eh-chips">
                <span class="eh-chip"><i class="fa-solid fa-id-badge"></i>{{ $viewed->employee_code }}</span>
                <span class="eh-chip"><i class="fa-solid fa-calendar-plus"></i>Joined {{ $p['date_of_joining'] ?? '—' }}</span>
                @if($viewed->date_of_exit && $status !== 'active')
                    <span class="eh-chip"><i class="fa-solid fa-calendar-xmark"></i>Last day {{ \Carbon\Carbon::parse($viewed->date_of_exit)->format('d M Y') }}</span>
                @endif
            </div>
        </div>
        <div class="eh-status">
            <span class="eh-pill {{ $statusClass }}">{{ $statusLabel }}</span>
            <div style="font-size:12px;color:rgba(255,255,255,.8);margin-top:6px;">
                {{ $isFormer ? 'Snapshot frozen '.\Carbon\Carbon::parse($snapshot['captured_at'] ?? now())->format('d M Y') : 'Live — as of '.now()->format('d M Y') }}
            </div>
            <div style="font-size:11px;color:rgba(255,255,255,.7);">CONFIDENTIAL — HR use only</div>
        </div>
    </div>

    <div class="eh-kpis">
        <div class="eh-kpi"><small>Tenure</small><b>{{ $p['tenure_text'] ?? '—' }}</b></div>
        <div class="eh-kpi"><small>Avg appraisal rating</small><b>{{ $perf['average_rating'] ?? '—' }}</b><span>{{ $perf['appraisal_count'] ?? 0 }} review(s)</span></div>
        <div class="eh-kpi"><small>Attendance (12 mo)</small><b>{{ $att['present'] ?? 0 }} present</b><span>{{ $att['late'] ?? 0 }} late · {{ $att['absent'] ?? 0 }} absent</span></div>
        <div class="eh-kpi"><small>Net sales (12 mo)</small><b>{{ $sales ? $money($sales['net_sales_12m']) : '—' }}</b><span>{{ $sales ? $sales['orders_12m'].' orders' : 'No SPC sales data' }}</span></div>
    </div>

    <div class="eh-tabs" id="ehTabs">
        @foreach(['overview' => 'Overview', 'performance' => 'Performance', 'timeline' => 'Career timeline', 'exit' => 'Exit / separation'] as $key => $label)
            <button type="button" class="eh-tab {{ $openTab === $key ? 'active' : '' }}" data-tab="{{ $key }}">
                {{ $label }}@if($key === 'exit' && $exitRow) <span class="eh-pill {{ $status === 'on_notice' ? 'warn' : 'bad' }}" style="margin-left:6px;padding:2px 8px;">{{ $status === 'on_notice' ? 'On notice' : 'Exited' }}</span>@endif
            </button>
        @endforeach
    </div>

    {{-- ============================ OVERVIEW ============================ --}}
    <div class="eh-panel {{ $openTab === 'overview' ? 'active' : '' }}" data-panel="overview">
        <div class="eh-card">
            <h3>Employment details</h3>
            <div class="eh-kv">
                <div><small>Designation</small>{{ $p['designation'] ?? '—' }}</div>
                <div><small>Department</small>{{ $p['department'] ?? '—' }}</div>
                <div><small>Reporting manager</small>{{ $p['reporting_manager'] ?? '—' }}</div>
                <div><small>Date of joining</small>{{ $p['date_of_joining'] ?? '—' }}</div>
                <div><small>Work email</small>{{ $p['work_email'] ?? '—' }}</div>
                <div><small>Personal email</small>{{ $p['personal_email'] ?? '—' }}</div>
                <div><small>Phone</small>{{ $p['phone'] ?? '—' }}</div>
                <div><small>City</small>{{ $p['city'] ?? '—' }}</div>
                <div><small>Portal role</small>{{ $p['portal_role'] ?? '—' }}</div>
            </div>
        </div>

        <div class="eh-card">
            <h3>Attendance &amp; leave — last 12 months</h3>
            <div class="eh-kv">
                <div><small>Present</small>{{ $att['present'] ?? 0 }}</div>
                <div><small>Late</small>{{ $att['late'] ?? 0 }} ({{ $att['total_late_minutes'] ?? 0 }} min)</div>
                <div><small>Half day</small>{{ $att['half_day'] ?? 0 }}</div>
                <div><small>Absent</small>{{ $att['absent'] ?? 0 }}</div>
                <div><small>On leave</small>{{ $att['on_leave'] ?? 0 }}</div>
                <div><small>Days recorded</small>{{ $att['days_recorded'] ?? 0 }}</div>
            </div>
            @if(!empty($snapshot['leave_12m']))
                <div class="eh-muted" style="margin-top:10px;">Approved leave: {{ collect($snapshot['leave_12m'])->map(fn ($l) => $l['type'].' '.rtrim(rtrim(number_format($l['days'], 2), '0'), '.').'d')->implode(' · ') }}</div>
            @endif
        </div>

        <div class="eh-card">
            <h3>Pay &amp; incentives</h3>
            <div class="eh-kv">
                <div><small>Gross monthly (latest)</small>{{ $money($pay['gross_monthly_at_exit'] ?? null) }}</div>
                <div><small>Salary revisions</small>{{ $pay['salary_revisions'] ?? 0 }}</div>
                <div><small>Incentive paid (12 mo)</small>{{ $money($pay['incentive_paid_12m'] ?? null) }}</div>
            </div>
        </div>

        @if($sales)
        <div class="eh-card">
            <h3>Sales activity (SPC)</h3>
            <div class="eh-kv">
                <div><small>Net sales, 12 months</small>{{ $money($sales['net_sales_12m']) }} ({{ $sales['orders_12m'] }} orders)</div>
                <div><small>Net sales, lifetime</small>{{ $money($sales['net_sales_lifetime']) }} ({{ $sales['orders_lifetime'] }} orders)</div>
                <div><small>Leads handled</small>{{ $sales['leads_total'] }}</div>
            </div>
            @if(!empty($sales['leads_by_status']))
                <div class="eh-muted" style="margin-top:10px;">Leads by status: {{ collect($sales['leads_by_status'])->map(fn ($n, $s) => "$s: $n")->implode(' · ') }}</div>
            @endif
        </div>
        @endif

        <div class="eh-card">
            <h3>Documents on file</h3>
            @if(empty($snapshot['documents']))
                <div class="eh-muted">No documents uploaded.</div>
            @else
                <div>{{ collect($snapshot['documents'])->map(fn ($d) => $d['document_type'].' ('.$d['status'].')')->implode(' · ') }}</div>
            @endif
        </div>
    </div>

    {{-- ============================ PERFORMANCE ============================ --}}
    <div class="eh-panel {{ $openTab === 'performance' ? 'active' : '' }}" data-panel="performance">
        <div class="eh-card">
            <h3>Appraisal summary</h3>
            <div class="eh-kv">
                <div><small>Average rating</small>{{ $perf['average_rating'] ?? '—' }}</div>
                <div><small>Latest rating</small>{{ $perf['latest_rating'] ?? '—' }}</div>
                <div><small>Best / lowest</small>{{ $perf['best_rating'] ?? '—' }} / {{ $perf['lowest_rating'] ?? '—' }}</div>
                @if($exitRow?->overall_rating)<div><small>Closing rating at exit</small>{{ $exitRow->overall_rating }} / 5</div>@endif
            </div>
        </div>
        <div class="eh-card">
            <h3>Appraisal cycles</h3>
            @if(!empty($perf['appraisals']))
                <div class="eh-scroll">
                <table>
                    <thead><tr><th>Cycle</th><th>Status</th><th>Rating</th><th>Manager review</th></tr></thead>
                    <tbody>
                    @foreach($perf['appraisals'] as $a)
                        <tr>
                            <td>{{ $a['cycle'] }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $a['status'])) }}</td>
                            <td>{{ $a['final_rating'] ?? '—' }}</td>
                            <td>{{ $a['manager_review'] ?: '—' }}</td>
                        </tr>
                        @foreach($a['goals'] ?? [] as $g)
                            <tr><td></td><td colspan="3" class="eh-muted">Goal: {{ $g['goal_text'] }} ({{ $g['weight_percent'] }}%) — self {{ $g['self_rating'] ?? '—' }}, manager {{ $g['manager_rating'] ?? '—' }}</td></tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
                </div>
            @else
                <div class="eh-muted">No appraisals on record.</div>
            @endif
        </div>
    </div>

    {{-- ============================ TIMELINE ============================ --}}
    <div class="eh-panel {{ $openTab === 'timeline' ? 'active' : '' }}" data-panel="timeline">
        <div class="eh-card">
            <h3>Career timeline</h3>
            @if($history->isEmpty())
                <div class="eh-muted">No history recorded yet. Promotions, transfers, manager changes, notice and exit will appear here.</div>
            @else
                <ul class="eh-timeline">
                    @foreach($history as $h)
                        <li class="{{ $h->event_type }}">
                            <div class="when">{{ optional($h->effective_date)->format('d M Y') }}@if($h->changedBy) · by {{ $h->changedBy->name }}@endif</div>
                            <div class="what">{{ \App\Models\Hr\EmployeeHistory::EVENTS[$h->event_type ?? 'change'][0] ?? 'Change' }} — {{ $h->field_changed }}</div>
                            <div class="chg">{{ $h->old_value ?? '—' }} → {{ $h->new_value ?? '—' }}</div>
                            @if($h->remarks)<div class="rem">{{ $h->remarks }}</div>@endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- ============================ EXIT ============================ --}}
    <div class="eh-panel {{ $openTab === 'exit' ? 'active' : '' }}" data-panel="exit">
        @if($exitRow)
            @if($pending)
                <div class="eh-alert warn">This employee was deactivated / deleted from Employee Records without exit details. Add the exit type, reason and clearance below so the record is complete.</div>
            @endif

            <div class="eh-card">
                <h3>Exit record</h3>
                <div class="eh-kv">
                    <div><small>Type</small>{{ $exitRow->typeLabel() }} ({{ $exitRow->initiated_by === 'company' ? 'company initiated' : 'employee initiated' }})</div>
                    <div><small>Notice given</small>{{ $exitRow->notice_date?->format('d M Y') ?? '—' }}</div>
                    <div><small>Last working day</small>{{ $exitRow->last_working_day->format('d M Y') }}</div>
                    <div><small>Notice served / required</small>{{ $exitRow->notice_served_days ?? '—' }} / {{ $exitRow->notice_period_days ?? '—' }} days{{ $exitRow->notice_waived ? ' (waived)' : '' }}</div>
                    <div><small>Reason category</small>{{ $pending ? '—' : ($exitRow->reason_category ?? '—') }}</div>
                    <div><small>Eligible for rehire</small>{{ ucfirst($exitRow->eligible_for_rehire) }}</div>
                </div>
                @if($exitRow->reason && !$pending)<div class="eh-note"><b>Reason:</b> {{ $exitRow->reason }}</div>@endif
                @if($exitRow->rehire_remarks)<div class="eh-note"><b>Rehire remarks:</b> {{ $exitRow->rehire_remarks }}</div>@endif
                @if($exitRow->manager_remarks)<div class="eh-note"><b>Manager / HR remarks:</b> {{ $exitRow->manager_remarks }}</div>@endif
                @if($exitRow->exit_interview_done)<div class="eh-note"><b>Exit interview:</b> {{ $exitRow->exit_interview_notes ?: 'Completed (no notes recorded).' }}</div>@endif
                @if($exitRow->final_settlement_notes)<div class="eh-note"><b>Final settlement:</b> {{ $exitRow->final_settlement_notes }}</div>@endif
                @if(!empty($open['direct_reports_at_exit']))<div class="eh-note"><b>Team that reported to them at exit:</b> {{ implode(', ', $open['direct_reports_at_exit']) }}</div>@endif

                <div class="eh-scroll" style="margin-top:12px;">
                <table>
                    <thead><tr><th>Clearance item</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach(\App\Models\Hr\EmployeeExit::CLEARANCE_ITEMS as $k => $label)
                        <tr><td>{{ $label }}</td><td>@if(!empty($exitRow->clearance[$k]))<span class="eh-pill ok">Done</span>@else<span class="eh-pill warn">Pending</span>@endif</td></tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>

            <div class="eh-card">
                <h3>{{ $pending ? 'Add exit details' : 'Edit exit details' }}</h3>
                @include('admin.employees.partials.exit-form', [
                    'action' => route('admin.employees.history.exit.update', $exitRow),
                    'exitRow' => $exitRow,
                ])
            </div>

            @unless($viewingSelf)
            <div class="eh-card">
                <h3>Reinstate / rehire</h3>
                <p class="eh-muted" style="margin-top:0;">Re-enables their HR and SPC logins. This exit stays in their history.</p>
                <form method="POST" action="{{ route('admin.employees.history.exit.reinstate', $viewed) }}"
                      onsubmit="return confirm('Reinstate {{ addslashes($name) }}? Their HR and SPC logins will be re-enabled; this exit stays in their history.')">
                    @csrf
                    <div class="eh-field"><input name="remarks" maxlength="1000" placeholder="Reason for reinstating (optional)"></div>
                    <div class="eh-form-actions"><button type="submit" class="eh-btn">Reinstate employee</button></div>
                </form>
            </div>
            @endunless
        @elseif($status !== 'active')
            <div class="eh-alert warn">This employee is marked {{ str_replace('_', ' ', $status) }} but has no exit record. Run <code>php artisan hr:backfill-history</code> to create one.</div>
        @else
            <div class="eh-card">
                <h3>Not separated</h3>
                <p class="eh-muted" style="margin:0 0 12px;">{{ $name }} is currently working. If they resign or are terminated, record it here — their details and performance are frozen into this history.</p>
                @if($canRecord)
                    <button type="button" class="eh-btn eh-btn-danger" data-bs-toggle="modal" data-bs-target="#recordExitModal"><i class="fa-solid fa-person-walking-arrow-right"></i>Record resignation / termination</button>
                @else
                    <div class="eh-muted">You can't record your own exit.</div>
                @endif
            </div>
        @endif

        @if($pastExits->isNotEmpty())
            <div class="eh-card">
                <h3>Earlier separations</h3>
                <div class="eh-scroll">
                <table>
                    <thead><tr><th>Type</th><th>Last day</th><th>Reason</th><th>Reinstated</th></tr></thead>
                    <tbody>
                    @foreach($pastExits as $x)
                        <tr>
                            <td>{{ $x->typeLabel() }}</td>
                            <td>{{ $x->last_working_day->format('d M Y') }}</td>
                            <td>{{ $x->reason_category ?: '—' }}{{ $x->reason ? ' — '.\Illuminate\Support\Str::limit($x->reason, 90) : '' }}</td>
                            <td>{{ $x->reinstated_at?->format('d M Y') ?? '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        @endif
    </div>
</div>

@if($canRecord)
<div class="modal fade" id="recordExitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content eh" style="padding:0;">
            <div class="modal-header">
                <h5 class="modal-title" style="font-family:'Kanit',sans-serif;">Record resignation / termination — {{ $name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="eh-muted" style="margin-top:0;">Saving freezes {{ $name }}'s details and performance into their history. If the last working day is in the future they stay <b>On notice</b> and can still sign in until that day.</p>
                @include('admin.employees.partials.exit-form', [
                    'action' => route('admin.employees.history.exit.store', $viewed),
                    'exitRow' => null,
                ])
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
(function () {
    const tabs = document.querySelectorAll('#ehTabs .eh-tab');
    const panels = document.querySelectorAll('.eh-panel');
    function show(key) {
        tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === key));
        panels.forEach(p => p.classList.toggle('active', p.dataset.panel === key));
        history.replaceState(null, '', '#' + key);
    }
    tabs.forEach(t => t.addEventListener('click', () => show(t.dataset.tab)));
    const fromHash = location.hash.replace('#', '');
    if (fromHash && document.querySelector('.eh-panel[data-panel="' + fromHash + '"]')) show(fromHash);

    @if($canRecord && $errors->any() && old('exit_type'))
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('recordExitModal')).show();
    });
    @endif
})();
</script>
@endpush
@endsection
