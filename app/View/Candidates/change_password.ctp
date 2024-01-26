<script>
    $().ready(function() {
        $.validator.addMethod("pass", function(value, element) {
            return  this.optional(element) || (/.{8,}/.test(value) && /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,20})/.test(value));
        }, "<?php echo __d('user', 'Password minimum length must be 8 charaters and combination of 1 special character, 1 lowercase character, 1 uppercase character and 1 number.', true);?>");
        $("#changepassword").validate();
    });
</script>
<?php echo $this->element('pstrength'); ?>
<?php // echo $this->Html->script('jquery/jquery.pstrength-min.1.2.js'); ?>
<script type="text/javascript">
    $(function() {
        $('.passcheck').pstrength();
    });
</script> 

<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                <div class="col-xs-12 col-sm-6 col-md-9 col-lg-9">

                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-aboutself-boxes"><h2><i><?php echo $this->Html->image('front/home/key-icon.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Change your password', true);?></span></h2></div>
                        <div class="information_cntn change_list_education">
                            <?php echo $this->element('session_msg'); ?>
                            
                            <?php echo $this->Form->create("Null", array('enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'changepassword', 'name' => 'changepassword')); ?>
                                <div class="form_list_education ">
                                    <label class="lable-acc"><?php echo __d('user', 'Old Password', true);?> <span class="star_red">*</span></label>
                                    <div class="form_input_education">
                                        <?php echo $this->Form->password('User.old_password', array('class' => "form-control required", 'placeholder' => __d('user', 'Old Password', true))) ?>
                                    </div>
                                </div>
                                <div class="form_list_education">
                                     <label class="lable-acc"><?php echo __d('user', 'New Password', true);?> <span class="star_red">*</span></label>
                                    <div class="form_input_education">
                                        <?php echo $this->Form->password('User.new_password', array('minlength' => '8','label' => '', 'div' => false, 'id' => 'new_password', 'class' => "form-control required passcheck", 'placeholder' => __d('user', 'New Password', true))) ?>
                                    </div>
                                </div>
                                <div class="form_list_education">
                                     <label class="lable-acc"><?php echo __d('user', 'Confirm Password', true);?> <span class="star_red">*</span></label>
                                    <div class="form_input_education">
                                        <?php echo $this->Form->password('User.conf_password', array('label' => '', 'div' => false, 'equalTo' => '#new_password', 'class' => "form-control required", 'placeholder' => __d('user', 'Confirm Password', true))) ?>
                                    </div>
                                </div>
                                
                                <div class="form_lst sssss">
                                    <span class="rltv">
                                    <div class="pro_row_left">
                                     <?php echo $this->Form->submit(__d('user', 'Update Password', true), array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                                     <?php echo $this->Html->link(__d('user', 'Cancel', true), array('controller' => 'candidates', 'action' => 'myaccount'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow')); ?>
                                    </div> 
                                    </span>
                                </div>
                            <?php echo $this->Form->end(); ?> 
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
   



