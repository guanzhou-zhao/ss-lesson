<?php

global $project;
$project = 'mysite';

global $database;
$database = '';

require_once('conf/ConfigureFromEnv.php');

// Set the site locale
i18n::set_locale('en_US');


// log errors and warnings
SS_Log::add_writer(new SS_LogFileWriter('../silverstripe-errors-warnings.log'), SS_Log::WARN, '<=');

// or just errors
SS_Log::add_writer(new SS_LogFileWriter('../silverstripe-errors.log'), SS_Log::ERR);

// or notices (e.g. for Deprecation Notifications)
SS_Log::add_writer(new SS_LogFileWriter('../silverstripe-errors-notices.log'), SS_Log::NOTICE);
