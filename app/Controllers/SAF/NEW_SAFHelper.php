<?php
namespace App\Controllers\SAF;
use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_ward_mstr;
use App\Models\model_view_ward_mapping_mstr;
use App\Models\model_apartment_details;
use App\Models\model_transfer_mode_mstr;
use App\Models\model_ownership_type_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_floor_mstr;
use App\Models\model_usage_type_mstr;
use App\Models\model_usage_type_dtl;
use App\Models\model_occupancy_type_mstr;
use App\Models\model_const_type_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_floor_details;
use App\Models\model_saf_floor_arv_dtl;
use App\Models\model_saf_tax;
use App\Models\model_saf_demand;
use App\Models\model_arr_old_building_mstr;
use App\Models\model_arr_building_mstr;
use App\Models\model_arr_vacant_mstr;
use App\Models\model_arr_new_vacant_mstr;
use App\Models\model_capital_value_rate;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_tax;
use App\Models\model_prop_demand;
use App\Models\model_payment_adjust;
use App\Models\model_saf_distributed_dtl;
use App\Models\model_doc_mstr;
use App\Models\model_saf_doc_dtl;
use App\Models\model_view_saf_doc_dtl;
use App\Models\model_level_pending_dtl;
use App\Models\model_view_saf_dtl;
use App\Models\model_transaction;
use App\Models\model_prop_floor_details;
use App\Models\model_govt_saf_dtl;
use App\Models\model_govt_saf_officer_dtl;
use App\Models\model_govt_saf_floor_dtl;
use App\Models\model_govt_saf_tax;
use App\Models\model_govt_saf_demand;
use CodeIgniter\Database\ConnectionInterface;
use DateTime;

class NEW_SAFHelper extends Controller
{
	protected $db;
	protected $dbSystem;
	protected $model_ulb_mstr;
	protected $model_fy_mstr;
	protected $model_ward_mstr;
	protected $model_view_ward_mapping_mstr;
	protected $model_apartment_details;
	protected $model_transfer_mode_mstr;
	protected $model_ownership_type_mstr;
	protected $model_prop_type_mstr;
	protected $model_road_type_mstr;
	protected $model_floor_mstr;
	protected $model_usage_type_mstr;
	protected $model_usage_type_dtl;
	protected $model_occupancy_type_mstr;
	protected $model_const_type_mstr;
	protected $model_saf_dtl;
	protected $model_saf_owner_detail;
	protected $model_saf_floor_details;
	protected $model_saf_floor_arv_dtl;
	protected $model_saf_tax;
	protected $model_saf_demand;
	protected $model_arr_old_building_mstr;
	protected $model_arr_building_mstr;
	protected $model_arr_vacant_mstr;
	protected $model_arr_new_vacant_mstr;
	protected $model_capital_value_rate;
	protected $model_prop_dtl;
	protected $model_prop_owner_detail;
	protected $model_prop_tax;
	protected $model_prop_demand;
	protected $model_payment_adjust;
	protected $model_saf_distributed_dtl;
	protected $model_doc_mstr;
	protected $model_saf_doc_dtl;
	protected $model_view_saf_doc_dtl;
	protected $model_level_pending_dtl;
	protected $model_view_saf_dtl;
	protected $model_transaction;
	protected $model_prop_floor_details;
	protected $model_govt_saf_dtl;
	protected $model_govt_saf_officer_dtl;
	protected $model_govt_saf_floor_dtl;
	protected $model_govt_saf_tax;
	protected $model_govt_saf_demand;

    public function __construct(ConnectionInterface $db)
    {
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'utility_helper']);
        $this->db = $db;
        if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect($db_name); 
        }
		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
		$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
		$this->model_view_ward_mapping_mstr = new model_view_ward_mapping_mstr($this->dbSystem);
		$this->model_apartment_details = new model_apartment_details($this->db);
        $this->model_transfer_mode_mstr = new model_transfer_mode_mstr($this->db);
        $this->model_ownership_type_mstr = new model_ownership_type_mstr($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->model_floor_mstr = new model_floor_mstr($this->db);
        $this->model_usage_type_mstr = new model_usage_type_mstr($this->db);
        $this->model_usage_type_dtl = new model_usage_type_dtl($this->db);
        $this->model_occupancy_type_mstr = new model_occupancy_type_mstr($this->db);
        $this->model_const_type_mstr = new model_const_type_mstr($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
		$this->model_saf_floor_details = new model_saf_floor_details($this->db);
		$this->model_saf_floor_arv_dtl = new model_saf_floor_arv_dtl($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_arr_old_building_mstr = new model_arr_old_building_mstr($this->db);
        $this->model_arr_building_mstr = new model_arr_building_mstr($this->db);
		$this->model_arr_vacant_mstr = new model_arr_vacant_mstr($this->db);
		$this->model_arr_new_vacant_mstr = new model_arr_new_vacant_mstr($this->db);
		$this->model_capital_value_rate = new model_capital_value_rate($this->db);
		$this->model_prop_dtl = new model_prop_dtl($this->db);
		$this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
		$this->model_prop_demand = new model_prop_demand($this->db);
		$this->model_prop_tax = new model_prop_tax($this->db);
		$this->model_payment_adjust = new model_payment_adjust($this->db);
		$this->model_saf_distributed_dtl = new model_saf_distributed_dtl($this->db);
		$this->model_doc_mstr = new model_doc_mstr($this->db);
		$this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
		$this->model_view_saf_doc_dtl = new model_view_saf_doc_dtl($this->db);
		$this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
		$this->model_transaction = new model_transaction($this->db);
		$this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
		$this->model_prop_floor_details = new model_prop_floor_details($this->db);
		$this->model_govt_saf_dtl = new model_govt_saf_dtl($this->db);
        $this->model_govt_saf_officer_dtl = new model_govt_saf_officer_dtl($this->db);
		$this->model_govt_saf_floor_dtl = new model_govt_saf_floor_dtl($this->db);
        $this->model_govt_saf_tax = new model_govt_saf_tax($this->db);
        $this->model_govt_saf_demand = new model_govt_saf_demand($this->db);
    }

    public function calBuildingTaxDtl_2023($floorDtlArr, $prop_type_mstr_id, $isAdditionaTaxImplemented)
	{
		$old_rule_arv_sub = [];
		$new_rule_arv_sub = [];
		$cv_rule_arv_sub = [];
		$vacantLandAdded = 0;
        
		// cal sub old/new rule arv
		foreach ($floorDtlArr as $key => $floorDtl) {
			$yyyyMMDDFloor = $floorDtl['date_from'] . "-01";
			$date_upto_floor = ($floorDtl['date_upto'] == "") ? "" : $floorDtl['date_upto'] . "-01";

			if ("2016-04-01" > $yyyyMMDDFloor) {
				//if ($prop_type_mstr_id!=1 && $prop_type_mstr_id!=5) {
				$old_rule_arv_sub[] = [
					"fyear" => $floorDtl['fyear'],
					"qtr" => $floorDtl['qtr'],
					"arv" => $floorDtl['old_arv'],
					"operator" => $floorDtl['operator']
				];
				//}
				if ($date_upto_floor == "" || $date_upto_floor >= "2016-04-01") {
					$new_rule_arv_sub[] = [
						"fyear" => "2016-2017",
						"qtr" => 1,
						"arv" => $floorDtl['new_arv'],
						"operator" => $floorDtl['operator']
					];
				}
				if ($date_upto_floor == "" || $date_upto_floor >= "2022-04-01") {
					$cv_rule_arv_sub[] = [
						"fyear" => "2022-2023",
						"qtr" => 1,
						"arv" => $floorDtl['cv'],
						"operator" => $floorDtl['operator']
					];
				}
			} else if ($yyyyMMDDFloor >= "2016-04-01" && "2022-04-01" > $yyyyMMDDFloor) {
				$new_rule_arv_sub[] = [
					"fyear" => $floorDtl['fyear'],
					"qtr" => $floorDtl['qtr'],
					"arv" => $floorDtl['new_arv'],
					"operator" => $floorDtl['operator']
				];
				if ($date_upto_floor == "" || $date_upto_floor >= "2022-04-01") {
					$cv_rule_arv_sub[] = [
						"fyear" => "2022-2023",
						"qtr" => 1,
						"arv" => $floorDtl['cv'],
						"operator" => $floorDtl['operator']
					];
				}
			} else if ($yyyyMMDDFloor >= "2022-04-01") {
				if ($floorDtl['type'] == "vacant" && $vacantLandAdded == 0) {
					$vacantLandAdded++;
					$cv_rule_arv_sub[] = [
						"VACANT_TYPE" => "VACANT",
						"fyear" => $floorDtl['fyear'],
						"qtr" => $floorDtl['qtr'],
						"arv" => $floorDtl['cv'],
						"operator" => $floorDtl['operator']
					];
				} else {
					$cv_rule_arv_sub[] = [
						"fyear" => $floorDtl['fyear'],
						"qtr" => $floorDtl['qtr'],
						"arv" => $floorDtl['cv'],
						"operator" => $floorDtl['operator']
					];
				}
			}
		}

		$effectQtr = (int)0;
		$effectFy = "";
		$old_arv_total = 0;
		$safTaxDtl = [];
		$taxCount = (int)0;

		$effectQtr = (int)0;
		$effectFy = "";
		$cv_arv_total = 0;
		foreach ($cv_rule_arv_sub as $key => $arvDtl) {
			if ($arvDtl['operator'] == "+") {
				$cv_arv_total += $arvDtl["arv"];
			} else if ($arvDtl['operator'] == "-") {
				$cv_arv_total -= $arvDtl["arv"];
			}
			$holding_tax = round(($cv_arv_total / 4), 2);
			$additional_tax = 0;
			if ($isAdditionaTaxImplemented == TRUE) {
				$additional_tax = $cv_arv_total / 4;
				if ($additional_tax > 0)
					$additional_tax = round((($additional_tax * 1.5) - $additional_tax), 2);
			}
			$quarterly_tax = $holding_tax + $additional_tax;
			if ($arvDtl['qtr'] != $effectQtr || $arvDtl['fyear'] != $effectFy) {
				$effectQtr = (int)$arvDtl['qtr'];
				$effectFy = $arvDtl['fyear'];
				$safTaxDtl[$taxCount++] = ["rule_type" => "CV_RULE", "fyear" => $effectFy, "qtr" => $effectQtr, "arv" => $cv_arv_total, "holding_tax" => $holding_tax, "additional_tax" => $additional_tax, "quarterly_tax" => $quarterly_tax];
			} else {
				$safTaxDtl[$taxCount - 1] = ["rule_type" => "CV_RULE", "fyear" => $effectFy, "qtr" => $effectQtr, "arv" => $cv_arv_total, "holding_tax" => $holding_tax, "additional_tax" => $additional_tax, "quarterly_tax" => $quarterly_tax];
			}
		}
		return [$safTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub];
	}
}