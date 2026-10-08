@extends('layouts.app')

@section('content')
<style>
.al-page { padding: 8px 4px 40px; font-family: 'Outfit', sans-serif; color: #22352C; }
.al-page h2 { font-size: 22px; font-weight: 600; margin: 0 0 4px; color: #1F3D14; }
.al-sub { color: #61756B; font-size: 13px; margin-bottom: 18px; }
.al-card { background: #fff; border: 1px solid rgba(18,58,40,.13); border-radius: 14px; padding: 18px; margin-bottom: 18px; box-shadow: 0 1px 2px rgba(10,61,44,.05); }
.al-card h3 { font-size: 15px; font-weight: 600; margin: 0 0 12px; }
.al-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 12px; }
.al-grid label { display: block; font-size: 12px; font-weight: 500; margin-bottom: 4px; color: #61756B; }
.al-grid input, .al-grid select, .al-grid textarea { width: 100%; border: 1px solid rgba(18,58,40,.2); border-radius: 9px; padding: 8px 10px; font-size: 13px; }
.al-btn { border: 1px solid #1F5C2E; background: linear-gradient(135deg, #5E8D3D, #1F5C2E); color: #fff; border-radius: 9px; padding: 8px 16px; font-size: 12.5px; font-weight: 500; cursor: pointer; }
.al-btn.ghost { background: #fff; color: #1F5C2E; }
.al-btn.bad { background: #C0392B; border-color: #C0392B; }
.al-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.al-table th { text-align: left; font-size: 11.5px; text-transform: uppercase; letter-spacing: .04em; color: #61756B; padding: 8px 10px; border-bottom: 1px solid rgba(18,58,40,.13); }
.al-table td { padding: 10px; border-bottom: 1px solid rgba(18,58,40,.07); vertical-align: top; }
.al-pill { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 600; }
.al-pill.pending { background: #FCF0D8; color: #7A5B00; }
.al-pill.approved { background: #DCF3E4; color: #1F5C2E; }
.al-pill.rejected { background: #FBE7E4; color: #A12B1F; }
.al-pill.cancelled { background: #ECEFEE; color: #61756B; }
.al-alert { border-radius: 10px; padding: 10px 14px; font-size: 13px; margin-bottom: 14px; }
.al-alert.ok { background: #DCF3E4; color: #1F5C2E; }
.al-alert.err { background: #FBE7E4; color: #A12B1F; }
.al-muted { color: #61756B; }
.al-row-actions { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
.al-row-actions input[type=text] { border: 1px solid rgba(18,58,40,.2); border-radius: 8px; padding: 5px 8px; font-size: 12px; width: 130px; }
</style>

<div class="al-page">
    <h2>Leave Requests</h2>
    <div class="al-sub">
        @if($isAssociate) Apply for leave and track approval. @else Approve or reject leave requests from associates (Farm Care Advisers / Tele Callers). @endif
    </div>

    @if(session('success')) <div class="al-alert ok">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="al-alert err">{{ session('error') }}</div> @endif
    @if($errors->any()) <div class="al-alert err">{{ $errors->first() }}</div> @endif

    @if($isAssociate)
    <div class="al-card">
        <h3>Apply for leave</h3>
        <form method="POST" action="{{ route('admin.associate-leave.store') }}">
            @csrf
            <div class="al-grid">
                <div>
                    <label>Leave type</label>
                    <select name="leave_type" required>
                        @foreach($types as $t)
                        <option value="{{ $t }}" {{ old('leave_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>From</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" min="{{ now()->toDateString() }}" required>
                </div>
                <div>
                    <label>To</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" min="{{ now()->toDateString() }}" required>
                </div>
                <div style="grid-column: 1 / -1;">
                    <label>Reason (optional)</label>
                    <textarea name="reason" rows="2" maxlength="500">{{ old('reason') }}</textarea>
                </div>
            </div>
            <div style="margin-top:12px;"><button type="submit" class="al-btn">Send request</button></div>
        </form>
    </div>

    <div class="al-card">
        <h3>My requests</h3>
        @if($mine->isEmpty())
            <div class="al-muted">No leave requests yet.</div>
        @else
        <div style="overflow-x:auto;">
        <table class="al-table">
            <thead><tr><th>Type</th><th>Dates</th><th>Days</th><th>Status</th><th>Decision</th><th></th></tr></thead>
            <tbody>
            @foreach($mine as $r)
                <tr>
                    <td>{{ $r->leave_type }}</td>
                    <td>{{ $r->start_date->format('d M Y') }} – {{ $r->end_date->format('d M Y') }}</td>
                    <td>{{ $r->days }}</td>
                    <td><span class="al-pill {{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                    <td class="al-muted">
                        @if($r->decided_by_name) {{ $r->decided_by_name }}, {{ $r->decided_at?->format('d M') }} @endif
                        @if($r->decision_remark)<br>{{ $r->decision_remark }}@endif
                    </td>
                    <td>
                        @if($r->status === 'pending')
                        <form method="POST" action="{{ route('admin.associate-leave.cancel', $r->id) }}">
                            @csrf
                            <button type="submit" class="al-btn ghost" onclick="return confirm('Cancel this request?')">Cancel</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        @endif
    </div>
    @else
    <div class="al-card">
        <h3>Requests to review</h3>
        @if($approvals->isEmpty())
            <div class="al-muted">No leave requests from associates reporting to you.</div>
        @else
        <div style="overflow-x:auto;">
        <table class="al-table">
            <thead><tr><th>Associate</th><th>Type</th><th>Dates</th><th>Days</th><th>Reason</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @foreach($approvals as $r)
                <tr>
                    <td>
                        <b>{{ $r->employee?->c_employee_name ?? '—' }}</b><br>
                        <span class="al-muted">{{ $r->employee?->c_employee_code }} · {{ $r->employee?->designation?->c_designation }}</span>
                    </td>
                    <td>{{ $r->leave_type }}</td>
                    <td>{{ $r->start_date->format('d M Y') }} – {{ $r->end_date->format('d M Y') }}</td>
                    <td>{{ $r->days }}</td>
                    <td class="al-muted">{{ $r->reason ?: '—' }}</td>
                    <td>
                        <span class="al-pill {{ $r->status }}">{{ ucfirst($r->status) }}</span>
                        @if($r->decided_by_name)<div class="al-muted" style="font-size:11.5px;margin-top:4px;">{{ $r->decided_by_name }}</div>@endif
                    </td>
                    <td>
                        @if($r->status === 'pending')
                        <form method="POST" action="{{ route('admin.associate-leave.decide', $r->id) }}" class="al-row-actions">
                            @csrf
                            <input type="text" name="remark" placeholder="Remark (optional)" maxlength="500">
                            <button type="submit" name="decision" value="approved" class="al-btn">Approve</button>
                            <button type="submit" name="decision" value="rejected" class="al-btn bad">Reject</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
