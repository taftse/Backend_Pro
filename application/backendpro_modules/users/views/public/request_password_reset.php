<h3><?php print $template['title'];?></h3>

<p><?php print lang('users_reset_password_description');?></p>

<?php print form_open(uri_string());?>

<ul class="form vhh">
    <li class="required">
        <?php print lang('users_email_label','email');?>
        <?php print form_input('email');?>
    </li>
</ul>
<div class="clear"></div>

<div class="buttons">
	<button type="submit"><?php print lang('users_reset_btn');?></button>
</div>

<?php print form_close();