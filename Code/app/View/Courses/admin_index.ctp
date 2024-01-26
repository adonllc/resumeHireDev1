<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-graduation-cap" ></i> Course » ', 'javascript:void(0)', array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list"></i> List Courses', 'javascript:void(0)', array('escape' => false));
?>

<!--main content start-->
<section id="main-content" class="site-min-height">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>

            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                         <span class="exlfink">Courses List </span>
                         <?php
                        $adminLId = $this->Session->read('adminid');
                        $checkSubRols = ClassRegistry::init('Admin')->getAdminRolesSub($this->Session->read('adminid'));
                        if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 8, 1)){ ?>
                           <span class="exportlink btn btn-success btn-xs pull-right">
                            <i class="" aria-hidden="true"></i> 
                            <?php echo $this->Html->link('Add Course ', array('controller' => 'courses', 'action' => 'addcourse'), array('escape' => false, 'title' => 'Add Course')); ?>
                        </span>
                        <?php } ?>
                    </header>
                    <div class="row-fluid">
                        <?php echo $this->Session->flash(); ?>
                        <div class="panel-body">
                              <?php echo $this->Form->create("Course", array("url" => "index", "method" => "Post")); ?>
                            <p>Search Course by Name</p>
                            <div class="form-group">
                                <?php echo $this->Form->text('Course.name', array('maxlength' => '80', 'label' => '',  'class' => 'form-control','div' => false, 'value' => $name, 'placeholder' => 'Search By Keyword')); ?>
                            </div>
                           
                            <?php echo $this->Ajax->submit("Search", array('div' => false, 'url' => array('controller' => 'courses', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => 'btn btn-success')); ?>
                            <?php echo $this->Html->link('Clear Filter', array('controller' => 'courses', 'action' => 'index',''), array('escape' => false, 'class' => 'btn btn-default')); ?>
                                <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </section>
            </div>

            <!-- element start-->
            <div id="listID">
                <?php echo $this->element("admin/courses/index"); ?>
            </div>
            <!-- element end-->

        </div>
        <!-- page end-->

    </section>
</section>
<!--main content end-->






