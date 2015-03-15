<?
namespace YAMini;
trait loader
{
    protected static $_classes   = [];

    public static function load_view($_files = null, $_data = [], $_return = false)
    {
        $that   = get_instance();
        $_files = self::files_path($_files, VIEW_PATH, VIEW_EXT);
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

    public static function class_loaded(){
        return static::$_classes;
    }

    public static function is_loaded($class){
        if (isset(static::$_classes[$class])){
            return static::$_classes[$class];
        }
        return FALSE;
    }

    public static function load_model($_files = null, $_alias = null)
    {
        return self::load($_files, $_alias, MODEL_PATH);
    }

    public static function load_lib($_files = null, $_alias = null)
    {
        return self::load($_files, $_alias, LIB_PATH);
    }

    public static function load($_files = null, $_alias = null, $_prefix = null)
    {
        if (!$_prefix) {
            $_prefix = LIB_PATH;
        }

        $_files = self::files_path($_files, $_prefix, PHP_EXT);
        if (!$_files) {
            return false;
        }

        if (count($_files) > 1) {
            $model_instances = [];
            foreach ($_files as $k => $_file) {
                if (in_array($k, static::$_classes)) {
                    break;
                } else {
                    $model_instance = self::factory(static::$_classes, $_file, $k);
                    $model_instances[$k] = $model_instance;
                }
            }
            return $model_instances;
        } else {
            $key   = key($_files);
            $_file = current($_files);
            $model_key = is_string($_alias) ? $_alias : $key ;
            if (in_array($model_key,static::$_classes)) {
                return static::$_classes[$model_key];
            } else {
                $model_instance = self::factory(static::$_classes, $_file, $model_key);
                return $model_instance;
            }
        }
    }
    /**
     * class 实例化工厂类
     * @param  static::$property $register       私有属性，类的注册表
     * @param  String            $_file          经过 self::files_path 处理过的完整真实地址
     * @param  String            $key            注册名
     * @return Object|flase      $class_instance 实例化对象
     */
    private static function factory(&$register = null, $_file = null, $key = null)
    {
        $class_name = str_replace([APP_PATH,'/'],['','\\'],rtrim($_file, PHP_EXT));
        $class_instance = new $class_name;
        if ($class_instance) {
            $register[$key] = $class_instance;
        }
        return $class_instance;
    }
    /**
     * 获取 view , model , library 文件路径地址
     * @param  null|String|Array $files   文件名(列表)
     * @param  String            $_prefix 文件路径前缀
     * @param  String            $_ext    文件扩展名
     * @return Array             $files   文件路径列表
     */
    private static function files_path($_files = null, $_prefix = '', $_ext = '')
    {
        $_files = is_object($_files) ? (array)$_files : $_files;
        $_files = is_array($_files)  ? $_files        : compact('_files');
        $_files = array_filter($_files);

        $ret_files = [];
        foreach ($_files as $_file) {
            $ret_files[$_file] = rtrim($_prefix.$_file, $_ext).$_ext;
        }

        return $ret_files;
    }
}
