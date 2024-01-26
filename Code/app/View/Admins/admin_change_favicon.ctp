<?php
echo $this->Html->script('jquery/ui/jquery.ui.core.js');
$max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'max_size'));
?> 

<script type="text/javascript">
    $(document).ready(function () {

        $("#iconPic").validate();

    });


</script>
<?php
echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css');
?>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-compass"></i> Change Favicon', 'javascript:void(0)', array('escape' => false));
?>
<?php echo $this->Form->create('Null', array('method' => 'POST', 'name' => 'changeFav', 'enctype' => 'multipart/form-data', 'id' => 'iconPic')); ?>

<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->


            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Change Favicon</h4>
                <?php echo $this->Session->flash(); ?>

                <section class="panel">
                    <header class="panel-heading">
                        Favicon Details:
                    </header>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Favicon </label>
                            <div class="col-sm-10">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 100px; height: 80px;">

                                        <?php
                                        $path = DISPLAY_FULL_FAV_PATH . $old_fav['Admin']['favicon'];

                                        // echo $path;
                                        if (!empty($old_fav['Admin']['favicon']) && isset($old_fav['Admin']['favicon'])) {
                                            ?>

                                            <div class="user_img_box">
                                                <div class="showchange showmede2 delete_icon" id="photo22" style="float: right;">
                                                    <a class="edit_profilepicture" href="<?php echo HTTP_PATH; ?>/admins/deleteFavicon/<?php echo $old_fav['Admin']['favicon']; ?>" onClick="return confirm('<?php echo __d('admin', 'Are you sure you want to Delete ?', true); ?>');">
                                                        <?php echo __d('admin', '<i class="fa fa-trash-o"></i> ', true); ?> <span class="edit_profilepicture_icon"></span>
                                                    </a>
                                                </div>
                                                <?php
                                                echo $this->Html->image(DISPLAY_FULL_FAV_PATH . $old_fav['Admin']['favicon'], array('escape' => false), array('class' => 'user_img_box '));
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
                            <label class="col-sm-2 col-sm-2 control-label">New Favicon <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
<?php echo $this->Form->input('Admin.favicon', array('type' => 'file', 'label' => false, 'div' => false, 'class' => 'default required', 'id' => 'fav_image', 'onchange' => 'favImageValidation()')) ?>    
                                <p><span >Supported File Types: ico (Max. <?php echo $max_size ?>MB). Min file size 16 X 16 pixels. </span></p>
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
echo $this->Form->hidden('Admin.old_fav');
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

    function favImageValidation() {

        var filename = document.getElementById("fav_image").value;
        var filetype = ['ico'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " file not allowed for image.");
                document.getElementById("fav_image").value = '';
                return false;
            } else {
                var fi = document.getElementById('fav_image');
                var filesize = fi.files[0].size;//check uploaded file size
                //alert(filesize);
                var over_max_size = <?php echo $max_size ?> * 1048576;
                //alert(over_max_size);
                if (filesize > over_max_size) {
                    alert('Maximum <?php echo $max_size ?>MB file size allowed for image.');
                    document.getElementById("fav_image").value = '';
                    return false;
                }
            }
        }
    }



</script>