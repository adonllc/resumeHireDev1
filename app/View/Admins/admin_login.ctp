<script type="text/javascript">
    $(document).ready(function(){
        $("#adminLogin").validate();
        $("#adminForgotpassword").validate();
//        $('.cancel').click(function(){
//            window.location.href = "<?php echo HTTP_PATH; ?>/admin/admins/login";
//        })
        
        
    });
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>


<script>
//      var recaptcha1;
    var recaptcha_register;
    var myCallBack = function () {
        //Render the recaptcha1 on the element with ID "recaptcha1"
        if ($("#recaptcha_register").length > 0) {
            recaptcha_register = grecaptcha.render('recaptcha_register', {
                'sitekey': '<?php echo CAPTCHA_KEY; ?>', //Replace this with your Site key
                'theme': 'light'
            });
        }

    };
</script>
<div class="container">
    <?php echo $this->Form->create('Admin', array("url" => "login", 'enctype' => 'multipart/form-data', 'name' => 'adminLogin', 'id' => 'adminLogin', 'class' => 'form-signin')); ?>
    <h2 class="form-signin-heading"> Administration Login </h2>
    <?php if (isset($forgot_hidden) && $forgot_hidden != 0) { ?>
        <?php echo $this->Session->flash(); ?>
    <?php } ?>
    <div class="login-wrap">
        <?php echo $this->Form->text('username', array('label' => false, 'id' => 'login', 'div' => false, 'class' => "form-control required", 'Placeholder' => 'Username', 'autofocus')); ?>
        <?php echo $this->Form->password('password', array('label' => false, 'id' => 'pass', 'div' => false, 'class' => "form-control required", 'Placeholder' => 'Password')); ?>


        <div class="">
            <div id="recaptcha_register" style="    transform: scale(0.8);-webkit-transform: scale(0.8);transform-origin: 0 0;-webkit-transform-origin: 0 0;"></div>
            <div class="gcpc" id="captcha_msg"></div>
        </div>

        <label class="checkbox">
            <p> <?php echo $this->Form->checkbox('Admin.keep', array('id' => "keep-logged", 'value' => "1")); ?> Remember me </p>
            <span class="pull-right">
                <?php //echo $this->Html->link('Forgot Password?', '#myModal', array('data-toggle' => 'modal')); ?>

            </span>
        </label>

        <?php echo $this->Form->submit('Sign in', array('class' => 'btn btn-lg btn-login btn-block', 'label' => '', 'div' => false, 'name' => 'loginform')) ?>

    </div>
    <?php echo $this->Form->end(); ?>
    <!-- Modal -->
    <?php echo $this->Form->create('Admin', array('enctype' => 'multipart/form-data', 'name' => 'adminForgotpassword', 'id' => 'adminForgotpassword', 'class' => 'form-signin form-signin-new')); ?>
    <div aria-hidden="<?php
    if (isset($forgot_hidden) && $forgot_hidden == 0) {
        echo 'false';
    } else {
        echo 'true';
    }
    ?>" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal <?php
         if (isset($forgot_hidden) && $forgot_hidden == 0) {
             echo 'fade in';
         } else {
             echo 'fade';
         }
    ?>" style="display:<?php
         if (isset($forgot_hidden) && $forgot_hidden == 0) {
             echo 'block;';
         } else {
             echo 'none;';
         }
    ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <?php echo $this->Form->button('&times;', array('class' => 'close cancel', 'data-dismiss' => 'modal', 'aria-hidden' => 'true', 'label' => '', 'div' => false)) ?>
                    <h4 class="modal-title">Forgot Password ?</h4>
                </div>

                <?php if (isset($forgot_hidden) && $forgot_hidden == 0) { ?>
                    <?php echo $this->Session->flash(); ?>
                <?php } ?>
                <div class="modal-body">
                    <p>Enter your e-mail address below to reset your password.</p>
                    <?php echo $this->Form->text('email', array('size' => '25', 'id' => 'recovery-mail', 'autocomplete' => 'off', 'label' => false, 'div' => false, 'placeholder' => 'Email', 'class' => "form-control placeholder-no-fix email required")); ?>
                </div>
                
               <?php 
                $adminQuestion = Classregistry::init('Admin')->findById(1);
               ?>
                <div class="modal-body se_que">
                    <p><?php echo $adminQuestion['Admin']['question1'];?></p>
                    <?php echo $this->Form->text('answer1', array( 'autocomplete' => 'off', 'label' => false, 'div' => false, 'placeholder' => 'Answer', 'class' => "form-control placeholder-no-fix required")); ?>
                </div>
                <div class="modal-body se_que">
                    <p><?php echo $adminQuestion['Admin']['question2'];?></p>
                    <?php echo $this->Form->text('answer2', array( 'autocomplete' => 'off', 'label' => false, 'div' => false, 'placeholder' => 'Answer', 'class' => "form-control placeholder-no-fix required")); ?>
                </div>
                
                <div class="modal-footer">
                    <?php echo $this->Form->button('Cancel', array('class' => 'btn btn-default  cancel', 'data-dismiss' => 'modal', 'label' => '', 'div' => false)) ?>
                    <?php echo $this->Form->submit('Submit', array('class' => 'btn btn-success', 'label' => '', 'div' => false, 'name' => 'forgotform')) ?>
                </div>
            </div>
        </div>
    </div>
    <!-- modal -->

    <?php echo $this->Form->end(); ?>
    <div class="powered_by">
        <div class="powered_tital">Powered by</div>
        <div class="powered_logo"><a href="https://www.logicspice.com/" target="_blenk"><?php echo $this->Html->image('front/logicspice-logo.png');?></a></div>
    </div>
</div>
<?php if (isset($forgot_hidden) && $forgot_hidden == 0) { ?>
    <div class="modal-backdrop fade in"></div>
<?php } ?>