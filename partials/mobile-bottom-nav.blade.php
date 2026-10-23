{{--
    Mobile bottom navigation dock (main site).
    Rendered only below 992px — see the .spc-mnav styles in the layouts.
    Items mirror the most-used areas: Home, Leads, Orders, Stores, HR.

    "More" is a button (not a link) that slides the sidebar open — the
    sidebar is a real <aside>, so we toggle the layout's show-sidebar class
    and dim the page behind it with a backdrop that closes it on tap.
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

        {{-- More: slides the sidebar open (full menu) --}}
        <button type="button" class="mnav-more" data-spc-sidebar-toggle aria-label="Open menu" aria-expanded="false">
            <i class="fa-solid fa-ellipsis"></i><span>More</span>
        </button>
    </div>
</nav>

{{-- Backdrop shown while the sidebar is open --}}
<div class="spc-sidebar-backdrop" data-spc-sidebar-close hidden></div>

<script>
    (function () {
        var wrapper = document.getElementById('main-wrapper');
        if (!wrapper) return;
        var backdrop = document.querySelector('.spc-sidebar-backdrop');

        function openSidebar() {
            wrapper.classList.add('show-sidebar');
            if (backdrop) backdrop.hidden = false;
            document.querySelectorAll('[data-spc-sidebar-toggle]')
                .forEach(function (b) { b.setAttribute('aria-expanded', 'true'); });
        }

        function closeSidebar() {
            wrapper.classList.remove('show-sidebar');
            if (backdrop) backdrop.hidden = true;
            document.querySelectorAll('[data-spc-sidebar-toggle]')
                .forEach(function (b) { b.setAttribute('aria-expanded', 'false'); });
        }

        document.addEventListener('click', function (e) {
            var open = e.target.closest('[data-spc-sidebar-toggle]');
            if (open) {
                e.preventDefault();
                wrapper.classList.contains('show-sidebar') ? closeSidebar() : openSidebar();
                return;
            }
            if (e.target.closest('[data-spc-sidebar-close]') || e.target.closest('.sidebartoggler')) {
                closeSidebar();
            }
        });
    })();
</script>
