<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * Khacl Config Array
     *
     * Contains config infomation for the ACL system
     *
     */

     $config['acl_tables'] = array(
        'aros'           => 'be_groupacl_aros',
        'acos'           => 'be_groupacl_acos',
        'axos'           => 'be_groupacl_axos',
        'access'         => 'be_groupacl_access',
        'access_actions' => 'be_groupacl_access_actions'
     ); 
?>