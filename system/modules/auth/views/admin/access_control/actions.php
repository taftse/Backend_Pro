<h2><?=$header?></h2>

<a href="#create" class="icon_add"><?=$this->lang->line('access_create_action')?></a>

<?=form_open('auth/admin/acl_actions/delete')?> 
<table class="data_grid">
<thead>
    <tr>
        <th width=5%><?=$this->lang->line('general_id')?></th>
        <th><?=$this->lang->line('access_actions')?></th>
        <th width=10%><?=form_checkbox('all','select',FALSE)?> <?=$this->lang->line('general_delete')?></th>
    </tr>
</thead>
<tfoot>
    <tr>
        <td colspan=2>&nbsp;</td>
        <td><?=form_submit('delete',$this->lang->line('general_delete'),'onClick="return confirm(\''.$this->lang->line('access_delete_actions').'\');"')?></td>  
    </tr>
</tfoot>
<tbody>
    <?php 
    $query = $this->access_control_model->fetch('axos');
    foreach($query->result() as $result): ?>
    <tr>
        <td><?=$result->id?></td>
        <td><?=$result->name?></td>
        <td><?=form_checkbox('select[]',$result->name,FALSE)?></td>
    </tr>    
    <?php endforeach;?>
</tbody>
</table>
<?=form_close()?>

<a name="create"></a>
<h2><?=$this->lang->line('access_create_action')?></h2>
<?=form_open('auth/admin/acl_actions/create',array('class'=>'horizontal'))?>
    <fieldset>
        <ol>
            <li>
                <?=form_label($this->lang->line('access_name'),'name')?>
                <?=form_input('name','','class="text"')?>
            </li>
            <li class="submit">
                <?=form_submit('submit',$this->lang->line('general_add'))?>
            </li>
        </ol>
    </fielset>
<?=form_close()?>
