<?php
echo $this->Html->script('jquery/ui/jquery.ui.core.js');
echo $this->Html->script('jquery/ui/jquery.ui.widget.js');
echo $this->Html->script('jquery/ui/jquery.ui.position.js');
echo $this->Html->script('jquery/ui/jquery.ui.menu.js');
echo $this->Html->script('jquery/ui/jquery.ui.autocomplete.js');
echo $this->Html->css('front/themes/base/jquery.ui.all.css');
?>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<script>
    $(function () {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
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
        $('#loaderID').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/getSubCategory/" + catId,
            cache: false,
            success: function (result) {
                $("#subcat").html(result);
                $('#loaderID').hide();
            }
        });
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
            opt += '<option value="">Max Exp(Year)</option>';
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
                opt += '<option value="' + ff + '"><?php echo CURRENCY; ?>' + ff + 'K</option>';
            }
        });
        $('#JobMaxSalary').html(opt);
    }

</script>
<script type="text/javascript">

    jQuery(document).ready(function ($) {

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



        // For Location
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








    });

//
// function updateResultCat(id) {
//        //alert(id);        
//        $("#subc" + id).removeClass('displayBl');
//        $('#divmain' + id).toggleClass('rotate90');
//
//        if ($('#main' + id).is(':checked')) {
//            $("#subc" + id).show();
//        } else {
//            $("#subc" + id).hide();
//            $(".mainc" + id).attr('checked', false);
//        }
//        ajaxCall();
//    }


    function updateResult() {
        ajaxCall();
    }

    function updateResultCat(id) {
        //alert(id);        
        $("#subc" + id).removeClass('displayBl');
        $('#divmain' + id).toggleClass('rotate90');

        if ($('#main' + id).is(':checked')) {
            $("#subc" + id).show();
        } else {
            $("#subc" + id).hide();
            $(".mainc" + id).attr('checked', false);
        }
        ajaxCall();
    }

    function ajaxCall() {
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/joblisting",
            cache: false,
            data: $('#searchJob2').serialize(),
            beforeSend: function () {
                $("#loaderID").show();
            },
            complete: function () {
                $("#loaderID").hide();
            },
            success: function (result) {
                $("#listID").html(result);
            }
        });
    }
</script>


<div class=""></div>
<div class="clear"></div>
<div class="iner_pages_formate_box">
    <!--------header search starts------------->
    <div class="deil_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="search_bar">
                        <?php echo $this->Form->create("Job", array('url' => 'filterJob/' . $slug, 'enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'searchJob1', 'name' => 'searchJob', 'autocomplete' => 'off')); ?>  

                        <?php
                        if (isset($searchkey) && !empty($searchkey)) {

                            echo $this->Form->input('searchkey', array('type' => 'hidden', 'value' => $searchkey, 'class' => 'keysearch'));
                        }
                        ?>

                        <div class="ser">
                            <label>Jobs Search for: </label>
                            <div class="sr_img">
                                <?php //echo $this->Form->text('Job.searchkey', array('maxlength' => '255', 'label' => '', 'div' => false, 'class' => "", 'placeholder' => __d('home', 'Skill, Designation ', true))); ?>  
                                <?php //echo $this->Form->input('hiddenId', array('type' => 'hidden')); ?>
                                <?php echo $this->Form->select('Job.searchkey', $skillDesList, array('multiple' => true, 'data-placeholder' => 'Choose skills, designations', 'class' => "chosen-select searchBox")); ?>
                                <div class="sre_icon"><i class="fa fa-search"></i></div>
                            </div>
                        </div>


                        <div class="ser">
                            <label>Location you want: </label>
                            <div class="sr_img">
                                <div class="sear_selcd">
                                    <span>
                                        <?php echo $this->Form->input('Job.location', array('type' => 'select', 'options' => $locationlList, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Any location')); ?>
                                    </span>
                                </div>
                            </div>
                        </div>



                        <div class="ser half_ser">
                            <label>You have experience: </label>
                            <div class="sr_img">
                                <div class="sear_selcd">
                                    <span>
                                        <?php
                                        if (isset($min_exp)) {
                                            $min_exp = array();
                                            for ($year = 0; $year <= 30; $year++) {
                                                $min_exp[$year] = $year;
                                            }
                                            echo $this->Form->input('Job.min_exp', array('type' => 'select', 'options' => $min_exp, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Min (Years)', 'onChange' => 'getMaxiExpList(this.value);'));
                                        } else {
                                            for ($year = 0; $year <= 30; $year++) {
                                                $min_exp[$year] = $year;
                                            }
                                            echo $this->Form->input('Job.min_exp', array('type' => 'select', 'options' => $min_exp, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Min (Years)', 'onChange' => 'getMaxiExpList(this.value);'));
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="sr_img">
                                <div class="sear_selcd">
                                    <span>
                                        <?php
                                        if (isset($max_exp)) {
                                            $expMax = $max_exp;

                                            for ($i = $max_exp; $expMax <= 30; $i++) {
                                                $max_expi[$i] = $expMax;
                                                $expMax = $expMax + 1;
                                            }

                                            echo $this->Form->input('Job.max_exp', array('type' => 'select', 'options' => $max_expi, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Max (Years)'));
                                        } else {

                                            $max_exp = array();
                                            echo $this->Form->input('Job.max_exp', array('type' => 'select', 'options' => $max_exp, 'label' => false, 'div' => false, 'class' => "search_input", 'empty' => 'Max (Years)'));
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="ser2">
                            <label>&nbsp;</label>
                            <div class="sr_img2">
                                <div id="loaderID" style="display:none;position:absolute;"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
                                <?php //echo $this->Form->submit('FIND JOBS', array('div' => false, 'label' => false, 'class' => 'find_tab_bt more_opt change_neww')); ?>
                                <?php echo $this->Ajax->submit("Find Jobs", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'filterJob', $slug), 'update' => 'filterJob', 'indicator' => 'loaderID', 'class' => '')); ?>
                            </div>
                        </div>


                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!------------------------------filter starts left side------------------------------------------>
    <div class="container" id="filterJob">
        <?php echo $this->element('jobs/job_listing'); ?>
    </div>
</div>