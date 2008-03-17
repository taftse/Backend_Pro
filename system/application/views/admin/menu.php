<ul id="menu">
     <li><?=anchor('admin',$this->lang->line('backendpro_dashboard'),array('class'=>'icon_house'))?></li>
    <li><span class="icon_computer">System</span>
        <ul>
            <li><?=anchor('auth/admin/users',$this->lang->line('backendpro_members'),array('class'=>'icon_group'))?></li> 
            <li><?=anchor('auth/admin/access_control',$this->lang->line('backendpro_access_control'),array('class'=>'icon_shield'))?></li> 
            <li><?=anchor('admin/settings',$this->lang->line('backendpro_settings'),array('class'=>'icon_cog'))?></li> 
            <li><?=anchor('admin/utilities',$this->lang->line('backendpro_utilities'),array('class'=>'icon_application'))?></li>
        </ul>
    </li>
</ul>