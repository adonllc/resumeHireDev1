<?php
echo $this->Html->script('jquery.validate.js');
echo $this->Html->script('jquery/ui/jquery.ui.core.js');
echo $this->Html->script('jquery/ui/jquery.ui.widget.js');
echo $this->Html->script('jquery/ui/jquery.ui.position.js');
echo $this->Html->script('jquery/ui/jquery.ui.menu.js');
echo $this->Html->script('jquery/ui/jquery.ui.autocomplete.js');
echo $this->Html->css('front/themes/base/jquery.ui.all.css');
$jobs_count = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'jobs_count'));
$top_emp_text = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'top_emp_text'));
//echo $jobs_count;die;
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
    <div class="slider-area clearfix" style="<?php echo empty($sliderList) ? "background: url(" . HTTP_IMAGE . "/front/home/slder.png);" : "" ?>">

        <?php $slider_co = 0;
        if ($sliderList) {
            ?>

            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    foreach ($sliderList as $slider) {
//                        pr($slider);die;
                        $filePath = UPLOAD_THUMB_SLIDER_IMAGE_PATH . $slider['Slider']['image'];
                        if (file_exists($filePath) && $slider['Slider']['image']) {

//                        }
                            ?>
                            <div class="carousel-item <?php echo $slider_co == 0 ? "active" : '' ?>">
                                <?php
                                // echo $this->Html->image('front/home/slder.png', array('alt' => ''));
                                echo $this->Html->image(DISPLAY_THUMB_SLIDER_IMAGE_PATH . $slider['Slider']['image'], array('alt' => ''));
                                ?>
                            </div>
                            <?php
                            $slider_co ++;
                        }
                    }
                    ?>

                </div>
                <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
<?php } ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="slider-wrapper">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="slider-text">
                                    <div class="slide-title">
                                        <h3><div class="mobile-show"><?php echo $jobs_count ?></div><div class="mobile-hide"><?php echo $jobs_count ?> 
                                                <!--<span class="typed"></span>--> 
                                            </div></h3>
                                        <!--                                        <div class="typed-strings">
                                                                                    <p><?php echo __d('home', 'Available', true) ?></p>
                                                                                </div>-->
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
                                <div class="job-search-bar">
                                    <div class="search-bar text-center">
                                                <?php echo $this->Form->create("Job", array('url' => array('controller' => 'jobs', 'action' => 'listing'), 'enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'searchJob1', 'name' => 'searchJob1')); ?>
                                        <div class="form-row">
                                            <div class="jobsearch-input">
                                                <!--<input type="text" class="form-control" placeholder="Search here ,skills,designation, companies.." aria-label="Username" aria-describedby="basic-addon1">-->
<?php echo $this->Form->text('Job.keyword', array('maxlength' => '255', 'autocomplete' => 'off', 'data-suggesstion' => 'homekeyword-box', 'data-search' => 'Search', 'label' => '', 'div' => false, 'class' => "keyword-box form-control", 'placeholder' => __d('home', 'Search here, Skills, Designation, Companies..', true), 'value' => '')); ?> 
                                                <div id="homekeyword-box" style="display: none"></div>
                                                   
                                            </div>
                                            <div class="jobsearch-btn">
                                                <div class="search_button">
                                                    <!--<a href="#" class="search">Search</a>-->
<?php echo $this->Form->submit(__d('home', 'Search', true), array('div' => false, 'label' => false, 'class' => 'search')); ?>
                                                </div>
                                                    <div class="upload_button"><a href="#"><?php echo __d('home', 'UPLOAD CV', true) ?></a></div>
                                                    <input id="file-input-hidden" type="file" name="data[Job][docs]" style="display: none;" />
                                            </div>
                                            <div id="loaderID" style="display:none;position:absolute;"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
                                        </div>
<?php echo $this->Form->end(); ?>

                                    </div>
                                    <div class="jobs-clients">
                                        <div class="jobs-clients-posted">
                                            <?php $jobs_posted_count = ClassRegistry::init('Job')->find('count');?>
                                            <strong><?php echo !empty($jobs_posted_count)? $jobs_posted_count : "100+" ?></strong>
                                            <span><?php echo __d('home', 'Jobs Posted', true) ?></span>
                                        </div>
                                        <div class="jobs-clients-posted">
                                            <?php $employer_posted_count = ClassRegistry::init('User')->find('count',array('conditions'=>array('User.user_type'=>'recruiter')));?>
                                            <strong><?php echo !empty($employer_posted_count)?  $employer_posted_count: "100+"?></strong>
                                            <span><?php echo __d('home', 'Employers', true) ?> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</section>
<div class="hidden" style="overflow: hidden; display: none; opacity: 0">
<h1>Job Board Software</h1>
<h2>Create your own Job board portal</h2>
</div>
<section class="create-account-option">
    <div class="container">
        <div class="row">
            <div class="col-md-4" data-aos="flip-up">
                <div class="card">
                    <div class="create-account-bg"></div>
                    <div class="create-account-icon"><?php echo $this->Html->image('front/home/create-account-icon.png', array('alt' => '')); ?></div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo __d('home', 'Create an account', true) ?></h5>
                        <p class="card-text"><?php echo __d('home', 'Job seeker can create account with basic user information.', true) ?></p>
                    </div>

                </div>
            </div>
            <div class="col-md-4" data-aos="flip-up">
                <div class="card">
                    <div class="create-account-bg"></div>
                    <div class="create-account-icon"><?php echo $this->Html->image('front/home/search-desired-icon.png', array('alt' => '')); ?></div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo __d('home', 'Search desired job', true) ?></h5>
                        <p class="card-text"><?php echo __d('home', 'Job aspirants can search Jobs using keyword.', true) ?></p>
                    </div>

                </div>
            </div>
            <div class="col-md-4" data-aos="flip-up">
                <div class="card">
                    <div class="create-account-bg"></div>
                    <div class="create-account-icon"><?php echo $this->Html->image('front/home/send-resume-icon.png', array('alt' => '')); ?></div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo __d('home', 'Send your resume', true) ?></h5>
                        <p class="card-text"><?php echo __d('home', 'Job Seeker can apply for Job, which is a potential match for their profile.', true) ?></p>
                    </div>

                </div>
            </div>
        </div>



    </div>
</section>
<?php if ($categories_listing) { ?>
    <section class="Find-job">
        <div class="job-categories-area  pt-100 clearfix">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title text-center">
                            <h2><?php echo __d('home', 'Explore Categories', true) ?></h2>
                        </div>
                    </div>

                </div>
                <div class="cat-list-items cat-list-items-new">
                    <div class="row m-0">
                        <?php
                        foreach ($categories_listing as $cat) {
                            $catvalue = $cat['Category']['name'];
                            $catid = $cat['Category']['id'];
                            $catslug = $cat['Category']['slug'];
                            $catimage = $cat['Category']['image'];
                            ?>
                            <div class="col-md-6 col-sm-6 col-lg-3 p-0 explore-categorys-new">
                                <div class="single-category">
                                    <div class="single-category-main">

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
                                        <div class="new-cat-icon"><?php
                                            $filePath = UPLOAD_SMALL_CATEGORY_IMAGE_PATH . $catimage;
                                            if (file_exists($filePath) && $catimage) {
                                                echo $this->Html->link($this->Html->image(DISPLAY_FULL_CATEGORY_IMAGE_PATH . $catimage), array('controller' => 'jobs', 'action' => 'listing', 'slug' => $catslug), array('escape' => false, 'rel' => 'nofollow'));
                                            } else {
                                                echo $this->Html->link($this->Html->image('front/home/accounts.png'), array('controller' => 'jobs', 'action' => 'listing', 'slug' => $catslug), array('escape' => false, 'rel' => 'nofollow'));
                                            }
                                            ?>
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
                <div class="expore_btn text-center">
                    <!--<a href="#" class="btn_same"></a>-->
    <?php
    echo $this->Html->link(__d('home', 'Explore All Categories', true), array('controller' => 'categories', 'action' => 'allcategories'), array('rel' => 'nofollow', 'class' => 'btn_same'))
    ?>
                </div>

            </div>
        </div>
    </section>
<?php } ?> 

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
                    <div class="section-title text-center">
                        <h2><?php echo __d('home', 'Membership Plan', true) ?></h2>
                    </div>
                </div>
            </div>
            <div class="owl-carousel owl-theme" id="plan-carousel">
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
                            <div class="pl_amount"><?php echo CURRENCY . ' ' . $plan['Plan']['amount']; ?> <span class="tmymy"> <?php echo __d('user', 'for', true); ?> <?php echo $plan['Plan']['type_value'] . ' ' . $plan['Plan']['type']; ?></span></div>
                            <div class="jobmembership-plan">

                                <div class="pl_ft">
                                    <div class="apply-immediately">
                                        <input type="checkbox" id="html">
                                        <label for="html">Apply Immediately</label>
                                    </div>
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
                                                    $joncnt = __d('user', 'Unlimited', true);
                                                    $ddd .= '<b>' . __d('user', 'Unlimited', true) . '</b>';
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
                                <?php
                                if ($this->Session->read('user_id')) {
                                    echo $this->Html->link('<div class="pl_buy">' . __d('user', 'Buy this Plan', true) . '</div>', array('controller' => 'plans', 'action' => 'purchase'), array('escape' => false));
                                } else {
                                    echo '<div class="pl_buy">' . __d('user', 'Buy this Plan', true) . '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div> 
    <?php } ?>

            </div>
        </div>
    </section>

<section class="logo_section_l pt-100 ">
    <div class="section_logo_pt pt-100">
        <div class="container">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h2><?php echo __d('home', 'Top Employers', true) ?></h2>
                </div>
            </div>
        </div>
        <div class="client-area  clearfix">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
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
                                    } else {
                                    echo  $profile_image;                                    echo '<br>';   
                                    }
                                }
                            }
                            ?>

                        </ul>
                    </div>
                    <div class="col-lg-12 text-center">
                        <div class="client-content">
                            <h5><p><?php echo $top_emp_text; ?></p></h5>
                            <!--<h5> <?php echo __d('home', 'See Why Over', true) ?> </h5>-->
                            <!--<h3 class="client-big">10,00258+</h3>-->
                            <!--<p><?php echo __d('home', 'Companies have already used', true) ?> </p>-->
                            <!--<a href="#tastimonial" class="client-btn">Reviews & Rating </a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php } ?>
<!--<section class="Download_our">-->
<!--    <div class="apps-download-area clearfix">-->
<!--        <div class="container">-->
<!--            <div class="row">-->
<!--                <div class="col-md-6">-->
<!--                    <div class="apps-details-content">-->
<!--                        <div class="section-title text-left">-->
<!--                            <h2><?php echo __d('home', 'Download our app', true) ?> <br>-->
<!--                                <span><?php echo __d('home', 'and find your dream job', true) ?></span>-->
<!--                            </h2>-->
<!--                        </div>-->
<!--                        <p><b> <?php echo __d('home', 'Connect to opportunities and show your professional potential to the world with us. We make us easy to view, find and apply on the latest jobs in the market.', true) ?> </b> </p>
<!--                        <p> <?php echo __d('home', 'Find jobs with your skills, save and apply on jobs easily. We allow candidates to update their Experience, Education, Skills and other professional details which will make you visible to companies easily.', true) ?>-->
<!--                        </p>-->-->
<!--                        <div class="apps-btn d-md-flex">-->
<!--                            <a href="https://play.google.com/store/apps/details?id=ls.lsjobseeker" target="_blank">-->
<!--                                <?php echo $this->Html->image('front/home/Google-Play.png', array('alt' => '')); ?>-->
<!--                            </a>-->
<!--                            <a href="https://itunes.apple.com/us/app/ls-job-seeker-candidate/id1403773426?ls=1&mt=8" target="_blank">-->
<!--                                <?php echo $this->Html->image('front/home/App-Store.png', array('alt' => '')); ?>-->
<!--                            </a>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="col-md-6 d-flex align-items-center themeix-h">-->
<!--                    <div class="mobile themeix-h " data-aos="fade-up-left">-->
<!--                        <?php echo $this->Html->image('front/home/mobile-img.png', array('alt' => '')); ?>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->
<section class="second_slider" style="display: none">
    <div class="job-browse-area pt-100 pb-100 clearfix" style="background-image:url(img/front/home/bg-2.jpg)">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="details-text text-center " data-aos="flip-up">
                        <div class="title pb-3">
                            <div class="heading-two mb-2">
                                <h2><?php echo __d('home', 'Browse Our', true) ?> <?php echo __d('home', 'Latest Jobs', true) ?></h2>
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


    </div> <?php } ?>
    <?php
  
if (isset($announcementList) && !empty($announcementList)) {
    ?>
<div class="marquee_bx">
    <div class="advi_bx"> 
        <div class="container">
            <div class="microsoft container-scr">
                <div class="wpanel_marquee">
                    <p> 
                       <?php
                            foreach ($announcementList as $announcement) {
                                ?>
                                <a href="<?php echo $announcement['Announcement']['url'] ?>" target="_blank"><?php echo $announcement['Announcement']['name'] ?></a>
                                <?php
                            }
                       
                            ?>

                      
                    </p></div>
            </div>
        </div>

    </div>
</div>

<?php } ?>
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
<?php if (isset($latestJobList) && !empty($latestJobList)) { ?>
    <script>
        $(document).ready(function () {
            $('#fetured-jobs').owlCarousel({
                loop: true,
                margin: 10,
                responsiveClass: true,
    // autoplay: true,
                autoplayTimeout: 5000,
                responsive: {
                    0: {items: 2, nav: true},
                    600: {items: 1, nav: false},
                    1000: {items: 2, nav: true, loop: true, margin: 20
                    }
                }
            })
        })
    </script>
<?php } ?>

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
        $('#plan-carousel').owlCarousel({
            center: true,
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
        });

        $(document).on('click','.upload_button',function(){
            $('#file-input-hidden').trigger('click');
            
        });

        $(document).on('change','#file-input-hidden',function(){
            // alert('asdasd');
            // $('#loaderID').show();
            var file_valid = FileValidation();
            console.log(file_valid);
            if(file_valid){
                var form = $('#searchJob1')[0];
                var formData = new FormData(form);
                // formData.append('Job[document]', $('input[type=file]')[0].files[0]); 
                $.ajax({
                    type: 'POST',
                    url: "<?php echo HTTP_PATH; ?>/candidates/uploadcv/login",
                    data:formData,
                    processData: false,
                    contentType: false,
                    success: function (result) {
                        window.location = "<?php echo HTTP_PATH; ?>/users/register/jobseeker";
                        // $("#updateCity").html(result);
                        // $('#loaderID').hide();
                    }
                });
            }
        });
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
<script>
    $(window).scroll(function () {
        if ($(this).scrollTop() > 5) {
            $(".header").addClass("fixed-me");
        } else {
            $(".header").removeClass("fixed-me");
        }
    });
</script>
<script>
 function getExt(filename) {
        var dot_pos = filename.lastIndexOf(".");
        if (dot_pos == -1)
            return "";
        return filename.substr(dot_pos + 1).toLowerCase();
        }

        function in_array(needle, haystack) {
            for (var i = 0, j = haystack.length; i < j; i++) {
                if (needle == haystack[i])
                    return true;
            }
            return false;
        }

        function FileValidation() {

            var filename = document.getElementById("file-input-hidden").value; //mp4, 3gp, avi
            // var filetype = ['mp4', '3gp', 'avi', 'mov'];
            var filetype=['pdf','doc','docx'];
            if (filename != '') {
                var ext = getExt(filename);
                ext = ext.toLowerCase();
                var checktype = in_array(ext, filetype);
                if (!checktype) {
                    alert(ext + " <?php echo __d('user', 'file not allowed.', true); ?>");
                    document.getElementById("file-input-hidden").value = '';
                    return false;
                } else {
                    var fi = document.getElementById('file-input-hidden');
                    var filesize = fi.files[0].size;//check uploaded file size in bytes
                    var over_max_size = 20 * 1048576;
                    if (filesize > over_max_size) {
                        alert('Maximum 20MB '+" <?php echo __d('user', 'file size allowed for file.', true); ?>");
                        alert("<?php // echo __d('user', 'Maximum 40MB file size allowed for CV Document.', true); ?>");
                        document.getElementById("file-input-hidden").value = '';
                        return false;
                    }
                }
            }
            return true;
        }
</script>
