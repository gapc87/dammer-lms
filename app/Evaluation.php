<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'module_id', 'evaluation', 'evaluation_id', 'evaluation_type'
    ];
}
