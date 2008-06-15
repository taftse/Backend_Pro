<h2><?=$header?></h2>

<?=form_open($form_link)?>
<table id="preference_form">

<?php foreach($field as $name => $data){ ?>
<tr>
    <td class='label'>
    
    <?=form_label($data['label'],$name)?>
    <?php 
    if (FALSE !== ($desc = $this->lang->line('preference_desc_'.$name)))
        print "<small>".$desc."</small>";
    ?>    
    </td>
    <td><?=$data['input']?></td>
</tr>     
<?php } ?>       

</table>

<div class="buttons">
	<button type="submit" class="positive">
    <?= $this->page->icon('disk');?>
    <?=$this->lang->line('general_save')?>
    </button>
                
    <a href="<?=site_url($cancel_link)?>" class="negative">
    <?= $this->page->icon('cross');?>
    <?=$this->lang->line('general_cancel')?>
    </a>      
</div>
<?=form_close()?>