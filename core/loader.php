<?
namespace YAMini;
trait loader
{
    protected static $models = [];
    public static function load_view($file = null, $data = [], $return = false)
    {
        $that      = get_instance();
        $view_path = VIEW_PATH.DIRECTORY_SEPARATOR.$file;
        is_array($data)?extract($data):null;

        ob_start();
        if (file_exists($view_path.VIEW_EXT)) {
            include $view_path.VIEW_EXT;
        } elseif (file_exists($view_path)) {
            include $view_path;
        } else {
            throw new Exception("Error Processing View :$view_path");
        }

        if ($return) {
            $buffer = ob_get_contents();
            @ob_end_clean();
            return $buffer;
        }
        ob_end_flush();
    }
    public static function load_model()
    {
        $model_instance = 'abc';
        if(!in_array($model_instance, static::$models)) {
            static::$models[] = $model_instance;
        }
        return $model_instance;
    }
}
