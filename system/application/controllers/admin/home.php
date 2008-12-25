<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 4.3.2 or newer
 *
 * @package         BackendPro
 * @author          Adam Price
 * @copyright       Copyright (c) 2008
 * @license         http://www.gnu.org/licenses/lgpl.html
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

// ---------------------------------------------------------------------------

/**
 * Home
 *
 * @package         BackendPro
 * @subpackage      Controllers
 */
class Home extends Admin_Controller
{
	function Home()
	{
		parent::Admin_Controller();

		// Load dashboard language file
		$this->lang->load('dashboard');

		log_message('debug','BackendPro : Home class loaded');
	}

	function index()
	{
		// Include dashboard Javascript code
		$this->page->set_asset('admin','js','dashboard.js');

		// Load the dashboard library
		$this->load->library('dashboard');

		// Assign widgets to dashboard
		$this->dashboard->assign_widget(new widget($this->lang->line('dashboard_example'),$this->lang->line('dashboard_example_body')),'left');
		$this->dashboard->assign_widget(new widget($this->lang->line('dashboard_statistics'),$this->_widget_statistics()),'right');

		// Load dashboard onto page
		$data['dashboard'] = $this->dashboard->output();

		// Display Page
		$data['header'] = $this->lang->line('backendpro_dashboard');
		$data['page'] = $this->config->item('backendpro_template_admin') . "home";
		$this->load->view($this->_container,$data);
	}

	/**
	 * Generate Statistics Code
	 *
	 * Generate the contents of the statistics widget and return it as a string.
	 *
	 * @access private
	 * @return string
	 */
	function _widget_statistics()
	{
		$this->load->module_model('auth','user_model');

		// Get total number of members
		$query = $this->user_model->getUsers();
		$data['total_members'] = $query->num_rows();

		// Get total number of unactivated members
		$query = $this->user_model->getUsers(array('users.active'=>'0'));
		$data['total_unactivated_members'] = $query->num_rows();

		$data['system_status'] = ($this->preference->item('maintenance_mode')) ? '<font color="red">'.$this->lang->line('dashboard_statistics_offline').'</font>' : '<font color="green">'.$this->lang->line('dashboard_statistics_online').'</font>';
		$data['user_registration'] = ($this->preference->item('allow_user_registration')) ? '<font color="green">'.$this->lang->line('dashboard_statistics_online').'</font>' : '<font color="red">'.$this->lang->line('dashboard_statistics_offline').'</font>';

		return $this->load->view($this->config->item('backendpro_template_admin') . 'dashboard/statistics',$data,TRUE);
	}
}
/* End of file home.php */
/* Location: ./system/application/controllers/admin/home.php */