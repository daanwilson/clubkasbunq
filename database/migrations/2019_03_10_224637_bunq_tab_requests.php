<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BunqTabRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bunq_tabs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('AccountId');
            $table->decimal('amount',5,2);
            $table->string('description',100);
            $table->date('expires')->nullable();
            $table->string('shortname',50);
            $table->integer('bunq_tab_id')->nullable();

            $table->timestamps();

            $table->unique('shortname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bunq_tabs');
    }
}
