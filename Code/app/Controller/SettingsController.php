<?php

class SettingsController extends AppController {

    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'SiteSetting', 'MailSetting');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('manageSetting.id' => 'asc'));
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

    /* ---------------------------- */
    /* ----Settings by admin------- */
    /* ---------------------------- */

    public function admin_index() {

        $this->layout = "admin";
        $this->set('settings', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Setting list");
        $id = $this->Session->read("adminid");
    }

    /* ---------------------------- */
    /* ----Settings by admin------- */
    /* ---------------------------- */

    public function admin_siteSettings() {

        $this->layout = "admin";
        $this->set('site_setting', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Site Setting");

        $id = $this->Session->read("adminid");
        $Admindetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));

        $msgString = "";

        if ($this->data) {

            if (empty($this->data['SiteSetting']['title'])) {
                $msgString .= "- Please enter tiktle Name.<br>";
            }
            if (empty($this->data['SiteSetting']['url'])) {
                $msgString .= "- Please enter website url.<br>";
            }
            if (empty($this->data['SiteSetting']['tagline'])) {
                $msgString .= "- Please enter tagline.<br>";
            }
            if (empty($this->data['SiteSetting']['phone'])) {
                $msgString .= "- Please enter phone Number.<br>";
            }

            if (empty($this->data['SiteSetting']['max_size'])) {
                $msgString .= "- Please enter max size.<br>";
            }
            if (empty($this->data['SiteSetting']['facebook_link'])) {
                $msgString .= "- Please enter facebook url.<br>";
            }

//            if (empty($this->data['SiteSetting']['twitter_link'])) {
//                $msgString .= "- Please enter twitter url.<br>";
//            }

            if (empty($this->data['SiteSetting']['linkedin_link'])) {
                $msgString .= "- Please enter linkedin url.<br>";
            }

            if (empty($this->data['SiteSetting']['instagram_link'])) {
                $msgString .= "- Please enter instagram url.<br>";
            }
            if (empty($this->data['SiteSetting']['pinterest'])) {
                $msgString .= "- Please enter pinterest url.<br>";
            }
            if (empty($this->data['SiteSetting']['jobs_count'])) {
                $msgString .= "- Please enter Number of jobs.<br>";
            }



            //if (empty($this->data['SiteSetting']['video_link'])) {
            //$msgString .= "- Please enter correct url.<br>";
            //}


            if (isset($msgString) && $msgString != '') {

                $this->Session->setFlash($msgString, 'error_msg');
            } else {


                $this->request->data['SiteSetting']['id'] = $Admindetail['Admin']['id'];
                $this->request->data['SiteSetting']['app_payment'] = $this->data['SiteSetting']['app_payment']?$this->data['SiteSetting']['app_payment']:'0';

                if ($this->SiteSetting->save($this->data)) {


                    $this->Session->setFlash("Site details update sucessfully.", 'success_msg');
                    $this->redirect(array('controller' => 'settings', 'action' => 'siteSettings'));
                }
            }
        } else {
            $id = $this->Session->read("adminid");
            $adminId = $this->SiteSetting->field('id', array('SiteSetting.id' => $id));
            // pr($adminId); die;
            $this->SiteSetting->id = $adminId;
            $this->data = $this->SiteSetting->read();

            // $this->request->data = $this->data['Sitesetting'];
        }
    }

    /* ------------------ */
    /* ----Manage Mails-- */
    /* ------------------ */

    public function admin_manageMails() {

        $this->layout = "admin";
        $this->set('manage_mail', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Manage Mail Setting");
        $this->set('default', '2');
        $id = $this->Session->read("adminid");
        $Admindetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
        $condition = array();

        $msgString = "";

        $separator = array();
        $urlSeparator = array();
        $keyword = '';
        $searchByDateFrom = '';
        $searchByDateTo = '';

        if (!empty($this->data)) {

            if (isset($this->data['MailSetting']['keyword']) && $this->data['MailSetting']['keyword'] != '') {
                $keyword = trim($this->data['MailSetting']['keyword']);
            }

            if (isset($this->data['MailSetting']['action'])) {
                $idList = $this->data['MailSetting']['idList'];
                if ($idList) {
                    if ($this->data['MailSetting']['action'] == "delete") {

                        $cnd = array("MailSetting.id IN ($idList) ");
                        $this->MailSetting->deleteAll($cnd);
                    }
                }
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['keyword']) && $this->params['named']['keyword'] != '') {
                $keyword = urldecode(trim($this->params['named']['keyword']));
            }
        }

        if (isset($keyword) && $keyword != '') {

            $separator[] = 'keyword:' . urlencode($keyword);
            $keyword = str_replace('_', '\_', $keyword);
            $condition[] = " (`MailSetting`.`mail_value` LIKE '%" . addslashes($keyword) . "%' OR `MailSetting`.`mail_name` LIKE '%" . addslashes($keyword) . "%' ) ";
            $keyword = str_replace('\_', '_', $keyword);
            $this->set('keyword', $keyword);
        }

        $order = 'MailSetting.id Desc';

        $separator = implode("/", $separator);


        $urlSeparator = implode("/", $urlSeparator);
        $this->set('keyword', $keyword);
        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['MailSetting'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('mailsettings', $this->paginate('MailSetting'));

        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/settings';
            $this->render('index');
        }
    }

    /* ------------------ */
    /* ----Delete Mails-- */
    /* ------------------ */

    public function admin_deletemails($slug = NULL, $type = NULL) {
        $this->set('manage_mail', 'active');
        $this->set('default', '1');
        if ($slug != '') {
            $id = $this->MailSetting->field('id', array('MailSetting.slug' => $slug));

            if ($this->MailSetting->delete($id)) {
                
            }
            $this->Session->setFlash('Mail deleted successfully', 'success_msg');
        }
        $this->redirect('/admin/settings/manageMails');
    }

    /* -------------- */
    /* ----Edit Mails-- */
    /* --------------- */

    public function admin_editMails($slug = null) {

        $this->layout = "admin";
        $this->set('manage_mail', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Mail Setting");

        $msgString = "";

        if ($this->data) {

            if (empty($this->data['MailSetting']['mail_value'])) {
                $msgString .= "- Please enter valid email.<br>";
            }

            if (isset($msgString) && $msgString != '') {

                $this->Session->setFlash($msgString, 'error_msg');
            } else {


                //$this->request->data['MailSetting']['slug'] = $this->stringToSlugUnique($this->data['MailSetting']['mail_name'], 'MailSetting', 'slug');


                if ($this->MailSetting->save($this->data)) {

                    $this->Session->setFlash("Mail details added sucessfully.", 'success_msg');
                    $this->redirect(array('controller' => 'settings', 'action' => 'manageMails'));
                }
            }
        } elseif ($slug != '') {

            $id = $this->MailSetting->field('id', array('MailSetting.slug' => $slug));
            $this->MailSetting->id = $id;
            $this->data = $this->MailSetting->read();
        }
    }

}

?>
