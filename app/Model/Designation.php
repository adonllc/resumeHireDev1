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
class Designation extends AppModel {

    public $name = 'Designation';

   
    function searchKDesignation() {

    $resultSkill = ClassRegistry::init('Designation')->find('list', array('fields' => array('Designation.id', 'Designation.name'), 'conditions' => array(),'limit'=>15));
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

?><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

