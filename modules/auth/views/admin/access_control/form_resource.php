<h2><?=$header?></h2>

<?=form_open('auth/admin/acl_resources/form/'.$this->validation->id,array('class'=>'horizontal'))?>
    <fieldset>
        <ol>
            <li>
                <?=form_label($this->lang->line('access_name'),'name')?>
                <?=form_input('name',$this->validation->name,'class="text"'.($this->validation->id==''?'':' READONLY'))?>
            </li>
            <li>
                <?=form_label($this->lang->line('access_parent_name'),'parent')?>
                <?=form_dropdown('parent',$resources,$this->validation->parent,'size=10 style="width:20.3em;"')?>
            </li>
            <li class="submit">
                <?=form_hidden('id',$this->validation->id)?>
                <?=form_submit('submit',$this->lang->line('general_save'))?>
            </li>
        </ol>
    </fieldset>
<?=form_close()?>