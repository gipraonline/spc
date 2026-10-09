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

        // Add each column on its own, so a table that already has some of them
        // (earlier partial run, manual SQL import) still gets the missing ones.
        $columns = [
            'approval_stage' => fn (Blueprint $t) => $t->string('approval_stage', 20)->default('draft'),
            'submitted_by' => fn (Blueprint $t) => $t->unsignedBigInteger('submitted_by')->nullable(),
            'submitted_at' => fn (Blueprint $t) => $t->timestamp('submitted_at')->nullable(),
            'coo_by' => fn (Blueprint $t) => $t->unsignedBigInteger('coo_by')->nullable(),
            'coo_at' => fn (Blueprint $t) => $t->timestamp('coo_at')->nullable(),
            'md_by' => fn (Blueprint $t) => $t->unsignedBigInteger('md_by')->nullable(),
            'md_at' => fn (Blueprint $t) => $t->timestamp('md_at')->nullable(),
            'returned_by' => fn (Blueprint $t) => $t->unsignedBigInteger('returned_by')->nullable(),
            'returned_at' => fn (Blueprint $t) => $t->timestamp('returned_at')->nullable(),
            'workflow_remarks' => fn (Blueprint $t) => $t->string('workflow_remarks', 255)->nullable(),
        ];

        foreach ($columns as $name => $define) {
            if (! $hr->hasColumn('payroll_runs', $name)) {
                $hr->table('payroll_runs', fn (Blueprint $table) => $define($table));
            }
        }

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
