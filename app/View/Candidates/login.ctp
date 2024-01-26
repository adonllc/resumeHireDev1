<?php echo $this->Html->script('jquery/jquery.pstrength-min.1.2.js'); ?>
<?php
    $site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
?> 
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#userlogin").validate();
    });
</script>
<div class="main_container">
    <div class="wrapper">
        <div class="content login_cnter">
            <div class="login_form">
                <div class="lefty_dv">
                    <div class="upper_hd_dv"> <span class="login_bhgy">Login</span> </div>

                    <?php echo $this->Form->create(Null, array('class' => 'cssForm', 'name' => 'userlogin', 'id' => 'userlogin')); ?>
                    <div class="form_contnr">
                        <?php echo $this->element('session_msg'); ?>
                        <div class="form_lst">
                            <span class="rltv"><?php echo $this->Form->text('User.email_address', array('class' => "required email", 'placeholder' => 'Email Address')) ?></span>
                        </div>

                        <div class="form_lst">
                            <span class="rltv"><?php echo $this->Form->password('User.password', array('class' => "required", 'id' => "password", 'placeholder' => 'Password')); ?></span>
                        </div>
                        
                        
                        <?php if ($this->Session->read('Userloginstatus') > 0) { ?>
                        <div class="form_lst captcha">
                            <div class="captcha_img">
                                <?php echo $this->Html->image($this->Html->url(array('controller' => 'users', 'action' => 'captcha'), true), array('style' => '', 'id' => 'captcha', 'vspace' => 2)); ?>
                                <a href="javascript:void(0);" onclick="document.getElementById('captcha').src = '<?php echo $this->Html->url('/users/captcha'); ?>?' + Math.round(Math.random(0) * 1000) + 1" title='Change Text'>
                                    <?php echo $this->Html->image('front/captcha_refresh.gif'); ?></a>
                            </div>
                            <div class="login_input">
                                <div id="customer_captcha_wrap" class="input_bx lonin">
                                    <?php echo $this->Form->text('User.captcha', array('autocomplete' => 'off', 'id' => 'user_password', 'class' => "required", 'placeholder' => 'Security Code*')); ?>
                                </div>
                            </div>
                            </div>
                        <?php } ?>   

                        <div class="fg_pass">
                            <span class="rmmr_me">
                                <div class="checkbox">
                                    
                                    <?php
                                    $checked = '';
                                    if (isset($_COOKIE["cookname"]) && isset($_COOKIE["cookpass"])) {
                                        $checked = 'checked';
                                    }
                                    echo $this->Form->checkbox('User.rememberme', array('class' => 'css-checkbox', 'id' => 'checkboxG1', $checked)); ?>
                                    <label for="checkboxG1" >Remember me</label>
                                </div> 
                            </span>

                            <span class="fg_lk">
                                <?php echo $this->Html->link('Forgot your password?', array('controller' => 'users', 'action' => 'forgotPassword'), array('escape' => false,'rel'=>'nofollow')); ?>
                            </span>
                        </div>

                        <div class="kg_sb">
                            <?php echo $this->Form->submit('Login', array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
                <div class="righty_dv">

                    <div class="rght_mn">

                        <div class="mj_hd">New Customer</div> 

                        <div class="lowr_txtng">If you don't have an account on <?php echo $site_title;?>?  then click below link. </div>

                        <div class="sgnup_blnk"><?php echo $this->Html->link('Sign Up Now!', array('controller' => 'users', 'action' => 'register'), array('escape' => false,'rel'=>'nofollow')); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
