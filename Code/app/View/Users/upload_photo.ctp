<?php
$max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'max_size'));
//pr($max_size); die;
?> 
<script>
    $().ready(function () {
        $("#editProfile").validate();
    });
</script>
<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="container">
            <div class="row">
                <div class="my_acc">
                    <?php echo $this->element('left_menu'); ?>
                    <div class="col-lg-9">
                        <?php echo $this->Form->create("Null", array('enctype' => 'multipart/form-data', "method" => "Post", 'id' => 'editProfile', 'name' => 'changeprofilepicture')); ?>
                        <div class="my-profile-boxes">
                            <div class="my-profile-boxes-top"><h2><i><?php echo $this->Html->image('front/home/change-logo-icon.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Change company logo', true);?></span></h2></div>
                            <div class="information_cntn">
                                <?php echo $this->element('session_msg'); ?>

                                <div class="form_list_education">
                                    <label class="lable-acc-add"><span class="lsdd-title"><?php echo __d('user', 'Current Logo', true);?> <span class="star_red"></span></span></label>
                                    <span class="rltv">
                                        <div class="user_img_box">
                                        <?php
                                        $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $UseroldImage['User']['profile_image'];
                                        if (file_exists($path) && !empty($UseroldImage['User']['profile_image'])) {
                                            ?>

                                            
                                                <div class="showchange showmede2 delete_icon" id="photo22">
                                                    <a class="edit_profilepicture" href="<?php echo HTTP_PATH; ?>/users/deleteImage/<?php echo $UseroldImage['User']['slug']; ?>" onClick="return confirm('<?php echo __d('user', 'Are you sure you want to Delete ?', true); ?>');">
                                                        <?php echo __d('user', '<i class="fa fa-trash-o"></i> ', true); ?> <span class="edit_profilepicture_icon"></span>
                                                    </a>
                                                </div>
                                                 
                                                <?php
                                                echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $UseroldImage['User']['profile_image'], array('escape' => false,'rel'=>'nofollow'), array('class' => ' '));
                                            } else {
                                                echo $this->Html->image('front/no_image_user.png', array('class' => 'image_css'));
                                            }
                                            ?>                                      
                                           </div>
                                    </span>
                                </div>

                                <div class="form_list_education">
                                   <label class="lable-acc"><?php echo __d('user', 'New Logo', true);?> </label>
                                    <div class="form_input_education form_upload_file">
                                        <span class="choose-file-your">Choose File</span>
                                        <?php echo $this->Form->file('User.profile_image', array('class' => 'form-control required')); ?>
                                    </div>
                                   <div class="abccc pstrength-minchar"><?php echo __d('user', 'Supported File Types', true);?>: gif, jpg, jpeg, png (Max. <?php echo $max_size; ?>MB). <?php echo __d('user', 'Min file size', true);?> 250 X 250 <?php echo __d('user', 'pixels', true);?></div>
                                </div>
                                
                                <div class="form_list_education">
                                    <label class="lable-acc-add"><span class="lsdd-title"><?php echo __d('user', 'Establishment photo', true);?> <span class="star_red"></span></span></label>
                                    <span class="rltv">
                                        <div class="user_img_box">
                                        <?php
                                        $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $UseroldLogo['User']['company_logo'];
                                        if (file_exists($path) && !empty($UseroldLogo['User']['company_logo'])) {
                                            ?>
                    
                                                <div class="showchange showmede2 delete_icon" id="photo22">
                                                    <a class="edit_profilepicture" href="<?php echo HTTP_PATH; ?>/users/deleteEstabImage/<?php echo $this->data['User']['slug']; ?>" onClick="return confirm('<?php echo __d('user', 'Are you sure you want to Delete ?', true); ?>');">
                                                        <?php echo __d('user', '<i class="fa fa-trash-o"></i> ', true); ?> <span class="edit_profilepicture_icon"></span>
                                                    </a>
                                                </div>
                                                 
                                                <?php
                                                echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $UseroldLogo['User']['company_logo'], array('escape' => false,'rel'=>'nofollow'), array('class' => ' '));
                                            } else {
                                                echo $this->Html->image('front/no_image_user.png', array('class' => 'image_css'));
                                            }
                                            ?>                                      
                                           </div>
                                    </span>
                                </div>
                                
                                <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'Upload Establishment photo', true); ?></label>
                                   <div class="form_input_education form_upload_file">
                                        <span class="choose-file-your">Choose File</span>
                                        <?php echo $this->Form->file('User.company_logo', array('class' => 'form-control')); ?>
                                                                            

                                   </div>
                                    <div class="abccc pstrength-minchar"><?php echo __d('user', 'Supported File Types', true); ?>: jpg, jpeg, png.<?php echo __d('user', 'Min file size', true);?> 1260 X 264 <?php echo __d('user', 'pixels', true);?></div>

                                        <?php
//                                        $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $UseroldLogo['User']['company_logo'];
//                                        if (file_exists($path) && !empty($UseroldLogo['User']['company_logo'])) {
                                            ?>

<!--                                            <div class="all-uploaded-images" id="<?php echo $this->data['User']['slug']; ?>" >
                                                <?php echo '<b>' . $UseroldLogo['User']['company_logo'] . '</b>'; ?>
                                                <a class="close_icon_for" href="<?php echo HTTP_PATH; ?>/users/deleteEstabImage/<?php echo $this->data['User']['slug']; ?>" onClick="return confirm('<?php echo __d('user', 'Are you sure you want to Delete ?', true); ?>');">
                                                    <?php echo __d('user', '<i class="fa fa-trash-o"></i> ', true); ?> 
                                                </a>
                                            </div>-->
                                            <?php
//                                            echo '<b>' . $UseroldImage['User']['company_logo'] . '</b>';
//                                        }
                                        ?>  
                                </div>

                                <div class="form_lst sssss">
                                    <span class="rltv">
                                        <div class="pro_row_left">
                                             <?php echo $this->Form->hidden('User.old_logo'); ?>  
                                            <?php echo $this->Form->hidden('User.old_image'); ?> 
                                            <?php echo $this->Form->submit(__d('user', 'Upload', true), array('div' => false, 'label' => false, 'class' => 'input_btn', 'onclick' => 'return imageValidation();')); ?>
                                            <?php echo $this->Html->link(__d('user', 'Cancel', true), array('controller' => 'users', 'action' => 'myaccount'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow')); ?>
                                        </div> 
                                    </span>
                                </div>

                            </div>        
                        </div>
                        <?php echo $this->Form->end(); ?> 
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

        var filetype = ['jpeg', 'png', 'jpg', 'gif'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " <?php echo __d('user', 'file not allowed for company logo.', true);?>");
                return false;
            } else {
                var fi = document.getElementById('UserProfileImage');
                var filesize = fi.files[0].size;//check uploaded file size
                //                    if(filesize > 2097152){
                //                        alert('Maximum 2MB file size allowed for Product Image.');
                //                        return false;
                //                    }
                var over_max_size = <?php echo $max_size ?> * 1048576;
                if (filesize > over_max_size) {
                    alert('Maximum <?php echo $max_size ?>MB <?php echo __d('user', 'file size allowed for company logo.', true);?>');
                    return false;
                }
            }
        }
        return true;
    }

</script>