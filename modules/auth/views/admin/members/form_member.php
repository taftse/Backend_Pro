<div id="generatePasswordWindow">
	<table>
		<tr><th width="50%"><?=$this->lang->line('userlib_generate_password'); ?></th><th class="right"><a href="javascript:void(0);" id="gpCloseWindow"><?=$this->page->icon('cross') ?></a></th></tr>
		<tr><td rowspan="3"><?=$this->lang->line('userlib_password'); ?>:<br/>&nbsp;&nbsp;&nbsp;<b id="gpPassword">PASSWORD</b></td><td class="right"><?=$this->lang->line('general_uppercase'); ?> <?=form_checkbox('uppercase','1',TRUE); ?></td></tr>
		<tr><td class="right"><?=$this->lang->line('general_numeric'); ?> <?=form_checkbox('numeric','1',TRUE); ?></td></tr>
		<tr><td class="right"><?=$this->lang->line('general_symbols'); ?> <?=form_checkbox('symbols','1',FALSE); ?></td></tr>
		<tr><td colspan="2"><a href="javascript:void(0);" class="icon_arrow_refresh" id="gpGenerateNew"><?=$this->lang->line('general_generate'); ?></a></td></tr>
		<tr><td><a href="javascript:void(0);" class="icon_tick" id="gpApply"><?=$this->lang->line('general_apply'); ?></a></td><td class="right"><?=$this->lang->line('general_length'); ?> <input type="text" name="length" value="12" maxlength="2" size="4" /></td></tr>
	</table>
</div>

<h2><?=$header?></h2>
<p><?=$this->lang->line('userlib_password_info')?></p>

<?=form_open('auth/admin/members/form/'.$this->validation->id,array('class'=>'horizontal'))?>
    <fieldset>
        <ol>
            <li>
                <?=form_label($this->lang->line('userlib_username'),'username')?>
                <?=form_input('username',$this->validation->username,'id="username" class="text"')?>
            </li>
            <li>
                <?=form_label($this->lang->line('userlib_email'),'email')?>
                <?=form_input('email',$this->validation->email,'id="email" class="text"')?>
            </li>
            <li>
                <?=form_label($this->lang->line('userlib_password'),'password')?>
                <?=form_password('password','','id="password" class="text"')?>
            </li>
            <li>
                <?=form_label($this->lang->line('userlib_confirm_password'),'confirm_password')?>
                <?=form_password('confirm_password','','id="confirm_password" class="text"')?>
            </li>
            <li>
                <?=form_label($this->lang->line('userlib_group'),'group')?>
                <?=form_dropdown('group',$groups,$this->validation->group,'id="group" size="10" style="width:20.3em;"')?>                
            </li>
            <li>
                <?=form_label($this->lang->line('userlib_active'),'active')?>
                Yes <?=form_radio('active','1',$this->validation->set_radio('active','1'),'id="active"')?>
                No <?=form_radio('active','0',$this->validation->set_radio('active','0'))?>
            </li>
            <li class="submit">
                <?=form_hidden('id',$this->validation->id)?>
                <div class="buttons">
	                <button type="submit" class="positive" name="submit" value="submit">
	                	<?= $this->page->icon('disk');?>
	                	<?=$this->lang->line('general_save')?>
	                </button>
	                
	                <a href="<?= site_url('auth/admin/members')?>" class="negative">
	                	<?= $this->page->icon('cross');?>
	                	<?=$this->lang->line('general_cancel')?>
	                </a>
	                
	                <a href="javascript:void(0);" id="generate_password">
	                	<?= $this->page->icon('key');?>
	                	<?=$this->lang->line('userlib_generate_password'); ?>
	                </a>
	            </div>
            </li>
        </ol>
    </fieldset>
<h2><?=$this->lang->line('userlib_user_profile')?></h2>
<?php 
    if( ! $this->preference->item('allow_user_profiles')):
        print "<p>".$this->lang->line('userlib_profile_disabled')."</p>";
    else:
?>       
    <fieldset>
        <ol>
            <li class="submit">
                <div class="buttons">
	                <button type="submit" class="positive" name="submit" value="submit">
	                	<?= $this->page->icon('disk');?>
	                	<?=$this->lang->line('general_save')?>
	                </button>
	                
	                <a href="<?= site_url('auth/admin/members')?>" class="negative">
	                	<?= $this->page->icon('cross');?>
	                	<?=$this->lang->line('general_cancel')?>
	                </a>
	            </div>
            </li>
        </ol>
    </fieldset>
<?php endif;?>
<?=form_close()?>