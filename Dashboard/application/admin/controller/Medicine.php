<?php
namespace app\admin\controller;


use app\admin\model\AuthDb;
use app\admin\model\MedicineDb;
use app\admin\model\PatientDb;
use think\Request;
use think\Validate;

/**
 * 记录服用的药品和时间
 * Class Auto
 * @package app\admin\controller
 */
class Medicine extends BaseController
{
    public function index() {
        $userList = PatientDb::getPatientNameByUserId($this->userId);
        $list = MedicineDb::getMedicineListByUserId($this->userId);
        $this->assign(['userList'=>$userList,'list'=>$list]);
        return view();
    }

    public function saveInfo(Request $request) {
        $param = $request->post();
        // 校验数据
        $valiedate = new Validate();
        $valiedate->rule([
            'nameId'    =>  'require',
            'drugNames' =>  'require|max:50',
            'takeTime'  =>  'require|date'
        ])->message([
            'nameId.require'    => 'Please select a patient name',
            'drugNames.require' =>  'Please enter the name of the medicine you are taking！',
            'drugNames.max'     =>  'The length of the drug name should not exceed 50',
            'takeTime.require'  =>  'Please choose when to take the medicine',
            'takeTime.date'     =>  'Please choose the right time'
        ]);
        if(!$valiedate->check($param)) returnAjax($valiedate->getError(), false);
        // 判断数据
        $isTrue = PatientDb::findPatientNameByIdAndUserId($this->userId, $param['nameId']);
        if($isTrue === false) returnAjax('The selected patient does not exist', false);
        // 保存数据
        MedicineDb::saveInfo($param, $this->userId);
        // 返回数据
        returnAjax("successfully added ！");
    }
}