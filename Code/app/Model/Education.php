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
class Education extends AppModel {

    public $name = 'Education';

    var $belongsTo = array(
         'Course' => array(
          'className' => 'Course',
          'foreignKey' => 'basic_course_id'
          ),
          'Specialization' => array(
          'className' => 'Specialization',
          'foreignKey' => 'basic_specialization_id'
          ),
        );
   

}

?>