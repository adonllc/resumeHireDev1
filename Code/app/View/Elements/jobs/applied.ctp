<?php
if ($jobs) {
    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'listID',
        'url' => array('controller' => 'jobs', 'action' => 'applied', $separator),
        'indicator' => 'loaderID'));
    ?>
    <div class="job_content" >
        <ul class="job_heading">
            <li><?php echo __d('user', 'Sr. No.', true);?></li>
            <li><?php echo __d('user', 'Job Title', true);?></li>
            <li><?php echo __d('user', 'Job Type', true);?></li>
            <li><?php echo __d('user', 'Applied Date', true);?></li>
            <li><?php echo __d('user', 'Status', true);?></li>
            <li><?php echo __d('user', 'Action', true);?></li>
        </ul>
        <?php
        $srNo = 1;
        global $active_option;
        foreach ($jobs as $job) {
            ?>
            <ul class="job_list">
                <li><?php echo $srNo++; ?></li>
                <li class="jobdi"><?php echo $job['Job']['title']; ?></li>
                <li>
                    <?php
                    if ($job['Job']['work_type'] == 1) {
                        echo __d('user', 'Full Time', true);
                    } else {
                        echo __d('user', 'Part Time', true);
                    }
                    ?>
                </li>
                <li><?php echo date('jS F,Y', strtotime($job['JobApply']['created'])); ?></li>
                <li><?php
                    if ($job['JobApply']['apply_status'] == 'active') {
                        echo __d('user', 'Applied', true);
                    } else {
                        echo $active_option[$job['JobApply']['apply_status']];
                    }
                    ?></li>
                <li><?php echo $this->Html->link(__d('user', 'Details', true), array('controller' => 'jobs', 'action' => 'detail', 'cat' => $job['Category']['slug'], 'slug' => $job['Job']['slug'], 'ext' => 'html'), array('class' => '')); ?>

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
                    '<span>'.__d('user', 'No. of Records', true).' </span><span class="">{:start}</span><span> - </span><span class="">{:end}</span><span> '.__d('user', 'of', true).' </span><span class="">{:count}</span>'
            );
            ?> 
        </div>

        <div class="paginations">
            <?php //echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false,'rel'=>'nofollow', 'class' => 'first'));  ?> 
            <?php if ($this->Paginator->hasPrev('JobApply')) echo $this->Paginator->prev(__d('home', 'Previous', true), array('class' => 'prev disabled', 'escape' => false,'rel'=>'nofollow')); ?> 
            <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false,'rel'=>'nofollow')); ?> 
            <?php if ($this->Paginator->hasNext('JobApply')) echo $this->Paginator->next(__d('home', 'Next', true), array('class' => 'next', 'escape' => false,'rel'=>'nofollow')); ?> 
            <?php //echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false,'rel'=>'nofollow'));   ?> 

        </div>	
    </div>



<?php }else { ?>
    <div class="no_found"><?php echo __d('user', 'No record found.', true);?></div>
<?php } ?>
