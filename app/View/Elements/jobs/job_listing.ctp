<script>
    function getMaxExpList(id) {

        var opt = '';
        var i = '';
        if (id !== '') {
            for (i = id; i <= 30; i++)
            {
                opt += '<option value="' + i + '">' + i + '</option>';
            }
            $('#maxexp').html(opt);
        } else {
            opt += '<option value="">Max Exp(Year)</option>';
            $('#maxexp').html(opt);
        }
    }

</script>
<script>
    $(document).ready(function () {

        $("#JobCreated").change(function () {
            $.ajax({
                type: 'POST',
                url: "<?php echo HTTP_PATH; ?>/jobs/jobListing/<?php echo $slug ?>",
                                cache: false,
                                data: $('#searchJob2').serialize(),
                                beforeSend: function () {
                                    $("#loaderID").show();
                                },
                                complete: function () {
                                    $("#loaderID").hide();
                                },
                                success: function (result) {
                                    $("#loaderID").hide();
                                    if (result) {
                                        $("#listID").html(result);

                                    }
                                }
                            });
                        });

                    });


<?php
$searchkeyArrCount = 0;
if (isset($searchkey) && !empty($searchkey)) {
    $searchkeyArr = explode("-", $searchkey);

    foreach ($searchkeyArr as $key) {
        $skillKeyArr = classregistry::init('Skill')->field('type', array('Skill.id' => $key));
        //pr($skillKeyArr);
        if ($skillKeyArr == 'Skill') {
            $keyCount[] = $skillKeyArr;
        }
    }

    if (!empty($keyCount)) {
        $searchkeyArrCount = count($keyCount);
    } else {
        $searchkeyArrCount = 0;
    }

    if ($searchkeyArrCount) {
        ?>
                            var skill_checked = '<?php echo $searchkeyArrCount; ?>';
                            var skillStr = '<?php echo $searchkey; ?>';
                            var skill_arr = skillStr.split("-");
                            $('#JobTotalSkill').val(skill_checked + ' Skills selected');
                            if (skill_arr) {
                                skill_arr.forEach(markChecked);
                            }
        <?php
    }
} else {
    $searchkey = '';
}
?>
                    function markChecked(element, index, array) {
                        $('#JobSkill' + element).attr('checked', 'checked');
                    }
                    // For Skill
                    $(document).mouseup(function (e)
                    {
                        var containerskill = $("#skill-dropdown");

                        if (!containerskill.is(e.target) // if the target of the click isn't the container...
                                && containerskill.has(e.target).length === 0) // ... nor a descendant of the container
                        {
                            containerskill.hide();
                        }
                    });
                    $('.test-skill').click(function () {
                        var skill_checked = $(".test-skill input:checked").length;
                        if (skill_checked == 0) {
                            $('#JobTotalSkill').val('All Skill');
                        } else {
                            $('#JobTotalSkill').val(skill_checked + ' Skills selected');
                        }
                    });



<?php
$searchDeskeyArrCount = 0;
if (isset($searchkey) && !empty($searchkey)) {
    $searchDeskeyArr = explode("-", $searchkey);

    foreach ($searchDeskeyArr as $key) {
        $desKeyArr = classregistry::init('Skill')->field('type', array('Skill.id' => $key));
        //pr($skillKeyArr);
        if ($desKeyArr == 'Designation') {
            $desKeyCount[] = $desKeyArr;
        }
    }

    if (!empty($desKeyCount)) {
        $searchDeskeyArrCount = count($desKeyCount);
    } else {
        $searchDeskeyArrCount = 0;
    }

    if ($searchDeskeyArrCount) {
        ?>
                            var designation_checked = '<?php echo $searchDeskeyArrCount; ?>';
                            var designationStr = '<?php echo $searchkey; ?>';
                            var designation_arr = designationStr.split("-");
                            $('#JobTotalDesignation').val(designation_checked + ' Designations selected');
                            if (designation_arr) {
                                designation_arr.forEach(markDesChecked);
                            }
        <?php
    }
} else {
    $searchkey = '';
}
?>

                    function markDesChecked(element, index, array) {
                        $('#JobDesignation' + element).attr('checked', 'checked');
                    }

                    // For Desgination
                    $(document).mouseup(function (e)
                    {
                        var containerdesignation = $("#designation-dropdown");

                        if (!containerdesignation.is(e.target) // if the target of the click isn't the container...
                                && containerdesignation.has(e.target).length === 0) // ... nor a descendant of the container
                        {
                            containerdesignation.hide();
                        }
                    });
                    $('.test-designation').click(function () {
                        var designation_checked = $(".test-designation input:checked").length;
                        if (designation_checked == 0) {
                            $('#JobTotalDesignation').val('All Designations');
                        } else {
                            $('#JobTotalDesignation').val(designation_checked + ' Designations selected');
                        }
                    });


<?php
$searchLockeyArrCount = 0;
if (isset($location) && !empty($location)) {

    // echo"hii";  pr($location);

    $searchLockeyArr = explode("-", $location);
    $searchLockeyArrCount = count($searchLockeyArr);
    if ($searchLockeyArrCount) {
        ?>
                            var location_checked = '<?php echo $searchLockeyArrCount; ?>';
                            var locationStr = '<?php echo $location; ?>';
                            var location_arr = locationStr.split("-");
                            $('#JobTotalLocation').val(location_checked + ' Locations selected');
                            if (location_arr) {
                                location_arr.forEach(markLocChecked);
                            }
        <?php
    }
} else {
    //echo"hii1";  pr($location);

    $location = '';
}
?>

                    function markLocChecked(element, index, array) {
                        $('#JobLocation' + element).attr('checked', 'checked');
                    }
                    // For Location
                    $(document).mouseup(function (e)
                    {
                        var containerlocation = $("#location-dropdown");

                        if (!containerlocation.is(e.target) // if the target of the click isn't the container...
                                && containerlocation.has(e.target).length === 0) // ... nor a descendant of the container
                        {
                            containerlocation.hide();
                        }
                    });
                    $('.test-location').click(function () {

                        var location_checked = $(".test-location input:checked").length;

                        if (location_checked == 0) {
                            $('#JobTotalLocation').val('All Locations');

                        } else {
                            $('#JobTotalLocation').val(location_checked + ' Locations selected');
                        }
                    });

                    //sub categories   

<?php
$searchSubcatkeyArrCount = 0;
if (isset($subcategory_id) && !empty($subcategory_id)) {
    $searchSubcatkeyArr = explode("-", $subcategory_id);
    $searchSubcatkeyArrCount = count($searchSubcatkeyArr);
    if ($searchSubcatkeyArrCount) {
        ?>
                            var subcat_checked = '<?php echo $searchSubcatkeyArrCount; ?>';
                            var subcatStr = '<?php echo $subcategories; ?>';
                            //var designation_arr = designationStr.split("-");
                            $('#JobTotalSubcategories').val(subcat_checked + ' Subcategory selected');
                            if (subcatStr) {
                                subcatStr.forEach(markSubcatChecked);
                            }
        <?php
    }
} else {
    $subcategory_id = '';
}
?>

                    function markSubcatChecked(element, index, array) {
                        $('#JobSubcategoryId' + element).attr('checked', 'checked');
                    }
                    // For subcategory
                    $(document).mouseup(function (e)
                    {
                        var containerSubcat = $("#subcat-dropdown");

                        if (!containerSubcat.is(e.target) // if the target of the click isn't the container...
                                && containerSubcat.has(e.target).length === 0) // ... nor a descendant of the container
                        {
                            containerSubcat.hide();
                        }
                    });
                    $('.test-subcat').click(function () {

                        var subcat_checked = $(".test-subcat input:checked").length;

                        if (subcat_checked == 0) {
                            $('#JobTotalSubcategories').val('All Subcategories');

                        } else {
                            $('#JobTotalSubcategories').val(subcat_checked + ' Subcategories selected');
                        }
                    });


<?php
$searchWorkkeyArrCount = 0;
if (isset($worktype) && !empty($worktype)) {



    $searchWorkkeyArr = explode("-", $worktype);
    $searchWorkkeyArrCount = count($searchWorkkeyArr);
    if ($searchWorkkeyArrCount) {
        ?>
                            var worktype_checked = '<?php echo $searchWorkkeyArrCount; ?>';
                            var worktypeStr = '<?php echo $worktype; ?>';
                            var worktype_arr = worktypeStr.split("-");
                            $('#JobTotalWorkType').val(worktype_checked + ' <?php echo __d('user', 'Worktype selected', true) ?>');
                            if (worktype_arr) {
                                worktype_arr.forEach(markWorkChecked);
                            }
        <?php
    }
} else {

    $worktype = '';
}
?>

                    function markWorkChecked(element, index, array) {
                        $('#JobTotalWorkType' + element).attr('checked', 'checked');
                    }
                    // For Location
                    $(document).mouseup(function (e)
                    {
                        var containerworktype = $("#worktype-dropdown");

                        if (!containerworktype.is(e.target) // if the target of the click isn't the container...
                                && containerworktype.has(e.target).length === 0) // ... nor a descendant of the container
                        {
                            containerworktype.hide();
                        }
                    });
                    $('.test-worktype').click(function () {

                        var worktype_checked = $(".test-worktype input:checked").length;

                        if (worktype_checked == 0) {
                            $('#JobTotalWorkType').val('<?php echo __d('user', 'All Worktype', true) ?>');

                        } else {
                            $('#JobTotalWorkType').val(worktype_checked + ' <?php echo __d('user', 'Worktype selected', true) ?>');
                        }
                    });


</script>
<style>
    .subcate{ display: block!important;}
</style>
<div class="row">

    <div class="col-lg-12 col-sm-12">
        <div class="iner_form_bg_box iner_form_new_box">
            <div class="top_page_name_box">
                <div class="page_name_boox">
                    <?php
                    if (isset($categoryName) && !empty($categoryName)) {
                        echo $categoryName . ' JOBS';
                    }
                    ?>
                </div>
                <div>
                    <span><?php echo $this->Html->link('Home', array('controller' => 'homes', 'action' => '', '')); ?> > <?php echo $this->Html->link($categoryName, 'javascript:void(0)'); ?></span> 
                </div>


            </div>
            <div class="row_hr">
                <?php echo $this->Form->create("Job", array('url' => 'jobListing/' . $slug, 'enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'searchJob2', 'name' => 'searchJob', 'autocomplete' => 'off')); ?>                        

                <?php
                if (isset($searchkey) && !empty($searchkey)) {
                    echo $this->Form->input('searchkey', array('type' => 'hidden', 'value' => $searchkey));
                }
                if (isset($order) && $order != '') {
                    //echo $this->Form->text('Job.created', array('type' => 'textbox', 'value' => $order));
                }
                ?>
                <div class="cols_3" id="sidebar">
                    <div class="job_searc_white_box job_searc_white_box_no">
                        <div class="job_search_filter_title">Filter Search</div>
                        <div class="clear"></div>
                        <div class="left_sec_form_1">

                            <div class="div_full_dfaya">

                                <div class="data_row_fulk">
                                    <div class="full_cols">
                                        <div class="key_word_txet">Keywords</div>
                                        <div class="clear"></div>
                                        <?php echo $this->Form->text('Job.keyword', array('placeholder' => 'Enter Keyword(s)', 'label' => '', 'div' => false, 'class' => "search_input")) ?>
                                    </div>
                                </div>
                                <?php if (isset($subcategories) && !empty($subcategories)) { ?>
                                    <div class="full_cols">
                                        <div class="key_word_txet">Sub Categories</div>
                                        <div class="select_drop">
                                            <div style="float:left;" id="subcat-filter">
                                                <?php
                                                if (!isset($subcategories) || empty($subcategories)) {

                                                    $subcategories = NULL;
                                                }
                                                ?>
                                                <?php echo $this->Form->text('Job.total_subcategories', array('maxlength' => 50, 'label' => '', 'size' => 20, 'div' => false, 'class' => 'filtertext', 'onClick' => "$('#subcat-dropdown').toggle();", "autocomplete" => "off", 'default' => 'All Subcategories', 'readonly' => 'readonly')); ?>
                                                <div id="subcat-dropdown" style="display:block!important;" class="designer-drop subcate">

                                                    <div class="morescroll">
                                                        <?php echo $this->Form->input('Job.subcategory_id', array('class' => 'des_box_cont test-subcat', 'multiple' => 'checkbox', 'options' => $subcategories, 'legend' => false, 'div' => false, 'label' => false)); ?>
                                                    </div>
                                                    <div class="quickFindApplyContainer">
                                                        <span id="multiSelectFooter2Brand" class="multiSelectFooter2 applyButton" style="display: none;">
                                                            <?php echo $this->Ajax->submit("Apply", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'jobListing', $slug), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => 'go_bin', 'after' => "$('#subcat-dropdown').hide();")); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>



                                <div class="full_cols">
                                    <div class="key_word_txet">Skills</div>
                                    <div class="select_drop">
                                        <div style="float:left;" id="skill-filter">
                                            <?php
                                            if (!isset($skillList) || empty($skillList)) {
                                                $skillList = NULL;
                                            }
                                            ?>
                                            <?php echo $this->Form->text('Job.total_skill', array('maxlength' => 50, 'label' => '', 'size' => 20, 'div' => false, 'class' => 'filtertext', 'onClick' => "$('#skill-dropdown').toggle();", "autocomplete" => "off", 'default' => 'All Skill', 'readonly' => 'readonly')); ?>
                                            <div id="skill-dropdown" style="display:none;" class="designer-drop">

                                                <div class="morescroll">
                                                    <?php echo $this->Form->input('Job.skill', array('class' => 'des_box_cont test-skill', 'multiple' => 'checkbox', 'options' => $skillList, 'legend' => false, 'div' => false, 'label' => false)); ?>
                                                </div>
                                                <div class="quickFindApplyContainer">
                                                    <span id="multiSelectFooter2Brand" class="multiSelectFooter2 applyButton" style="display: none;">
                                                        <?php echo $this->Ajax->submit("Apply", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'jobListing', $slug), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => 'go_bin', 'after' => "$('#skill-dropdown').hide();")); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="full_cols">
                                    <div class="key_word_txet">Designation</div>
                                    <div class="select_drop">
                                        <div style="float:left;" id="designation-filter">
                                            <?php
                                            if (!isset($designationlList) || empty($designationlList)) {
                                                $designationlList = NULL;
                                            }
                                            ?>
                                            <?php echo $this->Form->text('Job.total_designation', array('maxlength' => 50, 'label' => '', 'size' => 20, 'div' => false, 'class' => 'filtertext', 'onClick' => "$('#designation-dropdown').toggle();", "autocomplete" => "off", 'default' => 'All Designations', 'readonly' => 'readonly')); ?>
                                            <div id="designation-dropdown" style="display:none;" class="designer-drop">

                                                <div class="morescroll">
                                                    <?php echo $this->Form->input('Job.designation', array('class' => 'des_box_cont test-designation', 'multiple' => 'checkbox', 'options' => $designationlList, 'legend' => false, 'div' => false, 'label' => false)); ?>
                                                </div>
                                                <div class="quickFindApplyContainer">
                                                    <span id="multiSelectFooter2Brand" class="multiSelectFooter2 applyButton" style="display: none;">
                                                        <?php echo $this->Ajax->submit("Apply", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'jobListing', $slug), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => 'go_bin', 'after' => "$('#designation-dropdown').hide();")); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="full_cols">
                                    <div class="key_word_txet">Location</div>
                                    <div class="select_drop">
                                        <div id="location-filter">
                                            <?php
                                            if (!isset($locationlList) || empty($locationlList)) {
                                                $locationlList = NULL;
                                            }
                                            ?>
                                            <?php echo $this->Form->text('Job.total_location', array('maxlength' => 50, 'label' => '', 'size' => 20, 'div' => false, 'class' => 'filtertext search_input', 'onClick' => "$('#location-dropdown').toggle();", "autocomplete" => "off", 'default' => 'All Locations', 'readonly' => 'readonly')); ?>
                                            <div id="location-dropdown" style="display:none;" class="designer-drop">

                                                <div class="morescroll">
                                                    <?php echo $this->Form->input('Job.location', array('class' => 'des_box_cont test-location', 'multiple' => 'checkbox', 'options' => $locationlList, 'legend' => false, 'div' => false, 'label' => false)); ?>
                                                </div>
                                                <div class="quickFindApplyContainer">
                                                    <span id="multiSelectFooter2Brand" class="multiSelectFooter2 applyButton" style="display: none;">
                                                        <?php echo $this->Ajax->submit("Apply", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'jobListing', $slug), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => 'go_bin', 'after' => "$('#location-dropdown').hide();")); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>



                            <div class="div_full_dfaya">
                                <div class="data_row_fulk">
                                    <div class="full_cols">
                                        <div class="key_word_txet">Salary</div>
                                        <div class="sel_left">
                                            <div class="select_drop">
                                                <span>
                                                    <?php
                                                    global $minSalary;
                                                    echo $this->Form->input('Job.min_salary', array('type' => 'select', 'options' => $minSalary, 'label' => false, 'class' => "search_input_small", 'empty' => 'Min', 'onChange' => 'getMaxSalaryList(this.value);'));
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                        <span class="to_textsr">to</span>
                                        <div class="sel_left sel_right">
                                            <div class="select_drop">
                                                <span>
                                                    <?php
                                                    $maxSalary = array();
                                                    echo $this->Form->input('Job.max_salary', array('type' => 'select', 'options' => $maxSalary, 'label' => false, 'div' => false, 'class' => "search_input_small", 'empty' => 'Max'));
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="div_full_dfaya">
                                        <div class="data_row_fulk">
                                            <div class="full_cols">
                                                <div class="key_word_txet">Experience</div>
                                                <div class="sel_left">
                                                    <div class="select_drop">
                                                        <span>
                                                            <?php
                                                            if (isset($min_exp)) {
                                                                $min_exp = array();
                                                                for ($year = 0; $year <= 30; $year++) {
                                                                    $min_exp[$year] = $year;
                                                                }
                                                                echo $this->Form->input('Job.min_exp', array('id' => 'minexp', 'type' => 'select', 'options' => $min_exp, 'label' => false, 'div' => false, 'class' => "search_input_small", 'empty' => 'Min (Years)', 'onChange' => 'getMaxExpList(this.value);'));
                                                            } else {
                                                                for ($year = 0; $year <= 30; $year++) {
                                                                    $min_exp[$year] = $year;
                                                                }
                                                                echo $this->Form->input('Job.min_exp', array('id' => 'minexp', 'type' => 'select', 'options' => $min_exp, 'label' => false, 'div' => false, 'class' => "search_input_small", 'empty' => 'Min (Years)', 'onChange' => 'getMaxExpList(this.value);'));
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="to_textsr">to</span>
                                                <div class="sel_left sel_right">
                                                    <div class="select_drop">
                                                        <span>
                                                            <?php
                                                            if (isset($max_exp)) {
                                                                $expMax = $max_exp;

                                                                for ($i = $max_exp; $expMax <= 30; $i++) {
                                                                    $max_expi[$i] = $expMax;
                                                                    $expMax = $expMax + 1;
                                                                }

                                                                echo $this->Form->input('Job.max_exp', array('id' => 'maxexp', 'type' => 'select', 'options' => $max_expi, 'label' => false, 'div' => false, 'class' => "search_input_small", 'empty' => 'Max (Years)'));
                                                            } else {

                                                                $max_exp = array();
                                                                echo $this->Form->input('Job.max_exp', array('id' => 'maxexp', 'type' => 'select', 'options' => $max_exp, 'label' => false, 'div' => false, 'class' => "search_input_small", 'empty' => 'Max (Years)'));
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="full_cols">
                                <div class="key_word_txet">Work Type</div>
                                <div class="select_drop">
                                    <div id="worktype-filter">
                                        <?php
                                        global $worktype;
                                        if (!isset($worktype) || empty($worktype)) {
                                            $worktype = NULL;
                                        }
                                        ?>
                                        <?php echo $this->Form->text('Job.total_work_type', array('maxlength' => 50, 'label' => '', 'size' => 20, 'div' => false, 'class' => 'filtertext search_input', 'onClick' => "$('#worktype-dropdown').toggle();", "autocomplete" => "off", 'default' => __d('user', 'All Worktype', true), 'readonly' => 'readonly')); ?>
                                        <div id="worktype-dropdown" style="display:none;" class="designer-drop">

                                            <div class="morescroll">
                                                <?php echo $this->Form->input('Job.work_type', array('class' => 'des_box_cont test-worktype', 'multiple' => 'checkbox', 'options' => $worktype, 'legend' => false, 'div' => false, 'label' => false)); ?>
                                            </div>
                                            <div class="quickFindApplyContainer">
                                                <span id="multiSelectFooter2Brand" class="multiSelectFooter2 applyButton" style="display: none;">
                                                    <?php echo $this->Ajax->submit("Apply", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'listing'), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => 'go_bin', 'after' => "$('#worktype-dropdown').hide();")); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                        </div>




                        <div class="full_colsdr">
                            <?php echo $this->Ajax->submit("Search", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'jobListing', $slug), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => '')); ?>
                            <?php echo $this->Html->link('Reset', array('controller' => 'jobs', 'action' => 'jobListing', $slug), array('class' => '')); ?>
                        </div>

                    </div>
                </div>


                <!-- Note: form section ends in elements->jobs->filter_jobs.ctp -->

                <div class="cols_7">
                    <div id="listID" class="efe">
                        <?php echo $this->element('jobs/filter_job'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php echo $this->Html->script('front/stickySidebar.js'); ?>
<script>
    $(document).ready(function () {
        $('#sidebar').stickySidebar({
            sidebarTopMargin: 20,
            footerThreshold: 100
        });
    });
</script>