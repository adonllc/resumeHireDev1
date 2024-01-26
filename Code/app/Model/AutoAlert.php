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
class AutoAlert extends AppModel {

    public $name = 'AutoAlert';
//    var $belongsTo = array(
//        'User' => array(
//            'className' => 'User',
//            'foreignKey' => 'user_id',
//           // 'fields' => array('id', 'first_name', 'last_name', 'company_name', 'user_type', 'profile_image', 'email_address')
//        ),
//    );
    
    public function getUsersToAlert($jobId = null) {

        if (!empty($jobId)) {

            $jobDetail = ClassRegistry::init('Job')->find('first', array('conditions' => array('Job.id' => $jobId)));
            //echo"<pre>"; print_r($jobDetail); exit;
            if (!empty($jobDetail)) {
                $condition = "";
//                $condition = array(
//                    '(AutoAlert.location IN(' . $jobDetail['Job']['location'] . ') )',
//                    'AutoAlert.designation' => $jobDetail['Job']['designation'],
//                    'AutoAlert.keyword LIKE %' => $jobDetail['Job']['designation'].'%',
//                    'AutoAlert.category_id' => $jobDetail['Job']['category_id'],
//                    'AutoAlert.exp' => $jobDetail['Job']['exp'],
//                    'AutoAlert.salary' => $jobDetail['Job']['salary'],
//                    'AutoAlert.skill' => $jobDetail['Job']['skill'],
//                    'AutoAlert.work_type' => $jobDetail['Job']['work_type'],
//                    'AutoAlert.status' =>1
//                    
//                    
//                );
                if(!empty($jobDetail['Job']['location'])){
                    $condition[] = '(AutoAlert.location IN("' . $jobDetail['Job']['location'] . '"))';
                }
                if(!empty($jobDetail['Job']['designation'])){
                    $designation = ClassRegistry::init('Skill')->findById($jobDetail['Job']['designation']);
                    $condition[] = '(FIND_IN_SET("'. $designation['Skill']['name'].'",AutoAlert.designation))';
                }
                if(!empty($jobDetail['Job']['keyword'])){
                    $condition[] = '(AutoAlert.keyword  = ' . $jobDetail['Job']['location'] . ')';
                }
                
                if (isset($jobDetail['Job']['category_id']) && $jobDetail['Job']['category_id'] != '') {
                    $condition[] = "(AutoAlert.category_id = '" . addslashes($jobDetail['Job']['category_id']) . "')";
                }

                if (!empty($jobDetail['Job']['skill'])) {
                        $c[] = '(Skill.id IN ('.$jobDetail['Job']['skill'].'))';
                        $skilss = ClassRegistry::init('Skill')->find('list',array('conditions'=>$c,'fields'=>array('Skill.id','Skill.name')));

                        $condition[] = "(AutoAlert.skill IN ('" . implode('\',\'',$skilss) . "'))";
                }

                if (!empty($jobDetail['Job']['work_type'])) {

                      $condition[] = "(AutoAlert.work_type = '" . addslashes($jobDetail['Job']['work_type']) . "')";

                }
                if ((isset($jobDetail['Job']['salary']) && $jobDetail['Job']['salary'] != '')) {
                     $condition[] = " (AutoAlert.salary = '".$jobDetail['Job']['salary']."')";

                }

                if ((isset($jobDetail['Job']['exp']) && $jobDetail['Job']['exp'] != '')) {

                        $condition[] = " ((AutoAlert.exp = '".$jobDetail['Job']['exp']."') ";

                }
                
        
            //    echo "<pre>"; print_r($condition); //exit;
        
                $users = $this->find('all', array('conditions' => array('OR'=>$condition,'AutoAlert.status'=>1), 'fields' => array('AutoAlert.id, AutoAlert.email_address')));
              //  echo "<pre>"; print_r($users); exit;
                
            }
        }
        return $users;
    }

}

?>