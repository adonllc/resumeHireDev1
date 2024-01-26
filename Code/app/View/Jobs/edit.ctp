<?php echo $this->Html->script('jquery/ui/jquery.ui.core.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.widget.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.position.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.datepicker.js'); ?>
<?php echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css'); ?>
<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>
<?php echo $this->Html->css('front/sample.css'); ?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&key=<?php echo AUTO_SUGGESTION;?>"></script> 
<script>
    var autocomplete;
   function initialize() {
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('job_city')));
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            fillInAddress();
        });
    }
    function fillInAddress() {
        var place = autocomplete.getPlace();
        $('#JobLat').val(place.geometry.location.lat());
        $('#JobLong').val(place.geometry.location.lng());
    }
    window.onload = initialize;
</script>

<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<script>
    $(function () {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
</script>

<script>

    $(function () {
        $("#JobLastdate").datepicker({
            // defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            minDate: 'mm-dd-yyyy',
            //maxDate:'mm-dd-yyyy',
            changeYear: true,
            minDate:0
        });
    });
</script>
<script>
    $(document).ready(function () {
        $.validator.addMethod("contact", function (value, element) {
            return  this.optional(element) || (/^[0-9-]+$/.test(value));
        }, "<?php echo __d('user', 'Contact Number is not valid', true);?>.");
        $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_]+$/.test(value);
        }, "*<?php echo __d('user', 'Note: Special characters, number and spaces are not allowed', true);?>.");
        $.validator.addMethod("my_url", function (value, element) {
            var regexp = /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/
            return this.optional(element) || regexp.test(value);
        }, "<?php echo __d('user', 'Please enter a valid URL', true);?>");
        $.validator.addMethod("nameofuser", function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
        }, "*<?php echo __d('user', 'Note: Special characters and number are not allowed.', true);?>");

        $("#editJob").validate();

        CKEDITOR.replace('data[Job][description]', {
            toolbar:
                    [
                        {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline']},
                        {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-']},
                        {name: 'links', items: ['Link', 'Unlink']},//,'Image'
                        {name: 'tools', items: ['']}
                    ],
            language: 'en',
            height: 150,
            width: 563
        });

        CKEDITOR.replace('data[Job][brief_abtcomp]', {
            toolbar:
                    [
                        {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline']},
                        {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-']},
                        {name: 'links', items: ['Link', 'Unlink']},
                        {name: 'tools', items: ['']}
                    ],
            language: '',
            height: 150,
            width: 563
                    //uiColor: '#884EA1'
        });



    });
</script>
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

    function updateStateCity(postCode) {
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/cities/getStateCityByPostCode/Job/" + postCode,
            cache: false,
            success: function (result) {
                $("#updateCityState").html(result);
            }
        });
    }

    function getMaxExpList(id) {
        var opt = ''
        id = id * 1;
        for (i = id; i <= 30; i++)
        {
            opt += '<option value="' + i + '">' + i + '</option>';
        }
        $('#JobMaxExp').html(opt);
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


//validation on choosen buttons
    $(document).ready(function () {

        //location
        $('#JobLocation_chosen_span').hide();
        $('#JobLocation_chosen').click(function () {
            //alert($('#JobCustomerId').val());
            if ($('#JobLocation').val() == '' || $('#JobLocation').val() == '0') {
                $('#JobLocation_chosen').addClass('error');
                if ($('#JobLocation_chosen_span').length > 0) {
                    //code here
                } else {

                    $('#JobLocation_chosen').append('<span id="JobLocation_chosen_span" class="error customer_error" for="JobLocation_chosen" generated="true" style="display: block;"><?php echo __d('user', 'This field is required.', true);?></span>');

                }
            } else {
                $('#JobLocation_chosen').removeClass('error');
                $("#JobLocation_chosen_span").remove();
            }
        });
        //designation
        $('#JobDesignation_chosen').click(function () {
            //alert($('#JobCustomerId').val());
            if ($('#JobDesignation').val() == '' || $('#JobDesignation').val() == '0') {
                $('#JobDesignation_chosen').addClass('error');
                if ($('#JobDesignation_chosen_span').length > 0) {
                    //code here
                } else {

                    $('#JobDesignation_chosen').append('<span id="JobDesignation_chosen_span" class="error customer_error" for="JobDesignation_chosen" generated="true" style="display: block;"><?php echo __d('user', 'This field is required.', true);?></span>');

                }
            } else {
                $('#JobDesignation_chosen').removeClass('error');
                $("#JobDesignation_chosen_span").remove();
            }
        });

        //skill
        $('#JobSkill_chosen').click(function () {

            //alert($('#JobCustomerId').val());
            if ($('#JobSkill').val() == '' || $('#JobSkill').val() == '0' || $('.default').val() == 'Choose skills') {

                $('#JobSkill_chosen').addClass('error');
                if ($('#JobSkill_chosen_span').length > 0) {
                    //code here
                } else {
                    $('#JobSkill_chosen').append('<span id="JobSkill_chosen_span" class="error customer_error" for="JobSkill_chosen" generated="true" style="display: block;"><?php echo __d('user', 'This field is required.', true);?></span>');
                }
            } else {

                $('#JobSkill_chosen').removeClass('error');
                $("#JobSkill_chosen_span").remove();
            }
        });


        $('#saveCreateButton').click(function () {

            //alert($('#JobCustomerId').val());
            //location
            if ($('#JobLocation').val() == '' || $('#JobLocation').val() == '0') {
                if ($('#JobLocation_chosen_span').length > 0) {
                    //code here
                } else {
                    $('#cust_idd').append('<span id="JobLocation_chosen_span" class="error customer_error" for="JobLocation_chosen" generated="true" style="display: block;"><?php echo __d('user', 'This field is required.', true);?></span>');

                }
                $('#JobLocation_chosen').addClass('error');

            } else {

                $('#JobLocation_chosen').removeClass('error');
                $("#JobLocation_chosen_span").remove();
            }
            //designation
            if ($('#JobDesignation').val() == '' || $('#JobDesignation').val() == '0') {
                if ($('#JobDesignation_chosen_span').length > 0) {
                    //code here
                } else {
                    $('#cust_des').append('<span id="JobDesignation_chosen_span" class="error customer_error" for="JobLocation_chosen" generated="true" style="display: block;"><?php echo __d('user', 'This field is required.', true);?></span>');

                }
                $('#JobDesignation_chosen').addClass('error');

            } else {

                $('#JobDesignation_chosen').removeClass('error');
                $("#JobDesignation_chosen_span").remove();
            }
            //skill

            if ($('#JobSkill').val() == '' || $('#JobSkill').val() == '0' || $('.default').val() == '<?php echo __d('user', 'Choose skills', true);?>') {

                if ($('#JobSkill_chosen_span').length > 0) {
                    //code here
                } else {
                    $('#cust_skl').append('<span id="JobSkill_chosen_span" class="error customer_error" for="JobSkill_chosen" generated="true" style="display: block;"><?php echo __d('user', 'This field is required.', true);?></span>');
                }
                $('#JobSkill_chosen').addClass('error');

            } else {
                $('#JobSkill_chosen').removeClass('error');
                $("#JobSkill_chosen_span").remove();
            }
        });
    });




</script>
<div class="my_accnt">
    <?php echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu'); ?>
                <div class="col-sm-9 col-lg-9 col-xs-12">

                    <?php //pr($jobEdit); ?>


                    <?php
                    $userId = $this->Session->read('user_id');
                    $userDetail = classregistry::init('User')->find('first', array('conditions' => array('User.id' => $userId)));
                    ?>
                    <div class="info_dv">
                        <div class="heads"><?php echo __d('user', 'Edit Job', true);?></div>
                        <div class="information_cntn">
                            <?php echo $this->element('session_msg'); ?>

                            <?php echo $this->Form->create("Null", array('enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'editJob', 'class' => "form_trl_box_show")); ?>
                            <div class="form_lst">
                                <label><?php echo __d('user', 'Job Title', true);?> <span class="star_red">*</span></label>
                                <span class="rltv education_profile">
                                    <?php echo $this->Form->text('Job.title', array('class' => "form-control required keyword-box", 'placeholder' => __d('user', 'Job Title', true), 'autocomplete' => 'off', 'data-suggesstion' => 'jobkeyword-box', 'data-search' => 'Job')) ?>
                                    <div id="jobkeyword-box" class="account-suggestion common-serach-box" style="display: none"></div>
                                </span>
                            </div>
                            <div class="form_lst">
                                <label><?php echo __d('user', 'Category', true);?> <span class="star_red">*</span></label>
                                <span class="rltv">
                                    <?php echo $this->Form->input('Job.category_id', array('type' => 'select', 'options' => $categories, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => __d('user', 'Select Job Category', true), 'onChange' => 'updateSubCat(this.value)')) ?>
                                </span>     
                            </div>
                            <div class="form_lst div_subcategory" id="div_subcat">
                                <label class="relative_label"><?php echo __d('user', 'Sub Category', true);?> <span class="star_red"></span><span class="subcat_help_text"></span></label>
                                <span class="rltv subbb" id="subcat">
                                    <?php echo $this->Form->input('Job.subcategory_id', array('type' => 'select', 'options' => $subcategories, 'label' => false, 'div' => false, 'multiple' => true, 'class' => "form-control")) //'empty' => __d('user', 'Select Job Sub Category', true)?><br>
                                    <?php echo __d('user', 'Note: Please hold Ctrl / Command to select multiple options.', true);?>
                                </span>
                            </div>
                            <!--                            <div class="form_lst">
                                                            <label>Highlights of Job <span class="star_red"></span></label>
                                                            <span class="rltv"><?php //echo $this->Form->text('Job.selling_point1', array('class' => "", 'placeholder' => 'Highlights of Job'))    ?></span>
                                                        </div>-->

                            <?php if (isset($this->data['Job']['type']) && $this->data['Job']['type'] != 'bronze') { ?>
                                <!--                                <div class="form_lst">
                                                                    <label>Highlights of Job <span class="star_red"></span></label>
                                                                    <span class="rltv"><?php //echo $this->Form->text('Job.selling_point2', array('class' => "", 'placeholder' => 'Highlights of Job'))    ?></span>
                                                                </div>
                                                                <div class="form_lst">
                                                                    <label>Highlights of Job <span class="star_red"></span></label>
                                                                    <span class="rltv"><?php //echo $this->Form->text('Job.selling_point3', array('class' => "", 'placeholder' => 'Highlights of Job'))    ?></span>
                                                                </div>-->
                            <?php } ?>

                            <div class="form_lst">
                                <label><?php echo __d('user', 'Job Description', true);?> <span class="star_red">*</span></label>
                                <span class="rltv"><?php echo $this->Form->textarea('Job.description', array('class' => "required", 'placeholder' => __d('user', 'Job Description', true))) ?></span>
                            </div>

                            <?php
                            if (isset($userDetail['User']['company_name']) && !empty($userDetail['User']['company_name'])) {
                                $companyName = $userDetail['User']['company_name'];
                            } else {
                                $companyName = '';
                            }
                            ?>
                            <div class="form_lst">
                                <label><?php echo __d('user', 'Company name', true);?> <span class="star_red">*</span></label>
                                <span class="rltv"><?php echo $this->Form->text('Job.company_name', array('maxlength' => 100, 'value' => $companyName, 'class' => "required", 'placeholder' => __d('user', 'Company name', true))) ?></span>
                            </div>
                            <!-- <div class="form_lst">
                                 <label>Wage Package <span class="star_red">*</span></label>
                                 <span class="rltv">
                            <?php
                            //  global $priceArray;
                            // echo $this->Form->input('Job.price', array('type' => 'select', 'options' => $priceArray, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Wage Package'))
                            ?>
                                 </span>
                             </div>
                             <div class="form_lst">
                                 <label>Wage Package Status <span class="star_red">*</span></label>
                                 <span class="rltv" style="padding-top:8px;">
                                     <div class="radio">
                            <?php
                            //  $options2 = array('1' => '<label for="JobPriceStatus1" class="radio1">Show</label>', '0' => '<label for="JobPriceStatus0"  class="radio1">Hide</label>');
                            //   $attributes2 = array('legend' => false, 'label' => false, 'separator' => '</div><div class="radio">', 'class' => 'radio2');
                            //   echo $this->Form->radio('Job.price_status', $options2, $attributes2);
                            ?>
                                     </div>
                                 </span>
                             </div> -->
                            <?php
                            if (isset($userDetail['User']['company_about']) && !empty($userDetail['User']['company_about'])) {
                                $companyAbout = $userDetail['User']['company_about'];
                            }
                            ?>
                            <div class="form_lst">
                                <label><?php echo __d('user', 'Company Profile', true);?> <span class="star_red">*</span></label>
                                <span class="rltv"><?php echo $this->Form->textarea('Job.brief_abtcomp', array('value' => $companyAbout, 'class' => "required", 'placeholder' => __d('user', 'Job Description', true))) ?></span>
                            </div>

                            <div class="form_lst">
                                <label><?php echo __d('user', 'Work Type', true);?> <span class="star_red">*</span></label>
                                <span class="rltv">
                                    <?php
                                    global $worktype;
                                    echo $this->Form->input('Job.work_type', array('type' => 'select', 'options' => $worktype, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => __d('user', 'Select Work Type', true)))
                                    ?>
                                </span>     
                            </div>  

                            <div class="form_lst">
                                <label><?php echo __d('user', 'Contact Name', true);?> <span class="star_red">*</span></label>
                                <span class="rltv"><?php echo $this->Form->text('Job.contact_name', array('class' => "required ", 'placeholder' => __d('user', 'Contact Name', true))) ?></span>
                            </div>
                            <div class="form_lst">
                                <label><?php echo __d('user', 'Contact Number', true);?> <span class="star_red">*</span></label>
                                <span class="rltv"><?php echo $this->Form->text('Job.contact_number', array('maxlength' => '16','minlength' => '8', 'class' => "required", 'placeholder' => __d('user', 'Contact Number', true))) ?></span>
                            </div>

                            <div class="form_lst">
                                <label><?php echo __d('user', 'Company Website', true);?> <span class="star_red"></span></label>
                                <span class="rltv"><?php echo $this->Form->text('Job.url', array('class' => "my_url", 'placeholder' => __d('user', 'Company Website', true))) ?>
                                    Eg.: http://www.google.com or www.google.com
                                </span>
                            </div>
                            <div class="form_lst">
                                <label><?php echo __d('user', 'Skills', true);?> <div class="star_red">*</div></label>
                                <div class="rltv rel_Skills">
                                    <?php echo $this->Form->select('Job.skill', $skillList, array('multiple' => true, 'data-placeholder' => __d('user', 'Choose skills', true), 'class' => "chosen-select")); ?>
                                </div>
                            </div>

                            <div class="form_lst">
                                <label><?php echo __d('user', 'Designation', true);?> <div class="star_red">*</div></label>
                                <div class="rltv Location designation_div">
                                    <?php echo $this->Form->select('Job.designation', $designationlList, array('data-placeholder' => __d('user', 'Choose a designation', true), 'class' => "chosen-select")); ?>
                                </div>
                            </div>

                            <div class="form_lst">
                                <label><?php echo __d('user', 'Location', true);?> <div class="star_red">*</div></label>
                                 <div id="cust_idd" class="rltv rel_Location">
                                    <?php //echo $this->Form->select('Job.location', $locationlList, array('data-placeholder' => 'Choose location', 'class' => "chosen-select")); ?>
                                    <?php echo $this->Form->text('Job.job_city', array('class' => "required", 'placeholder' => __d('user', 'Enter Location', true), 'id'=>'job_city')) ?>
                                </div>
                            </div>

                            <div class="form_lst">
                                <label><?php echo __d('user', 'Experience (In Years)', true);?> <div class="star_red">*</div></label>
                                <div class="rltv">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-xs-12">
                                            <?php
                                            global $experienceArray;
                                            echo $this->Form->input('Job.exp', array('type' => 'select', 'options' => $experienceArray, 'label' => false, 'class' => "required"));
                                            ?>        
                                        </div>
                          
                                    </div>
                                </div>
                            </div>

                            <div class="form_lst">
                                <label><?php echo __d('user', 'Annual Salary', true);?> <div class="star_red">*</div></label>
                                <div class="rltv">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-xs-12">
                                            <?php
                                            global $sallery;
                                           
                                            echo $this->Form->input('Job.salary', array('type' => 'select', 'options' => $sallery, 'label' => false, 'class' => "required", 'empty' => __d('user', 'Select Salary', true)));
                                            ?>        
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <?php if (isset($this->data['Job']['type']) && $this->data['Job']['type'] != 'bronze') { ?>


                                <div class="form_lst">
                                    <label><?php echo __d('user', 'Company Logo', true);?> <span class="star_red"></span></label>
                                    <span class="rltv">
                                        <?php echo $this->Form->file('Job.logo', array('class' => 'default', 'onchange' => 'imageValidation()')) ?>
                                        <br>
                                        <?php echo __d('user', 'Supported File Types', true);?>: gif, jpg, jpeg, png (Max. 2MB).
                                        <br><br>
                                        <?php
                                        if ($this->data['Job']['old_logo']) {
                                            echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_JOB_LOGO_PATH . $this->data['Job']['old_logo'] . "&w=200&zc=1&q=100", array('escape' => false, 'rel' => 'nofollow'));
                                        }
                                        ?>

                                    </span>
                                </div>

<!--                                <div class="form_lst">

                                    <div class="or_det_devide"><span><?php echo __d('user', 'Or', true);?></span></div>

                                </div>

                                <div class="form_lst">
                                    <label><?php echo __d('user', 'Use Profile Logo', true);?></label>
                                    <span class="rltv rltv_check_boco">
                                        <?php if (trim($logo_status)) { ?>
                                            <span><?php echo $this->Form->input('Job.job_logo_check', array('type' => 'checkbox', 'label' => '', 'value' => '1')); ?> </span>
                                        <?php } ?>
                                    </span>

                                </div>-->
                            <?php } ?>

<div class="form_lst">
                                <label><?php echo __d('user', 'Last Date', true); ?> <div class="star_red">*</div></label>
                                <div id="cust_idd" class="rltv rel_Location">
                                    <?php echo $this->Form->text('Job.expire_time', array('class' => "required", 'autocomplete' => 'off','placeholder' => __d('user', 'Last Date', true), 'id' => 'JobLastdate')) ?>
                                </div>
                            </div>

                            <div class="form_lst sssss">
                                <label>&nbsp;</label>
                                <span class="rltv">
                                    <div class="pro_row_left">
                                        <?php echo $this->Form->hidden('Job.lat'); ?>   
                        <?php echo $this->Form->hidden('Job.long'); ?>
                                        <?php
                                        echo $this->Form->hidden('Job.id');
                                        echo $this->Form->hidden('Job.old_logo');
                                        echo $this->Form->hidden('Job.old_title');
                                        echo $this->Form->hidden('Job.type');
                                        ?>
                                        <?php echo $this->Form->submit(__d('user', 'Update Job', true), array('div' => false, 'label' => false, 'class' => 'input_btn', 'id' => 'saveCreateButton')); ?>
                                        <?php echo $this->Html->link(__d('user', 'Cancel', true), array('controller' => 'jobs', 'action' => 'accdetail', $slug), array('class' => 'input_btn rigjt', 'escape' => false, 'rel' => 'nofollow')); ?>
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
    function updateCity(stateId) {
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/cities/getStateCity/Job/" + stateId,
            cache: false,
            success: function (result) {
                $("#updateCity").html(result);
            }
        });
    }

    function updateSubCat(catId) {
        $('#div_subcat').css('display', 'none');
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/categories/getSubCategory/" + catId,
            cache: false,
            success: function (result) {
                $('#div_subcat').css('display', 'block');
                $("#subcat").html(result);
            },
            complete: function ()
            {
                console.log($('#JobSubcategoryId option').length);

                if ($('#JobSubcategoryId option').length == 1)
                {
                    $('#div_subcat').css('display', 'none');
                }
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

    function imageValidation() {

        var filename = document.getElementById("JobLogo").value;
        var filetype = ['jpg', 'jpeg', 'png', 'gif'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " file not allowed for logo.");
                document.getElementById("JobLogo").value = '';
                return false;
            } else {
                var fi = document.getElementById('JobLogo');
                var filesize = fi.files[0].size;//check uploaded file size
                if (filesize > 2097152) {
                    alert('Maximum 2MB file size allowed for logo.');
                    document.getElementById("JobLogo").value = '';
                    return false;
                }
            }
        }
    }

    function cvValidation() {

        var filename = document.getElementById("UserCv").value;
        var filetype = ['pdf', 'doc', 'docx'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " file not allowed for CV Document.");
                document.getElementById("UserCv").value = '';
                return false;
            } else {
                var fi = document.getElementById('UserCv');
                var filesize = fi.files[0].size;//check uploaded file size
                if (filesize > 2097152) {
                    alert('Maximum 2MB file size allowed for CV Document.');
                    document.getElementById("UserCv").value = '';
                    return false;
                }
            }
        }
    }








</script>