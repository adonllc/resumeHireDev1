<script type="text/javascript">
    $(document).ready(function() {
        $(".dev_menus").click(function() {
            $(".dev_drop_menu").toggle(1000);
        });
    });
</script>
<?php $info = Classregistry::init('User')->findById($this->Session->read('user_id')); ?> 
<?php
if ($info['User']['user_type'] == 'recruiter') {
    $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $info['User']['company_logo'];
    $company_logo = $info['User']['company_logo'];
    if (file_exists($path) && !empty($info['User']['company_logo'])) {
        $bgimage = "background-image: url(" . HTTP_PATH . "/app/webroot/files/user/full/$company_logo)";
    } else {
        $bgimage = "background-image: url(" . HTTP_IMAGE . "/front/profile_banner.png)";
    }
} else {
    $bgimage = "background-image: url(" . HTTP_IMAGE . "/front/profile_banner03.png)";
}
?>
<div class="image_sec" style="<?php echo $bgimage; ?>">
    <div class="container">
    <div class="row">
        <?php $info = Classregistry::init('User')->findById($this->Session->read('user_id'));?>  
        <div class="col-lg-12">
            <div class="profile_img">
                <?php
                    $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $info['User']['profile_image'];
                        if (file_exists($path) && !empty($info['User']['profile_image'])) {
                            ?>

                            <?php
                             echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_FULL_PROFILE_IMAGE_PATH . $info['User']['profile_image'] . "&w=200&zc=1&q=100", array('escape' => false));

                        } else {
                            echo $this->Html->image('front/no_image_user.png');
                        }
                ?>
            </div>

            <div class="profilie_right">
                <em><?php echo ucwords($info['User']['first_name'].' '.$info['User']['last_name']);?></em>
                <b>(<?php 
                    if($info['User']['user_type'] == 'recruiter'){
                        echo __d('user', 'Employer', true);
                    }else{
                          echo __d('user', 'Jobseeker', true);
                    }
                   ?>)</b>
                    <?php 
                       if($info['Location']['name']){
                      echo '<b><i class="fa fa-map-marker"></i>'. $info['Location']['name'] .'</b>';
                    }
//                    if($info['User']['user_type'] == 'recruiter'){ ?>
                    <div class="topln">
                        <?php $cplan = Classregistry::init('Plan')->getcurrentplan($info['User']['id']);
                        if($cplan){
                            echo '<span class="fertcd">'.$cplan['Plan']['plan_name'].'</span>';
                            echo $this->Html->link(__d('user', 'Upgrade Plan', true), array('controller'=>'plans', 'action'=>'purchase'), array('class'=>'upplan topplan'));
                        }else{
                            echo $this->Html->link(__d('user', 'Purchase Plan', true), array('controller'=>'plans', 'action'=>'purchase'), array('class'=>'upplan topplan noplan'));
                        } 
                        ?>
                    </div>
                    <?php
                        
//                    }
                 
                    ?>
            </div>
        </div>
        </div>
    </div>    
 <?php if ($info['User']['user_type'] == 'recruiter') {
          $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $info['User']['company_logo'];
    $company_logo = $info['User']['company_logo'];
    if (file_exists($path) && !empty($info['User']['company_logo'])) {
        $estab_text= __d('user', 'Change photo', true);
    } else{
         $estab_text= __d('user', 'Upload your Establishment photo here', true);
    }
?>
                    <div class="upload_estab_photo">
                        <div class="choose_photo_new">
                            <label for="UserProfileImage"><?php echo $estab_text; ?></label>
                            <a href="javascript:void(0)" id="change_picture" title="Change Picture" alt="">

                                <?php echo $this->Form->file('User.profile_image', array('class' => 'required upload_estab_file')); ?>
                            </a>
                        </div>




                    </div>
                <?php } ?>
</div>
<?php if ($info['User']['user_type'] == 'recruiter') { ?>
    <?php echo $this->Html->script('front/ajaxupload.3.5.js'); ?>
    <script>
        $(function () {
            var btnUpload = $('#change_picture');
          
            new AjaxUpload(btnUpload, {
                action: '<?php echo HTTP_PATH; ?>/users/ajaxchangeprofile',
                name: 'data[User][company_logo]',
                onSubmit: function (file, ext) {
                    if (!(ext && /^(jpeg|jpg|png)$/.test(ext))) {
                        $(".all_bg").hide();
                        alert('Supported File Types: gif, jpg, png');
                        return false;
                    }
                    $(".all_bg").show();
                },
                onComplete: function (file, response) {
                    var data = $.parseJSON(response);
                    if (data.message) {
                        $(".all_bg").hide();
                        window.location.reload();
                    } else {
                        $(".all_bg").hide();
                        alert(data.error);
                    }
                }
            });
        });
    </script>
<?php } ?>


