<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = "users";

    protected $fillable = [
        'name','schoolid', 'email', 'password','sex','xueyuan','zhuanye','shifouhuiyuan',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
