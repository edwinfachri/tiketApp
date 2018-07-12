<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'ticket';

    public function event() {
      return $this->hasMany('app\Event');
    }

    public function transaction() {
      return $this->belongsToMany('App\Transaction', 'transaction_ticket');
    }
}
