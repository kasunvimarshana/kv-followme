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
            'title' : 'Title',
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
                }else{
                    data_str = value.own_user;
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
        'autoWidth' : true,
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
                data.created_user = "{!! $auth_user->mail !!}";
                data.created_at = moment().format('YYYY-MM-DD');
                //data.created_at = "{!! date('Y-m-d') !!}";
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
                var buttonGroup_1 = $('<div></div>');
                buttonGroup_1.addClass('btn-group');
                var button_1 = $('<button></button>');
                button_1.addClass('btn btn-info btn-sm');
                var button_1_body = $('<i></i>');
                button_1_body.addClass('fa fa-edit');
                button_1_body.attr('data-toggle', 'tooltip');
                button_1_body.attr('data-placement', 'auto');
                button_1_body.attr('data-container', 'body');
                //button_1_body.attr('title', 'title');
                button_1_body.attr('data-title', 'Edit');
                //button_1_body.attr('data-content', 'content');
                button_1_body.tooltip();
                //button_1_body.text('text');
                button_1.off("click").on("click", function(event){
                    event.preventDefault();
                    //event.stopPropagation();
                    var url = "{!! route('tw.edit', ['#tW']) !!}";
                    url = url.replace("#tW", rowData.id);
                    //$( location ).attr("href", url);
                    var windowObject = window.open(url, '_blank', null, true);
                    windowObject.focus();
                });
                button_1.append(button_1_body);
                buttonGroup_1.append(button_1);
                
                //button group
                var buttonGroup_2 = $('<div></div>');
                buttonGroup_2.addClass('btn-group');
                var button_2 = $('<button></button>');
                button_2.addClass('btn btn-danger btn-sm');
                var button_2_body = $('<i></i>');
                button_2_body.addClass('fa fa-trash-o');
                button_2_body.attr('data-toggle', 'tooltip');
                button_2_body.attr('data-placement', 'auto');
                button_2_body.attr('data-container', 'body');
                //button_2_body.attr('title', 'title');
                button_2_body.attr('data-title', 'Delete');
                //button_2_body.attr('data-content', 'content');
                button_2_body.tooltip();
                button_2.off("click").on("click", function(event){
                    event.preventDefault();
                    //event.stopPropagation();
                    button_2.attr("disabled", true);
                    bootbox.confirm({
                        size: "small",
                        title: "Confirmation",
                        message: "<strong>Do you want to delete 3W ?</strong><br/><small>" + rowData.title + "</small>",
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
                                var url = "{!! route('tw.destroy', ['#tW']) !!}";
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
                                    button_2.attr("disabled", false);
                                });
                                
                            }else{
                                button_2.attr("disabled", false);
                            }
                        }
                    })
                        .find('.modal-header').addClass('bg-danger')
                        /*.find('.bootbox-cancel:first').focus()
                        .find('.bootbox-cancel').attr('autofocus', true)
                        .on('shown.bs.modal', function(e){
                            $(this).find(".bootbox-cancel:first").focus();
                        })*/
                        .init(function(e){
                            $(this).find(".bootbox-cancel").focus();
                        });
                    
                });
                button_2.append(button_2_body);
                buttonGroup_2.append(button_2);
                
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
                    url = url.replace("#tW", rowData.id);
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
                
                
                buttonToolbar.append(buttonGroup_3);
                buttonToolbar.append(buttonGroup_4);
                buttonToolbar.append(buttonGroup_1);
                buttonToolbar.append(buttonGroup_2);
                
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