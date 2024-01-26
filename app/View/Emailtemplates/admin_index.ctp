
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-envelope-o" ></i> Email Template Management » ', 'javascript:void(0)', array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list"></i> Email Template List', 'javascript:void(0)', array('escape' => false));
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
                        <span class="exlfink">Email Template List </span>
                    </header>
                    <div class="row-fluid">
                        <?php echo $this->Session->flash(); ?>
                     <?php echo $this->element("admin/emailtemplate/index"); ?>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
