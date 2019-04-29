<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('team_id');
            $table->date('date')->nullable();
            $table->string('description',100)->nullable();
            $table->string('currency',5)->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->unsignedTinyInteger('season_id')->nullable();
            $table->unsignedTinyInteger('purpose_id')->nullable();
            $table->unsignedTinyInteger('item_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_payments');
    }
}
