<h2><?=$header?></h2>

<a href="<?=site_url('auth/admin/acl_groups/form')?>" class="icon_add"><?=$this->lang->line('access_create_group')?></a>

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
    $obj = & $this->access_control_model->group;
    $tree = $obj->getTreePreorder($obj->getRoot());
    
    while($obj->getTreeNext($tree)):        
        // See if this group is locked
        $query = $this->access_control_model->fetch('groups',NULL,NULL,array('id'=>$tree['row']['id']));
        $row = $query->row();     
        
        // Get Offset
        $offset = $this->access_control_model->buildPrettyOffset(&$obj,$tree);
        $edit = ($obj->checkNodeIsRoot($tree['row'])?'&nbsp;':'<a href="'.site_url('auth/admin/acl_resources/form/'.$tree['row']['id']).'">'.img($this->config->item('shared_assets'). 'icons/pencil.png').'</a>');           
        $disable = ($row->disabled?'tick.png':'cross.png');
        $edit = ($obj->checkNodeIsRoot($tree['row']))?'':'<a href="'.site_url('auth/admin/acl_groups/form/'.$tree['row']['id']).'">'.img($this->config->item('shared_assets').'icons/pencil.png').'</a>';
    ?>  
        <tr>
            <td><?=$tree['row']['id']?></td>
            <td><?=$offset.$tree['row']['name']?></td>
            <td class="middle"><?=img($this->config->item('shared_assets').'icons/'.$disable)?></td> 
            <td class="middle"><?=$edit?></td> 
            <td><?=($row->locked OR $this->preference->item('default_user_group')==$tree['row']['id'])?'':form_checkbox('select[]',$tree['row']['name'],FALSE)?></td>
        </tr>
    <?php endwhile; ?>
</tbody>
</table>
<?=form_close()?>