<?php

echo $this->Html->script('jquery.validate.js'); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#adminEmailSettings").validate();
    });
</script>
<?php
echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css');
?>

<?php

$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list" ></i> Manage Email Setting » ', array('controller' => 'settings', 'action' => 'manageMails'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-envelope-o" ></i> Edit Email Settings ', array('controller' => 'settings', 'action' => 'editMails'), array('escape' => false));

?>
<?php echo $this->Form->create('emailsetting', array('method' => 'POST', 'name' => 'emailSettings', 'enctype' => 'multipart/form-data', 'id' => 'adminEmailSettings')); ?>

<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->


            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Edit Email Settings</h4>
                <?php echo $this->Session->flash(); ?>

                <section class="panel">
                    <header class="panel-heading">
                        Email Setting Details:
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Email Name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('MailSetting.mail_name', array('maxlength' => '20', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control", 'readonly')) ?>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Email Address <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('MailSetting.mail_value', array('maxlength' => '50', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control email required")) ?>    
                            </div>
                        </div>

                    </div>
                </section>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-sm-2 col-sm-2 control-label">&nbsp;</div>
                <div class="col-lg-9">
            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php echo $this->Html->link('Cancel', array('controller' => 'settings', 'action' => 'manageMails', ''), array('escape' => false, 'class' => 'btn btn-danger')); ?>
                </div></div></div>
    </section>
</section>

<?php 
echo $this->Form->hidden('MailSetting.id');

echo $this->Form->end(); ?>


