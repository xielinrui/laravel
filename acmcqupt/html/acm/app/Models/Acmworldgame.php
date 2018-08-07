<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Acmworldgame extends Model
{
    //
    use Notifiable;
    protected $table = "acmworldgames";

    protected $fillable = [
        'type','name','url','usezhinan',
    ];

    protected $hidden = [

    ];
}
