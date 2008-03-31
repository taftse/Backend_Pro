<h2><?=$header?></h2>

<div id="dashboard_left_container">
    <div class="dashboard_widget" id="notes">
        <b>My Notes</b>
        <?=form_open('admin')?>
        <?=form_textarea('note','','style="width:100%" rows="15" id="note"');?>
        <?=form_submit('submit',$this->lang->line('general_save'));?>
        <?=form_close();?>
    </div>
</div>

<div id="dashboard_right_container">
    <div class="dashboard_widget" id="statistics">
        <table width=100%>
            <tr><th>Statistics</th><th>Value</th></tr>
            <tr><td>System On</td><td>Yes</td></tr>
            <tr><td>BackendPro Version</td><td>0.2</td></tr>
            <tr><td>Members</td><td>1</td></tr>
            <tr><td>Un-Activated Members</td><td>0</td></tr>
        </table>
    </div>
</div>

