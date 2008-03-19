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
     * @tutorial        BackendPro.pkg
     */

     // ---------------------------------------------------------------------------

    /**
     * Access Control Model
     *
     * Provides functionaly to interact with the access control tables
     * in the database
     *
     * @package         BackendPro
     * @subpackage      Models
     */
    include_once(APPPATH.'models/Nested_sets_model.php');
    class access_control_model extends Base_Model
    {
        var $resource;
        var $group;
        
        /**
         * Constructor
         */
        function access_control_model()
        {
            // Inherit from parent class
            parent::Model();
            
            // Setup allowed tables
            $this->load->config('khacl');
            $this->_TABLES = $this->config->item('acl_tables');
            $this->_TABLES['groups'] = $this->config->item('backendpro_table_prefix')."groups";  
            $this->_TABLES['resources'] = $this->config->item('backendpro_table_prefix')."resources";  
            
            // Setup ACO Model
            $this->resource = new Nested_sets_model();
            $this->resource->setControlParams($this->_TABLES['acos']);
            $this->resource->setPrimaryKeyColumn('id');
            
            // Setup ARO Model
            $this->group = new Nested_sets_model();
            $this->group->setControlParams($this->_TABLES['aros']);
            $this->group->setPrimaryKeyColumn('id');

            log_message('debug','access_control_model Class Initialized');
        }
        
        /**
         * Get Permissions
         * 
         * This is used to display the table of all the permissions
         * 
         * @access public
         * @param
         * @return array 
         */
        function getPermissions($limit=NULL,$where=NULL)
        {
            // Run Query            
            $this->db->select("acl.id, acl.allow, aros.name AS aro, acos.name AS aco, axos.name AS axo, actions.allow AS axo_allow");
            $this->db->from($this->_TABLES['access'].' AS acl');
            $this->db->join($this->_TABLES['access_actions'].' AS actions', 'acl.id=actions.access_id', 'left');
            $this->db->join($this->_TABLES['aros'].' AS aros','acl.aro_id=aros.id'); 
            $this->db->join($this->_TABLES['acos'].' AS acos','acl.aco_id=acos.id'); 
            $this->db->join($this->_TABLES['axos'].' AS axos','actions.axo_id=axos.id','left');      
            $this->db->order_by('aro, aco, axo');           
            if($where != NULL){$this->db->where($where);}
            if(is_array($limit)){$this->db->limit($limit['limit'],$limit['offset']);}
            $query = $this->db->get();
            
            $data = array();
            
            foreach($query->result_array() as $row)
            {
                $id = $row['id'];
                $data[$id]['aro'] = $row['aro']; 
                $data[$id]['aco'] = $row['aco'];
                $data[$id]['allow'] = ($row['allow']=='Y') ? TRUE : FALSE;     
                
                if($row['axo']!=NULL){
                    $allow = ($row['axo_allow']=='Y') ? TRUE : FALSE;
                    $data[$id]['actions'][] = array('allow'=>$allow,'axo'=>$row['axo']);
                }
            }
            return $data;
        }
        
        /**
         * Custom Function to remove all group details
         * 
         * @access private
         * @param mixed $where Delete group where 
         */
        function _delete_groups($where)
        {
            // Remove group
            if ( !$this->khacl->aro->delete($where['name']))
                return FALSE;

            // Remove extra group infomation
            return $this->db->delete($this->_TABLES['groups'],array('id'=>$where['id']));
        }
        
        
        
        
        
        
        
        
        
        
        
        function buildActionSelector()
        {
            $value = '';
            $query = $this->fetch('axos');
            foreach($query->result() as $action)
            {
                $checkbox = 'action_'.$action->name;
                $radio = 'allow_'.$action->name;   
                $value .= form_checkbox($checkbox,$action->name,$this->validation->set_checkbox($checkbox,$action->name));
                $value .= $action->name . "<br>\n";
                
                $value .= '<div id="'.$radio.'" class="action_item">';
                $value .= form_radio($radio,'Y',$this->validation->set_radio($radio,'Y')) . $this->lang->line('access_allow');
                $value .= form_radio($radio,'N',$this->validation->set_radio($radio,'N')) . $this->lang->line('access_deny') . '</div>';
            }
            return $value;
        }
        
        function buildResourceSelector($disabled=FALSE)
        {
            return $this->_buildSelector($disabled,'aco'); 
        }
        
        function buildGroupSelector($disabled=FALSE)
        {
            return $this->_buildSelector($disabled,'aro');
        }
        
        function _buildSelector($disabled,$type)
        {
            $this->load->library('validation');
            
            // Value to return
            $value = '';
            
            // Set the table type
            switch($type){
                case 'aco':$obj = & $this->resource;break;
                case 'aro':$obj = & $this->group;break;
            }
            
            $disabled = ($disabled) ? ' disabled' : ''; 
            
            // Create Selector
            $tree = $obj->getTreePreorder($obj->getRoot());
            $lvl = 0;
            while($obj->getTreeNext($tree)):
                // Nest the tree
                $newLvl = $obj->getTreeLevel($tree);
                if ($lvl > $newLvl){
                    // Just gone up some levels
                    for($i=0;$i<$lvl-$newLvl;$i++) 
                        $value .= "</ul></li>";
                }
                $lvl = $newLvl;
                
                $set = $this->validation->set_radio($type,$tree['row']['name']);
                
                // If no node is set and this is the root, set it
                if($set == NULL AND $obj->checkNodeIsRoot($tree['row']))
                    $set = ' checked';
                
                $open = ($set!=NULL) ? ' class="open"' : '';
                $value .='<li id="'.$tree['row']['id'].'"'.$open.'>'.form_radio($type,$tree['row']['name'],$set,$disabled).'<span>'.$tree['row']['name'].'</span>';   

                if($obj->checkNodeHasChildren($tree['row']))
                    $value .= "<ul>";
                else
                    $value .= "</li>";
            endwhile;
            
            // Close Tree
            for($i=0;$i<$lvl;$i++)
                $value .= "</ul></li>";
                
            return $value;
        }
    }
?>