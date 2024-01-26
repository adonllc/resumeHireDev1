<?php

class JobsController extends AppController {

    public $uses = array('Job', 'Admin', 'Experience', 'Emailtemplate', 'User', 'Certificate', 'Country', 'State', 'City', 'PromoCode', 'JobApply', 'ShortList', 'Payment', 'JobNotification', 'Swear', 'PostCode', 'CoverLetter', 'Skill', 'Location', 'Education', 'Course', 'AlertLocation', 'Alert', 'AlertJob', 'Designation', 'AutoAlert', 'AutoJob', 'Plan', 'Keyword', 'Feed');
    public $helpers = array('Html', 'Form', 'Fck', 'Javascript', 'Ajax', 'Text', 'Number', 'Js', 'Pdf');
    public $paginate = array('limit' => '20', 'page' => '1', 'order' => array('User.id' => 'DESC'));
    public $components = array('RequestHandler', 'Email', 'Upload', 'PImageTest', 'PImage', 'Captcha', 'Common');
    public $layout = 'admin';

    function beforeFilter() {
        $loggedAdminId = $this->Session->read("adminid");
        if (isset($this->params['admin']) && $this->params['admin'] && !$loggedAdminId) {
            //$this->redirect("/admin/admins/login");
            $returnUrlAdmin = $this->params->url;
            $this->Session->write("returnUrlAdmin", $returnUrlAdmin);
            $this->redirect(array('controller' => 'admins', 'action' => 'login', ''));
        }
    }

    public function admin_index($slug = null) {

        $this->layout = "admin";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Jobs List");

        $this->set('jobs_list', 'active');

        if ($slug != '') {

            $user_id = $this->User->field('id', array('User.slug' => $slug));

            //echo $user_id; die;

            if ($user_id) {
                $condition = array('Job.user_id' => $user_id);
            } else {
                $this->redirect(array('controller' => 'jobs', 'action' => 'index'));
            }
        } else {
            $condition = array();
        }

        $separator = array($slug);
        $urlSeparator = array();
        $userName = '';
        $searchByDateFrom = '';
        $searchByDateTo = '';

        if (!empty($this->data)) {

            if (isset($this->data['Job']['userName']) && $this->data['Job']['userName'] != '') {
                $userName = trim($this->data['Job']['userName']);
            }

            if (isset($this->data['Job']['searchByDateFrom']) && $this->data['Job']['searchByDateFrom'] != '') {
                $searchByDateFrom = trim($this->data['Job']['searchByDateFrom']);
            }

            if (isset($this->data['Job']['searchByDateTo']) && $this->data['Job']['searchByDateTo'] != '') {
                $searchByDateTo = trim($this->data['Job']['searchByDateTo']);
            }

            if (isset($this->data['Job']['action'])) {
                $idList = $this->data['Job']['idList'];
                // pr($idList); die;
                if ($idList) {
                    if ($this->data['Job']['action'] == "activate") {
                        $cnd = array("Job.id IN ($idList) ");
                        $this->Job->updateAll(array('Job.status' => "'1'"), $cnd);
                    } elseif ($this->data['Job']['action'] == "deactivate") {
                        $cnd = array("Job.id IN ($idList) ");
                        $this->Job->updateAll(array('Job.status' => "'0'"), $cnd);
                    } elseif ($this->data['Job']['action'] == "delete") {
                        $cnd = array("Job.id IN ($idList) ");
                        $this->Job->deleteAll($cnd);
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
            $condition[] = " (`Job`.`title` LIKE '%" . addslashes($userName) . "%' OR `User`.`first_name` LIKE '%" . addslashes($userName) . "%' or concat(`User.first_name`,' ',`User.last_name`) LIKE '%" . addslashes($userName) . "%'  or `User`.`last_name` LIKE '%" . addslashes($userName) . "%' or `Job`.`company_name` LIKE '%" . addslashes($userName) . "%') ";
            $userName = str_replace('\_', '_', $userName);
            $this->set('searchKey', $userName);
        }

        if (isset($searchByDateFrom) && $searchByDateFrom != '') {
            $separator[] = 'searchByDateFrom:' . urlencode($searchByDateFrom);
            $searchByDateFrom = str_replace('_', '\_', $searchByDateFrom);
            $searchByDate_con1 = date('Y-m-d', strtotime($searchByDateFrom));
            $condition[] = " (Date(Job.created)>='$searchByDate_con1' ) ";
            $searchByDateFrom = str_replace('\_', '_', $searchByDateFrom);
        }

        if (isset($searchByDateTo) && $searchByDateTo != '') {
            $separator[] = 'searchByDateTo:' . urlencode($searchByDateTo);
            $searchByDateTo = str_replace('_', '\_', $searchByDateTo);
            $searchByDate_con2 = date('Y-m-d', strtotime($searchByDateTo));
            $condition[] = " (Date(Job.created)<='$searchByDate_con2' ) ";
            $searchByDateTo = str_replace('\_', '_', $searchByDateTo);
        }

        $order = 'Job.id Desc';

        $separator = implode("/", $separator);


        $this->set('searchByDateFrom', $searchByDateFrom);
        $this->set('searchByDateTo', $searchByDateTo);


        $urlSeparator = implode("/", $urlSeparator);
        $this->set('userName', $userName);
        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->set('slug', $slug);
        $this->paginate['Job'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('jobs', $this->paginate('Job'));
        //pr($condition);


        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/jobs';
            $this->render('index');
        }
    }

    public function admin_candidates($slug = null) {
        $this->layout = "admin";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Jobseeker list");
        $this->set('jobs_list', 'active');
        $this->set('jobsActive', 'active');

        $jobInfo = $this->Job->findBySlug($slug);
        if (empty($jobInfo)) {
            $this->redirect('/admin/jobs/index/');
        }
        $this->set('jobInfo', $jobInfo);
        $this->set('slug', $slug);

        $condition = array('JobApply.status' => 1, 'JobApply.job_id' => $jobInfo['Job']['id'], 'User.id !=' => '');

        $separator = array($slug);
        $urlSeparator = array();


        if (!empty($this->data)) {

            if (isset($this->data['JobApply']['keyword']) && $this->data['JobApply']['keyword'] != '') {
                $keyword = trim($this->data['JobApply']['keyword']);
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['keyword']) && $this->params['named']['keyword'] != '') {
                $keyword = urldecode(trim($this->params['named']['keyword']));
            }
        }

        if (isset($keyword) && $keyword != '') {
            $separator[] = 'keyword:' . urlencode($keyword);
            $condition[] = " (`JobApply`.`apply_status` LIKE '%" . addslashes($keyword) . "%' OR `User`.`first_name` LIKE '%" . addslashes($keyword) . "%' or concat(`User.first_name`,' ',`User.last_name`) LIKE '%" . addslashes($keyword) . "%' or `User`.`last_name` LIKE '%" . addslashes($keyword) . "%' or `User`.`email_address` LIKE '%" . addslashes($keyword) . "%'  ) ";
        }


        $order = 'JobApply.id Desc';

        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['JobApply'] = array('conditions' => $condition, 'limit' => '10', 'page' => '1', 'order' => $order);
        $this->set('candidates', $this->paginate('JobApply'));
        //pr($condition);exit;


        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/jobs';
            $this->render('candidates');
        }
    }

    public function admin_editjob($slug = null) {

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit Job");

        $this->layout = "admin";

        $this->set('jobs_list', 'active');
        $msgString = '';
        global $extentions;

        $jobInfo = $this->Job->findBySlug($slug);
        $this->set('jobInfo', $jobInfo);


        $categories = $this->Category->getCategoryList();
        $this->set('categories', $categories);
        $subcategories = array();
        $this->set('subcategories', $subcategories);

        // get skills from skill table
        $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('skillList', $skillList);

        // get designations from skill table
        $designationlList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Designation', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('designationlList', $designationlList);

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        if ($this->data) {

            if (empty($this->data["Job"]["title"])) {
                $msgString .= "- Job title is required field.<br>";
            }

            if (empty($this->data["Job"]["category_id"])) {
                $msgString .= "- Category is required field.<br>";
            }
            if (empty($this->data["Job"]["subcategory_id"])) {
                // $msgString .="- Sub Category is required field.<br>";
            }
            if (empty($this->data["Job"]["description"])) {
                $msgString .= "- Job description is required field.<br>";
            }
            if (empty($this->data["Job"]["company_name"])) {
                $msgString .= "- Company name is required field.<br>";
            }
            if (empty($this->data["Job"]["brief_abtcomp"])) {
                $msgString .= "- Company profile required field.<br>";
            }
            if (empty($this->data["Job"]["work_type"])) {
                $msgString .= "- Work type is required field.<br>";
            }
            if (empty($this->data["Job"]["contact_name"])) {
                $msgString .= "- Contact name is required field.<br>";
            }
            if (empty($this->data["Job"]["contact_number"])) {
                $msgString .= "- Contact number is required field.<br>";
            }


            /*   if (empty($this->data["Job"]["state_id"])) {
              $msgString .="- State is required field.<br>";
              }
              if (empty($this->data["Job"]["city_id"])) {
              $msgString .="- City is required field.<br>";
              } */

            if ($this->data["Job"]["exp"] == '') {
                $msgString .= "- Experience year is required field.<br>";
            }

            if ($this->data["Job"]["salary"] == '') {
                $msgString .= "- Annual Salary is required field.<br>";
            }


            if ($this->data['Job']['skill']) {
                
            } else if (empty($this->data['Job']['skill'])) {
                $msgString .= "- Skill is required field.<br>";
            } else {
                $this->request->data['Job']['skill'] = '';
            }

            if (empty($this->data["Job"]["designation"])) {
                $msgString .= "- Designation is required field.<br>";
            }
            /* if (empty($this->data["Job"]["location"])) {
              $msgString .="- Location is required field.<br>";
              } */

            $youtube_link = '';
            if ($this->data["Job"]["youtube_link"]) {
                $url = $this->data['Job']['youtube_link'];
                $urlArray = explode('watch?v=', $url);
                if (!$urlArray[1]) {
                    $msgString .= "- Please enter valid video URL.<br>";
                }
                $youtube_link = $urlArray[1];
            }

            if ($this->data["Job"]["logo"]["name"]) {
                $getextention = $this->PImage->getExtension($this->data['Job']['logo']['name']);
                $extention = strtolower($getextention);
                if ($this->data['Job']['logo']['size'] > '2097152') {
                    $msgString .= "- Max file size upload is 2MB.<br>";
                } elseif (!in_array($extention, $extentions)) {
                    $msgString .= "- Not Valid Extention.<br>";
                }
            }

            $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
            $toReplace = "-";
            $this->request->data['Job']['logo']['name'] = str_replace($specialCharacters, $toReplace, $this->data['Job']['logo']['name']);
            $this->request->data['Job']['logo']['name'] = str_replace("&", "and", $this->data['Job']['logo']['name']);
            if (!empty($this->data['Job']['logo']['name'])) {


                $imageArray = $this->data['Job']['logo'];


                $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_JOB_LOGO_PATH, "jpg,jpeg,png,gif");

                list($width, $height, $type, $attr) = getimagesize(UPLOAD_JOB_LOGO_PATH . '/' . $returnedUploadImageArray[0]);

                if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {

                    $msgString .= "- Logo file not valid.<br>";
                } else if ($width < 250 && $height < 250) {

                    @unlink(UPLOAD_JOB_LOGO_PATH . '/' . $returnedUploadImageArray[0]);
                    $msgString .= "- Logo size must be bigger than  250 X 250 pixels.<br>";
                } else {
                    //  copy(UPLOAD_FULL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0]);
                    //  copy(UPLOAD_FULL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0]);
                    list($width) = getimagesize(UPLOAD_JOB_LOGO_PATH . $returnedUploadImageArray[0]);
                    if ($width > 650) {
                        $this->PImageTest->resize(UPLOAD_JOB_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_JOB_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_JOB_LOGO_WIDTH, UPLOAD_JOB_LOGO_HEIGHT, 100);
                    }
                    chmod(UPLOAD_JOB_LOGO_PATH . $returnedUploadImageArray[0], 0755);
                    // $this->PImageTest->resize(UPLOAD_THUMB_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_THUMB_WEBSITE_LOGO_WIDTH, UPLOAD_THUMB_WEBSITE_LOGO_HEIGHT, 100);
                    //  $this->PImageTest->resize(UPLOAD_SMALL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_WEBSITE_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_SMALL_WEBSITE_LOGO_WIDTH, UPLOAD_SMALL_WEBSITE_LOGO_HEIGHT, 100);
                    $this->request->data["Job"]["logo"] = $returnedUploadImageArray[0];

                    //$this->request->data["Job"]["logo"] = $returnedUploadImageArray[0];
                }
            } else {
                $this->request->data["Job"]["logo"] = '';
            }


            if (empty($this->data["Job"]["created"])) {
                $msgString .= "- Job posted date and time is required field.<br>";
            } else {
                if (strtotime($this->data["Job"]["created"]) > time()) {
                    $msgString .= "- Job posted date and time is not valid field.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {

                $this->Session->setFlash($msgString, 'error_msg');

                $subcategories = $this->Category->getSubCategoryList($this->data['Job']['category_id']);
                $this->set('subcategories', $subcategories);

                /* $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['Job']['postal_code'])));

                  $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
                  $this->set('stateList', $stateList);

                  if ($postCodeDetails['PostCode']['id']) {
                  $cityList = $this->getCityList($postCodeDetails['PostCode']['id']);
                  } else {
                  $cityList = '';
                  }
                  $this->set('cityList', $cityList); */
            } else {

                /*   if ($this->data["Job"]["logo"]["name"]) {
                  $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                  $toReplace = "-";
                  $this->request->data["Job"]["logo"]['name'] = str_replace($specialCharacters, $toReplace, $this->data["Job"]["logo"]['name']);
                  $this->request->data["Job"]["logo"]['name'] = str_replace("&", "and", $this->data["Job"]["logo"]['name']);
                  $cvArray = $this->data["Job"]["logo"];
                  $returnedUploadCVArray = $this->PImage->upload($cvArray, UPLOAD_JOB_LOGO_PATH);
                  $this->request->data["Job"]["logo"] = $returnedUploadCVArray[0];
                  } else {

                  $this->request->data['Job']['logo'] = $this->data['Job']['old_logo'];
                  }
                 */

                $this->request->data['Job']['subcategory_id'] = implode(',', $this->data['Job']['subcategory_id']);


                $this->request->data['Job']['youtube_link'] = $youtube_link;

                if (empty($this->data["Job"]["exp_month"])) {
                    $this->request->data['Job']['exp_month'] = 0;
                }

                $this->request->data['Job']['skill'] = implode(',', $this->data['Job']['skill']);
                $exp = explode('-', $this->data['Job']['exp']);
                $this->request->data["Job"]["min_exp"] = $exp[0];
                $this->request->data["Job"]["max_exp"] = $exp[1];

                $sallery = explode('-', $this->data['Job']['salary']);
                $this->request->data["Job"]["min_salary"] = $sallery[0];
                $this->request->data["Job"]["max_salary"] = $sallery[1];
                $this->request->data['Job']['expire_time'] = strtotime($this->data['Job']['expire_time']);

                if ($this->Job->save($this->data)) {
                    $jobDetail = $this->Job->findBySlug($slug);
                    $site_title = $this->getSiteConstant('title');
                    $mail_from = $this->getMailConstant('from');

                    $title = $jobDetail['Job']['title'];
                    $category = $jobDetail['Category']['name'];
                    $skill = $jobDetail['Skill']['name'];
                    $location = $jobDetail['Job']['job_city'];
                    $minExp = $jobDetail['Job']['min_exp'] . ' Year';
                    $maxExp = $jobDetail['Job']['max_exp'] . ' Year';
                    $min_salary = CURRENCY . ' ' . intval($jobDetail['Job']['min_salary']);
                    $max_salary = CURRENCY . ' ' . intval($jobDetail['Job']['max_salary']);
                    $description = $jobDetail['Job']['description'];
                    $company_name = $jobDetail['Job']['company_name'];
                    $contact_number = $jobDetail['Job']['contact_number'];
                    $website = $jobDetail['Job']['url'] ? $jobDetail['Job']['url'] : 'N/A';
                    $address = $jobDetail['Job']['address'] ? $jobDetail['Job']['address'] : 'N/A';

                    $designation = $this->Skill->field('name', array(
                        'Skill.status' => 1,
                        'Skill.type' => 'Designation',
                        'Skill.id' => $jobDetail['Job']['designation'],
                    ));
                    $username = $jobDetail['User']['first_name'] . ' ' . $jobDetail['User']['last_name'];
                    $this->Email->to = $jobDetail['User']['email_address'];
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='48'"));

                    $toSubArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                    $fromSubArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                    $this->Email->subject = $subjectToSend;


                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";
                    $toRepArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                    $fromRepArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();

                    $this->Session->setFlash('Job details updated successfully', 'success_msg');
                    $this->redirect('/admin/jobs/index');
                }
            }
        } elseif ($slug != '') {
            $id = $this->Job->field('id', array('Job.slug' => $slug));
            $this->Job->id = $id;
            $this->data = $this->Job->read();

            $subcategories = $this->Category->getSubCategoryList($this->data['Job']['category_id']);
            $this->set('subcategories', $subcategories);

            $this->request->data['Job']['created'] = date('Y-m-d H:i', strtotime($this->data['Job']['created']));
            $this->request->data['Job']['old_logo'] = $this->data['Job']['logo'];
            $this->request->data['Job']['old_title'] = $this->data['Job']['title'];
            $this->request->data['Job']['subcategory_id'] = explode(',', $this->data['Job']['subcategory_id']);


            // get skills from skill table
            $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
            $this->set('skillList', $skillList);
            $this->request->data['Job']['skill'] = explode(',', $this->data['Job']['skill']);
            $this->request->data['Job']['expire_time'] = date('Y-m-d', $this->data['Job']['expire_time']);

            if ($this->data['Job']['youtube_link']) {
                $this->request->data['Job']['youtube_link'] = 'https://www.youtube.com/watch?v=' . $this->data['Job']['youtube_link'];
            }
            $this->request->data['Job']['exp'] = $this->data['Job']['min_exp'] . '-' . $this->data['Job']['max_exp'];
            $this->request->data['Job']['salary'] = $this->data['Job']['min_salary'] . '-' . $this->data['Job']['max_salary'];
            //pr($this->data);exit;
            /*   $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['Job']['postal_code'])));
              $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
              $this->set('stateList', $stateList);
              $cityList = $this->getCityList($postCodeDetails['PostCode']['id']);
              $this->set('cityList', $cityList); */
        }
    }

    function admin_getCityList($post_id = null) {

        $citys = $this->City->find('list', array(
            'conditions' => array(
                'City.status' => '1',
                'City.post_code_id' => $post_id
            ),
            'fields' => array(
                'City.id',
                'City.city_name'
            ),
            'order' => 'City.city_name asc')
        );

        return $citys;
    }

    public function admin_activatejob($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Job->field('id', array('Job.slug' => $slug));
            $cnd = array("Job.id = $id");
            $this->Job->updateAll(array('Job.status' => "'1'"), $cnd);
            $this->set('action', '/admin/jobs/deactivatejob/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivatejob($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Job->field('id', array('Job.slug' => $slug));
            $cnd = array("Job.id = $id");
            $this->Job->updateAll(array('Job.status' => "'0'"), $cnd);
            $this->set('action', '/admin/jobs/activatejob/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deletejobs($slug = NULL) {
        $this->set('list_users', 'active');
        $this->set('default', '1');
        if ($slug != '') {
            $id = $this->Job->field('id', array('Job.slug' => $slug));
            $image = $this->Job->field('logo', array('Job.slug' => $slug));
            if ($this->Job->delete($id)) {
                if (isset($jobDetail['User']) && $jobDetail['User']['email_address']) {
                    $jobDetail = $this->Job->findBySlug($slug);
                    $site_title = $this->getSiteConstant('title');
                    $mail_from = $this->getMailConstant('from');

                    $title = $jobDetail['Job']['title'];
                    $category = $jobDetail['Category']['name'];
                    $location = $jobDetail['Job']['job_city'];

                    $username = $jobDetail['User']['first_name'] . ' ' . $jobDetail['User']['last_name'];
                    $this->Email->to = $jobDetail['User']['email_address'];
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='47'"));

                    $toSubArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                    $fromSubArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                    $this->Email->subject = $subjectToSend;


                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";
                    $toRepArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!SITE_TITLE!]');
                    $fromRepArray = array($username, $title, $category, $location, $site_title);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                }
                @unlink(UPLOAD_JOB_LOGO_PATH . $image);
            }
            $this->Session->setFlash('Job details deleted successfully', 'success_msg');
        }
        $this->redirect('/admin/jobs/index');
    }

    public function admin_applied($slug = null) {
        $this->layout = "admin";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Short Listed Jobs');

        $this->set('add_candidate', 'active');
        $candidateInfo = $this->User->findBySlug($slug);
        if (!$candidateInfo) {
            $this->redirect('/admin/candidates/index/');
        }
        $this->set('candidateInfo', $candidateInfo);

        $condition = array('JobApply.user_id' => $candidateInfo['User']['id'], 'Job.status' => 1);
        $separator = array($slug);
        $urlSeparator = array();

        $order = 'JobApply.id Desc';

        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['JobApply'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('jobs', $this->paginate('JobApply'));

        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/jobs';
            $this->render('applied');
        }
    }

    public function selectType() {
        $this->layout = "client";
        $this->set('jobsCreate', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Select Job Type', true));
        $this->userLoginCheck();
        $this->recruiterAccess();
        //unset session when user back from create job
        unset($_SESSION['type']);

        $planInfo = $this->Admin->findByid(1);
        $this->set('planInfo', $planInfo);
        if ($this->data) {

            unset($_SESSION['dis_amount']);
            unset($_SESSION['promo_code']);

            if ($this->data['type'] == 'bronze') {
                $amount = $planInfo['Admin']['bronze'];
            } elseif ($this->data['type'] == 'silver') {
                $amount = $planInfo['Admin']['silver'];
            } else {
                $amount = $planInfo['Admin']['gold'];
            }

            $_SESSION['type'] = $this->data['type'];
            $_SESSION['amount'] = $amount;
            $this->redirect(array('controller' => 'jobs', 'action' => 'createJob'));
        }
    }

    public function copyJob($slug = null) {
        $this->layout = "";
        $this->userLoginCheck();
        $this->recruiterAccess();

        $planInfo = $this->Admin->findByid(1);
        $this->set('planInfo', $planInfo);

        $jobInfo = $this->Job->findByslug($slug);
        //echo"<pre>"; print_r($jobInfo); exit;


        unset($_SESSION['dis_amount']);
        unset($_SESSION['promo_code']);


        if ($jobInfo['Job']['type'] == 'bronze') {
            $amount = $planInfo['Admin']['bronze'];
        } elseif ($jobInfo['Job']['type'] == 'silver') {
            $amount = $planInfo['Admin']['silver'];
        } else {
            $amount = $planInfo['Admin']['gold'];
        }

        $_SESSION['type'] = $jobInfo['Job']['type'];
        $_SESSION['amount'] = $amount;
        $_SESSION['copy_data'] = $jobInfo;
        $this->redirect(array('controller' => 'jobs', 'action' => 'createJob', 'copy'));
    }

    public function beforeCreateJob() {
        $this->layout = "";
        $this->userLoginCheck();
        $this->recruiterAccess();

        unset($_SESSION['dis_amount']);
        unset($_SESSION['promo_code']);
        unset($_SESSION['type']);
        unset($_SESSION['amount']);
        unset($_SESSION['copy_data']);
        $this->redirect(array('controller' => 'jobs', 'action' => 'selectType'));
    }

    public function createJob($isCopy = null) {

//        Configure::write('debug', 2);

        $this->layout = "client";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Create Job', true));

        $this->set('jobsCreate', 'active');
        $this->set('isCopy', $isCopy);
        $this->userLoginCheck();
        $this->recruiterAccess();
        global $extentions;

//        echo "<pre>";
//        print_r($_SESSION);
//        exit;

        $userId = $this->Session->read('user_id');

        $isAbleToJob = $this->Plan->checkPlanFeature($userId, 1);
        if ($isAbleToJob['status'] == 0) {
            $this->Session->write('error_msg', $isAbleToJob['message']);
            $this->redirect('/jobs/management');
        }

        $logo_status = $this->User->field('profile_image', array('User.id' => $userId));
        $this->set('logo_status', $logo_status);

        $categories = $this->Category->getCategoryList();
        $this->set('categories', $categories);
        $subcategories = array();
        $this->set('subcategories', $subcategories);

        /* $stateList = array();
          $this->set('stateList', $stateList);
          $cityList = array();
          $this->set('cityList', $cityList); */
        $msgString = '';
        // get skills from skill table
        $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('skillList', $skillList);

        // get designations from skill table
        $designationlList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Designation', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('designationlList', $designationlList);

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

//        if ($_SESSION['type'] == '') {
//            $this->redirect('/jobs/selectType');
//        }

        $_SESSION['type'] = 'Gold';
        $_SESSION['amount'] = '180.00';

        if ($this->data) {
            //echo "<pre>"; print_r($this->data);exit;  

            if (!isset($_SESSION['type']) && $_SESSION['type'] == '') {
                $msgString .= __d('controller', 'Select Job Type', true) . "<br>";
            } else {
                $this->request->data["Job"]["type"] = $_SESSION['type'];
            }
            if (empty($this->data["Job"]["title"])) {
                $msgString .= __d('controller', 'Job title is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["title"]);
            }

            if (empty($this->data["Job"]["category_id"])) {
                $msgString .= __d('controller', 'Category is required field.', true) . "<br>";
            }

            if (empty($this->data["Job"]["description"])) {
                $msgString .= __d('controller', 'Job description is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["description"]);
            }

            if (empty($this->data["Job"]["company_name"])) {
                $msgString .= __d('controller', 'Company name is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["company_name"]);
            }

            /* if (empty($this->data["Job"]["price"])) {
              $msgString .="- Wage Package  is required field.<br>";
              }

              if ($this->data["Job"]["price_status"] == '') {
              $msgString .="- Wage Package Status  is required field.<br>";
              } */

            if (empty($this->data["Job"]["work_type"])) {
                $msgString .= __d('controller', 'Work type is required field.', true) . "<br>";
            }

            if (empty($this->data["Job"]["contact_name"])) {
                $msgString .= __d('controller', 'Contact name is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["contact_name"]);
            }

            if (empty($this->data["Job"]["contact_number"])) {
                $msgString .= __d('controller', 'Contact number is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["contact_number"]);
            }


            if (empty($this->data["Job"]["brief_abtcomp"])) {
                $msgString .= __d('controller', 'Company profile is required field.', true) . "<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["brief_abtcomp"]);
            }


            /* if (empty($this->data["Job"]["postal_code"])) {
              $msgString .="- Postal Code is required field.<br>";
              } else {
              $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["postal_code"]);
              }

              if (empty($this->data["Job"]["state_id"])) {
              $msgString .="- State is required field.<br>";
              }
              if (empty($this->data["Job"]["city_id"])) {
              $msgString .="- City is required field.<br>";
              } */

            //if (trim($this->data["Job"]["youtube_link"]) != '') {
            //$msgString .= $this->Swear->checkSwearWord($this->data["Job"]["youtube_link"]);
            //}
            //if (empty($this->data["Job"]["exp_year"])) {
//            if ($this->data["Job"]["exp_year"]=='') {
//                $msgString .="- Experience year is required field.<br>";
//            }

            if ($this->data["Job"]["exp"] == '') {
                $msgString .= __d('controller', 'Experience is required field.', true) . "<br>";
            }
//            if ($this->data["Job"]["max_exp"] == '') {
//                $msgString .="- Max Experience year is required field.<br>";
//            }
//            if ($this->data["Job"]["min_salary"] == '') {
//                $msgString .="- Min Annual Salary is required field.<br>";
//            }
            if ($this->data["Job"]["salary"] == '') {
                $msgString .= __d('controller', 'Annual Salary is required field.', true) . "<br>";
            }

            if ($this->data['Job']['skill']) {
                
            } else if (empty($this->data['Job']['skill'])) {
                $msgString .= __d('controller', 'Skill is required field.', true) . "<br>";
            } else {
                $this->request->data['Job']['skill'] = '';
            }

            if (empty($this->data["Job"]["designation"])) {
                $msgString .= __d('controller', 'Designation is required field.', true) . "<br>";
            }

//            if (empty($this->data["Job"]["location"])) {
//                $msgString .="- Location is required field.<br>";
//            }


            $youtube_link = '';

//            if (isset($this->data["Job"]["selling_point1"]) && trim($this->data["Job"]["selling_point1"]) != '') {
//                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["selling_point1"]);
//            }
//            if (isset($this->data["Job"]["selling_point2"]) && trim($this->data["Job"]["selling_point2"]) != '') {
//                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["selling_point2"]);
//            }
//            if (isset($this->data["Job"]["selling_point3"]) && trim($this->data["Job"]["selling_point3"]) != '') {
//                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["selling_point3"]);
//            }


            if (!empty($this->data["Job"]["logo"]["name"])) {
                $getextention = $this->PImage->getExtension($this->data['Job']['logo']['name']);
                $extention = strtolower($getextention);
                if ($this->data['Job']['logo']['size'] > '2097152') {
                    $msgString .= __d('controller', 'Max file size upload is 2MB.', true) . "<br>";
                } elseif (!in_array($extention, $extentions)) {
                    $msgString .= __d('controller', 'Not Valid Extention.', true) . "<br>";
                }
            } else {
                $this->data["Job"]["logo"]["name"] = '';
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);
                $subcategories = $this->Category->getSubCategoryList($this->data['Job']['category_id']);
                $this->set('subcategories', $subcategories);

                /* $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['Job']['postal_code'])));
                  $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
                  $this->set('stateList', $stateList);
                  $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
                  $this->set('cityList', $cityList); */
            } else {

                $keyword = trim($this->data['Job']['title']);
                $keywordId = $this->Keyword->field('id', array('Keyword.name' => $keyword, 'Keyword.type' => 'Job'));
                if (!$keywordId) {
                    $this->request->data['Keyword']['name'] = $keyword;
                    $this->request->data['Keyword']['slug'] = $this->stringToSlugUnique($keyword, 'Keyword');
                    $this->request->data['Keyword']['status'] = '1';
                    $this->request->data['Keyword']['approval_status'] = '0';
                    $this->request->data['Keyword']['type'] = 'Job';
                    $this->Keyword->save($this->data);
                }

                $exp = explode('-', $this->data['Job']['exp']);
                $this->request->data["Job"]["min_exp"] = $exp[0];
                $this->request->data["Job"]["max_exp"] = $exp[1];

                $sallery = explode('-', $this->data['Job']['salary']);
                $this->request->data["Job"]["min_salary"] = $sallery[0];
                $this->request->data["Job"]["max_salary"] = $sallery[1];

                if (!empty($this->data["Job"]["logo"]["name"])) {
                    $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                    $toReplace = "-";
                    $this->request->data["Job"]["logo"]['name'] = str_replace($specialCharacters, $toReplace, $this->data["Job"]["logo"]['name']);
                    $this->request->data["Job"]["logo"]['name'] = str_replace("&", "and", $this->data["Job"]["logo"]['name']);
                    if ($this->data["Job"]["logo"]['name']) {
                        $cvArray = $this->data["Job"]["logo"];
                        $returnedUploadCVArray = $this->PImage->upload($cvArray, UPLOAD_JOB_LOGO_PATH);
                        $this->request->data["Job"]["logo"] = $returnedUploadCVArray[0];
                        chmod(UPLOAD_JOB_LOGO_PATH . $returnedUploadImageArray[0], 0755);
                    }
                } else {
                    $this->request->data["Job"]["logo"] = '';
                }

                if ($this->data['Job']['subcategory_id']) {
                    $this->request->data['Job']['subcategory_id'] = implode(',', $this->data['Job']['subcategory_id']);
                } else {
                    $this->request->data['Job']['subcategory_id'] = 0;
                }
//                if ($this->data["Job"]["youtube_link"]) {
//                    $url = $this->data['Job']['youtube_link'];
//                    $urlArray = explode('watch?v=', $url);
//                    $this->request->data["Job"]["youtube_link"] = $urlArray[1];
//                }
                $slug = $this->stringToSlugUnique($this->data["Job"]["title"], 'Job');
                $this->request->data['Job']['slug'] = $slug;
                $this->request->data['Job']['type'] = $_SESSION['type'];
                $this->request->data['Job']['status'] = 1;
                $this->request->data['Job']['user_id'] = $userId;
                /* --2016-05-06-- */
                //  $this->request->data['Job']['payment_status'] = '0';
                /* --2016-05-06-- */
                $this->request->data['Job']['payment_status'] = 2;
                $this->request->data['Job']['amount_paid'] = $_SESSION['amount'];
                $this->request->data['Job']['job_number'] = 'JOB' . $userId . time();
                if (empty($this->data["Job"]["exp_month"])) {
                    $this->request->data['Job']['exp_month'] = 0;
                }


                if ($_SESSION['type'] == 'gold') {
                    $this->request->data['Job']['hot_job_time'] = time() + 7 * 24 * 3600;
                } else {
                    $this->request->data['Job']['hot_job_time'] = time();
                }
                $this->request->data['Job']['expire_time'] = strtotime($this->data['Job']['expire_time']);

                $this->request->data['Job']['skill'] = implode(',', $this->data['Job']['skill']);

                $this->request->data['Job']['user_plan_id'] = $isAbleToJob['user_plan_id'];


                if ($_SESSION['amount'] > 0) {
                    if ($this->Job->save($this->data)) {
                        $jobId = $this->Job->id;
                        $jobDetail = $this->Job->findById($jobId);
                        $site_title = $this->getSiteConstant('title');
                        $mail_from = $this->getMailConstant('from');

                        $title = $jobDetail['Job']['title'];
                        $category = $jobDetail['Category']['name'];
                        $skill = $jobDetail['Skill']['name'];
                        $location = $jobDetail['Job']['job_city'];
                        $minExp = $jobDetail['Job']['min_exp'] . ' Year';
                        $maxExp = $jobDetail['Job']['max_exp'] . ' Year';
                        $min_salary = CURRENCY . ' ' . intval($jobDetail['Job']['min_salary']);
                        $max_salary = CURRENCY . ' ' . intval($jobDetail['Job']['max_salary']);
                        $description = $jobDetail['Job']['description'];
                        $company_name = $jobDetail['Job']['company_name'];
                        $contact_number = $jobDetail['Job']['contact_number'];
                        $website = $jobDetail['Job']['url'] ? $jobDetail['Job']['url'] : 'N/A';
                        $address = $jobDetail['Job']['address'] ? $jobDetail['Job']['address'] : 'N/A';

                        $designation = $this->Skill->field('name', array(
                            'Skill.status' => 1,
                            'Skill.type' => 'Designation',
                            'Skill.id' => $jobDetail['Job']['designation'],
                        ));
                        $username = $jobDetail['User']['first_name'] . ' ' . $jobDetail['User']['last_name'];
                        $this->Email->to = $jobDetail['User']['email_address'];
                        $currentYear = date('Y', time());
                        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='46'"));

                        $toSubArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                        $fromSubArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                        $this->Email->subject = $subjectToSend;


                        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                        $this->Email->from = $site_title . "<" . $mail_from . ">";
                        $toRepArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                        $fromRepArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                        $this->Email->layout = 'default';
                        $this->set('messageToSend', $messageToSend);
                        $this->Email->template = 'email_template';
                        $this->Email->sendAs = 'html';
                        $this->Email->send();
                        $this->Email->reset();

                        $username = "Admin";
                        $adminInfo = $this->Admin->findById(1);

                        $this->Email->to = $adminInfo['Admin']['email'];
                        $currentYear = date('Y', time());
                        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='46'"));

                        $toSubArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                        $fromSubArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                        $this->Email->subject = $subjectToSend;


                        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                        $this->Email->from = $site_title . "<" . $mail_from . ">";
                        $toRepArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                        $fromRepArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                        $this->Email->layout = 'default';
                        $this->set('messageToSend', $messageToSend);
                        $this->Email->template = 'email_template';
                        $this->Email->sendAs = 'html';
                        $this->Email->send();

                        $users = $this->AlertLocation->getUsersToAlert($jobId);
                        if (!empty($users)) {
                            foreach ($users as $user) {
//                                pr($user);
                                $this->AlertJob->create();
                                $this->request->data["AlertJob"]['job_id'] = $jobId;
                                $this->request->data["AlertJob"]['user_id'] = $user['User']['id'];
                                $this->request->data['AlertJob']['email_address'] = $user['User']['email_address'];
                                $this->request->data["AlertJob"]['status'] = 1;
                                $this->AlertJob->save($this->data);
                            }
                        }

//                        $Autousers = $this->AutoAlert->getUsersToAlert($jobId);
//                        if (!empty($Autousers)) {
//                            foreach ($Autousers as $user) {
//                                $this->AlertJob->create();
//                                $this->request->data["AutoJob"]['job_id'] = $jobId;
//                                $this->request->data['AutoJob']['email_address'] = $user['AutoAlert']['email_address'];
//                                $this->request->data["AutoJob"]['status'] = 1;
//                                $this->AutoJob->save($this->data);
//                            }
//                        }
                        // print_r($Autousers);exit;      

                        unset($_SESSION['data']);
                        unset($_SESSION['copy_data']);
                        unset($_SESSION['type']);
                        unset($_SESSION['dis_amount']);
                        unset($_SESSION['promo_code']);

                        $this->Session->write('success_msg', __d('controller', 'Your job posted successfully.', true));
                        $this->redirect('/jobs/management');
                    }
                } else {


                    $this->request->data["Job"]['dis_amount'] = 0;
                    $this->request->data["Job"]['promo_code'] = '';
                    $this->request->data['Job']['status'] = 1;

                    if ($this->Job->save($this->data)) {
                        $jobId = $this->Job->id;
                        $jobDetail = $this->Job->findById($jobId);
                        $site_title = $this->getSiteConstant('title');
                        $mail_from = $this->getMailConstant('from');

                        $title = $jobDetail['Job']['title'];
                        $category = $jobDetail['Category']['name'];
                        $skill = $jobDetail['Skill']['name'];
                        $location = $jobDetail['Job']['job_city'];
                        $minExp = $jobDetail['Job']['min_exp'] . ' Year';
                        $maxExp = $jobDetail['Job']['max_exp'] . ' Year';
                        $min_salary = CURRENCY . ' ' . intval($jobDetail['Job']['min_salary']);
                        $max_salary = CURRENCY . ' ' . intval($jobDetail['Job']['max_salary']);
                        $description = $jobDetail['Job']['description'];
                        $company_name = $jobDetail['Job']['company_name'];
                        $contact_number = $jobDetail['Job']['contact_number'];
                        $website = $jobDetail['Job']['url'] ? $jobDetail['Job']['url'] : 'N/A';
                        $address = $jobDetail['Job']['address'] ? $jobDetail['Job']['address'] : 'N/A';

                        $designation = $this->Skill->field('name', array(
                            'Skill.status' => 1,
                            'Skill.type' => 'Designation',
                            'Skill.id' => $jobDetail['Job']['designation'],
                        ));
                        $username = $jobDetail['User']['first_name'] . ' ' . $jobDetail['User']['last_name'];
                        $this->Email->to = $jobDetail['User']['email_address'];
                        $currentYear = date('Y', time());
                        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='46'"));

                        $toSubArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                        $fromSubArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                        $this->Email->subject = $subjectToSend;


                        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                        $this->Email->from = $site_title . "<" . $mail_from . ">";
                        $toRepArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                        $fromRepArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                        $this->Email->layout = 'default';
                        $this->set('messageToSend', $messageToSend);
                        $this->Email->template = 'email_template';
                        $this->Email->sendAs = 'html';
                        $this->Email->send();
                        $this->Email->reset();

                        $username = "Admin";
                        $adminInfo = $this->Admin->findById(1);

                        $this->Email->to = $adminInfo['Admin']['email'];
                        $currentYear = date('Y', time());
                        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='46'"));

                        $toSubArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                        $fromSubArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                        $this->Email->subject = $subjectToSend;


                        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                        $this->Email->from = $site_title . "<" . $mail_from . ">";
                        $toRepArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                        $fromRepArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                        $this->Email->layout = 'default';
                        $this->set('messageToSend', $messageToSend);
                        $this->Email->template = 'email_template';
                        $this->Email->sendAs = 'html';
                        $this->Email->send();
                    }

                    unset($_SESSION['data']);
                    unset($_SESSION['copy_data']);
                    unset($_SESSION['type']);
                    unset($_SESSION['dis_amount']);
                    unset($_SESSION['promo_code']);

                    $this->Session->write('success_msg', __d('controller', 'Your job posted successfully.', true));
                    $this->redirect('/jobs/management');
                }
            }
        } else {

            // pr($_SESSION['copy_data']);exit;

            if (isset($_SESSION['data']) && $_SESSION['data'] != '') {
                $this->request->data["Job"] = $_SESSION['data'];

                $this->request->data['Job']['subcategory_id'] = explode(',', $this->data['Job']['subcategory_id']);

                /* $cityList = $this->City->find('list', array('conditions' => array('City.state_id' => $this->data['Job']['state_id'], 'City.status' => 1), 'fields' => array('City.id', 'City.city_name'), 'order' => 'City.city_name asc'));
                  $this->set('cityList', $cityList); */
                $subcategories = $this->Category->getSubCategoryList($this->data['Job']['category_id']);
                $this->set('subcategories', $subcategories);
                if ($this->data['Job']['youtube_link']) {
                    $this->request->data['Job']['youtube_link'] = 'https://www.youtube.com/watch?v=' . $this->data['Job']['youtube_link'];
                }

                /* $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['Job']['postal_code'])));
                  $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
                  $this->set('stateList', $stateList);
                  $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
                  $this->set('cityList', $cityList); */
            }

            if (isset($_SESSION['copy_data']) && $_SESSION['copy_data'] != '') {
                $this->data = $_SESSION['copy_data'];
//                echo '<pre>';
//                print_r($this->data);exit;


                $this->request->data['Job']['title'] = 'Copy of ' . $_SESSION['copy_data']['Job']['title'];
                $this->request->data['Job']['subcategory_id'] = explode(',', $this->data['Job']['subcategory_id']);
                $cityList = $this->City->find('list', array('conditions' => array('City.state_id' => $this->data['Job']['state_id'], 'City.status' => 1), 'fields' => array('City.id', 'City.city_name'), 'order' => 'City.city_name asc'));
                $this->set('cityList', $cityList);
                $subcategories = $this->Category->getSubCategoryList($this->data['Job']['category_id']);
                $this->set('subcategories', $subcategories);

                $this->request->data['Job']['salary'] = $this->data['Job']['min_salary'] . '-' . $this->data['Job']['max_salary'];
                $this->request->data['Job']['exp'] = $this->data['Job']['min_exp'] . '-' . $this->data['Job']['max_exp'];

                //pr($this->data);exit;
//                if ($this->data['Job']['youtube_link']) {
//                    $this->request->data['Job']['youtube_link'] = 'https://www.youtube.com/watch?v=' . $this->data['Job']['youtube_link'];
//                }
                $this->request->data['Job']['skill'] = explode(',', $this->data['Job']['skill']);
                /* $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['Job']['postal_code'])));
                  $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
                  $this->set('stateList', $stateList);
                  $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
                  $this->set('cityList', $cityList); */
            }
        }
    }

    public function payment() {
        $this->layout = "client";
        $this->set('jobsActive', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Payment Information');
        $this->userLoginCheck();
        $this->recruiterAccess();

        if (!$_SESSION['data']['title']) {
            $this->redirect("/payments/selectType/");
        }
    }

    public function addFreeJob() {
        $this->layout = "client";
        $this->set('jobsActive', 'active');

        $mail_from = $this->getMailConstant('from');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Select Plan');

        $this->userLoginCheck();
        $this->recruiterAccess();

        $this->request->data["Job"] = $_SESSION['data'];

        if (isset($_SESSION['dis_amount']) && $_SESSION['dis_amount'] != '') {
            $this->request->data["Job"]['dis_amount'] = $_SESSION['dis_amount'];
            $this->request->data["Job"]['promo_code'] = $_SESSION['promo_code'];
            $this->request->data["Job"]['payment_type'] = 1;
        } else {
            $this->request->data["Job"]['dis_amount'] = 0;
            $this->request->data["Job"]['promo_code'] = '';
            $this->request->data["Job"]['payment_type'] = 1;
        }

        $this->request->data["Job"]['status'] = 1;
        $this->request->data["Job"]['payment_status'] = 2;

        $promocode = $_SESSION['promo_code'];
        $promoamount = $_SESSION['dis_amount'];


        if ($this->Job->save($this->data)) {
            //if(1){
            $jobId = $this->Job->id;
            // $jobId = 30;

            $jobInfo = $this->Job->findById($jobId);

            $amount = number_format($_SESSION['amount'], 2);
            $totalAmount = $amount;

            $jobNumber = $jobInfo['Job']['job_number'];
            $transactionId = 'FREE' . date('ymd') . rand(1000, 9999);

            $this->request->data['Payment']['job_id'] = $jobInfo['Job']['id'];
            $this->request->data['Payment']['user_id'] = $jobInfo['User']['id'];
            $this->request->data['Payment']['payment_status'] = 'Completed';
            $this->request->data['Payment']['payer_id'] = '';
            $this->request->data['Payment']['subscr_id'] = '';
            $this->request->data['Payment']['signature'] = '';
            $this->request->data['Payment']['price'] = $totalAmount;
            $this->request->data['Payment']['transaction_id'] = $transactionId;
            $this->request->data['Payment']['slug'] = 'payment-' . $jobNumber . '-' . time();
            $this->request->data['Payment']['status'] = 1;
            $this->request->data['Payment']['dis_amount'] = $jobInfo['Job']['dis_amount'];
            $this->request->data['Payment']['payment_type'] = $jobInfo['Job']['payment_type'];

            $this->Payment->save($this->data['Payment']);

            $this->Job->updateAll(array('Job.payment_status' => '2', 'Job.status' => '1'), array('Job.id' => $jobInfo['Job']['id']));

            $jobCategory = $jobInfo['Job']['category_id'];
            $jobTitle = $jobInfo['Job']['title'];
            $jobId = $jobInfo['Job']['id'];
            $condition[] = " FIND_IN_SET(" . $jobCategory . ",User.email_notification_id)";
            $usersIds = $this->User->find('all', array('conditions' => $condition, 'fields' => array('User.first_name', 'User.email_address', 'User.id')));
            $week = 1;
            if ($jobInfo['Job']['type'] == 'silver') {
                $week = 2;
            } elseif ($jobInfo['Job']['type'] == 'gold') {
                $week = 4;
            }

            if ($usersIds) {
                foreach ($usersIds as $usersIdVV) {
                    $this->request->data['JobNotification']['id'] = '';
                    $this->request->data['JobNotification']['user_id'] = $usersIdVV['User']['id'];
                    $this->request->data['JobNotification']['job_id'] = $jobId;
                    $this->request->data['JobNotification']['first_name'] = $usersIdVV['User']['first_name'];
                    $this->request->data['JobNotification']['email_address'] = $usersIdVV['User']['email_address'];
                    $this->request->data['JobNotification']['last_email_sent_time'] = 0;
                    $this->request->data['JobNotification']['expire_time'] = time() + $week * 7 * 24 * 3600;
                    $this->request->data['JobNotification']['week'] = $week;
                    $this->JobNotification->save($this->data);
                }
            }


            $email = $jobInfo["User"]["email_address"];
            $name = $jobInfo["User"]["first_name"];
            $jobTitle = $jobInfo["Job"]["title"];
            $companyname = $jobInfo["User"]["company_name"];
            $link = HTTP_PATH . '/jobs/management';

            $date = date('F d, Y');


            $this->Email->to = $email;
            $currentYear = date('Y', time());
            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='21'"));

            $emData = $this->Emailtemplate->getSubjectLang();
            $subjectField = $emData['subject'];
            $templateField = $emData['template'];

            $toSubArray = array('[!username!]', '[!jobtitle!]', '[!transactionId!]', '[!DATE!]', '[!promocode!]', '[!SITE_TITLE!]');
            $fromSubArray = array($name, $jobTitle, $transactionId, $date, $promocode, $site_title);
            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
            $this->Email->subject = $subjectToSend;


            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
            $this->Email->from = $site_title . "<" . $mail_from . ">";
            $toRepArray = array('[!username!]', '[!jobtitle!]', '[!transactionId!]', '[!DATE!]', '[!promocode!]', '[!SITE_TITLE!]');
            $fromRepArray = array($name, $jobTitle, $transactionId, $date, $promocode, $site_title);
            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
            $this->Email->layout = 'default';
            $this->set('messageToSend', $messageToSend);
            $this->Email->template = 'email_template';
            $this->Email->attachments = array(UPLOAD_FULL_INVOICE_IMAGE_PATH . $jobInfo["Job"]["invoice_inumber"] . '.pdf');
            $this->Email->sendAs = 'html';
            $this->Email->send();


            $adminInfo = $this->Admin->findById(1);

            $this->Email->to = $adminInfo['Admin']['email'];
            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='22'"));

            $emData = $this->Emailtemplate->getSubjectLang();
            $subjectField = $emData['subject'];
            $templateField = $emData['template'];

            $toSubArray = array('[!site_title!]');
            $fromSubArray = array($site_title);
            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
            $this->Email->subject = $subjectToSend;


            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
            $this->Email->from = $site_title . "<" . $mail_from . ">";
            $currentYear = date('Y', time());
            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';
            $toRepArray = array('[!username!]', '[!jobtitle!]', '[!transactionId!]', '[!DATE!]', '[!invoiceInumber!]', '[!SITE_TITLE!], [!companyname!]');
            $fromRepArray = array($name, $jobTitle, $transactionId, $date, $jobInfo["Job"]["invoice_inumber"], $site_title, $companyname);
            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
            $this->Email->layout = 'default';
            $this->set('messageToSend', $messageToSend);
            $this->Email->template = 'email_template';
            $this->Email->attachments = array(UPLOAD_FULL_INVOICE_IMAGE_PATH . $jobInfo["Job"]["invoice_inumber"] . '.pdf');
            $this->Email->sendAs = 'html';
            $this->Email->send();


            unset($_SESSION['data']);
            unset($_SESSION['copy_data']);
            unset($_SESSION['type']);
            unset($_SESSION['dis_amount']);
            unset($_SESSION['promo_code']);
            $this->Session->write('success_msg', "Job posted successfully.");
            $this->redirect("/jobs/management/");
        }
    }

    public function processPayment() {
        $this->layout = "client";
        $this->set('jobsActive', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Select Plan');
        $this->userLoginCheck();
        $this->recruiterAccess();

        $this->request->data["Job"] = $_SESSION['data'];

        if (isset($_SESSION['dis_amount']) && $_SESSION['dis_amount'] != '') {
            $this->request->data["Job"]['dis_amount'] = $_SESSION['dis_amount'];
            $this->request->data["Job"]['promo_code'] = $_SESSION['promo_code'];
            $this->request->data["Job"]['payment_type'] = 3;
        } else {
            $this->request->data["Job"]['dis_amount'] = 0;
            $this->request->data["Job"]['promo_code'] = '';
            $this->request->data["Job"]['payment_type'] = 0;
        }

        $amount = number_format($this->data['Job']['amount'], 2);
        if (isset($this->request->data["Job"]['dis_amount']) && $this->request->data["Job"]['dis_amount'] != '') {
            $amount = $this->data['Job']['amount'] - $this->request->data["Job"]['dis_amount'];
        }

        $gstAmount = $amount * 10 / 100;
        $this->request->data["Job"]['amount'] = $amount + $gstAmount;

        $this->request->data["Job"]['type'] = $_SESSION['type'];
        $slug = $this->data["Job"]['slug'];

        if ($this->Job->save($this->data)) {
            unset($_SESSION['data']);
            unset($_SESSION['copy_data']);
            unset($_SESSION['type']);
            unset($_SESSION['dis_amount']);
            unset($_SESSION['promo_code']);
            $this->redirect("/payments/paynow/" . $slug);
        }
    }

    public function management() {

        $this->layout = "client";
        $this->set('jobsActive', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Manage Jobs', true));
        $this->userLoginCheck();
        $this->recruiterAccess();

        $condition = array('Job.user_id' => $this->Session->read('user_id'));
        $separator = array();
        $urlSeparator = array();

        $order = ' Job.expire_time DESC , Job.payment_status DESC';
        //$order = 'Job.id ASC';

        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        //pr($condition);exit;
        $this->paginate['Job'] = array('conditions' => $condition, 'limit' => '10', 'page' => '1', 'order' => $order);
        //  pr($this->paginate('Job'));
        $this->set('jobs', $this->paginate('Job'));

        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'jobs';
            $this->render('management');
        }
    }

    public function edit($slug = null) {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Edit Job', true));
        $this->set('jobsActive', 'active');

        $this->userLoginCheck();
        $this->recruiterAccess();

        $userId = $this->Session->read('user_id');
        $this->set('slug', $slug);
        $logo_status = $this->User->field('profile_image', array('User.id' => $userId));
        $this->set('logo_status', $logo_status);

        $categories = $this->Category->getCategoryList();
        $this->set('categories', $categories);
        $subcategories = array();
        $this->set('subcategories', $subcategories);

        // get skills from skill table
        $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('skillList', $skillList);

        // get designations from skill table
        $designationlList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Designation', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('designationlList', $designationlList);

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);


        $msgString = '';
        global $extentions;


        if ($this->data) {
            //pr($this->data);exit;

            if (empty($this->data["Job"]["title"])) {
                $msgString .= "- Job title is required field.<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["title"]);
            }

            if (empty($this->data["Job"]["category_id"])) {
                $msgString .= "- Category is required field.<br>";
            }


            if ($this->data['Job']['skill']) {
                
            } else if (empty($this->data['Job']['skill'])) {
                $msgString .= "- Skill is required field.<br>";
            } else {
                $this->request->data['Job']['skill'] = '';
            }

            if (empty($this->data["Job"]["designation"])) {
                $msgString .= "- Designation is required field.<br>";
            }

//            if (empty($this->data["Job"]["location"])) {
//                $msgString .="- Location is required field.<br>";
//            }


            if (empty($this->data["Job"]["description"])) {
                $msgString .= "- Job description is required field.<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["description"]);
            }

            if (empty($this->data["Job"]["company_name"])) {
                $msgString .= "- Company name is required field.<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["company_name"]);
            }

            /*  if (empty($this->data["Job"]["price"])) {
              $msgString .="- Wage Package  is required field.<br>";
              }
              if ($this->data["Job"]["price_status"] == '') {
              $msgString .="- Wage Package Status  is required field.<br>";
              }

              if (empty($this->data["Job"]["city_id"])) {
              $msgString .="- City is required field.<br>";
              }
              if (empty($this->data["Job"]["state_id"])) {
              $msgString .="- State is required field.<br>";
              }
             */
            if (empty($this->data["Job"]["work_type"])) {
                $msgString .= "- Work type is required field.<br>";
            }
            if (empty($this->data["Job"]["contact_name"])) {
                $msgString .= "- Contact name is required field.<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["contact_name"]);
            }

            if (empty($this->data["Job"]["contact_number"])) {
                $msgString .= "- Contact number is required field.<br>";
            } else {
                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["contact_number"]);
            }


            if (trim($this->data["Job"]["url"]) != '') {
                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["url"]);
            }


            $youtube_link = '';
//            if ($this->data["Job"]["youtube_link"]) {
//                $url = $this->data['Job']['youtube_link'];
//                $urlArray = explode('watch?v=', $url);
//                if (!$urlArray[1]) {
//                    $msgString .="- Please enter valid video URL.<br>";
//                }
//                $youtube_link = $urlArray[1];
//            }
//            if (trim($this->data["Job"]["youtube_link"]) != '') {
//                $msgString .= $this->Swear->checkSwearWord($this->data["Job"]["youtube_link"]);
//            }



            if ($this->data["Job"]["logo"]["name"]) {
                $getextention = $this->PImage->getExtension($this->data['Job']['logo']['name']);
                $extention = strtolower($getextention);
                if ($this->data['Job']['logo']['size'] > '2097152') {
                    $msgString .= "- Max file size upload is 2MB.<br>";
                } elseif (!in_array($extention, $extentions)) {
                    $msgString .= "- Not Valid Extention.<br>";
                }
            }


            if (isset($msgString) && $msgString != '') {
                $this->Session->write('error_msg', $msgString);

                $subcategories = $this->Category->getSubCategoryList($this->data['Job']['category_id']);
                $this->set('subcategories', $subcategories);

                /* $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['Job']['postal_code'])));
                  $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
                  $this->set('stateList', $stateList);
                  $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
                  $this->set('cityList', $cityList); */
            } else {

                $keyword = trim($this->data['Job']['title']);
                $keywordId = $this->Keyword->field('id', array('Keyword.name' => $keyword, 'Keyword.type' => 'Job'));
                if (!$keywordId) {
                    $this->request->data['Keyword']['name'] = $keyword;
                    $this->request->data['Keyword']['slug'] = $this->stringToSlugUnique($keyword, 'Keyword');
                    $this->request->data['Keyword']['status'] = '1';
                    $this->request->data['Keyword']['approval_status'] = '0';
                    $this->request->data['Keyword']['type'] = 'Job';
                    $this->Keyword->save($this->data);
                }

                if ($this->data["Job"]["logo"]["name"]) {
                    $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
                    $toReplace = "-";
                    $this->request->data["Job"]["logo"]['name'] = str_replace($specialCharacters, $toReplace, $this->data["Job"]["logo"]['name']);
                    $this->request->data["Job"]["logo"]['name'] = str_replace("&", "and", $this->data["Job"]["logo"]['name']);
                    $cvArray = $this->data["Job"]["logo"];
                    $returnedUploadCVArray = $this->PImage->upload($cvArray, UPLOAD_JOB_LOGO_PATH);
                    $this->request->data["Job"]["logo"] = $returnedUploadCVArray[0];
                    chmod(UPLOAD_JOB_LOGO_PATH . $returnedUploadImageArray[0], 0755);
                } else {
                    $this->request->data['Job']['logo'] = $this->data['Job']['old_logo'];
                }


                if ($this->data['Job']['subcategory_id']) {
                    $this->request->data['Job']['subcategory_id'] = implode(',', $this->data['Job']['subcategory_id']);
                } else {
                    $this->request->data['Job']['subcategory_id'] = 0;
                }

                $this->request->data['Job']['skill'] = implode(',', $this->data['Job']['skill']);

                $this->request->data['Job']['youtube_link'] = $youtube_link;

                $exp = explode('-', $this->data['Job']['exp']);
                $this->request->data["Job"]["min_exp"] = $exp[0];
                $this->request->data["Job"]["max_exp"] = $exp[1];

                $sallery = explode('-', $this->data['Job']['salary']);
                $this->request->data["Job"]["min_salary"] = $sallery[0];
                $this->request->data["Job"]["max_salary"] = $sallery[1];
                $this->request->data['Job']['expire_time'] = strtotime($this->data['Job']['expire_time']);
                //pr($this->data); exit;
                if ($this->Job->save($this->data)) {
                    $jobDetail = $this->Job->findBySlug($slug);
                    $site_title = $this->getSiteConstant('title');
                    $mail_from = $this->getMailConstant('from');

                    $title = $jobDetail['Job']['title'];
                    $category = $jobDetail['Category']['name'];
                    $skill = $jobDetail['Skill']['name'];
                    $location = $jobDetail['Job']['job_city'];
                    $minExp = $jobDetail['Job']['min_exp'] . ' Year';
                    $maxExp = $jobDetail['Job']['max_exp'] . ' Year';
                    $min_salary = CURRENCY . ' ' . intval($jobDetail['Job']['min_salary']);
                    $max_salary = CURRENCY . ' ' . intval($jobDetail['Job']['max_salary']);
                    $description = $jobDetail['Job']['description'];
                    $company_name = $jobDetail['Job']['company_name'];
                    $contact_number = $jobDetail['Job']['contact_number'];
                    $website = $jobDetail['Job']['url'] ? $jobDetail['Job']['url'] : 'N/A';
                    $address = $jobDetail['Job']['address'] ? $jobDetail['Job']['address'] : 'N/A';

                    $designation = $this->Skill->field('name', array(
                        'Skill.status' => 1,
                        'Skill.type' => 'Designation',
                        'Skill.id' => $jobDetail['Job']['designation'],
                    ));
                    $username = $jobDetail['User']['first_name'] . ' ' . $jobDetail['User']['last_name'];
                    $this->Email->to = $jobDetail['User']['email_address'];
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='48'"));

                    $toSubArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                    $fromSubArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                    $this->Email->subject = $subjectToSend;


                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";
                    $toRepArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                    $fromRepArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();

                    $this->Session->write('success_msg', "Job detail updated successfully.");
                    $this->redirect("/jobs/accdetail/" . $slug);
                }
            }
        } else {
            $id = $this->Job->field('id', array('Job.slug' => $slug));
            $this->Job->id = $id;
            $this->data = $this->Job->read();
            $this->set('jobEdit', $this->data);

            $subcategories = $this->Category->getSubCategoryList($this->data['Job']['category_id']);
            $this->set('subcategories', $subcategories);

            $this->request->data['Job']['old_logo'] = $this->data['Job']['logo'];
            $this->request->data['Job']['old_title'] = $this->data['Job']['title'];
            $this->request->data['Job']['subcategory_id'] = explode(',', $this->data['Job']['subcategory_id']);

            // get skills from skill table
            $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
            $this->set('skillList', $skillList);
            $this->request->data['Job']['skill'] = explode(',', $this->data['Job']['skill']);

            if ($this->data['Job']['youtube_link']) {
                $this->request->data['Job']['youtube_link'] = 'https://www.youtube.com/watch?v=' . $this->data['Job']['youtube_link'];
            }
            $this->request->data['Job']['expire_time'] = date('Y-m-d', $this->data['Job']['expire_time']);

            $this->request->data['Job']['exp'] = $this->data['Job']['min_exp'] . '-' . $this->data['Job']['max_exp'];
            $this->request->data['Job']['salary'] = $this->data['Job']['min_salary'] . '-' . $this->data['Job']['max_salary'];


            /* $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['Job']['postal_code'])));
              $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
              $this->set('stateList', $stateList);
              $cityList = $this->getCityList($postCodeDetails['PostCode']['id']);
              $this->set('cityList', $cityList); */
        }
    }

    function getCityList($post_id = null) {

        $citys = $this->City->find('list', array(
            'conditions' => array(
                'City.status' => '1',
                'City.post_code_id' => $post_id
            ),
            'fields' => array(
                'City.id',
                'City.city_name'
            ),
            'order' => 'City.city_name asc')
        );

        return $citys;
    }

    public function accdetail($slug = null, $status = null) {
        $this->layout = "client";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Job Details', true));

        $this->userLoginCheck();
        $this->recruiterAccess();
        $this->set('jobsActive', 'active');
        $jobInfo = $this->Job->findBySlug($slug);
        $this->set('jobInfo', $jobInfo);
        $this->set('status', $status);

        if ($status != '' && $status != null) {
            $condition = array('JobApply.status' => 1, 'JobApply.job_id' => $jobInfo['Job']['id'], 'apply_status' => $status);
        } else {
            $condition = array('JobApply.status' => 1, 'JobApply.job_id' => $jobInfo['Job']['id']);
        }


        $separator = array($slug, $status);
        $urlSeparator = array();


        if (!empty($this->data)) {

            if (isset($this->data['JobApply']['keyword']) && $this->data['JobApply']['keyword'] != '') {
                $keyword = trim($this->data['JobApply']['keyword']);
            }

            if (isset($this->data['JobApply']['action'])) {
                $idList = $this->data['JobApply']['idList'];
                if ($idList) { //pr($this->data);exit;
                    if ($this->data['JobApply']['action'] == "email") { //exit;
                        $_SESSION['email_ids'] = $idList;
                        $this->render('send_mail');
                        //$this->redirect('/jobs/sendMail/'.$slug);
                    }
                }
            }

            if (isset($this->data['JobApply']['status_change']) && $this->data['JobApply']['status_change'] != '') {
                $candidateId = $this->data['JobApply']['candidate_id'];
                $status_change = $this->data['JobApply']['status_change'];
                if ($candidateId) {
                    $cnd = array("JobApply.id = $candidateId");
                    $this->JobApply->updateAll(array('JobApply.apply_status' => "'$status_change'"), $cnd);
                }
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['keyword']) && $this->params['named']['keyword'] != '') {
                $keyword = urldecode(trim($this->params['named']['keyword']));
            }
        }

        if (isset($keyword) && $keyword != '') {
            $separator[] = 'keyword:' . urlencode($keyword);
            $condition[] = " (`JobApply`.`apply_status` LIKE '%" . addslashes($keyword) . "%' OR `User`.`first_name` LIKE '%" . addslashes($keyword) . "%' or concat(`User.first_name`,' ',`User.last_name`) LIKE '%" . addslashes($keyword) . "%' or `User`.`last_name` LIKE '%" . addslashes($keyword) . "%' or `User`.`email_address` LIKE '%" . addslashes($keyword) . "%'  ) ";
        }


        $order = 'JobApply.id Desc';

        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['JobApply'] = array('conditions' => $condition, 'limit' => '10', 'page' => '1', 'order' => $order);
        $this->set('candidates', $this->paginate('JobApply'));
        //pr($condition);exit;


        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'jobs';
            $this->render('active_candidate');
        }
    }

    public function closeJobDetail($slug = null) {

        $this->layout = "client";
        $this->set('title_for_layout', TITLE_FOR_PAGES . __d('controller', 'Job Details', true));
        $this->userLoginCheck();
        $this->recruiterAccess();
        $this->set('jobsActive', 'active');
        $jobInfo = $this->Job->findBySlug($slug);
        $this->set('jobInfo', $jobInfo);

        $condition = array('JobApply.status' => 1, 'JobApply.job_id' => $jobInfo['Job']['id']);
        $separator = array($slug);
        $urlSeparator = array();
        $status = '';

        if (!empty($this->data)) {
            //pr($this->data);exit;
            if (isset($this->data['JobApply']['keyword']) && $this->data['JobApply']['keyword'] != '') {
                $keyword = trim($this->data['JobApply']['keyword']);
            }

            if (isset($this->data['JobApply']['action'])) {
                $idList = $this->data['JobApply']['idList'];
                if ($idList) { //pr($this->data);exit;
                    if ($this->data['JobApply']['action'] == "email") { //exit;
                        $_SESSION['email_ids'] = $idList;
                        $this->render('send_mail');
                        //$this->redirect('/jobs/sendMail/'.$slug);
                    }
                }
            }
        } elseif (!empty($this->params)) {
            if (isset($this->params['named']['keyword']) && $this->params['named']['keyword'] != '') {
                $keyword = urldecode(trim($this->params['named']['keyword']));
            }
        }

        if (isset($keyword) && $keyword != '') {
            $separator[] = 'keyword:' . urlencode($keyword);
            $condition[] = " (`JobApply`.`apply_status` LIKE '%" . addslashes($keyword) . "%' OR `User`.`first_name` LIKE '%" . addslashes($keyword) . "%' or concat(`User.first_name`,' ',`User.last_name`) LIKE '%" . addslashes($keyword) . "%' or `User`.`last_name` LIKE '%" . addslashes($keyword) . "%' or `User`.`email_address` LIKE '%" . addslashes($keyword) . "%'  ) ";
        }


        $order = 'JobApply.id Desc';

        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['JobApply'] = array('conditions' => $condition, 'limit' => '5', 'page' => '1', 'order' => $order);
        $this->set('candidates', $this->paginate('JobApply'));
        //pr($this->paginate('JobApply'));exit;


        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'jobs';
            $this->render('closed_active_candidate');
        }
    }

    public function sendMail($slug = null) {

        $mail_from = $this->getMailConstant('from');
        $reply_to = $this->getMailConstant('reply_to');
        $site_title = $this->getSiteConstant('title');
        $site_url = $this->getSiteConstant('url');

        if ($this->data) {

            $jobInfo = $this->Job->find('first', array('conditions' => array('Job.slug' => $slug)));
            if (!empty($jobInfo)) {
                $recruiterInfo = $this->User->find('first', array('conditions' => array('User.id' => $jobInfo['Job']['user_id'])));
                $recruiter_email = $recruiterInfo['User']['email_address'];
                $recruiter_company = $recruiterInfo['User']['company_name'];
                $job_title = $jobInfo['Job']['title'];
            }
            // echo "<pre>"; print_r($jobInfo); exit;



            $allemails = explode(",", $this->data['Job']['email']);
            foreach ($allemails as $email) {
                if (trim($email) != '') {
                    if ($this->User->checkEmail($email) == true) {

                        $userInfo = $this->User->find('first', array('conditions' => array('User.email_address' => $email)));
                        $userName = ucfirst($userInfo["User"]["first_name"]);
                        //echo "<pre>"; print_r($userInfo); exit;

                        $this->Email->to = $email;
                        $message = nl2br($this->data['Job']['message']);
                        $subject = $this->data['Job']['subject'];
                        $currentYear = date('Y', time());
                        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                        $emData = $this->Emailtemplate->getSubjectLang();
                        $subjectField = $emData['subject'];
                        $templateField = $emData['template'];

                        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='18'"));
                        //$this->Email->subject = $this->data['Job']['subject'];
                        $toSubArray = array('[!username!]', '[!MESSAGE!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]');
                        $fromSubArray = array($userName, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $subject);
                        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                        $this->Email->subject = $subjectToSend;

                        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                        $this->Email->from = $site_title . "<" . $mail_from . ">";
                        $toRepArray = array('[!username!]', '[!MESSAGE!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!subject!]', '[!company_email!]', '[!company!]', '[!job!]');
                        $fromRepArray = array($userName, $message, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $subject, $recruiter_email, $recruiter_company, $job_title);
                        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                        $this->Email->layout = 'default';
                        $this->set('messageToSend', $messageToSend);
                        $this->Email->template = 'email_template';
                        $this->Email->sendAs = 'html';
                        //pr($this->Email->send()); exit;
                        $this->Email->send();
                        $this->Email->reset();
                    }
                }
            }
        }
        $this->Session->setFlash(__d('controller', 'Email sent successfully.', true), 'success_msg');
        $this->redirect($this->referer());
    }

    public function delete($slug = null) {

        $this->userLoginCheck();
        $this->recruiterAccess();

        $jobInfo = $this->Job->findBySlug($slug);
        $jobDetail = $jobInfo;
        if ($slug != '') {
            $site_title = $this->getSiteConstant('title');
            $mail_from = $this->getMailConstant('from');

            $title = $jobDetail['Job']['title'];
            $category = $jobDetail['Category']['name'];
            $location = $jobDetail['Job']['job_city'];

            $username = $jobDetail['User']['first_name'] . ' ' . $jobDetail['User']['last_name'];
            $this->Email->to = $jobDetail['User']['email_address'];
            $currentYear = date('Y', time());
            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='47'"));

            $toSubArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
            $fromSubArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
            $this->Email->subject = $subjectToSend;


            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
            $this->Email->from = $site_title . "<" . $mail_from . ">";
            $toRepArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!SITE_TITLE!]');
            $fromRepArray = array($username, $title, $category, $location, $site_title);
            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
            $this->Email->layout = 'default';
            $this->set('messageToSend', $messageToSend);
            $this->Email->template = 'email_template';
            $this->Email->sendAs = 'html';
            $this->Email->send();

            $id = $this->Job->field('id', array('Job.slug' => $slug));
            $this->Job->delete($id);
            $this->Session->write('success_msg', __d('controller', 'Job details deleted successfully.', true));
        }
        $this->redirect('/jobs/management');
    }

    public function deactive($slug = null) {

        $id = $this->Job->field('id', array('Job.slug' => $slug));
        $cnd = array("Job.id = $id");
        $this->Job->updateAll(array('Job.status' => "'0'"), $cnd);
        $this->Session->write('success_msg', __d('controller', 'Job deactivated successfully.', true));
        $this->redirect('/jobs/management');
    }

    public function active($slug = null) {

        $id = $this->Job->field('id', array('Job.slug' => $slug));
        $cnd = array("Job.id = $id");
        $this->Job->updateAll(array('Job.status' => "'1'"), $cnd);
        $this->Session->write('success_msg', __d('controller', 'Job activated successfully.', true));
        $this->redirect('/jobs/management');
    }

    public function close($slug = null) {
        $id = $this->Job->field('id', array('Job.slug' => $slug));
        $cnd = array("Job.id = $id");
        $this->Job->updateAll(array('Job.job_status' => "'1'", 'Job.status' => "'0'"), $cnd);
        $this->Session->write('success_msg', __d('controller', 'Job closed successfully.', true));
        $this->redirect('/jobs/management');
    }

    public function applycode($couponCode = null) {

        //$couponCode = $this->data['Job']['promo_code'];
        unset($_SESSION['dis_amount']);
        unset($_SESSION['promo_code']);
        $_SESSION['total'] = $_SESSION['amount'];
        if ($couponCode) {
            $procodeInfo = $this->PromoCode->findBySlug($couponCode);

            unset($_SESSION['dis_amount']);
            unset($_SESSION['promo_code']);
            $_SESSION['total'] = $_SESSION['amount'];

            if ($procodeInfo) {

                if ($procodeInfo['PromoCode']['expiry_date'] < date('Y-m-d')) {
                    $this->Session->write('error_msg', "This promo code has expired.");
                } elseif ($procodeInfo['PromoCode']['status'] == 0) {
                    $this->Session->write('error_msg', "This promo code deactivated by admin.");
                } else {
                    $this->Session->write('success_msg', "Promo code " . $couponCode . " applied successfully.");
                    $amount = $_SESSION['amount'];
                    if ($procodeInfo['PromoCode']['discount_type'] == 'Percent') {
                        $disAmount = round($amount * ($procodeInfo['PromoCode']['discount'] / 100), 2);
                    } else {
                        $disAmount = round($amount - $procodeInfo['PromoCode']['discount'], 2);
                        if ($disAmount < 0) {
                            $disAmount = $amount;
                        } else {
                            $disAmount = $procodeInfo['PromoCode']['discount'];
                        }
                    }
                    $paidAmount = $amount - $disAmount;
                    $_SESSION['total'] = $paidAmount;
                    $_SESSION['dis_amount'] = $disAmount;
                    $_SESSION['promo_code'] = $couponCode;
                }
            } else {
                $this->Session->write('error_msg', "Please enter a valid Promo Code");
            }
        }

        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements';
            $this->render('updateprice');
        }
    }

    function getStateCity($model = 'User', $state_id = null) {
        $this->layout = '';
        $cityList = array();
        if ($state_id) {
            $cityList = $this->City->find('list', array(
                'conditions' => array(
                    'City.status' => '1',
                    'City.state_id' => $state_id
                ),
                'fields' => array(
                    'City.id',
                    'City.city_name'
                ),
                'order' => 'City.city_name asc')
            );
        }
        $this->set('cityList', $cityList);
        $this->set('model', $model);
    }

    public function getSubCategory($categoryId = null) {
        $this->layout = '';
        $subcategories = array();
        if ($categoryId) {
            $subcategories = $this->Category->getSubCategoryList($categoryId);
        }
        $this->set('subcategories', $subcategories);
    }

    public function filterSection($categorySlug = '') {

        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Jobs List', true));
        $this->set('jobs_list', 'active');


        $userId = $this->Session->read('user_id');
        // $category['category']['id'] = $this->Category->findByName($categoryName);
        //find category Id by slug
        $categoryId = $this->Category->field('id', array('slug' => $categorySlug));

        if (!empty($categorySlug)) {
            $categoryName = $this->Category->field('name', array('Category.slug' => $categorySlug));
            $this->set('categoryName', $categoryName);
        } else {
            $metaData = classRegistry::init('Admin')->find('first', array('conditions' => array('Admin.id' => 1)));
            $this->set('metaData', $metaData);
        }



        //for url if user enter wrong argument/parameter
        // in url so check the information by follwoing method
        if (trim($categoryId) != '') {
            $categorieCount = $this->Category->findById($categoryId);
            if (empty($categorieCount)) {
                $this->redirect('/homes/error');
            }
        }

        if ($userId) {
            $showOldImages = $this->Certificate->find('list', array('conditions' => array('Certificate.user_id' => $userId), 'fields' => array('id', 'document'), 'order' => 'Certificate.id ASC'));
            $this->set('showOldImages', $showOldImages);
        }


        $categories = $this->Category->getCategoryList();
        $this->set('categories', $categories);

        $subcategories = array();
        if (isset($this->data['Job']['category_id']) && $this->data['Job']['category_id'] != '') {
            $this->set('subcategories', $this->Category->getSubCategoryList($this->data['Job']['category_id']));
        } else {
            $this->set('subcategories', $subcategories);
        }

//        $stateList = $states = $this->State->find('list', array('conditions' => array('State.status' => '1', 'State.country_id' => 1),
//            'fields' => array('State.id', 'State.state_name'), 'order' => 'State.state_name asc'));
//        $this->set('stateList', $stateList);
//        $cityList = array();
//
//        $this->set('cityList', $cityList);
        // get skills from skill table
        $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('skillList', $skillList);

        // get designations from skill table
        $designationlList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Designation', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('designationlList', $designationlList);

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        $condition = array('Job.status' => 1, 'Job.payment_status' => 2, 'Job.expire_time >=' => time());
//        $condition = array('Job.status' => 1, 'Job.payment_status' => 2);

        $separator = array();
        $urlSeparator = array();

        //  $order = 'Job.created Desc';
        $order = '';

        if (!empty($this->data)) {
            if (isset($this->data['Job']['created']) && $this->data['Job']['created'] != '') {
                $order = 'Job.' . $this->data['Job']['created'];
            }

            if (isset($this->data['Job']['keyword']) && $this->data['Job']['keyword'] != '') {
                $keyword = trim($this->data['Job']['keyword']);
            }

            //search for skill and designation
            //by front end
            /* if (isset($this->data['Job']['searchkey']) && $this->data['Job']['searchkey'] != '') {
              if (isset($this->data['Job']['hiddenId']) && !empty($this->data['Job']['hiddenId'])) {
              $searchkey = trim($this->data['Job']['hiddenId']);
              } else {
              $searchkey = trim($this->data['Job']['searchkey']);
              }
              }
              if (isset($this->data['Job']['hiddenId']) && $this->data['Job']['hiddenId'] > 0) {

              $searchkey = trim($this->data['Job']['hiddenId']);
              } */

            if (isset($this->data['Job']['searchkey']) && $this->data['Job']['searchkey'] != '') {
                if (is_array($this->data['Job']['searchkey'])) {
                    $searchkey = implode('-', $this->data['Job']['searchkey']);
                } else {
                    $searchkey = $this->data['Job']['searchkey'];
                }
            }

            if (isset($this->data['Job']['category_id']) && !empty($this->data['Job']['category_id']) && count($this->data['Job']['category_id']) > 0) {
                if (is_array($this->data['Job']['category_id'])) {
                    $category_id = implode('-', $this->data['Job']['category_id']);
                } else {
                    $category_id = $this->data['Job']['category_id'];
                }
            }

            if (isset($this->data['Job']['subcategory_id']) && !empty($this->data['Job']['subcategory_id']) && count($this->data['Job']['subcategory_id']) > 0) {
                if (is_array($this->data['Job']['subcategory_id'])) {
                    $subcategory_id = implode('-', $this->data['Job']['subcategory_id']);
                } else {
                    $subcategory_id = $this->data['Job']['subcategory_id'];
                }
            }
//            if (isset($this->data['Job']['category_id']) && $this->data['Job']['category_id'] != '') {
//                $categoryId = trim($this->data['Job']['category_id']);
//            }
//            if (isset($this->data['Job']['subcategory_id']) && $this->data['Job']['subcategory_id'] != '') {
//                $subcategoryId = trim($this->data['Job']['subcategory_id']);
//            }
//            if (isset($this->data['Job']['location']) && $this->data['Job']['location'] != '') {
//                $location = trim($this->data['Job']['location']);
//            }

            if (isset($this->data['Job']['location']) && !empty($this->data['Job']['location']) && count($this->data['Job']['location']) > 0) {

                if (is_array($this->data['Job']['location'])) {
                    $location = implode('-', $this->data['Job']['location']);
                    // $location = addslashes($location);
                    // $this->set('location', $this->data['Job']['location']);
                } else {
                    $location = $this->data['Job']['location'];
                }
            }

            if (isset($this->data['Job']['work_type']) && !empty($this->data['Job']['work_type']) && count($this->data['Job']['work_type']) > 0) {

                if (is_array($this->data['Job']['work_type'])) {
                    $worktype = implode('-', $this->data['Job']['work_type']);
                } else {
                    $worktype = $this->data['Job']['work_type'];
                }
            }

            /* if (!empty($this->data['Job']['location'])) {
              $location = implode(",", $this->data['Job']['location']);
              $location = addslashes($location);
              $this->set('location', $this->data['Job']['location']);
              } */


            //if (isset($this->data['Job']['skill']) && $this->data['Job']['skill'] != '') {
            //     $skill = implode(",", $this->data['Job']['skill']);
            // }

            if (!empty($this->data['Job']['skill'])) {
                $skill = implode(",", $this->data['Job']['skill']);
                $skill = addslashes($skill);
                $this->set('skill', $this->data['Job']['skill']);
            }


//            if (isset($this->data['Job']['designation']) && $this->data['Job']['designation'] != '') {
//                $designation = trim($this->data['Job']['designation']);
//            }
            if (!empty($this->data['Job']['designation'])) {
                $designation = implode(",", $this->data['Job']['designation']);
                $designation = addslashes($designation);
                $this->set('designation', $this->data['Job']['designation']);
            }
            if (isset($this->data['Job']['min_salary']) && $this->data['Job']['min_salary'] != '') {
                $min_salary = trim($this->data['Job']['min_salary']);
            }
            if (isset($this->data['Job']['max_salary']) && $this->data['Job']['max_salary'] != '') {
                $max_salary = trim($this->data['Job']['max_salary']);
            }
            if (isset($this->data['Job']['min_exp']) && $this->data['Job']['min_exp'] != '') {
                $min_exp = trim($this->data['Job']['min_exp']);
            }
            if (isset($this->data['Job']['max_exp']) && $this->data['Job']['max_exp'] != '') {
                $max_exp = trim($this->data['Job']['max_exp']);
            }
        } elseif (!empty($this->params)) {

            if (isset($this->params['named']['keyword']) && $this->params['named']['keyword'] != '') {
                $keyword = urldecode(trim($this->params['named']['keyword']));
            }
            if (isset($this->params['named']['category_id']) && $this->params['named']['category_id'] != '') {
                $category_id = trim($this->params['named']['category_id']);
            }
            if (isset($this->params['named']['subcategory_id']) && $this->params['named']['subcategory_id'] != '') {
                $subcategory_id = trim($this->params['named']['subcategory_id']);
            }
//            if (isset($this->params['named']['categoryId']) && $this->params['named']['categoryId'] != '') {
//                $categoryId = urldecode(trim($this->params['named']['categoryId']));
//            }
//            if (isset($this->params['named']['subcategoryId']) && $this->params['named']['subcategoryId'] != '') {
//                $subcategoryId = urldecode(trim($this->params['named']['subcategoryId']));
//            }
            //  if (isset($this->params['named']['location']) && $this->params['named']['location'] != '') {
            //       $location = urldecode(trim($this->params['named']['location']));
            //   }
            // if (isset($this->params['named']['skill']) && $this->params['named']['skill'] != '') {
            //     $skill = urldecode(implode(",", $this->data['Job']['skill']));
            //  }
            if (isset($this->params['named']['location']) && $this->params['named']['location'] != '') {
                $location = urldecode(trim($this->params['named']['location']));
                // $location = addslashes($location);
                //$this->set('location', $location);
            }

            if (isset($this->params['named']['work_type']) && $this->params['named']['work_type'] != '') {
                $worktype = urldecode(trim($this->params['named']['work_type']));
            }

            if (isset($this->params['named']['skill']) && $this->params['named']['skill'] != '') {
                $skill = trim($this->params['named']['skill']);
                $skill = addslashes($skill);
                $this->set('skill', $skill);
            }

//            if (isset($this->params['named']['designation']) && $this->params['named']['designation'] != '') {
//                $designation = urldecode(trim($this->params['named']['designation']));
//            }
            if (isset($this->params['named']['designation']) && $this->params['named']['designation'] != '') {
                $designation = trim($this->params['named']['designation']);
                $designation = addslashes($designation);
                $this->set('designation', $designation);
            }
            if (isset($this->params['named']['min_salary']) && $this->params['named']['min_salary'] != '') {
                $min_salary = urldecode(trim($this->params['named']['min_salary']));
            }
            if (isset($this->params['named']['max_salary']) && $this->params['named']['max_salary'] != '') {
                $max_salary = urldecode(trim($this->params['named']['max_salary']));
            }
            if (isset($this->params['named']['min_exp']) && $this->params['named']['min_exp'] != '') {
                $min_exp = urldecode(trim($this->params['named']['min_exp']));
            }
            if (isset($this->params['named']['max_exp']) && $this->params['named']['max_exp'] != '') {
                $max_exp = urldecode(trim($this->params['named']['max_exp']));
            }

            if (isset($this->params['named']['searchkey']) && $this->params['named']['searchkey'] != '') {
                $searchkey = urldecode(trim($this->params['named']['searchkey']));
            }
        }



        if (isset($keyword) && $keyword != '') {
            $separator[] = 'keyword:' . urlencode($keyword);
            $keyword = str_replace('_', '\_', $keyword);
            $condition[] = " (`Job`.`title` LIKE '%" . addslashes($keyword) . "%' OR `Job`.`description` LIKE '%" . addslashes($keyword) . "%' OR `Job`.`company_name` LIKE '%" . addslashes($keyword) . "%'  ) ";
            $keyword = str_replace('\_', '_', $keyword);
            $this->set('keyword', $keyword);
        }


        if (isset($searchkey) && !empty($searchkey)) {

            $searchkey_arr = explode("-", $searchkey);

            foreach ($searchkey_arr as $id) {
                $condition_search[] = "(FIND_IN_SET('" . $id . "',Job.skill) OR FIND_IN_SET('" . $id . "',Job.designation) )";
            }
            $condition[] = array('OR' => $condition_search);
            $urlSeparator[] = 'searchkey:' . $searchkey;
            $separator[] = 'searchkey:' . $searchkey;
            $this->set('searchkey', $searchkey);
        }
        // pr($condition); exit; 

        if (isset($category_id) && $category_id != '') {
            $this->set('topcate', $category_id);
            $separator[] = 'category_id:' . $category_id;
            $category_idCondtionArray = explode('-', $category_id);

            if (isset($subcategory_id) && $subcategory_id != '') {
                $this->set('subcate', $subcategory_id);
                $subcategory_idCondtionArray = explode('-', $subcategory_id);
                foreach ($subcategory_idCondtionArray as $subMain) {
                    $subMainVal = $this->Category->field('parent_id', array('Category.id' => $subMain));
                    if (($key = array_search($subMainVal, $category_idCondtionArray)) !== false) {
                        unset($category_idCondtionArray[$key]);
                    }
                    //   pr($category_idCondtionArray);
                }
                // pr($category_idCondtionArray);
                if ($category_idCondtionArray) {
                    $subcategory_idCondtion = implode(',', $subcategory_idCondtionArray);
                    $separator[] = 'subcategory_id:' . $subcategory_id;

                    $category_idCondtion = implode(',', $category_idCondtionArray);
                    $condition[] = " (Job.category_id IN ($category_idCondtion) OR Job.subcategory_id IN ($subcategory_idCondtion ) )";
                } else {
                    $subcategory_idCondtion = implode(',', $subcategory_idCondtionArray);
                    $condition[] = " (Job.subcategory_id IN ($subcategory_idCondtion ))";
                    $separator[] = 'subcategory_id:' . $subcategory_id;
                }
            } else {
                $category_idCondtion = implode(',', $category_idCondtionArray);
                $condition[] = " (Job.category_id IN ($category_idCondtion))";
            }
        }
        /* if (isset($categoryId) && $categoryId != '') {
          $separator[] = 'categoryId:' . urlencode($categoryId);
          $categoryId = str_replace('_', '\_', $categoryId);
          $condition[] = " (`Job`.`category_id` = $categoryId) ";
          $categoryId = str_replace('\_', '_', $categoryId);
          $this->set('categoryId', $categoryId);
          }
          if (isset($subcategoryId) && $subcategoryId != '') {
          $separator[] = 'subcategoryId:' . urlencode($subcategoryId);
          $subcategoryId = str_replace('_', '\_', $subcategoryId);
          $condition[] = "( FIND_IN_SET('$subcategoryId',`Job`.`subcategory_id`)) ";
          $subcategoryId = str_replace('\_', '_', $subcategoryId);
          $this->set('subcategoryId', $subcategoryId);
          }
          if (isset($skill) && $skill != '') {
          $separator[] = 'skill:' . urlencode($skill);
          $skill = str_replace('_', '\_', $skill);
          $condition[] = "( FIND_IN_SET('$skill',`Job`.`skill`)) ";
          $skill = str_replace('\_', '_', $skill);
          $this->set('skill', $skill);
          } */

        if (!empty($skill)) {
            $skill_arr = explode(",", $skill);
            foreach ($skill_arr as $skil) {
                $condition_skill[] = "(FIND_IN_SET('" . $skil . "',Job.skill))";
            }
            $condition[] = array('OR' => $condition_skill);
            $urlSeparator[] = 'skill:' . $skill;
            $separator[] = 'skill:' . $skill;
        }

        if (!empty($location)) {
            $location_arr = explode("-", $location);
            foreach ($location_arr as $loc) {
                $condition_location[] = "(FIND_IN_SET('" . $loc . "',Job.location))";
            }
            $condition[] = array('OR' => $condition_location);
            $urlSeparator[] = 'location:' . $location;
            $separator[] = 'location:' . $location;
            $this->set('location', $location);
        }

        if (!empty($worktype)) {

            $worktype_arr = explode("-", $worktype);

            foreach ($worktype_arr as $work) {
                $condition_worktype[] = "(FIND_IN_SET('" . $work . "',Job.work_type))";
            }
            $condition[] = array('OR' => $condition_worktype);
            $urlSeparator[] = 'worktype:' . $worktype;
            $separator[] = 'worktype:' . $worktype;
            $this->set('worktype', $worktype);
        }

        if (!empty($worktype)) {

            $worktype_arr = explode("-", $worktype);

            foreach ($worktype_arr as $work) {
                $condition_worktype[] = "(FIND_IN_SET('" . $work . "',Job.work_type))";
            }
            $condition[] = array('OR' => $condition_worktype);
            $urlSeparator[] = 'worktype:' . $worktype;
            $separator[] = 'worktype:' . $worktype;
            $this->set('worktype', $worktype);
        }

//        if (isset($designation) && $designation != '') {
//            $separator[] = 'designation:' . urlencode($designation);
//            $designation = str_replace('_', '\_', $designation);
//            $condition[] = " (`Job`.`designation` = $designation) ";
//            $designation = str_replace('\_', '_', $designation);
//            $this->set('designation', $designation);
//        }


        if (!empty($designation)) {
            $designation_arr = explode(",", $designation);
            foreach ($designation_arr as $des) {
                $condition_designation[] = "(FIND_IN_SET('" . $des . "',Job.designation))";
            }
            $condition[] = array('OR' => $condition_designation);
            $urlSeparator[] = 'designation:' . $designation;
            $separator[] = 'designation:' . $designation;
        }

        if ((isset($min_salary) && $min_salary != '') && (isset($max_salary) && $max_salary != '')) {
            $separator[] = 'min_salary:' . urlencode($min_salary);
            $separator[] = 'max_salary:' . urlencode($max_salary);
            $min_salary = str_replace('_', '\_', $min_salary);
            if ($min_salary == $max_salary) {
                $condition[] = " ((Job.min_salary <= $min_salary AND Job.max_salary >= $min_salary)) ";
            } else {
                // $condition[] = " ((Job.min_salary >= $min_salary AND Job.max_salary <= $max_salary) OR (Job.min_salary <= $min_salary )) ";
                $condition[] = " ((Job.min_salary >= $min_salary AND Job.max_salary <= $min_salary) OR (Job.min_salary >= $min_salary AND Job.max_salary <= $max_salary) OR (Job.min_salary = $max_salary ) OR (Job.max_salary = $min_salary )) ";
            }
            $min_salary = str_replace('\_', '_', $min_salary);

            $this->set('min_salary', $min_salary);
            $this->set('max_salary', $max_salary);
        }

        /*  if (isset($min_exp) && $min_exp != '') { //echo 'vdsdv';exit;
          $separator[] = 'min_exp:' . urlencode($min_exp);
          $min_exp = str_replace('_', '\_', $min_exp);
          $condition[] = " ( Job.min_exp >= '$min_exp' ) ";
          $min_exp = str_replace('\_', '_', $min_exp);
          }
          if (isset($max_exp) && $max_exp != '') {
          $separator[] = 'max_exp:' . urlencode($max_exp);
          $max_exp = str_replace('_', '\_', $max_exp);
          $condition[] = " ( Job.max_exp <= '$max_exp' ) ";
          $max_exp = str_replace('\_', '_', $max_exp);
          } */



        if ((isset($min_exp) && $min_exp != '') && (isset($max_exp) && $max_exp != '')) {
            $separator[] = 'min_exp:' . urlencode($min_exp);
            $separator[] = 'max_exp:' . urlencode($max_exp);
            $min_exp = str_replace('_', '\_', $min_exp);

            if ($min_exp == $max_exp) {
                $condition[] = " ((Job.min_exp <= $min_exp AND Job.max_exp >= $min_exp)) ";
            } else {
                //  $condition[] = " ((Job.min_exp >= $min_exp AND Job.max_exp <= $max_exp) OR (Job.min_exp <= $min_exp )  OR (Job.min_exp = $max_exp )) ";

                $condition[] = " ((Job.min_exp >= $min_exp AND Job.max_exp <= $min_exp) OR (Job.min_exp >= $min_exp AND Job.max_exp <= $max_exp) OR (Job.min_exp = $max_exp ) OR (Job.max_exp = $min_exp )) ";
            }
            $min_exp = str_replace('\_', '_', $min_exp);

            $this->set('min_exp', $min_exp);
            $this->set('max_exp', $max_exp);
        }




        /* if (isset($experience) && $experience != '') {
          $experience = floatval($experience);
          $separator[] = 'experience:' . urlencode($experience);
          $experience = str_replace('_', '\_', $experience);
          $total_experience = explode('.', $experience);

          if (!isset($total_experience[1]) && empty($total_experience[1]) && isset($total_experience[0])) {

          $month = 0;
          $condition[] = " (`Job`.`exp_year` =" . addslashes($total_experience[0]) . " AND `Job`.`exp_month` =" . $month . ") ";
          } elseif (empty($total_experience[0]) && !empty($total_experience[1]) && isset($total_experience[1])) {

          $year = 0;
          $condition[] = " (`Job`.`exp_year` =" . addslashes($year) . " AND `Job`.`exp_month` =" . $total_experience[1] . ") ";
          } else {

          $condition[] = " (`Job`.`exp_year` =" . addslashes($total_experience[0]) . " AND `Job`.`exp_month` =" . $total_experience[1] . ") ";
          }

          $experience = str_replace('\_', '_', $experience);
          $this->set('experience', $experience);
          } */


        //$order = 'Job.typeAlter Asc, Job.created Desc';
        //pr($condition);exit;

        $sort = '';
        if (isset($order) && $order != '') {

            $ord = explode(" ", $order);

            $separator[] = 'sort:' . urlencode($ord[0]);
            $sort = str_replace('_', '\_', $order);

            $separator[] = 'order:' . urlencode($ord[1]);
            $order = str_replace('_', '\_', $order);
            $this->set('order', $order);
        }

        //2017-02-18
        $condition[] = array("(Job.category_id != 0)");

        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['Job'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('jobs', $this->paginate('Job'));

        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'jobs';
            $this->render('listing');
        }
    }

    public function listing($categorySlug = '') {

        //Configure::write('debug', 2);


        if (isset($_SESSION['cokkiecount'])) {
            $_SESSION['viewed'] = 1;
        }
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Jobs List', true));
        $this->set('jobs_list', 'active');
        $this->set('find_a_job', 'active');


        $userId = $this->Session->read('user_id');
        if (!empty($categorySlug)) {
            //for url if user enter wrong argument/parameter
            // in url so check the information by follwoing method
            // print_r($categorySlug);exit;
            if (trim($categorySlug) != '') {
                $categorieCount = $this->Category->findBySlug($categorySlug);

                $locationCount = $this->Location->findBySlug($categorySlug);

                $desginationCount = $this->Skill->findBySlug($categorySlug);

                $jobtitle = $this->Job->findBySlug($categorySlug);

                $stored_keywords = $this->Keyword->findBySlugAndApprovalStatusAndStatus($categorySlug, 1, 1);
                // print_r($stored_keywords);
                // print_r($jobtitle);exit;
                // if (empty($categorieCount) && empty($locationCount) && empty($desginationCount)) {
                //     $this->redirect('/homes/error');
                // }
            }
            if (!empty($categorieCount)) {
                $this->request->data['Job']['category_id'] = $this->Category->field('id', array('slug' => $categorySlug));
                $catData = $this->Category->find('first', array('conditions' => array('Category.slug' => $categorySlug)));
                $this->set('catData', $catData);
            }
            if (!empty($locationCount)) {
                $this->request->data['Job']['location'] = $this->Location->field('name', array('slug' => $categorySlug));
                $location = $this->request->data['Job']['location'];
                $catData = $this->Location->find('first', array('conditions' => array('Location.slug' => $categorySlug)));
                $this->set('locData', $catData);
            }
            if (!empty($desginationCount)) {

                // $this->request->data['Job']['designation'] = $this->Skill->field('name', array('slug' => $categorySlug));
                $catData = $this->Skill->find('first', array('conditions' => array('Skill.slug' => $categorySlug)));
                $this->set('degData', $catData);
                // print_r(explode(',',$catData['Skill']['name']));exit;
                // print_r($catData['Skill']['name']);exit;
                $skill = $catData['Skill']['name'];
                $this->set('skill', $skill);
            }
            if (!empty($jobtitle)) {
                $this->request->data['Job']['keyword'] = $this->Job->field('title', array('slug' => $categorySlug));
                $keyword = $this->Job->field('title', array('slug' => $categorySlug));
                $this->set('keyword', $keyword);
            }
            if (!empty($stored_keywords)) {
                $this->request->data['Job']['keyword'] = $this->Keyword->field('name', array('slug' => $categorySlug));
                $keyword = $this->Keyword->field('name', array('slug' => $categorySlug));
                $this->set('keyword', $keyword);
            }
        } else {
            $metaData = classRegistry::init('Admin')->find('first', array('conditions' => array('Admin.id' => 1)));
            $this->set('metaData', $metaData);
        }
        $this->set('slug', $categorySlug);

//        if (empty($categorySlug)) {
//            $metaData = classRegistry::init('Admin')->find('first', array('conditions' => array('Admin.id' => 1)));
//            $this->set('metaData', $metaData);
//        }
        // $category['category']['id'] = $this->Category->findByName($categoryName);
        //find category Id by slug
        //for url if user enter wrong argument/parameter
        // in url so check the information by follwoing method
//        if (trim($categoryId) != '') {
//            $categorieCount = $this->Category->findById($categoryId);
//            if (empty($categorieCount)) {
//                $this->redirect('/homes/error');
//            }
//        }

        if ($userId) {
            $showOldImages = $this->Certificate->find('list', array('conditions' => array('Certificate.user_id' => $userId), 'fields' => array('id', 'document'), 'order' => 'Certificate.id ASC'));
            $this->set('showOldImages', $showOldImages);
        }


        $categories = $this->Category->getCategoryList();
        $this->set('categories', $categories);

        $subcategories = array();
        if (isset($this->data['Job']['category_id']) && $this->data['Job']['category_id'] != '') {
            $this->set('subcategories', $this->Category->getSubCategoryList($this->data['Job']['category_id']));
        } else {
            $this->set('subcategories', $subcategories);
        }

//        $stateList = $states = $this->State->find('list', array('conditions' => array('State.status' => '1', 'State.country_id' => 1),
//            'fields' => array('State.id', 'State.state_name'), 'order' => 'State.state_name asc'));
//        $this->set('stateList', $stateList);
//        $cityList = array();
//
//        $this->set('cityList', $cityList);
        // get skills from skill table
        $skillDesList = $this->Skill->find('list', array('conditions' => array('Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('skillDesList', $skillDesList);


        // get skills from skill table
        $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('skillList', $skillList);

        // get designations from skill table
        $designationlList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Designation', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('designationlList', $designationlList);

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        //$condition = array('Job.status' => 1, 'Job.payment_status' => 2, 'Job.expire_time >=' => time());
        $condition = array('Job.status' => 1, 'Job.expire_time >=' => time());
        //$condition = array('Job.status' => 1, 'Job.payment_status' => 2);

        $separator = array();
        $urlSeparator = array();

        $order = '';

        if (!empty($this->data)) {

            if (isset($this->data['Job']['created']) && $this->data['Job']['created'] != '') {
                $order = 'Job.' . $this->data['Job']['created'];
            }

            if (isset($this->data['Job']['keyword']) && $this->data['Job']['keyword'] != '') {
                $keyword = trim($this->data['Job']['keyword']);
                $keywordId = $this->Keyword->field('id', array('Keyword.name' => $keyword, 'Keyword.type' => 'Search'));
                if (!$keywordId) {
                    $this->request->data['Keyword']['name'] = $keyword;
                    $this->request->data['Keyword']['slug'] = $this->stringToSlugUnique($keyword, 'Keyword');
                    $this->request->data['Keyword']['status'] = '1';
                    $this->request->data['Keyword']['approval_status'] = '0';
                    $this->request->data['Keyword']['type'] = 'Search';
                    $this->Keyword->save($this->data);
                }
            }

            if (isset($this->data['Job']['category_id']) && !empty($this->data['Job']['category_id'])) {
                if (is_array($this->data['Job']['category_id'])) {
                    $category_id = implode('-', $this->data['Job']['category_id']);
                } else {
                    $category_id = $this->data['Job']['category_id'];
                }
            }

            if (isset($this->data['Job']['searchkey']) && $this->data['Job']['searchkey'] != '') {
                if (is_array($this->data['Job']['searchkey'])) {
                    $searchkey = implode('-', $this->data['Job']['searchkey']);
                } else {
                    $searchkey = $this->data['Job']['searchkey'];
                }
            }

            if (isset($this->data['Job']['subcategory_id']) && !empty($this->data['Job']['subcategory_id']) && count($this->data['Job']['subcategory_id']) > 0) {
                if (is_array($this->data['Job']['subcategory_id'])) {
                    $subcategory_id = implode('-', $this->data['Job']['subcategory_id']);
                } else {
                    $subcategory_id = $this->data['Job']['subcategory_id'];
                }
            }

            if (isset($this->data['Job']['location']) && !empty($this->data['Job']['location'])) {
                $location = trim($this->data['Job']['location']);
                // $location = str_replace(',', '', $location);
//                if (is_array($this->data['Job']['location'])) {
//                    $location = implode('-', str_replace(',', '', $this->data['Job']['location']));
//                } else {
//                    $location = str_replace(',', '', $this->data['Job']['location']);
//                }
            }
            if (isset($this->data['Job']['radius']) && $this->data['Job']['radius'] != '') {
                $radius = trim($this->data['Job']['radius']);
            }

            if (isset($this->data['Job']['work_type']) && !empty($this->data['Job']['work_type']) && count($this->data['Job']['work_type']) > 0) {
                if (is_array($this->data['Job']['work_type'])) {
                    $worktype = implode('-', $this->data['Job']['work_type']);
                } else {
                    $worktype = $this->data['Job']['work_type'];
                }
            }


            if (!empty($this->data['Job']['skill'])) {
                //$skill = implode(",", $this->data['Job']['skill']);
                $skill = $this->data['Job']['skill'];
                //$skill = addslashes($skill);
                $this->set('skill', $this->data['Job']['skill']);
            }

            if (!empty($this->data['Job']['designation'])) {

                $designation = addslashes($this->data['Job']['designation']);
                $this->set('designation', $this->data['Job']['designation']);
            }
            if (isset($this->data['Job']['salary']) && $this->data['Job']['salary'] != '') {
                $salary = trim($this->data['Job']['salary']);
            }
//            if (isset($this->data['Job']['max_salary']) && $this->data['Job']['max_salary'] != '') {
//                $max_salary = trim($this->data['Job']['max_salary']);
//            }

            if (isset($this->data['Job']['exp']) && $this->data['Job']['exp'] != '') {
                $exp = $this->data['Job']['exp'];
                $expArray = explode('-', $exp);
                $min_exp = $expArray[0];
                $max_exp = $expArray[1];
            }

            if (isset($this->data['Job']['min_exp']) && $this->data['Job']['min_exp'] != '') {
                $min_exp = trim($this->data['Job']['min_exp']);
            }
            if (isset($this->data['Job']['max_exp']) && $this->data['Job']['max_exp'] != '') {
                $max_exp = trim($this->data['Job']['max_exp']);
            }
            if (isset($this->data['Job']['lat']) && $this->data['Job']['lat'] != '') {
                $lat = trim($this->data['Job']['lat']);
            }
            if (isset($this->data['Job']['long']) && $this->data['Job']['long'] != '') {
                $long = trim($this->data['Job']['long']);
            }
        } elseif (!empty($this->params)) {

            if (isset($this->params['named']['keyword']) && $this->params['named']['keyword'] != '') {
                $keyword = urldecode(trim($this->params['named']['keyword']));
            }
            if (isset($this->params['named']['radius']) && $this->params['named']['radius'] != '') {
                $radius = urldecode(trim($this->params['named']['radius']));
            }
            if (isset($this->params['named']['category_id']) && $this->params['named']['category_id'] != '') {
                $category_id = trim($this->params['named']['category_id']);
            }
            if (isset($this->params['named']['subcategory_id']) && $this->params['named']['subcategory_id'] != '') {
                $subcategory_id = trim($this->params['named']['subcategory_id']);
            }

            if (isset($this->params['named']['location']) && $this->params['named']['location'] != '') {
                $location = urldecode(trim($this->params['named']['location']));
            } else if (isset($_SESSION['locationid']) && $_SESSION['locationid'] > 0) {
                $location = $_SESSION['locationid'];
            }

            if (isset($this->params['named']['work_type']) && $this->params['named']['work_type'] != '') {
                $worktype = urldecode(trim($this->params['named']['work_type']));
            }

            if (isset($this->params['named']['skill']) && $this->params['named']['skill'] != '') {
                $skill = trim($this->params['named']['skill']);
                $skill = addslashes($skill);
                $this->set('skill', $skill);
            }

            if (isset($this->params['named']['designation']) && $this->params['named']['designation'] != '') {
                $designation = trim($this->params['named']['designation']);
                $designation = addslashes($designation);
                $this->set('designation', $designation);

                $catData = $this->Skill->find('first', array('conditions' => array('Skill.name' => $designation)));
                $this->set('degData', $catData);
            }
            if (isset($this->params['named']['salary']) && $this->params['named']['salary'] != '') {
                $salary = urldecode(trim($this->params['named']['salary']));
            }
            if (isset($this->params['named']['max_salary']) && $this->params['named']['max_salary'] != '') {
                $max_salary = urldecode(trim($this->params['named']['max_salary']));
            }
            if (isset($this->params['named']['min_exp']) && $this->params['named']['min_exp'] != '') {
                $min_exp = urldecode(trim($this->params['named']['min_exp']));
            }
            if (isset($this->params['named']['max_exp']) && $this->params['named']['max_exp'] != '') {
                $max_exp = urldecode(trim($this->params['named']['max_exp']));
            }
            if (isset($this->params['named']['lat']) && $this->params['named']['lat'] != '') {
                $lat = urldecode(trim($this->params['named']['lat']));
            }
            if (isset($this->params['named']['long']) && $this->params['named']['long'] != '') {
                $long = urldecode(trim($this->params['named']['long']));
            }
            if (isset($this->params['named']['searchkey']) && $this->params['named']['searchkey'] != '') {
                $searchkey = urldecode(trim($this->params['named']['searchkey']));
            }

//            if (isset($this->params['named']['order']) && $this->params['named']['order'] != '') {
//                $order = urldecode(trim($this->params['named']['order']));
//            }
        }



        if (isset($keyword) && $keyword != '') {
            $separator[] = 'keyword:' . urlencode($keyword);
            $keyword = str_replace('_', '\_', $keyword);
            $condition[] = " (`Job`.`title` LIKE '%" . addslashes($keyword) . "%' OR `Job`.`description` LIKE '%" . addslashes($keyword) . "%' OR `Job`.`company_name` LIKE '%" . addslashes($keyword) . "%' ) ";
            $keyword = str_replace('\_', '_', $keyword);
            $this->set('keyword', $keyword);
            // print_r('asdasd');exit;
        }


        if (isset($searchkey) && !empty($searchkey)) {

            $searchkey_arr = explode("-", $searchkey);

            foreach ($searchkey_arr as $id) {
                $condition_search[] = "(FIND_IN_SET('" . $id . "',Job.skill) OR FIND_IN_SET('" . $id . "',Job.designation) )";
            }
            $condition[] = array('OR' => $condition_search);
            $urlSeparator[] = 'searchkey:' . $searchkey;
            $separator[] = 'searchkey:' . $searchkey;
            $this->set('searchkey', $searchkey);
        }
        // pr($condition); exit; 

        if (isset($category_id) && $category_id != '') {
            $this->set('topcate', $category_id);
            $separator[] = 'category_id:' . $category_id;
            $category_idCondtionArray = explode('-', $category_id);

            if (isset($subcategory_id) && $subcategory_id != '') {
                $this->set('subcate', $subcategory_id);
                $subcategory_idCondtionArray = explode('-', $subcategory_id);
                foreach ($subcategory_idCondtionArray as $subMain) {
                    $subMainVal = $this->Category->field('parent_id', array('Category.id' => $subMain));
                    if (($key = array_search($subMainVal, $category_idCondtionArray)) !== false) {
                        unset($category_idCondtionArray[$key]);
                    }
                    //   pr($category_idCondtionArray);
                }
                // pr($category_idCondtionArray);
                if ($category_idCondtionArray) {
                    $subcategory_idCondtion = implode(',', $subcategory_idCondtionArray);
                    $separator[] = 'subcategory_id:' . $subcategory_id;

                    $category_idCondtion = implode(',', $category_idCondtionArray);
                    $condition[] = " (Job.category_id IN ($category_idCondtion) OR Job.subcategory_id IN ($subcategory_idCondtion ) )";
                } else {
                    $subcategory_idCondtion = implode(',', $subcategory_idCondtionArray);
                    $condition[] = " (Job.subcategory_id IN ($subcategory_idCondtion ))";
                    $separator[] = 'subcategory_id:' . $subcategory_id;
                }
            } else {
                $category_idCondtion = implode(',', $category_idCondtionArray);
                $condition[] = " (Job.category_id IN ($category_idCondtion))";
            }
        }
        /* if (isset($categoryId) && $categoryId != '') {
          $separator[] = 'categoryId:' . urlencode($categoryId);
          $categoryId = str_replace('_', '\_', $categoryId);
          $condition[] = " (`Job`.`category_id` = $categoryId) ";
          $categoryId = str_replace('\_', '_', $categoryId);
          $this->set('categoryId', $categoryId);
          }
          if (isset($subcategoryId) && $subcategoryId != '') {
          $separator[] = 'subcategoryId:' . urlencode($subcategoryId);
          $subcategoryId = str_replace('_', '\_', $subcategoryId);
          $condition[] = "( FIND_IN_SET('$subcategoryId',`Job`.`subcategory_id`)) ";
          $subcategoryId = str_replace('\_', '_', $subcategoryId);
          $this->set('subcategoryId', $subcategoryId);
          }
          if (isset($skill) && $skill != '') {
          $separator[] = 'skill:' . urlencode($skill);
          $skill = str_replace('_', '\_', $skill);
          $condition[] = "( FIND_IN_SET('$skill',`Job`.`skill`)) ";
          $skill = str_replace('\_', '_', $skill);
          $this->set('skill', $skill);
          } */

        if (!empty($skill)) {
            // print_r($skill);exit;
            $skill_arr = explode(",", $skill);

//            foreach ($skill_arr as $skil) {
//                $condition_skill[] = "(FIND_IN_SET('" . $skil . "',Job.skill))";
//            }
            $keyword = array();
            foreach ($skill_arr as $skillhave) {
                $cbd[] = '(Skill.name = "' . $skillhave . '")';


                $skillDetail = $this->Skill->find('first', array('conditions' => $cbd));
                // print_r($skillDetail['Skill']['id']);exit;

                if ($skillDetail) {

                    $idshave = $skillDetail['Skill']['id'];
                    $condition_skill[] = "(FIND_IN_SET('" . $idshave . "',Job.skill))";
                    //$condition_skill[] = '(Job.skill LIKE "%'.$idshave.'%")';
                    // $condition_skill[] = '(Job.skill = "'.$idshave.'")';
                } else {
                    if ($skillhave != '') {
                        $condition_skill[] = "(Skill.name LIKE '%" . addslashes($skillhave) . "%')";
                    }
                }
            }

            $condition[] = array('OR' => $condition_skill);
            $urlSeparator[] = 'skill:' . $skill;
            $separator[] = 'skill:' . $skill;
        }

        if (!empty($location)) {

//            $location_arr = explode("-", $location);
//            if (count($location_arr) > 1) {
//                foreach ($location_arr as $loc) {
//                    $condition_location[] = "(FIND_IN_SET('" . $loc . "',Job.location))";
//                }
//                $condition[] = array('OR' => $condition_location);
//            } else {
//                $condition[] = "(FIND_IN_SET('" . $location . "',Job.location))";
//            }
//            $location_arr = explode("-", $location);
            // $condition[] = array("MATCH(job_city) AGAINST ('$location' IN BOOLEAN MODE)");
            //    $condition[] = " (`Job`.`job_city` like '%" . addslashes($location) . "%') ";

            $urlSeparator[] = 'location:' . $location;
            $separator[] = 'location:' . $location;
            $this->set('location', $location);
            $location = str_replace('_', '\_', $location);
            // print_r($location);exit;
            $this->Job->virtualFields['relevance'] = "MATCH(`Job`.`job_city`) AGAINST ('$location' IN BOOLEAN MODE) ";
            // $condition[] = array("MATCH(`Job`.`job_city`) AGAINST ('$location' IN BOOLEAN MODE) ");
            $condition[] = " (`Job`.`job_city` like '%" . addslashes($location) . "%') ";

            $location = str_replace('\_', '_', $location);
            $order = 'relevance Desc';
        }

        if (!empty($worktype)) {

            $worktype_arr = explode("-", $worktype);

            foreach ($worktype_arr as $work) {
                $condition_worktype[] = "(FIND_IN_SET('" . $work . "',Job.work_type))";
            }
            $condition[] = array('OR' => $condition_worktype);
            $urlSeparator[] = 'work_type:' . $worktype;
            $separator[] = 'work_type:' . $worktype;
            $this->set('worktype', $worktype);
        }



//        if (isset($designation) && $designation != '') {
//            $separator[] = 'designation:' . urlencode($designation);
//            $designation = str_replace('_', '\_', $designation);
//            $condition[] = " (`Job`.`designation` = $designation) ";
//            $designation = str_replace('\_', '_', $designation);
//            $this->set('designation', $designation);
//        }


        if (!empty($designation)) {
            $designation_arr = explode(",", $designation);
            foreach ($designation_arr as $des) {
                $cbsd[] = '(Designation.name = "' . $des . '")';
                $dDetail = $this->Designation->find('first', array('conditions' => $cbsd));
                if ($dDetail) {
                    $idshave = $dDetail['Designation']['id'];
                    $condition_designation[] = '(Job.designation LIKE "%' . $idshave . '%")';
                } else {
                    if ($des != '') {
                        //$condition_designation[] = "(FIND_IN_SET('" . $des . "',Job.designation))";
                        $condition_designation[] = "(Designation.name LIKE '%" . addslashes($des) . "%')";
                    }
                }
            }
            $condition[] = array('OR' => $condition_designation);
            $urlSeparator[] = 'designation:' . $designation;
            $separator[] = 'designation:' . $designation;
        }
        /* if (isset($min_salary) && $min_salary != '') { //echo 'vdsdv';exit;
          $separator[] = 'min_salary:' . urlencode($min_salary);
          $min_salary = str_replace('_', '\_', $min_salary);
          $condition[] = " ( Job.min_salary >= '$min_salary' ) ";
          $min_salary = str_replace('\_', '_', $min_salary);
          }
          if (isset($max_salary) && $max_salary != '') {
          $separator[] = 'max_salary:' . urlencode($max_salary);
          $max_salary = str_replace('_', '\_', $max_salary);
          $condition[] = " ( Job.max_salary <= '$max_salary' ) ";
          $max_salary = str_replace('\_', '_', $max_salary);
          } */

        if ((isset($salary) && $salary != '')) {
            $separator[] = 'salary:' . urlencode($salary);

            $salary = str_replace('_', '\_', $salary);
            $expsalary = explode('-', $salary);
            $min_salary = $expsalary[0];
            $max_salary = $expsalary[1];

            $condition[] = " ((Job.min_salary >= $min_salary AND Job.max_salary <= $min_salary) OR (Job.min_salary >= $min_salary AND Job.max_salary <= $max_salary) OR (Job.min_salary = $max_salary ) OR (Job.max_salary = $min_salary )) ";

            $salary = str_replace('\_', '_', $salary);

            $this->set('salary', $salary);
            // $this->set('max_salary', $max_salary);
        }

        /* if (isset($min_exp) && $min_exp != '') { //echo 'vdsdv';exit;
          $separator[] = 'min_exp:' . urlencode($min_exp);
          $min_exp = str_replace('_', '\_', $min_exp);
          $condition[] = " (Job.min_exp >= '$min_exp' AND (Job.max_exp >= '$min_exp' AND Job.max_exp <= '$max_exp')) ";
          $min_exp = str_replace('\_', '_', $min_exp);
          $this->set('min_exp', $min_exp);
          }
          if (isset($max_exp) && $max_exp != '') {
          $separator[] = 'max_exp:' . urlencode($max_exp);
          $max_exp = str_replace('_', '\_', $max_exp);
          $condition[] = " ( Job.max_exp <= '$max_exp') ";
          $max_exp = str_replace('\_', '_', $max_exp);
          $this->set('max_exp', $max_exp);
          } */

        if ((isset($min_exp) && $min_exp != '') && (isset($max_exp) && $max_exp != '')) {
            $separator[] = 'min_exp:' . urlencode($min_exp);
            $separator[] = 'max_exp:' . urlencode($max_exp);
            $min_exp = str_replace('_', '\_', $min_exp);

            if ($min_exp == $max_exp) {
                $condition[] = " ((Job.min_exp <= $min_exp AND Job.max_exp >= $min_exp)) ";
            } else {
                // $condition[] = " ((Job.min_exp >= $min_exp AND Job.max_exp <= $min_exp) OR (Job.min_exp >= $min_exp AND Job.max_exp <= $max_exp) OR (Job.min_exp = $max_exp ) OR (Job.max_exp = $min_exp )) ";
                $condition[] = " ((Job.min_exp >= $min_exp AND Job.max_exp <= $max_exp)) ";
            }
            $min_exp = str_replace('\_', '_', $min_exp);

            $this->set('min_exp', $min_exp);
            $this->set('max_exp', $max_exp);
        }

//        $sort = '';
//        if (isset($order) && $order != '') {
//
//            $ord = explode(" ", $order);
//
//            $separator[] = 'sort:' . urlencode($ord[0]);
//            $sort = str_replace('_', '\_', $order);
//
//            $separator[] = 'order:' . urlencode($ord[1]);
//            $order = str_replace('_', '\_', $order);
//            $this->set('order', $order);
//        }
        //2017-02-18
//        $condition[] = array("(Job.category_id != 0)");
//        if (!empty($radius) && !empty($lat) && !empty($long)) {
//            $latitude = $lat;
//            $longitude = $long;
////            $this->Job->virtualFields['distance'] = "(((acos(sin(($latitude* pi()/ 180)) * sin((User.lat * pi()/ 180))+ cos(($latitude * pi()/ 180)) * cos((User.lat * pi()/ 180)) * cos((($longitude - User.long) * pi()/ 180)))))* 60 * 1.1515 * 1.609344)";
//            $condition[] = array("(((acos(sin(($latitude* pi()/ 180)) * sin((Job.lat * pi()/ 180))+ cos(($latitude * pi()/ 180)) * cos((Job.lat * pi()/ 180)) * cos((($longitude - Job.long) * pi()/ 180)))))* 60 * 1.1515 * 1.609344)  < $radius");
//            // $condition[] = array("MATCH(job_city) AGAINST ('$location' IN BOOLEAN MODE)");
////             $condition[] = " (Job__distance < $radius ) ";
//
//            $separator[] = 'radius:' . $radius;
//            $this->set('radius', $radius);
//            $separator[] = 'lat:' . $lat;
//            $this->set('lat', $lat);
//            $separator[] = 'long:' . $long;
//            $this->set('long', $long);
//        }
        if (empty($order))
            $order = 'Job.id Desc, Job.created Desc';
        // echo "<pre>"; print_r($order); //exit;
        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        //    echo "<pre>"; print_r($condition); exit;
        $this->paginate['Job'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
//        echo '<pre>';
        //    print_r($this->paginate('Job'));exit;
        $this->set('jobs', $this->paginate('Job'));

        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'jobs';
            $this->render('listing');
        }
    }

    public function detail($catslug = null, $slug = null) {
        $this->layout = "client";
        $this->set('job_list', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Job Detail");

        $userId = $this->Session->read('user_id');

        if ($slug) {
            $detailSlug = explode('.', $slug);
        }

        $jobdetails = $this->Job->findBySlug($detailSlug[0]);


        //    pr($jobdetails);
//        echo '<pre>';
//        print_r($jobdetails);
//        exit;
        if (empty($jobdetails)) {

            $this->redirect('/homes/error');
        } elseif ($jobdetails['Category']['slug'] !== $catslug) {

            $this->redirect('/homes/error');
        }
        $this->set('jobdetails', $jobdetails);
        $cat_id = $jobdetails['Job']['category_id'];
        $job_id = $jobdetails['Job']['id'];
        $time = time();
        $jobcond = array("Job.status = 1 AND Job.id != $job_id AND Job.category_id = $cat_id AND Job.expire_time >= $time ");
        $relevantJobList = $this->Job->find('all', array('conditions' => $jobcond, 'order' => 'Job.created Desc', 'limit' => '6'));
        $this->set('relevantJobList', $relevantJobList);
//        echo '<pre>';
//        print_r($latestJobList);
//        exit;
        if ($userId) {
            $showOldImages = $this->Certificate->find('list', array('conditions' => array('Certificate.user_id' => $userId), 'fields' => array('id', 'document'), 'order' => 'Certificate.id ASC'));
            $this->set('showOldImages', $showOldImages);
        }
    }

    public function jobApply($slug = null) {

        $this->layout = "client";
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Apply Job', true));
        //$this->userLoginCheck();
        $msgString = '';
        $this->applyJobLoginCheck();
        $this->candidateAccess();
        $user_id = $this->Session->read('user_id');

        $job_id = $this->Job->field('id', array('Job.slug' => $slug));
        if ($job_id == '') {
            $msgString .= "- Invalid URL.<br>";
        }


        $jobdetails = $this->Job->findBySlug($slug);

        // echo"<pre>"; print_r($jobdetails); exit;
        //$job_owner_id = $this->Job->field('user_id', array('Job.slug' => $slug));
        //$owner_all_job_ids = $this->Job->find('list', array('conditions' => array('Job.user_id' => $job_owner_id), 'fields' => array('id')));

        $checkStatus = $this->JobApply->find('first', array('conditions' => array('JobApply.user_id' => $user_id, 'JobApply.job_id' => $job_id)));
        if (!empty($checkStatus)) {
            $msgString .= __d('controller', 'You already applied for this job.', true);
        }

        //  pr($this->data); exit;

        if ($this->data) {

            if (isset($msgString) && $msgString != '') {

                echo json_encode(array('success' => 0, 'message' => $msgString));
                exit;
//                $this->Session->write('error_msg', $msgString);
//                // $this->redirect('/jobs/detail/' . $slug);
//                //$this->redirect('/jobs/detail/' . $jobdetails['Category']['slug'] . '/' . $slug . '.html');
//                $this->redirect(array('controller' => 'jobs', 'action' => 'detail', 'cat' => $jobdetails['Category']['slug'], 'slug' => $slug, 'ext' => 'html'));
            } else {

                /* $candidateCount = $this->JobApply->find('count', array('conditions' => array('JobApply.user_id' => $user_id, 'JobApply.job_id' => $owner_all_job_ids)));
                  if ($candidateCount) {
                  $this->request->data['JobApply']['new_status'] = 0;
                  } else {
                  $this->request->data['JobApply']['new_status'] = 1;
                  } */

                $this->request->data['JobApply']['new_status'] = 1;
                $this->request->data['JobApply']['status'] = 1;
                $this->request->data['JobApply']['apply_status'] = 'active';
                $this->request->data['JobApply']['user_id'] = $user_id;
                $this->request->data['JobApply']['job_id'] = $job_id;

                if (!empty($this->data['JobApply']['cover_letter'])) {
                    $this->request->data['JobApply']['cover_letter_id'] = $this->data['JobApply']['cover_letter'];
                } else {
                    $this->request->data['JobApply']['cover_letter_id'] = " ";
                }
                $isAbleToJob = $this->Plan->checkPlanFeature($user_id, 4);
                $this->request->data['JobApply']['user_plan_id'] = $isAbleToJob['user_plan_id'];

                $this->request->data['JobApply']['attachment_ids'] = '';
                //$this->request->data['JobApply']['attachment_ids'] = implode(",", $this->data['JobApply']['attachment_ids']);


                if ($this->JobApply->save($this->data)) {
                    $userInfo = $this->User->findByid($user_id);
                    $jobInfo = $this->Job->findByid($job_id);
                    $recruiterInfo = $this->User->findByid($jobInfo['Job']['user_id']);

                    $jobTitle = $jobInfo["Job"]["title"];
                    $email = $userInfo["User"]["email_address"];
                    $userName = ucfirst($userInfo["User"]["first_name"]);

                    $this->Email->to = $email;
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='15'"));
                    //$link = HTTP_PATH . "/jobs/detail/" . $jobdetails['Category']['slug'] . '/' . $jobInfo['Job']['slug'] . 'html';
                    $link = HTTP_PATH . "/" . $jobInfo['Category']['slug'] . '/' . $jobInfo['Job']['slug'] . '.html';

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];


                    $toSubArray = array('[!username!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!activelink!]');
                    $fromSubArray = array($userName, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                    $this->Email->subject = $subjectToSend;

                    //$this->Email->subject = $emailtemplateMessage['Emailtemplate']['subject'];
                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!activelink!]');
                    $fromRepArray = array($userName, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                    $this->Email->reset();

                    // send mail to the recruiter    
                    $recruiterEmail = $recruiterInfo["User"]["email_address"];
                    $recruiterName = ucfirst($recruiterInfo["User"]["first_name"]);
//                    $recruiterName = 'Admin';
                    $this->Email->to = $recruiterEmail;
                    $adminDetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => 1)));
                    $adminEmail = $adminDetail['Admin']['email'];

//                    $this->Email->to = $adminEmail;
                    
                     $showOldImages = $this->Certificate->find('list', array('conditions' => array('Certificate.user_id' => $user_id),'fields'=>array('document')));
                    $mailFileArray = array();
                    if ($showOldImages) {
                        foreach ($showOldImages as $image) {
                            $mailFileArray[$image] = UPLOAD_CERTIFICATE_PATH . $image;
                        }
                    }

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='16'"));
                    $link = HTTP_PATH . "/candidates/profile/" . $userInfo['User']['slug'];

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];

                    $this->Email->subject = $emailtemplateMessage['Emailtemplate'][$subjectField];
                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . MAIL_FROM . '">' . MAIL_FROM . '</a>';
                    $toRepArray = array('[!username!]', '[!job_title!]', '[!jobseeker_name!]', '[!jobseeker_email!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!activelink!]');
                    $fromRepArray = array($recruiterName, $jobTitle, $userName, $email, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                      if (!empty($mailFileArray))
                    $this->Email->attachments = $mailFileArray;
                    $this->Email->sendAs = 'html';                   
                    $this->Email->send();
                    $this->Email->reset();

                    // send mail to the recruiter    
                    $recruiterEmail = $recruiterInfo["User"]["email_address"];
//                  $recruiterName = ucfirst($recruiterInfo["User"]["first_name"]);
                    $recruiterName = 'Admin';
//                  $this->Email->to = $recruiterEmail;
                    $adminDetail = $this->Admin->find('first', array('conditions' => array('Admin.id' => 1)));
                    $adminEmail = $adminDetail['Admin']['email'];

                    $this->Email->to = $adminEmail;

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='16'"));
                    $link = HTTP_PATH . "/candidates/profile/" . $userInfo['User']['slug'];

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];

                    $this->Email->subject = $emailtemplateMessage['Emailtemplate'][$subjectField];
                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . MAIL_FROM . '">' . MAIL_FROM . '</a>';
                    $toRepArray = array('[!username!]', '[!job_title!]', '[!jobseeker_name!]', '[!jobseeker_email!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!activelink!]');
                    $fromRepArray = array($recruiterName, $jobTitle, $userName, $email, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();

                    echo json_encode(array('success' => 1, 'message' => __d('controller', 'Your job application is successfully posted. We will contact you soon.', true)));
                    exit;
                    
                     $this->Session->write('success_msg', __d('controller', 'Your job application is successfully posted. We will contact you soon.', true));
                    $this->redirect('/' . $jobdetails['Category']['slug'] . '/' . $slug . '.html');
                    
//                    $this->Session->write('success_msg', 'Your job application is successfully posted. We will contact you soon.');
//
//                    //  $this->redirect('/jobs/detail/' . $slug);
//                    $this->redirect(array('controller' => 'jobs', 'action' => 'listing'));
                }
            }
        }
    }

    public function jobApplyDetail($slug = null) {

        $this->layout = "client";
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Apply Job');
        //$this->userLoginCheck();
        $msgString = '';
        $this->applyJobLoginCheck();
        $this->candidateAccess();

        $user_id = $this->Session->read('user_id');

        $job_id = $this->Job->field('id', array('Job.slug' => $slug));
        if ($job_id == '') {
            $msgString .= __d('controller', 'Invalid URL.', true) . "<br>";
        }

        $jobdetails = $this->Job->findBySlug($slug);
        // echo"<pre>"; print_r($jobdetails); exit;
        //$job_owner_id = $this->Job->field('user_id', array('Job.slug' => $slug));
        //$owner_all_job_ids = $this->Job->find('list', array('conditions' => array('Job.user_id' => $job_owner_id), 'fields' => array('id')));

        $checkStatus = $this->JobApply->find('first', array('conditions' => array('JobApply.user_id' => $user_id, 'JobApply.job_id' => $job_id)));
        if (!empty($checkStatus)) {
            $msgString .= "- You already applied for this job";
        }

        //  pr($this->data); exit;

        if ($this->data) {

            if (isset($msgString) && $msgString != '') {
                echo json_encode(array('success' => 0, 'message' => $msgString));
                exit;
//                $this->Session->write('error_msg', $msgString);
//                // $this->redirect('/jobs/detail/' . $slug);
//                //$this->redirect('/jobs/detail/' . $jobdetails['Category']['slug'] . '/' . $slug . '.html');
//                $this->redirect(array('controller' => 'jobs', 'action' => 'detail', 'cat' => $jobdetails['Category']['slug'], 'slug' => $slug, 'ext' => 'html'));
            } else {
                /* $candidateCount = $this->JobApply->find('count', array('conditions' => array('JobApply.user_id' => $user_id, 'JobApply.job_id' => $owner_all_job_ids)));
                  if ($candidateCount) {
                  $this->request->data['JobApply']['new_status'] = 0;
                  } else {
                  $this->request->data['JobApply']['new_status'] = 1;
                  } */

                $this->request->data['JobApply']['new_status'] = 1;
                $this->request->data['JobApply']['status'] = 1;
                $this->request->data['JobApply']['apply_status'] = 'active';
                $this->request->data['JobApply']['user_id'] = $user_id;
                $this->request->data['JobApply']['job_id'] = $job_id;

                if (!empty($this->data['JobApply']['cover_letter'])) {
                    $this->request->data['JobApply']['cover_letter_id'] = $this->data['JobApply']['cover_letter'];
                } else {
                    $this->request->data['JobApply']['cover_letter_id'] = " ";
                }

                $isAbleToJob = $this->Plan->checkPlanFeature($user_id, 4);
                $this->request->data['JobApply']['user_plan_id'] = $isAbleToJob['user_plan_id'];

                $this->request->data['JobApply']['attachment_ids'] = '';
                //$this->request->data['JobApply']['attachment_ids'] = implode(",", $this->data['JobApply']['attachment_ids']);

                if ($this->JobApply->save($this->data)) {
                    $userInfo = $this->User->findByid($user_id);
                    $jobInfo = $this->Job->findByid($job_id);
                    $recruiterInfo = $this->User->findByid($jobInfo['Job']['user_id']);

                    $jobTitle = $jobInfo["Job"]["title"];
                    $email = $userInfo["User"]["email_address"];
                    $userName = ucfirst($userInfo["User"]["first_name"]);

                    $this->Email->to = $email;
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='15'"));
                    //$link = HTTP_PATH . "/jobs/detail/" . $jobdetails['Category']['slug'] . '/' . $jobInfo['Job']['slug'] . 'html';
                    $link = HTTP_PATH . "/" . $jobdetails['Category']['slug'] . '/' . $jobInfo['Job']['slug'] . '.html';

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];

                    $toSubArray = array('[!username!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!activelink!]');
                    $fromSubArray = array($userName, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                    $this->Email->subject = $subjectToSend;

                    //$this->Email->subject = $emailtemplateMessage['Emailtemplate']['subject'];
                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";

                    $toRepArray = array('[!username!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!activelink!]');
                    $fromRepArray = array($userName, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                    $this->Email->reset();


                    $showOldImages = $this->Certificate->find('list', array('conditions' => array('Certificate.user_id' => $user_id),'fields'=>array('document')));
                    $mailFileArray = array();
                    if ($showOldImages) {
                        foreach ($showOldImages as $image) {
                            $mailFileArray[$image] = UPLOAD_CERTIFICATE_PATH . $image;
                        }
                    }

                    // send mail to the recruiter    
                    $recruiterEmail = $recruiterInfo["User"]["email_address"];
                    $recruiterName = ucfirst($recruiterInfo["User"]["first_name"]);

                    $this->Email->to = $recruiterEmail;
                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='16'"));
                    $link = HTTP_PATH . "/candidates/profile/" . $userInfo['User']['slug'];

                    $emData = $this->Emailtemplate->getSubjectLang();
                    $subjectField = $emData['subject'];
                    $templateField = $emData['template'];

                    $this->Email->subject = $emailtemplateMessage['Emailtemplate'][$subjectField];
                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . MAIL_FROM . '">' . MAIL_FROM . '</a>';
                    $toRepArray = array('[!username!]', '[!job_title!]', '[!jobseeker_name!]', '[!jobseeker_email!]', '[!DATE!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!SITE_LINK!]', '[!SITE_URL!]', '[!activelink!]');
                    $fromRepArray = array($recruiterName, $jobTitle, $userName, $email, $currentYear, HTTP_PATH, $site_title, $sitelink, $site_url, $link);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    if (!empty($mailFileArray))
                    $this->Email->attachments = $mailFileArray;
                    $this->Email->sendAs = 'html';
                    $this->Email->send();

                    // echo json_encode(array('success' => 1, 'message' => __d('controller', 'Your job application is successfully posted. We will contact you soon.', true)));
                    // exit;
                    
                       $this->Session->write('success_msg', __d('controller', 'Your job application is successfully posted. We will contact you soon.', true));
                    $this->redirect('/' . $jobdetails['Category']['slug'] . '/' . $slug . '.html');
 
//                    $this->Session->write('success_msg', 'Your job applicaion is successfully posted. We will contact you soon.');
//                    //  $this->redirect('/jobs/detail/' . $slug);
//                    $this->redirect(array('controller' => 'jobs', 'action' => 'listing'));
                    // $this->redirect(array('controller' => 'jobs', 'action' => 'detail', 'cat' => $jobdetails['Category']['slug'], 'slug' => $slug, 'ext' => 'html'));
                }
            }
        }
    }

    public function JobSave($slug = null) {

        $this->layout = "client";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Apply Job');

        $this->userLoginCheck();
        $this->candidateAccess();
        $user_id = $this->Session->read('user_id');
        //$job_id = $this->Job->field('id', array('Job.slug' => $slug));
        $jobData = $this->Job->find('first', array('conditions' => array('Job.slug' => $slug)));
        $job_id = $jobData['Job']['id'];


        $msgString = '';
        if ($job_id == '') {
            $msgString .= "- Invalid URL.<br>";
        }

        $checkStatus = $this->ShortList->find('first', array('conditions' => array('ShortList.user_id' => $user_id, 'ShortList.job_id' => $job_id)));
        if (!empty($checkStatus)) {
            $msgString .= __d('controller', 'You already saved this job.', true);
        }

        if (isset($msgString) && $msgString != '') {
            $this->Session->write('error_msg', $msgString);
            // $this->redirect('/jobs/detail/' . $slug);
            $this->redirect('/' . $jobData['Category']['slug'] . '/' . $slug . '.html');
        } else {

            $this->request->data['ShortList']['status'] = 1;
            $this->request->data['ShortList']['user_id'] = $user_id;
            $this->request->data['ShortList']['job_id'] = $job_id;
            if ($this->ShortList->save($this->data)) {

                $this->Session->write('success_msg', __d('controller', 'Job added in saved Jobs list', true));
                //  $this->redirect('/jobs/detail/' . $jobData['Category']['slug'] . '/' . $slug);
                $this->redirect('/' . $jobData['Category']['slug'] . '/' . $slug . '.html');
            }
        }
    }

    public function shortList() {
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Short List', true));
        $this->userLoginCheck();
        $this->candidateAccess();
        $this->set('shortList', 'active');
        $userId = $this->Session->read("user_id");
        $condition = array('ShortList.user_id' => $userId, 'Job.status' => 1, 'Job.job_status' => 0);
        $separator = array();
        $urlSeparator = array();

        $order = 'ShortList.id Desc';

        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['ShortList'] = array('conditions' => $condition, 'limit' => '10', 'page' => '1', 'order' => $order);
        $this->set('jobs', $this->paginate('ShortList'));
        //pr($this->paginate('ShortList'));exit;
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'jobs';
            $this->render('short_list');
        }
    }

    public function deleteShortList($id = null) {

        if ($id != '') {
            $this->ShortList->delete($id);
            $this->Session->write('success_msg', __d('controller', 'Job deleted successfully.', true));
        }
        //$this->redirect('/jobs/shortList');
        $this->redirect('/jobs/savedjob');
    }

    public function candidateDetails($job_slug = null, $can_slug = null) {
        $this->layout = "client";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Candidate Details', true));

        $this->set('jobsActive', 'active');
        $this->userLoginCheck();
        $this->recruiterAccess();
        $this->set('job_slug', $job_slug);

        $this->User->bindModel(array(
            'hasMany' => array(
                'Education' => array(
                    'className' => 'Education',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
                'Experience' => array(
                    'className' => 'Experience',
                    'foreignKey' => 'user_id',
                    'dependent' => true),
            ),
        ));

        $userdetails = $this->User->findBySlug($can_slug);
        /* echo"<pre>";
          print_r($userdetails);
          die; */


        $this->set('userdetails', $userdetails);

        $jobDetails = $this->Job->findBySlug($job_slug);
        // $attach_ids = explode(',', $applyDetails['JobApply']['attachment_ids']);

        $showOldImages = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userdetails['User']['id'], 'type' => 'image')));
        $this->set('showOldImages', $showOldImages);

        $showOldDocs = $this->Certificate->find('all', array('conditions' => array('Certificate.user_id' => $userdetails['User']['id'], 'type' => 'doc')));
        $this->set('showOldDocs', $showOldDocs);
        //pr($showOldDocs); exit;
//        $applyDetails = $this->JobApply->find('first', array('conditions' => array('JobApply.user_id' => $userdetails['User']['id'], 'JobApply.job_id' => $jobDetails['Job']['id'])));
//        if ($applyDetails['JobApply']['attachment_ids']) {
//
//            $attach_ids = explode(',', $applyDetails['JobApply']['attachment_ids']);
//
//            $showOldImages = $this->Certificate->find('all', array('conditions' => array('Certificate.id' => $attach_ids, 'Certificate.user_id' => $userdetails['User']['id'], 'type' => 'image')));
//            $this->set('showOldImages', $showOldImages);
//
//            $showOldDocs = $this->Certificate->find('all', array('conditions' => array('Certificate.id' => $attach_ids, 'Certificate.user_id' => $userdetails['User']['id'], 'type' => 'doc')));
//            $this->set('showOldDocs', $showOldDocs);
//        } else {
//            $this->set('showOldImages', '');
//            $this->set('showOldDocs', '');
//        }
    }

    public function applied() {
        $this->layout = "client";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Applied Jobs', true));

        $this->userLoginCheck();
        $this->candidateAccess();
        $this->set('appList', 'active');
        $userId = $this->Session->read("user_id");
        $condition = array('JobApply.user_id' => $userId, 'Job.status' => 1);
        $separator = array();
        $urlSeparator = array();

        $order = 'JobApply.id Desc';

        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['JobApply'] = array('conditions' => $condition, 'limit' => '10', 'page' => '1', 'order' => $order);
        $this->set('jobs', $this->paginate('JobApply'));
        //pr($this->paginate('ShortList'));exit;
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'jobs';
            $this->render('applied');
        }
    }

    public function paywithinvoice() {
        $this->layout = '';
        // pr($_SESSION['data']);exit;
        $invoiceInumber = 'INV' . date('ymd') . rand(1000, 9999);
        $this->set('invoiceInumber', $invoiceInumber);

        $_SESSION['data']['invoice_inumber'] = $invoiceInumber;

        $adminInfo = $this->Admin->findById(1);
        $this->set('adminInfo', $adminInfo);

        $userInfo = $this->User->findById($this->Session->read('user_id'));
        $this->set('userInfo', $userInfo);
    }

    public function saveJobData() {

        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');


        $this->request->data["Job"] = $_SESSION['data'];

        if (isset($_SESSION['dis_amount']) && $_SESSION['dis_amount'] != '') {
            $this->request->data["Job"]['dis_amount'] = $_SESSION['dis_amount'];
            $this->request->data["Job"]['promo_code'] = $_SESSION['promo_code'];
            $this->request->data["Job"]['payment_type'] = 4;
        } else {
            $this->request->data["Job"]['dis_amount'] = 0;
            $this->request->data["Job"]['promo_code'] = '';
            $this->request->data["Job"]['payment_type'] = 2;
        }

        $this->request->data["Job"]['type'] = $_SESSION['type'];
        $this->request->data["Job"]['status'] = 0;
        $this->request->data["Job"]['payment_status'] = 0;
        $slug = $this->data["Job"]['slug'];

        //pr($this->data);exit;

        if ($this->Job->save($this->data)) {
            //if (1) {
            $jobId = $this->Job->id;
            // $jobId = 28;

            $jobInfo = $this->Job->findById($jobId);

            $amount = number_format($_SESSION['amount'], 2);
            if (isset($_SESSION['dis_amount']) && $_SESSION['dis_amount'] != '') {
                $amount = $_SESSION['amount'] - $_SESSION['dis_amount'];
            }

            $gstAmount = $amount * 10 / 100;
            $totalAmount = $amount + $gstAmount;


            $this->request->data['Payment']['job_id'] = $jobInfo['Job']['id'];
            $this->request->data['Payment']['user_id'] = $jobInfo['User']['id'];
            $this->request->data['Payment']['payment_status'] = 'Pending';
            $this->request->data['Payment']['payer_id'] = '';
            $this->request->data['Payment']['subscr_id'] = '';
            $this->request->data['Payment']['signature'] = '';
            $this->request->data['Payment']['price'] = $totalAmount;
            $this->request->data['Payment']['transaction_id'] = $jobInfo['Job']['invoice_inumber'];
            $this->request->data['Payment']['slug'] = 'payment-' . $jobNumber . '-' . time();
            $this->request->data['Payment']['status'] = 1;
            $this->request->data['Payment']['dis_amount'] = $jobInfo['Job']['dis_amount'];
            $this->request->data['Payment']['payment_type'] = $jobInfo['Job']['payment_type'];
            $this->request->data['Payment']['invoice'] = $jobInfo["Job"]["invoice_inumber"];

            $this->Payment->save($this->data['Payment']);

            $this->Job->updateAll(array('Job.payment_status' => '2', 'Job.status' => '1'), array('Job.id' => $jobInfo['Job']['id']));

            $jobCategory = $jobInfo['Job']['category_id'];
            $jobTitle = $jobInfo['Job']['title'];
            $jobId = $jobInfo['Job']['id'];
            $condition[] = " FIND_IN_SET(" . $jobCategory . ",User.email_notification_id)";
            $usersIds = $this->User->find('all', array('conditions' => $condition, 'fields' => array('User.first_name', 'User.email_address', 'User.id')));
            $week = 1;
            if ($jobInfo['Job']['type'] == 'silver') {
                $week = 2;
            } elseif ($jobInfo['Job']['type'] == 'gold') {
                $week = 4;
            }

            if ($usersIds) {
                foreach ($usersIds as $usersIdVV) {
                    $this->request->data['JobNotification']['id'] = '';
                    $this->request->data['JobNotification']['user_id'] = $usersIdVV['User']['id'];
                    $this->request->data['JobNotification']['job_id'] = $jobId;
                    $this->request->data['JobNotification']['first_name'] = $usersIdVV['User']['first_name'];
                    $this->request->data['JobNotification']['email_address'] = $usersIdVV['User']['email_address'];
                    $this->request->data['JobNotification']['last_email_sent_time'] = 0;
                    $this->request->data['JobNotification']['expire_time'] = time() + $week * 7 * 24 * 3600;
                    $this->request->data['JobNotification']['week'] = $week;
                    $this->JobNotification->save($this->data);
                }
            }


            $email = $jobInfo["User"]["email_address"];
            $name = $jobInfo["User"]["first_name"];
            $jobTitle = $jobInfo["Job"]["title"];
            $invoiceInumber = $jobInfo["Job"]["invoice_inumber"];
            $companyname = $jobInfo["User"]["company_name"];
            $link = HTTP_PATH . '/jobs/management';


            $this->Email->to = $email;
            $date = date('F d, Y');
            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='23'"));
            $emData = $this->Emailtemplate->getSubjectLang();
            $subjectField = $emData['subject'];
            $templateField = $emData['template'];

            $toSubArray = array('[!username!]', '[!DATE!]', '[!jobtitle!]', '[!SITE_TITLE!]', '[!invoiceInumber!]');
            $fromSubArray = array($name, $date, $jobTitle, $site_title, $sitelink);
            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
            $this->Email->subject = $subjectToSend;

            //$this->Email->subject = $emailtemplateMessage['Emailtemplate']['subject'];
            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
            $this->Email->from = $site_title . "<" . $mail_from . ">";

            $toRepArray = array('[!username!]', '[!DATE!]', '[!jobtitle!]', '[!SITE_TITLE!]', '[!invoiceInumber!]');
            $fromRepArray = array($name, $date, $jobTitle, $site_title, $sitelink);
            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
            $this->Email->layout = 'default';
            $this->set('messageToSend', $messageToSend);
            $this->Email->template = 'email_template';
            $this->Email->attachments = array(UPLOAD_FULL_INVOICE_IMAGE_PATH . $jobInfo["Job"]["invoice_inumber"] . '.pdf');
            $this->Email->sendAs = 'html';
            $this->Email->send();

            $adminInfo = $this->Admin->findById(1);

            $this->Email->to = $adminInfo['Admin']['acnt_email_add'];
            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='24'"));
            $date = date('F d, Y');
            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';


            $toSubArray = array('[!username!]', '[!DATE!]', '[!jobtitle!]', '[!SITE_TITLE!]', '[!invoiceInumber!]', '[!companyname!]');
            $fromSubArray = array($name, $date, $jobTitle, $site_title, $sitelink, $companyname);
            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
            $this->Email->subject = $subjectToSend;

            // $this->Email->subject = $emailtemplateMessage['Emailtemplate']['subject'];
            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
            $this->Email->from = $site_title . "<" . $mail_from . ">";

            $toRepArray = array('[!username!]', '[!DATE!]', '[!jobtitle!]', '[!SITE_TITLE!]', '[!invoiceInumber!]', '[!companyname!]');
            $fromRepArray = array($name, $date, $jobTitle, $site_title, $sitelink, $companyname);
            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
            $this->Email->layout = 'default';
            $this->set('messageToSend', $messageToSend);
            $this->Email->template = 'email_template';
            $this->Email->attachments = array(UPLOAD_FULL_INVOICE_IMAGE_PATH . $jobInfo["Job"]["invoice_inumber"] . '.pdf');
            $this->Email->sendAs = 'html';
            $this->Email->send();

            unset($_SESSION['data']);
            unset($_SESSION['copy_data']);
            unset($_SESSION['type']);
            unset($_SESSION['dis_amount']);
            unset($_SESSION['promo_code']);
            $this->Session->write('success_msg', "Job posted successfully.");
            $this->redirect("/jobs/management/");
        }
    }

    public function paywithpromocode() {
        $this->layout = "client";
        $this->set('jobsActive', 'active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Pay with Promo Code');
        $this->userLoginCheck();
        $this->recruiterAccess();

        if (!$_SESSION['data']['title']) {
            $this->redirect("/payments/selectType/");
        }
    }

    public function changeStatus($jobNumber = null, $status = null) {

        $site_title = $this->getSiteConstant('title');
        $mail_from = $this->getMailConstant('from');


        $jobInfo = $this->Job->find('first', array('conditions' => array('Job.job_number' => $jobNumber, 'payment_status' => array('0', '1', '2'))));

        if ($status == 0) {
            $this->Job->updateAll(array('Job.payment_status' => '0', 'Job.status' => '0'), array('Job.id' => $jobInfo['Job']['id']));
            $id = $this->Payment->field('id', array('job_id' => $jobInfo['Job']['id']));
            if ($id) {
                $this->Payment->delete($id);
            }
        }
        if ($status == 1) {
            $this->Job->updateAll(array('Job.payment_status' => '1', 'Job.status' => '0'), array('Job.id' => $jobInfo['Job']['id']));
            $id = $this->Payment->field('id', array('job_id' => $jobInfo['Job']['id']));
            if ($id) {
                $this->Payment->delete($id);
            }
        }
        if ($status == 2) {
            if ($jobInfo) {
                //pr($jobInfo); exit;
                $this->request->data['Payment']['job_id'] = $jobInfo['Job']['id'];
                $this->request->data['Payment']['user_id'] = $jobInfo['User']['id'];
                $this->request->data['Payment']['payment_status'] = 'Completed';
                $this->request->data['Payment']['payer_id'] = '';
                $this->request->data['Payment']['subscr_id'] = '';
                $this->request->data['Payment']['signature'] = '';
                $this->request->data['Payment']['price'] = $jobInfo['Job']['amount_paid'];
                $this->request->data['Payment']['transaction_id'] = $jobInfo['Job']['invoice_inumber'];
                $this->request->data['Payment']['slug'] = 'payment-' . $jobNumber . '-' . time();
                $this->request->data['Payment']['status'] = 1;
                $this->request->data['Payment']['dis_amount'] = $jobInfo['Job']['dis_amount'];
                $this->request->data['Payment']['payment_type'] = $jobInfo['Job']['payment_type'];

                $this->Payment->save($this->data['Payment']);

                $this->Job->updateAll(array('Job.payment_status' => '2', 'Job.status' => '1'), array('Job.id' => $jobInfo['Job']['id']));

                $jobCategory = $jobInfo['Job']['category_id'];
                $jobTitle = $jobInfo['Job']['title'];
                $jobId = $jobInfo['Job']['id'];
                $condition[] = " FIND_IN_SET(" . $jobCategory . ",User.email_notification_id)";
                $usersIds = $this->User->find('all', array('conditions' => $condition, 'fields' => array('User.first_name', 'User.email_address', 'User.id')));
                $week = 1;
                if ($jobInfo['Job']['type'] == 'silver') {
                    $week = 2;
                } elseif ($jobInfo['Job']['type'] == 'gold') {
                    $week = 4;
                }

                if ($usersIds) {
                    foreach ($usersIds as $usersIdVV) {
                        $this->request->data['JobNotification']['id'] = '';
                        $this->request->data['JobNotification']['user_id'] = $usersIdVV['User']['id'];
                        $this->request->data['JobNotification']['job_id'] = $jobId;
                        $this->request->data['JobNotification']['first_name'] = $usersIdVV['User']['first_name'];
                        $this->request->data['JobNotification']['email_address'] = $usersIdVV['User']['email_address'];
                        $this->request->data['JobNotification']['last_email_sent_time'] = 0;
                        $this->request->data['JobNotification']['expire_time'] = time() + $week * 7 * 24 * 3600;
                        $this->request->data['JobNotification']['week'] = $week;
                        $this->JobNotification->save($this->data);
                    }
                }


                $transactionId = $jobInfo['Job']['invoice_inumber'];
                $amountPaid = $jobInfo['Job']['amount_paid'];

                $email = $jobInfo["User"]["email_address"];
                $name = $jobInfo["User"]["first_name"];
                $jobTitle = $jobInfo["Job"]["title"];
                $companyname = $jobInfo["User"]["company_name"];
                $link = HTTP_PATH . '/jobs/management';


                $this->Email->to = $email;
                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='25'"));
                $date = date('F d, Y');
                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                $emData = $this->Emailtemplate->getSubjectLang();
                $subjectField = $emData['subject'];
                $templateField = $emData['template'];

                $toSubArray = array('[!jobtitle!]', '[!transactionId!]', '[!username!]', '[!amountpaid!]', '[!SITE_TITLE!]', '[!DATE!]', '[!SITE_LINK!]', '[!subject!]');
                $fromSubArray = array($jobTitle, $transactionId, $name, $amountPaid, $site_title, $date, $link, $this->data['Job']['subject']);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
                $this->Email->subject = $subjectToSend;

                //$this->Email->subject = $this->data['Job']['subject'];
                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";
                $toRepArray = array('[!jobtitle!]', '[!transactionId!]', '[!username!]', '[!amountpaid!]', '[!SITE_TITLE!]', '[!DATE!]', '[!SITE_LINK!]', '[!subject!]');
                $fromRepArray = array($jobTitle, $transactionId, $name, $amountPaid, $site_title, $date, $link, $this->data['Job']['subject']);
                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
                $this->Email->layout = 'default';
                $this->set('messageToSend', $messageToSend);
                $this->Email->template = 'email_template';
                $this->Email->sendAs = 'html';
                $this->Email->send();
            }
        }

        echo '1';
        exit;
    }

    /* ------------------- */
    /* --Admin Copy Data-- */
    /* ------------------- */

    public function admin_copyJob($slug = null) {
        $this->layout = "";


        $planInfo = $this->Admin->findByid(1);
        $this->set('planInfo', $planInfo);

        $jobInfo = $this->Job->findByslug($slug);


        unset($_SESSION['dis_amount']);
        unset($_SESSION['promo_code']);


        if ($jobInfo['Job']['type'] == 'bronze') {
            $amount = $planInfo['Admin']['bronze'];
        } elseif ($jobInfo['Job']['type'] == 'silver') {
            $amount = $planInfo['Admin']['silver'];
        } else {
            $amount = $planInfo['Admin']['gold'];
        }

        $_SESSION['type'] = $jobInfo['Job']['type'];
        $_SESSION['amount'] = $amount;
        $_SESSION['copy_data'] = $jobInfo;
        $this->redirect(array('controller' => 'jobs', 'action' => 'addjob', 'copy'));
    }

    /* ------------------------ */
    /* --Before Admin Add job-- */
    /* ------------------------ */

    public function admin_beforeAddJob() {
        $this->layout = "";
        $this->set('add_job', 'active');
        unset($_SESSION['dis_amount']);
        unset($_SESSION['promo_code']);
        unset($_SESSION['type']);
        unset($_SESSION['amount']);
        unset($_SESSION['copy_data']);
        $this->redirect(array('controller' => 'jobs', 'action' => 'addjob'));
    }

    /* ----------------- */
    /* --Admin Add job-- */
    /* ----------------- */

    function admin_addjob($isCopy = null) {
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add Job");

        $this->layout = "admin";

        $this->set('add_job', 'active');

        $this->set('isCopy', $isCopy);
        $msgString = '';
        global $extentions;


        $adminId = $this->Session->read('adminid');

        $categories = $this->Category->getCategoryList();
        $this->set('categories', $categories);
        $subcategories = array();
        $this->set('subcategories', $subcategories);

        $stateList = array();
        $this->set('stateList', $stateList);
        $cityList = array();
        $this->set('cityList', $cityList);

        // get user name and id list and set it to dropdown
        $users = $this->User->find('all', array('fields' => array('User.id', 'User.first_name', 'User.last_name'), 'conditions' => array('User.user_type' => 'recruiter')));
        $fullName = Set::combine($users, '{n}.User.id', array('{0} {1}', '{n}.User.first_name', '{n}.User.last_name'));
        $this->set('fullname', $fullName);

        // get skills from skill table
        $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('skillList', $skillList);

        // get designations from skill table
        $designationlList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Designation', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('designationlList', $designationlList);

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        if ($this->data) {


            if (empty($this->data["Job"]["userId"])) {
                $msgString .= "- Select Employer is required field.<br>";
            }

//            if (empty($this->data["Job"]["type"])) {
//                $msgString .="- Job type is required field.<br>";
//            }

            if (empty($this->data["Job"]["title"])) {
                $msgString .= "- Job title is required field.<br>";
            }

            if (empty($this->data["Job"]["brief_abtcomp"])) {
                $msgString .= "- Company profile required field.<br>";
            }

            if (empty($this->data["Job"]["category_id"])) {
                $msgString .= "- Category is required field.<br>";
            }
            if (empty($this->data["Job"]["subcategory_id"])) {
                // $msgString .="- Sub Category is required field.<br>";
            }
            if (empty($this->data["Job"]["description"])) {
                $msgString .= "- Job description is required field.<br>";
            }
            if (empty($this->data["Job"]["company_name"])) {
                $msgString .= "- Company name is required field.<br>";
            }
            if (empty($this->data["Job"]["work_type"])) {
                $msgString .= "- Work type is required field.<br>";
            }
            if (empty($this->data["Job"]["contact_name"])) {
                $msgString .= "- Contact name is required field.<br>";
            }
            if (empty($this->data["Job"]["contact_number"])) {
                $msgString .= "- Contact number is required field.<br>";
            }
            /*   if (empty($this->data["Job"]["state_id"])) {
              $msgString .="- State is required field.<br>";
              }
              if (empty($this->data["Job"]["city_id"])) {
              $msgString .="- City is required field.<br>";
              } */

//            if ($this->data["Job"]["exp_year"]=='') {
//                $msgString .="- Experience year is required field.<br>";
//            }

            if ($this->data["Job"]["exp"] == '') {
                $msgString .= "- Experience year is required field.<br>";
            }
//            if ($this->data["Job"]["max_exp"] == '') {
//                $msgString .="- Max Experience year is required field.<br>";
//            }
            if ($this->data["Job"]["salary"] == '') {
                $msgString .= "- Annual Salary is required field.<br>";
            }
//            if ($this->data["Job"]["max_salary"] == '') {
//                $msgString .="- Max Annual Salary is required field.<br>";
//            }

            if ($this->data['Job']['skill']) {
                
            } else if (empty($this->data['Job']['skill'])) {
                $msgString .= "- Skill is required field.<br>";
            } else {
                $this->request->data['Job']['skill'] = '';
            }

            if (empty($this->data["Job"]["designation"])) {
                $msgString .= "- Designation is required field.<br>";
            }

            /* if (empty($this->data["Job"]["location"])) {
              $msgString .="- Location is required field.<br>";
              } */

            $youtube_link = '';
            if ($this->data["Job"]["youtube_link"]) {
                $url = $this->data['Job']['youtube_link'];
                $urlArray = explode('watch?v=', $url);
                if (!$urlArray[1]) {
                    $msgString .= "- Please enter valid video URL.<br>";
                }
                $youtube_link = $urlArray[1];
            }


            if ($this->data["Job"]["logo"]["name"]) {
                $getextention = $this->PImage->getExtension($this->data['Job']['logo']['name']);
                $extention = strtolower($getextention);
                if ($this->data['Job']['logo']['size'] > '2097152') {
                    $msgString .= "- Max file size upload is 2MB.<br>";
                } elseif (!in_array($extention, $extentions)) {
                    $msgString .= "- Not Valid Extention.<br>";
                }
            }

            $specialCharacters = array('#', '$', '%', '@', '+', '=', '\\', '/', '"', ' ', "'", ':', '~', '`', '!', '^', '*', '(', ')', '|', "'");
            $toReplace = "-";
            $this->request->data['Job']['logo']['name'] = str_replace($specialCharacters, $toReplace, $this->data['Job']['logo']['name']);
            $this->request->data['Job']['logo']['name'] = str_replace("&", "and", $this->data['Job']['logo']['name']);
            if (!empty($this->data['Job']['logo']['name'])) {

                $imageArray = $this->data['Job']['logo'];

                $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_JOB_LOGO_PATH, "jpg,jpeg,png,gif");

                list($width, $height, $type, $attr) = getimagesize(UPLOAD_JOB_LOGO_PATH . '/' . $returnedUploadImageArray[0]);

                if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {

                    $msgString .= "- Logo file not valid.<br>";
                } else if ($width < 250 && $height < 250) {

                    @unlink(UPLOAD_JOB_LOGO_PATH . '/' . $returnedUploadImageArray[0]);
                    $msgString .= "- Logo size must be bigger than  250 X 250 pixels.<br>";
                } else {

                    list($width) = getimagesize(UPLOAD_JOB_LOGO_PATH . $returnedUploadImageArray[0]);
                    if ($width > 650) {
                        $this->PImageTest->resize(UPLOAD_JOB_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_JOB_LOGO_PATH . $returnedUploadImageArray[0], UPLOAD_JOB_LOGO_WIDTH, UPLOAD_JOB_LOGO_HEIGHT, 100);
                    }
                    $this->request->data["Job"]["logo"] = $returnedUploadImageArray[0];
                    chmod(UPLOAD_JOB_LOGO_PATH . $returnedUploadImageArray[0], 0755);
                }
            } else {
                $this->request->data["Job"]["logo"] = '';
            }

            if (isset($msgString) && $msgString != '') {
                // show message when finds error
                $this->Session->setFlash($msgString, 'error_msg');
            } else {

                if ($this->data['Job']['subcategory_id']) {
                    $this->request->data['Job']['subcategory_id'] = implode(',', $this->data['Job']['subcategory_id']);
                } else {
                    $this->request->data['Job']['subcategory_id'] = 0;
                }

                $exp = explode('-', $this->data['Job']['exp']);
                $this->request->data["Job"]["min_exp"] = $exp[0];
                $this->request->data["Job"]["max_exp"] = $exp[1];

                $sallery = explode('-', $this->data['Job']['salary']);
                $this->request->data["Job"]["min_salary"] = $sallery[0];
                $this->request->data["Job"]["max_salary"] = $sallery[1];

                $slug = $this->stringToSlugUnique($this->data["Job"]["title"], 'Job');
                $this->request->data['Job']['slug'] = $slug;
                $this->request->data['Job']['type'] = $this->data["Job"]["type"];
                $this->request->data['Job']['status'] = 1;
                $this->request->data['Job']['admin_id'] = $adminId;

                $this->request->data['Job']['user_id'] = $this->data["Job"]["userId"];
                $this->request->data['Job']['payment_type'] = 2;
                $this->request->data['Job']['payment_status'] = '0';
                $this->request->data['Job']['job_number'] = 'JOB' . $adminId . time();
                $this->request->data['Job']['youtube_link'] = $youtube_link;
                if (empty($this->data["Job"]["exp_month"])) {
                    $this->request->data['Job']['exp_month'] = 0;
                }

                if ($this->data["Job"]["type"] == "bronze") {
                    // echo"----------->>>"; pr($this->data); die;
                    // $this->data["Job"]["selling_point2"] = '0';
                    $this->request->data['Job']['selling_point2'] = '';

                    //  $this->data["Job"]["selling_point2"] = '0';
                    $this->request->data["Job"]["selling_point3"] = '';
                }


                if ($this->data["Job"]["type"] == 'gold') {
                    $this->request->data['Job']['hot_job_time'] = time() + 7 * 24 * 3600;
                } else {
                    $this->request->data['Job']['hot_job_time'] = time();
                }

                $this->request->data['Job']['expire_time'] = strtotime($this->data['Job']['expire_time']);
                $this->request->data['Job']['skill'] = implode(',', $this->data['Job']['skill']);

                if ($this->Job->save($this->data)) {
                    $jobId = $this->Job->id;
                    $jobDetail = $this->Job->findById($jobId);
                    $site_title = $this->getSiteConstant('title');
                    $mail_from = $this->getMailConstant('from');

                    $title = $jobDetail['Job']['title'];
                    $category = $jobDetail['Category']['name'];
                    $skill = $jobDetail['Skill']['name'];
                    $location = $jobDetail['Job']['job_city'];
                    $minExp = $jobDetail['Job']['min_exp'] . ' Year';
                    $maxExp = $jobDetail['Job']['max_exp'] . ' Year';
                    $min_salary = CURRENCY . ' ' . intval($jobDetail['Job']['min_salary']);
                    $max_salary = CURRENCY . ' ' . intval($jobDetail['Job']['max_salary']);
                    $description = $jobDetail['Job']['description'];
                    $company_name = $jobDetail['Job']['company_name'];
                    $contact_number = $jobDetail['Job']['contact_number'];
                    $website = $jobDetail['Job']['url'] ? $jobDetail['Job']['url'] : 'N/A';
                    $address = $jobDetail['Job']['address'] ? $jobDetail['Job']['address'] : 'N/A';

                    $designation = $this->Skill->field('name', array(
                        'Skill.status' => 1,
                        'Skill.type' => 'Designation',
                        'Skill.id' => $jobDetail['Job']['designation'],
                    ));
                    $username = $jobDetail['User']['first_name'] . ' ' . $jobDetail['User']['last_name'];
                    $this->Email->to = $jobDetail['User']['email_address'];
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='46'"));

                    $toSubArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                    $fromSubArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                    $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                    $this->Email->subject = $subjectToSend;


                    $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                    $this->Email->from = $site_title . "<" . $mail_from . ">";
                    $toRepArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                    $fromRepArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                    $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                    $this->Email->layout = 'default';
                    $this->set('messageToSend', $messageToSend);
                    $this->Email->template = 'email_template';
                    $this->Email->sendAs = 'html';
                    $this->Email->send();

                    unset($_SESSION['copy_data']);
                    unset($_SESSION['type']);
                    unset($_SESSION['dis_amount']);
                    unset($_SESSION['promo_code']);

                    $this->Session->setFlash('Employer job posted successfully', 'success_msg');
                    $this->redirect('/admin/jobs/index');
                }
            }
        } else {

            if (isset($_SESSION['copy_data']) && $_SESSION['copy_data'] != '') {
                $this->data = $_SESSION['copy_data'];
                $this->request->data['Job']['title'] = $_SESSION['copy_data']['Job']['title'];
                $this->request->data['Job']['subcategory_id'] = explode(',', $this->data['Job']['subcategory_id']);
                $this->request->data['Job']['skill'] = explode(',', $this->data['Job']['skill']);

                /*   $cityList = $this->City->find('list', array('conditions' => array('City.state_id' => $this->data['Job']['state_id'], 'City.status' => 1), 'fields' => array('City.id', 'City.city_name'), 'order' => 'City.city_name asc'));
                  $this->set('cityList', $cityList); */

                $subcategories = $this->Category->getSubCategoryList($this->data['Job']['category_id']);
                $this->set('subcategories', $subcategories);
                //pr($this->data);exit;
                if ($this->data['Job']['youtube_link']) {
                    $this->request->data['Job']['youtube_link'] = 'https://www.youtube.com/watch?v=' . $this->data['Job']['youtube_link'];
                }

                /*  $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $this->data['Job']['postal_code'])));
                  $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
                  $this->set('stateList', $stateList);
                  $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
                  $this->set('cityList', $cityList); */
                $exp_array = array();
                $exp_array[] = $_SESSION['copy_data']['Job']['min_exp'];
                $exp_array[] = $_SESSION['copy_data']['Job']['max_exp'];

                $exp = implode('-', $exp_array);
                $this->request->data["Job"]["exp"] = $exp;

                $sal_array = array();
                $sal_array[] = $_SESSION['copy_data']['Job']['min_salary'];
                $sal_array[] = $_SESSION['copy_data']['Job']['max_salary'];

                $sallery = implode('-', $sal_array);
                $this->request->data["Job"]["salary"] = $sallery;
            }
        }
    }

    /* --------------------------------- */
    /* ----category wise searching------ */
    /* --------------------------------- */

    public function jobListing($categorySlug = '') {
//pr($categorySlug); exit;
        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Jobs List', true));
        $this->set('jobs_list', 'active');


        $userId = $this->Session->read('user_id');

        if (!empty($categorySlug)) {
            $categoryName = $this->Category->field('name', array('Category.slug' => $categorySlug));
            $this->set('categoryName', $categoryName);


            $catData = $this->Category->find('first', array('conditions' => array('Category.slug' => $categorySlug)));
            $this->set('catData', $catData);
        } else {
            $metaData = classRegistry::init('Admin')->find('first', array('conditions' => array('Admin.id' => 1)));
            $this->set('metaData', $metaData);
        }



        $this->set('slug', $categorySlug);

        // $category['category']['id'] = $this->Category->findByName($categoryName);
        //find category Id by slug
        $categoryId = $this->Category->field('id', array('slug' => $categorySlug));

        //for url if user enter wrong argument/parameter
        // in url so check the information by follwoing method
        if (trim($categoryId) != '') {
            $categorieCount = $this->Category->findById($categoryId);
            if (empty($categorieCount)) {
                $this->redirect('/homes/error');
            }
        }

        if ($userId) {
            $showOldImages = $this->Certificate->find('list', array('conditions' => array('Certificate.user_id' => $userId), 'fields' => array('id', 'document'), 'order' => 'Certificate.id ASC'));
            $this->set('showOldImages', $showOldImages);
        }


        $categories = $this->Category->getCategoryList();
        $this->set('categories', $categories);

        $subcategories = array();
        if (isset($categoryId) && $categoryId != '') {
            //pr($this->Category->getSubCategoryList($categoryId)); exit;
            $this->set('subcategories', $this->Category->getSubCategoryList($categoryId));
        } else {
            $this->set('subcategories', $subcategories);
        }

        //        $subcategories = array();
        //        if (isset($this->data['Job']['category_id']) && $this->data['Job']['category_id'] != '') {
        //            
        //            $this->set('subcategories', $this->Category->getSubCategoryList($categoryId));
        //        } else {
        //            $this->set('subcategories', $subcategories);
        //        }

        $skillDesList = $this->Skill->find('list', array('conditions' => array('Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('skillDesList', $skillDesList);


        // get skills from skill table
        $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('skillList', $skillList);

        // get designations from skill table
        $designationlList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Designation', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('designationlList', $designationlList);

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        //$condition = array('Job.status' => 1, 'Job.payment_status' => 2, 'Job.category_id' => $categoryId, 'Job.expire_time >=' => time());
        $condition = array('Job.status' => 1, 'Job.payment_status' => 2, 'Job.category_id' => $categoryId);
        $separator = array();
        $urlSeparator = array();

        $order = '';

        if (!empty($this->data)) {

            if (isset($this->data['Job']['created']) && $this->data['Job']['created'] != '') {
                $order = 'Job.' . $this->data['Job']['created'];
            }

            if (isset($this->data['Job']['keyword']) && $this->data['Job']['keyword'] != '') {
                $keyword = trim($this->data['Job']['keyword']);
            }

            if (isset($this->data['Job']['searchkey']) && $this->data['Job']['searchkey'] != '') {
                if (is_array($this->data['Job']['searchkey'])) {
                    $searchkey = implode('-', $this->data['Job']['searchkey']);
                } else {
                    $searchkey = $this->data['Job']['searchkey'];
                }
            }

            if (isset($this->data['Job']['category_id']) && !empty($this->data['Job']['category_id']) && count($this->data['Job']['category_id']) > 0) {
                if (is_array($this->data['Job']['category_id'])) {
                    $category_id = implode('-', $this->data['Job']['category_id']);
                } else {
                    $category_id = $this->data['Job']['category_id'];
                }
            }

            if (isset($this->data['Job']['subcategory_id']) && !empty($this->data['Job']['subcategory_id']) && count($this->data['Job']['subcategory_id']) > 0) {
                if (is_array($this->data['Job']['subcategory_id'])) {
                    $subcategory_id = implode('-', $this->data['Job']['subcategory_id']);
                } else {
                    $subcategory_id = $this->data['Job']['subcategory_id'];
                }
            }

            if (isset($this->data['Job']['location']) && !empty($this->data['Job']['location']) && count($this->data['Job']['location']) > 0) {

                if (is_array($this->data['Job']['location'])) {
                    $location = implode('-', $this->data['Job']['location']);
                    //$location = addslashes($location);
                    //  $this->set('location', $this->data['Job']['location']);
                } else {
                    $location = $this->data['Job']['location'];
                }
            }

            if (isset($this->data['Job']['work_type']) && !empty($this->data['Job']['work_type']) && count($this->data['Job']['work_type']) > 0) {

                if (is_array($this->data['Job']['work_type'])) {
                    $worktype = implode('-', $this->data['Job']['work_type']);
                } else {
                    $worktype = $this->data['Job']['work_type'];
                }
            }

            if (!empty($this->data['Job']['skill'])) {
                $skill = implode(",", $this->data['Job']['skill']);
                $skill = addslashes($skill);
                $this->set('skill', $this->data['Job']['skill']);
            }

            if (!empty($this->data['Job']['designation'])) {
                $designation = implode(",", $this->data['Job']['designation']);
                $designation = addslashes($designation);
                $this->set('designation', $this->data['Job']['designation']);
            }
            if (isset($this->data['Job']['min_salary']) && $this->data['Job']['min_salary'] != '') {
                $min_salary = trim($this->data['Job']['min_salary']);
            }
            if (isset($this->data['Job']['max_salary']) && $this->data['Job']['max_salary'] != '') {
                $max_salary = trim($this->data['Job']['max_salary']);
            }
            if (isset($this->data['Job']['min_exp']) && $this->data['Job']['min_exp'] != '') {
                $min_exp = trim($this->data['Job']['min_exp']);
            }
            if (isset($this->data['Job']['max_exp']) && $this->data['Job']['max_exp'] != '') {
                $max_exp = trim($this->data['Job']['max_exp']);
            }
        } elseif (!empty($this->params)) {

            if (isset($this->params['named']['keyword']) && $this->params['named']['keyword'] != '') {
                $keyword = urldecode(trim($this->params['named']['keyword']));
            }
            if (isset($this->params['named']['category_id']) && $this->params['named']['category_id'] != '') {
                $category_id = trim($this->params['named']['category_id']);
            }
            if (isset($this->params['named']['subcategory_id']) && $this->params['named']['subcategory_id'] != '') {
                $subcategory_id = trim($this->params['named']['subcategory_id']);
            }

            if (isset($this->params['named']['location']) && $this->params['named']['location'] != '') {
                // $location = trim($this->params['named']['location']);
                // $location = addslashes($location);
                // $this->set('location', $location);
                $location = urldecode(trim($this->params['named']['location']));
            }

            if (isset($this->params['named']['work_type']) && $this->params['named']['work_type'] != '') {
                $worktype = urldecode(trim($this->params['named']['work_type']));
            }


            if (isset($this->params['named']['skill']) && $this->params['named']['skill'] != '') {
                $skill = trim($this->params['named']['skill']);
                $skill = addslashes($skill);
                $this->set('skill', $skill);
            }

            if (isset($this->params['named']['designation']) && $this->params['named']['designation'] != '') {
                $designation = trim($this->params['named']['designation']);
                $designation = addslashes($designation);
                $this->set('designation', $designation);
            }
            if (isset($this->params['named']['min_salary']) && $this->params['named']['min_salary'] != '') {
                $min_salary = urldecode(trim($this->params['named']['min_salary']));
            }
            if (isset($this->params['named']['max_salary']) && $this->params['named']['max_salary'] != '') {
                $max_salary = urldecode(trim($this->params['named']['max_salary']));
            }
            if (isset($this->params['named']['min_exp']) && $this->params['named']['min_exp'] != '') {
                $min_exp = urldecode(trim($this->params['named']['min_exp']));
            }
            if (isset($this->params['named']['max_exp']) && $this->params['named']['max_exp'] != '') {
                $max_exp = urldecode(trim($this->params['named']['max_exp']));
            }

            if (isset($this->params['named']['searchkey']) && $this->params['named']['searchkey'] != '') {
                $searchkey = urldecode(trim($this->params['named']['searchkey']));
            }
        }



        if (isset($keyword) && $keyword != '') {
            $separator[] = 'keyword:' . urlencode($keyword);
            $keyword = str_replace('_', '\_', $keyword);
            $condition[] = " (`Job`.`title` LIKE '%" . addslashes($keyword) . "%' OR `Job`.`description` LIKE '%" . addslashes($keyword) . "%' OR `Job`.`company_name` LIKE '%" . addslashes($keyword) . "%' ) ";
            $keyword = str_replace('\_', '_', $keyword);
            $this->set('keyword', $keyword);
        }


        if (isset($searchkey) && !empty($searchkey)) {

            $searchkey_arr = explode("-", $searchkey);

            foreach ($searchkey_arr as $id) {
                $condition_search[] = "(FIND_IN_SET('" . $id . "',Job.skill) OR FIND_IN_SET('" . $id . "',Job.designation) )";
            }
            $condition[] = array('OR' => $condition_search);
            $urlSeparator[] = 'searchkey:' . $searchkey;
            $separator[] = 'searchkey:' . $searchkey;
            $this->set('searchkey', $searchkey);
        }

        if (!empty($location)) {
            $location_arr = explode("-", $location);
            foreach ($location_arr as $loc) {
                $condition_location[] = "(FIND_IN_SET('" . $loc . "',Job.location))";
            }
            $condition[] = array('OR' => $condition_location);
            $urlSeparator[] = 'location:' . $location;
            $separator[] = 'location:' . $location;
            $this->set('location', $location);
        }

        if (!empty($worktype)) {

            $worktype_arr = explode("-", $worktype);

            foreach ($worktype_arr as $work) {
                $condition_worktype[] = "(FIND_IN_SET('" . $work . "',Job.work_type))";
            }
            $condition[] = array('OR' => $condition_worktype);
            $urlSeparator[] = 'worktype:' . $worktype;
            $separator[] = 'worktype:' . $worktype;
            $this->set('worktype', $worktype);
        }

        // pr($condition); exit; 

        /*  if (isset($category_id) && $category_id != '') {
          $this->set('topcate', $category_id);
          $separator[] = 'category_id:' . $category_id;
          $category_idCondtionArray = explode('-', $category_id);

          if (isset($subcategory_id) && $subcategory_id != '') {
          $this->set('subcate', $subcategory_id);
          $subcategory_idCondtionArray = explode('-', $subcategory_id);
          foreach ($subcategory_idCondtionArray as $subMain) {
          $subMainVal = $this->Category->field('parent_id', array('Category.id' => $subMain));
          if (($key = array_search($subMainVal, $category_idCondtionArray)) !== false) {
          unset($category_idCondtionArray[$key]);
          }
          //   pr($category_idCondtionArray);
          }
          // pr($category_idCondtionArray);
          if ($category_idCondtionArray) {
          $subcategory_idCondtion = implode(',', $subcategory_idCondtionArray);
          $separator[] = 'subcategory_id:' . $subcategory_id;

          $category_idCondtion = implode(',', $category_idCondtionArray);
          $condition[] = " (Job.category_id IN ($category_idCondtion) OR Job.subcategory_id IN ($subcategory_idCondtion ) )";
          } else {
          $subcategory_idCondtion = implode(',', $subcategory_idCondtionArray);
          $condition[] = " (Job.subcategory_id IN ($subcategory_idCondtion ))";
          $separator[] = 'subcategory_id:' . $subcategory_id;
          }
          } else {
          $category_idCondtion = implode(',', $category_idCondtionArray);
          $condition[] = " (Job.category_id IN ($category_idCondtion))";
          }
          } */

        if (!empty($skill)) {

            $skill_arr = explode(",", $skill);
            foreach ($skill_arr as $skil) {
                $condition_skill[] = "(FIND_IN_SET('" . $skil . "',Job.skill))";
            }
            $condition[] = array('OR' => $condition_skill);
            $urlSeparator[] = 'skill:' . $skill;
            $separator[] = 'skill:' . $skill;
        }



        if (!empty($subcategory_id)) {

            $subcat_arr = explode("-", $subcategory_id);

            foreach ($subcat_arr as $subId) {
                $row[] = $subId;
            }
            $subcategory_idCondtion = implode(',', $row);
            $condition[] = " (Job.subcategory_id IN ($subcategory_idCondtion ) )";
            $urlSeparator[] = 'subcategory_id:' . $subcategory_id;
            $separator[] = 'subcategory_id:' . $subcategory_id;
        }


        if (!empty($designation)) {
            $designation_arr = explode(",", $designation);
            foreach ($designation_arr as $des) {
                $condition_designation[] = "(FIND_IN_SET('" . $des . "',Job.designation))";
            }
            $condition[] = array('OR' => $condition_designation);
            $urlSeparator[] = 'designation:' . $designation;
            $separator[] = 'designation:' . $designation;
        }
        if (isset($min_salary) && $min_salary != '') { //echo 'vdsdv';exit;
            $separator[] = 'min_salary:' . urlencode($min_salary);
            $min_salary = str_replace('_', '\_', $min_salary);
            $condition[] = " ( Job.min_salary >= '$min_salary' ) ";
            $min_salary = str_replace('\_', '_', $min_salary);
        }
        if ((isset($min_salary) && $min_salary != '') && (isset($max_salary) && $max_salary != '')) {
            $separator[] = 'min_salary:' . urlencode($min_salary);
            $separator[] = 'max_salary:' . urlencode($max_salary);
            $min_salary = str_replace('_', '\_', $min_salary);
            if ($min_salary == $max_salary) {
                $condition[] = " ((Job.min_salary <= $min_salary AND Job.max_salary >= $min_salary)) ";
            } else {
                // $condition[] = " ((Job.min_salary >= $min_salary AND Job.max_salary <= $max_salary) OR (Job.min_salary <= $min_salary )) ";
                $condition[] = " ((Job.min_salary >= $min_salary AND Job.max_salary <= $min_salary) OR (Job.min_salary >= $min_salary AND Job.max_salary <= $max_salary) OR (Job.min_salary = $max_salary ) OR (Job.max_salary = $min_salary )) ";
            }
            $min_salary = str_replace('\_', '_', $min_salary);

            $this->set('min_salary', $min_salary);
            $this->set('max_salary', $max_salary);
        }

        /* if (isset($min_exp) && $min_exp != '') { //echo 'vdsdv';exit;
          $separator[] = 'min_exp:' . urlencode($min_exp);
          $min_exp = str_replace('_', '\_', $min_exp);
          $condition[] = " ( Job.min_exp >= '$min_exp' ) ";
          $min_exp = str_replace('\_', '_', $min_exp);
          }
          if (isset($max_exp) && $max_exp != '') {
          $separator[] = 'max_exp:' . urlencode($max_exp);
          $max_exp = str_replace('_', '\_', $max_exp);
          $condition[] = " ( Job.max_exp <= '$max_exp' ) ";
          $max_exp = str_replace('\_', '_', $max_exp);
          } */

        if ((isset($min_exp) && $min_exp != '') && (isset($max_exp) && $max_exp != '')) {
            $separator[] = 'min_exp:' . urlencode($min_exp);
            $separator[] = 'max_exp:' . urlencode($max_exp);
            $min_exp = str_replace('_', '\_', $min_exp);

            if ($min_exp == $max_exp) {
                $condition[] = " ((Job.min_exp <= $min_exp AND Job.max_exp >= $min_exp)) ";
            } else {
                //  $condition[] = " ((Job.min_exp >= $min_exp AND Job.max_exp <= $max_exp) OR (Job.min_exp <= $min_exp )  OR (Job.min_exp = $max_exp )) ";

                $condition[] = " ((Job.min_exp >= $min_exp AND Job.max_exp <= $min_exp) OR (Job.min_exp >= $min_exp AND Job.max_exp <= $max_exp) OR (Job.min_exp = $max_exp ) OR (Job.max_exp = $min_exp )) ";
            }
            $min_exp = str_replace('\_', '_', $min_exp);

            $this->set('min_exp', $min_exp);
            $this->set('max_exp', $max_exp);
        }

        $sort = '';
        if (isset($order) && $order != '') {

            $ord = explode(" ", $order);

            $separator[] = 'sort:' . urlencode($ord[0]);
            $sort = str_replace('_', '\_', $order);

            $separator[] = 'order:' . urlencode($ord[1]);
            $order = str_replace('_', '\_', $order);
            $this->set('order', $order);
        }

        //2017-02-18
        $condition[] = array("(Job.category_id != 0)");

        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['Job'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('jobs', $this->paginate('Job'));

        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'jobs';
            $this->render('filter_job');
        }
    }

    public function filterJob($categorySlug = '') {

        $this->layout = "client";
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Jobs List', true));
        $this->set('jobs_list', 'active');


        $userId = $this->Session->read('user_id');


        if (!empty($categorySlug)) {
            $categoryName = $this->Category->field('name', array('Category.slug' => $categorySlug));
            $this->set('categoryName', $categoryName);
        } else {
            $metaData = classRegistry::init('Admin')->find('first', array('conditions' => array('Admin.id' => 1)));
            $this->set('metaData', $metaData);
        }

        $this->set('slug', $categorySlug);

        // $category['category']['id'] = $this->Category->findByName($categoryName);
        //find category Id by slug
        $categoryId = $this->Category->field('id', array('slug' => $categorySlug));

        //for url if user enter wrong argument/parameter
        // in url so check the information by follwoing method
        if (trim($categoryId) != '') {
            $categorieCount = $this->Category->findById($categoryId);
            if (empty($categorieCount)) {
                $this->redirect('/homes/error');
            }
        }

        if ($userId) {
            $showOldImages = $this->Certificate->find('list', array('conditions' => array('Certificate.user_id' => $userId), 'fields' => array('id', 'document'), 'order' => 'Certificate.id ASC'));
            $this->set('showOldImages', $showOldImages);
        }


        $categories = $this->Category->getCategoryList();
        $this->set('categories', $categories);

        $subcategories = array();
        if (isset($categoryId) && $categoryId != '') {
            //pr($this->Category->getSubCategoryList($categoryId)); exit;
            $this->set('subcategories', $this->Category->getSubCategoryList($categoryId));
        } else {
            $this->set('subcategories', $subcategories);
        }

        // get skills from skill table
        $skillList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Skill', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('skillList', $skillList);

        // get designations from skill table
        $designationlList = $this->Skill->find('list', array('conditions' => array('Skill.type' => 'Designation', 'Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('designationlList', $designationlList);

        // get locations from location table
        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);

        $condition = array('Job.status' => 1, 'Job.payment_status' => 2, 'Job.category_id' => $categoryId, 'Job.expire_time >=' => time());
//        $condition = array('Job.status' => 1, 'Job.payment_status' => 2, 'Job.category_id' => $categoryId);


        $separator = array();
        $urlSeparator = array();

        // $order = 'Job.created Desc';
        $order = "";

        if (!empty($this->data)) {

            if (isset($this->data['Job']['created']) && $this->data['Job']['created'] != '') {
                $order = 'Job.' . $this->data['Job']['created'];
                //$this->set('order', $order);
            }

            if (isset($this->data['Job']['keyword']) && $this->data['Job']['keyword'] != '') {
                $keyword = trim($this->data['Job']['keyword']);
            }

            if (isset($this->data['Job']['searchkey']) && $this->data['Job']['searchkey'] != '') {
                if (is_array($this->data['Job']['searchkey'])) {
                    $searchkey = implode('-', $this->data['Job']['searchkey']);
                } else {
                    $searchkey = $this->data['Job']['searchkey'];
                }
            }

            if (isset($this->data['Job']['category_id']) && !empty($this->data['Job']['category_id']) && count($this->data['Job']['category_id']) > 0) {
                if (is_array($this->data['Job']['category_id'])) {
                    $category_id = implode('-', $this->data['Job']['category_id']);
                } else {
                    $category_id = $this->data['Job']['category_id'];
                }
            }

            if (isset($this->data['Job']['subcategory_id']) && !empty($this->data['Job']['subcategory_id']) && count($this->data['Job']['subcategory_id']) > 0) {
                if (is_array($this->data['Job']['subcategory_id'])) {
                    $subcategory_id = implode('-', $this->data['Job']['subcategory_id']);
                } else {
                    $subcategory_id = $this->data['Job']['subcategory_id'];
                }
            }

            if (isset($this->data['Job']['location']) && !empty($this->data['Job']['location']) && count($this->data['Job']['location']) > 0) {

                if (is_array($this->data['Job']['location'])) {
                    $location = implode('-', $this->data['Job']['location']);
                    //$location = addslashes($location);
                    //$this->set('location', $this->data['Job']['location']);
                } else {
                    $location = $this->data['Job']['location'];
                }
            }

            if (isset($this->data['Job']['work_type']) && !empty($this->data['Job']['work_type']) && count($this->data['Job']['work_type']) > 0) {

                if (is_array($this->data['Job']['work_type'])) {
                    $worktype = implode('-', $this->data['Job']['work_type']);
                } else {
                    $worktype = $this->data['Job']['work_type'];
                }
            }

            if (!empty($this->data['Job']['skill'])) {
                $skill = implode(",", $this->data['Job']['skill']);
                $skill = addslashes($skill);
                $this->set('skill', $this->data['Job']['skill']);
            }

            if (!empty($this->data['Job']['designation'])) {
                $designation = implode(",", $this->data['Job']['designation']);
                $designation = addslashes($designation);
                $this->set('designation', $this->data['Job']['designation']);
            }
            if (isset($this->data['Job']['min_salary']) && $this->data['Job']['min_salary'] != '') {
                $min_salary = trim($this->data['Job']['min_salary']);
            }
            if (isset($this->data['Job']['max_salary']) && $this->data['Job']['max_salary'] != '') {
                $max_salary = trim($this->data['Job']['max_salary']);
            }
            if (isset($this->data['Job']['min_exp']) && $this->data['Job']['min_exp'] != '') {
                $min_exp = trim($this->data['Job']['min_exp']);
            }
            if (isset($this->data['Job']['max_exp']) && $this->data['Job']['max_exp'] != '') {
                $max_exp = trim($this->data['Job']['max_exp']);
            }
        } elseif (!empty($this->params)) {

            if (isset($this->params['named']['keyword']) && $this->params['named']['keyword'] != '') {
                $keyword = urldecode(trim($this->params['named']['keyword']));
            }
            if (isset($this->params['named']['category_id']) && $this->params['named']['category_id'] != '') {
                $category_id = trim($this->params['named']['category_id']);
            }
            if (isset($this->params['named']['subcategory_id']) && $this->params['named']['subcategory_id'] != '') {
                $subcategory_id = trim($this->params['named']['subcategory_id']);
            }

            if (isset($this->params['named']['location']) && $this->params['named']['location'] != '') {
                $location = urldecode(trim($this->params['named']['location']));
                // $location = addslashes($location);
                // $this->set('location', $location);
            }

            if (isset($this->params['named']['work_type']) && $this->params['named']['work_type'] != '') {
                $worktype = urldecode(trim($this->params['named']['work_type']));
            }

            if (isset($this->params['named']['skill']) && $this->params['named']['skill'] != '') {
                $skill = trim($this->params['named']['skill']);
                $skill = addslashes($skill);
                $this->set('skill', $skill);
            }

            if (isset($this->params['named']['designation']) && $this->params['named']['designation'] != '') {
                $designation = trim($this->params['named']['designation']);
                $designation = addslashes($designation);
                $this->set('designation', $designation);
            }
            if (isset($this->params['named']['min_salary']) && $this->params['named']['min_salary'] != '') {
                $min_salary = urldecode(trim($this->params['named']['min_salary']));
            }
            if (isset($this->params['named']['max_salary']) && $this->params['named']['max_salary'] != '') {
                $max_salary = urldecode(trim($this->params['named']['max_salary']));
            }
            if (isset($this->params['named']['min_exp']) && $this->params['named']['min_exp'] != '') {
                $min_exp = urldecode(trim($this->params['named']['min_exp']));
            }
            if (isset($this->params['named']['max_exp']) && $this->params['named']['max_exp'] != '') {
                $max_exp = urldecode(trim($this->params['named']['max_exp']));
            }

            if (isset($this->params['named']['searchkey']) && $this->params['named']['searchkey'] != '') {
                $searchkey = urldecode(trim($this->params['named']['searchkey']));
            }
        }



        if (isset($keyword) && $keyword != '') {
            $separator[] = 'keyword:' . urlencode($keyword);
            $keyword = str_replace('_', '\_', $keyword);
            $condition[] = " (`Job`.`title` LIKE '%" . addslashes($keyword) . "%' OR `Job`.`description` LIKE '%" . addslashes($keyword) . "%' OR `Job`.`company_name` LIKE '%" . addslashes($keyword) . "%' ) ";
            $keyword = str_replace('\_', '_', $keyword);
            $this->set('keyword', $keyword);
        }


        if (isset($searchkey) && !empty($searchkey)) {

            $searchkey_arr = explode("-", $searchkey);

            foreach ($searchkey_arr as $id) {
                $condition_search[] = "(FIND_IN_SET('" . $id . "',Job.skill) OR FIND_IN_SET('" . $id . "',Job.designation) )";
            }
            $condition[] = array('OR' => $condition_search);
            $urlSeparator[] = 'searchkey:' . $searchkey;
            $separator[] = 'searchkey:' . $searchkey;
            $this->set('searchkey', $searchkey);
        }

        if (!empty($location)) {
            $location_arr = explode("-", $location);
            foreach ($location_arr as $loc) {
                $condition_location[] = "(FIND_IN_SET('" . $loc . "',Job.location))";
            }
            $condition[] = array('OR' => $condition_location);
            $urlSeparator[] = 'location:' . $location;
            $separator[] = 'location:' . $location;
            $this->set('location', $location);
        }

        if (!empty($worktype)) {

            $worktype_arr = explode("-", $worktype);

            foreach ($worktype_arr as $work) {
                $condition_worktype[] = "(FIND_IN_SET('" . $work . "',Job.work_type))";
            }
            $condition[] = array('OR' => $condition_worktype);
            $urlSeparator[] = 'worktype:' . $worktype;
            $separator[] = 'worktype:' . $worktype;
            $this->set('worktype', $worktype);
        }

        // pr($condition); exit; 
//        if (isset($category_id) && $category_id != '') {
//            $this->set('topcate', $category_id);
//            $separator[] = 'category_id:' . $category_id;
//            $category_idCondtionArray = explode('-', $category_id);
//
//            if (isset($subcategory_id) && $subcategory_id != '') {
//                $this->set('subcate', $subcategory_id);
//                $subcategory_idCondtionArray = explode('-', $subcategory_id);
//                foreach ($subcategory_idCondtionArray as $subMain) {
//                    $subMainVal = $this->Category->field('parent_id', array('Category.id' => $subMain));
//                    if (($key = array_search($subMainVal, $category_idCondtionArray)) !== false) {
//                        unset($category_idCondtionArray[$key]);
//                    }
//                    //   pr($category_idCondtionArray);
//                }
//                // pr($category_idCondtionArray);
//                if ($category_idCondtionArray) {
//                    $subcategory_idCondtion = implode(',', $subcategory_idCondtionArray);
//                    $separator[] = 'subcategory_id:' . $subcategory_id;
//
//                    $category_idCondtion = implode(',', $category_idCondtionArray);
//                    $condition[] = " (Job.category_id IN ($category_idCondtion) OR Job.subcategory_id IN ($subcategory_idCondtion ) )";
//                } else {
//                    $subcategory_idCondtion = implode(',', $subcategory_idCondtionArray);
//                    $condition[] = " (Job.subcategory_id IN ($subcategory_idCondtion ))";
//                    $separator[] = 'subcategory_id:' . $subcategory_id;
//                }
//            } else {
//                $category_idCondtion = implode(',', $category_idCondtionArray);
//                $condition[] = " (Job.category_id IN ($category_idCondtion))";
//            }
//        }

        if (!empty($subcategory_id)) {
            $subcat_arr = explode("-", $subcategory_id);

            foreach ($subcat_arr as $subId) {
                $row[] = $subId;
            }
            $subcategory_idCondtion = implode(',', $row);
            $condition[] = " (Job.subcategory_id IN ($subcategory_idCondtion ) )";
            $urlSeparator[] = 'subcategory_id:' . $subcategory_id;
            $separator[] = 'subcategory_id:' . $subcategory_id;
        }

        if (!empty($skill)) {
            $skill_arr = explode(",", $skill);
            foreach ($skill_arr as $skil) {
                $condition_skill[] = "(FIND_IN_SET('" . $skil . "',Job.skill))";
            }
            $condition[] = array('OR' => $condition_skill);
            $urlSeparator[] = 'skill:' . $skill;
            $separator[] = 'skill:' . $skill;
        }



        if (!empty($designation)) {
            $designation_arr = explode(",", $designation);
            foreach ($designation_arr as $des) {
                $condition_designation[] = "(FIND_IN_SET('" . $des . "',Job.designation))";
            }
            $condition[] = array('OR' => $condition_designation);
            $urlSeparator[] = 'designation:' . $designation;
            $separator[] = 'designation:' . $designation;
        }
        /* if (isset($min_salary) && $min_salary != '') { //echo 'vdsdv';exit;
          $separator[] = 'min_salary:' . urlencode($min_salary);
          $min_salary = str_replace('_', '\_', $min_salary);
          $condition[] = " ( Job.min_salary >= '$min_salary' ) ";
          $min_salary = str_replace('\_', '_', $min_salary);
          }
          if (isset($max_salary) && $max_salary != '') {
          $separator[] = 'max_salary:' . urlencode($max_salary);
          $max_salary = str_replace('_', '\_', $max_salary);
          $condition[] = " ( Job.max_salary <= '$max_salary' ) ";
          $max_salary = str_replace('\_', '_', $max_salary);
          } */

        if ((isset($min_salary) && $min_salary != '') && (isset($max_salary) && $max_salary != '')) {
            $separator[] = 'min_salary:' . urlencode($min_salary);
            $separator[] = 'max_salary:' . urlencode($max_salary);
            $min_salary = str_replace('_', '\_', $min_salary);
            if ($min_salary == $max_salary) {
                $condition[] = " ((Job.min_salary <= $min_salary AND Job.max_salary >= $min_salary)) ";
            } else {
                // $condition[] = " ((Job.min_salary >= $min_salary AND Job.max_salary <= $max_salary) OR (Job.min_salary <= $min_salary )) ";
                $condition[] = " ((Job.min_salary >= $min_salary AND Job.max_salary <= $min_salary) OR (Job.min_salary >= $min_salary AND Job.max_salary <= $max_salary) OR (Job.min_salary = $max_salary ) OR (Job.max_salary = $min_salary )) ";
            }
            $min_salary = str_replace('\_', '_', $min_salary);

            $this->set('min_salary', $min_salary);
            $this->set('max_salary', $max_salary);
        }

        /*  if (isset($min_exp) && $min_exp != '') { //echo 'vdsdv';exit;
          $separator[] = 'min_exp:' . urlencode($min_exp);
          $min_exp = str_replace('_', '\_', $min_exp);
          $condition[] = " ( Job.min_exp >= '$min_exp' ) ";
          $min_exp = str_replace('\_', '_', $min_exp);
          }
          if (isset($max_exp) && $max_exp != '') {
          $separator[] = 'max_exp:' . urlencode($max_exp);
          $max_exp = str_replace('_', '\_', $max_exp);
          $condition[] = " ( Job.max_exp <= '$max_exp' ) ";
          $max_exp = str_replace('\_', '_', $max_exp);
          } */

        if ((isset($min_exp) && $min_exp != '') && (isset($max_exp) && $max_exp != '')) {
            $separator[] = 'min_exp:' . urlencode($min_exp);
            $separator[] = 'max_exp:' . urlencode($max_exp);
            $min_exp = str_replace('_', '\_', $min_exp);

            if ($min_exp == $max_exp) {
                $condition[] = " ((Job.min_exp <= $min_exp AND Job.max_exp >= $min_exp)) ";
            } else {
                //  $condition[] = " ((Job.min_exp >= $min_exp AND Job.max_exp <= $max_exp) OR (Job.min_exp <= $min_exp )  OR (Job.min_exp = $max_exp )) ";

                $condition[] = " ((Job.min_exp >= $min_exp AND Job.max_exp <= $min_exp) OR (Job.min_exp >= $min_exp AND Job.max_exp <= $max_exp) OR (Job.min_exp = $max_exp ) OR (Job.max_exp = $min_exp )) ";
            }
            $min_exp = str_replace('\_', '_', $min_exp);

            $this->set('min_exp', $min_exp);
            $this->set('max_exp', $max_exp);
        }

        $sort = '';
        if (isset($order) && $order != '') {

            $ord = explode(" ", $order);

            $separator[] = 'sort:' . urlencode($ord[0]);
            $sort = str_replace('_', '\_', $order);

            $separator[] = 'order:' . urlencode($ord[1]);
            $order = str_replace('_', '\_', $order);
            $this->set('order', $order);
        }

        //2017-02-18
        $condition[] = array("(Job.category_id != 0)");


        //$order = 'Job.typeAlter Asc, Job.created Desc';
        //pr($condition);exit;
        $separator = implode("/", $separator);
        $urlSeparator = implode("/", $urlSeparator);

        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->paginate['Job'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('jobs', $this->paginate('Job'));


        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'jobs';
            $this->render('job_listing');
        }
    }

    public function reset() {
        $this->layout = "";
        if ($_SESSION['locationid']) {
            unset($_SESSION['locationid']);
        }
        if ($_SESSION['cokkiecount']) {
            unset($_SESSION['cokkiecount']);
        }
        if ($_SESSION['viewed']) {
            unset($_SESSION['viewed']);
        }
        if ($_SESSION['countryName']) {
            unset($_SESSION['countryName']);
        }
        if ($_SESSION['regionName']) {
            unset($_SESSION['regionName']);
        }
        $this->redirect(array('controller' => 'jobs', 'action' => 'index'));
    }

    public function applypop($jobid) {
        $userId = $this->Session->read('user_id');
        $isAbleToJob = $this->Plan->checkPlanFeature($userId, 4);
//        print_r($isAbleToJob);die;
        if ($isAbleToJob['status'] == 0) {
            $this->Session->write('error_msg', $isAbleToJob['message']);
            exit;
            //$this->redirect('/jobs/management');
        }
        $this->layout = "";

        $job = $this->Job->findById($jobid);
        $this->set('job', $job);
        //   pr($this->data);
        $this->set('actionc', $this->data['actionc']);
        $this->viewPath = 'Elements' . DS . 'jobs';
        $this->render('jobapp');
    }

    public function getresumeForm() {
        
    }

    public function getplc() {
        $this->layout = "";

        $this->viewPath = 'Elements' . DS . 'jobs';
        $this->render('cc');
    }

    public function admin_import2() {
        ini_set("memory_limit", "-1");
        ini_set('max_execution_time', '10000');

        $this->layout = "admin";
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Import Jobs");

        $this->set('import_job', 'active');
        $this->set('default', '2');
        global $worktype;
        global $experienceArray;
        global $sallery;
//        echo '<pre>';
//        $xml = @simplexml_load_file("http://localhost/dlcjobs/jobs/feedlist1");
//        if ($xml) {
//            
//        } else {
//            $this->Session->setFlash('Something wrong in xls file.', 'error_msg');
//            $this->redirect('/admin/jobs/import');
//            exit;
//        }
//         exit;
        //var_dump($xml);
//       print_r($xml);
        // die;
//echo '<pre>'; 
//        $xml = simplexml_load_file(UPLOAD_DOCUMENT_PATH . "/Jobs_Format_August.xml") or die("Error: Cannot create object");
//       foreach($xml->children() as $key => $job) {
//            var_dump($job->employer_email);
//  echo $books->employer_email . ", ";
//  echo $books->title . ", ";
//  echo $books->year . ", ";
//  echo $books->price . "<br>";
//}
//        foreach($xml->children() as $key => $children) {
//  print($children->TEMPLATE); echo "<br>";
//  print($children->RECIPIENT_NUM); echo "<br>";
//  // Remaining codes here.
//}
//        $xml = new DOMDocument();
//$xml->load(UPLOAD_DOCUMENT_PATH."/plcjob_all-ch.xml");
//        $data = array (           
//      'template'       => $xml->getElementsByTagName('TEMPLATE')->item(0)->nodeValue,
//       'recipient_num' => $xml->getElementsByTagName('RECIPIENT_NUM')->item(0)->nodeValue          
//        );         
// var_dump($data);
//      echo '<pre>';  print_r($data);
//      die;
//        App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel/PHPExcel.php'));
        // Create new PHPExcel object
//        $file_name = '58af9_Jobseekers_FormatNovember_24_2020_01_00_pm.xlsx';
        if ($this->data) {

//            if (!empty($this->data['User']['filedata']['name'])) {
//                $imageArray = $this->data['User']['filedata'];
//                $imageArray['name'] = str_replace("\_", '_', str_replace(array('%', '$', '#', '%20', "/", "'", ' ', "\'"), '_', $imageArray['name']));
//                $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_DOCUMENT_PATH, "xml");
////                  pr($returnedUploadImageArray);exit;
//                if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
//                    $msgString .= "- File not valid.<br>";
//                    $file_name = '';
//                } else {
//                    $file_name = $returnedUploadImageArray[0];
//                }
//            }
//
//            $file = UPLOAD_DOCUMENT_PATH . '/' . $file_name;
            $file = $this->data['User']['url'];
            $rowNumber = 2;
            $totalInsertedRow = 0;
            $errorArray = array();
            $rowNumberError = array();
            $node_arr = array();
            $k = 0;
            $error = 0;
            $suces = 0;
            $msgString = '';
//            $feed = file_get_contents($file);
//            $items = simplexml_load_string($feed);
            //$xml = simplexml_load_file($file) or die("Error: Cannot create object");
            
            
            
            $xml = simplexml_load_file($file); 
            
            if($xml){
                //echo "continudde";
                foreach ($xml->job->children() as $child) {
                    $node_arr[] = $child->getName();
                    $suces = 1;
                }
    
    
    //            echo join(PHP_EOL, array_unique($names));
    //            echo '<pre>';
    //            print_r($node_arr);
    //            die;
    //            echo '<pre>';
    //            print_r($resourceList);
    //            die;
    //            $this->Session->write('import_jobdata', $resourceList);
                $this->Session->write('import_joburl', $file);
                $this->Session->write('import_node_arr', $node_arr);
    
    
    //            if ($resourceList && !$error) {
    //                $suces = 1;
    //            }
    //            exit;
    
                if ($error == 1) {
                    $this->Session->setFlash($errorMessage, 'error_msg');
                    $this->redirect('/admin/jobs/import');
                    exit;
                } elseif ($suces == 1) {
                    $this->Session->setFlash($file, 'success_msg');
    //                $this->Session->setFlash('Jobs details saved successfully', 'success_msg');
                    $this->redirect('/admin/jobs/autoimport');
                    exit;
                } else {
                    $this->Session->setFlash('Something wrong in XML feed. Please import proper XML feed data.', 'error_msg');
                    $this->redirect('/admin/jobs/import');
                    exit;
                }
            }else{
                $this->Session->setFlash('Something wrong in XML feed URL. Please import proper XML feed URL with data.', 'error_msg');
                $this->redirect('/admin/jobs/import');
                exit;
            }
                
        }
    }
    
    public function admin_import() {
        ini_set("memory_limit", "-1");
        ini_set('max_execution_time', '10000');

        $this->layout = "admin";
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Import Jobs");

        $this->set('import_job', 'active');
        $this->set('default', '2');
        global $worktype;
        global $experienceArray;
        global $sallery;
//        echo '<pre>';
//        $xml = @simplexml_load_file("http://localhost/dlcjobs/jobs/feedlist1");
//        if ($xml) {
//            
//        } else {
//            $this->Session->setFlash('Something wrong in xls file.', 'error_msg');
//            $this->redirect('/admin/jobs/import');
//            exit;
//        }
//         exit;
        //var_dump($xml);
//       print_r($xml);
        // die;
//echo '<pre>'; 
//        $xml = simplexml_load_file(UPLOAD_DOCUMENT_PATH . "/Jobs_Format_August.xml") or die("Error: Cannot create object");
//       foreach($xml->children() as $key => $job) {
//            var_dump($job->employer_email);
//  echo $books->employer_email . ", ";
//  echo $books->title . ", ";
//  echo $books->year . ", ";
//  echo $books->price . "<br>";
//}
//        foreach($xml->children() as $key => $children) {
//  print($children->TEMPLATE); echo "<br>";
//  print($children->RECIPIENT_NUM); echo "<br>";
//  // Remaining codes here.
//}
//        $xml = new DOMDocument();
//$xml->load(UPLOAD_DOCUMENT_PATH."/plcjob_all-ch.xml");
//        $data = array (           
//      'template'       => $xml->getElementsByTagName('TEMPLATE')->item(0)->nodeValue,
//       'recipient_num' => $xml->getElementsByTagName('RECIPIENT_NUM')->item(0)->nodeValue          
//        );         
// var_dump($data);
//      echo '<pre>';  print_r($data);
//      die;
//        App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel/PHPExcel.php'));
        // Create new PHPExcel object
//        $file_name = '58af9_Jobseekers_FormatNovember_24_2020_01_00_pm.xlsx';
        if ($this->data) {

//            if (!empty($this->data['User']['filedata']['name'])) {
//                $imageArray = $this->data['User']['filedata'];
//                $imageArray['name'] = str_replace("\_", '_', str_replace(array('%', '$', '#', '%20', "/", "'", ' ', "\'"), '_', $imageArray['name']));
//                $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_DOCUMENT_PATH, "xml");
////                  pr($returnedUploadImageArray);exit;
//                if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
//                    $msgString .= "- File not valid.<br>";
//                    $file_name = '';
//                } else {
//                    $file_name = $returnedUploadImageArray[0];
//                }
//            }
//
//            $file = UPLOAD_DOCUMENT_PATH . '/' . $file_name;
            $file = $this->data['User']['url'];
            $rowNumber = 2;
            $totalInsertedRow = 0;
            $errorArray = array();
            $rowNumberError = array();
            $node_arr = array();
            $k = 0;
            $error = 0;
            $suces = 0;
            $msgString = '';
            $feed = file_get_contents($file);
            $items = simplexml_load_string($feed);
            $xml = simplexml_load_file($file) or die("Error: Cannot create object");
            
            $linkToXmlFile = "compress.zlib://$file";
            
            
            
           // $xml = new XMLReader();
           // $xml->open($linkToXmlFile);
//echo "<pre>"; print_r($xml);exit;
            
            //$xml = simplexml_load_file($file); 
            // echo "<pre>"; print_r($xml);exit;
            
            if($xml){
                //echo "continudde";
                foreach ($xml->job->children() as $child) {
                    $node_arr[] = $child->getName();
                    $suces = 1;
                }
    
    
    //            echo join(PHP_EOL, array_unique($names));
    //            echo '<pre>';
    //            print_r($node_arr);
    //            die;
    //            echo '<pre>';
    //            print_r($resourceList);
    //            die;
    //            $this->Session->write('import_jobdata', $resourceList);
                $this->Session->write('import_joburl', $file);
                $this->Session->write('import_node_arr', $node_arr);
    
    
    //            if ($resourceList && !$error) {
    //                $suces = 1;
    //            }
    //            exit;
   
                if ($error == 1) {
                    $this->Session->setFlash($errorMessage, 'error_msg');
                    $this->redirect('/admin/jobs/import');
                    exit;
                } elseif ($suces == 1) {
                    $this->Session->setFlash($file, 'success_msg');
    //                $this->Session->setFlash('Jobs details saved successfully', 'success_msg');
                    $this->redirect('/admin/jobs/autoimport');
                    exit;
                } else {
                    $this->Session->setFlash('Something wrong in XML feed. Please import proper XML feed data.', 'error_msg');
                    $this->redirect('/admin/jobs/import');
                    exit;
                }
            }else{
                $this->Session->setFlash('Something wrong in XML feed URL. Please import proper XML feed URL with data.', 'error_msg');
                $this->redirect('/admin/jobs/import');
                exit;
            }
                
        }
    }

    public function admin_autoimport() {
        ini_set("memory_limit", "-1");
        ini_set('max_execution_time', '10000');
        // get user name and id list and set it to dropdown
        $users = $this->User->find('all', array('fields' => array('User.id', 'User.first_name', 'User.last_name'), 'conditions' => array('User.user_type' => 'recruiter')));
        $fullName = Set::combine($users, '{n}.User.id', array('{0} {1}', '{n}.User.first_name', '{n}.User.last_name'));
        $this->set('fullname', $fullName);
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Auto Job Import List");
        global $worktype;
        global $experienceArray;
        global $sallery;

        $this->set('import_list', 'active');
//        $resourceList = $this->Session->read('import_jobdata');
        $file = $this->Session->read('import_joburl');
        $import_node_arr = $this->Session->read('import_node_arr');
        $this->set('node_arr', array_combine($import_node_arr, $import_node_arr));
//        echo '<pre>';
//            print_r(array_combine($import_node_arr, $import_node_arr));
//            die;
        $catekeywords = $this->Category->find('list', array('conditions' => array('Category.status' => 1, 'Category.parent_id' => 0, 'Category.keywords !=' => ''), 'fields' => array('Category.keywords'), 'order' => 'Category.keywords ASC'));

//        echo '<pre>';
//        print_r($file);
//        print_r($catekeywords);
//        die;

        if (empty($file)) {
            $this->Session->setFlash('Something wrong in xls feed.', 'error_msg');
            $this->redirect('/admin/jobs/import');
        }
        if ($this->data) {


            if (empty($this->data["Feed"]["name"])) {
                $msgString .= "- Import Name is required field.<br>";
            }


            if (isset($msgString) && $msgString != '') {
                // show message when finds error
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
// pr($this->data);

                $feedname = $this->data['Feed']['name'];
                $feed_url = $this->data['Feed']['feed_url'];
                $feeduser_id = $this->data['Feed']['user_id'];
                $feedapplyurl = $this->data['Feed']['applyurl'];
                $feedexpire = $this->data['Feed']['expire'];
                $feedcompany_name = $this->data['Feed']['company_name'];
                $feedaddress = $this->data['Feed']['address'];
                $feedlogo = $this->data['Feed']['logo'];
                $feedbrief_abtcomp = $this->data['Feed']['brief_abtcomp'];
                $feedjob_city = $this->data['Feed']['job_city'];
                $feedurl = $this->data['Feed']['url'];
                $feedtitle = $this->data['Feed']['title'];
                $feedjob_id = $this->data['Feed']['job_id'];
                $feedcategory_id = $this->data['Feed']['category_id'];
                $feedsalary = $this->data['Feed']['salary'];
                $feeddescription = $this->data['Feed']['description'];
                $feedwork_type = $this->data['Feed']['work_type'];
                $feedskills = $this->data['Feed']['skills'];
                $feeddesignation = $this->data['Feed']['designation'];
                $feedexp = $this->data['Feed']['exp'];
                $feedexpirydate = $this->data['Feed']['expirydate'];
                $feedposteddate = $this->data['Feed']['posteddate'];

                $findfeedid = 0;
                $k = 0;
                $xml = simplexml_load_file($file);
                foreach ($xml->children() as $key => $job) {
//                    pr($this->$feedtitle);
//                    pr(trim($job->{$feedtitle}));
//                 echo $feedtitle;  // pr(trim($job->title));
//                    die;
                    $location_city = '';
                    $error = '';
                    if ($feedjob_city == 'locations') {
                        if (isset($job->locations)) {
                            $location_city = trim($job->locations->location->city) . ', ' . trim($job->locations->location->state);
                        }
                    } else if ($feedjob_city) {
                        $location_city = trim($job->{$feedjob_city});
                    }
//                    echo $location_city;die;
//                pr($job->employer_email);
//                foreach ($job->locations->children() as $locations) {
//                   $location_city = $locations->location->city . ', ' . $locations->location->state ;
//                
//                }
//                pr($location_city);
//                die;
//            for ($dd = 2; $dd <= $highestRow; $dd++) {
                    $title = '';
                    if ($feedtitle) {
                        $title = $job->{$feedtitle};
                    }
                    $jobid = '';
                    if ($feedjob_id) {
                        $jobid = $job->{$feedjob_id};
                    }
                    $description = '';
                    if ($feeddescription) {
                        $description = $job->{$feeddescription};
                    }
                    $category_id = '';
                    if ($feedcategory_id) {
                        $category_id = $job->{$feedcategory_id};
                    }
                    $company_name = '';
                    if ($feedcompany_name) {
                        $company_name = $job->{$feedcompany_name};
                    }
                    $address = '';
                    if ($feedaddress) {
                        $address = $job->{$feedaddress};
                    }
                    $logo = '';
                    if ($feedlogo) {
                        $logo = $job->{$feedlogo};
                    }
                    $worktypes = '';
                    if ($feedwork_type == 'workingTimes') {
                        if (isset($job->workingTimes)) {
                            $worktypes = trim($job->workingTimes->item);
                        }
                    } else
                    if ($feedwork_type) {
                        $worktypes = $job->{$feedwork_type};
                    }
                    $brief_abtcomp = '';
                    if ($feedbrief_abtcomp) {
                        $brief_abtcomp = $job->{$feedbrief_abtcomp};
                    }
                    $contact_number = '';
                    if ($feedcompany_name) {
                        $contact_number = $job->{$feedcompany_name};
                    }
                    $company_website = '';
                    if ($feedurl) {
                        $company_website = $job->{$feedurl};
                    }
                    $experience = '';
                    if ($feedexp) {
                        $experience = $job->{$feedexp};
                    }
                    $salary = '';
                    if ($feedsalary) {
                        $salary = $job->{$feedsalary};
                    }
                    $skills = '';
                    if ($feedskills) {
                        $skills = $job->{$feedskills};
                    }
                    $designation = '';
                    if ($feeddesignation) {
                        $designation = $job->{$feeddesignation};
                    }
                    $expire = '';
                    if ($feedexpire) {
                        $expire = $job->{$feedexpire};
                    }
                    $expirydate = '';
                    if ($feedexpirydate) {
                        $expirydate = $job->{$feedexpirydate};
                    }
                    $posteddate = '';
                    if ($feedposteddate) {
                        $newdate = $job->{$feedposteddate};
                        if (strpos($newdate, '.')) {
                            list($day, $month, $year) = explode('.', $newdate);
                            $posteddate = $year . '-' . $month . '-' . $day . ' ' . date('H:i:s');
                        } else if (strpos($newdate, '/')) {
                            list($day, $month, $year) = explode('/', $newdate);
                            $posteddate = $year . '-' . $month . '-' . $day . ' ' . date('H:i:s');
                        }
                    }
                    $applyurl = '';
                    if ($feedapplyurl) {
                        $applyurl = $job->{$feedapplyurl};
                    }



                    $resourceList[$k]['jobid'] = trim($jobid);
                    $resourceList[$k]['title'] = trim($title);
                    $resourceList[$k]['description'] = trim($description);
                    $resourceList[$k]['company_name'] = trim($company_name);
                    $resourceList[$k]['address'] = trim($address);
                    $resourceList[$k]['category_id'] = trim($category_id);
                    $resourceList[$k]['logo'] = trim($logo);
                    $resourceList[$k]['work_type'] = trim($worktypes);
                    $resourceList[$k]['brief_abtcomp'] = trim($brief_abtcomp);
                    $resourceList[$k]['contact_number'] = trim($contact_number);
                    $resourceList[$k]['url'] = trim($company_website);
                    $resourceList[$k]['exp'] = trim($experience);
                    $resourceList[$k]['salary'] = trim($salary); //column added
                    $resourceList[$k]['skill'] = trim($skills); //column added
                    $resourceList[$k]['designation'] = trim($designation);
                    $resourceList[$k]['job_city'] = trim($location_city);
//                    $resourceList[$k]['expire'] = trim($expire);
                    $resourceList[$k]['expirydate'] = trim($expirydate);
                    $resourceList[$k]['posteddate'] = trim($posteddate);
                    $resourceList[$k]['applyurl'] = trim($applyurl);
                    $k++;
                    if ($k == 1000) {
                        break;
                    }
                }
                // pr($resourceList);die;
//                $this->request->data = array();
                $this->Feed->create();
//                $this->request->data['Feed']['name'] = $feedname;
                $this->request->data['Feed']['feed_url'] = $feed_url;
//                $this->request->data['Feed']['user_id'] = $feeduser_id;
//                $this->request->data['Feed']['expire'] = $feedexpire;
                $this->request->data['Feed']['slug'] = $this->stringToSlugUnique($feedname, 'Feed', 'slug');
                $this->request->data['Feed']['status'] = '1';
                //print_r($this->data);
                // die;
                $this->Feed->save($this->data);
                $findfeedid = $this->Feed->id;

//echo $findfeedid;die;
                if ($resourceList) {
                    $sr = 1;
                    $NewArray = array();
                    $errorMessage = '';

                    foreach ($resourceList as $resource) {
//                        pr($resource);die;
                        $category_id = 0;
                        $subcategory_id = 0;

//                        $email = trim($resource['email']);
                        $jobid = trim($resource['jobid']);
                        $title = trim($resource['title']);
                        $category = trim($resource['category_id']);
                        $description = trim($resource['description']);
                        $company_name = trim($resource['company_name']);
                        $work_type = trim($resource['work_type']);
                        $contact_name = trim($resource['contact_name']);
                        $brief_abtcomp = trim($resource['brief_abtcomp']);
                        $address = trim($resource['address']);
                        $url = trim($resource['url']);
                        $applyurl = trim($resource['applyurl']);
                        $logo = trim($resource['logo']);
//                        $contact_number = trim($resource['contact_number']);

                        $exp = trim($resource['exp']);
                        $salary = trim($resource['salary']);
                        $skill = trim($resource['skill']);
                        $designation = trim($resource['designation']);
                        $job_city = trim($resource['job_city']);
//                        $expire = trim($resource['expire']);
                        $expirydate = trim($resource['expirydate']);
                        $posteddate = trim($resource['posteddate']);
                        if ($expirydate && strpos('-', $expirydate) && date('d', strtotime($expirydate))) {
                            $expire_time = date('Y-m-d H:i:s', strtotime('+' . date('d', strtotime($expirydate)) . ' days'));
                        } else {
                            $expire_time = date('Y-m-d H:i:s', strtotime('+7 days'));
                        }


//                        $category = $resource["category"];
//                        $condition = array();
//                        $condition[] = "(FIND_IN_SET('" . $category . "',Category.keywords) )";
//                        ;
//                        $category = $this->Category->find('first', array('conditions' => $condition, 'fields' => array('Category.id')));
                        $condition = array();
                        $condition[] = " (Category.name like '" . addslashes($category) . "')  ";
                        $findcatid = $this->Category->field('id', array('Category.name' => $feedcategory_id));
                        if ($findcatid) {
                            $category_id = $findcatid;
                        } else if ($catekeywords) {
                            foreach ($catekeywords as $catid => $catcomma) {
                                $catkeyarray = explode(',', $catcomma);
                                if ($catkeyarray) {
                                    foreach ($catkeyarray as $catkey) {
                                        if ($catkey) {
                                            $des_exists = stripos($description, trim($catkey));
                                            $titexists = stripos($title, trim($catkey));
                                            if ($des_exists !== false || $titexists !== false) {
                                                $category_id = $catid;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        // echo $category_id ; echo '<br>';
                        if ($applyurl) {
                            $apply_url = $applyurl;
                        }
//                        else if ($url) {
//                            $apply_url = trim($resource['url']);
//                        } 
                        else {
                            $apply_url = '';
                        }

                        if ($feeduser_id) {
                            $userId = $feeduser_id;
                        }
//                        else if ($email) {
//                            $userId = $this->User->field('User.id', array('User.email_address' => $email));
//                            $userId = 0;
//                        }
                        else {
                            $userId = 0;
                            // break;
                        }
//                        echo 'cate'.$category_id;
//                        echo '<br>';
//                        echo 'user'.$userId;

                        if (!$category_id)
                            continue;

//                        if ($userId == 0)
//                            continue;
//                        pr($worktype);

                        if ($work_type == 'fulltime' || $work_type == 'parttime') {
                            if ($work_type == 'fulltime') {
                                $work_type_id = 1;
                            } else {
                                $work_type_id = 6;
                            }
                        } else if ($work_type) {
                            $work_type_id = array_search(strtolower($work_type), array_map('strtolower', $worktype));
                        } else {
//                            $work_type_id = 1;  // default assigned value
                        }
                        if ($salary) {
                            $sallery_array = array_keys($sallery);
                            foreach ($sallery_array as $salrange) {
                                list($minsal, $maxsal) = explode('-', $salrange);
                                if ($minsal <= $salary && $maxsal >= $salary) {
                                    break;
                                }
                            }
                        } else {
//                            $sallery_array = array_keys($sallery);
//                            list($minsal, $maxsal) = explode('-', $sallery_array[0]); // default assigned value
                        }


                        if ($exp) {
                            $exper_array = array_keys($experienceArray);
                            foreach ($exper_array as $experrange) {
                                list($minexp, $maxexp) = explode('-', $experrange);
                                if ($minexp <= $exp && $maxexp >= $exp) {
                                    break;
                                }
                            }
                        } else {
                            $exper_array = array_keys($experienceArray);
//                            list($minexp, $maxexp) = explode('-', $exper_array[0]); // default assigned value
                        }

                        $findskillids = array();
                        $skillarr = explode(',', $skill);
                        if ($skillarr) {
                            foreach ($skillarr as $skillval) {
                                if ($skillval) {
                                    $condition = array('Skill.type' => 'Skill');
                                    $condition[] = " (Skill.name like '%" . addslashes($skillval) . "%')  ";
                                    $findskillid = $this->Skill->field('id', $condition);
//                                if (!$findskillid) {
//                                    $skillDetail = $this->Skill->find('first', array('conditions' => array('Skill.type' => 'Skill')));
//                                    $findskillid = $skillDetail['Skill']['id'];
//                                    $this->request->data = array();
//                                    $this->Skill->create();
//                                    $this->request->data['Skill']['name'] = $skill;
//                                    $this->request->data['Skill']['slug'] = $this->stringToSlugUnique($skillval, 'Skill', 'slug');
//                                    $this->request->data['Skill']['status'] = '1';
//                                    $this->request->data['Skill']['type'] = 'Skill';
//                                    $this->Skill->save($this->data);
//                                    $findskillid = $this->Skill->id;
//                                }
//                                if ($findskillid)
//                                    $findskillids [] = $findskillid;
                                }
                            }
                        }
                        $newfname = '';
                        if ($logo) {
                            $Filename = $logo;

                            $newfname = basename($Filename);
                            $newfurl = UPLOAD_JOB_LOGO_PATH . $newfname;
                            $file = fopen($Filename, 'rb');
                            if ($file) {
                                $newf = fopen($newfurl, 'wb');
                                if ($newf) {
                                    while (!feof($file)) {
                                        fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                                    }
                                }
                            }
                            if ($file) {
                                fclose($file);
                            }
                            if ($newf) {
                                fclose($newf);
                            }
                        }



                        if ($designation) {


                            $condition = array('Skill.type' => 'Designation');
                            $condition[] = " (Skill.name like '%" . addslashes($designation) . "%')  ";
                            $finddesid = $this->Skill->field('id', $condition);
                        }
//                        if (!$finddesid) {
//                            $skillDetail = $this->Skill->find('first', array('conditions' => array('Skill.type' => 'Designation')));
//                            $finddesid = $skillDetail['Skill']['id'];
//                            $this->request->data = array();
//                            $this->Skill->create();
//                            $this->request->data['Skill']['name'] = $designation;
//                            $this->request->data['Skill']['slug'] = $this->stringToSlugUnique($designation, 'Skill', 'slug');
//                            $this->request->data['Skill']['status'] = '1';
//                            $this->request->data['Skill']['type'] = 'Designation';
//                            $this->Skill->save($this->data);
//                            $finddesid = $this->Skill->id;
//                        }

                        $recruiterInfo = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
                        $companyname = $recruiterInfo ? $recruiterInfo['User']['company_name'] : $company_name;
//                        $company_contact = $recruiterInfo?$recruiterInfo['User']['company_contact']:$company_name;
                        $company_contact = $recruiterInfo ? $recruiterInfo['User']['email_address'] : '';
//                        $brief_abtcomp = $recruiterInfo['User']['brief_abtcomp'];
                        $company_contact = $recruiterInfo ? $recruiterInfo['User']['company_contact'] : '';

//                        $category_id = $category['Category']['id'];
                        $jobCheck = array();

                        if ($jobid) {
                            $jobCheck = $this->Job->find("first", array("conditions" => array("Job.job_id" => $jobid)));
                        }



                        $this->Job->create();
                        $this->request->data = array();
                        if ($jobCheck) {
                            $this->request->data["Job"]["id"] = $jobCheck["Job"]["id"];
                        } else {
                            if ($userId) {
                                $isAbleToJob = $this->Plan->checkPlanFeature($user_id, 4);
                                $this->request->data['Job']['user_plan_id'] = $isAbleToJob['user_plan_id'];
                            }
                        }
                        $this->request->data["Job"]["job_id"] = $jobid;
                        $this->request->data["Job"]["title"] = $title;
                        $this->request->data["Job"]["category_id"] = $category_id;
                        $this->request->data["Job"]["subcategory_id"] = 0;
//                        $this->request->data["Job"]["skill"] = $skillId;
                        $this->request->data["Job"]["designation"] = $finddesid;
                        $this->request->data["Job"]["feed_id"] = $findfeedid;
                        $this->request->data["Job"]["work_type"] = $work_type_id;
                        $this->request->data["Job"]["min_salary"] = $minsal;
                        $this->request->data["Job"]["max_salary"] = $maxsal;
                        $this->request->data["Job"]["min_exp"] = $minexp;
                        $this->request->data["Job"]["max_exp"] = $maxexp;
                        $this->request->data["Job"]["job_city"] = $job_city;
                        $this->request->data["Job"]["apply_url"] = $applyurl;
                        $this->request->data["Job"]["url"] = $url;
                        $this->request->data["Job"]["logo"] = $newfname;
                        $this->request->data["Job"]["description"] = $description;


                        $this->request->data["Job"]["company_name"] = $companyname;
                        $this->request->data["Job"]["contact_name"] = $companyname;
                        $this->request->data["Job"]["address"] = $address;
                        $this->request->data["Job"]["brief_abtcomp"] = $brief_abtcomp;
                        $this->request->data["Job"]["contact_number"] = $company_contact ? $company_contact : '';

                        $slug = $this->stringToSlugUnique($title, 'Job');
                        $this->request->data['Job']['slug'] = $slug;
                        $this->request->data['Job']['type'] = 'Gold';
                        ;
                        $this->request->data['Job']['status'] = 1;
                        $this->request->data['Job']['user_id'] = $userId;
                        $this->request->data['Job']['payment_status'] = 2;
                        $this->request->data['Job']['amount_paid'] = 180;
                        $this->request->data['Job']['job_number'] = 'JOB' . $userId . time();
                        $this->request->data['Job']['hot_job_time'] = time() + 7 * 24 * 3600;
                        $this->request->data['Job']['expire_time'] = strtotime($expire_time);
                        if ($posteddate && $posteddate > 0) {
                            $this->request->data['Job']['created'] = $posteddate;
                        } else {
                            $this->request->data['Job']['created'] = date('Y-m-d H:i:s');
                        }

                        $this->request->data['Job']['skill'] = implode(',', $findskillids);
                        if ($userId) {
                            $isAbleToJob = $this->Plan->checkPlanFeature($user_id, 4);
                            $this->request->data['Job']['user_plan_id'] = $isAbleToJob['user_plan_id'];
                        }
                        $this->Job->save($this->data);
                    }
//                    exit;

                    $this->Session->setFlash('Jobs import successfully', 'success_msg');
                    $this->redirect('/admin/jobs/importlist');
//                    exit;
                }
            }
        } else {

            $resourceList = $this->Session->read('import_jobdata');
            $import_joburl = $this->Session->read('import_joburl');
            $node_arr = $this->Session->read('import_node_arr');
            $this->request->data["Feed"]["feed_url"] = $import_joburl;
            $this->request->data["Feed"]["name"] = $import_joburl;
            $this->request->data["Feed"]["node_arr"] = $node_arr;
//             echo '<pre>';            print_r($this->data);die;
        }
    }

    public function stellenanzeigenfeedlist() {
        echo "<?xml version='1.0' encoding='utf-8'?>
          <jobs>
          <job>
			<ID>3953417</ID>
			<URL>https://www.stellenanzeigen.de/job/3953417/?campaign=plcjob&amp;utm_id=plcjob&amp;utm_source=plcjob&amp;utm_medium=anzeigenfeed&amp;utm_campaign=plcjob&amp;subkategorie=83X</URL>
			<DATUM>28.09.2021</DATUM>
			<POSITION>Ingenieur (m/w/d)</POSITION>
			<UNTERNEHMEN>Unfallkasse Berlin</UNTERNEHMEN>
			<RUBRIKEN>13,34</RUBRIKEN>
			<SUBRUBRIKEN>83,88,152,262</SUBRUBRIKEN>
			<EIGENSCHAFTEN>9,16,17,22,24</EIGENSCHAFTEN>
			<EINSATZORTTEXT>Berlin</EINSATZORTTEXT>
			<EINSATZORTGEO>12277</EINSATZORTGEO>
			<POSITIONSZUSATZ>Ihr Aufgabengebiet nach dem Abschluss der Ausbildung ist sehr vielseitig und beinhaltet die Beratung und &#220;berwachung kommunaler und staatlicher Einrichtungen bzw. Verwaltungen zu Fragen von Sicherheit und Gesundheit bei der Arbeit;...</POSITIONSZUSATZ>
			<VOLLTEXT>Ingenieur (m/w/d) Ingenieur (m/w/d), vorzugsweise der  Elektrotechnik Die Unfallkasse Berlin (www.unfallkasse-berlin.de) ist Tr&#228;gerin der gesetzlichen Unfallversicherung f&#252;r das Land Berlin. Sie hat die gesetzliche Aufgabe, Unf&#228;llen am Arbeitsplatz, in der Schule und auf den damit verbundenen Wegen vorzubeugen und sie mit allen geeigneten Mitteln zu verh&#252;ten. Ebenso soll sie Berufskrankheiten und arbeitsbedingten Gesundheitsgefahren vorbeugen und diese verhindern. F&#252;r die Abteilung Pr&#228;vention suchen wir zum n&#228;chstm&#246;glichen Zeitpunkt einen: Ingenieur (m/w/d), vorzugsweise der Elektrotechnik mit mindestens zweij&#228;hriger einschl&#228;giger Berufserfahrung nach dem Studienabschluss zur Ausbildung als Aufsichtsperson nach &#167; 18 SGB VII. Nach erfolgreich abgeschlossener Ausbildung ist der Einsatz als Aufsichtsperson in einem dann unbefristeten Besch&#228;ftigungsverh&#228;ltnis nach dem BG-AT (analog TV&#246;D-Bund) vorgesehen. Wir bieten: einen sicheren Arbeitsplatz mit einem selbstst&#228;ndigen, eigenverantwortlichen und facettenreichen Arbeitsbereich sowie die im &#246;ffentlichen Dienst &#252;blichen Sozialleistungen. Das Entgelt richtet sich w&#228;hrend der Ausbildung nach Entgeltgruppe 11 BG-AT (analog TV&#246;D-Bund), nach erfolgreich abgeschlossener Ausbildung, &#220;bertragung der T&#228;tigkeit als Aufsichtsperson und der Erf&#252;llung aller tariflichen Voraussetzungen nach der Entgeltgruppe 12 BG-AT. Weiterhin bieten wir Ihnen:   30 Tage Erholungsurlaub pro Jahr    Arbeitsfreier Heiligabend und Silvestertag    Flexible Arbeitszeitmodelle    M&#246;glichkeit des mobilen Arbeitens incl. dienstlich gestellter Hardware (Laptop + IPhone)    Betriebliches Gesundheitsmanagement und Betriebssport    Ein breites Angebot an fachlichen und pers&#246;nlichen Fort-/Weiterbildungsm&#246;glichkeiten    Betriebliche Altersvorsorge und die zus&#228;tzliche M&#246;glichkeit von Entgeltumwandlung    Vollst&#228;ndig ergonomisch eingerichtete B&#252;roarbeitspl&#228;tze  Ihre Aufgaben: W&#228;hrend Ihrer zweij&#228;hrigen Ausbildung zur Aufsichtsperson mit anschlie&#223;ender Pr&#252;fung erwerben Sie zun&#228;chst die erforderlichen fachlichen und methodischen Kompetenzen zur Durchf&#252;hrung des gesetzlichen Beratungs- und &#220;berwachungsauftrages. Ihr Aufgabengebiet nach dem Abschluss der Ausbildung ist sehr vielseitig und beinhaltet die Beratung und &#220;berwachung kommunaler und staatlicher Einrichtungen bzw. Verwaltungen zu Fragen von Sicherheit und Gesundheit bei der Arbeit. Weiterhin leiten Sie Seminare zu den entsprechenden Themen, arbeiten in Projekten und Arbeitskreisen mit und ermitteln die Ursachen von Arbeitsunf&#228;llen sowie Berufskrankheiten. Dabei sind Sie h&#228;ufig im Au&#223;endienst t&#228;tig. Voraussetzungen: Sie haben einen Bachelor-, Diplom- oder Masterabschluss der gew&#252;nschten Fachrichtung Zwingend ist eine mindestens zweij&#228;hrige Berufserfahrung nach dem Studium in der gew&#252;nschten Fachrichtung vorzuweisen. Sie k&#246;nnen &#252;berzeugend auftreten und gut argumentieren, sind zur kooperativen Zusammenarbeit mit unseren Versicherten und Partnern bef&#228;higt und sind flexibel und engagiert. F&#252;r R&#252;ckfragen steht Ihnen Frau Elsholz, Tel. 030/7624-1300, Email: d.elsholz@unfallkasse-berlin.de gern zur Verf&#252;gung Ihre schriftliche Bewerbung richten Sie bitte bis zum 06.10.2021 unter Angabe der Kennzahl 04 / 2021 an die Unfallkasse Berlin -z.H. Frau B&#246;ttcher- Culemeyerstra&#223;e 2, 12277 Berlin bzw. per Mail an s.boettcher@unfallkasse-berlin.de Die Unfallkasse Berlin f&#246;rdert die berufliche Chancengleichheit aller Geschlechter. Schwerbehinderte Personen werden bei gleicher Eignung bevorzugt. Wir begr&#252;&#223;en ausdr&#252;cklich die Bewerbung von Menschen aller Nationalit&#228;ten.</VOLLTEXT>
			<LOGO>https://anzeigen.jobstatic.de/upload/logos/6/134606.gif</LOGO>
			<BEWERBUNGSLINK>s.boettcher@unfallkasse-berlin.de</BEWERBUNGSLINK>
			<KEYWORDS>Ingenieur,Forschung,Ingenieurwesen,Elektronik,Automatisierungstechnik,Fertigungstechnik,Qualit&#228;tsmanagement,Physikalische,Qualit&#228;tssicherung,Magnetresonanz,Magnetische,Simulationen,Diplomingenieur</KEYWORDS>
			<SIMPLEHTML></SIMPLEHTML>
			<KLICKS></KLICKS>
			<BRANCHENID>130000</BRANCHENID>
		</job>
                <job>
			<ID>3929251</ID>
			<URL>https://www.stellenanzeigen.de/job/3929251/?campaign=plcjob&amp;utm_id=plcjob&amp;utm_source=plcjob&amp;utm_medium=anzeigenfeed&amp;utm_campaign=plcjob&amp;subkategorie=57X</URL>
			<DATUM>28.09.2021</DATUM>
			<POSITION>Stellvertretender Abteilungsleiter (m/w/d) IT - Infrastruktur</POSITION>
			<UNTERNEHMEN>EDEKA Handelsgesellschaft S&#252;dwest mbH Personalentwicklung</UNTERNEHMEN>
			<RUBRIKEN>10</RUBRIKEN>
			<SUBRUBRIKEN>57,58</SUBRUBRIKEN>
			<EIGENSCHAFTEN>10,16,17,18,19,22,24</EIGENSCHAFTEN>
			<EINSATZORTTEXT>Balingen, Offenburg, W&#252;rzburg</EINSATZORTTEXT>
			<EINSATZORTGEO>72336,77652,97070</EINSATZORTGEO>
			<POSITIONSZUSATZ>Stellvertretende Leitung der Abteilung IT-Infrastruktur; fachliche und disziplinarische F&#252;hrung sowie Weiterentwicklung des IT-Teams in Vertretung des Abteilungsleiters; Projektleitung bzw. Teilprojektleitung von IT-Projekten;...</POSITIONSZUSATZ>
			<VOLLTEXT>Mach was dein Herz dir sagt! Die EDEKA Rechenzentrum S&#252;d GmbH mit Sitz in Offenburg ist eine gemeinsame IT-Tochtergesellschaft der beiden EDEKA Regionalgesellschaften S&#252;dwest und EDEKA Nordbayern Sachsen-Th&#252;ringen.  Die EDEKA Rechenzentrum S&#252;d GmbH ist als IT-Servicegesellschaft der Betreiber, Entwickler und Servicepartner f&#252;r IT-technische L&#246;sungen der beiden Regionalgesellschaften EDEKA S&#252;dwest und EDEKA Nordbayern Sachsen-Th&#252;ringen und deren Tochtergesellschaften.  Mit &#252;ber 100 Mitarbeitern gew&#228;hrleistet die EDEKA Rechenzentrum S&#252;d GmbH eine sehr hohe Servicequalit&#228;t, Zuverl&#228;ssigkeit und IT Sicherheit. F&#252;r unseren Standort Balingen, Offenburg, W&#252;rzburg suchen wir Sie als stellvertretender Abteilungsleiter (m/w/d) IT - Infrastruktur Ihre Aufgaben   stellvertretende Leitung der Abteilung IT-Infrastruktur    fachliche und disziplinarische F&#252;hrung sowie Weiterentwicklung des IT-Teams in Vertretung des Abteilungsleiters    strategische Planung der IT-Landschaft f&#252;r die EDEKA Rechenzentrum S&#252;d GmbH und deren Kunden in Zusammenarbeit mit der Abteilungsleitung und dem Produktverantwortlichen    Mitverantwortung f&#252;r Budgetplanung/-kontrolle, Ressourcenplanung sowie Lizenzen und Wartungsvertr&#228;ge    Projektleitung bzw. Teilprojektleitung von IT-Projekten    Steuerung der Leistungserbringung von externen Dienstleistern    kompetente/r Ansprechpartner/in f&#252;r das Team und die Produktfelder der IT-Infrastruktur  Ihre Voraussetzungen   abgeschlossenes Studium (Informatik) oder durch Berufserfahrung und Fortbildungsma&#223;nahmen erworbene gleichwertige Qualifikationen    langj&#228;hrige Praxiserfahrungen in der IT-Betreuung und Weiterentwicklung der IT-Landschaft und in vergleichbarer Position    fundierte Kenntnisse:           VMware Cloud Foundation (vRealize, vSAN, NSX)          Container-Technologien (Kubernetes, Docker)          Unix-basierten Betriebssystemen (zum Beispiel SUSE Linux)         umfassende Erfahrungen im IT-Projekt- und -Prozessmanagement    ausgepr&#228;gte soziale Kompetenzen, ganzheitliches Denken, Kommunikationssicherheit, Umsetzungsst&#228;rke und Hands-on-Mentalit&#228;t    hohe Flexibilit&#228;t – insbesondere im Hinblick auf die Arbeitszeiten (Bereitschaftsdienst)    Reisebereitschaft/Mobilit&#228;t  Ihre Vorteile   Wir bieten einen attraktiven, sicheren Arbeitsplatz    Sie erwartet eine anspruchsvolle, abwechslungsreiche T&#228;tigkeit    Sie erhalten ein leistungsgerechtes Einkommen    Wir stellen Ihnen alle sozialen Leistungen eines fortschrittlichen Unternehmens zur Verf&#252;gung  Interessiert? Wir freuen uns auf Ihre Bewerbung mit Angabe Ihrer  Verf&#252;gbarkeit und Gehaltsvorstellung. Ihr Ansprechpartner Thomas Eisele Mehr &#252;ber EDEKA S&#252;dwest als Arbeitgeber: https://verbund.edeka/s&#252;dwest/karriere/</VOLLTEXT>
			<LOGO>https://anzeigen.jobstatic.de/upload/logos/3/197183.gif</LOGO>
			<BEWERBUNGSLINK>https://t.gohiring.com/h/39730741e0c6e29257e170eabd19bf21e7da519d9173da41b9803719cf09db52</BEWERBUNGSLINK>
			<KEYWORDS></KEYWORDS>
			<SIMPLEHTML></SIMPLEHTML>
			<KLICKS></KLICKS>
			<BRANCHENID>21200</BRANCHENID>
		</job>
		<job>
			<ID>3917482</ID>
			<URL>https://www.stellenanzeigen.de/job/3917482/?campaign=plcjob&amp;utm_id=plcjob&amp;utm_source=plcjob&amp;utm_medium=anzeigenfeed&amp;utm_campaign=plcjob&amp;subkategorie=86X</URL>
			<DATUM>28.09.2021</DATUM>
			<POSITION>Regionalbetreuung (m/w/d)</POSITION>
			<UNTERNEHMEN>FriedWald GmbH</UNTERNEHMEN>
			<RUBRIKEN>13</RUBRIKEN>
			<SUBRUBRIKEN>86,156</SUBRUBRIKEN>
			<EIGENSCHAFTEN>9,16,17,22,24</EIGENSCHAFTEN>
			<EINSATZORTTEXT>Biedenkopf, Frankenberg (Eder), Marburg, Waldeck</EINSATZORTTEXT>
			<EINSATZORTGEO>35216,35066,35037,34513</EINSATZORTGEO>
			<POSITIONSZUSATZ>Sind f&#252;r die forstliche und vertriebliche Betreuung der Standorte in der entsprechenden Region zust&#228;ndig; haben die Verantwortung f&#252;r die regionalen Vertriebsergebnisse; unterst&#252;tzen die lokalen Forst&#228;mter bei der Ersteinrichtung;...</POSITIONSZUSATZ>
			<VOLLTEXT>Regionalbetreuung (m/w/d) Zum n&#228;chstm&#246;glichen Zeitpunkt suchen wir einen Regionalbetreuer (w/m/d) f&#252;r den Gro&#223;raum Marburg-Biedenkopf/ Waldeck-Frankenberg, zun&#228;chst befristet auf 2 Jahre.  Stellenangebot: Regionalbetreuung (m/w/d) im Gro&#223;raum Marburg-Biedenkopf/ Waldeck-Frankenberg Ihre Aufgaben – Sie …   sind f&#252;r die forstliche und vertriebliche Betreuung der Standorte in der entsprechenden Region zust&#228;ndig.    haben die Verantwortung f&#252;r die regionalen Vertriebsergebnisse.    betreuen Bestattungsunternehmen in der Region.    unterst&#252;tzen die lokalen Forst&#228;mter bei der Ersteinrichtung von FriedWald-Standorten.    organisieren und planen FriedWald-Standort-Er&#246;ffnungen und andere Veranstaltungen, auch an Wochenenden.    &#252;berpr&#252;fen die Arbeitsabl&#228;ufe im Wald und f&#252;hren gelegentlich T&#228;tigkeiten als FriedWald-F&#246;rsterin bzw. FriedWald-F&#246;rster vor Ort durch: begleiten Baumauswahlen, Beisetzungen und Waldf&#252;hrungen.  Ihre St&#228;rken – Sie…   verf&#252;gen &#252;ber einen Hochschul- oder Fachhochschulabschluss im Bereich Forstwirtschaft oder eine vergleichbare Ausbildung.    haben praktische Erfahrungen im Forstbereich sowie betriebswirtschaftliche Kenntnisse und Erfahrungen im Umgang mit vertrieblichen Aktivit&#228;ten.    sind regional verbunden und zeichnen sich durch sehr gute Kenntnisse der sozialen und kulturellen Infrastruktur in der jeweiligen Region aus.    sind eine kommunikative Pers&#246;nlichkeit und haben Spa&#223; am Umgang mit Menschen.    gehen sicher mit Outlook und Word um und kennen sich gut mit CRM-/ERP-Systemen aus.    besitzen hohe Reisebereitschaft und einen PKW-F&#252;hrerschein (Firmenfahrzeug inkl. privater Nutzung wird zur Verf&#252;gung gestellt).    erf&#252;llen die Anforderungen f&#252;r einen Heimarbeitsplatz am Wohnort.  Arbeiten bei FriedWald setzt… Innovation, Inspiration, Offenheit und Spa&#223; an der Arbeit voraus. Sie bringen Ihre Pers&#246;nlichkeit mit und wir geben Ihnen das passende Werkzeug, damit Sie in Ihr vielseitiges und verantwortungsvolles Aufgabenspektrum hineinwachsen k&#246;nnen. Uns zeichnet ein angenehmes Arbeitsklima und respektvoller Umgang miteinander aus. Unsere Benefits…   Ein abwechslungsreiches Aufgabengebiet mit eigenem Gestaltungsspielraum.    Respektvoller Umgang miteinander in einem engagierten Team, das Spielraum f&#252;r Individualit&#228;t l&#228;sst.    Arbeitsplatz in einem systemrelevanten Unternehmen.    Vielf&#228;ltige betriebliche Mitarbeiterleistungen, wie zum Beispiel:         umfangreiche Einarbeitung und Weiterentwicklungsm&#246;glichkeiten          Prepaid-Kreditkarte – monatlich zur freien Verf&#252;gung          flexible Arbeitszeiten im Rahmen der Servicezeiten – damit Sie die Vereinbarkeit von Beruf und Familie leben k&#246;nnen          Unterst&#252;tzung bei einem eventuell notwendigen Wohnortwechsel          barrierefreier Unternehmenssitz in Griesheim – wir f&#246;rdern Diversit&#228;t und Inklusion          Zuschuss zur betrieblichen Altersvorsorge – damit Sie Ihre Zukunft planen k&#246;nnen          Coaching- und Supervisionsangebote – Entwicklung wird bei uns gro&#223;geschrieben          externe Mitarbeiterberatung bei beruflichen und pers&#246;nlichen Fragestellungen…u.v.m.       Wir passen zusammen? Dann freuen wir uns auf Sie!  Gerne erhalten wir Ihre Bewerbung mit Gehaltsvorstellung per E-Mail: jobs@friedwald.de oder per Post: FriedWald GmbH, Personalabteilung, Im Leuschnerpark 3, 64347 Griesheim. &#220;ber uns  FriedWald bietet in Zusammenarbeit mit L&#228;ndern, Kommunen, Kirchen und Forstverwaltungen Bestattungen unter B&#228;umen in gesondert ausgewiesenen Bestattungsw&#228;ldern an. Das Unternehmen besch&#228;ftigt zurzeit etwa 150 Mitarbeiterinnen und Mitarbeiter am Unternehmenssitz in Griesheim und im Au&#223;endienst und w&#228;chst seit Gr&#252;ndung im Jahr 2000 jedes Jahr weiter. Zur&#252;ck zur &#220;bersicht Personalreferentin  Maria Mourtzi  06155 848-117  jobs@friedwald.de</VOLLTEXT>
			<LOGO>https://anzeigen.jobstatic.de/upload/logos/9/93329.gif</LOGO>
			<BEWERBUNGSLINK>mailto:jobs@friedwald.de</BEWERBUNGSLINK>
			<KEYWORDS></KEYWORDS>
			<SIMPLEHTML></SIMPLEHTML>
			<KLICKS></KLICKS>
			<BRANCHENID>230500</BRANCHENID>
		</job></jobs>";
        die;
    }

    public function transportfeedlist() {
        echo "<?xml version='1.0' encoding='UTF-8'?>
<feed>
   <job>
      <id><![CDATA[00010952-37e5-400c-a041-0f31824818bd]]></id>
      <url><![CDATA[https://joblift.co.uk/joblink?to=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJvZmZlcklkIjoiMDAwMTA5NTItMzdlNS00MDBjLWEwNDEtMGYzMTgyNDgxOGJkIn0.1DwxrgKYinY8Jf3JxhEia2UbgyQrhGVHve0nlGwBA70&fecd=gqq7Pny0e&utm_campaign=577&utm_medium=external_feed&utm_source=plcjob_uk_mid]]></url>
      <company><![CDATA[Poundland &amp; Dealz]]></company>
      <title><![CDATA[Information Technology Procurement Manager]]></title>
      <fullDescription><![CDATA[<div><h2></h2><p><span>About the opportunity...<br></span><span><br></span><span>We are recruiting a Technology Procurement Manager to join the Procurement Team based at our Customer Support Centre in Walsall. The role has a flexible working location, allowing you to work from home and in the office.<br></span><span><br></span><span>Ensure you read the information regarding this opportunity thoroughly before making an application.<br></span><span><br></span><span>A key part of Poundland&amp;rsquo;s vision is to be &amp;ldquo;Technology Enabled&amp;rdquo;, so this is an important new role to the business. You will help Poundland and Dealz enable procurement of fit for purpose technology, manage spend, key suppliers, and service levels by guiding procurement processes and contract negotiations.<br></span><span><br></span><span>As a Technology Procurement Manager, you will report to the Head of Procurement. You will be the IT specialist within the team, working in partnership with the IT function to understand budgets and project plans for Poundland and Dealz. You will also work across other departments, playing a key role in projects that are enabled by technology.<br></span><span><br></span><span>We are committed to supporting our colleagues with their development, this role comes with the ability to study an apprenticeship in Commercial Procurement and supply level 4 or Supply Chain Leadership Professional (Integrated Degree) Level 6<br></span></p></div><div><h2>What you will be doing&amp;hellip;</h2><ul><li>﻿</li></ul></div><div><h2>As the Technology Procurement Manager, your duties will include</h2><ul><li>Identify opportunities to generate efficiencies and improve cost</li><li>To lead on the end-to-end procurement and vendor management for the IT department</li><li>To lead and manage RFPs (Request for Proposals) with new and existing suppliers</li><li>To act as the principal conduit for formal communications with external companies during the procurement process and participate directly in the negotiation of key contracts to obtain the best outcome possible for Poundland</li><li>To work with Contract owners to assist with all commercial matters, educate and coach others to improve buying practices</li><li>To proactively manage relationships with senior stakeholders to deliver outstanding commercial outcomes</li><li>To play an active role in the company's Sustainability plan to include ethical and responsible sourcing</li></ul></div><div><h2>What you'll need..</h2><ul><li>At least five years of experience in managing IT procurement in a fast-paced environment (Retail or FMCG)</li><li>Commercial negotiation and supplier management experience with a track record delivering cost savings, service improvements, and supplier innovation</li><li>Proven stakeholder influencing skills and experience in supplier development and commercial cost reduction</li><li>Practical knowledge of IT service agreements, Licensing and SAAS agreements</li><li>Excellent presentation and influencing skills</li><li>Substantial time and project management skills</li><li>Working towards, or MCIPS qualified (Member of the Chartered Institute of Procurement and Supply)</li></ul></div>]]></fullDescription>
      <description/>
      <locations>
         <location>
            <city><![CDATA[Walsall]]></city>
            <state><![CDATA[England]]></state>
            <geo/>
         </location>
      </locations>
      <publishDate><![CDATA[2021-08-19T23:00:00.000Z]]></publishDate>
      <referenceId><![CDATA[61541218__5dadec7cb0772304f2e9d6a703e6d8c7]]></referenceId>
      <contact/>
      <workingTimes>
         <item><![CDATA[fulltime]]></item>
      </workingTimes>
      <experience>2</experience>
      <salary>10000</salary>
      <keywords>call, customer</keywords>
      <language>en</language>
      <source><![CDATA[joblift]]></source>
      <esco>
         <esco.code>1324</esco.code>
         <esco.level0>Managers</esco.level0>
         <esco.level1>Production and specialised services managers</esco.level1>
         <esco.level2>Manufacturing, mining, construction, and distribution managers</esco.level2>
         <esco.level3>Supply, distribution and related managers</esco.level3>
      </esco>
      <CompanyLogo/>
   </job>
   <job>
      <id><![CDATA[00055a07-e45f-42a7-b150-dc5f3903d775]]></id>
      <url><![CDATA[https://joblift.co.uk/details/00055a07-e45f-42a7-b150-dc5f3903d775?fecd=gqq7Pny0e&utm_campaign=8&utm_medium=external_feed&utm_source=plcjob_uk_mid]]></url>
      <company><![CDATA[Nolan Recruitment Solutions]]></company>
      <title><![CDATA[Contracts Manager (Permanent)]]></title>
      <fullDescription><![CDATA[<div><h2></h2><p><span>Contracts Manager<br></span><span><br></span><span>Employment type: Permanent, full-time<br></span></p></div><div><h2>Location: Warrington</h2><p><span>Hours: 37.5 core hours per week, Monday to Friday<br></span><span><br></span><span>Salary: &amp;pound;60,000 - &amp;pound;70,000 p.a. + company vehicle + company benefits<br></span><span><br></span><span>Due to continued growth, my client is currently recruiting for a Contracts Manager, from a chemical or mechanical engineering background, to join their business on a permanent basis at their head office in Warrington.<br></span><span><br></span><span>My client provides environmental engineering solutions for clients in major processing, manufacturing, and production industries.<br></span></p></div><div><h2>The Role</h2><p><span>Reporting directly to the Managing Director, the Contracts Manager will be responsible for managing the multi-disciplined Engineering team in delivering large scale capital projects. The Contracts Manager will be tasked with gaining a clear understanding of project briefs and effectively establishing, planning, and coordinating the resources required in the Engineering team to deliver projects. Responsibilities include:<br></span><span><br></span><span>Managing project tender activity and providing technical support to the Sales team<br></span><span>Managing overall project engineering activity and ensuring projects are delivered professionally, whilst providing the agreed turnover and profit levels to meet the businesses' growth ambitions<br></span><span>Establishing and creating project plans and budgets<br></span><span>Managing internal and external engineering resources<br></span><span>Providing and maintaining technical project engineering supportExperience/ Skills Required<br></span><span><br></span><span>A Chemical or Mechanical Engineering degree<br></span><span>Proven experience of working in a project management role covering multi-disciplinary projects (process, mechanical and electrical)<br></span><span>Experience of working with main contractors, EPCs or large subcontractors<br></span><span>Experience of managing large scale system installation projects<br></span><span>Proven managerial experience<br></span><span>It is essential candidates have good knowledge of heat and mass balance calculations<br></span><span>Willingness to travel throughout the UK, Ireland and Europe (when required)<br></span><span>Knowledge of air pollution controls, process drying or dust extraction systems would be beneficial, although not essentialThis is an excellent opportunity to join an organisation which makes a positive impact on the environment and provides niche engineering services. As my client's business grows, their will be scope for personal and professional development with the organisation. If you are an experienced Chemical or Mechanical Engineer with experience of project managing large system installations, please apply today to be considered for the vacancy.<br></span><span><br></span><span>Key words: Mechanical Engineering, Chemical Engineering, Contracts Manager, Project Manager, Project Engineering, Energy from Waste, Petrochemical, Manufacturing, Production, Major Processing, Heat Calculations, Heat Recovery, Mass Balance Calculations, Pollution Controls<br></span></p></div>]]></fullDescription>
      <description/>
      <locations>
         <location>
            <district><![CDATA[Cheshire]]></district>
            <zip><![CDATA[CW1 3SU]]></zip>
            <city><![CDATA[Warrington]]></city>
            <state><![CDATA[England]]></state>
            <country><![CDATA[United Kingdom]]></country>
            <geo/>
         </location>
      </locations>
      <publishDate><![CDATA[2021-09-24T14:31:28.000Z]]></publishDate>
      <referenceId><![CDATA[214743303]]></referenceId>
      <contact/>
      <workingTimes>
         <item><![CDATA[fulltime]]></item>
      </workingTimes>
      <experience>2</experience>
      <salary>10000</salary>
      <language>en</language>
      <source><![CDATA[joblift]]></source>
      <esco>
         <esco.code>1219</esco.code>
         <esco.level0>Managers</esco.level0>
         <esco.level1>Administrative and commercial managers</esco.level1>
         <esco.level2>Business services and administration managers</esco.level2>
         <esco.level3>Business services and administration managers not elsewhere classified</esco.level3>
      </esco>
      <CompanyLogo/>
   </job>
   <job>
      <id><![CDATA[002304f3-150d-40fb-bcbe-bcdc1834e770]]></id>
      <url><![CDATA[https://joblift.co.uk/joblink?to=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJvZmZlcklkIjoiMDAyMzA0ZjMtMTUwZC00MGZiLWJjYmUtYmNkYzE4MzRlNzcwIn0.1FnRiJztqUrjg4Q2nHokVgDgIEJ4IJ8cAR3TIt34lCw&fecd=gqq7Pny0e&utm_campaign=577&utm_medium=external_feed&utm_source=plcjob_uk_mid]]></url>
      <company><![CDATA[Comparesoft]]></company>
      <title><![CDATA[Business Development Manager]]></title>
      <fullDescription><![CDATA[<div><h2></h2><p><span>Business Development Manager for the Fastest Growing AI-Driven B2B Software Marketplace.<br></span><span><br></span><span>Skills, Experience, Qualifications, If you have the right match for this opportunity, then make sure to apply today.<br></span></p></div><div><h2></h2><ul><li>Employment + Equity (Salary + Bonus + Stock)</li><li>Progression to Customer Engagement Director</li><li>Remote Working</li></ul></div><div><h2></h2><p><span>Comparesoft is the UK&amp;rsquo;s first AI-driven B2B marketplace. We help businesses to compare B2B software &amp;ndash; hence our name, Comparesoft.<br></span><span><br></span><span>KPMG, GE, Volvo, Siemens, Invesco, Barclays and 6000 other large, medium and small businesses have used our comparison platform.<br></span></p></div><div><h2>There are three key elements of Comparesoft</h2><p><span>1) Acquiring software buyers.<br></span><span><br></span><span>2) Acquiring software vendors.<br></span><span><br></span><span>3) Connecting relevant software buyers with relevant software vendors.<br></span><span><br></span><span>You will lead our sales execution in acquiring software vendors.<br></span></p></div><div><h2>On a day-to-day basis you will be</h2><p><span>1) Prospecting: The goal is to book 3 new business meetings every week.<br></span><span><br></span><span>2) Working closely with the pre-sales/product team: Along with the Pre-Sales/Product Team you will attend demo meetings to drive the meeting and ensure product-fit with new customers.<br></span><span><br></span><span>3) Following-up: Email follow-ups with identified sales opportunities with a view to acquire new customers. The goal is to acquire 7 new customers every month.<br></span><span><br></span><span>4) Maintain accurate records in the CRM system: Drive your prospecting, pipeline, sales and commissions using our CRM system.<br></span><span><br></span><span>5) Be strategic: Explore opportunities and think out-of-the-box to connect with new prospects.<br></span></p></div><div><h2>The role requires you to</h2><p><span>1. Take complete ownership of new business sales execution.<br></span><span><br></span><span>We are offering above market equity with this role. It is designed to be a co-owner in Comparesoft, and we expect an owner-level mindset.<br></span><span><br></span><span>2. Be good at strategic prospecting.<br></span><span><br></span><span>Mapping contacts in an account and then delivering personalised messages (without being aggressive) to establish contact and secure time will be vital.<br></span><span><br></span><span>We have a limited set of accounts to acquire, and the role will suit a methodical new business salesperson who is focussed on quality of conversations. Planned and strategic prospecting designed to engage sales and marketing decision makers will be vital.<br></span><span><br></span><span>3. Have 3 to 7 years of experience in selling software to sales and marketing contacts.<br></span><span><br></span><span>The role is designed to progress into Customer Engagement Director and then Chief Revenue Officer in 2 years. A thinker and a doer who does not require micro-management will excel in the role.<br></span><span><br></span><span>4. Communicate and demonstrate the value of Comparesoft to the revenue of customers.<br></span><span><br></span><span>We have had a positive impact on the sales pipeline and revenue for all of our customers. You will use our current success stories to demonstrate the value of Comparesoft.<br></span><span><br></span><span>5. Win new business accounts as per the agreed targets.<br></span><span><br></span><span>Additional share options and accelerated career progression is interlinked with your performance.<br></span><span><br></span><span>Behaviours for the role<br></span><span><br></span><span>1. Have a positive, upbeat and energetic outlook. Celebrate minor milestones as well as major goals.<br></span><span><br></span><span>2. The ability to hold thoughtful and planned communications at any level within customer accounts.<br></span><span><br></span><span>3. Drive sales momentum using social media, emails and digital content.<br></span><span><br></span><span>4. The ability to ask tough questions (with empathy) to prospective customers.<br></span><span><br></span><span>Your sales career is important to you and to us. Below, we have listed 10 key things that may be useful as you consider this role:<br></span><span><br></span><span>1. You will build and execute our sales strategy to acquire 1000 customers in the next 3 years. The role is designed to progress into Customer Engagement Director and then Chief Revenue Officer.<br></span><span><br></span><span>2. You will execute sales strategy for a product that is used by Transport for London, BAE Systems, Deloitte, Lucozade and 6000 other businesses.<br></span><span><br></span><span>3. You will receive Salary based on experience, 100% OTE + Significant stock options + Clear Progression Path to Customer Engagement Director. The stock options offered with this role are at least twice that of a comparable role in the market.<br></span><span><br></span><span>4. You are an early employee, which means that you will have the opportunity to make your mark by developing your team and your influence. You will have significant growth opportunities.<br></span><span><br></span><span>5. We have raised two rounds of funding from leading venture capitalists, Mercia Fund Management in January 2017 and Blackfinch Ventures in April 2019.<br></span><span><br></span><span>6. We are advised by the team that built Just Eat, Autobutler and NewVoiceMedia &amp;ndash; you can see that we are backed by people who have built high-growth companies.<br></span><span><br></span><span>7. We have developed a unique human + artificial intelligence-driven product to position Comparesoft as a leader in a &amp;pound;36.5 billion market in the UK.<br></span><span><br></span><span>8. Comparesoft is the UK&amp;rsquo;s first revenue-generating, AI-driven search assistant. We have proven our business model in 3 software categories and are expanding into 7 new software categories this year. We plan to be in 300 software categories in the next 3 to 5 years.<br></span><span><br></span><span>9. The founders of Comparesoft have known each other for the last 12 years and have worked together before starting Comparesoft.<br></span><span><br></span><span>10. Remote Working for the foreseeable future: We currently have an office in London. The role is 100% remote working.<br></span><span><br></span><span>Our selection process will typically involve an initial chat on the phone followed by an hour or two of online interviews where you&amp;rsquo;ll have a chance to meet the team. Our interview process is built around understanding your experience, your cultural fit with the business and that we&amp;rsquo;re the right fit for you: it&amp;rsquo;s not about making you sweat or catching you out.<br></span><span><br></span><span>We are an equal opportunity employer and we value diversity and inclusivity. We do not discriminate on the basis of gender, race, age, sexual orientation, colour, religion, national origin, disability status or marital status.<br></span><span><br></span><span>Take a look at our website to see if you&amp;rsquo;d like to work with us: https://comparesoft.com/about-comparesoft/.<br></span></p></div>]]></fullDescription>
      <description/>
      <locations>
         <location>
            <zip><![CDATA[TW3]]></zip>
            <city><![CDATA[Hounslow]]></city>
            <state><![CDATA[Greater London]]></state>
            <geo/>
         </location>
      </locations>
      <publishDate><![CDATA[2021-09-09T23:00:00.000Z]]></publishDate>
      <referenceId><![CDATA[64718285]]></referenceId>
      <contact/>
      <workingTimes>
         <item><![CDATA[fulltime]]></item>
      </workingTimes>
      <experience>2</experience>
      <salary>10000</salary>
      <language>en</language>
      <source><![CDATA[joblift]]></source>
      <esco>
         <esco.code>2431</esco.code>
         <esco.level0>Professionals</esco.level0>
         <esco.level1>Business and administration professionals</esco.level1>
         <esco.level2>Sales, marketing and public relations professionals</esco.level2>
         <esco.level3>Advertising and marketing professionals</esco.level3>
      </esco>
      <CompanyLogo/>
   </job>
   <job>
      <id><![CDATA[0023adf8-dc06-405b-abd9-2d5476b34d8e]]></id>
      <url><![CDATA[https://joblift.co.uk/details/0023adf8-dc06-405b-abd9-2d5476b34d8e?fecd=gqq7Pny0e&utm_campaign=8&utm_medium=external_feed&utm_source=plcjob_uk_mid]]></url>
      <company><![CDATA[Data Communications Company]]></company>
      <title><![CDATA[Senior Commercial Manager (Contract)]]></title>
      <fullDescription><![CDATA[<div><h2></h2><p><span>Smart DCC believes in making Britain more connected, so we can all lead smarter, greener lives. We&amp;rsquo;ve built the secure infrastructure that&amp;rsquo;s going to support the mass roll out of smart meters across the country. Our universal, secure network will be in 30 million homes and businesses, making it the largest network in Britain. That makes this a truly exciting time to join us. You&amp;rsquo;ll be part of a team that&amp;rsquo;s supporting the country&amp;rsquo;s transition to a low-carbon economy, and helping to ensure an affordable, secure, and sustainable energy supply for the future.<br></span><span><br></span><span>Operating independently of the parent company, Capita plc*. Smart DCC is a Disability Confident Committed Employer, with Smart targets set by BEIS, regulated by OFGEM, collaborating with Government, leading Telco and Utility industry service providers, to enable consumers better energy choices. A 2018 UK &amp;amp; Europe finalist of the Top Workplace award, we reward professionals that thrive in an environment of change and innovation.<br></span></p></div><div><h2>Key accountabilities</h2><p><span>Design and plans that successfully deliver strategic improvements to cost and time<br></span><span>Drive Value for Money by ensuring all DCC contracts and Commercial processes are effective and achieves DCCs goals<br></span><span>Driving and optimising commercial relationships and commercial opportunities with C-Suite Stakeholders.<br></span><span>Contributing to development of contract terms and conditions alongside DCC core commercial team and legal advisors.<br></span><span>Working with business analysts to incorporate service requirements into requirements specification schedules.<br></span><span>Contributing to development of pricing mechanisms, service levels and service credit mechanisms.The person:<br></span></p></div><div><h2>Experienced Commercial Manager or other relevant commercial roles</h2><ul><li>Experience managing supplier contracts through procurement and service delivery</li><li>Strong C-Suite level stakeholder management skills</li><li>Strong project management skills</li><li>Experience of identifying and delivering new business opportunities</li><li>Analytical approach to problem solving</li><li>An eye for detail, ability to review/quality assure documentation and provide constructive feedback</li><li>Resilient and able to manage challenge and comfortable working at pace&amp;lsquo;Apply now&amp;rsquo; to complete our short application, so that we can find out more about you. Your application will be carefully considered where you will hear from us regarding progress and feedback on your application.</li></ul></div><div><h2></h2><p><span>The parent company, Capita Plc*, are a leading UK provider of technology enabled business services. We&amp;rsquo;re supporting and improving the lives of millions of people every day and we can only do this with the right people in place, working towards a shared goal. We encourage an open, honest working environment where everyone can be true to themselves and people are valued for their differences. We&amp;rsquo;re always challenging each other to learn and improve, because we know when we work together, we can deliver better outcomes. We work across such a huge range of businesses and sectors, that you&amp;rsquo;ll have the opportunity to grow and develop your career in any number of directions. You&amp;rsquo;ll also become part of a network of 63,000 experienced, innovative, and dedicated individuals across multiple disciplines and sectors. There are countless opportunities to learn new skills and develop in your career, and we&amp;rsquo;ll provide the support you need to do deliver. Our purpose is to create a better outcome for you<br></span></p></div>]]></fullDescription>
      <description/>
      <locations>
         <location>
            <city><![CDATA[London]]></city>
            <state><![CDATA[England]]></state>
            <country><![CDATA[United Kingdom]]></country>
            <geo/>
         </location>
      </locations>
      <publishDate><![CDATA[2021-09-21T11:18:41.000Z]]></publishDate>
      <referenceId><![CDATA[214708817]]></referenceId>
      <contact/>
      <workingTimes>
         <item><![CDATA[fulltime]]></item>
      </workingTimes>
      <experience>2</experience>
      <salary>10000</salary>
      <language>en</language>
      <source><![CDATA[joblift]]></source>
      <esco>
         <esco.code>3322</esco.code>
         <esco.level0>Technicians and associate professionals</esco.level0>
         <esco.level1>Business and administration associate professionals</esco.level1>
         <esco.level2>Sales and purchasing agents and brokers</esco.level2>
         <esco.level3>Commercial sales representatives</esco.level3>
      </esco>
      <CompanyLogo/>
   </job>
   
   <job>
      <id><![CDATA[01aa18f0-b08b-4be1-b810-90a45d33434e]]></id>
      <url><![CDATA[https://joblift.co.uk/joblink?to=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJvZmZlcklkIjoiMDFhYTE4ZjAtYjA4Yi00YmUxLWI4MTAtOTBhNDVkMzM0MzRlIn0.eRJwUl9vwORH4j1wtDHH9_axTbiJ4oeyXopVEjjjtgc&fecd=gqq7Pny0e&utm_campaign=577&utm_medium=external_feed&utm_source=plcjob_uk_mid]]></url>
      <company><![CDATA[Faculty]]></company>
      <title><![CDATA[Lead Data Scientist - Government]]></title>
      <fullDescription><![CDATA[<div><h2></h2><p><span>Faculty is one of Europe&amp;rsquo;s leading applied artificial intelligence companies. We build, deploy and operate AI solutions to increase our customers&amp;rsquo; performance and help them realise their full potential. We believe that AI should be trustworthy, impactful and beneficial across society. Those principles have shaped our work with more than 230 organisations across the public and private sectors as we help them use AI to understand more deeply, make better decisions and act faster.Make sure to read the full description below, and please apply immediately if you are confident you meet all the requirements.We are looking for a Lead Data Scientist to join our Government Practice. Faculty has a very strong track record of supporting government departments and agencies to make use of AI and data science, including some of the most sophisticated public sector data science applications to have been implemented. Our work in government is varied - it includes longer-term embedded partnerships to help departments to build and scale their data science capability, large-scale ML software builds, as well as more exploratory discovery and strategy workYou will have access to powerful cloud computational resources, and you will enjoy the comforts of fast configuration, secure collaboration and easy deployment. Because your work in data science will inform the development of our AI products, you will often collaborate with software engineers and designers from our dedicated product team.What You'll Be Doing<br></span><span><br></span><span>&amp;bull; Lead project teams to deliver bespoke algorithms to our clients<br></span><span>&amp;bull; Conceiving the data science approach, designing the associated software architecture, and leading on its delivery whilst ensuring that best practices are followed throughout<br></span><span>&amp;bull; Working embedded within government client teams or other partners<br></span><span>&amp;bull; Inspire confidence and communicate the value and limitations of data science to non-technical government stakeholders<br></span><span>&amp;bull; Guide the process of defining the scope of projects to come with an emphasis on technical feasibility<br></span><span>&amp;bull; Acting as the developmental manager or designated mentor of a small number of data scientists<br></span><span>&amp;bull; Actively contribute to the growth of this reputation by delivering courses to high-value clients, by talking at major conferences, by participating in external roundtables, or by contributing to large-scale open-source projects<br></span><span>&amp;bull; Have the opportunity to teach data science courses ranging from data visualisation to reinforcement learning on Faculty&amp;rsquo;s academia to industry Fellowship program, as well as mentor Fellows<br></span></p></div><div><h2>Who We&amp;rsquo;re Looking For</h2><ul><li>Senior experience in either a professional data science position</li><li>Ideally some experience working with the UK government or wider public sector, although this is not essential</li><li>Strong programming skills as evidenced by earlier work in data science or software engineering. Fluent in Python. 4+ years of experience applying ML techniques to drive demonstrable value in one or more sectors of industry</li><li>An excellent command of the basic libraries for data science (e.g. NumPy, pandas, scikit-learn) and familiarity with a deep-learning framework (e.g. TensorFlow, PyTorch, Caffe)</li><li>A solid grasp of supervised/unsupervised machine learning, model cross validation, Bayesian inference, time-series analysis, simple NLP, effective SQL database querying, or using/writing simple APIs for models. We regard the ability to develop new algorithms when an innovative solution is needed as a fundamental skill</li><li>Excellent communication skills, and actively enjoy shaping requirements and communicating technical concepts to non-technical stakeholders</li><li>An appreciation for the scientific method as applied to the commercial world; a talent for converting business problems into a mathematical framework; resourcefulness in overcoming difficulties through creativity and commitment; a rigorous mindset in evaluating the performance and impact of models upon deployment</li><li>Experience leading a team of data scientists, both to deliver innovative work and as their developmental manager</li></ul></div><div><h2></h2><p><span>Faculty is the professional challenge of a lifetime. You&amp;rsquo;ll be surrounded by an impressive group of brilliant minds working to advance our goal of making artificial intelligence real. Our consultants, product developers, business development specialists, operations professionals and more all bring something unique to Faculty, and you&amp;rsquo;ll learn something new from everyone you meet. You&amp;rsquo;ll also have the opportunity to make your mark on a high-growth start-up now poised to expand internationally.<br></span><span><br></span><span>Fostering talent is one of our core values, it&amp;rsquo;s built into our culture and what we offer Genuinely flexible working We believe people have needs, responsibilities and interests that require something different to a strict working day. We trust people to organise and take accountability for their own work and do our best to support their lives outside Faculty. Unlimited holidays We encourage each other to use this time to take a break, work on personal projects, or to spend time with their friends and family. Breakfast and more fruit, drinks and snacks than you could ever eat provided (for when you're in the office). Did we mention Yoga? And finally, you&amp;rsquo;ll meet colleagues from around the world who challenge your thinking and teach you new skills. You&amp;rsquo;ll make friends and professional connections that will last a lifetime. We work hard and make sure we enjoy what we do. So we have frequent socials and informal get-togethers to help make sure you enjoy your time with us.<br></span></p></div>]]></fullDescription>
      <description/>
      <locations>
         <location>
            <zip><![CDATA[HA9 7BP]]></zip>
            <city><![CDATA[London]]></city>
            <state><![CDATA[England]]></state>
            <geo/>
         </location>
      </locations>
      <publishDate><![CDATA[2021-09-09T23:00:00.000Z]]></publishDate>
      <referenceId><![CDATA[64714417]]></referenceId>
      <contact/>
      <workingTimes>
         <item><![CDATA[fulltime]]></item>
      </workingTimes>
     <experience>2</experience>
      <salary>10000</salary>
      <language>en</language>
      <source><![CDATA[joblift]]></source>
      <esco>
         <esco.code>2511</esco.code>
         <esco.level0>Professionals</esco.level0>
         <esco.level1>Information and communications technology professionals</esco.level1>
         <esco.level2>Software and applications developers and analysts</esco.level2>
         <esco.level3>Systems analysts</esco.level3>
      </esco>
      <CompanyLogo/>
   </job>
   <job>
      <id><![CDATA[00055a07-e45f-42a7-b150-dc5f3903d775]]></id>
      <url><![CDATA[https://joblift.co.uk/details/00055a07-e45f-42a7-b150-dc5f3903d775?fecd=gqq7Pny0e&utm_campaign=8&utm_medium=external_feed&utm_source=plcjob_uk_mid]]></url>
      <company><![CDATA[Nolan Recruitment Solutions]]></company>
      <title><![CDATA[Contracts Manager (Permanent)]]></title>
      <fullDescription><![CDATA[<div><h2></h2><p><span>Contracts Manager<br></span><span><br></span><span>Employment type: Permanent, full-time<br></span></p></div><div><h2>Location: Warrington</h2><p><span>Hours: 37.5 core hours per week, Monday to Friday<br></span><span><br></span><span>Salary: &amp;pound;60,000 - &amp;pound;70,000 p.a. + company vehicle + company benefits<br></span><span><br></span><span>Due to continued growth, my client is currently recruiting for a Contracts Manager, from a chemical or mechanical engineering background, to join their business on a permanent basis at their head office in Warrington.<br></span><span><br></span><span>My client provides environmental engineering solutions for clients in major processing, manufacturing, and production industries.<br></span></p></div><div><h2>The Role</h2><p><span>Reporting directly to the Managing Director, the Contracts Manager will be responsible for managing the multi-disciplined Engineering team in delivering large scale capital projects. The Contracts Manager will be tasked with gaining a clear understanding of project briefs and effectively establishing, planning, and coordinating the resources required in the Engineering team to deliver projects. Responsibilities include:<br></span><span><br></span><span>Managing project tender activity and providing technical support to the Sales team<br></span><span>Managing overall project engineering activity and ensuring projects are delivered professionally, whilst providing the agreed turnover and profit levels to meet the businesses' growth ambitions<br></span><span>Establishing and creating project plans and budgets<br></span><span>Managing internal and external engineering resources<br></span><span>Providing and maintaining technical project engineering supportExperience/ Skills Required<br></span><span><br></span><span>A Chemical or Mechanical Engineering degree<br></span><span>Proven experience of working in a project management role covering multi-disciplinary projects (process, mechanical and electrical)<br></span><span>Experience of working with main contractors, EPCs or large subcontractors<br></span><span>Experience of managing large scale system installation projects<br></span><span>Proven managerial experience<br></span><span>It is essential candidates have good knowledge of heat and mass balance calculations<br></span><span>Willingness to travel throughout the UK, Ireland and Europe (when required)<br></span><span>Knowledge of air pollution controls, process drying or dust extraction systems would be beneficial, although not essentialThis is an excellent opportunity to join an organisation which makes a positive impact on the environment and provides niche engineering services. As my client's business grows, their will be scope for personal and professional development with the organisation. If you are an experienced Chemical or Mechanical Engineer with experience of project managing large system installations, please apply today to be considered for the vacancy.<br></span><span><br></span><span>Key words: Mechanical Engineering, Chemical Engineering, Contracts Manager, Project Manager, Project Engineering, Energy from Waste, Petrochemical, Manufacturing, Production, Major Processing, Heat Calculations, Heat Recovery, Mass Balance Calculations, Pollution Controls<br></span></p></div>]]></fullDescription>
      <description/>
      <locations>
         <location>
            <district><![CDATA[Cheshire]]></district>
            <zip><![CDATA[CW1 3SU]]></zip>
            <city><![CDATA[Warrington]]></city>
            <state><![CDATA[England]]></state>
            <country><![CDATA[United Kingdom]]></country>
            <geo/>
         </location>
      </locations>
      <publishDate><![CDATA[2021-09-24T14:31:28.000Z]]></publishDate>
      <referenceId><![CDATA[214743303]]></referenceId>
      <contact/>
      <workingTimes>
         <item><![CDATA[fulltime]]></item>
      </workingTimes>
      <experience>2</experience>
      <salary>10000</salary>
      <language>en</language>
      <source><![CDATA[joblift]]></source>
      <esco>
         <esco.code>1219</esco.code>
         <esco.level0>Managers</esco.level0>
         <esco.level1>Administrative and commercial managers</esco.level1>
         <esco.level2>Business services and administration managers</esco.level2>
         <esco.level3>Business services and administration managers not elsewhere classified</esco.level3>
      </esco>
      <CompanyLogo/>
   </job>
</feed>";
        die;
    }

    public function feedlist() {
        /* echo "<?xml version='1.0' encoding='utf-8'?>
          <jobs>
          <job>
          <id>
          <![CDATA[ 000db8e9-47ba-43c8-b061-34931bb73ec0 ]]>
          </id>
          <url>
          <![CDATA[ https://joblift.fr/joblink?to=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJvZmZlcklkIjoiMDAwZGI4ZTktNDdiYS00M2M4LWIwNjEtMzQ5MzFiYjczZWMwIn0.yFwliGhfw1IeV-_5WwK7u2x6SvQqmABNwpinr9HTPaI&fecd=4KkEbd5Y5&utm_campaign=51&utm_medium=external_feed&utm_source=plcjob_fr_mid ]]>
          </url>
          <company>
          <![CDATA[ Rialto Recruitment nv ]]>
          </company>
          <title>
          <![CDATA[ account manager ]]>
          </title>
          <fullDescription>
          <![CDATA[ Schneider Electric is a global specialist in energy management and automation with activities in more than 100 countries. They combine world-leading energy technologies, real-time automation, software and services into integrated solutions for homes, buildings, datacenters, infrastructure and industries. Schneider is currently looking for a Senior Account Manager Brussels – Wallonia (m/v/x) for the universal enclosures products. Identify the potential of the players in the market as well the influencers or promoters Analyze the business penetration rate and identify the clients’ investment policies to anticipate the competition risks and enrich the customers’ data base Give regular updates on the competition strategy, their prices and offers. Establish strong professional relationship with the partners like the Distributors, Panel builders or direct OEM clients. Build and implement commercial action plans (customer loyalty, investigate new clients, promotion of the offer, commercial campaigns) in compliance the marketing plan. Analyze and influence the technical specifications, define, and propose the most adapted &amp; optimized offers. Follow up and manage the quotations and negotiate the contracts in coordination with the sales manager. Report to the country management. Keep the customer data base updated. Handle the promotion of the UE offer and provide techno- commercial support &amp; trainings to the clients. Next to this, you will follow up the business execution toward the customers: Check the implementation of the jobs (compliance with the contracts specifications) and inform the internal teams in case of deviation. Follow-up of promotion &amp; prescription’ actions Search the reasons for non-satisfaction of the clients &amp; partners and be involved in the management of the dissatisfaction till end up. You will contribute to improvement of the offer and services Contribute to the evolution of the products offer &amp;services through the feedback from customers. Participate in the improvement of the team efficiency by sharing experience (success and failures). Ensure a close business relationship with the colleagues of other (sales) teams at Schneider Electric as well with the teams in the factories. Master’s degree with a background in the field of industrial components or electrical distribution Preferable 5 years plus experience in the energy/electrical sector Strong and demonstrable sales success at executive level. Strong interpersonal communication and relationship building skills, time management and organizational skills. Proven ability to follow sales processes and work to demanding timescales. Excellent influencing and communication skills at all levels. A real team player but also capable of operating independently. An entrepreneur who is flexible and has a positive attitude. Bilingual FR/ENG, the knowledge of Dutch is considered as a plus. Preferably based close to your assigned region. Very good knowledge of MS Office and being computer literate. An exiting and varied job with continuous challenges in an international organization. An excellent opportunity to be part of a global leader that contributes actively to sustainability and care for the planet. A role that includes mobility and autonomy and that allows working on wide ranging projects and with diversity of customers. An attractive salary package: Fixed sal... Originele vacature is te vinden op StepStone.be – Maak nu een Jobagent aan op StepStone en vind je droombaan! https://bit.ly/2jPYsZC Vind gelijkaardige jobs, informatie over werkgevers en carrièretips op StepStone.be! ]]>
          </fullDescription>
          <description/>
          <locations>
          <location>
          <zip>
          <![CDATA[ 61110 ]]>
          </zip>
          <city>
          <![CDATA[ Bruxelles ]]>
          </city>
          <state>
          <![CDATA[ Normandie ]]>
          </state>
          <geo/>
          </location>
          </locations>
          <publishDate>
          <![CDATA[ 2021-07-20T09:03:24.000Z ]]>
          </publishDate>
          <referenceId>
          <![CDATA[ &quot;2187263620210705&quot;&quot;__37719412096bb9d3a9ad6349ecb869ff ]]>
          </referenceId>
          <contact/>
          <workingTimes>
          <item>
          <![CDATA[ fulltime ]]>
          </item>
          </workingTimes>
          <salary/>
          <pricing/>
          <language>en</language>
          <source>
          <![CDATA[ joblift ]]>
          </source>
          <esco>
          <esco.code>2431</esco.code>
          <esco.level0>Professionals</esco.level0>
          <esco.level1>Business and administration professionals</esco.level1>
          <esco.level2>Sales, marketing and public relations professionals</esco.level2>
          <esco.level3>Advertising and marketing professionals</esco.level3>
          </esco>
          <CompanyLogo/>
          </job>
          </jobs>"; */
        /*  echo "<?xml version='1.0' encoding='utf-8'?>
          <jobs>
          <job>
          <jobid><![CDATA[0006aaa2fe6f]]></jobid>
          <title><![CDATA[Cyber Security Analyst - Consultant]]></title>
          <company><![CDATA[addexpert GmbH]]></company>
          <city><![CDATA[Baar]]></city>
          <state><![CDATA[Canton of Zug]]></state>
          <country><![CDATA[ch]]></country>
          <description><![CDATA[Für unseren Kunden suchen wir an unserem Standort in Baar per sofort oder nach Vereinbarung einen&nbsp;Cyber Security Consultant (80-100 %).Das sind Ihre AufgabenKunden bei der Erarbeitung von Cyber Security Management sowie bei der Beurteilung der IT-Infrastruktur unterstützenBeratung der Kunden in den Bereichen Cyber Security, Security Incident Response, Vulnerability Management/Assessment, GRC/ISMS, Information Security Risk Management, Awareness, AuditsProjektmitarbeit zum Aufbau von neuen KundenservicesKundenbeziehungen ausbauen und pflegenDas bringen Sie mitStudium mit Schwerpunkt Informatik (FH/Uni) oder vergleichbare Ausbildung (z.B. MAS Informationssicherheit)Erste Berufserfahrung in einer BeratungsfirmaErste Projekterfahrung im Bereich digitale TransformationKnow-how und Erfahrung in mindestens einem der Themen der folgenden Themen:Cloud ServicesPlanung, Konzeption und Aufbau von Informationssicherheits-, Datenschutz- und Risikomanagement-Frameworks auf Basis der gängigen Standards, z.B. ISO 27001/2NIST Cyber Security FrameworkStarkes Interesse an strategischen Projekten mit Fokus auf Cyber Security, technischen Security Assessments und IT StrategyKonzeptionelles, analytisches Denken und Freude am Lösen von komplexen AufgabenDeutsche Muttersprache, gute Englischkenntnisse in Wort und Schrift]]></description>
          <date><![CDATA[2021-06-21T05:24:06Z]]></date>
          <category><![CDATA[IT]]></category>
          <currency><![CDATA[EUR]]></currency>
          <cpc><![CDATA[0.18]]></cpc>
          <url><![CDATA[https://neuvoo.ch/job?id=0006aaa2fe6f&source=plcjob_all&utm_source=partner&utm_medium=plcjob_all&puid=8ddgeddg3deg3dea3aef3aef3aefgdde9ada3aeb7ddaeadabed3addfedd8fed3fcdbdbdbabdefed3&cg=talent]]></url>
          <logo><![CDATA[https://cdn-dynamic.talent.com/ajax/img/get-logo.php?empcode=addexpert-gmbh&empname=addexpert GmbH&v=024]]></logo>
          </job>
          <job>
          <jobid><![CDATA[00064804767c]]></jobid>
          <title><![CDATA[Professeur particulier de Géologie  à Onex]]></title>
          <company><![CDATA[Superprof]]></company>
          <city><![CDATA[Onex]]></city>
          <state><![CDATA[Geneva]]></state>
          <country><![CDATA[ch]]></country>
          <description><![CDATA[EntrepriseSuperprof est  l'outil de partage de connaissances par excellence. Il met en relation ceux qui désirent apprendre et ceux qui souhaitent enseigner.

          Créée en août 2013, Superprof connecte des élèves et des professeurs pour des leçons sur plus de 1000 disciplines: musique (guitare, piano, etc.), langues, sports (golf, coach sportif, natation, etc.) et matières scolaires (maths, français, philo, etc.).

          Huit millions d’utilisateurs se sont servis de cette plateforme en 2019, majoritairement en France mais aussi dans 36 autres pays (https://www.superprof.fr/superprof-dans-le-monde.html)MissionLeader en Europe sur la mise en relation d’élèves et professeurs particuliers , Superprof recherche des intervenants pédagogiques toute l'année dans toute la Suisse pour dispenser des cours particuliers dans de nombreux domaines : matières scolaires, langues, musique, chant, sports, loisirs…

          En fonction de vos connaissances et expériences, vous pouvez proposer des cours et créer autant d'annonces que vous voulez.  Rejoignez l’équipe de Superprof directement en cliquant ici et donnez des cours privés à des élèves proche de chez vous, de tout niveau et de tout âge.

          95% des professeurs inscrits trouvent plus d’élèves sur Superprof que sur n’importe quelle autre plateforme.

          Profil :
          -Vous êtes titulaire d'un diplôme de niveau bac minimum (ou équivalent)
          -Vous souhaitez transmettre votre savoir, votre passion et vos connaissances à des élèves
          -Vous êtes patient, ponctuel et pédagogueAvantages-Inscription totalement gratuite
          -Une rémunération attractive, nette et sans surprise (pas de commission)
          -      Paiement via Superprof
          -      Mise en place d'une politique d'annulation
          -Une maîtrise de votre emploi du temps
          -Une multitude d’élèves : plus de 100 000 recherches / jour
          -La possibilité de donner des cours via webcam]]></description>
          <date><![CDATA[2021-09-06T12:47:21Z]]></date>
          <category><![CDATA[Education]]></category>
          <currency><![CDATA[EUR]]></currency>
          <cpc><![CDATA[0.15]]></cpc>
          <url><![CDATA[https://neuvoo.ch/job?id=00064804767c&source=plcjob_all&utm_source=partner&utm_medium=plcjob_all&puid=eddgeddg3deg3dea3dec3de83deggddc9dd93dea7dd9eaddbed3addfeddbfed3fcdbdbdbabdefed3&cg=talent]]></url>
          <logo><![CDATA[https://cdn-dynamic.talent.com/ajax/img/get-logo.php?empcode=superprof-ch&empname=Superprof&v=024]]></logo>
          </job>

          <job>
          <jobid><![CDATA[001182dbd698]]></jobid>
          <title><![CDATA[Jung, motivert und bereit nach Liesberg zu fahren]]></title>
          <company><![CDATA[Personal Contact]]></company>
          <city><![CDATA[Liesberg]]></city>
          <state><![CDATA[Basel-Landschaft]]></state>
          <country><![CDATA[ch]]></country>
          <description><![CDATA[<div><p><p><b>Jung, motivert und bereit nach Liesberg zu fahren</b></p></p> <p>Job mode » Pensum</p> <p> Temporary » Full time</p> <p>Sector » Business</p> <p> Industry / Production » Chemistry / Pharma</p> <p>Enterprise Size</p> <p>Medium (51-250 employees)</p> <p>Enterprise Type</p> <p>modern</p> <p>Enterprise Management</p> <p>familial</p> <p>Personal Contact vermittelt und verleiht seit über 25 Jahren erfolgreich qualifiziertes Personal in den Bereichen Industrie, Pharma, Technik und Gewerbe.</p><p>Mit Fachkompetenz, Menschlichkeit und Engagement bietet Personal Contact lückenlose Dienstleistungen an, welche den höchsten qualitativen Ansprüchen des Personalwesens entsprechen.</p><p>Für unseren Kunden, ein internationales Pharmaunternehmen in der Region Baselland suchen wir ab sofort für einen längeren Einsatz Produktionsmitarbeiter (m/w) im 6/4- oder 3- Schichtbetrieb.</p><p>Berufliche Anforderungen:</p><p>- Erfahrung im GMP-Bereich</p><p>- Erfahrung in der Produktion Tablettierung und resp. im Dispensing von Vorteil</p><p>- Bereitschaft im 6/4- oder 3- Schichtbetrieb zu arbeiten</p><p>- Sehr gute Deutschkenntnisse in Wort und Schrift</p><p>- Sehr gute mathematische Kenntnisse</p><p>Persönliche Anforderungen:</p><p>- Abschluss EFZ/EBA (z. Beispiel: Koch, Detailhandel Lebensmittelbereich)</p><p>- Fahrausweis Kat. B und eigenes Auto</p><p>- Zuverlässigkeit und Selbständigkeit</p><p>- Teamfähigkeit und Eigeninitiative</p><p>- Äusserlich gepflegtes Erscheinungsbild</p> <p>Beginning of appointment</p> <p>immediately</p> <p>Job Area</p> <p> Canton of Basel-Land</p> <p>Place of work</p> <p>Liesberg</p> <p>Mobility</p> <p>Ohne Auto keine Chance!!!!!!!!!</p></div>]]></description>
          <date><![CDATA[2021-08-30T13:03:41Z]]></date>
          <category><![CDATA[Transportation]]></category>
          <currency><![CDATA[EUR]]></currency>
          <cpc><![CDATA[0.29]]></cpc>
          <url><![CDATA[https://neuvoo.ch/job?id=001182dbd698&source=plcjob_all&utm_source=partner&utm_medium=plcjob_all&puid=bddgcddg3def3def3de83dee3aecgade9adc3deacdd7cdd8ced3addeedd7fed3fcdbdbdbabdefed3&cg=talent]]></url>
          <logo><![CDATA[https://cdn-dynamic.talent.com/ajax/img/get-logo.php?empcode=personal-contact&empname=Personal Contact&v=024]]></logo>
          </job>
          </jobs>"; */
        /*  echo '<?xml version="1.0" encoding="utf-8"?>
          <jobs>
          <job>
          <jobid>00003e91a1e9</jobid>
          <title>Hiring for developer</title>
          <company>John</company>
          <city>Bangalore</city>
          <state>Karnataka</state>
          <country>India</country>
          <description>It is test description.</description>
          <worktype>Full Time</worktype>
          <experience>4</experience>
          <salary>5000</salary>
          <skills>Android</skills>
          <designation>Developer</designation>
          <employer_email>esimsek15@gmail.com	</employer_email>
          </job>
          <job>
          <jobid>00073e91a1e9</jobid>
          <title>Hiring developer</title>
          <company>John</company>
          <city>Bangalore</city>
          <state>Karnataka</state>
          <country>India</country>
          <description>It is test description.</description>
          <worktype>Full Time</worktype>
          <experience>4</experience>
          <salary>5000</salary>
          <skills>Android</skills>
          <designation>Developer</designation>
          <employer_email>jobs.crosslandcompany@gmail.com</employer_email>
          </job>
          <job>
          <jobid>00073545455e91a1e9</jobid>
          <title>Hiring developer</title>
          <company>John</company>
          <city>Bangalore</city>
          <state>Karnataka</state>
          <country>India</country>
          <description>It is test description.</description>
          <worktype>Full Time</worktype>
          <experience>4</experience>
          <salary>5000</salary>
          <skills>Android</skills>
          <designation>Developer</designation>
          <employer_email>jobstest.com</employer_email>
          </job>
          <job>
          <jobid>00073545455e91a1e9</jobid>
          <title>Hiring developer</title>
          <company>John</company>
          <city>Bangalore</city>
          <state>Karnataka</state>
          <country>India</country>
          <description>It is test description.</description>
          <employer_email>hello@hello.com</employer_email>
          </job>
          </jobs>'; */
        // die;
        /*  echo '<?xml version="1.0" encoding="utf-8"?>
          <jobs>
          <job>
          <jobid>00003e91a1e9</jobid>
          <title>Hiring for developer</title>
          <company>John</company>
          <city>Bangalore</city>
          <state>Karnataka</state>
          <country>India</country>
          <description>It is test description.</description>
          <worktype>Full Time</worktype>
          <contact_name>John Amhic</contact_name>
          <company_profile>We are IT industry lead company.</company_profile>
          <contact_number>9787800000</contact_number>
          <company_website>https://www.google.com</company_website>
          <experience>4</experience>
          <salary>5000</salary>
          <skills>Android</skills>
          <designation>Developer</designation>
          <location>Bangalore, Karnataka, India</location>
          <last_date>2021-09-09</last_date>
          <employer_email>esimsek15@gmail.com	</employer_email>
          </job>
          <job>
          <jobid>00073e91a1e9</jobid>
          <title>Hiring developer</title>
          <company>John</company>
          <city>Bangalore</city>
          <state>Karnataka</state>
          <country>India</country>
          <description>It is test description.</description>
          <worktype>Full Time</worktype>
          <worktype>Full Time</worktype>
          <contact_name>John Amhic</contact_name>
          <company_profile>We are IT industry lead company.</company_profile>
          <contact_number>9787800000</contact_number>
          <company_website>https://www.google.com</company_website>
          <experience>4</experience>
          <salary>5000</salary>
          <skills>Android</skills>
          <designation>Developer</designation>
          <location>Bangalore, Karnataka, India</location>
          <last_date>2021-09-09</last_date>
          <employer_email>jobs.crosslandcompany@gmail.com</employer_email>
          </job>
          </jobs>';
          die; */
        echo '<?xml version="1.0" encoding="utf-8"?>
          <jobs>
            <job>
                <jobid>703e91a1e9</jobid>          
                <company>John</company>
                <contact_name>John Amhic</contact_name>
                <company_profile>We are IT industry lead company.</company_profile>
                <contact_number>9787800000</contact_number>
                <company_website>https://www.google.com</company_website>
                <company_address>Bangalore, Karnataka, India</company_address>
                <title>Cyber Security Analyst</title>
                <description>It is test description.</description>
                <url>https://www.google.com</url>
                <worktype>Intern</worktype>          
                <experience>4</experience>
                <salary>5000</salary>
                <skills>Android</skills>
                <designation>Developer</designation>
                <location>Bangalore, Karnataka, India</location>
                <last_date>2021-09-09</last_date>
                <logo>https://job-board-portal-script.logicspice.com/app/webroot/files/joblogo/37fe8_customer-supprt.jpg</logo>
            </job>
            <job>
                <jobid>703e91a1e9</jobid>          
                <company>John</company>          
                <url>https://www.google.com</url>
                <worktype>Intern</worktype>
                <contact_name>John Amhic</contact_name>
                <company_profile>We are IT industry lead company.</company_profile>
                <contact_number>9787800000</contact_number>
                <company_website>https://www.google.com</company_website>
                <company_address>Bangalore, Karnataka, India</company_address>
                <title>Hiring Technicien</title>
                <description>It is test description.</description>
                <experience>4</experience>
                <salary>5000</salary>
                <skills>Android</skills>
                <designation>Developer</designation>
                <location>Bangalore, Karnataka, India</location>
                <last_date>2021-09-09</last_date>
                <logo>https://job-board-portal-script.logicspice.com/app/webroot/files/joblogo/37fe8_customer-supprt.jpg</logo>
            </job>
            <job>
                <jobid>70545455e91a1e9</jobid>         
                <company>John</company>    
                <url>https://www.google.com</url>
                <worktype>Intern</worktype>
                <contact_name>John Amhic</contact_name>
                <company_profile>We are IT industry lead company.</company_profile>
                <contact_number>9787800000</contact_number>
                <company_website>https://www.google.com</company_website>
                <company_address>Bangalore, Karnataka, India</company_address>
                <title>Hiring developer</title>
                <description>It is test description.</description>
                <experience>4</experience>
                <salary>5000</salary>
                <skills>Android</skills>
                <designation>Developer</designation>
                <location>Bangalore, Karnataka, India</location>
                <last_date>2021-09-09</last_date>
                <logo>https://job-board-portal-script.logicspice.com/app/webroot/files/joblogo/37fe8_customer-supprt.jpg</logo>
            </job>
            <job>
                <jobid>70545455e91a1e9</jobid>          
                <company>John</company>          
                <url>https://www.google.com</url>
                <worktype>Intern</worktype>
                <contact_name>John Amhic</contact_name>
                <company_profile>We are IT industry lead company.</company_profile>
                <contact_number>9787800000</contact_number>
                <company_website>https://www.google.com</company_website>
                <company_address>Bangalore, Karnataka, India</company_address>
                <title>Hiring developer</title>
                <description>It is test description.</description>
                <experience>4</experience>
                <salary>5000</salary>
                <skills>Android</skills>
                <designation>Developer</designation>
                <location>Bangalore, Karnataka, India</location>
                <last_date>2021-09-09</last_date>
                <logo>https://job-board-portal-script.logicspice.com/app/webroot/files/joblogo/37fe8_customer-supprt.jpg</logo>
            </job>
          </jobs>';

//          <city>Bangalore</city>
//          <state>Karnataka</state>
//          <country>India</country>
        die;
    }

    public function admin_importlist($slug = null) {

        $this->layout = "admin";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Auto Job Import List");

        $this->set('import_list', 'active');

        $condition = array();


        $separator = array($slug);
        $urlSeparator = array();
        $userName = '';
        $searchByDateFrom = '';
        $searchByDateTo = '';

        if (!empty($this->data)) {

            if (isset($this->data['Feed']['userName']) && $this->data['Feed']['userName'] != '') {
                $userName = trim($this->data['Feed']['userName']);
            }

            if (isset($this->data['Feed']['searchByDateFrom']) && $this->data['Feed']['searchByDateFrom'] != '') {
                $searchByDateFrom = trim($this->data['Feed']['searchByDateFrom']);
            }

            if (isset($this->data['Feed']['searchByDateTo']) && $this->data['Feed']['searchByDateTo'] != '') {
                $searchByDateTo = trim($this->data['Feed']['searchByDateTo']);
            }

            if (isset($this->data['Feed']['action'])) {
                $idList = $this->data['Feed']['idList'];
                // pr($idList); die;
                if ($idList) {
                    if ($this->data['Feed']['action'] == "activate") {
                        $cnd = array("Feed.id IN ($idList) ");
                        $this->Feed->updateAll(array('Feed.status' => "'1'"), $cnd);

                        $cnd1 = array("Job.feed_id IN ($idList) ");
                        $this->Job->updateAll(array('Job.status' => "'1'"), $cnd1);
                    } elseif ($this->data['Feed']['action'] == "deactivate") {
                        $cnd = array("Feed.id IN ($idList) ");
                        $this->Feed->updateAll(array('Feed.status' => "'0'"), $cnd);

                        $cnd1 = array("Job.feed_id IN ($idList) ");
                        $this->Job->updateAll(array('Job.status' => "'0'"), $cnd1);
                    } elseif ($this->data['Feed']['action'] == "delete") {
                        $cnd = array("Feed.id IN ($idList) ");
                        $this->Feed->deleteAll($cnd);

                        $cnd1 = array("Job.feed_id IN ($idList) ");
                        $this->Job->deleteAll($cnd1);
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
            $condition[] = " (`Feed`.`name` LIKE '%" . addslashes($userName) . "%' ) ";
            $userName = str_replace('\_', '_', $userName);
            $this->set('searchKey', $userName);
        }

        if (isset($searchByDateFrom) && $searchByDateFrom != '') {
            $separator[] = 'searchByDateFrom:' . urlencode($searchByDateFrom);
            $searchByDateFrom = str_replace('_', '\_', $searchByDateFrom);
            $searchByDate_con1 = date('Y-m-d', strtotime($searchByDateFrom));
            $condition[] = " (Date(Feed.created)>='$searchByDate_con1' ) ";
            $searchByDateFrom = str_replace('\_', '_', $searchByDateFrom);
        }

        if (isset($searchByDateTo) && $searchByDateTo != '') {
            $separator[] = 'searchByDateTo:' . urlencode($searchByDateTo);
            $searchByDateTo = str_replace('_', '\_', $searchByDateTo);
            $searchByDate_con2 = date('Y-m-d', strtotime($searchByDateTo));
            $condition[] = " (Date(Feed.created)<='$searchByDate_con2' ) ";
            $searchByDateTo = str_replace('\_', '_', $searchByDateTo);
        }

        $order = 'Feed.id Desc';

        $separator = implode("/", $separator);


        $this->set('searchByDateFrom', $searchByDateFrom);
        $this->set('searchByDateTo', $searchByDateTo);


        $urlSeparator = implode("/", $urlSeparator);
        $this->set('userName', $userName);
        $this->set('separator', $separator);
        $this->set('urlSeparator', $urlSeparator);
        $this->set('slug', $slug);
        $this->paginate['Feed'] = array('conditions' => $condition, 'limit' => '20', 'page' => '1', 'order' => $order);
        $this->set('jobs', $this->paginate('Feed'));
//        pr($condition);


        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/jobs';
            $this->render('importlist');
        }
    }

    public function admin_activatefeed($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Feed->field('id', array('Feed.slug' => $slug));
            $cnd = array("Feed.id = $id");
            $this->Feed->updateAll(array('Feed.status' => "'1'"), $cnd);

            $cnd1 = array("Job.feed_id = $id");
            $this->Job->updateAll(array('Job.status' => "'1'"), $cnd1);
            $this->set('action', '/admin/jobs/deactivatefeed/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivatefeed($slug = NULL) {
        $this->layout = "";
        if ($slug != '') {
            $id = $this->Feed->field('id', array('Feed.slug' => $slug));
            $cnd = array("Feed.id = $id");
            $this->Feed->updateAll(array('Feed.status' => "'0'"), $cnd);

            $cnd1 = array("Job.feed_id = $id");
            $this->Job->updateAll(array('Job.status' => "'0'"), $cnd1);
            $this->set('action', '/admin/jobs/activatefeed/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deletefeed($slug = NULL) {
        $this->set('list_users', 'active');
        $this->set('default', '1');
        if ($slug != '') {
            $id = $this->Feed->field('id', array('Feed.slug' => $slug));
            if ($this->Feed->delete($id)) {
                $cnd1 = array("Job.feed_id = $id");
                $this->Job->deleteAll($cnd1);
            }
            $this->Session->setFlash('Feed details deleted successfully', 'success_msg');
        }
        $this->redirect('/admin/jobs/importlist');
    }

    public function admin_import1() {
        ini_set("memory_limit", "-1");
        ini_set('max_execution_time', '10000');

        $this->layout = "admin";
        $mail_from = $this->getMailConstant('from');
        $site_url = $this->getSiteConstant('url');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Import Jobs");

        $this->set('import_job', 'active');
        $this->set('default', '2');
        global $worktype;
        global $experienceArray;
        global $sallery;

//echo '<pre>'; 
//        $xml = simplexml_load_file(UPLOAD_DOCUMENT_PATH."/plcjob_all-ch.xml") or die("Error: Cannot create object");
//       foreach($xml->children() as $books) {
//  echo $books->jobid . ", ";
//  echo $books->title . ", ";
//  echo $books->year . ", ";
//  echo $books->price . "<br>";
//}
//        foreach($xml->children() as $key => $children) {
//  print($children->TEMPLATE); echo "<br>";
//  print($children->RECIPIENT_NUM); echo "<br>";
//  // Remaining codes here.
//}
//        $xml = new DOMDocument();
//$xml->load(UPLOAD_DOCUMENT_PATH."/plcjob_all-ch.xml");
//        $data = array (           
//      'template'       => $xml->getElementsByTagName('TEMPLATE')->item(0)->nodeValue,
//       'recipient_num' => $xml->getElementsByTagName('RECIPIENT_NUM')->item(0)->nodeValue          
//        );         
// var_dump($data);
//      echo '<pre>';  print_r($data);
//      die;
//        App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel/PHPExcel.php'));
        // Create new PHPExcel object
//        $file_name = '58af9_Jobseekers_FormatNovember_24_2020_01_00_pm.xlsx';
        if ($this->data) {
            if (!empty($this->data['User']['filedata']['name'])) {
                $imageArray = $this->data['User']['filedata'];
                $imageArray['name'] = str_replace("\_", '_', str_replace(array('%', '$', '#', '%20', "/", "'", ' ', "\'"), '_', $imageArray['name']));
                $returnedUploadImageArray = $this->PImage->upload($imageArray, UPLOAD_DOCUMENT_PATH, "xls,xlsx");
//                  pr($returnedUploadImageArray);exit;
                if (isset($returnedUploadImageArray[1]) && !empty($returnedUploadImageArray[1])) {
                    $msgString .= "- File not valid.<br>";
                    $file_name = '';
                } else {
                    $file_name = $returnedUploadImageArray[0];
                }
            }

            $file = UPLOAD_DOCUMENT_PATH . '/' . $file_name;
            require BASE_PATH . "/app/Vendor/PHPExcel.php";
            require BASE_PATH . "/app/Vendor/PHPExcel/IOFactory.php";
            $objPHPExcel = new PHPExcel();
//        $objPHPExcel = new PHPExcel();
            $Reader = IOFactory::createReaderForFile($file);
            $Reader->setReadDataOnly(true);

            $objXLS = $Reader->load($file);

            $objWorksheet = $objXLS->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow();
//        pr($objXLS);
//        exit;

            $rowNumber = 2;
            $totalInsertedRow = 0;
            $errorArray = array();
            $rowNumberError = array();
            $k = 0;

            for ($dd = 2; $dd <= $highestRow; $dd++) {
                $error = '';
                $row1 = $objXLS->getSheet(0)->getCell('A' . $dd)->getValue();
                $row2 = $objXLS->getSheet(0)->getCell('B' . $dd)->getValue();
                $row3 = $objXLS->getSheet(0)->getCell('C' . $dd)->getValue();
                $row4 = $objXLS->getSheet(0)->getCell('D' . $dd)->getValue();
                $row5 = $objXLS->getSheet(0)->getCell('E' . $dd)->getValue();
                $row6 = $objXLS->getSheet(0)->getCell('F' . $dd)->getValue();
                $row7 = $objXLS->getSheet(0)->getCell('G' . $dd)->getValue();
                $row8 = $objXLS->getSheet(0)->getCell('H' . $dd)->getValue();
                $row9 = $objXLS->getSheet(0)->getCell('I' . $dd)->getValue();
                $row10 = $objXLS->getSheet(0)->getCell('J' . $dd)->getValue();
                $row11 = $objXLS->getSheet(0)->getCell('K' . $dd)->getValue();
                $row12 = $objXLS->getSheet(0)->getCell('L' . $dd)->getValue();
                $row13 = $objXLS->getSheet(0)->getCell('M' . $dd)->getValue();
                $row14 = $objXLS->getSheet(0)->getCell('N' . $dd)->getValue();
                $row15 = $objXLS->getSheet(0)->getCell('O' . $dd)->getValue();
                $row16 = $objXLS->getSheet(0)->getCell('P' . $dd)->getValue();
                $row17 = $objXLS->getSheet(0)->getCell('Q' . $dd)->getValue();
//                $row18 = $objXLS->getSheet(0)->getCell('R' . $dd)->getValue();
//                $row19 = $objXLS->getSheet(0)->getCell('S' . $dd)->getValue();
//                $row20 = $objXLS->getSheet(0)->getCell('T' . $dd)->getValue();
//                $row21 = $objXLS->getSheet(0)->getCell('U' . $dd)->getValue();
//                $row22 = $objXLS->getSheet(0)->getCell('V' . $dd)->getValue();
//                $row23 = $objXLS->getSheet(0)->getCell('W' . $dd)->getValue();
//                $row24 = $objXLS->getSheet(0)->getCell('X' . $dd)->getValue();
//                $row = $objXLS->getSheet(0)->getCell('Y' . $dd)->getValue();

                $resourceList[$k]['email'] = trim($row1);
                $resourceList[$k]['title'] = trim($row2);
                $resourceList[$k]['category'] = trim($row3);
                $resourceList[$k]['subcategory'] = trim($row4);
                $resourceList[$k]['description'] = trim($row5);
                $resourceList[$k]['company_name'] = trim($row6);
                $resourceList[$k]['work_type'] = trim($row7);
                $resourceList[$k]['contact_name'] = trim($row8);
                $resourceList[$k]['brief_abtcomp'] = trim($row9);
                $resourceList[$k]['contact_number'] = trim($row10);
                $resourceList[$k]['url'] = trim($row11);
                $resourceList[$k]['exp'] = trim($row12);
                $resourceList[$k]['salary'] = trim($row13); //column added
                $resourceList[$k]['skill'] = trim($row14); //column added
                $resourceList[$k]['designation'] = trim($row15);
                $resourceList[$k]['job_city'] = trim($row16);
                $resourceList[$k]['expire_time'] = trim($row17);
//                $resourceList[$k]['total_marks'] = trim($row18);
//                $resourceList[$k]['percentage'] = trim($row19);
//                $resourceList[$k]['result'] = trim($row20);
//                $resourceList[$k]['grade'] = trim($row21);
//                $resourceList[$k]['nsqf_level'] = trim($row22);
//                $resourceList[$k]['job_role'] = trim($row23);
//                $resourceList[$k]['acedmic_year'] = trim($row24);

                $k++;
            }
//            echo '<pre>';            print_r($resourceList);die;

            $error = 0;
            $suces = 0;
            $msgString = '';
            if ($resourceList) {
                $sr = 1;
                $NewArray = array();
                $errorMessage = '';
                foreach ($resourceList as $resource) {

                    $errorArray = array();

//                    $user_id = $this->User->field('User.id', array('User.email_address' => trim($resource['Email Address'])));
                    if (empty($resource['title'])) {
                        $errorArray[] = 'title';
                        $errorMessage .= "Job title is required field.";
                    }
                    if (empty($resource['category'])) {
                        $errorArray[] = 'category';
                        $errorMessage .= "Job category is required field.";
                    } else {
                        $category = $resource["category"];
                        $condition = array();
                        $condition[] = " FIND_IN_SET(" . $category . ",Category.keywords)";
                        $categoryId = $this->User->find('first', array('conditions' => $condition, 'fields' => array('Category.id')));
                        if (!$categoryId) {
                            $errorArray[] = 'category';
                            $errorMessage .= $category . " Category not exists.";
                        }
                    }
//                    if (empty($resource['Last Name'])) {
//                        $errorArray[] = 'Last Name';
//                        $errorMessage .= "Last Name is required field.";
//                    }                   
//                    if (empty($resource['email'])) {
//                        $errorArray[] = 'email';
//                        $errorMessage .= "Email Address is required field.";
//                    } elseif ($this->User->checkEmail($resource['email']) == false) {
//                        $errorArray[] = 'email';
//                        $errorMessage .= $email . " Email Address Not Valid.";
//                    }


                    if ($resource["email"] && $resource["email"] != 'NULL') {
                        $email = $resource["email"];
                        $userCheck = $this->User->field('User.id', array('User.email_address' => $email));
                        if (!$userCheck) {
                            $errorArray[] = 'email';
                            $errorMessage .= $email . " Email not exists.";
                        }
                    } else {
                        $errorMessage .= "Email is required field.";
                    }

                    $offshore = 0;
                    $onshore = 0;
                    if (isset($errorMessage) && $errorMessage != '') {
                        $newArr = array_values(array_unique($errorArray));
                        $error = 1;
                        $NewArray[] = array(
                            'title' => $resource['title'],
//                            'Last Name' => $resource['Last Name'],
                            'Email Address' => $resource['email'],
                            'error' => $newArr,
                            'Comments' => $errorMessage);
                    }
                    echo '<pre>';
                    pr($NewArray);
                    die;
                }

                if (empty($NewArray)) {
                    foreach ($resourceList as $resource) {
                        echo '<pre>';
                        print_r($resource);
                        die;

                        $email = trim($resource['email']);
                        $title = trim($resource['title']);
                        $category = trim($resource['category']);
                        $subcategory = trim($resource['subcategory']);
                        $description = trim($resource['description']);
                        $company_name = trim($resource['company_name']);
                        $work_type = trim($resource['work_type']);
                        $contact_name = trim($resource['contact_name']);
                        $brief_abtcomp = trim($resource['brief_abtcomp']);
                        $contact_number = trim($resource['contact_number']);
                        $url = trim($resource['url']);
                        $exp = trim($resource['exp']);
                        $salary = trim($resource['salary']);
                        $skill = trim($resource['skill']);
                        $designation = trim($resource['designation']);
                        $job_city = trim($resource['job_city']);
                        $expire_time = trim($resource['expire_time']);

//                        echo $email;die;
                        $condition = array();
                        $condition[] = " (Category.name like '" . $category . "%')  ";
                        $category_id = $this->Category->field('id', $condition);

                        if (!$category_id) {
                            $this->request->data = array();
                            $this->Category->create();
                            $this->request->data['Category']['name'] = $category;
                            $this->request->data['Category']['slug'] = $this->stringToSlugUnique($category, 'Category');
                            $this->request->data['Category']['status'] = '1';
                            $this->request->data['Category']['image'] = '';
                            $this->Category->save($this->data);
                            $category_id = $this->Category->id;

                            $this->request->data = array();
                            $this->Category->create();
                            $this->request->data['Category']['parent_id'] = $category_id;
                            $this->request->data['Category']['name'] = $subcategory;
                            $this->request->data['Category']['slug'] = $this->stringToSlugUnique($subcategory, 'Category');
                            $this->request->data['Category']['status'] = '1';
                            $this->request->data['Category']['image'] = '';
                            $this->Category->save($this->data);
                            $subcategory_id = $this->Category->id;
                        } else {
                            $condition = array();
                            $condition[] = " (Category.name like '" . $subcategory . "%' and Category.parent_id = $category_id)  ";
                            $subcategory_id = $this->Category->field('id', $condition);
                            if (!$subcategory_id) {
                                $this->request->data = array();
                                $this->Category->create();
                                $this->request->data['Category']['parent_id'] = $category_id;
                                $this->request->data['Category']['name'] = $subcategory;
                                $this->request->data['Category']['slug'] = $this->stringToSlugUnique($subcategory, 'Category');
                                $this->request->data['Category']['status'] = '1';
                                $this->request->data['Category']['image'] = '';
                                $this->Category->save($this->data);
                                $subcategory_id = $this->Category->id;
                            }
                        }

                        $work_type_id = array_search(ucwords($work_type), $worktype);
                        $sallery_array = array_keys($sallery);
                        foreach ($sallery_array as $salrange) {
                            list($minsal, $maxsal) = explode(',', $salrange);
                            if ($minsal <= $salary && $maxsal >= $salary) {
                                break;
                            }
                        }
                        $exper_array = array_keys($experienceArray);
                        foreach ($exper_array as $experrange) {
                            list($minexp, $maxexp) = explode(',', $experrange);
                            if ($minexp <= $exp && $maxexp >= $exp) {
                                break;
                            }
                        }

                        $this->request->data = array();
                        $this->Category->create();
                        $this->request->data['State']['state_name'] = $state;
                        $this->request->data['State']['slug'] = $this->stringToSlugUnique($state, 'State');
                        $this->request->data['State']['status'] = '1';
                        if ($this->State->save($this->data)) {
                            $state_id = $this->State->id;
                            $condition = array('City.state_id' => $state_id);
                            $condition[] = " (City.city_name like '%" . addslashes($city) . "%')  ";
                            $city_id = $this->City->field('id', $condition);

                            if (!$city_id) {
                                $this->request->data = array();
                                $this->City->create();
                                $this->request->data['City']['slug'] = $this->stringToSlugUnique($city, 'City');
                                $this->request->data['City']['state_id'] = $state_id;
                                $this->request->data['City']['city_name'] = $city;
                                $this->request->data['City']['status'] = 1;
                                $this->City->save($this->data);
                                $city_id = $this->City->id;
                            }
                        }


                        $suces = 1;
                        $skillId = 1;
                        $designationId = 1;
                        $this->Job->create();
                        $this->request->data = array();
                        $this->request->data["Job"]["category_id"] = $category_id;
                        $this->request->data["Job"]["subcategory_id"] = $subcategory_id;
                        $this->request->data["Job"]["skill"] = $skillId;
                        $this->request->data["Job"]["designation"] = $designationId;
                        $this->request->data["Job"]["work_type"] = $work_type_id;
                        $this->request->data["Job"]["min_salary"] = $minsal;
                        $this->request->data["Job"]["max_salary"] = $maxsal;
                        $this->request->data["Job"]["min_exp"] = $minexp;
                        $this->request->data["Job"]["max_exp"] = $maxexp;



                        $this->request->data["Job"]["logo"] = '';


//                        if ($this->data['Job']['subcategory_id']) {
//                            $this->request->data['Job']['subcategory_id'] = implode(',', $this->data['Job']['subcategory_id']);
//                        } else {
//                            $this->request->data['Job']['subcategory_id'] = 0;
//                        }
//                if ($this->data["Job"]["youtube_link"]) {
//                    $url = $this->data['Job']['youtube_link'];
//                    $urlArray = explode('watch?v=', $url);
//                    $this->request->data["Job"]["youtube_link"] = $urlArray[1];
//                }
                        $slug = $this->stringToSlugUnique($this->data["Job"]["title"], 'Job');
                        $this->request->data['Job']['slug'] = $slug;
                        $this->request->data['Job']['type'] = $_SESSION['type'];
                        $this->request->data['Job']['status'] = 1;
                        $this->request->data['Job']['user_id'] = $userId;
                        /* --2016-05-06-- */
                        //  $this->request->data['Job']['payment_status'] = '0';
                        /* --2016-05-06-- */
                        $this->request->data['Job']['payment_status'] = 2;
                        $this->request->data['Job']['amount_paid'] = $_SESSION['amount'];
                        $this->request->data['Job']['job_number'] = 'JOB' . $userId . time();
                        if (empty($this->data["Job"]["exp_month"])) {
                            $this->request->data['Job']['exp_month'] = 0;
                        }


                        if ($_SESSION['type'] == 'gold') {
                            $this->request->data['Job']['hot_job_time'] = time() + 7 * 24 * 3600;
                        } else {
                            $this->request->data['Job']['hot_job_time'] = time();
                        }
                        $this->request->data['Job']['expire_time'] = strtotime($this->data['Job']['expire_time']);

                        $this->request->data['Job']['skill'] = implode(',', $this->data['Job']['skill']);

                        $this->request->data['Job']['user_plan_id'] = $isAbleToJob['user_plan_id'];

                        if ($this->Job->save($this->data)) {
                            $jobId = $this->Job->id;
                            $jobDetail = $this->Job->findById($jobId);
                            $site_title = $this->getSiteConstant('title');
                            $mail_from = $this->getMailConstant('from');

                            $title = $jobDetail['Job']['title'];
                            $category = $jobDetail['Category']['name'];
                            $skill = $jobDetail['Skill']['name'];
                            $location = $jobDetail['Job']['job_city'];
                            $minExp = $jobDetail['Job']['min_exp'] . ' Year';
                            $maxExp = $jobDetail['Job']['max_exp'] . ' Year';
                            $min_salary = CURRENCY . ' ' . intval($jobDetail['Job']['min_salary']);
                            $max_salary = CURRENCY . ' ' . intval($jobDetail['Job']['max_salary']);
                            $description = $jobDetail['Job']['description'];
                            $company_name = $jobDetail['Job']['company_name'];
                            $contact_number = $jobDetail['Job']['contact_number'];
                            $website = $jobDetail['Job']['url'] ? $jobDetail['Job']['url'] : 'N/A';
                            $address = $jobDetail['Job']['address'] ? $jobDetail['Job']['address'] : 'N/A';

                            $designation = $this->Skill->field('name', array(
                                'Skill.status' => 1,
                                'Skill.type' => 'Designation',
                                'Skill.id' => $jobDetail['Job']['designation'],
                            ));
                            $username = $jobDetail['User']['first_name'] . ' ' . $jobDetail['User']['last_name'];
                            $this->Email->to = $jobDetail['User']['email_address'];
                            $currentYear = date('Y', time());
                            $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                            $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='46'"));

                            $toSubArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                            $fromSubArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                            $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                            $this->Email->subject = $subjectToSend;


                            $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                            $this->Email->from = $site_title . "<" . $mail_from . ">";
                            $toRepArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                            $fromRepArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                            $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                            $this->Email->layout = 'default';
                            $this->set('messageToSend', $messageToSend);
                            $this->Email->template = 'email_template';
                            $this->Email->sendAs = 'html';
//                        $this->Email->send();
//                        $this->Email->reset();

                            $users = $this->AlertLocation->getUsersToAlert($jobId);
                            /*  if (!empty($users)) {
                              foreach ($users as $user) {
                              //                                pr($user);
                              $this->AlertJob->create();
                              $this->request->data["AlertJob"]['job_id'] = $jobId;
                              $this->request->data["AlertJob"]['user_id'] = $user['User']['id'];
                              $this->request->data['AlertJob']['email_address'] = $user['User']['email_address'];
                              $this->request->data["AlertJob"]['status'] = 1;
                              $this->AlertJob->save($this->data);
                              }
                              } */
                        }
                    }
                }
            }
//             exit;

            if ($error == 1) {
                $this->Session->setFlash($errorMessage, 'error_msg');
                $this->redirect('/admin/jobs/import');
                exit;
            } elseif ($suces == 1) {
                $this->Session->setFlash('Jobs details saved successfully', 'success_msg');
                $this->redirect('/admin/jobs/import');
                exit;
            } else {
                $this->Session->setFlash('Something wrong in xls file.', 'error_msg');
                $this->redirect('/admin/jobs/import');
                exit;
            }
        }
    }

    public function admin_downloadfile() {
        set_time_limit(0);
    }

}

//class ends
?>