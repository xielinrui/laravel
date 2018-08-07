<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Baoming extends Model
{
    use Notifiable;

    protected $table = "baomings";

    protected $fillable = [
        'number',
    ];

    protected $hidden = [
        'number',
    ];
}
