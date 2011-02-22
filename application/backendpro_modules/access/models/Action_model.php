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
     * Get an action by resource ID
     *
     * @param int $resource_id Resource ID
     * @return object
     */
    public function get_by_resource($resource_id)
    {
        return parent::get_by('resource_id', $resource_id);
    }

    /**
     * Get all actions with a given resource Id
     *
     * @param int $resource_id Resource Id
     * @return objects
     */
    public function get_all_by_resource($resource_id)
    {
        return parent::get_all_by('resource_id', $resource_id);
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
        return parent::insert(array('name' => $name, 'resource_id' => $resource_id));
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

    /**
     * Check to see if an action is locked or not
     *
     * @throws BackendProException
     * @param int $id Action Id
     * @return bool
     */
    public function is_locked($id)
    {
        $action = $this->get($id);

        if ( ! is_null($action))
        {
            return $action->locked == 1;
        }
        else
        {
            throw new BackendProException(lang('access_action_not_found'));
        }
    }

    /**
     * Check if a action name is unique
     *
     * @param string $name The name to check
     * @return bool
     */
    public function is_unique($name)
    {
        log_message('debug:backendpro', sprintf('Checking if the Action name `%s` is unique', $name));
        $result = parent::get_by(array('name' => $name));

        log_message('debug:backendpro', 'Action name is ' . ($result === false ? 'unique' : 'not unique'));
        return $result === false;
    }
}

 /* End of Action_model.php */
 /* Location: ./application/backendpro_modules/access/models/Action_model.php */