<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * BackendPro
     *
     * A website backend system for developers for PHP 4.3.2 or newer
     *
     * @package	        BackendPro
     * @author			Adam Price
     * @copyright		Copyright (c) 2008
     * @license			http://www.gnu.org/licenses/lgpl.html
     * @tutorial		BackendPro.pkg
     */

     // ---------------------------------------------------------------------------

    /**
     * Detect_language
     *
     * Provides functionality to make the website multi-ligual. On creation
     * it tries to detect what language to use, if failing it uses the default.
     *
     * @package			BackendPro
     * @subpackage		Libraries
     */
	class Detect_language
	{
		/**
		 * Constructor
		 */
		function Detect_language()
		{
			// Get CI Instance
			$this->CI = &get_instance();

            $this->CI->load->config('detect_language');
            
			// Load needed files
			$this->CI->load->helper('cookie');
			$this->CI->load->helper('directory');

			// Get available languages
			$this->_available_languages();

			// Try to detect a language
			$this->_detect();

			log_message('debug','Detect_language Class Initialized');
		}

		/**
		 * Set Language
		 *
		 * Lets a user set the language stored in their cookie
		 *
		 * @access public
		 * @param string $language Language name
		 * @return void
		 */
		function set_language($language=NULL)
		{
			// Check the language is one we support
			if (in_array($language,$this->available_languages))
			{
				delete_cookie($this->CI->config->item('language_cookie_name'));
				set_cookie($this->CI->config->item('language_cookie_name'),$language);
			}
			return;
		}

		/**
		 * Detect Browser Language
		 *
		 * Checks if the user has a specifyed language, if not it will try
		 * to detect the browsers language and use that, if both fail
		 * it will use the default language
		 *
		 * @access private
		 * @return void
		 */
		function _detect()
		{
			// Check if a cookie exists with a language value
			if( FALSE !== ($language = get_cookie($this->CI->config->item('language_cookie_name'))))
			{
				if (in_array($language,$this->available_languages))
				{
					$this->CI->config->set_item('language',$language);
					return;
				}
			}

			// Lets get their browser prefered languages
			$accepted_languages = $this->CI->input->server('HTTP_ACCEPT_LANGUAGE');

			foreach ( explode(',',$accepted_languages) as $lang )
			{
				// The language may look like this en;q=0.4
				// So we first need to remove anything to left of ;
				$pos = strpos($lang,';');
				if ( $pos ) {
					// If we did find a ;
					$lang = substr($lang,0,$pos);
				}

				// Since we can get languages like en_gb and en_us remove the _** part
				$lang = substr($lang,0,2);

				// See if we have a language for this key
				if (array_key_exists($lang,$this->available_languages))
				{
					$this->CI->config->set_item('language',$this->available_languages[$lang]);
					break;
				}
			}
			return;
		}

		/**
		 * Detect Available Languages
		 *
		 * Checks in the language dirs to see what folders there are
		 * it then returns an array of language identifier => name
		 *
		 * @access private
		 * @return void
		 */
		function _available_languages()
		{
			$this->available_languages = array();

			$base = directory_map(BASEPATH . "language", TRUE);
			$app = directory_map(APPPATH . "language", TRUE);

			foreach($base as $language)
			{
				// First see if its a dir
				if(is_dir(BASEPATH . "language/".$language))
				{
					// Check we have the matching application language folder
					if (in_array($language,$app))
					{
						// Add the language to the available language array
						$key = array_search($language,$this->CI->config->item('browser_languages'));
						$this->available_languages[$key] = $language;
					}
				}
			}
		}
	}
?>