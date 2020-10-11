<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CatUserCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cat_User_Course', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            
            //Se agrega la llave foranea a la tabla users
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            //Se agrega la llave foranea a la tabla cat_courses
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('Cat_Courses');
            $table->boolean('approve')->default(0);   
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
        Schema::dropIfExists('Cat_User_Course');
    }
}
