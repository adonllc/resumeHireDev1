<?php

class SwearsController extends AppController {

    public $c_word = 'Swears';
    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'Swear', 'Swear');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Swear.c_word' => 'asc'));
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
        $this->set('swearlist', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Swear Words list");

        $condition = array();
        $separator = array();
        $urlSeparator = array();
        $c_word = '';

        if (!empty($this->data)) {
            if (isset($this->data['Swear']['s_word']) && $this->data['Swear']['s_word'] != '') {
                $c_word = trim($this->data['Swear']['s_word']);
            }

            if (isset($this->data['Swear']['action'])) {
                $idList = $this->data['Swear']['idList'];
                if ($idList) {
                    if ($this->data['Swear']['action'] == "delete") {
                        $cnd = array("Swear.id IN ($idList) ");
                        //   $cnd1 = array("Swear.parent_id IN ($idList) ");

                        $this->Swear->deleteAll($cnd);
                        //   $this->Swear->deleteAll($cnd1);
                    }
                }
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['s_word']) && $this->params['named']['s_word'] != '') {
                $c_word = urldecode(trim($this->params['named']['s_word']));
            }
        }

        if (isset($c_word) && $c_word != '') {
            $separator[] = 's_word:' . urlencode($c_word);
            $condition[] = " (Swear.s_word like '%" . addslashes($c_word) . "%')  ";
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

        $this->paginate['Swear'] = array(
            'conditions' => $condition,
            'order' => array('Swear.id' => 'DESC'),
            'limit' => '30'
        );

        $this->set('categories', $this->paginate('Swear', $condition));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/swears/';
            $this->render('index');
        }
    }

    public function admin_addswears() {


        $this->set('addswear', 'active');
        $this->set('swearlist', '');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Swear Words");

        if ($this->data) {

            if (empty($this->data["Swear"]["s_word"])) {
                $msgString .="- Swear Name is required field.<br>";
            } else {

                $sw = explode(",", $this->data['Swear']['s_word']);



                foreach ($sw as $k => $v) {



                    if (isset($msgString) && $msgString != '') {
                        $this->Session->setFlash($msgString, 'error_msg');
                    } else {
                        $fin = $this->Swear->find('all');
                        if (isset($fin) && count($fin) > 0 && is_array($fin)) {
                            if ($this->Swear->in_array_r($v, $fin)) {
                                $this->Session->setFlash('Swear Words Already Exists', 'error_msg');
                                $this->redirect('/admin/swears/index');
                            } else {

                                if (!empty($v)) {


                                    $s['Swear']['slug'] = $this->stringToSlugUnique($v, 'Swear', 'slug');

                                    $s['Swear']['s_word'] = $v;
                                    $this->Swear->saveall($s);
                                    //  unset($s);
                                }
                            }
                        } else {
                            //die("dsa");
                            if (!empty($v)) {

                                $s['Swear']['slug'] = $this->stringToSlugUnique($v, 'Swear', 'slug');
                                $s['Swear']['s_word'] = $v;
                                $this->Swear->saveAll($s);
                                unset($s);
                            }
                        }
                    }
                }

                $this->Session->setFlash('Swear Words Added Successfully', 'success_msg');
                $this->redirect('/admin/swears/index');
            }
        }
    }

    public function admin_editswear($slug = null) {

        $this->set('swearlist', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Swear");


        if ($this->data) {

            $find = $this->Swear->field('id', array('Swear.s_word' => $this->data["Swear"]["s_word"], 'Swear.slug <> "' . $slug . '"'));

            // pr($find);exit;
            if (isset($find) && $find > 0 && $find != $slug) {

                $msgString .="- Swear Name already exists.<br>";
            }

            if (empty($this->data["Swear"]["s_word"])) {
                $msgString .="- Swear Name is required field.<br>";
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
                // $this->redirect('/admin/editswear/'.$slug);
            } else {
                if ($this->Swear->save($this->data)) {
                    $this->Session->setFlash('Swear updated successfully', 'success_msg');
                    $this->redirect('/admin/swears/index');
                }
            }
        } else {


            $id = $this->Swear->field('id', array('Swear.slug' => $slug));
            $this->Swear->id = $id;
            $this->data = $this->Swear->read();
        }
    }

    public function admin_deleteSwear($id = null) {
        $id = $this->Swear->field('id', array('Swear.slug' => $id));
        if ($id) {
            //$this->Swear->deleteAll(array('Swear.parent_id'=>$id));
            $this->Swear->delete($id);
            $this->Session->setFlash('Swear deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'swears', 'action' => 'index'));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'swears', 'action' => 'index'));
        }
    }

}

?>
