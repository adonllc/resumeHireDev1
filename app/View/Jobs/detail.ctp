<?php
$_SESSION['job_apply_return_url'] = $this->params->url;
?>
<style>
    .det_imv ul, .det_imv ol{
        padding-left: 20px !important;
    }
    .det_imv ul li {
        list-style-type: circle;
    }


    .det_imv ol li {
        list-style-type:decimal;
    }
</style>
<?php echo $this->Html->script('facebox.js'); ?>
<?php echo $this->Html->css('facebox.css'); ?>
<script type="text/javascript">
    $(document).ready(function ($) {
        $('.close_image').hide();
        $('a[rel*=facebox]').facebox({
            loadingImage: '<?php echo HTTP_IMAGE ?>/loading.gif',
            closeImage: '<?php echo HTTP_IMAGE ?>/close.png'
        })
    })

//    $(document).ready(function () {
//        $(".compDetail").hide();
//        $("#compTitle").click(function () {
//            $(".compDetail").toggle('slow');
//        });
//    });

</script>
<script>
    function jobApplyConfitm() {
        swal({
            title: "<?php echo __d('user', 'Are you sure', true); ?>?",
            text: "<?php echo __d('user', 'You want to share your resume and other account details with this employer', true); ?> ?",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#fccd13",
            confirmButtonText: "Confirm",
            closeOnConfirm: false
        },
                function () {
                    window.location.href = "<?php echo HTTP_PATH; ?>/jobs/jobApply/<?php echo $jobdetails['Job']['slug']; ?>";
                                    });
                        }
</script>    
<script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js"></script>
<?php ClassRegistry::init('Job')->updateJobView($jobdetails['Job']['id']); ?>
<?php
$image_path = HTTP_PATH.'/app/webroot/img/front/no_image_user.png';
if ($jobdetails['Job']['job_logo_check']) {
    $logo_image = ClassRegistry::init('User')->field('profile_image', array('User.id' => $jobdetails['Job']['user_id']));
    $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $logo_image;
    if (file_exists($path) && !empty($logo_image)) {
        $image_path = DISPLAY_THUMB_PROFILE_IMAGE_PATH . $logo_image;
    }
} else {
    $path = UPLOAD_JOB_LOGO_PATH . $jobdetails['Job']['logo'];
    if (file_exists($path) && !empty($jobdetails['Job']['logo'])) {
        $image_path = DISPLAY_JOB_LOGO_PATH . $jobdetails['Job']['logo'];
    }
}
?>
<meta property="og:url"                content="<?php echo HTTP_PATH . '/' . $jobdetails['Category']['slug'] . '/' . $jobdetails['Job']['slug'] . '.html' ?>" />
<meta property="og:type"               content="article" />
<meta property="og:title"              content="<?php echo $jobdetails['Job']['title']; ?>" />
<meta property="og:description"        content="<?php echo strip_tags(nl2br($jobdetails['Job']['description'])); ?>" />
<meta property="og:image"              content="<?php echo $image_path; ?>" />
<div class="clear"></div>
<section class="slider_abouts" style="display: none ">
    <div class="breadcrumb-container">
        <nav class="breadcrumbs page-width breadcrumbs-empty">
            <h3 class="head-title"><?php echo $jobdetails['Job']['title']; ?></h3>
            <?php echo $this->Html->link(__d('user', 'Home', true), '/') ?>
            <span class="divider">/</span>
            <span><?php echo __d('user', 'Job Details', true) ?> </span>

        </nav>
    </div>
</section>
<section class="job-div_button">
    <div class="container">
        <div class="row pt-100 ">
            <div class="col-md-7">
                <?php echo $this->element('session_msg'); ?>
                <div class="single-job-detail">
                    <div class="logo-detail">
                        <?php
                        if ($jobdetails['Job']['logo']) {
                            $path = UPLOAD_JOB_LOGO_PATH . $jobdetails['Job']['logo'];
                            if (file_exists($path) && !empty($jobdetails['Job']['logo'])) {
                                echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_JOB_LOGO_PATH . $jobdetails['Job']['logo'] . "&w=200&zc=1&q=100", array('escape' => false, 'rel' => 'nofollow'));
                            } else {
                                echo $this->Html->image('front/no_image_user.png');
                            }
                        } else {
                            $logo_image = ClassRegistry::init('User')->field('profile_image', array('User.id' => $jobdetails['Job']['user_id']));
                            $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $logo_image;
                            if (file_exists($path) && !empty($logo_image)) {
                                echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $logo_image, array('escape' => false, 'rel' => 'nofollow'));
                            } else {
                                echo $this->Html->image('front/no_image_user.png');
                            }
                        }
                        ?>
                    </div>
                    <div class="job-meta-detail">
                        <div class="title JobTitleDeatilPage">
                            <h4> <a href="#"><?php echo $jobdetails['Job']['title']; ?></a> </h4>
                        </div>
                        <div class="meta-info">
                            <p><i class="fa fa-briefcase" aria-hidden="true"></i><?php
                                if (isset($jobdetails['Job']['min_exp']) && isset($jobdetails['Job']['max_exp'])) {
                                    echo $jobdetails['Job']['min_exp'] . "-" . $jobdetails['Job']['max_exp'] . ' ' . __d('user', 'yrs', true);
                                } else {
                                    echo "N/A";
                                }
                                ?></p>
                            <p><i class="fa fa-map-marker" aria-hidden="true"></i><a href="#"><?php
                                    if (!empty($jobdetails['Job']['job_city']) && isset($jobdetails['Job']['job_city'])) {
                                        echo $jobdetails['Job']['job_city'];
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?></a></p>
                            <p><i class="fa fa-calendar" aria-hidden="true"></i>
                                <?php
                                $now = time(); // or your date as well
                                $your_date = strtotime($jobdetails['Job']['created']);
                                $datediff = $now - $your_date;
                                $day = round($datediff / (60 * 60 * 24));
                                echo $day == 0 ? __d('user', 'Today', true) : $day . ' ' . __d('user', 'Days ago', true);
                                ?>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="job_button_p">
                    <?php
                    if ($jobdetails['Job']['expire_time'] < time()) {
                        $short_status = classregistry::init('ShortList')->find('first', array('conditions' => array('ShortList.user_id' => $this->Session->read('user_id'), 'ShortList.job_id' => $jobdetails['Job']['id'])));
                        if (empty($short_status)) {
                            echo $this->Html->link('<i class="fa fa-star-o"></i> ' . __d('user', 'Save Job', true), array('controller' => 'jobs', 'action' => 'JobSave', 'slug' => $jobdetails['Job']['slug']), array('class' => 'save active', 'escape' => false, 'rel' => 'nofollow'));
                        } else {
                            echo $this->Html->link('<i class="fa fa-star"></i> ' . __d('user', 'Already Saved', true), 'javascript:void(0);', array('class' => 'active Apply', 'escape' => false, 'rel' => 'nofollow'));
                        }
                        echo '&nbsp;&nbsp;';
                        echo $this->Html->link(__d('user', 'Job Expired', true), 'javascript:void(0);', array('class' => 'Apply active'));
                    } elseif ($this->Session->read('user_type') && $this->Session->read('user_type') != 'recruiter') {
                        ?>

                        <?php
                        $short_status = classregistry::init('ShortList')->find('first', array('conditions' => array('ShortList.user_id' => $this->Session->read('user_id'), 'ShortList.job_id' => $jobdetails['Job']['id'])));
                        if (empty($short_status)) {
                            echo $this->Html->link('<i class="fa fa-star-o"></i> ' . __d('user', 'Save Job', true), array('controller' => 'jobs', 'action' => 'JobSave', 'slug' => $jobdetails['Job']['slug']), array('class' => 'save active', 'escape' => false, 'rel' => 'nofollow'));
                        } else {
                            echo $this->Html->link('<i class="fa fa-star"></i> ' . __d('user', 'Already Saved', true), 'javascript:void(0);', array('class' => 'active Apply already-saved', 'escape' => false, 'rel' => 'nofollow'));
                        }
                        ?>

                        &nbsp;&nbsp;
                        <?php
                        if ($this->Session->read('user_id')) {
                            $apply_status = classregistry::init('JobApply')->find('first', array('conditions' => array('JobApply.user_id' => $this->Session->read('user_id'), 'JobApply.job_id' => $jobdetails['Job']['id'])));
                            if (empty($apply_status)) {
                                //echo $this->Html->link('Apply Now', 'javascript:void(0);', array('class' => 'sstar', 'onclick' => 'jobApplyConfitm();'));
                                echo '<a id="applybtn' . $jobdetails["Job"]["id"] . '" onclick="jobApply(' . $jobdetails["Job"]["id"] . ')" href="javascript:void(0);" class = "Apply active">' . __d('user', 'Apply Now', true) . '</a>';
                            } else {
                                echo $this->Html->link(__d('user', 'Already Applied', true), 'javascript:void(0);', array('class' => 'Apply active'));
                            }
                        } else {

                            echo $this->Html->link(__d('user', 'Apply Now', true), array('controller' => 'jobs', 'action' => 'jobApplyDetail', $jobdetails['Job']['slug']), array('class' => 'Apply active'));
                        }
                        ?>

                        <?php
                    } else {
                        echo $this->Html->link('<i class="fa fa-star-o"></i> ' . __d('user', 'Save Job', true), array('controller' => 'jobs', 'action' => 'JobSave', 'slug' => $jobdetails['Job']['slug']), array('class' => 'save active', 'escape' => false, 'rel' => 'nofollow'));
                        echo '&nbsp;&nbsp;';
                        echo $this->Html->link(__d('user', 'Apply Now', true), array('controller' => 'jobs', 'action' => 'jobApplyDetail', $jobdetails['Job']['slug']), array('class' => 'Apply active'));
                    }
                    ?>
                    <div class="share_icons addthis_button"> <a href="#" title="Share"><?php echo $this->Html->image('front/home/share-icon.png', array('alt' => '')); ?></a></div> 
                </div>
            </div>
        </div>
</section>
<section class="job_listing">
    <div class="job-post-details-area pb-100 pt-4">
        <div class="container">
            <!-- New formate detail page -->
            <div class="row">
                <div class="col-md-8">
                    <div class="job-post-wrapper">
                        <div class="entry-content">

                            <h3><?php echo __d('user', 'Job Description', true); ?></h3>
                            <div class="clear"></div>
                            <?php
                            $specification = 1;
                            if (trim($jobdetails['Job']['selling_point1']) == '' && trim($jobdetails['Job']['selling_point2']) == '' && trim($jobdetails['Job']['selling_point3']) == '') {
                                $specification = 0;
                                ?>
                                <div class="det_imv">
                                    <span>
    <?php echo ($jobdetails['Job']['description']); ?>
                                    </span>
                                </div>

<?php } else { ?>

                                <div class="det_imv">
                                    <span>
    <?php echo ($jobdetails['Job']['description']); ?>
                                    </span>
                                </div>


<?php } ?>


                            <div class="clear"></div><br/>
                            <h3><?php echo __d('user', 'Key skill Required', true); ?></h3>
                            <div class="show_skills_sc">

                                <ul>
                                    <?php
                                    $jobskill = ClassRegistry::init('Job')->field('skill', array('Job.id' => $jobdetails['Job']['id']));
                                    $jobId = explode(',', $jobskill);
                                    $i = 1;
                                    foreach ($jobId as $id) {
                                        $skill = ClassRegistry::init('Skill')->field('name', array('Skill.id' => $id));

                                        if (!empty($skill)) {
                                            if ($i == 1) {
                                                echo '<li>' . $skill . '</li>';
                                            } else {
                                                //echo " , " . $skill;
                                                echo '<li>' . $skill . '</li>';
                                            }
                                            $i = $i + 1;
                                        } else {
                                            echo"N/A";
                                        }
                                    }
                                    ?>
                                </ul>    
                            </div>

                            <div class="clear"></div><br/>
                            <h3><?php echo __d('user', 'Designation', true); ?></h3>
                            <div class="show_skills_sc">

                                <ul>
                                    <?php
                                    $jobDesignation = ClassRegistry::init('Job')->field('designation', array('Job.id' => $jobdetails['Job']['id']));
                                    // pr($jobDesignation);
                                    $designation = ClassRegistry::init('Skill')->field('name', array('Skill.id' => $jobDesignation, 'Skill.type' => 'Designation'));
                                    if (!empty($designation)) {
                                        echo '<li>' . $designation . '</li>';
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </ul>    
                            </div>

                            <div class="clear"></div><br/>


                        </div>

                    </div>
                    <div class="job-post-list">
                        <div class="sidebar-title inner-section ">
                            <h3><?php echo __d('user', 'Related Jobs', true) ?></h3>
                        </div>
                        <div class="related-job-bx">
                        <div class="row">
                        <?php
                        global $monthName;
                        if (isset($relevantJobList) && !empty($relevantJobList)) {
                            foreach ($relevantJobList as $key => $job) {
                                ?>
                         <div class="col-lg-10">
                                <div class="single-job " data-aos="fade-left">
                                    
                                    <div class="job-meta">
                                        <div class="title">
                                            <h4><?php echo $this->Html->link($job['Job']['title'], array('controller' => 'jobs', 'action' => 'detail', 'cat' => $job['Category']['slug'], 'slug' => $job['Job']['slug'], 'ext' => 'html')); ?></h4>
                                        </div>
                                        <div class="meta-info">
                                            <div class="job-experience"><label>Experience: </label>
                                                <span><?php
                                                if ($job['Job']['max_exp'] > 15) {
                                                    echo $job['Job']['min_exp'] . ' - ' . 'more than 15 years';
                                                } else {
                                                    echo $job['Job']['min_exp'] . ' - ' . $job['Job']['max_exp'] . ' ' . __d('user', 'yrs', true);
                                                }
                                                ?></span>
                                            </div>
                                           <div class="job-salary-package"> <?php if (isset($job['Job']['min_salary']) && isset($job['Job']['max_salary'])) {
                                                    echo CURRENCY . ' ' . intval($job['Job']['min_salary']) . " - " . CURRENCY . ' ' . intval($job['Job']['max_salary']);
                                                } else {
                                                    echo "N/A";
                                                } ?> /year</div>
                                            
                                        </div>
                                    </div>
                                    <div class="timing ml-auto">
                                        <a class="time-btn-new" href="<?php echo HTTP_PATH . '/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] . '.html' ?>"><?php
                                            global $worktype;
                                            echo $job['Job']['work_type'] ? $worktype[$job['Job']['work_type']] : 'N/A';
                                            ?></a>
                                         <div class="addthis_button addthis_button_share" addthis:url="<?php echo HTTP_PATH . '/jobs/detail/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] ?>">
        <?php echo $this->Html->image('front/home/share-icon.png', array('alt' => '')); ?>
                                            </div>
                                    </div>
                                    <div class="client-logo-img">
                                    <div class="logo-client-site">
                                        <a href="<?php echo HTTP_PATH . '/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] . '.html' ?>"><?php // echo $this->Html->image('front/home/logo-2.png', array('alt' => ''));    ?>
                                            <?php
                                            if ($job['Job']['logo']) {
                                                $path = UPLOAD_JOB_LOGO_PATH . $job['Job']['logo'];
                                                if (file_exists($path) && !empty($job['Job']['logo'])) {
                                                    echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_JOB_LOGO_PATH . $job['Job']['logo'] . "&w=200&zc=1&q=100", array('escape' => false, 'rel' => 'nofollow'));
                                                } else {
                                                    echo $this->Html->image('front/no_image_user.png');
                                                }
                                            } else {
                                                $logo_image = ClassRegistry::init('User')->field('profile_image', array('User.id' => $job['Job']['user_id']));
                                                $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $logo_image;
                                                if (file_exists($path) && !empty($logo_image)) {
                                                    echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $logo_image, array('escape' => false, 'rel' => 'nofollow'));
                                                } else {
                                                    echo $this->Html->image('front/no_image_user.png');
                                                }
                                            }
                                            ?>
                                        </a>
                                    </div>
                                        <div class="job-locations">
                                                <p><i><?php echo $this->Html->image('front/home/location-icon.png', array('alt' => '')); ?></i>
                                                <a href="<?php echo HTTP_PATH . '/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] . '.html' ?>"><?php echo $job['Job']['job_city'] ? $job['Job']['job_city'] : 'N/A'; ?></a>
                                                </p>
                                            </div>
                                            <div class="job-times"><p><i class="fa fa-calendar" aria-hidden="true"></i><?php
                                                $now = time(); // or your date as well
                                                $your_date = strtotime($job['Job']['created']);
                                                $datediff = $now - $your_date;
                                                $day = round($datediff / (60 * 60 * 24));
                                                echo $day == 0 ? __d('user', 'Today', true) : $day . ' ' . __d('user', 'Days ago', true);
                                                ?></p></div>
                                    </div>
                                </div>
                                </div>

                                <?php
                            }
                        } else {
                            ?>
                            <div class="col-md-12"><h6><?php echo __d('home', 'No record found', true); ?></h6></div>
<?php } ?>

                    </div>
                    </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="right-sidebar">

                        <div class="sidebar-widget mb-4">
                            <div class="sidebar-title">
                                <h3><?php echo __d('user', 'Job Overview', true) ?></h3>
                            </div>
                            <div class="sidebar-details">
                                <div class="single-overview">
                                    <div class="icon"><i class="fa fa-calendar"></i></div>
                                    <div class="meta-overview">
                                        <p><?php echo __d('user', 'Date Posted', true) ?>: <span><?php echo date('d F Y', strtotime($jobdetails['Job']['created'])); ?></span></p>
                                    </div>
                                </div>
                                <!--                                <div class="single-overview  d-flex">
                                                                    <div class="icon"><i class="fa fa-phone"></i></div>
                                                                    <div class="meta-overview">
                                                                        <p>Phone: <span><?php $this->Text->usformat($jobdetails['Job']['contact_number']) ?></span></p>
                                                                    </div>
                                                                </div>-->
                                <div class="single-overview ">
                                    <div class="icon"><i class="fa fa-sitemap"></i></div>
                                    <div class="meta-overview">
                                        <p><?php echo __d('user', 'Category', true); ?>: <span><?php echo $jobdetails['Category']['name']; ?></span></p>
                                    </div>
                                </div>
                                <div class="single-overview ">
                                    <div class="icon"><i class="fa fa-sitemap"></i></div>
                                    <div class="meta-overview">
                                        <p><?php echo __d('user', 'Sub Category', true); ?>: <span><?php
                                                $condition = array();
                                                $subcategory_id = $jobdetails['Job']['subcategory_id'] ? $jobdetails['Job']['subcategory_id'] : 0;
                                                $condition[] = " (Category.id IN ($subcategory_id ))";
                                                $subcategory = ClassRegistry::init('Category')->find('list', array('conditions' => $condition));
//                        pr($subcategory);
                                                echo $subcategory ? implode(', ', $subcategory) : 'N/A'
                                                ?></span></p>
                                    </div>
                                </div>
                                <div class="single-overview ">
                                    <div class="icon"><i class="fa fa-clock-o"></i></div>
                                    <div class="meta-overview">
                                        <p><?php echo __d('user', 'Job Type', true); ?>: <span><?php
                                                global $worktype;
                                                echo $worktype[$jobdetails['Job']['work_type']];
                                                ?></span></p>
                                    </div>
                                </div>
                                <div class="single-overview">
                                    <div class="icon"><i class="fa fa-dollar"></i></div>
                                    <div class="meta-overview">
                                        <p><?php echo __d('user', 'Salary', true); ?>: <span><?php
                                                if (isset($jobdetails['Job']['min_salary']) && isset($jobdetails['Job']['max_salary'])) {
                                                    echo CURRENCY . ' ' . intval($jobdetails['Job']['min_salary']) . " - " . CURRENCY . ' ' . intval($jobdetails['Job']['max_salary']);
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?> /year</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar-widget mb-4">
                            <div class="sidebar-title">
                                <h3><?php echo __d('home', 'Contact info', true); ?></h3>
                            </div>
                            <div class="sidebar-details">
                                <div class="contact-details">
                                    <div class="icon"><i class="fa fa-users"></i></div>
                                    <div class="contact-info">
                                        <p><?php echo __d('user', 'Company Name', true); ?>: <span><?php echo $jobdetails['Job']['company_name'] ? $jobdetails['Job']['company_name'] : 'N/A'; ?></span></p>
                                    </div>
                                </div>
                                <div class="contact-details">
                                    <div class="icon"><i class="fa fa-user"></i></div>
                                    <div class="contact-info">
                                        <p><?php echo __d('user', 'Recruiter Name', true); ?>: <span><?php echo $jobdetails['Job']['contact_name'] ?></span></p>
                                    </div>
                                </div>
                                <div class="contact-details">
                                    <div class="icon"><i class="fa fa-phone"></i></div>
                                    <div class="contact-info">
                                        <p><?php echo __d('user', 'Contact Company', true); ?>: <span><?php echo $this->Text->usformat($jobdetails['Job']['contact_number']) ?></span></p>
                                    </div>
                                </div>
                                <div class="contact-details WebsiteInfoBx">
                                    <div class="icon"><i class="fa fa-share"></i></div>
                                    <div class="contact-info WebsiteInfo">
                                        <p><?php echo __d('user', 'Website', true); ?>: <span><?php
                                                if (trim($jobdetails['Job']['url']) != '') {
                                                    echo $this->Text->autoLink(trim($jobdetails['Job']['url']), array('target' => '_blank', 'rel' => 'nofollow'));
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?></span></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="google-maps">
                            <iframe src = "https://maps.google.com/maps?q=<?php echo $jobdetails['Job']['lat'] ?>,<?php echo $jobdetails['Job']['long'] ?>&hl=es;z=14&amp;output=embed" style="border:0;width:100%;height:210px;"  allowfullscreen=""></iframe>
                            <!--<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14237.299956549761!2d75.8005658!3d26.8614139!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb53d8d49ec204540!2sLogicSpice%20Consultancy%20Private%20Limited!5e0!3m2!1sen!2sin!4v1575443976281!5m2!1sen!2sin" style="border:0;width:100%;height:210px;"  allowfullscreen=""></iframe>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>	
</section>


<?php /* if ($this->Session->read('user_id') != '') { ?>

  <!-------------------popup box start------------>
  <div id="confirmPopup<?php echo $jobdetails["Job"]["id"] ?>" style="display: none;">
  <!-- Fieldset -->

  <?php echo $this->Form->create('Job', array('url' => array('action' => 'jobApplyDetail/' . $jobdetails['Job']['slug']), 'enctype' => 'multipart/form-data', "method" => "Post")); ?>

  <div class="nzwh-wrapper">

  <fieldset class="nzwh">

  <legend class="nzwh"><h2> Job Application Confirmation  </h2></legend>

  <?php
  $optionNotification = classregistry::init('CoverLetter')->find('list', array('conditions' => array('CoverLetter.user_id' => $this->Session->read('user_id')), 'fields' => array('CoverLetter.id', 'CoverLetter.title')));
  ?>
  <div class="drt">
  <span class="fdsf"> Please Select the Cover Letter.</span>
  </div>
  <div class="drt">
  <div class="radio-inline">
  <?php
  if (!empty($optionNotification)) {

  $default = min(array_keys($optionNotification));
  $attributes = array('default' => $default, 'legend' => false, 'hiddenField' => false, 'label' => false, 'class' => 'radiobtn', 'separator' => '</div><div class="radio-inline">');
  echo $this->Form->radio('JobApply.cover_letter', $optionNotification, $attributes);
  } else {
  echo 'Please add a cover letter or apply without cover letter.';
  }
  ?>
  </div>
  </div>
  <div class="clear"></div>
  <!--   <div class="drt">
  <span class="fdsf"> Please Select the CV document/Certificate.</span>
  </div>
  <div class="drt">
  <div class="radio-inline dbl_check">
  <?php /*   if ($showOldImages) {
  $option = array();
  foreach ($showOldImages as $key => $value) {
  $option[$key] = $this->Html->link(substr($value, 6), array('controller' => 'candidates', 'action' => 'downloadDocCertificate', $value), array('class' => 'dfasggs'));
  ;
  }
  echo $this->Form->input('JobApply.attachment_ids', array('class' => '', 'multiple' => 'checkbox', 'escape' => false,'rel'=>'nofollow', 'options' => $option, 'legend' => false, 'div' => false, 'label' => false));
  } else {
  ?>
  No CV document/Certificate Found.
  <?php } *

  </div>
  </div> -->
  <div class="clear"></div>
  <?php echo $this->Form->hidden('Job.slug', array('value' => $jobdetails['Job']['slug'])); ?>
  <?php
  if (empty($optionNotification)) {
  echo $this->Form->hidden('Job.cover_letter', array('value' => 0));
  }
  ?>


  <?php echo $this->Form->submit('Submit', array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
  <?php echo $this->Html->link('Add Cover Letter', array('controller' => 'candidates', 'action' => 'editProfile/' . 'return'), array('class' => 'input_btn rigjt add_cover_letter_bt', 'escape' => false, 'rel' => 'nofollow')); ?>

  </fieldset>

  </div>
  <?php echo $this->Form->end(); ?>
  </div>




  <?php } */ ?>



<script>

                        $(document).ready(function () {
<?php
if (isset($_SESSION['job_apply_popup_status']) && $_SESSION['job_apply_popup_status'] == 1) {
    unset($_SESSION['job_apply_popup_status']);
    ?>
                                $('#applyNowPopup').click();
<?php } ?>

                        })

</script>


<script>
    function jobApply(id) {
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/applypop/" + id,
            cache: false,
            data: {'actionc': 'jobApplyDetail'},
            beforeSend: function () {
                $('#loaderID').show();
            },
            complete: function () {
                $('#loaderID').hide();
            },
            success: function (result) {
                if(result ==''){
                    window.location.reload();
                    return false;
                }

                $('#applypop').html(result);
                $('#applypop').addClass("popupc");


            }
        });
    }
    function closepopJob() {
        $('#applypop').removeClass("popupc");
        $('#applypop').html("");
    }
</script>

<div  id="applypop"></div>