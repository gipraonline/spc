@extends('layouts.app')

@push('styles')
<style>
.spc-wrap{--sb:var(--brand,#5E8D3D);--sb2:#1F5C2E;--ssoft:var(--brand-soft,#EEF5E6);--sink:#1F3D14;--smut:#61756B;--sline:rgba(18,58,40,.12);}
.spc-heading{margin-bottom:18px;display:flex;justify-content:space-between;align-items:flex-start;gap:14px;flex-wrap:wrap;}
.spc-heading h2{display:flex;align-items:center;gap:10px;margin:0;font-family:var(--font-head,'Kanit',sans-serif);font-size:22px;font-weight:600;color:var(--sink);}
.spc-heading h2 i{color:var(--sb);background:var(--ssoft);width:34px;height:34px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;font-size:14px;}
.spc-heading p{margin:4px 0 0 44px;font-size:13px;color:var(--smut);}
.spc-card{background:#fff;border:1px solid var(--sline);border-radius:18px;box-shadow:0 5px 18px rgba(15,81,50,.08);overflow:hidden;}
.spc-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;height:45px;padding:0 20px;border-radius:12px;font-weight:600;font-size:14px;text-decoration:none;cursor:pointer;border:1px solid transparent;transition:transform .2s,box-shadow .2s;}
.spc-btn-primary{background:linear-gradient(135deg,#7CA243,#1F5C2E);color:#fff;box-shadow:0 10px 18px -10px rgba(31,92,46,.6);}
.spc-btn-primary:hover{color:#fff;transform:translateY(-2px);box-shadow:0 16px 24px -12px rgba(31,92,46,.7);}
.spc-btn-secondary{background:#fff;color:#475569;border-color:#d5dde3;}
.spc-btn-secondary:hover{background:#f6f8f7;color:#1F3D14;}
.spc-toolbar{display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;padding:18px 20px;border-bottom:1px solid var(--sline);background:linear-gradient(45deg,rgba(203,255,205,.25),transparent 60%),#fff;}
.spc-toolbar .spc-f{display:flex;flex-direction:column;gap:6px;min-width:150px;flex:1 1 150px;}
.spc-toolbar label,.spc-lbl{font-size:11px;font-weight:700;color:var(--smut);text-transform:uppercase;letter-spacing:.06em;margin:0;}
.spc-toolbar .form-control,.spc-toolbar .form-select,.spc-field .form-control,.spc-field .form-select{height:45px;border-radius:12px;border:1px solid #d5dde3;background-color:#fff;font-size:14px;}
.spc-toolbar .form-control:focus,.spc-toolbar .form-select:focus,.spc-field .form-control:focus,.spc-field .form-select:focus{border-color:var(--sb);box-shadow:0 0 0 3px rgba(94,141,61,.15);}
.spc-toolbar .spc-btns{display:flex;gap:8px;}
.spc-table-wrap{overflow-x:auto;}
.spc-table{width:100%;margin:0;border-collapse:collapse;}
.spc-table thead th{background:#f8fafc;color:#64748b;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;padding:14px 18px;border-bottom:1px solid #eef1f4;white-space:nowrap;}
.spc-table tbody td{padding:14px 18px;font-size:13.5px;color:#334155;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
.spc-table tbody tr:hover{background:#fafcf8;}
.spc-table .text-end{text-align:right;}
.spc-person{display:flex;align-items:center;gap:12px;}
.spc-avatar{width:38px;height:38px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;background:linear-gradient(135deg,#7CA243,#1F5C2E);}
.spc-name{font-weight:700;color:var(--sink);line-height:1.2;}
.spc-sub{font-size:12px;color:var(--smut);}
.spc-code{background:#f1f5f9;padding:4px 10px;border-radius:6px;font-size:12px;color:#475569;font-weight:600;}
.spc-pill{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:30px;font-size:12px;font-weight:700;background:#eef2f6;color:#475569;}
.spc-pill i{width:7px;height:7px;border-radius:50%;background:currentColor;display:inline-block;}
.spc-pill.ok{background:#e6f4ea;color:#1F7A3A;}
.spc-pill.warn{background:#fff4dd;color:#B36B00;}
.spc-pill.off{background:#fde8e8;color:#B42318;}
.spc-pill.info{background:#e3f0fb;color:#1D6FA5;}
.spc-actions{display:flex;align-items:center;gap:14px;white-space:nowrap;}
.spc-actions form{margin:0;}
.spc-link{background:none;border:0;padding:0;font-size:13px;font-weight:700;color:var(--sb);text-decoration:none;cursor:pointer;}
.spc-link:hover{color:var(--sb2);text-decoration:underline;}
.spc-link.danger{color:#B42318;}
.spc-empty{text-align:center;padding:34px 20px;color:var(--smut);}
.spc-empty .ew{width:54px;height:54px;border-radius:16px;background:var(--ssoft);color:var(--sb);display:inline-flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:10px;}
.spc-empty b{display:block;color:var(--sink);}
.spc-foot{padding:14px 20px;}
.spc-alert{border-radius:12px;margin-bottom:16px;}
.spc-grid{display:grid;grid-template-columns:300px 1fr;}
@media(max-width:860px){.spc-grid{grid-template-columns:1fr;}}
.spc-side{position:relative;padding:28px 24px;color:#fff;background:radial-gradient(circle at 85% 8%,rgba(255,255,255,.14),transparent 45%),linear-gradient(160deg,#5E8D3D,#123a28);}
.spc-side .ico{width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.16);display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:16px;}
.spc-side h3{font-family:var(--font-head,'Kanit',sans-serif);font-size:20px;font-weight:600;margin:0 0 6px;color:#fff;}
.spc-side p{font-size:13px;color:rgba(255,255,255,.75);margin:0 0 18px;}
.spc-steps{display:flex;flex-direction:column;gap:10px;margin-top:18px;}
.spc-step{display:flex;align-items:center;gap:10px;font-size:13px;font-weight:600;}
.spc-step .num{width:26px;height:26px;border-radius:9px;background:rgba(255,255,255,.18);display:inline-flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;}
.spc-chip{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:30px;background:rgba(255,255,255,.16);font-size:12px;font-weight:700;margin:0 6px 6px 0;}
.spc-body{padding:26px 28px;}
.spc-note{display:flex;align-items:center;gap:10px;padding:11px 14px;border-radius:12px;background:var(--ssoft);color:var(--sink);font-size:13px;margin-bottom:20px;}
.spc-note i{color:var(--sb);}
.spc-fields{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;}
@media(max-width:640px){.spc-fields{grid-template-columns:1fr;}}
.spc-field{display:flex;flex-direction:column;gap:6px;}
.spc-field label{font-size:12px;font-weight:700;color:var(--sink);margin:0;}
.spc-field label i{color:var(--sb);margin-right:6px;}
.spc-formfoot{display:flex;justify-content:flex-end;gap:10px;margin-top:24px;padding-top:18px;border-top:1px solid var(--sline);}
.spc-view{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;}
@media(max-width:640px){.spc-view{grid-template-columns:1fr;}}
.spc-item{padding:12px 14px;border:1px solid #eef1f4;border-radius:14px;background:#fbfcfb;}
.spc-item span{display:block;font-size:10.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px;}
.spc-item span i{color:var(--sb);margin-right:6px;}
.spc-item b{display:block;color:var(--sink);font-weight:700;}
.spc-item b small{color:var(--smut);font-weight:500;}
.spc-tiles{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:22px;}
@media(max-width:640px){.spc-tiles{grid-template-columns:1fr;}}
.spc-tile{position:relative;overflow:hidden;display:flex;align-items:center;gap:14px;background:linear-gradient(45deg,rgba(203,255,205,.3),transparent 58%),#fff;border:1px solid var(--sline);border-radius:18px;padding:16px 18px;box-shadow:0 5px 18px rgba(15,81,50,.08);transition:transform .2s,box-shadow .2s;}
.spc-tile::before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;background:linear-gradient(180deg,#7CA243,#1F5C2E);opacity:.55;}
.spc-tile:hover{transform:translateY(-4px);box-shadow:0 22px 40px -18px rgba(8,48,31,.4);}
.spc-tile .ti-ico{width:46px;height:46px;border-radius:14px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:16px;color:#fff;background:linear-gradient(135deg,#7CA243,#1F5C2E);}
.spc-tile .ti-ico.amber{background:linear-gradient(135deg,#F4B942,#C07E08);}
.spc-tile .ti-ico.teal{background:linear-gradient(135deg,#2BB8A8,#0E6B5E);}
.spc-tile .ti-ico.blue{background:linear-gradient(135deg,#4FA3E0,#1D6FA5);}
.spc-tile .ti-ico.red{background:linear-gradient(135deg,#E5675B,#B42318);}
.spc-tile b{display:block;font-family:var(--font-head,'Kanit',sans-serif);font-size:22px;font-weight:600;color:var(--sink);line-height:1.1;}
.spc-tile span{font-size:10.5px;font-weight:700;color:var(--smut);text-transform:uppercase;letter-spacing:.08em;}
.spc-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin:22px 20px;}
@media(max-width:991.98px){.spc-stats{grid-template-columns:repeat(2,1fr);}}
@media(max-width:575.98px){.spc-stats{grid-template-columns:1fr;}}
.spc-pool{padding:22px 24px;border-bottom:1px solid var(--sline);}
.spc-pool:last-child{border-bottom:0;}
.spc-pool-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:16px;}
.spc-pool-title{display:inline-flex;align-items:center;gap:10px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:50px;padding:8px 18px;font-weight:800;font-size:13px;color:var(--sink);text-transform:uppercase;}
.spc-pool-title i{color:var(--sb);}
.spc-pool-title em{font-style:normal;font-weight:500;color:#64748b;}
.spc-pool-total{display:flex;align-items:center;gap:14px;background:linear-gradient(135deg,#7CA243,#1F5C2E);color:#fff;border-radius:16px;padding:12px 20px;min-width:240px;justify-content:space-between;}
.spc-pool-total small{display:block;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.75);}
.spc-pool-total b{font-family:var(--font-head,'Kanit',sans-serif);font-size:20px;font-weight:600;}
.spc-pool-total .w{width:40px;height:40px;border-radius:12px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;}
.spc-amt{font-weight:800;color:var(--sb2);}
.spc-section-title{padding:16px 24px;border-bottom:1px solid var(--sline);display:flex;align-items:center;gap:10px;font-family:var(--font-head,'Kanit',sans-serif);font-weight:600;color:var(--sink);font-size:16px;background:linear-gradient(45deg,rgba(203,255,205,.25),transparent 60%),#fff;}
.spc-section-title i{color:var(--sb);background:var(--ssoft);width:30px;height:30px;border-radius:9px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;}

</style>
@endpush

@section('content')
<div class="spc-wrap">
    <div class="spc-heading">
        <div>
            <h2><i class="fa-solid fa-file-invoice"></i>Sale Details</h2>
            <p>Sale information, totals and incentive distribution.</p>
        </div>
        <a href="{{ route('admin.sales.index') }}" class="spc-btn spc-btn-secondary">
            <i class="ti ti-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="spc-card mb-4">
        <div class="spc-grid">
            <div class="spc-side">
                <div class="ico"><i class="ti ti-check-double"></i></div>
                <h3>Incentive Status</h3>
                <p>Per Sale Distribution</p>
                <div>
                    <span class="spc-chip"><i class="ti ti-star-filled"></i>Calculation Ready</span>
                </div>
            </div>

            <div class="spc-body">
                <div class="spc-view">
                    <div class="spc-item">
                        <span><i class="ti ti-calendar"></i>Calculation Date</span>
                        <b>{{ isset($employeeIncentiveDate) ? $employeeIncentiveDate : 'N/A' }}</b>
                    </div>
                    <div class="spc-item">
                        <span><i class="ti ti-user"></i>Bill No</span>
                        <b>{{ $sale->c_bill_no ?? 'N/A' }} <small>({{ $sale->c_bill_no ?? '' }})</small></b>
                    </div>
                    <div class="spc-item">
                        <span><i class="ti ti-user"></i>Employee</span>
                        <b>{{ $sale->employee?->c_employee_name ?? 'N/A' }} <small>({{ $sale->employee?->c_employee_code ?? '' }})</small></b>
                    </div>
                    <div class="spc-item">
                        <span><i class="ti ti-building-store"></i>Store</span>
                        <b>{{ $sale->store?->c_store_name ?? 'N/A' }} <small>({{ $sale->store?->c_store_code ?? '' }})</small></b>
                    </div>
                    <div class="spc-item">
                        <span><i class="ti ti-calendar"></i>Sale Date</span>
                        <b>{{ \Carbon\Carbon::parse($sale->d_date)->format('d M Y') }}</b>
                    </div>
                    <div class="spc-item">
                        <span><i class="ti ti-package"></i>Item / Product</span>
                        <b>{{ $sale->product?->c_product_name ?? 'N/A' }}</b>
                    </div>
                    <div class="spc-item">
                        <span><i class="ti ti-hash"></i>Quantity</span>
                        <b>{{ $sale->n_quantity }}</b>
                    </div>
                    <div class="spc-item">
                        <span><i class="ti ti-currency-rupee"></i>Unit Price</span>
                        <b>₹{{ number_format($sale->product?->n_selling_price ?? 0, 2) }}</b>
                    </div>
                    <div class="spc-item">
                        <span><i class="ti ti-currency-rupee"></i>Unit Purchase Rate</span>
                        <b>₹{{ number_format($sale->product?->n_purchase_price ?? 0, 2) }}</b>
                    </div>
                </div>

                <div class="spc-tiles">
                    <div class="spc-tile">
                        <div class="ti-ico blue"><i class="fa-solid fa-cart-shopping"></i></div>
                        <div><b>₹{{ number_format($sale->total_sales_amount, 2) }}</b><span>Total Sales</span></div>
                    </div>
                    <div class="spc-tile">
                        <div class="ti-ico red"><i class="fa-solid fa-boxes-stacked"></i></div>
                        <div><b>₹{{ number_format($sale->total_purchase_amount, 2) }}</b><span>Total Purchase</span></div>
                    </div>
                    <div class="spc-tile">
                        <div class="ti-ico"><i class="fa-solid fa-chart-line"></i></div>
                        <div><b>₹{{ number_format($sale->total_margin_amount, 2) }}</b><span>Total Margin</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incentive Distribution Breakdown -->
    <div class="spc-card">
        <div class="spc-section-title">
            <i class="fa-solid fa-sitemap"></i>Incentive Distribution Breakdown
        </div>

        @php
        $groupedIncentives = $sale->incentives()->with('employee.designation')->get()->groupBy('c_pool_name');
        @endphp

        @forelse($groupedIncentives as $poolName => $poolIncentives)
        <div class="spc-pool">
            <div class="spc-pool-head">
                <div class="spc-pool-title">
                    <i class="ti ti-grid-dots"></i>
                    {{ str_replace('_', ' ', $poolName ?: 'OTHER') }}
                    @if($poolIncentives->first()->n_pool_percentage)
                    <em>({{ number_format($poolIncentives->first()->n_pool_percentage, 2) }}%)</em>
                    @endif
                </div>

                <div class="spc-pool-total">
                    <div>
                        <small>Pool Total Amount</small>
                        <b>₹{{ number_format($poolIncentives->sum('n_incentive_amount'), 2) }}</b>
                    </div>
                    <div class="w"><i class="ti ti-wallet"></i></div>
                </div>
            </div>

            <div class="spc-table-wrap" style="border:1px solid #eef1f4;border-radius:14px;">
                <table class="spc-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th class="text-end">Incentive Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($poolIncentives as $incentive)
                        @php
                            $iname = $incentive->employee?->c_employee_name ?? 'Unknown';
                            $iinit = collect(preg_split('/\s+/', trim($iname)))->filter()->take(2)->map(fn($p) => strtoupper(substr($p, 0, 1)))->implode('');
                        @endphp
                        <tr>
                            <td><span class="spc-code">{{ $incentive->employee?->c_employee_code ?? 'N/A' }}</span></td>
                            <td>
                                <div class="spc-person">
                                    <div class="spc-avatar">{{ $iinit ?: '?' }}</div>
                                    <div class="spc-name">{{ $iname }}</div>
                                </div>
                            </td>
                            <td><span class="spc-pill info">{{ $incentive->employee?->designation->c_designation ?? 'N/A' }}</span></td>
                            <td class="text-end"><span class="spc-amt">₹{{ number_format($incentive->n_incentive_amount, 2) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="spc-empty">
            <div class="ew"><i class="fa-solid fa-circle-info"></i></div>
            <b>No incentives calculated for this sale yet.</b>
        </div>
        @endforelse
    </div>
</div>
@endsection