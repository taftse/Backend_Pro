<h3><?php print $template['title'];?></h3>

<?php if($this->user->has_access('Users', 'Add', FAlSE)):
    print anchor('users/add', lang('users_add_link'));
endif;?>

<script type="text/javascript">
    function confirm_delete()
    {
        return confirm('<?php print lang('users_confirm_delete');?>');
    }
</script>

<table>
    <thead>
        <tr>
            <th><?php print lang('users_username_label');?></th>
            <th><?php print lang('users_email_label');?></th>
            <th><?php print lang('users_group_label');?></th>
            <th><?php print lang('users_is_active_label');?></th>
            <th><?php print lang('users_last_ip_label');?></th>
            <th><?php print lang('users_last_login_label');?></th>
            <th><?php print lang('users_created_on_label');?></th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user):?>
            <?php
                $user->last_login = ($user->last_login == NULL ? lang('users_no_last_login') : mysqldatetime_to_date($user->last_login, 'd/m/Y'));
                $user->last_ip = ($user->last_ip == NULL ? lang('users_no_last_ip') : $user->last_ip);
                $user->is_active = ($user->is_active ? lang('users_is_active_yes') : lang('users_is_active_no'));
            ?>
            <tr>
                <td>
                <?php if($this->user->has_access('Users', 'Edit', FALSE)):
                    print anchor('users/edit/' . $user->id, $user->username);
                else:
                    print $user->username;
                endif;?>
                </td>
                
                <td><?php print $user->email;?></td>
                <td><?php print $user->email;?></td>
                <td><?php print $user->is_active;?></td>
                <td><?php print $user->last_ip;?></td>
                <td><?php print $user->last_login;?></td>
                <td><?php print mysqldatetime_to_date($user->created_on, 'd/m/Y');?></td>

                <td>
                <?php if($this->user->has_access('Users', 'Edit', FALSE)):
                    print anchor('users/delete/' . $user->id, lang('users_delete_link'), "onclick='return confirm_delete();'");
                else:
                    print '&nbsp;';
                endif;?>
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>