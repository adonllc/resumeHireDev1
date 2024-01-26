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
class Swear extends AppModel {

    public $name = 'Swear';

    public function isRecordUniqueSwear($category_name = null) {
        // die("sda");
        $resultUser = $this->find('count', array('conditions' => "Swear.s_word = '" . addslashes($category_name) . "'"));
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
    
    function checkSwearWord($data) {
        $msgString = '';
        $swearWord = $this->find('all');
        foreach ($swearWord as $word) {
              if (preg_match("/".strtolower(($word['Swear']['s_word']))."\b/", strtolower(($data)))) {
//             if (strpos(strtolower(trim($data)), strtolower($word['Swear']['s_word'].' ')) !== false || strpos(strtolower(trim($data)), strtolower(' '.$word['Swear']['s_word'])) !== false) {
                $msgString = 'Unsuitable word "' . $word['Swear']['s_word'] . '" not permitted.<br>';
                break;
            }
        }

        if ($msgString != '') {
            return $msgString;
        } else {
            return '';
        }
    }

}

?>