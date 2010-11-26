<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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

if(!function_exists('mysqldatetime_to_timestamp'))
{
    /**
    * Convert MySQL's DATE (YYYY-MM-DD) or DATETIME (YYYY-MM-DD hh:mm:ss) to a unix timestamp
    *
    * Returns the timestamp equivalent of a given DATE/DATETIME
    *
    * @todo add regex to validate given datetime
    * @author Clemens Kofler <clemens.kofler@chello.at>
    * @return int
    */
    function mysqldatetime_to_unix($datetime = '')
    {
        // function is only applicable for valid MySQL DATETIME (19 characters) and DATE (10 characters)
        $l = strlen($datetime);
        if(!($l == 10 || $l == 19))
        {
            return 0;
        }
    
        $date = $datetime;
        $hours = 0;
        $minutes = 0;
        $seconds = 0;

        // DATETIME only
        if($l == 19)
        {
            list($date, $time) = explode(" ", $datetime);
            list($hours, $minutes, $seconds) = explode(":", $time);
        }

        list($year, $month, $day) = explode("-", $date);

        return mktime($hours, $minutes, $seconds, $month, $day, $year);
    }
}

if(!function_exists('mysqldatetime_to_date'))
{
    /**
    * Convert MySQL's DATE (YYYY-MM-DD) or DATETIME (YYYY-MM-DD hh:mm:ss) to date using given format string
    *
    * Returns the date (format according to given string) of a given DATE/DATETIME
    *
    * @author Clemens Kofler <clemens.kofler@chello.at>
    * @return string
    */
    function mysqldatetime_to_date($datetime = '', $format = 'd.m.Y, H:i:s')
    {
        return date($format, mysqldatetime_to_unix($datetime));
    }
}
 
/* End of MY_date_helper.php */
/* Location: ./application/helpers/MY_date_helper.php */