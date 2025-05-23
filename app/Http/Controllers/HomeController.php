<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use \Response;

use App\TW;
use App\Login;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    //
    public function __construct(){
        
    }
    
    public function index(){
        $loginUser = Login::getUserData();
        $date_today = Carbon::now()->format('Y-m-d');
        $date_from = Carbon::now()->subMonths(5)->format('Y-m-d');
        $date_to = Carbon::now()->format('Y-m-d');
        $start_date_from = $date_from;
        $start_date_to = $date_to;
        
        $twAllCount = TW::where('is_visible','=',true)
            ->whereDate('start_date','>=',$start_date_from)
            ->whereDate('start_date','<=',$start_date_to)
            ->whereHas('twUsers', function($query) use ($loginUser){
                $query->where('own_user','=',$loginUser->mail);
            })
            ->count();
        
        $twTodayCount = TW::where('is_visible','=',true)
            ->where(function($query){
                $query->where('is_done','=',false);
                $query->orWhereNull('is_done');
            })
            ->whereDate('due_date','=',$date_today)
            ->whereHas('twUsers', function($query) use ($loginUser){
                $query->where('own_user','=',$loginUser->mail);
            })
            ->count();
        
        $twTodayCreatedCount = TW::where('is_visible','=',true)
            ->where('created_user','=',$loginUser->mail)
            ->whereDate('created_at','=',$date_today)
            ->count();
        
        $twCompletedCount = TW::where('is_visible','=',true)
            ->where('is_done','=',true)
            ->whereDate('start_date','>=',$start_date_from)
            ->whereDate('start_date','<=',$start_date_to)
            ->whereHas('twUsers', function($query) use ($loginUser){
                $query->where('own_user','=',$loginUser->mail);
            })
            ->count();
        
        $twFailCount = TW::where('is_visible','=',true)
            ->where(function($query) use ($date_today){
                $query->where(function($query){
                    $query->whereNotNull('done_date');
                    $query->where(DB::raw('DATE(due_date)'),'<',DB::raw('DATE(done_date)'));
                });
                $query->orWhere(function($query) use ($date_today){
                    $query->whereDate('due_date','<',$date_today);
                    $query->where(function($query){
                        $query->where('is_done','=',false);
                        $query->orWhereNull('is_done');
                    });
                });
            })
            ->whereDate('start_date', '>=', $start_date_from)
            ->whereDate('start_date', '<=', $start_date_to)
            ->whereHas('twUsers', function($query) use ($loginUser){
                $query->where('own_user','=',$loginUser->mail);
            })
            ->count();
        
        $twInprogressCount = TW::where('is_visible','=',true)
            ->where(function($query){
                $query->where('is_done','=',false);
                $query->orWhereNull('is_done');
            })
            ->where(function($query) use ($date_today){
                //$query->whereRaw('due_date >= done_date');
                $query->orWhereDate('due_date','>=',$date_today);
            })
            ->whereDate('start_date','>=', $start_date_from)
            ->whereDate('start_date','<=', $start_date_to)
            ->whereHas('twUsers', function($query) use ($loginUser){
                $query->where('own_user','=',$loginUser->mail);
            })
            ->count();
        
        if( $twAllCount == 0 ){
            $twAllCount = 1;
        }
        
        if(view()->exists('home')){
            return View::make('home', array(
                'twTodayCount' => $twTodayCount,
                'twTodayCreatedCount' => $twTodayCreatedCount,
                'twCompletedCount' => $twCompletedCount,
                'twFailCount' => $twFailCount,
                'twInprogressCount' => $twInprogressCount
            ));
        }
    }
}
