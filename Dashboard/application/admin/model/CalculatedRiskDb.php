<?php


namespace app\admin\model;

use think\Db;
class CalculatedRiskDb
{
    private static $table = 'calculated_risk';

    public static function saveInfo($data, $userId) {
        $res = Db::table(self::$table)->insert([
            'fromId'    =>  $userId,
            'nameId'    =>  $data['nameId'],
            'cold'      =>  $data['cold'],
            'fever'     =>  $data['fever'],
            'cough'     =>  $data['cough'],
            'breath'    =>  $data['breath'],
            'contact'   =>  $data['contact'],
            'public'    =>  $data['public'],
            'create_time'   =>  date('Y-m-d H:i:s', time())
        ]);
        if($res) return true;
        returnAjax("Failed to add record", false);
    }

    public static function getCalculatedRiskListByUserId($userId) {
        $res = Db::table(self::$table)->alias('a')
            ->join('bind_patient b', 'a.nameId = b.id')
            ->where('a.fromId', $userId)
            ->field('a.*, b.name')
            ->select();
        return $res;
    }

    public static function findCalculatedRiskDetailByIdAndUserId($userId, $id) {
        $res = Db::table(self::$table)->alias('a')
            ->join('bind_patient b', 'a.nameId = b.id')
            ->where('a.fromId', $userId)
            ->where('a.id', $id)
            ->field('a.*, b.name')
            ->find();
        return $res ? $res : false;
    }
}