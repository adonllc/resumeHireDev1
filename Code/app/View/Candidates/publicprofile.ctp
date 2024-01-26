<?php
echo $this->Html->script('jquery.validate.js');
$max_size = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant', 'max_size'));
?>
<script>
    $(document).ready(function () {
        $("#sendmail").validate();

    });
</script>
<style type="text/css">

    .top_bt_action ul li{display: inline-block; vertical-align: middle;}
    .top_bt_action ul li .back_bt_ini{margin-left: 5px;}
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<div class="inner_page_top_bg_null"></div>
<div class="clear"></div>
<div class="iner_pages_formate_box">
    <div class="wrapper">
        <br>
        <?php echo $this->element('session_msg'); ?>
        <div class="iner_form_bg_box ddprofile">
            <div class="inr_firm_roq_left inr_firm_roq_nabws_left">
                <div class="areal_img_box">
                    <?php
                    $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $userdetails['User']['profile_image'];
                    if (file_exists($path) && !empty($userdetails['User']['profile_image'])) {
                        ?>

                        <?php
                        echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_FULL_PROFILE_IMAGE_PATH . $userdetails['User']['profile_image'] . "&w=200&zc=1&q=100", array('escape' => false));
                    } else {
                        echo $this->Html->image('front/no_image_user.png', array('width' => '100%'));
                    }
                    ?>
                </div>
            </div>
            <div class="inr_firm_roq inr_firm_roq_nabws">
                <div class="top_page_name_box">
                    <div class="page_name_boox page_name_boox_small"><span><?php echo ucfirst($userdetails['User']['first_name']) . ' ' . ucfirst($userdetails['User']['last_name']); ?></span></div>
                    <div class="top_bt_action">
                        <ul>
                            <li>
<?php echo $this->Html->link(__d('user', 'Send Mail', true), 'javascript:void(0);', array('class' => 'back_navy', 'title' => 'Back', 'onclick' => 'sendmail()')); ?>
                            </li>
                            <li>
                                <?php
                                $fav_status = classregistry::init('Favorite')->find('first', array('conditions' => array('Favorite.user_id' => $this->Session->read('user_id'), 'Favorite.candidate_id' => $userdetails['User']['id'])));
                                if (empty($fav_status)) {
                                    echo $this->Html->link(__d('user', 'Add to Favorite', true), array('controller' => 'candidates', 'action' => 'addToFavorite', $userdetails['User']['slug']), array('class' => 'active'));
                                } else {
                                    echo '<i class="fa fa-star"></i>' . $this->Html->link(' ' . __d('user', 'Favorite', true), 'javascript:void(0);', array('class' => 'active already_added'));
                                }
                                ?>

                            </li>
                            <li>
                                <div class="back_bt_ini">
                                    <?php echo $this->Html->link('', 'javascript:history.back()', array('class' => 'back_navy fa fa-reply', 'title' => 'Back')); ?></div>
                            </li>
                        </ul>
                    </div>

                
                <div class="clear"></div>

                <div class="ful_row_ddta">
                    <span class="blue"><?php echo __d('user', 'Email Address', true); ?>:</span>
                    <span class="grey"><?php echo $userdetails['User']['email_address'] ? $userdetails['User']['email_address'] : "N/A"; ?></span>
                </div>

                <div class="ful_row_ddta">
                    <span class="blue"><?php echo __d('user', 'Contact Number', true); ?>:</span><span class="grey">
                        <?php
                        echo $userdetails['User']['contact'] ? $this->Text->usformat($userdetails['User']['contact']) : 'N/A';
                        ?>
                    </span>
                </div>
                <?php if($userdetails['User']['location'] !=''){ ?>
                    <div class="ful_row_ddta">
                        <span class="blue"><?php echo __d('user', 'Native Location', true); ?>:</span><span class="grey">
                            <?php
                            echo $userdetails['User']['location'] ? $userdetails['User']['location'] : 'N/A';
                            ?>
                        </span>
                    </div>
                <?php } ?>
                
                <?php if ($userdetails['User']['total_exp']) { ?>
                    <div class="ful_row_ddta">
                        <span class="blue"><?php echo __d('user', 'Work Experience', true); ?>: </span><span class="grey">
                            <?php
                            global $totalexperienceArray;
                            echo $totalexperienceArray[$userdetails['User']['total_exp']];
                            ?>
                        </span>
                    </div>
                <?php } ?>
                <?php if ($userdetails['User']['skills']) { ?>
                    <div class="ful_row_ddta">
                        <span class="blue"><?php echo __d('user', 'Skills', true); ?>: </span><span class="grey">
                            <?php
                            echo str_replace(',', ', ',$userdetails['User']['skills']);
                            ?>
                        </span>
                    </div>
                <?php } ?>
                <?php if ($userdetails['User']['interest_categories']) { ?>
                    <div class="ful_row_ddta">
                        <span class="blue"><?php echo __d('user', 'Interest Categories', true);?>: </span><span class="grey">
                            <?php
                            echo $interestCategories;
                            ?>
                        </span>
                    </div>
                <?php } ?>
                  <?php if ($userdetails['User']['url']) { ?>
                    <div class="ful_row_ddta">
                        <span class="blue"><?php echo __d('user', 'YouTube URL', true); ?>: </span><span class="grey">
                            
                            <a href="<?php echo $userdetails['User']['url'];?>" target="_blank"><?php echo $userdetails['User']['url'];?></a>
                        </span>
                    </div>
                <?php } ?>
                
                </div>
            </div>



            <div class="clear"></div>

            <?php if (isset($userdetails['Education']) && !empty($userdetails['Education'])) { ?>
                <div class="ful_row_ddta">
                    <span class="blue"><?php echo __d('user', 'Education', true); ?>:</span><span class="grey">
                    </span>
                </div>
                <?php
//                        if (isset($userdetails['Education']) && !empty($userdetails['Education'])) {
//                            $count = 1;
//                            foreach ($userdetails['Education'] as $education) {
//                                echo'<p> <b> Qualification  ' . $count . '</b></p>';
//                                echo'<p>Course name:  ' . $courseName = ClassRegistry::init('Course')->field('name', array('Course.id' => $education['basic_course_id'])) . '</p>';
//                                echo'<p>Specialization:  ' . $specialization = ClassRegistry::init('Specialization')->field('name', array('Specialization.id' => $education['basic_specialization_id'])) . '</p>';
//
//                                if (isset($education["basic_university"])) {
//                                    echo'<p>University:  ' . $education["basic_university"] . '</p>';
//                                } else {
//                                    echo'<p>University: N/A</p>';
//                                }
//
//                                if (isset($education["basic_year"])) {
//                                    echo'<p>Passed in:  ' . $education["basic_year"] . '</p>';
//                                } else {
//                                    echo'<p>Passed in: N/A</p>';
//                                }
//
//                                $count++;
//                            }
//                        }
                ?>

                <div style="overflow: auto;  clear: both; float: left; width: 100%;">
                    <div class="job_content" >
                        <ul class="job_heading">
                            <li><?php echo __d('user', 'Qualification', true); ?></li>
                            <li><?php echo __d('user', 'Course Name', true); ?></li>
                            <li><?php echo __d('user', 'Specialization', true); ?></li>
                            <li><?php echo __d('user', 'University/Institute', true); ?></li>
                            <li><?php echo __d('user', 'Passed', true); ?></li>
                        </ul>
                        <?php
                        $count = 1;
                        foreach ($userdetails['Education'] as $education) {
                            ?>

                            <ul class="job_list">
                                <li><?php echo $count; ?></li>
                                <li class="jobdi"><?php echo $courseName = ClassRegistry::init('Course')->field('name', array('Course.id' => $education['basic_course_id'])); ?></li>
                                <li><?php echo $specialization = ClassRegistry::init('Specialization')->field('name', array('Specialization.id' => $education['basic_specialization_id'])); ?> </li>
                                <li><?php
                                    if (isset($education["basic_university"])) {
                                        echo $education["basic_university"];
                                    } else {
                                        echo'N/A';
                                    }
                                    ?>
                                </li>
                                <li><?php
                                    if (isset($education["basic_year"])) {
                                        echo $education["basic_year"];
                                    } else {
                                        echo'N/A';
                                    }
                                    ?>
                                </li>
                            </ul>
                            <?php
                            $count++;
                        }
                        ?>
                    </div>
                </div>

            <?php }
            ?>

            <?php if (isset($userdetails['Experience']) && !empty($userdetails['Experience'])) { ?>
                <div class="ful_row_ddta" style="margin-top:20px;">
                    <span class="blue"><?php echo __d('user', 'Experience', true); ?>:</span><span class="grey">
                        
                    </span>

                </div>

                <div style="overflow: auto;  clear: both; float: left; width: 100%;">
                    <div class="job_content" >
                        <ul class="job_heading">
                            <li><?php echo __d('user', 'Experience', true); ?></li>
                            <li><?php echo __d('user', 'Company name', true); ?></li>
                            <li><?php echo __d('user', 'Functional area', true);?></li>
                            <li><?php echo __d('user', 'Industry', true); ?></li>
                            <li><?php echo __d('user', 'Role', true); ?></li>
                            <li><?php echo __d('user', 'Designation', true); ?></li>
                            <li><?php echo __d('user', 'Duration', true); ?></li>
                            <!--<li>&nbsp;</li>-->
                        </ul>
                        <?php
                        $count = 1;
                        foreach ($userdetails['Experience'] as $experience) {
                            ?>

                            <ul class="job_list">
                                <li><?php echo $count; ?></li>
                                <li class="jobdi"><?php echo $experience['company_name']; ?></li>
                                <li><?php echo $experience['industry']; ?> </li>
                                <li><?php
                                    if (isset($experience["functional_area"])) {
                                        echo $experience['functional_area'];
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </li>
                                <li><?php
                                    if (isset($experience["role"])) {
                                        echo $experience['role'];
                                    } else {
                                        echo'N/A';
                                    }
                                    ?>
                                </li>
                                <li><?php
                                    if (isset($experience["designation"])) {
                                        echo $experience['designation'];
                                    } else {
                                        echo'N/A';
                                    }
                                    ?>
                                </li>
                                <li><?php
                                    if (isset($experience["from_month"]) && isset($experience["from_year"]) && isset($experience["to_month"]) && isset($experience["to_year"])) {

                                        $experience['from_month'] == 1;
                                        switch ($experience['from_month']) {
                                                case "1":
                                                    $fromName = __d('user', 'January', true);
                                                    break;
                                                case "2":
                                                    $fromName = __d('user', 'Febuary', true);
                                                    break;
                                                case "3":
                                                    $fromName = __d('user', 'March', true);
                                                    break;
                                                case "4":
                                                    $fromName = __d('user', 'April', true);
                                                    break;
                                                case "5":
                                                    $fromName = __d('user', 'May', true);
                                                    break;
                                                case "6":
                                                    $fromName = __d('user', 'June', true);
                                                    break;
                                                case "7":
                                                    $fromName = __d('user', 'July', true);
                                                    break;
                                                case "8":
                                                    $fromName = __d('user', 'August', true);
                                                    break;
                                                case "9":
                                                    $fromName = __d('user', 'September', true);
                                                    break;
                                                case "10":
                                                    $fromName = __d('user', 'October', true);
                                                    break;
                                                case "11":
                                                    $fromName = __d('user', 'November', true);
                                                    break;
                                                case "12":
                                                    $fromName = __d('user', 'Decemeber', true);
                                                    break;
                                                default:
                                                    $fromName = 'N/A';
                                            }

                                        $experience['to_month'] == 1;
                                        switch ($experience['to_month']) {
                                                case "1":
                                                    $toName = __d('user', 'January', true);
                                                    break;
                                                case "2":
                                                    $toName = __d('user', 'Febuary', true);
                                                    break;
                                                case "3":
                                                    $toName = __d('user', 'March', true);
                                                    break;
                                                case "4":
                                                    $toName = __d('user', 'April', true);
                                                    break;
                                                case "5":
                                                    $toName = __d('user', 'May', true);
                                                    break;
                                                case "6":
                                                    $toName = __d('user', 'June', true);
                                                    break;
                                                case "7":
                                                    $toName = __d('user', 'July', true);
                                                    break;
                                                case "8":
                                                    $toName = __d('user', 'August', true);
                                                    break;
                                                case "9":
                                                    $toName = __d('user', 'September', true);
                                                    break;
                                                case "10":
                                                    $toName = __d('user', 'October', true);
                                                    break;
                                                case "11":
                                                    $toName = __d('user', 'November', true);
                                                    break;
                                                case "12":
                                                    $toName = __d('user', 'Decemeber', true);
                                                    break;
                                                default:
                                                    $toName = 'N/A';
                                            }

                                        echo $fromName . '-' . $experience['from_year'] . ' ' . __d('user', 'TO', true) . $toName . '-' . $experience['to_year'];
                                    } else {
                                        echo'N/A';
                                    }
                                    ?>
                                </li>
                                <!--                                    <li>
                                <?php
//                                        if (isset($experience["job_profile"])) {
//                                            echo'<p>Job profile:  ' . $experience["job_profile"] . '</p>';
//                                        } else {
//                                            echo'<p>Job profile: N/A</p>';
//                                        }
                                ?>
                                                                    </li>-->
                            </ul>
                            <?php
                            $count++;
                        }
                        ?>

                    </div></div>

            <?php }
            ?>
            
           
            <?php if ($userdetails['User']['company_about']) { ?>
                    <div class="clear"></div>
                    <div class="data_row_ful_skil">
                        <div class="data_row_ful_skil_header"> <?php echo __d('user', 'About Candidate', true); ?> </div>
                        <div class="clear"></div>
                        <div class="data_row_ful_skil_content">
                            <?php echo $userdetails['User']['company_about'];?>
                        </div>
                    </div>
                <?php } ?>

            <div class="clear"></div>
            <div class="data_row_ful_skil">
                <div class="data_row_ful_skil_header"> <?php echo __d('user', 'CV Document/Certificates', true); ?> </div>
                <div class="clear"></div>

                <div class="data_row_ful_skil_content">
                    <?php
//                    $curl = Router::url($this->params['url'], true);
//                    $_SESSION['curll'] = $curl;
                    if ($showOldImages || $showOldDocs) { ?>
                        <div class="all-uploaded-images">
                            <?php
                            foreach ($showOldDocs as $showOldDoc) {
                                $doc = $showOldDoc['Certificate']['document'];
                                if (!empty($doc) && file_exists(UPLOAD_CERTIFICATE_PATH . $doc)) {
                                    ?>
                                    <div id="<?php echo $showOldDoc['Certificate']['slug']; ?>" alt="" class="doc_fukll_name">

                                        <span class="temp-image-section">
                                            <i class="fa fa-file-pdf-o pdfDoc" aria-hidden="true"></i>
                                            <?php echo $this->Html->link(substr($doc, 6), array('controller' => 'candidates', 'action' => 'downloadDocCertificateEmp', $doc,$userdetails['User']['slug']), array('class' => 'dfasggs', 'rel' => 'nofollow')); ?>    
                                        </span>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <?php
                            foreach ($showOldImages as $showOldImage) {
                                $image = $showOldImage['Certificate']['document'];
                                if (!empty($image) && file_exists(UPLOAD_CERTIFICATE_PATH . $image)) {
                                    ?>
                                    <div id="<?php echo $showOldImage['Certificate']['slug']; ?>" alt="" class="image_thumb">
                                        <div class="download_bt_show_img" title="Download"><?php echo $this->Html->link('<i class="fa fa-download"></i>', array('controller' => 'candidates', 'action' => 'downloadDocCertificateEmp', $image, $userdetails['User']['slug']), array('escape' => false, 'rel' => 'nofollow')); ?></div>
                                        <span class="temp-image-section">
                                            <?php echo $this->Html->image(DISPLAY_CERTIFICATE_PATH . $image); ?>

                                        </span>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <?php
                    } else { ?>
                        <div class="all-uploaded-images">
                            <div class="check"> N/A</div>
                        </div>
                    <?php } ?>
                </div>

            </div>

            <div class="clear"></div>



            <div class="clear"></div>
            <?php if ($this->Session->read('user_type') != 'recruiter') { ?>
                <div class="job_save_btt">
                    <ul>
                        <li>
                            <?php
                            $short_status = classregistry::init('ShortList')->find('first', array('conditions' => array('ShortList.user_id' => $this->Session->read('user_id'), 'ShortList.job_id' => $userdetails['User']['id'])));
                            if (empty($short_status)) {
                                echo $this->Html->link('<i class="fa fa-star-o"></i> '. __d('user', 'Save User', true), array('controller' => 'jobs', 'action' => 'JobSave', $userdetails['User']['slug']), array('class' => 'sstar', 'escape' => false, 'rel' => 'nofollow'));
                            } else {
                                echo $this->Html->link('<i class="fa fa-star-o"></i> ' . __d('user', 'Already Saved', true), 'javascript:void(0);', array('class' => 'sstar', 'escape' => false, 'rel' => 'nofollow'));
                            }
                            ?>

                        </li>
                        <li>

                            <?php echo $this->Html->link('<i class="fa  fa-reply"></i>'. __d('user', 'Back To Jobs', true), array('controller' => 'jobs', 'action' => 'listing'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?>
                        </li>
                    </ul>
                </div>
            <?php } ?>

        </div>
    </div>
</div>

<script>
    function sendmail() {
        $('#sendmailpop').show();
    }
    function closepop() {
        $('#sendmailpop').hide();
    }
</script>

<div  id="sendmailpop" class="popupc" style="display: none">

<?php echo $this->Form->create('null', array('url' => array('controller' => 'candidates', 'action' => 'sendmailjobseeker', $userdetails['User']['slug']), 'enctype' => 'multipart/form-data', "method" => "Post", "id" => 'sendmail')); ?>

    <div class="nzwh-wrapper" style="height: 380px">

        <fieldset class="nzwh">

            <legend class="nzwh">
                <h2> <?php echo __d('user', 'Send Mail To', true) . ' ' . ucfirst($userdetails['User']['first_name']) . ' ' . ucfirst($userdetails['User']['last_name']); ?>  </h2>
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
<?php echo __d('user', 'Select multiple file with Ctrl press,', true).' '.__d('user', 'Supported File Types', true); ?>: gif, jpg, jpeg, png, pdf, doc, docx (Max 5 images and Max. <?php echo $max_size; ?>MB).

                    </div>
                </div>

                <div class="form-group">
                    <label class="">&nbsp;</label>
                    <div class="form-group-input">
                        <?php echo $this->Form->hidden('Candidate.id', array('value' => $userdetails['User']['id'])); ?>
<?php echo $this->Form->hidden('Candidate.slug', array('value' => $userdetails['User']['slug'])); ?>


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