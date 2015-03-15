<?
namespace YAMini;
trait coreException
{
    public static function handler(\Exception $e)
    {
        $data = [
            'code'    => $e->getCode(),
            'message' => $e->getMessage(),
            'title'   => 'Oops...',
        ];
        header("HTTP/1.1 {$data['code']} {$data['message']}");
        self::show_error($data);
    }

    public static function show_error($data)
    {
        $error_tpl      = "error/error.php";
        $error_code_tpl = sprintf("error/%d.php",$data['code']);
        if(file_exists(VIEW_PATH.$error_code_tpl)) {
            $error_tpl = $error_code_tpl;
        }
        loader::load_view($error_tpl,$data);
    }
}
