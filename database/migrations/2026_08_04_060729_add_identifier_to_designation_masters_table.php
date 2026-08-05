<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('designation_masters', function (Blueprint $table) {
       $table->string('identifier')->unique()->after('c_designation');
    });
}

public function down(): void
{
    if (Schema::hasColumn('designation_masters', 'identifier')) {
        Schema::table('designation_masters', function (Blueprint $table) {
            $table->dropColumn('identifier');
        });
    }
}
};