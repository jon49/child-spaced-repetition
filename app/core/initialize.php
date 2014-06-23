<?php

/****************************************
  ENVIRONMENT SETTINGS
*****************************************/

// Currency
setlocale(LC_MONETARY, 'en_US');

// Timezone
date_default_timezone_set('America/Phoenix');

// Error Reporting
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

// Base Directory
define('BASEDIR', rtrim(getenv('DOCUMENT_ROOT'), '/');

// Script Basename
define('SCRIPT_BASENAME', basename($_SERVER['SCRIPT_FILENAME'], '.php'));

// App Settings
include(BASEDIR . '/app/app_settings.php');


/****************************************
  CORE LIBRARY
*****************************************/

include(BASEDIR . '/app/core/core.lib.php');


/****************************************
  CLASS LOADER
*****************************************/

include(BASEDIR . '/app/core/class_loader.class.php');
ClassLoader::setup();