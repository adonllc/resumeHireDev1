<?php

class Job extends AppModel {

    public $name = 'Job';
    var $virtualFields = array(
        'typeAlter' => "CASE Job.type WHEN 'gold' THEN '1' WHEN 'silver' THEN '2' ELSE '3' END"
    );
    var $belongsTo = array(
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id',
            'fields' => array('id', 'name', 'status','slug')
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'fields' => array('id', 'first_name', 'last_name', 'company_name', 'user_type', 'profile_image', 'email_address')
        ),
        'Admin' => array(
            'className' => 'Admin',
            'foreignKey' => 'admin_id',
            'fields' => array('id', 'first_name', 'last_name', 'company_name', 'email')
        ),
        'Skill' => array(
            'className' => 'Skill',
            'foreignKey' => 'skill',
            'fields' => array('id', 'name')
        ),
        'Location' => array(
            'className' => 'Location',
            'foreignKey' => 'location',
            'fields' => array('id', 'name')
        
        ),
        'Designation' => array(
            'className' => 'Skill',
            'foreignKey' => 'designation',
            'fields' => array('id', 'name')
        )
        /*'Subcategory' => array(
            'className' => 'Category',
            'foreignKey' => 'subcategory_id',
            'fields' => array('id', 'name')
        ),
        'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id',
            'fields' => array('id', 'state_name')
        ),
        'City' => array(
            'className' => 'city',
            'foreignKey' => 'city_id',
            'fields' => array('id', 'city_name')
        ),*/
    );

    public function isRecordUniqueJob($title = null, $userId) {
        $resultJob = $this->find('count', array('conditions' => "Job.title = '" . addslashes($title) . "' AND Job.user_id = '" . $userId . "'"));
        if ($resultJob) {
            return false;
        } else {
            return true;
        }
    }

    public function updateJobView($job_id = null) {
       // unset($_SESSION['jobviewarray']);
        if(isset($_SESSION['jobviewarray']) && $_SESSION['jobviewarray'] !=''){
            if(!in_array($job_id, $_SESSION['jobviewarray'])){
                $_SESSION['jobviewarray'][] = $job_id;
                $this->updateAll(array('view_count' => 'view_count + 1'), array('Job.id' => $job_id));
            }
        }else{
           $_SESSION['jobviewarray'][] = $job_id;
           $this->updateAll(array('view_count' => 'view_count + 1'), array('Job.id' => $job_id));
        }
        
        
    }

    public function updateJobSearch($job_id = null) {
        $this->updateAll(array('search_count' => 'search_count + 1'), array('Job.id' => $job_id));
    }

}

?>