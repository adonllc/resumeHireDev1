<?php

class JobNotification extends AppModel {

    public $name = 'JobNotification';
    var $belongsTo = array(
        'Job' => array(
            'className' => 'Job',
            'foreignKey' => 'job_id',
            'fields'=>array('id','title','slug','address','state_id','city_id','postal_code')
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'fields'=>array('id','email_address','first_name','last_name')
        ),
        'State' => array(
            'className' => 'State',
            'conditions' => 'State.id = Job.state_id',
            'foreignKey' => '',
            'dependent' => true,
            'fields'=>array('id','state_name')
        ),
        'City' => array(
            'className' => 'City',
            'conditions' => 'City.id = Job.city_id',
            'foreignKey' => '',
            'dependent' => true,
            'fields'=>array('id','city_name')
        )
//       
    );
    
    public function isRecordUniqueJob($title = null,$userId) {
        $resultJob = $this->find('count', array('conditions' =>"Job.title = '" . addslashes($title) . "' AND Job.user_id = '".$userId."'"));
        if ($resultJob) {
            return false;
        } else {
            return true;
        }
    }
    
}

?>