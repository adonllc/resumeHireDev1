<?php

class CandidatesController extends AppController {

    public $uses = array('Admin', 'Experience', 'Emailtemplate', 'User','ProfileView', 'Certificate', 'Country', 'State', 'City', 'Favorite', 'Job', 'JobApply', 'CoverLetter', 'Swear', 'PostCode', 'Course', 'Specialization', 'Education', 'Location', 'Skill', 'Plan', 'Download', 'Designation', 'ShortList', 'Alert', 'Pages', 'Setting', 'Professional', 'Mail', 'Payment', 'UserPlan',);
    public $helpers = array('Html', 'Form', 'Fck', 'Javascript', 'Ajax', 'Text', 'Number', 'Js', 'Pdf');
    public $paginate = array('limit' => '20', 'page' => '1', 'order' => array('User.id' => 'DESC'));
    public $components = array('RequestHandler', 'Email', 'Upload', 'PImageTest', 'PImage', 'Captcha', 'Common');
    public $layout = 'admin';

    function beforeFilter() {
        $loggedAdminId = $this->Session->read("adminid");
        if (isset($this->params['admin']) && $this->params['admin'] && !$loggedAdminId) {
            //$this->redirect("/admin/admins/login");
            $returnUrlAdmin = $this->params->url;
            $this->Session->write("returnUrlAdmin", $returnUrlAdmin);
            $this->redirect(array('controller' => 'admins', 'action' => 'login', ''));
        }
    }

    public function admin_index() {
        $this->layout = "admin";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Jobseeker List");

        $this->set('candidate_list', 'active');
        $this->set('default', '2');
        $condition = array('user_type' => 'candidate');
        $separator = array();
        $urlSeparator = array();
        $userName = '';
        $searchByDateFrom = '';
        $searchByDateTo = '';

        if (!empty($this->data)) {

            if (isset($this->data['User']['userName']) && $this->data['User']['userName'] != '') {
                $userName = trim($this->data['User']['userName']);
            }

            if (isset($this->data['User']['searchByDateFrom']) && $this->data['User']['searchByDateFrom'] != '') {
                $searchByDateFrom = trim($this->data['User']['searchByDateFrom']);
            }

            if (isset($this->data['User']['searchByDateTo']) && $this->data['User']['searchByDateTo'] != '') {
                $searchByDateTo = trim($this->data['User']['searchByDateTo']);
            }

            if (isset($this->data['User']['action'])) {
                $idList = $this->data['User']['idList'];
                if ($idList) {
                    if ($this->data['User']['action'] == "activate") {
                        $cnd = array("User.id IN ($idList) ");
                        $this->User->updateAll(array('User.status' => "'1'"), $cnd);
                    } elseif ($this->data['User']['action'] == "deactivate") {
                        $cnd = array("User.id IN ($idList) ");
                        $this->User->updateAll(array('User.status' => "'0'"), $cnd);
                    } elseif ($this->data['User']['action'] == "delete") {
                        $cnd = array("User.id IN ($idList) ");
                        $this->User->deleteAll($cnd);
                    }
                }
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['userName']) && $this->params['named']['userName'] != '') {
                $userName = urldecode(trim($this->params['named']['userName']));
            }
            if (isset($this->params['named']['searchByDateFrom']) && $this->params['named']['searchByDateFrom'] != '') {
                $searchByDateFrom = urldecode(trim($this->params['named']['searchByDateFrom']));
            }
            if (isset($this->params['named']['searchByDateTo']) && $this->params['named']['searchByDateTo'] != '') {
                $searchByDateTo = urldecode(trim($this->params['named']['searchByDateTo']));
            }
        }

        if (isset($userName) && $userName != '') {
            $separator[] = 'userName:' . urlencode($userName);
            $userName = str_replace('_', '\_', $userName);
            $condition[] = " (`User`.`company_name` LIKE '%" . addslashes($userName) . "%' OR `User`.`first_name` LIKE '%" . addslashes($userName) . "%' or concat(`User.first_name`,' ',`User.last_name`) LIKE '%" . addslashes($userName) . "%' or `User`.`email_address` LIKE '%" . addslashes($userName) . "%' or `User`.`last_name` LIKE '%" . addslashes($userName) . "%' ) ";
            $userName = str_replace('\_', '_', $userName);
            $this->set('searchKey', $userName);
        }

        if (isset($searchByDateFrom) && $searchByDateFrom != '') {
            $separator[] = 'searchByDateFrom:' . urlencode($searchByDateFrom);
            $searchByDateFrom = str_replace('_', '\_', $searchByDateFrom);
            $searchByDate_con1 = date('Y-m-d', strtotime($searchByDateFrom));
            $condition[] = " (Date(User.created)>='$searchByDate_con1' ) ";
            $searchByDateFrom = str_replace('\_', '_', $searchByDateFrom);
        }

        if (isset($searchByDateTo) && $searchByDateTo != '') {
            $separator[] = 'searchByDateTo:' . urlencode($searchByDateTo);
            $searchByDateTo = str_replace('_', '\_', $searchByDateTo);
            $searchByDate_con2 = date('Y-m-d', strtotime($searchByDateTo));
            $condition[] = " (Date(User.created)<='$searchByDate_con2' ) ";
            $searchByDateTo = str_replace('\_', '_', $searchByDateTo);
        }

        $order = 'User.id Desc';

        $separator = implode("/", $separator);


        $this->set('searchByDateFrom', $searchByDateFrom);
        $this->set('searchByDateTo', $searchByDateTo);


        $urlSeparator = implode("/", $urlSeparator);
        $this->set('userName', $userName);
        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['User'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('candidates', $this->paginate('User'));
        //pr($condition);


        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/candidates';
            $this->render('index');
        }
    }
    
    
    public function getUserdetail($uslug = null) {
        $this->layout = '';
        if (!empty($uslug)) {
            $user =array();
             $user['status']='0';
             
            
            $euserId = $this->Session->read("user_id");
            $userCheck = $this->User->find("first", array("conditions" => array("User.slug" => $uslug)));
                      
            
            $getviewrecord = $this->ProfileView->find("count", array("conditions" => array("ProfileView.candidate_id" => $userCheck['User']['id'],"ProfileView.emp_id" => $euserId)));
        //   echo '<pre>'; print_r($getviewrecord);
        //     echo '<pre>euserId'; print_r($euserId);
        //     echo '<pre>suserid'; print_r($userCheck['User']['id']);
        //     exit;
                
                
                //$getviewrecord=1;
            if($getviewrecord > 0){
                $user['email']=$userCheck['User']['email_address'];
                $user['contact']=$userCheck['User']['contact'] ? $userCheck['User']['contact'] : 'N/A';
                $user['status']='1';
              
            }else{
                
                $date = date('Y-m-d');
                 $userPlan = Classregistry::init('UserPlan')->find('first', array('conditions'=>array('UserPlan.user_id'=>$euserId), 'order'=>array('UserPlan.invoice_no'=>'DESC')));
              
               // $userPlan = Classregistry::init('UserPlan')->find('first', array('conditions'=>array('UserPlan.id'=>'400', 'UserPlan.user_id'=>$euserId,  'UserPlan.start_date <='=>$date), 'order'=>array('UserPlan.invoice_no'=>'DESC')));
                $features = $userPlan['UserPlan']['features_ids'];
                $featuresArray = explode(',', $features);
                $user['UserPlan id']=$userPlan['UserPlan']['id'];
                         
                 if(in_array(5, $featuresArray)){
    
                      
                        $fvalues = json_decode($userPlan['UserPlan']['fvalues'], true);
                        $maxviews = $fvalues[5];
                        
                        if($maxviews > 0){
                            
                            $pdate=$userPlan['UserPlan']['created'];
                           //$totalviews = $this->ProfileView->find("count", array("conditions" => array("ProfileView.emp_id" => $euserId)));
                          //$totalviews = $this->ProfileView->find("count", array("conditions" => array("ProfileView.emp_id" => $euserId,"Date(ProfileView.created) >=" => $pdate)));
                          $totalviews = $this->ProfileView->find("count", array("conditions" => array("ProfileView.emp_id" => $euserId,"ProfileView.status" => '1')));
                          
                          
                           if($maxviews > $totalviews){
                               //echo 'yes';
                                $user['totalviews']=$totalviews;
                                $user['$pdate']=$pdate;
                                $user['maxviews']=$maxviews;
                                 $user['email']=$userCheck['User']['email_address'];
                                $user['contact']=$userCheck['User']['contact'] ? $userCheck['User']['contact'] : 'N/A';
                                $user['status']='1';
                                
                                $this->request->data['ProfileView']['status'] = 1;
                                $this->request->data['ProfileView']['emp_id'] = $euserId;
                                $this->request->data['ProfileView']['candidate_id'] = $userCheck['User']['id'];
                  
                                 ($this->ProfileView->save($this->data));
                                 

                
                    
                           }
           
                        }
    
                 }
                 //print_r($maxviews);exit;
                
            }

              
              echo json_encode($user);
             exit;
            
        }
    }
    

    public function admin_addcandidates() {

        $this->layout = "admin";
        $this->set('add_candidate', 'active');

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Jobseeker");

        $msgString = '';
        global $extentions;
        global $extentions_doc;

        //$this->set('stateList', '');
        //$this->set('cityList', '');
        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);


        if ($this->data) {

            if (empty($this->data["User"]["first_name"])) {
                $msgString .= "- First name is required field.<br>";
            }

            if (empty($this->data["User"]["last_name"])) {
                $msgString .= "- Last name is required field.<br>";
            }

            /* if (empty($this->data["User"]["location"])) {
              $msgString .="- Location is required field.<br>";
              } */

            /* $this->request->data['User']['country_id'] = 1;
              if (empty($this->data["User"]["country_id"])) {
              $msgString .="- Country is required field.<br>";
              }
              if (empty($this->data["User"]["state_id"])) {
              $msgString .="- State is required field.<br>";
              }
              if (empty($this->data["User"]["city_id"])) {
              $msgString .="- City is required field.<br>";
              } */

            if (empty($this->data["User"]["contact"])) {
                $msgString .= "- Contact number is required field.<br>";
            }


            if ($this->data["User"]["profile_image"]["name"]) {

                $getextention = $this->PImage->getExtension($this->data['User']['profile_image']['name']);
                $extention = strtolower($getextention);

                if ($this->data['User']['profile_image']['size'] > '2097152') {
                    $msgString .= "- Max file size upload is 2MB.<br>";
                } elseif (!in_array($extention, $extentions)) {
                    $msgString .= "- Not Valid Extention.<br>";
                }
            }

            if (trim($this->data["User"]["email_address"]) == '') {
                $msgString .= "- Email is required field.<br>";
            } elseif ($this->User->checkEmail($this->data["User"]["email_address"]) == false) {
                $msgString .= "- Email Not Valid.<br>";
            }
            if ($this->User->isRecordUniqueemail($this->data["User"]["email_address"]) == false) {
                $msgString .= "- Email already exists.<br>";
            }

            if (trim($this->data["User"]["password"]) == '') {
                $msgString .= "- Password is required field.<br>";
            } elseif (strlen($this->data["User"]["password"]) < 8) {
                $msgString .= "- Password must be at least 8 characters.<br>";
            }

            if (trim($this->data["User"]["confirm_password"]) == '') {
                $msgString .= "- Confirm  Password is required field.<br>";
            } else {
                $password = $this->data["User"]["password"];
                $conformpassword = $this->data["User"]["confirm_password"];

                if ($password != $conformpassword) {
                    $msgString .= "- Password and confirm password mismatch.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');

                /* $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['User']['postal_code'])));
                  $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
                  $this->set('stateList', $stateList);
                  $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
                  $this->set('cityList', $cityList); */
            } else {

                $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                $toReplace = "-";
                $this->request->data['User']['profile_image']['name'] = str_replace($specialCharacters, $toReplace, $this->data['User']['profile_image']['name']);
                $this->request->data['User']['profile_image']['name'] = str_replace("&", "and", $this->data['User']['profile_image']['name']);
                if ($this->data['User']['profile_image']['name']) {
                    $imageArray = $this->data['User']['profile_image'];
                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_PROFILE_IMAGE_PATH, "jpg,jpeg,png,gif");
                    list($width, $height, $type, $attr) = getimagesize(UPLOAD_FULL_PROFILE_IMAGE_PATH . '/' . $returnedUploadImageArray[0]);
                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= "- Profile Image file not valid.<br>";
                    } else if ($width < 250 && $height < 250) {
                        @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . '/' . $returnedUploadImageArray[0]);
                        $msgString .= "- Profile Images size must be bigger than  250 X 250 pixels.<br>";
                    } else {
                        copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        list($width) = getimagesize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        if ($width > 650) {
                            $this->PImageTest->resize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_IMAGE_WIDTH, UPLOAD_FULL_PROFILE_IMAGE_HEIGHT, 100);
                        }

                        $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                        $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
                        $profilePic = $returnedUploadImageArray[0];
                        
                        chmod(UPLOAD_FULL_PROFILE_IMAGE_PATH .$returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);

                        if (isset($this->data['User']['old_image']) && $this->data['User']['old_image'] != "") {
                            @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                            @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                            @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                        }
                    }
                } else {
                    $profilePic = '';
                }


                if (isset($msgString) && $msgString != '') {
                    $this->Session->setFlash($msgString, 'error_msg');
                    //$cityList = $this->City->getCityList($this->data['User']['state_id']);
                    // $this->set('cityList', $cityList);
                } else {

                    $this->request->data['User']['profile_image'] = $profilePic;

                    $passwordPlain = $this->data["User"]["password"];
                    $salt = uniqid(mt_rand(), true);
                    $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');

                    $this->request->data['User']['password'] = $new_password;

                    $this->request->data['User']['first_name'] = trim($this->data['User']['first_name']);
                    $this->request->data['User']['last_name'] = trim($this->data['User']['last_name']);
                    $this->request->data['User']['status'] = 1;
                    $this->request->data['User']['activation_status'] = 1;
                    $this->request->data['User']['user_type'] = 'candidate';
                    $this->request->data['User']['slug'] = $this->stringToSlugUnique($this->data['User']['first_name'] . ' ' . $this->data['User']['last_name'], 'User', 'slug');

                    if ($this->User->save($this->data)) {


                        $email = $this->data["User"]["email_address"];
                        $username = $this->data["User"]["first_name"];

                        $this->Email->to = $email;
                        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';
                        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='26'"));
                        //$this->Email->subject = $this->data['Job']['subject'];
                        $toSubArray = array('[!email!]', '[!password!]', '[!username!]', '[!SITE_TITLE!]', '[!subject!]');
                        $fromSubArray = array($email, $passwordPlain, $username, $site_title, $this->data['Job']['subject']);
                        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                        $this->Email->subject = $subjectToSend;



                        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                        $this->Email->from = $site_title . "<" . $mail_from . ">";

                        $toRepArray = array('[!email!]', '[!password!]', '[!username!]', '[!SITE_TITLE!]', '[!subject!]');
                        $fromRepArray = array($email, $passwordPlain, $username, $site_title, $this->data['Job']['subject']);
                        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                        $this->Email->layout = 'default';
                        $this->set('messageToSend', $messageToSend);
                        $this->Email->template = 'email_template';
                        $this->Email->sendAs = 'html';
                        $this->Email->send();

                        $this->Session->setFlash('Jobseeker details saved successfully', 'success_msg');
                        $this->redirect('/admin/candidates/index');
                    }
                }
            }
        }
    }

    public function admin_editcandidates($slug = null) {

        $this->layout = "admin";
        $this->set('candidate_list', 'active');

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Jobseeker");


        $msgString = '';
        global $extentions;
        global $extentions_doc;
        $changedPassword = 0;
        $emailaddress = $this->User->field('email_address', array('slug' => $slug));

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        if ($this->data) {

            if (empty($this->data["User"]["first_name"])) {
                $msgString .= "- First name is required field.<br>";
            }

            if (empty($this->data["User"]["last_name"])) {
                $msgString .= "- Last name is required field.<br>";
            }

            /*  $this->request->data['User']['country_id'] = 1;
              if (empty($this->data["User"]["country_id"])) {
              $msgString .="- Country is required field.<br>";
              }
              if (empty($this->data["User"]["state_id"])) {
              $msgString .="- State is required field.<br>";
              }
              if (empty($this->data["User"]["city_id"])) {
              $msgString .="- City is required field.<br>";
              } */

            /* if (empty($this->data["User"]["location"])) {
              $msgString .="- Location is required field.<br>";
              } */

            if (empty($this->data["User"]["contact"])) {
                $msgString .= "- Contact number is required field.<br>";
            }


            if ($this->data["User"]["profile_image"]["name"]) {

                $getextention = $this->PImage->getExtension($this->data['User']['profile_image']['name']);
                $extention = strtolower($getextention);
                if ($this->data['User']['profile_image']['size'] > '2097152') {
                    $msgString .= "- Max file size upload is 2MB.<br>";
                } elseif (!in_array($extention, $extentions)) {
                    $msgString .= "- Not Valid Extention.<br>";
                }
            }

            if (trim($this->data["User"]["new_password"]) != '') {
                if (strlen($this->data["User"]["new_password"]) < 8) {
                    $msgString .= "- Password must be at least 8 characters.<br>";
                }

                if (trim($this->data["User"]["confirm_password"]) == '') {
                    $msgString .= "- Confirm Password is required field.<br>";
                } else {
                    $password = $this->data["User"]["new_password"];
                    $conformpassword = $this->data["User"]["confirm_password"];

                    if ($password != $conformpassword) {
                        $msgString .= "- New password and confirm password mismatch.<br>";
                    } elseif (crypt($this->data['User']['new_password'], $this->data['User']['old_password']) == $this->data['User']['old_password']) {// Checking the both password matched aur not
                        $msgString .= "- You cannot put old password for the new password!<br>";
                    } else {
                        $changedPassword = 1;
                        $passwordPlain = $this->data["User"]["new_password"];
                        $salt = uniqid(mt_rand(), true);
                        $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');

                        $this->request->data['User']['password'] = $new_password;
                    }
                }
            } elseif (trim($this->data["User"]["confirm_password"]) != '') {
                $msgString .= "- Please enter New Password first.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');

                /* $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['User']['postal_code'])));
                  $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
                  $this->set('stateList', $stateList);
                  $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
                  $this->set('cityList', $cityList); */
            } else {

                if ($this->data['User']['profile_image']['name']) {

                    $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                    $toReplace = "-";
                    $this->request->data['User']['profile_image']['name'] = str_replace($specialCharacters, $toReplace, $this->data['User']['profile_image']['name']);
                    $this->request->data['User']['profile_image']['name'] = str_replace("&", "and", $this->data['User']['profile_image']['name']);

                    $imageArray = $this->data['User']['profile_image'];
                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_PROFILE_IMAGE_PATH, "jpg,jpeg,png,gif");
                    list($width, $height, $type, $attr) = getimagesize(UPLOAD_FULL_PROFILE_IMAGE_PATH . '/' . $returnedUploadImageArray[0]);
                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= "- Profile Image file not valid.<br>";
                    } else if ($width < 250 && $height < 250) {
                        @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . '/' . $returnedUploadImageArray[0]);
                        $msgString .= "- Profile Images size must be bigger than  250 X 250 pixels.<br>";
                    } else {
                        copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        list($width) = getimagesize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        if ($width > 650) {
                            $this->PImageTest->resize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_IMAGE_WIDTH, UPLOAD_FULL_PROFILE_IMAGE_HEIGHT, 100);
                        }

                        $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                        $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
                        $this->request->data['User']['profile_image'] = $returnedUploadImageArray[0];
                        chmod(UPLOAD_FULL_PROFILE_IMAGE_PATH .$returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        
                        if (isset($this->data['User']['old_profile_image']) && $this->data['User']['old_profile_image'] != "") {
                            @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $this->data['User']['old_profile_image']);
                            @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $this->data['User']['old_profile_image']);
                            @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $this->data['User']['old_profile_image']);
                        }
                    }
                } else {
                    $this->request->data['User']['profile_image'] = $this->request->data['User']['old_profile_image'];
                }


                if (isset($msgString) && $msgString != '') {
                    $this->Session->setFlash($msgString, 'error_msg');

                    /* $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['User']['postal_code'])));
                      $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
                      $this->set('stateList', $stateList);
                      $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
                      $this->set('cityList', $cityList); */
                } else {

                    $this->request->data['User']['status'] = $this->request->data['User']['change_status'];
                    $this->request->data['User']['first_name'] = trim($this->data['User']['first_name']);
                    $this->request->data['User']['last_name'] = trim($this->data['User']['last_name']);
                    if ($this->User->save($this->data)) {
                        if ($changedPassword == 1) {

                            $email = $this->data["User"]["email_address"];
                            $name = ucwords($this->data['User']['first_name'] . ' ' . $this->data['User']['last_name']);
                            $username = $this->data["User"]["first_name"];

                            $this->Email->to = $email;
                            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='27'"));
                            //$this->Email->subject = $this->data['Job']['subject'];
                            $toSubArray = array('[!email!]', '[!password!]', '[!username!]', '[!SITE_TITLE!]', '[!subject!]');
                            $fromSubArray = array($email, $passwordPlain, $username, $site_title, $this->data['Job']['subject']);
                            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                            $this->Email->subject = $subjectToSend;

                            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                            $this->Email->from = $site_title . "<" . $mail_from . ">";
                            $toRepArray = array('[!email!]', '[!password!]', '[!username!]', '[!SITE_TITLE!]', '[!subject!]');
                            $fromRepArray = array($email, $passwordPlain, $username, $site_title, $this->data['Job']['subject']);
                            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                            $this->Email->layout = 'default';
                            $this->set('messageToSend', $messageToSend);
                            $this->Email->template = 'email_template';
                            $this->Email->sendAs = 'html';
                            $this->Email->send();
                        }
                        $this->Session->setFlash('Jobseeker details updated successfully', 'success_msg');
                        $this->redirect('/admin/candidates/index');
                    }
                }
            }
        } elseif ($slug != '') {
            $id = $this->User->field('id', array('User.slug' => $slug));
            $this->User->id = $id;
            $this->data = $this->User->read();
            $this->request->data['User']['old_password'] = $this->data['User']['password'];
            $this->request->data['User']['old_profile_image'] = $this->data['User']['profile_image'];
            $this->request->data['User']['old_cv'] = $this->data['User']['cv'];
            /* $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['User']['postal_code'])));
              $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
              $this->set('stateList', $stateList);
              $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
              $this->set('cityList', $cityList); */
        }
    }

    public function admin_download($filename = null) {
        set_time_limit(0);
        $file_path = UPLOAD_CV_PATH . $filename;
        $filename = substr($filename, 6);
        $this->Common->output_file($file_path, $filename);
        exit;
    }

    public function admin_activateuser($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->User->field('id', array('User.slug' => $slug));
            $cnd = array("User.id = $id");
            $this->User->updateAll(array('User.status' => "'1'", 'User.activation_status' => "'1'"), $cnd);
            $this->set('action', '/admin/candidates/deactivateuser/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivateuser($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->User->field('id', array('User.slug' => $slug));
            $cnd = array("User.id = $id");
            $this->User->updateAll(array('User.status' => "'0'"), $cnd);
            $this->set('action', '/admin/candidates/activateuser/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deletecandidates($slug = NULL) {
        $this->set('list_candidates', 'active');
        $this->set('default', '1');
        if ($slug != '') {
            $id = $this->User->field('id', array('User.slug' => $slug));
            $image = $this->User->field('profile_image', array('User.slug' => $slug));
            if ($this->User->delete($id)) {
                @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $image);
                @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $image);
                @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $image);
            }
            $this->Session->setFlash('Candidate details deleted successfully', 'success_msg');
        }
        $this->redirect('/admin/candidates/index');
    }

    public function admin_deleteUserImage($userSlug = null) {

        $this->layout = "";
        if (!empty($userSlug)) {
            $userData = $this->User->findByslug($userSlug);
            $id = $userData['User']['id'];
            $image = $userData['User']['profile_image'];
            $cnd1 = array("User.id = '$id'");
            $this->User->updateAll(array('User.profile_image' => "''"), $cnd1);
            @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $image);
            @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $image);
            @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $image);
            $this->Session->setFlash('Image deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'candidates', 'action' => 'editcandidates', $userSlug));
        }
    }

    public function admin_certificates($cslug) {
        $this->layout = "admin";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Manage Certificates");

        $this->set('candidate_list', 'active');
        $this->set('default', '2');

        global $extentions;
        global $extentions_doc;

        $candidateInfo = $this->User->findBySlug($cslug);
        if (!$candidateInfo) {
            $this->redirect('/admin/certificates/index/');
        }

        $this->set('candidateInfo', $candidateInfo);
        $this->set('cslug', $cslug);
        $userId = $candidateInfo['User']['id'];
        $showOldImages = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userId, 'type' => 'image')));
        $this->set('showOldImages', $showOldImages);
        $showOldDocs = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userId, 'type' => 'doc')));
        $this->set('showOldDocs', $showOldDocs);

        $condition = array('Certificate.user_id' => $candidateInfo['User']['id']);
        if (!empty($this->data)) {



            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {

                if (!empty($this->data["Certificate"]["document"])) {
                    $imagesArr = explode(',', $this->data["Certificate"]["document"]);

                    foreach ($imagesArr as $imageName) {
                        if (!empty($imageName) && file_exists(UPLOAD_TMP_CERTIFICATE_PATH . $imageName)) {
                            copy(UPLOAD_TMP_CERTIFICATE_PATH . $imageName, UPLOAD_CERTIFICATE_PATH . $imageName);
                            $this->Certificate->create();
                            $this->request->data['Certificate']['document'] = $imageName;
                            $this->request->data['Certificate']['user_id'] = $userId;
                            $getextention = $this->PImage->getExtension($imageName);
                            $extention = strtolower($getextention);
                            if (in_array($extention, $extentions)) {
                                $this->request->data['Certificate']['type'] = 'image';
                                $this->request->data['Certificate']['slug'] = 'image-' . $userId . time() . rand(111, 99999);
                            } elseif (in_array($extention, $extentions_doc)) {
                                $this->request->data['Certificate']['type'] = 'doc';
                                $this->request->data['Certificate']['slug'] = 'doc-' . $userId . time() . rand(111, 99999);
                            }
                            $this->Certificate->save($this->data);
                            @unlink(UPLOAD_TMP_CERTIFICATE_PATH . $imageName);
                        }
                    }
                    $this->Session->setFlash('Documents/Certificate uploaded successfully', 'success_msg');
                }
            }
        }
        $showOldImages = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userId, 'type' => 'image')));
        $this->set('showOldImages', $showOldImages);
        $showOldDocs = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userId, 'type' => 'doc')));
        $this->set('showOldDocs', $showOldDocs);
    }

    public function admin_deleteCertificate($slug = NULL, $cslug) {
        if ($slug != '') {
            $id = $this->Certificate->field('id', array('Certificate.slug' => $slug));
            $image = $this->Certificate->field('document', array('Certificate.slug' => $slug));
            if ($this->Certificate->delete($id)) {
                @unlink(UPLOAD_CERTIFICATE_PATH . $image);
            }
            $this->Session->setFlash('Certificate deleted successfully', 'success_msg');
        }
        $this->redirect('/admin/candidates/certificates/' . $cslug);
    }

    public function admin_deleteExperience($slug = NULL, $cslug) {
        if ($slug != '') {
            $id = $this->Experience->field('id', array('Experience.slug' => $slug));
            $this->Experience->delete($id);
            $this->Session->setFlash('Experience deleted successfully', 'success_msg');
        }
        $this->redirect('/admin/candidates/experience/' . $cslug);
    }

    public function admin_experience($cslug, $exslug = null) {
        $this->layout = "admin";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Manage Experiences");
        $this->set('candidate_list', 'active');
        $msgString = '';

        global $extentions;

        $candidateInfo = $this->User->findBySlug($cslug);
        if (!$candidateInfo) {
            $this->redirect('/admin/certificates/index/');
        }

        $this->set('candidateInfo', $candidateInfo);
        $this->set('cslug', $cslug);

        $condition = array('Experience.user_id' => $candidateInfo['User']['id']);
        if (!empty($this->data)) {
            // pr($this->data);
            if (empty($this->data["Experience"]["company_name"])) {
                $msgString .= "- Company name is required field.<br>";
            }
            if (empty($this->data["Experience"]["fdate"])) {
                $msgString .= "- From is required field.<br>";
            } else {
                $fff = strtotime('01-' . str_replace('/', '-', $this->data["Experience"]["fdate"]));
                $fdateA = date('Y-m-d', $fff);
                if ($fdateA == '1970-01-01') {
                    $msgString .= "- Please enter valid From date .<br>";
                } elseif ($fdateA > date('Y-m-d')) {
                    $msgString .= "- From date must be past date .<br>";
                }
            }
            if (empty($this->data["Experience"]["tdate"])) {
                $msgString .= "- Until is required field.<br>";
            } else {
                $ttt = strtotime('01-' . str_replace('/', '-', $this->data["Experience"]["tdate"]));
                $fdateA = date('Y-m-d', $ttt);
                if ($fdateA == '1970-01-01') {
                    $msgString .= "- Please enter valid Until date .<br>";
                } elseif ($fdateA > date('Y-m-d')) {
                    $msgString .= "- Until date must be past date .<br>";
                }
            }

            if ($ttt <= $fff) {
                $msgString .= "- From date must be less then Until date .<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {

                $this->request->data['Experience']['user_id'] = $candidateInfo['User']['id'];
                $this->request->data['Experience']['status'] = 1;
                $this->request->data['Experience']['fdate'] = date('Y-m-d', $fff);
                $this->request->data['Experience']['tdate'] = date('Y-m-d', $ttt);
                $this->request->data['Experience']['slug'] = 'exp-' . $candidateInfo['User']['id'] . '-' . time();
                //pr($this->data);exit;
                if ($this->Experience->save($this->data)) {
                    $this->Session->setFlash('Candidate experience details saved successfully', 'success_msg');
                    $this->redirect('/admin/candidates/experience/' . $cslug);
                }
            }
        } else {
            if (isset($exslug) && $exslug != '') {
                $id = $this->Experience->field('id', array('Experience.slug' => $exslug));
                $this->Experience->id = $id;
                $this->data = $this->Experience->read();
                $this->request->data['Experience']['fdate'] = date('m/Y', strtotime($this->data['Experience']['fdate']));
                $this->request->data['Experience']['tdate'] = date('m/Y', strtotime($this->data['Experience']['tdate']));
            }
        }

        $experiences = $this->Experience->find('all', array('conditions' => $condition, 'order' => array('Experience.id DESC')));
        $this->set('experiences', $experiences);
    }

    public function admin_deleteCvDocument($slug = null) {
        $userDetails = $this->User->findBySlug($slug);
        if ($userDetails > 0) {
            $id = $userDetails['User']['id'];
            $cnd1 = array("User.id = '$id'");
            $this->User->updateAll(array('User.cv' => "''"), $cnd1);
            @unlink(UPLOAD_CV_PATH . $userDetails['User']['cv']);
            $this->Session->write('success_msg', 'Cv document deleted successfully.');
            $this->redirect('/admin/candidates/editcandidates/' . $slug);
        }
    }

    public function admin_downloadDocCertificate($filename = null) {
        set_time_limit(0);
        $file_path = UPLOAD_CERTIFICATE_PATH . $filename;
        $filename = substr($filename, 6);
        $this->Common->output_file($file_path, $filename);
        exit;
    }

    public function admin_downloadImage($filename = null) {
        set_time_limit(0);
        $file_path = UPLOAD_CERTIFICATE_PATH . $filename;
        $filename = substr($filename, 6);
        $this->Common->output_file($file_path, $filename);
        exit;
    }

    public function register() {

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Sign Up', true));
        $this->layout = "client";
        $this->set('registerA', 'active');
        $this->userLoggedinCheck();
        $msgString = '';

        global $extentions;

        if ($this->data) {
            //pr($this->data); exit;

            if (trim($this->data["User"]["company_name"]) == '') {
                $msgString .= __d('controller', 'Company name is required field.', true) . "<br>";
            } else {
                $mobile_valid = preg_match('/^[ a-zA-Z0-9_]+$/', $this->data["User"]["company_name"]);
                if ($mobile_valid == 0) {
                    $msgString .= __d('controller', 'Special characters and spaces are not allowed in companyname is not valid.', true) . "<br>";
                } elseif ($this->User->isRecordUniquecompany($this->data["User"]["company_name"]) == false) {
                    $msgString .= __d('controller', 'Company name already exists.', true) . "<br>";
                }
            }

            if (empty($this->data["User"]["first_name"])) {
                $msgString .= __d('controller', 'First Name is required field.', true) . "<br>";
            }

            if (empty($this->data["User"]["last_name"])) {
                $msgString .= __d('controller', 'Last Name is required field.', true) . "<br>";
            }

            if (trim($this->data["User"]["email_address"]) == '') {
                $msgString .= __d('controller', 'Email is required field.', true) . "<br>";
            } elseif ($this->User->checkEmail($this->data["User"]["email_address"]) == false) {
                $msgString .= __d('controller', 'Email Not Valid.', true) . "<br>";
            }

            if ($this->User->isRecordUniqueemail($this->data["User"]["email_address"]) == false) {
                $msgString .= __d('controller', 'Email already exists.', true) . "<br>";
            }

            if (trim($this->data["User"]["password"]) == '') {
                $msgString .= __d('controller', 'Password is required field.', true) . "<br>";
            } elseif (strlen($this->data["User"]["password"]) < 8) {
                $msgString .= __d('controller', 'Password must be at least 8 characters.', true) . "<br>";
            }

            if (trim($this->data["User"]["confirm_password"]) == '') {
                $msgString .= __d('controller', 'Confirm  Password is required field.', true) . "<br>";
            } else {
                $password = $this->data["User"]["password"];
                $conformpassword = $this->data["User"]["confirm_password"];

                if ($password != $conformpassword) {
                    $msgString .= __d('controller', 'Password and confirm password mismatch.', true) . "<br>";
                }
            }

            $captcha = $this->Captcha->getVerCode();
            if ($this->data['User']['captcha'] == "") {
                $msgString .= __d('controller', 'Please enter security code.', true) . "<br>";
            } elseif ($this->data['User']['captcha'] != $captcha) {
                $msgString .= __d('controller', 'Please enter correct security code.', true) . "<br>";
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
                $this->request->data['User']['captcha'] = "";
            } else {

                $passwordPlain = $this->data["User"]["password"];
                $salt = uniqid(mt_rand(), true);
                $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
                $this->request->data['User']['password'] = $new_password;
                $this->request->data['User']['first_name'] = trim($this->data['User']['first_name']);
                $this->request->data['User']['last_name'] = trim($this->data['User']['last_name']);
                $this->request->data['User']['slug'] = $this->stringToSlugUnique($this->data['User']['first_name'] . ' ' . $this->data['User']['last_name'], 'User', 'slug');
                $this->request->data['User']['user_type'] = 'recruiter';
                $this->request->data['User']['country_id'] = 1;

                if ($this->User->save($this->data)) {
                    $userId = $this->User->id;
                    $email = $this->data["User"]["email_address"];
                    $username = $this->data["User"]["first_name"];
                    $link = HTTP_PATH . "/candidates/confirmation/" . $userId . "/" . md5($userId) . "/" . urlencode($email);
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $this->Email->to = $email;
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='19'"));

                    $toSubArray = array('[!username!]', '[!company_name!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                    $fromSubArray = array($username, $this->data["User"]["company_name"], $this->data['User']['user_type'], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $link, $site_url);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!company_name!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                    $fromRepArray = array($username, $this->data["User"]["company_name"], $this->data['User']['user_type'], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $link, $site_url);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();



                    $this->Session->setFlash(__d('controller', 'Your account is successfully created. Please check your email for activation link. Thank you!', true), 'success_msg');
                    $this->redirect('/candidates/login');
                }
            }
        }
    }

    function confirmation($id = null, $md5id = null, $email = null) {
        if (md5($id) == $md5id) {
            $userCheck = $this->User->find('first', array('conditions' => array('User.email_address' => urldecode($email), 'User.id' => $id)));

            if (!$this->Session->read('user_id') && $userCheck['User']['activation_status'] == 0 && !empty($userCheck)) {
                $cnd = array("User.id" => $id);
                $this->User->updateAll(array('User.status' => "'1'", 'User.activation_status' => "'1'"), $cnd);

                $this->Session->write('success_msg', __d('controller', 'Your account has been activated successfully.', true));
                $this->redirect('/candidates/login');
            } else {
                if ($this->Session->read('user_id')) {
                    $this->Session->write('error_msg', __d('controller', 'You are already login with other account. Please logout first to activate your another account!', true));
                } else {
                    $this->Session->write('error_msg', __d('controller', 'You have already used this activation link!', true));
                }
                $this->redirect('/candidates/login');
            }
        }
    }

    public function captcha() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        if (!isset($this->Captcha)) {
            App::import('Component', 'Captcha'); //load it
            $this->Captcha = new CaptchaComponent(); //make instance
            $this->Captcha->startup($this); //and do some manually calling
        }
        $this->Captcha->create();
    }

    public function convertHeading($string = null) {

        $specialCharacters = array('#', '$', '%', '@', '.', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', ' ');
        $toReplace = "_";
        $string = str_replace($specialCharacters, $toReplace, $string);
        $replace = str_replace("&", "and", $string);
        return strtolower($replace);
    }

    function login() {

        $this->layout = "client";
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Login', true));
        $this->set('loginA', 'active');
        $msgString = "";
        $this->set("returnurl", "");
        $this->userLoggedinCheck();
        $time = time();
        $time1 = date('Y-m-d H:i:s', $time);

        if (isset($this->data) && !empty($this->data)) {

            //pr($this->data);exit;

            if (empty($this->data['User']['email_address'])) {
                $msgString .= __d('controller', 'Please enter your email address.', true) . "<br>";
            }
            if (empty($this->data['User']['password'])) {
                $msgString .= __d('controller', 'Please enter your password.', true) . "<br>";
            }
            $captcha = $this->Captcha->getVerCode();
            if ($this->Session->read('Userloginstatus') > 0) {
                if ($this->data['User']['captcha'] == "") {
                    $msgString .= __d('controller', 'Please enter security code.', true) . "<br>";
                } elseif ($this->data['User']['captcha'] != $captcha) {
                    $msgString .= __d('controller', 'Please enter correct security code.', true) . "<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
            } else {
                $email = $this->data['User']['email_address'];
                $password = $this->data['User']['password'];

                $userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email, "User.modified <=" => $time1)));

                if (is_array($userCheck) && !empty($userCheck) && crypt($password, $userCheck['User']['password']) == $userCheck['User']['password']) {

                    if ($userCheck['User']['status'] == 1 && $userCheck['User']['activation_status'] == 1) {
                        if (isset($this->data['User']['rememberme']) && $this->data['User']['rememberme'] == '1') {
                            setcookie("cookname", $this->data['User']['email_address'], time() + 60 * 60 * 24 * 7, "/");
                            setcookie("cookpass", $this->data['User']['password'], time() + 60 * 60 * 24 * 7, "/");
                        } else {
                            setcookie("cookname", '', time() + 60 * 60 * 24 * 7, "/");
                            setcookie("cookpass", '', time() + 60 * 60 * 24 * 7, "/");
                        }

                        $this->Session->write("user_id", $userCheck['User']['id']);
                        $this->Session->write("email_address", $userCheck['User']['email_address']);
                        $this->Session->write("user_name", $userCheck['User']['first_name']);
                        $this->Session->delete('Userloginstatus');
                        if ($this->Session->read("returnUrl")) {
                            $returnUrl = $this->Session->read("returnUrl");
                            $this->Session->delete("returnUrl");
                            $this->redirect('/' . $returnUrl);
                        } else {
                            $this->redirect("/candidates/myaccount");
                        }
                    } else {
                        if ($userCheck['User']['activation_status'] == 0) {
                            $msgString .= __d('controller', 'Please check you mail for activation link to activate your account.', true) . "<br>";
                        } else {
                            $msgString .= __d('controller', 'Your account might have been temporarily disabled. Please contact us for more details.', true) . "<br>";
                        }
                        $this->Session->delete('Userloginstatus');
                        $this->Session->write('error_msg', $msgString);
                        $this->request->data['User']['captcha'] = '';
                        // $this->redirect('/candidates/login');
                    }
                } else {
                    $this->Session->delete('user_id');
                    $i = $this->Session->read('Userloginstatus');
                    if ($i < 6) {
                        $i = 1 + $i;
                        $this->Session->write('Userloginstatus', $i);
                    }
                    if ($i == 1) {
                        $this->Session->write('error_msg', __d('controller', 'Invalid email and/or password. you have five more attempts.', true));
                    }
                    if ($i == 2) {
                        $this->Session->write('error_msg', __d('controller', 'Invalid email and/or password. you have four more attempts.', true));
                    }
                    if ($i == 3) {
                        $userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email)));
                        if (!empty($userCheck)) {
                            $email = $userCheck["User"]["email_address"];
                            if ($userCheck["User"]["last_name"]) {
                                $username = $userCheck["User"]["first_name"] . ' ' . $userCheck["User"]["last_name"];
                            } else {
                                $username = $userCheck["User"]["first_name"];
                            }
                            $username = $userCheck["User"]["first_name"];

                            $this->Email->to = $email;
                            $currentYear = date('Y', time());
                            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='11'"));

                            $toSubArray = array('[!username!]', '[!SITE_TITLE!]');
                            $fromSubArray = array($username, $site_title);
                            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                            $this->Email->subject = $subjectToSend;

                            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                            $this->Email->from = $site_title . "<" . $mail_from . ">";

                            $toRepArray = array('[!username!]', '[!SITE_TITLE!]');
                            $fromRepArray = array($username, $site_title);
                            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                            $this->Email->layout = 'default';
                            $this->set('messageToSend', $messageToSend);
                            $this->Email->template = 'email_template';
                            $this->Email->sendAs = 'html';
                            $this->Email->send();
                        }

                        $this->Session->write('error_msg', __d('controller', 'Invalid username and/or password. you have three more attempts.', true));
                    }
                    if ($i == 4) {
                        $this->Session->write('error_msg', __d('controller', 'Invalid username and/or password. you have two more attempts.', true));
                    }
                    if ($i == 5) {
                        $this->Session->write('error_msg', __d('controller', 'Invalid username and/or password. you have one more attempts.', true));
                    }
                    if ($i == 6) {
                        $userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email)));
                        if ($userCheck['User']['modified'] <= date('Y-m-d H:i:s', time())) {
                            $userCheck['User']['id'] = $userCheck['User']['id'];
                            $userCheck['User']['modified'] = date('Y-m-d H:i:s', time() + 30 * 60);
                            $this->User->save($userCheck);
                        }
                        $this->Session->write('error_msg', __d('controller', 'Invalid email and/or password.', true));
                    }
                    $this->request->data['User']['captcha'] = '';
                }
            }
        } else {
            if (isset($_COOKIE["cookname"]) && isset($_COOKIE["cookpass"])) {
                $this->request->data['User']['email_address'] = $_COOKIE["cookname"];
                $this->request->data['User']['password'] = $_COOKIE["cookpass"];
            }
        }
    }

    public function myaccount() {

        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'My profile', true));

        $this->userLoginCheck();
        $this->candidateAccess();

        $userId = $this->Session->read("user_id");
        $userdetail = $this->User->findById($userId);

        $this->set('userdetail', $userdetail);
        $this->set('myaccount', 'active');

        $getRemainingFeatures = $this->Plan->getPlanFeature($userId);
        
        //echo '<pre>';print_r($getRemainingFeatures);exit;
         $this->set('getRemainingFeatures', $getRemainingFeatures);

        $showOldImages = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userId, 'type' => 'image')));
        $this->set('showOldImages', $showOldImages);

        $showOldDocs = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userId, 'type' => 'doc')));
        $this->set('showOldDocs', $showOldDocs);

        $coverLetters = $this->CoverLetter->find('list', array('conditions' => array('CoverLetter.user_id' => $userId), 'fields' => array('id', 'title'), 'order' => 'CoverLetter.id DESC'));
        if (!empty($coverLetters)) {
            $this->set('coverLetters', implode(',', $coverLetters));
        } else {
            $this->set('coverLetters', '');
        }
        $interest_categories = $userdetail['User']['interest_categories'];

        $educationDetails = $this->Education->find('all', array('conditions' => array('Education.user_id' => $userId)));
        // print_r($educationDetails);exit;
        $this->set('educationDetails',$educationDetails);

        $experienceDetails = $this->Experience->find('all', array('conditions' => array('Experience.user_id' => $userId)));
        $this->set('experienceDetails',$experienceDetails);

        if ($interest_categories) {
            $condition[] = " (Category.id IN ($interest_categories ) )";
            $Categories_array = $this->Category->find('list', array('conditions' => $condition, 'order' => array('Category.name' => 'asc')));
            if (!empty($Categories_array)) {
                $this->set('interestCategories', implode(',', $Categories_array));
            } else {
                $this->set('interestCategories', '');
            }
        } else {
            $this->set('interestCategories', '');
        }
    }

    public function editProfile($status = null) {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";

        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Edit Profile', true));
        $this->set('editprofile', 'active');
        global $extentions;
        global $extentions_doc;
        global $extentions_video;

        $this->userLoginCheck();
        $this->candidateAccess();
        $msgString = '';

        $userId = $this->Session->read("user_id");
        $userDetails = $this->User->findById($userId);
        $coverLetters = $this->CoverLetter->find('all', array('conditions' => array('CoverLetter.user_id' => $userId)));

        $showOldImages = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userId, 'type' => 'image')));
        $this->set('showOldImages', $showOldImages);
        $showOldDocs = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userId, 'type' => 'doc')));
        $this->set('showOldDocs', $showOldDocs);

        $categoryList = $this->Category->find('all', array('conditions' => array('Category.status' => 1, 'Category.parent_id' => 0), 'fields' => array('id', 'name'), 'order' => 'Category.name ASC'));
        $this->set('categoryList', $categoryList);

        $categories = $this->Category->getCategoryList();
        $this->set('categories', $categories);

        /* $basicCourseList = $this->Course->find('list', array('conditions' => array('Course.type' => 'Basic', 'Course.status' => 1)));
          $this->set('basicCourseList', $basicCourseList);

          $postCourseList = $this->Course->find('list', array('conditions' => array('Course.type' => 'Post', 'Course.status' => 1)));
          $this->set('postCourseList', $postCourseList);

          $doctorCourseList = $this->Course->find('list', array('conditions' => array('Course.type' => 'Doctor', 'Course.status' => 1)));
          $this->set('doctorCourseList', $doctorCourseList); */

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.name', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('skillList', $skillList);


        $specilyList = '';
        $this->set('specilyList1', $specilyList);
        //$this->set('specilyList2', $specilyList);
        //$this->set('specilyList3', $specilyList);

        $mob = "/^[1-9][0-9]*$/";

        if ($this->data) {

            if (empty($this->data["User"]["first_name"])) {
                $msgString .= __d('controller', 'First name is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["User"]["first_name"]);
            }

            if (empty($this->data["User"]["last_name"])) {
                $msgString .= __d('controller', 'Last name is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["User"]["last_name"]);
            }

            if (empty($this->data["User"]["location"])) {
                $msgString .= __d('controller', 'Location is required field.', true) . "<br>";
            }

            if (empty($this->data["User"]["contact"])) {
                $msgString .= "- Contact number is required field.<br>";
//            } elseif (!preg_match($mob, $this->data["User"]["contact"])) {
//                $msgString .= __d('controller', 'Enter Valid Phone number.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["User"]["contact"]);
            }


            if (!empty($this->data['CoverLetter'])) {
                $countter = 1;
                foreach ($this->data['CoverLetter'] as $key => $value) {
                    if (trim($value['title']) == '') {
                        $msgString .= "- Cover letter " . $countter . " title is required field.<br>";
                    } else {
                        $msgString .= $this->Swear->checkSwearWord($value['title']);
                    }
                    if (trim($value['description']) == '') {
                        $msgString .= __d('controller', 'Cover letter', true) . " " . $countter . " " . __d('controller', 'Cover description is required field.', true) . "<br>";
                    } else {
                        $msgString .= $this->Swear->checkSwearWord($value['description']);
                    }
                    $countter++;
                }
            }

            if ($this->data["User"]["video"]["name"]) {

                $getextention = $this->PImage->getExtension($this->data['User']['video']['name']);
                $extention = strtolower($getextention);

                if ($this->data['User']['video']['size'] > '20971520') {
                    $msgString .= "- Max file size upload is 20MB.<br>";
                } elseif (!in_array($extention, $extentions_video)) {
                    $msgString .= "- Not Valid Extention.<br>";
                }
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
                /* $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['User']['postal_code'])));
                  $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
                  $this->set('stateList', $stateList);
                  $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
                  $this->set('cityList', $cityList); */
                if (!empty($this->data['CoverLetter'])) {
                    $let = array();
                    foreach ($this->data['CoverLetter'] as $key => $value) {
                        $let[$key] = array('CoverLetter' => $value);
                    }
                    $this->request->data['CoverLetter'] = $let;
                }
            } else {

                $this->User->id = $this->Session->read("user_id");
                $this->request->data['User']['cv'] = '';
                $this->request->data['User']['email_notification_id'] = implode(',', $this->data["User"]["email_notification_id"]);
                $this->request->data['User']['profile_update_status'] = 1;
                if ($this->data['Certificate']['images']) {
                    $postArray = explode(',', $this->data['Certificate']['images']);
                    $certificateArray = $this->Certificate->find('list', array('conditions' => array('Certificate.user_id' => $userId), 'fields' => array('Certificate.id', 'Certificate.document')));
                    $deleteArray = array_diff($certificateArray, array_values($postArray));
                    if ($deleteArray) {
                        foreach ($deleteArray as $value) {
                            $this->Certificate->deleteAll(array("Certificate.document" => $value));
                            @unlink(UPLOAD_CERTIFICATE_PATH . $value);
                        }
                    }
                }

                $getextention = $this->PImage->getExtension($this->data['User']['video']['name']);
                $extention = strtolower($getextention);
                if (in_array($extention, $extentions_video)) {
//                    $imageArray = $imageData;
                    $imageArray = $this->data['User']['video'];
                    $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                    $toReplace = "-";
                    $returnedUploadVideoArray = $this->PImage->upload($imageArray, UPLOAD_VIDEO_PATH);
                    $image = $returnedUploadVideoArray[0];
                    chmod(UPLOAD_VIDEO_PATH .$returnedUploadImageArray[0], 0755);
                    $this->request->data['User']['video'] = $image;
                    @unlink(UPLOAD_VIDEO_PATH . $userDetails['User']['video']);
                } else {
                    $this->request->data['User']['video'] = $userDetails['User']['video'];
                }

                if ($this->data['CoverLetter']) {
                    foreach ($this->data['CoverLetter'] as $key => $value) {
                        if ($value['title'] != '' && $value['description'] != '') {
                            $update_data['CoverLetter'] = $value;
                            $update_data['CoverLetter']['user_id'] = $userId;
                            $this->CoverLetter->create();
                            $this->CoverLetter->save($update_data);
                        }
                    }
                }

//                if ($this->data['User']['pre_location']) {
//                    $this->request->data['User']['pre_location'] = implode(',', $this->data['User']['pre_location']);
//                }
                if ($this->data['User']['skills']) {
                    $this->request->data['User']['skills'] = implode(',', $this->data['User']['skills']);
                }
                $this->request->data['User']['interest_categories'] = $this->data['User']['interest_categories'] ? implode(',', $this->data['User']['interest_categories']) : $this->data['User']['interest_categories'];
                if ($this->User->save($this->data)) {


                    if (!empty($this->data["Certificate"]["document"])) {
                        $imagesArr = explode(',', $this->data["Certificate"]["document"]);

                        foreach ($imagesArr as $imageName) {
                            if (!empty($imageName) && file_exists(UPLOAD_TMP_CERTIFICATE_PATH . $imageName)) {
                                copy(UPLOAD_TMP_CERTIFICATE_PATH . $imageName, UPLOAD_CERTIFICATE_PATH . $imageName);
                                $this->Certificate->create();
                                $this->request->data['Certificate']['document'] = $imageName;
                                $this->request->data['Certificate']['user_id'] = $userId;
                                $getextention = $this->PImage->getExtension($imageName);
                                $extention = strtolower($getextention);
                                if (in_array($extention, $extentions)) {
                                    $this->request->data['Certificate']['type'] = 'image';
                                    $this->request->data['Certificate']['slug'] = 'image-' . $userId . time() . rand(111, 99999);
                                } elseif (in_array($extention, $extentions_doc)) {
                                    $this->request->data['Certificate']['type'] = 'doc';
                                    $this->request->data['Certificate']['slug'] = 'doc-' . $userId . time() . rand(111, 99999);
                                }
                                $this->Certificate->save($this->data);
                                @unlink(UPLOAD_TMP_CERTIFICATE_PATH . $imageName);
                            }
                        }
                    }

                    if ($status == 'return') {
                        $this->Session->write('success_msg', __d('controller', 'Your Profile Details are updated Succesfully. Now you can apply for the job which you want.', true));
                        $_SESSION['job_apply_popup_status'] = '1';
                        $this->redirect('/' . $_SESSION['job_apply_return_url']);
                    }

                    if ($userDetails['User']['profile_update_status'] == 0) {
                        $this->Session->write('success_msg', __d('controller', 'Your account is now complete, Happy job searching.', true));
                    } else {
                        $this->Session->write('success_msg', __d('controller', 'Profile details updated successfully.', true));
                    }
                    $this->redirect('/candidates/myaccount');
                }
            }
        } else {

            $this->User->id = $userId;
            $this->data = $this->User->read();

            //$this->request->data['User']['old_cv'] = $this->request->data['User']['cv'];

            /*  $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['User']['postal_code'])));
              $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
              $this->set('stateList', $stateList);
              $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
              $this->set('cityList', $cityList); */

            /*  $specilyList1 = $this->Specialization->find('list', array('conditions' => array('Specialization.status' => 1, 'Specialization.course_id' => $this->data['User']['basic_course_id']), 'order' => array('Specialization.name' => 'asc')));
              $this->set('specilyList1', $specilyList1);
              $specilyList2 = $this->Specialization->find('list', array('conditions' => array('Specialization.status' => 1, 'Specialization.course_id' => $this->data['User']['post_course_id']), 'order' => array('Specialization.name' => 'asc')));
              $this->set('specilyList2', $specilyList2);
              $specilyList3 = $this->Specialization->find('list', array('conditions' => array('Specialization.status' => 1, 'Specialization.course_id' => $this->data['User']['doctor_course_id']), 'order' => array('Specialization.name' => 'asc')));
              $this->set('specilyList3', $specilyList3); */

            if ($this->data['User']['dob'] == '' || $this->data['User']['dob'] == '0000-00-00') {
                $this->request->data['User']['dob'] = '';
            }

            if ($this->data["User"]["email_notification_id"] != '') {
                $this->request->data['User']['email_notification_id'] = explode(',', $this->data["User"]["email_notification_id"]);
            } else {
                $this->request->data['User']['email_notification_id'] = '';
            }

            if (!empty($coverLetters)) {
                $this->request->data['CoverLetter'] = $coverLetters;
            } else {
                $this->request->data['CoverLetter'] = '';
            }

//            if ($this->data['User']['pre_location']) {
//                $this->request->data['User']['pre_location'] = explode(',', $this->data['User']['pre_location']);
//            }
            if ($this->data['User']['skills']) {
                $this->request->data['User']['skills'] = explode(',', $this->data['User']['skills']);
            }
            if ($this->data['User']['interest_categories']) {
                $this->request->data['User']['interest_categories'] = explode(',', $this->data['User']['interest_categories']);
            }
        }
    }

    public function deleteCover($id = NULL) {
        $this->layout = "";
        if ($id != '') {
            $this->CoverLetter->delete($id);
            $letters = $this->CoverLetter->find('all', array('conditions' => array('CoverLetter.user_id' => $this->Session->read("user_id"))));

            if (!empty($letters)) {
                $this->request->data['CoverLetter'] = $letters;
            } else {
                $this->request->data['CoverLetter'] = '';
            }
        }
    }

    public function changePassword() {

        $this->layout = "client";
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Change Password', true));
        $msgString = '';
        $this->set('changepassword', 'active');
        $this->userLoginCheck();
        $this->candidateAccess();
        $id = $this->Session->read("user_id");
        $this->User->id = $id;
        $userOldPassword = $this->User->read();
        if ($this->data) {

            if (empty($this->data["User"]["old_password"])) {
                $msgString .= __d('controller', 'Old Password is required field.', true) . "<br>";
            }
            if (empty($this->data["User"]["new_password"])) {
                $msgString .= __d('controller', 'Old Password is required field.', true) . "<br>";
            } elseif (strlen($this->data["User"]["new_password"]) < 8) {
                $msgString .= __d('controller', 'Password must be at least 8 characters.', true) . "<br>";
            }


            if (empty($this->data["User"]["conf_password"])) {
                $msgString .= __d('controller', 'Confirm Password is required field.', true) . "<br>";
            } else {
                //md5($this->data['User']['old_password']) != $userOldPassword['User']['password']
                if (crypt($this->data['User']['old_password'], $userOldPassword['User']['password']) != $userOldPassword['User']['password']) {// Matching the old password
                    $msgString .= "- Old Password is not correct.<br>";
                } else {//md5($this->data['User']['new_password']) == $userOldPassword['User']['password']
                    if (crypt($this->data['User']['new_password'], $userOldPassword['User']['password']) == $userOldPassword['User']['password']) {// Checking the both password matched aur not
                        $msgString .= __d('controller', 'You cannot put your old password for the new password', true) . "<br>";
                    }
                }
            }

            if ($this->data['User']['new_password'] != $this->data['User']['conf_password']) {// Checking the both password matched aur not
                $msgString .= __d('controller', 'New Password and Confirm Password mismatch.', true) . "<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
            } else {
                $this->request->data['User']['id'] = $id;
                $passwordPlain = $this->data["User"]["conf_password"];
                $salt = uniqid(mt_rand(), true);
                $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
                $this->request->data['User']['password'] = $new_password;
                $this->request->data['User']['enc_password'] = $this->generatePassword($passwordPlain);


                $this->User->save($this->data);
                $this->Session->write('success_msg', __d('controller', 'Your Password has been changed successfully, Please login with new updated details.', true));
//                $this->redirect("/candidates/myaccount");
                $this->Session->delete('user_id');
                $this->Session->delete('user_name');
                $this->Session->delete('email_address');
                $userCheck = $this->User->findById($id);
                if (!empty($userCheck)) {
                    $email = $userCheck["User"]["email_address"];
                    if ($userCheck["User"]["last_name"]) {
                        $username = $userCheck["User"]["first_name"] . ' ' . $userCheck["User"]["last_name"];
                    } else {
                        $username = $userCheck["User"]["first_name"];
                    }
                    $username = $userCheck["User"]["first_name"];

                    $this->Email->to = $email;
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='44'"));

                    $toSubArray = array('[!username!]', '[!SITE_TITLE!]');
                    $fromSubArray = array($username, $site_title);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!SITE_TITLE!]');
                    $fromRepArray = array($username, $site_title);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                }
//                die;
                if ($userCheck['User']['user_type'] == 'recruiter') {
                    //$this->redirect("/users/editProfile");
                    $this->redirect("/users/employerlogin");
                } else {
                    //$this->redirect("/candidates/editProfile");
                    $this->redirect("/users/login");
                }
            }
        }
    }

    public function deleteImage($slug = null) {
        //$this->userLoggedinCheck();
        //$id = $this->Session->read("user_id");
        $cnd1 = array("User.slug" => "$slug");
        $userProfileImage = $this->User->field('profile_image', $cnd1);
        if ($userProfileImage) {
            $this->User->updateAll(array('User.profile_image' => "''"), $cnd1);
            @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $userProfileImage);
            @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $userProfileImage);
            @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $userProfileImage);
            $this->Session->write('success_msg', __d('controller', 'Profile Image deleted successfully.', true));
        }
        $this->redirect('/candidates/uploadPhoto');
        // exit;
    }

    public function logout() {
        $this->Session->write('success_msg', __d('controller', 'Logout successfully.', true));
        $this->Session->delete('user_id');
        $this->Session->delete('user_name');
        $this->Session->delete('email_address');
        $this->redirect('/candidates/login');
    }

    public function forgotPassword() {
        $this->layout = "client";

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Forgot Password', true));

        $msgstring = "";
        $this->set("returnurl", "");
        $this->userLoggedinCheck();
        if (isset($this->data) && !empty($this->data)) {
            if (empty($this->data['User']['email_address'])) {
                $msgstring = __d('controller', 'Please enter your email address.', true) . "<br>";
            } elseif ($this->User->checkEmail($this->data["User"]["email_address"]) == false) {
                $msgstring .= __d('controller', 'Please enter valid email address.', true) . "<br>";
            }
            if ($msgstring == '') {
                $email = $this->data["User"]["email_address"];
                $userCheck = $this->User->find("first", array("conditions" => "User.email_address = '" . $email . "'"));
                if (is_array($userCheck) && !empty($userCheck)) {
                    $this->User->updateAll(array('User.forget_password_status' => 1), array('User.id' => $userCheck['User']['id']));
                    $email = $userCheck["User"]["email_address"];
                    $link = HTTP_PATH . "/candidates/resetPassword/" . $userCheck['User']['id'] . "/" . md5($userCheck['User']['id']) . "/" . urlencode($email);
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';


                    $this->Email->to = $email;
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='4'"));
                    $toSubArray = array('[!username!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!activelink!]');
                    $fromSubArray = array($username, HTTP_PATH, $site_title, $link);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!activelink!]');
                    $fromRepArray = array($username, HTTP_PATH, $site_title, $link);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();


                    $this->Session->write('success_msg', __d('controller', 'A link to reset your password was sent to your email address', true));
                    $this->redirect('/candidates/forgotPassword');
                } else {
                    $msgstring = __d('controller', 'Email address you enter not found in our database, please enter correct email address.', true);
                    $this->Session->write("error_msg", $msgstring);
                }
            } else {
                $this->Session->write("error_msg", $msgstring);
            }
        }
    }

    function resetPassword($id = null, $md5id = null, $email = null) {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Reset Password', true));

        $msgString = "";
        $this->set("returnurl", "");
        $this->set("userId", $id);
        $this->userLoggedinCheck();
        if (md5($id) == $md5id) {
            $userCheck = $this->User->find('first', array('conditions' => array('User.email_address' => urldecode($email), 'User.id' => $id), 'fields' => array('User.forget_password_status', 'User.password')));

            if ($userCheck['User']['forget_password_status'] == 1) {
                $this->set('userId', $id);
                if (isset($this->data) && !empty($this->data)) {
                    if (trim($this->data["User"]["password"]) == '') {
                        $msgString .= __d('controller', 'New Password is required field.', true) . "<br>";
                    } elseif (strlen($this->data["User"]["password"]) < 8) {
                        $msgString .= __d('controller', 'New Password must be at least 8 characters.', true) . "<br>";
                    }

                    if (empty($this->data["User"]["confirm_password"])) {
                        $msgString .= __d('controller', 'Confirm Password is required field.', true) . "<br>";
                    }
                    $password = $this->data["User"]["password"];
                    $conformpassword = $this->data["User"]["confirm_password"];

                    if ($password != $conformpassword) {
                        $msgString .= __d('controller', 'New password and confirm password mismatch.', true) . "<br>";
                    } elseif (crypt($this->data['User']['password'], $userCheck['User']['password']) == $userCheck['User']['password']) {// Checking the both password matched aur not
                        $msgString .= __d('controller', 'You cannot put your old password for the new password', true) . "<br>";
                    } else {
                        $passwordPlain = $this->data["User"]["password"];
                        $salt = uniqid(mt_rand(), true);
                        $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
                        $this->request->data['User']['password'] = $new_password;
                        $this->request->data['User']['enc_password'] = $this->generatePassword($passwordPlain);
                    }

                    if (isset($msgString) && $msgString != '') {
                        $this->Session->write('error_msg', $msgString);
                    } else {
                        $this->request->data['User']['forget_password_status'] = 0;
                        $this->User->save($this->data);
                        $this->Session->write('success_msg', __d('controller', 'Password is reset successfully. Please Login', true));
                        $this->redirect('/candidates/login');
                    }
                }
            } else {
                $this->Session->write('error_msg', __d('controller', 'You have already use this link!', true));
                $this->redirect('/candidates/login');
            }
        } else {
            $this->redirect('/candidates/login');
        }
    }

    public function uploadPhoto() {

        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Upload Photo', true));
        $this->set('uploadPhoto', 'active');
        $msgString = '';
        global $extentions;
        $this->userLoginCheck();
        $this->candidateAccess();
        $id = $this->Session->read("user_id");
        $msgString = '';

        $UseroldImage = $this->User->find('first', array('conditions' => array('User.id' => $id), 'fields' => array('User.profile_image', 'User.slug')));
        $this->set("UseroldImage", $UseroldImage);
        if ($this->data) {

            $getextention = $this->PImage->getExtension($this->data['User']['profile_image']['name']);
            $extention = strtolower($getextention);
            if (empty($this->data["User"]["profile_image"]["name"])) {
                $msgString .= __d('controller', 'Profile image is required field.', true) . "<br>";
            } elseif ($this->data['User']['profile_image']['size'] > '2097152') {
                $msgString .= __d('controller', 'Max file size upload is 2MB.', true) . " <br>";
            } elseif (!in_array($extention, $extentions)) {
                $msgString .= __d('controller', 'Not Valid Extention.', true) . "<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
            } else {

                $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                $toReplace = "-";
                $this->request->data['User']['profile_image']['name'] = str_replace($specialCharacters, $toReplace, $this->data['User']['profile_image']['name']);
                $this->request->data['User']['profile_image']['name'] = str_replace("&", "and", $this->data['User']['profile_image']['name']);
                if (!empty($this->data['User']['profile_image']['name'])) {
                    $imageArray = $this->data['User']['profile_image'];
                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_PROFILE_IMAGE_PATH, "jpg,jpeg,png,gif");
                    list($width, $height, $type, $attr) = getimagesize(UPLOAD_FULL_PROFILE_IMAGE_PATH . '/' . $returnedUploadImageArray[0]);
                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= __d('controller', 'Profile Image file not valid.', true) . "<br>";
                    } else if ($width < 250 && $height < 250) {
                        @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . '/' . $returnedUploadImageArray[0]);
                        $msgString .= __d('controller', 'Profile Images size must be bigger than  250 X 250 pixels.', true) . "<br>";
                    } else {
                        copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        list($width) = getimagesize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        if ($width > 650) {
                            $this->PImageTest->resize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_IMAGE_WIDTH, UPLOAD_FULL_PROFILE_IMAGE_HEIGHT, 100);
                        }

                        $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                        $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
                        $profilePic = $returnedUploadImageArray[0];
                        chmod(UPLOAD_FULL_PROFILE_IMAGE_PATH .$returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        
                        if (isset($this->data['User']['old_image']) && $this->data['User']['old_image'] != "") {
                            @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                            @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                            @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                        }
                    }
                }

                if (isset($msgString) && $msgString != '') {
                    $this->Session->write('error_msg', $msgString);
                } else {
                    $id = $this->Session->read("user_id");
                    $cnd = array("User.id = $id");
                    $this->User->updateAll(array('User.profile_image' => "'$profilePic'"), $cnd);
                    $this->Session->write('success_msg', __d('controller', 'Your Image has been Uploaded successfully.', true));
                    $this->redirect('/candidates/myaccount');
                }
            }
        }
    }

    public function admin_sendemail($slug = null) {
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Send Email");
        $this->layout = "admin";
        $this->set('default', '1');
        $this->set('send_email1', 'active');
        $msgString = '';

        $sellerList = $this->User->find('list', array('conditions' => array(), 'fields' => array('User.id', 'User.first_name'), 'order' => 'User.id desc'));
        $this->set('sellerList', $sellerList);

        if ($this->data) {
            if (empty($this->data["User"]["id"])) {
                $msgString .= "- User Name is required field.<br>";
            }
            if (empty($this->data["User"]["subject"])) {
                $msgString .= "- Subject is required field.<br>";
            }
            if (empty($this->data["User"]["message"])) {
                $msgString .= "- Message is required field.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
            } else {

                $userdet = $this->User->find('first', array('fields' => array('User.first_name', 'User.email_address'), 'conditions' => array('User.id' => $this->data["User"]["id"])));
                if ($userdet) {
                    $email = $userdet['User']['email_address'];
                    if ($userdet["User"]["last_name"]) {
                        $username = $userdet["User"]["first_name"] . ' ' . $userdet["User"]["last_name"];
                    } else {
                        $username = $userdet["User"]["first_name"];
                    }
                    $this->Email->to = $email;
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='20'"));
                    // $this->Email->subject = $this->data['User']['subject'];

                    $toSubArray = array('[!username!]', '[!subject!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                    $fromSubArray = array($username, $this->data['User']['subject'], $this->data['User']['message'], $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                    $this->Email->subject = $subjectToSend;


                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";
                    $toRepArray = array('[!username!]', '[!subject!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                    $fromRepArray = array($username, $this->data['User']['subject'], $this->data['User']['message'], $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                    $this->Session->write('success_msg', 'You have sent the email to the seller successfully.');
                }
                $this->redirect('/admin/candidates/sendemail');
            }
        } elseif ($slug) {
            $this->request->data['User']['id'] = $this->User->field('id', array('User.slug' => $slug));
        }
    }

    function admin_generateABAFile($searchByDateFrom = null, $searchByDateTo = null, $searchByCountry = null, $userName = null) {
        $this->set('default', '1');
        $this->layout = "csv";

        $condition = array();

        $data = $this->User->find('all', array('fields' => array('User.first_name', 'User.last_name', 'User.email_address', 'User.status'), 'conditions' => $condition, 'order' => 'User.id desc'));
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]['User']['status'] == 1) {
                $data[$i]['User']['status'] = 'Activate';
            } else {
                $data[$i]['User']['status'] = 'Deactivate';
            };
        }
        $headers = array(
            'User' => array(
                'User.first_name' => 'First Name',
                'User.last_name' => 'Last Name',
                'User.email_address' => 'Email Address',
                'User.status' => 'Status',
            )
        );

        array_unshift($data, $headers);
        $this->set(compact('data'));
    }

    function view() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . ' : Login Process');
    }

    public function admin_reset_search() {
        $this->layout = '';
        $this->autoRender = false;
        if (isset($_SESSION['searchByDateFrom']) && $_SESSION['searchByDateFrom'] != '') {
            unset($_SESSION['searchByDateFrom']);
        }
        if (isset($_SESSION['searchByDateTo']) && $_SESSION['searchByDateTo'] != '') {
            unset($_SESSION['searchByDateTo']);
        }
        if (isset($_SESSION['userName']) && $_SESSION['userName'] != '') {
            unset($_SESSION['userName']);
        }

        $this->redirect($this->referer());
    }

    function admin_generatefile() {
        $this->set('default', '1');
        $this->layout = false;
        //App::import('Vendor', 'PHPExcel/PHPExcel');
        $data = $this->User->find('all', array('order' => 'User.id desc'));
        $this->set('data', $data);
    }

    function admin_generateipfile() {
        $this->set('default', '1');
        $this->layout = false;
        //App::import('Vendor', 'PHPExcel/PHPExcel');
        $data = $this->Ipaddress->find('all', array('order' => 'Ipaddress.id desc'));
        $this->set('data', $data);
    }

    public function certificates() {

        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Manage Certificates');
        $this->userLoginCheck();
        $this->candidateAccess();

        $this->set('manageCertificate', 'active');

        $userId = $this->Session->read('user_id');
        global $extentions;
        $msgString = '';

        $condition = array('Certificate.user_id' => $userId);
        if (!empty($this->data)) {
            $getextentionCV = $this->PImage->getExtension($this->data['Certificate']['document']['name']);
            $extentionCV = strtolower($getextentionCV);
            if (empty($this->data["Certificate"]["document"]["name"])) {
                $msgString .= __d('controller', 'Certificate copy is required field.', true) . "<br>";
            } elseif ($this->data['Certificate']['document']['size'] > '2097152') {
                $msgString .= __d('controller', 'Max file size upload is 2MB.', true) . "<br>";
            } elseif (!in_array($extentionCV, $extentions)) {
                $msgString .= "- Not Valid Extention.<br>";
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
            } else {

                $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                $toReplace = "-";
                $this->request->data['Certificate']['document']['name'] = str_replace($specialCharacters, $toReplace, $this->data['Certificate']['document']['name']);
                $this->request->data['Certificate']['document']['name'] = str_replace("&", "and", $this->data['Certificate']['document']['name']);
                $cvArray = $this->data['Certificate']['document'];
                $returnedUploadCVArray = $this->PImage->upload($cvArray, UPLOAD_CERTIFICATE_PATH);
                chmod(UPLOAD_CERTIFICATE_PATH .$returnedUploadImageArray[0], 0755);
                $this->request->data['Certificate']['document'] = $returnedUploadCVArray[0];
                $this->request->data['Certificate']['user_id'] = $userId;

                $this->request->data['Certificate']['slug'] = $this->stringToSlugUnique($this->data['Certificate']['document'], 'Certificate', 'slug');

                if ($this->Certificate->save($this->data)) {
                    $this->Session->write('success_msg', 'Candidate certificates uploaded successfully.');
                    $this->redirect('/candidates/certificates');
                }
            }
        }

        $certificates = $this->Certificate->find('all', array('conditions' => $condition, 'order' => array('Certificate.id DESC')));
        $this->set('certificates', $certificates);
    }

    public function deleteCertificate($slug = NULL) {
        if ($slug != '') {
            $id = $this->Certificate->field('id', array('Certificate.slug' => $slug));
            $image = $this->Certificate->field('document', array('Certificate.slug' => $slug));
            if ($this->Certificate->delete($id)) {
                @unlink(UPLOAD_CERTIFICATE_PATH . $image);
            }

            $this->Session->write('success_msg', 'Certificate deleted successfully.');
        }
        $this->redirect('/candidates/certificates/');
    }

    public function download($filename = null) {
        set_time_limit(0);
        $file_path = UPLOAD_CV_PATH . $filename;
        $filename = substr($filename, 6);
        $this->Common->output_file($file_path, $filename);
        exit;
    }

    public function downloadDocCertificate($filename = null) {
        set_time_limit(0);
        $file_path = UPLOAD_CERTIFICATE_PATH . $filename;
        $filename = substr($filename, 6);
        $this->Common->output_file($file_path, $filename);
        exit;
    }

    public function downloadDocCertificateTemp($filename = null) {
        set_time_limit(0);
        $file_path = UPLOAD_TMP_CERTIFICATE_PATH . $filename;
        $filename = substr($filename, 6);
        $this->Common->output_file($file_path, $filename);
        exit;
    }

    public function downloadImage($filename = null) {
        set_time_limit(0);
        $file_path = UPLOAD_CERTIFICATE_PATH . $filename;
        $filename = substr($filename, 6);
        $this->Common->output_file($file_path, $filename);
        exit;
    }

    public function downloadMailFile($filename = null) {
        set_time_limit(0);
        if (!file_exists(UPLOAD_MAIL_PATH . $filename)) {
            $this->Session->setFlash(__d('controller', 'Please try again', true), 'error_msg');
            $this->redirect($this->referer());
        }

        $file_path = UPLOAD_MAIL_PATH . $filename;
        $filename = substr($filename, 6);
        $this->Common->output_file($file_path, $filename);
        exit;
    }

    public function uploadmultipleimages() {

        $msgString = '';

        global $extentions;
        global $extentions_doc;

        $this->layout = '';

        if ($this->data['Certificate']['document']['name'] != '') {
            list($width, $height, $type, $attr) = getimagesize($this->data['Certificate']['document']['tmp_name']);
            $getextention = $this->PImage->getExtension($this->data['Certificate']['document']['name']);
            $extention = strtolower($getextention);

            if (!in_array($extention, $extentions) && !in_array($extention, $extentions_doc)) {
                $msgString .= __d('controller', 'Not Valid Extention.', true) . "<br>";
            } else {
                if (in_array($extention, $extentions)) {
                    if ($this->data['Certificate']['document']['size'] > '4194304') {
                        $msgString .= __d('controller', 'Max file size upload is 4MB', true) . "<br>";
                    } elseif (!in_array($extention, $extentions)) {
                        $msgString .= __d('controller', 'Not Valid Extention.', true) . "<br>";
                    } elseif ($width < 150 || $height < 150) {
                        $msgString .= "- Image size must be bigger than 150 x 150 pixels<br>";
                    }
                } elseif (in_array($extention, $extentions_doc)) {
                    if ($this->data['Certificate']['document']['size'] > '4194304') {
                        $msgString .= "- Max file size upload is 4MB.<br>";
                    } elseif (!in_array($extention, $extentions_doc)) {
                        $msgString .= __d('controller', 'Not Valid Extention.', true) . "<br>";
                    }
                }
            }
        }



        if (isset($msgString) && $msgString != '') {
            //$this->Session->setFlash($msgString, 'error_msg');
        } else {
            if (in_array($extention, $extentions)) {
                $imageArray = $this->data['Certificate']['document'];
                $imageArray['name'] = $this->sanitizeFilename($this->data['Certificate']['document']['name']);
                $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_TMP_CERTIFICATE_PATH, "jpg,jpeg,png,gif");
                if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                    $msgString .= "- Image file not valid <br>";
                } else {
                    $image = $returnedUploadImageArray[0];
                    chmod(UPLOAD_TMP_CERTIFICATE_PATH .$returnedUploadImageArray[0], 0755);
                }
            } elseif (in_array($extention, $extentions_doc)) {
                $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                $toReplace = "-";
                $this->request->data['Certificate']['document']['name'] = str_replace($specialCharacters, $toReplace, $this->data['Certificate']['document']['name']);
                $this->request->data['Certificate']['document']['name'] = str_replace("&", "and", $this->data['Certificate']['document']['name']);
                if ($this->data['Certificate']['document']['name']) {
                    $cvArray = $this->data['Certificate']['document'];
                    $returnedUploadCVArray = $this->PImage->upload($cvArray, UPLOAD_TMP_CERTIFICATE_PATH);
                    $image = $returnedUploadCVArray[0];
                     chmod(UPLOAD_TMP_CERTIFICATE_PATH .$returnedUploadImageArray[0], 0755);
                }
            }
        }



        if ($msgString) {
            echo json_encode(array('status' => 'error', 'message' => strip_tags($msgString)));
            die;
        } else {
            if (in_array($extention, $extentions)) {
                echo json_encode(array('status' => 'success', 'message' => '', 'image' => $image, 'type' => 'image'));
            } else {
                echo json_encode(array('status' => 'success', 'message' => '', 'image' => $image, 'type' => 'doc'));
            }
        }
        exit;
    }

    public function deleteCertificacte($slug = null) {
        $this->layout = '';
        $cerInfo = $this->Certificate->findBySlug($slug);
        $this->Certificate->delete($cerInfo['Certificate']['id']);
        @unlink(UPLOAD_CERTIFICATE_PATH . $cerInfo['Certificate']['document']);
        exit;
    }

    public function listing() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Jobseeker List', true));
        $this->set('candidates_list', 'active');
        $this->userLoginCheck();
        $this->recruiterAccess();

        $userId = $this->Session->read('user_id');
        $isAbleToJob = $this->Plan->checkPlanFeature($userId, 3);
        if ($isAbleToJob['status'] == 0) {
            $this->Session->write('error_msg', $isAbleToJob['message']);
            $this->redirect('/users/myaccount');
        }


        $stateList = $states = $this->State->find('list', array('conditions' => array('State.status' => '1', 'State.country_id' => 1),
            'fields' => array('State.id', 'State.state_name'), 'order' => 'State.state_name asc'));
        $this->set('stateList', $stateList);
        $cityList = array();
        $this->set('cityList', $cityList);

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.name', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('skillList', $skillList);


        $user_id = $this->Session->read('user_id');
        $job_ids = $this->Job->find('list', array('conditions' => array('Job.user_id' => $user_id), 'fields' => array('id')));

        //$candidate_ids = $this->JobApply->find('list', array('conditions' => array('JobApply.job_id' => $job_ids), 'fields' => array('user_id'), 'group' => 'JobApply.user_id'));

        $basicCourseList = $this->Course->find('list', array('conditions' => array('Course.type' => 'Basic', 'Course.status' => 1), 'order' => 'Course.name ASC'));
        $this->set('basicCourseList', $basicCourseList);
        $this->set('basicspecializationList', array());

        $condition = array('User.user_type' => 'candidate', 'User.status' => 1);
        $separator = array();
        $urlSeparator = array();


        if (!empty($this->data)) {
//            echo '<pre>';
//             print_r($this->data);
            if (isset($this->data['User']['keyword']) && $this->data['User']['keyword'] != '') {
                $keyword = trim($this->data['User']['keyword']);
            }

            /*   if (isset($this->data['User']['state_id']) && $this->data['User']['state_id'] != '') {
              $stateId = trim($this->data['User']['state_id']);
              }
              if (isset($this->data['User']['city_id']) && $this->data['User']['city_id'] != '') {
              $cityId = trim($this->data['User']['city_id']);
              } */
            if (isset($this->data['User']['location']) && $this->data['User']['location'] != '') {
                $location = trim($this->data['User']['location']);
                $location = str_replace(',', '', $location);
            }

            if (isset($this->data['User']['total_exp']) && $this->data['User']['total_exp'] != '') {
                $total_exp = trim($this->data['User']['total_exp']);
            }
            if (isset($this->data['User']['exp_salary']) && $this->data['User']['exp_salary'] != '') {
                $exp_salary = trim($this->data['User']['exp_salary']);
            }
            if (isset($this->data['User']['skills']) && $this->data['User']['skills'] != '') {
                $skills = $this->data['User']['skills'];
            }
            if (isset($this->data['User']['basic_course_id']) && $this->data['User']['basic_course_id'] != '') {
                $basic_course_id = trim($this->data['User']['basic_course_id']);
            }
            if (isset($this->data['User']['basic_specialization_id']) && $this->data['User']['basic_specialization_id'] != '') {
                $basic_specialization_id = trim($this->data['User']['basic_specialization_id']);
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['keyword']) && $this->params['named']['keyword'] != '') {
                $keyword = urldecode(trim($this->params['named']['keyword']));
            }

            /*    if (isset($this->params['named']['stateId']) && $this->params['named']['stateId'] != '') {
              $stateId = urldecode(trim($this->params['named']['stateId']));
              }
              if (isset($this->params['named']['cityId']) && $this->params['named']['cityId'] != '') {
              $cityId = urldecode(trim($this->params['named']['cityId']));
              }

             */
            if (isset($this->params['named']['location']) && $this->params['named']['location'] != '') {
                $location = urldecode(trim($this->params['named']['location']));
            }

            if (isset($this->params['named']['total_exp']) && $this->params['named']['total_exp'] != '') {
                $total_exp = urldecode(trim($this->params['named']['total_exp']));
            }
            if (isset($this->params['named']['exp_salary']) && $this->params['named']['exp_salary'] != '') {
                $exp_salary = urldecode(trim($this->params['named']['exp_salary']));
            }
            if (isset($this->params['named']['skills']) && $this->params['named']['skills'] != '') {
                $skills = explode('-', urldecode(trim($this->params['named']['skills'])));
            }
            if (isset($this->params['named']['basic_course_id']) && $this->params['named']['basic_course_id'] != '') {
                $basic_course_id = urldecode(trim($this->params['named']['basic_course_id']));
            }
            if (isset($this->params['named']['basic_specialization_id']) && $this->params['named']['basic_specialization_id'] != '') {
                $basic_specialization_id = urldecode(trim($this->params['named']['basic_specialization_id']));
            }
        }


        $order = 'User.id Desc';
        $fields = array('User.first_name', 'User.last_name', 'User.slug', 'User.id', 'User.email_address', 'User.skills', 'User.profile_image', 'User.contact', 'User.skills', 'Location.id', 'Location.name', 'User.total_exp');
        if (isset($total_exp) && $total_exp != '') {
            $separator[] = 'total_exp:' . $total_exp;
            $expArray = explode('-', $total_exp);
            $condition[] = array('User.total_exp >= ' => $expArray[0], 'User.total_exp <= ' => $expArray[1]);
            $this->set('total_exp', $total_exp);
        }
        if (isset($exp_salary) && $exp_salary != '') {
            $separator[] = 'exp_salary:' . $exp_salary;
            $expArray = explode('-', $exp_salary);
            $condition[] = array('User.exp_salary >= ' => $expArray[0], 'User.exp_salary <= ' => $expArray[1]);
            $this->set('exp_salary', $exp_salary);
        }


        if (isset($skills) && $skills != '') {

            $skillsText = implode('-', $skills);
            $skillsTextField = implode(' ', $skills);
            $temCnt = array();
            foreach ($skills as $val) {
                $temCnt[] = "(FIND_IN_SET('$val', User.skills))";
            }
            $condition[] = "(" . implode(' OR ', $temCnt) . ")";
            $separator[] = 'skills:' . urlencode($skillsText);
            $this->set('skills', $skills);

            $order = 'score DESC';
            $fields[] = "MATCH (skills) AGAINST ('$skillsTextField') AS score";
        }


        if (isset($basic_course_id) && $basic_course_id != '') {
            $separator[] = 'basic_course_id:' . $basic_course_id;
            $this->set('basic_course_id', $basic_course_id);

            $eduCnt = array('Education.basic_course_id' => $basic_course_id);
            if (isset($basic_specialization_id) && $basic_specialization_id != '') {
                $separator[] = 'basic_specialization_id:' . $basic_specialization_id;
                $this->set('basic_specialization_id', $basic_specialization_id);
                $eduCnt[] = array('Education.basic_specialization_id' => $basic_specialization_id);
            }

            $candidate_ids = $this->Education->find('list', array('conditions' => $eduCnt, 'fields' => array('user_id'), 'group' => 'Education.user_id'));
            $condition[] = array('User.id' => $candidate_ids);
        }
        if (isset($keyword) && $keyword != '') {
            $separator[] = 'keyword:' . urlencode($keyword);
            $keyword = str_replace('_', '\_', $keyword);
            $condition[] = " (`User`.`first_name` LIKE '%" . addslashes($keyword) . "%' or concat(`User.first_name`,' ',`User.last_name`) LIKE '%" . addslashes($keyword) . "%' or `User`.`last_name` LIKE '%" . addslashes($keyword) . "%' or `User`.`company_about` LIKE '%" . addslashes($keyword) . "%') ";
            $keyword = str_replace('\_', '_', $keyword);
            $this->set('keyword', $keyword);
        }

        /*  if (isset($stateId) && $stateId != '') {
          $separator[] = 'stateId:' . urlencode($stateId);
          $stateId = str_replace('_', '\_', $stateId);
          $condition[] = " (`User`.`state_id` = $stateId) ";
          $stateId = str_replace('\_', '_', $stateId);
          $this->set('stateId', $stateId);
          }
          if (isset($cityId) && $cityId != '') {
          $separator[] = 'cityId:' . urlencode($cityId);
          $cityId = str_replace('_', '\_', $cityId);
          $condition[] = " (`User`.`city_id` = $cityId) ";
          $cityId = str_replace('\_', '_', $cityId);
          $this->set('cityId', $cityId);
          } */

        if (isset($location) && $location != '') {
            $separator[] = 'location:' . urlencode($location);
            $location = str_replace('_', '\_', $location);
            //$condition[] = "(FIND_IN_SET($location, User.pre_location))";
            $condition[] = array("MATCH(pre_location) AGAINST ('$location' IN BOOLEAN MODE)");

            $location = str_replace('\_', '_', $location);
            $this->set('location', $location);
        }





        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
//        echo '<pre>';
//        print_r($condition);
        $this->paginate['User'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order, 'fields' => $fields);

        //pr($this->paginate('User'));exit;

        $this->set('candidates', $this->paginate('User'));
        //pr($this->paginate('Job'));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'candidates';
            $this->render('listing');
        }
    }

    public function profile($slug = null) {
        $this->layout = "client";
        $this->set('candidates_list', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Jobseeker Profile', true));
        $this->userLoginCheck();
        $this->recruiterAccess();

        $this->User->bindModel(array(
            'hasMany' => array(
                'Education' => array(
                    'className' => 'Education',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
                'Experience' => array(
                    'className' => 'Experience',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
            ),
        ));

        $userDetails = $this->User->findBySlug($slug);
        $this->set('userdetails', $userDetails);

        $interest_categories = $userDetails['User']['interest_categories'];
        if ($interest_categories) {
            $condition[] = " (Category.id IN ($interest_categories ) )";
            $Categories_array = $this->Category->find('list', array('conditions' => $condition, 'order' => array('Category.name' => 'asc')));
            if (!empty($Categories_array)) {
                $this->set('interestCategories', implode(', ', $Categories_array));
            } else {
                $this->set('interestCategories', '');
            }
        } else {
            $this->set('interestCategories', '');
        }

        $showOldImages = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userDetails['User']['id'], 'type' => 'image')));
        $this->set('showOldImages', $showOldImages);

        $showOldDocs = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userDetails['User']['id'], 'type' => 'doc')));
        $this->set('showOldDocs', $showOldDocs);
    }

    public function addVideoCv(){
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Add Video CV');
        $this->set('videocv',true);

        $this->userLoginCheck();
        $this->candidateAccess();
        $this->set('videocv', 'active');
        
        $userDetails = $this->User->findById($this->Session->read("user_id"));
       
        if($this->data)
        {
            // 
            global $extentions_video;  
            $msgString = '';
            if(empty($this->data["User"]["old_video"])){
                if(empty($this->data["User"]["video"]["name"])){
                    $msgString .= "- Video CV Required";
                }
            }
            if ($this->data["User"]["video"]["name"]) {
                $getextention = $this->PImage->getExtension($this->data['User']['video']['name']);
                $extention = strtolower($getextention);
                if ($this->data['User']['video']['size'] > '20971520') {
                    $msgString .= "- Maximum Size is 20 MB";
                } elseif (!in_array($extention, $extentions_video)) {
                    $msgString .= "- $extention is not a valid video extension";
                }
            }
            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
            } else {
                // print_r($this->data);exit;
                $this->User->id = $this->Session->read("user_id");
                $getextention = $this->PImage->getExtension($this->data['User']['video']['name']);
                $extention = strtolower($getextention);
                if (in_array($extention, $extentions_video)) {
//                    $imageArray = $imageData;
                    $imageArray = $this->data['User']['video'];
                    $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                    $toReplace = "-";
                    $returnedUploadVideoArray = $this->PImage->upload($imageArray, UPLOAD_VIDEO_PATH);
                    $image = $returnedUploadVideoArray[0];
                    
                    // chmod(UPLOAD_VIDEO_PATH .$returnedUploadVideoArray[0], 0755);
                    $this->request->data['User']['video'] = $image;
                    @unlink(UPLOAD_VIDEO_PATH . $userDetails['User']['video']);
                } else {
                    $this->request->data['User']['video'] = $userDetails['User']['video'];
                }
                    // print_r($this->request->data['User']['video']);exit;
                    $this->User->id =  $this->Session->read("user_id");
                    $this->User->saveField('video',$this->request->data['User']['video']);
                    if(!empty($this->data['User']['video_text'])){
                        $this->User->saveField('video_text',$this->data['User']['video_text']);
                    }
                    $this->User->saveField('video_status',0);
                    $this->Session->write('success_msg', 'Video Uploaded Successfully!!');
             }
             $this->redirect('/candidates/addvideocv');
        }else{
            $this->User->id  = $this->Session->read("user_id");
            $this->data = $this->User->read();
        }
    }

    public function uploadCvLogin(){
        // print_r($this->data);exit;
        $msgString= '';
        // print_r($this->data['Job']['docs']);exit;
        if ($this->data['Job']['docs']) {
            
            $getextention = $this->PImage->getExtension($this->data['Job']['docs']['name']);
            $extention = strtolower($getextention);
            global $extentions_doc;
            // print_r($extentions_doc);exit;
            if ($this->data['Job']['docs']['size'] > '20971520') {
                $msgString .= "- Max file size upload is 20MB.<br>";
            } elseif (!in_array($extention, $extentions_doc)) {
                $msgString .= "- Not Valid Extention.<br>";
            }
        }
        if (isset($msgString) && $msgString != '') {
            $this->Session->setFlash($msgString, 'error_msg');
            exit;
        }else{
            $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
            $toReplace = "-";
            str_replace($specialCharacters, $toReplace, $this->data['Job']['docs']);
            str_replace("&", "and", $this->data['Job']['docs']);
            if ($this->data['Job']['docs']) {
                $cvArray = $this->data['Job']['docs'];
                $returnedUploadCVArray = $this->PImage->upload($cvArray, UPLOAD_TMP_CERTIFICATE_PATH);
                $image = $returnedUploadCVArray[0];
                // print_r($image);exit;
                if(!empty($this->Session->read('user_id'))){
                    copy(UPLOAD_TMP_CERTIFICATE_PATH .$image , UPLOAD_CERTIFICATE_PATH .$image);
                    $this->request->data['Certificate']['user_id'] = $this->Session->read('user_id');
                    $this->request->data['Certificate']['document'] = $image;
                    $this->request->data['Certificate']['type'] = 'doc';
                    $this->request->data['Certificate']['slug'] = 'doc-' . $this->Session->read('user_id') . time() . rand(111, 99999);
                    $this->Certificate->save($this->request->data['Certificate']);
                    @unlink(UPLOAD_TMP_CERTIFICATE_PATH . $image);
                }else{
                    $this->Session->write("guest_user_cv",$image);
                }
                chmod(UPLOAD_TMP_CERTIFICATE_PATH .$returnedUploadCVArray[0], 0755);
                exit;
            }
        }
    }

    public function publicprofile($slug = null) {

        $this->layout = "client";
        $this->set('candidates_list', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";



        $this->User->bindModel(array(
            'hasMany' => array(
                'Education' => array(
                    'className' => 'Education',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
                'Experience' => array(
                    'className' => 'Experience',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
            ),
        ));

        $userDetails = $this->User->findBySlug($slug);
        $this->set('userdetails', $userDetails);

        $interest_categories = $userDetails['User']['interest_categories'];
        if ($interest_categories) {
            $condition[] = " (Category.id IN ($interest_categories ) )";
            $Categories_array = $this->Category->find('list', array('conditions' => $condition, 'order' => array('Category.name' => 'asc')));
            if (!empty($Categories_array)) {
                $this->set('interestCategories', implode(', ', $Categories_array));
            } else {
                $this->set('interestCategories', '');
            }
        } else {
            $this->set('interestCategories', '');
        }

        $this->set('title_for_layout', $title_for_pages . "View Profile of " . $userDetails['User']['first_name']);
        //  pr($userDetails); exit;
        $showOldImages = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userDetails['User']['id'], 'type' => 'image')));
        $this->set('showOldImages', $showOldImages);

        $showOldDocs = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userDetails['User']['id'], 'type' => 'doc')));
        $this->set('showOldDocs', $showOldDocs);
        $couses = "";
        $experience = "";
        $count = 1;

        $cond = array();
        $condcc = array();
        $useridc = array();
        $condccc = array();
        $couses = array();
        $experience = array();

        // pr($userDetails);
        if (count($userDetails['Education']) > 0) {
            foreach ($userDetails['Education'] as $education) {
                $couses[] = $education['basic_course_id'];
            }
        }
        if (count($userDetails['Experience']) > 0) {
            foreach ($userDetails['Experience'] as $Experiencec) {
                $experience[] = $Experiencec['designation'];
            }
        }

        //  pr($userDetails); exit;
        if ($couses) {
            $c = implode(',', $couses);
            $condcc[] = '(Education.id  IN (' . $c . ') AND Education.user_id <> ' . $userDetails['User']['id'] . ')';

            $userid = $this->Education->find('list', array('conditions' => $condcc, 'limit' => 5, 'fields' => array('Education.user_id')));

            if ($userid) {

                $useridc = implode(',', $userid);
                $cond[] = '(User.id IN (' . $useridc . '))';
            }
        }

        if ($experience) {
            $c = implode(',', $experience);
            $condccc[] = '(Experience.designation LIKE "%' . $c . '%" AND Experience.user_id <> ' . $userDetails['User']['id'] . ')';
            //   pr($condcc); exit;
            $userid = $this->Experience->find('list', array('conditions' => $condccc, 'fields' => array('Experience.user_id')));

            if ($userid) {

                $useridc = implode(',', $userid);
                $cond[] = '(User.id IN (' . $useridc . '))';
            }
        }

        if (empty($cond)) {
            $cond[] = '(User.id IN (0))';
        }

        $cond[] = '(User.category_id  = ' . $userDetails['User']['category_id'] . ' OR User.skills  LIKE "%' . $userDetails['User']['skills'] . '%" AND User.id <> ' . $userDetails['User']['id'] . ')';
        //pr($cond);
        $similarCandidate = $this->User->find('all', array('conditions' => $cond, 'limit' => 5, 'order' => 'rand()'));
        $this->set('similarCandidate', $similarCandidate);
        // pr($cond); exit;
    }

    public function addToFavorite($slug = null) {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Add To Favorite', true));
        $this->userLoginCheck();
        $this->recruiterAccess();
        $user_id = $this->Session->read('user_id');
        $candidate_id = $this->User->field('id', array('User.slug' => $slug));
        $msgString = '';
        if ($candidate_id == '') {
            $msgString .= "- Invalid URL.<br>";
        }

        if (isset($msgString) && $msgString != '') {
            $this->Session->setFlash($msgString, 'error_msg');
        } else {
            $this->request->data['Favorite']['user_id'] = $user_id;
            $this->request->data['Favorite']['candidate_id'] = $candidate_id;

            if ($this->Favorite->save($this->data)) {
                $this->Session->write('success_msg', __d('controller', 'Added to favorite list.', true));
                //$this->redirect('/candidates/profile/' . $slug);
            }
        }
        $this->redirect($this->referer());
    }

    public function favorite() {
        $this->layout = "client";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Favorite Candidate List', true));
        $this->userLoginCheck();
        $this->recruiterAccess();
        $this->set('favoriteList', 'active');
        $userId = $this->Session->read("user_id");
        $condition = array('Favorite.user_id' => $userId);
        $separator = array();
        $urlSeparator = array();

        $order = 'Favorite.id Desc';

        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['Favorite'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('favorites', $this->paginate('Favorite'));
        //pr($this->paginate('Favorite'));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'candidates';
            $this->render('favorite');
        }
    }

    public function deleteFavoriteList($id = null) {
        if ($id != '') {
            $this->Favorite->delete($id);
            $this->Session->write('success_msg', __d('controller', 'Favorite Jobseeker deleted successfully.', true));
        }
        $this->redirect('/candidates/favorite');
    }

    public function deleteCvDocument($cv = null) {
        $id = $this->Session->read("user_id");
        if ($id > 0) {
            $cv = $this->User->field('cv', array('id' => $id));
            $cnd1 = array("User.id = '$id'");
            $this->User->updateAll(array('User.cv' => "''"), $cnd1);
            @unlink(UPLOAD_CV_PATH . $cv);
            $this->Session->write('success_msg', 'Cv document deleted successfully.');
            $this->redirect('/candidates/editProfile');
        }
    }

    public function deleteVideo($video = null) {
        $id = $this->Session->read("user_id");
        if ($id > 0) {
            $video = $this->User->field('video', array('id' => $id));
            $cnd1 = array("User.id = '$id'");
            $this->User->updateAll(array('User.video' => "''"), $cnd1);
            @unlink(UPLOAD_VIDEO_PATH . $video);
            $this->Session->write('success_msg', __d('controller', 'Video deleted successfully.', true));
            $this->redirect(Router::url( $this->referer(), true ));
        }
    }

    /* --------------------------- */
    /* ---Edit Education starts--- */
    /* --------------------------- */

    public function editEducation($status = null) {
        $this->layout = "client";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";

        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Edit Education', true));
        $this->set('editeducation', 'active');


        $this->userLoginCheck();
        $this->candidateAccess();
        $msgString = '';

        $userId = $this->Session->read("user_id");
        $userDetails = $this->User->findById($userId);


        $user_type = $this->Session->read("user_type");

        $basicCourseList = $this->Course->find('list', array('conditions' => array('Course.type' => 'Basic', 'Course.status' => 1), 'order' => 'Course.name ASC'));
        $this->set('basicCourseList', $basicCourseList);

        $specilyList = '';
        $this->set('specilyList1', $specilyList);




        if ($this->data) {
            foreach ($this->data["Education"] as $education) {
                if (empty($education["basic_course_id"])) {
                    $msgString .= "- Course name is required field.<br>";
                }
                // if (empty($education["basic_specialization_id"])) {
                //$msgString .= "- Select specialization is required field.<br>";
                // }
                if (empty($education["basic_university"])) {
                    $msgString .= "- University name is required field.<br>";
                }
                if (empty($education["basic_year"])) {
                    $msgString .= "- Graduation year is required field.<br>";
                }
            }



            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
            } else {

                if ($this->data['Education']) {
                    foreach ($this->data['Education'] as $education) {

                        $this->request->data['Education']['user_id'] = $this->Session->read("user_id");
                        $this->request->data['Education']['user_type'] = $user_type;
                        $this->request->data['Education']['education_type'] = 'Basic';
                        $this->request->data['Education']['basic_course_id'] = $education['basic_course_id'];
                        //pr($education); exit;
                        if (isset($education['basic_specialization_id']) && !empty($education['basic_specialization_id'])) {
                            $this->request->data['Education']['basic_specialization_id'] = $education['basic_specialization_id'];
                        } else {
                            $this->request->data['Education']['basic_specialization_id'] = '0';
                        }


                        $this->request->data['Education']['basic_university'] = $education['basic_university'];
                        $this->request->data['Education']['basic_year'] = $education['basic_year'];

                        if ($education['id']) {
                            $this->request->data['Education']['id'] = $education['id'];
                        } else {
                            $this->request->data['Education']['id'] = '';
                        }

                        //pr($this->data['Education']);
                        if (!empty($this->data['Education']['basic_course_id']) || !empty($this->data['Education']['basic_university']) || !empty($this->data['Education']['basic_year'])) {
                            $this->Education->save($this->data);
                            $this->Session->write('success_msg', __d('controller', 'Education information updated successfully.', true));
                        }
                    }
                }
                //exit;
                $this->redirect('/candidates/editEducation');
            }
        } else {

            $this->User->id = $userId;
            $this->data = $this->Education->read();

            $eduDetails = $this->Education->find('all', array('conditions' => array('Education.user_id' => $this->User->id)));
            $this->set('eduDetails', $eduDetails);

            if ($eduDetails) {
                foreach ($eduDetails as $eduDetail) {
                    $eduDetail['Education']['basic_course_id'] = $eduDetail['Education']['basic_course_id'];
                    //$eduDetail['Education']['basic_specialization_id'] = $eduDetail['Education']['basic_specialization_id'];
                    $eduDetail['Education']['basic_university'] = $eduDetail['Education']['basic_university'];
                    $eduDetail['Education']['basic_year'] = $eduDetail['Education']['basic_year'];
                    $eduDetail['Education']['user_id'] = $this->User->id;
                    $eduDetail['Education']['user_type'] = $user_type;
                    $eduDetail['Education']['education_type'] = 'Basic';
                    $eduDetail['Education']['id'] = $eduDetail['Education']['id'];

                    $this->request->data['Education'][] = $eduDetail['Education'];
                }
            }
        }
    }

    public function deleteeducation($id = NULL) {
        $this->layout = "";
        $this->Education->delete($id);
        exit;
    }

    public function specilyList($cc = null) {
        $this->layout = "";
        //$valueFind = 0;
        if (isset($this->data['Education'][$cc]['basic_course_id'])) {
            $courseId = $this->data['Education'][$cc]['basic_course_id'];
            $specily = 'basic';
//            if ($this->data['Education'][$cc]['basic_course_id'] == 1) {
//                $valueFind = 1;
//            }
            /* } elseif (isset($this->data['User'][$cc]['post_course_id'])) {
              $courseId = $this->data['User'][$cc]['post_course_id'];
              $specily = 'post';
              } elseif ($this->data['User'][$cc]['doctor_course_id']) {
              $courseId = $this->data['User'][$cc]['doctor_course_id'];
              $specily = 'doctor';
              } */

//        if ($valueFind == 1) {
//            $this->set('viewList', '1');
//        } else {
            $specilyList = $this->Specialization->find('list', array('conditions' => array('Specialization.status' => 1, 'Specialization.course_id' => $courseId), 'order' => array('Specialization.name' => 'asc')));
            $this->set('specily', $specily);
            $this->set('specilyList', $specilyList);
            $this->set('cc', $cc);
        }
    }

    public function getSpecialization($courseValue, $cc) {
        $this->layout = "";
        $specily = 'basic';
        if (!empty($courseValue)) {
            $specializationList = $this->Specialization->find('list', array('conditions' => array('Specialization.status' => 1, 'Specialization.course_id' => $courseValue), 'order' => array('Specialization.name' => 'asc')));

            $this->set('specilyList', $specializationList);
            $this->set('cc', $cc);
            $this->set('specily', $specily);
        }
    }

    public function openeducation($cc = null) {
        $this->layout = '';
        $this->set('cc', $cc);
        $basicCourseList = $this->Course->find('list', array('conditions' => array('Course.type' => 'Basic', 'Course.status' => 1), 'order' => 'Course.name ASC'));
        $this->set('basicCourseList', $basicCourseList);
        $specilyList = '';
        $this->set('specilyList1', $specilyList);
        $this->set('specilyList2', $specilyList);
        $this->set('specilyList3', $specilyList);
    }

    /* ---Edit Education ends--- */

    /* ----edit experience-- */

    public function editExperience($status = null) {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";

        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Edit Experience', true));
        $this->set('editexperience', 'active');


        $this->userLoginCheck();
        $this->candidateAccess();
        $msgString = '';

        $userId = $this->Session->read("user_id");
        $userDetails = $this->User->findById($userId);


        if ($this->data) {

            foreach ($this->data["Experience"] as $experience) {
                if (empty($experience["industry"])) {
                    $msgString .= __d('controller', 'Industry is required field.', true) . "<br>";
                }
                if (empty($experience["company_name"])) {
                    $msgString .= __d('controller', 'Company name is required field.', true) . "<br>";
                }
                if (empty($experience["role"])) {
                    $msgString .= __d('controller', 'Role is required field.', true) . "<br>";
                }
                if (empty($experience["designation"])) {
                    $msgString .= __d('controller', 'Designation is required field.', true) . "<br>";
                }

                if (!empty($experience["from_month"]) && !empty($experience["to_month"])) {
                    if ($experience["from_year"] > $experience["to_year"]) {
                        $msgString .= "- To year must be greater than from year<br>";
                    } else if ($experience["from_year"] == $experience["to_year"]) {
                        if ($experience["to_month"] <= $experience["from_month"]) {
                            $msgString .= __d('controller', 'to month must be greater than from month in same year', true) . "<br>";
                        }
                    }
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
            } else {

                if ($this->data['Experience']) {
                    foreach ($this->data['Experience'] as $experience) {
                        //pr($experience); exit;
                        $this->request->data['Experience']['user_id'] = $this->Session->read("user_id");
                        $this->request->data['Experience']['industry'] = $experience['industry'];
                        $this->request->data['Experience']['company_name'] = $experience['company_name'];
                        $this->request->data['Experience']['functional_area'] = $experience['functional_area'];
                        $this->request->data['Experience']['role'] = $experience['role'];
                        $this->request->data['Experience']['designation'] = $experience['designation'];
                        $this->request->data['Experience']['ctclakhs'] = '0';
                        $this->request->data['Experience']['ctcthousand'] = '0';
                        $this->request->data['Experience']['from_month'] = $experience['from_month'];
                        $this->request->data['Experience']['from_year'] = $experience['from_year'];
                        $this->request->data['Experience']['to_month'] = $experience['to_month'];
                        $this->request->data['Experience']['to_year'] = $experience['to_year'];
                        $this->request->data['Experience']['job_profile'] = $experience['job_profile'];
                        $this->request->data['Experience']['status'] = 1;
                        //  $this->request->data['Experience']['created'] = data("Y-m-d");
                        $this->request->data['Experience']['slug'] = 'exp-' . $this->Session->read("user_id") . '-' . time();


                        if ($experience['id']) {
                            $this->request->data['Experience']['id'] = $experience['id'];
                        } else {
                            $this->request->data['Experience']['id'] = '';
                        }

                        //pr($this->data['Education']);
                        if (!empty($this->data['Experience']['company_name']) || !empty($this->data['Experience']['role']) || !empty($this->data['Experience']['designation'])) {
                            $this->Experience->save($this->data);
                            $this->Session->write('success_msg', __d('controller', 'Experience information updated successfully.', true));
                        }
                    }
                }
                //exit;
                $this->redirect('/candidates/editExperience');
            }
        } else {

            $this->User->id = $userId;
            $this->data = $this->Experience->read();

            $expDetails = $this->Experience->find('all', array('conditions' => array('Experience.user_id' => $this->User->id)));
            //pr($expDetails); exit;
            $this->set('expDetails', $expDetails);

            if ($expDetails) {
                foreach ($expDetails as $expDetail) {
                    $expDetail['Experience']['industry'] = $expDetail['Experience']['industry'];
                    $expDetail['Experience']['company_name'] = $expDetail['Experience']['company_name'];
                    $expDetail['Experience']['functional_area'] = $expDetail['Experience']['functional_area'];
                    $expDetail['Experience']['role'] = $expDetail['Experience']['role'];


                    $expDetail['Experience']['designation'] = $expDetail['Experience']['designation'];
                    $expDetail['Experience']['ctclakhs'] = '0';
                    $expDetail['Experience']['ctcthousand'] = '0';
                    $expDetail['Experience']['from_month'] = $expDetail['Experience']['from_month'];
                    $expDetail['Experience']['from_year'] = $expDetail['Experience']['from_year'];
                    $expDetail['Experience']['to_month'] = $expDetail['Experience']['to_month'];
                    $expDetail['Experience']['to_year'] = $expDetail['Experience']['to_year'];
                    $expDetail['Experience']['job_profile'] = $expDetail['Experience']['job_profile'];
                    $expDetail['Experience']['status'] = '1';
                    $expDetail['Experience']['job_profile'] = $expDetail['Experience']['job_profile'];
                    $expDetail['Experience']['user_id'] = $this->User->id;

                    $expDetail['Experience']['id'] = $expDetail['Experience']['id'];

                    $this->request->data['Experience'][] = $expDetail['Experience'];
                }
            }
        }
    }

    public function deleteexperience($id = NULL) {
        $this->layout = "";
        $this->Experience->delete($id);
        exit;
    }

    public function openexperience($cc = null) {
        $this->layout = '';
        $this->set('cc', $cc);
    }

    /* ----edit Professional-- */

    public function editProfessional($status = null) {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";

        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Edit Professional Registration', true));
        $this->set('editprofessional', 'active');


        $this->userLoginCheck();
        $this->candidateAccess();
        $msgString = '';

        $userId = $this->Session->read("user_id");
        $userDetails = $this->User->findById($userId);


        if ($this->data) {

            foreach ($this->data["Professional"] as $experience) {
                if (empty($experience["registration"])) {
                    $msgString .= __d('controller', 'Professional Registration is required field.', true) . "<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
            } else {

                if ($this->data['Professional']) {
                    foreach ($this->data['Professional'] as $professional) {

                        $this->request->data['Professional']['user_id'] = $this->Session->read("user_id");
                        $this->request->data['Professional']['registration'] = $professional['registration'];
                        $this->request->data['Professional']['status'] = 1;
                        $this->request->data['Professional']['slug'] = 'pro-' . $this->Session->read("user_id") . '-' . time();


                        if ($professional['id']) {
                            $this->request->data['Professional']['id'] = $professional['id'];
                        } else {
                            $this->request->data['Professional']['id'] = '';
                        }

                        if (!empty($this->data['Professional']['registration'])) {
//                           echo '<pre>'; print_r($this->data);die;                          
                            $this->Professional->save($this->data);
                            $this->Session->write('success_msg', __d('controller', 'Professional Registration updated successfully.', true));
                        }
                    }
                }
                //exit;
                $this->redirect('/candidates/editProfessional');
            }
        } else {

            $this->User->id = $userId;
            $this->data = $this->Professional->read();

            $proDetails = $this->Professional->find('all', array('conditions' => array('Professional.user_id' => $this->User->id)));
            $this->set('proDetails', $proDetails);

            if ($proDetails) {
                foreach ($proDetails as $proDetail) {
                    $expDetail['Professional']['registration'] = $proDetail['Professional']['registration'];
                    $expDetail['Professional']['status'] = '1';
                    $expDetail['Professional']['user_id'] = $this->User->id;

                    $expDetail['Professional']['id'] = $proDetail['Professional']['id'];

                    $this->request->data['Professional'][] = $proDetail['Professional'];
                }
            }
        }
    }

    public function deleteprofessional($id = NULL) {
        $this->layout = "";
        $this->Professional->delete($id);
        exit;
    }

    public function openprofessional($cc = null) {
        $this->layout = '';
        $this->set('cc', $cc);
    }

    public function companyprofile($slug) {


        $this->layout = "client";
        $this->set('candidates_list', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";


        $userDetails = $this->User->findBySlug($slug);
        $this->set('userdetails', $userDetails);
        $this->set('title_for_layout', $title_for_pages . "View Profile of " . $userDetails['User']['company_name']);

        $condition = array('Job.status' => 1, 'Job.user_id' => $userDetails['User']['id'], 'Job.expire_time >=' => time());
        $condition[] = array('(Job.category_id != 0)');

        $jobsof = $this->Job->find('all', array('conditions' => $condition));
        $this->set('jobsof', $jobsof);
    }

    public function makecv() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Make a CV');

        $this->userLoginCheck();
        $this->candidateAccess();

        $this->User->bindModel(array(
            'hasMany' => array(
                'Education' => array(
                    'className' => 'Education',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
                'Experience' => array(
                    'className' => 'Experience',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
            ),
        ));

        $userId = $this->Session->read("user_id");
        $userdetail = $this->User->findById($userId);

        $this->set('userdetail', $userdetail);
        $this->set('makecv', 'active');
    }

    public function generatecv() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Make a CV');

        $this->userLoginCheck();
        $this->candidateAccess();

        $this->User->bindModel(array(
            'hasMany' => array(
                'Education' => array(
                    'className' => 'Education',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
                'Experience' => array(
                    'className' => 'Experience',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
            ),
        ));

        $userId = $this->Session->read("user_id");
        $userdetail = $this->User->findById($userId);

        $this->set('userdetail', $userdetail);
        $this->set('myaccount', 'active');

        $this->set('name', ucfirst($userdetail['User']['first_name']) . '_' . $userdetail['User']['last_name'] . '_CV');
    }

    public function generatecvdoc() {
//        phpinfo();die;
        $this->layout = "";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Make a CV');

        $this->userLoginCheck();
        $this->candidateAccess();

        $this->User->bindModel(array(
            'hasMany' => array(
                'Education' => array(
                    'className' => 'Education',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
                'Experience' => array(
                    'className' => 'Experience',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
            ),
        ));

        $userId = $this->Session->read("user_id");
        $userdetail = $this->User->findById($userId);

        $this->set('userdetail', $userdetail);
        $this->set('myaccount', 'active');
        $filename = ucfirst($userdetail['User']['first_name']) . '_' . $userdetail['User']['last_name'] . '_CV';
        $username = ucwords($userdetail['User']['first_name'] . ' ' . $userdetail['User']['last_name']);
        $this->set('name', ucfirst($userdetail['User']['first_name']) . '_' . $userdetail['User']['last_name'] . '_CV');
        $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $userdetail['User']['profile_image'];
        if (file_exists($path) && !empty($userdetail['User']['profile_image'])) {
            $logoPath = DISPLAY_FULL_PROFILE_IMAGE_PATH . $userdetail['User']['profile_image'];
        } else {
            $logoPath = HTTP_IMAGE . '/front/no_image_user.png';
        }



        require_once(BASE_PATH . '/app/webroot/doclib/vendor/autoload.php');
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $cellRowSpan = array('vMerge' => 'restart');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellColSpan = array('gridSpan' => 2);

        $textLeft = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT, 'spaceAfter' => 4);
        $textRight = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT, 'spaceAfter' => 4);
        $textCenter = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 4);
        $textJustify = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 4);

        $txLeftb4space = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT, 'spaceAfter' => 4); // 'spaceBefore'=>2
        $txJustifyb4space = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 4); //, 'spaceBefore'=>2

        $listStyleEmptyBullet = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_EMPTY);
        $listStyleNumber = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER);

        $fbkbold11 = array('size' => 11, 'bold' => true, 'color' => '000000');
        $fbrnbold11 = array('size' => 11, 'bold' => true, 'color' => '6d0a13');
        $lightbold10 = array('size' => 10, 'bold' => true, 'color' => '0b0a0a');
        $fbkbold10 = array('size' => 10, 'bold' => true); //'color'=>'565555'
        $fbknormal10 = array('size' => 11); //, 'color'=>'565555'
        $fbrnbold10 = array('size' => 10, 'bold' => true, 'color' => '0c0303');

        $linespace = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT, 'spaceAfter' => 0);


        $section = $phpWord->addSection();
        $table = $section->addTable(array('align' => 'left'));

        $table->addRow();
        $table->addCell(2500, array())->addText(__d('user', 'Name and surname', true) . " : ", array('size' => 11, 'bold' => true));
        $table->addCell(5800, array())->addText($username, array('size' => 11, 'bold' => FALSE));
        $table->addCell(2000, $cellRowSpan)->addImage($logoPath, array('width' => '100', 'height' => '100'));
        $section->addText('');
        $table->addRow();
        $table->addCell(2500, array())->addText(__d('user', 'Email Address', true) . " : ", array('size' => 11, 'bold' => true));
        $table->addCell(5800, array())->addText($userdetail['User']['email_address'], array('size' => 11, 'bold' => FALSE));
        $table->addCell(null, $cellRowContinue);
        $section->addText('');
        $table->addRow();
        $table->addCell(2500, array())->addText(__d('user', 'Phone Number', true) . " : ", array('size' => 11, 'bold' => true));
        $table->addCell(5800, array())->addText($userdetail['User']['contact'], array('size' => 11, 'bold' => FALSE));
        $table->addCell(null, $cellRowContinue);
        $section->addText('');
        $table->addRow();
        $table->addCell(2500, array())->addText(__d('user', 'Address', true) . " : ", array('size' => 11, 'bold' => true));
        $table->addCell(5800, array())->addText($userdetail['User']['location'], array('size' => 11, 'bold' => FALSE));
        $table->addCell(null, $cellRowContinue);
        $table = $section->addTable(array('spaceAfter' => 5));
        $table->addRow();
        $table->addCell(8000, array('gridSpan' => 2))->addText(strtoupper(__d('user', 'Experience', true)), array('size' => 11, 'bold' => true));

        if (isset($userdetail['Experience']) && !empty($userdetail['Experience'])) {
            $total_records = count($userdetail['Experience']);
            foreach ($userdetail['Experience'] as $key => $experience) {
                if (isset($experience["from_month"]) && isset($experience["from_year"]) && isset($experience["to_month"]) && isset($experience["to_year"])) {

                    $experience['from_month'] == 1;
                    switch ($experience['from_month']) {
                        case "1":
                            $fromName = __d('user', 'January', true);
                            break;
                        case "2":
                            $fromName = __d('user', 'Febuary', true);
                            break;
                        case "3":
                            $fromName = __d('user', 'March', true);
                            break;
                        case "4":
                            $fromName = __d('user', 'April', true);
                            break;
                        case "5":
                            $fromName = __d('user', 'May', true);
                            break;
                        case "6":
                            $fromName = __d('user', 'June', true);
                            break;
                        case "7":
                            $fromName = __d('user', 'July', true);
                            break;
                        case "8":
                            $fromName = __d('user', 'August', true);
                            break;
                        case "9":
                            $fromName = __d('user', 'September', true);
                            break;
                        case "10":
                            $fromName = __d('user', 'October', true);
                            break;
                        case "11":
                            $fromName = __d('user', 'November', true);
                            break;
                        case "12":
                            $fromName = __d('user', 'Decemeber', true);
                            break;
                        default:
                            $fromName = 'N/A';
                    }

                    $experience['to_month'] == 1;
                    switch ($experience['to_month']) {
                        case "1":
                            $toName = __d('user', 'January', true);
                            break;
                        case "2":
                            $toName = __d('user', 'Febuary', true);
                            break;
                        case "3":
                            $toName = __d('user', 'March', true);
                            break;
                        case "4":
                            $toName = __d('user', 'April', true);
                            break;
                        case "5":
                            $toName = __d('user', 'May', true);
                            break;
                        case "6":
                            $toName = __d('user', 'June', true);
                            break;
                        case "7":
                            $toName = __d('user', 'July', true);
                            break;
                        case "8":
                            $toName = __d('user', 'August', true);
                            break;
                        case "9":
                            $toName = __d('user', 'September', true);
                            break;
                        case "10":
                            $toName = __d('user', 'October', true);
                            break;
                        case "11":
                            $toName = __d('user', 'November', true);
                            break;
                        case "12":
                            $toName = __d('user', 'Decemeber', true);
                            break;
                        default:
                            $toName = 'N/A';
                    }

                    $exp_name = $fromName . '-' . $experience['from_year'] . ' ' . __d('common', 'to', true) . ' ' . $toName . '-' . $experience['to_year'] . ' - ' . $experience['company_name'];
                } else {
                    $exp_name = 'N/A';
                }

                $section->addText('');
                $table->addRow();
                $table->addCell(450);
                $table->addCell(8000)->addText($exp_name, array('size' => 11), $txLeftb4space);
                $table->addRow();
                $table->addCell(450);
                $table->addCell(8000)->addText($experience['role'], array('size' => 11));
                $table->addRow();
                $table->addCell(450);
                $table->addCell(8000)->addText($experience['job_profile'], array('size' => 11));
                if (($key + 1) != $total_records) {
                    $table->addRow();
                    $table->addCell(8000, array('gridSpan' => 2));
                }
            }
        }

        $table = $section->addTable(array('spaceAfter' => 5));
        $table->addRow();

        $table->addCell(8000, array('gridSpan' => 2))->addText(strtoupper(__d('user', 'Education Specialization', true)), array('size' => 11, 'bold' => true));
        if (isset($userdetail['Education']) && !empty($userdetail['Education'])) {
            $total_records = count($userdetail['Education']);
            foreach ($userdetail['Education'] as $key => $education) {
                $couses[] = $education['basic_course_id'];

                $section->addText('');

                $table->addRow();
                $table->addCell(400);
                $table->addCell(8000)->addText($education["basic_year"] . ' – ' . $education["basic_university"], array('size' => 11));
                $specialization = ClassRegistry::init('Specialization')->field('name', array('Specialization.id' => $education['basic_specialization_id']));
                $table->addRow();
                $table->addCell(400);
                $table->addCell(8000)->addText($specialization, array('size' => 11));
                if (($key + 1) != $total_records) {
                    $table->addRow();
                    $table->addCell(8000, array('gridSpan' => 2));
                }
            }
        }
        $table = $section->addTable(array('spaceAfter' => 5));
        $table->addRow();

        $table->addCell(6000)->addText(strtoupper(__d('user', 'Skills', true)), array('size' => 11, 'bold' => true));
        if (isset($userdetail['User']['skills']) && !empty($userdetail['User']['skills'])) {
            $experiences = explode(',', $userdetail['User']['skills']);
            $total_records = count($experiences);
            foreach ($experiences as $key => $experience) {

                $section->addText('');
                $table->addRow();
                $table->addCell(6000, array())->addListItem($experience, 0, $fbknormal10, $listStyleEmptyBullet);
            }
        }
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $filenamedown = "$filename.docx" .
                $objWriter->save(UPLOAD_CV_PATH . "$filename.docx");

        set_time_limit(0);
        $file_path = UPLOAD_CV_PATH . "$filename.docx";
//        $filename = substr($filename, 6);
        $this->Common->output_file($file_path, $filenamedown);
        exit;
    }

    public function specilylistsearch() {
        $this->layout = '';
        $basic_course_id = $this->data['User']['basic_course_id'];

        $basicspecializationList = $this->Specialization->getSpecializationListByCourseId($basic_course_id);
        $this->set('basicspecializationList', $basicspecializationList);
    }

    public function updateskills() {
        $this->layout = '';
        $skillList = $this->Skill->find('all', array('conditions' => array(), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        foreach ($skillList as $val) {
            $name = trim($val['Skill']['name']);
            $this->Skill->updateAll(array('Skill.name' => "'$name'"), array('Skill.id' => $val['Skill']['id']));
        }
        exit;
    }

    public function canupdateskills() {
        $this->layout = '';

        $skillList = $this->Skill->find('list', array('conditions' => array(), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));

        $usersAll = $this->User->find('all', array('conditions' => array('User.skills <>' => ''), 'fields' => array('User.id', 'User.skills'), 'order' => 'User.id ASC'));
        if ($usersAll) {
            foreach ($usersAll as $user) {
                $namearray = explode(',', trim($user['User']['skills']));
                $skillArray = array();
                foreach ($namearray as $key) {
                    $skillArray[] = $skillList[$key];
                }
                $dd = implode(',', $skillArray);
                $this->User->updateAll(array('User.skills' => "'$dd'"), array('User.id' => $user['User']['id']));
            }
        }
        exit;
    }

    public function downloadDocCertificateEmp($filename = null, $userslug = null) {

        $userId = $this->Session->read('user_id');
       $isAbleToJob = $this->Plan->checkPlanFeature($userId, 2);
       // $isAbleToJob = $this->Plan->getcurrentplan($userId, 2);
        // echo '<pre>';
        // print_r($isAbleToJob);exit;
        if ($isAbleToJob['status'] == 0) {
            $this->Session->write('error_msg', $isAbleToJob['message']);
            $this->redirect('/candidates/profile/' . $userslug);
        }

        $userInfo = $this->User->findBySlug($userslug);
        $this->request->data['Download']['user_id'] = $userId;
        $this->request->data['Download']['user_plan_id'] = $isAbleToJob['user_plan_id'];
        $this->request->data['Download']['candidate_id'] = $userInfo['User']['id'];
        $this->request->data['Download']['document_name'] = $filename;
        $this->Download->save($this->data['Download']);

        set_time_limit(0);
        $file_path = UPLOAD_CERTIFICATE_PATH . $filename;
        $filename = substr($filename, 6);
        $this->Common->output_file($file_path, $filename);
        exit;
    }

    /*     * *********************** App function ******************** */

    public function apps_login() {
        $this->layout = '';
        $this->requestAuthentication('POST', 2);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);

        $email_address = $userData['email_address'];
        $password = $userData['password'];
        $type = $userData['type'];
        $device_id = $userData['device_id'];
        $device_type = $userData['device_type'];

        $userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email_address, "User.user_type" => 'candidate')));
        if (is_array($userCheck) && !empty($userCheck) && crypt($password, $userCheck['User']['password']) == $userCheck['User']['password']) {
            if ($userCheck['User']['status'] == 1 && $userCheck['User']['activation_status'] == 1) {
                if ($type == 'candidate') {
                    $payLoad = array(
                        "user_id" => $userCheck['User']['id'],
                        "time" => time()
                    );
                    $token = $this->setToken($payLoad);
                    $userCheck['User']['token'] = $token;
                    $data = $this->logindata($userCheck);
                    $this->User->updateAll(array('User.device_type' => "'$device_type'", 'User.device_id' => "'$device_id'"), array("User.id" => $userCheck['User']['id']));
                    echo $this->successOutputResult('login sucessfully', json_encode($data));
                } else {
                    echo $this->errorOutputResult(__d('controller', 'Invalid email and/or password.', true));
                }
            } else {
                if ($userCheck->activation_status == 0) {
                    $msgString = __d('controller', 'Please check you mail for activation link to activate your account.', true);
                } else {
                    $msgString = __d('controller', 'Your account might have been temporarily disabled. Please contact us for more details.', true);
                }
                echo $this->errorOutputResult($msgString);
            }
        } else {
            echo $this->errorOutputResult(__d('controller', 'Invalid email and/or password.', true));
        }
        exit;
    }

    public function logindata($userCheck) {

        $data = array();
        $data['user_id'] = $userCheck['User']['id'];
        $data['user_type'] = $userCheck['User']['user_type'];
        $data['first_name'] = $userCheck['User']['first_name'];
        $data['last_name'] = $userCheck['User']['last_name'];
        $data['email_address'] = $userCheck['User']['email_address'];
        $data['profile_image'] = $userCheck['User']['profile_image'];
        $data['video'] = $userCheck['User']['video'];
        /* $data['location'] = $userCheck['User']['location'];
          $gender = '';
          if ($userCheck['User']['gender']) {
          if($userCheck['User']['gender'] == 0){
          $gender = 'Male';
          }else{
          $gender = 'Female';
          }
          }
          $data['gender'] = $gender;
          $data['contact'] = $userCheck['User']['contact'];
          $data['pre_location'] = $userCheck['User']['pre_location'];
          $data['skills'] = str_replace(',', ', ', $userCheck['User']['skills']);
          $exp_salary = '';
          if ($userCheck['User']['exp_salary']) {
          $exp_salary = CURR.' '.$userCheck['User']['exp_salary'];
          }
          $data['exp_salary'] = $exp_salary;
          $total_exp = '';
          if ($userCheck['User']['total_exp']) {
          global $totalexperienceArray;
          $total_exp = $totalexperienceArray[$userCheck['User']['total_exp']];
          }
          $data['total_exp'] = $total_exp;
          $company_about = '';
          if ($userCheck['User']['company_about']) {
          $company_about = $userCheck['User']['company_about'];
          }
          $data['about_me'] = $company_about;
         */
        $data['token'] = $userCheck['User']['token'];
        return $data;
    }

    public function apps_forgotPassword() {
        $this->layout = '';
        $this->requestAuthentication('POST', 2);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');
        $email_address = isset($userData['email_address']) ? $userData['email_address'] : $userData['email'];
        $site_title = $this->getSiteConstant('title');
        $userCheck = $this->User->find("first", array("conditions" => "User.email_address = '" . $email_address . "'"));
        if (is_array($userCheck) && !empty($userCheck)) {
            $this->User->updateAll(array('User.forget_password_status' => 1), array('User.id' => $userCheck['User']['id']));
            $email = $userCheck["User"]["email_address"];
            $username = $userCheck["User"]["first_name"];
            $link = HTTP_PATH . "/users/resetPassword/" . $userCheck['User']['id'] . "/" . md5($userCheck['User']['id']) . "/" . urlencode($email);
            $this->Email->to = $email;
            $currentYear = date('Y', time());
            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='4'"));
            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';
            $emData = $this->Emailtemplate->getSubjectLang();
            $subjectField = $emData['subject'];
            $templateField = $emData['template'];
            $toSubArray = array('[!username!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!activelink!]');
            $fromSubArray = array($username, HTTP_PATH, $site_title, $link);
            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
            $this->Email->subject = $subjectToSend;
            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
            $this->Email->from = $site_title . "<" . $mail_from . ">";
            $toRepArray = array('[!username!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!activelink!]');
            $fromRepArray = array($username, HTTP_PATH, $site_title, $link);
            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
            $this->Email->layout = 'default';
            $this->set('messageToSend', $messageToSend);
            $this->Email->template = 'email_template';
            $this->Email->sendAs = 'html';
            $this->Email->send();
            echo $this->successOutputMsg(__d('controller', 'A link to reset your password was sent to your email address', true));
        } else {
            echo $this->errorOutputResult(__d('controller', 'Your email is not registered with', true) . ' ' . $site_title . __d('controller', 'Please enter correct email or register on', true) . '. ' . $site_title);
        }
        exit;
    }

    public function apps_signup() {
        $this->layout = '';
        $this->requestAuthentication('POST', 2);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);

        $this->request->data["User"] = $userData;
        if ($this->request->data["User"]['login_type'] == 'normal') {
            if ($this->User->isRecordUniqueemail($this->data["User"]["email_address"]) == false) {
                echo $this->errorOutputResult(__d('controller', 'Email already exists.', true));
                exit;
            }
            $mail_from = $this->getMailConstant('from');
            $site_url = $this->getSiteConstant('url');

            $site_title = $this->getSiteConstant('title');
            $passwordPlain = $this->data["User"]["password"];
            $salt = uniqid(mt_rand(), true);
            $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
            $this->request->data['User']['password'] = $new_password;
            $this->request->data['User']['first_name'] = trim($this->data['User']['first_name']);
            $this->request->data['User']['last_name'] = trim($this->data['User']['last_name']);
            $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim(strtolower($this->data['User']['first_name'])) . ' ' . trim(strtolower($this->data['User']['last_name'])), 'User', 'slug');
            $this->request->data['User']['country_id'] = 1;
            $this->request->data['User']['activation_status'] = 0;
            $this->request->data['User']['status'] = 0;
            $this->request->data['User']['user_type'] = 'candidate';

            if ($this->User->save($this->data)) {
                $userId = $this->User->id;
                $userDetail = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
                $email = $this->data["User"]["email_address"];
                $username = $this->data["User"]["first_name"];
                $link = HTTP_PATH . "/users/confirmation/" . $userId . "/" . md5($userId) . "/" . urlencode($email);

                $this->Email->to = $email;
                $currentYear = date('Y', time());
                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='13'"));
                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                $emData = $this->Emailtemplate->getSubjectLang();
                $subjectField = $emData['subject'];
                $templateField = $emData['template'];

                $toSubArray = array('[!username!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                $fromSubArray = array($username, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                $this->Email->subject = $subjectToSend;

                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";

                $toRepArray = array('[!username!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                $fromRepArray = array($username, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);

                $this->Email->layout = 'default';
                $this->set('messageToSend', $messageToSend);
                $this->Email->template = 'email_template';
                $this->Email->sendAs = 'html';
                $this->Email->send();
                echo $this->successOutputMsg(__d('controller', 'Your account is successfully created.Please check your email for activation link. Thank you!', true));
            }
        }
        exit;
    }

    public function apps_changepassword() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);

        $userId = $tokenData['user_id'];
        $this->request->data["User"] = $userData;

        $this->User->id = $userId;
        $userOldPassword = $this->User->read();

        if (crypt($this->data['User']['old_password'], $userOldPassword['User']['password']) != $userOldPassword['User']['password']) {
            echo $this->errorOutputResult(__d('controller', 'Old Password is not correct.', true));
            exit;
        } else {
            if (crypt($this->data['User']['new_password'], $userOldPassword['User']['password']) == $userOldPassword['User']['password']) {
                $msgString .= __d('controller', 'You cannot put your old password for the new password', true) . "<br>";
                echo $this->errorOutputResult(__d('controller', 'You cannot put your old password for the new password', true));
                exit;
            }
        }

        $this->request->data['User']['id'] = $userId;
        $passwordPlain = $this->data["User"]["new_password"];
        $salt = uniqid(mt_rand(), true);
        $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
        $this->request->data['User']['password'] = $new_password;
        $this->User->save($this->data);
        echo $this->successOutputMsg(__d('controller', 'Your Password has been changed successfully.', true));
        exit;
    }

    public function apps_editprofileold() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $this->request->data["User"] = $userData;

        $this->request->data["User"]['company_about'] = $this->data["User"]['about_me'];
        if ($this->data["User"]['gender'] == 'Male') {
            $this->request->data["User"]['gender'] = 0;
        } else {
            $this->request->data["User"]['gender'] = 1;
        }

        $this->request->data['User']['profile_update_status'] = 1;
        if (count($this->data["User"]['coverletters']) > 0) {
            foreach ($this->data['coverletters'] as $key => $value) {
                if ($value['title'] != '' && $value['description'] != '') {
                    $update_data['CoverLetter'] = $value;
                    $update_data['CoverLetter']['user_id'] = $userId;
                    $this->CoverLetter->create();
                    $this->CoverLetter->save($update_data);
                }
            }
        }

        $this->request->data['User']['id'] = $userId;

        if ($this->User->save($this->data)) {
            if (isset($_FILES) && count($_FILES) > 0) {
                foreach ($_FILES as $document) {
                    global $extentions;
                    global $extentions_doc;
                    if ($document['name'] != '') {
                        list($width, $height, $type, $attr) = getimagesize($document['tmp_name']);
                        $getextention = $this->PImage->getExtension($document['name']);
                        $extention = strtolower($getextention);

                        $imageArray = $document;
                        $imageArray['name'] = $this->sanitizeFilename($document['name']);
                        $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_CERTIFICATE_PATH, "jpg,jpeg,png,gif");
                        if ($returnedUploadImageArray[0]) {
                             chmod(UPLOAD_CERTIFICATE_PATH .$returnedUploadImageArray[0], 0755);
                            $this->Certificate->create();
                            $imageName = $returnedUploadImageArray[0];
                            $this->request->data['Certificate']['document'] = $imageName;
                            $this->request->data['Certificate']['user_id'] = $userId;
                            $getextention = $this->PImage->getExtension($imageName);
                            $extention = strtolower($getextention);
                            if (in_array($extention, $extentions)) {
                                $this->request->data['Certificate']['type'] = 'image';
                                $this->request->data['Certificate']['slug'] = 'image-' . $userId . time() . rand(111, 99999);
                            } elseif (in_array($extention, $extentions_doc)) {
                                $this->request->data['Certificate']['type'] = 'doc';
                                $this->request->data['Certificate']['slug'] = 'doc-' . $userId . time() . rand(111, 99999);
                            }
                            $this->Certificate->save($this->data);
                        }
                    }
                }
            }
        }
        echo $this->successOutputMsg(__d('controller', 'Profile details updated successfully.', true));
        exit;
    }
    
//     public function apps_homescreen(){
//         $this->layout = '';
//         // $tokenData = $this->requestAuthentication('POST', 0);
//         // $jsonStr = $_POST['jsonData'];
//         // $userData = json_decode($jsonStr, true);
//         // $auth_type = $userData['auth_type'];
//         // print_r($_SERVER['HTTP_TOKEN']);exit;
//         if(!empty($_SERVER['HTTP_TOKEN'])){
            
//             $tokenData = $this->requestAuthentication('GET',1);
//         }else{
//             $tokenData = $this->requestAuthentication('GET',2);
//         }
//         $userId = $tokenData['user_id'];
//         // print_r($userId);exit;
//         if(!empty($userId)){
//                 $userData = $this->User->findById($userId);
//                 $condition = array('Job.status' => 1, 'Job.expire_time >=' => time());
//                 //skills, designation, preferred location, or native location
//                 // print_r($userData);exit;
//                 if (!empty($userData['User']['location'])) {
//                     $location = $userData['User']['location'];
//                     // $condition[] = array("MATCH(job_city) AGAINST ('$location' IN BOOLEAN MODE)");
//                     $location_cond = " (`Job`.`job_city` like '%" . addslashes($location) . "%') ";
//                     $condition[] = array('OR' => $location_cond);
//                 }
//                 if (!empty($userData['User']['pre_location'])) {
//                     $location = $userData['User']['pre_location'];
//                     // $condition[] = array("MATCH(job_city) AGAINST ('$location' IN BOOLEAN MODE)");
//                     $pre_locat_condition[] = " (`Job`.`job_city` like '%" . addslashes($location) . "%') ";
//                     $condition[] = array('OR' => $pre_locat_condition);
//                 }
//                 if (!empty($userData['User']['skill'])) {
//                     $skill_arr = explode(",", $userData['User']['skill']);
//                     $keyword = array();
//                     foreach ($skill_arr as $skillhave) {
//                         $cbd = array();
//                         $cbd[] = '(Skill.name = "' . $skillhave . '")';
//                         $skillDetail = $this->Skill->find('first', array('conditions' => $cbd));
//                         if ($skillDetail) {
//                             $idshave = $skillDetail['Skill']['id'];
//                             $condition_skill[] = "(FIND_IN_SET('" . $idshave . "',Job.skill))";
//                         } else {
//                             if ($skillhave != '') {
//                                 $condition_skill[] = "(Skill.name LIKE '%" . addslashes($skillhave) . "%')";
//                             }
//                         }
//                     }
//                     $condition[] = array('OR' => $condition_skill);
//                 }
//                 //$designation = $this->Experience->find('all',array('conditions'=>array('Experience.user_id'=>$userData['User']['id']),'fields' => array('MAX(Experience.to_year) AS max_year_desg', '*')));
//                 //print_r($designation);exit;
//                 if (!empty($userData['User']['designation'])) {
//                     $designation_arr = explode(",", $userData['User']['designation']);
//                     foreach ($designation_arr as $des) {
//                         $cbsd[] = '(Designation.name = "' . $des . '")';
//                         $dDetail = $this->Designation->find('first', array('conditions' => $cbsd));
//                         if ($dDetail) {
//                             $idshave = $dDetail['Designation']['id'];
//                             $condition_designation[] = '(Job.designation LIKE "%' . $idshave . '%")';
//                         } else {
//                             if ($des != '') {
//                                 $condition_designation[] = "(Designation.name LIKE '%" . addslashes($des) . "%')";
//                             }
//                         }
//                     }
//                     $condition[] = array('OR' => $condition_designation);
//                 }
//                 if ($userData['page'] != '') {
//                     $page = $userData['page'];
//                 }
//                 if ($userData['sort'] != '') {
//                     $order = $userData['sort'];
//                 }
//         //        echo '<pre>';
//         //        print_r($condition);exit;
//         }else{
//             $condition = array('Job.status' => 1, 'Job.expire_time >=' => time());
//         }
//         print_r($condition);exit;
//         $limit = 10;
//         if (!isset($page)) {
//             $page = 1;
//             $limit = 99999999;
//         }
//         if (!isset($order)) {
//             $order = 'Job.created DESC';
//         }
//         $this->paginate['Job'] = array('conditions' => $condition, 'limit' => $limit, 'page' => $page, 'order' => $order);
// //        $total_record = $this->Job->find('first', array('conditions' =>$condition));
//         $total_record = $this->Job->find('count', array('conditions' => $condition));
//         $jobslist = $this->paginate('Job');
//         $jobArray = array();
//         if(empty($jobslist)){
//             $condition = array('Job.status' => 1, 'Job.expire_time >=' => time());
//             $this->paginate['Job'] = array('conditions' => $condition, 'limit' => $limit, 'page' => $page, 'order' => $order);
//             $jobslist = $this->paginate('Job');
//             $no_recommend_job = true;
//         }    
//         if ($jobslist) {
//             $i = 0;
//             foreach ($jobslist as $job) {
//                 $jobArray[$i]['id'] = $job['Job']['id'];
//                 $jobArray[$i]['title'] = $job['Job']['title'];
//                 $jobArray[$i]['company_name'] = $job['Job']['company_name'];
//                 $logo = '';
//                 if (file_exists(UPLOAD_JOB_LOGO_PATH . $job['Job']['logo'])) {
//                     $logo = $job['Job']['logo'];
//                 }

//                 $jobArray[$i]['logo'] = $logo;
//                 $jobArray[$i]['profile_image'] = $job['User']['profile_image'];
//                 $jobArray[$i]['location'] = $job['Job']['job_city'];
//                 $jobArray[$i]['date'] = date('F j, Y', strtotime($job['Job']['created']));
//                 $i++;
//             }
//         }

//         $data = array();
//         if(!empty($userId)){
//             if($no_recommend_job){
//                 $data['recommended_jobs_count'] = 0;
//                 $data['recommended_jobs'] =  array();
//                 $data['new_jobs'] = $jobArray;
//                 $data['new_jobs_count'] = $total_record;
//             }else{
//                 $data['recommended_jobs_count'] =$total_record;
//                 $data['recommended_jobs'] =   $jobArray;
//                 $data['new_jobs'] = array();
//                 $data['new_jobs_count'] = 0;
//             }
//         }else{
//             $data['recommended_jobs_count'] = 0;
//             $data['recommended_jobs'] = array();
//             $data['new_jobs'] =  $jobArray;
//             $data['new_jobs_count'] = $total_record;
//         }
//         echo $this->successOutputResult('Jobs Lists Homescreen', json_encode($data));
//         exit;

//     }
    public function apps_homescreen(){
        $this->layout = '';
        // $jsonStr = $_POST['jsonData'];
        // $userData = json_decode($jsonStr, true);
        // $auth_type = $userData['auth_type'];
        // $_SERVER
        if(!empty($_SERVER['HTTP_TOKEN'])){
            if(strlen($_SERVER['HTTP_TOKEN']) > 32){
                $tokenData = $this->requestAuthentication('GET',2);
            }else{
                $tokenData = $this->requestAuthentication('GET',1);
            }
            //  $tokenData = $this->requestAuthentication('GET',1);
        }else{
            $tokenData = $this->requestAuthentication('GET',2);
        }
        $userId = $tokenData['user_id'];
        $data = array();
        $data['new_jobs'] =  array();
        $data['new_jobs_count'] = 0;
        $data['recommended_jobs_count'] =0;
        $data['recommended_jobs'] =  array();
        if(!empty($userId)){
                $userData = $this->User->findById($userId);
                $condition_rec[] = array('Job.status' => 1, 'Job.expire_time >=' => time());
                //skills, designation, preferred location, or native location
                if (!empty($userData['User']['location']) || !empty($userData['User']['pre_location'])) {
                    $location = $userData['User']['location'];
                    $pre_location = $userData['User']['pre_location'];
                    // $condition[] = array("MATCH(job_city) AGAINST ('$location' IN BOOLEAN MODE)");
                    if(!empty($userData['User']['location']) && !empty($userData['User']['pre_location'])){
                        $condition_rec[] = " (`Job`.`job_city` like '%" . addslashes($location) . "%' OR `Job`.`job_city` like '%" . addslashes($pre_location) . "%') ";
                    }else{
                        if(!empty($userData['User']['location'])){
                            $condition_rec[] = " (`Job`.`job_city` like '%" . addslashes($location) . "%') ";
                        }else{
                            $condition_rec[] = " (`Job`.`job_city` like '%" . addslashes($pre_location) . "%') ";
                        }
                    }
                    // $condition_rec[] = " (`Job`.`job_city` like '%" . addslashes($location) . "%') ";
                    //$condition_rec[] = array('OR' => $location_cond);
                }
                // if (!empty($userData['User']['pre_location'])) {
                //     $location = $userData['User']['pre_location'];
                //     // $condition[] = array("MATCH(job_city) AGAINST ('$location' IN BOOLEAN MODE)");
                //     $condition_rec[] = " (`Job`.`job_city` like '%" . addslashes($location) . "%') ";
                //      //$condition_rec[] = array('OR' => $pre_locat_condition);
                // }
                if (!empty($userData['User']['skill'])) {
                    $skill_arr = explode(",", $userData['User']['skill']);
                    $keyword = array();
                    foreach ($skill_arr as $skillhave) {
                        $cbd = array();
                        $cbd[] = '(Skill.name = "' . $skillhave . '")';
                        $skillDetail = $this->Skill->find('first', array('conditions' => $cbd));
                        if ($skillDetail) {
                            $idshave = $skillDetail['Skill']['id'];
                            $condition_skill[] = "(FIND_IN_SET('" . $idshave . "',Job.skill))";
                        } else {
                            if ($skillhave != '') {
                                $condition_skill[] = "(Skill.name LIKE '%" . addslashes($skillhave) . "%')";
                            }
                        }
                    }
                    $condition_rec[] = array('OR' => $condition_skill);
                }
                if (!empty($userData['User']['designation'])) {
                    $designation_arr = explode(",", $userData['User']['designation']);
                    foreach ($designation_arr as $des) {
                        $cbsd[] = '(Designation.name = "' . $des . '")';
                        $dDetail = $this->Designation->find('first', array('conditions' => $cbsd));
                        if ($dDetail) {
                            $idshave = $dDetail['Designation']['id'];
                            $condition_designation[] = '(Job.designation LIKE "%' . $idshave . '%")';
                        } else {
                            if ($des != '') {
                                $condition_designation[] = "(Designation.name LIKE '%" . addslashes($des) . "%')";
                            }
                        }
                    }
                    $condition_rec[] = array('OR' => $condition_designation);
                }
                    // print_r($condition_rec);exit;
                    $total_record_rec = $this->Job->find('count', array('conditions' => $condition_rec));
                    $jobslist_rec = $this->Job->find('all', array('conditions' => $condition_rec,'order' => 'Job.created DESC'));
                    $jobArray_rec = array();
                    if ($jobslist_rec) {
                        $i = 0;
                        foreach ($jobslist_rec as $job_rec) {
                            $jobArray_rec[$i]['id'] = $job_rec['Job']['id'];
                            $jobArray_rec[$i]['title'] = $job_rec['Job']['title'];
                            $jobArray_rec[$i]['company_name'] = $job_rec['Job']['company_name'];
                            $logo = '';
                            if (file_exists(UPLOAD_JOB_LOGO_PATH . $job_rec['Job']['logo'])) {
                                $logo = $job_rec['Job']['logo'];
                            }
            
                            $jobArray_rec[$i]['logo'] = $logo;
                            $jobArray_rec[$i]['profile_image'] = $job_rec['User']['profile_image'];
                            $jobArray_rec[$i]['location'] = $job_rec['Job']['job_city'];
                            $jobArray_rec[$i]['date'] = date('F j, Y', strtotime($job_rec['Job']['created']));
                            $i++;
                        }
                    }
                $data['recommended_jobs_count'] = $total_record_rec;
                $data['recommended_jobs'] =   $jobArray_rec;
        
            // print_r($jobArray_rec);exit;
        }
            
            $condition = array('Job.status' => 1, 'Job.expire_time >=' => time());
            $total_record = $this->Job->find('count', array('conditions' => $condition));
            $jobslist = $this->Job->find('all', array('conditions' => $condition, 'order' => 'Job.created DESC'));
            // print_r($jobslist);exit;
            $jobArray = array();
            //  print_r($condition);
            if ($jobslist) {
                $i = 0;
                foreach ($jobslist as $job) {
                    $jobArray[$i]['id'] = $job['Job']['id'];
                    $jobArray[$i]['title'] = $job['Job']['title'];
                    $jobArray[$i]['company_name'] = $job['Job']['company_name'];
                    $logo = '';
                    if (file_exists(UPLOAD_JOB_LOGO_PATH . $job['Job']['logo'])) {
                        $logo = $job['Job']['logo'];
                    }
    
                    $jobArray[$i]['logo'] = $logo;
                    $jobArray[$i]['profile_image'] = $job['User']['profile_image'];
                    $jobArray[$i]['location'] = $job['Job']['job_city'];
                    $jobArray[$i]['date'] = date('F j, Y', strtotime($job['Job']['created']));
                    $i++;
                }
            }
        $data['new_jobs'] =  $jobArray;
        $data['new_jobs_count'] = $total_record;
        
        echo $this->successOutputResult('Jobs Lists Homescreen', json_encode($data));
        exit;

    }

    public function apps_changeprofilepic() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);

        $userId = $tokenData['user_id'];

        $UseroldImage = $this->User->find('first', array('conditions' => array('User.id' => $userId), 'fields' => array('User.profile_image', 'User.slug')));
        $this->set("UseroldImage", $UseroldImage);

        $imageData = $_FILES['image'];
        $getextention = $this->PImage->getExtension($imageData['name']);
        $extention = strtolower($getextention);

        $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
        $toReplace = "-";
        $imageData['name'] = str_replace($specialCharacters, $toReplace, $imageData['name']);
        $imageData['name'] = str_replace("&", "and", $imageData['name']);
        $imageArray = $imageData;
        $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_PROFILE_IMAGE_PATH, "jpg,jpeg,png,gif");

        copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
        copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
        list($width) = getimagesize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
        if ($width > 650) {
            $this->PImageTest->resize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_IMAGE_WIDTH, UPLOAD_FULL_PROFILE_IMAGE_HEIGHT, 100);
        }

        $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
        $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
        $profilePic = $returnedUploadImageArray[0];
        chmod(UPLOAD_FULL_PROFILE_IMAGE_PATH .$returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
        if (isset($UseroldImage['User']['image']) && $UseroldImage['User']['image'] != "") {
            @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $UseroldImage['User']['image']);
            @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $UseroldImage['User']['image']);
            @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $UseroldImage['User']['image']);
        }

        $this->User->updateAll(array('User.profile_image' => "'$profilePic'"), array("User.id" => $userId));
        echo $this->successOutputMsg(__d('controller', 'Your Image has been Uploaded successfully.', true));
        exit;
    }

    public function apps_getglobalvariables() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 0);
    }

    public function apps_viewprofile() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $userId = $tokenData['user_id'];
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $userData['user_id'] ? $userData['user_id'] : $userId;
        $userCheck = $this->User->findById($userId);
        $data = array();
        if ($userCheck) {
            $data['user_id'] = $userCheck['User']['id'];
            $data['user_type'] = $userCheck['User']['user_type'];
            $data['first_name'] = $userCheck['User']['first_name'];
            $data['last_name'] = $userCheck['User']['last_name'];
            $data['email_address'] = $userCheck['User']['email_address'];
            $data['location'] = $userCheck['User']['location'];
            $data['location_name'] = $userCheck['Location']['name'];
            $gender = '';
            if ($userCheck['User']['gender'] != '') {
                if ($userCheck['User']['gender'] == 0) {
                    $gender = 'Male';
                } else {
                    $gender = 'Female';
                }
            }
            $data['gender'] = $gender;
            $data['contact'] = $userCheck['User']['contact'];
            $data['pre_location'] = $userCheck['User']['pre_location'];
            $data['skills'] = $userCheck['User']['skills'] ? explode(',', trim($userCheck['User']['skills'])) : array();
            $exp_salary = '';
            if ($userCheck['User']['exp_salary']) {
                $exp_salary = CURR . ' ' . $userCheck['User']['exp_salary'];
            }
            $data['exp_salary'] = $exp_salary;
            $total_exp = '';
            if ($userCheck['User']['total_exp']) {
                global $totalexperienceArray;
                $total_exp = $totalexperienceArray[$userCheck['User']['total_exp']];
            }
            $data['total_exp'] = $total_exp;
            $company_about = '';
            if ($userCheck['User']['company_about']) {
                $company_about = $userCheck['User']['company_about'];
            }
            $data['about_me'] = $company_about;
            $data['profile_image'] = $userCheck['User']['profile_image'];
            $data['video'] = $userCheck['User']['video'];
            $myPlan = $this->Plan->getcurrentplanEXP($userId);
            if ($myPlan == 1) {
                $data['is_plan'] = 0;
            }
            $data['is_expire'] = 0;
            $data['favorite_count'] = $FavoriteCount;
            $data['job_count'] = $jobCount;
            $maxJobPost = '';
            if ($myPlan) {
                $user_plan_id = $myPlan['UserPlan']['id'];
                $data['plan_id'] = $myPlan['Plan']['id'];
                $data['plan_name'] = $myPlan['Plan']['plan_name'];
                $featureIds = explode(',', $myPlan['UserPlan']['feature_ids']);
                $fvalues = json_decode($myPlan['UserPlan']['fvalues'], true);
                $maxJobPost = isset($fvalues[1]) ? $fvalues[1] : '';
                $maxResumeDownload = isset($fvalues[2]) ? $fvalues[2] : '';
                $maxSearchCandidate = isset($fvalues[3]) ? $fvalues[3] : '';
                $job_apply = isset($fvalues[4]) ? $fvalues[4] : '';
                $data['is_plan'] = 1;
                $tdaye = date('Y-m-d');
                if ($myPlan['UserPlan']['is_expire'] == 1 || $myPlan['UserPlan']['end_date'] < $tdaye) {
                    $data['is_expire'] = 1;
                }
            }

            $coverLetters = $this->CoverLetter->find('all', array('conditions' => array('CoverLetter.user_id' => $userId), 'order' => 'CoverLetter.id DESC'));
            $clArray = array();
            if ($coverLetters) {
                foreach ($coverLetters as $coverLetter) {
                    $clArray[] = array('id' => $coverLetter['CoverLetter']['id'], 'title' => $coverLetter['CoverLetter']['title'], 'description' => $coverLetter['CoverLetter']['description']);
                }
            }
            $data['coverletter'] = $clArray;

            $cvImages = array();
            $showOldImages = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userId)));
            if ($showOldImages) {
                foreach ($showOldImages as $showOldImage) {
                    $image = $showOldImage['Certificate']['document'];
                    $id = $showOldImage['Certificate']['id'];
                    if (!empty($image) && file_exists(UPLOAD_CERTIFICATE_PATH . $image)) {
                        $cvImages[] = array('id' => $id, 'document' => $image, 'type' => $showOldImage['Certificate']['type']);
                    }
                }
            }

//            $cvDocuments = array();
//            $showOldDocs = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userId, 'type' => 'doc')));
//            if ($showOldDocs) {
//                foreach ($showOldDocs as $showOldImage) {
//                    $image = $showOldImage['Certificate']['document'];
//                    $id = $showOldImage['Certificate']['id'];
//                    if (!empty($image) && file_exists(UPLOAD_CERTIFICATE_PATH . $image)) {
//                        $cvDocuments[] = array('id' => $id, 'document' => $image);
//                        ;
//                    }
//                }
//            }
            $data['certificates'] = $cvImages;

            $basicCourseList = $this->Course->find('list', array('conditions' => array('Course.type' => 'Basic', 'Course.status' => 1), 'order' => 'Course.name ASC'));
            $specilyList1 = $this->Specialization->find('list', array('conditions' => array('Specialization.status' => 1), 'order' => array('Specialization.name' => 'asc')));
            $yearArray = array_combine(range(date("Y"), 1950), range(date("Y"), 1950));
            $eduDetails = $this->Education->find('all', array('conditions' => array('Education.user_id' => $userId)));
            $educationArray = array();
            if ($eduDetails) {
                foreach ($eduDetails as $eduDetail) {
                    $record = array();
                    $record['id'] = $eduDetail['Education']['id'];
                    $record['course_id'] = $eduDetail['Education']['basic_course_id'];
                    $record['course_name'] = $basicCourseList[$eduDetail['Education']['basic_course_id']];
                    $record['specialization_id'] = $eduDetail['Education']['basic_specialization_id'];
                    $record['specialization'] = $specilyList1[$eduDetail['Education']['basic_specialization_id']];
                    $record['university'] = $eduDetail['Education']['basic_university'];
                    $record['passed_in'] = $yearArray[$eduDetail['Education']['basic_year']];
                    $educationArray[] = $record;
                }
            }
            $data['educations'] = $educationArray;

            $experienceArray = array();
            global $monthName;
            $expDetails = $this->Experience->find('all', array('conditions' => array('Experience.user_id' => $userId)));
            if ($expDetails) {
                foreach ($expDetails as $expDetail) {
                    $record = array();
                    $record['id'] = $expDetail['Experience']['id'];
                    $record['industry'] = $expDetail['Experience']['industry'];
                    $record['functional_area'] = $expDetail['Experience']['functional_area'];
                    $record['role'] = $expDetail['Experience']['role'];
                    $record['company_name'] = $expDetail['Experience']['company_name'];
                    $record['designation'] = $expDetail['Experience']['designation'];
                    $record['duration_from'] = array('month' => $monthName[$expDetail['Experience']['from_month']], 'year' => $yearArray[$expDetail['Experience']['from_year']]);
                    $record['duration_to'] = array('month' => $monthName[$expDetail['Experience']['to_month']], 'year' => $yearArray[$expDetail['Experience']['to_year']]);
                    $record['job_profile'] = $expDetail['Experience']['job_profile'];
                    $experienceArray[] = $record;
                }
            }
            $data['experience'] = $experienceArray;

            $interest_categories = $userCheck['User']['interest_categories'];
            $data['interest_array'] = array();
            $Categories_array = array();
            $categoriesarray = array();
            if ($interest_categories) {
                $condition = array();
                $condition[] = " (Category.id IN ($interest_categories ) )";
                $Categories_array = $this->Category->find('list', array('conditions' => $condition, 'order' => array('Category.name' => 'asc')));
            }
//            print_r($Categories_array);die;
            if ($Categories_array) {
                foreach ($Categories_array as $id => $val) {
                    $record = array();
                    $record['id'] = $id;
                    $record['name'] = $val;
                    $categoriesarray[] = $record;
                }
            }
            $data['interest_array'] = $categoriesarray;
            echo $this->successOutputResult('View profile', json_encode($data));
        }
        exit;
    }
    
             public function apps_deleteaccount() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $userId = $tokenData['user_id'];
        $userCheck = $this->User->findById($userId);
        $data = array();
        if ($userCheck) {
            $id = $userCheck['User']['id'];
            // $cnd = array("User.id = $id");
            $this->User->delete($id);

           echo $this->successOutputMsg(__d('controller', 'Your Account has been deleted successfully.', true));
        }
        exit;
    }

    public function apps_editprofile() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $this->request->data["User"] = $userData;
        $this->request->data["User"]['company_about'] = $this->data["User"]['about_me'];
        if ($this->data["User"]['gender'] == 'Male') {
            $this->request->data["User"]['gender'] = 0;
        } else {
            $this->request->data["User"]['gender'] = 1;
        }

        $this->request->data['User']['profile_update_status'] = 1;
        $this->request->data['User']['id'] = $userId;

        if ($this->User->save($this->data)) {
            echo $this->successOutputMsg(__d('controller', 'Profile details updated successfully.', true));
        }
        exit;
    }

    public function apps_getskillslist() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 0);
        $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.name', 'Skill.name'), 'order' => 'Skill.name asc'));
        $skillArray = array();
        if ($skillList) {
            foreach ($skillList as $val) {
                $skillArray[] = $val;
            }
        }

        echo $this->successOutputResult('Skills List', json_encode($skillArray));
        exit;
    }

    public function apps_getcourselist() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 0);
        $basicCourseList = $this->Course->find('list', array('conditions' => array('Course.type' => 'Basic', 'Course.status' => 1), 'fields' => array('Course.id', 'Course.name'), 'order' => 'Course.name ASC'));
        $skillArray = array();

        $specializationList = $this->Specialization->find('all', array('conditions' => array('Specialization.status' => 1), 'order' => array('Specialization.name' => 'asc')));

        $spArray = array();
        if ($specializationList) {
            foreach ($specializationList as $val) {
                $spArray[$val['Specialization']['course_id']][] = array('id' => $val['Specialization']['id'], 'name' => $val['Specialization']['name']);
            }
        }

        $i = 0;
        if ($basicCourseList) {
            foreach ($basicCourseList as $key => $val) {
                $skillArray[$i]['id'] = $key;
                $skillArray[$i]['name'] = $val;
                if (array_key_exists($key, $spArray)) {
                    $skillArray[$i]['Specialization'] = $spArray[$key];
                } else {
                    $skillArray[$i]['Specialization'] = array();
                }
                $i++;
            }
        }
        echo $this->successOutputResult('Course List', json_encode($skillArray));
        exit;
    }

    public function apps_getspecializationlist() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 0);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $course_id = $userData['course_id'];
        $specializationList = $this->Specialization->find('list', array('conditions' => array('Specialization.status' => 1, 'Specialization.course_id' => $course_id), 'order' => array('Specialization.name' => 'asc')));
        echo $this->successOutputResult('Specialization List', json_encode($specializationList));
        exit;
    }

    public function apps_updateSkills() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $skills = $userData['skills'];
        $this->User->updateAll(array('User.skills' => "'$skills'"), array('User.id' => $userId));
        echo $this->successOutputMsg(__d('controller', 'Skills updated successfully.', true));
        exit;
    }

    public function apps_addEducation() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $specilyList1 = $this->Specialization->find('list', array('conditions' => array('Specialization.status' => 1), 'order' => array('Specialization.name' => 'asc')));
        $yearArray = array_combine(range(date("Y"), 1950), range(date("Y"), 1950));

        $userId = $tokenData['user_id'];
        $this->request->data['Education']['user_id'] = $userId;
        $this->request->data['Education']['user_type'] = 'candidate';
        $this->request->data['Education']['education_type'] = 'Basic';
        $this->request->data['Education']['basic_course_id'] = $userData['course_id'];

        if (isset($userData['specialization_id']) && !empty($userData['specialization_id'])) {
            $this->request->data['Education']['basic_specialization_id'] = $userData['specialization_id'];
        } else {
            $this->request->data['Education']['basic_specialization_id'] = '0';
        }
        $this->request->data['Education']['basic_university'] = $userData['university'];
        $this->request->data['Education']['basic_year'] = $userData['year'];
        $this->Education->save($this->data);
        $educationId = $this->Education->id;
        $eduDetail = $this->Education->find('first', array('conditions' => array('Education.id' => $educationId)));
        $educationArray = array();
        if ($eduDetail) {
            $record = array();
            $record['id'] = $eduDetail['Education']['id'];
            $record['course_id'] = $eduDetail['Education']['basic_course_id'];
            $record['course_name'] = $basicCourseList[$eduDetail['Education']['basic_course_id']];
            $record['specialization_id'] = $eduDetail['Education']['basic_specialization_id'];
            $record['specialization'] = $specilyList1[$eduDetail['Education']['basic_specialization_id']];
            $record['university'] = $eduDetail['Education']['basic_university'];
            $record['passed_in'] = $yearArray[$eduDetail['Education']['basic_year']];
        }
        $data['educations'] = $record;
        echo $this->successOutputResult('View educations', json_encode($data));
        exit;
    }

    public function apps_editEducation() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $this->request->data['Education']['id'] = $userData['id'];
        $this->request->data['Education']['user_id'] = $userId;
        $this->request->data['Education']['user_type'] = 'candidate';
        $this->request->data['Education']['education_type'] = 'Basic';
        $this->request->data['Education']['basic_course_id'] = $userData['course_id'];

        if (isset($userData['specialization_id']) && !empty($userData['specialization_id'])) {
            $this->request->data['Education']['basic_specialization_id'] = $userData['specialization_id'];
        } else {
            $this->request->data['Education']['basic_specialization_id'] = '0';
        }
        $this->request->data['Education']['basic_university'] = $userData['university'];
        $this->request->data['Education']['basic_year'] = $userData['year'];
        $this->Education->save($this->data);
        $educationId = $userData['id'];
        $eduDetail = $this->Education->find('first', array('conditions' => array('Education.id' => $educationId)));
        $educationArray = array();
        if ($eduDetail) {
            $record = array();
            $record['id'] = $eduDetail['Education']['id'];
            $record['course_id'] = $eduDetail['Education']['basic_course_id'];
            $record['course_name'] = $basicCourseList[$eduDetail['Education']['basic_course_id']];
            $record['specialization_id'] = $eduDetail['Education']['basic_specialization_id'];
            $record['specialization'] = $specilyList1[$eduDetail['Education']['basic_specialization_id']];
            $record['university'] = $eduDetail['Education']['basic_university'];
            $record['passed_in'] = $yearArray[$eduDetail['Education']['basic_year']];
        }
        $data['educations'] = $record;
        echo $this->successOutputResult('View educations', json_encode($data));
        exit;
    }

    public function apps_deleteEducation() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $this->Education->delete($userData['id']);
        echo $this->successOutputMsg(__d('controller', 'Education delete successfully.', true));
        exit;
    }

    public function apps_addExperience() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $this->request->data['Experience']['user_id'] = $userId;
        $this->request->data['Experience']['industry'] = $userData['industry'];
        $this->request->data['Experience']['functional_area'] = $userData['functional_area'];
        $this->request->data['Experience']['role'] = $userData['role'];
        $this->request->data['Experience']['company_name'] = $userData['company_name'];
        $this->request->data['Experience']['designation'] = $userData['designation'];
        $this->request->data['Experience']['ctclakhs'] = '0';
        $this->request->data['Experience']['ctcthousand'] = '0';
        $this->request->data['Experience']['from_month'] = $userData['from_month'];
        $this->request->data['Experience']['from_year'] = $userData['from_year'];
        $this->request->data['Experience']['to_month'] = $userData['to_month'];
        $this->request->data['Experience']['to_year'] = $userData['to_year'];
        $this->request->data['Experience']['job_profile'] = $userData['job_profile'];
        $this->request->data['Experience']['status'] = 1;
        $this->request->data['Experience']['slug'] = 'exp-' . $userId . '-' . time();
        $this->Experience->save($this->data);
        $experienceId = $this->Experience->id;
        $experienceArray = array();
        global $monthName;
        $expDetail = $this->Experience->find('all', array('conditions' => array('Experience.id' => $experienceId)));
        if ($expDetail) {

            $record = array();
            $record['id'] = $expDetail['Experience']['id'];
            $record['industry'] = $expDetail['Experience']['industry'];
            $record['functional_area'] = $expDetail['Experience']['functional_area'];
            $record['role'] = $expDetail['Experience']['role'];
            $record['company_name'] = $expDetail['Experience']['company_name'];
            $record['designation'] = $expDetail['Experience']['designation'];
            $record['duration_from'] = array('month' => $monthName[$expDetail['Experience']['from_month']], 'year' => $yearArray[$expDetail['Experience']['from_year']]);
            $record['duration_to'] = array('month' => $monthName[$expDetail['Experience']['to_month']], 'year' => $yearArray[$expDetail['Experience']['to_year']]);
            $record['job_profile'] = $expDetail['Experience']['job_profile'];
            $experienceArray[] = $record;
        }
        $data['experience'] = $experienceArray;
        echo $this->successOutputResult('Experience Details saved successfully', json_encode($data));
        exit;
    }

    public function apps_editExperience() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $this->request->data['Experience']['user_id'] = $userId;
        $this->request->data['Experience']['id'] = $userData['id'];
        $this->request->data['Experience']['industry'] = $userData['industry'];
        $this->request->data['Experience']['functional_area'] = $userData['functional_area'];
        $this->request->data['Experience']['role'] = $userData['role'];
        $this->request->data['Experience']['company_name'] = $userData['company_name'];
        $this->request->data['Experience']['designation'] = $userData['designation'];
        $this->request->data['Experience']['ctclakhs'] = '0';
        $this->request->data['Experience']['ctcthousand'] = '0';
        $this->request->data['Experience']['from_month'] = $userData['from_month'];
        $this->request->data['Experience']['from_year'] = $userData['from_year'];
        $this->request->data['Experience']['to_month'] = $userData['to_month'];
        $this->request->data['Experience']['to_year'] = $userData['to_year'];
        $this->request->data['Experience']['job_profile'] = $userData['job_profile'];
        $this->Experience->save($this->data);
        $experienceId = $userData['id'];
        $experienceArray = array();
        global $monthName;
        $expDetail = $this->Experience->find('all', array('conditions' => array('Experience.id' => $experienceId)));
        if ($expDetail) {

            $record = array();
            $record['id'] = $expDetail['Experience']['id'];
            $record['industry'] = $expDetail['Experience']['industry'];
            $record['functional_area'] = $expDetail['Experience']['functional_area'];
            $record['role'] = $expDetail['Experience']['role'];
            $record['company_name'] = $expDetail['Experience']['company_name'];
            $record['designation'] = $expDetail['Experience']['designation'];
            $record['duration_from'] = array('month' => $monthName[$expDetail['Experience']['from_month']], 'year' => $yearArray[$expDetail['Experience']['from_year']]);
            $record['duration_to'] = array('month' => $monthName[$expDetail['Experience']['to_month']], 'year' => $yearArray[$expDetail['Experience']['to_year']]);
            $record['job_profile'] = $expDetail['Experience']['job_profile'];
            $experienceArray[] = $record;
        }
        $data['experience'] = $experienceArray;
        echo $this->successOutputResult('Experience Details saved successfully', json_encode($data));
        exit;
    }

    public function apps_deleteExperience() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $this->Experience->delete($userData['id']);
        echo $this->successOutputMsg(__d('controller', 'Experience Details deleted successfully', true));
        exit;
    }

    public function apps_searchjobs() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 0);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
//        $condition = array();
        $condition = array('Job.status' => 1, 'Job.expire_time >=' => time());
        if ($userData['keyword'] != '') {
            $keyword = $userData['keyword'];
            $condition[] = " (`Job`.`title` LIKE '%" . addslashes($keyword) . "%' OR `Job`.`description` LIKE '%" . addslashes($keyword) . "%' OR `Job`.`company_name` LIKE '%" . addslashes($keyword) . "%' ) ";
        }
        if ($userData['category_id'] != '') {
            $category_idCondtionArray = explode('-', $userData['category_id']);
            $category_idCondtion = implode(',', $category_idCondtionArray);
            $condition[] = " (Job.category_id IN ($category_idCondtion))";
        }

        if ($userData['location'] != '') {
            $location = $userData['location'];
            // $condition[] = array("MATCH(job_city) AGAINST ('$location' IN BOOLEAN MODE)");
            $condition[] = " (`Job`.`job_city` like '%" . addslashes($location) . "%') ";
        }
        if ($userData['exp'] != '') {
            $expArray = explode('-', $userData['exp']);
            $min_exp = $expArray[0];
            $max_exp = $expArray[1];
            if ($min_exp == $max_exp) {
                $condition[] = " ((Job.min_exp <= $min_exp AND Job.max_exp >= $min_exp)) ";
            } else {
                $condition[] = " ((Job.min_exp >= $min_exp AND Job.max_exp <= $max_exp)) ";
            }
        }
        if ($userData['salary'] != '') {
            $expsalary = explode('-', $userData['salary']);
            $min_salary = $expsalary[0];
            $max_salary = $expsalary[1];
            $condition[] = " ((Job.min_salary >= $min_salary AND Job.max_salary <= $min_salary) OR (Job.min_salary >= $min_salary AND Job.max_salary <= $max_salary) OR (Job.min_salary = $max_salary ) OR (Job.max_salary = $min_salary )) ";
        }

        if ($userData['skill'] != '') {
            $skill_arr = explode(",", $userData['skill']);
            $keyword = array();
            foreach ($skill_arr as $skillhave) {
                $cbd = array();
                $cbd[] = '(Skill.name = "' . $skillhave . '")';
                $skillDetail = $this->Skill->find('first', array('conditions' => $cbd));
                if ($skillDetail) {
                    $idshave = $skillDetail['Skill']['id'];
                    $condition_skill[] = "(FIND_IN_SET('" . $idshave . "',Job.skill))";
                } else {
                    if ($skillhave != '') {
                        $condition_skill[] = "(Skill.name LIKE '%" . addslashes($skillhave) . "%')";
                    }
                }
            }
            $condition[] = array('OR' => $condition_skill);
        }

        if ($userData['designation'] != '') {
            $designation_arr = explode(",", $userData['designation']);
            foreach ($designation_arr as $des) {
                $cbsd[] = '(Designation.name = "' . $des . '")';
                $dDetail = $this->Designation->find('first', array('conditions' => $cbsd));
                if ($dDetail) {
                    $idshave = $dDetail['Designation']['id'];
                    $condition_designation[] = '(Job.designation LIKE "%' . $idshave . '%")';
                } else {
                    if ($des != '') {
                        $condition_designation[] = "(Designation.name LIKE '%" . addslashes($des) . "%')";
                    }
                }
            }
            $condition[] = array('OR' => $condition_designation);
        }

        if ($userData['work_type'] != '') {
            $worktype_arr = explode(",", $userData['work_type']);
            foreach ($worktype_arr as $work) {
                $condition_worktype[] = "(FIND_IN_SET('" . $work . "',Job.work_type))";
            }
            $condition[] = array('OR' => $condition_worktype);
        }
        if ($userData['page'] != '') {
            $page = $userData['page'];
        }
        if ($userData['sort'] != '') {
            $order = $userData['sort'];
        }
        $limit = 10;
        if (!isset($page)) {
            $page = 1;
            $limit = 99999999;
        }
        if (!isset($order)) {
            $order = 'Job.created DESC';
        }
//        echo '<pre>';
//        print_r($condition);exit;
        $this->paginate['Job'] = array('conditions' => $condition, 'limit' => $limit, 'page' => $page, 'order' => $order);
//        $total_record = $this->Job->find('first', array('conditions' =>$condition));
        $total_record = $this->Job->find('count', array('conditions' => $condition));
        $jobslist = $this->paginate('Job');
        $jobArray = array();
        if ($jobslist) {
            $i = 0;
            foreach ($jobslist as $job) {
                $jobArray[$i]['id'] = $job['Job']['id'];
                $jobArray[$i]['title'] = $job['Job']['title'];
                $jobArray[$i]['company_name'] = $job['Job']['company_name'];
                $logo = '';
                if (file_exists(UPLOAD_JOB_LOGO_PATH . $job['Job']['logo'])) {
                    $logo = $job['Job']['logo'];
                }

                $jobArray[$i]['logo'] = $logo;
                $jobArray[$i]['profile_image'] = $job['User']['profile_image'];
                $jobArray[$i]['location'] = $job['Job']['job_city'];
                $jobArray[$i]['date'] = date('F j, Y', strtotime($job['Job']['created']));
                $i++;
            }
        }
        $data = array();
        $data['total_records'] = $total_record;
        $data['job_array'] = $jobArray;

        echo $this->successOutputResult('Job List', json_encode($data));
        exit;
    }

    public function apps_searchcandidate() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 0);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $condition[] = array('(User.user_type = "candidate")');
        if ($userData['keyword'] != '') {
            $keyword = $userData['keyword'];
            $condition[] = " (`User`.`first_name` LIKE '%" . addslashes($keyword) . "%' or concat(`User.first_name`,' ',`User.last_name`) LIKE '%" . addslashes($keyword) . "%' or `User`.`last_name` LIKE '%" . addslashes($keyword) . "%' or `User`.`company_about` LIKE '%" . addslashes($keyword) . "%') ";
        }


        if ($userData['location'] != '') {
            $location = $userData['location'];
            // $condition[] = array("MATCH(job_city) AGAINST ('$location' IN BOOLEAN MODE)");
//            $condition[] = " (`Job`.`job_city` like '%" . addslashes($location) . "%') ";
            $condition[] = array("MATCH(pre_location) AGAINST ('$location' IN BOOLEAN MODE)");
        }
        if ($userData['exp'] != '') {
            $expArray = explode('-', $userData['exp']);
            $min_exp = $expArray[0];
            $max_exp = $expArray[1];
            if ($min_exp == $max_exp) {
                $condition[] = array('User.total_exp >= ' => $min_exp, 'User.total_exp <= ' => $min_exp);
            } else {
                $condition[] = array('User.total_exp >= ' => $min_exp, 'User.total_exp <= ' => $max_exp);
            }
        }


        if ($userData['salary'] != '') {
            $expsalary = explode('-', $userData['salary']);
            $min_salary = $expsalary[0];
            $max_salary = $expsalary[1];
            $condition[] = array('User.exp_salary >= ' => $min_salary, 'User.exp_salary <= ' => $max_salary);
        }

        if ($userData['skill'] != '') {
            $skill_arr = explode(",", $userData['skill']);
            $keyword = array();
            foreach ($skill_arr as $skillhave) {
//                $cbd = array();
//                $cbd[] = '(Skill.name = "' . $skillhave . '")';
//                $skillDetail = $this->Skill->find('first', array('conditions' => $cbd));
//                if ($skillDetail) {
//                    $idshave = $skillDetail['Skill']['id'];
//                    $condition_skill[] = "(FIND_IN_SET('" . $idshave . "',User.skills))";
//                } else {
//                    if ($skillhave != '') {
//                        $condition_skill[] = "(User.skills LIKE '%" . addslashes($skillhave) . "%')";
//                    }
//                }
                $condition_skill[] = "(FIND_IN_SET('" . $skillhave . "',User.skills))";
            }
            $condition[] = array('OR' => $condition_skill);
        }

        if ($userData['page'] != '') {
            $page = $userData['page'];
        }
        if ($userData['sort'] != '') {
            $order = $userData['sort'];
        }
        $limit = 99999999;
        if (!isset($page)) {
            $page = 1;
            $limit = 99999999;
        }
        if (!isset($order)) {
            $order = 'User.created DESC';
        }
//        echo '<pre>';
//        print_r($condition);exit;
        $this->paginate['User'] = array('conditions' => $condition, 'limit' => $limit, 'page' => $page, 'order' => $order);
//        $total_record = $this->Job->find('first', array('conditions' =>$condition));
        $total_record = $this->User->find('count', array('conditions' => $condition));
        $candidatelist = $this->paginate('User');
//        echo '<pre>';
//        print_r($candidatelist);
//        exit;
        $candidateArray = array();
        if ($candidatelist) {
            $i = 0;
            foreach ($candidatelist as $candidate) {
                $candidateArray[$i]['id'] = $candidate['User']['id'];
                $candidateArray[$i]['first_name'] = $candidate['User']['first_name'];
                $candidateArray[$i]['first_name'] = $candidate['User']['first_name'];
                $candidateArray[$i]['last_name'] = $candidate['User']['last_name'];
                $candidateArray[$i]['email_address'] = $candidate['User']['email_address'];
                $candidateArray[$i]['location'] = $candidate['User']['location'];
                $candidateArray[$i]['location_name'] = $candidate['Location']['name'];
                $gender = '';
                if ($candidate['User']['gender'] != '') {
                    if ($candidate['User']['gender'] == 0) {
                        $gender = 'Male';
                    } else {
                        $gender = 'Female';
                    }
                }
                $candidateArray[$i]['gender'] = $gender;
                $candidateArray[$i]['contact'] = $candidate['User']['contact'];
                $candidateArray[$i]['pre_location'] = $candidate['User']['pre_location'];
                $candidateArray[$i]['skills'] = $candidate['User']['skills'] ? explode(',', trim($candidate['User']['skills'])) : array();
                $exp_salary = '';
                if ($candidate['User']['exp_salary']) {
                    $exp_salary = CURR . ' ' . $candidate['User']['exp_salary'];
                }
                $candidateArray[$i]['exp_salary'] = $exp_salary;
                $total_exp = '';
                if ($candidate['User']['total_exp']) {
                    global $totalexperienceArray;
                    $total_exp = $totalexperienceArray[$candidate['User']['total_exp']];
                }
                $candidateArray[$i]['total_exp'] = $total_exp;
                $company_about = '';
                if ($candidate['User']['company_about']) {
                    $company_about = $candidate['User']['company_about'];
                }
                $candidateArray[$i]['about_me'] = $company_about;
                $candidateArray[$i]['profile_image'] = $candidate['User']['profile_image'];

                $candidateArray[$i]['date'] = date('F j, Y', strtotime($candidate['User']['created']));
                $i++;
            }
        }
        $data = array();
        $data['total_records'] = $total_record;
        $data['candidate_array'] = $candidateArray;

        echo $this->successOutputResult('Candidate List', json_encode($data));
        exit;
    }

    public function apps_getconstant() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 0);

        global $worktype;
        global $experienceArray;
        global $sallery;
        $data = array();
        $workArray = array();
        $i = 0;
        foreach ($worktype as $key => $val) {
            $workArray[$i]['id'] = $key;
            $workArray[$i]['val'] = $val;
            $i++;
        }

        $expArray = array();
        $i = 0;
        foreach ($experienceArray as $key => $val) {
            $expArray[$i]['id'] = $key;
            $expArray[$i]['val'] = $val;
            $i++;
        }
        $salleryArray = array();
        $i = 0;
        foreach ($sallery as $key => $val) {
            $salleryArray[$i]['id'] = $key;
            $salleryArray[$i]['val'] = $val;
            $i++;
        }
        $data['worktype'] = $workArray;
        $data['experience'] = $expArray;
        $data['salary'] = $salleryArray;
        echo $this->successOutputResult('Conatnts', json_encode($data));
        exit;
    }

    public function apps_jobdetail() {
        $this->layout = '';

        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $jobId = $userData['id'];
        $auth_type = $userData['auth_type'];
        $slug = $userData['slug'];
        $tokenData = $this->requestAuthentication('POST', $auth_type);
        $userId = $tokenData['user_id'];
        if(!empty($slug)){
            $jobdetails = $this->Job->findBySlug($slug);
        }else{
            $jobdetails = $this->Job->findById($jobId);     
        }
       //    $jobdetails = $this->Job->findById($jobId);
//        echo '<pre>';
//        print_r($userData);exit;
        $data = array();
        $data['title'] = $jobdetails['Job']['title'];
        $data['company_name'] = $jobdetails['Job']['company_name'];
        $data['location'] = $jobdetails['Job']['job_city'];
        $data['expire_time'] = date('Y-m-d', $jobdetails['Job']['expire_time']);
        if (isset($jobdetails['Job']['min_exp']) && isset($jobdetails['Job']['max_exp'])) {
            $experience = $jobdetails['Job']['min_exp'] . "-" . $jobdetails['Job']['max_exp'] . " Year";
        } else {
            $experience = "N/A";
        }
        $data['experience'] = $experience;
        $data['view_count'] = $jobdetails['Job']['view_count'];
        $data['applications'] = ClassRegistry::init('JobApply')->getTotalCandidate($jobdetails['Job']['id']);
        $is_applied = 0;
        if (isset($userId) && $userId > 0) {
            $apply_status = classregistry::init('JobApply')->find('first', array('conditions' => array('JobApply.user_id' => $userId, 'JobApply.job_id' => $jobdetails['Job']['id'])));
            if ($apply_status) {
                $is_applied = 1;
            }
        }
        $is_saved = 0;
        $clArray = array();
        $skills_array = array();
        if (isset($userId) && $userId > 0) {
            $save_status = classregistry::init('ShortList')->find('first', array('conditions' => array('ShortList.user_id' => $userId, 'ShortList.job_id' => $jobdetails['Job']['id'], 'Job.job_status' => 0)));
            if ($save_status) {
                $is_saved = 1;
            }


            $coverLetters = $this->CoverLetter->find('all', array('conditions' => array('CoverLetter.user_id' => $userId), 'order' => 'CoverLetter.id DESC'));

            if ($coverLetters) {
                foreach ($coverLetters as $coverLetter) {
                    $clArray[] = array('id' => $coverLetter['CoverLetter']['id'], 'title' => $coverLetter['CoverLetter']['title']);
                }
            }
        }
        $jobIds = explode(',', $jobdetails['Job']['skill']);
        $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));

        if ($skillList) {
            $i = 0;
            foreach ($jobIds as $jid) {
                $skills_array[$i]['id'] = $jid;
                $skills_array[$i]['name'] = $skillList[$jid];
                $i++;
            }
        }
        $data['skills_array'] = $skills_array;
        $data['coverletter'] = $clArray;
        $data['is_applied'] = $is_applied;
        $data['is_saved'] = $is_saved;
        $data['profile_image'] = $jobdetails['User']['profile_image'];
        $data['user_id'] = $userId;
        $logo = '';
        if (file_exists(UPLOAD_JOB_LOGO_PATH . $jobdetails['Job']['logo'])) {
            $logo = $jobdetails['Job']['logo'];
        }

        $data['logo'] = $logo;
        $data['description'] = $jobdetails['Job']['description'];
        if (isset($jobdetails['Job']['min_salary']) && isset($jobdetails['Job']['max_salary'])) {
            $salary = CURRENCY . ' ' . intval($jobdetails['Job']['min_salary']) . " - " . CURRENCY . ' ' . intval($jobdetails['Job']['max_salary']);
        } else {
            $salary = "N/A";
        }
        $data['salary'] = $salary;
        $data['category'] = $jobdetails['Category']['name'];
        global $worktype;
        $data['job_type'] = $worktype[$jobdetails['Job']['work_type']];
        $data['posted_date'] = date('F d,Y', strtotime($jobdetails['Job']['created']));


        $data['designation'] = $designation = ClassRegistry::init('Skill')->field('name', array('Skill.id' => $jobdetails['Job']['designation'], 'Skill.type' => 'Designation'));

        if ($jobdetails['Job']['brief_abtcomp']) {
            $company_profile = strip_tags($jobdetails['Job']['brief_abtcomp']);
        } else {
            if (!empty($jobdetails['User']['company_about'])) {
                $company_profile = $jobdetails['User']['company_about'];
            } else {
                $company_profile = 'N/A';
            }
        }
        $data['company_profile'] = strip_tags($company_profile);
        if (trim($jobdetails['Job']['url']) != '') {
            $website = $jobdetails['Job']['url'];
        } else {
            $website = 'N/A';
        }
        $data['website'] = $website;
        if ($jobdetails['Job']['contact_name'] == '') {
            $contact_name = $jobdetails['User']['first_name'];
        } else {
            $contact_name = $jobdetails['Job']['contact_name'];
        }
        if ($jobdetails['Job']['contact_number'] == '') {
            $contact_number = $jobdetails['User']['contact'];
        } else {
            $contact_number = $jobdetails['Job']['contact_number'];
        }
        $data['contact_name'] = $contact_name;
        $data['contact_number'] = $contact_number;
        $data['job_id'] = $jobdetails['Job']['id'];
        $data['url'] = HTTP_PATH.'/'.$jobdetails['Category']['slug'].'/'.$jobdetails['Job']['slug'].'.html';
      
        echo $this->successOutputResult('Job details', json_encode($data));
        exit;
    }

    public function apps_applyforjob() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $isAbleToJob = $this->Plan->checkPlanFeature($user_id, 4);
        if ($isAbleToJob['status'] == 0) {
            echo $this->errorOutputResult($isAbleToJob['message']);
            exit;
        }
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $job_id = $userData['jobid'];
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');
        $cover_letter = $userData['cover_letter'];

        $isAbleToJob = $this->Plan->checkPlanFeature($user_id, 4);
        $this->request->data['JobApply']['user_plan_id'] = $isAbleToJob['user_plan_id'];
        $this->request->data['JobApply']['new_status'] = 1;
        $this->request->data['JobApply']['status'] = 1;
        $this->request->data['JobApply']['apply_status'] = 'active';
        $this->request->data['JobApply']['user_id'] = $user_id;
        $this->request->data['JobApply']['job_id'] = $job_id;
        $this->request->data['JobApply']['cover_letter_id'] = $cover_letter;

        if (!empty($this->data['JobApply']['cover_letter'])) {
            $this->request->data['JobApply']['cover_letter_id'] = $this->data['JobApply']['cover_letter'];
        } else {
            $this->request->data['JobApply']['cover_letter_id'] = " ";
        }
        $this->request->data['JobApply']['attachment_ids'] = '';
//        echo '<pre>'; print_r($this->data);die;
        if ($this->JobApply->save($this->data)) {
            $userInfo = $this->User->findByid($user_id);
            $jobInfo = $this->Job->findByid($job_id);
            $recruiterInfo = $this->User->findByid($jobInfo['Job']['user_id']);

            $jobTitle = $jobInfo["Job"]["title"];
            $email = $userInfo["User"]["email_address"];
            $userName = ucfirst($userInfo["User"]["first_name"]);

            $this->Email->to = $email;
            $currentYear = date('Y', time());
            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='15'"));
            $link = HTTP_PATH . "/" . $jobdetails['Category']['slug'] . '/' . $jobInfo['Job']['slug'] . '.html';

            $emData = $this->Emailtemplate->getSubjectLang();
            $subjectField = $emData['subject'];
            $templateField = $emData['template'];

            $toSubArray = array('[!username!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!activelink!]');
            $fromSubArray = array($userName, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
            $this->Email->subject = $subjectToSend;

            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
            $this->Email->from = $site_title . "<" . $mail_from . ">";

            $toRepArray = array('[!username!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!activelink!]');
            $fromRepArray = array($userName, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
            $this->Email->layout = 'default';
            $this->set('messageToSend', $messageToSend);
            $this->Email->template = 'email_template';
            $this->Email->sendAs = 'html';
            $this->Email->send();
            $this->Email->reset();

            // send mail to the recruiter    
            $recruiterEmail = $recruiterInfo["User"]["email_address"];
            $recruiterName = ucfirst($recruiterInfo["User"]["first_name"]);

            $this->Email->to = $recruiterEmail;
            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='16'"));
            $link = HTTP_PATH . "/candidates/profile/" . $userInfo['User']['slug'];

            $emData = $this->Emailtemplate->getSubjectLang();
            $subjectField = $emData['subject'];
            $templateField = $emData['template'];

            $this->Email->subject = $emailtemplateMessage['Emailtemplate'][$subjectField];
            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
            $this->Email->from = $site_title . "<" . $mail_from . ">";
            $currentYear = date('Y', time());
            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . MAIL_FROM . '">' . MAIL_FROM . '</a>';
            $toRepArray = array('[!username!]', '[!job_title!]', '[!jobseeker_name!]', '[!jobseeker_email!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!activelink!]');
            $fromRepArray = array($recruiterName, $jobTitle, $userName, $email, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
            $this->Email->layout = 'default';
            $this->set('messageToSend', $messageToSend);
            $this->Email->template = 'email_template';
            $this->Email->sendAs = 'html';
            $this->Email->send();

            echo $this->successOutputMsg(__d('controller', 'Your job application is successfully posted. We will contact you soon.', true));
            exit;
        }
    }

    public function apps_getcategorylist() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 0);

        $categories = $this->Category->getCategoryList();
        $data = array();
        $catArray = array();
        $i = 0;
        foreach ($categories as $key => $val) {
            $catArray[$i]['id'] = $key;
            $catArray[$i]['val'] = $val;
            $i++;
        }
        echo $this->successOutputResult('Category List', json_encode($catArray));
        exit;
    }

    // save job    
    public function apps_savejob() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $job_id = $userData['id'];
        $checkStatus = $this->ShortList->find('first', array('conditions' => array('ShortList.user_id' => $user_id, 'ShortList.job_id' => $job_id)));
        if (!empty($checkStatus)) {
            echo $this->errorOutputResult(__d('controller', 'You already saved this job.', true));
            exit;
        }
        $this->request->data['ShortList']['status'] = 1;
        $this->request->data['ShortList']['user_id'] = $user_id;
        $this->request->data['ShortList']['job_id'] = $job_id;
        if ($this->ShortList->save($this->data)) {
            echo $this->successOutputMsg(__d('controller', 'Job added in saved Jobs list', true));
            exit;
        }
    }

    // save job listing  
    public function apps_getsavejoblist() {

        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);

        $user_id = $tokenData['user_id'];
        $jobs = $this->ShortList->find('all', array('conditions' => array('ShortList.user_id' => $user_id, 'Job.status' => 1, 'Job.job_status' => 0), 'order' => 'ShortList.id DESC'));
        $data = array();
        $jobArray = array();
        $i = 0;
//        echo '<pre>';
//        print_r($user_id);
//        die;
        foreach ($jobs as $key => $job) {
            $jobInfo = $this->Job->find('first', array('conditions' => array('Job.id' => $job['ShortList']['job_id'])));
//             $job_details = $this->Job->find('first', array('conditions' =>array('id'=>$job['ShortList']['job_id'])));
//             print_r($jobInfo);
            $jobArray[$i]['id'] = $job['ShortList']['id'];
            $jobArray[$i]['job_id'] = $job['ShortList']['job_id'];
            $jobArray[$i]['user_id'] = $job['ShortList']['user_id'];
            $jobArray[$i]['title'] = $job['Job']['title'];
            $jobArray[$i]['company_name'] = $job['Job']['company_name'];
            $logo = '';
            if (file_exists(UPLOAD_JOB_LOGO_PATH . $job['Job']['logo'])) {
                $logo = $job['Job']['logo'];
            }

            $jobArray[$i]['logo'] = $logo;
            $jobArray[$i]['profile_image'] = $jobInfo['User']['profile_image'];
            $jobArray[$i]['location'] = $job['Job']['job_city'];
            $jobArray[$i]['date'] = date('F j, Y', strtotime($job['ShortList']['created']));
            $i++;
        }
        echo $this->successOutputResult('Save Job List', json_encode($jobArray));
        exit;
    }

    // save job listing  
    public function apps_getapplyjoblist() {

        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);

        $user_id = $tokenData['user_id'];
        $jobs = $this->JobApply->find('all', array('conditions' => array('JobApply.user_id' => $user_id, 'Job.status' => 1), 'order' => 'JobApply.id DESC'));
        $data = array();
        $jobArray = array();
        $i = 0;
        foreach ($jobs as $key => $job) {
            $jobInfo = $this->Job->find('first', array('conditions' => array('Job.id' => $job['JobApply']['job_id'])));
            $jobArray[$i]['id'] = $job['JobApply']['id'];
            $jobArray[$i]['job_id'] = $job['JobApply']['job_id'];
            $jobArray[$i]['user_id'] = $job['JobApply']['user_id'];
            $jobArray[$i]['new_status'] = $job['JobApply']['new_status'];
            $jobArray[$i]['apply_status'] = $job['JobApply']['apply_status'];
            $jobArray[$i]['rating'] = $job['JobApply']['rating'];
            $jobArray[$i]['attachment_ids'] = $job['JobApply']['attachment_ids'];
            $jobArray[$i]['cover_letter_id'] = $job['JobApply']['cover_letter_id'];
            $jobArray[$i]['user_employer_id'] = $job['JobApply']['user_employer_id'];
            $jobArray[$i]['title'] = $job['Job']['title'];
            $jobArray[$i]['company_name'] = $job['Job']['company_name'];
            $logo = '';
            if (file_exists(UPLOAD_JOB_LOGO_PATH . $job['Job']['logo'])) {
                $logo = $job['Job']['logo'];
            }

            $jobArray[$i]['logo'] = $logo;
            $jobArray[$i]['profile_image'] = $jobInfo['User']['profile_image'];
            $jobArray[$i]['location'] = $job['Job']['job_city'];
            $jobArray[$i]['date'] = date('F j, Y', strtotime($job['JobApply']['created']));
            $i++;
        }
        echo $this->successOutputResult('Save Job List', json_encode($jobArray));
        exit;
    }

    // delete save job
    public function apps_deletesavedjob() {

        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);

        $user_id = $tokenData['user_id'];
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $job_id = $userData['id'];
        $this->ShortList->delete($job_id);
        $jobs = $this->ShortList->find('all', array('conditions' => array('ShortList.user_id' => $user_id, 'Job.status' => 1, 'Job.job_status' => 0)));
        $data = array();
        $jobArray = array();
        $i = 0;
        foreach ($jobs as $key => $job) {
            $jobInfo = $this->Job->find('first', array('conditions' => array('Job.id' => $job['ShortList']['job_id'])));

            $jobArray[$i]['id'] = $job['ShortList']['id'];
            $jobArray[$i]['job_id'] = $job['ShortList']['job_id'];
            $jobArray[$i]['user_id'] = $job['ShortList']['user_id'];
            $jobArray[$i]['title'] = $job['Job']['title'];
            $jobArray[$i]['company_name'] = $job['Job']['company_name'];
            $logo = '';
            if (file_exists(UPLOAD_JOB_LOGO_PATH . $job['Job']['logo'])) {
                $logo = $job['Job']['logo'];
            }

            $jobArray[$i]['logo'] = $logo;
            $jobArray[$i]['profile_image'] = $jobInfo['User']['profile_image'];
            $jobArray[$i]['location'] = $job['Job']['job_city'];
            $jobArray[$i]['date'] = date('F j, Y', strtotime($job['ShortList']['created']));
            $i++;
        }
        echo $this->successOutputResult('Job removed from Saved Jobs List', json_encode($jobArray));
        exit;
    }

    // save cover letter    
    public function apps_savecoverletter() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $cover_id = $userData['id'];
        $title = $userData['title'];
        $description = $userData['description'];
        if ($cover_id) {
            if ($title && $description) {
                $this->request->data['CoverLetter']['id'] = $cover_id;
                $this->request->data['CoverLetter']['user_id'] = $user_id;
                $this->request->data['CoverLetter']['title'] = $title;
                $this->request->data['CoverLetter']['description'] = $description;
                $this->CoverLetter->save($this->data);
                $msg = "Cover Letter saved successfully";
            } else {
                $msg = "Cover Letter deleted successfully";
                $this->CoverLetter->delete($cover_id);
            }

            $coverLetters = $this->CoverLetter->find('all', array('conditions' => array('CoverLetter.user_id' => $user_id), 'order' => 'CoverLetter.id DESC'));
            $clArray = array();
            if ($coverLetters) {
                foreach ($coverLetters as $coverLetter) {
                    $clArray[] = array('id' => $coverLetter['CoverLetter']['id'], 'title' => $coverLetter['CoverLetter']['title'], 'description' => $coverLetter['CoverLetter']['description']);
                }
            }
            $data['coverletter'] = $clArray;
            echo $this->successOutputResult($msg, json_encode($data));
//            echo $this->successOutputMsg(__d('controller', 'Job added in saved Jobs list', true));
            exit;
        } else {
            $msg = "Cover Letter saved successfully";
            $this->request->data['CoverLetter']['user_id'] = $user_id;
            $this->request->data['CoverLetter']['title'] = $title;
            $this->request->data['CoverLetter']['description'] = $description;
            if ($this->CoverLetter->save($this->data)) {
                $coverLetters = $this->CoverLetter->find('all', array('conditions' => array('CoverLetter.user_id' => $user_id), 'order' => 'CoverLetter.id DESC'));
                $clArray = array();
                if ($coverLetters) {
                    foreach ($coverLetters as $coverLetter) {
                        $clArray[] = array('id' => $coverLetter['CoverLetter']['id'], 'title' => $coverLetter['CoverLetter']['title'], 'description' => $coverLetter['CoverLetter']['description']);
                    }
                }
                $data['coverletter'] = $clArray;
                echo $this->successOutputResult($msg, json_encode($data));
                exit;
            }
        }
    }

    // save cover letter    
    public function apps_savecvdocument() {
        $this->layout = '';
        global $extentions;
        global $extentions_doc;
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $doc_id = $userData['id'];
        if ($doc_id) {
            $docInfo = $this->Certificate->find('first', array('conditions' => array('Certificate.id' => $doc_id)));
            $doc = $docInfo['Certificate']['document'];
            $this->Certificate->delete($doc_id);
            @unlink(UPLOAD_CERTIFICATE_PATH . $doc);
            echo $this->successOutputMsg(__d('controller', 'Document deleted successfully.', true));
            exit;
        } else {
            $imageData = $_FILES['image'];
            $getextention = $this->PImage->getExtension($imageData['name']);
            $extention = strtolower($getextention);

            if (in_array($extention, $extentions)) {
                $imageArray = $imageData;
//                $imageArray['name'] = $this->sanitizeFilename($imageArray['Certificate']['document']['name']);
                $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_CERTIFICATE_PATH, "jpg,jpeg,png,gif");
                if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
//                    $msgString .= "- Image file not valid <br>";
                    $image = '';
                } else {
                    $image = $returnedUploadImageArray[0];
                      chmod(UPLOAD_CERTIFICATE_PATH .$returnedUploadImageArray[0], 0755);
                }
            } elseif (in_array($extention, $extentions_doc)) {
                $imageArray = $imageData;
                $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                $toReplace = "-";
//                $this->request->data['Certificate']['document']['name'] = str_replace($specialCharacters, $toReplace, $imageArray['Certificate']['document']['name']);
//                $this->request->data['Certificate']['document']['name'] = str_replace("&", "and", $imageArray['Certificate']['document']['name']);
//                if ($this->data['Certificate']['document']['name']) {
//                    $cvArray = $this->data['Certificate']['document'];
                $returnedUploadCVArray = $this->PImage->upload($imageArray, UPLOAD_CERTIFICATE_PATH);
                $image = $returnedUploadCVArray[0];
                chmod(UPLOAD_CERTIFICATE_PATH .$returnedUploadImageArray[0], 0755);
//                }
            }
            if (in_array($extention, $extentions)) {
                $this->request->data['Certificate']['type'] = 'image';
                $this->request->data['Certificate']['slug'] = 'image-' . $userId . time() . rand(111, 99999);
            } elseif (in_array($extention, $extentions_doc)) {
                $this->request->data['Certificate']['type'] = 'doc';
                $this->request->data['Certificate']['slug'] = 'doc-' . $userId . time() . rand(111, 99999);
            }

            if ($image) {
                $this->request->data['Certificate']['document'] = $image;
                $this->request->data['Certificate']['user_id'] = $user_id;
                if ($this->Certificate->save($this->data)) {
                    $id = $this->Certificate->id;
                    $docInfo = $this->Certificate->find('first', array('conditions' => array('Certificate.id' => $id)));
                    $data = array('id' => $docInfo['Certificate']['id'], 'document' => $docInfo['Certificate']['document'], 'type' => $docInfo['Certificate']['type']);
                    echo $this->successOutputResult('Document saved successfully.', json_encode($data));
                    exit;
                };
            }
            exit;
        }
    }

    // save job    
    public function apps_saveAlert() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $location = $userData['location'];
        $designation = $userData['designation'];

        $this->request->data['Alert']['status'] = 1;
        $this->request->data['Alert']['slug'] = 'ALERT' . time() . rand(10000, 999999);
        $this->request->data['Alert']['user_id'] = $user_id;
        $this->request->data['Alert']['designation'] = $designation;
        $this->request->data['Alert']['location'] = $location;
        if ($this->Alert->save($this->data)) {
            echo $this->successOutputMsg(__d('controller', 'Alert saved. You will receive an alert when jobs are created and match your criteria.', true));
            exit;
        }
    }

    // update job    
    public function apps_updateAlert() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $location = $userData['location'];
        $alert_id = $userData['id'];
        $designation = $userData['designation'];

        $this->request->data['Alert']['status'] = 1;
        $this->request->data['Alert']['user_id'] = $user_id;
        $this->request->data['Alert']['id'] = $alert_id;
        $this->request->data['Alert']['designation'] = $designation;
        $this->request->data['Alert']['location'] = $location;
        if ($this->Alert->save($this->data)) {
            echo $this->successOutputMsg(__d('controller', 'Alert saved. You will receive an alert when jobs are created and match your criteria.', true));
            exit;
        }
    }

    public function apps_deleteAlert() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $user_id = $tokenData['user_id'];
        $this->Alert->delete($userData['id']);
        $alerts = $this->Alert->find('all', array('conditions' => array('Alert.status' => 1, 'Alert.user_id' => $user_id)));
        $data = array();
        $alertArray = array();
        $i = 0;
        foreach ($alerts as $key => $alert) {
            $designation = ClassRegistry::init('Skill')->field('name', array('Skill.id' => $alert['Alert']['designation'], 'Skill.type' => 'Designation'));
            $alertArray[$i]['id'] = $alert['Alert']['id'];
            $alertArray[$i]['user_id'] = $alert['Alert']['user_id'];
            $alertArray[$i]['designation'] = $alert['Alert']['designation'];
            $alertArray[$i]['name'] = $designation;
            $alertArray[$i]['location'] = $alert['Alert']['location'];


            $alertArray[$i]['date'] = date('F j, Y', strtotime($alert['Alert']['created']));
            $i++;
        }
        echo $this->successOutputResult('Job Alert deleted successfully', json_encode($alertArray));
        exit;
    }

    // Get save alert listing  
    public function apps_getAlertList() {

        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);

        $user_id = $tokenData['user_id'];
        $alerts = $this->Alert->find('all', array('conditions' => array('Alert.status' => 1, 'Alert.user_id' => $user_id)));
        $data = array();
        $alertArray = array();
        $i = 0;
        foreach ($alerts as $key => $alert) {
            $designation = ClassRegistry::init('Skill')->field('name', array('Skill.id' => $alert['Alert']['designation'], 'Skill.type' => 'Designation'));
            $alertArray[$i]['id'] = $alert['Alert']['id'];
            $alertArray[$i]['user_id'] = $alert['Alert']['user_id'];
            $alertArray[$i]['designation'] = $alert['Alert']['designation'];
            $alertArray[$i]['name'] = $designation;
            $alertArray[$i]['location'] = $alert['Alert']['location'];


            $alertArray[$i]['date'] = date('F j, Y', strtotime($alert['Alert']['created']));
            $i++;
        }
        echo $this->successOutputResult('Save Alert List', json_encode($alertArray));
        exit;
    }

    // Get designtion listing  
    public function apps_getDesignationList() {

        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);

        $user_id = $tokenData['user_id'];
        $designationlList = $this->Skill->find('all', array('conditions' => array('Skill.type' => 'Designation', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $data = array();
        $designationArray = array();
        $i = 0;
        foreach ($designationlList as $key => $designation) {
            $designationArray[$i]['id'] = $designation['Skill']['id'];
            $designationArray[$i]['name'] = $designation['Skill']['name'];

            $i++;
        }
        echo $this->successOutputResult('Get Designation List', json_encode($designationArray));
        exit;
    }

// user logout
    public function apps_logout() {

        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $this->User->updateAll(array('User.device_id' => "''"), array('User.id' => $user_id));
        echo $this->successOutputMsg(__d('controller', 'Logout successfully.', true));
        exit;
    }

    // Generate Cv
    public function apps_generatecv() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $this->User->bindModel(array(
            'hasMany' => array(
                'Education' => array(
                    'className' => 'Education',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
                'Experience' => array(
                    'className' => 'Experience',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
            ),
        ));

        $userdetail = $this->User->findById($user_id);
        $this->set('userdetail', $userdetail);
        $this->set('myaccount', 'active');
        $name = ucfirst($userdetail['User']['first_name']) . '_' . $userdetail['User']['last_name'] . '_CV';
        $this->set('name', $name);
//       $var= $this->render('generatecv_app');
        App::import('Vendor', 'tcpdf', array('file' => 'tcpdf/tcpdf.php'));
        $pdf = new tcpdf();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Autoringen');
        $pdf->SetTitle('Ownership');

// remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);

        $pdf->addPage('', 'A4');
        $pdf->setFont('helvetica', '', 11);

// set default header data
        $pdf->SetHeaderData();
        $date = date('m/d/Y');
        $firstLast = $userdetail['User']['first_name'] . ' ' . $userdetail['User']['last_name'];
        $profile_image = '';
        $profile_image1 = '';
        $address = '';
        $address1 = '';
        $address2 = '';
        $contact = ($userdetail['User']['contact']);
        $contact = sprintf("%s-%s-%s", substr($contact, 0, 3), substr($contact, 3, 3), substr($contact, 6));
        $email_address = $userdetail['User']['email_address'];

        if ($userdetail['User']['location']) {
            $address = "<tr><td style='font-size: 10px;'>Address: " . $userdetail['Location']['name'] . "</td></tr>";
//            $address = '<td style="font-size: 10px;">Address: ' . $userdetail['Location']['name'] . '</td>';
            $address1 = '<td style="text-align: right;font-size: 10px;">Address: ' . $userdetail['Location']['name'] . '</td>';
            $address2 = '<td style="text-align: right;">Address: ' . $userdetail['Location']['name'] . '</td>';
        }

        if ($userdetail['User']['profile_image'] != "") {

            $profile_image = $address;
        }
        if ($userdetail['User']['contact']) {
            $contact_str = "<tr> <td style='font-size: 10px;'>" . $contact . "</td></tr>";
        }

        $profile_image_url = DISPLAY_THUMB_PROFILE_IMAGE_PATH . $userdetail['User']['profile_image'];

        if ($userdetail['User']['profile_image'] == "") {
            $string = $addresss1;
        } else {
            $path = UPLOAD_FULL_PROFILE_IMAGE_PATH . $userdetail['User']['profile_image'];

            if (file_exists($path) && !empty($userdetail['User']['profile_image'])) {
                $string = "<td style='text-align: right;'><img src='$profile_image_url' style='width:80px;height:80px;' rel = 'nofollow'></td>";
            } else {
                $string = $address2;
            }
        }



        $Education = '';
        if (isset($userdetail['Education']) && !empty($userdetail['Education'])) {
            foreach ($userdetail['Education'] as $education) {
                $couses[] = $education['basic_course_id'];
                $Education .= '<tr><td style="line-height: 15px;font-size: 9px;" colspan="2">• I have Passed';
                $courseName = ClassRegistry::init('Course')->field('name', array('Course.id' => $education['basic_course_id']));
                $Education .= ' ' . $courseName;
                $Education .= " in ";

                if (isset($education["basic_year"])) {
                    $Education .= $education["basic_year"];
                } else {
                    $Education .= 'N/A';
                }
                $Education .= " in ";
                $specialization = ClassRegistry::init('Specialization')->field('name', array('Specialization.id' => $education['basic_specialization_id']));
                $Education .= $specialization;
                $Education .= " from ";
                if (isset($education["basic_university"])) {
                    $Education .= $education["basic_university"];
                } else {
                    $Education .= 'N/A';
                }
                $Education .= ".</td></tr>";
            }
        }

        $Experience = '';
        if (isset($userdetail['Experience']) && !empty($userdetail['Experience'])) {
            foreach ($userdetail['Experience'] as $experience) {
                $Experience .= '<tr><td style="line-height: 15px;font-size: 9px;" colspan="2">• I have worked as a ';

                if (isset($experience["role"])) {
                    $Experience .= $experience['role'];
                } else {
                    $Experience .= 'N/A';
                }
                if (isset($experience["designation"])) {
                    $Experience .= ' ' . $experience['designation'];
                } else {
                    $Experience .= ' ' . 'N/A';
                }
                $Experience .= " for ";
                $Experience .= $experience['company_name'];
                $Experience .= " since ";
                if (isset($experience["from_month"]) && isset($experience["from_year"]) && isset($experience["to_month"]) && isset($experience["to_year"])) {

                    $experience['from_month'] == 1;
                    switch ($experience['from_month']) {
                        case "1":
                            $fromName = 'Jan';
                            break;
                        case "2":
                            $fromName = 'Feb';
                            break;
                        case "3":
                            $fromName = 'Mar';
                            break;
                        case "4":
                            $fromName = 'Apr';
                            break;
                        case "5":
                            $fromName = 'May';
                            break;
                        case "6":
                            $fromName = 'June';
                            break;
                        case "7":
                            $fromName = 'Jul';
                            break;
                        case "8":
                            $fromName = 'Aug';
                            break;
                        case "9":
                            $fromName = 'Sept';
                            break;
                        case "10":
                            $fromName = 'Oct';
                            break;
                        case "11":
                            $fromName = 'Nov';
                            break;
                        case "12":
                            $fromName = 'Dec';
                            break;
                        default:
                            $fromName = 'N/A';
                    }

                    $experience['to_month'] == 1;
                    switch ($experience['to_month']) {
                        case "1":
                            $toName = 'Jan';
                            break;
                        case "2":
                            $toName = 'Feb';
                            break;
                        case "3":
                            $toName = 'Mar';
                            break;
                        case "4":
                            $toName = 'Apr';
                            break;
                        case "5":
                            $toName = 'May';
                            break;
                        case "6":
                            $toName = 'June';
                            break;
                        case "7":
                            $toName = 'Jul';
                            break;
                        case "8":
                            $toName = 'Aug';
                            break;
                        case "9":
                            $toName = 'Sept';
                            break;
                        case "10":
                            $toName = 'Oct';
                            break;
                        case "11":
                            $toName = 'Nov';
                            break;
                        case "12":
                            $toName = 'Dec';
                            break;
                        default:
                            $toName = 'N/A';
                    }

                    $Experience .= $fromName . '-' . $experience['from_year'] . ' to ' . $toName . '-' . $experience['to_year'];
                } else {
                    $Experience .= 'N/A';
                }
                $Experience .= "<br>
                  <table>";
                if ($experience['industry'] != "") {
                    $Experience .= '<tr><td style="line-height: 15px;font-size: 9px;" colspan="2"><label style="font-weight: bold">Industry: </label> ' . $experience['industry'] . ' </td></tr>';
                }
                if ($experience['functional_area'] != "") {
                    $Experience .= '<tr><td style="line-height: 15px;font-size: 9px;" colspan="2"><label style="font-weight: bold">Functional area: </label> ' . $experience['functional_area'] . ' </td></tr>';
                }
                if ($experience['role'] != "") {
                    $Experience .= '<tr><td style="line-height: 15px;font-size: 9px;" colspan="2"><label style="font-weight: bold">Role: </label> ' . $experience['role'] . ' </td></tr>';
                }
                if ($experience['job_profile'] != "") {
                    $Experience .= '<tr><td style="line-height: 15px;font-size: 9px;" colspan="2"><label style="font-weight: bold">Job profile: </label> ' . $experience['job_profile'] . ' </td></tr>';
                }
                $Experience .= "</table>
        </td>
        </tr>
        <tr> <td style='line-height: 5px;font-size: 12px;' colspan='2'> </td></tr>";
            }
        }

// output the HTML content
        $template_html = <<<EOT
<table border="0" style="width:600px; font-size:8px; font-family:Open Sans">
    <tbody>
        <tr style="width:100%;">
            <td style="width:10px;">&nbsp;</td>
            <td style="width:560px;">
                <table style="width:90%;">
                    <tr> <td style="line-height: 35px; width: 100%; text-align: center; font-size: 18px;" colspan="2">Curriculum Vitae (CV) </td></tr>
                </table>
                <table style="width:90%;">
                    <tr> <td style="line-height: 35px;" colspan="2"> </td></tr>
         <tr>
   <td>
                            <table>

                                <tr>
                                    <td style="font-size: 10px;font-weight: bold;">{$firstLast}</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 10px;">{$email_address}</td>
                                </tr>
                                {$contact_str}
                               {$profile_image}
                               
                            </table>
                        </td>
                               {$string}
                </tr>     
           <tr> <td style="line-height: 25px;font-size: 12px;border-bottom: 0px solid #333" colspan="2"> Education Qualification</td></tr>          
          <tr> <td style="line-height: 5px;font-size: 12px;" colspan="2"> </td></tr>                     
                              {$Education} 
                              <tr> <td style="line-height: 25px;font-size: 12px;" colspan="2"> </td></tr>
                    <tr> <td style="line-height: 24px;font-size: 12px;border-bottom: 0px solid #333" colspan="2"> Experience</td></tr>
                    <tr> <td style="line-height: 5px;font-size: 12px;" colspan="2"> </td></tr>
                              {$Experience}
        <tr> <td style="line-height: 25px;font-size: 12px;" colspan="2"> </td></tr>
                    <tr><td><label style="font-weight: bold; font-size: 11px; text-align: left;">Date: {$date}</label></td><td><label style="text-align: right;font-size: 11px;font-weight: bold">Signature</label></td></tr>


                </table>
            </td>
        </tr>
    </tbody>
</table>

EOT;
        $pdf->writeHTML($template_html, true, false, true, false, '');
        $ffname = UPLOAD_RESUME_PATH . $name;
        $pdf->Output($ffname . '.pdf', 'F');

        $data['resume_path'] = DISPLAY_RESUME_PATH . $name . '.pdf';
        echo $this->successOutputResult('Your CV have been generated successfully', json_encode($data));
        die;
    }

    public function apps_socialLogin() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 2);
        $user_id = $tokenData['user_id'];
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);

        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');

        $this->request->data["User"] = $userData;
        $emailAddress = $userData['email_address'];
        $device_type = $userData['device_type'];
        $device_id = $userData['device_id'];
        if ($userData['type'] == 'facebook') {

            $userCheck = $this->User->find('first', array('conditions' => array('User.email_address' => $emailAddress)));
            if ($userCheck) {
                $payLoad = array(
                    "user_id" => $userCheck['User']['id'],
                    "time" => time()
                );
                $token = $this->setToken($payLoad);
                $userCheck['User']['token'] = $token;
                $data = $this->logindata($userCheck);

                $this->User->updateAll(array('User.device_id' => "'$device_id'", 'User.device_type' => "'$device_type'"), array('User.id' => $userCheck['User']['id']));
                echo $this->successOutputResult('login sucessfully', json_encode($data));
                exit;
            } else {

                $passwordPlain = $this->data["User"]["password"];
                $salt = uniqid(mt_rand(), true);
                $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
                $this->request->data['User']['password'] = $new_password;
                $this->request->data['User']['first_name'] = trim($this->data['User']['first_name']);
                $this->request->data['User']['last_name'] = trim($this->data['User']['last_name']);
                $this->request->data['User']['facebook_user_id'] = trim($this->data['User']['facebook_user_id']);
                $this->request->data['User']['device_type'] = trim($this->data['User']['device_type']);
                $this->request->data['User']['device_id'] = trim($this->data['User']['device_id']);
                $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim(strtolower($this->data['User']['first_name'])) . ' ' . trim(strtolower($this->data['User']['last_name'])), 'User', 'slug');
                $this->request->data['User']['country_id'] = 1;
                $this->request->data['User']['activation_status'] = 0;
                $this->request->data['User']['status'] = 0;
                $this->request->data['User']['user_type'] = 'candidate';
//                echo '<pre>';
//                                print_r($this->data);die;
                if ($this->User->save($this->data)) {
                    $userId = $this->User->id;
                    $userCheck = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
                    $email = $this->data["User"]["email_address"];
                    $username = $this->data["User"]["first_name"];
                    $link = HTTP_PATH . "/users/confirmation/" . $userId . "/" . md5($userId) . "/" . urlencode($email);

                    $this->Email->to = $email;
                    $currentYear = date('Y', time());
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='13'"));
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];

                    $toSubArray = array('[!username!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                    $fromSubArray = array($username, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                    $fromRepArray = array($username, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);

                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                    $payLoad = array(
                        "user_id" => $userCheck['User']['id'],
                        "time" => time()
                    );
                    $token = $this->setToken($payLoad);
                    $userCheck['User']['token'] = $token;
                    $data = $this->logindata($userCheck);
                    echo $this->successOutputResult('View profile', json_encode($data));
                    exit;
                }
            }
        } else if ($userData['type'] == 'google') {
            $userCheck = $this->User->find('first', array('conditions' => array('User.email_address' => $emailAddress)));
            if ($userCheck) {
                $payLoad = array(
                    "user_id" => $userCheck['User']['id'],
                    "time" => time()
                );
                $token = $this->setToken($payLoad);
                $userCheck['User']['token'] = $token;
                $data = $this->logindata($userCheck);
                $this->User->updateAll(array('User.device_id' => "'$device_id'", 'User.device_type' => "'$device_type'"), array('User.id' => $userCheck['User']['id']));
                echo $this->successOutputResult('login sucessfully', json_encode($data));
                exit;
            } else {
                $passwordPlain = $this->data["User"]["password"];
                $salt = uniqid(mt_rand(), true);
                $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
                $this->request->data['User']['password'] = $new_password;
                $this->request->data['User']['first_name'] = trim($this->data['User']['first_name']);
                $this->request->data['User']['last_name'] = trim($this->data['User']['last_name']);
                $this->request->data['User']['device_type'] = trim($this->data['User']['device_type']);
                $this->request->data['User']['device_id'] = trim($this->data['User']['device_id']);
                $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim(strtolower($this->data['User']['first_name'])) . ' ' . trim(strtolower($this->data['User']['last_name'])), 'User', 'slug');
                $this->request->data['User']['country_id'] = 1;
                $this->request->data['User']['activation_status'] = 0;
                $this->request->data['User']['status'] = 0;
                $this->request->data['User']['user_type'] = 'candidate';

                if ($this->User->save($this->data)) {
                    $userId = $this->User->id;
                    $userCheck = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
                    $email = $this->data["User"]["email_address"];
                    $username = $this->data["User"]["first_name"];
                    $link = HTTP_PATH . "/users/confirmation/" . $userId . "/" . md5($userId) . "/" . urlencode($email);

                    $this->Email->to = $email;
                    $currentYear = date('Y', time());
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='13'"));
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];

                    $toSubArray = array('[!username!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                    $fromSubArray = array($username, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                    $fromRepArray = array($username, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);

                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                    $payLoad = array(
                        "user_id" => $userCheck['User']['id'],
                        "time" => time()
                    );
                    $token = $this->setToken($payLoad);
                    $userCheck['User']['token'] = $token;
                    $data = $this->logindata($userCheck);
                    echo $this->successOutputResult('View profile', json_encode($data));
                    exit;
                }
            }
        } else {
            $userCheck = $this->User->find('first', array('conditions' => array('User.email_address' => $emailAddress)));
            if ($userCheck) {
                $payLoad = array(
                    "user_id" => $userCheck['User']['id'],
                    "time" => time()
                );
                $token = $this->setToken($payLoad);
                $userCheck['User']['token'] = $token;
                $data = $this->logindata($userCheck);
                $this->User->updateAll(array('User.device_id' => "'$device_id'", 'User.device_type' => "'$device_type'"), array('User.id' => $userCheck['User']['id']));
                echo $this->successOutputResult('login sucessfully', json_encode($data));
                exit;
            } else {
                $passwordPlain = $this->data["User"]["password"];
                $salt = uniqid(mt_rand(), true);
                $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
                $this->request->data['User']['password'] = $new_password;
                $this->request->data['User']['first_name'] = trim($this->data['User']['first_name']);
                $this->request->data['User']['last_name'] = trim($this->data['User']['last_name']);
                $this->request->data['User']['linkedin_id'] = trim($this->data['User']['linkedin_id']);
                $this->request->data['User']['device_type'] = trim($this->data['User']['device_type']);
                $this->request->data['User']['device_id'] = trim($this->data['User']['device_id']);
                $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim(strtolower($this->data['User']['first_name'])) . ' ' . trim(strtolower($this->data['User']['last_name'])), 'User', 'slug');
                $this->request->data['User']['country_id'] = 1;
                $this->request->data['User']['activation_status'] = 0;
                $this->request->data['User']['status'] = 0;
                $this->request->data['User']['user_type'] = 'candidate';

                if ($this->User->save($this->data)) {
                    $userId = $this->User->id;
                    $userCheck = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
                    $email = $this->data["User"]["email_address"];
                    $username = $this->data["User"]["first_name"];
                    $link = HTTP_PATH . "/users/confirmation/" . $userId . "/" . md5($userId) . "/" . urlencode($email);

                    $this->Email->to = $email;
                    $currentYear = date('Y', time());
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='13'"));
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];

                    $toSubArray = array('[!username!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                    $fromSubArray = array($username, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                    $fromRepArray = array($username, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);

                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                    $payLoad = array(
                        "user_id" => $userCheck['User']['id'],
                        "time" => time()
                    );
                    $token = $this->setToken($payLoad);
                    $userCheck['User']['token'] = $token;
                    $data = $this->logindata($userCheck);
                    echo $this->successOutputResult('View profile', json_encode($data));
                    exit;
                }
            }
        }
        exit;
    }

    public function apps_getpagedetail() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 0);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $slug = $userData['slug'];
        $pagedetail = $this->Pages->find('first', array('conditions' => array('Pages.static_page_heading' => $slug, 'Pages.status' => '1')));
        $detailArray = $pagedetail['Pages'];
        echo $this->successOutputResult('Page Detail', json_encode($detailArray));
        exit;
    }

    // save job    
    public function apps_contactUs() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 0);
        $user_id = $tokenData['user_id'];
        $jsonStr = $_POST['jsonData'];
//        $jsonStr = '{"email":"vikas.sharma@logicspice.com","username":"4","message":"test","subject":"subject1"}';
        $userData = json_decode($jsonStr, true);
        $email = $userData['email'];
        $username = $userData['username'];
        $message = $userData['message'];
        $subjectbyuser = $userData['subject'];

        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');

        $currentYear = date('Y', time());
        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';
        $contact_details = $this->Setting->find('first');
        $this->Email->to = $contact_details['Setting']['email'];
        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='6'"));
        //$this->Email->subject = $this->data["User"]['subject'];
        $toSubArray = array('[!username!]', '[!email!]', '[!subject!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
        $fromSubArray = array($username, $email, $subjectbyuser, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $subjectbyuser);
        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
        $this->Email->subject = $subjectToSend;


        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
        $this->Email->from = $site_title . "<" . $mail_from . ">";

        $toRepArray = array('[!username!]', '[!email!]', '[!subject!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
        $fromRepArray = array($username, $email, $subjectbyuser, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $subjectbyuser);
        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
        $this->Email->layout = 'default';
        $this->set('messageToSend', $messageToSend);
        $this->Email->template = 'email_template';
        $this->Email->sendAs = 'html';
        $this->Email->send();
        $this->Email->reset();


        $this->Email->to = $email;
        //$this->Email->cc =$this->Admin->field('cc_email', array('Admin.id' => 1));
        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='17'"));

        $toSubArray = array('[!username!]', '[!email!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
        $fromSubArray = array($username, $email, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $subjectbyuser);
        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
        $this->Email->subject = $subjectToSend;


        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
        $this->Email->from = $site_title . "<" . $mail_from . ">";

        $toRepArray = array('[!username!]', '[!email!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
        $fromRepArray = array($username, $email, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $subjectbyuser);
        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
        $this->Email->layout = 'default';
        $this->set('messageToSend', $messageToSend);
        $this->Email->template = 'email_template';
        $this->Email->sendAs = 'html';
        $this->Email->send();
        echo $this->successOutputMsg(__d('controller', 'Your enquiry has been successfully sent to us!', true));
        exit;
    }

    public function apps_uploadVideo() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);

        $userId = $tokenData['user_id'];

        $UseroldImage = $this->User->find('first', array('conditions' => array('User.id' => $userId), 'fields' => array('User.video', 'User.slug')));
        $this->set("UseroldImage", $UseroldImage);

        $imageData = $_FILES['video'];
        $getextention = $this->PImage->getExtension($imageData['name']);
        $extention = strtolower($getextention);

        $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
        $toReplace = "-";
        $imageData['name'] = str_replace($specialCharacters, $toReplace, $imageData['name']);
        $imageData['name'] = str_replace("&", "and", $imageData['name']);
        $imageArray = $imageData;
        $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_VIDEO_PATH);


        $profilevideo = $returnedUploadImageArray[0];
        chmod(UPLOAD_VIDEO_PATH .$returnedUploadImageArray[0], 0755);
        if (isset($UseroldImage['User']['video']) && $UseroldImage['User']['video'] != "") {
            @unlink(UPLOAD_VIDEO_PATH . $UseroldImage['User']['video']);
        }

        $this->User->updateAll(array('User.video' => "'$profilevideo'"), array("User.id" => $userId));
        echo $this->successOutputMsg(__d('controller', 'Your video has been Uploaded successfully.', true));
        exit;
    }

    public function sendmailjobseeker($slug = null) {
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->userLoginCheck();

        $this->layout = "client";
        $title_for_pages = $site_title . " :: " . $tagline . " - ";

        $this->set('title_for_layout', $title_for_pages . __d('user', 'Send Email', true));

        $msgString = '';

        $userId = $this->Session->read("user_id");
        $recruiterInfo = $this->User->findById($userId);
        $recruiter_email = $recruiterInfo['User']['email_address'];
        $recruiter_company = $recruiterInfo['User']['company_name'];

        if ($this->data) {
            if (empty($this->data["Candidate"]["id"])) {
                $msgString .= "- User Name is required field.<br>";
            }
            if (empty($this->data["Candidate"]["subject"])) {
                $msgString .= "- Subject is required field.<br>";
            }
            if (empty($this->data["Candidate"]["message"])) {
                $msgString .= "- Message is required field.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                echo json_encode(array('status' => 'error', 'message' => strip_tags($msgString)));
                die;
            } else {
                $type = $this->data["Candidate"]["type"];
                $imageArr = array();
                $userInfo = $this->User->find('first', array('fields' => array('User.id', 'User.first_name', 'User.email_address'), 'conditions' => array('User.id' => $this->data["Candidate"]["id"])));
                if ($userInfo) {
                    $image = '';
                    if ($this->request->data['Candidate']['files']) {
                        foreach ($this->request->data['Candidate']['files'] as $images) {
                            if (!empty($images['name'])) {
                                $mailArray = $images;
                                $returnedUploadImageArray = $this->PImage->upload($mailArray, UPLOAD_MAIL_PATH);
                                $imageArr[] = $returnedUploadImageArray[0];
                                 chmod(UPLOAD_MAIL_PATH .$returnedUploadImageArray[0], 0755);
                            }
                        }
                        $image = implode(',', $imageArr);
                    }

                    $maildata['Mail']['to_id'] = $userInfo["User"]["id"];
                    $maildata['Mail']['from_id'] = $userId;
                    $maildata['Mail']['slug'] = $this->stringToSlugUnique($this->data["Candidate"]["subject"], 'Mail', 'slug');
                    ;
                    $maildata['Mail']['subject'] = $this->data["Candidate"]["subject"];
                    $maildata['Mail']['message'] = $this->data["Candidate"]["message"];
                    $maildata['Mail']['files'] = $image;
                    $maildata['Mail']['status'] = '1';
//                    print_r($maildata);die;
//                    $this->Mail->save($maildata);
                    if ($this->Mail->save($maildata)) {
                        $mailId = $this->Mail->id;

                        $mailDetail = $this->Mail->findById($mailId);
                        $files = explode(',', $mailDetail['Mail']['files']);
                        $mailFileArray = array();
                        if ($imageArr) {
                            foreach ($imageArr as $file) {
                                $mailFileArray[$file] = UPLOAD_MAIL_PATH . $file;
                            }
                        }

                        $userName = ucfirst($userInfo["User"]["first_name"]);
                        $email = ucfirst($userInfo["User"]["email_address"]);
                        //echo "<pre>"; print_r($userInfo); exit;

                        $this->Email->to = $email;
                        $message = nl2br($this->data['Candidate']['message']);
                        $subject = $this->data['Candidate']['subject'];
                        $currentYear = date('Y', time());
                        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                        $emData = $this->Emailtemplate->getSubjectLang();
                        $subjectField = $emData['subject'];
                        $templateField = $emData['template'];

                        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='45'"));

                        $toSubArray = array('[!username!]', '[!MESSAGE!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
                        $fromSubArray = array($userName, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $subject);
                        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                        $this->Email->subject = $subjectToSend;
                        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                        $this->Email->from = $site_title . "<" . $mail_from . ">";
                        $toRepArray = array('[!username!]', '[!MESSAGE!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]', '[!company_email!]', '[!company!]');
                        $fromRepArray = array($userName, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $subject, $recruiter_email, $recruiter_company);
                        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                        $this->Email->layout = 'default';
                        $this->set('messageToSend', $messageToSend);
                        $this->Email->template = 'email_template';
                        if (!empty($mailFileArray))
                            $this->Email->attachments = $mailFileArray;
                        $this->Email->sendAs = 'html';
                        $this->Email->send();
                        $this->Email->reset();
                        $this->Session->write('success_msg', __d('controller', 'You have sent the email to the candidate successfully.', true));
                    }
                }
                if (isset($type) & $type == 'candidates') {
                    $this->redirect('/candidates/mailhistory');
                } else {
                    $this->redirect('/candidates/profile/' . $slug);
                }
            }
        }
    }

    public function sendmailemployer($slug = null) {
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->userLoginCheck();

        $this->layout = "client";
        $title_for_pages = $site_title . " :: " . $tagline . " - ";

        $this->set('title_for_layout', $title_for_pages . __d('user', 'Send Email', true));

        $msgString = '';

        $userId = $this->Session->read("user_id");
        $recruiterInfo = $this->User->findById($userId);
        $recruiter_email = $recruiterInfo['User']['email_address'];
        $recruiter_company = $recruiterInfo['User']['company_name'];

        if ($this->data) {
            if (empty($this->data["Candidate"]["id"])) {
                $msgString .= "- User Name is required field.<br>";
            }
            if (empty($this->data["Candidate"]["subject"])) {
                $msgString .= "- Subject is required field.<br>";
            }
            if (empty($this->data["Candidate"]["message"])) {
                $msgString .= "- Message is required field.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                echo json_encode(array('status' => 'error', 'message' => strip_tags($msgString)));
                die;
            } else {
                $imageArr = array();
                $type = $this->data["Candidate"]["type"];
                $userInfo = $this->User->find('first', array('fields' => array('User.id', 'User.first_name', 'User.email_address'), 'conditions' => array('User.id' => $this->data["Candidate"]["id"])));
                if ($userInfo) {
                    $image = '';
                    if ($this->request->data['Candidate']['files']) {
                        foreach ($this->request->data['Candidate']['files'] as $images) {
                            if (!empty($images['name'])) {
                                $mailArray = $images;
                                $returnedUploadImageArray = $this->PImage->upload($mailArray, UPLOAD_MAIL_PATH);
                                $imageArr[] = $returnedUploadImageArray[0];
                                 chmod(UPLOAD_MAIL_PATH .$returnedUploadImageArray[0], 0755);
                            }
                        }
                        $image = implode(',', $imageArr);
                    }

                    $maildata['Mail']['to_id'] = $userInfo["User"]["id"];
                    $maildata['Mail']['from_id'] = $userId;
                    $maildata['Mail']['slug'] = $this->stringToSlugUnique($this->data["Candidate"]["subject"], 'Mail', 'slug');
                    ;
                    $maildata['Mail']['subject'] = $this->data["Candidate"]["subject"];
                    $maildata['Mail']['message'] = $this->data["Candidate"]["message"];
                    $maildata['Mail']['files'] = $image;
                    $maildata['Mail']['status'] = '1';
//                    print_r($maildata);die;
//                    $this->Mail->save($maildata);
                    if ($this->Mail->save($maildata)) {
                        $mailId = $this->Mail->id;

                        $mailDetail = $this->Mail->findById($mailId);
                        $files = explode(',', $mailDetail['Mail']['files']);
                        $mailFileArray = array();
                        if ($imageArr) {
                            foreach ($imageArr as $file) {
                                $mailFileArray[$file] = UPLOAD_MAIL_PATH . $file;
                            }
                        }

                        $userName = ucfirst($userInfo["User"]["first_name"]);
                        $email = ucfirst($userInfo["User"]["email_address"]);
                        //echo "<pre>"; print_r($userInfo); exit;

                        $this->Email->to = $email;
                        $message = nl2br($this->data['Candidate']['message']);
                        $subject = $this->data['Candidate']['subject'];
                        $currentYear = date('Y', time());
                        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                        $emData = $this->Emailtemplate->getSubjectLang();
                        $subjectField = $emData['subject'];
                        $templateField = $emData['template'];

                        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='51'"));

                        $toSubArray = array('[!user_name!]', '[!MESSAGE!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
                        $fromSubArray = array($userName, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $subject);
                        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                        $this->Email->subject = $subjectToSend;
                        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                        $this->Email->from = $site_title . "<" . $mail_from . ">";
                        $toRepArray = array('[!user_name!]', '[!MESSAGE!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]', '[!email_address!]');
                        $fromRepArray = array($userName, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $subject, $recruiter_email);
                        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                        $this->Email->layout = 'default';
                        $this->set('messageToSend', $messageToSend);
                        $this->Email->template = 'email_template';
                        if (!empty($mailFileArray))
                            $this->Email->attachments = $mailFileArray;
                        $this->Email->sendAs = 'html';
                        $this->Email->send();
                        $this->Email->reset();
                        $this->Session->write('success_msg', __d('controller', 'You have sent the email to the employer successfully.', true));
                    }
                }
                if (isset($type) & $type == 'candidates') {
                    $this->redirect('/candidates/mailhistory');
                } else {
                    $this->redirect('/candidates/profile/' . $slug);
                }
            }
        }
    }

    public function deleteAccount() {
        $this->layout = "client";
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Delete Account', true));
        $msgString = '';
        $this->set('deleteaccount', 'active');
        $this->userLoginCheck();
        $userId = $this->Session->read("user_id");
        if ($this->data) {

            if (empty($this->data["User"]["reason"])) {
                $msgString .= __d('controller', 'Reason is required field.', true) . "<br>";
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
            } else {
                $reason = $this->data["User"]["reason"];
                $userCheck = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
                $image = $userCheck['User']['profile_image'];
                $email = $userCheck["User"]["email_address"];
                $name = $userCheck['User']['first_name'] . " " . $userCheck['User']['last_name'];
                $adminDetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => 1)));
                if (!empty($adminDetail)) {
                    $this->Email->to = $adminDetail['Admin']['email'];
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='50'"));

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];
                    $currentYear = date('Y', time());

                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . MAIL_FROM . '">' . MAIL_FROM . '</a>';

                    $toSubArray = array('[!username!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]');
                    $fromSubArray = array('Admin', $currentYear, HTTP_PATH, $site_title, $sitelink);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                    $this->Email->subject = $subjectToSend;


                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";
                    $toRepArray = array('[!email!]', '[!username!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!reason!]');
                    $fromRepArray = array($email, $name, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $reason);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
//                    print_r($this->Email->send());
                }
//                die;
                if ($this->User->delete($userId)) {
                    @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $image);
                    @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $image);
                    @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $image);
                }
                $this->Session->delete('user_id');
                $this->Session->delete('user_name');
                $this->Session->delete('email_address');

                $this->Session->write('success_msg', __d('controller', 'Your account has been deleted successfully.', true));
                $this->redirect('/users/login');
            }
        }
    }

    public function mailhistory() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('user', 'Mail History', true));

        $this->userLoginCheck();


        $userId = $this->Session->read("user_id");
        $userdetail = $this->User->findById($userId);

        $this->set('userdetail', $userdetail);
//        $this->set('mail_type', $type);
        $this->set('mailhistory', 'active');
//        $condition = array('Mail.id !='=>0);
//        if ($type == 'inbox') {
//            $condition = array('Mail.to_id' => $userId);
//        } else {
//            $condition = array('Mail.from_id' => $userId);
//        }
//       $condition = array('Mail.to_id' => $userId);
        $condition = " Mail.to_id = $userId OR (Mail.from_id = $userId)";
//        $condition = array('Mail.from_id'=>$userId);  
//        $condition[]= " (Mail.is_delete NOT IN ($userId))";
//         $condition[] = "(NOT FIND_IN_SET('" . $userId . "',Mail.is_delete1))";
        $separator = array();
        $urlSeparator = array();

        $order = ' Mail.id DESC';
        //$order = 'Job.id ASC';

        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);

        $this->paginate['Mail'] = array('conditions' => $condition, 'limit' => '10', 'page' => '1', 'order' => $order);
        $this->set('mails', $this->paginate('Mail'));
//        echo '<pre>'; print_r($this->paginate['Mail']);die;
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'candidates';
            $this->render('mail');
        }
    }

    public function maildetail($slug = null) {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('user', 'Mail Detail', true));

        $this->userLoginCheck();

        $mailDetail = $this->Mail->find('first', array('conditions' => array('Mail.slug' => $slug)));
//        echo '<pre>';print_r($mailDetail);die;
        $jobslug = $mailDetail['Job']['slug'];
        $jobInfo = $this->Job->findBySlug($jobslug);
        $this->set('jobInfo', $jobInfo);
        $userId = $this->Session->read("user_id");
        $userdetail = $this->User->findById($userId);

        $this->set('userdetail', $userdetail);
        $this->set('mailDetail', $mailDetail);
        $this->set('mailhistory', 'active');
    }

    public function apps_getplanslist() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $userId = $tokenData['user_id'];
        $userCheck = $this->User->findById($userId);
        if ($userCheck['User']['user_type'] == 'recruiter') {
            $condition = array('Plan.status' => 1, 'Plan.planuser' => 'employer');
        } else {
            $condition = array('Plan.status' => 1, 'Plan.planuser' => 'jobseeker');
        }
        $plansList = $this->Plan->find('all', array('conditions' => $condition, 'order' => array('Plan.amount' => 'ASC')));
        $planArray = array();

        global $planFeatuersMax;
        global $planFeatuers;
        global $planFeatuersDis;
        global $planType;
        global $planFeatuersHelpText;
        $cplanId = 0;
        $sdate = date('Y-m-d');
        $sdateDIS = date('M d, Y');
        $planInfo = $this->Plan->getcurrentplan($userId);
        $myPlan = $this->Plan->getcurrentplanEXP($userId, 1);
//        print_r($planInfo);die;
        if ($plansList) {
            $i = 0;
            foreach ($plansList as $val) {
                $tpvalue = $val['Plan']['type_value'];
                if ($val['Plan']['type'] == 'Months') {
                    $edate = date('Y-m-d', strtotime($sdate . " + $tpvalue Months"));
                    $edateDIS = date('M d, Y', strtotime($sdate . " + $tpvalue Months"));
                } else {
                    $edate = date('Y-m-d', strtotime($sdate . " + $tpvalue Years"));
                    $edateDIS = date('M d, Y', strtotime($sdate . " + $tpvalue Years"));
                }
                $fvalues = $val['Plan']['fvalues'];
                $featureIds = explode(',', $val['Plan']['feature_ids']);
                $fvalues = json_decode($val['Plan']['fvalues'], true);

                $planArray[$i]['id'] = $val['Plan']['id'];
                $planArray[$i]['plan_name'] = $val['Plan']['plan_name'];
                $planArray[$i]['is_active'] = 0;
                if ($planInfo && $planInfo['Plan']['id'] == $val['Plan']['id']) {
                    $planArray[$i]['is_active'] = 1;
                }
                $planArray[$i]['plan_name'] = $val['Plan']['plan_name'];
                $planArray[$i]['amount'] = $val['Plan']['amount'];
                $planArray[$i]['type'] = $val['Plan']['type'];
                $planArray[$i]['type_value'] = $val['Plan']['type_value'];
                $planArray[$i]['slug'] = $val['Plan']['slug'];
                $joncnt = '';
                $ddd = '';
                $resumedow = '';
                $planArray[$i]['AccessCandidateSearching'] = 0;
                if ($featureIds) {

                    foreach ($featureIds as $fid) {

                        if ($fid == 3) {
                            $planArray[$i]['AccessCandidateSearching'] = 1;
                        }
                        if (array_key_exists($fid, $fvalues)) {
                            if ($fvalues[$fid] == $planFeatuersMax[$fid]) {
                                if ($fid == 1) {
                                    $joncnt = 'Unlimited';
                                } else
                                if ($fid == 2) {
                                    $ddd = 'Unlimited';
                                }
                                if ($fid == 4) {
                                    $resumedow = 'Unlimited';
                                }
                            } else {
                                if ($fid == 1) {
                                    $joncnt = $fvalues[$fid];
                                } else
                                if ($fid == 2) {
                                    $ddd = $fvalues[$fid];
                                }
                                if ($fid == 4) {
                                    $resumedow = $fvalues[$fid];
                                }
                            }
                        }
                    }
                }

                $planArray[$i]['job_post'] = $joncnt;
                $planArray[$i]['resume_download'] = $ddd;
                $planArray[$i]['job_apply'] = $resumedow;
                $i++;
            }
        }

        echo $this->successOutputResult('Plans List', json_encode($planArray));
        exit;
    }

    public function apps_purchaseplan() {

        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');
        $userId = $tokenData['user_id'];
        $this->request->data["Plan"] = $userData;
        $planId = $this->data["Plan"]["plan_id"];
        $transactionId = $this->data["Plan"]["transaction_id"];
        $aplimp = $this->data["Plan"]["aplimp"];
//        echo $planId;die;
//        $planInfo = $this->Plan->findById($planId);
        $condition = array();
        $condition[] = " ( (Plan.id = '$planId' ) OR (Plan.plan_name = '$planId' )) ";
        $planInfo = $this->Plan->find('first', array('conditions' => $condition));
        if (empty($planInfo)) {
            echo $this->errorOutputResult(__d('home', 'No record found', true));
            exit;
        }

        $payment_number = 'pay-' . date('Ymd') . time();
        $this->request->data['Payment']['user_id'] = $userId;
        $this->request->data['Payment']['payment_number'] = $payment_number;
        $this->request->data['Payment']['transaction_id'] = $transactionId;
        $this->request->data['Payment']['payment_status'] = 'completed';
        $this->request->data['Payment']['price'] = $planInfo['Plan']['amount'];
        $this->request->data['Payment']['plan_id'] = $planInfo['Plan']['id'];
        $this->request->data['Payment']['status'] = 0;
        $this->request->data['Payment']['slug'] = $payment_number . $userId;
        if ($aplimp) {
            $aplimp = 1;
        }
        $this->request->data['Payment']['aplimp'] = $aplimp;
//        echo '<pre>';
//        print_r($this->data);
////        print_r($planDetail);
//        die;
        if ($this->Payment->save($this->data)) {
            $payment_id = $this->Payment->id;
            $paymentInfo = $this->Payment->find('first', array('conditions' => array('Payment.id' => $payment_id)));

            $email = $paymentInfo["User"]["email_address"];
            $companyname = $paymentInfo["User"]["company_name"];
            $name = $paymentInfo["User"]["first_name"] . ' ' . $paymentInfo["User"]["last_name"];
            $planName = $paymentInfo["Plan"]["plan_name"] . ' Plan';
            $amount = CURR . ' ' . $paymentInfo["Plan"]["amount"];
            $date = date('F d, Y h:i A');

            $this->request->data['UserPlan']['payment_id'] = $paymentInfo['Payment']['id'];
            $this->request->data['UserPlan']['user_id'] = $paymentInfo['Payment']['user_id'];
            $this->request->data['UserPlan']['plan_id'] = $paymentInfo['Payment']['plan_id'];
            $this->request->data['UserPlan']['features_ids'] = $paymentInfo['Plan']['feature_ids'];
            $this->request->data['UserPlan']['fvalues'] = $paymentInfo['Plan']['fvalues'];
            $this->request->data['UserPlan']['amount'] = $paymentInfo['Plan']['amount'];


            $lastPlan = $this->UserPlan->find('first', array('conditions' => array('UserPlan.user_id' => $paymentInfo['Payment']['user_id']), 'order' => array('UserPlan.id' => 'DESC')));
            $sdate = date('Y-m-d');
            if ($lastPlan) {
                if ($paymentInfo['Payment']['aplimp']) {
                    $this->UserPlan->updateAll(array('UserPlan.is_expire' => "'1'"), array('UserPlan.id' => $lastPlan['UserPlan']['id']));
                    $sdate = date('Y-m-d');
                } else {
                    $lastend_date = $lastPlan['UserPlan']['end_date'];
                    $sdate = date('Y-m-d', strtotime($lastend_date . ' + 1 days'));
                }
            }
            $tpvalue = $paymentInfo['Plan']['type_value'];
            if ($paymentInfo['Plan']['type'] == 'Months') {
                $edate = date('Y-m-d', strtotime($sdate . " + $tpvalue Months"));
            } else {
                $edate = date('Y-m-d', strtotime($sdate . " + $tpvalue Years"));
            }
            $this->request->data['UserPlan']['start_date'] = $sdate;
            $this->request->data['UserPlan']['end_date'] = $edate;
            $this->request->data['UserPlan']['slug'] = 'uplan-' . $paymentInfo['Payment']['user_id'] . time();


            $this->UserPlan->save($this->data['UserPlan']);

            $payinfo = '<p style="color:#434343; margin:10px 0 0;"><b>' . __d('controller', 'Plan Name', true) . ':</b> ' . $planName . '</p>';
            $payinfo .= '<p style="color:#434343; margin:0px 0 0;"><b>' . __d('controller', 'Amount', true) . ':</b> ' . $amount . '</p>';
            $payinfo .= '<p style="color:#434343; margin:0px 0 0;"><b>' . __d('controller', 'Transaction ID', true) . ':</b> ' . $transactionId . '</p>';
            $payinfo .= '<p style="color:#434343; margin:0px 0 0;"><b>' . __d('controller', 'Date', true) . ':</b> ' . $date . '</p>';

            $currentYear = date('Y', time());
            $this->Email->to = $email;
            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='41'"));

            $emData = $this->Emailtemplate->getSubjectLang();
            $subjectField = $emData['subject'];
            $templateField = $emData['template'];

            $toSubArray = array('[!username!]', '[!payinfo!]', '[!SITE_TITLE!]', '[!DATE!]');
            $fromSubArray = array($name, $payinfo, $site_title, $currentYear);
            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
            $this->Email->subject = $subjectToSend;

            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
            $this->Email->from = $site_title . "<" . $mail_from . ">";

            $toRepArray = array('[!username!]', '[!payinfo!]', '[!SITE_TITLE!]', '[!DATE!]');
            $fromRepArray = array($name, $payinfo, $site_title, $currentYear);
            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
            $this->Email->layout = 'default';
            $this->set('messageToSend', $messageToSend);
            $this->Email->template = 'email_template';
            $this->Email->sendAs = 'html';
            $this->Email->send();

            $adminInfo = $this->Admin->findById(1);

            $this->Email->to = $adminInfo['Admin']['email'];
            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='42'"));
            $toSubArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]', '[!transactionId!]', '[!amountPaid!]', '[!company_name!]');
            $fromSubArray = array($name, $jobTitle, $site_title, $date, $transactionId, $amount, $companyname);
            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
            $this->Email->subject = $subjectToSend;

            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
            $this->Email->from = $site_title . "<" . $mail_from . ">";

            $toRepArray = array('[!username!]', '[!payinfo!]', '[!SITE_TITLE!]', '[!DATE!]');
            $fromRepArray = array($name, $payinfo, $site_title, $date);
            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
            $this->Email->layout = 'default';
            $this->set('messageToSend', $messageToSend);
            $this->Email->template = 'email_template';
            $this->Email->sendAs = 'html';
            $this->Email->send();
        }
        echo $this->successOutputMsg(__d('controller', 'You have successfully completed payment for your membership plan.', true));
        exit;
    }

    public function apps_getpaymenthistory() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $userplansList = $this->UserPlan->find('all', array('conditions' => array('UserPlan.user_id' => $userId), 'order' => array('UserPlan.id' => 'desc')));
        $planArray = array();
        if ($userplansList) {
            $i = 0;
            foreach ($userplansList as $val) {
                $planArray[$i]['id'] = $val['UserPlan']['id'];
                $planArray[$i]['plan_name'] = $val['Plan']['plan_name'];
                $planArray[$i]['user_id'] = $val['UserPlan']['user_id'];
                $planArray[$i]['amount'] = $val['UserPlan']['amount'];
                $planArray[$i]['start_date'] = $val['UserPlan']['start_date'];
                $planArray[$i]['end_date'] = $val['UserPlan']['end_date'];
                $planArray[$i]['transaction_id'] = $val['Payment']['transaction_id'];
                $planArray[$i]['created'] = $val['UserPlan']['created'];
                $i++;
            }
        }
        echo $this->successOutputResult('Payment History', json_encode($planArray));
        exit;
    }

}

?>
 