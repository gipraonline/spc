{{-- Exit tab for the HR profile card. HR / Super Admin only. --}}
@php
    use App\Models\Hr\EmployeeExit;

    $exitRow = $viewed->exits->first(fn ($x) => in_array($x->status, ['on_notice', 'completed']));
    $pastExits = $viewed->exits->where('status', 'reinstated');
    $isSelfView = $employee && $employee->id === $viewed->id;
    $pending = $exitRow && $exitRow->reason_category === \App\Services\Hr\EmployeeExitService::PENDING_DETAILS;
    $snap = $exitRow?->snapshot;
@endphp

<div class="tabpanel" data-tabpanel="exit" id="exit">

    {{-- ============ Someone still working: record an exit ============ --}}
    @if(!$exitRow && $viewed->employment_status === 'active' && !$isSelfView)
        <h4 style="margin:0 0 4px;">Record resignation / termination</h4>
        <p class="field-hint" style="margin:0 0 14px;">
            Saving freezes {{ $viewed->user->name }}'s details and performance into their history.
            If the last working day is in the future they stay <b>On notice</b> and can still sign in until that day.
        </p>

        <form method="POST" action="{{ route('hr.records.exit.store', $viewed) }}"
              onsubmit="return confirm('Record this exit for {{ addslashes($viewed->user->name) }}?')">
            @csrf
            <div class="field-grid">
                <div class="field">
                    <label>Exit type *</label>
                    <select name="exit_type" required>
                        @foreach(EmployeeExit::TYPES as $k => $label)
                            <option value="{{ $k }}" @selected(old('exit_type') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('exit_type')<small class="field-hint" style="color:#b42318">{{ $message }}</small>@enderror
                </div>
                <div class="field">
                    <label>Reason category</label>
                    <select name="reason_category">
                        <option value="">—</option>
                        @foreach(['Better opportunity','Higher studies','Personal / family','Relocation','Health','Compensation','Work environment','Performance','Misconduct','Attendance / absconding','Restructuring','Contract ended','Retirement','Other'] as $r)
                            <option @selected(old('reason_category') === $r)>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field full">
                    <label>Reason / details *</label>
                    <textarea name="reason" rows="3" required maxlength="2000">{{ old('reason') }}</textarea>
                    @error('reason')<small class="field-hint" style="color:#b42318">{{ $message }}</small>@enderror
                </div>
                <div class="field"><label>Notice given on</label><input type="date" name="notice_date" value="{{ old('notice_date') }}"></div>
                <div class="field"><label>Last working day *</label><input type="date" name="last_working_day" value="{{ old('last_working_day') }}" required></div>
                <div class="field"><label>Notice period (days)</label><input type="number" min="0" name="notice_period_days" value="{{ old('notice_period_days') }}"></div>
                <div class="field"><label>Notice served (days)</label><input type="number" min="0" name="notice_served_days" value="{{ old('notice_served_days') }}"></div>
                <div class="field">
                    <label>Eligible for rehire *</label>
                    <select name="eligible_for_rehire" required>
                        <option value="yes" @selected(old('eligible_for_rehire','yes') === 'yes')>Yes</option>
                        <option value="conditional" @selected(old('eligible_for_rehire') === 'conditional')>Conditional</option>
                        <option value="no" @selected(old('eligible_for_rehire') === 'no')>No</option>
                    </select>
                </div>
                <div class="field">
                    <label>Closing performance rating (1–5)</label>
                    <select name="overall_rating">
                        <option value="">—</option>
                        @for($i = 5; $i >= 1; $i--)<option value="{{ $i }}" @selected(old('overall_rating') == $i)>{{ $i }}</option>@endfor
                    </select>
                </div>
                <div class="field full"><label>Rehire remarks</label><input name="rehire_remarks" maxlength="1000" value="{{ old('rehire_remarks') }}"></div>
                <div class="field full"><label>Manager / HR remarks on performance & conduct</label><textarea name="manager_remarks" rows="3" maxlength="3000">{{ old('manager_remarks') }}</textarea></div>

                <div class="field full">
                    <label>Exit interview</label>
                    <label style="font-weight:400;display:flex;gap:8px;align-items:center;margin:4px 0;">
                        <input type="checkbox" name="exit_interview_done" value="1" @checked(old('exit_interview_done'))> Exit interview completed
                    </label>
                    <textarea name="exit_interview_notes" rows="3" maxlength="3000" placeholder="Feedback, reasons for leaving, suggestions">{{ old('exit_interview_notes') }}</textarea>
                </div>

                <div class="field full">
                    <label>Clearance checklist</label>
                    @foreach(EmployeeExit::CLEARANCE_ITEMS as $key => $label)
                        <label style="font-weight:400;display:flex;gap:8px;align-items:center;margin:4px 0;">
                            <input type="checkbox" name="clearance[{{ $key }}]" value="1" @checked(old("clearance.$key"))> {{ $label }}
                        </label>
                    @endforeach
                </div>
                <div class="field full"><label>Final settlement notes</label><input name="final_settlement_notes" maxlength="2000" value="{{ old('final_settlement_notes') }}"></div>

                @php $reports = $viewed->directReports->where('employment_status', '!=', 'exited'); @endphp
                @if($reports->isNotEmpty())
                    <div class="field full">
                        <label>{{ $reports->count() }} {{ \Illuminate\Support\Str::plural('person', $reports->count()) }} report to {{ $viewed->user->name }} — new manager</label>
                        <select name="reassign_reports_to">
                            <option value="">Move up to {{ $viewed->reportingManager?->user?->name ?? 'no manager (unassigned)' }} on last working day</option>
                            @foreach($possibleManagers as $m)
                                @continue($m->id === $viewed->id)
                                <option value="{{ $m->id }}">{{ $m->user->name }} ({{ $m->employee_code }})</option>
                            @endforeach
                        </select>
                        <small class="field-hint">{{ $reports->map(fn($r) => $r->user->name ?? $r->employee_code)->implode(', ') }}</small>
                    </div>
                @endif
            </div>
            <div class="form-actions"><button type="submit" class="btn-primary">Record exit</button></div>
        </form>

    {{-- ============ On notice / exited: show the record ============ --}}
    @elseif($exitRow)
        @if($pending)
            <div class="pill pill-warn" style="display:block;padding:10px 12px;margin-bottom:14px;border-radius:10px;">
                This employee was deactivated from the SPC Employees screen. Add the exit type, reason and clearance below so the record is complete.
            </div>
        @endif

        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:14px;">
            <span class="pill {{ $exitRow->status === 'on_notice' ? 'pill-warn' : 'pill-bad' }}">
                {{ $exitRow->status === 'on_notice' ? 'On notice' : 'Exited' }} — {{ $exitRow->typeLabel() }}
            </span>
            <span class="field-hint">Last working day {{ $exitRow->last_working_day->format('d M Y') }}</span>
            <a class="btn-secondary" style="text-decoration:none;margin-left:auto;" target="_blank" href="{{ route('hr.records.file', $viewed) }}">Open full employee file</a>
        </div>

        @if($snap)
            @php $perf = $snap['performance'] ?? []; $att = $snap['attendance'] ?? []; @endphp
            <div class="field-grid" style="margin-bottom:14px;">
                <div class="field"><label>Tenure</label><input disabled value="{{ $snap['profile']['tenure_text'] ?? '—' }}"></div>
                <div class="field"><label>Designation at exit</label><input disabled value="{{ $snap['profile']['designation'] ?? '—' }}"></div>
                <div class="field"><label>Avg appraisal rating</label><input disabled value="{{ $perf['average_rating'] ?? '—' }}{{ !empty($perf['appraisal_count']) ? ' ('.$perf['appraisal_count'].' reviews)' : '' }}"></div>
                <div class="field"><label>Attendance, last 12 mo</label><input disabled value="{{ $att['present'] ?? 0 }} present · {{ $att['late'] ?? 0 }} late · {{ $att['absent'] ?? 0 }} absent"></div>
                @if(!empty($snap['sales']))
                    <div class="field"><label>Sales (12 mo / lifetime)</label><input disabled value="₹{{ number_format($snap['sales']['net_sales_12m'] ?? 0) }} / ₹{{ number_format($snap['sales']['net_sales_lifetime'] ?? 0) }}"></div>
                    <div class="field"><label>Leads handled</label><input disabled value="{{ $snap['sales']['leads_total'] ?? 0 }}"></div>
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('hr.records.exit.update', $exitRow) }}">
            @csrf
            <div class="field-grid">
                <div class="field">
                    <label>Exit type *</label>
                    <select name="exit_type" required>
                        @foreach(EmployeeExit::TYPES as $k => $label)
                            <option value="{{ $k }}" @selected($exitRow->exit_type === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Reason category</label>
                    <input name="reason_category" maxlength="60" value="{{ $pending ? '' : $exitRow->reason_category }}">
                </div>
                <div class="field full"><label>Reason / details *</label>
                    <textarea name="reason" rows="3" required maxlength="2000">{{ $pending ? '' : $exitRow->reason }}</textarea></div>
                <div class="field"><label>Notice given on</label><input type="date" name="notice_date" value="{{ $exitRow->notice_date?->format('Y-m-d') }}"></div>
                <div class="field"><label>Last working day *</label><input type="date" name="last_working_day" value="{{ $exitRow->last_working_day->format('Y-m-d') }}" required></div>
                <div class="field">
                    <label>Eligible for rehire *</label>
                    <select name="eligible_for_rehire" required>
                        @foreach(['yes'=>'Yes','conditional'=>'Conditional','no'=>'No'] as $v => $l)
                            <option value="{{ $v }}" @selected($exitRow->eligible_for_rehire === $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Closing performance rating (1–5)</label>
                    <select name="overall_rating">
                        <option value="">—</option>
                        @for($i = 5; $i >= 1; $i--)<option value="{{ $i }}" @selected($exitRow->overall_rating == $i)>{{ $i }}</option>@endfor
                    </select>
                </div>
                <div class="field full"><label>Rehire remarks</label><input name="rehire_remarks" maxlength="1000" value="{{ $exitRow->rehire_remarks }}"></div>
                <div class="field full"><label>Manager / HR remarks on performance & conduct</label>
                    <textarea name="manager_remarks" rows="3" maxlength="3000">{{ $exitRow->manager_remarks }}</textarea></div>
                <div class="field full">
                    <label>Exit interview</label>
                    <label style="font-weight:400;display:flex;gap:8px;align-items:center;margin:4px 0;">
                        <input type="checkbox" name="exit_interview_done" value="1" @checked($exitRow->exit_interview_done)> Exit interview completed
                    </label>
                    <textarea name="exit_interview_notes" rows="3" maxlength="3000">{{ $exitRow->exit_interview_notes }}</textarea>
                </div>
                <div class="field full">
                    <label>Clearance checklist</label>
                    @foreach(EmployeeExit::CLEARANCE_ITEMS as $key => $label)
                        <label style="font-weight:400;display:flex;gap:8px;align-items:center;margin:4px 0;">
                            <input type="checkbox" name="clearance[{{ $key }}]" value="1" @checked(!empty($exitRow->clearance[$key]))> {{ $label }}
                        </label>
                    @endforeach
                </div>
                <div class="field full"><label>Final settlement notes</label><input name="final_settlement_notes" maxlength="2000" value="{{ $exitRow->final_settlement_notes }}"></div>
            </div>
            <div class="form-actions"><button type="submit" class="btn-primary">Save exit details</button></div>
        </form>

        @if(!$isSelfView)
            <form method="POST" action="{{ route('hr.records.exit.reinstate', $viewed) }}" style="margin-top:18px;border-top:1px solid var(--line-soft);padding-top:14px;"
                  onsubmit="return confirm('Reinstate {{ addslashes($viewed->user->name) }}? Their HR and SPC logins will be re-enabled; this exit stays in their history.')">
                @csrf
                <div class="field-grid">
                    <div class="field full"><label>Reinstate / rehire</label>
                        <input name="remarks" maxlength="1000" placeholder="Reason for reinstating (optional)"></div>
                </div>
                <div class="form-actions"><button type="submit" class="btn-secondary">Reinstate employee</button></div>
            </form>
        @endif

    {{-- ============ Exited by old toggle, no record yet (should be rare after backfill) ============ --}}
    @elseif($viewed->employment_status !== 'active')
        <p class="field-hint">This employee is marked {{ str_replace('_',' ',$viewed->employment_status) }} but has no exit record. Run <code>php artisan hr:backfill-history</code> to create one.</p>
    @else
        <p class="field-hint">You can't record your own exit.</p>
    @endif

    @if($pastExits->isNotEmpty())
        <h4 style="margin:22px 0 8px;">Earlier separations</h4>
        <table>
            <thead><tr><th>Type</th><th>Last day</th><th>Reason</th><th>Reinstated</th></tr></thead>
            <tbody>
            @foreach($pastExits as $x)
                <tr>
                    <td>{{ $x->typeLabel() }}</td>
                    <td>{{ $x->last_working_day->format('d M Y') }}</td>
                    <td>{{ $x->reason_category ?: '—' }}{{ $x->reason ? ' — '.\Illuminate\Support\Str::limit($x->reason, 80) : '' }}</td>
                    <td>{{ $x->reinstated_at?->format('d M Y') ?? '—' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
