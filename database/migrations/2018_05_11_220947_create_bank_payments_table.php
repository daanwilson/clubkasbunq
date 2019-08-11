<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('external_id');
            $table->unsignedSmallInteger('bankaccount_id');
            $table->unsignedInteger('external_bankaccount_id');
            $table->string('description',255)->nullable();
            $table->string('currency',5)->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->timestamp('created')->nullable();
            $table->timestamp('updated')->nullable();
            $table->string('counterpart_IBAN',50)->nullable();
            $table->string('counterpart_name',100)->nullable();
            $table->unsignedInteger('batch_id')->nullable();
            $table->unsignedTinyInteger('season_id')->nullable();
            $table->unsignedTinyInteger('purpose_id')->nullable();
            $table->unsignedTinyInteger('item_id')->nullable();
            $table->string('type',20)->nullable();
            $table->string('subType',20)->nullable();
            $table->enum('input_type',['payment','request','tab_request','request_response'])->default('payment');
            $table->string('status',20)->nullable();
            $table->string('share_url',200)->nullable();
            $table->timestamp('expire')->nullable();
            $table->timestamps();
            
            $table->foreign('bankaccount_id')->references('id')->on('bank_accounts')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_payments');
    }
}
