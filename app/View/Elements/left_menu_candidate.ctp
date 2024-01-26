<?php
if (!isset($editprofile)) {
    $editprofile = '';
}
if (!isset($mailhistory)) {
    $mailhistory = '';
}
if (!isset($editeducation)) {
    $editeducation = '';
}
if (!isset($editexperience)) {
    $editexperience = '';
}
if (!isset($editprofessional)) {
    $editprofessional = '';
}
if (!isset($myaccount)) {
    $myaccount = '';
}
if (!isset($changepassword)) {
    $changepassword = '';
}
if (!isset($uploadPhoto)) {
    $uploadPhoto = '';
}

if (!isset($experienceActive)) {
    $experienceActive = '';
}
if (!isset($manageCertificate)) {
    $manageCertificate = '';
}
if (!isset($shortList)) {
    $shortList = '';
}
if (!isset($appList)) {
    $appList = '';
}
if (!isset($alertManage)) {
    $alertManage = '';
}
if (!isset($makecv)) {
    $makecv = '';
}
if (!isset($transactionActive)) {
    $transactionActive = '';
}
if(!isset($videocv)){
    $videocv = '';
}
?>
<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 left-menumain">
    <div class="left_lnks">
        <div class="my_hd mobile_sh"><?php echo __d('user','My Profile',true);?> <span class="menu-icons"><?php echo $this->Html->image('front/home/menu-icon.png', array('alt' => '')); ?></span></div>    
        <div class="my_hd profilebx"><span><?php echo __d('user','My Profile',true);?></span></div>
        <ul class="profilediv">
            <li class="my-profiles-tab <?php echo $myaccount; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/my-profile-icon.png', array('alt' => '')).'</i><span>'.__d('user','My Profile',true).'</span>', array('controller' => 'candidates', 'action' => 'myaccount'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="edit-profiles-tab <?php echo $editprofile; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/edit-icon.png', array('alt' => '')).'</i><span>'.__d('user','Edit Profile',true).'</span>', array('controller' => 'candidates', 'action' => 'editProfile'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="my-education-tab <?php echo $editeducation; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/education-acc-icon.png', array('alt' => '')).'</i><span>'.__d('user','Education',true).'</span>', array('controller' => 'candidates', 'action' => 'editEducation'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="my-experience-tab <?php echo $editexperience; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/experience-acc-icon.png', array('alt' => '')).'</i><span>'.__d('user','Experience',true).'</span>', array('controller' => 'candidates', 'action' => 'editExperience'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="my-professional-tab <?php echo $editprofessional; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/professional-icon.png', array('alt' => '')).'</i><span>'.__d('user','Professional Registration',true).'</span>', array('controller' => 'candidates', 'action' => 'editProfessional'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="my-videocv-tab <?php echo $videocv; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/video-cv-icon.png', array('alt' => '')).'</i><span>'.__d('user','Video CV',true).'</span>', array('controller' => 'candidates', 'action' => 'addVideoCv'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="my-mackcv-tab <?php echo $makecv; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/make-v-icon.png', array('alt' => '')).'</i><span>'.__d('user','Make a CV',true).'</span>', array('controller' => 'candidates', 'action' => 'makecv'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
        </ul>
    </div>
    <div class="left_lnks">
        <div class="my_hd mobile_sh"><?php echo __d('user','Quick links',true);?></div>
        <div class="my_hd quickbx"><span><?php echo __d('user','Quick links',true);?></span></div>
        <ul class="quickdiv">
            
              <li class="my-manage-job-tab <?php echo $planactive; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/choices.png', array('alt' => '')).'</i><span>'.__d('user', 'Purchase Plan', true).'</span>', array('controller' => 'plans', 'action' => 'purchase'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
           
            <li class="my-paypanethistory-tab <?php echo $transactionActive;  ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/payment-icon.png', array('alt' => '')).'</i><span>'.__d('user', 'Payment History', true).'</span>',array('controller'=>'payments','action'=>'history'),array('class'=>'', 'escape' => false,'rel'=>'nofollow'));  ?></li>
            <li class="my-manage-tab <?php echo $alertManage; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/manage-icon.png', array('alt' => '')).'</i><span>'.__d('user','Manage Alerts',true).'</span>', array('controller' => 'alerts', 'action' => 'index'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
<!--            <li class="<?php //echo $shortList;  ?>"><?php //echo $this->Html->link('<i class="fa fa-heart"></i>Saved Jobs ', array('controller' => 'jobs', 'action' => 'shortList'), array('class' => '', 'escape' => false,'rel'=>'nofollow'));  ?></li>-->
            <li class="my-savedjob-tab <?php echo $shortList; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/seved-icon.png', array('alt' => '')).'</i><span>'.__d('user','Saved Jobs',true).'</span>', array('controller' => 'jobs', 'action' => 'shortList'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class="my-applied-tab <?php echo $appList; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/applied-icon.png', array('alt' => '')).'</i><span>'.__d('user','Applied Jobs',true).'</span>', array('controller' => 'jobs', 'action' => 'applied'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class="my-search-tab "><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/search-icon.png', array('alt' => '')).'</i><span>'.__d('user','Search Jobs',true).'</span>', array('controller' => 'jobs', 'action' => 'listing'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
        </ul>
    </div>
    <div class="left_lnks">
        <div class="my_hd mobile_sh"><?php echo __d('user','Settings',true);?></div>    
        <div class="my_hd sattingbx"><span><?php echo __d('user','Settings',true);?></span></div>
        <ul class="sattingsdiv">
            <li class="my-mailhistory-tab <?php echo $mailhistory; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/mail-history-icon.png', array('alt' => '')).'</i><span>'.__d('user', 'Mail History', true).'</span>', array('controller' => 'candidates', 'action' => 'mailhistory'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="my-changepass-tab <?php echo $changepassword; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/change-pass-icon.png', array('alt' => '')).'</i><span>'.__d('user','Change Password',true).'</span>', array('controller' => 'candidates', 'action' => 'changePassword'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class="my-changephoto-tab <?php echo $uploadPhoto; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/change-photo-icon.png', array('alt' => '')).'</i><span>'.__d('user','Change Photo</span>',true).'</span>', array('controller' => 'candidates', 'action' => 'uploadPhoto'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class="my-sign-tab "><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/logout-icon.png', array('alt' => '')).'</i><span>'.__d('home','Logout',true).'</span>', array('controller' => 'users', 'action' => 'logout'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class="my-trash-tab "><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/delete-assount-icon.png', array('alt' => '')).'</i><span>'.__d('common', 'Delete Account', true).'</span>', array('controller' => 'candidates', 'action' => 'deleteAccount'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
        </ul>
    </div>
</div>
<script>
            $(document).ready(function () {
                $('.profilebx').click(function () {
                    if ($('.profilebx').hasClass('profileopen')) {
                        $('.profilebx').removeClass('profileopen');
                    } else {
                        $('.profilebx').addClass('profileopen');
                    }
                    $(".profilediv").slideToggle();
                });

                $('.quickbx').click(function () {

                    if ($('.quickbx').hasClass('quicklinkopen')) {
                        $('.quickbx').removeClass('quicklinkopen');
                    } else {
                        $('.quickbx').addClass('quicklinkopen');
                    }
                    $(".quickdiv").slideToggle();
                });

                $('.sattingbx').click(function () {

                    if ($('.sattingbx').hasClass('sattingsopen')) {
                        $('.sattingbx').removeClass('sattingsopen');
                    } else {
                        $('.sattingbx').addClass('sattingsopen');
                    }

                    $(".sattingsdiv").slideToggle();
                });

            });
        </script>
<script>
       $(".menu-icons").click(function(){
  $(".left-menumain").addClass("left-menumain-show");
}); 
        </script>
        
        <script type="text/javascript">
            $(document).ready(function () {
                $('.menu-icons').click(function () {
                    if ($('.left-menumain').hasClass('left-menumain-show')) {
                        $('.left-menumain').removeClass('left-menumain-show');
                    } else {
                        $('.left-menumain').addClass('left-menumain-show');
                    }
                    $(".ftblock2").slideToggle();
                });
            });
        </script>