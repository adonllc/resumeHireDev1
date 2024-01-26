<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php if ($this->Session->read('user_type') != 'recruiter') {
                echo $this->element('left_menu_candidate');
                } else {
                   echo $this->element('left_menu'); 
                }
                ?>
                <div class="col-sm-9 col-lg-9 col-xs-12">
                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-payments-boxes-top"><h2><i><?php echo $this->Html->image('front/home/payment-icon-top.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Payment History', true);?></span></h2></div>
                        <div class="information_cntn payment-history-bx">
                            <?php echo $this->element('session_msg'); ?>    

                            <div class="listing_page">
                                <div class="job_scroll">
                                    <div id="loaderID" style="display:none;position:absolute;margin-left:0px;margin-top:60px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
                                    <div id='listID'>
                                    
                                        <?php echo $this->element('payments/history'); ?>    
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