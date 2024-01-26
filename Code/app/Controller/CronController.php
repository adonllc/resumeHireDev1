<?php

class CronController extends AppController {

    public $uses = array('Admin', 'User', 'JobNotification', 'JobApply', 'Job', 'Alert', 'AlertJob', 'Skill', 'Emailtemplate', 'Sendmail', 'Resume', 'Certificate', 'JobSeeker', 'AutoJob', 'Feed','Plan');
    public $helpers = array('Html', 'Form', 'Fck', 'Paginator', 'Javascript', 'Ajax', 'Text');
    //public $paginate = array('limit' => '10', 'page' => '1', 'order' => array('Booking.id' => 'DESC'));
    public $components = array('RequestHandler', 'Email', 'Upload', 'PImageTest', 'PImage', 'Common');
    public $layout = 'admin';

   public function databaseCopy() {
        
        ini_set('memory_limit', '-1');
         // MySQL host
        $mysql_host = 'localhost';
        // MySQL username
        $mysql_username = 'jobboard_jobsite';
        // MySQL password
        $mysql_password = 'V}10Y-jY)5bK';
        // Database name
        $mysql_database = 'jobboard_jobsite';
        
        $mysqli = new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database);
        $mysqli->query('SET foreign_key_checks = 0');
        if ($result = $mysqli->query("SHOW TABLES")) {
            while ($row = $result->fetch_array(MYSQLI_NUM)) {
                $mysqli->query('DROP TABLE IF EXISTS ' . $row[0]);
            }
        }

        $mysqli->query('SET foreign_key_checks = 1');
        $mysqli->close();

        // Name of the file
        $filename = BASE_PATH . '/app/webroot/db/jobsite.sql';
        //echo $filename; exit;
       

        // Connect to MySQL server
//        mysql_connect($mysql_host, $mysql_username, $mysql_password) or die('Error connecting to MySQL server: ' . mysql_error());
        // Select database
//        mysql_select_db($mysql_database) or die('Error selecting MySQL database: ' . mysql_error());

        $mysqli = new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database);
        
        // Temporary variable, used to store current query
        $templine = '';
        // Read in entire file
        $lines = file($filename);
        // Loop through each line
        foreach ($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                $mysqli->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysqli_error() . '<br /><br />');
                // Reset temp variable to empty
                $templine = '';
            }
        }
        echo "Tables imported successfully";

        exit;
    }

    public function sendAlert() {
        $this->layout = "";
        $site_title = $this->getSiteConstant('title');
        $mail_from = $this->getMailConstant('from');

        $allAlert = $this->AlertJob->find('all', array(
            'conditions' => array(
                'AlertJob.status' => 1
            ), 'order' => 'AlertJob.user_id'));
        //pr($allAlert);

        if (!empty($allAlert)) {
            foreach ($allAlert as $alert) {

                $jobDetail = $this->Job->find('first', array(
                    'conditions' => array(
                        'Job.status' => 1,
                        'Job.id' => $alert['AlertJob']['job_id']
                )));

                $title = $jobDetail['Job']['title'];
                $category = $jobDetail['Category']['name'];
                $skill = $jobDetail['Skill']['name'];
                $location = $jobDetail['Job']['job_city'];
                $minExp = $jobDetail['Job']['min_exp'] . ' Year';
                $maxExp = $jobDetail['Job']['max_exp'] . ' Year';
                $min_salary = CURRENCY . ' ' . intval($jobDetail['Job']['min_salary']) . 'Lac';
                $max_salary = CURRENCY . ' ' . intval($jobDetail['Job']['max_salary']) . 'Lac';
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

                if ($alert['User']['last_name']) {
                    $username = $alert['User']['first_name'] . ' ' . $alert['User']['last_name'];
                } else {
                    $username = $alert['User']['first_name'];
                }


                if ($alert['User']['email_address']) {

                    $this->Email->to = $alert['User']['email_address'];
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='34'"));

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
                    //$this->Email->attachments = array(UPLOAD_FULL_INVOICE_IMAGE_PATH . $jobInfo["Job"]["invoice_inumber"] . '.pdf');
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                    //print_r($this->Email->send()); exit;
                    $this->Email->reset();
                }

                $this->AlertJob->updateAll(array("AlertJob.status" => "'0'"), array('AlertJob.id' => $alert['AlertJob']['id']));
            }
        } else {
            echo "No mails to send";
        }
        exit;
    }

    public function sendAlertBySearch() {

        $this->layout = "";
        $site_title = $this->getSiteConstant('title');
        $mail_from = $this->getMailConstant('from');
        //  $this->Alert->updateAll(array("Alert.modified" => "'" . date('Y-m-d H:i:s', strtotime('-7 days')) . "'"));


        $allAlert = $this->AutoJob->find('all', array(
            'conditions' => array(
                'AutoJob.status' => 1
            ), 'order' => 'AutoJob.email_address'));
        //print_r($allAlert); exit;

        if (!empty($allAlert)) {
            foreach ($allAlert as $alert) {

                $jobDetail = $this->Job->find('first', array(
                    'conditions' => array(
                        'Job.status' => 1,
                        'Job.id' => $alert['AutoJob']['job_id']
                )));
                //print_r($jobDetail); exit;

                $title = $jobDetail['Job']['title'];
                $category = $jobDetail['Category']['name'];
                $skill = $jobDetail['Skill']['name'];
                $location = $jobDetail['Location']['name'];
                $minExp = $jobDetail['Job']['min_exp'] . ' Year';
                $maxExp = $jobDetail['Job']['max_exp'] . ' Year';
                $min_salary = CURRENCY . ' ' . intval($jobDetail['Job']['min_salary']) . 'Lac';
                $max_salary = CURRENCY . ' ' . intval($jobDetail['Job']['max_salary']) . 'Lac';
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

                $userDetail = $this->User->find('first', array('conditions' => array('User.email_address' => $alert['AutoJob']['email_address'])));
                if ($userDetail['User']['last_name']) {
                    $username = $userDetail['User']['first_name'] . ' ' . $userDetail['User']['last_name'];
                } else {
                    $username = $alert['AutoJob']['email_address'];
                }

                // print_r($userDetail); exit;
                if ($alert['AutoJob']['email_address']) {

                    $this->Email->to = $alert['AutoJob']['email_address'];
                    $currentYear = date('Y', time());
                    $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                    $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='39'"));

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
                    //$this->Email->attachments = array(UPLOAD_FULL_INVOICE_IMAGE_PATH . $jobInfo["Job"]["invoice_inumber"] . '.pdf');
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                    // pr($this->Email->send());
                    $this->Email->reset();
                }

                $this->AutoJob->updateAll(array("AutoJob.status" => "'0'"), array('AutoJob.id' => $alert['AutoJob']['id']));
            }
        } else {
            echo "No mails to send";
        }
        exit;
    }

    public function sendNewsletterEmail() {
        $this->layout = '';
        $site_title = $this->getSiteConstant('title');
        $mail_from = $this->getMailConstant('from');
        $users = $this->Sendmail->find('all', array('conditions' => array('Sendmail.is_mail_sent' => 0), 'order' => array('Sendmail.id ASC'), 'limit' => 20));
        if ($users) {
            foreach ($users as $user) {

                $email = trim($user['Sendmail']['email']);
                if ($this->User->checkEmail($email) == true) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                        $this->Email->to = $email;
                        $this->Email->subject = $user['Sendmail']['subject'];
                        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                        $this->Email->from = $site_title . "<" . $mail_from . ">";
                        $this->Email->template = 'send_newsletter';
                        $this->Email->layout = 'newsletter';
                        $this->Email->sendAs = 'html';
                        $this->set('message', $user['Sendmail']['body']);
                        $this->set('id', $user['Sendmail']['id']);
                        $this->set('email', $user['Sendmail']['email']);
                        $this->Email->send();

                        //$time = time();
                        $time = strtotime(date('Y-m-d H:i:s'));
                        $this->Sendmail->updateAll(array('Sendmail.is_mail_sent' => '1', 'Sendmail.sent_on' => "'$time'"), array('Sendmail.id' => $user['Sendmail']['id']));
                    }
                }
            }
        }

        $this->autoRender = false;
        exit;
    }

    public function getallsalary($tyep = null) {

        $allJobs = $this->Job->find('all', array('limit' => '2000,1000'));
        //pr($allJobs);
        foreach ($allJobs as $allJobsVal) {
            echo 'minsal:' . $allJobsVal['Job']['min_salary'];
            echo 'maxsal:' . $allJobsVal['Job']['max_salary'] . '<br>';
            $min = 0;
            $maX = 1;
            if ($allJobsVal['Job']['min_salary'] == '30' && $allJobsVal['Job']['max_salary'] == '100') {
                $min = 30;
                $maX = 50;
            } else if ($allJobsVal['Job']['min_salary'] == '70' && $allJobsVal['Job']['max_salary'] == '80' || $allJobsVal['Job']['min_salary'] == '60' && $allJobsVal['Job']['max_salary'] == '70') {
                $min = 0;
                $maX = 1;
            } else if ($allJobsVal['Job']['min_salary'] == '60' && $allJobsVal['Job']['max_salary'] == '100') {
                $min = 4;
                $maX = 5;
            } else if ($allJobsVal['Job']['min_salary'] == '50' && $allJobsVal['Job']['max_salary'] == '60') {
                $min = 0;
                $maX = 1;
            } else if ($allJobsVal['Job']['min_salary'] == '150' && $allJobsVal['Job']['max_salary'] == '175') {
                $min = 5;
                $maX = 7;
            } else if ($allJobsVal['Job']['min_salary'] == '200' && $allJobsVal['Job']['max_salary'] == '225') {
                $min = 7;
                $maX = 10;
            } else if ($allJobsVal['Job']['min_salary'] == '250' && $allJobsVal['Job']['max_salary'] == '275') {
                $min = 10;
                $maX = 12;
            } else if ($allJobsVal['Job']['min_salary'] == '375' && $allJobsVal['Job']['max_salary'] == '400') {
                $min = 15;
                $maX = 20;
            } else if ($allJobsVal['Job']['min_salary'] == '400' && $allJobsVal['Job']['max_salary'] == '425') {
                $min = 20;
                $maX = 25;
            } else if ($allJobsVal['Job']['min_salary'] == '425' && $allJobsVal['Job']['max_salary'] == '450') {
                $min = 20;
                $maX = 25;
            } else if ($allJobsVal['Job']['min_salary'] == '500' && $allJobsVal['Job']['max_salary'] == '600') {
                $min = 25;
                $maX = 30;
            } else {
                $min = 25;
                $maX = 30;
            }
            $this->request->data['Job']['id'] = $allJobsVal['Job']['id'];
            $this->request->data['Job']['min_salary'] = $min;
            $this->request->data['Job']['max_salary'] = $maX;

            $id = $allJobsVal['Job']['id'];
            if ($tyep == 1) {

                $cnd = array("Job.id = $id");
                $this->Job->updateAll(array('Job.min_salary' => $min, 'Job.max_salary' => $maX), $cnd);
                // $this->Job->create();
            } else {
//                pr($this->data);
            }
        }
        exit;
    }

    public function viewEmail() {
        $this->layout = '';

        Configure::write('debug', 2);

        $site_title = $this->getSiteConstant('title');
        $mail_from = $this->getMailConstant('from');
        $users = $this->Sendmail->find('all', array('conditions' => array('Sendmail.is_mail_sent' => 0), 'order' => array('Sendmail.id ASC'), 'limit' => 500));
        $deteel = array();
        if ($users) {
            foreach ($users as $user) {

                $email = trim($user['Sendmail']['email']);

                if ($this->User->checkEmail($email) == true) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                        echo "<li>" . $email . '</li>';
                    } else {

                        $userDetail = $this->User->find('first', array('conditions' => array('User.email_address' => $email)));
                        //print_r($userDetail); exit;
                        $this->User->updateAll(array("User.delete_valid" => "1"), array('User.id' => $userDetail['User']['id']));
                        $this->Sendmail->updateAll(array('Sendmail.delete_valid' => '1'), array('Sendmail.id' => $user['Sendmail']['id']));
                    }
                } else {

                    $userDetail = $this->User->find('first', array('conditions' => array('User.email_address' => "'$email'")));
                    //print_r($userDetail); exit;
                    $this->User->updateAll(array("User.delete_valid" => "1"), array('User.id' => $userDetail['User']['id']));
                    $this->Sendmail->updateAll(array('Sendmail.delete_valid' => '1'), array('Sendmail.id' => $user['Sendmail']['id']));
                }
            }
        }

        $this->autoRender = false;
        exit;
    }

    public function importresume() {

        ini_set('memory_limit', '512M');
        set_time_limit(0);
        $resume = $this->Resume->find('all', array('conditions' => array('Resume.careerdice_resumes_uploads <>' => "", 'Resume.import' => "0"), 'limit' => 50));
        if ($resume) {
            global $extentions;
            global $extentions_doc;
            foreach ($resume as $resumeVal) {

                $userexist = $this->JobSeeker->find('first', array('conditions' => array('JobSeeker.cjs_id' => $resumeVal['Resume']['careerdice_resume_job_seeker_id'])));
                if ($userexist) {
                    //pr($userexist); exit;
                    $filepath = BASE_PATH . '/app/webroot/files/resumes/' . $resumeVal['Resume']['careerdice_resumes_uploads'];
                    // echo $userexist['JobSeeker']['cjs_email'];
                    $jobsiteuser = $this->User->find('first', array('conditions' => array('User.email_address' => $userexist['JobSeeker']['cjs_email'])));
                    // echo $filepath.'<br>';
                    if ($jobsiteuser) {
                        if (file_exists($filepath)) {
                            //  pr($jobsiteuser); exit;
                            echo $filepath . '<br>';
                            copy(BASE_PATH . '/app/webroot/files/resumes/' . $resumeVal['Resume']['careerdice_resumes_uploads'], UPLOAD_CERTIFICATE_PATH . $resumeVal['Resume']['careerdice_resumes_uploads']);
                            $this->Certificate->create();
                            $this->request->data['Certificate']['document'] = $resumeVal['Resume']['careerdice_resumes_uploads'];
                            $this->request->data['Certificate']['user_id'] = $jobsiteuser['User']['id'];
                            $getextention = $this->PImage->getExtension($resumeVal['Resume']['careerdice_resumes_uploads']);
                            $extention = strtolower($getextention);
                            if (in_array($extention, $extentions)) {
                                $this->request->data['Certificate']['type'] = 'image';
                                $this->request->data['Certificate']['slug'] = 'image-' . $jobsiteuser['User']['id'] . time() . rand(111, 99999);
                            } elseif (in_array($extention, $extentions_doc)) {
                                $this->request->data['Certificate']['type'] = 'doc';
                                $this->request->data['Certificate']['slug'] = 'doc-' . $jobsiteuser['User']['id'] . time() . rand(111, 99999);
                            }
                            //  pr($this->data); exit;
                            $this->Certificate->save($this->data);
                            $this->Resume->updateAll(array('Resume.import' => '1'), array('Resume.careerdice_resume_id' => $resumeVal['Resume']['careerdice_resume_id']));
                        }
                    }
                }
            }
        }
        exit;
    }

    public function changeslug() {
        ini_set('memory_limit', '512M');
        set_time_limit(0);
        $users = $this->User->find('all', array('limit' => "15060,1724"));
        if ($users) {
            foreach ($users as $usersVal) {
                $slug = $usersVal['User']['slug'];
                echo $usersVal['User']['id'] . '<br>';
                $ifPresend = $this->User->find('all', array('conditions' => array('User.slug' => $slug, 'User.id <> ' => $usersVal['User']['id'])));
                if ($ifPresend) {
                    foreach ($ifPresend as $ifPresendVal) {
                        echo "<br>Changed begin " . $ifPresendVal['User']['slug'] . " to <br>";
                        $lastslug = $ifPresendVal['User']['slug'];
                        $newlug = $lastslug . '-' . $ifPresendVal['User']['id'];
                        $this->User->updateAll(array('User.slug' => "'$newlug'"), array('User.id' => $ifPresendVal['User']['id']));
                        echo "Changed to - " . $newlug;
                    }
                }
            }
        }
        exit;
    }

    public function changeslugjob() {
        ini_set('memory_limit', '512M');
        set_time_limit(0);
        $users = $this->Job->find('all', array('limit' => "3000,47"));
        if ($users) {
            foreach ($users as $usersVal) {
                $slug = $usersVal['Job']['slug'];
                echo $usersVal['Job']['id'] . '<br>';
                $ifPresend = $this->Job->find('all', array('conditions' => array('Job.slug' => $slug, 'Job.id <> ' => $usersVal['Job']['id'])));
                if ($ifPresend) {
                    foreach ($ifPresend as $ifPresendVal) {
                        echo "<br>Changed begin " . $ifPresendVal['Job']['slug'] . " to <br>";
                        $lastslug = $ifPresendVal['Job']['slug'];
                        $newlug = $lastslug . '-' . $ifPresendVal['Job']['id'];
                        $this->Job->updateAll(array('Job.slug' => "'$newlug'"), array('Job.id' => $ifPresendVal['Job']['id']));
                        echo "Changed to - " . $newlug;
                    }
                }
            }
        }
        exit;
    }

    public function refreshdata() {
        $this->layout = "";

        ini_set('memory_limit', '512M');
        set_time_limit(0);

        //ENTER THE RELEVANT INFO BELOW
        $mysqlDatabaseName = 'jobsitescript';
        $mysqlUserName = 'jobsite_user';
        $mysqlPassword = '6%Gh3o]V*ryi';
        $mysqlHostName = 'localhost';
        $mysqlImportFilename = BASE_PATH . '/app/webroot/db/jobsitescript.sql';

        mysql_connect($mysqlHostName, $mysqlUserName, $mysqlPassword) or die('Error connecting to MySQL server: ' . mysql_error());
        // Select database
        mysql_select_db($mysqlDatabaseName) or die('Error selecting MySQL database: ' . mysql_error());

        $sql = "SHOW TABLES FROM $mysqlDatabaseName";
        if ($result = mysql_query($sql)) {
            /* add table name to array */
            while ($row = mysql_fetch_row($result)) {
                $found_tables[] = $row[0];
            }
        } else {
            die("Error, could not list tables. MySQL Error: " . mysql_error());
        }

        /* loop through and drop each table */
        foreach ($found_tables as $table_name) {
            $sql = "DROP TABLE $mysqlDatabaseName.$table_name";
            if ($result = mysql_query($sql)) {
                //echo "Success - table $table_name deleted.";
            }
        }

        //DONT EDIT BELOW THIS LINE
        //Export the database and output the status to the page
        $command = 'mysql -h' . $mysqlHostName . ' -u' . $mysqlUserName . ' -p' . $mysqlPassword . ' ' . $mysqlDatabaseName . ' < ' . $mysqlImportFilename;
        exec($command, $output = array(), $worked);
        //   print_r($output);
        switch ($worked) {
            case 0:
                echo '<br>Successfully imported to database <b>' . $mysqlDatabaseName . '</b>';
                break;
            case 1:
                echo 'There was an error during import. Please make sure the import file is saved in the same folder as this script and check your values:<br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' . $mysqlDatabaseName . '</b></td></tr><tr><td>MySQL User Name:</td><td><b>' . $mysqlUserName . '</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' . $mysqlHostName . '</b></td></tr><tr><td>MySQL Import Filename:</td><td><b>' . $mysqlImportFilename . '</b></td></tr></table>';
                break;
        }
        exit;
    }

    public function sendLatestJobs() {
        $users = $this->User->find('all', array('conditions' => array('User.user_type' => 'candidate', 'User.interest_categories <>' => '')));

        if ($users) {
            foreach ($users as $user) {
                $email_string = '';
                $username = $user['User']['first_name'] . ' ' . $user['User']['last_name'];
                $interest_categories = $user['User']['interest_categories'];
                $email_address = $user['User']['email_address'];
                $condition[] = " (Job.category_id IN ($interest_categories ) )";
                $Job_array = $this->Job->find('all', array('conditions' => $condition, 'order' => array('Job.created' => 'desc'), 'limit' => 20));
                if ($Job_array) {
                    foreach ($Job_array as $Jobs) {
                        $title = $Jobs['Job']['title'];
                        $category = $Jobs['Category']['name'];
                        $skill = $Jobs['Skill']['name'];
                        $location = $Jobs['Job']['job_city'] ? $Jobs['Job']['job_city'] : '-';
                        $minExp = $Jobs['Job']['min_exp'] . ' Year';
                        $maxExp = $Jobs['Job']['max_exp'] . ' Year';
                        $min_salary = CURRENCY . ' ' . intval($Jobs['Job']['min_salary']) . 'Lac';
                        $max_salary = CURRENCY . ' ' . intval($Jobs['Job']['max_salary']) . 'Lac';
                        $description = $Jobs['Job']['description'];
                        $company_name = $Jobs['Job']['company_name'];
                        $contact_number = $Jobs['Job']['contact_number'];
                        $website = $Jobs['Job']['url'] ? $Jobs['Job']['url'] : 'N/A';
                        $address = $Jobs['Job']['address'] ? $Jobs['Job']['address'] : 'N/A';

                        $designation = $this->Skill->field('name', array(
                            'Skill.status' => 1,
                            'Skill.type' => 'Designation',
                            'Skill.id' => $Jobs['Job']['designation'],
                        ));
                        $cslug = $Jobs['Category']['slug'];
                        $jobslug = $Jobs['Job']['slug'];
                        $title1 = '<a href="' . HTTP_PATH . '/' . $cslug . '/' . $jobslug . '.html">' . $title . '</a>';
                        $email_string.= '      
<tr>
<td style="color:#434343; font-size:13px; line-height:18px;"> ' . $title1 . '</td>
<td style="color:#434343; font-size:13px; line-height:18px;">' . $location . '</td>
<td style="color:#434343; font-size:13px; line-height:18px;"> ' . $min_salary . ' - ' . $max_salary . '</td>
        </tr>';
                    }
                }
                $site_title = $this->getSiteConstant('title');
                $mail_from = $this->getMailConstant('from');

                $this->Email->to = $email_address;
                $currentYear = date('Y', time());
                $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

                $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='43'"));

                $toSubArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]');
                $fromSubArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title);
                $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate']['subject']);
                $this->Email->subject = $subjectToSend;

                $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
                $this->Email->from = $site_title . "<" . $mail_from . ">";
                $toRepArray = array('[!username!]', '[!Job_TITLE!]', '[!category!]', '[!location!]', '[!skill!]', '[!designation!]', '[!min_experience!]', '[!max_experience!]', '[!min_salary!]', '[!max_salary!]', '[!description!]', '[!company_name!]', '[!contact_number!]', '[!website!]', '[!address!]', '[!SITE_TITLE!]', '[!email_string!]');
                $fromRepArray = array($username, $title, $category, $location, $skill, $designation, $minExp, $maxExp, $min_salary, $max_salary, $description, $company_name, $contact_number, $website, $address, $site_title, $email_string);
                $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate']['template']);
                $this->Email->layout = 'default';
                $this->set('messageToSend', $messageToSend);
                $this->Email->template = 'email_template';
                $this->Email->sendAs = 'html';
                $this->Email->send();
                $this->Email->reset();
            }
        }
        exit;
    }

    public function sendtestemail() {
          Configure::write('debug', 2);
        $userId = 41;
           $site_title = $this->getSiteConstant('title');
                $mail_from = $this->getMailConstant('from');
        //last inserted user info
        $userCheck = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
        $email = $userCheck["User"]["email_address"];
        $username = $userCheck["User"]["first_name"];
        $link = HTTP_PATH . "/users/resetPassword/" . $userCheck['User']['id'] . "/" . md5($userCheck['User']['id']) . "/" . urlencode($email);

        $this->Email->to = $email;
        $currentYear = date('Y', time());
        $emailtemplateMessage = $this->Emailtemplate->find("first", array("conditions" => "Emailtemplate.id='4'"));
        $sitelink = '<a style="color:#000; text-decoration: underline;" href="mailto:' . $mail_from . '">' . $mail_from . '</a>';

        $emData = $this->Emailtemplate->getSubjectLang();
        $subjectField = $emData['subject'];
        $templateField = $emData['template'];

        $toSubArray = array('[!username!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!activelink!]');
        $fromSubArray = array($username, HTTP_PATH, $site_title, $link);
        $subjectToSend = str_replace($toSubArray, $fromSubArray, $emailtemplateMessage['Emailtemplate'][$subjectField]);
        $this->Email->subject = $subjectToSend;

        $this->Email->replyTo = $site_title . "<" . $mail_from . ">";
        $this->Email->from = $site_title . "<" . $mail_from . ">";

        $toRepArray = array('[!username!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]', '[!activelink!]');
        $fromRepArray = array($username, HTTP_PATH, $site_title, $link);
        $messageToSend = str_replace($toRepArray, $fromRepArray, $emailtemplateMessage['Emailtemplate'][$templateField]);
        $this->Email->layout = 'default';
        $this->set('messageToSend', $messageToSend);
//        echo '<pre>';print_r($this->Email);die;
        $this->Email->template = 'email_template';
        $this->Email->sendAs = 'html';
//        $this->Email->send();
        echo '<pre>';print_r($this->Email->send());
        exit;
    }
     public function autoxmlimport() {
	ini_set("memory_limit", "-1");
        ini_set('max_execution_time', '10000');

        $this->layout = "";
        $site_title = $this->getSiteConstant('title');
        $mail_from = $this->getMailConstant('from');
         global $worktype;
        global $experienceArray;
        global $sallery;
		
		   
//         $jobcondition = array();
//        $jobcondition [] = "CURRENT_DATE > DATE_ADD(Job.created, INTERVAL 6 DAY)";
//         $this->Job->deleteAll($jobcondition);
		  
        $catekeywords = $this->Category->find('list', array('conditions' => array('Category.status' => 1, 'Category.parent_id' => 0, 'Category.keywords !=' => ''), 'fields' => array('Category.keywords'), 'order' => 'Category.keywords ASC'));
        //  $this->Alert->updateAll(array("Alert.modified" => "'" . date('Y-m-d H:i:s', strtotime('-7 days')) . "'"));
        $conditions = array('Feed.status' => 1);
        $conditions [] = "MOD(DATEDIFF(CURRENT_DATE,created), `expire`) = 0";
//        $conditions [] = "CURRENT_DATE > created and CURRENT_DATE <= DATE_ADD(created, INTERVAL expire DAY)";
//        $conditions [] = "CURRENT_DATE > created and CURRENT_DATE <= DATE_ADD(created, INTERVAL expire DAY)";

        $feeds = $this->Feed->find('all', array(
            'conditions' => array(
                $conditions
            ), 'order' => 'Feed.id desc'));

//        echo '<pre>';        print_r($feeds);die;
        if (!empty($feeds)) {
            foreach ($feeds as $feed) {
                $findfeedid = $feed['Feed']['id'];
                $file = $feed['Feed']['feed_url'];
                $feedname = $feed['Feed']['name'];
                $feed_url = $feed['Feed']['feed_url'];
                $feeduser_id = $feed['Feed']['user_id'];
                $feedapplyurl = $feed['Feed']['applyurl'];
                $feedcompany_name = $feed['Feed']['company_name'];
                $feedaddress = $feed['Feed']['address'];
                $feedlogo = $feed['Feed']['logo'];
                $feedbrief_abtcomp = $feed['Feed']['brief_abtcomp'];
                $feedjob_city = $feed['Feed']['job_city'];
                $feedurl = $feed['Feed']['url'];
                $feedtitle = $feed['Feed']['title'];
                $feedjob_id = $feed['Feed']['job_id'];
                $feedcategory_id = $feed['Feed']['category_id'];
                $feedsalary = $feed['Feed']['salary'];
                $feeddescription = $feed['Feed']['description'];
                $feedwork_type = $feed['Feed']['work_type'];
                $feedskills = $feed['Feed']['skills'];
                $feeddesignation = $feed['Feed']['designation'];
                $feedexpirydate = $feed['Feed']['expirydate'];
                 $feedposteddate = $feed['Feed']['posteddate'];

//                $findfeedid = 0;
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
//                    if ($k == 100) {
//                        break;
//                    }
                }
                
//                echo '<pre>'; print_r($resourceList);die;


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
//                        echo $expire_time;die;


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
//                        echo $category_id ;die;
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
                        } else {
                            $userId = 0;
                            // break;
                        }
//                        echo $category_id;die;

                        if (!$category_id)
                            continue;

                        if ($work_type == 'fulltime' || $work_type == 'parttime') {
                           if($work_type == 'fulltime'){
                             $work_type_id =  1;
                           } else {
                              $work_type_id =  6;  
                           }
                             
                        } else if ($work_type) {
                            $work_type_id = array_search(strtolower($work_type), array_map('strtolower', $worktype));
                        } else {
                          //  $work_type_id = 1; // default assigned value
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
                            //$sallery_array = array_keys($sallery);
                           // list($minsal, $maxsal) = explode('-', $sallery_array[0]); // default assigned value
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
                           // $exper_array = array_keys($experienceArray);
                          //  list($minexp, $maxexp) = explode('-', $exper_array[0]); // default assigned value
                        }

                        $findskillids = array();
                        $skillarr = explode(',', $skill);
                        if ($skillarr) {
                            foreach ($skillarr as $skillval) {
                                $condition = array('Skill.type' => 'Skill');
                                $condition[] = " (Skill.name like '%" . addslashes($skillval) . "%')  ";
                                $findskillid = $this->Skill->field('id', $condition);
                                if (!$findskillid) {
                                    $skillDetail = $this->Skill->find('first', array('conditions' => array('Skill.type' => 'Skill')));
                                    $findskillid = $skillDetail['Skill']['id'];
//                                    $this->request->data = array();
//                                    $this->Skill->create();
//                                    $this->request->data['Skill']['name'] = $skill;
//                                    $this->request->data['Skill']['slug'] = $this->stringToSlugUnique($skillval, 'Skill', 'slug');
//                                    $this->request->data['Skill']['status'] = '1';
//                                    $this->request->data['Skill']['type'] = 'Skill';
//                                    $this->Skill->save($this->data);
//                                    $findskillid = $this->Skill->id;
                                }
                                if ($findskillid)
                                    $findskillids [] = $findskillid;
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




                        $condition = array('Skill.type' => 'Designation');
                        $condition[] = " (Skill.name like '%" . addslashes($designation) . "%')  ";
                        $finddesid = $this->Skill->field('id', $condition);
                         if (!$finddesid) {
                            $skillDetail = $this->Skill->find('first', array('conditions' => array('Skill.type' => 'Designation')));
                            $finddesid = $skillDetail['Skill']['id'];
//                            $this->request->data = array();
//                            $this->Skill->create();
//                            $this->request->data['Skill']['name'] = $designation;
//                            $this->request->data['Skill']['slug'] = $this->stringToSlugUnique($designation, 'Skill', 'slug');
//                            $this->request->data['Skill']['status'] = '1';
//                            $this->request->data['Skill']['type'] = 'Designation';
//                            $this->Skill->save($this->data);
//                            $finddesid = $this->Skill->id;
                        }

                        $recruiterInfo = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
                        $companyname = $recruiterInfo ? $recruiterInfo['User']['company_name'] : $company_name;
//                        $company_contact = $recruiterInfo?$recruiterInfo['User']['company_contact']:$company_name;
                        $company_contact = $recruiterInfo ? $recruiterInfo['User']['email_address'] : '';
//                        $brief_abtcomp = $recruiterInfo['User']['brief_abtcomp'];
                        $company_contact = $recruiterInfo ? $recruiterInfo['User']['company_contact'] : '';
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
                        $slug = $this->stringToSlugUnique($title, 'Job');
                        $this->request->data['Job']['slug'] = $slug;
                        $this->request->data['Job']['type'] = 'Gold';$this->request->data['Job']['payment_status'] = 2;
                        $this->request->data['Job']['amount_paid'] = 180;
                        $this->request->data['Job']['job_number'] = 'JOB' . $userId . time();
                        $this->request->data['Job']['hot_job_time'] = time() + 7 * 24 * 3600;
                        
                        
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

                        
                        
                        $this->request->data['Job']['status'] = 1;
                        $this->request->data['Job']['user_id'] = $userId;
                        $this->request->data['Job']['expire_time'] = strtotime($expire_time);

                        $this->request->data['Job']['skill'] = implode(',', $findskillids);
                        if($posteddate && $posteddate>0){
                          $this->request->data['Job']['created'] = $posteddate;  
                        }
//                        pr($this->data);
                        $this->Job->save($this->data);
                    }
                }
                exit;
            }
        } else {
            echo "No feeds to record";
        }
        exit;
    }

}

?>