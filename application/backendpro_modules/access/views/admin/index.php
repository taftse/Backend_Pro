<h3><?php print $template['title'];?></h3>

<script type="text/javascript">
    $(document).ready(function(){
        $('#access_container').permission_manager();
    });
</script>

<table cellpadding="0" cellspacing="0" id="access_container">
    <tr>
        <td width="25%">
            <div id="access_groups">
                <h3>1. <?php print lang('select_a_group');?></h3>
                <ul></ul>
            </div>
        </td>

        <td>
            <div id="access_resources">
                <h3>2. <?php print lang('choose_resource');?></h3>
                <ul class="treeview permissiontree"></ul>
            </div>
        </td>

        <td width="25%">
            <div id="access_actions">
                <h3>3. <?php print lang('choose_actions');?></h3>
                <ul></ul>
            </div>
        </td>
    </tr>
</table>