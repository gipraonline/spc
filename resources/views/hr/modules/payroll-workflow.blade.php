{{-- Payroll approval workflow: COO / MD approvals and Finance's payment + payslip-request desk. --}}
@php $showWorkflow = ($isCoo ?? false) || ($isMd ?? false) || ($isFinance ?? false); @endphp
@if($showWorkflow)
<div class="table-card" style="margin-bottom:18px;">
    <div class="tc-head">
        <h3><span class="wh-ico"><i class="fa-solid fa-list-check"></i></span>
            @if($isFinance) Payroll payments @else Payroll approvals @endif
        </h3>
        <span class="pill pill-muted">Recent runs</span>
    </div>
    <div class="tc-body">
        <table>
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Employees</th>
                    <th>Gross</th>
                    <th>Net pay</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($workflowRuns as $run)
                @php $stage = $run->approval_stage ?: 'draft'; @endphp
                <tr>
                    <td><b>{{ $run->monthLabel() }}</b></td>
                    <td>{{ $run->employee_count }}</td>
                    <td>₹{{ number_format($run->total_gross,0) }}</td>
                    <td>₹{{ number_format($run->total_net,0) }}</td>
                    <td>
                        <span class="pill {{ $stage === 'completed' ? 'pill-ok' : 'pill-muted' }}">{{ $run->stageLabel() }}</span>
                        @if($run->coo_at)<span class="field-hint" style="display:block;">COO approved {{ $run->coo_at->format('d M Y') }}</span>@endif
                        @if($run->md_at)<span class="field-hint" style="display:block;">MD approved {{ $run->md_at->format('d M Y') }}</span>@endif
                        @if($stage === 'returned' && $run->workflow_remarks)<span class="field-hint" style="display:block;">Returned: {{ $run->workflow_remarks }}</span>@endif
                    </td>
                    <td>
                        <div class="run-actions">
                            <a class="rbtn" href="{{ route('hr.payroll.register', $run) }}"><i class="fa-solid fa-file-excel"></i>Excel</a>

                            @if($isCoo && $stage === 'pending_coo')
                            <form method="POST" action="{{ route('hr.payroll.coo-approve', $run) }}"
                                onsubmit="return confirm('Approve {{ $run->monthLabel() }} payroll and send it to the MD?');">
                                @csrf<button type="submit" class="rbtn rbtn-ok"><i class="fa-solid fa-circle-check"></i>Approve</button></form>
                            @endif
                            @if($isMd && $stage === 'pending_md')
                            <form method="POST" action="{{ route('hr.payroll.md-approve', $run) }}"
                                onsubmit="return confirm('Approve {{ $run->monthLabel() }} payroll and send it to Finance?');">
                                @csrf<button type="submit" class="rbtn rbtn-ok"><i class="fa-solid fa-circle-check"></i>Approve</button></form>
                            @endif
                            @if(($isCoo && $stage === 'pending_coo') || ($isMd && $stage === 'pending_md'))
                            <form method="POST" action="{{ route('hr.payroll.return', $run) }}" style="display:inline-flex;gap:6px;align-items:center;">
                                @csrf
                                <input type="text" name="remarks" maxlength="255" required placeholder="Reason for returning" style="max-width:170px;">
                                <button type="submit" class="rbtn rbtn-bad"><i class="fa-solid fa-rotate-left"></i>Return to HR</button>
                            </form>
                            @endif

                            @if($isFinance && in_array($stage, ['pending_finance','completed'], true))
                            <a class="rbtn" href="{{ route('hr.payroll.bank-file', $run) }}"><i class="fa-solid fa-building-columns"></i>Bank file</a>
                            @endif
                            @if($isFinance && $stage === 'pending_finance')
                            <form method="POST" action="{{ route('hr.payroll.paid', $run) }}"
                                onsubmit="return confirm('Mark {{ $run->monthLabel() }} as paid? Do this after the bank transfer.');">
                                @csrf<button type="submit" class="rbtn rbtn-ok"><i class="fa-solid fa-circle-check"></i>Mark paid</button></form>
                            <form method="POST" action="{{ route('hr.payroll.discard', $run) }}"
                                onsubmit="return confirm('Discard {{ $run->monthLabel() }} payroll and all its payslips? HR will have to run it again.');">
                                @csrf<button type="submit" class="rbtn rbtn-bad"><i class="fa-solid fa-trash-can"></i>Discard</button></form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-widget">
                            <div class="ew-ico"><i class="fa-solid fa-list-check"></i></div>
                            <b>No payroll runs yet</b><span>Runs appear here once HR processes a month.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

@if($isFinance ?? false)
<div class="table-card" style="margin-bottom:18px;">
    <div class="tc-head">
        <h3><span class="wh-ico"><i class="fa-solid fa-inbox"></i></span>Payslip requests</h3>
        <span class="pill pill-muted">{{ $pendingRequests->count() }} pending</span>
    </div>
    <div class="tc-body">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Payslip</th>
                    <th>Reason</th>
                    <th>Requested</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingRequests as $rq)
                <tr>
                    <td class="cell-emp">
                        <div class="av">{{ strtoupper(substr($rq->employee->user->name ?? '?',0,1)) }}</div>
                        <div><b>{{ $rq->employee->user->name ?? '—' }}</b><span>{{ $rq->employee->employee_code ?? '' }}</span></div>
                    </td>
                    <td>{{ optional(optional($rq->payslip)->payrollRun)->monthLabel() ?? '—' }}</td>
                    <td>{{ $rq->reason ?: '—' }}</td>
                    <td>{{ optional($rq->created_at)->format('d M Y') }}</td>
                    <td>
                        <div class="run-actions">
                            <form method="POST" action="{{ route('hr.payroll.payslip.decide', $rq) }}">
                                @csrf<input type="hidden" name="decision" value="approve">
                                <button type="submit" class="rbtn rbtn-ok"><i class="fa-solid fa-circle-check"></i>Make available</button></form>
                            <form method="POST" action="{{ route('hr.payroll.payslip.decide', $rq) }}" style="display:inline-flex;gap:6px;align-items:center;">
                                @csrf<input type="hidden" name="decision" value="reject">
                                <input type="text" name="remarks" maxlength="255" required placeholder="Reason for rejecting" style="max-width:170px;">
                                <button type="submit" class="rbtn rbtn-bad"><i class="fa-solid fa-xmark"></i>Reject</button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-widget">
                            <div class="ew-ico"><i class="fa-solid fa-inbox"></i></div>
                            <b>No pending requests</b><span>Employee payslip requests show up here.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="table-card" style="margin-bottom:18px;">
    <div class="tc-head">
        <h3><span class="wh-ico"><i class="fa-regular fa-file-lines"></i></span>Payslip history</h3>
        <span class="pill pill-muted">{{ $financeSlips ? $financeSlips->total() : 0 }} payslips</span>
    </div>
    <div class="tc-body">
        <table>
            <thead>
                <tr>
                    <th>Cycle</th>
                    <th>Employee</th>
                    <th>Gross</th>
                    <th>Net Pay</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($financeSlips ?? [] as $p)
                <tr>
                    <td>{{ $p->payrollRun->monthLabel() }}</td>
                    <td class="cell-emp">
                        <div class="av">{{ strtoupper(substr($p->employee->user->name ?? '?',0,1)) }}</div>
                        <div><b>{{ $p->employee->user->name ?? '—' }}</b></div>
                    </td>
                    <td>₹{{ number_format($p->gross_pay,0) }}</td>
                    <td><b>₹{{ number_format($p->net_pay,0) }}</b></td>
                    <td><a href="{{ route('hr.payroll.payslip', $p) }}" target="_blank" class="btn-ghost">View</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-widget">
                            <div class="ew-ico"><i class="fa-regular fa-file-lines"></i></div>
                            <b>No payslips generated yet</b>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($financeSlips){{ $financeSlips->links() }}@endif
    </div>
</div>
@endif
