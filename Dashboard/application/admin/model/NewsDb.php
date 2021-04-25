<?php


namespace app\admin\model;


use think\Db;

class NewsDb
{
    private static $table = 'news';

    public static function createNews($data, $userId) {
        $res = Db::table(self::$table)->insert([
            'userId'    =>  $userId,
            'title'     =>  $data['title'],
            'covers'    =>  $data['imgPath'],
            'content'   =>  $data['content'],
            'create_time'   =>  date('Y-m-d H:i:s', time())
        ]);
        if($res) return true;
        returnAjax("Press Release Failed！", false);
    }

    public static function getNewsList() {
         $res = Db::table(self::$table)->order('create_time', 'desc')->select();
         return $res;
    }
}