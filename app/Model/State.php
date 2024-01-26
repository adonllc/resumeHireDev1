<?php

/**
 * @abstract This model class is written for State Model for this project
 * @Package MOdel
 * @category Model
 * @author Logicspice(info@logicspice.com)
 * @since 1.0.0 2015-07-21
 * @copyright Copyright & Copy ; 2015, Logicspice Consultancy Pvt. Ltd., Jaipur
 *
 */
class State extends AppModel {

    public $name = 'State';
 
    function getStateList($id = null) { 
        
        $states = $this->find('list', array(
            'conditions' => array(
                'State.status' => '1',
                'State.id' => $id
            ),
            'fields' => array(
                'State.id',
                'State.state_name'
            ),
            'order' => 'State.state_name asc')
        );

        return $states;
    }

    
    function isRecordUniqueState($state_name = null,$countryId) {
        $resultstate = $this->find('count', array('conditions' => array('State.state_name' => trim($state_name),'State.country_id' => trim($countryId))));
        if ($resultstate) {
            return false;
        } else {
            return true;
        }
    }


}

?>