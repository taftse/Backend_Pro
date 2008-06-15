<h3><?=$header?></h3>
<?=form_open('auth/login',array('class'=>'horizontal'))?>
    <fieldset>
        <ol>
            <li>
                <label for="email"><?=$this->lang->line('userlib_email')?>:</label>
                <input type="text" name="email" id="email" class="text" value="<?=$this->validation->email?>"/>
            </li>
            <li>
                <label for="password"><?=$this->lang->line('userlib_password')?>:</label>
                <input type="password" name="password" id="password" class="text" />
            </li>
            <li>
                <label for="remember"><?=$this->lang->line('userlib_remember_me')?>?</label>
                <?=form_checkbox('remember','yes',$this->input->post('remember'))?>
            </li>
            <?php
            // Only display captcha if needed
            if($this->preference->item('use_login_captcha')){
            ?>
            <li class="captcha">
                <label for="recaptcha_response_field"><?=$this->lang->line('userlib_captcha')?>:</label>
                <?=$captcha?>
            </li>
            <?php } ?>
            
            <li class="submit">
            	<div class="buttons">
            		<button type="submit" class="positive">
            			<?=$this->page->icon('key') ?>
            			<?=$this->lang->line('userlib_login')?>
            		</button>

            		<a href="<?=site_url('auth/forgotten_password') ?>">
            			<?=$this->page->icon('arrow_refresh') ?>
            			<?=$this->lang->line('userlib_forgotten_password')?>
            		</a>
            		
            		<a href="<?=site_url('auth/register') ?>">
            			<?=$this->page->icon('user') ?>
            			<?=$this->lang->line('userlib_register')?>
            		</a>             
            </li>
        </ol>
    </fieldset>
<?=form_close()?>