<h3><?php print $template['title'];?></h3>

<?php print form_open(uri_string());?>

<ul class="form vhh">
    <li>
        <?php print form_label($identity, 'identity');?>
        <?php print form_input('identity', set_value('identity'));?>
    </li>
    <li>
        <?php print lang('password', 'password');?>
        <?php print form_password('password');?>
    </li>
    <li>
        <?php print lang('remember_me', 'remember_me');?>
        <?php print form_checkbox('remember_me', '1');?>
    </li>
</ul>
<div class="clear"></div>

<div class="buttons">
	<button type="submit"><?php print lang('login');?></button>
</div>

<?php print form_close();?>