{{--
    Monthly employee performance report. Month comes from the page's month picker.
    Expects: $report (null for roles that may not see it), $month, $role.
--}}
@if($report)
    @php $inrR = fn ($v) => \App\Services\Hr\PerformanceInsightsService::inr($v); @endphp
    <div class="section-head">
        <h2><i class="fa-solid fa-table-list"></i>Employee report &mdash; {{ $month->format('F Y') }}</h2>
        <a href="{{ route('hr.appraisal.report.export', ['month' => $month->format('Y-m')]) }}" class="btn-secondary" style="padding:8px 14px;">
            <i class="fa-solid fa-file-arrow-down"></i> Download CSV
        </a>
    </div>

    <div class="table-card">
        <div class="tc-body pi-scroll">
            @if(empty($report['rows']))
                <div class="empty-widget">
                    <div class="ew-ico"><i class="fa-solid fa-users"></i></div>
                    <b>No employees to show</b>
                    <span>{{ $role === 'manager' ? 'No one reports to you yet.' : 'There are no active employees.' }}</span>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th><th>Department</th><th>Sales</th><th>Leave (days)</th>
                            <th>WFH (days)</th><th>Late</th><th>Salary paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report['rows'] as $r)
                            <tr>
                                <td><b>{{ $r['name'] }}</b><br><span class="field-hint">{{ $r['code'] }}</span></td>
                                <td>{{ $r['department'] }}</td>
                                <td><b>{{ $r['sales_linked'] ? $inrR($r['sales']) : '—' }}</b>@if($r['sales_linked'])<br><span class="field-hint">{{ $r['orders'] }} orders</span>@endif</td>
                                <td>{{ $r['leave_days'] }}</td>
                                <td>{{ $r['wfh_days'] }}</td>
                                <td>{{ $r['late'] }}</td>
                                <td>{{ $r['salary_paid'] !== null ? '₹'.number_format($r['salary_paid']) : 'Not paid yet' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><b>Total</b></td>
                            <td><b>{{ $inrR($report['totals']['sales']) }}</b></td>
                            <td><b>{{ $report['totals']['leave_days'] }}</b></td>
                            <td><b>{{ $report['totals']['wfh_days'] }}</b></td>
                            <td><b>{{ $report['totals']['late'] }}</b></td>
                            <td><b>₹{{ number_format($report['totals']['salary_paid']) }}</b></td>
                        </tr>
                    </tfoot>
                </table>
            @endif
        </div>
    </div>
@endif
