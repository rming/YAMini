<?
namespace controllers;
use \YAMini\controller as controller;

class Home extends controller
{

    public function index()
    {
        $data = [
            'header'   => ['title' => 'YAMini::Home'],
            'tpl_name' => 'tpl_home_index',
        ];
        self::view('tpl_layout',$data);
        /*
        $me = get_instance();
        //$params = self::params();
        $res = $this->load_model();
        var_dump($res);
        var_dump(self::$models);
        var_dump(static::$models);
        var_dump($me::$models);
        */
    }
    public function hello(){
        echo "hello";
    }

}
