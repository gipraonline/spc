@extends('hr.layouts.app')

@section('title', $module['title'])

@section('content')
    @include('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'System',
        'heroIcon' => 'fa-solid fa-shield-halved',
        'heroSummary' => 'Manage access, roles and the organization-wide audit trail.',
        'heroStats' => [
            ['label' => 'Users', 'icon' => 'fa-solid fa-user-gear', 'value' => $totalUsers],
            ['label' => 'Active', 'icon' => 'fa-solid fa-user-check', 'value' => $activeUsersCount],
            ['label' => 'Audit entries', 'icon' => 'fa-solid fa-timeline', 'value' => $totalAuditEntries],
        ],
    ])

    <div class="content">

        {{-- =========================================================
            USERS
        ========================================================== --}}
        <div class="table-card">
            <div class="tc-head">
                <h3>
                    <span class="wh-ico">
                        <i class="fa-solid fa-users-gear"></i>
                    </span>
                    Users
                </h3>

                <span class="pill pill-muted">
                    {{ $totalUsers }} accounts
                </span>
            </div>

            <div class="tc-body">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $u)
                            <tr>
                                <td>
                                    <div class="cell-emp">
                                        <div class="av">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <b>{{ $u->name }}</b>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    {{ $u->email }}
                                </td>

                                <td>
                                    {{ $u->roleLabel() }}
                                </td>

                                <td>
                                    <span class="pill {{ $u->is_active ? 'pill-ok' : 'pill-bad' }}">
                                        {{ $u->is_active ? 'Active' : 'Suspended' }}
                                    </span>
                                </td>

                                <td>
                                    @can('hr-system.edit')
                                        <a
                                            href="{{ request()->fullUrlWithQuery(['user' => $u->id]) }}"
                                            class="btn-ghost"
                                        >
                                            <i
                                                class="fa-solid fa-pen"
                                                style="font-size:11px;"
                                            ></i>
                                            Edit
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-widget">
                                        <div class="ew-ico">
                                            <i class="fa-solid fa-users"></i>
                                        </div>

                                        <b>No users found</b>
                                        <span>
                                            There are no user accounts to display.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $users->links() }}
            </div>
        </div>


        {{-- =========================================================
            ADD USER
        ========================================================== --}}
        @can('hr-system.create')
            @if(!$editingUser)
                <div class="card" style="margin-top:20px;">
                    <div class="widget-head">
                        <div class="wh-ico">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>

                        <div>
                            <h3>Add user</h3>
                            <p>
                                Creates a portal account with the chosen role.
                            </p>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('hr.system.user.store') }}"
                    >
                        @csrf

                        <div class="field-grid">

                            <div class="field">
                                <label>Full name</label>

                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                >

                                @error('name')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>


                            <div class="field">
                                <label>Email</label>

                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                >

                                @error('email')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>


                            <div class="field">
                                <label>Role</label>

                                <select name="role" required>
                                    @foreach($roles as $key => $data)
                                        <option
                                            value="{{ $key }}"
                                            @selected(old('role') === $key)
                                        >
                                            {{ $data['label'] }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('role')
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
                                <i class="fa-solid fa-user-plus"></i>
                                Create user
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        @endcan


        {{-- =========================================================
            EDIT USER
        ========================================================== --}}
        @can('hr-system.edit')
            @if($editingUser)
                <div class="card" style="margin-top:20px;">
                    <div class="widget-head">
                        <div class="wh-ico">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>

                        <div>
                            <h3>Edit user</h3>

                            <p>
                                Updating {{ $editingUser->name }}
                            </p>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('hr.system.user.update', $editingUser) }}"
                    >
                        @csrf

                        <div class="field-grid">

                            <div class="field">
                                <label>Full name</label>

                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $editingUser->name) }}"
                                    required
                                >

                                @error('name')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>


                            <div class="field">
                                <label>Email</label>

                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $editingUser->email) }}"
                                    required
                                >

                                @error('email')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>


                            <div class="field">
                                <label>Role</label>

                                <select name="role" required>
                                    @foreach($roles as $key => $data)
                                        <option
                                            value="{{ $key }}"
                                            @selected(
                                                old(
                                                    'role',
                                                    $editingUser->role
                                                ) === $key
                                            )
                                        >
                                            {{ $data['label'] }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('role')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>


                            <div
                                class="field"
                                style="flex-direction:row;align-items:center;gap:8px;"
                            >
                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    style="width:auto;"
                                    @checked(
                                        old(
                                            'is_active',
                                            $editingUser->is_active
                                        )
                                    )
                                >

                                <label style="margin:0;">
                                    Active
                                </label>
                            </div>

                        </div>

                        <div class="form-actions">

                            <button
                                type="submit"
                                class="btn-primary"
                            >
                                <i class="fa-solid fa-floppy-disk"></i>
                                Save user
                            </button>

                            <a
                                href="{{ route('hr.system.index') }}"
                                class="btn-secondary"
                            >
                                Cancel
                            </a>

                        </div>
                    </form>
                </div>
            @endif
        @endcan


        {{-- =========================================================
            AUDIT LOG
        ========================================================== --}}
        <div
            class="section-head"
            style="margin-top:30px;"
        >
            <h2>
                <i class="fa-solid fa-timeline"></i>
                Audit log
            </h2>

            <span class="hint">
                Every privileged action, newest first
            </span>
        </div>


        <div class="table-card">
            <div class="tc-body">

                @if($auditLog->isEmpty())

                    <div class="empty-widget">
                        <div class="ew-ico">
                            <i class="fa-solid fa-timeline"></i>
                        </div>

                        <b>No audit entries yet</b>

                        <span>
                            Privileged actions will be recorded here.
                        </span>
                    </div>

                @else

                    <table>
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($auditLog as $log)
                                <tr>

                                    <td>
                                        {{ \Illuminate\Support\Carbon::parse($log->created_at)->format('d M, H:i') }}
                                    </td>

                                    <td>
                                        <div class="cell-emp">

                                            <div class="av">
                                                {{ strtoupper(
                                                    substr(
                                                        $log->user->name ?? 'S',
                                                        0,
                                                        1
                                                    )
                                                ) }}
                                            </div>

                                            <div>
                                                <b>
                                                    {{ $log->user->name ?? 'System' }}
                                                </b>
                                            </div>

                                        </div>
                                    </td>

                                    <td>
                                        <span class="pill pill-muted">
                                            {{ $log->action }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $log->module
                                            )
                                        ) }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $auditLog->links() }}

                @endif

            </div>
        </div>

    </div>
@endsection