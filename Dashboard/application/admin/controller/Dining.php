<?php


namespace app\admin\controller;

use app\admin\model\DiningDb;
use app\admin\model\PatientDb;
use think\Request;
use think\Validate;

class Dining extends BaseController
{
    public function dining() {
        $userList = PatientDb::getPatientNameByUserId($this->userId);
        $list = DiningDb::getDiningListByUserId($this->userId);
        $this->assign(['userList'=>$userList,'list'=>$list]);
        return view();
    }

    public function saveInfo(Request $request) {
        $param = $request->post();
        // 校验数据
        $valiedate = new Validate();
        $valiedate->rule([
            'nameId'        =>  'require',
            'diningSelect'  =>  'require',
            'foodInf'       =>  'require|max:250',
            'diningTime'    =>  'require|date'
        ])->message([
            'nameId.require'        => 'Please select a patient name',
            'diningSelect.require'  =>  'Please choose where to dine！',
            'foodInf.require'       =>  'Please enter food information',
            'foodInf.max'           =>  'Food information is too long',
            'diningTime.require'    =>  'Please choose when to take the dining time',
            'diningTime.date'       =>  'Please choose the right time'
        ]);
        if(!$valiedate->check($param)) returnAjax($valiedate->getError(), false);
        DiningDb::saveInfo($param, $this->userId);
        returnAjax("successfully added");
    }
}