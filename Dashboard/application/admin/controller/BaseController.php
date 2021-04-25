<?php


namespace app\admin\controller;


use app\admin\model\LogsDb;
use app\admin\model\UserDb;
use think\App;
use think\Controller;
use think\facade\Cache;
use think\facade\Cookie;
use think\facade\Session;

class BaseController extends Controller
{
    public $userId = null;
    public $level = null;
    public $nickname = null;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->userId = Session::get('userId');
        // $userInfo = $this->getUserInfo();
        // $this->nickname = $userInfo['nickName'];
        // // 如果用户状态已被改变则不允许访问
        // if($userInfo['status'] != 1) {
        //     Cache::rm($this->userId);
        //     $this->redirect('/login');
        // }

        // $this->level = $userInfo['level'];

        // if($this->level != Session::get('level')) {
        //     Session::set('level', $this->level);
        // }

        // if($userInfo['roleId'] != Session::get('roleId')) {
        //     Session::set('roleId', $userInfo['roleId']);
        // }

        // $this->assign('userInfo', $userInfo);
        // 设置主题
        $this->checkTheme();
    }

    /**
     * 根据用户ID获取缓存数据
     */
    protected function getUserInfo() {
        $userInfo = Cache::get($this->userId);
        // 没有缓存则获取Db数据
        if($userInfo === false) {
            $user = UserDb::findUserInfoById($this->userId);
            $userInfo = [
                'userId'    =>  $this->userId,
                'userName'  =>  $user['username'],
                'nickName'  =>  $user['nickname'],
                'status'    =>  $user['status'],
                'roleId'    =>  $user['roleId'],
                'roleName'  =>  $user['role_name'],
                'level'     =>  $user['level']
            ];
            // 设置缓存过期时间为1小时
            Cache::set($this->userId, $userInfo, 3600);
        }
        return $userInfo;
    }

    /**
     * 对比用户的角色权重信息
     * @param $userLevel
     */
    protected function compareUserLevel($userLevel) {
        if ($userLevel > $this->level) {
            returnAjax("您的权限无法对当前员工进行操作！", false);
        }
    }

    /**
     * 判断用户的主题
     * 主题颜色分为 black 和 white
     */
    protected function checkTheme() {
        if(Cookie::has('theme')) {
            $theme = Cookie::get('theme');
            $this->assign('theme', $theme);
        } else {
            // 默认主题为黑色
            $this->assign('theme', 'black');
        }
    }

    /**
     * 写入日志的方法
     * @param $data
     */
    public function writeToLog($type, $message)
    {
        LogsDb::createLogs(['type'=>$type,'message'=>$message]);
    }
}