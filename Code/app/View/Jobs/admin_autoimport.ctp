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
<?php
$max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'max_size'));

?>

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
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("mypassword", function (input, element) {

            var reg = /[0-9]/; //at least one number
            var reg2 = /[a-z]/; //at least one small character
            var reg3 = /[A-Z]/; //at least one capital character
            //var reg4 = /[\W_]/; //at least one special character
            return this.optional(element) || reg.test(input) && reg2.test(input) && reg3.test(input);
        }, "<?php echo __d('common', 'Password Must include 1 lower and 1 upper case letter. Must include 1 number.'); ?>");
        $.validator.addMethod("contact", function (value, element) {
            return  this.optional(element) || (/^[0-9+]+$/.test(value));
        }, "Contact Number is not valid.");

        $.validator.addMethod("my_url", function (value, element) {
            var regexp = /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/
            return this.optional(element) || regexp.test(value);
        }, "Please enter a valid URL");

        $("#adminAddJob").validate();

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
            $('#JobMaxSalary').val(parseInt(maxSal));
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


    });

</script>

<script type="text/javascript">
   var previous;

    $(document).ready(function () {

        //dropdown select jquery
        $("#JobType").on('change', function () {

            $(this).find("option:selected").each(function () {
                // alert($(this).attr("value"));
                if ($(this).attr("value") == "bronze") {
                    $('highlight2').val('');
                    $('highlight3').val('');
                    $('#JobLogo').val('')
                    $('#highlight').hide();
                    $('#logoImage').hide();
                } else if ($(this).attr("value") == "silver") {
                    // $('#highlight2').val('');
                    // $('#highlight3').val('');
                    $('#JobLogo').val('');
                    $('#highlight').show();
                    $('#logoImage').show();
                } else if ($(this).attr("value") == "gold") {
                    // $('#highlight2').val('');
                    //   $('#highlight3').val('');
                    $('#JobLogo').val('');
                    $('#highlight').show();
                    $('#logoImage').show();
                } else {
                    $('#highlight').hide();
                    $('#logoImage').hide();
                }

            });
        }).change();

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
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-suitcase" ></i> Jobs » ', array('controller' => 'jobs', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-plus"></i> Add Job', 'javascript:void(0)', array('escape' => false));

//pr($_SESSION['copy_data']); die;
?>


<?php echo $this->Form->create('Feed', array('method' => 'POST', 'name' => 'autoimport', 'enctype' => 'multipart/form-data', 'id' => 'adminAddJob')); ?>
<?php echo $this->Form->hidden('Feed.feed_url'); ?>   
<section id="main-content" class="site-min-height">

    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>

            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Auto Job Import</h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
<!--                    <header class="panel-heading">
                        Job Detail:
                    </header>-->
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Import Name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Feed.name', array('class' => " form-control  required", 'autocomplete' => 'off')) ?>  
                                
                            </div>
                        </div>
                       

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Select Employer <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.user_id', array('type' => 'select', 'options' => $fullname, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Employer')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company Name <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.company_name', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Company Name')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company Logo <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.logo', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Company Logo')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company Description <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.brief_abtcomp', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Company Description')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company Location <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.address', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Company Location')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company Website <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.url', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Company Website')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Job Title <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.title', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select Job Title')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Job ID <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.job_id', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select Job ID')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Apply URL <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.applyurl', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Apply URL')) ?>


                            </div>
                        </div>
                           <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Job Expiration <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Feed.expire', array('maxlength' => '3', 'minlength' => '1', 'class' => "form-control  number")) ?>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Category <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.category_id', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control", 'empty' => 'Select Category')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Annual Salary <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.salary', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Annual Salary')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Job Description <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.description', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select Job Description')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Work Type <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.work_type', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Work Type')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Skills <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.skills', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Skills')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Designation <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.designation', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Designation')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Experience <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.exp', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Experience')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Job Location <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.job_city', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Job Location')) ?>


                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Expiry Date <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Feed.expirydate', array('type' => 'select', 'options' => $node_arr, 'label' => false, 'div' => false, 'class' => "form-control ", 'empty' => 'Select Expiry Date')) ?>


                            </div>
                        </div>
                         
<!--                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Apply URL <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php // echo $this->Form->text('Feed.applyurl', array( 'class' => "form-control")) ?>    
                            </div>
                        </div>-->
                     
<?php /* ?> 

                        <div class="form-group" style="display: none">
                            <label class="col-sm-2 col-sm-2 control-label">Job Type <div class="required_field">*</div></label>
                            <div class="col-sm-10" >

                                <?php
                                $option = array(
                                    'bronze' => 'bronze',
                                    'silver' => 'silver',
                                    'gold' => 'gold'
                                );
                                echo $this->Form->input('Job.type', array('type' => 'select', 'options' => $option, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select Job Type', 'default' => 'bronze'))
                                ?>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Job Title <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Job.title', array('class' => "required form-control keyword-box", 'autocomplete' => 'off', 'data-suggesstion' => 'jobkeyword-box', 'data-search' => 'Job')) ?>  
                                <div id="jobkeyword-box" class="register-suggestion common-serach-box" style="display: none"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Category <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Job.category_id', array('type' => 'select', 'options' => $categories, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select Job Type Category', 'onChange' => 'updateSubCat(this.value)')) ?>
                            </div>
                        </div>

                        

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Job Description <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php //echo $this->Form->textarea('Job.description', array('class' => "form-control required", 'id'=>''))  ?>
                                <?php
                                echo $this->Form->textarea('Job.description', array('label' => '', 'div' => false, 'id' => "description", 'placeholder' => 'Enter description'));
                                ?>
                            </div>
                        </div>

                        

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Work Type <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php
                                global $worktype;
                                echo $this->Form->input('Job.work_type', array('type' => 'select', 'options' => $worktype, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select Work Type'))
                                ?>
                            </div>
                        </div>

                      
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Experience (In Years) <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php
                                global $experienceArray;
                                echo $this->Form->input('Job.exp', array('type' => 'select', 'options' => $experienceArray, 'label' => false, 'class' => "form-control required"));
                                ?>        
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Annual Salary <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php
                                global $sallery;
                                echo $this->Form->input('Job.salary', array('type' => 'select', 'options' => $sallery, 'label' => false, 'class' => "form-control required", 'empty' => 'Select Salary'));
                                ?>        
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Skills <div class="required_field">*</div></label>
                            <div class="col-sm-10">

                                <?php echo $this->Form->select('Job.skill', $skillList, array('multiple' => true, 'data-placeholder' => 'Choose skills', 'class' => "chosen-select form-control required")); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Designation <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->select('Job.designation', $designationlList, array('data-placeholder' => 'Choose a designation', 'class' => "chosen-select form-control required")); ?>
                            </div>
                        </div> <?php */ ?> 
                                           
                        

                    </div>
                </section>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-sm-2 col-sm-2 control-label">&nbsp;</div>
                <div class="col-lg-9">

                    <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
                    <?php // echo $this->Html->link('Cancel', array('controller' => 'jobs', 'action' => 'index', ''), array('escape' => false, 'class' => 'btn btn-danger')); ?>
                </div></div></div>
    </section>
</section>

<?php echo $this->Form->end(); ?>

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
        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/categories/getSubCategory/" + catId,
            cache: false,
            success: function (result) {
                $("#subcat").html(result);
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
                var over_max_size = <?php echo $max_size ?> * 1048576;
                if (filesize > over_max_size) {
                    alert('Maximum <?php echo $max_size; ?>MB file size allowed for logo.');
                    document.getElementById("JobLogo").value = '';
                    return false;
                }
            }
        }
    }


</script>