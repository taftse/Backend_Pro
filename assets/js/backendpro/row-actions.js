$(document).ready(function(){

    // Find all row-action-trigger elements
    $('.row-action-trigger').each(function(){

        // Now find its child row-action element
        var row_action = $('.row-action', $(this));

        row_action.css('display','none');

    });
});