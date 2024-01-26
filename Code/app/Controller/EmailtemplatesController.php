<?php

class EmailtemplatesController extends AppController {

    public $uses = array('Admin', 'User', 'Emailtemplate', 'EmailLog','Setting');
    public $helpers = array('Html', 'Form', 'Fck', 'Paginator', 'Javascript', 'Ajax', 'Text', 'Number');
    public $paginate = array('limit' => '20', 'emailtemplate' => '1', 'order' => array('Emailtemplate.title ' => 'asc'));
    public $components = array('RequestHandler', 'Email', 'Captcha');
    public $layout = 'admin';

    function beforeFilter() {
        $loggedAdminId = $this->Session->read("adminid");
        if (isset($this->params['admin']) && $this->params['admin'] && !$loggedAdminId) {
            $this->redirect("/admin/admins/login");
        }
    }

    /**
     * @abstract This function is define to display emailtemplate listing on backend.
     * @access Public
     * @author Logicspice (info@logicspice)
     * @since 1.0.0 16-03-2012
     */
    public function admin_index() {
        $this->layout = "admin";

        $constant = $this->getConstantData();
        $this->set('title_for_layout', $constant['TAGLINE'] . ': Email Templates List');
        $condition = array();
        $separator = array();
        $urlSeparator = array();
        $this->set('manage_template', 'active');
        $this->set('default', '4');
        $separator = implode("/", $separator);
        //$this->adminsubLoggedinCheck();

        if (!empty($this->passedArgs)) {
            if (isset($this->passedArgs["emailtemplate"])) {
                $urlSeparator[] = 'emailtemplate:' . $this->passedArgs["emailtemplate"];
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

        $this->set('staticemailtemplates', $this->paginate('Emailtemplate', $condition));

        //echo '<pre>';print_r($this->paginate('Emailtemplate',$condition));die;
        if ($this->request->is('ajax')) {
            //Configure::write('debug', 0);
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/emailtemplate/';
            $this->render('index');
        }
    }

    /**
     *
     * @abstract This function is define to edit emailtemplate from backend.
     * @access Public
     * @author Logicspice (info@logicspice)
     * @since 1.0.0 16-03-2012
     */
    public function admin_editEmailtemplate($slug = null) {
        $id = $this->Emailtemplate->field('id', array('Emailtemplate.static_email_heading' => $slug));
        $this->layout = "admin";
        $constant = $this->getConstantData();
        $this->set('title_for_layout', $constant['TAGLINE'] . ' : Edit Email Template');
        $msgString = '';
        $this->set('default', '4');
        $this->set('manage_template', 'active');


        $Action_options = $this->Emailtemplate->field('variables', array('Emailtemplate.id =' . $id));
        // $Action_options = $this->Emailtemplate->find('first',array('conditions'=>'Emailtemplate.id ='.$id), array('fields' => array('variables','variables')));
        $this->set('Action_options', $Action_options);

        if ($this->data) {

            if (empty($this->data["Emailtemplate"]["title"])) {
                $msgString = "- Email Template title is required field.<br>";
            }
            if (empty($this->data["Emailtemplate"]["subject"])) {
                $msgString = "- Email Subject is required field.<br>";
            }
            if (empty($this->data["Emailtemplate"]["template"])) {
                $msgString .= "- Email Template description is required field.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $emailtemplate_link = strtolower($this->data["Emailtemplate"]["title "]);
                $pieces = explode(" ", $emailtemplate_link);
                $this->data["Emailtemplate"]["static_email_heading"] = implode("_", $pieces);
                if ($this->Emailtemplate->save($this->data)) {

                    $this->Session->setFlash('Email Template details updated successfully.', 'success_msg');
                    //$this->Session->write('message', 'Email Template details updated successfully.');
                    $this->redirect('/admin/emailtemplates/index');
                }
            }
        } elseif ($id > 0) {
            $this->Emailtemplate->id = $id;
            $this->data = $this->Emailtemplate->read();
        }
    }

    /**
     *
     * @abstract This function is define to delete emailtemplate from backend.
     * @access Public
     * @author Logicspice (info@logicspice)
     * @since 1.0.0 16-03-2012
     */
    public function admin_deleteemailtemplate($id = NULL) {
        if ($id > 0) {
            $this->Emailtemplate->delete($id);
            $this->viewPath = 'elements' . DS . 'admin';
            $this->Session->write('message', 'Email Template delete successfully.');
            $this->redirect('/admin/emailtemplates/index');
        }
    }

    /**
     *
     * @abstract This function is define to get emailtemplate content of under construction for front end.
     * @access Public
     * @author Logicspice (info@logicspice)
     * @since 1.0.0 16-03-2012
     */
    public function underconstruction() {
        $this->layout = "underconstruction";
        $constant = $this->getConstantData();
        $this->set('title_for_layout', $constant['TAGLINE'] . 'Undercounstruction');
    }

    public function terms_of_use() {
        $this->layout = "client";
        $emailtemplateContent = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='2'"));
        $constant = $this->getConstantData();
        $this->set('title_for_layout', $constant['TAGLINE'] . " : " . $emailtemplateContent['Emailtemplate']['title ']);
        $this->set('emailtemplateContent', $emailtemplateContent);
    }

    public function collection() {
        $this->layout = "client";
        $emailtemplateContent = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='43'"));
        $constant = $this->getConstantData();
        $this->set('title_for_layout', $constant['TAGLINE'] . " : " . $emailtemplateContent['Emailtemplate']['title ']);
        $this->set('emailtemplateContent', $emailtemplateContent);
    }

    public function magazines() {
        $this->layout = "client";
        $emailtemplateContent = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='44'"));
        $constant = $this->getConstantData();
        $this->set('title_for_layout', $constant['TAGLINE'] . " : " . $emailtemplateContent['Emailtemplate']['title ']);
        $this->set('emailtemplateContent', $emailtemplateContent);
        $this->set('magazine', 'active');
        $this->set('media', 'active');
    }

    public function videos() {
        $this->layout = "client";
        $emailtemplateContent = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='45'"));
        $constant = $this->getConstantData();
        $this->set('title_for_layout', $constant['TAGLINE'] . " : " . $emailtemplateContent['Emailtemplate']['title ']);
        $this->set('emailtemplateContent', $emailtemplateContent);
        $this->set('video', 'active');
        $this->set('media', 'active');
    }

    public function about_us() {
        $this->layout = "client";
        $this->set('about', 'active');
        $emailtemplateContent = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='2'"));
        $constant = $this->getConstantData();
        $this->set('title_for_layout', $constant['TAGLINE'] . " : " . $emailtemplateContent['Emailtemplate']['title ']);
        $this->set('emailtemplateContent', $emailtemplateContent);
    }

    public function contact_us() {
        $this->layout = "client";
    }

    public function admin_testmail($slug = null) {
        $detail = $this->Emailtemplate->find('first', array("conditions" => array('Emailtemplate.static_email_heading' => $slug)));
        $contact_details = $this->Setting->find('first');
          $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        
          $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');
//        echo '<pre>';
//        print_r($detail);
//        print_r($contact_details);
//        die;

//        $username = $this->data["User"]["name"];
//        $email = $this->data["User"]["email"];
//        $message = $this->data["User"]["message"];
//        $subjectbyuser = $this->data["User"]["subject"];
//        $currentYear = date('Y', time());
//        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

        $this->Email->to = $contact_details['Setting']['email'];
        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='6'"));
        //$this->Email->subject = $this->data["User"]['subject'];
//        $toSubArray = array('[!username!]', '[!email!]', '[!subjectuser!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
//        $fromSubArray = array($username, $email, $subjectbyuser, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
        $subjectToSend = $emailtemplateMessage['Emailtemplate']['subject'];
//        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
        $this->Email->subject = $subjectToSend;


        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
        $this->Email->from = $site_title . "<" . $mail_from . ">";

//        $toRepArray = array('[!username!]', '[!email!]', '[!message!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
//        $fromRepArray = array($username, $email, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $this->data["User"]['subject']);
        $messageToSend = $emailtemplateMessage['Emailtemplate']['template'];
//        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
        $this->Email->layout = 'default';
        $this->set('messageToSend', $messageToSend);
//        echo $messageToSend;die;
        $this->Email->template = 'email_template';
        $this->Email->sendAs = 'html';
        $this->Email->send();
        $this->Session->setFlash('Email Template test mail sent successfully.', 'success_msg');
        //$this->Session->write('message', 'Email Template details updated successfully.');
        $this->redirect('/admin/emailtemplates/index');
    }

}

?>
