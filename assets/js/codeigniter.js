
/**
 * Check the required CodeIgniter variables exist. If not
 * warn the user
 * 
 * @return
 */
function check_variables()
{
	if(typeof index_page === "undefined")
		alert("Warning: CodeIgniter JavaScript variable index_page is undefined");
	
	if(typeof base_url === "undefined")
		alert("Warning: CodeIgniter JavaScript variable base_url is undefined");
	
	if(typeof url_suffix === "undefined")
		alert("Warning: CodeIgniter JavaScript variable url_suffix is undefined");	
	
	if(typeof uri_string === "undefined")
		alert("Warning: CodeIgniter JavaScript variable uri_string is undefined");
}

/**
 * Site URL
 * 
 * This is the same method found in the CodeIgniter Config library
 * 
 * @param string uri the URI string
 * @return string
 */
function site_url(uri)
{
	// TODO: Dosn't handle if uri is an array, should implode it
	
	if(uri == undefined || uri == '')
	{
		return addendslash(base_url) + index_page;
	}
	else
	{
		// TODO: Should really trim(uri, '/')
		var suffix = (url_suffix == false) ? '' : url_suffix;
		return addendslash(base_url) + addendslash(index_page) + uri + suffix;
	}	
}

/**
 * Add End Slash
 * 
 * Adds and end / to a string if it dosn't already have one
 * 
 * @param string value
 * @return string
 */
function addendslash(value)
{
	var lastChar = value.substring(value.length - 1);
	
	if(value != '' && lastChar != '/')
	{
		value += '/';
	}
	
	return value;
}

/**
 * Run the check to see if the CodeIgniter variables
 * exists.
 */
check_variables();