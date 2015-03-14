<?

//production / development
define('ENVIRONMENT',"development");

 //useful path
define('BASE_PATH',         __DIR__.DIRECTORY_SEPARATOR);

define('APP_PATH',          BASE_PATH.'app'.DIRECTORY_SEPARATOR);
define('CONTROLLER_PATH',   APP_PATH.'controllers'.DIRECTORY_SEPARATOR);
define('MODEL_PATH',        APP_PATH.'models'.DIRECTORY_SEPARATOR);
define('VIEW_PATH',         APP_PATH.'views'.DIRECTORY_SEPARATOR);

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

$app = YAMini\core::get_instance();

require("router.php");

function get_instance()
{
    return \YAMini\core::get_controller_instance();
}
