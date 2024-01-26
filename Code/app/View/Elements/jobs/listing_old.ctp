<?php if ($jobs) { ?>
    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
    <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
    <div id="loaderID" style="display:none;width: 50%;position:absolute;text-align: center;margin-top:191px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
    <?php
    $urlArray = array_merge(array('controller' => 'jobs', 'action' => 'listing', $separator));
    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID', 'complete' => '$("html, body").animate({ scrollTop: 200 }, "slow");'));
    ?>
    <?php echo $this->Form->create("Job", array("url" => "listing", "method" => "Post")); ?>
    <div class="listing_box_full listing_box_full_fulll">
        <div class="paging">
            <div class="noofproduct">
                <?php
                echo $this->Paginator->counter(
                        '<span>No. of Records </span><span class="badge-gray">{:start}</span><span> - </span><span class="badge-gray">{:end}</span><span> of </span><span class="badge-gray">{:count}</span>'
                );
                ?> 
            </div>
            <div class="pagination">
                <?php echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false, 'class' => 'first')); ?> 
                <?php if ($this->Paginator->hasPrev('Job')) echo $this->Paginator->prev('<i class="fa fa-arrow-left"></i>', array('class' => 'prev disabled', 'escape' => false)); ?> 
                <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false)); ?> 
                <?php if ($this->Paginator->hasNext('Job')) echo $this->Paginator->next('<i class="fa fa-arrow-right"></i>', array('class' => 'next', 'escape' => false)); ?> 
                <?php echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false)); ?> 
            </div>	
        </div>
        <?php
        $count = 1;
        foreach ($jobs as $job) {
            ClassRegistry::init('Job')->updateJobSearch($job['Job']['id']);

            if ($job['Job']['type'] == 'gold') {
                $class = 'listing_full_row_bg';
            } else {
                $class = '';
            }
            ?>
            <div class="listing_full_row <?php echo $class; ?> ">
                <div class="listing_col_left">
                    <div class="lisint_box_title"><?php echo $this->Html->link($job['Job']['title'], array('controller' => 'jobs', 'action' => 'detail', $job['Job']['slug']), array('class' => '')); ?>
                        <div class="listing_box_full_fulll listing_box_full_fulllnoww live_change ">
                            <div class="open_bt">
                                <?php echo $this->Html->link('Details', array('controller' => 'jobs', 'action' => 'detail', $job['Job']['slug']), array('class' => 'sstar')); ?>
                            </div>
                        </div>
                    </div>  



                    <div class="list_location_box"> <?php echo $job['Job']['company_name'] ? $job['Job']['company_name'] : 'N/A'; ?></div>  
                    <div class="data_row_ful_skil_content2">
                        <?php
                        echo $this->Text->truncate($job['Job']['description'], 400, array('html' => true));
                        ?></br>
        <!--                        <span>Industry :
                        <?php
                        $industry = ClassRegistry::init('Industry')->field('name', array('Industry.id' => $job['User']['industry']));
                        if ($industry) {
                            echo $industry;
                        } else {
                            echo 'N/A';
                        }
                        ?>
                        </span>-->

                    </div>     
                    <div class="list_bot_boox">
                        <div class="list_bot_boox_table">
                            <div class="list_bot_boox_row">

                                <div class="list_bot_boox_col">
                                    <?php echo $this->Html->image('front/full_time_icon.png', array('alt' => 'icon')); ?>
                                    <?php
                                    global $worktype;
                                    echo $job['Job']['work_type'] ? $worktype[$job['Job']['work_type']] : 'N/A';
                                    ?>
                                </div>
                                <div class="list_bot_boox_col">
                                    <?php echo $this->Html->image('front/location_icon.png', array('alt' => 'icon')); ?> <?php echo substr($job['City']['city_name'] . ', ' . $job['State']['state_name'] . ', ' . $job['Job']['postal_code'], 0, 40); ?>
                                </div>
                                <div class="list_bot_boox_col">
                                    <?php echo $this->Html->image('front/calander_icon.png', array('alt' => 'icon')); ?>Time Posted:<?php echo date('jS F,Y h:i A', strtotime($job['Job']['created'])); ?>
                                </div>
                            </div>
                        </div>
                    </div>                       
                </div>
                <div class="listing_col_right">
                    <div class="open_deta_bt">

                        <div class="open_bt detail_bt detail_bt_nee">
                            <div class="addthis_button addthis_button_mar" addthis:url="<?php echo HTTP_PATH . '/jobs/detail/' . $job['Job']['slug'] ?>">Share</div>


                            <?php
                            if ($this->Session->read('user_type') != 'recruiter') {
                                $short_status = classregistry::init('ShortList')->find('first', array('conditions' => array('ShortList.user_id' => $this->Session->read('user_id'), 'ShortList.job_id' => $job['Job']['id'])));
                                if (empty($short_status)) {
                                    echo $this->Html->link(' Save Job', array('controller' => 'jobs', 'action' => 'JobSave', $job['Job']['slug']), array('class' => 'sstar', 'escape' => false));
                                } else {
                                    echo $this->Html->link(' Already Saved', 'javascript:void(0);', array('class' => 'sstar', 'escape' => false));
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $count++;
        }
        ?>
    </div>
    <?php echo $this->Form->end(); ?>
    <div class="paging">
        <div class="noofproduct">
            <?php
            echo $this->Paginator->counter(
                    '<span>No. of Records </span><span class="badge-gray">{:start}</span><span> - </span><span class="badge-gray">{:end}</span><span> of </span><span class="badge-gray">{:count}</span>'
            );
            ?> 
        </div>
        <div class="pagination">
            <?php echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false, 'class' => 'first')); ?> 
            <?php if ($this->Paginator->hasPrev('Job')) echo $this->Paginator->prev('<i class="fa fa-arrow-left"></i>', array('class' => 'prev disabled', 'escape' => false)); ?> 
            <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false)); ?> 
            <?php if ($this->Paginator->hasNext('Job')) echo $this->Paginator->next('<i class="fa fa-arrow-right"></i>', array('class' => 'next', 'escape' => false)); ?> 
            <?php echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false)); ?> 

        </div>	
    </div>
<?php } else { ?>
    <div class="listing_box_full">
        <div class="listing_full_row listing_full_row_bg">
            No record found.
        </div>
    </div>
<?php } ?>
