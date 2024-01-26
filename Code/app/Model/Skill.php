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
class Skill extends AppModel {

    public $name = 'Skill';

    public function isRecordUniqueSkill($category_name = null) {
        // die("sda");
        $resultUser = $this->find('count', array('conditions' => "Skill.name = '" . addslashes($category_name) . "'"));
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

    function checkSkillWord($data) {
        $msgString = '';
        $swearWord = $this->find('all');
        foreach ($swearWord as $word) {
            if (strpos(strtolower(trim($data)), strtolower($word['Skill']['name'])) !== false) {
                $msgString = 'Unsuitable word "' . $word['Skill']['name'] . '" not permitted.<br>';
                break;
            }
        }

        if ($msgString != '') {
            return $msgString;
        } else {
            return '';
        }
    }

    function searchKeyword() {

    $resultSkill = ClassRegistry::init('Skill')->find('list', array('fields' => array('Skill.id', 'Skill.name'), 'conditions' => array('status' => 1)));
//echo"<pre>"; print_r($resultSkill);
    $result = '';
    $i=1;
    foreach ($resultSkill as $k=>$v){

        if($i==1){
            $result .=  '{label:"'.$v.'", value:'.$k.'}';
        }
        else{
            $result .= ',{label:"'.$v.'", value:'.$k.'}';
        }
        $i=$i+1;
    }
      return $result;
       
    }
    
    function searchKDesignation() {

    $resultSkill = ClassRegistry::init('Skill')->find('list', array('fields' => array('Skill.id', 'Skill.name'), 'conditions' => array('status' => 1,'type' => 'Designation')));
//echo"<pre>"; print_r($resultSkill);
    $result = '';
    $i=1;
    foreach ($resultSkill as $k=>$v){

        if($i==1){
            $result .=  '{label:"'.$v.'", value:'.$k.'}';
        }
        else{
            $result .= ',{label:"'.$v.'", value:'.$k.'}';
        }
        $i=$i+1;
    }
      return $result;
       
    }

}

?>