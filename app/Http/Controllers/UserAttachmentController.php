<?php

namespace App\Http\Controllers;

use App\UserAttachment;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use \Response; 

use DB;
use App\Login;
use Storage;

class UserAttachmentController extends Controller
{
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserAttachment  $userAttachment
     * @return \Illuminate\Http\Response
     */
    public function show(UserAttachment $userAttachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserAttachment  $userAttachment
     * @return \Illuminate\Http\Response
     */
    public function edit(UserAttachment $userAttachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserAttachment  $userAttachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserAttachment $userAttachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserAttachment  $userAttachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserAttachment $userAttachment)
    {
        //
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        //Model::find(explode(',', $id))->delete();
        // do process
        // Start transaction!
        DB::beginTransaction();

        try {
            
            if(Storage::exists($userAttachment->link_url)){
                chmod(Storage::path($userAttachment->link_url), 0755);
                Storage::delete( $userAttachment->link_url );
            }
            $userAttachment->delete();
            
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
    public function listUserAttachments(Request $request){
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
        
        $userAttachment = new UserAttachment();
        
        $query = $userAttachment->where('is_visible', '=', true);
        
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
                $query = $query->where('file_original_name', 'like', '%' . $search . '%');
            }
        }
        
        // attached_by
        if( ($request->get('attached_by')) && (!empty($request->get('attached_by'))) ){
            $attached_by = $request->get('attached_by');
            $query = $query->where('attached_by', '=', $attached_by);
        }
        
        // attachable_type
        if( ($request->get('attachable_type')) && (!empty($request->get('attachable_type'))) ){
            $attachable_type = $request->get('attachable_type');
            $attachable_type = urldecode( $attachable_type );
            $query = $query->where('attachable_type', '=', $attachable_type);
        }
        
        // attachable_id
        if( ($request->get('attachable_id')) && (!empty($request->get('attachable_id'))) ){
            $attachable_id =  $request->get('attachable_id');
            $query = $query->where('attachable_id', '=', $attachable_id);
        }
        
        // created date
        if( ($request->get('created_at')) && (!empty($request->get('created_at'))) ){
            $created_at =  $request->get('created_at');
            $query = $query->whereDate('created_at', '=', $created_at);
        }
        
        // updated date
        if( ($request->get('updated_at')) && (!empty($request->get('updated_at'))) ){
            $updated_at =  $request->get('updated_at');
            $query = $query->whereDate('updated_at', '=', $updated_at);
        }
        
        // is_visible
        if( ($request->get('is_visible') != null) ){
            $is_visible =  $request->get('is_visible');
            $query = $query->where('is_visible', '=', $is_visible);
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
        $query->orderBy('id', 'desc');
        $query->orderBy('updated_at', 'desc');
        
        // get data
        $queryResult = $query->get();
        
        foreach($queryResult as $key => &$value){
            $value->link_url = Storage::url( $value->link_url );
        }
        
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
    
    public function getFile(Request $request, UserAttachment $userAttachment){
        //$link_url = Storage::url( $userAttachment->link_url );
        //return Storage::download($userAttachment->link_url, $name = null, $headers = null);

        if(Storage::exists($userAttachment->link_url)){
            return Storage::download($userAttachment->link_url, $userAttachment->file_original_name);
        } 
    }
    
}
