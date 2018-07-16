<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketTransaction extends Model
{
    public function ticket()
    {
        return $this->belongsToMany('App\Ticket');
    }

    public function transaction()
    {
        return $this->belongsToMany('App\Transaction');
    }
}
