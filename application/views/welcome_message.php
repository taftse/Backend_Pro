<div style="color:white">
<h1>Welcome to Backendpro!</h1>

<p>This version of BackendPro is still under development so not all features are complete.</p>

<p>If you would like to login please visit <?php print anchor('users/login','here');?>.
Once logged in you can visit 2 backend controllers.</p>

    <?php print anchor('users/manage','User Management');?><br/>
    <?php print anchor('settings', 'Settings');?>

<p>The new user guide can be accessed at <a href="user_guide">here</a></p>
</div>