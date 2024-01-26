<?php

class IndustriesController extends AppController {

    public $name = 'Industries';
    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'Industry');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Industry.name' => 'asc'));
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
        $this->set('industry_list', 'active');
        $this->set('title_for_layout', TITLE_FOR_PAGES . "List Industries");

        $condition = array();
        $separator = array();
        $urlSeparator = array();
        $name = '';

        if (!empty($this->data)) {
            if (isset($this->data['Industry']['name']) && $this->data['Industry']['name'] != '') {
                $name = trim($this->data['Industry']['name']);
            }

            if (isset($this->data['Industry']['action'])) {
                $idList = $this->data['Industry']['idList'];
                if ($idList) {
                    if ($this->data['Industry']['action'] == "activate") {
                        $cnd = array("Industry.id IN ($idList) ");
                        $this->Industry->updateAll(array('Industry.status' => "'1'"), $cnd);
                    } elseif ($this->data['Industry']['action'] == "deactivate") {
                        $cnd = array("Industry.id IN ($idList) ");
                        $this->Industry->updateAll(array('Industry.status' => "'0'"), $cnd);
                    } elseif ($this->data['Industry']['action'] == "delete") {
                        $cnd = array("Industry.id IN ($idList) ");
                        $this->Industry->deleteAll($cnd);
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
            $condition[] = " (Industry.name like '%" . addslashes($name) . "%')  ";
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
        $this->set('name', $name);
        $this->set('searchKey', $name);

        $this->paginate['Industry'] = array(
            'conditions' => $condition,
            'order' => array('Industry.id' => 'DESC'),
            'limit' => '50'
        );

        $this->set('industries', $this->paginate('Industry', $condition));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/industries/';
            $this->render('index');
        }
    }

    public function admin_addindustry() {
        $this->set('add_industry', 'active');
        $msgString = "";
        $this->set('title_for_layout', TITLE_FOR_PAGES . "Add Industry");

        if ($this->data) {

            if (empty($this->data["Industry"]["name"])) {
                $msgString .="- Industry Name is required field.<br>";
            } elseif ($this->Industry->isRecordUniqueIndustry(trim($this->data["Industry"]["name"])) == false) {
                $msgString .="- Industry Name already exists.<br>";
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data['Industry']['slug'] = $this->stringToSlugUnique($this->data["Industry"]["name"], 'Industry');
                $this->request->data['Industry']['status'] = '1';
                if ($this->Industry->save($this->data)) {
                    $this->Session->setFlash('Industry Added Successfully', 'success_msg');
                    $this->redirect('/admin/industries/index');
                }
            }
        }
    }

    public function admin_editindustry($slug = null) {

        $this->set('industry_list', 'active');
        $msgString = "";
        $this->set('title_for_layout', TITLE_FOR_PAGES . "Edit Industry");

        if ($this->data) {
            if (empty($this->data["Industry"]["name"])) {
                $msgString .="- Industry Name is required field.<br>";
            } elseif (strtolower(trim($this->data["Industry"]["old_name"])) != strtolower(trim($this->data["Industry"]["name"]))) {
                $this->request->data['Industry']['slug'] = $this->stringToSlugUnique($this->data["Industry"]["name"], 'Industry');
                if ($this->Industry->isRecordUniqueIndustry(trim($this->data["Industry"]["name"]), 0) == false) {
                    $msgString .="- Industry Name already exists.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if ($this->Industry->save($this->data)) {
                    $this->Session->setFlash('Industry updated successfully', 'success_msg');
                    $this->redirect('/admin/industries/index');
                }
            }
        } else {
            $id = $this->Industry->field('id', array('Industry.slug' => $slug));
            $this->Industry->id = $id;
            $this->data = $this->Industry->read();
            $this->request->data['Industry']['old_name'] = $this->data['Industry']['name'];
        }
    }

    public function admin_activateIndustry($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Industry->field('id', array('Industry.slug' => $slug));
            $cnd = array("Industry.id = $id");
            $this->Industry->updateAll(array('Industry.status' => "'1'"), $cnd);
            $this->set('action', '/admin/industries/deactivateIndustry/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivateIndustry($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Industry->field('id', array('Industry.slug' => $slug));
            $cnd = array("Industry.id = $id");
            $this->Industry->updateAll(array('Industry.status' => "'0'"), $cnd);
            $this->set('action', '/admin/industries/activateIndustry/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deleteIndustry($slug = null) {
        $id = $this->Industry->field('id', array('Industry.slug' => $slug));
        if ($id) {
            $this->Industry->delete($id);
            $this->Session->setFlash('Industry deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'industries', 'action' => 'index'));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'industries', 'action' => 'index'));
        }
    }

}

?>
