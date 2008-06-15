<h2><?=$header?></h2>

<div class="buttons">                
	<a href="<?= site_url('auth/admin/members/form')?>">
    <?= $this->page->icon('add');?>
    <?=$this->lang->line('userlib_create_user')?>
    </a>
</div><br/><br/>

<?=form_open('auth/admin/members/delete')?>
<table class="data_grid" cellspacing="0">
    <thead>
        <tr>
            <th width=5%><?=$this->lang->line('general_id')?></th>
            <th><?=$this->lang->line('userlib_username')?></th>
            <th><?=$this->lang->line('userlib_email')?></th>
            <th><?=$this->lang->line('userlib_group')?></th>
            <th><?=$this->lang->line('userlib_last_visit')?></th>
            <th width=5% class="middle"><?=$this->lang->line('userlib_active')?></th> 
            <th width=5% class="middle"><?=$this->lang->line('general_edit')?></th>
            <th width=10%><?=form_checkbox('all','select',FALSE)?><?=$this->lang->line('general_delete')?></th>        
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan=7>&nbsp;</td>
            <td><?=form_submit('delete',$this->lang->line('general_delete'),'onClick="return confirm(\''.$this->lang->line('userlib_delete_user_confirm').'\');"')?></td>
        </tr>
    </tfoot>
    <tbody>
        <?php foreach($members->result_array() as $row):
            // Check if this user account belongs to the person logged in
            // if so don't allow them to delete it
            $delete  = ($row['id'] == $this->session->userdata('id')?'&nbsp;':form_checkbox('select[]',$row['id'],FALSE));            
        ?>
        <tr>
            <td><?=$row['id']?></td>
            <td><?=$row['username']?></td>
            <td><?=$row['email']?></td>
            <td><?=$row['group']?></td>
            <td><?=$row['last_visit']?></td>
            <td class="middle"><?=img($this->config->item('shared_assets').'icons/'.($row['active']?'tick':'cross').'.png')?></td>
            <td class="middle"><a href="<?=site_url('auth/admin/members/form/'.$row['id'])?>"><?=img($this->config->item('shared_assets').'icons/pencil.png')?></a></td>
            <td><?=$delete?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?=form_close()?>