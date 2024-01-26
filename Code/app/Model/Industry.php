<?php

/**
 * @abstract This model class is written for Industry Model for this project
 * @Package MOdel
 * @category Model
 * @author Logicspice(info@logicspice.com)
 * @since 1.0.0 2015-07-22
 * @copyright Copyright & Copy ; 2015, Logicspice Consultancy Pvt. Ltd., Jaipur
 *
 */
class Industry extends AppModel {

    public $name = 'Industry';

    public function isRecordUniqueIndustry($industry_name = null) {
        $resultUser = $this->find('count', array('conditions' => "Industry.name = '" . addslashes($industry_name) . "' "));
        if ($resultUser) {
            return false;
        } else {
            return true;
        }
    }

    public function getIndustryList() {
        $industries = $this->find('list', array('conditions' => array('Industry.status' => 1), 'fields' => array('Industry.id', 'Industry.name')));
        return $industries;
    }

}

?>