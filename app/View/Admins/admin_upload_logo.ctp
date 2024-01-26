<?php
echo $this->Html->script('jquery/ui/jquery.ui.core.js');
$max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'max_size'));
?> 

<script type="text/javascript">
    $(document).ready(function () {

        $("#logoPic").validate();

    });


</script>
<?php
echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css');
?>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-picture-o"></i> Change Logo', 'javascript:void(0)', array('escape' => false));
?>
<?php echo $this->Form->create('Null', array('method' => 'POST', 'name' => 'uploadLogo', 'enctype' => 'multipart/form-data', 'id' => 'logoPic')); ?>

<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->


            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Change Logo</h4>
                <?php echo $this->Session->flash(); ?>

                <section class="panel">
                    <header class="panel-heading">
                        Logo Details:
                    </header>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Logo </label>
                            <div class="col-sm-10">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">

                                        <?php
                                        $path = UPLOAD_FULL_WEBSITE_LOGO_PATH . $old_logo['Admin']['logo'];
                                        if (file_exists($path) && !empty($old_logo['Admin']['logo']) && isset($old_logo['Admin']['logo'])) {
                                            ?>

                                            <div class="user_img_box">
                                                 <?php
                    if (IS_LIVE) { ?>
                                                <div class="showchange showmede2 delete_icon" id="photo22" style="float: right;">
                                                    <a class="edit_profilepicture" href="<?php echo HTTP_PATH; ?>/admins/deleteLogo/<?php echo $old_logo['Admin']['logo']; ?>" onClick="return confirm('<?php echo __d('admin', 'Are you sure you want to Delete ?', true); ?>');">
                                                        <?php echo __d('admin', '<i class="fa fa-trash-o"></i> ', true); ?> <span class="edit_profilepicture_icon"></span>
                                                    </a>
                                                </div>
                                                
                    <?php }
                                                echo $this->Html->image(DISPLAY_FULL_WEBSITE_LOGO_PATH . $old_logo['Admin']['logo'], array('escape' => false), array('class' => 'user_img_box '));
                                            } else {
                                                echo $this->Html->image('front/no_image_user.png', array('class' => 'image_css user_img_box'));
                                            }
                                            ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">New Logo <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->input('Admin.logo', array('type' => 'file', 'label' => false, 'div' => false, 'class' => 'default required', 'id' => 'upload_image')) ?>    
                                <p><span>- Supported File Types: gif, jpg, jpeg, png (Max. <?php echo $max_size ?>MB). Min file size 250 X 45 pixels. </span><br>
                                <p><span>- Best visible size: logo size 282 X 47 pixels. </span></p>
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
                    echo $this->Form->hidden('Admin.id');
                    echo $this->Form->hidden('Admin.slug');
                    echo $this->Form->hidden('Admin.status');
                    echo $this->Form->hidden('Admin.old_logo');// this is old logo name
                    ?>
                   
                    <?php
                    if (IS_LIVE) {
                       echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); 
                    echo $this->Html->link('Cancel', array('controller' => 'admins', 'action' => 'index', ''), array('escape' => false, 'class' => 'btn btn-danger'));
                    
                    } else {
                        echo "<blockquote> You are not allowed to update above information, because It's a demo of this product. Once we deliver code to you, you'll be able to update Configurations. </blockquote>";
                    }
                    ?>
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
        var fi = document.getElementById('upload_image');
         var file = fi.files[0];
                if (file) {
                    var img = new Image();
                    img.src = window.URL.createObjectURL(file);
                    img.onload = function () {
                        var width = img.naturalWidth,
                                height = img.naturalHeight;
                        window.URL.revokeObjectURL(img.src);
//                        alert(width);
//                        alert(height);
                        if (width > 290 || height > 55) {
                            alert("Width and Height of image should not more than 290 X 55 pixels");
                            $("#upload_image").val('');
                            return false;
                        }

                    };
                }
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

                //alert(filesize);
                var over_max_size = <?php echo $max_size ?> * 1048576;
                //alert(over_max_size);
                if (filesize > over_max_size) {
                    alert('Maximum <?php echo $max_size ?>MB file size allowed for image.');
                    document.getElementById("upload_image").value = '';
                    return false;
                }
            }
        }
    }


</script>
