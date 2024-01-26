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
<?php if ($candidates) { //pr($candidates);exit;
    ?>
    <div class="col-lg-12">
        <section class="panel">
            <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
            <div id="loaderID" style="display:none;width: 90%;position:absolute;text-align: center;margin-top:120px"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
            <?php
            $urlArray = array_merge(array('controller' => 'jobs', 'action' => 'candidates', $separator));
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
                        <?php if ($this->Paginator->hasPrev('JobApply')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                        <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                        <?php if ($this->Paginator->hasNext('Job')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                        <?php echo $this->Paginator->last('JobApply', array()); ?>&nbsp;                    
                    </span>
                </div>
                <div class="panel-body">
                    <section id="no-more-tables">
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th class="sorting_paging"><?php echo $this->Paginator->sort('User.full_name', 'Candidate Name'); ?></th>
    <!--                                    <th><i class="orting_paging"></i> City</th>
                                    <th><i class="orting_paging"></i> State</th>-->
                                    <th><i class="orting_paging"></i> Email Address</th>
                                    <th><i class="orting_paging"></i> Contact</th>
                                    <th><i class="orting_paging"></i> Location</th>
                                    <th style="width:15%"><i class=" fa fa-gavel"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                global $designation;
                                $payStatus = array('0' => 'Pending', '1' => 'Inprogress', '2' => 'Completed');

                                foreach ($candidates as $candidate) {
                                    $userdetail = classregistry::init('User')->find('first', array('conditions' => array('User.id' => $candidate['JobApply']['user_id'])));
                                    //pr($userdetail);exit;
                                    ?>
                                    <tr>
                                        <td data-title="Full Name">
                                            <?php echo $this->Html->link($userdetail['User']['first_name'] . ' ' . $userdetail['User']['last_name'], array("controller" => "candidates", "action" => 'editcandidates', $userdetail['User']['slug']), array('escape' => false, 'class' => "", 'title' => 'Profile')); ?>
                                        </td>
                                        <td data-title="Location">
                                            <?php //echo $userdetail['City']['city_name']?$userdetail['City']['city_name']:'N/A'; ?> 
                                            <?php echo $userdetail['User']['email_address'] ? $userdetail['User']['email_address'] : 'N/A'; ?> <br>
                                        </td>
                                        <td data-title="Location">
                                            <?php //echo $userdetail['State']['state_name']?$userdetail['State']['state_name']:'N/A'; ?> 
                                            <?php echo $userdetail['User']['contact'] ? $this->Text->usformat($userdetail['User']['contact']) : 'N/A'; ?> <br>
                                        </td>
                                        <td data-title="Location">
                                            <?php echo $userdetail['Location']['name'] ? $userdetail['Location']['name'] : 'N/A'; ?> <br>
                                        </td>

                                        <td data-title="Action">
                                            <a href="#info<?php echo $candidate['JobApply']['user_id']; ?>" rel="facebox" title="View" class="btn btn-info btn-xs"><i class="fa fa-eye "></i></a>
                                            <?php echo $this->Html->link('<i class="fa fa-file"></i>', array("controller" => "candidates", "action" => 'certificates', $userdetail['User']['slug']), array('escape' => false, 'class' => "btn btn-warning btn-xs", 'title' => 'Manage Certificates')); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>

            <?php echo $this->Form->end(); ?>
            <div id="pagingLinks" align="right">
                <?php __("Showing Page"); ?>
                <div class="countrdm"><?php echo $this->Paginator->counter('No. of Results <span class="badge-gray">{:start}</span> - <span class="badge-gray">{:end}</span> of <span class="badge-gray">{:count}</span>'); ?></div>
                &nbsp;
                <span class="custom_link pagination"> 
                    <?php echo $this->Paginator->first('First', array()); ?>&nbsp;
                    <?php if ($this->Paginator->hasPrev('JobApply')) echo $this->Paginator->prev('Prev', array()); ?>&nbsp;
                    <?php echo $this->Paginator->numbers(array('separator' => '  ')); ?>&nbsp;
                    <?php if ($this->Paginator->hasNext('Job')) echo $this->Paginator->next('Next', array()); ?>&nbsp;
                    <?php echo $this->Paginator->last('JobApply', array()); ?>&nbsp;                    
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
foreach ($candidates as $candidate) {
    $userdetail = classregistry::init('User')->find('first', array('conditions' => array('User.id' => $candidate['JobApply']['user_id'])));
    ?>

    <div id="info<?php echo $candidate['JobApply']['user_id']; ?>"
         style="display: none;">
        <!-- Fieldset -->
        <div class="nzwh-wrapper">
            <fieldset class="nzwh">
                <legend class="nzwh">
                    <?php echo $userdetail['User']['first_name'] . ' ' . $userdetail['User']['last_name']; ?>
                </legend>
                <div class="drt">

                    <span>First Name :</span>   <?php echo $userdetail['User']['first_name']; ?><br/>
                    <span>Last Name :</span>   <?php echo $userdetail['User']['last_name']; ?><br/>
                    <span>Email Address :</span>   <?php echo $userdetail['User']['email_address']; ?><br/>
    <!--                    <span>City :</span>   <?php //echo $userdetail['City']['city_name'] ? $userdetail['City']['city_name'] : 'N/A';     ?><br/>
                    <span>State :</span>   <?php //echo $userdetail['State']['state_name'] ? $userdetail['State']['state_name'] : 'N/A';     ?><br/>
                    <span>Country :</span>   <?php //echo $userdetail['Country']['country_name'];     ?><br/>-->
                    <span>Location :</span>   <?php echo $userdetail['Location']['name'] ? $userdetail['Location']['name'] : 'N/A'; ?><br/>
                    <span>Contact Number  :</span>   <?php echo $userdetail['User']['contact'] ? $this->Text->usformat($userdetail['User']['contact']) : 'N/A'; ?><br/>
                    <?php //$categoryName = classregistry::init('Category')->getSubCatNames($userdetail['User']['email_notification_id']); ?>
            <!--                    <span>Email Notification  :</span>   <?php //echo $categoryName ? $categoryName : 'N/A';    ?><br/>-->



                </div>
            </fieldset>
        </div>

    </div>
<?php }
?>

