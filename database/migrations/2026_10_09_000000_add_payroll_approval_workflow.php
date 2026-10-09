<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * spc_hr database. Not tracked by the default migrator, run it explicitly:
 *
 *   php artisan migrate --database=spc_hr \
 *     --path=database/migrations/2026_10_09_000000_add_payroll_approval_workflow.php
 *
 * or run database/sql/hr_payroll_approval_workflow.sql on the spc_hr database.
 *
 *  - payroll_runs:     approval_stage + who/when for each approval step
 *  - payslip_requests: employee requests for a payslip, decided by Finance
 */
return new class extends Migration
{
    protected $connection = 'spc_hr';

    public function up(): void
    {
        $hr = Schema::connection('spc_hr');

        $hr->table('payroll_runs', function (Blueprint $table) use ($hr) {
            if (! $hr->hasColumn('payroll_runs', 'approval_stage')) {
                $table->string('approval_stage', 20)->default('draft');
                $table->unsignedBigInteger('submitted_by')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->unsignedBigInteger('coo_by')->nullable();
                $table->timestamp('coo_at')->nullable();
                $table->unsignedBigInteger('md_by')->nullable();
                $table->timestamp('md_at')->nullable();
                $table->unsignedBigInteger('returned_by')->nullable();
                $table->timestamp('returned_at')->nullable();
                $table->string('workflow_remarks', 255)->nullable();
            }
        });

        DB::connection('spc_hr')->table('payroll_runs')->where('status', 'paid')->where('approval_stage', 'draft')->update(['approval_stage' => 'completed']);
        DB::connection('spc_hr')->table('payroll_runs')->where('status', 'processed')->where('approval_stage', 'draft')->update(['approval_stage' => 'pending_finance']);

        if (! $hr->hasTable('payslip_requests')) {
            $hr->create('payslip_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('payslip_id')->index();
                $table->unsignedBigInteger('employee_id')->index();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
                $table->string('reason', 255)->nullable();
                $table->string('remarks', 255)->nullable();
                $table->unsignedBigInteger('decided_by')->nullable();
                $table->timestamp('decided_at')->nullable();
                $table->timestamp('created_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::connection('spc_hr')->dropIfExists('payslip_requests');
    }
};
