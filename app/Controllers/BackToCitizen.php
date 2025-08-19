<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_ward_mstr;

class BackToCitizen extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_ward_mstr;

    public function __construct()
    {
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
    }
    public function report()
    {
        $data =(array)null;
        helper(['form']);
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //Transaction Mode List
        $wardList = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);
        $data['wardList'] = $wardList;
        if($this->request->getMethod()=='post')
        {
            $ward_mstr_id = $this->request->getVar('ward_mstr_id');
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['ward_mstr_id'] = $ward_mstr_id;
            if($ward_mstr_id!="")
            {
                $data['backToCitizenList'] = $this->model_saf_dtl->getBackToCitizenByWardMstrId($ward_mstr_id,$from_date,$to_date);
                foreach ($data['backToCitizenList'] as $key => $value) {
                    $data['backToCitizenList'][$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($value['id']);
                    $data['backToCitizenList'][$key]['guardian'] = $this->model_saf_owner_detail->getSafGuardianDetails($value['id']);
                    $data['backToCitizenList'][$key]['ownerDetails'] = $this->model_saf_owner_detail->ownerDetailsData($value['id']);
                    $data['backToCitizenList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                }
                return view('report/back_to_citizen_report',$data);  
            }
            else
            {
                //Get All Saf details
                $data['backToCitizenList'] = $this->model_saf_dtl->getBackToCitizen($from_date,$to_date);
                foreach ($data['backToCitizenList'] as $key => $value) {
                    $data['backToCitizenList'][$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($value['id']);
                    $data['backToCitizenList'][$key]['guardian'] = $this->model_saf_owner_detail->getSafGuardianDetails($value['id']);
                    $data['backToCitizenList'][$key]['ownerDetails'] = $this->model_saf_owner_detail->ownerDetailsData($value['id']);
                    $data['backToCitizenList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                }
                return view('report/back_to_citizen_report',$data); 
            }
        }
        else
        {
            //Get All Saf details
            $from_date = date('Y-m-d');
            $data['backToCitizenList'] = $this->model_saf_dtl->getBackToCitizen($from_date,$from_date);
            foreach ($data['backToCitizenList'] as $key => $value) {
                $data['backToCitizenList'][$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($value['id']);
                $data['backToCitizenList'][$key]['guardian'] = $this->model_saf_owner_detail->getSafGuardianDetails($value['id']);
                $data['backToCitizenList'][$key]['ownerDetails'] = $this->model_saf_owner_detail->ownerDetailsData($value['id']);
                $data['backToCitizenList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
            }
            return view('report/back_to_citizen_report',$data);
        } 
    }
}
?>
