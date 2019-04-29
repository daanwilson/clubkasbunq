<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClubkasColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_season_data', function (Blueprint $table) {
            $table->unsignedInteger('member_id');
            $table->unsignedTinyInteger('season_id');
            $table->unsignedSmallInteger('clubloten_count')->nullable();

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
        Schema::dropIfExists('member_season_data');
    }
}
