<?php


namespace app\admin\model;


use think\Db;

class LoginDb
{

    private static $table = "user";

    public static function checkUserByUsername($username)
    {
        $result = Db::table(self::$table)
                    ->where(['email'=>$username])
                    ->find();
        if(!$result) {
            return false;
        }
        return $result;
    }

    public static function insertUser($data) {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $res = Db::table(self::$table)->insert([
            'email'     =>  $data['email'],
            'nickname'  =>  $data['nickname'],
            'password'  =>  $password,
            'create_time'   =>  date('Y-m-d H:i:s', time())
        ]);
        if($res) return true;
        returnAjax("fail to register", false);
    }

    public static function updateUserPassword($userId, $password)
    {
        $result = Db::table(self::$table)->where('id', $userId)->update(['password'=>$password]);
        if($result) return true;
        returnAjax("Password change failed！", false);
    }
}