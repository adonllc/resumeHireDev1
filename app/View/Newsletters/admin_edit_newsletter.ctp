<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">

    $(document).ready(function () {
        $.validator.addMethod("alphanumeric", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s`~!@#$%^&*()+={}|;:'",.\/?\\-]+$/.test(value);
        }, "Please do not enter  special character like < or >");
        $("#NewsletterFrom").validate();
    });

</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-users" ></i> Newsletter » ', array('controller' => 'newsletters', 'action' => 'index/'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list"></i> Edit Newsletter', 'javascript:void(0)', array('escape' => false));
?>

<?php echo $this->Form->create('NewsletterFrom', array('method' => 'POST', 'name' => 'addnewsletter', 'enctype' => 'multipart/form-data', 'id' => 'NewsletterFrom')); ?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->
            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Edit Newsletters </h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        Newsletter Details:
                    </header>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Subject<div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Newsletter.subject', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Message<div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Fck->fckeditor(array('Newsletter', 'message'), $this->Html->base, $this->data['Newsletter']['message']); ?>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
        <?php
        echo $this->Form->hidden('Newsletter.id');
        ?>
        <div class="col-lg-10">
            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php echo $this->Form->reset('Reset', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-danger', 'id' => 'resetForm')); ?>
        </div>


    </section>

    <?php echo $this->Form->end(); ?>

    <?php echo $this->Form->create('Newsletter', array('method' => 'POST', 'name' => 'addcountries', 'action' => 'testEmail', 'id' => 'NewsletterFrom2')); ?>
    <div class="testemail">
        <h5 style="padding-left: 15px">
            Check Newsletter Formating by test email
        </h5>
        
        <div class="form-group">
            <label class="col-sm-2 col-sm-2 control-label">Test Email <div class="required_field">*</div></label>
            <div class="col-sm-10" style="width: 50%" >
                <?php echo $this->Form->text('Newsletter.email', array('div' => false, 'class' => "form-control required email")) ?>
            </div>
            <?php echo $this->Form->submit('Send', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
        </div>
        <div class="col-lg-10">
            <?php echo $this->Form->hidden('Newsletter.slug'); ?>
        </div>
    </div>    
    <?php echo $this->Form->end(); ?>
</section>