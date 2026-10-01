{{--
    SPC main-site sidebar, shown on the HR pages.

    Save as:  resources/views/hr/partials/spc-sidebar.blade.php
    Used by:  resources/views/hr/layouts/app.blade.php  (replaces @include('hr.partials.sidebar'))

    - Menu items come from the same `menus` table and role rules as the SPC
      main site (MenuController::getMenus()), so both sites show the same menu.
    - If the "HR" group already exists in that table (HrMenuSeeder / Create Menu),
      it is shown as part of the SPC menu. If not, the HR module links are added
      automatically so you can always move between HR pages.
    - If you are not signed in to SPC in this browser, only the HR links show.
--}}
@php
    $spcUser  = auth()->user();
    $spcMenus = collect();

    if ($spcUser) {
        try {
            $spcMenus = \App\Http\Controllers\Admin\MenuController::getMenus();
        } catch (\Throwable $e) {
            $spcMenus = collect();
        }
    }

    $isHrRoute = fn ($r) => is_string($r) && str_starts_with($r, 'hr.');

    $spcHasHrGroup = $spcMenus->contains(function ($p) use ($isHrRoute) {
        return $isHrRoute($p->route_name)
            || $p->children->contains(fn ($c) => $isHrRoute($c->route_name));
    });

    $spcUrl = function ($r) {
        return ($r && \Illuminate\Support\Facades\Route::has($r)) ? route($r) : '#';
    };

    // Active when the route matches exactly, or (for names like admin.sales.index)
    // when any route in the same section is current, e.g. admin.sales.*
    $spcActive = function ($r) {
        if (! $r) {
            return false;
        }
        if (request()->routeIs($r)) {
            return true;
        }

        return substr_count($r, '.') >= 2
            && request()->routeIs(\Illuminate\Support\Str::beforeLast($r, '.').'.*');
    };

    // Fallback HR links (only used when the DB menu has no HR group)
    $hrLucide = [
        'attendance' => 'clock', 'leave' => 'calendar-days', 'payroll' => 'wallet',
        'recruitment' => 'user-plus', 'employee-records' => 'users', 'document-verification' => 'file-check', 'appraisal' => 'trophy',
        'pf-gratuity' => 'piggy-bank', 'incentive' => 'medal', 'reports' => 'bar-chart-3',
        'system' => 'shield', 'wfh' => 'home', 'announcements' => 'megaphone',
        'support' => 'help-circle', 'settings' => 'settings', 'organization' => 'building-2',
    ];
    $hrGroupOrder = ['People', 'Workforce', 'Money', 'Growth', 'Records', 'Comms', 'System'];
    $hrGrouped = [];
    foreach (($modules ?? []) as $key => $module) {
        if ($key === 'my-profile') {
            continue;
        }
        $hrGrouped[$module['group'] ?? 'Other'][$key] = $module;
    }
@endphp

<aside class="sidebar spc-side">
    <div class="spc-side-inner">

        <div class="spc-brand">
            <a href="{{ \Illuminate\Support\Facades\Route::has('dashboard') ? route('dashboard') : url('/') }}">
                <img src="{{ asset('dist/images/logos/spclogo.png') }}" alt="SPC">
            </a>
            <button type="button" class="sidebar-close" onclick="document.getElementById('appShell').classList.remove('sidebar-open')" aria-label="Close menu">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <nav class="spc-nav">

            {{-- ===== SPC main-site menu (same data as the main sidebar) ===== --}}
            @foreach($spcMenus as $parent)
                @if($parent->children->isEmpty())
                    <a class="spc-link {{ $spcActive($parent->route_name) ? 'active' : '' }}" href="{{ $spcUrl($parent->route_name) }}">
                        <i data-lucide="{{ $parent->icon }}"></i>
                        <span>{{ $parent->name }}</span>
                    </a>
                @else
                    <p class="spc-cap">{{ $parent->name }}</p>
                    @foreach($parent->children as $child)
                        <a class="spc-link {{ $spcActive($child->route_name) ? 'active' : '' }}" href="{{ $spcUrl($child->route_name) }}">
                            <i data-lucide="{{ $child->icon }}"></i>
                            <span>{{ $child->name }}</span>
                        </a>
                    @endforeach
                @endif
            @endforeach

            {{-- ===== HR links, only if the HR group is not already in the DB menu ===== --}}
            @unless($spcHasHrGroup)
                <p class="spc-cap">HR</p>
                <a class="spc-link {{ request()->routeIs('hr.profile.index') ? 'active' : '' }}" href="{{ route('hr.profile.index') }}">
                    <i data-lucide="user"></i><span>My Profile</span>
                </a>

                @foreach($hrGroupOrder as $groupName)
                    @continue(empty($hrGrouped[$groupName]))
                    <p class="spc-cap">{{ $groupName }}</p>
                    @foreach($hrGrouped[$groupName] as $key => $module)
                        <a class="spc-link {{ (($moduleKey ?? null) === $key) ? 'active' : '' }}" href="{{ url('/hr/modules/'.$key) }}">
                            <i data-lucide="{{ $hrLucide[$key] ?? 'layout-grid' }}"></i>
                            <span>{{ $module['nav_label'] ?? $module['title'] }}</span>
                            @if(!empty($navBadges[$key] ?? null))
                                <span class="spc-badge">{{ $navBadges[$key] }}</span>
                            @endif
                        </a>
                    @endforeach
                @endforeach
            @endunless

        </nav>
    </div>
</aside>

<style>
    /* Restyle the HR shell's sidebar — "Evergreen mint" creative look.
       Soft mint wash (45deg #CBFFCD) over white, green accents everywhere. */
    .sidebar.spc-side{
        --spc-primary:#5E8D3D; --spc-primary-strong:#1F5C2E; --spc-primary-soft:#E4F3EB;
        --spc-text:#3D5247; --spc-line:rgba(18,58,40,.1);
        background:
            linear-gradient(45deg, #CBFFCD, transparent 62%),
            radial-gradient(420px 320px at -20% 108%, rgba(94,141,61,.14), transparent 55%),
            linear-gradient(175deg, #FFFFFF 0%, #F4FBF6 60%, #ECF7F0 100%);
        color:var(--spc-text);
        border-right:1px solid var(--spc-line); box-shadow:none;
    }
    .sidebar.spc-side::after{display:none;}
    .spc-side-inner{display:flex; flex-direction:column; min-height:100%;}

    .spc-brand{position:relative; display:flex; align-items:center; justify-content:center; padding:22px 20px 12px;}
    .spc-brand img{height:54px; max-width:180px; width:auto; object-fit:contain; display:block;}
    .sidebar.spc-side .sidebar-close{
        background:rgba(255,255,255,.65); border:1px solid rgba(18,58,40,.14); color:var(--spc-primary-strong);
        position:absolute; right:12px; top:50%; transform:translateY(-50%);
    }

    .spc-nav{padding:4px 16px 28px; display:flex; flex-direction:column; gap:2px;}
    .spc-cap{
        margin:22px 8px 8px; display:flex; align-items:center; gap:8px;
        font-family:var(--font-body); font-size:10px; font-weight:700;
        letter-spacing:.14em; text-transform:uppercase; color:#3E8A66;
    }
    .spc-cap::after{content:""; flex:1; height:1px; background:linear-gradient(90deg, rgba(94,141,61,.35), transparent);}
    .spc-link{
        display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px;
        font-family:var(--font-body); font-size:13.5px; font-weight:500; line-height:1.3;
        color:var(--spc-text); text-decoration:none; transition:background .15s, color .15s, box-shadow .15s;
        position:relative;
    }
    .spc-link svg{width:19px; height:19px; flex-shrink:0; stroke-width:1.75; transition:transform .16s;}
    .spc-link span:not(.spc-badge){min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;}
    .spc-link:hover{background:rgba(94,141,61,.1); color:var(--spc-primary-strong);}
    .spc-link:hover svg{transform:translateY(-1px) scale(1.06);}
    .spc-link.active{
        background:linear-gradient(135deg,#5E8D3D,#1F5C2E); color:#fff; font-weight:600;
        box-shadow:0 10px 20px -8px rgba(14,107,75,.55), inset 0 1.5px 0 rgba(255,255,255,.25);
    }
    .spc-link.active svg{stroke:#fff;}
    .spc-link.active::before{
        content:""; position:absolute; left:-16px; top:22%; bottom:22%; width:4px;
        border-radius:0 4px 4px 0; background:#17A673; box-shadow:0 0 12px rgba(23,166,115,.8);
    }
    .spc-badge{
        margin-left:auto; background:#FA896B; color:#fff; font-size:10px; font-weight:700;
        line-height:1; padding:4px 8px; border-radius:99px;
    }
    .spc-link.active .spc-badge{background:#fff; color:#C2410C; box-shadow:0 3px 8px rgba(0,0,0,.15);}
</style>