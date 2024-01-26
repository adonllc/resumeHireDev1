<?php echo $this->Html->script('jquery/ui/jquery.ui.core.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.widget.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.position.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.datepicker.js'); ?>
<?php echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css'); ?>
<script>
    $(function() {
        $("#searchByDateFrom").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'dd-mm-yy',
            numberOfMonths: 1,
            //minDate: 'mm-dd-yyyy',
            maxDate:'mm-dd-yyyy',
            changeYear: true,
            onClose: function(selectedDate) {
                if(selectedDate){$("#searchByDateTo").datepicker("option", "minDate", selectedDate);}
            }
        });
        $("#searchByDateTo").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'dd-mm-yy',
            numberOfMonths: 1,
            maxDate:'mm-dd-yyyy',
            changeYear: true,
            onClose: function(selectedDate) {
                if(selectedDate){$("#searchByDateFrom").datepicker("option", "maxDate", selectedDate);}
            }
        });

    });
</script>
<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-user" ></i> Employers » ', array('controller' => 'users', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-list"></i> Employer List', 'javascript:void(0)', array('escape' => false));
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
                        <span class="exlfink">Employer List </span>
                        <?php
                        $adminLId = $this->Session->read('adminid');
                        $checkSubRols = ClassRegistry::init('Admin')->getAdminRolesSub($this->Session->read('adminid'));
                        if(ClassRegistry::init('Admin')->getCheckRolesSub($adminLId, $checkSubRols, 1, 1)){ ?>
                        <span class="exportlink btn btn-success btn-xs pull-right">
                           <?php echo $this->Html->link('<i class="fa" aria-hidden="true"></i> Add Employer ', array('controller' => 'users', 'action' => 'addusers'), array('escape' => false, 'title' => 'Add Employer')); ?>
                        </span>
                        <?php } ?>
                    </header>
                    <div class="row-fluid ">
                        <?php echo $this->Session->flash(); ?>
                        <div class="panel-body">
                             <?php echo $this->Form->create("User", array("url" => "index", "method" => "Post",'class' => 'form-inline')); ?>
                            <p>Search employer by typing Company name, email or Created date</p>
                            <div class="form-group">
                                <?php echo $this->Form->text('User.userName', array('maxlength' => '80', 'label' => '', 'div' => false, 'class' => 'form-control fix_widh', 'placeholder' => 'Search By Keyword')); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->input('User.searchByDateFrom', array('type' => 'text', 'id' => 'searchByDateFrom', 'label' => '', 'div' => false, 'class' => "form-control fix_widh", 'placeholder' => 'From','readonly')); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->input('User.searchByDateTo', array('type' => 'text', 'id' => 'searchByDateTo', 'label' => '', 'div' => false, 'class' => "form-control fix_widh", 'placeholder' => 'To','readonly')); ?>
                            </div>
                            <?php echo $this->Ajax->submit("Search", array('div' => false, 'url' => array('controller' => 'users', 'action' => 'index'), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => 'btn btn-success')); ?>
                            <?php echo $this->Html->link('Clear Filter', array('controller' => 'users', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-default')); ?>
                                <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </section>
            </div>

            <!-- element start-->
            <div id="listID">
                <?php echo $this->element("admin/users/index"); ?>
            </div>
            <!-- element end-->

        </div>
        <!-- page end-->

    </section>
</section>
<!--main content end-->
