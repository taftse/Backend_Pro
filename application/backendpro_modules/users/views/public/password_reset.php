<h3><?php print $template['title'];?></h3>

<?php print form_open(uri_string());?>

<ul class="form vhh">
    <li>
        <?php print lang('users_username_label', 'username');?>
        <?php print form_input('username', set_value('username', $user->username), ' DISABLED');?>
    </li>
    <li>
        <?php print lang('users_new_password_label', 'new_password');?>
        <?php print form_password('new_password', set_value('new_password'));?>
    </li>
    <li>
        <?php print lang('users_confirm_password_label', 'confirm_password');?>
        <?php print form_password('confirm_password', set_value('confirm_password'));?>
    </li>
</ul>
<div class="clear"></div>

<div class="buttons">
	<button type="submit"><?php print lang('users_save_password_btn');?></button>
</div>

<?php print form_close();?>