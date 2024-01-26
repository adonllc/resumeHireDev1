<?php
class UserPlan extends AppModel {

    public $name = 'UserPlan';
    var $belongsTo = array(
        'Plan' => array(
            'className' => 'Plan',
            'foreignKey' => 'plan_id',
            'dependent' => true
        ),
        'Payment' => array(
            'className' => 'Payment',
            'foreignKey' => 'payment_id',
            'dependent' => true
        ),'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'dependent' => true
        )
    );

}

?>