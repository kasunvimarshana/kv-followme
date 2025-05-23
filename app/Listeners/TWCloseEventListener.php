<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\TW;
use App\RecurringType;
use App\RecurringPattern;
use App\Events\TWCloseEvent;
use Mail;
use Carbon\Carbon;
use App\Jobs\SendTWCloseMailJob;

class TWCloseEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TWCloseEvent $event)
    {
        //
        $tWClone = clone $event->tW;
        
        try{
            $tWClone->eventRecurringPatterns()->delete();
            
            $emailJob = (new SendTWCloseMailJob($tWClone))->delay(Carbon::now()->addSeconds(10));
            dispatch($emailJob);
            
        }catch(\Exception $e){
            
        }
    }
}
