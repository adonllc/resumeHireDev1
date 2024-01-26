<?php echo $this->Html->script('jquery/jquery.pstrength-min.1.2.js'); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#forgotPassword").validate();
    });
</script>
<?php
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'title'));
//pr($data); die;

?>
<style>
    a.login_text{
        color: blue;
        background-color: transparent;
        text-decoration: none;
    }
</style>
<section class="slider_abouts">
    <div class="breadcrumb-container">
        <nav class="breadcrumbs page-width breadcrumbs-empty">
            <h3 class="head-title"><?php echo __d('user', 'Forgot Password', true);?></h3>
            <a href="<?php echo $this->Html->url(array("controller" => "homes", "action" => 'index','')); ?>" title="Back to the frontpage"><?php echo __d('user', 'Home', true) ?></a>
            <span class="divider">/</span>
            <span><?php echo __d('user', 'Forgot Password', true);?></span>
        </nav>
    </div>
</section>
<section class="login">
    <div class="login-form-area pb-100 pt-100">
        <div class="container">
            <div class="use">
        <div class="content login_cnter">
            <div class="login_form_container">
            
            <div class="login-form form-bg">
                <h3><?php echo __d('user', 'Forgot Password', true);?></h3>
                    <?php echo $this->Form->create(Null, array('class' => 'cssForm', 'name' => 'userlogin', 'id' => 'forgotPassword')); ?>
                    <div class="form_contnrd">
                        <?php echo $this->element('session_msg'); ?>

                        <div class="entro"><?php echo __d('user', 'Enter the e-mail address associated with your account. Click submit to have your password e-mailed to you.', true);?></div>

                        <div class="form_lst">
                            <div class="rltv">
                                <div class="info-field">
                                    <?php echo $this->Form->text('User.email_address', array('class' => "form-control required email", 'placeholder' => __d('user', 'Email Address', true))) ?>
                                </div>

                                <span class="fg_lk fulliwasd">
                                    

                                    <li data-toggle="modal">
                                        <?php echo __d('user', 'Oops, I just remembered it! Take me back to the', true);?> <?php echo $this->Html->link(__d('user', 'Login', true), $this->request->referer(), array('class' => 'login_text')); //array('controller' => 'users', 'action' => 'login')?>
                                    </li>
                                </span>

                            </div>
                        </div>




                        <div class="form_lst dotno">
                            <div class="btn-green login-buttons">
                                <?php echo $this->Form->submit(__d('user', 'Submit', true), array('div' => false, 'label' => false, 'class' => 'btn_same btn btn-primary')); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
               
                <!-- <div class="righty_dv">
                 <h2>How does it work?</h2>
                 <p>3 easy steps to get free AutoFixter Quotes!</p>
                 <div class="staps">
                 <ul>
                 <li><a href="#">Upload Resume
                 <span>Upload your updated resume to here.</span>
                 </a></li>
                 <li><a href="#">RECEIVE job alerts
                 <span>You will receive the job alerts.</span>
                 </a></li>
                 <li><a href="#">get your opportunity
                 <span>Choose the best job and take your opportunity!</span>
                 </a></li>
                 
                 </ul>
                 </div>-->

                <!--<div class="rght_mn">

                    <div class="mj_hd">Need An Account</div> 

                    <div class="lowr_txtng">If you don't have an account on <?php //echo SITE_TITLE;     ?>?  then click below link. </div>

                    <div class="sgnup_blnk"><?php //echo $this->Html->link('Create Free Account', array('controller' => 'users', 'action' => 'register'), array('escape' => false));      ?></div>
                </div>
            </div>
        </div>-->
            </div>
        </div>
        </div>
    </div>
</div>
    </div>
</section>
