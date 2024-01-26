<?php if ($experiences) { ?>
    <div class="col-lg-12">
        <section class="panel">
            <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
            <div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
          
            <?php echo $this->Form->create("User", array("url" => "index", "method" => "Post")); ?>
            <div class="columns mrgih_tp">
                
                <div class="panel-body">
                    <section id="no-more-tables">
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th class="sorting_paging">Company Name</th>
                                    <th class="sorting_paging">From(MM/YY)</th>
                                    <th class="sorting_paging">Until(MM/YY)</th>
                                    <th class="sorting_paging">Job Role</th>
                                    <th><i class=" fa fa-gavel"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $dd = 1;
                                foreach ($experiences as $experience) {?>
                                    <tr>
                                        <td data-title="Certificate"><?php echo  $experience['Experience']['company_name'];?> </td>
                                        <td data-title="Created"><?php echo date('M/Y', strtotime($experience['Experience']['fdate'])); ?></td>
                                        <td data-title="Created"><?php echo date('M/Y', strtotime($experience['Experience']['tdate'])); ?></td>
                                        <td data-title="Created"><?php echo  $experience['Experience']['job_role'];?></td>
                                        <td data-title="Action">
                                            <?php echo $this->Html->link('<i class="fa fa-pencil"></i>', array("controller" => "candidates", "action" => 'experience', $cslug,$experience['Experience']['slug']), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Edit')); ?>
                                            <?php echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'candidates', 'action' => 'deleteExperience', $experience['Experience']['slug'],$cslug), array('class' => 'btn btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false, 'title' => 'Delete')); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
           
            <?php echo $this->Form->end(); ?>
        </section>
    </div>
<?php } else { ?>
    <div class="columns mrgih_tp">
        <table class="table table-striped table-advance table-hover table-bordered">
            <tr>
                <td><div id="noRcrdExist" class="norecext">No Record Found.</div></td>
            </tr>
        </table>
    </div>
    <?php }
?>