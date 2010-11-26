<h3><?php print $template['title'];?></h3>

<?php print form_open(site_url('users/save'), '', array('user_id' => $user->id));?>

<ul class="form vhh">
    <li class="required">
        <?php print lang('username', 'username');?>
        <?php print form_input('username', set_value('username', $user->username));?>
    </li>
    <li class="required">
        <?php print lang('email', 'email');?>
        <?php print form_input('email', set_value('email', $user->email));?>
    </li>
    <li<?php print ($user->id == '' ? " class='required'" : "");?>>
        <?php print lang('password', 'password');?>
        <?php print form_password('password', set_value('password'));?>
    </li>
    <li<?php print ($user->id == '' ? " class='required'" : "");?>>
        <?php print lang('confirm_password', 'confirm_password');?>
        <?php print form_password('confirm_password', set_value('confirm_password'));?>
    </li>
    <li>
        <?php print lang('group', 'group');?>
        <?php print form_dropdown('group', $groups);?>
    </li>
    <li>
        <?php print lang('is_active', 'is_active');?>
        <?php print form_checkbox('is_active', '1', set_value('is_active', $user->is_active));?>
    </li>
</ul>
<div class="clear"></div>

<?php print $template['partials']['user_profile'];?>

<div class="buttons">
	<button type="submit"><?php print lang('save');?></button>
</div>
<?php print form_close();?>