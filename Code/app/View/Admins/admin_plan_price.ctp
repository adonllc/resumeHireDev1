
<script type="text/javascript">
    $(document).ready(function(){
        $("#changepassword").validate();
    });
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-lock" ></i> Manage Plan Price ', 'javascript:void(0);', array('escape' => false));
?>
<?php echo $this->Form->create('Admin', array('method' => 'POST', 'name' => 'changepassword', 'enctype' => 'multipart/form-data', 'id' => 'changepassword', 'class' => 'form-horizontal tasi-form')); ?>
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
                <h4 style="margin-left:15px" class="m-bot15">Manage Plan Price</h4>
                <section class="panel">
                    <div class="panel-body">                              
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Bronze ($)</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.bronze', array('placeholder' => "Bronze", 'label' => '', 'div' => false, 'class' => 'form-control required number' , 'min'=>0,'maxlength'=>10)) ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Silver ($)</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.silver', array('placeholder' => "Silver", 'label' => '', 'div' => false, 'class' => 'form-control required number', 'min'=>0,'maxlength'=>10)) ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Gold ($)</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.gold', array('placeholder' => "Gold", 'label' => '', 'div' => false, 'class' => 'form-control required number', 'min'=>0,'maxlength'=>10)) ?>
                            </div>
                        </div>
                    </div>
                </section>                          
            </div>
        </div>
        <div class="col-lg-10">
            <?php echo $this->Form->hidden('Admin.id'); ?>
            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php //echo $this->Form->button('Cancel', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-danger')); ?>
            <?php echo $this->Html->link('Cancel', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false, 'class' => 'btn btn-danger')); ?>
        </div>
        <!-- page end-->
    </section>
</section>
<?php echo $this->Form->end(); ?>       