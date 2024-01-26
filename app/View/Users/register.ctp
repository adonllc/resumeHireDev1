<?php //pr($_SESSION);                    ?>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<script>
    $(function () {
        $('.chosen-select').chosen({max_selected_options: 3});

        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {

        /************************************************************/
        $.validator.addMethod("contact", function (value, element) {
            return  this.optional(element) || (/^[0-9+]+$/.test(value));
        }, "<?php echo __d('user', 'Contact Number is not valid', true); ?>.");
        $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_]+$/.test(value);
        }, "*<?php echo __d('user', 'Note: Special characters, number and spaces are not allowed', true); ?>.");
        $.validator.addMethod("pass", function (value, element) {
            return  this.optional(element) || (/.{8,}/.test(value) && /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,20})/.test(value));
        }, "<?php echo __d('user', 'Password minimum length must be 8 charaters and combination of 1 special character, 1 lowercase character, 1 uppercase character and 1 number.', true); ?>");
        $("#userRegister").validate();
        /************************************************************/

    });

</script>
<?php echo $this->element('pstrength'); ?>
<script type="text/javascript">
    $(function () {
        $('.password').pstrength();
    });
</script>

<?php
echo $this->Html->script('jquery.validate.js');
//echo $this->Html->script('jquery/jquery.pstrength-min.1.2.js');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#userRegister").validate();
        //skill
        $('#UserCategoryId_chosen').click(function () {

            //alert($('#JobCustomerId').val());
            if ($('#UserCategoryId').val() == '' || $('#UserCategoryId').val() == '0' || $('.default').val() == '<?php echo __d('user', 'Choose skills', true); ?>') {

                $('#UserCategoryId_chosen').addClass('error');
                if ($('#UserCategoryId_chosen_span').length > 0) {
                    //code here
                } else {
                    $('#UserCategoryId_chosen').append('<span id="UserCategoryId_chosen_span" class="error customer_error" for="UserCategoryId_chosen" generated="true" style="display: block;"><?php echo __d('user', 'This field is required.', true); ?></span>');
                }
            } else {

                $('#UserCategoryId_chosen').removeClass('error');
                $("#UserCategoryId_chosen_span").remove();
            }
        });
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
<?php
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'title'));
//pr($site_title); die;
?>  
<section class="slider_abouts">
    <div class="breadcrumb-container">
        <nav class="breadcrumbs page-width breadcrumbs-empty">
            <h3 class="head-title">  <?php if (isset($para['0']) && !empty($para['0']) && $para['0'] == 'jobseeker') { ?>
                    <?php echo __d('user', 'Jobseeker Sign up', true); ?>
                <?php } elseif (isset($para['0']) && !empty($para['0']) && $para['0'] == 'employer') { ?>
                    <?php echo __d('user', 'Employer Sign up', true); ?>   
                <?php } ?></h3>
             <a href="<?php echo $this->Html->url(array("controller" => "homes", "action" => 'index','')); ?>" title="Back to the frontpage"><?php echo __d('user', 'Home', true); ?></a>
            <span class="divider">/</span>
            <span> <?php echo __d('home', 'Sign Up', true) ?> </span>
        </nav>
    </div>
</section>
<section class="login">
    <div class="login-form-area pb-100 pt-100">
        <div class="container">
            <div class="use">

                <div class="content login_cnter">
                    <div class="login_form_container">

                        <?php echo $this->Form->create(Null, array('class' => 'cssForm', 'name' => 'userRegister', 'id' => 'userRegister')); ?>
                        <div class="login-form form-bg">
                           <h3><?php echo __d('home', 'Create an account', true) ?></h3>
                            <div class="form_contnrd">
                                <?php echo $this->element('session_msg'); ?>
                                <?php echo $this->Session->flash(); ?>


                                <?php
                                $display = '';
                                if (isset($para['0']) && !empty($para['0']) && $para['0'] == 'employer') {
                                    $display = 'block';
                                    echo $this->Form->hidden('User.user_type', array('value' => "recruiter"));
                                } elseif (isset($para['0']) && !empty($para['0']) && $para['0'] == 'jobseeker') {
                                    $display = 'none';
                                    echo $this->Form->hidden('User.user_type', array('value' => "candidate"));
                                } else {
                                    $display = 'none';
                                }
                                ?>

                                <div class="form_lst" id="ccname" style="display:<?php echo $display; ?>" >
                                    <div class="rltv">
                                        <div class="info-field">
                                            <div class="form_lst_row">
                                                <?php echo $this->Form->text('User.company_name', array('class' => "required form-control", 'placeholder' => __d('user', 'Company Name', true))) ?>
                                            </div>
                                        </div>
                                    </div>                                                       
                                </div>
                                <div class="form_lst">
                                    <div class="rltv">
                                        <div class="info-field">
                                            <div class="form_lst_row">
                                                <?php echo $this->Form->text('User.first_name', array('maxlength' => '20', 'class' => "required form-control validname", 'placeholder' => __d('user', 'First Name', true))) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form_lst">
                                    <div class="rltv">
                                        <div class="info-field">
                                            <div class="form_lst_row">
                                                <?php echo $this->Form->text('User.last_name', array('maxlength' => '20', 'class' => "required form-control validname", 'placeholder' => __d('user', 'Last Name', true))) ?>
                                            </div>
                                        </div>
                                    </div>                    
                                </div>
                                <div class="form_lst">
                                    <div class="rltv">
                                        <div class="info-field">
                                            <div class="form_lst_row">
                                                <?php echo $this->Form->text('User.email_address', array('class' => "required email form-control", 'placeholder' => __d('user', 'Email Address', true))) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form_lst">
                                    <div class="rltv">
                                        <div class="info-field">
                                            <div class="form_lst_row">
                                                <?php echo $this->Form->password('User.password', array('minlength' => '8', 'id' => 'password', 'class' => "password required form-control", 'placeholder' => __d('user', 'Password', true))) ?>
                                            </div>
                                        </div>
                                    </div>       
                                </div>
                                <div class="form_lst">
                                    <div class="rltv">
                                        <div class="info-field">
                                            <div class="form_lst_row">
                                                <?php echo $this->Form->password('User.confirm_password', array('equalTo' => '#password', 'class' => "form-control required", 'placeholder' => __d('user', 'Confirm Password', true))) ?>
                                            </div>
                                        </div>
                                    </div>       
                                </div>
                                <?php if (isset($para['0']) && !empty($para['0']) && $para['0'] == 'jobseeker') { ?>
                                    <div class="form_lst choose-list">
                                        <!--<label><?php // echo __d('user', 'Interest Categories', true);   ?></label>-->
                                        <div id="cust_skl" class="rltv rel_Skills category_drop">
                                            <div class="info-field">
                                                <?php echo $this->Form->select('User.interest_categories', $categories, array('multiple' => true, 'data-placeholder' => __d('user', 'Select Interest Categories', true), 'class' => "chosen-select form-control")); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- <div class="form_lst captcha dotno">
                                    <div class="col_devide">
                                        <div class="form_lst">
                                            <div class="">
                                                <?php //echo $this->Html->image($this->Html->url(array('controller' => 'users', 'action' => 'captcha'), true), array('style' => '', 'id' => 'captcha', 'vspace' => 2)); ?>
                                                <a href="javascript:void(0);" onclick="document.getElementById('captcha').src = '<?php// echo $this->Html->url('/users/captcha'); ?>?' + Math.round(Math.random(0) * 1000) + 1" title='Change Text'>
                                                    <?php //echo $this->Html->image('front/captcha_refresh.gif'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col_devide">
                                        <div class="info-field">
                                            <div class="form_lst_row login_input login_input_shwow">
                                                <div id="customer_captcha_wrap" class="input_bx lonin box_relative">
                                                    <?php //echo $this->Form->text('User.captcha', array('autocomplete' => 'off', 'id' => 'user_password', 'class' => "required form-control", 'placeholder' => __d('user', 'Security Code', true))); ?>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>                                                       
                                </div> -->
                                <div class="form_lst">
                                    <div class="rltv">
                                        <div class="">
                                            <div id="recaptcha_register" style="    transform: scale(0.8);-webkit-transform: scale(0.8);transform-origin: 0 0;-webkit-transform-origin: 0 0;"></div>
                                            <div class="gcpc" id="captcha_msg"></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form_lst dotno">
                                <div class="btn-green login-buttons curtainup register-buttons">
                                    <?php echo $this->Form->submit(__d('user', 'Submit', true), array('div' => false, 'label' => false, 'class' => 'btn_same btn btn-primary')); ?>
                                    <input class="btn btn-secondary" id="reset" type="reset" value="<?php echo __d('user', 'Reset', true) ?>">
                                </div>
                                </div>
                                <div class="row_defaultt">
                                    <div class="col_devide_full reun_takine">
                                        <?php
                                        $terms_and_conditions = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'terms-and-conditions'));
                                        if ($terms_and_conditions == 1) {
                                            echo __d('user', 'By signing up, you agree to', true) . ' ' . $site_title;
                                            ?> <a href="javascript:void(0);" onclick="window.open('<?php echo HTTP_PATH ?>/terms_and_conditions', 'term', 'width=900,height=400,scrollbars=1')" class="term_cond link_color" id="errorterms"> <?php echo __d('user', 'Terms and Conditions', true); ?></a>
                                            <?php
                                        }
                                        ?>    
                                    </div>
                                </div>
                                <div class="row_defaultt">
                                    <div class="col_devide_full reun_takine"><?php echo __d('user', 'Already a member', true); ?>?
                                        <?php
                                        if (isset($para['0']) && !empty($para['0']) && $para['0'] == 'employer') {

                                            echo $this->Html->link(__d('user', 'Sign In Here', true), array('controller' => 'users', 'action' => 'employerlogin'), array('class' => ''));
                                            ?>

                                            <?php
                                        } else if (isset($para['0']) && !empty($para['0']) && $para['0'] == 'jobseeker') {

                                            echo $this->Html->link(__d('user', 'Sign In Here', true), array('controller' => 'users', 'action' => 'login'), array('class' => ''));
                                            ?>

                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

