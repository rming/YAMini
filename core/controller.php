<?
namespace YAMini;
class Controller
{
    use uri, loader;
    protected static $instance = null;
    private function __construct()
    {
    }

    public function __get($property_name){
        if (isset(static::$_classes[$property_name])) {
            return static::$_classes[$property_name];
        } else {
            return null;
        }
    }


    public static function get_instance()
    {
        if (static::$instance === null) {
            return static::$instance = new static;
        } else {
            return static::$instance;
        }
    }

}
