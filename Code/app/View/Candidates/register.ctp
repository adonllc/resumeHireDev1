<script type="text/javascript">
    $(document).ready(function() {
              
        /************************************************************/
        $.validator.addMethod("contact", function(value, element) {
            return  this.optional(element) || (/^[0-9+]+$/.test(value));
        }, "Contact Number is not valid.");
         $.validator.addMethod("validname", function(value, element) {
            return this.optional(element) || /^[a-zA-Z_]+$/.test(value);
        }, "*Note: Special characters, number and spaces are not allowed.");
        $.validator.addMethod("pass", function(value, element) {
            return  this.optional(element) || (/.{8,}/.test(value) && /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,20})/.test(value));
        }, "Password minimum length must be 8 charaters and combination of 1 special character, 1 lowercase character, 1 uppercase character and 1 number.");
        $("#userRegister").validate();
        /************************************************************/
                
    });
    
</script>
<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($site_title); die;
?>  
<?php echo $this->element('pstrength'); ?>
<?php // echo $this->Html->script('jquery/jquery.pstrength-min.1.2.js'); ?>
<script type="text/javascript">
    $(function() {
        $('.password').pstrength();
    });
</script>

<?php echo $this->Html->script('jquery.validate.js'); ?>
<?php echo $this->Html->script('jquery/jquery.pstrength-min.1.2.js'); ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#userRegister").validate();
    });

</script>
<div class="main_container">
    <div class="wrapper">
        <div class="content login_cnter">
            <div class="login_form registerwa">
                <div class="lefty_dv">
                    <div class="upper_hd_dv"> <span class="login_bhgy">Sign up</span> </div>

                    <?php echo $this->Form->create(Null, array('class' => 'cssForm', 'name' => 'userlogin', 'id' => 'userRegister')); ?>
                    <div class="form_contnr">
                        <?php echo $this->element('session_msg'); ?>
                        <div class="form_lst">
                            <span class="rltv"><?php echo $this->Form->text('User.company_name', array('class' => "required", 'placeholder' => 'Company Name')) ?></span>
                        </div>
                        <div class="form_lst">
                            <span class="rltv"><?php echo $this->Form->text('User.first_name', array('maxlength' => '20','class' => "required validname", 'placeholder' => 'First Name')) ?></span>
                        </div>
                        <div class="form_lst">
                            <span class="rltv"><?php echo $this->Form->text('User.last_name', array('maxlength' => '20','class' => "required validname", 'placeholder' => 'Last Name')) ?></span>
                        </div>
                        <div class="form_lst">
                            <span class="rltv"><?php echo $this->Form->text('User.email_address', array('class' => "required email", 'placeholder' => 'Email Address')) ?></span>
                        </div>
                      
                        <div class="form_lst">
                            <span class="rltv"><?php echo $this->Form->password('User.password', array('id' => 'password', 'class' => "password required pass", 'placeholder' => 'Password')) ?></span>
                        </div>
                        <div class="form_lst">
                            <span class="rltv"><?php echo $this->Form->password('User.confirm_password', array('equalTo' => '#password', 'class' => "form-control required", 'placeholder' => 'Confirm Password')) ?>  </span>
                        </div>
                        
                        
                        
                        
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
                       <?php /* <div class="fg_pass">
                            <span class="rmmr_me">
                                <div class="checkbox">
                                    <?php echo $this->Form->checkbox('User.terms', array('class' => 'css-checkbox required', 'id' => 'checkboxG1')); ?>
                                    <label for="checkboxG1" class="tnc">By signing up, I agree to <?php echo $site_title; ?> <a href="javascript:void(0);" onclick="window.open('<?php echo HTTP_PATH ?>/pages/terms_and_conditions', 'term', 'width=900,height=400,scrollbars=1')" class="term_cond link_color" id="errorterms"> Terms and Conditions</a></label>
                                </div> 
                            </span>
                        </div> */?>
                        
                        <div class="kg_sb cntre">
                            <?php echo $this->Form->submit('Submit', array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                            <?php echo $this->Form->reset('Reset', array('class' => 'input_btn rst', 'id' => 'resetForm')); ?>
                            
                            
                        </div>
                        <div class="fg_pass">
                            <span class="rmmr_me">
                             <label class="tnc checkbox">By signing up, you agree to <?php echo $site_title; ?> <a href="javascript:void(0);" onclick="window.open('<?php echo HTTP_PATH ?>/pages/terms_and_conditions', 'term', 'width=900,height=400,scrollbars=1')" class="term_cond link_color" id="errorterms"> Terms and Conditions</a></label>
                            </span>
                        </div>   
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
                <div class="righty_dv">

                    <div class="rght_mn">

                        <div class="mj_hd">Already Registered </div> 

                        <div class="lowr_txtng">If you have an account on <?php echo $site_title;?>?  then click below link. </div>

                        <div class="sgnup_blnk"><?php echo $this->Html->link('Sign in!', array('controller' => 'users', 'action' => 'login'), array('escape' => false,'rel'=>'nofollow')); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
