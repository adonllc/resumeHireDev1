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
class AlertLocation extends AppModel {

    public $name = 'AlertLocation';
    var $belongsTo = array(
        'Alert' => array(
            'className' => 'Alert',
            'conditions' => 'Alert.id = AlertLocation.alert_id',
            'foreignKey' => '',
            'dependent' => true
        ),
        'User' => array(
            'className' => 'User',
            'conditions' => 'User.id = Alert.user_id',
            'foreignKey' => '',
            'dependent' => true
        )
    );

    function isAlertLocationUnique($alert_id = null, $location_id = null) {
        $result = $this->find('count', array('conditions' =>
            array("AlertLocation.alert_id" => $alert_id, "AlertLocation.location" => $location_id)
        ));
        if ($result) {
            return false;
        } else {
            return true;
        }
    }

    public function getUsersToAlert($jobId = null) {
        $users = array();
        if (!empty($jobId)) {
            $jobDetail = ClassRegistry::init('Job')->find('first', array('conditions' => array('Job.id' => $jobId)));
            //echo"<pre>"; print_r($jobDetail); exit;
            if (!empty($jobDetail)) {
                $condition = array(
                    //'Alert.location' => $jobDetail['Job']['job_city'],
                    "(Alert.location LIKE '%".trim($jobDetail['Job']['job_city'])."%')",
                    "User.id > 0",
                    'Alert.designation' => $jobDetail['Job']['designation'],
                    'Alert.status' => 1,
                );
//                echo '<pre>';
//                print_r($condition);
                $users = ClassRegistry::init('Alert')->find('all', array('conditions' => $condition, 'fields' => array('DISTINCT(User.id), User.email_address')));
//                print_r($users);exit;
            }
        }
        return $users;
    }

}

?>