<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&key=<?php echo AUTO_SUGGESTION;?>"></script> 
<script>
    var autocomplete;
      function initialize() {
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('UserLocation')));
    }    
</script>
<?php $max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','max_size')); ?> 
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

    });

    function makeInactive() {
        $('#hidden_but').show();
        $('#visible_but').hide();
        $('#UserChangeStatus').val('0');
    }
    function makeActive() {
        $('#visible_but').show();
        $('#hidden_but').hide();
        $('#UserChangeStatus').val('1');
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
$this->Html->addCrumb('<i class="fa fa-pencil-square-o"></i> Edit Employer Details', 'javascript:void(0)', array('escape' => false));
?>
<?php echo $this->Form->create('User', array('method' => 'POST', 'name' => 'addusers', 'enctype' => 'multipart/form-data', 'id' => 'adminChamheEmail')); ?>

<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
         
            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Edit Employer Details</h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        Employer Details:
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company Name <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('User.company_name', array('maxlength' => '50', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required", 'readonly' => true)) ?>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Company Profile <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php
                                echo $this->Form->textarea('User.company_about', array('label' => '', 'div' => false, 'id' => "description", 'placeholder' => 'Company Profile', 'class' => 'required'));
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
                                <?php echo $this->Form->text('User.first_name', array('maxlength' => '50', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control validname required")) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Last Name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('User.last_name', array('maxlength' => '50', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control validname required")) ?>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Address <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->textarea('User.address', array('size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Profile Image </label>
                            <div class="col-sm-10">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                        <?php
                                        if ($this->data['User']['old_profile_image']) {
                                            echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $this->data['User']['old_profile_image']);
                                        } else {
                                            echo $this->Html->image('no_image.gif');
                                        }
                                        ?>
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" id="uploadedImage" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
                                            <span class="fileupload-exists" id="undoIcon"><i class="fa fa-undo"></i> Change</span>
                                            <?php echo $this->Form->input('User.profile_image', array('type' => 'file', 'label' => false, 'div' => false, 'class' => 'default', 'id' => 'upload_image', 'onchange' => 'imageValidation()')) ?>
                                        </span>
                                    </div>
                                    <span >Supported File Types: gif, jpg, jpeg, png (Max. <?php echo $max_size; ?>MB). Min file size 250 X 250 pixels. </span>
                                </div>
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
                            <label class="col-sm-2 col-sm-2 control-label">Email Address <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('User.email_address', array('maxlength' => '255', 'size' => '25', 'readonly' => true, 'label' => '', 'div' => false, 'class' => 'form-control required email', 'autocomplete' => 'off')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Password <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->password('User.new_password', array('minlength' => '8', 'maxlength' => '40', 'size' => '25', 'label' => '', 'id' => 'password', 'div' => false, 'class' => "form-control", 'autocomplete' => 'off')) ?>
                                <em class="bugdm">* Note: If You want to change User's password, only then fill password below otherwise leave it blank.</em>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Confirm Password <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->password('User.confirm_password', array('maxlength' => '255', 'size' => '25', 'label' => '', 'equalTo' => '#password', 'div' => false, 'class' => "form-control")) ?>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="panel">
                    <header class="panel-heading">
                        Other Details:
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Employer Status<div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->hidden('User.change_status', array('value' => $this->data['User']['status'])); ?>
                                    <!--<span class="activedes">Active</span>-->
                                <div class="visible_but" id="visible_but" style="display:<?php
                                if ($this->data['User']['status'] == 1) {
                                    echo 'block;';
                                } else {
                                    echo 'none;';
                                }
                                ?>">
                                         <?php echo $this->Html->link('Active', 'javascript:void(0);', array('update' => 'status' . $this->data['User']['id'], 'onclick' => 'makeInactive();', 'escape' => false)); ?> 
                                </div>
                                <!--<span class="activedes">Inactive</span>-->
                                <div class="hidden_but" id="hidden_but" style="display:<?php
                                if ($this->data['User']['status'] == 1) {
                                    echo 'none;';
                                } else {
                                    echo 'block;';
                                }
                                ?>">
                                         <?php echo $this->Html->link('Inactive', 'javascript:void(0);', array('update' => 'status' . $this->data['User']['id'], 'onclick' => 'makeActive();', 'escape' => false)); ?>
                                </div>
                                <div class="clr"></div>
                                <span class="statuslabl">&nbsp;</span>
                                <span class="help_textnomi">Click above to change Employer's status.</span>
                                <br/>
                                <span class="help_textnomi" style="float: left;width: 100%; color:#f00;">*Changes will be reflect only after submitting form.</span>
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
                    <?php
                    echo $this->Form->hidden('User.id');
                    echo $this->Form->hidden('User.slug');
                    echo $this->Form->hidden('User.status');
                    //echo $this->Form->hidden('User.old_image');
                    echo $this->Form->hidden('User.old_profile_image');
                    echo $this->Form->hidden('User.old_password');
                    ?>
                    <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
                    <?php echo $this->Html->link('Cancel', array('controller' => 'users', 'action' => 'index', ''), array('escape' => false, 'class' => 'btn btn-danger')); ?>
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
                alert(ext + " file not allowed for company logo.");
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

</script>