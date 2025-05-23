<script>
$(function(){
    "use strict";
    $.fn.select2.defaults.set( "theme", "bootstrap" );
    /*$('#id').select2({
        theme: "bootstrap"
    });*/
    
    $('#created_user').select2({
        ajax          : {
            url: "{!! route('user.list') !!}", //user.list
            cache: true,
            // dataType: 'json',
            delay: 50,
            data: function (params) {
                var query = {
                    search			: params.term, // $.trim(params.term)
                    active		    : 1,
                    page  			: params.page || 1,
                    length			: 10
                }
                return query;
            },
            processResults: function (data, params) {
                //params.page = params.page || 1;
                return {
                    results: $.map(data.data, function (obj) {
                        return { 
                            id  : obj.mail, 
                            text: obj.mail + ' | ' + ( obj.displayname || obj.employeenumber ) + ' | ' + obj.physicaldeliveryofficename, 
                            data: obj 
                        };
                    }),
                    pagination: {
                        more: (params.page * data.length) < Number(data.recordsTotal)
                        //more: data.pagination.more
                    }
                };
            },
            cache: true
        },
        placeholder	      : 'Select Type',
        minimumInputLength: 2,
        multiple		  : false,
        closeOnSelect	  : true,
        allowClear	  : true,
        escapeMarkup      : function (markup) { return markup; }
    });
    
});
</script>