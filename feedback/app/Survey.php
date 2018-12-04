<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = ['title','url'];

    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function answers(){
        return $this->hasManyThrough(Answer::class,Question::class);
    }


    public function surveyors(){
        return $this->belongsToMany(Surveyor::class,'surveyor_survey');
    }
}
