<?php echo $this->Html->script('jquery/ui/jquery.ui.core.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.widget.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.position.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.datepicker.js'); ?>
<?php echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css'); ?>


<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="<?php echo HTTP_PATH; ?>/css/front/uploadfilemulti.css" rel="stylesheet">
<script src="<?php echo HTTP_PATH; ?>/js/front/jquery.fileuploadmulti.min.js" charset="utf-8"></script>
<?php echo $this->Html->script('jquery.validate.js'); ?>
<style>
    .adderror{ border: 0 solid #f3665c !important;
               color: #f3665c !important;
               font-weight: normal; }
    </style>
<script type="text/javascript">

    $(document).ready(function () {

        /************************************************************/
        $.validator.addMethod("contact", function (value, element) {
            return  this.optional(element) || (/^[0-9+]+$/.test(value));
        }, "<?php echo __d('user', 'Contact Number is not valid', true); ?>.");
        $.validator.addMethod("validname", function (value, element) {
            return this.optional(element) || /^[a-zA-Z_]+$/.test(value);
        }, "*<?php echo __d('user', 'Note: Special characters, number and spaces are not allowed', true); ?>.");

        $("#addVideoCV").validate();
        /************************************************************/
        $('#locDiv').find("#UserLocation_chosen a span").on("click", function () {
            $('#UserLocation_chosen').removeClass('error');
            $('#showerror').empty();
        });
       
            $('input[type="submit"]').on('click', function (e) {
                $('#aboutvideofile').removeClass('adderror');
                $('#aboutvideofile').text('');
                    <?php
                    $video = $this->data['User']['video'];
                    if (!empty($video) && file_exists(UPLOAD_VIDEO_PATH . $video)) {
                    ?>
                         var video = false;
                    <?php }else{ ?>
                         var video = true;
                    <?php }?>   
                // console.log($('#upload_video').attr('value'));
                console.log(video);
                if(video){
                    if ($('#upload_video').val() == '' || $('#upload_video').val() == null) {
                    // console.log('asa');
                        $('#upload_video').addClass('error');
                        $('#upload_video').addClass('required');
                        $('#aboutvideofile').addClass('adderror');
                        $('#aboutvideofile').text('<?php echo __d('user', 'This field is required.', true); ?>');
                        e.preventDefault();
                    }

                } 
                
                
                // $('#abouttext').removeClass('adderror');
                // $('#abouttext').text('');
                // if ($('#UserAbout').val() == '' || $('#UserAbout').val() == null) {
                //     $('#UserAbout').addClass('error');
                //     $('#UserAbout').addClass('required');
                //     $('#abouttext').addClass('adderror');
                //     $('#abouttext').text('<?php // echo __d('user', 'This field is required.', true); ?>');
                //     e.preventDefault();
                // }
            });

    });
    
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&key=<?php echo AUTO_SUGGESTION; ?>"></script> 
<div class="my_accnt">
        <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                <div class="col-xs-12 col-sm-3 col-md-9 col-lg-9">
                <div class="rght_dv">

                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-videocv-boxes"><h2><i><?php echo $this->Html->image('front/home/video-cv-icon2.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Video CV', true); ?></span></h2></div>
                        <div class="information_cntn" style="position:inherit !important;">
                            <?php echo $this->element('session_msg'); ?>             
                            <?php echo $this->Form->create("candidates", array('enctype' => 'multipart/form-data',"method" => "Post","action"=>"addVideoCv", 'id' => 'addVideoCV', 'class' => "form_trl_box_show", 'name' => 'changeprofilepicture')); ?>

                            <div class="form_lst">
                                <label><?php echo __d('user', 'Add a video file', true); ?> <span class="star_red"></span></label>
                                <span class="rltv">
                                    <?php echo $this->Form->file('User.video', array('class' => ($this->data['User']['video'])?'':'required', 'id' => 'upload_video', 'onchange' => 'VideoValidation()')); ?>
                                    <?php
//                                    foreach ($showOldImages as $showOldImage) {
                                        $video = $this->data['User']['video'];
                                        if (!empty($video) && file_exists(UPLOAD_VIDEO_PATH . $video)) {
                                            ?>
                                            <?php echo $this->Form->text('User.old_video', array('type'=>'hidden','value'=>$this->data['User']['video'])) ?>
                                             <span class="temp-image-section">
                                                 <?php echo $video; ?>
                                                  <?php echo $this->Html->link($this->Html->image('close.png', array('title' => __d('item', 'Delete', true))), array('controller' => 'candidates', 'action' => 'deleteVideo', $video ), array('class' => 'dfasggs','escape' => false, 'rel' => 'nofollow')); ?> 
                                              </span>
                                            <?php
                                        }
//                                    }
                                    ?>
                                    <div class="abccc pstrength-minchar"><?php echo __d('user', 'Supported File Types', true); ?>: mp4, 3gp, avi, mov (Maximum Size 20MB).</div>
                                    <label id="aboutvideofile"></label>
                                </span>
                                
                            </div>
                            <!-- <div class="form_lst">

                                    <div id="locDiv" class="rltv"><br />
                                        <?php // echo $this->Form->textarea('User.video_text', array('class' => "required ", 'id'=>'UserAbout','placeholder' => __d('user', 'About Your Self', true))) ?>
                                        <label id="abouttext"></label>
                                    </div>
                            </div>             -->

                      <div class="form_lst sssss">
                            <label class="blank_label">&nbsp;</label>
                            <span class="rltv">
                                <div class="pro_row_left">
                                    <?php //echo $this->Form->hidden('User.old_cv');   ?>
                                    <?php echo $this->Form->submit(__d('user', 'Update', true), array('div' => false, 'label' => false, 'class' => 'input_btn', 'id' => 'saveCreateButton')); ?>

                                    <?php echo $this->Html->link(__d('user', 'Cancel', true), array('controller' => 'candidates', 'action' => 'addVideoCv'), array('class' => 'input_btn rigjt', 'escape' => false, 'rel' => 'nofollow')); ?>
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
</div>
<script>
 function getExt(filename) {
        var dot_pos = filename.lastIndexOf(".");
        if (dot_pos == -1)
            return "";
        return filename.substr(dot_pos + 1).toLowerCase();
        }

        function in_array(needle, haystack) {
            for (var i = 0, j = haystack.length; i < j; i++) {
                if (needle == haystack[i])
                    return true;
            }
            return false;
        }

        function VideoValidation() {

            var filename = document.getElementById("upload_video").value; //mp4, 3gp, avi
            var filetype = ['mp4', '3gp', 'avi', 'mov'];
            if (filename != '') {
                var ext = getExt(filename);
                ext = ext.toLowerCase();
                var checktype = in_array(ext, filetype);
                if (!checktype) {
                    alert(ext + " <?php echo __d('user', 'file not allowed.', true); ?>");
                    document.getElementById("upload_video").value = '';
                    return false;
                } else {
                    var fi = document.getElementById('upload_video');
                    var filesize = fi.files[0].size;//check uploaded file size in bytes
                    var over_max_size = 20 * 1048576;
                    if (filesize > over_max_size) {
                        alert('Maximum 20MB '+" <?php echo __d('user', 'file size allowed for file.', true); ?>");
                        alert("<?php // echo __d('user', 'Maximum 40MB file size allowed for CV Document.', true); ?>");
                        document.getElementById("upload_video").value = '';
                        return false;
                    }
                }
            }
        }
</script>