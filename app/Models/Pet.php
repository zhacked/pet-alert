<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function clientData(){
        return $this->hasOne('App\Models\Client','id', 'client_id');
    }

}