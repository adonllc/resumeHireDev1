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
<?php if ($jobs) {?>
<div class="right_child_sec_over">
    <div class="job_content right_child_sec">
        
        
        <?php
        
            $this->Paginator->_ajaxHelperClass = "Ajax";
            $this->Paginator->Ajax = $this->Ajax;
            $this->Paginator->options(array('update' => 'listID',
                'url' => array('controller' => 'jobs', 'action' => 'management', $separator),
                'indicator' => 'loaderID'));
            ?>

            <ul class="job_heading">
                <li><?php echo __d('user', 'Status', true);?></li>
                <li><?php echo __d('user', 'Job Title', true);?></li>
                <li><?php echo __d('user', 'Posted On', true);?></li>
                <li><?php echo __d('user', 'Jobseeker', true);?></li>
                <li><?php echo __d('user', 'Notified Jobseekers', true);?></li>
                <!-- <li>Payment status</li>-->
            </ul>
            <?php
            $srNo = 1;
            foreach ($jobs as $job) {
                $jobSlug = $job['Job']['slug'];
                ?>
                <ul class="job_list">

                    <li>
                        <?php
                        if ($job['Job']['status'] == 0) {
                            echo $this->Html->link($this->Html->image('front/toggle_inactive.png'), array('controller' => 'jobs', 'action' => 'active', $job['Job']['slug']), array('escape' => false,'rel'=>'nofollow', 'confirm' => __d('user', 'Are you sure you want to activate this job', true)."?"));
                        } else {
                            echo $this->Html->link($this->Html->image('front/toggle_active.png'), array('controller' => 'jobs', 'action' => 'deactive', $job['Job']['slug']), array('escape' => false,'rel'=>'nofollow', 'confirm' => __d('user', 'Are you sure you want to deactivate this job', true)."?"));
                        }
                        ?>
                    </li>

                    <li class="jobdi"><?php echo $this->Html->link($job['Job']['title'], array('controller' => 'jobs', 'action' => 'accdetail', $job['Job']['slug']), array()); ?></li>
                    <li><?php echo date('d M, Y', strtotime($job['Job']['created'])); ?></li>
                    <li>
                        <span class="candi_img"><?php echo $this->Html->image('front/user_img.png', array("escape" => false)) ?></span>

                        <span class="fvghhnhn">
                            <div class="fvaa"> 
                                <div class="gvvgfr"><?php echo __d('user', 'All', true);?>:</div>
                                <div class="bhbghbh"><?php echo ClassRegistry::init('JobApply')->getTotalCandidate($job['Job']['id']); ?></div>
                            </div>
                            <div  class="fvaa">
                                <div class="gvvgfr"><b style="font-weight:100;"><?php echo __d('user', 'New', true);?>:</b></div>
                                <div class="bhbghbh"><b style="font-weight:100;">
                                        <?php echo ClassRegistry::init('JobApply')->getNewCount($job['Job']['id']); ?></b>
                                </div>
                            </div>
                        </span>

                    </li>
                    <li>
                        <?php echo ClassRegistry::init('AlertJob')->find('count', array('conditions' => array('AlertJob.job_id' => $job['Job']['id']))); ?>
                    </li>
                    <!--  <li>
                    <?php
                    /* if ($job['Job']['payment_status'] == 0) {
                      echo $this->Html->link('Pending', 'javascript:void(0);', array('escape' => false,'rel'=>'nofollow', 'onclick' => "completePayment('$jobSlug');"));
                      } else if ($job['Job']['payment_status'] == 1) {
                      echo 'In process';
                      } else {
                      if ($job['Job']['expire_time'] < time()) {
                      echo 'Plan Expired';
                      } else {
                      echo 'Completed';
                      }
                      } */
                    ?>
                      </li> -->
                </ul>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="paging">
        <div class="noofproduct">
            <?php
            echo $this->Paginator->counter(
                    '<span>'.__d('user', 'No. of Records', true).' </span><span class="">{:start}</span><span> - </span><span class="">{:end}</span><span> '.__d('user', 'of', true).' </span><span class="">{:count}</span>'
            );
            ?> 
        </div>

        <div class="pagination">
            <?php echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false,'rel'=>'nofollow', 'class' => 'first')); ?> 
            <?php if ($this->Paginator->hasPrev('Job')) echo $this->Paginator->prev('<i class="fa fa-arrow-left"></i>', array('class' => 'prev disabled', 'escape' => false,'rel'=>'nofollow')); ?> 
            <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false,'rel'=>'nofollow')); ?> 
            <?php if ($this->Paginator->hasNext('Job')) echo $this->Paginator->next('<i class="fa fa-arrow-right"></i>', array('class' => 'next', 'escape' => false,'rel'=>'nofollow')); ?> 
            <?php echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false,'rel'=>'nofollow')); ?> 
        </div>	
    </div>
<?php }else { ;?>
    <div class="no_found"><?php echo __d('user', 'No record found', true);?>.</div>
<?php } ?>