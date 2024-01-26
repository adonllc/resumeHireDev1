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
            loadingImage: '<?php echo HTTP_IMAGE ?>/loading.gif',
            closeImage: '<?php echo HTTP_IMAGE ?>/close.png'
        })
    })
</script>
<style>
    /* NZ Web Hosting - www.nzwhost.com 
     * Fieldset Alternative Demo
    */
    .fieldset {
        border: solid 2px #ff0000;
        background: #3ca4ee;
        margin-top: 20px;
        position: relative;
    }

    .legend {
        border: solid 2px #ff0000;
        left: 0.5em;
        top: -0.6em;
        position: absolute;
        background: #A7BB5C;
        font-weight: bold;
        padding: 0 0.25em 0 0.25em;
    }

    .nzwh-wrapper .content {
        margin: 1em 0.5em 0.5em 0.5em;
    }

    legend.nzwh {
        background: none repeat scroll 0 0 #fff !important;
        border: 1px solid #a7a7a7 !important;
        border-radius: 5px !important;
        color: #a0a0a0;
        font-weight: normal;
        left: 0.5em;
        padding: 5px;
        position: absolute;
        top: -0.99em;
        width: auto !important;
    }

    fieldset.nzwh {
        background: none repeat scroll 0 0 #eee;
        border: 1px solid #a7a7a7;
        margin-top: 10px;
        padding: 0 10px;
        position: relative;
    }
</style>



<?php if ($admins) { ?>
    <div class="col-lg-12">
        <section class="panel">
            <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
            <div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
            <?php
            $urlArray = array_merge(array('controller' => 'admins', 'action' => 'manage', $separator));
            $this->Paginator->_ajaxHelperClass = "Ajax";
            $this->Paginator->Ajax = $this->Ajax;
            $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
            ?>
            <?php echo $this->Form->create("Admin", array("url" => "manage", "method" => "Post")); ?>
            <div class="columns mrgih_tp">
                <div id="pagingLinks" align="right">
                    <?php __("Showing Page"); ?>
                    <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                    &nbsp;
                    <span class="custom_link pagination"> 
                        <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                        <?php if ($this->Paginator->hasPrev('Admin')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                        <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                        <?php if ($this->Paginator->hasNext('Admin')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                        <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                    </span>
                </div>
                <div class="panel-body">
                    <section id="no-more-tables">
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th style="width:5%"><input name="chkRecordId" value="0" onClick="checkAll(this.form)" type='checkbox' class="checkall" /></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Admin.first_name', 'First Name'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Admin.last_name', 'Last Name'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Admin.username', 'Username'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Admin.email', 'Email'); ?></th>
                                    <th class="sorting_paging"><i class="fa fa-calendar"></i> <?php echo $this->Paginator->sort('Admin.created', 'Created'); ?></th>
                                    <th style="width:20%"><i class=" fa fa-gavel"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                global  $designation;
                                foreach ($admins as $admin) { ?>
                                    <tr>
                                       
                                        <td data-title="">
                                            <?php if($admin['Admin']['id'] > 1){ ?>
                                                <input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php echo $admin['Admin']['id']; ?>" />
                                            <?php } ?>    
                                        </td>
                                        <td data-title="First Name"><?php echo $admin['Admin']['first_name']; ?></td>
                                        <td data-title="last name"><?php echo $admin['Admin']['last_name']; ?></td>
                                        <td data-title="Email"><?php echo $admin['Admin']['username']; ?></td>
                                        <td data-title="Email"><?php echo $admin['Admin']['email']; ?></td>
                                        <td data-title="Created"><?php echo date('F d,Y', strtotime($admin['Admin']['created'])); ?></td>
                                        <td data-title="Action">
                                             <?php if($admin['Admin']['id'] > 1){ ?>
                                                <div id="loaderIDAct<?php echo $admin['Admin']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                                                <span id="status<?php echo $admin['Admin']['id']; ?>">
                                                    <?php
                                                    if ($admin['Admin']['status'] == '1') {
                                                        echo $this->Ajax->link('<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>', array('controller' => 'admins', 'action' => 'deactivateuser', $admin['Admin']['slug']), array('update' => 'status' . $admin['Admin']['id'], 'indicator' => 'loaderIDAct' . $admin['Admin']['id'], 'confirm' => 'Are you sure you want to Deactivate ?', 'escape' => false, 'title' => 'Deactivate'));
                                                    } else {
                                                        echo $this->Ajax->link('<button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button>', array('controller' => 'admins', 'action' => 'activateuser', $admin['Admin']['slug']), array('update' => 'status' . $admin['Admin']['id'], 'indicator' => 'loaderIDAct' . $admin['Admin']['id'], 'confirm' => 'Are you sure you want to Activate ?', 'escape' => false, 'title' => 'Activate'));
                                                    }
                                                    ?>
                                                </span>

                                                <?php echo $this->Html->link('<i class="fa fa-pencil"></i>', array("controller" => "admins", "action" => 'editadmins', $admin['Admin']['slug']), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Edit')); ?>
                                                <?php echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'admins', 'action' => 'deleteadmins', $admin['Admin']['slug']), array('class' => 'btn btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false, 'title' => 'Delete')); ?>
                                                <?php echo $this->Html->link('<i class="fa fa-plus"></i>', array("controller" => "admins", "action" => 'managerole', $admin['Admin']['slug']), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Manage Roles')); ?>
                                             <?php } ?>
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
                    <?php echo $this->Form->text('Admin.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
                    <?php echo $this->Form->text('Admin.action', array('type' => 'hidden', 'value' => 'activate', 'id' => 'action')); ?>
                    <?php echo $this->Ajax->submit("Activate", array('div' => false, 'url' => array('controller' => 'admins', 'action' => 'manage'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('activate');", 'confirm' => "Are you sure you want to Activate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('activated');", 'class' => 'btn btn-success btn-cons')); ?> 
                    <?php echo $this->Ajax->submit("Deactivate", array('div' => false, 'url' => array('controller' => 'admins', 'action' => 'manage'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('deactivate');", 'confirm' => "Are you sure you want to Deactivate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deactivated');", 'class' => 'btn btn-success btn-cons')); ?> 
                    <?php echo $this->Ajax->submit("Delete", array('div' => false, 'url' => array('controller' => 'admins', 'action' => 'manage'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('delete');", 'confirm' => "Are you sure you want to Delete ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deleted');", 'class' => 'btn btn-success btn-cons')); ?> 
                     
                </div>
            </div>
            <?php
            if (isset($searchKey) && $searchKey != '') {
                echo $this->Form->hidden('Admin.name', array('type' => 'hidden', 'value' => $searchKey));
            }
            if (isset($searchDateFrom) && $searchDateFrom != '') {
                echo $this->Form->hidden('Admin.dateFrom', array('type' => 'hidden', 'value' => $searchDateFrom));
            }
            if (isset($searchDateTo) && $searchDateTo != '') {
                echo $this->Form->hidden('Admin.dateTo', array('type' => 'hidden', 'value' => $searchDateTo));
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