<?
/**
 * ENVIRONMENT
 * production / development
 */
define('ENVIRONMENT',"development");

/**
 * BASE_PATH & APP_PATH
 */
define('BASE_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);

define('APP_PATH',BASE_PATH.'app'.DIRECTORY_SEPARATOR);

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
        throw new Exception("Error ENVIRONMENT Const");
        break;
}

/**
 * autolader
 */
/*
spl_autoload_register(function($class_name){
    var_dump($class_name);
});
*/
/**
 * segment
 */
function uri_param($n = false)
{
    $request_uri = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:false;
    if($request_uri===false) {
        throw new Exception("Error REQUEST_URI");
    }

    if(!preg_match('/\/[0-9a-z_~\:\.\-\/]*/i',$request_uri,$matches)) {
        throw new Exception("Error REQUEST_URI");
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

function uri_param_assoc($start = 1){
    $uri_params = uri_param();
    if($start > count($uri_params) || !$uri_params) {
        $uri_params_assoc = [];
    } else {
        $max = 2*count($uri_params);
        //$start >= $max ? : ;
        $keys   = array_intersect_key($uri_params,array_flip(range($start-1, $max,2)));
        $values = array_intersect_key($uri_params,array_flip(range($start, $max,2)));
        $max_count        = max(count($keys),count($values));
        $uri_params_assoc = array_combine(array_pad($keys,$max_count,null),array_pad($values,$max_count,null));
    }

    return $uri_params_assoc;
}

/**
 * router
 *
 * [0-9a-z_~:.-/]
 * characters permitted in uri
 */
function router($method = 'POST/GET',$patterns = null,callable $handler = null)
{
    $request_method = isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:false;
    if($request_method === false) {
        throw new Exception("Error REQUEST_METHOD");
    }
    if(in_array( strtoupper($request_method) , explode('/',strtoupper($method)) )) {
        $uri_params = uri_param_assoc(1);
        var_dump($uri_params);
        if($patterns === null) {
            call_user_func_array($handler, ['']);
        } else {

        }
    }

}

router('GET','/',function(){
    echo "hello world!";
});







