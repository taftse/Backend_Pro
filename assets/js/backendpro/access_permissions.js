/**
 * BackendPro
 *
 * A website backend system for developers for PHP 5.2.6 or newer
 *
 * @package         BackendPro
 * @author          Adam Price <adam@kaydoo.co.uk>
 * @copyright       2008-2010, Adam Price
 * @license         http://www.opensource.org/licenses/mit-license.php MIT
 * @license         http://www.gnu.org/licenses/gpl.html GPL
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

(function( $ ){
    $.fn.permission_manager = function(options)
    {
        return this.each(function()
        {
            var container = $(this);

            var settings = {
            };

            // If options exist, lets merge them
            // with our default settings
            if (options)
            {
                $.extend(settings, options);
            }

            // Declare some variables which will help us
            var access_groups = $( '#access_groups');
            var access_resources = $('#access_resources');
            var access_actions = $('#access_actions');

            // This is the currently selected group the user has clicked on
            var selected_group = null;

            // This is the currently selected resource the user has clicked on
            var selected_resource = null;
            
            // Load the initial groups
            show_loader();
            perform_ajax_post('access_fetch/load_groups', '', render_groups, 'json');

            /***********************************************************************************************************
             *
             *  GROUP ACTIONS & EVENTS
             *
             **********************************************************************************************************/

            /**
             * Clear the group panel
             */
            function clear_groups()
            {
                clear_resources();

                // Remove all groups
                $('ul', access_groups).empty();

                // Set the selected group to nothing
                selected_group = null;
            }

            /**
             * Render all groups to screen
             *
             * @param json JSON list of groups
             */
            function render_groups(json)
            {
                clear_groups();

                // The groups are returned in the format
                for (var key in json) {
                    if (json.hasOwnProperty(key))
                    {
                        create_group(json[key]['id'], json[key]['name'], json[key]['locked'] == 1);
                    }
                }

                // Hide the loading screen
                hide_loader();
            }
            
            /**
             * Create a group
             *
             * @param key Group Key
             * @param name Group name
             * @param locked Whether the group is locked
             */
            function create_group(key, name, locked)
            {
                // Create a new group item with context menu
                var item = $('<li/>')
                    .attr('id', 'group_' + key)
                    .text(name)
                    .click(function(e){
                        switch_to_group($(e.target));
                    })
                    .contextMenu({ menu: 'group_menu', OnShowMenu: display_contextmenu}, function(action, el){
                        handle_contextmenu_action(action, el, 'group');
                });

                if(locked)
                {
                    // If its locked apply locked style
                    item.addClass('locked');
                }

                // Add to group list
                $('ul', access_groups).append(item);

                return item;
            }

            /**
             * Switch the manager so its focused on a new group
             *
             * @param group
             */
            function switch_to_group(group)
            {
                show_loader();

                if(selected_group != null)
                {
                    // Remove the selected style from the previously selected group
                    selected_group.removeClass('selected');
                }
                
                // Save the selected group to the GLOBAL var
                selected_group = group;

                // Highlight it as selected
                selected_group.addClass('selected');

                // Clear the current resources
                clear_resources();

                var id = extract_id(group.attr('id'));

                // Fetch the new resources using Ajax
                perform_ajax_post('access_fetch/load_resources',
                        'group=' + id,
                        render_resources,
                        'xml');
            }

            /**
             * Prompt the user for the change they want to make, validate it
             * and if valid save back and update the UI
             * 
             * @param action The action being carried out, either add/edit
             * @param element The element which was clicked
             */
            function save_group(action, element)
            {
                var data = '';
                var current_value = null;
                
                if(action == 'edit')
                {
                    current_value = element.text();
                    data = '&id=' + extract_id(element.attr('id'));
                }

                prompt_and_validate_change('group', current_value, function (value){
                    // The new value is valid save changes to the DB
                    perform_ajax_post(
                        'access_modify/save_group',
                        'value=' + value + data,
                        function(){
                            reload_permission_manager('group');
                        },
                        null);
                });
            }
            
            /***********************************************************************************************************
             *
             *  RESOURCE ACTIONS & EVENTS
             *
             **********************************************************************************************************/

            /**
             * Render all resources to screen based on the Xml
             * returned by ajax
             * 
             * @param xml
             */
            function render_resources(xml)
            {
                // Create tree view html
                generate_resource_structure($('resources', xml), $('ul', access_resources));

                // Turn the nested lists into a tree view
                //reset_resource_tree(); // TODO: Implemented the nested tree view for resources

                hide_loader();
            }

            /**
             * Clear the resource panel
             */
            function clear_resources()
            {
                clear_actions();

                // Remove all resources
                $('ul', access_resources).empty();

                // Set the selected resource to nothing
                selected_resource = null;
            }

            /**
             * Generate Resource UL Structure
             *
             * Create all resource LI's. Bind events for on click
             * so the actions are loaded, also add a context menu
             * to each resource.
             */
            function generate_resource_structure(parentElement, targetList)
            {
                // Get all child resource elements
                $('> resource', parentElement).each(function()
                {
                    // Determine what permission the resource is in
                    var permissionClass = ($(this).attr('has_access') == 'true') ? 'allow' : 'deny';

                    // Determine if the resource is locked
                    var item = create_resource($(this).attr('id'), $(this).attr('name'), targetList, permissionClass, $(this).attr('locked') == 1);

                    // Does this resource have children
                    if($('> resource', $(this)).length > 0)
                    {
                        // Create a target for the sub items
                        var submenu = $('<ul/>');

                        // Generate child resource structure
                        generate_resource_structure($(this), submenu);

                        submenu.appendTo(item);
                    }
                });
            }

            /**
             * Create a resource element
             *
             * @param id Resource Id
             * @param name Resource name
             * @param parent Parent resource element
             * @param permission_class Permission class to apply to the element, either allow or deny
             * @param locked Whether the resource is locked
             */
            function create_resource(id, name, parent, permission_class, locked)
            {
                // Create a new resource item with context menu
                var item = $('<li><span/></li>');

                item.find('span')
                    .attr('id', 'resource_' + id)
                    .text(name)
                    .addClass(permission_class)
                    .click(function(e){
                        switch_to_resource($(e.target));
                    })
                    .contextMenu({ menu: 'resource_menu', OnShowMenu: display_contextmenu }, function(action,el){
                        handle_contextmenu_action(action, el, 'resource');
                });

                if(locked)
                {
                    // If its locked apply locked style
                    item.find("span").addClass('locked');
                }

                // Add resource to parent list
                item.appendTo(parent);

                return item;
            }

            /**
             * Switch the manager so its focused on a new resource
             * 
             * @param resource
             */
            function switch_to_resource(resource)
            {
                show_loader();
                
                if(selected_resource != null)
                {
                    // Remove the selected style from the current resource
                    selected_resource.removeClass('selected');
                }

                // Save the selected resource to the GLOBAL var
                selected_resource = resource;

                // Highlight it as selected
                selected_resource.addClass('selected');

                // Clear the current actions
                clear_actions();

                var resource_id = extract_id(resource.attr('id'));
                var group_id = extract_id(selected_group.attr('id'));

                // Fetch the actions using Ajax
                perform_ajax_post('access_fetch/load_actions',
                        'resource_id=' + resource_id + '&group_id=' + group_id,
                        render_actions,
                        'json');
            }

            /**
             * Prompt the user for the change they want to make, validate it
             * and if valid save back and update the UI
             *
             * @param action The action being carried out, either add/edit
             * @param element The element which was clicked
             */
            function save_resource(action, element)
            {
                var data = '';
                var current_value = null;

                if(action == 'edit')
                {
                    current_value = element.text();
                    data = '&id=' + extract_id(element.attr('id'));
                }
                else
                {
                    data = '&parent_id=' + extract_id(element.attr('id'));
                }

                prompt_and_validate_change('resource', current_value, function (value){
                    // The new value is valid save changes to the DB
                    perform_ajax_post(
                        'access_modify/save_resource',
                        'value=' + value + data,
                        function(){
                            reload_permission_manager('resource');
                        },
                        null);
                });
            }

            /***********************************************************************************************************
             *
             *  ACTION ACTIONS & EVENTS
             *
             **********************************************************************************************************/

            /**
             * Clear the actions panel
             */
            function clear_actions()
            {
                // Remove all actions
                $('ul', access_actions).empty();
            }

            /**
             * Render all actions contained in the JSON result
             * 
             * @param json
             */
            function render_actions(json)
            {
                var permission_class = (selected_resource.hasClass('allow') ? 'allow' : 'deny');

                if(json.length == 0)
                {
                    // Display an All Actions permission
                    create_action('all', lang('access_all_actions'), permission_class, true);
                }
                else
                {
                    // Display a view permission action & all custom actions
                    create_action('view', lang('access_view_action'), permission_class, true);

                    for (var key in json) {
                        if (json.hasOwnProperty(key))
                        {
                            permission_class = (json[key]['has_access']) ? 'allow' : 'deny';

                            create_action(json[key]['id'], json[key]['name'], permission_class, json[key]['locked'] == '1');
                        }
                    }
                }

                hide_loader();
            }

            /**
             * Create an action element inside the actions list
             *
             * @param id Action ID
             * @param name Action display name
             * @param permission_class The permission class to apply to the action, either allow or deny
             * @param locked Whether the action should be locked to modification
             */
            function create_action(id, name, permission_class, locked)
            {
                // Create an action item with context menu
                var item = $('<li/>')
                    .attr('id', 'action_' + id)
                    .text(name)
                    .addClass(permission_class)
                    .click(function(e){
                        change_action_permission($(e.target));
                    })
                    .contextMenu({ menu: 'action_menu', OnShowMenu: display_contextmenu }, function(action, el){
                        handle_contextmenu_action(action, el, 'action');
                });

                if(locked)
                {
                    // If its locked apply locked style
                    item.addClass('locked');
                }

                // Add action to action list
                $('ul', access_actions).append(item);

                return item;
            }

            /**
             * Change the current access level for the selected action
             * If they are allowed access then deny them access
             * If they are denied then grant them access
             *
             * If the action requires to be propagated up to parent
             * resources then do so
             *
             * @param action
             */
            function change_action_permission(action)
            {
                var action_id = extract_id(action.attr('id'));
                var resource_id = extract_id(selected_resource.attr('id'));
                var group_id = extract_id(selected_group.attr('id'));

                if (action.hasClass('allow'))
                {
                    // Switch to Deny
                    if (action_id == 'all' || action_id == 'view')
                    {
                        // Since we are removing all permissions for this resource prompt the user
                        var confirm_msg = sprintf(lang('access_confirm_resource_permission_revoke'), selected_group.text(), selected_resource.text());
                        if(confirm(confirm_msg))
				        {
                            perform_ajax_post('access_modify/change_permission',
                                'resource_id=' + resource_id + '&group_id=' + group_id + '&permission=deny',
                                function(){
                                    // Propagate the permission change down
                                    propagate_permission_change(selected_resource, 'deny');

                                    action.addClass('deny');
                                    action.removeClass('allow');
                                },
                                null);
                        }
                    }
                    else
                    {
                        // We are only revoking an action permission don't prompt
                        perform_ajax_post('access_modify/change_permission',
                            'resource_id=' + resource_id + '&group_id=' + group_id + '&action_id=' + action_id + '&permission=deny',
                            function(){
                                action.addClass('deny');
                                action.removeClass('allow');
                            },
                            null);
                    }
                }
                else
                {
                    // Switch to Allow
                    var action_url = '&action_id=' + action_id;
                    if (action_id == 'all' || action_id == 'view')
                    {
                        // No action ID, we are just granting access to the resource
                        action_url = '';
                    }

                    perform_ajax_post('access_modify/change_permission',
                        'resource_id=' + resource_id + '&group_id=' + group_id + '&permission=allow' + action_url,
                        function(){
                            // Propagate the permission change up
                            propagate_permission_change(selected_resource, 'allow');

                            action.addClass('allow');
                            action.removeClass('deny');
                        },
                        null);
                }
            }

            /**
             * Propagate a permission change either up to the parent resources
             * or down depending on what the new permission should be.
             * Allow propagates up
             * Deny propagates down
             *
             * @param resource The resource to start at
             * @param new_permission The permission to apply
             */
            function propagate_permission_change(resource, new_permission)
            {
                var current_permission = new_permission == 'allow' ? 'deny' : 'allow';
                if(new_permission == 'allow')
                {
                    // We need to move up the tree
                    if ( ! resource.hasClass(new_permission))
                    {
                        // Apply the class to the resource
                        resource.removeClass(current_permission);
                        resource.addClass(new_permission);

                        // Check that the sub action called view/all is also changed
                        var main_action = $('li#action_view,li#action_all', access_actions);
                        if( ! main_action.hasClass(new_permission))
                        {
                            main_action.removeClass(current_permission);
                            main_action.addClass(new_permission);
                        }

                        // Can we move up any more
                        var new_parent = resource
                            .parent() // Get the parent LI
                            .parent() // Get the parent UL
                            .parent() // Get LI above the span item we want
                            .find('>span'); // Move down to the span item

                        if(new_parent.length == 1)
                        {
                            propagate_permission_change(new_parent, new_permission);
                        }
                    }
                }
                else
                {
                    // We need to move down the tree
                    resource.removeClass(current_permission);
                    resource.addClass(new_permission);

                    // Apply the new permission to all actions
                    $('li', access_actions).each(function(){
                        $(this).removeClass(current_permission);
                        $(this).addClass(new_permission);
                    });

                    // If any child resources exist, move to them
                    var childList = $('>ul', resource.parent());

                    if(childList.length == 1)
                    {
                        $('> li > span', childList).each(function(){
                            propagate_permission_change($(this), new_permission);
                        });
                    }
                }
            }

            /**
             * Prompt the user for the change they want to make, validate it
             * and if valid save back and update the UI
             *
             * @param action The action being carried out, either add/edit
             * @param element The element which was clicked
             */
            function save_action(action, element)
            {
                var data = '';
                var current_value = null;

                if (action == 'edit')
                {
                    current_value = element.text();
                    data = '&id=' + extract_id(element.attr('id'));
                }
                else
                {
                    data = '&resource_id=' + extract_id(selected_resource.attr('id'));
                }

                prompt_and_validate_change('action', current_value, function (value){
                    // The new value is valid save changes to the DB
                    perform_ajax_post(
                        'access_modify/save_action',
                        'value=' + value + data,
                        function(){
                            reload_permission_manager('action');
                        },
                        null);
                });
            }

            /***********************************************************************************************************
             *
             *  HELPER METHODS
             * 
             **********************************************************************************************************/

            function handle_contextmenu_action(action, element, section)
            {
                switch(action)
                {
                    case 'add':
                    case 'edit':
                        switch(section)
                        {
                            case 'group':
                                save_group(action, element);
                            break;

                            case 'resource':
                                save_resource(action, element);
                            break;

                            case 'action':
                                save_action(action, element);
                            break;

                            default:
                                alert(sprintf(lang('access_unknown_section'), section));
                        }
                    break;

                    case 'delete':
                            if (confirm(sprintf(lang('access_confirm_delete'), section)))
                            {
                                perform_ajax_post(
                                    'access_modify/delete_item',
                                    'id=' + extract_id(element.attr('id')) + '&section=' + section,
                                    function(){
                                        reload_permission_manager(section);
                                    },
                                    null);
                            }
                    break;

                    default:
                        // Not sure what the action is, warn the user
                        alert(sprintf(lang('access_unknown_action'), action));
                }
            }

            function reload_permission_manager(section)
            {
                switch(section)
                {
                    case 'group':
                        // Reload all the groups
                        show_loader();
                        perform_ajax_post('access_fetch/load_groups', '', render_groups, 'json');
                    break;

                    case 'resource':
                        // Reload the resource view
                        switch_to_group(selected_group);
                    break;

                    case 'action':
                        // Reload the action view
                        switch_to_resource(selected_resource);
                    break;
                    
                    default:
                        alert(sprintf(lang('access_unknown_section'), section));
                }
            }

            /**
             * Prompt the user for a new value and then validate it
             *
             * @param section The section the prompt is for group/action/resource
             * @param current_value The current value if an edit is being performed
             * @param success_callback The function to call after a valid input has been entered
             */
            function prompt_and_validate_change(section, current_value, success_callback)
            {
                var value = prompt(lang('access_' + section + '_prompt'), current_value);

                // If the user entered a value
                if (value != null && value != current_value)
                {
                    perform_ajax_post(
                            'access_modify/validate_' + section,
                            'value=' + value,
                            function(result){
                                if(result == 'valid')
                                {
                                    success_callback(value);
                                }
                                else
                                {
                                    alert(result);
                                    prompt_and_validate_change(section, current_value, success_callback);
                                }
                            },
                            null);
                }
            }
            
            function hide_loader()
            {
                // TODO: Implement a Overlay loader
            }

            function show_loader()
            {
                // TODO: Implement an Overlay loader
            }

            /**
             * Extract the unique ID part from an Id string.
             * So if given reason_12 this will return 12
             *
             * @param id ID String
             */
            function extract_id(id)
            {
                return id.substring(id.indexOf("_") + 1);
            }

            function display_contextmenu(item, menu)
            {
                // Get the edit and delete menu items
                var items = menu.find('li.edit, li.delete');

                // If Item is locked disable edit and delete
                if(item.hasClass('locked'))
                    items.each(function(){$(this).addClass('disabled');});
                else
                    items.each(function(){$(this).removeClass('disabled');});
            }

            /**
             * Perform an ajax request to a target method and call the success
             * callback method on return
             * 
             * @param method
             * @param data
             * @param callback
             * @param dataType
             */
            function perform_ajax_post(method, data, callback, dataType)
            {
                $.ajax({
                    url: site_url('access/' + method),
                    type: 'POST',
                    dataType: dataType,
                    data: data,
                    success: callback,
                    error: function(xhr, textStatus){
                        if(textStatus == 'timeout')
                            alert(lang('access_server_timeout'));
                        else
                            alert('Error: ' + xhr.responseText);
                    }
                });
            }
        });
    };
})(jQuery);