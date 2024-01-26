<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-globe" ></i> Countries » ', array('controller' => 'countries', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-table" ></i> '.$countryInfo['Country']['country_name'].' » ', array('controller' => 'states', 'action' => 'index',$cslug), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-align-justify"></i> '.$stateInfo['State']['state_name'].' » ', array('controller' => 'states', 'action' => 'index',$cslug), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list"></i> List Cities', 'javascript:void(0)', array('escape' => false));
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
                         <span class="exlfink">City List </span>
                    <span class="exportlink btn btn-success btn-xs pull-right">
                            <i class="fa fa-plus" aria-hidden="true"></i> 
                            <?php echo $this->Html->link('Add City ', array('controller' => 'cities', 'action' => 'addcity',$sslug,$cslug), array('escape' => false, 'title' => 'Add City')); ?>
                        </span>
                    </header>
                    <div class="row-fluid">
                        <?php echo $this->Session->flash(); ?>
                        <div class="panel-body">
                              <?php echo $this->Form->create("City", array("url" => "index", "method" => "Post")); ?>
                            <p>Search City by Name</p>
                            <div class="form-group">
                                <?php echo $this->Form->text('City.name', array('maxlength' => '80', 'label' => '',  'class' => 'form-control','div' => false, 'value' => $name, 'placeholder' => 'Search By Keyword')); ?>
                            </div>
                           
                            <?php echo $this->Ajax->submit("Search", array('div' => false, 'url' => array('controller' => 'cities', 'action' => 'index',$sslug,$cslug), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => 'btn btn-success')); ?>
                            <?php echo $this->Html->link('Clear Filter', array('controller' => 'cities', 'action' => 'index',$sslug,$cslug), array('escape' => false, 'class' => 'btn btn-default')); ?>
                                <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </section>
            </div>

            <!-- element start-->
            <div id="listID">
                <?php echo $this->element("admin/cities/index"); ?>
            </div>
            <!-- element end-->

        </div>
        <!-- page end-->

    </section>
</section>
<!--main content end-->






