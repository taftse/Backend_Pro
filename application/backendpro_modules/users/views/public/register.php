<h3><?php print $template['title'];?></h3>

<?php print form_open(uri_string());?>

<ul class="form vhh">
    <li>
        <?php print lang('username', 'username');?>
        <?php print form_input('username', set_value('username'));?>
    </li>
    <li>
        <?php print lang('email', 'email');?>
        <?php print form_input('email', set_value('email'));?>
    </li>
    <li>
        <?php print lang('confirm_email', 'confirm_email');?>
        <?php print form_input('confirm_email', set_value('confirm_email'));?>
    </li>
    <li>
        <?php print lang('password', 'password');?>
        <?php print form_password('password', set_value('password'));?>
    </li>
    <li>
        <?php print lang('confirm_password', 'confirm_password');?>
        <?php print form_password('confirm_password', set_value('confirm_password'));?>
    </li>
</ul>
<div class="clear"></div>

<div class="buttons">
	<button type="submit"><?php print lang('create_account');?></button>
</div>

<?php print form_close();?>