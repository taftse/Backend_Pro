<h2><?=$header?></h2>

<div class="buttons">
	<a href="<?= site_url('auth/admin/acl_permissions/form')?>">
    <?=$this->page->icon('add');?>
    <?=$this->lang->line('access_create_permission')?>
    </a>
    
    <a href="<?= site_url('auth/admin/acl_permissions/show')?>">
    <?=$this->page->icon('lightning');?>
    <?=$this->lang->line('access_advanced_permissions')?>
    </a>
</div><br/><br/>

<?=form_open('auth/admin/acl_permissions/delete')?>
<table width=100% cellspacing=0>
<thead>
    <tr>
        <th width=5%><?=$this->lang->line('general_id')?></th>
        <th width=25%><?=$this->lang->line('access_groups')?></th>
        <th width=25%><?=$this->lang->line('access_resources')?></th>
        <th width=25%><?=$this->lang->line('access_actions')?></th>
        <th width=10% class="middle"><?=$this->lang->line('general_edit')?></th>
        <th width=10%><?=form_checkbox('all','select',FALSE) . $this->lang->line('general_delete')?></th>
    </tr>
</thead>

<tfoot>
    <tr>
        <td colspan=5>&nbsp;</td>
        <td><?=form_submit('delete',$this->lang->line('general_delete'),'onClick = "return confirm(\''.$this->lang->line('access_delete_permissions_confirm').'\');"')?></td>
    </tr>
</tfoot>

<tbody>
        <?php foreach($this->access_control_model->getPermissions() as $key=>$row){?>
        <tr>
            <td style="vertical-align:middle"><?=$key?></td>
            <td style="vertical-align:middle"><?=$row['aro']?></td>
            <td style="vertical-align:middle"><span class="<?=($row['allow']) ? 'allow':'deny'?>"><?=$row['aco']?></span></td>
            <td>
                <?php
                // Print out the actions
                if(isset($row['actions'])){
                    foreach($row['actions'] as $action)
                    {
                        print '<span class="';
                        print ($action['allow']) ? 'allow':'deny';
                        print '">'.$action['axo'].'</span><br>';
                    }
                }
                else { print "&nbsp;"; }
                ?>
            </td>
            <td class="middle"><a href="<?=site_url('auth/admin/acl_permissions/form/'.$key)?>"><img src="<?=base_url().$this->config->item('shared_assets').'icons/pencil.png'?>" /></a></td>
            <td style="vertical-align:middle"><?=form_checkbox('select[]',$key,FALSE)?></td>
        </tr>
        <?php } ?>
</tbody>
<?=form_close()?>
</table>

<div class="buttons">
    <a href="<?=site_url('auth/admin/access_control')?>">
    <?=$this->page->icon('arrow_left');?>
    <?=$this->lang->line('general_back')?>
    </a>
</div>