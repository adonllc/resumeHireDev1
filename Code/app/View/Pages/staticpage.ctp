<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#contactUs").validate();
        $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_ ]+$/.test(value);
        }, "*<?php echo __d('user', 'Note: Special characters, number and spaces are not allowed', true); ?>.");
    });

</script>
<section class="slider_abouts">
    <div class="breadcrumb-container">
        <nav class="breadcrumbs page-width breadcrumbs-empty">

            <h3 class="head-title"><?php
                $static_page_title = 'static_page_title';
                $static_page_description = 'static_page_description';
                if ($_SESSION['Config']['language'] != 'en') {
                    $static_page_title = 'static_page_title_' . $_SESSION['Config']['language'];
                    $static_page_description = 'static_page_description_' . $_SESSION['Config']['language'];
                }
                echo $pagedetails['Page'][$static_page_title];
                ?></h3>

            <a href="<?php echo $this->Html->url(array("controller" => "homes", "action" => 'index','')); ?>"><?php echo __d('user', 'Home', true) ?></a>

            <span class="divider">/</span>
            <span> <?php
                $static_page_title = 'static_page_title';
                $static_page_description = 'static_page_description';
                if ($_SESSION['Config']['language'] != 'en') {
                    $static_page_title = 'static_page_title_' . $_SESSION['Config']['language'];
                    $static_page_description = 'static_page_description_' . $_SESSION['Config']['language'];
                }
                echo $pagedetails['Page'][$static_page_title];
                ?> </span>

        </nav>
    </div>
</section>




<section class="abouts_section">
    <div class="about-content-area pb-100 pt-100 clearfix">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-lg-8">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="about-details" data-aos="fade-down">
                                <?php echo $pagedetails['Page'][$static_page_description]; ?>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-5 col-lg-4 align-items-center themeix-high">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="send_box">
                                <div class="call-back themeix-high">
                                    <?php echo $this->Form->create(null, array('enctype' => 'multipart/form-data', 'name' => 'contactUs', 'id' => 'contactUs')); ?>
                                    <?php echo $this->Session->flash(); ?>
                                    <p><span class="title"><?php echo __d('home', 'Send us message', true) ?></span></p>
                                    <p>
                                        <?php echo $this->Form->text('User.name', array('placeholder' => __d('user', 'Name', true).'*', 'size' => '20', 'label' => '', 'div' => false, 'class' => "required validname")) ?>
                                    </p>
                                    <p>
                                       <?php echo $this->Form->text('User.email', array('placeholder' => __d('user', 'Email Address', true).'*', 'size' => '20', 'label' => '', 'div' => false, 'class' => "required email")) ?>
                                    </p>
                                    <p>
<!--                                        <select class="custom-multi-select select2-hidden-accessible required" name="subject" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                            <option value="">Please Select</option>
                                            <option value="General Query">General Query</option>
                                            <option value="Personal Feedback">Personal Feedback</option>
                                            <option value="Pre Sale Question">Pre Sale Question</option>
                                            <option value="Support Issue">Support Issue</option>
                                            <option value="Refund Issue">Refund Issue</option>
                                        </select>-->
                                        <?php 
                                        $lList = array(
                                            "General Query"=>__d('home', 'General Query', true),
                                            "Personal Feedback"=>__d('home', 'Personal Feedback', true),
                                            "Pre Sale Question"=>__d('home', 'Pre Sale Question', true),
                                            "Support Issue"=>__d('home', 'Support Issue', true),
                                            "Refund Issue"=>__d('home', 'Refund Issue', true)
                                        );
                                echo $this->Form->select('User.subject', $lList, array( 'class'=>"custom-multi-select select2-hidden-accessible required", 'empty'=>'Please Select'));
//                                echo $this->Form->text('User.location', array('div' => false, 'class' => "form-control required", 'placeholder'=>'Enter Location'))
                              ?>
                                    </p>
                                   <?php echo $this->Form->textarea('User.message', array('placeholder' => __d('user', 'Message', true).'*', 'class' => 'required ', 'size' => '50', 'rows' => 5, 'cols' => 5, 'label' => '', 'div' => false, 'no-resize' => true)); ?>
                                        	
                                    </textarea>
                                    <button type="submit" class="mt-2 buttonfx curtainup"><?php echo __d('user', 'Send Message', true) ?></button>
                                    <?php echo $this->Form->end(); ?>	
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .about-details ol li ol {
    padding-left: 18px;
}
.about-details ol li ol li {
    list-style-type: lower-alpha;
}
.about-details ol li {
    display: list-item;
    text-align: -webkit-match-parent;
    list-style-type: decimal;
}
   .about-details ul li {
    display: list-item;
    text-align: -webkit-match-parent;
    list-style-type: disc;
}
    </style>