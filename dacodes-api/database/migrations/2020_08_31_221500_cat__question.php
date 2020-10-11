<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CatQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cat_Question', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->bigInteger('type');          
            $table->string('question',500);
            $table->string('answer',500);                   
            $table->boolean('active')->default(1);

            //Se agrega la llave foranea a la tabla cat_categories
            $table->unsignedBigInteger('lesson_id');
            $table->foreign('lesson_id')->references('id')->on('Cat_Lesson');

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
        Schema::dropIfExists('Cat_Question');
    }
}
