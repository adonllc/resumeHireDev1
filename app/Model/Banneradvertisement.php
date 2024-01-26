<?php

class Banneradvertisement extends AppModel {

    public $name = 'Banneradvertisement';

    function isUniqueBanner($title = null) {
        $resultCode = $this->find('count', array('conditions' => "Banneradvertisement.title = '" . addslashes($title) . "'"));
        if ($resultCode) {
            return false;
        } else {
            return true;
        }
    }





}

?>
