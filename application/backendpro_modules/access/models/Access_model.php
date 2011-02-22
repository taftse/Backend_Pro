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
     * @param string|int $resource_id Resource name or id
     * @param string|int $action_id Action name or id if given
     * @return bool
     */
    public function has_access($group_id, $resource_id, $action_id = null)
    {
        // Get the tables
        $permissions = $this->tables['permissions'];
        $permission_actions = $this->tables['permission_actions'];
        $resources = $this->tables['resources'];
        $actions = $this->tables['actions'];

        $action_msg = (is_null($action_id) ? '' : ' and action ' . $action_id);
        log_message('debug', sprintf('Checking if the group %s has access to resource %s%s', $group_id, $resource_id, $action_msg));

        $this->db->from($permissions . ' AS permissions');
        $this->db->join($resources . ' AS resources', 'resources.id = permissions.resource_id');        

        $this->db->where('permissions.group_id', $group_id);

        // Depending on if the resource is the name or id adjust the query
        if (is_numeric($resource_id))
        {
            $this->db->where('resources.id', $resource_id);
        }
        else
        {
            $this->db->where('resources.name', $resource_id);
        }

        // If we are checking permission on an action add an extra clause
        if ( ! is_null($action_id))
        {
            $this->db->join($permission_actions . ' AS permission_actions', 'permission_actions.permission_id = permissions.id', 'LEFT');
            $this->db->join($actions . ' AS actions', 'actions.id = permission_actions.action_id', 'LEFT');

            // Depending on if the action is the name or id adjust the query
            if (is_numeric($action_id))
            {
                $this->db->where('actions.id', $action_id);
            }
            else
            {
                $this->db->where('actions.name', $action_id);
            }           
        }        

        $result = $this->db->get();
        
        if ($result === false)
        {
            throw new DatabaseException("Unable to check if the user has permission to the resource");
        }

        if ($result->num_rows() > 0)
        {
            // Permission has been found
            log_message('debug','Access Allowed');
            return true;
        }
        else
        {
            // Permission not found
            log_message('debug','Access Denied');
            return false;
        }
    }

    /**
     * Grant the group access to a given resource & optional action
     *
     * @throws DatabaseException
     * @param int $group_id Group Id
     * @param int $resource_id Resource Id
     * @param int|false $action_id Action Id
     * @return void
     */
    public function grant_access($group_id, $resource_id, $action_id = false)
    {
        log_message('debug', sprintf('Granting access to resource:action = %s:%s for group %s', $resource_id, $action_id, $group_id));
        $CI = &get_instance();
        $CI->load->model('resource_model');

        $this->db->trans_start();

        // Fetch the resource
        if (($resource = $CI->resource_model->get($resource_id)) !== false)
        {
            $rt = $this->tables['resources'];
            $pt = $this->tables['permissions'];

            // Get all parent resources which the user does not have
            // access to at the moment
            $this->db->select("$rt.id");
            $this->db->from($rt);
            $this->db->join($pt, "$rt.id = $pt.resource_id AND `$pt`.`group_id` = $group_id", 'left');
            $this->db->where('lft <=', $resource->lft);
            $this->db->where('rgt >=', $resource->rgt);
            $this->db->where('group_id', null);

            if (($resources = $this->db->get()) === false)
            {
                throw new DatabaseException('Could not get all parent resources');
            }

            if ($resources->num_rows() > 0)
            {
                log_message('debug', sprintf('Granting access to %s parent resources', $resources->num_rows()));
                // Now update the permission table by granting the group
                // access to all the resources
                $permissions = array();

                foreach ($resources->result() as $value)
                {
                    $permissions[] = array('group_id' => $group_id, 'resource_id' => $value->id);
                }

                if ($this->db->insert_batch($pt, $permissions) === false)
                {
                    throw new DatabaseException('Cannot create resource permissions');
                }
                log_message('debug', 'Resource access granted');
            }

            // If an action_id has been given then create a permission for it
            if ($action_id !== false)
            {
                log_message('debug', 'Granting access to action ' . $action_id);
                // Get the permission id which matches the group & resource
                $permission_id = $this->get_permission_id($group_id, $resource_id);

                // Insert into the permission_actions table
                if ($this->db->insert($this->tables['permission_actions'], array('permission_id' => $permission_id, 'action_id' => $action_id)) === false)
                {
                    throw new DatabaseException('Cannot create action permission');
                }
                log_message('debug', 'Action access granted');
            }
        }
        else
        {
            throw new DatabaseException('Unable to get the resource with Id' . $resource_id);
        }

        $this->db->trans_complete();
        log_message('debug', 'Access granted');
    }

    /**
     * Get the permission id for a given group & resource
     *
     * @throws DatabaseException
     * @param int $group_id Group Id
     * @param int $resource_id Resource Id
     * @return int
     */
    private function get_permission_id($group_id, $resource_id)
    {
        $result = $this->db->get_where($this->tables['permissions'], array('group_id' => $group_id, 'resource_id' => $resource_id));

        if ($result === false)
        {
            throw new DatabaseException('Unable to get the permission with with (group_id, resource_id) (' . $group_id . ',' . $resource_id . ')');
        }

        if ($result->num_rows() != 1)
        {
            throw new BackendProException('Expected 1 permission for group/resource, ' . $result->num_rows() . ' found');
        }

        return $result->row()->id;
    }

    /**
     * Revoke any permissions a group has to a specific resource and action
     *
     * @param int $group_id Group Id
     * @param int $resource_id Resource Id
     * @param int $action_id Action Id
     * @return void
     */
    public function revoke_access($group_id, $resource_id, $action_id = null)
    {
        log_message('debug', sprintf('Revoking access to resource:action = %s:%s for group %s', $resource_id, $action_id, $group_id));
        
        // Get the matching permission id
        $permission_id = $this->get_permission_id($group_id, $resource_id);
        
        if ( ! is_null($action_id) && $action_id !== false)
        {
            // We must only revoke access to the action not the resource
            if ($this->db->delete($this->tables['permission_actions'], array('permission_id' => $permission_id, 'action_id' => $action_id)) === false)
            {
                throw new DatabaseException('Unable to delete the action permission');
            }
        }
        else
        {
            // Delete the resource permission
            if ($this->db->delete($this->tables['permissions'], array('id' => $permission_id)) === false)
            {
                throw new DatabaseException('Unable to delete the resource permission');
            }
        }
        log_message('debug', 'Access revoked');
    }
}

/* End of Access_model.php */
/* Location: ./application/backendpro_modules/access/models/Access_model.php */