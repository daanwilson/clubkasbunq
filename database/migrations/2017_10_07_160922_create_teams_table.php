<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->unsignedTinyInteger('id',true);
            $table->string('name',100)->unique();
            $table->string('color',10)->nullable();
            $table->unsignedTinyInteger('start_age')->nullable();
            $table->unsignedTinyInteger('end_age')->nullable();
            $table->unsignedTinyInteger('start_season_month')->nullable();
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
        Schema::dropIfExists('teams');
    }
}
