{{--
    Resignation / termination form, used both to RECORD an exit ($exitRow null)
    and to EDIT one ($exitRow set). Posts to the SPC history routes, which call
    the same EmployeeExitService the HR module uses.
--}}
@php
    $editing = isset($exitRow) && $exitRow;
    $pendingDetails = $editing && $exitRow->reason_category === \App\Services\Hr\EmployeeExitService::PENDING_DETAILS;
    $v = fn (string $key, $default = null) => old($key, $editing ? ($pendingDetails && in_array($key, ['reason', 'reason_category']) ? '' : $exitRow->{$key}) : $default);
    $d = fn (string $key) => old($key, $editing && $exitRow->{$key} ? $exitRow->{$key}->format('Y-m-d') : null);
    $reasons = ['Better opportunity', 'Higher studies', 'Personal / family', 'Relocation', 'Health', 'Compensation', 'Work environment', 'Performance', 'Misconduct', 'Attendance / absconding', 'Restructuring', 'Contract ended', 'Retirement', 'Other'];
@endphp

<form method="POST" action="{{ $action }}" class="eh-form" onsubmit="return confirm('{{ $editing ? 'Save these exit details?' : 'Record this exit for '.addslashes($viewed->user->name ?? $viewed->employee_code).'? Their details and performance will be frozen into history.' }}')">
    @csrf
    <div class="eh-fgrid">
        <div class="eh-field">
            <label>Exit type *</label>
            <select name="exit_type" required>
                @foreach(\App\Models\Hr\EmployeeExit::TYPES as $k => $label)
                    <option value="{{ $k }}" @selected($v('exit_type', 'resignation') === $k)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="eh-field">
            <label>Reason category</label>
            <select name="reason_category">
                <option value="">—</option>
                @foreach($reasons as $r)
                    <option @selected($v('reason_category') === $r)>{{ $r }}</option>
                @endforeach
                @if($editing && !$pendingDetails && $exitRow->reason_category && !in_array($exitRow->reason_category, $reasons))
                    <option selected>{{ $exitRow->reason_category }}</option>
                @endif
            </select>
        </div>
        <div class="eh-field full">
            <label>Reason / details *</label>
            <textarea name="reason" rows="3" required maxlength="2000">{{ $v('reason') }}</textarea>
        </div>
        <div class="eh-field"><label>Notice given on</label><input type="date" name="notice_date" value="{{ $d('notice_date') }}"></div>
        <div class="eh-field"><label>Last working day *</label><input type="date" name="last_working_day" required value="{{ $d('last_working_day') }}"></div>

        @unless($editing)
            <div class="eh-field"><label>Notice period (days)</label><input type="number" min="0" max="365" name="notice_period_days" value="{{ old('notice_period_days') }}"></div>
            <div class="eh-field"><label>Notice served (days)</label><input type="number" min="0" max="365" name="notice_served_days" value="{{ old('notice_served_days') }}"></div>
            <div class="eh-field full">
                <label class="eh-check"><input type="checkbox" name="notice_waived" value="1" @checked(old('notice_waived'))> Notice period waived</label>
            </div>
        @endunless

        <div class="eh-field">
            <label>Eligible for rehire *</label>
            <select name="eligible_for_rehire" required>
                @foreach(['yes' => 'Yes', 'conditional' => 'Conditional', 'no' => 'No'] as $val => $label)
                    <option value="{{ $val }}" @selected($v('eligible_for_rehire', 'yes') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="eh-field">
            <label>Closing performance rating (1–5)</label>
            <select name="overall_rating">
                <option value="">—</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" @selected((string) $v('overall_rating') === (string) $i)>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="eh-field full"><label>Rehire remarks</label><input name="rehire_remarks" maxlength="1000" value="{{ $v('rehire_remarks') }}"></div>
        <div class="eh-field full">
            <label>Manager / HR remarks on performance &amp; conduct</label>
            <textarea name="manager_remarks" rows="3" maxlength="3000">{{ $v('manager_remarks') }}</textarea>
        </div>

        <div class="eh-field full">
            <label>Exit interview</label>
            <label class="eh-check"><input type="checkbox" name="exit_interview_done" value="1" @checked(old('exit_interview_done', $editing ? $exitRow->exit_interview_done : false))> Exit interview completed</label>
            <textarea name="exit_interview_notes" rows="3" maxlength="3000" placeholder="Feedback, reasons for leaving, suggestions">{{ $v('exit_interview_notes') }}</textarea>
        </div>

        <div class="eh-field full">
            <label>Clearance checklist</label>
            @foreach(\App\Models\Hr\EmployeeExit::CLEARANCE_ITEMS as $key => $label)
                <label class="eh-check">
                    <input type="checkbox" name="clearance[{{ $key }}]" value="1" @checked(old("clearance.$key", $editing ? !empty($exitRow->clearance[$key]) : false))> {{ $label }}
                </label>
            @endforeach
        </div>
        <div class="eh-field full"><label>Final settlement notes</label><input name="final_settlement_notes" maxlength="2000" value="{{ $v('final_settlement_notes') }}"></div>

        @unless($editing)
            @php $reports = $viewed->directReports->where('employment_status', '!=', 'exited'); @endphp
            @if($reports->isNotEmpty())
                <div class="eh-field full">
                    <label>{{ $reports->count() }} {{ \Illuminate\Support\Str::plural('person', $reports->count()) }} report to {{ $viewed->user->name }} — new manager</label>
                    <select name="reassign_reports_to">
                        <option value="">Move up to {{ $viewed->reportingManager?->user?->name ?? 'no manager (unassigned)' }} on the last working day</option>
                        @foreach($possibleManagers as $m)
                            @continue($m->id === $viewed->id)
                            <option value="{{ $m->id }}">{{ $m->user->name }} ({{ $m->employee_code }})</option>
                        @endforeach
                    </select>
                    <small class="eh-hint">{{ $reports->map(fn ($r) => $r->user->name ?? $r->employee_code)->implode(', ') }}</small>
                </div>
            @endif
        @endunless
    </div>

    <div class="eh-form-actions">
        <button type="submit" class="eh-btn eh-btn-primary">{{ $editing ? 'Save exit details' : 'Record exit' }}</button>
    </div>
</form>
