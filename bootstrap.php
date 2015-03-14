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
define('REWRITE_EXT',        '.html');

//Composer autoloader
require BASE_PATH.'/vendor/autoload.php';

//Exception handler
set_exception_handler(['coreException', 'handler']);

trait coreException
{
    public static function handler(Exception $e)
    {
        $code = $e->getCode();
        switch ($code) {
            case 404:
                header("Location: /404");
                break;
            case 405;
                header("Location: /405");
                break;
            default:
                exit($e->getMessage());
                break;
        }
    }

}

class core
{
    use uri;
    private static $instance = null;
    private static $routers  = [];
    private function __construct()
    {
        self::router('GET/POST/HEAD');
        self::router('PUT/DELETE/TRACE/CONNECT/OPTIONS/PATCH/COPY/LINK/UNLINK/PURGE',null,function(){
            throw new Exception("Method Not Allowed", 405);
        });
    }
    public static function get_instance()
    {
        if (self::$instance === null) {
            return self::$instance = new self;
        }
    }

    public function router($method = 'GET/POST/HEAD', $pattern = null, $handler = null)
    {
        self::$routers[] = [
            'method'  => $method,
            'pattern' => $pattern,
            'handler' => $handler,
        ];
        return self::$routers;
    }

    public function run()
    {
        $routers = self::$routers;

        $request_method = isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:false;

        if ($request_method === false) throw new Exception("Error Processing REQUEST_METHOD");

        $routers = array_filter($routers, function($router) use ($request_method){
            $select = in_array( strtoupper($request_method) , explode('/',strtoupper($router['method'])) ) || ($router['method'] == '*');
            $select = $select && preg_match('/'.$router['pattern'].'/i', '/'.implode('/', uri::params()) );
            return $select;
        });

        $router = array_pop($routers);

        if (!$router) throw new Exception("Method Not Allowed", 405);

        self::process($router);

    }


    private function process($router)
    {
        extract($router);

        if (!is_callable($handler)) {
            if (is_string($handler)) {
                $uri_params = array_filter(explode('/', $handler));
            } else {
                $uri_params = uri::params();
            }

            $directory_name       = array_shift($uri_params);
            $directory_real       = CONTROLLER_PATH.$directory_name.DIRECTORY_SEPARATOR;

            if (!is_dir($directory_real)) {
                array_unshift($uri_params, $directory_name);
                $directory_name = null;
                $directory_real = CONTROLLER_PATH;
            }

            switch (count($uri_params)) {
                case 0:
                    array_push($uri_params, DEFAULT_CONTROLLER,DEFAULT_METHOD);
                    break;
                case 1:
                    array_push($uri_params, DEFAULT_METHOD);
                    break;
                default:
                    $uri_params_chunked = array_chunk($uri_params, 2, false);
                    $uri_params         = array_shift($uri_params_chunked);
                    break;
            }

            $class_name = array_shift($uri_params);
            if (!file_exists($directory_real.$class_name.PHP_EXT)) {
                throw new Exception("Page Not Found", 404);
            }

            $class_real  = str_replace(DIRECTORY_SEPARATOR, '\\', str_replace(APP_PATH, '', $directory_real.$class_name));
            $controller  = new $class_real();
            $method_name = array_shift($uri_params);

            if (is_callable([$controller, $method_name])){
                $processor = [$controller, $method_name];
            } else {
                throw new Exception("Page Not Found", 404);
            }

        } else {
            $processor = $router['handler'];
        }

        call_user_func_array($processor,uri::params(2));
    }

    public function __destruct()
    {
        try {
            self::run();
        } catch (Exception $e) {
            coreException::handler($e);
        }
    }


}

trait uri
{
    public static function params($start = -1)
    {
        $request_uri = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:false;
        if ($request_uri===false) {
            throw new Exception("Error Processing REQUEST_URI");
        }
        $request_uri = mb_substr($request_uri, 0, mb_stripos($request_uri, REWRITE_EXT, 0, 'utf-8')?:strlen($request_uri), 'utf-8');
        if (!preg_match('/\/[0-9a-z_~\:\.\-\/]*/i', $request_uri, $matches)) {
            throw new Exception("Error Processing REQUEST_URI");
        } else {
            $params = array_filter(explode('/', trim(array_shift($matches), '/')));
            return array_slice($params,$start-1);
        }
    }

    public static function segment($n = 1)
    {
        $params = self::params();
        $n = is_numeric($n) ? $n-1 : 0;

        return isset($params[$n]) ? $params[$n] : false;
    }

    public static function params_assoc($start = 3)
    {
        $params = self::params();
        if ($start > count($params) || !$params) {
            $params_assoc = [];
        } else {
            $params_origin = $params;
            array_shift($params);
            $params_assoc = [];
            array_map(function($k, $v) use (&$params_assoc){$params_assoc[$k]=$v;}, $params_origin, $params);
            $assoc_index      = array_flip(range($start-1,  2*count($params_origin), 2));
            $assoc_keys       = array_intersect_key(array_keys($params_assoc), $assoc_index);
            $params_assoc = array_intersect_key($params_assoc, array_flip($assoc_keys));
        }

        return $params_assoc;
    }
}

trait load
{
    public static function view($file = null, $data = [], $return = false)
    {
        $view_path = VIEW_PATH.DIRECTORY_SEPARATOR.$file;
        is_array($data)?extract($data):null;
        ob_start();
        include($view_path);
        if ($return) {
            $buffer = ob_get_contents();
            @ob_end_clean();
            return $buffer;
        }
        ob_end_flush();
    }
}

$app = core::get_instance();

require("router.php");

