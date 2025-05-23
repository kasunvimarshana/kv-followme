<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\TWUpdateEvent;
use App\TW;
use Mail;
use Carbon\Carbon;
use App\Jobs\SendTWUpdateMailJob;

class TWUpdateEventListener
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
    public function handle(TWUpdateEvent $event)
    {
        //
        $tWClone = clone $event->tW;
        
        try{
            
            /*
            $twUsers = $tWClone->twUsers;
            
            foreach($twUsers as $key=>$value){
                //Mail::to($value->own_user)->send($email);
                $toUser = $value;
                //$toUser = $value->own_user;
                $emailJob = (new SendTWUpdateMailJob($tWClone, $toUser))->delay(Carbon::now()->addSeconds(10));
                dispatch($emailJob);
            }
            */
            
            $emailJob = (new SendTWUpdateMailJob($tWClone))->delay(Carbon::now()->addSeconds(10));
            dispatch($emailJob);
            
        }catch(\Exception $e){
            
        }
    }
}
