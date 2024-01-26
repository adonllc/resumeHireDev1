<?php

class ShortList extends AppModel {

    public $name = 'ShortList';
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
    );

    function isUniqueRecord($user_id = null, $job_id = null) {
        $resultCode = $this->find('count', array('conditions' => "ShortList.job_id = '" . addslashes($job_id) . "'"));
        if ($resultCode) {
            return false;
        } else {
            return true;
        }
    }

}

?>
