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
    });
</script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&key=<?php echo AUTO_SUGGESTION;?>"></script> 
<script>
    var autocomplete;
      function initialize() {
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('UserLocation')));
    }    
</script>
<script type="text/javascript">
    window.onload = function () {
        initialize();        
    };
</script> 

<script>
    $(function () {
        $("#UserDob").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            //minDate: 'mm-dd-yyyy',
            maxDate: 'mm-dd-yyyy',
            yearRange: "c-60:c+20",
            changeYear: true,
        });
    });
</script>
<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("contact", function (value, element) {
            return  this.optional(element) || (/^[0-9+]+$/.test(value));
        }, "Contact Number is not valid.");
        $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_]+$/.test(value);
        }, "*Note: Special characters, number and spaces are not allowed.");
        $.validator.addMethod("pass", function (value, element) {
            return  this.optional(element) || (/.{8,}/.test(value) && /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,20})/.test(value));
        }, "Password minimum length must be 8 charaters and combination of 1 special character, 1 lowercase character, 1 uppercase character and 1 number.");

        $("#adminCandidate").validate();

    });
</script>
<?php

$max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','max_size'));
//pr($max_size); die;
?> 
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
            url: "<?php echo HTTP_PATH; ?>/admin/cities/getStateCityByPostCode/User/" + postCode,
            cache: false,
            success: function (result) {
                $("#updateCityState").html(result);
            }
        });
    }

</script>
<?php

$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-users" ></i> Jobseekers » ', array('controller' => 'candidates', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-plus"></i> Add Jobseeker Details', 'javascript:void(0)', array('escape' => false));
?>
<?php echo $this->Form->create('User', array('method' => 'POST', 'name' => 'addcandidates', 'enctype' => 'multipart/form-data', 'id' => 'adminCandidate')); ?>

<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->


            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Add Jobseeker</h4>
                <?php echo $this->Session->flash(); ?>

                <section class="panel">
                    <header class="panel-heading">
                        Jobseeker Details:
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">First Name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('User.first_name', array('maxlength' => '20', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control validname required")) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Last Name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('User.last_name', array('maxlength' => '20', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control validname required")) ?>    
                            </div>
                        </div>
                        <!--Postal Code, city, state-->
                        <!-- <div class="form-group">
                             <label class="col-sm-2 col-sm-2 control-label">Postal Code <div class="required_field">*</div></label>
                             <div class="col-sm-10" >
                                <?php //echo $this->Form->text('User.postal_code', array('maxlength' => '16', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required", 'id' => 'UserPostalCodeId')) ?>    
                             </div>
                         </div>
                         <div id="updateCityState">
                             <div class="form-group">
                                 <label class="col-sm-2 col-sm-2 control-label">Suburb <div class="required_field">*</div></label>
                                 <div class="col-sm-10" id="cityDiv" >
                                    <?php //echo $this->Form->input('User.city_id', array('type' => 'select', 'options' => $cityList, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select City', 'id' => 'UserCityId')) ?>
                                 </div>
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-2 col-sm-2 control-label">State <div class="required_field">*</div></label>
                                 <div class="col-sm-10" >
                                    <?php //echo $this->Form->input('User.state_id', array('type' => 'select', 'options' => $stateList, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select State', 'id' => 'UserStateId')) ?>
                                 </div>
                             </div>
                         </div>-->

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Location <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                              <?php 
                                //echo $this->Form->select('User.location', $locationlList, array('data-placeholder'=>'Choose location', 'class'=>"chosen-select form-control required", 'empty'=>'Select Location'));
                                echo $this->Form->text('User.location', array('div' => false, 'class' => "form-control required", 'placeholder'=>'Enter Location'))
                              ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Contact Number <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('User.contact', array('maxlength' => '16','minlength' => '8', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control contact required")) ?>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Profile Image </label>
                            <div class="col-sm-10">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                        <?php echo $this->Html->image('no_image.gif'); ?>
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" id="uploadedImage" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileupload-new btn-default btn"><i class="fa fa-paper-clip"></i> Select image</span>
                                            <span class="fileupload-exists btn-default btn" id="undoIcon"><i class="fa fa-undo"></i> Change</span>
                                            <?php echo $this->Form->input('User.profile_image', array('type' => 'file', 'label' => false, 'div' => false, 'class' => 'default', 'id' => 'upload_image', 'onchange' => 'imageValidation()')) ?>
                                        </span>
                                    </div>
                                    <span>Supported File Types: gif, jpg, jpeg, png (Max. <?php echo $max_size; ?>MB). Min file size 250 X 250 pixels. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="panel">
                    <header class="panel-heading">
                        Login Details:
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Email Address <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('User.email_address', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => 'form-control required  email', 'autocomplete' => 'off')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Password <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->password('User.password', array('minlength' => '8', 'maxlength' => '40', 'size' => '25', 'label' => '', 'id' => 'password', 'div' => false, 'class' => "form-control required", 'autocomplete' => 'off')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Confirm Password <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->password('User.confirm_password', array('maxlength' => '255', 'size' => '25', 'label' => '', 'equalTo' => '#password', 'div' => false, 'class' => "form-control required")) ?>
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
            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php echo $this->Html->link('Reset', array('controller' => 'candidates', 'action' => 'addcandidates', ''), array('escape' => false, 'class' => 'btn btn-danger')); ?>
                </div></div></div>
    </section>
</section>

<?php echo $this->Form->end(); ?>

<script>
    function resetss() {
        alert('e');
    }

    function updateCity() {
        $('#cityDiv').html('<select id="UserCityId" class="form-control required" name="data[User][city_id]"><option value="">Select City</option></select>');
    }


<?php
$this->Js->get('#UserCountryId')->event('change', $this->Js->request(array(
            'controller' => 'states',
            'action' => 'getStates',
                ), array(
            'update' => '#UserStateId',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);
?>
</script>
<script>
<?php
$this->Js->get('#UserStateId')->event('change', $this->Js->request(array(
            'controller' => 'cities',
            'action' => 'getCities'
                ), array(
            'update' => '#UserCityId',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);
?>
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

        var filename = document.getElementById("upload_image").value;
        var filetype = ['jpg', 'jpeg', 'png', 'gif'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " file not allowed for image.");
                document.getElementById("upload_image").value = '';
                return false;
            } else {
                var fi = document.getElementById('upload_image');
                var filesize = fi.files[0].size;//check uploaded file size
                var over_max_size = <?php echo $max_size ?> * 1048576;
                if (filesize > over_max_size) {
                    alert('Maximum <?php echo $max_size ?>MB file size allowed for image.');
                    document.getElementById("upload_image").value = '';
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

                if (filesize > 4194304) {
                    alert('Maximum 4MB file size allowed for CV Document.');
                    document.getElementById("UserCv").value = '';
                    return false;
                }
            }
        }
    }

</script>