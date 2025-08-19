<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Controllers\SAF\SAFHelper;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_ward_mstr;
use App\Models\model_transaction;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_floor_details;
use App\Models\model_prop_tax;
use App\Models\model_prop_demand;
use App\Models\ObjectionModel;
use App\Models\model_road_type_mstr;
use App\Models\PropertyTypeModel;
use App\Models\model_floor_mstr;
use App\Models\model_usage_type_mstr;
use App\Models\model_usage_type_dtl;
use App\Models\model_occupancy_type_mstr;
use App\Models\model_const_type_mstr;
use App\Models\model_saf_memo_dtl;
use App\Models\model_prop_basic_update;
use App\Models\model_notice;

use App\Models\model_datatable;
use App\Models\model_view_ward_permission;
use App\Models\ModelPropNoticeSerial;


use CodeIgniter\Session\Session;
use Exception;

class propdtl extends AlphaController
{
	protected $db;
	protected $dbSystem;
	protected $model_ulb_mstr;
	protected $model_fy_mstr;
    protected $model_ward_mstr;
    protected $model_transaction;
	protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_prop_floor_details;
    protected $model_prop_tax;
    protected $model_prop_demand;
    protected $ObjectionModel;
    protected $model_road_type_mstr;
    protected $PropertyTypeModel;
    protected $model_floor_mstr;
	protected $model_usage_type_mstr;
	protected $model_usage_type_dtl;
	protected $model_occupancy_type_mstr;
	protected $model_const_type_mstr;
    protected $model_saf_memo_dtl;
	protected $model_prop_basic_update;
	protected $model_notice;

    protected $model_datatable;
    protected $ModelPropNoticeSerial;
    protected $model_view_ward_permission;


    public function __construct()
    {
        parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper','utility_helper',"php_office_helper"]);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbSystem()) {
           
            $this->dbSystem = db_connect($db_name); 
        }



        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_transaction = new model_transaction($this->db);
		$this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_prop_floor_details = new model_prop_floor_details($this->db);
        $this->model_prop_tax = new model_prop_tax($this->db);
        $this->model_prop_demand = new model_prop_demand($this->db);
        $this->ObjectionModel = new ObjectionModel($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->PropertyTypeModel = new PropertyTypeModel($this->db);
        $this->model_floor_mstr = new model_floor_mstr($this->db);
        $this->model_usage_type_mstr = new model_usage_type_mstr($this->db);
        $this->model_usage_type_dtl = new model_usage_type_dtl($this->db);
        $this->model_occupancy_type_mstr = new model_occupancy_type_mstr($this->db);
        $this->model_const_type_mstr = new model_const_type_mstr($this->db);
        $this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
		$this->model_prop_basic_update = new model_prop_basic_update($this->db);
		$this->model_notice = new model_notice($this->db);

        $this->model_datatable = new model_datatable($this->db);
        $this->ModelPropNoticeSerial = new ModelPropNoticeSerial($this->db);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);

    }

    function __destruct() {
		if (isset($this->db)) $this->db->close();
		if (isset($this->dbSystem)) $this->dbSystem->close();
	}


    public function full($prop_dtl_id_MD5)
    {
        $data=(array)null;
        $prop = $this->model_prop_dtl->get_prop_full_details($prop_dtl_id_MD5);
        $prop = $prop['get_prop_full_details'];
        $data=json_decode($prop, true);
        $data["ulb"] = getUlbDtl();
        $Session = Session();
        $emp_details=$Session->get("emp_details");
        $data["emp_details"]=$emp_details;
        //print_var($prop);
        $data['basic_details_data']=array(
            'ward_no'=> isset($data['ward_no'])?$data['ward_no']:'N/A',
            'new_holding_no'=> isset($data['new_holding_no'])?$data['new_holding_no']:'N/A',
            'new_ward_no'=> isset($data['new_ward_no'])?$data['new_ward_no']:'N/A',
            'holding_no'=> isset($data['holding_no'])?$data['holding_no']:'N/A',
            'assessment_type'=> isset($data['assessment_type'])?$data['assessment_type']:'N/A',
            'plot_no'=> isset($data['plot_no'])?$data['plot_no']:'N/A',
            'property_type'=> isset($data['property_type'])?$data['property_type']:'N/A',
            'area_of_plot'=> isset($data['area_of_plot'])?$data['area_of_plot']:'N/A',
            'ownership_type'=> isset($data['ownership_type'])?$data['ownership_type']:'N/A',
            'is_water_harvesting'=> isset($data['is_water_harvesting'])?$data['is_water_harvesting']:'N/A',
            'holding_type'=> isset($data['holding_type'])?$data['holding_type']:'N/A',
            'prop_address'=> isset($data['prop_address'])?$data['prop_address']:'N/A',
            'road_type'=> isset($data['road_type'])?$data['road_type']:'N/A',
            'zone_mstr_id'=> isset($data['zone_mstr_id'])?$data['zone_mstr_id']:'N/A',
            'entry_type'=> isset($data['entry_type'])?$data['entry_type']:'N/A',
            'flat_registry_date'=> isset($data['flat_registry_date'])?$data['flat_registry_date']:'N/A',
            'created_on'=> isset($data['created_on'])?$data['created_on']:'N/A',
            'prop_type_mstr_id'=> isset($data['prop_type_mstr_id'])?$data['prop_type_mstr_id']:'N/A',
            'appartment_name'=> isset($data['apartment_name'])?$data['apartment_name']:'N/A',
            'apt_code'=> isset($data['apt_code'])?$data['apt_code']:'N/A',
            'prop_type'=> 'prop'

        );
        //$this->cachePage(90);
        return view('property/prop_dtl', $data);
    }
    // Code added on 11-05-2022
    public function fullTest($prop_dtl_id)
    {
        $data=(array)null;
        $Session = Session();

        $prop = $this->model_prop_dtl->get_prop_full_details_int($prop_dtl_id);
        $prop = $prop['get_prop_full_details'];
        $data=json_decode($prop, true);
        
        
        $data["ulb"]=$Session->get("ulb_dtl");
        $data["emp_details"]=$Session->get("emp_details");

        $data['basic_details_data']=array(
            'ward_no'=> isset($data['ward_no'])?$data['ward_no']:'N/A',
            'new_holding_no'=> isset($data['new_holding_no'])?$data['new_holding_no']:'N/A',
            'new_ward_no'=> isset($data['new_ward_no'])?$data['new_ward_no']:'N/A',
            'holding_no'=> isset($data['holding_no'])?$data['holding_no']:'N/A',
            'assessment_type'=> isset($data['assessment_type'])?$data['assessment_type']:'N/A',
            'plot_no'=> isset($data['plot_no'])?$data['plot_no']:'N/A',
            'property_type'=> isset($data['property_type'])?$data['property_type']:'N/A',
            'area_of_plot'=> isset($data['area_of_plot'])?$data['area_of_plot']:'N/A',
            'ownership_type'=> isset($data['ownership_type'])?$data['ownership_type']:'N/A',
            'is_water_harvesting'=> isset($data['is_water_harvesting'])?$data['is_water_harvesting']:'N/A',
            'holding_type'=> isset($data['holding_type'])?$data['holding_type']:'N/A',
            'prop_address'=> isset($data['prop_address'])?$data['prop_address']:'N/A',
            'road_type'=> isset($data['road_type'])?$data['road_type']:'N/A',
            'zone_mstr_id'=> isset($data['zone_mstr_id'])?$data['zone_mstr_id']:'N/A',
            'entry_type'=> isset($data['entry_type'])?$data['entry_type']:'N/A',
            'flat_registry_date'=> isset($data['flat_registry_date'])?$data['flat_registry_date']:'N/A',
            'created_on'=> isset($data['created_on'])?$data['created_on']:'N/A',
            'prop_type_mstr_id'=> isset($data['prop_type_mstr_id'])?$data['prop_type_mstr_id']:'N/A',
            'appartment_name'=> isset($data['apartment_name'])?$data['apartment_name']:'N/A',
            'apt_code'=> isset($data['apt_code'])?$data['apt_code']:'N/A',
            'prop_type'=> 'prop'

        );
        
        return view('property/prop_dtl', $data);
    }

    public function bulkComparativeDemand(){
        if($this->request->getMethod()=='post')
        {
            print_var($_POST);die();

            $data = $this->model_prop_dtl->getbulkDemandData();
            // $this->comparativeTax()
            
        }
        return view('property/bulkDemandPrint');
    }

    public function comparativeTax($prop_dtl_id_MD5=null){
        $safHelper = new SAFHelper($this->db);
        $session=session();
        $ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];
        if ($prop = $this->model_prop_dtl->get_prop_full_details($prop_dtl_id_MD5)) {
            $prop = $prop['get_prop_full_details'];
            $data=json_decode($prop, true);
            $inputs = [
                "ward_mstr_id"=>$data["ward_mstr_id"],
                "zone_mstr_id"=>$data["zone_mstr_id"],
                "prop_type_mstr_id"=>$data["prop_type_mstr_id"],
                "road_type_mstr_id"=>$data["road_type_mstr_id"],
                "area_of_plot"=>$data["area_of_plot"],
                "is_mobile_tower"=>$data["is_mobile_tower"],
                "tower_area"=>$data["tower_area"],
                "tower_installation_date"=>$data["tower_installation_date"],
                "is_hoarding_board"=>$data["is_hoarding_board"],
                "hoarding_area"=>$data["hoarding_area"],
                "hoarding_installation_date"=>$data["hoarding_installation_date"],
                "is_petrol_pump"=>$data["is_petrol_pump"],
                "under_ground_area"=>$data["under_ground_area"],
                "petrol_pump_completion_date"=>$data["petrol_pump_completion_date"],
                "is_water_harvesting"=>$data["is_water_harvesting"],
                "land_occupation_date"=>$data["occupation_date"]
            ];
            if ($data["prop_type_mstr_id"]!=4) {
                foreach ($data["prop_floor_details"] AS $key=>$floorValue) {
                    $inputs["floor_mstr_id"][$key] = $floorValue["floor_mstr_id"];
                    $inputs["usage_type_mstr_id"][$key] = $floorValue["usage_type_mstr_id"];
                    $inputs["const_type_mstr_id"][$key] = $floorValue["const_type_mstr_id"];
                    $inputs["occupancy_type_mstr_id"][$key] = $floorValue["occupancy_type_mstr_id"];
                    $inputs["builtup_area"][$key] = $floorValue["builtup_area"];
                    $inputs["date_from"][$key] = date("Y-m", strtotime($floorValue["date_from"]));
                    $inputs["date_upto"][$key] = ($floorValue["date_upto"]=="")?"":date("Y-m", strtotime($floorValue["date_upto"]));
                }
            }
            //print_var($inputs);

            if ($inputs['prop_type_mstr_id']==4) { // vacant land cal
                if ($inputs['road_type_mstr_id']!=4) {
                    $vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
                } else {
                    $vacantDtlArr = $safHelper->makeVacantWithNoRoadFloorDtlArr($inputs);
                }
                list($safTaxDtl, $new_rule_sub, $cv_rule_sub, $cv_vacant_land_dtl) = $safHelper->calVacantTaxDtl($vacantDtlArr);
                
                $data['new_rule_sub'] = $new_rule_sub;
                $data['cv_rule_sub'] = $cv_rule_sub;
                $data['safTaxDtl'] = $safTaxDtl;
                $data['cv_vacant_land_dtl'][0] = $cv_vacant_land_dtl;
            }else { // building cal
                $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                foreach ($floorDtlArr as $key => $value) {
                    $floorDtlArr[$key]["floor_name"] = $this->model_floor_mstr->getdatabyid($value["floor_mstr_id"])["floor_name"];
                }
                $data['floorDtlArr'] = $floorDtlArr;
            }
            $data['ulb_details'] = $session->get('ulb_dtl');
            $data['ulb_mstr_name'] = $session->get('ulb_dtl');
            return view('property/bulkDemandPrint',$data);
        } 
        // else {
        //     return $this->response->redirect(base_url('err/err'));
        // }
    }

    public function basicEdit($prop_dtl_id_MD5) {
        $Session = Session();
        $emp_details = $Session->get("emp_details");
        $emp_details_id = $emp_details["id"];
        $ulb_dtl = $Session->get("ulb_dtl");

        if($this->request->getMethod()=='post') {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            if(isset($inputs["save"]) && $inputs["save"]=="Save") {
                $input = [
                    "new_ward_mstr_id"=>$inputs["new_ward_mstr_id"],
                    "ward_mstr_id" => $inputs["ward_mstr_id"],
                    "khata_no"=>$inputs["khata_no"],
                    "plot_no"=>$inputs["plot_no"],
                    "village_mauja_name"=>$inputs["village_mauja_name"],
                    "area_of_plot"=>$inputs["area_of_plot"],
                    "prop_address"=>$inputs["prop_address"],
                    "prop_city"=>$inputs["prop_city"],
                    "prop_dist"=>$inputs["prop_dist"],
                    "prop_pin_code"=>$inputs["prop_pin_code"],
                    "corr_address"=>$inputs["corr_address"],
                    "corr_city"=>$inputs["corr_city"],
                    "corr_dist"=>$inputs["corr_dist"],
                    "corr_pin_code"=>$inputs["corr_pin_code"],
                ];
                $basic_update = [
                    "prop_dtl_id"=>$inputs["prop_dtl_id"],
                    "update_type"=>"PROPERTY BASIC UPDATE",
                    "remarks"=>$inputs["prop_remarks"],
                    "create_on"=>date("Y-m-d H:i:s"),
                    "emp_detail_id"=>$emp_details_id,
                    "status"=>1,
                    "updates_field"=>json_encode($input)
                ];
                if ($prop_basic_update_id = $this->model_prop_basic_update->insertData($basic_update)) {
                    $document_dtl = $this->request->getFile("prop_update_file");
                    $extension = $document_dtl->getExtension();
                    $document_dtl->move(WRITEPATH."/uploads//".$ulb_dtl['city']."/evidence_for_prop_basic_update//", md5($prop_basic_update_id).".".$extension);

                    $updatData = [
                        "supportive_document"=>"/evidence_for_prop_basic_update//".md5($prop_basic_update_id).".".$extension
                    ];
                    if ($this->model_prop_basic_update->updateBYId($prop_basic_update_id, $updatData)) {
                        $updated=$this->model_prop_dtl->updateProp($prop_dtl_id_MD5, $input);
                        if ($inputs["saf_dtl_id"]!="") {
                            $updated=$this->model_saf_dtl->updateById($inputs["saf_dtl_id"], $input);
                        }
                        if($updated) {
                            flashToast("message", "Property detail updated successfully");
                            return $this->response->redirect(base_url("propDtl/full/".$prop_dtl_id_MD5));
                        }
                    }
                } else {
                    flashToast("message", "Opps!! something when wrong...");
                }
            } else if(isset($inputs["submit"]) && $inputs["submit"]=="Update") {
                $input = [
                    "owner_name"=>strtoupper($inputs["owner_name"]),
                    "guardian_name"=>strtoupper($inputs["guardian_name"]),
                    "relation_type"=>strtoupper($inputs["relation_type"]),
                    "mobile_no"=>$inputs["mobile_no"],
                    "aadhar_no"=>($inputs["aadhar_no"]=="")?NULL:$inputs["aadhar_no"],
                    "pan_no"=>strtoupper($inputs["pan_no"]),
                    "email"=>$inputs["email"],
                ];

                $basic_update = [
                    "prop_dtl_id"=>$inputs["owner_prop_dtl_id"],
                    "prop_owner_detail_id"=>$inputs["id"],
                    "update_type"=>"OWNER UPDATE",
                    "remarks"=>'',
                    "create_on"=>date("Y-m-d H:i:s"),
                    "emp_detail_id"=>$emp_details_id,
                    "status"=>1,
                    "updates_field"=>json_encode($input)
                ];
                if ($prop_basic_update_id = $this->model_prop_basic_update->insertData($basic_update)) {
                    $document_dtl = $this->request->getFile("owner_update_file");
                    $extension = $document_dtl->getExtension();
                    $document_dtl->move(WRITEPATH."/uploads//".$ulb_dtl['city']."/evidence_for_prop_basic_update//", md5($prop_basic_update_id).".".$extension);

                    $updatData = [
                        "supportive_document"=>"/evidence_for_prop_basic_update//".md5($prop_basic_update_id).".".$extension
                    ];
                    if ($this->model_prop_basic_update->updateBYId($prop_basic_update_id, $updatData)) {
                        if ($inputs["owner_saf_dtl_id"]!="") {
                            if ($lastOwnerDtl =$this->model_prop_owner_detail->getOwnerDtlById($inputs["id"])) {
                                $sql = "SELECT id, saf_dtl_id FROM tbl_saf_owner_detail WHERE saf_dtl_id='".$inputs["owner_saf_dtl_id"]."' AND owner_name ILIKE '".$lastOwnerDtl["owner_name"]."' AND mobile_no::TEXT ILIKE '".$lastOwnerDtl["mobile_no"]."'";
                                if($saf_owner_dtl = $this->db->query($sql)->getFirstRow("array")) {
                                    $this->model_saf_owner_detail->UpdateOwner($input, $saf_owner_dtl["id"]);
                                }
                            }
                        }
                        $updated = $this->model_prop_owner_detail->UpdateOwner($input, $inputs["id"]);
                        if ($updated) {
                            flashToast("message", "Owner detail updated successfully");
                            return $this->response->redirect(base_url("propDtl/full/".$prop_dtl_id_MD5));
                        }
                    }
                } else {
                    flashToast("message", "Opps!! something when wrong...");
                }
            }
        }
        $prop = $this->model_prop_dtl->get_prop_full_details($prop_dtl_id_MD5);
        $prop = $prop['get_prop_full_details'];
        $data=json_decode($prop, true);
        //print_var($data);

        $data["ulb"]=$Session->get("ulb_dtl");
        $data["emp_details"]=$Session->get("emp_details");
        $data["ward_list"]=$this->model_ward_mstr->getWardList(["ulb_mstr_id"=> $data["ulb"]["ulb_mstr_id"]]);
        return view('property/edit_prop_basic', $data);
    }
	
    public function edit($prop_dtl_id_MD5)
    {
        $data=(array)null;
        $Session = Session();
        if($this->request->getMethod()=='post')
        {

            $inputs = arrFilterSanitizeString($this->request->getVar());
            
            if(isset($inputs["save"]) && $inputs["save"]=="Save")
            {
                unset($inputs["save"]);
                $updated=$this->model_prop_dtl->updateProp($prop_dtl_id_MD5, $inputs);
                if($updated){
                    flashToast("message", "Property detail updated successfully");
                }
            }
            else if(isset($inputs["submit"]) && $inputs["submit"]=="Update")
            {
                unset($inputs["submit"]);
                $updated=$this->model_prop_owner_detail->UpdateOwner($inputs, $inputs["id"]);
                if($updated){
                    flashToast("message", "Owner detail updated successfully");
                }
            }
            
        }
        $prop = $this->model_prop_dtl->get_prop_full_details($prop_dtl_id_MD5);
        $prop = $prop['get_prop_full_details'];
        $data=json_decode($prop, true);
        
        
        $data["ulb"]=$Session->get("ulb_dtl");
        $data["emp_details"]=$Session->get("emp_details");
        $data["ward_list"]=$this->model_ward_mstr->getWardList(["ulb_mstr_id"=> $data["ulb"]["ulb_mstr_id"]]);
        // print_var($data);
        // return;
        
        return view('property/edit_prop', $data);
        
    }

    public function ObjectionList()
    {
        $data=(array)null;
        $data["result"]=$this->ObjectionModel->ObjectionList();
        return view('property/ObjectionList', $data);
    }

    public function applyObjection($prop_dtl_id_MD5)
    {
        $data=(array)null;
        $Session = Session();
        
        $data = $this->model_prop_dtl->getPropDtlByMD5PropDtlId($prop_dtl_id_MD5);

        //Checking If Objection is already applied
        $objection=$this->ObjectionModel->GetObjectionByPropId($data["prop_dtl_id"]);
        if(!empty($objection))
        {
            
            flashToast("message", "Objection is already applied of this holding");
            return $this->response->redirect(base_url("propDtl/ViewObjection/".md5($objection["id"])));
        }

        //Checking SAF is fully approved or not
        $data['Memo']=$this->model_saf_memo_dtl->getAllMemo(["saf_dtl_id"=> $data["saf_dtl_id"]]);
        $fam = array_filter($data['Memo'], function ($var) {
            return ($var['memo_type'] == 'FAM');
        });

        if(empty($fam))
        {
            
            flashToast("message", "Final approval is not done. So, you cant apply for objection.");
            return $this->response->redirect(base_url("propDtl/full/".md5($data["prop_dtl_id"])));
        }


        $data['prop_owner_detail'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id'=> $data['prop_dtl_id']]);
        $data['prop_floor_details'] = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=> $data['prop_dtl_id']]);
        $data["objectionTypeList"] = $this->ObjectionModel->objectionTypeList();
        $data["roadTypeList"] = $this->model_road_type_mstr->getRoadTypeList();
        $data["propertyTypeList"] = $this->PropertyTypeModel->getPropertyTypeList();
        
		$data["floorList"] = $this->model_floor_mstr->getFloorList();
		$data["usageTypeList"] = $this->model_usage_type_mstr->getUsageTypeList();
		$data["occupancyTypeList"] = $this->model_occupancy_type_mstr->getOccupancyTypeList();
		$data["constTypeList"] = $this->model_const_type_mstr->getConstTypeList();
        $data["ulb"]=$Session->get("ulb_dtl");
        $data["emp_details"]=$Session->get("emp_details");

        
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            if(is_array($inputs["objection_type_id"]) && !empty($inputs["objection_type_id"]))
            {
                // 1	Typographical Error
                // 2	Rainwater Harvesting
                // 3	Road Width
                // 4	Property Type
                // 5	Area of Plot
                // 6	Mobile Tower
                // 7	Hoarding Board
                // 8	Other
                // 9	Floor Detail
                $obj=[
                        "prop_dtl_id"=> $data["prop_dtl_id"],
                        "saf_dtl_id"=> $data["saf_dtl_id"],
                        "objection_no"=> null,
                        "holding_no"=> $data["new_holding_no"],
                        "ward_id"=> $data["ward_mstr_id"],
                        "objection_form"=> null,//Insert by trigger
                        "evidence_document"=> null,//Insert by trigger
                        "user_id"=> $data["emp_details"]["id"],
                    ];
                # Note: Objection No generating by trigger
                $objection_id = $this->ObjectionModel->InsertObjection($obj);
                $objection_form = $this->request->getFile("objection_form");
                $extension = $objection_form->getExtension();
                $objection_form->move(WRITEPATH."/uploads//".$data['ulb']['city']."/objection//", "objection_form_".$objection_id.".".$extension);

                $evidence_document = $this->request->getFile("evidence_document");
                $extension = $evidence_document->getExtension();
                $evidence_document->move(WRITEPATH."/uploads//".$data['ulb']['city']."/objection//", "evidence_document_".$objection_id.".".$extension);
                
                
                foreach($inputs["objection_type_id"] as $obj_type_id)
                {
                    # Rainwater Harvesting
                    if($obj_type_id==2)
                    {
                        $objdtl=[
                                    "objection_id"=> $objection_id,
                                    "objection_type_id"=> $obj_type_id,
                                    "according_assessment"=> $data["is_water_harvesting"],
                                    "assess_area"=> null,
                                    "assess_date"=> null,
                                    "according_applicant"=> $inputs["is_water_harvesting"],
                                    "applicant_area"=> null,
                                    "applicant_date"=> null,
                                    "objection_by"=> "Citizen",
                                    "user_id"=> $data["emp_details"]["id"],
                        ];
                        $objection_dtl_id = $this->ObjectionModel->InsertObjectionDetails($objdtl);
                    }

                    # Road Width
                    if($obj_type_id==3)
                    {
                        $objdtl=[
                                    "objection_id"=> $objection_id,
                                    "objection_type_id"=> $obj_type_id,
                                    "according_assessment"=> $data["road_type_mstr_id"],
                                    "assess_area"=> null,
                                    "assess_date"=> null,
                                    "according_applicant"=> $inputs["road_type_mstr_id"],
                                    "applicant_area"=> null,
                                    "applicant_date"=> null,
                                    "objection_by"=> "Citizen",
                                    "user_id"=> $data["emp_details"]["id"],
                        ];
                        $objection_dtl_id = $this->ObjectionModel->InsertObjectionDetails($objdtl);
                        
                    }

                    # Property Type
                    if($obj_type_id==4)
                    {
                        $objdtl=[
                                    "objection_id"=> $objection_id,
                                    "objection_type_id"=> $obj_type_id,
                                    "according_assessment"=> $data["prop_type_mstr_id"],
                                    "assess_area"=> null,
                                    "assess_date"=> null,
                                    "according_applicant"=> $inputs["property_type_id"],
                                    "applicant_area"=> null,
                                    "applicant_date"=> null,
                                    "objection_by"=> "Citizen",
                                    "user_id"=> $data["emp_details"]["id"],
                        ];
                        $objection_dtl_id = $this->ObjectionModel->InsertObjectionDetails($objdtl);
                    }

                    # Area of plot
                    if($obj_type_id==5)
                    {
                        $objdtl=[
                                    "objection_id"=> $objection_id,
                                    "objection_type_id"=> $obj_type_id,
                                    "according_assessment"=> $data["area_of_plot"],
                                    "assess_area"=> null,
                                    "assess_date"=> null,
                                    "according_applicant"=> $inputs["area_of_plot"],
                                    "applicant_area"=> null,
                                    "applicant_date"=> null,
                                    "objection_by"=> "Citizen",
                                    "user_id"=> $data["emp_details"]["id"],
                        ];
                        $objection_dtl_id = $this->ObjectionModel->InsertObjectionDetails($objdtl);
                        
                    }

                    # Mobile Tower
                    if($obj_type_id==6)
                    {
                        $objdtl=[
                                    "objection_id"=> $objection_id,
                                    "objection_type_id"=> $obj_type_id,
                                    "according_assessment"=> $data["is_mobile_tower"],
                                    "assess_area"=> $data["tower_area"],
                                    "assess_date"=> $data["tower_installation_date"],
                                    "according_applicant"=> $inputs["is_mobile_tower"],
                                    "applicant_area"=> $inputs["tower_area"],
                                    "applicant_date"=> $inputs["tower_installation_date"],
                                    "objection_by"=> "Citizen",
                                    "user_id"=> $data["emp_details"]["id"],
                        ];
                        $objection_dtl_id = $this->ObjectionModel->InsertObjectionDetails($objdtl);
                        
                    }

                    # Hording Board
                    if($obj_type_id==7)
                    {
                        $objdtl=[
                                    "objection_id"=> $objection_id,
                                    "objection_type_id"=> $obj_type_id,
                                    "according_assessment"=> $data["is_hoarding_board"],
                                    "assess_area"=> $data["tower_area"],
                                    "assess_date"=> $data["tower_installation_date"],
                                    "according_applicant"=> $inputs["is_hoarding_board"],
                                    "applicant_area"=> $inputs["hoarding_area"],
                                    "applicant_date"=> $inputs["hoarding_installation_date"],
                                    "objection_by"=> "Citizen",
                                    "user_id"=> $data["emp_details"]["id"],
                        ];
                        $objection_dtl_id = $this->ObjectionModel->InsertObjectionDetails($objdtl);
                    }

                    # Floor Details
                    if($obj_type_id==9)
                    {
                        $i=0;
                        foreach($data['prop_floor_details'] as $floor)
                        {
                            $floordtl=[
                                        "prop_dtl_id"=> $floor["prop_dtl_id"],
                                        "objection_id"=> $objection_id,
                                        "objection_type_id"=> $obj_type_id,
                                        "prop_floor_dtl_id"=> $floor["id"],
                                        "floor_mstr_id"=> $floor["floor_mstr_id"],
                                        "usage_type_mstr_id"=> $floor["usage_type_mstr_id"],
                                        "occupancy_type_mstr_id"=> $floor["occupancy_type_mstr_id"],
                                        "const_type_mstr_id"=> $floor["const_type_mstr_id"],
                                        "builtup_area"=> $floor["builtup_area"],
                                        "carpet_area"=> $floor["carpet_area"],
                                        "date_from"=> $floor["date_from"],
                                        "date_upto"=> $floor["date_upto"],
                                        "remarks"=> null,
                                        "objection_by"=> 'Assessment',
                                    ];
                            $this->ObjectionModel->InsertFloorObjectionDetails($floordtl);
                            
                            if($inputs["usage_type_mstr_id"][$i]==1)
                            $objection_carpet_area=$inputs["builtup_area"][$i]*0.7;
                            else
                            $objection_carpet_area=$inputs["builtup_area"][$i]*0.8;
                            
                            
                            $floordtl=[
                                "prop_dtl_id"=> $floor["prop_dtl_id"],
                                "objection_id"=> $objection_id,
                                "objection_type_id"=> $obj_type_id,
                                "prop_floor_dtl_id"=> $floor["id"],
                                "floor_mstr_id"=> $inputs["floor_mstr_id"][$i],
                                "usage_type_mstr_id"=> $inputs["usage_type_mstr_id"][$i],
                                "occupancy_type_mstr_id"=> $inputs["occupancy_type_mstr_id"][$i],
                                "const_type_mstr_id"=> $inputs["const_type_mstr_id"][$i],
                                "builtup_area"=> $inputs["builtup_area"][$i],
                                "carpet_area"=> $objection_carpet_area,
                                "date_from"=> $floor["date_from"],
                                "date_upto"=> $floor["date_upto"],
                                "remarks"=> null,
                                "objection_by"=> 'Citizen',
                            ];
                            $this->ObjectionModel->InsertFloorObjectionDetails($floordtl);
                            $i++;
                        }
                    }
                }
                flashToast("message", "Objection Applied Successfully");
                $this->response->redirect(base_url("propDtl/ViewObjection/".md5($objection_id)));
            }
        }
        
        $data["prop_dtl_id_MD5"]=$prop_dtl_id_MD5;
        return view('property/applyObjection', $data);
    }
    

    public function ViewObjection($objection_id_MD5)
    {
        $data=(array)null;
        $Session = Session();
        
        $objection=$this->ObjectionModel->GetObjection($objection_id_MD5);
        $data = $this->model_prop_dtl->getPropDtlByMD5PropDtlId(md5($objection["prop_dtl_id"]));
        //print_var($data);
        $data["objection"]=$objection;
        
        $data["objection_status"]=$this->ObjectionModel->ObjectionStatus($objection_id_MD5);
        $data["objection_detail"] = $this->ObjectionModel->GetObjectionDetails($objection["id"]);
        $data["assessment_floor_detail"] = $this->ObjectionModel->GetObjectionFloorDetailsAssessment($objection["id"]);
        $data["objection_floor_detail"] = $this->ObjectionModel->GetObjectionFloorDetailsCitizen($objection["id"]);
        
        //print_var($data["objection_detail"]);
        $data['prop_owner_detail'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id'=> $data['prop_dtl_id']]);
        $data["roadTypeList"] = $this->model_road_type_mstr->getRoadTypeList();
        $data["propertyTypeList"] = $this->PropertyTypeModel->getPropertyTypeList();
        
        $data["ulb"]=$Session->get("ulb_dtl");
        $data["emp_details"]=$Session->get("emp_details");

        $data["prop_dtl_id_MD5"]=md5($objection["prop_dtl_id"]);
        return view('property/ViewObjection', $data);
    }


    public function ViewObjection1($objection_id_MD5)
    {
        $data=(array)null;
        $Session = Session();
        
        $objection=$this->ObjectionModel->GetObjection($objection_id_MD5);
        $data = $this->model_prop_dtl->getPropDtlByMD5PropDtlId(md5($objection["prop_dtl_id"]));
        //print_var($data);
        $data["objection"]=$objection;
        
        $data["objection_status"]=$this->ObjectionModel->ObjectionStatus($objection_id_MD5);
        $data["objection_detail"] = $this->ObjectionModel->GetObjectionDetails($objection["id"]);
        $data["assessment_floor_detail"] = $this->ObjectionModel->GetObjectionFloorDetailsAssessment($objection["id"]);
        $data["objection_floor_detail"] = $this->ObjectionModel->GetObjectionFloorDetailsCitizen($objection["id"]);
        
        //print_var($data["objection_detail"]);
        $data['prop_owner_detail'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id'=> $data['prop_dtl_id']]);
        $data["roadTypeList"] = $this->model_road_type_mstr->getRoadTypeList();
        $data["propertyTypeList"] = $this->PropertyTypeModel->getPropertyTypeList();
        
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            if(isset($inputs["btn_reject"]))
            {
                $arr=[
                    "id"=> $objection["id"],
                    "level1_remarks"=> $inputs["level1_remarks"],
                    "level_status"=> 0,//Rejeceted
                ];
                $this->ObjectionModel->UpdateObjection($arr);
                flashToast("message", "Application rejected successfully");
            }

            if(isset($inputs["btn_forward"]))
            {
                $arr=[
                    "id"=> $objection["id"],
                    "level1_remarks"=> $inputs["level1_remarks"],
                    "level_status"=> 2,//Tax Collector
                ];
                $this->ObjectionModel->UpdateObjection($arr);
                flashToast("message", "Application forwarded to tax collector");
            }
            
            $this->response->redirect(base_url("propDtl/ObjectionMail1/"));
        }

        $data["ulb"]=$Session->get("ulb_dtl");
        $data["emp_details"]=$Session->get("emp_details");
        $data["prop_dtl_id_MD5"]=md5($objection["prop_dtl_id"]);
        return view('property/ViewObjection1', $data);
    }
    
    public function ViewObjectionSH($objection_id_MD5)
    {
        $data=(array)null;
        $Session = Session();
        
        $objection=$this->ObjectionModel->GetObjection($objection_id_MD5);
        $data = $this->model_prop_dtl->getPropDtlByMD5PropDtlId(md5($objection["prop_dtl_id"]));
        //print_var($data);
        $data["objection"]=$objection;
        
        $data["objection_status"]=$this->ObjectionModel->ObjectionStatus($objection_id_MD5);
        $data["objection_detail"] = $this->ObjectionModel->GetObjectionDetails($objection["id"]);
        $data["assessment_floor_detail"] = $this->ObjectionModel->GetObjectionFloorDetailsAssessment($objection["id"]);
        $data["objection_floor_detail"] = $this->ObjectionModel->GetObjectionFloorDetailsCitizen($objection["id"]);
        
        //print_var($data["objection_detail"]);
        $data['prop_owner_detail'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id'=> $data['prop_dtl_id']]);
        $data["roadTypeList"] = $this->model_road_type_mstr->getRoadTypeList();
        $data["propertyTypeList"] = $this->PropertyTypeModel->getPropertyTypeList();
        
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            if(isset($inputs["btn_reject"]))
            {
                $arr=[
                    "id"=> $objection["id"],
                    "sh_remarks"=> $inputs["sh_remarks"],
                    "level_status"=> 0,//Rejeceted
                ];
                $this->ObjectionModel->UpdateObjection($arr);
                flashToast("message", "Application rejected successfully");
            }

            if(isset($inputs["btn_forward"]))
            {
                $arr=[
                    "id"=> $objection["id"],
                    "sh_remarks"=> $inputs["sh_remarks"],
                    "level_status"=> 4,//Executice Officer
                ];
                $this->ObjectionModel->UpdateObjection($arr);
                flashToast("message", "Application forwarded to executive officer");
            }
            
            $this->response->redirect(base_url("propDtl/ObjectionMailSH/"));
        }

        $data["ulb"]=$Session->get("ulb_dtl");
        $data["emp_details"]=$Session->get("emp_details");
        $data["prop_dtl_id_MD5"]=md5($objection["prop_dtl_id"]);
        return view('property/ViewObjectionSH', $data);
    }

    public function ViewObjectionEO($objection_id_MD5)
    {
        $data=(array)null;
        $Session = Session();
        
        $objection=$this->ObjectionModel->GetObjection($objection_id_MD5);
        $data = $this->model_prop_dtl->getPropDtlByMD5PropDtlId(md5($objection["prop_dtl_id"]));
        //print_var($data);
        $data["objection"]=$objection;
        
        $data["objection_status"]=$this->ObjectionModel->ObjectionStatus($objection_id_MD5);
        $data["objection_detail"] = $this->ObjectionModel->GetObjectionDetails($objection["id"]);
        $data["assessment_floor_detail"] = $this->ObjectionModel->GetObjectionFloorDetailsAssessment($objection["id"]);
        $data["objection_floor_detail"] = $this->ObjectionModel->GetObjectionFloorDetailsCitizen($objection["id"]);
        
        //print_var($data["objection_detail"]);
        $data['prop_owner_detail'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id'=> $data['prop_dtl_id']]);
        $data["roadTypeList"] = $this->model_road_type_mstr->getRoadTypeList();
        $data["propertyTypeList"] = $this->PropertyTypeModel->getPropertyTypeList();
        
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            if(isset($inputs["btn_reject"]))
            {
                $arr=[
                    "id"=> $objection["id"],
                    "eo_remarks"=> $inputs["eo_remarks"],
                    "level_status"=> 0,//Rejeceted
                ];
                $this->ObjectionModel->UpdateObjection($arr);
                flashToast("message", "Application rejected successfully");
            }
            $this->response->redirect(base_url("propDtl/ObjectionMailEO"));
        }

        $data["ulb"]=$Session->get("ulb_dtl");
        $data["emp_details"]=$Session->get("emp_details");
        $data["prop_dtl_id_MD5"]=md5($objection["prop_dtl_id"]);
        return view('property/ViewObjectionEO', $data);
    }

    public function ObjectionMail1()
    {
        $data=(array)null;
        $data["result"]=$this->ObjectionModel->ObjectionMail1List();
        return view('property/ObjectionMail1', $data);
    }

    public function search($holding_type = null)
    {
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $ward_list = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        
        if($this->request->getMethod()=='post')
        {
            $data = arrFilterSanitizeString($this->request->getVar());
            if($result_list = $this->model_prop_dtl->propSearchUsingWardHoldingNoOwnerMobile($data))
            {
                $data['result_list'] = $result_list;
            }
        }
        $data['ward_list'] = $ward_list;
        $data['holding_type'] = $holding_type;
        return view('property/prop_search', $data);
    }

        
    public function ObjectionMailSH()
    {
        $data=(array)null;
        $data["result"]=$this->ObjectionModel->ObjectionMailSH();
        return view('property/ObjectionMailSH', $data);
    }


    public function ObjectionMailEO()
    {
        $data=(array)null;
        $data["result"]=$this->ObjectionModel->ObjectionMailEO();
        return view('property/ObjectionMailEO', $data);
    }

    public function OnlinePaymentRequest($prop_id) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        try {
            $data = [];
            $sql = "SELECT
                        tbl_prop_dtl.id AS prop_dtl_id,
                        holding_no,
                        new_holding_no,
                        prop_address,
                        owner_dtl.owner_name,
                        owner_dtl.mobile_no
                    FROM tbl_prop_dtl 
                    INNER JOIN (
                        SELECT
                            prop_dtl_id,
                            STRING_AGG(owner_name, ', ') AS owner_name,
                            STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no
                        FROM tbl_prop_owner_detail
                        GROUP BY prop_dtl_id
                    ) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id
                    WHERE id=".$prop_id;
            if ($data = $this->db->query($sql)->getFirstRow("array")) {
                $sql = "SELECT
                                tbl_razor_pay_request.*
                            FROM tbl_razor_pay_request
                            LEFT JOIN tbl_razor_pay_response ON tbl_razor_pay_response.razorpay_payment_id=tbl_razor_pay_request.id
                            WHERE 
                                tbl_razor_pay_request.prop_dtl_id=".$prop_id." 
                                AND tbl_razor_pay_request.module='Property'
                                AND tbl_razor_pay_request.order_id IS NOT NULL
                                AND tbl_razor_pay_response.id IS NULL";
                if ($request_list = $this->db->query($sql)->getResultArray()) {
                    $data["request_list"] = $request_list;
                }
                //print_var($data);
                return view('property/online_payment_request', $data);
            }
        } catch(Exception $e) {
            print_var($e);
        }
    }
	
	public function getDemand_old($prop_id)
    {
        $sql = "SELECT
                tbl_prop_dtl.id,
                view_ward_mstr.ward_no,
                new_ward.ward_no AS new_ward_no,
                tbl_prop_dtl.holding_no,
                tbl_prop_dtl.new_holding_no,
                tbl_prop_dtl.prop_address,
                owner_dtl.owner_name,
                owner_dtl.mobile_no,
                tax_dtl.tax_dtl_temp,
                demand_dtl.demand_dtl_temp,
                demand_dtl.t_balance,
                demand_dtl.additional_amount,
                demand_dtl.adjust_amt,
                demand_dtl.due_date
            FROM tbl_prop_dtl
            INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
            left JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_prop_dtl.new_ward_mstr_id
            INNER JOIN (
                SELECT
                    prop_dtl_id,
                    STRING_AGG(owner_name, ',') AS owner_name,
                    STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
                FROM tbl_prop_owner_detail
                GROUP BY prop_dtl_id
            ) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id
            INNER JOIN (
                SELECT 
                    prop_dtl_id,
                    json_agg(json_build_object('qtr', qtr, 'fy_mstr_id', fy_mstr_id, 'fyear', fyear, 'quarterly_tax', (holding_tax+water_tax+education_cess+health_cess+latrine_tax+additional_tax)) ORDER BY id ASC) AS tax_dtl_temp
                FROM tbl_prop_tax
                GROUP BY prop_dtl_id
            ) AS tax_dtl ON tax_dtl.prop_dtl_id=tbl_prop_dtl.id
            LEFT JOIN (
                SELECT 
                    prop_dtl_id,
                    json_agg(json_build_object('due_date', due_date, 'qtr', qtr, 'fy_mstr_id', fy_mstr_id, 'fyear', fyear, 'amount', amount, 'balance', balance, 'demand_amount', demand_amount, 'additional_amount', additional_amount, 'adjust_amt', adjust_amt) ORDER BY due_date ASC) AS demand_dtl_temp,
                    SUM(balance) AS t_balance,
                    SUM(additional_amount) AS additional_amount,
                    SUM(adjust_amt) AS adjust_amt,
                    max(due_date) AS due_date
                FROM tbl_prop_demand
                WHERE status=1 AND paid_status=0
                GROUP BY prop_dtl_id
            ) AS demand_dtl ON demand_dtl.prop_dtl_id=tbl_prop_dtl.id
            WHERE 
                tbl_prop_dtl.id='".$prop_id."'";
            if ($result = $this->db->query($sql)->getFirstRow("array")) {
                $result["tax_dtl"] = json_decode($result["tax_dtl_temp"], true);
                $uptoIndex = count($result["tax_dtl"])-1;
                $result["quarterly_tax"] = $result["tax_dtl"][$uptoIndex]["quarterly_tax"];

                $result["demand_dtl"] = isset($result["demand_dtl_temp"])?json_decode($result["demand_dtl_temp"], true):array();
                if($result["demand_dtl"]){
                    $result["demand_from_qtr"] = $result["demand_dtl"][0]["qtr"];
                    $result["demand_from_fy"] = $result["demand_dtl"][0]["fyear"];
                    $uptoIndex = count($result["demand_dtl"])-1;
                    $result["demand_upto_qtr"] = $result["demand_dtl"][$uptoIndex]["qtr"];
                    $result["demand_upto_fy"] = $result["demand_dtl"][$uptoIndex]["fyear"];
                    $result["prop_dtl_id"] = $result["id"];
                    $input = [
                        'fy' => $result["demand_upto_fy"],
                        'qtr' => $result["demand_upto_qtr"],
                        'prop_dtl_id' => $result["id"],
                        'user_id' => 2,
                    ];
                    $result["payment_dtl"] = $this->model_prop_demand->getPropDemandAmountDetails($input);
                }
                return $result;
            }
    }

    public function getDemand($prop_id,$fyear=null)
    {
        $sql = "SELECT
                tbl_prop_dtl.id,
                view_ward_mstr.ward_no,
                new_ward.ward_no AS new_ward_no,
                tbl_prop_dtl.holding_no,
                tbl_prop_dtl.new_holding_no,
                tbl_prop_dtl.prop_address,
                owner_dtl.owner_name,
                owner_dtl.mobile_no,
                tax_dtl.tax_dtl_temp,
                demand_dtl.demand_dtl_temp,
                demand_dtl.t_balance,
                demand_dtl.additional_amount,
                demand_dtl.adjust_amt,
                demand_dtl.due_date
            FROM tbl_prop_dtl
            INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
            left JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_prop_dtl.new_ward_mstr_id
            INNER JOIN (
                SELECT
                    prop_dtl_id,
                    STRING_AGG(owner_name, ',') AS owner_name,
                    STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
                FROM tbl_prop_owner_detail
                GROUP BY prop_dtl_id
            ) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id
            INNER JOIN (
                SELECT 
                    prop_dtl_id,
                    json_agg(json_build_object('qtr', qtr, 'fy_mstr_id', fy_mstr_id, 'fyear', fyear, 'quarterly_tax', (holding_tax+water_tax+education_cess+health_cess+latrine_tax+additional_tax)) ORDER BY id ASC) AS tax_dtl_temp
                FROM tbl_prop_tax
                GROUP BY prop_dtl_id
            ) AS tax_dtl ON tax_dtl.prop_dtl_id=tbl_prop_dtl.id
            LEFT JOIN (
                SELECT 
                    prop_dtl_id,
                    json_agg(json_build_object('due_date', due_date, 'qtr', qtr, 'fy_mstr_id', fy_mstr_id, 'fyear', fyear, 'amount', amount, 'balance', balance, 'demand_amount', demand_amount, 'additional_amount', additional_amount, 'adjust_amt', adjust_amt) ORDER BY due_date ASC) AS demand_dtl_temp,
                    SUM(balance) AS t_balance,
                    SUM(additional_amount) AS additional_amount,
                    SUM(adjust_amt) AS adjust_amt,
                    max(due_date) AS due_date
                FROM tbl_prop_demand
                WHERE status=1 AND paid_status=0 ".($fyear ? " AND tbl_prop_demand.fyear<='$fyear' ":"")."
                GROUP BY prop_dtl_id
            ) AS demand_dtl ON demand_dtl.prop_dtl_id=tbl_prop_dtl.id
            WHERE 
                tbl_prop_dtl.id='".$prop_id."'";
            if ($result = $this->db->query($sql)->getFirstRow("array")) {
                $result["tax_dtl"] = json_decode($result["tax_dtl_temp"], true);
                $uptoIndex = count($result["tax_dtl"])-1;
                $result["quarterly_tax"] = $result["tax_dtl"][$uptoIndex]["quarterly_tax"];

                $result["demand_dtl"] = isset($result["demand_dtl_temp"])?json_decode($result["demand_dtl_temp"], true):array();
                if($result["demand_dtl"]){
                    $result["demand_from_qtr"] = $result["demand_dtl"][0]["qtr"];
                    $result["demand_from_fy"] = $result["demand_dtl"][0]["fyear"];
                    $uptoIndex = count($result["demand_dtl"])-1;
                    $result["demand_upto_qtr"] = $result["demand_dtl"][$uptoIndex]["qtr"];
                    $result["demand_upto_fy"] = $result["demand_dtl"][$uptoIndex]["fyear"];
                    $result["prop_dtl_id"] = $result["id"];
                    $input = [
                        'fy' => $result["demand_upto_fy"],
                        'qtr' => $result["demand_upto_qtr"],
                        'prop_dtl_id' => $result["id"],
                        'user_id' => 2,
                    ];
                    $result["payment_dtl"] = $this->model_prop_demand->getPropDemandAmountDetails($input);
                }
                return $result;
            }
    }


    public function Noticeold($prop_id) {
        try {
            $data = arrFilterSanitizeString($this->request->getVar());
            $result = $this->getDemand($prop_id);
            
            $emp_details_id = $_SESSION['emp_details']['id'];

            if (isset($_POST['gen_notice'])) {
                $sql = "SELECT * FROM tbl_prop_notices 
                        WHERE notice_no='".trim($data["notice_no"])."'";
                $checkdata = $this->db->query($sql)->getResultArray("array");
                if(count($checkdata)==0)
                {
                    if($data["notice_type"] == 'Demand')
                    {
                        if($result["t_balance"] > 0){
                            $input = [
                                "prop_dtl_id"=>$prop_id,
                                "notice_no"=>$data["notice_no"],
                                "notice_date"=>$data["notice_date"],
                                "notice_type"=>$data["notice_type"],
                                "from_qtr"=>$result["demand_from_qtr"],
                                "from_fyear"=>$result["demand_from_fy"],
                                "upto_qtr"=>$result["demand_upto_qtr"],
                                "upto_fyear"=>$result["demand_upto_fy"],
                                "demand_amount"=>$result["t_balance"],
                                "penalty"=>$result["payment_dtl"]["OnePercentPnalty"],
                                "due_date"=>$result["due_date"],
                                "generated_by_emp_details_id"=>$emp_details_id,
                                "print_status"=>1
                            ];
                        }else{
                            $input = []; 
                        }
                        
                    }
                    if($data["notice_type"] == 'Assessment')
                    {
                        $input = [
                            "prop_dtl_id"=>$prop_id,
                            "notice_no"=>$data["notice_no"],
                            "notice_date"=>$data["notice_date"],
                            "notice_type"=>$data["notice_type"],
                            "generated_by_emp_details_id"=>$emp_details_id,
                            "print_status"=>1
                        ];
                        
                    }
                    if($input)
                    {
                        $lastInsertId = $this->model_notice->insertNoticeData($input);
                        $serial_no = "NOTICE/".$lastInsertId."/".$this->model_notice->getCount($prop_id)['serial'];
                        $this->model_notice->updateRecord(['serial_no'=>$serial_no], $lastInsertId);
                    }
                    
                }
            }
            $result['notice_dtl'] = $this->model_notice->getNotice($prop_id);
            return view('property/generate_notice', $result);
        } catch(Exception $e) {
            print_var($e);
        }
    }
    public function Notice_old($prop_id) {
        try {
            $data = arrFilterSanitizeString($this->request->getVar());
            $result = $this->getDemand($prop_id);
            $emp_details_id = $_SESSION['emp_details']['id'];
            if (isset($_POST['gen_notice'])) {
                $propsql="SELECT tbl_prop_dtl.id,view_ward_mstr.ward_no 
                 FROM tbl_prop_dtl
            INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
            where tbl_prop_dtl.id=$prop_id
            ";
                $propdata = $this->db->query($propsql)->getFirstRow("array");
                $sql = "SELECT * FROM tbl_prop_notices 
                        WHERE prop_dtl_id='".trim($prop_id)."' AND fnyear='".getFY()."' AND notice_type='".$data['notice_type']."'";
                $checkdata = $this->db->query($sql)->getResultArray("array");
                //dd(count($checkdata));
                if(count($checkdata)<3)
                {
                    if($data["notice_type"] == 'Demand')
                    {
                        $noticeno=$this->generatenoticeno('11',$propdata['ward_no']);
                        if($result["t_balance"] > 0){
                            $input = [
                                "prop_dtl_id"=>$prop_id,
                                //"notice_no"=>$data["notice_no"],
                                "notice_no"=>$noticeno,
                                "notice_date"=>$data["notice_date"],
                                "notice_type"=>$data["notice_type"],
                                "from_qtr"=>$result["demand_from_qtr"],
                                "from_fyear"=>$result["demand_from_fy"],
                                "upto_qtr"=>$result["demand_upto_qtr"],
                                "upto_fyear"=>$result["demand_upto_fy"],
                                "demand_amount"=>$result["t_balance"],
                                "penalty"=>$result["payment_dtl"]["OnePercentPnalty"],
                                "due_date"=>$result["due_date"],
                                "generated_by_emp_details_id"=>$emp_details_id,
                                "fnyear"=>getFY(),
                                "print_status"=>1
                            ];
                        }else{
                            $input = []; 
                        }
                        
                    }
                    if($data["notice_type"] == 'Assessment')
                    {
                        $noticeno=$this->generatenoticeno('12',$propdata['ward_no']);
                        $input = [
                            "prop_dtl_id"=>$prop_id,
                            //"notice_no"=>$data["notice_no"],
                            "notice_no"=>$noticeno,
                            "notice_date"=>$data["notice_date"],
                            "notice_type"=>$data["notice_type"],
                            "generated_by_emp_details_id"=>$emp_details_id,
                            "fnyear"=>getFY(),
                            "print_status"=>1
                        ];
                        
                    }
                    if($input)
                    {
                        $lastInsertId = $this->model_notice->insertNoticeData($input);
                        $serial_no = "NOTICE/".$lastInsertId."/".$this->model_notice->getCount($prop_id)['serial'];
                        // $notice_no = "NOTICE/".$lastInsertId."/".$this->model_notice->getCount($prop_id)['serial'];
                        $this->model_notice->updateRecord(['serial_no'=>$serial_no], $lastInsertId);
                    }
                }else{
                    flashToast('message','Maximum notice for current financial year has been reached.');

                    $url=base_url('propDtl/Notice').'/'.$prop_id;
                    return redirect()->to($url);
                }
            }
            $result['notice_dtl'] = $this->model_notice->getNotice($prop_id);
            return view('property/generate_notice', $result);
        } catch(Exception $e) {
            print_var($e);
            exit();
        }
    }

    public function Notice($prop_id) {
        try {
            $data = arrFilterSanitizeString($this->request->getVar());
            $fyear = getFY();
            list($from,$upto) = explode("-",$fyear);
            $privFyear = ($from-1)."-".$from;
            $result = $this->getDemand($prop_id,$privFyear);
            $emp_details_id = $_SESSION['emp_details']['id'];
            if (isset($_POST['gen_notice'])) {
                $propsql="SELECT tbl_prop_dtl.id,view_ward_mstr.ward_no 
                        FROM tbl_prop_dtl
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        where tbl_prop_dtl.id=$prop_id
                ";
                $propdata = $this->db->query($propsql)->getFirstRow("array");
                $sql = "SELECT * FROM tbl_prop_notices 
                        WHERE prop_dtl_id='".trim($prop_id)."' AND fnyear='".getFY()."' AND notice_type='".$data['notice_type']."'";
                $checkdata = $this->db->query($sql)->getResultArray("array");
                //dd(count($checkdata));
                if(count($checkdata)<3)
                {   
                    $firstActiveEoSql = " select * from view_emp_details where lock_status=0 and user_type_mstr_id=10 order by id ASC limit 1 "; 
                    $firstEo = $this->dbSystem->query($firstActiveEoSql)->getFirstRow("array");
                    
                    if($data["notice_type"] == 'Demand')
                    {
                        $noticeno=$this->generatenoticeno('11',$propdata['ward_no']);
                        if($result["t_balance"] > 0){
                            $input = [
                                "prop_dtl_id"=>$prop_id,
                                //"notice_no"=>$data["notice_no"],
                                "notice_no"=>$noticeno,
                                "notice_date"=>$data["notice_date"],
                                "notice_type"=>$data["notice_type"],
                                "from_qtr"=>$result["demand_from_qtr"],
                                "from_fyear"=>$result["demand_from_fy"],
                                "upto_qtr"=>$result["demand_upto_qtr"],
                                "upto_fyear"=>$result["demand_upto_fy"],
                                "demand_amount"=>$result["t_balance"],
                                "penalty"=>$result["payment_dtl"]["OnePercentPnalty"],
                                "due_date"=>$result["due_date"],
                                "generated_by_emp_details_id"=>$emp_details_id,
                                "fnyear"=>getFY(),
                                "print_status"=>1,
                                "approved_by"=>$firstEo["id"]??null,
                            ];
                        }else{
                            $input = []; 
                        }
                        
                    }
                    if($data["notice_type"] == 'Assessment')
                    {
                        $noticeno=$this->generatenoticeno('12',$propdata['ward_no']);
                        $input = [
                            "prop_dtl_id"=>$prop_id,
                            //"notice_no"=>$data["notice_no"],
                            "notice_no"=>$noticeno,
                            "notice_date"=>$data["notice_date"],
                            "notice_type"=>$data["notice_type"],
                            "generated_by_emp_details_id"=>$emp_details_id,
                            "fnyear"=>getFY(),
                            "print_status"=>1,
                            "approved_by"=>$firstEo["id"]??null,
                        ];
                        
                    }
                    if($input)
                    {
                        $lastInsertId = $this->model_notice->insertNoticeData($input);
                        $serial_no = "NOTICE/".$lastInsertId."/".$this->model_notice->getCount($prop_id)['serial'];
                        // $notice_no = "NOTICE/".$lastInsertId."/".$this->model_notice->getCount($prop_id)['serial'];
                        $this->model_notice->updateRecord(['serial_no'=>$serial_no], $lastInsertId);
                    }
                    if($this->request->getVar("ajaxNotice")){
                        return $lastInsertId;
                    }
                }else{
                    flashToast('message','Maximum notice for current financial year has been reached.');

                    $url=base_url('propDtl/Notice').'/'.$prop_id;
                    return redirect()->to($url);
                }
            }
            $result['notice_dtl'] = $this->model_notice->getNotice($prop_id);
            return view('property/generate_notice', $result);
        } catch(Exception $e) {
            print_var($e);
            exit();
        }
    }
	

    public function generatenoticeno($ward,$wardno){
        $pad=str_pad(rand(999,999999),6,'0',STR_PAD_LEFT);
        $noticeno=$ward.'/REV/'.$wardno.'/'.$pad;
        $sql = "SELECT * FROM tbl_prop_notices 
                        WHERE notice_no='".trim($noticeno)."'";
        $checkdata = $this->db->query($sql)->getResultArray("array");
        if(count($checkdata)>0)
        {
            return $this->generatenoticeno();
        }
        return $noticeno;
    }

    public function GeneratedNotice_old($notice_id) {
        try {
            
            $ulb_mstr_dtl = getUlbDtl();
            $noticeDtl = $this->model_notice->getNoticeById($notice_id);

            $sql = "SELECT
                tbl_prop_dtl.id,
                view_ward_mstr.ward_no,
                new_ward.ward_no AS new_ward_no,
                tbl_prop_dtl.holding_no,
                tbl_prop_dtl.new_holding_no,
                tbl_prop_dtl.prop_address,
                owner_dtl.owner_name,
                owner_dtl.mobile_no,
                owner_dtl.guardian_name
            FROM tbl_prop_dtl
            INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
            left JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_prop_dtl.new_ward_mstr_id
            INNER JOIN (
                SELECT
                    prop_dtl_id,
                    STRING_AGG(owner_name, ',') AS owner_name,
                    STRING_AGG(mobile_no::TEXT, ',') AS mobile_no,
                    STRING_AGG(guardian_name::TEXT, ',') AS guardian_name
                FROM tbl_prop_owner_detail
                GROUP BY prop_dtl_id
            ) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id           
            WHERE 
                tbl_prop_dtl.id='".$noticeDtl["prop_dtl_id"]."'";
            $result = $this->db->query($sql)->getFirstRow("array");
            
            $data['ulb']=$ulb_mstr_dtl;
            $data['notice']=$noticeDtl;
            $data['property']=$result;
            
            if($noticeDtl["notice_type"] == "Demand"){
                return view('property/demand_notice', $data);
            }
            if($noticeDtl["notice_type"] == "Assessment"){
                return view('property/assessment_notice', $data);
            }
            
        } catch(Exception $e) {
            print_var($e);
        }
    }

    public function GeneratedNotice($notice_id) {
        try {
            
            $ulb_mstr_dtl = getUlbDtl();
            $noticeDtl = $this->model_notice->getNoticeById($notice_id);
            $sql = "SELECT
                tbl_prop_dtl.id,
                view_ward_mstr.ward_no,
                new_ward.ward_no AS new_ward_no,
                tbl_prop_dtl.holding_no,
                tbl_prop_dtl.new_holding_no,
                tbl_prop_dtl.prop_address,
                owner_dtl.owner_name,
                owner_dtl.mobile_no,
                owner_dtl.email,
                owner_dtl.guardian_name
            FROM tbl_prop_dtl
            INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
            left JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_prop_dtl.new_ward_mstr_id
            INNER JOIN (
                SELECT
                    prop_dtl_id,
                    STRING_AGG(owner_name, ',') AS owner_name,
                    STRING_AGG(mobile_no::TEXT, ',') AS mobile_no,
                    STRING_AGG(email::TEXT, ',') AS email,
                    STRING_AGG(guardian_name::TEXT, ',') AS guardian_name
                FROM tbl_prop_owner_detail
                GROUP BY prop_dtl_id
            ) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id           
            WHERE 
                tbl_prop_dtl.id='".$noticeDtl["prop_dtl_id"]."'";
            $result = $this->db->query($sql)->getFirstRow("array");

            $data['ulb']=$ulb_mstr_dtl;
            $data['notice']=$noticeDtl;
            $data['property']=$result;

            $sign = "dmcsign.png";
            $degignation = "अपर प्रशासक";
            if($data['notice']["created_on"]<'2024-09-28'){
                $sign = "dmcsign.png";
            }
            if($data['notice']["created_on"]<'2024-02-15'){
                $sign = "rajnishkumar_sign.png";
            }
            if($noticeDtl["approved_by"]=='1661'){
                $degignation = "उप प्रशासक";
            }
            if($noticeDtl["approved_by"]=='1719'){
                $degignation = "अपर प्रशासक";
            }
            $data["degignation"]=$degignation;
            $data["signature_path"]=base_url('/public/assets/img/'.$sign);
            if($noticeDtl["approved_by"]){
                $empDtl = $this->dbSystem->query("select * from tbl_emp_details where id=".$noticeDtl["approved_by"])->getFirstRow("array");
				$data["signature_path"]= $empDtl && $empDtl["signature_path"]? base_url("/getImageLink.php?path=/emp_signature/".$empDtl["signature_path"]):$data["signature_path"] ;
            }
            // dd($data['ulb']);
            if($noticeDtl["notice_type"] == "Demand"){
                $data['notice']["noticePenalty"] = round((float)(($noticeDtl["demand_amount"] + $noticeDtl["penalty"]) * 0.01), 2) ;
                return view('property/demand_notice', $data);
            }
            if($noticeDtl["notice_type"] == "Assessment"){
                return view('property/assessment_notice', $data);
            }
        } catch(Exception $e) {
            print_var($e);
        }
    }
	
	
	public function NoticeList()
    {
        $data=(array)null;
        $data["result"]=$this->model_notice->getNoticeList();
        return view('property/NoticeList', $data);
    }


    public function ViewNotice($notice_id_MD5)
    {
        $data=(array)null;
        $Session = Session();
        
        $ulb_mstr_dtl = getUlbDtl();
        $emp_details_id = $_SESSION['emp_details']['id'];
        $noticeDtl = $this->model_notice->getNoticeById($notice_id_MD5);

        $sql = "SELECT *  FROM view_prop_dtl_owner_ward_prop_type_ownership_type WHERE prop_dtl_id='".$noticeDtl["prop_dtl_id"]."'";
        $result = $this->db->query($sql)->getFirstRow("array");
        
        

        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            if(isset($inputs["btn_deactive"]))
            {
                $data=[
                    "remarks"=> $inputs["level1_remarks"],
                    "status"=> 0,//Deactivated
                    "deactivated_by"=> $emp_details_id,
                    "deactivate_date"=> date('Y-m-d H:i:s')
                ];
                $this->model_notice->updateRecord($data, $noticeDtl["id"]);
                flashToast("message", "Notice deactivated successfully");
            }
            
            $this->response->redirect(base_url("propDtl/NoticeList/"));
        }

        $data['ulb']=$ulb_mstr_dtl;
        $data['notice']=$noticeDtl;
        $data['property']=$result;

        $data["notice_id_MD5"]=md5($noticeDtl["id"]);
        return view('property/ViewNotice', $data);
    }
    public function printNotice()
    {
        if($this->request->getMethod()!="post")
        {
            echo "No data found";
            exit();
        }
        try{
            $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
            $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
            $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
            $search_notice_type = sanitizeString($this->request->getVar('search_notice_type'));
            $whereQuery = "";
            if ($search_ward_mstr_id != '') {
                $whereQuery .= " AND  (tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."' OR tbl_prop_dtl.new_ward_mstr_id='".$search_ward_mstr_id."')";
            }
            if($search_notice_type !="")
            {
                $whereQuery .= " AND tbl_prop_notices.notice_type='".$search_notice_type."'";
            }
            if($search_notice_type=="Demand"){
                return $this->printBulkNotice();
            }
            $selectQuery = "SELECT 
                 tbl_prop_notices.id AS s_no,
                --ROW_NUMBER () OVER (ORDER BY tbl_prop_notices.id desc) AS s_no,
                CONCAT('NOTICE/', tbl_prop_notices.notice_no) AS notice_no,
                tbl_prop_notices.notice_date,
                tbl_prop_notices.notice_type,
                tbl_prop_notices.demand_amount,
                tbl_prop_notices.penalty,
                tbl_prop_notices.remarks,
                CASE WHEN tbl_prop_notices.from_fyear is not null THEN CONCAT(tbl_prop_notices.from_fyear, '(', tbl_prop_notices.from_qtr, ')', ' / ', tbl_prop_notices.upto_fyear, '(', tbl_prop_notices.upto_qtr, ')') ELSE '' END AS from_upto_fy_qtr,
                CASE WHEN char_length(tbl_prop_dtl.new_holding_no) > 0 THEN tbl_prop_dtl.new_holding_no ELSE tbl_prop_dtl.holding_no END as holding_no,
                CASE WHEN new_ward.ward_no is not null THEN new_ward.ward_no ELSE old_ward.ward_no END as ward_no,
                tbl_prop_owner_detail.wname as owner_name,
                tbl_prop_owner_detail.wmobile as owner_mobile_no,
                tbl_prop_notices.created_on::date as generated_date,
                gen.emp_name as generated_by,
                tbl_prop_notices.deactivate_date::date as deactivate_date,
                deactive.emp_name as deactivated_by
                ";

            $sql = " FROM tbl_prop_notices
                    JOIN tbl_prop_dtl ON tbl_prop_notices.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN view_ward_mstr old_ward ON tbl_prop_dtl.ward_mstr_id=old_ward.id
                    LEFT JOIN view_ward_mstr new_ward ON tbl_prop_dtl.new_ward_mstr_id=new_ward.id
                    JOIN (
                        SELECT 
                            prop_dtl_id,
                            array_to_string(array_agg(owner_name), '','') as wname ,
                            array_to_string(array_agg(mobile_no), '','') as wmobile 
                        FROM tbl_prop_owner_detail 
                        GROUP BY prop_dtl_id
                        ) tbl_prop_owner_detail on tbl_prop_owner_detail.prop_dtl_id = tbl_prop_notices.prop_dtl_id
                    LEFT JOIN view_emp_details_login gen ON tbl_prop_notices.generated_by_emp_details_id=gen.id
                    LEFT JOIN view_emp_details_login deactive ON tbl_prop_notices.deactivated_by=deactive.id
                    WHERE (notice_date BETWEEN '".$search_from_date."' AND  '".$search_upto_date."') ". $whereQuery;

            $fetchSql = $selectQuery.$sql;
            $result = $this->db->query($fetchSql);
            $records = $result->getResultArray(['s_no']);

          //  dd($fetchSql,$records);

            $ulb_mstr_dtl = getUlbDtl();
            $noticeAarray=$records;//['489','488','487','486','485'];
            $printArray=[];
            //dd($noticeAarray);
            foreach ($noticeAarray as $arr) {
                $noticeDtl = $this->model_notice->getNoticeById(md5($arr['s_no']));
                if(empty($noticeDtl['prop_dtl_id'])){
                    continue;
                }
               // dd($noticeDtl,$arr['s_no']);
                $sql = "SELECT
                    tbl_prop_dtl.id,
                    view_ward_mstr.ward_no,
                    new_ward.ward_no AS new_ward_no,
                tbl_prop_dtl.holding_no,
                tbl_prop_dtl.new_holding_no,
                tbl_prop_dtl.prop_address,
                owner_dtl.owner_name,
                owner_dtl.mobile_no,
                owner_dtl.guardian_name
            FROM tbl_prop_dtl
            INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
            left JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_prop_dtl.new_ward_mstr_id
            INNER JOIN (
                SELECT
                    prop_dtl_id,
                    STRING_AGG(owner_name, ',') AS owner_name,
                    STRING_AGG(mobile_no::TEXT, ',') AS mobile_no,
                    STRING_AGG(guardian_name::TEXT, ',') AS guardian_name
                FROM tbl_prop_owner_detail
                GROUP BY prop_dtl_id
            ) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id           
            WHERE 
                tbl_prop_dtl.id = '" . $noticeDtl["prop_dtl_id"] . "'";
                $result = $this->db->query($sql)->getFirstRow("array");

                $data['ulb'] = $ulb_mstr_dtl;
                $data['notice'] = $noticeDtl;
                $data['property'] = $result;
                $printArray['printArray'][] = $data;
              }
            return view('property/demand_notice_print', $printArray);
        } catch(Exception $e) {
            print_var($e);
        }
    }
    /*
    public function generateBulkNotice($ajax = false){
        $data = $this->request->getVar();
        $fyear = getFY();
        // $privFyear = getFY(explode("-",$fyear)[0]."-03-31");

        if ($this->request->getMethod() == "post" || $ajax) {            
            if($this->request->getVar("selecteditems")){
                foreach($this->request->getVar("selecteditems") as $prop_id){ 
                    if($this->request->getVar("nextNotice")){
                        $this->generatedNoticeUpdateSerial($prop_id);
                    }else{
                        $this->Notice($prop_id);
                    }                  
                }
                return true;
            }
            $where = " where tbl_prop_dtl.status = 1 ";
            if ($this->request->getVar("ward_id")) {
                $where .= " AND tbl_prop_dtl.ward_mstr_id = " . $this->request->getVar("ward_id");
            }
            if ($this->request->getVar("property_type_id")) {
                $where .= " AND tbl_prop_dtl.prop_type_mstr_id = " . $this->request->getVar("property_type_id");
            }

            
            $start = sanitizeString($this->request->getVar('start'));
            $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

            $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
            $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
            if ($columnName == "ward_no")
                $columnName = 'tbl_prop_dtl.ward_mstr_id';
            else
                $columnName = 'tbl_prop_dtl.id';

            $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']);
            $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
            $limit = " LIMIT " . ($rowperpage == -1 ? "ALL" : $rowperpage) . " OFFSET " . $start;


            $searchValue = sanitizeString($this->request->getVar('search')['value']);
            $whereQueryWithSearch = "";
            if ($searchValue != '') {
                $whereQueryWithSearch = " AND (
                    tbl_prop_dtl.new_holding_no ILIKE '%" . $searchValue . "%'
                    OR tbl_prop_dtl.holding_no ILIKE '%" . $searchValue . "%'
                    OR tbl_prop_dtl.prop_address ILIKE '%" . $searchValue . "%'
                    OR owneres.owner_name ILIKE '%" . $searchValue . "%'
                    OR owneres.mobile_no ILIKE '%" . $searchValue . "%'
                    
                    )
                ";
            }

            $with ="with demands as(
                        SELECT 
                            prop_dtl_id,
                            SUM(balance) AS balance,
                            --SUM(additional_amount) AS additional_amount,
                            --SUM(adjust_amt) AS adjust_amt,
                            --max(due_date) AS due_date,
                            MAX(fyear) upto_fyear,
                            (
                                SELECT MAX(qtr)
                                FROM tbl_prop_demand
                                WHERE fyear = (
                                    SELECT MAX(fyear)
                                    FROM tbl_prop_demand
                                ) and tbl_prop_demand.prop_dtl_id = prop_dtl_id
                                GROUP BY fyear
                            ) as upto_qtr,
                            MIN(fyear) from_fyear,
                            (
                                SELECT MAX(qtr)
                                FROM tbl_prop_demand
                                WHERE fyear = (
                                    SELECT MIN(fyear)
                                    FROM tbl_prop_demand
                                ) and tbl_prop_demand.prop_dtl_id = prop_dtl_id
                                GROUP BY fyear
                            ) as from_qtr
                            
                        FROM tbl_prop_demand
                        join(
                            select distinct(prop_dtl_id) as temp_prop_id
                            from tbl_prop_demand
                            WHERE status=1 AND paid_status=0 and fyear < '$fyear' and balance>0
                        )arrear on arrear.temp_prop_id = tbl_prop_demand.prop_dtl_id
                        WHERE status=1 AND paid_status=0 
                        GROUP BY prop_dtl_id
                    ) ,
                    owneres as (
                        select  tbl_prop_owner_detail.prop_dtl_id,
                            string_agg(tbl_prop_owner_detail.owner_name,',')owner_name,
                            string_agg(tbl_prop_owner_detail.mobile_no::text,',')mobile_no
                        from tbl_prop_owner_detail
                        join demands on demands.prop_dtl_id = tbl_prop_owner_detail.prop_dtl_id
                        where status = 1 
                        GROUP BY tbl_prop_owner_detail.prop_dtl_id
                    ),
                    notices as (
                        select prop_dtl_id
                        from tbl_prop_notices
                        where status!=0 and fnyear='$fyear' and notice_type='Demand'
                        group by prop_dtl_id
                    )            
            ";

            $select = "select ROW_NUMBER() OVER ( $orderBY) as s_no,view_ward_mstr.ward_no,tbl_prop_type_mstr.property_type,
                            tbl_prop_dtl.id,case when trim(new_holding_no)='' then holding_no else new_holding_no end as holding_no,
                            tbl_prop_dtl.prop_address,
                            demands.*,
                            owneres.owner_name, owneres.mobile_no
            ";
            $from = "from tbl_prop_dtl
                    join tbl_prop_type_mstr on tbl_prop_type_mstr.id = tbl_prop_dtl.prop_type_mstr_id
                    join view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                    join demands on demands.prop_dtl_id = tbl_prop_dtl.id
                    left join owneres on owneres.prop_dtl_id = tbl_prop_dtl.id
                    left join notices on notices.prop_dtl_id = tbl_prop_dtl.id                        
                    $where  and demands.balance>0                  
            ";
            if($this->request->getVar("report_type")!="defaulter_list"){
                $from.=" AND notices.prop_dtl_id is null ";
            }

            if($this->request->getVar("report_type")=="notice_be_generated"){
                if($this->request->getVar("serialNo") && $this->request->getVar("serialNo")!=""){
                    $where.=" AND notice_serial = ".$this->request->getVar("serialNo");
                }
                if($this->request->getVar("closeStatus")){
                    #open
                    if($this->request->getVar("closeStatus")=="open"){
                        $where.=" AND tbl_prop_notices.status = 1";
                    }
                    elseif($this->request->getVar("closeStatus")=="close"){
                        $where.=" AND tbl_prop_notices.status != 1";
                    }
                }
                if($this->request->getVar("servedStatus")){
                    #open
                    if($this->request->getVar("servedStatus")=="served"){
                        $where.=" AND tbl_prop_notices.notice_served_on is not null ";
                    }
                    elseif($this->request->getVar("servedStatus")=="not_served"){
                        $where.=" AND tbl_prop_notices.notice_served_on is null ";
                    }
                }
                $with="with notices as (
                            select prop_dtl_id,max(id) as notice_id
                            from tbl_prop_notices
                            where status!=0 and fnyear='$fyear' and notice_type='Demand'
                            group by prop_dtl_id
                        ) ,
                        owneres as (
                            select  tbl_prop_owner_detail.prop_dtl_id,
                                string_agg(tbl_prop_owner_detail.owner_name,',')owner_name,
                                string_agg(tbl_prop_owner_detail.mobile_no::text,',')mobile_no
                            from tbl_prop_owner_detail
                            join notices on notices.prop_dtl_id = tbl_prop_owner_detail.prop_dtl_id
                            where status = 1 
                            GROUP BY tbl_prop_owner_detail.prop_dtl_id
                        )          
                ";
                $select="select ROW_NUMBER() OVER ( $orderBY) as s_no,view_ward_mstr.ward_no,tbl_prop_type_mstr.property_type,
                            tbl_prop_notices.*,
                            tbl_prop_dtl.id,case when trim(new_holding_no)='' then holding_no else new_holding_no end as holding_no,
                            tbl_prop_dtl.prop_address,                            
                            owneres.owner_name, owneres.mobile_no,
                            notices.notice_id
                        ";
                $from = "from tbl_prop_dtl
                        join tbl_prop_type_mstr on tbl_prop_type_mstr.id = tbl_prop_dtl.prop_type_mstr_id
                        join view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                        join notices on notices.prop_dtl_id = tbl_prop_dtl.id
                        join tbl_prop_notices on tbl_prop_notices.id = notices.notice_id
                        left join owneres on owneres.prop_dtl_id = tbl_prop_dtl.id                                                 
                        $where   AND notices.prop_dtl_id is not null                
                ";
            }

            if($this->request->getVar("report_type")=="notice_be_closed"){
                $with="with notices as (
                            select prop_dtl_id,max(id) as notice_id
                            from tbl_prop_notices
                            where status=5 and fnyear='$fyear' and notice_type='Demand'
                            group by prop_dtl_id
                        ) ,
                        owneres as (
                            select  tbl_prop_owner_detail.prop_dtl_id,
                                string_agg(tbl_prop_owner_detail.owner_name,',')owner_name,
                                string_agg(tbl_prop_owner_detail.mobile_no::text,',')mobile_no
                            from tbl_prop_owner_detail
                            join notices on notices.prop_dtl_id = tbl_prop_owner_detail.prop_dtl_id
                            where status = 1 
                            GROUP BY tbl_prop_owner_detail.prop_dtl_id
                        )          
                ";
                $select="select ROW_NUMBER() OVER ( $orderBY) as s_no,view_ward_mstr.ward_no,tbl_prop_type_mstr.property_type,
                            tbl_prop_notices.*,
                            tbl_prop_dtl.id,case when trim(new_holding_no)='' then holding_no else new_holding_no end as holding_no,
                            tbl_prop_dtl.prop_address,                            
                            owneres.owner_name, owneres.mobile_no,
                            notices.notice_id,
                            tbl_transaction.tran_no,tran_date,tran_mode,payable_amt
                        ";
                $from = "from tbl_prop_dtl
                        join tbl_prop_type_mstr on tbl_prop_type_mstr.id = tbl_prop_dtl.prop_type_mstr_id
                        join view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                        join notices on notices.prop_dtl_id = tbl_prop_dtl.id
                        join tbl_prop_notices on tbl_prop_notices.id = notices.notice_id
                        join tbl_transaction on tbl_transaction.id = tbl_prop_notices.clear_by_id
                        left join owneres on owneres.prop_dtl_id = tbl_prop_dtl.id                            
                        $where  AND notices.prop_dtl_id is not null                
                ";
            }
            if($this->request->getVar("report_type")=="notice_summary"){
                $fromDate = $this->request->getVar("fromDate");
                $uptoDate = $this->request->getVar("uptoDate");
                $with = "";
                $orderBY ="";
                $select = "SELECT count(tbl_prop_notices.prop_dtl_id) as total_property, sum(tbl_prop_notices.demand_amount + tbl_prop_notices.penalty) as total_demand,
                                count(case when tbl_prop_notices.clear_by_id is not null then tbl_prop_notices.prop_dtl_id end) as collection_from_prop,
                                sum( case when tbl_prop_notices.clear_by_id is not null then (tbl_prop_notices.demand_amount + tbl_prop_notices.penalty) end) as total_collection_demand                            
                ";
                $from = " FROM tbl_prop_notices 
                          JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_prop_notices.prop_dtl_id
                          $where 
                            AND tbl_prop_notices.status in(1,5) AND tbl_prop_notices.notice_type = 'Demand'
                          ".($fromDate && $uptoDate ? " AND tbl_prop_notices.notice_date between '$fromDate' AND '$uptoDate' " : "")."
                             
                ";


                
            }
            $totalRecords = $this->model_datatable->getTotalRecords($from, false,$with);

            if ($totalRecords > 0) {

                ## Total number of records with filtering
                $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($from . $whereQueryWithSearch, false,$with);

                ## Fetch records                
                $fetchSql = $with.$select . $from . $whereQueryWithSearch . $orderBY;
                if (!$ajax) {
                    $fetchSql .= $limit;
                }
                $result = $this->model_datatable->getRecords($fetchSql, false);

                $records = [];
                if ($result) {
                    if($this->request->getVar("report_type")=="notice_be_closed"){
                        foreach ($result as $key => $tran_dtl) {
                            $records[] = [
                                's_no' => $tran_dtl['s_no'],
                                'ward_no' => $tran_dtl['ward_no'],
                                "notice_no"=>$tran_dtl['notice_no'],
                                'holding_no' => $tran_dtl['holding_no'],
                                "property_type" => $tran_dtl['property_type'],
                                'prop_address' => $tran_dtl["prop_address"],
                                'owner_name' => $tran_dtl['owner_name'],
                                'mobile_no' => $tran_dtl['mobile_no'],
                                'from' => ($tran_dtl['from_qtr']."/".$tran_dtl['from_fyear']),
                                'upto' => ($tran_dtl['upto_qtr']."/".$tran_dtl['upto_fyear']),
                                'balance' => ($tran_dtl['demand_amount'] + $tran_dtl["penalty"]),
                                "notice_date"=> ($tran_dtl['notice_date']),
                                'paid_amount' => ($tran_dtl['payable_amt']),
                                'tran_date' => ($tran_dtl['tran_date']),
                                'tran_no' => ($tran_dtl['tran_no']),
                                'payment_mode' => ($tran_dtl['tran_mode']),
                                "link" => $ajax ? "" : ("<a class ='btn btn-info btn-sm' target='blanck' href = '" . base_url("jsk/payment_jsk_receipt/" . md5($tran_dtl["clear_by_id"])) . "'>view</a>"),
    
                            ];
                        }
                    }
                    elseif($this->request->getVar("report_type")=="notice_be_generated"){
                        foreach ($result as $key => $tran_dtl) {
                            $s = 1;
                            $srial="";
                            while($s <= $tran_dtl["notice_serial"]) {
                                $srial.="<span style ='pading : 1 px;'>  <img src='".base_url('public/assets/img/correct.png')."' style='height: 25px' /></span>";        
                                $s++;                        
                            }                           
                            $records[] = [
                                's_no' => $tran_dtl['s_no'].(($ajax || $tran_dtl['status']==5) ? "" : ("<input type='checkbox' name='noticeCheck[]' value ='".$tran_dtl["notice_id"]."' />")),
                                'ward_no' => $tran_dtl['ward_no'],
                                "notice_no"=>$tran_dtl['notice_no'],
                                'holding_no' => $tran_dtl['holding_no'],
                                "property_type" => $tran_dtl['property_type'],
                                'prop_address' => $tran_dtl["prop_address"],
                                'owner_name' => $tran_dtl['owner_name'],
                                'mobile_no' => $tran_dtl['mobile_no'],
                                'from' => ($tran_dtl['from_qtr']."/".$tran_dtl['from_fyear']),
                                'upto' => ($tran_dtl['upto_qtr']."/".$tran_dtl['upto_fyear']),
                                'balance' => ($tran_dtl['demand_amount'] + $tran_dtl["penalty"]),
                                "notice_date"=> ($tran_dtl['notice_date']),
                                "notice_serial"=> $ajax ? $tran_dtl["notice_serial"] : ($srial),
                                "is_close"=>  $tran_dtl["status"]==1 ?"OPEN" : "CLOSE",
                                "is_served"=>  $tran_dtl["notice_served_on"] ?"YES" : "NO",
                                "link" => $ajax ? "" : ("<a class ='btn btn-info btn-sm' target='blanck' href = '" . base_url("propDtl/GeneratedNotice/" . md5($tran_dtl["notice_id"])) . "'>view</a>"),
    
                            ];
                        }
                    }
                    elseif($this->request->getVar("report_type")=="notice_summary"){
                        foreach ($result as $key => $tran_dtl) {
                            $records[] = [
                                's_no' => $tran_dtl['s_no'],
                                'total_property' => $tran_dtl['total_property'],
                                'total_demand' => $tran_dtl['total_demand'],
                                "collection_from_prop" => $tran_dtl['collection_from_prop'],
                                'total_collection_demand' => $tran_dtl["total_collection_demand"],
    
                            ];
                        }
                    }
                    else{
                        foreach ($result as $key => $tran_dtl) {
                            $records[] = [
                                's_no' => $tran_dtl['s_no'].(($ajax || $this->request->getVar("report_type")=="defaulter_list") ? "" : ("<input type='checkbox' name='check[]' value ='".$tran_dtl["id"]."' />")),
                                'ward_no' => $tran_dtl['ward_no'],
                                'holding_no' => $tran_dtl['holding_no'],
                                "property_type" => $tran_dtl['property_type'],
                                'prop_address' => $tran_dtl["prop_address"],
                                'owner_name' => $tran_dtl['owner_name'],
                                'mobile_no' => $tran_dtl['mobile_no'],
                                'from' => ($tran_dtl['from_qtr']."/".$tran_dtl['from_fyear']),
                                'upto' => ($tran_dtl['upto_qtr']."/".$tran_dtl['upto_fyear']),
                                'balance' => ($tran_dtl['balance']),
                                "link" => $ajax ? "" : ("<a class ='btn btn-info btn-sm' target='blanck' href = '" . base_url("propDtl/full/" . $tran_dtl["id"]) . "'>view</a>"),
    
                            ];
                        }
                    }
                }
            }
            if ($ajax) {
                phpOfficeLoad();
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $activeSheet = $spreadsheet->getActiveSheet();

                if($this->request->getVar("report_type")=="notice_be_closed"){

                    $activeSheet->setCellValue('A1', 'Sl No.');
                    $activeSheet->setCellValue('B1', 'Ward No');
                    $activeSheet->setCellValue('C1', 'Notice No');
                    $activeSheet->setCellValue('D1', 'Hoalding No');
                    $activeSheet->setCellValue('E1', 'Property Type');
                    $activeSheet->setCellValue('F1', 'Address');
                    $activeSheet->setCellValue('G1', 'Owner Name');
                    $activeSheet->setCellValue('H1', 'Mobile No.');
                    $activeSheet->setCellValue('I1', 'From');
                    $activeSheet->setCellValue('J1', 'Upto');
                    $activeSheet->setCellValue('K1', 'Demand Amount');
                    $activeSheet->setCellValue('L1', 'Notice Date');
                    $activeSheet->setCellValue('M1', 'Paid Amount');
                    $activeSheet->setCellValue('N1', 'Tran Date');
                    $activeSheet->setCellValue('O1', 'Tran No.');
                    $activeSheet->setCellValue('P1', 'Payment Mode');

                    $filename = "Notice Close Genrated" . date('Ymd-His') . ".xlsx";
                }
                elseif($this->request->getVar("report_type")=="notice_be_generated"){                    

                    $activeSheet->setCellValue('A1', 'Sl No.');
                    $activeSheet->setCellValue('B1', 'Ward No');                    
                    $activeSheet->setCellValue('C1', 'Notice No');
                    $activeSheet->setCellValue('D1', 'Hoalding No');
                    $activeSheet->setCellValue('E1', 'Property Type');
                    $activeSheet->setCellValue('F1', 'Address');
                    $activeSheet->setCellValue('G1', 'Owner Name');
                    $activeSheet->setCellValue('H1', 'Mobile No.');
                    $activeSheet->setCellValue('I1', 'From');
                    $activeSheet->setCellValue('J1', 'Upto');
                    $activeSheet->setCellValue('K1', 'Demand Amount');
                    $activeSheet->setCellValue('L1', 'Notice Date');
                    $activeSheet->setCellValue('M1', 'Notice Serial');
                    $activeSheet->setCellValue('N1', 'Notice Status');
                    $activeSheet->setCellValue('O1', 'Is Notice Served');

                    $filename = "Notice Genrated" . date('Ymd-His') . ".xlsx";
                }
                else{
                    $activeSheet->setCellValue('A1', 'Sl No.');
                    $activeSheet->setCellValue('B1', 'Ward No');
                    $activeSheet->setCellValue('C1', 'Hoalding No');
                    $activeSheet->setCellValue('D1', 'Property Type');
                    $activeSheet->setCellValue('E1', 'Address');
                    $activeSheet->setCellValue('F1', 'Owner Name');
                    $activeSheet->setCellValue('G1', 'Mobile No.');
                    $activeSheet->setCellValue('H1', 'From');
                    $activeSheet->setCellValue('I1', 'Upto');
                    $activeSheet->setCellValue('J1', 'Demand Amount');

                    $filename = "Notice To Be Genrated" . date('Ymd-His') . ".xlsx";
                    if($this->request->getVar("report_type")=="defaulter_list"){
                        $filename = "Defaulter List" . date('Ymd-His') . ".xlsx";
                    }
                }


                $activeSheet->fromArray($records, NULL, 'A3');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');

            } else {
                $response = array(
                    "draw" => 0,
                    "recordsTotal" => $totalRecords,
                    "recordsFiltered" => $totalRecordwithFilter??0,
                    "data" => $records??[],
                    // "summary"=>$totalSummary,

                );
                return json_encode($response);
            }
        }
        $ulb_mstr_id = 1;
        $data['wardList'] = $this->model_ward_mstr->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
        $data["propertyTypeList"] = $this->db->query("SELECT id,property_type FROM tbl_prop_type_mstr WHERE status =1 ORDER BY property_type ASC ")->getResultArray();        
        return view("property/bulk_generate_notice",$data);
    }

    public function generatedNoticeUpdateSerial($noticeId){        
        $data = $this->request->getVar();     
        $emp_details_id = $_SESSION['emp_details']['id'];   
        $noticeDtl = $this->model_notice->getNoticeById($noticeId);
        if($this->request->getMethod()=="post"){
            $lastId = null;
            $sql = "UPDATE tbl_prop_notices SET notice_serial = notice_serial+1 WHERE id = ".$noticeDtl["id"];
            $this->db->transBegin();
            if($this->db->query($sql)){
                $inputs=[
                    "notice_id" => $noticeDtl["id"],
                    "notice_date" => $data["notice_date"]??date("Y-m-d"),
                    "serial_no" => $noticeDtl["notice_serial"]+1,
                    "generated_by_emp_details_id" => $emp_details_id,
                    "print_status" => 1,
                ];
                $lastId = $this->ModelPropNoticeSerial->insertNoticeSerialData($inputs);
            }
            if ($this->db->transStatus() === FALSE) {
                $this->db->transRollback();
            } else {
                $this->db->transCommit();
            }
            if($_POST["ajaxCall"]??false){
                return $lastId;
            }
            flashToast('message',$noticeDtl["notice_serial"]+1 .' Notice Generated');
            return $this->response->redirect(base_url('propDtl/generatedNoticeUpdateSerial/'.$noticeId));
        }
        dd("view");
    }

    public function printBulkNotice(){
        $data = $this->request->getVar();
        if($this->request->getMethod()=="post")
        {
            $fromDate = $this->request->getVar("from_date");
            $uptoDate = $this->request->getVar("upto_date");
            $noticSql = "SELECT * FROM tbl_prop_notices WHERE status = 1 AND notice_type = 'Demand' AND notice_date BETWEEN '$fromDate' AND '$uptoDate' ";
            $noticList = $this->db->query($noticSql)->getResultArray();
            foreach($noticList as $key=>$val){
                $ulb_mstr_dtl = getUlbDtl();
                $sql = "SELECT
                    tbl_prop_dtl.id,
                    view_ward_mstr.ward_no,
                    new_ward.ward_no AS new_ward_no,
                    tbl_prop_dtl.holding_no,
                    tbl_prop_dtl.new_holding_no,
                    tbl_prop_dtl.prop_address,
                    owner_dtl.owner_name,
                    owner_dtl.mobile_no,
                    owner_dtl.guardian_name
                FROM tbl_prop_dtl
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                left JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_prop_dtl.new_ward_mstr_id
                INNER JOIN (
                    SELECT
                        prop_dtl_id,
                        STRING_AGG(owner_name, ',') AS owner_name,
                        STRING_AGG(mobile_no::TEXT, ',') AS mobile_no,
                        STRING_AGG(guardian_name::TEXT, ',') AS guardian_name
                    FROM tbl_prop_owner_detail
                    GROUP BY prop_dtl_id
                ) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id           
                WHERE 
                    tbl_prop_dtl.id='".$val["prop_dtl_id"]."'";
                $result = $this->db->query($sql)->getFirstRow("array");
    
                $data["noticeList"][$key]=[
                    "ulb"=>$ulb_mstr_dtl,
                    "notice"=>$val,
                    "property"=>$result
                ];
            }
        }
        $ulb_mstr_id = 1;
        $data['wardList'] = $this->model_ward_mstr->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
        $data["propertyTypeList"] = $this->db->query("SELECT id,property_type FROM tbl_prop_type_mstr WHERE status =1 ORDER BY property_type ASC ")->getResultArray();
        return view("property/printBulkNotice",$data);
    }

    public function noticeServeList(){
        $data = arrFilterSanitizeString($this->request->getVar());
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereDateRange = "";
        $whereWard = " AND tbl_prop_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";

        $whereSearchPrm = "";
        if (isset($data["from_date"]) && isset($data["upto_date"]) && isset($data["ward_mstr_id"]) && isset($data["search_param"])) {
            if ($data["from_date"]!="" && $data["upto_date"]!="") {
                $whereDateRange = " AND tbl_prop_notices.notice_date BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
            }
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_prop_dtl.ward_mstr_id IN (".$data["ward_mstr_id"].")";
            }
            if ($data["search_param"]!="") {
                $whereSearchPrm = " AND (tbl_prop_dtl.holding_no ILIKE '".$data["search_param"]."'
                                        OR tbl_prop_notices.notice_no ILIKE '%".$data["search_param"]."%'
                                        OR owner_dtl.owner_name ILIKE '%".$data["search_param"]."%'
                                        OR owner_dtl.mobile_no ILIKE '%".$data["search_param"]."%')";
            }
        }

        $sql = "select tbl_prop_notices.* , tbl_prop_dtl.holding_no, tbl_prop_dtl.ward_mstr_id,view_ward_mstr.ward_no,
                    owner_dtl.owner_name, owner_dtl.mobile_no,tbl_prop_dtl.prop_address
                from tbl_prop_notices 
                join tbl_prop_dtl on tbl_prop_dtl.id = tbl_prop_notices.prop_dtl_id 
                    and tbl_prop_notices.notice_type = 'Demand'
                join view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                LEFT JOIN ( SELECT tbl_prop_owner_detail.prop_dtl_id,
                        string_agg(tbl_prop_owner_detail.owner_name::text, ', '::text) AS owner_name,
                        string_agg(tbl_prop_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                    FROM tbl_prop_owner_detail
                    GROUP BY tbl_prop_owner_detail.prop_dtl_id
                ) owner_dtl ON owner_dtl.prop_dtl_id = tbl_prop_notices.prop_dtl_id 
                where tbl_prop_notices.notice_served_on is null and tbl_prop_notices.status = 1
                $whereWard
                $whereSearchPrm
                $whereDateRange
        ";

        $result = $this->model_datatable->getDatatable($sql);
		$data['posts'] = $result['result'];
		$data['leveldetails'] = $data['posts'];
		$data['pager'] = $result['count'];
        return view('mobile/Property/property/noticeServeList', $data);
    }

    public function noticeDtl($noticeId){
        $data = $this->request->getVar();
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        
        $ulb_mstr_dtl = getUlbDtl();
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($ulb_mstr_dtl["ulb_mstr_id"]);
        $ulb_city_nm=$data['ulb_dtl']['city'];
        $noticeDtl = $this->model_notice->getNoticeById($noticeId);
        if(!$noticeDtl || $noticeDtl["notice_type"]!="Demand"){
            return redirect()->back()->with('error',"");;
        }
        if($this->request->getMethod()=="post"){
            $recieving=$this->request->getFile('notice_recieving');
            $recieving_path = null;
            
            if(isset($recieving)){
                if($recieving->IsValid() && !$recieving->hasMoved()){
                    $newFileName = md5($noticeDtl["id"]);
                    $file_ext = $recieving->getExtension();
                    
                    $path = $ulb_city_nm."/"."prop/notice_recieving";
                    
                    if($recieving->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){                            
                        $recieving_path = $path."/".$newFileName.'.'.$file_ext;
                    }
                }
            }
            $updateSql = "update tbl_prop_notices set notice_served_on = current_date , notice_served_by = $emp_details_id , notice_receiving_remarks =  '".$data["remarks"]."'".($recieving_path ?",notice_receiving = '$recieving_path' " :"")." where id =  ".$noticeDtl["id"];
            $this->db->query($updateSql);
            return $this->response->redirect(base_url('propDtl/noticeServeList'));
        }
        $sql = "SELECT
            tbl_prop_dtl.id,
            view_ward_mstr.ward_no,
            new_ward.ward_no AS new_ward_no,
            tbl_prop_dtl.holding_no,
            tbl_prop_dtl.new_holding_no,
            tbl_prop_dtl.prop_address,
            owner_dtl.owner_name,
            owner_dtl.mobile_no,
            owner_dtl.email,
            owner_dtl.guardian_name
        FROM tbl_prop_dtl
        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
        left JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_prop_dtl.new_ward_mstr_id
        INNER JOIN (
            SELECT
                prop_dtl_id,
                STRING_AGG(owner_name, ',') AS owner_name,
                STRING_AGG(mobile_no::TEXT, ',') AS mobile_no,
                STRING_AGG(email::TEXT, ',') AS email,
                STRING_AGG(guardian_name::TEXT, ',') AS guardian_name
            FROM tbl_prop_owner_detail
            GROUP BY prop_dtl_id
        ) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id           
        WHERE 
            tbl_prop_dtl.id='".$noticeDtl["prop_dtl_id"]."'";
        $result = $this->db->query($sql)->getFirstRow("array");

        $data['ulb']=$ulb_mstr_dtl;
        $data['notice']=$noticeDtl;
        $data['property']=$result;
        return view("mobile/Property/property/noticeServed",$data);
    }
    */

    public function defaulterNoticesDtl(){
        ini_set('max_execution_time', 30000);
        $data = $this->request->getVar();
        $fyear = getFY();
        $fyearFromDate = explode("-",$fyear)[0]."-04-01";
        $fyearUptoDate = explode("-",$fyear)[1]."-03-31";
        // $privFyear = getFY(explode("-",$fyear)[0]."-03-31");
        $fromDate = $this->request->getVar("fromDate");
        $uptoDate = $this->request->getVar("uptoDate");
        $fromAmt = $this->request->getVar("from_amt");
        $uptoAmt = $this->request->getVar("upto_amt");

        if ($this->request->getMethod() == "post" ) {            
            if($this->request->getVar("selecteditems")){
                foreach($this->request->getVar("selecteditems") as $prop_id){ 
                    if($this->request->getVar("nextNotice")){
                        $this->generatedNoticeUpdateSerial($prop_id);
                    }else{
                        $this->Notice($prop_id);
                    }                  
                }
                return true;
            }
            $where1 = "WHERE 1=1 ";
            $where = " where tbl_prop_dtl.status = 1 ";
            if ($this->request->getVar("ward_id")) {
                $where .= " AND tbl_prop_dtl.ward_mstr_id = " . $this->request->getVar("ward_id");
                $where1 .= " AND view_ward_mstr.id = ". $this->request->getVar("ward_id");
            }
            if ($this->request->getVar("property_type_id")) {
                $where .= " AND tbl_prop_dtl.prop_type_mstr_id = " . $this->request->getVar("property_type_id");
            }

            
            $start = sanitizeString($this->request->getVar('start'));
            $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

            $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']);
            $orderBY = " ORDER BY  (substring(ward_no, '^[0-9]+'))::int " . $columnSortOrder;
            $limit = " LIMIT " . ($rowperpage == -1 ? "ALL" : $rowperpage) . " OFFSET " . $start;


            $searchValue = sanitizeString($this->request->getVar('search')['value']);
            $whereQueryWithSearch = "";

            $with ="with demands_ as(
                        SELECT 
                            prop_dtl_id,
                            SUM(balance - (balance*10.9/100)) AS balance                            
                        FROM tbl_prop_demand
                        join(
                            select distinct(prop_dtl_id) as temp_prop_id
                            from tbl_prop_demand
                            WHERE status=1 AND paid_status=0 and fyear < '$fyear' and balance>0
                        )arrear on arrear.temp_prop_id = tbl_prop_demand.prop_dtl_id
                        WHERE status=1 
                        AND paid_status=0 
                        and balance > 0
                        and fyear < '$fyear'
                        GROUP BY prop_dtl_id
                    ) ,
                    demands as(
                        SELECT 
                            prop_dtl_id,
                            SUM(balance) AS balance                            
                        FROM (
                                (
                                    select distinct(prop_dtl_id) as prop_dtl_id ,SUM(balance - (balance*10.9/100)) AS balance
                                    from tbl_prop_demand
                                    WHERE status=1 AND paid_status=0 and fyear < '$fyear' and balance>0
                                    GROUP BY prop_dtl_id
                                )
                                union(
                                    select distinct prop_dtl_id as prop_dtl_id,SUM(amount - (amount*10.9/100)) AS balance
                                    from tbl_collection
                                    where fyear<'$fyear' and status=1 and created_on::date between '$fyearFromDate' and '$fyearUptoDate'
                                    GROUP BY prop_dtl_id
                                )
                        )arrear 
                        WHERE balance > 0
                        GROUP BY prop_dtl_id
                    ) ,
                    remaining_defaulter as(
                        select distinct(tbl_prop_demand.prop_dtl_id) as prop_dtl_id ,
                            SUM(tbl_prop_demand.balance - (tbl_prop_demand.balance*10.9/100)) AS balance
                        from tbl_prop_demand
                        left join demands on demands.prop_dtl_id =  tbl_prop_demand.prop_dtl_id  
                        WHERE status=1 AND paid_status=0 and fyear < '$fyear' and tbl_prop_demand.balance>0
                            ".($fromAmt && $uptoAmt 
                                ? " AND round(demands.balance) BETWEEN $fromAmt AND $uptoAmt " 
                                : (
                                    $fromAmt 
                                    ? " AND round(demands.balance) >= $fromAmt " 
                                    : (
                                        $uptoAmt 
                                        ? "AND round(demands.balance) <= $uptoAmt " 
                                        : ""
                                        )
                                   )
                             )." 
                        GROUP BY tbl_prop_demand.prop_dtl_id
                    ), 
                    notices as (
                        select tbl_prop_notices.*
                        from tbl_prop_notices
                        JOIN(
                            SELECT max(id) as max_id ,prop_dtl_id as max_prop_dtl_id
                            FROM tbl_prop_notices
                            where status!=0 and fnyear='$fyear' and notice_type='Demand'
                            group by prop_dtl_id
                        ) as last_notice on last_notice.max_id = tbl_prop_notices.id 
                        left join demands on demands.prop_dtl_id =  tbl_prop_notices.prop_dtl_id 
                        where 1=1 ".($fromAmt && $uptoAmt 
                                ? " AND round(demands.balance) BETWEEN $fromAmt AND $uptoAmt " 
                                : (
                                    $fromAmt 
                                    ? " AND round(demands.balance) >= $fromAmt " 
                                    : (
                                        $uptoAmt 
                                        ? "AND round(demands.balance) <= $uptoAmt " 
                                        : ""
                                        )
                                   )
                             )."                
                    ),
                    defaulter_pay_without_notice as (
                        select collection_from.prop_dtl_id, collection_from.balance
                        from (
                                select distinct prop_dtl_id as prop_dtl_id,SUM(amount - (amount*10.9/100)) AS balance
                                from tbl_collection
                                where fyear<'$fyear' and status=1 and created_on::date between '$fyearFromDate' and '$fyearUptoDate'
                                GROUP BY prop_dtl_id
                        )collection_from
                        join demands on demands.prop_dtl_id =  collection_from.prop_dtl_id  
                        left join notices on notices.prop_dtl_id = collection_from.prop_dtl_id
                        left join remaining_defaulter on remaining_defaulter.prop_dtl_id = collection_from.prop_dtl_id
                        where notices.id is null AND remaining_defaulter.prop_dtl_id is null
                            ".($fromAmt && $uptoAmt 
                                ? " AND round(demands.balance) BETWEEN $fromAmt AND $uptoAmt " 
                                : (
                                    $fromAmt 
                                    ? " AND round(demands.balance) >= $fromAmt " 
                                    : (
                                        $uptoAmt 
                                        ? "AND round(demands.balance) <= $uptoAmt " 
                                        : ""
                                        )
                                   )
                             )."
                        
                    ),
                    records as (
                        SELECT view_ward_mstr.ward_no, view_ward_mstr.id,
                            count(tbl_prop_dtl.id) as total, sum( COALESCE(tbl_prop_dtl.balance,0)) as balance ,
                            count(case when tbl_prop_dtl.remaing_prop_dtl_id is not null then tbl_prop_dtl.id end) as total_remaining_defaulter  ,
		                    count(case when tbl_prop_dtl.withot_prop_dtl_id is not null then tbl_prop_dtl.id end) as total_defaulter_pay_without_notice  ,
                            count(case when notices.id is not null then tbl_prop_dtl.id end) as total_notice_generated  ,
                            count(case when notices.notice_served_by is not null then tbl_prop_dtl.id end) as total_notice_served  ,
                            count(case when notices.id is not null AND notices.clear_by_id is not null and tbl_prop_dtl.remaing_prop_dtl_id is null then tbl_prop_dtl.id end) as total_payment_received_from_notice ,
		                    count(case when notices.id is not null AND notices.clear_by_id is not null and tbl_prop_dtl.remaing_prop_dtl_id is not null then tbl_prop_dtl.id end) as top_prop_receive_payment_and_regen_demand                 
                        FROM view_ward_mstr                         
                        LEFT JOIN (
                            SELECT tbl_prop_dtl.ward_mstr_id, tbl_prop_dtl.id, demands.balance,								
                                case when remaining_defaulter.prop_dtl_id is not null then tbl_prop_dtl.id else null end as remaing_prop_dtl_id,
                                case when defaulter_pay_without_notice.prop_dtl_id is not null then tbl_prop_dtl.id else null end as withot_prop_dtl_id
							FROM tbl_prop_dtl
                            left join tbl_saf_dtl on tbl_saf_dtl.previous_holding_id = tbl_prop_dtl.id::text and tbl_saf_dtl.saf_pending_status !=1 and tbl_saf_dtl.status =1
							JOIN tbl_prop_type_mstr on tbl_prop_type_mstr.id = tbl_prop_dtl.prop_type_mstr_id
	                        JOIN demands on demands.prop_dtl_id = tbl_prop_dtl.id     
                            left join remaining_defaulter on remaining_defaulter.prop_dtl_id = tbl_prop_dtl.id
		                    left join defaulter_pay_without_notice on defaulter_pay_without_notice.prop_dtl_id = tbl_prop_dtl.id                       
                            $where  and demands.balance>0 and (tbl_saf_dtl.assessment_type not in('Mutation','Mutation with Reassessment') OR tbl_saf_dtl.id is null)
                                    AND tbl_prop_dtl.status=1
                                    and char_length(tbl_prop_dtl.new_holding_no)>0 
                                    and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) 
                            ".($fromAmt && $uptoAmt 
                                ? " AND round(demands.balance) BETWEEN $fromAmt AND $uptoAmt " 
                                : (
                                    $fromAmt 
                                    ? " AND round(demands.balance) >= $fromAmt " 
                                    : (
                                        $uptoAmt 
                                        ? "AND round(demands.balance) <= $uptoAmt " 
                                        : ""
                                        )
                                   )
                             )."
                        ) tbl_prop_dtl on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                        left join notices on notices.prop_dtl_id = tbl_prop_dtl.id 
                        $where1
                        GROUP BY view_ward_mstr.id, view_ward_mstr.ward_no
                    )            
            ";

            $select = "select ROW_NUMBER() OVER ( $orderBY) as s_no,records.*
            ";
            $select2 = "select count(id)ward_no, sum(total)total,round(sum(balance))balance ";
            $from = "from records                
            ";
            // print_var($with.$select.$from);die;
            $totalRecords = $this->model_datatable->getTotalRecords($from, false,$with); 
            // print_var($this->db->getLastQuery());die;

            if ($totalRecords > 0) {

                ## Total number of records with filtering
                $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($from . $whereQueryWithSearch, false,$with);

                ## Fetch records                
                $fetchSql = $with.$select . $from . $whereQueryWithSearch . $orderBY;
                $fetchSql .= $limit;

                $result = $this->model_datatable->getRecords($fetchSql, false);

                $records = [];
                if ($result) {
                    $ward_wish_Arrear_for_current=[
                        "1" => 721238.54,
                        "1A" => 0,
                        "2" => 5857636.78,
                        "2A" => 0,
                        "3" => 2802960.72,
                        "3A" => 0,
                        "4" => 6048756.17,
                        "4A" => 0,
                        "5" => 5020476.73,
                        "5A" => 0,
                        "6" => 1435251.55,
                        "6A" => 0,
                        "7" => 4306830.81,
                        "7A" => 0,
                        "8" => 3237438.22,
                        "8A" => 0,
                        "9" => 1187196.24,
                        "9A" => 0,
                        "10" => 4965912.81,
                        "10A" => 0,
                        "11" => 1825202.47,
                        "11A" => 0,
                        "12" => 1119567.8,
                        "12A" => 0,
                        "13" => 776844.62,
                        "13A" => 0,
                        "14" => 900834.45,
                        "14A" => 0,
                        "15" => 5120181.4,
                        "15A" => 0,
                        "16" => 951775.27,
                        "16A" => 0,
                        "17" => 1036012.58,
                        "17A" => 0,
                        "18" => 352502.4,
                        "18A" => 0,
                        "19" => 2724219.91,
                        "19A" => 0,
                        "20" => 2735329.96,
                        "20A" => 0,
                        "21" => 2794882.81,
                        "21A" => 6271.82,
                        "22" => 3111960.99,
                        "22A" => 4950.94,
                        "23" => 3174090.1,
                        "23A" => 0,
                        "24" => 2221832.56,
                        "24A" => 0,
                        "25" => 2842801.62,
                        "26" => 1442803.09,
                        "27" => 3021048,
                        "27A" => 0,
                        "28" => 1602256.36,
                        "28A" => 0,
                        "29" => 2762152.96,
                        "30" => 1280166.26,
                        "30A" => 0,
                        "31" => 2641690.02,
                        "32" => 1086562.21,
                        "32A" => 0,
                        "33" => 2160694.63,
                        "34" => 2626770.82,
                        "34A" => 0,
                        "35" => 2282141.77,
                        "35A" => 0,
                        "36" => 1447102.92,
                        "36A" => 0,
                        "37" => 4329231.17,
                        "37A" => 0,
                        "38" => 8668274.93,
                        "38/2" => 196950.64,
                        "38A" => 0,
                        "39" => 505648.57,
                        "40" => 548201.41,
                        "41" => 60030.87,
                        "42" => 262987.41,
                        "42A" => 0,
                        "43" => 1875103.54,
                        "44" => 1014046.87,
                        "45" => 3156569.32,
                        "45/1" => 5012.95,
                        "45/2" => 11863.11,
                        "45/3" => 23049.26,
                        "45/4" => 55031.34,
                        "45/5" => 2274.26,
                        "45/6" => 0,
                        "45/7" => 0,
                        "45/8" => 273.91,
                        "45/9" => 0,
                        "45A" => 0,
                        "46" => 863396.73,
                        "46/1" => 0,
                        "46/10" => 0,
                        "46/11" => 0,
                        "46/12" => 0,
                        "46/13" => 0,
                        "46/2" => 0,
                        "46/3" => 0,
                        "46/4" => 0,
                        "46/5" => 1182.46,
                        "46/6" => 0,
                        "46/7" => 0,
                        "46/8" => 0,
                        "46/9" => 0,
                        "46A" => 0,
                        "47" => 2414902.44,
                        "47/1" => 0,
                        "47/2" => 0,
                        "47/3" => 10566.07,
                        "47/5" => 12514.26,
                        "47/7" => 5295.41,
                        "47A" => 0,
                        "48" => 1527247.48,
                        "48A" => 0,
                        "49" => 1578811.18,
                        "49A" => 0,
                        "50" => 937187.74,
                        "50/1" => 0,
                        "50/2" => 2869.91,
                        "50/4" => 0,
                        "50/5" => 3908.1,
                        "50A" => 0,
                        "51" => 697529.35,
                        "51/2" => 0,
                        "51/3" => 0,
                        "52" => 2809746.34,
                        "52/1" => 11975.98,
                        "52/10" => 0,
                        "52/11" => 0,
                        "52/12" => 0,
                        "52/2" => 6973.49,
                        "52/3" => 4259.45,
                        "52/4" => 0,
                        "52/5" => 1470.06,
                        "52/6" => 0,
                        "52/7" => 0,
                        "52A" => 0,
                        "53" => 839397.58,
                        "53/1" => 0,
                        "53/10" => 0,
                        "53/12" => 0,
                        "53/15" => 0,
                        "53/2" => 22987.62,
                        "53/3" => 5082.62,
                        "53A" => 0,
                        "54" => 3022250.82,
                        "54/11" => 0,
                        "54/13" => 0,
                        "54/4" => 0,
                        "54/5" => 0,
                        "54/6" => 10337.58,
                        "54/7" => 0,
                        "54/8" => 0,
                        "54/9" => 0,
                        "55" => 506368.38,
                        "55/1" => 0,
                        "55/2" => 0,
                        "55/3" => 0,
                        "55A" => 0,
                    ];
                    foreach ($result as $key => $val) {
                        $arr = $ward_wish_Arrear_for_current[$val["ward_no"]];
                        if($result[$key]['balance']>$arr)
                        $result[$key]['balance'] = $arr;                        
                    }                    
                    
                    foreach ($result as $key => $tran_dtl) {
                        $records[] = [
                            's_no' => $tran_dtl['s_no'],
                            'ward_no' => (($ajax??"") ? $tran_dtl['ward_no'] : ("<a href = '#' onClick ='openWindow(".$tran_dtl["id"].",".'"All"'.")'/>" .$tran_dtl['ward_no'] ."</a>")) ,
                            'balance' => round($tran_dtl['balance'],2),
                            'total' => (($ajax??"") ? $tran_dtl['total'] : ("<a href = '#' onClick ='openWindow(".$tran_dtl["id"].",".'"All"'.")'/>" .$tran_dtl['total'] ."</a>")),
                            'total_remaining_defaulter' => (($ajax??"") ? $tran_dtl['total_remaining_defaulter'] : ("<a href = '#' onClick ='openWindow(".$tran_dtl["id"].",".'"total_remaining_defaulter"'.")'/>" .$tran_dtl['total_remaining_defaulter'] ."</a>")),
                            'total_defaulter_pay_without_notice' => (($ajax??"") ? $tran_dtl['total_defaulter_pay_without_notice'] : ("<a href = '#' onClick ='openWindow(".$tran_dtl["id"].",".'"total_defaulter_pay_without_notice"'.")'/>" .$tran_dtl['total_defaulter_pay_without_notice'] ."</a>")),
                            'total_notice_generated' => (($ajax??"") ? $tran_dtl['total_notice_generated'] : ("<a href = '#' onClick ='openWindow(".$tran_dtl["id"].",".'"total_notice_generated"'.")'/>" .$tran_dtl['total_notice_generated'] ."</a>")),
                            'total_notice_served' => (($ajax??"") ? $tran_dtl['total_notice_served'] : ("<a href = '#' onClick ='openWindow(".$tran_dtl["id"].",".'"total_notice_served"'.")'/>" .$tran_dtl['total_notice_served'] ."</a>")),
                            'total_payment_received_from_notice' => (($ajax??"") ? $tran_dtl['total_payment_received_from_notice'] : ("<a href = '#' onClick ='openWindow(".$tran_dtl["id"].",".'"total_payment_received_from_notice"'.")'/>" .$tran_dtl['total_payment_received_from_notice'] ."</a>")),
                            "top_prop_receive_payment_and_regen_demand" => $tran_dtl["top_prop_receive_payment_and_regen_demand"],
                            "collection_from_prop" => $tran_dtl["collection_from_prop"],
                            "total_collection_demand"=>round($tran_dtl["total_collection_demand"],2),    
                        ];
                    }

                }
            }
            $totalSummary=[
                "ward_no"=>sizeof($result??[])??0,
                "total"=>array_sum(array_column($result??[],"total"))??0,
                "balance"=>array_sum(array_column($result??[],"balance"))??0,
                "total_remaining_defaulter"=>array_sum(array_column($result??[],"total_remaining_defaulter"))??0,
                "total_defaulter_pay_without_notice"=>array_sum(array_column($result??[],"total_defaulter_pay_without_notice"))??0,
                "total_notice_generated"=>array_sum(array_column($result??[],"total_notice_generated"))??0,
                "total_notice_served"=>array_sum(array_column($result??[],"total_notice_served"))??0,
                "total_payment_received_from_notice"=>array_sum(array_column($result??[],"total_payment_received_from_notice"))??0,
            ];
            $response = array(
                "draw" => 0,
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecordwithFilter??0,
                "data" => $records??[],
                "summary"=>$totalSummary,

            );
            return json_encode($response);
        }
        $ulb_mstr_id = 1;
        $data['wardList'] = $this->model_ward_mstr->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
        $data["propertyTypeList"] = $this->db->query("SELECT id,property_type FROM tbl_prop_type_mstr WHERE status =1 ORDER BY property_type ASC ")->getResultArray(); 
        // dd("klsdflsk");       
        return view("property/ward_wise_bulk_generate_noticeDtl",$data);
    }

    public function generateBulkNoticeDtl($ajax = false){
        ini_set('max_execution_time', 30000);

        $data = $this->request->getVar();
        $fyear = getFY();        
        $fyearFromDate = explode("-",$fyear)[0]."-04-01";
        $fyearUptoDate = explode("-",$fyear)[1]."-03-31";
        // $privFyear = getFY(explode("-",$fyear)[0]."-03-31");
        $fromDate = $this->request->getVar("fromDate");
        $uptoDate = $this->request->getVar("uptoDate");
        $fromAmt = $this->request->getVar("from_amt");
        $uptoAmt = $this->request->getVar("upto_amt");
        if ($this->request->getMethod() == "post" || $ajax) {           
            
            $where1 = "WHERE 1=1 ";
            $where = " where tbl_prop_dtl.status = 1 ";
            if ($this->request->getVar("ward_id")) {
                $where .= " AND tbl_prop_dtl.ward_mstr_id = " . $this->request->getVar("ward_id");
                $where1 .= " AND view_ward_mstr.id = ". $this->request->getVar("ward_id");
            }
            $where = " where tbl_prop_dtl.status = 1 ";
            if ($this->request->getVar("ward_id")) {
                $where .= " AND tbl_prop_dtl.ward_mstr_id = " . $this->request->getVar("ward_id");
            }
            if ($this->request->getVar("property_type_id")) {
                $where .= " AND tbl_prop_dtl.prop_type_mstr_id = " . $this->request->getVar("property_type_id");
            }

            
            $start = sanitizeString($this->request->getVar('start'));
            $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

            $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
            $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
            if ($columnName == "ward_no")
                $columnName = 'record.ward_mstr_id';
            else
                $columnName = 'records.prop_id';

            $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']);
            $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
            $limit = " LIMIT " . ($rowperpage == -1 ? "ALL" : $rowperpage) . " OFFSET " . $start;


            $searchValue = sanitizeString($this->request->getVar('search')['value']);
            $whereQueryWithSearch = "";
            if ($searchValue != '') {
                $whereQueryWithSearch = " AND (
                    tbl_prop_dtl.new_holding_no ILIKE '%" . $searchValue . "%'
                    OR tbl_prop_dtl.holding_no ILIKE '%" . $searchValue . "%'
                    OR tbl_prop_dtl.prop_address ILIKE '%" . $searchValue . "%'
                    OR owneres.owner_name ILIKE '%" . $searchValue . "%'
                    OR owneres.mobile_no ILIKE '%" . $searchValue . "%'
                    
                    )
                ";
            }

            $with ="with demands as(
                        SELECT 
                            prop_dtl_id,
                            SUM(balance) AS balance,
                            min(from_fyear) as from_fyear, max(upto_fyear) upto_fyear                            
                        FROM (
                                (
                                    select distinct(prop_dtl_id) as prop_dtl_id ,SUM(balance - (balance*10.9/100)) AS balance,
                                        min(fyear) as from_fyear, max(fyear) upto_fyear
                                    from tbl_prop_demand
                                    WHERE status=1 AND paid_status=0 and fyear < '$fyear' and balance>0
                                    GROUP BY prop_dtl_id
                                )
                                union(
                                    select distinct prop_dtl_id as prop_dtl_id,SUM(amount - (amount*10.9/100)) AS balance,
                                        min(fyear) as from_fyear, max(fyear) upto_fyear
                                    from tbl_collection
                                    where fyear<'$fyear' and status=1 and created_on::date between '$fyearFromDate' and '$fyearUptoDate'
                                    GROUP BY prop_dtl_id
                                )
                        )arrear 
                        WHERE balance > 0
                        GROUP BY prop_dtl_id
                    ) ,
                    remaining_defaulter as(
                        select distinct(tbl_prop_demand.prop_dtl_id) as prop_dtl_id ,
                            SUM(tbl_prop_demand.balance - (tbl_prop_demand.balance*10.9/100)) AS balance
                        from tbl_prop_demand
                        left join demands on demands.prop_dtl_id =  tbl_prop_demand.prop_dtl_id  
                        WHERE status=1 AND paid_status=0 and fyear < '$fyear' and tbl_prop_demand.balance>0
                            ".($fromAmt && $uptoAmt 
                                ? " AND round(demands.balance) BETWEEN $fromAmt AND $uptoAmt " 
                                : (
                                    $fromAmt 
                                    ? " AND round(demands.balance) >= $fromAmt " 
                                    : (
                                        $uptoAmt 
                                        ? "AND round(demands.balance) <= $uptoAmt " 
                                        : ""
                                        )
                                )
                            )." 
                        GROUP BY tbl_prop_demand.prop_dtl_id
                    ), 
                    notices as (
                        select tbl_prop_notices.*,tran_no,tran_date
                        from tbl_prop_notices
                        JOIN(
                            SELECT max(id) as max_id ,prop_dtl_id as max_prop_dtl_id
                            FROM tbl_prop_notices
                            where status!=0 and fnyear='$fyear' and notice_type='Demand'
                            group by prop_dtl_id
                        ) as last_notice on last_notice.max_id = tbl_prop_notices.id 
                        left join demands on demands.prop_dtl_id =  tbl_prop_notices.prop_dtl_id 
                        left join tbl_transaction on tbl_transaction.id = tbl_prop_notices.clear_by_id
                        where 1=1 ".($fromAmt && $uptoAmt 
                                ? " AND round(demands.balance) BETWEEN $fromAmt AND $uptoAmt " 
                                : (
                                    $fromAmt 
                                    ? " AND round(demands.balance) >= $fromAmt " 
                                    : (
                                        $uptoAmt 
                                        ? "AND round(demands.balance) <= $uptoAmt " 
                                        : ""
                                        )
                                )
                            )."                
                    ),
                    defaulter_pay_without_notice as (
                        select collection_from.prop_dtl_id, collection_from.balance
                        from (
                                select distinct prop_dtl_id as prop_dtl_id,SUM(amount - (amount*10.9/100)) AS balance
                                from tbl_collection
                                where fyear<'$fyear' and status=1 and created_on::date between '$fyearFromDate' and '$fyearUptoDate'
                                GROUP BY prop_dtl_id
                        )collection_from
                        join demands on demands.prop_dtl_id =  collection_from.prop_dtl_id  
                        left join notices on notices.prop_dtl_id = collection_from.prop_dtl_id
                        left join remaining_defaulter on remaining_defaulter.prop_dtl_id = collection_from.prop_dtl_id
                        where notices.id is null AND remaining_defaulter.prop_dtl_id is null
                            ".($fromAmt && $uptoAmt 
                                ? " AND round(demands.balance) BETWEEN $fromAmt AND $uptoAmt " 
                                : (
                                    $fromAmt 
                                    ? " AND round(demands.balance) >= $fromAmt " 
                                    : (
                                        $uptoAmt 
                                        ? "AND round(demands.balance) <= $uptoAmt " 
                                        : ""
                                        )
                                )
                            )."
                        
                    ),
                    owners as (
                        select string_agg(owner_name,',')as owner_name,string_agg(mobile_no::text,',')as mobile_no,
                            tbl_prop_owner_detail.prop_dtl_id
                        from tbl_prop_owner_detail
                        where tbl_prop_owner_detail.status =1 
                        group by tbl_prop_owner_detail.prop_dtl_id
                    ),
                    records as (
                        SELECT view_ward_mstr.ward_no, view_ward_mstr.id, view_ward_mstr.ward_no,tbl_prop_dtl.id as prop_id,
                            prop_address,holding_no,property_type,
                            owners.owner_name, owners.mobile_no,
                            tbl_prop_dtl.from_fyear,tbl_prop_dtl.upto_fyear,
                            notices.id as notice_id, concat('NOTICE/',notices.notice_no,' (',notices.notice_date,')') as notice_no,
                            notices.notice_served_on as notice_served_on,
                            notices.notice_receiving as notice_receiving,
                            concat(notices.tran_no,' (',notices.tran_date,')') as tran_dtl,
                            (tbl_prop_dtl.id) as total, 
                            ( COALESCE(tbl_prop_dtl.balance,0)) as balance ,
                            (case when tbl_prop_dtl.remaing_prop_dtl_id is not null then tbl_prop_dtl.id end) as total_remaining_defaulter  ,
                            (case when tbl_prop_dtl.withot_prop_dtl_id is not null then tbl_prop_dtl.id end) as total_defaulter_pay_without_notice  ,
                            (case when notices.id is not null then tbl_prop_dtl.id end) as total_notice_generated  ,
                            (case when notices.notice_served_by is not null then tbl_prop_dtl.id end) as total_notice_served  ,
                            (case when notices.id is not null AND notices.clear_by_id is not null and tbl_prop_dtl.remaing_prop_dtl_id is null then notices.clear_by_id end) as total_payment_received_from_notice ,
                            (case when notices.id is not null AND notices.clear_by_id is not null and tbl_prop_dtl.remaing_prop_dtl_id is not null then tbl_prop_dtl.id end) as top_prop_receive_payment_and_regen_demand                 
                        FROM view_ward_mstr                         
                        JOIN (
                            SELECT tbl_prop_dtl.ward_mstr_id, tbl_prop_dtl.id, demands.balance,	
                                case when tbl_prop_dtl.new_holding_no is null then tbl_prop_dtl.holding_no else tbl_prop_dtl.new_holding_no end as holding_no,
                                tbl_prop_dtl.prop_address,tbl_prop_type_mstr.property_type,
                                case when remaining_defaulter.prop_dtl_id is not null then tbl_prop_dtl.id else null end as remaing_prop_dtl_id,
                                case when defaulter_pay_without_notice.prop_dtl_id is not null then tbl_prop_dtl.id else null end as withot_prop_dtl_id,
                                from_fyear,upto_fyear
                            FROM tbl_prop_dtl
                            left join tbl_saf_dtl on tbl_saf_dtl.previous_holding_id = tbl_prop_dtl.id::text and tbl_saf_dtl.saf_pending_status !=1 and tbl_saf_dtl.status =1
                            JOIN tbl_prop_type_mstr on tbl_prop_type_mstr.id = tbl_prop_dtl.prop_type_mstr_id
                            JOIN demands on demands.prop_dtl_id = tbl_prop_dtl.id     
                            left join remaining_defaulter on remaining_defaulter.prop_dtl_id = tbl_prop_dtl.id
                            left join defaulter_pay_without_notice on defaulter_pay_without_notice.prop_dtl_id = tbl_prop_dtl.id                       
                            $where  and demands.balance>0 and (tbl_saf_dtl.assessment_type not in('Mutation','Mutation with Reassessment') OR tbl_saf_dtl.id is null)
                                    AND tbl_prop_dtl.status=1
                                    and char_length(tbl_prop_dtl.new_holding_no)>0 
                                    and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) 
                            ".($fromAmt && $uptoAmt 
                                ? " AND round(demands.balance) BETWEEN $fromAmt AND $uptoAmt " 
                                : (
                                    $fromAmt 
                                    ? " AND round(demands.balance) >= $fromAmt " 
                                    : (
                                        $uptoAmt 
                                        ? "AND round(demands.balance) <= $uptoAmt " 
                                        : ""
                                        )
                                )
                            )."
                        ) tbl_prop_dtl on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                        left join notices on notices.prop_dtl_id = tbl_prop_dtl.id 
                        left join owners on owners.prop_dtl_id = tbl_prop_dtl.id
                        $where1
                        -- GROUP BY view_ward_mstr.id, view_ward_mstr.ward_no,tbl_prop_dtl.id 
                    )            
            ";

            $select = "select ROW_NUMBER() OVER ( $orderBY) as s_no,records.*
            ";
            $from = "from records                
            ";
            $fromWhere = " WHERE 1=1 ";
            if($this->request->getVar("report_type")=="total_remaining_defaulter" || $this->request->getVar("report_type")=="All"){
                $fromWhere.=" AND records.total_remaining_defaulter is not null ";
            }

            if($this->request->getVar("report_type")=="total_defaulter_pay_without_notice"){
                $fromWhere.=" AND records.total_defaulter_pay_without_notice is not null ";
            }

            if($this->request->getVar("report_type")=="total_notice_generated"){
                $fromWhere.=" AND records.total_notice_generated is not null ";
            }
            if($this->request->getVar("report_type")=="total_notice_served"){
                $fromWhere.=" AND records.total_notice_served is not null ";
            }
            if($this->request->getVar("report_type")=="total_payment_received_from_notice"){
                $fromWhere.=" AND records.total_payment_received_from_notice is not null ";
            }
            $from.=$fromWhere;
            // print_var($with.$select.$from);die;
            $totalRecords = $this->model_datatable->getTotalRecords($from, false,$with);

            if ($totalRecords > 0) {

                ## Total number of records with filtering
                $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($from . $whereQueryWithSearch, false,$with);

                ## Fetch records                
                $fetchSql = $with.$select . $from . $whereQueryWithSearch . $orderBY;
                if (!$ajax) {
                    $fetchSql .= $limit;
                }
                $result = $this->model_datatable->getRecords($fetchSql, false);

                $records = [];                
                foreach ($result as $key => $tran_dtl) {
                    $records[] = [
                        's_no' => $tran_dtl['s_no'],
                        'ward_no' => $tran_dtl['ward_no'],
                        'holding_no' => $tran_dtl['holding_no'],
                        "property_type"=>$tran_dtl["property_type"],
                        'prop_address' => $tran_dtl["prop_address"],
                        'owner_name' => $tran_dtl['owner_name'],
                        'mobile_no' => $tran_dtl['mobile_no'],
                        'from' => ($tran_dtl['from_fyear']),
                        'upto' => ($tran_dtl['upto_fyear']),
                        'balance' => ($tran_dtl['balance']),
                        "noticeLink" => $ajax ? ($tran_dtl["notice_id"]?"Yeas":"") : ( $tran_dtl["notice_id"] ? "<a class ='btn btn-info btn-sm' target='blanck' href = '" . base_url("propDtl/GeneratedNotice/" . md5($tran_dtl["notice_id"])) . "'>".$tran_dtl["notice_no"]."</a>" : ""),
                        "noticeReivingLink" => $ajax ? ($tran_dtl["notice_receiving"] ?"Yeas":"") : (  $tran_dtl["notice_receiving"] ? "<a class ='btn btn-info btn-sm' target='blanck' href = '" . base_url("getImageLink.php?path=" . ($tran_dtl["notice_receiving"])) . "'>".$tran_dtl["notice_served_on"]."</a>" : ""),
                        "is_payment_reive" => $ajax ? ($tran_dtl["total_payment_received_from_notice"] ? "Yes" :"") : (  $tran_dtl["total_payment_received_from_notice"] ? "<a class ='btn btn-info btn-sm' target='blanck' href = '" . base_url("jsk/payment_jsk_receipt/" . md5($tran_dtl["total_payment_received_from_notice"])) . "'>".$tran_dtl["tran_dtl"]."</a>" : ""),
                        "link" => $ajax ? "" : ("<a class ='btn btn-info btn-sm' target='blanck' href = '" . base_url("propDtl/full/" . $tran_dtl["id"]) . "'>view</a>"),

                    ];
                }
            }
            if ($ajax) {
                phpOfficeLoad();
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $activeSheet = $spreadsheet->getActiveSheet();

                $activeSheet->setCellValue('A1', 'Sl No.');
                $activeSheet->setCellValue('B1', 'Ward No');
                $activeSheet->setCellValue('C1', 'Holding No');                                       
                $activeSheet->setCellValue('D1', 'Property Type');
                $activeSheet->setCellValue('E1', 'Address');
                $activeSheet->setCellValue('F1', 'Owner Name');
                $activeSheet->setCellValue('G1', 'Mobile No.');
                $activeSheet->setCellValue('H1', 'From');
                $activeSheet->setCellValue('I1', 'Upto');
                $activeSheet->setCellValue('J1', 'Demand Amount');
                $activeSheet->setCellValue('K1', 'Is Notice Generated');
                $activeSheet->setCellValue('L1', 'Is Notice Served');
                $activeSheet->setCellValue('M1', 'Is Payment From Notice');

                $filename = "Defaulter" . date('Ymd-His') . ".xlsx";                

                $activeSheet->fromArray($records, NULL, 'A3');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');

            } else {
                $response = array(
                    "draw" => 0,
                    "recordsTotal" => $totalRecords,
                    "recordsFiltered" => $totalRecordwithFilter??0,
                    "data" => $records??[],
                    // "summary"=>$totalSummary,

                );
                return json_encode($response);
            }
        }
        $ulb_mstr_id = 1;
        $data['wardList'] = $this->model_ward_mstr->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
        $data["propertyTypeList"] = $this->db->query("SELECT id,property_type FROM tbl_prop_type_mstr WHERE status =1 ORDER BY property_type ASC ")->getResultArray();        
        return view("property/bulk_generate_dtl",$data);
    }
        
    public function defaulterNotices(){
        $data = $this->request->getVar();
        $fyear = getFY();
        // $privFyear = getFY(explode("-",$fyear)[0]."-03-31");
        $fromDate = $this->request->getVar("fromDate");
        $uptoDate = $this->request->getVar("uptoDate");
        $fromAmt = $this->request->getVar("from_amt");
        $uptoAmt = $this->request->getVar("upto_amt");

        if ($this->request->getMethod() == "post" ) {            
            if($this->request->getVar("selecteditems")){
                foreach($this->request->getVar("selecteditems") as $prop_id){ 
                    if($this->request->getVar("nextNotice")){
                        $this->generatedNoticeUpdateSerial($prop_id);
                    }else{
                        $this->Notice($prop_id);
                    }                  
                }
                return true;
            }
            $where1 = "WHERE 1=1 ";
            $where = " where tbl_prop_dtl.status = 1 ";
            if ($this->request->getVar("ward_id")) {
                $where .= " AND tbl_prop_dtl.ward_mstr_id = " . $this->request->getVar("ward_id");
                $where1 .= " AND view_ward_mstr.id = ". $this->request->getVar("ward_id");
            }
            if ($this->request->getVar("property_type_id")) {
                $where .= " AND tbl_prop_dtl.prop_type_mstr_id = " . $this->request->getVar("property_type_id");
            }

            
            $start = sanitizeString($this->request->getVar('start'));
            $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

            $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']);
            $orderBY = " ORDER BY  (substring(ward_no, '^[0-9]+'))::int " . $columnSortOrder;
            $limit = " LIMIT " . ($rowperpage == -1 ? "ALL" : $rowperpage) . " OFFSET " . $start;


            $searchValue = sanitizeString($this->request->getVar('search')['value']);
            $whereQueryWithSearch = "";

            $with ="with demands as(
                        SELECT 
                            prop_dtl_id,
                            SUM(balance) AS balance                            
                        FROM tbl_prop_demand
                        join(
                            select distinct(prop_dtl_id) as temp_prop_id
                            from tbl_prop_demand
                            WHERE status=1 AND paid_status=0 and fyear < '$fyear' and balance>0
                        )arrear on arrear.temp_prop_id = tbl_prop_demand.prop_dtl_id
                        WHERE status=1 AND paid_status=0 and fyear < '$fyear'
                        GROUP BY prop_dtl_id
                    ) ,
                    notices as (
                        select prop_dtl_id
                        from tbl_prop_notices
                        where status!=0 and fnyear='$fyear' and notice_type='Demand'
                        group by prop_dtl_id
                    ),
                    records as (
                        SELECT view_ward_mstr.ward_no, view_ward_mstr.id,
                            count(tbl_prop_dtl.id) as total, sum( tbl_prop_dtl.balance) as balance                        
                        FROM view_ward_mstr                         
                        LEFT JOIN (
                            SELECT tbl_prop_dtl.ward_mstr_id, tbl_prop_dtl.id, demands.balance
                            FROM tbl_prop_dtl
                            left join tbl_saf_dtl on tbl_saf_dtl.previous_holding_id = tbl_prop_dtl.id::text and tbl_saf_dtl.saf_pending_status !=1 and tbl_saf_dtl.status =1
                            JOIN tbl_prop_type_mstr on tbl_prop_type_mstr.id = tbl_prop_dtl.prop_type_mstr_id
                            JOIN demands on demands.prop_dtl_id = tbl_prop_dtl.id
                            $where  and demands.balance>0 and (tbl_saf_dtl.assessment_type not in('Mutation','Mutation with Reassessment') OR tbl_saf_dtl.id is null)
                            ".($fromAmt && $uptoAmt 
                                ? " AND round(demands.balance) BETWEEN $fromAmt AND $uptoAmt " 
                                : (
                                    $fromAmt 
                                    ? " AND round(demands.balance) >= $fromAmt " 
                                    : (
                                        $uptoAmt 
                                        ? "AND round(demands.balance) <= $uptoAmt " 
                                        : ""
                                        )
                                )
                            )."
                        ) tbl_prop_dtl on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                        left join notices on notices.prop_dtl_id = tbl_prop_dtl.id 
                        $where1
                        GROUP BY view_ward_mstr.id, view_ward_mstr.ward_no
                    )            
            ";

            $select = "select ROW_NUMBER() OVER ( $orderBY) as s_no,records.*
            ";
            $select2 = "select count(id)ward_no, sum(total)total,round(sum(balance))balance ";
            $from = "from records                
            ";

            if($this->request->getVar("report_type")=="notice_be_generated"){
                if($this->request->getVar("serialNo") && $this->request->getVar("serialNo")!=""){
                    $where.=" AND notice_serial = ".$this->request->getVar("serialNo");
                }
                if($this->request->getVar("closeStatus")){
                    #open
                    if($this->request->getVar("closeStatus")=="open"){
                        $where.=" AND tbl_prop_notices.status = 1";
                    }
                    elseif($this->request->getVar("closeStatus")=="close"){
                        $where.=" AND tbl_prop_notices.status != 1";
                    }
                }
                if($this->request->getVar("servedStatus")){
                    #open
                    if($this->request->getVar("servedStatus")=="served"){
                        $where.=" AND tbl_prop_notices.notice_served_on is not null ";
                    }
                    elseif($this->request->getVar("servedStatus")=="not_served"){
                        $where.=" AND tbl_prop_notices.notice_served_on is  null";
                    }
                }
                $with="with notices as (
                            select prop_dtl_id,max(id) as notice_id
                            from tbl_prop_notices
                            where status!=0 and fnyear='$fyear' and notice_type='Demand'
                            group by prop_dtl_id
                        ) ,
                        records as (
                            SELECT view_ward_mstr.ward_no, view_ward_mstr.id,
                                count(tbl_prop_dtl.id) as total, 
                                sum( tbl_prop_dtl.demand_amount)balance
                            FROM  view_ward_mstr 
                            LEFT join (
                                SELECT tbl_prop_dtl.id , tbl_prop_dtl.ward_mstr_id,tbl_prop_notices.demand_amount
                                FROM tbl_prop_dtl
                                left join tbl_saf_dtl on tbl_saf_dtl.previous_holding_id = tbl_prop_dtl.id::text and tbl_saf_dtl.saf_pending_status !=1 and tbl_saf_dtl.status =1
                                join tbl_prop_type_mstr on tbl_prop_type_mstr.id = tbl_prop_dtl.prop_type_mstr_id
                                join notices on notices.prop_dtl_id = tbl_prop_dtl.id
                                join tbl_prop_notices on tbl_prop_notices.id = notices.notice_id 
                                $where   AND notices.prop_dtl_id is not null and (tbl_saf_dtl.assessment_type not in('Mutation','Mutation with Reassessment') OR tbl_saf_dtl.id is null)
                                ".($fromAmt && $uptoAmt 
                                    ? " AND round(tbl_prop_dtl.demand_amount) BETWEEN $fromAmt AND $uptoAmt " 
                                    : (
                                        $fromAmt 
                                        ? " AND round(tbl_prop_dtl.demand_amount) >= $fromAmt " 
                                        : (
                                            $uptoAmt 
                                            ? "AND round(tbl_prop_dtl.demand_amount) <= $uptoAmt " 
                                            : ""
                                            )
                                    )
                                )."
                                ".($fromDate && $uptoDate ? " AND ".($this->request->getVar("servedStatus")=="served" ? "tbl_prop_notices.notice_served_on::date between": "tbl_prop_notices.notice_date::date between")." '$fromDate' AND '$uptoDate' " : "")."
                            )tbl_prop_dtl on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id 
                            $where1
                            GROUP BY view_ward_mstr.id, view_ward_mstr.ward_no
                        )  
                                
                ";
                $select2 = "select count(id)ward_no, sum(total)total,sum(balance)balance ";
            }

            if($this->request->getVar("report_type")=="notice_be_closed"){
                $with="with notices as (
                            select prop_dtl_id,max(id) as notice_id
                            from tbl_prop_notices
                            where status=5 and fnyear='$fyear' and notice_type='Demand'
                            group by prop_dtl_id
                        ) ,
                        records as (
                            SELECT view_ward_mstr.ward_no, view_ward_mstr.id,
                                count(tbl_prop_dtl.id) as total, 
                                sum(tbl_prop_dtl.demand_amount)balance,
                                sum( tbl_prop_dtl.payable_amt)payable_amt
                            FROM  view_ward_mstr 
                            LEFT join (
                                SELECT tbl_prop_dtl.id , tbl_prop_dtl.ward_mstr_id,tbl_prop_notices.demand_amount ,tbl_transaction.payable_amt
                                from tbl_prop_dtl
                                left join tbl_saf_dtl on tbl_saf_dtl.previous_holding_id = tbl_prop_dtl.id::text and tbl_saf_dtl.saf_pending_status !=1 and tbl_saf_dtl.status =1
                                join tbl_prop_type_mstr on tbl_prop_type_mstr.id = tbl_prop_dtl.prop_type_mstr_id
                                join view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                                join notices on notices.prop_dtl_id = tbl_prop_dtl.id
                                join tbl_prop_notices on tbl_prop_notices.id = notices.notice_id
                                join tbl_transaction on tbl_transaction.id = tbl_prop_notices.clear_by_id                       
                                $where  AND notices.prop_dtl_id is not null and (tbl_saf_dtl.assessment_type not in('Mutation','Mutation with Reassessment') OR tbl_saf_dtl.id is null)
                                ".($fromAmt && $uptoAmt 
                                    ? " AND round(tbl_prop_dtl.demand_amount) BETWEEN $fromAmt AND $uptoAmt " 
                                    : (
                                        $fromAmt 
                                        ? " AND round(tbl_prop_dtl.demand_amount) >= $fromAmt " 
                                        : (
                                            $uptoAmt 
                                            ? "AND round(tbl_prop_dtl.demand_amount) <= $uptoAmt " 
                                            : ""
                                            )
                                    )
                                )."                                
                                ".($fromDate && $uptoDate ? " AND tbl_transaction.tran_date between '$fromDate' AND '$uptoDate' " : "")." 
                            )tbl_prop_dtl on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id 
                            $where1
                            GROUP BY view_ward_mstr.id, view_ward_mstr.ward_no
                        )         
                ";
                $select2 = "select count(id)ward_no, sum(total)total,sum(balance)balance, sum(payable_amt)payable_amt ";
            }
            if($this->request->getVar("report_type")=="notice_summary"){
                $with="with records as (
                        SELECT view_ward_mstr.ward_no, view_ward_mstr.id,
                            count(tbl_prop_notices.prop_dtl_id) as total, 
                            sum(tbl_prop_notices.demand_amount + tbl_prop_notices.penalty) as balance,
                            count(case when tbl_prop_notices.clear_by_id is not null then tbl_prop_notices.prop_dtl_id end) as collection_from_prop,
                            sum( case when tbl_prop_notices.clear_by_id is not null then (tbl_prop_notices.demand_amount + tbl_prop_notices.penalty) end) as total_collection_demand
                        FROM view_ward_mstr
                        LEFT JOIN(
                            select tbl_prop_notices.prop_dtl_id, tbl_prop_notices.clear_by_id,
                                tbl_prop_notices.demand_amount , tbl_prop_notices.penalty,
                                tbl_prop_dtl.ward_mstr_id
                            from tbl_prop_notices
                            JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_prop_notices.prop_dtl_id
                            left join tbl_saf_dtl on tbl_saf_dtl.previous_holding_id = tbl_prop_dtl.id::text and tbl_saf_dtl.saf_pending_status !=1 and tbl_saf_dtl.status =1
                            $where and (tbl_saf_dtl.assessment_type not in('Mutation','Mutation with Reassessment') OR tbl_saf_dtl.id is null)
                            ".($fromAmt && $uptoAmt 
                                ? " AND round(tbl_prop_dtl.demand_amount) BETWEEN $fromAmt AND $uptoAmt " 
                                : (
                                    $fromAmt 
                                    ? " AND round(tbl_prop_dtl.demand_amount) >= $fromAmt " 
                                    : (
                                        $uptoAmt 
                                        ? "AND round(tbl_prop_dtl.demand_amount) <= $uptoAmt " 
                                        : ""
                                        )
                                )
                            )."
                            AND tbl_prop_notices.status in(1,5) AND tbl_prop_notices.notice_type = 'Demand'
                        ".($fromDate && $uptoDate ? " AND tbl_prop_notices.notice_date between '$fromDate' AND '$uptoDate' " : "")."
                        ) tbl_prop_notices on view_ward_mstr.id = tbl_prop_notices.ward_mstr_id 
                        $where1
                        GROUP BY view_ward_mstr.id, view_ward_mstr.ward_no
                )";
                
                $select2 = "select count(id)ward_no, sum(total)total,sum(balance)balance, sum(collection_from_prop)collection_from_prop, sum(total_collection_demand)total_collection_demand ";
            }
            $totalRecords = $this->model_datatable->getTotalRecords($from, false,$with); 
            $totalSummary = $this->model_datatable->getRecords($with.$select2.$from, false)[0]??[];
            // print_var($this->db->getLastQuery());die;

            if ($totalRecords > 0) {

                ## Total number of records with filtering
                $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($from . $whereQueryWithSearch, false,$with);

                ## Fetch records                
                $fetchSql = $with.$select . $from . $whereQueryWithSearch . $orderBY;
                $fetchSql .= $limit;

                $result = $this->model_datatable->getRecords($fetchSql, false);

                $records = [];
                if ($result) {
                    foreach ($result as $key => $tran_dtl) {
                        $records[] = [
                            's_no' => $tran_dtl['s_no'],
                            'ward_no' => (($ajax??"") ? $tran_dtl['ward_no'] : ("<a href = '#' onClick ='openWindow(".$tran_dtl["id"].")'/>" .$tran_dtl['ward_no'] ."</a>")) ,
                            'total' => $tran_dtl['total'],
                            'balance' => round($tran_dtl['balance'],2),
                            "collection_from_prop" =>$tran_dtl["collection_from_prop"],
                            "total_collection_demand"=>round($tran_dtl["total_collection_demand"],2),    
                        ];
                    }
                }
            }
            $response = array(
                "draw" => 0,
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecordwithFilter??0,
                "data" => $records??[],
                "summary"=>$totalSummary,

            );
            return json_encode($response);
        }
        $ulb_mstr_id = 1;
        $data['wardList'] = $this->model_ward_mstr->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
        $data["propertyTypeList"] = $this->db->query("SELECT id,property_type FROM tbl_prop_type_mstr WHERE status =1 ORDER BY property_type ASC ")->getResultArray();        
        return view("property/ward_wise_bulk_generate_notice",$data);
    }

    public function generateBulkNotice($ajax = false){
        $data = $this->request->getVar();
        $fyear = getFY();
        // $privFyear = getFY(explode("-",$fyear)[0]."-03-31");
        $fromDate = $this->request->getVar("fromDate");
        $uptoDate = $this->request->getVar("uptoDate");
        $fromAmt = $this->request->getVar("from_amt");
        $uptoAmt = $this->request->getVar("upto_amt");
        if ($this->request->getMethod() == "post" || $ajax) {            
            if($this->request->getVar("selecteditems")){
                foreach($this->request->getVar("selecteditems") as $prop_id){ 
                    if($this->request->getVar("nextNotice")){
                        $this->generatedNoticeUpdateSerial($prop_id);
                    }else{
                        $this->Notice($prop_id);
                    }                  
                }
                return true;
            }
            $where = " where tbl_prop_dtl.status = 1 ";
            if ($this->request->getVar("ward_id")) {
                $where .= " AND tbl_prop_dtl.ward_mstr_id = " . $this->request->getVar("ward_id");
            }
            if ($this->request->getVar("property_type_id")) {
                $where .= " AND tbl_prop_dtl.prop_type_mstr_id = " . $this->request->getVar("property_type_id");
            }

            
            $start = sanitizeString($this->request->getVar('start'));
            $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

            $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
            $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
            if ($columnName == "ward_no")
                $columnName = 'tbl_prop_dtl.ward_mstr_id';
            else
                $columnName = 'tbl_prop_dtl.id';

            $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']);
            $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
            $limit = " LIMIT " . ($rowperpage == -1 ? "ALL" : $rowperpage) . " OFFSET " . $start;


            $searchValue = sanitizeString($this->request->getVar('search')['value']);
            $whereQueryWithSearch = "";
            if ($searchValue != '') {
                $whereQueryWithSearch = " AND (
                    tbl_prop_dtl.new_holding_no ILIKE '%" . $searchValue . "%'
                    OR tbl_prop_dtl.holding_no ILIKE '%" . $searchValue . "%'
                    OR tbl_prop_dtl.prop_address ILIKE '%" . $searchValue . "%'
                    OR owneres.owner_name ILIKE '%" . $searchValue . "%'
                    OR owneres.mobile_no ILIKE '%" . $searchValue . "%'
                    
                    )
                ";
            }

            $with ="with demands as(
                        SELECT 
                            prop_dtl_id,
                            SUM(balance) AS balance,
                            --SUM(additional_amount) AS additional_amount,
                            --SUM(adjust_amt) AS adjust_amt,
                            --max(due_date) AS due_date,
                            MAX(fyear) upto_fyear,
                            (
                                SELECT MAX(qtr)
                                FROM tbl_prop_demand
                                WHERE fyear = (
                                    SELECT MAX(fyear)
                                    FROM tbl_prop_demand
                                    where tbl_prop_demand.prop_dtl_id = prop_dtl_id AND status=1 AND paid_status=0
                                ) and tbl_prop_demand.prop_dtl_id = prop_dtl_id
                                GROUP BY fyear
                            ) as upto_qtr,
                            MIN(fyear) from_fyear,
                            (
                                SELECT MIN(qtr)
                                FROM tbl_prop_demand
                                WHERE fyear = (
                                    SELECT MIN(fyear)
                                    FROM tbl_prop_demand
                                    where tbl_prop_demand.prop_dtl_id = prop_dtl_id AND status=1 AND paid_status=0
                                ) and tbl_prop_demand.prop_dtl_id = prop_dtl_id
                                GROUP BY fyear
                            ) as from_qtr
                            
                        FROM tbl_prop_demand
                        join(
                            select distinct(prop_dtl_id) as temp_prop_id
                            from tbl_prop_demand
                            WHERE status=1 AND paid_status=0 and fyear < '$fyear' and balance>0
                        )arrear on arrear.temp_prop_id = tbl_prop_demand.prop_dtl_id
                        WHERE status=1 AND paid_status=0 
                        GROUP BY prop_dtl_id
                    ) ,
                    owneres as (
                        select  tbl_prop_owner_detail.prop_dtl_id,
                            string_agg(tbl_prop_owner_detail.owner_name,',')owner_name,
                            string_agg(tbl_prop_owner_detail.mobile_no::text,',')mobile_no
                        from tbl_prop_owner_detail
                        join demands on demands.prop_dtl_id = tbl_prop_owner_detail.prop_dtl_id
                        where status = 1 
                        GROUP BY tbl_prop_owner_detail.prop_dtl_id
                    ),
                    tbl_saf_dtl as(
                        select *
                        from tbl_saf_dtl
                        join(
                            select max(tbl_saf_dtl.id) as last_saf
                            from tbl_saf_dtl
                            join demands on demands.prop_dtl_id::text = tbl_saf_dtl.previous_holding_id
                            where tbl_saf_dtl.status=1 and tbl_saf_dtl.saf_pending_status !=1
                            group by previous_holding_id
                        ) as last_saf on last_saf.last_saf = tbl_saf_dtl.id
                        where tbl_saf_dtl.saf_pending_status !=1
                    ),
                    notices as (
                        select prop_dtl_id
                        from tbl_prop_notices
                        where status!=0 and fnyear='$fyear' and notice_type='Demand'
                        group by prop_dtl_id
                    )            
            ";

            $select = "select ROW_NUMBER() OVER ( $orderBY) as s_no,view_ward_mstr.ward_no,tbl_prop_type_mstr.property_type,
                            tbl_prop_dtl.id,case when trim(tbl_prop_dtl.new_holding_no)='' then tbl_prop_dtl.holding_no else tbl_prop_dtl.new_holding_no end as holding_no,
                            tbl_prop_dtl.prop_address,
                            demands.*,
                            owneres.owner_name, owneres.mobile_no
            ";
            $from = "from tbl_prop_dtl
                    left join tbl_saf_dtl on tbl_saf_dtl.previous_holding_id = tbl_prop_dtl.id::text and tbl_saf_dtl.saf_pending_status !=1 and tbl_saf_dtl.status =1
                    join tbl_prop_type_mstr on tbl_prop_type_mstr.id = tbl_prop_dtl.prop_type_mstr_id
                    join view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                    join demands on demands.prop_dtl_id = tbl_prop_dtl.id
                    left join owneres on owneres.prop_dtl_id = tbl_prop_dtl.id
                    left join notices on notices.prop_dtl_id = tbl_prop_dtl.id                        
                    $where  and demands.balance>0 and (tbl_saf_dtl.assessment_type not in('Mutation','Mutation with Reassessment') OR tbl_saf_dtl.id is null)  
                    ".($fromAmt && $uptoAmt 
                        ? " AND round(demands.balance) BETWEEN $fromAmt AND $uptoAmt " 
                        : (
                            $fromAmt 
                            ? " AND round(demands.balance) >= $fromAmt " 
                            : (
                                $uptoAmt 
                                ? "AND round(demands.balance) <= $uptoAmt " 
                                : ""
                            )
                        )
                    )."            
            ";
            if($this->request->getVar("report_type")!="defaulter_list"){
                $from.=" AND notices.prop_dtl_id is null ";
            }

            if($this->request->getVar("report_type")=="notice_be_generated"){
                if($this->request->getVar("serialNo") && $this->request->getVar("serialNo")!=""){
                    $where.=" AND notice_serial = ".$this->request->getVar("serialNo");
                }
                if($this->request->getVar("closeStatus")){
                    #open
                    if($this->request->getVar("closeStatus")=="open"){
                        $where.=" AND tbl_prop_notices.status = 1";
                    }
                    elseif($this->request->getVar("closeStatus")=="close"){
                        $where.=" AND tbl_prop_notices.status != 1";
                    }
                }
                if($this->request->getVar("servedStatus")){
                    #open
                    if($this->request->getVar("servedStatus")=="served"){
                        $where.=" AND tbl_prop_notices.notice_served_on is not null ";
                    }
                    elseif($this->request->getVar("servedStatus")=="not_served"){
                        $where.=" AND tbl_prop_notices.notice_served_on is  null";
                    }
                }
                $with="with notices as (
                            select prop_dtl_id,max(id) as notice_id
                            from tbl_prop_notices
                            where status!=0 and fnyear='$fyear' and notice_type='Demand'
                            group by prop_dtl_id
                        ) ,
                        owneres as (
                            select  tbl_prop_owner_detail.prop_dtl_id,
                                string_agg(tbl_prop_owner_detail.owner_name,',')owner_name,
                                string_agg(tbl_prop_owner_detail.mobile_no::text,',')mobile_no
                            from tbl_prop_owner_detail
                            join notices on notices.prop_dtl_id = tbl_prop_owner_detail.prop_dtl_id
                            where status = 1 
                            GROUP BY tbl_prop_owner_detail.prop_dtl_id
                        )          
                ";
                $select="select ROW_NUMBER() OVER ( $orderBY) as s_no,view_ward_mstr.ward_no,tbl_prop_type_mstr.property_type,
                            tbl_prop_notices.*,
                            tbl_prop_dtl.id,case when trim(tbl_prop_dtl.new_holding_no)='' then tbl_prop_dtl.holding_no else tbl_prop_dtl.new_holding_no end as holding_no,
                            tbl_prop_dtl.prop_address,                            
                            owneres.owner_name, owneres.mobile_no,
                            notices.notice_id
                        ";
                $from = "from tbl_prop_dtl
                        left join tbl_saf_dtl on tbl_saf_dtl.previous_holding_id = tbl_prop_dtl.id::text and tbl_saf_dtl.saf_pending_status !=1 and tbl_saf_dtl.status =1
                        join tbl_prop_type_mstr on tbl_prop_type_mstr.id = tbl_prop_dtl.prop_type_mstr_id
                        join view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                        join notices on notices.prop_dtl_id = tbl_prop_dtl.id
                        join tbl_prop_notices on tbl_prop_notices.id = notices.notice_id
                        left join owneres on owneres.prop_dtl_id = tbl_prop_dtl.id                                                 
                        $where   AND notices.prop_dtl_id is not null 
                        and (tbl_saf_dtl.assessment_type not in('Mutation','Mutation with Reassessment') OR tbl_saf_dtl.id is null)
                        ".($fromAmt && $uptoAmt 
                            ? " AND round(tbl_prop_dtl.demand_amount) BETWEEN $fromAmt AND $uptoAmt " 
                            : (
                                $fromAmt 
                                ? " AND round(tbl_prop_dtl.demand_amount) >= $fromAmt " 
                                : (
                                    $uptoAmt 
                                    ? "AND round(tbl_prop_dtl.demand_amount) <= $uptoAmt " 
                                    : ""
                                    )
                            )
                        )."  
                        ".($fromDate && $uptoDate ? " AND ".($this->request->getVar("servedStatus")=="served" ? "tbl_prop_notices.notice_served_on::date between": "tbl_prop_notices.notice_date::date between")." '$fromDate' AND '$uptoDate' " : "")."             
                ";
            }

            if($this->request->getVar("report_type")=="notice_be_closed"){
                $with="with notices as (
                            select prop_dtl_id,max(id) as notice_id
                            from tbl_prop_notices
                            where status=5 and fnyear='$fyear' and notice_type='Demand'
                            group by prop_dtl_id
                        ) ,
                        owneres as (
                            select  tbl_prop_owner_detail.prop_dtl_id,
                                string_agg(tbl_prop_owner_detail.owner_name,',')owner_name,
                                string_agg(tbl_prop_owner_detail.mobile_no::text,',')mobile_no
                            from tbl_prop_owner_detail
                            join notices on notices.prop_dtl_id = tbl_prop_owner_detail.prop_dtl_id
                            where status = 1 
                            GROUP BY tbl_prop_owner_detail.prop_dtl_id
                        )          
                ";
                $select="select ROW_NUMBER() OVER ( $orderBY) as s_no,view_ward_mstr.ward_no,tbl_prop_type_mstr.property_type,
                            tbl_prop_notices.*,
                            tbl_prop_dtl.id,case when trim(tbl_prop_dtl.new_holding_no)='' then tbl_prop_dtl.holding_no else tbl_prop_dtl.new_holding_no end as holding_no,
                            tbl_prop_dtl.prop_address,                            
                            owneres.owner_name, owneres.mobile_no,
                            notices.notice_id,
                            tbl_transaction.tran_no,tran_date,tran_mode,payable_amt
                        ";
                $from = "from tbl_prop_dtl
                        left join tbl_saf_dtl on tbl_saf_dtl.previous_holding_id = tbl_prop_dtl.id::text and tbl_saf_dtl.saf_pending_status !=1 and tbl_saf_dtl.status =1
                        join tbl_prop_type_mstr on tbl_prop_type_mstr.id = tbl_prop_dtl.prop_type_mstr_id
                        join view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                        join notices on notices.prop_dtl_id = tbl_prop_dtl.id
                        join tbl_prop_notices on tbl_prop_notices.id = notices.notice_id
                        join tbl_transaction on tbl_transaction.id = tbl_prop_notices.clear_by_id
                        left join owneres on owneres.prop_dtl_id = tbl_prop_dtl.id                            
                        $where  AND notices.prop_dtl_id is not null 
                            and (tbl_saf_dtl.assessment_type not in('Mutation','Mutation with Reassessment') OR tbl_saf_dtl.id is null)
                        ".($fromAmt && $uptoAmt 
                            ? " AND round(tbl_prop_dtl.demand_amount) BETWEEN $fromAmt AND $uptoAmt " 
                            : (
                                $fromAmt 
                                ? " AND round(tbl_prop_dtl.demand_amount) >= $fromAmt " 
                                : (
                                    $uptoAmt 
                                    ? "AND round(tbl_prop_dtl.demand_amount) <= $uptoAmt " 
                                    : ""
                                    )
                            )
                        )."
                        ".($fromDate && $uptoDate ? " AND tbl_transaction.tran_date between '$fromDate' AND '$uptoDate' " : "")."               
                ";
            }
            if($this->request->getVar("report_type")=="notice_summary"){
                $with = "";
                $orderBY ="";
                $select = "SELECT count(tbl_prop_notices.prop_dtl_id) as total_property, sum(tbl_prop_notices.demand_amount + tbl_prop_notices.penalty) as total_demand,
                                count(case when tbl_prop_notices.clear_by_id is not null then tbl_prop_notices.prop_dtl_id end) as collection_from_prop,
                                sum( case when tbl_prop_notices.clear_by_id is not null then (tbl_prop_notices.demand_amount + tbl_prop_notices.penalty) end) as total_collection_demand                            
                ";
                $from = " FROM tbl_prop_notices 
                        JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_prop_notices.prop_dtl_id
                        left join tbl_saf_dtl on tbl_saf_dtl.previous_holding_id = tbl_prop_dtl.id::text and tbl_saf_dtl.saf_pending_status !=1 and tbl_saf_dtl.status =1
                        $where 
                        and (tbl_saf_dtl.assessment_type not in('Mutation','Mutation with Reassessment') OR tbl_saf_dtl.id is null)
                        ".($fromAmt && $uptoAmt 
                                ? " AND round(tbl_prop_dtl.demand_amount) BETWEEN $fromAmt AND $uptoAmt " 
                                : (
                                    $fromAmt 
                                    ? " AND round(tbl_prop_dtl.demand_amount) >= $fromAmt " 
                                    : (
                                        $uptoAmt 
                                        ? "AND round(tbl_prop_dtl.demand_amount) <= $uptoAmt " 
                                        : ""
                                        )
                                )
                            )."
                            AND tbl_prop_notices.status in(1,5) AND tbl_prop_notices.notice_type = 'Demand'
                        ".($fromDate && $uptoDate ? " AND tbl_prop_notices.notice_date between '$fromDate' AND '$uptoDate' " : "")."
                            
                ";


                
            }
            // print_var( $with.$select . $from . $whereQueryWithSearch . $orderBY);die;
            $totalRecords = $this->model_datatable->getTotalRecords($from, false,$with);

            if ($totalRecords > 0) {

                ## Total number of records with filtering
                $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($from . $whereQueryWithSearch, false,$with);

                ## Fetch records                
                $fetchSql = $with.$select . $from . $whereQueryWithSearch . $orderBY;
                if (!$ajax) {
                    $fetchSql .= $limit;
                }
                $result = $this->model_datatable->getRecords($fetchSql, false);

                $records = [];
                if ($result) {
                    if($this->request->getVar("report_type")=="notice_be_closed"){
                        foreach ($result as $key => $tran_dtl) {
                            $records[] = [
                                's_no' => $tran_dtl['s_no'],
                                'ward_no' => $tran_dtl['ward_no'],
                                "notice_no"=>$tran_dtl['notice_no'],
                                'holding_no' => $tran_dtl['holding_no'],
                                "property_type" => $tran_dtl['property_type'],
                                'prop_address' => $tran_dtl["prop_address"],
                                'owner_name' => $tran_dtl['owner_name'],
                                'mobile_no' => $tran_dtl['mobile_no'],
                                'from' => ($tran_dtl['from_qtr']."/".$tran_dtl['from_fyear']),
                                'upto' => ($tran_dtl['upto_qtr']."/".$tran_dtl['upto_fyear']),
                                'balance' => ($tran_dtl['demand_amount'] + $tran_dtl["penalty"]),
                                "notice_date"=> ($tran_dtl['notice_date']),
                                'paid_amount' => ($tran_dtl['payable_amt']),
                                'tran_date' => ($tran_dtl['tran_date']),
                                'tran_no' => ($tran_dtl['tran_no']),
                                'payment_mode' => ($tran_dtl['tran_mode']),
                                "link" => $ajax ? "" : ("<a class ='btn btn-info btn-sm' target='blanck' href = '" . base_url("jsk/payment_jsk_receipt/" . md5($tran_dtl["clear_by_id"])) . "'>view</a>"),

                            ];
                        }
                    }
                    elseif($this->request->getVar("report_type")=="notice_be_generated"){
                        foreach ($result as $key => $tran_dtl) {
                            $s = 1;
                            $srial="";
                            while($s <= $tran_dtl["notice_serial"]) {
                                $srial.="<span style ='pading : 1 px;'>  <img src='".base_url('public/assets/img/correct.png')."' style='height: 25px' /></span>";        
                                $s++;                        
                            }                           
                            $records[] = [
                                's_no' => $tran_dtl['s_no'].(($ajax || $tran_dtl['status']==5) ? "" : ("<input type='checkbox' name='noticeCheck[]' value ='".$tran_dtl["notice_id"]."' />")),
                                'ward_no' => $tran_dtl['ward_no'],
                                "notice_no"=>$tran_dtl['notice_no'],
                                'holding_no' => $tran_dtl['holding_no'],
                                "property_type" => $tran_dtl['property_type'],
                                'prop_address' => $tran_dtl["prop_address"],
                                'owner_name' => $tran_dtl['owner_name'],
                                'mobile_no' => $tran_dtl['mobile_no'],
                                'from' => ($tran_dtl['from_qtr']."/".$tran_dtl['from_fyear']),
                                'upto' => ($tran_dtl['upto_qtr']."/".$tran_dtl['upto_fyear']),
                                'balance' => ($tran_dtl['demand_amount'] + $tran_dtl["penalty"]),
                                "notice_date"=> ($tran_dtl['notice_date']),
                                "notice_serial"=> $ajax ? $tran_dtl["notice_serial"] : ($srial),
                                "is_close"=>  $tran_dtl["status"]==1 ?"OPEN" : "CLOSE",
                                "is_served"=>  $tran_dtl["notice_served_on"] ?"YES" : "NO",
                                "link" => $ajax ? "" : ("<a class ='btn btn-info btn-sm' target='blanck' href = '" . base_url("propDtl/GeneratedNotice/" . md5($tran_dtl["notice_id"])) . "'>view</a>"),

                            ];
                        }
                    }
                    elseif($this->request->getVar("report_type")=="notice_summary"){
                        foreach ($result as $key => $tran_dtl) {
                            $records[] = [
                                's_no' => $tran_dtl['s_no'],
                                'total_property' => $tran_dtl['total_property'],
                                'total_demand' => $tran_dtl['total_demand'],
                                "collection_from_prop" => $tran_dtl['collection_from_prop'],
                                'total_collection_demand' => $tran_dtl["total_collection_demand"],

                            ];
                        }
                    }
                    else{
                        foreach ($result as $key => $tran_dtl) {
                            $records[] = [
                                's_no' => $tran_dtl['s_no'].(($ajax || $this->request->getVar("report_type")=="defaulter_list") ? "" : ("<input type='checkbox' name='check[]' value ='".$tran_dtl["id"]."' />")),
                                'ward_no' => $tran_dtl['ward_no'],
                                'holding_no' => $tran_dtl['holding_no'],
                                "property_type" => $tran_dtl['property_type'],
                                'prop_address' => $tran_dtl["prop_address"],
                                'owner_name' => $tran_dtl['owner_name'],
                                'mobile_no' => $tran_dtl['mobile_no'],
                                'from' => ($tran_dtl['from_qtr']."/".$tran_dtl['from_fyear']),
                                'upto' => ($tran_dtl['upto_qtr']."/".$tran_dtl['upto_fyear']),
                                'balance' => ($tran_dtl['balance']),
                                "link" => $ajax ? "" : ("<a class ='btn btn-info btn-sm' target='blanck' href = '" . base_url("propDtl/full/" . $tran_dtl["id"]) . "'>view</a>"),

                            ];
                        }
                    }
                }
            }
            if ($ajax) {
                phpOfficeLoad();
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $activeSheet = $spreadsheet->getActiveSheet();

                if($this->request->getVar("report_type")=="notice_be_closed"){

                    $activeSheet->setCellValue('A1', 'Sl No.');
                    $activeSheet->setCellValue('B1', 'Ward No');                                       
                    $activeSheet->setCellValue('C1', 'Notice No');
                    $activeSheet->setCellValue('D1', 'Hoalding No');
                    $activeSheet->setCellValue('E1', 'Property Type');
                    $activeSheet->setCellValue('F1', 'Address');
                    $activeSheet->setCellValue('G1', 'Owner Name');
                    $activeSheet->setCellValue('H1', 'Mobile No.');
                    $activeSheet->setCellValue('I1', 'From');
                    $activeSheet->setCellValue('J1', 'Upto');
                    $activeSheet->setCellValue('K1', 'Demand Amount');
                    $activeSheet->setCellValue('L1', 'Notice Date');
                    $activeSheet->setCellValue('M1', 'Paid Amount');
                    $activeSheet->setCellValue('N1', 'Tran Date');
                    $activeSheet->setCellValue('O1', 'Tran No.');
                    $activeSheet->setCellValue('P1', 'Payment Mode');

                    $filename = "Notice Close Genrated" . date('Ymd-His') . ".xlsx";
                }
                elseif($this->request->getVar("report_type")=="notice_be_generated"){                    

                    $activeSheet->setCellValue('A1', 'Sl No.');
                    $activeSheet->setCellValue('B1', 'Ward No');                    
                    $activeSheet->setCellValue('C1', 'Notice No');
                    $activeSheet->setCellValue('D1', 'Hoalding No');
                    $activeSheet->setCellValue('E1', 'Property Type');
                    $activeSheet->setCellValue('F1', 'Address');
                    $activeSheet->setCellValue('G1', 'Owner Name');
                    $activeSheet->setCellValue('H1', 'Mobile No.');
                    $activeSheet->setCellValue('I1', 'From');
                    $activeSheet->setCellValue('J1', 'Upto');
                    $activeSheet->setCellValue('K1', 'Demand Amount');
                    $activeSheet->setCellValue('L1', 'Notice Date');
                    $activeSheet->setCellValue('M1', 'Notice Serial');
                    $activeSheet->setCellValue('N1', 'Notice Status');
                    $activeSheet->setCellValue('O1', 'IS Notice Served');

                    $filename = "Notice Genrated" . date('Ymd-His') . ".xlsx";
                }
                else{
                    $activeSheet->setCellValue('A1', 'Sl No.');
                    $activeSheet->setCellValue('B1', 'Ward No');
                    $activeSheet->setCellValue('C1', 'Hoalding No');
                    $activeSheet->setCellValue('D1', 'Property Type');
                    $activeSheet->setCellValue('E1', 'Address');
                    $activeSheet->setCellValue('F1', 'Owner Name');
                    $activeSheet->setCellValue('G1', 'Mobile No.');
                    $activeSheet->setCellValue('H1', 'From');
                    $activeSheet->setCellValue('I1', 'Upto');
                    $activeSheet->setCellValue('J1', 'Demand Amount');

                    $filename = "Notice To Be Genrated" . date('Ymd-His') . ".xlsx";
                    if($this->request->getVar("report_type")=="defaulter_list"){
                        $filename = "Defaulter List" . date('Ymd-His') . ".xlsx";
                    }
                }
                

                $activeSheet->fromArray($records, NULL, 'A3');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');

            } else {
                $response = array(
                    "draw" => 0,
                    "recordsTotal" => $totalRecords,
                    "recordsFiltered" => $totalRecordwithFilter??0,
                    "data" => $records??[],
                    // "summary"=>$totalSummary,

                );
                return json_encode($response);
            }
        }
        $ulb_mstr_id = 1;
        $data['wardList'] = $this->model_ward_mstr->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
        $data["propertyTypeList"] = $this->db->query("SELECT id,property_type FROM tbl_prop_type_mstr WHERE status =1 ORDER BY property_type ASC ")->getResultArray();        
        return view("property/bulk_generate_notice",$data);
    }

    public function generatedNoticeUpdateSerial($noticeId){        
        $data = $this->request->getVar();     
        $emp_details_id = $_SESSION['emp_details']['id'];   
        $noticeDtl = $this->model_notice->getNoticeById($noticeId);
        if($this->request->getMethod()=="post"){
            $lastId = null;
            $sql = "UPDATE tbl_prop_notices SET notice_serial = notice_serial+1 WHERE id = ".$noticeDtl["id"];
            $this->db->transBegin();
            if($this->db->query($sql)){
                $inputs=[
                    "notice_id" => $noticeDtl["id"],
                    "notice_date" => $data["notice_date"]??date("Y-m-d"),
                    "serial_no" => $noticeDtl["notice_serial"]+1,
                    "generated_by_emp_details_id" => $emp_details_id,
                    "print_status" => 1,
                ];
                $lastId = $this->ModelPropNoticeSerial->insertNoticeSerialData($inputs);
            }
            if ($this->db->transStatus() === FALSE) {
                $this->db->transRollback();
            } else {
                $this->db->transCommit();
            }
            if($_POST["ajaxCall"]??false){
                return $lastId;
            }
            flashToast('message',$noticeDtl["notice_serial"]+1 .' Notice Generated');
            return $this->response->redirect(base_url('propDtl/generatedNoticeUpdateSerial/'.$noticeId));
        }
        dd("view");
    }

    public function printBulkNotice(){
        $data = $this->request->getVar();
        if($this->request->getMethod()=="post")
        {
            $fromDate = $this->request->getVar("search_from_date");
            $uptoDate = $this->request->getVar("search_upto_date");
            $wardId = $this->request->getVar("search_ward_mstr_id");
            $propertyTypeId = $this->request->getVar("property_type_id");

            $noticSql = "SELECT tbl_prop_notices.* 
                        FROM tbl_prop_notices 
                        JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_prop_notices.prop_dtl_id
                        WHERE tbl_prop_notices.status = 1 AND tbl_prop_notices.notice_type = 'Demand' 
                            AND tbl_prop_notices.notice_date BETWEEN '$fromDate' AND '$uptoDate' 
                            ".($propertyTypeId ? " AND tbl_prop_dtl.prop_type_mstr_id =$propertyTypeId ":"")."
                            ".($wardId ? " AND (tbl_prop_dtl.ward_mstr_id='".$wardId."' OR tbl_prop_dtl.new_ward_mstr_id='".$wardId."')" : "")."";
            $noticList = $this->db->query($noticSql)->getResultArray();
            foreach($noticList as $key=>$val){
                $ulb_mstr_dtl = getUlbDtl();
                $sql = "SELECT
                    tbl_prop_dtl.id,
                    view_ward_mstr.ward_no,
                    new_ward.ward_no AS new_ward_no,
                    tbl_prop_dtl.holding_no,
                    tbl_prop_dtl.new_holding_no,
                    tbl_prop_dtl.prop_address,
                    owner_dtl.owner_name,
                    owner_dtl.mobile_no,
                    owner_dtl.guardian_name
                FROM tbl_prop_dtl
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                left JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_prop_dtl.new_ward_mstr_id
                INNER JOIN (
                    SELECT
                        prop_dtl_id,
                        STRING_AGG(owner_name, ',') AS owner_name,
                        STRING_AGG(mobile_no::TEXT, ',') AS mobile_no,
                        STRING_AGG(guardian_name::TEXT, ',') AS guardian_name
                    FROM tbl_prop_owner_detail
                    GROUP BY prop_dtl_id
                ) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id           
                WHERE 
                    tbl_prop_dtl.id='".$val["prop_dtl_id"]."'";
                $result = $this->db->query($sql)->getFirstRow("array");
                $val["noticePenalty"] = round((float)(($val["demand_amount"] + $val["penalty"]) * 0.01), 2) ;

                $sign = "dmcsign.png";
                $degignation = "अपर प्रशासक";
                if($val["created_on"]<'2024-09-28'){
                    $sign = "dmcsign.png";
                }
                if($val["created_on"]<'2024-02-15'){
                    $sign = "rajnishkumar_sign.png";
                }
                if($val["approved_by"]=='1661'){
                    $degignation = "उप प्रशासक";
                }
                if($val["approved_by"]=='1719'){
                    $degignation = "अपर प्रशासक";
                }
                $val["degignation"]=$degignation;
                $val["signature_path"]=base_url('/public/assets/img/'.$sign);
                if($val["approved_by"]){
                    $empDtl = $this->dbSystem->query("select * from tbl_emp_details where id=".$val["approved_by"])->getFirstRow("array");
                    $val["signature_path"]= $empDtl && $empDtl["signature_path"]? base_url("/getImageLink.php?path=/emp_signature/".$empDtl["signature_path"]):$val["signature_path"] ;
                }
                
                $data["noticeList"][$key]=[
                    "ulb"=>$ulb_mstr_dtl,
                    "notice"=>$val,
                    "property"=>$result
                ];
            }
        }
        $ulb_mstr_id = 1;
        $data['wardList'] = $this->model_ward_mstr->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
        $data["propertyTypeList"] = $this->db->query("SELECT id,property_type FROM tbl_prop_type_mstr WHERE status =1 ORDER BY property_type ASC ")->getResultArray();
        return view("property/printBulkNotice",$data);
    }

    public function noticeServeList(){
        $data = arrFilterSanitizeString($this->request->getVar());
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereDateRange = "";
        $whereWard = " AND tbl_prop_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";

        $whereSearchPrm = "";
        if (isset($data["from_date"]) && isset($data["upto_date"]) && isset($data["ward_mstr_id"]) && isset($data["search_param"])) {
            if ($data["from_date"]!="" && $data["upto_date"]!="") {
                $whereDateRange = " AND tbl_prop_notices.notice_date BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
            }
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_prop_dtl.ward_mstr_id IN (".$data["ward_mstr_id"].")";
            }
            if ($data["search_param"]!="") {
                $NoList = explode("NOTICE/",$data["search_param"]);
                $noticeNo = $NoList[1]??$data["search_param"];
                $whereSearchPrm = " AND (tbl_prop_dtl.holding_no ILIKE '".$data["search_param"]."'
                                        OR tbl_prop_dtl.new_holding_no ILIKE '".$data["search_param"]."'
                                        OR tbl_prop_notices.notice_no ILIKE '%".$noticeNo."%'
                                        OR owner_dtl.owner_name ILIKE '%".$data["search_param"]."%'
                                        OR owner_dtl.mobile_no ILIKE '%".$data["search_param"]."%')";
            }
        }

        $sql = "select tbl_prop_notices.*, concat('NOTICE/',tbl_prop_notices.notice_no)notice_no ,
                    CASE WHEN trim(tbl_prop_dtl.new_holding_no)='' THEN tbl_prop_dtl.holding_no ELSE tbl_prop_dtl.new_holding_no END AS holding_no , 
                    tbl_prop_dtl.ward_mstr_id,view_ward_mstr.ward_no,
                    owner_dtl.owner_name, owner_dtl.mobile_no,tbl_prop_dtl.prop_address
                from tbl_prop_notices 
                join tbl_prop_dtl on tbl_prop_dtl.id = tbl_prop_notices.prop_dtl_id 
                    and tbl_prop_notices.notice_type = 'Demand'
                join view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                LEFT JOIN ( SELECT tbl_prop_owner_detail.prop_dtl_id,
                        string_agg(tbl_prop_owner_detail.owner_name::text, ', '::text) AS owner_name,
                        string_agg(tbl_prop_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                    FROM tbl_prop_owner_detail
                    GROUP BY tbl_prop_owner_detail.prop_dtl_id
                ) owner_dtl ON owner_dtl.prop_dtl_id = tbl_prop_notices.prop_dtl_id 
                where tbl_prop_notices.notice_served_on is null and tbl_prop_notices.status = 1
                $whereWard
                $whereSearchPrm
                $whereDateRange
        ";

        $result = $this->model_datatable->getDatatable($sql);
        $data['posts'] = $result['result'];
        $data['leveldetails'] = $data['posts'];
        $data['pager'] = $result['count'];
        return view('mobile/Property/property/noticeServeList', $data);
    }

    public function noticeDtl($noticeId){
        $data = $this->request->getVar();
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        
        $ulb_mstr_dtl = getUlbDtl();
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($ulb_mstr_dtl["ulb_mstr_id"]);
        $ulb_city_nm=$data['ulb_dtl']['city'];
        $noticeDtl = $this->model_notice->getNoticeById($noticeId);
        if(!$noticeDtl || $noticeDtl["notice_type"]!="Demand"){
            return redirect()->back()->with('error',"");;
        }
        if($this->request->getMethod()=="post"){
            $recieving=$this->request->getFile('notice_recieving');
            $recieving_path = null;
            
            if(isset($recieving)){
                if($recieving->IsValid() && !$recieving->hasMoved()){
                    $newFileName = md5($noticeDtl["id"]);
                    $file_ext = $recieving->getExtension();
                    
                    $path = $ulb_city_nm."/"."prop/notice_recieving";
                    
                    if($recieving->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){                            
                        $recieving_path = $path."/".$newFileName.'.'.$file_ext;
                    }
                }
            }
            $updateSql = "update tbl_prop_notices set notice_served_on = current_date , notice_served_by = $emp_details_id , notice_receiving_remarks =  '".$data["remarks"]."'".($recieving_path ?",notice_receiving = '$recieving_path' " :"")." where id =  ".$noticeDtl["id"];
            $this->db->query($updateSql);
            return $this->response->redirect(base_url('propDtl/noticeServeList'));
        }
        $sql = "SELECT
                    tbl_prop_dtl.id,
                    view_ward_mstr.ward_no,
                    new_ward.ward_no AS new_ward_no,
                    tbl_prop_dtl.holding_no,
                    tbl_prop_dtl.new_holding_no,
                    tbl_prop_dtl.prop_address,
                    owner_dtl.owner_name,
                    owner_dtl.mobile_no,
                    owner_dtl.email,
                    owner_dtl.guardian_name
                FROM tbl_prop_dtl
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                left JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_prop_dtl.new_ward_mstr_id
                INNER JOIN (
                    SELECT
                        prop_dtl_id,
                        STRING_AGG(owner_name, ',') AS owner_name,
                        STRING_AGG(mobile_no::TEXT, ',') AS mobile_no,
                        STRING_AGG(email::TEXT, ',') AS email,
                        STRING_AGG(guardian_name::TEXT, ',') AS guardian_name
                    FROM tbl_prop_owner_detail
                    GROUP BY prop_dtl_id
                ) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id           
                WHERE 
                    tbl_prop_dtl.id='".$noticeDtl["prop_dtl_id"]."'
        ";
        $result = $this->db->query($sql)->getFirstRow("array");

        $data['ulb']=$ulb_mstr_dtl;
        $data['notice']=$noticeDtl;
        $data['property']=$result;
        return view("mobile/Property/property/noticeServed",$data);
    }

}