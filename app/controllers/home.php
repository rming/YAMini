<?
namespace controllers;

class home
{
    use \uri;
    public function __construct()
    {
    }

    public function index($name,$age)
    {
        echo "I am $name , $age";
    }

}
