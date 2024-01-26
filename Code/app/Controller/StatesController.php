<?php

class StatesController extends AppController {

    public $name = 'States';
    public $uses = array('Admin', 'State', 'Country', 'City');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('State.name' => 'asc'));
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

    public function admin_index($cslug = null) {
        $this->layout = "admin";
        $this->set('country_list', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "List States");

        $countryInfo = $this->Country->findBySlug($cslug);
        if (!$countryInfo) {
            $this->redirect('/admin/countries/index/');
        }

        $condition = array('State.country_id' => $countryInfo['Country']['id']);
        $separator = array();
        $urlSeparator = array();
        $name = '';

        $this->set('countryInfo', $countryInfo);
        $this->set('cslug', $cslug);


        if (!empty($this->data)) {
            if (isset($this->data['State']['name']) && $this->data['State']['name'] != '') {
                $name = trim($this->data['State']['name']);
            }

            if (isset($this->data['State']['action'])) {
                $idList = $this->data['State']['idList'];
                if ($idList) {
                    if ($this->data['State']['action'] == "activate") {
                        $cnd = array("State.id IN ($idList) ");
                        $this->State->updateAll(array('State.status' => "'1'"), $cnd);
                    } elseif ($this->data['State']['action'] == "deactivate") {
                        $cnd = array("State.id IN ($idList) ");
                        $this->State->updateAll(array('State.status' => "'0'"), $cnd);
                    } elseif ($this->data['State']['action'] == "delete") {
                        $this->City->deleteAll(array("City.state_id IN ($idList) "));
                        $cnd = array("State.id IN ($idList) ");
                        $this->State->deleteAll($cnd);
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
            $condition[] = " (State.state_name like '%" . addslashes($name) . "%')  ";
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

        $this->paginate['State'] = array(
            'conditions' => $condition,
            'order' => array('State.id' => 'DESC'),
            'limit' => '50'
        );


        $this->set('states', $this->paginate('State'));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/states/';
            $this->render('index');
        }
    }

    public function admin_addstate($cslug = null) {
        $this->set('country_list', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add State");

        $countryInfo = $this->Country->findBySlug($cslug);
        if (!$countryInfo) {
            $this->redirect('/admin/countries/index/');
        }

        $this->set('countryInfo', $countryInfo);
        $this->set('cslug', $cslug);


        if ($this->data) {

            // pr($this->data);exit;

            if (empty($this->data["State"]["state_name"])) {
                $msgString .="- State name is required field.<br>";
            } elseif ($this->State->isRecordUniqueState($this->data["State"]["state_name"], $countryInfo['Country']['id']) == false) {
                $msgString .="- State name already exists.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data['State']['slug'] = $this->stringToSlugUnique($this->data["State"]["state_name"], 'State');
                $this->request->data['State']['status'] = '1';
                $this->request->data['State']['country_id'] = $countryInfo['Country']['id'];
                if ($this->State->save($this->data)) {
                    $this->Session->setFlash('State added successfully', 'success_msg');
                    $this->redirect('/admin/states/index/' . $cslug);
                }
            }
        }
    }

    public function admin_editstate($slug = null, $cslug = null) {

        $this->set('country_list', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit State");

        $countryInfo = $this->Country->findBySlug($cslug);
        if (!$countryInfo) {
            $this->redirect('/admin/countries/index/');
        }
        $this->set('countryInfo', $countryInfo);
        $this->set('cslug', $cslug);


        if ($this->data) {


            if (empty($this->data["State"]["state_name"])) {
                $msgString .="- State name is required field.<br>";
            } elseif (strtolower($this->data["State"]["state_name"]) != strtolower($this->data["State"]["old_name"])) {
                if ($this->State->isRecordUniqueState($this->data["State"]["state_name"], $countryInfo['Country']['id']) == false) {
                    $msgString .="- State name already exists.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if ($this->State->save($this->data)) {
                    $this->Session->setFlash('State updated successfully', 'success_msg');
                    $this->redirect('/admin/states/index/' . $cslug);
                }
            }
        } else {
            $id = $this->State->field('id', array('State.slug' => $slug));
            $this->State->id = $id;
            $this->data = $this->State->read();
            $this->request->data['State']['old_name'] = $this->data['State']['state_name'];
        }
    }

    public function admin_activateState($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->State->field('id', array('State.slug' => $slug));
            $cnd = array("State.id = $id");
            $this->State->updateAll(array('State.status' => "'1'"), $cnd);
            $this->set('action', '/admin/states/deactivateState/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivateState($slug = NULL, $parentSlug = null) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->State->field('id', array('State.slug' => $slug));
            $cnd = array("State.id = $id");
            $this->State->updateAll(array('State.status' => "'0'"), $cnd);
            $this->set('action', '/admin/states/activateState/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);


            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deleteState($slug = null, $cslug = null) {
        $id = $this->State->field('id', array('State.slug' => $slug));
        if ($id) {

            $this->City->deleteAll(array('City.state_id' => $id));
            $this->State->delete($id);
            $this->Session->setFlash('State deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'states', 'action' => 'index', $cslug));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'states', 'action' => 'index', $cslug));
        }
    }

    function admin_getStates($modal = 'User', $required = 'required') {
        $this->layout = '';
        $country_id = $this->data[$modal]['country_id'];
        if (!empty($country_id)) {
            $state_list = $this->State->getStateList($country_id);
        }

        $this->set('stateList', $state_list);
        $this->set('modal', $modal);
        $this->set('required', $required);
    }

}

?>
