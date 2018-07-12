<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function location() {
      return $this->belongsTo('app\Location');
    }

    public function schedule() {
      return $this->belongsTo('app\Schedule');
    }

    public function ticketType() {
      return $this->belongsTo('app\TicketType');
    }

    public function transaction() {
      return $this->hasMany('app\Transaction');
    }
}
