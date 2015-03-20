<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';

$active_record = TRUE;

$db['default']['hostname'] = '192.168.1.200';
$db['default']['username'] = 'xicom';
$db['default']['password'] = 'technologies';
$db['default']['database'] = '0_doctor_now';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


$db['demo']['hostname'] = 'localhost';
$db['demo']['username'] = 'drnow';
$db['demo']['password'] = 'XT-drnow@2212';
$db['demo']['database'] = 'drnow';
$db['demo']['dbdriver'] = 'mysql';
$db['demo']['dbprefix'] = '';
$db['demo']['pconnect'] = FALSE;
$db['demo']['db_debug'] = TRUE;
$db['demo']['cache_on'] = FALSE;
$db['demo']['cachedir'] = '';
$db['demo']['char_set'] = 'utf8';
$db['demo']['dbcollat'] = 'utf8_general_ci';
$db['demo']['swap_pre'] = '';
$db['demo']['autoinit'] = TRUE;
$db['demo']['stricton'] = FALSE;


/* For live */
$db['demo_client']['hostname'] = 'localhost';
$db['demo_client']['username'] = 'drn_xicom';
$db['demo_client']['password'] = '*Tpq,R!zy)it';
$db['demo_client']['database'] = 'drn_doctor';
$db['demo_client']['dbdriver'] = 'mysqli';
$db['demo_client']['dbprefix'] = '';
$db['demo_client']['pconnect'] = FALSE;
$db['demo_client']['db_debug'] = TRUE;
$db['demo_client']['cache_on'] = FALSE;
$db['demo_client']['cachedir'] = '';
$db['demo_client']['char_set'] = 'utf8';
$db['demo_client']['dbcollat'] = 'utf8_general_ci';
$db['demo_client']['swap_pre'] = '';
$db['demo_client']['autoinit'] = TRUE;
$db['demo_client']['stricton'] = FALSE;

/* Third party demo */
$db['third_party_demo']['hostname'] = 'localhost';
$db['third_party_demo']['username'] = 'drnadmin';
$db['third_party_demo']['password'] = '!65fy4Oj';
$db['third_party_demo']['database'] = 'moorrohot6116com6081_DrN';
$db['third_party_demo']['dbdriver'] = 'mysql';
$db['third_party_demo']['dbprefix'] = '';
$db['third_party_demo']['pconnect'] = FALSE;
$db['third_party_demo']['db_debug'] = TRUE;
$db['third_party_demo']['cache_on'] = FALSE;
$db['third_party_demo']['cachedir'] = '';
$db['third_party_demo']['char_set'] = 'utf8';
$db['third_party_demo']['dbcollat'] = 'utf8_general_ci';
$db['third_party_demo']['swap_pre'] = '';
$db['third_party_demo']['autoinit'] = TRUE;
$db['third_party_demo']['stricton'] = FALSE;
/* End of file database.php */
/* Location: ./application/config/database.php */
