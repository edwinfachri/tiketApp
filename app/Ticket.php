<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'ticket';
    protected $fillable = ['price', 'quota'];

    public function event() {
      return $this->belongsTo('app\Event');
    }

    public function transaction() {
      return $this->belongsToMany('App\Transaction');
    }
}
