<h2><?=$header?></h2>

<?=$dashboard ?>

<div class="buttons" style="clear: both">
	<a href="javascript:void(0);" id="edit_dashboard">
		<?=$this->page->icon('pencil') ?>
		<?=$this->lang->line('general_edit') ?> <?=$this->lang->line('backendpro_dashboard') ?>
	</a>
	
	<a href="javascript:void(0);" id="save_dashboard">
		<?=$this->page->icon('disk') ?>
		<?=$this->lang->line('general_save') ?>
	</a>
</div>