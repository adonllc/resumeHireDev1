<?php

class CitiesController extends AppController {

    public $name = 'Cities';
    public $uses = array('Admin', 'City', 'State', 'Country', 'PostCode', 'Location');
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

    public function admin_index($sslug = null, $cslug = null) {
        $this->layout = "admin";
        $this->set('country_list', 'active');

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "List Cities");

        $stateInfo = $this->State->findBySlug($sslug);
        if (!$stateInfo) {
            $this->redirect('/admin/states/index/' . $cslug);
        }
        $countryInfo = $this->Country->findBySlug($cslug);
        $this->set('countryInfo', $countryInfo);
        $this->set('stateInfo', $stateInfo);
        $this->set('sslug', $sslug);
        $this->set('cslug', $cslug);

        $condition = array('City.state_id' => $stateInfo['State']['id']);
        $separator = array();
        $urlSeparator = array();
        $name = '';


        if (!empty($this->data)) {
            if (isset($this->data['City']['name']) && $this->data['City']['name'] != '') {
                $name = trim($this->data['City']['name']);
            }

            if (isset($this->data['City']['action'])) {
                $idList = $this->data['City']['idList'];
                if ($idList) {
                    if ($this->data['City']['action'] == "activate") {
                        $cnd = array("City.id IN ($idList) ");
                        $this->City->updateAll(array('City.status' => "'1'"), $cnd);
                    } elseif ($this->data['City']['action'] == "deactivate") {
                        $cnd = array("City.id IN ($idList) ");
                        $this->City->updateAll(array('City.status' => "'0'"), $cnd);
                    } elseif ($this->data['City']['action'] == "delete") {
                        $cnd = array("City.id IN ($idList) ");
                        $this->City->deleteAll($cnd);
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
            $condition[] = " (City.city_name like '%" . addslashes($name) . "%')  ";
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

        $this->paginate['City'] = array(
            'conditions' => $condition,
            'order' => array('City.id' => 'DESC'),
            'limit' => '50'
        );


        $this->set('cities', $this->paginate('City'));
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->viewPath = 'Elements' . DS . 'admin/cities/';
            $this->render('index');
        }
    }

    public function admin_addcity($sslug = null, $cslug = null) {
        $this->set('country_list', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Add City");

        $stateInfo = $this->State->findBySlug($sslug);
        if (!$stateInfo) {
            $this->redirect('/admin/states/index/' . $cslug);
        }
        $countryInfo = $this->Country->findBySlug($cslug);
        $this->set('countryInfo', $countryInfo);
        $this->set('stateInfo', $stateInfo);
        $this->set('sslug', $sslug);
        $this->set('cslug', $cslug);


        if ($this->data) {

            if (empty($this->data["City"]["city_name"])) {
                $msgString .="- City name is required field.<br>";
            } elseif ($this->City->isRecordUniqueCity($this->data["City"]["city_name"], $stateInfo['State']['id']) == false) {
                $msgString .="- City name already exists.<br>";
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                $this->request->data['City']['slug'] = $this->stringToSlugUnique($this->data["City"]["city_name"], 'City');
                $this->request->data['City']['status'] = '1';
                $this->request->data['City']['state_id'] = $stateInfo['State']['id'];
                $this->request->data['City']['country_id'] = $stateInfo['State']['country_id'];
                if ($this->City->save($this->data)) {
                    $this->Session->setFlash('City added successfully', 'success_msg');
                    $this->redirect('/admin/cities/index/' . $sslug . '/' . $cslug);
                }
            }
        }
    }

    public function admin_editcity($slug = null, $sslug = null, $cslug = null) {

        $this->set('country_list', 'active');
        $msgString = "";

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . "Edit City");

        $stateInfo = $this->State->findBySlug($sslug);
        if (!$stateInfo) {
            $this->redirect('/admin/states/index/' . $cslug);
        }
        $countryInfo = $this->Country->findBySlug($cslug);
        $this->set('countryInfo', $countryInfo);
        $this->set('stateInfo', $stateInfo);
        $this->set('sslug', $sslug);
        $this->set('cslug', $cslug);


        if ($this->data) {

            if (empty($this->data["City"]["city_name"])) {
                $msgString .="- City name is required field.<br>";
            } elseif (strtolower($this->data["City"]["city_name"]) != strtolower($this->data["City"]["old_name"])) {
                if ($this->City->isRecordUniqueCity($this->data["City"]["city_name"], $stateInfo['State']['id']) == false) {
                    $msgString .="- City name already exists.<br>";
                }
            }

            if (isset($msgString) && $msgString != '') {
                $this->Session->setFlash($msgString, 'error_msg');
            } else {
                if ($this->City->save($this->data)) {
                    $this->Session->setFlash('City updated successfully', 'success_msg');
                    $this->redirect('/admin/cities/index/' . $sslug . '/' . $cslug);
                }
            }
        } else {
            $id = $this->City->field('id', array('City.slug' => $slug));
            $this->City->id = $id;
            $this->data = $this->City->read();
            $this->request->data['City']['old_name'] = $this->data['City']['city_name'];
        }
    }

    public function admin_activateCity($slug = NULL) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->City->field('id', array('City.slug' => $slug));
            $cnd = array("City.id = $id");
            $this->City->updateAll(array('City.status' => "'1'"), $cnd);
            $this->set('action', '/admin/cities/deactivateCity/' . $slug);
            $this->set('id', $id);
            $this->set('status', 1);
            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deactivateCity($slug = NULL, $parentSlug = null) {
        if ($slug != '') {
            $this->layout = "";
            $id = $this->City->field('id', array('City.slug' => $slug));
            $cnd = array("City.id = $id");
            $this->City->updateAll(array('City.status' => "'0'"), $cnd);
            $this->set('action', '/admin/cities/activateCity/' . $slug);
            $this->set('id', $id);
            $this->set('status', 0);


            $this->viewPath = 'Elements' . DS . 'admin';
            $this->render('update_status');
        }
    }

    public function admin_deleteCity($slug = null, $sslug = null, $cslug = null) {
        $id = $this->City->field('id', array('City.slug' => $slug));
        if ($id) {

            $this->City->delete($id);
            $this->Session->setFlash('City deleted successfully', 'success_msg');
            $this->redirect(array('controller' => 'cities', 'action' => 'index', $sslug, $cslug));
        } else {
            $this->Session->setFlash('No record deleted', 'error_msg');
            $this->redirect(array('controller' => 'cities', 'action' => 'index', $sslug, $cslug));
        }
    }

    function admin_getStateCityByPostCode($model = 'User', $postCode = null) {
        $this->layout = '';
        $cityList = array();
        $stateList = array();
        if ($postCode) {
            $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $postCode)));
            if ($postCodeDetails) {
                $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
                $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
            }
        }
        $this->set('stateList', $stateList);
        $this->set('cityList', $cityList);
        $this->set('model', $model);
    }

    function admin_getCities($modal = 'User', $required = 'required') {
        $this->layout = '';
        $state_id = $this->data[$modal]['state_id'];
        if (!empty($state_id)) {
            $city_list = $this->City->getCityList($state_id);
        }

        $this->set('cityList', $city_list);
        $this->set('modal', $modal);
        $this->set('required', $required);
    }

    function getStateCity($model = 'User', $state_id = null) {
        $this->layout = '';
        $cityList = array();
        if ($state_id) {
            $cityList = $this->City->getCityList($state_id);
        }
        $this->set('cityList', $cityList);
        $this->set('model', $model);
    }

    function getStateCityByPostCode($model = 'User', $postCode = null) {
        $this->layout = '';
        $cityList = array();
        $stateList = array();
        if ($postCode) {
            $postCodeDetails = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $postCode)));
            if ($postCodeDetails) {
                $stateList = $this->State->getStateList($postCodeDetails['PostCode']['state_id']);
                $cityList = $this->City->getCityList($postCodeDetails['PostCode']['id']);
            }
        }
        $this->set('stateList', $stateList);
        $this->set('cityList', $cityList);
        $this->set('model', $model);
    }

    public function importPostCode() {

        ini_set('memory_limit', '-1');

        $stateIdArray = $this->State->find('list', array('fields' => array('state_name', 'id')));


        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Import XLSX');

        $this->layout = "admin";

        App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel/PHPExcel.php'));
        // Create new PHPExcel object
        $file = UPLOAD_CERTIFICATE_PATH . 'ACTallaustralianpostcodes.xls';
        $objPHPExcel = new PHPExcel();
        $Reader = PHPExcel_IOFactory::createReaderForFile($file);
        $Reader->setReadDataOnly(true);

        $objXLS = $Reader->load($file);

        $objWorksheet = $objXLS->getActiveSheet();

        // $objXLS->setActiveSheetIndex(0)->getHighestRow();

        $highestRow = $objWorksheet->getHighestRow();



        $rowNumber = 2;
        $totalInsertedRow = 0;
        $errorArray = array();
        $rowNumberError = array();
        $k = 0;


        for ($dd = 2; $dd <= $highestRow; $dd++) {

            $error = '';
            $this->State->create();

            $postCode = $this->import_csv_filter($objXLS->getSheet(0)->getCell('A' . $dd)->getValue());
            $suburb = $this->import_csv_filter($objXLS->getSheet(0)->getCell('B' . $dd)->getValue());
            $state = $this->import_csv_filter($objXLS->getSheet(0)->getCell('C' . $dd)->getValue());

            $postArray[] = trim($postCode);
            $stateArray[trim($postCode)] = trim($state);


//            $this->State->create();
//            $this->request->data['State']['country_id'] = $country_id;
//            $this->request->data['State']['name'] = trim($name);
//            $this->request->data['State']['slug'] = $this->stringToSlugUnique($name, 'State', 'slug');
//            $this->request->data['State']['status'] = 1;
//            $this->State->save($this->data['State']);
            $k++;
        }



        //pr($stateArray);exit;

        $postcode = array_unique($postArray);

        foreach ($postcode as $postcodeV) {

            $oldSata = $this->PostCode->find('first', array('conditions' => array('PostCode.post_code' => $postcodeV)));

            if ($oldSata) {
                $this->request->data['PostCode']['id'] = $oldSata['PostCode']['id'];
            }

            $this->request->data['PostCode']['country_id'] = 1;
            $this->request->data['PostCode']['state_id'] = $stateIdArray[$stateArray[$postcodeV]];
            $this->request->data['PostCode']['post_code'] = $postcodeV;
            $this->request->data['PostCode']['slug'] = $postcodeV;
            $this->request->data['PostCode']['status'] = 1;


            $this->PostCode->save($this->data['PostCode']);
        }

        exit;
    }

    public function importSuburb() { exit;

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');

        $stateIdArray = $this->State->find('list', array('fields' => array('state_name', 'id')));
        $postCodeIdArray = $this->PostCode->find('list', array('fields' => array('post_code', 'id')));

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Import XLSX');

        $this->layout = "admin";

        App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel/PHPExcel.php'));
        // Create new PHPExcel object
        $file = UPLOAD_CERTIFICATE_PATH . 'ACTallaustralianpostcodes.xls';
        $objPHPExcel = new PHPExcel();
        $Reader = PHPExcel_IOFactory::createReaderForFile($file);
        $Reader->setReadDataOnly(true);

        $objXLS = $Reader->load($file);

        $objWorksheet = $objXLS->getActiveSheet();

        // $objXLS->setActiveSheetIndex(0)->getHighestRow();

        $highestRow = $objWorksheet->getHighestRow();



        $rowNumber = 2;
        $totalInsertedRow = 0;
        $errorArray = array();
        $rowNumberError = array();
        $k = 0;


        for ($dd = 2; $dd <= $highestRow; $dd++) {

            $error = '';
            $this->State->create();

            $postCode = $this->import_csv_filter($objXLS->getSheet(0)->getCell('A' . $dd)->getValue());
            $suburb = $this->import_csv_filter($objXLS->getSheet(0)->getCell('B' . $dd)->getValue());
            $state = $this->import_csv_filter($objXLS->getSheet(0)->getCell('C' . $dd)->getValue());

            $suburbArray[] = trim($suburb);
            $postArray[trim($suburb)] = trim($postCode);
            $stateArray[trim($suburb)] = trim($state);


//            $this->State->create();
//            $this->request->data['State']['country_id'] = $country_id;
//            $this->request->data['State']['name'] = trim($name);
//            $this->request->data['State']['slug'] = $this->stringToSlugUnique($name, 'State', 'slug');
//            $this->request->data['State']['status'] = 1;
//            $this->State->save($this->data['State']);
            $k++;
        }




        $suburbArray = array_unique($suburbArray);


        foreach ($suburbArray as $key => $postcodeV) {

            $oldSata = $this->City->find('first', array('conditions' => array('City.city_name' => $postcodeV)));

            if ($oldSata) {
                $this->request->data['City']['id'] = $oldSata['City']['id'];
                $this->request->data['City']['slug'] = $oldSata['City']['slug'];
                $this->request->data['City']['country_id'] = 1;
                $this->request->data['City']['state_id'] = $stateIdArray[$stateArray[$postcodeV]];
                $this->request->data['City']['post_code_id'] = $postCodeIdArray[$postArray[$postcodeV]];
                $this->request->data['City']['city_name'] = $postcodeV;
                $this->request->data['City']['status'] = 1;
                $this->City->save($this->data['City']);
            }
        }
        pr($suburbArray);
        exit;

        exit;
    }
    
    
    public function importLocation() {

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');

       // $stateIdArray = $this->State->find('list', array('fields' => array('state_name', 'id')));
       // $postCodeIdArray = $this->PostCode->find('list', array('fields' => array('post_code', 'id')));

        $site_title = $this->getSiteConstant('title');
        $tagline = $this->getSiteConstant('tagline');
        $title_for_pages = $site_title . " :: " . $tagline . " - ";
        $this->set('title_for_layout', $title_for_pages . 'Import XLSX');

        $this->layout = "admin";

        App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel/PHPExcel.php'));
        // Create new PHPExcel object
        $file = UPLOAD_CERTIFICATE_PATH . 'list_of_cities_and_towns_in_india-834j.xls';
        $objPHPExcel = new PHPExcel();
        $Reader = PHPExcel_IOFactory::createReaderForFile($file);
        $Reader->setReadDataOnly(true);

        $objXLS = $Reader->load($file);

        $objWorksheet = $objXLS->getActiveSheet();

        // $objXLS->setActiveSheetIndex(0)->getHighestRow();

        $highestRow = $objWorksheet->getHighestRow();



        $rowNumber = 2;
        $totalInsertedRow = 0;
        $errorArray = array();
        $rowNumberError = array();
        $k = 0;


        for ($dd = 2; $dd <= $highestRow; $dd++) {

            $error = '';
           
            $suburb = $this->import_csv_filter($objXLS->getSheet(0)->getCell('B' . $dd)->getValue());
           $suburbArray[] = trim($suburb);
            $k++;
        }

        $suburbArray = array_unique($suburbArray);
        
        
        foreach ($suburbArray as $key => $postcodeV) {

            $oldSata = $this->Location->find('first', array('conditions' => array('Location.name' => $postcodeV)));

            if (!$oldSata) {
                if($postcodeV){
                    $this->request->data['Location']['id'] = '';
                    $this->request->data['Location']['slug'] = $this->stringToSlugUnique($postcodeV, 'Location', 'slug');
                    $this->request->data['Location']['status'] = 1;
                    $this->request->data['Location']['name'] = $postcodeV;
                    $this->Location->save($this->data['Location']);
                }
            }
        }
        exit;

        exit;
    }

}

?>
