
<script type="text/javascript">
    $(document).ready(function() {
        $.validator.addMethod("url1", function(value, element) { 
            return this.optional(element) ||  /^(http(s?):\/\/)?(www\.)+[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/.test(value);
        }, "Please enter a valid URL.");
        $.validator.addMethod("contact", function(value, element) {
            return  this.optional(element) || (/^[0-9+]+$/.test(value));
        }, "Contact Number is not valid.");
        
        $("#addBanner").validate();
        
        
       
    });
    function showDiv(){

        if(document.getElementById('Type1').checked){ 
            $('#advertisementimage').show();
            $('#advertisementimage2').show();
            $('#advertisementimage3').show();
            $('#advertisementcode').hide();
        }else if(document.getElementById('Type2').checked){ 
            $('#advertisementimage').hide();
            $('#advertisementimage2').hide();
            $('#advertisementimage3').hide();
            $('#advertisementcode').show();
        }

    }
    
</script>
<?php

$max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','max_size'));
//pr($max_size); die;
?> 
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-file-image-o" ></i> Banner Advertisements » ', array('controller' => 'banneradvertisements', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-plus"></i> Edit Banner Advertisement', 'javascript:void(0)', array('escape' => false));
?>
<?php echo $this->Form->create('Banneradvertisement', array('method' => 'POST', 'name' => 'addBanner', 'enctype' => 'multipart/form-data', 'id' => 'addBanner')); ?>

<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->

            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Edit Advertisement</h4>
                <?php echo $this->Session->flash(); ?>

                <section class="panel">
                    <header class="panel-heading">
                        Advertisement Details:
                    </header>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Place of Advertisement <div class="required_field"></div></label>
                            <div class="col-sm-10 passw" >
                                <?php
                                if ($this->data['Banneradvertisement']['advertisement_place'] == 'job_selection') {
                                    $place = 'Job Package Page';
                                } else{
                                    $place = $this->data['Banneradvertisement']['advertisement_place'];
                                }
                                echo $place;
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Title <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Banneradvertisement.title', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required alphanumeric")) ?>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Advertisement Type <div class="required_field">*</div></label>
                            <div class="col-sm-10 thso_radio_hdfsd" >
                            	<div class="sjdgfgds_radio">
                                <?php
                                $options = array('1' => 'Picture Adverts', '2' => 'Google Adverts');
                                $attributes = array('id' => 'type', 'legend' => false, 'default' => '1', 'onclick' => 'showDiv();');
                                echo $this->Form->radio('Banneradvertisement.type', $options, $attributes);
                                ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id = "advertisementimage" <?php if ($type == 2) { ?>style="display:none;" <?php } ?>>
                            <label class="col-sm-2 col-sm-2 control-label">URL <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php
                                if ($type == 1 || $type == 3) {
                                    $urlclass = 'required url1';
                                } else {
                                    $urlclass = '';
                                }
                                echo $this->Form->text('Banneradvertisement.url', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required url1 " . $urlclass))
                                ?>    
                                <span class="help_text">(Enter URL Like http://www.google.com)</span>
                            </div>
                        </div>

                        <div class="form-group" id = "advertisementText" <?php if ($type == 2 || $type == 1) { ?>style="display:none;" <?php } ?>>
                            <label class="col-sm-2 col-sm-2 control-label">Advertisement Text <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php
                                if ($type == 3) {
                                    $textclass = 'required';
                                } else {
                                    $textclass = '';
                                }
                                echo $this->Form->textarea('Banneradvertisement.text', array('size' => '25', 'rows' => 5, 'cols' => '50', 'label' => '', 'div' => false, 'class' => "form-control required " . $textclass))
                                ?>
                            </div>
                        </div>

                        <div class="form-group" id = "advertisementimage2" <?php if ($type == 2 || $type == 3) { ?>style="display:none;" <?php } ?>>
                            <label class="col-sm-2 col-sm-2 control-label">Advertisement Image </label>
                            <div class="col-sm-10">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                        <?php
                                        if ($this->data['Banneradvertisement']['image']) {
                                            echo $this->Html->image(DISPLAY_THUMB_BANNER_AD_IMAGE_PATH . $this->data['Banneradvertisement']['image']);
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
                                            <?php echo $this->Form->input('Banneradvertisement.image', array('type' => 'file', 'label' => false, 'div' => false, 'class' => 'default', 'id' => 'upload_image', 'onchange' => 'imageValidation()')) ?>
                                        </span>
                                    </div>
                                    <span >Supported File Types: gif, jpg, jpeg, png (Max. <?php echo $max_size; ?>MB).
                                        Standard size of Advertisement images<br/>
                                     <!--   1) Job Selection Page (Width:1294px, Height:292px) -->
                                         1) Job Selection Page (Width:720px, Height:320px)
                                        <br/>
                                    </span>
                                </div>
                            </div>
                        </div>

                        

                        <div class="form-group" id = "advertisementcode" <?php if ($type == 1 || $type == 3) { ?>style="display:none;" <?php } ?> >
                            <label class="col-sm-2 col-sm-2 control-label">Advertisement HTML Code <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php
                                if ($type == 2) {
                                    $codeclass = 'required';
                                } else {
                                    $codeclass = '';
                                }
                                echo $this->Form->textarea('Banneradvertisement.code', array('size' => '25', 'rows' => 5, 'cols' => '50', 'label' => '', 'div' => false, 'class' => "form-control required " . $codeclass))
                                ?>
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
                    echo $this->Form->input('Banneradvertisement.advertisement_place', array('type' => 'hidden', 'value' => $this->data['Banneradvertisement']['advertisement_place']));
                    echo $this->Form->input('Banneradvertisement.old_image', array('type' => 'hidden', 'value' => $this->data['Banneradvertisement']['image']));
                    echo $this->Form->input('Banneradvertisement.id', array('type' => 'hidden'));
                    ?>
                    <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
                    <?php echo $this->Html->link('Cancel', array('controller' => 'banneradvertisements', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-danger')); ?>
                </div></div></div>
    </section>
</section>

<?php echo $this->Form->end(); ?>





<script>
   
    function in_array(needle, haystack) {
        for (var i=0, j=haystack.length; i < j; i++) {
            if (needle == haystack[i])
                return true;
        }
        return false;
    }

    function getExt(filename) {
        var dot_pos = filename.lastIndexOf(".");
        if(dot_pos == -1)
            return "";
        return filename.substr(dot_pos+1).toLowerCase();
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

</script>