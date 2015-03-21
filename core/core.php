<?
namespace YAMini;
class core
{
    use uri, coreException;
    private static $instance   = null;
    private static $controller = null;
    private static $routers  = [];
    private function __construct()
    {
        self::router('GET/POST/HEAD');
        self::router('PUT/DELETE/TRACE/CONNECT/OPTIONS/PATCH/COPY/LINK/UNLINK/PURGE',null,function(){
            throw new \Exception("Method Not Allowed", 500);
        });
    }

    public static function get_instance()
    {
        if (self::$instance === null) {
            return self::$instance = new self;
        } else {
            return self::$instance;
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

    private function run()
    {
        $routers = self::$routers;

        $request_method = isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:false;

        if ($request_method === false) throw new \Exception("Error Processing REQUEST_METHOD", 500);

        $routers = array_filter($routers, function($router) use ($request_method){
            $select = in_array( strtoupper($request_method) , explode('/',strtoupper($router['method'])) ) || ($router['method'] == '*');
            $select = $select && preg_match('/'.$router['pattern'].'/i', '/'.implode('/', uri::params()) );
            return $select;
        });

        $router = array_pop($routers);

        if (!$router) throw new \Exception("Method Not Allowed", 500);

        self::process($router);

    }


    private function process($router)
    {
        extract($router);

        if (!is_callable($handler)) {
            if (is_string($handler)) {
                $params = array_filter(explode('/', $handler));
            } else {
                $params = uri::params();
            }

            $directory_name = array_shift($params);
            $directory_real = rtrim(CONTROLLER_PATH.$directory_name.DIRECTORY_SEPARATOR,'/').DIRECTORY_SEPARATOR;

            if (!is_dir($directory_real)) {
                array_unshift($params, $directory_name);
                $directory_name = null;
                $directory_real = CONTROLLER_PATH;
            }

            switch (count($params)) {
                case 0:
                    array_push($params, DEFAULT_CONTROLLER,DEFAULT_METHOD);
                    break;
                case 1:
                    array_push($params, DEFAULT_METHOD);
                    break;
                default:
                    $params_chunked = array_chunk($params, 2, false);
                    $params         = array_shift($params_chunked);
                    break;
            }

            $class_name = ucwords(array_shift($params));
            if (!file_exists($directory_real.$class_name.PHP_EXT)) {
                throw new \Exception("Page Not Found", 404);
            }

            $class_real  = str_replace(DIRECTORY_SEPARATOR, '\\', str_replace(APP_PATH, '', $directory_real.$class_name));

            $controller  = $class_real::get_instance();
            self::$controller = $controller;

            $method_name = array_shift($params);
            if (is_callable([$controller, $method_name])){
                $processor = [$controller, $method_name];
            } else {
                throw new \Exception("Page Not Found", 404);
            }

        } else {
            $processor = $router['handler'];
        }

        $args = uri::params(3);
        if(preg_match('/'.$router['pattern'].'/i', uri::request_uri(), $matches )) {
            if(count($matches) >= 2) {
                $request_uri_end = array_pop($matches);
                $args = array_filter(explode('/', $request_uri_end));
            }
        }
        call_user_func_array($processor,$args);
    }

    public static function get_controller_instance()
    {
        return self::$controller;
    }

    public function __destruct()
    {
        try {
            self::run();
        } catch (\Exception $e) {
            coreException::handler($e);
        }
    }

    public function __clone()
    {
        throw new \Exception("Clone Is Not Allowed", 500);
    }


}

