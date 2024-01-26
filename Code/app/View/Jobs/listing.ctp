<?php
echo $this->Html->script('jquery/ui/jquery.ui.core.js');
echo $this->Html->script('jquery/ui/jquery.ui.widget.js');
echo $this->Html->script('jquery/ui/jquery.ui.position.js');
echo $this->Html->script('jquery/ui/jquery.ui.menu.js');
echo $this->Html->script('jquery/ui/jquery.ui.autocomplete.js');
echo $this->Html->css('front/themes/base/jquery.ui.all.css');
?>
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
</script>
<script type="text/javascript">

    $(function () {
        var availableTags = [<?php echo ClassRegistry::init('Skill')->searchKeyword(); ?>];
        var availableDesignation = [<?php echo ClassRegistry::init('Skill')->searchKDesignation(); ?>];

        $(function () {

            function split(val) {
                return val.split(/,\s*/);
            }
            function extractLast(term) {
                return split(term).pop();
            }

            $("#JobSkill")
                    // don't navigate away from the field on tab when selecting an item
                    .bind("keydown", function (event) {
                        if (event.keyCode === $.ui.keyCode.TAB &&
                                $(this).autocomplete("instance").menu.active) {
                            event.preventDefault();
                        }
                    })
                    .autocomplete({
                        minLength: 1,
                        source: function (request, response) {
                            // delegate back to autocomplete, but extract the last term
                            response($.ui.autocomplete.filter(
                                    availableTags, extractLast(request.term)));
                        },
                        focus: function () {
                            // prevent value inserted on focus
                            return false;
                        },
                        select: function (event, ui) {
                            var terms = split(this.value);
                            // remove the current input
                            terms.pop();
                            // add the selected item
                            terms.push(ui.item.label);

                            //$('#JobTotalSkillSearch').val(ui.item.value);
                            // add placeholder to get the comma-and-space at the end
                            terms.push("");
                            this.value = terms.join(",");
                            return false;
                        }
                    });
            $("#JobDesignation")
                    // don't navigate away from the field on tab when selecting an item
                    .bind("keydown", function (event) {
                        if (event.keyCode === $.ui.keyCode.TAB &&
                                $(this).autocomplete("instance").menu.active) {
                            event.preventDefault();
                        }
                    })
                    .autocomplete({
                        minLength: 1,
                        source: function (request, response) {
                            // delegate back to autocomplete, but extract the last term
                            response($.ui.autocomplete.filter(
                                    availableDesignation, extractLast(request.term)));
                        },
                        focus: function () {
                            // prevent value inserted on focus
                            return false;
                        },
                        select: function (event, ui) {
                            var terms = split(this.value);
                            // remove the current input
                            terms.pop();
                            // add the selected item
                            terms.push(ui.item.label);

                            //$('#JobTotalSkillSearch').val(ui.item.value);
                            // add placeholder to get the comma-and-space at the end
                            terms.push("");
                            this.value = terms.join(",");
                            return false;
                        }
                    });
        });
    });



</script>
<?php
//for error issue Document Expired after back button
header("Cache-Control: max-age=300, must-revalidate");
$_SESSION['job_apply_return_url'] = $this->params->url;
?>

<script>
    function updateCity(stateId) {
        $('#loaderID').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getStateCity/Job/" + stateId,
            cache: false,
            success: function (result) {
                $("#updateCity").html(result);
                $('#loaderID').hide();
            }
        });
    }

      function updateSubCat(catId) {
//      alert(catId);
     if(catId){
        $('#loaderID').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getSubCategory/" + catId,
            cache: false,
            success: function (result) {
                $("#JobSubcategoryId").html(result);
                $('#loaderID').hide();
            }
        });
    }
    }

    function getMaxiExpList(id) {
        var opt = '';
        var i = '';
        if (id !== '') {
            for (i = id; i <= 30; i++)
            {
                opt += '<option value="' + i + '">' + i + '</option>';
            }
            $('#JobMaxExp').html(opt);
        } else {
            opt += '<option value=""><?php echo __d('user', 'Max Exp(Year)', true); ?></option>';
            $('#JobMaxExp').html(opt);
        }
    }

    function getMaxSalaryList(id) {
        var opt = ''
        id = id * 1;
        $("#JobMinSalary option").each(function ()
        {
            var ff = $(this).val() * 1;
            if (ff >= id) {
                opt += '<option value="' + ff + '"><?php echo CURRENCY; ?> ' + ff + 'K</option>';
            }
        });
        $('#JobMaxSalary').html(opt);
    }
</script>
<script type="text/javascript">

    jQuery(document).ready(function ($) {

<?php
$searchDeskeyArrCount = 0;
if (isset($searchkey) && !empty($searchkey)) {
    $searchDeskeyArr = explode("-", $searchkey);
    //$desKeyCount = '';
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
                $('#JobTotalDesignation').val('<?php echo __d('user', 'All Designations', true) ?>');
            } else {
                $('#JobTotalDesignation').val(designation_checked + ' Designations selected');
            }
        });

        // For Desgination
        $(document).mouseup(function (e)
        {
            var containerdesignation = $("#worktype-dropdown");

            if (!containerdesignation.is(e.target) // if the target of the click isn't the container...
                    && containerdesignation.has(e.target).length === 0) // ... nor a descendant of the container
            {
                containerdesignation.hide();
            }
        });
        $('.test-worktype').click(function () {
            var wtype_checked = $(".test-worktype input:checked").length;
            if (wtype_checked == 0) {
                $('#JobTotalWorkType').val('<?php echo __d('user', 'All Worktype', true) ?>');
            } else {
                $('#JobTotalWorkType').val(wtype_checked + ' <?php echo __d('user', 'Worktype selected', true) ?>');
            }
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
                $('#JobTotalSkill').val(skill_checked + ' <?php echo __d('user', 'Skills selected', true); ?>');
            }
        });


<?php
$searchLockeyArrCount = 0;
if (isset($location) && !empty($location)) {

    //pr($location);

    $searchLockeyArr = explode("-", $location);
    $searchLockeyArrCount = count($searchLockeyArr);
    if ($searchLockeyArrCount) {
        ?>
                var location_checked = '<?php echo $searchLockeyArrCount; ?>';
                var locationStr = '<?php echo $location; ?>';
                var location_arr = locationStr.split("-");
                $('#JobTotalLocation').val(location_checked + ' <?php echo __d('user', 'Locations selected', true); ?>');
                if (location_arr) {
                    location_arr.forEach(markLocChecked);
                }
        <?php
    }
} else {

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
                $('#JobTotalLocation').val(location_checked + ' <?php echo __d('user', 'Locations selected', true); ?>');
            }
        });


    });

</script>

<div class=""></div>
<div class="clear"></div>
<section class="slider_abouts">

      
<div class="search_bar_listing">
    <!--------header search starts------------->
 
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="search-bar-inner text-center">
                        <?php echo $this->Form->create("Job", array('url' => 'filterSection', 'enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'searchJob1', 'name' => 'searchJob', 'autocomplete' => 'off')); ?>  
                        <?php echo $this->Form->hidden('Job.lat'); ?>   
                        <?php echo $this->Form->hidden('Job.long'); ?>
                        <div class="searh_new_1">
                           <div class="form-row">
                               <div class="col-md-3">
                                
                                    <?php echo $this->Form->text('Job.keyword', array('maxlength' => '255', 'label' => '', 'autocomplete' => 'off', 'data-suggesstion' => 'jobkeyword-box', 'data-search' => 'Search', 'label' => '', 'div' => false, 'class' => "keyword-box form-control", 'placeholder' => __d('user', 'Search by Keyword', true))); ?>  
                                    <div id="jobkeyword-box" class="common-serach-box" style="display: none"></div>
                                    <?php //echo $this->Form->select('Job.searchkey', $skillList, array('multiple' => true, 'data-placeholder' => 'Choose skills, designations', 'class' => "chosen-select", 'autocomplete' => 'off')); ?>
                                    <?php //echo $this->Form->input('hiddenId', array('type' => 'hidden')); ?>
                                 
                               
                               </div>
                               <div class="col-md-2">
                               <div class="select-categorys">
                                   <span>
                                   <?php echo $this->Form->input('Job.category_id', array('type' => 'select', 'options' => $categories, 'label' => false, 'div' => false, 'class' => "placeholder form-control", 'empty' => __d('user', 'Any Category', true),'onChange' => 'updateSubCat(this.value)'));  ?>
                                   </span>
                               </div>
                               </div>
                               <div class="col-md-3">
                                   <div class="select-categorys">
                                   <span>
                                   <?php echo $this->Form->input('Job.subcategory_id', array('type' => 'select', 'options' => $subcategories, 'label' => false, 'div' => false,'empty' => __d('user', 'Select Job Sub Category', true), 'class' => "placeholder form-control")); //'empty' => __d('user', 'Select Job Sub Category', true) ?>
                                   </span>
                                   </div>
                               </div>
                               <div class="col-md-2">
                         
                                            <?php
                                            $locationid = "";
                                            if (isset($_SESSION['locationid']) && $_SESSION['locationid'] > 0) {
                                                $locationid = $_SESSION['locationid'];
                                            }
                                            // echo $this->Form->input('Job.location', array('type' => 'select', 'options' => $locationlList, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any Location','default'=>$locationid)); 
                                            ?>
                                            <?php echo $this->Form->input('Job.location', array('type' => 'text', 'label' => false, 'div' => false, 'class' => "form-control", 'placeholder' => __d('user', 'Enter Location', true), 'id' => 'job_city')); ?>
                                      
                               </div>
                               <div class="col-md-2">
                                   <div class="sr_butn">
                                    <div id="loaderID" style="display:none;position:absolute;"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
                                    <?php //echo $this->Form->submit('FIND JOBS', array('div' => false, 'label' => false, 'class' => 'find_tab_bt more_opt change_neww'));     ?>
                                    <?php echo $this->Ajax->submit(__d('user', 'Find Jobs', true), array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'listing'), 'update' => 'filterSection', 'indicator' => 'loaderID', 'class' => 'currant-upplan pdf-btn')); ?>
                                   
                                </div>
                               </div>
                         
                           </div>


                        </div>
                         
                   




                        <?php echo $this->Form->input('Job.created', array('type' => 'hidden', 'value' => '')); ?>

                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>  
    <!------------------------------filter starts left side------------------------------------------>
    
    <section class="jovb_list-overfellow" id="filterSection">

        <?php echo $this->element('jobs/listing'); ?>

    </section>

   
<script type="text/javascript">
    window.onload = function () {
        initialize();
    };
</script> 