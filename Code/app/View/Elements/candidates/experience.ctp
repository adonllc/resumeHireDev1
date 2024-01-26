
<?php if ($experiences) { ?>
 
    <ul class="job_heading">
        <li>Company Name</li>
        <li>From(MM/YY)</li>
        <li>Until(MM/YY)</li>
        <li>Job Role</li>
        <li>Action</li>
    </ul>
    <?php
    $srNo = 1;
    foreach ($experiences as $experience) {
        ?>
        <ul class="job_list">
            <li><?php echo  $experience['Experience']['company_name'];?></li>
            <li><?php echo date('M/Y', strtotime($experience['Experience']['fdate'])); ?></li>
            <li><?php echo date('M/Y', strtotime($experience['Experience']['tdate'])); ?></li>
            <li><?php echo  $experience['Experience']['job_role'];?></li>
            <li>
                <?php echo $this->Html->link('<i class="fa fa-pencil"></i>', array("controller" => "candidates", "action" => 'experience', $experience['Experience']['slug']), array('escape' => false,'rel'=>'nofollow', 'class' => " btn-warning btn-xs", 'title' => 'Edit')); ?>
                <?php echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'candidates', 'action' => 'deleteExperience', $experience['Experience']['slug']), array('class' => ' btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false,'rel'=>'nofollow', 'title' => 'Delete')); ?>
            </li>                            
        </ul>
        <?php
    }
    ?>
    
<?php }else { ?>
    <div class="no_found">No record found.</div>
<?php } ?>