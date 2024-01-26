<?php

/**
 * @abstract This controller Created for Default controller of the site.
 * @Package Controller
 * @category Controller
 * @author Logicspice(info@logicspice.com)
 * @since 1.0.0 2014-10-17
 * @copyright Copyright & Copy ; 2014, Logicspice Consultancy Pvt. Ltd., Jaipur
 *
 */
class HomesController extends AppController {

    public $uses = array('User', 'Page', 'Emailtemplate', 'Category', 'State', 'City', 'Admin', 'Location', 'Skill', 'Job', 'Announcement', 'Plan','Slider');
    public $helpers = array('Html', 'Form', 'Javascript', 'Ajax', 'Text', 'Number');
    public $components = array('RequestHandler', 'Email', 'Upload', 'PImageTest', 'PImage', 'Captcha', 'Common');

    /**
     * @abstract This action written for Default Page of the site.
     * @access Public
     * @author Logicspice (info@logicspice.com)
     * @since 1.0.0 2014-10-17
     */
    public function index() {
//        Configure::write('Config.language', 'de');
//        $_SESSION['Config']['language'] = 'de';
//        $_SESSION['Config']['language'] = 'fra';
//        echo __d('controller', 'Page not found', true);
//        Configure::write('Config.language', 'fra');
//        echo '<pre>';
//        $headers = apache_request_headers();
//        print_r(Configure::read());
//        print_r($_SESSION);
//        print_r($headers);
//        die;
//


        $this->layout = "home";
        $this->set('active', 'homepage');
        $this->set('homepage','active');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline;
        $title_for_pages = HOME_META_TITLE;
        $this->set('title_for_layout', $title_for_pages);

        $sloganText = $this->Admin->field('slogan_text', array('Admin.id' => 1));
        $this->set('sloganText', $sloganText);
        $categories = $this->Category->getCategoryList();
        $this->set('categories', $categories);
        $subcategories = array();
        $this->set('subcategories', $subcategories);

        $skillList = $this->Skill->find('list', array('conditions' => array('Skill.status' => '1'), 'fields' => array('Skill.id', 'Skill.name'), 'order' => 'Skill.name asc'));
        $this->set('skillList', $skillList);

        $locationlList = $this->Location->find('list', array('conditions' => array('Location.status' => '1'), 'fields' => array('Location.id', 'Location.name'), 'order' => 'Location.name asc'));
        $this->set('locationlList', $locationlList);
        
         $time =  time();
        $jobcond = array("Job.status = 1 AND Job.category_id != 0 AND Job.expire_time >= $time ");
        $latestJobList = $this->Job->find('all', array('conditions' => $jobcond, 'order' => 'Job.created Desc', 'limit' => '12'));
        
       // print_r($latestJobList);exit;

        $this->set('latestJobList', $latestJobList);
//        echo '<pre>';print_r($latestJobList);die;

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
        $condition = array();
        $condition[] = '(User.user_type = "candidate" AND User.status = 1 )';
        $newJobseeker = $this->User->find('all', array('conditions' => $condition, 'order' => 'User.id DESC', 'limit' => 5000));
        $nrtt = array();
        if ($newJobseeker) {
            $count = 0;
            foreach ($newJobseeker as $newJobseekerVal) {
                if (isset($newJobseekerVal['Education']) && count($newJobseekerVal['Education']) > 0 || isset($newJobseekerVal['Experience']) && count($newJobseekerVal['Experience']) > 0) {
                    $nrtt[] = $newJobseekerVal;
                    $count ++;
                }
                if ($count == 5) {
                    break;
                }
            }
        }
        $this->set('newJobseeker', $nrtt);
        $condition2 = array();
        $condition2[] = '(User.user_type = "recruiter" AND User.status = 1 )';
        $newJobrecuirer = $this->User->find('all', array('conditions' => $condition2, 'order' => 'User.verify DESC,User.id DESC', 'limit' => 10));
        $this->set('newJobrecuirer', $newJobrecuirer);

       

        $categoryDetail = $this->Job->find('all', array('conditions' => $jobcond, 'order' => 'Job.created Desc', 'group' => 'Job.category_id', 'fields' => array('count(Job.id)', 'Category.name', 'Category.slug'), 'limit' => 10));
 //print_r($jobcond);exit;


        $categories_listing = $this->Category->find('all', array('conditions' => array('Category.parent_id' => 0, 'Category.status' => 1, 'Category.image <>' => ''), 'fields' => array('Category.slug', 'Category.name','Category.image','Category.id'), 'limit' => 8));
        $this->set('categories_listing', $categories_listing);


//        echo '<pre>';print_r($categories_listing);die;
        $jobsByfunction = $this->Job->find('all', array('conditions' => $jobcond, 'order' => 'Job.created Desc', 'group' => 'Job.designation', 'fields' => array('count(Job.id)', 'Designation.name', 'Designation.slug', 'Category.id', 'Category.name')));
        $this->set('jobsByfunction', $jobsByfunction);

        $announcementList = $this->Announcement->find('all', array('conditions' => array('Announcement.status' => '1'), 'fields' => array('Announcement.id', 'Announcement.name', 'Announcement.url'), 'order' => 'Announcement.id desc'));
        $this->set('announcementList', $announcementList);
        $sliderList = $this->Slider->find('all', array('conditions' => array('Slider.status' => '1'), 'fields' => array('Slider.id', 'Slider.image', 'Slider.title'), 'order' => 'Slider.id desc'));
        $this->set('sliderList', $sliderList);
//        pr($sliderList);die;

       $userId = $this->Session->read("user_id");
       if($userId){
        $userCheck = $this->User->findById($userId);
        if ($userCheck['User']['user_type'] == 'recruiter') {
            $condition = array('Plan.status' => 1, 'Plan.planuser' => 'employer');
        } else {
            $condition = array('Plan.status' => 1, 'Plan.planuser' => 'jobseeker');
        }
        } else {
            $condition = array('Plan.status' => 1);
        }

        $plans = $this->Plan->find('all', array('conditions' => $condition, 'order' => array('Plan.amount' => 'ASC')));
        $this->set('plans', $plans);
        $this->set('plans', '');
//        $contact_details = $this->Setting->find('first');
    }

    public function sitemap($page = null) {

        $this->layout = "client";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Sitemap");

       $urlArray = array(
            'how-it-works' => __d('home', 'How it works', true),
            'about-us' => __d('home', 'About Us', true),
            'career-tools' => __d('home', 'Career tools', true),
            'career-resources' => __d('home', 'Career Resources', true),
            'faq' => __d('home', 'FAQ', true),
            //'contact-us' => 'Contact Us',
            'benefits' => __d('home', 'Benefits', true),
            'terms-and-conditions' => __d('home', 'Terms and Conditions', true),
            'privacy-policy' => __d('home', 'Privacy Policy', true),
        );

        $this->set('urlArray', $urlArray);

        $categories = $this->Category->find('all', array('conditions' => array('Category.parent_id' => 0, 'Category.status' => 1)));
        $this->set('categories', $categories);

        $jobs = $this->Job->find('all', array('conditions' => array('Job.status' => 1), 'limit' => 95, 'order' => array('Job.id DESC')));
        $this->set('jobs', $jobs);
    }

    public function error() {
        $this->layout = 'client';
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . __d('controller', 'Page not found', true));
    }

    public function postTest() {
        $this->layout = "home";
        $this->set('active', 'homepage');
        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Welcome");
    }

    public function auspost() {
        //pr($this->data);exit;
        //get post data. urlencode the postcode data incase someone enters a suburb name with spaces.
        $postcode = urlencode($this->data['postcode']);

        //your Auspost API
        $apiKey = '95bc926e-368d-44e5-97d8-ad1293facd38';

        // Set the URL for the Postcode search
        $urlPrefix = 'auspost.com.au';
        $parcelTypesURL = 'http://' . $urlPrefix . '/api/postcode/search.json?q=' . $postcode . '&excludePostBoxFlag=true';

        // Lookup postcode
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $parcelTypesURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('AUTH-KEY: ' . $apiKey));
        $rawBody = curl_exec($ch);

        // Check the response: if the body is empty then an error occurred
        if (!$rawBody) {
            die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        } else {
            pr($rawBody);
        }
        exit;
    }

    public function phpinfo() {
        phpinfo();
        exit;
    }

    public function setlanguage($lang = 'en') {
        $curl = $_GET['curl'];
        $_SESSION['Config']['language'] = $lang;
        $this->redirect('/' . $curl);
    }

}

?>