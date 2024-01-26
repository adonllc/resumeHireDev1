<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>
<?php
echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css');
?>
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("url1", function (value, element) {
            return this.optional(element) || /^(http(s?):\/\/)?(www\.)+[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/.test(value);
        }, "Please enter a valid URL.");
        $.validator.addMethod("contact", function (value, element) {
            return  this.optional(element) || (/^[0-9+]+$/.test(value));
        }, "Contact Number is not valid.");

        $("#addBlog").validate();



    });


</script>
<?php
$max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'max_size'));
//pr($max_size); die;
?> 
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-file-image-o" ></i> Blogs » ', array('controller' => 'blogs', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-plus"></i> Add Blog', 'javascript:void(0)', array('escape' => false));
?>
<?php echo $this->Form->create('Blog', array('method' => 'POST', 'name' => 'addBlog', 'enctype' => 'multipart/form-data', 'id' => 'addBlog')); ?>

<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->

            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Add Blog</h4>
                <?php echo $this->Session->flash(); ?>

                <section class="panel">
                    <header class="panel-heading">
                        Blog Details:
                    </header>
                    <div class="panel-body">


                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Blog Title <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Blog.title', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Description <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->textarea('Blog.description', array('label' => '', 'div' => false, 'id' => "description", 'placeholder' => 'Description', 'class' => "form-control required")) ?>    
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Blog Image <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <div id="fileupload" class="fileupload fileupload-new" data-provides="fileupload">
                                    <div id="no_image_div" class="fileupload-new thumbnail"  style="width: 200px; height: 150px;">
                                        <?php echo $this->Html->image('no_image.gif'); ?>
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" id="uploadedImage" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileupload-new" id="selectIcon"><i class="fa fa-paper-clip"></i> Select image</span>
                                            <span class="fileupload-exists" id="undoIcon"><i class="fa fa-undo"></i> Change</span>
                           
                                            <?php echo $this->Form->input('Blog.image', array('type' => 'file', 'label' => false, 'div' => false, 'class' => 'default', 'id' => 'upload_image', 'onchange' => 'imageValidation()')) ?>
                                        </span>
                                    </div>
                                    <span >Supported File Types: gif, jpg, jpeg, png (Max. <?php echo $max_size; ?>MB). Min file size 250 X 250 pixels. </span>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Meta Title <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Blog.meta_title', array('maxlength' => '255','size' => '25', 'label' => '', 'div' => false, 'class' => "form-control")) ?>    
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Meta Keyword <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Blog.meta_keyword', array('maxlength' => '255','size' => '25', 'label' => '', 'div' => false, 'class' => "form-control")) ?>    
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Meta Description <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->textarea('Blog.meta_description', array('maxlength' => '255','size' => '25', 'label' => '', 'div' => false, 'class' => "form-control")) ?>    
                             Note.: Meta details are important please fill these information. If you don't filled it by default information will be show.
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
                    <?php echo $this->Html->link('Cancel', array('controller' => 'blogs', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-danger')); ?>
                </div></div></div>
    </section>
</section>

<?php echo $this->Form->end(); ?>





<script>

    CKEDITOR.replace('data[Blog][description]', {
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

</script>