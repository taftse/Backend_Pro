<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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