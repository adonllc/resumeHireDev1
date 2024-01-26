<style>
    <!--
    .colr1{background-color: LavenderBlush !important;}
    -->
</style>
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
<?php if ($plans) { ?>
    <div class="col-lg-12">
        <section class="panel">
            <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
            <div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
            <?php 
            $urlArray = array_merge(array('controller' => 'plans', 'action' => 'index/', $separator));
            $this->Paginator->_ajaxHelperClass = "Ajax";
            $this->Paginator->Ajax = $this->Ajax;
            $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
            ?>
            <?php echo $this->Form->create("Strategy", array("url" => "index", "method" => "Post")); ?>
            <div class="columns mrgih_tp">
                <div id="pagingLinks" align="right">
                    <?php __("Showing Page"); ?>
                    <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                    &nbsp;
                    <span class="custom_link pagination"> 
                        <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                        <?php if ($this->Paginator->hasPrev('Strategy')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                        <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                        <?php if ($this->Paginator->hasNext('Strategy')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                        <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                    </span>
                </div>
                <div class="panel-body">
                    <section id="no-more-tables">
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th style="width:5%"><input name="chkRecordId" value="0" onClick="checkAll(this.form)" type='checkbox' class="checkall" /></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Plan.plan_name', 'Plan Name'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Plan.planuser', 'User Plan'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Plan.amount', 'Amount'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Plan.type', 'Plan Type'); ?></th>
                                    <th class="sorting_paging">Time Period</th>
                                   
                                    <th class="sorting_paging"><i class="fa fa-calendar"></i> <?php echo $this->Paginator->sort('Plan.created', 'Created'); ?></th>
                                    <th  style="width:12%" ><i class=" fa fa-gavel"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                global $planType;
                                foreach ($plans as $plan) { ?>
                                    <tr>
                                        <td data-title=""><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php echo $plan['Plan']['id']; ?>" /></td>
                                        <td data-title="Plan name"><?php echo $plan['Plan']['plan_name']; ?></td>
                                        <td data-title="User Plan"><?php echo ucfirst($plan['Plan']['planuser']); ?></td>
                                        <td data-title="Amount"><?php echo CURR.' '.$plan['Plan']['amount']; ?></td>
                                        <td data-title="Type"><?php echo $planType[$plan['Plan']['type']]; ?></td>
                                        <td data-title="Type"><?php echo $plan['Plan']['type_value'].' '.$plan['Plan']['type']; ?></td>
                                     
                                 
                                        <td data-title="Created"><?php echo date('F d,Y', strtotime($plan['Plan']['created'])); ?></td>
                                        <td data-title="Action">
                                            <div id="loaderIDAct<?php echo $plan['Plan']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                                            <span id="status<?php echo $plan['Plan']['id']; ?>">
                                                <?php
                                                if ($plan['Plan']['status'] == '1') {
                                                    echo $this->Ajax->link('<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>', array('controller' => 'plans', 'action' => 'deactivateplans', $plan['Plan']['slug']), array('update' => 'status' . $plan['Plan']['id'], 'indicator' => 'loaderIDAct' . $plan['Plan']['id'], 'confirm' => 'Are you sure you want to Deactivate ?', 'escape' => false, 'title' => 'Deactivate'));
                                                } else {
                                                    echo $this->Ajax->link('<button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button>', array('controller' => 'plans', 'action' => 'activateplans', $plan['Plan']['slug']), array('update' => 'status' . $plan['Plan']['id'], 'indicator' => 'loaderIDAct' . $plan['Plan']['id'], 'confirm' => 'Are you sure you want to Activate ?', 'escape' => false, 'title' => 'Activate'));
                                                }
                                                ?>
                                            </span>
                                            <?php echo $this->Html->link('<i class="fa fa-pencil"></i>', array("controller" => "plans", "action" => 'editPlan', $plan['Plan']['slug'], ), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Edit')); ?>
                                            <?php echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'plans', 'action' => 'deletePlan', $plan['Plan']['slug'], ), array('update' => 'deleted' . $plan['Plan']['id'], 'indicator' => 'loaderID', 'class' => 'btn btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false, 'title' => 'Delete')); ?>
                                            <a href="#info<?php echo $plan['Plan']['id']; ?>" rel="facebox" title="View" class="btn btn-info btn-xs"><i class="fa fa-eye "></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
            <div id="actdiv" class="outside">
                <div class="block-footer mogi">
                    <?php echo $this->Form->text('Plan.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
                    <?php echo $this->Form->text('Plan.action', array('type' => 'hidden', 'value' => 'activate', 'id' => 'action')); ?>
                    <?php echo $this->Ajax->submit("Activate", array('div' => false, 'url' => array('controller' => 'plans', 'action' => 'index','' ), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('activate');", 'confirm' => "Are you sure you want to Activate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('activated');", 'class' => 'btn btn-success btn-cons')); ?> 
                    <?php echo $this->Ajax->submit("Deactivate", array('div' => false, 'url' => array('controller' => 'plans', 'action' => 'index','' ), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('deactivate');", 'confirm' => "Are you sure you want to Deactivate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deactivated');", 'class' => 'btn btn-success btn-cons')); ?> 
                    <?php //echo $this->Ajax->submit("Delete", array('div' => false, 'url' => array('controller' => 'strategies', 'action' => 'index', ), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('delete');", 'confirm' => "Are you sure you want to Delete ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deleted');", 'class' => 'btn btn-success btn-cons')); ?> 
                </div>
            </div>
            <?php
           
            if (isset($searchKey) && $searchKey != '') {
                echo $this->Form->hidden('Strategy.strategyName', array('type' => 'hidden', 'value' => $searchKey));
            }
            if (isset($searchDateFrom) && $searchDateFrom != '') {
                echo $this->Form->hidden('Strategy.dateFrom', array('type' => 'hidden', 'value' => $searchDateFrom));
            }
            if (isset($searchDateTo) && $searchDateTo != '') {
                echo $this->Form->hidden('Strategy.dateTo', array('type' => 'hidden', 'value' => $searchDateTo));
            }
            ?>
            <?php echo $this->Form->end(); ?>
        </section>
    </div>
<?php 
global $planFeatuersMax;
global $planFeatuers;
foreach ($plans as $plan) { ?>

<div id="info<?php echo $plan['Plan']['id']; ?>"
     style="display: none;">
    <!-- Fieldset -->
    <div class="nzwh-wrapper">
        <fieldset class="nzwh">
            <legend class="nzwh">
                    <?php echo $plan['Plan']['plan_name']; ?>
            </legend>
            <div class="drt">
                <span>Amount (<?php echo CURR;  ?>): </span> <?php echo $plan['Plan']['amount']; ?><br> 
                <span>Plan Type: </span> <?php echo $planType[$plan['Plan']['type']]; ?><br> 
                <span>Time Period: </span> <?php echo $plan['Plan']['type_value'].' '.$plan['Plan']['type']; ?><br> 
                <span><strong>Features</strong></span> 
                <ol>
                    <?php 
                    $fvalues = json_decode($plan['Plan']['fvalues'], true);
                    $fetures = explode(',',$plan['Plan']['feature_ids']);
//                    print_r($planFeatuers);
                    foreach($fetures as $fid){
//                        echo $fid;
                        $ddd = '<li>'.$planFeatuers[$fid];
                        if(array_key_exists($fid, $fvalues)){
                            if($fvalues[$fid] == $planFeatuersMax[$fid]){
                                $ddd .= ' - Unlimited';
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
    <?php }
?>
        <?php } else { ?>
    <div class="columns mrgih_tp">
        <table class="table table-striped table-advance table-hover table-bordered">
            <tr>
                <td><div id="noRcrdExist" class="norecext">No record found.</div></td>
            </tr>
        </table>
    </div>
    <?php
    
}?>

