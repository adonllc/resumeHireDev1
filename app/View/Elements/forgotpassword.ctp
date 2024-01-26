<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#forgotPasswordpop").validate({
            submitHandler: function (form) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo HTTP_PATH; ?>/users/forgot_popup",
                    data: $("#forgotPasswordpop").serialize(),
                    dataType: "text",
                    beforeSend: function () {
                        $('#logbtn').hide();
                        $('#loaderID').show();
                    },
                    success: function (data) {
                            $('#loaderID').hide();
                            var obj = jQuery.parseJSON( data );
                            var msg = obj.msg;
                            var joclass = obj.classh;
                            $('#msgjoh').html('<div class="'+joclass+'">'+msg+'</div>');
                            return false;
                            
                    }
                });
            }
        });
    });
</script>
<?php
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'title'));
//pr($data); die;
?>
<div class="modal-body login_modal_body">
    <div id="loaderID" style="display:none;position:absolute;margin-top:250px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
    <?php echo $this->Form->create(Null, array('class' => 'cssForm', 'name' => 'userlogin', 'id' => 'forgotPasswordpop')); ?>
    <?php echo $this->element('session_msg'); ?>
    
    <div class="entro">Enter the e-mail address associated with your account. Click submit to have your password e-mailed to you.</div>
  
<div class="errormsg" id="msgjoh"></div>
    <div class="form-group">
        <div class="row">
            <div class="col-lg-3 text-right modal_lable"><b>Email Address</b></div>
            <div class="col-lg-9 padding-right-30">
                <?php echo $this->Form->text('User.email_address', array('id' => 'userEmail', 'class' => "required email text_input_box", 'placeholder' => 'Email Address')) ?>
                <!--                <label id="label_error" for="UserEmailAddress" generated="true"></label>-->
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-lg-3">&nbsp;</div>  
            <div class="col-lg-9 col-sm-9">
                <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        <?php echo $this->Form->submit('Submit', array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                        <?php //echo $this->Ajax->submit("Login", array('div' => false, 'label' => false, 'url' => array('controller' => 'users', 'action' => 'login'), 'update' => 'loginModal', 'indicator' => 'loaderID', 'class' => 'input_btn login_btn btn btn-success'));  ?>
<!--                        <input type="submit" class = "input_btn login_btn btn btn-success" onclick="login()" value="Login">-->

                    </div>
                    <!--<div class="col-lg-6 col-sm-6 text-right modal_lable"><a href="#">Forgot Password</a></div>   -->       
                </div>  
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
