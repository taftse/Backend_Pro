$(document).ready(function(){ 

	var access_groups = $('#access-groups');
	var access_resources = $('#access-resources');
	var access_actions = $('#access-actions');
	
	var selected_group = null;
	var selected_resource = null;
	
	// First load all the Access Groups
	perform_ajax_post('load_groups', 
				   		'', 
					   	group_fetch_onSuccess,
				   		'json');		
	
	/**
	 * Show loader area
	 */
	function show_loader(area)
	{
		change_loader(area, true);
	}
	
	/**
	 * Hide loader area
	 */
	function hide_loader(area)
	{
		change_loader(area, false);
	}
	
	/**
	 * Change the visability of a loader
	 */
	function change_loader(area, showLoading)
	{
		// Find the loading element
		var loader = $('div#loading', area);
		
		if(showLoading)
			loader.show();
		else
			loader.hide();
	}	
	
	/**
	 * Switch the selected group
	 * to that passed in
	 */
	function switch_selected_group(group)
	{	
		if(selected_group != null)
		{
			// Remove the selected style from the previously selected group
			selected_group.removeClass('selected');
		}
		
		// Save the selected group to the GLOBAL var
		selected_group = group;
		
		// Highlight it as selected
		selected_group.addClass('selected');
	}
	
	/**
	 * Switch the selected resource
	 * to that passed in
	 */
	function switch_selected_resource(resource)
	{
		if(selected_resource != null)
		{
			// Remove the selected style from the current resource
			selected_resource.removeClass('selected');
		}
		
		// Save the selected resource to the GLOBAL var
		selected_resource = resource;
		
		// Highlight it as selected
		selected_resource.addClass('selected');
	}	
	
	/**
	 * Clear the resource panel
	 */
	function clear_resources()
	{
		clear_actions();
		
		// Remove all resources
		$('ul', access_resources).empty();
		
		show_loader(access_resources);
		
		selected_resource = null;
	}
	
	/**
	 * Clear the actions panel
	 */
	function clear_actions()
	{
		// Remove all actions
		$('ul', access_actions).empty();
		
		
		show_loader(access_actions);
	}
	
	/**
	 * Create Group Item
	 *
	 * Create a group item and append it to the current list
	 */
	function create_group_item(id, name, locked)
	{
		// Create a new group item with context menu
		var item = $('<li/>')
			.attr('id', id)
			.text(name)
			.click(function(e){switch_selected_group($(e.target));})	// Switch the selected group
			.click(load_resources)									// Load all resources
			.contextMenu({ menu: 'group_menu', OnShowMenu: contextMenu_OnShow}, group_context_menu);
			
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
	 * Create Resource Item
	 *
	 * Create a resource item and append it to the parent list
	 */
	function create_resource_item(id, name, parent, permission, locked)
	{
		// Create a new resource item with context menu
		var item = $('<li><span/></li>');
								
		item.find('span')
			.attr('id', id)
			.text(name)
			.addClass(permission)
			.click(function(e){switch_selected_resource($(e.target));})
			.click(load_actions)
			.contextMenu({ menu: 'resource_menu', OnShowMenu: contextMenu_OnShow }, resource_context_menu);	
			
		if(locked)
		{
			// If its locked apply locked style
			item.find("span").addClass('locked');	
		}
			
		// Add resource to parent list
		item.appendTo(parent);	
		
		return item;
	}
	
	/** Create Action Item
	 *
	 * Create a new action item and append it to the
	 * actions list
	 */
	function create_action_item(id, name, permission, locked)
	{
		// Create an action item with context menu
		var item = $('<li/>')
			.attr('id', id)
			.text(name)
			.addClass(permission)
			.click(action_onClick)
			.contextMenu({ menu: 'action_menu', OnShowMenu: contextMenu_OnShow }, action_context_menu);
					
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
	 * Load Resources
	 *
	 * Load all resources and display permissions for the currently selected
	 * group.
	 */
	function load_resources()
	{
		// Clear the current resources
		clear_resources();
		
		// Fetch the resources using Ajax		
		perform_ajax_post('load_resources', 
				'group=' + selected_group.attr('id'),
				resource_fetch_onSucces,
				'xml');
	}
	
	/**
	 * Load Actions
	 *
	 * Load all actions for a given resource and display
	 * permissions for the selected group
	 */
	function load_actions()
	{
		// Clear the current actions
		clear_actions();
				
		// Fetch the actions using Ajax
		perform_ajax_post('load_actions', 
				'resource_id=' + selected_resource.attr('id') + '&group_id=' + selected_group.attr('id'),
				action_fetch_onSuccess,
				'json');
	}
	
	
	/**
	 * Group Fetch OnSuccess
	 *
	 * Add all groups to the list and bind events
	 * for on click.
	 */
	function group_fetch_onSuccess(json)
	{
		for (var key in json) {
			if (json.hasOwnProperty(key))
			{				
				create_group_item(key, json[key]['name'], (json[key]['locked'] == 1 ? true : false));
			}
		}

		hide_loader(access_groups);	
	}
	
	/**
	 * Resource Fetch OnSuccess
	 * 
	 * Add all resources to the list and turn them into a treeview.
 	 */

	function resource_fetch_onSucces(xml)
	{	// Create tree view html
		generate_resource_structure($('resources', xml), $('ul', access_resources));
		
		// Turn the nested lists into a tree view
		reset_resource_tree();
		
		hide_loader(access_resources);
	}
	
	/**
	 * Action Fetch OnSuccess
	 *
	 * Code to run when the ajax fetch actions successeds. Add actions to
	 * list.
	 */
	function action_fetch_onSuccess(object)
	{			
		if(object.length == 0)
		{
			// Display an All Actions permission
			create_action_item('all', 'All Actions', selected_resource.attr('class'), true);
		}
		else
		{
			// Display a view permission action & all custom actions
			create_action_item('view', 'View', selected_resource.attr('class'), true);
			
			for (var key in object) {
				if (object.hasOwnProperty(key))
				{
					create_action_item(key, object[key]['name'], object[key]['permission'], false);
				}
			}	
		}	
		
		hide_loader(access_actions);	
	}
	
	/**
	 * Action OnClick
	 *
	 * Change permission in the DB and change
	 * the visual look on screen
 	 */
	function action_onClick(e)
	{
		var target = $(e.target);
		
		if(target.hasClass('allow'))
		{
			// Current action is set to ALLOW
			if(target.attr('id') == 'all' || target.attr('id') == 'view')
			{
				// Trying to remove permission for the entire resource and all desendants, confirm it
				if(confirm("You are about to remove permission for the group '" + selected_group.text() + "' to access the resource '" + selected_resource.text() + "'. This will also remove all permission to desendant resources. Are you sure you want to continue?"))
				{
					show_loader(access_resources);
					
					perform_ajax_post('change_permission', 
							'resource_id=' + selected_resource.attr('id') + '&group_id=' + selected_group.attr('id') + '&permission=deny',
							function(result)
							{
								// Make sure all parent resources display as having the deny permission
								recursively_apply_permission(selected_resource, 'deny');
								
								// Reload the actions
								load_actions();
								
								hide_loader(access_resources);
							});
				}
				else
				{
					// Confirmation failed
					return;
				}
			}
			else
			{
				// Just trying to remove an action permission, no need for prompt
				perform_ajax_post('change_permission', 
						'resource_id=' + selected_resource.attr('id') + '&group_id=' + selected_group.attr('id') + '&action_id=' + target.attr('id') + '&permission=deny',
						function(result)
						{
							target.toggleClass('allow');
							target.toggleClass('deny');
						});
			}
		}
		else
		{
			// Current actions is set to DENY
			var action_id = target.attr('id');
			if(action_id == 'all' || action_id == 'view')
			{
				// No action ID, we are just granting access to the resource
				action_id = null;
			}
			
			perform_ajax_post('change_permission', 
					'resource_id=' + selected_resource.attr('id') + '&group_id=' + selected_group.attr('id') + '&action_id=' + action_id + '&permission=allow',
					function(result)
					{
						// Make sure all parent resources display as having the allow permission
						recursively_apply_permission(selected_resource, 'allow');
				
						target.toggleClass('allow');
						target.toggleClass('deny');
						
						load_actions();
					});
		}
	}
	
	
	/**
	 * OnShow of Context Menu
	 *
	 * Before the menu shows disable/enable menu items
	 * depending on if the item is locked or not
	 */
	function contextMenu_OnShow(item, menu)
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
			// Determin what permission the resource is in
			var permissionClass = ($(this).attr('hasAccess') == 'true') ? 'allow' : 'deny';
				
			// Determine if the resource is locked
			var locked = ($(this).attr('locked') == 1) ? true : false;		
			
			var item = create_resource_item($(this).attr('id'), $(this).attr('name'), targetList, permissionClass, locked);
			
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
	 * Recursively Apply Permission
	 *
	 * Either move up or down the tree and change the visual
	 * permission displayed.
	 */
	function recursively_apply_permission(parent_resource, new_permission)
	{
		if(new_permission == 'allow')
		{
			// Move up tree
			if(parent_resource.hasClass('allow') == false)
			{
				// Apply the correct style
				parent_resource.removeClass('deny');
				parent_resource.addClass('allow');
				
				// Can we move up any more?
				var new_parent = parent_resource
					.parent() // Get the parent LI
					.parent() // Get the parent UL
					.parent() // Get LI above the span item we want
					.find('>span'); // Move down to the span item
					
				if(new_parent.length == 1)
				{
					// We can move up so do so
					recursively_apply_permission(new_parent, new_permission);
				}
			}
			else
			{
				// Parent is already has allow permission, don't continue up
			}
		}
		else
		{
			// Move down tree
			
			// Deny access to the current resource
			parent_resource.removeClass('allow');
			parent_resource.addClass('deny');
			
			// If any child resources exist, move to them
			var childList = $('>ul', parent_resource.parent());
			
			if(childList.length == 1)
			{							
				$('> li > span', childList).each(function(){
					recursively_apply_permission($(this), new_permission);
				});			
			}
		}
	}
	
	/**
	 * Group Context Menu
	 *
	 * Handle the add/edit/delete actions from the group
	 * context menu
 	 */
	function group_context_menu(action, el, pos)
	{
		switch(action)
		{
			case 'add':
   				var name = prompt('Please enter the group name', '');
   				if(name != null)
   				{   		
   					show_loader(access_groups);
   					
   					perform_ajax_post('save_group', 
				   		'name=' + name, 
					   	function(id){
			   				// Add the new group item to the list
				   			var item = create_group_item(id, name);
				   			
				   			hide_loader(access_groups);
					   	});
				}
	   			break;
			
			case 'edit':
				var name = prompt('Please enter the group name', el.text());
   				if(name != null)
   				{
   					show_loader(access_groups);
   					
   					perform_ajax_post('save_group', 
				   		'name=' + name + '&id=' + el.attr('id'), 
					   	function(id){
			   				// Update item in list
							el.text(name);
							
							hide_loader(access_groups);
					   	});				
				}
				break;
				
			case 'delete':				
				// Confirm the action
				if(confirm("Are you sure you want to delete the group '" + el.text() + "'?"))
				{
					show_loader(access_groups);
					
					perform_ajax_post('delete_group', 
				   		'name=' + el.text() + '&id=' + el.attr('id'), 
					   	function(id){
							
							if(el.hasClass('selected'))
							{
								// User deleting the selected group
								selected_group = null;
								clear_resources();
							}
						
			   				// Delete the item
			   				el.remove();
			   				
			   				hide_loader(access_groups);
					   	});	
				}
				break;
		}
	}
	
	/**
	 * OnAction of Resource Context Menu
	 *
	 * Handles all the actions from the resource context menu
	 */
	function resource_context_menu(action, el, pos)
	{	
		switch(action)
		{
			case 'add':
				var name = prompt("Please enter the resource name", "");
				if(name != null)
				{
					show_loader(access_resources);
					
					perform_ajax_post('save_resource',
						'name=' + name + '&parent_id=' + el.attr('id'),
						function(id){
							// Add the Resource to the tree view
							
							// Get the child list
							var childlist = el.next();
							if(childlist.length == 0)
							{
								el.after("<ul></ul>");
								childlist = el.next();
							}
							
							// Create the resource in the child list
							create_resource_item(id, name, childlist, "deny", false);
							
							// Redraw the resource tree
							reset_resource_tree();
							
							hide_loader(access_resources);
						});
				}
				break;
				
			case 'edit':
				var name = prompt("Please enter the resource name", el.text());
				if(name != null)
				{
					show_loader(access_resources);
					
					perform_ajax_post('save_resource',
						'name=' + name + '&id=' + el.attr('id'),
						function(id)
						{
							el.text(name);
							
							hide_loader(access_resources);
						});
				}
				break;
				
			case 'delete':
				if(confirm("Are you sure you want to delete the resource '" + el.text() + "'? Doing so will also delete all child resources along with any linked permissions and actions."))
				{
					show_loader(access_resources);
					
					perform_ajax_post('delete_resource',
						'id=' + el.attr('id') + '&name=' + el.text(),
						function(id)
						{				
							if(el.hasClass('selected'))
							{
								// User deleting the selected resource
								clear_actions();
								
							}
							
							if(el.parent().parent().find('li').length == 1)
								el.parent().parent().remove(); // Remove the UL since this is the last resource
							else
								el.parent().remove(); // Remove only this resource
							
							// Redraw the resource tree
							reset_resource_tree();
							
							hide_loader(access_resources);
						});
				}
				break;
		}
	}	
	
	/**
	 * Action Context Menu
	 *
	 * Handle the add/edit/delete actions from
	 * the actions context menu.
 	 */
	function action_context_menu(action, el, pos)
	{		
		switch(action)
		{
			case 'add':
				var name = prompt('Please enter the action name', '');
				if(name != null)
				{
					show_loader(access_actions);
					
					perform_ajax_post('save_action', 
				   		'name=' + name + '&resource_id=' + selected_resource.attr('id'), 
					   	function(id){
			   				// Add action to list
			   				
			   				// If this is the first custom action change the All action to View
			   				if($("ul > li", access_actions).length == 1)
			   				{
			   					$("ul > li#all", access_actions)
								   .attr("id", "view")
								   .text("View");
		   					}
			   				
			   				create_action_item(id, name, 'deny');
			   				
			   				hide_loader(access_actions);
					   	});			
				}
				break;
				
			case 'edit':			 	
				var name = prompt('Please enter the action name', el.text());
				if(name != null)
				{
					show_loader(access_actions);
					
					perform_ajax_post('save_action', 
				   		'name=' + name + '&id=' + el.attr('id') + '&resource_id=' + selected_resource.attr('id'), 
					   	function(id){
			   				// Update action in list
			   				el.text(name);
			   				
			   				hide_loader(access_actions);
					   	});		
				}
				break;
				
			case 'delete':
				if(confirm("Are you sure you want to delete the action '" + el.text() + "'?"))
				{
					show_loader(access_actions);
					
					perform_ajax_post('delete_action',
						'id=' + el.attr('id') + '&name=' + el.text(),
						function()
						{
							// Remove the item from the list
							el.remove();
							
							// If that was the last custom action change the View action to All
							if($("ul > li", access_actions).length == 1)
			   				{
			   					$("ul > li#view", access_actions)
								   .attr("id", "all")
								   .text("All Actions");
		   					}
		   					
		   					hide_loader(access_actions);
						});
				}
				break;
		}
	}
	
	/**
	 * Perform Ajax Post Request
	 *
	 * Perform an ajax post request to a specific method with
	 * certain data
 	 */
	function perform_ajax_post(method, data, callback, dataType)
	{		
		$.ajax({
			url: base_url + '/' + index_page + '/access/access_permissions/' + method,
			type: 'POST',
			dataType: dataType,
			data: data,
			success: callback,
			error: function(xhr, textStatus, errorThrown){
				if(textStatus == 'timeout')
					alert('Server timeout, please try again');
				else
		        	alert(xhr.responseText);
		    }
		});
	}
	
	/**
	 * Reset Resource Tree
	 * 
	 * Clean up the resource tree and redraw it
	 */
	function reset_resource_tree()
	{
		// Remove all hitareas & classes from li
		$('div.hitarea', access_resources).remove();
		$('li', access_resources)							
			.removeClass($(this).treeview.classes.last)
			.removeClass($(this).treeview.classes.lastCollapsable)
			.removeClass($(this).treeview.classes.lastExpandable);							
		
		// Rebuild the tree
		$('ul:first', access_resources).treeview({'label_toggle': 'false'});
	}
});