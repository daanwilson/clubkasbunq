<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('invoiceId');
            $table->unsignedInteger('invoiceNumber');
            $table->date('invoiceDate');
            $table->decimal('invoiceAmount',8,2);
            $table->unsignedSmallInteger('invoiceAccountId');
            $table->unsignedSmallInteger('accountId');
            $table->decimal('transactionAmount',8,2);
            $table->unsignedTinyInteger('cashed')->default(0);
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
        Schema::dropIfExists('invoices');
    }
}
