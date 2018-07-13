<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';

    public function location() {
      return $this->belongsTo('app\Location', 'location_id');
    }

    public function ticket() {
      return $this->hasOne('app\Ticket');
    }

    public function transaction() {
      return $this->hasMany('app\Transaction');
    }
}
