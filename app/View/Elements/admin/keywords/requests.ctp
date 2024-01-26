<script type="text/javascript">
    $(document).ready(function ($) {
        $(".approve_status").change(function () {
            if (!confirm("Are you sure you want to Approve, After approval this record will be moved?")) {
                return false;
            }
            var value = $(this).val();
            var slug = $(this).attr('data-slug');
           
           if(value =='Approved'){               
                $.ajax({
                url: '<?php echo HTTP_PATH . '/admin/keywords/approveStatus/'; ?>'+slug,
                type: 'POST',
                data: { value: value},
                cache: false,
                beforeSend: function () {
                    $(".locater_img").show();
                },
                success: function (data, textStatus, XMLHttpRequest)
                {
                    $(".locater_img").hide();
                    window.location.reload();
                }
            });
           }
           
        })

    })
</script>
<?php if ($keywords) { ?>
    <div class="col-lg-12">
        <section class="panel">
            <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
            <div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
            <?php
            $urlArray = array_merge(array('controller' => 'keywords', 'action' => 'requests', $separator));
            $this->Paginator->_ajaxHelperClass = "Ajax";
            $this->Paginator->Ajax = $this->Ajax;
            $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
            ?>
            <?php echo $this->Form->create("Keyword", array("url" => "requests", "method" => "Post")); ?>
            <div class="columns mrgih_tp">
                <div id="pagingLinks" align="right">
                    <?php __("Showing Page"); ?>
                    <div class="countrdm"><?php echo $this->Paginator->counter('No. of Records <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                    &nbsp;
                    <span class="custom_link pagination"> 
                        <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                        <?php if ($this->Paginator->hasPrev('Keyword')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                        <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                        <?php if ($this->Paginator->hasNext('Keyword')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                        <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                    </span>
                </div>
                <div class="panel-body">
                    <section id="no-more-tables">
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th style="width:5%"><input name="chkRecordId" value="0" onClick="checkAll(this.form)" type='checkbox' class="checkall" /></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Keyword.name', 'Keyword Name'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Keyword.type', 'Keyword Type'); ?></th>
                                    <th class="action_dvv"> Approval Status</th>

                                    <th><i class=" fa fa-gavel"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($keywords as $announcement) { ?>
                                    <tr>
                                        <td data-title=""><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php echo $announcement['Keyword']['id']; ?>" /></td>
                                        <td data-title="Keyword Name"><?php echo $announcement['Keyword']['name']; ?></td>
                                        <td data-title="Keyword Type"><?php echo $announcement['Keyword']['type']; ?></td>
                                        <td data-title="Approval Status">
                                            <?php
                                            $statusLists = array(
//                                        'Pending' => 'Pending',
                                                'Approved' => 'Approved',
                                            );

                                            $slug = $announcement['Keyword']['slug'];
                                            echo $this->Form->select('Keyword.approval_status', $statusLists, array('empty' => 'Pending', 'label' => '', 'div' => false, 'class' => "form-control approve_status", 'data-slug' => $announcement['Keyword']['slug']));
                                            ?>
                                        </td>
                                        <td data-title="Action">
                                            <div id="loaderIDAct<?php echo $announcement['Keyword']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>

                                            <?php
//                                                    echo $this->Html->link('<button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button>', array('controller' => 'keywords', 'action' => 'activaterequests', $announcement['Keyword']['slug']), array('confirm' => 'Are you sure you want to Approve, After approval this record will be moved?', 'escape' => false, 'title' => 'Approve'));
                                            ?>

                                            <?php // echo $this->Html->link('<i class="fa fa-pencil"></i>', array("controller" => "keywords", "action" => 'edit',$announcement['Keyword']['slug']), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Edit'));  ?>
                                            <?php echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'keywords', 'action' => 'deleterequests', $announcement['Keyword']['slug']), array('update' => 'deleted' . $announcement['Keyword']['id'], 'indicator' => 'loaderID', 'class' => 'btn btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false, 'title' => 'Delete')); ?>

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
                    <?php echo $this->Form->text('Keyword.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
                    <?php echo $this->Form->text('Keyword.action', array('type' => 'hidden', 'value' => 'activate', 'id' => 'action')); ?>

                    <?php // echo $this->Ajax->submit("Activate", array('div' => false, 'url' => array('controller' => 'keywords', 'action' => 'requests'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('activate');", 'confirm' => "Are you sure you want to Activate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('activated');", 'class' => 'btn btn-success btn-cons')); 
//                        echo $this->Ajax->submit("Deactivate", array('div' => false, 'url' => array('controller' => 'keywords', 'action' => 'requests'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('deactivate');", 'confirm' => "Are you sure you want to Deactivate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deactivated');", 'class' => 'btn btn-success btn-cons')); 
                     echo $this->Ajax->submit("Delete", array('div' => false, 'url' => array('controller' => 'keywords', 'action' => 'requests'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('delete');", 'confirm' => "Are you sure you want to Delete ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deleted');", 'class' => 'btn btn-success btn-cons')); ?> 
                </div>
            </div>
            <div id="pagingLinks" align="right">
                <?php __("Showing Page"); ?>
                <div class="countrdm"><?php echo $this->Paginator->counter('No. of Records <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                &nbsp;
                <span class="custom_link pagination"> 
                    <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                    <?php if ($this->Paginator->hasPrev('Keyword')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                    <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                    <?php if ($this->Paginator->hasNext('Keyword')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                    <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                </span>
            </div>
            <?php
            if (isset($searchKey) && $searchKey != '') {
                echo $this->Form->hidden('Keyword.c_word', array('type' => 'hidden', 'value' => $searchKey));
            }
            ?>
            <?php echo $this->Form->end(); ?>
        </section>
    </div>
<?php } else { ?>
    <div class="columns mrgih_tp">
        <table class="table table-striped table-advance table-hover table-bordered">
            <tr>
                <td><div id="noRcrdExist" class="norecext">There are Keyword to show.</div></td>
            </tr>
        </table>
    </div>
<?php }
?>
