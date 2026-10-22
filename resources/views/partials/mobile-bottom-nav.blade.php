{{--
    Mobile bottom navigation dock (main site).
    Rendered only below 992px — see the .spc-mnav styles in the layouts.
    Items mirror the most-used areas: Home, Leads, Orders, Stores, HR.

    "More" slides the sidebar open as a drawer (full menu) instead of
    toggling the header panel. The open state is the same `show-sidebar`
    class the theme's hamburger uses, so both controls stay in sync.
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
        @unless(auth()->user()?->isAssociate())
        <a href="{{ url('/hr/modules/attendance') }}" class="{{ $mnavIsHr ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i><span>HR</span>
        </a>
        @endunless

        {{-- More: opens the sidebar drawer --}}
        <button type="button" class="mnav-more" data-spc-sidebar-toggle role="button" aria-expanded="false"
            aria-controls="main-wrapper" aria-label="Open full menu">
            <i class="fa-solid fa-ellipsis"></i><span>More</span>
        </button>
    </div>
</nav>

{{-- Backdrop shown while the sidebar drawer is open (tapping it closes) --}}
<div class="spc-sidebar-backdrop" data-spc-sidebar-close hidden></div>

<script>
    (function() {
        var wrapper = document.getElementById('main-wrapper');
        if (!wrapper) return;
        var backdrop = document.querySelector('.spc-sidebar-backdrop');
        var toggles = document.querySelectorAll('[data-spc-sidebar-toggle]');

        function setOpen(open) {
            wrapper.classList.toggle('show-sidebar', open);
            if (backdrop) backdrop.hidden = !open;
            toggles.forEach(function(t) {
                t.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        }

        function isOpen() {
            return wrapper.classList.contains('show-sidebar');
        }

        toggles.forEach(function(t) {
            t.addEventListener('click', function(e) {
                e.preventDefault();
                setOpen(!isOpen());
            });
        });

        if (backdrop) {
            backdrop.addEventListener('click', function() {
                setOpen(false);
            });
        }

        // Close the drawer when a nav link inside it is tapped
        var links = wrapper.querySelectorAll('.sidebar-nav a');
        links.forEach(function(a) {
            a.addEventListener('click', function() {
                setOpen(false);
            });
        });
    })();
</script>
