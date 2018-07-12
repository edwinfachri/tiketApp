<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    
    public function transaction() {
      return $this->hasMany('app\Transaction');
    }
}
