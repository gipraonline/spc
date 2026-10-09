<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Hr\AnnouncementController;
use App\Http\Controllers\Hr\AppraisalController;
use App\Http\Controllers\Hr\AttendanceController;
use App\Http\Controllers\Hr\DocumentVerificationController;
use App\Http\Controllers\Hr\EmployeeExitController;
use App\Http\Controllers\Hr\EmployeeRecordsController;
use App\Http\Controllers\Hr\IncentiveController;
use App\Http\Controllers\Hr\LeaveController;
use App\Http\Controllers\Hr\NotificationController;
use App\Http\Controllers\Hr\OrganizationController;
use App\Http\Controllers\Hr\PayrollController;
use App\Http\Controllers\Hr\PfGratuityController;
use App\Http\Controllers\Hr\RecruitmentController;
use App\Http\Controllers\Hr\ReportsController;
use App\Http\Controllers\Hr\SettingsController;
use App\Http\Controllers\Hr\SupportController;
use App\Http\Controllers\Hr\SystemController;
use App\Http\Controllers\Hr\WfhController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HR Module Routes (ONE SPC)
|--------------------------------------------------------------------------
|
| Everything here is mounted under /hr, reads/writes only the "spc_hr"
| database connection, and is registered separately from the SPC
| module's own routes/web.php so the SPC module is left untouched.
| Route names are auto-prefixed "hr." by the group below.
|
*/

Route::prefix('hr')->name('hr.')->group(function () {

    Route::redirect('/login', '/login');
    // Single logout for the whole app: same action the SPC side uses,
    // so there's exactly one place that tears down the session.
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // The HR-only dashboard was removed; /hr now lands on the unified dashboard.
    Route::redirect('/', '/dashboard');

    Route::middleware(['hr.auth'])->group(function () {

        // Attendance
        Route::middleware(['permission:attendance.view'])->group(function () {

            Route::get('/modules/attendance', [AttendanceController::class, 'index'])
                ->name('attendance.index');

            Route::get('/modules/attendance/report', [AttendanceController::class, 'report'])
                ->name('attendance.report');

            Route::post('/modules/attendance/punch', [AttendanceController::class, 'punch'])
                ->name('attendance.punch');

            Route::post('/modules/attendance/check-in', [AttendanceController::class, 'checkIn'])
                ->name('attendance.check-in');

            Route::post('/modules/attendance/check-out', [AttendanceController::class, 'checkOut'])
                ->name('attendance.check-out');

            Route::post('/modules/attendance/{employee}/mark', [AttendanceController::class, 'mark'])
                ->name('attendance.mark');

            Route::post('/modules/attendance/regularize', [AttendanceController::class, 'regularize'])
                ->name('attendance.regularize');

            Route::post('/modules/attendance/regularize/{regularization}/decide', [AttendanceController::class, 'decide'])
                ->name('attendance.decide');
        });

        // Leave
        Route::middleware(['permission:leave.view'])->group(function () {

            Route::get('/modules/leave', [LeaveController::class, 'index'])
                ->name('leave.index');

            Route::post('/modules/leave/apply', [LeaveController::class, 'apply'])
                ->name('leave.apply');

            Route::post('/modules/leave/{leaveRequest}/decide', [LeaveController::class, 'decide'])
                ->name('leave.decide');
        });
        // Payroll
        Route::middleware(['permission:payroll.view'])->group(function () {
            Route::get('/modules/payroll', [PayrollController::class, 'index'])->name('payroll.index');
            Route::post('/modules/payroll/run', [PayrollController::class, 'runPayroll'])->name('payroll.run');
            Route::post('/modules/payroll/{employee}/salary', [PayrollController::class, 'updateSalary'])->name('payroll.salary.update');
            Route::get('/modules/payroll/payslip/{payslip}', [PayrollController::class, 'payslip'])->name('payroll.payslip');
            Route::post('/modules/payroll/runs/{run}/submit', [PayrollController::class, 'submit'])->name('payroll.submit');
            Route::post('/modules/payroll/runs/{run}/coo-approve', [PayrollController::class, 'approveCoo'])->name('payroll.coo-approve');
            Route::post('/modules/payroll/runs/{run}/md-approve', [PayrollController::class, 'approveMd'])->name('payroll.md-approve');
            Route::post('/modules/payroll/runs/{run}/return', [PayrollController::class, 'returnToHr'])->name('payroll.return');
            Route::post('/modules/payroll/payslip/{payslip}/request', [PayrollController::class, 'requestPayslip'])->name('payroll.payslip.request');
            Route::post('/modules/payroll/payslip-requests/{payslipRequest}/decide', [PayrollController::class, 'decideRequest'])->name('payroll.payslip.decide');
            Route::post('/modules/payroll/runs/{run}/discard', [PayrollController::class, 'discard'])->name('payroll.discard');
            Route::post('/modules/payroll/runs/{run}/paid', [PayrollController::class, 'markPaid'])->name('payroll.paid');
            Route::get('/modules/payroll/runs/{run}/register', [PayrollController::class, 'register'])->name('payroll.register');
            Route::get('/modules/payroll/runs/{run}/bank-file', [PayrollController::class, 'bankFile'])->name('payroll.bank-file');
        });

        // Recruitment
        Route::middleware(['permission:recruitment.view'])->group(function () {
            Route::get('/modules/recruitment', [RecruitmentController::class, 'index'])
                ->name('recruitment.index');

            Route::post('/modules/recruitment/requisitions', [RecruitmentController::class, 'storeRequisition'])
                ->name('recruitment.requisition.store');

            Route::post('/modules/recruitment/requisitions/{requisition}/decide', [RecruitmentController::class, 'decideRequisition'])
                ->name('recruitment.requisition.decide');

            Route::post('/modules/recruitment/requisitions/{requisition}/candidates', [RecruitmentController::class, 'storeCandidate'])
                ->name('recruitment.candidate.store');

            Route::get('/modules/recruitment/candidates/{candidate}/resume', [RecruitmentController::class, 'resume'])
                ->name('recruitment.candidate.resume');

            Route::post('/modules/recruitment/candidates/{candidate}/stage', [RecruitmentController::class, 'updateCandidateStage'])
                ->name('recruitment.candidate.stage');

            Route::post('/modules/recruitment/checklist/{item}/toggle', [RecruitmentController::class, 'toggleChecklistItem'])
                ->name('recruitment.checklist.toggle');
        });

        // Employee Records
        Route::middleware(['permission:employee-records.view'])->group(function () {
            Route::get('/modules/employee-records', [EmployeeRecordsController::class, 'index'])
                ->name('records.index');

            Route::post('/modules/employee-records', [EmployeeRecordsController::class, 'store'])
                ->name('records.store');

            Route::post('/modules/employee-records/{employee}/status', [EmployeeRecordsController::class, 'updateStatus'])
                ->name('records.status');

            // Employee history / exits (resignation, termination, ...)
            Route::get('/modules/employee-records/exit-register', [EmployeeExitController::class, 'register'])
                ->name('records.exit.register');

            Route::get('/modules/employee-records/{employee}/file', [EmployeeExitController::class, 'file'])
                ->name('records.file');

            Route::post('/modules/employee-records/{employee}/exit', [EmployeeExitController::class, 'store'])
                ->name('records.exit.store');

            Route::post('/modules/employee-records/exits/{exit}', [EmployeeExitController::class, 'update'])
                ->name('records.exit.update');

            Route::post('/modules/employee-records/{employee}/reinstate', [EmployeeExitController::class, 'reinstate'])
                ->name('records.exit.reinstate');

            Route::post('/modules/employee-records/documents/{document}/verify', [EmployeeRecordsController::class, 'verifyDocument'])
                ->name('records.document.verify');
        });

        // Self-service on the profile card: an employee saves their own details,
        // password, secondary contact and documents from My Profile. These need
        // either permission; the controller still allows only HR or the person
        // themselves, so nobody can edit someone else's record.
        Route::middleware(['permission:employee-records.view|my-profile.view'])->group(function () {
            Route::post('/modules/employee-records/{employee}', [EmployeeRecordsController::class, 'update'])
                ->name('records.update');

            Route::post('/modules/employee-records/{employee}/password', [EmployeeRecordsController::class, 'updatePassword'])
                ->name('records.password.update');

            Route::post('/modules/employee-records/{employee}/secondary-contact', [EmployeeRecordsController::class, 'updateSecondaryContact'])
                ->name('records.secondary-contact.update');

            Route::post('/modules/employee-records/{employee}/documents', [EmployeeRecordsController::class, 'uploadDocument'])
                ->name('records.document.upload');

            Route::get('/modules/employee-records/documents/{document}/download', [EmployeeRecordsController::class, 'downloadDocument'])
                ->name('records.document.download');
        });

        // Document Verification — HR Admin / Super Admin can open and view (e.g. the MD).
        // Verifying / rejecting needs the Spatie permission document-verification.verify,
        // which only HR gets by default (Admin > Roles can change that).
        Route::middleware(['hr.admin'])->group(function () {
            Route::get('/modules/document-verification', [DocumentVerificationController::class, 'index'])
                ->name('verification.index');

            Route::get('/modules/document-verification/{document}/preview', [DocumentVerificationController::class, 'preview'])
                ->name('verification.preview');

            Route::post('/modules/document-verification/{document}/decide', [DocumentVerificationController::class, 'decide'])
                ->middleware('permission:document-verification.verify')
                ->name('verification.decide');
        });

        // My Profile
        Route::middleware(['permission:my-profile.view'])->group(function () {
            Route::get('/my-profile', [EmployeeRecordsController::class, 'myProfile'])
                ->name('profile.index');
        });

        // Performance / Appraisal
        Route::middleware(['permission:performance.view'])->group(function () {
            Route::get('/modules/appraisal', [AppraisalController::class, 'index'])
                ->name('appraisal.index');

            Route::post('/modules/appraisal/{appraisal}/self', [AppraisalController::class, 'submitSelfAssessment'])
                ->name('appraisal.self');

            Route::post('/modules/appraisal/{appraisal}/review', [AppraisalController::class, 'submitManagerReview'])
                ->name('appraisal.review');

            Route::post('/modules/appraisal/targets', [AppraisalController::class, 'saveTargets'])
                ->name('appraisal.targets.save');
        });

        // PF & Gratuity
        Route::middleware(['permission:pf-gratuity.view'])->group(function () {
            Route::get('/modules/pf-gratuity', [PfGratuityController::class, 'index'])
                ->name('pf.index');
        });

        // Incentives
        Route::middleware(['permission:incentives.view'])->group(function () {
            Route::get('/modules/incentive', [IncentiveController::class, 'index'])
                ->name('incentive.index');

            Route::post('/modules/incentive/rules', [IncentiveController::class, 'storeRule'])
                ->name('incentive.rule.store');

            Route::post('/modules/incentive/payouts/{payout}/approve', [IncentiveController::class, 'approvePayout'])
                ->name('incentive.payout.approve');

            Route::post('/modules/incentive/calculate', [IncentiveController::class, 'calculate'])
                ->name('incentive.calculate');

            Route::post('/modules/incentive/rules/{rule}/toggle', [IncentiveController::class, 'toggleRule'])
                ->name('incentive.rule.toggle');

            Route::delete('/modules/incentive/rules/{rule}', [IncentiveController::class, 'destroyRule'])
                ->name('incentive.rule.destroy');
        });

        // HR Reports
        Route::middleware(['permission:hr-reports.view'])->group(function () {
            Route::get('/modules/reports', [ReportsController::class, 'index'])
                ->name('reports.index');
        });

        // System & Access
        Route::get('/modules/system', [SystemController::class, 'index'])
            ->middleware('permission:hr-system.view')
            ->name('system.index');

        // Work From Home
        Route::middleware(['permission:work-from-home.view'])->group(function () {
            Route::get('/modules/wfh', [WfhController::class, 'index'])->name('wfh.index');
            Route::post('/modules/wfh', [WfhController::class, 'store'])->name('wfh.store');
            Route::post('/modules/wfh/{wfhRequest}/decide', [WfhController::class, 'decide'])->name('wfh.decide');
        });

        // Announcements
        Route::middleware(['permission:announcements.view'])->group(function () {
            Route::get('/modules/announcements', [AnnouncementController::class, 'index'])
                ->name('announcements.index');

            Route::post('/modules/announcements', [AnnouncementController::class, 'store'])
                ->name('announcements.store');
        });

        // HR Support
        Route::middleware(['permission:hr-support.view'])->group(function () {
            Route::get('/modules/support', [SupportController::class, 'index'])
                ->name('support.index');

            Route::post('/modules/support', [SupportController::class, 'store'])
                ->name('support.store');

            Route::post('/modules/support/{ticket}', [SupportController::class, 'update'])
                ->name('support.update');
        });

        // Settings
        Route::get('/modules/settings', [SettingsController::class, 'index'])
            ->middleware('permission:hr-settings.view')
            ->name('settings.index');

        Route::post('/modules/settings', [SettingsController::class, 'update'])
            ->middleware('permission:hr-settings.edit')
            ->name('settings.update');

        Route::post('/modules/settings/leave-types', [SettingsController::class, 'updateLeaveTypes'])
            ->middleware('permission:hr-settings.edit')
            ->name('settings.leave-types.update');

        Route::delete('/modules/settings/leave-types/{leaveType}', [SettingsController::class, 'destroyLeaveType'])
            ->middleware('permission:hr-settings.edit')
            ->name('settings.leave-types.destroy');

        Route::post('/modules/settings/leave-types/new', [SettingsController::class, 'storeLeaveType'])
            ->middleware('permission:hr-settings.edit')
            ->name('settings.leave-types.store');

        // Organization (Departments, Designations, Holidays)

        Route::get('/modules/organization', [OrganizationController::class, 'index'])
            ->middleware('permission:organization.view')
            ->name('organization.index');

        Route::post('/modules/organization/departments', [OrganizationController::class, 'storeDepartment'])
            ->middleware('permission:organization.create')
            ->name('organization.department.store');

        Route::post('/modules/organization/departments/{department}', [OrganizationController::class, 'updateDepartment'])
            ->middleware('permission:organization.edit')
            ->name('organization.department.update');

        Route::post('/modules/organization/designations', [OrganizationController::class, 'storeDesignation'])
            ->middleware('permission:organization.create')
            ->name('organization.designation.store');

        Route::post('/modules/organization/designations/{designation}', [OrganizationController::class, 'updateDesignation'])
            ->middleware('permission:organization.edit')
            ->name('organization.designation.update');

        Route::post('/modules/organization/designations/{designation}/delete', [OrganizationController::class, 'destroyDesignation'])
            ->middleware('permission:organization.delete')
            ->name('organization.designation.destroy');

        Route::post('/modules/organization/holidays', [OrganizationController::class, 'storeHoliday'])
            ->middleware('permission:organization.create')
            ->name('organization.holiday.store');

        Route::post('/modules/organization/holidays/{holiday}/delete', [OrganizationController::class, 'destroyHoliday'])
            ->middleware('permission:organization.delete')
            ->name('organization.holiday.destroy');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    });
});
