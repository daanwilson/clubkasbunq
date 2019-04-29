<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('member_id',20);
            $table->string('member_initials',10)->nullable();
            $table->string('member_lastname',50)->nullable();
            $table->string('member_insertion',20)->nullable();
            $table->string('member_firstname',20)->nullable();
            $table->string('member_street',50)->nullable();
            $table->string('member_number',50)->nullable();
            $table->string('member_number_addition',10)->nullable();
            $table->string('member_zipcode',10)->nullable();
            $table->string('member_place',50)->nullable();
            $table->unsignedSmallInteger('member_country_id')->nullable();
            $table->date('member_birthdate')->nullable();
            $table->string('member_birthplace')->nullable();
            $table->unsignedSmallInteger('member_birthcountry_id')->nullable();
            $table->enum('member_gender',array("","m","f"))->nullable();
            $table->string('member_phone',15)->nullable();
            $table->string('member_mobile',15)->nullable();
            $table->string('member_email',50)->nullable();
            $table->string('member_parent1_name',50)->nullable();
            $table->string('member_parent1_phone',15)->nullable();
            $table->string('member_parent1_email',50)->nullable();
            $table->string('member_parent2_name',50)->nullable();
            $table->string('member_parent2_phone',15)->nullable();
            $table->string('member_parent2_email',50)->nullable();
            $table->string('member_health_insurance_company',50)->nullable();
            $table->string('member_health_insurance_number',50)->nullable();
            $table->text('member_additional_info')->default(null)->nullable();
            
            $table->foreign('member_country_id')->references('id')->on('countries');
            $table->foreign('member_birthcountry_id')->references('id')->on('countries');
            
            $table->timestamps();
            $table->softDeletes();
        });
        // Create table for associating roles to users (Many-to-Many)
        Schema::create('member_teams', function (Blueprint $table) {
            $table->unsignedInteger('member_id');
            $table->unsignedTinyInteger('team_id');

            $table->foreign('member_id')->references('id')->on('members')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['member_id', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_teams');
        Schema::dropIfExists('members');
    }
}
