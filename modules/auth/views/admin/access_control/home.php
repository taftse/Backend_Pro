<h2><?=$header?></h2>

<table id="access_control_menu">
<tr>
    <td><?=img($this->config->item('admin_assets') . 'images/ac_permissions.png')?></td>
    <td>
        <h3><?=anchor('auth/admin/acl_permissions',$this->lang->line('access_permissions'))?></h3>
        <p><?=$this->lang->line('access_permissions_desc')?></p>
    </td>
    
    <td><?=img($this->config->item('admin_assets') . 'images/ac_groups.png')?></td>
    <td>
        <h3><?=anchor('auth/admin/acl_groups',$this->lang->line('access_groups'))?></h3>
        <p><?=$this->lang->line('access_groups_desc')?></p>
    </td>
</tr>

<tr>
    <td><?=img($this->config->item('admin_assets') . 'images/ac_resources.png')?></td>
    <td>
        <h3><?=anchor('auth/admin/acl_resources',$this->lang->line('access_resources'))?></h3>
        <p><?=$this->lang->line('access_resources_desc')?></p>
    </td>
    
    <td><?=img($this->config->item('admin_assets') . 'images/ac_actions.png')?></td>
    <td>
        <h3><?=anchor('auth/admin/acl_actions',$this->lang->line('access_actions'))?></h3>
        <p><?=$this->lang->line('access_actions_desc')?></p>
    </td>
</tr>
</table>