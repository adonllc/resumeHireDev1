<?php

class SmtpsettingsController extends AppController {

    public $uses = array('Admin', 'Emailtemplate', 'User', 'Smtpsetting');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Smtpsetting.id' => 'asc'));
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

    public function admin_configuration() {        
        
        require BASE_PATH."/app/webroot/email.php";

        $this->layout = "admin";
        $this->set('smtpsetting', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Site Setting");

        $id = $this->Session->read("adminid");
        $msgString = "";

        if ($this->data) {
            if ($this->data['Smtpsetting']['is_smtp'] == 1) {
                if (empty($this->data['Smtpsetting']['smtp_host'])) {
                    $msgString .= "- Please enter SMTP Host Name.<br>";
                }
                if (empty($this->data['Smtpsetting']['smtp_username'])) {
                    $msgString .= "- Please enter SMTP Username.<br>";
                }
                if (empty($this->data['Smtpsetting']['smtp_password'])) {
                    $msgString .= "- Please enter SMTP Password.<br>";
                }
                if (empty($this->data['Smtpsetting']['smtp_port'])) {
                    $msgString .= "- Please enter SMTP Post.<br>";
                }
                if (empty($this->data['Smtpsetting']['smtp_timeout'])) {
                    $msgString .= "- Please enter SMTP Timeout.<br>";
                }
            }
            
            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $adminInfo =  $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
                if ($this->data['Smtpsetting']['is_smtp'] == 1) {                    
                    $mail_from = $this->getMailConstant('from');
                    $mail = new EMail;
                    $mail->Username = $this->data['Smtpsetting']['smtp_username'];
                    $mail->Password = $this->data['Smtpsetting']['smtp_password'];
                    $mail->Server = $this->data['Smtpsetting']['smtp_host'];
                    $mail->Port = $this->data['Smtpsetting']['smtp_port'];

                    $mail->AddTo($adminInfo["Admin"]['email']); //to email
                    $mail->SetFrom($mail_from);  //from email
                    $mail->Subject = "Test mail sent via SMTP";
                    $mail->Message = "This is the test mail sent via SMTP";


                   // $mail->AddCc("madan.saini@logicspice.com"); // cc email
                    $mail->ContentType = "text/html";       
                    $mail->Headers['X-SomeHeader'] = 'abcde';  
                    $mail->ConnectTimeout = 30;  
                    $mail->ResponseTimeout = 8;  

                    if($mail->Send()){
                        $emailSent = 1;
                    }else{
                        $emailSent =  'SMTP configuration not valid, please check and try again.';
                    }
                }else{
                    $emailSent = 1;
                }
               
                if($emailSent == 1){
                    $this->request->data['Smtpsetting']['id'] = 1;                
                    if ($this->Smtpsetting->save($this->data)) {
                        $this->Session->setFlash("Smtp details update sucessfully.", 'success_msg');
                        $this->redirect(array('controller' => 'smtpsettings', 'action' => 'configuration'));
                    }
                }else{
                    $this->Session->setFlash($emailSent, 'error_msg');
                }
            }
        } else {
            $this->Smtpsetting->id = 1;
            $this->request->data = $this->Smtpsetting->read();
        }        
    }
}

?>