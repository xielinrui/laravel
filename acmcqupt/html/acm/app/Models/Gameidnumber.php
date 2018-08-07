<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Gameidnumber extends Model
{
    use Notifiable;

    protected $table = "gameidnumbers";

    protected $fillable = [
        'gameid','number',
    ];

    protected $hidden = [
        'gameid','number',
    ];
}
