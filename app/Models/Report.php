<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function clientData(){
        return $this->hasOne('App\Models\Client','id', 'client_id');
    }
    public function petData(){
        return $this->hasOne('App\Models\Pet','id', 'pet_id');
    }
    public function employeeData(){
        return $this->hasOne('App\Models\Employees','id', 'pet_id');
    }

}
