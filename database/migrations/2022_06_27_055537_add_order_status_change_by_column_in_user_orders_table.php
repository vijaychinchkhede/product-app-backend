<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderStatusChangeByColumnInUserOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->enum('order_status_change_by',['Admin','User'])->default('User');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->dropColumn(['order_status_change_by','updated_at']);
        });
    }
}
