

<?php if ($users) { ?>
    <div class="col-lg-12">
        <section class="panel">
            <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
            <div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
            <?php
            $urlArray = array_merge(array('controller' => 'newsletters', 'action' => 'unsubscriberlist', $separator));
            $this->Paginator->_ajaxHelperClass = "Ajax";
            $this->Paginator->Ajax = $this->Ajax;
            $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
            ?>
            <?php echo $this->Form->create("Newsletter", array("url" => "unsubscriberlist", "method" => "Post")); ?>
            <div class="columns mrgih_tp">
                <div id="pagingLinks" align="right">
                    <?php __("Showing Page"); ?>
                    <div class="countrdm"><?php echo $this->Paginator->counter('No. of Records <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                    &nbsp;
                    <span class="custom_link pagination"> 
                        <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                        <?php if ($this->Paginator->hasPrev('User')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                        <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                        <?php if ($this->Paginator->hasNext('User')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                        <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                    </span>
                </div>
                <div class="panel-body">
                    <section id="no-more-tables">
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th style="width:5%"><input name="chkRecordId" value="0" onClick="checkAll(this.form)" type='checkbox' class="checkall" /></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('User.first_name', 'User Name'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('User.email_address', 'Email Address'); ?></th>
                                    <th class="sorting_paging">User Type</th>
    <!--                                    <th class="sorting_paging"><?php //echo $this->Paginator->sort('Sendmail.subject', 'Subject');   ?></th>
                                    <th class="sorting_paging"><i class="fa fa-calendar"></i> <?php //echo $this->Paginator->sort('Sendmail.is_mail_sent', 'Mail Status');   ?></th>
                                    <th class="sorting_paging"><i class="fa fa-calendar"></i> <?php //echo $this->Paginator->sort('Sendmail.sent_on', 'Mail Sent Time');   ?></th>-->
<!--                                    <th><i class=" fa fa-gavel"></i> Action</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user) { ?>
                                    <tr>
                                        <td data-title=""><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php echo $user['User']['id']; ?>" /></td>
                                        <td data-title="User Name"><?php echo $user['User']['first_name'] . ' ' . $user['User']['last_name']; ?></td>
                                        <td data-title="Email Address"><?php echo $user['User']['email_address']; ?></td>
                                        <td data-title="Email Address">
                                            <?php
                                            $userType = classregistry::init('User')->field('user_type', array('User.email_address' => $user['User']['email_address']));
                                            if ($userType == 'candidate') {
                                                echo 'Jobseeker';
                                            } else {
                                                echo 'Employer';
                                            }
                                            ?>
                                        </td>
        <!--                                        <td data-title="Subject"><?php //echo $newsletter['Sendmail']['subject'];   ?></td>
                                        <td data-title="Mail Status"><?php
//                                            if ($newsletter['Sendmail']['is_mail_sent'] == 1) {
//                                                echo "Sent";
//                                            } else {
//                                                echo "Not Sent";
//                                            }
                                        ?></td>
                                        <td data-title="Mail Sent Time"><?php
//                                            if ($newsletter['Sendmail']['sent_on'] != '' && $newsletter['Sendmail']['sent_on'] != '0' && $newsletter['Sendmail']['sent_on'] != NULL) {
//                                                echo date('F d,Y', $newsletter['Sendmail']['sent_on']);
//                                            } else {
//                                                echo 'N/A';
//                                            }
                                        ?></td>-->
<!--                                        <td data-title="Action">
                                            <div id="loaderIDAct<?php //echo $user['User']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                                            <span id="status<?php //echo $user['User']['id']; ?>">
                                                <?php
                                                /* if ($newsletter['Sendmail']['status'] == '1') {
                                                  echo $this->Ajax->link('<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>', array('controller' => 'newsletters', 'action' => 'deactivateSendmail', $newsletter['Sendmail']['slug']), array('update' => 'status' . $newsletter['Sendmail']['id'], 'indicator' => 'loaderIDAct' . $newsletter['Sendmail']['id'], 'confirm' => 'Are you sure you want to Deactivate ?', 'escape' => false, 'title' => 'Deactivate'));
                                                  } else {
                                                  echo $this->Ajax->link('<button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button>', array('controller' => 'newsletters', 'action' => 'activateSendmail', $newsletter['Sendmail']['slug']), array('update' => 'status' . $newsletter['Sendmail']['id'], 'indicator' => 'loaderIDAct' . $newsletter['Sendmail']['id'], 'confirm' => 'Are you sure you want to Activate ?', 'escape' => false, 'title' => 'Activate'));
                                                  } */
                                                ?>

                                            </span>
                                            <?php //echo $this->Html->link('<i class="fa fa-pencil"></i>', array("controller" => "newsletters", "action" => 'editSendmail',$newsletter['Sendmail']['slug']), array('escape' => false, 'class' => "btn btn-info btn-xs", 'title' => 'Edit'));   ?>
                                            <?php //echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'newsletters', 'action' => 'deleteSendmail', $newsletter['Sendmail']['id']), array('class' => 'btn btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false, 'title' => 'Delete')); ?>

                                        </td>-->
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
            <div id="actdiv" class="outside">
                <div class="block-footer mogi">
                    <?php echo $this->Form->text('User.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
                    <?php echo $this->Form->text('User.action', array('type' => 'hidden', 'value' => 'activate', 'id' => 'action')); ?>
                    <?php //echo $this->Ajax->submit("Activate", array('div' => false, 'url' => array('controller' => 'newsletters', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('activate');", 'confirm' => "Are you sure you want to Activate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('activated');", 'class' => 'btn btn-success btn-cons'));   ?> 
                    <?php //echo $this->Ajax->submit("Deactivate", array('div' => false, 'url' => array('controller' => 'newsletters', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('deactivate');", 'confirm' => "Are you sure you want to Deactivate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deactivated');", 'class' => 'btn btn-success btn-cons'));   ?> 
                    <?php //echo $this->Ajax->submit("Delete", array('div' => false, 'url' => array('controller' => 'newsletters', 'action' => 'sentMail'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('delete');", 'confirm' => "Are you sure you want to Delete ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deleted');", 'class' => 'btn btn-success btn-cons')); ?> 
                </div>
            </div>
            <div id="pagingLinks" align="right">
                <?php __("Showing Page"); ?>
                <div class="countrdm"><?php echo $this->Paginator->counter('No. of Records <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                &nbsp;
                <span class="custom_link pagination"> 
                    <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                    <?php if ($this->Paginator->hasPrev('User')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                    <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                    <?php if ($this->Paginator->hasNext('User')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                    <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                </span>
            </div>
            <?php
            if (isset($searchKey) && $searchKey != '') {
                echo $this->Form->hidden('User.email', array('type' => 'hidden', 'value' => $searchKey));
            }
            ?>
            <?php echo $this->Form->end(); ?>
        </section>
    </div>
<?php } else { ?>
    <div class="columns mrgih_tp">
        <table class="table table-striped table-advance table-hover table-bordered">
            <tr>
                <td><div id="noRcrdExist" class="norecext">There are no record found.</div></td>
            </tr>
        </table>
    </div>
<?php }
?>
