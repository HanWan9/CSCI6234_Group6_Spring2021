<?php


namespace app\admin\controller;


use app\admin\model\ClinicDb;
use app\admin\model\PatientDb;
use think\Request;
use think\Validate;

class Clinic extends BaseController
{
    public function clinic() {
        $userList = PatientDb::getPatientNameByUserId($this->userId);
        $list = ClinicDb::getListByUserId($this->userId);
        $this->assign(['userList'=>$userList, 'list'=>$list]);
        return view();
    }

    public function saveInfo(Request $request) {
        $param = $request->post();
        // 校验数据
        $valiedate = new Validate();
        $valiedate->rule([
            'nameId'    =>  'require',
            'doctorName' =>  'require|max:150',
            'clinicTime'  =>  'require|date',
        ])->message([
            'nameId.require'    => 'Please select a patient name',
            'doctorName.require' =>  'Please enter doctor name！',
            'doctorName.max'     =>  'The length of the doctor name should not exceed 150！',
            'clinicTime.require'  =>  'Please choose clinic time',
            'clinicTime.date'     =>  'Please choose the right time'
        ]);
        if(!$valiedate->check($param)) returnAjax($valiedate->getError(), false);

        ClinicDb::saveInfo($param, $this->userId);
        returnAjax("successfully added");
    }
}