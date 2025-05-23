<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\TWInfoCreateEvent;
use App\TWInfo;
use Mail;
use Carbon\Carbon;
use App\Jobs\SendTWInfoCreateMailJob;

class TWInfoCreateEventListener
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
    public function handle(TWInfoCreateEvent $event)
    {
        //
        $tWInfoClone = clone $event->tWInfo;
        
        try{
            
            /*
            $tW = $tWInfoClone->tw;
            
            $twUsers = $tW->twUsers;
            $createdUser = $tWInfoClone->createdUser();
            
            foreach($twUsers as $key=>$value){
                //Mail::to($value->own_user)->send($email);
                $toUser = $value;
                //$toUser = $value->own_user;
                $emailJob = (new SendTWInfoCreateMailJob($tWInfoClone, $tW, $toUser))->delay(Carbon::now()->addSeconds(10));
                dispatch($emailJob);
            }
            */
            $emailJob = (new SendTWInfoCreateMailJob($tWInfoClone))->delay(Carbon::now()->addSeconds(10));
            dispatch($emailJob);
            
        }catch(\Exception $e){
            
        }
        
    }
}
