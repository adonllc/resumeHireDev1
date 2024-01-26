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
class Alert extends AppModel {

    public $name = 'Alert';
    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
           // 'fields' => array('id', 'first_name', 'last_name', 'company_name', 'user_type', 'profile_image', 'email_address')
        ),
    );

}

?>