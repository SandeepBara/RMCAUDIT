<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_ward_mstr;
use App\Models\model_ward_permission;

class WardWiseSafMobi extends MobiController
{
    protected $db;
    protected $dbSystem;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
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
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
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
        $safDetailsList=[];
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
                $safData = $this->model_saf_dtl->getDataByWardMstrId($ward_mstr_id,$from_date,$to_date);
                foreach ($safData as $key => $value) {
                    $safDetailsList[$key]['ownerDetails'] = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($value['id']);
                    $safDetailsList[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                    $safDetailsList[$key]['saf_no'] = $value['saf_no'];
                }
                $data['safDetailsList'] = $safDetailsList;
                return view('report/ward_wise_saf_mobi_report',$data);  
            }
            else
            {
                //Get All Saf details
                $key=0;
                foreach ($data['wardList'] as $val) {
                    if($safData = $this->model_saf_dtl->getDataByWardMstrId($val['id'],$from_date,$to_date))
                    {
                        //print_r($data['safDetailsList']);
                        foreach ($safData as $value) {
                            $safDetailsList[$key]['ownerDetails'] = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($value['id']);
                            $safDetailsList[$key]['ward_no'] = $val['ward_no'];
                            //Saf No
                            $safDetailsList[$key]['saf_no'] = $value['saf_no'];
                            $key++;
                        }   
                    }
                }
                $data['safDetailsList'] = $safDetailsList;
                return view('report/ward_wise_saf_mobi_report',$data); 
            }
        }
        else
        {
            //Get All Saf details
            $key =0;
            $from_date = date('Y-m-d');
            foreach ($data['wardList'] as $val) {
                if($safData = $this->model_saf_dtl->getDataByWardMstrId($val['id'],$from_date,$from_date))
                {
                    //print_r($data['safDetailsList']);
                    foreach ($safData as $value) {
                        $safDetailsList[$key]['ownerDetails'] = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($value['id']);
                        $safDetailsList[$key]['ward_no'] = $val['ward_no'];
                        //Saf No
                        $safDetailsList[$key]['saf_no'] = $value['saf_no'];
                        $key++;
                    }   
                }
            }
            $data['safDetailsList'] = $safDetailsList;
            return view('report/ward_wise_saf_mobi_report',$data); 
        } 
    }
}
?>
