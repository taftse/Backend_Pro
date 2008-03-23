<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
    /**
     * BackendPro
     *
     * A website backend system for developers for PHP 4.3.2 or newer
     *
     * @package         BackendPro
     * @author          Adam Price
     * @copyright       Copyright (c) 2008
     * @license         http://www.gnu.org/licenses/lgpl.html
     * @link            http://backendpro.kaydoo.co.uk   
     */

     // ---------------------------------------------------------------------------

    /**
     * Settings
     *
     * Main website settings controller
     *
     * @package         BackendPro
     * @subpackage      Controllers
     */      
     class Settings extends Admin_Controller
     {
         /**
          * Constructor
          */
         function Settings()
         {
             // Call parent constructor
             parent::Admin_Controller();
             
             log_message('debug','Settings Class Initialized'); 
         }
         
         function index()
         {       
            $this->load->module_model('auth','access_control_model');
            // Setup the preference form
            $config['form_name'] = $this->lang->line('backendpro_settings');
            $config['form_link'] = 'admin/settings/index';
            
            // Setup preference groups
            $config['group'] = array(
                'general'     => array('name'=>'General Configuration', 'fields'=>'site_name,webmaster_name,webmaster_email'),
                'members'     => array('name'=>'Member Settings', 'fields'=>'allow_user_registration,activation_method,account_activation_time,autologin_period,default_user_group,allow_user_profiles'),  
                'security'    => array('name'=>'Security Preferences', 'fields'=>'use_login_captcha,use_registration_captcha,min_password_length'),  
                'email'       => array('name'=>'Email Configuration', 'fields'=>'automated_from_name,automated_from_email,email_protocol,email_mailpath,smtp_host,smtp_user,smtp_pass,smtp_port,smtp_timeout,email_mailtype,email_charset,email_wordwrap,email_wrapchars,bcc_batch_mode,bcc_batch_size'),  
                'maintenance' => array('name'=>'Maintenance & Debugging Settings', 'fields'=>'maintenance_mode,maintenance_message,page_debug,keep_error_logs_for'),  
            );
            
            // Setup custom field options
            $config['field']['site_name'] = array('rules'=>'trim|required');
            $config['field']['webmaster_name'] = array('rules'=>'trim|required');
            $config['field']['webmaster_email'] = array('rules'=>'trim|required|valid_email');
            
            $config['field']['allow_user_registration'] = array('type'=>'boolean');  
            $config['field']['activation_method'] = array('type'=>'dropdown','params'=>array('options'=>array('none'=>'No activation required','email'=>'Self activation by email','admin'=>'Manual activation by an administrator')));
            $config['field']['account_activation_time'] = array('rules'=>'trim|required|numeric'); 
            $config['field']['autologin_period'] = array('rules'=>'trim|required|numeric'); 
            $config['field']['default_user_group'] = array('type'=>'dropdown','params'=>array('options'=>$this->access_control_model->buildACLDropdown('group','id')));
            $config['field']['allow_user_profiles'] = array('type'=>'boolean');  
            
            $config['field']['use_login_captcha'] = array('type'=>'boolean');  
            $config['field']['use_registration_captcha'] = array('type'=>'boolean');  
            $config['field']['min_password_length'] = array('rules'=>'trim|required|numeric');  
            
            $config['field']['automated_from_email'] = array('rules'=>'trim|valid_email');
            $config['field']['email_protocol'] = array('type'=>'dropdown','params'=>array('options'=>array('sendmail'=>'Sendmail','mail'=>'PHP Mail','smtp'=>'SMTP')));
            $config['field']['smtp_port'] = array('rules'=>'trim|numeric');    
            $config['field']['smtp_timeout'] = array('rules'=>'trim|numeric');
            $config['field']['email_mailtype'] = array('type'=>'dropdown','params'=>array('options'=>array('text'=>'Plaintext','html'=>'HTML')));
            $config['field']['email_wordwrap'] = array('type'=>'boolean');
            $config['field']['email_wrapchars'] = array('rules'=>'trim|numeric');
            $config['field']['bcc_batch_mode'] = array('type'=>'boolean');
            $config['field']['bcc_batch_size'] = array('rules'=>'trim|numeric');    
               
            $config['field']['maintenance_mode'] = array('type'=>'boolean'); 
            $config['field']['maintenance_message'] = array('type'=>'textarea'); 
            $config['field']['page_debug'] = array('type'=>'boolean'); 
            $config['field']['keep_error_logs_for'] = array('rules'=>'trim|required|numeric'); 

            // Display the form
            $this->load->module_library('preferences','preference_form');
            $this->preference_form->initalize($config);
            $data['header'] = $this->preference_form->form_name;
            $data['content'] = $this->preference_form->display();             
            $this->load->view(Site_Controller::$_container,$data);
         }
     }
?>