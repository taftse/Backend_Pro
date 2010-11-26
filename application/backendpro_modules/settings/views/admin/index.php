<script type="text/javascript">
    $(document).ready(function(){
        // Setup the jQuery UI tabs
        $('#tabs').tabs();

        // When the user hovers over a setting, show the action links
        $('.row-action-trigger').hover(function()
        {
            $('.row-actions span', $(this)).show();
        }, function()
        {
            $('.row-actions span', $(this)).hide();
        });

        // When the user clicks the delete link
        // remove the setting
        $('.row-actions .delete a').click(function()
        {
            if(confirm('<?php print lang('confirm_setting_delete');?>'))
            {
                var link = $(this);
                var slug = link.attr('href').substr(1);

                // Perform an ajax delete
                $.ajax({
                    type: "POST",
                    url: site_url('settings/delete/' + slug),
                    success: function(){
                        // Remove the row
                        link.parents('tr').slideUp(function(){
                            $(this).remove();
                        });
                    }
                });
            }
        });
    });
</script>
<h2><?php print $template['title']?></h2>
<?php print anchor('settings/add', lang('add_setting'));?>
<?php print form_open('settings/save');?>
<div id="tabs">
	<ul>
        <?php foreach($sections as $slug => $name):?>
        <li><a href="#tabs-<?php print $slug;?>"><?php print $name;?></a></li>
        <?php endforeach;?>
	</ul>

    <?php foreach($sections as $slug => $name):?>
    <div id="tabs-<?php print $slug;?>">
        <table class="preference-table">
            <?php foreach($settings[$slug] as $setting):?>
            <tr class="row-action-trigger">
                <td class="name-column">
                    <?php print form_label($setting->title, $setting->slug);?>
                    <?php if($setting->is_required):?>
                        <span style="color: red;">*</span>
                    <?php endif;?>

                    <?php if($setting->description != ''):?>
                    <p class="note"><?php print $setting->description;?></p>
                    <?php endif;?>

                    <?php if($this->user->has_access('Settings','Manage', FALSE)):?>
                    <span class="row-actions">
                        <span class="edit"><?php print anchor('settings/edit/' . $setting->slug, lang('edit'));?> | </span>
                        <span class="delete"><a href='#<?php print $setting->slug;?>'><?php print lang('delete');?></a></span>
                    </span>
                    <?php endif;?>
                </td>               

                <td><?php print $setting->control;?></td>
            </tr>
            <?php endforeach;?>
        </table>
        <div class="clear"></div>
    </div>
    <?php endforeach; ?>
</div>

<div class="buttons">
	<button type="submit"><?php print lang('save');?></button>
</div>
<?php print form_close();?>