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
 * The Access modify class handles all ajax data modify operations
 *
 * @subpackage      Access Module
 */
class Access_modify extends Access_ajax
{
     /**
     * An array of all allowed sections for the permission
     * manager
     *
     * @var array
     */
    private $allowed_sections = array('group', 'resource', 'action');

    /**
     * An array of all allowed permissions
     *
     * @var array
     */
    private $allowed_permissions = array('allow', 'deny');

    public function __construct()
    {
        parent::__construct();

        $this->load->config('access', true);

        log_message('debug', 'Access_modify class loaded');
    }

    /**
     * Change the permissions a specific user group has on a resource+action
     *
     * @return void
     */
    public function change_permission()
    {
        // TODO: Can we not move this into the constructor??
        $this->block_none_ajax();
        
        // Get all submitted data
        $group_id = $this->input->post('group_id');
        $resource_id = $this->input->post('resource_id');
        $action_id = $this->input->post('action_id');
        $permission = $this->input->post('permission');

        $action_msg = $action_id === false ? '' : ' and action ' . $action_id;
        log_message('debug',sprintf('Changing the permission for group `%s` to access resource `%s`%s to `%s`', $group_id, $resource_id, $action_msg, $permission));

        // Validate the values
        if($group_id === false || !is_numeric($group_id))
        {
            $this->ajax_error(lang('access_invalid_group_id'));
        }
        else if($resource_id === false || !is_numeric($resource_id))
        {
            $this->ajax_error(lang('access_invalid_resource_id'));
        }
        else if ( ! in_array($permission, $this->allowed_permissions))
        {
            $this->ajax_error(lang('access_invalid_permission'));
        }

        $this->load->model('access_model');

        try
        {
            // Grant/Revoke the user depending on the permission value
            switch($permission)
            {
                case 'allow':
                    $this->access_model->grant_access($group_id, $resource_id, $action_id);
                    break;

                case 'deny':
                    $this->access_model->revoke_access($group_id, $resource_id, $action_id);
                    break;
            }
        }
        catch (Exception $ex)
        {
            $this->ajax_error(lang('access_unable_to_change_permission'));
        }
    }

    /**
     * Save a group
     * 
     * @return void
     */
    public function save_group()
    {
        $this->block_none_ajax();

        $value = $this->input->post('value');
        $id = $this->input->post('id');
        log_message('debug', sprintf('Saving the group name `%s`%s', $value, ($id === false ? '' : ' to the group ' . $id)));

        // Validate the values we have been given
        if ($value === false || !is_string($value))
        {
            $this->ajax_error(sprintf(lang('access_invalid_value'), $value));
        }
        else if ($id !== false && ! is_numeric($id))
        {
            $this->ajax_error(sprintf(lang('access_invalid_id'), $id));
        }

        // TODO: Perform strict validation again just to make sure

        $this->load->model('group_model');

        try
        {
            if ($id === false)
            {
                // Create a new create
                $this->group_model->insert($value);
            }
            else if ( ! $this->group_model->is_locked($id))
            {
                // Update an existing group
                $this->group_model->update($id, $value);
            }
            else
            {
                $this->ajax_error(lang('access_unable_to_modify_locked_item'));
            }
        }
        catch (Exception $ex)
        {
            $this->ajax_error(lang('access_unable_to_save_group'));
        }
    }

    /**
     * Save a Resource
     * 
     * @return void
     */
    public function save_resource()
    {
        $this->block_none_ajax();

        $value = $this->input->post('value');
        $id = $this->input->post('id');
        $parent_id = $this->input->post('parent_id');

        log_message('debug', sprintf(
            'Saving the resource name `%s`%s%s',
            $value,
            ($id === false ? '' : ' to the resource ' . $id),
            ($parent_id === false ? '' : ' under parent resource ' . $parent_id)
        ));

        // Validate the values we have been given
        if ($value === false || !is_string($value))
        {
            $this->ajax_error(sprintf(lang('access_invalid_value'), $value));
        }
        else if ($id !== false && ! is_numeric($id))
        {
            $this->ajax_error(sprintf(lang('access_invalid_id'), $id));
        }
        else if ($id === false && $parent_id === false)
        {
            $this->ajax_error(lang('access_invalid_resource_save_parameters'));
        }
        else if ($parent_id !== false && ! is_numeric($parent_id))
        {
            $this->ajax_error(sprintf(lang('access_invalid_id'), $parent_id));
        }

        // TODO: Perform strict validation again

        $this->load->model('resource_model');

        try
        {
            if($id !== false)
            {
                if ( ! $this->resource_model->is_locked($id))
                {
                    // Update a resource
                    $this->resource_model->update($id, $value);
                }
                else
                {
                    $this->ajax_error(lang('access_unable_to_modify_locked_item'));
                }
            }
            else
            {
                // Create a new resource
                $this->resource_model->insert($value, $parent_id);
            }
        }
        catch (Exception $ex)
        {
            $this->ajax_error(lang('access_unable_to_save_resource'));
        }
    }

    /**
     * Save an Action
     * 
     * @return void
     */
    public function save_action()
    {
        $this->block_none_ajax();

        $value = $this->input->post('value');
        $id = $this->input->post('id');
        $resource_id = $this->input->post('resource_id');

        log_message('debug:backendpro', sprintf(
            'Saving the action name `%s`%s%s',
            $value,
            ($id === false ? '' : ' to the action ' . $id),
            ($resource_id === false ? '' : ' under parent resource ' . $resource_id)
        ));
        
        // Validate the values we have been given
        if ($value === false || !is_string($value))
        {
            $this->ajax_error(sprintf(lang('access_invalid_value'), $value));
        }
        else if ($id !== false && ! is_numeric($id))
        {
            $this->ajax_error(sprintf(lang('access_invalid_id'), $id));
        }
        else if ($id === false && $resource_id === false)
        {
            $this->ajax_error(lang('access_invalid_action_save_parameters'));
        }
        else if ($resource_id !== false && ! is_numeric($resource_id))
        {
            $this->ajax_error(sprintf(lang('access_invalid_id'), $resource_id));
        }

        // TODO: Rerun strict validation

        $this->load->model('action_model');

        try
        {
            if($id !== false)
            {
                if ( ! $this->action_model->is_locked($id))
                {
                    // Update an existing action
                    $this->action_model->update($id, $value);
                }
                else
                {
                    $this->ajax_error(lang('access_unable_to_modify_locked_item'));
                }
            }
            else
            {
                // Create a new action
                $this->action_model->insert($value, $resource_id);
            }
        }
        catch (Exception $ex)
        {
            $this->ajax_error(lang('access_unable_to_save_action'));
        }
    }

    /**
     * Delete the selected group/resource/action from the DB
     *
     * @return void
     */
    public function delete_item()
    {
        $this->block_none_ajax();

        $section = $this->input->post('section');
        $id = $this->input->post('id');
        log_message('debug:backendpro', sprintf('Deleting the %s with id `%s`', $section, $id));

        if ($id === false || ! is_numeric($id))
        {
            $this->ajax_error(sprintf(lang('access_invalid_id'), $id));
        }
        else if ( ! in_array($section, $this->allowed_sections))
        {
            $this->ajax_error(sprintf(lang('access_unknown_section'), $section));
        }
        
        try
        {
            switch($section)
            {
                case 'group':
                    $this->load->model('group_model');

                    // Make sure the group isn't locked
                    if ( ! $this->group_model->is_locked($id))
                    {
                        $this->group_model->delete($id);
                    }
                    else
                    {
                        $this->ajax_error(lang('access_unable_to_modify_locked_item'));
                    }
                    break;

                case 'resource':
                    $this->load->model('resource_model');

                    // Make sure the resource isn't locked
                    if ( ! $this->resource_model->is_locked($id))
                    {
                        $this->resource_model->delete($id);
                    }
                    else
                    {
                        $this->ajax_error(lang('access_unable_to_modify_locked_item'));
                    }
                    break;

                case 'action':
                    $this->load->model('action_model');

                    // Make sure the action isn't locked
                    if ( ! $this->action_model->is_locked($id))
                    {
                        $this->action_model->delete($id);
                    }
                    else
                    {
                        $this->ajax_error(lang('access_unable_to_modify_locked_item'));
                    }
                    break;
            }
        }
        catch (Exception $ex)
        {
            $this->ajax_error(sprintf(lang('access_unable_to_delete_item'), $section));
        }
    }


    /**
     * Validate the details given for a group.
     * This is an AJAX operation
     *
     * @return void
     */
    public function validate_group()
    {
        $this->validate_item($this->input->post('value'), 'group');
    }

    /**
     * Validate the details given for a resource
     * This is an AJAX operation
     *
     * @return void
     */
    public function validate_resource()
    {
        $this->validate_item($this->input->post('value'), 'resource');
    }

    /**
     * Validate the details given for an action
     * This is an AJAX operation
     *
     * @return void
     */
    public function validate_action()
    {
        $this->validate_item($this->input->post('value'), 'action');
    }

    /**
     * A generic validator to validate a single value for
     * different permission sections
     *
     * @param string $value The value to validate
     * @param string $section The section either group/resource/action
     * @return void
     */
    private function validate_item($value, $section)
    {
        $this->block_none_ajax();

        try
        {
            $result = $this->perform_validation($value, $section);
        }
        catch (Exception $ex)
        {
            $this->ajax_error(lang('access_failed_to_validate'));
        }

        if ($result === true)
        {
            print 'valid';
        }
        else
        {
            log_message('debug:backendpro', sprintf('Validation failed because `%s`', $result));
            print $result;
        }
    }

    /**
     * A generic method which performs validation
     * @param  $name
     * @param  $section
     * @return bool|string
     */
    private function perform_validation($name, $section)
    {
        log_message('debug:backendpro', sprintf('Validating the %s name `%s`', ucfirst($section), $name));

        $this->load->library('form_validation');
        $this->load->model($section . '_model');

        $min_len = $this->config->item($section . '_name_min_length', 'access');
        $max_len = $this->config->item($section . '_name_max_length', 'access');

        if ( ! $this->form_validation->required($name))
        {
            return lang('access_validation_'.$section.'_name_required');
        }
        else if ( ! $this->form_validation->min_length($name, $min_len))
        {
            return sprintf(lang('access_validation_'.$section.'_name_min_length'), $min_len);
        }
        else if ( ! $this->form_validation->max_length($name, $max_len))
        {
            return sprintf(lang('access_validation_'.$section.'_name_max_length'), $max_len);
        }
        else if ( ! $this->{$section . '_model'}->is_unique($name))
        {
            return sprintf(lang('access_validation_'.$section.'_name_unique'), $name);
        }

        // All is good
        log_message('debug:backendpro', ucfirst($section) . ' name is valid');
        return true;
    }
}

/* End of file access_modify.php */
/* Location: ./application/backendpro_modules/access/controllers/access_modify.php */