<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-envelope" ></i> Change Email ', 'javascript:void(0);', array('escape' => false));
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#changeAdminEmail").validate();
        $("#changeAdminCCEmail").validate();
    });
</script>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <?php echo $this->Form->create('Admin', array('method' => 'POST', 'name' => 'changeAdminEmail', 'enctype' => 'multipart/form-data', 'id' => 'changeAdminEmail', 'class' => 'form-horizontal tasi-form')); ?>
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->
            <div class="col-lg-12">
                <?php echo $this->Session->flash(); ?>
                <h4 style="margin-left:15px" class="m-bot15">Change Admin Email</h4>
                <section class="panel">
                    <div class="panel-body">                              
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Current Email</label>
                            <div class="col-sm-10">
                                <div class="form-control">
                                    <?php echo $Admins['Admin']['email']; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">New Email</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.new_email', array('id' => 'email', 'placeholder' => "New Email", 'id' => 'email', 'label' => '', 'div' => false, 'class' => 'form-control required email', 'data-bv-notempty' => 'true')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Confirm Email</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.conf_email', array('placeholder' => "Confirm Email", 'equalto' => '#email', 'label' => '', 'div' => false, 'class' => 'form-control required email', 'data-bv-notempty' => 'true')) ?>
                            </div>
                        </div>      
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <?php
                                echo $this->Form->input('Admin.id', array('type' => 'hidden', 'value' => $Admins['Admin']['id']));
                                echo $this->Form->input('Admin.old_email', array('type' => 'hidden', 'value' => $Admins['Admin']['email']));
                                ?>
                                <?php 
                                if(IS_LIVE){
                                    echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success'));
                                    echo $this->Html->link('Cancel', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false, 'class' => 'btn btn-danger')); 
                                }else{
                                    echo "<blockquote> You are not allowed to update above information, because It's a demo of this product. Once we deliver code to you, you'll be able to update Configurations. </blockquote>";
                                }?>
                            </div>
                        </div>      
                    </div>
                </section>                        
            </div>
        </div>
        
        <?php echo $this->Form->end(); ?>
        <!-- page end-->
        <div style="clear: both;"></div>
        <!-- page end-->
    </section>
</section>
