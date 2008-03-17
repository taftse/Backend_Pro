<h2><?=$header?></h2>

<?=form_open('auth/admin/acl_permissions/delete')?>   
<table width=100% cellspacing=0>
<thead>
    <tr>
        <th width=5%><?=$this->lang->line('access_id')?></th> 
        <th width=25%><?=$this->lang->line('access_groups')?></th> 
        <th width=25%><?=$this->lang->line('access_resources')?></th> 
        <th width=25%><?=$this->lang->line('access_actions')?></th> 
        <th width=10% class="middle"><?=$this->lang->line('access_edit')?></th> 
        <th width=10%><?=form_checkbox('all','select',FALSE)?></th> 
    </tr>
</thead>

<tfoot>
    <tr>
        <td colspan=5>&nbsp;</td>
        <td><?=form_submit('delete',$this->lang->line('access_delete'),'onClick = "return confirm(\''.$this->lang->line('access_delete_permissions').'\');"')?></td>
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
            <td class="middle"><a href="<?=site_url('auth/admin/acl_permissions/manage/'.$key)?>"><img src="<?=base_url().$this->config->item('shared_assets').'icons/pencil.png'?>" /></a></td> 
            <td style="vertical-align:middle"><?=form_checkbox('select[]',$key,FALSE)?></td> 
        </tr>
        <?php } ?>
</tbody>
<?=form_close()?>
</table>
<?=anchor('auth/admin/acl_permissions/manage',$this->lang->line('access_create_permission'),array('class'=>'icon_add'))?>&nbsp;&nbsp;&nbsp;
<?=anchor('auth/admin/acl_permissions/view',$this->lang->line('access_advanced_permissions'),array('class'=>'icon_lightning'))?>                                                                                      