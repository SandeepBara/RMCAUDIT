<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\water_level_pending_model;
use App\Models\water_applicant_details_model;
use App\Models\water_property_type_mstr_model;

class WaterApplicantionTrackStatus extends AlphaController
{
    protected $water;
    protected $dbSystem;
    protected $model_ward_mstr;
    protected $WaterApplyNewConnectionModel;
    protected $water_level_pending_model;
    protected $water_applicant_details_model;
    protected $water_property_type_mstr_model;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->water);
        $this->water_level_pending_model = new water_level_pending_model($this->water);
        $this->water_applicant_details_model = new water_applicant_details_model($this->water);
        $this->water_property_type_mstr_model = new water_property_type_mstr_model($this->water);
    }
    public function detail()
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
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            if($data['ward_mstr_id']!="")
            {
                $data['applyDetailsList'] = $this->WaterApplyNewConnectionModel->getAllData($data);
                foreach ($data['applyDetailsList'] as $key => $value) 
                {
                    $data['applyDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_id']);
                    $data['applyDetailsList'][$key]['dealingStatus'] = $this->water_level_pending_model->getDealingAssistantStatus($value['id']); 
                    $data['applyDetailsList'][$key]['juniorStatus'] = $this->water_level_pending_model->getJuniorEngineerStatus($value['id']);
                    $data['applyDetailsList'][$key]['sectionStatus'] = $this->water_level_pending_model->getSectionHeadStatus($value['id']);
                     $data['applyDetailsList'][$key]['assistantStatus'] = $this->water_level_pending_model->getAssistantEngineerStatus($value['id']);
                     $data['applyDetailsList'][$key]['executiveStatus'] = $this->water_level_pending_model->getExecutiveOfficerStatus($value['id']);
                    /*if( $applicant = $this->water_applicant_details_model->applicantDetails($value['id'])){
                        $data['applyDetailsList'][$key]['applicant'] = $applicant;
                    }*/

                    if( $applicant = $this->water_applicant_details_model->getApplicantDetails($value['id'])){
                        $data['applyDetailsList'][$key]['applicant'] = $applicant;
                    }

                    $data['applyDetailsList'][$key]['property_type'] = $this->water_property_type_mstr_model->propertyDetails($value['id']);
                }
                
                return view('water/report/track_application',$data);  
            }
            else
            {
                $data['applyDetailsList'] = $this->WaterApplyNewConnectionModel->getAllDataBydate($data);
                foreach ($data['applyDetailsList'] as $key => $value) 
                {
                    $data['applyDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_id']);
                    $data['applyDetailsList'][$key]['dealingStatus'] = $this->water_level_pending_model->getDealingAssistantStatus($value['id']); 
                    $data['applyDetailsList'][$key]['juniorStatus'] = $this->water_level_pending_model->getJuniorEngineerStatus($value['id']);
                    $data['applyDetailsList'][$key]['sectionStatus'] = $this->water_level_pending_model->getSectionHeadStatus($value['id']);
                     $data['applyDetailsList'][$key]['assistantStatus'] = $this->water_level_pending_model->getAssistantEngineerStatus($value['id']);
                     $data['applyDetailsList'][$key]['executiveStatus'] = $this->water_level_pending_model->getExecutiveOfficerStatus($value['id']);
                    /*if( $applicant = $this->water_applicant_details_model->applicantDetails($value['id'])){
                        $data['applyDetailsList'][$key]['applicant'] = $applicant;
                    }*/

                    if( $applicant = $this->water_applicant_details_model->getApplicantDetails($value['id']))
                    {
                        $data['applyDetailsList'][$key]['applicant'] = $applicant;
                    }

                    $data['applyDetailsList'][$key]['property_type'] = $this->water_property_type_mstr_model->propertyDetails($value['id']);
                }
                //print_var($data['applyDetailsList']);die;
                return view('water/report/track_application',$data);  
            }
        }
        else 
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['applyDetailsList'] = $this->WaterApplyNewConnectionModel->getAllDataBydate($data);
                foreach ($data['applyDetailsList'] as $key => $value) {
                    $data['applyDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_id']);
                    $data['applyDetailsList'][$key]['dealingStatus'] = $this->water_level_pending_model->getDealingAssistantStatus($value['id']); 
                    $data['applyDetailsList'][$key]['juniorStatus'] = $this->water_level_pending_model->getJuniorEngineerStatus($value['id']);
                    $data['applyDetailsList'][$key]['sectionStatus'] = $this->water_level_pending_model->getSectionHeadStatus($value['id']);
                     $data['applyDetailsList'][$key]['assistantStatus'] = $this->water_level_pending_model->getAssistantEngineerStatus($value['id']);
                     $data['applyDetailsList'][$key]['executiveStatus'] = $this->water_level_pending_model->getExecutiveOfficerStatus($value['id']);
                    /*if( $applicant = $this->water_applicant_details_model->applicantDetails($value['id'])){
                        $data['applyDetailsList'][$key]['applicant'] = $applicant;
                    }*/
                    
                    if( $applicant = $this->water_applicant_details_model->getApplicantDetails($value['id'])){
                        $data['applyDetailsList'][$key]['applicant'] = $applicant;
                    }

                    $data['applyDetailsList'][$key]['property_type'] = $this->water_property_type_mstr_model->propertyDetails($value['id']);
                }
            return view('water/report/track_application',$data);
        } 
    }
    public function detail_copy()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //Transaction Mode List
        $wardList = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);
        $data['wardList'] = $wardList;
        if($this->request->getMethod()=='post')
        { print_var('hear');die;
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            if($data['ward_mstr_id']!="")
            {
                $data['applyDetailsList'] = $this->WaterApplyNewConnectionModel->getAllData($data);
                foreach ($data['applyDetailsList'] as $key => $value) 
                {
                    $data['applyDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_id']);
                    $data['applyDetailsList'][$key]['dealingStatus'] = $this->water_level_pending_model->getDealingAssistantStatus($value['id']); 
                    $data['applyDetailsList'][$key]['juniorStatus'] = $this->water_level_pending_model->getJuniorEngineerStatus($value['id']);
                    $data['applyDetailsList'][$key]['sectionStatus'] = $this->water_level_pending_model->getSectionHeadStatus($value['id']);
                     $data['applyDetailsList'][$key]['assistantStatus'] = $this->water_level_pending_model->getAssistantEngineerStatus($value['id']);
                     $data['applyDetailsList'][$key]['executiveStatus'] = $this->water_level_pending_model->getExecutiveOfficerStatus($value['id']);
                    /*if( $applicant = $this->water_applicant_details_model->applicantDetails($value['id'])){
                        $data['applyDetailsList'][$key]['applicant'] = $applicant;
                    }*/

                    if( $applicant = $this->water_applicant_details_model->getApplicantDetails($value['id'])){
                        $data['applyDetailsList'][$key]['applicant'] = $applicant;
                    }

                    $data['applyDetailsList'][$key]['property_type'] = $this->water_property_type_mstr_model->propertyDetails($value['id']);
                }
                
                return view('water/report/track_application_copy',$data);  
            }
            else
            {
                $data['applyDetailsList'] = $this->WaterApplyNewConnectionModel->getAllDataBydate($data);
                foreach ($data['applyDetailsList'] as $key => $value) 
                {
                    $data['applyDetailsList'][$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_id']);
                    $data['applyDetailsList'][$key]['dealingStatus'] = $this->water_level_pending_model->getDealingAssistantStatus($value['id']); 
                    $data['applyDetailsList'][$key]['juniorStatus'] = $this->water_level_pending_model->getJuniorEngineerStatus($value['id']);
                    $data['applyDetailsList'][$key]['sectionStatus'] = $this->water_level_pending_model->getSectionHeadStatus($value['id']);
                     $data['applyDetailsList'][$key]['assistantStatus'] = $this->water_level_pending_model->getAssistantEngineerStatus($value['id']);
                     $data['applyDetailsList'][$key]['executiveStatus'] = $this->water_level_pending_model->getExecutiveOfficerStatus($value['id']);
                    /*if( $applicant = $this->water_applicant_details_model->applicantDetails($value['id'])){
                        $data['applyDetailsList'][$key]['applicant'] = $applicant;
                    }*/

                    if( $applicant = $this->water_applicant_details_model->getApplicantDetails($value['id']))
                    {
                        $data['applyDetailsList'][$key]['applicant'] = $applicant;
                    }

                    $data['applyDetailsList'][$key]['property_type'] = $this->water_property_type_mstr_model->propertyDetails($value['id']);
                }
                //print_var($data['applyDetailsList']);die;
                return view('water/report/track_application_copy',$data);  
            }
        }
        else 
        {
            return view('water/report/track_application_copy',$data);
        } 
    }

    public function detail_copy2()
    {
        print_var('kkk');die;
    }
}
?>
