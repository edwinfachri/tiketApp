<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';

    public function location() {
      return $this->belongsTo('app\Location', 'location_id');
    }

    public function schedule() {
      return $this->belongsTo('app\Schedule', 'schedule_id');
    }

    public function ticketType() {
      return $this->belongsTo('app\TicketType');
    }

    public function transaction() {
      return $this->hasMany('app\Transaction');
    }
}
