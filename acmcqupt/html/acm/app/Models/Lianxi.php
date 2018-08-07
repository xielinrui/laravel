<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Lianxi extends Model
{
    //
    use Notifiable;
    protected $table = "lianxis";
    protected $fillable = [
        'type','name','url','usezhinan',
    ];

    protected $hidden = [

    ];
}
