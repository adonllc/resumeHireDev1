<?php
 $max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','max_size'));
?> 
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
            return;
        return filename.substr(dot_pos + 1).toLowerCase();
    }

    function imageValidation() {

        var filename = document.getElementById("upload_image").value;

        var filetype = ['png'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " file not allowed for Profile Picture.");
                return false;
            } else {
                var fi = document.getElementById('upload_image');
                var filesize = fi.files[0].size;//check uploaded file size
                //                    if(filesize > 2097152){
                //                        alert('Maximum 2MB file size allowed for Product Image.');
                //                        return false;
                //                    }
                var over_max_size = <?php echo $max_size ?> * 1048576;
                if (filesize > over_max_size) {
                    alert('Maximum <?php echo $max_size ?>MB file size allowed for logo Picture.');
                    return false;
                }
            }
        }
        return true;
    }

</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#changepassword").validate();
    });
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-lock" ></i> Manage Invoice Fields', 'javascript:void(0);', array('escape' => false));
?>
<?php echo $this->Form->create('Admin', array('method' => 'POST', 'name' => 'changepassword', 'enctype' => 'multipart/form-data', 'id' => 'changepassword', 'class' => 'form-horizontal tasi-form')); ?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->
            <div class="col-lg-12">
                <?php echo $this->Session->flash(); ?>
                <h4 style="margin-left:15px" class="m-bot15">Manage Invoice Fields</h4>
                <section class="panel">
                    <div class="panel-body">                              
<!--                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">ABN <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php //echo $this->Form->text('Admin.abn', array('placeholder' => "ABN", 'label' => '', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Account Name <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.account_name', array('placeholder' => "Account Name", 'label' => '', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">BSB <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.bsb', array('placeholder' => "BSB", 'label' => '', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">ACC <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.acc', array('placeholder' => "ACC", 'label' => '', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Accounts Email Address <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.acnt_email_add', array('placeholder' => "Account Email Address", 'label' => '', 'div' => false, 'class' => 'form-control required email')) ?>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Accounts Number <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.ac_nu', array('placeholder' => "Account Number", 'label' => '', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Payment Terms (in Days) <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                 <?php echo $this->Form->text('Admin.payment_terms', array( 'min'=>1, 'placeholder' => "Payment Terms", 'label' => '', 'div' => false, 'class' => 'form-control required digit')) ?>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Invoice Address <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                 <?php echo $this->Form->text('Admin.invoice_address', array(  'placeholder' => "Invoice Address", 'label' => '', 'div' => false, 'class' => 'form-control required digit')) ?>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Invoice Suburb <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                 <?php echo $this->Form->text('Admin.invoice_suburb', array( 'placeholder' => "Invoice Suburb", 'label' => '', 'div' => false, 'class' => 'form-control required digit')) ?>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Invoice State <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                 <?php echo $this->Form->text('Admin.invoice_state', array( 'placeholder' => "Invoice State", 'label' => '', 'div' => false, 'class' => 'form-control required digit')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Invoice Postcode <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                 <?php echo $this->Form->text('Admin.invoice_postcode', array( 'placeholder' => "Invoice Postcode ", 'label' => '', 'div' => false, 'class' => 'form-control required digit')) ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Bank Name <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                 <?php echo $this->Form->text('Admin.bank', array(  'placeholder' => "Bank Name", 'label' => '', 'div' => false, 'class' => 'form-control required digit')) ?>
                            </div>
                        </div>


                       <!-- <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Logo</label>
                            <div class="col-sm-10">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                        <?php 
                                        
                                        if($this->data['Admin']['logo']){
                                           // echo $this->Html->image(DISPLAY_FULL_INVOICE_IMAGE_PATH.$this->data['Admin']['logo']);
                                        }else{
                                           // echo $this->Html->image('no_image.gif');
                                        }    
                                            ?>
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" id="uploadedImage" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
                                            <span class="fileupload-exists" id="undoIcon"><i class="fa fa-undo"></i> Change</span>
                                            <?php //echo $this->Form->input('Admin.logo', array('type' => 'file', 'label' => false, 'div' => false, 'class' => 'default', 'id' => 'upload_image')) ?>
                                        </span>
                                    </div>
                                    <span >Supported File Types:  png. </span>
                                </div>
                            </div>
                        </div> -->


                    </div>
                </section>                          
            </div>
        </div>
        <div class="col-lg-10">

            <?php echo $this->Form->hidden('Admin.id');
            // echo $this->Form->hidden('Admin.logo'); ?>
            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success','onclick' => 'return imageValidation();')); ?>
            <?php //echo $this->Form->button('Cancel', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-danger')); ?>
            <?php echo $this->Html->link('Cancel', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false, 'class' => 'btn btn-danger')); ?>
        </div>
        <!-- page end-->
    </section>
</section>
<?php echo $this->Form->end(); ?>       