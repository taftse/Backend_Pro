Welcome <?php print $user['username'];?>,

Congratulations on setting up your new account with <?php print setting_item('site_name');?>. Your account details
are included below

Username: <?php print $user['username'];?>

Email: <?php print $user['email'];?>

Password: <?php print $password;?>

<?php if(isset($user['activation_key'])):?>
        
Before you can start using your account you must activate it. Please follow the link below. Until you do this
you won't be able to log in.

<?php print site_url('users/activate/' . $user['activation_key']);?>
<?php endif; ?>