<h3><?=$header?></h3>
<?=form_open('auth/forgotten_password',array('class'=>'vertical'))?>
    <fieldset>
        <ol>
            <li>
                <label for="email"><?=$this->lang->line('userlib_email')?>:</label>
                <input type="text" name="email" id="email" class="text" />
            </li>
            <li class="submit">
            	<div class="buttons">
            		<button type="submit" class="positive">
            			<?=$this->page->icon('arrow_refresh') ?>
            			<?=$this->lang->line('userlib_reset_password')?>
            		</button>
            		
            		<a href="<?=site_url('auth/login') ?>" class="negative">
            			<?=$this->page->icon('cross') ?>
            			<?=$this->lang->line('general_cancel')?>
            		</a>
            	</div>
            </li>
        </ol>
    </fieldset>
<?=form_close()?>