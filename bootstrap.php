<?

//production / development
define('ENVIRONMENT',"development");

 //useful path
define('BASE_PATH',         __DIR__.DIRECTORY_SEPARATOR);

define('APP_PATH',          BASE_PATH.'app'.DIRECTORY_SEPARATOR);
define('CONFIG_PATH',       APP_PATH.'config'.DIRECTORY_SEPARATOR);
define('CONTROLLER_PATH',   APP_PATH.'controllers'.DIRECTORY_SEPARATOR);
define('MODEL_PATH',        APP_PATH.'models'.DIRECTORY_SEPARATOR);
define('LIB_PATH',          APP_PATH.'libs'.DIRECTORY_SEPARATOR);
define('VIEW_PATH',         APP_PATH.'views'.DIRECTORY_SEPARATOR);

//database config
define('DB_CONNECT',         true);
define('DB_CONFIG_FILE',     'database');
define('DB_DEFAULT_GROUP',   'default');

//uri config
define('DEFAULT_CONTROLLER', 'home');
define('DEFAULT_METHOD',     'index');
define('PHP_EXT',            '.php');
define('VIEW_EXT',           '.php');
define('REWRITE_EXT',        '.html');

//Composer autoloader
require BASE_PATH.'/vendor/autoload.php';

//Exception handler
set_exception_handler(['YAMini\\coreException', 'handler']);

$_app = \YAMini\core::get_instance();

require CONFIG_PATH."router.php";

function get_instance()
{
    return \YAMini\core::get_controller_instance();
}

