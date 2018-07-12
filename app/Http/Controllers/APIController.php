<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\Location;
use App\Schedule;
use App\Ticket;

class APIController extends Controller
{
    public function createEvent(Request $request) {
        $event_name = strtolower($_POST['event_name']);
        $event_age_limit = empty($_POST['event_age_limit'])?$_POST['event_age_limit']:'0';
        $location_name = strtolower($_POST['location_name']);
        $location_city = strtolower($_POST['location_city']);
        $location_country = strtolower($_POST['location_country']);
        $schedule_date_start = $_POST['schedule_date_start'];
        $schedule_date_finish = empty($_POST['schedule_date_finish'])?$_POST['schedule_date_finish']:$_POST['schedule_date_start'];

        $event = new Event;
        $event->name = $event_name;
        $event->age_limit = $event_age_limit;

        $location = Location::firstOrCreate([
          'name' => $location_name,
          'city' => $location_city,
          'country' => $location_country
        ]);

        $schedule = new Schedule;
        $schedule->date_start = $schedule_date_start;
        $schedule->date_finish = $schedule_date_finish;
        $schedule->save();

        $event->schedule()->associate($schedule);
        $event->location()->associate($location);
        $event->save();

        // return $event;
        return response()->json($event, 201);
    }

    public function createLocation(Request $request) {
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

        $event = Event::find($event_id);

        $ticket = new Ticket;
        $ticket->price = $ticket_price;
        $ticket->quota = $ticket_quota;

        $event->ticket()->save($ticket);
        return response()->json($ticket, 201);
    }

    public function getEvent() {

    }

    public function purchaseTicket() {

    }

    public function getTransactionDetail() {

    }
}
