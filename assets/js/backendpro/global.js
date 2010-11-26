$(document).ready(function(){ 
	
	/* Setup the Navigation for the header menu */
	$("#navigation").superfish(); 
    
    /* 	Allow hiding a content box if it has the
    	closable class */
   	$('<div/>').prependTo('.content-box.closable')
    		.addClass('close-action')
    		.addClass('ui-icon')
    		.addClass('ui-icon-close')
    		.click(function()
    			{
    				$(this).parent().slideUp('normal',function(){$(this).remove();});
   				}
			);
			
	/* COMPLEX CODE
	var searchArea = $("table.simple thead, table.simple tfoot");
	$("input[type='checkbox']", searchArea).each(function()
	{		
		$(this).click(function(){
			
			// Get the column index of this checkbox
			var i = 1;
			var curr = $(this).parent().prev();
			
			while(curr.length > 0)
			{
				curr = curr.prev();
				i++;
			}			
			
			// Apply check status to all checkboxes in the same column
			var checked = $(this).attr('checked');
			$("table.simple .column-checkbox:nth-child(" + i + ") input[type='checkbox']").each(function(){
				$(this).attr('checked', checked);
			});
		});
	});*/
	
	var searchArea = $("table.simple thead, table.simple tfoot");
	$("input[type='checkbox']", searchArea).each(function()
	{		
		$(this).click(function(){	
			// Apply check status to all checkboxes in the same column
			var checked = $(this).attr('checked');
			$("table.simple input[type='checkbox'][disabled!='disabled']").each(function(){ // TODO: This shouldn't return disabled checkboxes
				$(this).attr('checked', checked);
			});
		});
	});	
	
	$("table.simple tbody input[type='checkbox']").each(function(){
		$(this).click(function(){
			if($(this).attr('checked') == 'checked')
				$(this).parent('tr').attr('background-color','red');
		});
	});
	
			
	/* Setup the Dashboard Portlets */
	/*$(".portlet-column").sortable({
		connectWith: '.portlet-column',
		handle: '.ui-widget-header'
	});
	
	$(".portlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
		.find(".portlet-header")
		.addClass("ui-widget-header")
		.prepend('<span class="ui-icon ui-icon-plus"></span>')
		.end()
		.find(".portlet-content");
	
	$(".portlet-header .ui-icon").click(function() {
		$(this).toggleClass("ui-icon-minus");
		$(this).parents(".portlet:first").find(".portlet-content").slideToggle();
	});
	
	$(".portlet-column").disableSelection();*/
}); 