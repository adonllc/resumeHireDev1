<?php if (isset($specilyList) && !empty($specilyList)) { ?>
    <div class="form_list_education">
        <label class="lable-acc"><?php echo __d('user', 'Specialization', true);?> <span class="star_red">*</span></label>
        <div class="form_input_education qualification-select">
            <span >
             <?php echo $this->Form->input('Education.' . $cc . '.' . $specily . '_specialization_id', array('type' => 'select', 'options' => $specilyList, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select Specialization')) ?>
            </span>
        </div>
    </div>
<?php } ?>