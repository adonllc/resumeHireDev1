 <div class="job_content">
<?php 
if ($jobs) {
    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'listID',
        'url' => array('controller' => 'jobs', 'action' => 'management', $separator),
        'indicator' => 'loaderID'));
    ?>

    <ul class="job_heading">
        
        <li>Job</li>
        <li>Locations</li>
        <li>Posted On</li>
        <li>Jobseeker</li>
    </ul>
    <?php
    $srNo = 1;
    foreach ($jobs as $job) {
        ?>
        <ul class="job_list">
           
        <li class="jobdi"><?php echo $this->Html->link($job['Job']['title'], array('controller' => 'jobs', 'action' => 'closeJobDetail', $job['Job']['slug']), array()); ?></li>
        <li>
            <?php echo $job['Job']['address']; ?> <br>
    <?php echo $job['City']['city_name'] . ', ' . $job['State']['state_name']; ?>
        </li>
        <li><?php echo date('d M, Y', strtotime($job['Job']['created'])); ?></li>
        <li>
            <span class="candi_img"><?php echo $this->Html->image('front/user_img.png', array("escape" => false)) ?></span>
            <span class="candi_blk">
                <div class="upper_candi"><?php echo ClassRegistry::init('JobApply')->getTotalCandidate($job['Job']['id']); ?></div>
                <div class="lowr_candi"><i><?php echo ClassRegistry::init('JobApply')->getNewCount($job['Job']['id']); ?></i><em>new</em></div> 
            </span> 
        </li>
    </ul>
    <?php
}
?>
</div>
<div class="paging">
    <div class="noofproduct">
        <?php
        echo $this->Paginator->counter(
                '<span>'.__d('user', 'No. of Records', true).' </span><span class="badge-gray">{:start}</span><span> - </span><span class="badge-gray">{:end}</span><span> '.__d('user', 'of', true).' </span><span class="badge-gray">{:count}</span>'
        );
        ?> 
    </div>
    <div class="pagination">
        <?php echo $this->Paginator->first('first', array('escape' => false,'rel'=>'nofollow', 'class' => 'first')); ?> 
        <?php if ($this->Paginator->hasPrev('Job')) echo $this->Paginator->prev('<i class="fa fa-arrow-left"></i>', array('class' => 'prev disabled', 'escape' => false,'rel'=>'nofollow')); ?> 
        <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false,'rel'=>'nofollow')); ?> 
        <?php if ($this->Paginator->hasNext('Job')) echo $this->Paginator->next('<i class="fa fa-arrow-right"></i>', array('class' => 'next', 'escape' => false,'rel'=>'nofollow')); ?> 
<?php echo $this->Paginator->last('last', array('class' => 'last', 'escape' => false,'rel'=>'nofollow')); ?> 

    </div>	
</div>
<?php }else { ?>
    <div class="no_found">No record found.</div>
<?php } ?>