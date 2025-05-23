@extends('layouts.home_layout')

@section('section_stylesheet')
    @parent
    <!-- DataTable -->
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-select-bs/css/select.bootstrap.min.css') }}" />
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
                        <a data-toggle="collapse" data-parent="#collapseOneParent" href="#collapseOne"><span class="glyphicon glyphicon-plus"></span> Edit 3W Resource</a>
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
                                <form action="{!! route('twInfo.update', ['tWInfo' => $tWInfo->id]) !!}" method="POST" class="col-sm-8" autocomplete="off" id="twInfoForm" enctype="multipart/form-data">
                                    @csrf
                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <label for="description" class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-10">
                                            <!-- p class="form-control-static"></p -->
                                            <textarea class="form-control rounded-0" id="description" name="description" placeholder="Description" rows="5" required>{{ $tWInfo->description }}</textarea>
                                        </div>
                                        <!-- span id="form-control" class="help-block"></span -->
                                    </div>
                                    <!-- /.form-group -->
                                    
                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <label for="var_user_attachment" class="col-sm-2 control-label">File</label>
                                        <div class="col-sm-10">
                                            <!-- p class="form-control-static"></p -->
                                            <input type="file" multiple=true class="form-control file" id="var_user_attachment" name="var_user_attachment[]" value="{{ old('var_user_attachment[]') }}"/>
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
                        <a data-toggle="collapse" data-parent="#collapseTwoParent" href="#collapseTwo"><span class="glyphicon glyphicon-plus"></span> Attachments</a>
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
                                <table id="userAttachmentDataTable" class="table table-bordered" style="width:100%" width="100%" cellspacing="0" border="1" align="left"></table>
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
    <!-- DataTable -->
    <script src="{{ asset('node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-responsive-bs/js/responsive.bootstrap.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-scroller-bs/js/scroller.bootstrap.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-select/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-select-bs/js/select.bootstrap.min.js') }}"></script>
    <!-- Bootstrap FileInput -->
    <script src="{!! asset('node_modules/bootstrap-fileinput/js/plugins/piexif.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('node_modules/bootstrap-fileinput/js/plugins/sortable.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('node_modules/bootstrap-fileinput/js/fileinput.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('node_modules/bootstrap-fileinput/js/locales/fr.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('node_modules/bootstrap-fileinput/js/locales/es.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('node_modules/bootstrap-fileinput/themes/fas/theme.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('node_modules/bootstrap-fileinput/themes/explorer-fas/theme.js') !!}" type="text/javascript"></script>

    @includeIf('partials.tw_info_user_attachment_data_table', array())
    <script>
    $(function() {
        "use strict";
        
        $("#var_user_attachment").fileinput({
            'showUpload': false,
            'previewFileType': 'any',
            'showRemove': false
        });
        
        $('#twInfoForm').submit(function(event) {
            event.preventDefault();
            var form = $(this);
            var form_id = $(this).attr('id');
            var _token = '{{ Session::token() }}';

            var tableObj = $('#userAttachmentDataTable');
            var description = form.find('#description');
            var var_user_attachment = form.find('#var_user_attachment');
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
                    //tableObj.DataTable().ajax.reload( null, false ); // user paging is not reset on reload
                    tableObj.DataTable().ajax.reload( null, true ); // user paging is not reset on reload
                    //description.val(null);
                    var_user_attachment.fileinput('clear');
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