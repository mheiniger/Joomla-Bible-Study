<?php
/**
 * Bible Study Defines
 *
 * @package        BibleStudy.Admin
 * @copyright  (C) 2007 - 2014 Joomla Bible Study Team All rights reserved
 * @license        http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link           http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;

// Version information
define('BIBLESTUDY_VERSION', '9.0.0');
define('BIBLESTUDY_VERSION_DATE', '2015-01-01');
define('BIBLESTUDY_VERSION_BUILD', '4000');
define('BIBLESTUDY_VERSION_UPDATEFILE', 'JBS Version ' . BIBLESTUDY_VERSION);

// Default values
define('BIBLESTUDY_COMPONENT_NAME', 'com_biblestudy');
define('BIBLESTUDY_LANGUAGE_DEFAULT', 'english');
define('BIBLESTUDY_TEMPLATE_DEFAULT', 'default');

// File system paths
define('BIBLESTUDY_COMPONENT_RELPATH', 'components' . DIRECTORY_SEPARATOR . BIBLESTUDY_COMPONENT_NAME);

// Root system paths
define('BIBLESTUDY_ROOT_PATH', JPATH_ROOT);
define('BIBLESTUDY_ROOT_PATH_ADMIN', JPATH_ADMINISTRATOR);
define('BIBLESTUDY_MEDIA_PATH', JPATH_ROOT . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_biblestudy');
define('BIBLESTUDY_PATH_IMAGES', BIBLESTUDY_MEDIA_PATH . DIRECTORY_SEPARATOR . 'images');

// Site Component paths
define('BIBLESTUDY_PATH', JPATH_SITE . DIRECTORY_SEPARATOR . BIBLESTUDY_COMPONENT_RELPATH);
define('BIBLESTUDY_PATH_LIB', BIBLESTUDY_PATH . DIRECTORY_SEPARATOR . 'lib');
define('BIBLESTUDY_PATH_TEMPLATE', BIBLESTUDY_PATH . DIRECTORY_SEPARATOR . 'template');
define('BIBLESTUDY_PATH_TEMPLATE_DEFAULT', BIBLESTUDY_PATH_TEMPLATE . DIRECTORY_SEPARATOR . BIBLESTUDY_TEMPLATE_DEFAULT);
define('BIBLESTUDY_PATH_HELPERS', BIBLESTUDY_PATH . DIRECTORY_SEPARATOR . 'helpers');

// Admin Component paths
define('BIBLESTUDY_PATH_ADMIN', BIBLESTUDY_ROOT_PATH_ADMIN . DIRECTORY_SEPARATOR . BIBLESTUDY_COMPONENT_RELPATH);
define('BIBLESTUDY_PATH_ADMIN_LIB', BIBLESTUDY_PATH_ADMIN . DIRECTORY_SEPARATOR . 'lib');
define('BIBLESTUDY_PATH_ADMIN_HELPERS', BIBLESTUDY_PATH_ADMIN . DIRECTORY_SEPARATOR . 'helpers');
define('BIBLESTUDY_PATH_ADMIN_LANGUAGE', BIBLESTUDY_PATH_ADMIN . DIRECTORY_SEPARATOR . 'language');
define('BIBLESTUDY_PATH_ADMIN_INSTALL', BIBLESTUDY_PATH_ADMIN . DIRECTORY_SEPARATOR . 'install');
define('BIBLESTUDY_PATH_ADMIN_IMAGES', BIBLESTUDY_PATH_ADMIN . DIRECTORY_SEPARATOR . 'images');

// Addons paths
define('BIBLESTUDY_PATH_ADMIN_ADDON', BIBLESTUDY_PATH_ADMIN . DIRECTORY_SEPARATOR . 'addons');

define('BIBLESTUDY_FILE_INSTALL', BIBLESTUDY_PATH_ADMIN . DIRECTORY_SEPARATOR . 'biblestudy.xml');

// Mod Biblestudy
define('BIBLESTUDY_PATH_MOD', BIBLESTUDY_ROOT_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'mod_biblestudy');

// Minimum version requirements
DEFINE('BIBLESTUDY_MIN_PHP', '5.3.10');
DEFINE('BIBLESTUDY_MIN_MYSQL', '5.1');

// Time related
define('BIBLESTUDY_SECONDS_IN_HOUR', 3600);
define('BIBLESTUDY_SECONDS_IN_YEAR', 31536000);

// Database defines
define('BIBLESTUDY_DB_MISSING_COLUMN', 1054);
