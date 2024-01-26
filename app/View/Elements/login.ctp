<?php echo $this->Html->script('jquery.validate.js'); ?>
<?php //echo $this->Html->script('jquery/jquery.pstrength-min.1.2.js'); ?>
<script type="text/javascript">
//    jQuery(document).ready(function () {
//        jQuery("#userlogin").validate();
//    });
</script>
<div class="modal-body login_modal_body">
    <?php echo $this->Form->create("Null", array('class' => 'cssForm', 'name' => 'userlogin', 'id' => 'userlogin')); ?>
    <?php echo $this->element('session_msg'); ?>
    <div class="errormsg" id="msg" style="display: none;"></div>
    <?php if ($this->Session->read('resend_link')) { ?>
        <div class="resend_act"><?php echo $this->Html->link('Click here', array('controller' => 'users', 'action' => 'resendEmail'), array()); ?> to resend activation email.</div>
    <?php } ?>

    <div class="form-group">
        <div class="row">
            <div class="col-lg-3 text-right modal_lable"><b>Email Address</b></div>
            <div class="col-lg-9 padding-right-30">
                <?php echo $this->Form->text('User.email_address', array('class' => "required email text_input_box", 'placeholder' => 'Email Address', 'id' => 'emailUser')) ?>
                <label id="label_error" for="UserEmailAddress" generated="true"></label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-lg-3 text-right modal_lable"><b>Password</b></div>
            <div class="col-lg-9 padding-right-30">
                <?php echo $this->Form->password('User.password', array('class' => "required text_input_box", 'id' => "passwordUser", 'placeholder' => 'Password')); ?>
                <label id="label_error_pas" for="UserPassword" generated="true"></label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-lg-3 text-right modal_lable"></div>
            <div class="col-lg-9 padding-right-30">
                <?php if ($this->Session->read('Userloginstatus') > 1) { ?>

                    <div class="form_lst captcha dotno">
                        <!--<label>&nbsp;</label>-->
                        <div class="rltv">
                            <div class="captcha_img">
                                <?php echo $this->Html->image($this->Html->url(array('controller' => 'users', 'action' => 'captcha'), true), array('style' => '', 'id' => 'captcha', 'vspace' => 2)); ?>
                                <a href="javascript:void(0);" onclick="document.getElementById('captcha').src = '<?php echo $this->Html->url('/users/captcha'); ?>?' + Math.round(Math.random(0) * 1000) + 1" title='Change Text'>
                                    <?php echo $this->Html->image('front/captcha_refresh.gif'); ?></a>
                            </div>
                            <div class="login_input login_input_shwow">
                                <div id="customer_captcha_wrap" class="input_bx lonin box_relative">
                                    <?php echo $this->Form->text('User.captcha', array('autocomplete' => 'off', 'id' => 'user_password', 'class' => "required", 'placeholder' => 'Security Code*')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>    

            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-lg-3 text-right modal_lable"></div>
            <div class="col-lg-9 padding-right-30">
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
                            <label for="checkboxG1" >Remember me</label>
                        </div> 
                    </span>

                    <span class="fg_lk">
                        <?php echo $this->Html->link('Forgot your password?', array('controller' => 'users', 'action' => 'forgotPassword'), array('escape' => false)); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-lg-3">&nbsp;</div>  
            <div class="col-lg-9 col-sm-9">
                <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        <?php //echo $this->Form->submit('Login', array('div' => false, 'label' => false, 'class' => 'input_btn login_btn btn btn-success'));    ?>
                        <?php //echo $this->Ajax->submit("Login", array('div' => false, 'label' => false, 'url' => array('controller' => 'users', 'action' => 'login'), 'update' => 'loginModal', 'indicator' => 'loaderID', 'class' => 'input_btn login_btn btn btn-success'));  ?>
                        <input type="submit" class="input_btn login_btn btn btn-success" onclick="login()" value="Login">
                    </div>
                    <!--<div class="col-lg-6 col-sm-6 text-right modal_lable"><a href="#">Forgot Password</a></div>   -->       
                </div>  
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>

<script type="text/javascript">


    // function login(e) {
    $("#userlogin").validate({
        submitHandler: function (form) {

            //alert($('#userlogin').serialize());
            $('#emailUser').removeClass('error');
            $('#passwordUser').removeClass('error');
            //$('#label_error').empty();
            //$('#label_error_pas').empty();
            if ($('#emailUser').val() == '' && $('#passwordUser').val() == '') {
                $('#emailUser').addClass('error');
                $('#passwordUser').addClass('error');
                e.preventDefault();
                return false;
            } else {

//            if ($('#label_error_pas').val() == '') {
//                $('#emailUser').addClass('error');
//                $('#passwordUser').addClass('error');
//                $('#error').text('Please enter a valid email address.');
//                $('#error').addClass('error');
//                // $('#label_error_pas').text('This field is required.');
//                // $('#label_error_pas').addClass('error');
//                e.preventDefault();
//            } else {
                $('#emailUser').removeClass('error');
                $('#passwordUser').removeClass('error');
//alert($('#userlogin').serialize());

                $.ajax({
                    type: 'POST',
                    url: '<?php echo HTTP_PATH; ?>/users/login_popup',
                    data: $("#userlogin").serialize(),
                    beforeSend: function () {
                        $("#loaderID").show();
                    },
                    complete: function () {
                        $("#loaderID").hide();
                    },
                    success: function (data) {
          
                        //$("#loginModal").fadeOut("slow");
                        if (data == 'employer') {
                            window.location.href = "<?php echo HTTP_PATH; ?>/users/myaccount";
                        } else if (data == 'jobseeker') {
                            window.location.href = "<?php echo HTTP_PATH; ?>/candidates/myaccount";
                        } else {
                            //alert(data);
                            $("#msg").css("display", "block");
                            $('#msg').html('<div class="error_msg error_lo">' + data + '</div>');
                            setTimeout(function () {
                                //$('.ersu_message1').remove();
                                $("#msg").css("display", "none");
                            }, 3000);
                            return false;
                            //$('#signinform').html(data);
                            // window.location.href = "<?php //echo HTTP_PATH;     ?>/homes/index";
                        }
                    },
                    error: function (data) {
                        // console.log(data);
                        //alert(JSON.stringify(data));
                    }

                    //return false; 

                });
                //}


            }


            return false;
        }
    });


</script>