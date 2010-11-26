<head>
<title><?php print $template['title']?></title>
<?php
    print $template['metadata'];
    print $template['variables'];
    print $this->asset->render();
?>
</head>