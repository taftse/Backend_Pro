<h3><?=$header?></h3>
<?=form_open('auth/register',array('class'=>'horizontal'))?>
    <fieldset>
        <ol>
            <li>
                <label for="username"><?=$this->lang->line('userlib_username')?>:</label>
                <input type="text" name="username" id="username" size="32" class="text" value="<?=$this->validation->username?>" />
            </li>
            <li>
                <label for="email"><?=$this->lang->line('userlib_email')?>:</label>
                <input type="text" name="email" id="email" class="text"  value="<?=$this->validation->email?>" />
            </li>
            <li>
                <label for="password"><?=$this->lang->line('userlib_password')?>:</label>
                <input type="password" name="password" id="password" size="32" class="text" />
            </li>
            <li>
                <label for="confirm_password"><?=$this->lang->line('userlib_confirm_password')?>:</label>
                <input type="password" name="confirm_password" id="confirm_password" size="32" class="text" />
            </li>
            <?php
            // Only display captcha if needed
            if($this->preference->item('use_registration_captcha')){
            ?>
            <li class="captcha">
                <label for="recaptcha_response_field"><?=$this->lang->line('userlib_captcha')?>:</label>
                <?=$captcha?>
            </li>
            <?php } ?>
            <li class="submit">
                <input type="submit" name="submit" value="<?=$this->lang->line('userlib_register')?>" /> &nbsp;
            </li>
        </ol>
    </fieldset>
<?=form_close()?>