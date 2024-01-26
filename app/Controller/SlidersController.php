<?php

class SlidersController extends AppController {

    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'Swear', 'Slider');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Slider.name' => 'asc'));
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
        $this->set('slider_list', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Slider list");

        $condition = array();
        $separator = array();
        $urlSeparator = array();
        $userName = '';
        $searchByDateFrom = '';
        $searchByDateTo = '';

        $this->set('slug', $slug);

        if (!empty($this->data)) {

            if (isset($this->data['Slider']['userName']) && $this->data['Slider']['userName'] != '') {
                $userName = trim($this->data['Slider']['userName']);
            }

            if (isset($this->data['Slider']['searchByDateFrom']) && $this->data['Slider']['searchByDateFrom'] != '') {
                $searchByDateFrom = trim($this->data['Slider']['searchByDateFrom']);
            }

            if (isset($this->data['Slider']['searchByDateTo']) && $this->data['Slider']['searchByDateTo'] != '') {
                $searchByDateTo = trim($this->data['Slider']['searchByDateTo']);
            }

            if (isset($this->data['Slider']['action'])) {
                $idList = $this->data['Slider']['idList'];
                if ($idList) {
                    if ($this->data['Slider']['action'] == "activate") {
                        $cnd = array("Slider.id IN ($idList) ");
                        $this->Slider->updateAll(array('Slider.status' => "'1'"), $cnd);
                    } elseif ($this->data['Slider']['action'] == "deactivate") {
                        $cnd = array("Slider.id IN ($idList) ");
                        $this->Slider->updateAll(array('Slider.status' => "'0'"), $cnd);
                    } elseif ($this->data['Slider']['action'] == "delete") {
                        $cnd = array("Slider.id IN ($idList) ");
                        $this->Slider->deleteAll($cnd);
                    }
                }
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['userName']) && $this->params['named']['userName'] != '') {
                $userName = urldecode(trim($this->params['named']['userName']));
            }
            if (isset($this->params['named']['searchByDateFrom']) && $this->params['named']['searchByDateFrom'] != '') {
                $searchByDateFrom = urldecode(trim($this->params['named']['searchByDateFrom']));
            }
            if (isset($this->params['named']['searchByDateTo']) && $this->params['named']['searchByDateTo'] != '') {
                $searchByDateTo = urldecode(trim($this->params['named']['searchByDateTo']));
            }
        }

        if (isset($userName) && $userName != '') {
            $separator[] = 'userName:' . urlencode($userName);
            $userName = str_replace('_', '\_', $userName);
            $condition[] = " (`Slider`.`title` LIKE '%" . addslashes($userName) . "%') ";
            $userName = str_replace('\_', '_', $userName);
            $this->set('searchKey', $userName);
        }

        if (isset($searchByDateFrom) && $searchByDateFrom != '') {
            $separator[] = 'searchByDateFrom:' . urlencode($searchByDateFrom);
            $searchByDateFrom = str_replace('_', '\_', $searchByDateFrom);
            $searchByDate_con1 = date('Y-m-d', strtotime($searchByDateFrom));
            $condition[] = " (Date(Slider.created)>='$searchByDate_con1' ) ";
            $searchByDateFrom = str_replace('\_', '_', $searchByDateFrom);
        }

        if (isset($searchByDateTo) && $searchByDateTo != '') {
            $separator[] = 'searchByDateTo:' . urlencode($searchByDateTo);
            $searchByDateTo = str_replace('_', '\_', $searchByDateTo);
            $searchByDate_con2 = date('Y-m-d', strtotime($searchByDateTo));
            $condition[] = " (Date(Slider.created)<='$searchByDate_con2' ) ";
            $searchByDateTo = str_replace('\_', '_', $searchByDateTo);
        }

        $order = 'Slider.id Desc';

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
        $this->set('searchByDateFrom', $searchByDateFrom);
        $this->set('searchByDateTo', $searchByDateTo);
        $_SESSION['searchByDateFrom'] = $searchByDateFrom;
        $_SESSION['searchByDateTo'] = $searchByDateTo;
        $_SESSION['userName'] = $userName;


        $urlSeparator = implode("/", $urlSeparator);
        $this->set('userName', $userName);
        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['Slider'] = array('conditions' => $condition, 'limit' => '50', 'page' => '1', 'order' => $order);
        $this->set('sliders', $this->paginate('Slider'));
        //pr($condition);


        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/sliders';
            $this->render('index');
        }
    }

    public function admin_add() {


        $this->set('add_slider', 'active');
        $msgString = "";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Slider");

        global $extentions;

        if ($this->data) {
            //  pr($this->data);exit;
            if (empty($this->data["Slider"]["title"])) {
                $msgString .="- Title is required field.<br>";
            } else {

                if ($this->Slider->isRecordUniquetitle($this->data["Slider"]["title"]) == false) {
                    $msgString .="- Slider title already exists.<br>";
                } else {
                    $msgString .= $this->Swear->checkSwearWord($this->data["Slider"]["title"]);
                }
            }

//            if (empty($this->data["Slider"]["description"])) {
//                $msgString .="- Description is required field.<br>";
//            } else {
//                $msgString .= $this->Swear->checkSwearWord($this->data["Slider"]["description"]);
//            }
//
//            if (!empty($this->data["Slider"]["meta_title"])) {
//                $msgString .= $this->Swear->checkSwearWord($this->data["Slider"]["meta_title"]);
//            }
//            if (!empty($this->data["Slider"]["meta_keyword"])) {
//                $msgString .= $this->Swear->checkSwearWord($this->data["Slider"]["meta_keyword"]);
//            }
//            if (!empty($this->data["Slider"]["meta_description"])) {
//                $msgString .= $this->Swear->checkSwearWord($this->data["Slider"]["meta_description"]);
//            }

            if (empty($this->data["Slider"]["image"]["name"])) {
                $msgString .= "- Slider Image is required field.<br>";
            } else {
                list($width, $height, $type, $attr) = getimagesize($this->data["Slider"]["image"]['tmp_name']);
                $getextention = $this->PImage->getExtension($this->data["Slider"]["image"]['name']);
                $extention = strtolower($getextention);
                if (!in_array($extention, $extentions)) {
                    $msgString .="- Not Valid Extention.<br>";
                } elseif ($this->data["Slider"]["image"]['size'] > '2097152') {
                    $msgString .="- Max file size upload is 2MB.<br>";
                } elseif ($width < 250 || $height < 250) {
//                    $msgString .= "- Width and Height of blog image must be more than 250 X 250 pixels respectively.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                //   pr($this->data);

                if (!empty($this->data["Slider"]["image"]['name'])) {
                    $imageArray = $this->data["Slider"]["image"];
                    $imageArray['name'] = str_replace("\_", '_', str_replace(array('%', '$', '#', '%20', "/", "'", ' ', "\'"), '_', $imageArray['name']));

                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_SLIDER_IMAGE_PATH, "jpg,jpeg,png");

                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= "- Slider image file not valid.<br>";
                        $this->request->data["Slider"]["image"] = '';
                    } else {
                        copy(UPLOAD_FULL_SLIDER_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_SLIDER_IMAGE_PATH . $returnedUploadImageArray[0]);
                        $this->PImageTest->resize(UPLOAD_THUMB_SLIDER_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_SLIDER_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_SLIDER_WIDTH, UPLOAD_THUMB_SLIDER_HEIGHT, 100);
                        $this->request->data["Slider"]["image"] = $returnedUploadImageArray[0];
                         chmod(UPLOAD_FULL_SLIDER_IMAGE_PATH .$returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_SLIDER_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                    }
                } else {
                    $this->request->data["Slider"]["image"] = '';
                }


                $this->request->data['Slider']['status'] = 1;
                $this->request->data['Slider']['slug'] = $this->stringToSlugUnique($this->data['Slider']['title'], 'Slider', 'slug');



                if ($this->Slider->save($this->data)) {
                    // pr($this->data);
                    $this->Session->setFlash('Slider details saved successfully', 'success_msg');
                    $this->redirect('/admin/sliders/index');
                }
            }
        }
    }

    public function admin_edit($slug = null) {

        $this->set('slider_list', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Slider");

        global $extentions;
        $this->set('blogslug', $slug);
        $this->set('blogtitle', $this->Slider->field('title', array('slug' => $slug)));


        if ($this->data) {
            //  pr($this->data);exit;
            if (empty($this->data["Slider"]["title"])) {
                $msgString .="- Title is required field.<br>";
            } else {

                if (strtolower($this->data["Slider"]["title"]) != strtolower($this->data["Slider"]["old_title"])) {
                    if ($this->Slider->isRecordUniquetitle($this->data["Slider"]["title"]) == false) {
                        $msgString .="- Slider Title already exists.<br>";
                    }
                } else {
                    $msgString .= $this->Swear->checkSwearWord($this->data["Slider"]["title"]);
                }
            }


//            if (empty($this->data["Slider"]["description"])) {
//                $msgString .="- Description is required field.<br>";
//            } else {
//                $msgString .= $this->Swear->checkSwearWord($this->data["Slider"]["description"]);
//            }
//
//            if (!empty($this->data["Slider"]["meta_title"])) {
//                $msgString .= $this->Swear->checkSwearWord($this->data["Slider"]["meta_title"]);
//            }
//            if (!empty($this->data["Slider"]["meta_keyword"])) {
//                $msgString .= $this->Swear->checkSwearWord($this->data["Slider"]["meta_keyword"]);
//            }
//            if (!empty($this->data["Slider"]["meta_description"])) {
//                $msgString .= $this->Swear->checkSwearWord($this->data["Slider"]["meta_description"]);
//            }

            if (!empty($this->data["Slider"]["image"]['name'])) {
                list($width, $height, $type, $attr) = getimagesize($this->data["Slider"]["image"]['tmp_name']);
                $getextention = $this->PImage->getExtension($this->data["Slider"]["image"]['name']);
                $extention = strtolower($getextention);
                if (!in_array($extention, $extentions)) {
                    $msgString .="- Not Valid Extention.<br>";
                } elseif ($this->data["Slider"]["image"]['size'] > '2097152') {
                    $msgString .="- Max file size upload is 2MB.<br>";
                } elseif ($width < 250 || $height < 250) {
//                    $msgString .= "- Width and Height of Slider image must be more than 250 X 250 pixels respectively.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {


                if (!empty($this->data["Slider"]["image"]['name'])) {
                    $imageArray = $this->data["Slider"]["image"];
                    $imageArray['name'] = str_replace("\_", '_', str_replace(array('%', '$', '#', '%20', "/", "'", ' ', "\'"), '_', $imageArray['name']));

                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_SLIDER_IMAGE_PATH, "jpg,jpeg,png,gif,bmp");

                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= "- Slider image file not valid.<br>";
                        $this->request->data["Slider"]["image"] = $this->data['Slider']['old_image'];
                    } else {
                        copy(UPLOAD_FULL_SLIDER_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_SLIDER_IMAGE_PATH . $returnedUploadImageArray[0]);
                        $this->PImageTest->resize(UPLOAD_THUMB_SLIDER_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_SLIDER_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_SLIDER_WIDTH, UPLOAD_THUMB_SLIDER_HEIGHT, 100);
                        $blogPic = $returnedUploadImageArray[0];
                         chmod(UPLOAD_FULL_SLIDER_IMAGE_PATH .$returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_SLIDER_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        $this->request->data["Slider"]["image"] = $blogPic;
                        if ($this->data['Slider']['old_image']) {
                            @unlink(UPLOAD_FULL_SLIDER_IMAGE_PATH . $this->data['Slider']['old_image']);
                            @unlink(UPLOAD_THUMB_SLIDER_IMAGE_PATH . $this->data['Slider']['old_image']);
                        }
                    }
                } else {
                    $this->request->data["Slider"]["image"] = $this->data['Slider']['old_image'];
                }

                if (isset($msgString) && $msgString != '') {
                    $this->Session->write('error_msg', $msgString);
                } else {
                    if ($this->data['Slider']['delete'] == 1) {
                        $image = $this->data['Slider']['old_image'];
                        $filePath = UPLOAD_FULL_SLIDER_IMAGE_PATH . $image;
                        if (file_exists($filePath) && $image) {
                            @unlink(UPLOAD_FULL_SLIDER_IMAGE_PATH . $image);
                            @unlink(UPLOAD_THUMB_SLIDER_IMAGE_PATH . $image);
                            @unlink(UPLOAD_SMALL_SLIDER_PATH . $image);
                        }
                        if ($this->data["Slider"]["image"] == $image) {
                            $this->request->data["Slider"]["image"] = '';
                        }
                    }

                    if ($this->Slider->save($this->data)) {

                        $this->Session->setFlash('Slider details updated successfully', 'success_msg');
                        $this->redirect('/admin/sliders/index');
                    }
                }
            }
        } elseif ($slug != '') {
            $id = $this->Slider->field('id', array('Slider.slug' => $slug));
            $this->Slider->id = $id;
            $this->data = $this->Slider->read();
            $this->request->data['Slider']['old_image'] = $this->data['Slider']['image'];
            $this->request->data['Slider']['old_title'] = $this->data['Slider']['title'];
        }
    }

    public function admin_activate($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Slider->field('id', array('Slider.slug' => $slug));
            $cnd = array("Slider.id = $id");
            $this->Slider->updateAll(array('Slider.status' => "'1'"), $cnd);
            $this->set('action', '/admin/sliders/deactivate/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivate($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Slider->field('id', array('Slider.slug' => $slug));
            $cnd = array("Slider.id = $id");
            $this->Slider->updateAll(array('Slider.status' => "'0'"), $cnd);
            $this->set('action', '/admin/sliders/activate/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_delete($slug = NULL) {
        $this->set('list_blogs', 'active');
        $this->set('default', '1');
        if ($slug != '') {
            $id = $this->Slider->field('id', array('Slider.slug' => $slug));
            $image = $this->Slider->field('image', array('Slider.slug' => $slug));
            if ($this->Slider->delete($id)) {
                @unlink(UPLOAD_FULL_SLIDER_IMAGE_PATH . $image);
                @unlink(UPLOAD_THUMB_SLIDER_IMAGE_PATH . $image);
            }

            $this->Session->setFlash('Slider details deleted successfully', 'success_msg');
        }
        $this->redirect('/admin/sliders/index');
    }

    public function admin_deleteBlogImage($blogSlug = null) {

        $this->layout = "";
        if (!empty($blogSlug)) {
            $blogData = $this->Slider->findByslug($blogSlug);
            $id = $blogData['Slider']['id'];
            $image = $blogData['Slider']['image'];
            $cnd1 = array("Slider.id = '$id'");
            $this->Slider->updateAll(array('Slider.image' => "''"), $cnd1);
            @unlink(UPLOAD_FULL_SLIDER_IMAGE_PATH . $image);
            @unlink(UPLOAD_THUMB_SLIDER_IMAGE_PATH . $image);
            $this->Session->setFlash('Image deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'sliders', 'action' => 'edit', $blogSlug));
        }
    }

    

}

?>
