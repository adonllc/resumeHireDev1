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
    })
</script>
<?php if ($users) { ?>
    <div class="col-lg-12">
        <section class="panel">
            <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
            <div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px">
                <?php echo $this->Html->image("loader_large_blue.gif"); ?>
            </div>
            <?php
            $urlArray = array_merge(array('controller' => 'users', 'action' => 'selectforslider', $separator));
            $this->Paginator->_ajaxHelperClass = "Ajax";
            $this->Paginator->Ajax = $this->Ajax;
            $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
            ?>
            <?php echo $this->Form->create("User", array("url" => "selectforslider", "method" => "Post")); ?>
            <div class="columns mrgih_tp">
                <div id="pagingLinks" align="right">
                    <?php __("Showing Page"); ?>
                    <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
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
    <!--                                    <th style="width:5%"><input name="chkRecordId" value="0" onClick="checkAll(this.form)" type='checkbox' class="checkall" /></th>-->
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('User.company_name', 'Company Name'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('User.first_name', 'Full Name'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('User.email_address', 'Email'); ?></th>
                                    <th class="sorting_paging"><?php echo 'Logo'; ?></th>
                                    <th class="sorting_paging"><i class="fa fa-calendar"></i> <?php echo $this->Paginator->sort('User.created', 'Created'); ?></th>
                                    <th><i class=" fa fa-gavel"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                global $designation;
                                foreach ($users as $user) {
                                    ?>
                                    <tr>
        <!--                                        <td data-title=""><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php //echo $user['User']['id'];   ?>" /></td>-->
                                        <td data-title="Employer name">
                                            <?php
                                            //echo $this->Html->link($user['User']['company_name'], array('controller'=>'jobs','action'=>'index',$user['User']['slug']), array('escape' => false, 'class' => '')); 
                                            ?>
                                            <?php echo $user['User']['company_name']; ?>
                                        </td>
                                        <td data-title="Full Name">
                                            <?php
                                            $full_name = $user['User']['first_name'] . ' ' . $user['User']['last_name'];
                                            echo trim($full_name) ? $full_name : 'N/A';
                                            ?>
                                        </td>
                                        <td data-title="Email"><?php echo $user['User']['email_address']; ?></td>
                                        <td data-title="Designation"><?php
                                            if ($user['User']['profile_image']) {
                                                if (file_exists(UPLOAD_FULL_PROFILE_IMAGE_PATH . $user['User']['profile_image'])) {
                                                    echo $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $user['User']['profile_image'], array('style' => 'width:100px; height:100px;'));
                                                    echo '<br>';
                                                } else {
                                                    echo $this->Html->image('front/no_image_user.png', array('class' => 'image_css'));
                                                }
                                            } else {
                                                echo $this->Html->image('front/no_image_user.png', array('class' => 'image_css'));
                                            }
                                            ?>
                                        </td>
                                        <td data-title="Created"><?php echo date('F d,Y', strtotime($user['User']['created'])); ?></td>
                                        <td data-title="Action">
                                            <div id="loaderIDAct<?php echo $user['User']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                                            <span id="status<?php echo $user['User']['id']; ?>">
                                                <?php
                                                if ($user['User']['home_slider'] == '1') {
                                                    echo $this->Ajax->link('<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>', array('controller' => 'users', 'action' => 'deactivateslider', $user['User']['slug']), array('update' => 'status' . $user['User']['id'], 'indicator' => 'loaderIDAct' . $user['User']['id'], 'confirm' => 'Are you sure you want to Deactivate ?', 'escape' => false, 'title' => 'Deactivate'));
                                                } else {
                                                    echo $this->Ajax->link('<button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button>', array('controller' => 'users', 'action' => 'activateslider', $user['User']['slug']), array('update' => 'status' . $user['User']['id'], 'indicator' => 'loaderIDAct' . $user['User']['id'], 'confirm' => 'Are you sure you want to Activate ?', 'escape' => false, 'title' => 'Activate'));
                                                }
                                                ?>
                                            </span>
                                            <?php //echo $this->Html->link('<i class="fa fa-pencil"></i>', array("controller" => "users", "action" => 'editusers', $user['User']['slug']), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Edit')); ?>

                                            <?php //echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'users', 'action' => 'deleteusers', $user['User']['slug']), array('update' => 'deleted' . $user['User']['id'], 'indicator' => 'loaderID', 'class' => 'btn btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false, 'title' => 'Delete')); ?>
                                            <a href="#info<?php echo $user['User']['id']; ?>" rel="facebox" title="View" class="btn btn-info btn-xs"><i class="fa fa-eye "></i></a>
                                            <div id="loaderIDEmail<?php echo $user['User']['id']; ?>" style="display:none;position:absolute;margin:-30px -1px 0px 124px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
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
                    <?php echo $this->Form->text('User.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
                    <?php echo $this->Form->text('User.action', array('type' => 'hidden', 'value' => 'activate', 'id' => 'action')); ?>
                    <?php //echo $this->Ajax->submit("Activate", array('div' => false, 'url' => array('controller' => 'users', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('activate');", 'confirm' => "Are you sure you want to Activate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('activated');", 'class' => 'btn btn-success btn-cons')); ?> 
                    <?php //echo $this->Ajax->submit("Deactivate", array('div' => false, 'url' => array('controller' => 'users', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('deactivate');", 'confirm' => "Are you sure you want to Deactivate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deactivated');", 'class' => 'btn btn-success btn-cons')); ?> 
                    <?php //echo $this->Ajax->submit("Delete", array('div' => false, 'url' => array('controller' => 'users', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('delete');", 'confirm' => "Are you sure you want to Delete ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deleted');", 'class' => 'btn btn-success btn-cons')); ?> 

                </div>
            </div>
            <div id="pagingLinks" align="right">
                <?php __("Showing Page"); ?>
                <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
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
                echo $this->Form->hidden('User.userName', array('type' => 'hidden', 'value' => $searchKey));
            }
//            if (isset($searchDateFrom) && $searchDateFrom != '') {
//                echo $this->Form->hidden('User.dateFrom', array('type' => 'hidden', 'value' => $searchDateFrom));
//            }
//            if (isset($searchDateTo) && $searchDateTo != '') {
//                echo $this->Form->hidden('User.dateTo', array('type' => 'hidden', 'value' => $searchDateTo));
//            }
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



<?php foreach ($users as $user) { ?>

    <div id="info<?php echo $user['User']['id']; ?>" style="display: none;">
        <!-- Fieldset -->
        <div class="nzwh-wrapper">
            <fieldset class="nzwh">
                <legend class="nzwh">
                    <?php
                    $full_name = $user['User']['first_name'] . ' ' . $user['User']['last_name'];
                    echo trim($full_name) ? $full_name : 'N/A';
                    ?>
                </legend>
                <div class="drt">

                    <span>Company Name :</span>   <?php echo $user['User']['company_name']; ?><br/>
    <!--                <span>ABN :</span>  <?php //echo $user['User']['abn'] ? $user['User']['abn'] : 'N/A';       ?><br/>-->
                    <span>Position :</span>   <?php echo $user['User']['position'] ? $user['User']['position'] : 'N/A'; ?><br/>
                    <span>First Name :</span>   <?php echo $user['User']['first_name'] ? $user['User']['first_name'] : 'N/A'; ?><br/>
                    <span>Last Name :</span>   <?php echo $user['User']['last_name'] ? $user['User']['last_name'] : 'N/A'; ?><br/>
                    <span>Email Address :</span>   <?php echo $user['User']['email_address'] ? $user['User']['email_address'] : 'N/A'; ?><br/>
                    <span>Address :</span>   <?php echo $user['User']['address'] ? $user['User']['address'] : 'N/A'; ?><br/>
                    <!--<span>City :</span>   <?php //echo $user['City']['city_name'];       ?><br/>
                    <span>State :</span>   <?php //echo $user['State']['state_name'];       ?><br/>
                    <span>Country :</span>   <?php //echo $user['Country']['country_name'];       ?><br/>-->
                    <span>Location :</span>   <?php echo $user['Location']['name'] ? $user['Location']['name'] : 'N/A'; ?><br/>
                    <span>Contact Number  :</span>   <?php echo $user['User']['contact'] ? $this->Text->usformat($user['User']['contact']) : 'N/A'; ?><br/>
                    <span>Company Number  :</span>   <?php echo $user['User']['company_contact'] ? $this->Text->usformat($user['User']['company_contact']) : 'N/A'; ?><br/>
                    <span>Website  :</span>   <?php echo $user['User']['url'] ? $user['User']['url'] : 'N/A'; ?><br/>
                    <!-- <span>Industry in which they work  :</span>   <?php //echo  $user['Industry']['name'];       ?><br/>-->

                </div>
            </fieldset>
        </div>

    </div>
<?php }
?>