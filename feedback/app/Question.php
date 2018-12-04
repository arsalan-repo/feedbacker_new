<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable= ['type','option1','option2','option3','option4','option5','question_title','survey_id'];

    public function survey(){
        return $this->belongsTo(Survey::class);
    }


    public function answers(){
        return $this->hasMany(Answer::class);
    }
}
