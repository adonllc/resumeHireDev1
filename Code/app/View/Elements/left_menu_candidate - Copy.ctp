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
?>
<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">


    <div class="left_lnks">
        <div class="my_hd mobile_sh"><?php echo __d('user','My Profile',true);?></div>    
        <div class="my_hd profilebx"><span><?php echo __d('user','My Profile',true);?></span></div>
        <ul class="profilediv">
            <li class="<?php echo $myaccount; ?>"><?php echo $this->Html->link('<i class="fa fa-user"></i>'.__d('user','My Profile',true), array('controller' => 'candidates', 'action' => 'myaccount'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="<?php echo $editprofile; ?>"><?php echo $this->Html->link('<i class="fa fa-pencil"></i>'.__d('user','Edit Profile',true), array('controller' => 'candidates', 'action' => 'editProfile'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="<?php echo $editeducation; ?>"><?php echo $this->Html->link('<i class="fa fa-graduation-cap"></i>'.__d('user','Education',true), array('controller' => 'candidates', 'action' => 'editEducation'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="<?php echo $editexperience; ?>"><?php echo $this->Html->link('<i class="fa fa-black-tie"></i>'.__d('user','Experience',true), array('controller' => 'candidates', 'action' => 'editExperience'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="<?php echo $editprofessional; ?>"><?php echo $this->Html->link('<i class="fa fa-hand-o-right"></i>'.__d('user','Professional Registration',true), array('controller' => 'candidates', 'action' => 'editProfessional'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="<?php echo $makecv; ?>"><?php echo $this->Html->link('<i class="fa fa-file"></i>'.__d('user','Make a CV',true), array('controller' => 'candidates', 'action' => 'makecv'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
        </ul>
    </div>

    <div class="left_lnks">
        <div class="my_hd mobile_sh"><?php echo __d('user','Quick links',true);?></div>
        <div class="my_hd quickbx"><span><?php echo __d('user','Quick links',true);?></span></div>
        <ul class="quickdiv">
            <li class="<?php echo $transactionActive;  ?>"><?php echo $this->Html->link('<i class="fa fa-dollar"></i> '.__d('user', 'Payment History', true),array('controller'=>'payments','action'=>'history'),array('class'=>'', 'escape' => false,'rel'=>'nofollow'));  ?></li>
            <li class="<?php echo $alertManage; ?>"><?php echo $this->Html->link('<i class="fa fa-bell"></i> '.__d('user','Manage Alerts',true), array('controller' => 'alerts', 'action' => 'index'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
<!--            <li class="<?php //echo $shortList;  ?>"><?php //echo $this->Html->link('<i class="fa fa-heart"></i>Saved Jobs ', array('controller' => 'jobs', 'action' => 'shortList'), array('class' => '', 'escape' => false,'rel'=>'nofollow'));  ?></li>-->
            <li class="<?php echo $shortList; ?>"><?php echo $this->Html->link('<i class="fa fa-heart"></i>'.__d('user','Saved Jobs',true), array('controller' => 'jobs', 'action' => 'shortList'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class="<?php echo $appList; ?>"><?php echo $this->Html->link('<i class="fa fa-briefcase"></i>'.__d('user','Applied Jobs',true), array('controller' => 'jobs', 'action' => 'applied'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li><?php echo $this->Html->link('<i class="fa fa-search"></i>'.__d('user','Search Jobs',true), array('controller' => 'jobs', 'action' => 'listing'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
        </ul>
    </div>

    <div class="left_lnks">
        <div class="my_hd mobile_sh"><?php echo __d('user','Settings',true);?></div>    
        <div class="my_hd sattingbx"><span><?php echo __d('user','Settings',true);?></span></div>
        <ul class="sattingsdiv">
            <li class="<?php echo $mailhistory; ?>"><?php echo $this->Html->link('<i class="fa fa-envelope"></i> '.__d('user', 'Mail History', true), array('controller' => 'candidates', 'action' => 'mailhistory'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="<?php echo $changepassword; ?>"><?php echo $this->Html->link('<i class="fa fa-key"></i>'.__d('user','Change Password',true), array('controller' => 'candidates', 'action' => 'changePassword'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class="<?php echo $uploadPhoto; ?>"><?php echo $this->Html->link('<i class="fa fa-camera"></i>'.__d('user','Change Photo',true), array('controller' => 'candidates', 'action' => 'uploadPhoto'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class=""><?php echo $this->Html->link('<i class="fa fa-sign-out"></i>'.__d('home','Logout',true), array('controller' => 'users', 'action' => 'logout'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class=""><?php echo $this->Html->link('<i class="fa fa-trash"></i> '.__d('common', 'Delete Account', true), array('controller' => 'candidates', 'action' => 'deleteAccount'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
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