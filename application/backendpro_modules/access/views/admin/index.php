<h3><?php print $template['title'];?></h3>

<script type="text/javascript">
    $(document).ready(function(){
        // Setup the permission Manager and bind it to the controls on the page
        $('#access_container').permission_manager();
    });
</script>

<style type="text/css">
    .allow {
        color: green;
    }

    .deny {
        color: red;
    }

    .selected {
        font-weight: bold;
    }
</style>

<table cellpadding="0" cellspacing="0" id="access_container">
    <tr>
        <td width="25%">
            <div id="access_groups">
                <h3>1. <?php print lang('access_select_group_title');?></h3>
                <ul></ul>
            </div>
        </td>

        <td>
            <div id="access_resources">
                <h3>2. <?php print lang('access_select_resource_title');?></h3>
                <ul></ul>
            </div>
        </td>

        <td width="25%">
            <div id="access_actions">
                <h3>3. <?php print lang('access_select_actions_title');?></h3>
                <ul></ul>
            </div>
        </td>
    </tr>
</table>

<ul id="group_menu" class="contextMenu groupContextMenu">
    <li class="add"><a href="#add"><?php print lang('access_add_group');?></a></li>
    <li class="edit"><a href="#edit"><?php print lang('access_edit_group');?></a></li>
    <li class="delete"><a href="#delete"><?php print lang('access_delete_group');?></a></li>
</ul>

<ul id="resource_menu" class="contextMenu resourceContextMenu">
    <li class="add"><a href="#add"><?php print lang('access_add_resource');?></a></li>
    <li class="edit"><a href="#edit"><?php print lang('access_edit_resource');?></a></li>
    <li class="delete"><a href="#delete"><?php print lang('access_delete_resource');?></a></li>
</ul>

<ul id="action_menu" class="contextMenu actionContextMenu">
    <li class="add"><a href="#add"><?php print lang('access_add_action');?></a></li>
    <li class="edit"><a href="#edit"><?php print lang('access_edit_action');?></a></li>
    <li class="delete"><a href="#delete"><?php print lang('access_delete_action');?></a></li>
</ul>