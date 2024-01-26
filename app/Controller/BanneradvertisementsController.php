<?php

class BanneradvertisementsController extends AppController {

    var $name = 'Banneradvertisements';
    var $uses = array("Banneradvertisement", "User", "Admin");
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number', 'Time');
    var $paginate = array('limit' => '20', 'page' => '1', 'order' => array('Banneradvertisement.id' => 'desc'));
    public $components = array('RequestHandler', 'Email', 'Upload', 'PImageTest', 'PImage', 'Captcha');
    var $layout = 'admin';

    function beforeFilter() {
        $loggedAdminId = $this->Session->read("adminid");
        if (isset($this->params['admin']) && $this->params['admin'] && !$loggedAdminId) {
            $this->redirect("/admin/admins/login");
        }
    }

    function admin_index() {
        $this->layout = "admin";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Banner Advertisement List');

        $this->set('banner_list', 'active');
        $condition = array();
        $separator = array();
        $urlSeparator = array();
        $bannerName = '';
        $searchByDateFrom = '';
        $searchByDateTo = '';

        if (!empty($this->data)) {

            if (isset($this->data['Banneradvertisement']['bannerName']) && $this->data['Banneradvertisement']['bannerName'] != '') {
                $bannerName = trim($this->data['Banneradvertisement']['bannerName']);
            }
            if (isset($this->data['Banneradvertisement']['searchByDateFrom']) && $this->data['Banneradvertisement']['searchByDateFrom'] != '') {
                $searchByDateFrom = trim($this->data['Banneradvertisement']['searchByDateFrom']);
            }

            if (isset($this->data['Banneradvertisement']['searchByDateTo']) && $this->data['Banneradvertisement']['searchByDateTo'] != '') {
                $searchByDateTo = trim($this->data['Banneradvertisement']['searchByDateTo']);
            }


            if (isset($this->data['Banneradvertisement']['action'])) {
                $idList = $this->data['Banneradvertisement']['idList'];

                if ($idList) {
                    if ($this->data['Banneradvertisement']['action'] == "activate") {
                        $cnd = array("Banneradvertisement.id IN ($idList) ");
                        $this->Banneradvertisement->updateAll(array('Banneradvertisement.status' => "'1'"), $cnd);
                    } elseif ($this->data['Banneradvertisement']['action'] == "deactivate") {
                        $cnd = array("Banneradvertisement.id IN ($idList) ");
                        $this->Banneradvertisement->updateAll(array('Banneradvertisement.status' => "'0'"), $cnd);
                    } elseif ($this->data['Banneradvertisement']['action'] == "delete") {
                        $cnd = array("Banneradvertisement.id IN ($idList) ");
                        $this->Banneradvertisement->deleteAll($cnd);
                    }
                }
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['bannerName']) && $this->params['named']['bannerName'] != '') {
                $bannerName = urldecode(trim($this->params['named']['bannerName']));
            }
            if (isset($this->params['named']['searchByDateFrom']) && $this->params['named']['searchByDateFrom'] != '') {
                $searchByDateFrom = urldecode(trim($this->params['named']['searchByDateFrom']));
            }
            if (isset($this->params['named']['searchByDateTo']) && $this->params['named']['searchByDateTo'] != '') {
                $searchByDateTo = urldecode(trim($this->params['named']['searchByDateTo']));
            }
        }

        if (isset($bannerName) && $bannerName != '') {
            $separator[] = 'bannerName:' . urlencode($bannerName);
            $bannerName = str_replace('_', '\_', $bannerName);
            $condition[] = " (`Banneradvertisement`.`title` like '%" . addslashes($bannerName) . "%') ";
            $bannerName = str_replace('\_', '_', $bannerName);
            $this->set('searchKey', $bannerName);
        }

        $separator = implode("/", $separator);

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
        $order = 'Banneradvertisement.status Desc';
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->set('bannerName', $bannerName);
        $this->paginate['Banneradvertisement'] = array('conditions' => $condition, 'limit' => '50', 'page' => '1', 'order' => $order);
        $this->set('advertisements', $this->paginate('Banneradvertisement'));


        if ($this->RequestHandler->isAjax()) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/banneradvertisements/';
            $this->render('index');
        }
    }

    function admin_deactivateBanneradvertisement($slug = NULL) {

        $this->layout = "";
        if ($slug != '') {
            $id = $this->Banneradvertisement->field('id', array('Banneradvertisement.slug' => $slug));
            $cnd = array("Banneradvertisement.id = $id");
            $this->Banneradvertisement->updateAll(array('Banneradvertisement.status' => "'0'"), $cnd);
            $this->set('action', '/admin/banneradvertisements/activateBanneradvertisement/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    function admin_activateBanneradvertisement($slug = NULL) {


        $this->layout = "";
        if ($slug != '') {
            $id = $this->Banneradvertisement->field('id', array('Banneradvertisement.slug' => $slug));
            $cnd = array("Banneradvertisement.id = $id");
            $this->Banneradvertisement->updateAll(array('Banneradvertisement.status' => "'1'"), $cnd);
            $this->set('action', '/admin/banneradvertisements/deactivateBanneradvertisement/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    function admin_deleteBanneradvertisement($slug = NULL) {
        $id = $this->Banneradvertisement->field('id', array('Banneradvertisement.slug' => $slug));
        if ($id > 0) {
            $imageName = $this->Banneradvertisement->find('first', array('conditions' => array('Banneradvertisement.id' => $id), 'fields' => array('Banneradvertisement.image')));
            $image = $imageName['Banneradvertisement']['image'];
            if (isset($image) && $image != '') {
                @unlink(UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $image);
                @unlink(UPLOAD_THUMB_BANNER_AD_IMAGE_PATH . $image);
            }
            $this->Banneradvertisement->delete($id);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->Session->setFlash('Advertisement details deleted successfully.', 'success_msg');
            $this->redirect('/admin/banneradvertisements/index');
        }
    }

    function admin_move_up_down($imageID = NULL, $move = NULL) {


        if (!$imageID) {
            echo 'Banner Advertisement not found';
            exit;
        }

        $sql = "UPDATE banneradvertisements SET image_order = image_order + $move WHERE id = $imageID";

        mysql_query($sql) or die(mysql_error());

        $this->admin_renumber_order();
    }

    function admin_renumber_order() {

        $table = "banneradvertisements";
        $idfield = 'id';
        $orderfield = 'image_order';


        $sql = "SELECT * FROM $table";

        $sql .= " ORDER BY $orderfield ASC";

        $result = mysql_query($sql) or die(mysql_error());

        $i = 10;
        $inc = 10;

        while ($row = mysql_fetch_assoc($result)) {
            $sql = "UPDATE $table
                                SET $orderfield = $i
                                WHERE $idfield = " . $row[$idfield];

            mysql_query($sql) or die(mysql_error());

            $i += 10;
        }

        $this->redirect("/admin/banneradvertisements/index/");
    }

    function admin_addBanneradvertisement() {

        $this->layout = "admin";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Add Banner Advertisement');

        $msgString = '';
        $this->set('add_banner', 'active');
        $this->set("type", "1");

        if ($this->data) {
            if (empty($this->data["Banneradvertisement"]["advertisement_place"])) {
                $msgString .= "Advertisement Place is required field.<br>";
            } else {
                $advertisement_place = $this->data["Banneradvertisement"]["advertisement_place"];
            }

            if (empty($this->data["Banneradvertisement"]["title"])) {
                $msgString .= "Title is required field.<br>";
            } else {
                if ($this->Banneradvertisement->isUniqueBanner($this->data["Banneradvertisement"]["title"]) == false) {
                    $msgString .="- Banner title already exists.<br>";
                }
            }


            if ($this->data["Banneradvertisement"]["type"] == 1) {
                if (empty($this->data["Banneradvertisement"]["url"])) {
                    $msgString .= "URL is required field.<br>";
                } else {
                    if (preg_match("/^(http(s?):\/\/)?(www\.)+[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/", $this->data["Banneradvertisement"]["url"]) == false) {
                        $msgString .= "Please enter valid url.<br>";
                    }
                }

                if (empty($this->data['Banneradvertisement']['image']['name'])) {
                    $msgString .= "Advertisement Image is required field.<br>";
                }
                $this->set("type", "1");
            } elseif ($this->data["Banneradvertisement"]["type"] == 3) {
                if (empty($this->data["Banneradvertisement"]["url"])) {
                    $msgString .= "URL is required field.<br>";
                } else {
                    if (preg_match("/^(http(s?):\/\/)?(www\.)+[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/", $this->data["Banneradvertisement"]["url"]) == false) {
                        $msgString .= "Please enter valid url.<br>";
                    }
                }
                if (empty($this->data['Banneradvertisement']['text'])) {
                    $msgString .= "Banner Advertisement Text is required field.<br>";
                }
                $this->set("type", "3");
            } else {
                if (empty($this->data["Banneradvertisement"]["code"])) {
                    $msgString .= "Advertisement Html Code is required field.<br>";
                }
                $this->set("type", "2");
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if (!empty($this->data['Banneradvertisement']['image']['name'])) {
                    $this->request->data['Banneradvertisement']['image']['name'] = str_replace(' ', '-', $this->data['Banneradvertisement']['image']['name']);
                    $imageArray = $this->data['Banneradvertisement']['image'];
                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_BANNER_AD_IMAGE_PATH);

                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= "- Image file not valid.<br>";
                    } else {
                        list($width, $height, $type, $attr) = getimagesize(UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0]);
                        if ($this->data["Banneradvertisement"]["advertisement_place"] == 'Top' && ($width != 1294 || $height != 292)) {
                            @unlink(UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0]);
                            $msgString .="- Images size must be 1147 X 143 pixels for Top Area.<br>";
                        } elseif ($this->data["Banneradvertisement"]["advertisement_place"] == 'Bottom' && ($width != 1294 || $height != 292)) {
                            @unlink(UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0]);
                            $msgString .="- Images size must be 1149 X 94 pixels for Bottom Area.<br>";
                        } else {
                            copy(UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0]);
                            $this->PImageTest->resize(UPLOAD_THUMB_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_BANNER_AD_IMAGE_WIDTH, UPLOAD_THUMB_BANNER_AD_IMAGE_HEIGHT, 100);
                            // $this->PImageTest->resize(UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_BANNER_AD_IMAGE_WIDTH, UPLOAD_FULL_BANNER_AD_IMAGE_HEIGHT, 100);
                            $this->request->data['Banneradvertisement']['image'] = $returnedUploadImageArray[0];
                             chmod(UPLOAD_FULL_BANNER_AD_IMAGE_PATH .$returnedUploadImageArray[0], 0755);
                             chmod(UPLOAD_THUMB_BANNER_AD_IMAGE_PATH .$returnedUploadImageArray[0], 0755);
                        }
                    }
                } else {
                    $this->request->data['Banneradvertisement']['image'] = '';
                }

                if (isset($msgString) && $msgString != '') {
                    $this->Session->setFlash($msgString, 'error_msg');
                } else {
                    $this->request->data['Banneradvertisement']['status'] = 1;
                    $this->request->data['Banneradvertisement']['slug'] = $this->stringToSlugUnique($this->data['Banneradvertisement']['title'], 'Banneradvertisement', 'slug');
                    ;
                    $this->Banneradvertisement->save($this->data);

                    $this->Session->setFlash('Advertisement added successfully.', 'success_msg');
                    $this->redirect("/admin/banneradvertisements/index/");
                }
            }
        }
    }

    function admin_editBanneradvertisement($slug = null) {

        $this->layout = "admin";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Edit Banner Advertisement');

        $this->set("expand_menu_links", "Edit Banneradvertisement");
        $msgString = '';
        $this->set('banner_list', 'active');
        $this->set('default', '3');
        $title = $this->Banneradvertisement->field('title', array('slug' => $slug));
        if ($this->data) {
            //pr($this->data);exit;
            if (empty($this->data["Banneradvertisement"]["advertisement_place"])) {
                $msgString .= "Advertisement Place is required field.<br>";
            } else {
                $advertisement_place = $this->data["Banneradvertisement"]["advertisement_place"];
            }

            if (empty($this->data["Banneradvertisement"]["title"])) {
                $msgString .= "Title is required field.<br>";
            } else if ($this->data["Banneradvertisement"]["title"] != $title) {
                if ($this->Banneradvertisement->isUniqueBanner($this->data["Banneradvertisement"]["title"]) == false) {
                    $msgString .="- Banner title already exists.<br>";
                }
            }

            if ($this->data["Banneradvertisement"]["type"] == 1) {
                if (empty($this->data["Banneradvertisement"]["url"])) {
                    $msgString .= "URL is required field.<br>";
                } else {
                    if (preg_match("/^(http(s?):\/\/)?(www\.)+[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/", $this->data["Banneradvertisement"]["url"]) == false) {
                        $msgString .= "Please enter valid url.<br>";
                    }
                }

                if (empty($this->data['Banneradvertisement']['old_image']) && empty($this->data['Banneradvertisement']['image']['name'])) {
                    $msgString .= "Advertisement Image is required field.<br>";
                }
                $this->set("type", "1");
            } elseif ($this->data["Banneradvertisement"]["type"] == 3) {
                if (empty($this->data["Banneradvertisement"]["url"])) {
                    $msgString .= "URL is required field.<br>";
                } else {
                    if (preg_match("/^(http(s?):\/\/)?(www\.)+[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/", $this->data["Banneradvertisement"]["url"]) == false) {
                        $msgString .= "Please enter valid url.<br>";
                    }
                }
                if (empty($this->data['Banneradvertisement']['text'])) {
                    $msgString .= "Advertisement Text is required field.<br>";
                }
                $this->set("type", "3");
            } else {
                if (empty($this->data["Banneradvertisement"]["code"])) {
                    $msgString .= "Advertisement Html Code is required field.<br>";
                }
                $this->set("type", "2");
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
                $this->request->data['Banneradvertisement']['image'] = $this->data['Banneradvertisement']['old_image'];
            } else {
                if (!empty($this->data['Banneradvertisement']['image']['name'])) {
                    $this->request->data['Banneradvertisement']['image']['name'] = str_replace(' ', '-', $this->data['Banneradvertisement']['image']['name']);
                    $imageArray = $this->data['Banneradvertisement']['image'];
                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_BANNER_AD_IMAGE_PATH);

                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= "- Image file not valid.<br>";
                    } else {
                        list($width, $height, $type, $attr) = getimagesize(UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0]);
                        if ($this->data["Banneradvertisement"]["advertisement_place"] == 'Top' && ($width != 1294 || $height != 292)) {
                            @unlink(UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0]);
                            $msgString .="- Images size must be 1147 X 143 pixels for Top Area.<br>";
                        } elseif ($this->data["Banneradvertisement"]["advertisement_place"] == 'Bottom' && ($width != 1294 || $height != 292)) {
                            @unlink(UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0]);
                            $msgString .="- Images size must be 1149 X 94 pixels for Bottom Area.<br>";
                        } else {
                            copy(UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0]);
                            $this->PImageTest->resize(UPLOAD_THUMB_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_BANNER_AD_IMAGE_WIDTH, UPLOAD_THUMB_BANNER_AD_IMAGE_HEIGHT, 100);
                            //$this->PImageTest->resize(UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_FULL_BANNER_AD_IMAGE_WIDTH, UPLOAD_FULL_BANNER_AD_IMAGE_HEIGHT, 100);
                            $this->request->data['Banneradvertisement']['image'] = $returnedUploadImageArray[0];
                             chmod(UPLOAD_FULL_BANNER_AD_IMAGE_PATH .$returnedUploadImageArray[0], 0755);
                             chmod(UPLOAD_THUMB_BANNER_AD_IMAGE_PATH .$returnedUploadImageArray[0], 0755);
                             
                            @unlink(UPLOAD_FULL_BANNER_AD_IMAGE_PATH . $this->data['Banneradvertisement']['old_image']);
                            @unlink(UPLOAD_THUMB_BANNER_AD_IMAGE_PATH . $this->data['Banneradvertisement']['old_image']);
                        }
                    }
                } else {
                    $this->request->data['Banneradvertisement']['image'] = $this->data['Banneradvertisement']['old_image'];
                }

                if (isset($msgString) && $msgString != '') {
                    $this->Session->setFlash($msgString, 'error_msg');
                    $this->request->data['Banneradvertisement']['image'] = $this->data['Banneradvertisement']['old_image'];
                } else {

                    if ($this->data["Banneradvertisement"]["type"] == 1) {
                        $this->request->data["Banneradvertisement"]["code"] = '';
                        $this->request->data["Banneradvertisement"]["text"] = '';
                    } elseif ($this->data["Banneradvertisement"]["type"] == 3) {
                        $this->request->data["Banneradvertisement"]["code"] = '';
                        $this->request->data['Banneradvertisement']['image'] = '';
                    } elseif ($this->data["Banneradvertisement"]["type"] == 2) {
                        $this->request->data['Banneradvertisement']['image'] = '';
                        $this->request->data['Banneradvertisement']['url'] = '';
                        $this->request->data["Banneradvertisement"]["text"] = '';
                    }
                    $this->Banneradvertisement->save($this->data);

                    $this->Session->setFlash('Advertisement updated successfully.', 'success_msg');
                    $this->redirect("/admin/banneradvertisements/index/");
                }
            }
        } elseif ($slug != '') {
            $id = $this->Banneradvertisement->field('id', array('Banneradvertisement.slug' => $slug));
            $this->Banneradvertisement->id = $id;
            $this->data = $this->Banneradvertisement->read();
            $this->set("type", $this->data['Banneradvertisement']['type']);
        }
    }

    function getBanneradvertisement($position = null, $limit = null) {
        return $advertisementdetail = $this->Banneradvertisement->find('all', array('conditions' => array('Banneradvertisement.status' => '1', 'Banneradvertisement.advertisement_place' => $position), 'order' => 'RAND()', 'limit' => $limit));
    }

}

?>
