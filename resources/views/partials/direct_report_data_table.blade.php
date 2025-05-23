<!-- script>
$(function(){
    "use strict";
    var dataTableUserList = $('#userDataTable').DataTable();
});
</script -->

<script>
//var twProgressDataTableCustomData = {};
$(function(){
    "use strict";
    //$.fn.dataTable.ext.errMode = 'none';
    //$.fn.dataTableExt.errMode = 'ignore';
    $.fn.dataTableExt.sErrMode = "console";
    var dataTableTWList = $('#directReportDataTable').DataTable({
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
            'title' : 'Associate',
            'orderable' : false,
            'data' : 'cn',
            'render' : function(data, type, row){
                var formatted_data = data;
                //formatted_data = formatted_data.substring(0, formatted_data.lastIndexOf('@'));
                return formatted_data;
            }
        },{
            'title' : 'Completed',
            'orderable' : false,
            'data' : 'twCompletedCount',
            'render' : function(data, type, row){
                var formatted_data = data;
                return formatted_data;
            }
        },{
            'title' : 'Failed',
            'orderable' : false,
            'data' : 'twFailCount',
            'render' : function(data, type, row){
                var formatted_data = data;
                return formatted_data;
            }
        },{
            'title' : 'Inprogress',
            'orderable' : false,
            'data' : 'twInprogressCount',
            'render' : function(data, type, row){
                var formatted_data = data;
                return formatted_data;
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
        'searching' : false,
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
            'url' : "{!! route('user.listDirectReports', [urlencode($auth_user->mail)]) !!}",
            'cache' : true,
            'dataSrc' : 'data',
            'type' : 'GET',
            'deferRender' : true,
            //'dataType' : 'json',
            'delay' : 250,
            'data' : function(data){
                //console.log(data);
                var tableObj = $('#directReportDataTable');
                var tableObjData = {};
                var tableObjDataTemp = tableObj.data();
                data.own_user = "{!! $auth_user->mail !!}";
                
                tableObjData.due_date_from = moment().subtract(5, 'M').format('YYYY-MM-DD');
                //tableObjData.due_date_to = moment().format('YYYY-MM-DD');
                data = $.extend(data, tableObjData);
            },
            'error' : function(e){
                //console.log(e);
            }
        },
        'rowCallback' : function(row, data, displayNum, displayIndex, dataIndex){
            
        },
        'createRow' : function(row, data, dataIndex){},
        //'order' : [[1, 'asc']],
        'columnDefs' : [{
            'targets' : [0, 1],
            'width' : '30%'
        },{
            'targets' : [0],
            'responsivePriorty' : 0
        },{
            'targets' : [1],
            'responsivePriority' : 2,
            'visible' : true,
            'data' : null,
            'createdCell' : function(td, cellData, rowData, row, col){
                var parentTd = $(td);
                //parentTd.empty();
                parentTd.addClass('bg-green');
                parentTd.addClass('text-style-1');
            }
        },{
            'targets' : [2],
            'responsivePriority' : 2,
            'visible' : true,
            'data' : null,
            'createdCell' : function(td, cellData, rowData, row, col){
                var parentTd = $(td);
                //parentTd.empty();
                parentTd.addClass('bg-red');
                parentTd.addClass('text-style-1');
            }
        },{
            'targets' : [3],
            'responsivePriority' : 2,
            'visible' : true,
            'data' : null,
            'createdCell' : function(td, cellData, rowData, row, col){
                var parentTd = $(td);
                //parentTd.empty();
                parentTd.addClass('bg-yellow');
                parentTd.addClass('text-style-1');
            }
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
                button_1.addClass('btn btn-success btn-sm');
                var button_1_body = $('<i></i>');
                button_1_body.addClass('fa fa-eye');
                button_1_body.attr('data-toggle', 'tooltip');
                button_1_body.attr('data-placement', 'auto');
                button_1_body.attr('data-container', 'body');
                //button_1_body.attr('title', 'title');
                button_1_body.attr('data-title', 'View');
                //button_1_body.attr('data-content', 'content');
                button_1_body.tooltip();
                //button_1_body.text('text');
                button_1.off("click").on("click", function(event){
                    event.preventDefault();
                    //event.stopPropagation();
                    var url = "{!! route('directReport.showDirectReportTW', ['#user']) !!}";
                    url = url.replace("#user", encodeURIComponent(rowData.mail));
                    //$( location ).attr("href", url);
                    var windowObject = window.open(url, '_blank', null, true);
                    windowObject.focus();
                });
                button_1.append(button_1_body);
                buttonGroup_1.append(button_1);
                
                buttonToolbar.append(buttonGroup_1);
                
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
    
    $('#directReportDataTable').closest('.collapse').on('show.bs.collapse', function(){
        dataTableTWList.table().columns.adjust().draw();
    });
});
</script>