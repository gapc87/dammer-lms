<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name', 'teacher_id'
    ];

    /**
     * Un usuario es el profesor de un módulo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * En un módulo hay muchos estuduantes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany('App\User', 'students');
    }

    /**
     * Un módulo posee muchos recursos
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function resources()
    {
        return $this->morphedByMany('App\Resource', 'evaluation')->withPivot('evaluation');
    }

    /**
     * Un módulo posee muchas tareas
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tasks()
    {
        return $this->morphedByMany('App\Task', 'evaluation')->withPivot('evaluation');
    }
}
