<?php
class Plan extends AppModel {

    public $name = 'Plan';
//    var $belongsTo = array(
//        'Company' => array(
//            'className' => 'Company',
//            'foreignKey' => 'user_id',
//            'dependent' => true
//        )
//    );

    function isRecordUniquePlan($name_name = null,$user_type= null) {
        $resultCompany = $this->find('count', array('conditions' => array('Plan.plan_name'=>addslashes($name_name), 'Plan.planuser' => $user_type)));
        if ($resultCompany) {
            return false;
        } else {
            return true;
        }
    }
    
    public function getcurrentplan($userId=null){
        $date = date('Y-m-d');
        
        //$userPlan = Classregistry::init('UserPlan')->find('first', array('conditions'=>array('UserPlan.user_id'=>$userId,  'UserPlan.start_date <='=>$date), 'order'=>array('UserPlan.invoice_no'=>'DESC')));
        
        $userPlan = Classregistry::init('UserPlan')->find('first', array('conditions'=>array('UserPlan.user_id'=>$userId,'UserPlan.is_expire'=> 0), 'order'=>array('UserPlan.invoice_no'=>'DESC')));
        return $userPlan;
    }
    
    public function getcurrentplanEXP($userId=null){
        $date = date('Y-m-d');
        $userPlan = Classregistry::init('UserPlan')->find('first', array('conditions'=>array('UserPlan.user_id'=>$userId,  'UserPlan.start_date <='=>$date), 'order'=>array('UserPlan.id'=>'DESC')));
        if($userPlan){
            return $userPlan;
        }else{
            return 1;
        }
        
    }
    public function getfutureplan($userId=null){
        $date = date('Y-m-d');
        $userPlan = Classregistry::init('UserPlan')->find('first', array('conditions'=>array('UserPlan.user_id'=>$userId, 'UserPlan.is_expire'=>0, 'UserPlan.start_date >'=>$date)));
        $futurePlan = 0;
        if($userPlan){
            $futurePlan = 1;
        }    
        return $futurePlan;
    }
    
    public function checkPlanFeature($userId=null, $fid=null){
        $myPlan = $this->getcurrentplanEXP($userId);
        if($myPlan == 1){
            $pdata = array('status'=>0,'message'=>'');
            $pdata['status'] = 0;
            $pdata['message'] = __d('user', 'You do not have any plan, please purchase any suitable plan to access website functionality.', true);
            return $pdata;
        }
//        echo '<pre>';
//        print_r($myPlan);exit;
        $myPlan = Classregistry::init('UserPlan')->find('first', array('conditions'=>array('UserPlan.user_id'=>$userId,'UserPlan.is_expire'=> 0), 'order'=>array('UserPlan.invoice_no'=>'DESC')));
   

        $features = $myPlan['UserPlan']['features_ids'];
        $user_plan_id = $myPlan['UserPlan']['id'];
        $featuresArray = explode(',', $features);
        $fvalues = json_decode($myPlan['UserPlan']['fvalues'], true);
        $sdate = $myPlan['UserPlan']['start_date'];
        $edate = $myPlan['UserPlan']['end_date'];
        
        $pdata = array('status'=>0,'message'=>'');
         
        $tdaye = date('Y-m-d');
        if($myPlan){
            if($myPlan['UserPlan']['is_expire'] == 1 || $myPlan['UserPlan']['end_date'] < $tdaye){
                $pdata['status'] = 0;
                $pdata['message'] = __d('user', 'Your current plan has been expiered, so please upgrade your plan first and than try to access this functionality.', true);
                return $pdata;
            }
        }
        
        switch ($fid) {
            case 1:
                if(in_array(1, $featuresArray)){
                    $maxJobPost = $fvalues[1];
                    $postJobCount = Classregistry::init('Job')->find('count', array('conditions'=>array('Job.user_id'=>$userId, 'Job.user_plan_id'=>$user_plan_id)));
                    if($postJobCount >= $maxJobPost){
                        $pdata['message'] = __d('user', 'You have already posted maximum number of jobs as per your plan, please upgrade your plan to post more jobs.', true);
                    }else{
                       $pdata['status'] = 1; 
                       $pdata['user_plan_id'] = $myPlan['UserPlan']['id']; 
                    }
                }else{
                    $pdata['message'] = __d('user', 'You can not post new job now, please upgrade your plan to post more jobs.', true);
                }
                break;
            case 2:
                if(in_array(2, $featuresArray)){
                    $maxJobPost = $fvalues[2];
                    $postJobCount = Classregistry::init('Download')->find('count', array('conditions'=>array('Download.user_id'=>$userId, 'Download.user_plan_id'=>$user_plan_id)));
                    if($postJobCount >= $maxJobPost){
                        $pdata['message'] = __d('user', 'You have already download maximum number of resume as per your plan, please upgrade your plan to download this document.', true);
                    }else{
                       $pdata['status'] = 1; 
                       $pdata['user_plan_id'] = $myPlan['UserPlan']['id']; 
                    }
                }else{
                    $pdata['message'] = __d('user', 'You can not download this document, please upgrade your plan to download this document.', true);
                }
                break;
            case 3:
                if(in_array(3, $featuresArray)){
                       $pdata['status'] = 1;
                       $pdata['user_plan_id'] = $myPlan['UserPlan']['id']; 
                }else{
                    $pdata['message'] = __d('user', 'You can not access search candidate feature, please upgrade your plan to access this feature.', true);
                }
                break;
                
            case 4:
//                  print_r($fvalues);exit;
            if(in_array(4, $featuresArray)){
                $maxJobPost = $fvalues[4];
                $postJobCount = Classregistry::init('JobApply')->find('count', array('conditions'=>array('JobApply.user_id'=>$userId, 'JobApply.user_plan_id'=>$user_plan_id)));
                if($postJobCount >= $maxJobPost){
                    $pdata['message'] = __d('user', 'You have already applied maximum number of jobs as per your plan, please upgrade your plan to apply more jobs.', true);
                }else{
                   $pdata['status'] = 1; 
                   $pdata['user_plan_id'] = $myPlan['UserPlan']['id']; 
                }
            }else{
                $pdata['message'] = __d('user', 'You can not apply a job now, please upgrade your plan to apply more jobs.', true);
            }
            break;
        } 
        
        return $pdata;
        echo '<pre>';
        print_r($pdata);
        exit;
    }
    
    
     public function getPlanFeature($userId=null){
       // $myPlan = $this->getcurrentplanEXP($userId);
       
        $date = date('Y-m-d');
        $pdata = array('status'=>0);
         
        $myPlan = Classregistry::init('UserPlan')->find('first', array('conditions'=>array('UserPlan.user_id'=>$userId,'UserPlan.is_expire'=> 0), 'order'=>array('UserPlan.invoice_no'=>'DESC')));
   
   
        if(!empty($myPlan)){
                    $pdata = array('status'=>1);
                    $features = $myPlan['UserPlan']['features_ids'];
                    $user_plan_id = $myPlan['UserPlan']['id'];
                    $featuresArray = explode(',', $features);
                    $fvalues = json_decode($myPlan['UserPlan']['fvalues'], true);
                    $sdate = $myPlan['UserPlan']['start_date'];
                    $edate = $myPlan['UserPlan']['end_date'];
                    $pdate=$myPlan['UserPlan']['created'];
                     $pdata['sdate'] =$sdate;
                      $pdata['edate'] =$edate;
                      
                if(in_array(1, $featuresArray)){
                    $maxJobPost = $fvalues[1];
                    $postJobCount = Classregistry::init('Job')->find('count', array('conditions'=>array('Job.user_id'=>$userId, 'Job.user_plan_id'=>$user_plan_id)));
                    
                    $pdata['maxJobPost'] =$maxJobPost;
                    $pdata['postJobCount'] =$postJobCount;
                    
                    if($maxJobPost > 500){
                         
                        $pdata['availableJobpost'] ='Unlimited';
                    }else{
                        $pdata['availableJobpost'] =$maxJobPost - $postJobCount;
                   
                    }
                    
                  
                }else{
                     $pdata['maxJobPost'] =0;
                    $pdata['postJobCount'] =0;
                    $pdata['availableJobpost'] ='';
                }
                
                
                if(in_array(2, $featuresArray)){
                    $maxDownloadCount = $fvalues[2];
                    $downloadCount = Classregistry::init('Download')->find('count', array('conditions'=>array('Download.user_id'=>$userId, 'Download.user_plan_id'=>$user_plan_id)));
                   
                    $pdata['maxDownloadCount'] =$maxDownloadCount;
                    $pdata['downloadCount'] =$downloadCount;
                    
                    if($maxDownloadCount > 500){
                        $pdata['availableDownloadCount'] ='Unlimited';
                    }else{
                         $pdata['availableDownloadCount'] =$maxDownloadCount - $downloadCount;
                   
                    }
                    
                }else{
                    $pdata['maxDownloadCount'] =$maxDownloadCount;
                    $pdata['downloadCount'] =$downloadCount;
                    $pdata['availableDownloadCount'] ='';
                }
                
                
                if(in_array(3, $featuresArray)){
                     $pdata['searchCandidate'] =1;
                }else{
                    
                    $pdata['searchCandidate'] ='';
                    
                    
                }
                
                if(in_array(4, $featuresArray)){
                    $maxAppliedCount = $fvalues[4];
                    $appliedCount = Classregistry::init('JobApply')->find('count', array('conditions'=>array('JobApply.user_id'=>$userId, 'JobApply.user_plan_id'=>$user_plan_id)));
                    
                    $pdata['maxAppliedCount'] =$maxAppliedCount;
                    $pdata['appliedCount'] =$appliedCount;
                    
                     if($maxAppliedCount > 500){
                          $pdata['availableAppliedCount'] ='Unlimited';
                     }else
                     {
                          $pdata['availableAppliedCount'] =$maxAppliedCount - $appliedCount;
                     }
                   
                        
                   
                }else{
                    
                    $pdata['maxAppliedCount'] =$maxAppliedCount;
                    $pdata['appliedCount'] =$appliedCount;
                    
                    $pdata['availableAppliedCount'] ='';
                }
                
            
                if(in_array(5, $featuresArray)){
                    $maxProfileView = $fvalues[5];
                    
                      $profileViewCount =Classregistry::init('ProfileView')->find("count", array("conditions" => array("ProfileView.emp_id" => $userId,"ProfileView.status" => '1')));
                      //"Date(ProfileView.created) >=" => $pdate
                             
                    $pdata['maxProfileView'] =$maxProfileView;
                    $pdata['profileViewCount'] =$profileViewCount;
                    
                    $cc=$maxProfileView - $profileViewCount;
                    
                     if($maxProfileView > 500){
                         $pdata['availableProfileView'] ='Unlimited';
                     }else{
                         
                              $pdata['availableProfileView'] =$maxProfileView - $profileViewCount;
                      
                     }
                    
                    
                  
                }else{
                     $pdata['maxProfileView'] =0;
                    $pdata['profileViewCount'] =0;
                    $pdata['availableProfileView'] ='2';
                }
                
            
                
                
                
        }

        
        
        
        return $pdata;
        echo '<pre>';
        print_r($pdata);
        exit;
    }
    

}

?>