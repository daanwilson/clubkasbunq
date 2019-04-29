<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryKeyMemberUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('member_season_data');
        Schema::create('member_season_data', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('member_id');
            $table->unsignedTinyInteger('season_id');
            $table->unsignedSmallInteger('clubloten_count')->nullable();

            $table->foreign('member_id')->references('id')->on('members')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('season_id')->references('id')->on('seasons')
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
        Schema::table('member_season_data', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
}
