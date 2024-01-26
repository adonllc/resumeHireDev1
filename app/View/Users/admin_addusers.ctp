<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&key=<?php echo AUTO_SUGGESTION;?>"></script> 
<script>
    var autocomplete;
      function initialize() {
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('UserLocation')));
    }    
</script>
<?php echo $this->Html->script('jquery.validate.js'); ?>

<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<script>
    $(function () {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
</script>
<?php echo $this->Html->css('jquery.datetimepicker.css'); ?>
<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>

<script type="text/javascript">
    $(document).ready(function () {

        $.validator.addMethod("mypassword", function (input) {

            var reg = /[0-9]/; //at least one number
            var reg2 = /[a-z]/; //at least one small character
            var reg3 = /[A-Z]/; //at least one capital character
            //var reg4 = /[\W_]/; //at least one special character
            return reg.test(input) && reg2.test(input) && reg3.test(input);
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

        $('#resetForm').click(function () {
            $('.fileupload-exists .fileupload-new, .fileupload-new .fileupload-exists').css("display", "inline-block");
            $('#undoIcon').css("display", "none");
            $('#uploadedImage').children().css("display", "none");
            //$('#uploadedImage').html('');
            $('#no_image_div').css("display", "block");
            $('#uploadedImage').css("border", "none");
        });


    });
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
$this->Html->addCrumb('<i class="fa fa-user" ></i> Employers » ', array('controller' => 'users', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-plus"></i> Add Employer Details', 'javascript:void(0)', array('escape' => false));
?>
<?php echo $this->Form->create('User', array('method' => 'POST', 'name' => 'addusers', 'enctype' => 'multipart/form-data', 'id' => 'adminChamheEmail')); ?>

<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->


            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Add Employer</h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        Employer Details:
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company Name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('User.company_name', array('maxlength' => '50', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company Profile <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php
                                echo $this->Form->textarea('User.company_about', array('class'=>'required','label' => '', 'div' => false, 'id' => "description", 'placeholder' => 'Company Profile'));
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Position <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('User.position', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>

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
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Address <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->textarea('User.address', array('size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>
 

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Location <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php 
                              //echo $this->Form->select('User.location', $locationlList, array('data-placeholder'=>'Choose location', 'class'=>"chosen-select form-control ", 'empty'=>'Select Location')); 
                              echo $this->Form->text('User.location', array('div' => false, 'class' => "form-control required", 'placeholder'=>'Enter Location'))
                              ?>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Contact Number <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('User.contact', array('maxlength' => '16','minlength' => '8', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company Number <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('User.company_contact', array('maxlength' => '16','minlength' => '8', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company Website <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('User.url', array('div' => false, 'class' => "form-control my_url")) ?> 
                                Eg.: http://www.google.com or www.google.com
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
                                <?php echo $this->Form->text('User.email_address', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => 'form-control required email', 'autocomplete' => 'off')) ?>
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
                    <?php echo $this->Form->reset('Reset', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-danger', 'id' => 'resetForm')); ?>
                </div></div></div>
    </section>
</section>

<?php echo $this->Form->end(); ?>

<script>

    CKEDITOR.replace('data[User][company_about]', {
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