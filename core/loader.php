<?
namespace YAMini;
trait loader
{
    protected static $_classes = [];

    public static function config($_config = null)
    {
        $config_file = CONFIG_PATH.$_config.PHP_EXT;
        if(!file_exists($config_file)) {
            throw new \Exception(sprintf("Config Not Found [ %s ]",$_config.PHP_EXT), 500);
        } else {
            return (require CONFIG_PATH.$_config.PHP_EXT);
        }
    }

    public static function load_db($_config = null, $_alias = null)
    {
        if(!$_config) {
            $_config = DB_DEFAULT_GROUP;
        }

        if (!is_string($_config)) {
            throw new \Exception("Database config Error", 500);
        }

        $db_config = self::config(DB_CONFIG_FILE);
        $db_config = current(array_intersect_key($db_config,[$_config=>null]));
        $_classes  = [$_config=>'YAMini\DB'];

        return self::load($_classes, $_alias, $db_config);
    }

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
                throw new \Exception(sprintf("View Not Found [ %s ]",str_replace(BASE_PATH, '', $_file)),500);
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
        $_files = self::files_path($_files, MODEL_PATH, PHP_EXT);
        $_classes = array_map(function($_file){
            return str_replace([APP_PATH,'/'],['','\\'],rtrim($_file, PHP_EXT));
        }, $_files);
        return self::load($_classes, $_alias);
    }

    public static function load_lib($_files = null, $_alias = null)
    {

        $_files   = self::files_path($_files, LIB_PATH, PHP_EXT);
        $_classes = array_map(function($_file){
            return str_replace([APP_PATH,'/'],['','\\'],rtrim($_file, PHP_EXT));
        }, $_files);

        return self::load($_classes, $_alias);
    }
    /**
     * 类加载工具
     * @param  array                 $_classes     完整类名 数组
     * @param  String                $_alias       类实例化后的别名 （仅对 $_classes 数量为一 时有效）
     * @param  String|Array          $_config      类实例化参数    （仅对 $_classes 数量为一 时有效）
     * @return Object|Object Array   $class_instances  实例化后对象（数组）
     */
    public static function load($_classes, $_alias = null, $_config = null)
    {

        $is_single = count($_classes) === 1;

        $class_instances = [];
        foreach ($_classes as $key => $_class) {
            $_config       = $is_single && $_config ? $_config : null;
            $instance_name = $is_single && is_string($_alias) && $_alias ? $_alias : $key ;
            if (!class_exists($_class)) {
                throw new \Exception(sprintf("Class Not Found [ %s ]", $_class), 500);
            }

            if (in_array($instance_name, static::$_classes)) {
                $class_instance = static::$_classes[$instance_name];
            } else {
                $class_instance = self::factory(static::$_classes, $_class, $instance_name, $_config);
            }
            $class_instances[$instance_name] = $class_instance;
        }
        return count($class_instances) === 1 ? current($class_instances) : $class_instances;
    }

    /**
     * class 实例化工厂类
     * @param  static::$property $register       私有属性，类的注册表
     * @param  String            $class          类名
     * @param  String            $instance_name  注册名
     * @param  Array|String      $config         实例化配置参数
     *
     * @return Object|flase      $class_instance 实例化对象
     */
    private static function factory(&$register = null, $class = null, $instance_name = null, $config = null)
    {
        if($config) {
            $class_instance = new $class($config);
        } else {
            $class_instance = new $class;
        }

        if ($class_instance) {
            $register[$instance_name] = $class_instance;
        }
        return $class_instance;
    }
    /**
     * 获取 view , model , library 文件路径地址
     * @param  null|String|Array $files   文件名(列表)
     * @param  String            $_prefix 文件路径前缀
     * @param  String            $_ext    文件扩展名
     *
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
