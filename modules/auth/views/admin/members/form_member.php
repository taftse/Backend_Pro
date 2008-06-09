<div id="generatePasswordWindow">
	<table>
		<tr><th width="50%">Generate Password</th><th class="right"><a href="javascript: void(0)" class="icon_cancel" id="gpCloseWindow"></a></th></tr>
		<tr><td rowspan=3>Password:<br/>&nbsp;&nbsp;&nbsp;<b id="gpPassword">PASSWORD</b></td><td class="right">Uppercase <input type="checkbox" name="uppercase" value="1" checked /></td></tr>
		<tr><td class="right">Numeric <input type="checkbox" name="numeric" value="1" checked /></td></tr>
		<tr><td class="right">Symbols <input type="checkbox" name="symbols" value="1" /></td></tr>
		<tr><td colspan=2><a href="javascript: void(0)" class="icon_arrow_refresh" id="gpGenerateNew">Generate</a></td></tr>
		<tr><td><a href="javascript: void(0)" class="icon_tick" id="gpApply">Apply</a></td><td class="right">Length <input type="text" name="length" maxlength="2" size="4" value="12" /></td></tr>
	</table>
</div>

<h2><?=$header?></h2>
<p><?=$this->lang->line('userlib_password_info')?></p>

<?=form_open('auth/admin/members/form/'.$this->validation->id,array('class'=>'horizontal'))?>
    <fieldset>
        <ol>
            <li>
                <?=form_label($this->lang->line('userlib_username'),'username')?>
                <?=form_input('username',$this->validation->username,'class="text"')?>
            </li>
            <li>
                <?=form_label($this->lang->line('userlib_email'),'email')?>
                <?=form_input('email',$this->validation->email,'class="text"')?>
            </li>
            <li>
                <?=form_label($this->lang->line('userlib_password'),'password')?>
                <?=form_password('password','','class="text"')?>
                <a href="javascript: void(0)" class="icon_key" id="generate_password">Generate Password</a>
            </li>
            <li>
                <?=form_label($this->lang->line('userlib_confirm_password'),'confirm_password')?>
                <?=form_password('confirm_password','','class="text"')?>
            </li>
            <li>
                <?=form_label($this->lang->line('userlib_group'),'group')?>
                <?=form_dropdown('group',$groups,$this->validation->group,'size=10 style="width:20.3em;"')?>                
            </li>
            <li>
                <?=form_label($this->lang->line('userlib_active'),'active')?>
                Yes <?=form_radio('active','1',$this->validation->set_radio('active','1'))?>
                No <?=form_radio('active','0',$this->validation->set_radio('active','0'))?>
            </li>
            <li class="submit">
                <?=form_hidden('id',$this->validation->id)?>
                <?=form_submit('submit',$this->lang->line('general_save'))?>
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
                <?=form_submit('submit',$this->lang->line('general_save'))?>
            </li>
        </ol>
    </fieldset>
<?php endif;?>
<?=form_close()?>