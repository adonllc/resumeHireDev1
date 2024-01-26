<?php

class CategoriesController extends AppController {

    public $name = 'Categories';
    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'Category');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Category.name' => 'asc'));
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
        $this->set('category_list', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "List Categories");

        $condition = array('Category.parent_id' => 0);
        $separator = array();
        $urlSeparator = array();
        $name = '';

        if (!empty($this->data)) {
            if (isset($this->data['Category']['name']) && $this->data['Category']['name'] != '') {
                $name = trim($this->data['Category']['name']);
            }

            if (isset($this->data['Category']['action'])) {
                $idList = $this->data['Category']['idList'];
                if ($idList) {
                    if ($this->data['Category']['action'] == "activate") {
                        $cnd = array("Category.id IN ($idList) ");
                        $this->Category->updateAll(array('Category.status' => "'1'"), $cnd);
                    } elseif ($this->data['Category']['action'] == "deactivate") {
                        $cnd = array("Category.id IN ($idList) ");
                        $this->Category->updateAll(array('Category.status' => "'0'"), $cnd);
                    } elseif ($this->data['Category']['action'] == "delete") {
                        $cnd = array("Category.id IN ($idList) ");
                        $cnd1 = array("Category.parent_id IN ($idList) ");

                        $this->Category->deleteAll($cnd);
                        $this->Category->deleteAll($cnd1);
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
            $condition[] = " (Category.name like '%" . addslashes($name) . "%')  ";
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

        $this->paginate['Category'] = array(
            'conditions' => $condition,
            'order' => array('Category.id' => 'DESC'),
            'limit' => '50'
        );

        $this->set('categories', $this->paginate('Category', $condition));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/categories/';
            $this->render('index');
        }
    }

    public function admin_addcategory() {
        $this->set('add_category', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Category");

        global $extentions;

        if ($this->data) {

            if (empty($this->data["Category"]["name"])) {
                $msgString .="- Category Name is required field.<br>";
            } elseif ($this->Category->isRecordUniqueCategory($this->data["Category"]["name"], 0) == false) {
                $msgString .="- Category Name already exists.<br>";
            }

             if (empty($this->data["Category"]["image"]["name"])) {
                $msgString .= "- Category Image is required field.<br>";
            } else {
                list($width, $height, $type, $attr) = getimagesize($this->data["Category"]["image"]['tmp_name']);
                $getextention = $this->PImage->getExtension($this->data["Category"]["image"]['name']);
                $extention = strtolower($getextention);
                if (!in_array($extention, $extentions)) {
                    $msgString .="- Not Valid Extention.<br>";
                } elseif ($this->data["Category"]["image"]['size'] > '2097152') {
                    $msgString .="- Max file size upload is 2MB.<br>";
                } elseif ($width < 40 || $height < 40) {
                    $msgString .= "- Width and Height of Category image must be more than 40 X 40 pixels respectively.<br>";
                }elseif ($width > 100 || $height > 100) {
                    $msgString .= "- Width and Height of Category image must be lower than 100 X 100 pixels respectively.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                 if (!empty($this->data["Category"]["image"]['name'])) {
                    $imageArray = $this->data["Category"]["image"];
                    $imageArray['name'] = str_replace("\_", '_', str_replace(array('%', '$', '#', '%20', "/", "'", ' ', "\'"), '_', $imageArray['name']));

                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_CATEGORY_IMAGE_PATH, "jpg,jpeg,png");

                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= "- Category image file not valid.<br>";
                        $this->request->data["Category"]["image"] = '';
                    } else {
                        copy(UPLOAD_FULL_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0]);
                        $this->PImageTest->resize(UPLOAD_THUMB_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_CATEGORY_IMAGE_WIDTH, UPLOAD_THUMB_CATEGORY_IMAGE_HEIGHT, 100);
                        copy(UPLOAD_FULL_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0]);
                        $this->PImageTest->resize(UPLOAD_SMALL_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_CATEGORY_IMAGE_WIDTH, UPLOAD_SMALL_CATEGORY_IMAGE_HEIGHT, 100);
                        $this->request->data["Category"]["image"] = $returnedUploadImageArray[0];
                         chmod(UPLOAD_FULL_CATEGORY_IMAGE_PATH .$returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_SMALL_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                    }
                } else {
                    $this->request->data["Category"]["image"] = '';
                }
                
                $this->request->data['Category']['meta_keywords'] = trim($this->data['Category']['meta_keywords']);
                $this->request->data['Category']['meta_title'] = trim($this->data['Category']['meta_title']);
                $this->request->data['Category']['meta_description'] = trim($this->data['Category']['meta_description']);
                $this->request->data['Category']['slug'] = $this->stringToSlugUnique($this->data["Category"]["name"], 'Category');
                $this->request->data['Category']['status'] = '1';
                $this->request->data['Category']['keywords'] = trim($this->data['Category']['keywords']);
                if ($this->Category->save($this->data)) {
                    $this->Session->setFlash('Category Added Successfully', 'success_msg');
                    $this->redirect('/admin/categories/index');
                }
            }
        }
    }

    public function admin_editcategory($slug = null) {

        $this->set('category_list', 'active');
        $msgString = "";
        $this->set('catslug', $slug);
        $site_title = $this->getSiteConstant('title');
        $max_size = $this->getSiteConstant('max_size');
        $over_max_size = ($max_size) * 1048576;
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Category");

        global $extentions;

        if ($this->data) {
            if (empty($this->data["Category"]["name"])) {
                $msgString .="- Category Name is required field.<br>";
            } elseif (strtolower($this->data["Category"]["old_name"]) != strtolower($this->data["Category"]["name"])) {
                $this->request->data['Category']['slug'] = $this->stringToSlugUnique($this->data["Category"]["name"], 'Category');
                if ($this->Category->isRecordUniqueCategory($this->data["Category"]["name"], 0) == false) {
                    $msgString .="- Category Name already exists.<br>";
                }
            }
             if (!empty($this->data["Category"]["image"]['name'])) {
                list($width, $height, $type, $attr) = getimagesize($this->data["Category"]["image"]['tmp_name']);
                $getextention = $this->PImage->getExtension($this->data["Category"]["image"]['name']);
                $extention = strtolower($getextention);
                if (!in_array($extention, $extentions)) {
                    $msgString .="- Not Valid Extention.<br>";
                } elseif ($this->data["Category"]["image"]['size'] > '2097152') {
                    $msgString .="- Max file size upload is 2MB.<br>";
                } elseif ($width < 40 || $height < 40) {
                    $msgString .= "- Width and Height of Category image must be more than 40 X 40 pixels respectively.<br>";
                }elseif ($width > 100 || $height > 100) {
                    $msgString .= "- Width and Height of Category image must be lower than 100 X 100 pixels respectively.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if (!empty($this->data["Category"]["image"]['name'])) {
                    $imageArray = $this->data["Category"]["image"];
                    $imageArray['name'] = str_replace("\_", '_', str_replace(array('%', '$', '#', '%20', "/", "'", ' ', "\'"), '_', $imageArray['name']));

                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_CATEGORY_IMAGE_PATH, "jpg,jpeg,png,gif,bmp");

                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= "- Category image file not valid.<br>";
                        $this->request->data["Category"]["image"] = $this->data['Category']['old_image'];
                    } else {
                        copy(UPLOAD_FULL_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0]);
                        $this->PImageTest->resize(UPLOAD_THUMB_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_CATEGORY_IMAGE_WIDTH, UPLOAD_THUMB_CATEGORY_IMAGE_HEIGHT, 100);
                        copy(UPLOAD_FULL_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0]);
                        $this->PImageTest->resize(UPLOAD_SMALL_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_CATEGORY_IMAGE_WIDTH, UPLOAD_SMALL_CATEGORY_IMAGE_HEIGHT, 100);
                        $catPic = $returnedUploadImageArray[0];
                          chmod(UPLOAD_FULL_CATEGORY_IMAGE_PATH .$returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_SMALL_CATEGORY_IMAGE_PATH . $returnedUploadImageArray[0], 0755);
                        $this->request->data["Category"]["image"] = $catPic;
                        if ($this->data['Category']['old_image']) {
                            @unlink(UPLOAD_FULL_CATEGORY_IMAGE_PATH . $this->data['Category']['old_image']);
                            @unlink(UPLOAD_THUMB_CATEGORY_IMAGE_PATH . $this->data['Category']['old_image']);
                            @unlink(UPLOAD_SMALL_CATEGORY_IMAGE_PATH . $this->data['Category']['old_image']);
                        }
                    }
                } else {
                    $this->request->data["Category"]["image"] = $this->data['Category']['old_image'];
                }
                $this->request->data['Category']['meta_keywords'] = trim($this->data['Category']['meta_keywords']);
                $this->request->data['Category']['meta_title'] = trim($this->data['Category']['meta_title']);
                $this->request->data['Category']['meta_description'] = trim($this->data['Category']['meta_description']);
                $this->request->data['Category']['keywords'] = trim($this->data['Category']['keywords']);

                if ($this->Category->save($this->data)) {
                    $this->Session->setFlash('Category updated successfully', 'success_msg');
                    $this->redirect('/admin/categories/index');
                }
            }
        } else {
            $id = $this->Category->field('id', array('Category.slug' => $slug));
            $this->Category->id = $id;
            $this->data = $this->Category->read();
            $this->request->data['Category']['old_name'] = $this->data['Category']['name'];
             $this->request->data['Category']['old_image'] = $this->data['Category']['image'];
        }
    }

    /* ------------------------------- */
    /* ----Delete Category Image------ */
    /* ------------------------------- */

    public function admin_deleteCategoryImage($catSlug = null) {

        $this->layout = "";
        if (!empty($catSlug)) {
            $catData = $this->Category->findByslug($catSlug);
            $id = $catData['Category']['id'];
            $image = $catData['Category']['image'];
            $cnd1 = array("Category.id = '$id'");
            $this->Category->updateAll(array('Category.image' => "''"), $cnd1);
            @unlink(UPLOAD_FULL_CATEGORY_IMAGE_PATH . $image);
            @unlink(UPLOAD_THUMB_CATEGORY_IMAGE_PATH . $image);
            @unlink(UPLOAD_SMALL_CATEGORY_IMAGE_PATH . $image);
            $this->Session->setFlash('Category Image deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'categories', 'action' => 'editcategory', $catSlug));
        }
    }

    public function admin_activateCategory($slug = NULL, $parentSlug = null) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Category->field('id', array('Category.slug' => $slug));
            $cnd = array("Category.id = $id");
            $this->Category->updateAll(array('Category.status' => "'1'"), $cnd);
            $this->set('action', '/admin/categories/deactivateCategory/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivateCategory($slug = NULL, $parentSlug = null) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Category->field('id', array('Category.slug' => $slug));
            $cnd = array("Category.id = $id");
            $this->Category->updateAll(array('Category.status' => "'0'"), $cnd);
            $this->set('action', '/admin/categories/activateCategory/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);


            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deleteCategory($slug = null) {
        $id = $this->Category->field('id', array('Category.slug' => $slug));
        if ($id) {
            $this->Category->deleteAll(array('Category.parent_id' => $id));
            $this->Category->delete($id);
            $this->Session->setFlash('Category deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'categories', 'action' => 'index'));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'categories', 'action' => 'index'));
        }
    }

    public function admin_subindex($cslug = null) {
        $this->layout = "admin";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');

        $this->set('title_for_layout', $site_title . " :: " . $tagline . ' - ' . 'Sub Categories List');
        $this->set('category_list', 'active');


        $cateInfo = $this->Category->findBySlug($cslug);
        if (!$cateInfo) {
            $this->redirect('/admin/categories/index/');
        }
        $this->set('cateInfo', $cateInfo);
        $this->set('cslug', $cslug);

        $condition = array('Category.parent_id' => $cateInfo['Category']['id']);
        $separator = array();
        $urlSeparator = array();
        $name = '';

        if (!empty($this->data)) {
            if (isset($this->data['Category']['name']) && $this->data['Category']['name'] != '') {
                $name = trim($this->data['Category']['name']);
            }

            if (isset($this->data['Category']['action'])) {
                $idList = $this->data['Category']['idList'];
                if ($idList) {
                    if ($this->data['Category']['action'] == "activate") {
                        $cnd = array("Category.id IN ($idList) ");
                        $this->Category->updateAll(array('Category.status' => "'1'"), $cnd);
                    } elseif ($this->data['Category']['action'] == "deactivate") {
                        $cnd = array("Category.id IN ($idList) ");
                        $this->Category->updateAll(array('Category.status' => "'0'"), $cnd);
                    } elseif ($this->data['Category']['action'] == "delete") {
                          $cnd = array("Category.id IN ($idList) ");
                        $this->Category->deleteAll($cnd);
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
            $condition[] = " (Category.name like '%" . addslashes($name) . "%')  ";
        }

        $order = array('Category.id' => 'Desc');

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

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->set('name', $name);
        $this->set('searchKey', $name);

        $this->paginate['Category'] = array('conditions' => $condition, 'limit' => '10', 'page' => '1', 'order' => $order);
        $this->set('categories', $this->paginate('Category'));
        /////pr($this->paginate('Category'));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/categories';
            $this->render('subindex');
        }
    }

    /**
     *
     * @abstract This function is define to add Sub Categories from backend.
     * @access Public
     * @author Logicspice (info@logicspice)
     */
    public function admin_addsubcat($cslug = null) {

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');

        $this->set('title_for_layout', $site_title . " :: " . $tagline . ' - ' . 'Add Sub Category');
        $this->layout = "admin";
        $this->set('category_list', 'active');
        $msgString = '';

        $cateInfo = $this->Category->findBySlug($cslug);
        if (!$cateInfo) {
            $this->redirect('/admin/categories/index/');
        }

        $this->set('cateInfo', $cateInfo);
        $this->set('cslug', $cslug);

        if ($this->data) {

            if (trim($this->data["Category"]["name"]) == '') {
                $msgString .="- Sub category name is required field.<br>";
            } else if ($this->Category->isRecordUniqueSubCategories($this->data["Category"]["name"], $cateInfo["Category"]["id"]) == false) {
                $msgString .="- Sub category name already exists.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data['Category']['status'] = 1;
                $this->request->data['Category']['parent_id'] = $cateInfo["Category"]["id"];
                $this->request->data['Category']['name'] = trim($this->data['Category']['name']);
                $this->request->data['Category']['slug'] = $this->stringToSlugUnique($this->data['Category']['name'], 'Category');
                if ($this->Category->save($this->data)) {
                    $this->Session->setFlash('Sub category added successfully', 'success_msg');
                    $this->redirect(array('controller' => 'categories', 'action' => 'subindex', $cslug));
                }
            }
        }
    }

    /**
     *
     * @abstract This function is define to edit Sub Categories from backend.
     * @access Public
     * @author Logicspice (info@logicspice)
     */
    public function admin_editsubcat($slug = null, $cslug = null) {

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');

        $this->set('title_for_layout', $site_title . " :: " . $tagline . ' - ' . 'Edit Sub Category Details');
        $this->layout = "admin";
        $this->set('category_list', 'active');
        $msgString = '';

        $cateInfo = $this->Category->findBySlug($cslug);
        if (!$cateInfo) {
            $this->redirect('/admin/categories/index/');
        }

        $this->set('cateInfo', $cateInfo);
        $this->set('cslug', $cslug);

        if ($this->data) {

            if (trim($this->data["Category"]["name"]) == '') {
                $msgString .="- Sub category name is required field.<br>";
            } elseif (strtolower($this->data["Category"]["name"]) != strtolower($this->data["Category"]["old_name"])) {
                if ($this->Category->isRecordUniqueSubCategories($this->data["Category"]["name"], $cateInfo["Category"]["id"]) == false) {
                    $msgString .="- Sub category name already exists.<br>";
                }
            }
            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data['Category']['name'] = trim($this->data['Category']['name']);
                if ($this->Category->save($this->data)) {
                    $this->Session->setFlash('Sub category updated successfully', 'success_msg');
                    $this->redirect(array('controller' => 'categories', 'action' => 'subindex', $cslug));
                }
            }
        } elseif ($slug != '') {
            $id = $this->Category->field('id', array('Category.slug' => $slug));
            $this->Category->id = $id;
            $this->data = $this->Category->read();
            $this->request->data['Category']['old_name'] = $this->data['Category']['name'];
        }
    }

    /**
     *
     * @abstract This function is define to activate Sub Categories from backend.
     * @access Public
     * @author Logicspice (info@logicspice)
     */
    public function admin_activatesubcat($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Category->field('id', array('Category.slug' => $slug));
            $cnd = array('Category.id' => $id);
            $this->Category->updateAll(array('Category.status' => "'1'"), $cnd);
            $this->set('action', '/admin/categories/deactivatesubcat/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    /**
     *
     * @abstract This function is define to deactivate Sub Categories from backend.
     * @access Public
     * @author Logicspice (info@logicspice)
     */
    public function admin_deactivatesubcat($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->Category->field('id', array('Category.slug' => $slug));
            $cnd = array('Category.id' => $id);
            $this->Category->updateAll(array('Category.status' => "'0'"), $cnd);
            $this->set('action', '/admin/categories/activatesubcat/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    /**
     *
     * @abstract This function is define to delete Sub Categories from backend.
     * @access Public
     * @author Logicspice (info@logicspice)
     */
    public function admin_deletesubcat($slug = NULL, $cslug = null) {
        if ($slug != '') {
            if (!empty($cslug)) {
                $mainCategoryDetail = $this->Category->findBySlug($cslug);
                if (empty($mainCategoryDetail) || $mainCategoryDetail['Category']['parent_id'] != 0) {
                    $this->Session->setFlash('Wrong URL access.', 'error_msg');
                    $this->redirect(array('controller' => 'categories', 'action' => 'index', ''));
                }
            }
            $id = $this->Category->field('id', array('Category.slug' => $slug));
            $this->Category->delete($id);
            $this->Session->setFlash('Sub category deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'categories', 'action' => 'subindex', $cslug));
        }
    }

    public function admin_getsubcategory() {
        $this->layout = '';
        $categoryId = $this->data['Product']['category_id'];
        $subcatlist = $this->Category->find('list', array('conditions' => array('status' => 1, 'parent_id' => $categoryId), 'fields' => array('id', 'name'), 'order' => array('name' => 'ASC')));
        $this->set('subcatlist', $subcatlist);
    }

    public function getSubCategory($categoryId = null) {
        $this->layout = '';
        if (!empty($categoryId)) {
            $subcategories = $this->Category->getSubCategoryList($categoryId);
            $this->set('subcategories', $subcategories);
        }
    }
    
       public function allcategories() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('home', 'All categories', true));

        $categories = $this->Category->find('list', array('conditions' => array('Category.parent_id' => 0, 'Category.status' => 1), 'fields' => array('Category.slug', 'Category.name')));
        $this->set('categories', $categories);
    }

}

?>
