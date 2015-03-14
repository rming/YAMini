<?
$app->router('*','^\/404$', function(){
    header("HTTP/1.1 404 Not Found");
    load::view('404.php', ['title'=>'Oops...']);
});

$app->router('*','^\/405$', function(){
    header("HTTP/1.1 405 Method Not Allowed");
    echo "405 Page Not Found";
});


$app->router('GET','^\/$', function(){
    echo "hello world!";
});

$app->router('GET','^\/halo$', function(){
    echo "halo word!";
});


$app->router('GET','^\/welcome', function($ss,$name){
    printf("welcome %s! \n%s",$name,date('Y-m-d H:i:s'));
});

$app->router('GET','^\/who(.*)$', '/home');

$app->router('GET','^\/home(.*)', '/home/index');



/*
$app->router('GET','^\/(.*)$', function(){
    echo "site cloesed!";
});
*/


/**
 * $app->router($method = 'GET/POST/HEAD',$pattern = null, $handler = null)
 * $handler = String/Closure
 * uri::params_assoc($start = 3)
 * uri::params($n = false)
 * uri::segment($start = 1)
 *
 * load::view($file = null,$data = [],$return = false)
 *
 *
 */
