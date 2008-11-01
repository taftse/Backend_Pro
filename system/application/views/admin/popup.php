<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?=$header.' | '.$this->preference->item('site_name')?></title>
	<?=$this->page->output_variables()?>
	<?=$this->page->output_assets('admin')?>
</head>
<body style="padding: 2em;">
    <a name="top"></a>
    <?=displayStatus();?>
    <?=(isset($content)) ? $content : NULL; ?>
    <?php
    if( isset($page)){
    if( isset($module)){
            $this->load->module_view($module,$page);
        } else {
            $this->load->view($page);
        }}
    ?>
</body>
</html>