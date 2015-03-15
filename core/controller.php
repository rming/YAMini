<?
namespace YAMini;
class Controller
{
    use uri, loader;
    protected static $instance = null;
    private function __construct()
    {
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
