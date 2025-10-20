<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    //
      function scopeWithQuiz($query){
        return $query->join('quizzes','records.quiz_id',"=","quizzes.id")
        ->select('quizzes.*','records.*');
    }

    function quiz(){
        return $this->belongsTo(Quiz::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function getAccuracyPercentageAttribute()
    {
        if ($this->total_questions == 0) {
            return 0;
        }
        return round(($this->correct_answers / $this->total_questions) * 100);
    }
}
