
<script>
    function jobStatusOpen() {
        $('#jobStatus').val(0);
        $('#jobStatusClosed').removeClass('active');
        $('#jobStatusOpen').addClass('active');
        getJobList();
        return false;
    }
    function jobStatusClosed() {
        $('#jobStatus').val(1);
        $('#jobStatusOpen').removeClass('active');
        $('#jobStatusClosed').addClass('active');
        getJobList();
        return false;
    }

    function getJobList() {

        $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH; ?>/jobs/management",
            cache: false,
            data: $('#jobManagement').serialize(),
            beforeSend: function () {
                $("#loaderID").show();
            },
            complete: function () {
                $("#loaderID").hide();
            },
            success: function (result) {
                $('#listID').html(result);
            }
        });
        return false;

    }
</script>
<div class="my_accnt">
    <?php //echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu'); ?>
                <div class="col-sm-9 col-lg-9 col-xs-12">
                    <div class="my-profile-boxes info_dv_esdit_pre">
                        <div class="my-profile-boxes-top my-education-boxes"><h2><i><?php echo $this->Html->image('front/home/manage-job-icon2.png', array('alt' => '')); ?></i><span><?php echo __d('user', 'Manage Jobs', true);?></span></h2>
                            <div class="add-alert"><?php echo $this->Html->link(__d('user', 'Create Job', true), array('controller' => 'jobs', 'action' => 'createJob'), array()); ?></div>
                        </div>
                        <div class="information_cntn payment-history-bx">

                            <?php echo $this->element('session_msg'); ?>    

                            <div class="listing_page">
                                <?php //echo $this->Form->create('Job', array('action' => 'management', 'method' => 'POST', 'name' => 'jobManagement', 'enctype' => 'multipart/form-data', 'id' => 'jobManagement')); ?>
                                <!--                                <div class="tabs"> 
                                                                    <ul>
                                                                        <li>
                                <?php echo $this->Html->link(__d('user', 'Open', true), 'javascript:void(0);', array('onclick' => 'jobStatusOpen();', 'id' => 'jobStatusOpen', 'class' => 'active')); ?>
                                                                        </li>
                                                                        <li><?php echo $this->Html->link('Closed', 'javascript:void(0);', array('onclick' => 'jobStatusClosed();', 'id' => 'jobStatusClosed', 'class' => '')); ?></li>
                                <?php echo $this->Form->input('Job.type', array('type' => 'hidden', 'value' => '0', 'id' => 'jobStatus')); ?>
                                                                    </ul>
                                                                </div>-->
                                <?php //echo $this->Form->end(); ?>

                                <div class="job_scroll">
                                    <div id="loaderID" style="display:none;position:absolute;margin-left:0;"><?php echo $this->Html->image("loader_large_blue.gif"); ?></div>
                                    <div id='listID'>

                                        <?php echo $this->element('jobs/management'); ?>    

                                    </div>

                                </div>
                            </div>
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




