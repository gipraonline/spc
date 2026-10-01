@extends('layouts.app')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
@endpush

@section('content')
<style>
/* ===== Franchises — same visual language as the Employee Records module ===== */
.employee-page {
    --brand: #4E7A33;
    --brand-strong: #0E5239;
    --brand-ink: #1F3D14;
    --brand-bright: #5E8D3D;
    --brand-glow: rgba(94, 141, 61, .35);
    --brand-soft: #E4F3EB;
    --brand-softer: #F2F9F5;
    --line: rgba(18, 58, 40, 0.13);
    --line-soft: rgba(18, 58, 40, 0.07);
    --text: #22352C;
    --text-muted: #61756B;
    --ok-soft: #DCF3E4;
    --warn-soft: #FCF0D8;
    --bad-soft: #FBE7E4;
    --shadow-sm: 0 1px 2px rgba(10, 61, 44, .05);
    --font-head: 'Kanit', sans-serif;
    --font-body: 'Outfit', sans-serif;
    padding: 24px clamp(16px, 3vw, 32px) 8px;
    font-family: var(--font-body);
    color: var(--text);
}

.employee-page-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.employee-page-heading h2 {
    margin: 0;
    font-family: var(--font-head);
    font-weight: 600;
    font-size: 21px;
    color: var(--brand-ink);
    display: flex;
    align-items: center;
    gap: 10px;
}

.employee-page-heading h2 i {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: var(--brand-soft);
    color: var(--brand);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.employee-page-heading p {
    margin: 5px 0 0;
    color: var(--text-muted);
    font-size: 13px;
}

.employee-directory-card {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.employee-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 18px 20px 16px;
    background: #fff;
    flex-wrap: wrap;
}

.employee-search {
    position: relative;
    height: 41px;
    width: 280px;
    max-width: 100%;
    display: flex;
    align-items: center;
    gap: 9px;
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 0 12px;
    background: #FBFDFC;
}

.employee-search:focus-within {
    border-color: var(--brand-bright);
    background: #fff;
    box-shadow: 0 0 0 3.5px rgba(94, 141, 61, .14);
}

.employee-search span {
    font-size: 14px;
    color: var(--text-muted);
}

.employee-search input {
    border: 0;
    background: transparent;
    padding: 0;
    font-size: 13px;
    min-width: 0;
    outline: 0;
    box-shadow: none;
    width: 100%;
}

.employee-search #employee_suggestions {
    top: calc(100% + 6px);
    left: 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(10, 61, 44, .12);
    z-index: 1000;
    background: #fff;
}

.employee-search #employee_suggestions .list-group-item {
    border: 0;
    padding: 10px 14px;
    cursor: pointer;
    font-size: 13px;
    font-family: var(--font-body);
}

.employee-search #employee_suggestions .list-group-item:hover {
    background: var(--brand-softer);
}

.employee-filters {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.employee-filters select {
    height: 41px;
    min-width: 170px;
    padding: 0 10px;
    border: 1px solid var(--line);
    background: #fff;
    font-size: 13px;
    border-radius: 12px;
    font-family: var(--font-body);
    color: var(--text);
}

.employee-filter-btn,
.employee-reset-btn {
    height: 41px;
    border: 0;
    background: var(--brand-softer);
    color: var(--brand);
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    padding: 0 16px;
    border-radius: 12px;
    font-family: var(--font-body);
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}

.employee-filter-btn {
    background: var(--brand);
    color: #fff;
}

.employee-filter-btn:hover {
    filter: brightness(1.07);
}

.employee-reset-btn:hover {
    background: var(--brand-soft);
}

.employee-add {
    height: 41px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 0 17px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, #5E8D3D, #1F5C2E);
    color: #fff;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    font-family: var(--font-body);
    box-shadow: 0 10px 20px -10px var(--brand-glow);
    text-decoration: none;
}

.employee-add:hover {
    filter: brightness(1.07);
    color: #fff;
}

.employee-table-wrap {
    overflow-x: auto;
    border-top: 1px solid var(--line-soft);
}

.employee-table {
    min-width: 1080px;
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
}

.employee-table th {
    padding: 12px;
    border-bottom: 1px solid var(--line-soft);
    background: #F7FBF8;
    color: #5B6E63;
    font-size: 10.5px;
    letter-spacing: .07em;
    text-transform: uppercase;
    text-align: left;
}

.employee-table td {
    padding: 12px;
    border-bottom: 1px solid var(--line-soft);
    color: var(--text);
    white-space: nowrap;
    height: 64px;
}

.employee-table tbody tr:last-child td {
    border-bottom: 0;
}

.employee-table tbody tr:hover {
    background: #F4FAF7;
}

.employee-table th:first-child,
.employee-table td:first-child {
    padding-left: 14px;
}

.employee-table th.actions-head {
    width: 160px;
}

.employee-person {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 205px;
}

.employee-avatar {
    width: 34px;
    height: 34px;
    border-radius: 11px;
    background: linear-gradient(135deg, var(--brand-soft), #D2EEDF);
    color: var(--brand);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
    flex-shrink: 0;
    font-family: var(--font-head);
}

.employee-name {
    font-family: var(--font-head);
    font-size: 13.5px;
    font-weight: 500;
    line-height: 1.3;
    color: var(--brand-ink);
}

.employee-email {
    font-size: 11.5px;
    color: var(--text-muted);
    line-height: 1.35;
    margin-top: 2px;
}

.employee-id {
    font-family: var(--font-body);
    font-size: 11.5px;
    color: #64756B;
}

.employee-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5.5px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
}

.employee-status i {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: block;
    background: currentColor;
}

.employee-status.active {
    background: var(--ok-soft);
    color: #116A38;
}

.employee-status.inactive {
    background: var(--bad-soft);
    color: #942B2B;
}

.employee-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 15px;
}

.employee-actions .ea-link {
    background: none;
    border: none;
    font-size: 12.5px;
    color: var(--brand);
    font-weight: 600;
    cursor: pointer;
    font-family: var(--font-body);
    padding: 0;
    text-decoration: none;
}

.employee-actions .ea-link:hover {
    color: var(--brand-strong);
    text-decoration: underline;
}

.employee-actions form {
    margin: 0;
}

.employee-actions .deactivate {
    height: 31px;
    border-radius: 9px;
    padding: 0 13px;
    font: 500 11.5px/1 var(--font-body);
    cursor: pointer;
    border: 1px solid #C23A3A;
    background: #C23A3A;
    color: #fff;
}

.employee-actions .deactivate:hover {
    filter: brightness(1.08);
}

.employee-table td.employee-empty {
    height: auto;
    padding: 46px 20px;
    text-align: center;
    white-space: normal;
    color: var(--text-muted);
    font-size: 13px;
}

.employee-table tbody tr:hover td.employee-empty {
    background: transparent;
}

.employee-empty-ico {
    width: 46px;
    height: 46px;
    margin: 0 auto 12px;
    border-radius: 14px;
    background: var(--brand-soft);
    color: var(--brand);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}

.employee-empty b {
    display: block;
    font-family: var(--font-head);
    font-size: 15px;
    font-weight: 500;
    color: var(--brand-ink);
    margin-bottom: 3px;
}

.employee-empty span {
    display: block;
}

.employee-pagination {
    padding: 16px 20px;
}

.employee-alert {
    margin-bottom: 16px;
}

@media (max-width:1100px) {
    .employee-toolbar {
        align-items: stretch;
        flex-direction: column;
    }

    .employee-search {
        width: 100%;
    }

    .employee-filters {
        justify-content: flex-start;
    }
}
</style>

<div class="employee-page">
    <div class="employee-page-heading">
        <div>
            <h2><i class="fa-solid fa-store"></i>Franchises</h2>
            <p>Search, filter and manage your franchises.</p>
        </div>
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success employee-alert" role="alert">
        {{ $message }}
    </div>
    @endif

    <div class="employee-directory-card">
        <!-- Search Store -->
        <form method="POST" action="{{ route('admin.franchises.search') }}" class="employee-toolbar">
            @csrf

            <div class="employee-search">
                <span aria-hidden="true"><i class="fa-solid fa-magnifying-glass" style="font-size:12px;"></i></span>
                <input type="text" name="search" value="{{ session('store_search') }}" placeholder="Store Code or Name..."
                    id="storeSearch" autocomplete="off">
            </div>

            <div class="employee-filters">
                {{-- State --}}
                <select name="state_id" id="state_id">
                    <option value="">All States</option>
                    @foreach($states as $state)
                    <option value="{{ $state->n_state_id }}"
                        {{ session('store_state_id') == $state->n_state_id ? 'selected' : '' }}>
                        {{ $state->name }}
                    </option>
                    @endforeach
                </select>

                {{-- District --}}
                <select name="district_id" id="district_id">
                    <option value="">All Districts</option>
                    @foreach($districts as $district)
                    <option value="{{ $district->id }}"
                        {{ session('store_district_id') == $district->id ? 'selected' : '' }}>
                        {{ $district->district_name }}
                    </option>
                    @endforeach
                </select>

                {{-- Panchayath --}}
                <select name="panchayath_id" id="panchayath_id">
                    <option value="">All Panchayaths</option>
                    @foreach($panchayaths as $panchayath)
                    <option value="{{ $panchayath->id }}"
                        {{ session('store_panchayath_id') == $panchayath->id ? 'selected' : '' }}>
                        {{ $panchayath->panchayath_name }}
                    </option>
                    @endforeach
                </select>

                <button type="submit" class="employee-filter-btn">
                    <i class="fa-solid fa-magnifying-glass" style="margin-right:6px;font-size:11px;"></i>Search
                </button>

                <a href="{{ route('admin.franchises.clearSearch') }}" class="employee-reset-btn">
                    <i class="fa-solid fa-rotate-left" style="margin-right:6px;font-size:11px;"></i>Reset
                </a>

                @canany(['franchises.export', 'franchises.view'])
                <a href="{{ route('admin.franchises.export') }}" class="employee-add" style="background:linear-gradient(135deg,#2f8f5b,#1a6b3f);">
                    <i class="fa-solid fa-file-excel" style="font-size:11px;"></i>Export
                </a>
                @endcanany

                @can('franchises.create')
                <a href="{{ route('admin.franchises.create') }}" class="employee-add">
                    <i class="fa-solid fa-plus" style="font-size:11px;"></i>Add Franchise
                </a>
                @endcan
            </div>
        </form>

        <div class="employee-table-wrap">
            <table class="employee-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Owner Name</th>
                        <th>State</th>
                        <th>District</th>
                        <th>Panchayath</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        @canany(['franchises.edit', 'franchises.delete'])
                        <th class="actions-head">Actions</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stores as $store)
                    @php
                    $storeName = $store->c_store_name ?? '?';
                    $initials = collect(preg_split('/\s+/', trim($storeName)))
                    ->filter()->take(2)->map(fn($part) => strtoupper(substr($part, 0, 1)))->implode('');
                    $isActive = $store->c_store_status === 'Y';
                    @endphp
                    <tr>
                        <td>
                            <div class="employee-person">
                                <div class="employee-avatar">{{ $initials ?: '?' }}</div>
                                <div class="employee-name">{{ $store->c_store_name }}</div>
                            </div>
                        </td>
                        <td class="employee-id">{{ $store->c_store_code }}</td>
                        <td>{{ $store->c_owner_name ?? '-' }}</td>
                        <td>{{ $store->state->name ?? '-' }}</td>
                        <td>{{ $store->district->district_name ?? '-' }}</td>
                        <td>{{ $store->panchayath->panchayath_name ?? '-' }}</td>
                        <td>{{ $store->c_store_email ?? '-' }}</td>
                        <td>{{ $store->n_store_phone ?? '-' }}</td>
                        <td>
                            <span class="employee-status {{ $isActive ? 'active' : 'inactive' }}">
                                <i></i>{{ $isActive ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        @canany(['franchises.edit', 'franchises.delete'])
                        <td>
                            <div class="employee-actions">
                                @can('franchises.edit')
                                <a href="{{ route('admin.franchises.edit', $store) }}" class="ea-link">Edit</a>
                                @endcan

                                @can('franchises.delete')
                                <form method="POST" action="{{ route('admin.franchises.destroy', $store) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="deactivate"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                @endcan
                            </div>
                        </td>
                        @endcanany
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="employee-empty">
                            <div class="employee-empty-ico"><i class="fa-solid fa-magnifying-glass"></i></div>
                            <b>No franchises found</b>
                            <span>Try changing the search or filters.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="employee-pagination">
            {{ $stores->links() }}
        </div>
    </div>
</div>

@endsection
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {

    const stateSelect = document.getElementById('state_id');
    const districtSelect = document.getElementById('district_id');
    const panchayathSelect = document.getElementById('panchayath_id');

    /*
    |--------------------------------------------------------------------------
    | STATE → DISTRICT
    |--------------------------------------------------------------------------
    */

    stateSelect.addEventListener('change', function() {

        const stateId = this.value;

        districtSelect.innerHTML =
            '<option value="">Loading Districts...</option>';

        panchayathSelect.innerHTML =
            '<option value="">All Panchayaths</option>';

        if (!stateId) {
            districtSelect.innerHTML =
                '<option value="">All Districts</option>';
            return;
        }

        fetch("{{ route('admin.districts', ':stateId') }}"
                .replace(':stateId', stateId))
            .then(response => response.json())
            .then(districts => {

                districtSelect.innerHTML =
                    '<option value="">All Districts</option>';

                districts.forEach(district => {

                    districtSelect.innerHTML += `
                        <option value="${district.id}">
                            ${district.district_name}
                        </option>
                    `;
                });

            })
            .catch(error => {

                console.error('Error loading districts:', error);

                districtSelect.innerHTML =
                    '<option value="">Unable to load districts</option>';
            });
    });


    /*
    |--------------------------------------------------------------------------
    | DISTRICT → PANCHAYATH
    |--------------------------------------------------------------------------
    */

    districtSelect.addEventListener('change', function() {

        const districtId = this.value;

        panchayathSelect.innerHTML =
            '<option value="">Loading Panchayaths...</option>';

        if (!districtId) {

            panchayathSelect.innerHTML =
                '<option value="">All Panchayaths</option>';

            return;
        }

        fetch("{{ route('admin.filterPanchayath') }}?district=" + districtId)
            .then(response => response.json())
            .then(response => {

                console.log('Panchayath response:', response);

                panchayathSelect.innerHTML =
                    '<option value="">All Panchayaths</option>';

                if (
                    response.panchayaths &&
                    response.panchayaths.length > 0
                ) {

                    response.panchayaths.forEach(panchayath => {

                        panchayathSelect.innerHTML += `
                            <option value="${panchayath.id}">
                                ${panchayath.panchayath_name}
                            </option>
                        `;
                    });

                } else {

                    panchayathSelect.innerHTML =
                        '<option value="">No Panchayaths Found</option>';
                }

            })
            .catch(error => {

                console.error(
                    'Error loading panchayaths:',
                    error
                );

                panchayathSelect.innerHTML =
                    '<option value="">Unable to load Panchayaths</option>';
            });
    });

});
</script>

@endpush