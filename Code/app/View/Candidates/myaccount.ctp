<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="container">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                <div class="col-xs-12 col-sm-6 col-md-9 col-lg-9">
                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top">
                            <h2><i><?php echo $this->Html->image('front/home/profile-icon.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Profile', true);?></span></h2>
                            <?php echo $this->Html->link(__d('user', '<i class="fa fa-pencil"></i>Edit', true), array('controller' => 'candidates', 'action' => 'editProfile'), array('class' => '', 'escape' => false,'rel'=>'nofollow')); ?>
                          
                        </div>
                        <div class="my-profile-boxes-mddel">
                        <div class="my-profile-boxes-detail">
                            <?php $info = Classregistry::init('User')->findById($this->Session->read('user_id'));?>  
                            <div class="my-profile-img"><?php
                            $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $info['User']['profile_image'];
                            if (file_exists($path) && !empty($info['User']['profile_image'])) {
                                ?>

                                <?php
                                echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_FULL_PROFILE_IMAGE_PATH . $info['User']['profile_image'] . "&w=200&zc=1&q=100", array('escape' => false));

                            } else {
                                echo $this->Html->image('front/no_image_user.png');
                            }
                ?></div>
                            <div class="my-profile-names"><?php echo $userdetail['User']['first_name']; ?> <?php echo $userdetail['User']['last_name']; ?></div>
                        </div>
                            <div class="users-informetion-detal con-left-bx"><i><?php echo $this->Html->image('front/home/phone-icon.png', array('alt' => '')); ?></i> <span><?php echo $userdetail['User']['contact']; ?></span></div>
                            <div class="users-informetion-detal"><i><?php echo $this->Html->image('front/home/massege-icon.png', array('alt' => '')); ?></i> <span><?php echo $userdetail['User']['email_address']; ?></span></div>
                            <div class="users-location-detal"><i><?php echo $this->Html->image('front/home/user-location-icon.png', array('alt' => '')); ?></i> <span><?php echo $userdetail['User']['location']; ?></span></div>
                        </div>
                    </div>
                    
                    
                     <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-education-boxes">
                            <h2><i><?php echo $this->Html->image('front/home/education-icon.png', array('alt' => '')); ?></i><span>Education</span></h2>
                            <?php echo $this->Html->link(__d('user', '<i class="fa fa-pencil"></i>Edit', true), array('controller' => 'candidates', 'action' => 'editEducation'), array('class' => '', 'escape' => false,'rel'=>'nofollow')); ?>
                          
                        </div>
                        
                        <div class="my-profile-boxes-mddel">
                            <ul class="condidet-skills">
                            <?php if(!empty($educationDetails)){ 
                                foreach($educationDetails as $educationDetail){?>
                                    <li><?php echo 'I have completed '.$educationDetail['Course']['name'].''.(!empty($educationDetail['Specialization']['name'])? '('.$educationDetail['Specialization']['name'].')':' ').' from '.$educationDetail['Education']['basic_university'].' on '.$educationDetail['Education']['basic_year']; ?></li>
                            <?php } }else{
                                ?>
                                    <li><?php echo 'No Record Found ';?></li>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                    
                    
                    
                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-experience-boxes">
                            <h2><i><?php echo $this->Html->image('front/home/experience-icon.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Experience', true);?></span></h2>
                            <?php echo $this->Html->link(__d('user', '<i class="fa fa-pencil"></i>Edit', true), array('controller' => 'candidates', 'action' => 'editExperience'), array('class' => '', 'escape' => false,'rel'=>'nofollow')); ?>
                          
                        </div>
                        <div class="my-profile-boxes-mddel">
                            <?php if(!empty($experienceDetails)){ 
                                foreach($experienceDetails as $experienceDetail){?>    
                                    <ul class="condidet-skills">
                                    <?php   $fromMonthNum  = $experienceDetail['Experience']['from_month'];
                                            $toMonthNum  = $experienceDetail['Experience']['to_month'];
                                           
                                            // $fromYearNum  = $experienceDetails['Education']['from_year'];
                                            // $toYearNum  = $experienceDetails['Education']['to_year'];
                                           
                                            $dateObj  = DateTime::createFromFormat('!m', $fromMonthNum);
                                            $fromMonth = $dateObj->format('F'); // March

                                            $dateObj  = DateTime::createFromFormat('!m', $toMonthNum);
                                            $toMonth = $dateObj->format('F'); // March

                                    ?>
                                        <li>I have worked as a <?php echo $experienceDetail['Experience']['role']; ?> with <?php echo $experienceDetail['Experience']['company_name']; ?> from <?php echo $fromMonth ?>-<?php echo $experienceDetail['Experience']['from_year']; ?> to <?php echo $toMonth ?>-<?php echo  $experienceDetail['Experience']['to_year']; ?> </li>
                                        <li>Industry: <?php echo $experienceDetail['Experience']['industry']; ?></li>
                                        <li>Functional area: <?php echo $experienceDetail['Experience']['functional_area']; ?></li>
                                        <li>Role: <?php echo $experienceDetail['Experience']['role']; ?></li>
                                        <span>----------------</span>
                                    </ul>
                            <?php } }else{
                                ?>
                                    <ul class="condidet-skills">
                                        <li> No Record Found.</li>
                                    </ul>
                                <?php }?>
                        </div>
                    </div>
                    
                    
                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-skills-boxes">
                            <h2><i><?php echo $this->Html->image('front/home/skills-icon.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Skills', true);?></span></h2>
                            <?php echo $this->Html->link(__d('user', '<i class="fa fa-pencil"></i>Edit', true), array('controller' => 'candidates', 'action' => 'editProfile'), array('class' => '', 'escape' => false,'rel'=>'nofollow')); ?>
                          
                        </div>
                        <div class="my-profile-boxes-mddel">
                            <ul class="condidet-skills-user">
                                <?php if(!empty($userdetail['User']['skills'])){
                                    foreach(explode(',',$userdetail['User']['skills']) as $skills_set){?>
                                    <li><?php echo $skills_set ?></li>
                                <?php } }else{?>
                                    <li><?php echo 'No Record Found'; ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-experience-boxes">
                            <h2><i><?php //echo $this->Html->image('front/home/licenses-icon.png', array('alt' => '')); ?></i><span>Licenses & Certifications</span></h2>
                            <?php //echo $this->Html->link(__d('user', '<i class="fa fa-pencil"></i>Edit', true), array('controller' => 'candidates', 'action' => 'editProfile'), array('class' => '', 'escape' => false,'rel'=>'nofollow')); ?>
                          
                        </div>
                        <div class="my-profile-boxes-mddel">
                            <ul class="condidet-skills-user">
                                <li>Best Developer</li>
                            </ul>
                        </div>
                    </div> -->
                    
                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-aboutself-boxes">
                            <h2><i><?php echo $this->Html->image('front/home/about-icon.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'About Your Self', true);?></span></h2>
                            <?php echo $this->Html->link(__d('user', '<i class="fa fa-pencil"></i>Edit', true), array('controller' => 'candidates', 'action' => 'editProfile'), array('class' => '', 'escape' => false,'rel'=>'nofollow')); ?>
                          
                        </div>
                        <div class="my-profile-boxes-mddel">
                            <p><?php echo ($userdetail['User']['company_about'])? $userdetail['User']['company_about']: 'No Description Found' ?></p>
                        </div>
                    </div>
                    
                     <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-current-boxes">
                            <h2><i><?php echo $this->Html->image('front/home/current-plan-icon.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Current Plan', true);?></span></h2>
                            
                          
                        </div>
                        <div class="my-profile-boxes-mddel">
                           
                                         <?php
                                           $cplan = Classregistry::init('Plan')->getcurrentplan($userdetail['User']['id']);
                                           if($cplan){
                                               echo '<span class="fertcd CurrentPlan">'.$cplan['Plan']['plan_name'].'</span>';
                                               echo $this->Html->link(__d('user', 'Upgrade Plan', true), array('controller'=>'plans', 'action'=>'purchase'), array('class'=>'currant-upplan'));
                                           }else{
                                               echo $this->Html->link(__d('user', 'Purchase Plan', true), array('controller'=>'plans', 'action'=>'purchase'), array('class'=>'currant-upplan noplanmy'));
                                           }
                                           
                                         ?>
                        </div>
                        
                            <?php if($cplan){ ?>
                               <div class="my-profile-boxes-mddel">
                                    <label><?php echo __d('user', 'Available Plan Features', true);?></label>
                                    <span class="form_list_jobseekars plan_list_jobseekars">
                                         
                                           <ol>
                                                 <li>Plan Time Period - <?php echo $cplan['UserPlan']['type_value'].' '.$cplan['UserPlan']['type']; ?></li>
                                                <li>Number of Job for Apply  - <?php echo $getRemainingFeatures['availableAppliedCount'] ? $getRemainingFeatures['availableAppliedCount'] : 'N/A'; ?></li>
                                                </ol>
                                    </span>
                                        
                                </div>
                                <?php } ?>
                                
                                
                    </div>
                     <div class="my-profile-boxes aboutself-boxes">
                        <div class="my-profile-boxes-top my-aboutself-boxes">
                            <h2><i><?php echo $this->Html->image('front/home/cv-icon.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'CV Document/Certificates', true);?></span></h2>
                        </div>
                        <div class="my-profile-boxes-mddel">
                        <?php if ($showOldImages || $showOldDocs) { 
                            foreach ($showOldDocs as $showOldDoc) {
                                $doc = $showOldDoc['Certificate']['document'];
                                if (!empty($doc) && file_exists(UPLOAD_CERTIFICATE_PATH . $doc)) {
                                    $docs_array = ['doc','docx'];
                                    $pdf = ['pdf'];
                                    if(in_array(pathinfo($doc, PATHINFO_EXTENSION),$pdf)){
                                 ?>
                                    <a href="<?php echo $this->Html->url(array('controller' => 'candidates', 'action' => 'downloadDocCertificate', $doc)); ?>" class="currant-upplan pdf-btn"><i><?php echo $this->Html->image('front/home/pdf-icon.png', array('alt' => '')); ?></i><span><?php echo substr($doc,6); //document53.PDF?></span></a>
                                <?php }else{?>  
                                    <a href="<?php echo $this->Html->url(array('controller' => 'candidates', 'action' => 'downloadDocCertificate', $doc)); ?>" class="currant-upplan"><i><?php echo $this->Html->image('front/home/doc-icon.png', array('alt' => '')); ?></i><span><?php echo substr($doc,6); //document53.docx ?></span></a>
                                <?php } 
                                } 
                            } 
                        }?>
                        </div>
                    </div>
                    <div class="account-servise-btn">
                        <?php echo $this->Html->link(''.__d('common', 'Delete Account', true), array('controller' => 'candidates', 'action' => 'deleteAccount'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?>
                    <?php echo $this->Html->link(''.__d('user','Change Password',true), array('controller' => 'candidates', 'action' => 'changePassword'), array('class' => '', 'escape' => false, 'rel' => 'nofollow')); ?>
                    </div>
                    
                    
                    <div class="info_dv" style="display:none">
                        <div class="heads"><?php echo __d('user', 'My Profile', true);?></div>
                        <div class="information_cntn">
                            <?php echo $this->element('session_msg'); ?>    
                            <div class="form_lst">
                                <label><?php echo __d('user', 'Email Address', true);?></label>
                                <?php //echo"<pre>"; print_r($userdetail);  ?>
                                <span class="rltv"><em><?php echo $userdetail['User']['email_address']; ?> </em></span>
                            </div>
                            <div class="form_lst">
                                <label><?php echo __d('user', 'First Name', true);?></label>
                                <span class="rltv"><em><?php echo $userdetail['User']['first_name']; ?> </em></span>
                            </div>
                            <div class="form_lst">
                                <label><?php echo __d('user', 'Last Name', true);?></label>
                                <span class="rltv"><em><?php echo $userdetail['User']['last_name']; ?> </em></span>
                            </div>
                                
                           <!--state, city, postal code--->
                           <!-- <?php //if ($userdetail['City']['city_name']) { ?>
                            <div class="form_lst">
                                <label>City</label>
                                <span class="rltv"><em><?php //echo $userdetail['City']['city_name']; ?> </em></span>
                            </div>
                            <?php //} ?>

                            <?php //if ($userdetail['State']['state_name']) { ?>
                            <div class="form_lst">
                                <label>State</label>
                                <span class="rltv"><em><?php //echo $userdetail['State']['state_name']; ?> </em></span>
                            </div>
                            <?php //} ?>

                            <?php //if ($userdetail['User']['postal_code']) { ?>
                            <div class="form_lst">
                                <label>Postcode</label>
                                <span class="rltv"><em><?php //echo $userdetail['User']['postal_code']; ?> </em></span>
                            </div>
                            <?php //} ?>-->
                            
                            <?php if ($userdetail['User']['location']) { ?>
                            <div class="form_lst">
                                <label><?php echo __d('user', 'Native Location', true);?></label>
                                <span class="rltv"><em><?php echo $userdetail['User']['location']; ?> </em></span>
                            </div>
                            <?php } ?>
                            <?php if ($userdetail['User']['gender'] !='') { ?>
                            <div class="form_lst">
                                <label><?php echo __d('user', 'Gender', true);?></label>
                                <span class="rltv"><em><?php if($userdetail['User']['gender'] == 0){
                                    echo __d('user', 'Male', true);
                                }else{
                                    echo __d('user', 'Female', true);
                                }?> </em></span>
                            </div>
                            <?php } ?>

                            <?php if ($userdetail['User']['contact']) { ?>
                            <div class="form_lst">
                                <label><?php echo __d('user', 'Contact Number', true);?></label>
                                <span class="rltv"><em><?php echo $userdetail['User']['contact']; ?> </em></span>
                            </div>
                            <?php } ?>
                            
                            <?php if ($userdetail['User']['pre_location']) { ?>
                                <div class="form_lst">
                                    <label><?php echo __d('user', 'Preferred Job Locations', true);?></label>
                                    <span class="rltv"><em>
                                        <?php echo $userdetail['User']['pre_location']; ?>
                                        <?php // echo Classregistry::init('Location')->getpreferredlocation($userdetail['User']['pre_location']);
                                    ?> </em></span>
                                </div>
                            <?php } ?>
                            <?php if ($userdetail['User']['skills']) { ?>
                                <div class="form_lst">
                                    <label><?php echo __d('user', 'Skills', true);?></label>
                                    <span class="rltv"><em>
                                        <?php echo str_replace(',', ', ', $userdetail['User']['skills']);
                                        //Classregistry::init('Location')->getpreferredSkills($userdetail['User']['skills']);
                                    ?> </em></span>
                                </div>
                            <?php } ?>
                            <?php if ($userdetail['User']['interest_categories']) { ?>
                                <div class="form_lst">
                                    <label><?php echo __d('user', 'Interest Categories', true);?></label>
                                    <span class="rltv"><em>
                                        <?php echo $interestCategories;
                                        //Classregistry::init('Location')->getpreferredSkills($userdetail['User']['skills']);
                                    ?> </em></span>
                                </div>
                            <?php } ?>
                            <?php if ($userdetail['User']['exp_salary']) { ?>
                                <div class="form_lst">
                                    <label><?php echo __d('user', 'Expected Salary', true);?></label>
                                    <span class="rltv"><em>
                                        <?php echo CURRENCY.' '.$userdetail['User']['exp_salary'];
                                    ?> </em></span>
                                </div>
                            <?php } ?>
                            
                            <?php if ($userdetail['User']['total_exp']) { ?>
                                <div class="form_lst">
                                    <label><?php echo __d('user', 'Total Work Experience', true);?></label>
                                    <span class="rltv"><em><?php 
                                    global $totalexperienceArray;
                                    echo $totalexperienceArray[$userdetail['User']['total_exp']];
                                    ?> </em></span>
                                </div>
                            <?php } ?>
                            
                            <?php if ($userdetail['User']['company_about']) { ?>
                                <div class="form_lst">
                                    <label><?php echo __d('user', 'About Your Self', true);?></label>
                                    <span class="rltv"><em><?php 
                                    echo $userdetail['User']['company_about'];
                                    ?> </em></span>
                                </div>
                            <?php } ?>

                            <?php if ($coverLetters !='') { ?>
                            <div class="form_lst">
                                <label><?php echo __d('user', 'Cover Letter', true);?></label>
                                <span class="rltv"><em><?php echo nl2br($coverLetters); ?> </em></span>
                            </div>
                            <?php } ?>
                            <?php
                            $categoryName = classregistry::init('Category')->getSubCatNames($userdetail['User']['email_notification_id']);
                            if (trim($categoryName)) {
                                ?>
                            <div class="form_lst">
                                <label><?php echo __d('user', 'Email Notification', true);?> </label>
                                <span class="rltv"><em>
                                            <?php echo $categoryName; ?>
                                    </em></span>
                            </div>

                            <?php } ?>
                            <div class="form_lst">
                                    <label><?php echo __d('user', 'Current Plan', true);?></label>
                                    <span class="rltv"><em>
                                         <?php
                                           $cplan = Classregistry::init('Plan')->getcurrentplan($userdetail['User']['id']);
                                           if($cplan){
                                               echo '<span class="fertcd CurrentPlan">'.$cplan['Plan']['plan_name'].'</span>';
                                               echo $this->Html->link(__d('user', 'Upgrade Plan', true), array('controller'=>'plans', 'action'=>'purchase'), array('class'=>'upplan'));
                                           }else{
                                               echo $this->Html->link(__d('user', 'Purchase Plan', true), array('controller'=>'plans', 'action'=>'purchase'), array('class'=>'upplan noplanmy'));
                                           }
                                           
                                         ?>
                                        
                                        </em></span>
                                </div>



                                

                            <!-- <div class="form_lst">
                            <label>CV Documents </label>
                            <span class="rltv"><em>
                            <?php
                            if ($userdetail['User']['cv']) {
                                echo $this->Html->link(substr($userdetail['User']['cv'], 6), array('controller' => 'candidates', 'action' => 'download', $userdetail['User']['cv']), array('class' => 'fvvff_ss','rel'=>'nofollow'));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                              </em></span>
                            </div>-->

                            <div class="form_lst">
                                <label><?php echo __d('user', 'CV Document/Certificates', true);?> </label>
                                <span class="rltv"><em>
                                        <?php if ($showOldImages || $showOldDocs) { ?>
                                        <div class="all-uploaded-images">
                                                <?php
                                                foreach ($showOldDocs as $showOldDoc) {
                                                    $doc = $showOldDoc['Certificate']['document'];
                                                    if (!empty($doc) && file_exists(UPLOAD_CERTIFICATE_PATH . $doc)) {
                                                        ?>
                                            <div id="<?php echo $showOldDoc['Certificate']['slug']; ?>" alt="" class="doc_fukll_name">

                                                <span class="temp-image-section">
                                                                <?php echo $this->Html->link(substr($doc, 6), array('controller' => 'candidates', 'action' => 'downloadDocCertificate', $doc), array('class' => 'dfasggs','rel'=>'nofollow')); ?>    
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
                                                <div class="download_bt_show_img" title="Download"><?php echo $this->Html->link('<i class="fa fa-download"></i>', array('controller' => 'candidates', 'action' => 'downloadImage', $image), array('escape' => false,'rel'=>'nofollow')); ?></div>
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
                                    </em></span>
                            </div>
                            <div style="float: right;">
                                <?php echo $this->Html->link(__d('user', 'Edit Profile', true), array('controller' => 'candidates', 'action' => 'editProfile'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow')); ?>
                            </div>


                        </div>  

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>