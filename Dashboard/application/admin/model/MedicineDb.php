<?php


namespace app\admin\model;


use think\Db;

class MedicineDb
{
    private static $table = 'medicine';

    public static function saveInfo($data, $userId) {
        $res = Db::table(self::$table)->insert([
            'fromId'    =>  $userId,
            'nameId'    =>  $data['nameId'],
            'drug_names'    =>  $data['drugNames'],
            'take_medicine_time'    =>  $data['takeTime'],
            'create_time'   =>  date('Y-m-d H:i:s', time())
        ]);
        if($res) return true;
        returnAjax("fail to add", false);
    }

    public static function getMedicineListByUserId($userId) {
        $res = Db::table(self::$table)->alias('a')
                ->join('bind_patient b', 'a.nameId = b.id')
                ->where('a.fromId', $userId)
                ->field('a.*, b.name')
                ->select();
        return $res;
    }
}