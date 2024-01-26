<?php echo $this->Html->script('jquery.validate.js'); ?>

<script type="text/javascript">
    $(document).ready(function() {

        $.validator.addMethod("mypassword", function(input) {

            var reg = /[0-9]/; //at least one number
            var reg2 = /[a-z]/; //at least one small character
            var reg3 = /[A-Z]/; //at least one capital character
            //var reg4 = /[\W_]/; //at least one special character
            return reg.test(input) && reg2.test(input) && reg3.test(input);
        }, "<?php echo __d('common', 'Password Must include 1 lower and 1 upper case letter. Must include 1 number.'); ?>");

        $.validator.addMethod("contact", function(value, element) {
            return  this.optional(element) || (/^[0-9+]+$/.test(value));
        }, "Contact Number is not valid.");
         $.validator.addMethod("validname", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9_]+$/.test(value);
        }, "*Note: Special characters and spaces are not allowed.");
        $.validator.addMethod("uid1", function(value, element) {
            return  this.optional(element) || (/^[a-z0-9_-]{3,15}$/.test(value));
        }, "Unique id is not valid.");
        $.validator.addMethod("pass", function(value, element) {
            return  this.optional(element) || (/.{8,}/.test(value) && /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,20})/.test(value));
        }, "Password minimum length must be 8 characters and combination of 1 special character, 1 lowercase character, 1 uppercase character and 1 number.");

        $("#adminChamheEmail").validate();
        
        $('#resetForm').click(function(){
            $('.fileupload-exists .fileupload-new, .fileupload-new .fileupload-exists').css("display", "inline-block");
            $('#undoIcon').css("display", "none");
            $('#uploadedImage').children().css("display", "none");
            //$('#uploadedImage').html('');
            $('#no_image_div').css("display", "block");
            $('#uploadedImage').css("border","none");
        });
    });
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list" ></i> Sub Admins » ', array('controller' => 'admins', 'action' => 'manage'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-plus"></i> Add Sub Admin', 'javascript:void(0)', array('escape' => false));
?>
<?php echo $this->Form->create('Admin', array('method' => 'POST', 'name' => 'addadmin', 'enctype' => 'multipart/form-data', 'id' => 'adminChamheEmail')); ?>

<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->


            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Add Sub Admin</h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        Admin Details:
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">First Name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Admin.first_name', array('maxlength' => '50', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Last Name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Admin.last_name', array('maxlength' => '50', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Username <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Admin.username', array('maxlength' => '50', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Email Address <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Admin.email', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => 'form-control required email', 'autocomplete' => 'off')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Password <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->password('Admin.password', array('minlength' => '8', 'maxlength' => '40', 'size' => '25', 'label' => '', 'id' => 'password', 'div' => false, 'class' => "form-control pass required", 'autocomplete' => 'off')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Confirm Password <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->password('Admin.confirm_password', array('maxlength' => '255', 'size' => '25', 'label' => '', 'equalTo' => '#password', 'div' => false, 'class' => "form-control required")) ?>
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
        $('#no_image_div').css("display", "none");
        $('#selectIcon').css("display", "none");
        $('#undoIcon').css("display", "block");
        var filename = document.getElementById("upload_image").value;
        var filetype = ['jpg', 'jpeg', 'png', 'gif'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " file not allowed for image.");
                document.getElementById("upload_image").value = '';
                $('#no_image_div').css("display", "block");
                $('#selectIcon').css("display", "block");
                $('#undoIcon').css("display", "none");
                $('#uploadedImage').children().css("display", "none");
                //$('#uploadedImage').html('');
                $('#no_image_div').css("display", "block");
                $('#uploadedImage').css("border","none");
                return false;
            } else {
                var fi = document.getElementById('upload_image');
                var filesize = fi.files[0].size;//check uploaded file size
                if (filesize > 2097152) {
                    alert('Maximum 2MB file size allowed for image.');
                    document.getElementById("upload_image").value = '';
                    $('#no_image_div').css("display", "block");
                    $('#selectIcon').css("display", "block");
                    $('#undoIcon').css("display", "none");
                    $('#uploadedImage').children().css("display", "none");
                    //$('#uploadedImage').html('');
                    $('#no_image_div').css("display", "block");
                    $('#uploadedImage').css("border","none");
                    return false;
                }
            }
        }        
    }
    
</script>