<?php 
if (!isset($myaccount)) {
    $myaccount = '';
}
if (!isset($editprofile)) {
    $editprofile = '';
}
if (!isset($changepassword)) {
    $changepassword = '';
}
if (!isset($uploadPhoto)) {
    $uploadPhoto = '';
}
if (!isset($membership)) {
    $membership = '';
}
if (!isset($upgradeAccount)) {
    $upgradeAccount = '';
}
if (!isset($job)) {
    $job = '';
}
if (!isset($job_history)) {
    $job_history = '';
}
if (!isset($load)) {
    $load = '';
}

if (!isset($vacancy)) {
    $vacancy = '';
}

$userdetail = classregistry::init('User')->find('first', array('conditions' => array('User.id' => $this->Session->read('user_id'))));
?>

<div class="deshboard_left_box">
            	<div class="des_box_full_bbox">
                   <div class="user_img_box">
                       <?php
                                $profile_image = $userdetail['User']['profile_image'];
                                $path = UPLOAD_THUMB_PROFILE_IMAGE_PATH . $profile_image;
                                if (file_exists($path) && !empty($profile_image)) {
                                    echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $profile_image, array('escape' => false));
                                } else {
                                    echo $this->Html->image('front/no_image_user.png');
                                }
                                ?>
                       
                    </div>
                    
                    
                    <div class="user_pro_biot_box">
                        <h1><?php echo $userdetail['User']['first_name'].' '.$userdetail['User']['last_name'];?></h1>
                        <div class="clear"></div>
                        
                        <span class="us_detai_box"><?php echo $userdetail['User']['email_address'];?></span>
                        <span class="us_detai_box"><?php echo $this->Text->usformat($userdetail['User']['contact']);?></span>
                        
                        <div class="change_picture_ic">                        
                            <?php echo $this->Html->link('Change Picture', array("controller" => "users", "action" => 'uploadPhoto'),array('class' =>'edit_icon')); ?>
                        </div>
                    </div>
                </div>
                <div  class="clear"></div>
                
                <div class="my_int_box">
                	<div class="my_int_box_header">
                    	My Strategies
                    </div>
                    <div class="my_int_box_content">
                    	<span class="clr_int">Interest paid to Investors:</span>
                        <div class="clear"></div>
                        
                        <span class="da_dolor_tupe">$59,17,848</span>
                    </div>
                </div>
            </div>

 