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
    <!-- Bootstrap FileInput -->
    <link href="{!! asset('node_modules/bootstrap-fileinput/css/fileinput.css') !!}" media="all" rel="stylesheet" type="text/css"/>
    <!-- link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous" -->
    <link href="{!! asset('node_modules/bootstrap-fileinput/themes/explorer-fas/theme.css') !!}" media="all" rel="stylesheet" type="text/css"/>
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
                        <a data-toggle="collapse" data-parent="#collapseOneParent" href="#collapseOne"><span class="glyphicon glyphicon-plus"></span> Edit 3W</a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <!-- --- -->
                        <!-- row -->
                        <div class="row">

                            <!-- col -->
                            <div class="col-sm-12">
                                <!-- form -->
                                <form action="{!! route('tw.update', ['tW' => $tW->id]) !!}" method="POST" class="col-sm-9" autocomplete="off" id="twForm" enctype="multipart/form-data">
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
                                                        <select class="form-control select2" id="meeting_category_id" name="meeting_category_id" value="{{ $tW->meeting_category_id }}" data-placeholder="Category" style="width: 100%;" required>
                                                            @if($tW)
                                                                @php
                                                                    $oldMeetingCategory = $tW->meetingCategory;
                                                                @endphp
                                                                @isset($oldMeetingCategory)
                                                                    <option value="{{ $oldMeetingCategory->id }}" selected> {{ $oldMeetingCategory->name }} </option>
                                                                @endisset
                                                            @endif
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
                                                        <input type="text" class="form-control" id="title" name="title" placeholder="Subject" value="{{ $tW->title }}" required/>
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
                                                        <select class="form-control select2" id="own_user" name="own_user[]" value="{{ old('own_user[]') }}" data-placeholder="Owner" style="width: 100%;" multiple="multiple">
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
                                                <div class="form-group col-sm-12">
                                                    <!-- skip div -->
                                                    <div class="col-sm-2"></div>
                                                    <!-- /.skip div -->

                                                    <!-- form-group -->
                                                    <div class="form-group col-sm-12 col-md-5 col-lg-5">
                                                        <label for="start_date" class="col-sm-2 control-label text-muted small">Start Date</label>
                                                        <div class="col-sm-10">
                                                            <!-- p class="form-control-static"></p -->
                                                            <div class="input-group date">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input type="text" class="form-control pull-right" id="start_date" name="start_date" placeholder="Start Date" value="{{ old('start_date') }}" required/>
                                                            </div>
                                                        </div>
                                                        <!-- span id="form-control" class="help-block"></span -->
                                                    </div>
                                                    <!-- /.form-group -->

                                                    <!-- form-group -->
                                                    <div class="form-group col-sm-12 col-md-5 col-lg-5">
                                                        <label for="due_date" class="col-sm-2 control-label text-muted small">Due Date</label>
                                                        <div class="col-sm-10">
                                                            <!-- p class="form-control-static"></p -->
                                                            <div class="input-group date">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input type="text" class="form-control pull-right" id="due_date" name="due_date" placeholder="Due Date" value="{{ old('due_date') }}" required/>
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
                                                        <textarea class="form-control rounded-0" id="description" name="description" placeholder="Description" rows="5" required>{{ $tW->description }}</textarea>
                                                    </div>
                                                    <!-- span id="form-control" class="help-block"></span -->
                                                </div>
                                                <!-- /.form-group -->

                                                <!-- form-group -->
                                                <div class="form-group col-sm-12">
                                                    <!-- btn-toolbar -->
                                                    <div class="col col-sm-12">
                                                        <!-- div class="btn-group btn-group-lg pull-right" -->
                                                            <button type="submit" class="btn btn-primary pull-right" id="submit">Submit</button>
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
                        <a data-toggle="collapse" data-parent="#collapseTwoParent" href="#collapseTwo"><span class="glyphicon glyphicon-plus"></span> 3W Owners</a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse out">
                    <div class="panel-body">
                        
                        <!-- --- -->
                        <!-- row -->
                        <div class="row">
                            <!-- col -->
                            <div class="col-sm-12">
                                <!-- table -->
                                <!-- class="table table-striped table-bordered dt-responsive nowrap" -->
                                <table id="twUserDataTable" class="table table-bordered" style="width:100%" width="100%" cellspacing="0" border="1" align="left"></table>
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
            <!-- panel -->
            <div id="collapseThreeParent" class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#collapseThreeParent" href="#collapseThree"><span class="glyphicon glyphicon-plus"></span> 3W Resourses</a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse out">
                    <div class="panel-body">
                        
                        <!-- --- -->
                        <!-- row -->
                        <div class="row">
                            <!-- col -->
                            <div class="col-sm-12">
                                <!-- table -->
                                <!-- class="table table-striped table-bordered dt-responsive nowrap" -->
                                <table id="twInfoDataTable" class="table table-bordered" style="width:100%" width="100%" cellspacing="0" border="1" align="left"></table>
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
    <!-- Bootstrap FileInput -->
    <script src="{!! asset('node_modules/bootstrap-fileinput/js/plugins/piexif.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('node_modules/bootstrap-fileinput/js/plugins/sortable.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('node_modules/bootstrap-fileinput/js/fileinput.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('node_modules/bootstrap-fileinput/js/locales/fr.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('node_modules/bootstrap-fileinput/js/locales/es.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('node_modules/bootstrap-fileinput/themes/fas/theme.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('node_modules/bootstrap-fileinput/themes/explorer-fas/theme.js') !!}" type="text/javascript"></script>

    @includeIf('partials.meeting_category_select', array())
    @includeIf('partials.tw_own_user_select', array())
    @includeIf('partials.tw_info_data_table_tw_edit', array())
    @includeIf('partials.tw_user_data_table_tw_edit', array())
    <script>
    $(function() {
        "use strict";
        
        $('#start_date').datepicker({
            'autoclose': true,
            'format': "yyyy-mm-dd",
            'immediateUpdates': true,
            'todayBtn': true,
            'todayHighlight': true,
            // 'widgetParent': ???,
            'widgetPositioning': {
                horizontal: "auto",
                vertical: "auto"
            },
            'toggleActive': true,
            'orientation': 'auto',
            'container': 'body'
        }).datepicker("setDate", moment('{!! $tW->start_date !!}', 'YYYY-MM-DD HH:mm:ss').toDate())
        .trigger("changeDate");
        
        $('#due_date').datepicker({
            'autoclose': true,
            'format': "yyyy-mm-dd",
            'immediateUpdates': true,
            'todayBtn': true,
            'todayHighlight': true,
            // 'widgetParent': ???,
            'widgetPositioning': {
                horizontal: "auto",
                vertical: "auto"
            },
            'toggleActive': true,
            'orientation': 'auto',
            'container': 'body'
        }).datepicker("setDate", moment('{!! $tW->due_date !!}', 'YYYY-MM-DD HH:mm:ss').toDate())
        .trigger("changeDate");
        
        $('#start_date').datepicker().on('show', function(e){
            $(this).datepicker("setEndDate", $('#due_date').val());
        });
        
        $('#due_date').datepicker().on('show', function(e){
            $(this).datepicker("setStartDate", $('#start_date').val());
        });
        
        $('#twForm').submit(function(event) {
            event.preventDefault();
            var form = $(this);
            var form_id = $(this).attr('id');
            var _token = '{{ Session::token() }}';

            var tableObj = $('#twUserDataTable');
            var own_user = form.find('#own_user');
            var meeting_category_id = form.find('#meeting_category_id');
            var title = form.find('#title');
            var start_date = form.find('#start_date');
            var due_date = form.find('#due_date');
            var description = form.find('#description');
            var submit = form.find('#submit');
            
            submit.attr("disabled", true);

            var formdata = new FormData( this );
            
            formdata.append('submit', true);
            // process the form
            $.ajax({
                type        : form.attr('method'), // define the type of HTTP verb we want to use (POST for our form)
                url         : form.attr('action'), // the url where we want to POST
                data        : formdata, // our data object
                //dataType    : 'json', // what type of data do we expect back from the server
                //encode      : true,
                processData : false,
                contentType : false,
                cache : false
            })
                // using the done promise callback
                .done(function(data) {
                    //console.log(data);
                    swal({
                        'title': data.title,
                        'text': data.text,
                        'type': data.type,
                        'timer': data.timer,
                        'showConfirmButton': false
                    });
                    //tableObj.DataTable().ajax.reload( null, false ); // user paging is not reset on 
                    tableObj.DataTable().ajax.reload( null, true ); // user paging is not reset on 
                    //title.val(null);
                    //description.val(null);
                    //due_date.datepicker("setDate", new Date()).trigger("changeDate");
                    //start_date.datepicker("setDate", new Date()).trigger("changeDate");
                    //own_user.val(null).trigger('change');
                    //meeting_category_id.val(null).trigger('change');
                    // scroll top
                    $('html, body').animate({scrollTop:0}, 'slow');
                })
                .fail(function() {
                    //console.log( "error" );
                })
                .always(function() {
                    //console.log( "complete" );
                    submit.attr("disabled", false);
                    //submit.removeAttr("disabled");
                });
        });
    });
    </script>
@endsection