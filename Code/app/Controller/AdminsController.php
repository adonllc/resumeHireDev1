<?php

/**
 * This file used for admin related functionlity
 * @organization LogicSpice Consultancy Private Limited
 * @website https://www.logicspice.com
 * @contact info@logicspice.com
 * 
 */
class AdminsController extends AppController {

    //var $name       = 'Admins';
    var $uses = array('Admin', 'User', 'Emailtemplate', 'Setting', 'Category', 'Country', 'Job', 'Location','Changecolors', 'Skill', 'Blog');
    public $helpers = array('Html', 'Form', 'Fck', 'Javascript', 'Ajax', 'Text', 'Number', 'Session');
    var $components = array('RequestHandler', 'Email', 'Captcha', 'Upload', 'PImageTest', 'PImage');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Admin.name' => 'asc'));
    var $layout = 'admin';

    function beforeFilter() {

        if ($this->action != 'admin_forgotpassword') {
            $loggedAdminId = $this->Session->read("adminid");
            if (!$loggedAdminId && $this->params['action'] != "admin_login" && $this->params['action'] != 'admin_captcha') {
                $returnUrlAdmin = $this->params->url;
                $this->Session->write("returnUrlAdmin", $returnUrlAdmin);
                $this->redirect(array('controller' => 'admins', 'action' => 'login', ''));
            }
        }
    }

    function admin_index() {
        $this->layout = 'admin';
        $this->redirect(array('controller' => 'admins', 'action' => 'login', ''));
    }

    function admin_captcha() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        if (!isset($this->Captcha)) {
            App::import('Component', 'Captcha'); //load it
            $this->Captcha = new CaptchaComponent(); //make instance
            $this->Captcha->startup($this); //and do some manually calling
        }
        $this->Captcha->create();
    }

    /**
     * @abstract This function is define to admin add subadmin users.
     * @access Public
     * @author Logicspice (info@logicspice)
     * @since 1.0.0 2015-06-19
     */
    public function admin_dashboard() {
        $this->layout = 'admin_dashboard';
        $this->set('dashboard', 'active');
        //$constant = $this->getConstantData();

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . " Dashboard");


        $total_customers = $this->User->find('count', array('conditions' => array('User.user_type' => 'recruiter')));
        $this->set('total_customers', $total_customers);

        $total_candidate = $this->User->find('count', array('conditions' => array('User.user_type' => 'candidate')));
        $this->set('total_candidate', $total_candidate);

        $total_job = $this->Job->find('count', array('conditions' => array()));
        $this->set('total_job', $total_job);

        $total_categories = $this->Category->find('count', array('conditions' => array('Category.parent_id' => 0)));
        $this->set('total_categories', $total_categories);

        $total_skill = $this->Skill->find('count', array('conditions' => array('Skill.type' => 'Skill')));
        $this->set('total_skill', $total_skill);

        $total_designation = $this->Skill->find('count', array('conditions' => array('Skill.type' => 'Designation')));
        $this->set('total_designation', $total_designation);

        $total_location = $this->Location->find('count', array());
        $this->set('total_location', $total_location);

        $total_country = $this->Country->find('count', array());
        $this->set('total_country', $total_country);

        $total_blog = $this->Blog->find('count', array());
        $this->set('total_blog', $total_blog);

        $current_date = date('d');
        $curr_month = date('m');
        $current_year = date('Y');
        $user_datas = array();
        $count_data_arr = array();
        for ($i = 1; $i <= $current_date; $i++) {
            $day = $i;
            $cond = array(
                'DAY(User.created)' => $day,
                'MONTH(User.created)' => $curr_month,
                'YEAR(User.created)' => $current_year,
                'User.user_type' => 'recruiter'
            );
            //$cond[] = "DAY(Event.created)='$day' AND MONTH(Event.created)='$curr_month' AND YEAR(Event.created)='$current_year'";

            $count_data = $this->User->find('count', array('conditions' => $cond));
            $user_datas[$i] = $day . ',' . $count_data;

            $count_data_arr[] = $count_data;
        }

        $total_users_no = array_sum($count_data_arr);
        $total_users_time = sizeof($count_data_arr);
        $max_users = max($count_data_arr);

        $this->set('total_user_no', $total_users_no);
        $this->set('total_user_time', $total_users_time);
        $this->set('max_user', $max_users);
        $this->set('user_datas', $user_datas);


        $jobseeker_datas = array();
        $count_data_arr1 = array();
        for ($i = 1; $i <= $current_date; $i++) {
            $day = $i;
            $cond = array(
                'DAY(User.created)' => $day,
                'MONTH(User.created)' => $curr_month,
                'YEAR(User.created)' => $current_year,
                'User.user_type' => 'candidate'
            );
            //$cond[] = "DAY(Event.created)='$day' AND MONTH(Event.created)='$curr_month' AND YEAR(Event.created)='$current_year'";

            $count_data = $this->User->find('count', array('conditions' => $cond));
            $jobseeker_datas[$i] = $day . ',' . $count_data;
            $count_data_arr1[] = $count_data;
        }

        $total_jobseeker_no = array_sum($count_data_arr1);
        $total_jobseeker_time = sizeof($count_data_arr1);
        $max_jobseeker = max($count_data_arr1);

        $this->set('total_jobseeker_no', $total_jobseeker_no);
        $this->set('total_jobseeker_time', $total_jobseeker_time);
        $this->set('max_jobseeker', $max_jobseeker);
        $this->set('jobseeker_datas', $jobseeker_datas);
    }

    public function admin_logout() {
        $this->Session->delete('adminid');
        $this->Session->delete('groupid');
        $this->Session->setFlash('Logout successfully.', 'success_msg');
//        $returnUrlAdmin = $this->request->referer();
//        $this->Session->write("returnUrlAdmin", $returnUrlAdmin);
        //$this->redirect(array('controller' => 'admins', 'action' => 'login', ''));
        $this->redirect(array('controller' => 'admins', 'action' => 'login/?secureKey=' . SECURE_CODE, ''));
    }

    public function admin_login() {
        $this->layout = 'admin_login';

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . "Administration Login");
        $msgString = "";
        if ($this->Session->check('adminid')) {
            $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', ''));
        }
        $this->set('forgot_hidden', '1');

        if (!empty($this->data)) {

            if (empty($this->data['Admin']['username'])) {
                $msgString .= "- Please enter your user name.<br>";
            }
            if (empty($this->data['Admin']['password'])) {
                $msgString .= "- Please enter your password.<br>";
            }


            if(isset( $this->request->data['g-recaptcha-response'])){
                $captcha= $this->request->data['g-recaptcha-response'];

            }
            
                if(!$captcha){
                    $msgString .= "- Please check the captcha.<br>";

               // $msgString .= __d('controller', 'Please check the the captcha form.', true) . "<br>";

                }

            if (isset($this->data['loginform']) && $this->data['loginform'] == 'Sign in') {

                if ($msgString != '') {
                    $this->Session->setFlash($msgString, 'error_msg');
                } else {

                    $username = $this->data['Admin']['username'];
                    $password = $this->data['Admin']['password'];

                    $time1 = date("Y-m-d H:i:s", time() - 60 * 60 * 24);

//                    $time = time() - 30;
//                    $time1 = date('Y-m-d H:i:s', $time);
                    $adminDetail = $this->Admin->find('first', array('conditions' => array("(Admin.username = '" . addslashes($username) . "') AND (Admin.status = 1 OR Admin.modified <= '" . $time1 . "')")));

                    //$adminDetail = $this->Admin->find('first', array('conditions' =>array("Admin.username" => $username)));
                    // pr($adminDetail);exit;
                    if ($adminDetail['Admin']['status'] == 1 || $adminDetail['Admin']['modified'] <= $time1) {
                        if (is_array($adminDetail) && !empty($adminDetail) && crypt($password, $adminDetail['Admin']['password']) == $adminDetail['Admin']['password']) {

                            $this->Session->write("adminid", $adminDetail['Admin']['id']);
                            $this->Session->write("groupid", $adminDetail['Admin']['id']);
                            $this->Session->write("admin_username", $adminDetail['Admin']['username']);

                            if (isset($this->data['Admin']['keep']) && $this->data['Admin']['keep'] == '1') {
                                setcookie("admincookname_famevote", $this->data['Admin']['username'], time() + 60 * 60 * 24 * 100, "/");
                                setcookie("admincookpass_famevote", $this->data['Admin']['password'], time() + 60 * 60 * 24 * 100, "/");
                            } else {
                                setcookie("admincookname_famevote", '', time() + 60 * 60 * 24 * 100, "/");
                                setcookie("admincookpass_famevote", '', time() + 60 * 60 * 24 * 100, "/");
                            }

                            $adminrecord['Admin']['id'] = $adminDetail['Admin']['id'];
                            $adminrecord['Admin']['status'] = 1;

                            $this->Admin->save($adminrecord);
                            $this->Session->delete('Adminloginstatus');

                            if ($this->Session->check("returnUrlAdmin")) {
                                $returnUrlAdmin = $this->Session->read("returnUrlAdmin");

                                $this->Session->delete("returnUrlAdmin");
                                $this->redirect("/" . $returnUrlAdmin);
                            } else {
                                $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', ''));
                            }
                        } else {

                            $i = $this->Session->read('Adminloginstatus');

                            $this->Session->setFlash('Invalid username and/or password.', 'error_msg');
                            // $this->redirect(array('controller' => 'admins', 'action' => 'login', ''));
                            $this->redirect(array('controller' => 'admins', 'action' => 'login/?secureKey=' . SECURE_CODE, ''));
                        }
                    } else {
                        $this->Session->setFlash('Your account got temporary disabled. Please login after 24 hours.', 'error_msg');
                        // $this->redirect(array('controller' => 'admins', 'action' => 'login', ''));
                        $this->redirect(array('controller' => 'admins', 'action' => 'login/?secureKey=' . SECURE_CODE, ''));
                    }
                }
            } else if (isset($this->data['forgotform']) && $this->data['forgotform'] == 'Submit') {

                if (trim($this->data['Admin']['email']) == '') {
                    $this->set('forgot_hidden', '0');
                    $this->Session->setFlash('Please enter Email Address', 'error_msg');
                } else if ($this->Admin->checkEmail($this->data['Admin']['email']) == false) {
                    $this->set('forgot_hidden', '0');
                    $this->Session->setFlash('Please enter valid Email Address', 'error_msg');
                } else if (trim($this->data['Admin']['answer1']) == '') {
                    $this->set('forgot_hidden', '0');
                    $this->Session->setFlash('Please enter answer for first question', 'error_msg');
                } else if (trim($this->data['Admin']['answer2']) == '') {
                    $this->set('forgot_hidden', '0');
                    $this->Session->setFlash('Please enter answer for second question', 'error_msg');
                } else {

                    $uid = $this->Admin->find('first', array('conditions' => array('Admin.email' => $this->data['Admin']['email'])));
                    if (empty($uid)) {
                        $this->set('forgot_hidden', '0');
                        $this->Session->setFlash('Please enter correct Email Address', 'error_msg');
                    } elseif ($uid['Admin']['answer1'] != $this->data['Admin']['answer1']) {
                        $this->set('forgot_hidden', '0');
                        $this->Session->setFlash('Please enter correct answer for first question.', 'error_msg');
                    } elseif ($uid['Admin']['answer2'] != $this->data['Admin']['answer2']) {
                        $this->set('forgot_hidden', '0');
                        $this->Session->setFlash('Please enter correct answer for second question.', 'error_msg');
                    } elseif ($uid['Admin']['status'] != '1') {
                        $this->set('forgot_hidden', '0');
                        $this->Session->setFlash('Your account might be temporarily disabled by site administrator.', 'error_msg');
                    } else {

                        $email = $uid['Admin']['email'];
                        $username = $uid['Admin']['username'];
                        $password = $uid['Admin']['password'];

                        $dear_user = $username;

                        $passwordPlain = $this->rand_string(8);
                        $salt = uniqid(mt_rand(), true);

                        $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');

                        $this->Admin->updateAll(array('Admin.password' => "'" . $new_password . "'"), array('Admin.email' => $this->data['Admin']['email']));

                        $this->Email->to = $email;
                        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='5'"));

                        $toSubArray = array('[!SITE_TITLE!]');
                        $fromSubArray = array($site_title);
                        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                        $this->Email->subject = $subjectToSend;

                        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                        $this->Email->from = $site_title . "<" . $mail_from . ">";

                        $adminloginlink = HTTP_PATH . '/admin?secureKey=' . SECURE_CODE;

                        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';
                        $toRepArray = array('[!username!]', '[!email!]', '[!password!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!adminloginlink!]');
                        $fromRepArray = array($username, $email, $passwordPlain, HTTP_PATH, $site_title, $adminloginlink);
                        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                        $this->Email->layout = 'default';
                        $this->set('messageToSend', $messageToSend);
                        $this->Email->template = 'email_template';
                        $this->Email->sendAs = 'html';
                        $this->Email->send();



                        $this->Session->setFlash('Your password has been sent on your email id.', 'success_msg');
                        //$this->redirect(array('controller' => 'admins', 'action' => 'login', ''));
                        $this->redirect(array('controller' => 'admins', 'action' => 'login/?secureKey=' . SECURE_CODE, ''));
                    }
                }
            }
        } else {

            $secureCode = $_GET['secureKey'];

            if ($secureCode != '') {
                if ($secureCode != SECURE_CODE) {
                    $this->redirect('/');
                }
            } else {
                $this->redirect('/');
            }


            if (isset($_COOKIE["admincookname_famevote"]) && isset($_COOKIE["admincookpass_famevote"])) {

                $this->request->data['Admin']['username'] = $_COOKIE["admincookname_famevote"];
                $this->request->data['Admin']['password'] = $_COOKIE["admincookpass_famevote"];
                $this->request->data['Admin']['keep'] = '1';
            }
        }
    }
    
    public function admin_changecolorscheme() {
        $this->layout = "admin";
        $this->set('changecolorscheme', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Change Color Scheme');

        if($this->data){
            // print_r($this->data);exit;
            $msgString = '';
            if(empty($this->data['Changecolors']['theme_color'])){
                $msgString .= 'Theme Color is a required field';
            }
            if(empty($this->data['Changecolors']['theme_background'])){
                $msgString .= 'Theme Background is a required field';
            }
            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                 if(empty($this->data['Changecolors']['is_default'])){
                    $this->request->data['Changecolors']['is_default'] = 0;
                }
                if($this->Changecolors->save($this->data['Changecolors'])){
                    $this->Session->setFlash('Color Theme Updated Successfully', 'success_msg');
                } else {
                    $this->Session->setFlash('Something went wrong!!', 'error_msg');
                }
            }
        }else{
           //$data =  $this->Changecolors->find('first');
             $data =  $this->Changecolors->find('first',array('conditions'=>array('Changecolors.id'=>6)));
       
           $this->data =$data;
        }
    }


    /**
     * @abstract This function is define to change admin password.
     * @access Public
     * @author Logicspice (info@logicspice)
     * @since 1.0.0 2015-06-19
     */
    public function admin_changepassword() {
        $this->layout = 'admin';
        $this->set('changepassword', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Change Password');

        $msgString = '';

        if ($this->data) {

            if (empty($this->data["Admin"]["old_password"])) {
                $msgString .= "- Current Password is required field.<br>";
            } else {
                if ($this->Admin->isPasswordExist($this->data["Admin"]["old_password"], $this->Session->read("adminid")) == false) {
                    $msgString .= "- Current Password Incorrect.<br>";
                }
            }

            if (empty($this->data["Admin"]["password"])) {
                $msgString .= "- New Password is required field.<br>";
            }

            if (empty($this->data["Admin"]["confirm_password"])) {
                $msgString .= "- Confirm  Password is required field.<br>";
            }
            $password = $this->data["Admin"]["password"];
            $confirmpassword = $this->data["Admin"]["confirm_password"];

            if ($password != $confirmpassword) {
                $msgString .= "- New Password &amp; Confirm Password didn't Match.<br>";
            }

            if ($this->Admin->isPasswordExist($this->data["Admin"]["old_password"], $this->Session->read("adminid")) == true && ($this->data["Admin"]["old_password"] == $this->data["Admin"]["password"])) {
                $msgString .= "- You can not change new password same as current password.<br>";
            }

            if (!empty($msgString) && isset($msgString)) {
                $this->Session->setFlash($msgString, 'error_msg');
            }


            if ($msgString == '') {

                $salt = uniqid(mt_rand(), true);
                $this->request->data['Admin']['password'] = crypt($this->data['Admin']['password'], '$2a$07$' . $salt . '$');

                if ($this->Admin->save($this->data)) {
                    $this->Session->setFlash('Your Password changed Successfully', 'success_msg');
                    $this->redirect(array('controller' => 'admins', 'action' => 'changepassword', ''));
                }
            }
        }//end of main if
    }

    /**
     * @abstract This function is define to change admin email address.
     * @access Public
     * @author Logicspice (info@logicspice)
     * @since 1.0.0 2015-06-19
     */
    public function admin_changeusername() {

        $this->layout = "admin";
        $this->set('changeusername', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Change Username');

        $id = $this->Session->read("adminid");
        $Admindetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
        $this->set('Admins', $Admindetail);

        $msgString = "";

        if (!empty($this->data)) {

            if (empty($this->data["Admin"]["new_username"])) {
                $msgString = "- New Username is required field.<br>";
            } else if ($this->data["Admin"]["new_username"] == $Admindetail["Admin"]["username"]) {
                $msgString .= "- You can not change new username same as current username.<br>";
            }
            if (empty($this->data["Admin"]["conf_username"])) {
                $msgString .= "- Confirm Username is required field.<br>";
            }

            if (trim($this->data['Admin']['new_username']) != trim($this->data['Admin']['conf_username'])) {
                $msgString .= "- New Username And Confirm Username Should be Match.<br>";
            }
            if (trim($this->data['Admin']['new_username']) != trim($this->data['Admin']['old_username'])) {
                $conditions = array();
                $conditions["Admin.username"] = trim($this->data["Admin"]["new_username"]);
                if ($this->Admin->isEmailExist($conditions) == false) {
                    $msgString .= "- Username already exists.<br>";
                }
            }
            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {

                $this->request->data["Admin"]["username"] = $this->data["Admin"]["new_username"];

                if ($this->Admin->save($this->data)) {
                    $this->Session->setFlash('Admin Username Updated Successfully.', 'success_msg');
                    $this->redirect(array('controller' => 'admins', 'action' => 'changeusername', ''));
                }
            }
        }
    }

    public function admin_translation() {

        $this->layout = "admin";
        $this->set('changeutranslation', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Translation');

        $id = $this->Session->read("adminid");
        $Admindetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
        $this->set('Admins', $Admindetail);

        $msgString = "";

        if (!empty($this->data)) {

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $common = BASE_PATH . "/app/Locale/fra/LC_MESSAGES/common.po";
                $str = ($this->request->data['Setting']['common']);
                file_put_contents($common, $str);

                $home = BASE_PATH . "/app/Locale/fra/LC_MESSAGES/home.po";
                $str = ($this->request->data['Setting']['home']);
                file_put_contents($home, $str);

                $controller = BASE_PATH . "/app/Locale/fra/LC_MESSAGES/controller.po";
                $str = ($this->request->data['Setting']['controller']);
                file_put_contents($controller, $str);

                $user = BASE_PATH . "/app/Locale/fra/LC_MESSAGES/user.po";
                $str = ($this->request->data['Setting']['user']);
                file_put_contents($user, $str);

                $this->redirect(array('controller' => 'admins', 'action' => 'translation', ''));
            }
        } else {
            $common = BASE_PATH . "/app/Locale/fra/LC_MESSAGES/common.po";
            $commonfile = file_get_contents($common, true);
            $this->request->data['Setting']['common'] = $commonfile;

            $home = BASE_PATH . "/app/Locale/fra/LC_MESSAGES/home.po";
            $homefile = file_get_contents($home, true);
            $this->request->data['Setting']['home'] = $homefile;

            $controller = BASE_PATH . "/app/Locale/fra/LC_MESSAGES/controller.po";
            $controllerfile = file_get_contents($controller, true);
            $this->request->data['Setting']['controller'] = $controllerfile;

            $user = BASE_PATH . "/app/Locale/fra/LC_MESSAGES/user.po";
            $userfile = file_get_contents($user, true);
            $this->request->data['Setting']['user'] = $userfile;
        }
    }

    /**
     * @abstract This function is define to change admin email address.
     * @access Public
     * @author Logicspice (info@logicspice)
     * @since 1.0.0 2015-06-19
     */
    public function admin_changeemail() {

        $this->layout = "admin";
        $this->set('changeemail', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Change Email');

        $id = $this->Session->read("adminid");
        $Admindetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
        $this->set('Admins', $Admindetail);
        $msgString = "";

        if (!empty($this->data)) {

            if (empty($this->data["Admin"]["new_email"])) {
                $msgString = "- New Email is required field.<br>";
            } elseif ($this->Admin->checkEmail($this->data["Admin"]["new_email"]) == false) {
                $msgString .= "- Please enter Valid New Email.<br>";
            } else if ($this->data["Admin"]["new_email"] == $Admindetail["Admin"]["email"]) {
                $msgString .= "- You can not change new email same as current email.<br>";
            }

            if (empty($this->data["Admin"]["conf_email"])) {
                $msgString .= "- Confirm Email is required field.<br>";
            } elseif ($this->Admin->checkEmail($this->data["Admin"]["conf_email"]) == false) {
                $msgString .= "- Please enter confirm Valid Email.<br>";
            }

            if ($this->data['Admin']['new_email'] != $this->data['Admin']['conf_email']) {
                $msgString .= "- New Email And Confirm Email Should be Match.<br>";
            }
            if ($this->data['Admin']['new_email'] != $this->data['Admin']['old_email']) {
                if ($this->Admin->isRecordUniqueemail($this->data["Admin"]["new_email"]) == false) {
                    $msgString .= "- Email already exists.<br>";
                }
            }
            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {

                $this->request->data["Admin"]["email"] = $this->data["Admin"]["new_email"];

                if ($this->Admin->save($this->data)) {
                    $this->Session->setFlash('Admin Email Updated Successfully.', 'success_msg');
                    $this->redirect(array('controller' => 'admins', 'action' => 'changeemail', ''));
                }
            }
        }
    }

    public function admin_changeccemail() {

        $this->layout = "admin";
        $this->set('default', '0');
        $this->set('change_cc_email', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . "Change Administration CC Email");

        $id = $this->Session->read("adminid");
        $Admindetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
        $this->set('Admins', $Admindetail);
        $msgString = "";

        if (!empty($this->data)) {

            if (empty($this->data["Admin"]["new_email"])) {
                $msgString = "- New CC Email is required field.<br>";
            } elseif ($this->Admin->checkEmail($this->data["Admin"]["new_email"]) == false) {
                $msgString .= "- Please enter valid New CC Email.<br>";
            }

            if (empty($this->data["Admin"]["conf_email"])) {
                $msgString .= "- Confirm CC Email is required field.<br>";
            } elseif ($this->Admin->checkEmail($this->data["Admin"]["conf_email"]) == false) {
                $msgString .= "- Please enter valid Confirm CC Email.<br>";
            }

            if ($this->data['Admin']['new_email'] != $this->data['Admin']['conf_email']) {
                $msgString .= "- New CC Email and Confirm CC Email must be match.<br>";
            }
            if ($this->data["Admin"]["old_email"] == $this->data["Admin"]["new_email"]) {
                $msgString .= "- You can not change New CC Email same as current CC Email.<br>";
            }

            if ($this->data['Admin']['new_email'] != $this->data['Admin']['old_email']) {
                $conditions = array();
                $conditions["Admin.cc_email"] = $this->data["Admin"]["new_email"];
                if ($this->Admin->isEmailExist($conditions) == false) {
                    $msgString .= "- CC Email already exists.<br>";
                }
            }
            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
            } else {

                $this->request->data["Admin"]["cc_email"] = $this->data["Admin"]["new_email"];

                if ($this->Admin->save($this->data)) {
                    $this->Session->write('success_msg', 'Admin CC Email Updated Successfully.');
                    $this->redirect(array('controller' => 'admins', 'action' => 'changeccemail', ''));
                }
            }
        }
    }

    /**
     * @abstract This function is define to change admin email address.
     * @access Public
     * @author Logicspice (info@logicspice)
     * @since 1.0.0 2015-06-19
     */
    public function admin_settings() {

        $this->layout = "admin";
        $this->set('default', '0');
        $this->set('change_contactemail', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . "Set Contact Us Details");

        $id = $this->Session->read("adminid");
        $Admindetail = $this->Setting->find('first', array('conditions' => array('Setting.id' => '1')));


        $this->set('Admins', $Admindetail);
        $msgString = "";

        if (!empty($this->data)) {


            if (trim($this->data["Setting"]["company_name"]) == '') {
                $msgString .= "Company Name is required field.<br>";
            }

            if (trim($this->data["Setting"]["email"]) == '') {
                $msgString .= "Email Address is required field.<br>";
            }
            if (trim($this->data["Setting"]["address"]) == '') {
                $msgString .= "Address is required field.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if ($this->Setting->save($this->data)) {
                    $this->Session->setFlash('Contact Us Details Updated Successfully.', 'success_msg');
                    $this->redirect('/admin/admins/settings');
                }
            }
        } else {
            $this->Setting->id = '1';
            $this->data = $this->Setting->read();
        }
    }

    public function admin_picture() {

        $this->layout = "admin";
        $this->set('default', '0');
        $this->set('change_picture', 'active');
        $id = $this->Session->read("adminid");
        $Admindetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
        $this->set('Admins', $Admindetail);
        $msgString = "";
        global $extentions;
        if ($this->data) {
            $getextention = $this->PImage->getExtension($this->data['Admin']['profile_image']['name']);
            $extention = strtolower($getextention);
            if (empty($this->data["Admin"]["profile_image"]["name"])) {
                $msgString .= "- Photo is required field.<br>";
            } elseif ($this->data['Admin']['profile_image']['size'] > '2097152') {
                $msgString .= "- Max file size upload is 2MB.<br>";
            } else if (!in_array($extention, $extentions)) {
                $msgString .= "- Not Valid Extention.<br>";
            }
            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
            } else {

                if (!empty($this->data['Admin']['profile_image']['name'])) {
                    $imageArray = $this->data['Admin']['profile_image'];
                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_PROFILE_IMAGE_PATH, "jpg,jpeg,png,gif");

                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= "- Image file not valid.<br>";
                    } else {
                        copy(UPLOAD_FULL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        $this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                        $profilePic = $returnedUploadImageArray[0];
                         chmod(UPLOAD_FULL_PROFILE_IMAGE_PATH .$returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                    }
                }
// to remove old image
                if ($Admindetail['Admin']['profile_image'] != '') {
                    @unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $Admindetail['Admin']['profile_image']);
                    @unlink(UPLOAD_FULL_PROFILE_IMAGE_PATH . $Admindetail['Admin']['profile_image']);
                }


                $cnd = array("Admin.id = $id");
                $this->Admin->updateAll(array('Admin.profile_image' => "'$profilePic'"), $cnd);
                $this->Session->write('success_msg', 'Admin Picture Updated Successfully.');
                $this->redirect(array('controller' => 'admins', 'action' => 'picture', ''));
            }
        }
    }

    public function userdetails() {
        $id = '1';
        $userdetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
        return $userdetail;
    }

    public function admin_serverFilePath() {
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . "Server Configuration Path");
        $this->set('default', '0');
        $this->layout = "admin";
        $this->set('serverpath', 'active');
        $condition = array();
        $separator = array();
        $urlSeparator = array();

        $separator = implode("/", $separator);

        if (!empty($this->passedArgs)) {
            if (isset($this->passedArgs["page"])) {
                $urlSeparator[] = 'page:' . $this->passedArgs["page"];
            }
            if (isset($this->passedArgs["sort"])) {
                $urlSeparator[] = 'sort:' . $this->passedArgs["sort"];
            }
            if (isset($this->passedArgs["direction"])) {
                $urlSeparator[] = 'direction:' . $this->passedArgs["direction"];
            }
        }

        $urlSeparator = implode("/", $urlSeparator);
        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        //echo '<pre>';print_r($this->paginate('Page',$condition));die;
        if ($this->request->is('ajax')) {
            //Configure::write('debug', 0);
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/admin/';
            $this->render('server_file_path');
        }
    }

    public function admin_sendemail() {
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . "Send Email");
        $this->layout = "admin";
        $this->set('default', '1');
        $this->set('send_email2', 'active');
        $msgString = '';

        if ($this->data) {
            if (empty($this->data["User"]["email_address"])) {
                $msgString .= "- Email Address is required field.<br>";
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
                $email = $this->data['User']['email_address'];
                if ($this->data['User']['name']) {
                    $username = $this->data['User']['name'];
                } else {
                    $userdet = $this->User->find('first', array('fields' => array('User.first_name', 'User.email_address'), 'conditions' => array('User.email_address' => $this->data["User"]["email_address"])));
                    if ($userdet) {
                        $username = $userdet['User']['first_name'];
                    } else {
                        $username = 'User';
                    }
                }
                $this->Email->to = $email;
                $this->Email->cc = $this->Admin->field('cc_email', array('Admin.id' => 1));

                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='20'"));
                $this->Email->subject = $this->data['User']['subject'];
                //$this->Email->subject = $emailtemplateMessage['Emailtemplate']['subject'];
                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";
                $currentYear = date('Y', time());
                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';
                $toRepArray = array('[!username!]', '[!subject!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
                $fromRepArray = array($username, $this->data['User']['subject'], $this->data['User']['message'], $currentYear, CNE_HTTP_PATH, $site_title, $sitelink, $site_url);
                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                $this->Email->layout = 'default';
                $this->set('messageToSend', $messageToSend);
                $this->Email->template = 'email_template';
                $this->Email->sendAs = 'html';
                $this->Email->send();

                $this->Session->write('success_msg', 'You have successfully sent email.');
                $this->redirect(array('controller' => 'admins', 'action' => 'sendemail', ''));
            }
        }
    }

    public function admin_commission() {
        $this->layout = "admin";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . "Set Commission");
        $msgString = '';
        $this->set('commision_set', 'active');
        $id = $this->Session->read("adminid");
        $Admindetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
        $this->set('Admins', $Admindetail);

        if (!empty($this->data)) {

            if ($this->data["Admin"]["commission"] == '') {
                $msgString = "- Commissoin is required field.<br>";
            }
            if ($this->data["Admin"]["commission"] < 0) {
                $msgString = "- Commissoin cannot be less than 0.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data["Admin"]["id"] = 1;
                if ($this->Admin->save($this->data)) {
                    $this->Session->setFlash('Admin Commission Set Successfully.', 'success_msg');
                    $this->redirect(array('controller' => 'admins', 'action' => 'commission'));
                }
            }
        } else {
            $this->Admin->id = '1';
            $this->data = $this->Admin->read();
        }
    }

    public function admin_promocode() {
        $this->layout = "admin";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'promocode');
        $msgString = '';
        $id = $this->Session->read("adminid");
        $Admindetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
        $this->set('Admins', $Admindetail);
        $userlist = $this->User->find('list', array('fields' => array('id', 'username'), 'order' => 'User.username ASC'));
        $this->set('userlist', $userlist);

        if (!empty($this->data)) {

            if (empty($this->data["Admin"]["promocode"])) {
                $msgString = "- Promo Code is required field.<br>";
            }
            if (empty($this->data["Admin"]["selectuser"])) {
                $msgString = "- Select the any one user required.<br>";
            }
            if (empty($this->data["Admin"]["offer"])) {
                $msgString = "- Offer is required field.<br>";
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data["Admin"]["id"] = 1;

                if ($this->Admin->save($this->data)) {
                    $this->Session->setFlash('Promo Code Set Successfully.', 'success_msg');
                    $this->redirect(array('controller' => 'admins', 'action' => 'promocode'));
                }
            }
        }
    }

    public function admin_securityQuestions() {
        $this->layout = 'admin';
        $this->set('questionS', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Security Questions');
        $msgString = '';

        if ($this->data) {

            if (empty($this->data["Admin"]["question1"])) {
                $msgString .= "- Question 1 is required field.<br>";
            }
            if (empty($this->data["Admin"]["answer1"])) {
                $msgString .= "- Answer 1 is required field.<br>";
            }
            if (empty($this->data["Admin"]["question2"])) {
                $msgString .= "- Question 2 is required field.<br>";
            }
            if (empty($this->data["Admin"]["answer2"])) {
                $msgString .= "- Answer 2 is required field.<br>";
            }

            if (strtolower($this->data["Admin"]["question1"]) == strtolower($this->data["Admin"]["question2"])) {
                $msgString .= "- Question 1 and Question 2 must be different.<br>";
            }

            if (!empty($msgString) && isset($msgString)) {
                $this->Session->setFlash($msgString, 'error_msg');
            }


            if ($msgString == '') {

                if ($this->Admin->save($this->data)) {
                    $this->Session->setFlash('Security questions updated Successfully', 'success_msg');
                    $this->redirect(array('controller' => 'admins', 'action' => 'securityQuestions', ''));
                }
            }
        } else {
            $this->Admin->id = $this->Session->read("adminid");
            // pr($this->Admin->read());exit;
            $this->request->data = $this->Admin->read();
        }
    }

    public function admin_planPrice() {
        $this->layout = 'admin';
        $this->set('planS', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Manage Plan Price');

        $msgString = '';

        if ($this->data) {

            if ($this->data["Admin"]["bronze"] == '' || $this->data["Admin"]["bronze"] < 0) {
                $msgString .= "- Bronze price is required field.<br>";
            }

            if ($this->data["Admin"]["silver"] == '' || $this->data["Admin"]["silver"] < 0) {
                $msgString .= "- Silver price is required field.<br>";
            }
            if ($this->data["Admin"]["gold"] == '' || $this->data["Admin"]["gold"] < 0) {
                $msgString .= "- Gold price is required field.<br>";
            }

            if (!empty($msgString) && isset($msgString)) {
                $this->Session->setFlash($msgString, 'error_msg');
            }


            if ($msgString == '') {

                if ($this->Admin->save($this->data)) {
                    $this->Session->setFlash('Plan price updated Successfully', 'success_msg');
                    $this->redirect(array('controller' => 'admins', 'action' => 'planPrice', ''));
                }
            }
        } else {
            $this->Admin->id = $this->Session->read("adminid");
            // pr($this->Admin->read());exit;
            $this->request->data = $this->Admin->read();
        }
    }

    public function admin_changeSlogan() {
        $this->layout = 'admin';
        $this->set('change_text', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Change Slogan');
        $msgString = '';

        if ($this->data) {

            if (empty($this->data["Admin"]["slogan_text"])) {
                $msgString .= "- Slogan Text is required field.<br>";
            }


            if (!empty($msgString) && isset($msgString)) {
                $this->Session->setFlash($msgString, 'error_msg');
            }


            if ($msgString == '') {

                if ($this->Admin->save($this->data)) {
                    $this->Session->setFlash('Slogan text updated Successfully', 'success_msg');
                    $this->redirect(array('controller' => 'admins', 'action' => 'changeSlogan', ''));
                }
            }
        } else {
            $this->Admin->id = $this->Session->read("adminid");
            $this->request->data = $this->Admin->read();
        }
    }

    public function admin_changePaymentdetail() {
        $this->layout = 'admin';
        $this->set('change_paypal', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Change Payment Detail');
        $msgString = '';

        if ($this->data) {

            if (empty($this->data["Admin"]["paypal_email"])) {
                $msgString .= "- Paypal Email is required field.<br>";
            }
            if (empty($this->data["Admin"]["stripe_secret_key"])) {
                $msgString .= "- Stripe Secret Key is required field.<br>";
            }


            if (!empty($msgString) && isset($msgString)) {
                $this->Session->setFlash($msgString, 'error_msg');
            }


            if ($msgString == '') {

                if ($this->Admin->save($this->data)) {
                    $this->Session->setFlash('Payment Email updated Successfully', 'success_msg');
                    $this->redirect(array('controller' => 'admins', 'action' => 'changePaymentdetail', ''));
                }
            }
        } else {
            $this->Admin->id = $this->Session->read("adminid");
            $this->request->data = $this->Admin->read();
        }
    }

    public function admin_invoice() {
        global $extentions;


        $this->layout = 'admin';
        $this->set('invoices', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Invoice');
        $msgString = '';

        $invoice = $this->Admin->find('all');
        $this->set('invoice', $invoice);

        $max_size = $this->getSiteConstant('max_size');
        $over_max_size = $max_size * 1048576;

        // pr($invoice);


        if ($this->data) {

            //   pr($this->data);exit;

            /* if (empty($this->data["Admin"]["abn"])) {
              $msgString .="- ABN is required field.<br>";
              } */
            if (empty($this->data["Admin"]["account_name"])) {
                $msgString .= "- Account Name is required field.<br>";
            }
            if (empty($this->data["Admin"]["bsb"])) {
                $msgString .= "- BSB is required field.<br>";
            }
            if (empty($this->data["Admin"]["acc"])) {
                $msgString .= "- ACC is required field.<br>";
            }
            if (empty($this->data["Admin"]["payment_terms"])) {
                $msgString .= "- Payment Terms is required field.<br>";
            }
            if (empty($this->data["Admin"]["acnt_email_add"])) {
                $msgString .= "- Account Email Address is required field.<br>";
            }
            if (empty($this->data["Admin"]["ac_nu"])) {
                $msgString .= "- Account Number is required field.<br>";
            }



            if ($this->data["Admin"]["logo"]["name"]) {
//     /        pr( $extentions);
                //echo $this->data['Admin']['logo']['size'] ;


                $getextention = $this->PImage->getExtension($this->data['Admin']['logo']['name']);
                $extention = strtolower($getextention);
//                     /   die("das");
                if ($this->data['Admin']['logo']['size'] > $over_max_size) {
                    //   die("sda");
                    $msgString .= "- Max file size upload is $max_size MB.<br>";
                } elseif (!in_array($extention, $extentions)) {
                    $msgString .= "- Not Valid Extention.<br>";
                }
            }


            if (!empty($msgString) && isset($msgString)) {
                $this->Session->setFlash($msgString, 'error_msg');
                $this->redirect(array('controller' => 'admins', 'action' => 'invoice', ''));
            }


            if ($msgString == '') {


                //    pr($this->data);
                if ($this->data['Admin']['logo']['name']) {



                    $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                    $toReplace = "-";
                    $this->request->data['Admin']['logo']['name'] = str_replace($specialCharacters, $toReplace, $this->data['Admin']['logo']['name']);
                    $this->request->data['Admin']['logo']['name'] = str_replace("&", "and", $this->data['Admin']['logo']['name']);

                    $imageArray = $this->data['Admin']['logo'];
                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_INVOICE_IMAGE_PATH, "jpg,jpeg,png,gif");



                    list($width, $height, $type, $attr) = getimagesize(UPLOAD_FULL_INVOICE_IMAGE_PATH . '/' . $returnedUploadImageArray[0]);

                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {


                        $msgString .= "- Profile Image file not valid.<br>";
                    } else {
                        //pr($returnedUploadImageArray); 
                        //  copy(HTTP_IMAGE . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        //  copy(HTTP_IMAGE . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0]);
                        list($width) = getimagesize(UPLOAD_FULL_INVOICE_IMAGE_PATH . $returnedUploadImageArray[0]);

                        $this->PImageTest->resize(UPLOAD_FULL_INVOICE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_INVOICE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_PROFILE_IMAGE_WIDTH, UPLOAD_FULL_PROFILE_IMAGE_HEIGHT, 100);


                        //$this->PImageTest->resize(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_PROFILE_IMAGE_WIDTH, UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT, 100);
                        // $this->PImageTest->resize(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_PROFILE_IMAGE_WIDTH, UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT, 100);

                        $this->request->data['Admin']['logo'] = $returnedUploadImageArray[0];
                         chmod(UPLOAD_FULL_INVOICE_IMAGE_PATH .$returnedUploadImageArray[0], 0755);



                        if (isset($this->data['Admin']['logo']) && $this->data['Admin']['logo'] != "") {

                            //  @unlink(UPLOAD_FULL_INVOICE_IMAGE_PATH . $this->data['Admin']['logo']);
                            //@unlink(UPLOAD_THUMB_PROFILE_IMAGE_PATH . $this->data['User']['old_profile_image']);
                            // @unlink(UPLOAD_SMALL_PROFILE_IMAGE_PATH . $this->data['User']['old_profile_image']);
                        }
                        // die();
                    }
                } else {

                    $this->request->data['Admin']['logo'] = $invoice[0]['Admin']['logo'];
                }

                if ($this->Admin->save($this->data)) {
                    $this->Session->setFlash('Updated Successfully', 'success_msg');
                    $this->redirect(array('controller' => 'admins', 'action' => 'invoice', ''));
                }
            }
        } else {
            $this->Admin->id = $this->Session->read("adminid");
            $this->request->data = $this->Admin->read();
        }
    }

    /* ------------------------------- */
    /* ----Upload Logo by admin------- */
    /* ------------------------------- */

    public function admin_uploadLogo() {

        $this->layout = "admin";
        $this->set('default', '0');
        $this->set('uploadLogo', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Upload logo');

        $id = $this->Session->read("adminid");


        $oldLogo = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id), 'fields' => array('Admin.logo')));
        $this->set('old_logo', $oldLogo);


        $msgString = "";
        global $extentions;

        $max_size = $this->getSiteConstant('max_size');
        $over_max_size = $max_size * 1048576;

        if ($this->data) {

            //logo
            $getextention = $this->PImage->getExtension($this->data['Admin']['logo']['name']);
            $extention = strtolower($getextention);
            if (empty($this->data["Admin"]["logo"]["name"])) {

                $msgString .= "- Logo image is required field.<br>";
            } elseif ($this->data['Admin']['logo']['size'] > $over_max_size) {
                $msgString .= "- Max file size upload is $max_size MB.<br>";
            } elseif (!in_array($extention, $extentions)) {
                $msgString .= "- Not Valid Extention.<br>";
            }



            if (isset($msgString) && $msgString != '') {

                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                $toReplace = "-";
                //logo
                $this->request->data['Admin']['logo']['name'] = str_replace($specialCharacters, $toReplace, $this->data['Admin']['logo']['name']);
                $this->request->data['Admin']['logo']['name'] = str_replace("&", "and", $this->data['Admin']['logo']['name']);
                if (!empty($this->data['Admin']['logo']['name'])) {


                    $imageArray = $this->data['Admin']['logo'];
                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_WEBSITE_LOGO_PATH, "jpg,jpeg,png,gif");
                    list($width, $height, $type, $attr) = getimagesize(UPLOAD_FULL_WEBSITE_LOGO_PATH . '/' . $returnedUploadImageArray[0]);
                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= "- Logo file not valid.<br>";
//                    } else if ($width > 300 && $height > 60) {
//                        @unlink(UPLOAD_FULL_WEBSITE_LOGO_PATH . '/' . $returnedUploadImageArray[0]);
//                        $msgString .="- Logo size must not be bigger than  282 X 47 pixels.<br>";
                    } else {
                        copy(UPLOAD_FULL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0]);
                        copy(UPLOAD_FULL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0]);
                        list($width) = getimagesize(UPLOAD_FULL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0]);
//                        echo $width;die;
                        // if ($width > 300) {
                        //     $this->PImageTest->resize(UPLOAD_FULL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_WEBSITE_LOGO_WIDTH, UPLOAD_FULL_WEBSITE_LOGO_HEIGHT, 100);
                        // }
                        $this->PImageTest->resize(UPLOAD_THUMB_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_WEBSITE_LOGO_WIDTH, UPLOAD_THUMB_WEBSITE_LOGO_HEIGHT, 100);
                        $this->PImageTest->resize(UPLOAD_SMALL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_WEBSITE_LOGO_WIDTH, UPLOAD_SMALL_WEBSITE_LOGO_HEIGHT, 100);
                        $logoPic = $returnedUploadImageArray[0];
                         chmod(UPLOAD_FULL_WEBSITE_LOGO_PATH .$returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_SMALL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], 0755);
                        if (isset($this->data['Admin']['old_logo']) && $this->data['Admin']['old_logo'] != "") {
                            @unlink(UPLOAD_FULL_WEBSITE_LOGO_PATH . $this->data['Admin']['old_logo']);
                            @unlink(UPLOAD_THUMB_WEBSITE_LOGO_PATH . $this->data['Admin']['old_logo']);
                            @unlink(UPLOAD_SMALL_WEBSITE_LOGO_PATH . $this->data['Admin']['old_logo']);
                        }
                    }
                }


                if (isset($msgString) && $msgString != '') {

                    $this->Session->setFlash($msgString, 'error_msg');
                } else {

                    $id = $this->Session->read("adminid");
                    $cnd = array("Admin.id = $id");
                    $this->Admin->updateAll(array('Admin.logo' => "'$logoPic'"), $cnd);
                    $this->Session->setFlash('Admin logo Updated Successfully.', 'success_msg');
                    $this->redirect(array('controller' => 'admins', 'action' => 'uploadLogo', ''));
                }
            }
        }
        $Admindetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
        $this->request->data = $Admindetail;
        $this->request->data['Admin']['old_logo'] = $Admindetail['Admin']['logo'];
    }

    /* ------------------------------- */
    /* ----Delete Logo by admin------- */
    /* ------------------------------- */

    public function deleteLogo($image = null) {
        $id = $this->Session->read("adminid");

        if ($id > 0) {
            $cnd1 = array("Admin.id = '$id'");
            $this->Admin->updateAll(array('Admin.logo' => "''"), $cnd1);
            @unlink(UPLOAD_FULL_WEBSITE_LOGO_PATH . $image);
            $this->Session->setFlash('Logo deleted successfully.', 'success_msg');
            // $this->Session->write('success_msg', 'Logo deleted successfully.');
            $this->redirect('/admin/admins/uploadLogo');
        }
    }

    /* ------------------------------- */
    /* ----Upload Logo by admin------- */
    /* ------------------------------- */

    public function admin_changeFavicon() {

        $this->layout = "admin";
        $this->set('default', '0');
        $this->set('changefav', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Change Favicon');

        $id = $this->Session->read("adminid");


        $oldFav = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id), 'fields' => array('Admin.favicon')));
        $this->set('old_fav', $oldFav);

        $msgString = "";
        global $favextentions;

        $max_size = $this->getSiteConstant('max_size');
        $over_max_size = $max_size * 1048576;

        if ($this->data) {


            //fav icon
            $getfavextention = $this->PImage->getExtension($this->data['Admin']['favicon']['name']);
            $favExtention = strtolower($getfavextention);
            if (empty($this->data["Admin"]["favicon"]["name"])) {
                $msgString .= "- Favicon image is required field.<br>";
            } elseif ($this->data['Admin']['favicon']['size'] > $over_max_size) {
                $msgString .= "- Max file size upload is $max_size MB.<br>";
            } elseif (!in_array($favExtention, $favextentions)) {
                $msgString .= "- Not Valid Extention.<br>";
            }


            if (isset($msgString) && $msgString != '') {

                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                $toReplace = "-";

                //favicon

                $this->request->data['Admin']['favicon']['name'] = str_replace($specialCharacters, $toReplace, $this->data['Admin']['favicon']['name']);
                $this->request->data['Admin']['favicon']['name'] = str_replace("&", "and", $this->data['Admin']['favicon']['name']);
                if (!empty($this->data['Admin']['favicon']['name'])) {


                    $imageArray = $this->data['Admin']['favicon'];
                    $returnedFavImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_FAV_PATH, "ico");
                    list($width, $height, $type, $attr) = getimagesize(UPLOAD_FULL_FAV_PATH . '/' . $returnedFavImageArray[0]);
                    if (isset($returnedFavImageArray[1]) && !empty($returnedFavImageArray[1])) {
                        $msgString .= "- Favicon file not valid.<br>";
                    } else if ($width < 16 && $height < 16) {
                        @unlink(UPLOAD_FULL_FAV_PATH . '/' . $returnedFavImageArray[0]);
                        $msgString .= "- favicon size must be bigger than  16 X 16 pixels.<br>";
                    } else {
                        copy(UPLOAD_FULL_FAV_PATH . $returnedFavImageArray[0], UPLOAD_THUMB_FAV_PATH . $returnedFavImageArray[0]);
                        copy(UPLOAD_FULL_FAV_PATH . $returnedFavImageArray[0], UPLOAD_SMALL_FAV_PATH . $returnedFavImageArray[0]);
                        list($width) = getimagesize(UPLOAD_FULL_FAV_PATH . $returnedFavImageArray[0]);
                        if ($width > 650) {
                            $this->PImageTest->resize(UPLOAD_FULL_FAV_PATH . $returnedFavImageArray[0], UPLOAD_FULL_FAV_PATH . $returnedFavImageArray[0], UPLOAD_FULL_FAV_WIDTH, UPLOAD_FULL_FAV_HEIGHT, 100);
                        }
                        $this->PImageTest->resize(UPLOAD_THUMB_FAV_PATH . $returnedFavImageArray[0], UPLOAD_THUMB_FAV_PATH . $returnedFavImageArray[0], UPLOAD_THUMB_FAV_WIDTH, UPLOAD_THUMB_FAV_HEIGHT, 100);
                        $this->PImageTest->resize(UPLOAD_SMALL_FAV_PATH . $returnedFavImageArray[0], UPLOAD_SMALL_FAV_PATH . $returnedFavImageArray[0], UPLOAD_SMALL_FAV_WIDTH, UPLOAD_SMALL_FAV_HEIGHT, 100);
                        $favPic = $returnedFavImageArray[0];
                          chmod(UPLOAD_FULL_FAV_PATH .$returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_FAV_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_SMALL_FAV_PATH . $returnedUploadImageArray[0], 0755);
                        if (isset($this->data['Admin']['old_fav']) && $this->data['Admin']['old_fav'] != "") {
                            @unlink(UPLOAD_FULL_FAV_PATH . $this->data['Admin']['old_fav']);
                            @unlink(UPLOAD_THUMB_FAV_PATH . $this->data['Admin']['old_fav']);
                            @unlink(UPLOAD_SMALL_FAV_PATH . $this->data['Admin']['old_fav']);
                        }
                    }
                }


                if (isset($msgString) && $msgString != '') {

                    $this->Session->setFlash($msgString, 'error_msg');
                } else {

                    $id = $this->Session->read("adminid");
                    $cnd = array("Admin.id = $id");
                    $this->Admin->updateAll(array('Admin.favicon' => "'$favPic'"), $cnd);
                    $this->Session->setFlash('Admin favicon Updated Successfully.', 'success_msg');
                    $this->redirect(array('controller' => 'admins', 'action' => 'changeFavicon', ''));
                }
            }
        }
        $Admindetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
        $this->request->data = $Admindetail;
        $this->request->data['Admin']['old_fav'] = $Admindetail['Admin']['favicon'];
    }

    /* ------------------------------- */
    /* ----Delete Favicon by admin------- */
    /* ------------------------------- */

    public function deletefavicon($image = null) {
        $id = $this->Session->read("adminid");

        if ($id > 0) {
            $cnd1 = array("Admin.id = '$id'");
            $this->Admin->updateAll(array('Admin.favicon' => "''"), $cnd1);
            @unlink(UPLOAD_FULL_WEBSITE_LOGO_PATH . $image);
            $this->Session->setFlash('Favicon deleted successfully.', 'success_msg');
            // $this->Session->write('success_msg', 'Logo deleted successfully.');
            $this->redirect('/admin/admins/changeFavicon');
        }
    }

    /* ---Meta management-- */

    public function admin_metaManagement() {

        $this->layout = "admin";
        $this->set('default', '0');
        $this->set('meta', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Meta Management');

        $id = $this->Session->read("adminid");

        $Admindetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
        $this->set('Admins', $Admindetail);

        $msgString = "";

        if ($this->data) {

            if (empty($this->data['Admin']['default_title'])) {
                $msgString .= "";
            }
            if (empty($this->data['Admin']['default_keyword'])) {
                $msgString .= "";
            }
            if (empty($this->data['Admin']['default_description'])) {
                $msgString .= "";
            }
            if (empty($this->data['Admin']['meta_jobtitle'])) {
                $msgString .= "";
            }
            if (empty($this->data['Admin']['meta_jobkeywords'])) {
                $msgString .= "";
            }
            if (empty($this->data['Admin']['meta_jobdescription'])) {
                $msgString .= "";
            }
            if (empty($this->data['Admin']['meta_catetitle'])) {
                $msgString .= "";
            }
            if (empty($this->data['Admin']['meta_catekeywords'])) {
                $msgString .= "";
            }
            if (empty($this->data['Admin']['meta_catedescription'])) {
                $msgString .= "";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data['Admin']['id'] = $Admindetail['Admin']['id'];
                if ($this->Admin->save($this->data)) {
                    $this->Session->setFlash("Meta details update sucessfully.", 'success_msg');
                    $this->redirect(array('controller' => 'admins', 'action' => 'metaManagement'));
                }
            }
        } else {
            $adminId = $this->Session->read("adminid");
            $this->Admin->id = $adminId;
            $this->data = $this->Admin->read();
        }
    }

    public function admin_manage() {

        $this->layout = "admin";
        $this->set('subadmin_list', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Manage Sub Admins');

        $condition = array('Admin.id >' => 1);
        $separator = array();
        $urlSeparator = array();
        $name = '';

        if ($this->Session->read("adminid") != 1) {
            $this->redirect(array('controller' => 'admins', 'action' => 'dashboard'));
        }

        $separatorArr = array();

        if (!empty($this->data)) {
            if (isset($this->data['Admin']['name']) && $this->data['Admin']['name'] != '') {
                $name = trim($this->data['Admin']['name']);
            }

            if (isset($this->data['Admin']['action'])) {
                $idList = $this->data['Admin']['idList'];
                if ($idList) {
                    if ($this->data['Admin']['action'] == "activate") {
                        $cnd = array("Admin.id IN ($idList) ");
                        $this->Admin->updateAll(array('Admin.status' => "'1'"), $cnd);
                    } elseif ($this->data['Admin']['action'] == "deactivate") {
                        $cnd = array("Admin.id IN ($idList) ");
                        $this->Admin->updateAll(array('Admin.status' => "'0'"), $cnd);
                    } elseif ($this->data['Admin']['action'] == "delete") {
                        $cnd = array("Admin.id IN ($idList) ");
                        $this->Admin->deleteAll($cnd);
                    }
                }
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['name']) && $this->params['named']['name'] != '') {
                $name = urldecode(trim($this->params['named']['name']));
            }
        }

        if (isset($name) && $name != '') {
            $separator[] = 'name:' . urlencode($name);
            $$nameassetName = str_replace('_', '\_', $name);
            $condition[] = " (`Admin`.`first_name` LIKE '%" . addslashes($name) . "%' or `Admin`.`last_name` LIKE '%" . addslashes($name) . "%' or `Admin`.`username` LIKE '%" . addslashes($name) . "%' or `Admin`.`email` LIKE '%" . addslashes($name) . "%'    ) ";
            $name = str_replace('\_', '_', $name);
            $this->set('searchKey', $name);
        }

        $order = array('Admin.status' => 'Desc', 'Admin.first_name' => 'ASC');
        $separator = implode("/", $separator);

        $urlSeparator = implode("/", $urlSeparator);
        $this->set('$name', $name);
        $this->set('separator', $separator);
        $this->set('separatorArr', $separatorArr);

        $this->paginate['Admin'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('admins', $this->paginate('Admin'));

//        echo '<pre>';
//        print_r($condition);
//        print_r($this->paginate('Admin'));exit;

        if ($this->request->is('ajax')) {
            //Configure:write('debug', 0);
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/admin';
            $this->render('manage');
        }
    }

    public function admin_addsubadmin() {
        $this->layout = "admin";
        $this->set('subadmin_list', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Add Sub Admin');

        $msgString = '';
        global $extentions;

        if ($this->Session->read("adminid") != 1) {
            $this->redirect(array('controller' => 'admins', 'action' => 'dashboard'));
        }


        if ($this->data) {

            if (empty($this->data["Admin"]["first_name"])) {
                $msgString .= "- First name is required field.<br>";
            }

            if (empty($this->data["Admin"]["last_name"])) {
                $msgString .= "- Last name is required field.<br>";
            }

            if (empty($this->data["Admin"]["username"])) {
                $msgString .= "- Username is required field.<br>";
            } elseif ($this->Admin->isRecordUniqueUsername($this->data["Admin"]["username"]) == false) {
                $msgString .= "- Username already exists.<br>";
            }

            if (trim($this->data["Admin"]["email"]) == '') {
                $msgString .= "- Email is required field.<br>";
            } elseif ($this->User->checkEmail($this->data["Admin"]["email"]) == false) {
                $msgString .= "- Email Not Valid.<br>";
            }
            if ($this->Admin->isRecordUniqueemail($this->data["Admin"]["email"]) == false) {
                $msgString .= "- Email already exists.<br>";
            }

            if (trim($this->data["Admin"]["password"]) == '') {
                $msgString .= "- Password is required field.<br>";
            } elseif (strlen($this->data["Admin"]["password"]) < 8) {
                $msgString .= "- Password must be at least 8 characters.<br>";
            } else {
                $strongPassword = preg_match('((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,20})', $this->data["Admin"]["password"]);
                if ($strongPassword == 0) {
                    $msgString .= "- Password minimum length must be 8 characters and combination of 1 special character, 1 lowercase character, 1 uppercase character and 1 number.<br>";
                }
            }
            if (trim($this->data["Admin"]["confirm_password"]) == '') {
                $msgString .= "- Confirm  Password is required field.<br>";
            } else {
                $password = $this->data["Admin"]["password"];
                $conformpassword = $this->data["Admin"]["confirm_password"];

                if ($password != $conformpassword) {
                    $msgString .= "- Password and confirm password mismatch.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {

                $passwordPlain = $this->data["Admin"]["password"];
                $salt = uniqid(mt_rand(), true);
                $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');

                $this->request->data['Admin']['password'] = $new_password;

                $this->request->data['Admin']['first_name'] = trim($this->data['Admin']['first_name']);
                $this->request->data['Admin']['last_name'] = trim($this->data['Admin']['last_name']);
                $this->request->data['Admin']['status'] = 1;
                $this->request->data['Admin']['slug'] = $this->stringToSlugUnique($this->data['Admin']['username'], 'Admin', 'slug');


                if ($this->Admin->save($this->data)) {

//                    $email = $this->data['Admin']['email'];
//                    $username = $this->data['Admin']['username'];
//                    $name = ucwords($this->data['Admin']['first_name'] . ' ' . $this->data['Admin']['last_name']);
//                    $this->Email->to = $email;
//
//                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='25'"));
//                    $this->Email->subject = SITE_TITLE . ' Sub Admin has been created';
//                    $this->Email->replyTo = SITE_TITLE . "<" . MAIL_FROM . ">";
//                    $this->Email->from = SITE_TITLE . "<" . MAIL_FROM . ">";
//                    $currentYear = date('Y', time());
//                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . MAIL_FROM . '">' . MAIL_FROM . '</a>';
//                    $toRepArray = array('[!name!]','[!username!]', '[!email!]', '[!password!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]');
//                    $fromRepArray = array($name, $username, $email, $passwordPlain, $currentYear, HTTP_PATH, SITE_TITLE, $sitelink, SITE_URL);
//                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
//                    $this->Email->layout = 'default_admin';
//                    $this->set('messageToSend', $messageToSend);
//                    $this->Email->template = 'email_template';
//                    $this->Email->sendAs = 'html';
//                    $this->Email->send();

                    $this->Session->setFlash('Sub admin account created successfully', 'success_msg');
                    $this->redirect('/admin/admins/manage');
                }
            }
        }
    }

    public function admin_editadmins($slug = null) {
        $this->layout = "admin";
        $this->set('subadmin_list', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Edit Sub Admin');


        $msgString = '';
        global $extentions;
        if ($this->Session->read("adminid") != 1) {
            $this->redirect(array('controller' => 'admins', 'action' => 'dashboard'));
        }


        if ($this->data) {

            // pr($this->data);exit;

            if (empty($this->data["Admin"]["first_name"])) {
                $msgString .= "- First name is required field.<br>";
            }

            if (empty($this->data["Admin"]["last_name"])) {
                $msgString .= "- Last name is required field.<br>";
            }

            if (empty($this->data["Admin"]["username"])) {
                $msgString .= "- Username is required field.<br>";
            } elseif (strtolower($this->data["Admin"]["username"]) != strtolower($this->data["Admin"]["old_username"])) {
                if ($this->Admin->isRecordUniqueUsername($this->data["Admin"]["username"]) == false) {
                    $msgString .= "- Username already exists.<br>";
                }
            }

            if (trim($this->data["Admin"]["email"]) == '') {
                $msgString .= "- Email is required field.<br>";
            } elseif ($this->User->checkEmail($this->data["Admin"]["email"]) == false) {
                $msgString .= "- Email Not Valid.<br>";
            }
            if (strtolower($this->data["Admin"]["email"]) != strtolower($this->data["Admin"]["old_email"])) {
                if ($this->Admin->isRecordUniqueemail($this->data["Admin"]["old_email"]) == false) {
                    $msgString .= "- Email already exists.<br>";
                }
            }

            if (trim($this->data["Admin"]["new_password"]) != '') {
                if (strlen($this->data["Admin"]["new_password"]) < 8) {
                    $msgString .= "- Password must be at least 8 characters.<br>";
                } else {
                    $strongPassword = preg_match('((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,20})', $this->data["Admin"]["new_password"]);
                    if ($strongPassword == 0) {
                        $msgString .= "- Password minimum length must be 8 characters and combination of 1 special character, 1 lowercase character, 1 uppercase character and 1 number.<br>";
                    }
                }
                if (trim($this->data["Admin"]["confirm_password"]) == '') {
                    $msgString .= "- Confirm Password is required field.<br>";
                } else {
                    $password = $this->data["Admin"]["new_password"];
                    $conformpassword = $this->data["Admin"]["confirm_password"];

                    if ($password != $conformpassword) {
                        $msgString .= "- New password and confirm password mismatch.<br>";
                    } elseif (crypt($this->data['Admin']['new_password'], $this->data['Admin']['old_password']) == $this->data['Admin']['old_password']) {// Checking the both password matched aur not
                        $msgString .= "- You cannot put old password for the new password!<br>";
                    } else {
                        $changedPassword = 1;
                        $passwordPlain = $this->data["Admin"]["new_password"];
                        $salt = uniqid(mt_rand(), true);
                        $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');

                        $this->request->data['Admin']['password'] = $new_password;
                    }
                }
            } elseif (trim($this->data["User"]["confirm_password"]) != '') {
                $msgString .= "-Please enter New Password first.<br>";
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {

                $this->request->data['Admin']['first_name'] = trim($this->data['Admin']['first_name']);
                $this->request->data['Admin']['last_name'] = trim($this->data['Admin']['last_name']);
                if ($this->Admin->save($this->data)) {
                    $this->Session->setFlash('Sub admin account updated successfully', 'success_msg');
                    $this->redirect('/admin/admins/manage');
                }
            }
        } else {
            $id = $this->Admin->field('id', array('Admin.slug' => $slug));
            $this->Admin->id = $id;
            $this->data = $this->Admin->read();
            $this->request->data['Admin']['old_username'] = $this->data['Admin']['username'];
            $this->request->data['Admin']['old_email'] = $this->data['Admin']['email'];
            $this->request->data['Admin']['old_password'] = $this->data['Admin']['password'];
        }
    }

    public function admin_deleteadmins($slug = NULL) {
        if ($slug != '') {
            $id = $this->Admin->field('id', array('Admin.slug' => $slug));
            $this->Admin->delete($id);
            $this->Session->setFlash('Sub admin details deleted successfully', 'success_msg');
        }
        $this->redirect('/admin/admins/manage');
    }

    public function admin_activateuser($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Admin->field('id', array('Admin.slug' => $slug));
            $cnd = array("Admin.id = $id");
            $this->Admin->updateAll(array('Admin.status' => "'1'"), $cnd);
            $this->set('action', '/admin/admins/deactivateuser/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivateuser($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Admin->field('id', array('Admin.slug' => $slug));
            $cnd = array("Admin.id = $id");
            $this->Admin->updateAll(array('Admin.status' => "'0'"), $cnd);
            $this->set('action', '/admin/admins/activateuser/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_managerole($slug = null) {

        $this->layout = "admin";
        $this->set('subadmin_list', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $this->set('title_for_layout', $site_title . ' :: ' . $tagline . ' - ' . 'Manage Roles');

        $msgString = '';
        global $extentions;
        if ($this->Session->read("adminid") != 1) {
            $this->redirect(array('controller' => 'admins', 'action' => 'dashboard'));
        }

        if ($this->data) {
            $this->request->data['Admin']['role_ids'] = implode(',', $this->data["Admin"]["role_ids"]);
            $this->request->data['Admin']['sub_role_ids'] = json_encode($this->data["Admin"]["sub_role_ids"]);



            if ($this->Admin->save($this->data)) {
                $this->Session->setFlash('Role assigned successfully', 'success_msg');
                $this->redirect('/admin/admins/manage');
            }
        } else {
            $id = $this->Admin->field('id', array('Admin.slug' => $slug));
            $this->Admin->id = $id;
            $this->data = $this->Admin->read();
        }
    }

}

?>
