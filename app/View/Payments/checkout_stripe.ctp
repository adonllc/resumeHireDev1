
<script>
    
    $(document).ready(function () {
        $('.showstripdiv').css('display','none');
        $("#searchform").validate();
        
        
        
    })

</script>

<div class="my_accnt">
     <?php echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="container">
            <div class="row">
                <div class="my_acc">
                    <?php echo $this->element('left_menu'); ?>
                    <div class="col-lg-9">
                    <div class="desck_mid_right_topdd">
                        <span><?php echo __d('user', 'Make Payment', true);?></span></div>
                    <div class="descl_bxz">
                        <div class="my_profl">


                            <div class="myacco_top">
                                <div class="myacco">
                                    <div class="mu_top"><?php echo __d('user', 'Pay', true);?> <?php echo $_SESSION['Config']['language'] == 'en'?CURRENCY.$amount: $amount.CURRENCY ; ?> <?php echo __d('user', 'via Stripe Payment Gateway', true);?></div>

                                    <div class="ee" id="errormessageac"><?php echo $this->Session->flash(); ?></div>
                                    <?php echo $this->Form->create(Null, array('id' => 'searchform', 'enctype' => 'multipart/form-data')); ?>
                                    <input type="hidden" value="<?php echo $amount; ?>" id="total_amount" />
                                    <input type="hidden" value="<?php echo $amount; ?>" id="non_wallet_paybal_amount" />
                                    <div class="dr_fom">
                                        <div id="payment_box">
                                            <?php 
                                            $payableAmount = $amount; ?>
                                            
                                            <input type="hidden" value="<?php echo $payableAmount; ?>" id="wallet_paybal_amount" />
                                            <?php echo $this->Form->text('Payment.paybal_amount', array('type'=>'hidden','value' => $payableAmount, 'id' => 'paybal_amount')); ?>
                                            <div class="in_edit_input">
                                                <label><?php echo __d('user', 'Final Payable Amount', true);?></label>
                                                <div class="in_edit_bx fnlamnt" id="final_amount">
                                                    <?php echo $_SESSION['Config']['language'] == 'en'?CURRENCY.number_format($payableAmount,2): number_format($payableAmount,2).CURRENCY;;?>
                                                </div>
                                            </div>
                                            <?php if($payableAmount == '0'){
                                                $showstripdiv = 'showstripdiv';
                                                $showwalletdiv = '';
                                            }else{
                                                $showstripdiv = '';
                                                $showwalletdiv = 'showwalletdiv';
                                            }
    ?>
                                            <div class="paybystrip <?php echo $showstripdiv ?>">
                                       
                                            
                                                <div class="in_edit_input">
                                                    <label><?php echo __d('user', 'Credit Card Number', true);?></label>
                                                    <div class="in_edit_bx">
                                                        <?php echo $this->Form->text('Payment.card_no', array('class' => "required number", 'placeholder' => __d('user', 'Credit Card Number', true), 'autocomplete' => 'off', 'id' => 'card_no', 'minlength' => '12', 'maxlength' => '16')); ?>
                                                    </div>
                                                </div>
                                                <div class="in_edit_input">
                                                    <label><?php echo __d('user', 'Name on Card', true);?></label>
                                                    <div class="in_edit_bx">
                                                        <?php echo $this->Form->text('Payment.name', array('class' => "required", 'placeholder' => __d('user', 'Name on Card', true), 'autocomplete' => 'off', 'id' => 'name_on_card')); ?>
                                                    </div>
                                                </div>
                                                <div class="in_edit_input">
                                                    <label><?php echo __d('user', 'Valid Thru(Month/Year)', true);?></label>
                                                    <div class="in_edit_bx">
                                                        <div class="src_edit_select expdt_lft">
                                                            <span>
                                                                <?php
                                                                for ($month = 1; $month <= 12; $month++) {
                                                                    $exp_month[$month] = $month;
                                                                }
                                                                echo $this->Form->select('Payment.exp_month', $exp_month, array('class' => "required", 'empty' => false, 'id' => 'exp_month', 'empty' => __d('user', 'Select Month', true)));
                                                                ?>
                                                            </span>
                                                        </div>
                                                        <div class="src_edit_select expdt_rgt">
                                                            <span>
                                                                <?php
                                                                for ($k = date('Y'); $k <= date('Y') + 20; $k++) {
                                                                    $exp_year[$k] = $k;
                                                                }
                                                                echo $this->Form->select('Payment.exp_year', $exp_year, array('class' => "required", 'empty' => false, 'id' => 'exp_year', 'empty' => __d('user', 'Select Year', true)));
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="in_edit_input">
                                                    <label><?php echo __d('user', 'CVV Number', true);?></label>
                                                    <div class="in_edit_bx">
                                                        <?php echo $this->Form->password('Payment.cvv_no', array('class' => "required cvc_no number", 'placeholder' => __d('user', 'CVV Number', true), 'autocomplete' => 'off', 'id' => 'cvv_no', 'minlength' => '3', 'maxlength' => '4', 'size' => '4')); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="in_edit_input">
                                                    <label>&nbsp;</label>
                                                    <div class="in_edit_bx">
                                                        <div class="btn_form_end_row" id="sub_btn_dive_rg">

                                                            <?php
                                                            echo $this->Form->submit(__d('user', 'Pay Now', true), array('class' => 'ancor_btn', 'div' => false));
                                                            ?>
                                                        </div>
                                                        <div class="btn_form_end_row" id="sub_btn_dive_loader_rg" style="display: none;">
                                                            <div class="btm_loader"> <?php echo $this->Html->image('loading.svg'); ?><?php echo __d('user', 'Please wait...', true);?></div>
                                                        </div>
                                                    </div>
                                                </div>

                                           
                                            </div>
                                        </div>
                                    </div>
                                    <?php echo $this->Form->end(); //g-recaptcha-response  ?>
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