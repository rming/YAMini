<?
namespace models;
use Illuminate\Database\Eloquent\Model as Model;
class User_model extends Model{

    protected $table = 'users';

    public function halo()
    {
        var_dump('I am User_model');
    }
}
