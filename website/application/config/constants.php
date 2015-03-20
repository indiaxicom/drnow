<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/* Site Settings */

define('SITE_NAME', 'Doctor Now');

/*
 * Paths
 */
define('CSS_URL_PATH', 'assets/css/');
define('JS_URL_PATH', 'assets/js/');
define('IMAGES_URL_PATH', 'assets/images/');
define('PROFILE_IMAGE_PATH', 'assets/files/profile/');
define('SIGNATURE_IMAGE_PATH', 'assets/files/signatures/');
define('APPOINTMENT_IMAGE_PATH', 'assets/files/appointments/');


/*File image type*/
define('PROFILE_IMAGE', '1');
define('SIGNATURE_IMAGE', '2');
define('APPOINTMENT_IMAGE', '3');

/* USERS Status
 */
define('ACTIVE', '1');
define('IN_ACTIVE', '-1');
define('DELETED', '-2');
define('CANCELLED', '-3');

/*
 * Email Template Types
 */
define('DOCTOR_NEW_REGISTRATION', '1');
define('FORGOT_PASSWORD', '2');
define('RESET_PASSWORD', '3');

define('SUPER_ADMIN', '1');

//user types in users table

define('USER_ADMIN', '1');
define('USER_DOCTOR', '2');
define('USER_PATIENT', '3');


/*
 * Gender Constants
 */
define('MALE', '1');
define('FEMALE', '0');

/*
 * CMS
 */
define('REGISTER_PAGE', '1');
define('TERMS_OF_USE_PAGE', '2');
define('PRIVACY_POLICY_PAGE', '3');

//Google api key for push notifications
define('GOOGLE_PUSH_API_KEY', 'AIzaSyDFj3ZhBT0HGS2iPlrnD_6R0GvTr0X9DN0');

//device type
define('ANDROID_DEVICE', '1');
define('IOS_DEVICE', '2');


//Parse credentials
define('PARSE_APP_ID', 'WRfQ61MfN5TbesLCpOLCMhmWdW9X2GP2ECNxQEJZ');
define('PARSE_REST_KEY', 'Bhu0yFCoChW2lNSHaV6Pkg9D1rUJhOTSPZXBlbG9');

/*Third Party API URL*/
//define('THIRD_PARTY_API_URL', 'http://www.testapi.net/apiweb.php');
define('THIRD_PARTY_API_URL', 'http://api.drnow.co.uk/apiweb.php');

/* Call status */
define('CALL_PENDING', '0');
define('CALL_COMPLETED', '1');

/* Default Appointment Interval in minutes */
define('APPOINTMENT_INTERVAL', 10);
/* End of file constants.php */
/* Location: ./application/config/constants.php */
