<?php


namespace app\admin\controller;


use app\admin\model\PatientDb;
use think\facade\Session;
use think\Request;
use think\Validate;

class Patient extends BaseController
{
    public function index() {
        $list = PatientDb::getPatientNameByUserId($this->userId);
        $this->assign('list', $list);
        return view();
    }

    public function saveBindInfo(Request $request) {
        $param = $request->post();
        // 校验数据
        $valiedate = new Validate();
        $valiedate->rule([
            'nickname'  =>  'require|max:50',
        ])->message([
            'nickname.require'  =>  'Please enter patient name！',
            'nickname.max'      =>  'Patient name length is greater than 50',
        ]);
        if(!$valiedate->check($param))
        {
            returnAjax($valiedate->getError(), false);
        }
        // 保存数据
        PatientDb::savePatientName($this->userId, $param['nickname']);
        returnAjax("Binding success!");
    }
}