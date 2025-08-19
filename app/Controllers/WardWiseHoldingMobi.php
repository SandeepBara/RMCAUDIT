<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_ward_mstr;
use App\Models\model_ward_permission;
class WardWiseHoldingMobi extends MobiController
{
    protected $db;
    protected $dbSystem;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_ward_mstr;
    protected $model_ward_permission;
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
        $this->model_ward_permission = new model_ward_permission($this->dbSystem);
    }
    public function report()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //Employee Details
        $emp_details = $session->get('emp_details');
        ///get permitted ward mstr id
        $wardPermissioin = $this->model_ward_permission->getWardDataByEmpdetailsId($emp_details['id']);
        $wardPermissioin=explode(",",$wardPermissioin['ward_mstr_id']);
        //Transaction Mode List
        $data['wardList'] = $this->model_ward_mstr->getPermittedWard($wardPermissioin,$ulb_mstr_id);
        $propDetailsList =[];
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
                if($propData = $this->model_prop_dtl->getDataByWardMstrId($ward_mstr_id,$from_date,$to_date)){
                    foreach ($propData as $key => $value) {
                        $propDetailsList[$key]['ownerDetails'] = $this->model_prop_owner_detail->getOwnerDataByPropDetailsId($value['id']);
                        $propDetailsList[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                        $propDetailsList[$key]['holding_no'] = $value['holding_no'];
                    }
                }
                $data['propDetailsList'] = $propDetailsList;
                return view('report/ward_wise_holding_mobi_report',$data);   
            }
            else
            {
                //Get All Property details
                $key=0;
                foreach ($data['wardList'] as $val) {
                        if($propData = $this->model_prop_dtl->getDataByWardMstrId($val['id'],$from_date,$to_date)){
                        foreach ($propData as $value) {
                            $propDetailsList[$key]['ownerDetails'] = $this->model_prop_owner_detail->getOwnerDataByPropDetailsId($value['id']);
                            $propDetailsList[$key]['ward_no'] = $val['ward_no'];
                            $propDetailsList[$key]['holding_no'] = $value['holding_no'];
                            $key++;
                        }
                    }
                }
                $data['propDetailsList'] = $propDetailsList;
                return view('report/ward_wise_holding_mobi_report',$data);   
            }
        }
        else
        {
            //Get All Property details
            $from_date = date('Y-m-d');
            $key=0;
            foreach ($data['wardList'] as $val) {
                    if($propData = $this->model_prop_dtl->getDataByWardMstrId($val['id'],$from_date,$from_date)){
                    foreach ($propData as $value) {
                        $propDetailsList[$key]['ownerDetails'] = $this->model_prop_owner_detail->getOwnerDataByPropDetailsId($value['id']);
                        $propDetailsList[$key]['ward_no'] = $val['ward_no'];
                        $propDetailsList[$key]['holding_no'] = $value['holding_no'];
                        $key++;
                    }
                }
            }
            $data['propDetailsList'] = $propDetailsList;
            return view('report/ward_wise_holding_mobi_report',$data);   
        } 
    }
}
?>
