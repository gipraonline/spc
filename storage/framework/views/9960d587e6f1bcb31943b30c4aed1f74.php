        <?php if($viewed): ?>
        <div class="card">
            <h3><?php echo e($viewed->user->name); ?>

                <span
                    class="pill <?php echo e($viewed->employment_status === 'active' ? 'pill-ok' : ($viewed->employment_status === 'on_notice' ? 'pill-warn' : 'pill-bad')); ?>"
                    style="margin-left:8px;">
                    <?php echo e(ucfirst(str_replace('_',' ',$viewed->employment_status))); ?>

                </span>
                <span class="pill pill-muted"><?php echo e($viewed->user->roleLabel()); ?></span>
            </h3>
            <p class="card-note"><?php echo e($viewed->employee_code); ?> &middot; <?php echo e($viewed->designation->title ?? '—'); ?> &middot;
                <?php echo e($viewed->department->name ?? '—'); ?> &middot; <?php echo e($viewed->city); ?></p>

            <div class="tabs">
                <button type="button" class="tab active" data-tab="personal"
                    onclick="hrTab(this,'personal')">Personal</button>
                <button type="button" class="tab" data-tab="employment"
                    onclick="hrTab(this,'employment')">Employment</button>
                <button type="button" class="tab" data-tab="bank" onclick="hrTab(this,'bank')">Bank & statutory</button>
                <button type="button" class="tab" data-tab="documents"
                    onclick="hrTab(this,'documents')">Documents</button>
                <?php $isSelf = $employee && $employee->id === $viewed->id; ?>
                <?php if(($role === 'hr_admin' || $role === 'super_admin') || $isSelf): ?>
                <button type="button" class="tab" data-tab="security" onclick="hrTab(this,'security')">Security</button>
<<<<<<< Updated upstream
=======
                <button type="button" class="tab" data-tab="secondary-contact"
                    onclick="hrTab(this,'secondary-contact')">Secondary Contact</button>
>>>>>>> Stashed changes
                <?php endif; ?>
                <?php if(($role === 'hr_admin' || $role === 'super_admin') && $viewed->history &&
                $viewed->history->isNotEmpty()): ?>
                <button type="button" class="tab" data-tab="history" onclick="hrTab(this,'history')">History</button>
                <?php endif; ?>
            </div>

            <?php
            $canEdit = ($role === 'hr_admin' || $role === 'super_admin') || ($employee && $employee->id ===
            $viewed->id);
            $isHr = ($role === 'hr_admin' || $role === 'super_admin');
            ?>

            <form method="POST" action="<?php echo e(route('hr.records.update', $viewed)); ?>">
                <?php echo csrf_field(); ?>
                <div class="tabpanel active" data-tabpanel="personal">
                    <div class="field-grid">
                        <div class="field"><label>Phone</label><input name="phone" value="<?php echo e($viewed->phone); ?>"
                                <?php if(!$canEdit): echo 'disabled'; endif; ?>></div>
                        <div class="field"><label>Personal email</label><input name="personal_email"
                                value="<?php echo e($viewed->personal_email); ?>" <?php if(!$canEdit): echo 'disabled'; endif; ?>></div>
                        <div class="field full"><label>Address</label><input name="address"
                                value="<?php echo e($viewed->address); ?>" <?php if(!$canEdit): echo 'disabled'; endif; ?>></div>
                        <div class="field"><label>City</label><input name="city" value="<?php echo e($viewed->city); ?>"
                                <?php if(!$canEdit): echo 'disabled'; endif; ?>></div>
<<<<<<< Updated upstream
=======
                        <div class="field"><label>Date of birth</label><input type="date" name="date_of_birth"
                                value="<?php echo e($viewed->date_of_birth ? \Illuminate\Support\Carbon::parse($viewed->date_of_birth)->format('Y-m-d') : ''); ?>"
                                <?php if(!$canEdit): echo 'disabled'; endif; ?>></div>
>>>>>>> Stashed changes
                        <div class="field"><label>Date of joining</label><input value="<?php echo e($viewed->date_of_joining); ?>"
                                disabled></div>
                    </div>
                </div>

                <div class="tabpanel" data-tabpanel="employment">
                    <div class="field-grid">
                        <?php if($isHr): ?>
                        <div class="field">
                            <label>Designation</label>
                            <select name="designation_id">
                                <option value="">—</option>
                                <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($d->id); ?>" <?php if($viewed->designation_id ==
                                    $d->id): echo 'selected'; endif; ?>><?php echo e($d->title); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="field">
                            <label>Department</label>
                            <select name="department_id">
                                <option value="">—</option>
                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($d->id); ?>" <?php if($viewed->department_id == $d->id): echo 'selected'; endif; ?>><?php echo e($d->name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="field">
                            <label>Reporting manager</label>
                            <select name="reporting_manager_id">
                                <option value="">— None —</option>
                                <?php $__currentLoopData = $possibleManagers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($m->id === $viewed->id) continue; ?>
                                <option value="<?php echo e($m->id); ?>" <?php if($viewed->reporting_manager_id ==
                                    $m->id): echo 'selected'; endif; ?>><?php echo e($m->user->name); ?> (<?php echo e($m->user->roleLabel()); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="field">
                            <label>Employment status</label>
                            <select name="employment_status">
                                <option value="active" <?php if($viewed->employment_status === 'active'): echo 'selected'; endif; ?>>Active
                                </option>
                                <option value="on_notice" <?php if($viewed->employment_status === 'on_notice'): echo 'selected'; endif; ?>>On
                                    notice</option>
                                <option value="exited" <?php if($viewed->employment_status === 'exited'): echo 'selected'; endif; ?>>Exited
                                </option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Portal role</label>
                            <select name="portal_role">
                                <option value="employee" <?php if($viewed->user->role === 'employee'): echo 'selected'; endif; ?>>Employee</option>
                                <option value="manager" <?php if($viewed->user->role === 'manager'): echo 'selected'; endif; ?>>Reporting Manager
                                </option>
                                <?php if(in_array($viewed->user->role, ['hr_admin','super_admin'])): ?>
                                <option value="<?php echo e($viewed->user->role); ?>" selected disabled>
                                    <?php echo e($viewed->user->roleLabel()); ?> (change via System & Access)</option>
                                <?php endif; ?>
                            </select>
                            <p class="field-hint">Promotions to HR Admin / Super Admin are made from System & Access.
                            </p>
                        </div>
                        <?php else: ?>
                        <div class="field"><label>Designation</label><input
                                value="<?php echo e($viewed->designation->title ?? '—'); ?>" disabled></div>
                        <div class="field"><label>Department</label><input
                                value="<?php echo e($viewed->department->name ?? '—'); ?>" disabled></div>
                        <div class="field"><label>Reporting manager</label><input
                                value="<?php echo e($viewed->reportingManager->user->name ?? '—'); ?>" disabled></div>
                        <div class="field"><label>Employment status</label><input
                                value="<?php echo e(ucfirst(str_replace('_',' ',$viewed->employment_status))); ?>" disabled></div>
                        <?php endif; ?>
                    </div>
                    <?php if(!$isHr): ?>
                    <p class="field-hint" style="margin-top:14px;">Designation, department and manager are HR-controlled
                        and not editable from this form.</p>
                    <?php endif; ?>
                </div>

                <div class="tabpanel" data-tabpanel="bank">
                    <div class="field-grid">
                        <div class="field"><label>Bank name</label><input name="bank_name"
                                value="<?php echo e($viewed->bank_name); ?>" <?php if(!$canEdit): echo 'disabled'; endif; ?>></div>
                        <div class="field"><label>Account number</label><input name="bank_account_number"
                                value="<?php echo e($viewed->bank_account_number); ?>" <?php if(!$canEdit): echo 'disabled'; endif; ?>></div>
                        <div class="field"><label>IFSC</label><input name="bank_ifsc" value="<?php echo e($viewed->bank_ifsc); ?>"
                                <?php if(!$canEdit): echo 'disabled'; endif; ?>></div>
                    </div>
                </div>

                <?php if($canEdit): ?>
                <div class="form-actions" id="saveChangesActions">
                    <button type="submit" class="btn-primary">Save changes</button>
                </div>
                <?php endif; ?>
            </form>

            <div class="tabpanel" data-tabpanel="documents">
                <?php if(($viewed->documents ?? collect())->isEmpty()): ?>
                <p class="field-hint">No documents on file.</p>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Uploaded</th>
                            <th>Expiry</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $viewed->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $expiringSoon = $doc->expiry_date &&
                        \Illuminate\Support\Carbon::parse($doc->expiry_date)->lte(now()->addDays(30));
                        $docPill = ['verified' => 'pill-ok', 'pending' => 'pill-warn', 'rejected' =>
                        'pill-bad'][$doc->status] ?? 'pill-muted';
                        ?>
                        <tr>
                            <td><?php echo e($doc->document_type); ?></td>
                            <td><?php echo e(\Illuminate\Support\Carbon::parse($doc->uploaded_at)->format('d M Y')); ?></td>
                            <td>
                                <?php echo e($doc->expiry_date ? \Illuminate\Support\Carbon::parse($doc->expiry_date)->format('d M Y') : '—'); ?>

                                <?php if($expiringSoon): ?><span class="pill pill-warn" style="margin-left:4px;">Expiring
                                    soon</span><?php endif; ?>
                            </td>
                            <td><span class="pill <?php echo e($docPill); ?>"><?php echo e(ucfirst($doc->status)); ?></span></td>
                            <td>
                                <div class="row-actions">
                                    <a href="<?php echo e(route('hr.records.document.download', $doc)); ?>"
                                        class="btn-ghost">Download</a>
                                    <?php if(($role === 'hr_admin' || $role === 'super_admin') && $doc->status === 'pending'): ?>
                                    <form method="POST" action="<?php echo e(route('hr.records.document.verify', $doc)); ?>">
                                        <?php echo csrf_field(); ?><input type="hidden" name="action" value="verified"><button class="approve"
                                            type="submit">Verify</button></form>
                                    <form method="POST" action="<?php echo e(route('hr.records.document.verify', $doc)); ?>">
                                        <?php echo csrf_field(); ?><input type="hidden" name="action" value="rejected"><button class="reject"
                                            type="submit">Reject</button></form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php endif; ?>

                <?php if(($role === 'hr_admin' || $role === 'super_admin') || $isSelf): ?>
                <form method="POST" action="<?php echo e(route('hr.records.document.upload', $viewed)); ?>"
                    enctype="multipart/form-data"
                    style="margin-top:20px;padding-top:20px;border-top:1px solid var(--line);">
                    <?php echo csrf_field(); ?>
                    <div class="field-grid cols-3">
                        <div class="field">
                            <label>Document type</label>
                            <select name="document_type" required>
                                <option value="Aadhar Card">Aadhar Card</option>
                                <option value="PAN Card">PAN Card</option>
                                <option value="Address Proof">Address Proof</option>
                                <option value="Education Certificate">Education Certificate</option>
                                <option value="Experience Letter">Experience Letter</option>
                                <option value="Offer Letter">Offer Letter</option>
                                <option value="Resume">Resume</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="field"><label>File (PDF/JPG/PNG, max 5MB)</label><input type="file" name="file"
                                accept=".pdf,.jpg,.jpeg,.png" required></div>
                        <div class="field"><label>Expiry date (if applicable)</label><input type="date"
                                name="expiry_date"></div>
                    </div>
                    <div class="form-actions"><button type="submit" class="btn-primary">Upload document</button></div>
                </form>
                <?php endif; ?>
            </div>

            <?php if(($role === 'hr_admin' || $role === 'super_admin') || $isSelf): ?>
            <div class="tabpanel" data-tabpanel="security">
                <?php if($isSelf): ?>
                <p class="card-note">Change your own sign-in password.</p>
                <form method="POST" action="<?php echo e(route('hr.records.password.update', $viewed)); ?>" style="max-width:360px;">
                    <?php echo csrf_field(); ?>
                    <div class="field" style="margin-bottom:14px;"><label>Current password</label><input type="password"
                            name="current_password" required></div>
                    <div class="field" style="margin-bottom:14px;"><label>New password</label><input type="password"
                            name="new_password" minlength="8" required></div>
                    <div class="field"><label>Confirm new password</label><input type="password"
                            name="new_password_confirmation" minlength="8" required></div>
                    <div class="form-actions"><button type="submit" class="btn-primary">Update password</button></div>
                </form>
                <?php else: ?>
                <p class="card-note">Reset <?php echo e($viewed->user->name); ?>'s password — no current password required for an
                    admin reset.</p>
                <form method="POST" action="<?php echo e(route('hr.records.password.update', $viewed)); ?>" style="max-width:360px;">
                    <?php echo csrf_field(); ?>
                    <div class="field" style="margin-bottom:14px;"><label>New password</label><input type="password"
                            name="new_password" minlength="8" required></div>
                    <div class="field"><label>Confirm new password</label><input type="password"
                            name="new_password_confirmation" minlength="8" required></div>
                    <div class="form-actions"><button type="submit" class="btn-primary">Reset password</button></div>
                </form>
                <?php endif; ?>
            </div>
<<<<<<< Updated upstream
=======

            <div class="tabpanel" data-tabpanel="secondary-contact">
                <p class="card-note">Emergency / secondary contact on file for <?php echo e($viewed->user->name); ?>.</p>
                <?php $sc = $viewed->secondaryContact; ?>
                <form method="POST" action="<?php echo e(route('hr.records.secondary-contact.update', $viewed)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="field-grid">
                        <div class="field"><label>Contact name</label><input name="name"
                                value="<?php echo e($sc->name ?? ''); ?>" <?php if(!$canEdit): echo 'disabled'; endif; ?>></div>
                        <div class="field"><label>Relation</label><input name="relation"
                                value="<?php echo e($sc->relation ?? ''); ?>" placeholder="e.g. Spouse, Parent, Sibling"
                                <?php if(!$canEdit): echo 'disabled'; endif; ?>></div>
                        <div class="field"><label>Phone number</label><input name="phone"
                                value="<?php echo e($sc->phone ?? ''); ?>" <?php if(!$canEdit): echo 'disabled'; endif; ?>></div>
                        <div class="field"><label>Email</label><input name="email" value="<?php echo e($sc->email ?? ''); ?>"
                                <?php if(!$canEdit): echo 'disabled'; endif; ?>></div>
                        <div class="field full"><label>Address</label><input name="address"
                                value="<?php echo e($sc->address ?? ''); ?>" <?php if(!$canEdit): echo 'disabled'; endif; ?>></div>
                    </div>
                    <?php if($canEdit): ?>
                    <div class="form-actions"><button type="submit" class="btn-primary">Save secondary
                            contact</button></div>
                    <?php endif; ?>
                </form>
            </div>
>>>>>>> Stashed changes
            <?php endif; ?>

            <?php if(($role === 'hr_admin' || $role === 'super_admin') && $viewed->history && $viewed->history->isNotEmpty()): ?>
            <div class="tabpanel" data-tabpanel="history">
                <table>
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Effective</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $viewed->history->sortByDesc('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($h->field_changed); ?></td>
                            <td><?php echo e($h->old_value ?? '—'); ?></td>
                            <td><?php echo e($h->new_value ?? '—'); ?></td>
                            <td><?php echo e($h->effective_date); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <p class="field-hint">No employee profile linked to your account.</p>
        <?php endif; ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/partials/profile-card.blade.php ENDPATH**/ ?>