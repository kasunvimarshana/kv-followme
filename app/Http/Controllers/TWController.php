<?php

namespace App\Http\Controllers;

use App\TW;
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
use App\Enums\TWStatusEnum;
use App\Enums\TWMetaEnum;
use App\TWUser;
use App\User;
use App\TWInfo;
use App\UserAttachment;
use Storage;
use Carbon\Carbon;

use App\Events\TWCreateEvent;
use App\Events\TWResubmitEvent;
use App\Events\TWUpdateEvent;
use App\Events\TWCloseEvent;

//use Maatwebsite\Excel\Facades\Excel;
use Excel;
use App\Exports\CommonExportWorkBook;
use App\Helpers\NotifyHelper;

class TWController extends Controller
{
    /* *** */
    public function is_true($val, $return_null = false){
        $boolval = ( is_string($val) ? filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool) $val );
        //$boolval = boolval( $boolval );
        return ( $boolval === null && !$return_null ? false : $boolval );
    }
    /* *** */
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
        if(view()->exists('tw_create')){
            return View::make('tw_create');
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
        /*DB::transaction(function () {
            DB::table('table_1')->update(['column' => 1]);
            DB::table('table_2')->delete();
        });*/
        // validate the info, create rules for the inputs
        $rules = array(
            'title'    => 'required'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {

            $data = array(
                'title' => 'error',
                'text' => 'error',
                'type' => 'warning',
                'timer' => 3000
            );

            return Response::json( $data );

        } else {
            // do process
            $loginUserObj = Login::getUserData();
            $current_user = $loginUserObj->mail;
            $company_name = $loginUserObj->company;
            $department_name = $loginUserObj->department;
            $twResourceDir = TWMetaEnum::RESOURCE_DIR .'/'. uniqid( time() ) . '_';

            $twData = array(
                'meeting_category_id'     => Input::get('meeting_category_id'),
                'title'     => Input::get('title'),
                'start_date'     => Input::get('start_date'),
                'due_date'     => Input::get('due_date'),
                'description'     => Input::get('description'),
                'created_user'     => $current_user,
                'company_name'     => $company_name,
                'department_name'     => $department_name,
                'is_visible' => true,
                'status_id' => TWStatusEnum::OPEN,
                'resource_dir' => $twResourceDir,
                'is_cloned' => false,
                'is_cloned_child' => false,
                'is_archived' => false,
                'is_reviewable' => true
            );

            $twUserData = (array) Input::get('own_user');

            $userAttachmentData = (array) $request->file('var_user_attachment');

            // Start transaction!
            DB::beginTransaction();

            try {
                //create directory
                if(!Storage::exists($twResourceDir)) {
                    Storage::makeDirectory($twResourceDir, 0775, true); //creates directory
                }
                // Validate, then create if valid
                $newTW = TW::create( $twData );

                $newTWInfo = TWInfo::create(array(
                    'is_visible' => true,
                    't_w_id' => $newTW->id,
                    'description' => $newTW->description,
                    'created_user' => $current_user
                ));

                foreach($twUserData as $key => $value){
                    $tempTWUser = new User();
                    $tempTWUser->mail = $value;
                    $tempTWUser = $tempTWUser->getUser();

                    $newTWUser = TWUser::create(array(
                        't_w_id' => $newTW->id,
                        'is_visible' => true,
                        'own_user' => $tempTWUser->mail,
                        'company_name' => $tempTWUser->company,
                        'department_name' => $tempTWUser->department,
                        'is_cloned' => false
                    ));
                }

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

                event(new TWCreateEvent($newTW));
            }catch(\Exception $e){

                DB::rollback();

                //delete directory
                if(Storage::exists($twResourceDir)) {
                    Storage::deleteDirectory($twResourceDir);
                }

                $data = array(
                    'title' => 'error',
                    'text' => 'error',
                    'type' => 'warning',
                    'timer' => 3000
                );

                return Response::json( $data );

            }

            // If we reach here, then
            // data is valid and working.
            // Commit the queries!
            DB::commit();
        }

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
     * @param  \App\TW  $tW
     * @return \Illuminate\Http\Response
     */
    public function show(TW $tW)
    {
        //
        if(view()->exists('tw_show')){
            return View::make('tw_show', ['tW' => $tW]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TW  $tW
     * @return \Illuminate\Http\Response
     */
    public function edit(TW $tW)
    {
        //
        if(view()->exists('tw_edit')){
            return View::make('tw_edit', ['tW' => $tW]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TW  $tW
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TW $tW)
    {
        //
        $tWClone = clone $tW;
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        // validate the info, create rules for the inputs
        $rules = array(
            'title'    => 'required'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {

            $data = array(
                'title' => 'error',
                'text' => 'error',
                'type' => 'warning',
                'timer' => 3000
            );

            return Response::json( $data );

        } else {
            // do process
            $loginUserObj = Login::getUserData();
            $current_user = $loginUserObj->mail;

            $twData = array(
                'meeting_category_id'     => Input::get('meeting_category_id'),
                'title'     => Input::get('title'),
                'start_date'     => Input::get('start_date'),
                'due_date'     => Input::get('due_date'),
                'description'     => Input::get('description'),
                'status_id' => TWStatusEnum::OPEN
            );

            $twUserData = (array) Input::get('own_user');

            // Start transaction!
            DB::beginTransaction();

            try {
                // Validate, then create if valid
                $tWClone->update( $twData );

                foreach($twUserData as $key => $value){
                    $tempTWUser = new User();
                    $tempTWUser->mail = $value;
                    $tempTWUser = $tempTWUser->getUser();

                    $newTWUser = TWUser::create(array(
                        't_w_id' => $tWClone->id,
                        'is_visible' => true,
                        'own_user' => $tempTWUser->mail,
                        'company_name' => $tempTWUser->company,
                        'department_name' => $tempTWUser->department,
                        'is_cloned' => false
                    ));
                }

                event(new TWUpdateEvent($tWClone));
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

            // If we reach here, then
            // data is valid and working.
            // Commit the queries!
            DB::commit();
        }

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
     * @param  \App\TW  $tW
     * @return \Illuminate\Http\Response
     */
    public function destroy(TW $tW)
    {
        //
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        //Model::find(explode(',', $id))->delete();
        // do process
        // Start transaction!
        DB::beginTransaction();

        try {

            //delete directory
            if(Storage::exists($tW->resource_dir)) {
                chmod(Storage::path($tW->resource_dir), 0775);
                Storage::deleteDirectory($tW->resource_dir);
            }

            $tW->eventRecurringPatterns()->delete();
            $tW->twInfos()->delete();
            $tW->twUsers()->delete();
            $tW->delete();

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
    public function listTWs(Request $request){
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
        $date_today = Carbon::now()->format('Y-m-d');

        $draw = $request->get('draw');

        $tw = new TW();

        $query = $tw->with(['twUsers', 'twInfos', 'status'])->where('is_visible', '=', true);

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
                $query = $query->where('title', 'like', '%' . $search . '%');
            }
        }

        // created_user
        if( ($request->get('created_user')) && (!empty($request->get('created_user'))) ){
            $created_user = $request->get('created_user');
            $query = $query->where('created_user', '=', $created_user);
        }

        // meeting_category_id
        if( ($request->get('meeting_category_id')) && (!empty($request->get('meeting_category_id'))) ){
            $meeting_category_id =  $request->get('meeting_category_id');
            $query = $query->where('meeting_category_id', '=', $meeting_category_id);
        }

        // status
        if( ($request->get('status_id')) && (!empty($request->get('status_id'))) ){
            $status_id =  $request->get('status_id');
            $query = $query->where('status_id', '=', $status_id);
        }

        // start date
        if( ($request->get('start_date')) && (!empty($request->get('start_date'))) ){
            $start_date =  $request->get('start_date');
            $query = $query->whereDate('start_date', 'like', $start_date . '%');
        }

        // start date from
        if( ($request->get('start_date_from')) && (!empty($request->get('start_date_from'))) ){
            $start_date_from =  $request->get('start_date_from');
            $query = $query->whereDate('start_date', '>=', $start_date_from);
        }

        // start date to
        if( ($request->get('start_date_to')) && (!empty($request->get('start_date_to'))) ){
            $start_date_to =  $request->get('start_date_to');
            $query = $query->whereDate('start_date', '<=', $start_date_to);
        }

        // due date
        if( ($request->get('due_date')) && (!empty($request->get('due_date'))) ){
            $due_date =  $request->get('due_date');
            $query = $query->whereDate('due_date', 'like', $due_date . '%');
        }

        // due date from
        if( ($request->get('due_date_from')) && (!empty($request->get('due_date_from'))) ){
            $due_date_from =  $request->get('due_date_from');
            $query = $query->whereDate('due_date', '>=', $due_date_from);
        }

        // due date to
        if( ($request->get('due_date_to')) && (!empty($request->get('due_date_to'))) ){
            $due_date_to =  $request->get('due_date_to');
            $query = $query->whereDate('due_date', '<=', $due_date_to);
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

        // own user
        if( ($request->get('own_user')) && (!empty($request->get('own_user'))) ){
            $own_user =  $request->get('own_user');
            $query = $query->whereHas('twUsers', function($query) use ($own_user){
                $query = $query->where('own_user', '=', $own_user);
            });
        }

        // own company
        if( ($request->get('own_company')) && (!empty($request->get('own_company'))) ){
            $own_company =  $request->get('own_company');
            $query = $query->whereHas('twUsers', function($query) use ($own_company){
                $query = $query->where('company_name','=',$own_company);
                $query = $query->distinct('t_w_id');
            });
        }

        // own department
        if( ($request->get('own_department')) && (!empty($request->get('own_department'))) ){
            $own_department =  $request->get('own_department');
            $query = $query->whereHas('twUsers', function($query) use ($own_department){
                $query = $query->where('department_name','=',$own_department);
                $query = $query->distinct('t_w_id');
            });
        }

        // created company
        if( ($request->get('created_company')) && (!empty($request->get('created_company'))) ){
            $created_company = $request->get('created_company');
            $query = $query->where('company_name', '=', $created_company);
        }

        // created department
        if( ($request->get('created_department')) && (!empty($request->get('created_department'))) ){
            $created_department = $request->get('created_department');
            $query = $query->where('department_name', '=', $created_department);
        }

        // is_visible
        if( ($request->get('is_visible') != null) ){
            $is_visible =  $request->get('is_visible');
            $is_visible = $this->is_true( $is_visible );
            $query = $query->where('is_visible', '=', $is_visible);
        }

        // is_done
        if( ($request->get('is_done') != null) ){
            $is_done =  $request->get('is_done');
            $is_done = $this->is_true( $is_done );
            $query = $query->where('is_done', '=', $is_done);
        }

        // progress
        if( ($request->get('progress')) && (!empty($request->get('progress'))) ){
            $progress =  $request->get('progress');
            if( $progress == TWStatusEnum::COMPLETED ){
                $query = $query->where('is_done','=',true);
            }else if( $progress == TWStatusEnum::PASS ){
                $query = $query->where(function($query){
                    $query = $query->where('is_done','=',true);
                    $query = $query->whereNotNull('done_date');
                    $query = $query->where(DB::raw('DATE(due_date)'),'>=',DB::raw('DATE(done_date)'));
                });
            }else if( $progress == TWStatusEnum::FAIL ){
                /*$query = $query->where(function($query){
                    $query->where('is_done','=',false);
                    $query->orWhereNull('is_done');
                });
                $query = $query->where(function($query){
                    $query->whereRaw('due_date > done_date');
                    $query->where(DB::raw("DATE(due_date) > DATE(done_date)"));
                    $query->orWhereDate('due_date','<',Carbon::now()->format('Y-m-d'));
                    $query->orWhereNull('done_date');
                });*/
                $query = $query->where(function($query) use ($date_today){
                    $query = $query->where(function($query){
                        $query = $query->whereNotNull('done_date');
                        $query = $query->where(DB::raw('DATE(due_date)'),'<',DB::raw('DATE(done_date)'));
                    });
                    $query = $query->orWhere(function($query) use ($date_today){
                        $query = $query->whereDate('due_date','<',$date_today);
                        $query = $query->where(function($query){
                            $query = $query->where('is_done','=',false);
                            $query = $query->orWhereNull('is_done');
                        });
                    });
                });
            }else if( $progress == TWStatusEnum::INPROGRESS ){
                $query = $query->where(function($query) use ($date_today){
                    $query = $query->where(function($query){
                        $query = $query->where('is_done','=',false);
                        $query = $query->orWhereNull('is_done');
                    });
                    $query = $query->where(function($query) use ($date_today){
                        //$query->whereRaw('due_date >= done_date');
                        $query = $query->orWhereDate('due_date','>=',$date_today);
                    });
                });
            }else if( $progress == TWStatusEnum::OPEN ){
                $query = $query->where(function($query){
                    $query = $query->where('is_done','=',false);
                    $query = $query->orWhereNull('is_done');
                });
            }else if( $progress == TWStatusEnum::CLOSE ){
                $query = $query->where('is_done','=',true);
            }else if( $progress == TWStatusEnum::FAIL_WITH_COMPLETED ){
                $query = $query->where(function($query){
                    $query = $query->where('is_done','=',true);
                    $query = $query->whereNotNull('done_date');
                    $query = $query->where(DB::raw('DATE(due_date)'),'<',DB::raw('DATE(done_date)'));
                });
            }else if( $progress == TWStatusEnum::FAIL_WITH_UNCOMPLETED ){
                $query = $query->where(function($query) use ($date_today){
                    $query = $query->whereDate('due_date','<',$date_today);
                    $query = $query->where(function($query){
                        $query = $query->where('is_done','=',false);
                        $query = $query->orWhereNull('is_done');
                    });
                });
            }
        }

        // is_archived ( is_bool($variable) )
        if( ($request->has('is_archived')) ){
            /*
            $is_archived_val_true = "true";
            $is_archived_val_false = "false";
            $is_archived_val_temp = $request->input('is_archived');

            if( (strcasecmp($is_archived_val_temp, $is_archived_val_true) == 0) ){

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_archived','=',true);
                    });
                });

            }else if( (strcasecmp($is_archived_val_temp, $is_archived_val_false) == 0) ){

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_archived','=',false);
                        $query = $query->orWhereNull('is_archived');
                    });
                });

            }
            */
            $is_archived = $request->input('is_archived');
            $is_archived = $this->is_true( $is_archived );
            if( ($is_archived) ){

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_archived','=',true);
                    });
                });

            }else{

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_archived','=',false);
                        $query = $query->orWhereNull('is_archived');
                    });
                });

            }
        }

        // is_reviewable ( is_bool($variable) )
        if( ($request->has('is_reviewable')) ){
            /*
            $is_reviewable_val_true = "true";
            $is_reviewable_val_false = "false";
            $is_reviewable_val_temp = $request->input('is_reviewable');

            if( (strcasecmp($is_reviewable_val_temp, $is_reviewable_val_true) == 0) ){

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_reviewable','=',true);
                    });
                });

            }else if( (strcasecmp($is_reviewable_val_temp, $is_reviewable_val_false) == 0) ){

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_reviewable','=',false);
                        $query = $query->orWhereNull('is_reviewable');
                    });
                });

            }
            */
            $is_reviewable = $request->input('is_reviewable');
            $is_reviewable = $this->is_true( $is_reviewable );
            if( ($is_reviewable) ){

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_reviewable','=',true);
                    });
                });

            }else{

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_reviewable','=',false);
                        $query = $query->orWhereNull('is_reviewable');
                    });
                });

            }
        }

        // is_cloned_child ( is_bool($variable) )
        if( ($request->has('is_cloned_child')) ){
            /*
            $is_cloned_child_val_true = "true";
            $is_cloned_child_val_false = "false";
            $is_cloned_child_val_temp = $request->input('is_cloned_child');

            if( (strcasecmp($is_cloned_child_val_temp, $is_cloned_child_val_true) == 0) ){

                $query = $query->whereHas('twUsers', function($query){
                    $query = $query->where('is_cloned','=',true);
                });

            }else if( (strcasecmp($is_cloned_child_val_temp, $is_cloned_child_val_false) == 0) ){

                $query = $query->whereDoesntHave('twUsers', function($query){
                    $query = $query->where('is_cloned','=',true);
                });

            }
            */
            $is_cloned_child = $request->input('is_cloned_child');
            $is_cloned_child = $this->is_true( $is_cloned_child );
            if( ($is_cloned_child) ){
                /*
                $query = $query->whereHas('twUsers', function($query){
                    $query = $query->where('is_cloned','=',true);
                });
                */
                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_cloned_child','=',true);
                    });
                });

            }else{
                /*
                $query = $query->whereDoesntHave('twUsers', function($query){
                    $query = $query->where('is_cloned','=',true);
                });
                */
                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_cloned_child','=',false);
                        $query = $query->orWhereNull('is_cloned_child');
                    });
                });

            }
        }

        //description
        if( (($request->has("description")) && ($request->filled("description"))) ){
            $description = $request->input("description");
            $query = $query->where('description', 'like', '%' . $description . '%');
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

    public function changeDoneTrue(Request $request, TW $tW){
        //
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        $loginUserObj = Login::getUserData();
        $current_user = $loginUserObj->mail;
        $tWClone = clone $tW;
        // do process
        $twData = array(
            'is_done' => true,
            'done_user' => $current_user,
            'status_id' => TWStatusEnum::CLOSE,
            'done_date' => DB::raw('now()')
        );
        // Start transaction!
        DB::beginTransaction();

        try {
            $updatedTW = $tWClone->update( $twData );

            event(new TWCloseEvent($tWClone));
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

    public function changeDoneFalse(Request $request, TW $tW){
        //
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        $loginUserObj = Login::getUserData();
        $current_user = $loginUserObj->mail;
        $tWClone = clone $tW;
        // do process
        $twData = array(
            'is_done' => false,
            'done_user' => null,
            'status_id' => TWStatusEnum::OPEN,
            'done_date' => null,
            'due_date' => Input::get('due_date')
        );
        // Start transaction!
        DB::beginTransaction();

        try {

            $updatedTW = $tWClone->update( $twData );

            $newTWInfo = TWInfo::create(array(
                'is_visible' => true,
                't_w_id' => $tWClone->id,
                'description' => Input::get('description'),
                'created_user' => $current_user
            ));

            event(new TWResubmitEvent($tWClone));
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

    public function showCreatedTW(Request $request, TW $tW){
        if(view()->exists('tw_created_show_all')){
            return View::make('tw_created_show_all');
        }
    }

    public function showOwneTW(Request $request, TW $tW){
        if(view()->exists('tw_owne_show_all')){
            return View::make('tw_owne_show_all');
        }
    }

    public function showClone(Request $request, TW $tW){
        if(view()->exists('tw_clone')){
            return View::make('tw_clone', ['tW' => $tW]);
        }
    }

    public function doClone(Request $request, TW $tW){
        $tWClone = clone $tW;
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        // do process
        $loginUserObj = Login::getUserData();
        $current_user = $loginUserObj->mail;
        $company_name = $loginUserObj->company;
        $department_name = $loginUserObj->department;
        $twResourceDir = TWMetaEnum::RESOURCE_DIR .'/'. uniqid( time() ) . '_';

        $twData = array(
            'meeting_category_id'     => Input::get('meeting_category_id'),
            'title'     => Input::get('title'),
            'start_date'     => Input::get('start_date'),
            'due_date'     => Input::get('due_date'),
            'description'     => Input::get('description'),
            'created_user'     => $current_user,
            'company_name'     => $company_name,
            'department_name'     => $department_name,
            'is_visible' => true,
            'status_id' => TWStatusEnum::OPEN,
            'resource_dir' => $twResourceDir,
            'is_cloned' => false,
            'is_cloned_child' => true,
            'cloned_parent_id' => $tWClone->id,
            'is_archived' => false,
            'is_reviewable' => true
        );

        $twUserData = (array) Input::get('own_user');

        //$userAttachmentData = (array) $request->file('var_user_attachment');
        $tempTWInfos = $tWClone->twInfos;

        // Start transaction!
        DB::beginTransaction();

        try {
            //$updatedTW = $tWClone->update( array('is_cloned' => true) );
            $currentTWUser = $tWClone->twUsers->where('own_user', '=', $current_user)->first();
            if( $currentTWUser ){
                $updatedCurrentTWUser = $currentTWUser->update( array('is_cloned' => true) );
            }

            //create directory
            if(!Storage::exists($twResourceDir)) {
                Storage::makeDirectory($twResourceDir, 0775, true); //creates directory
            }
            // Validate, then create if valid
            $newTW = TW::create( $twData );

            foreach($twUserData as $key => $value){
                $tempTWUser = new User();
                $tempTWUser->mail = $value;
                $tempTWUser = $tempTWUser->getUser();

                $newTWUser = TWUser::create(array(
                    't_w_id' => $newTW->id,
                    'is_visible' => true,
                    'own_user' => $tempTWUser->mail,
                    'company_name' => $tempTWUser->company,
                    'department_name' => $tempTWUser->department,
                    'is_cloned' => false
                ));
            }

            foreach($tempTWInfos as $key => $value){
                $tempTWInfoClone = clone $value;

                $newTWInfo = TWInfo::create(array(
                    'is_visible' => $tempTWInfoClone->is_visible,
                    't_w_id' => $newTW->id,
                    'description' => $tempTWInfoClone->description,
                    'created_user' => $current_user
                ));

                $tempUserAttachments = $tempTWInfoClone->userAttachments;

                foreach($tempUserAttachments as $key => $value){
                    $tempUserAttachmentClone = clone $value;

                    if(Storage::exists( $tempUserAttachmentClone->link_url )) {
                        $is_visible = $tempUserAttachmentClone->is_visible;
                        $file_original_name = $tempUserAttachmentClone->file_original_name;
                        $file_type = $tempUserAttachmentClone->file_type;
                        $newTWResourceDir = $newTW->resource_dir;
                        $tWCloneResourceDir = $tWClone->resource_dir;
                        $tempUserAttachmentCloneLinkUrl = $tempUserAttachmentClone->link_url;
                        $newUserAttachmentLinkUrl = str_replace($tWCloneResourceDir, $newTWResourceDir, $tempUserAttachmentCloneLinkUrl);
                        Storage::copy($tempUserAttachmentCloneLinkUrl, $newUserAttachmentLinkUrl);
                        //chmod(Storage::path($newUserAttachmentLinkUrl), 0755);

                        $newUserAttachment = $newTWInfo->userAttachments()->create(array(
                            'is_visible' => $is_visible,
                            'attached_by' => $current_user,
                            'file_original_name' => $file_original_name,
                            'file_type' => $file_type,
                            'link_url' => $newUserAttachmentLinkUrl
                        ));
                    }
                }
            }

            event(new TWCreateEvent($newTW));
        }catch(\Exception $e){

            DB::rollback();
            //delete directory
            if(Storage::exists($twResourceDir)) {
                Storage::deleteDirectory($twResourceDir);
            }

            $data = array(
                'title' => 'error',
                'text' => 'error',
                'type' => 'warning',
                'timer' => 3000
            );

            return redirect()->back()->withInput();

        }

        // If we reach here, then
        // data is valid and working.
        // Commit the queries!
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

        //return Response::json( $data );
        return redirect()->route('tw.showOwneTW');
    }

    public function changeArchivedTrue(Request $request, TW $tW){
        //
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        $loginUserObj = Login::getUserData();
        $current_user = $loginUserObj->mail;
        $tWClone = clone $tW;
        // do process
        $twData = array(
            'is_archived' => true
        );
        // Start transaction!
        DB::beginTransaction();

        try {
            $updatedTW = $tWClone->update( $twData );

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

    public function changeArchivedFalse(Request $request, TW $tW){
        //
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        $loginUserObj = Login::getUserData();
        $current_user = $loginUserObj->mail;
        $tWClone = clone $tW;
        // do process
        $twData = array(
            'is_archived' => false
        );
        // Start transaction!
        DB::beginTransaction();

        try {
            $updatedTW = $tWClone->update( $twData );

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

    public function changeReviewableTrue(Request $request, TW $tW){
        //
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        $loginUserObj = Login::getUserData();
        $current_user = $loginUserObj->mail;
        $tWClone = clone $tW;
        // do process
        $twData = array(
            'is_reviewable' => true
        );
        // Start transaction!
        DB::beginTransaction();

        try {
            $updatedTW = $tWClone->update( $twData );

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

    public function changeReviewableFalse(Request $request, TW $tW){
        //
        $data = array('title' => '', 'text' => '', 'type' => '', 'timer' => 3000);
        $loginUserObj = Login::getUserData();
        $current_user = $loginUserObj->mail;
        $tWClone = clone $tW;
        // do process
        $twData = array(
            'is_reviewable' => false
        );
        // Start transaction!
        DB::beginTransaction();

        try {
            $updatedTW = $tWClone->update( $twData );

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

    public function getTWInfoCount(Request $request, TW $tW){
        //
        $data = array('count' => 0);
        $loginUserObj = Login::getUserData();
        $current_user = $loginUserObj->mail;
        $tWClone = clone $tW;
        // Start transaction!
        //DB::beginTransaction();
        try {
            $count = 0;
            $count = $tWClone->twInfos()->count();
            $data = array('count' => $count);
        }catch(\Exception $e){
            //DB::rollback();
            $data = array('count' => 0);
            //return Response::json( $data );
        }
        //DB::commit();
        return Response::json( $data );
    }

    public function downloadTWs(Request $request){
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
        $date_today = Carbon::now()->format('Y-m-d');

        $draw = $request->get('draw');

        $tw = new TW();

        $query = $tw->with(['twUsers', 'twInfos', 'status', 'meetingCategory'])->where('is_visible', '=', true);

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
                $query = $query->where('title', 'like', '%' . $search . '%');
            }
        }

        // created_user
        if( ($request->get('created_user')) && (!empty($request->get('created_user'))) ){
            $created_user = $request->get('created_user');
            $query = $query->where('created_user', '=', $created_user);
        }

        // meeting_category_id
        if( ($request->get('meeting_category_id')) && (!empty($request->get('meeting_category_id'))) ){
            $meeting_category_id =  $request->get('meeting_category_id');
            $query = $query->where('meeting_category_id', '=', $meeting_category_id);
        }

        // status
        if( ($request->get('status_id')) && (!empty($request->get('status_id'))) ){
            $status_id =  $request->get('status_id');
            $query = $query->where('status_id', '=', $status_id);
        }

        // start date
        if( ($request->get('start_date')) && (!empty($request->get('start_date'))) ){
            $start_date =  $request->get('start_date');
            $query = $query->whereDate('start_date', 'like', $start_date . '%');
        }

        // start date from
        if( ($request->get('start_date_from')) && (!empty($request->get('start_date_from'))) ){
            $start_date_from =  $request->get('start_date_from');
            $query = $query->whereDate('start_date', '>=', $start_date_from);
        }

        // start date to
        if( ($request->get('start_date_to')) && (!empty($request->get('start_date_to'))) ){
            $start_date_to =  $request->get('start_date_to');
            $query = $query->whereDate('start_date', '<=', $start_date_to);
        }

        // due date
        if( ($request->get('due_date')) && (!empty($request->get('due_date'))) ){
            $due_date =  $request->get('due_date');
            $query = $query->whereDate('due_date', 'like', $due_date . '%');
        }

        // due date from
        if( ($request->get('due_date_from')) && (!empty($request->get('due_date_from'))) ){
            $due_date_from =  $request->get('due_date_from');
            $query = $query->whereDate('due_date', '>=', $due_date_from);
        }

        // due date to
        if( ($request->get('due_date_to')) && (!empty($request->get('due_date_to'))) ){
            $due_date_to =  $request->get('due_date_to');
            $query = $query->whereDate('due_date', '<=', $due_date_to);
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

        // own user
        if( ($request->get('own_user')) && (!empty($request->get('own_user'))) ){
            $own_user =  $request->get('own_user');
            $query = $query->whereHas('twUsers', function($query) use ($own_user){
                $query = $query->where('own_user', '=', $own_user);
            });
        }

        // own company
        if( ($request->get('own_company')) && (!empty($request->get('own_company'))) ){
            $own_company =  $request->get('own_company');
            $query = $query->whereHas('twUsers', function($query) use ($own_company){
                $query = $query->where('company_name','=',$own_company);
                $query = $query->distinct('t_w_id');
            });
        }

        // own department
        if( ($request->get('own_department')) && (!empty($request->get('own_department'))) ){
            $own_department =  $request->get('own_department');
            $query = $query->whereHas('twUsers', function($query) use ($own_department){
                $query = $query->where('department_name','=',$own_department);
                $query = $query->distinct('t_w_id');
            });
        }

        // created company
        if( ($request->get('created_company')) && (!empty($request->get('created_company'))) ){
            $created_company = $request->get('created_company');
            $query = $query->where('company_name', '=', $created_company);
        }

        // created department
        if( ($request->get('created_department')) && (!empty($request->get('created_department'))) ){
            $created_department = $request->get('created_department');
            $query = $query->where('department_name', '=', $created_department);
        }

        // is_visible
        if( ($request->get('is_visible') != null) ){
            $is_visible =  $request->get('is_visible');
            $is_visible = $this->is_true( $is_visible );
            $query = $query->where('is_visible', '=', $is_visible);
        }

        // is_done
        if( ($request->get('is_done') != null) ){
            $is_done =  $request->get('is_done');
            $is_done = $this->is_true( $is_done );
            $query = $query->where('is_done', '=', $is_done);
        }

        // progress
        if( ($request->get('progress')) && (!empty($request->get('progress'))) ){
            $progress =  $request->get('progress');
            if( $progress == TWStatusEnum::COMPLETED ){
                $query = $query->where('is_done','=',true);
            }else if( $progress == TWStatusEnum::PASS ){
                $query = $query->where(function($query){
                    $query = $query->where('is_done','=',true);
                    $query = $query->whereNotNull('done_date');
                    $query = $query->where(DB::raw('DATE(due_date)'),'>=',DB::raw('DATE(done_date)'));
                });
            }else if( $progress == TWStatusEnum::FAIL ){
                /*$query = $query->where(function($query){
                    $query->where('is_done','=',false);
                    $query->orWhereNull('is_done');
                });
                $query = $query->where(function($query){
                    $query->whereRaw('due_date > done_date');
                    $query->where(DB::raw("DATE(due_date) > DATE(done_date)"));
                    $query->orWhereDate('due_date','<',Carbon::now()->format('Y-m-d'));
                    $query->orWhereNull('done_date');
                });*/
                $query = $query->where(function($query) use ($date_today){
                    $query = $query->where(function($query){
                        $query = $query->whereNotNull('done_date');
                        $query = $query->where(DB::raw('DATE(due_date)'),'<',DB::raw('DATE(done_date)'));
                    });
                    $query = $query->orWhere(function($query) use ($date_today){
                        $query = $query->whereDate('due_date','<',$date_today);
                        $query = $query->where(function($query){
                            $query = $query->where('is_done','=',false);
                            $query = $query->orWhereNull('is_done');
                        });
                    });
                });
            }else if( $progress == TWStatusEnum::INPROGRESS ){
                $query = $query->where(function($query) use ($date_today){
                    $query = $query->where(function($query){
                        $query = $query->where('is_done','=',false);
                        $query = $query->orWhereNull('is_done');
                    });
                    $query = $query->where(function($query) use ($date_today){
                        //$query->whereRaw('due_date >= done_date');
                        $query = $query->orWhereDate('due_date','>=',$date_today);
                    });
                });
            }else if( $progress == TWStatusEnum::OPEN ){
                $query = $query->where(function($query){
                    $query = $query->where('is_done','=',false);
                    $query = $query->orWhereNull('is_done');
                });
            }else if( $progress == TWStatusEnum::CLOSE ){
                $query = $query->where('is_done','=',true);
            }else if( $progress == TWStatusEnum::FAIL_WITH_COMPLETED ){
                $query = $query->where(function($query){
                    $query = $query->where('is_done','=',true);
                    $query = $query->whereNotNull('done_date');
                    $query = $query->where(DB::raw('DATE(due_date)'),'<',DB::raw('DATE(done_date)'));
                });
            }else if( $progress == TWStatusEnum::FAIL_WITH_UNCOMPLETED ){
                $query = $query->where(function($query) use ($date_today){
                    $query = $query->whereDate('due_date','<',$date_today);
                    $query = $query->where(function($query){
                        $query = $query->where('is_done','=',false);
                        $query = $query->orWhereNull('is_done');
                    });
                });
            }
        }

        // is_archived ( is_bool($variable) )
        if( ($request->has('is_archived')) ){
            /*
            $is_archived_val_true = "true";
            $is_archived_val_false = "false";
            $is_archived_val_temp = $request->input('is_archived');

            if( (strcasecmp($is_archived_val_temp, $is_archived_val_true) == 0) ){

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_archived','=',true);
                    });
                });

            }else if( (strcasecmp($is_archived_val_temp, $is_archived_val_false) == 0) ){

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_archived','=',false);
                        $query = $query->orWhereNull('is_archived');
                    });
                });

            }
            */
            $is_archived = $request->input('is_archived');
            $is_archived = $this->is_true( $is_archived );
            if( ($is_archived) ){

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_archived','=',true);
                    });
                });

            }else{

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_archived','=',false);
                        $query = $query->orWhereNull('is_archived');
                    });
                });

            }
        }

        // is_reviewable ( is_bool($variable) )
        if( ($request->has('is_reviewable')) ){
            /*
            $is_reviewable_val_true = "true";
            $is_reviewable_val_false = "false";
            $is_reviewable_val_temp = $request->input('is_reviewable');

            if( (strcasecmp($is_reviewable_val_temp, $is_reviewable_val_true) == 0) ){

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_reviewable','=',true);
                    });
                });

            }else if( (strcasecmp($is_reviewable_val_temp, $is_reviewable_val_false) == 0) ){

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_reviewable','=',false);
                        $query = $query->orWhereNull('is_reviewable');
                    });
                });

            }
            */
            $is_reviewable = $request->input('is_reviewable');
            $is_reviewable = $this->is_true( $is_reviewable );
            if( ($is_reviewable) ){

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_reviewable','=',true);
                    });
                });

            }else{

                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_reviewable','=',false);
                        $query = $query->orWhereNull('is_reviewable');
                    });
                });

            }
        }

        // is_cloned_child ( is_bool($variable) )
        if( ($request->has('is_cloned_child')) ){
            /*
            $is_cloned_child_val_true = "true";
            $is_cloned_child_val_false = "false";
            $is_cloned_child_val_temp = $request->input('is_cloned_child');

            if( (strcasecmp($is_cloned_child_val_temp, $is_cloned_child_val_true) == 0) ){

                $query = $query->whereHas('twUsers', function($query){
                    $query = $query->where('is_cloned','=',true);
                });

            }else if( (strcasecmp($is_cloned_child_val_temp, $is_cloned_child_val_false) == 0) ){

                $query = $query->whereDoesntHave('twUsers', function($query){
                    $query = $query->where('is_cloned','=',true);
                });

            }
            */
            $is_cloned_child = $request->input('is_cloned_child');
            $is_cloned_child = $this->is_true( $is_cloned_child );
            if( ($is_cloned_child) ){
                /*
                $query = $query->whereHas('twUsers', function($query){
                    $query = $query->where('is_cloned','=',true);
                });
                */
                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_cloned_child','=',true);
                    });
                });

            }else{
                /*
                $query = $query->whereDoesntHave('twUsers', function($query){
                    $query = $query->where('is_cloned','=',true);
                });
                */
                $query = $query->where(function($query){
                    $query = $query->where(function($query){
                        $query = $query->where('is_cloned_child','=',false);
                        $query = $query->orWhereNull('is_cloned_child');
                    });
                });

            }
        }

        //description
        if( (($request->has("description")) && ($request->filled("description"))) ){
            $description = $request->input("description");
            $query = $query->where('description', 'like', '%' . $description . '%');
        }

        // get filtered record count
        /*
        $recordsFiltered = $query->count();
        */

        // get limit value
        /*
        if( $request->get('length') ){
            $length = intval( $request->get('length') );
            $length = abs( $length );
            $query = $query->limit($length);
        }
        */
        // set default value for length (PHP_INT_MAX)
        /*
        if( $length <= 0 ){
            $length = PHP_INT_MAX;
            $length = abs( $length );
            //$length = 0;
        }
        */
        // get offset value
        /*
        if( $request->get('start') ){
            $start = intval( $request->get('start') );
            $start = abs( $start );
        }else if( $request->get('page') ){
            $start = intval( $request->get('page') );
            //$start = abs( ( ( $start - 1 ) * $length ) );
            $start = ( ( $start - 1 ) * $length );
            $start = abs( $start );
        }
        */
        // filter with offset value
        /*
        if( $start > 0 ){
            //$query = $query->limit($length)->skip($start);
            $query = $query->limit($length)->offset($start);
        }else if( $length > 0 ){
            $query = $query->limit($length);
        }
        */
        // order
        $query->orderBy('id', 'desc');
        $query->orderBy('updated_at', 'desc');

        // get data
        $queryResult = $query->get();

        /* *** */
        $temp_result_array_1 = array();
        $temp_result_array_2 = array();
        foreach($queryResult as $key => $value){
            //$temp_result_array_2 = array( 11 );
            $temp_result_array_2 = array_fill(0, 10, null);
            //$value->createdUser();
            //$value->doneUser();
            $temp_result_array_2[0] = $value->id;
            if( ($value->meetingCategory) ){
                $temp_meetingCategory = $value->meetingCategory;
                $temp_result_array_2[1] = $temp_meetingCategory->name;
            }
            $temp_result_array_2[2] = $value->title;
            $temp_result_array_2[3] = $value->description;
            $temp_result_array_2[4] = $value->start_date;
            $temp_result_array_2[5] = $value->due_date;
            $temp_result_array_2[6] = $value->done_date;
            $temp_result_array_2[7] = $value->created_user;
            $temp_result_array_2[8] = $value->done_user;
            if( ($value->twUsers) ){
                $temp_twUsers = $value->twUsers;
                $temp_twUser_Array = array();
                foreach($temp_twUsers as $k => $v){
                    array_push($temp_twUser_Array, $v->own_user);
                }
                $temp_result_array_2[9] = implode(", ", $temp_twUser_Array);
                //$temp_result_array_2[9] = join(", ", $temp_twUser_Array);
            }
            if( ($value->status) ){
                $temp_status = $value->status;
                $temp_result_array_2[10] = $temp_status->name;
            }

            array_push($temp_result_array_1, $temp_result_array_2);
        }
        /* *** */

        $export = new CommonExportWorkBook( $temp_result_array_1 );
        return Excel::download($export, 'download.xlsx');
    }
}
