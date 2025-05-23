<?php

namespace App\Listeners;

use App\Events\TWCreateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\TW;
use App\RecurringType;
use App\RecurringPattern;
use Mail;
use Carbon\Carbon;
use App\Jobs\SendTWCreateMailJob;

class TWCreateEventListener
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
     * @param  TWCreateEvent  $event
     * @return void
     */
    public function handle(TWCreateEvent $event)
    {
        //
        $tWClone = clone $event->tW;
        $recurringTypeOwner = new RecurringType();
        $recurringPatternOwner = new RecurringPattern();
        $recurringTypeHOD = new RecurringType();
        $recurringPatternHOD = new RecurringPattern();
        
        try{
            $recurringTypeOwner = RecurringType::where('name','tw-owner')->first();
            $recurringPatternOwner = $recurringTypeOwner->recurringPatterns()->first();
            $recurringTypeHOD = RecurringType::where('name','tw-owner-hod')->first();
            $recurringPatternHOD = $recurringTypeHOD->recurringPatterns()->first();
            
            $newEventRecurringPatternsTWOwner = $tWClone->eventRecurringPatterns()->create(array(
                'is_visible' => true,
                'is_recurring' => $recurringPatternOwner->is_recurring,
                'recurring_type_id' => $recurringTypeOwner->id,
                'minute' => $recurringPatternOwner->minute,
                'hour' => $recurringPatternOwner->hour,
                'day' => $recurringPatternOwner->day,
                'day_of_month' => $recurringPatternOwner->day_of_month,
                'month' => $recurringPatternOwner->month,
                'day_of_week' => $recurringPatternOwner->day_of_week,
                'year' => $recurringPatternOwner->year,
                'has_max_number_of_occures' => $recurringPatternOwner->has_max_number_of_occures,
                'has_seperation_count' => $recurringPatternOwner->has_seperation_count,
                'seperation_count' => $recurringPatternOwner->seperation_count
            ));
            
            $newEventRecurringPatternsHOD = $tWClone->eventRecurringPatterns()->create(array(
                'is_visible' => true,
                'is_recurring' => $recurringPatternHOD->is_recurring,
                'recurring_type_id' => $recurringTypeHOD->id,
                'minute' => $recurringPatternHOD->minute,
                'hour' => $recurringPatternHOD->hour,
                'day' => $recurringPatternHOD->day,
                'day_of_month' => $recurringPatternHOD->day_of_month,
                'month' => $recurringPatternHOD->month,
                'day_of_week' => $recurringPatternHOD->day_of_week,
                'year' => $recurringPatternHOD->year,
                'has_max_number_of_occures' => $recurringPatternHOD->has_max_number_of_occures,
                'has_seperation_count' => $recurringPatternHOD->has_seperation_count,
                'seperation_count' => $recurringPatternHOD->seperation_count
            ));
            
            /*
            $emailJob = (new SendTWCreateMailJob($tWClone))->delay(Carbon::now()->addSeconds(10));
            dispatch($emailJob);
            */
            
            /*
            $twUsers = $tWClone->twUsers;
            
            foreach($twUsers as $key=>$value){
                //Mail::to($value->own_user)->send($email);
                $toUser = $value;
                //$toUser = $value->own_user;
                $emailJob = (new SendTWCreateMailJob($tWClone, $toUser))->delay(Carbon::now()->addSeconds(10));
                dispatch($emailJob);
            }
            */
            $emailJob = (new SendTWCreateMailJob($tWClone))->delay(Carbon::now()->addSeconds(10));
            dispatch($emailJob);
            
        }catch(\Exception $e){
            
        }
    }
}
