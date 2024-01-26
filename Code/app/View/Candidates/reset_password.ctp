<script type="text/javascript">
    jQuery(document).ready(function(){
        $.validator.addMethod('mypassword', function(value, element) {
            return this.optional(element) || (value.match(/[0-9]/));
        },
        'Password must contain at least one number.');
        
        $.validator.addMethod("pass", function(value, element) {
            return  this.optional(element) || (/.{8,}/.test(value) && /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,20})/.test(value));
        }, 
        'Password minimum length must be 8 charaters and combination of 1 special character, 1 lowercase character, 1 uppercase character and 1 number.');
        jQuery("#resetPassword").validate();
    });
</script>
<?php // echo $this->Html->script('jquery/jquery.pstrength-min.1.2.js'); ?>
<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));

?>  
<?php echo $this->element('pstrength'); ?>
<script type="text/javascript">
    jQuery(function() {
        jQuery('#password').pstrength();
    });
</script>
<div class="main_container">
    <div class="wrapper">
        <div class="content login_cnter">
            <div class="login_form">
                <div class="lefty_dv">
                    <div class="upper_hd_dv"> <span class="login_bhgy">Reset your password</span> </div>

                    <?php echo $this->Form->create(Null, array('class' => 'cssForm', 'name' => 'userlogin', 'id' => 'resetPassword')); ?>
                    <div class="form_contnr">
                        <?php echo $this->element('session_msg'); ?>
                        <div class="form_lst">
                            <span class="rltv"><?php echo $this->Form->password('User.password', array('class' => 'required pass', 'id' => "password", 'placeholder' => 'New Password')); ?></span>
                        </div>

                        <div class="form_lst">
                            <span class="rltv"><?php echo $this->Form->password('User.confirm_password', array( 'class' => 'required', "equalTo" => "#password", 'placeholder' => 'Retype Password')); ?></span>
                        </div>
                        <div class="kg_sb">
                            <?php echo $this->Form->submit('Reset Password', array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                        </div>
                    </div>
                     <?php echo $this->Form->hidden('User.id', array('value' => $userId)) ?>
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
