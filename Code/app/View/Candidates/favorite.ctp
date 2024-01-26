<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu'); ?>
                <div class="col-sm-9 col-lg-9 col-xs-12">
                    <div class="my-profile-boxes">
                        <div class="my-profile-boxes-top my-favorite-boxes"><h2><i><?php echo $this->Html->image('front/home/favorite-icon.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Favorite Candidates', true);?></span></h2></div>
                        <div class="information_cntn payment-history-bx">
                            <?php echo $this->element('session_msg'); ?>    

                            <div class="listing_page">
                                <div class="job_scroll">
                                    <div id="loaderID" style="display:none;position:absolute;margin-left:0px;margin-top:250px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
                                    <div id='listID'>
                                        <?php echo $this->element('candidates/favorite'); ?>    
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