<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Peoplebaoming extends Model
{
    use Notifiable;

    protected $table = "peoplebaomings";

    protected $fillable = [
        'baomingid','schoolid','phone','email','xueyuan','zhuanye','duiming','gameid',
    ];

    protected $hidden = [

    ];
}
