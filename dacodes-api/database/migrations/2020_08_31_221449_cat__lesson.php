<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CatLesson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cat_Lesson', function (Blueprint $table) {
            $table->bigIncrements('id');           
            $table->string('name',200);
            $table->string('description',500);    
            $table->bigInteger('index');       
            $table->boolean('active')->default(1);

            //Se agrega la llave foranea a la tabla cat_categories
            $table->unsignedBigInteger('courses_id');
            $table->foreign('courses_id')->references('id')->on('Cat_Courses');

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
        Schema::dropIfExists('Cat_Lesson');
    }
}
