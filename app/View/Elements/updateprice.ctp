<div class="form_lst">
    <label>Have Promo Code?</label>
    <span class="rltv">
        <em>
            <?php
            $pp = '';
            if (isset($_SESSION['promo_code'])) {
                $pp = $_SESSION['promo_code'];
            }
            ?>
            <div class="left_ldin"><?php echo $this->Form->text('Job.promo_code', array('class' => 'uuuucccc', 'placeholder' => 'Enter promo code', 'maxlength' => 10, 'value' => $pp)); ?> </div>
            <div class="rght_rd">
                <div id="subTypeLoader" class="subTypeLoader"><?php echo $this->Html->image('loading.gif'); ?></div>
                <span class="applycc" onclick="applyCode();">Apply</span>
            </div>
            <div class="proseesion"><?php echo $this->element('session_msg'); ?></div>    

        </em>
    </span>
</div>
<?php
$payAmount = $_SESSION['amount'];
if (isset($_SESSION['dis_amount']) && $_SESSION['dis_amount'] != '') {
    ?>
    <div class="form_lst mmzro">
        <label>Total Amount</label>
        <span class="rltv">
            <em>
                <?php echo CURRENCY . number_format($_SESSION['amount'], 2); ?>
            </em>
        </span>
    </div>
    <div class="form_lst mmzro">
        <label>Discount</label>
        <span class="rltv">
            <em>
                - <?php echo CURRENCY . number_format($_SESSION['dis_amount'], 2); ?>
                <a class="removepp" onclick="removeCoupon()" href="javascript:void(0)" >Remove Promo Code</a>
            </em>
        </span>
    </div>

    <?php
    $payAmount = $_SESSION['amount'] - $_SESSION['dis_amount'];
}
?>
<div class="form_lst">
    <label>GST</label>
    <span class="rltv">
        <em>
            <?php 
            $gst=$payAmount*10/100;
            echo CURRENCY . number_format($gst, 2); ?>
        </em>
    </span>
</div>
<div class="form_lst">
    <label>Payable Amount</label>
    <span class="rltv">
        <em>
            <?php
            $payAmount=$payAmount+$gst;
            echo CURRENCY . number_format($payAmount, 2); ?>
        </em>
    </span>
</div>

<div class="form_lst sssss">
    <label>&nbsp;</label>
    <span class="rltv">
        <div class="pro_row_left">
            <?php 
            if($payAmount != 0){
                if(isset($_SESSION['dis_amount']) && $_SESSION['dis_amount'] !=''){
                    echo $this->Html->link('Pay with PayPal', array('controller' => 'jobs', 'action' => 'processPayment'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow')); 
                    echo $this->Html->link('Pay with Invoice', array('controller' => 'jobs', 'action' => 'paywithinvoice'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow')); 
                }
                //echo $this->Html->link('Pay with PayPal', array('controller' => 'jobs', 'action' => 'processPayment'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow')); 
            }else{
              echo $this->Html->link('Continue', array('controller' => 'jobs', 'action' => 'addFreeJob'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow'));   
            }
             ?>   
            <?php echo $this->Html->link('Back', array('controller' => 'jobs', 'action' => 'payment'), array('class' => 'input_btn rigjt', 'escape' => false,'rel'=>'nofollow')); ?>
        </div> 
    </span>
</div>