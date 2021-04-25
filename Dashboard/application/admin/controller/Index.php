<?php


namespace app\admin\controller;

use app\admin\model\LoginDb;
use app\admin\model\NewsDb;
use app\admin\model\UserDb;
use think\facade\Cache;
use think\facade\Cookie;
use think\facade\Session;
use think\Request;
use think\Validate;

class Index extends BaseController
{
    public function index(){
        $list = NewsDb::getNewsList();
        $this->assign(['list'=>$list]);
        return view();
    }

    public function releaseNews() {
        return view();
    }

    /**
     * 上传封面图
     * @param Request $request
     */
    public function uploadCovers(Request $request) {
        $covers = $request->file('covers');
        $this->checkUploadCovers(['data'=>$covers]);
        // 上传图片
        $info = $covers->move( './static/uploads');
        if($info) {
            // 更换图片的地址
            $imgSaveName = str_replace('\\', '/', $info->getSaveName());
            // 保存完整图片
            $imgPath = '/static/uploads/' . $imgSaveName;
            returnAjax([
                'msg'       =>  'Cover uploaded successfully！',
                'imgPath'  =>  $imgPath
            ]);
        }
        returnAjax("fail to upload !", false);
    }

    public function saveNews(Request $request) {
        $param = $request->post();
        // 校验数据
        $valiedate = new Validate();
        $valiedate->rule([
            'imgPath'    =>  'require',
            'title'     =>  'require|max:50',
            'content'   =>  'require',
        ])->message([
            'imgPath.require'     => 'Please upload the cover picture',
            'title.require'      => 'Please enter a title！',
            'title.max'          => 'The header length must not exceed 50！',
            'content.require'    =>  'Please choose clinic time'
        ]);
        if(!$valiedate->check($param)) returnAjax($valiedate->getError(), false);

        NewsDb::createNews($param, $this->userId);
        returnAjax("Successful press release！");
    }

    /**
     * 校验上传文件的数据
     * @param $data
     * @return bool
     */
    private function checkUploadCovers($data)
    {
        $rule = [
            'data'  =>  'require|fileExt:png,jpg,jpeg|fileSize:2097152'
        ];

        $message = [
            'data.require'  =>  'Please upload the cover picture！',
            'data.fileMime' =>  'Please upload the cover image in the image format',
            'data.fileExt'  =>  'Cover image format can only be PNG, JPG, JPEG！',
            'data.fileSize' =>  'Cover image size should not exceed 2M'
        ];

        $valiedate = new Validate();
        $valiedate->rule($rule)->message($message);
        if(!$valiedate->check($data))
        {
            returenAjax($valiedate->getError(), false);
        }
        return true;
    }

    /**
     * 用户退出登录
     */
    public function outLogin()
    {
        Session::clear();
        Cache::rm($this->userId);
        $this->redirect('/login');
    }

    /**
     * 用户切换主题
     * @param Request $request
     */
    public function changeTheme(Request $request)
    {
        $color = $request->post('type');

        if($color == 'default') {
            Cookie::set('theme', 'white');
        } else {
            Cookie::set('theme', 'black');
        }
        returnAjax("Themes switched successfully！");
    }

    /**
     * 用户修改自己的密码
     * @param Request $request
     */
    public function updateMySelfPassword(Request $request) {
        $param = $request->post();
        // 校验数据
        $rule = [
            'new_password'          =>  'require|min:8|max:18|regex:^(?![a-zA-z]+$)(?!\d+$)(?![!@#$%^&*+-.]+$)(?![a-zA-z\d]+$)(?![a-zA-z!@#$%^&*+-.]+$)(?![\d!@#$%^&*]+$)[a-zA-Z\d!@#$%^&*]+$',
            'confirm_password'      =>  'require|confirm:new_password',
        ];

        // 校验角色信息
        $valiedate = new Validate();
        $valiedate->rule($rule)->message([
            'new_password.require'      =>  'Please enter your new login password！',
            'new_password.min'          =>  'The new login password consists of alphanumeric + special characters and is 8 to 18 characters in length！',
            'new_password.max'          =>  'The new login password consists of alphanumeric + special characters and is 8 to 18 characters in length！',
            'new_password.regex'        =>  'The new login password consists of alphanumeric + special characters and is 8 to 18 characters in length！',
            'confirm_password.require'  =>  'Please reconfirm your password！',
            'confirm_password.confirm'  =>  'The two passwords you entered do not match. Please try again！',
        ]);

        if(!$valiedate->check($param))
        {
            returnAjax($valiedate->getError(), false);
        }

        $password = password_hash($param['new_password'], PASSWORD_DEFAULT);
        LoginDb::updateUserPassword($this->userId, $password);

        returnAjax("Password changed successfully！");
    }
}