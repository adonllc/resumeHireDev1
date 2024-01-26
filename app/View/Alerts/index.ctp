<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                <div class="col-lg-9 col-sm-9">
                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-manage-boxes">
                            <h2><i><?php echo $this->Html->image('front/home/manage-icon2.png', array('alt' => '')); ?></i>
                                <span><?php echo __d('user', 'Manage Alerts', true);?></span></h2>
                            <div class="add-alert">
                                <?php echo $this->Html->link(__d('user', 'Add Alert', true), array('controller' => 'alerts', 'action' => 'add'), array('class' => '', 'escape' => false,'rel'=>'nofollow')); ?>
                            </div>
                        </div>
                        <div class="information_cntn payment-history-bx">
                           

                            <?php echo $this->element('session_msg'); ?>    

                            <div class="listing_page">
                                <div class="job_scroll">
                                    <div id="loaderID" style="display:none;position:absolute;margin-left:100px;margin-top:250px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
                                    <div id='listID'>
                                        <div class="over_flow_mange">
                                        <?php echo $this->element('alerts/index'); ?>    
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
</div>