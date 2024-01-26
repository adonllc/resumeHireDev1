<script type="text/javascript">
    jQuery(document).ready(function(){
        $.validator.addMethod('mypassword', function(value, element) {
            return this.optional(element) || (value.match(/[0-9]/));
        },"<?php echo __d('user', 'Password must contain at least one number', true);?>." );
        
        $.validator.addMethod("pass", function(value, element) {
            return  this.optional(element) || (/.{8,}/.test(value) && /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,20})/.test(value));
        }, "<?php echo __d('user', 'Password minimum length must be 8 charaters and combination of 1 special character, 1 lowercase character, 1 uppercase character and 1 number.', true);?>" );
        jQuery("#resetPassword").validate();
    });
</script>
<?php // echo $this->Html->script('jquery/jquery.pstrength-min.1.2.js'); ?>
<?php echo $this->element('pstrength'); ?>
<script type="text/javascript">
    jQuery(function() {
        jQuery('#password').pstrength();
    });
</script>
<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($site_title); die;
?> 
<div class="main_container">
    <div class="wrapper">
        <div class="content login_cnter login_cnter_forgot">
            <div class="forgot_cnter">
                 <div class="upper_hd_dv"> <span class="login_bhgy"><?php echo __d('user', 'Reset your password', true);?></span> </div>
            <div class="login_form">
                <div class="lefty_dv_fo">
                   

                    <?php echo $this->Form->create(Null, array('class' => 'cssForm', 'name' => 'userlogin', 'id' => 'resetPassword')); ?>
                    <div class="form_contnr">
                        <?php echo $this->element('session_msg'); ?>
                        <div class="form_lst">
                            <label><?php echo __d('user', 'New Password', true);?></label>
                            <span class="rltv"><?php echo $this->Form->password('User.password', array('minlength' => '8','class' => 'required', 'id' => "password", 'placeholder' => __d('user', 'New Password', true))); ?></span>
                        </div>

                        <div class="form_lst">
                            <label><?php echo __d('user', 'Confirm Password', true);?></label>
                            <span class="rltv"><?php echo $this->Form->password('User.confirm_password', array( 'class' => 'required', "equalTo" => "#password", 'placeholder' => __d('user', 'Confirm Password', true))); ?></span>
                        </div>
                        <div class="form_lst dotno">
                            <label>&nbsp;</label>
                            <div class="kg_sb rltv">
                            <?php echo $this->Form->submit(__d('user', 'Reset Password', true), array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                            </div>
                            </div>
                    </div>
                     <?php echo $this->Form->hidden('User.id', array('value' => $userId)) ?>
                    <?php echo $this->Form->end(); ?>
                </div>
<!--                <div class="righty_dv">

                    <div class="rght_mn">

                        <div class="mj_hd">Already Registered </div> 

                        <div class="lowr_txtng">If you have an account on <?php //echo $site_title;?>?  then click below link. </div>

                        <div class="sgnup_blnk"><?php //echo $this->Html->link('Sign in!', array('controller' => 'users', 'action' => 'login'), array('escape' => false)); ?></div>
                    </div>
                </div>-->
            </div>
            </div>
        </div>
    </div>
</div>
