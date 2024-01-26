<?php

class PromoCodesController extends AppController {

    public $name = 'PromoCodes';
    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'Category', 'Product', 'PromoCode');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Category.name' => 'asc'));
    public $components = array('RequestHandler', 'Email', 'Upload', 'PImageTest', 'PImage', 'Captcha');
    public $layout = 'admin';

    function beforeFilter() {
        $loggedAdminId = $this->Session->read("adminid");
        if (isset($this->params['admin']) && $this->params['admin'] && !$loggedAdminId) {
            $this->redirect("/admin/admins/login");
        }
    }

    function random_num() {
        $len = 8;
        $ch = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $l = strlen($ch) - 1;
        $str = "";
        for ($i = 0; $i < $len; $i++) {
            $x = rand(0, $l);
            $str .= $ch[$x];
        }
        return $str;
    }

    public function admin_index() {
        $this->layout = "admin";
        $this->set('promo_list', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "List Promo Codes");

        $condition = array();
        $separator = array();
        $urlSeparator = array();
        $name = '';
        $searchByDateFrom = '';
        $searchByDateTo = '';

        if (!empty($this->data)) {
            if (isset($this->data['PromoCode']['name']) && $this->data['PromoCode']['name'] != '') {
                $name = trim($this->data['PromoCode']['name']);
            }
            if (isset($this->data['PromoCode']['searchByDateFrom']) && $this->data['PromoCode']['searchByDateFrom'] != '') {
                $searchByDateFrom = trim($this->data['PromoCode']['searchByDateFrom']);
            }

            if (isset($this->data['PromoCode']['searchByDateTo']) && $this->data['PromoCode']['searchByDateTo'] != '') {
                $searchByDateTo = trim($this->data['PromoCode']['searchByDateTo']);
            }

            if (isset($this->data['PromoCode']['action'])) {
                $idList = $this->data['PromoCode']['idList'];
                if ($idList) {
                    if ($this->data['PromoCode']['action'] == "activate") {
                        $cnd = array("PromoCode.id IN ($idList) ");
                        $this->PromoCode->updateAll(array('PromoCode.status' => "'1'"), $cnd);
                    } elseif ($this->data['PromoCode']['action'] == "deactivate") {
                        $cnd = array("PromoCode.id IN ($idList) ");
                        $this->PromoCode->updateAll(array('PromoCode.status' => "'0'"), $cnd);
                    } elseif ($this->data['PromoCode']['action'] == "delete") {
                        $cnd = array("PromoCode.id IN ($idList) ");

                        $this->PromoCode->deleteAll($cnd);
                    }
                }
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['name']) && $this->params['named']['name'] != '') {
                $name = urldecode(trim($this->params['named']['name']));
            }
            if (isset($this->params['named']['searchByDateFrom']) && $this->params['named']['searchByDateFrom'] != '') {
                $searchByDateFrom = urldecode(trim($this->params['named']['searchByDateFrom']));
            }
            if (isset($this->params['named']['searchByDateTo']) && $this->params['named']['searchByDateTo'] != '') {
                $searchByDateTo = urldecode(trim($this->params['named']['searchByDateTo']));
            }
        }
        if (isset($name) && $name != '') {
            $separator[] = 'name:' . urlencode($name);
            $condition[] = " (PromoCode.code like '%" . addslashes($name) . "%')";
        }

        if (isset($searchByDateFrom) && $searchByDateFrom != '') {
            $separator[] = 'searchByDateFrom:' . urlencode($searchByDateFrom);
            $searchByDateFrom = str_replace('_', '\_', $searchByDateFrom);
            $searchByDate_con1 = date('Y-m-d', strtotime($searchByDateFrom));
            $condition[] = " (Date(PromoCode.expiry_date)>='$searchByDate_con1' ) ";
            $searchByDateFrom = str_replace('\_', '_', $searchByDateFrom);
        }

        if (isset($searchByDateTo) && $searchByDateTo != '') {

            $separator[] = 'searchByDateTo:' . urlencode($searchByDateTo);
            $searchByDateTo = str_replace('_', '\_', $searchByDateTo);
            $searchByDate_con2 = date('Y-m-d', strtotime($searchByDateTo));
            $condition[] = " (Date(PromoCode.expiry_date)<='$searchByDate_con2' ) ";
            $searchByDateTo = str_replace('\_', '_', $searchByDateTo);
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
        $this->set('searchByDateFrom', $searchByDateFrom);
        $this->set('searchByDateTo', $searchByDateTo);
        $this->set('name', $name);
        $this->set('searchKey', $name);


        $this->paginate['PromoCode'] = array(
            'conditions' => $condition,
            'order' => array('PromoCode.id' => 'DESC'),
            'limit' => '20'
        );

        $this->set('code_list', $this->paginate('PromoCode', $condition));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/promoCodes/';
            $this->render('index');
        }
    }

    public function admin_addpromocode() {
        $this->layout = "admin";
        $this->set('add_promo', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Promo Code");

        if ($this->data) {

            if (empty($this->data["PromoCode"]["discount_type"])) {
                $msgString .="- Discount Type is required field.<br>";
            } else {
                if (empty($this->data["PromoCode"]["discount"])) {
                    $msgString .="- Discount is required field.<br>";
                } elseif ($this->data["PromoCode"]["discount_type"] == 'Percent' && $this->data["PromoCode"]["discount"] > 100) {
                    $msgString .="- Please enter a valid discount percent.<br>";
                } elseif ($this->data["PromoCode"]["discount"] < 1) {
                    $msgString .="- Please enter a valid discount amount.<br>";
                }
            }
            if (empty($this->data["PromoCode"]["code"])) {
                $msgString .="- Promo Code is required field.<br>";
            } elseif ($this->PromoCode->isUniquePrmoCode($this->data["PromoCode"]["code"]) == false) {
                $msgString .="- Promo Code already exists.<br>";
            }



            if (empty($this->data["PromoCode"]["details"])) {
                $msgString .="- Description is required field.<br>";
            }

            if (empty($this->data["PromoCode"]["expiry_date"])) {
                $msgString .="- Valid Till is required field.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {

                $this->request->data['PromoCode']['code'] = strtoupper(trim($this->data["PromoCode"]["code"]));
                $this->request->data['PromoCode']['slug'] = strtoupper($this->data["PromoCode"]["code"]);
                $this->request->data['PromoCode']['status'] = 1;


                if ($this->PromoCode->save($this->data)) {
                    $this->Session->setFlash('Promo Code Added Successfully.', 'success_msg');
                    $this->redirect('/admin/promoCodes/index');
                }
            }
        }
    }

    public function admin_editpromocode($slug = null) {
        $this->layout = "admin";
        $this->set('promo_list', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Promo Code");

        if ($this->data) {
            if (empty($this->data["PromoCode"]["discount_type"])) {
                $msgString .="- Discount Type is required field.<br>";
            } else {
                if ($this->data["PromoCode"]["discount_type"] == 'Percent' && $this->data["PromoCode"]["discount"] > 100) {
                    $msgString .="- Please enter a valid discount percent.<br>";
                } elseif ($this->data["PromoCode"]["discount"] < 1) {
                    $msgString .="- Please enter a valid discount amount.<br>";
                }
            }
            if (empty($this->data["PromoCode"]["code"])) {
                $msgString .="- Promo Code is required field.<br>";
            }



            if (empty($this->data["PromoCode"]["details"])) {
                $msgString .="- User Type is required field.<br>";
            }

            if (empty($this->data["PromoCode"]["expiry_date"])) {
                $msgString .="- User Type is required field.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data['PromoCode']['code'] = strtoupper(trim($this->data["PromoCode"]["code"]));
                if ($this->PromoCode->save($this->data)) {
                    $this->Session->setFlash('Promo Code updated successfully.', 'success_msg');
                    $this->redirect('/admin/promoCodes/index');
                }
            }
        } else {
            $id = $this->PromoCode->field('id', array('PromoCode.slug' => $slug));
            $this->PromoCode->id = $id;
            $this->data = $this->PromoCode->read();
        }
    }

    public function admin_activatePromoCode($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->PromoCode->field('id', array('PromoCode.slug' => $slug));
            $cnd = array("PromoCode.id = $id");
            $this->PromoCode->updateAll(array('PromoCode.status' => "'1'"), $cnd);
            $this->set('action', '/admin/promoCodes/deactivatePromoCode/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivatePromoCode($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->PromoCode->field('id', array('PromoCode.slug' => $slug));
            $cnd = array("PromoCode.id = $id");
            $this->PromoCode->updateAll(array('PromoCode.status' => "'0'"), $cnd);
            $this->set('action', '/admin/promoCodes/activatePromoCode/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);


            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deletePromoCode($slug = null) {
        $this->set('list_codes', 'active');
        $this->set('default', '1');
        if ($slug != '') {
            $id = $this->PromoCode->field('id', array('PromoCode.slug' => $slug));
            if ($this->PromoCode->delete($id)) {
                $this->Session->setFlash('Promo Code details deleted successfully.', 'success_msg');
            }
            $this->redirect('/admin/promoCodes/index');
        }
    }

    public function admin_reset_search() {
        $this->layout = '';
        $this->autoRender = false;
        if (isset($_SESSION['searchByDateFrom']) && $_SESSION['searchByDateFrom'] != '') {
            unset($_SESSION['searchByDateFrom']);
        }
        if (isset($_SESSION['searchByDateTo']) && $_SESSION['searchByDateTo'] != '') {
            unset($_SESSION['searchByDateTo']);
        }
        if (isset($_SESSION['name']) && $_SESSION['name'] != '') {
            unset($_SESSION['name']);
        }

        $this->redirect($this->referer());
    }

}

?>
