<?php

namespace App\Http\Controllers;

use App\UserEscalateOff;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use DB;
use \Response;


class UserEscalateOffController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if(view()->exists('user_escalate_off_create')){
            return View::make('user_escalate_off_create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        // do process

        $userEscalateOffData = array(	
            'is_visible' => true,
            'is_escalate' => true,
            'user_pk' => Input::get('user_pk'),
            'email_escalate' => Input::get('email_escalate'),
        );

        // Start transaction!
        DB::beginTransaction();

        try {
            // Validate, then create if valid
            $newUserEscalateOff = UserEscalateOff::create( $userEscalateOffData );

        }catch(\Exception $e){

            DB::rollback();

            $data = array(
                'title' => 'error',
                'text' => 'error',
                'type' => 'warning',
                'timer' => 3000
            );

            return Response::json( $data ); 

        }

        DB::commit();
        
        $data = array(
            'title' => 'success',
            'text' => 'success',
            'type' => 'success',
            'timer' => 3000
        );
        
        return Response::json( $data ); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserEscalateOff  $userEscalateOff
     * @return \Illuminate\Http\Response
     */
    public function show(UserEscalateOff $userEscalateOff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserEscalateOff  $userEscalateOff
     * @return \Illuminate\Http\Response
     */
    public function edit(UserEscalateOff $userEscalateOff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserEscalateOff  $userEscalateOff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserEscalateOff $userEscalateOff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserEscalateOff  $userEscalateOff
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserEscalateOff $userEscalateOff)
    {
        //
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        //Model::find(explode(',', $id))->delete();
        // do process
        // Start transaction!
        DB::beginTransaction();

        try {
            
            $userEscalateOff->delete();
            
        }catch(\Exception $e){
            DB::rollback();
            
            $data = array(
                'title' => 'error',
                'text' => 'error',
                'type' => 'warning',
                'timer' => 3000
            );

            return Response::json( $data );
        }

        DB::commit();

        $data = array(
            'title' => 'success',
            'text' => 'success',
            'type' => 'success',
            'timer' => 3000
        );

        return Response::json( $data );
    }
    
    //other
    public function listUserEscalateOff(Request $request){
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
        
        $userEscalateOff = new UserEscalateOff();
        
        $query = $userEscalateOff->where('is_visible', '=', true);
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
                $query = $query->where('user_pk', 'like', '%' . $search . '%');
            }
        }
        
        // role_pk
        if( ($request->get('email_escalate')) && (!empty($request->get('email_escalate'))) ){
            $email_escalate =  $request->get('email_escalate');
            $query = $query->whereDate('email_escalate', '=', $email_escalate);
        }
        
        // user_pk
        if( ($request->get('user_pk')) && (!empty($request->get('user_pk'))) ){
            $user_pk =  $request->get('user_pk');
            $query = $query->whereDate('user_pk', '=', $user_pk);
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
