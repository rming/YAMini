<?
namespace YAMini;
trait uri
{
    public static function request_uri()
    {
        $request_uri = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:false;
        if ($request_uri===false) {
            throw new Exception("Error Processing REQUEST_URI");
        } else {
            return $request_uri;
        }
    }
    public static function params($start = 1)
    {
        $request_uri = self::request_uri();
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
            $assoc_index  = array_flip(range($start-1,  2*count($params_origin), 2));
            $assoc_keys   = array_intersect_key(array_keys($params_assoc), $assoc_index);
            $params_assoc = array_intersect_key($params_assoc, array_flip($assoc_keys));
        }

        return $params_assoc;
    }
}
