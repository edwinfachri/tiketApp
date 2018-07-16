<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketTransaction extends Model
{
    protected $table = 'ticket_transaction';

    public function ticket() {
      return $this->hasMany('app\Ticket');
    }

    public function transaction() {
      return $this->hasMany('app\Transaction');
    }
}
