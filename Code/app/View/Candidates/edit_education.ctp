<script>

    $(document).ready(function () {
        $.validator.addMethod("contact", function (value, element) {
            return  this.optional(element) || (/^[0-9+]+$/.test(value));
        }, "<?php echo __d('user', 'Contact Number is not valid', true);?>.");
        $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_]+$/.test(value);
        }, "*<?php echo __d('user', 'Note: Special characters, number and spaces are not allowed', true);?>.");

        $("#editEducation").validate();

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
<link href="<?php echo HTTP_PATH; ?>/css/front/uploadfilemulti.css" rel="stylesheet">
<script src="<?php echo HTTP_PATH; ?>/js/front/jquery.fileuploadmulti.min.js" charset="utf-8"></script>
<script>

    $(document).ready(function () {

        $("#addeducation").click(function () {
            var cc = ($('#educationcounter').val() * 1) + 1;
            $('#educationcounter').val(cc);
            $("#loader1").show();
            $.ajax({
                type: "POST",
                url: "<?php echo HTTP_PATH; ?>/candidates/openeducation/" + cc,
                cache: false,
                success: function (responseText) {
                    $("#loader1").hide();
                    $('#educationElement').append(responseText);
                    $("#loader1").hide();
                }
            });
        });



    });

    function removeCC(cc, id) {
        if (id) {
            $("#loader1").show();
            $.ajax({
                type: "POST",
                url: "<?php echo HTTP_PATH; ?>/candidates/deleteeducation/" + id,
                cache: false,
                success: function (responseText) {
                    $('#dynamic' + cc).remove();
                    $("#loader1").hide();
                    $(".success_msg").text("Education deleted successfully");

                    window.location.href = "<?php echo HTTP_PATH; ?>/candidates/editEducation";
                }
            });
        } else {
            $('#dynamic' + cc).remove();
        }
    }


    /*  $(document).ready(function () {
     
     $('.checkCourse').change(function (e) {
     
     //alert(e.target.id); //get id of dropdown
     var getVal = $(this).val();
     var selectId = e.target.id;
     alert(getVal);
     var count = 0;
     $('#basic_course_id' + count).each(function (e) {
     
     if ($(this).val() == getVal) {
     $("#error_message" + selectId).addClass("error");
     $('#' + selectId).addClass("error_border");
     $("div #error_message" + selectId).text('You have already select the same course name.');
     $("input[type=submit]").attr('disabled', 'disabled');
     
     } else {
     alert('no error');
     $("#error_message" + selectId).removeClass("error");
     $('#' + selectId).removeClass("error_border");
     $("div #error_message" + selectId).text('');
     $("input[type=submit]").removeAttr('disabled');
     
     }
     
     count++;
     
     });
     });
     
     }); */

    $(document).on("change", ".in_year", function (e) {
        //$('.in_year').change(function(e){
        console.log('from edit');
        var this_value = $(this).val();
        var this_id = $(this).attr('id');
        var element = $(this);
        var is_error = 0;

        $('.in_year').each(function (e) {
            var other_value = $(this).val();
            var other_id = $(this).attr('id');

            if (this_value == other_value && this_id != other_id)
            {
                is_error = 1;
            }
        });
        if (is_error)
        {
            //element.val('').change();
            alert('You have already selected year ' + this_value);
            element.addClass('error');
            $('#addeducation').addClass('disableClick');
            $('input[type="submit"]').attr('disabled', 'disabled');
        } else
        {
            element.removeClass('error');
            $('#addeducation').removeClass('disableClick');
            $('input[type="submit"]').removeAttr('disabled', 'disabled');
        }
    });


    $(document).on("change", ".checkCourse", function (e) {
        //$('.in_year').change(function(e){
        // console.log('from edit');
        var courseValue = $(this).val();
        // alert(courseValue);
        var count = $(this).attr("data-value");
//       / alert(count);
        if (courseValue) {
            $("#loader1").show();
            $.ajax({
                type: "POST",
                url: "<?php echo HTTP_PATH; ?>/candidates/getSpecialization/" + courseValue + "/" + count,
                cache: false,
                success: function (responseText) {
                    $("#loader1").hide();
                    //alert(responseText);
                    if (responseText !== '') {
                        $("#spical" + count).html(responseText);
                    } else {
                        $("#spical" + count).html('');
                    }

                }
            });
        }
    });



</script>
<style>
    .error { color:red; /*border: 1px solid red!important;*/ }
    .already { color:green; /*border: 1px solid green!important;*/}
    .disableClick{
        pointer-events: none;
    }
</style>

<?php
echo $this->Html->script('jquery/ui/jquery.ui.menu.js');
echo $this->Html->script('jquery/ui/jquery.ui.autocomplete.js');
?>
<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                <div class="col-xs-12 col-sm-6 col-md-9 col-lg-9">
                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-education-boxes-top"><h2><i><?php echo $this->Html->image('front/home/education-icon-top.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Education Information', true);?></span></h2></div>
                        <div class="information_cntn" style="position:inherit !important;">
                            <?php echo $this->element('session_msg'); ?>
                            <?php echo $this->Form->create("Null", array('enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'editEducation')); ?>
                           
                            <div id="educationElement"  class="educationElement">
                               
                                <?php
                                if (isset($eduDetails) && !empty($eduDetails)) {
                                    // pr($eduDetails); exit;
                                    $count = 0;
                                    foreach ($eduDetails as $eduDetail) {
                                        // echo"-->"; pr($eduDetail); 
                                        ?>
                                <span class="colify-title"><?php echo __d('user', 'Qualification', true);?> <?php echo $count + 1; ?></span>

                                        <div id="dynamic<?php echo $count; ?>" class="dynamiccc">
                                        <div class="form_list_education">
                                            <?php echo $this->Form->hidden('Education.' . $count . '.id'); ?>
                                        </div>

                                            <div class="form_list_education">
                                                <label class="lable-acc"><?php echo __d('user', 'Course Name', true);?> <span class="star_red">*</span></label>
                                                <div class="form_input_education qualification-select">
                                                    <span>
                                                    <?php echo $this->Form->input('Education.' . $count . '.basic_course_id', array('id' => 'basic_course_id' . $count, 'data-value' => $count, 'type' => 'select', 'options' => $basicCourseList, 'label' => false, 'div' => false, 'class' => "form-control checkCourse required", 'empty' => __d('user', 'Select Course', true))) ?>
                                                    <?php //echo $this->Ajax->observefield('basic_course_id' . $count, array('url' => 'specilyList/' . $count, 'update' => 'specilyListBasic' . $count)); ?>
                                                    </span>
                                                </div>
                                                <div id="error_message<?php echo 'basic_course_id' . $count; ?>"></div>
                                            </div>

                                            <div id="specilyListBasic<?php echo $count; ?>">
                                                <?php
                                                //$specilyList1 = Classregistry::init('Specialization')->find('list', array('conditions' => array('Specialization.status' => 1, 'Specialization.course_id' => $eduDetail['Education']['basic_course_id']), 'order' => array('Specialization.name' => 'asc')));
//pr($eduDetail['Education']['basic_specialization_id']);
                                                $specialList = Classregistry::init('Specialization')->getSpecializationListByCourseId($eduDetail['Education']['basic_course_id']);
//pr($specialList);                                         
                                                ?>
                                                <div id="spical<?php echo $count ?>">
                                                    <?php if (!empty($specialList)) { ?>

                                                        <div class="form_list_education">
                                                            <label class="lable-acc"><?php echo __d('user', 'Specialization', true);?> <span class="star_red">*</span></label>
                                                            <div class="form_input_education qualification-select">
                                                                <span>
                                                                <?php echo $this->Form->input('Education.' . $count . '.basic_specialization_id', array('type' => 'select', 'options' => $specialList, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => __d('user', 'Select Specialization', true))) ?>
                                                                </span>
                                                            </div>
                                                        </div>

                                                    <?php } ?>
                                                </div>
                                                <div class="form_list_education">
                                                    <label class="lable-acc"><?php echo __d('user', 'University/Institute', true);?> <span class="star_red">*</span></label>
                                                    <div class="form_input_education">
                                                        <?php echo $this->Form->text('Education.' . $count . '.basic_university', array('label' => false, 'div' => false, 'class' => "form-control required",'placeholder' => __d('user', 'University/Institute', true))) ?>
                                                    </div>
                                                </div>
                                                <?php //$year = range(date("Y"), 1950);      ?>
                                                <div class="form_list_education">
                                                    <label class="lable-acc"><?php echo __d('user', 'Passed in', true);?> <span class="star_red">*</span></label>
                                                    <div class="form_input_education qualification-select">
                                                        <span>
                                                        <?php echo $this->Form->input('Education.' . $count . '.basic_year', array('type' => 'select', 'options' => array_combine(range(date("Y"), 1950), range(date("Y"), 1950)), 'label' => false, 'div' => false, 'class' => "form-control required in_year", 'empty' => __d('user', 'Select Year', true))) ?>
                                                        </span>
                                                    </div>
                                                </div>



                                                <div class="wewa">
                                                    <div class="wewain">
                                                        <?php echo $this->Html->link('<i class="fa fa-trash"></i>'.__d('user', 'Remove', true), 'javascript:removeCC("' . $count . '","' . $eduDetail['Education']['id'] . '");', array('confirm' => "'".__d('user', 'Are you sure you want to delete this qualification ?', true)."'", 'escape' => false,'rel'=>'nofollow')); ?>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <?php
                                        $count++;
                                    }
                                    ?>
                                <?php } else { ?>
                                    <span class="colify-title"><?php echo __d('user', 'Qualification', true);?></span><br>

                                    <div id="dynamic0" class="dynamiccc">

                                        <div class="form_list_education">
                                            <label class="lable-acc"><?php echo __d('user', 'Course Name', true);?> <span class="star_red">*</span></label>
                                            <div class="form_input_education qualification-select"> 
                                            <span>
                                                <?php echo $this->Form->input('Education.0.basic_course_id', array('id' => 'basic_course_id0', 'data-value' => '0', 'type' => 'select', 'options' => $basicCourseList, 'label' => false, 'div' => false, 'class' => "form-control checkCourse required", 'empty' => __d('user', 'Select Course', true))) ?>
                                                <?php //echo $this->Ajax->observefield('basic_course_id0', array('url' => 'specilyList/0', 'update' => 'specilyListBasic0')); ?>
                                            </span>
                                            </div>

                                        </div>

                                        <div id="specilyListBasic0">
                                            <?php
                                            if (!isset($specialList)) {
                                                $specialList = array();
                                            }
                                            ?>
                                            <div id="spical0">
                                                <div class="form_list_education">
                                                    <label class="lable-acc"><?php echo __d('user', 'Specialization', true);?> <span class="star_red">*</span></label>
                                                    <div class="form_input_education qualification-select"> 
                                                    <span><?php echo $this->Form->input('Education.0.basic_specialization_id', array('type' => 'select', 'options' => $specialList, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => __d('user', 'Select Specialization', true))) ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form_list_education">
                                                <label class="lable-acc"><?php echo __d('user', 'University/Institute', true);?> <span class="star_red">*</span></label>
                                                <div class="form_input_education qualification-select">     
                                                   <?php echo $this->Form->text('Education.0.basic_university', array('label' => false, 'div' => false, 'class' => "form-control required",'placeholder' => __d('user', 'University/Institute', true))) ?>
                                                </div>
                                            </div>
                                            <?php //$year = range(date("Y"), 1950);        ?>
                                            <div class="form_list_education">
                                                <label class="lable-acc"><?php echo __d('user', 'Passed in', true);?> <span class="star_red">*</span></label>
                                                <div class="form_input_education qualification-select"> 
                                                    <span ><?php echo $this->Form->input('Education.0.basic_year', array('type' => 'select', 'options' => array_combine(range(date("Y"), 1950), range(date("Y"), 1950)), 'label' => false, 'div' => false, 'class' => "form-control required in_year", 'empty' => __d('user', 'Select Year', true))) ?></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                <?php } ?>

                            </div>

                            <div class="educationElement">
                                <div class="wewain-add">
                                    <div id="loader1" class="loader" style="display:none; left: 50%;   position:absolute"><?php echo $this->Html->image("loading.gif"); ?></div>
                                    <?php echo $this->Html->link(__d('user', '+ Add More', true), 'javascript:void(0);', array('id' => 'addeducation', 'class' => 'add_btn', 'escape' => false,'rel'=>'nofollow')); ?>
                                </div>
                            </div>

                            <div class="form_lst sssss">
                                <span class="rltv">
                                    <div class="pro_row_left">
                                        <?php //echo $this->Form->hidden('User.old_cv');    ?>
                                        <?php
                                        if (isset($eduDetails) && !empty($eduDetails)) {
                                            echo $this->Form->input('Candidate.cc', array('type' => 'hidden', 'id' => 'educationcounter', 'value' => (count($eduDetails) - 1)));
                                        } else {
                                            echo $this->Form->input('Candidate.cc', array('type' => 'hidden', 'id' => 'educationcounter', 'value' => '0'));
                                        }
                                        ?>
                                        <?php echo $this->Form->submit(__d('user', 'Update', true), array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                                        <?php echo $this->Html->link(__d('user', 'Cancel', true), array('controller' => 'users', 'action' => 'myaccount'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow')); ?>
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

