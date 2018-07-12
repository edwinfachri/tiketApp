<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    public function event() {
      return $this->hasMany('app\Event');
    }

    public function transaction() {
      return $this->belongsToMany('App\Transaction', 'transaction_ticket_type');
    }
}
