<?php


namespace app\admin\model;

use think\Db;
use app\admin\controller\BaseController;
use think\facade\Session;

class UserDb
{
    private static $table = "user";

    public static function createUser($param)
    {
        // 加密员工密码
        $password = password_hash($param['password'], PASSWORD_DEFAULT);
        $result = Db::table(self::$table)->insert([
            'nickname'      =>  $param['nickname'],
            'username'      =>  $param['username'],
            'password'      =>  $password,
            'roleId'        =>  $param['role'],
            'create_time'   =>  date('Y-m-d H:i:s')
        ]);

        if($result) {
            return true;
        }

        returnAjax("员工添加失败！", false);
    }

    public static function getUserList($page = 1, $search = null)
    {
        $level = Session::get('level');
        $where[] = ['b.level', '<=', $level];
        if(!empty($search)) $where[] = ['a.nickname', 'like', '%'.$search.'%'];
        $result = Db::table(self::$table)->alias('a')
                ->leftJoin('role b', 'a.roleId = b.id')
                ->where($where)
                ->order('b.level','DESC')
                ->field('a.*, b.role_name, b.level')
                ->paginate(10, false, ['page'=>$page]);
        return $result;
    }

    public static function findUserInfoById($userId)
    {
        $result = Db::table(self::$table)->alias('a')
            ->join('role b', 'a.roleId = b.id')
            ->where('a.id', $userId)
            ->field('a.*, b.role_name, b.level')
            ->find();
        return empty($result) ? false : $result;
    }

    public static function updateUserStatusById($userId, $status)
    {
        $result = Db::table(self::$table)->where('id', $userId)->update(['status'=>$status]);
        if($result) return true;
        returnAjax("员工状态修改失败！", false);
    }

    public static function deleteUserById($userId)
    {
        $result = Db::table(self::$table)->where('id', $userId)->delete();
        if($result) return true;
        returnAjax("员工删除失败！", false);
    }

    public static function findUserByNotMyId($userId, $username)
    {
        $result = Db::table(self::$table)->where('id', '<>', $userId)->where('username', $username)->find();
        return $result ? true : false;
    }

    public static function updateUserInfoById($userId, $data)
    {
        $result = Db::table(self::$table)->where('id', $userId)
                    ->update([
                        'nickname'  =>  $data['nickname'],
                        'username'  =>  $data['username'],
                        'roleId'    =>  $data['role']
                    ]);
        if($result) {
            return  true;
        }
        returnAjax("员工信息修改成功！", false);
    }

    public static function updateUserPassword($userId, $password)
    {
        $result = Db::table(self::$table)->where('id', $userId)->update(['password'=>$password]);
        if($result) return true;
        returnAjax("员工密码修改成功！");
    }
}