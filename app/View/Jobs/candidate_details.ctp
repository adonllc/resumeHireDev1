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
        <div class="iner_form_bg_box">

            <div class="inr_firm_roq_left inr_firm_roq_nabws_left">
                <div class="areal_img_box">
                    <?php
                    $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $userdetails['User']['profile_image'];
                    if (file_exists($path) && !empty($userdetails['User']['profile_image'])) {
                        ?>

                        <?php
                        echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_FULL_PROFILE_IMAGE_PATH . $userdetails['User']['profile_image'] . "&w=200&zc=1&q=100", array('escape' => false, 'rel' => 'nofollow'));
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
                                <?php
                                $fav_status = classregistry::init('Favorite')->find('first', array('conditions' => array('Favorite.user_id' => $this->Session->read('user_id'), 'Favorite.candidate_id' => $userdetails['User']['id'])));
                                if (empty($fav_status)) {
                                    echo $this->Html->link(__d('user', 'Add to Favorite', true), array('controller' => 'candidates', 'action' => 'addToFavorite', $userdetails['User']['slug']), array('class' => 'active'));
                                } else {
                                    echo '<i class="fa fa-star"></i>' . $this->Html->link(' '.__d('user', 'Already Added', true), 'javascript:void(0);', array('class' => 'active already_added'));
                                }
                                ?>

                            </li>
                            <li>
                                <div class="back_bt_ini">
                                    <?php echo $this->Html->link('', 'javascript:history.back()', array('class' => 'back_navy fa fa-reply', 'title' => 'Back')); ?></div>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="clear"></div>

                <div class="ful_row_ddta">
                    <span class="blue"><?php echo __d('user', 'Email Address', true);?>:</span>
                    <span class="grey"><?php echo $userdetails['User']['email_address'] ? $userdetails['User']['email_address'] : "N/A"; ?></span>
                </div>

                <div class="ful_row_ddta">
                    <span class="blue"><?php echo __d('user', 'Contact', true);?>:</span><span class="grey">
                        <?php
                        echo $userdetails['User']['contact'] ?$this->Text->usformat( $userdetails['User']['contact']) : 'N/A';
                        ?>
                    </span>
                </div>

                <div class="ful_row_ddta">
                    <span class="blue"><?php echo __d('user', 'Location', true);?>:</span><span class="grey">
                        <?php
                        echo $userdetails['Location']['name'] ? $userdetails['Location']['name'] : 'N/A';
                        ?>
                    </span>
                </div>
            </div>
            <div class="clear"></div>

            <div class="full_row_div">
                <!--                <div class="ful_row_ddta">
                                    <span class="blue">First Name :</span><span class="grey"><?php //echo ucfirst($userdetails['User']['first_name']);          ?></span>
                                </div>
                                <div class="ful_row_ddta">
                                    <span class="blue">Last Name:</span>
                                    <span class="grey"><?php //echo ucfirst($userdetails['User']['last_name']);          ?></span>
                                </div>-->



                <?php if (isset($userdetails['Education']) && !empty($userdetails['Education'])) { ?>
                    <div class="ful_row_ddta">
                        <span class="blue"><?php echo __d('user', 'Education', true);?>:</span><span class="grey">
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
                                <li><?php echo __d('user', 'Qualification', true);?></li>
                                <li><?php echo __d('user', 'Course Name', true);?></li>
                                <li><?php echo __d('user', 'Specialization', true);?></li>
                                <li><?php echo __d('user', 'University', true);?></li>
                                <li><?php echo __d('user', 'Passed', true);?></li>
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
                        <span class="blue"><?php echo __d('user', 'Experience', true);?>:</span><span class="grey">
                            <?php
//                        if (isset($userdetails['Experience']) && !empty($userdetails['Experience'])) {
//                            $count = 1;
//                            foreach ($userdetails['Experience'] as $experience) {
//                                echo'<p> <b> Experience  ' . $count . '</b></p>';
//                                echo'<p>Company name:  ' . $experience['company_name'] . '</p>';
//                                echo'<p>Industry:  ' . $experience['industry'] . '</p>';
//
//                                if (isset($experience["functional_area"])) {
//                                    echo'<p>Functional area:  ' . $experience['functional_area'] . '</p>';
//                                } else {
//                                    echo'<p>Functional area: N/A</p>';
//                                }
//
//                                if (isset($experience["role"])) {
//                                    echo'<p>Role:  ' . $experience['role'] . '</p>';
//                                } else {
//                                    echo'<p>Role: N/A</p>';
//                                }
//
//                                if (isset($experience["designation"])) {
//                                    echo'<p>Designation:  ' . $experience['designation'] . '</p>';
//                                } else {
//                                    echo'<p>Designation: N/A</p>';
//                                }
//
//                                if (isset($experience["from_month"]) && isset($experience["from_year"]) && isset($experience["to_month"]) && isset($experience["to_year"])) {
//
//                                    $experience['from_month'] == 1;
//                                    switch ($experience['from_month']) {
//                                        case "1":
//                                            $fromName = 'January';
//                                            break;
//                                        case "2":
//                                            $fromName = 'Febuary';
//                                            break;
//                                        case "3":
//                                            $fromName = 'March';
//                                            break;
//                                        case "4":
//                                            $fromName = 'April';
//                                            break;
//                                        case "5":
//                                            $fromName = 'May';
//                                            break;
//                                        case "6":
//                                            $fromName = 'June';
//                                            break;
//                                        case "7":
//                                            $fromName = 'July';
//                                            break;
//                                        case "8":
//                                            $fromName = 'August';
//                                            break;
//                                        case "9":
//                                            $fromName = 'September';
//                                            break;
//                                        case "10":
//                                            $fromName = 'October';
//                                            break;
//                                        case "11":
//                                            $fromName = 'November';
//                                            break;
//                                        case "12":
//                                            $fromName = 'Decemeber';
//                                            break;
//                                        default:
//                                            $fromName = 'N/A';
//                                    }
//
//                                    $experience['to_month'] == 1;
//                                    switch ($experience['to_month']) {
//                                        case "1":
//                                            $toName = 'January';
//                                            break;
//                                        case "2":
//                                            $toName = 'Febuary';
//                                            break;
//                                        case "3":
//                                            $toName = 'March';
//                                            break;
//                                        case "4":
//                                            $toName = 'April';
//                                            break;
//                                        case "5":
//                                            $toName = 'May';
//                                            break;
//                                        case "6":
//                                            $toName = 'June';
//                                            break;
//                                        case "7":
//                                            $toName = 'July';
//                                            break;
//                                        case "8":
//                                            $toName = 'August';
//                                            break;
//                                        case "9":
//                                            $toName = 'September';
//                                            break;
//                                        case "10":
//                                            $toName = 'October';
//                                            break;
//                                        case "11":
//                                            $toName = 'November';
//                                            break;
//                                        case "12":
//                                            $toName = 'Decemeber';
//                                            break;
//                                        default:
//                                            $toName = 'N/A';
//                                    }
//
//                                    echo'<p>Duration:  from ' . $fromName, $experience['from_year'] . ' to ' . $toName, $experience['to_year'] . '</p>';
//                                } else {
//                                    echo'<p>Duration: N/A</p>';
//                                }
//
//                                if (isset($experience["job_profile"])) {
//                                    echo'<p>Job profile:  ' . $experience["job_profile"] . '</p>';
//                                } else {
//                                    echo'<p>Job profile: N/A</p>';
//                                }
//
//                                $count++;
//                            }
//                        }
                            ?>
                        </span>

                    </div>

                    <div style="overflow: auto;  clear: both; float: left; width: 100%;">
                        <div class="job_content" >
                            <ul class="job_heading">
                                <li><?php echo __d('user', 'Experience', true);?></li>
                                <li><?php echo __d('user', 'Company name', true);?></li>
                                <li><?php echo __d('user', 'Industry', true);?></li>
                                <li><?php echo __d('user', 'Functional area', true);?></li>
                                <li><?php echo __d('user', 'Role', true);?></li>
                                <li><?php echo __d('user', 'Designation', true);?></li>
                                <li><?php echo __d('user', 'Duration', true);?></li>
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

                                            echo $fromName . '-' . $experience['from_year'] . ' to ' . $toName . '-' . $experience['to_year'];
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

            </div>


            <div class="clear"></div>
            <div class="data_row_ful_skil">
                <div class="data_row_ful_skil_header"> <?php echo __d('user', 'CV Document/Certificates', true);?>  </div>
                <div class="clear"></div>

                <div class="data_row_ful_skil_content">
                    <?php if ($showOldImages || $showOldDocs) { ?>
                        <div class="all-uploaded-images">
                            <?php
                            foreach ($showOldDocs as $showOldDoc) {
                                $doc = $showOldDoc['Certificate']['document'];
                                if (!empty($doc) && file_exists(UPLOAD_CERTIFICATE_PATH . $doc)) {
                                    ?>
                                    <div id="<?php echo $showOldDoc['Certificate']['slug']; ?>" alt="" class="doc_fukll_name">

                                        <span class="temp-image-section">
                                            <i class="fa fa-file-pdf-o pdfDoc" aria-hidden="true"></i>
                                            <?php echo $this->Html->link(substr($doc, 6), array('controller' => 'candidates', 'action' => 'downloadDocCertificate', $doc), array('class' => 'dfasggs')); ?>    
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
                                        <div class="download_bt_show_img" title="Download"><?php echo $this->Html->link('<i class="fa fa-download"></i>', array('controller' => 'candidates', 'action' => 'downloadImage', $image), array('escape' => false, 'rel' => 'nofollow')); ?></div>
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
                    } else {
                        ?>
                        <div class="all-uploaded-images">
                            <div class="check"> N/A</div>
                        </div>
                    <?php } ?>
                </div>

            </div>
   <div class="clear"></div>
                     <?php
            $video = $userdetails['User']['video'];
            if (!empty($video) && file_exists(UPLOAD_VIDEO_PATH . $video)) {
                $extension = pathinfo(UPLOAD_VIDEO_PATH . $video,PATHINFO_EXTENSION );
//                echo $extension; 
                ?>
                <div class="clear"></div>
                <div class="data_row_ful_skil">
                    <div class="data_row_ful_skil_header"> <?php echo __d('user', 'Video', true); ?> </div>
                    <div class="clear"></div>
                    <div class="data_row_ful_skil_content">
                        <video width="420" height="240" controls>
                            <source src="<?php echo DISPLAY_VIDEO_PATH . $video ?>" type="video/<?php echo $extension ?>">
                            <!--Your browser does not support the video tag.-->
                        </video>
                    </div>
                </div>
<?php } ?>

            <div class="clear"></div>
            <?php if ($this->Session->read('user_type') != 'recruiter') { ?>
                <div class="job_save_btt">
                    <ul>
                        <li>
                            <?php
                            $short_status = classregistry::init('ShortList')->find('first', array('conditions' => array('ShortList.user_id' => $this->Session->read('user_id'), 'ShortList.job_id' => $userdetails['User']['id'])));
                            if (empty($short_status)) {
                                echo $this->Html->link('<i class="fa fa-star-o"></i> '.__d('user', 'Save User', true), array('controller' => 'jobs', 'action' => 'JobSave', $userdetails['User']['slug']), array('class' => 'sstar', 'escape' => false, 'rel' => 'nofollow'));
                            } else {
                                echo $this->Html->link('<i class="fa fa-star-o"></i> '.__d('user', 'Already Saved', true), 'javascript:void(0);', array('class' => 'sstar', 'escape' => false, 'rel' => 'nofollow'));
                            }
                            ?>

                        </li>
                        <li>

                            <?php echo $this->Html->link('<i class="fa  fa-reply"></i>'.__d('user', 'Back To Jobs', true), array('controller' => 'jobs', 'action' => 'listing'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?>
                        </li>
                    </ul>
                </div>
            <?php } ?>

        </div>
    </div>
</div>
