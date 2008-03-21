<h2><?=$header?></h2>

<a href="<?=site_url('auth/admin/acl_resources/form')?>" class="icon_add"><?=$this->lang->line('access_create_resource')?></a>

<!-- VIEW RESOURCES -->
<?=form_open('auth/admin/acl_resources/delete')?> 
<table class="data_grid">
<thead>
    <tr>
        <th width=5%><?=$this->lang->line('general_id')?></th>
        <th><?=$this->lang->line('access_resources')?></th>  
        <th width=10% class="middle"><?=$this->lang->line('general_edit')?></th>   
        <th width=10%><?=form_checkbox('all','select',FALSE)?> <?=$this->lang->line('general_delete')?></th>
    </tr>
</thead>
<tfoot>
    <tr>
        <td colspan=3>&nbsp;</td>
        <td><?=form_submit('delete',$this->lang->line('general_delete'),'onClick="return confirm(\''.$this->lang->line('access_delete_resources_confirm').'\');"')?></td>
    </tr>
</tfoot>
<tbody>
    <?php 
    // Output nested resource view
    $obj = & $this->access_control_model->resource;
    $tree = $obj->getTreePreorder($obj->getRoot());
    
    while($obj->getTreeNext($tree)):        
        // See if this resource is loced
        $query = $this->access_control_model->fetch('resources','locked',NULL,array('id'=>$tree['row']['id']));
        $row = $query->row();     
        
        // Get Offset
        $offset = $this->access_control_model->buildPrettyOffset(&$obj,$tree);           
    ?>  
        <tr>
            <td><?=$tree['row']['id']?></td>
            <td><?=$offset.$tree['row']['name']?></td>  
            <td class="middle"><a href="<?=site_url('auth/admin/acl_resources/form/'.$tree['row']['id'])?>"><?=img($this->config->item('shared_assets'). 'icons/pencil.png')?></a></td>  
            <td><?=($row->locked?'':form_checkbox('select[]',$tree['row']['name'],FALSE))?></td>
        </tr>
    <?php endwhile; ?>
</tbody>
</table>
<?=form_close()?>