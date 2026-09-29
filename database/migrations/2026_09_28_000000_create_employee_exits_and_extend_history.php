<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * spc_hr database (same as employees / employee_history).
     * The HR tables are not migration-tracked in this repo, so either run:
     *   php artisan migrate --database=spc_hr \
     *     --path=database/migrations/2026_09_28_000000_create_employee_exits_and_extend_history.php
     * or run the equivalent SQL in database/employee_history.sql on spc_hr.
     */
    protected $connection = 'spc_hr';

    public function up(): void
    {
        // One row per separation. An employee who is re-hired and leaves
        // again gets a second row, so nothing is ever overwritten.
        Schema::connection('spc_hr')->create('employee_exits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();

            $table->enum('exit_type', [
                'resignation', 'termination', 'retirement',
                'end_of_contract', 'absconding', 'death', 'other',
            ]);
            $table->enum('initiated_by', ['employee', 'company'])->default('employee');
            $table->string('reason_category', 60)->nullable();
            $table->text('reason')->nullable();

            $table->date('notice_date')->nullable();
            $table->date('last_working_day');
            $table->unsignedSmallInteger('notice_period_days')->nullable();
            $table->unsignedSmallInteger('notice_served_days')->nullable();
            $table->boolean('notice_waived')->default(false);

            $table->enum('eligible_for_rehire', ['yes', 'no', 'conditional'])->default('yes');
            $table->text('rehire_remarks')->nullable();

            $table->boolean('exit_interview_done')->default(false);
            $table->text('exit_interview_notes')->nullable();

            $table->json('clearance')->nullable();
            $table->text('final_settlement_notes')->nullable();
            $table->text('manager_remarks')->nullable();
            $table->unsignedTinyInteger('overall_rating')->nullable(); // 1-5 closing rating

            // Frozen copy of who they were and how they performed at exit.
            $table->json('snapshot')->nullable();

            $table->enum('status', ['on_notice', 'completed', 'reinstated'])->default('on_notice');
            $table->timestamp('reinstated_at')->nullable();
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'status']);
            $table->index('last_working_day');
        });

        // Turn employee_history into a real timeline, not just a field diff.
        Schema::connection('spc_hr')->table('employee_history', function (Blueprint $table) {
            $table->string('event_type', 30)->default('change')->after('employee_id');
            $table->text('remarks')->nullable()->after('new_value');
            $table->index(['employee_id', 'effective_date']);
        });

        Schema::connection('spc_hr')->table('employee_history', function (Blueprint $table) {
            $table->text('old_value')->nullable()->change();
            $table->text('new_value')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::connection('spc_hr')->table('employee_history', function (Blueprint $table) {
            $table->dropIndex(['employee_id', 'effective_date']);
            $table->dropColumn(['event_type', 'remarks']);
        });
        Schema::connection('spc_hr')->dropIfExists('employee_exits');
    }
};
