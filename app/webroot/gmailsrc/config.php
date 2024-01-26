<?php

/*
 * Copyright 2010 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

global $apiConfig;
$apiConfig = array(
    // True if objects should be returned by the service classes.
    // False if associative arrays should be returned (default behavior).
    'use_objects' => false,
    // The application_name is included in the User-Agent HTTP header.
    'application_name' => 'GriffeRack',
    // OAuth2 Settings, you can get these keys at https://code.google.com/apis/console
    /* 'oauth2_client_id' => '282259147558-o1a20qu792cepcfbrbk19i819bhn1827.apps.googleusercontent.com',
      'oauth2_client_secret' => 'j-9Oy6jxeuy4Dh6p0B7xv0YR',
      'oauth2_redirect_uri' => 'http://lampdevelopers.com/griffe_rack/users/gmaillogin', */

    //uplift website
    /* 'oauth2_client_id' => '16660681832-c1gtv4nj3aqtad59q98h5i5il67dfgra.apps.googleusercontent.com',
      'oauth2_client_secret' => '3dGjvAXVkLL_lpzjBmFhQtF_',
      'oauth2_redirect_uri' => 'https://www.upliftjobs.com/users/gmaillogin', */

    //demo website
    'oauth2_client_id' => '19572120562-u595qieqfi2idbt0suokv6r1aoo3q9ii.apps.googleusercontent.com',
    'oauth2_client_secret' => 'IlznzUQr9pvBGnmnvTBhvN4u',
    'oauth2_redirect_uri' => 'http://demo.imagetowebpage.com/job_site_script/users/gmaillogin',
// The developer key, you get this at https://code.google.com/apis/console
    //other site
    //'developer_key' => 'AIzaSyCwDTggLKqTm1388KHm5J8VD-7Kz49qEIc',

    /* ---Upliftjobs start---
      'developer_key' => 'AIzaSyDRpbHKxNRxgFCFAvZ4CmwtLLh48d5tt90',
      // Site name to show in the Google's OAuth 1 authentication screen.
      'site_name' => 'https://www.upliftjobs.com/',
      /*---Upliftjobs end--- */

    /* ---Demo start--- */
    //Demo Jobsitescript ---
    'developer_key' => 'AIzaSyC-RR5kF0VdtMeHOG7XbE2YENNQiaxNko8',
    // Site name to show in the Google's OAuth 1 authentication screen.
    'site_name' => 'http://demo.imagetowebpage.com/job_site_script/',
    /* ---Demo ends--- */


    // Which Authentication, Storage and HTTP IO classes to use.
    'authClass' => 'Google_OAuth2',
    'ioClass' => 'Google_CurlIO',
    'cacheClass' => 'Google_FileCache',
    // Don't change these unless you're working against a special development or testing environment.
    'basePath' => 'https://www.googleapis.com',
    // IO Class dependent configuration, you only have to configure the values
    // for the class that was configured as the ioClass above
    'ioFileCache_directory' =>
    (function_exists('sys_get_temp_dir') ?
            sys_get_temp_dir() . '/Google_Client' :
            '/tmp/Google_Client'),
    // Definition of service specific values like scopes, oauth token URLs, etc
    'services' => array(
        'analytics' => array('scope' => 'https://www.googleapis.com/auth/analytics.readonly'),
        'calendar' => array(
            'scope' => array(
                "https://www.googleapis.com/auth/calendar",
                "https://www.googleapis.com/auth/calendar.readonly",
            )
        ),
        'books' => array('scope' => 'https://www.googleapis.com/auth/books'),
        'latitude' => array(
            'scope' => array(
                'https://www.googleapis.com/auth/latitude.all.best',
                'https://www.googleapis.com/auth/latitude.all.city',
            )
        ),
        'moderator' => array('scope' => 'https://www.googleapis.com/auth/moderator'),
        'oauth2' => array(
            'scope' => array(
                'https://www.googleapis.com/auth/userinfo.profile',
                'https://www.googleapis.com/auth/userinfo.email',
            )
        ),
        'plus' => array('scope' => 'https://www.googleapis.com/auth/plus.me'),
        'siteVerification' => array('scope' => 'https://www.googleapis.com/auth/siteverification'),
        'tasks' => array('scope' => 'https://www.googleapis.com/auth/tasks'),
        'urlshortener' => array('scope' => 'https://www.googleapis.com/auth/urlshortener')
    )
);
