<?php

class JobAppliesController extends AppController {

    public $name = 'JobApplies';
    public $uses = array('Admin', 'User', 'Job', 'JobApply');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('City.name' => 'asc'));
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

    public function changeCandidateStatus($id = NULL, $status = null) {
        if ($id != '' && $status != '') {
            $this->layout = "";
            $cnd = array("JobApply.id = $id");
            $this->JobApply->updateAll(array('JobApply.apply_status' => "'$status'"), $cnd);
            $this->Session->setFlash(__d('controller', 'Status changed successfully.', true), 'success_msg');
        }
        $this->redirect($this->referer());
    }

    public function updateRating($id = NULL, $rating = null) {
        if ($id != '' && $rating != '') {
            $this->layout = "";
            $cnd = array("JobApply.id = $id");
            $this->JobApply->updateAll(array('JobApply.rating' => "'$rating'"), $cnd);
        }
        exit;
    }

}

?>
