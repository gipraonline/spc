<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_card_designation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('n_designation_id');
            $table->string('card_key', 60);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->unique(['n_designation_id', 'card_key']);
            $table->index('n_designation_id');
        });

        // Sensible starting point: HR designations see only HR cards.
        // Everyone else stays "unconfigured" (= sees every card) until an
        // admin edits their designation.
        $catalog = config('dashboard_cards.cards', []);
        $hrDesignations = DB::table('designation_masters')
            ->whereIn('identifier', ['HRM', 'HR_TEAM'])
            ->pluck('n_designation_id');

        $now = now();
        foreach ($hrDesignations as $designationId) {
            $rows = [];
            foreach ($catalog as $key => $card) {
                $rows[] = [
                    'n_designation_id' => $designationId,
                    'card_key' => $key,
                    'is_visible' => ($card['group'] ?? '') === 'hr',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($rows) {
                DB::table('dashboard_card_designation')->insert($rows);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_card_designation');
    }
};
