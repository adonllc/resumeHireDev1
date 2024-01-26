
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-user" ></i> Change Username ', 'javascript:void(0);', array('escape' => false));
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#changeusername").validate();
    });
</script>
<?php echo $this->Form->create('Admin', array('method' => 'POST', 'name' => 'changeusername', 'enctype' => 'multipart/form-data', 'id' => 'changeusername', 'class' => 'form-horizontal tasi-form')); ?>

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
                <h4 style="margin-left:15px" class="m-bot15">Change Admin Username</h4>
                <section class="panel">
                    <div class="panel-body">                              
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Current Username</label>
                            <div class="col-sm-10">
                                <div class="form-control">
                                    <?php echo $Admins['Admin']['username']; ?>
                                </div>  
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">New Username</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.new_username', array('id' => 'username', 'placeholder' => "New Username", 'id' => 'username', 'label' => '', 'div' => false, 'class' => 'form-control required', 'data-bv-notempty' => 'true')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Confirm Username</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.conf_username', array('placeholder' => "Confirm Username", 'equalto' => '#username', 'label' => '', 'div' => false, 'class' => 'form-control required', 'data-bv-notempty' => 'true')) ?>
                            </div>
                        </div>      
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <?php
                                    echo $this->Form->input('Admin.id', array('type' => 'hidden', 'value' => $Admins['Admin']['id']));
                                    echo $this->Form->input('Admin.old_username', array('type' => 'hidden', 'value' => $Admins['Admin']['username']));
                                    ?>
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