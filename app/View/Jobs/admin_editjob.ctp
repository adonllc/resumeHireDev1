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
</script>
<?php
$max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'max_size'));

echo $this->Html->script('jquery.datetimepicker.js');
?>
<?php echo $this->Html->css('jquery.datetimepicker.css'); ?>
<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>

<?php echo $this->Html->script('jquery/ui/jquery.ui.core.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.widget.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.position.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.datepicker.js'); ?>
<?php echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css'); ?>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<script>
    $(function () {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
           $('#JobLastdate').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate:0
        });
    });
</script>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<script>
    $(function () {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
</script>


<script type="text/javascript">
    $(function () {

        var date = new Date();
        var currentHour = date.getHours();
        currentHour = currentHour + 1;
        var currneMonth = date.getMonth() + 1;
        var twoDigitMonth = (currneMonth > 9) ? currneMonth : '0' + currneMonth;
        var twoDigitDate = (date.getDate() > 9) ? date.getDate() : '0' + date.getDate();

        var currentDate = date.getFullYear() + "-" + twoDigitMonth + "-" + twoDigitDate;
        //alert(currentDate);
        $('#JobCreated').datetimepicker({
            //            mask: '9999-19-39 29:59',
            //            format: 'Y-m-d H:i',
            //            minDate: 'mm-dd-yyyy',
            //minTime: 'h:mm'
            format: 'Y-m-d H:i',
            formatDate: 'Y-m-d',
            formatTime: 'H:i',
            maxDate: 'mm-dd-yyyy',
            //minTime: 'h:mm',
            //maxTime: 'H:i',
            timepicker: true,
            onSelectDate: function (ct) {

                if (currentDate == jQuery('#JobCreated').val().split(" ")[0]) {
                    this.setOptions({
                        maxTime: 'H:i'
                    })
                } else {
                    this.setOptions({
                        maxTime: '24:00'
                    })
                }

            }


        });


    });
</script>
<?php echo $this->Html->script('jquery.validate.js'); ?>

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
        $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_]+$/.test(value);
        }, "*Note: Special characters, number and spaces are not allowed.");
        $.validator.addMethod("uid1", function (value, element) {
            return  this.optional(element) || (/^[a-z0-9_-]{3,15}$/.test(value));
        }, "Unique id is not valid.");
        $.validator.addMethod("pass", function (value, element) {
            return  this.optional(element) || (/.{8,}/.test(value) && /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,20})/.test(value));
        }, "Password minimum length must be 8 charaters and combination of 1 special character, 1 lowercase character, 1 uppercase character and 1 number.");
        $.validator.addMethod("my_url", function (value, element) {
            var regexp = /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/
            return this.optional(element) || regexp.test(value);
        }, "Please enter a valid URL");

        $("#adminChamheEmail").validate();

        CKEDITOR.replace('data[Job][description]', {
            toolbar:
                    [
                        {name: 'editing', items: ['Scayt']},
                        {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline']},
                        {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-']},
                        {name: 'links', items: ['Link', 'Unlink']},
                        {name: 'tools', items: ['']}
                    ],
            language: '',
            height: 150,
            width: 563
        });
        
        CKEDITOR.replace('data[Job][brief_abtcomp]', {
            toolbar:
                    [
                        {name: 'editing', items: ['Scayt']},
                        {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline']},
                        {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-']},
                        {name: 'links', items: ['Link', 'Unlink']},
                        {name: 'tools', items: ['']}
                    ],
            language: '',
            height: 150,
            width: 563
        });


        var opt = '';
        var minExp = $('#JobMinExp').val();
        var maxExp = "<?php echo $this->data['Job']['max_exp']; ?>";
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
        var maxSal = "<?php echo $this->data['Job']['max_salary']; ?>";
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

    function makeInactive() {
        $('#hidden_but').show();
        $('#visible_but').hide();
        $('#JobChangeStatus').val('0');
    }
    function makeActive() {
        $('#visible_but').show();
        $('#hidden_but').hide();
        $('#JobChangeStatus').val('1');
    }
</script>
<?php
echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css');
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
            url: "<?php echo HTTP_PATH; ?>/admin/cities/getStateCityByPostCode/Job/" + postCode,
            cache: false,
            success: function (result) {
                $("#updateCityState").html(result);
            }
        });
    }

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
$this->Html->addCrumb('<i class="fa fa-list" ></i> Jobs List » ', array('controller' => 'users', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-pencil-square-o"></i> Edit Job Details', 'javascript:void(0)', array('escape' => false));
?>
<?php echo $this->Form->create('Job', array('method' => 'POST', 'name' => 'addusers', 'enctype' => 'multipart/form-data', 'id' => 'adminChamheEmail')); ?>

<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->


            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Edit Job</h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        Job Details:
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Employer Name <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Job.re_name', array('value' => $jobInfo['User']['first_name'] . ' ' . $jobInfo['User']['last_name'], 'class' => "form-control required", 'readonly' => true)) ?>    
                            </div>
                        </div>
                        <div class="form-group" style="display: none">
                            <label class="col-sm-2 col-sm-2 control-label">Job Type <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Job.type', array('maxlength' => '50', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required", 'readonly' => true)) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Job Title <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Job.title', array('class' => "form-control required")) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Category <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Job.category_id', array('type' => 'select', 'options' => $categories, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select Job Type Category', 'onChange' => 'updateSubCat(this.value)')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Sub Category <div class="required_field"></div></label>
                            <div class="col-sm-10"  id="subcat">
                                <?php echo $this->Form->input('Job.subcategory_id', array('multiple' => true, 'type' => 'select', 'options' => $subcategories, 'label' => false, 'div' => false, 'class' => "form-control", 'empty' => 'Select Job Type Sub Category')) ?>
                                Note: Please hold Ctrl / Command to select multiple options.
                            </div>

                        </div>

                        <div class="form-group" style="display: none">
                            <label class="col-sm-2 col-sm-2 control-label">Highlights of Job <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Job.selling_point1', array('class' => "form-control")); ?>   
                                
                            </div>
                        </div> 


                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Job Description <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php
                                echo $this->Form->textarea('Job.description', array('label' => '', 'div' => false, 'id' => "description", 'placeholder' => 'Enter description'));
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Job.company_name', array('maxlength' => '100', 'class' => "form-control required")) ?>    
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
                            <label class="col-sm-2 col-sm-2 control-label">Contact Name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Job.contact_name', array('class' => "form-control required")) ?>    
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company Profile <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php
                                echo $this->Form->textarea('Job.brief_abtcomp', array('class'=>'required','label' => '', 'div' => false, 'id' => "profile", 'placeholder' => 'Company Profile'));
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Contact Number <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Job.contact_number', array('maxlength' => '16', 'minlength' => '8','class' => "form-control contact required")) ?>    
                            </div>
                        </div>

                          <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company Website <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Job.url', array('class' => "form-control my_url")) ?>   
                                Ex: http://www.google.com
                            </div>
                        </div> 
<!--                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">YouTube Video Url <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Job.youtube_link', array('class' => "form-control url")) ?>   
                                Ex: https://www.youtube.com/watch?v=7TF00hJI78Y 
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Experience (In Years) <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php 
                                  global $experienceArray;
                                    echo $this->Form->input('Job.exp', array('type'=>'select','options'=>$experienceArray, 'label'=>false, 'class' => "form-control required"));
                                ?>        
                            </div>
                           
                        </div>

                        <?php /* <div class="form-group">
                          <label class="col-sm-2 col-sm-2 control-label">Experience <div class="required_field">*</div></label>
                          <div class="col-sm-3">
                          <?php
                          $arrYear= array();
                          for($year = 0; $year <= 30; $year++){
                          $arrYear[$year]  = $year;
                          }
                          echo $this->Form->input('Job.exp_year', array('type'=>'select','options'=>$arrYear, 'label'=>false, 'class' => "form-control required", 'empty'=>'Select Year'));

                          ?>
                          </div>
                          <div class="col-sm-3">
                          <?php    $arrMonth= array();
                          for($month = 0; $month <= 11; $month++){
                          $arrMonth[$month]  = $month;
                          }
                          echo $this->Form->input('Job.exp_month', array('type'=>'select','options'=>$arrMonth, 'label'=>false, 'class' => "form-control", 'empty'=>'Select Month'));
                          ?>
                          </div>
                          </div> */ ?>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Annual Salary <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php  global $sallery;
                                    echo $this->Form->input('Job.salary', array('type'=>'select','options'=>$sallery, 'label'=>false, 'class' => "form-control required", 'empty'=>'Select Salary'));
                                ?>        
                            </div>
                           
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Skills <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                               <!-- <select data-placeholder="Choose a Country" class="chosen-select" multiple tabindex="4">
                                    <option value=""></option>
                                    <option value="United States">United States</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select> -->


                                <?php echo $this->Form->select('Job.skill', $skillList, array('multiple' => true, 'data-placeholder' => 'Choose skills', 'class' => "chosen-select")); ?>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Designation <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->select('Job.designation', $designationlList, array('data-placeholder' => 'Choose a designation', 'class' => "chosen-select")); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Location <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php //echo $this->Form->select('Job.location', $locationlList, array('data-placeholder' => 'Choose location', 'class' => "chosen-select")); ?>
                                <?php echo $this->Form->text('Job.job_city', array('id' => 'job_city', 'class' => "form-control required")) ?>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Last Date <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                        <?php echo $this->Form->text('Job.expire_time', array('id' => 'JobLastdate','autocomplete' => 'off', 'class' => "form-control required", 'placeholder' => 'Last Date')) ?>    
                            </div>
                        </div>


                        <?php if (isset($this->data['Job']['type']) && $this->data['Job']['type'] != 'bronze') { ?>

                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">Company Logo <div class="required_field"></div></label>
                                <div class="col-sm-10" >
                                    <?php echo $this->Form->file('Job.logo', array('class' => 'default', 'onchange' => 'imageValidation()')) ?>
                                    Supported File Types: gif, jpg, jpeg, png (Max. <?php echo $max_size; ?>MB).
                                    <br><br>
                                    <?php
                                    if ($this->data['Job']['old_logo']) {
                                        echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_JOB_LOGO_PATH . $this->data['Job']['old_logo'] . "&w=200&zc=1&q=100", array('escape' => false));
                                    }
                                    ?>
                                </div>
                            </div>    
                        <?php } ?>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Job Posted Date <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Job.created', array('class' => "form-control required")) ?>    
                            </div>
                        </div>


                    </div>
                </section>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-sm-2 col-sm-2 control-label">&nbsp;</div>
                <div class="col-lg-9">
                    <?php echo $this->Form->hidden('Job.lat'); ?>   
                        <?php echo $this->Form->hidden('Job.long'); ?>
                    <?php
                    echo $this->Form->hidden('Job.id');
                    echo $this->Form->hidden('Job.old_logo');
                    echo $this->Form->hidden('Job.old_title');
                    echo $this->Form->hidden('Job.type');
                    ?>
                    <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
                    <?php echo $this->Html->link('Cancel', array('controller' => 'jobs', 'action' => 'index', ''), array('escape' => false, 'class' => 'btn btn-danger')); ?>
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
                    alert('Maximum <?php echo $max_size ?>MB file size allowed for logo.');
                    document.getElementById("JobLogo").value = '';
                    return false;
                }
            }
        }
    }

    function cvValidation() {

        var filename = document.getElementById("JobCv").value;
        var filetype = ['pdf', 'doc', 'docx'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " file not allowed for CV Document.");
                document.getElementById("JobCv").value = '';
                return false;
            } else {
                var fi = document.getElementById('JobCv');
                var filesize = fi.files[0].size;//check uploaded file size

                if (filesize > 2097152) {
                    alert('Maximum <?php echo $max_size ?>MB file size allowed for CV Document.');
                    document.getElementById("JobCv").value = '';
                    return false;
                }
            }
        }
    }

</script>