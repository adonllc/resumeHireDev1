<?php echo $this->Html->script('facebox.js'); ?>
<?php echo $this->Html->css('facebox.css'); ?>
<script type="text/javascript">
    $(document).ready(function($) {
        $('.close_image').hide();
        $('a[rel*=facebox]').facebox({
            loadingImage : '<?php echo HTTP_IMAGE ?>/loading.gif',
            closeImage   : '<?php echo HTTP_IMAGE ?>/close.png'
        })
    })
</script>

<?php
if ($payments) {
    $this->Paginator->_ajaxHelperClass = "Ajax";
    $this->Paginator->Ajax = $this->Ajax;
    $this->Paginator->options(array('update' => 'listID',
        'url' => array('controller' => 'payments', 'action' => 'history', $separator),
        'indicator' => 'loaderID'));
    ?>
    <div class="right_child_sec_over">
    <div class="job_content" >
    <ul class="job_heading">
        <li><?php echo __d('user', 'Sr. No.', true);?></li>
        <li><?php echo __d('user', 'Plan Name', true);?></li>
        <li><?php echo __d('user', 'Amount', true);?></li>
        <li><?php echo __d('user', 'Transaction Id', true);?></li>
        <li><?php echo __d('user', 'Start Date', true);?></li>
        <li><?php echo __d('user', 'End Date', true);?></li>
        <li><?php echo __d('user', 'Paid On', true);?></li>
        <li class="pay-action"><?php echo __d('user', 'Action', true);?></li>
    </ul>
    <?php
    $srNo = 1;
//    echo '<pre>';
//    print_r($payments);exit;
    foreach ($payments as $payment) {
        ?>
        <ul class="job_list">
            <li><?php echo $srNo++; ?></li>
            <li class="jobdi plan-btn"><a href="#info<?php echo $payment['Payment']['id']; ?>" rel="facebox" title="View" class="btn btn-info btn-xs"><?php echo $payment['Plan']['plan_name']; ?></a></li>
            <li class="jobdi"><?php echo CURR.' '.$payment['UserPlan']['amount']; ?></li>
            <li class="transaction-payment"><span><?php echo $payment['Payment']['transaction_id']; ?></span></li>
            <li><?php echo date('M d, Y', strtotime($payment['UserPlan']['start_date'])); ?></li>
            <li><?php echo date('M d, Y', strtotime($payment['UserPlan']['end_date'])); ?></li>
            <li><?php echo date('M d, Y', strtotime($payment['UserPlan']['created'])); ?></li>
            <li class="jobdi pay-action">
                <a href="#invoiceinfo<?php echo $payment['Payment']['id']; ?>" rel="facebox" title="<?php echo __d('user', 'Preview', true) ?>" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                <?php echo $this->Html->link('<i class="fa fa-download"></i>', array('controller' => 'users', 'action' => 'generateinvoice', $payment['Payment']['slug']), array('escape' => false, 'rel' => 'nofollow','class'=>'btn btn-danger btn-xs','title'=>__d('user', 'Download Invoice', true))); ?>
            </li>
        </ul>
        <?php
    }
    ?>
     </div>
     </div>
        <div class="paging">
            <div class="noofproduct">
                <?php
                echo $this->Paginator->counter(
                        '<span>'.__d('user', 'No. of Records', true).' </span><span class="">{:start}</span><span> - </span><span class="">{:end}</span><span> '.__d('user', 'of', true).' </span><span class="">{:count}</span>'
                );
                ?> 
            </div>
    
            <div class="pagination">
                <?php echo $this->Paginator->first('<i class="fa fa-arrow-circle-o-left"></i>', array('escape' => false, 'class' => 'first')); ?> 
                <?php if ($this->Paginator->hasPrev('UserPlan')) echo $this->Paginator->prev('<i class="fa fa-arrow-left"></i>', array('class' => 'prev disabled', 'escape' => false)); ?> 
                <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'badge-gray', 'escape' => false)); ?> 
                <?php if ($this->Paginator->hasNext('UserPlan')) echo $this->Paginator->next('<i class="fa fa-arrow-right"></i>', array('class' => 'next', 'escape' => false,'rel'=>'nofollow')); ?> 
                <?php echo $this->Paginator->last('<i class="fa fa-arrow-circle-o-right"></i>', array('class' => 'last', 'escape' => false)); ?> 
            </div>	
        </div>
<?php }else { ?>
    <div class="no_found"><?php echo __d('user', 'No record found.', true);?></div>
<?php } ?>

    
<?php if($payments){
global $planType;    
global $planFeatuersMax;
global $planFeatuers;
foreach ($payments as $payment) { ?>
<div id="info<?php echo $payment['Payment']['id']; ?>" style="display: none;">
    <!-- Fieldset -->
    <div class="nzwh-wrapper fntpop">
        <fieldset class="nzwh">
            <legend class="nzwh">
                    <?php echo $payment['Payment']['transaction_id']; ?>
            </legend>
            <div class="drt">
                <span><?php echo __d('user', 'Plan Name', true);?>: </span> <?php echo $payment['Plan']['plan_name']; ?><br> 
                <span><?php echo __d('user', 'Amount', true);?>: </span> <?php echo CURR.' '.$payment['UserPlan']['amount']; ?><br> 
                <span><?php echo __d('user', 'Transaction Id', true);?>: </span> <?php echo $payment['Payment']['transaction_id']; ?><br> 
                <span><?php echo __d('user', 'Start Date', true);?> : </span> <?php echo date('M d, Y', strtotime($payment['UserPlan']['start_date'])); ?><br> 
                <span><?php echo __d('user', 'End Date', true);?> : </span> <?php echo date('M d, Y', strtotime($payment['UserPlan']['end_date'])); ?><br> 
                <span><strong><?php echo __d('user', 'Features', true);?></strong></span> 
                <ol>
                    <?php 
                    $fvalues = json_decode($payment['UserPlan']['fvalues'], true);
                    $fetures = explode(',',$payment['UserPlan']['features_ids']);
                    foreach($fetures as $fid){
                        $ddd = '<li>'.$planFeatuers[$fid];
                        if(array_key_exists($fid, $fvalues)){
                            if($fvalues[$fid] == $planFeatuersMax[$fid]){
                                $ddd .= ' - '.__d('user', 'Unlimited', true);
                            }else{
                                $ddd .= ' - '.$fvalues[$fid];
                            }
                        }
                        $ddd .= '</li>';
                        echo $ddd;
                    }
                    ?>
                <ol>
            </div>
        </fieldset>
    </div>
    
</div>
    <div id="invoiceinfo<?php echo $payment['Payment']['id']; ?>" style="display: none;">
    <!-- Fieldset -->
    <div class="nzwh-wrapper fntpop">
        <fieldset class="nzwh">
            <legend class="nzwh">
                    <?php echo $payment['Payment']['transaction_id']; ?>
            </legend>
            <div class="drt">
                 <span><?php echo strtoupper(__d('user', 'Invoice No', true));?>: </span> <?php echo str_pad($payment['UserPlan']['invoice_no'], 4, '0', STR_PAD_LEFT); ?><br> 
                 <span><?php echo __d('user', 'First Name', true);?>: </span> <?php echo $payment['User']['first_name']; ?><br> 
                <span><?php echo __d('user', 'Last Name', true);?>: </span> <?php echo $payment['User']['last_name']; ?><br> 
                <span><?php echo __d('user', 'Contact Number', true);?>: </span> <?php echo $this->Text->usformat($payment['User']['contact']); ?><br> 
                <span><?php echo __d('user', 'Email Address', true);?>: </span> <?php echo $payment['User']['email_address']; ?><br> 
                <span><?php echo __d('user', 'Company Name', true);?>: </span> <?php echo $payment['User']['company_name']; ?><br> 
                <span><?php echo __d('user', 'Address', true);?>: </span> <?php echo $payment['User']['address']; ?><br> 
               
                <span><?php echo __d('user', 'Plan Name', true);?>: </span> <?php echo $payment['Plan']['plan_name']; ?><br> 
                <span><?php echo __d('user', 'Amount', true);?>: </span> <?php echo CURR.' '.$payment['UserPlan']['amount']; ?><br> 
                <span><?php echo __d('user', 'Transaction Id', true);?>: </span> <?php echo $payment['Payment']['transaction_id']; ?><br> 
                <span><?php echo __d('user', 'Start Date', true);?> : </span> <?php echo date('d/m/Y', strtotime($payment['UserPlan']['start_date'])); ?><br> 
                <span><?php echo __d('user', 'End Date', true);?> : </span> <?php echo date('d/m/Y', strtotime($payment['UserPlan']['end_date'])); ?><br> 
                <span><strong><?php echo __d('user', 'Features', true);?></strong></span> 
                <ol>
                    <?php 
                    $fvalues = json_decode($payment['UserPlan']['fvalues'], true);
                    $fetures = explode(',',$payment['UserPlan']['features_ids']);
                    foreach($fetures as $fid){
                        $ddd = '<li>'.$planFeatuers[$fid];
                        if(array_key_exists($fid, $fvalues)){
                            if($fvalues[$fid] == $planFeatuersMax[$fid]){
                                $ddd .= ' - '.__d('user', 'Unlimited', true);
                            }else{
                                $ddd .= ' - '.$fvalues[$fid];
                            }
                        }
                        $ddd .= '</li>';
                        echo $ddd;
                    }
                    ?>
                <ol>
            </div>
        </fieldset>
    </div>
    
</div>
<?php } }
?>