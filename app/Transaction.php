<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';

    public function event() {
      return $this->belongsTo('app\Event');
    }

    public function customer() {
      return $this->belongsTo('app\Customer');
    }

    public function ticket() {
      return $this->belongsToMany('App\Ticket', 'transaction_ticket');
    }
}
