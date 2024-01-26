<?php

class BlogsController extends AppController {

    public $uses = array('Admin', 'Page', 'Emailtemplate', 'User', 'Blog', 'Swear');
    public $helpers = array('Html', 'Form', 'Paginator', 'Fck', 'Javascript', 'Ajax', 'Js', 'Text', 'Number');
    public $paginate = array('limit' => '50', 'page' => '1', 'order' => array('Skill.name' => 'asc'));
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
        $this->set('blog_list', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Blog list");

        $condition = array();
        $separator = array();
        $urlSeparator = array();
        $userName = '';
        $searchByDateFrom = '';
        $searchByDateTo = '';

        $this->set('slug', $slug);

        if (!empty($this->data)) {

            if (isset($this->data['Blog']['userName']) && $this->data['Blog']['userName'] != '') {
                $userName = trim($this->data['Blog']['userName']);
            }

            if (isset($this->data['Blog']['searchByDateFrom']) && $this->data['Blog']['searchByDateFrom'] != '') {
                $searchByDateFrom = trim($this->data['Blog']['searchByDateFrom']);
            }

            if (isset($this->data['Blog']['searchByDateTo']) && $this->data['Blog']['searchByDateTo'] != '') {
                $searchByDateTo = trim($this->data['Blog']['searchByDateTo']);
            }

            if (isset($this->data['Blog']['action'])) {
                $idList = $this->data['Blog']['idList'];
                if ($idList) {
                    if ($this->data['Blog']['action'] == "activate") {
                        $cnd = array("Blog.id IN ($idList) ");
                        $this->Blog->updateAll(array('Blog.status' => "'1'"), $cnd);
                    } elseif ($this->data['Blog']['action'] == "deactivate") {
                        $cnd = array("Blog.id IN ($idList) ");
                        $this->Blog->updateAll(array('Blog.status' => "'0'"), $cnd);
                    } elseif ($this->data['Blog']['action'] == "delete") {
                        $cnd = array("Blog.id IN ($idList) ");
                        $this->Blog->deleteAll($cnd);
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
            $condition[] = " (`Blog`.`title` LIKE '%" . addslashes($userName) . "%') ";
            $userName = str_replace('\_', '_', $userName);
            $this->set('searchKey', $userName);
        }

        if (isset($searchByDateFrom) && $searchByDateFrom != '') {
            $separator[] = 'searchByDateFrom:' . urlencode($searchByDateFrom);
            $searchByDateFrom = str_replace('_', '\_', $searchByDateFrom);
            $searchByDate_con1 = date('Y-m-d', strtotime($searchByDateFrom));
            $condition[] = " (Date(Blog.created)>='$searchByDate_con1' ) ";
            $searchByDateFrom = str_replace('\_', '_', $searchByDateFrom);
        }

        if (isset($searchByDateTo) && $searchByDateTo != '') {
            $separator[] = 'searchByDateTo:' . urlencode($searchByDateTo);
            $searchByDateTo = str_replace('_', '\_', $searchByDateTo);
            $searchByDate_con2 = date('Y-m-d', strtotime($searchByDateTo));
            $condition[] = " (Date(Blog.created)<='$searchByDate_con2' ) ";
            $searchByDateTo = str_replace('\_', '_', $searchByDateTo);
        }

        $order = 'Blog.id Desc';

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
        $this->paginate['Blog'] = array('conditions' => $condition, 'limit' => '50', 'page' => '1', 'order' => $order);
        $this->set('blogs', $this->paginate('Blog'));
        //pr($condition);


        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/blogs';
            $this->render('index');
        }
    }

    public function admin_addblogs() {


        $this->set('add_blog', 'active');
        $msgString = "";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Blog");

        global $extentions;

        if ($this->data) {
            //  pr($this->data);exit;
            if (empty($this->data["Blog"]["title"])) {
                $msgString .="- Title is required field.<br>";
            } else {

                if ($this->Blog->isRecordUniquetitle($this->data["Blog"]["title"]) == false) {
                    $msgString .="- Blog title already exists.<br>";
                } else {
                    $msgString .= $this->Swear->checkSwearWord($this->data["Blog"]["title"]);
                }
            }

            if (empty($this->data["Blog"]["description"])) {
                $msgString .="- Description is required field.<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["Blog"]["description"]);
            }

            if (!empty($this->data["Blog"]["meta_title"])) {
                $msgString .= $this->Swear->checkSwearWord($this->data["Blog"]["meta_title"]);
            }
            if (!empty($this->data["Blog"]["meta_keyword"])) {
                $msgString .= $this->Swear->checkSwearWord($this->data["Blog"]["meta_keyword"]);
            }
            if (!empty($this->data["Blog"]["meta_description"])) {
                $msgString .= $this->Swear->checkSwearWord($this->data["Blog"]["meta_description"]);
            }

            if (empty($this->data["Blog"]["image"]["name"])) {
                $msgString .= "- Blog Image is required field.<br>";
            } else {
                list($width, $height, $type, $attr) = getimagesize($this->data["Blog"]["image"]['tmp_name']);
                $getextention = $this->PImage->getExtension($this->data["Blog"]["image"]['name']);
                $extention = strtolower($getextention);
                if (!in_array($extention, $extentions)) {
                    $msgString .="- Not Valid Extention.<br>";
                } elseif ($this->data["Blog"]["image"]['size'] > '2097152') {
                    $msgString .="- Max file size upload is 2MB.<br>";
                } elseif ($width < 250 || $height < 250) {
                    $msgString .= "- Width and Height of blog image must be more than 250 X 250 pixels respectively.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                //   pr($this->data);

                if (!empty($this->data["Blog"]["image"]['name'])) {
                    $imageArray = $this->data["Blog"]["image"];
                    $imageArray['name'] = str_replace("\_", '_', str_replace(array('%', '$', '#', '%20', "/", "'", ' ', "\'"), '_', $imageArray['name']));

                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_BLOG_PATH, "jpg,jpeg,png");

                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= "- Blog image file not valid.<br>";
                        $this->request->data["Blog"]["image"] = '';
                    } else {
                        copy(UPLOAD_FULL_BLOG_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_BLOG_PATH . $returnedUploadImageArray[0]);
                        $this->PImageTest->resize(UPLOAD_THUMB_BLOG_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_BLOG_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_BLOG_WIDTH, UPLOAD_THUMB_BLOG_HEIGHT, 100);
                        copy(UPLOAD_FULL_BLOG_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_BLOG_PATH . $returnedUploadImageArray[0]);
                        $this->PImageTest->resize(UPLOAD_SMALL_BLOG_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_BLOG_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_BLOG_WIDTH, UPLOAD_SMALL_BLOG_HEIGHT, 100);
                        $this->request->data["Blog"]["image"] = $returnedUploadImageArray[0];
                         chmod(UPLOAD_FULL_BLOG_PATH .$returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_BLOG_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_SMALL_BLOG_PATH . $returnedUploadImageArray[0], 0755);
                    }
                } else {
                    $this->request->data["Blog"]["image"] = '';
                }


                $this->request->data['Blog']['status'] = 1;
                $this->request->data['Blog']['slug'] = $this->stringToSlugUnique($this->data['Blog']['title'], 'Blog', 'slug');



                if ($this->Blog->save($this->data)) {
                    // pr($this->data);
                    $this->Session->setFlash('Blog details saved successfully', 'success_msg');
                    $this->redirect('/admin/blogs/index');
                }
            }
        }
    }

    public function admin_editblogs($slug = null) {

        $this->set('blog_list', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Blog");

        global $extentions;
        $this->set('blogslug', $slug);
        $this->set('blogtitle', $this->Blog->field('title', array('slug' => $slug)));


        if ($this->data) {
            //  pr($this->data);exit;
            if (empty($this->data["Blog"]["title"])) {
                $msgString .="- Title is required field.<br>";
            } else {

                if (strtolower($this->data["Blog"]["title"]) != strtolower($this->data["Blog"]["old_title"])) {
                    if ($this->Blog->isRecordUniquetitle($this->data["Blog"]["title"]) == false) {
                        $msgString .="- Blog Title already exists.<br>";
                    }
                } else {
                    $msgString .= $this->Swear->checkSwearWord($this->data["Blog"]["title"]);
                }
            }


            if (empty($this->data["Blog"]["description"])) {
                $msgString .="- Description is required field.<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["Blog"]["description"]);
            }

            if (!empty($this->data["Blog"]["meta_title"])) {
                $msgString .= $this->Swear->checkSwearWord($this->data["Blog"]["meta_title"]);
            }
            if (!empty($this->data["Blog"]["meta_keyword"])) {
                $msgString .= $this->Swear->checkSwearWord($this->data["Blog"]["meta_keyword"]);
            }
            if (!empty($this->data["Blog"]["meta_description"])) {
                $msgString .= $this->Swear->checkSwearWord($this->data["Blog"]["meta_description"]);
            }

            if (!empty($this->data["Blog"]["image"]['name'])) {
                list($width, $height, $type, $attr) = getimagesize($this->data["Blog"]["image"]['tmp_name']);
                $getextention = $this->PImage->getExtension($this->data["Blog"]["image"]['name']);
                $extention = strtolower($getextention);
                if (!in_array($extention, $extentions)) {
                    $msgString .="- Not Valid Extention.<br>";
                } elseif ($this->data["Blog"]["image"]['size'] > '2097152') {
                    $msgString .="- Max file size upload is 2MB.<br>";
                } elseif ($width < 250 || $height < 250) {
                    $msgString .= "- Width and Height of Blog image must be more than 250 X 250 pixels respectively.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {


                if (!empty($this->data["Blog"]["image"]['name'])) {
                    $imageArray = $this->data["Blog"]["image"];
                    $imageArray['name'] = str_replace("\_", '_', str_replace(array('%', '$', '#', '%20', "/", "'", ' ', "\'"), '_', $imageArray['name']));

                    $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_FULL_BLOG_PATH, "jpg,jpeg,png,gif,bmp");

                    if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                        $msgString .= "- Blog image file not valid.<br>";
                        $this->request->data["Blog"]["image"] = $this->data['Blog']['old_image'];
                    } else {
                        copy(UPLOAD_FULL_BLOG_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_BLOG_PATH . $returnedUploadImageArray[0]);
                        $this->PImageTest->resize(UPLOAD_THUMB_BLOG_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_BLOG_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_BLOG_WIDTH, UPLOAD_THUMB_BLOG_HEIGHT, 100);
                        copy(UPLOAD_FULL_BLOG_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_BLOG_PATH . $returnedUploadImageArray[0]);
                        $this->PImageTest->resize(UPLOAD_SMALL_BLOG_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_BLOG_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_BLOG_WIDTH, UPLOAD_SMALL_BLOG_HEIGHT, 100);
                        $blogPic = $returnedUploadImageArray[0];
                          chmod(UPLOAD_FULL_BLOG_PATH .$returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_THUMB_BLOG_PATH . $returnedUploadImageArray[0], 0755);
                        chmod(UPLOAD_SMALL_BLOG_PATH . $returnedUploadImageArray[0], 0755);
                        $this->request->data["Blog"]["image"] = $blogPic;
                        if ($this->data['Blog']['old_image']) {
                            @unlink(UPLOAD_FULL_BLOG_PATH . $this->data['Blog']['old_image']);
                            @unlink(UPLOAD_THUMB_BLOG_PATH . $this->data['Blog']['old_image']);
                            @unlink(UPLOAD_SMALL_BLOG_PATH . $this->data['Blog']['old_image']);
                        }
                    }
                } else {
                    $this->request->data["Blog"]["image"] = $this->data['Blog']['old_image'];
                }

                if (isset($msgString) && $msgString != '') {
                    $this->Session->write('error_msg', $msgString);
                } else {
                    if ($this->data['Blog']['delete'] == 1) {
                        $image = $this->data['Blog']['old_image'];
                        $filePath = UPLOAD_FULL_BLOG_PATH . $image;
                        if (file_exists($filePath) && $image) {
                            @unlink(UPLOAD_FULL_BLOG_PATH . $image);
                            @unlink(UPLOAD_THUMB_BLOG_PATH . $image);
                            @unlink(UPLOAD_SMALL_BLOG_PATH . $image);
                        }
                        if ($this->data["Blog"]["image"] == $image) {
                            $this->request->data["Blog"]["image"] = '';
                        }
                    }

                    if ($this->Blog->save($this->data)) {

                        $this->Session->setFlash('Blog details updated successfully', 'success_msg');
                        $this->redirect('/admin/blogs/index');
                    }
                }
            }
        } elseif ($slug != '') {
            $id = $this->Blog->field('id', array('Blog.slug' => $slug));
            $this->Blog->id = $id;
            $this->data = $this->Blog->read();
            $this->request->data['Blog']['old_image'] = $this->data['Blog']['image'];
            $this->request->data['Blog']['old_title'] = $this->data['Blog']['title'];
        }
    }

    public function admin_activateblog($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Blog->field('id', array('Blog.slug' => $slug));
            $cnd = array("Blog.id = $id");
            $this->Blog->updateAll(array('Blog.status' => "'1'"), $cnd);
            $this->set('action', '/admin/blogs/deactivateblog/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivateblog($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Blog->field('id', array('Blog.slug' => $slug));
            $cnd = array("Blog.id = $id");
            $this->Blog->updateAll(array('Blog.status' => "'0'"), $cnd);
            $this->set('action', '/admin/blogs/activateblog/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deleteblogs($slug = NULL) {
        $this->set('list_blogs', 'active');
        $this->set('default', '1');
        if ($slug != '') {
            $id = $this->Blog->field('id', array('Blog.slug' => $slug));
            $image = $this->Blog->field('image', array('Blog.slug' => $slug));
            if ($this->Blog->delete($id)) {
                @unlink(UPLOAD_FULL_BLOG_PATH . $image);
                @unlink(UPLOAD_THUMB_BLOG_PATH . $image);
                @unlink(UPLOAD_SMALL_BLOG_PATH . $image);
            }

            $this->Session->setFlash('Blog details deleted successfully', 'success_msg');
        }
        $this->redirect('/admin/blogs/index');
    }

    public function admin_deleteBlogImage($blogSlug = null) {

        $this->layout = "";
        if (!empty($blogSlug)) {
            $blogData = $this->Blog->findByslug($blogSlug);
            $id = $blogData['Blog']['id'];
            $image = $blogData['Blog']['image'];
            $cnd1 = array("Blog.id = '$id'");
            $this->Blog->updateAll(array('Blog.image' => "''"), $cnd1);
            @unlink(UPLOAD_FULL_BLOG_PATH . $image);
            @unlink(UPLOAD_THUMB_BLOG_PATH . $image);
            @unlink(UPLOAD_SMALL_BLOG_PATH . $image);
            $this->Session->setFlash('Image deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'blogs', 'action' => 'editblogs', $blogSlug));
        }
    }

    public function index() {

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . __d('controller', 'Blog', true));
        $this->layout = "client";
        $this->set('blogs', 'active');

        $condition = array();
        $separator = array();
        $urlSeparator = array();

        $condition[] = " (Blog.status ='1') ";
        $order = " Blog.id DESC";

        $urlSeparator = implode("/", $urlSeparator);
        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);

        $this->paginate['Blog'] = array('conditions' => $condition, 'limit' => '10', 'page' => '1', 'order' => $order);
        $this->set('blogList', $this->paginate('Blog'));
        //$this->set('slug', $slug);

        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'blogs';
            $this->render('index');
        }

        // $blogList = $this->Blog->find('all', array('conditions' => array('Blog.status' => '1'), 'order' => 'Blog.created desc'));
        // $this->set('blogList', $blogList);
    }

    public function detail($slug = null) {
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . __d('controller', 'Blog Detail', true));
        $this->layout = "client";
        $this->set('blogs', 'active');

        if ($slug) {
            $blogSlug = explode('.', $slug);
        }

        $blogData = $this->Blog->find('first', array('conditions' => array('Blog.slug' => $blogSlug[0])));
        //echo"<pre>"; pr($blogData); exit;
        $this->set('blogData', $blogData);
    }

    public function apps_getBlogsList() {
//        Configure::write('debug', 2);
        $this->layout = "";

        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . __d('controller', 'Blog', true));

        $condition = array();
        $separator = array();
        $urlSeparator = array();

        $condition[] = " (Blog.status ='1') ";
        $order = " Blog.id DESC";

        $urlSeparator = implode("/", $urlSeparator);
        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);

        $this->paginate['Blog'] = array('conditions' => $condition, 'limit' => '9999', 'page' => '1', 'order' => $order);
        $this->set('blogList', $this->paginate('Blog'));
//        $this->render('get_blogs_list');
//        exit;
    }

    public function apps_getBlogsdetail($slug = null) {
//         Configure::write('debug', 2);
        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set("title_for_layout", $title_for_pages . __d('controller', 'Blog Detail', true));
        $this->layout = "";

        if ($slug) {
            $blogSlug = explode('.', $slug);
        }

        $blogData = $this->Blog->find('first', array('conditions' => array('Blog.slug' => $blogSlug[0])));
        //echo"<pre>"; pr($blogData); exit;
        $this->set('blogData', $blogData);
    }

}

?>
