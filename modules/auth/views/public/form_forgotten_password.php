<h3><?=$header?></h3>
<?=form_open('auth/forgotten_password',array('class'=>'vertical'))?>
    <fieldset>
        <ol>
            <li>
                <label for="email"><?=$this->lang->line('userlib_email')?>:</label>
                <input type="text" name="email" id="email" class="text" />
            </li>
            <li class="submit">
                <input type="submit" name="submit" value="<?=$this->lang->line('userlib_reset_password')?>" /> &nbsp;
            </li>
        </ol>
    </fieldset>
<?=form_close()?>