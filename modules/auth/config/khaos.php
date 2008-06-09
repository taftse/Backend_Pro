<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * Khacl Config Array
     *
     * Contains config infomation for the ACL system
     *
     */

     $config['acl']['tables'] = array(
        'aros'           => 'be_acl_groups',
        'acos'           => 'be_acl_resources',
        'axos'           => 'be_acl_actions',
        'access'         => 'be_acl_permissions',
        'access_actions' => 'be_acl_permission_actions'
     ); 
?>