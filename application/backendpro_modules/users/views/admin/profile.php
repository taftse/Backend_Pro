<h4>Profile</h4>

<ul class="form vhh">
    <li>
        <?php print lang('first_name', 'first_name');?>
        <?php print form_input('first_name', set_value('first_name', $profile->first_name));?>
    </li>
    <li>
        <?php print lang('second_name', 'second_name');?>
        <?php print form_input('second_name', set_value('second_name', $profile->second_name));?>
    </li>
    <li>
        <?php print lang('gender', 'gender');?>
        <?php print form_dropdown('gender', $gender_options, set_value('gender', $profile->gender));?>
    </li>
</ul>
<div class="clear"></div>