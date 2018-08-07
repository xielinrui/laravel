<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Userlogin extends Model
{
    //
    use Notifiable;

    protected $table = "userlogins";

    protected $fillable = [
        'schoolid', 'ci','status'
    ];

    protected $hidden = [
        'ci'
    ];
}
