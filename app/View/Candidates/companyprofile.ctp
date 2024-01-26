<div class="inner_page_top_bg_null"></div>
<div class="clear"></div>
<div class="iner_pages_formate_box">
    <div class="wrapper">
        <br>
        <?php echo $this->element('session_msg'); ?>
        <div class="iner_form_bg_box">

            <div class="newcleft">
            
            <div class="inr_firm_roq inr_firm_roq_nabws">
                <div class="top_page_name_title">
                    <div class="page_name_boox page_name_boox_small leftalign"><span><?php echo ucfirst($userdetails['User']['company_name']); ?></span>
                    <?php 
                    if($userdetails['User']['verify'] == 1){
                                                ?><span class="verifedleft" title="Verified"><?php echo $this->Html->image('front/verified_green.png'); ?></span><?php
                                            }
                    
                    ?>
                    </div>

                    <div class="top_bt_action">
                        <ul>
<!--                            <li>
                                <?php
                                $fav_status = classregistry::init('Favorite')->find('first', array('conditions' => array('Favorite.user_id' => $this->Session->read('user_id'), 'Favorite.candidate_id' => $userdetails['User']['id'])));
                                if (empty($fav_status)) {
                                    echo $this->Html->link(__d('user', 'Add to Favorite', true), array('controller' => 'candidates', 'action' => 'addToFavorite', $userdetails['User']['slug']), array('class' => 'active'));
                                } else {
                                    echo '<i class="fa fa-star"></i>' . $this->Html->link(' '. __d('user', 'Already Added', true), 'javascript:void(0);', array('class' => 'active already_added'));
                                }
                                ?>

                            </li>-->
<!--                            <li>
                                <div class="back_bt_ini">
                                    <?php echo $this->Html->link('', 'javascript:history.back()', array('class' => 'back_navy fa fa-reply', 'title' => 'Back')); ?></div>
                            </li>-->
                        </ul>
                    </div>

                </div>
                <div class="clear"></div>
                <div class="inpfil">
                <div class="ful_row_ddta">
                   
                        <?php
//                        pr($userdetails); exit;
                        echo $userdetails['User']['company_about'] ? $userdetails['User']['company_about'] : '';
                        ?>
                   
                </div>
                <?php 
                                            $userid = $this->Session->read('user_id');
                                                if($userid > 0){
                                            ?>
                <div class="ful_row_ddta">
                    <span class="blue"><i class="fa fa-phone"></i> <?php echo __d('user', 'Contact', true);?>:</span><span class="grey">
                        <?php
//                        pr($userdetails); exit;
                        echo $userdetails['User']['company_contact'] ? $this->Text->usformat($userdetails['User']['company_contact']) : 'N/A';
                        ?>
                    </span>
                </div>
                
                <div class="ful_row_ddta">
                    <span class="blue"><i class="fa fa-user"></i> <?php echo __d('user', 'Contact Name', true);?>:</span><span class="grey">
                        <?php
                       
                        echo $userdetails['User']['company_name'] ? $userdetails['User']['company_name'] : 'N/A';
                        ?>
                    </span>
                </div>
                                                <?php } ?>

                <div class="ful_row_ddta">
                    <span class="blue"><i class="fa fa-map-marker"></i> <?php echo __d('user', 'Location', true);?>:</span><span class="grey">
                        <?php
                        echo $userdetails['User']['address'] ? $userdetails['User']['address'].' '.$userdetails['User']['postal_code'] : 'N/A';
                        ?>
                    </span>
                </div>
                
            </div>
                
                <div class="clear"></div>
            <?php if ($jobsof) { ?>
                
                <div class="noft"><?php echo __d('user', 'Jobs posted by', true);?> <?php echo $userdetails['User']['company_name']  ?> </div>
                <div class="ekdiv nctyy">
                <?php   
                   $count = 1;
                foreach ($jobsof as $job) {
            ClassRegistry::init('Job')->updateJobSearch($job['Job']['id']);

            if ($job['Job']['type'] == 'gold') {
                $class = 'listing_full_row_bg';
            } else {
                $class = '';
            }
            ?>
            <!--right filter Section starts-->
            <div class="listing_full_row <?php echo $class; ?> ">
                <div class="">
                    <div class="lisint_box_title">
                        <?php echo $this->Html->link($job['Job']['title'], array('controller' => 'jobs', 'action' => 'detail', 'cat' => $job['Category']['slug'], 'slug' => $job['Job']['slug'], 'ext' => 'html')); ?>

                        <div class="listing_boxbfg">
                            <div class="open_bt">
                                <?php //echo $this->Html->link('Details', array('controller' => 'jobs', 'action' => 'detail', 'cat' => $job['Category']['slug'], 'slug' => $job['Job']['slug'], 'ext' => 'html'), array('class' => 'sstar', 'target' => '_blank')); ?>
                                <?php echo $this->Html->link(__d('user', 'Details', true), array('controller' => 'jobs', 'action' => 'detail', 'cat' => $job['Category']['slug'], 'slug' => $job['Job']['slug'], 'ext' => 'html'), array('class' => 'sstar')); ?>
                                <?php
//                                if ($this->Session->read('user_type') != 'recruiter') {
//
//                                    if ($this->Session->read('user_id')) {
//                                        $apply_status = classregistry::init('JobApply')->find('first', array('conditions' => array('JobApply.user_id' => $this->Session->read('user_id'), 'JobApply.job_id' => $job['Job']['id'])));
//                                        if (empty($apply_status)) {
//                                            //echo $this->Html->link('Apply Now', 'javascript:void(0);', array('class' => 'sstar', 'onclick' => 'jobApplyConfitm();'));
//                                            echo '<a href="#confirmPopup' . $job["Job"]["id"] . '" rel="facebox" class = "sstar">Apply Now</a>';
//                                        } else {
//                                            echo $this->Html->link('Already Applied', 'javascript:void(0);', array('class' => 'sstar'));
//                                        }
//                                    } else {
//
//                                        echo $this->Html->link('Apply Now', array('controller' => 'jobs', 'action' => 'jobApply', $job['Job']['slug']), array('class' => 'sstar'));
//                                    }
//                                }
                                ?>

                            </div>
                        </div>
                    </div> 
                    <div class="company_div"><?php echo $job['Job']['company_name'] ? $job['Job']['company_name'] : 'N/A'; ?></div>
                    <div class="list_location_box">
                        <span class="listing_loc_exp"> <b> <i class="fa fa-briefcase" aria-hidden="true"></i></b>  <!--<?php //echo substr($job['City']['city_name'] . ', ' . $job['State']['state_name'] . ', ' . $job['Job']['postal_code'], 0, 40);                                          ?>--> 
                            &nbsp; <?php
                            if ($job['Job']['max_exp'] > 15) {
                                echo $job['Job']['min_exp'] . ' - ' . __d('user', 'more than 15 years', true);
                            } else {
                                echo $job['Job']['min_exp'] . ' - ' . $job['Job']['max_exp'] . ' '.__d('user', 'yrs', true);
                            }
                            ?>

                        </span>
                        <span class="listing_loc_exp"> <b> <i class="fa fa-map-marker" aria-hidden="true"></i></b>  <!--<?php //echo substr($job['City']['city_name'] . ', ' . $job['State']['state_name'] . ', ' . $job['Job']['postal_code'], 0, 40);                                          ?>--> 
                            &nbsp; <?php echo $job['Job']['job_city'] ? $job['Job']['job_city'] : 'N/A'; ?></span>

                    </div>  
                    <div class="data_row_ful_skil_content2">

                        <div class="big_desc"><span class="first_span"><?php echo __d('user', 'Keyskills', true);?> :</span>
                            <span class="second_span"><?php
                                $jobskill = ClassRegistry::init('Job')->field('skill', array('Job.id' => $job['Job']['id']));
                                $jobId = explode(',', $jobskill);
                                $i = 1;
                                foreach ($jobId as $id) {
                                    $skill = ClassRegistry::init('Skill')->field('name', array('Skill.id' => $id));

                                    if (!empty($skill)) {
                                        if ($i == 1) {
                                            echo $skill;
                                        } else {
                                            echo " , " . $skill;
                                        }
                                        $i = $i + 1;
                                    } else {
                                        echo"N/A";
                                    }
                                }
                                ?>
                            </span>

                        </div>

                        <div class="big_desc"><span class="first_span"><?php echo __d('user', 'Designation', true);?>:</span>
                            <span class="second_span">
                                <?php
                                $jobDesignation = ClassRegistry::init('Job')->field('designation', array('Job.id' => $job['Job']['id']));
                                // pr($jobDesignation);
                                $designation = ClassRegistry::init('Skill')->field('name', array('Skill.id' => $jobDesignation, 'Skill.type' => 'Designation'));
                                if (!empty($designation)) {
                                    echo $designation;
                                } else {
                                    echo 'N/A';
                                }
                                ?>

                            </span>

                        </div>



                        <?php
                        if (!empty($job['Job']['description'])) {

                            if (str_word_count($job['Job']['description']) > 10) {
                                ?>
                                <div class="big_desc">
                                    <span class="first_span"><?php echo __d('user', 'Job Description', true);?>:</span>
                                    <span class="second_span" style="text-align: justify;">
                                        <?php
                                        $descriptionJob = $this->Text->truncate($job['Job']['description'], 260, array('html' => true));
                                        echo strip_tags($descriptionJob);
                                        /*  if (str_word_count($job['Job']['description']) > 10 && str_word_count($job['Job']['description']) < 50) {
                                          $pos = strpos($job['Job']['description'], ' ', 50);
                                          echo substr($job['Job']['description'], 0, $pos) . '...';
                                          } elseif (str_word_count($job['Job']['description']) > 51 && str_word_count($job['Job']['description']) < 100) {
                                          $pos = strpos($job['Job']['description'], ' ', 100);
                                          echo substr($job['Job']['description'], 0, $pos) . '...';
                                          } elseif (str_word_count($job['Job']['description']) > 101 && str_word_count($job['Job']['description']) < 120) {
                                          $pos = strpos($job['Job']['description'], ' ', 100);
                                          echo substr($job['Job']['description'], 0, $pos) . '...';
                                          } elseif (str_word_count($job['Job']['description']) > 121 && str_word_count($job['Job']['description']) < 150) {
                                          $pos = strpos($job['Job']['description'], ' ', 130);
                                          echo substr($job['Job']['description'], 0, $pos) . '...';
                                          } else {
                                          $pos = strpos($job['Job']['description'], ' ', 250);
                                          echo substr($job['Job']['description'], 0, $pos) . '...';
                                          } */
                                        ?>
                                    </span>  
                                    <?php
                                    /*    $industry = ClassRegistry::init('Industry')->field('name', array('Industry.id' => $job['User']['industry']));
                                      if ($industry) {
                                      echo $industry;
                                      } else {
                                      echo 'N/A';
                                      } */
                                    ?>

                                </div>
                                <?php
                            }
                        }
                        ?>

                    </div>     
                    <div class="list_bot_boox list_socials">
                        <div class="list_bot_boox_table">
                            <div class="list_bot_boox_row">
                                <div class="list_bot_boox_col">

                                    <?php //echo $this->Html->image('front/dolor_icn.png', array('alt' => 'icon'));  ?>
                                    <span><?php echo CURRENCY . ' ' . intval($job['Job']['min_salary']) . " - " .CURRENCY . ' ' . intval($job['Job']['max_salary']); ?></span>
                                </div>

                                <div class="list_bot_boox_col">
                                    <i class="fa fa-bookmark" aria-hidden="true"></i>
                                   
                                    <span>
                                        <?php
                                        global $worktype;
                                        echo $job['Job']['work_type'] ? $worktype[$job['Job']['work_type']] : 'N/A';
                                        ?>
                                    </span>
                                </div>

                                <div class="list_bot_boox_col">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                    <span><?php echo date('F j, Y', strtotime($job['Job']['created'])); //date('jS F,Y h:i A'              ?></span>
                                </div>
                                <div class="list_bot_boox_col">
                                    <div class="savejob">
                                        <?php
                                        if ($this->Session->read('user_type') != 'recruiter') {
                                            $short_status = classregistry::init('ShortList')->find('first', array('conditions' => array('ShortList.user_id' => $this->Session->read('user_id'), 'ShortList.job_id' => $job['Job']['id'])));
                                            if (empty($short_status)) {
                                                //echo $this->Html->link(' Save Job', array('controller' => 'jobs', 'action' => 'JobSave', $job['Job']['slug']), array('class' => 'sstar', 'escape' => false,'rel'=>'nofollow'));

//                                                echo $this->Html->image("front/savejob.png", array(
//                                                    "alt" => "icon",
//                                                    'url' => array('controller' => 'jobs', 'action' => 'JobSave', $job['Job']['slug'])
//                                                ));
                                                echo' <i class="fa fa-star-o" aria-hidden="true"></i><span>' . $this->Html->link(' '.__d('user', 'Save Job', true), array('controller' => 'jobs', 'action' => 'JobSave', $job['Job']['slug']), array('class' => 'sstar', 'escape' => false, 'rel' => 'nofollow')) . '</span>';
                                            } else {
                                                //echo' <span>' . $this->Html->link(' Save Job', array('controller' => 'jobs', 'action' => 'JobSave', $job['Job']['slug']), array('class' => 'sstar', 'escape' => false,'rel'=>'nofollow')) .'</span>';
//                                                echo $this->Html->image("front/savejob2.png", array(
//                                                    "alt" => "icon",
//                                                ));
                                                echo $this->Html->link('<i class="fa fa-star" aria-hidden="true"></i> <span>'.__d('user', 'Already Saved', true).'</span>', 'javascript:void(0);', array('class' => 'sstar', 'escape' => false, 'rel' => 'nofollow'));
                                            }
                                        }
                                        ?>
                                       <!-- <a href="#"><?php //echo $this->Html->image('front/savejob.png', array('alt' => 'icon'));                                 ?><span>Save Job</span></a></div>-->

                                    </div>
                                </div>

                                <div class="list_bot_boox_col">
                                    <div class="addthis_button addthis_button_mar" addthis:url="<?php echo HTTP_PATH . '/' . $job['Category']['slug'] . '/' . $job['Job']['slug'] . '.html' ?>">
                                        <i class="fa fa-share-alt"></i><?php echo __d('user', 'Share to a friend', true);?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                       
                </div>
                <div class="listing_col_right">
                    <div class="open_deta_bt">

                        <div class="open_bt detail_bt detail_bt_nee">
                            <!--<div class="addthis_button addthis_button_mar" addthis:url="<?php //echo HTTP_PATH . '/jobs/detail/' . $job['Job']['slug']                                          ?>"><i class="fa fa-share-alt"></i>
                                Share to a friend</div>-->

                            <?php
                            // if ($this->Session->read('user_type') != 'recruiter') {
                            //Save Job 
                            /* $short_status = classregistry::init('ShortList')->find('first', array('conditions' => array('ShortList.user_id' => $this->Session->read('user_id'), 'ShortList.job_id' => $job['Job']['id'])));
                              if (empty($short_status)) {
                              echo $this->Html->link(' Save Job', array('controller' => 'jobs', 'action' => 'JobSave', $job['Job']['slug']), array('class' => 'sstar', 'escape' => false,'rel'=>'nofollow'));
                              } else {
                              echo $this->Html->link(' Already Saved', 'javascript:void(0);', array('class' => 'sstar', 'escape' => false,'rel'=>'nofollow'));
                              } */




                            /* if ($this->Session->read('user_id')) {
                              $apply_status = classregistry::init('JobApply')->find('first', array('conditions' => array('JobApply.user_id' => $this->Session->read('user_id'), 'JobApply.job_id' => $job['Job']['id'])));
                              if (empty($apply_status)) {
                              //echo $this->Html->link('Apply Now', 'javascript:void(0);', array('class' => 'sstar', 'onclick' => 'jobApplyConfitm();'));
                              echo '<a href="#confirmPopup'. $job["Job"]["id"] .'" rel="facebox" class = "sstar">Apply Now</a>';
                              } else {
                              echo $this->Html->link('Already Applied', 'javascript:void(0);', array('class' => 'sstar'));
                              }
                              } else {
                              echo $this->Html->link('Apply Now', array('controller' => 'jobs', 'action' => 'jobApply', $job['Job']['slug']), array('class' => 'sstar'));
                              } */


                            //}
                            ?>
                        </div>
                    </div>
                </div>

            </div>
            <!--right filter Section ends-->
            <!-------------------popup box start------------>
            <?php if ($this->Session->read('user_id') != '') { ?>

                <div id="confirmPopup<?php echo $job["Job"]["id"] ?>" style="display: none;">
                    <!-- Fieldset -->

                    <?php echo $this->Form->create('null', array('url' => array('controller' => 'jobs', 'action' => 'jobApply', 'slug' => $job['Job']['slug']), 'enctype' => 'multipart/form-data', "method" => "Post")); ?>

                    <div class="nzwh-wrapper">

                        <fieldset class="nzwh">

                            <legend class="nzwh"><h2> <?php echo __d('user', 'Job Application Confirmation', true);?>  </h2></legend>

                            <?php
                            $optionNotification = classregistry::init('CoverLetter')->find('list', array('conditions' => array('CoverLetter.user_id' => $this->Session->read('user_id')), 'fields' => array('CoverLetter.id', 'CoverLetter.title')));
                            ?>
                            <div class="drt">
                                <span class="fdsf"> <?php echo __d('user', 'Please Select the Cover Letter.', true);?></span>
                            </div>
                            <div class="drt">
                                <div class="radio-inline">
                                    <?php
                                    if (!empty($optionNotification)) {

                                        $default = min(array_keys($optionNotification));
                                        $attributes = array('default' => $default, 'legend' => false, 'hiddenField' => false, 'label' => false, 'class' => 'radiobtn', 'separator' => '</div><div class="radio-inline">');
                                        echo $this->Form->radio('JobApply.cover_letter', $optionNotification, $attributes);
                                    } else {
                                        echo __d('user', 'Please add a cover letter or apply without cover letter.', true);
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <!--   <div class="drt">
                                   <span class="fdsf"> Please Select the CV document/Certificate.</span>
                               </div>
                               <div class="drt">
                                   <div class="radio-inline dbl_check">
                            <?php /*   if ($showOldImages) {
                              $option = array();
                              foreach ($showOldImages as $key => $value) {
                              $option[$key] = $this->Html->link(substr($value, 6), array('controller' => 'candidates', 'action' => 'downloadDocCertificate', $value), array('class' => 'dfasggs'));
                              ;
                              }
                              echo $this->Form->input('JobApply.attachment_ids', array('class' => '', 'multiple' => 'checkbox', 'escape' => false,'rel'=>'nofollow', 'options' => $option, 'legend' => false, 'div' => false, 'label' => false));
                              } else {
                              ?>
                              No CV document/Certificate Found.
                              <?php } */ ?>
               
                                   </div>
                               </div> -->
                            <div class="clear"></div>
                            <?php echo $this->Form->hidden('Job.slug', array('value' => $job['Job']['slug'])); ?>
                            <?php
                            if (empty($optionNotification)) {
                                echo $this->Form->hidden('Job.cover_letter', array('value' => 0));
                            }
                            ?>


                            <?php echo $this->Form->submit(__d('user', 'Submit', true), array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>
                            <?php echo $this->Html->link(__d('user', 'Add Cover Letter', true), array('controller' => 'candidates', 'action' => 'editProfile/' . 'return'), array('class' => 'input_btn rigjt add_cover_letter_bt', 'escape' => false, 'rel' => 'nofollow')); ?>

                        </fieldset>

                    </div>
                    <?php echo $this->Form->end(); ?> 
                </div>

            <?php } ?>

            <!-----------------popup box end------------------------>

            <?php
            $count++;
        } ?>
                    </div>
            <?php } ?>
            </div>
        </div>
            <div class="newcright">
        
                <div class="areal_img_box">
                    <?php
                    $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $userdetails['User']['profile_image'];
                    if (file_exists($path) && !empty($userdetails['User']['profile_image'])) {
                        ?>

                        <?php
                        echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_FULL_PROFILE_IMAGE_PATH . $userdetails['User']['profile_image'] . "&w=200&zc=1&q=100", array('escape' => false));
                    } else {
                       
                        echo $this->Html->image('front/skyline.svg', array('width' => '100%'));
                    }
                    ?>
                </div>
           
           
        </div>
        </div>
        
    </div>
</div>
