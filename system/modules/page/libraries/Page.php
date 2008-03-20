<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * BackendPro
     *
     * A website backend system for developers for PHP 4.3.2 or newer
     *
     * @package            BackendPro
     * @author            Adam Price
     * @copyright        Copyright (c) 2008
     * @license            http://www.gnu.org/licenses/lgpl.html
     */

     // ---------------------------------------------------------------------------

    /**
     * Page Class
     *
     * Asset management and optimisation library. Allows the programmer
     * a simple way to load default assets (js/css files) for both the public
     * and admin areas of a site. Also allows passing php variables into
     * javascript variables.
     *
     * @package            BackendPro
     * @subpackage        Libraries
     */
    class Page
    {
        function Page()
        {
            // Create CI instance
            $this->CI = &get_instance();

            // Load needed files
            $this->CI->load->config('page');

            // Setup default, js_vars & on-fly asset arrays
            $this->default_assets = $this->CI->config->item('default_assets');
            $this->extra_assets = array();
            $this->variables = $this->CI->config->item('default_page_variables');

            // Setup output string
            $this->output = "";

            // Breadcrumb trail
            $this->breadcrumb = array();

            log_message('debug', "Page Class Initialized");
        }
        // ---------------------------------------------------------------------------

        /**
         * Load Asset file
         *
         * Quick load an asset file for inclusion straight away
         * Checks the given asset is valid, if so adds it to an array ready
         * for inclusion later.
         *
         * @access public
         * @param string $area     Asset type
         * @param string $type     Asset file type
         * @param string $file     Asset file name
         * @return void
         */
        function set_asset($area = NULL,$type = NULL,$file = NULL)
        {
            $file_tmp = $this->CI->config->item($area . "_assets") . $type . "/" . $file;
            if ( ! file_exists($file_tmp))
            {
                // Lets check the asset exists and is valid
                log_message("error","Asset is not valid or does not exist (" . $file_tmp . ").");
                return;
            }

            // Otherwise add file to $this->extra_assets
            $this->extra_assets[$area][$type][] = $file;
            log_message('debug','Quick load of asset (' . $file_tmp . ') successfull');
            return;
        }

        /**
         * Setup transfer variable
         *
         * Transfer a variable from php to javascript
         *
         * @access public
         * @param string $name Variable name
         * @param mixed $value Variable value
         * @return void
         */
        function set_variable($name = NULL,$value = NULL)
        {
            if ( is_null($name))
            {
                log_message("error","When transfering a variable a name must be given.");
                return;
            }

            $this->variables[$name] = $value;
            log_message('debug','PHP variable ('.$name.') transfer successfull');
            return;
        }

        /**
         * Set Breadcrumb
         *
         * @access public
         * @param string $name Name of crumb
         * @param string $link CI Controller link e.g. auth/login
         * @return void
         */
        function set_crumb($name, $link = '')
        {
            $this->breadcrumb_trail[$name] = $link;
            log_message('debug','Breadcrumb link "'.$name.'" pointing to "'.$link.'" created'); 
            return;
        }

        /**
         * Output Breadcrumb Trail
         *
         * @access public
         * @param boolean $print Prints string to page instead of returning it
         * @return string
         */
        function output_breadcrumb($print = TRUE)
        {
            $output = "";

            $i = 1;
            foreach ( $this->breadcrumb_trail as $name => $link )
            {
                if ( $i == count($this->breadcrumb_trail) ) {
                    // On last item, only show text
                    $output .= $name;
                } else {
                    $output .= anchor($link, $name);
                    $output .= " &#187; ";
                }
                $i++;
            }

            // Print/Output trail
            if ($print){
                print $output;
                return;
            }
            return $output;
        }

        /**
         * Output Page Assets & Variables
         *
         * Create HTML code to include css/js files and transfer php variables
         * to javscript ones.
         *
         * @access public
         * @param string $area Specifies which assets to output, either 'public' OR 'admin'
         * @param boolean $print Whether to print the output or return it
         * @return string Valid HTML code for including the needed css/js files into the HEAD tags
         */
        function output_assets ($area = 'public',$print = TRUE)
        {
            if ($area != 'public' AND $area != 'admin')
            {
                // Just check a valid area has been given
                log_message("error","Cannot link asset area '" . $area . "'.");
                return;
            }

            // PREPARE VARIABLE OUTPUT
            // Transfer PHP variables into JS variables
            if (count($this->variables) != 0)
            {
                $this->output .= "<script type=\"text/javascript\">\n<!--\n";
                foreach($this->variables as $name => $value)
                {
                    $this->output .= "var " . $name . " = ";
                    $this->_handle_variable($value);
                    $this->output .= ";\n";
                }
                $this->output .= "// -->\n</script>\n";
            }

            // PREPARE ASSET OUTPUT
            $this->CI->load->helper('file');
            foreach(array('shared',$area) as $type)
            //foreach(array($area) as $type)
            {
                foreach(array('css','js') as $asset)
                {
                    // Asset path
                    $dir = $this->CI->config->item($type . "_assets") . $asset . "/";

                    // Get all files in asset path
                    $asset_files = get_filenames($dir);

                    // First lets check if there is a cache
                    if($this->CI->config->item('asset_cache_length') != 0)
                    {
                        $is_cache = FALSE;
                        foreach($asset_files as $file)
                        {
                            if( preg_match("/".$this->CI->config->item('asset_cache_file_pfx')."([0-9]+).*/",$file,$match)){
                                // Check if the cache file has not expired
                                if($match[1] >= time())
                                {
                                    // Cache is valid
                                    $is_cache = TRUE;
                                    $this->{'_include_' . $asset}($dir . $file);
                                }
                                else
                                {
                                    // Remove old cache file
                                    unlink($dir . $file);
                                }
                                break;
                            }
                        }

                        // We couldn't find a valid cache file so create one
                        if( ! $is_cache)
                            $this->_write_cache($dir,$type,$asset);
                    }
                    else
                    {
                        // Caching is not used link files normally
                        foreach($this->default_assets[$type][$asset] as $file)
                        {
                            if( file_exists ($dir . $file))
                            {
                                $this->{'_include_' . $asset}($dir . $file);
                            }
                        }
                    }

                    // Link any extra asset files loaded on the fly
                    if( isset($this->extra_assets[$type][$asset]) && count($this->extra_assets[$type][$asset]) != 0)
                    {
                        foreach($this->extra_assets[$type][$asset] as $file)
                        {
                            $this->{'_include_' . $asset}($dir . $file);
                        }
                    }
                }
            }

            // Output HTML
            if($print){
                print $this->output;
            } else {
                return $this->output;
            }
        }

        /**
         * Write cache file
         *
         * Write the given asset files to a cache file
         *
         * @access private
         * @param string $path Cache file path
         * @param string $area Choosen area, either public/admin/shared
         * @param string $type Asset type, either css/js
         * @return boolean TRUE if cache file created, FALSE otherwise
         */
        function _write_cache ($asset_path, $asset_area, $asset_type)
        {
            // Check dir is valid and writeable
            if ( ! is_dir ($asset_path) OR ! is_writable ($asset_path))
            {
                log_message('error','Cache path (' . $asset_path . ') is not a directory or is not writable');
                return FALSE;
            }

            if(count($this->default_assets[$asset_area][$asset_type]) == 0)
            {
                // Don't create cache if there are no files to cache
                return FALSE;
            }

            // Create cache path with filename
            $asset_path = $asset_path . $this->CI->config->item('asset_cache_file_pfx') . ceil($this->CI->config->item('asset_cache_length')*3600 + time()) . "." . $asset_type;
            
            //Take what's in the buffer now and give it to ci
            $this->CI->output->append_output(ob_get_contents());
            ob_end_clean();
            
            // Foreach file belonging to $area & $type add it to the cache file
            $cache_output = "";
            $tmp_path = BASEPATH . "../" . $this->CI->config->item($asset_area . "_assets") . $asset_type . "/";
            foreach($this->default_assets[$asset_area][$asset_type] as $asset_file)
            {
                ob_start();
                include $tmp_path . $asset_file;    
                $cache_output .= ob_get_contents();
                ob_end_clean();
            }
            //Restart the buffer so ci doesn't know anything happened
            ob_start();
            
            // Compress the cache data
            $cache_output = $this->_cache_compress($cache_output);

            // Write the cache file and link it
            $this->{'_include_' . $asset_type}($asset_path);
            return write_file($asset_path,$cache_output);
        }

        /**
         * Compress cache
         *
         * Given a string remove comments/line breaks/tabs
         *
         * @access private
         * @param string $data Cache data string
         * @return string Compress Cache data string
         */
        function _cache_compress($data)
        {        
            log_message('debug','Cache file compressed');
            return $data;
        }

        /**
         * Output Script Code
         *
         * @access private
         * @param string $file Filename of script to include
         * @return string Generated script include code
         */
        function _include_js($file)
        {
            $this->output .= '<script type="text/javascript" src="' . base_url() . $file . '"></script>';
            $this->output .= "\n";
        }

        /**
         * Output Style Code
         *
         * @access private
         * @param string $file Filename of style to include
         * @return string Generated style include code
         */
        function _include_css($file)
        {
            $this->output .= '<link rel="stylesheet" type="text/css" href="' . base_url() . $file . '" />';
            $this->output .= "\n";
        }

        /**
         * Handle Variable
         *
         * @access private
         * @param mixed $value
         * @return void
         */
        function _handle_variable($value)
        {
            switch(gettype($value))
            {
                case 'boolean':
                    $this->output .= ($value===TRUE) ? "true" : "false";
                break;

                case 'integer':
                case 'double':
                    $this->output .= $value;
                break;

                case 'string':
                    $this->output .= "\"".$value."\"";
                break;

                case 'array':
                    $this->output .= "new Array(";
                    foreach($array as $value)
                    {
                        $this->_handle_variable($value);
                        $this->output .= ",";
                    }
                    $this->output = substr($this->output,0,-1);
                    $this->output .= ")";
                break;

                default:
                    // Otherwise assume its NULL
                    $this->output .= "null";
                break;
            }
        }
    }
?>