<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
Schema::table('field_logs', function (Blueprint $table) {

$table->dropForeign(['user_id']);

$table->foreign('user_id')
->references('id')
->on('admins')
->cascadeOnDelete();

});
}


public function down()
{
Schema::table('field_logs', function (Blueprint $table) {

$table->dropForeign(['user_id']);

$table->foreign('user_id')
->references('id')
->on('users')
->cascadeOnDelete();

});
}
};