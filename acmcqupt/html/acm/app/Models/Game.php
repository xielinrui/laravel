<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use Notifiable;

    protected $table = "games";

    protected $fillable = [
        'gamename','fuzeren','fuzerenphone','gametime','address','origanizetion','guize','number','news','resultid','baomingid','gamestatus',];

    protected $hidden = [

    ];
}
