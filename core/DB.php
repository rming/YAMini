<?
namespace YAMini;
use Illuminate\Database\Capsule\Manager as Capsule;
class DB
{
    public function __construct($config = [])
    {
        $capsule = new Capsule;
        $capsule->addConnection($config);
        $capsule->bootEloquent();
        return $capsule;
    }

}
