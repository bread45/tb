<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\QuoteRequests,
    App\QuoteRequestSupplier,
    App\EventTypes,
    App\Notification;

use Carbon\Carbon;

class EventCompleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily hours to check all events (Quotes) if completed any event than status changed to completed event.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $QuoteRequests = QuoteRequests::where("main_type", "upcoming")->where('event_date', '<', Carbon::now()->format('Y-m-d'))->get();
        foreach ($QuoteRequests as $key => $q_req) {
            /* START : Event data get */
            $event_type = EventTypes::where('id', $q_req->event_type_id)->first();
            $event_message = 'Event Completed.';
            $event_title = $event_type->title . ' in ' . $q_req->location . ' event is completed.';
            /* END : Event data get */

            $QuoteRequestSupplier = QuoteRequestSupplier::where("quote_request_id", $q_req->id)->where("isQuote", "upcoming")->get();
            if (count($QuoteRequestSupplier) > 0) {
                foreach ($QuoteRequestSupplier as $key => $q_supplier) {
                    $q_supplier->update([
                        "isQuote" => "completed"
                    ]);
                    
                    /* START : Event notification send */
                    Notification::create([
                        'from_user_id' => $q_req->user_id,
                        'to_user_id' => $q_supplier->supplier_id,
                        'notification_type' => 'Event',
                        'title' => $event_title,
                        'message' => $event_message,
                        'url' => '',
                    ]);
                    /* END : Event notification send */
                }
            }
            $q_req->update([
                "main_type" => "completed"
            ]);

            /* START : Event notification send */
            Notification::create([
                'from_user_id' => $q_req->user_id,
                'to_user_id' => $q_req->user_id,
                'notification_type' => 'Event',
                'title' => $event_title,
                'message' => $event_message,
                'url' => '',
            ]);
            /* END : Event notification send */
        }
    }
}
