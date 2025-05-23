<?php

namespace App\Http\Controllers;

use App\TWInfo;
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
use App\Enums\TWMetaEnum;
use App\TWUser;
use App\User;
use App\TW;
use App\UserAttachment;
use Storage;
use Chumper\Zipper\Zipper;

use App\Events\TWInfoCreateEvent;

class TWInfoController extends Controller
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
    public function create(Request $request, TW $tW)
    {
        //
        if(view()->exists('tw_info_create')){
            return View::make('tw_info_create', ['tW' => $tW]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TW $tW)
    {
        //
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        // do process
        $current_user = Login::getUserData()->mail;
        $twResourceDir = $tW->resource_dir;

        $twInfoData = array(	
            'is_visible' => true,
            't_w_id' => $tW->id,
            'description' => Input::get('description'),
            'created_user' => $current_user
        );

        $userAttachmentData = (array) $request->file('var_user_attachment');

        // Start transaction!
        DB::beginTransaction();

        try {
            //create directory
            if(!Storage::exists($twResourceDir)) {
                Storage::makeDirectory($twResourceDir, 0775, true); //creates directory
            }
            // Validate, then create if valid
            
            $newTWInfo = TWInfo::create( $twInfoData );

            if( $request->hasFile('var_user_attachment') ){
                chmod(Storage::path($twResourceDir), 0755);
                
                foreach($userAttachmentData as $key => $value){
                    $file_original_name = $value->getClientOriginalName();
                    $file_type = $value->getClientOriginalExtension();
                    //$filename = $value->store( $twResourceDir );
                    $filename = $value->storeAs( 
                        $twResourceDir,
                        uniqid( time() ) . '_' . $file_original_name
                    );
                    //chmod(Storage::path($filename), 0755);
                    
                    $newUserAttachment = $newTWInfo->userAttachments()->create(array(
                        'is_visible' => true,
                        'attached_by' => $current_user,
                        'file_original_name' => $file_original_name,
                        //'attachable_type' => get_class( $newTWInfo ),
                        //'attachable_id' => $newTWInfo->id,
                        'file_type' => $file_type,
                        'link_url' => $filename
                    ));
                }
            }
            
            event(new TWInfoCreateEvent($newTWInfo));
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
     * @param  \App\TWInfo  $tWInfo
     * @return \Illuminate\Http\Response
     */
    public function show(TWInfo $tWInfo)
    {
        //
        if(view()->exists('tw_info_show')){
            return View::make('tw_info_show', ['tWInfo' => $tWInfo]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TWInfo  $tWInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(TWInfo $tWInfo)
    {
        //
        if(view()->exists('tw_info_edit')){
            return View::make('tw_info_edit', ['tWInfo' => $tWInfo]);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TWInfo  $tWInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TWInfo $tWInfo)
    {
        //
        $tWInfoClone = clone $tWInfo;
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        // do process
        $current_user = Login::getUserData()->mail;
        $twResourceDir = $tWInfoClone->tw->resource_dir;

        $twInfoData = array(
            'description' => Input::get('description')
        );

        $userAttachmentData = (array) $request->file('var_user_attachment');

        // Start transaction!
        DB::beginTransaction();

        try {
            //create directory
            if(!Storage::exists($twResourceDir)) {
                Storage::makeDirectory($twResourceDir, 0775, true); //creates directory
            }
            // Validate, then create if valid
            $tWInfoClone->update( $twInfoData );

            if( $request->hasFile('var_user_attachment') ){
                chmod(Storage::path($twResourceDir), 0755);
                
                foreach($userAttachmentData as $key => $value){
                    $file_original_name = $value->getClientOriginalName();
                    $file_type = $value->getClientOriginalExtension();
                    //$filename = $value->store( $twResourceDir );
                    $filename = $value->storeAs( 
                        $twResourceDir,
                        uniqid( time() ) . '_' . $file_original_name
                    );
                    //chmod(Storage::path($filename), 0755);
                    
                    $newUserAttachment = $tWInfoClone->userAttachments()->create(array(
                        'is_visible' => true,
                        'attached_by' => $current_user,
                        'file_original_name' => $file_original_name,
                        //'attachable_type' => get_class( $newTWInfo ),
                        //'attachable_id' => $newTWInfo->id,
                        'file_type' => $file_type,
                        'link_url' => $filename
                    ));
                }
            }
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
     * Remove the specified resource from storage.
     *
     * @param  \App\TWInfo  $tWInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(TWInfo $tWInfo)
    {
        //
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        //Model::find(explode(',', $id))->delete();
        // do process
        // Start transaction!
        DB::beginTransaction();

        try {
            
            //delete directory
            $userAttachments = $tWInfo->userAttachments;
            if( $userAttachments ){
                foreach($userAttachments as $userAttachment){
                    if(Storage::exists( $userAttachment->link_url )) {
                        chmod(Storage::path($userAttachment->link_url), 0775);
                        Storage::delete( $userAttachment->link_url );
                    }
                    $userAttachment->delete();
                }
            }
            
            $tWInfo->delete();
            
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
    public function listTWInfos(Request $request){
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
        
        $twInfo = new TWInfo();
        
        $query = $twInfo->with(['tw', 'userAttachments'])->where('is_visible', '=', true);
        
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
                $query = $query->where('created_user', 'like', '%' . $search . '%');
            }
        }
        
        // created_user
        if( ($request->get('created_user')) && (!empty($request->get('created_user'))) ){
            $created_user = $request->get('created_user');
            $query = $query->where('created_user', '=', $created_user);
        }
        
        // t_w_id
        if( ($request->get('t_w_id')) && (!empty($request->get('t_w_id'))) ){
            $t_w_id =  $request->get('t_w_id');
            $query = $query->where('t_w_id', '=', $t_w_id);
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
    
    public function getFile(Request $request, TWInfo $tWInfo){
        $userAttachments = $tWInfo->userAttachments;
        if( $userAttachments ){
            $twResourceDir = TWMetaEnum::RESOURCE_DIR . '/' . 'tep_files';
            if(!Storage::exists($twResourceDir)) {
                Storage::makeDirectory($twResourceDir, 0775, true); //creates directory
            }
            $zipperName = $twResourceDir . '/attachments.zip';
            
            $zipper = new Zipper();
            $zipper->make(Storage::path($zipperName))->folder('attachments');
            foreach($userAttachments as $userAttachment){
                if(Storage::exists( $userAttachment->link_url )) {
                    $zipper->add( Storage::path( $userAttachment->link_url ) );
                }
            }
            $zipper->close();
            
            if(Storage::exists($zipperName)) {
                //return response()->download( Storage::url( $zipperName ) );
                return Storage::download( $zipperName );
            }else{
                return redirect()->back();
            }
        } 
    }
}
