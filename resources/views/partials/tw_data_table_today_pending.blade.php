<!-- script>
$(function(){
    "use strict";
    var dataTableUserList = $('#userDataTable').DataTable();
});
</script -->

<script>
$(function(){
    "use strict";
    //$.fn.dataTable.ext.errMode = 'none';
    //$.fn.dataTableExt.errMode = 'ignore';
    $.fn.dataTableExt.sErrMode = "console";
    var dataTableTWList = $('#twDataTable').DataTable({
        'language' : {
            'lengthMenu' : 'Show _MENU_ Entries'
        },
        'columns' : [/*{
            'title' : '',
            'className' : 'details-control',
            'orderable' : false,
            'className' : 'center',
            'data' : null,
            'defaultContent' : '',
            'render' : function(data, type, row){
                //$.fn.dataTable.render.number( ',', '.', 0, '$' );
                return data.epf_no;
            }
        },*/{
            'title' : '3W',
            'orderable' : false,
            'data' : 'title',
            'render' : function(data, type, row){
                return data;
            }
        },{
            'title' : 'Description',
            'orderable' : false,
            'data' : 'description',
            'render' : function(data, type, row){
                return data;
            }
        },{
            'title' : 'Start Date',
            'orderable' : false,
            'data' : 'start_date',
            'render' : function(data, type, row){
                var date = moment(data, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
                return date;
            }
        },{
            'title' : 'Due Date',
            'orderable' : false,
            'data' : 'due_date',
            'render' : function(data, type, row){
                var date = moment(data, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
                return date;
            }
        },{
            'title' : 'Owners',
            'orderable' : false,
            'data' : 'tw_users',
            'render' : function(data, type, row){
                var data_str = '';
                if(($.isArray(data))){
                    $.each(data, function( key, value ){
                        var formatted_data = value.own_user;
                        formatted_data = formatted_data.substring(0, formatted_data.lastIndexOf('@'));
                        data_str =  formatted_data + ' <br/> ' + data_str;
                    });
                }
                
                return data_str;
            }
        },{
            'title' : '',
            'orderable' : false,
            'className' : 'center',
            'data' : null,
            'render' : function(data, type, row){
                return '';
            }
        }],
        'responsive' : false,
        'scrollX' : true,
        'paging' : true,
        'lengthChange' : true,
        'lengthMenu' : [[5, 10, 25, 50, 100, {!! PHP_INT_MAX !!}], [5, 10, 25, 50, 100, 'all']],
        'searching' : true,
        'ordering' : false,
        'info' : true,
        'autoWidth' : false,
        'processing' : false,
        'serverSide' : true,
        'jQueryUI' : false,
        'initComplete' : function(){
            //console.log("initComplete");
            //$(this).show();
        },
        'ajax' : {
            'url' : "{!! route('tw.list') !!}",
            'cache' : true,
            'dataSrc' : 'data',
            'type' : 'GET',
            'deferRender' : true,
            //'dataType' : 'json',
            'delay' : 300,
            'data' : function(data){
                //console.log(data);
                data.due_date = moment().format('YYYY-MM-DD');
                data.is_done = false;
                data.own_user = "{!! $auth_user->mail !!}";
                data.status = null;
            },
            'error' : function(e){
                //console.log(e);
            }
        },
        'rowCallback' : function(row, data, displayNum, displayIndex, dataIndex){
            var parentTR = $( row );
            //parentTR.empty();
            //parentTd.addClass('default');
            //var due_date = moment(data.due_date, 'YYYY-MM-DD HH:mm:ss').toDate();
            //var today = moment().format('YYYY-MM-DD');
            //var due_date = moment(data.due_date, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
            //var done_date = moment(data.done_date, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
            
            if( ((data.is_reviewable == false) || (data.is_reviewable == null)) ){
                parentTR.addClass('bg-info border border-primary text-white');
            }else{
                parentTR.removeClass('bg-info border border-primary text-white');
            }
        },
        'createRow' : function(row, data, dataIndex){},
        //'order' : [[1, 'asc']],
        'columnDefs' : [{
            'targets' : [0, 1],
            'width' : '30%'
        },{
            'targets' : [1, 2],
            'responsivePriorty' : 1
        },{
            'targets' : [-1],
            'responsivePriority' : 2,
            'visible' : true,
            //'width' : '250px',
            'data' : null, // Use the full data source object for the renderer's source
            'createdCell' : function(td, cellData, rowData, row, col){
                var parentTd = $(td);
                parentTd.empty();
                
                var buttonToolbar = $('<div></div>');
                buttonToolbar.addClass('btn-toolbar');//pull-left
                //button group
                var buttonGroup_3 = $('<div></div>');
                buttonGroup_3.addClass('btn-group');
                var button_3 = $('<button></button>');
                button_3.addClass('btn btn-success btn-sm');
                var button_3_body = $('<i></i>');
                button_3_body.addClass('fa fa-eye');
                button_3_body.attr('data-toggle', 'tooltip');
                button_3_body.attr('data-placement', 'auto');
                button_3_body.attr('data-container', 'body');
                //button_3_body.attr('title', 'title');
                button_3_body.attr('data-title', 'View');
                //button_3_body.attr('data-content', 'content');
                button_3_body.tooltip();
                button_3.off("click").on("click", function(event){
                    event.preventDefault();
                    //event.stopPropagation();
                    var url = "{!! route('tw.show', ['#tW']) !!}";
                    //$( location ).attr("href", url);
                    var windowObject = window.open(url, '_blank', null, true);
                    windowObject.focus();
                });
                button_3.append(button_3_body);
                buttonGroup_3.append(button_3);
                
                //button group
                var buttonGroup_4 = $('<div></div>');
                buttonGroup_4.addClass('btn-group');
                var button_4 = $('<button></button>');
                button_4.addClass('btn btn-warning btn-sm');
                var button_4_body = $('<i></i>');
                button_4_body.addClass('fa fa-book');
                button_4_body.attr('data-toggle', 'tooltip');
                button_4_body.attr('data-placement', 'auto');
                button_4_body.attr('data-container', 'body');
                //button_4_body.attr('title', 'title');
                button_4_body.attr('data-title', 'Update Attachment');
                //button_4_body.attr('data-content', 'content');
                button_4_body.tooltip();
                button_4.off("click").on("click", function(event){
                    event.preventDefault();
                    //event.stopPropagation();
                    var url = "{!! route('twInfo.create', ['#tW']) !!}";
                    url = url.replace("#tW", rowData.id);
                    //$( location ).attr("href", url);
                    var windowObject = window.open(url, '_blank', null, true);
                    windowObject.focus();
                });
                button_4.append(button_4_body);
                buttonGroup_4.append(button_4);
                
                //button group
                var buttonGroup_5 = $('<div></div>');
                buttonGroup_5.addClass('btn-group');
                var button_5 = $('<button></button>');
                button_5.addClass('btn btn-info btn-sm');
                var button_5_body = $('<i></i>');
                button_5_body.addClass('fa fa-clipboard');
                button_5_body.attr('data-toggle', 'tooltip');
                button_5_body.attr('data-placement', 'auto');
                button_5_body.attr('data-container', 'body');
                //button_5_body.attr('title', 'title');
                button_5_body.attr('data-title', 'Update Status');
                //button_5_body.attr('data-content', 'content');
                button_5_body.tooltip();
                button_5.off("click").on("click", function(event){
                    event.preventDefault();
                    //event.stopPropagation();
                    button_5.attr("disabled", true);
                    
                    var url = "{!! route('tw.getTWInfoCount', ['#tW']) !!}";
                    url = url.replace("#tW", rowData.id);
                    $.ajax({
                        type: "GET",
                        url: url,
                        data: new Object(),
                        //success: success,
                        //dataType: dataType,
                        //context: document.body
                    })
                    .done(function( data ) {
                        //console.log(data);
                        var count = 0;
                        if( (data) && (data.count) ){
                           count = Number(data.count);
                        }
                        if( (count != void(0)) && (count > 1) ){
                            bootbox.confirm({
                            size: "small",
                            title: "Confirmation",
                            message: "<strong>Job Done ?</strong><br/><small>" + rowData.title + "</small>",
                            onEscape: true,
                            show: true,
                            scrollable: true,
                            buttons: {
                                confirm: {
                                    label: 'Yes',
                                    className: 'btn-success'
                                },
                                cancel: {
                                    label: 'No',
                                    className: 'btn-danger btn-primary'
                                }
                            },
                            callback: function (result) {
                                //console.log('This was logged in the callback: ' + result);
                                if( result === true ){
                                    var url = "{!! route('tw.changeDoneTrue', ['#tW']) !!}";
                                    url = url.replace("#tW", rowData.id);
                                    //$( location ).attr("href", url);

                                    $.ajax({
                                        type: "GET",
                                        url: url,
                                        data: null,
                                        //success: success,
                                        //dataType: dataType,
                                        //context: document.body
                                    })
                                    .done(function( data ) {
                                        swal({
                                            'title': data.title,
                                            'text': data.text,
                                            'type': data.type,
                                            'timer': data.timer,
                                            'showConfirmButton': false
                                        });
                                        $('#twDataTable').DataTable().ajax.reload( null, false ); // user paging is not reset on reload
                                    })
                                    .fail(function() {
                                        //console.log( "error" );
                                        alert('fail');
                                    })
                                    .always(function() {
                                        //console.log( "finished" );
                                        button_5.attr("disabled", false);
                                    });

                                }else{
                                    button_5.attr("disabled", false);
                                }
                            }
                        })
                            .find('.modal-header').addClass('bg-success')
                            /*.find('.bootbox-cancel:first').focus()
                            .find('.bootbox-cancel').attr('autofocus', true)
                            .on('shown.bs.modal', function(e){
                                $(this).find(".bootbox-cancel:first").focus();
                            })*/
                            .init(function(e){
                                $(this).find(".bootbox-cancel").focus();
                            });
                        }else{
                            bootbox.alert({
                                message: "Please Mention Your Action Steps in Description Section, Prior to Close The 3W",
                                size: 'small',
                                //className: 'rubberBand animated',
                                //backdrop: true,
                                //locale: 'en',
                                callback: function () {
                                    //console.log('This was logged in the callback!');
                                    button_5.attr("disabled", false);
                                }
                            });
                        }
                    })
                    .fail(function() {
                        //console.log( "error" );
                        bootbox.alert({
                            message: "Please Mention Your Action Steps in Description Section, Prior to Close The 3W",
                            size: 'small',
                            //className: 'rubberBand animated',
                            //backdrop: true,
                            //locale: 'en',
                            callback: function () {
                                //console.log('This was logged in the callback!');
                                button_5.attr("disabled", false);
                            }
                        });
                    })
                    .always(function() {
                        //console.log( "finished" );
                    });
                    /*
                    if( (rowData.tw_infos == void(0)) || ((rowData.tw_infos) && (rowData.tw_infos.length <= 1)) ){
                        
                        bootbox.alert({
                            message: "Please Mention Your Action Steps in Description Section, Prior to Close The 3W",
                            size: 'small',
                            //className: 'rubberBand animated',
                            //backdrop: true,
                            //locale: 'en',
                            callback: function () {
                                //console.log('This was logged in the callback!');
                                button_5.attr("disabled", false);
                            }
                        });
                        
                    }else{
                        
                        bootbox.confirm({
                        size: "small",
                        title: "Confirmation",
                        message: "<strong>Job Done ?</strong><br/><small>" + rowData.title + "</small>",
                        onEscape: true,
                        show: true,
                        scrollable: true,
                        buttons: {
                            confirm: {
                                label: 'Yes',
                                className: 'btn-success'
                            },
                            cancel: {
                                label: 'No',
                                className: 'btn-danger btn-primary'
                            }
                        },
                        callback: function (result) {
                            //console.log('This was logged in the callback: ' + result);
                            if( result === true ){
                                var url = "{!! route('tw.changeDoneTrue', ['#tW']) !!}";
                                url = url.replace("#tW", rowData.id);
                                //$( location ).attr("href", url);
                                
                                $.ajax({
                                    type: "GET",
                                    url: url,
                                    data: null,
                                    //success: success,
                                    //dataType: dataType,
                                    //context: document.body
                                })
                                .done(function( data ) {
                                    swal({
                                        'title': data.title,
                                        'text': data.text,
                                        'type': data.type,
                                        'timer': data.timer,
                                        'showConfirmButton': false
                                    });
                                    $('#twDataTable').DataTable().ajax.reload( null, false ); // user paging is not reset on reload
                                })
                                .fail(function() {
                                    //console.log( "error" );
                                    alert('fail');
                                })
                                .always(function() {
                                    //console.log( "finished" );
                                    button_5.attr("disabled", false);
                                });
                                
                            }else{
                                button_5.attr("disabled", false);
                            }
                        }
                    })
                        .find('.modal-header').addClass('bg-success')
                        //.find('.bootbox-cancel:first').focus()
                        //.find('.bootbox-cancel').attr('autofocus', true)
                        //.on('shown.bs.modal', function(e){
                        //    $(this).find(".bootbox-cancel:first").focus();
                        //})
                        .init(function(e){
                            $(this).find(".bootbox-cancel").focus();
                        });
                        
                    }
                    */
                });
                button_5.append(button_5_body);
                buttonGroup_5.append(button_5);
                
                buttonToolbar.append(buttonGroup_3);
                buttonToolbar.append(buttonGroup_4);
                buttonToolbar.append(buttonGroup_5);
                
                var popoverButtonToolbar = $('<div></div>');
                popoverButtonToolbar.addClass('btn-toolbar pull-left');
                var popoverButtonGroup_1 = $('<div></div>');
                popoverButtonGroup_1.addClass('btn-group');
                
                var popoverToggleButton = $('<button></button>');
                var popoverToggleButtonId = 'id-' + moment().format('HH-mm-ss-SSS');
                popoverToggleButton.addClass('btn btn-primary btn-sm my-popover');
                popoverToggleButton.attr('id', popoverToggleButtonId);
                popoverToggleButton.attr('data-toggle', 'popover');
                popoverToggleButton.attr('data-placement', 'auto');
                popoverToggleButton.attr('data-container', 'body');
                //popoverToggleButton.attr('data-trigger', 'focus');
                var popoverToggleButtonSpan = $('<span></span>');
                popoverToggleButtonSpan.addClass('fa fa-gears');
                
                popoverToggleButton.popover({
                    html: true, 
                    selector: false,
                    //selector: ('#'+popoverToggleButtonId),
                    //trigger: 'manual',
                    content: function() {
                        //var content_string = buttonToolbar.html();
                        return buttonToolbar;
                    }
                });
                
                /*
                popoverToggleButton.on('show.bs.popover', function(){
                    console.log("show");
                });
                popoverToggleButton.on('hidden.bs.popover', function(){
                    console.log("hidden");
                });
                */

                popoverToggleButton.append(popoverToggleButtonSpan);
                popoverButtonGroup_1.append(popoverToggleButton);
                popoverButtonToolbar.append(popoverButtonGroup_1);
                popoverButtonToolbar.appendTo(parentTd);
            }
        }],
        'drawCallback' : function(settings){
            var api = this.api();
            var table = api.table();
        }
    });
    
    $('#twDataTable').closest('.collapse').on('show.bs.collapse', function(){
        dataTableTWList.table().columns.adjust().draw();
    });
});
</script>