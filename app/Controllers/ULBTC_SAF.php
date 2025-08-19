<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_view_saf_receive_list;
use App\Models\model_ward_mstr;
use App\Models\model_view_saf_doc_dtl;
use App\Models\model_level_pending_dtl;
use App\Models\model_emp_dtl_permission;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_doc_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_saf_memo_dtl;
use App\Models\model_saf_floor_details;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_floor_details;
use App\Models\model_saf_tax;
use App\Models\model_prop_tax;
use App\Models\model_saf_demand;
use App\Models\model_prop_demand;
use App\Models\model_fy_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_view_saf_floor_details;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_view_ward_permission;

class ULBTC_SAF extends AlphaController
{
    protected $db;
    protected $dbSystem;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_emp_dtl_permission = new model_emp_dtl_permission($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_view_saf_receive_list = new model_view_saf_receive_list($this->db);
        $this->model_view_saf_doc_dtl = new model_view_saf_doc_dtl($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
        $this->model_saf_floor_details = new model_saf_floor_details($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_prop_floor_details = new model_prop_floor_details($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_prop_tax = new model_prop_tax($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_prop_demand = new model_prop_demand($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->model_view_saf_floor_details = new model_view_saf_floor_details($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
    }

    public function index()
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        //print_r($emp_mstr);

        $receiver_emp_details_id = $emp_mstr["user_type_mstr_id"];
        //print_r($ward_permission);
        $ward="";

        $i=0;
        foreach($wardList as $key => $value){
            if($i==0){
                $ward=array($value['ward_mstr_id']);
            }else{
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
         }

        helper(['form']);
        if($this->request->getMethod()=='post'){
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
             $data['from_date'] = $this->request->getVar('from_date');
             $data['to_date'] = $this->request->getVar('to_date');
            if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->model_view_saf_receive_list->wardwiseulbtcsafList($receiver_emp_details_id,$data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else{
                $data['posts'] = $this->model_view_saf_receive_list->ulbtcsafList($receiver_emp_details_id,$data['from_date'],$data['to_date'],$ward);
            }


        //$data['posts'] = $this->model_view_saf_receive_list->sisafList($receiver_emp_details_id,$ward);
        //print_r($data['posts']);
        $j=0;
        foreach($data['posts'] as $key => $value){
                   $previous_hold_id=$this->model_prop_dtl->getholdingnobysafid($value['saf_dtl_id']);
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                   $owner = $this->model_saf_owner_detail->ownerdetails($value['saf_dtl_id']);
                    $j=0;
                   foreach($owner as $keyy => $val){
                        //$ow[$key][$keyy]['owner']= $val["owner_name"];
                       if($j==0){
                           $data['posts'][$key]['owner_name']=array($val["owner_name"]);
                           $data['posts'][$key]['mobile_no']=array($val["mobile_no"]);
                       }else{
                           array_push($data['posts'][$key]['owner_name'], $val["owner_name"]);
                           array_push($data['posts'][$key]['mobile_no'], $val["mobile_no"]);
                       }
                       $j++;

                   }
                  $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
                  $data['posts'][$key]['prop_holding_no'] = $previous_hold_id['holding_no'];
               }
            //print_r($data['posts']);
             return view('property/saf/ulbtc_saf_list', $data);
            }
        else
            {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_saf_receive_list->ulbtcsafList($receiver_emp_details_id,$data['from_date'],$data['to_date'],$ward);
        //$data['posts'] = $this->model_view_saf_receive_list->sisafList($receiver_emp_details_id,$ward);
        //print_r($data['posts']);
        $j=0;
        foreach($data['posts'] as $key => $value){
                   $previous_hold_id=$this->model_prop_dtl->getholdingnobysafid($value['saf_dtl_id']);
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                   $owner = $this->model_saf_owner_detail->ownerdetails($value['saf_dtl_id']);
                    $j=0;
                   foreach($owner as $keyy => $val){
                        //$ow[$key][$keyy]['owner']= $val["owner_name"];
                       if($j==0){
                           $data['posts'][$key]['owner_name']=array($val["owner_name"]);
                           $data['posts'][$key]['mobile_no']=array($val["mobile_no"]);
                       }else{
                           array_push($data['posts'][$key]['owner_name'], $val["owner_name"]);
                           array_push($data['posts'][$key]['mobile_no'], $val["mobile_no"]);
                       }
                       $j++;

                   }
                  $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
                  $data['posts'][$key]['prop_holding_no'] = $previous_hold_id['holding_no'];

        }
            return view('property/saf/ulbtc_saf_list', $data);
            }


	}
    public function view($id=null)
	{
        $data =(array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['form'] = $this->model_view_saf_receive_list->safreceivedetailbyid($id);
        $data['previous_prop_dtl']=$this->model_prop_dtl->getholdingnobysafid($data['form']['saf_dtl_id']);

        //print_r($data['previous_prop_dtl']);
        //

        $data['owner_list'] = $this->model_saf_owner_detail->ownerdetails($data['form']['saf_dtl_id']);
        $verify_status='1';

        //$data['applicant_image'] = $this->model_saf_doc_dtl->get_details_by_safid($data['form']['saf_dtl_id']);
        foreach($data['owner_list'] as $key => $value){
                $app_other_doc='applicant_image';
                $app_img = $this->model_saf_doc_dtl->get_details_by_safid($data['form']['saf_dtl_id'],$value['id'],$app_other_doc,$verify_status);
                $app_doc_type="other";
                $app_doc = $this->model_view_saf_doc_dtl->safownerdocnamebydoctype($data['form']['saf_dtl_id'],$value['id'],$app_doc_type,$verify_status);
                $data['owner_list'][$key]['applicant_img'] = $app_img['doc_path'];
                $data['owner_list'][$key]['applicant_img_id'] = $app_img['id'];
                $data['owner_list'][$key]['applicant_img_verify_status'] = $app_img['verify_status'];
                $data['owner_list'][$key]['applicant_doc'] = $app_doc['doc_path'];
                $data['owner_list'][$key]['applicant_doc_name'] = $app_doc['doc_name'];
                $data['owner_list'][$key]['applicant_doc_id'] = $app_doc['id'];
                $data['owner_list'][$key]['applicant_doc_verify_status'] = $app_doc['verify_status'];

            }
        //$data['applicant_image'] = $this->model_saf_doc_dtl->get_details_by_safid($data['form']['saf_dtl_id']);
        //print_r($data['form']);
        $app_doc_type="other";
        $data['applicant_document'] = $this->model_view_saf_doc_dtl->verified_doc_list_by_safid($data['form']['saf_dtl_id'],$app_doc_type);
        $tr_doc_type="transfer_mode";
        $data['prop_tr_mode_document'] = $this->model_view_saf_doc_dtl->verified_doc_list_by_safid($data['form']['saf_dtl_id'],$tr_doc_type);
        $pr_doc_type="property_type";
        $data['prop_pr_mode_document'] = $this->model_view_saf_doc_dtl->verified_doc_list_by_safid($data['form']['saf_dtl_id'],$pr_doc_type);
        $data['dl_remarks'] = $this->model_level_pending_dtl->dl_remarks_by_saf_id($data['form']['saf_dtl_id']);
        $data['ulb_tc_remarks'] = $this->model_level_pending_dtl->ulbtc_remarks_by_saf_id($data['form']['saf_dtl_id'],$sender_user_type_id);
            //print_r($data['dl_remarks']);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no'] = $data['ward']['ward_no'];
        $saf_dtl_id=$data['form']['saf_dtl_id'];
        $data['tax_list'] = $this->model_saf_tax->tax_list($data['form']['saf_dtl_id']);
        $data['occupancy_detail'] = $this->model_saf_floor_details->occupancy_detail($data['form']['saf_dtl_id']);
        /****** verification code starts***********/
        $data["ward_list"]=$this->model_ward_mstr->getWardList($data);
        $data["Saf_detail"]=$this->model_saf_dtl->Saf_details_md5(md5($data['form']['saf_dtl_id']));
        $data["Saf_dtl_id"]=$data["Saf_detail"]["id"];
        $data["saf_no"]=$data["Saf_detail"]["saf_no"];        
        $data["area_of_plot"]=$data["Saf_detail"]["area_of_plot"];        
        $data["apply_date"]=$data["Saf_detail"]["apply_date"]; 
        $data["is_hoarding_board"]= $data["Saf_detail"]["is_hoarding_board"]; 
        $data["hoarding_area"]= $data["Saf_detail"]["hoarding_area"]; 
        $data["hoarding_installation_date"]= $data["Saf_detail"]["hoarding_installation_date"]; 
        $data["is_mobile_tower"]= $data["Saf_detail"]["is_mobile_tower"]; 
        $data["tower_area"]= $data["Saf_detail"]["tower_area"]; 
        $data["tower_installation_date"]= $data["Saf_detail"]["tower_installation_date"];
        $data["is_petrol_pump"]= $data["Saf_detail"]["is_petrol_pump"]; 
        $data["under_ground_area"]= $data["Saf_detail"]["under_ground_area"]; 
        $data["petrol_pump_completion_date"]= $data["Saf_detail"]["petrol_pump_completion_date"];
        $data["is_water_harvesting"]= $data["Saf_detail"]["is_water_harvesting"]; 
        $data["land_occupation_date"]= $data["Saf_detail"]["land_occupation_date"]; 
        $data["ward_detail"]=$this->model_ward_mstr->getdatabyid($data["Saf_detail"]["ward_mstr_id"]);            
        $data["prop_type_detail"]=$this->model_prop_type_mstr->getdatabyid($data["Saf_detail"]["prop_type_mstr_id"]);
        $data["prop_type_list"]=$this->model_prop_type_mstr->getPropTypeList();
        $data["road_type_detail"]=$this->model_road_type_mstr->getdatabyid($data["Saf_detail"]["road_type_mstr_id"]);
        $data["road_type_list"]=$this->model_road_type_mstr->getRoadTypeList();
        $data["ward_no"]= $data["ward_detail"]["ward_no"];
        $data["property_type"]= $data["prop_type_detail"]["property_type"];
        $data["road_type"]= $data["road_type_detail"]["road_type"];
        $data["owner_detail"]=$this->model_saf_owner_detail->ownerdetails_md5(md5($data['form']['saf_dtl_id']));
        $data["floor_details"]=$this->model_view_saf_floor_details->getDataBySafDtlId_md5(md5($data['form']['saf_dtl_id']));

        ///////agency code

        $tc_user_for='AGENCY';
        $data["fieldVerificationmstr_detail"]=$this->model_field_verification_dtl->getulbdatabysafid($data["Saf_dtl_id"],$tc_user_for);
        $data["vward_detail"]=$this->model_ward_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["ward_mstr_id"]);
        $data["vprop_type_detail"]=$this->model_prop_type_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["prop_type_mstr_id"]);
        $data["vroad_type_detail"]=$this->model_road_type_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["road_type_mstr_id"]);
         $data["vward_no"]= $data["vward_detail"]["ward_no"];
        $data["vproperty_type"]= $data["vprop_type_detail"]["property_type"];
        $data["vroad_type"]= $data["vroad_type_detail"]["road_type"];
        $data["varea_of_plot"]=$data["fieldVerificationmstr_detail"]["area_of_plot"];
        
        $data["vis_hoarding_board"]= $data["fieldVerificationmstr_detail"]["is_hoarding_board"]; 
        $data["vhoarding_area"]= $data["fieldVerificationmstr_detail"]["hoarding_area"]; 
        $data["vhoarding_installation_date"]= $data["fieldVerificationmstr_detail"]["hoarding_installation_date"];

        $data["vis_mobile_tower"]= $data["fieldVerificationmstr_detail"]["is_mobile_tower"]; 
        $data["vtower_area"]= $data["fieldVerificationmstr_detail"]["tower_area"]; 
        $data["vtower_installation_date"]= $data["fieldVerificationmstr_detail"]["tower_installation_date"];
        $data["vis_petrol_pump"]= $data["fieldVerificationmstr_detail"]["is_petrol_pump"]; 
        $data["vunder_ground_area"]= $data["fieldVerificationmstr_detail"]["under_ground_area"]; 
        $data["vpetrol_pump_completion_date"]= $data["fieldVerificationmstr_detail"]["petrol_pump_completion_date"];
        $data["vis_water_harvesting"]= $data["fieldVerificationmstr_detail"]["is_water_harvesting"]; 
        $data["vland_occupation_date"]= $data["fieldVerificationmstr_detail"]["land_occupation_date"]; 
        $data["vfloor_details"]= $this->model_field_verification_floor_details->getagencyDataBymstrId($data["fieldVerificationmstr_detail"]["id"],$tc_user_for);

        ///////agency code

        $utc_user_for='ULB';
        $data["ufieldVerificationmstr_detail"]=$this->model_field_verification_dtl->getulbdatabysafid($data["Saf_dtl_id"],$utc_user_for);
        $data["uvward_detail"]=$this->model_ward_mstr->getdatabyid($data["ufieldVerificationmstr_detail"]["ward_mstr_id"]);
        $data["uvprop_type_detail"]=$this->model_prop_type_mstr->getdatabyid($data["ufieldVerificationmstr_detail"]["prop_type_mstr_id"]);
        $data["uvroad_type_detail"]=$this->model_road_type_mstr->getdatabyid($data["ufieldVerificationmstr_detail"]["road_type_mstr_id"]);
         $data["uvward_no"]= $data["uvward_detail"]["ward_no"];
        $data["uvproperty_type"]= $data["uvprop_type_detail"]["property_type"];
        $data["uvroad_type"]= $data["uvroad_type_detail"]["road_type"];
        $data["uvarea_of_plot"]=$data["ufieldVerificationmstr_detail"]["area_of_plot"];
        $data["uvis_mobile_tower"]= $data["ufieldVerificationmstr_detail"]["is_mobile_tower"]; 
        $data["uvtower_area"]= $data["ufieldVerificationmstr_detail"]["tower_area"]; 
        $data["uvtower_installation_date"]= $data["ufieldVerificationmstr_detail"]["tower_installation_date"];
        $data["uvis_petrol_pump"]= $data["ufieldVerificationmstr_detail"]["is_petrol_pump"]; 
        $data["uvunder_ground_area"]= $data["ufieldVerificationmstr_detail"]["under_ground_area"]; 
        $data["uvpetrol_pump_completion_date"]= $data["ufieldVerificationmstr_detail"]["petrol_pump_completion_date"];
        $data["uvis_water_harvesting"]= $data["ufieldVerificationmstr_detail"]["is_water_harvesting"]; 
        $data["uvland_occupation_date"]= $data["ufieldVerificationmstr_detail"]["land_occupation_date"]; 
        $data["uvfloor_details"]= $this->model_field_verification_floor_details->getagencyDataBymstrId($data["ufieldVerificationmstr_detail"]["id"],$utc_user_for);
        //print_r($data['vfloor_details']);
        /******* verification code ends**********/
             return view('property/saf/ulbtc_saf_view', $data);
    }


}