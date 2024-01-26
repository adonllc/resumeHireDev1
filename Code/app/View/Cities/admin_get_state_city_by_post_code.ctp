<div class="form-group">
    <label class="col-sm-2 col-sm-2 control-label">Suburb <div class="required_field">*</div></label>
    <div class="col-sm-10" id="cityDiv" >
        <?php echo $this->Form->input($model . '.city_id', array('type' => 'select', 'options' => $cityList, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select City')) ?>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 col-sm-2 control-label">State <div class="required_field">*</div></label>
    <div class="col-sm-10" >
        <?php echo $this->Form->input($model . '.state_id', array('type' => 'select', 'options' => $stateList, 'label' => false, 'div' => false, 'class' => "form-control required", 'empty' => 'Select State')) ?>
    </div>
</div>
