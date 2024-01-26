<?php

/**
 * @abstract This model class is written for User Model for this project
 * @Package MOdel
 * @category Model
 * @author Logicspice(info@logicspice.com)
 * @since 1.0.0 15-Mar-12
 * @copyright Copyright & Copy ; 2012, Logicspice Consultancy Pvt. Ltd., Jaipur
 *
 */
class Company extends AppModel {

    public $name = 'Company';
    var $belongsTo = array(
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country',
            'dependent' => true
        )
    );

    function isRecordUniquecompany($company_name = null) {

        $resultCompany = $this->find('count', array('conditions' => "Company.company_name = '" . addslashes($company_name) . "'"));

        if ($resultCompany) {
            return false;
        } else {
            return true;
        }
    }

}

?>