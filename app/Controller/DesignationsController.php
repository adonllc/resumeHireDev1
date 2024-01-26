<?php

class DesignationsController extends AppController {

    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'Skill');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Skill.name' => 'asc'));
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

    public function admin_index($slug = null) {

        $this->layout = "admin";
        $this->set('designationlist', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Designation list");

        $condition = array('Skill.type' => 'Designation');
        $separator = array();
        $urlSeparator = array();
        $c_word = '';

        $this->set('slug', $slug);

        if (!empty($this->data)) {
            if (isset($this->data['Skill']['name']) && $this->data['Skill']['name'] != '') {
                $c_word = trim($this->data['Skill']['name']);
            }

            if (isset($this->data['Skill']['action'])) {
                $idList = $this->data['Skill']['idList'];
                if ($idList) {
                    if ($this->data['Skill']['action'] == "activate") {
                        $cnd = array("Skill.id IN ($idList) ");
                        $this->Skill->updateAll(array('Skill.status' => "'1'"), $cnd);
                    } elseif ($this->data['Skill']['action'] == "deactivate") {
                        $cnd = array("Skill.id IN ($idList) ");
                        $this->Skill->updateAll(array('Skill.status' => "'0'"), $cnd);
                    } elseif ($this->data['Skill']['action'] == "delete") {
                        $cnd = array("Skill.id IN ($idList) ");
                        $this->Skill->deleteAll($cnd);
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
            $condition[] = " (Skill.name like '%" . addslashes($c_word) . "%')  ";
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

        $this->paginate['Skill'] = array(
            'conditions' => $condition,
            'order' => array('Skill.id' => 'DESC'),
            'limit' => '30'
        );

        $this->set('designations', $this->paginate('Skill', $condition));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/designations/';
            $this->render('index');
        }
    }

    public function admin_adddesignations() {


        $this->set('adddesignation', 'active');
        $this->set('designationlist', '');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Designation");

        if ($this->data) {

            if (empty($this->data["Skill"]["name"])) {
                $msgString .="- Skill Name is required field.<br>";
            } else {

                $sw = explode(",", trim($this->data['Skill']['name']));



                foreach ($sw as $k => $v) {
                    if (isset($msgString) && $msgString != '') {
                        $this->Session->setFlash($msgString, 'error_msg');
                    } else {
                        $fin = $this->Skill->find('all', array('conditions' => array('Skill.type' => 'Designation')));
                        if (isset($fin) && count($fin) > 0 && is_array($fin)) {
                            if ($this->Skill->in_array_r(trim($v), $fin)) {
                                $this->Session->setFlash('Designation Already Exists', 'error_msg');
                                $this->redirect('/admin/designations/adddesignations');
                            } else {
                                if (!empty($v)) {
                                    $s['Skill']['slug'] = $this->stringToSlugUnique($v, 'Skill', 'slug');
                                    $s['Skill']['name'] = $v;
                                    $s['Skill']['type'] = 'Designation';
                                    $this->Skill->saveall($s);
                                    //  unset($s);
                                }
                            }
                        } else {
                            //die("dsa");
                            if (!empty($v)) {

                                $s['Skill']['slug'] = $this->stringToSlugUnique($v, 'Skill', 'slug');
                                $s['Skill']['name'] = $v;
                                $s['Skill']['type'] = 'Designation';
                                $this->Skill->saveAll($s);
                                unset($s);
                            }
                        }
                    }
                }

                $this->Session->setFlash('Designation Added Successfully', 'success_msg');
                $this->redirect('/admin/designations/index');
            }
        }
    }

    public function admin_editdesignation($slug = null) {

        $this->set('designationlist', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Designation");


        if ($this->data) {

            $find = $this->Skill->field('id', array('Skill.name' => $this->data["Skill"]["name"], 'Skill.slug <> "' . $slug . '"', 'Skill.type' => 'Designation'));

            // pr($find);exit;
            if (isset($find) && $find > 0 && $find != $slug) {
                $msgString .="- Designation Name already exists.<br>";
            }

            if (empty($this->data["Skill"]["name"])) {
                $msgString .="- Designation Name is required field.<br>";
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if ($this->Skill->save($this->data)) {
                    $this->Session->setFlash('Designation updated successfully', 'success_msg');
                    $this->redirect('/admin/designations/index');
                }
            }
        } else {
            $id = $this->Skill->field('id', array('Skill.slug' => $slug));
            $this->Skill->id = $id;
            $this->data = $this->Skill->read();
        }
    }

    public function admin_deleteDesignation($id = null) {
        $id = $this->Skill->field('id', array('Skill.slug' => $id));
        if ($id) {
            //$this->Skill->deleteAll(array('Skill.parent_id'=>$id));
            $this->Skill->delete($id);
            $this->Session->setFlash('Designation deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'designations', 'action' => 'index'));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'designations', 'action' => 'index'));
        }
    }

    public function admin_activatedesignation($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Skill->field('id', array('Skill.slug' => $slug));
            $cnd = array("Skill.id = $id");
            $this->Skill->updateAll(array('Skill.status' => "'1'"), $cnd);
            $this->set('action', '/admin/skills/deactivatedesignation/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivatedesignation($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Skill->field('id', array('Skill.slug' => $slug));
            $cnd = array("Skill.id = $id");
            $this->Skill->updateAll(array('Skill.status' => "'0'"), $cnd);
            $this->set('action', '/admin/skills/activatedesignation/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

}

?>
