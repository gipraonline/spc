
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_masters', function (Blueprint $table) {

            $table->string('c_unit', 50)
                  ->nullable()
                  ->after('n_mrp');

            $table->string('n_gst_percentage',5,2)
                  ->nullable()
                  ->after('c_hsn_code');
            $table->string('c_hsn_code',50)
                  ->nullable()
                  ->after('c_unit');
        });
    }

    public function down(): void
    {
        Schema::table('product_masters', function (Blueprint $table) {
            $table->dropColumn('c_unit');
            $table->dropColumn('n_hsn_code');
            $table->dropColumn('n_gst_percentage');
        });
    }
};
