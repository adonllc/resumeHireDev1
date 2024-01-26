<?php

class AnnouncementsController extends AppController {

    public $c_word = 'Announcements';
    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'Announcement', 'Announcement');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Announcement.id' => 'desc'));
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

    public function admin_index() {

        $this->layout = "admin";
        $this->set('announcementlist', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Announcement list");

        $condition = array();
        $separator = array();
        $urlSeparator = array();
        $c_word = '';

        if (!empty($this->data)) {
            if (isset($this->data['Announcement']['name']) && $this->data['Announcement']['name'] != '') {
                $c_word = trim($this->data['Announcement']['name']);
            }

            if (isset($this->data['Announcement']['action'])) {
                $idList = $this->data['Announcement']['idList'];
                if ($idList) {
                     if ($this->data['Announcement']['action'] == "activate") {
                        $cnd = array("Announcement.id IN ($idList) ");
                        $this->Announcement->updateAll(array('Announcement.status' => "'1'"), $cnd);
                    } elseif ($this->data['Announcement']['action'] == "deactivate") {
                        $cnd = array("Announcement.id IN ($idList) ");
                        $this->Announcement->updateAll(array('Announcement.status' => "'0'"), $cnd);
                    } elseif ($this->data['Announcement']['action'] == "delete") {
                        $cnd = array("Announcement.id IN ($idList) ");

                        $this->Announcement->deleteAll($cnd);
                    }
                }
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['name']) && $this->params['named']['name'] != '') {
                $c_word = urldecode(trim($this->params['named']['name']));
            }
        }

        if (isset($c_word) && $c_word != '') {
            $separator[] = 'name:' . urlencode($c_word);
            $condition[] = " (Announcement.name like '%" . addslashes($c_word) . "%')  ";
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
        $this->set('c_word', $c_word);
        $this->set('searchKey', $c_word);

        $this->paginate['Announcement'] = array(
            'conditions' => $condition,
            'order' => array('Announcement.id' => 'DESC'),
            'limit' => '30'
        );

        $this->set('announcements', $this->paginate('Announcement', $condition));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/announcements/';
            $this->render('index');
        }
    }

    public function admin_add() {


        $this->set('addannouncement', 'active');
        $this->set('announcementlist', '');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Announcement");

        if ($this->data) {

            if (empty($this->data["Announcement"]["name"])) {
                $msgString .="- Announcement Name is required field.<br>";
            } else {

                $this->request->data['Announcement']['slug'] = $this->stringToSlugUnique($this->data["Announcement"]["name"], 'Announcement');
                $this->request->data['Announcement']['status'] = '1';
                if ($this->Announcement->save($this->data)) {
                    $this->Session->setFlash('Announcement Added Successfully', 'success_msg');
                    $this->redirect('/admin/announcements/index');
                }
            }
        }
    }

    public function admin_edit($slug = null) {

        $this->set('announcementlist', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Announcement");


        if ($this->data) {

            $find = $this->Announcement->field('id', array('Announcement.name' => $this->data["Announcement"]["name"], 'Announcement.slug <> "' . $slug . '"'));

            // pr($find);exit;
            if (isset($find) && $find > 0 && $find != $slug) {

                $msgString .="- Announcement Name already exists.<br>";
            }

            if (empty($this->data["Announcement"]["name"])) {
                $msgString .="- Announcement Name is required field.<br>";
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if ($this->Announcement->save($this->data)) {
                    $this->Session->setFlash('Announcement updated successfully', 'success_msg');
                    $this->redirect('/admin/announcements/index');
                }
            }
        } else {


            $id = $this->Announcement->field('id', array('Announcement.slug' => $slug));
            $this->Announcement->id = $id;
            $this->data = $this->Announcement->read();
        }
    }

    public function admin_delete($id = null) {
        $id = $this->Announcement->field('id', array('Announcement.slug' => $id));
        if ($id) {
            $this->Announcement->delete($id);
            $this->Session->setFlash('Announcement deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'announcements', 'action' => 'index'));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'announcements', 'action' => 'index'));
        }
    }
      public function admin_activate($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Announcement->field('id', array('Announcement.slug' => $slug));
            $cnd = array("Announcement.id = $id");
            $this->Announcement->updateAll(array('Announcement.status' => "'1'"), $cnd);
            $this->set('action', '/admin/announcements/deactivate/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivate($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Announcement->field('id', array('Announcement.slug' => $slug));
            $cnd = array("Announcement.id = $id");
            $this->Announcement->updateAll(array('Announcement.status' => "'0'"), $cnd);
            $this->set('action', '/admin/announcements/activate/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

}

?>
