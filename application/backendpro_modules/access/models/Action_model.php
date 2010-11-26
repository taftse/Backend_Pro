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

/**
 * The action model provides functions to get/insert/update/delete access
 * actions from the database.
 *
 * @subpackage      Access Module
 */
class Action_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();

        $tables = $this->config->item('tables','backendpro');

        // Set base model properties
        $this->table = $tables['actions'];

        log_message('debug','Action_model loaded');
    }

    /**
     * Get all actions by resource ID
     *
     * @param int $resource_id Resource ID
     * @return object
     */
    public function get_by_resource($resource_id)
    {
        return parent::get_by('resource_id', $resource_id);
    }

    /**
     * Insert action
     *
     * @param string $name Action name
     * @param int $resource_id Resource ID
     * @return int
     */
    public function insert($name, $resource_id)
    {
        return parent::insert(array('name' => $name, 'resource_id' => $name));
    }

    /**
     * Update action
     *
     * @param int $id Action ID
     * @param string $name Action name
     * @return bool
     */
    public function update($id, $name)
    {
        return parent::update($id, array('name' => $name));
    }
}

 /* End of Action_model.php */
 /* Location: ./application/backendpro_modules/access/models/Action_model.php */