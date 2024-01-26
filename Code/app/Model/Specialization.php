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
class Specialization extends AppModel {

    public $name = 'Specialization';

    /* --------------------------- */
    /* --get specialization list-- */
    /* --------------------------- */

    function getSpecializationList($id = null) {

        $specializations = $this->find('list', array(
            'conditions' => array(
                'Specialization.status' => '1',
                'Specialization.id' => $id
            ),
            'fields' => array(
                'Specialization.id',
                'Specialization.name'
            ),
            'order' => 'Specialization.name asc')
        );

        return $specializations;
    }

    /* ----------------------------------------- */
    /* --get specialization list by courser id-- */
    /* ----------------------------------------- */

    function getSpecializationListByCourseId($id = null) {

        $conditions = array(
            'Specialization.status' => '1'
        );

        if (!empty($id)) {
            $conditions[] = array('Specialization.course_id' => $id);
        }

        $specializations = $this->find('list', array(
            'conditions' => $conditions,
            'fields' => array(
                'Specialization.id',
                'Specialization.name'
            ),
            'order' => 'Specialization.name asc'
                )
        );

        return $specializations;
    }

    /* ----------------------- */
    /* --is record is Unique-- */
    /* ----------------------- */

    function isRecordUniqueSpecialization($specialization_name = null, $courseId) {
        $resultSpecialization = $this->find('count', array('conditions' => array('Specialization.name' => trim($specialization_name), 'Specialization.course_id' => trim($courseId))));
        if ($resultSpecialization) {
            return false;
        } else {
            return true;
        }
    }

}

?>