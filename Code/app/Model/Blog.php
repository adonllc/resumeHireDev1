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
class Blog extends AppModel {

    public $name = 'Blog';

    function isRecordUniquetitle($title = null) {

        $resultBlog = $this->find('count', array('conditions' => "Blog.title = '" . addslashes($title) . "'"));

        if ($resultBlog) {
            return false;
        } else {
            return true;
        }
    }


    

}

?>