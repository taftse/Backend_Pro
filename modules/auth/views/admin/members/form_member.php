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