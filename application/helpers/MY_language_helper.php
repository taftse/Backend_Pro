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

if( !function_exists('translate_lang'))
{
	/**
	 * Translate Language String
	 *
	 * Attempt to translate the string in case a language
	 * line needs to be used instead.
	 *
	 * E.g. If language:my_lang string is given, it will replace
	 * the string with the correct language string
	 * 
	 * @param string $string String to search
	 * @return string
	 */
	function translate_lang($string)
	{
		// Do we need to translate the string?
		// We look for the prefix language: to determine this
		if (substr($string, 0, 5) == 'lang:')
		{
			$CI = &get_instance();

			// Grab the variable
			$line = substr($string, 5);

			// Were we able to translate the string?  If not we use $line
			if (FALSE === ($string = $CI->lang->line($line)))
			{
				return $line;
			}
		}

		return $string;
	}
}

/* End of file MY_language_helper.php */
/* Location: ./application/backendpro_modules/core/helpers/MY_language_helper.php */