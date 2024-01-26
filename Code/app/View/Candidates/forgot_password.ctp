<?php echo $this->Html->script('jquery/jquery.pstrength-min.1.2.js'); ?>
<?php
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'title'));
?>  
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#forgotPassword").validate();
    });
</script>
<div class="main_container">
    <div class="wrapper">
        <div class="content login_cnter">
            <div class="login_form">
                <div class="lefty_dv">
                    <div class="upper_hd_dv"> <span class="login_bhgy">Forgot Password</span> </div>

                    <?php echo $this->Form->create(Null, array('class' => 'cssForm', 'name' => 'userlogin', 'id' => 'forgotPassword')); ?>
                    <div class="form_contnr">
                        <?php echo $this->element('session_msg'); ?>

                        <b class="entro">Enter the e-mail address associated with your account. Click submit to have your password e-mailed to you.</b>

                        <div class="form_lst">
                            <span class="rltv"><?php echo $this->Form->text('User.email_address', array('class' => " required email", 'placeholder' => 'Email Address')) ?></span>
                        </div>



                        <!--                        <div class="fg_pass ">
                                                    <span class="fg_lk fulliwa">
                                                        Oops, I just remembered it! Take me back to the <?php //echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login'), array('escape' => false));  ?>
                                                    </span>
                                                </div>-->

                        <div class="kg_sb">
                            <?php echo $this->Form->submit('Submit', array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>

<!--                    <div class="righty_dv">

                        <div class="rght_mn">

                            <div class="mj_hd">Already Registered </div> 

                            <div class="lowr_txtng">If you have an account on <?php //echo $site_title; ?>?  then click below link. </div>

                            <div class="sgnup_blnk"><?php //echo $this->Html->link('Sign in!', array('controller' => 'users', 'action' => 'login'), array('escape' => false, 'rel' => 'nofollow')); ?></div>
                        </div>
                    </div>-->

                </div>

            </div>
        </div>
    </div>
</div>
