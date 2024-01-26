<?php

class CurrenciesController extends AppController {

    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'Currency');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Currency.name' => 'asc'));
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
        $this->set('currencieslist', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Currency list");

        $condition = array();
        $separator = array();
        $urlSeparator = array();
        $c_word = '';

        if (!empty($this->data)) {
            if (isset($this->data['Currency']['name']) && $this->data['Currency']['name'] != '') {
                $c_word = trim($this->data['Currency']['name']);
            }

            if (isset($this->data['Currency']['action'])) {
                $idList = $this->data['Currency']['idList'];
                if ($idList) {
                     if ($this->data['Currency']['action'] == "activate") {
                        $cnd = array("Currency.id IN ($idList) ");
                        $this->Currency->updateAll(array('Currency.status' => "'1'"), $cnd);
                    } elseif ($this->data['Currency']['action'] == "deactivate") {
                        $cnd = array("Currency.id IN ($idList) ");
                        $this->Currency->updateAll(array('Currency.status' => "'0'"), $cnd);
                    } elseif ($this->data['Currency']['action'] == "delete") {
                        $cnd = array("Currency.id IN ($idList) ");
                        $this->Currency->deleteAll($cnd);
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
            $condition[] = " (Currency.name like '%" . addslashes($c_word) . "%')  ";
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

        $this->paginate['Currency'] = array(
            'conditions' => $condition,
            'order' => array('Currency.id' => 'DESC'),
            'limit' => '30'
        );

        $this->set('currencies', $this->paginate('Currency', $condition));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/currencies/';
            $this->render('index');
        }
    }

    public function admin_add() {


        $this->set('addcurrencies', 'active');
        $this->set('currencieslist', '');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Currency");

        if ($this->data) {

            if (empty($this->data["Currency"]["name"])) {
                $msgString .="- Currency Name is required field.<br>";
            } else {
               $this->request->data['Currency']['status'] ='1'; 
               $this->request->data['Currency']['is_default'] ='0'; 
                $slug = $this->stringToSlugUnique($this->data["Currency"]["name"], 'Currency');
                $this->request->data['Currency']['slug'] = $slug;
                $this->Currency->save($this->data);
              
                $this->Session->setFlash('Currency Added Successfully', 'success_msg');
                $this->redirect('/admin/currencies/index');
            
            }
        }
    }

    public function admin_edit($slug = null) {

        $this->set('currencieslist', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Currency");


        if ($this->data) {

            $find = $this->Currency->field('id', array('Currency.name' => $this->data["Currency"]["name"], 'Currency.slug <> "' . $slug . '"'));

            if (isset($find) && $find > 0 && $find != $slug) {
                $msgString .="- Currency Name already exists.<br>";
            }

            if (empty($this->data["Currency"]["name"])) {
                $msgString .="- Currency Name is required field.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if ($this->Currency->save($this->data)) {
                    $this->Session->setFlash('Currency updated successfully', 'success_msg');
                    $this->redirect('/admin/currencies/index');
                }
            }
        } else {
            $id = $this->Currency->field('id', array('Currency.slug' => $slug));
            $this->Currency->id = $id;
            $this->data = $this->Currency->read();
        }
    }

    public function admin_delete($id = null) {
        $id = $this->Currency->field('id', array('Currency.slug' => $id));
        if ($id) {
            //$this->Currency->deleteAll(array('Currency.parent_id'=>$id));
            $this->Currency->delete($id);
            $this->Session->setFlash('Currency deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'currencies', 'action' => 'index'));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'currencies', 'action' => 'index'));
        }
    }
    
    public function admin_defaultcurrency($id = null) {
        if ($id) {
            $cnd = array("Currency.id = $id");
            $this->Currency->updateAll(array('Currency.is_default' => "'0'"));
            $this->Currency->updateAll(array('Currency.is_default' => "'1'"), $cnd);
            $this->Session->setFlash('Currency deleted successfully', 'success_msg');
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
        }
        echo '1';exit;
    }
    
    public function admin_activate($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Currency->field('id', array('Currency.slug' => $slug));
            $cnd = array("Currency.id = $id");
            $this->Currency->updateAll(array('Currency.status' => "'1'"), $cnd);
            $this->set('action', '/admin/currencies/deactivate/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivate($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Currency->field('id', array('Currency.slug' => $slug));
            $cnd = array("Currency.id = $id");
            $this->Currency->updateAll(array('Currency.status' => "'0'"), $cnd);
            $this->set('action', '/admin/currencies/activate/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);

            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

}

?>
