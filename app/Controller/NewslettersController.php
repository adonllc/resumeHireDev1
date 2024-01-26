<?php

class NewslettersController extends AppController {

    var $name = 'Newsletters';
    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'Location', 'Newsletter', 'Sendmail', 'Job');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Newsletter.id' => 'asc'));
    public $components = array('RequestHandler', 'Email', 'Upload', 'PImageTest', 'PImage', 'Captcha');
    public $layout = 'admin';

    function beforeFilter() {
        $loggedAdminId = $this->Session->read("adminid");
        if (isset($this->params['admin']) && $this->params['admin'] && !$loggedAdminId) {
            $returnUrlAdmin = $this->params->url;
            $this->Session->write("returnUrlAdmin", $returnUrlAdmin);
            $this->redirect(array('controller' => 'admins', 'action' => 'login', ''));
        }
    }

    /*
     * Newsletter list
     */

    function admin_index() {
        $this->layout = "admin";
//        $this->set("expand_menu_links", "newsletters");
        $this->set('list_newsletter', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Newsletter Template List");

        $condition = array();
        $separator = array();
        $urlSeparator = array();

        if (!empty($this->data)) {

            if (isset($this->data['Newsletter']['subject']) && $this->data['Newsletter']['subject'] != '') {
                $userName = trim($this->data['Newsletter']['subject']);
            }

            if (isset($this->data['Newsletter']['action'])) {
                $idList = $this->data['Newsletter']['idList'];
                if ($idList) {
                    if ($this->data['Newsletter']['action'] == "activate") {
                        $cnd = array("Newsletter.id IN ($idList) ");
                        $this->Newsletter->updateAll(array('Newsletter.status' => "'1'"), $cnd);
                    } elseif ($this->data['Newsletter']['action'] == "deactivate") {
                        $cnd = array("Newsletter.id IN ($idList) ");
                        $this->Newsletter->updateAll(array('Newsletter.status' => "'0'"), $cnd);
                    } elseif ($this->data['Newsletter']['action'] == "delete") {
                        $cnd = array("Newsletter.id IN ($idList) ");
                        $this->Newsletter->deleteAll($cnd);
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
            $condition[] = " (`Newsletter`.`subject` LIKE '%" . addslashes($userName) . "%' ) ";
            $userName = str_replace('\_', '_', $userName);
            $this->set('searchKey', $userName);
        }


        $separator = implode("/", $separator);

        if (!empty($this->passedArgs)) {
            if (isset($this->passedArgs["newsletter"])) {
                $urlSeparator[] = 'newsletter:' . $this->passedArgs["newsletter"];
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
        $this->set('newsletters', $this->paginate('Newsletter', $condition));
        //print_r($newsletters);
        //exit;
        if ($this->RequestHandler->isAjax()) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/newsletters/';
            $this->render('index');
        }
    }

    /*
     * Add Newsletter 
     */

    function admin_addNewsletter() {
        $this->layout = "admin";
        //$this->set("expand_menu_links", "newsletters");

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Newsletter Template");
        $this->set('list_newsletter', 'active');

        $msgString = '';
        $conditions = array();

        if ($this->data) {

            if (empty($this->data["Newsletter"]["subject"])) {
                $msgString .="- Subject is required field.<br>";
            }

            if (empty($this->data["Newsletter"]["message"])) {
                $msgString .="- Message is required field.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {

                $this->request->data['Newsletter']['status'] = '1';
                $this->request->data['Newsletter']['slug'] = $this->stringToSlugUnique($this->data['Newsletter']['subject'], 'Newsletter', 'slug');

                if ($this->Newsletter->save($this->data)) {
                    $this->Session->setFlash('Newsletter added successfully.', 'success_msg');
                    $this->redirect('/admin/newsletters/index');
                }
            }
        }
    }

    /*
     * Edit newsletter
     */

    function admin_editNewsletter($slug = NULL) {
        $this->layout = "admin";
        // $this->set("expand_menu_links", "newsletters");
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Newsletter Template");
        $this->set('list_newsletter', 'active');

        $msgString = '';
        $conditions = array();

        if ($this->data) {

            if (empty($this->data["Newsletter"]["subject"])) {
                $msgString .="- Newsletter title is required field.<br>";
            }

            if (empty($this->data["Newsletter"]["message"])) {
                $msgString .="- Message is required field.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if ($this->Newsletter->save($this->data)) {
                    $this->Session->setFlash('Newsletter details updated successfully.', 'success_msg');
                    $this->redirect('/admin/newsletters/index');
                }
            }
        } elseif ($slug) {
            $id = $this->Newsletter->field('id', array('Newsletter.slug' => $slug));
            $this->Newsletter->id = $id;
            $this->data = $this->Newsletter->read();
        }
    }

    /*
     * activate newsletter 
     */

    public function admin_activateNewsletter($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Newsletter->field('id', array('Newsletter.slug' => $slug));
            $cnd = array("Newsletter.id = $id");
            $this->Newsletter->updateAll(array('Newsletter.status' => "'1'"), $cnd);
            $this->set('action', '/admin/newsletters/deactivateNewsletter/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    /*
     * deactivate newsletter 
     */

    public function admin_deactivateNewsletter($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Newsletter->field('id', array('Newsletter.slug' => $slug));
            $cnd = array("Newsletter.id = $id");
            $this->Newsletter->updateAll(array('Newsletter.status' => "'0'"), $cnd);
            $this->set('action', '/admin/newsletters/activateNewsletter/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    /*
     * Delete newsletter
     */

    function admin_deleteNewsletter($slug = NULL) {
        if ($slug) {

            $id = $this->Newsletter->field('id', array('Newsletter.slug' => $slug));
            $this->Newsletter->delete($id);
            $this->Session->setFlash('Record delete successfully.', 'success_msg');
            $this->redirect('/admin/newsletters/index');
        }
    }

    /*
     * Test newsletter
     */

    function admin_testEmail() {

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');

        $email = $this->data['Newsletter']['email'];
        $slug = $this->data['Newsletter']['slug'];

        $newsletterInfo = $this->Newsletter->findBySlug($slug);

        $this->Email->to = $email;
        $this->Email->subject = $newsletterInfo['Newsletter']['subject'];
        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
        $this->Email->from = $site_title . "<" . $mail_from . ">";
        $this->Email->template = 'send_newsletter';
        $this->Email->layout = 'newsletter';
        $this->Email->sendAs = 'html';
        $this->set('message', $newsletterInfo['Newsletter']['message']);
        $this->set('id', $newsletterInfo['Newsletter']['id']);
        $this->set('email', $email);
        $this->Email->send();

        $this->Session->setFlash('Test email sent successfully.', 'success_msg');
        $this->redirect('/admin/newsletters/editNewsletter/' . $slug);
    }

    /*
     * Send Newsletter mails and mails send by cron contoller
     */

    function admin_sendNewsletter() {


        $this->layout = "admin";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Send Newsletter Template");
        $this->set('send_newsletter', 'active');

        $msgString = '';

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');


        $templates = $this->Newsletter->find('list', array('fields' => array('id', 'subject'), "conditions" => array("Newsletter.status" => 1)));
        $this->set('templates', $templates);

        //$userList = $this->Newslettersubscriber->find('list', array('fields' => array('email', 'email'), "conditions" => array("Newslettersubscriber.status" => 1, "Newslettersubscriber.unsubscribe" => 0), 'order' => array('Newslettersubscriber.email')));
        //$this->set('userList', $userList);
//        $regUserList = $this->User->find('list', array('fields' => array('email_address', 'email_address'), "conditions" => array("User.status" => 1, "User.unsubscribe" => 0), 'order' => array('User.email_address')));
//        $this->set('regUserList', $regUserList);

        $jobseekerUserList = $this->User->find('list', array('fields' => array('email_address', 'email_address'), "conditions" => array("User.status" => 1, "User.unsubscribe" => 0, "User.user_type" => 'candidate'), 'order' => array('User.email_address')));
        $this->set('jobseekerUserList', $jobseekerUserList);

        $employerUserList = $this->User->find('list', array('fields' => array('email_address', 'email_address'), "conditions" => array("User.status" => 1, "User.unsubscribe" => 0, "User.user_type" => 'recruiter'), 'order' => array('User.email_address')));
        $this->set('employerUserList', $employerUserList);

        if (isset($this->data) && !empty($this->data)) {

            if (empty($this->data['Sendmail']['template_id'])) {
                $msgString .="- Please select a Newsletter Template.<br>";
            }
            if (empty($this->data['usertype'])) {
                $msgString.="-Please select any  user type";
            } elseif ($this->data['usertype'] == 'recruiter') {
                if (empty($this->data['Sendmail']['empstatus'])) {
                    $msgString.="-Please select atleast one recipient.";
                } elseif ($this->data['Sendmail']['empstatus'] == 2 && empty($this->data['Sendmail']['employers'])) {
                    $msgString .="- Please select employers to send a Newsletter.<br>";
                }
            } elseif ($this->data['usertype'] == 'candidate') {
                if (empty($this->data['Sendmail']['jobseekstatus'])) {
                    $msgString.="-Please select atleast one recipient.";
                } elseif ($this->data['Sendmail']['jobseekstatus'] == 2 && empty($this->data['Sendmail']['jobseekers'])) {
                    $msgString .="- Please select jobseekers to send a Newsletter.<br>";
                }
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {

                $templateId = $this->data['Sendmail']['template_id'];
                $templateInfo = $this->Newsletter->find('first', array('conditions' => array('id' => $templateId)));

                if ($this->data['usertype'] == 'recruiter') {
                    $receiptType = trim($this->data['Sendmail']['empstatus']);
                    if (!empty($this->data['Sendmail']['employers']))
                        $selectedEmployer = ($this->data['Sendmail']['employers']);
                    switch ($receiptType) {
                        case 1:
                            //$userList = $this->Newslettersubscriber->find('all', array('fields' => array('id', 'email'), "conditions" => array("Newslettersubscriber.status=1", "Newslettersubscriber.unsubscribe=0"), 'order' => array('Newslettersubscriber.id')));
                            $empUserList = $this->User->find('list', array('fields' => array('id', 'email_address'), "conditions" => array("User.status" => 1, "User.unsubscribe" => 0, "User.user_type" => 'recruiter'), 'order' => array('User.email_address')));

                            foreach ($empUserList as $user):
                                //pr($user); exit;
                                $this->request->data['Sendmail']['email'] = trim($user);
                                $this->request->data['Sendmail']['subject'] = trim($templateInfo['Newsletter']['subject']);
                                $this->request->data['Sendmail']['body'] = trim($templateInfo['Newsletter']['message']);
                                $this->Sendmail->create();
                                 //echo"<pre>";  print_r($this->data); exit;
                                $this->Sendmail->save($this->data);
                                $lastSendMailId = $this->Sendmail->id;
                                //mail sent
                                /* $this->Email->to = $user['Newslettersubscriber']['email'];
                                  $this->Email->subject = $templateInfo['Newsletter']['subject'];
                                  $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                                  $this->Email->from = $site_title . "<" . $mail_from . ">";
                                  $this->Email->template = 'send_newsletter';
                                  $this->Email->layout = 'newsletter';
                                  $this->Email->sendAs = 'html';
                                  $this->set('message', $templateInfo['Newsletter']['message']);
                                  $this->set('id', $templateInfo['Newsletter']['id']);
                                  $this->set('email', $user['Newslettersubscriber']['email']);
                                  $this->Email->send();

                                  $current_date = strtotime(date('Y-m-d'));
                                  $this->Sendmail->updateAll(array('Sendmail.is_mail_sent' => "'" . 1 . "'", 'Sendmail.sent_on' => "'" . $current_date . "'"), array('Sendmail.id' => $lastSendMailId)); */



                            endforeach;

                            break;
                        case 2:
                            // echo"<pre>";  print_r($selectedEmployer); exit;
                            foreach ($selectedEmployer as $user):

                                $this->request->data['Sendmail']['email'] = trim($user);
                                $this->request->data['Sendmail']['subject'] = trim($templateInfo['Newsletter']['subject']);
                                $this->request->data['Sendmail']['body'] = trim($templateInfo['Newsletter']['message']);
                                //echo "<pre>"; print_r($user);
                                $this->Sendmail->create();
                                $this->Sendmail->save($this->data);

                                $lastSendMailId = $this->Sendmail->id;
                                //mail sent
                                /* $this->Email->to = $user;
                                  $this->Email->subject = $templateInfo['Newsletter']['subject'];
                                  $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                                  $this->Email->from = $site_title . "<" . $mail_from . ">";
                                  $this->Email->template = 'send_newsletter';
                                  $this->Email->layout = 'newsletter';
                                  $this->Email->sendAs = 'html';
                                  $this->set('message', $templateInfo['Newsletter']['message']);
                                  $this->set('id', $templateInfo['Newsletter']['id']);
                                  $this->set('email', $user);
                                  $this->Email->send();

                                  $current_date = strtotime(date('Y-m-d'));
                                  $this->Sendmail->updateAll(array('Sendmail.is_mail_sent' => "'" . 1 . "'", 'Sendmail.sent_on' => "'" . $current_date . "'"), array('Sendmail.id' => $lastSendMailId)); */

                            endforeach;

                            break;
                    }
                }else if ($this->data['usertype'] == 'candidate') {
                    $receiptType = $this->data['Sendmail']['jobseekstatus'];
                    if (!empty($this->data['Sendmail']['jobseekstatus']))
                        $selectedJobseeker = $this->data['Sendmail']['jobseekers'];
                     // echo"<pre>";  print_r($selectedJobseeker); exit;
                    
                    switch ($receiptType) {
                        case 1:
                            $jobseekUserList = $this->User->find('list', array('fields' => array('id', 'email_address'), "conditions" => array("User.status" => 1, "User.unsubscribe" => 0, "User.user_type" => 'candidate'), 'order' => array('User.email_address')));

                            foreach ($jobseekUserList as $user):
                                $this->request->data['Sendmail']['email'] = trim($user);
                                $this->request->data['Sendmail']['subject'] = trim($templateInfo['Newsletter']['subject']);
                                $this->request->data['Sendmail']['body'] = trim($templateInfo['Newsletter']['message']);
                                $this->Sendmail->create();
                                $this->Sendmail->save($this->data);

                                $lastSendMailId = $this->Sendmail->id;
                                //mail sent
                                /* $this->Email->to = $user['Newslettersubscriber']['email'];
                                  $this->Email->subject = $templateInfo['Newsletter']['subject'];
                                  $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                                  $this->Email->from = $site_title . "<" . $mail_from . ">";
                                  $this->Email->template = 'send_newsletter';
                                  $this->Email->layout = 'newsletter';
                                  $this->Email->sendAs = 'html';
                                  $this->set('message', $templateInfo['Newsletter']['message']);
                                  $this->set('id', $templateInfo['Newsletter']['id']);
                                  $this->set('email', $user['Newslettersubscriber']['email']);
                                  $this->Email->send();

                                  $current_date = strtotime(date('Y-m-d'));
                                  $this->Sendmail->updateAll(array('Sendmail.is_mail_sent' => "'" . 1 . "'", 'Sendmail.sent_on' => "'" . $current_date . "'"), array('Sendmail.id' => $lastSendMailId)); */



                            endforeach;

                            break;
                        case 2:
                                  
                            foreach ($selectedJobseeker as $user):

                                $this->request->data['Sendmail']['email'] = trim($user);
                                $this->request->data['Sendmail']['subject'] = trim($templateInfo['Newsletter']['subject']);
                                $this->request->data['Sendmail']['body'] = trim($templateInfo['Newsletter']['message']);

                                $this->Sendmail->create();
                                $this->Sendmail->save($this->data);

                                $lastSendMailId = $this->Sendmail->id;
                                //mail sent
                                /* $this->Email->to = $user;
                                  $this->Email->subject = $templateInfo['Newsletter']['subject'];
                                  $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                                  $this->Email->from = $site_title . "<" . $mail_from . ">";
                                  $this->Email->template = 'send_newsletter';
                                  $this->Email->layout = 'newsletter';
                                  $this->Email->sendAs = 'html';
                                  $this->set('message', $templateInfo['Newsletter']['message']);
                                  $this->set('id', $templateInfo['Newsletter']['id']);
                                  $this->set('email', $user);
                                  $this->Email->send();

                                  $current_date = strtotime(date('Y-m-d'));
                                  $this->Sendmail->updateAll(array('Sendmail.is_mail_sent' => "'" . 1 . "'", 'Sendmail.sent_on' => "'" . $current_date . "'"), array('Sendmail.id' => $lastSendMailId)); */



                            endforeach;

                            break;
                    }
                }
                //exit;
//end of awitch case

                $this->Session->setFlash('Newsletter sent to the users successfully.', 'success_msg');
                $this->redirect('/admin/newsletters/sentMail');
            }//end of message conditions
        }//end of isset $this->data condition
    }

    /*
     * unsubscribe
     */

    public function unsubscribe($email = null, $md5 = null) {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Unsubscribe");

        //$this->Newslettersubscriber->updateAll(array('Newslettersubscriber.unsubscribe' => '1'), array('Newslettersubscriber.email' => $email));
        $this->User->updateAll(array('User.unsubscribe' => '1'), array('User.email_address' => $email));
        $this->Session->write('success_msg', 'You have successfully unsubscribed from newsletter.');
        //$this->redirect('/#newsletter');
        $this->redirect('/homes/index');
    }

    /*
     * Sent Mail
     */

    function admin_sentMail() {
        //  exit;
        $this->layout = "admin";
        //$this->set("expand_menu_links", "newsletters");
        $this->set('sent_mail', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Sent Mail List");


        $condition = array();
        $separator = array();
        $urlSeparator = array();

        if (!empty($this->data)) {

            if (isset($this->data['Sendmail']['email']) && $this->data['Sendmail']['email'] != '') {
                $email = trim($this->data['Sendmail']['email']);
            }

            if (isset($this->data['Sendmail']['action'])) {
                $idList = $this->data['Sendmail']['idList'];
                if ($idList) {
                    if ($this->data['Sendmail']['action'] == "delete") {
                        $cnd = array("Sendmail.id IN ($idList) ");
                        $this->Sendmail->deleteAll($cnd);
                    }
                }
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['email']) && $this->params['named']['email'] != '') {
                $email = urldecode(trim($this->params['named']['email']));
            }
        }

        if (isset($email) && $email != '') {
            $separator[] = 'email:' . urlencode($email);
            $email = str_replace('_', '\_', $email);
            $condition[] = " (`Sendmail`.`email` LIKE '%" . addslashes($email) . "%' ) ";
            $email = str_replace('\_', '_', $email);
            $this->set('searchKey', $email);
        }


        $separator = implode("/", $separator);

        if (!empty($this->passedArgs)) {
            if (isset($this->passedArgs["sendmail"])) {
                $urlSeparator[] = 'sendmail:' . $this->passedArgs["sendmail"];
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
        $order = 'Sendmail.id Desc';
        $this->paginate['Sendmail'] = array('conditions' => $condition, 'limit' => '100', 'page' => '1', 'order' => $order);
        $this->set('sendmails', $this->paginate('Sendmail'));
        // exit;
        //print_r($newsletters);
        //exit;
        if ($this->RequestHandler->isAjax()) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/newsletters';
            $this->render('sentmail');
        }
    }

    // end of function admin_editNewsletter

    function admin_deleteSendMail($id = NULL) {
        if ($id) {
            $this->Sendmail->delete($id);
            $this->Session->setFlash('Record delete successfully.', 'success_msg');
            $this->redirect('/admin/newsletters/sentMail');
        }
    }

    /*
     * Sent Mail
     */

    function admin_unsubscriberlist() {
        //  exit;
        $this->layout = "admin";
        //$this->set("expand_menu_links", "newsletters");
        $this->set('unsubscriberlist', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Unsubscriber List");


        $condition = array('User.unsubscribe' => '1');
        $separator = array();
        $urlSeparator = array();

        if (!empty($this->data)) {

            if (isset($this->data['User']['email_address']) && $this->data['User']['email_address'] != '') {
                $email = trim($this->data['User']['email_address']);
            }

            if (isset($this->data['User']['action'])) {
                $idList = $this->data['User']['idList'];
                if ($idList) {
                    if ($this->data['User']['action'] == "delete") {
                        $cnd = array("User.id IN ($idList) ");
                        //$this->User->deleteAll($cnd);
                    }
                }
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['email_address']) && $this->params['named']['email_address'] != '') {
                $email = urldecode(trim($this->params['named']['email_address']));
            }
        }

        if (isset($email) && $email != '') {
            $separator[] = 'email_address:' . urlencode($email);
            $email = str_replace('_', '\_', $email);
            $condition[] = " (`User`.`email_address` LIKE '%" . addslashes($email) . "%' ) ";
            $email = str_replace('\_', '_', $email);
            $this->set('searchKey', $email);
        }


        $separator = implode("/", $separator);

        if (!empty($this->passedArgs)) {
            if (isset($this->passedArgs["user"])) {
                $urlSeparator[] = 'user:' . $this->passedArgs["user"];
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
        $order = 'User.id Desc';
        $this->paginate['User'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('users', $this->paginate('User'));
        // exit;
        //print_r($newsletters);
        //exit;
        if ($this->RequestHandler->isAjax()) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/newsletters';
            $this->render('unsubscriberlist');
        }
    }

}



?>
