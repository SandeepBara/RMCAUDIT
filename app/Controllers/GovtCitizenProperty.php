<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\SAF\SAFHelper;
use App\Models\model_ulb_mstr;
use App\Models\model_ward_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_tax;
use App\Models\model_prop_demand;
use App\Models\model_emp_details;
use App\Models\model_prop_floor_details;
use App\Models\model_transaction;
use App\Models\model_tran_mode_mstr;
use App\Models\model_payment_adjustment;
use App\Models\model_fy_mstr;
use App\Models\model_cheque_details;
use App\Models\model_collection;
use App\Models\model_saf_dtl;
use App\Models\model_tc_call;
use App\Models\model_bank_recancilation;
use App\Models\model_transaction_fine_rebet_details;
use App\Models\model_saf_memo_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_view_saf_dtl;
use App\Models\model_saf_tax;
use App\Models\model_system_name;
use App\Models\model_road_type_mstr;
use App\Models\PropertyTypeModel;
use App\Models\model_floor_mstr;
use App\Models\model_usage_type_mstr;
use App\Models\model_usage_type_dtl;
use App\Models\model_occupancy_type_mstr;
use App\Models\model_const_type_mstr;
use Exception;

class GovtCitizenProperty extends AlphaController
{
	protected $db;
	protected $dbSystem;
	/* protected $model_ulb_mstr; */
	protected $model_ward_mstr;
	protected $model_prop_dtl;
	protected $model_prop_owner_detail;
	protected $model_prop_tax;
	protected $model_prop_demand;
	protected $model_prop_floor_details;
	protected $model_emp_details;
	protected $model_transaction;
	protected $model_tran_mode_mstr;
	protected $model_payment_adjustment;
	/* protected $model_fy_mstr; */
	protected $model_cheque_details;
	protected $model_collection;
	protected $model_saf_dtl;
	/* protected $model_tc_call; */
	protected $model_bank_recancilation;
	protected $model_transaction_fine_rebet_details;
	protected $model_saf_memo_dtl;
	protected $model_saf_owner_detail;
	protected $model_view_saf_dtl;
	protected $model_saf_tax;
	/* protected $model_system_name; */
	protected $model_road_type_mstr;
    protected $PropertyTypeModel;
    protected $model_floor_mstr;
	protected $model_usage_type_mstr;
	protected $model_usage_type_dtl;
	protected $model_occupancy_type_mstr;
	protected $model_const_type_mstr;

	public function __construct()
	{
		parent::__construct();
		helper(['db_helper', 'qr_code_generator_helper','utility_helper']);
		if ($db_name = dbConfig("property")) {
			$this->db = db_connect($db_name);
		}
		/* if ($db_system = dbSystem()) {
			$this->dbSystem = db_connect($db_system);
		} */
		/* $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
		$this->model = new model_ward_mstr($this->dbSystem); */
		$this->model_prop_dtl = new model_prop_dtl($this->db);
		/* $this->model_emp_details = new model_emp_details($this->dbSystem);
		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem); */
		$this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
		$this->model_prop_tax = new model_prop_tax($this->db);
		$this->model_prop_demand = new model_prop_demand($this->db);
		$this->model_prop_floor_details = new model_prop_floor_details($this->db);
		$this->model_transaction = new model_transaction($this->db);
		$this->model_saf_dtl = new model_saf_dtl($this->db);
		$this->model_tran_mode_mstr = new model_tran_mode_mstr($this->db);
		$this->model_payment_adjustment = new model_payment_adjustment($this->db);
		$this->model_cheque_details = new model_cheque_details($this->db);
		$this->model_collection = new model_collection($this->db);
		/* $this->model_tc_call = new model_tc_call($this->dbSystem); */
		$this->model_bank_recancilation = new model_bank_recancilation($this->db);
		$this->model_transaction_fine_rebet_details = new model_transaction_fine_rebet_details($this->db);
		$this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
		$this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
		$this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
		$this->model_saf_tax = new model_saf_tax($this->db);
		$this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->PropertyTypeModel = new PropertyTypeModel($this->db);
        $this->model_floor_mstr = new model_floor_mstr($this->db);
        $this->model_usage_type_mstr = new model_usage_type_mstr($this->db);
        $this->model_usage_type_dtl = new model_usage_type_dtl($this->db);
        $this->model_occupancy_type_mstr = new model_occupancy_type_mstr($this->db);
        $this->model_const_type_mstr = new model_const_type_mstr($this->db);
		/* $this->model_system_name = new model_system_name($this->dbSystem); */
	}

	function __destruct() {
		$this->db->close();
	}

	public function comparativeTax($govt_saf_dtl_id=null) {
        $safHelper = new SAFHelper($this->db);
        $session=session();
        //$ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];
		$sql = "SELECT 
					tbl_govt_saf_dtl.id,
					view_ward_mstr.ward_no,
					tbl_govt_saf_dtl.application_no,
					tbl_govt_saf_dtl.office_name,
					tbl_govt_saf_dtl.building_colony_address AS prop_address,
					tbl_govt_saf_dtl.ward_mstr_id,
					tbl_govt_saf_dtl.new_ward_mstr_id,
					tbl_govt_saf_dtl.zone_mstr_id,
					tbl_govt_saf_dtl.prop_type_mstr_id,
					tbl_govt_saf_dtl.road_type_mstr_id,
					10 AS area_of_plot,
					tbl_govt_saf_dtl.is_mobile_tower,
					tbl_govt_saf_dtl.tower_area,
					tbl_govt_saf_dtl.tower_installation_date,
					tbl_govt_saf_dtl.is_hoarding_board,
					tbl_govt_saf_dtl.hoarding_area,
					tbl_govt_saf_dtl.hoarding_installation_date,
					tbl_govt_saf_dtl.is_petrol_pump,
					tbl_govt_saf_dtl.under_ground_area,
					tbl_govt_saf_dtl.petrol_pump_completion_date,
					tbl_govt_saf_dtl.is_water_harvesting,
					NULL AS land_occupation_date,
					floor_dtl.prop_floor_details	
				FROM tbl_govt_saf_dtl
				INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_govt_saf_dtl.ward_mstr_id
				INNER JOIN tbl_govt_saf_officer_dtl ON tbl_govt_saf_officer_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
				INNER JOIN (
                    SELECT
                        govt_saf_dtl_id,
                        json_agg(json_build_object('id', id, 'floor_mstr_id', floor_mstr_id, 'usage_type_mstr_id', usage_type_mstr_id, 'const_type_mstr_id', const_type_mstr_id, 'occupancy_type_mstr_id', occupancy_type_mstr_id, 'builtup_area', builtup_area, 'date_from', date_from, 'date_upto', date_upto)) AS prop_floor_details
                    FROM tbl_govt_saf_floor_dtl
                    GROUP BY govt_saf_dtl_id
                ) AS floor_dtl ON floor_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
				WHERE 
					tbl_govt_saf_dtl.id=".$govt_saf_dtl_id;
		if ($data = $this->db->query($sql)->getFirstRow("array")) {
			$inputs = [
                "ward_mstr_id"=>$data["ward_mstr_id"],
                "new_ward_mstr_id"=>$data["new_ward_mstr_id"],
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
                "land_occupation_date"=>$data["occupation_date"],
                "prop_id" => $govt_saf_dtl_id,
                "comperitiv_tax" => true,
                "is_gb_saf" => true,
            ];
			if ($data["prop_type_mstr_id"]!=4) {
				$data["prop_floor_details"]=json_decode($data["prop_floor_details"], true);
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
            } else { // building cal
                $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                foreach ($floorDtlArr as $key => $value) {
                    $floorDtlArr[$key]["floor_name"] = $this->model_floor_mstr->getdatabyid($value["floor_mstr_id"])["floor_name"];
                }
                $data['floorDtlArr'] = $floorDtlArr;
            }
            $data['ulb_details'] = $session->get('ulb_dtl');
            $data['ulb_mstr_name'] = $session->get('ulb_dtl');
            return view('property/govt_comparative_tax',$data);
		}
    }

    public function colonycomparativeTax($govt_saf_dtl_id=null) {
        $safHelper = new SAFHelper($this->db);
        $session=session();
        //$ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];
		$sql = "SELECT 
					tbl_govt_saf_dtl.id,
                    tbl_govt_saf_dtl.colony_mstr_id,
                    tbl_govt_saf_dtl.is_csaf2_generated,
					view_ward_mstr.ward_no,
					tbl_govt_saf_dtl.application_no,
					tbl_govt_saf_dtl.office_name,
					tbl_govt_saf_dtl.building_colony_address AS prop_address,
					tbl_govt_saf_dtl.ward_mstr_id,
					tbl_govt_saf_dtl.new_ward_mstr_id,
					tbl_govt_saf_dtl.zone_mstr_id,
					tbl_govt_saf_dtl.prop_type_mstr_id,
					tbl_govt_saf_dtl.road_type_mstr_id,
					10 AS area_of_plot,
					tbl_govt_saf_dtl.is_mobile_tower,
					tbl_govt_saf_dtl.tower_area,
					tbl_govt_saf_dtl.tower_installation_date,
					tbl_govt_saf_dtl.is_hoarding_board,
					tbl_govt_saf_dtl.hoarding_area,
					tbl_govt_saf_dtl.hoarding_installation_date,
					tbl_govt_saf_dtl.is_petrol_pump,
					tbl_govt_saf_dtl.under_ground_area,
					tbl_govt_saf_dtl.petrol_pump_completion_date,
					tbl_govt_saf_dtl.is_water_harvesting,
					NULL AS land_occupation_date
				FROM tbl_govt_saf_dtl
				INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_govt_saf_dtl.ward_mstr_id
				INNER JOIN tbl_govt_saf_officer_dtl ON tbl_govt_saf_officer_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
				WHERE 
					tbl_govt_saf_dtl.id=".$govt_saf_dtl_id;
		if ($data = $this->db->query($sql)->getFirstRow("array")) {
            
			$inputs = [
                "ward_mstr_id"=>$data["ward_mstr_id"],
                "new_ward_mstr_id"=>$data["new_ward_mstr_id"],
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
                "land_occupation_date"=>$data["occupation_date"],
                "prop_id" => $govt_saf_dtl_id,
                "comperitiv_tax" => true,
                "is_gb_saf" => true,
            ];
			if ($data["prop_type_mstr_id"]!=4) {
                $sql = "SELECT
                            tbl_govt_saf_floor_dtl.*
                        FROM tbl_govt_saf_dtl
                        INNER JOIN tbl_govt_saf_floor_dtl ON tbl_govt_saf_floor_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
                        WHERE
                            tbl_govt_saf_floor_dtl.status=1
                            AND tbl_govt_saf_dtl.colony_mstr_id=".$data["colony_mstr_id"];
                $data["prop_floor_details"] = $this->db->query($sql)->getResult("array");
                //print_var($data["prop_floor_details"]);
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
            } else { // building cal
                $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                foreach ($floorDtlArr as $key => $value) {
                    $floorDtlArr[$key]["floor_name"] = $this->model_floor_mstr->getdatabyid($value["floor_mstr_id"])["floor_name"];
                }
                $data['floorDtlArr'] = $floorDtlArr;
            }
            $data['ulb_details'] = $session->get('ulb_dtl');
            $data['ulb_mstr_name'] = $session->get('ulb_dtl');
            return view('property/govt_comparative_tax',$data);
			//print_var($data);
		}
    }
}
