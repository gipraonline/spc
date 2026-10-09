@extends('hr.layouts.app')

@section('title', $module['title'])


@section('content')
<style>
.run-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.run-actions form {
    margin: 0;
}

.rbtn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 13px;
    border-radius: 10px;
    border: 1px solid var(--line);
    background: #fff;
    color: var(--text);
    font-family: var(--font-body);
    font-size: 12.5px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
    transition: border-color .15s, background .15s, color .15s;
}

.rbtn i {
    font-size: 12px;
    color: var(--text-muted);
}

.rbtn:hover {
    border-color: var(--brand-bright);
    background: var(--brand-softer);
    color: var(--brand-ink);
}

.rbtn:hover i {
    color: var(--brand);
}

.rbtn:focus-visible {
    outline: 2px solid var(--brand-bright);
    outline-offset: 2px;
}

.rbtn-ok {
    background: var(--ok);
    border-color: var(--ok);
    color: #fff;
}

.rbtn-ok i,
.rbtn-ok:hover i {
    color: #fff;
}

.rbtn-ok:hover {
    background: #116B32;
    border-color: #116B32;
    color: #fff;
}

.rbtn-bad {
    border-color: color-mix(in srgb, var(--bad) 35%, white);
    color: var(--bad);
}

.rbtn-bad i {
    color: var(--bad);
}

.rbtn-bad:hover {
    background: var(--bad-soft);
    border-color: var(--bad);
    color: var(--bad);
}

.rbtn-bad:hover i {
    color: var(--bad);
}
</style>
    @php
        $current = $selectedRun;
        $stageOf = fn ($r) => $r->approval_stage ?: 'draft';
        $pending = $runs->filter(fn ($r) => in_array($r->approval_stage, ['pending_coo', 'pending_md', 'pending_finance'], true))->count();
    @endphp

    @include('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Money',
        'heroIcon' => 'fa-solid fa-percent',
        'heroSummary' => 'Commission for Farm Care Advisers and Tele Callers on the sales they close.',
        'heroStats' => [
            ['label' => 'FCA rate', 'icon' => 'fa-solid fa-seedling', 'value' => isset($currentRates['FCA']) && $currentRates['FCA'] ? rtrim(rtrim(number_format($currentRates['FCA']->percent, 2), '0'), '.') . '%' : 'Not set'],
            ['label' => 'Tele Caller rate', 'icon' => 'fa-solid fa-headset', 'value' => isset($currentRates['TC']) && $currentRates['TC'] ? rtrim(rtrim(number_format($currentRates['TC']->percent, 2), '0'), '.') . '%' : 'Not set'],
            ['label' => 'In approval', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pending],
        ],
    ])

    <div class="content">
        @if(session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="flash-errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="grid-2">
            {{-- ===== Commission percentage (Office Administration sets it) ===== --}}
            <div class="card">
                <div class="widget-head">
                    <div class="wh-ico"><i class="fa-solid fa-percent"></i></div>
                    <div>
                        <h3>Commission percentage</h3>
                        <p>Applied to a person's eligible net sales for the month. Set by Office Administration.</p>
                    </div>
                </div>

                <table>
                    <thead><tr><th>Role</th><th>Current rate</th><th>Effective from</th></tr></thead>
                    <tbody>
                        @foreach($designations as $code => $label)
                            @php $r = $currentRates[$code] ?? null; @endphp
                            <tr>
                                <td><b>{{ $label }}</b></td>
                                <td>{{ $r ? rtrim(rtrim(number_format($r->percent, 2), '0'), '.') . '%' : 'Not set' }}</td>
                                <td>{{ $r ? $r->effective_from->format('d M Y') : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($isOfficeAdmin)
                    <form method="POST" action="{{ route('hr.commission.rate.store') }}" style="margin-top:16px;">
                        @csrf
                        <div class="field-grid">
                            <div class="field">
                                <label>Role</label>
                                <select name="designation_code" required>
                                    @foreach($designations as $code => $label)
                                        <option value="{{ $code }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>Commission %</label>
                                <input type="number" name="percent" step="0.01" min="0" max="100" required placeholder="e.g. 2.5">
                            </div>
                            <div class="field">
                                <label>Effective from</label>
                                <input type="date" name="effective_from" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="form-actions"><button type="submit" class="btn-primary">Save percentage</button></div>
                        <p class="field-hint">A new percentage only applies to months calculated after its effective date. Statements already calculated never change.</p>
                    </form>
                @endif

                @if($rateHistory->isNotEmpty())
                    <details style="margin-top:14px;">
                        <summary style="cursor:pointer;font-size:12.5px;color:var(--text-soft);">Rate history</summary>
                        <table style="margin-top:8px;">
                            <thead><tr><th>Role</th><th>%</th><th>Effective</th><th>Set on</th></tr></thead>
                            <tbody>
                                @foreach($rateHistory as $h)
                                    <tr>
                                        <td>{{ $designations[$h->designation_code] ?? $h->designation_code }}</td>
                                        <td>{{ rtrim(rtrim(number_format($h->percent, 2), '0'), '.') }}%</td>
                                        <td>{{ $h->effective_from->format('d M Y') }}</td>
                                        <td>{{ optional($h->created_at)->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </details>
                @endif
            </div>

            {{-- ===== HR: calculate a month ===== --}}
            @if($isHr)
                <div class="card">
                    <div class="widget-head">
                        <div class="wh-ico"><i class="fa-solid fa-calculator"></i></div>
                        <div>
                            <h3>Calculate commission</h3>
                            <p>Counts orders that are <b>{{ $eligibility['order_status'] }}</b> with payment <b>{{ $eligibility['payment_status'] }}</b>, credited to a Farm Care Advisor or Tele Caller &mdash; the same rule as incentives.</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('hr.commission.calculate') }}">
                        @csrf
                        <div class="field-grid">
                            <div class="field"><label>Month</label><input type="month" name="month" value="{{ $runMonth }}" max="{{ now()->format('Y-m') }}" required></div>
                        </div>
                        <div class="form-actions"><button type="submit" class="btn-primary">Calculate commission</button></div>
                        <p class="field-hint">Recalculating replaces the lines while the statement is still with HR. Once it is sent for approval it is locked.</p>
                    </form>
                </div>
            @endif
        </div>

        {{-- ===== Statements: HR / COO / MD / Finance ===== --}}
        @if($canViewStatements)
            <div class="table-card" style="margin-top:18px;">
                <div class="tc-head">
                    <h3><span class="wh-ico"><i class="fa-solid fa-list-check"></i></span>Commission statements</h3>
                    <span class="pill pill-muted">{{ $runs->count() }} {{ \Illuminate\Support\Str::plural('month', $runs->count()) }}</span>
                </div>
                <div class="tc-body">
                    <table>
                        <thead>
                            <tr>
                                <th>Period</th><th>People</th><th>Net sales</th><th>Commission</th><th>Status</th><th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($runs as $run)
                                @php $stage = $stageOf($run); @endphp
                                <tr @if($current && $current->id === $run->id) style="background:var(--brand-softer);" @endif>
                                    <td><b>{{ $run->monthLabel() }}</b></td>
                                    <td>{{ $run->advisor_count }}</td>
                                    <td>₹{{ number_format($run->total_sales, 0) }}</td>
                                    <td><b>₹{{ number_format($run->total_commission, 0) }}</b></td>
                                    <td>
                                        <span class="pill {{ $stage === 'completed' ? 'pill-ok' : 'pill-muted' }}">{{ $run->stageLabel() }}</span>
                                        @if($run->coo_at)<span class="field-hint" style="display:block;">COO approved {{ $run->coo_at->format('d M Y') }}</span>@endif
                                        @if($run->md_at)<span class="field-hint" style="display:block;">MD approved {{ $run->md_at->format('d M Y') }}</span>@endif
                                        @if($run->paid_at)<span class="field-hint" style="display:block;">Paid {{ $run->paid_at->format('d M Y') }}</span>@endif
                                        @if($stage === 'returned' && $run->workflow_remarks)<span class="field-hint" style="display:block;">Returned: {{ $run->workflow_remarks }}</span>@endif
                                    </td>
                                    <td>
                                        <div class="run-actions">
                                            <a class="rbtn" href="{{ route('hr.commission.index', ['run' => $run->id]) }}"><i class="fa-regular fa-eye"></i>View</a>
                                            <a class="rbtn" href="{{ route('hr.commission.export', $run) }}"><i class="fa-solid fa-file-excel"></i>Excel</a>

                                            @if($isHr && in_array($stage, ['draft', 'returned'], true))
                                                <form method="POST" action="{{ route('hr.commission.submit', $run) }}"
                                                    onsubmit="return confirm('Send {{ $run->monthLabel() }} commission to the COO for approval?');">
                                                    @csrf<button type="submit" class="rbtn rbtn-ok"><i class="fa-solid fa-paper-plane"></i>Send to COO</button></form>
                                                <form method="POST" action="{{ route('hr.commission.discard', $run) }}"
                                                    onsubmit="return confirm('Discard the {{ $run->monthLabel() }} commission statement? You can calculate it again afterwards.');">
                                                    @csrf<button type="submit" class="rbtn rbtn-bad"><i class="fa-solid fa-trash-can"></i>Discard</button></form>
                                            @endif

                                            @if($isCoo && $stage === 'pending_coo')
                                                <form method="POST" action="{{ route('hr.commission.coo-approve', $run) }}"
                                                    onsubmit="return confirm('Approve {{ $run->monthLabel() }} commission and send it to the MD?');">
                                                    @csrf<button type="submit" class="rbtn rbtn-ok"><i class="fa-solid fa-circle-check"></i>Approve</button></form>
                                            @endif
                                            @if($isMd && $stage === 'pending_md')
                                                <form method="POST" action="{{ route('hr.commission.md-approve', $run) }}"
                                                    onsubmit="return confirm('Approve {{ $run->monthLabel() }} commission and send it to Finance?');">
                                                    @csrf<button type="submit" class="rbtn rbtn-ok"><i class="fa-solid fa-circle-check"></i>Approve</button></form>
                                            @endif
                                            @if(($isCoo && $stage === 'pending_coo') || ($isMd && $stage === 'pending_md'))
                                                <form method="POST" action="{{ route('hr.commission.return', $run) }}" style="display:inline-flex;gap:6px;align-items:center;">
                                                    @csrf
                                                    <input type="text" name="remarks" maxlength="255" required placeholder="Reason for returning" style="max-width:170px;">
                                                    <button type="submit" class="rbtn rbtn-bad"><i class="fa-solid fa-rotate-left"></i>Return to HR</button>
                                                </form>
                                            @endif

                                            @if($isFinance && $stage === 'pending_finance')
                                                <form method="POST" action="{{ route('hr.commission.paid', $run) }}"
                                                    onsubmit="return confirm('Mark {{ $run->monthLabel() }} commission as paid? Do this after the payment is made.');">
                                                    @csrf<button type="submit" class="rbtn rbtn-ok"><i class="fa-solid fa-circle-check"></i>Mark paid</button></form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-widget">
                                            <div class="ew-ico"><i class="fa-solid fa-percent"></i></div>
                                            <b>No commission calculated yet</b>
                                            <span>Once Office Administration sets the percentage, HR can calculate a month here.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ===== Selected statement: who earned what ===== --}}
            @if($current)
                <div class="table-card" style="margin-top:18px;">
                    <div class="tc-head">
                        <h3><span class="wh-ico"><i class="fa-solid fa-users"></i></span>{{ $current->monthLabel() }} &mdash; commission by person</h3>
                        <a class="rbtn" href="{{ route('hr.commission.export', $current) }}"><i class="fa-solid fa-file-excel"></i>Download Excel</a>
                    </div>
                    <div class="tc-body">
                        <table>
                            <thead>
                                <tr><th>Employee</th><th>Role</th><th>Orders</th><th>Net sales</th><th>Rate</th><th>Commission</th></tr>
                            </thead>
                            <tbody>
                                @forelse($lines as $l)
                                    <tr>
                                        <td class="cell-emp">
                                            <div class="av">{{ strtoupper(substr($l->employee_name ?? '?', 0, 1)) }}</div>
                                            <div><b>{{ $l->employee_name }}</b><span>{{ $l->employee_code }}</span></div>
                                        </td>
                                        <td>{{ $designations[$l->designation_code] ?? $l->designation_code }}</td>
                                        <td>{{ $l->orders_count }}</td>
                                        <td>₹{{ number_format($l->sales_amount, 0) }}</td>
                                        <td>{{ rtrim(rtrim(number_format($l->rate_percent, 2), '0'), '.') }}%</td>
                                        <td><b>₹{{ number_format($l->commission_amount, 0) }}</b></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-widget">
                                                <div class="ew-ico"><i class="fa-solid fa-users"></i></div>
                                                <b>No eligible sales in {{ $current->monthLabel() }}</b>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                @if($lines->isNotEmpty())
                                    <tr>
                                        <td><b>Total</b></td><td></td>
                                        <td><b>{{ $lines->sum('orders_count') }}</b></td>
                                        <td><b>₹{{ number_format($lines->sum('sales_amount'), 0) }}</b></td>
                                        <td></td>
                                        <td><b>₹{{ number_format($lines->sum('commission_amount'), 0) }}</b></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @else
            <p class="field-hint" style="margin-top:16px;">Commission statements are visible to HR, the COO, the MD and Finance.</p>
        @endif
    </div>
@endsection
