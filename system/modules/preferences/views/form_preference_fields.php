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
<?=form_submit('submit',$this->lang->line('preference_save'))?>
<?=form_close()?>