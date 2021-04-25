<?php


namespace app\admin\controller;


use app\admin\model\PatientDb;
use app\admin\model\TripDb;
use think\Request;
use think\Validate;

class Trip extends BaseController
{
    public function trip() {
        $userList = PatientDb::getPatientNameByUserId($this->userId);
        $list = TripDb::getTripListByUserId($this->userId);
        $this->assign(['userList'=>$userList,'list'=>$list]);
        return view();
    }

    public function saveInfo(Request $request) {
        $param = $request->post();
        // 校验数据
        $valiedate = new Validate();
        $valiedate->rule([
            'nameId'    =>  'require',
            'destination' =>  'require|max:150',
            'travelTime'  =>  'require|date',
            'returnTime'  =>  'require|date',
        ])->message([
            'nameId.require'    => 'Please select a patient name',
            'destination.require' =>  'Please enter destination！',
            'destination.max'     =>  'The length of the drug name should not exceed 150！',
            'takeTime.require'  =>  'Please choose travel time',
            'takeTime.date'     =>  'Please choose the right time',
            'returnTime.require'  =>  'Please choose return time',
            'returnTime.date'     =>  'Please choose the right time',
        ]);
        if(!$valiedate->check($param)) returnAjax($valiedate->getError(), false);

        // 判断数据
        $isTrue = PatientDb::findPatientNameByIdAndUserId($this->userId, $param['nameId']);
        if($isTrue === false) returnAjax('The selected patient does not exist', false);

        TripDb::saveInfo($param, $this->userId);
        returnAjax("successfully added！");
    }
}