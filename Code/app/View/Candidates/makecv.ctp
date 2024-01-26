<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                <div class="col-lg-9 col-sm-9">
                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top make-cv-boxes-top"><h2><i><?php echo $this->Html->image('front/home/cv-icon-top.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Curriculum Vitae of', true);?> <?php echo $userdetail['User']['first_name'] ?> <?php echo $userdetail['User']['last_name']; ?></span></h2></div>
                        <div class="information_cntn information_cntn_cv">
                            <?php echo $this->element('session_msg'); ?>  
                            <div class="onceclm">
                                <div class="mack-cv-profileimg"> 
                                    <?php if ($userdetail['User']['profile_image'] == "") { ?>
                                        <?php if ($userdetail['User']['location']) { ?>
                                            <div class="form_lstcv nobold">
                                                <label><?php echo __d('user', 'Address', true);?>: <?php echo $userdetail['User']['pre_location']; ?></label>
                                            </div>
                                        <?php } ?>
                                        <?php
                                    } else {
                                        $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $userdetail['User']['profile_image'];
                                        if (file_exists($path) && !empty($userdetail['User']['profile_image'])) {
                                            echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $userdetail['User']['profile_image'], array('escape' => false, 'rel' => 'nofollow', 'class' => '_fffdff'), array('class' => ' '));
                                        } else {
                                            ?>
                                            <div class="form_lstcv nobold">
                                                <label><?php echo __d('user', 'Address', true);?>: <?php echo $userdetail['Location']['name']; ?></label>
                                            </div>    
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="liftdi">
                                    <div class="form_lstcv_names">
                                        <label><?php echo $userdetail['User']['first_name']; ?> <?php echo $userdetail['User']['last_name']; ?></label>
                                    </div>
                                    <div class="form_lstcv nobold">
                                        <label><i class="fa fa-envelope-o" aria-hidden="true"></i><?php echo $userdetail['User']['email_address']; ?></label>
                                    </div>
                                    <?php if ($userdetail['User']['contact']) { ?>
                                        <div class="form_lstcv nobold">
                                            <label><?php echo __d('user', '<i class="fa fa-phone" aria-hidden="true"></i>', true);?><?php echo $userdetail['User']['contact']; ?></label>
                                            <span class="rltv"><em> </em></span>
                                        </div>
                                    <?php } ?>
                                    <?php if ($userdetail['User']['profile_image'] != "") { ?>

                                        <?php if ($userdetail['User']['location']) { ?>
                                            <div class="form_lstcv nobold">
                                                <label><?php echo __d('user', '<i class="fa fa-map-marker" aria-hidden="true"></i>', true);?><?php echo $userdetail['User']['location']; ?></label>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="maind"><?php echo __d('home', 'Education', true);?></div>
                            <?php
                            //pr($userdetail);
                            if (isset($userdetail['Education']) && !empty($userdetail['Education'])) {
                                ?><ul>
                                    <?php
                                    foreach ($userdetail['Education'] as $education) {
                                        $couses[] = $education['basic_course_id'];
                                        ?><li><?php echo __d('user', 'I have Passed', true);?> <?php echo $courseName = ClassRegistry::init('Course')->field('name', array('Course.id' => $education['basic_course_id'])); ?> <?php echo __d('user', 'in', true);?> <?php
                                            if (isset($education["basic_year"])) {
                                                echo $education["basic_year"];
                                            } else {
                                                echo'N/A';
                                            }
                                            ?>
                                            <?php echo __d('user', 'in', true);?> <?php echo $specialization = ClassRegistry::init('Specialization')->field('name', array('Specialization.id' => $education['basic_specialization_id'])); ?> <?php echo __d('user', 'from', true);?> <?php
                                            if (isset($education["basic_university"])) {
                                                echo $education["basic_university"];
                                            } else {
                                                echo'N/A';
                                            }
                                            ?>.</li> 
                                        <?php
                                    }
                                    ?>
                                </ul>
                            <?php } else {
                                ?>
                                <div class="asdasd">
                                    <?php echo $this->Html->link( __d('user', 'Complete your education qualification to make a CV', true), array('controller' => 'candidates', 'action' => 'editEducation'), array('class' => 'input_btn rigjt', 'escape' => false, 'rel' => 'nofollow')); ?>
                                </div>    
                            <?php }
                            ?>
                            <div class="maind"><?php echo __d('user', 'Experience', true);?></div>
                            <?php if (isset($userdetail['Experience']) && !empty($userdetail['Experience'])) { ?>
                                <ul>
                                    <?php
                                    foreach ($userdetail['Experience'] as $experience) {
                                        ?><li><?php echo __d('user', 'I have worked as a', true);?> <?php
                                            if (isset($experience["role"])) {
                                                echo $experience['role'];
                                            } else {
                                                echo'N/A';
                                            }
                                            ?> <?php
                                            if (isset($experience["designation"])) {
                                                echo $experience['designation'];
                                            } else {
                                                echo'N/A';
                                            }
                                            ?> <?php echo __d('user', 'for', true);?> <?php echo $experience['company_name']; ?> <?php echo __d('user', 'since', true);?> <?php
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

                                                echo $fromName . '-' . $experience['from_year'] . ' '.__d('common', 'to', true).' ' . $toName . '-' . $experience['to_year'];
                                            } else {
                                                echo'N/A';
                                            }
                                            ?>
                                            <ul class="ntydd">
                                                <?php if ($experience['industry'] != "") {
                                                    ?>  <li class="asdasd"><label><?php echo __d('user', 'Industry', true);?>: </label> <?php echo $experience['industry']; ?> </li><?php }
                                                ?>
                                                <?php if ($experience['functional_area'] != "") {
                                                    ?>  <li class="asdasd"><label><?php echo __d('user', 'Functional area', true);?>: </label> <?php echo $experience['functional_area']; ?> </li><?php }
                                                ?>
                                                    <?php if ($experience['role'] != "") {
                                                        ?>  <li class="asdasd"><label><?php echo __d('user', 'Role', true);?>: </label> <?php echo $experience['role']; ?> </li><?php }
                                                    ?>
                                                    <?php if ($experience['job_profile'] != "") {
                                                        ?>  <li class="asdasd"><label><?php echo __d('user', 'Job Profile', true);?>: </label> <?php echo $experience['job_profile']; ?> </li><?php }
                                                    ?>
                                            </ul>     
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <?php ?>

                            <?php } else {
                                ?>
                                <div class="asdasd">
                                    <?php echo $this->Html->link( __d('user', 'Complete your experience details to make a CV', true), array('controller' => 'candidates', 'action' => 'editExperience'), array('class' => 'input_btn rigjt', 'escape' => false, 'rel' => 'nofollow')); ?>
                                </div>    
                            <?php }
                            ?>
                            <div class="onceclm martyy">
                                <div class="liftdi">
                                    <div class="form_lstcv">
                                        <label><?php echo __d('user', 'Date', true);?>: <?php echo date('m/d/Y'); ?></label>
                                    </div>
                                </div>
                                <div class="roghtdi roghtdi-cv"> 
                                    <div class="form_lstcv">
                                        <label><?php echo __d('user', 'Signature', true);?></label>
                                    </div>
                                </div>
                            </div>
                            <?php if (isset($userdetail['Experience']) && !empty($userdetail['Experience']) && isset($userdetail['Education']) && !empty($userdetail['Education'])) {
                                ?>
                            <div class="mackcv-btns">
                                <div class="pdf-btn-bx">
                                    <?php echo $this->Html->link($this->Html->image('front/home/pdf-icon.png').' '.__d('user', 'Generate CV', true), array('controller' => 'candidates', 'action' => 'generatecv'), array('class' => 'currant-upplan', 'escape' => false, 'rel' => 'nofollow')); ?>
                                </div>    
                                <div class="doc-btn">
                                    <?php echo $this->Html->link($this->Html->image('front/home/doc-icon.png').' '.__d('user', 'Generate CV', true), array('controller' => 'candidates', 'action' => 'generatecvdoc'), array('class' => 'currant-upplan pdf-btn', 'escape' => false, 'rel' => 'nofollow')); ?>
                                </div>  
                                </div>
                                <?php
                            }
                            ?>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


