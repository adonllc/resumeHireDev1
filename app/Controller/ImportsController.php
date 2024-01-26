<?php

class ImportsController extends AppController {

    public $uses = array('Admin', 'User', 'JobNotification', 'JobApply', 'Job', 'Category', 'Country', 'AdminLog', 'Education', 'Experience', 'Location', 'Skill', 'UpdateJob','Skillold');
    public $helpers = array('Html', 'Form', 'Fck', 'Paginator', 'Javascript', 'Ajax', 'Text');
    public $paginate = array('limit' => '10', 'page' => '1', 'order' => array('Booking.id' => 'DESC'));
    public $components = array('RequestHandler', 'Email', 'Upload', 'PImageTest', 'PImage', 'Common');
    public $layout = 'admin';

    public function category() {

        echo"please remove the exit; from function.";
        exit;

        $this->AdminLog->setDatabase('careerdicein', 'test');
        $categories = $this->AdminLog->query('SELECT * FROM careerdice_industry_types');
        //pr($categories); exit;
        if ($categories) {
            foreach ($categories as $category) {

                $this->request->data['Category']['cat_id'] = $category['careerdice_industry_types']['careerdice_industry_type_id'];

                $this->request->data['Category']['name'] = $category['careerdice_industry_types']['careerdice_industry_type'];
                if (empty($this->data["Category"]["name"])) {
                    $msgString .="- Category Name is required field.<br>";
                } elseif ($this->Category->isRecordUniqueCategory($this->data["Category"]["name"], 0) == false) {
                    $msgString .="- Category Name already exists.<br>";
                }



                $this->request->data['Category']['meta_title'] = $category['careerdice_industry_types']['careerdice_industry_type_meta_title'];
                $this->request->data['Category']['meta_description'] = $category['careerdice_industry_types']['careerdice_industry_type_meta_description'];
                $this->request->data['Category']['meta_keywords'] = $category['careerdice_industry_types']['careerdice_industry_meta_keywords'];
                $this->request->data['Category']['meta_keyphrase'] = $category['careerdice_industry_types']['careerdice_industry_meta_keyphrase'];
                $this->request->data['Category']['page_header'] = $category['careerdice_industry_types']['careerdice_industry_type_page_header'];
                $this->request->data['Category']['slug'] = $this->stringToSlugUnique($this->data["Category"]["name"], 'Category');
                $this->request->data['Category']['parent_id'] = '0';
                $this->request->data['Category']['category_image'] = '';
                $this->request->data['Category']['status'] = '1';
                $this->request->data['Category']['id'] = '';
                // pr($category); 
                $this->Category->save($this->data['Category']);
            }
        }
        exit;
    }

    public function country() {
        echo"please remove the exit; from function.";
        exit;

        $this->AdminLog->setDatabase('careerdicein', 'test');
        $countries = $this->AdminLog->query('SELECT * FROM countries');
        // pr($countries); exit;
        if ($countries) {
            foreach ($countries as $country) {

                $this->request->data['Country']['country_name'] = $country['countries']['country_name'];
                if (empty($this->data["Country"]["country_name"])) {
                    $msgString .="- Country Name is required field.<br>";
                } elseif ($this->Country->isRecordUniqueCountry($this->data["Country"]["country_name"], 0) == false) {
                    $msgString .="- Country Name already exists.<br>";
                }

                $this->request->data['Country']['slug'] = $this->stringToSlugUnique($this->data["Country"]["country_name"], 'Country');
                $this->request->data['Country']['status'] = '1';
                $this->request->data['Country']['id'] = '';
                $this->Country->save($this->data['Country']);
            }
        }
        exit;
    }

    public function jobseeker() {
        echo"please remove the exit; from function.";
        exit;

        $this->AdminLog->setDatabase('careerdicein', 'test');
        $jobseekers = $this->AdminLog->query('SELECT * FROM careerdice_job_seekers');
        // pr($countries); exit;
        if ($jobseekers) {
            foreach ($jobseekers as $jobseeker) {

                $this->request->data['User']['user_type'] = 'candidate';

                //$this->request->data['User']['username'] = $jobseeker['careerdice_job_seekers']['cjs_username'];

                $this->request->data['User']['company_name'] = '';
                $this->request->data['User']['abn'] = '';
                $this->request->data['User']['position'] = '';
                $this->request->data['User']['first_name'] = $jobseeker['careerdice_job_seekers']['cjs_first_name'];
                $this->request->data['User']['last_name'] = $jobseeker['careerdice_job_seekers']['cjs_last_name'];
                $this->request->data['User']['father_name'] = $jobseeker['careerdice_job_seekers']['cjs_father_name'];
                $this->request->data['User']['gender'] = '';
                $this->request->data['User']['email_address'] = $jobseeker['careerdice_job_seekers']['cjs_email'];
                if (empty($this->data["User"]["email_address"])) {
                    $msgString .="- User email is required field.<br>";
                } elseif ($this->User->isRecordUniqueemail($this->data["User"]["email_address"]) == false) {
                    $msgString .="- Email already exists.<br>";
                }

                $this->request->data['User']['dob'] = $jobseeker['careerdice_job_seekers']['cjs_dob'];
                $passwordPlain = $jobseeker['careerdice_job_seekers']['cjs_password'];
                $salt = uniqid(mt_rand(), true);
                $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
                $this->request->data['User']['password'] = $new_password;
                $this->request->data['User']['profile_image'] = '';
                $this->request->data['User']['contact'] = $jobseeker['careerdice_job_seekers']['cjs_phone'];
                $this->request->data['User']['location'] = 0;
                $this->request->data['User']['city_id'] = 0;
                $this->request->data['User']['state_id'] = 0;
                $this->request->data['User']['country_id'] = 0;
                $this->request->data['User']['address'] = $jobseeker['careerdice_job_seekers']['cjs_home_address'];
                $this->request->data['User']['postal_code'] = $jobseeker['careerdice_job_seekers']['cjs_zip'];
                $this->request->data['User']['company_contact'] = '';
                $this->request->data['User']['industry'] = $jobseeker['careerdice_job_seekers']['cjs_industry'];
                $this->request->data['User']['url'] = '';
                $this->request->data['User']['forget_password_status'] = 0;
                $this->request->data['User']['slug'] = $this->stringToSlugUnique($this->data['User']['first_name'] . ' ' . $this->data['User']['last_name'], 'User', 'slug');
                $this->request->data['User']['status'] = '1';
                $this->request->data['User']['profile_update_status'] = 0;
                $this->request->data['User']['created'] = $jobseeker['careerdice_job_seekers']['cjs_signup_date'];
                $this->request->data['User']['modified'] = '';
                $this->request->data['User']['activation_status'] = 1;
                $this->request->data['User']['description'] = '';
                $this->request->data['User']['exp_year'] = '';
                $this->request->data['User']['exp_month'] = '';
                $this->request->data['User']['skills'] = '';
                $this->request->data['User']['cover_letter'] = '';
                $this->request->data['User']['email_notification_id'] = '';
                $this->request->data['User']['cv'] = '';
                $this->request->data['User']['device_id'] = '';
                $this->request->data['User']['device_type'] = '';
                $this->request->data['User']['contact_person'] = '';
                $this->request->data['User']['contact_person_email'] = '';
                $this->request->data['User']['employer_type'] = '';
                $this->request->data['User']['expiry_date'] = 0000 - 00 - 00;
                $this->request->data['User']['jobseeker_id'] = $jobseeker['careerdice_job_seekers']['cjs_id'];
                $this->request->data['User']['employer_id'] = 0;
                $this->request->data['User']['id'] = '';
                //$this->User->save($this->data['User']);

                if ($this->User->save($this->data['User'])) {
                    $getLastUserId = $this->User->id;
                    // $getUserId =array();
                    // $getUserId = $this->User->query('SELECT id FROM tbl_users as User where jobseeker_id =' . $jobseeker['careerdice_job_seekers']['cjs_id']);
                    // pr($getLastUserId); exit;


                    $qualifications = $this->AdminLog->query('SELECT * FROM careerdice_resume_qualifications where crq_job_seeker_id =' . $jobseeker['careerdice_job_seekers']['cjs_id']);
                    if ($qualifications) {
                        foreach ($qualifications as $qualification) {

                            $this->request->data['Education']['user_id'] = $getLastUserId;
                            $this->request->data['Education']['user_type'] = 'candidate';
                            $this->request->data['Education']['education_type'] = 'Basic';
                            $this->request->data['Education']['qualification_level'] = $qualification['careerdice_resume_qualifications']['crq_qualification_level'];
                            $this->request->data['Education']['qualification_name'] = $qualification['careerdice_resume_qualifications']['crq_qualification_name'];
                            $this->request->data['Education']['percentage'] = $qualification['careerdice_resume_qualifications']['crq_percentage'];
                            $this->request->data['Education']['basic_course_id'] = 0;
                            $this->request->data['Education']['basic_specialization_id'] = 0;
                            $this->request->data['Education']['basic_university'] = $qualification['careerdice_resume_qualifications']['crq_university'];
                            $this->request->data['Education']['basic_year'] = $qualification['careerdice_resume_qualifications']['crq_year_of_passing'];
                            $this->request->data['Education']['id'] = '';
                            $this->Education->create();
                            $this->Education->save($this->data['Education']);
                        }
                    }

                    $experiences = $this->AdminLog->query('SELECT * FROM careerdice_resume_experience where cre_job_seeker_id=' . $jobseeker['careerdice_job_seekers']['cjs_id']);
                    //pr($employers); exit;
                    if ($experiences) {
                        foreach ($experiences as $experience) {

                            $this->request->data['Experience']['user_id'] = $getLastUserId;
                            $this->request->data['Experience']['company_name'] = $experience['careerdice_resume_experience']['cre_company'];
                            $this->request->data['Experience']['industry'] = '0';
                            $this->request->data['Experience']['functional_area'] = '';
                            $this->request->data['Experience']['role'] = '';
                            $this->request->data['Experience']['designation'] = $experience['careerdice_resume_experience']['cre_job_description'];
                            $this->request->data['Experience']['ctclakhs'] = 0;
                            $this->request->data['Experience']['ctcthousand'] = 0;
                            $this->request->data['Experience']['from_month'] = '';
                            $this->request->data['Experience']['from_year'] = 0000;
                            $this->request->data['Experience']['to_month'] = '';
                            $this->request->data['Experience']['to_year'] = 0000;
                            $this->request->data['Experience']['job_profile'] = $experience['careerdice_resume_experience']['cre_designation'];
                            $this->request->data['Experience']['status'] = '1';
                            $this->request->data['Experience']['id'] = '';
                            $this->Experience->create();
                            $this->Experience->save($this->data['Experience']);
                        }
                    }
                }
            }
        }
        exit;
    }

    public function employer() {

        echo"please remove the exit; from function.";
        exit;

        $this->AdminLog->setDatabase('careerdicein', 'test');
        $employers = $this->AdminLog->query('SELECT * FROM careerdice_employers');
        //pr($employers); exit;
        if ($employers) {

            // echo $minexp;
            // echo '<br>';

            foreach ($employers as $employer) {

                $this->request->data['User']['user_type'] = 'recruiter';

                $this->request->data['User']['company_name'] = $employer['careerdice_employers']['careerdice_employer_company'];
                $this->request->data['User']['abn'] = '';
                $this->request->data['User']['position'] = $employer['careerdice_employers']['careerdice_employer_designation'];
                $this->request->data['User']['first_name'] = $employer['careerdice_employers']['careerdice_employer_name'];
                $this->request->data['User']['last_name'] = '';
                $this->request->data['User']['father_name'] = '';
                $this->request->data['User']['gender'] = '';
                $this->request->data['User']['email_address'] = $employer['careerdice_employers']['careerdice_employer_email'];
                if (empty($this->data["User"]["email_address"])) {
                    $msgString .="- User email is required field.<br>";
                } elseif ($this->User->isRecordUniqueemail($this->data["User"]["email_address"]) == false) {
                    $msgString .="- Email already exists.<br>";
                }

                $this->request->data['User']['dob'] = '0000-00-00';
                $passwordPlain = $employer['careerdice_employers']['careerdice_employer_password'];
                $salt = uniqid(mt_rand(), true);
                $new_password = crypt($passwordPlain, '$2a$07$' . $salt . '$');
                $this->request->data['User']['password'] = $new_password;
                $this->request->data['User']['profile_image'] = $employer['careerdice_employers']['careerdice_employers_logo'];
                $this->request->data['User']['contact'] = $employer['careerdice_employers']['careerdice_employer_phone'];
                $this->request->data['User']['location'] = 0;
                $this->request->data['User']['city_id'] = 0;
                $this->request->data['User']['state_id'] = 0;
                $this->request->data['User']['country_id'] = 0;
                $this->request->data['User']['address'] = $employer['careerdice_employers']['careerdice_employer_address'];
                $this->request->data['User']['postal_code'] = $employer['careerdice_employers']['careerdice_employer_zip'];
                $this->request->data['User']['company_contact'] = $employer['careerdice_employers']['careerdice_employer_fax'];
                $this->request->data['User']['industry'] = $employer['careerdice_employers']['careerdice_employer_industry_id'];
                $this->request->data['User']['url'] = $employer['careerdice_employers']['careerdice_employer_url'];
                $this->request->data['User']['forget_password_status'] = 0;
                $this->request->data['User']['slug'] = $this->stringToSlugUnique($this->data['User']['first_name'] . ' ' . $this->data['User']['last_name'], 'User', 'slug');
                $this->request->data['User']['status'] = $employer['careerdice_employers']['careerdice_employer_status'];
                $this->request->data['User']['profile_update_status'] = 0;
                $this->request->data['User']['created'] = $employer['careerdice_employers']['careerdice_employer_signup_date'];
                $this->request->data['User']['modified'] = '';
                $this->request->data['User']['activation_status'] = $employer['careerdice_employers']['careerdice_activation_status'];
                $this->request->data['User']['description'] = '';
                $this->request->data['User']['exp_year'] = '';
                $this->request->data['User']['exp_month'] = '';
                $this->request->data['User']['skills'] = '';
                $this->request->data['User']['cover_letter'] = '';
                $this->request->data['User']['email_notification_id'] = '';
                $this->request->data['User']['cv'] = '';
                $this->request->data['User']['device_id'] = '';
                $this->request->data['User']['device_type'] = '';
                $this->request->data['User']['contact_person'] = $employer['careerdice_employers']['careerdice_employer_contact_person'];
                $this->request->data['User']['contact_person_email'] = $employer['careerdice_employers']['careerdice_employer_contact_person_email'];
                $this->request->data['User']['employer_type'] = $employer['careerdice_employers']['careerdice_employer_type'];
                $this->request->data['User']['expiry_date'] = $employer['careerdice_employers']['careerdice_employer_expiry_date'];
                $this->request->data['User']['jobseeker_id'] = 0;
                $this->request->data['User']['employer_id'] = $employer['careerdice_employers']['careerdice_employer_id'];
                $this->request->data['User']['id'] = '';
                $this->User->create();
                $this->User->save($this->data['User']);
            }
        }
        exit;
    }

    public function jobLocation() {

        echo"please remove the exit; from function.";
        exit;

        $this->AdminLog->setDatabase('careerdicein', 'test');
        $locations = array();
        $locations = $this->AdminLog->query('SELECT `careerdice_job_location` FROM careerdice_jobs');

        foreach ($locations as $loc) {
            $rows[] = $loc['careerdice_jobs'];
        }
        foreach ($rows as $row) {
            $data[] = explode(',', $row['careerdice_job_location']);
        }

        foreach ($data as $k1 => $v1) {
            foreach ($v1 as $k2 => $v2) {
                $jobloc[] = $v2;
            }
        }
        $jobLocations = array_unique($jobloc);
        if ($jobLocations) {
            $locName = $this->Location->find('all');
            foreach ($locName as $name) {
                $LocationName[] = $name['Location']['name'];
                foreach ($jobLocations as $jobLocation) {

                    if (!in_array($jobLocation, $LocationName)) {
                        $this->request->data['Location']['name'] = $jobLocation;
                        $this->request->data['Location']['slug'] = $this->stringToSlugUnique($this->data["Location"]["name"], 'Location', 'slug');
                        $this->request->data['Location']['status'] = '1';
                        $this->request->data['Location']['id'] = '';
                        $this->Location->save($this->data['Location']);
                    }
                }
            }
        }
        exit;
    }

    public function designation() {
        echo"please remove the 'exit' from function.";
        exit;

        $this->AdminLog->setDatabase('careerdicein', 'test');
        $designations = $this->AdminLog->query('SELECT DISTINCT `careerdice_job_designation` FROM careerdice_jobs where careerdice_job_designation!=""');
        foreach ($designations as $designation) {
            $des[] = $designation['careerdice_jobs'];
        }
    }

    public function jobAdd() {
        echo"please remove the exit; from function.";
        exit;

        $this->AdminLog->setDatabase('careerdicein', 'test');
        // $jobDetails = $this->AdminLog->query('SELECT * FROM careerdice_jobs where `careerdice_job_post_date` BETWEEN DATE_SUB( "2016-05-05",INTERVAL 6 MONTH ) AND DATE_SUB( "2016-05-05" ,INTERVAL 0 MONTH )');
        $jobDetails = $this->AdminLog->query('SELECT * FROM careerdice_jobs');
        //pr($jobDetails); exit;
        if ($jobDetails) {

            $cdate = '2016-04-11';

            $loc = '0';

            $skill = '0';

            $des = '39';

            $minexp = '9';
            $maxexp = '10';

            foreach ($jobDetails as $detail) {

                if ($cdate == '2016-05-12') {
                    $cdate = '2016-04-12';
                } else {
                    $cdate = date('Y-m-d', strtotime($cdate) + 24 * 3600);
                }

                if ($loc == '100') {
                    $loc = '1';
                } else {
                    $loc = $loc + 1;
                }

                if ($skill == '39') {
                    $skill = '1';
                } else {
                    $skill = $skill + 1;
                }

                if ($des == '53') {
                    $des = '40';
                } else {
                    $des = $des + 1;
                }


                if ($minexp == '9') {
                    $minexp = '0';
                } else {
                    $minexp = $minexp + 1;
                }

                if ($maxexp == '10') {
                    $maxexp = '1';
                } else {
                    $maxexp = $maxexp + 1;
                }

                // echo $minexp;
                // echo '<br>';
                $userId = $this->User->field('id', array('User.employer_id' => $detail['careerdice_jobs']['careerdice_job_employer_id']));

                $catId = $this->Category->field('id', array('Category.cat_id' => $detail['careerdice_jobs']['careerdice_job_industry_id']));

                // pr($catId); exit;

                $this->request->data['Job']['job_number'] = 'JOB' . $userId . time();
                $this->request->data['Job']['job_id'] = $detail['careerdice_jobs']['careerdice_job_id'];
                if (!empty($userId)) {
                    $this->request->data['Job']['user_id'] = $userId;
                } else {
                    $this->request->data['Job']['user_id'] = 16634;
                }

                $this->request->data['Job']['admin_id'] = 0;
                $this->request->data['Job']['type'] = 'bronze';
                $this->request->data['Job']['title'] = $detail['careerdice_jobs']['careerdice_job_job_title'];
                $this->request->data['Job']['category_id'] = $catId;
                $this->request->data['Job']['subcategory_id'] = '';
                $this->request->data['Job']['skill'] = $skill;
                $this->request->data['Job']['designation'] = $des;
                $this->request->data['Job']['location'] = $loc;
                $this->request->data['Job']['price'] = 0;
                $this->request->data['Job']['price_status'] = 0;
                $this->request->data['Job']['role'] = 0;
                $this->request->data['Job']['description'] = $detail['careerdice_jobs']['careerdice_job_jobdescription'];
                $this->request->data['Job']['company_name'] = $detail['careerdice_jobs']['careerdice_job_company_name'];
                $this->request->data['Job']['work_type'] = 1;
                $this->request->data['Job']['contact_name'] = $detail['careerdice_jobs']['careerdice_job_contperson'];
                $this->request->data['Job']['contact_number'] = $detail['careerdice_jobs']['careerdice_job_phone'];
                if ($detail['careerdice_jobs']['careerdice_job_no_of_candidates'] != '') {
                    $this->request->data['Job']['vacancy'] = $detail['careerdice_jobs']['careerdice_job_no_of_candidates'];
                } else {
                    $this->request->data['Job']['vacancy'] = 1;
                }
                $this->request->data['Job']['address'] = $detail['careerdice_jobs']['careerdice_job_cont_address'];
                $this->request->data['Job']['state_id'] = 0;
                $this->request->data['Job']['city_id'] = 0;
                $this->request->data['Job']['postal_code'] = 0;
                $this->request->data['Job']['url'] = $detail['careerdice_jobs']['careerdice_job_url'];
                $this->request->data['Job']['youtube_link'] = 0;
                $this->request->data['Job']['lastdate'] = $detail['careerdice_jobs']['careerdice_job_last_date'];
                $this->request->data['Job']['slug'] = $this->stringToSlugUnique($detail['careerdice_jobs']['careerdice_job_job_title'], 'Job');
                $this->request->data['Job']['status'] = 1;
                $this->request->data['Job']['job_status'] = 0;
                $this->request->data['Job']['created'] = $cdate;
                $this->request->data['Job']['modified'] = $detail['careerdice_jobs']['careerdice_job_last_updated'];
                $this->request->data['Job']['payment_status'] = 2;
                $this->request->data['Job']['amount_paid'] = 90.00;
                $this->request->data['Job']['logo'] = $detail['careerdice_jobs']['careerdice_job_company_logo'];
                $this->request->data['Job']['expire_time'] = time() + 30 * 24 * 3600;
                $this->request->data['Job']['dis_amount'] = 0.00;
                $this->request->data['Job']['promo_code'] = '';
                $this->request->data['Job']['selling_point1'] = '';
                $this->request->data['Job']['selling_point2'] = '';
                $this->request->data['Job']['selling_point3'] = '';
                $this->request->data['Job']['hot_job_time'] = 0;
                $this->request->data['Job']['search_count'] = 0;
                $this->request->data['Job']['view_count'] = $detail['careerdice_jobs']['careerdice_job_viewed'];
                $this->request->data['Job']['job_logo_check'] = 0;
                $this->request->data['Job']['invoice_inumber'] = 'null';
                $this->request->data['Job']['payment_type'] = 2;
                $this->request->data['Job']['weekly_email_sent'] = 0;
                $this->request->data['Job']['exp_year'] = 0;
                $this->request->data['Job']['exp_month'] = 0;
                $this->request->data['Job']['min_exp'] = $minexp;
                $this->request->data['Job']['max_exp'] = $maxexp;
                $this->request->data['Job']['min_salary'] = 50;
                $this->request->data['Job']['max_salary'] = 60;
                $this->request->data['Job']['confidential'] = $detail['careerdice_jobs']['careerdice_job_confidential'];
                $this->request->data['Job']['job_position'] = $detail['careerdice_jobs']['careerdice_job_position_id'];
                $this->request->data['Job']['eligibility'] = $detail['careerdice_jobs']['careerdice_job_eligibility'];
                $this->request->data['Job']['job_specialization'] = $detail['careerdice_jobs']['careerdice_job_specialization'];
                $this->request->data['Job']['job_salary'] = $detail['careerdice_jobs']['careerdice_job_salary'];
                $this->request->data['Job']['job_experience_id'] = $detail['careerdice_jobs']['careerdice_job_experience_id'];
                $this->request->data['Job']['job_email'] = $detail['careerdice_jobs']['careerdice_job_email'];
                $this->request->data['Job']['job_fax'] = $detail['careerdice_jobs']['careerdice_job_fax'];
                $this->request->data['Job']['designation_ofperson'] = $detail['careerdice_jobs']['careerdice_job_designation_ofperson'];
                $this->request->data['Job']['brief_abtcomp'] = $detail['careerdice_jobs']['careerdice_job_brief_abtcomp'];
                $this->request->data['Job']['meta_tag_title'] = $detail['careerdice_jobs']['careerdice_job_meta_tag_title'];
                $this->request->data['Job']['meta_tag_keywords'] = $detail['careerdice_jobs']['careerdice_job_meta_tag_keywords'];
                $this->request->data['Job']['meta_tag_description'] = $detail['careerdice_jobs']['careerdice_job_meta_tag_description'];
                $this->request->data['Job']['meta_tag_keyphrase'] = $detail['careerdice_jobs']['careerdice_job_meta_tag_keyphrase'];
                $this->request->data['Job']['id'] = '';

                $this->Job->create();

                //exit;
                $this->Job->save($this->data['Job']);
            }
        }
        exit;
    }

    public function jobApllied() {
        echo"please remove the exit; from function.";
        exit;

        $this->AdminLog->setDatabase('careerdicein', 'test');
        $applyDetails = $this->AdminLog->query('SELECT * FROM careerdice_job_applied where `cja_job_applied_date` BETWEEN DATE_SUB( "2016-05-05",INTERVAL 6 MONTH ) AND DATE_SUB( "2016-05-05" ,INTERVAL 0 MONTH )');
        //pr($applyDetails); exit;
        if ($applyDetails) {

            foreach ($applyDetails as $detail) {

                $jobseekerId = $this->User->field('id', array('User.jobseeker_id' => $detail['careerdice_job_applied']['cja_job_seeker_id']));

                $employerId = $this->User->field('id', array('User.employer_id' => $detail['careerdice_job_applied']['cja_employer_id']));

                $jobId = $this->Job->field('id', array('Job.job_id' => $detail['careerdice_job_applied']['cja_job_id']));

                $this->request->data['JobApply']['user_id'] = $jobseekerId;
                $this->request->data['JobApply']['job_id'] = $jobId;
                $this->request->data['JobApply']['user_employer_id'] = $employerId;

                $this->request->data['JobApply']['cover_letter_id'] = 0;
                $this->request->data['JobApply']['attachment_ids'] = 0;
                $this->request->data['JobApply']['rating'] = 0;
                $this->request->data['JobApply']['apply_status'] = 'Active';
                $this->request->data['JobApply']['new_status'] = 1;
                $this->request->data['JobApply']['status'] = 1;
                $this->request->data['JobApply']['created'] = $detail['careerdice_job_applied']['cja_job_applied_date'];

                if ($jobseekerId != '' && $employerId != '' && $jobId != '') {
                    $this->JobApply->create();
                    $this->JobApply->save($this->data['JobApply']);
                }
            }
        }
        exit;
    }

    public function locationAdd() {
        echo"please remove the exit; from function.";
        exit;
        $locationDetails = $this->Location->find('all');

        if ($locationDetails) {
            foreach ($locationDetails as $detail) {

                $str = substr(strtolower($detail['Location']['name']), 0, 35);
                $str = Inflector::slug($str, '-');

                $cnd = array('Location.id' => $detail['Location']['id']);
                $this->Location->updateAll(array('Location.slug' => "'" . $str . "'"), $cnd);
            }
        }

        exit;
    }

    public function jobUpdateLocation() {


        $this->AdminLog->setDatabase('careerdicein', 'test');
        // $jobDetails = $this->AdminLog->query('SELECT * FROM careerdice_jobs where `careerdice_job_post_date` BETWEEN DATE_SUB( "2016-05-05",INTERVAL 6 MONTH ) AND DATE_SUB( "2016-05-05" ,INTERVAL 0 MONTH )');
        $jobDetails = $this->AdminLog->query('SELECT * FROM careerdice_jobs');
        //pr($jobDetails); exit;
        if ($jobDetails) {
            $oldJobs = array();
            foreach ($jobDetails as $detail) {
                $oldJobs[$detail['careerdice_jobs']['careerdice_job_id']] = strtolower(trim($detail['careerdice_jobs']['careerdice_job_location']));
            }
            // pr($oldJobs);exit;
            $locations = $this->Location->find('all');
            $locationArray = array();
            foreach ($locations as $location) {
                $locationArray[strtolower($location['Location']['name'])] = $location['Location']['id'];
            }

//           $jobs =  $this->Job->find('all', array('fields'=>array('Job.id', 'Job.job_id')));
//           foreach($jobs as $job){
//               $oldJobId = $job['Job']['job_id'];
//               $Id = $job['Job']['id'];
//               
//               if(array_key_exists($oldJobs[$oldJobId], $locationArray) && $locationArray[$oldJobs[$oldJobId]] !=''){
//                   $liID = $locationArray[$oldJobs[$oldJobId]];
//                   $this->Job->updateAll(array('Job.l_status'=>"'1'", 'Job.location'=>$liID), array('Job.id'=>$Id));
//               }
//           }

            $jobs = $this->Job->find('all', array('conditions' => array('Job.l_status' => 0), 'fields' => array('Job.id', 'Job.job_id')));
            foreach ($jobs as $job) {
                $oldJobId = $job['Job']['job_id'];
                $Id = $job['Job']['id'];

                $lExplaode = explode(',', $oldJobs[$oldJobId]);
                pr($lExplaode);
                $lname = trim($oldJobs[$oldJobId]);
                if (strpos($lname, 'banglore') !== false) {
                    echo $lname = 'bengaluru';
                }
                if (array_key_exists($lname, $locationArray) && $locationArray[$lname] != '') {
                    $liID = $locationArray[$lname];
                    $this->Job->updateAll(array('Job.l_status' => "'1'", 'Job.location' => $liID), array('Job.id' => $Id));
                }
            }
        }
        echo 'Record updated';
        exit;
    }

    public function jobUpdateDesignation() {


        $this->AdminLog->setDatabase('careerdicein', 'test');
        // $jobDetails = $this->AdminLog->query('SELECT * FROM careerdice_jobs where `careerdice_job_post_date` BETWEEN DATE_SUB( "2016-05-05",INTERVAL 6 MONTH ) AND DATE_SUB( "2016-05-05" ,INTERVAL 0 MONTH )');
        $jobDetails = $this->AdminLog->query('SELECT * FROM careerdice_jobs');
        //pr($jobDetails); exit;
        if ($jobDetails) {
            $oldJobs = array();
            foreach ($jobDetails as $detail) {
                if ($detail['careerdice_jobs']['careerdice_job_designation']) {
                    $oldJobs[$detail['careerdice_jobs']['careerdice_job_id']] = strtolower(trim($detail['careerdice_jobs']['careerdice_job_designation']));
                }
            }
            //pr($oldJobs);exit;
            $locations = $this->Skill->find('all', array('conditions' => array('Skill.type' => 'Designation')));
            $locationArray = array();
            foreach ($locations as $location) {
                $locationArray[strtolower($location['Skill']['name'])] = $location['Skill']['id'];
            }

            // pr($locationArray); exit;
//           $jobs =  $this->Job->find('all', array('fields'=>array('Job.id', 'Job.job_id')));
//           foreach($jobs as $job){
//               $oldJobId = $job['Job']['job_id'];
//               $Id = $job['Job']['id'];
//               
//               if(array_key_exists($oldJobs[$oldJobId], $locationArray) && $locationArray[$oldJobs[$oldJobId]] !=''){
//                   $liID = $locationArray[$oldJobs[$oldJobId]];
//                   $this->Job->updateAll(array('Job.l_skill'=>"'1'", 'Job.designation'=>$liID), array('Job.id'=>$Id));
//               }
//           }
            //  pr($oldJobs);exit;

            $jobs = $this->Job->find('all', array('conditions' => array('Job.l_skill' => 0), 'limit' => 100, 'fields' => array('Job.id', 'Job.job_id')));
            foreach ($jobs as $job) {
                $oldJobId = $job['Job']['job_id'];
                $Id = $job['Job']['id'];

                $lExplaode = explode(',', $oldJobs[$oldJobId]);
                pr($lExplaode);
                $lname = trim($oldJobs[$oldJobId]);
                if (strpos($lname, 'associate') !== false) {
                    echo $lname = 'associate';
                }
                if (array_key_exists($lname, $locationArray) && $locationArray[$lname] != '') {
                    echo $liID = $locationArray[$lname];
                    $this->Job->updateAll(array('Job.l_skill' => "'1'", 'Job.designation' => $liID), array('Job.id' => $Id));
                }
            }
        }
        echo 'Record updated';
        exit;
    }

    public function jobUpdateExp() {


        $this->AdminLog->setDatabase('careerdicein', 'test');
        // $jobDetails = $this->AdminLog->query('SELECT * FROM careerdice_jobs where `careerdice_job_post_date` BETWEEN DATE_SUB( "2016-05-05",INTERVAL 6 MONTH ) AND DATE_SUB( "2016-05-05" ,INTERVAL 0 MONTH )');
        $jobDetails = $this->AdminLog->query('SELECT * FROM careerdice_jobs');
        //pr($jobDetails); exit;
        if ($jobDetails) {
            $oldJobs = array();
            foreach ($jobDetails as $detail) {
                // pr($detail);exit;
                if ($detail['careerdice_jobs']['careerdice_job_designation']) {
                    $oldJobs[$detail['careerdice_jobs']['careerdice_job_id']] = strtolower(trim($detail['careerdice_jobs']['careerdice_job_experience_id']));
                }
            }


            // pr($locationArray); exit;

            $jobs = $this->Job->find('all', array('conditions' => array('Job.l_exp' => 0), 'fields' => array('Job.id', 'Job.job_id'), 'limit' => 500));
            foreach ($jobs as $job) {
                $oldJobId = $job['Job']['job_id'];
                $Id = $job['Job']['id'];
                if ($oldJobs[$oldJobId] == 2 || $oldJobs[$oldJobId] == 3) {
                    $min = 0;
                    $max = 1;
                } else if ($oldJobs[$oldJobId] == 4) {
                    $min = 1;
                    $max = 2;
                } else if ($oldJobs[$oldJobId] == 5) {
                    $min = 3;
                    $max = 5;
                } else if ($oldJobs[$oldJobId] == 6) {
                    $min = 5;
                    $max = 7;
                } else if ($oldJobs[$oldJobId] == 7) {
                    $min = 7;
                    $max = 10;
                } else if ($oldJobs[$oldJobId] == 8) {
                    $min = 10;
                    $max = 15;
                } else {
                    $min = 15;
                    $max = 35;
                }

                $this->Job->updateAll(array('Job.l_exp' => "'1'", 'Job.min_exp' => "'$min'", 'Job.max_exp' => "'$max'"), array('Job.id' => $Id));
                //echo $oldJobs[$oldJobId];exit;
            }
        }
        echo 'Record updated';
        exit;
    }

    public function updatejobs() {
       // $jobs = $this->Job->find('all', array('fields' => array('Job.id', 'Job.job_id', 'Job.designation', 'Job.location', 'Job.min_exp', 'Job.max_exp')));
       
//        if ($jobs) {
//
//            foreach ($jobs as $detail) {
//                
//                $this->request->data['UpdateJob']['job_id'] = $detail['Job']['id'];
//                $this->request->data['UpdateJob']['job_job_id'] = $detail['Job']['job_id'];
//                $this->request->data['UpdateJob']['designation'] = $detail['Job']['designation'];
//                $this->request->data['UpdateJob']['location'] = $detail['Job']['location'];
//                $this->request->data['UpdateJob']['min_exp'] = $detail['Job']['min_exp'];
//                $this->request->data['UpdateJob']['max_exp'] = $detail['Job']['max_exp'];
//
//               // $this->UpdateJob->create();
//               // $this->UpdateJob->save($this->data['UpdateJob']);
//            }
//        }
        
        $jobDetails = $this->UpdateJob->find('all', array('fields' => array('UpdateJob.id', 'UpdateJob.job_id', 'UpdateJob.job_job_id', 'UpdateJob.designation', 'UpdateJob.location', 'UpdateJob.min_exp', 'UpdateJob.max_exp')));
       // pr($jobDetails); exit;
        if($jobDetails){
            
            foreach($jobDetails as $job){
                 $this->Job->updateAll(array('Job.designation' => "'".$job['UpdateJob']['designation']."'",'Job.location' => "'".$job['UpdateJob']['location']."'", 'Job.min_exp' => "'".$job['UpdateJob']['min_exp']."'", 'Job.max_exp' => "'".$job['UpdateJob']['max_exp']."'"), array('Job.id' => "'".$job['UpdateJob']['job_id']."'"));
            }
            
           
        }
        
        exit;
    }
    
    
    public function skilldata(){
        $jobSkill = $this->Skillold->find('all',array('conditions' => array('Skillold.type' => 'Skill')));
       // pr($jobSkill); exit;
        $sa = array();
        $ssa = '';
        $cnd = array();
        foreach($jobSkill as $skill){
            $skillname = trim($skill['Skillold']['name']);
            $skillslug = trim($skill['Skillold']['slug']);
           
            $sa[] = $skillname; 
            $ssa = implode(',', $sa);
           // $this->Skill->updateAll(array('Skill.name' => "'".$skillname."'",'Skill.slug' => "'".$skillslug."'"), array('Skill.id' => $skill['Skill']['id'], 'Skill.type' => 'Skill'));
            $cnd = array("Skillold.name IN ($ssa) ");
            $this->Skillold->deleteAll($cnd);
        }
        exit;
    }
    

}

?>