<?php

/**
 * @abstract This model class is written for Category Model for this project
 * @Package MOdel
 * @category Model
 * @author Logicspice(info@logicspice.com)
 * @since 1.0.0 2015-07-21
 * @copyright Copyright & Copy ; 2015, Logicspice Consultancy Pvt. Ltd., Jaipur
 *
 */
class Country extends AppModel {

    public $name = 'Country';
 
    function getCountryList() {
        $countries = $this->find('list', array(
            'conditions' => array(
               
                'Country.status' => '1'
            ),
            'fields' => array(
                'Country.id',
                'Country.country_name'
            ),
            'order' => 'Country.country_name asc')
        );

        return $countries;
    }

    
    function isRecordUniqueCountry($country_name = null) {
        $resultcountry = $this->find('count', array('conditions' => array('Country.country_name' => trim($country_name))));
        if ($resultcountry) {
            return false;
        } else {
            return true;
        }
    }


}

?>