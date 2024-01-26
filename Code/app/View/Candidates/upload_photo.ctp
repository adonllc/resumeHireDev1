<?php

$max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','max_size'));
?> 
<script>
    $().ready(function () {
        $("#editProfile").validate();
    });
</script>
<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                <div class="col-xs-12 col-sm-3 col-md-9 col-lg-9">

                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-uoload-photo"><h2><i><?php echo $this->Html->image('front/home/change-pass-icon2.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Change profile picture', true);?></span></h2></div>
                        <div class="information_cntn">
                            <?php echo $this->element('session_msg'); ?>

                            <?php echo $this->Form->create("Null", array('enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'editProfile', 'name' => 'changeprofilepicture')); ?>
                            <div class="form_list_education">
<!--                                <label><?php echo __d('user', 'Current Image', true);?> <span class="star_red"></span></label>-->
                                <div class="form_input_education">
                                    <div class="user_img_box">
                                        <?php
                                            $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $UseroldImage['User']['profile_image'];
                                            if (file_exists($path) && !empty($UseroldImage['User']['profile_image'])) {
                                                ?>


                                        <div class="showchange showmede2 delete_icon" id="photo22">
                                            <a class="edit_profilepicture" href="<?php echo HTTP_PATH; ?>/candidates/deleteImage/<?php echo $UseroldImage['User']['slug']; ?>" onClick="return confirm('<?php echo __d('user', 'Are you sure you want to Delete ?', true); ?>');">
                                                        <i class="fa fa-trash-o"></i> <span class="edit_profilepicture_icon"></span>
                                            </a>
                                        </div>


                                                <?php
                                                echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $UseroldImage['User']['profile_image'], array('escape' => false,'rel'=>'nofollow'), array('class'=>' '));
                                            } else {
                                                echo $this->Html->image('front/no_image_user.png' ,array('class'=>'image_css'));
                                            }
                                        ?>
                                    </div>    
                                    </div>    
                               
                            </div>


                            <div class="form_list_education">
                                <label class="lable-acc"><?php echo __d('user', 'New Image', true);?> <span class="star_red">*</span></label>
                                <div class="form_input_education form_upload_file">
                                    <span class="choose-file-your">Choose File</span>
                                         <?php echo $this->Form->file('User.profile_image', array('class' => 'form-control required')); ?>
                                    
                                </div>
                                <div class="abccc pstrength-minchar"><?php echo __d('user', 'Supported File Types', true);?>: gif, jpg, jpeg, png (Max. <?php echo $max_size; ?>MB). <?php echo __d('user', 'Min file size', true);?> 250 X 250 <?php echo __d('user', 'pixels', true);?>.</div>
                            </div>

                            <div class="form_lst sssss">
                                <span class="rltv">
                                    <div class="pro_row_left">
                                     <?php echo $this->Form->submit(__d('user', 'Upload', true), array('div' => false, 'label' => false, 'class' => 'input_btn','onclick' => 'return imageValidation();')); ?>
                                     <?php echo $this->Html->link(__d('user', 'Cancel', true), array('controller' => 'candidates', 'action' => 'myaccount'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow')); ?>
                                    </div> 
                                </span>
                            </div>
                            <?php echo $this->Form->end(); ?> 
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><script>
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

        var filename = document.getElementById("UserProfileImage").value;
        var filetype = ['jpg', 'jpeg', 'png', 'gif'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " <?php echo __d('user', 'file not allowed for image.', true);?>");
                document.getElementById("UserProfileImage").value = '';
                return false;
            } else {
                var fi = document.getElementById('UserProfileImage');
                var filesize = fi.files[0].size;//check uploaded file size
                var over_max_size = <?php echo $max_size ?> * 1048576;
                if (filesize > over_max_size) {
                    alert('Maximum <?php echo $max_size ?> <?php echo __d('user', 'MB file size allowed for image.', true);?>');
                    document.getElementById("UserProfileImage").value = '';
                    return false;
                }
            }
        }
    }

</script>