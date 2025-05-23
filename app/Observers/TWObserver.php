<?php

namespace App\Observers;

use App\TW;

class TWObserver
{
    /**
     * Handle the t w "created" event.
     *
     * @param  \App\TW  $tW
     * @return void
     */
    public function created(TW $tW)
    {
        //
    }

    /**
     * Handle the t w "updated" event.
     *
     * @param  \App\TW  $tW
     * @return void
     */
    public function updated(TW $tW)
    {
        //
        try{
            $tW->eventRecurringPatterns()->update([
                'last_event_at' => $tW->due_date,
                'next_event_at' => $tW->due_date
            ]);
        }catch(\Exception $e){
            //dd($e);
        }
    }

    /**
     * Handle the t w "deleted" event.
     *
     * @param  \App\TW  $tW
     * @return void
     */
    public function deleted(TW $tW)
    {
        //
    }

    /**
     * Handle the t w "restored" event.
     *
     * @param  \App\TW  $tW
     * @return void
     */
    public function restored(TW $tW)
    {
        //
    }

    /**
     * Handle the t w "force deleted" event.
     *
     * @param  \App\TW  $tW
     * @return void
     */
    public function forceDeleted(TW $tW)
    {
        //
    }
}
