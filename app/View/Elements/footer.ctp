<?php
$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'title'));
$facebook_link = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'facebook_link'));
$instagram_link = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'instagram_link'));
$linkedin_link = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'linkedin_link'));
$pintrest_link = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'pinterest'));
$enquiry_mail = $this->requestAction(array('controller' => 'App', 'action' => 'getMailConstant', 'enquiry_mail'));
$company_name = ClassRegistry::init('Setting')->field('company_name', array('Setting.id' => 1));
$curl = $this->params->url;

?> 
<footer class="footer">
    <div class="bottom_footer">
        <div class="container">
            <div class="row">

                <div class="col-sm-3 col-md-3">
                    <ul class="list-unstyled clear-margins">
                        <li class="widget-container widget_nav_menu">
                            <div class="title-widget ftdrop1"><span><?php echo __d('home', 'Jobseekers', true); ?></span></div>
                            <div class="title-widget mobile_sh"><span><?php echo __d('home', 'Jobseekers', true); ?></span></div>
                            <div class="ftblock1">
                                <ul class="user_link">
                                    <li>
                                        <?php echo $this->Html->link(__d('user', 'Search Jobs', true), array('controller' => 'jobs', 'action' => 'listing'), array('class' => '')); ?>
                                    </li>
                                    <li>
                                        <?php echo $this->Html->link(__d('home', 'Jobseeker Login', true), array('controller' => 'users', 'action' => 'login'), array('class' => '')); ?>
                                    </li>
                                    <li>
                                        <?php echo $this->Html->link(__d('home', 'Create Job alert', true), array('controller' => 'alerts', 'action' => 'add'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?>
                                    </li>
                                    <li>
                                        <?php echo $this->Html->link(__d('user', 'Experience', true), array('controller' => 'candidates', 'action' => 'editExperience'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?>
                                    </li>
                                    <li>
                                        <?php echo $this->Html->link(__d('user', 'Education', true), array('controller' => 'candidates', 'action' => 'editEducation'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?>
                                    </li>
                                    
                                    <li>
                                        <?php echo $this->Html->link(__d('user', 'Browse Jobs', true), array('controller' => 'jobs', 'action' => 'listing'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?>
                                    </li>
                                     
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-3 col-md-3">
                    <ul class="list-unstyled clear-margins">
                        <li class="widget-container widget_nav_menu">
                            <div class="title-widget ftdrop2"><span><?php echo __d('home', 'About Us', true); ?></span></div>
                            <div class="title-widget mobile_sh"><span><?php echo __d('home', 'About Us', true); ?></span></div>
                            <div class="ftblock2">
                                <ul class="user_link">
                            <?php
                            $about_us = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'about-us'));
                            if ($about_us == 1) {
                                echo '<li>' . $this->Html->link(__d('home', 'About Us', true), array('controller' => 'pages', 'action' => 'about_us'), array('rel' => 'nofollow')) . '</li>';
                            }
                            $faq = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'faq'));
                            if ($faq == 1) {
                                echo '<li>' . $this->Html->link(__d('home', 'FAQ', true), array('controller' => 'pages', 'action' => 'staticpage', 'faq'), array('rel' => 'nofollow')) . '</li>';
                            }
                            $privacy_policy = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'privacy-policy'));
                            if ($privacy_policy == 1) {
                                echo '<li>' . $this->Html->link(__d('home', 'Privacy Policy', true), array('controller' => 'pages', 'action' => 'staticpage', 'privacy-policy'), array('rel' => 'nofollow')) . '</li>';
                            }
                            ?>
                                    <li><?php echo $this->Html->link(__d('home', 'Contact us', true), '/contact-us', array('rel' => 'nofollow')); ?></li>
                            <li><?php echo $this->Html->link(__d('home', 'Sitemap', true), '/sitemap.html', array('rel' => 'nofollow')); ?></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-3 col-md-3">
                    <ul class="list-unstyled clear-margins">
                        <li class="widget-container widget_nav_menu">
                            <div class="title-widget ftdrop3"><span><?php echo __d('home', 'Quick Links', true); ?></span></div>
                            <div class="title-widget mobile_sh"><span><?php echo __d('home', 'Quick Links', true); ?></span></div>
                            <div class="ftblock3">
                                <ul class="user_link">
                                    <?php
                            

                            $saved_jobs = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'saved-jobs'));
                            if ($saved_jobs == 1) {
                                echo '<li>' . $this->Html->link(__d('home', 'Saved Jobs', true), array('controller' => 'pages', 'action' => 'staticpage', 'saved-jobs'), array('rel' => 'nofollow')) . '</li>';
                            }

                            $companies = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'companies'));
                            if ($companies == 1) {
                                echo '<li>' . $this->Html->link(__d('home', 'Companies', true), array('controller' => 'pages', 'action' => 'staticpage', 'companies'), array('rel' => 'nofollow')) . '</li>';
                            }

                            $career_tools = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'career-tools'));
                            if ($career_tools == 1) {
                                echo '<li>' . $this->Html->link(__d('home', 'Career tools', true), array('controller' => 'pages', 'action' => 'staticpage', 'career-tools'), array('rel' => 'nofollow')) . '</li>';
                            }

                            $career_resources = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'career-resources'));
                            if ($career_resources == 1) {
                                echo '<li>' . $this->Html->link(__d('home', 'Career Resources', true), array('controller' => 'pages', 'action' => 'staticpage', 'career-resources'), array('rel' => 'nofollow')) . '</li>';
                            }

                            

                            $benefits = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'benefits'));
                            if ($benefits == 1) {
                                echo '<li>' . $this->Html->link(__d('home', 'Benefits', true), array('controller' => 'pages', 'action' => 'staticpage', 'benefits'), array('rel' => 'nofollow')) . '</li>';
                            }

                            $post_a_job = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'post-a-job'));
                            if ($this->Session->read('user_id') && $this->Session->read('user_type') == 'recruiter') {
                                echo '<li>' . $this->Html->link(__d('home', 'Post a Job', true), array('controller' => 'jobs', 'action' => 'createJob'), array('rel' => 'nofollow')) . '</li>';
                            } else{
                                 echo '<li>' . $this->Html->link(__d('home', 'Post a Job', true), array('controller' => 'users', 'action' => 'employerlogin'), array('rel' => 'nofollow')) . '</li>';
                            }
                           $find_a_job = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'find-a-job'));
//                            if ($find_a_job == 1) {
                               // echo '<li>' . $this->Html->link(__d('home', 'Find a Job', true), array('controller' => 'jobs', 'action' => 'listing'), array('rel' => 'nofollow')) . '</li>';
//                            }

                            $resignation_sample = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'resignation-sample'));
                            if ($resignation_sample == 1) {
                                echo '<li>' . $this->Html->link(__d('home', 'Resignation Sample', true), array('controller' => 'pages', 'action' => 'staticpage', 'resignation-sample'), array('rel' => 'nofollow')) . '</li>';
                            }

                            $resume_sample = classregistry::init('Page')->field('status', array('Page.static_page_heading' => 'resume-sample'));
                            if ($resume_sample == 1) {
                                echo '<li>' . $this->Html->link(__d('home', 'Resume Sample', true), array('controller' => 'pages', 'action' => 'staticpage', 'resume-sample'), array('rel' => 'nofollow')) . '</li>';
                            }
                            ?>
                           
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-3 col-md-3">
                    <div class="list-unstyled clear-margins">
                       
                            <div class="title-widget ftdrop4"><span><?php echo __d('home', 'Follow Us', true); ?></span></div>
                            <div class="title-widget mobile_sh"><span><?php echo __d('home', 'Follow Us', true); ?></span></div>
                            <div class="ftblock4">
                                <div class="social_icon">
                                    <a href="<?php echo $facebook_link; ?>" target="_new" class=""><i class="fa fa-facebook"></i></a>
                                    <a href="<?php echo $instagram_link; ?>" target="_new" class=""><i class="fa fa-instagram"></i></a>
                                    <a href="<?php echo $linkedin_link; ?>" target="_new" class=""><i class="fa fa-linkedin"></i></a>
                                    <a href="<?php echo $pintrest_link; ?>" target="_new" class=""><i class="fa fa-pinterest"></i></a>
                                </div>
                                <div class="langauge">


<!--                            <select id="pet-select">

                                <option value="dog">English</option>
                                <option value="cat">Spanish</option>
                                <option value="hamster">Portuguese</option>
                                <option value="parrot">Japanese</option>

                            </select>-->
                                    <span>
                                    <select class="form-control" id="changelanguage">
                                        <option <?php
                                        if ($_SESSION['Config']['language'] == 'en') {
                                            echo 'selected="selected"';
                                        }
                                        ?> data-url="<?php echo HTTP_PATH . '/setlanguage/en?curl=' . $curl ?>"><?php echo __d('home', 'English', true) ?></option> 
                                        <option <?php
                                        if ($_SESSION['Config']['language'] == 'fra') {
                                            echo 'selected="selected"';
                                        }
                                        ?> data-url="<?php echo HTTP_PATH . '/setlanguage/fra?curl=' . $curl ?>"><?php echo __d('home', 'French', true) ?></option>
                                        <option <?php
                                        if ($_SESSION['Config']['language'] == 'de') {
                                            echo 'selected="selected"';
                                        }
                                        ?> data-url="<?php echo HTTP_PATH . '/setlanguage/de?curl=' . $curl ?>"><?php echo __d('home', 'German', true) ?></option>
                                    </select>
                                    </span>
                        </div>
                            </div>
                       
                    </div>
                </div>

             
             
            </div>
            
            <div class="footer-bottom-area clearfix">
            <div class="container">
               <div class="row">
                  <div class="col-md-12">
                     <div class="footer-bottom text-center">
                         <p style="display: none;"> <?php //echo __d('user', 'Copyright', true) ?> © <?php //echo date("Y"); ?> | <?php //echo $company_name; ?> | <a target="_blank" href="https://www.logicspice.com/job-board-software" >White Labeled Recruitment Software</a></p>
                         <p> © <?php echo __d('user', 'Copyright', true) ?> @ <?php echo date("Y"); ?> | <a href="https://resumehire.net" target="_blank">Resumehire</a>. All Rights Reserved</p>
                        
                     </div>
                  </div>
               </div>
            </div>
         </div>
        </div>
    </div>
     
</footer>
<script>
    function offpop() {
        $('#loginModal').hide();
        $('.modal-backdrop').remove();
    }

    function loginpop() {
        $('#forgotpassword').hide();
        $('.modal-backdrop').remove();
        $('#loginModal').show();
    }
</script>

<!-- Login Popup -->

<div id="loginModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $site_title; ?> <?php echo __d('home', 'login', true); ?></h4>
                <div id="loaderID" style="display:none; position:absolute;"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
            </div>
            <div id="signinform">
                <?php echo $this->element("login"); ?>
            </div>
            <div class="modal-footer text-center">
                <p>Not a member as yet? <?php echo $this->Html->link(__d('home', 'Jobseeker Register', true), array('controller' => 'users', 'action' => 'register', 'jobseeker'), array('rel' => 'nofollow')); ?> or  <?php echo $this->Html->link('Employer Register', array('controller' => 'users', 'action' => 'register', 'employer'), array('rel' => 'nofollow')); ?> </p>
            </div>
        </div>

    </div>
</div>



<!-- Forgot password -->

<div id="forgotpassword" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $site_title; ?> <?php echo __d('home', 'forgot password', true); ?></h4>
                <div id="loaderID" style="display:none; position:absolute;"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
            </div>
            <div id="signinform">
                <?php echo $this->element("forgotpassword"); ?>
            </div>
            <div class="modal-footer text-center">

<!--                <span>Back to <?php //echo $this->Html->link('Sign in!', array('controller' => 'users', 'action' => 'login'), array('escape' => false, 'rel' => 'nofollow'));    ?></span>-->

                <p class="fg_lk" data-toggle="modal" data-target="#forgotpassword">
                    <?php //echo $this->Html->link('Forgot your password?', array('controller' => 'users', 'action' => 'forgotPassword'), array('escape' => false,'rel'=>'nofollow')); ?>
                    <?php echo __d('home', 'Back to', true); ?> <a class="text-center" onclick="loginpop()"><?php echo __d('home', 'Login', true); ?></a>
                </p>

            </div>
        </div>

    </div>
</div>


<!-- feedback Popup -->

<div class="feedback_popup_section">
    <div class="feedback_button"></div>

    <div class="feedback_form">
        <div id="loaderID" class="loasd_sehcf" style="display:none; position:absolute !important; top:0 !important; right:0 !important; bottom:0 !important; left:0 !important; text-align: center !important;"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
        <div id="reportform">
            <div id="ref" class="success_msg success_lo" style="display: none;">
                <span id="report_message" class="span_text"></span>
            </div>
            <?php echo $this->element("report"); ?>

        </div>


    </div>
</div>
<script>
    $.validator.messages.required = "<?php echo __d('user', 'This field is required', true); ?>";
    $.validator.messages.email = "<?php echo __d('user', 'Please enter a valid email address', true); ?>";
    $.validator.messages.number = "<?php echo __d('user', 'Please enter a valid number', true); ?>";
    $.validator.messages.max = "<?php echo __d('user', 'Please enter a value less than or equal to', true); ?> {0}";
    $.validator.messages.min = "<?php echo __d('user', 'Please enter a value greater than or equal to', true); ?> {0}";
    $.validator.messages.maxlength = "<?php echo __d('user', 'Please enter no more than', true); ?> {0} <?php echo __d('user', 'characters', true); ?>";
        $.validator.messages.minlength = "<?php echo __d('user', 'Please enter at least', true); ?> {0} <?php echo __d('user', 'characters', true); ?>";
            $.validator.messages.equalTo = "<?php echo __d('user', 'Please enter the same value again', true); ?>";
            $.validator.messages.url = "<?php echo __d('user', 'Please enter a valid URL', true); ?>";
            $.validator.messages.digits = "<?php echo __d('user', 'Please enter only digits', true); ?>";
            $.validator.messages.pass = "<?php echo __d('user', 'Minimum 8 characters. Must include 1 lower and 1 upper case letter. Must include 1 number', true); ?>";
</script>



<script type="text/javascript">
// Select dropdowns

    $("select").addClass("not_chosen");
    if ($('select').length) {

        // Traverse through all dropdowns
        $.each($('select'), function (i, val) {
            var $el = $(val);

            // If there's any dropdown with default option selected
            // give them `not_chosen` class to style appropriately
            // We assume default option to have a value of ''
            if (!$el.val()) {
                $el.addClass('not_chosen');
            }

            // Add change event handler to do the same thing,
            // i.e., adding/removing classes for proper
            // styling. Basically we're emulating placeholder
            // behaviour on select dropdowns.
            $el.on('change', function () {
                if (!$el.val())
                    $el.addClass('not_chosen');
                else
                    $el.removeClass('not_chosen');
            });

            // end of each callback
        });
    }
</script>



<script>
    $(document).ready(function () {
        $(".feedback_button").click(function () {
            $("#report_message").empty();
            $("#ref").hide();
            $(".feedback_form").slideToggle();
            $(".feedback_button").toggleClass("off");

            $(".feedback_form").toggleClass("box");
        });
        setInterval(request, 6000);
    });
$("#changelanguage").change(function () {
            var url = $('option:selected', this).attr('data-url');
            if (url) {
                window.location.href = url;
            }
        });
    $(document).on("keyup", ".keyword-box", function (e) {
        e.preventDefault();
        $(".common-serach-box").hide();
        var suggesstion = $(this).data('suggesstion');
        var search = $(this).data('search');
        var ids = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: "<?php echo HTTP_PATH ?>/keywords/ajaxkeywordlist",
            data: 'keyword=' + $(this).val() + '&suggesstion=' + suggesstion + '&search=' + search + '&ids=' + ids,
            dataType: "html",
            beforeSend: function () {
//			$(this).css("background"," url(img/loading.gif) no-repeat 125px");
            },
            success: function (data) {
                $("#" + suggesstion).show();
                $("#" + suggesstion).html(data);
                $(this).css("background", "none");
            }
        });
    });
    $(document).on("keyup", ".specialty-box", function (e) {
        e.preventDefault();
        $(".common-serach-box").hide();
        var suggesstion = $(this).data('suggesstion');
        var search = $(this).data('search');
        var ids = $(this).attr('id');
        var graduation = $("#" + $(this).data('graduation')).val();
        $.ajax({
            type: "POST",
            url: "<?php echo HTTP_PATH ?>/keywords/ajaxspecialtylist",
            data: 'keyword=' + $(this).val() + '&suggesstion=' + suggesstion + '&search=' + search + '&ids=' + ids + '&graduation=' + graduation,
            dataType: "html",
            beforeSend: function () {
//			$(this).css("background"," url(img/loading.gif) no-repeat 125px");
            },
            success: function (data) {
                $("#" + suggesstion).show();
                $("#" + suggesstion).html(data);
                $(this).css("background", "none");
            }
        });
    });

    function selectKeyword(val, ids, suggesstion) {
        $("#" + ids).val(val);
        $("#" + suggesstion).hide();
    }

<?php //if(isset($_SESSION['locationid']) && $_SESSION['locationid'] > 0){   ?>
    function request() {
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/users/countJob/",
            cache: false,
            data: {},
            beforeSend: function () {

            },
            complete: function () {

            },
            success: function (result) {
                var obj = JSON.parse(result);
                if ((obj.jobcount > obj.cokkiecount) || obj.viewed == 0) {
                    $('#bells').html('<a href="<?php echo HTTP_PATH ?>/jobs"><i class="fa fa-bell"></i><span class="ncr">' + obj.jobcount + '</span></a>');
                }

            }
        });
    }

<?php // }   ?>
</script>
<script>
    $.validator.messages.required = "<?php echo __d('user', 'This field is required', true); ?>";
    $.validator.messages.email = "<?php echo __d('user', 'Please enter a valid email address', true); ?>";
    $.validator.messages.number = "<?php echo __d('user', 'Please enter a valid number', true); ?>";
    $.validator.messages.max = "<?php echo __d('user', 'Please enter a value less than or equal to', true); ?> {0}";
    $.validator.messages.min = "<?php echo __d('user', 'Please enter a value greater than or equal to', true); ?> {0}";
    $.validator.messages.maxlength = "<?php echo __d('user', 'Please enter no more than', true); ?> {0} <?php echo __d('email', 'characters', true); ?>";
        $.validator.messages.minlength = "<?php echo __d('user', 'Please enter at least', true); ?> {0} <?php echo __d('email', 'characters', true); ?>";
            $.validator.messages.equalTo = "<?php echo __d('user', 'Please enter the same value again', true); ?>";
            $.validator.messages.url = "<?php echo __d('user', 'Please enter a valid URL', true); ?>";
            $.validator.messages.digits = "<?php echo __d('user', 'Please enter only digits', true); ?>";
            $.validator.messages.pass = "<?php echo __d('user', 'Minimum 8 characters. Must include 1 lower and 1 upper case letter. Must include 1 number', true); ?>";
</script>



<script type="text/javascript">
            $(document).ready(function () {
                $('.ftdrop1').click(function () {
                    if ($('.ftdrop1').hasClass('ftopen1')) {
                        $('.ftdrop1').removeClass('ftopen1');
                    } else {
                        $('.ftdrop1').addClass('ftopen1');
                    }
                    $(".ftblock1").slideToggle();
                });
                $('.ftdrop2').click(function () {
                    if ($('.ftdrop2').hasClass('ftopen2')) {
                        $('.ftdrop2').removeClass('ftopen2');
                    } else {
                        $('.ftdrop2').addClass('ftopen2');
                    }
                    $(".ftblock2").slideToggle();
                });
                $('.ftdrop3').click(function () {
                    if ($('.ftdrop3').hasClass('ftopen3')) {
                        $('.ftdrop3').removeClass('ftopen3');
                    } else {
                        $('.ftdrop3').addClass('ftopen3');
                    }
                    $(".ftblock3").slideToggle();
                });
                $('.ftdrop4').click(function () {
                    if ($('.ftdrop4').hasClass('ftopen4')) {
                        $('.ftdrop4').removeClass('ftopen4');
                    } else {
                        $('.ftdrop4').addClass('ftopen4');
                    }
                    $(".ftblock4").slideToggle();
                });
            });
        </script>
  <script>
    $(window).resize(function() {
    var mobileWidth =  (window.innerWidth > 0) ? 
                        window.innerWidth : 
                        screen.width;
    var viewport = (mobileWidth > 360) ?
                    'width=device-width, initial-scale=1.0' :
                    'width=1200';
    $("meta[name=viewport]").attr('content', viewport);
}).resize();   
    </script>