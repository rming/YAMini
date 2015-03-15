<?
namespace controllers;
use \YAMini\controller as controller;

class Home extends controller
{

    protected function __construct()
    {
    }
    public function index()
    {

/*
        $home_model = $this->load_model('home_model','my_home_model');
        $res = $this->class_loaded();
        $this->my_home_model->halo();

        $home_model_2 = $this->load_model('home_model_2');

        $res = $this->load_model(['home_model','home_model_2','home/home_model_3'],'abc');
        var_dump($res);
*/
/*

        $this->load_lib('eat');
        $this->eat->food('banana');
*/
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

        $that = get_instance();

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
