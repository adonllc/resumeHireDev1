<?php

class JobApply extends AppModel {

    public $name = 'JobApply';
    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'Job' => array(
            'className' => 'Job',
            'foreignKey' => 'job_id'
        ),
        'Category' => array(
            'className' => 'Category',
            'conditions' => 'Category.id = Job.category_id',
            'foreignKey' => '',
            'dependent' => true
        ),
        'CoverLetter' => array(
            'className' => 'CoverLetter',
            'foreignKey' => 'cover_letter_id'
        )
    );

    function isUniqueRecord($user_id = null, $job_id = null) {
        $resultCode = $this->find('count', array('conditions' => "JobApply.job_id = '" . addslashes($job_id) . "'"));
        if ($resultCode) {
            return false;
        } else {
            return true;
        }
    }

    function getTotalCandidate($job_id = null) {
        $resultCode = $this->find('count', array('conditions' => "JobApply.job_id = '" . $job_id . "' AND User.id !='' "));
        return $resultCode;
    }

    function getStatusCount($job_id = null, $status = null) {
        $resultCode = $this->find('count', array('conditions' => "JobApply.job_id = '" . $job_id . "' AND JobApply.apply_status = '" . $status . "'"));
        return $resultCode;
    }

    function getNewCount($job_id = null) {
        $resultCode = $this->find('count', array('conditions' => "JobApply.job_id = '" . $job_id . "' AND JobApply.new_status = '1'"));
        return $resultCode;
    }

    public function updateNewStatus($id = null) {
        $this->updateAll(array('new_status' => '0'), array('JobApply.id' => $id));
    }

}

?>
