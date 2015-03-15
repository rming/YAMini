<?
namespace controllers;
use \YAMini\controller as controller;

class Home extends controller
{

    public function index()
    {
        $that = get_instance();

        $data = [
            'title'    => 'YAMini::Home',
            'tpl_name' => 'tpl_home_index',
        ];


        self::load_view('tpl_layout',$data);
        /*

        $this->load_view('tpl_header',$data);
        $this->load_view('tpl_home_index',$data);
        $this->load_view('tpl_footer',$data);

        //$params = self::params();
        /*
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
