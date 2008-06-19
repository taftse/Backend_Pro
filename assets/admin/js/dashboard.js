$(document).ready(function(){
	// Declare well used objects
	var editBnt = $('#edit_dashboard');
  	var saveBnt = $('#save_dashboard');
  	var regions = $("#dashboard .sortable");
  	var widgets = $('.widget',regions);
  	
  	/* CREATE INITIAL DASHBOARD STATE */
  	// Hide Save button
  	saveBnt.hide();
  
  	// Get the current dashboard settings from the cookie
  	// and position the widgets accordingly
  	var arr = $.cookie('bep_dashboard');
  	if( arr != null)
  	{
  		// Only position the widgets if we have a cookie
  		arr = arr.split('&');
	  	for(i=0;i<arr.length;i++)
	  	{
	  		var cmd = arr[i].split('[]=');
	  		if(cmd[0] == "topsection" || cmd[0] == "leftsection" || cmd[0] == "rightsection")
	  		{
	  			// We have a position cmd
	  			$('#widget_'+cmd[1],regions).appendTo('#'+cmd[0]);
	  		}
	  		else
	  		{
	  			// We have a visibility cmd
	  			if(cmd[1] == 'hidden')
	  				$('#'+cmd[0],regions).hide();
	  		}
	  	}  	
	 }
  
  	// Make regions sortable
    regions.sortable({
    	handle: $('#dashboard .widget .header'),
    	cursor: 'move',
    	connectWith: [$('#topsection'),$('#leftsection'),$('#rightsection')],
    	opacity: 0.8,
    	scroll: false
    });
    
    // Disable regions
    regions.sortable("disable");
    
    // Assign a class to the actions so we can identify them in the future
    $('div.action',widgets).each(function(){
    	var tick = $('img:eq(0)',this);
    	var cross= $('img:eq(1)',this);
    	
    	// Setup onclick actions
    	tick.addClass('db-visible').hide().click(function(){
    		$(this).hide();
    		cross.show();
    	});
    	cross.addClass('db-hidden').hide().click(function(){
    		$(this).hide();
    		tick.show();
    	});
    });
    
    /* WHEN USER REQUESTS TO EDIT DASHBOARD */
    editBnt.click(function(){
    	// Hide the button and display the save button
    	editBnt.hide();
    	saveBnt.show();
    	
    	// Make regions sortable
    	regions.sortable("enable");
    	
    	// Get rid of everything apart from the header in the widgets
    	$('div.body',widgets).hide();
    	
    	// Loop over all widgets
    	widgets.each(function(){
    		// Decide whether to show a tick or a cross for this widget
    		if($(this).css('display') == 'none')
    			$('.action img.db-hidden',this).show();
    		else
    			$('.action img.db-visible',this).show();
    		
    		// Show the widget
    		$(this).show();
    	});
    });
    
    /* WHEN THE USER REQUESTS TO SAVE THE DASHBOARD */
    saveBnt.click(function(){
    	// Variable to store cookie settings in
    	var cookie = "";
    	
    	// Save the order which the widgets are in
    	regions.each(function(){
    		cookie = cookie + "&" + $(this).sortable("serialize").replace(/widget/g, $(this).attr('id'));
    	});
    	
    	// Loop over all widgets and save their values into cookies
    	// If it isn't meant to be shown hide the widget
    	widgets.each(function(){
    		if($('div.action img:visible',this).hasClass('db-hidden')){
    			// Cross is showing
    			cookie = cookie + "&" + $(this).attr('id') + "[]=hidden";
    			$(this).hide();
    		}
    		else{
    			// Tick is showing
    			cookie = cookie + "&" + $(this).attr('id') + "[]=visible";
    		}
    	});
    	
    	// Save cookie
    	$.cookie('bep_dashboard',cookie.substring(1),{expires: 31});
    	
    	// Hide the button and display the edit button
    	saveBnt.hide();
    	editBnt.show();
    	
    	// Make regions sortable
    	regions.sortable("disable");
    	
    	// Show bodies of widgets again
    	$('div.body',widgets).show();
    	
    	// Hide the actions
    	$('.action img',widgets).hide();
    });
  });