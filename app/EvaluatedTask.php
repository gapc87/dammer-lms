<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluatedTask extends Model
{
    protected $fillable = [
        'uuid', 'score', 'upload', 'student_id', 'task_id'
    ];

    public function student()
    {
        return $this->belongsTo('App\User');
    }

    public function module()
    {
        return $this->hasManyThrough('App\Module', 'App\Student');
    }

    public function task()
    {
        return $this->belongsTo('App\Task');
    }
}
