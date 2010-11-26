<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 5.2.6 or newer
 *
 * @package         BackendPro
 * @author          Adam Price <adam@kaydoo.co.uk>
 * @copyright       2008-2010, Adam Price
 * @license			http://www.opensource.org/licenses/mit-license.php MIT
 * @license         http://www.gnu.org/licenses/gpl.html GPL
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

/**
 * The Template library makes outputing views easyier. It also allows partial
 * views to be created and used along with other page concepts such as Breadcrumb's,
 * metadata etc.
 */
class Template
{
    /**
     * Breadcrumb links for the current page
     *
     * @var array
     */
    private $breadcrumbs = array();

    /**
     * All metadata to output for the current page
     *
     * @var array
     */
    private $metadata = array();

    /**
     * A global array of page variables. This will be the final
     * array of values passed to the view when built
     *
     * @var array
     */
    public $data = array();

    /**
     * Collection of partial views to load
     * @var array
     */
    private $partials = array();

    /**
     * The layout view file to use
     *
     * @var string
     */
    public $layout = '';

    /**
     * The title of the page
     * 
     * @var string
     */
    private $title = '';

    /**
     * The site title including the site name if given
     * 
     * @var string
     */
    private $site_title = '';

    /**
     * The site name to include in the page title
     * 
     * @var string
     */
    public $site_name = '';

    /**
     * The list of current PHP to JS variables to convert
     * 
     * @var array
     */
    private $variables = array();

    public function __construct()
    {
        $CI =& get_instance();
        
        $CI->load->helper('html');
        $CI->load->config('template/template', TRUE);

        log_message('debug', 'Template Library loaded');
    }

    /**
     * Build the required view
     *
     * @param string $view The view to load
     * @param array $data The data variables to pass to the view
     * @param bool $return Whether to return the output or print it
     * @param bool $wrap_with_layout Whether to wrap the view with the layout
     * @return 
     */
    public function build($view, $data = array(), $return = FALSE, $wrap_with_layout = TRUE)
    {
        $CI =& get_instance();

        // Merge the data arrays
        $this->data = array_merge($this->data, $data);

        // Populate the template master variables
        $template['title'] = $this->title;
        $template['site_title'] = $this->site_title;
        $template['metadata'] = $this->render_metadata();
        $template['breadcrumbs'] = $this->render_breadcrumbs();
        $template['partials'] = $this->partials;
        $template['variables'] = $this->render_variables();

        // Add the template to the data
        $this->data['template'] = $template;

        // Load the view we want to build
        $output = $CI->load->view($view, $this->data, TRUE);
        
        // If we have a layout then load it, and wrap our output with it
        if(!empty($this->layout) && $wrap_with_layout)
        {
            log_message('debug', 'Wrapping the view with the main layout file ' . $this->layout);
            // Pass our output to the layout file
            $this->data['body'] = $output;

            // Load the layout view file
            $output = $CI->load->view($this->layout, $this->data, TRUE);
        }

        // Either output the result or return it
        if($return)
        {
            return $output;
        }
        else
        {
            $CI->output->set_output($output);
        }
    }

    /**
     * Set the site title
     * 
     * @param string $title The title to set on the page
     * @return void
     */
    public function set_title($title)
    {
        $this->title = $title;

        $CI =& get_instance();

        $separator = $CI->config->item('title_separator', 'template');

        // If we have a site name then add it to the title
        if($this->site_name != '')
        {
            $this->site_title = $this->title . $separator . $this->site_name;
        }
        else
        {
            $this->site_title = $this->title;
        }

        log_message('debug','Site title set to \'' . $this->site_title . '\'');
    }

    /**
     * Set a partial view to be outputed into the final output
     *
     * @param string $name Partial view name
     * @param string $view The view to load
     * @param array $data Data variable to set on the view
     * @param bool $overwrite Whether to allow a partial view to be overridden
     * @return void
     */
    public function set_partial($name, $view, $data = array(), $overwrite = FALSE)
    {
        // Check a partial with the same name doesn't exist
        // If it does and we havn't been told to overwrite it, throw an error
        if(in_array($name, $this->partials) && $overwrite == FALSE)
        {
            show_error('\'' . $name . '\' is already being used as a partial view name, pleaase choose a unique name');
            return;
        }

        $CI =& get_instance();
        log_message('debug', 'Setting the view partial ' . $name);
        $this->partials[$name] = $CI->load->view($view, $data, TRUE);
    }

    /**
     * Set a metadata tag ready to be outputed on template build
     *
     * @param string $name The name of the tag
     * @param string $content The contents
     * @param bool $http TRUE for an equiv metatag, FALSE for a name metatag
     * @return void
     */
    public function set_metadata($name, $content, $http = FALSE)
    {
        if(empty($name) || empty($content))
        {
            log_message('error','Invalid metadata tag provided, cannot add to template');
            return;
        }

        log_message('debug', 'Setting the meta data value ' . $name);
        $this->metadata[$name] = array('content' => $content, 'type' => ($http?'equiv':'name'));
    }

    /**
     * Render the metadata to be outputed to the screen
     *
     * @return string
     */
    private function render_metadata()
    {       
        $html = array();
        log_message('debug', 'Rendering ' . count($this->metadata) . ' metadata values');

        foreach($this->metadata as $name => $tag)
        {
            // Clean them
            $name = htmlspecialchars(strip_tags($name));
            $content = htmlspecialchars(strip_tags($tag['content']));

            $html[] = meta($name, $content, $tag['type']);
        }
        
        return implode("\n\t\t", $html);
    }

    /**
     * Render the breadcrumb trail
     * 
     * @return string
     */
    private function render_breadcrumbs()
    {
        $CI =& get_instance();

        $data['breadcrumbs'] = $this->breadcrumbs;
        $data['display_final_link'] = $CI->config->item('display_final_link','template');
        $data['separator'] = $CI->config->item('breadcrumb_separator','template');

        return $CI->load->view('template/breadcrumb', $data, TRUE);
    }

    /**
     * Render the JS variables
     * 
     * @return string
     */
    private function render_variables()
    {
        if (count($this->variables) > 0)
        {
            $output = "<script type=\"text/javascript\">\n<!--\n";
            foreach($this->variables as $name => $value)
            {
                $output .= "var " . $name . " = ";
                $output .= $this->convert_variable($value);
                $output .= ";\n";
            }
            $output .= "// -->\n</script>\n";

            return $output;
        }

        return NULL;
    }

    /**
     * Convert a PHP variable to a JS variable
     *
     * @param mixed $value PHP variable value to convert
     * @return string
     */
    private function convert_variable($value)
    {
        $output = NULL;
        switch(gettype($value))
        {
            case 'bool':
            case 'boolean':
                $output .= ($value===TRUE) ? "true" : "false";
                break;

            case 'integer':
            case 'double':
                $output .= $value;
                break;

            case 'string':
                $output .= "\"".$value."\"";
                break;

            case 'array':
                $output .= "new Array(";
                foreach($value as $item)
                {
                    $output .= $this->_handle_variable($item);
                    $output .= ",";
                }
                $output = substr($output,0,-1);
                $output .= ")";
                break;

            default:
                // Otherwise assume its NULL
                $output .= "null";
                break;
        }

        return $output;
    }

    /**
     * Set a breadcrumb link
     *
     * @param string $title The link title
     * @param string $uri The URI of the link
     * @return void
     */
    public function set_breadcrumb($title, $uri = FALSE)
    {
        log_message('debug','Breadcrumb added for ' . $title . ' => ' . $uri);
        $this->breadcrumbs[] = array('title' => $title, 'uri' => $uri);
    }

    /**
     * Set a new variable to output to the page
     *
     * @param string $name Variable name
     * @param string $value Variable value
     * @return void
     */
    public function set_variable($name, $value = '')
    {
        if(is_array($name))
        {
            foreach($name as $key => $value)
            {
                $this->set_variable($key, $value);
            }
            return;
        }

        $this->variables[$name] = $value;
    }
}

/* End of file Template.php */
/* Location: ./application/backendpro_modules/template/libraries/Template.php */