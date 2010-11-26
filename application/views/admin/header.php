<div id="header-wrapper">
    <div id="metadata">
        <div id="user-details"><?php print lang('logged_in_as');?> <?php print $this->user->data()->username;?> | <?php print anchor('users/logout', lang('logout'));?></div>
        <div id="breadcrumb"><?php print $template['breadcrumbs']; ?></div>
    </div><!-- // metadata -->

    <div id="banner"><a href="#"><img src="<?php print base_url();?>assets/images/backendpro/shield-icon_32.png" /></a> <?php print anchor('', $this->setting->item('site_name'));?>
    </div><!-- // banner -->

    <div class="clear"></div>
</div><!-- // header-wrapper -->