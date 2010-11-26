function create_menu(basepath)
{
	var base = (basepath == 'null') ? '' : basepath;

	document.write(
		'<table cellpadding="0" cellspaceing="0" border="0" style="width:98%"><tr>' +
		'<td class="td" valign="top">' +

		'<ul>' +
		'<li><a href="'+base+'index.html">User Guide Home</a></li>' +	
		'</ul>' +	

		'<h3>Basic Info</h3>' +
		'<ul>' +
			'<li><a href="'+base+'basic_info/requirements.html">Server Requirements</a></li>' +
			'<li><a href="'+base+'basic_info/license.html">License Agreement</a></li>' +
			'<li><a href="'+base+'basic_info/changelog.html">Change Log</a></li>' +
			'<li><a href="'+base+'basic_info/roadmap.html">#Roadmap</a></li>' +
			'<li><a href="'+base+'basic_info/credits.html">#Credits</a></li>' +
		'</ul>' +	
		
		'<h3>Installation</h3>' +
		'<ul>' +
			'<li><a href="'+base+'installation/downloading.html">Downloading BackendPro</a></li>' +
			'<li><a href="'+base+'installation/installation.html">Installation Instructions</a></li>' +
			'<li><a href="'+base+'installation/upgrading.html">#Upgrading from a Previous Version</a></li>' +
			'<li><a href="'+base+'installation/troubleshooting.html">#Troubleshooting</a></li>' +
		'</ul>' +
		
		'<h3>Introduction</h3>' +
		'<ul>' +
			'<li><a href="'+base+'overview/getting_started.html">#Getting Started</a></li>' +
			'<li><a href="'+base+'overview/at_a_glance.html">#BackendPro at a Glance</a></li>' +
			'<li><a href="'+base+'overview/features.html">#Supported Features</a></li>' +
			'<li><a href="'+base+'overview/goals.html">#Design and Architectural Goals</a></li>' +
		'</ul>' +	

				
		'</td><td class="td_sep" valign="top">' +

		'<h3>General Topics</h3>' +
		'<ul>' +
            '<li><a href="'+base+'general/developing.html">#Developing with BackendPro</a></li>' +
			'<li><a href="'+base+'general/controllers.html">BackendPro Controllers</a></li>' +
			'<li><a href="'+base+'general/core_modules.html">#Core Modules</a></li>' +		
			'<li><a href="'+base+'libraries/Template.html#usage">Template System</a></li>' +
            '<li><a href="'+base+'general/permissions.html">#Permission System</a></li>' +
            '<li><a href="'+base+'general/settings.html">#Setting Management</a></li>' +
            '<li><a href="'+base+'general/exceptions.html">#BackendPro Exceptions</a></li>' +
			'<li><a href="'+base+'general/modular_extensions.html">Modular Extensions - HMVC</a></li>' +
            '<li><a href="'+base+'general/extending.html">#Extending BackendPro</a></li>' +     
		'</ul>' +
		
		'</td><td class="td_sep" valign="top">' +

				
		'<h3>Library Reference</h3>' +
		'<ul>' +		
		'<li><a href="'+base+'libraries/Asset.html">#Asset Class</a></li>' +
		'<li><a href="'+base+'libraries/User.html">User Library</a></li>' +
		'<li><a href="'+base+'libraries/Dashboard.html">#Dashboard Class</a></li>' +
		'<li><a href="'+base+'libraries/EmailHandler.html">#Email Handler Class</a></li>' +
		'<li><a href="'+base+'libraries/FormValidation.html">#Extended Form Validation Class</a></li>' +
		'<li><a href="'+base+'libraries/Setting.html">Setting Library</a></li>' +
		'<li><a href="'+base+'libraries/Status.html">#Status Class</a></li>' +
        '<li><a href="'+base+'libraries/Template.html">Template Library</a></li>' +
		'</ul>' +

		'</td><td class="td_sep" valign="top">' +

		'<h3>Helper Reference</h3>' +
		'<ul>' +
		'<li><a href="'+base+'helpers/authentication_helper.html">#Authentication Helper</a></li>' +
		'<li><a href="'+base+'helpers/language_helper.html">Extended Language Helper</a></li>' +
        '<li><a href="'+base+'helpers/date_helper.html">Extended Date Helper</a></li>' +
		'<li><a href="'+base+'helpers/setting_helper.html">Setting Helper</a></li>' +
		'</ul>' +	
		
		'<h3>Other Reference</h3>' +
		'<ul>' +
		'<li><a href="'+base+'other/constants.html">Extended Constants</a></li>' +
		'<li><a href="'+base+'other/common_lang.html">Common Languange</a></li>' +
		'</ul>' +	

		'<h3>Additional Resources</h3>' +
		'<ul>' +
		'<li><a href="http://codeigniter.com/forums/viewthread/76078/">Community Discussion</a></li>' +
		'<li><a href="http://backendpro.co.uk/">Project Site</a></li>' +
		'<li><a href="http://backendpro.co.uk/development">Developement Site</a></li>' +
		'</ul>' +	
		
		'</td></tr></table>');
}