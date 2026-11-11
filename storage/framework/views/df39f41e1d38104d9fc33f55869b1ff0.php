<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ---- Tabs ---- */
.ctabs {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin: 0 0 18px;
    border-bottom: 1px solid var(--line);
}

.ctab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 16px;
    margin-bottom: -1px;
    border: 0;
    border-bottom: 2px solid transparent;
    background: transparent;
    color: var(--text-muted);
    font-family: var(--font-body);
    font-size: 13.5px;
    font-weight: 500;
    cursor: pointer;
    white-space: nowrap;
    transition: color .15s, border-color .15s, background .15s;
}

.ctab i {
    font-size: 13px;
}

.ctab:hover {
    color: var(--brand-ink);
    background: var(--brand-softer);
}

.ctab:focus-visible {
    outline: 2px solid var(--brand-bright);
    outline-offset: -2px;
}

.ctab.is-active {
    color: var(--brand-ink);
    border-bottom-color: var(--brand);
    font-weight: 600;
}

.ctab .ctab-count {
    min-width: 20px;
    padding: 1px 7px;
    border-radius: 999px;
    background: var(--brand-softer);
    color: var(--brand-ink);
    font-size: 11.5px;
    font-weight: 600;
    text-align: center;
}

.cpanel[hidden] {
    display: none;
}

@media (max-width: 640px) {
    .ctabs {
        flex-wrap: nowrap;
        overflow-x: auto;
    }
}
</style>
    <?php
        $canPublish = $role === 'hr_admin' || $role === 'super_admin';

        // Tabs this person can see: key => [label, icon, count badge]
        $tabs = [
            'latest' => ['Latest announcements', 'fa-solid fa-stream', $unreadTotal > 0 ? $unreadTotal : null],
        ];
        if ($canPublish) {
            $tabs['publish'] = ['Publish', 'fa-solid fa-pen-nib', null];
        }

        // Open ?tab= if given; reopen Publish if the form came back with errors.
        $requestedTab = request()->query('tab');
        if (is_string($requestedTab) && isset($tabs[$requestedTab])) {
            $activeTab = $requestedTab;
        } elseif ($canPublish && $errors->any()) {
            $activeTab = 'publish';
        } else {
            $activeTab = 'latest';
        }
    ?>

    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Comms',
        'heroIcon' => 'fa-solid fa-bullhorn',
        'heroSummary' => 'Company-wide and role-targeted announcements, newest first.',
        'heroStats' => $canPublish ? [
            ['label' => 'Published', 'icon' => 'fa-solid fa-paper-plane', 'value' => $totalPublished],
            ['label' => 'Unread', 'icon' => 'fa-regular fa-envelope-open', 'value' => $unreadTotal],
        ] : [
            ['label' => 'Latest', 'icon' => 'fa-regular fa-envelope-open', 'value' => $unreadTotal . ' new'],
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">
        
        <?php if(count($tabs) > 1): ?>
            <div class="ctabs" role="tablist" aria-label="Announcement sections">
                <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$tabLabel, $tabIcon, $tabCount]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" class="ctab <?php echo e($key === $activeTab ? 'is-active' : ''); ?>"
                        role="tab" id="ctab-<?php echo e($key); ?>" data-tab="<?php echo e($key); ?>"
                        aria-controls="cpanel-<?php echo e($key); ?>" aria-selected="<?php echo e($key === $activeTab ? 'true' : 'false'); ?>"
                        tabindex="<?php echo e($key === $activeTab ? '0' : '-1'); ?>">
                        <i class="<?php echo e($tabIcon); ?>"></i><?php echo e($tabLabel); ?>

                        <?php if($tabCount !== null): ?><span class="ctab-count"><?php echo e($tabCount); ?></span><?php endif; ?>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        
        <div class="cpanel" id="cpanel-latest" data-panel="latest" role="tabpanel" aria-labelledby="ctab-latest" <?php if($activeTab !== 'latest'): ?> hidden <?php endif; ?>>
            <?php if($announcements->isEmpty()): ?>
                <div class="table-card"><div class="tc-body">
                    <div class="empty-widget">
                        <div class="ew-ico"><i class="fa-solid fa-bullhorn"></i></div>
                        <b>No announcements yet</b>
                        <span>Company news will appear here as soon as it's published.</span>
                    </div>
                </div></div>
            <?php endif; ?>
            <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card" style="margin-bottom:14px;">
                    <div class="widget-head" style="margin-bottom:10px;">
                        <div class="wh-ico"><i class="fa-solid fa-comment-dots"></i></div>
                        <div>
                            <h3><?php echo e($a->title); ?>

                                <?php if (! (in_array($a->id, $readIds))): ?>
                                    <span class="pill pill-warn" style="margin-left:8px;vertical-align:middle;">New</span>
                                <?php endif; ?>
                            </h3>
                            <p><?php echo e($a->createdBy->name ?? 'HR'); ?> &middot; <?php echo e(\Illuminate\Support\Carbon::parse($a->published_at)->format('d M Y, H:i')); ?>

                                <?php if($a->audience_role !== 'all'): ?> &middot; <i class="fa-solid fa-user-group" style="font-size:9px;"></i> <?php echo e(ucfirst(str_replace('_',' ',$a->audience_role))); ?> only <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <p style="font-size:13.5px;line-height:1.65;margin:0;"><?php echo e($a->body); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php echo e($announcements->links()); ?>

        </div>

        
        <?php if($canPublish): ?>
            <div class="cpanel" id="cpanel-publish" data-panel="publish" role="tabpanel" aria-labelledby="ctab-publish" <?php if($activeTab !== 'publish'): ?> hidden <?php endif; ?>>
                <div class="card">
                    <div class="widget-head">
                        <div class="wh-ico"><i class="fa-solid fa-pen-nib"></i></div>
                        <div>
                            <h3>Publish an announcement</h3>
                            <p>Delivered instantly to the notification bell of the chosen audience.</p>
                        </div>
                    </div>
                    <form method="POST" action="<?php echo e(route('hr.announcements.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="field-grid">
                            <div class="field full">
                                <label>Title</label>
                                <input id="annTitle" name="title" value="<?php echo e(old('title')); ?>"
                                    data-default-placeholder="e.g. Office closed for Onam"
                                    placeholder="e.g. Office closed for Onam">
                                <span class="field-hint">Optional. If left blank, the first line of the message is used as the title.</span>
                                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="field full">
                                <label>Message</label>
                                <textarea id="annBody" name="body" placeholder="Announcement details" required><?php echo e(old('body')); ?></textarea>
                                <?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="field">
                                <label>Audience</label>
                                <select name="audience_role">
                                    <option value="all">Everyone</option>
                                    <option value="employee">Employees</option>
                                    <option value="manager">Reporting Managers</option>
                                    <option value="hr_admin">HR Admins</option>
                                    <option value="super_admin">Super Admins</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions"><button type="submit" class="btn-primary">Publish announcement</button></div>
                    </form>

                    <script>
                    (function () {
                        var title = document.getElementById('annTitle');
                        var body = document.getElementById('annBody');
                        if (!title || !body) return;

                        // Show the title that will be used (from the message) as a live preview in the empty title box.
                        function preview() {
                            var first = (body.value.trim().split(/\r?\n/)[0] || '').trim();
                            if (first.length > 60) first = first.slice(0, 60).trimEnd() + '...';
                            title.placeholder = first || title.dataset.defaultPlaceholder;
                        }

                        body.addEventListener('input', preview);
                        preview();
                    })();
                    </script>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
    (function () {
        var tabs = Array.prototype.slice.call(document.querySelectorAll('.ctab'));
        var panels = Array.prototype.slice.call(document.querySelectorAll('.cpanel'));
        if (!tabs.length) return;

        function show(key, updateUrl) {
            tabs.forEach(function (t) {
                var on = t.getAttribute('data-tab') === key;
                t.classList.toggle('is-active', on);
                t.setAttribute('aria-selected', on ? 'true' : 'false');
                t.tabIndex = on ? 0 : -1;
            });
            panels.forEach(function (p) {
                p.hidden = p.getAttribute('data-panel') !== key;
            });
            if (updateUrl && window.history && window.history.replaceState) {
                var url = new URL(window.location.href);
                url.searchParams.set('tab', key);
                window.history.replaceState(null, '', url);
            }
        }

        tabs.forEach(function (t, i) {
            t.addEventListener('click', function () { show(t.getAttribute('data-tab'), true); });
            t.addEventListener('keydown', function (e) {
                var next = null;
                if (e.key === 'ArrowRight') next = tabs[(i + 1) % tabs.length];
                if (e.key === 'ArrowLeft') next = tabs[(i - 1 + tabs.length) % tabs.length];
                if (next) { e.preventDefault(); next.focus(); show(next.getAttribute('data-tab'), true); }
            });
        });
    })();
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/announcements.blade.php ENDPATH**/ ?>