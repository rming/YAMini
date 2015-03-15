<?
namespace controllers;
use \YAMini\controller as controller;

class Home extends controller
{

    protected function __construct()
    {
    }
    public function index(){
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
        */
    }

    public function test()
    {

        $home_model = $this->load_model('home_model','my_home_model');
        $this->my_home_model->halo();

        //$res = $this->class_loaded();
        //var_dump($res);

        //$home_model_2 = $this->load_model('home_model_2');

        $res = $this->load_model(['home_model','home_model_2','home/home_model_3'],'abc');
        var_dump($res);


        $this->load_lib('eat');
        $this->eat->food('banana');

        $params = self::params();
        var_dump($params);

        $that = get_instance();
        if ($that === $this) {
            var_dump('$that ==== $this');
        }

    }

    public function hello()
    {
        echo "hello";
    }

}
