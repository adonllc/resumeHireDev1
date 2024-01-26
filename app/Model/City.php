<?php

/**
 * @abstract This model class is written for City Model for this project
 * @Package MOdel
 * @category Model
 * @author Logicspice(info@logicspice.com)
 * @since 1.0.0 2015-07-21
 * @copyright Copyright & Copy ; 2015, Logicspice Consultancy Pvt. Ltd., Jaipur
 *
 */
class City extends AppModel {

    public $name = 'City';
 
    function getCityList($post_id = null) {    
        
        $citys = $this->find('list', array(
            'conditions' => array(
                'City.status' => '1',
                'City.post_code_id' => $post_id
            ),
            'fields' => array(
                'City.id',
                'City.city_name'
            ),
            'order' => 'City.city_name asc')
        );

        return $citys;
    }
    
           
     

    
    function isRecordUniqueCity($city_name = null,$stateId) {
        $resultcity = $this->find('count', array('conditions' => array('City.city_name' => trim($city_name),'City.state_id' => trim($stateId))));
        if ($resultcity) {
            return false;
        } else {
            return true;
        }
    }
    
   


}

?>