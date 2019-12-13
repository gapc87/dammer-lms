<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Un usuario pertenece a un rol
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    /**
     * Un estudiante está enrolado en varios módulos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules()
    {
        return $this->belongsToMany('App\Module', 'students');
    }

    /**
     * Un profesor enseña módulos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teach()
    {
        return $this->hasMany('App\Module', 'teacher_id');
    }

    public function evaluatedTask()
    {
        return $this->hasOneThrough('App\EvaluatedTask',
            'App\Student',
            'user_id',
            'student_id',
            '',
            'user_id');
    }
}
