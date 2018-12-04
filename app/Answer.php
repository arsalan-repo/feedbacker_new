<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function surveyor(){
        return $this->belongsTo(Surveyor::class);
    }

    public function Survey(){
        return $this->belongsTo(Survey::class);
    }

    public function question(){
        return $this->hasOne(Question::class);
    }
}
