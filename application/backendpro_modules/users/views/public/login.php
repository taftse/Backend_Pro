<h3><?php print $template['title'];?></h3>

<?php print form_open(uri_string());?>

<ul class="form vhh">
    <li>
        <?php print form_label($identity, 'identity');?>
        <?php print form_input('identity', set_value('identity'));?>
    </li>
    <li>
        <?php print lang('users_password_label', 'password');?>
        <?php print form_password('password');?>
    </li>
    <li>
        <?php print lang('users_remember_me_label', 'remember_me');?>
        <?php print form_checkbox('remember_me', '1');?>
    </li>
    <li>
        <?php print anchor('users/reset/request', lang('users_reset_link'));?>
        <?php print anchor('users/register', lang('users_register_link'));?>
    </li>
</ul>
<div class="clear"></div>

<div class="buttons">
	<button type="submit"><?php print lang('users_login_btn');?></button>
</div>

<?php print form_close();?>