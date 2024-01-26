<script type="text/javascript">
    $(document).ready(function() {
        
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s`~!@#$%^&*()+={}|;:'",.\/?\\-]+$/.test(value);
            }, "Please do not enter  special character like < or >");
            $("#addPage").validate();
        });
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa fa-file-text-o" ></i> Content » ', array('controller' => 'pages', 'action' => 'index', ''), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-pencil-square-o"></i> Edit Page Details', 'javascript:void(0)', array('escape' => false));
?>
<!-- main content start -->
<!-- replace <form class="form-horizontal tasi-form" method="get" id="useraddedit"> -->
<?php echo $this->Form->create('Page', array('method' => 'POST', 'name' => 'addPage', 'id' => 'addPage', 'enctype' => 'multipart/form-data')); ?>
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->


            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Edit Page Detail</h4>
                <?php echo $this->Session->flash(); ?>
                <section class="panel">
                    <header class="panel-heading">
                        Page Details:
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Page Title <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Page.static_page_title', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control alphanumeric required")) ?>    
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Description <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Fck->fckeditor(array('Page', 'static_page_description'), $this->Html->base, $this->data['Page']['static_page_description']); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Page Title (German) <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Page.static_page_title_de', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control alphanumeric")) ?>    
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Description (German) <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Fck->fckeditor(array('Page', 'static_page_description_de'), $this->Html->base, $this->data['Page']['static_page_description_de']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Page Title (French) <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('Page.static_page_title_fra', array('maxlength' => '255', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control alphanumeric")) ?>    
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Description (French) <div class="required_field"></div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Fck->fckeditor(array('Page', 'static_page_description_fra'), $this->Html->base, $this->data['Page']['static_page_description_fra']); ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                 <div class="col-sm-2 col-sm-2 control-label">&nbsp;</div>
        <div class="col-lg-9">
            <?php echo $this->Form->hidden('Page.pageOldName');?>
            <?php echo $this->Form->hidden('Page.id');?>
            <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
            
            <?php //echo $this->Form->reset('Reset', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-danger')); ?>
            <?php echo $this->Html->link('Cancel', array('controller' => 'pages', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-danger')); ?>
        </div></div></div>
        <!-- page end -->
    </section>
</section>
<?php echo $this->Form->end(); ?>
<!-- main content end -->