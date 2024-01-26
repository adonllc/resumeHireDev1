<header class="header white-bg">
    <div class="sidebar-toggle-box">
        <div data-original-title="" data-placement="right" class="fa fa-bars tooltips"></div>
    </div>
    <!--logo start-->
    <?php //echo $this->Html->link($this->Html->image('logo.jpg', array('alt' => SITE_TITLE, 'title' => SITE_TITLE, 'width' => '200', 'height' => '60', 'style' => "")) . '<em>' . SITE_TITLE . ' : </em> <span>Administration</span>', "/admin/admins/dashboard", array('class' => 'logo', 'escape' => false)); ?>
<?php
    $site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
   
    $logoImage = classRegistry::init('Admin')->field('Admin.logo', array('id' => 1));

    if(isset($logoImage) && !empty($logoImage)){
        $logo = DISPLAY_FULL_WEBSITE_LOGO_PATH. $logoImage;
    }else{
        $logo = ' ';
    }

    echo $this->Html->link($this->Html->image($logo, array('alt' => $site_title, 'title' => $site_title)) . ' <span>Administration</span>', "/admin/admins/dashboard", array('class' => 'logo', 'escape' => false)); ?>
    <!--logo end-->
    <div class="nav notify-row" id="top_menu">
        <!--  notification start -->
        <ul class="nav top-menu">
        </ul>
        <!--  notification end -->
    </div>
    <div class="top-nav ">
        <!--search & user info start-->
        <ul class="nav pull-right top-menu">
            <li>
            </li>
            <!-- user login dropdown start-->
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <img alt="" src="<?php echo HTTP_PATH; ?>/app/webroot/img/no_user.png">
                    <span class="username"><?php echo $_SESSION['admin_username'];?></span>
                    <b class="caret"></b>
                </a>

                <ul class="dropdown-menu extended logout">
                    <div class="log-arrow-up"></div>
                    <li class="setrigh"><?php echo $this->Html->link('<i class=" fa fa-user"></i>Change Username', array('controller' => 'admins', 'action' => 'changeusername'), array('escape' => false)); ?></li>
                    <li><?php echo $this->Html->link('<i class="fa fa-lock"></i> Change Password', array('controller' => 'admins', 'action' => 'changepassword'), array('escape' => false)); ?></li>
                    <li><?php echo $this->Html->link('<i class="fa fa-envelope"></i> Change Email', array('controller' => 'admins', 'action' => 'changeemail'), array('escape' => false)); ?></li>
                    <?php /* <li><?php echo $this->Html->link('<i class="fa fa-bell-o"></i> Notification','#',array('escape'=>false));?></li> */ ?>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-key"></i> Logout', array('controller' => 'admins', 'action' => 'logout'), array('title' => 'Logout', 'class' => '', 'escape' => false)); ?>                
                    </li>
                </ul>
            </li>
            <!-- user login dropdown end -->
        </ul>
        <!--search & user info end-->
    </div>
</header>