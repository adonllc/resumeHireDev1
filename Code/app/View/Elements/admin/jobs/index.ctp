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
            $urlArray = array_merge(array('controller' => 'jobs', 'action' => 'index', $separator));
            $this->Paginator->_ajaxHelperClass = "Ajax";
            $this->Paginator->Ajax = $this->Ajax;
            $this->Paginator->options(array('update' => 'listID', 'url' => $urlArray, 'indicator' => 'loaderID'));
            ?>
            <?php echo $this->Form->create("Job", array("url" => "index", "method" => "Post")); ?>
            <div class="columns mrgih_tp">
                <div id="pagingLinks" align="right">
                    <?php __("Showing Page"); ?>
                    <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                    &nbsp;
                    <span class="custom_link pagination"> 
                        <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                        <?php if ($this->Paginator->hasPrev('Job')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                        <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                        <?php if ($this->Paginator->hasNext('Job')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                        <?php echo $this->Paginator->last('Last', array()); ?>&nbsp;                    
                    </span>
                </div>
                <div class="panel-body">
                    <section id="no-more-tables" style="overflow:auto;">
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th style="width:5%"><input name="chkRecordId" value="0" onClick="checkAll(this.form)" type='checkbox' class="checkall" /></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('User.first_name', 'Employer Name'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Job.title', 'Job Title'); ?></th>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('Job.company_name', 'Company Name'); ?></th>
                                    <th><i class="orting_paging"></i> Location</th>
                                    <th><i class="orting_paging"></i> Category</th>
                                    <th class="sorting_paging"><i class="fa fa-calendar"></i> <?php echo $this->Paginator->sort('Job.created', 'Date'); ?></th>
                                    <th><i class="orting_paging"></i> Jobseekers</th>
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
                                        <td data-title=""><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId" value="<?php echo $job['Job']['id']; ?>" /></td>

                                        <td data-title="Full Name">
                                            <a href="#userInfo<?php echo $job['Job']['user_id']; ?>" rel="facebox1" title="View" class=""><?php echo $job['User']['first_name'] . ' ' . $job['User']['last_name']; ?></a>
                                        </td>
                                        <td data-title="Title"><?php echo $job['Job']['title']; ?></td>
                                        <td data-title="Title"><?php echo $job['Job']['company_name'] ? $job['Job']['company_name'] : 'N/A'; ?></td>
                                        <td data-title="Location">
                                            <?php echo $job['Job']['job_city']?$job['Job']['job_city']:'N/A'; ?> <br>
                                            <?php //echo $job['Job']['address']; ?> 
                                            <?php //echo $job['City']['city_name'] . ', ' . $job['State']['state_name']; ?>

                                        </td>
                                        <td data-title="Category">
                                            <?php echo $job['Category']['name']; ?>
                                        </td>
                                        <td data-title="Created"><?php echo date('F d,Y', strtotime($job['Job']['created'])); ?></td>
                                        <td data-title="Created"><?php echo ClassRegistry::init('JobApply')->getTotalCandidate($job['Job']['id']); ?></td>
        <!--                                        <td data-title="Category">
                                        <?php
//                                            if ($job['Job']['expire_time'] < time() && $job['Job']['payment_status'] == 2) {
//                                                echo 'Job Expired';
//                                            } else {
//                                                echo $this->Form->select('Job.payment_status', $payStatus, array('class' => 'payselect', 'empty' => false, 'value' => $job['Job']['payment_status'], 'onChange' => 'changeStatus("' . $job['Job']['job_number'] . '",this.value)'));
                                        ?>
                                                <div id="loaderIDActCC<?php echo $job['Job']['job_number']; ?>" style="display:none;position:absolute;margin:4px 0 0 33px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                                        <?php //} ?>
                                        </td>-->
                                        <td data-title="Action">
                                            <?php if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 3, 2)){ ?>
                                            <div id="loaderIDAct<?php echo $job['Job']['id']; ?>" style="display:none;position:absolute;margin:0px 0 0 4px;z-index: 9999;"><?php echo $this->Html->image("loading.gif"); ?></div>
                                            <span id="status<?php echo $job['Job']['id']; ?>">
                                                <?php
                                                if ($job['Job']['status'] == '1') {
                                                    echo $this->Ajax->link('<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>', array('controller' => 'jobs', 'action' => 'deactivatejob', $job['Job']['slug']), array('update' => 'status' . $job['Job']['id'], 'indicator' => 'loaderIDAct' . $job['Job']['id'], 'confirm' => 'Are you sure you want to Deactivate ?', 'escape' => false, 'title' => 'Deactivate'));
                                                } else {
                                                    echo $this->Ajax->link('<button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button>', array('controller' => 'jobs', 'action' => 'activatejob', $job['Job']['slug']), array('update' => 'status' . $job['Job']['id'], 'indicator' => 'loaderIDAct' . $job['Job']['id'], 'confirm' => 'Are you sure you want to Activate ?', 'escape' => false, 'title' => 'Activate'));
                                                }
                                                ?>
                                            </span>
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>', array("controller" => "jobs", "action" => 'editjob', $job['Job']['slug']), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Edit'));
                                            }
                                            if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 3, 3)){
                                                echo $this->Html->link('<i class="fa fa-trash-o "></i>', array('controller' => 'jobs', 'action' => 'deletejobs', $job['Job']['slug']), array('update' => 'deleted' . $job['Job']['id'], 'indicator' => 'loaderID', 'class' => 'btn btn-primary btn-xs', 'confirm' => 'Are you sure you want to Delete ?', 'escape' => false, 'title' => 'Delete'));
                                            } ?>
                                            <a href="#info<?php echo $job['Job']['id']; ?>" rel="facebox" title="View" class="btn btn-info btn-xs"><i class="fa fa-eye "></i></a>
                                            <?php echo $this->Html->link('<i class="fa fa-users"></i>', array("controller" => "jobs", "action" => 'candidates', $job['Job']['slug']), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Jobseeker List')); ?>
                                            <?php //echo $this->Html->link('<i class="fa fa-files-o"></i>', array("controller" => "jobs", "action" => 'copyJob', $job['Job']['slug']), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Copy Details')); ?>
                                            <?php echo $this->Html->link('<i class="fa fa-file"></i>', array('controller' => 'jobs', 'action' => 'copyJob', $job['Job']['slug']), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Copy Details')); ?>
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
                    <?php echo $this->Form->text('Job.idList', array('type' => 'hidden', 'value' => '', 'id' => 'idList')); ?>
                    <?php echo $this->Form->text('Job.action', array('type' => 'hidden', 'value' => 'activate', 'id' => 'action')); ?>
                    <?php 
                    if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 3, 2)){
                        echo $this->Ajax->submit("Activate", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'index', $slug), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('activate');", 'confirm' => "Are you sure you want to Activate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('activated');", 'class' => 'btn btn-success btn-cons')); 
                        echo $this->Ajax->submit("Deactivate", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'index', $slug), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('deactivate');", 'confirm' => "Are you sure you want to Deactivate ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deactivated');", 'class' => 'btn btn-success btn-cons'));
                    }
                    if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 3, 3)){
                        echo $this->Ajax->submit("Delete", array('div' => false, 'url' => array('controller' => 'jobs', 'action' => 'index', $slug), 'update' => 'listID', 'indicator' => 'loaderID', 'before' => "setAction('delete');", 'confirm' => "Are you sure you want to Delete ?", 'condition' => "isAnySelect(this.form)", "complete" => "showMessage('deleted');", 'class' => 'btn btn-success btn-cons')); 
                    }?> 

                </div>
            </div>
            <?php
            if (isset($searchKey) && $searchKey != '') {
                echo $this->Form->hidden('Job.userName', array('type' => 'hidden', 'value' => $searchKey));
            }
            if (isset($searchDateFrom) && $searchDateFrom != '') {
                echo $this->Form->hidden('Job.dateFrom', array('type' => 'hidden', 'value' => $searchDateFrom));
            }
            if (isset($searchDateTo) && $searchDateTo != '') {
                echo $this->Form->hidden('Job.dateTo', array('type' => 'hidden', 'value' => $searchDateTo));
            }
            ?>
            <?php echo $this->Form->end(); ?>
            <div id="pagingLinks" align="right">
                <?php __("Showing Page"); ?>
                <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                &nbsp;
                <span class="custom_link pagination"> 
                    <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                    <?php if ($this->Paginator->hasPrev('Job')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                    <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                    <?php if ($this->Paginator->hasNext('Job')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
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



<?php
global $priceArray;
global $worktype;
foreach ($jobs as $job) {
    ?>

    <div id="info<?php echo $job['Job']['id']; ?>"
         style="display: none;">
        <!-- Fieldset -->
        <div class="nzwh-wrapper">
            <fieldset class="nzwh">
                <legend class="nzwh">
                    <?php echo $this->Text->truncate($job['Job']['title'], 40); ?>
                </legend>
                <div class="drt">
                    <span>Search Count :</span> <?php echo $job['Job']['search_count'] ? $job['Job']['search_count'] : 'No Search Count'; ?><br/>
                    <span>Job View Count :</span> <?php echo $job['Job']['view_count'] ? $job['Job']['view_count'] : 'No View Count'; ?><br/>
                    <span>Employer Name :</span>   <?php echo $job['User']['first_name'] . ' ' . $job['User']['last_name']; ?><br/>
                    <span>Job Title :</span>   <?php echo $job['Job']['title']; ?><br/>
                    <span>Category :</span>   <?php echo $job['Category']['name']; ?><br/>
                    <span>Company Name :</span>   <?php echo $job['Job']['company_name'] ? $job['Job']['company_name'] : 'N/A'; ?><br/>
                    <!--<span>Wage Package :</span>   <?php //echo $priceArray[$job['Job']['price']];    ?><br/>-->
                    <span>Work Type :</span>   <?php echo $worktype[$job['Job']['work_type']]; ?><br/>
                    <span>Contact Name :</span>   <?php echo $job['Job']['contact_name']; ?><br/>
                    <span>Contact Number :</span>   <?php echo $job['Job']['contact_number']; ?><br/>
                   <!-- <span>City:</span>  <?php //echo $job['City']['city_name'] ? $job['City']['city_name'] : 'N/A';    ?><br/>
                    <span>State:</span>  <?php //echo $job['State']['state_name'] ? $job['State']['state_name'] : 'N/A';    ?><br/>
                    <span>Postal Code:</span>  <?php //echo $job['Job']['postal_code'] ? $job['Job']['postal_code'] : 'N/A';    ?><br/>-->
                    <span>Company Website :</span>   <?php echo $job['Job']['url'] ? $job['Job']['url'] : 'N/A'; ?><br/>
                    <span>Job Description :</span>   <div class="set_des"><?php echo $job['Job']['description']; ?></div><br/>
                    <?php /* <span>Highlight Job 1 :</span>   <?php echo $job['Job']['selling_point1']; ?><br/>
                    <?php if (isset($job['Job']['type']) && $job['Job']['type'] != 'bronze') { ?>
                        <span>Highlight Job 2 :</span>   <?php echo $job['Job']['selling_point2']; ?><br/>
                    <span>Highlight Job 3 :</span>   <?php echo $job['Job']['selling_point3']; ?><br/>  
                        <span>Logo :</span>  
                        <?php
                        $image = '';
                        if ($job['Job']['job_logo_check']) {
                            $logo_image = ClassRegistry::init('User')->field('profile_image', array('User.id' => $job['Job']['user_id']));
                            $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $logo_image;
                            if (file_exists($path) && !empty($logo_image)) {
                                $image = $this->Html->image(DISPLAY_THUMB_PROFILE_IMAGE_PATH . $logo_image, array('escape' => false));
                            } else {
                                $image = $this->Html->image('front/no_image_logo1.png');
                            }
                        } else {
                            $logo_image = $job['Job']['logo'];
                            $path = UPLOAD_JOB_LOGO_PATH . $logo_image;
                            if (file_exists($path) && !empty($logo_image)) {
                                $image = $this->Html->image(DISPLAY_JOB_LOGO_PATH . $logo_image, array('escape' => false));
                            } else {
                                $image = $this->Html->image('front/no_image_logo1.png');
                            }
                        }
                        echo '<span class="logo_match">' . $image . '</span>';
                        ?>
                        <br/>
                    <?php } */ ?>
    <!--                <span>Job paid  :</span>  
                    <?php
//                    $payment_type = $job['Job']['payment_type'];
//                    if ($payment_type == 0 || $payment_type == 3) {
//                        echo 'Paypal';
//                    } elseif ($payment_type == 1) {
//                        echo 'Promo Code';
//                    } elseif ($payment_type == 2 || $payment_type == 4) {
//                        echo 'Invoice';
//                    }
                    ?>
            <br/>    -->
                    <?php
//                    $paymentDetails = ClassRegistry::init('Payment')->find('first', array('conditions' => array('Payment.job_id' => $job['Job']['id'])));
//                    if ($payment_type != 1 && !empty($paymentDetails)) {
//                        
                    ?>
    <!--                <span>Transaction Id  :</span> -->
                    <?php
//                        if ($payment_type == 0 || $payment_type == 3 || $payment_type == 1) {
//                            echo $paymentDetails['Payment']['transaction_id'];
//                        } elseif ($payment_type == 2 || $payment_type == 4) {
//                            if ($paymentDetails['Payment']['invoice'] != '') {
//                                echo $this->Html->link($paymentDetails['Payment']['transaction_id'], array("controller" => "users", "action" => 'downloadInvoice', $paymentDetails['Payment']['invoice']), array('escape' => false, 'class' => "", 'title' => 'Download Invoice'));
//                            } else {
//                                echo $paymentDetails['Payment']['transaction_id'];
//                            }
//                        }
//                    }
                    ?>
                    <!--                <br/>  -->
                    <?php //if (trim($job['Job']['promo_code']) != '') {  ?>
                    <!--                    <span>Promo Code  :</span> -->
                    <?php //echo $job['Job']['promo_code']; ?>
                    <?php //}  ?>


                </div>
            </fieldset>
        </div>

    </div>
<?php }
?>




<?php
foreach ($jobs as $job) {

    $userdetail = classregistry::init('User')->find('first', array('conditions' => array('User.id' => $job['Job']['user_id'])));
    ?>

    <div id="userInfo<?php echo $job['Job']['user_id']; ?>"
         style="display: none;">
        <!-- Fieldset -->
        <div class="nzwh-wrapper">
            <fieldset class="nzwh">
                <legend class="nzwh">
                    <?php
                    $full_name = $userdetail['User']['first_name'] . ' ' . $userdetail['User']['last_name'];
                    echo trim($full_name) ? $full_name : 'N/A';
                    ?>
                </legend>
                <div class="drt">

                    <span>Company Name :</span>   <?php echo $userdetail['User']['company_name']; ?><br/>
    <!--                <span>ABN :</span>  <?php //echo $userdetail['User']['abn'] ? $userdetail['User']['abn'] : 'N/A';    ?><br/>-->
                    <span>Position :</span>   <?php echo $userdetail['User']['position']; ?><br/>
                    <span>First Name :</span>   <?php echo $userdetail['User']['first_name']; ?><br/>
                    <span>Last Name :</span>   <?php echo $userdetail['User']['last_name']; ?><br/>
                    <span>Email Address :</span>   <?php echo $userdetail['User']['email_address']; ?><br/>
                    <span>Address :</span>   <?php echo $userdetail['User']['address']; ?><br/>
    <!--                <span>City :</span>   <?php //echo $userdetail['City']['city_name'];    ?><br/>
                    <span>State :</span>   <?php //echo $userdetail['State']['state_name'];    ?><br/>
                    <span>Country :</span>   <?php //echo $userdetail['Country']['country_name'];    ?><br/>-->
                    <span>Contact Number  :</span>   <?php echo $this->Text->usformat($userdetail['User']['contact']); ?><br/>
                    <span>Company Number  :</span>   <?php echo $this->Text->usformat($userdetail['User']['company_contact']); ?><br/>
                    <span>Website  :</span>   <?php echo $userdetail['User']['url'] ? $userdetail['User']['url'] : 'N/A'; ?><br/>
                    <!-- <span>Industry in which they work  :</span>   <?php echo classregistry::init('Industry')->field('name', array('Industry.id' => $userdetail['User']['industry'])); ?><br/>-->

                </div>
            </fieldset>
        </div>

    </div>
<?php }
?>
