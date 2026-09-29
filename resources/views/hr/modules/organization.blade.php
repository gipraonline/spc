@extends('hr.layouts.app')

@section('title', $module['title'])

@section('content')
    @include('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Records',
        'heroIcon' => 'fa-regular fa-building',
        'heroSummary' => 'Departments, designations and the company holiday calendar.',
        'heroStats' => [
            ['label' => 'Departments', 'icon' => 'fa-solid fa-sitemap', 'value' => $departments->count()],
            ['label' => 'Designations', 'icon' => 'fa-solid fa-briefcase', 'value' => $designations->count()],
            ['label' => 'Holidays', 'icon' => 'fa-regular fa-calendar', 'value' => $holidays->count()],
        ],
    ])

    <div class="content">

        {{-- =========================================================
            TABS
        ========================================================== --}}
        <div class="tabs">
            <button
                type="button"
                class="tab active"
                data-tab="departments"
                onclick="hrTab(this, 'departments')"
            >
                Departments
            </button>

            <button
                type="button"
                class="tab"
                data-tab="designations"
                onclick="hrTab(this, 'designations')"
            >
                Designations
            </button>

            <button
                type="button"
                class="tab"
                data-tab="holidays"
                onclick="hrTab(this, 'holidays')"
            >
                Holiday calendar
            </button>
        </div>


        {{-- =========================================================
            DEPARTMENTS
        ========================================================== --}}
        <div
            class="tabpanel active"
            data-tabpanel="departments"
        >
            <div class="grid-2">

                {{-- Department list --}}
                <div class="table-card">

                    <div class="tc-head">
                        <h3>
                            <span class="wh-ico">
                                <i class="fa-solid fa-sitemap"></i>
                            </span>
                            Departments
                        </h3>
                    </div>

                    <div class="tc-body">

                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Employees</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($departments as $d)
                                    <tr>
                                        <td>

                                            @can('organization.edit')

                                                <form
                                                    method="POST"
                                                    action="{{ route('hr.organization.department.update', $d) }}"
                                                    style="display:flex;gap:6px;"
                                                >
                                                    @csrf

                                                    <input
                                                        name="name"
                                                        value="{{ $d->name }}"
                                                        style="padding:5px 8px;font-size:12.5px;"
                                                    >

                                                    <input
                                                        name="code"
                                                        value="{{ $d->code }}"
                                                        style="padding:5px 8px;font-size:12.5px;width:70px;"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="btn-ghost"
                                                        style="padding:0;"
                                                    >
                                                        Save
                                                    </button>
                                                </form>

                                            @else

                                                <b>{{ $d->name }}</b>

                                            @endcan

                                        </td>

                                        <td>
                                            {{ $d->code }}
                                        </td>

                                        <td>
                                            {{ $d->employees_count }}
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="3">
                                            <div class="empty-widget">
                                                <div class="ew-ico">
                                                    <i class="fa-solid fa-sitemap"></i>
                                                </div>

                                                <b>No departments</b>

                                                <span>
                                                    No departments have been created yet.
                                                </span>
                                            </div>
                                        </td>
                                    </tr>

                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>


                {{-- Add department --}}
                @can('organization.create')

                    <div class="card">

                        <div class="widget-head">
                            <div class="wh-ico">
                                <i class="fa-solid fa-plus"></i>
                            </div>

                            <div>
                                <h3>Add department</h3>
                                <p>Create a new department for the org.</p>
                            </div>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('hr.organization.department.store') }}"
                        >
                            @csrf

                            <div class="field-grid">

                                <div class="field">
                                    <label>Name</label>

                                    <input
                                        name="name"
                                        value="{{ old('name') }}"
                                        placeholder="e.g. Finance"
                                        required
                                    >

                                    @error('name')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label>Code</label>

                                    <input
                                        name="code"
                                        value="{{ old('code') }}"
                                        placeholder="e.g. FIN"
                                        required
                                    >

                                    @error('code')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                            </div>

                            <div class="form-actions">
                                <button
                                    type="submit"
                                    class="btn-primary"
                                >
                                    <i class="fa-solid fa-plus"></i>
                                    Add department
                                </button>
                            </div>

                        </form>
                    </div>

                @endcan

            </div>
        </div>


        {{-- =========================================================
            DESIGNATIONS
        ========================================================== --}}
        <div
            class="tabpanel"
            data-tabpanel="designations"
        >
            <div class="grid-2">

                {{-- Designation list --}}
                <div class="table-card">

                    <div class="tc-head">
                        <h3>
                            <span class="wh-ico">
                                <i class="fa-solid fa-briefcase"></i>
                            </span>
                            Designations
                        </h3>
                    </div>

                    <div class="tc-body">

                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Department</th>
                                    <th>Employees</th>
                                    @can('organization.delete')<th></th>@endcan
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($designations as $d)

                                    <tr>
                                        <td>

                                            @can('organization.edit')

                                                <form
                                                    method="POST"
                                                    action="{{ route('hr.organization.designation.update', $d) }}"
                                                    style="display:flex;gap:6px;"
                                                >
                                                    @csrf

                                                    <input
                                                        name="title"
                                                        value="{{ $d->title }}"
                                                        style="padding:5px 8px;font-size:12.5px;"
                                                    >

                                                    <select
                                                        name="department_id"
                                                        style="padding:5px 8px;font-size:12.5px;"
                                                    >
                                                        <option value="">
                                                            &mdash;
                                                        </option>

                                                        @foreach($departments as $dep)
                                                            <option
                                                                value="{{ $dep->id }}"
                                                                @selected($d->department_id === $dep->id)
                                                            >
                                                                {{ $dep->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <button
                                                        type="submit"
                                                        class="btn-ghost"
                                                        style="padding:0;"
                                                    >
                                                        Save
                                                    </button>

                                                </form>

                                            @else

                                                <b>{{ $d->title }}</b>

                                            @endcan

                                        </td>

                                        <td>
                                            {{ $d->department->name ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $d->employees_count }}
                                        </td>

                                        @can('organization.delete')
                                            <td>
                                                <form
                                                    method="POST"
                                                    action="{{ route('hr.organization.designation.destroy', $d) }}"
                                                    onsubmit="return confirm('Delete this designation from HR and SPC?')"
                                                >
                                                    @csrf
                                                    <button type="submit" class="btn-ghost" style="padding:0;color:#c0392b;">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        @endcan
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-widget">

                                                <div class="ew-ico">
                                                    <i class="fa-solid fa-briefcase"></i>
                                                </div>

                                                <b>No designations</b>

                                                <span>
                                                    No designations have been created yet.
                                                </span>

                                            </div>
                                        </td>
                                    </tr>

                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>


                {{-- Add designation --}}
                @can('organization.create')

                    <div class="card">

                        <div class="widget-head">
                            <div class="wh-ico">
                                <i class="fa-solid fa-plus"></i>
                            </div>

                            <div>
                                <h3>Add designation</h3>
                                <p>Create a new job title.</p>
                            </div>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('hr.organization.designation.store') }}"
                        >
                            @csrf

                            <div class="field-grid">

                                <div class="field">
                                    <label>Title</label>

                                    <input
                                        name="title"
                                        value="{{ old('title') }}"
                                        placeholder="e.g. Finance Manager"
                                        required
                                    >

                                    @error('title')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>


                                <div class="field">
                                    <label>Department</label>

                                    <select name="department_id">
                                        <option value="">
                                            &mdash;
                                        </option>

                                        @foreach($departments as $dep)
                                            <option
                                                value="{{ $dep->id }}"
                                                @selected(old('department_id') == $dep->id)
                                            >
                                                {{ $dep->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('department_id')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label>Reports to</label>

                                    <select name="parent_designation_id">
                                        <option value="">&mdash; None (top level) &mdash;</option>

                                        @foreach($parentOptions as $opt)
                                            <option
                                                value="{{ $opt->n_designation_id }}"
                                                @selected(old('parent_designation_id') == $opt->n_designation_id)
                                            >
                                                {{ $opt->c_designation }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('parent_designation_id')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                            </div>

                            <div class="form-actions">
                                <button
                                    type="submit"
                                    class="btn-primary"
                                >
                                    <i class="fa-solid fa-plus"></i>
                                    Add designation
                                </button>
                            </div>

                        </form>
                    </div>

                @endcan

            </div>
        </div>


        {{-- =========================================================
            HOLIDAYS
        ========================================================== --}}
        <div
            class="tabpanel"
            data-tabpanel="holidays"
        >
            <div class="grid-2">

                {{-- Holiday list --}}
                <div class="table-card">

                    <div class="tc-head">
                        <h3>
                            <span class="wh-ico">
                                <i class="fa-regular fa-calendar"></i>
                            </span>
                            Holiday calendar
                        </h3>
                    </div>

                    <div class="tc-body">

                        <table>
                            <thead>
                                <tr>
                                    <th>Holiday</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($holidays as $h)

                                    <tr>

                                        <td>
                                            {{ $h->name }}
                                        </td>

                                        <td>
                                            {{ \Illuminate\Support\Carbon::parse($h->holiday_date)->format('d M Y (D)') }}
                                        </td>

                                        <td>
                                            <span class="pill {{ $h->is_optional ? 'pill-muted' : 'pill-ok' }}">
                                                {{ $h->is_optional ? 'Optional' : 'Mandatory' }}
                                            </span>
                                        </td>

                                        <td>

                                            @can('organization.delete')

                                                <form
                                                    method="POST"
                                                    action="{{ route('hr.organization.holiday.destroy', $h) }}"
                                                    onsubmit="return confirm('Remove this holiday?');"
                                                >
                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="btn-ghost"
                                                    >
                                                        <i class="fa-solid fa-trash"></i>
                                                        Remove
                                                    </button>
                                                </form>

                                            @endcan

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-widget">

                                                <div class="ew-ico">
                                                    <i class="fa-regular fa-calendar"></i>
                                                </div>

                                                <b>No holidays</b>

                                                <span>
                                                    No holidays have been added yet.
                                                </span>

                                            </div>
                                        </td>
                                    </tr>

                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>


                {{-- Add holiday --}}
                @can('organization.create')

                    <div class="card">

                        <div class="widget-head">
                            <div class="wh-ico">
                                <i class="fa-regular fa-calendar-plus"></i>
                            </div>

                            <div>
                                <h3>Add holiday</h3>
                                <p>
                                    Feeds dashboards and WFH/leave context.
                                </p>
                            </div>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('hr.organization.holiday.store') }}"
                        >
                            @csrf

                            <div class="field-grid">

                                <div class="field full">
                                    <label>Name</label>

                                    <input
                                        name="name"
                                        value="{{ old('name') }}"
                                        placeholder="e.g. Diwali"
                                        required
                                    >

                                    @error('name')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>


                                <div class="field">
                                    <label>Date</label>

                                    <input
                                        type="date"
                                        name="holiday_date"
                                        value="{{ old('holiday_date') }}"
                                        required
                                    >

                                    @error('holiday_date')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>


                                <div
                                    class="field"
                                    style="flex-direction:row;align-items:center;gap:8px;margin-top:22px;"
                                >
                                    <input
                                        type="checkbox"
                                        name="is_optional"
                                        value="1"
                                        style="width:auto;"
                                        @checked(old('is_optional'))
                                    >

                                    <label style="margin:0;">
                                        Optional holiday
                                    </label>
                                </div>

                            </div>

                            <div class="form-actions">
                                <button
                                    type="submit"
                                    class="btn-primary"
                                >
                                    <i class="fa-regular fa-calendar-plus"></i>
                                    Add holiday
                                </button>
                            </div>

                        </form>
                    </div>

                @endcan

            </div>
        </div>

    </div>


    {{-- =============================================================
        TABS SCRIPT
    ============================================================== --}}
    <script>
        function hrTab(btn, name) {
            document
                .querySelectorAll('.tabs .tab')
                .forEach(t => t.classList.remove('active'));

            document
                .querySelectorAll('.content > .tabpanel')
                .forEach(p => p.classList.remove('active'));

            btn.classList.add('active');

            document
                .querySelector('.content > [data-tabpanel="' + name + '"]')
                .classList.add('active');
        }
    </script>

@endsection