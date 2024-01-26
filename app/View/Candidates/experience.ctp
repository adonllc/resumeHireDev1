<?php echo $this->Html->script('front/jquery.maskedinput.js'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#canExp").validate();

        $(function(){
            $("#ExperienceFdate").mask("99/9999");
            $("#ExperienceTdate").mask("99/9999");
        });
    });
</script>

<div class="my_accnt">
    <?php echo $this->element('user_menu'); ?>
    <div class="account_cntn">
        <div class="wrapper">
            <div class="my_acc">
                <?php echo $this->element('left_menu_candidate'); ?>
                <div class="col-xs-12 col-sm-3 col-md-9 col-lg-9">
                    <div class="info_dv">
                        <div class="heads">Manage Experience Details</div>
                        <div class="information_cntn">
                            <?php echo $this->element('session_msg'); ?>    
                            <div class="manage_cet">
                                <?php echo $this->Form->create(null, array("method" => "Post",'class' => 'form-inline', 'enctype' => 'multipart/form-data',  'id' => 'canExp')); ?>
                                 
                                <div class="panel-body">
                                        <div class="cc_name"><?php echo $this->Form->text('Experience.company_name', array('class' => "form-control required",'placeholder'=>'Company Name')) ?> </div>
                                        <div class="cc_fdate"><?php echo $this->Form->text('Experience.fdate', array('class' => "form-control required",'placeholder'=>'From(MM/YR)')) ?> </div>
                                        <div class="cc_fdate"><?php echo $this->Form->text('Experience.tdate', array('class' => "form-control required",'placeholder'=>'Until (MM/YR)')) ?> </div>
                                        <div class="cc_role"><?php echo $this->Form->text('Experience.job_role', array('class' => "form-control required",'placeholder'=>'Job Role')) ?> </div>
                                   </div>
                                <?php 
                                if(isset($this->data['Experience']['id']) && $this->data['Experience']['id'] !=''){
                                    echo $this->Form->hidden('Experience.id');
                                    echo $this->Form->submit('Update', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success'));
                                }else{
                                   echo $this->Form->submit('Add', array('size' => '30', 'label' => '', 'div' => false, 'class' => 'btn btn-success')); 
                                }
                                 ?>
                                <?php echo $this->Html->link('Cancel', array('controller' => 'candidates', 'action' => 'myaccount', ''), array('escape' => false, 'class' => 'btn btn-danger')); ?>
                            <?php echo $this->Form->end(); ?>
                            </div>
                            <div class="manage_cet">
                                <div class="job_scroll">
                                <div class="job_content" id='listID'>
                                
                                    <?php echo $this->element("candidates/experience"); ?>
                                </div>
                                
                                </div>
                                
                            </div>
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




