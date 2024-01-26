<?php
pr($state_list);
echo $this->Form->select($modal.'.state_id', $stateList, array('empty' => 'Select State', 'label' => '', 'div' => false, 'class' => "form-control " . $required)); ?>