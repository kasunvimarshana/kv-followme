<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
//use Auth;
//use DB;
use App\User;
//use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use \Response;

use App\LDAPModel;
use LdapQuery\Builder; 
use Illuminate\Http\JsonResponse;
use App\TW;
use App\Login;
use Carbon\Carbon;
use DB;

class UserController extends Controller
{
    
    //other
    public function listUsers(Request $request){
        // Solution to get around integer overflow errors
        // $model->latest()->limit(PHP_INT_MAX)->offset(1)->get();
        // function will process the ajax request
        $draw = null;
        $start = 0;
        $length = 0;
        $search = null;
        
        $recordsTotal = 0;
        $recordsFiltered = 0;
        $query = null;
        $queryResult = null;
        //$recordsTotal = Model::where('active','=','1')->count();
        
        $draw = $request->get('draw');
        
        $user = new User();
        $ldapModel = new LDAPModel();
        $query = new Builder();
        //$ldapModel->setOption(LDAP_OPT_SIZELIMIT, 1000);
            
        // get search query value
        if( ($request->get('search')) && (!empty($request->get('search'))) ){
            $search = $request->get('search');
            if( $search && (@key_exists('value', $search)) ){
                $search = $search['value'];
            }
            if($search && (!empty($search))){
                //$search = (string) $search;
                $query = $query->whereRaw('mail', '=', $search . '*');
            }
        }
        
        // employeenumber
        if( ($request->get('employeenumber')) && (!empty($request->get('employeenumber'))) ){
            $employeenumber = $request->get('employeenumber');
            $query = $query->whereRaw('employeenumber', '=', $employeenumber);
        }
        
        // cn
        if( ($request->get('cn')) && (!empty($request->get('cn'))) ){
            $cn =  $request->get('cn');
            $query = $query->whereRaw('cn', '=', '*' . $cn . '*');
        }
        
        // department
        if( ($request->get('department')) && (!empty($request->get('department'))) ){
            $department = $request->get('department');
            $query = $query->whereRaw('department', '=', $department);
        }
        
        // mobile
        if( ($request->get('mobile')) && (!empty($request->get('mobile'))) ){
            $mobile = $request->get('mobile');
            $query = $query->whereRaw('mobile', '=', $mobile);
        }
        
        // get data
        $queryResult = (array) $user->findUsers( $query->stringify() );
        $recordsFiltered = count($queryResult, 0);
        
        $recordsTotal = $recordsFiltered;
        $data = array(
            'draw' => $draw,
            'start' => $start,
            'page' => $start,
            'length' => $length,
            'search' => $search,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $queryResult,
            'pagination' => array(
                'more' => ( ($start * $length) < $recordsFiltered ) ? true : false
            )
        );
        
        return response()->json($data, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        //return Response::json( $data );
    }
    
    public function listDirectReports(Request $request, $user){
        $draw = null;
        $start = 0;
        $length = 0;
        $search = null;
        
        $recordsTotal = 0;
        $recordsFiltered = 0;
        $query = null;
        $queryResult = null;
        //$recordsTotal = Model::where('active','=','1')->count();
        
        $draw = $request->get('draw');
        
        $requestUser = new User();
        $requestUser->mail = urldecode($user);
        $requestUser->getUser();
        //$requestUser = Login::getUserData();
        $directReportsArray = $requestUser->getDirectReports();
        
        $date_today = Carbon::now()->format('Y-m-d');
        $date_from = Carbon::now()->subMonths(5)->format('Y-m-d');
        $date_to = Carbon::now()->format('Y-m-d');
        $start_date_from = $date_from;
        $start_date_to = $date_to;
        foreach($directReportsArray as $key=>&$value){

            $twAllCount = TW::where('is_visible','=',true)
            ->whereDate('start_date','>=',$start_date_from)
            ->whereDate('start_date','<=',$start_date_to)
            ->whereHas('twUsers', function($query) use ($value){
                $query->where('own_user','=',$value->mail);
            })
            ->count();
            
            $twCompletedCount = TW::where('is_visible','=',true)
                ->where('is_done','=',true)
                ->whereDate('start_date','>=',$start_date_from)
                ->whereDate('start_date','<=',$start_date_to)
                ->whereHas('twUsers', function($query) use ($value){
                    $query->where('own_user','=',$value->mail);
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
                ->whereHas('twUsers', function($query) use ($value){
                    $query->where('own_user','=',$value->mail);
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
                ->whereHas('twUsers', function($query) use ($value){
                    $query->where('own_user','=',$value->mail);
                })
                ->count();

            if( $twAllCount == 0 ){
                $twAllCount = 1;
            }
            
            $value->twCompletedCount = $twCompletedCount;
            $value->twFailCount = $twFailCount;
            $value->twInprogressCount = $twInprogressCount;

        }
        
        // get data
        $queryResult = (array) $directReportsArray;
        $recordsFiltered = count($queryResult, 0);
        
        $recordsTotal = $recordsFiltered;
        $data = array(
            'draw' => $draw,
            'start' => $start,
            'page' => $start,
            'length' => $length,
            'search' => $search,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $queryResult,
            'pagination' => array(
                'more' => ( ($start * $length) < $recordsFiltered ) ? true : false
            )
        );
        
        return response()->json($data, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }
    
    public function showDirectReports(Request $request){
        $loginUser = Login::getUserData();
        $date_today = Carbon::now()->format('Y-m-d');
        $date_from = Carbon::now()->subMonths(5)->format('Y-m-d');
        $date_to = Carbon::now()->format('Y-m-d');
        $start_date_from = $date_from;
        $start_date_to = $date_to;
        $directReportsArray = $loginUser->getDirectReports();
        
        foreach($directReportsArray as $key=>&$value){
            
            $twAllCount = TW::where('is_visible','=',true)
                ->whereDate('start_date','>=',$start_date_from)
                ->whereDate('start_date','<=',$start_date_to)
                ->whereHas('twUsers', function($query) use ($value){
                    $query->where('own_user','=',$value->mail);
                })
                ->count();

            $twPassCount = TW::where('is_visible','=',true)
                ->where(function($query){
                    $query->where('is_done','=',true);
                    $query->whereNotNull('done_date');
                    $query->where(DB::raw('DATE(due_date)'),'>=',DB::raw('DATE(done_date)'));
                })
                ->whereDate('start_date','>=',$start_date_from)
                ->whereDate('start_date','<=',$start_date_to)
                ->whereHas('twUsers', function($query) use ($value){
                    $query->where('own_user','=',$value->mail);
                })
                ->count();

            $twFailWithCompletedCount = TW::where('is_visible','=',true)
                ->where(function($query){
                    $query->where(function($query){
                        $query->where('is_done','=',true);
                        $query->whereNotNull('done_date');
                        $query->where(DB::raw('DATE(due_date)'),'<',DB::raw('DATE(done_date)'));
                    });
                })
                ->whereDate('start_date', '>=', $start_date_from)
                ->whereDate('start_date', '<=', $start_date_to)
                ->whereHas('twUsers', function($query) use ($value){
                    $query->where('own_user','=',$value->mail);
                })
                ->count();
            
            $twFailWithUncompletedCount = TW::where('is_visible','=',true)
                ->where(function($query) use ($date_today){
                    $query->whereDate('due_date','<',$date_today);
                    $query->where(function($query){
                        $query->where('is_done','=',false);
                        $query->orWhereNull('is_done');
                    });
                })
                ->whereDate('start_date', '>=', $start_date_from)
                ->whereDate('start_date', '<=', $start_date_to)
                ->whereHas('twUsers', function($query) use ($value){
                    $query->where('own_user','=',$value->mail);
                })
                ->count();

            $twInprogressCount = TW::where('is_visible','=',true)
                ->where(function($query){
                    $query->where('is_done','=',false);
                    $query->orWhereNull('is_done');
                })
                ->where(function($query){
                    //$query->whereRaw('due_date >= done_date');
                    $query->orWhereDate('due_date','>=',Carbon::now()->format('Y-m-d'));
                })
                ->whereDate('start_date','>=', $start_date_from)
                ->whereDate('start_date','<=', $start_date_to)
                ->whereHas('twUsers', function($query) use ($value){
                    $query->where('own_user','=',$value->mail);
                })
                ->count();

            if( $twAllCount == 0 ){
                $twAllCount = 1;
            }
            
            $value->twAllCount = $twAllCount;
            $value->twPassCount = $twPassCount;
            $value->twFailWithCompletedCount = $twFailWithCompletedCount;
            $value->twFailWithUncompletedCount = $twFailWithUncompletedCount;
            $value->twInprogressCount = $twInprogressCount;
            $value->twPassCountPercentage = (($twPassCount / $twAllCount) * 100);
            $value->twFailWithCompletedCountPercentage = (($twFailWithCompletedCount / $twAllCount) * 100);
            $value->twFailWithUncompletedCountPercentage = (($twFailWithUncompletedCount / $twAllCount) * 100);
            $value->twInprogressCountPercentage = (($twInprogressCount / $twAllCount) * 100);

        }
        
        // get data
        $directReportsArray = (array) $directReportsArray;
        
        if(view()->exists('subordinate_show')){
            return View::make('subordinate_show', ['directReportsArray' => $directReportsArray]);
        }
    }
    
    public function showDirectReportTW(Request $request, $user){
        $directReportUser = new User();
        $directReportUser->mail = urldecode($user);
        $directReportUser->getUser();
        if(view()->exists('subordinate_tw_show')){
            return View::make('subordinate_tw_show', ['directReportUser' => $directReportUser]);
        }
    }
    
}
