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

// Base Dir
if (!defined('BASEDIR')) {
	define('BASEDIR', rtrim(getenv('DOCUMENT_ROOT'), '/'));
}

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