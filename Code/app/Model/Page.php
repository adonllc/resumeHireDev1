<?php

class Page extends AppModel {

    var $name = 'Page';

    function isRecordUniquepage($name = null) {
        $conn = "Page.static_page_title = '" . addslashes($name) . "'";
        $result = $this->find('count', array('conditions' => $conn));
        if ($result) {
            return false;
        } else {
            return true;
        }
    }

}

?>