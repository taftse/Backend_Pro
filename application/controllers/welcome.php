<?php

class Welcome extends Public_Controller {

	function Welcome()
	{
		parent::__construct();
	}
	
	function index()
	{
        $this->template->set_title('Welcome');
        $this->template->build('welcome_message');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */