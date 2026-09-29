{{--
    Mobile bottom navigation dock (HR shell).
    Rendered only below 1000px — see the .spc-mnav styles in hr/layouts/app.blade.php.
    Items: Home, Attendance, Leave (raised), Payroll, Profile.
--}}
@php
    $hrMnavSeg = request()->segment(2); // /hr/modules/{seg}
@endphp
<nav class="spc-mnav" aria-label="Mobile navigation">
    <div class="spc-mnav-inner">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i><span>Home</span>
        </a>
        <a href="{{ url('/hr/modules/attendance') }}" class="{{ $hrMnavSeg === 'attendance' ? 'active' : '' }}">
            <i class="fa-solid fa-stopwatch"></i><span>Attendance</span>
        </a>
        <a href="{{ url('/hr/modules/leave') }}"
           class="mnav-raise {{ $hrMnavSeg === 'leave' ? 'active' : '' }}">
            <span class="mnav-ico"><i class="fa-solid fa-calendar-days"></i></span><span>Leave</span>
        </a>
        <a href="{{ url('/hr/modules/payroll') }}" class="{{ $hrMnavSeg === 'payroll' ? 'active' : '' }}">
            <i class="fa-solid fa-wallet"></i><span>Payroll</span>
        </a>
        <a href="{{ route('hr.profile.index') }}" class="{{ request()->routeIs('hr.profile.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user"></i><span>Profile</span>
        </a>
    </div>
</nav>
