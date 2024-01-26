<?php

class SpecializationsController extends AppController {

    public $name = 'Specializations';
    public $uses = array('Admin', 'Experience', 'Emailtemplate', 'User', 'Certificate', 'Country', 'State', 'City', 'Favorite', 'Job', 'JobApply', 'CoverLetter', 'Swear', 'PostCode', 'Course', 'Specialization', 'Education', 'Location');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Specialization.name' => 'asc'));
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
        $this->set('course_list', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "List Specialization");

        $courseInfo = $this->Course->findBySlug($cslug);
        if (!$courseInfo) {
            $this->redirect('/admin/courses/index/');
        }

        $condition = array('Specialization.course_id' => $courseInfo['Course']['id']);
        $separator = array();
        $urlSeparator = array();
        $name = '';

        $this->set('courseInfo', $courseInfo);
        $this->set('cslug', $cslug);


        if (!empty($this->data)) {
            if (isset($this->data['Specialization']['name']) && $this->data['Specialization']['name'] != '') {
                $name = trim($this->data['Specialization']['name']);
            }

            if (isset($this->data['Specialization']['action'])) {
                $idList = $this->data['Specialization']['idList'];
                if ($idList) {
                    if ($this->data['Specialization']['action'] == "activate") {
                        $cnd = array("Specialization.id IN ($idList) ");
                        $this->Specialization->updateAll(array('Specialization.status' => "'1'"), $cnd);
                    } elseif ($this->data['Specialization']['action'] == "deactivate") {
                        $cnd = array("Specialization.id IN ($idList) ");
                        $this->Specialization->updateAll(array('Specialization.status' => "'0'"), $cnd);
                    } elseif ($this->data['Specialization']['action'] == "delete") {
                        $cnd = array("Specialization.id IN ($idList) ");
                        $this->Specialization->deleteAll($cnd);
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
            $condition[] = " (Specialization.name like '%" . addslashes($name) . "%')  ";
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

        $this->paginate['Specialization'] = array(
            'conditions' => $condition,
            'order' => array('Specialization.id' => 'DESC'),
            'limit' => '50'
        );


        $this->set('specializations', $this->paginate('Specialization'));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/specializations/';
            $this->render('index');
        }
    }

    public function admin_addspecialization($cslug = null) {

        $this->set('course_list', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Specialization");

        $courseInfo = $this->Course->findBySlug($cslug);
        if (!$courseInfo) {
            $this->redirect('/admin/courses/index/');
        }

        $this->set('courseInfo', $courseInfo);
        $this->set('cslug', $cslug);


        if ($this->data) {

            // pr($this->data);exit;

            if (empty($this->data["Specialization"]["name"])) {
                $msgString .="- Specialization name is required field.<br>";
            } elseif ($this->Specialization->isRecordUniqueSpecialization($this->data["Specialization"]["name"], $courseInfo['Course']['id']) == false) {
                $msgString .="- Specialization name already exists.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data['Specialization']['type'] = 'Basic';
                $this->request->data['Specialization']['slug'] = $this->stringToSlugUnique($this->data["Specialization"]["name"], 'Specialization');
                $this->request->data['Specialization']['status'] = '1';
                $this->request->data['Specialization']['course_id'] = $courseInfo['Course']['id'];
                if ($this->Specialization->save($this->data)) {
                    $this->Session->setFlash('Specialization added successfully', 'success_msg');
                    $this->redirect('/admin/specializations/index/' . $cslug);
                }
            }
        }
    }

    public function admin_editspecialization($slug = null, $cslug = null) {

        $this->set('course_list', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Specialization");

        $courseInfo = $this->Course->findBySlug($cslug);
        if (!$courseInfo) {
            $this->redirect('/admin/courses/index/');
        }
        $this->set('courseInfo', $courseInfo);
        $this->set('cslug', $cslug);


        if ($this->data) {


            if (empty($this->data["Specialization"]["name"])) {
                $msgString .="- Specialization name is required field.<br>";
            } elseif (strtolower($this->data["Specialization"]["name"]) != strtolower($this->data["Specialization"]["old_name"])) {
                if ($this->Specialization->isRecordUniqueSpecialization($this->data["Specialization"]["name"], $courseInfo['Course']['id']) == false) {
                    $msgString .="- Specialization name already exists.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data['Specialization']['type'] = 'Basic';
                if ($this->Specialization->save($this->data)) {
                    $this->Session->setFlash('Specialization updated successfully', 'success_msg');
                    $this->redirect('/admin/specializations/index/' . $cslug);
                }
            }
        } else {
            $id = $this->Specialization->field('id', array('Specialization.slug' => $slug));
            $this->Specialization->id = $id;
            $this->data = $this->Specialization->read();
            $this->request->data['Specialization']['old_name'] = $this->data['Specialization']['name'];
        }
    }

    public function admin_activateSpecialization($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Specialization->field('id', array('Specialization.slug' => $slug));
            $cnd = array("Specialization.id = $id");
            $this->Specialization->updateAll(array('Specialization.status' => "'1'"), $cnd);
            $this->set('action', '/admin/specializations/deactivateSpecialization/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivateSpecialization($slug = NULL, $parentSlug = null) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Specialization->field('id', array('Specialization.slug' => $slug));
            $cnd = array("Specialization.id = $id");
            $this->Specialization->updateAll(array('Specialization.status' => "'0'"), $cnd);
            $this->set('action', '/admin/specializations/activateSpecialization/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);


            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deleteSpecialization($slug = null, $cslug = null) {
           
        $id = $this->Specialization->field('id', array('Specialization.slug' => $slug));
        if ($id) {

            $this->Specialization->delete($id);
            $this->Session->setFlash('Specialization deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'specializations', 'action' => 'index', $cslug));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'specializations', 'action' => 'index', $cslug));
        }
    }

}

?>
