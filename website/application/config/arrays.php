<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------
  | Customized Arrays
  | -------------------------------------------------------------------
  | This file contains arrays of used in website.
  |
 */

$config['userTypeArr'] = array(
    USER_ADMIN => 'Admin',
    USER_DOCTOR => 'Doctor',
    USER_PATIENT => 'Patient'
);

/*
 * User status Array
 */
$config['userStatusArr'] = array(
    ACTIVE => 'Active',
    IN_ACTIVE => 'Inactive'
);

$config['cmsArr'] = array(
    REGISTER_PAGE => array('title' => 'Register Page', 'alias' => 'register'),
    TERMS_OF_USE_PAGE => array('title' => 'Terms & Conditions', 'alias' => 'terms-of-use'),
    PRIVACY_POLICY_PAGE => array('title' => 'Privacy Policy', 'alias' => 'privacy-policy'),
);

/*
 * Array of messages to display on website 
 */
$config['messagesArr'] = array(
    '1001' => 'Record Saved Successfuly',
    '1002' => 'Record Updated Successfuly',
    '1003' => 'Wrong email or password',
    '1004' => 'Logout Successfully',
    '1005' => 'You are not authorised to enter this section',
    '1006' => 'Password Updated Successfuly',
    '1007' => 'Incorrect existing password',
    '1008' => 'Unexpected Error',
    '1009' => 'This doctor already has shift between this time',
);

/*
 * Array containing Email Template
 */
$config['emailTempArr'] = array(
    DOCTOR_NEW_REGISTRATION => 'Doctor\'s New Registration',
    FORGOT_PASSWORD => 'Forgot Password',
    RESET_PASSWORD => 'Reset Password',
);


$config['fileTypeArr'] = array(
    'image' => 'jpg|png|jpeg|gif',
    'document' => 'doc|docx|pdf|xls|xlsx|odt',
    'video' => 'mpg|mpeg'
);

$config['docFieldArr'] = array(
    'first_name' => 'doctorforename',
    'last_name' => 'doctorsurname',
    'salutation' => 'doctorsalutation',
    'email' => 'doctoremail',
    'password' => 'doctorpassword',
    'gender' => 'doctorsex',
    'bio' => 'doctorbio',
    'doctoravatar' => 'doctoravatar',
    'gmc_no' => 'doctorgovbody'
);

/* End of file arrays.php */
/* Location: ./application/config/user_agents.php */
