<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Controllers\SAF\SAFHelper;
use App\Controllers\SAF\SAFCal;
use App\Controllers\BO_SAF;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_ward_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_view_saf_dtl;
use App\Models\model_prop_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_view_saf_floor_details;
use App\Models\model_saf_floor_arv_dtl;
use App\Models\model_saf_tax;
use App\Models\model_saf_demand;
use App\Models\model_saf_doc_dtl;
use App\Models\model_transaction;
use App\Models\model_level_pending_dtl;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_saf_geotag_upload_dtl;
use App\Models\model_saf_memo_dtl;
use App\Models\model_view_saf_receive_list;
use App\Models\model_prop_owner_detail;


class TCVerification extends AlphaController
{
	protected $db;
	protected $dbSystem;
	protected $model_ulb_mstr;
    protected $model_ward_mstr;
	protected $model_fy_mstr;
    protected $model_saf_dtl;
    protected $model_view_saf_dtl;
    protected $model_prop_dtl;
	protected $model_saf_owner_detail;
	protected $model_view_saf_floor_details;
	protected $model_saf_floor_arv_dtl;
	protected $model_saf_tax;
    protected $model_saf_demand;
    protected $model_saf_doc_dtl;
    protected $model_transaction;
    protected $model_level_pending_dtl;
    protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
    protected $model_saf_geotag_upload_dtl;
    protected $model_saf_memo_dtl;
	protected $model_view_saf_receive_list;
    protected $BO_SAF_Controller;
    protected $model_prop_owner_detail;

    public function __construct()
    {
        parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect($db_name); 
        }
		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
		$this->model_view_saf_floor_details = new model_view_saf_floor_details($this->db);
		$this->model_saf_floor_arv_dtl = new model_saf_floor_arv_dtl($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
        $this->model_transaction = new model_transaction($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
        $this->model_saf_geotag_upload_dtl = new model_saf_geotag_upload_dtl($this->db);
        $this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
		$this->model_view_saf_receive_list = new model_view_saf_receive_list($this->db);
        $this->BO_SAF_Controller = new BO_SAF($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
    }

    public function index2($field_verification_id)
    {
        $data=array();
        $data['verification_data'] = $this->model_field_verification_dtl->getFieldVerificationDtlBySafDtlIdAndVerifiedBy($field_verification_id);
        if($data['verification_data']) {
            
            $data['assessment_data'] = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId(md5($data['verification_data']['saf_dtl_id']));   
            $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId($data['verification_data']);
            // Vacant land
            
            if($data['verification_data']['prop_type_mstr_id']!=4) {
                $data['saf_floor_data'] = $this->model_view_saf_floor_details->getDataBySafDtlId($data['verification_data']);
                $data["verification_floor_data"] = $this->model_field_verification_floor_details->getagencyDataBymstrId($data["verification_data"]["id"], $data['verification_data']["verified_by"]);
                //print_var($data["verification_floor_data"]);
                $data["ExtraFloorAddedByTC"] = $this->model_field_verification_floor_details->getExtraFloorAddedByTC($data["verification_data"]["id"], $data['verification_data']["verified_by"]);
            }
            if($data['verification_data']['verified_by']=='AGENCY TC') {
                $data["safGeoTaggingDtl"] = $this->model_saf_geotag_upload_dtl->getAllGeoTagImgDtlBySafDtlId($data['verification_data']);
            } else if ($data['verification_data']['verified_by']=='ULB TC') {
                $safCal = new SAFCal();
                $newSafTaxDtl = $safCal->safCalTax($data['verification_data']);
                $safTaxDtl = $this->model_saf_tax->getJoinBySafDtlIdMd5(md5($data['verification_data']['saf_dtl_id']));
                
                $data['safTaxDtl'] = $safTaxDtl;
                $data['newSafTaxDtl'] = $newSafTaxDtl;
                $data['diffTax'] = $safCal->calcDiffPanelty($safTaxDtl, $newSafTaxDtl);
            }
            return view('property/saf/TCVerification', $data);
        }
    }

    public function index($field_verification_id)
    {
        $safHelper = new SAFHelper($this->db);
        $data=array();
        $data['verification_data'] = $this->model_field_verification_dtl->getFieldVerificationDtlBySafDtlIdAndVerifiedBy($field_verification_id);
        //print_var($data['verification_data']);
        if($data['verification_data']) {
            
            $data['assessment_data'] = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId(md5($data['verification_data']['saf_dtl_id']));   
            $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId($data['verification_data']);
            // Vacant land
            if($data['verification_data']['prop_type_mstr_id']!=4) {
                $data['saf_floor_data'] = $this->model_view_saf_floor_details->getDataBySafDtlId($data['verification_data']);
                $data["verification_floor_data"] = $this->model_field_verification_floor_details->getagencyDataBymstrId($data["verification_data"]["id"], $data['verification_data']["verified_by"]);
                //print_var($data["verification_floor_data"]);
                $data["ExtraFloorAddedByTC"] = $this->model_field_verification_floor_details->getExtraFloorAddedByTC($data["verification_data"]["id"], $data['verification_data']["verified_by"]);
            }
            if($data['verification_data']['verified_by']=='AGENCY TC') {
                $data["safGeoTaggingDtl"] = $this->model_saf_geotag_upload_dtl->getAllGeoTagImgDtlBySafDtlId($data['verification_data']);
            } else if ($data['verification_data']['verified_by']=='ULB TC') {
                
                $inputs = $data['verification_data'];
                $inputs["new_ward_mstr_id"] = $inputs["ward_mstr_id"];
                if ($inputs["is_mobile_tower"]=="t") $inputs["is_mobile_tower"] = 1; else $inputs["is_mobile_tower"] = 0;
                if ($inputs["is_hoarding_board"]=="t") $inputs["is_hoarding_board"] = 1; else $inputs["is_hoarding_board"] = 0;
                if ($inputs["is_petrol_pump"]=="t") $inputs["is_petrol_pump"] = 1; else $inputs["is_petrol_pump"] = 0;
                if ($inputs["is_water_harvesting"]=="t") $inputs["is_water_harvesting"] = 1; else $inputs["is_water_harvesting"] = 0;

                if ($inputs["prop_type_mstr_id"]==4) {
                    $inputs["land_occupation_date"] = $data["assessment_data"]["land_occupation_date"];

                    if ($inputs['road_type_mstr_id']!=4) {
                        $vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
                    } else {
                        $vacantDtlArr = $safHelper->makeVacantWithNoRoadFloorDtlArr($inputs);
                    }                  
                    list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calVacantTaxDtl($vacantDtlArr);
                } else {
                    $floorKey = 0;
                    foreach ($data["verification_floor_data"] as $key => $value) {
                        $inputs["floor_mstr_id"][$floorKey] = $value["floor_mstr_id"];
                        $inputs["usage_type_mstr_id"][$floorKey] = $value["usage_type_mstr_id"];
                        $inputs["const_type_mstr_id"][$floorKey] = $value["const_type_mstr_id"];
                        $inputs["occupancy_type_mstr_id"][$floorKey] = $value["occupancy_type_mstr_id"];
                        $inputs["builtup_area"][$floorKey] = $value["builtup_area"];
                        $inputs["date_from"][$floorKey] = date("Y-m", strtotime($value["date_from"]));
                        $inputs["date_upto"][$floorKey] = "";
                        if ($value["date_upto"]!="" && $value["date_upto"]!="null") {
                            $inputs["date_upto"][$floorKey] = date("Y-m", strtotime($value["date_upto"]));
                        }
                        $floorKey++;
                    }
                    // foreach ($data["ExtraFloorAddedByTC"] as $key => $value) {
                    //     $inputs["floor_mstr_id"][$floorKey] = $value["floor_mstr_id"];
                    //     $inputs["usage_type_mstr_id"][$floorKey] = $value["usage_type_mstr_id"];
                    //     $inputs["const_type_mstr_id"][$floorKey] = $value["const_type_mstr_id"];
                    //     $inputs["occupancy_type_mstr_id"][$floorKey] = $value["occupancy_type_mstr_id"];
                    //     $inputs["builtup_area"][$floorKey] = $value["builtup_area"];
                    //     $inputs["date_from"][$floorKey] = date("Y-m", strtotime($value["date_from"]));
                    //     $inputs["date_upto"][$floorKey] = "";
                    //     if ($value["date_upto"]!="" && $value["date_upto"]!="null") {
                    //         $inputs["date_upto"][$floorKey] = date("Y-m", strtotime($value["date_upto"]));
                    //     }
                    //     $floorKey++;
                    // }

                    $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                    $data['floorDtlArr'] = $floorDtlArr;
                    
                    $isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
                    list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calBuildingTaxDtl($floorDtlArr, $inputs['prop_type_mstr_id'], $isAdditionaTaxImplemented);
                    //$data['safTaxDtl'] = $newSafTaxDtl;
                    //print_var($newSafTaxDtl);
                }
                //print_var($inputs);


                //$newSafTaxDtl = $safCal->safCalTax($data['verification_data']);
                $safTaxDtl = $this->model_saf_tax->getJoinBySafDtlIdMd5(md5($data['verification_data']['saf_dtl_id']));
                /* $session = session();
                $emp_details = $session->get("emp_details");
                if ($emp_details["id"]==1) {
                    print_var($safTaxDtl);
                } */
                $data['safTaxDtl'] = $safTaxDtl;
                $data['newSafTaxDtl'] = $newSafTaxDtl;
                $data['diffTax'] = $safHelper->calcDiffPanelty($safTaxDtl, $newSafTaxDtl);
                //print_var($data['diffTax']);
            }
            return view('property/saf/TCVerification', $data);
        }
    }
}