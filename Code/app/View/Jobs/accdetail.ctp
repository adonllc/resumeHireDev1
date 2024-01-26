<script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js"></script>
<?php
echo $this->Html->css('front/sweetalert.css');
echo $this->Html->script('front/sweetalert.min.js');
echo $this->Html->script('front/sweetalert-dev.js');
?>
<script>
   
    function completePayment(job_slug) {
        swal({
            title: "",
            text: "<?php echo __d('user', 'Are you sure you want to pay for this job', true);?> ?",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#fccd13",
            confirmButtonText: "Confirm",
            closeOnConfirm: false
        },
                function () {
                    window.location.href = "<?php echo HTTP_PATH; ?>/payments/manualPaynow/" + job_slug;
                });
    }


</script>
<div class="my_accnt">
    <?php echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu'); ?>
                <div class="col-sm-9 col-lg-9 col-xs-12">
                    <div class="info_dv">
                        <div class="heads"><?php echo $jobInfo['Job']['title']; ?>
                            <div class="job links_bbosks">
                                <?php echo $this->Html->link('<i class="fa fa-edit"></i>', array('controller' => 'jobs', 'action' => 'edit', $jobInfo['Job']['slug']), array('escape' => false, 'rel' => 'nofollow')); ?>
                                <?php //echo $this->Html->link('<i class="fa fa-trash"></i>', array('controller' => 'jobs', 'action' => 'delete', $jobInfo['Job']['slug']), array('escape' => false, 'rel' => 'nofollow', 'confirm' => 'Are you sure you want to delete this job?')); ?>
                                <?php echo $this->Html->link('<i class="fa fa-trash"></i>', array('controller' => 'jobs', 'action' => 'delete', $jobInfo['Job']['slug']), array('escape' => false, 'rel' => 'nofollow', 'confirm' => 'Are you sure you want to delete this job?')); ?>

                                <?php echo $this->Html->link('<i class="fa fa-files-o"></i>', array('controller' => 'jobs', 'action' => 'copyJob', $jobInfo['Job']['slug']), array('escape' => false, 'rel' => 'nofollow')); ?>
<!--                                <a href=""><i class="fa fa-share"></i> &nbsp;Share</a>-->
                                <?php echo $this->Html->link('<i class="fa fa-eye"></i>', array('controller' => 'jobs', 'action' => 'detail', 'cat' => $jobInfo['Category']['slug'], 'slug' => $jobInfo['Job']['slug'], 'ext' => 'html'), array('escape' => false, 'rel' => 'nofollow')); ?>
                                <div class="addthis_button" addthis:url="<?php echo HTTP_PATH . '/' . $jobInfo['Category']['slug'] . '/' . $jobInfo['Job']['slug'] . '.html' ?>"><i class="fa fa-share-alt"></i></div>

                            </div>
                        </div>
                        <?php echo $this->Session->flash(); ?>
                        <div class="information_cntn">
                            <?php echo $this->element('session_msg'); ?>    

                            <div class="listing_page">

                                <div class="wow_div">


                                    <div class="leftin_wow"> 
                                        <div class="stst_img">
                                            <?php
                                            $jobSlug = $jobInfo['Job']['slug'];
//                                            if ($jobInfo['Job']['payment_status'] == 0) {
//                                                echo $this->Html->link($this->Html->image('front/toggle_inactive.png'), 'javascript:void(0);', array('escape' => false, 'rel' => 'nofollow', 'onclick' => "completePayment('$jobSlug');"));
//                                            } else if ($jobInfo['Job']['payment_status'] == 1) {
//                                                echo $this->Html->link($this->Html->image('front/toggle_inactive.png'), 'javascript:void(0);', array('escape' => false, 'rel' => 'nofollow'));
//                                            } else {
//                                                if ($jobInfo['Job']['expire_time'] < time()) {
//                                                    echo $this->Html->link($this->Html->image('front/toggle_inactive.png'), 'javascript:void(0);', array('escape' => false, 'rel' => 'nofollow'));
//                                                } else {
                                                    if ($jobInfo['Job']['status'] == 0) {
                                                        echo $this->Html->link($this->Html->image('front/toggle_inactive.png'), array('controller' => 'jobs', 'action' => 'active', $jobInfo['Job']['slug']), array('escape' => false, 'rel' => 'nofollow', 'confirm' => "'".__d('user', 'Are you sure you want to activate this job', true)."'?"));
                                                    } else {
                                                        echo $this->Html->link($this->Html->image('front/toggle_active.png'), array('controller' => 'jobs', 'action' => 'deactive', $jobInfo['Job']['slug']), array('escape' => false, 'rel' => 'nofollow', 'confirm' => "'".__d('user', 'Are you sure you want to deactivate this job', true)."'?"));
                                                    }
//                                                }
//                                            }
                                            ?>
                                        </div>
                                        <div class="gitust">
                                            <div><i><?php echo __d('user', 'Created', true);?></i><em><?php echo date('d F, Y', strtotime($jobInfo['Job']['created'])); ?></em></div>

                                        </div> 
                                    </div>

                                    <div class="righting_wow">

                                        <div class="calcultn">
                                            <div class="left_side_calu">
                                                <i class="fa fa-search icon_calcultn"></i>
                                            </div>
                                            <div class="right_side_calu">
                                                <i><?php echo $jobInfo['Job']['search_count'] ? $jobInfo['Job']['search_count'] : '0'; ?></i>
                                                <div class="clr"></div>
                                                <em><?php echo __d('user', 'Search Views', true);?></em>
                                            </div>
                                        </div>

                                        <div class="calcultn">
                                            <div class="left_side_calu">
                                                <i class="fa fa-suitcase icon_calcultn"></i>
                                            </div>
                                            <div class="right_side_calu">
                                                <i><?php echo $jobInfo['Job']['view_count'] ? $jobInfo['Job']['view_count'] : '0'; ?></i>
                                                <div class="clr"></div>
                                                <em><?php echo __d('user', 'Job Views', true);?></em>
                                            </div>
                                        </div>
                                        <div class="calcultn">
                                            <div class="left_side_calu">
                                                <i class="fa fa-clock-o icon_calcultn"></i>
                                            </div>
                                            <div class="right_side_calu">
                                                <i><?php echo ClassRegistry::init('JobApply')->getTotalCandidate($jobInfo['Job']['id']); ?></i>
                                                <div class="clr"></div>
                                                <em><?php echo __d('user', 'Applications', true);?></em>
                                            </div>
                                        </div>
                                    </div> 
                                </div>

                                <div class="wow_tab">
                                    <ul>
                                        <li id="jobtab1" class="ttt active"><a href="javascript:void(0);" onclick="changeTab(1)"><?php echo __d('user', 'Jobseekers', true);?></a></li>
                                        <li id="jobtab2" class="ttt"><a href="javascript:void(0);" onclick="changeTab(2)"><?php echo __d('user', 'Job Details', true);?></a></li> 
                                    </ul>
                                </div>
                                <div class="jjj" id="job1" style="display: none1;">

                                    <div class="detl_scroll">
                                        <div class="search_full">
                                            <div class="left_dbv">
                                                <ul>
                                                    <li id="activebu" class="awei">
                                                        <i><?php echo __d('user', 'Active', true);?></i>
                                                        <div class="clr"></div>
                                                        <a href="javascript:void(0);" class="search_by_status" status=""><span class="numbvering"  onclick="getactive('activebu')"><?php echo ClassRegistry::init('JobApply')->getStatusCount($jobInfo['Job']['id'], 'active'); ?></span></a>
                                                    </li>

                                                    <li id="Shortlist" class="awei">
                                                        <i><?php echo __d('user', 'Shortlist', true);?></i>
                                                        <div class="clr"></div>
                                                        <a href="javascript:void(0);" class="search_by_status" status="short_list"><span class="numbvering"  onclick="getactive('Shortlist')"><?php echo ClassRegistry::init('JobApply')->getStatusCount($jobInfo['Job']['id'], 'short_list'); ?></span> </a>
                                                    </li>

                                                    <li id="Interview" class="awei">
                                                        <i><?php echo __d('user', 'Interview', true);?></i>
                                                        <div class="clr"></div>
                                                        <a href="javascript:void(0);" class="search_by_status" status="interview"><span class="numbvering"  onclick="getactive('Interview')"><?php echo ClassRegistry::init('JobApply')->getStatusCount($jobInfo['Job']['id'], 'interview'); ?></span> </a>
                                                    </li>

                                                    <li id="Offer" class="awei">
                                                        <i><?php echo __d('user', 'Offer', true);?></i>
                                                        <div class="clr"></div>
                                                        <a href="javascript:void(0);" class="search_by_status" status="offer"> <span class="numbvering"  onclick="getactive('Offer')"><?php echo ClassRegistry::init('JobApply')->getStatusCount($jobInfo['Job']['id'], 'offer'); ?></span> </a>
                                                    </li>

                                                    <li id="Accept" class="awei">
                                                        <i><?php echo __d('user', 'Accept', true);?></i>
                                                        <div class="clr"></div>
                                                        <a href="javascript:void(0);" class="search_by_status" status="accept"><span class="numbvering"  onclick="getactive('Accept')"><?php echo ClassRegistry::init('JobApply')->getStatusCount($jobInfo['Job']['id'], 'accept'); ?></span> </a>
                                                    </li>

                                                    <li id="Notsuitable" class="awei">
                                                        <i><?php echo __d('user', 'Not suitable', true);?></i>
                                                        <div class="clr"></div>
                                                        <a href="javascript:void(0);" class="search_by_status" status="not_suitable"><span class="numbvering"  onclick="getactive('Notsuitable')"><?php echo ClassRegistry::init('JobApply')->getStatusCount($jobInfo['Job']['id'], 'not_suitable'); ?></span> </a>
                                                    </li>
                                                    <li id="Total" class="awei">
                                                        <i><?php echo __d('user', 'Total', true);?></i>
                                                        <div class="clr"></div>
                                                        <a href="javascript:void(0);" class="search_by_status" status=""><span class="numbvering"  onclick="getactive('Total')"><?php echo ClassRegistry::init('JobApply')->getTotalCandidate($jobInfo['Job']['id']); ?></span> </a>
                                                    </li>
                                                    <li class="awei newda">
                                                        <i><?php echo __d('user', 'New', true);?></i>
                                                        <div class="clr"></div>
                                                        <span class="numbvering" id="New" onclick="getactive('New')"><?php echo ClassRegistry::init('JobApply')->getNewCount($jobInfo['Job']['id']); ?></span> 
                                                    </li>

                                                </ul>
                                            </div>
                                            <?php echo $this->Form->create('Job', array('url' => 'accdetail', 'method' => 'POST', 'name' => 'searchApplyCandidate', 'enctype' => 'multipart/form-data', 'id' => 'searchApplyCandidate')); ?>
                                            <div class="rght_srch">
                                                <div class="srch_iputwa">
                                                    <?php echo $this->Form->text('JobApply.keyword', array('maxlength' => '255', 'label' => '', 'div' => false, 'class' => '', 'autocomplete' => 'off', 'placeholder' => __d('user', 'Search', true), 'id' => 'searchcc')) ?>
                                                </div>
                                        <!--                                            <i>Search candidates</i>-->
                                            </div>
                                            <?php echo $this->Form->end(); ?>



                                        </div>
                                        <div id="listID">
                                            <?php  echo $this->element('jobs/active_candidate'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="jjj" id="job2" style="display: none;">
                                    <div class="search_full">
                                        <!--                                        <div class="form_lst">
                                                                                    <label>Payment Status</label>
                                                                                    <span class="rltv"><em>
                                        <?php
//                                                    if ($jobInfo['Job']['payment_status'] == 0) {
//                                                        echo 'Pending';
//                                                    } elseif ($jobInfo['Job']['payment_status'] == 1) {
//                                                        echo 'In process';
//                                                    } elseif ($jobInfo['Job']['payment_status'] == 2) {
//                                                        echo 'Completed';
//                                                    }
                                        ?>
                                                                                        </em></span>
                                                                                </div>-->
                                        <div class="form_lst">
                                            <label><?php echo __d('user', 'Category', true);?></label>
                                            <span class="rltv"><em><?php echo $jobInfo['Category']['name']; ?> </em></span>
                                        </div>
                                        <?php
                                        $subCatName = Classregistry::init('Category')->getSubCatNames($jobInfo['Job']['subcategory_id']);
                                        if (!empty($subCatName)) {
                                            ?>
                                            <div class="form_lst">
                                                <label><?php echo __d('user', 'Sub Category', true);?></label>
                                                <span class="rltv"><em>
                                                        <?php
                                                        echo $subCatName;
                                                        ?> 
                                                    </em></span>
                                            </div>
                                        <?php } ?>
                                        <div class="form_lst">
                                            <label><?php echo __d('user', 'Contact Name', true);?> </label>
                                            <span class="rltv"><em><?php echo $jobInfo['Job']['contact_name']; ?> </em></span>
                                        </div>
                                        <div class="form_lst">
                                            <label><?php echo __d('user', 'Contact Number', true);?> </label>
                                            <span class="rltv"><em><?php echo $jobInfo['Job']['contact_number']; ?> </em></span>
                                        </div>

                                        <div class="form_lst">
                                            <label><?php echo __d('user', 'Skills', true);?> </label>
                                            <span class="rltv"><em>
                                                    <?php
                                                    $jobskill = ClassRegistry::init('Job')->field('skill', array('Job.id' => $jobInfo['Job']['id']));

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
                                                </em></span>
                                        </div>

                                        <div class="form_lst">
                                            <label><?php echo __d('user', 'Designation', true);?> </label>
                                            <span class="rltv"><em>
                                                    <?php
                                                    $jobDesignation = ClassRegistry::init('Job')->field('designation', array('Job.id' => $jobInfo['Job']['id']));
                                                    // pr($jobDesignation);
                                                    $designation = ClassRegistry::init('Skill')->field('name', array('Skill.id' => $jobDesignation, 'Skill.type' => 'Designation'));
                                                    if (!empty($designation)) {
                                                        echo $designation;
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?>
                                                </em></span>
                                        </div>

                                        <!--  <div class="form_lst">
                                              <label>City </label>
                                              <span class="rltv"><em><?php //echo $jobInfo['City']['city_name']?$jobInfo['City']['city_name']:'N/A';             ?> </em></span>
                                          </div>
                                          <div class="form_lst">
                                              <label>State </label>
                                              <span class="rltv"><em><?php //echo $jobInfo['State']['state_name']?$jobInfo['State']['state_name']:'N/A';             ?> </em></span>
                                          </div>
                                          <div class="form_lst">
                                              <label>Postcode </label>
                                              <span class="rltv"><em><?php //echo $jobInfo['Job']['postal_code']?$jobInfo['Job']['postal_code']:'N/A';             ?> </em></span>
                                          </div>-->

                                        <div class="form_lst">
                                            <label><?php echo __d('user', 'Location', true);?> </label>
                                            <span class="rltv"><em><?php echo $jobInfo['Job']['job_city'] ? $jobInfo['Job']['job_city'] : 'N/A'; ?> </em></span>
                                        </div>

                                        <div class="form_lst">
                                            <label><?php echo __d('user', 'Work Type', true);?> </label>
                                            <span class="rltv"><em>
                                                    <?php
                                                    global $worktype;
                                                    echo $worktype[$jobInfo['Job']['work_type']];
                                                    ?> </em></span>
                                        </div>

                                        <div class="form_lst">
                                            <label><?php echo __d('user', 'Description', true);?> </label>
                                            <span class="rltv"><em><?php echo strip_tags(nl2br($jobInfo['Job']['description'])); ?> </em></span>
                                        </div>

                                        <div class="form_lst">
                                            <label><?php echo __d('user', 'Salary', true);?> </label>
                                            <span class="rltv"><em>
                                                    <?php
                                                    if (isset($jobInfo['Job']['min_salary']) && isset($jobInfo['Job']['max_salary'])) {
                                                        echo CURRENCY . ' ' . intval($jobInfo['Job']['min_salary']) . " - " .CURRENCY . ' ' . intval($jobInfo['Job']['max_salary']);
                                                    } else {
                                                        echo "N/A";
                                                    }
                                                    ?>
                                                </em></span>
                                        </div>
                                        <div class="form_lst">
                                            <label><?php echo __d('user', 'Experience', true);?> </label>
                                            <span class="rltv"><em>
                                                    <?php
                                                    if (isset($jobInfo['Job']['min_exp']) && isset($jobInfo['Job']['max_exp'])) {
                                                        echo $jobInfo['Job']['min_exp'] . "-" . $jobInfo['Job']['max_exp'] . " Year";
                                                    } else {
                                                        echo "N/A";
                                                    }
                                                    ?>
                                                </em></span>
                                        </div>

                                        <div class="form_lst">
                                            <label><?php echo __d('user', 'Company Name', true);?> </label>
                                            <span class="rltv"><em><?php echo $jobInfo['Job']['company_name'] ? $jobInfo['Job']['company_name'] : 'N/A'; ?> </em></span>
                                        </div>
                                        <div class="form_lst">
                                            <label><?php echo __d('user', 'Company Profile', true);?> </label>
                                            <span class="rltv"><em><?php echo $jobInfo['Job']['brief_abtcomp'] ? $jobInfo['Job']['brief_abtcomp'] : 'N/A'; ?> </em></span>
                                        </div>
                                        <!--<div class="form_lst">
                                            <label>Wage Package </label>
                                            <span class="rltv"><em>
                                        <?php
                                        /*   global $priceArray;
                                          if ($jobInfo['Job']['price']) {
                                          echo $priceArray[$jobInfo['Job']['price']];
                                          } else {
                                          echo 'N/A';
                                          }; */
                                        ?> 
                                                </em></span>
                                        </div>-->
                                        <div class="form_lst">
                                            <label><?php echo __d('user', 'Company Website', true);?> </label>
                                            <span class="rltv"><em><?php echo $jobInfo['Job']['url'] ? "<a href='".$jobInfo['Job']['url']."' target='_blank'>".$jobInfo['Job']['url']."</a>" : 'N/A'; ?> </em></span>
                                        </div>
<!--                                        <div class="form_lst">
                                            <label>Highlights of Job 1</label>
                                            <span class="rltv"><em><?php //echo $jobInfo['Job']['selling_point1'] ? $jobInfo['Job']['selling_point2'] : 'N/A'; ?> </em></span>
                                        </div>-->


                                        <?php if (isset($jobInfo['Job']['type']) && $jobInfo['Job']['type'] != 'bronze') { ?>
<!--                                            <div class="form_lst">
                                                <label>Highlights of Job 2</label>
                                                <span class="rltv"><em><?php //echo $jobInfo['Job']['selling_point2'] ? $jobInfo['Job']['selling_point2'] : 'N/A'; ?> </em></span>
                                            </div>
                                            <div class="form_lst">
                                                <label>Highlights of Job 3</label>
                                                <span class="rltv"><em><?php //echo $jobInfo['Job']['selling_point3'] ? $jobInfo['Job']['selling_point3'] : 'N/A'; ?> </em></span>
                                            </div>-->
                                            <div class="form_lst">
                                                <label><?php echo __d('user', 'Logo', true);?> </label>
                                                <span class="rltv">
                                                    <em>
                                                        <?php
                                                        if($jobInfo['Job']['logo']) {
                                                            $path = UPLOAD_JOB_LOGO_PATH . $jobInfo['Job']['logo'];
                                                            if (file_exists($path) && !empty($jobInfo['Job']['logo'])) {
                                                                echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_JOB_LOGO_PATH . $jobInfo['Job']['logo'] . "&w=200&zc=1&q=100", array('escape' => false, 'rel' => 'nofollow'));
                                                            } else {
                                                                echo $this->Html->image('front/no_image_user.png');
                                                            }
                                                        } else{
                                                             $logo_image = ClassRegistry::init('User')->field('profile_image', array('User.id' => $jobInfo['Job']['user_id']));
                                                            $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $logo_image;
                                                            if (file_exists($path) && !empty($logo_image)) {
                                                                echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $logo_image, array('escape' => false, 'rel' => 'nofollow'));
                                                            } else {
                                                                echo $this->Html->image('front/no_image_user.png');
                                                            }
                                                        }
                                                        ?>
                                                    </em>
                                                </span>
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




