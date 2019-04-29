<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token',50)->nullable();
            $table->unsignedSmallInteger('batch_nr')->nullable();
            $table->unsignedSmallInteger('bankaccount_id')->nullable();
            $table->unsignedInteger('bunq_request_id')->nullable();
            $table->string('bunq_share_url',200)->nullable();

            $table->decimal('amount',6,2)->nullable();
            $table->string('description',200)->nullable();
            $table->unsignedInteger('member_id')->nullable();
            $table->unsignedTinyInteger('season_id')->nullable();
            $table->string('email',100)->nullable();
            $table->string('iban',50)->nullable();
            $table->enum('status',['OPEN','ACCEPTED','EXPIRED'])->default('OPEN');
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
        Schema::dropIfExists('payment_requests');
    }
}
