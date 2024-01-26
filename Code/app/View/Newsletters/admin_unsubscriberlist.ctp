<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-users" ></i> Newsletter » ', array('controller' => 'newsletters', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list"></i> Unsubscriber User List', 'javascript:void(0)', array('escape' => false));
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
                        <span class="exlfink">Unsubscribe Users List </span>

                    </header>
                    <div class="row-fluid">
                        <?php echo $this->Session->flash(); ?>
                        <div class="panel-body">
                            <?php echo $this->Form->create("User", array("url" => "index", "method" => "Post")); ?>
                            <p>Search Unsubscribe users by email address</p>
                            <div class="form-group">
                                <?php echo $this->Form->text('User.email_address', array('maxlength' => '80', 'label' => '', 'div' => false, 'class' => 'form-control fix_widh', 'placeholder' => 'Search By Keyword')); ?>
                            </div>

                            <?php echo $this->Ajax->submit("Search", array('div' => false, 'url' => array('controller' => 'newsletters', 'action' => 'unsubscriberlist'), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => 'btn btn-success')); ?>
                            <?php echo $this->Html->link('Clear Filter', array('controller' => 'newsletters', 'action' => 'unsubscriberlist'), array('escape' => false, 'class' => 'btn btn-default')); ?>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </section>
            </div>

            <!-- element start-->
            <div id="listID">
                <?php echo $this->element("admin/newsletters/unsubscriberlist"); ?>
            </div>
            <!-- element end-->

        </div>
        <!-- page end-->

    </section>
</section>
<!--main content end-->






