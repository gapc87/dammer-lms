<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 'statement', 'start_at', 'end_at'
    ];

    /**
     * Una tarea pertenece a un módulo
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function module()
    {
        return $this->morphToMany('App\Module', 'evaluation');
    }
}
