<?php if ($favorites) { 
    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'listID',
        'url' => array('controller' => 'candidates', 'action' => 'favorite', $separator),
        'indicator' => 'loaderID'));
    ?>
<div class="overflow_gkgkg">
<div class="job_content job_content_btbtb" >
    <ul class="job_heading">
        <li><?php echo __d('user', 'Sr. No.', true);?></li>
        <li><?php echo __d('user', 'Jobseeker Name', true);?></li>
        <li><?php echo __d('user', 'Email', true);?></li>
        <li><?php echo __d('user', 'Action', true);?></li>
    </ul>
    <?php
    $srNo = 1;
    foreach ($favorites as $favorite) {
        ?>
        <ul class="job_list">
            <li><?php echo $srNo++; ?></li>
            <li class="jobdi"><?php echo ucfirst($favorite['Candidate']['first_name']).' '.ucfirst($favorite['Candidate']['last_name']);?></li>
            <li>
                <?php echo $favorite['Candidate']['email_address'] ? $favorite['Candidate']['email_address'] : "N/A"; ?>
            </li>
            
            <li><?php echo $this->Html->link(__d('user', 'Profile', true), array('controller' => 'candidates', 'action' => 'profile',$favorite['Candidate']['slug']), array('class' => ''));?>
                |
            <?php echo $this->Html->link(__d('user', 'Delete', true), array('controller' => 'candidates', 'action' => 'deleteFavoriteList',$favorite['Favorite']['id']), array('class' => ''));?>
            </li>
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
                <?php if ($this->Paginator->hasPrev('Favorite')) echo $this->Paginator->prev('<i class="fa fa-arrow-left"></i>', array('class' => 'prev disabled', 'escape' => false,'rel'=>'nofollow')); ?> 
                <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false,'rel'=>'nofollow')); ?> 
                <?php if ($this->Paginator->hasNext('Favorite')) echo $this->Paginator->next('<i class="fa fa-arrow-right"></i>', array('class' => 'next', 'escape' => false,'rel'=>'nofollow')); ?> 
                <?php echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false,'rel'=>'nofollow')); ?> 
            </div>	
        </div>

    
    
    
<?php }else { ?>
    <div class="no_found"><?php echo __d('user', 'No record found.', true);?></div>
<?php } ?>
