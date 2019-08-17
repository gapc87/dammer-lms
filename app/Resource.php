<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'title', 'resource'
    ];

    /**
     * Un recurso pertenece a un módulo
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function module()
    {
        return $this->morphToMany('App\Module', 'evaluation');
    }
}
