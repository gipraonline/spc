<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            // Straight-line km from the order location to the assigned franchise.
            // Filled automatically by App\Models\SalesOrder when lat/long + franchise are known.
            $table->decimal('franchise_distance_km', 8, 2)->nullable()->after('nearest_franchise_id');

            // Reports (performance, incentives, coverage map) filter on these.
            $table->index(['farm_care_advisor_id', 'd_date'], 'sales_orders_advisor_date_idx');
            $table->index('nearest_franchise_id', 'sales_orders_franchise_idx');
            $table->index(['c_order_status', 'd_date'], 'sales_orders_status_date_idx');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->index(['n_fca_id', 'next_followup_date'], 'leads_fca_followup_idx');
            $table->index('n_mobile', 'leads_mobile_idx');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('leads_fca_followup_idx');
            $table->dropIndex('leads_mobile_idx');
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropIndex('sales_orders_advisor_date_idx');
            $table->dropIndex('sales_orders_franchise_idx');
            $table->dropIndex('sales_orders_status_date_idx');
            $table->dropColumn('franchise_distance_km');
        });
    }
};
