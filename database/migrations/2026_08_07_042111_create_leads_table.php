<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {

            $table->bigIncrements('n_lead_id');

            // Customer
            $table->enum('c_customer_type', ['new', 'existing'])->default('new');

            $table->string('c_customer_name');
            $table->string('n_mobile', 20);
            $table->string('c_email')->nullable();
            $table->text('c_address')->nullable();

            $table->unsignedBigInteger('n_state_id')->nullable();
            $table->unsignedBigInteger('n_district_id')->nullable();

            // Visit
            $table->date('d_visit_date')->nullable();

            // Lead Status
            $table->string('c_lead_status')->nullable();

            $table->date('d_expected_availability_date')->nullable();

            // Follow-up
            $table->date('next_followup_date')->nullable();
            $table->time('next_followup_time')->nullable();
            $table->string('followup_type')->nullable();

            // Priority
            $table->enum('priority', [
                'Low',
                'Medium',
                'High',
                'Urgent'
            ])->default('Medium');

            // Remarks
            $table->longText('remarks')->nullable();

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // Soft Delete
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
