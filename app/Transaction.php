<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function event() {
      return $this->belongsTo('app\Event');
    }

    public function customer() {
      return $this->belongsTo('app\Customer');
    }

    public function ticketType() {
      return $this->belongsToMany('App\TicketType', 'transaction_ticket_type');
    }
}
