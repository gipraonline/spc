<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('hr.partials.topbar', ['title' => $roleData['label'] . ' dashboard', 'eyebrow' => 'HR Management Module'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">
        <?php
            $hour = (int) now()->format('G');
            $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
            $greetIcon = $hour < 12 ? 'fa-solid fa-mug-hot' : ($hour < 17 ? 'fa-solid fa-sun' : 'fa-solid fa-moon');
            // Icon per KPI slot, per role — matches the order of kpisFor() in DashboardController.
            $kpiIcons = match($role) {
                'employee'    => ['fa-regular fa-calendar-check', 'fa-solid fa-plane-departure', 'fa-solid fa-money-check-dollar', 'fa-solid fa-star'],
                'manager'     => ['fa-solid fa-user-check', 'fa-solid fa-hourglass-half', 'fa-solid fa-clipboard-list', 'fa-solid fa-coins'],
                'hr_admin'    => ['fa-solid fa-users', 'fa-solid fa-sack-dollar', 'fa-solid fa-briefcase', 'fa-solid fa-chart-line'],
                default       => ['fa-solid fa-chart-pie', 'fa-solid fa-arrow-trend-up', 'fa-solid fa-indian-rupee-sign', 'fa-solid fa-shield-halved'],
            };
        ?>
        <div class="dash-hero">
            <div class="dash-hero-text">
                <?php $in = $todayAttendance?->check_in; $out = $todayAttendance?->check_out; ?>
                <h2><?php echo e($greeting); ?>, <?php echo e(explode(' ', $authUser->name)[0]); ?> <i class="<?php echo e($greetIcon); ?>" style="font-size:19px;color:#5FE0B2;"></i></h2>
                <p><?php echo e($roleData['tagline']); ?>. Here's what's happening across HR today.</p>
                <?php if($employee && in_array($role, ['employee','manager','hr_admin'])): ?>
                    <div class="dash-hero-check">
                        <?php if($in && $out): ?>
                            <span class="ci-done"><i class="fa-solid fa-circle-check"></i>Checked in <?php echo e(\Illuminate\Support\Carbon::parse($in)->format('h:i A')); ?> &middot; Out <?php echo e(\Illuminate\Support\Carbon::parse($out)->format('h:i A')); ?></span>
                        <?php else: ?>
                            <form method="POST" action="<?php echo e($out ? route('hr.attendance.check-out') : route('hr.attendance.check-in')); ?>" style="display:inline;"><?php echo csrf_field(); ?>
                                <button type="submit" class="ci-btn <?php echo e($in ? 'out' : ''); ?>">
                                    <i class="fa-solid <?php echo e($in ? 'fa-right-from-bracket' : 'fa-fingerprint'); ?>"></i>
                                    <?php echo e($in ? 'Check out' : 'Check in'); ?>

                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if($employee && in_array($role, ['employee','manager','hr_admin'])): ?>
            <div class="ci-box">
                <div class="ci-state"><span class="ci-pulse"></span><?php echo e($in && !$out ? 'On the clock' : ($in && $out ? 'Day complete' : 'Not checked in')); ?></div>
                <div class="ci-clock" id="liveClock"><?php echo e(now()->format('h:i A')); ?></div>
                <div class="ci-sub">
                    <?php if($in): ?>
                        In <?php echo e(\Illuminate\Support\Carbon::parse($in)->format('h:i A')); ?><?php if($out): ?> &middot; Out <?php echo e(\Illuminate\Support\Carbon::parse($out)->format('h:i A')); ?><?php endif; ?>
                    <?php else: ?>
                        Shift 09:00 &ndash; 18:00
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="dash-hero-date">
                <b><?php echo e(now()->format('l, d M Y')); ?></b>
                <span><i class="fa-regular fa-clock"></i><span id="liveClock2"><?php echo e(now()->format('h:i A')); ?></span></span>
            </div>
        </div>

        <?php if($tickerAnnouncements->isNotEmpty()): ?>
        <div class="ticker">
            <span class="ticker-label"><i class="fa-solid fa-bullhorn"></i><span>Notices</span></span>
            <div class="ticker-items">
                <?php $__currentLoopData = $tickerAnnouncements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="ticker-item"><i class="fa-solid fa-circle" style="font-size:5px;"></i><b><?php echo e($ta->title); ?></b><span class="t-date"><?php echo e(\Illuminate\Support\Carbon::parse($ta->published_at ?? $ta->created_at)->format('d M')); ?></span></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <a href="<?php echo e(route('hr.announcements.index')); ?>" class="ticker-link">All notices <i class="fa-solid fa-chevron-right"></i></a>
        </div>
        <?php endif; ?>

        <div class="kpi-row">
            <?php $__currentLoopData = $kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="kpi-card">
                    <div class="kpi-top">
                        <div class="kpi-ico"><i class="<?php echo e($kpiIcons[$loop->index] ?? 'fa-solid fa-chart-simple'); ?>"></i></div>
                    </div>
                    <div>
                        <div class="kpi-label"><?php echo e($kpi['label']); ?></div>
                        <div class="kpi-val"><?php echo e($kpi['value']); ?></div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="section-head">
            <h2><i class="fa-solid fa-bolt"></i>Quick actions</h2>
            <span class="hint">Jump straight into the things you do most</span>
        </div>
        <div class="quick-actions">
            <?php $__currentLoopData = $quickActions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($qa['url']); ?>" class="qa-btn"><i class="fa-solid fa-arrow-up-right-from-square"></i><?php echo e($qa['label']); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="grid-2" style="margin-top:30px;">
            <div class="card">
                <?php if($role !== 'employee'): ?>
                    <div class="card-head">
                        <h3><i class="fa-solid fa-clipboard-check"></i>Pending approvals</h3>
                        <span class="pill pill-warn"><?php echo e($pendingApprovals->count()); ?> pending</span>
                    </div>
                    <div style="overflow-x:auto;">
                        <table>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $pendingApprovals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="cell-emp">
                                        <div class="av"><?php echo e($p['initials']); ?></div>
                                        <div><b><?php echo e($p['employee']); ?></b><span><?php echo e($p['detail']); ?></span></div>
                                    </td>
                                    <td class="row-actions">
                                        <form method="POST" action="<?php echo e($p['route']); ?>"><?php echo csrf_field(); ?><input type="hidden" name="action" value="approve"><button class="approve" type="submit">Approve</button></form>
                                        <form method="POST" action="<?php echo e($p['route']); ?>"><?php echo csrf_field(); ?><input type="hidden" name="action" value="reject"><button class="reject" type="submit">Reject</button></form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td style="text-align:center;color:var(--text-muted);padding:36px 12px;">
                                    <div class="empty-state" style="padding:0;"><i class="fa-solid fa-circle-check glyph"></i>All caught up — nothing pending.</div>
                                </td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="card-head"><h3><i class="fa-regular fa-paper-plane"></i>My requests</h3><span class="pill pill-muted">Latest first</span></div>
                    <div style="overflow-x:auto;">
                        <table>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $myRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><b><?php echo e($r['type']); ?></b><div class="card-note" style="margin:2px 0 0;"><?php echo e($r['detail']); ?></div></td>
                                    <td style="text-align:right;"><span class="pill <?php echo e($r['pill']); ?>"><?php echo e($r['status']); ?></span></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td style="text-align:center;color:var(--text-muted);padding:36px 12px;">
                                    <div class="empty-state" style="padding:0;"><i class="fa-regular fa-calendar glyph"></i>No leave, WFH, or attendance requests yet.</div>
                                </td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <div class="stack">
                <?php if($deptDistribution): ?>
                    <div class="card card-pad">
                        <h3 style="margin:0 0 12px;font-size:13.5px;"><i class="fa-solid fa-chart-pie" style="color:var(--brand);margin-right:8px;"></i>Employee distribution</h3>
                        <?php $__currentLoopData = $deptDistribution['departments']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bar-row">
                                <span class="bar-label"><?php echo e($d->name); ?></span>
                                <div class="bar-track"><div class="bar-fill" style="width:<?php echo e(round($d->employees_count / $deptDistribution['max'] * 100)); ?>%;"></div></div>
                                <span class="bar-value"><?php echo e($d->employees_count); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <?php if($upcomingHoliday): ?>
                    <div class="card card-pad" style="background:linear-gradient(135deg,var(--brand-softer),#fff);border-color:rgba(31,169,122,.3);">
                        <h3 style="margin:0 0 6px;font-size:13.5px;"><i class="fa-solid fa-umbrella-beach" style="color:var(--brand);margin-right:8px;"></i>Next holiday</h3>
                        <div style="font-family:var(--font-head);font-size:18px;font-weight:600;color:var(--brand-ink);"><?php echo e($upcomingHoliday->name); ?></div>
                        <p class="card-note" style="margin:4px 0 0;">
                            <?php echo e(\Illuminate\Support\Carbon::parse($upcomingHoliday->holiday_date)->format('d M Y (D)')); ?>

                            &middot; <?php echo e($upcomingHoliday->is_optional ? 'Optional' : 'Mandatory'); ?>

                            &middot; in <?php echo e(now()->startOfDay()->diffInDays($upcomingHoliday->holiday_date)); ?> days
                        </p>
                    </div>
                <?php endif; ?>

                <?php if($upcomingBirthdays->isNotEmpty()): ?>
                    <div class="card card-pad">
                        <h3 style="margin:0 0 10px;font-size:13.5px;"><i class="fa-solid fa-cake-candles" style="color:var(--brand);margin-right:8px;"></i>Upcoming birthdays</h3>
                        <?php $__currentLoopData = $upcomingBirthdays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div style="display:flex;justify-content:space-between;align-items:center;font-size:12.5px;padding:7px 0;border-bottom:1px solid var(--line-soft);">
                                <span class="cell-emp"><span class="av" style="width:25px;height:25px;font-size:10px;"><?php echo e(strtoupper(substr($b->user->name,0,1))); ?></span><?php echo e($b->user->name); ?></span>
                                <span style="color:var(--brand);font-weight:600;"><i class="fa-solid fa-gift" style="font-size:10px;margin-right:5px;"></i><?php echo e($b->next_birthday->format('d M')); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <?php if($recentActivity->isNotEmpty()): ?>
                    <div class="card card-pad">
                        <h3 style="margin:0 0 10px;font-size:13.5px;"><i class="fa-solid fa-wave-square" style="color:var(--brand);margin-right:8px;"></i>Recent activity</h3>
                        <?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div style="font-size:12.5px;padding:7px 0;border-bottom:1px solid var(--line-soft);">
                                <strong><?php echo e($log->user->name ?? 'System'); ?></strong> &middot; <?php echo e(ucfirst(strtolower($log->action))); ?> on <?php echo e(str_replace('_',' ',$log->module)); ?>

                                <div style="color:var(--text-muted);"><?php echo e(\Illuminate\Support\Carbon::parse($log->created_at)->diffForHumans()); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <p class="access-note">Use the sidebar to switch modules, or sign out from your profile menu to sign in as someone else.</p>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/dashboard.blade.php ENDPATH**/ ?>