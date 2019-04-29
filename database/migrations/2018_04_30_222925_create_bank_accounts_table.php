<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->unsignedSmallInteger('id',true);
            $table->string('description',50)->nullable();
            $table->string('currency',5)->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->string('IBAN',50)->nullable();
            $table->string('color',10)->nullable();
            $table->unsignedInteger('external_id')->nullable();
            $table->timestamps();
        });
        
        // Create table for associating roles to users (Many-to-Many)
        Schema::create('bank_account_roles', function (Blueprint $table) {
            $table->unsignedSmallInteger('bankaccount_id');
            $table->unsignedTinyInteger('role_id');

            $table->foreign('bankaccount_id')->references('id')->on('bank_accounts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['bankaccount_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_account_roles');
        Schema::dropIfExists('bank_accounts');        
    }
}
