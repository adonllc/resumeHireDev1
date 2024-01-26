
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-code" ></i> Meta Management ', 'javascript:void(0);', array('escape' => false));
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#manage").validate();
    });
</script>
<?php echo $this->Form->create('Admin', array('method' => 'POST', 'name' => 'metaManagement', 'enctype' => 'multipart/form-data', 'id' => 'manage', 'class' => 'form-horizontal tasi-form')); ?>
<style type='text/css'>
    label.col-sm-2.col-sm-2.control-label {
        line-height: normal;
    }
</style>
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
                <h4 style="margin-left:15px" class="m-bot15">Default Meta Management</h4>
                <section class="panel">
                    <div class="panel-body"> 

                        <h4> Home Page Meta Management </h4>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Home Meta title</label>
                            <div class="col-sm-10">

                                <?php echo $this->Form->text('Admin.default_title', array('placeholder' => "Default title", 'label' => '', 'div' => false, 'class' => 'form-control')) ?>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Home Meta keywords</label>
                            <div class="col-sm-10">

                                <?php echo $this->Form->text('Admin.default_keyword', array('placeholder' => "Default keywords", 'label' => '', 'div' => false, 'class' => 'form-control')) ?>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Home Meta description</label>
                            <div class="col-sm-10">

                                <?php echo $this->Form->text('Admin.default_description', array('placeholder' => "Default description", 'label' => '', 'div' => false, 'class' => 'form-control')) ?>

                            </div>
                        </div>

                        <h4> Job Meta Management </h4>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Job Meta title</label>
                            <div class="col-sm-10">

                                <?php echo $this->Form->text('Admin.meta_jobtitle', array('placeholder' => "Job title", 'label' => '', 'div' => false, 'class' => 'form-control')) ?>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Job Meta keywords</label>
                            <div class="col-sm-10">

                                <?php echo $this->Form->text('Admin.meta_jobkeywords', array('placeholder' => "Job keywords", 'label' => '', 'div' => false, 'class' => 'form-control')) ?>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Job Meta description</label>
                            <div class="col-sm-10">

                                <?php echo $this->Form->text('Admin.meta_jobdescription', array('placeholder' => "Job description", 'label' => '', 'div' => false, 'class' => 'form-control')) ?>

                            </div>
                        </div>

                        <h4> Category Meta Management </h4>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Category Meta title</label>
                            <div class="col-sm-10">

                                <?php echo $this->Form->text('Admin.meta_catetitle', array('placeholder' => "Category title", 'label' => '', 'div' => false, 'class' => 'form-control')) ?>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Category Meta keywords</label>
                            <div class="col-sm-10">

                                <?php echo $this->Form->text('Admin.meta_catekeywords', array('placeholder' => "Category keywords", 'label' => '', 'div' => false, 'class' => 'form-control')) ?>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Category Meta description</label>
                            <div class="col-sm-10">

                                <?php echo $this->Form->text('Admin.meta_catedescription', array('placeholder' => "Category description", 'label' => '', 'div' => false, 'class' => 'form-control')) ?>

                            </div>
                        </div>

                    </div>
                </section>                          
            </div>
        </div>
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <div class="col-sm-2 col-sm-2 control-label">&nbsp;</div>
                <div class="col-lg-9">                    
                    <?php
                    echo $this->Form->input('Admin.id', array('type' => 'hidden', 'value' => $Admins['Admin']['id']));
                    //echo $this->Form->input('Admin.old_username', array('type' => 'hidden', 'value' => $Admins['Admin']['username']));
                    ?>
                    <?php
                    if (IS_LIVE) {
                        echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success'));
                        echo $this->Html->link('Cancel', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false, 'class' => 'btn btn-danger'));
                    } else {
                        echo "<blockquote> You are not allowed to update above information, because It's a demo of this product. Once we deliver code to you, you'll be able to update Configurations. </blockquote>";
                    }
                    ?>
                </div>


            </div>
        </div>

        <!-- page end-->
    </section>
</section>
<?php echo $this->Form->end(); ?>