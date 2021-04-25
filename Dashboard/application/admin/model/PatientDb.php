<?php


namespace app\admin\model;


use think\Db;

class PatientDb
{
    private static $table = 'bind_patient';

    public static function savePatientName($fromId ,$name) {
        $res = Db::table(self::$table)->insert([
            'fromId'    =>  $fromId,
            'name'      =>  $name,
            'create_time'   =>  date('Y-m-d H:i:s', time())
        ]);

        if($res) return true;
        returnAjax("Binding failure", false);
    }

    public static function getPatientNameByUserId($userId) {
        $result = Db::table(self::$table)->where('fromId', $userId)->select();
        return $result;
    }

    public static function updatePatientNameByIdAndUserId($listId, $userId, $name) {
        $res = Db::table(self::$table)->where([
            'id'    =>  $listId,
            'fromId'    =>  $userId
        ])->update(['name'=>$name]);
        if($res) return true;
        returnAjax("change failed", false);
    }

    public static function findPatientNameByIdAndUserId($userId, $nameId) {
        $res = Db::table(self::$table)->where(['fromId'=>$userId, 'id'=>$nameId])->find();
        return $res ? true : false;
    }

    // Todo 删除
}