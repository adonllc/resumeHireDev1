<?php
class Mail extends AppModel {

    public $name = 'Mail';
//    public $recursive = 0;
    var $belongsTo = array(
        'Job' => array(
            'className' => 'Job',
            'foreignKey' => 'job_id',
//            'dependent' => false
        ),
        'Sender' => array(
            'className' => 'User',
            'foreignKey' => 'to_id',
            'fields' => array('id','slug', 'first_name', 'last_name', 'company_name', 'user_type', 'profile_image', 'email_address')
        ),
        'Company' => array(
            'className' => 'User',
            'foreignKey' => 'from_id',
            'fields' => array('id','slug', 'first_name', 'last_name', 'company_name', 'user_type', 'profile_image', 'email_address')
        )
    );

}

?>