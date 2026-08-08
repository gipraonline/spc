<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE product_masters
            MODIFY c_unit VARCHAR(50) NULL AFTER n_mrp
        ");

        DB::statement("
            ALTER TABLE product_masters
            MODIFY c_hsn_code VARCHAR(20) NULL AFTER c_unit
        ");

        DB::statement("
            ALTER TABLE product_masters
            MODIFY n_gst_percentage DECIMAL(5,2) NULL AFTER c_hsn_code
        ");
    }

    public function down(): void
    {
        // Revert the column order if needed
        DB::statement("
            ALTER TABLE product_masters
            MODIFY c_unit VARCHAR(50) NULL AFTER deleted_at
        ");

        DB::statement("
            ALTER TABLE product_masters
            MODIFY c_hsn_code VARCHAR(20) NULL AFTER c_unit
        ");

        DB::statement("
            ALTER TABLE product_masters
            MODIFY n_gst_percentage DECIMAL(5,2) NULL AFTER c_hsn_code
        ");
    }
};