<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-users" ></i> Newsletter » ', 'javascript:void(0)', array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list"></i> Sent Mail List', 'javascript:void(0)', array('escape' => false));
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
                         <span class="exlfink">Sent Mail List </span>
                    
                    </header>
                    <div class="row-fluid">
                        <?php echo $this->Session->flash(); ?>
                        <div class="panel-body">
                              <?php echo $this->Form->create("sendmail", array("url" => "index", "method" => "Post")); ?>
                            <p>Search Newsletter by Subject</p>
                            <div class="form-group">
                                 <?php echo $this->Form->text('Sendmail.email', array('maxlength' => '80', 'label' => '', 'div' => false, 'class' => 'form-control fix_widh',  'placeholder' => 'Search By Keyword')); ?>
                            </div>
                           
                            <?php echo $this->Ajax->submit("Search", array('div' => false, 'url' => array('controller' => 'newsletters', 'action' => 'sentMail'), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => 'btn btn-success')); ?>
                            <?php echo $this->Html->link('Clear Filter', array('controller' => 'newsletters', 'action' => 'sentMail'), array('escape' => false, 'class' => 'btn btn-default')); ?>
                                <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </section>
            </div>

            <!-- element start-->
            <div id="listID">
                <?php echo $this->element("admin/newsletters/sentmail"); ?>
            </div>
            <!-- element end-->

        </div>
        <!-- page end-->

    </section>
</section>
<!--main content end-->






