
<div id="dynamic<?php echo $cc; ?>" class="dynamiccc">
    <span class="colify-title"><?php echo __d('user', 'Add another Professional Registration', true); ?></span> 
    
    <div class="form_list_education">
        <label class="lable-acc"><?php echo __d('user', 'Name of Professional Governing Body', true); ?> <span class="star_red">*</span></label>
         <div class="form_input_education">
            <?php echo $this->Form->text('Professional.' . $cc . '.registration', array('label' => false, 'div' => false, 'class' => "form-control required", 'placeholder' => __d('user', 'Name of Professional Governing Body', true))) ?>
         </div>
    </div>
   
    <div class="wewa">
        <div class="wewain">
            <?php echo $this->Html->link('<i class="fa fa-trash"></i>' . __d('user', 'Remove', true), 'javascript:removeCC("' . $cc . '");', array('confirm' => 'Are you sure you want to delete this row ?', 'escape' => false, 'rel' => 'nofollow')); ?>
        </div>
    </div>


</div>