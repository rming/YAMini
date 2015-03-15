<?
namespace YAMini;
trait loader
{
    protected static $models = [];
    public static function load_view($_files = null, $_data = [], $_return = false)
    {
        $that = get_instance();
        if(!is_array($_files)) {
            $_files = compact('_files');
        }

        $_files = array_map(function($v){
            return rtrim(VIEW_PATH.$v,VIEW_EXT).VIEW_EXT;
        }, $_files);

        is_array($_data)?extract($_data):null;

        ob_start();
        foreach ($_files as $_file) {
            if (file_exists($_file)) {
               include $_file;
            } else {
                throw new \Exception(sprintf("Error Processing View :%s",str_replace(BASE_PATH, '', $_file)),404);
            }
        }

        if ($_return) {
            $buffer = ob_get_contents();
            @ob_end_clean();
            return $buffer;
        }
        ob_end_flush();
    }

    public static function load_model($file = null)
    {
        $model_instance = 'abc';
        if(!in_array($model_instance, static::$models)) {
            static::$models[] = $model_instance;
        }
        return $model_instance;
    }
}
