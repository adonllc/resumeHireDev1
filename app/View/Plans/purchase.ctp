
<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="container">
            <div class="row">
                <div class="my_acc">
                    <?php if ($this->Session->read('user_type') != 'recruiter') {
                echo $this->element('left_menu_candidate');
                } else {
                   echo $this->element('left_menu'); 
                }
                ?>
                    <div class="col-lg-9 col-sm-9">
                        <div class="my-profile-boxes">
                            <div class="my-profile-boxes-top my-education-boxes"><h2><i><?php echo $this->Html->image('front/home/member-purchase-icon.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Purchase Membership Plan', true); ?></span></h2></div>
                            <div class="information_cntn">
                                <?php echo $this->element('session_msg'); ?>   
                                <?php echo $this->Form->create(null, array("method" => "Post", 'name' => 'purchaseplan', 'id' => 'purchaseplan')); ?>
                                <div class="pl_main_bx">
                                <div class="row">
                                    <?php
                                    $cplan = Classregistry::init('Plan')->getcurrentplan($this->Session->read('user_id'));
                                    $futureplan = Classregistry::init('Plan')->getfutureplan($this->Session->read('user_id'));
                                    $cplanId = 0;
                                    $sdate = date('Y-m-d');
                                    $sdateDIS = date('M d, Y');
                                    if ($cplan) {
                                        $cplanId = $cplan['UserPlan']['plan_id'];
                                        $sdate = date('Y-m-d', strtotime($cplan['UserPlan']['end_date'] . ' + 1 days'));
                                        $sdateDIS = date('M d, Y', strtotime($cplan['UserPlan']['end_date'] . ' + 1 days'));
                                    }
                                    global $planFeatuersMax;
                                    global $planFeatuers;
                                    global $planFeatuersDis;
                                    global $planType;
                                    global $planFeatuersHelpText;
                                    if ($plans) {
                                        foreach ($plans as $plan) {
                                            $tpvalue = $plan['Plan']['type_value'];
                                            if ($plan['Plan']['type'] == 'Months') {
                                                $edate = date('Y-m-d', strtotime($sdate . " + $tpvalue Months"));
                                                $edateDIS = date('M d, Y', strtotime($sdate . " + $tpvalue Months"));
                                            } else {
                                                $edate = date('Y-m-d', strtotime($sdate . " + $tpvalue Years"));
                                                $edateDIS = date('M d, Y', strtotime($sdate . " + $tpvalue Years"));
                                            }
                                            ?>
                                            <div class="col-xs12 col-md-6 col-lg-6">
                                            <div class="pl_main">
                                                <div class="pl_name"><?php echo $plan['Plan']['plan_name']; ?></div>
                                                <div class="pl_amount"><?php echo CURR . ' ' . $plan['Plan']['amount']; ?> <span class="tmymy"> <?php echo __d('user', 'for', true); ?> <?php echo $plan['Plan']['type_value'] . ' ' . $plan['Plan']['type']; ?></span></div>
                                                <?php
                                                if ($futureplan == 0) {
                                                    if ($cplanId == $plan['Plan']['id']) {
                                                        ?>
                                                        <div class="pl_buy ccplan" onclick="notseelct()"><?php echo __d('user', 'Current Plan', true); ?></div>
                                                    <?php } else { ?>
                                                        <div class="pl_buy" onclick="setplanid(<?php echo $plan['Plan']['id']; ?>, '<?php echo $plan['Plan']['plan_name']; ?>', '<?php echo $sdateDIS; ?>', '<?php echo $edateDIS; ?>', '<?php echo $plan['Plan']['amount'] ?>')"><?php echo __d('user', 'Buy this Plan', true); ?></div>
                                                        <?php
                                                    }
                                                } else {
                                                    if ($cplanId == $plan['Plan']['id']) {
                                                        ?>
                                                        <div class="pl_buy ccplan" onclick="notseelct()"><?php echo __d('user', 'Current Plan', true); ?></div>
                                                    <?php } else { ?>
                                                        <div class="pl_buy" onclick="notseelctNot()"><?php echo __d('user', 'Buy this Plan', true); ?></div>
                                                        <?php
                                                    }
                                                }
                                                if ($cplanId) {
                                                    ?>
                                                    <div class="plied_img">
                                                        <input class="plcheckbox" name="data[User][rememberme<?php echo $plan['Plan']['id']; ?>]" id="check<?php echo $plan['Plan']['id']; ?>" value="<?php echo $plan['Plan']['id']; ?>" type="checkbox" onclick="checkcheckbox(<?php echo $plan['Plan']['id']; ?>)"> <?php echo __d('user', 'Apply Immediately', true); ?>
                                                        <div class="help_bxse"><i class="fa fa-info-circle" aria-hidden="true"></i><div class="uxicon_help"><?php echo __d('user', 'If you select this option plan will be applied from today and any remaining feature of your current plan will not be carry forwarded.', true); ?></div></div>
                                                    </div>        
                                                <?php } ?>    
                                                <div class="jobmembership-plan">
                                                <div class="pl_ft">
                                                    <?php
                                                    $fvalues = $plan['Plan']['fvalues'];
                                                    $featureIds = explode(',', $plan['Plan']['feature_ids']);
//                                                    print_r($featureIds);
                                                    $fvalues = json_decode($plan['Plan']['fvalues'], true);
                                                    if ($featureIds) {
                                                        echo '<ul class="pl_fflist">';
                                                        foreach ($featureIds as $fid) {
                                                            $ddd = '<li>';
                                                            if (array_key_exists($fid, $fvalues)) {
                                                                if ($fvalues[$fid] == $planFeatuersMax[$fid]) {
                                                                    $joncnt = __d('user', 'Unlimited', true);
                                                                    $ddd .= '<b>'.__d('user', 'Unlimited', true).'</b>';
                                                                } else {
                                                                    $joncnt = $fvalues[$fid];
                                                                    $ddd .= '<b>' . $fvalues[$fid] . '</b>';
                                                                }
                                                            }

                                                            if (array_key_exists($fid, $planFeatuersHelpText)) {
                                                                $timecnt = $plan['Plan']['type_value'] . ' ' . $plan['Plan']['type'];
                                                                if ($fid == 1) {
                                                                    $farray = array('[!JOBS!]', '[!TIME!]', '[!RESUME!]');
                                                                    $toarray = array($joncnt, $timecnt, '');
                                                                } elseif ($fid == 2) {
                                                                    $farray = array('[!JOBS!]', '[!TIME!]', '[!RESUME!]');
                                                                    $toarray = array('', $timecnt, $joncnt);
                                                                }

                                                                $msgText = str_replace($farray, $toarray, $planFeatuersHelpText[$fid]);
                                                                $disText = '<div class="help_bxse"><i class="fa fa-info-circle" aria-hidden="true"></i><div class="uxicon_help">' . $msgText . '</div></div>';
                                                            } else {
                                                                $disText = '';
                                                            }
                                                            $ddd .= ' ' . $planFeatuersDis[$fid] . $disText . '</li>';
                                                            echo $ddd;
                                                        }
                                                        echo '</ul>';
                                                    }
                                                    ?>
                                                </div>
                                                </div>

                                            </div>
                                            </div>
                                        <?php } ?>
                                        <div class="purplan_note"><?php echo __d('user', 'Note: If you upgrade plan than it will be applied after your current plan end date.', true); ?></div>
                                        <?php
                                    } else {
                                        echo '<div class="not_pla_av pbtnn">' . __d('user', 'Currently no plan is activated by the admin', true) . '</div>';
                                    }
                                    ?>
                                </div> 
                                </div> 
                                <?php echo $this->Form->hidden('Plan.id'); ?>
                                <?php echo $this->Form->hidden('Plan.payment_option', array('value' => '')); ?>
                                <?php echo $this->Form->hidden('Plan.aplimp', array('value' => 0)); ?>
                                <?php echo $this->Form->end(); ?> 
                            </div>        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade paymentmethod-modal" id="paymentmethod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo __d('home', 'Select Payment Method', true); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body login_modal_body">
       <div id="signinform">
                <?php echo $this->Html->script('jquery.validate.js'); ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        $("#paymentmethodpop").submit(function (e) {
                            e.preventDefault();
                        }).validate({
                            submitHandler: function (form) {
                                var value = $('#paymentmethodpop input[type=radio]:checked').val();
//                            alert(value);
                                $("#PlanPaymentOption").val(value);
                                $("#purchaseplan").submit();
                                //submit via ajax
                                return false; //This doesn't prevent the form from submitting.

                            }
                        });
//                    jQuery("#paymentmethodpop").validate({
//                    submitHandler: function (form) {
//                        var value = $('input[name=data[Plan][payment_method]]:checked').val();
//                        alert(value);
//                        $("#PlanPaymentOption").val(value);
//                        return false;
//                    }
//                    });
                    });
                </script>

                <div class="dd">
                    <div id="loaderID" style="display:none;position:absolute;margin-top:250px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
                    <?php echo $this->Form->create(Null, array('class' => 'cssForm', 'name' => 'userlogin', 'id' => 'paymentmethodpop')); ?>

                    <div class="entro" id="textplan"><?php echo __d('user', 'This plan will apply from today and any remaining feature of the current plan will not carry forward', true) . '.'; ?></div>

                    <div class="errormsg" id="msgjoh"></div>
                    <div class="form-group new-form-group">
                        <div class="row">
                            <div class="col-md-5 col-sm-6 modal_lable"><b><?php echo __d('user', 'Payment Method', true); ?></b></div>
                            <div class="col-md-7 col-sm-6">
                                <?php // echo $this->Form->text('User.email_address', array('id' => 'userEmail', 'class' => "required email text_input_box", 'placeholder' => 'Email Address')) ?>
                                <div class="rltv">
                                    <div class="dty">
                                        <?php
                                        $options = array('paypal' => '<label for="PlanPaymentMethodPaypal">' . __d('user', 'Paypal', true) . '</label>', "stripe" => '<label for="PlanPaymentMethodStripe">' . __d('user', 'Stripe', true) . '</label>');
                                        echo $this->Form->radio('Plan.payment_method', $options, array('escape' => false, 'label' => false, 'legend' => false, 'separator' => '</div><div class="dty">', 'default' => '1', 'class' => 'required PaymentMethod'));
                                        ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group new-pop">
                        <div class="row">
                            <div class="col-md-4 col-sm-6">&nbsp;</div>  
                            <div class="col-md-8 col-sm-6">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        <?php echo $this->Form->submit(__d('user', 'Submit', true), array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>

                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>

            </div>
      </div>
    </div>
  </div>
</div>


<div id="paymentmethoddf" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __d('home', 'Select Payment Method', true); ?></h4>
            </div>
            <div id="signinform">
                <?php echo $this->Html->script('jquery.validate.js'); ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        $("#paymentmethodpop").submit(function (e) {
                            e.preventDefault();
                        }).validate({
                            submitHandler: function (form) {
                                var value = $('#paymentmethodpop input[type=radio]:checked').val();
//                            alert(value);
                                $("#PlanPaymentOption").val(value);
                                $("#purchaseplan").submit();
                                //submit via ajax
                                return false; //This doesn't prevent the form from submitting.

                            }
                        });
//                    jQuery("#paymentmethodpop").validate({
//                    submitHandler: function (form) {
//                        var value = $('input[name=data[Plan][payment_method]]:checked').val();
//                        alert(value);
//                        $("#PlanPaymentOption").val(value);
//                        return false;
//                    }
//                    });
                    });
                </script>
                <div class="modal-body login_modal_body">
                    <div id="loaderID" style="display:none;position:absolute;margin-top:250px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
                    <?php echo $this->Form->create(Null, array('class' => 'cssForm', 'name' => 'userlogin', 'id' => 'paymentmethodpop')); ?>

                    <div class="entro" id="textplan"><?php echo __d('user', 'This plan will apply from today and any remaining feature of the current plan will not carry forward', true) . '.'; ?></div>

                    <div class="errormsg" id="msgjoh"></div>
                    <div class="form-group new-form-group">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 modal_lable"><b><?php echo __d('user', 'Payment Method', true); ?></b></div>
                            <div class="col-md-8 col-sm-6">
                                <?php // echo $this->Form->text('User.email_address', array('id' => 'userEmail', 'class' => "required email text_input_box", 'placeholder' => 'Email Address')) ?>
                                <div class="rltv">
                                    <div class="dty">
                                        <?php
                                        $options = array('paypal' => '<label for="PlanPaymentMethodPaypal">' . __d('user', 'Paypal', true) . '</label>', "stripe" => '<label for="PlanPaymentMethodStripe">' . __d('user', 'Stripe', true) . '</label>');
                                        echo $this->Form->radio('Plan.payment_method', $options, array('escape' => false, 'label' => false, 'legend' => false, 'separator' => '</div><div class="dty">', 'default' => '1', 'class' => 'required PaymentMethod'));
                                        ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group new-pop">
                        <div class="row">
                            <div class="col-md-4 col-sm-6">&nbsp;</div>  
                            <div class="col-md-8 col-sm-6">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        <?php echo $this->Form->submit(__d('user', 'Submit', true), array('div' => false, 'label' => false, 'class' => 'input_btn')); ?>

                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
    function notseelct() {
        // alert('You can not buy your curent plan.');
    }
    function notseelctNot() {
        alert("<?php echo __d('user', 'You can not buy this plan because you already purchesed one plan in advanced.', true); ?>");
    }
    function setplanid(pid, pname, sdate, edate, amount) {

        if ($('#check' + pid).is(':checked')) {
            $('#PlanAplimp').val(pid);
        } else {
            $('#PlanAplimp').val('0');
        }

        if ($('#PlanAplimp').val() == 0) {
            var textplan = '<?php echo __d('user', 'This plan will be applied from', true); ?> ' + sdate + ' to ' + edate + '.';
            $('#PlanId').val(pid);
            $('#textplan').val(textplan);
        } else {
            var textplan = "<?php echo __d('user', 'This plan will apply from today and any remaining feature of the current plan will not carry forward', true) . '.'; ?>";
            $('#PlanId').val(pid);
            $('#textplan').val(textplan);
        }
        if (amount <= 0) {
            $("#PlanPaymentOption").val('paypal');
            $("#purchaseplan").submit();
        } else {
            $('#paymentmethod').modal('show');
        }

    }

    function checkcheckbox(pid) {
        if ($('#check' + pid).is(':checked')) {
            $('.plcheckbox').prop('checked', false);
            $('#check' + pid).prop('checked', true);
        } else {
            $('.plcheckbox').prop('checked', false);
        }


    }
</script>