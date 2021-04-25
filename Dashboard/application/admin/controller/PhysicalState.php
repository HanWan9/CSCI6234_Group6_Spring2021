<?php


namespace app\admin\controller;

use app\admin\model\PhysicalStateDb;
use app\admin\model\PatientDb;
use think\Request;
use think\Validate;

class PhysicalState extends BaseController
{
    public function index() {
        $userList = PatientDb::getPatientNameByUserId($this->userId);
        $list = PhysicalStateDb::getPhysicalStateListByUserId($this->userId);
        $this->assign(['userList'=>$userList,'list'=>$list]);
        return view();
    }

    public function saveInfo(Request $request) {
        $param = $request->post();
        // 校验数据
        $valiedate = new Validate();
        $valiedate->rule([
            'nameId'        =>  'require',
            'status'        =>  'require',
            'symptom'       =>  'require|max:255'
        ])->message([
            'nameId.require'        =>  'Please select a patient name',
            'status.require'        =>  'Please select body status！',
            'symptom.require'       =>  'Please enter a symptom record',
            'symptom.max'           =>  'symptom is too long',
        ]);
        if(!$valiedate->check($param)) returnAjax($valiedate->getError(), false);
        PhysicalStateDb::saveInfo($param, $this->userId);
        returnAjax("successfully added");
    }
}