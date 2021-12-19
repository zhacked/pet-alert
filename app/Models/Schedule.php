<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $guarded = [];

   
    public function clientData(){
        return $this->hasOne('App\Models\User','id', 'client_id');
    }

      public function petData(){
        return $this->hasOne('App\Models\Pet', 'id', 'pet_id');
    }

    public function serviceData(){
        return $this->hasOne('App\Models\Service', 'id', 'service_id');
    }
 
    public function employeeData(){
        return $this->hasOne('App\Models\User','id', 'employee_id');
    }
}
