<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function () {   
        $("#sendmail").validate();
    });

</script>
<div class="my_accnt">
    <?php 
    //echo $this->element('user_menu');
    $max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'max_size'));
    ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu'); ?>
                <div class="col-sm-9 col-lg-9 col-xs-12">
                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-payments-boxes"><h2><i><?php echo $this->Html->image('front/home/mailhistory-icon2.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Mail Detail', true); ?></span></h2>

                        </div>
                        <?php echo $this->Session->flash(); ?>
                        <div class="information_cntn">
                            <?php echo $this->element('session_msg'); ?>    

                            <div class="listing_page">

                                <div class="jjj" id="job2" >
                                    <div class="search_full">
                                        <?php
                                        $site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'title'));
                                        $enquiry_mail = $this->requestAction(array('controller' => 'App', 'action' => 'getMailConstant', 'enquiry_mail'));

                                        $logoImage = classRegistry::init('Admin')->field('Admin.logo', array('id' => 1));

                                        if (isset($logoImage) && !empty($logoImage)) {
                                            $logo = DISPLAY_THUMB_WEBSITE_LOGO_PATH . $logoImage;
                                        } else {
                                            $logo = ' ';
                                        }
                                        if ($mailDetail['Mail']['job_id']) {
                                            if ($userdetail['User']['id'] == $mailDetail['Company']['id']) {
                                                ?>
                                                <span class="reply_mail">
                                                    <?php echo $this->Html->link(__d('common', 'Reply', true), 'javascript:void(0);', array('class' => 'active', 'onclick' => 'sendmail()')); ?>
                                                </span>
                                                <?php
                                            }
                                        } elseif ($userdetail['User']['id'] == $mailDetail['Sender']['id']) {
                                            ?>
                                            <span class="reply_mail">
                                                <?php echo $this->Html->link(__d('common', 'Reply', true), 'javascript:void(0);', array('class' => 'active', 'onclick' => 'sendmail()')); ?>
                                            </span>
                                        <?php } ?>
                                        <table width="90%" align="center">
                                            <tbody>
                                                <tr>
                                                    <td valign="top">
                                                        <!-- Begin Middle Content -->
                                                        <table width="100%">
                                                            <tbody>
                                                                <tr><td>




                                                                        <?php
                                                                        $files = explode(',', $mailDetail['Mail']['files']);
                                                                        
                                                                        if ($mailDetail['Mail']['job_id']) {
                                                                            $cslug = $jobInfo['Category']['slug'];
                                                                            $jobslug = $jobInfo['Job']['slug'];
                                                                             
                                                                            ?> 
                                                                            <table width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td style="color:#434343; font:bold 15px Arial, Helvetica, sans-serif; margin:0; padding:10px 0 0;"><b style="color:#000000;"><?php echo __d('user', 'Dear', true); ?></b> <?php echo ucwords($mailDetail['Company']['first_name'] . ' ' . $mailDetail['Company']['last_name']) ?>,</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="color:#434343; font-size:13px; line-height:18px;">
                                                                                            <p style="color:#434343; margin:10px 0 0;"><?php echo __d('user', 'A jobseeker applied for a job', true); ?> <?php echo $mailDetail['Job']['title'] ?> <?php echo __d('user', 'on', true); ?> <?php echo $site_title; ?>.<?php echo __d('user', 'Here is the detail', true); ?>:-</p>
                                                                                            <p style="color:#434343; margin:10px 0 0;"><strong style="width:150px;"><?php echo __d('user', 'Jobseeker Name', true); ?>:</strong> <?php echo ucwords($mailDetail['Sender']['first_name'] . ' ' . $mailDetail['Sender']['last_name']) ?></p>
                                                                                            <p style="color:#434343; margin:10px 0 0;"><strong style="width:150px;"><?php echo __d('user', 'Email Address', true); ?>:</strong> <?php echo $mailDetail['Sender']['email_address'] ?></p>
                                                                                            <p style="color:#434343; margin:10px 0 0;"><a href="<?php echo HTTP_PATH . '/candidates/profile/' . $mailDetail['Sender']['slug'] ?>" style="color:#0d4f87; "><?php echo __d('user', 'Click here', true); ?></a> <?php echo __d('user', 'to jobseeker details', true); ?>.</p>
                                                                                            <?php
                                                                                            if ($files) {
                                                                                                echo '<p class="mail_download" style="color:#434343; margin:10px 0 0;">';
                                                                                               foreach ($files as $file) { 
                                                                                                   
                                                                                                  if ($file && file_exists(UPLOAD_MAIL_PATH . $file)) { 
                                                                                                    $fileName = DISPLAY_MAIL_PATH . $file;
                                                                                                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                                                                                                    global $extentions;
                                                                                                    if (in_array($ext, $extentions)) {
                                                                                                        echo $this->Html->link('<i class="fa fa-download" aria-hidden="true"></i>'.$this->Html->image(PHP_PATH . "timthumb.php?src=" . $fileName . "&w=120&h=120&zc=2&q=100"), array('controller' => 'candidates', 'action' => 'downloadMailFile',$file), array('class' => '', 'escape' => false, 'rel' => 'nofollow'));
                                                                                                    } elseif ($ext == 'pdf') {
                                                                                                        $fileName = HTTP_IMAGE."/front/pdf_large.png";
                                                                                                        echo $this->Html->link('<i class="fa fa-download" aria-hidden="true"></i>'.$this->Html->image(PHP_PATH . "timthumb.php?src=" . $fileName . "&w=120&h=120&zc=2&q=100"), array('controller' => 'candidates', 'action' => 'downloadMailFile',$file), array('class' => '', 'escape' => false, 'rel' => 'nofollow'));
                                                                                                    } else {
                                                                                                        $fileName = HTTP_IMAGE."/front/doc_large.png";
                                                                                                        echo $this->Html->link('<i class="fa fa-download" aria-hidden="true"></i>'.$this->Html->image(PHP_PATH . "timthumb.php?src=" . $fileName . "&w=120&h=120&zc=2&q=100"), array('controller' => 'candidates', 'action' => 'downloadMailFile',$file), array('class' => '', 'escape' => false, 'rel' => 'nofollow'));
                                                                                                    }
                                                                                                }
                                                                                                }
                                                                                                echo '</p>';
                                                                                            }
                                                                                            ?>

                                                                                        </td>
                                                                                    </tr>

                                                                                </tbody></table>
                                                                        <?php } elseif ($mailDetail['Sender']['user_type'] == 'recruiter') { ?>
                                                                            <table width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td style="color:#434343; font:bold 15px Arial, Helvetica, sans-serif; margin:0; padding:10px 0 0;"><b style="color:#000000;"><?php echo __d('user', 'Dear', true); ?></b> <?php echo ucwords($mailDetail['Sender']['first_name'] . ' ' . $mailDetail['Sender']['last_name']) ?>,</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="color:#434343; font-size:13px; line-height:18px;">
                                                                                            <p style="color:#434343; margin:10px 0 0;"><?php echo __d('user', 'An jobseeker send you the mail on', true); ?> <?php echo $site_title; ?>. <?php echo __d('user', 'Here is the detail', true); ?>:-</p>

                                                                                            <p style="color:#434343; margin:10px 0 0;"><?php echo __d('user', 'User Name', true); ?> : <?php echo ucwords($mailDetail['Company']['first_name'] . ' ' . $mailDetail['Company']['last_name']) ?></p>
                                                                                            <p style="color:#434343; margin:10px 0 0;"><?php echo __d('user', 'Email Address', true); ?> : <?php echo $mailDetail['Company']['email_address'] ?></p>
                                                                                            <p style="color:#434343; margin:10px 0 0;"><?php echo __d('user', 'Subject', true); ?> : <?php echo $mailDetail['Mail']['subject'] ?></p>
                                                                                            <p style="color:#434343; margin:10px 0 0;"><?php echo __d('user', 'Message', true); ?> : <?php echo $mailDetail['Mail']['message'] ?></p>
                                                                                            <?php
                                                                                            if ($files) {
                                                                                                
                                                                                                echo '<p class="mail_download" style="color:#434343; margin:10px 0 0;">';
                                                                                                foreach ($files as $file) {
                                                                                                   
                                                                                                    if ($file && file_exists(UPLOAD_MAIL_PATH . $file)) {
                                                                                                    $fileName = DISPLAY_MAIL_PATH . $file;
                                                                                                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                                                                                                    global $extentions;
                                                                                                    if (in_array($ext, $extentions)) {
                                                                                                        echo $this->Html->link('<i class="fa fa-download" aria-hidden="true"></i>'.$this->Html->image(PHP_PATH . "timthumb.php?src=" . $fileName . "&w=120&h=120&zc=2&q=100"), array('controller' => 'candidates', 'action' => 'downloadMailFile',$file), array('class' => '', 'escape' => false, 'rel' => 'nofollow'));
                                                                                                    } elseif ($ext == 'pdf') {
                                                                                                        $fileName = HTTP_IMAGE."/front/pdf_large.png";
                                                                                                        echo $this->Html->link('<i class="fa fa-download" aria-hidden="true"></i>'.$this->Html->image(PHP_PATH . "timthumb.php?src=" . $fileName . "&w=120&h=120&zc=2&q=100"), array('controller' => 'candidates', 'action' => 'downloadMailFile',$file), array('class' => '', 'escape' => false, 'rel' => 'nofollow'));
                                                                                                    } else {
                                                                                                        $fileName = HTTP_IMAGE."/front/doc_large.png";
                                                                                                        echo $this->Html->link('<i class="fa fa-download" aria-hidden="true"></i>'.$this->Html->image(PHP_PATH . "timthumb.php?src=" . $fileName . "&w=120&h=120&zc=2&q=100"), array('controller' => 'candidates', 'action' => 'downloadMailFile',$file), array('class' => '', 'escape' => false, 'rel' => 'nofollow'));
                                                                                                    }
                                                                                                }
                                                                                                }
                                                                                                echo '</p>';
                                                                                            }
                                                                                            ?>



                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        <?php } else { ?>
                                                                            <table width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td style="color:#434343; font:bold 15px Arial, Helvetica, sans-serif; margin:0; padding:10px 0 0;"><b style="color:#000000;"><?php echo __d('user', 'Dear', true); ?></b> <?php echo ucwords($mailDetail['Sender']['first_name'] . ' ' . $mailDetail['Sender']['last_name']) ?>,</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="color:#434343; font-size:13px; line-height:18px;">
                                                                                            <p style="color:#434343; margin:10px 0 0;"><?php echo __d('user', 'An employer send you the mail on', true); ?> <?php echo $site_title; ?>. <?php echo __d('user', 'Here is the detail', true); ?>:-</p>

                                                                                            <p style="color:#434343; margin:10px 0 0;"><?php echo __d('user', 'Company name', true); ?> : <?php echo ucwords($mailDetail['Company']['company_name']) ?></p>
                                                                                            <p style="color:#434343; margin:10px 0 0;"><?php echo __d('user', 'Email Address', true); ?> : <?php echo $mailDetail['Company']['email_address'] ?></p>
                                                                                            <p style="color:#434343; margin:10px 0 0;"><?php echo __d('user', 'Subject', true); ?> : <?php echo $mailDetail['Mail']['subject'] ?></p>
                                                                                            <p style="color:#434343; margin:10px 0 0;"><?php echo __d('user', 'Message', true); ?> : <?php echo $mailDetail['Mail']['message'] ?></p>
                                                                                            <?php
                                                                                            if ($files) {
                                                                                                echo '<p class="mail_download" style="color:#434343; margin:10px 0 0;">';
                                                                                                foreach ($files as $file) {
//                                                                                                    print_r($file);die;
                                                                                                    if ($file && file_exists(UPLOAD_MAIL_PATH . $file)) {
                                                                                                    $fileName = DISPLAY_MAIL_PATH . $file;
                                                                                                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                                                                                                    global $extentions;
                                                                                                    if (in_array($ext, $extentions)) {
                                                                                                        echo $this->Html->link('<i class="fa fa-download" aria-hidden="true"></i>'.$this->Html->image(PHP_PATH . "timthumb.php?src=" . $fileName . "&w=120&h=120&zc=2&q=100"), array('controller' => 'candidates', 'action' => 'downloadMailFile',$file), array('class' => '', 'escape' => false, 'rel' => 'nofollow'));
                                                                                                    } elseif ($ext == 'pdf') {
                                                                                                        $fileName = HTTP_IMAGE."/front/pdf_large.png";
                                                                                                        echo $this->Html->link('<i class="fa fa-download" aria-hidden="true"></i>'.$this->Html->image(PHP_PATH . "timthumb.php?src=" . $fileName . "&w=120&h=120&zc=2&q=100"), array('controller' => 'candidates', 'action' => 'downloadMailFile',$file), array('class' => '', 'escape' => false, 'rel' => 'nofollow'));
                                                                                                    } else {
                                                                                                        $fileName = HTTP_IMAGE."/front/doc_large.png";
                                                                                                        echo $this->Html->link('<i class="fa fa-download" aria-hidden="true"></i>'.$this->Html->image(PHP_PATH . "timthumb.php?src=" . $fileName . "&w=120&h=120&zc=2&q=100"), array('controller' => 'candidates', 'action' => 'downloadMailFile',$file), array('class' => '', 'escape' => false, 'rel' => 'nofollow'));
                                                                                                    }
                                                                                                }
                                                                                                }
                                                                                                echo '</p>';
                                                                                            }
                                                                                            ?>



                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        <?php } ?> 









                                                                    </td></tr>

                                                            </tbody>
                                                        </table>
                                                        <!-- End Middle Content --> 
                                                    </td>
                                                </tr>




                                            </tbody>
                                        </table>         

                                    </div>
                                </div>
                            </div>
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function changeTab(id) {
        $('.ttt').removeClass('active');
        $('.jjj').hide('');
        $('#job' + id).show();
        $('#jobtab' + id).addClass('active');
    }

</script>




<script>
    function sendmail() {
        $('#sendmailpop').show();
    }
    function closepop() {
        $('#sendmailpop').hide();
    }
</script>

<div  id="sendmailpop" class="popupc" style="display: none">

    <?php echo $this->Form->create('null', array('url' => array('controller' => 'candidates', 'action' => 'sendmailjobseeker', $mailDetail['Company']['slug']), 'enctype' => 'multipart/form-data', "method" => "Post", "id" => 'sendmail')); ?>

    <div class="nzwh-wrapper" style="height: 380px">

        <fieldset class="nzwh">

            <legend class="nzwh">
                <h2> <?php echo __d('common', 'Reply Mail', true); ?>  </h2>
                <div class="close-btn"><?php echo $this->Html->image('close.png', array('alt' => '', "onclick" => 'closepop()')); ?></div>
            </legend>

            <div class="clear"></div>
            <div class="form-proflis">
                <div class="form-group">
                    <label class=""> <?php echo __d('user', 'Subject', true); ?><span class="star_red">*</span></label>
                    <div class="form-group-input">
                        <?php echo $this->Form->text('Candidate.subject', array('class' => "form-control required", 'placeholder' => __d('user', 'Subject', true))) ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class=""> <?php echo __d('user', 'Message', true); ?><span class="star_red">*</span></label>
                    <div class="form-group-input">
                        <?php echo $this->Form->textarea('Candidate.message', array('class' => "form-control required", 'placeholder' => __d('user', 'Message', true))) ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class=""> <?php echo __d('user', 'Multiple Files', true); ?></label>
                    <div class="form-group-input">
                        <?php echo $this->Form->input('Candidate.files', ['name' => 'data[Candidate][files][]', 'onchange' => 'imageValidation()', 'label' => false, 'type' => 'file', 'div' => false, 'class' => 'form-control', 'multiple' => 'multiple', 'id' => 'JobLogo']); ?>
                        <br>
                        <?php echo __d('user', 'Select multiple file with Ctrl press,', true) . ' ' . __d('user', 'Supported File Types', true); ?>: gif, jpg, jpeg, png, pdf, doc, docx (Max 5 images and Max. <?php echo $max_size; ?>MB).

                    </div>
                </div>

                <div class="form-group">
                    <label class="">&nbsp;</label>
                    <div class="form-group-input">
                        <?php echo $this->Form->hidden('Candidate.id', array('value' => $mailDetail['Company']['id'])); ?>
                        <?php echo $this->Form->hidden('Candidate.slug', array('value' => $mailDetail['Company']['slug'])); ?>
                        <?php echo $this->Form->hidden('Candidate.type', array('value' => 'users')); ?>


                        <?php echo $this->Form->submit(__d('user', 'Submit', true), array('div' => false, 'label' => false, 'class' => 'input_btn', 'id' => 'logbtn')); ?>
                    </div>
                </div>
            </div>
        </fieldset>

    </div>
    <?php echo $this->Form->end(); ?> 
    <div class="vv" onclick="closepopJob()"></div>
</div>

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

        var filename = document.getElementById("JobLogo").value;
        var filetype = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
        if (filename != '') {
            var ext = getExt(filename);
            ext = ext.toLowerCase();
            var checktype = in_array(ext, filetype);
            if (!checktype) {
                alert(ext + " file not allowed for file.");
                document.getElementById("JobLogo").value = '';
                return false;
            } else {
                var fi = document.getElementById('JobLogo');
                var filesize = fi.files[0].size;//check uploaded file size
                var over_max_size = <?php echo $max_size ?> * 1048576;
                if (filesize > over_max_size) {
                    alert('Maximum <?php echo $max_size; ?>MB file size allowed for file.');
                    document.getElementById("JobLogo").value = '';
                    return false;
                }
            }
        }
    }

    $("#JobLogo").on('change', function () {

        //Get count of selected files
        var countFiles = $(this)[0].files.length;
        if (countFiles > 5) {
            alert('You can upload maximum 5 images.');
            $("#JobLogo").val('');
            return;
        }

    });
</script>