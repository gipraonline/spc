@extends('layouts.app')

@section('content')
<style>
.mc-page { padding: 8px 4px 40px; font-family: 'Outfit', sans-serif; color: #22352C; }
.mc-page h2 { font-size: 22px; font-weight: 600; margin: 0 0 4px; color: #1F3D14; }
.mc-sub { color: #61756B; font-size: 13px; margin-bottom: 18px; }
.mc-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 18px; }
.mc-stat { background: linear-gradient(135deg, #F4FAF3, #fff); border: 1px solid rgba(18,58,40,.13); border-radius: 14px; padding: 16px 18px; }
.mc-stat span { display: block; font-size: 11.5px; text-transform: uppercase; letter-spacing: .05em; color: #61756B; margin-bottom: 6px; }
.mc-stat b { font-size: 24px; color: #1F3D14; font-weight: 600; }
.mc-card { background: #fff; border: 1px solid rgba(18,58,40,.13); border-radius: 14px; padding: 18px; margin-bottom: 18px; box-shadow: 0 1px 2px rgba(10,61,44,.05); }
.mc-card h3 { font-size: 15px; font-weight: 600; margin: 0 0 12px; }
.mc-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.mc-table th { text-align: left; font-size: 11.5px; text-transform: uppercase; letter-spacing: .04em; color: #61756B; padding: 8px 10px; border-bottom: 1px solid rgba(18,58,40,.13); }
.mc-table td { padding: 10px; border-bottom: 1px solid rgba(18,58,40,.07); }
.mc-pill { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 600; }
.mc-pill.paid { background: #DCF3E4; color: #1F5C2E; }
.mc-pill.wait { background: #FCF0D8; color: #7A5B00; }
.mc-muted { color: #61756B; font-size: 12.5px; }
.mc-note { background: #F4FAF3; border: 1px dashed rgba(94,141,61,.4); border-radius: 12px; padding: 12px 14px; font-size: 13px; margin-bottom: 18px; }
</style>

<div class="mc-page">
    <h2>My Commission</h2>
    <div class="mc-sub">
        {{ $designationLabel }} commission on your approved and paid sales.
        @if($rate !== null) Current rate: <b>{{ rtrim(rtrim(number_format($rate, 2), '0'), '.') }}%</b> of net sales. @endif
    </div>

    <div class="mc-stats">
        <div class="mc-stat"><span>Paid to you</span><b>₹{{ number_format($paidTotal, 0) }}</b></div>
        <div class="mc-stat"><span>Approved, payment pending</span><b>₹{{ number_format($awaitingTotal, 0) }}</b></div>
        <div class="mc-stat"><span>Months with commission</span><b>{{ $lines->count() }}</b></div>
    </div>

    @if($estimate)
        <div class="mc-note">
            <b>{{ now()->format('F Y') }} so far:</b> about
            <b>₹{{ number_format($estimate['commission_amount'], 0) }}</b>
            from {{ $estimate['orders_count'] }} {{ \Illuminate\Support\Str::plural('order', $estimate['orders_count']) }}
            (₹{{ number_format($estimate['sales_amount'], 0) }} net sales).
            This is an estimate. It becomes final after HR calculates it and the COO, MD and Finance approve it.
        </div>
    @endif

    <div class="mc-card">
        <h3>Commission statements</h3>
        <table class="mc-table">
            <thead>
                <tr><th>Month</th><th>Orders</th><th>Net sales</th><th>Rate</th><th>Commission</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse($lines as $l)
                    <tr>
                        <td><b>{{ \DateTime::createFromFormat('!m', $l->month)->format('F') }} {{ $l->year }}</b></td>
                        <td>{{ $l->orders_count }}</td>
                        <td>₹{{ number_format($l->sales_amount, 0) }}</td>
                        <td>{{ rtrim(rtrim(number_format($l->rate_percent, 2), '0'), '.') }}%</td>
                        <td><b>₹{{ number_format($l->commission_amount, 0) }}</b></td>
                        <td>
                            @if($l->approval_stage === 'completed')
                                <span class="mc-pill paid">Paid{{ $l->paid_at ? ' on '.\Illuminate\Support\Carbon::parse($l->paid_at)->format('d M Y') : '' }}</span>
                            @else
                                <span class="mc-pill wait">Approved &mdash; payment pending</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="mc-muted">No approved commission yet. Statements appear here once the COO and MD have approved them.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mc-muted">Commission counts orders that are Approved with payment received, credited to you, within the month.</div>
</div>
@endsection
