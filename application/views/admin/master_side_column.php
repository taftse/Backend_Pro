<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<?php $this->load->view('admin/head');?>
<body>

<div id="wrapper">
	<?php $this->load->view('admin/header');?>

	<div id="content-wrapper">
		<div id="content" class="fixed">
            
            <div id="side-column"">
                <?php print $template['partials']['side_column'];?>
            </div><!-- // side-column -->

            <div id="main-wrapper" class="side-column">
            <?php print $this->status->display();?>
            <?php print $body; ?>
            </div><!-- // main-wrapper -->

			<div class="clear"></div>
		</div><!-- // content -->
	</div><!-- // content-wrapper -->

	<?php $this->load->view('admin/footer');?>
</div><!-- // wrapper -->

</body>
</html>