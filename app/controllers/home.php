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

        $views = [
            'tpl_header',
            'tpl_home_index',
            'tpl_footer',
        ];
        $this->load_view($views,$data);
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

    public function hello()
    {
        echo "hello";
    }

}
