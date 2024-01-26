<?php

class LocationsController extends AppController {

    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'Location');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Location.name' => 'asc'));
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
        $this->set('locationlist', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Location list");

        $condition = array();
        $separator = array();
        $urlSeparator = array();
        $c_word = '';

        if (!empty($this->data)) {
            if (isset($this->data['Location']['name']) && $this->data['Location']['name'] != '') {
                $c_word = trim($this->data['Location']['name']);
            }

            if (isset($this->data['Location']['action'])) {
                $idList = $this->data['Location']['idList'];
                if ($idList) {
                     if ($this->data['Location']['action'] == "activate") {
                        $cnd = array("Location.id IN ($idList) ");
                        $this->Location->updateAll(array('Location.status' => "'1'"), $cnd);
                    } elseif ($this->data['Location']['action'] == "deactivate") {
                        $cnd = array("Location.id IN ($idList) ");
                        $this->Location->updateAll(array('Location.status' => "'0'"), $cnd);
                    } elseif ($this->data['Location']['action'] == "delete") {
                        $cnd = array("Location.id IN ($idList) ");
                        $this->Location->deleteAll($cnd);
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
            $condition[] = " (Location.name like '%" . addslashes($c_word) . "%')  ";
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

        $this->paginate['Location'] = array(
            'conditions' => $condition,
            'order' => array('Location.id' => 'DESC'),
            'limit' => '30'
        );

        $this->set('locations', $this->paginate('Location', $condition));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/locations/';
            $this->render('index');
        }
    }

    public function admin_addlocations() {


        $this->set('addlocation', 'active');
        $this->set('locationlist', '');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Location");

        if ($this->data) {

            if (empty($this->data["Location"]["name"])) {
                $msgString .="- Location Name is required field.<br>";
            } else {
                $sw = explode(",", $this->data['Location']['name']);

                foreach ($sw as $k => $v) {
                    if (isset($msgString) && $msgString != '') {
                        $this->Session->setFlash($msgString, 'error_msg');
                    } else {
                        $fin = $this->Location->find('all');
                        if (isset($fin) && count($fin) > 0 && is_array($fin)) {
                            if ($this->Location->in_array_r($v, $fin)) {
                                $this->Session->setFlash('Location Already Exists', 'error_msg');
                                $this->redirect('/admin/locations/index');
                            } else {
                                if (!empty($v)) {
                                    $s['Location']['slug'] = $this->stringToSlugUnique($v, 'Location', 'slug');
                                    $s['Location']['name'] = $v;
                                    $this->Location->saveall($s);
                                    //  unset($s);
                                }
                            }
                        } else {
                            //die("dsa");
                            if (!empty($v)) {

                                $s['Location']['slug'] = $this->stringToSlugUnique($v, 'Location', 'slug');
                                $s['Location']['name'] = $v;
                                $this->Location->saveAll($s);
                                unset($s);
                            }
                        }
                    }
                }

                $this->Session->setFlash('Location Added Successfully', 'success_msg');
                $this->redirect('/admin/locations/index');
            }
        }
    }

    public function admin_editlocation($slug = null) {

        $this->set('locationlist', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Location");


        if ($this->data) {

            $find = $this->Location->field('id', array('Location.name' => $this->data["Location"]["name"], 'Location.slug <> "' . $slug . '"'));

            if (isset($find) && $find > 0 && $find != $slug) {
                $msgString .="- Location Name already exists.<br>";
            }

            if (empty($this->data["Location"]["name"])) {
                $msgString .="- Location Name is required field.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if ($this->Location->save($this->data)) {
                    $this->Session->setFlash('Location updated successfully', 'success_msg');
                    $this->redirect('/admin/locations/index');
                }
            }
        } else {
            $id = $this->Location->field('id', array('Location.slug' => $slug));
            $this->Location->id = $id;
            $this->data = $this->Location->read();
        }
    }

    public function admin_deleteLocation($id = null) {
        $id = $this->Location->field('id', array('Location.slug' => $id));
        if ($id) {
            //$this->Location->deleteAll(array('Location.parent_id'=>$id));
            $this->Location->delete($id);
            $this->Session->setFlash('Location deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'locations', 'action' => 'index'));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'locations', 'action' => 'index'));
        }
    }
    
    public function admin_activateLocation($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Location->field('id', array('Location.slug' => $slug));
            $cnd = array("Location.id = $id");
            $this->Location->updateAll(array('Location.status' => "'1'"), $cnd);
            $this->set('action', '/admin/locations/deactivateLocation/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivateLocation($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Location->field('id', array('Location.slug' => $slug));
            $cnd = array("Location.id = $id");
            $this->Location->updateAll(array('Location.status' => "'0'"), $cnd);
            $this->set('action', '/admin/locations/activateLocation/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);

            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }
    
      
     public function alllocations() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('home', 'All locations', true));

        $jobsByCity = $this->Location->find('list', array('conditions' => array('Location.status' => 1), 'fields' => array('slug', 'name'), 'order' => array('Location.name' => 'ASC')));
        $this->set('jobsByCity', $jobsByCity);
    }

}

?>
