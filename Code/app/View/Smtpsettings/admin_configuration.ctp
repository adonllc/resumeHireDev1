<script type="text/javascript">
    $(document).ready(function () {
        $("#adminSettings").validate();
    });
    
    
    function showhmtpoption(){
        if($('#SmtpsettingIsSmtp').val() == 1){
            $('#smtpsetting').show();
        }else{
           $('#smtpsetting').hide(); 
        }
    }
</script>
<?php
echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css');
?>

<?php
$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-cog" ></i> SMTP Settings » ', 'javascript(0)', array('escape' => false));
?>
<?php echo $this->Form->create(null, array('method' => 'POST', 'name' => 'siteSettings', 'id' => 'adminSettings')); ?>
<?php
//echo"<pre>"; print_r($this->data);
?>
<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->


            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">SMTP Settings</h4>
                <?php echo $this->Session->flash(); ?>

                <section class="panel">
                    <header class="panel-heading">
                        SMTP Setting Details:
                    </header>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Send Email Using <div class="required_field">*</div></label>
                            <div class="col-sm-9" >

                                <?php
                                $option = array(
                                    '0' => 'Normal Email',
                                    '1' => 'SMTP Configuration',
                                );

                                echo $this->Form->input('Smtpsetting.is_smtp', array('options' => $option, 'type' => 'select', 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => false, 'onchange'=>'showhmtpoption();'));
                                ?>    
                            </div>
                        </div>
                        <?php
                         $display = 'none';
                         if(isset($this->data['Smtpsetting']['is_smtp']) && $this->data['Smtpsetting']['is_smtp'] == 1){
                             $display = 'block';
                         }
                        ?>
                        <div id="smtpsetting" style="display: <?php echo $display;?>">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">SMTP Host Name <div class="required_field">*</div></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->text('Smtpsetting.smtp_host', array('label' => '', 'div' => false, 'class' => "form-control required")); ?>    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">SMTP Username <div class="required_field">*</div></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->text('Smtpsetting.smtp_username', array('label' => '', 'div' => false, 'class' => "form-control required")); ?>    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">SMTP Password <div class="required_field">*</div></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->text('Smtpsetting.smtp_password', array('label' => '', 'div' => false, 'class' => "form-control required")); ?>    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">SMTP Port Number <div class="required_field">*</div></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->text('Smtpsetting.smtp_port', array('label' => '', 'div' => false, 'class' => "form-control required")); ?>    
                                    <span class="help_text">Example: 465, 25, 587, 2525 etc, Please check your SMTP detail</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">SMTP Timeout <div class="required_field">*</div></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->text('Smtpsetting.smtp_timeout', array('label' => '', 'div' => false, 'class' => "form-control required")); ?>    
                                    <span class="help_text">Example: 30, 50</span>
                                </div>
                            </div>
                    </div> 
                            <div class="form-group">
                               
                                <label class="col-sm-3 control-label"> <div class="required_field"></div></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
                                    <?php echo $this->Html->link('Cancel', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false, 'class' => 'btn btn-danger')); ?>
                                </div>
                            </div>
                       
                    </div>
                </section>
            </div>
        </div>
    </section>
</section>

<?php echo $this->Form->end(); ?>
