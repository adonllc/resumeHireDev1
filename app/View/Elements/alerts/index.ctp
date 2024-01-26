<?php
if ($alerts) {

    //pr($alerts); exit;

    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'listID',
        'url' => array('controller' => 'alerts', 'action' => 'index', $separator),
        'indicator' => 'loaderID'));
    ?>
<div class="over_flow_auto_sigr">
    <div class="job_content" >
        <ul class="job_heading">
            <li><?php echo __d('user', 'Sr. No.', true);?></li>
            <li><?php echo __d('user', 'Location', true);?></li>
            <li><?php echo __d('user', 'Designation', true);?></li>
            <li><?php echo __d('user', 'Action', true);?></li>
        </ul>
        <?php
        $srNo = 1;

        foreach ($alerts as $alert) {
            ?>
            <ul class="job_list">
                <li><?php echo $srNo++; ?></li>
                <li>
                    <?php
                      echo $alert['Alert']['location'];
//                    $totalLocation = ClassRegistry::init('AlertLocation')->find('all', array('conditions' => array('AlertLocation.alert_id' => $alert['Alert']['id']), array('fields' => 'location')));
//                    //echo"<pre>"; pr($totalLocation);
//
//                    $i = 1;
//
//                    foreach ($totalLocation as $locId) {
//                        $location = ClassRegistry::init('Location')->field('name', array('Location.id' => $locId['AlertLocation']['location']));
//
//                        if (!empty($location)) {
//                            if ($i == 1) {
//                                echo $location;
//                            } else {
//                                echo " , " . $location;
//                            }
//                            $i = $i + 1;
//                        } else {
//                            echo"N/A";
//                        }
//                        $location = '';
//                    }
                    ?>


                </li>
                <li>
                    <?php //echo $alert['Alert']['designation'];  ?>
                    <?php
                    $designation = ClassRegistry::init('Skill')->field('name', array('Skill.id' => $alert['Alert']['designation'], 'Skill.type' => 'Designation'));
                    if (!empty($designation)) {
                        echo $designation;
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link('<i class="fa fa-pencil" aria-hidden="true"></i> '.__d('user', 'Edit', true), array('controller' => 'alerts', 'action' => 'edit', 'slug' => $alert['Alert']['slug']), array('class' => 'edit-pencil-btn','escape'=>false)); ?>
                    &nbsp;&nbsp;
                    <?php echo $this->Html->link('<i class="fa fa-trash-o" aria-hidden="true"></i> '.__d('user', 'Delete', true), array('controller' => 'alerts', 'action' => 'delete/' . $alert['Alert']['slug']), array('class' => 'delete-trash-btn','escape'=>false, 'confirm' =>__d('user', 'Are you sure you want to Delete ?', true))); ?>
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

        <div class="paginations">
            <?php //echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false,'rel'=>'nofollow', 'class' => 'first')); ?> 
            <?php if ($this->Paginator->hasPrev('Alert')) echo $this->Paginator->prev(__d('home', 'Previous', true), array('class' => 'prev disabled', 'escape' => false,'rel'=>'nofollow')); ?> 
            <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false,'rel'=>'nofollow')); ?> 
            <?php if ($this->Paginator->hasNext('Alert')) echo $this->Paginator->next(__d('home', 'Next', true), array('class' => 'next', 'escape' => false,'rel'=>'nofollow')); ?> 
            <?php //echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false,'rel'=>'nofollow')); ?> 

        </div>	
    </div>



<?php }else { ?>
    <div class="no_found"><?php echo __d('user', 'No record found.', true);?></div>
<?php } ?>
