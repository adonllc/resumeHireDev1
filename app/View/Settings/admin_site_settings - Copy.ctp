

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

        $("#adminSettings").validate();

    });
</script>
<?php
echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css');
?>

<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-cog" ></i> Settings » ', array('controller' => 'settings', 'action' => 'siteSettings'), array('escape' => false));

$this->Html->addCrumb('<i class="fa fa-list" ></i> Site Settings » ', array('controller' => 'settings', 'action' => 'siteSettings'), array('escape' => false));
?>
<?php echo $this->Form->create('setting', array('method' => 'POST', 'name' => 'siteSettings', 'enctype' => 'multipart/form-data', 'id' => 'adminSettings')); ?>
<?php
//echo"<pre>"; print_r($this->data);
?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->


            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Edit Site Settings</h4>
                <?php echo $this->Session->flash(); ?>

                <section class="panel">
                    <header class="panel-heading">
                        Setting Details:
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Site Title <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('SiteSetting.title', array('maxlength' => '50', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Site Url <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('SiteSetting.url', array('maxlength' => '150', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control url required")) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Site Tagline <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('SiteSetting.tagline', array('maxlength' => '150', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Facebook Link <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('SiteSetting.facebook_link', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>

<!--                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Twitter Link <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('SiteSetting.twitter_link', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>-->

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Linkedin Link <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('SiteSetting.linkedin_link', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Instagram Link <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('SiteSetting.instagram_link', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Pinterest Link <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('SiteSetting.pinterest', array('label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>

                        <!--                        <div class="form-group">
                                                    <label class="col-sm-2 col-sm-2 control-label">Site Video <div class="required_field">*</div></label>
                                                    <div class="col-sm-10" >
                        <?php //echo $this->Form->text('SiteSetting.video_link', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                                                    </div>
                                                </div>-->

                        <!-- <div class="form-group">
                             <label class="col-sm-2 col-sm-2 control-label">Enquiry Mail <div class="required_field">*</div></label>
                             <div class="col-sm-10" >
                        <?php //echo $this->Form->text('Sitesetting.enquirymail', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required email"))  ?>    
                             </div>
                         </div>-->

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Contact Number <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('SiteSetting.phone', array('maxlength' => '16','minlength' => '8', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control contact required")) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Max Image Upload Size (in Mb) <div class="required_field">*</div></label>
                            <div class="col-sm-10" >

                                <?php
                                $option = array(
                                    '2' => 2,
                                    '4' => 4,
                                    '6' => 6,
                                    '8' => 8,
                                    '10' => 10,
                                );

                                echo $this->Form->input('SiteSetting.max_size', array('options' => $option, 'type' => 'select', 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Chosse size'));
                                ?>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Payment Through App</label>
                            <div class="col-sm-10" >

                                <?php 
                                $checked = '';
                                if ($this->request->data['SiteSetting']['app_payment']=='1') {
                                    $checked = 'checked';
                                }
                                ?>    
                                <div class="des_box_cont test-size">
                                    <input  <?php echo $checked; ?> type="checkbox" id="app_payment" value="1" name="data[SiteSetting][app_payment]"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Number of jobs</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('SiteSetting.jobs_count', array('size' => '25', 'label' => '', 'div' => false, 'class' => 'form-control')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Top Employer Text</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('SiteSetting.top_emp_text', array('size' => '25', 'label' => '', 'div' => false, 'class' => 'form-control')) ?>
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
<?php echo $this->Html->link('Cancel', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false, 'class' => 'btn btn-danger')); ?>
                </div>
            </div>
        </div>
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
                if (filesize > 4194304) {
                    alert('Maximum 4MB file size allowed for image.');
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