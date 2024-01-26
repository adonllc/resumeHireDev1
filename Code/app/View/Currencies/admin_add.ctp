<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#addCat").validate();
    });
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'swears', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-fa-money" ></i> Currency List » ', array('controller' => 'currencies', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-plus" ></i> Add Currency');
?>

<?php echo $this->Form->create(null, array('enctype' => 'multipart/form-data', 'id' => 'addCat')); ?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->
            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Add Currency </h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        Currency Details:
                    </header>
                    <div class="panel-body">


                        <div class="form-group">
                            <label class="col-sm-3 control-label">Currency Name<div class="required_field">*</div></label>
                            <div class="col-sm-9" >
                                <?php echo $this->Form->text('Currency.name', array('maxlength' => '50', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Currency Code<div class="required_field">*</div></label>
                            <div class="col-sm-9" >
                                <?php echo $this->Form->text('Currency.code', array('maxlength' => '20', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Currency Symbol<div class="required_field">*</div></label>
                            <div class="col-sm-9" >
                                <?php echo $this->Form->text('Currency.symbol', array('maxlength' => '10', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>

                            </div>
                        </div>
<!--                        <div class="form-group">
                            <label class="col-sm-3 control-label">Symbol Placement <div class="required_field">*</div></label>
                            <div class="col-sm-9" >
                                <table cellpadding="5">
                                    <tr>
                                        <td><input id="subscriber" type="radio" name="data[Currency][symbol_place]" value="before" class="required" checked></td>
                                        <td>Before</td>
                                    </tr>
                                    <tr>
                                        <td valign="top"> <input id="register" type="radio" name="data[Currency][symbol_place]" value="after"  class="required" ></td>
                                        <td>After</td>
                                    </tr>
                                </table>
                            </div>
                        </div>-->
                    </div>
                </section>

            </div>
        </div>
        <div class="col-lg-10">
            <?php echo $this->Form->hidden('Currency.symbol_place',array('value'=>'before'));
            echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php echo $this->Form->reset('Reset', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-danger', 'id' => 'resetForm')); ?>
        </div>
    </section>
</section>
<?php echo $this->Form->end(); ?>

