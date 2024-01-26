<?php

class CountriesController extends AppController {

    public $name = 'Countries';
    public $uses = array('Admin', 'Country', 'City', 'State');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Country.name' => 'asc'));
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
        $this->set('country_list', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "List Countries");

        $condition = array();
        $separator = array();
        $urlSeparator = array();
        $name = '';

        if (!empty($this->data)) {
            if (isset($this->data['Country']['name']) && $this->data['Country']['name'] != '') {
                $name = trim($this->data['Country']['name']);
            }

            if (isset($this->data['Country']['action'])) {
                $idList = $this->data['Country']['idList'];
                if ($idList) {
                    if ($this->data['Country']['action'] == "activate") {
                        $cnd = array("Country.id IN ($idList) ");
                        $this->Country->updateAll(array('Country.status' => "'1'"), $cnd);
                    } elseif ($this->data['Country']['action'] == "deactivate") {
                        $cnd = array("Country.id IN ($idList) ");
                        $this->Country->updateAll(array('Country.status' => "'0'"), $cnd);
                    } elseif ($this->data['Country']['action'] == "delete") {
                        $cnd = array("Country.id IN ($idList) ");
                        $this->Country->deleteAll($cnd);

                        $this->City->deleteAll(array("City.country_id IN ($idList)"));
                        $this->State->deleteAll(array("State.country_id IN ($idList)"));
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
            $condition[] = " (Country.country_name like '%" . addslashes($name) . "%')  ";
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

        $this->paginate['Country'] = array(
            'conditions' => $condition,
            'order' => array('Country.id' => 'DESC'),
            'limit' => '50'
        );


        $this->set('countries', $this->paginate('Country'));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/countries/';
            $this->render('index');
        }
    }

    public function admin_addcountry() {
        $this->set('add_country', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Country");

        if ($this->data) {

            if (empty($this->data["Country"]["country_name"])) {
                $msgString .="- Country name is required field.<br>";
            } elseif ($this->Country->isRecordUniqueCountry($this->data["Country"]["country_name"]) == false) {
                $msgString .="- Country name already exists.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data['Country']['slug'] = $this->stringToSlugUnique($this->data["Country"]["country_name"], 'Country');
                $this->request->data['Country']['status'] = '1';
                if ($this->Country->save($this->data)) {
                    $this->Session->setFlash('Country added successfully', 'success_msg');
                    $this->redirect('/admin/countries/index/');
                }
            }
        }
    }

    public function admin_editcountry($slug = null) {

        $this->set('country_list', 'active');
        $msgString = "";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Country");

        if ($this->data) {

            if (empty($this->data["Country"]["country_name"])) {
                $msgString .="- Country name is required field.<br>";
            } elseif (strtolower($this->data["Country"]["country_name"]) != strtolower($this->data["Country"]["old_name"])) {
                if ($this->Country->isRecordUniqueCountry($this->data["Country"]["country_name"]) == false) {
                    $msgString .="- Country name already exists.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if ($this->Country->save($this->data)) {
                    $this->Session->setFlash('Country updated successfully', 'success_msg');
                    $this->redirect('/admin/countries/index');
                }
            }
        } else {
            $id = $this->Country->field('id', array('Country.slug' => $slug));
            $this->Country->id = $id;
            $this->data = $this->Country->read();
            $this->request->data['Country']['old_name'] = $this->data['Country']['country_name'];
        }
    }

    public function admin_activateCountry($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Country->field('id', array('Country.slug' => $slug));
            $cnd = array("Country.id = $id");
            $this->Country->updateAll(array('Country.status' => "'1'"), $cnd);
            $this->set('action', '/admin/countries/deactivateCountry/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivateCountry($slug = NULL, $parentSlug = null) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Country->field('id', array('Country.slug' => $slug));
            $cnd = array("Country.id = $id");
            $this->Country->updateAll(array('Country.status' => "'0'"), $cnd);
            $this->set('action', '/admin/countries/activateCountry/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);


            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deleteCountry($slug = null) {
        $id = $this->Country->field('id', array('Country.slug' => $slug));
        if ($id) {

            $this->City->deleteAll(array("City.country_id" => $id));
            $this->State->deleteAll(array("State.country_id" => $id));

            $this->Country->delete($id);
            $this->Session->setFlash('Country deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'countries', 'action' => 'index'));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'countries', 'action' => 'index'));
        }
    }

}

?>
