<style>
    <!--
    .colr1{background-color: LavenderBlush !important;}
    -->
</style>

<?php echo $this->Html->script('facebox.js'); ?>
<?php echo $this->Html->css('facebox.css'); ?>
<script type="text/javascript">
    $(document).ready(function ($) {
        $('.close_image').hide();
        $('a[rel*=facebox]').facebox({
            loadingImage: '<?php echo HTTP_IMAGE ?>/loading.gif',
            closeImage: '<?php echo HTTP_IMAGE ?>/close.png'
        })

        $('a[rel*=facebox1]').facebox({
            loadingImage: '<?php echo HTTP_IMAGE ?>/loading.gif',
            closeImage: '<?php echo HTTP_IMAGE ?>/close.png'
        })
    })


    function changeStatus(jobNumber, status) {

        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/changeStatus/" + jobNumber + '/' + status,
            cache: false,
            beforeSend: function () {
                $("#loaderIDActCC" + jobNumber).show();
            },
            complete: function () {
                $("#loaderIDActCC" + jobNumber).hide();
            },
            success: function (result) {

            }
        });


    }
</script>

<?php
if ($jobs) {

    //pr($jobs); 
    ?>
    <div class="col-lg-12">
        <section class="panel">
            <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
            <div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
            <?php
            $urlArray = array_merge(array('controller' => 'jobs', 'action' => 'importlist', $separator));
            $this->Paginator->_ajaxHelperClass = "Ajax";
            $this->Paginator->Ajax = $this->Ajax;
            $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
            ?>
            <?php echo $this->Form->create("Job", array("url" => "importlist", "method" => "Post")); ?>
            <div class="columns mrgih_tp">
                <div id="pagingLinks" align="right">
                    <?php __("Showing Page"); ?>
                    <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                    &nbsp;
                    <span class="custom_link pagination"> 
                        <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                        <?php if ($this->Paginator->hasPrev('Feed')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                        <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                        <?php if ($this->Paginator->hasNext('Feed')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                        <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                    </span>
                </div>
                <div class="panel-body">
                    <section id="no-more-tables" style="overflow:auto;">
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th style="width:5%"><input name="chkRecordId" value="0" onClick="checkAll(this.form)" type='checkbox' class="checkall" /></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Feed.name', 'Name'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Feed.feed_url', 'URL'); ?></th>
                                    <th class="sorting_paging"><i class="fa fa-calendar"></i> <?php echo $this->Paginator->sort('Feed.created', 'Date'); ?></th>
    <!--                                    <th><i class="orting_paging"></i> Payment State</th>-->
                                    <th style="width:15%"><i class=" fa fa-gavel"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                $adminLId = $this->Session->read('adminid');
                                $checkSubRols = ClassRegistry::init('Admin')->getAdminRolesSub($this->Session->read('adminid'));
                                
                                global $designation;
                                $payStatus = array('0' => 'Pending', '1' => 'Inprogress', '2' => 'Completed');

                                foreach ($jobs as $job) {
                                    ?>
                                    <tr>
                                        <td data-title=""><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php echo $job['Feed']['id']; ?>" /></td>

                                        <td data-title="Name"><?php echo $job['Feed']['name']; ?></td>
                                        
                                        <td data-title="URL">
                                            <?php echo $job['Feed']['feed_url']; ?>
                                        </td>
                                        <td data-title="Created"><?php echo date('F d,Y', strtotime($job['Feed']['created'])); ?></td>

                                        <td data-title="Action">
                                            <?php if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 3, 2)){ ?>
                                            <div id="loaderIDAct<?php echo $job['Feed']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                                            <span id="status<?php echo $job['Feed']['id']; ?>">
                                                <?php
                                                if ($job['Feed']['status'] == '1') {
                                                    echo $this->Ajax->link('<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>', array('controller' => 'jobs', 'action' => 'deactivatefeed', $job['Feed']['slug']), array('update' => 'status' . $job['Feed']['id'], 'indicator' => 'loaderIDAct' . $job['Feed']['id'], 'confirm' => 'Are you sure you want to Deactivate ?', 'escape' => false, 'title' => 'Deactivate'));
                                                } else {
                                                    echo $this->Ajax->link('<button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button>', array('controller' => 'jobs', 'action' => 'activatefeed', $job['Feed']['slug']), array('update' => 'status' . $job['Feed']['id'], 'indicator' => 'loaderIDAct' . $job['Feed']['id'], 'confirm' => 'Are you sure you want to Activate ?', 'escape' => false, 'title' => 'Activate'));
                                                }
                                                ?>
                                            </span>
                                            <?php
                                            }
                                            if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 3, 3)){
                                                echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'jobs', 'action' => 'deletefeed', $job['Feed']['slug']), array('update' => 'deleted' . $job['Feed']['id'], 'indicator' => 'loaderID', 'class' => 'btn btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false, 'title' => 'Delete'));
                                            } ?>
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
                    <?php echo $this->Form->text('Feed.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
                    <?php echo $this->Form->text('Feed.action', array('type' => 'hidden', 'value' => 'activate', 'id' => 'action')); ?>
                    <?php 
                    if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 3, 2)){
                        echo $this->Ajax->submit("Activate", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'importlist', $slug), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('activate');", 'confirm' => "Are you sure you want to Activate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('activated');", 'class' => 'btn btn-success btn-cons')); 
                        echo $this->Ajax->submit("Deactivate", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'importlist', $slug), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('deactivate');", 'confirm' => "Are you sure you want to Deactivate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deactivated');", 'class' => 'btn btn-success btn-cons'));
                    }
                    if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 3, 3)){
                        echo $this->Ajax->submit("Delete", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'importlist', $slug), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('delete');", 'confirm' => "Are you sure you want to Delete ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deleted');", 'class' => 'btn btn-success btn-cons')); 
                    }?> 

                </div>
            </div>
            <?php
            if (isset($searchKey) && $searchKey != '') {
                echo $this->Form->hidden('Feed.userName', array('type' => 'hidden', 'value' => $searchKey));
            }
            if (isset($searchDateFrom) && $searchDateFrom != '') {
                echo $this->Form->hidden('Feed.dateFrom', array('type' => 'hidden', 'value' => $searchDateFrom));
            }
            if (isset($searchDateTo) && $searchDateTo != '') {
                echo $this->Form->hidden('Feed.dateTo', array('type' => 'hidden', 'value' => $searchDateTo));
            }
            ?>
            <?php echo $this->Form->end(); ?>
            <div id="pagingLinks" align="right">
                <?php __("Showing Page"); ?>
                <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                &nbsp;
                <span class="custom_link pagination"> 
                    <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                    <?php if ($this->Paginator->hasPrev('Feed')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                    <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                    <?php if ($this->Paginator->hasNext('Feed')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                    <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                </span>
            </div>
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

