<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('users', function (Blueprint $table) {
           $table->increments('id');
           $table->string('uid');
           $table->string('fullname');
           $table->string('email')->unique();
           $table->string('contraseña');
           $table->string('avatar')->nullable();
           $table->string('password');
           $table->string('speciality');
           $table->string('work_location');
           $table->unsignedInteger('status_id');
           $table->timestamps();

           $table ->foreign('status_id')->references('id')->on('statuses')->onUpdate('no action')->onDelete('no action');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
