<?
/**
 * ENVIRONMENT
 * production / development
 */
define('ENVIRONMENT',"development");

/**
 * BASE_PATH & APP_PATH
 */
define('BASE_PATH' , dirname(__FILE__).DIRECTORY_SEPARATOR);

define('APP_PATH'  , BASE_PATH.'app'.DIRECTORY_SEPARATOR);

define('DEFAULT_CONTROLLER' ,'home');
define('DEFAULT_METHOD'     ,'index');
define('PHP_EXT'            ,'.php');

/**
 * exception_handler
 */
set_exception_handler(function(Exception $e){
    exit($e->getMessage());
});

/**
 * whether dispay errors base on ENVIRONMENT
 */
switch (ENVIRONMENT) {
    case 'production':
        ini_set("display_errors", "off");
        error_reporting(0);
        break;

    case 'development':
        ini_set("display_errors", "On");
        error_reporting(E_ALL);
        break;

    default:
        throw new Exception("Error Processing ENVIRONMENT");
        break;
}

/**
 * @var $router
 */
$routers = [];
/**
 * autolader
 */
spl_autoload_register(function($class_name){
    require(BASE_PATH.str_replace('\\','/',$class_name).PHP_EXT);
});

/**
 * uri_param
 */
function uri_param($n = false)
{
    $request_uri = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:false;
    if($request_uri===false) {
        throw new Exception("Error Processing REQUEST_URI");
    }

    if(!preg_match('/\/[0-9a-z_~\:\.\-\/]*/i',$request_uri,$matches)) {
        throw new Exception("Error Processing REQUEST_URI");
    } else {
        $uri_params = array_filter(explode('/',trim(array_shift($matches),'/')));
        if(is_numeric($n)) {
            $n = $n-1;
            if(isset($uri_params[$n])) {
                return $uri_params[$n];
            } else {
                return false;
            }
        } else {
            return $uri_params;
        }
    }
}
/**
 * uri_param_assoc
 */
function uri_param_assoc($start = 1){
    $uri_params = uri_param();
    if($start > count($uri_params) || !$uri_params) {
        $uri_params_assoc = [];
    } else {
        $uri_params_origin = $uri_params;
        array_shift($uri_params);
        $uri_params_assoc = [];
        array_map(function($k,$v) use (&$uri_params_assoc){$uri_params_assoc[$k]=$v;},$uri_params_origin,$uri_params);
        $assoc_index      = array_flip(range($start-1, 2*count($uri_params_origin),2));
        $assoc_keys       = array_intersect_key(array_keys($uri_params_assoc),$assoc_index);
        $uri_params_assoc = array_intersect_key($uri_params_assoc,array_flip($assoc_keys));
    }

    return $uri_params_assoc;
}
/**
 *
 *router
 *
 */
function router($method = 'POST/GET',$pattern = null,callable $handler = null){
    global $routers;
    $routers[] = [
        'method'   => $method,
        'pattern' => $pattern,
        'handler' => $handler,
    ];
}

/**
 * process
 *
 * [0-9a-z_~:.-/]
 * characters permitted in uri
 */
function process($router)
{
    global $instance;
    extract($router);
    $uri_params = uri_param();
    //directory
    $directory_name       = array_shift($uri_params);
    $directory_controller = APP_PATH.'controller'.DIRECTORY_SEPARATOR;
    $directory_real       = $directory_controller.$directory_name.DIRECTORY_SEPARATOR;

    if(!is_dir($directory_real)) {
        array_unshift($uri_params,$directory_name);
        $directory_name = null;
        $directory_real = $directory_controller;
    }
    //补全默认controller
    switch (count($uri_params)) {
        case 0:
            array_push($uri_params,DEFAULT_CONTROLLER,DEFAULT_METHOD);
            break;
        case 1:
            array_push($uri_params,DEFAULT_METHOD);
            break;
        default:
            $uri_params_chunked = array_chunk($uri_params,2,false);
            $uri_params         = array_shift($uri_params_chunked);
            break;
    }
    //实例化controller
    $class_name = array_shift($uri_params);
    if(!file_exists($directory_real.$class_name.PHP_EXT)) {
        throw new Exception("Error Processing Controller");
    }
    $class_real = str_replace(DIRECTORY_SEPARATOR,'\\', str_replace(BASE_PATH, '', $directory_real.$class_name));
    $instance = new $class_real();
    //执行method
    $method_name = array_shift($uri_params);
    if($pattern === null) {
        $instance->$method_name();
    } else {
        call_user_func_array($handler,uri_param_assoc());
    }
}

function run()
{
    global $routers;

    $request_method = isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:false;
    if($request_method === false) {
        throw new Exception("Error Processing REQUEST_METHOD");
    }

    $routers = array_filter($routers,function($router) use ($request_method){
        $select = true;
        $select = $select && in_array( strtoupper($request_method) , explode('/',strtoupper($router['method'])) );
        $select = $select && preg_match('/'.$router['pattern'].'/i', '/'.implode('/', uri_param()) );
        return $select;
    });

    $router = array_pop($routers);
    if(is_callable($router['handler'])) {
        call_user_func($router['handler']);
    } else {
        process($router);
    }

}

router('GET/POST');

router('GET','^\/$',function(){
    echo "halo world!";
});

router('GET','^\/wellcome$',function(){
    echo "wellcome!";
});

run();

/**
 * $instance
 * $routers
 *
 *
 * router()
 * uri_param_assoc()
 * uri_param()
 *
 * @todo
 *
 * load_view()
 * $e->getCode() => router -> load_view();
 *
 *
 */




