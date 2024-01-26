
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-file-text" ></i> Translations ', 'javascript:void(0);', array('escape' => false));
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#changecontact").validate();
    });
</script>
<style>
    .form-group textarea {
        height:auto
    }
</style>
<?php echo $this->Form->create('Setting', array('method' => 'POST', 'name' => 'changecontact', 'enctype' => 'multipart/form-data', 'id' => 'changecontact', 'class' => 'form-horizontal tasi-form')); ?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->
            <div class="col-lg-12">
                <?php echo $this->Session->flash(); ?>
                <h4 style="margin-left:15px" class="m-bot15">Translations File</h4>
                <section class="panel">
                    <div class="panel-body">                              

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">French Common Translation</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->textarea('Setting.common', array('cols' => '5', 'rows' => '15', 'label' => '', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div> 

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">French controller Translation</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->textarea('Setting.controller', array('cols' => '5', 'rows' => '15', 'label' => '', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div> 

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">French Home Translation</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->textarea('Setting.home', array('cols' => '5', 'rows' => '15', 'label' => '', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div> 

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">French User Translation</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->textarea('Setting.user', array('cols' => '5', 'rows' => '15', 'label' => '', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div> 

                    </div>
                </section>                          
            </div>
        </div>
        <div class="col-lg-12">  

            <?php
            if (IS_LIVE) {
                echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'type' => 'submit', 'class' => ' btn btn-success btn-cons'));
                echo $this->Html->link('Cancel', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false, 'class' => 'btn btn-danger'));
            } else {
                echo "<blockquote> You are not allowed to update above information, because It's a demo of this product. Once we deliver code to you, you'll be able to update Configurations. </blockquote>";
            }
            ?>
        </div>
        <!-- page end-->
    </section>
</section>
<?php echo $this->Form->end(); ?>