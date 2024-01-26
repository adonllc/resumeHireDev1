<?php
/*
 * Admin Menu
 */
$admin_list = '';
if (!isset($changeemail)) {
    $changeemail = '';
} else {
    $admin_list = 'display: block;';
}
if (!isset($change_paypal)) {
    $change_paypal = '';
} else {
    $admin_list = 'display: block;';
}
if (!isset($changeusername)) {
    $changeusername = '';
} else {
    $admin_list = 'display: block;';
}
if (!isset($changeutranslation)) {
    $changeutranslation = '';
} else {
    $admin_list = 'display: block;';
}
if (!isset($changepassword)) {
    $changepassword = '';
} else {
    $admin_list = 'display: block;';
}

$swearmenu = '';

if (!isset($swearlist)) {
    $swearlist = '';
} else {
    $swearmenu = 'display: block;';
}
if (!isset($addswear)) {
    $addswear = '';
} else {
    $swearmenu = 'display: block;';
}

$announcementmenu = '';

if (!isset($announcementlist)) {
    $announcementlist = '';
} else {
    $announcementmenu = 'display: block;';
}
if (!isset($addannouncement)) {
    $addannouncement = '';
} else {
    $announcementmenu = 'display: block;';
}
$keywordmenu = '';

if (!isset($keywordlist)) {
    $keywordlist = '';
} else {
    $keywordmenu = 'display: block;';
}
if (!isset($jobslist)) {
    $jobslist = '';
} else {
    $keywordmenu = 'display: block;';
}
if (!isset($requestslist)) {
    $requestslist = '';
} else {
    $keywordmenu = 'display: block;';
}
$skillmenu = '';
if (!isset($skilllist)) {
    $skilllist = '';
} else {
    $skillmenu = 'display: block;';
}
if (!isset($addskill)) {
    $addskill = '';
} else {
    $skillmenu = 'display: block;';
}

$designationmenu = '';
if (!isset($designationlist)) {
    $designationlist = '';
} else {
    $designationmenu = 'display: block;';
}
if (!isset($adddesignation)) {
    $adddesignation = '';
} else {
    $designationmenu = 'display: block;';
}

$locationmenu = '';
if (!isset($locationlist)) {
    $locationlist = '';
} else {
    $locationmenu = 'display: block;';
}
if (!isset($addlocation)) {
    $addlocation = '';
} else {
    $locationmenu = 'display: block;';
}

$currenciesmenu = '';
if (!isset($currencieslist)) {
    $currencieslist = '';
} else {
    $currenciesmenu = 'display: block;';
}
if (!isset($addcurrencies)) {
    $addcurrencies = '';
} else {
    $currenciesmenu = 'display: block;';
}

if (!isset($change_contactemail)) {
    $change_contactemail = '';
} else {
    $admin_list = 'display: block;';
}
if (!isset($change_text)) {
    $change_text = '';
} else {
    $admin_list = 'display: block;';
}
if (!isset($invoices)) {
    $invoices = '';
} else {
    $admin_list = 'display: block;';
}

if (!isset($uploadLogo)) {
    $uploadLogo = '';
} else {
    $admin_list = 'display: block;';
}

if (!isset($changefav)) {
    $changefav = '';
} else {
    $admin_list = 'display: block;';
}

if (!isset($meta)) {
    $meta = '';
} else {
    $admin_list = 'display: block;';
}

if (!isset($subadmin_list)) {
    $subadmin_list = '';
} else {
    $admin_list = 'display: block;';
}
if (!isset($smtpsetting)) {
    $smtpsetting = '';
} else {
    $admin_list = 'display: block;';
}

if (!isset($questionS)) {
    $questionS = '';
} else {
    $admin_list = 'display: block;';
}
if (!isset($planS)) {
    $planS = '';
} else {
    $admin_list = 'display: block;';
}

$user_active = '';
if (!isset($user_list)) {
    $user_list = '';
} else {
    $user_active = 'display: block;';
}
if (!isset($add_user)) {
    $add_user = '';
} else {
    $user_active = 'display: block;';
}
if (!isset($selectslider_list)) {
    $selectslider_list = '';
} else {
    $user_active = 'display: block;';
}

$candidate_active = '';
if (!isset($candidate_list)) {
    $candidate_list = '';
} else {
    $candidate_active = 'display: block;';
}
if (!isset($add_candidate)) {
    $add_candidate = '';
} else {
    $candidate_active = 'display: block;';
}

/*
 * Category Menu
 */
$category_active = '';
if (!isset($category_list)) {
    $category_list = '';
} else {
    $category_active = 'display: block;';
}
if (!isset($add_category)) {
    $add_category = '';
} else {
    $category_active = 'display: block;';
}
/*
 * Industry Menu
 */
$industry_active = '';
if (!isset($industry_list)) {
    $industry_list = '';
} else {
    $industry_active = 'display: block;';
}
if (!isset($add_industry)) {
    $add_industry = '';
} else {
    $industry_active = 'display: block;';
}


$payment_active = '';
if (!isset($payment_list)) {
    $payment_list = '';
} else {
    $payment_active = 'display: block;';
}

/*
 * Country menu
 */
$country_active = '';
if (!isset($country_list)) {
    $country_list = '';
} else {
    $country_active = 'display: block;';
}
if (!isset($add_country)) {
    $add_country = '';
} else {
    $country_active = 'display: block;';
}
/*
 * Pages Menu
 */
$page_active = '';
if (!isset($page_list)) {
    $page_list = '';
} else {
    $page_active = 'display: block;';
}

/*
 * Job menu
 */
$jobs_active = '';
if (!isset($jobs_list)) {
    $jobs_list = '';
} else {
    $jobs_active = 'display: block;';
}

if (!isset($add_job)) {
    $add_job = '';
} else {
    $jobs_active = 'display: block;';
}

/*
 * Promo Code Menu
 */

$promo_active = '';
if (!isset($promo_list)) {
    $promo_list = '';
} else {
    $promo_active = 'display: block;';
}
if (!isset($add_promo)) {
    $add_promo = '';
} else {
    $promo_active = 'display: block;';
}



/*
 * Banner Menu
 */

$banner_active = '';
if (!isset($banner_list)) {
    $banner_list = '';
} else {
    $banner_active = 'display: block;';
}
if (!isset($add_banner)) {
    $add_banner = '';
} else {
    $banner_active = 'display: block;';
}



/*
 * settings Menu
 */

$settings_active = '';
if (!isset($settings)) {
    $settings = '';
} else {
    $settings_active = 'display: block;';
}

if (!isset($site_setting)) {
    $site_setting = '';
} else {
    $settings_active = 'display: block;';
}

if (!isset($mail_setting)) {
    $mail_setting = '';
} else {
    $settings_active = 'display: block;';
}
if (!isset($manage_mail)) {
    $manage_mail = '';
} else {
    $settings_active = 'display: block;';
}




$template_active = '';
if (!isset($manage_template)) {
    $manage_template = '';
} else {
    $template_active = 'display: block;';
}


$dashboard_list = '';
if (!isset($dashboard)) {
    $dashboard = '';
    $dashboardClass = "";
} else {

    $dashboard_list = 'display: block;';
}


/* --Course menu-- */
$course_active = '';
if (!isset($course_list)) {
    $course_list = '';
} else {
    $course_active = 'display: block;';
}
if (!isset($add_course)) {
    $add_course = '';
} else {
    $course_active = 'display: block;';
}

/* --Blogs menu-- */
$blog_active = '';
if (!isset($blog_list)) {
    $blog_list = '';
} else {
    $blog_active = 'display: block;';
}
if (!isset($add_blog)) {
    $add_blog = '';
} else {
    $blog_active = 'display: block;';
}

/* --Slider menu-- */
$slider_active = '';
if (!isset($slider_list)) {
    $slider_list = '';
} else {
    $slider_active = 'display: block;';
}
if (!isset($add_slider)) {
    $add_slider = '';
} else {
    $slider_active = 'display: block;';
}

/*
 * Newsletter
 */
$newsletter_list = '';
if (!isset($list_subscriber)) {
    $list_subscriber = '';
} else {
    $newsletter_list = 'display: block;';
}

if (!isset($list_newsletter)) {
    $list_newsletter = '';
} else {
    $newsletter_list = 'display: block;';
}
if (!isset($send_newsletter)) {
    $send_newsletter = '';
} else {
    $newsletter_list = 'display: block;';
}
if (!isset($sent_mail)) {
    $sent_mail = '';
} else {
    $newsletter_list = 'display: block;';
}
if (!isset($unsubscriberlist)) {
    $unsubscriberlist = '';
} else {
    $newsletter_list = 'display: block;';
}

$adminRols = ClassRegistry::init('Admin')->getAdminRoles($this->Session->read('adminid'));
$adminLId = $this->Session->read('adminid');

$checkSubRols = ClassRegistry::init('Admin')->getAdminRolesSub($this->Session->read('adminid'));

?>

<aside>
    <div id="sidebar"  class="nav-collapse " style="overflow:auto!important;">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">


            <li class="sub-menu <?php if (!empty($dashboard_list)) {  echo "dcjq-parent-li"; } ?>">
                <?php echo $this->Html->link('<i class="fa fa-dashboard"></i><span> Dashboard</span>', array('controller' => 'admins', 'action' => 'dashboard'), array('class' => $dashboard, 'escape' => false)); ?>
            </li>

            <!--Configuration menu start-->
            <li class="sub-menu <?php if (!empty($admin_list)) { echo "dcjq-parent-li";}?>">
                <a href="javascript:void(0);" <?php
            if (!empty($admin_list)) {
                echo "class='dcjq-parent active'";
            }
                ?>>
                    <i class="fa fa-gears"></i>
                    <span>Configuration </span>
                </a>
                <ul class="sub" <?php echo $admin_list; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-user"></i><span> Change Username</span>', array('controller' => 'admins', 'action' => 'changeusername'), array('class' => $changeusername, 'escape' => false)); ?>
                    </li>
                   
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-lock"></i><span> Change Password</span>', array('controller' => 'admins', 'action' => 'changepassword'), array('class' => $changepassword, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-envelope"></i><span> Change Email</span>', array('controller' => 'admins', 'action' => 'changeemail'), array('class' => $changeemail, 'escape' => false)); ?>
                    </li>
                    
                    <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols)) { ?>
<!--                     <li>
                        <?php // echo $this->Html->link('<i class="fa fa-file-text"></i><span> Translation</span>', array('controller' => 'admins', 'action' => 'translation'), array('class' => $changeutranslation, 'escape' => false)); ?>
                    </li>-->
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-question"></i><span> Security Questions </span>', array('controller' => 'admins', 'action' => 'securityQuestions'), array('class' => $questionS, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-dollar"></i><span> Manage Plans </span>', array('controller' => 'plans', 'action' => 'index/'), array('class' => $planS, 'escape' => false));  ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa  fa-map-marker"></i><span> Set Contact Us Address</span>', array('controller' => 'admins', 'action' => 'settings'), array('class' => $change_contactemail, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa  fa-file-text"></i><span> Slogan Text</span>', array('controller' => 'admins', 'action' => 'changeSlogan'), array('class' => $change_text, 'escape' => false)); ?>
                    </li>
                    <!--                    <li>
                    <?php //echo $this->Html->link('<i class="fa fa-lock"></i><span>Manage Invoice Fields</span>', array('controller' => 'admins', 'action' => 'invoice'), array('class' => $invoices, 'escape' => false));   ?>
                                        </li>-->
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-picture-o"></i><span>Change Logo</span>', array('controller' => 'admins', 'action' => 'uploadLogo'), array('class' => $uploadLogo, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-money"></i><span>Change Payment Detail</span>', array('controller' => 'admins', 'action' => 'changePaymentdetail'), array('class' => $change_paypal, 'escape' => false)); ?>
                    </li>

                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-compass"></i><span>Change Favicon</span>', array('controller' => 'admins', 'action' => 'changeFavicon'), array('class' => $changefav, 'escape' => false)); ?>
                    </li>

                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-code"></i><span>Meta Management</span>', array('controller' => 'admins', 'action' => 'metaManagement'), array('class' => $meta, 'escape' => false)); ?>
                    </li>
                    
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span>Manage Sub Admins</span>', array('controller' => 'admins', 'action' => 'manage'), array('class' => $subadmin_list, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-cog"></i><span>SMTP Settings</span>', array('controller' => 'smtpsettings', 'action' => 'configuration'), array('class' => $smtpsetting, 'escape' => false)); ?>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <!--Configuration menu end-->

            <!--settings menu start-->
            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols)) { ?>
            <li class="sub-menu <?php
                        if (!empty($settings_active)) {
                            echo "dcjq-parent-li";
                        }
                        ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($settings_active)) {
                echo "class='dcjq-parent active'";
            }
                        ?>>
                    <i class="fa fa-cog"></i>
                    <span>Setting </span>
                </a>
                <ul class="sub" <?php echo $settings_active; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Site Setting</span>', array('controller' => 'settings', 'action' => 'siteSettings'), array('class' => $site_setting, 'escape' => false)); ?>
                    </li>

                    <!--<li>
                    <?php //echo $this->Html->link('<i class="fa fa-envelope-o"></i><span> Add Mail Setting</span>', array('controller' => 'settings', 'action' => 'addMails'), array('class' => $mail_setting, 'escape' => false));   ?>
                    </li>-->

                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-envelope-square"></i><span> Manage Email Setting</span>', array('controller' => 'settings', 'action' => 'manageMails'), array('class' => $manage_mail, 'escape' => false)); ?>
                    </li>

                </ul>
            </li>
            <?php } ?>
            <!--settings menu end-->
            <!--Employer menu end-->
            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 1)) { ?>
            <li class="sub-menu <?php if (!empty($user_active)) { echo "dcjq-parent-li"; } ?>">
                <a href="javascript:void(0);" <?php if (!empty($user_active)) { echo "class='dcjq-parent active'";} ?>>
                    <i class="fa fa-user"></i>
                    <span>Employers </span>
                </a>
                <ul class="sub" <?php echo $user_active; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Employer List</span>', array('controller' => 'users', 'action' => 'index'), array('class' => $user_list, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php 
                        if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 1, 1)){ 
                            echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Employer</span>', array('controller' => 'users', 'action' => 'addusers'), array('class' => $add_user, 'escape' => false)); 
                        }?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-plus"></i><span> Home Page Slider</span>', array('controller' => 'users', 'action' => 'selectforslider'), array('class' => $selectslider_list, 'escape' => false)); ?>
                    </li>
                </ul>
            </li>
            <?php } ?>
            <!--Employer menu end-->
            <!--Jobseeker menu start-->
            
            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 2)) { ?>
            <li class="sub-menu <?php  if (!empty($candidate_active)) { echo "dcjq-parent-li"; }?>">
                <a href="javascript:void(0);" <?php if (!empty($candidate_active)) { echo "class='dcjq-parent active'";}?>>
                    <i class="fa fa-users"></i>
                    <span>Jobseekers</span>
                </a>
                <ul class="sub" <?php echo $candidate_active; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Jobseekers List</span>', array('controller' => 'candidates', 'action' => 'index'), array('class' => $candidate_list, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php 
                        if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 2, 1)){ 
                            echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Jobseekers</span>', array('controller' => 'candidates', 'action' => 'addcandidates'), array('class' => $add_candidate, 'escape' => false));
                        } ?>
                    </li>
                </ul>
            </li>
            <?php } ?>
            <!--jobseeker menu end-->
            <!------Country Starts-------->
            <?php /*<li class="sub-menu <?php
                        if (!empty($country_active)) {
                            echo "dcjq-parent-li";
                        }
                        ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($country_active)) {
                echo "class='dcjq-parent active'";
            }
                        ?>>
                    <i class="fa fa-globe"></i>
                    <span>Countries </span>
                </a>
                <ul class="sub" <?php echo $country_active; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Country List</span>', array('controller' => 'countries', 'action' => 'index'), array('class' => $country_list, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Country</span>', array('controller' => 'countries', 'action' => 'addcountry'), array('class' => $add_country, 'escape' => false)); ?>
                    </li>
                </ul>
            </li> */?>

            <!------Country Ends-------->
            <!------Category starts-------->
            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 4)) { ?>
            <li class="sub-menu <?php if (!empty($category_active)) { echo "dcjq-parent-li";} ?>">
                <a href="javascript:void(0);" <?php if (!empty($category_active)) { echo "class='dcjq-parent active'";} ?>>
                    <i class="fa fa-sitemap"></i>
                    <span>Categories </span>
                </a>
                <ul class="sub" <?php echo $category_active; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Categories List</span>', array('controller' => 'categories', 'action' => 'index'), array('class' => $category_list, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php 
                        if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 4, 1)){ 
                            echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Category</span>', array('controller' => 'categories', 'action' => 'addcategory'), array('class' => $add_category, 'escape' => false)); 
                        }?>
                    </li>
                </ul>
            </li>
            <?php } ?>
            <!------Category ends-------->

            <!--            <li class="sub-menu <?php
                        // if (!empty($industry_active)) {
                        //    echo "dcjq-parent-li";
                        //}
                        ?>">
                            <a href="javascript:void(0);" <?php
            //if (!empty($industry_active)) {
            // echo "class='dcjq-parent active'";
            // }
                        ?>>
                                <i class="fa  fa-list-ol"></i>
                                <span>Job Industries </span>
                            </a>
                            <ul class="sub" <?php //echo $industry_active;    ?>>
                                <li>
            <?php //echo $this->Html->link('<i class="fa fa-list"></i><span> Industries List</span>', array('controller' => 'industries', 'action' => 'index'), array('class' => $industry_list, 'escape' => false));  ?>
                                </li>
                                <li>
            <?php //echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Industry</span>', array('controller' => 'industries', 'action' => 'addindustry'), array('class' => $add_industry, 'escape' => false));  ?>
                                </li>
                            </ul>
                        </li>-->

            <!------Swear starts-------->
            
            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols)) { ?>
            
            <li class="sub-menu <?php
            if (!empty($swearmenu)) {
                echo "dcjq-parent-li";
            }
            ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($swearmenu)) {
                echo "class='dcjq-parent active'";
            }
            ?>>
                    <i class="fa fa-file-word-o"></i>
                    <span>Swear Words</span>
                </a>
                <ul class="sub" <?php echo $swearmenu; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span>Swear Words List</span>', array('controller' => 'swears', 'action' => 'index'), array('class' => $swearlist, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Swear Words</span>', array('controller' => 'swears', 'action' => 'addswears'), array('class' => $addswear, 'escape' => false)); ?>
                    </li>
                </ul>
            </li> 
            <?php } ?>
            <!------Swear ends-------->

            <!------Skill starts-------->
            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 6)) { ?>
            <li class="sub-menu <?php if (!empty($skillmenu)) { echo "dcjq-parent-li"; } ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($skillmenu)) {
                echo "class='dcjq-parent active'";
            }
                        ?>>
                    <i class="fa fa-laptop"></i>
                    <span>Skills</span>
                </a>
                <ul class="sub" <?php echo $skillmenu; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span>Skills List</span>', array('controller' => 'skills', 'action' => 'index'), array('class' => $skilllist, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php
                        if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 6, 1)){ 
                            echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Skills</span>', array('controller' => 'skills', 'action' => 'addskills'), array('class' => $addskill, 'escape' => false));
                        }?>
                    </li>
                </ul>
            </li> 
             <?php } ?>
            <!------Skill ends-------->
            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 7)) { ?>
            <li class="sub-menu <?php
                        if (!empty($designationmenu)) {
                            echo "dcjq-parent-li";
                        }
                        ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($designationmenu)) {
                echo "class='dcjq-parent active'";
            }
                        ?>>
                    <i class="fa fa-graduation-cap"></i>
                    <span>Designations</span>
                </a>
                <ul class="sub" <?php echo $designationmenu; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span>Designations List</span>', array('controller' => 'designations', 'action' => 'index'), array('class' => $designationlist, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php 
                        if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 7, 1)){
                            echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Designations</span>', array('controller' => 'designations', 'action' => 'adddesignations'), array('class' => $adddesignation, 'escape' => false)); 
                        } ?>
                    </li>
                </ul>
            </li> 
            <?php } ?>

            <?php /* if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 11)) { ?>
            <li class="sub-menu <?php
                        if (!empty($locationmenu)) {
                            echo "dcjq-parent-li";
                        }
                        ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($locationmenu)) {
                echo "class='dcjq-parent active'";
            }
                        ?>>
                    <i class="fa fa-map-marker"></i>
                    <span>Locations</span>
                </a>
                <ul class="sub" <?php echo $locationmenu; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span>Locations List</span>', array('controller' => 'locations', 'action' => 'index'), array('class' => $locationlist, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php 
                        if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 11, 1)){
                            echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Locations</span>', array('controller' => 'locations', 'action' => 'addlocations'), array('class' => $addlocation, 'escape' => false)); 
                        }
                        ?>
                    </li>
                </ul>
            </li> 
            <?php } */ ?>

            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 3)) { ?>
            <li class="sub-menu <?php
                        if (!empty($jobs_active)) {
                            echo "dcjq-parent-li";
                        }
                        ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($jobs_active)) {
                echo "class='dcjq-parent active'";
            }
                        ?>>
                    <i class="fa fa-suitcase"></i>
                    <span>Jobs </span>
                </a>
                <ul class="sub" <?php echo $jobs_active; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Jobs List</span>', array('controller' => 'jobs', 'action' => 'index'), array('class' => $jobs_list, 'escape' => false)); ?>
                    </li>

                    <li>
                        <?php 
                        if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 3, 1)){
                            echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Job</span>', array('controller' => 'jobs', 'action' => 'beforeAddJob'), array('class' => $add_job, 'escape' => false));
                        }?>
                    </li>

                </ul>
            </li>
            <?php } ?>

            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols)) { ?>

             <li class="sub-menu <?php
                         if (!empty($payment_active)) {
                             echo "dcjq-parent-li";
                         }
                        ?>">
                   <a href="javascript:void(0);" <?php
               if (!empty($payment_active)) {
                 echo "class='dcjq-parent active'";
              }
                        ?>>
                       <i class="fa fa-dollar"></i>
                       <span>Payment History </span>
                   </a>
                   <ul class="sub" <?php echo $payment_active;         ?>>
                       <li>
                            <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Transaction List</span>', array('controller' => 'payments', 'action' => 'history'), array('class' => $payment_list, 'escape' => false));    ?>
                       </li>
                   </ul>
               </li>
            <?php } ?>

             <li class="sub-menu <?php
                        if (!empty($currenciesmenu)) {
                            echo "dcjq-parent-li";
                        }
                        ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($currenciesmenu)) {
                echo "class='dcjq-parent active'";
            }
                        ?>>
                    <i class="fa fa-money"></i>
                    <span>Currency</span>
                </a>
                <ul class="sub" <?php echo $currenciesmenu; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span>Currency List</span>', array('controller' => 'currencies', 'action' => 'index'), array('class' => $currencieslist, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php 
                            echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Currency</span>', array('controller' => 'currencies', 'action' => 'add'), array('class' => $addcurrencies, 'escape' => false)); 
                        
                        ?>
                    </li>
                </ul>
            </li>
   
             <!--    <li class="sub-menu <?php
            //  if (!empty($promo_active)) {
            //      echo "dcjq-parent-li";
            //   }
            ?>">
                   <a href="javascript:void(0);" <?php
            //if (!empty($promo_active)) {
            // echo "class='dcjq-parent active'";
            // }
            ?>>
                       <i class="fa  fa-gift"></i>
                       <span>Promo Codes </span>
                   </a>
                   <ul class="sub" <?php //echo $promo_active;         ?>>
                       <li>
            <?php //echo $this->Html->link('<i class="fa fa-list"></i><span> Promo Code List</span>', array('controller' => 'promoCodes', 'action' => 'index'), array('class' => $promo_list, 'escape' => false));    ?>
                       </li>
                       <li>
            <?php //echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Promo Code</span>', array('controller' => 'promoCodes', 'action' => 'addpromocode'), array('class' => $add_promo, 'escape' => false));    ?>
                       </li>
                   </ul>
               </li> -->

            <!----News letter menu---->
            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols)) { ?>
            <li class="sub-menu <?php
            if (!empty($newsletter_list)) {
                echo "dcjq-parent-li";
            }
            ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($newsletter_list)) {
                echo "class='dcjq-parent active'";
            }
            ?>>
                    <i class="fa fa-list"></i>
                    <span>Manage Newsletter </span>
                </a>
                <ul class="sub" <?php echo $newsletter_list; ?>>
                    <!-- <li>
                    <?php //echo $this->Html->link('<i class="fa fa-list"></i><span> Newsletter Subscriber</span>', array('controller' => 'newslettersubscribers', 'action' => 'index/'), array('class' => $list_subscriber, 'escape' => false));  ?>
                    </li>-->
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> List Newsletter</span>', array('controller' => 'newsletters', 'action' => 'index/'), array('class' => $list_newsletter, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Send Newsletter Email</span>', array('controller' => 'newsletters', 'action' => 'sendNewsletter/'), array('class' => $send_newsletter, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Email Logs</span>', array('controller' => 'newsletters', 'action' => 'sentMail/'), array('class' => $sent_mail, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Unsubscribe User List</span>', array('controller' => 'newsletters', 'action' => 'unsubscriberlist/'), array('class' => $unsubscriberlist, 'escape' => false)); ?>
                    </li>
                </ul>
            </li>
            
            <!---banner menu----->

            <li class="sub-menu <?php
                        if (!empty($banner_active)) {
                            echo "dcjq-parent-li";
                        }
                        ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($banner_active)) {
                echo "class='dcjq-parent active'";
            }
                        ?>>
                    <i class="fa  fa-file-image-o"></i>
                    <span>Banner Advertisement </span>
                </a>
                <ul class="sub" <?php echo $banner_active; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Banner List</span>', array('controller' => 'banneradvertisements', 'action' => 'index'), array('class' => $banner_list, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Banner</span>', array('controller' => 'banneradvertisements', 'action' => 'addBanneradvertisement'), array('class' => $add_banner, 'escape' => false)); ?>
                    </li>
                </ul>
            </li>
            <?php } ?>
            
            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 8)) { ?>

            <!------Course Starts-------->
            <li class="sub-menu <?php
                        if (!empty($course_active)) {
                            echo "dcjq-parent-li";
                        }
                        ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($course_active)) {
                echo "class='dcjq-parent active'";
            }
                        ?>>
                    <i class="fa fa-graduation-cap"></i>
                    <span>Course </span>
                </a>
                <ul class="sub" <?php echo $course_active; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Course List</span>', array('controller' => 'courses', 'action' => 'index'), array('class' => $course_list, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php 
                        if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 8, 1)){
                            echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Course</span>', array('controller' => 'courses', 'action' => 'addcourse'), array('class' => $add_course, 'escape' => false));
                        } ?>
                    </li>
                </ul>
            </li>
            <?php } ?>
            
            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 10)) { ?>
            <!------Course Ends-------->



            <li class="sub-menu <?php
                        if (!empty($page_active)) {
                            echo "dcjq-parent-li";
                        }
                        ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($page_active)) {
                echo "class='dcjq-parent active'";
            }
                        ?>>
                    <i class="fa fa-file-text-o"></i>
                    <span>Contents </span>
                </a>
                <ul class="sub" <?php echo $page_active; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Pages List</span>', array('controller' => 'pages', 'action' => 'index', ''), array('class' => $page_list, 'escape' => false)); ?>
                    </li>
                </ul>
            </li>
            <?php } ?>
            
            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 9)) { ?>

            <!--email template menu start-->

            <li class="sub-menu <?php
                        if (!empty($template_active)) {
                            echo "dcjq-parent-li";
                        }
                        ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($template_active)) {
                echo "class='dcjq-parent active'";
            }
                        ?>>
                    <i class="fa fa-envelope-o"></i>
                    <span>Email Templates </span>
                </a>
                <ul class="sub" <?php echo $template_active; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Email Template Setting</span>', array('controller' => 'emailtemplates', 'action' => 'index'), array('class' => $manage_template, 'escape' => false)); ?>
                    </li>

                </ul>
            </li>
            <?php } ?>
            
            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 5)) { ?>
            <!--email template menu end-->

            <!---Blog management--->
            <li class="sub-menu <?php
                        if (!empty($blog_active)) {
                            echo "dcjq-parent-li";
                        }
                        ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($blog_active)) {
                echo "class='dcjq-parent active'";
            }
                        ?>>
                    <i class="fa fa-file-image-o"></i>
                    <span>Blogs </span>
                </a>
                <ul class="sub" <?php echo $blog_active; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Blog List</span>', array('controller' => 'blogs', 'action' => 'index'), array('class' => $blog_list, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php
                        if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 5, 1)){
                            echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Blog</span>', array('controller' => 'blogs', 'action' => 'addblogs'), array('class' => $add_blog, 'escape' => false)); 
                        }?>
                    </li>
                </ul>
            </li>
            <?php } ?>
            <?php if (ClassRegistry::init('Admin')->getCheckRoles($adminLId, $adminRols, 5)) { ?>
            <!--email template menu end-->

            <!---Blog management--->
            <li class="sub-menu <?php
                        if (!empty($slider_active)) {
                            echo "dcjq-parent-li";
                        }
                        ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($slider_active)) {
                echo "class='dcjq-parent active'";
            }
                        ?>>
                    <i class="fa fa-file-image-o"></i>
                    <span>Sliders </span>
                </a>
                <ul class="sub" <?php echo $slider_active; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Slider List</span>', array('controller' => 'sliders', 'action' => 'index'), array('class' => $slider_list, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php
                        if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 5, 1)){
                            echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Slider</span>', array('controller' => 'sliders', 'action' => 'add'), array('class' => $add_slider, 'escape' => false)); 
                        }?>
                    </li>
                </ul>
            </li>
            <?php } ?>
            
             <li class="sub-menu <?php
            if (!empty($announcementmenu)) {
                echo "dcjq-parent-li";
            }
            ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($announcementmenu)) {
                echo "class='dcjq-parent active'";
            }
            ?>>
                    <i class="fa fa-bullhorn"></i>
                    <span>Announcement</span>
                </a>
                <ul class="sub" <?php echo $announcementmenu; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span>Announcement List</span>', array('controller' => 'announcements', 'action' => 'index'), array('class' => $announcementlist, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-plus"></i><span> Add Announcement</span>', array('controller' => 'announcements', 'action' => 'add'), array('class' => $addannouncement, 'escape' => false)); ?>
                    </li>
                </ul>
            </li> 
            
            <li class="sub-menu <?php
            if (!empty($keywordmenu)) {
                echo "dcjq-parent-li";
            }
            ?>">
                <a href="javascript:void(0);" <?php
            if (!empty($keywordmenu)) {
                echo "class='dcjq-parent active'";
            }
            ?>>
                    <i class="fa fa-search"></i>
                    <span>Keywords</span>
                </a>
                <ul class="sub" <?php echo $keywordmenu; ?>>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span>Search Keywords</span>', array('controller' => 'keywords', 'action' => 'index'), array('class' => $keywordlist, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Job Keywords</span>', array('controller' => 'keywords', 'action' => 'jobs'), array('class' => $jobslist, 'escape' => false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-list"></i><span> Requested Keywords</span>', array('controller' => 'keywords', 'action' => 'requests'), array('class' => $requestslist, 'escape' => false)); ?>
                    </li>
                </ul>
            </li>
           
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>


