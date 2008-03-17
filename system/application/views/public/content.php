    <div id="content">
        <?php $this->status->display();?>
        
        <?php
            if( isset($module)){
                $this->load->module_view($module,$page);
            } else {
                $this->load->view($page);
            }        
        ?>
    </div> 