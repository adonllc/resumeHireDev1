<?php

class AlertsController extends AppController {

    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'Location', 'Skill', 'Designation', 'Alert', 'AlertLocation');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('alerts.id' => 'desc'));
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

    public function index() {

        $this->layout = "client";
        $this->set('alertManage', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Alert list', true));
        $separator = '';
        $this->userLoginCheck();
        $this->candidateAccess();

        $user_id = $this->Session->read("user_id");
        $userdetail = $this->User->findById($user_id);
        $this->set('userdetail', $userdetail);



        $this->set('separator', $separator);
        $condition = array('Alert.user_id' => $this->Session->read("user_id"));
        $order = 'Alert.id DESC';

        $this->paginate = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('alerts', $this->paginate('Alert'));
        //pr($this->paginate('Alert'));

        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'alerts';
            $this->render('index');
        }
    }

    public function add() {

        $this->layout = "client";
        $this->set('alertManage', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Alert Add', true));

        $this->userLoginCheck();
        $this->candidateAccess();
        $msgString = '';

        $user_id = $this->Session->read("user_id");
        $userdetail = $this->User->findById($user_id);
        $this->set('userdetail', $userdetail);


        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        // get designations from skill table
        $designationlList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Designation', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('designationlList', $designationlList);

//        echo"<pre>";
//        pr($this->data);
//        exit;

        if ($this->data) {
            if (empty($this->data["Alert"]["location"])) {
                $msgString .= __d('controller', 'Please select locations first.', true)."<br>";
            } else {
                $locations = $this->data["Alert"]["location"];
            }

            if (empty($this->data["Alert"]["designation"])) {
                $msgString .= __d('controller', 'Please select designation.', true)."<br>";
            } else {
                $designations = $this->data["Alert"]["designation"];
            }

            if (isset($msgString) && $msgString != '') {
                // $this->Session->setFlash($msgString, 'error_msg');
                $this->Session->write('error_msg', $msgString);
            } else {

                $this->request->data['Alert']['designation'] = $designations;
                $this->request->data['Alert']['user_id'] = $user_id;
                $this->request->data['Alert']['status'] = 1;
                $this->request->data['Alert']['slug'] = 'ALERT' . time() . rand(10000, 999999);

                if ($this->Alert->save($this->data)) {

//                    $alertId = $this->Alert->id;
//
//                    foreach ($locations as $location) {
//                        $this->AlertLocation->create();
//                        $this->request->data['AlertLocation']['location'] = $location;
//                        $this->request->data['AlertLocation']['alert_id'] = $alertId;
//                        $this->AlertLocation->save($this->data);
//                    }

                    $this->Session->write('success_msg', __d('controller', 'Alert saved. You will receive an alert when jobs are created and match your criteria.', true));
                    $this->redirect('/alerts/index');
                }
            }
        }
    }

    public function edit($slug = null) {

        $this->layout = "client";
        $this->set('alertManage', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Edit Alert', true));

        $this->userLoginCheck();
        $this->candidateAccess();
        $msgString = '';

        $user_id = $this->Session->read("user_id");
        $userDetail = $this->User->findById($user_id);

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        // get designations from skill table
        $designationlList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Designation', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('designationlList', $designationlList);

        $alert_detail = $this->Alert->find('first', array('conditions' => array('Alert.slug' => $slug)));
        $alert_id = $alert_detail['Alert']['id'];
        //pr($alert_detail);
        $this->set('alert_detail', $alert_detail);
        if($alert_detail){
            
        }else{
             $this->redirect('/homes/error');
        }

        if ($this->data) {

            if (empty($this->data["Alert"]["location"])) {
                $msgString .= __d('controller', 'Please select locations first.', true)."<br>";
            } else {
                $locations = $this->data["Alert"]["location"];
            }

            if (empty($this->data["Alert"]["designation"])) {
                $msgString .= __d('controller', 'Please select designation.', true)."<br>";
            } else {
                $designations = $this->data["Alert"]["designation"];
            }


            if (isset($msgString) && $msgString != '') {
                // $this->Session->setFlash($msgString, 'error_msg');
                $this->Session->write('error_msg', $msgString);
            } else {

                $this->request->data['Alert']['designation'] = $designations;
                //$this->request->data['Alert']['user_id'] = $user_id;
                //$this->request->data['Alert']['status'] = 1;
                //$this->request->data['Alert']['slug'] = 'ALERT' . time() . rand(10000, 999999);

//                if ($locations) {
//                    foreach ($locations as $location) {
//                        if ($this->AlertLocation->isAlertLocationUnique($alert_id, $location) != false) {
//                            $this->AlertLocation->create();
//                            $this->request->data['AlertLocation']['location'] = $location;
//                            $this->request->data['AlertLocation']['alert_id'] = $alert_id;
//                            $this->AlertLocation->save($this->data);
//                        }
//                    }
//                    $locationList = implode(', ', $locations);
//                    if ($locationList) {
//                        $cnd = array(
//                            'AlertLocation.alert_id' => $alert_id,
//                            'AlertLocation.location NOT IN (' . $locationList . ')');
//                        $this->AlertLocation->deleteall($cnd);
//                    }
//                }


                $this->request->data['Alert']['id'] = $alert_id;
                if ($this->Alert->save($this->data)) {
                    $this->Session->write('success_msg', __d('controller', 'Alert saved. You will receive an alert when jobs are created and match your criteria.', true));
                    $this->redirect('/alerts/index');
                }
            }
        } elseif ($slug != '') {

            $this->Alert->id = $alert_id;
            $this->data = $this->Alert->read();
//            $this->request->data['Alert']['location'] = $this->AlertLocation->find('list', array(
//                'conditions' => array('AlertLocation.alert_id' => $this->data['Alert']['id']),
//                'fields' => 'AlertLocation.location'
//            ));
        }
    }

    public function delete($slug = NULL) {
        if ($slug != '') {
            $this->userLoginCheck();
            $this->candidateAccess();
            $this->layout = "";
            $user_id = $this->Session->read('user_id');

            $AlertDetail = $this->Alert->findBySlug($slug);

            if ($AlertDetail['Alert']['user_id'] == $user_id) {
                $this->Alert->delete($AlertDetail['Alert']['id']);

                $cnd = array('AlertLocation.alert_id' => $AlertDetail['Alert']['id']);
                $this->AlertLocation->deleteall($cnd);

                $this->Session->write('success_msg', __d('controller', 'Alert details deleted successfully.', true));
            } else {
                // $this->Session->setFlash('You can\'t delete this Alert', 'error_msg');
                $this->Session->write('error_msg', 'You can\'t delete this Alert');
            }
            $this->redirect('/alerts/index');
        }
    }

}

?>
