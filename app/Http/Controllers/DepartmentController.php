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
use App\TWUser;
use App\Login;
use Carbon\Carbon;
use DB;
use \StdClass;

class DepartmentController extends Controller
{
    //
    public function showDepartments(Request $request){
        $loginUser = Login::getUserData();
        $date_today = Carbon::now()->format('Y-m-d');
        $date_from = Carbon::now()->subMonths(5)->format('Y-m-d');
        $date_to = Carbon::now()->format('Y-m-d');
        $start_date_from = $date_from;
        $start_date_to = $date_to;
        
        $departmentObj = new StdClass();
        $departmentObj->company_name = $loginUser->company;
        $departmentObj->department_name = $loginUser->department;
        
        $twAllCount = TW::where('is_visible','=',true)
            ->whereDate('start_date','>=',$start_date_from)
            ->whereDate('start_date','<=',$start_date_to)
            ->whereHas('twUsers', function($query) use ($loginUser){
                $query->where('company_name','=',$loginUser->company);
                $query->where('department_name','=',$loginUser->department);
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
            /*->where(function($query) use ($loginUser){
                $query->where('company_name','=',$loginUser->company);
                $query->where('department_name','=',$loginUser->department);
            })*/
            ->whereHas('twUsers', function($query) use ($loginUser){
                $query->where('company_name','=',$loginUser->company);
                $query->where('department_name','=',$loginUser->department);
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
            /*->where(function($query) use ($loginUser){
                $query->where('company_name','=',$loginUser->company);
                $query->where('department_name','=',$loginUser->department);
            })*/
            ->whereHas('twUsers', function($query) use ($loginUser){
                $query->where('company_name','=',$loginUser->company);
                $query->where('department_name','=',$loginUser->department);
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
            /*->where(function($query) use ($loginUser){
                $query->where('company_name','=',$loginUser->company);
                $query->where('department_name','=',$loginUser->department);
            })*/
            ->whereHas('twUsers', function($query) use ($loginUser){
                $query->where('company_name','=',$loginUser->company);
                $query->where('department_name','=',$loginUser->department);
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
            /*->where(function($query) use ($loginUser){
                $query->where('company_name','=',$loginUser->company);
                $query->where('department_name','=',$loginUser->department);
            })*/
            ->whereHas('twUsers', function($query) use ($loginUser){
                $query->where('company_name','=',$loginUser->company);
                $query->where('department_name','=',$loginUser->department);
                $query->distinct('t_w_id');
            })
            ->count();
        
        if( $twAllCount == 0 ){
            $twAllCount = 1;
        }
        
        $departmentObj->twAllCount = $twAllCount;
        $departmentObj->twPassCount = $twPassCount;
        $departmentObj->twFailWithCompletedCount = $twFailWithCompletedCount;
        $departmentObj->twFailWithUncompletedCount = $twFailWithUncompletedCount;
        $departmentObj->twInprogressCount = $twInprogressCount;
        $departmentObj->twPassCountPercentage = (($twPassCount / $twAllCount) * 100);
        $departmentObj->twFailWithCompletedCountPercentage = (($twFailWithCompletedCount / $twAllCount) * 100);
        $departmentObj->twFailWithUncompletedCountPercentage = (($twFailWithUncompletedCount / $twAllCount) * 100);
        $departmentObj->twInprogressCountPercentage = (($twInprogressCount / $twAllCount) * 100);
        
        if(view()->exists('department_show')){
            return View::make('department_show', ['departmentObj' => $departmentObj]);
        }
    }
    
    public function showDepartmentTW(Request $request, $company, $department){
        $loginUser = Login::getUserData();
        $date_today = Carbon::now()->format('Y-m-d');
        $date_from = Carbon::now()->subMonths(5)->format('Y-m-d');
        $date_to = Carbon::now()->format('Y-m-d');
        $start_date_from = $date_from;
        $start_date_to = $date_to;
        
        $companyObj = urldecode($company);
        $departmentObj = urldecode($department);
        $progressVal = urldecode($request->get('progress'));
        
        if(view()->exists('department_tw_show')){
            return View::make('department_tw_show', ['companyObj' => $companyObj, 'departmentObj' => $departmentObj, 'progressVal' => $progressVal]);
        }
    }
    
    //other
    public function listDepartments(Request $request){
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
        $query = $tw->select('department_name');
        $query = $query->groupBy('department_name');
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
                $query = $query->where('department_name', 'like', '%' . $search . '%');
            }
        }
        
        // company_name
        if( ($request->get('department_name')) && (!empty($request->get('department_name'))) ){
            $department_name =  $request->get('department_name');
            $query = $query->whereDate('department_name', '=', $department_name);
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
    
    public function listTWCreatedDepartments(Request $request){
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
        $query = $tw->select('department_name');
        $query = $query->groupBy('department_name');
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
                $query = $query->where('department_name', 'like', '%' . $search . '%');
            }
        }
        
        // company_name
        if( ($request->get('department_name')) && (!empty($request->get('department_name'))) ){
            $department_name =  $request->get('department_name');
            $query = $query->whereDate('department_name', '=', $department_name);
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
    
    public function listTWOwnDepartments(Request $request){
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
        
        $tWUser = new TWUser();
        
        //$query = $tw->where('is_visible', '=', true);
        $query = $tWUser->select('department_name');
        $query = $query->groupBy('department_name');
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
                $query = $query->where('department_name', 'like', '%' . $search . '%');
            }
        }
        
        // company_name
        if( ($request->get('department_name')) && (!empty($request->get('department_name'))) ){
            $department_name =  $request->get('department_name');
            $query = $query->whereDate('department_name', '=', $department_name);
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
    
}
