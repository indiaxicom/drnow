<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "operations/login";
$route['login'] = "home";
$route['404_override'] = '';

$route['admin'] = 'admin/dashboard';
$route['admin/login'] = 'admin/dashboard/login';
$route['admin/logout'] = 'admin/dashboard/logout';

$route['admin/manage_doctors'] = 'admin/manage_users';
$route['admin/manage_doctors/(:num)'] = 'admin/manage_users/index/$1';
$route['admin/manage_doctors/edit/(:num)'] = 'admin/manage_users/add/$1';
$route['admin/manage_doctors/add'] = 'admin/manage_users/add';
$route['admin/manage_doctors/change_status'] = 'admin/manage_users/change_status';
$route['admin/manage_users/edit/(:num)'] = 'admin/manage_users/add/$1';
$route['admin/manage_users/(:num)'] = 'admin/manage_users/index/$1';

$route['admin/manage_email_templates/(:num)'] = 'admin/manage_email_templates/index/$1';
$route['admin/manage_email_templates/edit/(:num)'] = 'admin/manage_email_templates/add/$1';

$route['admin/manage_content/(:num)'] = 'admin/manage_content/index/$1';
$route['admin/manage_content/edit/(:num)'] = 'admin/manage_content/add/$1';

$route['admin/manage_shifts/(:num)'] = 'admin/manage_shifts/index/$1';
$route['admin/manage_shifts/edit/(:num)'] = 'admin/manage_shifts/add/$1';

/*Frontend*/
$route['login'] = 'operations/login';
$route['logout'] = 'operations/logout';
$route['profile'] = 'doctors/profile';

$route['patients/(:any)'] = 'patients/profile/$1';
