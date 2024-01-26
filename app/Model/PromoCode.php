<?php

class PromoCode extends AppModel {

    public $name = 'PromoCode';

    function isUniquePrmoCode($code = null) {
        $resultCode = $this->find('count', array('conditions' => "PromoCode.code = '" . addslashes($code) . "'"));
        if ($resultCode) {
            return false;
        } else {
            return true;
        }
    }





}

?>
