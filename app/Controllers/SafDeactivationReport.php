<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_prop_saf_deactivation;
use App\Models\model_ward_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
class SafDeactivationReport extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_prop_saf_deactivation;
    protected $model_ward_mstr;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_prop_saf_deactivation = new model_prop_saf_deactivation($this->db);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
    }
    public function detail()
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
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $ward_mstr_id = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['ward_mstr_id'] = $ward_mstr_id;
            if($ward_mstr_id!=""){
                    if($data['safDeactivationList'] = $this->model_prop_saf_deactivation->getSafDeactivationDetails($from_date,$to_date,$ward_mstr_id)){
                    foreach ($data['safDeactivationList'] as $key => $value) {
                        $prop_dtl = $this->model_saf_dtl->getDeactivateSafNo($value['prop_dtl_id']);
                        $data['safDeactivationList'][$key]['saf_no'] = $prop_dtl['saf_no'];
                        $data['safDeactivationList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($prop_dtl['ward_mstr_id']);
                        $data['safDeactivationList'][$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($value['prop_dtl_id']);
                        $data['safDeactivationList'][$key]['guardian'] = $this->model_saf_owner_detail->getSafGuardianDetails($value['prop_dtl_id']);
                        $data['safDeactivationList'][$key]['ownerDetails'] = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($value['prop_dtl_id']);
                    }
                }
                return view('report/saf_deactivation_report',$data);
            }else{
                if($data['safDeactivationList'] = $this->model_prop_saf_deactivation->getAllSafDeactivationDetails($from_date,$to_date)){
                foreach ($data['safDeactivationList'] as $key => $value) {
                    $prop_dtl = $this->model_saf_dtl->getDeactivateSafNo($value['prop_dtl_id']);
                    $data['safDeactivationList'][$key]['saf_no'] = $prop_dtl['saf_no'];
                    $data['safDeactivationList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($prop_dtl['ward_mstr_id']);
                    $data['safDeactivationList'][$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($value['prop_dtl_id']);
                    $data['safDeactivationList'][$key]['guardian'] = $this->model_saf_owner_detail->getSafGuardianDetails($value['prop_dtl_id']);
                    $data['safDeactivationList'][$key]['ownerDetails'] = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($value['prop_dtl_id']);
                }
            }
            return view('report/saf_deactivation_report',$data);
            }
              
        }
        else
        {
            $from_date = date('Y-m-d');
            if($data['safDeactivationList'] = $this->model_prop_saf_deactivation->getAllSafDeactivationDetails($from_date,$from_date)){

                foreach ($data['safDeactivationList'] as $key => $value) {
                    $prop_dtl = $this->model_saf_dtl->getDeactivateSafNo($value['prop_dtl_id']);
                    $data['safDeactivationList'][$key]['saf_no'] = $prop_dtl['saf_no'];
                    $data['safDeactivationList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($prop_dtl['ward_mstr_id']);
                    $data['safDeactivationList'][$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($value['prop_dtl_id']);
                    $data['safDeactivationList'][$key]['guardian'] = $this->model_saf_owner_detail->getSafGuardianDetails($value['prop_dtl_id']);
                    $data['safDeactivationList'][$key]['ownerDetails'] = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($value['prop_dtl_id']);
                }
            }
            return view('report/saf_deactivation_report',$data);
        } 
    }
}
?>
