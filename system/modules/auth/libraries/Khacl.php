<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Khaos :: Khacl
 * 
 * @author      David Cole <neophyte@sourcetutor.com>
 * @version     0.1
 * @copyright   2008
 */

define('KH_ACL', true);
define('KH_ACL_VERSION', 0.1);

/**
 * KhACL
 * 
 */
class Khacl
{
	/**
	 * Access Request Object
	 *
	 * @var object
	 * @access public
	 */
	var $aro;
	
	/**
	 * Access Control Pbject
	 *
	 * @var object
	 * @access public
	 */
	var $aco;
	
	/**
	 * Access Extension Object
	 *
	 * @var object
	 * @access public
	 */
	var $axo;
	
	/**
	 * Codeigniter Super Object
	 *
	 * @var object
	 * @access private
	 */
	var $_CI;
	
	/**
	 * Enable Cache?
	 *
	 * @var bool
	 * @access private
	 */
	var $_Cache = false;
	
	/**
	 * KhACL Tables
	 *
	 * @var array
	 * @access private
	 */
	var $_Tables = array('aros'           => 'khacl_aros',
	                     'acos'           => 'khacl_acos',
	                     'axos'           => 'khacl_axos',
	                     'access'         => 'khacl_access',
	                     'access_actions' => 'khacl_access_actions');
	
	/**
	 * Constructor
	 *
	 * @return Khacl
	 */
    function Khacl()
    {
    	$this->_CI =& get_instance();
    	$this->_CI->config->load('khacl',false,true);    	
    	
    	// Is 'Khaos :: Stache' available ?
    	if (defined('KH_STACHE') && is_object($this->_CI->stache))
    		$this->_Cache = true;

    	// Grab ACL options
    	$options = $this->_CI->config->item('acl_tables');    	
        
        if (isset($options) && is_array($options))
    	    $this->_Tables = array_merge($this->_Tables, $options);
    	
    	// Instantiate the ARO, ACO and AXO objects
    	$this->aro = new KH_ACL_ARO($this->_CI, $this->_Tables, $this->_Cache);
    	$this->aco = new KH_ACL_ACO($this->_CI, $this->_Tables, $this->_Cache);
    	$this->axo = new KH_ACL_AXO($this->_CI, $this->_Tables, $this->_Cache); 
    }
    
    /**
     * Check Access
     *
     * @param mixed $aro
     * @param mixed $aco
     * @param mixed $axo
     * 
     * @return bool
     */
    function check($aro, $aco, $axo = null)
    {
        $result = $this->query($aro, $aco);
        
    	// ARO lacks any access - DENY
    	if ($result['access'] == 'N')
    		return false;
    	
    	if ($axo === null)
    	{
    	    /*
    	     * No AXO specified and we know the ARO has access to the ACO
    	     * from the above check. - ALLOW
    	     */
    	    
        	return true;    	    
    	}
    	else
    	{
    	    /*
    	     * AXO specified and we know the AROhas access to the ACO so now we just
    	     * have to make sure the user also has access to the AXO.
    	     */
    	    
        	if ((isset($result['extensions'][$axo])) && ($result['extensions'][$axo] == 'Y')) // All good - ALLOW
        		return true;  
            else // AXO is set to deny - DENY
                return false; 	    
    	}
    	
    	// DENY	
    	return false;	
    }
    
    /**
     * Allow Access
     *
     * Grants the ARO access to the AXO on the ACO, if no AXO is specified
     * then the user is simply granted access to the ACO.
     * 
     * @param mixed $aro
     * @param mixed $aco
     * @param mixed $axo
     * 
     * @return bool
     * @access public
     */
    function allow($aro, $aco, $axo = null)
    {
    	return $this->_set($aro, $aco, $axo, true);
    }
    
    /**
     * Deny Access
     *
     * Denies the ARO access to AXO on the ACO, if no AXO is specified
     * then the ARO is outright denied access to the ACO.
     * 
     * @param mixed $aro
     * @param mixed $aco
     * @param mixed $axo
     * 
     * @return bool
     * @access public
     */
    function deny($aro, $aco, $axo = null)
    {
    	return $this->_set($aro, $aco, $axo, false);
    }
    
    /**
     * Query ARO Access
     *
     * Determines if the ARO has access to the ACO along with
     * any extra AXOs.
     * 
     * Sample Response:
     * 
     *   Array
     *   (
     *       [access] => Y
     *       [extensions] => Array
     *           (
     *               [publish] => N
     *               [create] => Y
     *               [delete] => N
     *           )
     *   )
     * 
     * @param mixed $aro
     * @param mixed $aco
     * 
     * @return array
     */
    function query($aro, $aco)
    {    	
    	// By default we deny everything
    	$result = array('access' => 'N', 'extensions' => array());

    	/*
    	 * Retrieve the data needed to determine the AROs access
    	 * and extensions to the specified ACO.
    	 */
    	
		if (($aro_tree = $this->aro->branch($aro)) === false) // No ARO by this name
		    return $result;
		    
		if (($aco_tree = $this->aco->branch($aco)) === false) // No ACO by this name
		    return $result;
		
		if (($acl_map = $this->map($aro_tree, $aco_tree)) === false) // No access map between the ARO/ACO
		    return $result;
		
		/*
		 * Iterate over the ARO and ACO trees from the top most parent
		 * down allowing the set access and extensions to cascade down till
		 * we have the final result.
		 */
		
		foreach ($aro_tree as $aro)
		{
		    foreach($aco_tree as $aco)
		    {
				foreach ($acl_map as $access)
				{
					if (($access['aro_id'] == $aro->id) && ($access['aco_id'] == $aco->id))
					{
						$result['access']     = $access['allow'];
						$result['extensions'] = array_merge($result['extensions'], $access['extensions']);
					}
				}
		    }
		}

		return $result;	
    }
    
    /**
     * ACL Access Map
     *
     * Retrieves the full access map of
     * the ARO to the ACO.
     * 
     * @param array $aro  ARO Tree
     * @param array $aco  ACO Tree
     * 
     * @return array
     * @access public
     */
    function map($aro_tree, $aco_tree)
    {        
        $map        = array(); // Holds final access map
        $interested = array(); // Holds records for which we are interested in

        // Build the array of aro/aco pairs were interested in       
        foreach ($aro_tree as $aro)
            foreach ($aco_tree as $aco)
                $interested[] = '('.$this->_Tables['access'].'.aro_id = '.$aro->id.' AND '.$this->_Tables['access'].'.aco_id = '.$aco->id.')';

        if (count($interested) == 0) // No point continuing if were not interested in anything
            return false;
                        
        /*
         * This is where the real work starts, build the query and
         * generate the access map array.
         */        

        $rs = $this->_CI->db->query('SELECT *
                                       FROM '.$this->_Tables['access'].'
                                       WHERE '.implode(' OR ', $interested));
        
        foreach ($rs->result() as $row)
        {
        	// Check for any AXO
        	$rs_axo = $this->_CI->db->query('SELECT '.$this->_Tables['access_actions'].'.allow,
        	                                        '.$this->_Tables['axos'].'.name
        	                                   FROM '.$this->_Tables['access_actions'].'
        	                                     LEFT JOIN '.$this->_Tables['axos'].' ON '.$this->_Tables['access_actions'].'.axo_id = '.$this->_Tables['axos'].'.id
        	                                   WHERE '.$this->_Tables['access_actions'].'.access_id = '.$row->id);
        	
        	// If there are any AXO build the extensions array
        	if ($rs_axo->num_rows(0) > 0)
        	{
        		foreach ($rs_axo->result() as $axo_map)
        			$extensions[$axo_map->name] = $axo_map->allow;
        	}
        	else 
        		$extensions = array();
        	
        	// Add this access to the maps array
        	$map[] = array('aro_id'     => $row->aro_id,
        	               'aco_id'     => $row->aco_id,
        	               'allow'      => $row->allow,
        	               'extensions' => $extensions);
        	
        }
               
        return $map;         
    }
    
    /**
     * Set Permissions
     *
     * @param mixed $aro
     * @param mixed $aco
     * @param mixed $axo
     * @param mixed $allow
     * 
     * @return bool
     * @access private
     */
    function _set($aro, $aco, $axo = null, $allow = true)
    {
        $allow = ($allow)?'Y':'N';
        
		// Grab the id of the ARO
		if (($rs = $this->_CI->db->query('SELECT id FROM '.$this->_Tables['aros'].' WHERE name = ? LIMIT 1', array($aro))) !== false)
		{
		    if ($rs->num_rows() == 1)
		    {
		        $row    = $rs->row();
		        $aro_id = $row->id;
		    }
		    else 
		        return false;
		}
		else 
		    return false;
		
		// Grab the id of the ACO
		if (($rs = $this->_CI->db->query('SELECT id FROM '.$this->_Tables['acos'].' WHERE name = ? LIMIT 1', array($aco))) !== false)
		{
		    if ($rs->num_rows() == 1)
		    {
		        $row    = $rs->row();
		        $aco_id = $row->id;
		    }
		    else 
		        return false;
		}
		else 
		    return false;		
		
		// Grab the id of the AXO
		if ($axo !== null)
		{
    		if (($rs = $this->_CI->db->query('SELECT id FROM '.$this->_Tables['axos'].' WHERE name = ? LIMIT 1', array($axo))) !== false)
    		{
    		    if ($rs->num_rows() == 1)
    		    {
    		        $row    = $rs->row();
    		        $axo_id = $row->id;
    		    }
    		    else 
    		        return false;
    		}
    		else 
    		    return false;		
		}

		/*
		 * If needed create/modify the ARO -> ACO map in the access table
		 */
		
		if (($rs = $this->_CI->db->query('SELECT id, allow FROM '.$this->_Tables['access'].' WHERE aro_id = ? AND aco_id = ? LIMIT 1', array($aro_id, $aco_id))) !== false)
		{
		    if ($rs->num_rows() === 0) // Create new link
		    {
		    	if ($axo === null) // No AXO so set the ARO -> ACO access to whatever is set by $allow
		    	{
	                if (!$this->_CI->db->query('INSERT INTO '.$this->_Tables['access'].' (aro_id, aco_id, allow) VALUES (?, ?, ?)', array($aro_id, $aco_id, $allow)))
	                    return false;
		    	}
		    	else // AXO set so make the ARO -> ACO access to allowed as the ALLOW/DENY will be determined by the AXO later on
		    	{
	                if (!$this->_CI->db->query('INSERT INTO '.$this->_Tables['access'].' (aro_id, aco_id, allow) VALUES (?, ?, \'Y\')', array($aro_id, $aco_id)))
	                    return false;		    		
		    	}
                    
                $access_id = $this->_CI->db->insert_id();
		    }
		    else // Modify existing link if needed
		    {
                $row       = $rs->row();
                $access_id = $row->id;
                
                if ($axo === null) // No AXO so update the ARO -> ACO access to whatever is specified by $allow
                {
                    if ($row->allow != $allow)
                        if (!$this->_CI->db->query('UPDATE '.$this->_Tables['access'].' SET allow = ? WHERE id = ?', array($allow, $access_id)))
                            return false;
                }
                else // AXO specified so we set the ARO -> ACO access to allowed as the ALLOW/DENY willbe determined by the AXO later on
                {
                    if (!$this->_CI->db->query('UPDATE '.$this->_Tables['access'].' SET allow = \'Y\' WHERE id = ?', array($access_id)))
                        return false;                	
                }
		    }
		}
		else 
		    return false;
		
		/*
		 * If needed create/modify the access -> action link in the access_actions table
		 */
		
		if ($axo !== null)
		{
    		if (($rs = $this->_CI->db->query('SELECT id, allow FROM '.$this->_Tables['access_actions'].' WHERE access_id = ? AND axo_id = ? LIMIT 1', array($access_id, $axo_id))) !== false)
    		{
    		    if ($rs->num_rows() === 0) // create link
    		    {
    		        if (!$this->_CI->db->query('INSERT INTO '.$this->_Tables['access_actions'].' (access_id, axo_id, allow) VALUES (?, ?, ?)', array($access_id, $axo_id, $allow)))
    		            return false;
    		    }
    		    else // Modify existing link 
    		    {
    		        $row = $rs->row();
    		        
    		        if ($row->allow != $allow)
    		            if (!$this->_CI->db->query('UPDATE '.$this->_Tables['access_actions'].' SET allow = ? WHERE id = ?', array($allow, $row->id)))
    		                return false;
    		    }
    		    
    		    return true;
    		}
    		else 
    		    return false;
		}
		else 
		    return true;
    }    
}

/**
 * ARO List
 *
 */
class KH_ACL_ARO
{
	/**
	 * KhACL Tables
	 *
	 * @var array
	 * @access private
	 */
	var $_Tables = array();
	
	/**
	 * Codeigniter super object
	 *
	 * @var object
	 * @access private
	 */
	var $_CI;
	
	/**
	 * Cache Enabled?
	 *
	 * @var bool
	 * @access private
	 */
	var $_Cache;
	
	/**
	 * Constructor
	 *
	 * @param object $ci
	 * @param array  $config
	 * @param bool   $cache
	 * 
	 * @return KH_ACL_ARO
	 */
	function KH_ACL_ARO(&$ci, $tables, $cache)
	{		
		$this->_CI     = &$ci;
		$this->_Cache  = $cache;
        $this->_Tables = $tables;
	}
	
	/**
	 * Create ARO
	 *
	 * @param string $aro
	 * @param string $parent
	 * @param int    $link
	 * 
	 * @return bool
	 * @access public
	 */
	function create($aro, $parent = null, $link = null)
	{
	    /*
	     * Ensure there is no other ARO by this name in the
	     * database.
	     */
	                                        
	    $rs = $this->_CI->db->query('SELECT id FROM '.$this->_Tables['aros'].' WHERE name = ?', array($aro));
	    
	    if ($rs->num_rows() === 0)
	    {
	        $link = is_numeric($link)?$link:'NULL';
	        
    	    if ($parent === null)
    	    {
    	        /*
    	         * If no parent is set then we can add the ARO
    	         * to the end of the tree so as few records as possible
    	         * are updated.
    	         */
    	        
    	        // Get the right most value of the tree
    	        $rs = $this->_CI->db->query('SELECT rgt FROM '.$this->_Tables['aros'].' ORDER BY rgt DESC LIMIT 1');
    	        
    	        if ($rs->num_rows() === 0) // Tree is empty
    	           $right = 0;
    	        else 
    	        {
    	           $row   = $rs->row();
    	           $right = $row->rgt;
    	        }
    	        
    	        // Insert the record
    	        $this->_CI->db->query('INSERT INTO '.$this->_Tables['aros'].' (lft, rgt, name, link) VALUES ('.($right + 1).', '.($right + 2).', '.$this->_CI->db->escape($aro).', '.$link.')');
    	    }
    	    else 
    	    {
    	        /*
    	         * Parent is specified so we have to update all records
    	         * which are futher down the tree than the parent.
    	         */
    	        
    	        // Grab the left value of the specified parent
    	        $rs = $this->_CI->db->query('SELECT lft FROM '.$this->_Tables['aros'].' WHERE name = ? LIMIT 1', array($parent));
    	        
    	        if ($rs->num_rows() === 0) // We cant do much if we cant find the parent
    	            return false;
    	        else 
    	        {
    	            $row  = $rs->row();
    	            $left = $row->lft;
    	        }
    	        
    	        // Update all records past the left point by 2 to make room for the new ARO
    	        $this->_CI->db->query('UPDATE '.$this->_Tables['aros'].' SET rgt = rgt + 2 WHERE rgt > '.$left);
    	        $this->_CI->db->query('UPDATE '.$this->_Tables['aros'].' SET lft = lft + 2 WHERE lft > '.$left);
    	        
    	        // Insert the record
                $this->_CI->db->query('INSERT INTO '.$this->_Tables['aros'].' (lft, rgt, name, link) VALUES ('.($left + 1).', '.($left + 2).', '.$this->_CI->db->escape($aro).', '.$link.')');    	        
    	    }
    	    
    	    return true;
	    }
	    else 
	       return false;
	}
	
	/**
	 * Retrieve ARO Branch
	 *
	 * @param string $aro
	 * 
	 * @return array
	 * @access public
	 */
	function branch($aro)
	{
	    $rs = $this->_CI->db->query('SELECT tree.id,
	                                        tree.name,
	                                        tree.link
	                                   FROM '.$this->_Tables['aros'].' AS node,
	                                        '.$this->_Tables['aros'].' AS tree
	                                   WHERE node.lft BETWEEN tree.lft AND tree.rgt
	                                     AND node.name = ?
	                                   ORDER BY tree.lft ASC', array($aro));
	    
	    return $rs->result();
	}
	
	/**
	 * Delete ARO
	 *
	 * @param string $aro
	 * 
	 * @return bool
	 * @access public
	 */
	function delete($aro)
	{
	    // Grab the ARO branch details
	    if (!($rs = $this->_CI->db->query('SELECT id, lft, rgt FROM '.$this->_Tables['aros'].' WHERE name = ?', array($aro))))
	        return false;
	        
	    if ($rs->num_rows === 0)
	        return false;

	    /*
	     * Delete the ARO
	     */
	    
        $row   = $rs->row();
        $left  = $row->lft;
        $right = $row->rgt;
        $width = ($right - $left) + 1;
        
        $rs = $this->_CI->db->query('DELETE '.$this->_Tables['aros'].'
                                       FROM '.$this->_Tables['aros'].'
                                       LEFT JOIN '.$this->_Tables['access'].' ON '.$this->_Tables['aros'].'.id = '.$this->_Tables['access'].'.aro_id
                                       LEFT JOIN '.$this->_Tables['access_actions'].' ON '.$this->_Tables['access'].'.id = '.$this->_Tables['access_actions'].'.access_id
                                       WHERE '.$this->_Tables['aros'].'.lft BETWEEN '.$left.' AND '.$right);
    
        if (!$rs)
            return false;
            
        $this->_CI->db->query('UPDATE '.$this->_Tables['aros'].' SET rgt = rgt - '.$width.' WHERE rgt > '.$right);
        $this->_CI->db->query('UPDATE '.$this->_Tables['aros'].' SET lft = lft - '.$width.' WHERE lft > '.$right);
        
        return true;
	}
}

/**
 * ACO List
 *
 */
class KH_ACL_ACO
{
	/**
	 * KhACL Tables
	 *
	 * @var array
	 * @access private
	 */
	var $_Tables = array();
	
	/**
	 * Codeigniter super object
	 *
	 * @var object
	 * @access private
	 */
	var $_CI;
	
	/**
	 * Cache Enabled?
	 *
	 * @var bool
	 * @access private
	 */
	var $_Cache;
	
	/**
	 * Constructor
	 *
	 * @param object $ci
	 * @param array  $config
	 * @param bool   $cache
	 * 
	 * @return KH_ACL_ACO
	 */
	function KH_ACL_ACO(&$ci, $tables, $cache)
	{
		$this->_CI     = &$ci;
		$this->_Cache  = $cache;
        $this->_Tables = $tables;
	}
	
	/**
	 * Create ACO
	 *
	 * @param string $aco
	 * @param string $parent
	 * @param int    $link
	 * 
	 * @return bool
	 * @access public
	 */
	function create($aco, $parent = null, $link = null)
	{
	    /*
	     * Ensure there is no other ARO by this name in the
	     * database.
	     */
	    
	    $rs = $this->_CI->db->query('SELECT id FROM '.$this->_Tables['acos'].' WHERE name = ?', array($aco));
	    
	    if ($rs->num_rows() === 0)
	    {
	        $link = is_numeric($link)?$link:'NULL';
	        
    	    if ( is_null($parent))
    	    {
    	        /*
    	         * If no parent is set then we can add the ARO
    	         * to the end of the tree so as few records as possible
    	         * are updated.
    	         */
    	        
    	        // Get the right most value of the tree
    	        $rs = $this->_CI->db->query('SELECT rgt FROM '.$this->_Tables['acos'].' ORDER BY rgt DESC LIMIT 1');
    	        
    	        if ($rs->num_rows() === 0) // Tree is empty
    	           $right = 0;
    	        else 
    	        {
    	           $row   = $rs->row();
    	           $right = $row->rgt;
    	        }
    	        
    	        // Insert the record
    	        return $this->_CI->db->query('INSERT INTO '.$this->_Tables['acos'].' (lft, rgt, name, link) VALUES ('.($right + 1).', '.($right + 2).', '.$this->_CI->db->escape($aco).', '.$link.')');
    	    }
    	    else 
    	    {
    	        /*
    	         * Parent is specified so we have to update all records
    	         * which are futher down the tree than the parent.
    	         */
    	        
    	        // Grab the left value of the specified parent
    	        $rs = $this->_CI->db->query('SELECT lft FROM '.$this->_Tables['acos'].' WHERE name = ? LIMIT 1', array($parent));
    	        
    	        if ($rs->num_rows() === 0) // We cant do much if we cant find the parent
    	            return false;
    	        else 
    	        {
    	            $row  = $rs->row();
    	            $left = $row->lft;
    	        }
    	        
                $this->_CI->db->trans_start();
                
    	        // Update all records past the left point by 2 to make room for the new ARO
    	        $this->_CI->db->query('UPDATE '.$this->_Tables['acos'].' SET rgt = rgt + 2 WHERE rgt > '.$left);
    	        $this->_CI->db->query('UPDATE '.$this->_Tables['acos'].' SET lft = lft + 2 WHERE lft > '.$left);
    	        
    	        // Insert the record
                $this->_CI->db->query('INSERT INTO '.$this->_Tables['acos'].' (lft, rgt, name, link) VALUES ('.($left + 1).', '.($left + 2).', '.$this->_CI->db->escape($aco).', '.$link.')');    	        
    	    
                if($this->_CI->db->trans_status() === TRUE)
                {
                    $this->_CI->db->trans_commit();
                    return true;
                }
                else
                {
                    $this->_CI->db->trans_rollback();
                    return false;
                }            
            }
	    }
        return false;
	}	
	
	/**
	 * Retrieve ACO Branch
	 *
	 * @param string $aco
	 * 
	 * @return array
	 * @access public
	 */
	function branch($aco)
	{
	    $rs = $this->_CI->db->query('SELECT tree.id,
	                                        tree.name,
	                                        tree.link
	                                   FROM '.$this->_Tables['acos'].' AS node,
	                                        '.$this->_Tables['acos'].' AS tree
	                                   WHERE node.lft BETWEEN tree.lft AND tree.rgt
	                                     AND node.name = ?
	                                   ORDER BY tree.lft ASC', array($aco));
	    
	    return $rs->result();
	}	
	
	/**
	 * Delete ACO
	 *
	 * @param string $aro
	 * 
	 * @return bool
	 * @access public
	 */
	function delete($aco)
	{
	    // Grab the ACO branch details
	    if (!($rs = $this->_CI->db->query('SELECT id, lft, rgt FROM '.$this->_Tables['acos'].' WHERE name = ?', array($aco))))
	        return false;
	        
	    if ($rs->num_rows === 0)
	        return false;

	    /*
	     * Delete the ACO
	     */
	    
        $row   = $rs->row();
        $left  = $row->lft;
        $right = $row->rgt;
        $width = ($right - $left) + 1;

        /** Start Code Change */
        $this->_CI->db->trans_start();
        
        $this->_CI->db->delete($this->_Tables['acos'],'lft BETWEEN '.$left.' AND '.$right);        
        $this->_CI->db->query('UPDATE '.$this->_Tables['acos'].' SET rgt = rgt - '.$width.' WHERE rgt > '.$right);
        $this->_CI->db->query('UPDATE '.$this->_Tables['acos'].' SET lft = lft - '.$width.' WHERE lft > '.$right);
        
        if($this->_CI->db->trans_status() === true)
        {
            $this->_CI->db->trans_commit();
            return true;
        }
        else
        {
            $this->_CI->db->trans_rollback();
            return false;
        }
        /** End Code Change */
        
        /*$rs = $this->_CI->db->query('DELETE '.$this->_Tables['acos'].'
                                       FROM '.$this->_Tables['acos'].'
                                       LEFT JOIN '.$this->_Tables['access'].' ON '.$this->_Tables['acos'].'.id = '.$this->_Tables['access'].'.aco_id
                                       LEFT JOIN '.$this->_Tables['access_actions'].' ON '.$this->_Tables['access'].'.id = '.$this->_Tables['access_actions'].'.access_id
                                       WHERE '.$this->_Tables['acos'].'.lft BETWEEN '.$left.' AND '.$right);
        
        if (!$rs)
            return false;
            
        $this->_CI->db->query('UPDATE '.$this->_Tables['acos'].' SET rgt = rgt - '.$width.' WHERE rgt > '.$right);
        $this->_CI->db->query('UPDATE '.$this->_Tables['acos'].' SET lft = lft - '.$width.' WHERE lft > '.$right);
        
        return true; */
	}	
}

/**
 * AXO List
 *
 */
class KH_ACL_AXO
{
	/**
	 * ARO Table
	 *
	 * @var array
	 * @access private
	 */
	var $_Tables = array();
	
	/**
	 * Codeigniter super object
	 *
	 * @var object
	 * @access private
	 */
	var $_CI;
	
	/**
	 * Cache Enabled?
	 *
	 * @var bool
	 * @access private
	 */
	var $_Cache;
	
	/**
	 * Constructor
	 *
	 * @param object $ci
	 * @param array  $config
	 * @param bool   $cache
	 * 
	 * @return KH_ACL_AXO
	 */
	function KH_ACL_AXO(&$ci, $tables, $cache)
	{
		$this->_CI     = &$ci;
		$this->_Cache  = $cache;
        $this->_Tables = $tables;
	}
	
	/**
	 * Create AXO
	 *
	 * @param string $axo
	 * 
	 * @return bool
	 * @access public
	 */
	function create($axo)
	{
	    /*
	     * Ensure there is no other AXO
	     * in the database by this name
	     */
	    
	    $rs = $this->_CI->db->query('SELECT id FROM '.$this->_Tables['axos'].' WHERE name = ?', array($axo));
	    
	    if ($rs->num_rows() === 0)
	    {
	        // Insert new AXO
            /** Added return on line beliw */
	        return $this->_CI->db->query('INSERT INTO '.$this->_Tables['axos'].' (name) VALUES ('.$this->_CI->db->escape($axo).')');
	        //return true;
	    }
	    //else 
	    return false;	    
	}
	
	/**
	 * Delete AXO
	 *
	 * @param string $axo
	 * 
	 * @return bool
	 * @access public
	 */
	function delete($axo)
	{
	    // grab the axo_id so we can delete the access -> action links later on
	    if (!($rs = $this->_CI->db->query('SELECT id FROM '.$this->_Tables['axos'].' WHERE name = ? LIMIT 1', array($axo))))
	        return false;
	    
	    if ($rs->num_rows() === 0)
	        return false;
	    else 
	    {
	        $row    = $rs->row();
	        $axo_id = $row->id;
	    }
	            
	    // delete acces->action links
	    //$this->_CI->db->query('DELETE FROM '.$this->_Tables['access_actions'].' WHERE axo_id = ?', array($axo_id));
	    
	    // delete the AXO
	    return $this->_CI->db->query('DELETE FROM '.$this->_Tables['axos'].' WHERE id = ? LIMIT 1', array($axo_id));
	}
}

?>