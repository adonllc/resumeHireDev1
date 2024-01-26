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
class Course extends AppModel {

    public $name = 'Course';

    function getCourseList() {
        $courses = $this->find('list', array(
            'conditions' => array(
                'Course.status' => '1'
            ),
            'fields' => array(
                'Course.id',
                'Course.name'
            ),
            'order' => 'Course.name asc')
        );

        return $courses;
    }

    function isRecordUniqueCourse($course_name = null) {
        $resultcourse = $this->find('count', array('conditions' => array('Course.name' => trim($course_name))));
        if ($resultcourse) {
            return false;
        } else {
            return true;
        }
    }

   

}

?>