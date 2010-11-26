<html>
<head>
<title><?php print $template['site_title'];?></title>
<?php
    print $template['metadata'];
    print $this->asset->render();
?>
</head>
<body>
<?php print $this->status->display();?>
<?php print $body;?>
</body>
</html>