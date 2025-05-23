@extends('layouts.home_layout')

@section('section_stylesheet')
    @parent
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('node_modules/admin-lte/bower_components/select2/dist/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('node_modules/select2-bootstrap-theme/dist/select2-bootstrap.min.css') }}" />
    <!-- DataTable -->
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-select-bs/css/select.bootstrap.min.css') }}" />
    <!-- Bootstrap Datepicker -->
    <link rel="stylesheet" href="{{ asset('node_modules/admin-lte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" />
@endsection

@section('section_script_main')
    @parent
@endsection

@section('content')
<!-- row -->
<div class="row">
    
    <!-- col -->
    <div class="col-sm-12">
        
        <!-- accordion -->
        <div class="panel-group" id="accordion">
            
            <!-- panel -->
            <div id="collapseOneParent" class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#collapseOneParent" href="#collapseOne"><span class="glyphicon glyphicon-plus"></span> Filter</a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse out">
                    <div class="panel-body">
                        <!-- --- -->
                        <!-- row -->
                        <div class="row">

                            <!-- col -->
                            <div class="col-sm-12">
                                <!-- form -->
                                <form action="#" method="POST" class="col-sm-9" autocomplete="off" id="twForm" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <!-- -->
                                    <div class="row form-group col-sm-12">
                                        <div class="panel panel-default panel-warning">
                                            <div class="panel-heading"><strong class="lead text-danger">What</strong></div>
                                            <div class="panel-body">
                                                <!-- -->
                                                <!-- form-group -->
                                                <div class="form-group col-sm-12">
                                                    <label for="meeting_category_id" class="col-sm-2 control-label text-muted small">Category</label>
                                                    <div class="col-sm-10">
                                                        <!-- p class="form-control-static"></p -->
                                                        <select class="form-control select2" id="meeting_category_id" name="meeting_category_id" value="{{ old('meeting_category_id') }}" data-placeholder="Category" style="width: 100%;">
                                                        </select>
                                                    </div>
                                                    <!-- span id="form-control" class="help-block"></span -->
                                                </div>
                                                <!-- /.form-group -->

                                                <!-- form-group -->
                                                <div class="form-group col-sm-12">
                                                    <label for="title" class="col-sm-2 control-label text-muted small">Subject</label>
                                                    <div class="col-sm-10">
                                                        <!-- p class="form-control-static"></p -->
                                                        <input type="text" class="form-control" id="title" name="title" placeholder="3W" value="{{ old('title') }}"/>
                                                    </div>
                                                    <!-- span id="form-control" class="help-block"></span -->
                                                </div>
                                                <!-- /.form-group -->
                                                <!-- -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- -->
                                    
                                    <!-- -->
                                    <div class="row form-group col-sm-12">
                                        <div class="panel panel-default panel-warning">
                                            <div class="panel-heading"><strong class="lead text-danger">Who</strong></div>
                                            <div class="panel-body">
                                                <!-- -->
                                                <!-- form-group -->
                                                <div class="form-group col-sm-12">
                                                    <label for="own_user" class="col-sm-2 control-label text-muted small">Owner</label>
                                                    <div class="col-sm-10">
                                                        <!-- p class="form-control-static"></p -->
                                                        <select class="form-control select2" id="own_user" name="own_user" value="{{ old('own_user') }}" data-placeholder="Owner" style="width: 100%;">
                                                        </select>
                                                    </div>
                                                    <!-- span id="form-control" class="help-block"></span -->
                                                </div>
                                                <!-- /.form-group -->
                                                <!-- -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- -->
                                    
                                    <!-- -->
                                    <div class="row form-group col-sm-12">
                                        <div class="panel panel-default panel-warning">
                                            <div class="panel-heading"><strong class="lead text-danger">When</strong></div>
                                            <div class="panel-body">
                                                <!-- -->
                                                <!-- form-group -->
                                                <div class="form-group col-sm-12" style="display : none !important;">
                                                    <!-- skip div -->
                                                    <div class="col-sm-2"></div>
                                                    <!-- /.skip div -->

                                                    <!-- form-group -->
                                                    <div class="form-group col-sm-12 col-md-5 col-lg-5">
                                                        <label for="start_date_from" class="col-sm-2 control-label text-muted small">Start Date (From)</label>
                                                        <div class="col-sm-10">
                                                            <!-- p class="form-control-static"></p -->
                                                            <div class="input-group date">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input type="text" class="form-control pull-right" id="start_date_from" name="start_date_from" placeholder="Start Date (From)" value="{{ old('start_date_from') }}"/>
                                                            </div>
                                                        </div>
                                                        <!-- span id="form-control" class="help-block"></span -->
                                                    </div>
                                                    <!-- /.form-group -->

                                                    <!-- form-group -->
                                                    <div class="form-group col-sm-12 col-md-5 col-lg-5">
                                                        <label for="start_date_to" class="col-sm-2 control-label text-muted small">Start Date (To)</label>
                                                        <div class="col-sm-10">
                                                            <!-- p class="form-control-static"></p -->
                                                            <div class="input-group date">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input type="text" class="form-control pull-right" id="start_date_to" name="start_date_to" placeholder="Start Date (To)" value="{{ old('start_date_to') }}"/>
                                                            </div>
                                                        </div>
                                                        <!-- span id="form-control" class="help-block"></span -->
                                                    </div>
                                                    <!-- /.form-group -->
                                                </div>
                                                <!-- /.form-group -->

                                                <!-- form-group -->
                                                <div class="form-group col-sm-12">
                                                    <!-- skip div -->
                                                    <div class="col-sm-2"></div>
                                                    <!-- /.skip div -->

                                                    <!-- form-group -->
                                                    <div class="form-group col-sm-12 col-md-5 col-lg-5">
                                                        <label for="due_date_from" class="col-sm-2 control-label text-muted small">Due Date (From)</label>
                                                        <div class="col-sm-10">
                                                            <!-- p class="form-control-static"></p -->
                                                            <div class="input-group date">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input type="text" class="form-control pull-right" id="due_date_from" name="due_date_from" placeholder="Due Date (From)" value="{{ old('due_date_from') }}"/>
                                                            </div>
                                                        </div>
                                                        <!-- span id="form-control" class="help-block"></span -->
                                                    </div>
                                                    <!-- /.form-group -->

                                                    <!-- form-group -->
                                                    <div class="form-group col-sm-12 col-md-5 col-lg-5">
                                                        <label for="due_date_to" class="col-sm-2 control-label text-muted small">Due Date (To)</label>
                                                        <div class="col-sm-10">
                                                            <!-- p class="form-control-static"></p -->
                                                            <div class="input-group date">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input type="text" class="form-control pull-right" id="due_date_to" name="due_date_to" placeholder="Due Date (To)" value="{{ old('due_date_to') }}"/>
                                                            </div>
                                                        </div>
                                                        <!-- span id="form-control" class="help-block"></span -->
                                                    </div>
                                                    <!-- /.form-group -->
                                                </div>
                                                <!-- /.form-group -->
                                                <!-- -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- -->
                                    
                                    <!-- -->
                                    <div class="row form-group col-sm-12">
                                        <div class="panel panel-default panel-warning">
                                            <!-- div class="panel-heading"><strong class="lead text-danger">Description</strong></div -->
                                            <div class="panel-body">
                                                <!-- -->
                                                <!-- form-group -->
                                                <div class="form-group col-sm-12">
                                                    <label for="description" class="col-sm-2 control-label text-muted small">Description</label>
                                                    <div class="col-sm-10">
                                                        <!-- p class="form-control-static"></p -->
                                                        <textarea class="form-control rounded-0" id="description" name="description" placeholder="Description" rows="5">{{ old('description') }}</textarea>
                                                    </div>
                                                    <!-- span id="form-control" class="help-block"></span -->
                                                </div>
                                                <!-- /.form-group -->
                                                <!-- -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- -->
                                    
                                    <!-- -->
                                    <div class="row form-group col-sm-12">
                                        <div class="panel panel-default panel-warning">
                                            <!-- div class="panel-heading"><strong class="lead text-danger">Description</strong></div -->
                                            <div class="panel-body">
                                                <!-- -->
                                                <!-- form-group -->
                                                <div class="form-group col-sm-12" style="display : none !important;">
                                                    <label for="created_department_name" class="col-sm-2 control-label text-muted small">Created Department</label>
                                                    <div class="col-sm-10">
                                                        <!-- p class="form-control-static"></p -->
                                                        <select class="form-control select2" id="created_department_name" name="created_department_name" value="{{ old('created_department_name') }}" data-placeholder="Department" style="width: 100%;">
                                                        </select>
                                                    </div>
                                                    <!-- span id="form-control" class="help-block"></span -->
                                                </div>
                                                <!-- /.form-group -->

                                                <!-- form-group -->
                                                <div class="form-group col-sm-12">
                                                    <label for="own_department_name" class="col-sm-2 control-label text-muted small">Department</label>
                                                    <div class="col-sm-10">
                                                        <!-- p class="form-control-static"></p -->
                                                        <select class="form-control select2" id="own_department_name" name="own_department_name" value="{{ old('own_department_name') }}" data-placeholder="Department" style="width: 100%;">
                                                        </select>
                                                    </div>
                                                    <!-- span id="form-control" class="help-block"></span -->
                                                </div>
                                                <!-- /.form-group -->



                                                <!-- form-group -->
                                                <div class="form-group col-sm-12">
                                                    <label for="status_id" class="col-sm-2 control-label text-muted small">Job Status</label>
                                                    <div class="col-sm-10">
                                                        <!-- p class="form-control-static"></p -->
                                                        <select class="form-control select2" id="status_id" name="status_id" value="{{ old('status_id') }}" style="width: 100%;">
                                                            <option value=""> All </option>
                                                            <option value="{!! App\Enums\TWStatusEnum::OPEN !!}"> Open </option>
                                                            <option value="{!! App\Enums\TWStatusEnum::CLOSE !!}"> Closed </option>
                                                            <option value="{!! App\Enums\TWStatusEnum::INPROGRESS !!}"> Inprogress </option>
                                                            <option value="{!! App\Enums\TWStatusEnum::PASS !!}"> Pass </option>
                                                            <option value="{!! App\Enums\TWStatusEnum::FAIL !!}"> Fail </option>
                                                            <option value="{!! App\Enums\TWStatusEnum::COMPLETED !!}"> Done </option>
                                                            <option value="{!! App\Enums\TWStatusEnum::FAIL_WITH_COMPLETED !!}"> Fail (Close) </option>
                                                            <option value="{!! App\Enums\TWStatusEnum::FAIL_WITH_UNCOMPLETED !!}"> Fail (Open) </option>
                                                        </select>
                                                    </div>
                                                    <!-- span id="form-control" class="help-block"></span -->
                                                </div>
                                                <!-- /.form-group -->

                                                <!-- form-group -->
                                                <div class="form-group col-sm-12">
                                                    <label for="is_reviewable" class="col-sm-2 control-label text-muted small">Archived</label>
                                                    <div class="col-sm-10">
                                                        <!-- p class="form-control-static"></p -->
                                                        <select class="form-control select2" id="is_reviewable" name="is_reviewable" value="{{ old('is_reviewable') }}" style="width: 100%;">
                                                            <option value="true"> No </option>
                                                            <option value="false"> Yes </option>
                                                            <option value=""> All </option>
                                                        </select>
                                                    </div>
                                                    <!-- span id="form-control" class="help-block"></span -->
                                                </div>
                                                <!-- /.form-group -->

                                                <!-- form-group -->
                                                <div class="form-group col-sm-12">
                                                    <!-- btn-toolbar -->
                                                    <div class="col col-sm-12">
                                                        <!-- div class="btn-group btn-group-lg pull-right" -->
                                                            <button type="submit" class="btn btn-primary pull-right" id="submit">Search</button>
                                                            <button type="button" class="btn btn-success pull-right" id="download">Download</button>
                                                            <button type="reset" class="btn btn-info pull-right" id="reset">Reset</button>
                                                        <!-- /div -->
                                                    </div>
                                                </div>
                                                <!-- /.form-group -->
                                                <!-- -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- -->

                                </form>
                                <!-- /.form -->
                            </div>
                            <!-- /.col -->

                        </div>
                        <!-- /.row -->
                        <!-- --- -->
                    </div>
                </div>
            </div>
            <!-- /.panel -->
            <!-- panel -->
            <div id="collapseTwoParent" class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#collapseTwoParent" href="#collapseTwo"><span class="glyphicon glyphicon-plus"></span> Filter Data List
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse in">
                    <div class="panel-body">
                        
                        <!-- --- -->
                        <!-- row -->
                        <div class="row">
                            <!-- col -->
                            <div class="col-sm-12">
                                <!-- table -->
                                <!-- class="table table-striped table-bordered dt-responsive nowrap" -->
                                <table id="twDataTable" class="table table-bordered" style="width:100%" width="100%" cellspacing="0" border="1" align="left"></table>
                                <!-- /.table -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                        <!-- --- -->
                        
                    </div>
                </div>
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.accordion -->
        
    </div>
    <!-- /.col -->
    
</div>
<!-- /.row -->

<!-- row -->
<div id="hidden_form_container_download_1" class="row" style="display: none;">
</div>
<!-- /.row -->
@endsection

@section('section_script')
    @parent
    <!-- Select2 -->
    <script src="{{ asset('node_modules/admin-lte/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- DataTable -->
    <script src="{{ asset('node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-responsive-bs/js/responsive.bootstrap.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-scroller-bs/js/scroller.bootstrap.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-select/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-select-bs/js/select.bootstrap.min.js') }}"></script>
    <!-- Bootstrap Datepicker -->
    <script src="{{ asset('node_modules/admin-lte/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('node_modules/admin-lte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    @includeIf('partials.tw_created_department_select', array())
    @includeIf('partials.tw_own_department_select', array())
    @includeIf('partials.meeting_category_select', array())
    @includeIf('partials.tw_summary.tw_own_user_select', array())
    @includeIf('partials.tw_summary.tw_data_table_company_all', array())
    <script>
    $(function() {
        "use strict";
        
        $('#start_date_from').datepicker({
            'autoclose': true,
            'format': "yyyy-mm-dd",
            'immediateUpdates': true,
            'todayBtn': true,
            'todayHighlight': true,
            'clearBtn': true
        });//.datepicker("setDate", new Date());
        
        $('#start_date_to').datepicker({
            'autoclose': true,
            'format': "yyyy-mm-dd",
            'immediateUpdates': true,
            'todayBtn': true,
            'todayHighlight': true,
            'clearBtn': true
        });//.datepicker("setDate", $('#date').val());
        
        $('#due_date_from').datepicker({
            'autoclose': true,
            'format': "yyyy-mm-dd",
            'immediateUpdates': true,
            'todayBtn': true,
            'todayHighlight': true,
            'clearBtn': true
        });//.datepicker("setDate", new Date());
        
        $('#due_date_to').datepicker({
            'autoclose': true,
            'format': "yyyy-mm-dd",
            'immediateUpdates': true,
            'todayBtn': true,
            'todayHighlight': true,
            'clearBtn': true
        });//.datepicker("setDate", $('#date').val());
        
        $('#status_id').select2();
        
        $('#is_reviewable').select2();
        
        @if((isset($progressVal)) && (!empty($progressVal)))
            var status_id = $('#status_id');
            $('#status_id').val({!! $progressVal !!}).trigger('change');
        @endif
        
        $('#reset').on('click', function(event){
            //$("form").get(0).reset();
            //$('form > input[type=reset]').trigger('click');
            var tableObj = $('#twDataTable');
            $('#created_department_name').val(null).trigger('change');
            $('#own_department_name').val(null).trigger('change');
            $('#own_user').val(null).trigger('change');
            $('#meeting_category_id').val(null).trigger('change');
            $('#status_id').val(null).trigger('change');
            $('#is_reviewable').val(null).trigger('change');
            //$('#twDataTable').DataTable().ajax.reload( null, false ); // user paging is not 
            
            tableObj.removeData();
            $('#twForm').trigger('submit');
        });
        
        $('#twForm').submit(function(event) {
            event.preventDefault();
            
            var tableObj = $('#twDataTable');
            var created_department_name = $('#created_department_name');
            var own_department_name = $('#own_department_name');
            var own_user = $('#own_user');
            var meeting_category_id = $('#meeting_category_id');
            var title = $('#title');
            var start_date_from = $('#start_date_from');
            var start_date_to = $('#start_date_to');
            var due_date_from = $('#due_date_from');
            var due_date_to = $('#due_date_to');
            var description = $('#description');
            var status_id = $('#status_id');
            var is_reviewable = $('#is_reviewable');
            
            var created_department_name_val = created_department_name.val();
            var own_department_name_val = own_department_name.val();
            var own_user_val = own_user.val();
            var meeting_category_id_val = meeting_category_id.val();
            var title_val = title.val();
            var start_date_from_val = start_date_from.val();
            var start_date_to_val = start_date_to.val();
            var due_date_from_val = due_date_from.val();
            var due_date_to_val = due_date_to.val();
            var description_val = description.val();
            var status_id_val = status_id.val();
            var is_reviewable_val = is_reviewable.val();
            
            tableObj.removeData();
            
            tableObj.is_cloned_child = "false";
            
            if( created_department_name_val ){
               tableObj.data('created_department_name', created_department_name_val);
            }
            if( own_department_name_val ){
               tableObj.data('own_department_name', own_department_name_val);
            }
            if( own_user_val ){
               tableObj.data('own_user', own_user_val);
            }
            if( meeting_category_id_val ){
               tableObj.data('meeting_category_id', meeting_category_id_val);
            }
            if( title_val ){
               tableObj.data('title', title_val);
            }
            if( start_date_from_val ){
               tableObj.data('start_date_from', start_date_from_val);
            }
            if( start_date_to_val ){
               tableObj.data('start_date_to', start_date_to_val);
            }
            if( due_date_from_val ){
               tableObj.data('due_date_from', due_date_from_val);
            }
            if( due_date_to_val ){
               tableObj.data('due_date_to', due_date_to_val);
            }
            if( description ){
               tableObj.data('description', description_val);
            }
            if( status_id_val ){
               tableObj.data('status_id', status_id_val);
            }
            /*
            if( (typeof is_reviewable_val === "boolean") ){
               tableObj.data('is_reviewable', is_reviewable_val);
            }
            */
            var is_reviewable_val_true = new String("true").trim().toLowerCase();
            var is_reviewable_val_false = new String("false").trim().toLowerCase();
            var is_reviewable_val_temp = new String(is_reviewable_val).trim().toLowerCase();
            if( ( is_reviewable_val_temp.localeCompare(is_reviewable_val_true) == 0 ) || ( is_reviewable_val_temp.localeCompare(is_reviewable_val_false) == 0 ) ){
               tableObj.data('is_reviewable', is_reviewable_val_temp);
            }
            
            //tableObj.DataTable().ajax.reload( null, false ); // user paging is not reset on reload
            tableObj.DataTable().ajax.reload( null, true ); // user paging is not reset on reload
            // scroll top
            $('html, body').animate({scrollTop:0}, 'slow');
        });
        
        //$('#twForm').trigger('submit');
    });
    </script>

    <script>
        var temp_twForm = null;
        var download = null;
        var hidden_form_container_download_1 = null;
        
        temp_twForm = $("#twForm");
        download = temp_twForm.find("#download");
        //hidden_form_container_download_1 = $("#hidden_form_container_download_1");
        download.off("click").on("click", function(event){
            event.preventDefault();
            //event.stopPropagation();
            //var formData = new FormData(document.querySelector('form'));
            //document.getElementById("form").target = "_blank";
            //var formData = new Object();
            hidden_form_container_download_1 = $("#hidden_form_container_download_1");
            hidden_form_container_download_1.empty();
            var form_1 = $("<form>");
            var url = "{!! route('tw.download', []) !!}";
            
            form_1.empty();
            form_1.attr("id", "hidden_form_1");
            form_1.attr("method", "get");
            form_1.attr("action", url);
            form_1.attr("target", "_blank");
            form_1.off("submit");
            
            var form_2_clone = null;
            var form_2_clone_serialize_array = null;
            //form_2_clone = download.closest("#twForm").clone(true);
            form_2_clone = download.closest("#twForm");
            form_2_clone_serialize_array = form_2_clone.serializeArray();
            
            var field_1 = null;
            field_1 = $("<input>");
            field_1.attr("type", "hidden");
            field_1.attr("name", "is_cloned_child");
            field_1.attr("value", "false");
            form_1.append(field_1);
            
            $.each(form_2_clone_serialize_array, function(k, v){
                //$form.append('<input type="hidden" name="' + k + '" value="' + v + '">');
                //console.log(k);
                //console.log(v);
                if( v ){
                    field_1 = $("<input>");
                    field_1.attr("type", "hidden");
                    field_1.attr("name", v.name);
                    //field_1.attr("value", v.value);
                    field_1.attr("value", (form_2_clone.find("#" + v.name).val()));
                    form_1.append(field_1);
                }
                
                console.log( v );
                console.log(v.name + " === " + field_1.attr("value"));
            });
            
            hidden_form_container_download_1.append(form_1);
            form_1.submit();
            
            delete form_2_clone;
            delete form_2_clone_serialize_array;
            hidden_form_container_download_1.empty();
        });
    </script>
@endsection