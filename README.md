
<img src="http://ww4.sinaimg.cn/large/005wzwuKgw1eq6ofm37yfj30lo08iwep.jpg">
###Description

Yet another mini PHP framework.

两个周末，完成了MVC的基本框架，然后加上ORM（或者不加），就可以跑了。

框架是`mini`的，但是未必是高效的，主要本人编码太渣，纯粹练手。

###Feature：

- 使用 `Composer` 进行包管理，和 `app` 命名空间管理，详细见`composer.json`
- `\YAMini\core`是主流程，主要部分 trait `loader`,`uri`,`coreException`
- 全局函数`get_instance()`获取当前控制器单例（如果是直接跑的路由`callback`那这个就没意义了）
- `\YAMini\controller`中插入了trait `\YAMini\loader` 和 `\YAMini\uri`
- `\YAMini\loader`中实现了加载类实例化工厂，基于static后期绑定的注册器
- 代码中使用最多的 `traits` 和 `satic`


###Documentation
####起步

下载解压，打开目录，安装`composer`，执行 `composer update`，生成`autoloader` 和 安装依赖（目前没有依赖），配置`rewrite`。

`REWRITE`通用写法：

    if (-f $request_filename/index.html){
        rewrite (.*) $1/index.html break;
    }
    if (-f $request_filename/index.php){
        rewrite (.*) $1/index.php;
    }
    if (!-f $request_filename){
        rewrite (.*) /index.php;
    }
**测试**

http://yourdomain.com/
http://yourdomain.com/home/test
http://yourdomain.com/home/halo


####路由配置
文件：`router.php`
如果没有任何配置，路由默认会按照`URI`请求`controller::method()`，默认`controller`和默认`method`在`bootstrap`中进行配置。

示例:

`/home`      => `controllers\Home::index()`
`/home/halo` => `controllers\Home::halo()`
`/home/abcde7834fghi` => `new \Exception('Page Not Found',404)`

路由配置举例（匹配规则比较糙）：

    /**
     * 路由设置
     * @param string|null           $method  GET/POST/HEAD/OPTION...* 任选
     * @param string|null           $pattern 正则表达式
     * @param callable|null|string  $handler callback function or URI Rewrite
     */
    $app->router($method = 'GET/POST/HEAD',$pattern = null, $handler = null)

    //根目录
    $app->router('GET/POST','^\/$', function(){
        echo "Home Page!";
    });

    //匹配所有路径
    $app->router('*','^\/(.*)$', function(){
        echo "Site Cloesed!";
    });

    //REWRITE /home/数字 到 /home/index/数字
    $app->router('GET','^\/home\/(\d+)$', '/home/index');

    //匹配 /homework  /home* 的
    $app->router('GET','^\/who(.*)$', '/home');

    //默认参数，控制器中同理
    $app->router('GET','^\/home\/welcome\/(.*)$', function($name){
        printf("welcome %s! \n%s",$name,date('Y-m-d H:i:s'));
    });



####控制器基本写法
**简介**
控制器需要在 `app/controllers` 目录下，可以继承（或者不继承）`\YAMini\controller（代码在 `/core` 下，在composer.json中有 PSR-4配置 ）

    //controllers/home.php
    namespace controllers;
    use \YAMini\controller as controller;
    class Home extends controller
    {
        public function index()
        {
            $data = [
                'title'    => 'YAMini::Home',
                'tpl_name' => 'tpl_home_index',
            ];

            $this->load_view('tpl_layout',$data);
        }
    }

####视图的加载方式

##### 1.使用「布局模板文件」加载
在控制器中使用 `$this->load_view()`，然后在 `layout` 中使用 `include`

        //controllers/home.php
        namespace controllers;
        use \YAMini\controller as controller;
        class Home extends controller
        {
            public function index()
            {
                $data = [
                    'title'    => 'YAMini::Home',
                    'tpl_name' => 'tpl_home_index',
                ];

                $this->load_view('tpl_layout',$data);
            }
        }

        //views/tpl_layout.php
        <?include 'tpl_header.php';?>
        <?include rtrim($tpl_name,VIEW_EXT).VIEW_EXT;?>
        <?include 'tpl_footer.php';?>

##### 2.在控制器中「加载多个视图」

在控制器中通过多次 `load_view()` 加载多个视图

        //controllers/home.php
        namespace controllers;
        use \YAMini\controller as controller;
        class Home extends controller
        {
            public function index()
            {
                $data = [
                    'title'    => 'YAMini::Home',
                    'tpl_name' => 'tpl_home_index',
                ];

                $views = [
                    'tpl_header',
                    'tpl_home_index',
                    'tpl_footer',
                ];
                $this->load_view($views,$data);
                /*
                 * 另外一种繁琐的写法
                 * $this->load_view('tpl_header'     ,$data);
                 * $this->load_view('tpl_home_index' ,$data);
                 * $this->load_view('tpl_footer'     ,$data);
                */
            }
        }

####模板

开什么玩笑，PHP不就是写前端的么

####模型创建

@todo ORM 或者 PDO

####常见代码


    /**
     * controller
     */
    //trait loader
    //load view
    loader::load_view($files = null,$data = [],$return = false);
    $this->load_view('tpl_home_index');
    $this->load_view(['tpl_header','tpl_home','tpl_footer']);

    //load model
    //$_files 可以为数组，此时别名设置无效
    $this->load_model($_files = null, $_alias = null);
    $this->load_lib($_files = null, $_alias = null);

    //class loaded
    $this->class_loaded();
    $this->is_loaded($class);

    //trait uri
    //uri array
    $this->params($start = 1)
    //uri associated array
    $this->params_assoc($start = 3)
    //uri segment
    $this->segment($n = 1)
    //uri string
    $this->request_uri();

    //view
    //view 下的 $that 是 get_instance() 获得的实例
    $that->load_view('tpl_home_index');

    //lib
    $that = get_instance();

    //默认参数
    地址重写前 ，args 默认从第三段 uri 开始，重写后，从匹配项后 开始。



##TODO
- trait url
- ORM
