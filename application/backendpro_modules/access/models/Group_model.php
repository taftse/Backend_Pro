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
 * The group model provides functions to get/insert/update/delete access
 * groups from the database.
 *
 * @subpackage      Access Module
 */
class Group_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();

        $tables = $this->config->item('tables','backendpro');

        // Set base model properties
        $this->table = $tables['groups'];
        
        log_message('debug','Group_model loaded');
    }

    /**
     * Insert a new group
     *
     * @param string $name Group name
     * @return int Newly inserted group id
     */
    public function insert($name)
    {
        return parent::insert(array('name' => $name));
    }

    /**
     * Update a group
     *
     * @param int $id Group ID
     * @param string $name Group name
     * @return bool
     */
    public function update($id, $name)
    {
        return parent::update($id, array('name' => $name));
    }

    /**
     * Check to see if a group is locked or not
     *
     * @throws BackendProException
     * @param int $id Group Id
     * @return bool
     */
    public function is_locked($id)
    {
        $group = $this->get($id);

        if ( $group !== false)
        {
            return $group->locked == 1;
        }
        else
        {
            throw new BackendProException(lang('access_group_not_found'));
        }
    }

    /**
     * Check if a group name is unique
     *
     * @param string $name The name to check
     * @return bool
     */
    public function is_unique($name)
    {
        log_message('debug:backendpro', sprintf('Checking if the Group name `%s` is unique', $name));
        $result = parent::get_by(array('name' => $name));

        log_message('debug:backendpro', 'Group name is ' . ($result === false ? 'unique' : 'not unique'));
        return $result === false;
    }
}

 /* End of Group_model.php */
 /* Location: ./application/backendpro_modules/access/models/Group_model.php */