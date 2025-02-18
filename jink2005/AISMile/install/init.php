<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

// Check PHP version
if (version_compare(PHP_VERSION, '5.1.3', '<'))
	die('You need at least PHP 5.1.3 to run MileBiz. Your current PHP version is '.PHP_VERSION);

// Generate common constants
define('PS_INSTALLATION_IN_PROGRESS', true);
define('_PS_INSTALL_PATH_', dirname(__FILE__).'/');
define('_PS_INSTALL_DATA_PATH_', _PS_INSTALL_PATH_.'data/');
define('_PS_INSTALL_CONTROLLERS_PATH_', _PS_INSTALL_PATH_.'controllers/');
define('_PS_INSTALL_MODELS_PATH_', _PS_INSTALL_PATH_.'models/');
define('_PS_INSTALL_LANGS_PATH_', _PS_INSTALL_PATH_.'langs/');
define('_PS_INSTALL_FIXTURES_PATH_', _PS_INSTALL_PATH_.'fixtures/');

require_once(_PS_INSTALL_PATH_ . 'install_version.php');

// we check if theses constants are defined
// in order to use init.php in upgrade.php script
if (!defined('__PS_BASE_URI__'))
	define('__PS_BASE_URI__', substr($_SERVER['REQUEST_URI'], 0, -1 * (strlen($_SERVER['REQUEST_URI']) - strrpos($_SERVER['REQUEST_URI'], '/')) - strlen(substr(dirname($_SERVER['REQUEST_URI']), strrpos(dirname($_SERVER['REQUEST_URI']), '/') + 1))));

if (!defined('_THEME_NAME_'))
	define('_THEME_NAME_', 'default');

require_once(dirname(_PS_INSTALL_PATH_).'/config/defines.inc.php');
require_once(dirname(_PS_INSTALL_PATH_).'/config/defines_uri.inc.php');

// MileBiz autoload is used to load some helpfull classes like Tools.
// Add classes used by installer bellow.
require_once(_PS_ROOT_DIR_.'/config/autoload.php');
require_once(_PS_ROOT_DIR_.'/config/alias.php');
require_once(_PS_INSTALL_PATH_.'classes/exception.php');
require_once(_PS_INSTALL_PATH_.'classes/languages.php');
require_once(_PS_INSTALL_PATH_.'classes/language.php');
require_once(_PS_INSTALL_PATH_.'classes/model.php');
require_once(_PS_INSTALL_PATH_.'classes/session.php');
require_once(_PS_INSTALL_PATH_.'classes/sqlLoader.php');
require_once(_PS_INSTALL_PATH_.'classes/xmlLoader.php');
require_once(_PS_INSTALL_PATH_.'classes/simplexml.php');

@set_time_limit(0);
if (!@ini_get('date.timezone'))
	@date_default_timezone_set('UTC');

// Try to improve memory limit if it's under 32M
if (psinstall_get_memory_limit() < psinstall_get_octets('32M'))
	ini_set('memory_limit', '32M');

function psinstall_get_octets($option)
{
	if (preg_match('/[0-9]+k/i', $option))
		return 1024 * (int)$option;

	if (preg_match('/[0-9]+m/i', $option))
		return 1024 * 1024 * (int)$option;

	if (preg_match('/[0-9]+g/i', $option))
		return 1024 * 1024 * 1024 * (int)$option;

	return $option;
}

function psinstall_get_memory_limit()
{
	$memory_limit = @ini_get('memory_limit');
	
	if (preg_match('/[0-9]+k/i', $memory_limit))
		return 1024 * (int)$memory_limit;
	
	if (preg_match('/[0-9]+m/i', $memory_limit))
		return 1024 * 1024 * (int)$memory_limit;
	
	if (preg_match('/[0-9]+g/i', $memory_limit))
		return 1024 * 1024 * 1024 * (int)$memory_limit;
	
	return $memory_limit;
}