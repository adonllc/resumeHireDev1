<?php

/**
 * @abstract This model class is written for Category Model for this project
 * @Package MOdel
 * @category Model
 * @author Logicspice(info@logicspice.com)
 * @since 1.0.0 2015-07-22
 * @copyright Copyright & Copy ; 2015, Logicspice Consultancy Pvt. Ltd., Jaipur
 *
 */
class Location extends AppModel {

    public $name = 'Location';

    public function isRecordUniqueLocation($category_name = null) {
        // die("sda");
        $resultUser = $this->find('count', array('conditions' => "Location.name = '" . addslashes($category_name) . "'"));
        if ($resultUser) {
            return false;
        } else {
            return true;
        }
    }

    public function in_array_r($needle, $haystack, $strict = false) {


        foreach ($haystack as $item) {
            foreach ($item as $items) {

                foreach ($items as $k) {


                    if (($strict ? $k === $needle : $k == $needle) || (is_array($k) && $this->in_array_r($needle, $k, $strict))) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
    
    function checkLocationWord($data) {
        $msgString = '';
        $swearWord = $this->find('all');
        foreach ($swearWord as $word) {
            if (strpos(strtolower(trim($data)), strtolower($word['Location']['name'])) !== false) {
                $msgString = 'Unsuitable word "' . $word['Location']['name'] . '" not permitted.<br>';
                break;
            }
        }

        if ($msgString != '') {
            return $msgString;
        } else {
            return '';
        }
    }
    
    public function getpreferredlocation($locationsId=null) {
        if($locationsId){
            $locationsIdArray = explode(',', $locationsId);
            $locationList = $this->find('list', array('conditions'=>array('id'=>$locationsIdArray), 'fields'=>array('id', 'name')));
        }
        return implode(', ', $locationList);
    }
    public function getpreferredSkills($skillsId=null) {
        if($skillsId){
            $skillsIdArray = explode(',', $skillsId);
            $locationList = Classregistry::init('Skill')->find('list', array('conditions'=>array('id'=>$skillsIdArray), 'fields'=>array('id', 'name')));
        }
        return implode(', ', $locationList);
    }

}

?>