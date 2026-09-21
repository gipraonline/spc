<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('store_masters', function (Blueprint $table) {
        $table->string('c_owner_name')->nullable()->after('c_store_name');
    });
}

public function down()
{
    Schema::table('store_masters', function (Blueprint $table) {
        $table->dropColumn('c_owner_name');
    });
}
};