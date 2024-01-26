<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#editCat").validate();
    });
  
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-laptop" ></i> Skills List » ', array('controller' => 'skills', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-pencil-square-o" ></i> Edit Skill');
?>





<?php echo $this->Form->create(null, array('enctype' => 'multipart/form-data', 'id' => 'editCat')); ?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->
            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Edit Skill</h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        Skill Details:
                    </header>
                    <div class="panel-body">


                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Skill Name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Skill.name', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
        <div class="col-lg-10">
            <?php
            echo $this->Form->hidden('Skill.id');
          //   echo $this->Form->hidden('Skill.old_c_word');
            
             
           
            ?>
            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php echo $this->Html->link('Cancel', array('controller' => 'skills', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-danger')); ?>        </div>
    </section>
</section>
<?php echo $this->Form->end(); ?>

<?php echo $this->element('sql_dump'); ?>