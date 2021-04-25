<?php


namespace app\admin\model;


use think\Db;

class TripDb
{
    private static $table = 'trip';

    public static function saveInfo($data, $userId) {
        $res = Db::table(self::$table)->insert([
            'fromId'    =>  $userId,
            'nameId'    =>  $data['nameId'],
            'destination'   =>  $data['destination'],
            'travel_time'   =>  $data['travelTime'],
            'return_time'   =>  $data['returnTime'],
            'create_time'   =>  date('Y-m-d H:i:s', time())
        ]);
        if($res) return true;
        returnAjax("fail to add", false);
    }

    public static function getTripListByUserId($userId) {
        $res = Db::table(self::$table)->alias('a')
            ->join('bind_patient b', 'a.nameId = b.id')
            ->where('a.fromId', $userId)
            ->field('a.*, b.name')
            ->select();
        return $res;
    }
}