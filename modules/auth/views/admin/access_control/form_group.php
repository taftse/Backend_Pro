<h2><?=$header?></h2>

<?=form_open('auth/admin/acl_groups/form/'.$this->validation->id,array('class'=>'horizontal'))?>
    <fieldset>
        <ol>
            <li>
                <?=form_label($this->lang->line('access_name'),'name')?>
                <?=form_input('name',$this->validation->name,'class="text"')?>
            </li>
            <li>
                <?=form_label($this->lang->line('access_disabled'),'disabled')?>
                Yes <?=form_radio('disabled','1',$this->validation->set_radio('disabled','1'))?>
                No <?=form_radio('disabled','0',$this->validation->set_radio('disabled','0'))?>
            </li>
            <li>
                <?=form_label($this->lang->line('access_parent_name'),'parent')?>
                <?=form_dropdown('parent',$groups,$this->validation->parent,'size=10 style="width:20.3em;"')?>
            </li>
            <li class="submit">
                <?=form_hidden('id',$this->validation->id)?>
                <?=form_submit('submit',$this->lang->line('general_save'))?>
            </li>
        </ol>
    </fieldset>
<?=form_close()?>