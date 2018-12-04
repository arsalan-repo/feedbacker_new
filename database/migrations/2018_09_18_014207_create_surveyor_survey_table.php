<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyorSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveyor_survey', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('surveyor_id');
            $table->unsignedInteger('survey_id');
            $table->timestamps();

            $table->foreign('survey_id')->references('id')->on('surveys');
            $table->foreign('surveyor_id')->references('id')->on('surveyors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surveyor_survey');
    }
}
