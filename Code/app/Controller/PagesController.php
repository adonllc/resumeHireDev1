<?php

class PagesController extends AppController {

    public $uses = array('Admin', 'Page', 'Emailtemplate', 'Setting');
    public $helpers = array('Html', 'Form', 'Fck', 'Javascript', 'Ajax', 'Text', 'Number');
    public $paginate = array('limit' => '10', 'page' => '1', 'order' => array('Page.static_page_title' => 'asc'));
    public $components = array('RequestHandler', 'Email', 'Captcha');
    public $layout = 'admin';

    function beforeFilter() {
        $loggedAdminId = $this->Session->read("adminid");
        if (isset($this->params['admin']) && $this->params['admin'] && !$loggedAdminId) {
            $returnUrlAdmin = $this->params->url;
            $this->Session->write("returnUrlAdmin", $returnUrlAdmin);
            $this->redirect(array('controller' => 'admins', 'action' => 'login', ''));
        }
    }

    public function admin_index() {

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Pages List");

        $this->set('default', '1');
        $this->layout = "admin";
        $this->set('page_list', 'active');
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
        $this->set('staticpages', $this->paginate('Page', $condition));
        //pr($this->paginate('Page', $condition));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/page/';
            $this->render('index');
        }
    }

    public function admin_editPage($title = null) {
        $this->layout = "admin";
        $this->set('default', '1');
        $this->set('page_list', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Page");
        $msgString = '';
        if ($this->data) {

            if (trim($this->data["Page"]["static_page_title"]) == '') {
                $msgString .= "- Page title is required field.<br>";
            }

            if (strtolower($this->data["Page"]["pageOldName"]) != strtolower(trim($this->data["Page"]["static_page_title"]))) {
                $new_slug = $this->data['Page']['static_page_title'];
                if ($this->Page->isRecordUniquepage($new_slug) == false) {
                    $msgString .="- Page name already exists.<br>";
                }
            }
            $description = str_replace("&nbsp;", '', $this->data["Page"]["static_page_description"]);
            $page_des = trim($description);
            if ($page_des == '' || $page_des == '<p></p>' || $page_des == '<p> </p>') {
                $msgString .="- Page description is required field.<br>";
            }



            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data["Page"]["static_page_title"] = trim($this->data["Page"]["static_page_title"]);
                $this->request->data["Page"]["static_page_description"] = trim($this->data["Page"]["static_page_description"]);

                if ($this->Page->save($this->data)) {
                    $this->Session->setFlash('Page details updated successfully', 'success_msg');
                    $this->redirect('/admin/pages/index');
                }
            }
        } elseif ($title != '') {
            $page = $this->Page->find('first', array('conditions' => array('Page.static_page_heading' => $title), 'fields' => array('id')));
            //print_r($card);exit;
            $this->Page->id = $page['Page']['id'];
            $this->data = $this->Page->read();
            $this->request->data["Page"]["pageOldName"] = $this->data["Page"]["static_page_title"];
        }
    }

    public function admin_deletepage($id = NULL) {
        if ($id > 0) {
            $this->Page->delete($id);
            $this->viewPath = 'elements' . DS . 'admin';
            $this->Session->setFlash('Page deleted successfully', 'success_msg');
            $this->redirect('/admin/pages/index');
        }
    }

    public function admin_preview() {
        //print_r($this->data);exit;
        $this->layout = 'ajax';
        $this->set('description', $this->data['Page']['static_page_description']);
    }

    public function underconstruction() {
        $this->layout = "";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Undercounstruction");
    }

    public function detail() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Undercounstruction");
    }

    public function staticpage($slugPage = null) {
        // use it for routing purpose

        $pageUrlData = explode('.', $slugPage);
        $page = $pageUrlData[0];


        // pr($page); exit;

        $this->layout = "client";

        $this->set('header_act', $page);
        if ($page == 'how-it-works') {
            $this->set('how_it_works', 'active');
        }
        if ($page == 'faq') {
            $this->set('faq_active', 'active');
        }

        $condition = array('conditions' => array('Page.static_page_heading' => $page));
        $pagedetails = $this->Page->find('first', $condition);
        if (empty($pagedetails)) {
            $this->redirect('/homes/error');
        }

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";


        $this->set('title_for_layout', $title_for_pages . $pagedetails['Page']['static_page_title']);
        
          $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');


        $contact_details = $this->Setting->find('first');
        //pr($contact_details); exit;
        $this->set('contact_details', $contact_details);
        $msgString ='';
        if ($this->data) {

            if (empty($this->data["User"]["name"])) {
                $msgString .= __d('controller', 'Name is required field.', true) . "<br>";
            }
            if (empty($this->data["User"]["email"])) {
                $msgString .= __d('controller', 'Email Address is required field.', true) . "<br>";
            } elseif ($this->User->checkEmail($this->data["User"]["email"]) == false) {
                $msgString .= __d('controller', 'Email Address is not valid.', true) . "<br>";
            }
            if (empty($this->data["User"]["subject"])) {
                $msgString .= __d('controller', 'Subject is required field.', true) . "<br>";
            }
            $this->request->data["User"]["message"] = trim($this->data["User"]["message"]);
            if (empty($this->data["User"]["message"])) {
                $msgString .= __d('controller', 'Message is required field.', true) . "<br>";
            }
          
            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
//                pr($this->data);die;

                $username = $this->data["User"]["name"];
                $email = $this->data["User"]["email"];
                $message = $this->data["User"]["message"];
                $subjectbyuser = $this->data["User"]["subject"];
                $currentYear = date('Y', time());
                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                $this->Email->to = $contact_details['Setting']['email'];
                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='6'"));
                //$this->Email->subject = $this->data["User"]['subject'];
                $toSubArray = array('[!username!]', '[!email!]', '[!subjectuser!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
                $fromSubArray = array($username, $email, $subjectbyuser, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                $this->Email->subject = $subjectToSend;


                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";

                $toRepArray = array('[!username!]', '[!email!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
                $fromRepArray = array($username, $email, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
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
                $fromSubArray = array($username, $email, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                $this->Email->subject = $subjectToSend;


                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";

                $toRepArray = array('[!username!]', '[!email!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
                $fromRepArray = array($username, $email, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                $this->Email->layout = 'default';
                $this->set('messageToSend', $messageToSend);
                $this->Email->template = 'email_template';
                $this->Email->sendAs = 'html';
                $this->Email->send();
                $this->Session->setFlash(__d('controller', 'Your enquiry has been successfully sent to us!', true), 'success_msg');
//                $this->request->data = array();
//                $this->request->data['User']['email'] = $this->Session->read('email_address');
//                $this->request->data['User']['name'] = $this->Session->read('user_name');
//                $this->redirect('/thank_you');
                $this->redirect(array('controller' => 'pages', 'action' => 'staticpage/'.$slugPage));
            }
        } else {
            if ($this->Session->read('user_id') != '') {
//                $this->User->id = $this->Session->read('user_id');
//                $this->data = $this->User->read();
                $this->request->data['User']['email'] = $this->Session->read('email_address');
                $this->request->data['User']['name'] = $this->Session->read('user_name');
            }
        }

        $this->set('pagedetails', $pagedetails);
    }

    public function site_link() {
        $this->layout = "client";
    }

    function getPageDetail($page_name = null) {
        $condition = array('conditions' => array('Page.static_page_heading' => $page_name));
        return $this->Page->find('first', $condition);
    }

    public function convertHeading($string = null) {

        $specialCharacters = array('#', '$', '%', '@', '.', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', ' ');
        $toReplace = "-";
        $string = str_replace($specialCharacters, $toReplace, $string);
        $replace = str_replace("&", "and", $string);
        return strtolower($replace);
    }

    public function index($page = null) {

        $this->layout = "client";
        $this->set('slug', $page);

        $condition = array('conditions' => array('Page.static_page_heading' => $page));
        $pagedetails = $this->Page->find('first', $condition);
        $lang = $_SESSION['Config']['language'];

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";

        $this->set('title_for_layout', $title_for_pages . $pagedetails['Page']['static_page_title']);

        $this->set('pagedetails', $pagedetails);
        if ($page == 'about-us') {
            $this->set('topactive', 'aboutus');
        }
        if ($page == 'connect') {
            $this->set('active', 'connect');
        }
        if ($page == 'our-fabric') {
            $this->set('active', 'ourfabric');
        }
        if ($page == 'privacy-policy') {
            //$this->set('topactive', '');
        }
        if ($page == 'under') {
            $this->set('under', 1);
        }
        $this->layout = "client";
        $this->set('slug', $page);
        $this->set('header_act', $page);
        $condition = array('conditions' => array('Page.static_page_heading' => $page));
        $pagedetails = $this->Page->find('first', $condition);
        $this->set('page_set', $pagedetails['Page']['static_page_heading']);

        if (!empty($pagedetails)) {
            $this->set('title_for_layout', $title_for_pages . $pagedetails['Page']['static_page_title']);
            $this->set('pagedetails', $pagedetails);
        } else {
            $this->set('title_for_layout', $title_for_pages . 'Page not found');
            $this->set('pagedetails', '');
            $this->set('under', '1');
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

    public function sendmessage() {
        $msgString = '';
        $this->layout = "client";
        $this->set("active", "contact_us");
        $this->set("top_active", "contact_us");
        $this->set('active', 'contact_us');
        $this->set('contact_us', 'active');

        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";

        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Contact Us', true));

        $contact_details = $this->Setting->find('first');
        //pr($contact_details); exit;
        $this->set('contact_details', $contact_details);

        if ($this->data) {

            if (empty($this->data["User"]["name"])) {
                $msgString .= __d('controller', 'Name is required field.', true) . "<br>";
            }
            if (empty($this->data["User"]["email"])) {
                $msgString .= __d('controller', 'Email Address is required field.', true) . "<br>";
            } elseif ($this->User->checkEmail($this->data["User"]["email"]) == false) {
                $msgString .= __d('controller', 'Email Address is not valid.', true) . "<br>";
            }
            if (empty($this->data["User"]["subject"])) {
                $msgString .= __d('controller', 'Subject is required field.', true) . "<br>";
            }
            $this->request->data["User"]["message"] = trim($this->data["User"]["message"]);
            if (empty($this->data["User"]["message"])) {
                $msgString .= __d('controller', 'Message is required field.', true) . "<br>";
            }
          
            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                pr($this->data);die;

                $username = $this->data["User"]["name"];
                $email = $this->data["User"]["email"];
                $message = $this->data["User"]["message"];
                $subjectbyuser = $this->data["User"]["subject"];
                $currentYear = date('Y', time());
                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                $this->Email->to = $contact_details['Setting']['email'];
                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='6'"));
                //$this->Email->subject = $this->data["User"]['subject'];
                $toSubArray = array('[!username!]', '[!email!]', '[!subjectuser!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
                $fromSubArray = array($username, $email, $subjectbyuser, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                $this->Email->subject = $subjectToSend;


                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";

                $toRepArray = array('[!username!]', '[!email!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
                $fromRepArray = array($username, $email, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
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
                $fromSubArray = array($username, $email, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                $this->Email->subject = $subjectToSend;


                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";

                $toRepArray = array('[!username!]', '[!email!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
                $fromRepArray = array($username, $email, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                $this->Email->layout = 'default';
                $this->set('messageToSend', $messageToSend);
                $this->Email->template = 'email_template';
                $this->Email->sendAs = 'html';
                $this->Email->send();
                $this->Session->setFlash(__d('controller', 'Your enquiry has been successfully sent to us!', true), 'success_msg');
//                $this->request->data = array();
//                $this->request->data['User']['email'] = $this->Session->read('email_address');
//                $this->request->data['User']['name'] = $this->Session->read('user_name');
//                $this->redirect('/thank_you');
                $this->redirect(array('controller' => 'pages', 'action' => 'contactUs'));
            }
        } else {
            if ($this->Session->read('user_id') != '') {
//                $this->User->id = $this->Session->read('user_id');
//                $this->data = $this->User->read();
                $this->request->data['User']['email'] = $this->Session->read('email_address');
                $this->request->data['User']['name'] = $this->Session->read('user_name');
            }
        }
    }

    public function contactUs() {
        $msgString = '';
        $this->layout = "client";
      //  App::import('Component', 'Captcha');
        $this->set("active", "contact_us");
        $this->set("top_active", "contact_us");
        $this->set('active', 'contact_us');
        $this->set('contact_us', 'active');

        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";

        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Contact Us', true));
        global $extentions_file;
      //  $this->Captcha = new CaptchaComponent();
       // $this->Captcha->startup($this);

        $contact_details = $this->Setting->find('first');
        //pr($contact_details); exit;
        $this->set('contact_details', $contact_details);

        if ($this->data) {

            if (empty($this->data["User"]["name"])) {
                $msgString .= __d('controller', 'Name is required field.', true) . "<br>";
            }
            if (empty($this->data["User"]["email"])) {
                $msgString .= __d('controller', 'Email Address is required field.', true) . "<br>";
            } elseif ($this->User->checkEmail($this->data["User"]["email"]) == false) {
                $msgString .= __d('controller', 'Email Address is not valid.', true) . "<br>";
            }
            if (empty($this->data["User"]["subject"])) {
                $msgString .= __d('controller', 'Subject is required field.', true) . "<br>";
            }
            $this->request->data["User"]["message"] = trim($this->data["User"]["message"]);
            if (empty($this->data["User"]["message"])) {
                $msgString .= __d('controller', 'Message is required field.', true) . "<br>";
            }
           // $captcha = $this->Captcha->getVerCode();
            // if ($this->data['User']['captcha'] == "") {
            //     $msgString .= __d('controller', 'Please Enter security code.', true) . "<br>";
            // } elseif ($this->data['User']['captcha'] != $captcha) {
            //     $msgString .= __d('controller', 'Please Enter correct security code.', true) . "<br>";
            // }

            if(isset( $this->request->data['g-recaptcha-response'])){
                $captcha= $this->request->data['g-recaptcha-response'];

            }
    
           // print_r($captcha);exit;
        if(!$captcha){

           $msgString .= __d('controller', 'Please check the captcha.', true) . "<br>";

        }
            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {


                $username = $this->data["User"]["name"];
                $email = $this->data["User"]["email"];
                $message = $this->data["User"]["message"];
                $subjectbyuser = $this->data["User"]["subject"];
                $currentYear = date('Y', time());
                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                $this->Email->to = $contact_details['Setting']['email'];
                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='6'"));
                //$this->Email->subject = $this->data["User"]['subject'];
                $toSubArray = array('[!username!]', '[!email!]', '[!subjectuser!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
                $fromSubArray = array($username, $email, $subjectbyuser, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                $this->Email->subject = $subjectToSend;


                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";

                $toRepArray = array('[!username!]', '[!email!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
                $fromRepArray = array($username, $email, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
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
                $fromSubArray = array($username, $email, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                $this->Email->subject = $subjectToSend;


                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";

                $toRepArray = array('[!username!]', '[!email!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
                $fromRepArray = array($username, $email, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                $this->Email->layout = 'default';
                $this->set('messageToSend', $messageToSend);
                $this->Email->template = 'email_template';
                $this->Email->sendAs = 'html';
                $this->Email->send();
                $this->Session->setFlash(__d('controller', 'Your enquiry has been successfully sent to us!', true), 'success_msg');
//                $this->request->data = array();
//                $this->request->data['User']['email'] = $this->Session->read('email_address');
//                $this->request->data['User']['name'] = $this->Session->read('user_name');
//                $this->redirect('/thank_you');
                $this->redirect(array('controller' => 'pages', 'action' => 'contactUs'));
            }
        } else {
            if ($this->Session->read('user_id') != '') {
//                $this->User->id = $this->Session->read('user_id');
//                $this->data = $this->User->read();
                $this->request->data['User']['email'] = $this->Session->read('email_address');
                $this->request->data['User']['name'] = $this->Session->read('user_name');
            }
        }
    }

    public function thank_you() {
        $this->layout = "client";
        $pageContent = $this->Page->find("first", array("conditions" => "Page.static_page_heading='thank_you'"));
        $this->set("pageContent", $pageContent);
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . $pageContent['Page']['static_page_title']);
    }

    public function terms_and_conditions() {

        $this->layout = "";
        $pageContent = $this->Page->find("first", array("conditions" => "Page.static_page_heading='terms-and-conditions'"));
        $this->set("pageContent", $pageContent);
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . $pageContent['Page']['static_page_title']);
        // pr($pageContent);exit;
    }

    public function terms_and_condition() {

        $this->layout = "";
        $pageContent = $this->Page->find("first", array("conditions" => "Page.static_page_heading='terms-and-conditions'"));
        $this->set("pageContent", $pageContent);
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . $pageContent['Page']['static_page_title']);
        // pr($pageContent);exit;
    }

    public function privacy_policy() {
        $this->layout = "";
        $pageContent = $this->Page->find("first", array("conditions" => "Page.static_page_heading='privacy-policy'"));
        $this->set("pageContent", $pageContent);
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . $pageContent['Page']['static_page_title']);
        //print_r($pageContent);exit;
    }

    public function career_resources() {
        $this->layout = "";
        $pageContent = $this->Page->find("first", array("conditions" => "Page.static_page_heading='career-resources'"));
        $this->set("pageContent", $pageContent);
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . $pageContent['Page']['static_page_title']);
        //print_r($pageContent);exit;
    }

    public function career_tools() {
        $this->layout = "";
        $pageContent = $this->Page->find("first", array("conditions" => "Page.static_page_heading='career-tools'"));
        $this->set("pageContent", $pageContent);
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . $pageContent['Page']['static_page_title']);
        //print_r($pageContent);exit;
    }

    public function about_us() {
        $this->layout = "client";
         $this->set('about_active','active');
        $pageContent = $this->Page->find("first", array("conditions" => "Page.static_page_heading='about-us'"));
        $this->set("pageContent", $pageContent);
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . $pageContent['Page']['static_page_title']);
        //print_r($pageContent);exit;
    }

    public function advertising() {
        $this->layout = "client";
        $pageContent = $this->Page->find("first", array("conditions" => "Page.static_page_heading='advertising'"));
        $this->set("pageContent", $pageContent);
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . $pageContent['Page']['static_page_title']);
        //print_r($pageContent);exit;
    }

    public function links() {
        $this->layout = "client";
        $pageContent = $this->Page->find("first", array("conditions" => "Page.static_page_heading='links'"));
        $this->set("pageContent", $pageContent);
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . $pageContent['Page']['static_page_title']);
        //print_r($pageContent);exit;
    }

    public function faq() {
        $this->layout = "";
        $pageContent = $this->Page->find("first", array("conditions" => "Page.static_page_heading='faq'"));
        $this->set("pageContent", $pageContent);
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";

        $this->set("title_for_layout", $title_for_pages . $pageContent['Page']['static_page_title']);
        //print_r($pageContent);exit;
    }

    public function latest() {
        $this->layout = "";
        $PageList = $this->Page->find('all', array('fields' => array('Page.static_page_title', 'Page.static_page_heading'), 'order' => array('Page.id asc'), 'limit' => '5'));
        return $PageList;
    }

    public function sitemap() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . 'Site Map');
    }

    public function jobs_detial_public() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . 'Site Map');
    }

    public function admin_activatepage($id = NULL) {
        $this->layout = "";
        if ($id != '') {
            $cnd = array("Page.id = $id");
            $this->Page->updateAll(array('Page.status' => "'1'"), $cnd);
            $this->set('action', '/admin/pages/deactivatepage/' . $id);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivatepage($id = NULL) {
        $this->layout = "";
        if ($id != '') {
            $cnd = array("Page.id = $id");
            $this->Page->updateAll(array('Page.status' => "'0'"), $cnd);
            $this->set('action', '/admin/pages/activatepage/' . $id);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function signpage() {
        $this->layout = "client";
    }

}

?>