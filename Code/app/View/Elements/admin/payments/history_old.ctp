
<?php if ($payments) { ?>
    <div class="col-lg-12">
        <section class="panel">
            <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
            <div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
            <?php
            $urlArray = array_merge(array('controller' => 'payments', 'action' => 'history', $separator));
            $this->Paginator->_ajaxHelperClass = "Ajax";
            $this->Paginator->Ajax = $this->Ajax;
            $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
            ?>
            <?php echo $this->Form->create("Payment", array("url" => "history", "method" => "Post")); ?>
            <div class="columns mrgih_tp">
                <div id="pagingLinks" align="right">
                    <?php __("Showing Page"); ?>
                    <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                    &nbsp;
                    <span class="custom_link pagination"> 
                        <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                        <?php if ($this->Paginator->hasPrev('Payment')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                        <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                        <?php if ($this->Paginator->hasNext('Payment')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                        <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                    </span>
                </div>
                <div class="panel-body">
                    <section id="no-more-tables">
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('User.company_name', 'Company Name'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('User.first_name', 'Full Name'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Job.title', 'Job Title'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Payment.transaction_id', 'Transaction#'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Payment.price', 'Amoount'); ?></th>
                                    <th class="sorting_paging"><i class="fa fa-calendar"></i> <?php echo $this->Paginator->sort('Payment.created', 'Date'); ?></th>
                                    <?php /*<th><i class=" fa fa-gavel"></i> Action</th>*/?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                global  $designation;
                                foreach ($payments as $payment) { ?>
                                    <tr>
                                        <td data-title="Company name"><?php echo $payment['User']['company_name']; ?></td>
                                        <td data-title="Full Name"><?php echo $payment['User']['first_name'] . ' ' . $payment['User']['last_name']; ?></td>
                                        <td data-title="Company name"><?php echo $payment['Job']['title']; ?></td>
                                        <td data-title="Designation"><?php echo $payment['Payment']['transaction_id']?$payment['Payment']['transaction_id']:'N/A'; ?></td>
                                        <td data-title="Email"><?php echo CURRENCY.$payment['Payment']['price']; ?></td>
                                        <td data-title="Created"><?php echo date('F d,Y', strtotime($payment['Payment']['created'])); ?></td>
                                        <?php /*<td data-title="Action">
                                            <?php echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'payments', 'action' => 'deletepayments', $payment['Payment']['slug']), array('update' => 'deleted' . $payment['Payment']['id'], 'indicator' => 'loaderID', 'class' => 'btn btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false, 'title' => 'Delete')); ?>
                                            <a href="#info<?php echo $payment['Payment']['id']; ?>" rel="facebox" title="View" class="btn btn-info btn-xs"><i class="fa fa-eye "></i></a>
                                        </td>*/?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
            <div id="actdiv" class="outside">
                <div class="block-footer mogi">
                    <?php echo $this->Form->text('Payment.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
                    <?php echo $this->Form->text('Payment.action', array('type' => 'hidden', 'value' => 'activate', 'id' => 'action')); ?>
                    <?php //echo $this->Ajax->submit("Activate", array('div' => false, 'url' => array('controller' => 'payments', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('activate');", 'confirm' => "Are you sure you want to Activate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('activated');", 'class' => 'btn btn-success btn-cons')); ?> 
                    <?php //echo $this->Ajax->submit("Deactivate", array('div' => false, 'url' => array('controller' => 'payments', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('deactivate');", 'confirm' => "Are you sure you want to Deactivate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deactivated');", 'class' => 'btn btn-success btn-cons')); ?> 
                    <?php //echo $this->Ajax->submit("Delete", array('div' => false, 'url' => array('controller' => 'payments', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('delete');", 'confirm' => "Are you sure you want to Delete ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deleted');", 'class' => 'btn btn-success btn-cons')); ?> 
                     
                </div>
            </div>
            <?php
            if (isset($searchKey) && $searchKey != '') {
                echo $this->Form->hidden('Payment.paymentName', array('type' => 'hidden', 'value' => $searchKey));
            }
            if (isset($searchDateFrom) && $searchDateFrom != '') {
                echo $this->Form->hidden('Payment.dateFrom', array('type' => 'hidden', 'value' => $searchDateFrom));
            }
            if (isset($searchDateTo) && $searchDateTo != '') {
                echo $this->Form->hidden('Payment.dateTo', array('type' => 'hidden', 'value' => $searchDateTo));
            }
            ?>
            <?php echo $this->Form->end(); ?>
        </section>
    </div>
<?php } else { ?>
    <div class="columns mrgih_tp">
        <table class="table table-striped table-advance table-hover table-bordered">
            <tr>
                <td><div id="noRcrdExist" class="norecext">No Record Found.</div></td>
            </tr>
        </table>
    </div>
    <?php }
?>

