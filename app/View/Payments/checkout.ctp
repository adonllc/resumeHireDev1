<?php echo $this->Html->script('jquery.js'); ?>

<script type="text/javascript" language="javascript">
    function show_div() {
        $('.loader_img').show();
    }
    $(function(){
         setTimeout(function() {
            $('#payment_form').submit();
        }
        , 2000);
    })   
</script>
<title>
    <?php
    $paypal_email = classRegistry::init('Admin')->field('Admin.paypal_email', array('id' => 1));
    $paypal_url = classRegistry::init('Admin')->field('Admin.paypal_url', array('id' => 1));
    $site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
    
    if (isset($title_for_layout))
            echo $title_for_layout;
        else
            echo $site_title;
        // - Modification de #Username
        ?>
    <?php //echo TITLE_FOR_PAGES;?> </title>
<div class="active_vb">
    <div class="wrapper">
        <div class="modalv2 mpre"> 
            <div class="activate-popup alum-popup pricing">
                <div class="content">
                    <div class="paypal_process_t" style="text-align:center; padding-top:80px">
                        <div class="">
                            <!--<h1>Please wait, your payment is being processed...</h1>-->
                            
                            <h1><?php echo __d('user', 'Please wait, We are redirecting you to payment gateway', true);?>...</h1>
                            
                            
                            <div><?php echo __d('user', 'Please do not refresh or click on back browser button', true);?></div>
                           <div class="loder_img cerntekej">
                                <span class="loading_img"></span>
                                <br/>
                                <?php //echo $this->Html->image('logo.png', array('alt' => "")); ?>
                                <div style="background: #444; padding: 13px 25px; border-radius: 7px; display: inline-block">
                                <?php

                                $logoImage = classRegistry::init('Admin')->field('Admin.logo', array('id' => 1));
                                // pr($logoImage);
                                if (isset($logoImage) && !empty($logoImage)) {
                                    $logo = DISPLAY_FULL_WEBSITE_LOGO_PATH . $logoImage;
                                } else {
                                    $logo = ' ';
                                }

                                echo $this->Html->image($logo, array(), array('alt' => $site_title, 'title' => $site_title));
                                ?>
                                    </div>
                                <br/>
                                <br/>
                                <br/>
                                <?php echo $this->Html->image('loader_large_blue.gif', array('alt' => "")); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rws">
                    <div class="full_input forgot_text">                        
                        <div class="rgt_input">
                            <form name="payment_form" action="<?php echo $paypal_url; ?>" method="post" id="payment_form">
                                    <input type="hidden" name="business" value="<?php echo $paypal_email; ?>">
                                    <!--<input type="hidden" name="currency_code" value="AUD">-->
                                    <input type="hidden" name="currency_code" value="<?php echo CURR; ?>">
                                    <input type="hidden" name="cmd" value="_xclick">
                                    <input type="hidden" name="item_number" value="<?php echo $item_number; ?>"/>
                                    <input type="hidden" name="item_name" value="Payment for puchasing <?php echo $paymentInfo['Plan']['plan_name'];?> plan on <?php echo $site_title;?>"/>
                                    <input type="hidden" name="amount" value="<?php echo $amount;?>">
                                    <input type="hidden" name="no_shipping" value="1">
                                    <input type="hidden" name="lc" value="EN"/>
                                    <input type="hidden" name="return" value="<?php echo $success_url;?> "/>
                                    <input type="hidden" name="cancel_return" value="<?php echo $cancel_url; ?>"/>
                                    <input type="hidden" name="notify_url" value="<?php echo $notify_url;?> "/>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="invite-now"></div>
            </div>        
        </div>
    </div>
</div>   
