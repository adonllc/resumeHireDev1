<?php

class KeywordsController extends AppController {

    public $c_word = 'Keywords';
    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'Keyword', 'Skill', 'Course', 'Specialization');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Keyword.id' => 'desc'));
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
        $this->set('keywordlist', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Search Keyword list");

        $condition = array('Keyword.type' => 'Search', 'Keyword.approval_status' => '1');
        $separator = array();
        $urlSeparator = array();
        $c_word = '';

        if (!empty($this->data)) {
            if (isset($this->data['Keyword']['name']) && $this->data['Keyword']['name'] != '') {
                $c_word = trim($this->data['Keyword']['name']);
            }

            if (isset($this->data['Keyword']['action'])) {
                $idList = $this->data['Keyword']['idList'];
                if ($idList) {
                    if ($this->data['Keyword']['action'] == "activate") {
                        $cnd = array("Keyword.id IN ($idList) ");
                        $this->Keyword->updateAll(array('Keyword.status' => "'1'"), $cnd);
                    } elseif ($this->data['Keyword']['action'] == "deactivate") {
                        $cnd = array("Keyword.id IN ($idList) ");
                        $this->Keyword->updateAll(array('Keyword.status' => "'0'"), $cnd);
                    } elseif ($this->data['Keyword']['action'] == "delete") {
                        $cnd = array("Keyword.id IN ($idList) ");

                        $this->Keyword->deleteAll($cnd);
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
            $condition[] = " (Keyword.name like '%" . addslashes($c_word) . "%')  ";
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

        $this->paginate['Keyword'] = array(
            'conditions' => $condition,
            'order' => array('Keyword.id' => 'DESC'),
            'limit' => '30'
        );

        $this->set('keywords', $this->paginate('Keyword', $condition));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/keywords/';
            $this->render('index');
        }
    }

    public function admin_add() {


        $this->set('keywordlist', 'active');
        $this->set('keywordlist', '');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Search Keyword");

        if ($this->data) {

            if (empty($this->data["Keyword"]["name"])) {
                $msgString .="- Keyword Name is required field.<br>";
            } else {

                $this->request->data['Keyword']['slug'] = $this->stringToSlugUnique($this->data["Keyword"]["name"], 'Keyword');
                $this->request->data['Keyword']['status'] = '1';
                $this->request->data['Keyword']['approval_status'] = '1';
                $this->request->data['Keyword']['type'] = 'Search';
                if ($this->Keyword->save($this->data)) {
                    $this->Session->setFlash('Search Keyword Added Successfully', 'success_msg');
                    $this->redirect('/admin/keywords/index');
                }
            }
        }
    }

    public function admin_edit($slug = null) {

        $this->set('keywordlist', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Search Keyword");


        if ($this->data) {

            $find = $this->Keyword->field('id', array('Keyword.name' => $this->data["Keyword"]["name"], 'Keyword.slug <> "' . $slug . '"'));

            // pr($find);exit;
            if (isset($find) && $find > 0 && $find != $slug) {

                $msgString .="- Keyword Name already exists.<br>";
            }

            if (empty($this->data["Keyword"]["name"])) {
                $msgString .="- Keyword Name is required field.<br>";
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if ($this->Keyword->save($this->data)) {
                    $this->Session->setFlash('Search Keyword updated successfully', 'success_msg');
                    $this->redirect('/admin/keywords/index');
                }
            }
        } else {


            $id = $this->Keyword->field('id', array('Keyword.slug' => $slug));
            $this->Keyword->id = $id;
            $this->data = $this->Keyword->read();
        }
    }

    public function admin_delete($id = null) {
        $id = $this->Keyword->field('id', array('Keyword.slug' => $id));
        if ($id) {
            $this->Keyword->delete($id);
            $this->Session->setFlash('Search Keyword deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'keywords', 'action' => 'index'));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'keywords', 'action' => 'index'));
        }
    }

    public function admin_activate($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Keyword->field('id', array('Keyword.slug' => $slug));
            $cnd = array("Keyword.id = $id");
            $this->Keyword->updateAll(array('Keyword.status' => "'1'"), $cnd);
            $this->set('action', '/admin/keywords/deactivate/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivate($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Keyword->field('id', array('Keyword.slug' => $slug));
            $cnd = array("Keyword.id = $id");
            $this->Keyword->updateAll(array('Keyword.status' => "'0'"), $cnd);
            $this->set('action', '/admin/keywords/activate/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    /* --------------------------------------------------------  
     * -------------------  Keyword Search ---------------------
     */

    public function admin_jobs() {

        $this->layout = "admin";
        $this->set('jobslist', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Job Keyword list");

        $condition = array('Keyword.type' => 'Job', 'Keyword.approval_status' => '1');
        $separator = array();
        $urlSeparator = array();
        $c_word = '';

        if (!empty($this->data)) {
            if (isset($this->data['Keyword']['name']) && $this->data['Keyword']['name'] != '') {
                $c_word = trim($this->data['Keyword']['name']);
            }

            if (isset($this->data['Keyword']['action'])) {
                $idList = $this->data['Keyword']['idList'];
                if ($idList) {
                    if ($this->data['Keyword']['action'] == "activate") {
                        $cnd = array("Keyword.id IN ($idList) ");
                        $this->Keyword->updateAll(array('Keyword.status' => "'1'"), $cnd);
                    } elseif ($this->data['Keyword']['action'] == "deactivate") {
                        $cnd = array("Keyword.id IN ($idList) ");
                        $this->Keyword->updateAll(array('Keyword.status' => "'0'"), $cnd);
                    } elseif ($this->data['Keyword']['action'] == "delete") {
                        $cnd = array("Keyword.id IN ($idList) ");

                        $this->Keyword->deleteAll($cnd);
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
            $condition[] = " (Keyword.name like '%" . addslashes($c_word) . "%')  ";
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

        $this->paginate['Keyword'] = array(
            'conditions' => $condition,
            'order' => array('Keyword.id' => 'DESC'),
            'limit' => '30'
        );

        $this->set('keywords', $this->paginate('Keyword', $condition));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/keywords/';
            $this->render('jobs');
        }
    }

    public function admin_addjobs() {


        $this->set('jobslist', 'active');
        $this->set('jobslist', '');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Jobs Keyword");

        if ($this->data) {

            if (empty($this->data["Keyword"]["name"])) {
                $msgString .="- Keyword Name is required field.<br>";
            } else {

                $this->request->data['Keyword']['slug'] = $this->stringToSlugUnique($this->data["Keyword"]["name"], 'Keyword');
                $this->request->data['Keyword']['status'] = '1';
                $this->request->data['Keyword']['approval_status'] = '1';
                $this->request->data['Keyword']['type'] = 'Job';
                if ($this->Keyword->save($this->data)) {
                    $this->Session->setFlash('Jobs Keyword Added Successfully', 'success_msg');
                    $this->redirect('/admin/keywords/jobs');
                }
            }
        }
    }

    public function admin_editjobs($slug = null) {

        $this->set('jobslist', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Jobs Keyword");


        if ($this->data) {

            $find = $this->Keyword->field('id', array('Keyword.name' => $this->data["Keyword"]["name"], 'Keyword.slug <> "' . $slug . '"'));

            // pr($find);exit;
            if (isset($find) && $find > 0 && $find != $slug) {

                $msgString .="- Keyword Name already exists.<br>";
            }

            if (empty($this->data["Keyword"]["name"])) {
                $msgString .="- Keyword Name is required field.<br>";
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if ($this->Keyword->save($this->data)) {
                    $this->Session->setFlash('Jobs Keyword updated successfully', 'success_msg');
                    $this->redirect('/admin/keywords/jobs');
                }
            }
        } else {


            $id = $this->Keyword->field('id', array('Keyword.slug' => $slug));
            $this->Keyword->id = $id;
            $this->data = $this->Keyword->read();
        }
    }

    public function admin_deletejobs($id = null) {
        $id = $this->Keyword->field('id', array('Keyword.slug' => $id));
        if ($id) {
            $this->Keyword->delete($id);
            $this->Session->setFlash('Jobs Keyword deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'keywords', 'action' => 'jobs'));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'keywords', 'action' => 'jobs'));
        }
    }

    public function admin_activatejobs($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Keyword->field('id', array('Keyword.slug' => $slug));
            $cnd = array("Keyword.id = $id");
            $this->Keyword->updateAll(array('Keyword.status' => "'1'"), $cnd);
            $this->set('action', '/admin/keywords/deactivatejobs/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivatejobs($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Keyword->field('id', array('Keyword.slug' => $slug));
            $cnd = array("Keyword.id = $id");
            $this->Keyword->updateAll(array('Keyword.status' => "'0'"), $cnd);
            $this->set('action', '/admin/keywords/activatejobs/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    /* --------------------------------------------------------  
     * -------------------  Keyword Search ---------------------
     */

    public function admin_requests() {

        $this->layout = "admin";
        $this->set('requestslist', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Request Keyword list");

        $condition = array('Keyword.approval_status' => '0');
        $separator = array();
        $urlSeparator = array();
        $c_word = '';

        if (!empty($this->data)) {
            if (isset($this->data['Keyword']['name']) && $this->data['Keyword']['name'] != '') {
                $c_word = trim($this->data['Keyword']['name']);
            }

            if (isset($this->data['Keyword']['action'])) {
                $idList = $this->data['Keyword']['idList'];
                if ($idList) {
                    if ($this->data['Keyword']['action'] == "activate") {
                        $cnd = array("Keyword.id IN ($idList) ");
                        $this->Keyword->updateAll(array('Keyword.status' => "'1'"), $cnd);
                    } elseif ($this->data['Keyword']['action'] == "deactivate") {
                        $cnd = array("Keyword.id IN ($idList) ");
                        $this->Keyword->updateAll(array('Keyword.status' => "'0'"), $cnd);
                    } elseif ($this->data['Keyword']['action'] == "delete") {
                        $cnd = array("Keyword.id IN ($idList) ");

                        $this->Keyword->deleteAll($cnd);
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
            $condition[] = " (Keyword.name like '%" . addslashes($c_word) . "%')  ";
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

        $this->paginate['Keyword'] = array(
            'conditions' => $condition,
            'order' => array('Keyword.id' => 'DESC'),
            'limit' => '30'
        );

        $this->set('keywords', $this->paginate('Keyword', $condition));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/keywords/';
            $this->render('requests');
        }
    }

    public function admin_deleterequests($id = null) {
        $id = $this->Keyword->field('id', array('Keyword.slug' => $id));
        if ($id) {
            $this->Keyword->delete($id);
            $this->Session->setFlash('Requests Keyword deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'keywords', 'action' => 'requests'));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'keywords', 'action' => 'requests'));
        }
    }

    public function admin_approveStatus($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $keywordData = $this->Keyword->findByslug($slug);
            $id = $keywordData['Keyword']['id'];
            $type = $keywordData['Keyword']['type'];
            $name = $keywordData['Keyword']['name'];
            if ($type == 'Search' || $type == 'Job') {
                $cnd = array("Keyword.id = $id");
                $this->Keyword->updateAll(array('Keyword.approval_status' => "'1'"), $cnd);
            } elseif ($type == 'Primary') {

                $this->request->data['Course']['type'] = 'Primary';
                $this->request->data['Course']['slug'] = $this->stringToSlugUnique($name, 'Course');
                $this->request->data['Course']['name'] = $name;
                $this->request->data['Course']['status'] = '1';
                if ($this->Course->save($this->data)) {
                    $this->Keyword->delete($id);
                }
            } elseif ($type == 'Post Graduate') {
                $this->request->data['Course']['type'] = 'Post Graduate';
                $this->request->data['Course']['slug'] = $this->stringToSlugUnique($name, 'Course');
                $this->request->data['Course']['name'] = $name;
                $this->request->data['Course']['status'] = '1';
                if ($this->Course->save($this->data)) {
                    $this->Keyword->delete($id);
                }
            } elseif ($type == 'Specialization') {
                $course_id = $keywordData['Keyword']['course_id'];
                if ($course_id) {
                    $this->request->data['Specialization']['type'] = 'Basic';
                    $this->request->data['Specialization']['slug'] = $this->stringToSlugUnique($name, 'Specialization');
                    $this->request->data['Specialization']['name'] = $name;
                    $this->request->data['Specialization']['status'] = '1';
                    $this->request->data['Specialization']['course_id'] = $course_id;
                    if ($this->Specialization->save($this->data)) {
                        $this->Keyword->delete($id);
                    }
                } else {
                    $msgString = "Please update course field value before specialization approval.";
                    $this->Session->setFlash($msgString, 'error_msg');
                }
            }
            exit;
        } exit;
    }

    public function ajaxkeywordlist() {
        $this->layout = '';
        $keyword = '';
        $suggesstion = '';
        $search = '';
        $ids = '';

        if ($this->request->is('ajax')) {
            $search = trim($this->data['search']);
            if ($search == 'Designation') {
                $condition = array('Skill.status' => '1');
                $keyword = trim($this->data['keyword']);
                $suggesstion = trim($this->data['suggesstion']);

                $ids = trim($this->data['ids']);
                $condition[] = " (Skill.type = 'Designation')  ";
                $condition[] = " (Skill.name like '%" . addslashes($keyword) . "%')  ";

                $KeywordList = $this->Skill->find('list', array('conditions' => $condition, 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));

                $this->set('keyword', $keyword);
                $this->set('suggesstion', $suggesstion);
                $this->set('search', $search);
                $this->set('ids', $ids);
                $this->set('KeywordList', $KeywordList);

                $this->viewPath = 'Elements' . DS . 'admin/keywords/';
                $this->render('ajaxkeywordlist');
            } elseif ($search == 'Primary') {
                $condition = array('Course.status' => '1');
                $keyword = trim($this->data['keyword']);
                $suggesstion = trim($this->data['suggesstion']);

                $ids = trim($this->data['ids']);
                $condition[] = " (Course.type = 'Primary')  ";
                $condition[] = " (Course.name like '%" . addslashes($keyword) . "%')  ";

                $KeywordList = $this->Course->find('list', array('conditions' => $condition, 'fields' => array('Course.id', 'Course.name'), 'order' => 'Course.name asc'));

                $this->set('keyword', $keyword);
                $this->set('suggesstion', $suggesstion);
                $this->set('search', $search);
                $this->set('ids', $ids);
                $this->set('KeywordList', $KeywordList);

                $this->viewPath = 'Elements' . DS . 'admin/keywords/';
                $this->render('ajaxkeywordlist');
            } elseif ($search == 'Post Graduate') {
                $condition = array('Course.status' => '1');
                $keyword = trim($this->data['keyword']);
                $suggesstion = trim($this->data['suggesstion']);

                $ids = trim($this->data['ids']);
                $condition[] = " (Course.type = 'Post Graduate')  ";
                $condition[] = " (Course.name like '%" . addslashes($keyword) . "%')  ";

                $KeywordList = $this->Course->find('list', array('conditions' => $condition, 'fields' => array('Course.id', 'Course.name'), 'order' => 'Course.name asc'));

                $this->set('keyword', $keyword);
                $this->set('suggesstion', $suggesstion);
                $this->set('search', $search);
                $this->set('ids', $ids);
                $this->set('KeywordList', $KeywordList);

                $this->viewPath = 'Elements' . DS . 'admin/keywords/';
                $this->render('ajaxkeywordlist');
            } elseif ($search == 'Specialty') {
                $condition = array('Specialization.status' => '1');
                $keyword = trim($this->data['keyword']);
                $suggesstion = trim($this->data['suggesstion']);

                $ids = trim($this->data['ids']);
                $condition[] = " (Specialization.type = 'Basic')  ";
                $condition[] = " (Specialization.name like '%" . addslashes($keyword) . "%')  ";

                $KeywordList = $this->Specialization->find('list', array('conditions' => $condition, 'fields' => array('Specialization.id', 'Specialization.name'), 'order' => 'Specialization.name asc'));

                $this->set('keyword', $keyword);
                $this->set('suggesstion', $suggesstion);
                $this->set('search', $search);
                $this->set('ids', $ids);
                $this->set('KeywordList', $KeywordList);

                $this->viewPath = 'Elements' . DS . 'admin/keywords/';
                $this->render('ajaxkeywordlist');
            } else if ($search == 'Job') {
                $condition = array('Keyword.approval_status' => '1', 'Keyword.status' => '1');
                $keyword = trim($this->data['keyword']);
                $suggesstion = trim($this->data['suggesstion']);

                $ids = trim($this->data['ids']);
                if ($search == 'Job') {
                    $condition[] = " (Keyword.type = 'Job')  ";
                }
                $condition[] = " (Keyword.name like '%" . addslashes($keyword) . "%')  ";

                $KeywordList = $this->Keyword->find('list', array('conditions' => $condition, 'fields' => array('Keyword.id', 'Keyword.name'), 'order' => 'Keyword.name asc'));

                $this->set('keyword', $keyword);
                $this->set('suggesstion', $suggesstion);
                $this->set('search', $search);
                $this->set('ids', $ids);
                $this->set('KeywordList', $KeywordList);

                $this->viewPath = 'Elements' . DS . 'admin/keywords/';
                $this->render('ajaxkeywordlist');
            } else {
                $condition = array('Keyword.approval_status' => '1', 'Keyword.status' => '1');
                $keyword = trim($this->data['keyword']);
                $suggesstion = trim($this->data['suggesstion']);

                $ids = trim($this->data['ids']);
                if ($search == 'Search') {
                    $condition[] = " (Keyword.type = 'Search')  ";
                }
                $condition[] = " (Keyword.name like '%" . addslashes($keyword) . "%')  ";

                $KeywordList = $this->Keyword->find('list', array('conditions' => $condition, 'fields' => array('Keyword.id', 'Keyword.name'), 'order' => 'Keyword.name asc'));

                $this->set('keyword', $keyword);
                $this->set('suggesstion', $suggesstion);
                $this->set('search', $search);
                $this->set('ids', $ids);
                $this->set('KeywordList', $KeywordList);

                $this->viewPath = 'Elements' . DS . 'admin/keywords/';
                $this->render('ajaxkeywordlist');
            }
        } else {
            exit;
        }
    }

    public function ajaxspecialtylist() {
        $this->layout = '';
        $keyword = '';
        $suggesstion = '';
        $search = '';
        $ids = '';

        if ($this->request->is('ajax')) {
            $search = trim($this->data['search']);
            $graduation = trim($this->data['graduation']);

            if ($search == 'Specialty' && $graduation) {
                $condition = array('Course.status' => '1');

                $condition[] = " (Course.type = 'Post Graduate')  ";
                $condition[] = " (Course.name like '%" . addslashes($graduation) . "%')  ";
                $course_id = $this->Course->field('id', $condition);
                $condition = array('Specialization.status' => '1', 'Specialization.course_id' => $course_id);
                $keyword = trim($this->data['keyword']);
                $suggesstion = trim($this->data['suggesstion']);

                $ids = trim($this->data['ids']);
                $condition[] = " (Specialization.type = 'Basic')  ";
                $condition[] = " (Specialization.name like '%" . addslashes($keyword) . "%')  ";

                $KeywordList = $this->Specialization->find('list', array('conditions' => $condition, 'fields' => array('Specialization.id', 'Specialization.name'), 'order' => 'Specialization.name asc'));

                $this->set('keyword', $keyword);
                $this->set('suggesstion', $suggesstion);
                $this->set('search', $search);
                $this->set('ids', $ids);
                $this->set('KeywordList', $KeywordList);

                $this->viewPath = 'Elements' . DS . 'admin/keywords/';
                $this->render('ajaxspecialtylist');
            } else {
                exit;
            }
        } else {
            exit;
        }
    }

}

?>
