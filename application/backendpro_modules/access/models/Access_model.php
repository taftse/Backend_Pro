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
 * The Access model allows checks to be performed on a group
 * for if it has access to a specific resource and action.
 *
 * @subpackage      Access Module
 */
class Access_model extends CI_Model
{
    /**
     * BackendPro Database tables
     * 
     * @var array
     */
    private $tables = array();

    public function __construct()
    {
        parent::__construct();

        // Get the Database tables
        $this->tables = $this->config->item('tables', 'backendpro');

        log_message('debug','Access_model loaded');
    }

    /**
     * Checks if a group has access to a specific resource and optionally
     * an action
     *
     * @param int $group_id Group ID
     * @param string $resource Resource name
     * @param string $action Action name if given
     * @return bool
     */
    public function has_access($group_id, $resource, $action = NULL)
    {
        // Get the tables
        $permissions = $this->tables['permissions'];
        $permission_actions = $this->tables['permission_actions'];
        $resources = $this->tables['resources'];
        $actions = $this->tables['actions'];

        $this->db->from($permissions . ' AS permissions');
        $this->db->join($resources . ' AS resources', 'resources.id = permissions.resource_id');        

        $this->db->where('permissions.group_id', $group_id);
        $this->db->where('resources.name', $resource);

        // If we are checking permission on an action add an extra clause
        if($action != NULL)
        {
            $this->db->join($permission_actions . ' AS permission_actions', 'permission_actions.permission_id = permissions.id', 'LEFT');
            $this->db->join($actions . ' AS actions', 'actions.id = permission_actions.action_id', 'LEFT');
            $this->db->where('actions.name', $action);
        }        

        $result = $this->db->get();

        if($result === FALSE)
        {
            throw new DatabaseException("Unable to check if the user has permission to the resouce");
        }

        if($result->num_rows() > 0)
        {
            // Permission has been found
            log_message('debug','User does have access to \'' . $resource . ($action != NULL ? '.' . $action : '') . '\'');
            return TRUE;
        }
        else
        {
            // Permission not found
            log_message('debug','User does not have access to \'' . $resource . ($action != NULL ? '.' . $action : '') . '\'');   
            return FALSE;
        }
    }
}

/* End of Access_model.php */
/* Location: ./application/backendpro_modules/access/models/Access_model.php */