<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CatTypeQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cat_Type_Question', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->boolean('boolean');           
            $table->string('opc_multiple_1',500);
            $table->string('opc_multiple_2',500);
            $table->string('opc_multiple_3',500);                   
            $table->boolean('active')->default(1);
            $table->unsignedBigInteger('question_id');
            $table->boolean('approve')->default(0);

            //Se agrega la llave foranea a la tabla
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('Cat_Question');

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
        Schema::dropIfExists('Cat_Type_Question');
    }
}
