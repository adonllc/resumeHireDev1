<?php

class UsersController extends AppController {

    public $uses = array('Admin', 'Page', 'Certificate', 'Emailtemplate', 'User', 'Setting', 'Country', 'State', 'City', 'Industry', 'Swear', 'PostCode', 'Location', 'Education', 'Course', 'Experience', 'Job', 'AutoAlert', 'Category', 'Plan', 'Payment', 'UserPlan', 'AlertLocation', 'AlertJob', 'Skill', 'Favorite', 'JobApply', 'SiteSetting', 'Mail');
    public $helpers = array('Html', 'Form', 'Fck', 'Javascript', 'Ajax', 'Text', 'Number', 'Js');
    public $paginate = array('limit' => '20', 'page' => '1', 'order' => array('User.id' => 'DESC'));
    public $components = array('RequestHandler', 'Email', 'Upload', 'PImageTest', 'PImage', 'Captcha', 'Common', 'Gmaillogin');
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
        $this->set('title_for_layout', $title_for_pages . "Employer List");

        $this->set('user_list', 'active');
        $this->set('default', '2');
        $condition = array('user_type' => 'recruiter');
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
            $condition[] = " (`User`.`company_name` LIKE '%" . addslashes($userName) . "%' OR `User`.`first_name` LIKE '%" . addslashes($userName) . "%' or concat(`User.first_name`,' ',`User.last_name`) LIKE '%" . addslashes($userName) . "%' or `User`.`email_address` LIKE '%" . addslashes($userName) . "%' or `User`.`last_name` LIKE '%" . addslashes($userName) . "%' OR `User`.`company_name` LIKE '%" . addslashes($userName) . "%' ) ";
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
        $this->set('users', $this->paginate('User'));
        //pr($condition);


        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/users';
            $this->render('index');
        }
    }

    public function admin_addusers() {

        $this->layout = "admin";
        $this->set('default', '2');
        $this->set('add_user', 'active');

        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getMailConstant('url');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Employer");

        $msgString = '';
        global $extentions;

        $this->set('stateList', '');
        $this->set('cityList', '');
        $industryList = $this->Industry->getIndustryList();
        $this->set('industryList', $industryList);

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        if ($this->data) {

            if (trim($this->data["User"]["company_name"]) == '') {
                $msgString .= "- Company name is required field.<br>";
            }

            if (trim($this->data["User"]["company_about"]) == '') {
                $msgString .= "- Company profile is required field.<br>";
            }

//            if (empty($this->data["User"]["abn"])) {
//                $msgString .="- ABN is required field.<br>";
//            }
            if (empty($this->data["User"]["position"])) {
                $msgString .= "- Position is required field.<br>";
            }
            if (empty($this->data["User"]["first_name"])) {
                $msgString .= "- First name is required field.<br>";
            }

            if (empty($this->data["User"]["last_name"])) {
                $msgString .= "- Last name is required field.<br>";
            }

            if (empty($this->data["User"]["address"])) {
                $msgString .= "- Address  is required field.<br>";
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
            if (empty($this->data["User"]["company_contact"])) {
                $msgString .= "- Company number is required field.<br>";
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
                $passwordPlain = $this->data["User"]["password"];
                $salt = uniqid(mt_rand(), true);
                $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');

                $this->request->data['User']['password'] = $new_password;

                $this->request->data['User']['first_name'] = trim($this->data['User']['first_name']);
                $this->request->data['User']['last_name'] = trim($this->data['User']['last_name']);
                $this->request->data['User']['status'] = 1;
                $this->request->data['User']['activation_status'] = 1;
                $this->request->data['User']['user_type'] = 'recruiter';
                $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim($this->data['User']['first_name']) . ' ' . trim($this->data['User']['last_name']), 'User', 'slug');

                if ($this->User->save($this->data)) {

                    $email = $this->data["User"]["email_address"];
                    $username = $this->data['User']['first_name'] . ' ' . $this->data['User']['last_name'];
                    $firstname = $this->data["User"]["first_name"];

                    $this->Email->to = $email;

                    $currentYear = date('Y', time());
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='28'"));

                    $toSubArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_URL!], [!first_name!]');
                    $fromSubArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $site_url, $firstname);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_URL!]', '[!first_name!]');
                    $fromRepArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $site_url, $firstname);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();

                    $this->Session->setFlash('Employer details saved successfully', 'success_msg');
                    $this->redirect('/admin/users/index');
                }
            }
        }
    }

    public function admin_editusers($slug = null) {

        $this->layout = "admin";
        $this->set('default', '2');
        $this->set('user_list', 'active');

        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getMailConstant('url');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Employer");

        $msgString = '';
        global $extentions;
        $changedPassword = 0;
        $emailaddress = $this->User->field('email_address', array('slug' => $slug));

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        if ($this->data) {


            if (empty($this->data["User"]["company_name"])) {
                $msgString .= "- Company Name is required field.<br>";
            }

            if (trim($this->data["User"]["company_about"]) == '') {
                $msgString .= "- Company profile is required field.<br>";
            }

            if (empty($this->data["User"]["first_name"])) {
                $msgString .= "- First Name is required field.<br>";
            }
            if (empty($this->data["User"]["last_name"])) {
                $msgString .= "- Last Name is required field.<br>";
            }

            if (empty($this->data["User"]["address"])) {
                $msgString .= "- Address  is required field.<br>";
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

            /*  if (empty($this->data["User"]["location"])) {
              $msgString .="- Location is required field.<br>";
              } */

            if (empty($this->data["User"]["contact"])) {
                $msgString .= "- Contact number is required field.<br>";
            }
            if (empty($this->data["User"]["company_contact"])) {
                $msgString .= "- Company number is required field.<br>";
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

            if ($this->data["User"]["profile_image"]["name"]) {

                $getextention = $this->PImage->getExtension($this->data['User']['profile_image']['name']);
                $extention = strtolower($getextention);
                if ($this->data['User']['profile_image']['size'] > '2097152') {
                    $msgString .= "- Max file size upload is 2MB.<br>";
                } elseif (!in_array($extention, $extentions)) {
                    $msgString .= "- Not Valid Extention.<br>";
                }
            }

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
                } else if ($width < 100 && $height < 100) {

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
                    chmod(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
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
                        $username = ucwords($this->data['User']['first_name'] . ' ' . $this->data['User']['last_name']);
                        $firstname = $this->data["User"]["first_name"];

                        $this->Email->to = $email;

                        $currentYear = date('Y', time());
                        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='29'"));

                        $toSubArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_URL!], [!first_name!]');
                        $fromSubArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $site_url, $firstname);
                        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                        $this->Email->subject = $subjectToSend;

                        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                        $this->Email->from = $site_title . "<" . $mail_from . ">";

                        $toRepArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_URL!]', '[!first_name!]');
                        $fromRepArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $site_url, $firstname);
                        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                        $this->Email->layout = 'default';
                        $this->set('messageToSend', $messageToSend);
                        $this->Email->template = 'email_template';
                        $this->Email->sendAs = 'html';
                        $this->Email->send();
                    }
                    $this->Session->setFlash('Employer details updated successfully', 'success_msg');
                    $this->redirect('/admin/users/index');
                }
            }
        } elseif ($slug != '') {
            $id = $this->User->field('id', array('User.slug' => $slug));
            $this->User->id = $id;
            $this->data = $this->User->read();
            $this->request->data['User']['old_password'] = $this->data['User']['password'];
            $this->request->data['User']['old_profile_image'] = $this->data['User']['profile_image'];

            /* $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['User']['postal_code'])));
              $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
              $this->set('stateList', $stateList);
              $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
              $this->set('cityList', $cityList); */
        }
    }

    public function admin_activateuser($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->User->field('id', array('User.slug' => $slug));
            $cnd = array("User.id = $id");
            $this->User->updateAll(array('User.status' => "'1'", 'User.activation_status' => "'1'"), $cnd);
            $this->set('action', '/admin/users/deactivateuser/' . $slug);
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
            $this->set('action', '/admin/users/activateuser/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deleteusers($slug = NULL, $type = NULL) {
        $this->set('list_users', 'active');
        $this->set('default', '1');
        if ($slug != '') {
            $id = $this->User->field('id', array('User.slug' => $slug));
            $image = $this->User->field('profile_image', array('User.slug' => $slug));
            if ($this->User->delete($id)) {
                @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $image);
                @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $image);
                @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $image);
            }
            $this->Session->setFlash('Employer details deleted successfully', 'success_msg');
        }
        $this->redirect('/admin/users/index');
    }

    public function admin_verifyNow($slug = NULL, $type = NULL) {
        $this->set('list_users', 'active');
        $this->set('default', '1');
        if ($slug != '') {
            $id = $this->User->field('id', array('User.slug' => $slug));

            $cnd = array("User.id = $id");
            $this->User->updateAll(array('User.verify' => "'1'"), $cnd);
            $this->Session->setFlash('Employer verified successfully', 'success_msg');
        }
        $this->redirect('/admin/users/index');
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
            $this->redirect(array('controller' => 'users', 'action' => 'editusers', $userSlug));
        }
    }

    public function admin_downloadInvoice($filename = null) {
        set_time_limit(0);
        $file_path = UPLOAD_FULL_INVOICE_IMAGE_PATH . $filename . '.pdf';
        $filename = substr($filename, 6);
        $this->Common->output_file($file_path, $filename);
        exit;
    }

    /*
     * Select logo for home page
     */

    public function admin_selectforslider() {

        $this->layout = "admin";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Select for slider");

        $this->set('selectslider_list', 'active');
        $this->set('default', '2');
        $condition = array('AND' => array('User.user_type' => 'recruiter', 'User.activation_status' => 1, 'User.status' => 1, 'NOT' => array(
                    'User.profile_image' => ' '
        )));
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
        $this->set('users', $this->paginate('User'));
        //pr($condition);


        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/users';
            $this->render('selectslider');
        }
    }

    public function admin_activateslider($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->User->field('id', array('User.slug' => $slug));
            $cnd = array("User.id = $id");
            $this->User->updateAll(array('User.home_slider' => "'1'"), $cnd);
            $this->set('action', '/admin/users/deactivateslider/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivateslider($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->User->field('id', array('User.slug' => $slug));
            $cnd = array("User.id = $id");
            $this->User->updateAll(array('User.home_slider' => "'0'"), $cnd);
            $this->set('action', '/admin/users/activateslider/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function gmailSession($usertype) {

        $this->Session->write('loginusertype', $usertype);
        //exit;
    }

    /*
     * Gmail Account login
     */

    function gmaillogin() {
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');

        $userid = $this->Session->read("user_id");
        $userType = $this->Session->read("loginusertype");
        if (isset($_GET['code'])) {
            try {
                $data = $this->Gmaillogin->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);
                $user_info = $this->Gmaillogin->GetUserProfileInfo($data['access_token']);

                $user_id = $user_info['id'];
                $user_name = filter_var($user_info['displayName'], FILTER_SANITIZE_SPECIAL_CHARS);
                $email = filter_var($user_info['emails'][0]['value'], FILTER_SANITIZE_EMAIL);
                $profile_url = filter_var($user_info['url'], FILTER_VALIDATE_URL);
                $profile_image_url = filter_var($user_info['image']['url'], FILTER_VALIDATE_URL);
                $userInfo = $this->User->find("first", array("conditions" => array("User.email_address" => $email)));

                if ($userInfo) {
                    if ($userType == 'jobseeker') {
                        if ($userInfo['User']['user_type'] !== 'candidate') {
                            $this->Session->setFlash(__d('controller', 'Please check the email id, it does not belongs to jobseeker account.', true), 'error_msg');
                            echo "<script>window.close();window.opener.location.reload();</script>";
                            exit;
                        }
                    } else if ($userType == 'employer') {
                        if ($userInfo['User']['user_type'] !== 'recruiter') {
                            $this->Session->setFlash(__d('controller', 'Please check the email id, it does not belongs to employer account.', true), 'error_msg');
                            echo "<script> window.close();window.opener.location.reload();</script>";
                            exit;
                        }
                    }

                    if ($userInfo['User']['status'] == '1') {
                        $this->User->id = $userInfo['User']['id'];
                        $this->request->data['User']['first_name'] = $user_info['name']['familyName'];
                        $this->request->data['User']['last_name'] = $user_info['name']['givenName'];

                        $this->User->save($this->data);
                        $this->Session->write("user_id", $userInfo['User']['id']);
                        $this->Session->write("user_name", $userInfo['User']['username']);
                        $this->Session->write("user_type", $userInfo['User']['user_type']);
                        $this->Session->write("email_address", $userInfo['User']['email_address']);

                        if ($this->Session->read("returnUrl")) {
                            $returnUrl = $this->Session->read("returnUrl");
                            $this->Session->delete("returnUrl");
                            echo "<script>window.close(); window.opener.location.href = '" . HTTP_PATH . '/' . $returnUrl . "';</script>";
                        } else if ($userType == 'jobseeker') {
                            echo "<script>window.close();window.opener.location.href = '" . HTTP_PATH . '/candidates/myaccount' . "';</script>";
                        } else {
                            echo "<script>window.close(); window.opener.location.href = '" . HTTP_PATH . '/users/myaccount' . "';</script>";
                        }
                        exit;
                    } else {
                        $this->Session->setFlash(__d('controller', 'Your account is deactivated by admin', true), 'error_msg');
                        echo "<script>window.close(); window.opener.location.reload();</script>";
                    }
                } else if ($email) {
                    $source = "Gmail";
                    $this->request->data['User']['email_address'] = $email;
                    $this->request->data['User']['first_name'] = $user_info['name']['familyName'];
                    $this->request->data['User']['last_name'] = $user_info['name']['givenName'];
                    if ($userType == 'jobseeker') {
                        $this->request->data['User']['user_type'] = 'candidate';
                    } else {
                        $this->request->data['User']['user_type'] = 'recruiter';
                    }
                    if ($profile_image_url) {
                        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                        srand((double) microtime() * 1000000);
                        $i = 1;
                        $imagename = '';
                        while ($i <= 10) {
                            $num = rand() % 33;
                            $tmp = substr($chars, $num, 1);
                            $imagename = $imagename . $tmp;
                            $i++;
                        }
                        $imagename = $imagename . '.jpg';
                        $fullpath = UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename;
                        $remote_img = $profile_image_url;
                        $img_file = file_get_contents($remote_img);
                        $file_handler = fopen($fullpath, 'w');
                        if (fwrite($file_handler, $img_file) == false) {
                            echo 'error';
                        }
                        fclose($file_handler);
                        $this->request->data['User']['profile_image'] = $imagename;
                        copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename);
                        copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename);
                        $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                        $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
                    }

                    // $this->request->data['User']['ip_address'] = $_SERVER['REMOTE_ADDR'];
                    // $this->request->data['User']['username'] = $this->stringToSlugUnique($user['given_name'], 'User', 'slug');
                    //$this->request->data['User']['slug'] = $this->data['User']['first_name'];
                    $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim(strtolower($this->data['User']['first_name'])), 'User', 'slug');
                    $this->request->data['User']['status'] = '1';
                    $this->request->data['User']['activation_status'] = '1';
                    srand((double) microtime() * 1000000);
                    $i = 1;
                    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                    $password = '';
                    while ($i <= 10) {
                        $num = rand() % 33;
                        $tmp = substr($chars, $num, 1);
                        $password = $password . $tmp;
                        $i++;
                    }
                    $passwordPlain = $password;
                    $salt = uniqid(mt_rand(), true);
                    $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
                    $this->request->data['User']['password'] = $new_password;
                    $this->request->data['User']['plain_password'] = $passwordPlain;
                    $this->request->data['User']['source'] = $source;
//                    $lat = $_SESSION['latitude'];
//                    $long = $_SESSION['longitude'];
//                    $this->request->data['User']['latitude'] = $lat;
//                    $this->request->data['User']['longitude'] = $long;
                    //$_SESSION['facebook_data'] = $this->data;
                    if ($this->User->save($this->data)) {
                        $userId = $this->User->id;
                        $email = $this->data["User"]["email_address"];
                        $username = $this->data["User"]["first_name"] . ' ' . $this->data["User"]["last_name"];

                        $this->Email->to = $email;
                        $currentYear = date('Y', time());

                        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='33'"));
                        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                        $toSubArray = array('[!username!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!SOURCE!]');
                        $fromSubArray = array($username, $userType, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $link, $site_url, $source);
                        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                        $this->Email->subject = $subjectToSend;

                        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                        $this->Email->from = $site_title . "<" . $mail_from . ">";

                        $toRepArray = array('[!username!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!SOURCE!]');
                        $fromRepArray = array($username, $userType, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $link, $site_url, $source);
                        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                        $this->Email->layout = 'default';
                        $this->set('messageToSend', $messageToSend);
                        $this->Email->template = 'email_template';
                        $this->Email->sendAs = 'html';
                        $this->Email->send();

                        $userCheck = $this->User->find('first', array('conditions' => array('User.id' => $userId)));

                        $this->Session->write("user_id", $userId);
                        //$this->Session->write("user_name", $this->data["User"]["username"]);
                        $this->Session->write("email_address", $this->data["User"]["email_address"]);

                        if ($userType == 'jobseeker') {
                            $this->Session->write("user_type", 'candidate');
                        } else {
                            $this->Session->write("user_type", 'recruiter');
                        }

                        if ($this->Session->read("returnUrl")) {
                            $returnUrl = $this->Session->read("returnUrl");
                            $this->Session->delete("returnUrl");
                            echo "<script>window.close(); window.opener.location.href = '" . HTTP_PATH . '/' . $returnUrl . "'; </script>";
                        } else if ($userType == 'jobseeker') {
                            $action = '/candidates/myaccount';
                            echo "<script> window.close(); window.opener.location.href = '" . HTTP_PATH . $action . "';</script>";
                        } else {
                            $action = '/users/myaccount';
                            echo "<script> window.close(); window.opener.location.href = '" . HTTP_PATH . $action . "';</script>";
                        }
                        exit;
                    }
                } else {
                    $this->Session->setFlash(__d('controller', 'Please try again', true), 'error_msg');
                    echo "<script>window.close(); window.opener.location.reload();</script>";
                    exit();
                }
            } catch (Exception $e) {
                // echo $e->getMessage();
                $this->Session->setFlash(__d('controller', 'Please try again', true), 'error_msg');
                echo "<script>window.close(); window.opener.location.reload();</script>";
                exit();
            }
        } else {
            $this->Session->setFlash(__d('controller', 'Please try again', true), 'error_msg');
            echo "<script>window.close(); window.opener.location.reload();</script>";
            exit();
        }
    }

    /*
     * Check login for facebook
     */

    public function chklogin($type = null) {
//pr($_SESSION); exit;
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');

        if ($type == 'jobseeker') {
            $typeUser = 'candidate';
        } else {
            $typeUser = 'recruiter';
        }




        App::import('Vendor', 'facebook');
        $facebook = new Facebook(array(
            'appId' => FACEBOOK_APP_ID,
            'secret' => FACEBOOK_SECRET,
            'cookie' => false
        ));

        $fb_session = $facebook->getAccessToken();
        $fb_uid = $facebook->getUser();

        if ($fb_uid) { // Checks if there is already a logged in user
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                //$fb_user = $facebook->api('/me');
                $fb_user = $facebook->api('/me?access_token=' . $fb_session . '&fields=id,name,email,first_name,last_name');
            } catch (FacebookApiException $e) {
                // Will throw the very first time as the token is not valid
                error_log($e);
                $fb_uid = null;
                // $permissions = 'email,publish_actions,user_birthday,user_photos,user_hometown,user_location,user_status';
                $permissions = 'email,publish_actions,user_birthday,user_hometown,user_location,user_status';

                $loginParams = array(
                    'canvas' => 1,
                    'fbconnect' => 0,
                    'req_perms' => $permissions,
                    'scope' => $permissions,
                    "next" => HTTP_PATH . "/users/chklogin/" . $type,
                    'redirect_uri' => HTTP_PATH . "/users/chklogin/" . $type
                );
                $loginUrl = $facebook->getLoginUrl($loginParams);
                echo "<script type='text/javascript'>top.location.href = '" . $loginUrl . "';</script>";
            }
        }
        if (!$fb_uid) { //Ask for bare minimum login
            $fb_uid = null;
            //$permissions = 'email,publish_actions,user_birthday,user_photos,user_hometown,user_location,user_status';
            $permissions = 'email,publish_actions,user_birthday,user_hometown,user_location,user_status';
            $loginParams = array(
                'canvas' => 1,
                'fbconnect' => 0,
                'req_perms' => $permissions,
                'scope' => $permissions,
                "next" => HTTP_PATH . "/users/chklogin/" . $type,
                'redirect_uri' => HTTP_PATH . "/users/chklogin/" . $type
            );

            $loginUrl = $facebook->getLoginUrl($loginParams);
            echo "<script type='text/javascript'>top.location.href = '" . $loginUrl . "';</script>";
        }

        $fbID = $fb_user['id'];
        $fb_first_name = $fb_user['first_name'];
        $fb_last_name = $fb_user['last_name'];
        $fb_email = $fb_user['email'];
        // $fb_link = $fb_user['link'];
        //$fb_username = $fb_user['username'];
        // $fb_dob = $fb_user['birthday'];
        //  $fb_sex = $fb_user['gender'];
        // if ($fb_user['location']['name'] != '') {
        //     $fb_loc = $fb_user['location']['name'];
        //  } else {
        //     $fb_loc = "";
        // }
        $fb_access_token = $fb_session; //EAADsmodqd1YBAFqWXqgvb4WHj2ZAqm2HvbK6tpTZAIlVlwtuttOdgR4pIW6AZBXp2u46sp3y7fpXfjESQP1WaPuGz1FzfW9rwOfgpQIgteiJxF76lKSTHVjbSaBdG10yQukdREhTJBnPF75hTVWmJ9uzrjCn8e5sezbKCZCGzQZDZD
        //echo"<pre>"; print_r($fb_email); exit;

        $userInfo = $this->User->find("first", array("conditions" => array("User.email_address" => $fb_email)));
        //echo"<pre>"; print_r($userInfo); exit;
        if ($userInfo) {
            if ($userInfo['User']['status'] == '1' && $userInfo['User']['activation_status'] == '1') {

                $this->User->id = $userInfo['User']['id'];

                //$this->request->data['User']['facebook_user_id'] = $fbID;
                $this->request->data['User']['first_name'] = $fb_first_name;
                $this->request->data['User']['last_name'] = $fb_last_name;
                // $this->request->data['User']['fb_link'] = $fb_link;
                // $this->request->data['User']['ip_address'] = $_SERVER['REMOTE_ADDR'];
                //$lat = $_SESSION['latitude'];
                //$long = $_SESSION['longitude'];
                //$this->request->data['User']['latitude'] = $lat;
                //$this->request->data['User']['longitude'] = $long;
                //$this->request->data['User']['address'] = $fb_loc;
                //$this->request->data['User']['facebook_user_link'] = $fb_link;
                //$this->request->data['User']['dob'] = date('Y-m-d', strtotime($fb_dob));
                //$this->request->data['User']['slug'] = $this->stringToSlug($fb_first_name) . mktime("s");
                //upload image
                /*
                  $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                  srand((double) microtime() * 1000000);
                  $i = 1;
                  $imagename = '';
                  while ($i <= 10) {
                  $num = rand() % 33;
                  $tmp = substr($chars, $num, 1);
                  $imagename = $imagename . $tmp;
                  $i++;
                  }

                  $imagename = $imagename . '.jpg';

                  $fullpath = UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename;
                  $remote_img = 'https://graph.facebook.com/' . $fbID . '/picture?type=large';

                  $img_file = file_get_contents($remote_img);
                  $file_handler = fopen($fullpath, 'w');
                  if (fwrite($file_handler, $img_file) == false) {
                  echo 'error';
                  }
                  fclose($file_handler);
                  $this->request->data['User']['profile_image'] = $imagename;
                  copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename);
                  copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename);
                  $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                  $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);

                 * //exit;
                 */

                $this->User->save($this->data);

                $this->Session->write("user_id", $userInfo['User']['id']);
                $this->Session->write("user_name", $userInfo['User']['first_name']);
                $this->Session->write("email_address", $fb_email);
                $this->Session->write("user_type", 'candidate');
                //$this->Session->write("email_address", $fb_user_type);
                //$this->Session->write("facebook_id", $userInfo['User']['facebook_user_id']);
                //$this->Session->write("fb_id", $fbID);
                /*  echo "<script>
                  window.close();
                  window.opener.location.reload();
                  </script>"; */


                echo "<script>
                window.close();
                window.opener.location.href = '" . HTTP_PATH . "/candidates/myaccount';
                </script>";

                //                if ($this->Session->read("returnsubUrl") != '') {
                //                    $this->redirect('/' . $this->Session->read("returnsubUrl"));
                //                } else {
                //                    $this->redirect('/users/myaccount');
                //                }
            } else {
                //$this->Session->setFlash('Your account might have been temporarily disabled. Please contact us for more details.<br>', 'error_msg');
                $this->Session->write("success_msg", __d('controller', 'Your account might have been temporarily disabled. Please contact us for more details.', true) . '<br>');
                echo "<script>
                window.close();
                window.opener.location.reload();
                </script>";
            }
        } else {

            $this->request->data['User']['email_address'] = $fb_email;
            $this->request->data['User']['first_name'] = $fb_first_name;
            $this->request->data['User']['last_name'] = $fb_last_name;
            $this->request->data['User']['user_type'] = $typeUser;
            //$this->request->data['User']['facebook_user_id'] = $fbID;
            //$this->request->data['User']['facebook_user_link'] = $fb_link;
            // $this->request->data['User']['dob'] = date('Y-m-d', strtotime($fb_dob));
            // $this->request->data['User']['fb_link'] = $fb_link;
            //$this->request->data['User']['gender'] = $gender;
            //$this->request->data['User']['address'] = $fb_loc;
            // $this->request->data['User']['username'] = $fb_first_name . time();
            //$this->request->data['User']['ip_address'] = $_SERVER['REMOTE_ADDR'];
//            $lat = $_SESSION['latitude'];
//            $long = $_SESSION['longitude'];
//            $this->request->data['User']['latitude'] = $lat;
//            $this->request->data['User']['longitude'] = $long;



            /* $chars = "abcdefghijkmnopqrstuvwxyz023456789";
              srand((double) microtime() * 1000000);
              $i = 1;
              $imagename = '';
              while ($i <= 10) {
              $num = rand() % 33;
              $tmp = substr($chars, $num, 1);
              $imagename = $imagename . $tmp;
              $i++;
              }

              $imagename = $imagename . '.jpg';

              $fullpath = UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename;
              $remote_img = 'https://graph.facebook.com/' . $fbID . '/picture?type=large';

              $img_file = file_get_contents($remote_img);
              $file_handler = fopen($fullpath, 'w');
              if (fwrite($file_handler, $img_file) == false) {
              echo 'error';
              }
              fclose($file_handler);
              copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename);
              copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename);
              $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
              $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
              //exit;
              $this->request->data['User']['profile_image'] = $imagename;
             */

            $this->request->data['User']['profile_image'] = '';
            $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim(strtolower($fb_first_name)), 'User', 'slug');
            $this->request->data['User']['status'] = '1';
            $this->request->data['User']['activation_status'] = '1';

            srand((double) microtime() * 1000000);
            $i = 1;
            $chars = "abcdefghijkmnopqrstuvwxyz023456789";
            $password = '';
            while ($i <= 10) {
                $num = rand() % 33;
                $tmp = substr($chars, $num, 1);
                $password = $password . $tmp;
                $i++;
            }
            $passwordPlain = $password;
            $salt = uniqid(mt_rand(), true);
            $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
            $this->request->data['User']['password'] = $new_password;

//            $permissions = $facebook->api('/me/permissions');
//            if (isset($permissions['data'][0]['publish_stream']) && $permissions['data'][0]['publish_stream']) {
//
//                $messageforfb = $fb_first_name . " has just sign up on " . SITE_TITLE;
//
//                $fblink = HTTP_PATH;
//                $publishStream = $facebook->api("/me/feed", 'post', array(
//                    'message' => $messageforfb,
//                    'link' => $fblink,
//                    'picture' => HTTP_IMAGE . '/front/facebook_logo.png',
//                    'name' => 'Upliftjobs'
//                        )
//                );
//            }
            //$_SESSION['facebook_data'] = $this->data;
            if ($this->User->save($this->data)) {

                $userId = $this->User->id;
                $email = $this->data["User"]["email_address"];
                $username = $this->data["User"]["first_name"] . ' ' . $this->data["User"]["last_name"];
                $source = "Facebook";

                $this->Email->to = $email;
                $currentYear = date('Y', time());

                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='33'"));
                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                $emData = $this->Emailtemplate->getSubjectLang();
                $subjectField = $emData['subject'];
                $templateField = $emData['template'];

                $toSubArray = array('[!username!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!SOURCE!]');
                $fromSubArray = array($username, $type, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $source);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                $this->Email->subject = $subjectToSend;

                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";

                $toRepArray = array('[!username!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!SOURCE!]');
                $fromRepArray = array($username, $type, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $source);
                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                $this->Email->layout = 'default';
                $this->set('messageToSend', $messageToSend);
                $this->Email->template = 'email_template';
                $this->Email->sendAs = 'html';
                $this->Email->send();

                /*   $this->Session->write("user_id", $userId);
                  $this->Session->write("user_name", $this->data["User"]["first_name"]);
                  $this->Session->write("email_address", $fb_email);
                  $this->Session->write("facebook_id", $fbID);
                  $this->Session->write("fb_id", $fbID); */
                //$this->redirect('/users/myaccount');
                //$this->Session->setFlash('You are successfully signup by facebook. For login credentials Please check your email account.<br>', 'success_msg');
                $this->Session->write("success_msg", __d('controller', 'You are successfully signup by facebook. For login credentials Please check your email account.', true) . '<br>');
                echo "<script>
                window.close();
                window.opener.location.href = '" . HTTP_PATH . "/users/login';
                </script>";
            }
//            echo "<script>
//                window.close();
//                window.opener.location.href = '" . HTTP_PATH . "/users/facebookLogin/Facebook" . "';
//                </script>";
        }
    }

    /*
     * Jobseeker and employer registeration
     */

    public function register($type) {

        Configure::write('debug', 2);
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . "Sign Up");
        $this->layout = "client";
        $authUrl = '';
        // $this->set('registerA', 'active');
        if ($type == 'jobseeker') {
            $this->set('registerA', 'active');
        } else {
            $this->set('registerB', 'active');
        }

        $this->set('checkpage', 'register');

        $this->userLoggedinCheck();
        $this->gmailSession($type);
        $msgString = '';
        global $extentions;

//echo $type; exit;
        $this->set('userType', $type);

        $this->Session->write("type", $type);
        $categories = $this->Category->getCategoryList();
        $this->set('categories', $categories);
//pr($_SESSION);
        //get parameter from url
        $para = $this->params['pass'];

        /* --facebook user detail register code starts-- */
        if (isset($_COOKIE['fbsr_' . FACEBOOK_APP_ID])) {
            setcookie('fbsr_' . FACEBOOK_APP_ID, $_COOKIE['fbsr_' . FACEBOOK_APP_ID], time() - 3600, "/");
            setcookie('PHPSESSID', $_COOKIE['PHPSESSID'], time() - 3600, "/");

            unset($_COOKIE['fbsr_' . FACEBOOK_APP_ID]);
            unset($_COOKIE['PHPSESSID']);
        }
        App::import('Vendor', 'facebook');
        $facebook = new Facebook(array(
            'appId' => FACEBOOK_APP_ID,
            'secret' => FACEBOOK_SECRET,
            'cookie' => false
        ));

        //Ask for bare minimum login
        $fb_uid = null;
        //$permissions = 'email,publish_actions,user_birthday,user_photos,user_hometown,user_location,user_status';
        $permissions = 'email,publish_actions,user_birthday,user_hometown,user_location,user_status';
        $loginParams = array(
            'canvas' => 1,
            'fbconnect' => 0,
            'display' => 'popup',
            'req_perms' => $permissions,
            'scope' => $permissions,
            "next" => HTTP_PATH . "/users/chklogin/" . $para[0],
            'redirect_uri' => HTTP_PATH . "/users/chklogin/" . $para[0],
            'cancel_url' => HTTP_PATH . "/users/login"
        );
        $loginUrl = $facebook->getLoginUrl($loginParams);
        $this->set("loginUrl", $loginUrl);

        if (!isset($para[0]) && empty($para[0])) {
            $this->redirect("/index");
        } else if (($para[0] == 'jobseeker') || ($para[0] == 'employer')) {

            $this->set('para', $para);

            if ($this->data) {

                if ($this->data['User']['user_type'] == 'recruiter') {
                    if (trim($this->data["User"]["company_name"]) == '') {
                        $msgString .= "- Company name is required field.<br>";
                    } else {
                        $msgString .= $this->Swear->checkSwearWord($this->data["User"]["company_name"]);
                    }
                }

                if (empty($this->data["User"]["first_name"])) {
                    $msgString .= __d('controller', 'First Name is required field.', true) . " <br>";
                } else {
                    $msgString .= $this->Swear->checkSwearWord($this->data["User"]["first_name"]);
                }

                if (empty($this->data["User"]["last_name"])) {
                    $msgString .= __d('controller', 'Last Name is required field.', true) . "<br>";
                } else {
                    $msgString .= $this->Swear->checkSwearWord($this->data["User"]["first_name"]);
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

                // $captcha = $this->Captcha->getVerCode();
                // if ($this->data['User']['captcha'] == "") {
                //     $msgString .= __d('controller', 'Please enter security code.', true) . "<br>";
                // } elseif ($this->data['User']['captcha'] != $captcha) {
                //   //  $msgString .= __d('controller', 'Please enter correct security code.', true) . "<br>";
                // }

                if(isset( $this->request->data['g-recaptcha-response'])){
                    $captcha= $this->request->data['g-recaptcha-response'];
    
                }
        
                    if(!$captcha){
            
                        $msgString .= __d('controller', 'Please check the captcha.', true) . "<br>";
            
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
                    $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim(strtolower($this->data['User']['first_name'])) . ' ' . trim(strtolower($this->data['User']['last_name'])), 'User', 'slug');
                    $this->request->data['User']['country_id'] = 1;
                    $this->request->data['User']['activation_status'] = 0;
                    $this->request->data['User']['status'] = 0;
                    $this->request->data['User']['interest_categories'] = isset($this->data['User']['interest_categories']) ? implode(',', $this->data['User']['interest_categories']) : '';

//                    echo '<pre>';print_r($this->data);die;
                    if ($this->User->save($this->data)) {
                        $userId = $this->User->id;
                        //last inserted user info
                        $userDetail = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
                        $created = date('F d, Y', strtotime($userDetail['User']['created']));
                        //registeration mail to admin
                        $adminDetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => 1)));
                        $email = isset($email) ? $email : $this->data["User"]["email_address"];

                        $email = $this->data["User"]["email_address"];
                        $username = $this->data["User"]["first_name"];
                        $link = HTTP_PATH . "/users/confirmation/" . $userId . "/" . md5($userId) . "/" . urlencode($email);
                        // $confirm = '<a href="' . $link . '">Click Here</a>';
                        if ($this->data['User']['user_type'] == 'recruiter') {

                            $this->Email->to = $email;

                            $emData = $this->Emailtemplate->getSubjectLang();
                            $subjectField = $emData['subject'];
                            $templateField = $emData['template'];

                            // $this->Email->subject = "Your " . SITE_TITLE . " Account has been created";
                            $currentYear = date('Y', time());

                            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='19'"));
                            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                            //$this->Email->subject = $emailtemplateMessage['Emailtemplate']['subject'];
                            $toSubArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                            $fromSubArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                            $this->Email->subject = $subjectToSend;

                            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                            $this->Email->from = $site_title . "<" . $mail_from . ">";

                            $toRepArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                            $fromRepArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);

                            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);

                            $this->Email->layout = 'default';
                            $this->set('messageToSend', $messageToSend);
                            $this->Email->template = 'email_template';
                            $this->Email->sendAs = 'html';
                            $this->Email->send();
                            $this->Email->reset();

//  echo '<pre>';
//                            print_r($this->Email->send());
//                            die;
                            //registeration mail to admin
                            if (!empty($adminDetail)) {
                                $this->Email->to = $adminDetail['Admin']['email'];
                                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='37'"));
                                //$this->Email->subject = $emailtemplateMessage['Emailtemplate']['subject'];

                                $emData = $this->Emailtemplate->getSubjectLang();
                                $subjectField = $emData['subject'];
                                $templateField = $emData['template'];

                                $toSubArray = array('[!username!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                                $fromSubArray = array('Admin', $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url);
                                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                                $this->Email->subject = $subjectToSend;

                                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                                $this->Email->from = $site_title . "<" . $mail_from . ">";
                                $currentYear = date('Y', time());

                                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . MAIL_FROM . '">' . MAIL_FROM . '</a>';
                                $toRepArray = array('[!company_name!]', '[!email!]', '[!created!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                                $fromRepArray = array($this->data["User"]["company_name"], $email, $userDetail['User']['created'], $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url);
                                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                                $this->Email->layout = 'default';
                                $this->set('messageToSend', $messageToSend);
                                $this->Email->template = 'email_template';
                                $this->Email->sendAs = 'html';
                                $this->Email->send();
                                $this->Email->reset();
                            }
                        } else {
                            if (!empty($this->Session->read('guest_user_cv'))) {
                                // print_r('asdasd');exit;
                                if (file_exists(UPLOAD_TMP_CERTIFICATE_PATH . $this->Session->read('guest_user_cv'))) {
                                    copy(UPLOAD_TMP_CERTIFICATE_PATH . $this->Session->read('guest_user_cv'), UPLOAD_CERTIFICATE_PATH . $this->Session->read('guest_user_cv'));
                                }
                                $this->request->data['Certificate']['user_id'] = $userId;
                                $this->request->data['Certificate']['document'] = $this->Session->read('guest_user_cv');
                                $this->request->data['Certificate']['type'] = 'doc';
                                $this->request->data['Certificate']['slug'] = 'doc-' . $userId . time() . rand(111, 99999);
                                $this->request->data['Certificate']['created'] = date('Y-m-d', time());
                                @unlink(UPLOAD_TMP_CERTIFICATE_PATH . $this->Session->read('guest_user_cv'));
                                $this->Session->delete('guest_user_cv');
                                $this->Certificate->save($this->request->data['Certificate']);
                            }

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
                            $this->Email->reset();

                            //$resend_array = array('username' => $username, 'email' => $email, 'password' => $passwordPlain, 'link' => $link, 'userType' => 'checked');
                            //send mail to admin


                            if (!empty($adminDetail)) {
                                $this->Email->to = $adminDetail['Admin']['email'];
                                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='36'"));
                                //$this->Email->subject = $emailtemplateMessage['Emailtemplate']['subject'];

                                $toSubArray = array('[!username!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                                $fromSubArray = array($username, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url);
                                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                                $this->Email->subject = $subjectToSend;

                                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                                $this->Email->from = $site_title . "<" . $mail_from . ">";
                                $currentYear = date('Y', time());

                                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . MAIL_FROM . '">' . MAIL_FROM . '</a>';
                                $toRepArray = array('[!username!]', '[!email!]', '[!created!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                                $fromRepArray = array($username, $email, $userDetail['User']['created'], $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url);
                                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                                $this->Email->layout = 'default';
                                $this->set('messageToSend', $messageToSend);
                                $this->Email->template = 'email_template';
                                $this->Email->sendAs = 'html';
                                $this->Email->send();
                                $this->Email->reset();
                            }
                        }

//                        $userCheck = $this->User->findById($userId);
//                        echo '<pre>';print_r($userCheck);die;
                        $this->Session->write("user_id", $userDetail['User']['id']);
                        $this->Session->write("user_type", $userDetail['User']['user_type']);
                        $this->Session->write("email_address", $userDetail['User']['email_address']);
                        $this->Session->write("user_name", $userDetail['User']['first_name']);
                        $this->Session->delete('Userloginstatus');

                        $this->Session->write('success_msg', __d('controller', 'Your account is successfully created. Please check your email for activation link. Thank you!', true));

                        if ($userDetail['User']['user_type'] == 'recruiter') {
                            //$this->redirect("/users/editProfile");
                            $this->redirect("/users/employerlogin");
                        } else {
                            //$this->redirect("/candidates/editProfile");
                            $this->redirect("/users/login");
                        }
                    }
                }
            }
        } else {
            $this->redirect("home/error");
        }
    }

    function confirmation($id = null, $md5id = null, $email = null) {
        if (md5($id) == $md5id) {
            $userCheck = $this->User->find('first', array('conditions' => array('User.email_address' => $email, 'User.id' => $id)));
            $type = $userCheck['User']['user_type'];

            if (!$this->Session->read('user_id') && $userCheck['User']['activation_status'] == 0 && !empty($userCheck)) {
                $cnd = array("User.id" => $id);
                $this->User->updateAll(array('User.status' => "'1'", 'User.activation_status' => "'1'"), $cnd);

                $this->Session->write('success_msg', __d('controller', 'Your account has been activated successfully.', true));
                if ($type && ($type == 'recruiter' || $type == 'employer')) {
                    $this->redirect('/users/employerlogin');
                } else {
                    $this->redirect('/users/login');
                }
            } else {
                if ($this->Session->read('user_id')) {
                    $this->Session->write('error_msg', __d('controller', 'You are already login with other account. Please logout first to activate your another account!', true));
                } else {
                    $this->Session->write('error_msg', __d('controller', 'You have already used this activation link!', true));
                }
                if ($type && ($type == 'recruiter' || $type == 'employer')) {
                    $this->redirect('/users/employerlogin');
                } else {
                    $this->redirect('/users/login');
                }
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

    function forgot_popup() {

        $this->layout = "";
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Forgot Password');

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
                    $result = array('msg' => __d('controller', 'A link to reset your password has been sent to your email address', true), 'classh' => 'success_msg success_lo');
                    echo json_encode($result);
                    exit;
                } else {

                    $result = array('msg' => __d('controller', 'Your email is not registered with', true) . ' ' . $site_title . '. ' . __d('controller', 'Please enter correct email or register on', true) . ' ' . $site_title, 'classh' => 'error_msg error_lo');
                    echo json_encode($result);
                    $this->request->data['User']['email_address'] = "";
                    exit;
                }
            } else {

                $result = array('msg' => $msgstring, 'classh' => 'error_msg error_lo');
                echo json_encode($result);
                exit;
            }
        }
        exit;
    }

    /* public function login_popup() {
      $this->layout = "";
      $mail_from = $this->getMailConstant('from');
      $site_title = $this->getSiteConstant('title');
      $tagline = $this->getSiteConstant('tagline');

      $msgString = "";
      //$this->set("returnurl", "");
      $this->userLoggedinCheck();
      $time = time();
      $time1 = date('Y-m-d H:i:s', $time);

      if (isset($this->data) && !empty($this->data)) {
      //            echo "<pre>";
      //            print_r($this->data);
      //            exit;
      if (empty($this->data['User']['email_address'])) {
      $msgString .= "- Please enter your email address.<br>";
      } elseif ($this->User->isRecordUniqueemail($this->data["User"]["email_address"]) == true) {

      $msgString .="- Your email is not registered with " . $site_title . ". Please enter correct email or register on " . $site_title;
      }


      if (empty($this->data['User']['password'])) {
      $msgString .= "- Please enter your password.<br>";
      }

      $captcha = $this->Captcha->getVerCode();

      if ($this->Session->read('Userloginstatus') > 1) {
      if ($this->data['User']['captcha'] == "") {
      $msgString .= "- Please enter security code.<br>";
      } elseif ($this->data['User']['captcha'] != $captcha) {
      $msgString .= "- Please enter correct security code.<br>";
      }
      }

      if (isset($msgString) && $msgString != '') {

      $this->Session->write('error_msg', $msgString);
      exit;
      } else {

      $email = $this->data['User']['email_address'];
      $password = $this->data['User']['password'];

      $userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email, "User.modified <=" => $time1)));
      //echo "<pre>"; print_r($userCheck); exit;
      if (is_array($userCheck) && !empty($userCheck) && crypt($password, $userCheck['User']['password']) == $userCheck['User']['password']) {

      if ($userCheck['User']['status'] == 1 && $userCheck['User']['activation_status'] == 1) {

      if (isset($this->data['User']['rememberme']) && $this->data['User']['rememberme'] == '1') {
      setcookie("cookname", $this->data['User']['email_address'], time() + 60 * 60 * 24 * 7, "/");
      setcookie("cookpass", $this->data['User']['password'], time() + 60 * 60 * 24 * 7, "/");
      } else {
      setcookie("cookname", '', time() + 60 * 60 * 24 * 7, "/");
      setcookie("cookpass", '', time() + 60 * 60 * 24 * 7, "/");
      }

      $this->Session->delete('resend_link');
      $this->Session->delete('resend_array');

      $this->Session->write("user_id", $userCheck['User']['id']);
      $this->Session->write("user_type", $userCheck['User']['user_type']);
      $this->Session->write("email_address", $userCheck['User']['email_address']);
      $this->Session->write("user_name", $userCheck['User']['first_name']);
      $this->Session->delete('Userloginstatus');

      if ($this->Session->read("returnUrl")) {

      $returnUrl = $this->Session->read("returnUrl");
      $this->Session->delete("returnUrl");
      $this->redirect('/' . $returnUrl);
      } else {

      if ($userCheck['User']['user_type'] == 'recruiter') {

      //$this->redirect("/users/myaccount");

      echo 'employer';
      exit;
      } else {

      //$this->redirect("/candidates/myaccount");
      echo 'jobseeker';
      exit;
      //header('Location: '. HTTP_PATH . '/candidates/myaccount');
      }
      }
      } else {

      if ($userCheck['User']['activation_status'] == 0) {
      $msgString .= "Please check you mail for activation link to activate your account.<br>";
      } else {
      $msgString .= "Your account might have been temporarily disabled. Please contact us for more details.<br>";
      }

      $this->Session->delete('Userloginstatus');
      $this->Session->write('error_msg', $msgString);
      $this->request->data['User']['captcha'] = '';
      exit;
      }
      } else {


      $this->Session->delete('user_id');
      $i = $this->Session->read('Userloginstatus');
      if ($i < 6) {
      $i = 1 + $i;
      $this->Session->write('Userloginstatus', $i);
      }
      if ($i == 1) {
      $this->Session->write('error_msg', 'Invalid email and/or password. you have five more attempts.');
      }
      if ($i == 2) {
      $this->Session->write('error_msg', 'Invalid email and/or password. you have four more attempts.');
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
      $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='11'"));

      $toSubArray = array('[!username!]', '[!SITE_TITLE!]');
      $fromSubArray = array($username, $site_title);
      $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
      $this->Email->subject = $subjectToSend;

      $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
      $this->Email->from = $site_title . "<" . $mail_from . ">";

      $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';
      $toRepArray = array('[!username!]', '[!SITE_TITLE!]');
      $fromRepArray = array($username, $site_title);
      $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
      $this->Email->layout = 'default';
      $this->set('messageToSend', $messageToSend);
      $this->Email->template = 'email_template';
      $this->Email->sendAs = 'html';
      $this->Email->send();
      }

      $this->Session->write('error_msg', 'Invalid username and/or password. you have three more attempts.');
      }
      if ($i == 4) {
      $this->Session->write('error_msg', 'Invalid username and/or password. you have two more attempts.');
      }
      if ($i == 5) {
      $this->Session->write('error_msg', 'Invalid username and/or password. you have one more attempts.');
      }
      if ($i == 6) {
      $userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email)));
      if ($userCheck['User']['modified'] <= date('Y-m-d H:i:s', time())) {
      $userCheck['User']['id'] = $userCheck['User']['id'];
      $userCheck['User']['modified'] = date('Y-m-d H:i:s', time() + 30 * 60);
      $this->User->save($userCheck);
      }
      $this->Session->write('error_msg', 'Invalid email and/or password.');
      }
      $this->request->data['User']['captcha'] = '';
      }
      exit;
      }
      } else {
      if (isset($_COOKIE["cookname"]) && isset($_COOKIE["cookpass"])) {
      $this->request->data['User']['email_address'] = $_COOKIE["cookname"];
      $this->request->data['User']['password'] = $_COOKIE["cookpass"];
      }
      }

      if ($this->request->is('ajax')) {
      $this->layout = '';
      $this->viewPath = 'Elements';
      $this->render('login');
      }
      } */

    function login_popup() {


        $this->layout = "";

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');

        $msgString = "";
        //$this->set("returnurl", "");
        $this->userLoggedinCheck();
        $time = time();
        $time1 = date('Y-m-d H:i:s', $time);

        if (isset($this->data) && !empty($this->data)) {

            if (empty($this->data['User']['email_address'])) {
                $msgString .= "- Please enter your email address.<br>";
            } elseif ($this->User->isRecordUniqueemail($this->data["User"]["email_address"]) == true) {

                $msgString .= "- Your email is not registered with " . $site_title . ". Please enter correct email or register on " . $site_title;
            }


            if (empty($this->data['User']['password'])) {
                $msgString .= "- Please enter your password.<br>";
            }

//            $captcha = $this->Captcha->getVerCode();
//
//            if ($this->Session->read('Userloginstatus') > 1) {
//                if ($this->data['User']['captcha'] == "") {
//                    $msgString .= "- Please enter security code.<br>";
//                } elseif ($this->data['User']['captcha'] != $captcha) {
//                    $msgString .= "- Please enter correct security code.<br>";
//                }
//            }

            if (isset($msgString) && $msgString != '') {
                //$this->request->data['User']['captcha'] = '';
                echo $msgString;
                exit;
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

                        $this->Session->delete('resend_link');
                        $this->Session->delete('resend_array');

                        $this->Session->write("user_id", $userCheck['User']['id']);
                        $this->Session->write("user_type", $userCheck['User']['user_type']);
                        $this->Session->write("email_address", $userCheck['User']['email_address']);
                        $this->Session->write("user_name", $userCheck['User']['first_name']);
                        // $this->Session->delete('Userloginstatus');

                        if ($this->Session->read("returnUrl")) {

                            $returnUrl = $this->Session->read("returnUrl");
                            $this->Session->delete("returnUrl");
                            $this->redirect('/' . $returnUrl);
                        } else {

                            if ($userCheck['User']['user_type'] == 'recruiter') {

                                echo 'employer';
                                exit;
                            } elseif ($userCheck['User']['user_type'] == 'candidate') {

                                //$this->redirect("/candidates/myaccount");
                                echo 'jobseeker';
                                exit;
                                //header('Location: '. HTTP_PATH . '/candidates/myaccount');
                            } else {
                                echo 'Some error found please try again';
                                exit;
                            }
                        }
                    } else {

                        if ($userCheck['User']['activation_status'] == 0) {
                            $msgString .= __d('controller', 'Please check your mail for activation link to activate your account.', true) . "<br>";
                        } else {
                            $msgString .= __d('controller', 'Your account might have been temporarily disabled. Please contact us for more details.', true) . "<br>";
                        }
                        //$this->Session->delete('Userloginstatus');
                        //$this->Session->write('error_msg', $msgString);
                        echo $msgString;
                        // $this->request->data['User']['captcha'] = '';

                        exit;
                    }
                } else {
                    echo __d('controller', 'Email or password is incorrect, please try again.', true) . '<br>';
                    exit;
                }
                /* else {


                  $this->Session->delete('user_id');
                  $i = $this->Session->read('Userloginstatus');

                  if ($i < 6) {
                  $i = 1 + $i;
                  $this->Session->write('Userloginstatus', $i);
                  }
                  if ($i == 1) {
                  //$this->Session->write('error_msg', 'Invalid email and/or password. you have five more attempts.');
                  echo 'Invalid email and/or password. you have five more attempts.';

                  }
                  if ($i == 2) {
                  //$this->Session->write('error_msg', 'Invalid email and/or password. you have four more attempts.');
                  echo 'Invalid email and/or password. you have four more attempts.';

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
                  $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='11'"));

                  $toSubArray = array('[!username!]', '[!SITE_TITLE!]');
                  $fromSubArray = array($username, $site_title);
                  $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                  $this->Email->subject = $subjectToSend;

                  $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                  $this->Email->from = $site_title . "<" . $mail_from . ">";

                  $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';
                  $toRepArray = array('[!username!]', '[!SITE_TITLE!]');
                  $fromRepArray = array($username, $site_title);
                  $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                  $this->Email->layout = 'default';
                  $this->set('messageToSend', $messageToSend);
                  $this->Email->template = 'email_template';
                  $this->Email->sendAs = 'html';
                  $this->Email->send();
                  }

                  // $this->Session->write('error_msg', 'Invalid username and/or password. you have three more attempts.');
                  echo 'Invalid username and/or password. you have three more attempts.';

                  }
                  if ($i == 4) {
                  //$this->Session->write('error_msg', 'Invalid username and/or password. you have two more attempts.');
                  echo 'Invalid username and/or password. you have two more attempts.';

                  }
                  if ($i == 5) {
                  // $this->Session->write('error_msg', 'Invalid username and/or password. you have one more attempts.');
                  echo 'Invalid username and/or password. you have one more attempts.';

                  }
                  if ($i == 6) {
                  $userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email)));
                  if ($userCheck['User']['modified'] <= date('Y-m-d H:i:s', time())) {
                  $userCheck['User']['id'] = $userCheck['User']['id'];
                  $userCheck['User']['modified'] = date('Y-m-d H:i:s', time() + 30 * 60);
                  $this->User->save($userCheck);
                  }
                  // $this->Session->write('error_msg', 'Invalid email and/or password.');
                  echo 'Invalid email and/or password.';

                  }
                  $this->request->data['User']['captcha'] = '';
                  } */
            }
        } else {
            if (isset($_COOKIE["cookname"]) && isset($_COOKIE["cookpass"])) {
                $this->request->data['User']['email_address'] = $_COOKIE["cookname"];
                $this->request->data['User']['password'] = $_COOKIE["cookpass"];
            }
        }
        exit;
//        if ($this->request->is('ajax')) {
//            $this->layout = '';
//            $this->viewPath = 'Elements';
//            $this->render('login');
//        }
    }

    /*
     * Jobseeker login
     */

    function login() {

        $this->layout = "client";

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";

        $this->set('title_for_layout', $title_for_pages . 'Login');
        $this->set('loginA', 'active');
        $this->set('checkpage', 'login');
        $msgString = "";
        $this->set("returnurl", "");
        $this->userLoggedinCheck();

        $time = time();
        $time1 = date('Y-m-d H:i:s', $time);
        $authUrl = '';
        $type = 'jobseeker';
        $this->set('userType', $type);
        $this->gmailSession($type);

        $this->Session->write("type", $type);

        if (isset($this->data) && !empty($this->data)) {
//echo "<pre>"; print_r($this->data); exit;

            if (empty($this->data['User']['email_address'])) {
                $msgString .= "- Please enter your email address.<br>";
            } elseif ($this->User->isRecordUniqueemail($this->data["User"]["email_address"]) == true) {

                $msgString .= __d('controller', 'Your email is not registered with', true) . " " . $site_title . '. ' . __d('controller', 'Please enter correct email or register on', true) . " " . $site_title;
            }

            if (empty($this->data['User']['password'])) {
                $msgString .= __d('controller', 'Please enter your password.', true) . "<br>";
            }

            $captcha = $this->Captcha->getVerCode();

            if ($this->Session->read('Userloginstatus') > 1) {
                if ($this->data['User']['captcha'] == "") {
                  //  $msgString .= __d('controller', 'Please enter security code.', true) . "<br>";
                } elseif ($this->data['User']['captcha'] != $captcha) {
                  //  $msgString .= __d('controller', 'Please enter correct security code.', true) . "<br>";
                    $this->request->data['User']['captcha'] = '';
                }
            }

            if (isset($msgString) && $msgString != '') {
                //echo 'error';
                $this->Session->write('error_msg', $msgString);
                //exit;
            } else {

                $email = trim($this->data['User']['email_address']);
                $password = $this->data['User']['password'];

                //$userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email, "User.modified <=" => $time1)));
                $userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email, "User.modified <=" => $time1, "User.user_type" => 'candidate')));

          
                
                
                
               // if (is_array($userCheck) && !empty($userCheck) && crypt($password, $userCheck['User']['password']) == $userCheck['User']['password']) {
                    if (is_array($userCheck) && !empty($userCheck) && crypt($password, $userCheck['User']['password']) == $userCheck['User']['password']) {
                           
             
                    
                    if ($userCheck['User']['status'] == 1 && $userCheck['User']['activation_status'] == 1) {
                        if (isset($this->data['User']['rememberme']) && $this->data['User']['rememberme'] == '1') {
                            setcookie("cookname", $this->data['User']['email_address'], time() + 60 * 60 * 24 * 7, "/");
                            setcookie("cookpass", $this->data['User']['password'], time() + 60 * 60 * 24 * 7, "/");
                        } else {
                            setcookie("cookname", '', time() + 60 * 60 * 24 * 7, "/");
                            setcookie("cookpass", '', time() + 60 * 60 * 24 * 7, "/");
                        }

                        $this->Session->delete('resend_link');
                        $this->Session->delete('resend_array');

                        $this->Session->write("user_id", $userCheck['User']['id']);
                        $this->Session->write("user_type", $userCheck['User']['user_type']);
                        $this->Session->write("email_address", $userCheck['User']['email_address']);
                        $this->Session->write("user_name", $userCheck['User']['first_name']);
                        $this->Session->delete('Userloginstatus');

                        if ($this->Session->read("returnUrl")) {

                            $returnUrl = $this->Session->read("returnUrl");
                            $this->Session->delete("returnUrl");
                            $this->redirect('/' . $returnUrl);
                        } else {

                            if ($userCheck['User']['user_type'] == 'recruiter') {
                                $this->redirect("/users/myaccount");
                            } else {
                                $this->redirect("/candidates/myaccount");
                            }
                        }
                    } else {

                        if ($userCheck['User']['activation_status'] == 0) {
                            $msgString .= __d('controller', 'Please check your mail for activation link to activate your account.', true) . "<br>";
                        } else {
                            $msgString .= __d('controller', 'Your account might have been temporarily disabled. Please contact us for more details.', true) . "<br>";
                        }
                        $this->Session->delete('Userloginstatus');
                        $this->Session->write('error_msg', $msgString);
                        $this->request->data['User']['captcha'] = '';
                    }
                } else {
                  // echo 'else';exit;

                    
                    $this->Session->delete('user_id');
                    $i = $this->Session->read('Userloginstatus');
                    if ($i < 6) {
                        $i = 1 + $i;
                        $this->Session->write('Userloginstatus', $i);
                    }
                    if ($i == 1) {
                        //$this->Session->write('error_msg', 'Invalid email and/or password . you have five more attempts.');
                        $this->Session->write('error_msg', __d('controller', 'Invalid email/password or you have not registered as a jobseeker. you have five more attempts.', true));
                    }
                    if ($i == 2) {
                        $this->Session->write('error_msg', __d('controller', 'Invalid email/password or you have not registered as a jobseeker. you have four more attempts.', true));
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
                            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='11'"));

                            $emData = $this->Emailtemplate->getSubjectLang();
                            $subjectField = $emData['subject'];
                            $templateField = $emData['template'];

                            $toSubArray = array('[!username!]', '[!SITE_TITLE!]');
                            $fromSubArray = array($username, $site_title);
                            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                            $this->Email->subject = $subjectToSend;

                            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                            $this->Email->from = $site_title . "<" . $mail_from . ">";

                            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';
                            $toRepArray = array('[!username!]', '[!SITE_TITLE!]');
                            $fromRepArray = array($username, $site_title);
                            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                            $this->Email->layout = 'default';
                            $this->set('messageToSend', $messageToSend);
                            $this->Email->template = 'email_template';
                            $this->Email->sendAs = 'html';
                            $this->Email->send();
                        }

                        $this->Session->write('error_msg', __d('controller', 'Invalid email/password or you have not registered as a jobseeker. you have three more attempts.', true));
                    }
                    if ($i == 4) {
                        $this->Session->write('error_msg', __d('controller', 'Invalid email/password or you have not registered as a jobseeker. you have two more attempts.', true));
                    }
                    if ($i == 5) {
                        $this->Session->write('error_msg', __d('controller', 'Invalid email/password or you have not registered as a jobseeker. you have one more attempts.', true));
                    }
                    if ($i == 6) {
                        $userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email)));
                        if ($userCheck['User']['modified'] <= date('Y-m-d H:i:s', time())) {
                            $userCheck['User']['id'] = $userCheck['User']['id'];
                            $userCheck['User']['modified'] = date('Y-m-d H:i:s', time() + 30 * 60);
                            $this->User->save($userCheck);
                        }
                        $this->Session->write('error_msg', __d('controller', 'Invalid email/password or you have not registered as a jobseeker.', true));
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

    /*
     * Login with employer
     */

    function employerlogin() {

        $this->layout = "client";

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";

        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Employer Login', true));
        $this->set('loginB', 'active');
        $this->set('checkpage', 'login');
        $msgString = "";
        $this->set("returnurl", "");
        $this->userLoggedinCheck();
        $time = time();
        $time1 = date('Y-m-d H:i:s', $time);
        $this->gmailSession('employer');
        $authUrl = '';

        if (isset($this->data) && !empty($this->data)) {
//echo "<pre>"; print_r($this->data); exit;

            if (empty($this->data['User']['email_address'])) {
                $msgString .= __d('controller', 'Please enter your email address.', true) . "<br>";
            } elseif ($this->User->isRecordUniqueemail($this->data["User"]["email_address"]) == true) {

                $msgString .= __d('controller', 'Your email is not registered with', true) . " " . $site_title . '. ' . __d('controller', 'Please enter correct email or register on', true) . " " . $site_title;
            }

            if (empty($this->data['User']['password'])) {
                $msgString .= __d('controller', 'Please enter your password.', true) . "<br>";
            }

            $captcha = $this->Captcha->getVerCode();

            if ($this->Session->read('Userloginstatus') > 1) {
                if ($this->data['User']['captcha'] == "") {
                    $msgString .= __d('controller', 'Please enter security code.', true) . "<br>";
                } elseif ($this->data['User']['captcha'] != $captcha) {
                   // $msgString .= __d('controller', 'Please enter correct security code.', true) . "<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                //echo 'error';
                $this->Session->write('error_msg', $msgString);
                //exit;
            } else {

                $email = trim($this->data['User']['email_address']);
                $password = $this->data['User']['password'];

                //$userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email, "User.modified <=" => $time1)));
                $userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email, "User.modified <=" => $time1, "User.user_type" => 'recruiter')));

                if (is_array($userCheck) && !empty($userCheck) && crypt($password, $userCheck['User']['password']) == $userCheck['User']['password']) {
                    if ($userCheck['User']['status'] == 1 && $userCheck['User']['activation_status'] == 1) {
                        if (isset($this->data['User']['rememberme']) && $this->data['User']['rememberme'] == '1') {
                            setcookie("cookname", $this->data['User']['email_address'], time() + 60 * 60 * 24 * 7, "/");
                            setcookie("cookpass", $this->data['User']['password'], time() + 60 * 60 * 24 * 7, "/");
                        } else {
                            setcookie("cookname", '', time() + 60 * 60 * 24 * 7, "/");
                            setcookie("cookpass", '', time() + 60 * 60 * 24 * 7, "/");
                        }

                        $this->Session->delete('resend_link');
                        $this->Session->delete('resend_array');

                        $this->Session->write("user_id", $userCheck['User']['id']);
                        $this->Session->write("user_type", $userCheck['User']['user_type']);
                        $this->Session->write("email_address", $userCheck['User']['email_address']);
                        $this->Session->write("user_name", $userCheck['User']['first_name']);
                        $this->Session->delete('Userloginstatus');

                        if ($this->Session->read("returnUrl")) {

                            $returnUrl = $this->Session->read("returnUrl");
                            $this->Session->delete("returnUrl");
                            $this->redirect('/' . $returnUrl);
                        } else {

                            if ($userCheck['User']['user_type'] == 'recruiter') {
                                $this->redirect("/users/myaccount");
                            } else {
                                $this->redirect("/candidates/myaccount");
                            }
                        }
                    } else {

                        if ($userCheck['User']['activation_status'] == 0) {
                            $msgString .= __d('controller', 'Please check your mail for activation link to activate your account.', true) . "<br>";
                        } else {
                            $msgString .= __d('controller', 'Your account might have been temporarily disabled. Please contact us for more details.', true) . "<br>";
                        }
                        $this->Session->delete('Userloginstatus');
                        $this->Session->write('error_msg', $msgString);
                        $this->request->data['User']['captcha'] = '';
                    }
                } else {


                    $this->Session->delete('user_id');
                    $i = $this->Session->read('Userloginstatus');
                    if ($i < 6) {
                        $i = 1 + $i;
                        $this->Session->write('Userloginstatus', $i);
                    }
                    if ($i == 1) {
                        //$this->Session->write('error_msg', 'Invalid email and/or password . you have five more attempts.');
                        $this->Session->write('error_msg', __d('controller', 'Invalid email/password or you have not registered as an employer. you have five more attempts.', true));
                    }
                    if ($i == 2) {
                        $this->Session->write('error_msg', __d('controller', 'Invalid email/password or you have not registered as an employer. you have four more attempts.', true));
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
                            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='11'"));

                            $emData = $this->Emailtemplate->getSubjectLang();
                            $subjectField = $emData['subject'];
                            $templateField = $emData['template'];

                            $toSubArray = array('[!username!]', '[!SITE_TITLE!]');
                            $fromSubArray = array($username, $site_title);
                            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                            $this->Email->subject = $subjectToSend;

                            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                            $this->Email->from = $site_title . "<" . $mail_from . ">";

                            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';
                            $toRepArray = array('[!username!]', '[!SITE_TITLE!]');
                            $fromRepArray = array($username, $site_title);
                            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                            $this->Email->layout = 'default';
                            $this->set('messageToSend', $messageToSend);
                            $this->Email->template = 'email_template';
                            $this->Email->sendAs = 'html';
                            $this->Email->send();
                        }

                        $this->Session->write('error_msg', __d('controller', 'Invalid email/password or you have not registered as an employer. you have three more attempts.', true));
                    }
                    if ($i == 4) {
                        $this->Session->write('error_msg', __d('controller', 'Invalid email/password or you have not registered as an employer. you have two more attempts.', true));
                    }
                    if ($i == 5) {
                        $this->Session->write('error_msg', __d('controller', 'Invalid email/password or you have not registered as an employer. you have one more attempts.', true));
                    }
                    if ($i == 6) {
                        $userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email)));
                        if ($userCheck['User']['modified'] <= date('Y-m-d H:i:s', time())) {
                            $userCheck['User']['id'] = $userCheck['User']['id'];
                            $userCheck['User']['modified'] = date('Y-m-d H:i:s', time() + 30 * 60);
                            $this->User->save($userCheck);
                        }
                        $this->Session->write('error_msg', __d('controller', 'Invalid email/password or you have not registered as an employer.', true));
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

    /*
     * facebook login
     */

    public function fblogin($type = null) {

        //echo HTTP_PATH; exit;  
        //pr($_SESSION['type']); exit;


        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');

        if ($_SESSION['type'] == 'jobseeker') {
            $typeUser = 'candidate';
        } else {
            $typeUser = 'recruiter';
        }

        $source = "Facebook";
        $type = $_SESSION['type'];

        if (isset($_SESSION['FB']) && $_SESSION['FB'] != '') {
            $fbID = $_SESSION['FB']['fbid'];
            $fb_first_name = $_SESSION['FB']['first_name'];
            $fb_last_name = $_SESSION['FB']['last_name'];
            $fb_full_name = $fb_first_name . " " . $fb_last_name;
            $fb_username = $_SESSION['FB']['first_name'] . time();
            $fb_email = $_SESSION['FB']['email'];
            $fb_link = '';

            //$fb_user_type = $this->Session->read('loginusertype');

            unset($_SESSION['FB']);

            $userInfo = $this->User->find("first", array("conditions" => array("User.facebook_user_id" => $fbID)));
//            print_r($userInfo); //exit;
//pr($_SESSION['type']); exit;




            if ($userInfo) {

                if ($_SESSION['type'] == 'jobseeker') {
                    if ($userInfo['User']['user_type'] !== 'candidate') {
                        $this->Session->setFlash(__d('controller', 'Please check the email id, it does not belongs to jobseeker account.', true), 'error_msg');
                        echo "<script>
                    window.close();
                    window.opener.location.reload();
                    </script>";
                        exit;
                    }
                }

                if ($_SESSION['type'] == 'employer') {
                    if ($userInfo['User']['user_type'] !== 'recruiter') {
                        $this->Session->setFlash(__d('controller', 'Please check the email id, it does not belongs to employer account.', true), 'error_msg');
                        echo "<script>
                    window.close();
                    window.opener.location.reload();
                    </script>";
                        exit;
                    }
                }


                if ($userInfo['User']['status'] == '1') {
                    //echo "sdfs";exit;
                    //$this->User->id = $userInfo['User']['id'];
                    //$this->request->data['User']['facebook_user_id'] = $fbID;
                    //$this->request->data['User']['full_name'] = $fb_full_name;
                    $this->request->data['User']['id'] = $userInfo['User']['id'];

                    $this->User->id = $userInfo['User']['id'];
                    $this->request->data['User']['first_name'] = $fb_first_name;
                    $this->request->data['User']['last_name'] = $fb_last_name;

                    //$this->request->data['User']['facebook_user_id'] = $fbID;



                    $imageName = $userInfo['User']['profile_image'];
                    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                    srand((double) microtime() * 1000000);
                    $i = 1;
                    $imagename = '';
                    while ($i <= 10) {
                        $num = rand() % 33;
                        $tmp = substr($chars, $num, 1);
                        $imagename = $imagename . $tmp;
                        $i++;
                    }

                    $imagename = $imagename . '.jpg';

                    $fullpath = UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename;
                    $remote_img = 'https://graph.facebook.com/' . $fbID . '/picture?type=large';

                    $img_file = file_get_contents($remote_img);
                    $file_handler = fopen($fullpath, 'w');
                    if (fwrite($file_handler, $img_file) == false) {
                        echo 'error';
                    }
                    fclose($file_handler);

                    $this->request->data['User']['profile_image'] = $imagename;
                    copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename);
                    copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename);
                    $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                    $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
                    if (isset($imagename) && $imagename != "") {
                        if ($imageName != '') {
                            @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imageName);
                            @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imageName);
                            @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imageName);
                        }
                    }
                    //exit;
                    // pr($this->data); exit;
                    $this->User->save($this->data);
                    $this->Session->write("user_id", $userInfo['User']['id']);
                    $this->Session->write("user_name", $userInfo['User']['first_name']);
                    $this->Session->write("email_address", $userInfo['User']['email_address']);
                    $this->Session->write("facebook_id", $userInfo['User']['facebook_user_id']);

                    if ($_SESSION['type'] == 'jobseeker') {
                        $this->Session->write("user_type", 'candidate');

                        // print_r($_SESSION); exit;
                        echo "<script>
                    window.close();
                    window.opener.location.href = '" . HTTP_PATH . "/candidates/myaccount" . "';
                    </script>";
                    } else {
                        $this->Session->write("user_type", 'recruiter');

                        // print_r($_SESSION); exit;
                        echo "<script>
                    window.close();
                    window.opener.location.href = '" . HTTP_PATH . "/users/myaccount" . "';
                    </script>";
                    }



//                    echo "<script>
//                        window.close();
//                        window.opener.location.reload();
//                        </script>";
                } else {
                    $this->Session->setFlash(__d('controller', 'Your account might have been temporarily disabled. Please contact us for more details.', true), 'error_msg');
                    //$this->redirect('/');
                    echo "<script>
                    window.close();
                    window.opener.location.reload();
                    </script>";
                }
            } else {

                $userfbInfo = $this->User->find("first", array("conditions" => array("User.email_address" => $fb_email)));

                $this->request->data['User']['email_address'] = $fb_email;
                $this->request->data['User']['facebook_user_id'] = $fbID;
                $this->request->data['User']['first_name'] = $fb_first_name;
                $this->request->data['User']['last_name'] = $fb_last_name;
                $this->request->data['User']['user_type'] = $typeUser;

                $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                srand((double) microtime() * 1000000);
                $i = 1;
                $imagename = '';
                while ($i <= 10) {
                    $num = rand() % 33;
                    $tmp = substr($chars, $num, 1);
                    $imagename = $imagename . $tmp;
                    $i++;
                }

                $imagename = $imagename . '.jpg';

                $fullpath = UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename;
                $remote_img = 'https://graph.facebook.com/' . $fbID . '/picture?type=large';

                $img_file = file_get_contents($remote_img);
                // echo $remote_img; exit;
                //pr($img_file); exit;
                $file_handler = fopen($fullpath, 'w');
                if (fwrite($file_handler, $img_file) == false) {
                    echo 'error';
                }
                fclose($file_handler);
                copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename);
                copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename);
                $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
                //exit;
                $this->request->data['User']['profile_image'] = $imagename;

                if ($userfbInfo) {
                    $this->request->data['User']['id'] = $userfbInfo['User']['id'];
                } else {
                    $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim(strtolower($fb_first_name)), 'User', 'slug');
                    //pr($this->data); exit;
                    srand((double) microtime() * 1000000);
                    $i = 1;
                    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                    $password = '';
                    while ($i <= 10) {
                        $num = rand() % 33;
                        $tmp = substr($chars, $num, 1);
                        $password = $password . $tmp;
                        $i++;
                    }
                    $passwordPlain = $password;
                    $salt = uniqid(mt_rand(), true);
                    $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
                    $this->request->data['User']['password'] = $new_password;
                    $this->request->data['User']['status'] = '1';
                    $this->request->data['User']['activation_status'] = '1';
                }
                if ($this->User->save($this->data)) {


                    $userId = $this->User->id;
                    $email = $this->data["User"]["email_address"];
                    $username = $this->data["User"]["full_name"];
                    $source = "Facebook";
                    //
                    if ($userfbInfo) {
                        //code here
                    } else {


                        $userId = $this->User->id;
                        $email = $this->data["User"]["email_address"];
                        $username = $this->data["User"]["first_name"] . ' ' . $this->data["User"]["last_name"];
                        $source = "Facebook";

                        $this->Email->to = $email;
                        $currentYear = date('Y', time());

                        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='33'"));
                        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                        $emData = $this->Emailtemplate->getSubjectLang();
                        $subjectField = $emData['subject'];
                        $templateField = $emData['template'];

                        $toSubArray = array('[!username!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!SOURCE!]');
                        $fromSubArray = array($username, $type, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $source);
                        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                        $this->Email->subject = $subjectToSend;

                        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                        $this->Email->from = $site_title . "<" . $mail_from . ">";

                        $toRepArray = array('[!username!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!SOURCE!]');
                        $fromRepArray = array($username, $type, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $source);
                        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                        $this->Email->layout = 'default';
                        $this->set('messageToSend', $messageToSend);
                        $this->Email->template = 'email_template';
                        $this->Email->sendAs = 'html';
                        $this->Email->send();
                    }

                    //pr($this->Email->send()); exit;
                    /*   $this->Session->write("user_id", $userId);
                      $this->Session->write("user_name", $this->data["User"]["first_name"]);
                      $this->Session->write("email_address", $fb_email);
                      $this->Session->write("facebook_id", $fbID);
                      $this->Session->write("fb_id", $fbID); */
                    //$this->redirect('/users/myaccount');
                    // $this->Session->setFlash('Your account has been successfully created by Facebook. For login credentials Please check your email account.<br>', 'success_msg');
                    //   $this->Session->write('success_msg', 'Your account is successfully created. Thank you !');
                    $this->Session->write("user_id", $userId);
                    $this->Session->write("user_name", $username);
                    $this->Session->write("email_address", $email);
                    $this->Session->write("facebook_id", $fbID);
                    // $this->Session->write("facebook_id", $userInfo['User']['facebook_user_id']);
                    if ($_SESSION['type'] == 'jobseeker') {
                        $this->Session->write("user_type", 'candidate');

                        echo "<script>
                        window.close();
                        window.opener.location.href = '" . HTTP_PATH . "/candidates/myaccount" . "';
                        </script>";
                    } else {
                        $this->Session->write("user_type", 'recruiter');

                        echo "<script>
                        window.close();
                        window.opener.location.href = '" . HTTP_PATH . "/users/myaccount" . "';
                        </script>";
                    }
                }
            }
        } else {
            header("Location: " . HTTP_PATH . "/app/webroot/facebook/fbconfig.php");
            exit;
        }
        exit;
    }

    public function myaccount() {

        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'My profile', true));
        $this->userLoginCheck();
        $this->recruiterAccess();
        
       

        $userId = $this->Session->read("user_id");
        $userdetail = $this->User->findById($userId);
        $getRemainingFeatures = $this->Plan->getPlanFeature($userId);
        
        //echo '<pre>';print_r($getRemainingFeatures);exit;
         $this->set('getRemainingFeatures', $getRemainingFeatures);
        
        $this->set('userdetail', $userdetail);
        $this->set('myaccount', 'active');
        //pr($this->data);exit;
    }

    public function editProfile() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Edit Profile', true));
        $this->set('editprofile', 'active');

        $this->userLoginCheck();
        $this->recruiterAccess();
        $msgString = '';

        $userId = $this->Session->read("user_id");
        $UseroldImage = $this->User->find('first', array('conditions' => array('User.id' => $userId), 'fields' => array('User.company_logo', 'User.slug')));
        $this->set("UseroldImage", $UseroldImage);
        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        if ($this->data) {

            if (empty($this->data["User"]["company_name"])) {
                $msgString .= __d('controller', 'Company name is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["User"]["company_name"]);
            }

            if (empty($this->data["User"]["company_about"])) {
                $msgString .= __d('controller', 'Company profile is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["User"]["company_about"]);
            }

            if (empty($this->data["User"]["position"])) {
                $msgString .= __d('controller', 'Position is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["User"]["position"]);
            }


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

            if (empty($this->data["User"]["address"])) {
                $msgString .= __d('controller', 'Address  is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["User"]["address"]);
            }

            if (empty($this->data["User"]["location"])) {
                $msgString .= __d('controller', 'wel', true) . "<br>";
            }

            if (empty($this->data["User"]["contact"])) {
                $msgString .= __d('controller', 'Contact number is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["User"]["contact"]);
            }
            if (empty($this->data["User"]["company_contact"])) {
                $msgString .= __d('controller', 'Company number is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["User"]["company_contact"]);
            }

            if (trim($this->data["User"]["url"]) != '') {
                $msgString .= $this->Swear->checkSwearWord($this->data["User"]["url"]);
            }
            if (!empty($this->data['User']['company_logo']['name'])) {
                $getextention = $this->PImage->getExtension($this->data['User']['company_logo']['name']);
                $extention = strtolower($getextention);
                global $extentions;
                if (!in_array($extention, $extentions)) {
                    $msgString .= __d('controller', 'Not Valid Extention.', true) . "<br>";
                }
                if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                    $msgString .= __d('controller', 'Image file not valid.', true) . "<br>";
                } else {
                    $imageArray = $this->data['User']['company_logo'];
                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_PROFILE_IMAGE_PATH, "jpg,jpeg,png");
                    list($width, $height, $type, $attr) = getimagesize(UPLOAD_FULL_PROFILE_IMAGE_PATH . '/' . $returnedUploadImageArray[0]);
                    copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                    copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                    list($width) = getimagesize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                    if ($width > 650) {
                        $this->PImageTest->resize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_IMAGE_WIDTH, UPLOAD_FULL_PROFILE_IMAGE_HEIGHT, 100);
                    }
                    $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                    $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
                    $profilePic = $returnedUploadImageArray[0];
                    chmod(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                    chmod(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                    chmod(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                    $this->request->data['User']['company_logo'] = $profilePic;
                    if (isset($this->data['User']['old_image']) && $this->data['User']['old_image'] != "") {
                        @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                        @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                        @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                    }
                }
            } else {
                $this->request->data['User']['company_logo'] = $this->data['User']['old_image'];
            }
            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);

                $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['User']['postal_code'])));
                $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
                $this->set('stateList', $stateList);
                $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
                $this->set('cityList', $cityList);
            } else {
                $this->User->id = $this->Session->read("user_id");
                if ($this->User->save($this->data)) {
                    $this->Session->write('success_msg', __d('controller', 'Profile details updated successfully.', true));
                    $this->redirect('/users/myaccount');
                }
            }
        } else {
            $this->User->id = $userId;
            $this->data = $this->User->read();
            $this->request->data['User']['old_image'] = $UseroldImage['User']['company_logo'];
//            $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['User']['postal_code'])));
//            $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
//            $this->set('stateList', $stateList);
//            $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
//            $this->set('cityList', $cityList);
        }
    }

    public function changePassword() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Change Password', true));
        $msgString = '';
        $this->set('changepassword', 'active');
        $this->userLoginCheck();
        $this->recruiterAccess();
        $id = $this->Session->read("user_id");
        $this->User->id = $id;
        $userOldPassword = $this->User->read();
        if ($this->data) {

            if (empty($this->data["User"]["old_password"])) {
                $msgString .= __d('controller', 'Old Password is required field.', true) . "<br>";
            }
            if (empty($this->data["User"]["new_password"])) {
                $msgString .= __d('controller', 'New Password is required field.', true) . "<br>";
            } elseif (strlen($this->data["User"]["new_password"]) < 8) {
                $msgString .= __d('controller', 'Password must be at least 8 characters.', true) . "<br>";
            }


            if (empty($this->data["User"]["conf_password"])) {
                $msgString .= __d('controller', 'Confirm Password is required field.', true) . "<br>";
            } else {
                if (crypt($this->data['User']['old_password'], $userOldPassword['User']['password']) != $userOldPassword['User']['password']) {// Matching the old password
                    $msgString .= __d('controller', 'Old Password is not correct.', true) . "<br>";
                } else {
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
                $this->Session->write('success_msg', __d('controller', 'Your Password has been changed successfully.', true));
                $this->redirect("/users/myaccount");
            }
        }
    }

    public function deleteImage($slug = null) {
        $cnd1 = array("User.slug" => "$slug");
        $userProfileImage = $this->User->field('profile_image', $cnd1);
        if ($userProfileImage) {
            $this->User->updateAll(array('User.profile_image' => "''"), $cnd1);
            @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $userProfileImage);
            @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $userProfileImage);
            @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $userProfileImage);
            $this->Session->write('success_msg', __d('controller', 'Image deleted successfully.', true));
        }
        $this->redirect('/users/uploadPhoto');
        // exit;
    }

    public function deleteEstabImage($slug = null) {
        $cnd1 = array("User.slug" => "$slug");
        $userProfileImage = $this->User->field('company_logo', $cnd1);
        if ($userProfileImage) {
            $this->User->updateAll(array('User.company_logo' => "''"), $cnd1);
            @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $userProfileImage);
            @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $userProfileImage);
            @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $userProfileImage);
            $this->Session->write('success_msg', __d('controller', 'Establishment photo deleted successfully.', true));
        }
        $this->redirect('/users/uploadPhoto');
    }

    public function logout() {
        $this->Session->write('success_msg', __d('controller', 'Logout successfully.', true));
        $this->Session->delete('user_id');
        $this->Session->delete('user_name');
        $this->Session->delete('email_address');
        $lange = $_SESSION['Config']['language'];
        $type = $this->Session->read("type") ? $this->Session->read("type") : $this->Session->read("user_type");

//        session_destroy();
        unset($_SESSION['data']);
        unset($_SESSION['copy_data']);
        unset($_SESSION['type']);
        unset($_SESSION['dis_amount']);
        unset($_SESSION['promo_code']);
        unset($_SESSION['FB']);
        unset($_SESSION['FB']);
        unset($_SESSION['token']);

        $_SESSION['Config']['language'] = $lange;
        $this->Session->write("type", $type);
        //$this->redirect('/homes/index');
        $this->redirect('/');
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
                    $showOldImages = $this->Certificate->find('list', array('conditions' => array('Certificate.user_id' => $userCheck['User']['id']), 'fields' => array('document')));
                    $mailFileArray = array();
                    if ($showOldImages) {
                        foreach ($showOldImages as $image) {
                            $mailFileArray[$image] = UPLOAD_CERTIFICATE_PATH . $image;
                        }
                    }
//                   echo '<pre>'; print_r($showOldImages);
//                    print_r($mailFileArray);
                    if (!empty($mailFileArray))
                        $this->Email->attachments = $mailFileArray;
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
//                    die;


                    $this->Session->write('success_msg', __d('controller', 'A link to reset your password was sent to your email address', true));
                    $this->redirect('/users/forgotPassword');
                } else {
                    $msgstring = __d('controller', 'Your email is not registered with', true) . ' ' . $site_title . '. ' . __d('controller', 'Please enter correct email or register on', true) . '. ' . $site_title;
                    $this->Session->write("error_msg", $msgstring);
                    $this->request->data['User']['email_address'] = "";
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
            $userCheck = $this->User->find('first', array('conditions' => array('User.email_address' => urldecode($email), 'User.id' => $id), 'fields' => array('User.forget_password_status', 'User.password', 'User.user_type')));

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
                        $this->Session->write('success_msg', __d('controller', 'Password is reset successfully. Please Login ', true));

                        if ($userCheck['User']['user_type'] == 'recruiter') {
                            $this->redirect('/users/employerlogin');
                        } else {
                            $this->redirect('/users/login');
                        }
                    }
                }
            } else {
                $this->Session->write('error_msg', __d('controller', 'You have already use this link!', true));
                $this->redirect('/users/login');
            }
        } else {
            $this->redirect('/users/login');
        }
    }

    public function uploadPhoto() {

        $this->layout = "client";
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Upload Photo', true));
        $this->set('uploadPhoto', 'active');
        $msgString = '';
        global $extentions;
        $this->userLoginCheck();
        $this->recruiterAccess();
        $id = $this->Session->read("user_id");
        $UseroldLogo = $this->User->find('first', array('conditions' => array('User.id' => $id), 'fields' => array('User.company_logo', 'User.slug')));
        $this->set("UseroldLogo", $UseroldLogo);

        $max_size = $this->getSiteConstant('max_size');
        $over_max_size = $max_size * 1048576;
        $msgString = '';

        $UseroldImage = $this->User->find('first', array('conditions' => array('User.id' => $id), 'fields' => array('User.profile_image', 'User.slug')));
        $this->set("UseroldImage", $UseroldImage);

        if ($this->data) {

//            $getextention = $this->PImage->getExtension($this->data['User']['profile_image']['name']);
//            $extention = strtolower($getextention);
//            if (empty($this->data["User"]["profile_image"]["name"])) {
//                $msgString .= __d('controller', 'Image is required field.', true) . "<br>";
//            } elseif ($this->data['User']['profile_image']['size'] > $over_max_size) {
//                $msgString .= __d('controller', 'Max file size upload is', true) . " " . $max_size . " Mb.<br>";
//            } elseif (!in_array($extention, $extentions)) {
//                $msgString .= __d('controller', 'Not Valid Extention.', true) . "<br>";
//            }






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
                        $msgString .= __d('controller', 'Image file not valid.', true) . "<br>";
                    } else if ($width < 250 && $height < 250) {
                        @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . '/' . $returnedUploadImageArray[0]);
                        $msgString .= __d('controller', 'Images size must be bigger than  250 X 250 pixels.', true) . "<br>";
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
                        chmod(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        $this->request->data['User']['profile_image'] = $profilePic;
                        if (isset($this->data['User']['old_image']) && $this->data['User']['old_image'] != "") {
                            @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                            @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                            @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                        }
                    }
                } else {
                    $this->request->data['User']['profile_image'] = $this->data['User']['old_image'];
                }

                if (!empty($this->data['User']['company_logo']['name'])) {
                    $getextention = $this->PImage->getExtension($this->data['User']['company_logo']['name']);
                    $extention = strtolower($getextention);
                    global $extentions;
                    if (!in_array($extention, $extentions)) {
                        $msgString .= __d('controller', 'Not Valid Extention.', true) . "<br>";
                    }
                    $imageArray = $this->data['User']['company_logo'];
                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_PROFILE_IMAGE_PATH, "jpg,jpeg,png");
                    list($width, $height, $type, $attr) = getimagesize(UPLOAD_FULL_PROFILE_IMAGE_PATH . '/' . $returnedUploadImageArray[0]);
                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= __d('controller', 'Image file not valid.', true) . "<br>";
                    } else {
                        copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        list($width) = getimagesize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        if ($width > 650) {
                            $this->PImageTest->resize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_LOGO_IMAGE_WIDTH, UPLOAD_FULL_PROFILE_LOGO_IMAGE_HEIGHT, 100);
                        }
                        $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                        $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
                        $profilePic = $returnedUploadImageArray[0];
                        chmod(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        $this->request->data['User']['company_logo'] = $profilePic;
                        if (isset($this->data['User']['old_logo']) && $this->data['User']['old_logo'] != "") {
                            @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $this->data['User']['old_logo']);
                            @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $this->data['User']['old_logo']);
                            @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $this->data['User']['old_logo']);
                        }
                    }
                } else {
                    $this->request->data['User']['company_logo'] = $this->data['User']['old_logo'];
                }

                if (isset($msgString) && $msgString != '') {
                    $this->Session->write('error_msg', $msgString);
                } else {
                    $id = $this->Session->read("user_id");
                    $this->User->id = $this->Session->read("user_id");

                    if ($this->User->save($this->data)) {
                        $this->Session->write('success_msg', __d('controller', 'Your Image has been Uploaded successfully.', true));
                        $this->redirect('/users/uploadPhoto');
                    }
                }
            }
        } else {
            $this->User->id = $this->Session->read("user_id");
            $this->data = $this->User->read();
            $this->request->data['User']['old_logo'] = $UseroldLogo['User']['company_logo'];
            $this->request->data['User']['old_image'] = $UseroldImage['User']['profile_image'];
        }
    }

    public function admin_sendemail($slug = null) {

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');
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
                    //$this->Email->cc =$this->Admin->field('cc_email', array('Admin.id' => 1));
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='20'"));
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];

                    // $this->Email->subject = $this->data['User']['subject'];
                    //$this->Email->subject = $emailtemplateMessage['Emailtemplate']['subject'];
                    $toSubArray = array('[!username!]', '[!subject!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                    $fromSubArray = array($username, $this->data['User']['subject'], $this->data['User']['message'], $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!subject!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                    $fromRepArray = array($username, $this->data['User']['subject'], $this->data['User']['message'], $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                    $this->Session->write('success_msg', 'You have sent the email to the seller successfully.');
                }
                $this->redirect('/admin/users/sendemail');
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

    public function resendEmail() {
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');

        $resend_link = $this->Session->read('resend_link');
        $resend_array = $this->Session->read('resend_array');
        if ($resend_array) {
            $userType = $resend_array['userType'];
            $email = $resend_array['email'];
            $username = $resend_array['username'];
            $passwordPlain = $resend_array['password'];
            $link = $resend_array['link'];

            if ($userType == 'recruiter') {

                $company_name = $resend_array['company_name'];

                $this->Email->to = $email;
                $currentYear = date('Y', time());
                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';
                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='19'"));

                $emData = $this->Emailtemplate->getSubjectLang();
                $subjectField = $emData['subject'];
                $templateField = $emData['template'];

                $toSubArray = array('[!username!]', '[!company_name!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                $fromSubArray = array($username, $company_name, $userType, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $link, $site_url);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                $this->Email->subject = $subjectToSend;

                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";

                $toRepArray = array('[!username!]', '[!company_name!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                $fromRepArray = array($username, $company_name, $userType, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $link, $site_url);
                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                $this->Email->layout = 'default';
                $this->set('messageToSend', $messageToSend);
                $this->Email->template = 'email_template';
                $this->Email->sendAs = 'html';
                $this->Email->send();
            } else {


                $this->Email->to = $email;

                $currentYear = date('Y', time());
                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='13'"));
                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                $emData = $this->Emailtemplate->getSubjectLang();
                $subjectField = $emData['subject'];
                $templateField = $emData['template'];

                //$this->Email->subject = $emailtemplateMessage['Emailtemplate']['subject'];
                $toSubArray = array('[!username!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                $fromSubArray = array($username, $userType, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $link, $site_url);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                $this->Email->subject = $subjectToSend;

                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";

                $toRepArray = array('[!username!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                $fromRepArray = array($username, $userType, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $link, $site_url);
                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                $this->Email->layout = 'default';
                $this->set('messageToSend', $messageToSend);
                $this->Email->template = 'email_template';
                $this->Email->sendAs = 'html';
                $this->Email->send();
            }

            $this->Session->write('success_msg', __d('controller', 'Activation email send to your email id.', true));
            $this->redirect('/users/login');
        } else {
            $this->redirect('/users/login');
        }
    }

    public function newRegister() {
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . "Sign Up");
        $this->layout = "client";
        $this->set('registerA', 'active');
    }

    public function admin_sliderSearch() {
        $this->layout = "admin";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Employer List");

        $this->set('user_list', 'active');
        $this->set('default', '2');
        $condition = array('user_type' => 'recruiter');
        $separator = array();
        $urlSeparator = array();
        $userName = '';

        if (!empty($this->data)) {

            if (isset($this->data['User']['userName']) && $this->data['User']['userName'] != '') {
                $userName = trim($this->data['User']['userName']);
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
        }

        if (isset($userName) && $userName != '') {
            $separator[] = 'userName:' . urlencode($userName);
            $userName = str_replace('_', '\_', $userName);
            $condition[] = " (`User`.`company_name` LIKE '%" . addslashes($userName) . "%' OR `User`.`first_name` LIKE '%" . addslashes($userName) . "%' or concat(`User.first_name`,' ',`User.last_name`) LIKE '%" . addslashes($userName) . "%' or `User`.`email_address` LIKE '%" . addslashes($userName) . "%' or `User`.`last_name` LIKE '%" . addslashes($userName) . "%' OR `User`.`company_name` LIKE '%" . addslashes($userName) . "%' ) ";
            $userName = str_replace('\_', '_', $userName);
            $this->set('searchKey', $userName);
        }



        $order = 'User.id Desc';

        $separator = implode("/", $separator);

        $urlSeparator = implode("/", $urlSeparator);
        $this->set('userName', $userName);
        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['User'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('users', $this->paginate('User'));
        //pr($condition);


        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/users';
            $this->render('selectslider');
        }
    }

    public function reportproblem() {
        $this->layout = "";
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');
        $msgString = "";

        $username = $this->data['User']['name'];
        $user_email = $this->data['User']['email_address'];
        $contact = $this->data['User']['contact'];
        $message = $this->data['User']['message'];
        $currentUrl = $this->data['User']['current_url'];

        if ($this->data) {

            if (empty($username)) {
                $msgString .= __d('controller', 'User name is required field.', true) . "<br>";
            }
            if (empty($message)) {
                $msgString .= __d('controller', 'Message is required field.', true) . "<br>";
            }

            if (isset($msgString) && $msgString != '') {
                ///$this->Session->write('error_msg', $msgString);
                echo $msgString;
                exit;
            } else {

                $adminDetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => 1)));
                $adminEmail = $adminDetail['Admin']['email'];

                $this->Email->to = $adminEmail;

                $created = date("F j, Y");
                $currentYear = date('Y', time());
                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='35'"));
                $link = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                $emData = $this->Emailtemplate->getSubjectLang();
                $subjectField = $emData['subject'];
                $templateField = $emData['template'];

                $toSubArray = array('[!name!]', '[!user_email!]', '[!contact!]', '[!message!]', '[!created!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!DATE!]', '[!HTTP_PATH!]', '[!CURRENT_URL!]');
                $fromSubArray = array($username, $user_email, $contact, $message, $created, $site_title, $link, $site_url, $currentYear, HTTP_PATH, $currentUrl);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                $this->Email->subject = $subjectToSend;

                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";

                $toRepArray = array('[!name!]', '[!user_email!]', '[!contact!]', '[!message!]', '[!created!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!DATE!]', '[!HTTP_PATH!]', '[!CURRENT_URL!]');
                $fromRepArray = array($username, $user_email, $contact, $message, $created, $site_title, $link, $site_url, $currentYear, HTTP_PATH, $currentUrl);
                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                $this->Email->layout = 'default';
                $this->set('messageToSend', $messageToSend);
                $this->Email->template = 'email_template';
                $this->Email->sendAs = 'html';
                $this->Email->send();

                // $this->Session->write('success_msg', 'Your feedback successfully send.');
                echo __d('controller', 'Your feedback successfully sent.', true);
                exit;
            }
        }
    }

    /*
     * Linkedin Start
     */

    function linkedinlogin() {
        $this->layout = "client";
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . __d('controller', 'Linkedin Login', true));

        $userid = $this->Session->read('user_id');
        $user_type = $this->Session->read('loginusertype');
        //echo"<==>"; print_r($userid); exit;

        if (empty($userid)) {

            require LINKEDIN_HTTP_FILE;
            require LINKEDIN_OAUTH_CLIENT_FILE;

            $client = new oauth_client_class;
            $client->debug = 1;
            $client->debug_http = 1;
            $client->server = 'LinkedIn';
            $client->redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] .
                    dirname(strtok($_SERVER['REQUEST_URI'], '?')) . '/linkedinlogin';
//            $client->redirect_uri = 'http://192.168.0.251' .
//                    dirname(strtok($_SERVER['REQUEST_URI'], '?')) . '/linkedinlogin';

            $client->client_id = LINKEDIN_API_KEY;
            $application_line = __LINE__;
            $client->client_secret = LINKEDIN_SECRET;

            /*  API permission scopes
             *  Separate scopes with a space, not with +
             */
            $client->scope = 'r_basicprofile r_emailaddress ';

            if (strlen($client->client_id) == 0 || strlen($client->client_secret) == 0)
                die('Please go to LinkedIn Apps page https://www.linkedin.com/secure/developer?newapp= , ' .
                                'create an application, and in the line ' . $application_line .
                                ' set the client_id to Consumer key and client_secret with Consumer secret. ' .
                                'The Callback URL must be ' . $client->redirect_uri) . ' Make sure you enable the ' .
                        'necessary permissions to execute the API calls your application needs.';

            if (($success = $client->Initialize())) {
                if (($success = $client->Process())) {
                    if (strlen($client->access_token)) {
                        $success = $client->CallAPI(
                                'https://api.linkedin.com/v1/people/~:(id,picture-urls::(original),email-address,first-name,last-name,location:(name),public-profile-url,siteStandardProfileRequest)', 'GET', array(
                            'format' => 'json'
                                ), array('FailOnAccessError' => true), $user);
                    }
                }
                $success = $client->Finalize($success);
            }

            if ($client->exit)
                exit;
            if (strlen($client->authorization_error)) {
                $client->error = $client->authorization_error;
                $success = false;
            }

            if ($success) {


                $email_address = $user->emailAddress;
                //$count1 = $this->Interviewer->find('count', array('conditions' => array('Interviewer.email_address' => $email_address)));
                //$count2 = $this->Resource->find('count', array('conditions' => array('Resource.email_address' => $email_address)));
                //$count3 = $this->Selfresource->find('count', array('conditions' => array('Selfresource.email_address' => $email_address)));

                $count1 = $this->User->find('count', array('conditions' => array('User.email_address' => $email_address)));

                $error = '';
                //if ($count1 > 0) {
                //$error = 'Account Already Exists As An Jobseeker';
                // } //elseif ($count2 > 0) {
//                    $error = 'Account Already Exists As A Resource User';
//                } elseif ($count3 > 0) {
//                    $error = 'Account Already Exists As A Self Load User';
//                }

                if ($error) {
                    $this->Session->setFlash($error, 'error_msg');
                    //$this->Session->write($error);
                    echo "<script>
                                window.close();
                                window.opener.location.reload();
                                </script>";
                } else {
                    $userInfo = $this->User->find("first", array("conditions" => array("User.email_address" => $user->emailAddress)));

                    if ($userInfo) {

                        if ($user_type == 'jobseeker') {
                            if ($userInfo['User']['user_type'] !== 'candidate') {

                                //$this->Session->destroy();
                                //session_start();

                                $this->Session->setFlash(__d('controller', 'Please check the email id, it does not belongs to jobseeker account.', true), 'error_msg');

                                echo "<script>
                    window.close();
                    window.opener.location.reload();
                    </script>";
                                exit;
                            }
                        }

                        if ($user_type == 'employer') {
                            if ($userInfo['User']['user_type'] !== 'recruiter') {
                                //$this->Session->destroy();
                                // session_start();

                                $this->Session->setFlash(__d('controller', 'Please check the email id, it does not belongs to employer account.', true), 'error_msg');

                                echo "<script>
                    window.close();
                    window.opener.location.reload();
                    </script>";
                                exit;
                            }
                        }

                        if ($userInfo['User']['status'] == '1' && $userInfo['User']['activation_status'] == '1') {

                            $this->User->id = $userInfo['User']['id'];
                            $this->request->data['User']['linkedin_id'] = $user->id;
//                        $this->request->data['User']['linkedin_link'] = $user->publicProfileUrl;
//                        $this->request->data['User']['first_name'] = $user->firstName;
//                        $this->request->data['User']['last_name'] = $user->lastName;
//                        if ($user->location->name) {
//                            $this->request->data['User']['address'] = $user->location->name;
//                        }
                            if ($user->pictureUrls->values) {
                                $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                                srand((double) microtime() * 1000000);
                                $i = 1;
                                $imagename = '';
                                while ($i <= 10) {
                                    $num = rand() % 33;
                                    $tmp = substr($chars, $num, 1);
                                    $imagename = $imagename . $tmp;
                                    $i++;
                                }
                                $imagename = $imagename . '.jpg';
                                $fullpath = UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename;
                                $remote_img = $user->pictureUrls->values[0];
                                $img_file = file_get_contents($remote_img);
                                $file_handler = fopen($fullpath, 'w');
                                if (fwrite($file_handler, $img_file) == false) {
                                    echo 'error';
                                }
                                fclose($file_handler);
                                $this->request->data['User']['profile_image'] = $imagename;
                                copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename);
                                copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename);
                                $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                                $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
                            }
                            //exit;

                            $this->User->save($this->data);

                            $user_id = $this->User->id;

                            $this->Session->write("user_id", $userInfo['User']['id']);
                            $this->Session->write("user_name", $userInfo['User']['first_name']);
                            $this->Session->write("user_type", $userInfo['User']['user_type']);
                            $this->Session->write("email_address", $userInfo['User']['email_address']);

                            $user_type = $userInfo['User']['user_type'];

                            if ($user_type == 'candidate') {
                                echo "<script>
                                window.close();
                                window.opener.location.href = '" . HTTP_PATH . "/candidates/myaccount" . "';
                                </script>";
                            } elseif ($user_type == 'recruiter') {
                                echo "<script>
                                window.close();
                                window.opener.location.href = '" . HTTP_PATH . "/users/myaccount" . "';
                                </script>";
                            }
                        } else {
                            $this->Session->setFlash(__d('controller', 'Your account might have been temporarily disabled. Please contact us for more details.', true), 'error_msg');
                            echo "<script>
                                window.close();
                                window.opener.location.reload();
                                </script>";
                        }
                    } else {

                        // $user_type = 'candidate';


                        if ($user_type == '') {
                            $this->Session->setFlash(__d('controller', 'Please register first on site.', true), 'error_msg');
                            echo "<script>
                                window.close();
                                window.opener.location.reload();
                                </script>";
                        } else {
                            $this->request->data['User']['email_address'] = $user->emailAddress;
                            $this->request->data['User']['linkedin_id'] = $user->id;
                            //$this->request->data['User']['linkedin_link'] = $user->publicProfileUrl;
                            $this->request->data['User']['first_name'] = $user->firstName;
                            $this->request->data['User']['last_name'] = $user->lastName;
                            //$this->request->data['User']['user_type'] = $user_type;
                            if ($user_type == 'employer') {
                                $this->request->data['User']['user_type'] = 'recruiter';
                                $usertypeSes = 'recruiter';
                            } else {
                                $this->request->data['User']['user_type'] = 'candidate';
                                $usertypeSes = 'candidate';
                            }



                            /* $company = str_replace(" ", "", 'UUUGA');
                              $result = substr($company, 0, 3);
                              $result_company = strtoupper($result);

                              $countryInfo = $this->Country->findById(41);

                              $country = str_replace(" ", "", $countryInfo['Country']['name']);
                              $result1 = substr($country, 0, 3);
                              $result_country = strtoupper($result1);
                              $entity = '000';

                              $lastserial = $this->User->find('first', array('conditions' => array('User.user_type' => 'Buyer'), 'fields' => array('User.serial_no'), 'order' => array('User.id' => 'desc')));
                              if ($lastserial) {
                              $lastserialno = substr($lastserial['User']['serial_no'], 3, 5);
                              $serial_no = $result_company . '' . sprintf('%05d', $lastserialno + 1) . $result_country . $entity;
                              } else {
                              $lastserialno = 0;
                              $serial_no = $result_company . '' . sprintf('%05d', $lastserialno + 1) . $result_country . $entity;
                              } */

//                    if ($user->location->name) {
//                        $this->request->data['User']['address'] = $user->location->name;
//                    }
                            if ($user->pictureUrls->values) {
                                $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                                srand((double) microtime() * 1000000);
                                $i = 1;
                                $imagename = '';
                                while ($i <= 10) {
                                    $num = rand() % 33;
                                    $tmp = substr($chars, $num, 1);
                                    $imagename = $imagename . $tmp;
                                    $i++;
                                }
                                $imagename = $imagename . '.jpg';
                                $fullpath = UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename;
                                $remote_img = $user->pictureUrls->values[0];
                                $img_file = file_get_contents($remote_img);
                                $file_handler = fopen($fullpath, 'w');
                                if (fwrite($file_handler, $img_file) == false) {
                                    echo 'error';
                                }
                                fclose($file_handler);
                                $this->request->data['User']['profile_image'] = $imagename;
                                copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename);
                                copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename);
                                $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                                $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
                            }

                            $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim($user->firstName) . ' ' . trim($user->lastName), 'User', 'slug');
                            $this->request->data['User']['status'] = '1';
                            $this->request->data['User']['activation_status'] = '1';
                            // $this->request->data['User']['serial_no'] = $serial_no;
                            // $this->request->data['User']['organization'] = 'UUUGA';
                            // $this->request->data['User']['country_id'] = 41;
                            // $this->request->data['User']['agent_id'] = 324;
                            srand((double) microtime() * 1000000);
                            $i = 1;
                            $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                            $password = '';
                            while ($i <= 10) {
                                $num = rand() % 33;
                                $tmp = substr($chars, $num, 1);
                                $password = $password . $tmp;
                                $i++;
                            }

                            $passwordPlain = $password;
                            $salt = uniqid(mt_rand(), true);
                            $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');

                            $this->request->data['User']['password'] = $new_password;
                            $enc_password = $this->generatePassword($passwordPlain);
                            $this->request->data['User']['plain_password'] = $enc_password;

                            if ($this->User->save($this->data)) {
                                $userId = $this->User->id;
                                $email = $user->emailAddress;
                                $name = $user->firstName . ' ' . $user->lastName;

                                $this->Session->write("user_id", $userId);
                                $this->Session->write("user_name", $name);
                                $this->Session->write("email_address", $email);
                                $this->Session->write("user_type", $usertypeSes);

                                $userCheck = $this->User->find('first', array('conditions' => array('User.id' => $userId)));

                                $email = $userCheck["User"]["email_address"];
                                $name = $userCheck['User']['first_name'] . " " . $userCheck['User']['last_name'];
                                $source = "Linkedin";

                                $type = $user_type;

                                $this->Email->to = $email;
                                //$this->Email->bcc = $this->Admin->field('email', array('Admin.id' => 1));
                                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='33'"));

                                $emData = $this->Emailtemplate->getSubjectLang();
                                $subjectField = $emData['subject'];
                                $templateField = $emData['template'];

                                $currentYear = date('Y', time());

                                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                                $toSubArray = array('[!username!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!SOURCE!]');
                                $fromSubArray = array($name, $type, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $source);
                                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                                $this->Email->subject = $subjectToSend;

                                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                                $this->Email->from = $site_title . "<" . $mail_from . ">";

                                $toRepArray = array('[!username!]', '[!user_type!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!SOURCE!]');
                                $fromRepArray = array($name, $type, $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $source);
                                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                                $this->Email->layout = 'default';
                                $this->set('messageToSend', $messageToSend);
                                $this->Email->template = 'email_template';
                                $this->Email->sendAs = 'html';
                                $this->Email->send();
                            }
//echo $user_type; exit;

                            if ($user_type == 'jobseeker') {
                                echo "<script>
                                window.close();
                                window.opener.location.href = '" . HTTP_PATH . "/candidates/myaccount" . "';
                                </script>";
                            } elseif ($user_type == 'employer') {
                                echo "<script>
                                window.close();
                                window.opener.location.href = '" . HTTP_PATH . "/users/myaccount" . "';
                                </script>";
                            }

                            exit;
                        }
                    }
                }
            } else {
//                echo "<script>
//                window.close();
//                window.opener.location.href = '" . HTTP_PATH . "/homes/index/login" . "';
//                </script>";
                echo "<script>
                window.close();
                window.opener.location.href = '" . HTTP_PATH . "/users/login" . "';
                </script>";
            }
        }
        exit;
    }

    function setlinkedinlogin() {

        $this->layout = "client";
        $this->set('active', 'invitefriends');
        $this->set('title_for_layout', TITLE_FOR_PAGES . __d('title', 'LinkedinLogin', true));

        require LINKEDIN_HTTP_FILE;
        require LINKEDIN_OAUTH_CLIENT_FILE;

        $client = new oauth_client_class;
        $client->debug = 1;
        $client->debug_http = 1;
        $client->server = 'LinkedIn';
        $client->redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] .
                dirname(strtok($_SERVER['REQUEST_URI'], '?')) . '/linkedinlogin';

        $client->client_id = LINKEDIN_API_KEY;
        $application_line = __LINE__;
        $client->client_secret = LINKEDIN_SECRET;

        /*  API permission scopes
         *  Separate scopes with a space, not with +
         */
        $client->scope = 'r_basicprofile r_emailaddress r_contactinfo';

        if (strlen($client->client_id) == 0 || strlen($client->client_secret) == 0)
            die('Please go to LinkedIn Apps page https://www.linkedin.com/secure/developer?newapp= , ' .
                            'create an application, and in the line ' . $application_line .
                            ' set the client_id to Consumer key and client_secret with Consumer secret. ' .
                            'The Callback URL must be ' . $client->redirect_uri) . ' Make sure you enable the ' .
                    'necessary permissions to execute the API calls your application needs.';

        if (($success = $client->Initialize())) {
            if (($success = $client->Process())) {
                if (strlen($client->access_token)) {
                    $success = $client->CallAPI(
                            'http://api.linkedin.com/v1/people/~:(id,picture-urls::(original),email-address,first-name,last-name,location:(name),public-profile-url,siteStandardProfileRequest)', 'GET', array(
                        'format' => 'json'
                            ), array('FailOnAccessError' => true), $user);
                }
            }
            $success = $client->Finalize($success);
        }
        if ($client->exit)
            exit;
        if (strlen($client->authorization_error)) {
            $client->error = $client->authorization_error;
            $success = false;
        }

        //        echo '<pre>'; 
        //        print_r($user);
        //        exit;

        if ($success) {
            $this->User->id = $this->Session->read('user_id');
            $this->request->data['User']['linkedin_id'] = $user->id;
            $this->request->data['User']['linkedin_link'] = $user->publicProfileUrl;
            $this->request->data['User']['first_name'] = $user->firstName;
            $this->request->data['User']['last_name'] = $user->lastName;
            if ($user->location->name) {
                $this->request->data['User']['address'] = $user->location->name;
            }
            if ($user->pictureUrls->values) {
                $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                srand((double) microtime() * 1000000);
                $i = 1;
                $imagename = '';
                while ($i <= 10) {
                    $num = rand() % 33;
                    $tmp = substr($chars, $num, 1);
                    $imagename = $imagename . $tmp;
                    $i++;
                }
                $imagename = $imagename . '.jpg';
                $fullpath = UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename;
                $remote_img = $user->pictureUrls->values[0];
                $img_file = file_get_contents($remote_img);
                $file_handler = fopen($fullpath, 'w');
                if (fwrite($file_handler, $img_file) == false) {
                    echo 'error';
                }
                fclose($file_handler);
                $this->request->data['User']['profile_image'] = $imagename;
                copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename);
                copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename);
                $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_PATH . $imagename, UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_PATH . $imagename, UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
            }
            //exit;
            $this->User->save($this->data);

            echo "<script>
                                window.close();
                                window.opener.location.reload();
                                </script>";
        } else {
            echo "<script>
                alert('There is an Error!');
                window.close();
                window.opener.location.href = '" . HTTP_PATH . "/users/notification" . "';
                </script>";
        }
    }

    public function peoplesview($alpha = null) {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Peoples having starting alphabetically', true) . " -" . $alpha);
        $this->set('jobs_list', 'active');
        $this->set('slug', $alpha);
        $this->set('alpha', $alpha);

        if ($alpha != "") {

            $userId = $this->Session->read('user_id');
            $condition[] = '(User.first_name LIKE "' . $alpha . '%" AND User.user_type = "candidate" AND User.status = 1)';

            $separator = array();
            $urlSeparator = array();

            $order = 'User.first_name ASC';

            $separator = implode("/", $separator);
            $urlSeparator = implode("/", $urlSeparator);

            $this->set('separator', $separator);
            $this->set('urlSeparator', $urlSeparator);
            $this->paginate['User'] = array('conditions' => $condition, 'limit' => '100', 'page' => '1', 'order' => $order);
            //pr($this->paginate['Job']);exit;
            $this->set('candidates', $this->paginate('User'));

            if ($this->request->is('ajax')) {
                $this->layout = '';
                $this->viewPath = 'Elements' . DS . 'candidates';
                $this->render('peoples');
            }
        } else {
            $this->redirect('/homes/error');
        }
    }

    public function viewjobs($alpha) {


        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'View Jobs having starting alphabetically', true) . " -  " . $alpha);
        $this->set('jobs_list', 'active');
        $this->set('alpha', $alpha);
        $this->set('slug', $alpha);

        $userId = $this->Session->read('user_id');

        $condition = array('Job.status' => 1, 'Job.payment_status' => 2);

        $separator = array();
        $urlSeparator = array();

        $order = '';

        if (!empty($this->data)) {
            //pr($this->data); exit;
            if (isset($this->data['Job']['created']) && $this->data['Job']['created'] != '') {
                $order = 'Job.' . $this->data['Job']['created'];
            }

            if (isset($this->data['Job']['keyword']) && $this->data['Job']['keyword'] != '') {
                $keyword = trim($this->data['Job']['keyword']);
            }

            if (isset($this->data['Job']['category_id']) && !empty($this->data['Job']['category_id']) && count($this->data['Job']['category_id']) > 0) {
                if (is_array($this->data['Job']['category_id'])) {
                    $category_id = implode('-', $this->data['Job']['category_id']);
                } else {
                    $category_id = $this->data['Job']['category_id'];
                }
            }

            if (isset($this->data['Job']['searchkey']) && $this->data['Job']['searchkey'] != '') {
                if (is_array($this->data['Job']['searchkey'])) {
                    $searchkey = implode('-', $this->data['Job']['searchkey']);
                } else {
                    $searchkey = $this->data['Job']['searchkey'];
                }
            }

            if (isset($this->data['Job']['subcategory_id']) && !empty($this->data['Job']['subcategory_id']) && count($this->data['Job']['subcategory_id']) > 0) {
                if (is_array($this->data['Job']['subcategory_id'])) {
                    $subcategory_id = implode('-', $this->data['Job']['subcategory_id']);
                } else {
                    $subcategory_id = $this->data['Job']['subcategory_id'];
                }
            }

            if (isset($this->data['Job']['location']) && !empty($this->data['Job']['location']) && count($this->data['Job']['location']) > 0) {
                if (is_array($this->data['Job']['location'])) {
                    $location = implode('-', $this->data['Job']['location']);
                } else {
                    $location = $this->data['Job']['location'];
                }
            }

            if (isset($this->data['Job']['work_type']) && !empty($this->data['Job']['work_type']) && count($this->data['Job']['work_type']) > 0) {
                if (is_array($this->data['Job']['work_type'])) {
                    $worktype = implode('-', $this->data['Job']['work_type']);
                } else {
                    $worktype = $this->data['Job']['work_type'];
                }
            }


            if (!empty($this->data['Job']['skill'])) {
                //$skill = implode(",", $this->data['Job']['skill']);
                $skill = $this->data['Job']['skill'];
                //$skill = addslashes($skill);
                $this->set('skill', $this->data['Job']['skill']);
            }

            if (!empty($this->data['Job']['designation'])) {

                $designation = addslashes($this->data['Job']['designation']);
                $this->set('designation', $this->data['Job']['designation']);
            }
            if (isset($this->data['Job']['salary']) && $this->data['Job']['salary'] != '') {
                $salary = trim($this->data['Job']['salary']);
            }
//            if (isset($this->data['Job']['max_salary']) && $this->data['Job']['max_salary'] != '') {
//                $max_salary = trim($this->data['Job']['max_salary']);
//            }

            if (isset($this->data['Job']['exp']) && $this->data['Job']['exp'] != '') {
                $exp = $this->data['Job']['exp'];
                $expArray = explode('-', $exp);
                $min_exp = $expArray[0];
                $max_exp = $expArray[1];
            }

            if (isset($this->data['Job']['min_exp']) && $this->data['Job']['min_exp'] != '') {
                $min_exp = trim($this->data['Job']['min_exp']);
            }
            if (isset($this->data['Job']['max_exp']) && $this->data['Job']['max_exp'] != '') {
                $max_exp = trim($this->data['Job']['max_exp']);
            }
        } elseif (!empty($this->params)) {

            if (isset($this->params['named']['keyword']) && $this->params['named']['keyword'] != '') {
                $keyword = urldecode(trim($this->params['named']['keyword']));
            }
            if (isset($this->params['named']['category_id']) && $this->params['named']['category_id'] != '') {
                $category_id = trim($this->params['named']['category_id']);
            }
            if (isset($this->params['named']['subcategory_id']) && $this->params['named']['subcategory_id'] != '') {
                $subcategory_id = trim($this->params['named']['subcategory_id']);
            }

            if (isset($this->params['named']['location']) && $this->params['named']['location'] != '') {
                $location = urldecode(trim($this->params['named']['location']));
            }

            if (isset($this->params['named']['work_type']) && $this->params['named']['work_type'] != '') {
                $worktype = urldecode(trim($this->params['named']['work_type']));
            }

            if (isset($this->params['named']['skill']) && $this->params['named']['skill'] != '') {
                $skill = trim($this->params['named']['skill']);
                $skill = addslashes($skill);
                $this->set('skill', $skill);
            }

            if (isset($this->params['named']['designation']) && $this->params['named']['designation'] != '') {
                $designation = trim($this->params['named']['designation']);
                $designation = addslashes($designation);
                $this->set('designation', $designation);
            }
            if (isset($this->params['named']['salary']) && $this->params['named']['salary'] != '') {
                $salary = urldecode(trim($this->params['named']['salary']));
            }
            if (isset($this->params['named']['max_salary']) && $this->params['named']['max_salary'] != '') {
                $max_salary = urldecode(trim($this->params['named']['max_salary']));
            }
            if (isset($this->params['named']['min_exp']) && $this->params['named']['min_exp'] != '') {
                $min_exp = urldecode(trim($this->params['named']['min_exp']));
            }
            if (isset($this->params['named']['max_exp']) && $this->params['named']['max_exp'] != '') {
                $max_exp = urldecode(trim($this->params['named']['max_exp']));
            }

            if (isset($this->params['named']['searchkey']) && $this->params['named']['searchkey'] != '') {
                $searchkey = urldecode(trim($this->params['named']['searchkey']));
            }

//            if (isset($this->params['named']['order']) && $this->params['named']['order'] != '') {
//                $order = urldecode(trim($this->params['named']['order']));
//            }
        }



        if (isset($keyword) && $keyword != '') {
            $separator[] = 'keyword:' . urlencode($keyword);
            $keyword = str_replace('_', '\_', $keyword);
            $condition[] = " (`Job`.`title` LIKE '%" . addslashes($keyword) . "%' OR `Job`.`description` LIKE '%" . addslashes($keyword) . "%' OR `Job`.`company_name` LIKE '%" . addslashes($keyword) . "%' ) ";
            $keyword = str_replace('\_', '_', $keyword);
            $this->set('keyword', $keyword);
        }


        if (isset($searchkey) && !empty($searchkey)) {

            $searchkey_arr = explode("-", $searchkey);

            foreach ($searchkey_arr as $id) {
                $condition_search[] = "(FIND_IN_SET('" . $id . "',Job.skill) OR FIND_IN_SET('" . $id . "',Job.designation) )";
            }
            $condition[] = array('OR' => $condition_search);
            $urlSeparator[] = 'searchkey:' . $searchkey;
            $separator[] = 'searchkey:' . $searchkey;
            $this->set('searchkey', $searchkey);
        }
        // pr($condition); exit; 

        if (isset($category_id) && $category_id != '') {
            $this->set('topcate', $category_id);
            $separator[] = 'category_id:' . $category_id;
            $category_idCondtionArray = explode('-', $category_id);

            if (isset($subcategory_id) && $subcategory_id != '') {
                $this->set('subcate', $subcategory_id);
                $subcategory_idCondtionArray = explode('-', $subcategory_id);
                foreach ($subcategory_idCondtionArray as $subMain) {
                    $subMainVal = $this->Category->field('parent_id', array('Category.id' => $subMain));
                    if (($key = array_search($subMainVal, $category_idCondtionArray)) !== false) {
                        unset($category_idCondtionArray[$key]);
                    }
                    //   pr($category_idCondtionArray);
                }
                // pr($category_idCondtionArray);
                if ($category_idCondtionArray) {
                    $subcategory_idCondtion = implode(',', $subcategory_idCondtionArray);
                    $separator[] = 'subcategory_id:' . $subcategory_id;

                    $category_idCondtion = implode(',', $category_idCondtionArray);
                    $condition[] = " (Job.category_id IN ($category_idCondtion) OR Job.subcategory_id IN ($subcategory_idCondtion ) )";
                } else {
                    $subcategory_idCondtion = implode(',', $subcategory_idCondtionArray);
                    $condition[] = " (Job.subcategory_id IN ($subcategory_idCondtion ))";
                    $separator[] = 'subcategory_id:' . $subcategory_id;
                }
            } else {
                $category_idCondtion = implode(',', $category_idCondtionArray);
                $condition[] = " (Job.category_id IN ($category_idCondtion))";
            }
        }


        if (!empty($skill)) {


            $skill_arr = explode(",", $skill);

//            foreach ($skill_arr as $skil) {
//                $condition_skill[] = "(FIND_IN_SET('" . $skil . "',Job.skill))";
//            }
            $keyword = array();
            foreach ($skill_arr as $skillhave) {
                $cbd[] = '(Skill.name = "' . $skillhave . '")';

                $skillDetail = $this->Skill->find('first', array('conditions' => $cbd));

                if ($skillDetail) {

                    $idshave = $skillDetail['Skill']['id'];
                    $condition_skill[] = "(FIND_IN_SET('" . $idshave . "',Job.skill))";
                    //$condition_skill[] = '(Job.skill LIKE "%'.$idshave.'%")';
                    // $condition_skill[] = '(Job.skill = "'.$idshave.'")';
                } else {
                    if ($skillhave != '') {
                        $condition_skill[] = "(Skill.name LIKE '%" . addslashes($skillhave) . "%')";
                    }
                }
            }

            $condition[] = array('OR' => $condition_skill);
            $urlSeparator[] = 'skill:' . $skill;
            $separator[] = 'skill:' . $skill;
        }

        if (!empty($location)) {

            $location_arr = explode("-", $location);

            foreach ($location_arr as $loc) {
                $condition_location[] = "(FIND_IN_SET('" . $loc . "',Job.location))";
            }
            $condition[] = array('OR' => $condition_location);
            $urlSeparator[] = 'location:' . $location;
            $separator[] = 'location:' . $location;
            $this->set('location', $location);
        }

        if (!empty($worktype)) {

            $worktype_arr = explode("-", $worktype);

            foreach ($worktype_arr as $work) {
                $condition_worktype[] = "(FIND_IN_SET('" . $work . "',Job.work_type))";
            }
            $condition[] = array('OR' => $condition_worktype);
            $urlSeparator[] = 'work_type:' . $worktype;
            $separator[] = 'work_type:' . $worktype;
            $this->set('worktype', $worktype);
        }



//        if (isset($designation) && $designation != '') {
//            $separator[] = 'designation:' . urlencode($designation);
//            $designation = str_replace('_', '\_', $designation);
//            $condition[] = " (`Job`.`designation` = $designation) ";
//            $designation = str_replace('\_', '_', $designation);
//            $this->set('designation', $designation);
//        }


        if (!empty($designation)) {
            $designation_arr = explode(",", $designation);
            foreach ($designation_arr as $des) {
                $cbsd[] = '(Designation.name = "' . $des . '")';
                $dDetail = $this->Designation->find('first', array('conditions' => $cbsd));
                if ($dDetail) {
                    $idshave = $dDetail['Designation']['id'];
                    $condition_designation[] = '(Job.designation LIKE "%' . $idshave . '%")';
                } else {
                    if ($des != '') {
                        //$condition_designation[] = "(FIND_IN_SET('" . $des . "',Job.designation))";
                        $condition_designation[] = "(Designation.name LIKE '%" . addslashes($des) . "%')";
                    }
                }
            }
            $condition[] = array('OR' => $condition_designation);
            $urlSeparator[] = 'designation:' . $designation;
            $separator[] = 'designation:' . $designation;
        }
        /* if (isset($min_salary) && $min_salary != '') { //echo 'vdsdv';exit;
          $separator[] = 'min_salary:' . urlencode($min_salary);
          $min_salary = str_replace('_', '\_', $min_salary);
          $condition[] = " ( Job.min_salary >= '$min_salary' ) ";
          $min_salary = str_replace('\_', '_', $min_salary);
          }
          if (isset($max_salary) && $max_salary != '') {
          $separator[] = 'max_salary:' . urlencode($max_salary);
          $max_salary = str_replace('_', '\_', $max_salary);
          $condition[] = " ( Job.max_salary <= '$max_salary' ) ";
          $max_salary = str_replace('\_', '_', $max_salary);
          } */

        if ((isset($salary) && $salary != '')) {
            $separator[] = 'salary:' . urlencode($salary);

            $salary = str_replace('_', '\_', $salary);
            $expsalary = explode('-', $salary);
            $min_salary = $expsalary[0];
            $max_salary = $expsalary[1];

            $condition[] = " ((Job.min_salary >= $min_salary AND Job.max_salary <= $min_salary) OR (Job.min_salary >= $min_salary AND Job.max_salary <= $max_salary) OR (Job.min_salary = $max_salary ) OR (Job.max_salary = $min_salary )) ";

            $salary = str_replace('\_', '_', $salary);

            $this->set('salary', $salary);
            // $this->set('max_salary', $max_salary);
        }



        if ((isset($min_exp) && $min_exp != '') && (isset($max_exp) && $max_exp != '')) {
            $separator[] = 'min_exp:' . urlencode($min_exp);
            $separator[] = 'max_exp:' . urlencode($max_exp);
            $min_exp = str_replace('_', '\_', $min_exp);

            if ($min_exp == $max_exp) {
                $condition[] = " ((Job.min_exp <= $min_exp AND Job.max_exp >= $min_exp)) ";
            } else {
                // $condition[] = " ((Job.min_exp >= $min_exp AND Job.max_exp <= $min_exp) OR (Job.min_exp >= $min_exp AND Job.max_exp <= $max_exp) OR (Job.min_exp = $max_exp ) OR (Job.max_exp = $min_exp )) ";
                $condition[] = " ((Job.min_exp >= $min_exp AND Job.max_exp <= $max_exp)) ";
            }
            $min_exp = str_replace('\_', '_', $min_exp);

            $this->set('min_exp', $min_exp);
            $this->set('max_exp', $max_exp);
        }

        $sort = '';
        if (isset($order) && $order != '') {

            $ord = explode(" ", $order);

            $separator[] = 'sort:' . urlencode($ord[0]);
            $sort = str_replace('_', '\_', $order);

            $separator[] = 'order:' . urlencode($ord[1]);
            $order = str_replace('_', '\_', $order);
            $this->set('order', $order);
        }

        //2017-02-18
        $condition[] = array('(Job.category_id != 0 AND Job.title LIKE "' . $alpha . '%")');
        // $condition[] = '(User.first_name LIKE "'.$alpha.'%" AND User.user_type = "candidate" AND User.status = 1)';
        //$order = 'Job.typeAlter Asc, Job.created Desc';
        //print_r($condition); exit;
        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['Job'] = array('conditions' => $condition, 'limit' => '100', 'page' => '1', 'order' => $order);
        //pr($this->paginate['Job']);exit;
        $this->set('jobs', $this->paginate('Job'));

        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'jobs';
            $this->render('nameby');
        }
    }

    public function viewcompanies($alpha = null) {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Companies profiles having starting alphabetically', true) . " -" . $alpha);
        $this->set('jobs_list', 'active');
        $this->set('slug', $alpha);
        $this->set('alpha', $alpha);

        if ($alpha != "") {

            $userId = $this->Session->read('user_id');
            $condition[] = '(User.company_name LIKE "' . $alpha . '%" AND User.user_type = "recruiter" AND User.status = 1)';

            $separator = array();
            $urlSeparator = array();

            $order = 'User.company_name ASC';

            $separator = implode("/", $separator);
            $urlSeparator = implode("/", $urlSeparator);

            $this->set('separator', $separator);
            $this->set('urlSeparator', $urlSeparator);
            $this->paginate['User'] = array('conditions' => $condition, 'limit' => '100', 'page' => '1', 'order' => $order);
            //pr($this->paginate['Job']);exit;
            $this->set('candidates', $this->paginate('User'));

            if ($this->request->is('ajax')) {
                $this->layout = '';
                $this->viewPath = 'Elements' . DS . 'candidates';
                $this->render('peoples');
            }
        } else {
            $this->redirect('/homes/error');
        }
    }

    public function jobsof($company) {


        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";

        $this->set('jobs_list', 'active');
        $this->set('alpha', $company);
        $this->set('slug', $company);

        $userId = $this->Session->read('user_id');

        $compay_id = $this->User->findBySlug($company);

        // pr($compay_id);
        $this->set('company_name', $compay_id['User']['company_name']);
        $this->set('compay_id', $compay_id);
        $this->set('title_for_layout', $title_for_pages . "View Jobs of  -  " . $compay_id['User']['company_name']);
        $condition = array('Job.status' => 1, 'Job.payment_status' => 2, 'Job.user_id' => $compay_id['User']['id']);

        $separator = array();
        $urlSeparator = array();

        $order = '';

        if (!empty($this->data)) {
            //pr($this->data); exit;
            if (isset($this->data['Job']['created']) && $this->data['Job']['created'] != '') {
                $order = 'Job.' . $this->data['Job']['created'];
            }

            if (isset($this->data['Job']['keyword']) && $this->data['Job']['keyword'] != '') {
                $keyword = trim($this->data['Job']['keyword']);
            }

            if (isset($this->data['Job']['category_id']) && !empty($this->data['Job']['category_id']) && count($this->data['Job']['category_id']) > 0) {
                if (is_array($this->data['Job']['category_id'])) {
                    $category_id = implode('-', $this->data['Job']['category_id']);
                } else {
                    $category_id = $this->data['Job']['category_id'];
                }
            }

            if (isset($this->data['Job']['searchkey']) && $this->data['Job']['searchkey'] != '') {
                if (is_array($this->data['Job']['searchkey'])) {
                    $searchkey = implode('-', $this->data['Job']['searchkey']);
                } else {
                    $searchkey = $this->data['Job']['searchkey'];
                }
            }

            if (isset($this->data['Job']['subcategory_id']) && !empty($this->data['Job']['subcategory_id']) && count($this->data['Job']['subcategory_id']) > 0) {
                if (is_array($this->data['Job']['subcategory_id'])) {
                    $subcategory_id = implode('-', $this->data['Job']['subcategory_id']);
                } else {
                    $subcategory_id = $this->data['Job']['subcategory_id'];
                }
            }

            if (isset($this->data['Job']['location']) && !empty($this->data['Job']['location']) && count($this->data['Job']['location']) > 0) {
                if (is_array($this->data['Job']['location'])) {
                    $location = implode('-', $this->data['Job']['location']);
                } else {
                    $location = $this->data['Job']['location'];
                }
            }

            if (isset($this->data['Job']['work_type']) && !empty($this->data['Job']['work_type']) && count($this->data['Job']['work_type']) > 0) {
                if (is_array($this->data['Job']['work_type'])) {
                    $worktype = implode('-', $this->data['Job']['work_type']);
                } else {
                    $worktype = $this->data['Job']['work_type'];
                }
            }


            if (!empty($this->data['Job']['skill'])) {
                //$skill = implode(",", $this->data['Job']['skill']);
                $skill = $this->data['Job']['skill'];
                //$skill = addslashes($skill);
                $this->set('skill', $this->data['Job']['skill']);
            }

            if (!empty($this->data['Job']['designation'])) {

                $designation = addslashes($this->data['Job']['designation']);
                $this->set('designation', $this->data['Job']['designation']);
            }
            if (isset($this->data['Job']['salary']) && $this->data['Job']['salary'] != '') {
                $salary = trim($this->data['Job']['salary']);
            }
//            if (isset($this->data['Job']['max_salary']) && $this->data['Job']['max_salary'] != '') {
//                $max_salary = trim($this->data['Job']['max_salary']);
//            }

            if (isset($this->data['Job']['exp']) && $this->data['Job']['exp'] != '') {
                $exp = $this->data['Job']['exp'];
                $expArray = explode('-', $exp);
                $min_exp = $expArray[0];
                $max_exp = $expArray[1];
            }

            if (isset($this->data['Job']['min_exp']) && $this->data['Job']['min_exp'] != '') {
                $min_exp = trim($this->data['Job']['min_exp']);
            }
            if (isset($this->data['Job']['max_exp']) && $this->data['Job']['max_exp'] != '') {
                $max_exp = trim($this->data['Job']['max_exp']);
            }
        } elseif (!empty($this->params)) {

            if (isset($this->params['named']['keyword']) && $this->params['named']['keyword'] != '') {
                $keyword = urldecode(trim($this->params['named']['keyword']));
            }
            if (isset($this->params['named']['category_id']) && $this->params['named']['category_id'] != '') {
                $category_id = trim($this->params['named']['category_id']);
            }
            if (isset($this->params['named']['subcategory_id']) && $this->params['named']['subcategory_id'] != '') {
                $subcategory_id = trim($this->params['named']['subcategory_id']);
            }

            if (isset($this->params['named']['location']) && $this->params['named']['location'] != '') {
                $location = urldecode(trim($this->params['named']['location']));
            }

            if (isset($this->params['named']['work_type']) && $this->params['named']['work_type'] != '') {
                $worktype = urldecode(trim($this->params['named']['work_type']));
            }

            if (isset($this->params['named']['skill']) && $this->params['named']['skill'] != '') {
                $skill = trim($this->params['named']['skill']);
                $skill = addslashes($skill);
                $this->set('skill', $skill);
            }

            if (isset($this->params['named']['designation']) && $this->params['named']['designation'] != '') {
                $designation = trim($this->params['named']['designation']);
                $designation = addslashes($designation);
                $this->set('designation', $designation);
            }
            if (isset($this->params['named']['salary']) && $this->params['named']['salary'] != '') {
                $salary = urldecode(trim($this->params['named']['salary']));
            }
            if (isset($this->params['named']['max_salary']) && $this->params['named']['max_salary'] != '') {
                $max_salary = urldecode(trim($this->params['named']['max_salary']));
            }
            if (isset($this->params['named']['min_exp']) && $this->params['named']['min_exp'] != '') {
                $min_exp = urldecode(trim($this->params['named']['min_exp']));
            }
            if (isset($this->params['named']['max_exp']) && $this->params['named']['max_exp'] != '') {
                $max_exp = urldecode(trim($this->params['named']['max_exp']));
            }

            if (isset($this->params['named']['searchkey']) && $this->params['named']['searchkey'] != '') {
                $searchkey = urldecode(trim($this->params['named']['searchkey']));
            }

//            if (isset($this->params['named']['order']) && $this->params['named']['order'] != '') {
//                $order = urldecode(trim($this->params['named']['order']));
//            }
        }



        $condition[] = array('(Job.category_id != 0)');
        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['Job'] = array('conditions' => $condition, 'limit' => '100', 'page' => '1', 'order' => $order);
        //pr($this->paginate['Job']);exit;
        $this->set('jobs', $this->paginate('Job'));

        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'jobs';
            $this->render('jobsof');
        }
    }

    public function setLocationInSession() {
        $this->layout = "";
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ipAddr = trim($ip);
                }
            }
        }
        if (empty($ipAddr)) {
            $ipAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unknown';
        }

        $country = @unserialize(file_get_contents("http://ip-api.com/php/" . $ipAddr));
        //print_r($country);
        //    $country_name = $country['country']; //code/abbr/name
        //    $state_name = $country['regionName']; //code/abbr/name
        //echo 'http://www.geoplugin.net/php.gp?ip='.$ipAddr; exit;
        //  $country = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ipAddr));
        //    print_r($country);
        // pr($country);
//         $_SESSION['countryName'] = $country['geoplugin_countryName']; //code/abbr/name
//         $_SESSION['regionName'] = $country['geoplugin_regionName']; //code/abbr/name
        $_SESSION['countryName'] = $country['country']; //code/abbr/name
        $_SESSION['regionName'] = $country['city']; //code/abbr/name
//         $_SESSION['countryName'] = "Delhi"; //code/abbr/name
//         $_SESSION['regionName'] = "Delhi"; //code/abbr/name
        //echo"-->";  print_r($_SESSION);

        if ($_SESSION['countryName'] != "" && $_SESSION['regionName'] != "") {
            echo "1";
            $locationid = ClassRegistry::init('Location')->find('first', array('conditions' => array('OR' => array('Location.name' => $_SESSION['countryName'], 'Location.name' => $_SESSION['regionName']))));
            if ($locationid) {
                $_SESSION['locationid'] = $locationid['Location']['id'];
                $condition = array('Job.status' => 1, 'Job.payment_status' => 2);

                $condition[] = '(FIND_IN_SET("' . $locationid['Location']['id'] . '",Job.location))';
                $condition[] = array("(Job.category_id != 0)");
                //    echo "<pre>"; print_r($condition);
                $jobCount = ClassRegistry::init('Job')->find('count', array('conditions' => $condition));
                if ($jobCount > 0 && !isset($_SESSION['viewed'])) {

                    $_SESSION['cokkiecount'] = $jobCount;
                    $_SESSION['viewed'] = 0;
                }
            }
            exit;
        } else {
            echo "0";
            exit;
        }
        exit;
    }

    public function countJob() {
        $this->layout = "";
        if (!isset($_SESSION['never'])) {
            if (isset($_SESSION['locationid']) && $_SESSION['locationid'] > 0) {
                $condition = array('Job.status' => 1, 'Job.payment_status' => 2);

                $condition[] = '(FIND_IN_SET("' . $_SESSION['locationid'] . '",Job.location))';
                $condition[] = "(Job.category_id != 0)";
                $jobCount = ClassRegistry::init('Job')->find('count', array('conditions' => $condition));
                $cokkiecount = 0;
                // pr($_SESSION);
                if (isset($_SESSION['cokkiecount'])) {
                    $cokkiecount = $_SESSION['cokkiecount'];
                    if ($jobCount > $cokkiecount) {
                        if (isset($_SESSION['viewed'])) {
                            unset($_SESSION['viewed']);
                        }

                        echo json_encode(array('jobcount' => $jobCount, 'cokkiecount' => $cokkiecount, 'viewed' => 0));
                        exit;
                    } else if ($_SESSION['viewed'] == 0) {

                        echo json_encode(array('jobcount' => $jobCount, 'cokkiecount' => $cokkiecount, 'viewed' => $_SESSION['viewed']));
                        exit;
                    } else {
                        echo json_encode(array('jobcount' => 0, 'cokkiecount' => 0));
                        exit;
                    }
                } else {
                    echo json_encode(array('jobcount' => 0, 'cokkiecount' => 1));
                    exit;
                }
            } else {
                echo json_encode(array('jobcount' => 0, 'cokkiecount' => 0));
                exit;
            }
        } else {
            echo json_encode(array('jobcount' => 0, 'cokkiecount' => 0));
            exit;
        }
    }

    public function never() {
        $this->layout = "";
        echo $_SESSION['never'] = 1;
        exit;
    }

    public function getalert($emailaddress) {
        $this->layout = "";

        if ($this->data) {
            $keyword = "";
            $category_id = "";
            $searchkey = "";
            $subcategory_id = "";
            $location = "";
            $worktype = "";
            $skill = "";
            $designation = "";
            $salary = "";
            $exp = "";

            if (isset($this->data['Job']['keyword']) && $this->data['Job']['keyword'] != '') {
                $keyword = trim($this->data['Job']['keyword']);
            }

            if (isset($this->data['Job']['category_id']) && !empty($this->data['Job']['category_id']) && count($this->data['Job']['category_id']) > 0) {
                if (is_array($this->data['Job']['category_id'])) {
                    $category_id = implode('-', $this->data['Job']['category_id']);
                } else {
                    $category_id = $this->data['Job']['category_id'];
                }
            }

            if (isset($this->data['Job']['searchkey']) && $this->data['Job']['searchkey'] != '') {
                if (is_array($this->data['Job']['searchkey'])) {
                    $searchkey = implode('-', $this->data['Job']['searchkey']);
                } else {
                    $searchkey = $this->data['Job']['searchkey'];
                }
            }

            if (isset($this->data['Job']['subcategory_id']) && !empty($this->data['Job']['subcategory_id']) && count($this->data['Job']['subcategory_id']) > 0) {
                if (is_array($this->data['Job']['subcategory_id'])) {
                    $subcategory_id = implode('-', $this->data['Job']['subcategory_id']);
                } else {
                    $subcategory_id = $this->data['Job']['subcategory_id'];
                }
            }

            if (isset($this->data['Job']['location']) && !empty($this->data['Job']['location']) && count($this->data['Job']['location']) > 0) {
                if (is_array($this->data['Job']['location'])) {
                    $location = implode('-', $this->data['Job']['location']);
                } else {
                    $location = $this->data['Job']['location'];
                }
            }

            if (isset($this->data['Job']['work_type']) && !empty($this->data['Job']['work_type']) && count($this->data['Job']['work_type']) > 0) {
                if (is_array($this->data['Job']['work_type'])) {
                    $worktype = implode('-', $this->data['Job']['work_type']);
                } else {
                    $worktype = $this->data['Job']['work_type'];
                }
            }


            if (!empty($this->data['Job']['skill'])) {
                //$skill = implode(",", $this->data['Job']['skill']);
                $skill = $this->data['Job']['skill'];
            }

            if (!empty($this->data['Job']['designation'])) {

                $designation = addslashes($this->data['Job']['designation']);
            }
            if (isset($this->data['Job']['salary']) && $this->data['Job']['salary'] != '') {
                $salary = trim($this->data['Job']['salary']);
            }

            if (isset($this->data['Job']['exp']) && $this->data['Job']['exp'] != '') {
                $exp = $this->data['Job']['exp'];
            }


            if (isset($emailaddress) && $emailaddress != '') {
                $condition[] = " (`AutoAlert`.`email_address` LIKE '%" . addslashes($emailaddress) . "%' ) ";
            }
            if (isset($keyword) && $keyword != '') {
                $condition[] = " (`AutoAlert`.`keyword` LIKE '%" . addslashes($keyword) . "%' ) ";
            }


            if (isset($searchkey) && !empty($searchkey)) {

                $condition[] = "(AutoAlert.keyword LIKE '%" . addslashes($searchkey) . "%')";
            }
            // pr($condition); exit; 

            if (isset($category_id) && $category_id != '') {
                $condition[] = "(AutoAlert.category_id = '" . addslashes($category_id) . "')";
            }

            if (!empty($skill)) {

                $condition[] = "(AutoAlert.skill = '" . addslashes($skill) . "')";
            }

            if (!empty($location)) {

                $condition[] = "(AutoAlert.location = '" . addslashes($location) . "')";
            }

            if (!empty($worktype)) {

                $condition[] = "(AutoAlert.work_type = '" . addslashes($worktype) . "')";
            }

            if (!empty($designation)) {

                $condition[] = "(AutoAlert.designation = '" . addslashes($designation) . "')";
            }


            if ((isset($salary) && $salary != '')) {
                $condition[] = " (AutoAlert.salary = $salary ) ";
            }

            if ((isset($exp) && $exp != '')) {

                $condition[] = " ((AutoAlert.exp = $exp) ";
            }

            $ifexist = $this->AutoAlert->find('first', array('conditions' => $condition));
            if ($ifexist) {
                echo json_encode(array('success' => 0, 'message' => $emailaddress . ' ' . __d('controller', 'already have subscribed job alert for this search', true)));
                exit;
            } else {


                $this->request->data['AutoAlert']['keyword'] = $this->data['Job']['keyword'];
                if (isset($this->data['Job']['subcategory_id'])) {
                    $this->request->data['AutoAlert']['subcategory_id'] = $this->data['Job']['subcategory_id'];
                }
                $this->request->data['AutoAlert']['category_id'] = $this->data['Job']['category_id'];
                $this->request->data['AutoAlert']['location'] = $this->data['Job']['location'];
                $this->request->data['AutoAlert']['exp'] = $this->data['Job']['exp'];
                $this->request->data['AutoAlert']['salary'] = $this->data['Job']['salary'];
                $this->request->data['AutoAlert']['skill'] = $this->data['Job']['skill'];
                $this->request->data['AutoAlert']['designation'] = $this->data['Job']['designation'];
//            $this->request->data['AutoAlert']['total_work_type'] = $this->data['Job']['total_work_type'];
                if (is_array($this->data['Job']['work_type'])) {
                    $this->request->data['AutoAlert']['work_type'] = implode(',', $this->data['Job']['work_type']);
                } else {
                    $this->request->data['AutoAlert']['work_type'] = $this->data['Job']['work_type'];
                }

                $this->request->data['AutoAlert']['email_address'] = trim($emailaddress);
                $this->AutoAlert->save($this->data);
                $autolist = $this->AutoAlert->id;
                $site_title = $this->getSiteConstant('title');
                $mail_from = $this->getMailConstant('from');

                $this->Email->to = trim($emailaddress);
                $currentYear = date('Y', time());
                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                $emData = $this->Emailtemplate->getSubjectLang();
                $subjectField = $emData['subject'];
                $templateField = $emData['template'];

                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='38'"));
                $LINK = '<a href="' . HTTP_PATH . '/users/activeAlert/' . $autolist . "/" . md5($autolist) . "/" . urlencode($emailaddress) . '">Activate your job alert</a>';
                $SEARCH = "";
                if ($this->data['Job']['category_id'] != "") {
                    $catName = $this->Category->findById($this->data['Job']['category_id']);
                    if ($catName) {
                        $SEARCH .= "<p><stong>Category</stong>: " . $catName['Category']['name'] . "</p>";
                    }
                }
                if ($this->data['Job']['location'] != "") {
                    $LocName = $this->Location->findById($this->data['Job']['location']);
                    if ($LocName) {
                        $SEARCH .= "<p><stong>Location</stong>: " . $LocName['Location']['name'] . "</p>";
                    }
                }
                if ($this->data['Job']['exp'] != "") {

                    $SEARCH .= "<p><stong>Experience</stong>: " . $this->data['Job']['exp'] . " Year</p>";
                }
                if ($this->data['Job']['salary'] != "") {

                    $SEARCH .= "<p><stong>Salary</stong>: " . $this->data['Job']['salary'] . " Lac</p>";
                }
                if ($this->data['Job']['skill'] != "") {
                    $SEARCH .= "<p><stong>Skill</stong>: " . $this->data['Job']['skill'] . "</p>";
                }
                if ($this->data['Job']['designation'] != "") {
                    $SEARCH .= "<p><stong>Designation</stong>: " . $this->data['Job']['designation'] . "</p>";
                }
                if (isset($this->data['Job']['work_type'])) {
                    if (is_array($this->data['Job']['work_type']) != "") {
                        global $worktype;
                        $wo = "";

                        foreach ($this->data['Job']['work_type'] as $node) {
                            $wo[] = $worktype[$node];
                        }
                        $SEARCH .= "<p><stong>Work Type</stong>: " . implode(', ', $wo) . "</p>";
                    } else if ($this->data['Job']['work_type'] != "") {
                        global $worktype;
                        $SEARCH .= "<p><stong>Work Type</stong>: " . $worktype[$this->data['Job']['work_type']] . "</p>";
                    }
                }

//        echo $SEARCH; exit;

                $toSubArray = array('[!email_address!]', '[!SEARCH!]', '[!LINK!]', '[!SITE_TITLE!]');
                $fromSubArray = array($emailaddress, $SEARCH, $LINK, $site_title);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                $this->Email->subject = $subjectToSend;
                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";
                $toRepArray = array('[!email_address!]', '[!SEARCH!]', '[!LINK!]', '[!SITE_TITLE!]');
                $fromRepArray = array(trim($emailaddress), $SEARCH, $LINK, $site_title);
                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);

                $this->Email->layout = 'default';
                $this->set('messageToSend', $messageToSend);
                $this->Email->template = 'email_template';
                //$this->Email->attachments = array(UPLOAD_FULL_INVOICE_IMAGE_PATH . $jobInfo["Job"]["invoice_inumber"] . '.pdf');
                $this->Email->sendAs = 'html';
                $this->Email->send();
//                 pr($this->Email->send());
                $this->Email->reset();

                echo json_encode(array('success' => 1, 'message' => __d('controller', 'Please check your email we have sent a confirmation message', true) . ".<br><p>" . __d('controller', 'Click on the link in this email to start receiving your job alerts. If you dont see it within a few minutes, please check your spam folder.', true) . "</p>"));
                exit;
            }
        }
        exit;
    }

    function activeAlert($id = null, $md5id = null, $email = null) {
        if (md5($id) == $md5id) {
            $userCheck = $this->AutoAlert->find('first', array('conditions' => array('AutoAlert.email_address' => $email, 'AutoAlert.id' => $id)));
            if (!$this->Session->read('user_id') && $userCheck['AutoAlert']['status'] == 0 && !empty($userCheck)) {
                $cnd = array("AutoAlert.id" => $id);
                $this->AutoAlert->updateAll(array('AutoAlert.status' => "'1'"), $cnd);

                $this->Session->write('success_msg', __d('controller', 'Your job alert has been activated successfully.', true));
                $this->redirect('/users/login');
            } else {
                if ($this->Session->read('user_id')) {
                    $this->Session->write('success_msg', __d('controller', 'Your job alert has been activated successfully.', true));
                } else {
                    $this->Session->write('error_msg', __d('controller', 'You have already used this link!', true));
                }
                $this->redirect('/users/login');
            }
        }
    }

    /*     * *********************** App function ******************** */

    public function apps_getvisitortoken() {

        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 0);
//       print_r($tokenData); die('f');
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $randam = substr(str_shuffle($chars), 0, 10);
        $payLoad = array(
            "code" => $randam,
            "time" => time()
        );
        $token = $this->setGuestToken($payLoad);
        $data = array();
        $data['token'] = $token;
        echo $this->successOutputResult('Visitor Token', json_encode($data));
        exit;
    }

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

        $userCheck = $this->User->find("first", array("conditions" => array("User.email_address" => $email_address, "User.user_type" => 'recruiter')));
        if (is_array($userCheck) && !empty($userCheck) && crypt($password, $userCheck['User']['password']) == $userCheck['User']['password']) {
            if ($userCheck['User']['status'] == 1 && $userCheck['User']['activation_status'] == 1) {
                if ($type == 'recruiter') {
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

        $data['token'] = $userCheck['User']['token'];
        return $data;
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
            $site_title = $this->getSiteConstant('title');
            $site_url = $this->getSiteConstant('url');

            $passwordPlain = $this->data["User"]["password"];
            $salt = uniqid(mt_rand(), true);
            $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
            $this->request->data['User']['password'] = $new_password;
            $this->request->data['User']['company_name'] = trim($this->data['User']['company_name']);
            $this->request->data['User']['first_name'] = trim($this->data['User']['first_name']);
            $this->request->data['User']['last_name'] = trim($this->data['User']['last_name']);
            $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim(strtolower($this->data['User']['first_name'])) . ' ' . trim(strtolower($this->data['User']['last_name'])), 'User', 'slug');
            $this->request->data['User']['country_id'] = 1;
            $this->request->data['User']['activation_status'] = 0;
            $this->request->data['User']['status'] = 0;
            $this->request->data['User']['user_type'] = 'recruiter';
            if ($this->User->save($this->data)) {
                $userId = $this->User->id;
                $userDetail = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
                $email = $this->data["User"]["email_address"];
                $username = $this->data["User"]["first_name"];
                $link = HTTP_PATH . "/users/confirmation/" . $userId . "/" . md5($userId) . "/" . urlencode($email);

                $this->Email->to = $email;

                $emData = $this->Emailtemplate->getSubjectLang();
                $subjectField = $emData['subject'];
                $templateField = $emData['template'];

                $currentYear = date('Y', time());

                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='19'"));
                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                $toSubArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                $fromSubArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                $this->Email->subject = $subjectToSend;
                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";

                $toRepArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                $fromRepArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
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

    public function apps_socialLogin() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 2);
        $user_id = $tokenData['user_id'];
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);

        $this->request->data["User"] = $userData;
        $emailAddress = $userData['email_address'];
        $device_type = $userData['device_type'];
        $device_id = $userData['device_id'];

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');

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


                $i = 1;
                $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                $password = '';
                while ($i <= 10) {
                    $num = rand() % 33;
                    $tmp = substr($chars, $num, 1);
                    $password = $password . $tmp;
                    $i++;
                }

                $passwordPlain = $password;
                $salt = uniqid(mt_rand(), true);
                $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
                $this->request->data['User']['password'] = $new_password;
                $this->request->data['User']['company_name'] = trim($this->data['User']['company_name']);
                $this->request->data['User']['first_name'] = trim($this->data['User']['first_name']);
                $this->request->data['User']['last_name'] = trim($this->data['User']['last_name']);
                $this->request->data['User']['facebook_user_id'] = trim($this->data['User']['facebook_user_id']);
                $this->request->data['User']['device_type'] = trim($this->data['User']['device_type']);
                $this->request->data['User']['device_id'] = trim($this->data['User']['device_id']);
                $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim(strtolower($this->data['User']['first_name'])) . ' ' . trim(strtolower($this->data['User']['last_name'])), 'User', 'slug');
                $this->request->data['User']['country_id'] = 1;
                $this->request->data['User']['activation_status'] = 0;
                $this->request->data['User']['status'] = 0;
                $this->request->data['User']['user_type'] = 'recruiter';
                if ($this->User->save($this->data)) {
                    $userId = $this->User->id;
                    $userDetail = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
                    $email = $this->data["User"]["email_address"];
                    $username = $this->data["User"]["first_name"];
                    $link = HTTP_PATH . "/users/confirmation/" . $userId . "/" . md5($userId) . "/" . urlencode($email);

                    $this->Email->to = $email;

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];

                    $currentYear = date('Y', time());

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='19'"));
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $toSubArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                    $fromSubArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                    $fromRepArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
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

                $i = 1;
                $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                $password = '';
                while ($i <= 10) {
                    $num = rand() % 33;
                    $tmp = substr($chars, $num, 1);
                    $password = $password . $tmp;
                    $i++;
                }

                $passwordPlain = $password;
                $salt = uniqid(mt_rand(), true);
                $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
                $this->request->data['User']['password'] = $new_password;
                $this->request->data['User']['company_name'] = trim($this->data['User']['company_name']);
                $this->request->data['User']['first_name'] = trim($this->data['User']['first_name']);
                $this->request->data['User']['last_name'] = trim($this->data['User']['last_name']);
                $this->request->data['User']['device_type'] = trim($this->data['User']['device_type']);
                $this->request->data['User']['device_id'] = trim($this->data['User']['device_id']);
                $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim(strtolower($this->data['User']['first_name'])) . ' ' . trim(strtolower($this->data['User']['last_name'])), 'User', 'slug');
                $this->request->data['User']['country_id'] = 1;
                $this->request->data['User']['activation_status'] = 0;
                $this->request->data['User']['status'] = 0;
                $this->request->data['User']['user_type'] = 'recruiter';

                if ($this->User->save($this->data)) {
                    $userId = $this->User->id;
                    $userDetail = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
                    $email = $this->data["User"]["email_address"];
                    $username = $this->data["User"]["first_name"];
                    $link = HTTP_PATH . "/users/confirmation/" . $userId . "/" . md5($userId) . "/" . urlencode($email);

                    $this->Email->to = $email;

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];

                    $currentYear = date('Y', time());

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='19'"));
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $toSubArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                    $fromSubArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                    $fromRepArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
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

                $i = 1;
                $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                $password = '';
                while ($i <= 10) {
                    $num = rand() % 33;
                    $tmp = substr($chars, $num, 1);
                    $password = $password . $tmp;
                    $i++;
                }

                $passwordPlain = $password;
                $salt = uniqid(mt_rand(), true);
                $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
                $this->request->data['User']['password'] = $new_password;
                $this->request->data['User']['company_name'] = trim($this->data['User']['company_name']);
                $this->request->data['User']['first_name'] = trim($this->data['User']['first_name']);
                $this->request->data['User']['last_name'] = trim($this->data['User']['last_name']);
                $this->request->data['User']['linkedin_id'] = trim($this->data['User']['linkedin_id']);
                $this->request->data['User']['device_type'] = trim($this->data['User']['device_type']);
                $this->request->data['User']['device_id'] = trim($this->data['User']['device_id']);
                $this->request->data['User']['slug'] = $this->stringToSlugUnique(trim(strtolower($this->data['User']['first_name'])) . ' ' . trim(strtolower($this->data['User']['last_name'])), 'User', 'slug');
                $this->request->data['User']['country_id'] = 1;
                $this->request->data['User']['activation_status'] = 0;
                $this->request->data['User']['status'] = 0;
                $this->request->data['User']['user_type'] = 'recruiter';

                if ($this->User->save($this->data)) {
                    $userId = $this->User->id;
                    $userDetail = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
                    $email = $this->data["User"]["email_address"];
                    $username = $this->data["User"]["first_name"];
                    $link = HTTP_PATH . "/users/confirmation/" . $userId . "/" . md5($userId) . "/" . urlencode($email);

                    $this->Email->to = $email;

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];

                    $currentYear = date('Y', time());

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='19'"));
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $toSubArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                    $fromSubArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!link!]');
                    $fromRepArray = array($username, $this->data["User"]["company_name"], $email, $passwordPlain, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
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

    public function apps_forgotPassword() {
        $this->layout = '';
        $this->requestAuthentication('POST', 2);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $email_address = $userData['email_address'];
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
            echo $this->errorOutputResult(__d('controller', 'Your email is not registered with', true) . ' ' . $site_title . '. ' . __d('controller', 'Please enter correct email or register on', true) . '. ' . $site_title);
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

    public function apps_editprofile() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $this->request->data["User"] = $userData;

        $this->request->data['User']['profile_update_status'] = 1;
        $this->request->data['User']['id'] = $userId;
        if ($this->User->save($this->data)) {
            echo $this->successOutputMsg(__d('controller', 'Profile details updated successfully.', true));
        }
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
        global $extentions;
        $imageData = $_FILES['image'];
        $getextention = $this->PImage->getExtension($imageData['name']);
        $extention = strtolower($getextention);
        if (!empty($imageData["name"])) {
            if ($imageData['size'] > '2097152') {
                echo $this->errorOutputResult(__d('controller', 'Max file size upload is 2MB.', true));
                exit;
            } elseif (!in_array($extention, $extentions)) {
                echo $this->errorOutputResult(__d('controller', 'Not Valid Extention.', true));
                exit;
            }
        }


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
        chmod(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
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

    public function apps_viewprofile() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $userId = $tokenData['user_id'];
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

            $data['contact'] = $userCheck['User']['contact'];
            $data['address'] = $userCheck['User']['address'];
            $data['position'] = $userCheck['User']['position'];
            $data['url'] = $userCheck['User']['url'];

            $data['company_name'] = $userCheck['User']['company_name'];
            $data['company_contact'] = $userCheck['User']['company_contact'];
            $data['company_about'] = $userCheck['User']['company_about'];
            $data['profile_image'] = $userCheck['User']['profile_image'];

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
                            } else {
                                if ($fid == 1) {
                                    $joncnt = $fvalues[$fid];
                                } else
                                if ($fid == 2) {
                                    $ddd = $fvalues[$fid];
                                }
                            }
                        }
                    }
                }

                $planArray[$i]['job_post'] = $joncnt;
                $planArray[$i]['resume_download'] = $ddd;
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

    public function apps_getlocationlist() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 0);

        $lcoationList = $this->Location->find('all', array('conditions' => array('Location.status' => 1), 'order' => array('Location.name' => 'ASC')));
        $data = array();
        $catArray = array();
        $i = 0;
        foreach ($lcoationList as $key => $val) {
            $catArray[$i]['id'] = $val['Location']['id'];
            $catArray[$i]['name'] = $val['Location']['name'];
            $i++;
        }
        echo $this->successOutputResult('Location List', json_encode($catArray));
        exit;
    }

    public function apps_createJob() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $this->request->data["Job"] = $userData;
        $isAbleToJob = $this->Plan->checkPlanFeature($userId, 1);
        if ($isAbleToJob['status'] == 0) {
            echo $this->errorOutputResult($isAbleToJob['message']);
            exit;
        }
        global $extentions;
        $imageData = $_FILES['logo'];
        $getextention = $this->PImage->getExtension($imageData['name']);
        $extention = strtolower($getextention);
        if (!empty($imageData["name"])) {

            if ($imageData['size'] > '2097152') {
                echo $this->errorOutputResult(__d('controller', 'Max file size upload is 2MB.', true));
                exit;
            } elseif (!in_array($extention, $extentions)) {
                echo $this->errorOutputResult(__d('controller', 'Not Valid Extention.', true));
                exit;
            }
        }

        if (!empty($imageData["name"]) && $imageData['size'] > '0') {
            $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
            $toReplace = "-";
            $logo_name = str_replace($specialCharacters, $toReplace, $imageData['name']);
            $logo_name = str_replace("&", "and", $logo_name);
//           print_r($logo_name);die;
            if ($logo_name) {
                $cvArray = $imageData;
                $returnedUploadCVArray = $this->PImage->upload($cvArray, UPLOAD_JOB_LOGO_PATH);
                $this->request->data["Job"]["logo"] = $returnedUploadCVArray[0];
                chmod(UPLOAD_JOB_LOGO_PATH . $returnedUploadImageArray[0], 0755);
            }
        } else {
            $this->request->data["Job"]["logo"] = '';
        }

        $exp = explode('-', $this->data['Job']['exp']);
        $this->request->data["Job"]["min_exp"] = $exp[0];
        $this->request->data["Job"]["max_exp"] = $exp[1];

        $sallery = explode('-', $this->data['Job']['salary']);
        $this->request->data["Job"]["min_salary"] = $sallery[0];
        $this->request->data["Job"]["max_salary"] = $sallery[1];

        $this->request->data['Job']['user_id'] = $userId;
        $this->request->data['Job']['subcategory_id'] = '';
        $this->request->data['Job']['status'] = 1;
        $this->request->data['Job']['type'] = 'Gold';
        $this->request->data['Job']['exp_month'] = '0';
        $this->request->data['Job']['payment_status'] = 2;
        $this->request->data['Job']['hot_job_time'] = time() + 7 * 24 * 3600;

        $this->request->data['Job']['user_plan_id'] = $isAbleToJob['user_plan_id'];
        $this->request->data['Job']['amount_paid'] = '180.00';
        $slug = $this->stringToSlugUnique($this->data["Job"]["title"], 'Job');
        $this->request->data['Job']['slug'] = $slug;
        $this->request->data['Job']['job_number'] = 'JOB' . $userId . time();
        $this->request->data['Job']['expire_time'] = strtotime($this->data['Job']['expire_time']);
        if ($this->Job->save($this->data)) {
            $jobId = $this->Job->id;
            $users = $this->AlertLocation->getUsersToAlert($jobId);
            if (!empty($users)) {
                foreach ($users as $user) {
                    $this->AlertJob->create();
                    $this->request->data["AlertJob"]['job_id'] = $jobId;
                    $this->request->data["AlertJob"]['user_id'] = $user['User']['id'];
                    $this->request->data['AlertJob']['email_address'] = $user['User']['email_address'];
                    $this->request->data["AlertJob"]['status'] = 1;
                    $this->AlertJob->save($this->data);
                }
            }

            echo $this->successOutputMsg(__d('controller', 'Your job posted successfully.', true));
            exit;
        }
    }

    public function apps_getskillslist() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 0);
        $skillList = $this->Skill->find('all', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $skillArray = array();
        $i = 0;
        if ($skillList) {
            foreach ($skillList as $val) {
                $skillArray[$i]['id'] = $val['Skill']['id'];
                $skillArray[$i]['name'] = $val['Skill']['name'];
                $i++;
            }
        }

        echo $this->successOutputResult('Skills List', json_encode($skillArray));
        exit;
    }

    public function apps_getJobsList() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];

        $jobsList = $this->Job->find('all', array('conditions' => array('Job.user_id' => $userId), 'order' => array('Job.id' => 'DESC', 'Job.payment_status' => 'DESC')));
        $data = array();
        $jobsArray = array();
        $i = 0;
        global $worktype;
        $planInfo = $this->Plan->getcurrentplanEXP($userId, 1);
        foreach ($jobsList as $key => $val) {
            $alertjob_count = ClassRegistry::init('AlertJob')->find('count', array('conditions' => array('AlertJob.job_id' => $val['Job']['id'])));
            $all_job_count = ClassRegistry::init('JobApply')->getTotalCandidate($val['Job']['id']);
            $new_job_count = ClassRegistry::init('JobApply')->getNewCount($val['Job']['id']);
            $jobsArray[$i]['id'] = $val['Job']['id'];
            $jobsArray[$i]['user_id'] = $val['Job']['user_id'];
            $jobsArray[$i]['title'] = $val['Job']['title'];
            $jobsArray[$i]['status'] = $val['Job']['status'];
            $jobsArray[$i]['alertjob_count'] = $alertjob_count;
            $jobsArray[$i]['all_job_count'] = $all_job_count;
            $jobsArray[$i]['new_job_count'] = $new_job_count;
            $jobsArray[$i]['created'] = date('Y-m-d', strtotime($val['Job']['created']));
            $i++;
        }
        echo $this->successOutputResult('Jobs List', json_encode($jobsArray));
        exit;
    }

    public function apps_getJobsDetail() {
        $this->layout = '';
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $jobId = $userData['job_id'];
        $tokenData = $this->requestAuthentication('POST', 1);
        $userId = $tokenData['user_id'];
        $jobdetails = $this->Job->findById($jobId);
        $data = array();
        $data['id'] = $jobdetails['Job']['id'];
        $data['title'] = $jobdetails['Job']['title'];
        $data['company_name'] = $jobdetails['Job']['company_name'];
        $data['location'] = $jobdetails['Job']['job_city'];
        $data['category_id'] = $jobdetails['Job']['category_id'];
        $data['expire_time'] = date('Y-m-d', $jobdetails['Job']['expire_time']);
        if (isset($jobdetails['Job']['min_exp']) && isset($jobdetails['Job']['max_exp'])) {
            $experience = $jobdetails['Job']['min_exp'] . "-" . $jobdetails['Job']['max_exp'] . " Year";
        } else {
            $experience = "N/A";
        }
        $data['experience'] = $experience;

        $data['applications'] = ClassRegistry::init('JobApply')->getTotalCandidate($jobdetails['Job']['id']);

        $skills_array = array();

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
        $data['profile_image'] = $jobdetails['User']['profile_image'];
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
        $data['job_type_id'] = $jobdetails['Job']['work_type'];
        $data['salary_id'] = $jobdetails['Job']['min_salary'] . '-' . $jobdetails['Job']['max_salary'];
        $data['experience_id'] = $jobdetails['Job']['min_exp'] . '-' . $jobdetails['Job']['max_exp'];
        $data['designation_id'] = $jobdetails['Job']['designation'];
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
        $data['view_count'] = $jobdetails['Job']['view_count'];
        $data['search_count'] = $jobdetails['Job']['search_count'];
        $active = ClassRegistry::init('JobApply')->getStatusCount($jobdetails['Job']['id'], 'active');
        $data['active_job_count'] = $active;
        $short_list = ClassRegistry::init('JobApply')->getStatusCount($jobdetails['Job']['id'], 'short_list');
        $data['short_list_job_count'] = $short_list;
        $interview = ClassRegistry::init('JobApply')->getStatusCount($jobdetails['Job']['id'], 'interview');
        $data['interview_job_count'] = $interview;
        $offer = ClassRegistry::init('JobApply')->getStatusCount($jobdetails['Job']['id'], 'offer');
        $data['offer_job_count'] = $offer;
        $accept = ClassRegistry::init('JobApply')->getStatusCount($jobdetails['Job']['id'], 'accept');
        $data['accept_job_count'] = $accept;
        $not_suitable = ClassRegistry::init('JobApply')->getStatusCount($jobdetails['Job']['id'], 'not_suitable');
        $data['not_suitable'] = $not_suitable;
        $getTotalCandidate = ClassRegistry::init('JobApply')->getTotalCandidate($jobdetails['Job']['id']);
        $data['get_total_candidate'] = $getTotalCandidate;
        $getNewCount = ClassRegistry::init('JobApply')->getNewCount($jobdetails['Job']['id']);
        $data['get_new_count'] = $getNewCount;

        $apply_array = $this->JobApply->find('all', array('conditions' => array('JobApply.status' => 1, 'JobApply.job_id' => $jobdetails['Job']['id'])));

        $candidate_listing = array();
        if ($apply_array) {
            foreach ($apply_array as $apply) {
                $candidate_id = $apply['User']['id'];
                $lastfave = $this->Favorite->find('first', array('conditions' => array('Favorite.user_id' => $userId, 'Favorite.candidate_id' => $candidate_id), 'fields' => array('Favorite.id')));
                $candidate_listing[] = array(
                    'id' => $apply['JobApply']['id'],
                    'apply_status' => $apply['JobApply']['apply_status'],
                    'is_favourite' => $lastfave ? '1' : '0',
                    'rating' => $apply['JobApply']['rating'],
                    'created' => date('Y-m-d', strtotime($apply['JobApply']['created'])),
                    'user_id' => $apply['User']['id'],
                    'first_name' => $apply['User']['first_name'],
                    'last_name' => $apply['User']['last_name'],
                    'full_name' => $apply['User']['first_name'] . ' ' . $apply['User']['last_name'],
                    'contact' => $apply['User']['contact'],
                    'email_address' => $apply['User']['email_address'],
                );
            }
        }

        $data['get_candidate_count'] = count($candidate_listing);
        $data['candidate'] = $candidate_listing;
        echo $this->successOutputResult('Job details', json_encode($data));
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

    public function apps_addtofavourite() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $this->request->data["Favorite"] = $userData;
        $candidate_id = $userData['candidate_id'];
        $this->request->data["Favorite"]['user_id'] = $userId;
        $lastfave = $this->Favorite->find('first', array('conditions' => array('Favorite.user_id' => $userId, 'candidate_id' => $candidate_id)));
        if (empty($lastfave)) {
            $this->Favorite->save($this->data);
        }
        echo $this->successOutputMsg(__d('controller', 'Add To Favorite', true));
        exit;
    }

    public function apps_removetofavourite() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $candidate_id = $userData['candidate_id'];
        $this->Favorite->deleteAll(array('candidate_id' => $candidate_id, 'user_id' => $userId));

        $userfavList = $this->Favorite->find('all', array('conditions' => array('Favorite.user_id' => $userId), 'order' => array('Favorite.id' => 'desc')));
        $userfavArray = array();
        if ($userfavList) {
            $i = 0;
            foreach ($userfavList as $val) {
                $userfavArray[$i]['id'] = $val['Favorite']['id'];
                $userfavArray[$i]['candidate_id'] = $val['Favorite']['candidate_id'];
                $userfavArray[$i]['first_name'] = $val['Candidate']['first_name'];
                $userfavArray[$i]['last_name'] = $val['Candidate']['last_name'];
                $userfavArray[$i]['profile_image'] = $val['Candidate']['profile_image'];
                $userfavArray[$i]['email_address'] = $val['Candidate']['email_address'];

                $userfavArray[$i]['created'] = $val['Favorite']['created'];
                $i++;
            }
        }
        echo $this->successOutputResult('Get Favourite List', json_encode($userfavArray));
        exit;
    }

    public function apps_getfavouritellist() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $userfavList = $this->Favorite->find('all', array('conditions' => array('Favorite.user_id' => $userId), 'order' => array('Favorite.id' => 'desc')));
        $userfavArray = array();
        if ($userfavList) {
            $i = 0;
            foreach ($userfavList as $val) {
                $userfavArray[$i]['id'] = $val['Favorite']['id'];
                $userfavArray[$i]['candidate_id'] = $val['Favorite']['candidate_id'];
                $userfavArray[$i]['first_name'] = $val['Candidate']['first_name'];
                $userfavArray[$i]['last_name'] = $val['Candidate']['last_name'];
                $userfavArray[$i]['profile_image'] = $val['Candidate']['profile_image'];
                $userfavArray[$i]['email_address'] = $val['Candidate']['email_address'];

                $userfavArray[$i]['created'] = $val['Favorite']['created'];
                $i++;
            }
        }
        echo $this->successOutputResult('Get Favourite List', json_encode($userfavArray));
        exit;
    }

    public function apps_updateJobStatus() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $status = $userData['status'];
        $job_id = $userData['job_id'];

        $jobsDetail = $this->Job->find('first', array('conditions' => array('Job.id' => $job_id), 'order' => array('Job.expire_time' => 'DESC', 'Job.payment_status' => 'DESC')));
        if ($jobsDetail) {
            $cnd = array("Job.id = $job_id");
            $this->Job->updateAll(array('Job.status' => "$status"), $cnd);
            if ($status)
                echo $this->successOutputMsg(__d('controller', 'Job activated successfully.', true));
            else
                echo $this->successOutputMsg(__d('controller', 'Job deactivated successfully.', true));
            exit;
        }
    }

    public function apps_dashboard() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $condition = array('Job.user_id' => $userId);
        $jobCount = ClassRegistry::init('Job')->find('count', array('conditions' => $condition));
        $FavoriteCount = Classregistry::init('Favorite')->find('count', array('conditions' => array('Favorite.user_id' => $userId)));

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
            $data['is_plan'] = 1;
            $tdaye = date('Y-m-d');
            if ($myPlan['UserPlan']['is_expire'] == 1 || $myPlan['UserPlan']['end_date'] < $tdaye) {
                $data['is_expire'] = 1;
            }
        }
        $condition = array('SiteSetting.id' => 1);
        $app_payment = $this->SiteSetting->field('app_payment', $condition);
        $data['app_payment'] = $app_payment ? $app_payment : '0';
        $data['is_job_post'] = 0;
        if ($maxJobPost && $user_plan_id) {
            $postJobCount = Classregistry::init('Job')->find('count', array('conditions' => array('Job.user_id' => $userId, 'Job.user_plan_id' => $user_plan_id)));
            if ($postJobCount >= $maxJobPost) {
                $data['is_job_post'] = 0;
            } else {
                $data['is_job_post'] = 1;
            }
        }
        $data['is_resume_download'] = 0;
        if ($maxResumeDownload && $user_plan_id) {
            $postResumeCount = Classregistry::init('Download')->find('count', array('conditions' => array('Download.user_id' => $userId, 'Download.user_plan_id' => $user_plan_id)));
            if ($postResumeCount >= $maxResumeDownload) {
                $data['is_resume_download'] = 0;
            } else {
                $data['is_resume_download'] = 1;
            }
        }
        $data['is_search_candidate'] = 0;
        if ($user_plan_id) {
//            $SearchCandidateCount = Classregistry::init('Job')->find('count', array('conditions' => array('Job.user_id' => $userId, 'Job.user_plan_id' => $user_plan_id)));
            if (in_array(3, $featureIds)) {
                $data['is_search_candidate'] = 0;
            } else {
                $data['is_search_candidate'] = 1;
            }
        }
        echo $this->successOutputResult('Dashboard', json_encode($data));
        exit;
    }

    public function apps_deleteJob() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $job_id = $userData['job_id'];
//        die;
        $this->Job->deleteAll(array('Job.user_id' => $userId, 'Job.id' => $job_id));
        echo $this->successOutputMsg(__d('controller', 'Job details deleted successfully.', true));
        exit;
    }

    public function apps_updateApplyStatus() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $apply_id = $userData['apply_id'];
        $apply_status = $userData['apply_status'];
        $cnd = array("JobApply.id = $apply_id");
        $this->JobApply->updateAll(array('JobApply.apply_status' => "'$apply_status'"), $cnd);

        echo $this->successOutputMsg(__d('controller', 'Job detail updated successfully.', true));
        exit;
    }

    public function apps_updateJob() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $this->request->data["Job"] = $userData;
        $job_id = $userData['job_id'];
        $jobsDetail = $this->Job->find('first', array('conditions' => array('Job.id' => $job_id), 'order' => array('Job.expire_time' => 'DESC', 'Job.payment_status' => 'DESC')));
        $isAbleToJob = $this->Plan->checkPlanFeature($userId, 1);
        if ($isAbleToJob['status'] == 0) {
            echo $this->errorOutputResult($isAbleToJob['message']);
            exit;
        }
        global $extentions;
        $imageData = $_FILES['logo'];
        $getextention = $this->PImage->getExtension($imageData['name']);
        $extention = strtolower($getextention);
        if (!empty($imageData["name"])) {

            if ($imageData['size'] > '2097152') {
                echo $this->errorOutputResult(__d('controller', 'Max file size upload is 2MB.', true));
                exit;
            } elseif (!in_array($extention, $extentions)) {
                echo $this->errorOutputResult(__d('controller', 'Not Valid Extention.', true));
                exit;
            }
        }

        if (!empty($imageData["name"]) && $imageData['size'] > '0') {
            $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
            $toReplace = "-";
            $logo_name = str_replace($specialCharacters, $toReplace, $imageData['name']);
            $logo_name = str_replace("&", "and", $logo_name);
            if ($logo_name) {
                $cvArray = $imageData;
                $returnedUploadCVArray = $this->PImage->upload($cvArray, UPLOAD_JOB_LOGO_PATH);
                $this->request->data["Job"]["logo"] = $returnedUploadCVArray[0];
                chmod(UPLOAD_JOB_LOGO_PATH . $returnedUploadImageArray[0], 0755);
                if (isset($jobsDetail['Job']['logo']) && $jobsDetail['Job']['logo'] != "") {
                    @unlink(UPLOAD_JOB_LOGO_PATH . $jobsDetail['Job']['logo']);
                }
            }
        } else {
            $this->request->data["Job"]["logo"] = '';
        }
        $this->request->data['Job']['id'] = $userData['id'];
        $exp = explode('-', $this->data['Job']['exp']);
        $this->request->data["Job"]["min_exp"] = $exp[0];
        $this->request->data["Job"]["max_exp"] = $exp[1];

        $sallery = explode('-', $this->data['Job']['salary']);
        $this->request->data["Job"]["min_salary"] = $sallery[0];
        $this->request->data["Job"]["max_salary"] = $sallery[1];

        $this->request->data['Job']['user_id'] = $userId;

        $this->request->data['Job']['subcategory_id'] = '';
        $this->request->data['Job']['status'] = 1;
        $this->request->data['Job']['type'] = 'Gold';
        $this->request->data['Job']['exp_month'] = '0';
        $this->request->data['Job']['payment_status'] = 2;
        $this->request->data['Job']['hot_job_time'] = time() + 7 * 24 * 3600;

        $this->request->data['Job']['user_plan_id'] = $isAbleToJob['user_plan_id'];
        $this->request->data['Job']['amount_paid'] = '180.00';
        $this->request->data['Job']['job_number'] = 'JOB' . $userId . time();
        $this->request->data['Job']['expire_time'] = strtotime($this->data['Job']['expire_time']);
//        echo '<pre>';print_r($this->data);die;
        if ($this->Job->save($this->data)) {
            echo $this->successOutputMsg(__d('controller', 'Job detail updated successfully.', true));
            exit;
        }
    }

    public function apps_submitReview() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $job_applies_id = $userData['job_applies_id'];
        $rating = $userData['rating'];
        $cnd = array("JobApply.id = $job_applies_id");
        $this->JobApply->updateAll(array('JobApply.rating' => "'$rating'"), $cnd);
        echo $this->successOutputMsg(__d('user', 'Rating successfully given to candidate.', true));
        exit;
    }

    public function apps_sendMail() {
        $this->layout = '';
        $tokenData = $this->requestAuthentication('POST', 1);
        $jsonStr = $_POST['jsonData'];
        $userData = json_decode($jsonStr, true);
        $userId = $tokenData['user_id'];
        $email_address = $userData['email_address'];
        $subject = $userData['subject'];
        $job_id = $userData['job_id'];
        $message = $userData['message'];
        $allemails = explode(",", $email_address);
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');
        $jobInfo = $this->Job->find('first', array('conditions' => array('Job.id' => $job_id)));
        if (!empty($jobInfo)) {
            $recruiterInfo = $this->User->find('first', array('conditions' => array('User.id' => $jobInfo['Job']['user_id'])));
            $recruiter_email = $recruiterInfo['User']['email_address'];
            $recruiter_company = $recruiterInfo['User']['company_name'];
            $job_title = $jobInfo['Job']['title'];
        }

        foreach ($allemails as $email) {
            if (trim($email) != '') {
                if ($this->User->checkEmail($email) == true) {

                    $userInfo = $this->User->find('first', array('conditions' => array('User.email_address' => $email)));
                    $userName = ucfirst($userInfo["User"]["first_name"]);

                    $this->Email->to = $email;
                    $message = nl2br($message);
                    $subject = $subject;
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='18'"));
                    $toSubArray = array('[!username!]', '[!MESSAGE!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
                    $fromSubArray = array($userName, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $subject);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                    $this->Email->subject = $subjectToSend;
                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";
                    $toRepArray = array('[!username!]', '[!MESSAGE!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]', '[!company_email!]', '[!company!]', '[!job!]');
                    $fromRepArray = array($userName, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $subject, $recruiter_email, $recruiter_company, $job_title);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
//                    $this->Email->send();
                    $this->Email->reset();
                }
            }
        }
        echo $this->successOutputMsg(__d('controller', 'Email sent successfully.', true));
        exit;
    }

    public function employers() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('home', 'All companies', true));

        $condition2 = array();
        $condition2[] = '(User.user_type = "recruiter" AND User.status = 1 and User.company_name <> "")';
        $newJobrecuirer = $this->User->find('all', array('conditions' => $condition2, 'order' => 'User.verify DESC,User.id DESC', 'limit' => 5));
        $this->set('newJobrecuirer', $newJobrecuirer);
    }

    public function ajaxchangeprofile() {
        $this->layout = "";
        $msgString = '';

        $userId = $this->Session->read("user_id");
        $UseroldImage = $this->User->find('first', array('conditions' => array('User.id' => $userId), 'fields' => array('User.company_logo', 'User.slug')));
        if ($this->data) {
            $this->request->data['User']['old_image'] = $UseroldImage['User']['company_logo'];
            if (!empty($this->data['User']['company_logo']['name'])) {
                $getextention = $this->PImage->getExtension($this->data['User']['company_logo']['name']);
                $extention = strtolower($getextention);
                global $extentions;
                if (!in_array($extention, $extentions)) {
                    $msgString .= __d('controller', 'Not Valid Extention.', true) . "<br>";
                }
                $imageArray = $this->data['User']['company_logo'];
                $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_PROFILE_IMAGE_PATH, "jpg,jpeg,png");
                list($width, $height, $type, $attr) = getimagesize(UPLOAD_FULL_PROFILE_IMAGE_PATH . '/' . $returnedUploadImageArray[0]);
                if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                    $msgString .= __d('controller', 'Image file not valid.', true) . "<br>";
                } else {
                    copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                    copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                    list($width) = getimagesize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                    if ($width > 650) {
                        $this->PImageTest->resize(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_LOGO_IMAGE_WIDTH, UPLOAD_FULL_PROFILE_LOGO_IMAGE_HEIGHT, 100);
                    }
                    $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                    $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);
                    $profilePic = $returnedUploadImageArray[0];
                    chmod(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                    chmod(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                    chmod(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                    $this->request->data['User']['company_logo'] = $profilePic;
                    if (isset($this->data['User']['old_image']) && $this->data['User']['old_image'] != "") {
                        @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                        @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                        @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $this->data['User']['old_image']);
                    }
                }
            } else {
                $this->request->data['User']['company_logo'] = $this->data['User']['old_image'];
            }
            if (isset($msgString) && $msgString != '') {
                echo json_encode(array('error' => $msgString));
                exit;
            } else {
                $this->User->id = $this->Session->read("user_id");
//                print_r($this->data);die;
                if ($this->User->save($this->data)) {
                    echo json_encode(array('message' => true));
                    exit;
                }
            }
        }
        exit;
    }

    public function generateinvoice($slug = null) {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Make a CV');

        $this->userLoginCheck();
//        $this->recruiterAccess();

        $planDetail = $this->UserPlan->find('first', array('conditions' => array('Payment.slug' => $slug)));

        $userId = $this->Session->read("user_id");
        $userdetail = $this->User->findById($userId);

        $this->set('userdetail', $userdetail);
        $this->set('site_title', $site_title);
        $this->set('planDetail', $planDetail);
        $this->set('myaccount', 'active');

        $this->set('name', ucfirst($userdetail['User']['first_name']) . '_' . $userdetail['User']['last_name'] . '_INVOICE_' . date('Y_m_d'));
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
        $this->recruiterAccess();
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
                $company_name = $userCheck['User']['company_name'];
                $email = $userCheck["User"]["email_address"];
                $name = $userCheck['User']['first_name'] . " " . $userCheck['User']['last_name'];
                $adminDetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => 1)));
                if (!empty($adminDetail)) {
                    $this->Email->to = $adminDetail['Admin']['email'];
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='49'"));

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];
                    $currentYear = date('Y', time());

                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . MAIL_FROM . '">' . MAIL_FROM . '</a>';

                    $toSubArray = array('[!username!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!company_name!]');
                    $fromSubArray = array('Admin', $currentYear, HTTP_PATH, $site_title, $sitelink, $company_name);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";
                    $toRepArray = array('[!company_name!]', '[!email!]', '[!username!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!reason!]');
                    $fromRepArray = array($company_name, $email, $name, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $reason);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                }
                if ($this->User->delete($userId)) {
                    @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $image);
                    @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $image);
                    @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $image);
                }
                $this->Session->delete('user_id');
                $this->Session->delete('user_name');
                $this->Session->delete('email_address');

                $this->Session->write('success_msg', __d('controller', 'Your account has been deleted successfully.', true));
                $this->redirect('/users/employerlogin');
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
        $this->recruiterAccess();

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
            $this->viewPath = 'Elements' . DS . 'users';
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
        $this->recruiterAccess();

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

    public function maildesign() {
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');
        $tagline = $this->getSiteConstant('tagline');

        $email = 'test@gmail.com';
        $username = 'test';
        $firstname = 'test';

        $this->Email->to = $email;

        $currentYear = date('Y', time());
        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='29'"));

        $toSubArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_URL!], [!first_name!]');
        $fromSubArray = array($username, $username, $email, $username, $currentYear, HTTP_PATH, $site_title, $site_url, $firstname);
        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
        $this->Email->subject = $subjectToSend;

        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
        $this->Email->from = $site_title . "<" . $mail_from . ">";

        $toRepArray = array('[!username!]', '[!company_name!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_URL!]', '[!first_name!]');
        $fromRepArray = array($username, $username, $email, $username, $currentYear, HTTP_PATH, $site_title, $site_url, $firstname);
        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
        $this->Email->layout = 'default';
        $this->set('messageToSend', $messageToSend);
        $this->Email->template = 'email_template';
        $this->Email->sendAs = 'html';
//                        $this->Email->send();
        echo '<pre>';
        pr($this->Email->send());
        die;
    }

    function generateformat() {
        $this->set('default', '1');
        $this->layout = false;
        $filename = 'Candidates Info - Template.xlsx';
        $file_path = BASE_PATH . '/app/webroot/' . $filename;
        $this->Common->output_file($file_path, $filename);
        exit;
    }
    
    

    

    public function import() {
        ini_set("memory_limit", "-1");
        global $extentions;
        global $extentions_doc;

        $this->layout = "client";
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('user', 'Import Jobseekers', true));

        $this->set('importList', 'active');
        $this->set('default', '2');

//        App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel/PHPExcel.php'));
        // Create new PHPExcel object
        // $file_name = '02106_JobseekersSeptember_03_2020_09_18_am.xlsx';
        $file_name = '';
        $msgString = '';
        if ($this->data) {
//              pr($this->data);exit;
            if (!empty($this->data['User']['filedata']['name'])) {

                $imageArray = $this->data['User']['filedata'];
                $imageArray['name'] = str_replace("\_", '_', str_replace(array('%', '$', '#', '%20', "/", "'", ' ', "\'"), '_', $imageArray['name']));
                $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_DOCUMENT_PATH, "xls,xlsx");
//                  pr($returnedUploadImageArray);exit;
                if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                    $msgString .= "- " . __d('controller', 'File not valid', true) . ".<br>";
                    $file_name = '';
                } else {
                    $file_name = $returnedUploadImageArray[0];
                }
            }
            if ($msgString) {
                $this->Session->write('error_msg', $msgString);
                $this->redirect('/users/import');
                exit;
            }

            $file = UPLOAD_DOCUMENT_PATH . '/' . $file_name;
            require BASE_PATH . "/app/Vendor/PHPExcel.php";
            require BASE_PATH . "/app/Vendor/PHPExcel/IOFactory.php";
            $objPHPExcel = new PHPExcel();
//        $objPHPExcel = new PHPExcel();
            $Reader = IOFactory::createReaderForFile($file);
            $Reader->setReadDataOnly(true);

            $objXLS = $Reader->load($file);

            $objWorksheet = $objXLS->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow();
//        pr($highestRow);
//        exit;

            $rowNumber = 2;
            $totalInsertedRow = 0;
            $errorArray = array();
            $rowNumberError = array();
            $k = 0;

            for ($dd = 2; $dd <= $highestRow; $dd++) {
                $error = '';
                $row1 = $objXLS->getSheet(0)->getCell('A' . $dd)->getValue();
                $row2 = $objXLS->getSheet(0)->getCell('B' . $dd)->getValue();
                $row3 = $objXLS->getSheet(0)->getCell('C' . $dd)->getValue();
                $row4 = $objXLS->getSheet(0)->getCell('D' . $dd)->getValue();
                $row5 = $objXLS->getSheet(0)->getCell('E' . $dd)->getValue();
                $row6 = $objXLS->getSheet(0)->getCell('F' . $dd)->getValue();
                $row7 = $objXLS->getSheet(0)->getCell('G' . $dd)->getValue();
                $row8 = $objXLS->getSheet(0)->getCell('H' . $dd)->getValue();
                $row9 = $objXLS->getSheet(0)->getCell('I' . $dd)->getValue();
                $row10 = $objXLS->getSheet(0)->getCell('J' . $dd)->getValue();
                $row11 = $objXLS->getSheet(0)->getCell('K' . $dd)->getValue();
                $row12 = $objXLS->getSheet(0)->getCell('L' . $dd)->getValue();
                $row13 = $objXLS->getSheet(0)->getCell('M' . $dd)->getValue();
                $row14 = $objXLS->getSheet(0)->getCell('N' . $dd)->getValue();
                $row15 = $objXLS->getSheet(0)->getCell('O' . $dd)->getValue();
                $row16 = $objXLS->getSheet(0)->getCell('P' . $dd)->getValue();
                $row17 = $objXLS->getSheet(0)->getCell('Q' . $dd)->getValue();
                $row18 = $objXLS->getSheet(0)->getCell('R' . $dd)->getValue();
                $row19 = $objXLS->getSheet(0)->getCell('S' . $dd)->getValue();
                $row20 = $objXLS->getSheet(0)->getCell('T' . $dd)->getValue();
                $row21 = $objXLS->getSheet(0)->getCell('U' . $dd)->getValue();
                $row22 = $objXLS->getSheet(0)->getCell('V' . $dd)->getValue();
                $row23 = $objXLS->getSheet(0)->getCell('W' . $dd)->getValue();
                $row24 = $objXLS->getSheet(0)->getCell('X' . $dd)->getValue();
                $row25 = $objXLS->getSheet(0)->getCell('Y' . $dd)->getValue();
//                $row26 = $objXLS->getSheet(0)->getCell('Z' . $dd)->getValue();

                $sr_number = trim($row1);
                $full_name = trim($row2);
                $title = trim($row3);
                $curr_employer = trim($row4);
                $designation = trim($row5);
                $prev_employer = trim($row6);
                $education = trim($row7);
                $source = trim($row8);
                $experience = trim($row9);
                $salary = trim($row10);
                $experience_value = trim($row11);
                $salary_value = trim($row12);
                $cuur_location = trim($row13);
                $prefer_location = trim($row14);
                $status = trim($row15);
                $primary_email = trim($row16);
                $secondary_email = trim($row17);
                $mobile = trim($row18);
                $landline = trim($row19);
                $skill = trim($row20);
                $Annotation_Date = trim($row21);
                $Annotation_By = trim($row22);
                $Annotation_Note = trim($row23);
                $EmailAddress = $primary_email;
                $Filename = trim($row24);

                if (empty($full_name) && empty($EmailAddress)) {
                    break;
                }


                $full_name_arr = explode(' ', $full_name, 2);
//               print_r($full_name_arr);
                $first_name = $full_name_arr[0];
                $last_name = $full_name_arr[1];

                $resourceList[$k]['First Name'] = $first_name;
                $resourceList[$k]['Last Name'] = $last_name;
                $resourceList[$k]['Location'] = $cuur_location;
                $resourceList[$k]['Contact Number'] = $mobile;
                $resourceList[$k]['Email Address'] = $EmailAddress;
                $resourceList[$k]['Filename'] = $Filename;
                $resourceList[$k]['skill'] = $skill;
                $resourceList[$k]['experience'] = round($experience_value);
                $resourceList[$k]['pre_location'] = $prefer_location;
                $resourceList[$k]['title'] = $title;
                $resourceList[$k]['curr_employer'] = $curr_employer;
                $resourceList[$k]['designation'] = $designation;
                $resourceList[$k]['prev_employer'] = $prev_employer;
                $resourceList[$k]['education'] = $education;
                $resourceList[$k]['salary'] = $salary;
                $resourceList[$k]['experience_value'] = $experience_value;
                $resourceList[$k]['salary_value'] = $salary_value;

                $k++;
            }

//            echo '<pre>'; print_r($NewArray);die;

            $error = 0;
            $suces = 0;
            $msgString = '';
            if ($resourceList) {
                $sr = 1;
                $NewArray = array();
                $errorMessage = '';
                foreach ($resourceList as $resource) {
                    $errorArray = array();

//                    $user_id = $this->User->field('User.id', array('User.email_address' => trim($resource['Email Address'])));
                    if (empty($resource['First Name'])) {
                        $errorArray[] = 'First Name';
                        $errorMessage .= __d('controller', 'First name is required field.', true) . "<br>";
                    }
//                    if (empty($resource['Last Name'])) {
//                        $errorArray[] = 'Last Name';
//                        $errorMessage .= "Last Name is required field.";
//                    }
//                    if (empty($resource['Filename'])) {
//                        $errorArray[] = 'Filename';
//                        $errorMessage .= "Filename is required field.";
//                    }
                    $regex = "/^([a-zA-Z0-9\.+]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/";
                    if (empty($resource['Email Address'])) {
                        $errorArray[] = 'Email Address';
                        $errorMessage .= __d('controller', 'Email Address is required field.', true) . "<br>";
                    } elseif ($this->User->checkEmail($resource['Email Address']) == false) {
                        $errorArray[] = 'Email Address';
                        $errorMessage .= $email . " " . __d('controller', 'Email Address is not valid.', true);
                    } elseif (preg_match($regex, $resource["Email Address"]) == false) {
                        $errorArray[] = 'Email Address';
                        $errorMessage .= $email . " " . __d('controller', 'Email Address is not valid.', true);
                    }

                    if ($resource["Email Address"]) {
                        $email = $resource["Email Address"];
                        $userCheck = $this->User->field('User.id', array('User.email_address' => $email));
                        if ($userCheck) {
                            $errorArray[] = 'Email Address';
                            $errorMessage .= $email . " " . __d('controller', 'Email already exists.', true) . "<br>";
                        }
                    }

                    $offshore = 0;
                    $onshore = 0;
                    if (isset($errorMessage) && $errorMessage != '') {
                        $newArr = array_values(array_unique($errorArray));
                        $error = 1;
                        $NewArray[] = array(
                            'First Name' => $resource['First Name'],
                            'Last Name' => $resource['Last Name'],
                            'Email Address' => $resource['Email Address'],
//                            'Filename' => $resource['Filename'],
                            'error' => $newArr,
                            'Comments' => $errorMessage);
                    }
                }
//                echo '<pre>'; 
//                print_r($NewArray);
//                print_r($resourceList);
//                die;
                if (empty($NewArray)) {
                    foreach ($resourceList as $resource) {
                        $first = trim($resource['First Name']);
                        $last = trim($resource['Last Name']);
                        $email = trim($resource['Email Address']);
                        $location = trim($resource['Location']);
                        $contact = trim($resource['Contact Number']);
                        $Filename = trim($resource['Filename']);
                        $skill = trim($resource['skill']);

                        $experience = trim($resource['experience']);
                        $pre_location = trim($resource['pre_location']);
                        $title = trim($resource['title']);
                        $curr_employer = trim($resource['curr_employer']);
                        $designation = trim($resource['designation']);
                        $prev_employer = trim($resource['prev_employer']);
                        $education = trim($resource['education']);
                        $salary = trim($resource['salary']);
                        $experience_value = trim($resource['experience_value']);
                        $salary_value = trim($resource['salary_value']);

                        $company_about = '<Title>' . $title . '<Current Employer>' . $curr_employer . '<Designation> ' . $designation . ' <Previous Employer>' . $prev_employer . '<Education>' . $education . '<Salary>' . $salary . '<Experience Value>' . $experience_value . '<Salary Value>' . $salary_value . '';

                        $sw = explode(",", $skill);

                        foreach ($sw as $k => $v) {
                            $fin = $this->Skill->find('all', array('conditions' => array('Skill.type' => 'Skill')));

                            if (isset($fin) && count($fin) > 0 && is_array($fin)) {
                                if ($this->Skill->in_array_r(trim($v), $fin)) {
                                    // return ;
                                } else {
                                    if (!empty($v)) {
                                        $s['Skill']['slug'] = $this->stringToSlugUnique($v, 'Skill', 'slug');
                                        $s['Skill']['name'] = $v;
                                        $s['Skill']['type'] = 'Skill';
                                        $this->Skill->saveall($s);
                                        //  unset($s);
                                    }
                                }
                            } else {
                                //die("dsa");
                                if (!empty($v)) {

                                    $s['Skill']['slug'] = $this->stringToSlugUnique($v, 'Skill', 'slug');
                                    $s['Skill']['name'] = $v;
                                    $s['Skill']['type'] = 'Skill';
                                    $this->Skill->saveAll($s);
                                    unset($s);
                                }
                            }
                        }

                        if ($Filename) {
                            $newfname = basename($Filename);
                            $newfurl = UPLOAD_CERTIFICATE_PATH . $newfname;
                            $file = fopen($Filename, 'rb');
                            if ($file) {
                                $newf = fopen($newfurl, 'wb');
                                if ($newf) {
                                    while (!feof($file)) {
                                        fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                                    }
                                }
                            }
                            if ($file) {
                                fclose($file);
                            }
                            if ($file && $newf) {
                                fclose($newf);
                            }
                        }
                        $suces = 1;

                        $this->User->create();

                        $this->request->data = array();

                        $passwordPlain = $first . rand();

                        $salt = uniqid(mt_rand(), true);

                        $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');

                        $this->request->data['User']['password'] = $new_password;

                        $this->request->data['User']['first_name'] = $first;
                        $this->request->data['User']['last_name'] = $last;
                        $this->request->data['User']['contact'] = $contact;
                        $this->request->data['User']['location'] = $location;
                        $this->request->data['User']['email_address'] = $email;
                        $this->request->data['User']['skills'] = $skill;
                        $this->request->data['User']['email_address'] = $email;
                        $this->request->data['User']['total_exp'] = round($experience_value);
                        $this->request->data['User']['pre_location'] = $pre_location;
                        $this->request->data['User']['company_about'] = $company_about;
//                        $this->request->data['User']['token'] = $passwordPlain;
                        $this->request->data['User']['created'] = date('Y-m-d H:i:s');
                        $this->request->data['User']['status'] = 1;
                        $this->request->data['User']['activation_status'] = 1;
                        $this->request->data['User']['user_type'] = 'candidate';
                        $this->request->data['User']['slug'] = $this->stringToSlugUnique($first . ' ' . $last, 'User', 'slug');
                        //echo '<pre>'; print_r($this->data);
                        if ($this->User->save($this->data)) {
                            $userId = $this->User->id;
                            if ($Filename) {
                                if (file_exists($newfurl)) {
                                    $this->Certificate->create();
                                    $this->request->data['Certificate']['document'] = $newfname;
                                    $this->request->data['Certificate']['user_id'] = $userId;
                                    $getextention = $this->PImage->getExtension($newfname);
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
                            $userCheck = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
                            $email = $this->data["User"]["email_address"];
                            $username = $this->data["User"]["first_name"];

                            $emData = $this->Emailtemplate->getSubjectLang();
                            $subjectField = $emData['subject'];
                            $templateField = $emData['template'];

                            $this->Email->to = $email;
                            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';
                            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='52'"));
                            //$this->Email->subject = $this->data['Job']['subject'];
                            $toSubArray = array('[!email!]', '[!password!]', '[!username!]', '[!SITE_TITLE!]');
                            $fromSubArray = array($email, $passwordPlain, $username, $site_title);
                            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                            $this->Email->subject = $subjectToSend;

                            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                            $this->Email->from = $site_title . "<" . $mail_from . ">";

                            $toRepArray = array('[!email!]', '[!password!]', '[!username!]', '[!SITE_TITLE!]');
                            $fromRepArray = array($email, $passwordPlain, $username, $site_title);
                            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                            $this->Email->layout = 'default';
                            $this->set('messageToSend', $messageToSend);
//                            echo $messageToSend;die;
                            $this->Email->template = 'email_template';
                            $this->Email->sendAs = 'html';
                            $this->Email->send();
                            $this->Email->reset();
                        }
                    }
                }
            }
            //die;

            if ($error == 1) {
                $this->Session->write('error_msg', $errorMessage);
                $this->redirect('/users/import');
                exit;
            } elseif ($suces == 1) {
                $this->Session->write('success_msg', __d('controller', 'Jobseekers details saved successfully.'));
                $this->redirect('/users/import');
                exit;
            } else {
                $this->Session->write('error_msg', __d('controller', 'Something wrong in xls file.'));
                $this->redirect('/users/import');
                exit;
            }
        }
    }

}

?>