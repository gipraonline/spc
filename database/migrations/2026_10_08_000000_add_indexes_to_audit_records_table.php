<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The System & Access page filters audit entries by module and sorts by
     * date, so index those two columns now that every menu writes here.
     */
    public function up(): void
    {
        Schema::table('audit_records', function (Blueprint $table) {
            $table->index(['module', 'created_at'], 'audit_records_module_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('audit_records', function (Blueprint $table) {
            $table->dropIndex('audit_records_module_created_idx');
        });
    }
};
