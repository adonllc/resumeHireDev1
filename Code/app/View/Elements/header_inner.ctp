<?php
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'title'));
$site_phone = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'phone'));
$site_info_mail = $this->requestAction(array('controller' => 'App', 'action' => 'getMailConstant', 'site_info_mail'));
$userdetail = classregistry::init('User')->find('first', array('conditions' => array('User.id' => $this->Session->read('user_id'))));
?>  
<?php
if (!isset($loginA)) {
    $loginA = '';
}
if (!isset($loginB)) {
    $loginB = '';
}

if (!isset($registerA)) {
    $registerA = '';
}
if (!isset($registerB)) {
    $registerB = '';
}

if (!isset($how_it_works)) {
    $how_it_works = '';
}
if (!isset($find_a_job)) {
    $find_a_job = '';
}

if (!isset($jobs_list)) {
    $jobs_list = '';
}
if (!isset($candidates_list)) {
    $candidates_list = '';
}
if (!isset($blogs)) {
    $blogs = '';
}

if (!isset($faq_active)) {
    $faq_active = '';
}
if (!isset($homepage)) {
    $homepage = '';
}
if (!isset($about_active)) {
    $about_active = '';
}
if (!isset($contact_us)) {
    $contact_us = '';
}
?>

<?php
if ($this->Session->read('user_id')) {
    $extra_class = "";
} else {
    $extra_class = 'header_new';
}
?>
<header>
 

    <div class="header header-inner">
          <div class="container">
            <div class="row">
                <div class="col-lg-3 col-sm-3">
                    <div class="logo">
                        <!--<a href="javascript:void(0)"><?php echo $this->Html->image('front/logo.png',array('alt'=>'')); ?> </a>-->
                        <?php 
                        $logoImage = classRegistry::init('Admin')->field('Admin.logo', array('id' => 1));
                        // pr($logoImage);
                        if (isset($logoImage) && !empty($logoImage)) {
                            $logo = DISPLAY_FULL_WEBSITE_LOGO_PATH . $logoImage;
                        } else {
                            $logo = ' ';
                        }

                        echo $this->Html->link($this->Html->image($logo, array('alt' => $site_title, 'title' => $site_title)), '/', array('escape' => false, 'rel' => 'nofollow', 'class' => ''));
//                        ?>
                    </div>
                </div>
                <div class="col-lg-9 col-sm-9">
                   
                       
                        <nav role="navigation" class="navbar navbar-expand-lg navbar-light bg-light">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <div class="toggle position-relative">
                                        <div class="line top position-absolute"></div>
                                        <div class="line middle cross1 position-absolute"></div>
                                        <div class="line middle cross2 position-absolute"></div>
                                        <div class="line bottom position-absolute"></div>
                                    </div>
                                </button>
                            </div>
                            <!-- Collection of nav links and other content for toggling -->
                            <div id="navbarSupportedContent" class="collapse navbar-collapse">
                                <ul class="navbar-nav ml-auto mr-6">
                                <li class="nav-item <?php echo $homepage; ?>"> 
                                    <?php echo $this->Html->link('' . __d('user', 'Home', true), '/', array('class' => 'nav-link ' . $homepage, 'escape' => false)); ?>
                                </li>
                                <?php
                                $about_us = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'about-us'));
                                if ($about_us == 1) {
                                    echo '<li class="nav-item ' . $about_active . '">' . $this->Html->link('' . __d('home', 'About Us', true), array('controller' => 'pages', 'action' => 'about_us'), array('rel' => 'nofollow', 'class' => 'nav-link ' . $about_active, 'escape' => false)) . '</li>';
                                }
                                if ($this->Session->read('user_id') && $this->Session->read('user_type') == 'recruiter') {
                                    ?>
                                    <li class="nav-item <?php echo $candidates_list; ?>">
                                        <?php echo $this->Html->link(__d('home', 'Jobseekers', true), array('controller' => 'candidates', 'action' => 'listing'), array('class' => 'nav-link ' . $candidates_list)); ?>
                                    </li>
                                    <?php
                                }
                                $how_it_works_page = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'how-it-works'));
                                if ($how_it_works_page == 1) {
                                    echo '<li class="nav-item ' . $how_it_works . '" id="how_it_work_div">' . $this->Html->link('' . __d('home', 'How it works', true), array('controller' => 'pages', 'action' => 'staticpage', 'how-it-works'), array('class' => 'nav-link ' . $how_it_works, 'data-toggle' => 'modal', 'data-target' => '#howworksModal', 'escape' => false)) . '</li>';
                                }
                                ?>
                                <li class="nav-item <?php echo $blogs ?>"> 
                                    <?php echo $this->Html->link('' . __d('home', 'Blog', true), array('controller' => 'blogs', 'action' => 'index'), array('class' => 'nav-link ' . $blogs, 'escape' => false)); ?>
                                </li>

                                <?php
                                $faq = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'faq'));
                                if ($faq == 1) {
                                    echo '<li class="nav-item ' . $faq_active . '">' . $this->Html->link('' . __d('home', 'FAQ', true), array('controller' => 'pages', 'action' => 'staticpage', 'faq'), array('rel' => 'nofollow', 'class' => 'nav-link ' . $faq_active, 'escape' => false)) . '</li>';
                                }
                                ?>

                                <li class="nav-item <?php echo $contact_us; ?>"><?php echo $this->Html->link('' . __d('home', 'Contact us', true), '/contact-us', array('rel' => 'nofollow', 'class' => 'nav-link ' . $contact_us, 'escape' => false)); ?></li>

                                <?php
                                $find_a_job_page = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'find-a-job'));
                                echo '<li class="nav-item ' . $find_a_job . '" id="how_it_work_div">' . $this->Html->link('<i class="fa fa-search"></i>' . __d('user', '', true), array('controller' => 'jobs', 'action' => 'listing'), array('class' => 'nav-link ' . $find_a_job, 'escape' => false)) . '</li>';
                                if ($this->Session->read('user_id')) {
                                    ?>
                                    <li class="nav-item"> 
                                        <?php
                                        if ($this->Session->read('user_type') == 'candidate') {
                                            echo $this->Html->link($userdetail['User']['first_name'], array('controller' => 'candidates', 'action' => 'myaccount'), array('class' => $loginA . ' nav-link'));
                                        } else {
                                            echo $this->Html->link($userdetail['User']['first_name'], array('controller' => 'users', 'action' => 'myaccount'), array('class' => $loginA . ' nav-link'));
                                        }
                                        ?>
                                    </li>
                                    <li class="nav-item login_link">
                                    <?php echo $this->Html->link(__d('home', 'Logout', true), array('controller' => 'users', 'action' => 'logout'), array('class' => $registerA . ' nav-link signup')); ?>
                                    </li>
                            <?php } else { ?>
                                    <li class="nav-item login-dropdown dropdown <?php echo $loginA . $loginB; ?>">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 <?php echo __d('home', 'Login', true); ?>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <?php echo $this->Html->link(__d('home', 'Jobseeker Login', true), array('controller' => 'users', 'action' => 'login'), array('class' => $loginA . ' dropdown-item signup ')); ?>
             <?php echo $this->Html->link(__d('home', 'Employer Login', true), array('controller' => 'users', 'action' => 'employerlogin'), array('class' => $loginB . ' dropdown-item signup ')); ?>

                                        </div>
                                    </li>

                                    <li class="nav-item login-dropdown dropdown  <?php echo $registerA . $registerB; ?>">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo __d('home', 'Register', true); ?>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <?php echo $this->Html->link(__d('home', 'jobseeker Sign Up', true), array('controller' => 'users', 'action' => 'register', 'jobseeker'), array('class' => $registerA . ' dropdown-item login', 'escape' => false)); ?>
                <?php echo $this->Html->link(__d('user', 'Employer Sign Up', true), array('controller' => 'users', 'action' => 'register', 'employer'), array('class' => $registerB . ' dropdown-item login', 'escape' => false)); ?>

                                        </div>
                                    </li>

                                <?php } ?>

                                <?php
                                $curl = $this->params->url;
                                ?>
                                <li class="language-fleg nav-item" style="display: none">
                                    <span class="<?php
                                    if ($_SESSION['Config']['language'] == 'en') {
                                        echo 'active';
                                    }
                                    ?>"><?php echo $this->Html->link($this->Html->image('front/uk.png'), HTTP_PATH . '/setlanguage/en?curl=' . $curl, array('escape' => false, 'title' => 'English')); ?></span>
                                    <span class="<?php
                                    if ($_SESSION['Config']['language'] == 'de') {
                                        echo 'active';
                                    }
                                    ?>"><?php echo $this->Html->link($this->Html->image('front/germany.png'), HTTP_PATH . '/setlanguage/de?curl=' . $curl, array('escape' => false, 'title' => 'German')); ?></span>
                                    <span class="<?php
                                    if ($_SESSION['Config']['language'] == 'fra') {
                                        echo 'active';
                                    }
                                    ?>"><?php echo $this->Html->link($this->Html->image('front/french.png'), HTTP_PATH . '/setlanguage/fra?curl=' . $curl, array('escape' => false, 'title' => __d('home', 'French', true))); ?></span>
                                </li>
                                <?php
                                $count = 0;
                                ?>

                                    <?php if ($this->Session->read('user_id') && $this->Session->read('user_type') == 'candidate') { ?>
                                    <li class="newbell nav-item" id="bells">
                                        <?php
                                        echo $this->Html->link('<i class="fa fa-bell"></i>', array('controller' => 'alerts', 'action' => 'index'), array('escape' => false, 'class' => ' nav-link '));
                                    }
                                    ?>            
                                </li>






                            </ul>

                            </div>
                        </nav>
                </div>
            </div>
        </div>
    </div>
</header>

<div id="howworksModal" class="modal fade howwork_header" role="dialog">
    <div class="modal-dialog howwork_dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">&times;</button>

            <div class="modal-body">
<?php echo $this->Html->image('front/Job_portal.jpg'); ?>
            </div>

        </div>

    </div>
</div>
<div id="toTop"><?php echo __d('home', 'Top', true); ?></div>
<script>
    $(window).scroll(function () {
        if ($(this).scrollTop() > 5) {
            $(".header").addClass("fixed-me");
        } else {
            $(".header").removeClass("fixed-me");
        }
    });
</script>
<script type="text/javascript">
    $(window).scroll(function () {
        if ($(this).scrollTop() > 0) {
            $('#toTop').fadeIn();
        } else {
            $('#toTop').fadeOut();
        }
    });
    $('#toTop').click(function () {
        $('body,html').animate({scrollTop: 0}, 800);
    });
</script>