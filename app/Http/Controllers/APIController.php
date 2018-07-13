<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\Location;
use App\Ticket;
use App\Transaction;
use App\Customer;

class APIController extends Controller
{
    public function createEvent() {
        $event_name = strtolower($_POST['event_name']);
        $event_age_limit = empty($_POST['event_age_limit'])?$_POST['event_age_limit']:'0';
        $location_id = $_POST['location_id'];
        $event_date_start = $_POST['event_date_start'];
        $event_date_finish = empty($_POST['event_date_finish'])?$_POST['event_date_finish']:$_POST['event_date_start'];

        $event = new Event;
        $event->name = $event_name;
        $event->age_limit = $event_age_limit;
        $event->date_start = $event_date_start;
        $event->date_finish = $event_date_finish;
        $location = Location::findOrFail($location_id);

        $event->location()->associate($location);
        $event->save();

        return response()->json($event, 201);
    }

    public function createLocation() {
        $location_name = strtolower($_POST['location_name']);
        $location_city = strtolower($_POST['location_city']);
        $location_country = strtolower($_POST['location_country']);

        $location = Location::firstOrCreate([
          'name' => $location_name,
          'city' => $location_city,
          'country' => $location_country
        ]);

        return response()->json($location, 201);
    }

    public function createTicket() {
        $event_id = $_POST['event_id'];
        $ticket_price = $_POST['ticket_price'];
        $ticket_quota = $_POST['ticket_quota'];

        $event = Event::findOrFail($event_id);

        $ticket = new Ticket([
          'price' => $ticket_price,
          'quota' => $ticket_quota
        ]);
        $event->ticket()->save($ticket);

        return response()->json($ticket, 201);
    }

    public function getEvent() {
        $event_id = $_GET['event_id'];
        $location = new Location;
        $event = Event::with(['location'])->findOrFail($event_id);
        return response()->json($event, 201);
    }

    public function purchaseTicket() {
        # Number of distinct ticket type bought
        $transaction_count = (int) $_POST['transaction_count'];

        # Array of ticket ids
        $arr_transaction_ticket_id = [];

        $customer = $_POST['customer_id'];

        $temp_event_id = '';

        # Check whether the transaction associate with one event and whether the ticket is available
        for ($i = 1; $i <= $transaction_count; $i++) {
            $transaction_ticket_id = $_POST['transaction_ticket_id_'.($i)];
            $transaction_quantity = (int) $_POST['transaction_quantity_'.($i)];
            $ticket = Ticket::findOrFail($transaction_ticket_id);
            # Save the event id since one transaction can only be assiciated to one Event
            if ($temp_event_id == '') {
              $temp_event_id = $ticket->event_id;
            }

            # Check whether the event id is the same as previous transaction unless cancel the transaction
            if ($temp_event_id != $ticket->event_id) {
                return response()->json(['error'=>'One purchase can only have one event'], 201);
            }

            # Check whether the ticket is available
            if ($ticket->quota < $transaction_quantity) {
                $event_name = Event::findOrFail($ticket->event_id)->name;
                return response()->json(['error'=>'The ticket for '.$event_name.' is '.$ticket->quota.' left'], 201);
            }
        }

        # Generate Unique ID to group the transaction
        $uid = md5(uniqid(rand(), true));

        for ($i = 1; $i <= $transaction_count; $i++) {
            $transaction_ticket_id = $_POST['transaction_ticket_id_'.($i)];
            $transaction_quantity = (int) $_POST['transaction_quantity_'.($i)];

            $transaction = new Transaction;
            $customer = Customer::findOrFail($customer)->id;
            $ticket = Ticket::findOrFail($transaction_ticket_id);

            # Distract the quota of the ticket
            $ticket->quota -= $transaction_quantity;
            $ticket->save();

            $transaction->customer()->associate($customer);
            $transaction->ticket()->associate($ticket);

            $transaction->quantity = $transaction_quantity;
            $transaction->uid = $uid;

            $transaction->save();
        }
    }

    public function getTransactionDetail() {
        $transaction_id = $_GET['transaction_id'];
        $transaction = Transaction::find($transaction_id);
        $transactions = Transaction::where([
          'uid'=>$transaction->uid
        ])->get();

        $total = 0;
        foreach ($transactions as $key=>$value) {
          $transactions[$key]['ticket'] = Ticket::find($value->ticket_id);
          $transactions[$key]['event'] = Event::find($transactions[$key]['ticket']->event_id);
          $transactions[$key]['subtotal'] = $value->quantity * $transactions[$key]['ticket']->price;
          $total += $transactions[$key]['subtotal'];
        }
        $transactions['total'] = $total;

        return response()->json([$transactions], 201);
    }
}
