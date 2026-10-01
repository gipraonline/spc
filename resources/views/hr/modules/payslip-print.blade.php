<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="{{ asset('dist/images/logos/fav.png') }}" />
    <title>Payslip — {{ $payslip->employee->user->name }} — {{ $payslip->payrollRun->monthLabel() }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@500;600&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{
            --brand:#4E7A33; --brand-strong:#1F3D14; --brand-soft:#E4F3EB;
            --line:rgba(18,58,40,0.14); --text:#22352C; --text-dim:#61756B;
            --sans:'Outfit',-apple-system,BlinkMacSystemFont,"Segoe UI",Helvetica,Arial,sans-serif;
            --head:'Kanit',sans-serif;
        }
        body{font-family:var(--sans);color:var(--text);max-width:760px;margin:48px auto;padding:0 20px;}
        .slip-head{display:flex;align-items:center;gap:12px;border-bottom:3px solid var(--brand);padding-bottom:14px;margin-bottom:6px;}
        .slip-mark{width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#1B8A5F,var(--brand-strong));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:17px;}
        h1{font-family:var(--head);font-weight:600;letter-spacing:.01em;font-size:20px;margin:0;color:var(--brand-strong);}
        .muted{color:var(--text-dim);font-size:13px;}
        table{width:100%;border-collapse:collapse;margin-top:24px;font-size:14px;}
        td{padding:8px 0;border-bottom:1px solid var(--line);}
        td.amount{text-align:right;}
        .total td{font-family:var(--head);font-weight:600;font-size:16px;color:var(--brand-strong);border-top:2px solid var(--brand);border-bottom:none;padding-top:14px;}
        .print-btn{margin-top:28px;padding:10px 18px;background:linear-gradient(180deg,#1B8A5F,#136B4C);color:#fff;border:none;border-radius:9px;cursor:pointer;font-family:inherit;font-weight:650;}
        .two{display:grid;grid-template-columns:1fr 1fr;gap:28px;margin-top:8px;}
        .box h3{font-family:var(--head);font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--brand-strong);margin:22px 0 0;}
        .info td{border-bottom:none;padding:5px 0;}
        .net{margin-top:22px;border:2px solid var(--brand);border-radius:10px;padding:14px 18px;display:flex;justify-content:space-between;align-items:center;font-family:var(--head);font-weight:600;font-size:18px;color:var(--brand-strong);}
        @media print { .print-btn{display:none;} }
    </style>
</head>
<body>
    @php
        $p = $payslip;
        $emp = $p->employee;
        $fmt = fn ($v) => number_format((float) $v, 2);
        $totalDed = $p->pf_deduction + $p->esi_deduction + $p->professional_tax + $p->tds_deduction + $p->other_deductions;
        $earnedRows = [
            ['Basic', $p->basic_earned],
            ['House rent allowance', $p->hra_earned],
            ['Other allowances', $p->allowances_earned],
            ['Variable pay', $p->variable_earned],
            ['Incentive', $p->incentive_pay],
        ];
        // payslips created before the payroll upgrade only carry the gross figure
        $hasBreakup = collect($earnedRows)->sum(fn ($r) => (float) $r[1]) > 0;
        if (! $hasBreakup) { $earnedRows = [['Gross pay', $p->gross_pay]]; }
    @endphp

    <div class="slip-head">
        <div class="slip-mark">{{ strtoupper(substr($companyName, 0, 1)) }}</div>
        <div>
            <h1>{{ $companyName }} &mdash; Payslip</h1>
            <p class="muted" style="margin:2px 0 0;">{{ $p->payrollRun->monthLabel() }}</p>
        </div>
    </div>

    <table class="info">
        <tr><td class="muted">Employee</td><td><b>{{ $emp->user->name }}</b> ({{ $emp->employee_code }})</td><td class="muted">Department</td><td>{{ $emp->department->name ?? '—' }}</td></tr>
        <tr><td class="muted">Designation</td><td>{{ $emp->designation->title ?? '—' }}</td><td class="muted">Date of joining</td><td>{{ \Illuminate\Support\Carbon::parse($emp->date_of_joining)->format('d M Y') }}</td></tr>
        <tr><td class="muted">Bank</td><td>{{ $emp->bank_name ?: '—' }}</td><td class="muted">Account</td><td>{{ $emp->bank_account_number ? '••••'.substr($emp->bank_account_number, -4) : '—' }}</td></tr>
        @if($p->days_in_month)
        <tr><td class="muted">Days in month</td><td>{{ $p->days_in_month }}</td><td class="muted">Paid days / LOP</td><td>{{ rtrim(rtrim(number_format($p->paid_days, 2), '0'), '.') }} / {{ rtrim(rtrim(number_format($p->lop_days, 2), '0'), '.') ?: '0' }}</td></tr>
        @endif
    </table>

    <div class="two">
        <div class="box">
            <h3>Earnings</h3>
            <table style="margin-top:8px;">
                @foreach($earnedRows as [$label, $amount])
                    @if((float) $amount > 0 || ! $hasBreakup)
                        <tr><td>{{ $label }}</td><td class="amount">₹{{ $fmt($amount) }}</td></tr>
                    @endif
                @endforeach
                <tr class="total"><td>Gross earnings</td><td class="amount">₹{{ $fmt($p->gross_pay) }}</td></tr>
            </table>
        </div>
        <div class="box">
            <h3>Deductions</h3>
            <table style="margin-top:8px;">
                <tr><td>Provident fund</td><td class="amount">₹{{ $fmt($p->pf_deduction) }}</td></tr>
                <tr><td>ESI</td><td class="amount">₹{{ $fmt($p->esi_deduction) }}</td></tr>
                <tr><td>Professional tax</td><td class="amount">₹{{ $fmt($p->professional_tax) }}</td></tr>
                <tr><td>Income tax (TDS)</td><td class="amount">₹{{ $fmt($p->tds_deduction) }}</td></tr>
                <tr><td>Other deductions</td><td class="amount">₹{{ $fmt($p->other_deductions) }}</td></tr>
                <tr class="total"><td>Total deductions</td><td class="amount">₹{{ $fmt($totalDed) }}</td></tr>
            </table>
        </div>
    </div>

    <div class="net"><span>Net pay</span><span>₹{{ $fmt($p->net_pay) }}</span></div>

    @if((float) $p->employer_pf > 0 || (float) $p->employer_esi > 0)
        <p class="muted" style="margin-top:18px;">Employer contributions this month (not deducted from you): PF ₹{{ $fmt($p->employer_pf) }}@if((float) $p->employer_esi > 0), ESI ₹{{ $fmt($p->employer_esi) }}@endif.</p>
    @endif
    <p class="muted" style="margin-top:6px;">This is a computer-generated payslip.</p>

    <button class="print-btn" onclick="window.print()">Print / save as PDF</button>
</body>
</html>
