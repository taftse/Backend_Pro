<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">    
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>
	<?=$this->page->output_metatags()?>
	<title><?=$header.' | '.$this->preference->item('site_name')?></title>
	<?=$this->page->output_assets('admin')?>
</head>
<body>
    
<div id="wrapper">
    <div id="header">
        <div id="site"><?=$this->preference->item('site_name')?></div>
        <div id="info">
            <?=anchor('',$this->lang->line('backendpro_view_website'),array('class'=>'icon_world_go'))?>&nbsp;&nbsp;&nbsp;&nbsp;
            <?=anchor('auth/logout',$this->lang->line('userlib_logout'),array('class'=>'icon_key_go'))?>
        </div>
    </div>