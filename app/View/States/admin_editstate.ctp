<?php echo $this->Html->script('jquery.validate.js'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#editCat").validate();
    });
  
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-globe" ></i> Countries » ', array('controller' => 'countries', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-table" ></i> '.$countryInfo['Country']['country_name'] .'  » ', array('controller' => 'states', 'action' => 'index',$cslug), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list"></i> Edit State', 'javascript:void(0)', array('escape' => false));
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
                <h4 style="margin-left:15px" class="m-bot15">Edit State</h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        State Details:
                    </header>
                    <div class="panel-body">


                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">State Name <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('State.state_name', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
        <div class="col-lg-10">
            <?php
            echo $this->Form->hidden('State.id');
            echo $this->Form->hidden('State.slug');
            echo $this->Form->hidden('State.old_name');
            ?>
            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            <?php echo $this->Html->link('Cancel', array('controller' => 'states', 'action' => 'index',$cslug), array('escape' => false, 'class' => 'btn btn-danger')); ?>        </div>
    </section>
</section>
<?php echo $this->Form->end(); ?>