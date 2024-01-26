<?php

$site_title = $this->requestAction(array('controller' => 'App', 'action' => 'getSiteConstant','title'));
//pr($data); die;
?>  
<script>
    function hidePaypal(){
         $('#pay_with_paypal').attr('href', 'javascript:void(0);');
        return true;
    }
    function hideInvoice(){
        
         $('#pay_with_invoice').attr('href', 'javascript:void(0);');
      
        return true;
    }
</script>
<div class="my_accnt">
    <?php echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu'); ?>
                <div class="col-xs-12 col-sm-3 col-md-9 col-lg-9">
                    <div class="info_dv">
                        <div class="heads">View Payment Information</div>
                        <div class="information_cntn">
                            <?php echo $this->element('session_msg'); ?>    
                             <div class="form_lst">
                                <label>Job Title</label>
                                <span class="rltv"><em><?php echo $_SESSION['data']['title']; ?> </em></span>
                             </div>
                             <div class="form_lst">
                                <label>Job Type</label>
                                <span class="rltv"><em><?php echo $site_title.' '.ucfirst($_SESSION['data']['type']); ?> </em></span>
                             </div>
                            <div class="form_lst">
                                <label>GST</label>
                                <span class="rltv"><em>
                                    <?php
                                    $gst=$_SESSION['amount']*10/100;
                                    echo CURRENCY . number_format( $gst, 2); ?> </em></span>
                             </div>
                             <div class="form_lst">
                                <label>Payable Amount</label>
                                <span class="rltv"><em><?php
                                $total=$gst+$_SESSION['amount'];
                                echo CURRENCY . number_format( $total, 2); ?> </em></span>
                             </div>
                            <div class="form_lst">
                                <div class="pro_row_left">
                                    <?php echo $this->Html->link('Back', array('controller' => 'jobs', 'action' => 'createJob'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow')); ?>
                                    <?php   echo $this->Html->link('Pay with Promo Code', array('controller' => 'jobs', 'action' => 'paywithpromocode'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow'));             ?>   
                                    <?php   echo $this->Html->link('Pay with PayPal', array('controller' => 'jobs', 'action' => 'processPayment'), array('id'=>'pay_with_paypal','class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow','onclick'=> 'return hideInvoice();')); ?>   
                                    <?php   echo $this->Html->link('Pay with Invoice', array('controller' => 'jobs', 'action' => 'paywithinvoice'), array('id'=>'pay_with_invoice','class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow','onclick'=> 'return hidePaypal();'));            
                                    ?>   
                                </div> 
                            </div>
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function applyCode(){
    if($('#JobPromoCode').val() == ''){
        $('#JobPromoCode').addClass('error');
        return;
    }    
    
    $.ajax({
            type : 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/applycode/"+$('#JobPromoCode').val(),
            cache: false,
            //data : $('#promocode').serialize(),
            beforeSend: function(){ $("#subTypeLoader").show(); },
            complete: function(){ $("#subTypeLoader").hide();},
            success: function(result) {
                $("#updatePrice").html(result);
            }
        });
}
function removeCoupon(){
    $.ajax({
            type : 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/applycode/",
            cache: false,
            //data : $('#promocode').serialize(),
            beforeSend: function(){ $("#subTypeLoader").show(); },
            complete: function(){ $("#subTypeLoader").hide();},
            success: function(result) {
                $("#updatePrice").html(result);
            }
        });
}
</script>




