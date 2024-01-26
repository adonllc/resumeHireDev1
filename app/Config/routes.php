<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/', array('controller' => 'homes', 'action' => 'index', ''));

Router::connect('/admin', array('controller' => 'admins', 'action' => 'login', 'admin' => true));

Router::connect('/terms_and_conditions', array('controller' => 'pages', 'action' => 'terms_and_conditions'));
Router::connect('/terms_and_condition', array('controller' => 'pages', 'action' => 'terms_and_condition'));
Router::connect('/privacy_policy', array('controller' => 'pages', 'action' => 'privacy_policy'));
Router::connect('/career_resources', array('controller' => 'pages', 'action' => 'career_resources'));
Router::connect('/career_tools', array('controller' => 'pages', 'action' => 'career_tools'));
Router::connect('/about_us', array('controller' => 'pages', 'action' => 'about_us'));
Router::connect('/faq', array('controller' => 'pages', 'action' => 'faq'));
Router::connect('/contact-us', array('controller' => 'pages', 'action' => 'contactUs'));
Router::connect('/sendmessage', array('controller' => 'pages', 'action' => 'sendmessage'));
Router::connect('/candidates/specilylistsearch', array('controller' => 'candidates', 'action' => 'specilylistsearch'));

Router::connect('/candidates/updateskills', array('controller' => 'candidates', 'action' => 'updateskills'));
Router::connect('/candidates/canupdateskills', array('controller' => 'candidates', 'action' => 'canupdateskills'));
Router::connect('/users/import', array('controller' => 'users', 'action' => 'import'));
Router::connect('/users/generateformat', array('controller' => 'users', 'action' => 'generateformat'));


Router::connect('/plans/purchase', array('controller' => 'plans', 'action' => 'purchase'));
Router::connect('/payments/checkout', array('controller' => 'payments', 'action' => 'checkout'));
Router::connect('/payments/checkoutSuccess', array('controller' => 'payments', 'action' => 'checkoutSuccess'));
Router::connect('/payments/checkoutCancel', array('controller' => 'payments', 'action' => 'checkoutCancel'));
Router::connect('/payments/checkoutNotification', array('controller' => 'payments', 'action' => 'checkoutNotification'));
Router::connect('/payments/history', array('controller' => 'payments', 'action' => 'history'));
Router::connect('/refresh-database', array('controller' => 'cron', 'action' => 'databaseCopy'));
Router::connect('/setlanguage/:lang', array('controller' => 'homes', 'action' => 'setlanguage'), array('pass' => array('lang')));


Router::connect(
        '/pages/:slug', array('controller' => 'pages', 'action' => 'staticpage'), array(
    'pass' => array('slug', 'ext'),
        )
);
Router::connect('/webservices/:action/*', array('controller' => 'webservices'));
Router::connect('/cron/autoxmlimport', array('controller' => 'cron', 'action' => 'autoxmlimport'));
Router::connect('/cron/sqlimport', array('controller' => 'cron', 'action' => 'sqlimport'));
Router::connect('/cron/changeslug', array('controller' => 'cron', 'action' => 'changeslug'));
Router::connect('/cron/changeslugjob', array('controller' => 'cron', 'action' => 'changeslugjob'));
Router::connect('/cron/importresume', array('controller' => 'cron', 'action' => 'importresume'));
Router::connect('/cron/sendAlertBySearch', array('controller' => 'cron', 'action' => 'sendAlertBySearch'));
Router::connect('/cron/sendLatestJobs', array('controller' => 'cron', 'action' => 'sendLatestJobs'));
Router::connect('/cron/sendtestemail', array('controller' => 'cron', 'action' => 'sendtestemail'));
Router::connect('/users/setLocationInSession', array('controller' => 'users', 'action' => 'setLocationInSession'));
Router::connect('/users/countJob', array('controller' => 'users', 'action' => 'countJob'));
Router::connect('/users/never', array('controller' => 'users', 'action' => 'never'));
Router::connect('/candidates/makecv', array('controller' => 'candidates', 'action' => 'makecv'));
Router::connect('/candidates/generatecv', array('controller' => 'candidates', 'action' => 'generatecv'));
//Router::connect('/users/getalert', array('controller' => 'users', 'action' => 'getalert'));
Router::connect('/jobs/reset', array('controller' => 'jobs', 'action' => 'reset'));
Router::connect('/jobs/applypop', array('controller' => 'jobs', 'action' => 'applypop'), array('pass' => array('slug')));

/* import */
Router::connect('/imports/skilldata', array('controller' => 'imports', 'action' => 'skilldata'));

/* * *home*** */
Router::connect('/homes/error', array('controller' => 'homes', 'action' => 'error'));
Router::connect('/homes/index', array('controller' => 'homes', 'action' => 'index'));
//Router::connect('/homes/phpinfo', array('controller' => 'homes', 'action' => 'phpinfo'));

/* --Admin-- */
Router::connect('/admin/users', array('controller' => 'users', 'action' => 'index', 'admin' => true));
Router::connect('/admin/users/selectforslider', array('controller' => 'users', 'action' => 'selectforslider', 'admin' => true));

Router::connect('/admin/candidates', array('controller' => 'candidates', 'action' => 'index', 'admin' => true));
Router::connect('/admin/countries', array('controller' => 'countries', 'action' => 'index', 'admin' => true));
Router::connect('/admin/categories', array('controller' => 'categories', 'action' => 'index', 'admin' => true));
Router::connect('/admin/swears', array('controller' => 'swears', 'action' => 'index', 'admin' => true));
Router::connect('/admin/skills', array('controller' => 'skills', 'action' => 'index', 'admin' => true));
Router::connect('/admin/designations', array('controller' => 'designations', 'action' => 'index', 'admin' => true));
Router::connect('/admin/locations', array('controller' => 'locations', 'action' => 'index', 'admin' => true));
Router::connect('/admin/jobs', array('controller' => 'jobs', 'action' => 'index', 'admin' => true));
Router::connect('/admin/banneradvertisements', array('controller' => 'banneradvertisements', 'action' => 'index', 'admin' => true));
Router::connect('/admin/courses', array('controller' => 'courses', 'action' => 'index', 'admin' => true));
Router::connect('/admin/pages', array('controller' => 'pages', 'action' => 'index', 'admin' => true));
Router::connect('/admin/emailtemplates', array('controller' => 'emailtemplates', 'action' => 'index', 'admin' => true));
Router::connect('/admin/blogs', array('controller' => 'blogs', 'action' => 'index', 'admin' => true));
Router::connect('/admin/currencies', array('controller' => 'currencies', 'action' => 'index', 'admin' => true));
Router::connect('/admin/users/selectforslider', array('controller' => 'users', 'action' => 'selectforslider', 'admin' => true));
Router::connect('/admin/newsletters', array('controller' => 'newsletters', 'action' => 'index', 'admin' => true));
Router::connect('/admin/announcements', array('controller' => 'announcements', 'action' => 'index', 'admin' => true));
Router::connect('/admin/keywords', array('controller' => 'keywords', 'action' => 'index', 'admin' => true));
Router::connect('/admin/sliders', array('controller' => 'sliders', 'action' => 'index', 'admin' => true));
//Router::connect('/admin/newsletters', array('controller' => 'newsletters', 'action' => 'index', 'admin' => true));

/* --blogs-- */
Router::connect('/blog', array('controller' => 'blogs', 'action' => 'index'));
Router::connect('/blog/index', array('controller' => 'blogs', 'action' => 'index'));
Router::connect('/blog/:slug', array('controller' => 'blogs', 'action' => 'detail'), array('pass' => array('slug', 'ext')));


/* --Categories-- */
Router::connect('/categories/getSubCategory/', array('controller' => 'categories', 'action' => 'getSubCategory'), array('pass' => array('')));
Router::connect('/categories/getSubCategory/:catId', array('controller' => 'categories', 'action' => 'getSubCategory'), array('pass' => array('catId')));
Router::connect('/categories/allcategories', array('controller' => 'categories', 'action' => 'allcategories'));
Router::connect('/locations/alllocations', array('controller' => 'locations', 'action' => 'alllocations'));


/* --User-- */
Router::connect('/users/myaccount', array('controller' => 'users', 'action' => 'myaccount'));
Router::connect('/users/editProfile', array('controller' => 'users', 'action' => 'editProfile'));
Router::connect('/users/changePassword', array('controller' => 'users', 'action' => 'changePassword'));
Router::connect('/users/uploadPhoto', array('controller' => 'users', 'action' => 'uploadPhoto'));
Router::connect('/users/logout', array('controller' => 'users', 'action' => 'logout'));
Router::connect('/users/login_popup', array('controller' => 'users', 'action' => 'login_popup'));
Router::connect('/users/forgot_popup', array('controller' => 'users', 'action' => 'forgot_popup'));
Router::connect('/users/forgotPassword', array('controller' => 'users', 'action' => 'forgotPassword'));
Router::connect('/users/newRegister', array('controller' => 'users', 'action' => 'newRegister'));
Router::connect('/users/captcha', array('controller' => 'users', 'action' => 'captcha'));
Router::connect('/users/login', array('controller' => 'users', 'action' => 'login'));
Router::connect('/users/employerlogin', array('controller' => 'users', 'action' => 'employerlogin'));
Router::connect('/users/fblogin', array('controller' => 'users', 'action' => 'fblogin'));
Router::connect('/users/gmaillogin', array('controller' => 'users', 'action' => 'gmaillogin'));
Router::connect('/users/linkedinlogin', array('controller' => 'users', 'action' => 'linkedinlogin'));
Router::connect('/users/chklogin', array('controller' => 'users', 'action' => 'chklogin'));
Router::connect('/users/reportproblem', array('controller' => 'users', 'action' => 'reportproblem'));
Router::connect('/users/employers', array('controller' => 'users', 'action' => 'employers'));
Router::connect('/users/ajaxchangeprofile', array('controller' => 'users', 'action' => 'ajaxchangeprofile'));
Router::connect('/users/deleteAccount', array('controller' => 'users', 'action' => 'deleteAccount'));
Router::connect('/users/mailhistory', array('controller' => 'users', 'action' => 'mailhistory'));
Router::connect('/users/maildetail/:slug', array('controller' => 'users', 'action' => 'maildetail'), array('pass' => array('slug')));
Router::connect('/users/maildesign', array('controller' => 'users', 'action' => 'maildesign'));

/* --Candidate-- */
Router::connect('/candidates/generatecvdoc', array('controller' => 'candidates', 'action' => 'generatecvdoc'));
Router::connect('/candidates/deleteAccount', array('controller' => 'candidates', 'action' => 'deleteAccount'));
Router::connect('/candidates/favorite', array('controller' => 'candidates', 'action' => 'favorite'));
Router::connect('/candidates/listing', array('controller' => 'candidates', 'action' => 'listing'));
Router::connect('/candidates/myaccount', array('controller' => 'candidates', 'action' => 'myaccount'));
Router::connect('/candidates/editProfile', array('controller' => 'candidates', 'action' => 'editProfile'));
Router::connect('/candidates/editEducation', array('controller' => 'candidates', 'action' => 'editEducation'));
Router::connect('/candidates/editExperience', array('controller' => 'candidates', 'action' => 'editExperience'));
Router::connect('/candidates/editProfessional', array('controller' => 'candidates', 'action' => 'editProfessional'));
Router::connect('/candidates/changePassword', array('controller' => 'candidates', 'action' => 'changePassword'));
Router::connect('/candidates/uploadPhoto', array('controller' => 'candidates', 'action' => 'uploadPhoto'));
Router::connect('/candidates/uploadmultipleimages', array('controller' => 'candidates', 'action' => 'uploadmultipleimages'));
Router::connect('/candidates/mailhistory', array('controller' => 'candidates', 'action' => 'mailhistory'));
Router::connect('/candidates/maildetail/:slug', array('controller' => 'candidates', 'action' => 'maildetail'), array('pass' => array('slug')));
Router::connect('/candidates/uploadcv/login', array('controller' => 'candidates', 'action' => 'uploadCvLogin'));
Router::connect('/candidates/addvideocv', array('controller' => 'candidates', 'action' => 'addVideoCv'));


/* --Alerts-- */
Router::connect('/alerts/index', array('controller' => 'alerts', 'action' => 'index'));
Router::connect('/alerts/add', array('controller' => 'alerts', 'action' => 'add'));
Router::connect('/alerts/edit/:slug', array('controller' => 'alerts', 'action' => 'edit'), array('pass' => array('slug')));

Router::connect('/cron/sendAlertInsert', array('controller' => 'cron', 'action' => 'sendAlertInsert'));
Router::connect('/cron/sendAlert', array('controller' => 'cron', 'action' => 'sendAlert'));
Router::connect('/cron/sendNewsletterEmail', array('controller' => 'cron', 'action' => 'sendNewsletterEmail'));
Router::connect('/cron/getallsalary', array('controller' => 'cron', 'action' => 'getallsalary'));

/* --pages-- */
Router::connect('/how-it-works.html', array('controller' => 'pages', 'action' => 'staticpage', 'how-it-works'), array('pass' => array('slug', 'ext')));
Router::connect('/about-us.html', array('controller' => 'pages', 'action' => 'staticpage', 'about-us'), array('pass' => array('slug', 'ext')));
Router::connect('/saved-jobs.html', array('controller' => 'pages', 'action' => 'staticpage', 'saved-jobs'), array('pass' => array('slug', 'ext')));
Router::connect('/companies.html', array('controller' => 'pages', 'action' => 'staticpage', 'companies'), array('pass' => array('slug', 'ext')));
Router::connect('/career-tools.html', array('controller' => 'pages', 'action' => 'staticpage', 'career-tools'), array('pass' => array('slug', 'ext')));
Router::connect('/career-resources.html', array('controller' => 'pages', 'action' => 'staticpage', 'career-resources'), array('pass' => array('slug', 'ext')));
Router::connect('/faq.html', array('controller' => 'pages', 'action' => 'staticpage', 'faq'), array('pass' => array('slug', 'ext')));
Router::connect('/benefits.html', array('controller' => 'pages', 'action' => 'staticpage', 'benefits'), array('pass' => array('slug', 'ext')));
Router::connect('/post-a-job.html', array('controller' => 'pages', 'action' => 'staticpage', 'post-a-job'), array('pass' => array('slug', 'ext')));
Router::connect('/privacy-policy.html', array('controller' => 'pages', 'action' => 'staticpage', 'privacy-policy'), array('pass' => array('slug', 'ext')));
Router::connect('/find-a-job.html', array('controller' => 'pages', 'action' => 'staticpage', 'find-a-job'), array('pass' => array('slug', 'ext')));
Router::connect('/terms-and-conditions.html', array('controller' => 'pages', 'action' => 'staticpage', 'terms-and-conditions'), array('pass' => array('slug', 'ext')));
Router::connect('/resignation-sample.html', array('controller' => 'pages', 'action' => 'staticpage', 'resignation-sample'), array('pass' => array('slug', 'ext')));
Router::connect('/resume-sample.html', array('controller' => 'pages', 'action' => 'staticpage', 'resume-sample'), array('pass' => array('slug', 'ext')));
Router::connect('/sitemap.html', array('controller' => 'homes', 'action' => 'sitemap'));


Router::connect('/viewjobs/:slug', array('controller' => 'users', 'action' => 'viewjobs'), array('pass' => array('slug')));
Router::connect('/peoplesview/:slug', array('controller' => 'users', 'action' => 'peoplesview'), array('pass' => array('slug')));
Router::connect('/publicprofile/:slug', array('controller' => 'candidates', 'action' => 'publicprofile'), array('pass' => array('slug')));
Router::connect('/viewcompanies/:slug', array('controller' => 'users', 'action' => 'viewcompanies'), array('pass' => array('slug')));
Router::connect('/jobsof/:slug', array('controller' => 'users', 'action' => 'jobsof'), array('pass' => array('slug')));
Router::connect('/profile/:slug', array('controller' => 'candidates', 'action' => 'companyprofile'), array('pass' => array('slug')));

Router::connect('/keywords/ajaxkeywordlist', array('controller' => 'keywords', 'action' => 'ajaxkeywordlist'));
Router::connect('/keywords/ajaxspecialtylist', array('controller' => 'keywords', 'action' => 'ajaxspecialtylist'));
/* --Job-- */
Router::connect('/jobs/listing', array('controller' => 'jobs', 'action' => 'listing'));
Router::connect('/jobs/beforeCreateJob', array('controller' => 'jobs', 'action' => 'beforeCreateJob'));
Router::connect('/jobs/savedjob', array('controller' => 'jobs', 'action' => 'shortList'));
Router::connect('/jobs/applied', array('controller' => 'jobs', 'action' => 'applied'));
Router::connect('/jobs/selectType', array('controller' => 'jobs', 'action' => 'selectType'));
Router::connect('/jobs/createJob', array('controller' => 'jobs', 'action' => 'createJob'));
Router::connect('/jobs/management', array('controller' => 'jobs', 'action' => 'management'));
Router::connect('/jobs/copyJob', array('controller' => 'jobs', 'action' => 'copyJob'));
Router::connect('/jobs', array('controller' => 'jobs', 'action' => 'listing'));
//Router::connect('/jobs/:slug', array('controller' => 'jobs', 'action' => 'listing'), array('pass' => array('slug')));
Router::connect('/:slug', array('controller' => 'jobs', 'action' => 'listing'), array('pass' => array('slug')));
//Router::connect('/jobs/filterSection', array('controller' => 'jobs', 'action' => 'filterSection'));
//Router::connect('/jobs/filterJob', array('controller' => 'jobs', 'action' => 'filterJob'));
Router::connect('/jobs/feedlist', array('controller' => 'jobs', 'action' => 'feedlist'));
Router::connect('/jobs/transportfeedlist', array('controller' => 'jobs', 'action' => 'transportfeedlist'));
Router::connect('/jobs/stellenanzeigenfeedlist', array('controller' => 'jobs', 'action' => 'stellenanzeigenfeedlist'));

Router::connect('/jobs/jobApplyDetail/:slug', array('controller' => 'jobs', 'action' => 'jobApplyDetail'), array('pass' => array('slug')));
Router::connect('/jobs/jobApply/:slug', array('controller' => 'jobs', 'action' => 'jobApply'), array('pass' => array('slug')));
Router::connect('/jobs/JobSave/:slug', array('controller' => 'jobs', 'action' => 'JobSave'), array('pass' => array('slug')));
//Router::connect('/:slug', array('controller' => 'jobs', 'action' => 'jobListing'), array('pass' => array('slug')));
Router::connect('/:cat/:slug', array('controller' => 'jobs', 'action' => 'detail'), array('pass' => array('cat', 'slug', 'ext')));

/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
//Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
Router::parseExtensions('json', 'csv');
