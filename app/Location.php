<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'location';
    protected $fillable = ['name', 'city', 'country'];
    
    public function event() {
      return $this->hasMany('app\Event');
    }
}
