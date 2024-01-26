<?php //echo $this->Html->script('jquery/jquery.pstrength-min.1.2.js');         ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        
             $.validator.addMethod("email", function (value, element) {
            return  this.optional(element) || (/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)(\s+)?$/.test(value));
        }, "<?php echo __d('user', 'Email Address is not valid', true);?>");
        
        jQuery("#employeruserlogin").validate();
    });
</script>
<section class="slider_abouts">
    <div class="breadcrumb-container">
       <nav class="breadcrumbs page-width breadcrumbs-empty">
            <h3 class="head-title"><?php echo __d('user', 'Employer Sign In', true);?></h3>
            <a href="<?php echo $this->Html->url(array("controller" => "homes", "action" => 'index','')); ?>" title="Back to the frontpage"><?php echo __d('user', 'Home', true) ?></a>
            <span class="divider">/</span>
            <span> <?php echo __d('home', 'Login', true) ?> </span>
        </nav>
    </div>
</section>
<section class="login">
    <div class="login-form-area pb-100 pt-100">
    <div class="container">

        <div class="use">
            <div class="content login_cnter">
                <div class="login_form_container">
                    <?php echo $this->element('session_msg'); ?>
                    <?php echo $this->Session->flash(); ?>
                   
                    <div class="login-form form-bg">
                       <h3><?php echo __d('home', 'Login', true) ?></h3>
                        <!--------social ends------------>
                        <?php echo $this->Form->create(Null, array('class' => 'cssForm', 'name' => 'userlogin', 'id' => 'employeruserlogin')); ?>
                        <div class="form_contnrd">
                            <?php //echo $this->element('session_msg'); ?>
                            <?php //echo $this->Session->flash(); ?>

                            <?php if ($this->Session->read('resend_link')) { ?>
                                <div class="resend_act"><?php echo $this->Html->link(__d('user', 'Click here', true), array('controller' => 'users', 'action' => 'resendEmail'), array()); ?> <?php echo __d('user', 'to resend activation email', true);?>.</div>
                            <?php }
                            ?>
                            <div class="form_lst">
                                <div class="rltv">
                                    <div class="info-field">
                                        <?php echo $this->Form->text('User.email_address', array('class' => "required email form-control", 'placeholder' => __d('user', 'Email Address', true))) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form_lst">
                                <div class="rltv">
                                    <div class="info-field">
                                        <?php echo $this->Form->password('User.password', array('class' => "required form-control", 'id' => "password", 'placeholder' => __d('user', 'Password', true))); ?>
                                    </div>
                                </div>
                            </div>
                            <?php if ($this->Session->read('Userloginstatus') > 1) { ?>
                                <div class="form_lst captcha dotno">
                                    <div class="rltv">
                                        <div class="captcha_img">
                                            <?php echo $this->Html->image($this->Html->url(array('controller' => 'users', 'action' => 'captcha'), true), array('style' => '', 'id' => 'captcha', 'vspace' => 2)); ?>
                                            <a href="javascript:void(0);" onclick="document.getElementById('captcha').src = '<?php echo $this->Html->url('/users/captcha'); ?>?' + Math.round(Math.random(0) * 1000) + 1" title='Change Text'>
                                                <?php echo $this->Html->image('front/captcha_refresh.gif'); ?></a>
                                        </div>
                                        <div class="login_input login_input_shwow">
                                            <div id="customer_captcha_wrap" class="input_bx lonin box_relative">

                                                <?php echo $this->Form->text('User.captcha', array('autocomplete' => 'off', 'id' => 'user_password', 'class' => "required", 'placeholder' => __d('user', 'Security Code', true))); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>   

                            <div class="form_lst dotno">
                                <span class="rmmr_me">
                                    <div class="checkbox2">

                                        <?php
                                        $checked = '';
                                        if (isset($_COOKIE["cookname"]) && isset($_COOKIE["cookpass"])) {
                                            $checked = 'checked';
                                        }
                                        echo $this->Form->checkbox('User.rememberme', array('class' => 'css-checkbox', 'id' => 'checkboxG1', $checked));
                                        ?>
                                        <label for="checkboxG1" ><?php echo __d('user', 'Remember me', true);?></label>
                                    </div> 
                                </span>

<!--                                <span class="fg_lk" data-toggle="modal" data-target="#forgotpassword">
                                <?php //echo $this->Html->link('Forgot your password?', array('controller' => 'users', 'action' => 'forgotPassword'), array('escape' => false,'rel'=>'nofollow')); ?>
                                    <a class="" onclick="offpop()">Forgot your password?</a>
                                </span>-->

                                <span class="fg_lk" data-toggle="modal">
                                    <?php echo $this->Html->link(__d('user', 'Forgot your password?', true), array('controller' => 'users', 'action' => 'forgotPassword'), array('escape' => false, 'rel' => 'nofollow')); ?>
                                    <!--                                    <a class="" onclick="offpop()">Forgot your password?</a>-->
                                </span>
                            </div>
                            <div class="form_lst dotno">
                                 <div class="btn-green login-buttons curtainup mb-1 mt-2">
                                    <?php echo $this->Form->hidden('User.user_type', array('value' => "recruiter", 'id' => "user_type")); ?>

                                    <?php echo $this->Form->submit(__d('user', 'Login', true), array('div' => false, 'label' => false, 'class' => 'btn_same btn btn-primary')); ?>
                                </div>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>




                   
                </div>




            </div>

        </div>
        </div>
    </div>
</section>
