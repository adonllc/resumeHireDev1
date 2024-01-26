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
$this->Html->addCrumb('<i class="fa fa-list" ></i> Sub Admin List', 'javascript:void(0)', array('escape' => false));
//$this->Html->addCrumb('<i class="fa fa-list"></i> List', 'javascript:void(0)', array('escape' => false));
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
                        <span class="exlfink">Sub-Admin List </span>
                        <span class="exportlink btn btn-success btn-xs pull-right">
                            <?php echo $this->Html->link('<i class="fa fa-user-plus" aria-hidden="true"></i> Add Sub Admin ', array('controller' => 'admins', 'action' => 'addsubadmin'), array('escape' => false, 'title' => 'Add Sub Admin')); ?>
                        </span>
                    </header>
                    <div class="row-fluid ">
                        <?php echo $this->Session->flash(); ?>
                        <div class="panel-body">
                             <?php echo $this->Form->create("Admin", array("url" => "index", "method" => "Post",'class' => 'form-inline')); ?>
                            <p>Search Sub Admin by typing first name, last name,  username, email</p>
                            <div class="form-group">
                                <?php echo $this->Form->text('Admin.name', array('maxlength' => '80', 'label' => '', 'div' => false, 'class' => 'form-control fix_widh', 'placeholder' => 'Search By Keyword')); ?>
                            </div>
                           <?php /* <div class="form-group">
                                <?php echo $this->Form->input('User.searchByDateFrom', array('type' => 'text', 'id' => 'searchByDateFrom', 'label' => '', 'div' => false, 'class' => "form-control fix_widh", 'value' => $_SESSION['searchByDateFrom'], 'placeholder' => 'From','readonly')); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->input('User.searchByDateTo', array('type' => 'text', 'id' => 'searchByDateTo', 'label' => '', 'div' => false, 'class' => "form-control fix_widh", 'value' => $_SESSION['searchByDateTo'], 'placeholder' => 'To','readonly')); ?>
                            </div> */?>
                            <?php echo $this->Ajax->submit("Search", array('div' => false, 'url' => array('controller' => 'admins', 'action' => 'manage'), 'update' => 'listID', 'indicator' => 'loaderID', 'class' => 'btn btn-success')); ?>
                            <?php echo $this->Html->link('Clear Filter', array('controller' => 'admins', 'action' => 'manage'), array('escape' => false, 'class' => 'btn btn-default')); ?>
                                <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </section>
            </div>

            <!-- element start-->
            <div id="listID">
                <?php echo $this->element("admin/admin/manage"); ?>
            </div>
            <!-- element end-->

        </div>
        <!-- page end-->

    </section>
</section>
<!--main content end-->
