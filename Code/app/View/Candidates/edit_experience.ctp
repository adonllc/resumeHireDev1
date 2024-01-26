<script>
    $(document).ready(function () {
        $("#editExperience").validate();
    });
</script>
<?php echo $this->Html->script('jquery/ui/jquery.ui.core.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.widget.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.position.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.datepicker.js'); ?>
<?php echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css'); ?>
<?php echo $this->Html->script('jquery.validate.js'); ?>



<link href="<?php echo HTTP_PATH; ?>/css/front/uploadfilemulti.css" rel="stylesheet">

<script src="<?php echo HTTP_PATH; ?>/js/front/jquery.fileuploadmulti.min.js" charset="utf-8"></script>
<script>

    $(document).ready(function () {

        $("#addexperience").click(function () {
            var cc = ($('#experiencecounter').val() * 1) + 1;
            $('#experiencecounter').val(cc);
            $("#loader1").show();
            $.ajax({
                type: "POST",
                url: "<?php echo HTTP_PATH; ?>/candidates/openexperience/" + cc,
                cache: false,
                success: function (responseText) {
                    $("#loader1").hide();
                    $('#experienceElement').append(responseText);
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
                url: "<?php echo HTTP_PATH; ?>/candidates/deleteexperience/" + id,
                cache: false,
                success: function (responseText) {
                    $('#dynamic' + cc).remove();
                    $("#loader1").hide();

                    window.location.href = "<?php echo HTTP_PATH; ?>/candidates/editExperience";
                }
            });
        } else {
            $('#dynamic' + cc).remove();
        }
    }


    /*  $(document).ready(function () {
     $('.upperyear').change(function () {
     
     var opt = '';
     var i = '';
     var currentYear = new Date().getFullYear()
     var firstyearVal = $(this).val();
     //alert(firstyearVal);
     //console.log($(this).val());
     var lowerYearEle = $(this).parent().siblings('.loweryeardiv').addClass('chaman');
     // console.log(lowerYearEle);
     for (i = firstyearVal; i <= currentYear; i++)
     {
     opt += '<option value="' + i + '">' + i + '</option>';
     }
     //console.log(opt);
     $(this).parent().siblings('.loweryeardiv').find('.loweryear').append(opt);
     });
     }); */

    $(document).on("change", ".upperyear", function (e) {
        var opt = '';
        var i = '';
        var upperValue = $(this).val();
        var count = $(this).attr("data-value");
        var currentYear = new Date().getFullYear();
        if (upperValue == currentYear) {
            var date_cur = new Date();
            var current_month = date_cur.getMonth();
            var fromMonth = $("#fromMonth" + count).val();
            if (current_month < fromMonth) {
                $("#fromMonth" + count).val(current_month);
                $("#toMonth" + count).val(current_month);
            }
            $("#fromMonth" + count+" > option").each(function () {
                var new_str1 = $(this).val();     
                if (parseInt(new_str1) > parseInt(current_month)) {
                    $(this).prop("disabled", true);
                }
            })
            $("#toMonth" + count+" > option").each(function () {
                var new_str1 = $(this).val();     
                if (parseInt(new_str1) > parseInt(current_month)) {
                    $(this).prop("disabled", true);
                }
            })
            
        }else{
            $("#fromMonth" + count+" > option").removeProp('disabled');
            $("#toMonth" + count+" > option").removeProp('disabled');
        }
        //alert(upperValue);
        //alert(count);
        $(".loweryear" + count).empty();

        if (upperValue !== '') {
            for (i = upperValue; i <= currentYear; i++)
            {
                opt += '<option value="' + i + '">' + i + '</option>';
            }

            //console.log(opt);
            $(".loweryear" + count).removeAttr('disabled');
            $(".loweryear" + count).append(opt);
        } else {
            opt += '<option value=""> Select Year </option>';
            $(".loweryear" + count).prop('disabled', 'disabled');
            $(".loweryear" + count).append(opt);
        }

    });

    $(document).on("change", ".loweryear_cmn", function (e) {
        var opt = '';
        var i = '';
        var upperValue = $(this).val();
        var count = $(this).attr("data-value");
        var currentYear = new Date().getFullYear();
        if (upperValue == currentYear) {
            var date_cur = new Date();
            var current_month = date_cur.getMonth();
            var fromMonth = $("#toMonth" + count).val();
            if (current_month < fromMonth) {
                $("#toMonth" + count).val(current_month);
            }
          
            $("#toMonth" + count+" > option").each(function () {
                var new_str1 = $(this).val();     
                if (parseInt(new_str1) > parseInt(current_month)) {
                    $(this).prop("disabled", true);
                }
            })
            
        }else{
            $("#toMonth" + count+" > option").removeProp('disabled');
        }

    });

    $(document).ready(function () {
        //$("#editExperience").onsubmit(function (e) {
        $("#editExperience").on("submit", function (e) {


            var durationData = $('.expDuration').length;
            //alert(durationData);
            for (i = 0; i < durationData; i++) {
                //  var divId = $('#expDuration'+ i).html();
                //alert(durationData);
                var fromMonth = $('#duration' + i).find('#fromMonth' + i).val();
                var fromYear = $('#duration' + i).find('#Experience' + i + 'FromYear').val();
                var toMonth = $('#duration' + i).find('#toMonth' + i).val();
                var toYear = $('#duration' + i).find('#Experience' + i + 'ToYear').val();

                //alert(fromMonth);
                //alert(fromYear);
                //alert(toMonth);
                //alert(toYear);



                var currentYear = new Date().getFullYear()
                var currentMonth = (new Date).getMonth() + 1;

                // if (fromYear == toYear) {
                if ((parseInt(fromMonth) > parseInt(toMonth)) && ((fromYear > toYear) || (fromYear == toYear))) {

                    $('#durError' + i).text("<?php echo __d('user', 'From month should be less than to month', true); ?>");

                    $('#fromMonth' + i).addClass('error');
                    $('#Experience' + i + 'FromYear').addClass('error');
                    $('#toMonth' + i).addClass('error');
                    $('#Experience' + i + 'ToYear').addClass('error');

                    e.preventDefault();
                } else if ((parseInt(fromMonth) == parseInt(toMonth)) && ((parseInt(fromYear) > parseInt(toYear)))) {

                    $('#durError' + i).text("<?php echo __d('user', 'From month should be less than to month', true); ?>");

                    $('#fromMonth' + i).addClass('error');
                    $('#Experience' + i + 'FromYear').addClass('error');
                    $('#toMonth' + i).addClass('error');
                    $('#Experience' + i + 'ToYear').addClass('error');

                    e.preventDefault();
                } else if ((parseInt(fromYear) == parseInt(currentYear)) && (parseInt(toYear) == parseInt(currentYear))) {

                    if ((parseInt(fromMonth) > parseInt(currentMonth)) || (parseInt(toMonth) > parseInt(currentMonth))) {
                        $('#durError' + i).text("<?php echo __d('user', 'Please check months, it should be less than or equal to current month if you choose current year.', true); ?>");

                        $('#fromMonth' + i).addClass('error');
                        $('#Experience' + i + 'FromYear').addClass('error');
                        $('#toMonth' + i).addClass('error');
                        $('#Experience' + i + 'ToYear').addClass('error');

                        e.preventDefault();
                    } else {
                        $('#durError' + i).empty();
                        $('#fromMonth' + i).removeClass('error');
                        $('#Experience' + i + 'FromYear').removeClass('error');
                        $('#toMonth' + i).removeClass('error');
                        $('#Experience' + i + 'ToYear').removeClass('error');
                    }

                } else if ((parseInt(fromMonth) > parseInt(currentMonth) && parseInt(fromYear) >= parseInt(currentYear)) && (parseInt(toMonth) > parseInt(currentMonth) && parseInt(toYear) <= parseInt(currentYear))) {


                    $('#durError' + i).text("<?php echo __d('user', 'Please check months, it should be less than or equal to current month if you choose current year.', true); ?>");

                    $('#fromMonth' + i).addClass('error');
                    $('#Experience' + i + 'FromYear').addClass('error');
                    $('#toMonth' + i).addClass('error');
                    $('#Experience' + i + 'ToYear').addClass('error');

                    e.preventDefault();


                } else {
                    $('#durError' + i).empty();
                    $('#fromMonth' + i).removeClass('error');
                    $('#Experience' + i + 'FromYear').removeClass('error');
                    $('#toMonth' + i).removeClass('error');
                    $('#Experience' + i + 'ToYear').removeClass('error');
                }
                //}

            }

        });

    });

    function removeError(count) {
        $('#durError' + count).text('');
        $('#fromMonth' + count).removeClass('error');
        $('#Experience' + count + 'FromYear').removeClass('error');
        $('#toMonth' + count).removeClass('error');
        $('#Experience' + count + 'ToYear').removeClass('error');
    }

//    function checkyearmonth(){
//        var cc = ($('#experiencecounter').val() * 1) + 1;
//        var todayDate = new Date();
//        for(var i=0; i < cc; i++){
//            if($('#toMonth0').val() !='' && $('#Experience0ToYear').val() !=''){
//                var inputDateText = 
//                alert($('#Experience0ToYear').val());
//            }
//        }
//        alert('f');
//        return false;
//     
//    }
</script>


<?php
echo $this->Html->script('jquery/ui/jquery.ui.menu.js');
echo $this->Html->script('jquery/ui/jquery.ui.autocomplete.js');


$monthNums = range(1, 12);
foreach ($monthNums as $monthNum) {
    $dateObj = DateTime::createFromFormat('!m', $monthNum);
    $month[$dateObj->format('F')] = $dateObj->format('F');
}
?>
<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                <div class="col-xs-12 col-sm-6 col-md-9 col-lg-9">

                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-experience-boxes-top"><h2><i><?php echo $this->Html->image('front/home/experience-icon-top.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Experience Information', true); ?></span></h2></div>
                        <div class="information_cntn" style="position:inherit !important;">
                            <?php echo $this->element('session_msg'); ?>

                            <?php echo $this->Form->create("Null", array('enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'editExperience')); ?>


                            <div id="experienceElement"  class="experienceElement">

                                <?php
                                if (isset($expDetails) && !empty($expDetails)) {
                                    $count = 0;
                                    foreach ($expDetails as $expDetail) {
                                        ?>
                                <div class="colify-title"><?php echo __d('user', 'Experience', true); ?> <?php echo $count + 1; ?></div>

                                        <div id="dynamic<?php echo $count; ?>" class="dynamiccc">

                                            <div class="form_list_education">
                                                <label class="lable-acc"><?php echo __d('user', 'Industry', true); ?> <span class="star_red">*</span></label>
                                                <div class="form_input_education">
                                                    <?php echo $this->Form->text('Experience.' . $count . '.industry', array('label' => false, 'div' => false, 'class' => "form-control required", 'placeholder' => __d('user', 'Industry', true))) ?>
                                                    <?php echo __d('user', 'Please do not use abbreviations or short-forms', true); ?>
                                                </div>

                                            </div>
                                            <div class="form_list_education">
                                                <label class="lable-acc"><?php echo __d('user', 'Functional Area', true); ?> </label>
                                                <div class="form_input_education">
                                                    <?php echo $this->Form->text('Experience.' . $count . '.functional_area', array('label' => false, 'div' => false, 'class' => "form-control", 'placeholder' => __d('user', 'Functional Area', true))) ?>
                                                    <?php echo __d('user', 'Please do not use abbreviations or short-forms', true); ?>
                                                </div>
                                            </div>
                                            <div class="form_list_education">
                                                <label class="lable-acc"><?php echo __d('user', 'Role', true); ?> <span class="star_red">*</span></label>
                                                <div class="form_input_education">
                                                    <?php echo $this->Form->text('Experience.' . $count . '.role', array('label' => false, 'div' => false, 'class' => "form-control required", 'placeholder' => __d('user', 'Role', true))) ?>
                                                </div>
                                            </div>

                                            <div class="form_list_education">
                                                <label class="lable-acc"><?php echo __d('user', 'Company Name', true); ?> <span class="star_red">*</span></label>
                                                <div class="form_input_education">
                                                    <?php echo $this->Form->text('Experience.' . $count . '.company_name', array('label' => false, 'div' => false, 'class' => "form-control required", 'placeholder' => __d('user', 'Company Name', true))) ?>
                                                </div>
                                            </div>

                                            <div class="form_list_education">
                                               <label class="lable-acc"><?php echo __d('user', 'Designation', true); ?> <span class="star_red">*</span></label>
                                                <div class="form_input_education">
                                                    <?php echo $this->Form->text('Experience.' . $count . '.designation', array('label' => false, 'div' => false, 'class' => "form-control required", 'placeholder' => __d('user', 'Designation', true))) ?>
                                                </div>
                                            </div>

                                            <div class="form_list_education">
                                                <label class="lable-acc-add"><span class="lsdd-title"><?php echo __d('user', 'Duration', true); ?> <span class="star_red">*</span></span></label>

                                                <div id="duration<?php echo $count ?>" class="form_input_education rltv1 expDuration">  
                                                    <div class="row">

                                                        <div class="col-sm-6 col-xs-12">  
                                                            <label class="lable-acc lable-acc-month">Start Month</label>
                                                            <div class="qualification-select">
                                                        <span>
                                                            <?php
                                                            global $monthName;
//                                                          
                                                            ?>
                                                            <?php echo $this->Form->input('Experience.' . $count . '.from_month', array('id' => 'fromMonth' . $count, 'type' => 'select', 'options' => $monthName, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => __d('user', 'Select Month', true), 'onchange' => 'removeError(' . $count . ')')) ?>
                                                        </span>
                                                            </div>
                                                            </div>
                                                        <div class="col-sm-6 col-xs-12">   
                                                            <label class="lable-acc lable-acc-month">Start Year</label>
                                                            <div class="qualification-select">
                                                        <span>
                                                            <?php echo $this->Form->input('Experience.' . $count . '.from_year', array('data-value' => $count, 'type' => 'select', 'options' => array_combine(range(date("Y"), 1950), range(date("Y"), 1950)), 'label' => false, 'div' => false, 'class' => "form-control upperyear required", 'empty' => __d('user', 'Select Year', true), 'onchange' => 'removeError(' . $count . ')')) ?>
                                                        </span>
                                                            </div>
                                                            </div>
                                                        <div class="col-sm-12 col-xs-12 text-center">    
                                                            <b class="totag"><?php echo __d('user', 'TO', true); ?></b>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-12"> 
                                                            <label class="lable-acc lable-acc-month">End Month</label>
                                                            <div class="qualification-select">
                                                        <span>
                                                            <?php
                                                            global $monthName;
                                                            ?>
                                                            <?php echo $this->Form->input('Experience.' . $count . '.to_month', array('id' => 'toMonth' . $count, 'type' => 'select', 'options' => $monthName, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => __d('user', 'Select Month', true), 'onchange' => 'removeError(' . $count . ')')) ?>
                                                        </span>
                                                            </div>
                                                            </div>
                                                        <div class="col-sm-6 col-xs-12 loweryeardiv">
                                                            <label class="lable-acc lable-acc-month">End Year</label>
                                                            <div class="qualification-select">
                                                        <span>
                                                            <?php //echo $this->Form->input('Experience.' . $count . '.to_year', array('id' => 'toMonth' . $count, 'type' => 'select', 'options' => array_combine(range(date("Y"), 1950), range(date("Y"), 1950)), 'label' => false, 'div' => false, 'class' => "required", 'empty' => 'Select')) ?>
                                                            <?php
                                                            if (isset($expDetail['Experience']['to_year']) && !empty($expDetail['Experience']['to_year'])) {
                                                                $option = array();
                                                                $curYear = date("Y");
                                                                for ($i = $expDetail['Experience']['to_year']; $i <= $curYear; $i++) {
                                                                    $option[$i] = $i;
                                                                }
                                                            } else {
                                                                $option = array();
                                                            }
                                                            echo $this->Form->input('Experience.' . $count . '.to_year', array('data-value' => $count, 'options' => $option, 'type' => 'select', 'label' => false, 'div' => false, 'class' => "form-control loweryear_cmn loweryear$count", 'empty' => __d('user', 'Select Year', true), 'onchange' => 'removeError(' . $count . ')'))
                                                            ?>
                                                        </span>
                                                        </div>
                                                        </div>

                                                        <div id="durError<?php echo $count ?>" class="col-sm-12 col-xs-12" style="color:#f3665c;">    

                                                        </div>


                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form_list_education">
                                                <label class="lable-acc"><?php echo __d('user', 'Job Profile', true); ?></label>
                                                <div class="form_input_education">                                   
                                                    <?php echo $this->Form->input('Experience.' . $count . '.job_profile', array('type' => 'textarea', 'label' => false, 'div' => false, 'class' => "form-control", 'placeholder' => 'Job Profile')) ?>
                                                </div>
                                            </div>
                                            <?php echo $this->Form->hidden('Experience.' . $count . '.id'); ?>
                                            <div class="wewa">
                                                <div class="wewain">
                                                    <?php echo $this->Html->link('<i class="fa fa-trash"></i>'.__d('user', 'Remove', true), 'javascript:removeCC("' . $count . '","' . $expDetail['Experience']['id'] . '");', array('confirm' => __d('user', 'Are you sure you want to delete this row ?', true), 'escape' => false, 'rel' => 'nofollow')); ?>
                                                </div>
                                            </div>


                                        </div>
                                        <?php
                                        $count++;
                                    }
                                    ?>
                                <?php } else { ?>

                                    <div id="dynamic0" class="dynamiccc">
                                        <div class="form_list_education">
                                            <label class="lable-acc"><?php echo __d('user', 'Industry', true); ?> <span class="star_red">*</span></label>
                                            <div class="form_input_education"> 
                                                <?php echo $this->Form->text('Experience.0.industry', array('label' => false, 'div' => false, 'class' => "form-control required", 'placeholder' => __d('user', 'Industry', true))) ?>
                                                <?php echo __d('user', 'Please do not use abbreviations or short-forms', true); ?>
                                            </div>

                                        </div>
                                        <div class="form_list_education">
                                            <label class="lable-acc"><?php echo __d('user', 'Functional Area', true); ?> </label>
                                            <div class="form_input_education"> 
                                                <?php echo $this->Form->text('Experience.0.functional_area', array('label' => false, 'div' => false, 'class' => "form-control", 'placeholder' => __d('user', 'Functional Area', true))) ?>
                                                <?php echo __d('user', 'Please do not use abbreviations or short-forms', true); ?>
                                            </div>
                                        </div>
                                        <div class="form_list_education">
                                            <label class="lable-acc"><?php echo __d('user', 'Role', true); ?> <span class="star_red">*</span></label>
                                            <div class="form_input_education"> 
                                                <?php echo $this->Form->text('Experience.0.role', array('label' => false, 'div' => false, 'class' => "form-control required", 'placeholder' => __d('user', 'Role', true))) ?>
                                            </div>
                                        </div>

                                        <div class="form_list_education">
                                            <label class="lable-acc"><?php echo __d('user', 'Company Name', true); ?> <span class="star_red">*</span></label>
                                            <div class="form_input_education"> 
                                                <?php echo $this->Form->text('Experience.0.company_name', array('label' => false, 'div' => false, 'class' => "form-control required", 'placeholder' => __d('user', 'Company Name', true))) ?>
                                            </div>
                                        </div>

                                        <div class="form_list_education">
                                            <label class="lable-acc"><?php echo __d('user', 'Designation', true); ?> <span class="star_red">*</span></label>
                                            <div class="form_input_education"> 
                                                <?php echo $this->Form->text('Experience.0.designation', array('label' => false, 'div' => false, 'class' => "form-control required", 'placeholder' => __d('user', 'Designation', true))) ?>
                                            </div>
                                        </div>

                                        <div class="form_list_education">
                                            <label class="lable-acc-add"><span class="lsdd-title"><?php echo __d('user', 'Duration', true); ?></span></label>
                                            <span id="duration0" class="form_input_education rltv1 expDuration">  
                                                <div class="row">
                                                    <div class="col-sm-6 col-xs-12">  
                                                        <label class="lable-acc lable-acc-month">Start Month</label>
                                                        <div class="qualification-select">
                                                        <span>
                                                        <?php
                                                        global $monthName;
                                                        echo $this->Form->input('Experience.0.from_month', array('id' => 'fromMonth0', 'type' => 'select', 'options' => $monthName, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => __d('user', 'Select Month', true), 'onchange' => 'removeError(0)'))
                                                        ?>
                                                        </span>
                                                    </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xs-12"> 
                                                        <label class="lable-acc lable-acc-month">Start Year</label>
                                                        <div class="qualification-select">
                                                        <span>
                                                        <?php echo $this->Form->input('Experience.0.from_year', array('data-value' => '0', 'type' => 'select', 'options' => array_combine(range(date("Y"), 1950), range(date("Y"), 1950)), 'label' => false, 'div' => false, 'class' => "form-control upperyear required", 'empty' => __d('user', 'Select Year', true), 'onchange' => 'removeError(0)')) ?>
                                                        </span>
                                                        </div>
                                                        </div>
                                                    <div class="col-sm-12 col-xs-12 text-center">    
                                                        <b class="totag"><?php echo __d('user', 'TO', true); ?></b>
                                                    </div>
                                                    <div class="col-sm-6 col-xs-12"> 
                                                        <label class="lable-acc lable-acc-month">End Month</label>
                                                        <div class="qualification-select">
                                                        <span>
                                                        <?php
                                                        global $monthName;

                                                        echo $this->Form->input('Experience.0.to_month', array('id' => 'toMonth0', 'type' => 'select', 'options' => $monthName, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => __d('user', 'Select Month', true), 'onchange' => 'removeError(0)'))
                                                        ?>
                                                        </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xs-12 loweryeardiv">
                                                        <label class="lable-acc lable-acc-month">End Year</label>
                                                        <div class="qualification-select">
                                                        <span>
                                                        <?php //echo $this->Form->input('Experience.0.to_year', array('type' => 'select', 'label' => false, 'div' => false, 'class' => "loweryear required", 'empty' => 'Select Year'))  ?>
                                                        <?php echo $this->Form->input('Experience.0.to_year', array('data-value' => '0', 'type' => 'select', 'label' => false, 'div' => false, 'class' => "form-control loweryear0 required", 'empty' => __d('user', 'Select Year', true), 'onchange' => 'removeError(0)')) ?>
                                                        </span>
                                                        </div>
                                                        </div>
                                                    <div id="durError0" class="col-sm-12 col-xs-12" style="color:#f3665c;">    

                                                    </div>

                                                </div>
                                            </span>
                                        </div>

                                        <div class="form_list_education">
                                            <label class="lable-acc"><?php echo __d('user', 'Job Profile', true); ?></label>
                                            <div class="form_input_education">                                     
                                                <?php echo $this->Form->input('Experience.0.job_profile', array('type' => 'textarea', 'label' => false, 'div' => false, 'class' => "form-control", 'placeholder' => __d('user', 'Job Profile', true))) ?>

                                            </div>
                                        </div>
                                    </div>    
                                <?php } ?>



                                <div class="clear"></div><br/>

                            </div>
                            <div class="experienceElement">
                                <div class="wewain-add">
                                    <div id="loader1" class="loader" style="display:none; left: 50%;   position:absolute"><?php echo $this->Html->image("loading.gif"); ?></div>
                                    <?php echo $this->Html->link(__d('user', '+ Add More', true), 'javascript:void(0);', array('id' => 'addexperience', 'class' => 'add_btn', 'escape' => false, 'rel' => 'nofollow')); ?>
                                </div>
                            </div>




                            <div class="form_lst sssss">
                                <span class="rltv">
                                    <div class="pro_row_left">
                                        <?php //echo $this->Form->hidden('User.old_cv');   ?>
                                        <?php
                                        if (isset($expDetails) && !empty($expDetails)) {
                                            echo $this->Form->input('Candidate.cc', array('type' => 'hidden', 'id' => 'experiencecounter', 'value' => (count($expDetails) - 1)));
                                        } else {
                                            echo $this->Form->input('Candidate.cc', array('type' => 'hidden', 'id' => 'experiencecounter', 'value' => '0'));
                                        }
                                        ?>
                                        <?php echo $this->Form->submit(__d('user', 'Update', true), array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
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

