<h2><?php print $template['title'];?></h2>

<?php print form_open(uri_string() . '/save', '', array('original_slug' => $original_slug))?>
<ul class="form vhh">
    <li class="required">
        <?php print lang('slug','slug');?>
        <?php print form_input('slug', set_value('slug', $setting->slug));?>
    </li>
    <li class="required">
        <?php print lang('title','title');?>
        <?php print form_input('title', set_value('title', $setting->title));?>
    </li>
    <li>
        <?php print lang('description','description');?>
        <?php print form_input('description', set_value('description', $setting->description));?>
    </li>
    <li>
        <?php print lang('type','type');?>
        <?php print form_dropdown('type', $types, set_value('type', $setting->type));?>
    </li>
    <li>
        <?php print lang('options','options');?>
        <?php print form_input('options', set_value('options', $setting->options));?>
    </li>
    <li>
        <?php print lang('value','value');?>
        <?php print form_input('value', set_value('value', $setting->value));?>
    </li>
    <li>
        <?php print lang('validation_rules','validation_rules');?>
        <?php print form_input('validation_rules', set_value('validation_rules', $setting->validation_rules));?>
    </li>
    <li>
        <?php print lang('is_required','is_required');?>
        <?php print form_checkbox('is_required', '1', $setting->is_required);?>
    </li>
    <li>
        <?php print lang('is_gui','is_gui');?>
        <?php print form_checkbox('is_gui', '1', $setting->is_gui);?>
    </li>
    <li>
        <?php print lang('module','module');?>
        <?php print form_input('module', set_value('module', $setting->module));?>
    </li>
</ul>
<div class="clear"></div>

<div class="buttons">
	<button type="submit"><?php print lang('save');?></button>
</div>
<?php print form_close();?>