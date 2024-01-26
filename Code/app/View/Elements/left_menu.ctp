<?php
if (!isset($editprofile)) {
    $editprofile = '';
}
if (!isset($mailhistory)) {
    $mailhistory = '';
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
if (!isset($employee)) {
    $employee = '';
}
if (!isset($income)) {
    $income = '';
}
if (!isset($jobsActive)) {
    $jobsActive = '';
}
if (!isset($transactionActive)) {
    $transactionActive = '';
}
if (!isset($favoriteList)) {
    $favoriteList = '';
}
if (!isset($jobsCreate)) {
    $jobsCreate = '';
}
if (!isset($importList)) {
    $importList = '';
}
//if (!isset($alertManage)) {
//    $alertManage = '';
//}
?>
<div class="col-lg-3 col-sm-3"> 
    <div class="left_lnks">
        <div class="my_hd mobile_sh"><?php echo __d('user', 'Quick links', true);?><span class="menu-icons"><?php echo $this->Html->image('front/home/menu-icon.png', array('alt' => '')); ?></span></div>
        <div class="my_hd quickbx2"><span><?php echo __d('user', 'Quick links', true);?></span></div>
       <ul class="quickdiv2">
<!--            <li class="<?php //echo $alertManage;  ?>"><?php //echo $this->Html->link('<i class="fa fa-plus"></i> Manage Alerts ', array('controller' => 'alerts', 'action' => 'index'), array('class' => '', 'escape' => false,'rel'=>'nofollow'));  ?></li>-->
<!--            <li class="<?php //echo $jobsCreate;  ?>"><?php //echo $this->Html->link('<i class="fa fa-plus"></i> Create Job ', array('controller' => 'jobs', 'action' => 'selectType'), array('class' => '', 'escape' => false,'rel'=>'nofollow'));  ?></li>-->
            <li class="my-creat-tab <?php echo $jobsCreate; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/creat-job-icon.png', array('alt' => '')).'</i><span>'.__d('user', 'Create Job', true).'</span>', array('controller' => 'jobs', 'action' => 'createJob'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class="my-manage-job-tab <?php echo $jobsActive; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/manage-job-icon.png', array('alt' => '')).'</i><span>'.__d('user', 'Manage Jobs', true).'</span>', array('controller' => 'jobs', 'action' => 'management'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class="my-paypanethistory-tab <?php echo $transactionActive;  ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/payment-icon.png', array('alt' => '')).'</i><span>'.__d('user', 'Payment History', true).'</span>',array('controller'=>'payments','action'=>'history'),array('class'=>'', 'escape' => false,'rel'=>'nofollow'));  ?></li>
            <li class="my-favorites-tab <?php echo $favoriteList; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/seved-icon.png', array('alt' => '')).'</i><span>'.__d('user', 'Favorites List', true).'</span>', array('controller' => 'candidates', 'action' => 'favorite'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class="my-change-logo-tab <?php echo $importList; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/paper.png', array('alt' => '')).'</i><span>'.__d('user', 'Import Jobseekers', true).'</span>', array('controller' => 'users', 'action' => 'import'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
<!--            <li><a href="#"><i class="fa fa-download"></i>Download Resume</a></li> -->
        </ul>
    </div>
    <div class="left_lnks">
        <div class="my_hd mobile_sh"><?php echo __d('user', 'My Dashboard', true);?></div>    
        <div class="my_hd dashboardsbx"><span><?php echo __d('user', 'My Dashboard', true);?></span></div>
        <ul class="dashboardsdiv">
            <li class="my-mail-history-tab <?php echo $mailhistory; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/mail-history-icon.png', array('alt' => '')).'</i><span>'.__d('user', 'Mail History', true).'</span>', array('controller' => 'users', 'action' => 'mailhistory'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="my-profiles-tab <?php echo $myaccount; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/my-profile-icon.png', array('alt' => '')).'</i><span>'.__d('user', 'My Profile', true).'</span>', array('controller' => 'users', 'action' => 'myaccount'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="my-edit-tab <?php echo $editprofile; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/edit-icon.png', array('alt' => '')).'</i><span>'.__d('user', 'Edit Profile', true).'</span>', array('controller' => 'users', 'action' => 'editProfile'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?> </li> 
            <li class="my-change-pass-tab <?php echo $changepassword; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/change-pass-icon.png', array('alt' => '')).'</i><span>'.__d('user', 'Change Password', true).'</span>', array('controller' => 'users', 'action' => 'changePassword'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class="my-change-logo-tab <?php echo $uploadPhoto; ?>"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/change-photo-icon.png', array('alt' => '')).'</i><span>'.__d('user', 'Change Logo', true).'</span>', array('controller' => 'users', 'action' => 'uploadPhoto'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class="my-logout-tab"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/logout-icon.png', array('alt' => '')).'</i><span>'.__d('home', 'Logout', true).'</span>', array('controller' => 'users', 'action' => 'logout'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
            <li class="my-delete-tab"><?php echo $this->Html->link('<i>'.$this->Html->image('front/home/delete-assount-icon.png', array('alt' => '')).'</i><span>'.__d('common', 'Delete Account', true).'</span>', array('controller' => 'users', 'action' => 'deleteAccount'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?></li>
        </ul>
    </div>

    
</div>

<script>
    $(document).ready(function () {

        $('.quickbx2').click(function () {

            if ($('.quickbx2').hasClass('quicklinkopen2')) {
                $('.quickbx2').removeClass('quicklinkopen2');
            } else {
                $('.quickbx2').addClass('quicklinkopen2');
            }
            $(".quickdiv2").slideToggle();
        });

        $('.dashboardsbx').click(function () {

            if ($('.dashboardsbx').hasClass('dashboardsopen')) {
                $('.dashboardsbx').removeClass('dashboardsopen');
            } else {
                $('.dashboardsbx').addClass('dashboardsopen');
            }

            $(".dashboardsdiv").slideToggle();
        });

    });
</script>