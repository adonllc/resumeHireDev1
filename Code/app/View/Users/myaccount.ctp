
<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="container">
            <div class="row">
                <div class="my_acc">
                    <?php echo $this->element('left_menu'); ?>
                    <div class="col-lg-9 col-sm-9">
                        <div class="my-profile-boxes">
                            <div class="my-profile-boxes-top"><h2><i><?php echo $this->Html->image('front/home/profile-icon.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'My Profile', true);?></span></h2></div>
                            <div class="information_cntn">
                                <?php echo $this->element('session_msg'); ?>    
                                <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'Email Address', true);?></label>
                                    <span class="form_list_jobseekars"><?php echo $userdetail['User']['email_address']; ?></span>
                                </div>
                                <div class="form_list_education">
                                   <label class="lable-acc"><?php echo __d('user', 'Company Name', true);?></label>
                                    <span class="form_list_jobseekars"><?php echo $userdetail['User']['company_name'] ? $userdetail['User']['company_name'] : 'N/A'; ?></span>
                                </div>
                             
                                <?php if ($userdetail['User']['position']) { ?>
                                    <div class="form_list_education">
                                        <label class="lable-acc"><?php echo __d('user', 'Position', true);?></label>
                                        <span class="form_list_jobseekars">
                                                <?php
                                                echo $userdetail['User']['position'];
                                                ?> </span>
                                    </div>
                                <?php } ?>

                                <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'First Name', true);?></label>
                                    <span class="form_list_jobseekars"><?php echo $userdetail['User']['first_name']; ?></span>
                                </div>
                                <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'Last Name', true);?></label>
                                    <span class="form_list_jobseekars"><?php echo $userdetail['User']['last_name']; ?></span>
                                </div>
                                <?php if ($userdetail['User']['address']) { ?>
                                    <div class="form_list_education">
                                        <label class="lable-acc"><?php echo __d('user', 'Address', true);?></label>
                                        <span class="form_list_jobseekars"><?php echo $userdetail['User']['address']; ?></span>
                                    </div>
                                <?php } ?>

                                <!-- <?php //if ($userdetail['City']['city_name']) {   ?>
                                     <div class="form_lst">
                                         <label>City</label>
                                         <span class="rltv"><em><?php //echo $userdetail['City']['city_name'];   ?> </em></span>
                                     </div>
                                <?php //} ?>
     
                                <?php //if ($userdetail['State']['state_name']) { ?>
                                     <div class="form_lst">
                                         <label>State</label>
                                         <span class="rltv"><em><?php //echo $userdetail['State']['state_name'];   ?> </em></span>
                                     </div>
                                <?php //} ?>
     
                                <?php //if ($userdetail['Country']['country_name']) { ?>
                                     <div class="form_lst">
                                         <label>Country</label>
                                         <span class="rltv"><em><?php //echo $userdetail['Country']['country_name'];   ?> </em></span>
                                     </div> -->
                                <?php //} ?>

                                <?php if ($userdetail['User']['location']) { ?>
                                    <div class="form_list_education">
                                        <label class="lable-acc"><?php echo __d('user', 'Location', true);?></label>
                                        <span class="form_list_jobseekars"><?php echo $userdetail['User']['location']?$userdetail['User']['location']:'N/A'; ?></span>
                                    </div>
                                <?php } ?>    


                                <?php if ($userdetail['User']['contact']) { ?>
                                    <div class="form_list_education">
                                        <label class="lable-acc"><?php echo __d('user', 'Contact Number', true);?></label>
                                        <span class="form_list_jobseekars"><?php echo $userdetail['User']['contact']; ?></span>
                                    </div>
                                <?php } ?>
                                <?php if ($userdetail['User']['company_contact']) { ?>
                                    <div class="form_list_education">
                                        <label class="lable-acc"><?php echo __d('user', 'Company Number', true);?></label>
                                        <span class="form_list_jobseekars"><?php echo $userdetail['User']['company_contact']; ?></span>
                                    </div> 
                                <?php } ?>

                                <?php if ($userdetail['User']['url']) { ?>
                                    <div class="form_list_education">
                                        <label class="lable-acc"><?php echo __d('user', 'Company Website', true);?></label>
                                        <span class="form_list_jobseekars">
                                                <?php echo $userdetail['User']['url'] ? "<a href='".$userdetail['User']['url']."' target='_blank'>".$userdetail['User']['url']."</a>" : 'N/A'; ?>
                                            </span>
                                    </div>
                                <?php } ?>

                                <div style="float: right; display:none;">
                                    <?php echo $this->Html->link(__d('user', 'Edit Profile', true), array('controller' => 'users', 'action' => 'editProfile'), array('class' => 'input_btn rigjt', 'escape' => false, 'rel' => 'nofollow')); ?>
                                </div>
                                
                                
                                 <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'Current Plan', true);?></label>
                                    <span class="form_list_jobseekars plan_list_jobseekars">
                                         <?php
                                           $cplan = Classregistry::init('Plan')->getcurrentplan($userdetail['User']['id']);
                                           if($cplan){
                                               echo '<span class="fertcd">'.$cplan['Plan']['plan_name'].'</span>';
                                               echo $this->Html->link(__d('user', 'Upgrade Plan', true), array('controller'=>'plans', 'action'=>'purchase'), array('class'=>'currant-upplan'));
                                           }else{
                                               echo $this->Html->link(__d('user', 'Purchase Plan', true), array('controller'=>'plans', 'action'=>'purchase'), array('class'=>'currant-upplan pdf-btn noplanmy'));
                                           }
                                           
                                           
                                           
                                          ?>
                                              
                                </div>
                                
                                <?php if($cplan){ ?>
                                <div class="form_list_education">
                                    <label class="lable-acc"><?php echo __d('user', 'Available Plan Features', true);?></label>
                                    <span class="form_list_jobseekars plan_list_jobseekars">
                                         
                                           <ol>
                                               <li>Plan Time Period - <?php echo $cplan['UserPlan']['type_value'].' '.$cplan['UserPlan']['type']; ?></li>
                                               <?php if($getRemainingFeatures['availableJobpost']){ ?> <li>Number of Job Post - <?php echo $getRemainingFeatures['availableJobpost'] ? $getRemainingFeatures['availableJobpost'] : 'N/A'; ?></li><?php } ?>
                                               <?php if($getRemainingFeatures['availableDownloadCount']){ ?> <li>Number of resume download - <?php echo $getRemainingFeatures['availableDownloadCount'] ? $getRemainingFeatures['availableDownloadCount'] : 'N/A'; ?></li><?php } ?>
                                               <?php if($getRemainingFeatures['searchCandidate']){ ?> <li>Access candidate search functionality - <?php echo $getRemainingFeatures['searchCandidate'] ? 'YES' : 'Not'; ?></li><?php } ?>
                                              <?php if($getRemainingFeatures['availableProfileView'] != '2'){ ?>  <li>Number of Candidate Profile Views - <?php echo $getRemainingFeatures['availableProfileView'] ? $getRemainingFeatures['availableProfileView'] : '0'; ?></li><?php } ?>
                                          </ol>
                                        
                                </div>
                                <?php } ?>
                                
                                
                            </div>        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




