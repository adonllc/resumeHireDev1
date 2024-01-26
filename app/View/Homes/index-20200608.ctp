<?php
echo $this->Html->script('jquery.validate.js');
echo $this->Html->script('jquery/ui/jquery.ui.core.js');
echo $this->Html->script('jquery/ui/jquery.ui.widget.js');
echo $this->Html->script('jquery/ui/jquery.ui.position.js');
echo $this->Html->script('jquery/ui/jquery.ui.menu.js');
echo $this->Html->script('jquery/ui/jquery.ui.autocomplete.js');
echo $this->Html->css('front/themes/base/jquery.ui.all.css');
?>
<?php echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css'); ?>
<?php echo $this->Html->css('front/sample.css'); ?> 

<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&key=<?php echo AUTO_SUGGESTION; ?>"></script> 
<script>
    var autocomplete;
    function initialize() {
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('job_city')));
    }
</script>

<script>
    function updateCity(stateId) {
        $('#loaderID').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getStateCity/Job/" + stateId,
            cache: false,
            success: function (result) {
                $("#updateCity").html(result);
                $('#loaderID').hide();
            }
        });
    }

    function updateSubCat(catId) {
        $('#loaderID').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getSubCategory/" + catId,
            cache: false,
            success: function (result) {
                $("#subcat").html(result);
                $('#loaderID').hide();
            }
        });
    }

    function res_updateCity(stateId) {
        $('#res_loaderID').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getStateCity/Job/" + stateId,
            cache: false,
            success: function (result) {
                $("#res_updateCity").html(result);
                $('#res_loaderID').hide();
            }
        });
    }

    function res_updateSubCat(catId) {
        $('#res_loaderID').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getSubCategory/" + catId,
            cache: false,
            success: function (result) {
                $("#res_subcat").html(result);
                $('#res_loaderID').hide();
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        // Handler for .ready() called.
        $('#find_job_div, #find_job_div1').click(function () {
            $('html, body').animate({
                scrollTop: $('#banner_sec').offset().top
            }, '20');
        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        // Handler for .ready() called.
        $('#how_it_work_div').click(function () {
            $('html, body').animate({
                scrollTop: $('#howitworks').offset().top
            }, '20');
        });
    });

    //toogle visiblity on search box at admin
    function toggle_visibility(id) {
        //toogle method easy open-close
        var e = document.getElementById(id);

        if (e.style.display == 'none') {

            $('#search').text('');
            $('#loaderID').show();
            $(e).show('4000');
            $('#search').text('Hide Search');
            $('#loaderID').hide("2000");

        } else {

            $('#search').text('');
            $('#loaderID').show();
            $('#search').text('Advance Search');
            $(e).hide('4000');
            $('#loaderID').hide("2000");

        }
    }


    $(document).ready(function () {

        $("#searchJob1").validate();

    });

//    function getMaxExpList(id) {
//        var opt = ''
//        id = id * 1;
//        $("#JobMinExp option").each(function ()
//        {
//            var ff = $(this).val() * 1;
//            if (ff >= id) {
//                opt += '<option value="' + ff + '">' + ff + '</option>';
//            }
//        });
//        $('#JobMaxExp').html(opt);
//    }

    function getMaxExpList(id) {

        // alert(id);
        var opt = '';
        var i = '';
        if (id !== '') {
            for (i = id; i <= 30; i++)
            {

                opt += '<option value="' + i + '">' + i + '</option>';

            }
            $('#JobMaxExp').html(opt);
        } else {
            opt += '<option value="">Max Exp(Year)</option>';
            $('#JobMaxExp').html(opt);
        }
    }

    function getMaxSalaryList(id) {
        var opt = ''
        id = id * 1;
        $("#JobMinSalary option").each(function ()
        {
            var ff = $(this).val() * 1;
            if (ff >= id) {
                opt += '<option value="' + ff + '"><?php echo CURRENCY; ?>' + ff + 'K</option>';
            }
        });
        $('#JobMaxSalary').html(opt);
    }
</script>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(document).mouseup(function (e)
        {
            var containerdesignation = $("#worktype-dropdown");

            if (!containerdesignation.is(e.target) // if the target of the click isn't the container...
                    && containerdesignation.has(e.target).length === 0) // ... nor a descendant of the container
            {
                containerdesignation.hide();
            }
        });
        $('.test-worktype').click(function () {
            var wtype_checked = $(".test-worktype input:checked").length;
            if (wtype_checked == 0) {
                $('#JobTotalWorkType').val('<?php echo __d('user', 'All Worktype', true) ?>');
            } else {
                $('#JobTotalWorkType').val(wtype_checked + ' <?php echo __d('user', 'Worktype selected', true) ?>');
            }
        });
    });


</script>
<script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js"></script>



<?php
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'title'));
$video_url = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'video_link'));
?> 
<div id="content"></div>

<section class="slider">
    <div class="slider-area clearfix">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="slider-wrapper">
                        <div class="row">
                            <div class="col-md-68 mx-auto">
                                <div class="slider-text text-center">
                                    <div class="slide-title">
                                        <h1><div class="mobile-show">6,000+ <?php echo __d('home', 'Jobs Available', true) ?></div><div class="mobile-hide">6,000+ <?php echo __d('home', 'Jobs', true) ?> <span class="typed"></span> </div></h1>
                                        <div class="typed-strings">
                                            <p><?php echo __d('home', 'Available', true) ?></p>
                                        </div>
                                        <p>
                                            <?php
                                            $slogantext = classregistry::init('Admin')->field('slogan_text');
                                            if (isset($slogantext) && !empty($slogantext)) {
                                                echo substr($slogantext, 0, 60);
                                            } else {
                                                echo __d('home', 'Job Site Script', true);
                                            }
                                            ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="job-search-bar">
            <div class="search-bar text-center">
                <div class="container">
                    <div class="section-title text-left">
                        <h2><?php echo __d('home', 'Finding your next job or career', true) ?></h2>
                        <div class="line"></div>
                    </div>
                    <?php echo $this->Form->create("Job", array('url' => array('controller' => 'jobs', 'action' => 'listing'), 'enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'searchJob1', 'name' => 'searchJob1')); ?>
                    <div class="form-row">
                        <div class="col-md-10">
                            <!--<input type="text" class="form-control" placeholder="Search here ,skills,designation, companies.." aria-label="Username" aria-describedby="basic-addon1">-->
                            <?php echo $this->Form->text('Job.keyword', array('maxlength' => '255', 'autocomplete' => 'off', 'data-suggesstion' => 'homekeyword-box', 'data-search' => 'Search', 'label' => '', 'div' => false, 'class' => "keyword-box form-control", 'placeholder' => __d('home', 'Search here ,skills,designation, companies..', true), 'value' => '')); ?> 
                            <div id="homekeyword-box" style="display: none"></div>
                        </div>
                        <div class="col-md-2">
                            <div class="search_button">
                                <!--<a href="#" class="search">Search</a>-->
                                <?php echo $this->Form->submit(__d('home', 'Search', true), array('div' => false, 'label' => false, 'class' => 'search')); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>

</section>
<div class="marquee_bx">
    <div class="advi_bx"> 
        <div class="container">
            <div class="microsoft container-scr">
                <div class="wpanel_marquee">
                    <p> 
                        <?php
                        if (isset($announcementList) && !empty($announcementList)) {
                            foreach ($announcementList as $announcement) {
                                ?>
                                <a href="<?php echo $announcement['Announcement']['url'] ?>" target="_blank"><?php echo $announcement['Announcement']['name'] ?></a>
                                <?php
                            }
                        } else {
                            ?>

                        <p><?php echo __d('home', 'No Announcements available', true) ?></p>
                    <?php } ?></p></div>
            </div>
        </div>

    </div>
</div>
<?php if ($categories_listing) { ?>
    <section class="Find-job">
        <div class="job-categories-area  pt-100 clearfix">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="section-title text-left">
                            <h2><?php echo __d('home', 'Explore All Categories', true) ?></h2>
                            <div class="line"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="expore_btn text-right">
                            <!--<a href="#" class="btn_same"></a>-->
                            <?php
                            echo $this->Html->link(__d('home', 'Explore All Categories', true), array('controller' => 'categories', 'action' => 'allcategories'), array('rel' => 'nofollow', 'class' => 'btn_same'))
                            ?>
                        </div>
                    </div>
                </div>
                <div class="cat-list-items">
                    <div class="row">
                        <?php
                        foreach ($categories_listing as $cat) {
                            $catvalue = $cat['Category']['name'];
                            $catid = $cat['Category']['id'];
                            $catslug = $cat['Category']['slug'];
                            $catimage = $cat['Category']['image'];
                            ?>
                            <div class="col-md-6 col-sm-6 col-lg-3">
                                <div class="single-category text-center " data-aos="flip-up">
                                    <div class="single-category-zoom"></div>
                                    <div class="single-category-main">
                                        <div class="cat-icon"><?php
                                            $filePath = UPLOAD_SMALL_CATEGORY_IMAGE_PATH . $catimage;
                                            if (file_exists($filePath) && $catimage) {
                                                echo $this->Html->link($this->Html->image(DISPLAY_SMALL_CATEGORY_IMAGE_PATH . $catimage), array('controller' => 'jobs', 'action' => 'listing', 'slug' => $catslug), array('escape' => false, 'rel' => 'nofollow'));
                                            } else {
                                                echo $this->Html->link($this->Html->image('front/home/accounts.png'), array('controller' => 'jobs', 'action' => 'listing', 'slug' => $catslug), array('escape' => false, 'rel' => 'nofollow'));
                                            }
                                            ?>
                                        </div>
                                        <div class="cat-details">
                                            <h4>
                                                <!--<a href="#">Accounting & Finance</a>-->
                                                <?php
                                                echo $this->Html->link($catvalue, array('controller' => 'jobs', 'action' => 'listing', 'slug' => $catslug));
                                                $subcategories = ClassRegistry::init('Category')->find('list', array('conditions' => array('Category.parent_id' => $catid, 'Category.status' => 1), 'fields' => array('Category.slug', 'Category.name'), 'limit' => 3));
//           pr($catid);
                                                $subcategories_array = implode(' | ', $subcategories);
                                                if ($subcategories)
                                                    echo '<span>' . $subcategories_array . '</span>';
                                                ?>
                                                <!--<span>Private  |  Andriod   |   Web</span>-->
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?> 
                        <div class="expore_btn btn_centre">
                            <?php
                            echo $this->Html->link('Explore All Categories', array('controller' => 'categories', 'action' => 'allcategories'), array('rel' => 'nofollow', 'class' => 'btn_same'))
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?> 
<section class="how_it_work">
    <div class="job-categories-area  pt-100  clearfix">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-left">
                        <h2><?php echo __d('home', 'How it works', true) ?></h2>
                        <div class="line"></div>
                    </div>
                </div>
            </div>
            <div class="cat-list-items">
                <div class="row">
                    <div class="col-md-4 text-center " data-aos="flip-up">
                        <div class="thumbnail-bx">
                            <div class="thumbnail-inder">
                                <div class="cat-icon"><a href="#"><?php echo $this->Html->image('front/home/sign-up.png', array('alt' => '')); ?></a></div>
                                <div class="cat-details">
                                    <h3>1. <?php echo __d('home', 'Create an account', true) ?></h3>
                                    <p><?php echo __d('home', 'Job seeker can create account with basic user information.', true) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center " data-aos="flip-up">
                        <div class="thumbnail-bx">
                            <div class="thumbnail-inder">
                                <div class="cat-icon"><a href="#"><?php echo $this->Html->image('front/home/job.png', array('alt' => '')); ?></a></div>
                                <div class="cat-details">
                                    <h3>2. <?php echo __d('home', 'Search desired job', true) ?></h3>
                                    <p><?php echo __d('home', 'Job aspirants can search Jobs using keyword.', true) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center " data-aos="flip-up">
                        <div class="thumbnail-bx">
                            <div class="thumbnail-inder">
                                <div class="cat-icon"><a href="#"><?php echo $this->Html->image('front/home/smartphone.png', array('alt' => '')); ?></a></div>
                                <div class="cat-details">
                                    <h3>3. <?php echo __d('home', 'Send your resume', true) ?></h3>
                                    <p><?php echo __d('home', 'Job Seeker can apply for Job, which is a potential match for their profile.', true) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="Featured_Jobs">
    <div class="job-post-area pt-100 bg-color2 pb-100 minus-15 clearfix">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-left">
                        <h2><?php echo __d('home', 'Featured Jobs', true) ?></h2>
                        <div class="line"></div>
                    </div>
                </div>
            </div>

            <div class="owl-carousel owl-theme" id="fetured-jobs">
                <div class="job-post-list">
                    <?php
                    $jcount = 1;
                    $btn_class = 1;
                    global $monthName;
                    if (isset($latestJobList) && !empty($latestJobList)) {
                        foreach ($latestJobList as $key => $job) {
                            ?>
                            <div class="single-job d-md-flex" data-aos="fade-left">
                                <div class="logo">
                                    <a href="<?php echo HTTP_PATH . '/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] . '.html' ?>"><?php // echo $this->Html->image('front/home/logo-2.png', array('alt' => ''));       ?>
                                        <?php
                                        if ($job['Job']['job_logo_check']) {
                                            $logo_image = ClassRegistry::init('User')->field('profile_image', array('User.id' => $job['Job']['user_id']));
                                            $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $logo_image;
                                            if (file_exists($path) && !empty($logo_image)) {
                                                echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $logo_image, array('escape' => false, 'rel' => 'nofollow'));
                                            } else {
                                                echo $this->Html->image('front/no_image_user.png');
                                            }
                                        } else {
                                            $path = UPLOAD_JOB_LOGO_PATH . $job['Job']['logo'];
                                            if (file_exists($path) && !empty($job['Job']['logo'])) {
                                                echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_JOB_LOGO_PATH . $job['Job']['logo'] . "&w=200&zc=1&q=100", array('escape' => false, 'rel' => 'nofollow'));
                                            } else {
                                                echo $this->Html->image('front/no_image_user.png');
                                            }
                                        }
                                        ?>
                                    </a>
                                </div>
                                <div class="job-meta">
                                    <div class="title">
                                        <h4><?php echo $this->Html->link($job['Job']['title'], array('controller' => 'jobs', 'action' => 'detail', 'cat' => $job['Category']['slug'], 'slug' => $job['Job']['slug'], 'ext' => 'html')); ?></h4>
                                    </div>
                                    <div class="meta-info d-flex">
                                        <p><i class="fa fa-briefcase" aria-hidden="true"></i><?php
                                            if ($job['Job']['max_exp'] > 15) {
                                                echo $job['Job']['min_exp'] . ' - ' . 'more than 15 years';
                                            } else {
                                                echo $job['Job']['min_exp'] . ' - ' . $job['Job']['max_exp'] . ' ' . __d('user', 'yrs', true);
                                            }
                                            ?></p>
                                        <p><i class="fa fa-map-marker" aria-hidden="true"></i><a href="<?php echo HTTP_PATH . '/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] . '.html' ?>"><?php echo $job['Job']['job_city'] ? $job['Job']['job_city'] : 'N/A'; ?></a></p>
                                        <p><i class="fa fa-calendar" aria-hidden="true"></i><?php
                                            $now = time(); // or your date as well
                                            $your_date = strtotime($job['Job']['created']);
                                            $datediff = $now - $your_date;
                                            $day = round($datediff / (60 * 60 * 24));
                                            echo $day == 0 ? __d('user', 'Today', true) : $day . ' ' . __d('user', 'Days ago', true);
                                            ?></p>
                                    </div>
                                </div>
                                <div class="timing ml-auto">
                                    <div class="addthis_button addthis_button_mar" addthis:url="<?php echo HTTP_PATH . '/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] . '.html' ?>">
                                        <i class="fa fa-share-alt"></i>
                                    </div>
                                    <a class="time-btn<?php echo $btn_class ?> time-btn" href="<?php echo HTTP_PATH . '/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] . '.html' ?>"><?php
                                        global $worktype;
                                        echo $job['Job']['work_type'] ? $worktype[$job['Job']['work_type']] : 'N/A';
                                        ?></a>
                                </div>
                            </div>

                            <?php
                            $btn_class++;
                            if ($jcount < 12 && $jcount % 4 == 0) {
                                $btn_class = 1;
                                echo '</div><div class="job-post-list">';
                            }
                            $jcount++;
                        }
                    } else {
                        ?>
                        <h6><?php echo __d('home', 'No record found', true); ?></h6>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
</section>
<?php
if ($plans) {
    global $planFeatuersMax;
    global $planFeatuers;
    global $planFeatuersDis;
    global $planType;
    global $planFeatuersHelpText;
    ?>
    <section class="Purchase-Membership">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-left">
                        <h2><?php echo __d('home', 'Membership Plan', true) ?></h2>
                        <div class="line"></div>
                    </div>
                </div>
            </div>
            <div class="<?php echo count($plans) >= 3 ? 'owl-carousel' : 'owl-carousel'; ?> owl-theme">
                <?php
                $sdate = date('Y-m-d');
                foreach ($plans as $plan) {
                    $tpvalue = $plan['Plan']['type_value'];
                    $plan_name = $plan['Plan']['plan_name'];
                    if ($plan['Plan']['type'] == 'Months') {
                        $edate = date('Y-m-d', strtotime($sdate . " + $tpvalue Months"));
                        $edateDIS = date('M d, Y', strtotime($sdate . " + $tpvalue Months"));
                    } else {
                        $edate = date('Y-m-d', strtotime($sdate . " + $tpvalue Years"));
                        $edateDIS = date('M d, Y', strtotime($sdate . " + $tpvalue Years"));
                    }
                    ?> 
                    <div class="item_owl">
                        <div class="pl_main">
                            <div class="pl_name"><?php echo $plan_name ?></div>
                            <div class="pl_amount"><?php echo CURR . ' ' . $plan['Plan']['amount']; ?> <span class="tmymy"> for <?php echo $plan['Plan']['type_value'] . ' ' . $plan['Plan']['type']; ?></span></div>
                            <?php
                            if ($this->Session->read('user_id') && $this->Session->read('user_type') == 'recruiter') {
                                echo $this->Html->link('<div class="pl_buy">Buy this Plan</div>', array('controller' => 'plans', 'action' => 'purchase'), array('escape' => false));
                            } else {
                                echo '<div class="pl_buy">Buy this Plan</div>';
                            }
                            ?>

                            <div class="pl_ft">
                                <?php
                                $fvalues = $plan['Plan']['fvalues'];
                                $featureIds = explode(',', $plan['Plan']['feature_ids']);
                                $fvalues = json_decode($plan['Plan']['fvalues'], true);
                                if ($featureIds) {
                                    echo '<ul class="pl_fflist">';
                                    foreach ($featureIds as $fid) {
                                        $ddd = '<li>';
                                        if (array_key_exists($fid, $fvalues)) {
                                            if ($fvalues[$fid] == $planFeatuersMax[$fid]) {
                                                $joncnt = 'Unlimited';
                                                $ddd .= '<b>Unlimited</b>';
                                            } else {
                                                $joncnt = $fvalues[$fid];
                                                $ddd .= '<b>' . $fvalues[$fid] . '</b>';
                                            }
                                        }

                                        if (array_key_exists($fid, $planFeatuersHelpText)) {
                                            $timecnt = $plan['Plan']['type_value'] . ' ' . $plan['Plan']['type'];
                                            if ($fid == 1) {
                                                $farray = array('[!JOBS!]', '[!TIME!]', '[!RESUME!]');
                                                $toarray = array($joncnt, $timecnt, '');
                                            } elseif ($fid == 2) {
                                                $farray = array('[!JOBS!]', '[!TIME!]', '[!RESUME!]');
                                                $toarray = array('', $timecnt, $joncnt);
                                            }

                                            $msgText = str_replace($farray, $toarray, $planFeatuersHelpText[$fid]);
                                            $disText = '<div class="help_bxse"><i class="fa fa-info-circle" aria-hidden="true"></i><div class="uxicon_help">' . $msgText . '</div></div>';
                                        } else {
                                            $disText = '';
                                        }
                                        $ddd .= ' ' . $planFeatuersDis[$fid] . $disText . '</li>';
                                        echo $ddd;
                                    }
                                    echo '</ul>';
                                }
                                ?>
                            </div>
                        </div>
                    </div> 
                <?php } ?>

            </div>
        </div>
    </section>
<?php } ?>
<section class="Download_our">
    <div class="apps-download-area pt-100 pb-100 clearfix">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="apps-details-content">
                        <div class="section-title text-left">
                            <h2><?php echo __d('home', 'Download our app', true) ?> <br>
                                <?php echo __d('home', 'and find your dream job', true) ?>
                            </h2>
                            <div class="line"></div>
                        </div>
                        <p><b> <?php echo __d('home', 'Connect to opportunities and show your professional potential to the world with us. We make us easy to view, find and apply on the latest jobs in the market.', true) ?> </b> </p>
                        <p> <?php echo __d('home', 'Find jobs with your skills, save and apply on jobs easily. We allow candidates to update their Experience, Education, Skills and other professional details which will make you visible to companies easily.', true) ?>
                        </p>
                        <div class="apps-btn d-md-flex">
                            <a href="https://play.google.com/store/apps/details?id=ls.lsjobseeker" target="_blank">
                                <?php echo $this->Html->image('front/home/Google-Play.png', array('alt' => '')); ?>
                            </a>
                            <a href="https://itunes.apple.com/us/app/ls-job-seeker-candidate/id1403773426?ls=1&mt=8" target="_blank">
                                <?php echo $this->Html->image('front/home/App-Store.png', array('alt' => '')); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 d-flex align-items-center themeix-h">
                    <div class="mobile themeix-h " data-aos="fade-up-left">
                        <?php echo $this->Html->image('front/home/mobile-img.png', array('alt' => '')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="second_slider">
    <div class="job-browse-area pt-100 pb-100 clearfix" style="background-image:url(img/front/home/bg-2.jpg)">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="details-text text-center " data-aos="flip-up">
                        <div class="title pb-3">
                            <div class="heading-two mb-2">
                                <h2><?php echo __d('home', 'Browse Our', true) ?> <span>6,000+ </span><?php echo __d('home', 'Latest Jobs', true) ?></h2>
                            </div>
                            <p><?php echo __d('home', 'Get your best job in here', true) ?></p>
                        </div>
                        <div class="btn-trasparent-white buttonfx curtainup">
                            <?php echo $this->Html->link(__d('home', 'Get started now', true), array('controller' => 'jobs', 'action' => 'listing'), array('escape' => false)); ?>
                            <!--<a href="#"></a></div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="logo_section_l">
    <div class="section_logo_pt pt-100 pb-100">
        <div class="container">
            <div class="col-md-12">
                <div class="section-title text-left">
                    <h2><?php echo __d('home', 'Top Employers', true) ?></h2>
                    <div class="line"></div>
                </div>
            </div>
        </div>
        <div class="client-area  clearfix">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <ul class="client-logo-bx">
                            <?php
                            $homeLogos = Classregistry::init('User')->find('all', array('conditions' => array('User.home_slider' => 1, 'User.status' => 1, 'User.activation_status' => 1, 'User.profile_image <>' => ''), 'limit' => 8));
                            if ($homeLogos) {
                                ?>

                                <?php
                                foreach ($homeLogos as $homeLogo) {
                                    $profile_image = $homeLogo['User']['profile_image'];
                                    $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $profile_image;
                                    if (file_exists($path) && !empty($profile_image)) {
                                        ?>
                                        <li><div class="client-logo">
                                                <?php
                                                echo $this->Html->link($this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_FULL_PROFILE_IMAGE_PATH . $profile_image . "&w=220&h=120&zc=2&q=100", array('escape' => false, 'rel' => 'nofollow', 'alt' => 'logo | jobsitescript.com')), array('controller' => 'candidates', 'action' => 'companyprofile', 'slug' => $homeLogo['User']['slug']), array('escape' => false, 'rel' => 'nofollow'));
                                                ?>
                                            </div>  </li>
                                        <?php
                                    }
                                }
                            }
                            ?>

                        </ul>
                    </div>
                    <div class="col-lg-4 d-flex align-items-center justify-content-center">
                        <div class="client-content">
                            <h5> <?php echo __d('home', 'See Why Over', true) ?> </h5>
                            <h3 class="client-big">10,00258+</h3>
                            <p><?php echo __d('home', 'Companies have already used', true) ?> </p>
                            <!--<a href="#tastimonial" class="client-btn">Reviews & Rating </a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$adDetails = $this->requestAction('/banneradvertisements/getBanneradvertisement/home_ad1/1');
$adDetails2 = Classregistry::init('Banneradvertisement')->find('all', array('conditions' => array('Banneradvertisement.status' => '1', 'Banneradvertisement.advertisement_place' => 'home_ad2'), 'order' => 'RAND()', 'limit' => 1));
if (!empty($adDetails) || !empty($adDetails2)) {
    ?>
    <div class="col-lg-12 col-sm-12">
        <div class="right_panal_bot">

            <?php
            if (!empty($adDetails)) {
                ?>
                <div class="add_img">
                    <?php foreach ($adDetails as $ad_listing) { ?>
                        <?php
                        if (strpos($ad_listing['Banneradvertisement']['url'], 'http') === false) {
                            $url1 = 'http://' . $ad_listing['Banneradvertisement']['url'];
                        } else {
                            $url1 = $ad_listing['Banneradvertisement']['url']; //$ad_listing['Banneradvertisement']['url'];
                        }
                        ?>
                        <?php
                        if ($ad_listing['Banneradvertisement']['type'] == 1) {
                            echo $this->Html->link($this->Html->image(DISPLAY_FULL_BANNER_AD_IMAGE_PATH . $ad_listing['Banneradvertisement']['image'], array()), $url1, array('escape' => false, 'rel' => 'nofollow', 'target' => '_blank'));
                        } elseif ($ad_listing['Banneradvertisement']['type'] == 2) {
                            echo $ad_listing['Banneradvertisement']['code'];
                        } else {
                            echo $this->Html->link($ad_listing['Banneradvertisement']['text'], $url1, array('escape' => false, 'rel' => 'nofollow', 'target' => '_blank'));
                        }
                        ?>
                    <?php } ?>
                </div>
            <?php } ?>




            <?php
            if (!empty($adDetails2)) {
                ?>

                <div class="add_img add_imgright">
                    <?php foreach ($adDetails2 as $ad_listing2) { ?>
                        <?php
                        if (strpos($ad_listing2['Banneradvertisement']['url'], 'http') === false) {
                            $url1 = 'http://' . $ad_listing2['Banneradvertisement']['url'];
                        } else {
                            $url1 = $ad_listing2['Banneradvertisement']['url']; //$ad_listing['Banneradvertisement']['url'];
                        }
                        ?>
                        <?php
                        if ($ad_listing2['Banneradvertisement']['type'] == 1) {
                            echo $this->Html->link($this->Html->image(DISPLAY_FULL_BANNER_AD_IMAGE_PATH . $ad_listing2['Banneradvertisement']['image'], array()), $url1, array('escape' => false, 'rel' => 'nofollow', 'target' => '_blank'));
                        } elseif ($ad_listing2['Banneradvertisement']['type'] == 2) {
                            echo $ad_listing2['Banneradvertisement']['code'];
                        } else {
                            echo $this->Html->link($ad_listing2['Banneradvertisement']['text'], $url1, array('escape' => false, 'rel' => 'nofollow', 'target' => '_blank'));
                        }
                        ?>
                    <?php } ?>
                </div>

            <?php } ?>



        </div>


    </div> <?php
}
$sliders = array();
$sliders[] = 'slder.png';
$sliders[] = 'slder0.png';
$sliders[] = 'slder1.png';
//     print_r($sliders);die;
?>

<script type="text/javascript">
    window.onload = function () {
        initialize();
        setTimeout("hideSessionMessage()", 1000);
    };
    function hideSessionMessage() {
        $('#unsubscribe').fadeOut("slow");
    }
</script> 
<script>
    $(function () {
        $('#logo_slider').owlCarousel({
            rtl: false,
            loop: false,
            nav: true,
            autoplay: false,
            autoplayTimeout: 1000,
            smartSpeed: 500,
            slideSpeed: 1000,
            autoplayHoverPause: true,
            goToFirstSpeed: 100,
            responsive: {
                0: {items: 2},
                479: {items: 3},
                500: {items: 3},
                766: {items: 4},
                1000: {items: 4},
                1100: {items: 5},
                1280: {items: 5}
            }

        })

    });
</script>
<script>
    $('input[type="text"]').blur(function () {
        $(window).scrollTop(0, 0);
    });
</script>

<script>
    $(document).ready(function () {
        $('#fetured-jobs').owlCarousel({
            loop: true,
            margin: 10,
            responsiveClass: true,
// autoplay: true,
            autoplayTimeout: 5000,
            responsive: {
                0: {items: 1, nav: true},
                600: {items: 1, nav: false},
                1000: {items: 2, nav: true, loop: true, margin: 20
                }
            }
        })

    })
</script>
<script>
    /*  Type js  */
    if ((".typed").length > 0) {
        var options = {
            stringsElement: '.typed-strings',
            typeSpeed: 100,
            backDelay: 700,
            loop: !0,
            startDelay: 500,
            cursorChar: '|',
        }
        var typed = new Typed(".typed", options);
    }
</script>
<script>
    $(document).ready(function () {
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 10,
            responsiveClass: true,
            autoplay: true,
            autoplayTimeout: 5000,
            responsive: {
                0: {items: 1, nav: true},
                600: {items: 2, nav: false},
                1000: {items: 3, nav: true, loop: true, margin: 20
                }
            }
        })

//        console.log(images);

    })
    var arr = [];
    var images = '<?php echo json_encode($sliders); ?>';
    var obj = jQuery.parseJSON(images);
    $.each(obj, function (key, value) {
//  alert(value);
        arr.push(value);
    });
//console.log(images);
//images.forEach(function(images) { console.log(images); });

//var images = ['banner1.jpg', 'banner2.jpg', 'banner3.jpg'];

//
//for (var i = 0; i <= images.length; i++) {
//
//     arr.push(images[i]);
//   
//}
//  console.log(arr);

    var i = 0;
    var j = 0;
    setInterval(function () {
        $('.slider-area').css('background-image', function () {
            if (i >= arr.length) {
                i = 0;
                j = 0;
            } else {
                j = i;
//                  i++;  
            }
            return 'url(' + "<?php echo DISPLAY_FULL_SLIDER_IMAGE_PATH ?>" + arr[i++] + ')';
        });
    }, 5000);
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
<script>
    $(window).scroll(function () {
        if ($(this).scrollTop() > 5) {
            $(".header").addClass("fixed-me");
        } else {
            $(".header").removeClass("fixed-me");
        }
    });
</script>
