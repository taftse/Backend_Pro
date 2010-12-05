<h2><?php print $template['title'];?></h2>

<?php print form_open(uri_string())?>
<ul class="form vhh">
    <li class="required">
        <?php print lang('settings_slug_label','slug');?>
        <?php print form_input('slug', set_value('slug', $setting->slug));?>
    </li>
    <li class="required">
        <?php print lang('settings_title_label','title');?>
        <?php print form_input('title', set_value('title', $setting->title));?>
    </li>
    <li>
        <?php print lang('settings_description_label','description');?>
        <?php print form_input('description', set_value('description', $setting->description));?>
    </li>
    <li>
        <?php print lang('settings_type_label','type');?>
        <?php print form_dropdown('type', $types, set_value('type', $setting->type));?>
    </li>
    <li>
        <?php print lang('settings_options_label','options');?>
        <?php print form_input('options', set_value('options', $setting->options));?>
    </li>
    <li>
        <?php print lang('settings_value_label','value');?>
        <?php print form_input('value', set_value('value', $setting->value));?>
    </li>
    <li>
        <?php print lang('settings_validation_rules_label','validation_rules');?>
        <?php print form_input('validation_rules', set_value('validation_rules', $setting->validation_rules));?>
    </li>
    <li>
        <?php print lang('settings_is_required_label','is_required');?>
        <?php print form_checkbox('is_required', '1', $setting->is_required);?>
    </li>
    <li>
        <?php print lang('settings_is_gui_label','is_gui');?>
        <?php print form_checkbox('is_gui', '1', $setting->is_gui);?>
    </li>
    <li>
        <?php print lang('settings_module_label','module');?>
        <?php print form_input('module', set_value('module', $setting->module));?>
    </li>
</ul>
<div class="clear"></div>

<div class="buttons">
	<button type="submit" name="submit" value="save"><?php print lang('settings_save_link');?></button>
</div>
<?php print form_close();?>