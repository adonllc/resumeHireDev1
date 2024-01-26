<div class="form_lst">
    <label>Suburb <span class="star_red">*</span></label>
    <span class="rltv" ><?php echo $this->Form->input($model.'.city_id', array('type' => 'select', 'options' => $cityList, 'label' => false, 'div' => false, 'class' => "required", 'empty' => 'Select City')) ?></span>
</div>
<div class="form_lst">
    <label>State <span class="star_red">*</span></label>
    <span class="rltv"><?php echo $this->Form->input($model.'.state_id', array('type' => 'select', 'options' => $stateList, 'label' => false, 'div' => false, 'class' => "required", 'empty' => 'Select State')) ?></span>
</div>