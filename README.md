###Description

Yet another mini PHP framework.

###Feature：

- 使用 `Composer` 进行包管理，和 `app` 命名空间管理，下载后执行 `composer update`
- 继承 和 后期静态绑定 实现的 `get_instance()` 获取当前实例 controller 的单例
- trait 和 后期静态绑定 实现的 `load_model` 注册当前 `model` 实例到 `static::$models`


###TOC

[TOC]

###Documentation

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


@todo
- load model
- model , library 单例的维护（注册器）
- register object list
- ORM
- basic controller
- site url
