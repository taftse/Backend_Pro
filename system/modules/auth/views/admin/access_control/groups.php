<h2><?=$header?></h2>

<a href="<?=site_url('auth/admin/acl_groups/form')?>" class="icon_add"><?=$this->lang->line('access_create_group')?></a>

<!-- VIEW GROUPS -->
<?=form_open('auth/admin/acl_groups/delete')?> 
<table class="data_grid">
<thead>
    <tr>
        <th width=5%><?=$this->lang->line('general_id')?></th>
        <th><?=$this->lang->line('access_groups')?></th>
        <th width=10% class="middle"><?=$this->lang->line('access_disabled')?></th>  
        <th width=10% class="middle"><?=$this->lang->line('general_edit')?></th>  
        <th width=10%><?=form_checkbox('all','select',FALSE)?> <?=$this->lang->line('general_delete')?></th>
    </tr>
</thead>
<tfoot>
    <tr>
        <td colspan=4>&nbsp;</td>
        <td><?=form_submit('delete',$this->lang->line('general_delete'),'onClick="return confirm(\''.$this->lang->line('access_delete_groups_confirm').'\');"')?></td>
    </tr>
</tfoot>
<tbody>
    <?php 
    // Output nested resource view
    $resourceObj = & $this->access_control_model->group;
    $tree = $resourceObj->getTreePreorder($resourceObj->getRoot());
    
    while($resourceObj->getTreeNext($tree)):        
        $lvl = $resourceObj->getTreeLevel($tree);
        $offset = '';
        
        // Nest the tree
        if($lvl > 1){
            $ancestor = $resourceObj->getAncestor($tree['row']);
            while( ! $resourceObj->checkNodeIsRoot($ancestor))
            {
                if($resourceObj->checkNodeHasNextSibling($ancestor)):
                    // Ancestor has sibling so put a | in offset
                    $offset = "|&nbsp;&nbsp; " . $offset;
                else:
                    // No next sibling just put space
                    $offset = "&nbsp;&nbsp;&nbsp; " . $offset;   
                endif; 
                $ancestor = $resourceObj->getAncestor($ancestor);                            
            }
        }                
        
        // If this is the last node add branch terminator
        if($resourceObj->checkNodeHasNextSibling($tree['row']))
            $offset .= "|- ";
        elseif($lvl != 0)
            $offset .= "'- "; 
            
        // Get extra group information
        $query = $this->access_control_model->fetch('groups',NULL,NULL,array('id'=>$tree['row']['id']));
        $row = $query->row_array();
        $disable = ($row['disabled']?'tick.png':'cross.png');   
        $edit = '<a href="'.site_url('auth/admin/acl_groups/manage/'.$tree['row']['id']).'">'.img($this->config->item('shared_assets').'icons/pencil.png').'</a>';                
    ?>  
        <tr>
            <td><?=$tree['row']['id']?></td>
            <td><?=$offset.$tree['row']['name']?></td>
            <td class="middle"><?=img($this->config->item('shared_assets').'icons/'.$disable)?></td> 
            <td class="middle"><?=($tree['row']['id']==1?'':$edit)?></td> 
            <td><?=($row['locked']?'':form_checkbox('select[]',$tree['row']['name'],FALSE))?></td>
        </tr>
    <?php endwhile; ?>
</tbody>
</table>
<?=form_close()?>