<?php
$max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'max_size'));
echo $this->Html->script('jquery/ui/jquery.ui.core.js');
?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.widget.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.position.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.datepicker.js'); ?>
<?php echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css'); ?>
<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>
<?php echo $this->Html->css('front/sample.css'); ?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&key=<?php echo AUTO_SUGGESTION; ?>"></script> 
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
<?php
if (isset($this->data['Job']['max_exp']) && !empty($this->data['Job']['max_exp'])) {
    $exp_max = $this->data['Job']['max_exp'];
} else {
    $exp_max = '';
}
if (isset($this->data['Job']['max_salary']) && !empty($this->data['Job']['max_salary'])) {
    $sal_max = $this->data['Job']['max_salary'];
} else {
    $sal_max = '';
}
?>
<script>
    $().ready(function () {
        $.validator.addMethod("contact", function (value, element) {
            return  this.optional(element) || (/^[0-9-]+$/.test(value));
        }, "<?php echo __d('user', 'Contact Number is not valid', true); ?>.");
        $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_]+$/.test(value);
        }, "*<?php echo __d('user', 'Note: Special characters, number and spaces are not allowed', true); ?>.");
        //        $.validator.addMethod('my_url', function( value, element ) {
        //            // contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/
        //            return this.optional( element ) || /^(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test( value );
        //        }, 'Please enter a valid url without the.');
        $.validator.addMethod("my_url", function (value, element) {
            var regexp = /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/
            return this.optional(element) || regexp.test(value);
        }, "<?php echo __d('user', 'Please enter a valid URL', true); ?>");
        $.validator.addMethod("nameofuser", function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
        }, "*<?php echo __d('user', 'Note: Special characters and number are not allowed.', true); ?>");
        $("#createJob").validate();


        CKEDITOR.replace('data[Job][description]', {
            toolbar:
                    [
                        {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline']},
                        {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-']},
                        {name: 'links', items: ['Link', 'Unlink']},
                        {name: 'tools', items: ['']}
                    ],
            language: '',
            height: 150,
                    //uiColor: '#884EA1'
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
                    //uiColor: '#884EA1'
        });



        var opt = '';
        var minExp = $('#JobMinExp').val();
        var maxExp = "<?php echo $exp_max ?>";
        minExp = minExp * 1;
        $("#JobMinExp option").each(function ()
        {
            var ff = $(this).val() * 1;
            if (ff >= minExp) {
                opt += '<option value="' + ff + '">' + ff + '</option>';
            }
        });
        $('#JobMaxExp').html(opt);
        if (maxExp) {
            $('#JobMaxExp').val(maxExp);
        }

        var optSal = '';
        var minSal = $('#JobMinSalary').val();
        var maxSal = "<?php echo $sal_max ?>";
        minSal = minSal * 1;
        $("#JobMinSalary option").each(function ()
        {
            var ffSal = $(this).val() * 1;
            if (ffSal >= minSal) {
                optSal += '<option value="' + ffSal + '"><?php echo CURRENCY; ?>' + ffSal + 'K</option>';
            }
        });
        $('#JobMaxSalary').html(optSal);
        if (maxSal) {
            $('#JobMaxSalary').val(parseInt(maxSal));
        }

        /*var minExp = $('#JobMinExp').val();
         if(minExp!=''){
         $('#JobMaxExp').html('');
         if(minExp == ''){
         $('#JobMaxExp').html('<option value = "">Max</option>');
         }else{
         max_exp = new Array();
         for(var exp = minExp; exp <= 30; exp++){
         max_exp[exp]  = exp;
         }
         max_expArr = max_exp.filter(Boolean);
         $.each(max_expArr, function(key, value) {  
         $('#JobMaxExp')
         .append($("<option></option>")
         .attr("value",value)
         .text(value)); 
         });
         }            
         var maxExp = "<?php echo $this->data['Job']['max_exp']; ?>";
         if(maxExp){
         $('#JobMaxExp').val(maxExp);
         }
         }
         
         var minSal = $('#JobMinSalary').val();
         var maxSal = "<?php echo $this->data['Job']['max_salary']; ?>";
         if(minSal!=''){
         $('#JobMaxSalary').html('');
         if(minSal == ''){
         $('#JobMaxSalary').html('<option value = "">Max</option>');
         }else{
         max_sal = new Array();
         for(minSal; minSal <= 1900; ){
         max_sal[minSal] = "<?php echo CURRENCY; ?>" + minSal + 'K';
         if(minSal < 100){
         minSal = parseInt(minSal) + parseInt(10);
         }else if(minSal >= 100 && minSal < 1000){
         minSal = parseInt(minSal) + parseInt(25);
         }else if(minSal >= 1000 && minSal < 2000){
         minSal = parseInt(minSal) + parseInt(100);
         }
         }
         max_sal[minSal] = "<?php echo CURRENCY; ?>2000K & above";
         max_salArr = max_sal.filter(Boolean);
         $.each(max_salArr, function(key, value) {  
         var vArr = value.split("K");
         var vvArr = vArr[0].split("<?php echo CURRENCY; ?>");
         $('#JobMaxSalary')
         .append($("<option></option>")
         .attr("value",vvArr[1])
         .text(value)); 
         });
         }
         if(maxSal){
         $('#JobMaxSalary').val(maxSal);
         }
         }*/

    });
</script>

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

</script>



<script language="javascript" type="text/javascript">
    $(function () {
        $("#JobLogo").change(function () {
            $("#dvPreview").html("");
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
            if (regex.test($(this).val().toLowerCase())) {
                if ($.browser.msie && parseFloat(jQuery.browser.version) <= 9.0) {
                    $("#dvPreview").show();
                    $("#dvPreview")[0].filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = $(this).val();
                } else {
                    if (typeof (FileReader) != "undefined") {
                        $("#dvPreview").show();
                        $("#dvPreview").append("<img />");
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $("#dvPreview img").attr("src", e.target.result);
                            $("#dvPreview").show();
                        }
                        reader.readAsDataURL($(this)[0].files[0]);
                    } else {
                        alert("<?php echo __d('user', 'This browser does not support FileReader.', true); ?>");
                    }
                }
            } else {
                //alert("Please upload a valid image file.");
            }

        });
    });

    function getMaxExpList(id) {
        var opt = ''
        id = id * 1;
        $("#JobMinExp option").each(function ()
        {
            var ff = $(this).val() * 1;
            if (ff >= id) {
                opt += '<option value="' + ff + '">' + ff + '</option>';
            }
        });
        $('#JobMaxExp').html(opt);
    }

    /* function getMaxExpList_old(minExp) {
     $('#JobMaxExp').html('');
     if (minExp == '') {
     $('#JobMaxExp').html('<option value = "">Max</option>');
     } else {
     max_exp = new Array();
     for (var exp = minExp; exp <= 30; exp++) {
     max_exp[exp] = exp;
     }
     max_expArr = max_exp.filter(Boolean);
     $.each(max_expArr, function (key, value) {
     $('#JobMaxExp')
     .append($("<option></option>")
     .attr("value", value)
     .text(value));
     });
     }
     }*/

    function getMaxSalaryList(id) {
        var opt = ''
        id = id * 1;//here is var id like a value

        $("#JobMinSalary option").each(function ()
        {
            var ff = $(this).val() * 1;
            if (ff >= id) {
                opt += '<option value="' + ff + '"><?php echo CURRENCY; ?>' + ff + 'K</option>';
            }
        });
        $('#JobMaxSalary').html(opt);
    }

    function getMaxSalaryList_old(minSal) {
        $('#JobMaxSalary').html('');
        if (minSal == '') {
            $('#JobMaxSalary').html('<option value = ""><?php echo __d('user', 'Max', true); ?></option>');
        } else {
            max_sal = new Array();
            for (minSal; minSal <= 1900; ) {
                max_sal[minSal] = "<?php echo CURRENCY; ?>" + minSal + 'K';
                if (minSal < 100) {
                    minSal = parseInt(minSal) + parseInt(10);
                } else if (minSal >= 100 && minSal < 1000) {
                    minSal = parseInt(minSal) + parseInt(25);
                } else if (minSal >= 1000 && minSal < 2000) {
                    minSal = parseInt(minSal) + parseInt(100);
                }
            }
            max_sal[minSal] = "<?php echo CURRENCY; ?>2000K & above";
            max_salArr = max_sal.filter(Boolean);
            $.each(max_salArr, function (key, value) {
                var vArr = value.split("K");
                var vvArr = vArr[0].split("<?php echo CURRENCY; ?>");
                $('#JobMaxSalary')
                        .append($("<option></option>")
                                .attr("value", vvArr[1])
                                .text(value));
            });
        }
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
                    $('#JobLocation_chosen').append('<span id="JobLocation_chosen_span" class="error customer_error" for="JobLocation_chosen" generated="true" style="display: block;"><?php echo __d('user', 'This field is required.', true); ?></span>');
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

                    $('#JobDesignation_chosen').append('<span id="JobDesignation_chosen_span" class="error customer_error" for="JobDesignation_chosen" generated="true" style="display: block;"><?php echo __d('user', 'This field is required.', true); ?></span>');

                }
            } else {
                $('#JobDesignation_chosen').removeClass('error');
                $("#JobDesignation_chosen_span").remove();
            }
        });

        //skill
        $('#JobSkill_chosen').click(function () {

            //alert($('#JobCustomerId').val());
            if ($('#JobSkill').val() == '' || $('#JobSkill').val() == '0' || $('.default').val() == '<?php echo __d('user', 'Choose skills', true); ?>') {

                $('#JobSkill_chosen').addClass('error');
                if ($('#JobSkill_chosen_span').length > 0) {
                    //code here
                } else {
                    $('#JobSkill_chosen').append('<span id="JobSkill_chosen_span" class="error customer_error" for="JobSkill_chosen" generated="true" style="display: block;"><?php echo __d('user', 'This field is required.', true); ?></span>');
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
                    $('#cust_idd').append('<span id="JobLocation_chosen_span" class="error customer_error" for="JobLocation_chosen" generated="true" style="display: block;"><?php echo __d('user', 'This field is required.', true); ?></span>');

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
                    $('#cust_des').append('<span id="JobDesignation_chosen_span" class="error customer_error" for="JobLocation_chosen" generated="true" style="display: block;"><?php echo __d('user', 'This field is required.', true); ?></span>');

                }
                $('#JobDesignation_chosen').addClass('error');

            } else {

                $('#JobDesignation_chosen').removeClass('error');
                $("#JobDesignation_chosen_span").remove();
            }
            //skill

            if ($('#JobSkill').val() == '' || $('#JobSkill').val() == '0' || $('.default').val() == 'Choose skills') {

                if ($('#JobSkill_chosen_span').length > 0) {
                    //code here
                } else {
                    $('#cust_skl').append('<span id="JobSkill_chosen_span" class="error customer_error" for="JobSkill_chosen" generated="true" style="display: block;"><?php echo __d('user', 'This field is required.', true); ?></span>');
                }
                $('#JobSkill_chosen').addClass('error');

            } else {
                $('#JobSkill_chosen').removeClass('error');
                $("#JobSkill_chosen_span").remove();
            }
        });
    });

</script>
<?php //pr($_SESSION['type'])
?>
<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu'); ?>
                <div class="col-sm-9 col-lg-9 col-xs-12">
                    <?php
                    $userId = $this->Session->read('user_id');
                    $userDetail = classregistry::init('User')->find('first', array('conditions' => array('User.id' => $userId)));
                    ?>
                    <div class="my-profile-boxes info_dv_esdit_pre">
                        <div class="my-profile-boxes-top my-skills-boxes"><h2><i><?php echo $this->Html->image('front/home/creat-job-icon2.png', array('alt' => '')); ?></i><span>
                            <?php
                            if (isset($isCopy) && $isCopy == 'copy') {
                                echo __d('user', 'Copy', true);
                            } else {
                                echo __d('user', 'Create', true);
                                unset($_SESSION['data']);
                            }
                            ?>
                            <?php echo __d('user', 'Job', true); ?></span></h2></div>
                        <div class="information_cntn">
                            <?php echo $this->element('session_msg'); ?>

                            <?php echo $this->Form->create("Null", array('enctype' => 'multipart/form-data', "method" => "Post", 'class' => "form_trl_box_show2", 'id' => 'createJob',)); ?>
<?php echo $this->Form->hidden('Job.lat'); ?>   
                        <?php echo $this->Form->hidden('Job.long'); ?>

                            <?php echo $this->Form->hidden('Job.sessiontype', array('value' => $_SESSION['type'])) ?>

                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Job Title', true); ?> <span class="star_red">*</span></label>
                                <div class="form_input_education">
                                    <?php echo $this->Form->text('Job.title', array('class' => "form-control required keyword-box", 'placeholder' => __d('user', 'Job Title', true), 'autocomplete' => 'off', 'data-suggesstion' => 'jobkeyword-box', 'data-search' => 'Job')) ?>
                                    <div id="jobkeyword-box" class="account-suggestion common-serach-box" style="display: none"></div>
                                </div>
                            </div>
                            <div class="form_list_education">
                               <label class="lable-acc"><?php echo __d('user', 'Category', true); ?> <span class="star_red">*</span></label>
                                <div class="form_input_education qualification-select">
                                    <span>
                                    <?php //echo $this->Form->input('Job.category_id', array('type' => 'select', 'options' => $categories, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select Job Category', 'onChange' => 'updateSubCat(this.value)'))  ?>
                                    <?php echo $this->Form->input('Job.category_id', array('type' => 'select', 'options' => $categories, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => __d('user', 'Select Job Category', true), 'onChange' => 'updateSubCat(this.value)')) ?>
                                    </span>
                                </div> 
                            </div>
                            <div class="form_list_education div_subcategory" id="div_subcat" style="display: none;">
                                <label class="lable-acc"><?php echo __d('user', 'Sub Category', true); ?> <span class="star_red"></span><!--<span class="subcat_help_text"></span>--></label>
                                <div class="form_input_education qualification-select subbb" id="subcat">
                                    <span>
                                    <?php echo $this->Form->input('Job.subcategory_id', array('type' => 'select', 'options' => $subcategories, 'label' => false, 'div' => false, 'class' => "form-control", 'multiple' => true)) //'empty' => __d('user', 'Select Job Sub Category', true) ?>
                                    <?php echo __d('user', 'Note: Please hold Ctrl / Command to select multiple options.', true); ?>
                                    </span>
                                </div>     

                            </div>
                           
                            <div class="form_list_education">
                                <label class="lable-acc-add"><span class="lsdd-title"><?php echo __d('user', 'Job Description', true); ?> <span class="star_red">*</span></span></label>
                                <div class="form_input_education"><?php echo $this->Form->textarea('Job.description', array('class' => "form-control required", 'placeholder' => __d('user', 'Job Description', true))) ?></div>
                            </div>

                            <?php
                            if (isset($userDetail['User']['company_name']) && !empty($userDetail['User']['company_name'])) {
                                $companyName = $userDetail['User']['company_name'];
                            } else {
                                $companyName = '';
                            }
                            ?>
                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Company name', true); ?> <span class="star_red">*</span></label>
                                <div class="form_input_education"><?php echo $this->Form->text('Job.company_name', array('maxlength' => 100, 'value' => $companyName, 'class' => "form-control required", 'placeholder' => __d('user', 'Company name', true))) ?></div>
                            </div>
                            
                            <?php
                            if (isset($userDetail['User']['company_about']) && !empty($userDetail['User']['company_about'])) {
                                $companyAbout = $userDetail['User']['company_about'];
                            }
                            ?>
                            <div class="form_list_education">
                                <label class="lable-acc-add"><span class="lsdd-title"><?php echo __d('user', 'Company Profile', true); ?> <span class="star_red">*</span></span></label>
                               <div class="form_input_education">
                                    <?php echo $this->Form->textarea('Job.brief_abtcomp', array('value' => $companyAbout, 'class' => "required", 'placeholder' => __d('user', 'Job Description', true))) ?>
                               </div>
                            </div>

                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Work Type', true); ?> <span class="star_red">*</span></label>
                                <div class="form_input_education qualification-select" id="subcat">
                                    <span>
                                    <?php
                                    global $worktype;
                                    echo $this->Form->input('Job.work_type', array('type' => 'select', 'options' => $worktype, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => __d('user', 'Select Work Type', true)))
                                    ?>
                                    </span>
                                </div>     
                            </div>


                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Contact Name', true); ?> <span class="star_red">*</span></label>
                                <div class="form_input_education">
                                    <?php echo $this->Form->text('Job.contact_name', array('class' => "form-control required ", 'placeholder' => __d('user', 'Contact Name', true))) ?>
                                </div>
                            </div>

                            <?php
                            if (isset($userDetail['User']['company_contact']) && !empty($userDetail['User']['company_contact'])) {
                                $companyContact = $userDetail['User']['company_contact'];
                            } else {
                                $companyContact = '';
                            }
                            ?>

                            <div class="form_list_education">
                               <label class="lable-acc"><?php echo __d('user', 'Contact Number', true); ?> <span class="star_red">*</span></label>
                                <div class="form_input_education">
                                    <?php echo $this->Form->text('Job.contact_number', array('maxlength' => '16', 'minlength' => '8', 'value' => $companyContact, 'class' => "form-control contact required", 'placeholder' => __d('user', 'Contact Number', true))) ?>
                                </div>
                            </div>

                            <?php
                            if (isset($userDetail['User']['url']) && !empty($userDetail['User']['url'])) {
                                $companyWebsite = $userDetail['User']['url'];
                            } else {
                                $companyWebsite = '';
                            }
                            ?>
                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Company Website', true); ?> <span class="star_red"></span></label>
                                <div class="form_input_education">
                                    <?php echo $this->Form->text('Job.url', array('class' => "form-control url", 'value' => $companyWebsite, 'placeholder' => __d('user', 'Company Website', true))) ?>
                                    Eg.: https://www.google.com or http://google.com
                                </div>
                            </div>
                            <?php /* ?><div class="form_lst">
                              <label>YouTube Video URL <span class="star_red"></span></label>
                              <span class="rltv"><?php echo $this->Form->text('Job.youtube_link', array('class' => "my_url", 'placeholder' => 'YouTube Video Url')) ?>
                              Eg.: https://www.youtube.com/watch?v=7TF00hJI78Y
                              </span>
                              </div><?php */ ?>

                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Skills', true); ?> <div class="star_red">*</div></label>
                                <div id="cust_skl" class="form_input_education rel_Skills">
                                    <?php echo $this->Form->select('Job.skill', $skillList, array('multiple' => true, 'data-placeholder' => __d('user', 'Choose skills', true), 'class' => "chosen-select")); ?>
                                </div>
                            </div>

                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Designation', true); ?> <div class="star_red">*</div></label>
                                <div id="cust_des" class="form_input_education qualification-select Location">
                                    <span>
                                        <?php echo $this->Form->select('Job.designation', $designationlList, array('empty' => __d('user', 'Choose a designation', true), 'class' => "form-control")); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="form_list_education">
                               <label class="lable-acc"><?php echo __d('user', 'Location', true); ?> <div class="star_red">*</div></label>
                                <div id="cust_idd" class="form_input_education rel_Location">
                                    <?php //echo $this->Form->select('Job.location', $locationlList, array('data-placeholder' => 'Choose location', 'class' => "chosen-select")); ?>
                                    <?php echo $this->Form->text('Job.job_city', array('class' => "form-control required", 'placeholder' => __d('user', 'Enter Location', true), 'id' => 'job_city')) ?>
                                </div>
                            </div>

                            <?php /* <div class="form_lst">
                              <label>Experience <span class="star_red">*</span></label>
                              <div class="rltv">
                              <div class="row">
                              <div class="col-lg-6 col-sm-6">
                              <?php
                              $arrYear= array();
                              for($year = 1; $year <= 30; $year++){
                              $arrYear[$year]  = $year;
                              }
                              echo $this->Form->input('Job.exp_year', array('type'=>'select','options'=>$arrYear, 'label'=>false, 'class' => "required", 'empty'=>'Select Year'));
                              ?>

                              </div>
                              <div class="col-lg-6 col-sm-6">
                              <?php
                              $arrMonth= array();
                              for($month = 1; $month <= 11; $month++){
                              $arrMonth[$month]  = $month;
                              }
                              echo $this->Form->input('Job.exp_month', array('type'=>'select','options'=>$arrMonth, 'label'=>false, 'empty'=>'Select Month'));
                              ?>

                              </div>
                              </div>
                              </div>
                              </div> */ ?>

                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Experience (In Years)', true); ?> <div class="star_red">*</div></label>
                                <div class="form_input_education qualification-select">
                                    <span>
                                            <?php
                                            global $experienceArray;
                                            echo $this->Form->input('Job.exp', array('type' => 'select', 'options' => $experienceArray, 'label' => false, 'class' => "form-control required"));
                                            ?>        
                                        
                                        <!--                                        <div class="col-lg-1 col-sm-1 col-xs-12 to_cretae_hs">
                                                                                    <b>to</b>        
                                                                                </div>
                                                                                <div class="col-lg-6 col-sm-6 col-xs-12">
                                        <?php
                                        $max_exp = array();
                                        echo $this->Form->input('Job.max_exp', array('type' => 'select', 'options' => $max_exp, 'label' => false, 'class' => "required", 'empty' => __d('user', 'Max', true)));
                                        ?>
                                                                                </div>-->
                                    </span>
                                </div>
                            </div>
                            <!--                            <div class="form_lst">
                                                            <label>Experience (In Years) <div class="star_red">*</div></label>
                                                            <div class="rltv">
                                                                <div class="row">
                                                                    <div class="col-lg-5 col-sm-5 col-xs-12">
                            <?php
                            $min_exp = array();
                            for ($year = 0; $year <= 30; $year++) {
                                $min_exp[$year] = $year;
                            }
                            echo $this->Form->input('Job.min_exp', array('type' => 'select', 'options' => $min_exp, 'label' => false, 'class' => "form-control required", 'empty' => 'Min', 'onChange' => 'getMaxExpList(this.value);'));
                            ?>        
                                                                    </div>
                                                                    <div class="col-lg-1 col-sm-1 col-xs-12 to_cretae_hs">
                                                                        <b>to</b>        
                                                                    </div>
                                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                            <?php
                            $max_exp = array();
                            echo $this->Form->input('Job.max_exp', array('type' => 'select', 'options' => $max_exp, 'label' => false, 'class' => "form-control required", 'empty' => __d('user', 'Max', true)));
                            ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>-->

                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Annual Salary', true); ?> <div class="star_red">*</div></label>
                                <div class="form_input_education qualification-select">
                                    <span>
                                            <?php
                                            global $sallery;
                                            echo $this->Form->input('Job.salary', array('type' => 'select', 'options' => $sallery, 'label' => false, 'class' => "form-control required", 'empty' => __d('user', 'Select Salary', true)));
                                            ?>        
                                    </span>
                                </div>
                            </div>
                            <!--                            <div class="form_lst">
                                                            <label>Annual Salary <div class="star_red">*</div></label>
                                                            <div class="rltv">
                                                                <div class="row">
                                                                    <div class="col-lg-5 col-sm-5 col-xs-12">
                            <?php
                            global $minSalary;
                            echo $this->Form->input('Job.min_salary', array('type' => 'select', 'options' => $minSalary, 'label' => false, 'class' => "form-control required", 'empty' => 'Min', 'onChange' => 'getMaxSalaryList(this.value);'));
                            ?>        
                                                                    </div>
                                                                    <div class="col-lg-1 col-sm-1 col-xs-12 to_cretae_hs">
                                                                        <b>to</b>        
                                                                    </div>
                                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                            <?php
                            $maxSalary = array();
                            echo $this->Form->input('Job.max_salary', array('type' => 'select', 'options' => $maxSalary, 'label' => false, 'class' => "form-control required", 'empty' => 'Max'));
                            ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>-->



                            <?php if (isset($_SESSION['type']) && $_SESSION['type'] != 'bronze') { ?>

                                <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'Company Logo', true); ?> <span class="star_red"></span></label>
                                    <div class="form_input_education form_upload_file">
                                        <span class="choose-file-your">Choose File</span>
                                        <?php echo $this->Form->file('Job.logo', array('class' => 'form-control default', 'onchange' => 'imageValidation()')) ?>
                                         
                                    </div>
<div class="abccc pstrength-minchar"><?php echo __d('user', 'Supported File Types', true); ?>: gif, jpg, jpeg, png (Max. <?php echo $max_size; ?>MB).</div>
                                </div>

<!--                                <div class="form_lst">

                                    <div class="or_det_devide"><span><?php echo __d('user', 'Or', true); ?></span></div>

                                </div>

                                <div class="form_lst">
                                    <label><?php echo __d('user', 'Use Profile Logo', true); ?></label>
                                    <span class="rltv rltv_check_boco">

                                        <span>
                                            <?php
                                            if (trim($logo_status)) {
                                                echo $this->Form->input('Job.job_logo_check', array('type' => 'checkbox', 'label' => '', 'value' => '1'));
                                            } else {
                                                echo $this->Form->input('Job.job_logo_check', array('type' => 'checkbox', 'label' => '', 'value' => '0'));
                                            }
                                            ?>
                                        </span>
                                    </span>

                                </div>  -->
                            <?php } ?>

                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'Expiry Date', true); ?> <div class="star_red">*</div></label>
                                <div id="cust_idd" class="form_input_education rel_Location">
                                    <?php echo $this->Form->text('Job.expire_time', array('class' => "form-control required",'autocomplete' => 'off', 'placeholder' => __d('user', 'Expiry Date', true), 'id' => 'JobLastdate')) ?>
                                </div>
                            </div>



                            <div class="">
                                <span class="rltv">
                                    <div class="pro_row_left">
                                        <?php echo $this->Form->submit(__d('user', 'Post Job', true), array('div' => false, 'label' => false, 'class' => 'input_btn', 'id' => 'saveCreateButton')); ?>
                                        <a href="#infoDetails" rel="facebox" title="View" class="input_btn rigjt" onclick="displayData();"><?php echo __d('user', 'Preview', true); ?></a>
                                        <?php //echo $this->Html->link('Back', array('controller' => 'jobs', 'action' => 'selectType'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow'));  ?>
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











<?php
$userdetail = classregistry::init('User')->find('first', array('conditions' => array('User.id' => $this->Session->read('user_id'))));
?>

<div id="infoDetails" style="display: none;">
    <!-- Fieldset -->
    <div class="nzwh-wrapper">
        <fieldset class="nzwh">

            <div class="iner_form_bg_box">

                <div class="iner_sec_top_row_view">
                    <div class="inr_firm_roq_left">
                        <div class="areal_img_box" id="dvPreview" style="display:none;">
                            <?php
                            /* $profile_image = $userdetail['User']['profile_image'];
                              $path = UPLOAD_THUMB_PROFILE_IMAGE_PATH . $profile_image;
                              if (file_exists($path) && !empty($profile_image)) {
                              echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $profile_image, array('escape' => false,'rel'=>'nofollow'));
                              } else {
                              echo $this->Html->image('front/no_image_user.png');
                              } */
                            ?>
                        </div>
                    </div>
                    <div class="inr_firm_roq">
                        <div class="top_page_name_box">
                            <div class="page_name_boox page_name_boox_small"><span id="facebox_job_title"></span></div>
                        </div>
                        <div class="clear"></div>
                        <div class="list_bot_boox_table_bbox">
                            <div class="list_bot_boox_table">
                                <div class="list_bot_boox_row">

                                    <div class="list_bot_boox_col">

                                        <?php echo $this->Html->image('front/full_time_icon.png', array('alt' => 'icon')); ?>
                                        <span id="facebox_work_type1"></span>

                                    </div>
                                    <div class="list_bot_boox_col">
                                        <?php echo $this->Html->image('front/location_icon.png', array('alt' => 'icon')); ?>
                                        <span id="facebox_city_name"> </span>
                                        <span id="facebox_state_name"></span>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>

                <div class="full_row_div">
                    <div class="ful_row_ddta">
                        <div class="ful_row_ddta">
                            <span class="blue"><?php echo __d('user', 'Company name', true); ?>: </span><span class="grey" id="facebox_company_name"></span>
                        </div>
                        <span class="blue"><?php echo __d('user', 'Company Website', true); ?>: </span><span class="grey" id="facebox_company_website"></span>
                    </div>
                    <?php /* <div class="ful_row_ddta" id="facebox_job_price_div">
                      <span class="blue">Wage Package:</span>
                      <span class="grey" id="facebox_job_price"></span>
                      </div> */ ?>
                    <div class="ful_row_ddta">
                        <span class="blue"><?php echo __d('user', 'Job Type', true); ?>:</span><span class="grey" id="facebox_work_type">

                        </span>
                    </div>
                    <div class="ful_row_ddta">
                        <span class="blue"><?php echo __d('user', 'Category', true); ?>:</span><span class="grey" id="facebox_job_category">

                        </span>
                    </div>
                    <div class="ful_row_ddta">
                        <span class="blue"><?php echo __d('user', 'Experience', true); ?>:</span><span class="grey" id="facebox_job_experience">  

                        </span>
                    </div>
                    <div class="ful_row_ddta">
                        <span class="blue"><?php echo __d('user', 'Anunal Salary', true); ?>:</span><span class="grey" id="facebox_salary">  

                        </span>
                    </div>
                    <div class="ful_row_ddta">
                        <span class="blue"><?php echo __d('user', 'Skills', true); ?>:</span><span class="grey" id="facebox_skill">  

                        </span>
                    </div>
                    <div class="ful_row_ddta">
                        <span class="blue"><?php echo __d('user', 'Designation', true); ?>:</span><span class="grey" id="facebox_designation">  

                        </span>
                    </div>



                </div>

                <div class="clear"></div>



                <div class="clear"></div>

                <div class="data_row_ful_skil">
                    <div class="data_row_ful_skil_header"><?php echo __d('user', 'Job Description', true); ?></div>
                    <div class="clear"></div>

                    <div class="data_row_ful_skil_content2" id="facebox_job_description">
                    </div>

                </div>

                <div class="clear"></div>


            </div>
        </fieldset>
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

    function displayData() {


        var instance = CKEDITOR.instances.JobDescription;

        var description = instance.getData()


        //var content = $( 'textarea.editor' ).val();



        if ($.trim($("#JobTitle").val()) == '') {
            $('#facebox_job_title').text('N/A');
        } else {
            $('#facebox_job_title').text($('#JobTitle').val());
        }
        if ($.trim($("#JobWorkType option:selected").val()) == '') {
            $('#facebox_work_type1').text('N/A');
        } else {
            $('#facebox_work_type1').text($("#JobWorkType option:selected").text());
        }

<?php /* if ($.trim($("#JobCityId option:selected").val()) == '') {
  $('#facebox_city_name').text('');
  } else {
  $('#facebox_city_name').text($("#JobCityId option:selected").text());
  }

  if ($.trim($("#JobStateId option:selected").val()) == '') {
  $('#facebox_state_name').text('');
  } else {
  if ($.trim($("#UserPostalCodeId").val()) == '') {
  $('#facebox_state_name').text($("#JobStateId option:selected").text());
  } else {
  $('#facebox_state_name').text($("#JobStateId option:selected").text()+", "+$("#UserPostalCodeId").val());
  }
  } */ ?>
        if ($.trim($("#job_city").val()) == '') {
            $('#facebox_city_name').text('N/A');
        } else {
            $('#facebox_city_name').text($("#job_city").val());
        }

        if ($.trim($('#JobCompanyName').val()) == '') {
            $('#facebox_company_name').text('N/A');
        } else {
            $('#facebox_company_name').text($('#JobCompanyName').val());
        }

        if ($.trim($('#JobUrl').val()) == '') {
            $('#facebox_company_website').text('N/A');
        } else {
            $('#facebox_company_website').text($('#JobUrl').val());
        }

<?php /* if ($('input[name="data[Job][price_status]"]:checked').val() == 1) {
  $('#facebox_job_price_div').show();
  if ($.trim($("#JobPrice option:selected").text()) == 'Wage Package') {
  $('#facebox_job_price').text('N/A');
  } else {
  $('#facebox_job_price').text($("#JobPrice option:selected").text());
  }
  } else {
  $('#facebox_job_price_div').hide();
  } */
?>

        if ($.trim($("#JobWorkType option:selected").text()) == 'Select Work Type') {
            $('#facebox_work_type').text('N/A');
        } else {
            $('#facebox_work_type').text($("#JobWorkType option:selected").text());
        }

        if ($.trim($("#JobCategoryId option:selected").text()) == "<?php echo __d('user', 'Select Job Category', true); ?>") {
            $('#facebox_job_category').text('N/A');
        } else {
            $('#facebox_job_category').text($("#JobCategoryId option:selected").text());
        }

        /*if ($.trim($("#JobExpYear option:selected").text()) == 'Select Year') {
         $('#facebox_job_experience').text('N/A');
         } else {
         if($("#JobExpMonth").val() == '')
         {
         var m = $("#JobExpYear option:selected").text() + ' year ';
         $('#facebox_job_experience').text(m);
         
         }else{
         var s = $("#JobExpYear option:selected").text() + ' year ' + $("#JobExpMonth option:selected").text() + ' month';
         $('#facebox_job_experience').text(s);
         }
         }*/
        if ($.trim($('#JobExp').val()) == '') {
            $('#facebox_job_experience').text('N/A');
        } else {
            if ($.trim($('#JobExp').val()) == '') {
                $('#facebox_job_experience').text($("#JobExp option:selected").text());
            } else {
                $('#facebox_job_experience').text($("#JobExp option:selected").text());
            }
        }
        if ($.trim($('#JobSalary').val()) == '') {
            $('#facebox_salary').text('N/A');
        } else {
            if ($.trim($('#JobSalary').val()) == '') {
                $('#facebox_salary').text("<?php echo CURRENCY; ?> " + $('#JobSalary').val());
            } else {
                $('#facebox_salary').text("<?php echo CURRENCY; ?> " + $('#JobSalary').val());
            }
        }

        if ($.trim($('#JobSkill').val()) == '') {
            $('#facebox_skill').text('N/A');
        } else {
            //$('#facebox_skill').text($('#JobSkill').val());
            var skillArr = $('#JobSkill').val();
            newSkillVal = new Array();
            $.each(skillArr, function (key, value) {
                newSkillVal[key] = $("#JobSkill option[value='" + value + "']").text();
            });
            $('#facebox_skill').text(newSkillVal.join(", "));
        }
        if ($.trim($('#JobDesignation').val()) == '') {
            $('#facebox_designation').text('N/A');
        } else {
            $('#facebox_designation').text($("#JobDesignation option[value='" + $('#JobDesignation').val() + "']").text());
        }



        if ($.trim($('#JobYoutubeLink').val()) == '') {
            $('#facebox_youtube').text('N/A');
        } else {
            $('#facebox_youtube').text($('#JobYoutubeLink').val());
        }

        if ($.trim($('#JobSellingPoint1').val()) == '' && $.trim($('#JobSellingPoint2').val()) == '' & $.trim($('#JobSellingPoint3').val()) == '') {
            $('#facebox_specification_status').hide();
        } else {
            var sessionType = $('#JobSessiontype').val();
            if (sessionType == 'silver' || sessionType == 'gold') {
                $('#facebox_specification_status').show();
                if ($('#JobSellingPoint1').val() == '') {
                    $('#facebox_selling_point1').hide();
                } else {
                    $('#facebox_selling_point1').text($('#JobSellingPoint1').val());
                }

                if ($('#JobSellingPoint2').val() == '') {
                    $('#facebox_selling_point2').hide();
                } else {
                    $('#facebox_selling_point2').text($('#JobSellingPoint2').val());
                }

                if ($('#JobSellingPoint3').val() == '') {
                    $('#facebox_selling_point3').hide();
                } else {
                    $('#facebox_selling_point3').text($('#JobSellingPoint3').val());
                }

            } else {
                $('#facebox_specification_status').show();
                $('#facebox_selling_point1').text($('#JobSellingPoint1').val());
                $('#facebox_selling_point2').hide();
                $('#facebox_selling_point3').hide();
            }
        }

        //$('#facebox_job_description').html(CKEDITOR.instances.JobDescription.getData());
        var desc = description.replace(/&nbsp;/gi, '')

        if (desc == '') {
            $('#facebox_job_description').text('N/A');
        } else {

            $('#facebox_job_description').text(desc.replace(/(<([^>]+)>)/ig, ""));
            $('#facebox_job_description').replace('<br />', '');




        }
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
                var over_max_size = <?php echo $max_size ?> * 1048576;
                if (filesize > over_max_size) {
                    alert('Maximum <?php echo $max_size; ?>MB file size allowed for logo.');
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
                var over_max_size = <?php echo $max_size ?> * 1048576;
                if (filesize > over_max_size) {
                    alert('Maximum <?php echo $max_size; ?>MB file size allowed for CV Document.');
                    document.getElementById("UserCv").value = '';
                    return false;
                }
            }
        }
    }






</script>
