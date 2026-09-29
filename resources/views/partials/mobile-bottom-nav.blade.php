{{--
    Mobile bottom navigation dock (main site).
    Rendered only below 992px — see the .spc-mnav styles in the layouts.
    Items mirror the most-used areas: Home, Leads, Orders, Stores, HR.
--}}
@php
    $mnavIsHr = request()->is('hr') || request()->is('hr/*') || request()->routeIs('hr.*');
@endphp
<nav class="spc-mnav" aria-label="Mobile navigation">
    <div class="spc-mnav-inner">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i><span>Home</span>
        </a>
        <a href="{{ route('admin.leads.index') }}" class="{{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
            <i class="fa-solid fa-bullseye"></i><span>Leads</span>
        </a>
        <a href="{{ route('admin.salesorders.index') }}"
           class="mnav-raise {{ request()->routeIs('admin.salesorders.*') ? 'active' : '' }}">
            <span class="mnav-ico"><i class="fa-solid fa-cart-shopping"></i></span><span>Orders</span>
        </a>
        <a href="{{ route('admin.franchises.index') }}" class="{{ request()->routeIs('admin.franchises.*') ? 'active' : '' }}">
            <i class="fa-solid fa-store"></i><span>Stores</span>
        </a>
        <a href="{{ url('/hr/modules/attendance') }}" class="{{ $mnavIsHr ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i><span>HR</span>
        </a>
    </div>
</nav>
