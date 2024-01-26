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
class PostCode extends AppModel {

    public $name = 'PostCode';
 
     function postCodeList() {
        $resultCompany = $this->find('list', array('fields' => array('PostCode.post_code'), 'conditions' => "PostCode.status = '1'", 'order' => array('PostCode.post_code' => 'ASC')));
        if ($resultCompany)
            return '"' . implode('","', $resultCompany) . '"';
        else
            return;
    }


}

?>