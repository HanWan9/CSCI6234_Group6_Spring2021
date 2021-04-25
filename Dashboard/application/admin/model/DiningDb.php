<?php


namespace app\admin\model;
use think\Db;

class DiningDb
{
    private static $table = 'dining';

    public static function saveInfo($data, $userId) {
        $res = Db::table(self::$table)->insert([
            'fromId'        =>  $userId,
            'nameId'        =>  $data['nameId'],
            'diningSelect'  =>  $data['diningSelect'],
            'foodInf'       =>  $data['foodInf'],
            'diningTime'    =>  $data['diningTime'],
            'create_time'   =>  date('Y-m-d H:i:s', time())
        ]);
        if($res) return true;
        returnAjax("fail to add");
    }

    public static function getDiningListByUserId($userId) {
        $res = Db::table(self::$table)->alias('a')
            ->join('bind_patient b', 'a.nameId = b.id')
            ->where('a.fromId', $userId)
            ->field('a.*, b.name')
            ->select();
        return $res;
    }
}