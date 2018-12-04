<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Surveyor extends Model
{
    public function answers(){
        return $this->hasMany(Answer::class);
    }

    public function surveys(){
        return $this->belongsToMany(Survey::class,'surveyor_survey');
    }
}

