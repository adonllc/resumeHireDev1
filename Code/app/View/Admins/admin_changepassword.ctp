
<script type="text/javascript">
    $(document).ready(function(){
        $("#changepassword").validate();
    });
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-lock" ></i> Change Password ', 'javascript:void(0);', array('escape' => false));
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
                <h4 style="margin-left:15px" class="m-bot15">Change Password</h4>
                <section class="panel">
                    <div class="panel-body">                              
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Current Password</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->password('Admin.old_password', array('placeholder' => "Current Password", 'label' => '', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">New Password</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->password('Admin.password', array('id' => 'password', 'placeholder' => "New Password", 'label' => '', 'minlength' => '8', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Confirm Password</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->password('Admin.confirm_password', array('placeholder' => "Confirm Password", 'equalto' => '#password', 'label' => '', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div>      
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->input('Admin.id', array('type' => 'hidden', 'value' => $this->Session->read("adminid"))); ?>
                                <?php 
                                if(IS_LIVE){
                                    echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); 
                                    echo $this->Html->link('Cancel', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false, 'class' => 'btn btn-danger')); 
                                }else{
                                    echo "<blockquote> You are not allowed to update above information, because It's a demo of this product. Once we deliver code to you, you'll be able to update Configurations. </blockquote>";    
                                }
                                ?>
                            </div>
                        </div>      
                    </div>
                </section>                          
            </div>
        </div>
    </section>
</section>
<?php echo $this->Form->end(); ?>       