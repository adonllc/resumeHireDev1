
<?php
if (empty($specilyList)) {
    $specilyList = NULL;
}
if (empty($specily)) {
    $specily = NULL;
}
if (empty($viewList)) {
    $viewList = NULL;
}

if ($viewList != 1 && $viewList == '') {
    ?>
    <div id="spical<?php echo $cc ?>">
        <div class="form_lst">
            <label>Specialization <span class="star_red">*</span></label>
            <span class="rltv"><?php echo $this->Form->input('Education.' . $cc . '.' . $specily . '_specialization_id', array('type' => 'select', 'options' => $specilyList, 'label' => false, 'div' => false, 'class' => "required", 'empty' => 'Select Specialization')) ?></span>
        </div>
    </div>
    <div class="form_lst">
        <label>University/Institute <span class="star_red">*</span></label>
        <span class="rltv"><?php echo $this->Form->text('Education.' . $cc . '.' . $specily . '_university', array('label' => false, 'div' => false, 'class' => "required")) ?></span>
    </div>
    <?php //$year = range(date("Y"),1950); ?>
    <div class="form_lst">
        <label>Passed in <span class="star_red">*</span></label>
        <span class="rltv"><?php echo $this->Form->input('Education.' . $cc . '.' . $specily . '_year', array('type' => 'select', 'options' => array_combine(range(date("Y"), 1950), range(date("Y"), 1950)), 'label' => false, 'div' => false, 'class' => "required in_year", 'empty' => 'Select Year')) ?></span>
    </div>
<?php } ?>
<script>

    $(document).ready(function () {
        document.getElementById("specilyListBasic<?php echo $cc; ?>").style.display = "";
    });
</script>
