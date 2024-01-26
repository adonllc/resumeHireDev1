<script>

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
     
     $("#error_message" + selectId).removeClass("error");
     $('#' + selectId).removeClass("error_border");
     $("div #error_message" + selectId).text('');
     
     
     }
     
     count++;
     
     });
     });
     
     }); */
    
    

</script>

<style>
    .error { color:red; /*border: 1px solid red!important;*/ }
    .already { color:green; /*border: 1px solid green!important;*/}
</style>

<div id="dynamic<?php echo $cc; ?>" class="dynamiccc">
    <span class="colify-title"><?php echo __d('user', 'Add Another Qualification', true);?> </span> 

    <div class="form_list_education">
       <label class="lable-acc"><?php echo __d('user', 'Course Name', true);?>  <span class="star_red">*</span></label>
        <div class="form_input_education qualification-select"> 
            <span>
                <?php echo $this->Form->input('Education.' . $cc . '.basic_course_id', array('id' => 'basic_course_id' . $cc, 'data-value' => $cc, 'type' => 'select', 'options' => $basicCourseList, 'label' => false, 'div' => false, 'class' => "form-control checkCourse required", 'empty' => __d('user', 'Select Course', true))) ?>
                <?php //echo $this->Ajax->observefield('basic_course_id' . $cc, array('url' => 'specilyList/' . $cc, 'update' => 'specilyListBasic' . $cc)); ?>
            </span>
        </div>
        <div id="error_message<?php echo 'basic_course_id' . $cc; ?>"></div>
    </div>

    <div id="specilyListBasic<?php echo $cc; ?>">
        <?php
        if (!isset($specialList)) {
            $specialList = array();
        }
        ?>
        <div id="spical<?php echo $cc ?>">
            <div class="form_list_education">
                <label class="lable-acc"><?php echo __d('user', 'Specialization', true);?>  <span class="star_red">*</span></label>
                <div class="form_input_education qualification-select"> 
                    <span>
                    <?php echo $this->Form->input('Education.' . $cc . '.basic_specialization_id', array('type' => 'select', 'options' => $specialList, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => __d('user', 'Select Specialization', true))) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="form_list_education">
            <label class="lable-acc"><?php echo __d('user', 'University/Institute', true);?>  <span class="star_red">*</span></label>
            <div class="form_input_education"> 
                <span>
                <?php echo $this->Form->text('Education.' . $cc . '.basic_university', array('label' => false, 'div' => false, 'class' => "form-control required",'placeholder' => __d('user', 'University/Institute', true))) ?>
                </span>
            </div>
        </div>
        <?php //$year = range(date("Y"), 1950);  ?>
        <div class="form_list_education">
            <label class="lable-acc"><?php echo __d('user', 'Passed in', true);?>  <span class="star_red">*</span></label>
            <div class="form_input_education qualification-select"> 
                <span>
                <?php echo $this->Form->input('Education.' . $cc . '.basic_year', array('type' => 'select', 'options' => array_combine(range(date("Y"), 1950), range(date("Y"), 1950)), 'label' => false, 'div' => false, 'class' => "form-control required in_year", 'empty' => __d('user', 'Select Year', true))) ?>
                </span>
            </div>
        </div>
    </div>
    <div class="wewa">
        <div class="wewain">
            <?php echo $this->Html->link('<i class="fa fa-trash"></i>'.__d('user', 'Remove', true), 'javascript:removeCC("' . $cc . '");', array('confirm' => "'".__d('user', 'Are you sure you want to delete this row?', true)."'", 'escape' => false,'rel'=>'nofollow')); ?>
        </div>
    </div>


</div>