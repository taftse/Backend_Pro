<h3><?php print $template['title'];?></h3>

<?php print form_open(uri_string());?>

<ul class="form vhh">
    <li class="required">
        <?php print lang('users_username_label', 'username');?>
        <?php print form_input('username', set_value('username', $user->username));?>
    </li>
    <li class="required">
        <?php print lang('users_email_label', 'email');?>
        <?php print form_input('email', set_value('email', $user->email));?>
    </li>
    <li<?php print ($user->id == '' ? " class='required'" : '');?>>
        <?php print lang('users_password_label', 'password');?>
        <?php print form_password('password', set_value('password'));?>
    </li>
    <li>
        <?php print lang('users_confirm_password_label', 'confirm_password');?>
        <?php print form_password('confirm_password', set_value('confirm_password'));?>
    </li>
    <li>
        <?php print lang('users_group_label', 'group');?>
        <?php print form_dropdown('group', $user_groups);?>
    </li>
    <li>
        <?php print lang('users_is_active_label', 'is_active');?>
        <?php print form_checkbox('is_active', '1', set_value('is_active', $user->is_active));?>
    </li>
</ul>
<div class="clear"></div>

<?php print $template['partials']['user_profile'];?>

<div class="buttons">
	<button type="submit" name="submit" value="save"><?php print lang('users_save_changes_btn');?></button>
</div>
<?php print form_close();?>