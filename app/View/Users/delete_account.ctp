<?php echo $this->Html->script('jquery.validate.js'); ?>
<script>
    $(document).ready(function() {
     $("#accountdelete").validate();
    });
</script>


<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
     <div class="container">
        <div class="row">
            <div class="my_acc">
                <?php echo $this->element('left_menu'); ?>
               <div class="col-lg-9">

                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top"><h2><i><?php echo $this->Html->image('front/home/delete-assount-icon2.png', array('alt' => '')); ?></i><span><?php echo __d('common', 'Delete Account', true);?></span></h2></div>
                        <div class="information_cntn">
                            <?php echo $this->element('session_msg'); ?>
                            
                            <?php echo $this->Form->create("Null", array('enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'accountdelete', 'name' => 'accountdelete')); ?>
                                <div class="form_list_education">
                               <label class="lable-acc"><?php echo __d('common', 'Reason for leaving', true);?> <div class="star_red">*</div></label>
                                <div id="locDiv" class="form_input_education">
                                    <?php echo $this->Form->textarea('User.reason', array('class' => "form-control required","minlength"=>"30", 'placeholder' => __d('user', 'Reason for leaving', true))) ?>
                                    <label id="abouttext"></label>
                                </div>
                            </div>
                                
                                <div class="form_lst sssss">
                                    <span class="rltv">
                                    <div class="pro_row_left">
                                     <?php echo $this->Form->submit(__d('common', 'Delete account', true), array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                                     <?php echo $this->Html->link(__d('user', 'Cancel', true), array('controller' => 'users', 'action' => 'myaccount'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow')); ?>
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
</div>