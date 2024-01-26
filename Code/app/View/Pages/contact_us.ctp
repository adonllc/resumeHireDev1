<script src="https://maps.googleapis.com/maps/api/js" type="text/javascript"></script>
<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#contactUs").validate();
          $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_ ]+$/.test(value);
        }, "*<?php echo __d('user', 'Note: Special characters, number and spaces are not allowed', true); ?>.");
    });

</script>

<script>
    var map;
    function Init() {
        var geocoder = new google.maps.Geocoder();    // instantiate a geocoder object
        var address = '<?php echo $contact_details['Setting']['address']; ?>';

        geocoder.geocode({'address': address}, function (results, status) {
            var addr_type = results[0].types[0];	// type of address inputted that was geocoded

            if (status == google.maps.GeocoderStatus.OK) {
                ShowLocation(results[0].geometry.location, address, addr_type);
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });

        map = new google.maps.Map(document.getElementById('map-canvas'));
    }

    function ShowLocation(latlng, address, addr_type) {
        map.setCenter(latlng);
        var zoom = 15;
        map.setZoom(zoom);

        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            title: 'address'
                    //icon : "<?php //echo bloginfo('template_url');    ?>/images/map_icon.png",
        });

        var contentString = '<div class="info_box">' +
                '<div class="inflfour">Details from google maps</div>' +
                '<div class="inflfive"><?php echo $contact_details['Setting']['address']; ?></div>' +
                //'<div class="inflsix"><a href="<?php //echo bloginfo('url');    ?>">www.faircomny.com</a></div>'+
                '<div class="inflseven"><?php echo $contact_details['Setting']['contact']; ?></div>' +
                //'<div class="infleight"><a href="<?php //echo $googleplus_link[0];    ?>">Google+ page</a></div>'+
                +'</div>' + '</div>'; 	// HTML text to display in the InfoWindow
        var infowindow = new google.maps.InfoWindow({content: contentString});
        google.maps.event.addListener(marker, 'mouseover', function () {
            infowindow.open(map, marker);
        });
    }

    google.maps.event.addDomListener(window, 'load', Init);

</script>
<script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>


<script>
//      var recaptcha1;
    var recaptcha_register;
    var myCallBack = function () {
        //Render the recaptcha1 on the element with ID "recaptcha1"
        if ($("#recaptcha_register").length > 0) {
            recaptcha_register = grecaptcha.render('recaptcha_register', {
                'sitekey': '<?php echo CAPTCHA_KEY; ?>', //Replace this with your Site key
                'theme': 'light'
            });
        }

    };
</script>
<section class="slider_abouts">
	<div class="breadcrumb-container">
    <nav class="breadcrumbs page-width breadcrumbs-empty">
      
      <h3 class="head-title"><?php echo __d('user', 'Contact Us', true);?></h3>
        <a href="<?php echo $this->Html->url(array("controller" => "homes", "action" => 'index','')); ?>" title="Back to the frontpage"><?php echo __d('user', 'Home', true) ?></a>
          <span class="divider">/</span>
          <span><?php echo __d('user', 'Contact Us', true);?></span>
    </nav>
</div>
</section>

<section class="contact_us">
    <div class="job-post-details-area pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-7">
                    <div class="contact-wrapper">
                       
                        <div class="google-maps mb-4">
                            <!--<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14237.299956549761!2d75.8005658!3d26.8614139!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb53d8d49ec204540!2sLogicSpice%20Consultancy%20Private%20Limited!5e0!3m2!1sen!2sin!4v1575443976281!5m2!1sen!2sin" style="border:0;width:100%;height:450px;" allowfullscreen=""></iframe>-->
                            <iframe src = "https://maps.google.com/maps?q=<?php echo $contact_details['Setting']['address'] ?>&hl=es;z=14&amp;output=embed" style="border:0;width:100%;height:450px;" allowfullscreen=""></iframe>
                        </div>
                        <!--<p>Nrem ipsum dolor sit amet, eleifend nunc tellus turpis. Eu lorem urna liberve bulumfermentum interdum dui commodo natoque libero pretium, sapien commodo, urna nunc, adipiscing laoreet pellentesque. Molestie erat sem</p>-->
                        <div class="contact-form">
                            <div class="section-title inner-section">
                                <h3><?php echo __d('user', 'Contact Form', true);?></h3>
                            </div>
                            <div class="cont_ac">
<!--                    <div class="con_left">
                       
                        <?php
                        if (empty($contact_details['Setting']['company_name']) && empty($contact_details['Setting']['contact']) && empty($contact_details['Setting']['email']) && empty($contact_details['Setting']['address'])) {
                            
                        } else {
                            ?>
                            <div class="inputs"> 
                                <div class="copmanys copmanys_name"> 
                                    <small><i class="fa fa-institution blubx"></i></small>
                                    <div class="metios"> 
                                        <em>Company Name</em>
                                        <b>
                                            <?php
                                            if ($contact_details['Setting']['company_name']) {
                                                echo $contact_details['Setting']['company_name'];
                                            }
                                            ?>
                                        </b>
                                    </div>
                                </div>
                                <?php if ($contact_details['Setting']['address']) { ?>
                                    <div class="copmanys copmanys_address">
                                        <small><i class="fa fa-home blubx"></i></small>
                                        <div class="metios">
                                            <em>Address</em>
                                            <b>
                                                <?php echo $contact_details['Setting']['address']; ?>
                                            </b>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($contact_details['Setting']['contact']) { ?>
                                    <div class="copmanys copmanys_contace">
                                        <small><i class="fa fa-phone-square blubx"></i></small>
                                        <div class="metios">
                                            <em>Contact</em>
                                            <b>
                                                <?php echo $contact_details['Setting']['contact']; ?>
                                            </b>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($contact_details['Setting']['email']) { ?>
                                    <div class="copmanys copmanys_email">
                                        <small><i class="fa fa-envelope-o blubx"></i></small>
                                        <div class="metios">
                                            <em>Email</em>
                                            <b>
                                                <?php echo $contact_details['Setting']['email']; ?>
                                            </b>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <span class="cont"> <?php //echo $this->Html->image('front/cont.png',array('alt'=>''));    ?></span>

                        </div>
                        <?php
                        if ($contact_details['Setting']['address'] && $contact_details['Setting']['latitude'] && $contact_details['Setting']['longitude']) {
                            $stylecss = '';
                        } else {
                            $stylecss = 'width:100%;';
                        }
                        ?>


                        <?php if ($contact_details['Setting']['address'] && $contact_details['Setting']['latitude'] && $contact_details['Setting']['longitude']) { ?>

                        <?php } ?>
                    </div>-->

                    <div class="rig_con" style="<?php echo $stylecss; ?>">

                        <div class="cgtr">
                            <?php echo $this->Form->create(null, array('enctype' => 'multipart/form-data', 'name' => 'contactUs', 'id' => 'contactUs')); ?>
                            <?php echo $this->Session->flash(); ?>
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                           

                                <div class="form-group">
                                    <?php echo $this->Form->text('User.name', array('placeholder' => __d('user', 'Name', true).'*', 'size' => '20', 'label' => '', 'div' => false, 'class' => "required validname")) ?>
                                </div>
                           
                                     </div>
                            <div class="col-lg-6 col-md-6">

                                <div class="form-group">
                                    <?php echo $this->Form->text('User.subject', array('placeholder' => __d('user', 'Subject', true).'*', 'size' => '20', 'label' => '', 'div' => false, 'class' => "required")) ?>
                                </div>

                            </div>
                             <div class="col-lg-12 col-md-12">                               

                                <div class="form-group">
                                    <?php echo $this->Form->text('User.email', array('placeholder' => __d('user', 'Email Address', true).'*', 'size' => '20', 'label' => '', 'div' => false, 'class' => "required email")) ?>
                                </div>
                            </div>
                            
                                 <!-- <div class="col-lg-6 col-md-6">  
                                     <div class="form-group">
                                    <?php echo $this->Html->image($this->Html->url(array('controller' => 'users', 'action' => 'captcha'), true), array('style' => '', 'vspace' => 2, 'id' => 'captcha')); ?>                      
                                    <a href="javascript:void(0);" onclick="document.getElementById('captcha').src = '<?php echo $this->Html->url('/users/captcha'); ?>?' + Math.round(Math.random(0) * 1000) + 1"> <img src="<?php echo HTTP_IMAGE . "/front/captcha_refresh.gif"; ?>" width="35"></a>

                                     </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6">                                   
                                    <div class="form-group">
                                        <?php echo $this->Form->text('User.captcha', array('placeholder' => __d('user', 'Security Code', true).'*', 'size' => '20', 'label' => '', 'div' => false, 'id' => 'captcha', 'class' => "required ")) ?>
                                    </div>
                                
                                </div> -->


                             <div class="col-lg-12 col-md-12">                                    
                                <div class="form-group">
                                    <?php echo $this->Form->textarea('User.message', array('placeholder' => __d('user', 'Message', true).'*', 'class' => 'required ', 'size' => '50', 'rows' => 5, 'cols' => 5, 'label' => '', 'div' => false, 'no-resize' => true)); ?>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6">  
                                <div class="">
                                    <div id="recaptcha_register" style="    transform: scale(0.8);-webkit-transform: scale(0.8);transform-origin: 0 0;-webkit-transform-origin: 0 0;"></div>
                                    <div class="gcpc" id="captcha_msg"></div>
                                </div>
                                </div>
                                
                            <div class="col-lg-12 col-md-12">
                                <div class="login_boxes0">
                                    <div class="input_box"> 
                                        <?php echo $this->Form->submit(__d('user', 'Submit', true), array('class' => 'btn-c buttonfx curtainup', 'size' => '30', 'label' => '', 'div' => false)) ?> 
                                        <label>&nbsp;</label>
                                        <?php echo $this->Form->reset(__d('user', 'Reset', true), array('class' => ' btn-c buttonfx curtainup', 'maxlength' => '50', 'size' => '30', 'label' => '', 'div' => false, 'value' => __d('user', 'Reset', true))) ?>
                                    </div>

                                </div>
                            </div>
                           
                        </div>
                        </div>
                        <?php echo $this->Form->end(); ?>	

                    </div>
                    <div class="clear"></div>

                </div>
                        </div>
                       
                    </div>
                </div>
                <div class="col-lg-4 col-md-5">
                    <div class="right-sidebar">
                        <div class="sidebar-widget mb-4">
                            <div class="sidebar-title">
                                <h3><?php echo __d('user', 'Search Jobs', true) ?></h3>
                            </div>
                            <div class="sidebar-details">
                                <?php echo $this->Form->create("Job", array('url' => array('controller' => 'jobs', 'action' => 'listing'), 'enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'searchJob1', 'name' => 'searchJob1')); ?>
                                    <div class="form-group"><input type="search" class="from-control " placeholder="<?php echo __d('user', 'Keywords', true) ?>"></div>
                                    <button type="submit" class="buttonfx curtainup"><?php echo __d('home', 'Search', true) ?></button>
                                    <?php // echo $this->Form->submit(__d('home', 'Search', true), array('div' => false, 'label' => false, 'class' => 'buttonfx curtainup')); ?>
                                 <?php echo $this->Form->end(); ?>
                            </div>
                        </div>
                        <div class="sidebar-widget">
                            <div class="sidebar-title">
                                <h3><?php echo __d('home', 'Contact info', true) ?></h3>
                            </div>
                            <div class="sidebar-details">
                                <div class="contact-details  ">
                                    <div class="icon"><i class="fa fa-envelope-o"></i></div>
                                    <div class="contact-info">
                                        <p><?php echo __d('user', 'Email', true) ?>: <span><?php echo $contact_details['Setting']['email']; ?></span></p>
                                    </div>
                                </div>
                                <div class="contact-details">
                                    <div class="icon"><i class="fa fa-phone"></i></div>
                                    <div class="contact-info">
                                        <p><?php echo __d('home', 'Phone', true) ?>: <span><?php echo $contact_details['Setting']['contact']; ?></span></p>
                                    </div>
                                </div>
                                <div class="contact-details  ">
                                    <div class="icon"><i class="fa fa-map-marker"></i></div>
                                    <div class="contact-info">
                                        <p><?php echo __d('user', 'Location', true) ?>: <span><?php echo $contact_details['Setting']['address']; ?></span></p>
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
