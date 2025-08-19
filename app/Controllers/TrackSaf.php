<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_ward_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_level_pending_dtl;
use App\Models\model_user_type_mstr;

class TrackSaf extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_ward_mstr;
    protected $model_prop_dtl;
    protected $model_level_pending_dtl;
    protected $model_user_type_mstr;
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
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_user_type_mstr = new model_user_type_mstr($this->dbSystem);
    }
    public function detail()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //Transaction Mode List
        $wardList = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);
        $user_type = $this->model_user_type_mstr->userDetailsList();
       /* print_r($user_type);
        die();*/
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
                    
                    $data['safDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                    if($value['prop_dtl_id']!=0){
                        $data['safDetailsList'][$key]['holding'] = $this->model_prop_dtl->getDataBySafDtlId($value['prop_dtl_id']);
                    }else{
                        $data['safDetailsList'][$key]['holding'] ='0';
                    }
                    $data['safDetailsList'][$key]['dealingStatus'] = $this->model_level_pending_dtl->getDealingAssistantStatus($value['id']); 
                    $data['safDetailsList'][$key]['agencyStatus'] = $this->model_level_pending_dtl->getAgencyTcStatus($value['id']);
                    $data['safDetailsList'][$key]['ulbTaxStatus'] = $this->model_level_pending_dtl->getUlbTcStatus($value['id']);
                     $data['safDetailsList'][$key]['sectionInchargeStatus'] = $this->model_level_pending_dtl->getSectionInchargeStatus($value['id']);
                     $data['safDetailsList'][$key]['executiveOfficerStatus'] = $this->model_level_pending_dtl->getExecutiveOfficerStatus($value['id']);
                    
                    /*if( $owner = $this->model_saf_owner_detail->ownerdetails($value['id'])){
                        $data['safDetailsList'][$key]['ownerdata'] = $owner;
                    }*/
                    if( $owner = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($value['id'])){
                        $data['safDetailsList'][$key]['ownerdata'] = $owner;
                    }

                    
                }
                return view('report/track_saf',$data);  
            }
            else
            {
               $data['safDetailsList'] = $this->model_saf_dtl->getAllData($from_date,$to_date);
                
                foreach ($data['safDetailsList'] as $key => $value) {
                    
                    $data['safDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                    if($value['prop_dtl_id']!=0){
                        $data['safDetailsList'][$key]['holding'] = $this->model_prop_dtl->getDataBySafDtlId($value['prop_dtl_id']);
                    }else{
                        $data['safDetailsList'][$key]['holding'] ='0';
                    }
                     $data['safDetailsList'][$key]['dealingStatus'] = $this->model_level_pending_dtl->getDealingAssistantStatus($value['id']); 
                    $data['safDetailsList'][$key]['agencyStatus'] = $this->model_level_pending_dtl->getAgencyTcStatus($value['id']);
                    $data['safDetailsList'][$key]['ulbTaxStatus'] = $this->model_level_pending_dtl->getUlbTcStatus($value['id']);
                     $data['safDetailsList'][$key]['sectionInchargeStatus'] = $this->model_level_pending_dtl->getSectionInchargeStatus($value['id']);
                     $data['safDetailsList'][$key]['executiveOfficerStatus'] = $this->model_level_pending_dtl->getExecutiveOfficerStatus($value['id']);

                    /*if( $owner = $this->model_saf_owner_detail->ownerdetails($value['id'])){
                        $data['safDetailsList'][$key]['ownerdata'] = $owner;
                    }*/

                    if( $owner = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($value['id'])){
                        $data['safDetailsList'][$key]['ownerdata'] = $owner;
                    }
                }
               // print_r($data);
            return view('report/track_saf',$data); 
        }
    }
    else {
           $from_date = date('Y-m-d');
           $data['safDetailsList'] = $this->model_saf_dtl->getAllData($from_date,$from_date);
                foreach ($data['safDetailsList'] as $key => $value) {
                    $data['safDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                    if($value['prop_dtl_id']!=0){
                        $data['safDetailsList'][$key]['holding'] = $this->model_prop_dtl->getDataBySafDtlId($value['prop_dtl_id']);
                    }else{
                        $data['safDetailsList'][$key]['holding'] ='0';
                    }
                    $data['safDetailsList'][$key]['dealingStatus'] = $this->model_level_pending_dtl->getDealingAssistantStatus($value['id']); 
                    $data['safDetailsList'][$key]['agencyStatus'] = $this->model_level_pending_dtl->getAgencyTcStatus($value['id']);
                    $data['safDetailsList'][$key]['ulbTaxStatus'] = $this->model_level_pending_dtl->getUlbTcStatus($value['id']);
                     $data['safDetailsList'][$key]['sectionInchargeStatus'] = $this->model_level_pending_dtl->getSectionInchargeStatus($value['id']);
                     $data['safDetailsList'][$key]['executiveOfficerStatus'] = $this->model_level_pending_dtl->getExecutiveOfficerStatus($value['id']);
                    /*if( $owner = $this->model_saf_owner_detail->ownerdetails($value['id'])){
                        $data['safDetailsList'][$key]['ownerdata'] = $owner;
                    }*/

                    if( $owner = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($value['id'])){
                        $data['safDetailsList'][$key]['ownerdata'] = $owner;
                    }
                }
            return view('report/track_saf',$data);
        } 
    }
}
?>
