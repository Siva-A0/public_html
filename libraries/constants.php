<?php

###############################################################################
#
#	
#
#
###############################################################################

// Define for database
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__) . '/config.php';
}

//database server
DEFINE('DB_SERVER', (string)app_env('APP_DB_HOST', "localhost"));

//database login name
//DEFINE('DB_USER', "nirulawi_wise");
//database login password
//DEFINE('DB_PASS', "DuwLA%h;r)TX");

//database name
//DEFINE('DB_DATABASE', "nirulawi_wise");

//DEFINE('BASE_PATH', "http://nirulawise.com");

//database login name
DEFINE('DB_USER', (string)app_env('APP_DB_USER', "root"));
//database login password
DEFINE('DB_PASS', (string)app_env('APP_DB_PASS', ""));

//database name
DEFINE('DB_DATABASE', (string)app_env('APP_DB_NAME', "anu"));

DEFINE('BASE_PATH', (string)app_env('APP_SITE_URL', ""));


/*
*These are tables 
*/
DEFINE('TB_USERS','students');

DEFINE('TB_STAFF','faculty');
DEFINE('TB_STAFF_CATEGORY','faculty_category');

DEFINE('TB_BATCH','year_batch');
DEFINE('TB_STREAM','stream');
DEFINE('TB_CLASS','class');
DEFINE('TB_SECTION','section');
DEFINE('TB_SYLLABUS','syllabus');

DEFINE('TB_COMT_CATEG','committee_cat');
DEFINE('TB_COMMITTEE','committee');

DEFINE('TB_EVENT_TYPES','event_types');
DEFINE('TB_EVENTS','events');
DEFINE('TB_EVENT_REG','event_reg');
DEFINE('TB_EVENT_RESULT','event_results');

DEFINE('TB_SUBJECTS','subjects');
DEFINE('TB_MATERAILS','materials');
DEFINE('TB_PREV_PAPERS','prev_papers');

DEFINE('TB_ACHIEVEMENTS','achievements');

DEFINE('TB_PLACEMENTS','placements');

DEFINE('TB_ALUMNI','alumni');

DEFINE('TB_COMMENTS','comments');

DEFINE('TB_HIGHLIGHTS','highlights');

DEFINE('TB_GALLERY','gallery');
DEFINE('TB_GALLERY_CATEGORY','gallery_category');
DEFINE('TB_SUPPORT_SETTINGS','support_settings');
DEFINE('TB_PASSWORD_RESETS','password_resets');

DEFINE('TEACHING',1);
DEFINE('NONTEACHING',2);

DEFINE('BRANCH','IT');

DEFINE('DOCUMENT',1);
DEFINE('NON_DOCUMENT',2);

DEFINE('anu',1);
DEFINE('AIML',1);
DEFINE('DEPARTMENT',2);

DEFINE('HOD','hod');
DEFINE('PRINCIPAL','principal');
DEFINE('DIRECTOR','director');
DEFINE('CHAIRMAN','chairman');

DEFINE('PASSOUT','0');

DEFINE('OTHERSTUD','5');

/*
 *  this tables is for admin
 */
DEFINE('ADMIN_TABLE','admin');


// define for Inifile

// Allow direct file download (hotlinking)?
// Empty - allow hotlinking
// If set to nonempty value (Example: example.com) will only allow downloads when referrer contains this text
define('ALLOWED_REFERRER', '');

?>
