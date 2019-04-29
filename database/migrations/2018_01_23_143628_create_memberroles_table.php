<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberrolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_roles', function (Blueprint $table) {
            $table->unsignedTinyInteger('id',true);
            $table->string('role_name',50);
            $table->timestamps();
        });
        // Create table for associating roles to users (Many-to-Many)
        Schema::create('member_member_roles', function (Blueprint $table) {
            $table->integer('member_id')->unsigned();
            $table->unsignedTinyInteger('role_id');
            $table->date('role_start_date')->nullable();

            $table->foreign('member_id')->references('id')->on('members')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('member_roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['member_id', 'role_id']);
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_member_roles');
        Schema::dropIfExists('member_roles');
    }
}
