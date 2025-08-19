<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_ward_mstr;

class WardWiseHolding extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
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
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
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
            $data['ward_mstr_id'] = $ward_mstr_id;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            if($ward_mstr_id!="")
            {
                $data['propDetailsList'] = $this->model_prop_dtl->getDataByWardMstrId($ward_mstr_id,$from_date,$to_date);
                foreach ($data['propDetailsList'] as $key => $value) {
                    $data['propDetailsList'][$key]['ownerDetails'] = $this->model_prop_owner_detail->getOwnerDataByPropDetailsId($value['id']);
                    $data['propDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                }
                return view('report/ward_wise_holding_report',$data);   
            }
            else
            {
                //Get All Property details
                $data['propDetailsList'] = $this->model_prop_dtl->getHoldingWardMstrId($from_date,$to_date);
                foreach ($data['propDetailsList'] as $key => $value) {                    
                    $data['propDetailsList'][$key]['ownerDetails'] = $this->model_prop_owner_detail->getOwnerDataByPropDetailsId($value['id']);
                    $data['propDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                }
                return view('report/ward_wise_holding_report',$data);   
            }
        }
        else
        {
            //Get All Property details
            $from_date = date('Y-m-d');
            $data['propDetailsList'] = $this->model_prop_dtl->getHoldingWardMstrId($from_date,$from_date);
            foreach ($data['propDetailsList'] as $key => $value) {
                $data['propDetailsList'][$key]['ownerDetails'] = $this->model_prop_owner_detail->getOwnerDataByPropDetailsId($value['id']);
                $data['propDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
            }
            return view('report/ward_wise_holding_report',$data);
        } 
    }
}
?>
