
<script type="text/javascript">
    $(document).ready(function () {
        $("#changepassword").validate();
    });
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-question" ></i> Security Questions ', 'javascript:void(0);', array('escape' => false));
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
                <h4 style="margin-left:15px" class="m-bot15">Security Questions</h4>
                <section class="panel">
                    <div class="panel-body">                              
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Question 1</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.question1', array('placeholder' => "Question 1", 'label' => '', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Answer 1</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.answer1', array('placeholder' => "Answer 1", 'class' => 'form-control required')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Question 2</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.question2', array('placeholder' => "Question 2", 'label' => '', 'div' => false, 'class' => 'form-control required')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Answer 2</label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('Admin.answer2', array('placeholder' => "Answer 2", 'class' => 'form-control required')) ?>
                            </div>
                        </div>

                    </div>
                </section>                          
            </div>
        </div>
        <div class="col-lg-12">
            <?php echo $this->Form->hidden('Admin.id'); ?>
            <?php
            if (IS_LIVE) {
                echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success'));
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