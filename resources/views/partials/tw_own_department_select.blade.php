<script>
$(function(){
    "use strict";
    $.fn.select2.defaults.set( "theme", "bootstrap" );
    /*$('#id').select2({
        theme: "bootstrap"
    });*/
    
    $('#own_department_name').select2({
        ajax          : {
            url: "{!! route('department.listOwn') !!}",
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
                            id  : obj.department_name, 
                            text: obj.department_name || obj.department_name, 
                            data: obj 
                        };
                    }),
                    pagination: {
                        //more: (params.page * data.length) < Number(data.recordsTotal)
                        more: data.pagination.more
                    }
                };
            },
            cache: true
        },
        placeholder	      : 'Select Department',
        //minimumInputLength: 1,
        multiple		  : false,
        closeOnSelect	  : true,
        allowClear	  : true,
        escapeMarkup      : function (markup) { return markup; }
    });
    
});
</script>