####YAMini
Yet another mini PHP framework.

####Notice：

- 使用 `Composer` 进行包管理，和 `app` 命名空间管理，下载后执行 `composer update`
- 继承 和 后期静态绑定 实现的 `get_instance()` 获取当前实例 controller 的单例
- trait 和 后期静态绑定 实现的 `load_model` 注册当前 `model` 实例到 `static::$models`

@todo
- load model
- model , library 单例的维护（注册器）
- register object list
- ORM
- basic controller
- site url
