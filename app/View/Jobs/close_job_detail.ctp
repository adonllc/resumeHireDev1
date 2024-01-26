
<script>
    $(document).ready(function() {
        
        
        $('#searchApplyCandidate').submit(function(){
            $.ajax({
                type: 'POST',
                url: "<?php echo HTTP_PATH; ?>/jobs/accdetail/<?php echo $jobInfo['Job']['slug']; ?>",
                cache: false,
                data: $('#searchApplyCandidate').serialize(),
                beforeSend: function(){ $("#loaderID").show(); },
                complete: function(){ $("#loaderID").hide();},
                success: function(result) {
                    $('#listID').html(result);
                }
            }); 
            return false;
        });
    });
    
 
   
    
    
</script>
<div class="my_accnt">
    <?php echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu'); ?>
                <div class="col-xs-12 col-sm-3 col-md-9 col-lg-9">
                    <div class="info_dv">
                        <div class="heads"><?php echo $jobInfo['Job']['title']; ?>
                            <div class="job">
                                <?php echo $this->Html->link('<i class="fa fa-trash"></i> &nbsp;Delete', array('controller' => 'jobs', 'action' => 'delete', $jobInfo['Job']['slug']), array('escape' => false,'rel'=>'nofollow', 'confirm' => 'Are you sure you want to delete this job?')); ?>
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
                                            
                                                echo $this->Html->link($this->Html->image('front/toggle_off.png'), 'javascript:void(0);', array('escape' => false,'rel'=>'nofollow'));
                                           
                                            ?>
                                        </div>
                                        <div class="gitust">
                                            <div><i>Created</i><em><?php echo date('d F, Y', strtotime($jobInfo['Job']['created'])); ?></em></div>

                                        </div> 
                                    </div>

                                    <div class="righting_wow">

                                        <div class="calcultn">
                                            <i><?php echo $jobInfo['Job']['search_count']; ?></i>
                                            <div class="clr"></div>
                                            <em>Search Views</em>
                                        </div>

                                        <div class="calcultn">
                                            <i><?php echo $jobInfo['Job']['view_count']; ?></i>
                                            <div class="clr"></div>
                                            <em>Job Views</em>
                                        </div>
                                        <div class="calcultn">
                                            <i><?php echo ClassRegistry::init('JobApply')->getTotalCandidate($jobInfo['Job']['id']); ?></i>
                                            <div class="clr"></div>
                                            <em>Applications</em>
                                        </div>
                                    </div> 
                                </div>

                                <div class="wow_tab">
                                    <ul>
                                        <li id="jobtab1" class="ttt active"><a href="javascript:void(0);" onclick="changeTab(1)"><i class="fa fa-user"></i>Jobseekers</a></li>
                                        <li id="jobtab2" class="ttt"><a href="javascript:void(0);" onclick="changeTab(2)"><i class="fa fa-list"></i>Job Details</a></li> 
                                    </ul>
                                </div>
                                <div class="jjj" id="job1" style="display: none1;">
                                    <div class="search_full">
                                        <div class="left_dbv">
                                            <ul>
                                                <li>
                                                    <i>Active</i>
                                                    <div class="clr"></div>
                                                    <span class="numbvering"><?php echo ClassRegistry::init('JobApply')->getStatusCount($jobInfo['Job']['id'], 'active'); ?></span> 
                                                </li>

                                                <li>
                                                    <i>Shortlist</i>
                                                    <div class="clr"></div>
                                                    <span class="numbvering"><?php echo ClassRegistry::init('JobApply')->getStatusCount($jobInfo['Job']['id'], 'short_list'); ?></span> 
                                                </li>

                                                <li>
                                                    <i>Interview</i>
                                                    <div class="clr"></div>
                                                    <span class="numbvering"><?php echo ClassRegistry::init('JobApply')->getStatusCount($jobInfo['Job']['id'], 'interview'); ?></span> 
                                                </li>

                                                <li>
                                                    <i>Offer</i>
                                                    <div class="clr"></div>
                                                    <span class="numbvering"><?php echo ClassRegistry::init('JobApply')->getStatusCount($jobInfo['Job']['id'], 'offer'); ?></span> 
                                                </li>

                                                <li>
                                                    <i>Accept</i>
                                                    <div class="clr"></div>
                                                    <span class="numbvering"><?php echo ClassRegistry::init('JobApply')->getStatusCount($jobInfo['Job']['id'], 'accept'); ?></span> 
                                                </li>

                                                <li>
                                                    <i>Not suitable</i>
                                                    <div class="clr"></div>
                                                    <span class="numbvering"><?php echo ClassRegistry::init('JobApply')->getStatusCount($jobInfo['Job']['id'], 'not_suitable'); ?></span> 
                                                </li>
                                                <li>
                                                    <i>Total</i>
                                                    <div class="clr"></div>
                                                    <span class="numbvering"><?php echo ClassRegistry::init('JobApply')->getTotalCandidate($jobInfo['Job']['id']); ?></span> 
                                                </li>
                                                <li class="newda">
                                                    <i>New</i>
                                                    <div class="clr"></div>
                                                    <span class="numbvering"><?php echo ClassRegistry::init('JobApply')->getNewCount($jobInfo['Job']['id']); ?></span> 
                                                </li>

                                            </ul>
                                        </div>
                                        <?php echo $this->Form->create('Job', array('url' => 'accdetail', 'method' => 'POST', 'name' => 'searchApplyCandidate', 'enctype' => 'multipart/form-data', 'id' => 'searchApplyCandidate')); ?>
                                        <div class="rght_srch">
                                            <div class="srch_iputwa">
                                                <?php echo $this->Form->text('JobApply.keyword', array('maxlength' => '255', 'label' => '', 'div' => false, 'class' => '', 'autocomplete' => 'off', 'placeholder' => 'Search', 'id' => 'searchcc')) ?>
                                            </div>
<!--                                            <i>Search candidates</i>-->
                                        </div>
                                        <?php echo $this->Form->end(); ?>



                                    </div>
                                    <div class="detl_scroll">
                                        <div id="listID">
                                            <?php echo $this->element('jobs/closed_active_candidate'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="jjj" id="job2" style="display: none;">
                                    <div class="search_full">
                                        <div class="form_lst">
                                            <label>Payment Status</label>
                                            <span class="rltv"><em>
                                                    <?php
                                                    if ($jobInfo['Job']['payment_status'] == 0) {
                                                        echo 'Pending';
                                                    } else {
                                                        echo 'Confirmed';
                                                    }
                                                    ?>
                                                </em></span>
                                        </div>
                                        <div class="form_lst">
                                            <label>Category</label>
                                            <span class="rltv"><em><?php echo $jobInfo['Category']['name']; ?> </em></span>
                                        </div>

                                        <div class="form_lst">
                                            <label>Sub Category</label>
                                            <span class="rltv"><em>
                                                    <?php
                                                    $subCatName = Classregistry::init('Category')->getSubCatNames($jobInfo['Job']['subcategory_id']);
                                                    echo $subCatName;
                                                    ?> 
                                                </em></span>
                                        </div>
                                        <div class="form_lst">
                                            <label>Contact Name </label>
                                            <span class="rltv"><em><?php echo $jobInfo['Job']['contact_name']; ?> </em></span>
                                        </div>
                                        <div class="form_lst">
                                            <label>Contact Number </label>
                                            <span class="rltv"><em><?php echo $jobInfo['Job']['contact_number']; ?> </em></span>
                                        </div>

                                        <div class="form_lst">
                                            <label>Address </label>
                                            <span class="rltv"><em><?php echo $jobInfo['Job']['address'] . ', ' . $jobInfo['City']['city_name'] . ', ' . $jobInfo['State']['state_name']; ?> </em></span>
                                        </div>
                                        <div class="form_lst">
                                            <label>Last date of Applying </label>
                                            <span class="rltv"><em><?php echo date('F d, Y', strtotime($jobInfo['Job']['lastdate'])); ?> </em></span>
                                        </div>
                                        <div class="form_lst">
                                            <label>Work Type </label>
                                            <span class="rltv"><em>
                                                    <?php
                                                    global $worktype;
                                                    echo $worktype[$jobInfo['Job']['work_type']];
                                                    ?> </em></span>
                                        </div>

                                        <div class="form_lst">
                                            <label>Description </label>
                                            <span class="rltv"><em><?php echo nl2br($jobInfo['Job']['description']); ?> </em></span>
                                        </div>
                                        <div class="form_lst">
                                            <label>Wage Package </label>
                                            <span class="rltv"><em>
                                                    <?php
                                                    global $priceArray;
                                                    if ($jobInfo['Job']['price']) {
                                                        echo $priceArray[$jobInfo['Job']['price']];
                                                    } else {
                                                        echo 'N/A';
                                                    };
                                                    ?> 
                                                </em></span>
                                        </div>
                                        <div class="form_lst">
                                            <label>Company Website </label>
                                            <span class="rltv"><em><?php echo $jobInfo['Job']['url'] ? $jobInfo['Job']['url'] : 'N/A'; ?> </em></span>
                                        </div>
                                        <div class="form_lst">
                                            <label>Selling Point 1</label>
                                            <span class="rltv"><em><?php echo $jobInfo['Job']['selling_point1']; ?> </em></span>
                                        </div>


                                        <?php if (isset($jobInfo['Job']['type']) && $jobInfo['Job']['type'] != 'bronze') { ?>
                                            <div class="form_lst">
                                                <label>Selling Point 2</label>
                                                <span class="rltv"><em><?php echo $jobInfo['Job']['selling_point2']; ?> </em></span>
                                            </div>
                                            <div class="form_lst">
                                                <label>Selling Point 3</label>
                                                <span class="rltv"><em><?php echo $jobInfo['Job']['selling_point3']; ?> </em></span>
                                            </div>
                                            <div class="form_lst">
                                                <label>Logo </label>
                                                <span class="rltv"><em>
                                                        <?php
                                                        $path = UPLOAD_JOB_LOGO_PATH . $jobInfo['Job']['logo'];
                                                        if (file_exists($path) && !empty($jobInfo['Job']['logo'])) {
                                                            echo $this->Html->image(PHP_PATH . "timthumb.php?src=" . DISPLAY_JOB_LOGO_PATH . $jobInfo['Job']['logo'] . "&w=200&zc=1&q=100", array('escape' => false,'rel'=>'nofollow'));
                                                        } else {
                                                            echo $this->Html->image('front/no_image_user.png');
                                                        }
                                                        ?>
                                                    </em></span>
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
    function changeTab(id){
        $('.ttt').removeClass('active');
        $('.jjj').hide('');
        $('#job'+id).show();
        $('#jobtab'+id).addClass('active');
    }

</script>




