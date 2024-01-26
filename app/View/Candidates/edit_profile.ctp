<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">

    $(document).ready(function () {

        /************************************************************/
        $.validator.addMethod("contact", function (value, element) {
            return  this.optional(element) || (/^[0-9+]+$/.test(value));
        }, "<?php echo __d('user', 'Contact Number is not valid', true); ?>.");
        $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_]+$/.test(value);
        }, "*<?php echo __d('user', 'Note: Special characters, number and spaces are not allowed', true); ?>.");

        $("#editCandidateProfile").validate();
        /************************************************************/

    });

</script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&key=<?php echo AUTO_SUGGESTION; ?>"></script> 
<script>
    var autocomplete;
    var autocomplete2;
    function initialize() {
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('UserLocation')));
        autocomplete2 = new google.maps.places.Autocomplete((document.getElementById('UserPreLocation')));
    }
</script>
<script type="text/javascript">
    window.onload = function () {
        initialize();
    };
</script> 
<script type="text/javascript">

    $(document).ready(function () {

        $('#UserContact').keypress(function (e) {
            if ($('#UserContact').val() != '') {
                $('#UserContact').removeClass('error');
                $('#UserContact').removeClass('required');
                $('#showerror1').removeClass('adderror');
                $('#showerror1').empty();
            }

        });

        $('#UserExpSalary').keypress(function (e) {
            $('#expsalary1').text('');
        });

        $('input[type="submit"]').on('click', function (e) {

            if ($('#UserLocation_chosen').find('span:nth-child(1)').text() == 'Choose location')
            {
                //alert($('#UserLocation_chosen').find('span:nth-child(1)').text() == 'Choose location');
                $('#UserLocation_chosen').addClass('error');
                $('#UserLocation_chosen').addClass('required');
                $('#showerror').addClass('adderror');
                $('#showerror').text('<?php echo __d('user', 'This field is required.', true); ?>');
                e.preventDefault();
            }

            $('#showerror1').removeClass('adderror');
            $('#showerror1').text('');
            if (($('#UserContact').val() == '') || (!$.isNumeric($('#UserContact').val()))) {
                $('#UserContact').addClass('error');
                $('#UserContact').addClass('required');
                $('#showerror1').addClass('adderror');
                $('#showerror1').text('<?php echo __d('user', 'This field is required.', true); ?>');
                e.preventDefault();
            } else if ($('#UserContact').val() !== '') {
                $('#UserContact').removeClass('error');
                $('#UserContact').removeClass('required');
            } else {
                $('#UserContact').removeClass('required');
            }


            $('#location1').removeClass('adderror');
            $('#location1').text('');
            if ($('#UserPreLocation').val() == '' || $('#UserPreLocation').val() == null) {
                $('#UserPreLocation').addClass('error');
                $('#UserPreLocation').addClass('required');
                $('#location1').addClass('adderror');
                $('#location1').text('<?php echo __d('user', 'This field is required.', true); ?>');
                e.preventDefault();
            }

            $('#skillss1').removeClass('adderror');
            $('#skillss1').text('');
            if ($('#UserSkills').val() == '' || $('#UserSkills').val() == null) {
                $('#UserSkills').addClass('error');
                $('#UserSkills').addClass('required');
                $('#skillss1').addClass('adderror');
                $('#skillss1').text('<?php echo __d('user', 'This field is required.', true); ?>');
                e.preventDefault();
            }

            $('#expsalary1').removeClass('adderror');
            $('#expsalary1').text('');
            if ($('#UserExpSalary').val() == '' || $('#UserExpSalary').val() == null) {
                $('#UserExpSalary').addClass('error');
                $('#UserExpSalary').addClass('required');
                $('#expsalary1').addClass('adderror');
                $('#expsalary1').text('<?php echo __d('user', 'This field is required.', true); ?>');
                e.preventDefault();
            }

            $('#abouttext').removeClass('adderror');
            $('#abouttext').text('');
            if ($('#UserCompanyAbout').val() == '' || $('#UserCompanyAbout').val() == null) {
                $('#UserCompanyAbout').addClass('error');
                $('#UserCompanyAbout').addClass('required');
                $('#abouttext').addClass('adderror');
                $('#abouttext').text('<?php echo __d('user', 'This field is required.', true); ?>');
                e.preventDefault();
            }

            $('#totalexp').removeClass('adderror');
            $('#totalexp').text('');
            if ($('#UserTotalExp').val() == '' || $('#UserTotalExp').val() == null) {
                $('#UserTotalExp').addClass('error');
                $('#UserTotalExp').addClass('required');
                $('#totalexp').addClass('adderror');
                $('#totalexp').text('<?php echo __d('user', 'This field is required.', true); ?>');
                e.preventDefault();
            }

        });

        $('.test-size').click(function () {
            var size_checked = $(".test-size input:checked").length;
            if (size_checked == 0) {
                $('#ProductTotalSize').val('');
            } else {
                $('#ProductTotalSize').val(size_checked + ' category selected');
            }
        });




    });

</script>
<?php echo $this->Html->script('jquery/ui/jquery.ui.core.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.widget.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.position.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.datepicker.js'); ?>
<?php echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css'); ?>


<?php echo $this->Html->css('front/sample.css'); ?>


<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<script>
    $(function () {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
</script>



<link href="<?php echo HTTP_PATH; ?>/css/front/uploadfilemulti.css" rel="stylesheet">

<script src="<?php echo HTTP_PATH; ?>/js/front/jquery.fileuploadmulti.min.js" charset="utf-8"></script>
<script>

    $(document).ready(function () {

        $('#locDiv').find("#UserLocation_chosen a span").on("click", function () {
            $('#UserLocation_chosen').removeClass('error');
            $('#showerror').empty();
        });


        var settings = {
            url: "<?php echo HTTP_PATH . "/candidates/uploadmultipleimages" ?>",
            method: "POST",
            dragDropStr: "<span><b></b></span>",
            allowedTypes: "jpg,png,gif,jpeg,doc,docx,pdf",
            fileName: "data[Certificate][document]",
            multiple: true,
            maxFileSize: 1049 * 1000 * 4,
            // maxFileCount:<?php //echo UPLOAD_MAX_IMAGE;                                                             ?>,
            onSelect: function (response, data_re)
            {
                var input = $("#images");
                if (input.val())
                    var array = input.val().split(",");
                else
                    var array = [];
            },
            onSuccess: function (response, data_re, xhr)
            {
                var status = $("#status");
                status.html('');
                var data = $.parseJSON(data_re);
                if (data.status == 'success') {

                    var counter = 0;

                    var html = '';
                    var image_arr = [];

                    var input = $("#images");
                    if (input.val())
                        var array = input.val().split(",");
                    else
                        var array = [];

                    var image = '<?php echo DISPLAY_TMP_CERTIFICATE_PATH; ?>' + data.image;
                    var imagename = data.image;
                    var id1 = imagename.replace('.', '-');
                    if (data.type == 'image') {
                        html += "<div class='image_thumb' alt='" + imagename + "' id='delete_" + id1 + "'> <span class='temp-image-section'><img  src='<?php echo DISPLAY_TMP_CERTIFICATE_PATH ?>" + data.image + "'/></span><span class='delete_image' alt='" + data.image + "'>X </span></div>";
                    } else if (data.type == 'doc') {
                        //html += "<div class='doc_fukll_name'  alt='" + imagename + "' id='delete_" + id1 + "'><div class='doc_files_border'><span class='temp-image-section'><a href='<?php echo HTTP_PATH; ?>/candidates/downloadDocCertificateTemp/"+ data.image +"' class='dfasggs' >"+imagename.substring(6)+"</a><span class='close_icon_for' alt='" + data.image + "'>X </span></span></div></div>";  
                        html += "<div class='doc_fukll_name'  alt='" + imagename + "' id='delete_" + id1 + "'><div class='doc_files_border'><span class='temp-image-section'><a href='<?php echo HTTP_PATH; ?>/candidates/downloadDocCertificateTemp/" + data.image + "' class='dfasggs' >" + imagename.substring(6) + "</a><span class='close_icon_for' alt='" + data.image + "'>X </span></span></div></div>";
                    }

                    array.push(data.image);

                    $("#images").val();

                    input.val(array);
                    if (data.type == 'image') {
                        $(".check").after(html);
                    } else if (data.type == 'doc') {
                        $(".check_doc").after(html);
                    }
                    //$(".loading-image").hide();

                } else {
                    alert(data.message);
                    //$(".loading-image").hide();
                }
            }
            ,
            afterUploadAll: function ()
            {
                $(".upload-statusbar").remove();
            },
            onError: function (files, status, errMsg)
            {
                $("#status").html("<font color='red'>Upload is Failed</font>");
            }
        }
        $("#mulitplefileuploader").uploadFile(settings);

        $(document).on("click", ".delete_image , .close_icon_for", function () {

            var id = $(this).attr('alt');
            var id1 = id.replace('.', '-');
            $("#delete_" + id1).hide();
            var $input = $("#images");

            var arrayOld = $input.val().split(",");
            arrayNew = $.grep(arrayOld, function (image_names, i) {
                //var substr = image_names.split('_');
                //return substr[0] !== id;
                return image_names !== id;
            });
            $input.val(arrayNew);
            $input.val(arrayNew);
            var $input1 = $("#images1");
            var arrayOld = $input1.val().split(",");
            arrayNew = $.grep(arrayOld, function (image_names, i) {
                //var substr = image_names.split('_');
                //return substr[0] !== id;
                return image_names !== id;
            });
            $input1.val(arrayNew);
        })

        /*
         $('#upload_images').submit(function(e) {
         var images = $.trim($('#images').val());
         if (images === '') {
         $(".ajax-upload-dragdrop").css("border-color", "red");
         } else {
         $(".ajax-upload-dragdrop").css("border-color", "#7ba723");
         }
         
         if (images === '') {
         $('.sub_mit').removeAttr('disabled');
         $('.sub_mit').val('Upload');
         return false;
         } else {
         $('.sub_mit').attr('disabled','disabled');
         $('.sub_mit').val('Processing..');
         return true;
         }
         });
         */


        var counter = $('#UserCoverLetterCount').val();
        $("#addButton").click(function () {

            if (counter > 15) {
                alert("<?php echo __d('user', 'You can not add cover latter more than 15', true); ?>.");
                return false;
            }
            $('#MediaGroup').append('<div class="media_box" id="media_box' + counter + '"><span class="cover_ttt"><input type="text"  value="" placeholder="<?php echo __d('user', 'Cover Letter Title', true); ?>" class="form-control required" maxlength="30" name="data[CoverLetter][' + counter + '][title]"></span><span class="cover_desss"><textarea  placeholder="<?php echo __d('user', 'Cover Letter', true); ?>" class="form-control required" name="data[CoverLetter][' + counter + '][description]"></textarea></span><span class="close_icon3_1"><img src="<?php echo HTTP_IMAGE ?>/close.png" id="' + counter + '" class="close" /></span></div>');
            counter++;

            $('img.close').last().click(function () {
                $("#media_box" + (this.id)).remove();
                counter--;
            });
        });
    });






</script>
<style>
    .adderror{ border: 0 solid #f3665c !important;
               color: #f3665c !important;
               font-weight: normal; }
    </style>

    <?php
    echo $this->Html->script('jquery/ui/jquery.ui.menu.js');
    echo $this->Html->script('jquery/ui/jquery.ui.autocomplete.js');
    ?>
    <script type="text/javascript">
        $(function () {
            var availableCode = [<?php echo ClassRegistry::init('PostCode')->postCodeList(); ?>];
            $("#UserPostalCodeId").autocomplete({
                source: availableCode,
                minLength: 1,
                change: function (event, ui) {
                    updateStateCity($("#UserPostalCodeId").val());
                }
            });

        });

    </script>
    <div class="my_accnt">
        <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                <div class="col-lg-9 col-sm-9">
                    <div class="my-profile-boxes info_dv_esdit_pre">
                        <div class="my-profile-boxes-top my-education-boxes"><h2><i><?php echo $this->Html->image('front/home/edit-icon2.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Edit Profile Information', true); ?></span></h2></div>
                        <div class="information_cntn" style="position:inherit !important;">
                            <?php echo $this->element('session_msg'); ?>

                            <?php echo $this->Form->create("Null", array('enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'editCandidateProfile', 'class' => "form_trl_box_show2", 'name' => 'changeprofilepicture')); ?>

                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'First Name', true); ?> <span class="star_red">*</span></label>
                                <div class="form_input_education">
                                    <?php echo $this->Form->text('User.first_name', array('maxlength' => '20', 'label' => '', 'div' => false, 'class' => "form-control required validname", 'placeholder' => __d('user', 'First Name', true))) ?>
                                </div>
                            </div>
                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Last Name', true); ?> <span class="star_red">*</span></label>
                                <div class="form_input_education">
                                    <?php echo $this->Form->text('User.last_name', array('maxlength' => '20', 'label' => '', 'div' => false, 'class' => "form-control required validname", 'placeholder' => __d('user', 'Last Name', true))) ?>
                                </div>
                            </div>
                            <div class="form_list_education">
                               <label class="lable-acc"><?php echo __d('user', 'Gender', true); ?> <div class="star_red">*</div></label>
                                <div class="form_input_education">
                                <div class="form_input_gender">
                                    <div class="dty">
                                        <?php
                                        $options = array('0' => '<label for="UserGender0">' . __d('user', 'Male', true) . '</label>', "1" => '<label for="UserGender1">' . __d('user', 'Female', true) . '</label>');
                                        echo $this->Form->radio('User.gender', $options, array('escape' => false, 'label' => false, 'legend' => false, 'separator' => '</div><div class="dty">', 'default' => '1', 'class' => 'required'));
                                        ?>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Native Location', true); ?> <div class="star_red">*</div></label>
                                <div id="locDiv" class="form_input_education">
                                    <?php echo $this->Form->text('User.location', array('div' => false, 'class' => "form-control required ", 'placeholder' => __d('user', 'Enter Location', true), 'id' => 'UserLocation')); ?>
                                    <label id="showerror"></label>
                                </div>
                            </div>
                            <div class="form_list_education">
                               <label class="lable-acc"><?php echo __d('user', 'Contact Number', true); ?> <span class="star_red">*</span></label>
                                <div class="form_input_education"><?php echo $this->Form->text('User.contact', array('maxlength' => '16', 'minlength' => '8', 'class' => "form-control contact required", 'placeholder' => __d('user', 'Contact Number', true))) ?>
                                    <label id="showerror1"></label>
                                </div>
                            </div>
                            <div class="form_list_education">
                               <label class="lable-acc"><?php echo __d('user', 'Preferred Job Locations', true); ?> <div class="star_red">*</div></label>
                               <div class="form_input_education">
                                    <?php
                                    //echo $this->Form->select('User.pre_location', $locationlList, array('multiple' => true, 'data-placeholder' => 'Choose Preferred Job Locations', 'class' => "chosen-select required"));
                                    echo $this->Form->text('User.pre_location', array('div' => false, 'class' => "form-control required ", 'placeholder' => __d('user', 'Enter Location', true), 'id' => 'UserPreLocation'));
                                    ?>
                                    <label id="location1"></label>
                                </div>
                            </div>
                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Skills', true); ?> <div class="star_red">*</div></label>
                                <div id="cust_skl" class="form_input_education rel_Skills">
                                    <?php echo $this->Form->select('User.skills', $skillList, array('multiple' => true, 'data-placeholder' => __d('user', 'Choose Skills', true), 'class' => "chosen-select required")); ?>
                                    <label id="skillss1"></label>
                                </div>
                            </div>


                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Expected Salary (In USD)', true); ?> <span class="star_red">*</span></label>
                               <div class="form_input_education">
                                    <?php echo $this->Form->text('User.exp_salary', array('maxlength' => '20', 'class' => "form-control required digits", 'placeholder' => __d('user', 'Expected Salary (In USD)', true))) ?>
                                    <label id="expsalary1"></label>
                                </div>
                            </div>

                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Total Work Experience', true); ?> <div class="star_red">*</div></label>
                                <div id="locDiv" class="form_input_education qualification-select">
                                    <span>
                                    <?php
                                    global $totalexperienceArray;
                                    echo $this->Form->select('User.total_exp', $totalexperienceArray, array('empty' => __d('user', 'Select Total Experience', true), 'class' => "form-control required"));
                                    ?>
                                    <label id="totalexp"></label>
                                    </span>
                                </div>
                            </div>

                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'About Your Self', true); ?> <div class="star_red">*</div></label>
                                <div id="locDiv" class="form_input_education">
<?php echo $this->Form->textarea('User.company_about', array('class' => "form-control", 'placeholder' => __d('user', 'About Your Self', true))) ?>
                                    <label id="abouttext"></label>
                                </div>
                            </div>
                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'YouTube URL', true); ?> </label>
                                <div class="form_input_education"><?php echo $this->Form->text('User.url', array('class' => "form-control url", 'placeholder' => __d('user', 'YouTube URL', true))) ?>
                                    Eg.: http://www.youtube.com/watch?v=xyz or http://youtu.be/xyz
                                </div>
                            </div>
                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Interest Categories', true); ?> </label>
                                <div id="cust_skl" class="form_input_education rel_Skills">
<?php echo $this->Form->select('User.interest_categories', $categories, array('multiple' => true, 'data-placeholder' => __d('user', 'form-control Select Interest Categories', true), 'class' => "chosen-select")); ?>
                                    <label id="skillss1"></label>
                                </div>
                            </div>
                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'CV Document/Certificates', true); ?> <span class="star_red"></span><span class="subcat_help_text"></span></label>
                               <div class="form_input_education">
                                    <div id="mulitplefileuploader"><?php echo __d('user', 'Choose File', true); ?> </div>
                                    <!-- <div class="supported-types">Supported File Types: gif, jpg, jpeg, png (Max. 4 MB)</div>-->
                                    <div class="supported-types"><p>- <?php echo __d('user', 'Supported File Types', true); ?>: pdf, doc and docx, gif, jpg, jpeg, png (Max. 4 MB).<br> - <?php echo __d('user', 'Min file size', true); ?> 150 X 150 <?php echo __d('user', 'pixels', true) . ' ' . __d('user', 'for image', true); ?></p></div>

                                    <input type="hidden" id="images" name="data[Certificate][document]" value="" >

                                    <div class="hmdnd no-margin-row">

                                        <label>&nbsp;</label>
                                        <?php
                                        $new_slug_arry = array();
                                        if ($showOldImages || $showOldDocs) {
                                            ?>
                                            <div class="all-uploaded-images">
                                                <div class="check_doc"> </div>
                                                <?php
                                                foreach ($showOldDocs as $showOldDoc) {
                                                    $doc = $showOldDoc['Certificate']['document'];
                                                    if (!empty($doc) && file_exists(UPLOAD_CERTIFICATE_PATH . $doc)) {
                                                        $new_slug_arry [] = $showOldDoc['Certificate']['document'];
                                                        ?>
                                                        <div id="<?php echo $showOldDoc['Certificate']['slug']; ?>" alt="" class="doc_fukll_name">
                                                            <div class="doc_files_border">
                                                                <span class="temp-image-section">
            <?php echo $this->Html->link(substr($doc, 6), array('controller' => 'candidates', 'action' => 'downloadDocCertificate', $doc), array('class' => 'dfasggs', 'rel' => 'nofollow')); ?>    
                                                                    <span class="close_icon_for" alt="<?php echo $showOldDoc['Certificate']['document']; ?>"  onclick="deleteOldImage('<?php echo $showOldDoc['Certificate']['slug']; ?>')">
                                                                        X
                                                                    </span>
                                                                </span>

                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                                <div class="check"> </div>

                                                <?php
                                                foreach ($showOldImages as $showOldImage) {
                                                    $image = $showOldImage['Certificate']['document'];
                                                    if (!empty($image) && file_exists(UPLOAD_CERTIFICATE_PATH . $image)) {
                                                        $new_slug_arry [] = $showOldImage['Certificate']['document'];
                                                        ?>
                                                        <div id="<?php echo $showOldImage['Certificate']['slug']; ?>" alt="" class="image_thumb">
                                                            <span class="temp-image-section">
                                                                <img src="<?php echo DISPLAY_CERTIFICATE_PATH . $image; ?>">
                                                            </span>
                                                            <span class="delete_image" alt="<?php echo $showOldImage['Certificate']['document']; ?>" onclick="deleteOldImage('<?php echo $showOldImage['Certificate']['slug']; ?>')">
                                                                X
                                                            </span>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
<?php } else {
    ?>
                                            <div class="all-uploaded-images">
                                                <div class="check_doc"> </div>
                                                <div class="check"> </div>
                                            </div>
                                        <?php
                                        }
                                        echo $this->Form->hidden('Certificate.images', array('id' => 'images1', 'value' => implode(',', $new_slug_arry)));
                                        ?>


                                    </div>
                                </div>
                            </div>

                            <!-- <div class="form_list_education">
                                <label class="lable-acc"><?php // echo __d('user', 'Upload Video', true); ?> <span class="star_red"></span></label>
                                <div class="form_input_education form_upload_file">
                                    <span class="choose-file-your">Choose File</span>
                                    <?php // echo $this->Form->file('User.video', array('class' => 'form-control', 'id' => 'upload_video', 'onchange' => 'VideoValidation()')); ?>
                                    </div>
                                        <?php
//                                    foreach ($showOldImages as $showOldImage) {
                                       // $video = $this->data['User']['video'];
                                        // if (!empty($video) && file_exists(UPLOAD_VIDEO_PATH . $video)) {
                                         //   ?>
                                             <span class="temp-image-section">
                                                 <?php // echo $video; ?>
                                                  <?php // echo $this->Html->link($this->Html->image('close.png', array('title' => __d('item', 'Delete', true))), array('controller' => 'candidates', 'action' => 'deleteVideo', $doc), array('class' => 'dfasggs','escape' => false, 'rel' => 'nofollow')); ?> 
                                              </span>
                                            <?php
                                       // }
//                                    }
                                    ?>
                                    <div class="abccc pstrength-minchar"><?php // echo __d('user', 'Supported File Types', true); ?>: mp4, 3gp, avi (Max. 20MB).</div>
                                
                            </div> -->
                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Cover Letter', true); ?> <span class="star_red"></span></label>
                                <div class="form_input_education">
                                    <div class="add-cover-button">
                                    <input type="button" value="<?php echo __d('user', 'Add Cover Letter', true); ?>" class="currant-upplan" name="New Photo" id="addButton"> 
                                </div>
                                    <div id="MediaGroup">

                                        <?php
                                        $coverCounter = 0;
                                        if (!empty($this->data['CoverLetter'])) {
                                            foreach ($this->data['CoverLetter'] as $letter) {
                                                ?>
                                                <div class="media_box">
                                                    <?php
                                                    if (isset($letter['CoverLetter']['id']) && $letter['CoverLetter']['id'] != '') {
                                                        echo '<span class="cover_ttt">' . $this->Form->text('CoverLetter.' . $coverCounter . '.title', array('maxlength' => '30', 'class' => "form-control required", 'placeholder' => __d('user', 'Cover Letter Title', true), 'value' => $letter['CoverLetter']['title'])) . '</span>';

                                                        echo $this->Form->hidden('CoverLetter.' . $coverCounter . '.id', array('value' => $letter['CoverLetter']['id']));

                                                        echo '<span class="cover_desss">' . $this->Form->textarea('CoverLetter.' . $coverCounter . '.description', array('class' => "form-control required", 'placeholder' => __d('user', 'Cover Letter', true), 'value' => $letter['CoverLetter']['description'])) . '</span>';
                                                        $coverCounter++;
                                                    }
                                                    if (isset($letter['CoverLetter']['id']) && $letter['CoverLetter']['id'] != '') {
                                                        ?>

                                                        <span class="close_icon3_1">
                                                        <?php echo $this->Ajax->link($this->Html->image('close.png', array('title' => __d('item', 'Delete', true))), array('controller' => 'candidates', 'action' => 'deleteCover', $letter['CoverLetter']['id']), array('escape' => false, 'update' => 'MediaGroup', 'indicator' => 'loaderID', 'class' => 'custom_link', 'confirm' => 'Are you sure ?')); ?>
                                                        </span>
                                                        <?php
                                                    } else {
                                                        
                                                    }
                                                    ?>
                                                </div><?php }
                                                ?> 

    <?php
}
?>
                                    </div>
                                </div>
                            </div>
<?php echo $this->Form->text('User.CoverLetterCount', array('type' => 'hidden', 'value' => $coverCounter)); ?>
                            <div class="form_lst sssss">
                                <span class="rltv">
                                    <div class="pro_row_left">
                                        <?php //echo $this->Form->hidden('User.old_cv');   ?>

<?php echo $this->Form->submit(__d('user', 'Update', true), array('div' => false, 'label' => false, 'class' => 'input_btn', 'id' => 'saveCreateButton')); ?>
<?php echo $this->Html->link(__d('user', 'Cancel', true), array('controller' => 'users', 'action' => 'myaccount'), array('class' => 'input_btn rigjt', 'escape' => false, 'rel' => 'nofollow')); ?>
                                    </div> 
                                </span>
                            </div>

<?php echo $this->Form->end(); ?> 
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function deleteOldImage(slug) {
        $('#' + slug).hide();
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/candidates/deleteCertificacte/" + slug,
            cache: false,
            success: function (result) {

            }
        });
    }
    //    function updateCity(stateId){
    //        $.ajax({
    //            type : 'POST',
    //            url: "<?php echo HTTP_PATH; ?>/cities/getStateCity/User/"+stateId,
    //            cache: false,
    //            success: function(result) {
    //                $("#updateCity").html(result);
    //            }
    //        });
    //    }
    function updateStateCity(postCode) {

        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/cities/getStateCityByPostCode/User/" + postCode,
            cache: false,
            success: function (result) {
                $("#updateCityState").html(result);
            }
        });

    }
</script>

<script>
    function in_array(needle, haystack) {
        for (var i = 0, j = haystack.length; i < j; i++) {
            if (needle == haystack[i])
                return true;
        }
        return false;
    }

    function getExt(filename) {
        var dot_pos = filename.lastIndexOf(".");
        if (dot_pos == -1)
            return "";
        return filename.substr(dot_pos + 1).toLowerCase();
    }


    function cvValidation() {

        var filename = document.getElementById("input_choose_cv").value;
        var filetype = ['pdf', 'doc', 'docx'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " <?php echo __d('user', 'file not allowed for CV Document.', true); ?>");
                document.getElementById("input_choose_cv").value = '';
                return false;
            } else {
                var fi = document.getElementById('input_choose_cv');
                var filesize = fi.files[0].size;//check uploaded file size
                if (filesize > 4194304) {
                    alert("<?php echo __d('user', 'Maximum 4MB file size allowed for CV Document.', true); ?>");
                    document.getElementById("input_choose_cv").value = '';
                    return false;
                }
            }
        }
    }

    function VideoValidation() {

        var filename = document.getElementById("upload_video").value; //mp4, 3gp, avi
        var filetype = ['mp4', '3gp', 'avi'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " <?php echo __d('user', 'file not allowed.', true); ?>");
                document.getElementById("upload_video").value = '';
                return false;
            } else {
                var fi = document.getElementById('upload_video');
                var filesize = fi.files[0].size;//check uploaded file size in bytes
                var over_max_size = 20 * 1048576;
                if (filesize > over_max_size) {
                    alert('Maximum 20MB '+" <?php echo __d('user', 'file size allowed for file.', true); ?>");
                    alert("<?php // echo __d('user', 'Maximum 40MB file size allowed for CV Document.', true); ?>");
                    document.getElementById("upload_video").value = '';
                    return false;
                }
            }
        }
    }

</script>



<script>
    //check if browser supports file api and filereader features
    if (window.File && window.FileReader && window.FileList && window.Blob) {

        //this is not completely neccesary, just a nice function I found to make the file size format friendlier
        //https://stackoverflow.com/questions/10420352/converting-file-size-in-bytes-to-human-readable
        function humanFileSize(bytes, si) {
            var thresh = si ? 1000 : 1024;
            if (bytes < thresh)
                return bytes + ' B';
            var units = si ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'] : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
            var u = -1;
            do {
                bytes /= thresh;
                ++u;
            } while (bytes >= thresh);
            return bytes.toFixed(1) + ' ' + units[u];
        }


        //this function is called when the input loads an image
        function renderImage(file) {
            var reader = new FileReader();
            reader.onload = function (event) {
                the_url = event.target.result
                //of course using a template library like handlebars.js is a better solution than just inserting a string
                $('#preview').html("<img src='" + the_url + "' />")
                $('#name').html(file.name)
                $('#size').html(humanFileSize(file.size, "MB"))
                $('#type').html(file.type)
            }

            //when the file is read it triggers the onload event above.
            reader.readAsDataURL(file);
        }


        //this function is called when the input loads a video
        function renderVideo(file) {
            var reader = new FileReader();
            reader.onload = function (event) {
                the_url = event.target.result
                //of course using a template library like handlebars.js is a better solution than just inserting a string
                $('#data-vid').html("<video width='400' controls><source id='vid-source' src='" + the_url + "' type='video/mp4'></video>")
                $('#name-vid').html(file.name)
                $('#size-vid').html(humanFileSize(file.size, "MB"))
                $('#type-vid').html(file.type)
                //alert(humanFileSize(file.size, "MB"));

            }

            //when the file is read it triggers the onload event above.
            reader.readAsDataURL(file);
        }



        //watch for change on the 
        $("#the-photo-file-field").change(function () {
            console.log("photo file has been chosen")
            //grab the first image in the fileList
            //in this example we are only loading one file.
            console.log(this.files[0].size)
            renderImage(this.files[0])

        });

        $("#input_choose_cv").change(function () {
            console.log("video file has been chosen")
            //grab the first image in the fileList
            //in this example we are only loading one file.
            //console.log(this.files[0].size)
            renderVideo(this.files[0])

        });

    } else {

        //alert('The File APIs are not fully supported in this browser.');

    }
</script>
