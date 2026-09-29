<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| SPC-side notifications (sales / order / other)
|--------------------------------------------------------------------------
|
| Mirrors App\Models\Hr\Notification's shape (spc_hr DB) so the two can be
| merged into one feed for the unified dashboard's bell. `user_id` stores
| the authenticated admin's key (admins.n_role_id — see App\Models\Admin),
| the same value Auth::id() returns for this guard.
|
| This table is empty until something calls Notification::send(...) — see
| App\Models\Notification. Wiring that into the sales/order flows (e.g. an
| order getting approved/dispatched) is a follow-up: add the call at the
| point the status actually changes, wherever that lives in your
| SalesController.
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('category', 20)->default('other'); // sales | order | other
            $table->string('title')->nullable();
            $table->string('message');
            $table->string('link')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
