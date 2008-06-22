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
                <div class="buttons">
					<button type="submit" class="positive" name="submit" value="submit">
						<?=$this->page->icon('disk') ?>
						<?=$this->lang->line('general_save') ?>
					</button>
					
					<a href="<?=site_url('auth/admin/acl_resources')?>" class="negative">
						<?=$this->page->icon('cross') ?>
						<?=$this->lang->line('general_cancel')?>
					</a>
				</div>
            </li>
        </ol>
    </fieldset>
<?=form_close()?>