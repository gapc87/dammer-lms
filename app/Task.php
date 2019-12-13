<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Task extends Model
{
    protected $fillable = [
        'uuid', 'title', 'statement', 'start_at', 'end_at'
    ];

    /**
     * Una tarea pertenece a un módulo
     *
     * @return MorphToMany
     */
    public function module()
    {
        return $this->morphToMany('App\Module', 'evaluation');
    }

    public function evaluatedTask()
    {
        return $this->hasMany('App\EvaluatedTask');
    }
}
