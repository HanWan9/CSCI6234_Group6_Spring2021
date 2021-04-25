<?php


namespace app\admin\controller;


use app\admin\model\CalculatedRiskDb;
use app\admin\model\PatientDb;
use think\Request;
use think\Validate;

class CalculatedRisk extends BaseController
{
    public function index() {
        $userList = PatientDb::getPatientNameByUserId($this->userId);
        $list = CalculatedRiskDb::getCalculatedRiskListByUserId($this->userId);
        $result = $this->calculatedRisk($list);
        $this->assign(['userList'=>$userList, 'list'=>$result]);
        return view();
    }

    private function calculatedRisk($list) {
        foreach ($list as $key => &$value) {
            $num = 0;
            foreach ($value as $k => $v) {
                if( ($k == 'cold' && $v == 1)
                    || ($k == 'fever' && $v == 1) || ($k == 'cough' && $v == 1)
                    || ($k == 'breath' && $v == 1) || ($k == 'contact' && $v == 1)
                    || ($k == 'public' && $v == 1) )
                {
                    $num += 1;
                }
            }

            if($num > 3) {
                $type = 'Higher risk';
            } else if ($num >= 2) {
                $type = 'Medium risk';
            } else {
                $type = 'Low risk';
            }
            $value['type'] = $type;
        }
        return $list;
    }

    public function saveInfo(Request $request) {
        $param = $request->post();
        $valiedate = new Validate();
        $valiedate->rule([
            'nameId'  =>  'require',
            'cold'    =>  'require|in:0,1',
            'fever'   =>  'require|in:0,1',
            'cough'   =>  'require|in:0,1',
            'breath'  =>  'require|in:0,1',
            'contact' =>  'require|in:0,1',
            'public'  =>  'require|in:0,1',
        ])->message([
            'nameId.require'    => 'Please select a patient name',
            'cold.require'      =>  'please choose `Whether a cold`',
            'cold.in'           =>  'please choose `Whether a cold`',
            'fever.require'     =>  'please choose `Whether a fever`',
            'fever.in'          =>  'please choose `Whether a fever`',
            'cough.require'     =>  'please choose `Whether a cough`',
            'cough.in'          =>  'please choose `Whether a cough`',
            'breath.require'    =>  'please choose `Whether a shortness of breath`',
            'breath.in'         =>  'please choose `Whether a shortness of breath`',
            'contact.require'   =>  'please choose `Whether a been in contact with infected people`',
            'contact.in'        =>  'please choose `Whether a been in contact with infected people`',
            'public.require'    =>  'please choose `Whether a ever been to a public place with a lot of people`',
            'public.in'         =>  'please choose `Whether a ever been to a public place with a lot of people`',
        ]);
        if(!$valiedate->check($param)) returnAjax($valiedate->getError(), false);

        CalculatedRiskDb::saveInfo($param, $this->userId);

        returnAjax("Added record successfully");
    }

    public function viewDetail(Request $request) {
        $id = $request->post('workId');

        $result = CalculatedRiskDb::findCalculatedRiskDetailByIdAndUserId($this->userId, $id);

        if($result === false) returnAjax("Query failed, record does not exist", false);
        $typeList = [
            'cold', 'fever', 'cough', 'breath', 'contact', 'public',
        ];
        $arrayRes = [];
        foreach ($result as $key => $val) {
            if(in_array($key, $typeList)) {
                $arrayRes[$key] = $val == 1 ? 'Yes' : 'No';
            }
        }
        $arrayRes['name'] = $result['name'];
        returnAjax([
            'messages'  =>  'The query is successful',
            'result'    =>  $arrayRes
        ]);
    }
}