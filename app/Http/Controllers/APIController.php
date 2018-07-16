<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Event;
use App\Location;
use App\Ticket;
use App\Transaction;
use App\Customer;
use App\TicketTransaction;

class APIController extends Controller
{
    public function createEvent(Request $request) {
        # Validation for each parameter
        $validator = Validator::make($request->all(), [
          'event_name' => 'required|max:255',
          'event_age_limit' => 'nullable|digits_between:0,100',
          'location_id' => 'required|numeric',
          'event_date_start' => 'required|date',
          'event_date_finish' => 'nullable|date',
        ]);

        # Return error message when it fails
        if ($validator->fails()) {
          return response()->json(['error'=>'Validation Error'], 406);
        }

        # Initialize each parameter
        # Some nullable parameters will be set by its default value
        $event_name = $_POST['event_name'];
        $event_age_limit = empty($_POST['event_age_limit'])?$_POST['event_age_limit']:'0';
        $location_id = $_POST['location_id'];
        $event_date_start = $_POST['event_date_start'];
        $event_date_finish = isset($_POST['event_date_finish'])?$_POST['event_date_finish']:$_POST['event_date_start'];

        # Create new Event model and assign each parameter to its attributes
        $event = new Event;
        $event->name = $event_name;
        $event->age_limit = $event_age_limit;
        $event->date_start = $event_date_start;
        $event->date_finish = $event_date_finish;

        # Find the location of event and return failed if not found
        $location = Location::findOrFail($location_id);

        # Associate location to the event to save its relational model
        $event->location()->associate($location);
        $event->save();

        return response()->json(['success'=>'Event Successfully Created'], 201);
    }

    public function createLocation(Request $request) {
        # Validation for each parameter
        $validator = Validator::make($request->all(), [
          'location_name' => 'required|string|min:3|max:255',
          'location_city' => 'required|string|min:3|max:255',
          'location_country' => 'required|string|min:3|max:255',
        ]);

        # Return error message when it fails
        if ($validator->fails()) {
          return response()->json(['error'=>'Validation Error'], 406);
        }

        # Initialize each parameter
        $location_name = strtolower($_POST['location_name']);
        $location_city = strtolower($_POST['location_city']);
        $location_country = strtolower($_POST['location_country']);

        # Search the location in case it has been in database or create if not found
        $location = Location::firstOrCreate([
          'name' => $location_name,
          'city' => $location_city,
          'country' => $location_country
        ]);

        return response()->json(['success'=>'Location Successfully Created'], 201);
    }

    public function createTicket(Request $request) {
        # Validation for each parameter
        $validator = Validator::make($request->all(), [
          'event_id' => 'required|numeric',
          'ticket_price' => 'required|numeric',
          'ticket_quota' => 'required|numeric',
        ]);

        # Return error message when it fails
        if ($validator->fails()) {
          return response()->json(['error'=>'Validation Error'], 406);
        }

        # Initialize each parameter
        $event_id = $_POST['event_id'];
        $ticket_price = $_POST['ticket_price'];
        $ticket_quota = $_POST['ticket_quota'];

        # Search event in database, return fail if not found
        $event = Event::findOrFail($event_id);

        # Create new ticket model
        $ticket = new Ticket([
          'price' => $ticket_price,
          'quota' => $ticket_quota
        ]);

        # Save event and ticket to its relational model
        $event->ticket()->save($ticket);

        return response()->json(['success'=>'Ticket Successfully Created'], 201);
    }

    public function getEvent(Request $request) {
        # Validation for each parameter
        $validator = Validator::make($request->all(), [
          'event_id' => 'required|numeric',
        ]);

        # Return error message when it fails
        if ($validator->fails()) {
          return response()->json(['error'=>'Validation Error'], 406);
        }

        # Initialize parameter
        $event_id = $_GET['event_id'];

        # Create new location model instance
        $location = new Location;

        # Select event join to location table, return fail if not found
        $event = Event::where('id',$event_id)->first(['name', 'age_limit', 'date_start', 'date_finish']);
        $location = Event::findOrFail($event_id)->location->first(['name', 'city', 'country']);
        $event['location'] = $location;
        return response()->json($event, 200);
    }

    public function purchaseTicket(Request $request) {
        # Validation for each parameter
        $validator = Validator::make($request->all(), [
          'customer_id' => 'required|numeric',
          'transaction_count' => 'required|numeric',
        ]);

        # Return error message when it fails
        if ($validator->fails()) {
          return response()->json(['error'=>'Validation Error'], 406);
        }

        # Initialize some parameters
        # Number of distinct ticket type bought
        $transaction_count = (int) $_POST['transaction_count'];
        $customer = $_POST['customer_id'];

        # Initialize temporary variable to check the consistency of event id
        $temp_event_id = '';

        # Check whether the transaction associate with one event
        # and whether the ticket is available
        for ($i = 1; $i <= $transaction_count; $i++) {

            # Validation for each parameter
            $validator = Validator::make($request->all(), [
              'transaction_ticket_id_'.($i) => 'required|numeric',
              'transaction_quantity_'.($i) => 'required|numeric',
            ]);

            # Return error message when it fails
            if ($validator->fails()) {
              return response()->json(['error'=>'Validation Error'], 406);
            }

            # Initialize some parameters
            $transaction_ticket_id = $_POST['transaction_ticket_id_'.($i)];
            $transaction_quantity = (int) $_POST['transaction_quantity_'.($i)];

            # Find ticket, return fail if not found
            $ticket = Ticket::findOrFail($transaction_ticket_id);

            # Save the event id since one transaction can only be assiciated to one Event
            if ($temp_event_id == '') {
              $temp_event_id = $ticket->event_id;
            }

            # Check whether the event id is the same as previous transaction unless cancel the transaction
            if ($temp_event_id != $ticket->event_id) {
                return response()->json(['error'=>'One purchase can only have one event'], 406);
            }

            # Check whether the ticket is available
            if ($ticket->quota < $transaction_quantity) {
                $event_name = Event::findOrFail($ticket->event_id)->name;
                return response()->json(['error'=>'The ticket for '.$event_name.' is '.$ticket->quota.' left'], 406);
            }
        }

        # Create new transaction instance and associate it with the customer
        $transaction = new Transaction;
        $customer = Customer::findOrFail($customer)->id;
        $transaction->customer()->associate($customer);
        $transaction->save();

        # Save the transactions
        for ($i = 1; $i <= $transaction_count; $i++) {

            # Initialize each parameter
            $transaction_ticket_id = $_POST['transaction_ticket_id_'.($i)];
            $transaction_quantity = (int) $_POST['transaction_quantity_'.($i)];

            # Search ticket, return fail if not found
            $ticket = Ticket::findOrFail($transaction_ticket_id);

            # Attach the transaction to ticket to make TicketTransaction model
            $ticket->transaction()->attach($transaction->id,
            [
              'quantity' => $transaction_quantity,
              'total' => $ticket->price * $transaction_quantity,
              'created_at' => $transaction->created_at,
              'updated_at' => $transaction->created_at
            ]);

            # Distract the quota of the ticket
            $ticket->quota -= $transaction_quantity;
            $ticket->save();
        }

        # Return transaction detail
        $ticket_transaction = TicketTransaction::where('transaction_id', $transaction->id)->get();

        # Initialize the total charge
        $total = 0;

        # Count the total and subtotal charge for the tickets
        foreach ($ticket_transaction as $key=>$value) {
          $ticket_transaction[$key]['ticket'] = Ticket::where('id', $value->ticket_id)->first(['price', 'event_id']);
          $ticket_transaction[$key]['event'] = Event::where('id', $ticket_transaction[$key]['ticket']->event_id)
                                        ->first(['name']);
          $total += $value->total;
        }
        $ticket_transaction['grand_total'] = $total;

        return response()->json([
          'details' => $ticket_transaction,
          'success'=>'Transaction succeeded'
        ], 201);
    }

    public function getTransactionDetail(Request $request) {
        # Validation for each parameter
        $validator = Validator::make($request->all(), [
          'transaction_id' => 'required|numeric',
        ]);

        # Return error message when it fails
        if ($validator->fails()) {
          return response()->json(['error'=>'Validation Error'], 406);
        }

        # Initialize parameter
        $transaction_id = $_GET['transaction_id'];

        # Find collection of transaction with the same uid
        # Transactions with the same uid are purchased in one receipt
        $ticket_transaction = TicketTransaction::where('transaction_id', $transaction_id)->get();

        # Initialize the total charge
        $total = 0;

        # Count the total and subtotal charge for the tickets
        foreach ($ticket_transaction as $key=>$value) {
          $ticket_transaction[$key]['ticket'] = Ticket::where('id', $value->ticket_id)->first(['price', 'event_id']);
          $ticket_transaction[$key]['event'] = Event::where('id', $ticket_transaction[$key]['ticket']->event_id)
                                        ->first(['name', 'location_id', 'age_limit', 'date_start', 'date_finish']);
          $ticket_transaction[$key]['location'] = Location::where('id', $ticket_transaction[$key]['event']->location_id)
                                        ->first(['name', 'city', 'country']);
          $total += $value->total;
        }
        $ticket_transaction['grand_total'] = $total;

        return response()->json($ticket_transaction, 200);
    }

    public function createCustomer(Request $request) {
        # Validation for each parameter
        $validator = Validator::make($request->all(), [
          'customer_name' => 'required|string|max:255',
          'customer_birth_date' => 'required|date',
          'customer_city' => 'required|string|min:5',
          'customer_address' => 'required|string|min:5',
          'customer_phone' => 'required|numeric',
          'customer_email' => 'required|email'
        ]);

        # Return error message when it fails
        if ($validator->fails()) {
          return response()->json(['error'=>'Validation Error'], 406);
        }

        # Initialize each parameter
        $customer_name = strtolower($_POST['customer_name']);
        $customer_birth_date = $_POST['customer_birth_date'];
        $customer_city = strtolower($_POST['customer_city']);
        $customer_address = strtolower($_POST['customer_address']);
        $customer_phone = $_POST['customer_phone'];
        $customer_email = strtolower($_POST['customer_email']);

        # Create new Customer model and assign each parameter to its attributes
        $customer = new Customer;
        $customer->name = $customer_name;
        $customer->birth_date = $customer_birth_date;
        $customer->city = $customer_city;
        $customer->address = $customer_address;
        $customer->phone = $customer_phone;
        $customer->email = $customer_email;
        $customer->save();

        return response()->json(['success'=>'User Successfully Created'], 201);
    }
}
