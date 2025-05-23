<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

use App\User;
use Illuminate\Support\Str;
use \Response;

use App\LDAPModel;
use LdapQuery\Builder; 
use Illuminate\Http\JsonResponse;
use App\TW;
use App\Login;
use Carbon\Carbon;
use DB;
use \StdClass;

class CompanyController extends Controller
{
    //
    public function showDepartments(Request $request){
        $loginUser = Login::getUserData();
        $date_today = Carbon::now()->format('Y-m-d');
        $date_from = Carbon::now()->subMonths(5)->format('Y-m-d');
        $date_to = Carbon::now()->format('Y-m-d');
        $start_date_from = $date_from;
        $start_date_to = $date_to;
        
        $companyObj = new StdClass();
        $companyObj->company_name = $loginUser->company;
        $departmentsArray = array();
        
        $departmentsArray = DB::table('t_w_users')
            ->select('company_name')
            ->addSelect('department_name')
            ->where('is_visible', true)
            ->where('company_name','=',$companyObj->company_name)
            ->distinct('department_name')
            ->get();
        
        foreach($departmentsArray as $key=>&$value){
            
            $twAllCount = TW::where('is_visible','=',true)
            ->whereDate('start_date','>=',$start_date_from)
            ->whereDate('start_date','<=',$start_date_to)
            ->whereHas('twUsers', function($query) use ($value){
                $query->where('company_name','=',$value->company_name);
                $query->where('department_name','=',$value->department_name);
                $query->distinct('t_w_id');
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
            /*->where(function($query) use ($value){
                $query->where('company_name','=',$value->company_name);
                $query->where('department_name','=',$value->department_name);
            })*/
            ->whereHas('twUsers', function($query) use ($value){
                $query->where('company_name','=',$value->company_name);
                $query->where('department_name','=',$value->department_name);
                $query->distinct('t_w_id');
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
                /*->where(function($query) use ($value){
                    $query->where('company_name','=',$value->company_name);
                    $query->where('department_name','=',$value->department_name);
                })*/
                ->whereHas('twUsers', function($query) use ($value){
                    $query->where('company_name','=',$value->company_name);
                    $query->where('department_name','=',$value->department_name);
                    $query->distinct('t_w_id');
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
                /*->where(function($query) use ($value){
                    $query->where('company_name','=',$value->company_name);
                    $query->where('department_name','=',$value->department_name);
                })*/
                ->whereHas('twUsers', function($query) use ($value){
                    $query->where('company_name','=',$value->company_name);
                    $query->where('department_name','=',$value->department_name);
                    $query->distinct('t_w_id');
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
                /*->where(function($query) use ($value){
                    $query->where('company_name','=',$value->company_name);
                    $query->where('department_name','=',$value->department_name);
                })*/
                ->whereHas('twUsers', function($query) use ($value){
                    $query->where('company_name','=',$value->company_name);
                    $query->where('department_name','=',$value->department_name);
                    $query->distinct('t_w_id');
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
        $companyObj->departments = $departmentsArray;
        
        if(view()->exists('company_department_show')){
            return View::make('company_department_show', ['companyObj' => $companyObj]);
        }
    }
    
    //other
    public function listCompanies(Request $request){
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
        
        $tw = new TW();
        
        //$query = $tw->where('is_visible', '=', true);
        $query = $tw->select('company_name');
        $query = $query->groupBy('company_name');
        $recordsTotal = $query->count();
        $recordsFiltered = $recordsTotal;
            
        // get search query value
        if( ($request->get('search')) && (!empty($request->get('search'))) ){
            $search = $request->get('search');
            if( $search && (@key_exists('value', $search)) ){
                $search = $search['value'];
            }
            if($search && (!empty($search))){
                //$search = (string) $search;
                $query = $query->where('company_name', 'like', '%' . $search . '%');
            }
        }
        
        // company_name
        if( ($request->get('company_name')) && (!empty($request->get('company_name'))) ){
            $company_name =  $request->get('company_name');
            $query = $query->whereDate('company_name', '=', $company_name);
        }
        
        // get filtered record count
        $recordsFiltered = $query->count();
        
        // get limit value
        if( $request->get('length') ){
            $length = intval( $request->get('length') );
            $length = abs( $length );
            $query = $query->limit($length);
        }
        // set default value for length (PHP_INT_MAX)
        if( $length <= 0 ){
            $length = PHP_INT_MAX;
            $length = abs( $length );
            //$length = 0;
        }
        
        // get offset value
        if( $request->get('start') ){
            $start = intval( $request->get('start') );
            $start = abs( $start );
        }else if( $request->get('page') ){
            $start = intval( $request->get('page') );
            //$start = abs( ( ( $start - 1 ) * $length ) );
            $start = ( ( $start - 1 ) * $length );
            $start = abs( $start );
        }
        
        // filter with offset value
        if( $start > 0 ){
            //$query = $query->limit($length)->skip($start);
            $query = $query->limit($length)->offset($start);
        }else if( $length > 0 ){
            $query = $query->limit($length);
        }
        
        // order
        /*
        $query->orderBy('id', 'desc');
        $query->orderBy('updated_at', 'desc');
        */
        
        // get data
        $queryResult = $query->get();
        
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
        
        return Response::json( $data );   
    }
    
    public function showCompanyTW(Request $request, $company){
        $loginUser = Login::getUserData();
        $date_today = Carbon::now()->format('Y-m-d');
        $date_from = Carbon::now()->subMonths(5)->format('Y-m-d');
        $date_to = Carbon::now()->format('Y-m-d');
        $start_date_from = $date_from;
        $start_date_to = $date_to;
        
        $companyObj = urldecode($company);
        
        if(view()->exists('company_tw_show')){
            return View::make('company_tw_show', ['companyObj' => $companyObj]);
        }
    }
    
}
