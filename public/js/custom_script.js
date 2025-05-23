// cahange viewport
$(function(){
    "use strict";
    //var viewport = $('meta[name="viewport"]');
    //viewport.attr("content", "width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=0.1 user-scalable=no"); 
    //document.body.style.zoom = "80%";
    //document.activeElement.parentElement.style.zoom = 0.9;
});

// hide popovers and tooltips
function hideTooltipAndPopover(){
    "use strict";
    $('.popover').popover('hide');
    $('.tooltip').tooltip('hide');
    
    //$('.tooltip').tooltip('toggle');
    //$('.tooltip').tooltip('toggleEnabled');
    //$('.tooltip').popover('dispose');
    //$('.popover').popover('toggle');
    //$('.popover').popover('toggleEnabled');
    //$('.popover').popover('dispose');
    //$('.popover').remove();
    //$('.tooltip').remove();
}
$(document).on('focus', ':not(.popover)', function(){
    hideTooltipAndPopover();
});
$(document).on('focus', ':not(.tooltip)', function(){
    hideTooltipAndPopover();
});

// disable context menu
$(function() {
    "use strict";
    /* disable right click */
    $(document).on("contextmenu",function(e){
        return false;
    });
});

$(function(){
    /* Resolve conflict in jQuery UI tooltip with Bootstrap tooltip */
    //$.widget.bridge('uibutton', $.ui.button);
});

// accordion
$(function(){
    "use strict";
    // Add minus icon for collapse element which is open by default
    $(".collapse.in").each(function(){
        $(this).siblings(".panel-heading").find(".glyphicon").addClass("glyphicon-minus").removeClass("glyphicon-plus");
    });

    // Toggle plus minus icon on show hide of collapse element
    $(".collapse").on('show.bs.collapse', function(){
        $(this).parent().find(".glyphicon").removeClass("glyphicon-plus").addClass("glyphicon-minus");
    }).on('hide.bs.collapse', function(){
        $(this).parent().find(".glyphicon").removeClass("glyphicon-minus").addClass("glyphicon-plus");
    });
});

// ajax function
$(function(){
    "use strict";
    /*$( document ).ajaxStart(function(){
        $('#container_fluid').loading('start');
    });*/
    /*$( document ).ajaxComplete(function(){
        $('#container_fluid').loading('stop');
    });*/
    /*$( document ).ajaxStop(function(){
        $('#container_fluid').loading('stop');
    });*/
    $(document).bind("ajaxStart", function(event, request, settings){
        hideTooltipAndPopover();
        $('#container_fluid').loading('start');
    }).bind("ajaxStop", function(event, request, settings){
        $('#container_fluid').loading('stop');
        //initialize tooltip
        $('[data-toggle="tooltip"]').tooltip(); 
        //initialize popover
        $('[data-toggle="popover"]').popover();
    });
});

//set tooltips
/*$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});*/

/*$(function(){
    Number.prototype.pad = function(size) {
        var s = String(this);
        while (s.length < (size || 2)) {s = "0" + s;}
        return s;
    }
    //(1).pad(3) // => "001"
});*/

/*
const str1 = '5';
console.log(str1.padStart(2, '0'));
// expected output: "05"
const fullNumber = '00000000000000000';
const last4Digits = fullNumber.slice(-4);
const maskedNumber = last4Digits.padStart(fullNumber.length, '*');
console.log(maskedNumber);
// expected output: "************0000"
*/

/* headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } */

/*
$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    console.log(e);
    $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust()
        .responsive.recalc()
        .fixedColumns().relayout();
});
*/

// Disable automatic style injection
//Chart.platform.disableCSSInjection = true;

// generate unique id
/*function IDGenerator(){	 
    this.length = 8;
    this.timestamp = +new Date;
    var _getRandomInt = function( min, max ) {
        return Math.floor( Math.random() * ( max - min + 1 ) ) + min;
    }
    this.generate = function() {
        var ts = this.timestamp.toString();
        var parts = ts.split( "" ).reverse();
        var id = "";
        for( var i = 0; i < this.length; ++i ){
            var index = _getRandomInt( 0, parts.length - 1 );
            id += parts[index];	 
        }
        return id;
    }
}*/