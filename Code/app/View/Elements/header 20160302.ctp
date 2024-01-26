<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
?>  


<?php

if (!isset($loginA)) {
    $loginA = '';
}

if (!isset($registerA)) {
    $registerA = '';
}

if (!isset($how_it_works)) {
    $how_it_works = '';
}

if (!isset($jobs_list)) {
    $jobs_list = '';
}
if (!isset($candidates_list)) {
    $candidates_list = '';
}
?>

<?php
    $userdetail = classregistry::init('User')->find('first', array('conditions' => array('User.id' => $this->Session->read('user_id'))));
?>
<header>
    <div class="wrapper">
        <div class="header_left">
            <div class="logo">
                <?php

                    $logoImage = classRegistry::init('Admin')->field('Admin.logo', array('id' => 1));
                    // pr($logoImage);
                    if(isset($logoImage) && !empty($logoImage)){
                        $logo = DISPLAY_FULL_WEBSITE_LOGO_PATH. $logoImage;
                    }else{
                        $logo = ' ';
                    }
                
                    echo $this->Html->link($this->Html->image($logo, array('height' => 100, 'width' => 350), array('alt' => $site_title, 'title' => $site_title)), '/', array('escape' => false, 'class' => ''));
                ?>
            </div>
        </div>
        <div class="header_right">
            <div class="dev_menu"></div>
            <nav>
                <ul>
                    <li><?php echo $this->Html->link('Job Search', '/', array('class' => $jobs_list)); ?></li>

                    <?php if ($this->Session->read('user_id') && $this->Session->read('user_type') == 'recruiter') { ?>
                    <li>
                            <?php echo $this->Html->link('Jobseekers', array('controller' => 'candidates', 'action' => 'listing'), array('class' => $candidates_list)); ?>
                    </li>
                    <?php } ?>

                    <li id="how_it_work_div"> <?php echo $this->Html->link("How $site_title Works",array('controller'=>'homes','action'=>'index#howitworks'), array('class' => $how_it_works)); ?></li>
                    <?php if ($this->Session->read('user_id')) { ?>
                    <li> <?php
                    if ($this->Session->read('user_type') == 'candidate') {
                        echo $this->Html->link($userdetail['User']['first_name'], array('controller' => 'candidates', 'action' => 'myaccount'), array('class' => ''));
                    } else {
                        echo $this->Html->link($userdetail['User']['first_name'], array('controller' => 'users', 'action' => 'myaccount'), array('class' => ''));
                    }
                        ?></li>
                    <li class="login_link"> <?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('class' => '')); ?></li>
                    <?php } else { ?>
                    <li> <?php echo $this->Html->link('Sign up', array('controller' => 'users', 'action' => 'register'), array('class' => $registerA)); ?></li>
                    <li class=""> <?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login'), array('class' => $loginA)); ?></li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
</header>