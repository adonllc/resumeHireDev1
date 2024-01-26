<?php
echo $this->Html->script('jquery.validate.js');
$max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'max_size'));
//pr($max_size); die;
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#editCat").validate();
    });

</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false,'rel'=>'nofollow'));
$this->Html->addCrumb('<i class="fa fa-th" ></i> Categories List » ', '/admin/categories', array('escape' => false,'rel'=>'nofollow'));
$this->Html->addCrumb('<i class="fa fa-pencil-square-o" ></i> Edit Category');
?>





<?php echo $this->Form->create(null, array('enctype' => 'multipart/form-data', 'id' => 'editCat')); ?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->
            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Edit Category</h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        Category Details:
                    </header>
                    <div class="panel-body">


                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Category Name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Category.name', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Category Image</label>
                            <div class="col-sm-10">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail bordernone">
                                        <div class="fileupload-new thumbnail" style="max-width: 200px; max-height: 150px; line-height: 10px;">
                                            <?php
                                            $filePath = UPLOAD_SMALL_CATEGORY_IMAGE_PATH . $this->data['Category']['old_image'];
//                                            echo $filePath;
                                            if (file_exists($filePath) && $this->data['Category']['old_image']) {
                                                echo $this->Html->image(DISPLAY_SMALL_CATEGORY_IMAGE_PATH . $this->data['Category']['old_image'], array('alt' => ''));
                                            } else {
                                                echo $this->Html->image('no_image.gif');
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        if (!empty($this->data['Category']['old_image'])) {
                                            echo $this->Html->link('Delete Old Image', array('controller' => 'categories', 'action' => 'deleteCategoryImage', $this->data['Category']['slug'], $catslug), array('escape' => false, 'title' => 'Delete Old Image', 'class' => 'btn btn-primary btn-danger', 'confirm' => "Are you sure want to Delete this Image?"));
                                        }
                                        ?>
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                            <?php echo $this->Form->input('Category.image', array('type' => 'file', 'label' => false, 'div' => false, 'class' => 'default', 'id' => 'upload_image', 'onchange' => 'imageValidation()')) ?>
                                        </span>
                                    </div><span >Supported File Types: gif, jpg, jpeg, png (Max. 2MB). Min file size 40 X 40 pixels. Max file size 100 X 100 pixels.</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Meta Keyword </label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Category.meta_keywords', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control")) ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Meta Title </label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Category.meta_title', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control")) ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Meta Description </label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->textarea('Category.meta_description', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control")) ?>
                                Note.: Meta details are important please fill these information. If you don't filled it by default information will be show.

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Keywords <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Category.keywords', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control")) ?>
                                <em>(comma (,) separated)</em>
                            </div>
                        </div>




                    </div>
                </section>

            </div>
        </div>
        <div class="col-lg-10">
            <?php
            echo $this->Form->hidden('Category.id');
            echo $this->Form->hidden('Category.slug');
            echo $this->Form->hidden('Category.old_name');
            echo $this->Form->hidden('Category.old_image');
            ?>
            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php echo $this->Html->link('Cancel', array('controller' => 'categories', 'action' => 'index'), array('escape' => false,'rel'=>'nofollow', 'class' => 'btn btn-danger')); ?>        </div>
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