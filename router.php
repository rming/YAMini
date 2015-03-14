<?

/*
$app->router('GET','^\/$', function(){
    echo "hello world!";
});

$app->router('GET','^\/halo$', function(){
    echo "halo word!";
});


$app->router('GET','^\/welcome\/(\d+)$', function($ss,$name){
    printf("welcome %s! \n%s",$name,date('Y-m-d H:i:s'));
});

$app->router('GET','^\/who(.*)$', '/home');

$app->router('GET','^\/home(.*)$', '/home/index');
*/


/*
$app->router('GET','^\/(.*)$', function(){
    echo "site cloesed!";
});
*/


/**
 *
 * $app->router($method = 'GET/POST/HEAD',$pattern = null, $handler = null)
 * $handler = String/Closure
 * 地址重写前 ，args 默认从第三段 uri 开始，重写后，从匹配项后 开始
 *
 * coreException::show_error($data);
 *
 * uri::params_assoc($start = 3)
 * uri::params($n = false)
 * uri::segment($start = 1)
 *
 * load::view($file = null,$data = [],$return = false)
 *
 *
 */
