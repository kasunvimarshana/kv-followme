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
    var dataTableTWList = $('#userEscalateOffDataTable').DataTable({
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
            'title' : 'User',
            'orderable' : false,
            'data' : 'user_pk',
            'render' : function(data, type, row){
                return data;
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
            'url' : "{!! route('userEscalateOff.list') !!}",
            'cache' : true,
            'dataSrc' : 'data',
            'type' : 'GET',
            'deferRender' : true,
            //'dataType' : 'json',
            'delay' : 300,
            'data' : function(data){
                //console.log(data);
            },
            'error' : function(e){
                //console.log(e);
            }
        },
        'rowCallback' : function(row, data, displayNum, displayIndex, dataIndex){},
        'createRow' : function(row, data, dataIndex){},
        //'order' : [[1, 'asc']],
        'columnDefs' : [{
            'targets' : [0],
            'width' : '90%'
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
                        message: "<strong>Do you want to delete ?</strong><br/><small></small>",
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
                                className: 'btn-danger  btn-primary'
                            }
                        },
                        callback: function (result) {
                            //console.log('This was logged in the callback: ' + result);
                            if( result === true ){
                                var url = "{!! route('userEscalateOff.destroy', ['#userEscalateOff']) !!}";
                                url = url.replace("#userEscalateOff", rowData.id);
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
                                    $('#userEscalateOffDataTable').DataTable().ajax.reload( null, false ); // user paging is not reset on reload
                                })
                                .fail(function() {
                                    //console.log( "error" );
                                    //alert('fail');
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
    
    $('#userEscalateOffDataTable').closest('.collapse').on('show.bs.collapse', function(){
        dataTableTWList.table().columns.adjust().draw();
    });
});
</script>