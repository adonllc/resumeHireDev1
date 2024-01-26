<?php

/**
 * @abstract This model class is written for User Model for this project
 * @Package MOdel
 * @category Model
 * @author Logicspice(info@logicspice.com)
 * @since 1.0.0 15-Mar-12
 * @copyright Copyright & Copy ; 2012, Logicspice Consultancy Pvt. Ltd., Jaipur
 *
 */
class User extends AppModel {

    public $name = 'User';
    // public $recursive = 2;
    var $virtualFields = array(
        'full_name' => 'CONCAT(User.first_name, " ", User.last_name)'
    );
    var $belongsTo = array(
        /* 'Country' => array(
          'className' => 'Country',
          'foreignKey' => 'country_id'
          ),
          'State' => array(
          'className' => 'State',
          'foreignKey' => 'state_id'
          ),
          'City' => array(
          'className' => 'City',
          'foreignKey' => 'city_id'
          ),
          'Industry' => array(
          'className' => 'Industry',
          'foreignKey' => 'industry'
          ),
          'CourseBasic' => array(
          'className' => 'Course',
          'foreignKey' => 'basic_course_id'
          ),
          'SpecializationBasic' => array(
          'className' => 'Specialization',
          'foreignKey' => 'basic_specialization_id'
          ), */
        'Location' => array(
            'className' => 'Location',
            'foreignKey' => 'location',
            'fields' => array('id', 'name')
        ),
       
    );

    function checkEmail($email_address = null) {

        if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            return true;
        }
    }

    function isRecordUniqueemail($email_address = null) {

        $resultUser = $this->find('count', array('conditions' => "User.email_address = '" . addslashes($email_address) . "'"));

        if ($resultUser) {
            return false;
        } else {
            return true;
        }
    }

    function isRecordUniqueABN($abn = null) {

        $resultUser = $this->find('count', array('conditions' => "User.abn = '" . addslashes($abn) . "'"));

        if ($resultUser) {
            return false;
        } else {
            return true;
        }
    }

    function isRecordUniqueOperatoremail($email_address = null) {

        $resultUser = $this->find('count', array('conditions' => "User.email_address = '" . addslashes($email_address) . "' AND User.user_type = 'Operator'"));

        if ($resultUser) {
            return false;
        } else {
            return true;
        }
    }

    public function getUserDetails($user_id, $user_type) {
        if ($user_id != "") {
            return $this->find("first", array('conditions' => array('User.status' => '1', 'User.user_type' => $user_type, 'User.id' => $user_id), 'recursive' => -1));
        }
    }

    public function getUserId($slug) {
        if ($slug != "") {
            $result = $this->field('id', array('User.slug' => $slug));
            return $result;
        }
        return true;
    }

    public function getRecruiterCompanyName($user_id) {
        if ($user_id != "") {
            $result = $this->field('company_name', array('User.id' => $user_id));
            return $result;
        }
        return true;
    }

    function isRecordUniqueuser($username = null) {

        $resultUser = $this->find('count', array('conditions' => "User.username = '" . addslashes($username) . "'"));

        if ($resultUser) {
            return false;
        } else {
            return true;
        }
    }

    function isRecordUniqueemailEdit($email_address = null, $id = null) {

        $resultUser = $this->find('count', array('conditions' => "User.email_address = '" . addslashes($email_address) . "' AND User.id!='" . $id . "'"));
//echo $resultUser;exit();
        if ($resultUser) {
            return false;
        } else {
            return true;
        }
    }

    public function getUsername($user_id = null) {
        return $this->field('username', array('User.id' => $user_id));
    }

    public function getUserdetail($user_id = null) {
        return $this->find('first', array('conditions' => array('User.id' => $user_id)));
    }

    function checkUserActivationStatus($user_id) {
        $result = $this->find('count', array('conditions' => array('User.id' => $user_id, 'User.status' => '1')));
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getProfilepic($userid) {
        return $this->field('profile_image', array('User.id' => $userid));
    }

    function isRecordUniquecompany($company_name = null) {

        $resultCompany = $this->find('count', array('conditions' => "User.company_name = '" . addslashes($company_name) . "'"));

        if ($resultCompany) {
            return false;
        } else {
            return true;
        }
    }

}

?>