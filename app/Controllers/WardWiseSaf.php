<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_ward_mstr;

class WardWiseSaf extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_ward_mstr;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form']);
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
                $data['safDetailsList'] = $this->model_saf_dtl->getDataByWardMstrId($ward_mstr_id,$from_date,$to_date);
                foreach ($data['safDetailsList'] as $key => $value) {
                    $data['safDetailsList'][$key]['apply_date'] = $value['apply_date'];
                    $data['safDetailsList'][$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($value['id']);
                    $data['safDetailsList'][$key]['guardian'] = $this->model_saf_owner_detail->getSafGuardianDetails($value['id']);
                    $data['safDetailsList'][$key]['ownerDetails'] = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($value['id']);
                    $data['safDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                }
                return view('report/ward_wise_saf_report',$data);  
            }
            else
            {
                //Get All Saf details
                $safDetailsList = $this->model_saf_dtl->getSafWardMstrId($from_date,$to_date);
                $data['safDetailsList'] = $safDetailsList;
              
                foreach ($data['safDetailsList'] as $key => $value) {
                    $data['safDetailsList'][$key]['apply_date'] = $value['apply_date'];
                    $data['safDetailsList'][$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($value['id']);
                    $data['safDetailsList'][$key]['guardian'] = $this->model_saf_owner_detail->getSafGuardianDetails($value['id']);
                    $data['safDetailsList'][$key]['ownerDetails'] = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($value['id']);
                    $data['safDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                }
                /*$data = [
                    'safDetailsList' => $safDetailsList->paginate(10),
                    'pager' => $safDetailsList->pager
                ];*/
                return view('report/ward_wise_saf_report',$data); 
            }
        }
        else
        {
            //Get All Saf details
            $from_date = date('Y-m-d');
            $data['safDetailsList'] = $this->model_saf_dtl->getSafWardMstrId($from_date,$from_date);
            foreach ($data['safDetailsList'] as $key => $value) {
                $data['safDetailsList'][$key]['apply_date'] = $value['apply_date'];
                $data['safDetailsList'][$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($value['id']);
                $data['safDetailsList'][$key]['guardian'] = $this->model_saf_owner_detail->getSafGuardianDetails($value['id']);
                $data['safDetailsList'][$key]['ownerDetails'] = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($value['id']);
                $data['safDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
            }
            return view('report/ward_wise_saf_report',$data);
        } 
    }
}
?>
