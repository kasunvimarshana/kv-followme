<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\TW;
use DB;
use App\Login;
use App\Enums\TWStatusEnum;
use App\Enums\TWMetaEnum;
use App\Enums\RecurringTypeEnum;
use App\TWUser;
use App\User;
use App\TWInfo;
use App\UserAttachment;
use Storage;
use Carbon\Carbon;

use Mail;
use App\Jobs\SendTWDevDateReachMailJob;
use App\Jobs\SendTWOwnerHODTWDevDateReachMailJob;

class SendTWEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twemail:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send TW Emails';

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
        //
        $loginUser = Login::getUserData();
        $date_today = Carbon::now()->format('Y-m-d H:i:s');
        $date_from = Carbon::now()->subMonths(5)->format('Y-m-d');
        $date_to = Carbon::now()->format('Y-m-d');
        $start_date_from = $date_from;
        $start_date_to = $date_to;
        
        $tWObjectArray = TW::where('is_visible','=',true)
            ->where(function($query) use ($date_today){
                $query->whereDate('due_date','<',$date_today);
                $query->where(function($query){
                    $query->where('is_done','=',false);
                    $query->orWhereNull('is_done');
                });
            })
            ->get();
        
        //dd($date_today_as_obj = Carbon::createFromFormat('Y-m-d H:i:s', $date_today));
        
        if($tWObjectArray){
            
            foreach($tWObjectArray as $keyTWObject=>$valueTWObject){
                
                $eventRecurringPatternsArray = $valueTWObject->eventRecurringPatterns;
                if($eventRecurringPatternsArray){
                    
                    foreach($eventRecurringPatternsArray as $keyEventRecurringPattern=>$valueEventRecurringPattern){
                        
                        //get last event date
                        $last_event_at = $valueEventRecurringPattern->last_event_at;
                        if( (empty($last_event_at)) ){
                            //$last_event_at = $valueEventRecurringPattern->created_at;
                            $last_event_at = $valueTWObject->due_date;
                        }
                        
                        //get other data
                        $recurringType = $valueEventRecurringPattern->recurringType;
                        $is_recurring = $valueEventRecurringPattern->is_recurring;
                        $recurring_type_id = $valueEventRecurringPattern->recurring_type_id;
                        $day = $valueEventRecurringPattern->day;
                        $day_of_week = $valueEventRecurringPattern->day_of_week;
                        $month = $valueEventRecurringPattern->month;
                        $is_active = $recurringType->is_active;
                        $is_send = false;
                        $send_date = null;
                        
                        $date_today_as_obj = Carbon::createFromFormat('Y-m-d H:i:s', $date_today);
                        $last_event_at_as_obj = Carbon::createFromFormat('Y-m-d H:i:s', $last_event_at);
                        $next_event_at_as_obj = Carbon::createFromFormat('Y-m-d H:i:s', $last_event_at);
                        
                        $date_today_as_obj = $date_today_as_obj->startOfDay();
						$last_event_at_as_obj = $last_event_at_as_obj->startOfDay();
						$next_event_at_as_obj = $next_event_at_as_obj->startOfDay();
                        
                        //if is active
                        if($is_active){
                            
                            //day
                            if( (!empty($day)) ){
                                $next_event_at_as_obj = $next_event_at_as_obj->addDays($day);
                            }
                            //monts
                            if( (!empty($month)) ){
                                $next_event_at_as_obj = $next_event_at_as_obj->addMonths($month);
                            }
                            //day of week
                            if( (!empty($day_of_week)) ){
                                switch($day_of_week){
                                    case 1:
                                        $next_event_at_as_obj = $next_event_at_as_obj->next(Carbon::MONDAY);
                                        break;
                                    case 2:
                                        $next_event_at_as_obj = $next_event_at_as_obj->next(Carbon::TUESDAY);
                                        break;
                                    case 3:
                                        $next_event_at_as_obj = $next_event_at_as_obj->next(Carbon::WEDNESDAY);
                                        break;
                                    case 4:
                                        $next_event_at_as_obj = $next_event_at_as_obj->next(Carbon::THURSDAY);
                                        break;
                                    case 5:
                                        $next_event_at_as_obj = $next_event_at_as_obj->next(Carbon::FRIDAY);
                                        break;
                                    case 6:
                                        $next_event_at_as_obj = $next_event_at_as_obj->next(Carbon::SATURDAY);
                                        break;
                                    case 7:
                                        $next_event_at_as_obj = $next_event_at_as_obj->next(Carbon::SUNDAY);
                                        break;
                                    default:
                                        break;
                                }
                            }
                            
                            //diff
                            //$dayDifferent = $date_today_as_obj->diffInMinutes($next_event_at_as_obj, false);
                            //$dayDifferent = $date_today_as_obj->diffInHours($next_event_at_as_obj, false);
                            //$dayDifferent = $date_today_as_obj->diffInDays($next_event_at_as_obj, false);
                            $dayDifferent = $date_today_as_obj->diffInDays($next_event_at_as_obj, false);
                            
                            //check different
                            if( ($dayDifferent < 0) ){
                                $eventRecurringPatternData = array(
                                    'last_event_at' => $next_event_at_as_obj->format('Y-m-d'),
                                    'next_event_at' => $next_event_at_as_obj->format('Y-m-d')
                                );
                                
                                /*
                                $emailJob = (new SendTWDevDateReachMailJob($valueTWObject))->delay(Carbon::now()->addSeconds(10));
                                dispatch($emailJob);
                                */
                                
                                if($recurring_type_id == RecurringTypeEnum::TW_OWNER){
                                    
                                    /*
                                    $twUsers = $valueTWObject->twUsers;
                                    foreach($twUsers as $key=>$value){
                                        //Mail::to($value->own_user)->send($email);
                                        $toUser = $value;
                                        //$toUser = $value->own_user;
                                        try{
                                            $emailJob = (new SendTWDevDateReachMailJob($valueTWObject, $toUser))->delay(Carbon::now()->addSeconds(10));
                                            dispatch($emailJob);
                                        }catch(\Exception $e){
                                            //dd($e);
                                        }
                                    }
                                    */
                                    try{
                                        $emailJob = (new SendTWDevDateReachMailJob($valueTWObject))->delay(Carbon::now()->addSeconds(10));
                                        dispatch($emailJob);
                                    }catch(\Exception $e){
                                        //dd($e);
                                    }
                                    
                                }else if($recurring_type_id == RecurringTypeEnum::TW_OWNER_HOD){
                                        try{
                                            $emailJob = (new SendTWOwnerHODTWDevDateReachMailJob($valueTWObject))->delay(Carbon::now()->addSeconds(10));
                                            dispatch($emailJob);
                                        }catch(\Exception $e){
                                            //dd($e);
                                        }
                                }
                                
                                $valueEventRecurringPattern->update( $eventRecurringPatternData );
                                $valueEventRecurringPattern->increment('number_of_occures', 1);
                            }
                            
                        }
                        
                    }
                    
                }
                
            }
            
        }
    }
}
