<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use DB;
use \Response;

use App\RecurringType;
use App\RecurringPattern;
use App\Helpers\NotifyHelper;

class NotificationScheduleController extends Controller
{
    //
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $recurringTypeOwner = new RecurringType();
        $recurringPatternOwner = new RecurringPattern();
        $recurringTypeHOD = new RecurringType();
        $recurringPatternHOD = new RecurringPattern();

        try{
            $recurringTypeOwner = RecurringType::where('name','tw-owner')->first();
            $recurringPatternOwner = $recurringTypeOwner->recurringPatterns()->first();
            $recurringTypeHOD = RecurringType::where('name','tw-owner-hod')->first();
            $recurringPatternHOD = $recurringTypeHOD->recurringPatterns()->first();
        }catch(\Exception $e){}

        if(view()->exists('notification_schedule_create')){
            return View::make('notification_schedule_create', array(
                'recurringTypeOwner' => $recurringTypeOwner,
                'recurringPatternOwner' => $recurringPatternOwner,
                'recurringTypeHOD' => $recurringTypeHOD,
                'recurringPatternHOD' => $recurringPatternHOD
            ));
        }
    }

    public function storeScheduleTWOwner(Request $request){
        $is_active = $request->get('is_active');
        $is_active = (empty($is_active)) ? false : true;
        $is_recurring = $request->get('is_recurring');
        $is_recurring = (empty($is_recurring)) ? false : true;

        $recurringTypeData = array(
            'is_active' => $is_active
        );

        $recurringPatternData = array(
            'is_recurring' => $is_recurring,
            'day' => $request->get('day'),
            'day_of_week' => $request->get('day_of_week'),
            'month' => $request->get('month')
        );

        // Start transaction!
        DB::beginTransaction();

        try {
            // Validate, then create if valid
            $recurringType = RecurringType::where('name','tw-owner')->first();
            $recurringPattern = $recurringType->recurringPatterns()->first();
            $eventRecurringPatterns = $recurringType->eventRecurringPatterns();

            $recurringType->update( $recurringTypeData );
            $recurringPattern->update( $recurringPatternData );
            $eventRecurringPatterns->update( $recurringPatternData );

        }catch(\Exception $e){

            DB::rollback();
            $data = array(
                'title' => 'error',
                'text' => 'error',
                'type' => 'warning',
                'timer' => 3000
            );

            return redirect()->back()->withInput();

        }

        DB::commit();

        $data = array(
            'title' => 'success',
            'text' => 'success',
            'type' => 'success',
            'timer' => 3000
        );

        NotifyHelper::flash(
            $data['title'],
            $data['type'], [
            'timer' => $data['timer'],
            'text' => $data['text'],
        ]);

        return redirect()->route('notificationSchedule.create');
    }

    public function storeScheduleHOD(Request $request){
        $is_active = $request->get('is_active');
        $is_active = (empty($is_active)) ? false : true;
        $is_recurring = $request->get('is_recurring');
        $is_recurring = (empty($is_recurring)) ? false : true;

        $recurringTypeData = array(
            'is_active' => $is_active
        );

        $recurringPatternData = array(
            'is_recurring' => $is_recurring,
            'day' => $request->get('day'),
            'day_of_week' => $request->get('day_of_week'),
            'month' => $request->get('month')
        );

        // Start transaction!
        DB::beginTransaction();

        try {
            // Validate, then create if valid
            $recurringType = RecurringType::where('name','tw-owner-hod')->first();
            $recurringPattern = $recurringType->recurringPatterns()->first();
            $eventRecurringPatterns = $recurringType->eventRecurringPatterns();

            $recurringType->update( $recurringTypeData );
            $recurringPattern->update( $recurringPatternData );
            $eventRecurringPatterns->update( $recurringPatternData );

        }catch(\Exception $e){

            DB::rollback();
            $data = array(
                'title' => 'error',
                'text' => 'error',
                'type' => 'warning',
                'timer' => 3000
            );

            return redirect()->back()->withInput();

        }

        DB::commit();

        $data = array(
            'title' => 'success',
            'text' => 'success',
            'type' => 'success',
            'timer' => 3000
        );

        NotifyHelper::flash(
            $data['title'],
            $data['type'], [
            'timer' => $data['timer'],
            'text' => $data['text'],
        ]);

        return redirect()->route('notificationSchedule.create');
    }
}
