<?php
namespace app\admin\controller;

use think\Request;
use think\Validate;
use app\admin\model\LoginDb;
use think\facade\Session;

class Login
{
    /**
     * 登录页面
     * @return \think\response\View
     */
    public function login() 
    {
        return view();
    }

    /**
     * 检查登录的数据
     * @param Request $request
     */
    public function checkLogin(Request $request)
    {
        if($request->isPost())
        {
            $data = $request->post();

            $this->checkLoginFromData($data);

            $captcha = new \think\captcha\Captcha();
            if( $captcha->check($data['captcha']) === false) returnAjax('The verification code entered is incorrect！', false);

            // 查询用户数据
            $user = LoginDb::checkUserByUsername($data['username']);

            if($user === false) returnAjax('The user does not exist. ！', false);

            // 校验密码
            $isTrue = password_verify($data['password'], $user['password']);
            if($isTrue === false) returnAjax('The password is incorrect. Please re-enter it', false);

            // 判断员工的状态
            if($user['status'] != 1) returnAjax("The user has been barred from logging in, please contact the administrator！", false);

            // 存储用户数据
            Session::set('userId', $user['id']);
            Session::set('userName', $user['email']);
            Session::set('nickName', $user['nickname']);

            returnAjax('login successfully ！');
        }
    }

    /**
     * 校验登录数据
     * @param $data 校验的数据
     */
    private function checkLoginFromData($data)
    {
        // 校验数据
        $valiedate = new Validate();
        $valiedate->rule([
            'username'  =>  'require|max:18',
            'password'  =>  'require|max:18',
            'captcha'   =>  'require'
        ])->message([
            'username.require'  =>  'Please enter email address！',
            'username.max'      =>  'Usernames should not be longer than 18 digits',
            'password.require'  =>  'Enter your PIN！',
            'captcha'           =>  'Please enter the verification code！'
        ]);

        if(!$valiedate->check($data))
        {
            returnAjax($valiedate->getError(), false);
        }
    }

    public function register() {
        return view();
    }

    public function checkRegister(Request $request) {
        $param = $request->post();
        $this->checkRegisterParam($param);
        LoginDb::insertUser($param);
        returnAjax("registered successfully！");
    }

    private function checkRegisterParam($data) {
        $valiedate = new Validate();
        $valiedate->rule([
            'nickname'                  =>  'require|max:30',
            'email'                     =>  'require|regex:^[0-9a-z]+@(([0-9a-z]+)[.])+[a-z]{2,3}$|unique:user',
            'password'                  =>  'require|min:8|max:18|regex:^(?![a-zA-z]+$)(?!\d+$)(?![!@#$%^&*+-.]+$)(?![a-zA-z\d]+$)(?![a-zA-z!@#$%^&*+-.]+$)(?![\d!@#$%^&*]+$)[a-zA-Z\d!@#$%^&*]+$',
            'confirm_password'          =>  'require|confirm:password',
        ])->message([
            'nickname.require'          =>  'Please enter nickname！',
            'nickname.max'              =>  'Nicknames cannot be longer than 30！',
            'email.require'             =>  'Please enter email address',
            'email.regex'               =>  'Please enter the correct email format！',
            'email.unique'              =>  'This mailbox has been registered!',
            'password.require'          =>  'Enter your PIN！',
            'password.min'              =>  'The login password is composed of letters + numbers + special characters, and the length is 8 ~ 18 characters！',
            'password.max'              =>  'The login password is composed of letters + numbers + special characters, and the length is 8 ~ 18 characters！',
            'password.regex'            =>  'The login password is composed of letters + numbers + special characters, and the length is 8 ~ 18 characters！',
            'confirm_password.require'  =>  'Please reconfirm your password！',
            'confirm_password.confirm'  =>  'The two passwords you entered do not match. Please try again！',
        ]);

        if(!$valiedate->check($data))
        {
            returnAjax($valiedate->getError(), false);
        }
    }

    /**
     * 验证码
     * @return mixed
     */
    public function verify() {
        $config =    [
            'fontSize'    =>    15,
            'length'      =>    5,
            'useNoise'    =>    false,
        ];
        $captcha = new \think\captcha\Captcha($config);
        return $captcha->entry();
    }
}