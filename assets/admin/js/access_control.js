/**
 * Contains JS code to handle the permission trees for access control
 *
 */
$(document).ready(function(){    
    
    // Create permission trees
    $('#groups').treeview({
        cookie_name: 'group_tree'
    });
    $('#resources').treeview({
        cookie_name: 'resource_tree'
    });
    
    // Setup inital actions depending on checkboxes
    $("input[name^='action_']:checkbox",'div.scrollable_tree')
        .each(function(){toggleActions($(this));})   
        .change(function(){toggleActions($(this));});
    
    // Function to hide/show action options
    function toggleActions(checkbox)
    {
        var id = checkbox.val();
        if(checkbox.is(':checked')){
            $("div[id=allow_"+id+"]",'div.scrollable_tree').show();
        } else {
            $("div[id=allow_"+id+"]",'div.scrollable_tree').hide(); 
        }
    }
    
    // When a user picks a differnt access_group load its access_rights in
    // the respective div
    $('#access_groups input[name="aro"]').click(function(){
        
        // Call the ajax function to build the requested access tree
        $.post(
            base_url+index_page+'/auth/admin/acl_permissions/ajax_fetch_resources_access/'+$(this).val(),
            {},
            function(val){
                $('#access_rights').html(val);               
            }
        );
        
    });
    
        
        
        
        
        
        
        
        
        
        
        
    $("input[name='all']").change(function(){
            var children = $(this).val();
            var checked = $(this).is(':checked');
            var form = $(this).parents('form:first');
            $("input[name='"+children+"[]']",form).each(function(){$(this).attr('checked',checked);});
    });
});