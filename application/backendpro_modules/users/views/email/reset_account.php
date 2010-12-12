Hi <?php print $user->username;?>,

We received a request to reset your password on <?php print setting_item('site_name');?>. Please follow the link
below to reset your password:

<?php print site_url('users/reset/' . $reset_key);?>


If you did not request the reset please ignore this email and delete it.

Kind Regards