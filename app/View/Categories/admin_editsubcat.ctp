<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#addCat").validate();
    });
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-th" ></i> Categories » ', array('controller' => 'categories', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-table" ></i> '.$cateInfo['Category']['name'] .'  »', array('controller' => 'categories', 'action' => 'subindex',$cslug), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list"></i> Edit Sub Category', 'javascript:void(0)', array('escape' => false));
?>
<?php echo $this->Form->create('Category', array('method' => 'POST', 'name' => 'editsubcat', 'id' => 'addCategory', 'enctype' => 'multipart/form-data')); ?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->


            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Edit Sub Category Detail</h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                       Sub Category Details:
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Sub Category Name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Category.name', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="col-lg-10">
            <?php
            echo $this->Form->hidden('Category.id');
            echo $this->Form->hidden('Category.slug');
            echo $this->Form->hidden('Category.old_name');
            ?>
            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php echo $this->Html->link('Cancel', array('controller' => 'categories', 'action' => 'subindex',$cslug), array('escape' => false,'class'=>'btn btn-danger')); ?>
        </div>
        <!-- page end -->
    </section>
</section>
<?php echo $this->Form->end(); ?>
<!-- main content end -->