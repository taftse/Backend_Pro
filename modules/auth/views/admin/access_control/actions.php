<h2><?=$header?></h2>

<div class="buttons">
	<a href="#create">
		<?=$this->page->icon('add') ?>
		<?=$this->lang->line('access_create_action')?>
	</a>
</div><br/><br/>

<?=form_open('auth/admin/acl_actions/delete')?> 
<table class="data_grid" cellspacing="0">
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
        <td><?=form_submit('delete',$this->lang->line('general_delete'),'onClick="return confirm(\''.$this->lang->line('access_delete_actions_confirm').'\');"')?></td>  
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

<div class="buttons">
	<a href="<?=site_url('auth/admin/access_control') ?>">
		<?=$this->page->icon('arrow_left') ?>
		<?=$this->lang->line('general_back')?>
	</a>
</div><br/><br/>

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
            	<div class="buttons">
            		<button type="submit" class="positive" name="submit" value="submit">
            			<?=$this->page->icon('disk') ?>
            			<?=$this->lang->line('general_save') ?>
            		</button>
            	</div>
            </li>
        </ol>
    </fieldset>
<?=form_close()?>
