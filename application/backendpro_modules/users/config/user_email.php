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

$config['email']['useragent'] = "BackendPro";
$config['email']['protocol'] = 'smtp';
$config['email']['mailpath'] = '/usr/sbin/sendmail';
$config['email']['smtp_host'] = '';
$config['email']['smtp_user'] = '';
$config['email']['smtp_pass'] = '';
$config['email']['smtp_port'] = 25;
$config['email']['smtp_timeout'] = 5;
$config['email']['wordwrap'] = FALSE;
$config['email']['wrapchars'] = 76;
$config['email']['mailtype'] = 'text';
$config['email']['charset'] = 'utf-8';
$config['email']['bcc_batch_mode'] = FALSE;
$config['email']['bcc_batch_size'] = 200;

/* End of user_email.php */
/* Location: ./application/backendpro_modules/users/config/user_email.php */