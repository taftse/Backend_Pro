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
            var settings = {
                'access_groups' : '#access_groups',
                'access_resources' : '#access_resources',
                'access_actions' : '#access_actions'
            };

            // If options exist, lets merge them
            // with our default settings
            if (options)
            {
                $.extend(settings, options);
            }

            // Declare some variables which will help us
            var access_groups = $(settings.access_groups);
            var access_resources = $(settings.access_resources);
            var access_actions = $(settings.access_actions);

            // This is the currently selected group the user has clicked on
            var selected_group = null;

            // This is the currently selected resource the user has clicked on
            var selected_resource = null;

            // Load the initial groups
            perform_ajax_post('load_groups', '', load_groups_onsuccess, 'json');

            function load_groups_onsuccess(json)
            {
                alert('Loaded');
            }

            /**
             * Perform an ajax post request to a specific method with
             * certain data.
             */
            function perform_ajax_post(method, data, callback, dataType)
            {
                $.ajax({
                    url: site_url('access/' + method),
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
        });
    };
})(jQuery);