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

require_once dirname(__FILE__).'/access_ajax.php';

/**
 * The Access fetch controller handles all ajax data fetch
 * operations for the access module.
 *
 * @subpackage      Access Module
 */
class Access_fetch extends Access_ajax
{
    public function __construct()
    {
        parent::__construct();
        
        log_message('debug', 'Access_fetch class loaded');
    }

    /**
     * Load all groups and output them as JSON
     *
     * @return json
     */
    public function load_groups()
    {
        $this->block_none_ajax();
        log_message('debug', 'Loading all user groups');

        $this->load->model('group_model');

        try
        {
            $groups = $this->group_model->get_all();
            $json = json_encode($groups);

            log_message('debug', 'Groups: ' . $json);
            print $json;
        }
        catch(Exception $ex)
        {
            $this->ajax_error(lang('access_group_load_failure'));
        }
    }

    /**
     * Load all resources and perform permission checks on each
     * for the given group
     *
     * @return xml
     */
    public function load_resources()
    {
        $this->block_none_ajax();
        log_message('debug', 'Loading all resources');

        // Get the posted group id
        $group_id = $this->input->post('group');
        log_message('debug', 'Permissions will be calculated for the group with id ' . $group_id);

        if($group_id === false || !is_numeric($group_id))
        {
            $this->ajax_error(lang('access_invalid_group_id'));
        }

        $this->load->model('resource_model');
        $this->load->model('access_model');

        try
        {
            $resources = $this->resource_model->get_all();

            // Output the resources as nested Xml
            $output = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
            $output .= "<resources>\n";
            $output .= $this->generate_resource_xml($group_id, $resources, NULL);
            $output .= "</resources>\n";

            header ("content-type: text/xml");
            print $output;
        }
        catch(Exception $ex)
        {
            $this->ajax_error(lang('access_resource_load_failure'));
        }
    }

    /**
     * Generate Xml for a single resource
     *
     * @param int $group_id Group ID
     * @param Objects $resources Resource result set
     * @param int $parent_id Parent ID
     * @return void
     */
    private function generate_resource_xml($group_id, $resources, $parent_id)
    {
        $output = "";

        foreach($resources as $resource)
        {
            // Resource is a child of the parent
            if($resource->parent_id == $parent_id)
            {
                // Check if the group has access
                $has_access = ($this->access_model->has_access($group_id, $resource->id, NULL)) ? 'true' : 'false';

                $output .= "<resource name='" . $resource->name . "' id='" . $resource->id . "' has_access='" . $has_access . "' locked='" . $resource->locked . "'>\n";
                $output .= $this->generate_resource_xml($group_id, $resources, $resource->id);
                $output .= "</resource>\n";
            }
        }

        return $output;
    }

    /**
     * Load all actions for a given resource and perform
     * permission checks for the given group
     *
     * @return void
     */
    public function load_actions()
    {
        $this->block_none_ajax();

        // Get the posted group id & resource id
        $group_id = $this->input->post('group_id');
        $resource_id = $this->input->post('resource_id');
        log_message('debug', 'Loading all actions for group = ' . $group_id . ' & resource = ' . $resource_id);

        if($group_id === false || !is_numeric($group_id))
        {
            $this->ajax_error(lang('access_invalid_group_id'));
        }
        else if($resource_id === false || !is_numeric($resource_id))
        {
            $this->ajax_error(lang('access_invalid_resource_id'));
        }

        $this->load->model('action_model');
        $this->load->model('access_model');

        try
        {
            $actions = $this->action_model->get_all_by_resource($resource_id);

            $json_array = array();

            foreach($actions as $action)
            {
                // Check if the group has access to this action
                $has_access = $this->access_model->has_access($group_id, $resource_id, $action->id);

                $json_array[] = array('id' => $action->id, 'name' => $action->name, 'has_access' => $has_access, 'locked' => $action->locked);
            }

            $json = json_encode($json_array);
            log_message('debug', 'Actions: ' . $json);
            print $json;
        }
        catch (Exception $ex)
        {
            $this->ajax_error(lang('access_action_load_failure'));
        }
    }
}

/* End of access_fetch.php */
/* Location: ./application/backendpro_modules/access/controllers/access_fetch.php */