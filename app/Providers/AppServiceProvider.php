<?php

namespace App\Providers;

use App\Models\Hr\Department as HrDepartment;
use App\Models\Hr\User as HrUser;
use App\Services\NotificationFeedService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Feeds the bell in the main topbar (layouts/app.blade.php) on every
        // page that uses it, not just the unified dashboard, with the merged
        // HR + sales + order + other notifications.
        View::composer('layouts.app', function ($view) {
            if (Auth::check()) {
                $feed = app(NotificationFeedService::class)->forUser(Auth::user());
                $view->with('navNotifications', $feed['items']);
                $view->with('navUnreadCount', $feed['unreadCount']);
                $view->with('navProfile', $this->buildNavProfile(Auth::user()));
            } else {
                $view->with('navNotifications', collect());
                $view->with('navUnreadCount', 0);
                $view->with('navProfile', null);
            }
        });
    }

    /**
     * Profile details for the topbar user dropdown, so the SPC modules show
     * the same Role / Employee code / Department / Designation as the HR
     * module. Source of truth is the linked HR account (matched by email);
     * falls back to the SPC employee master when there is no HR account.
     */
    private function buildNavProfile($admin): array
    {
        $roleName = (string) optional($admin->roles->first())->name;
        $profile = [
            'role' => \Illuminate\Support\Str::of($roleName ?: 'User')->replace('_', ' ')->title()->toString(),
            'code' => null,
            'department' => null,
            'designation' => null,
        ];

        try {
            $hrUser = HrUser::findForSpcAdmin($admin);
            $hrEmp = $hrUser?->employee()->with(['department', 'designation'])->first();

            if ($hrUser) {
                $profile['role'] = $hrUser->roleLabel();
            }
            if ($hrEmp) {
                $profile['code'] = $hrEmp->employee_code;
                $profile['department'] = $hrEmp->department?->name;
                $profile['designation'] = $hrEmp->designation?->title;
            }
        } catch (\Throwable $e) {
            report($e); // HR database unavailable: fall through to the SPC data below
        }

        // Fallback / gap-filling from the SPC employee master (its columns are c_*)
        $emp = $admin->employee ?? null;
        if ($emp) {
            $profile['code'] ??= $emp->c_employee_code;
            $profile['designation'] ??= optional($emp->designation)->c_designation;
            if ($profile['department'] === null && $emp->department_id) {
                try {
                    $profile['department'] = HrDepartment::find($emp->department_id)?->name;
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        return $profile;
    }
}
