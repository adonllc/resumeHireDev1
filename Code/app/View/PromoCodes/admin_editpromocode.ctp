<?php echo $this->Html->script('jquery/ui/jquery.ui.core.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.widget.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.position.js'); ?>
<?php echo $this->Html->script('jquery/ui/jquery.ui.datepicker.js'); ?>
<?php echo $this->Html->css('front/themes/ui-lightness/jquery.ui.all.css'); ?>
<script>
    $(function() {
        $("#PromoCodeExpiryDate").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            minDate: 'mm-dd-yyyy',
            //maxDate:'mm-dd-yyyy',
            yearRange: "c-60:c+20",
            changeYear: true,
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
         $.validator.addMethod("contact", function(value, element) {
            return  this.optional(element) || (/^[0-9+]+$/.test(value));
        }, "Contact Number is not valid.");
         $.validator.addMethod("validname", function(value, element) {
            return this.optional(element) || /^[a-zA-Z_]+$/.test(value);
        }, "*Note: Special characters, number and spaces are not allowed.");
        $.validator.addMethod("pass", function(value, element) {
            return  this.optional(element) || (/.{8,}/.test(value) && /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,20})/.test(value));
        }, "Password minimum length must be 8 charaters and combination of 1 special character, 1 lowercase character, 1 uppercase character and 1 number.");

      $("#editPromoCode").validate();
       
    });
    
     function cahangeDiscountType(){
        var value= $('#discount_type').val();
        if(value=='Percent'){
            $('#PromoCodeDiscount').attr('max',100);
        }else{
            $('#PromoCodeDiscount').attr('max','');
        }
    }
    
</script>

<?php

$this->Html->addCrumb('<i class="fa fa-dashboard" ></i> Dashboard » ', array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-gift" ></i> Promo Codes » ', array('controller' => 'promoCodes', 'action' => 'index'), array('escape' => false));
$this->Html->addCrumb('<i class="fa fa-pencil-square-o"></i> Edit Promo Code', 'javascript:void(0)', array('escape' => false));
?>
<?php echo $this->Form->create('PromoCode', array('method' => 'POST', 'name' => 'addpromocode', 'enctype' => 'multipart/form-data', 'id' => 'editPromoCode')); ?>

<section id="main-content" class="site-min-height">
    <section class="wrapper">
        <div class="row">
            <!-- Bread crumb start -->
            <div class="col-lg-12">
                <?php echo $this->Html->getCrumbList(array('id' => 'breadcrumb', 'class' => 'breadcrums')); ?>
            </div>
            <!-- Bread crumb end -->

            <div class="col-lg-12">
                <h4 style="margin-left:15px" class="m-bot15">Edit Promo Code</h4>
                <?php echo $this->Session->flash(); ?>

                <section class="panel">
                    <header class="panel-heading">
                        Promo Code Details:
                    </header>
                    <div class="panel-body">
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Promo Code <div class="required_field">*</div></label>
                            <div class="col-sm-10">
                                <?php echo $this->Form->text('PromoCode.code', array('maxlength' => '10', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required",'readonly'=>true)) ?>    
                               
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Discount Type <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php $option = array('Percent' => 'Percent', 'Price' => 'Price'); ?>
                                <?php echo $this->Form->input('PromoCode.discount_type', array('type' => 'select', 'options' => $option, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select Discount Type', 'id' => 'discount_type', 'onchange' => 'cahangeDiscountType();')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Discount <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php if(isset($this->data['PromoCode']['discount_type'])){
                                    if($this->data['PromoCode']['discount_type']=='Percent'){$max_val='100';}else{$max_val='';}
                                }else{
                                    $max_val='';
                                }
                                    
                                    ?>
                                <?php echo $this->Form->text('PromoCode.discount', array('min' => 0.1, 'max' => $max_val, 'maxlength' => '7', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required number")) ?>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Description <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->textarea('PromoCode.details', array('size' => '25', 'rows' => 5, 'cols' => '50', 'label' => '', 'div' => false, 'class' => "form-control required ")) ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Valid Till <div class="required_field">*</div></label>
                            <div class="col-sm-10" >
                                <?php echo $this->Form->text('PromoCode.expiry_date', array('maxlength' => '20', 'size' => '25', 'label' => '', 'div' => false, 'class' => "form-control required")) ?>    
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
                    <?php  echo $this->Form->hidden('PromoCode.id');?>
                    <?php echo $this->Form->submit('Save', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); ?>
                    <?php echo $this->Html->link('Reset', array('controller' => 'promoCodes', 'action' => 'addpromocode'), array('escape' => false, 'class' => 'btn btn-danger')); ?>
                </div></div></div>
    </section>
</section>

<?php echo $this->Form->end(); ?>

