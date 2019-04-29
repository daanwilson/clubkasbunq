<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->unsignedTinyInteger('id',true);
            $table->string('season_name',50);
            $table->date('season_start')->nullable();
            $table->date('season_end')->nullable();
            $table->timestamps();
        });
        
        // Create table for associating roles to users (Many-to-Many)
        Schema::create('member_seasons', function (Blueprint $table) {
            $table->unsignedInteger('member_id');
            $table->unsignedTinyInteger('season_id');

            $table->foreign('member_id')->references('id')->on('members')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('season_id')->references('id')->on('seasons')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->primary(['member_id', 'season_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_seasons');
        Schema::dropIfExists('seasons');
    }
}
