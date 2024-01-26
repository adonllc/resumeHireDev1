<?php

class CoursesController extends AppController {

    public $name = 'Courses';
    public $uses = array('Admin', 'Experience', 'Emailtemplate', 'User', 'Certificate', 'Country', 'State', 'City', 'Favorite', 'Job', 'JobApply', 'CoverLetter', 'Swear', 'PostCode', 'Course', 'Specialization', 'Education', 'Location');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Course.name' => 'asc'));
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
        $this->set('course_list', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "List Courses");

        $condition = array();
        $separator = array();
        $urlSeparator = array();
        $name = '';

        if (!empty($this->data)) {
            if (isset($this->data['Course']['name']) && $this->data['Course']['name'] != '') {
                $name = trim($this->data['Course']['name']);
            }

            if (isset($this->data['Course']['action'])) {
                $idList = $this->data['Course']['idList'];
                if ($idList) {
                    if ($this->data['Course']['action'] == "activate") {
                        $cnd = array("Course.id IN ($idList) ");
                        $this->Course->updateAll(array('Course.status' => "'1'"), $cnd);
                    } elseif ($this->data['Course']['action'] == "deactivate") {
                        $cnd = array("Course.id IN ($idList) ");
                        $this->Course->updateAll(array('Course.status' => "'0'"), $cnd);
                    } elseif ($this->data['Course']['action'] == "delete") {
                        $cnd = array("Course.id IN ($idList) ");
                        $this->Course->deleteAll($cnd);

                        $this->Specialization->deleteAll(array("Specialization.course_id IN ($idList)"));
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
            $condition[] = " (Course.name like '%" . addslashes($name) . "%')  ";
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

        $this->paginate['Course'] = array(
            'conditions' => $condition,
            'order' => array('Course.id' => 'DESC'),
            'limit' => '50'
        );


        $this->set('courses', $this->paginate('Course'));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/courses/';
            $this->render('index');
        }
    }

    public function admin_addcourse() {
        $this->set('add_course', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Course");

        if ($this->data) {

            if (empty($this->data["Course"]["name"])) {
                $msgString .="- Course name is required field.<br>";
            } elseif ($this->Course->isRecordUniqueCourse($this->data["Course"]["name"]) == false) {
                $msgString .="- Course name already exists.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data['Course']['type'] = 'Basic';
                $this->request->data['Course']['slug'] = $this->stringToSlugUnique($this->data["Course"]["name"], 'Course');
                $this->request->data['Course']['status'] = '1';
                if ($this->Course->save($this->data)) {
                    $this->Session->setFlash('Course added successfully', 'success_msg');
                    $this->redirect('/admin/courses/index/');
                }
            }
        }
    }

    public function admin_editcourse($slug = null) {

        $this->set('course_list', 'active');
        $msgString = "";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Course");

        if ($this->data) {

            if (empty($this->data["Course"]["name"])) {
                $msgString .="- Course name is required field.<br>";
            } elseif (strtolower($this->data["Course"]["name"]) != strtolower($this->data["Course"]["old_name"])) {
                if ($this->Course->isRecordUniqueCourse($this->data["Course"]["name"]) == false) {
                    $msgString .="- Course name already exists.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data['Course']['type'] = 'Basic';
                if ($this->Course->save($this->data)) {
                    $this->Session->setFlash('Course updated successfully', 'success_msg');
                    $this->redirect('/admin/courses/index');
                }
            }
        } else {
            $id = $this->Course->field('id', array('Course.slug' => $slug));
            $this->Course->id = $id;
            $this->data = $this->Course->read();
            $this->request->data['Course']['old_name'] = $this->data['Course']['name'];
        }
    }

    public function admin_activateCourse($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Course->field('id', array('Course.slug' => $slug));
            $cnd = array("Course.id = $id");
            $this->Course->updateAll(array('Course.status' => "'1'"), $cnd);
            $this->set('action', '/admin/courses/deactivateCourse/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivateCourse($slug = NULL, $parentSlug = null) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Course->field('id', array('Course.slug' => $slug));
            $cnd = array("Course.id = $id");
            $this->Course->updateAll(array('Course.status' => "'0'"), $cnd);
            $this->set('action', '/admin/courses/activateCourse/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);


            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deleteCourse($slug = null) {

        $id = $this->Course->field('id', array('Course.slug' => $slug));

        if ($id) {

            $this->Specialization->deleteAll(array("Specialization.course_id" => $id));

            $this->Course->delete($id);
            $this->Session->setFlash('Course deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'courses', 'action' => 'index'));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'courses', 'action' => 'index'));
        }
    }

}

?>
