<div id="navigation">
    <?=$this->load->view($this->config->item('backendpro_template_admin') . 'menu');?>
</div>
<div id="breadcrumb">
        <?php $this->page->output_breadcrumb();?>
    </div>
<div id="content">            
    <a name="top"></a>
    <?=$this->status->display();?>
    <?=(isset($content)) ? $content : NULL; ?>
    <?php
    if( isset($page)){
    if( isset($module)){
            $this->load->module_view($module,$page);
        } else {
            $this->load->view($page);
        }} 
    ?>
</div>