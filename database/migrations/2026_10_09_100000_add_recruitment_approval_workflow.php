<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * spc_hr database. Not tracked by the default migrator, run it explicitly:
 *
 *   php artisan migrate --database=spc_hr \
 *     --path=database/migrations/2026_10_09_100000_add_recruitment_approval_workflow.php
 *
 *  - job_requisitions: status becomes pending_approval | open | rejected | closed,
 *                      plus who approved / rejected it and when
 *  - candidates:       stage and source become plain strings (applied, shortlisted,
 *                      interviewed, offered, hired, rejected / referral, portal,
 *                      walk-in, agency)
 *
 * Existing requisitions keep their current status, so open ones stay open.
 */
return new class extends Migration
{
    protected $connection = 'spc_hr';

    public function up(): void
    {
        $hr = Schema::connection('spc_hr');
        $db = DB::connection('spc_hr');

        if ($db->getDriverName() === 'mysql') {
            $db->statement("ALTER TABLE job_requisitions MODIFY status VARCHAR(30) NOT NULL DEFAULT 'pending_approval'");
            $db->statement("ALTER TABLE candidates MODIFY stage VARCHAR(30) NOT NULL DEFAULT 'applied'");
            $db->statement('ALTER TABLE candidates MODIFY source VARCHAR(30) NULL');
        }

        $hr->table('job_requisitions', function (Blueprint $table) use ($hr) {
            if (! $hr->hasColumn('job_requisitions', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->string('decision_remarks', 255)->nullable();
                $table->timestamp('closed_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::connection('spc_hr')->table('job_requisitions', function (Blueprint $table) {
            $table->dropColumn(['approved_by', 'approved_at', 'decision_remarks', 'closed_at']);
        });
    }
};
