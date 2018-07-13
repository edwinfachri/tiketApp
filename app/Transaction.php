<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';

    public function event() {
      return $this->belongsTo('app\Ticket');
    }

    public function customer() {
      return $this->belongsTo('app\Customer');
    }
}
