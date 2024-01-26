<?php

/**
 * @abstract This model class is written for Category Model for this project
 * @Package MOdel
 * @category Model
 * @author Logicspice(info@logicspice.com)
 * @since 1.0.0 2015-07-21
 * @copyright Copyright & Copy ; 2015, Logicspice Consultancy Pvt. Ltd., Jaipur
 *
 */
class Favorite extends AppModel {

    public $name = 'Favorite';
 
     
    var $belongsTo = array(
        'Candidate' => array(
            'className' => 'User',
            'foreignKey' => 'candidate_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );


    
    


}

?>