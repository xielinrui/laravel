<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //
    use Notifiable;

    protected $table = "admins";

    protected $fillable = [
       'username','password'
    ];

    protected $hidden = [
        'password',
    ];
}
