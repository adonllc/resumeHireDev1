<?php

class PaymentsController extends AppController {

    public $uses = array('Payment', 'Admin', 'Job', 'Emailtemplate', 'User', 'JobNotification', 'UserPlan','ProfileView');
    public $helpers = array('Html', 'Form', 'Fck', 'Javascript', 'Ajax', 'Text', 'Number', 'Js', 'Time');
    public $paginate = array('limit' => '20', 'page' => '1', 'order' => array('Payment.created' => 'DESC'));
    public $components = array('RequestHandler', 'Email', 'Upload', 'PImageTest', 'PImage', 'Captcha');
    public $layout = 'admin';

    function beforeFilter() {
        $loggedAdminId = $this->Session->read("adminid");
        if (isset($this->params['admin']) && $this->params['admin'] && !$loggedAdminId) {
            $this->redirect("/admin/admins/login");
        }
    }

    public function admin_history() {
        $this->layout = "admin";
        $this->set('payment_list', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "List Payment History");

        $condition = array();
        $separator = array();
        $urlSeparator = array();
        $name = '';
        $searchByDateFrom = '';
        $searchByDateTo = '';

        if (!empty($this->data)) {
            if (isset($this->data['Payment']['name']) && $this->data['Payment']['name'] != '') {
                $name = trim($this->data['Payment']['name']);
            }
            if (isset($this->data['Payment']['searchByDateFrom']) && $this->data['Payment']['searchByDateFrom'] != '') {
                $searchByDateFrom = trim($this->data['Payment']['searchByDateFrom']);
            }

            if (isset($this->data['Payment']['searchByDateTo']) && $this->data['Payment']['searchByDateTo'] != '') {
                $searchByDateTo = trim($this->data['Payment']['searchByDateTo']);
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['name']) && $this->params['named']['name'] != '') {
                $name = urldecode(trim($this->params['named']['name']));
            }
            if (isset($this->params['named']['searchByDateFrom']) && $this->params['named']['searchByDateFrom'] != '') {
                $searchByDateFrom = urldecode(trim($this->params['named']['searchByDateFrom']));
            }
            if (isset($this->params['named']['searchByDateTo']) && $this->params['named']['searchByDateTo'] != '') {
                $searchByDateTo = urldecode(trim($this->params['named']['searchByDateTo']));
            }
        }
        if (isset($name) && $name != '') {
            $separator[] = 'name:' . urlencode($name);
            $condition[] = " (`User`.`first_name` like '%" . addslashes($name) . "%' or concat(`User.first_name`,' ',`User.last_name`) LIKE '%" . addslashes($name) . "%' or `User`.`company_name` like '%" . addslashes($name) . "%' or `User`.`last_name` like '%" . addslashes($name) . "%' or `Plan`.`plan_name` like '%" . addslashes($name) . "%' or `Payment`.`transaction_id` like '%" . addslashes($name) . "%' ) ";
        }

        if (isset($searchByDateFrom) && $searchByDateFrom != '') {

            $separator[] = 'searchByDateFrom:' . urlencode($searchByDateFrom);
            $searchByDateFrom = str_replace('_', '\_', $searchByDateFrom);
            $searchByDate_con1 = date('Y-m-d', strtotime($searchByDateFrom));
            $condition[] = " (Date(UserPlan.created)>='$searchByDate_con1' ) ";
            $searchByDateFrom = str_replace('\_', '_', $searchByDateFrom);
        }

        if (isset($searchByDateTo) && $searchByDateTo != '') {

            $separator[] = 'searchByDateTo:' . urlencode($searchByDateTo);
            $searchByDateTo = str_replace('_', '\_', $searchByDateTo);
            $searchByDate_con2 = date('Y-m-d', strtotime($searchByDateTo));
            $condition[] = " (Date(UserPlan.created)<='$searchByDate_con2' ) ";
            $searchByDateTo = str_replace('\_', '_', $searchByDateTo);
        }


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

        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->set('searchByDateFrom', $searchByDateFrom);
        $this->set('searchByDateTo', $searchByDateTo);
        $this->set('name', $name);
        $this->set('searchKey', $name);


        $this->paginate['UserPlan'] = array(
            'conditions' => $condition,
            'order' => array('UserPlan.id' => 'DESC'),
            'limit' => '50'
        );

        $this->set('payments', $this->paginate('UserPlan'));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/payments/';
            $this->render('history');
        }
    }

    public function paynow($slug = null) {

        $this->recruiterAccess();

        $jobInfo = $this->Job->findBySlug($slug);
        $jobNumber = $jobInfo['Job']['job_number'];
        $amount = $jobInfo['Job']['amount_paid'] - $jobInfo['Job']['dis_amount'];

        $amount = number_format($amount, 2);
        $gstAmount = $amount * 10 / 100;
        $amount = $amount + $gstAmount;

        $this->layout = "";

        $site_url = $this->getSiteConstant('url');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Payment Process');

        $this->set('success_url', HTTP_PATH . '/payments/success/' . $jobNumber);
        $this->set('notify_url', HTTP_PATH . '/payments/ipnNotification/' . $jobNumber);
        $this->set('cancel_url', HTTP_PATH . '/payments/cancel/' . $jobNumber);

        $this->set('amount', $amount);
        $this->set('item_number', $jobNumber);
    }

    public function success($jobNumber = null) {
        $this->layout = 'client';

        $site_title = $this->getSiteConstant('title');
        $mail_from = $this->getMailConstant('from');

        $jobInfo = $this->Job->find('first', array('conditions' => array('Job.job_number' => $jobNumber)));
        if ($jobInfo['Job']['payment_status'] == 0) {
            $this->Job->updateAll(array('Job.payment_status' => '1'), array('Job.id' => $jobInfo['Job']['id']));
        }
        $email = $jobInfo["User"]["email_address"];
        $name = $jobInfo["User"]["first_name"];
        $jobTitle = $jobInfo['Job']['title'];


        $currentYear = date('Y', time());

        $this->Email->to = $email;
        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='30'"));
        $toSubArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]');
        $fromSubArray = array($name, $jobTitle, $site_title, $currentYear);
        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
        $this->Email->subject = $subjectToSend;

        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
        $this->Email->from = $site_title . "<" . $mail_from . ">";

        $toRepArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]');
        $fromRepArray = array($name, $jobTitle, $site_title, $currentYear);
        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
        $this->Email->layout = 'default';
        $this->set('messageToSend', $messageToSend);
        $this->Email->template = 'email_template';
        $this->Email->sendAs = 'html';
        $this->Email->send();


        $this->Session->write('success_msg', __d('controller', 'Your job posted successfully and automatically activated once PayPal confirmed your payment transaction.', true));
        $this->redirect('/jobs/management');
    }

    public function cancel($jobNumber = null) {
        $this->Session->write('error_msg', __d('controller', 'Sorry, your payment could not be completed, please try again', true));
        $this->redirect('/jobs/management');
    }

    public function ipnNotification($jobNumber = null) {

        $site_title = $this->getSiteConstant('title');
        $mail_from = $this->getMailConstant('from');

        if (!empty($_REQUEST) && !empty($_REQUEST['item_number']) && $jobNumber != '') {
            //if(1){

            if ($_REQUEST['txn_id']) {
                $transactionId = $_REQUEST['txn_id'];
                $amountPaid = $_REQUEST['mc_gross'];
            } elseif ($_REQUEST['tx']) {
                $transactionId = $_REQUEST['tx'];
                $amountPaid = $_REQUEST['amt'];
            }

            $st = $_REQUEST['st'];
            $payer_id = $_REQUEST['payer_id'];
            $subscr_id = $_REQUEST['subscr_id']; ///Profile ID
            $sig = $_REQUEST['sig'];


//            $transactionId = 'SHDU5478SSSS58746';
//            $st = 'completed';
//            $payer_id = 45456465;
//            $subscr_id = 889789;
//            $sig = 'asdasjk789789798';
//            $amountPaid = 100;

            if ($transactionId) {

                $jobInfo = $this->Job->find('first', array('conditions' => array('Job.job_number' => $jobNumber, 'payment_status' => array('0', '1'))));

                if ($jobInfo) {

                    $this->request->data['Payment']['job_id'] = $jobInfo['Job']['id'];
                    $this->request->data['Payment']['user_id'] = $jobInfo['User']['id'];
                    $this->request->data['Payment']['payment_status'] = 'Completed';
                    $this->request->data['Payment']['payer_id'] = $payer_id;
                    $this->request->data['Payment']['subscr_id'] = $subscr_id;
                    $this->request->data['Payment']['signature'] = $sig;
                    $this->request->data['Payment']['price'] = $amountPaid;
                    $this->request->data['Payment']['transaction_id'] = $transactionId;
                    $this->request->data['Payment']['slug'] = 'payment-' . $jobNumber . '-' . time();
                    $this->request->data['Payment']['status'] = 1;
                    $this->request->data['Payment']['dis_amount'] = $jobInfo['Job']['dis_amount'];
                    $this->request->data['Payment']['promo_code'] = $jobInfo['Job']['promo_code'];

                    $this->Payment->save($this->data['Payment']);

                    $this->Job->updateAll(array('Job.payment_status' => '2', 'Job.status' => '1'), array('Job.id' => $jobInfo['Job']['id']));

                    $jobCategory = $jobInfo['Job']['category_id'];
                    $jobTitle = $jobInfo['Job']['title'];
                    $jobId = $jobInfo['Job']['id'];
                    $condition[] = " FIND_IN_SET(" . $jobCategory . ",User.email_notification_id)";
                    $usersIds = $this->User->find('all', array('conditions' => $condition, 'fields' => array('User.first_name', 'User.email_address', 'User.id')));
                    $week = 1;
                    if ($jobInfo['Job']['type'] == 'silver') {
                        $week = 2;
                    } elseif ($jobInfo['Job']['type'] == 'gold') {
                        $week = 4;
                    }

                    if ($usersIds) {
                        foreach ($usersIds as $usersIdVV) {
                            $this->request->data['JobNotification']['id'] = '';
                            $this->request->data['JobNotification']['user_id'] = $usersIdVV['User']['id'];
                            $this->request->data['JobNotification']['job_id'] = $jobId;
                            $this->request->data['JobNotification']['first_name'] = $usersIdVV['User']['first_name'];
                            $this->request->data['JobNotification']['email_address'] = $usersIdVV['User']['email_address'];
                            $this->request->data['JobNotification']['last_email_sent_time'] = 0;
                            $this->request->data['JobNotification']['expire_time'] = time() + $week * 7 * 24 * 3600;
                            $this->request->data['JobNotification']['week'] = $week;
                            $this->JobNotification->save($this->data);
                        }
                    }

                    $email = $jobInfo["User"]["email_address"];
                    $name = $jobInfo["User"]["first_name"];
                    $jobTitle = $jobInfo["Job"]["title"];
                    $companyname = $jobInfo["User"]["company_name"];
                    $link = HTTP_PATH . '/jobs/management';


                    $date = date('F d, Y');

                    $this->Email->to = $email;
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='31'"));
                    $toSubArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]', '[!transactionId!]', '[!amountPaid!]', '[!SITE_LINK!]');
                    $fromSubArray = array($name, $jobTitle, $site_title, $date, $transactionId, $amountPaid, $link);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]', '[!transactionId!]', '[!amountPaid!]', '[!SITE_LINK!]');
                    $fromRepArray = array($name, $jobTitle, $site_title, $date, $transactionId, $amountPaid, $link);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();



                    $adminInfo = $this->Admin->findById(1);

                    $this->Email->to = $adminInfo['Admin']['email'];
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='32'"));
                    $toSubArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]', '[!transactionId!]', '[!amountPaid!]', '[!company_name!]');
                    $fromSubArray = array($name, $jobTitle, $site_title, $date, $transactionId, $amountPaid, $companyname);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]', '[!transactionId!]', '[!amountPaid!]', '[!company_name!]');
                    $fromRepArray = array($name, $jobTitle, $site_title, $date, $transactionId, $amountPaid, $companyname);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                }
            }
        }

        exit;
    }

    

    public function payment_new($buCode = null) {
        $this->layout = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');

        $this->set('title_for_layout', $site_title . ' :: ' . __d('common', $tagline) . ' - ' . __d('common', 'Payment Process'));

        $this->set('success_url', HTTP_PATH . '/memberships/success_new/' . $buCode . '/' . md5($buCode));
        $this->set('notify_url', HTTP_PATH . '/memberships/recurring_confirm/' . $buCode . '/' . md5($buCode));
        $this->set('cancel_url', HTTP_PATH . '/memberships/cancel_new/' . $buCode . '/' . md5($buCode));

        $amount = $this->Admin->getBMAmount();
        $this->set('amount', $amount);
        $this->set('item_number', $buCode);
    }

    public function success_new($buCode = null, $md5buCode = null) {

        $this->layout = 'client';
        $userInfo = $this->User->find('first', array('conditions' => array('User.business_code' => $buCode)));

        $site_title = $this->getSiteConstant('title');
        $mail_from = $this->getMailConstant('from');

        $email = $userInfo["User"]["email_address"];
        $name = $userInfo["User"]["first_name"];
        $bu_name = $userInfo["User"]["business_name"];
        $password = $_SESSION['password'];

        $this->Email->to = $email;
        $this->Email->subject = "You have successfully purchase business account membership  on " . $site_title;
        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
        $this->Email->from = $site_title . "<" . $mail_from . ">";
        $this->Email->layout = 'default';
        $this->Email->template = 'business_membership_renew';
        $this->Email->sendAs = 'html';

        $this->set('name', $name);
        $this->set('email', $email);
        $this->set('bu_name', $bu_name);
        $this->set('password', $password);
        $this->Email->send();

        $this->Session->write('success_msg', 'You have successfully purchase business account membership and updated on site once PayPal confirmed your transaction.');
        $this->redirect('/businesses/myaccount');
    }

    public function cancel_new($buCode = null, $md5buCode = null) {
        $this->Session->write('error_msg', 'Sorry, your payment could not be completed, please try again');
        $this->redirect('/businesses/myaccount');
    }

    public function manualPaynow($slug = null) {
        $this->recruiterAccess();
        $jobInfo = $this->Job->findBySlug($slug);
        $jobNumber = $jobInfo['Job']['job_number'];
        $amount = $jobInfo['Job']['amount_paid'] - $jobInfo['Job']['dis_amount'];

        $amount = number_format($amount, 2);
        $gstAmount = $amount * 10 / 100;
        $amount = $amount + $gstAmount;

        $this->layout = "";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Payment Process');



        $this->set('success_url', HTTP_PATH . '/payments/manualSuccess/' . $jobNumber);
        $this->set('notify_url', HTTP_PATH . '/payments/manualIpnNotification/' . $jobNumber);
        $this->set('cancel_url', HTTP_PATH . '/payments/manualCancel/' . $jobNumber);

        $this->set('amount', $amount);
        $this->set('item_number', $jobNumber);
    }

    public function manualSuccess($jobNumber = null) {

        $this->layout = 'client';
        $jobInfo = $this->Job->find('first', array('conditions' => array('Job.job_number' => $jobNumber)));

        $email = $jobInfo["User"]["email_address"];
        $name = $jobInfo["User"]["first_name"];
        $jobTitle = $jobInfo['Job']['title'];


        $site_title = $this->getSiteConstant('title');
        $mail_from = $this->getMailConstant('from');

        $currentYear = date('Y', time());

        $this->Email->to = $email;
        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='30'"));
        $toSubArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]');
        $fromSubArray = array($name, $jobTitle, $site_title, $currentYear);
        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
        $this->Email->subject = $subjectToSend;

        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
        $this->Email->from = $site_title . "<" . $mail_from . ">";

        $toRepArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]');
        $fromRepArray = array($name, $jobTitle, $site_title, $currentYear);
        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
        $this->Email->layout = 'default';
        $this->set('messageToSend', $messageToSend);
        $this->Email->template = 'email_template';
        $this->Email->sendAs = 'html';
        $this->Email->send();



        $this->Session->write('success_msg', 'Your job automatically activated once PayPal confirmed your payment transaction.');
        $this->redirect('/jobs/management');
    }

    public function manualCancel($jobNumber = null) {
        $this->Session->write('error_msg', 'Sorry, your payment could not be completed, please try again.');
        $this->redirect('/jobs/management');
    }

    public function manualIpnNotification($jobNumber = null) {

        $site_title = $this->getSiteConstant('title');
        $mail_from = $this->getMailConstant('from');


        if (!empty($_REQUEST) && !empty($_REQUEST['item_number']) && $jobNumber != '') {
            //if(1){

            if ($_REQUEST['txn_id']) {
                $transactionId = $_REQUEST['txn_id'];
                $amountPaid = $_REQUEST['mc_gross'];
            } elseif ($_REQUEST['tx']) {
                $transactionId = $_REQUEST['tx'];
                $amountPaid = $_REQUEST['amt'];
            }

            $st = $_REQUEST['st'];
            $payer_id = $_REQUEST['payer_id'];
            $subscr_id = $_REQUEST['subscr_id']; ///Profile ID
            $sig = $_REQUEST['sig'];


//            $transactionId = 'SHDU5478SSSS58746';
//            $st = 'completed';
//            $payer_id = 45456465;
//            $subscr_id = 889789;
//            $sig = 'asdasjk789789798';
//            $amountPaid = 100;

            if ($transactionId) {

                $jobInfo = $this->Job->find('first', array('conditions' => array('Job.job_number' => $jobNumber, 'payment_status' => 0)));

                if ($jobInfo) {

                    $this->request->data['Payment']['job_id'] = $jobInfo['Job']['id'];
                    $this->request->data['Payment']['user_id'] = $jobInfo['User']['id'];
                    $this->request->data['Payment']['payment_status'] = 'Completed';
                    $this->request->data['Payment']['payer_id'] = $payer_id;
                    $this->request->data['Payment']['subscr_id'] = $subscr_id;
                    $this->request->data['Payment']['signature'] = $sig;
                    $this->request->data['Payment']['price'] = $amountPaid;
                    $this->request->data['Payment']['transaction_id'] = $transactionId;
                    $this->request->data['Payment']['slug'] = 'payment-' . $jobNumber . '-' . time();
                    $this->request->data['Payment']['status'] = 1;
                    $this->request->data['Payment']['dis_amount'] = $jobInfo['Job']['dis_amount'];
                    $this->request->data['Payment']['promo_code'] = $jobInfo['Job']['promo_code'];

                    $this->Payment->save($this->data['Payment']);

                    $this->Job->updateAll(array('Job.payment_status' => '2', 'Job.status' => '1'), array('Job.id' => $jobInfo['Job']['id']));

                    $jobCategory = $jobInfo['Job']['category_id'];
                    $jobTitle = $jobInfo['Job']['title'];
                    $jobId = $jobInfo['Job']['id'];
                    $condition[] = " FIND_IN_SET(" . $jobCategory . ",User.email_notification_id)";
                    $usersIds = $this->User->find('all', array('conditions' => $condition, 'fields' => array('User.first_name', 'User.email_address', 'User.id')));
                    $week = 1;
                    if ($jobInfo['Job']['type'] == 'silver') {
                        $week = 2;
                    } elseif ($jobInfo['Job']['type'] == 'gold') {
                        $week = 4;
                    }

                    if ($usersIds) {
                        foreach ($usersIds as $usersIdVV) {
                            $this->request->data['JobNotification']['id'] = '';
                            $this->request->data['JobNotification']['user_id'] = $usersIdVV['User']['id'];
                            $this->request->data['JobNotification']['job_id'] = $jobId;
                            $this->request->data['JobNotification']['first_name'] = $usersIdVV['User']['first_name'];
                            $this->request->data['JobNotification']['email_address'] = $usersIdVV['User']['email_address'];
                            $this->request->data['JobNotification']['last_email_sent_time'] = 0;
                            $this->request->data['JobNotification']['expire_time'] = time() + $week * 7 * 24 * 3600;
                            $this->request->data['JobNotification']['week'] = $week;
                            $this->JobNotification->save($this->data);
                        }
                    }

                    $email = $jobInfo["User"]["email_address"];
                    $name = $jobInfo["User"]["first_name"];
                    $jobTitle = $jobInfo["Job"]["title"];
                    $companyname = $jobInfo["User"]["company_name"];
                    $link = HTTP_PATH . '/jobs/management';

                    $date = date('F d, Y');

                    $this->Email->to = $email;
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='31'"));
                    $toSubArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]', '[!transactionId!]', '[!amountPaid!]', '[!SITE_LINK!]');
                    $fromSubArray = array($name, $jobTitle, $site_title, $date, $transactionId, $amountPaid, $link);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]', '[!transactionId!]', '[!amountPaid!]', '[!SITE_LINK!]');
                    $fromRepArray = array($name, $jobTitle, $site_title, $date, $transactionId, $amountPaid, $link);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();



                    $adminInfo = $this->Admin->findById(1);

                    $this->Email->to = $adminInfo['Admin']['email'];
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='32'"));
                    $toSubArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]', '[!transactionId!]', '[!amountPaid!]', '[!company_name!]');
                    $fromSubArray = array($name, $jobTitle, $site_title, $date, $transactionId, $amountPaid, $companyname);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                    $this->Email->subject = $subjectToSend;

                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]', '[!transactionId!]', '[!amountPaid!]', '[!company_name!]');
                    $fromRepArray = array($name, $jobTitle, $site_title, $date, $transactionId, $amountPaid, $companyname);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                }
            }
        }

        exit;
    }
    
    
    ///////////// by Dinesh Dhaker
    
    public function checkout($pnumber = null) {
        $paymentInfo = $this->Payment->find('first', array('conditions'=>array('Payment.payment_number'=>$pnumber)));
        $amount = $paymentInfo['Payment']['price'];
        $this->set('paymentInfo', $paymentInfo);
//        print_r($paymentInfo);die;
        
        $this->layout = "";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Payment Process', true));

        $this->set('success_url', HTTP_PATH . '/payments/checkoutSuccess/' . $pnumber);
        $this->set('notify_url', HTTP_PATH . '/payments/checkoutNotification/' . $pnumber);
        $this->set('cancel_url', HTTP_PATH . '/payments/checkoutCancel/' . $pnumber);

        $this->set('amount', $amount);
        $this->set('item_number', $pnumber);
        if($amount < 0.0001){
            $txn_id = 'free'.date('Ymd').rand(1000,9999);
            $this->redirect('/payments/checkoutSuccess/' . $pnumber.'?txn_id='.$txn_id);
        }
    }
    
     public function checkoutStripe($pnumber = null) {
        $paymentInfo = $this->Payment->find('first', array('conditions' => array('Payment.payment_number' => $pnumber)));
        $amount = $paymentInfo['Payment']['price'];
        $this->set('paymentInfo', $paymentInfo);

        $this->layout = 'client';
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Payment Process', true));
        ;

        $this->set('amount', $amount);
        $this->set('item_number', $pnumber);
        if (empty($paymentInfo)) {
            $this->redirect(array('controller' => 'plans', 'action' => 'purchase'));
        }
        if ($amount < 0.0001) {
            $txn_id = 'free' . date('Ymd') . rand(1000, 9999);
            $this->redirect('/payments/checkoutSuccess/' . $pnumber . '?txn_id=' . $txn_id);
        }
        if ($this->data) {
            $payableAmount = $amount;
            if ($payableAmount > 0) {
                $stripe_secret_key = $this->Admin->field('Admin.stripe_secret_key', array('id' => 1));
//                echo $stripe_secret_key;die;
                App::import('Vendor', 'Stripe', array('file' => 'Stripe/Stripe.php'));
                Stripe::setApiKey($stripe_secret_key);
                // Strip payment process code

                $stripe_error = "";
                $user_id = $paymentInfo['Payment']['user_id'];
                
                if($stripe_secret_key != ''){

                try {
                    $t = Stripe_Token::create(array("card" => array(
                                    "number" => $this->data['Payment']['card_no'],
                                    "exp_month" => $this->data['Payment']['exp_month'],
                                    "exp_year" => $this->data['Payment']['exp_year'],
                                    "cvc" => $this->data['Payment']['cvv_no'],
                                    'name' => $this->data['Payment']['name'],
                    )));
                } catch (Exception $e) {
                    
                    //echo '<pre>';print_r($e);exit;

                    $e_json = $e->getJsonBody();
                    $error = $e_json['error'];
                    $stripe_error = $error['message'];
                    $this->Session->setFlash($stripe_error, 'error_msg');
                    
                }
//                echo '<pre>';
////                
//                print_r($t);
//                die;
                if (isset($t) && !empty($t)) {
                    $token = $t->id;

                    $cardid = $t->card->id;

                    $email = $paymentInfo["User"]["email_address"];
                    $name = $paymentInfo["User"]["first_name"] . ' ' . $paymentInfo["User"]["last_name"];

                    $customer = Stripe_Customer::create(array(
                                'card' => $token,
                                'email' => strip_tags(trim($email))
                                    )
                    );
                    $user_id = $customer->id;
//   echo '<pre>';print_r($customer);die;
                    try {
                        $payment = Stripe_Charge::create(
                                        array(
                                            'amount' => $amount * 100,
                                            'currency' => CURR,
                                            "customer" => $user_id
                        ));

                        $charge_id = $payment->id;
                        $lastdigit = $payment->source->last4;
                    } catch (Stripe_ApiConnectionError $e) {
                        $e_json = $e->getJsonBody();
                        $error = $e_json['error'];
                        $stripe_error = $error['message'];
                        $this->Session->setFlash($stripe_error, 'error_msg');
                    } catch (Stripe_InvalidRequestError $e) {

                        $e_json = $e->getJsonBody();
                        $error = $e_json['error'];
                        $stripe_error = $error['message'];
                        $this->Session->setFlash($stripe_error, 'error_msg');
                    } catch (Stripe_ApiError $e) {
                        $e_json = $e->getJsonBody();
                        $error = $e_json['error'];
                        $stripe_error = $error['message'];
                        $this->Session->setFlash($stripe_error, 'error_msg');
                    } catch (Stripe_CardError $e) {

                        $e_json = $e->getJsonBody();
                        $error = $e_json['error'];
                        $stripe_error = $error['message'];
                        $this->Session->setFlash($stripe_error, 'error_msg');
                    }
                 

                    $transfer_txn_id = $charge_id;
                    //Strip payment process code
                    if ($stripe_error == '') {


                        $authorizationID = $payment->id;
                        $startdate = date("Y-m-d"); // current date
                        $enddate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($startdate)) . " +1 year"));
                        $amountCharge = CURRENCY . ' ' . $amount;
                        $txn_id = $authorizationID;
                        $stripe_txn_id = $txn_id;
                        $this->redirect('/payments/checkoutSuccess/' . $pnumber . '?txn_id=' . $txn_id . '&mc_gross=' . $amount);
                    }
                } else {

                    $msg = $stripe_error;
                    $this->Session->setFlash($stripe_error, 'error_msg');
                    
                            // $this->Session->write('error_msg', $stripe_error);
                            // $this->redirect('/payments/checkoutStripe/'.$pnumber);
        
                }
                }
            }
        } else {
            $this->request->data['Payment']['name'] = '';
        }
    }
    
    public function checkoutSuccess($pnumber = null) {
        $this->layout = 'client';
        
        $site_title = $this->getSiteConstant('title');
        $mail_from = $this->getMailConstant('from');

//        echo '<pre>';
//        print_r($_REQUEST);exit;
        
        if ($_REQUEST['txn_id']) {
            $transactionId = $_REQUEST['txn_id'];
            $amountPaid = $_REQUEST['mc_gross'];
        } elseif ($_REQUEST['tx']) {
            $transactionId = $_REQUEST['tx'];
            $amountPaid = $_REQUEST['amt'];
        }
//        echo $transactionId;exit;
//        $transactionId = 'sdsdas';
        
        if($transactionId){
             $invoice_no = $this->UserPlan->find('first', array(
                'fields' => array('MAX(UserPlan.invoice_no) AS max_invoice_no')));
            $max_invoice_no = isset($invoice_no[0]['max_invoice_no']) ? $invoice_no[0]['max_invoice_no']+1 : '1';
            
            $paymentInfo = $this->Payment->find('first', array('conditions'=>array('Payment.payment_number'=>$pnumber)));
            if ($paymentInfo['Payment']['payment_status'] == 'pending') {
                $this->Payment->updateAll(array('Payment.payment_status' => "'completed'", 'Payment.transaction_id' => "'$transactionId'"), array('Payment.id' => $paymentInfo['Payment']['id']));
               
                $companyname = $paymentInfo["User"]["company_name"];
                $email = $paymentInfo["User"]["email_address"];
                $name = $paymentInfo["User"]["first_name"].' '.$paymentInfo["User"]["last_name"];
                $planName = $paymentInfo["Plan"]["plan_name"].' Plan';
                $amount = CURR.' '.$paymentInfo["Plan"]["amount"];
                $date = date('F d, Y h:i A');
                
                $this->request->data['UserPlan']['payment_id']  = $paymentInfo['Payment']['id'];
                $this->request->data['UserPlan']['user_id']  = $paymentInfo['Payment']['user_id'];
                $this->request->data['UserPlan']['plan_id']  = $paymentInfo['Payment']['plan_id'];
                $this->request->data['UserPlan']['features_ids']  = $paymentInfo['Plan']['feature_ids'];
                $this->request->data['UserPlan']['fvalues']  = $paymentInfo['Plan']['fvalues'];
                $this->request->data['UserPlan']['amount']  = $paymentInfo['Plan']['amount'];
                $this->request->data['UserPlan']['type_value']  = $paymentInfo['Plan']['type_value'];
                $this->request->data['UserPlan']['type']  = $paymentInfo['Plan']['type'];
                
                $lastPlan = $this->UserPlan->find('first', array('conditions'=>array('UserPlan.user_id'=>$paymentInfo['Payment']['user_id']), 'order'=>array('UserPlan.id'=>'DESC')));
                $sdate = date('Y-m-d');
                if($lastPlan){
                    if($paymentInfo['Payment']['aplimp']){
                        $this->UserPlan->updateAll(array('UserPlan.is_expire' => "'1'"), array('UserPlan.id' => $lastPlan['UserPlan']['id']));
                        $sdate = date('Y-m-d');
                    }else{
                        $lastend_date = $lastPlan['UserPlan']['end_date'];
                        $sdate = date('Y-m-d', strtotime($lastend_date. ' + 1 days'));
                    }
                }               
                $tpvalue = $paymentInfo['Plan']['type_value'];
               // echo $paymentInfo['Plan']['type'];
                if($paymentInfo['Plan']['type'] == 'Months'){
                    $edate = date('Y-m-d', strtotime($sdate. " + $tpvalue Months"));
                }else{
                    $edate = date('Y-m-d', strtotime($sdate. " + $tpvalue Years"));
                }                 
                $this->request->data['UserPlan']['start_date']  = $sdate;
                $this->request->data['UserPlan']['end_date']  = $edate;
                $this->request->data['UserPlan']['slug']  = 'uplan-'.$paymentInfo['Payment']['user_id'].time();
                $this->request->data['UserPlan']['invoice_no'] = $max_invoice_no;
                
                $this->UserPlan->save($this->data['UserPlan']);
                
                ////////////update profileview table status
                 $this->ProfileView->updateAll(array('ProfileView.status' => "'2'"), array('ProfileView.emp_id' => $paymentInfo['Payment']['user_id']));
                
                $payinfo = '<p style="color:#434343; margin:10px 0 0;"><b>'.__d('controller', 'Plan Name', true).':</b> '.$planName.'</p>';
                $payinfo .= '<p style="color:#434343; margin:0px 0 0;"><b>'.__d('controller', 'Amount', true).':</b> '.$amount.'</p>';
                $payinfo .= '<p style="color:#434343; margin:0px 0 0;"><b>'.__d('controller', 'Transaction ID', true).':</b> '.$transactionId.'</p>';
                $payinfo .= '<p style="color:#434343; margin:0px 0 0;"><b>'.__d('controller', 'Date', true).':</b> '.$date.'</p>';
               
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
                 $this->Email->reset();
                
                $adminInfo = $this->Admin->findById(1);

                $this->Email->to = $adminInfo['Admin']['email'];
                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='42'"));
                $toSubArray = array('[!username!]', '[!job_title!]', '[!SITE_TITLE!]', '[!DATE!]', '[!transactionId!]', '[!amountPaid!]', '[!company_name!]');
                $fromSubArray = array($name, $jobTitle, $site_title, $date, $transactionId, $amountPaid, $companyname);
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
                
                

                $this->Session->write('success_msg', __d('controller', 'You have successfully completed payment for your membership plan.', true));
                
            }else{
                $this->Session->write('success_msg', __d('controller', 'You have successfully completed payment for your membership plan.', true));
            }
        }else{
            $this->Session->write('success_msg', __d('controller', 'You have successfully completed payment, so your plan automatically activated once PayPal confirmed your payment transaction.', true)); 
        }
        
        $this->redirect('/users/myaccount');
    }

    public function checkoutCancel($pnumber = null) {
        $this->Session->write('error_msg', __d('controller', 'Sorry, your payment could not be completed, please try again', true));
        $this->redirect('/users/myaccount');
    }

    public function checkoutNotification($pnumber = null) {

        $site_title = $this->getSiteConstant('title');
        $mail_from = $this->getMailConstant('from');

        if (!empty($_REQUEST) && !empty($_REQUEST['item_number']) && $pnumber != '') {
            //if(1){

            if ($_REQUEST['txn_id']) {
                $transactionId = $_REQUEST['txn_id'];
                $amountPaid = $_REQUEST['mc_gross'];
            } elseif ($_REQUEST['tx']) {
                $transactionId = $_REQUEST['tx'];
                $amountPaid = $_REQUEST['amt'];
            }

            $st = $_REQUEST['st'];
            $payer_id = $_REQUEST['payer_id'];
            $subscr_id = $_REQUEST['subscr_id']; ///Profile ID
            $sig = $_REQUEST['sig'];


//            $transactionId = 'SHDU5478SSSS58746';
//            $st = 'completed';
//            $payer_id = 45456465;
//            $subscr_id = 889789;
//            $sig = 'asdasjk789789798';
//            $amountPaid = 100;
            
            
            if($transactionId){
                                $invoice_no = $this->UserPlan->find('first', array(
                    'fields' => array('MAX(UserPlan.invoice_no) AS max_invoice_no')));
                $max_invoice_no = isset($invoice_no[0]['max_invoice_no']) ? $invoice_no[0]['max_invoice_no']+1 : '1';
                
                $paymentInfo = $this->Payment->find('first', array('conditions'=>array('Payment.payment_number'=>$pnumber)));
                if ($paymentInfo['Payment']['payment_status'] == 'pending') {
                    $this->Payment->updateAll(array('Payment.payment_status' => "'completed'", 'Payment.transaction_id' => "'$transactionId'"), array('Payment.id' => $paymentInfo['Payment']['id']));
                
                    $email = $paymentInfo["User"]["email_address"];
                    $name = $paymentInfo["User"]["first_name"].' '.$paymentInfo["User"]["last_name"];
                    $planName = $paymentInfo["Plan"]["plan_name"].' Plan';
                    $amount = CURR.' '.$paymentInfo["Plan"]["amount"];
                    $date = date('F d, Y h:i A');

                    $this->request->data['UserPlan']['payment_id']  = $paymentInfo['Payment']['id'];
                    $this->request->data['UserPlan']['user_id']  = $paymentInfo['Payment']['user_id'];
                    $this->request->data['UserPlan']['plan_id']  = $paymentInfo['Payment']['plan_id'];
                    $this->request->data['UserPlan']['features_ids']  = $paymentInfo['Plan']['feature_ids'];
                    $this->request->data['UserPlan']['fvalues']  = $paymentInfo['Plan']['fvalues'];
                    $this->request->data['UserPlan']['amount']  = $paymentInfo['Plan']['amount'];
                    $this->request->data['UserPlan']['type_value']  = $paymentInfo['Plan']['type_value'];
                    $this->request->data['UserPlan']['type']  = $paymentInfo['Plan']['type'];
                
                    $lastPlan = $this->UserPlan->find('first', array('conditions'=>array('UserPlan.user_id'=>$paymentInfo['Payment']['user_id']), 'order'=>array('UserPlan.id'=>'DESC')));
                    $sdate = date('Y-m-d');
                    if($lastPlan){
                        if($paymentInfo['Payment']['aplimp']){
                            $this->UserPlan->updateAll(array('UserPlan.is_expire' => "'1'"), array('UserPlan.id' => $lastPlan['UserPlan']['id']));
                            $sdate = date('Y-m-d');
                        }else{
                            $lastend_date = $lastPlan['UserPlan']['end_date'];
                            $sdate = date('Y-m-d', strtotime($lastend_date. ' + 1 days'));
                        }
                    }                
                    $tpvalue = $paymentInfo['Plan']['type_value'];
                    if($paymentInfo['Plan']['type'] == 'Months'){
                        $edate = date('Y-m-d', strtotime($sdate. " + $tpvalue Months"));
                    }else{
                        $edate = date('Y-m-d', strtotime($sdate. " + $tpvalue Years"));
                    }                
                    $this->request->data['UserPlan']['start_date']  = $sdate;
                    $this->request->data['UserPlan']['end_date']  = $edate;
                    $this->request->data['UserPlan']['slug']  = 'uplan-'.$paymentInfo['Payment']['user_id'].time();
                    $this->request->data['UserPlan']['invoice_no'] = $max_invoice_no;
                    $this->UserPlan->save($this->data['UserPlan']);
                    ////////////update profileview table status
                 $this->ProfileView->updateAll(array('ProfileView.status' => "'2'"), array('ProfileView.emp_id' => $paymentInfo['Payment']['user_id']));
                
                    
                    $payinfo = '<p style="color:#434343; margin:10px 0 0;"><b>Plan Name:</b> '.$planName.'</p>';
                    $payinfo .= '<p style="color:#434343; margin:0px 0 0;"><b>Amount:</b> '.$amount.'</p>';
                    $payinfo .= '<p style="color:#434343; margin:0px 0 0;"><b>Transaction ID:</b> '.$transactionId.'</p>';
                    $payinfo .= '<p style="color:#434343; margin:0px 0 0;"><b>Date:</b> '.$date.'</p>';

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
                    $fromSubArray = array($name, $jobTitle, $site_title, $date, $transactionId, $amountPaid, $companyname);
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
            }
            exit;            
        }
        exit;
    }
    
    
    public function history() {

        $this->layout = "client";
        $this->set('transactionActive', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Payment History', true));
         $this->userLoginCheck();
        //$this->recruiterAccess();

        $userId = $this->Session->read("user_id");

        $condition = array('UserPlan.user_id' => $userId);
        $separator = array();
        $urlSeparator = array();

        $order = 'UserPlan.id Desc';

        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['UserPlan'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('payments', $this->paginate('UserPlan'));

        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'payments';
            $this->render('history');
        }
    }

}

?>