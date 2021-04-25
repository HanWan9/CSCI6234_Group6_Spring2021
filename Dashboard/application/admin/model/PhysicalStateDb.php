<?php


namespace app\admin\model;


use think\Db;

class PhysicalStateDb
{
    private static $table = 'status';

    public static function saveInfo($data, $userId) {
        $res = Db::table(self::$table)->insert([
            'fromId'    =>  $userId,
            'nameId'    =>  $data['nameId'],
            'status'    =>  $data['status'],
            'semiography'   =>  $data['symptom'],
            'create_time'   =>  date('Y-m-d H:i:s', time())
        ]);
        if($res) return true;
        returnAjax("fail to add", false);
    }

    public static function getPhysicalStateListByUserId($userId) {
        $res = Db::table(self::$table)->alias('a')
            ->join('bind_patient b', 'a.nameId = b.id')
            ->where('a.fromId', $userId)
            ->field('a.*, b.name')
            ->select();
        return $res;
    }
}