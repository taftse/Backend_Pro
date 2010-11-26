<h3><?php print $template['title'];?></h3>

<?php if($this->user->has_access('Users', 'Add', FAlSE)):
    print anchor('users/add', lang('add_user'));
endif;?>

<script type="text/javascript">
    function confirm_delete()
    {
        return confirm('<?php print lang('confirm_user_delete');?>');
    }
</script>

<table>
    <thead>
        <tr>
            <th><?php print lang('username');?></th>
            <th><?php print lang('email');?></th>
            <th><?php print lang('group');?></th>
            <th><?php print lang('is_active');?></th>
            <th><?php print lang('last_ip');?></th>
            <th><?php print lang('last_login');?></th>
            <th><?php print lang('created_on');?></th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user):?>
            <?php
                $user->last_login = ($user->last_login == NULL ? 'Never' : mysqldatetime_to_date($user->last_login, 'd/m/Y'));
                $user->last_ip = ($user->last_ip == NULL ? 'None' : $user->last_ip);
                $user->is_active = ($user->is_active ? 'Yes' : 'No');
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
                    print anchor('users/delete/' . $user->id, lang('delete'), "onclick='return confirm_delete();'");
                else:
                    print '&nbsp;';
                endif;?>
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>