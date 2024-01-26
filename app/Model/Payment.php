<?php

class Payment extends AppModel {

    public $name = 'Payment';
    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'fields'=>array('id','first_name','last_name','company_name','user_type','email_address','full_name','slug')
        ),
        'Job' => array(
            'className' => 'Job',
            'foreignKey' => 'job_id',
            'fields'=>array('id','title','promo_code','payment_type')
        ),
        'Plan' => array(
            'className' => 'Plan',
            'foreignKey' => 'plan_id',
        )
    );
    
    
}

?>