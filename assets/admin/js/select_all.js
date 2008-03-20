/* Provides a method to select multiple checkboxes */
$(document).ready(function(){  
    $("input[name='all']").change(function(){
            var children = $(this).val();
            var checked = $(this).is(':checked');
            var form = $(this).parents('form:first');
            $("input[name='"+children+"[]']",form).each(function(){$(this).attr('checked',checked);});
    });
});