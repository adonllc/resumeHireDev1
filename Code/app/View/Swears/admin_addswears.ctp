<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#addCat").validate();
    });
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'swears', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-file-word-o" ></i> Swear Words List » ', '/admin/swears', array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-plus" ></i> Add Swears Words');
?>

<?php echo $this->Form->create(null, array('enctype' => 'multipart/form-data', 'id' => 'addCat'));?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->
            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Add Swear Words </h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        Swear Details:
                    </header>
                    <div class="panel-body">
                        
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Swear Words Name<div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Swear.s_word', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>
                                <em>(comma (,) separated)</em>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
        <div class="col-lg-10">
            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php echo $this->Form->reset('Reset', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-danger', 'id' => 'resetForm')); ?>
        </div>
    </section>
</section>
<?php echo $this->Form->end(); ?>

<?php echo $this->element('sql_dump'); ?>