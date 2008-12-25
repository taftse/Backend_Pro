<ul id="menu">
    <li><?php print anchor('admin',$this->lang->line('backendpro_dashboard'),array('class'=>'icon_house'))?></li>
    <?php if(check('System',NULL,FALSE)):?>
    <li><span class="icon_computer"><?php print $this->lang->line('backendpro_system')?></span>
        <ul>
            <?php if(check('Members',NULL,FALSE)):?><li><?php print anchor('auth/admin/members',$this->lang->line('backendpro_members'),array('class'=>'icon_group'))?></li><?php endif;?>  
            <?php if(check('Access Control',NULL,FALSE)):?><li><?php print anchor('auth/admin/access_control',$this->lang->line('backendpro_access_control'),array('class'=>'icon_shield'))?></li><?php endif;?>  
            <?php if(check('Settings',NULL,FALSE)):?><li><?php print anchor('admin/settings',$this->lang->line('backendpro_settings'),array('class'=>'icon_cog'))?></li><?php endif;?>  
        </ul>
    </li>
    <?php endif;?>
</ul>