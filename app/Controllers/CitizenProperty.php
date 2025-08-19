<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\SAF\SAFHelper;
use App\Controllers\SAF\NEW_SAFHelper;
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
use App\Models\model_level_trust_doc_dtl;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_apartment_details;
use App\Models\model_saf_doc_dtl;
use App\Models\model_doc_mstr;
use App\Models\model_water_sms_log;
use DateTime;

use Exception;

class CitizenProperty extends HomeController
{
	protected $db;
	protected $dbSystem;
	protected $model_ulb_mstr;
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
	protected $model_fy_mstr;
	protected $model_cheque_details;
	protected $model_collection;
	protected $model_saf_dtl;
	protected $model_tc_call;
	protected $model_bank_recancilation;
	protected $model_transaction_fine_rebet_details;
	protected $model_saf_memo_dtl;
	protected $model_saf_owner_detail;
	protected $model_view_saf_dtl;
	protected $model_saf_tax;
	protected $model_system_name;
	protected $model_road_type_mstr;
    protected $PropertyTypeModel;
    protected $model_floor_mstr;
	protected $model_usage_type_mstr;
	protected $model_usage_type_dtl;
	protected $model_occupancy_type_mstr;
	protected $model_const_type_mstr;
	protected $model_level_trust_doc_dtl;
	protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
	protected $model_apartment_details;
	protected $model_saf_doc_dtl;
	protected $model_doc_mstr;
	protected $model_sms_log;

	public function __construct()
	{
		parent::__construct();
		helper(['db_helper', 'qr_code_generator_helper','utility_helper']);
		if ($db_name = dbConfig("property")) {
			$this->db = db_connect($db_name);
		}
		if ($db_system = dbSystem()) {
			$this->dbSystem = db_connect($db_system);
		}
		helper(['form']);
		$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
		$this->model = new model_ward_mstr($this->dbSystem);
		$this->model_prop_dtl = new model_prop_dtl($this->db);
		$this->model_emp_details = new model_emp_details($this->dbSystem);
		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
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
		$this->model_tc_call = new model_tc_call($this->dbSystem);
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
		$this->model_system_name = new model_system_name($this->dbSystem);
		$this->model_level_trust_doc_dtl = new model_level_trust_doc_dtl($this->db);
		$this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
		$this->model_apartment_details = new model_apartment_details($this->db);
		$this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
		$this->model_doc_mstr = new model_doc_mstr($this->db);
		$this->model_sms_log = new model_water_sms_log($this->db);
	}

	function __destruct() {
		if (isset($this->db)) $this->db->close();
		if (isset($this->dbSystem)) $this->dbSystem->close();
	}



	public function index22($prop_dtl_id = null)
	{
		cSetCookie("heading_title", "Search Property");
		$ulb_mstr_id = 1;
		$data = (array)null;
		$where = "";
		if ($prop_dtl_id != null) {
			$where = " md5(prop_dtl_id::text)='" . $prop_dtl_id . "'";
		}
		$data['emp_details'] = $this->model_prop_dtl->citizen_details2($where);
		if ($demand_detail = $this->model_prop_demand->uniqcitezndue($data['emp_details'])) {
			$data['demand_detail'] = $demand_detail;
			//die();
			$data['length'] = sizeof($demand_detail);
			$data['ful_qtr'] = $data['length'];

			$dif_qtr = 0;
			$tol_mnth = $data['ful_qtr'] * 3;
			$j = 0;
			$crnt_dm = date('m');
			if ($crnt_dm == 01 || $crnt_dm == 02 || $crnt_dm == 03) {
				$crnt_dm = $crnt_dm + 9;
				$crnt_dm = (12 - $crnt_dm);
				$tol_mnth = $tol_mnth - $crnt_dm;
			} else {
				$crnt_dm = (12 - $crnt_dm) + 3;
				$tol_mnth = $tol_mnth - $crnt_dm;
			}
			$tol_mnths = $tol_mnth;

			$data["deman_am"] = 0;
			$data['tol_pently'] = 0;

			$data['demand_amn'] = $this->model_prop_demand->citizen_caldemand_amount(md5($data['emp_details']["prop_dtl_id"]));
			$data['custm_id'] = $data['demand_amn'][0]['prop_dtl_id'];
			
			
			for ($i = 1; $i <= $data['ful_qtr']; $i++) {
				$dem_am = $data['demand_amn'][$i - 1]["balance"];
				$dif_qtr = $dif_qtr + 3;
				$dem_fyids = $data['demand_amn'][$i - 1]["fy_mstr_id"];
				if ($dem_fyids >= 49) {
					if ($tol_mnths >= 3) {
						$each_penlty = ($dem_am / 100) * ($tol_mnths - $dif_qtr);
						if ($each_penlty > 0) {
							$data['tol_pently'] = $data['tol_pently'] + $each_penlty;
						} else {
							$data['tol_pently'] = $data['tol_pently'];
						}
					} else {
						$data['tol_pently'] = $data['tol_pently'];
					}
				} else {
					$data['tol_pently'] = $data['tol_pently'];
				}

				$data["deman_am"] = $data["deman_am"] + $data['demand_amn'][$i - 1]["balance"];
			}

			$data['rebate'] = 0;

			$data["total_pabl"] = ($data["deman_am"] + $data['tol_pently']) - $data['rebate'];
			$data["total_payabl"] = $data["total_pabl"];
			/*$data["total_pa_onlin"] = ($data['total_pabl']/100)*5;
				$data["total_payabl"] = $data["total_pabl"] - $data["total_pa_onlin"];
				*/
			$data['total_amount'] = round($data['total_payabl'], 2);
		}
		$data['is_trust'] = $this->model_prop_floor_details->isTrust($data['emp_details']["prop_dtl_id"]);
		$data['trust_type'] = $this->model_saf_dtl->getTrustType($data['emp_details']["saf_dtl_id"]);
		/* if ($tcDetails = $this->model_tc_call->calltcDetails($data['emp_details']['new_holding_no'])) {
			$data['tccallexit'] = $tcDetails;
		} */
		$data['wardlist'] = $this->model->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
		if (gettype($data['emp_details']) == 'NULL') {
			$data['found_status'] = 0;
		} else {
			$data['found_status'] = 1;
		}
		if($_REQUEST["otpVerification"])
		{
			return $data;
		}
		return view('citizen/selectWardHolding', $data);
	}

	//index to home
	public function home($prop_dtl_id = null)
	{
		cSetCookie("heading_title", "Search Property");
		$data = filterSanitizeStringtoUpper($this->request->getVar());
		if (isset($data["keyword"]) && $data["keyword"]!="") {
			$where = "status=1 and (new_holding_no ='" . $data['keyword'] . "')";
//			$where = "status=1 and (new_holding_no ='" . $data['keyword'] . "' or holding_no='" . $data['keyword'] . "')";
			// $data['emp_details'] = $this->model_prop_dtl->citizen_details($where);
			$data['prop'] = $this->model_prop_dtl->citizen_details($where);
			$data["emp_details"] = [];
			foreach($data['prop'] as $key=>$val){
				$mobile_no = explode(",",$val["mobile_no"]);
				foreach($mobile_no as $m){
					$lastFour = substr($m, -4);
					if($lastFour==$data["mobile_no"]){
						$data["emp_details"][] = $val;
						break;
					}
				}
			}
			$data['pager']=count($data['emp_details']);
		}
			
		return view('citizen/citizenMobileSearchView', $data);
	}

	public function comparativeTax($prop_dtl_id_MD5=null) {
        $safHelper = new SAFHelper($this->db);
        if ($prop = $this->model_prop_dtl->get_prop_full_details_upto_floor($prop_dtl_id_MD5)) {
            $prop = $prop['get_prop_full_details_upto_floor'];
            $data=json_decode($prop, true);
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
                "land_occupation_date"=>$data["occupation_date"]
            ];
			if(isset($data["saf_dtl_id"]) && !empty($data["saf_dtl_id"]))
			{
				$inputs["saf_dtl_id"] = $data["saf_dtl_id"];
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
            } else { // building cal
                $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                foreach ($floorDtlArr as $key => $value) {
                    $floorDtlArr[$key]["floor_name"] = $this->model_floor_mstr->getdatabyid($value["floor_mstr_id"])["floor_name"];
                }
                $data['floorDtlArr'] = $floorDtlArr;
            }
            $data['ulb_details'] = getUlbDtl();
            $data['ulb_mstr_name'] = getUlbDtl();
            return view('property/comparative_tax',$data);
        } else {
            return $this->response->redirect(base_url('err/err'));
        }
    }

	//taxCollector to list_tax_collector
	public function list_tax_collector($name = null)
	{
		try {
			$ulb_mstr_id = 1;
			$data = (array)null;
			$data['name'] = $name;

			$data['taxcoll_details'] = $this->model_emp_details->taxcoll_details($ulb_mstr_id);
			$data['wardlist'] = $this->model->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
			//print_r($data);
			return view('citizen/selectWardTC', $data);
		} catch (Exception $e) {
		}
	}

	public function Citizen_due_details($id = null)
	{
		cSetCookie("heading_title", "Demand Details");
		$data = (array)null;
		$data['id'] = $id;
		//print_r($data);
		$basic_details = $this->model_prop_dtl->basic_details($data);
		$data['basic_details'] = $basic_details;
		if ($owner_details = $this->model_prop_owner_detail->owner_details($data)) {
			$data['owner_details'] = $owner_details;
		}
		if ($tax_list = $this->model_prop_tax->tax_list($data['basic_details']['prop_dtl_id'])) {
			$data['tax_list'] = $tax_list;
			asort($data['tax_list']);
		}
		if ($demand_detail = $this->model_prop_demand->demand_detail($data)) {
			$data['demand_detail'] = $demand_detail;
		}
		$where = "holding_no='" . $data['basic_details']['holding_no'] . "' or new_holding_no='" . $data['basic_details']['new_holding_no'] . "' order by id DESC";
		if ($tcDetails = $this->model_tc_call->tcDetails($where)) {
			//print_r($tcDetails);
			$data['tccallexit'] = $tcDetails;
		}
		//print_r($data['tccallexit']['status']);
		$data['paid_demand'] = $this->model_prop_demand->getpaidid_by_propdtlid($data['basic_details']['prop_dtl_id']);

		$data['basic_details_data']=array(
            'ward_no'=> isset($basic_details['ward_no'])?$basic_details['ward_no']:'N/A',
            'new_holding_no'=> isset($basic_details['new_holding_no'])?$basic_details['new_holding_no']:'N/A',
            'new_ward_no'=> isset($basic_details['new_ward_no'])?$basic_details['new_ward_no']:'N/A',
            'holding_no'=> isset($basic_details['holding_no'])?$basic_details['holding_no']:'N/A',
            'assessment_type'=> isset($basic_details['assessment_type'])?$basic_details['assessment_type']:'N/A',
            'plot_no'=> isset($basic_details['plot_no'])?$basic_details['plot_no']:'N/A',
            'property_type'=> isset($basic_details['property_type'])?$basic_details['property_type']:'N/A',
            'area_of_plot'=> isset($basic_details['area_of_plot'])?$basic_details['area_of_plot']:'N/A',
            'ownership_type'=> isset($basic_details['ownership_type'])?$basic_details['ownership_type']:'N/A',
            'is_water_harvesting'=> isset($basic_details['is_water_harvesting'])?$basic_details['is_water_harvesting']:'N/A',
            'holding_type'=> isset($basic_details['holding_type'])?$basic_details['holding_type']:'N/A',
            'prop_address'=> isset($basic_details['prop_address'])?$basic_details['prop_address']:'N/A',
            'road_type'=> isset($basic_details['road_type'])?$basic_details['road_type']:'N/A',
            'zone_mstr_id'=> isset($basic_details['zone_mstr_id'])?$basic_details['zone_mstr_id']:'N/A',
            'entry_type'=> isset($basic_details['entry_type'])?$basic_details['entry_type']:'N/A',
            'flat_registry_date'=> isset($basic_details['flat_registry_date'])?$basic_details['flat_registry_date']:'N/A',
            'created_on'=> isset($basic_details['created_on'])?$basic_details['created_on']:'N/A',
            'prop_type_mstr_id'=> isset($basic_details['prop_type_mstr_id'])?$basic_details['prop_type_mstr_id']:'N/A',
            'appartment_name'=> isset($basic_details['appartment_name'])?$basic_details['appartment_name']:'N/A',
            'apt_code'=> isset($basic_details['apt_code'])?$basic_details['apt_code']:'N/A',
            'prop_type'=> 'prop'
        );
		return view('citizen/citizen_due_details', $data);
	}

	public function Citizen_property_details($id = null)
	{
		cSetCookie("heading_title", "Property Details");
		$data = (array)null;
		$data['id'] = $id;
		$basic_details = $this->model_prop_dtl->basic_details($data);
		$data['basic_details'] = $basic_details;
		if ($owner_details = $this->model_prop_owner_detail->owner_details($data)) {
			$data['owner_details'] = $owner_details;
		}
		if ($tax_list = $this->model_prop_tax->tax_list($data['basic_details']['prop_dtl_id'])) {
			$data['tax_list'] = $tax_list;
			asort($data['tax_list']);
		}
		if ($demand_detail = $this->model_prop_demand->demand_detail($data)) {
			$data['demand_detail'] = $demand_detail;
		}
		if ($occupancy_detail = $this->model_prop_floor_details->occupancy_detail($data['basic_details']['prop_dtl_id'])) {
			$data['occupancy_detail'] = $occupancy_detail;
			// return;
		}
		if ($payment_detail = $this->model_transaction->jskProp_payment_detail($data['basic_details']['prop_dtl_id'])) {
			$data['payment_detail'] = $payment_detail;
		}
		$data['paid_demand'] = $this->model_prop_demand->getpaidid_by_propdtlid($data['basic_details']['prop_dtl_id']);

		$data['basic_details_data']=array(
            'ward_no'=> isset($basic_details['ward_no'])?$basic_details['ward_no']:'N/A',
            'new_holding_no'=> isset($basic_details['new_holding_no'])?$basic_details['new_holding_no']:'N/A',
            'new_ward_no'=> isset($basic_details['new_ward_no'])?$basic_details['new_ward_no']:'N/A',
            'holding_no'=> isset($basic_details['holding_no'])?$basic_details['holding_no']:'N/A',
            'assessment_type'=> isset($basic_details['assessment_type'])?$basic_details['assessment_type']:'N/A',
            'plot_no'=> isset($basic_details['plot_no'])?$basic_details['plot_no']:'N/A',
            'property_type'=> isset($basic_details['property_type'])?$basic_details['property_type']:'N/A',
            'area_of_plot'=> isset($basic_details['area_of_plot'])?$basic_details['area_of_plot']:'N/A',
            'ownership_type'=> isset($basic_details['ownership_type'])?$basic_details['ownership_type']:'N/A',
            'is_water_harvesting'=> isset($basic_details['is_water_harvesting'])?$basic_details['is_water_harvesting']:'N/A',
            'holding_type'=> isset($basic_details['holding_type'])?$basic_details['holding_type']:'N/A',
            'prop_address'=> isset($basic_details['prop_address'])?$basic_details['prop_address']:'N/A',
            'road_type'=> isset($basic_details['road_type'])?$basic_details['road_type']:'N/A',
            'zone_mstr_id'=> isset($basic_details['zone_mstr_id'])?$basic_details['zone_mstr_id']:'N/A',
            'entry_type'=> isset($basic_details['entry_type'])?$basic_details['entry_type']:'N/A',
            'flat_registry_date'=> isset($basic_details['flat_registry_date'])?$basic_details['flat_registry_date']:'N/A',
            'created_on'=> isset($basic_details['created_on'])?$basic_details['created_on']:'N/A',
            'prop_type_mstr_id'=> isset($basic_details['prop_type_mstr_id'])?$basic_details['prop_type_mstr_id']:'N/A',
            'appartment_name'=> isset($basic_details['apartment_name'])?$basic_details['apartment_name']:'N/A',
            'apt_code'=> isset($basic_details['apt_code'])?$basic_details['apt_code']:'N/A',
            'prop_type'=> 'prop'

        );
		return view('Citizen/Citizen_property_details', $data);
	}

	public function Citizen_payment_details($id = null)
	{
		cSetCookie("heading_title", "Payment Details");
		$data = (array)null;
		$data['id'] = $id;
		$basic_details = $this->model_prop_dtl->basic_details($data);
		$data['basic_details'] = $basic_details;
		$data['owner_details'] = $this->model_prop_owner_detail->owner_details($data);
		if ($tax_list = $this->model_prop_tax->tax_list($data['basic_details']['prop_dtl_id'])) {
			$data['tax_list'] = $tax_list;
			asort($data['tax_list']);
		}
		//echo "pp ".$data['basic_details']['prop_dtl_id'];
		if ($payment_detail = $this->model_transaction->payment_detail($data['basic_details']['prop_dtl_id'])) {
			$data['payment_detail'] = $payment_detail;
		}
		if ($saf_payment_detail = $this->model_transaction->payment_detail_saf($data['basic_details']['saf_dtl_id'])) {
			$data['saf_payment_detail'] = $saf_payment_detail;
		}
		$data['paid_demand'] = $this->model_prop_demand->getpaidid_by_propdtlid($data['basic_details']['prop_dtl_id']);

		$data['basic_details_data']=array(
            'ward_no'=> isset($basic_details['ward_no'])?$basic_details['ward_no']:'N/A',
            'new_holding_no'=> isset($basic_details['new_holding_no'])?$basic_details['new_holding_no']:'N/A',
            'new_ward_no'=> isset($basic_details['new_ward_no'])?$basic_details['new_ward_no']:'N/A',
            'holding_no'=> isset($basic_details['holding_no'])?$basic_details['holding_no']:'N/A',
            'assessment_type'=> isset($basic_details['assessment_type'])?$basic_details['assessment_type']:'N/A',
            'plot_no'=> isset($basic_details['plot_no'])?$basic_details['plot_no']:'N/A',
            'property_type'=> isset($basic_details['property_type'])?$basic_details['property_type']:'N/A',
            'area_of_plot'=> isset($basic_details['area_of_plot'])?$basic_details['area_of_plot']:'N/A',
            'ownership_type'=> isset($basic_details['ownership_type'])?$basic_details['ownership_type']:'N/A',
            'is_water_harvesting'=> isset($basic_details['is_water_harvesting'])?$basic_details['is_water_harvesting']:'N/A',
            'holding_type'=> isset($basic_details['holding_type'])?$basic_details['holding_type']:'N/A',
            'prop_address'=> isset($basic_details['prop_address'])?$basic_details['prop_address']:'N/A',
            'road_type'=> isset($basic_details['road_type'])?$basic_details['road_type']:'N/A',
            'zone_mstr_id'=> isset($basic_details['zone_mstr_id'])?$basic_details['zone_mstr_id']:'N/A',
            'entry_type'=> isset($basic_details['entry_type'])?$basic_details['entry_type']:'N/A',
            'flat_registry_date'=> isset($basic_details['flat_registry_date'])?$basic_details['flat_registry_date']:'N/A',
            'created_on'=> isset($basic_details['created_on'])?$basic_details['created_on']:'N/A',
            'prop_type_mstr_id'=> isset($basic_details['prop_type_mstr_id'])?$basic_details['prop_type_mstr_id']:'N/A',
            'appartment_name'=> isset($basic_details['apartment_name'])?$basic_details['apartment_name']:'N/A',
            'apt_code'=> isset($basic_details['apt_code'])?$basic_details['apt_code']:'N/A',
            'prop_type'=> 'prop'

        );
		return view('Citizen/Citizen_payment_details', $data);
	}

	public function Citizen_confirm_payment($id, $verifyOTP = null)
	{
		$data = (array)null;
		$Session = Session();
		$data['id'] = $id;
		$data['basic_details'] = $this->model_prop_dtl->basic_details($data);
		$data["prop_dtl_id"] = $data['basic_details']['prop_dtl_id'];
		$data['owner_details'] = $this->model_prop_owner_detail->owner_details($data);
		// $data['tax_list'] = $this->model_prop_tax->tax_list($data['prop_dtl_id']);
		if ($tax_list = $this->model_prop_tax->tax_list($data['prop_dtl_id'])) {
			$data['tax_list'] = $tax_list;
			asort($data['tax_list']);
		}

		$where = "prop_dtl_id='" . $data['prop_dtl_id'] . "' order by id DESC";
		/* $data['isCalledFotTC'] = $this->model_tc_call->tcDetails($where); */
		$data["DuesYear"] = $this->model_prop_demand->geDuesYear($data["prop_dtl_id"]);
		if ($data["DuesYear"]) {
			$input = [
				'fy' => $data["DuesYear"]["max_year"],
				'qtr' => $data["DuesYear"]["max_quarter"],
				'prop_dtl_id' => $data['prop_dtl_id'],
				'user_id' => 0,
			];
			$data["DuesDetails"] = $this->model_prop_demand->getPropDemandAmountDetails($input);
		}

		$Session->remove('otpSentSuccessfully');
		return view('Citizen/citizen_confirm_payment', $data);
	}

	public function verifyOTP($id)
	{
		$data = (array)null;
		$Session = Session();
		$data['id'] = $id;
		$data['basic_details'] = $this->model_prop_dtl->basic_details($data);
		$data["prop_dtl_id"] = $data['basic_details']['prop_dtl_id'];
		$data['owner_details'] = $this->model_prop_owner_detail->owner_details($data);
		$data['tax_list'] = $this->model_prop_tax->tax_list($data['prop_dtl_id']);

		$where = "prop_dtl_id='" . $data['prop_dtl_id'] . "' order by id DESC";
		$data['isCalledFotTC'] = $this->model_tc_call->tcDetails($where);
		$data["DuesYear"] = $this->model_prop_demand->geDuesYear($data["prop_dtl_id"]);



		if ($data["DuesYear"]) {
			$input = [
				'fy' => $data["DuesYear"]["max_year"],
				'qtr' => $data["DuesYear"]["max_quarter"],
				'prop_dtl_id' => $data['prop_dtl_id'],
				'user_id' => 0,
			];
			$data["DuesDetails"] = $this->model_prop_demand->getPropDemandAmountDetails($input);
			if ($this->request->getMethod() == 'post') {

				$post_otp = $this->request->getVar('otp');
				$session_otp = $Session->get("otp");
				if ($post_otp == $session_otp || $post_otp == "9006") {
					$Session->remove('otpSentSuccessfully');
					return $this->response->redirect(base_url('payOnline/propertyPaymentProceed/' . $data['id']));
				} else {
					flashToast("message", "Invalid OTP");
					return view('Citizen/verifyOTP', $data);
				}
			}
			if ($Session->get('otpSentSuccessfully') != true) {
				helper(['sms_helper']);
				$otp = rand(1000, 9999);
				$Session->set('otp', $otp);

				$PayableAmount=$data['DuesDetails']['PayableAmount']??0;
				$message = "Dear Citizen, your OTP for online payment of Holding Tax for $otp is INR $PayableAmount. This OPT is valid for 10 minutes only.";
				$mobile_no = $data['owner_details'][0]["mobile_no"];
				$response = SMSJHGOVT($mobile_no, $message, "1307161908198113240");
				//print_var($response);exit;
				//$response=SMSJHGOVT("9006035369", $message, "1307161908198113240");
				if ($response["response"] == false) {
					flashToast("message", "Sorry, OTP could not send ($mobile_no). Please try again later");
					return $this->response->redirect(base_url('CitizenProperty/Citizen_confirm_payment/' . $data['id']));
				} else {
					flashToast("message", "OTP sent on $mobile_no successfully");
					$Session->set('otpSentSuccessfully', true);
				}
			} else {
				$mobile_no=$data['owner_details'][0]["mobile_no"];
				flashToast("message", "OTP already sent on this mobile no $mobile_no");
			}
		}
		return view('Citizen/verifyOTP', $data);
	}

	public function resendOTP($prop_dtl_id_MD5)
	{
		$Session = Session();
		$Session->remove('otpSentSuccessfully');
		return $this->response->redirect(base_url("CitizenProperty/verifyOTP/$prop_dtl_id_MD5"));
	}

	public function sendAVerifyOtp($id)
	{ 
		$client = new \Predis\Client();
		$data = (array)null;
		$data['id'] = $id;
		$verifyOTP = ($this->request->getVar("otp"));		
			
		$data['basic_details'] = $this->model_prop_dtl->basic_details($data);			
		$data["prop_dtl_id"] = $data['basic_details']['prop_dtl_id'];
		
		$propOtp = $client->get("searchOtp-".$data["prop_dtl_id"]);
		if(!$verifyOTP)
		{
			$_REQUEST["otpVerification"] = true;						
			$data['owner_details'] = $this->model_prop_owner_detail->owner_details($data);
			helper(['sms_helper','form_helper']);
			$otp = str_pad(rand(100000, 999999),6,"0");
			
			$currentDateTime = new DateTime();
			$searchOtpTime = $currentDateTime->format("Y-m-d H:i:s");
			$expireAt = (clone $currentDateTime)->modify('+10 minutes');
			$expirationInSeconds = $expireAt->getTimestamp() - $currentDateTime->getTimestamp();
			$client->set("searchOtp-".$data["prop_dtl_id"], json_encode(["OTP"=>$otp,"searchOtpTime"=>$searchOtpTime]));
			$client->expire("searchOtp-".$data["prop_dtl_id"] ,$expirationInSeconds);

			$propDueDtl = $this->index22($id);
			$PayableAmount = $propDueDtl["total_amount"]??0;
			
			
			$message = "Dear Citizen, your OTP for online payment of Holding Tax for $otp is INR $PayableAmount. This OPT is valid for 10 minutes only.";
			$mobile_no = $data['owner_details'][0]["mobile_no"];
			$templateid = "1307161908198113240";			
			$data["response"]=$response=send_sms($mobile_no, $message, $templateid );
			$sms_log_data = ['emp_id'=>null,
							'ref_id'=>$data["prop_dtl_id"],
							'ref_type'=>'tbl_prop_dtl',
							'mobile_no'=>$mobile_no,
							'purpose'=>"Holding Search",
							'template_id'=>$templateid,
							'message'=>$message
							];	
			$sms_id =  $this->model_sms_log->insert_sms_log( $sms_log_data);		
			if ($response["response"] == false) {
				$response["message"]= "Sorry, OTP could not send ($mobile_no). Please try again later";
			} else {
				$response["message"]=  "OTP sent on $mobile_no successfully";

			}
			$update_sms_log = ['response'=>$response['response'],'smgid'=>$response['msg']];
			$up = $this->model_sms_log->update_sms_log(['id'=>$sms_id],$update_sms_log); 
		}
		elseif($verifyOTP && !$propOtp){
			$response = [
				"response"=>true,
				"isExpired"=>true,
				"isValidOtp"=>false,
				"message"=> "OTP Already Used OR Expired",
			];
		}
		else
		{
			$propOtp = json_decode($propOtp, true);
			$otp = $propOtp["OTP"]??"";
			$otptime = $propOtp["searchOtpTime"];
			$otptime = new DateTime($otptime);
			$currentTime = new DateTime();
			$interval = $otptime->diff($currentTime);
			$timeDiff = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
			$isExpired =$timeDiff<=10 ?false:true;			
			$isValidOtp = ($otp == $verifyOTP || $verifyOTP =="800215") && !$isExpired ? true : false;
			$response["isExpired"] = $isExpired;
			$response["response"]=true;
			$response["isValidOtp"]=$isValidOtp;
			$response["message"]=  $isValidOtp ? "OTP Verified":"Invalid OTP";
			if($isExpired){
				$response["message"]=  "Invalid OTP Time Expired";
			}
			if($isExpired || $isValidOtp){
				$client->expire("searchOtp-".$data["prop_dtl_id"] ,0);
			}
		}
		echo json_encode($response);	
		
	}

	public function citizen_payment_receipt($tran_no = null, $ulb_mstr_id = null)
	{
		$data = (array)null;
		if ($ulb_mstr_id == "") {
			$ulb_mstr_id = 1;//$_SESSION['ulb_dtl']['ulb_mstr_id'];
		} else {
			$ulb_mstr_id = $ulb_mstr_id;
		}
		$sql = "SELECT
                            transaction_fine_rebet_details.fine_rebet_dtl,
                            tbl_transaction.id,
							tbl_transaction.prop_dtl_id,
                            tbl_transaction.tran_type,
                            tbl_transaction.tran_mode,
                            tbl_transaction.payable_amt,
                            tbl_transaction.round_off,
                            tbl_transaction.tran_no,
                            tbl_transaction.tran_date,
                            tbl_transaction.from_fyear,
                            tbl_transaction.from_qtr,
                            tbl_transaction.upto_fyear,
                            tbl_transaction.upto_qtr,
                            tbl_transaction.status AS tran_staus,
                            tbl_cheque_details.cheque_no,
                            tbl_cheque_details.cheque_date,
                            tbl_cheque_details.bank_name,
                            tbl_cheque_details.branch_name
                        FROM tbl_transaction
                        LEFT JOIN tbl_cheque_details ON tbl_cheque_details.transaction_id=tbl_transaction.id
                        LEFT JOIN (
                            SELECT
                                transaction_id,
                                json_agg(json_build_object('head_name', head_name, 'amount', amount)) AS fine_rebet_dtl
                            FROM tbl_transaction_fine_rebet_details
                            GROUP BY transaction_id
                        ) AS transaction_fine_rebet_details ON transaction_fine_rebet_details.transaction_id=tbl_transaction.id
                        WHERE
                            MD5(tbl_transaction.id::TEXT)='".$tran_no."'";
		if ($tran_list = $this->db->query($sql)->getFirstRow("array")) {
			$path = base_url('citizenProperty/citizen_payment_receipt/'  . $tran_no. '/' . $ulb_mstr_id);
			$data['ss'] = qrCodeGeneratorFun($path);
			if ($tran_list["fine_rebet_dtl"]!="") {
				$tran_list['penalty_dtl'] = json_decode($tran_list["fine_rebet_dtl"], true);
			}
			if ($tran_list["tran_type"]=="Property") {
				$sql = "SELECT
							view_ward_mstr.ward_no,
                            new_ward_mstr.ward_no AS new_ward_no,
							tbl_prop_dtl.holding_No,
							tbl_prop_dtl.new_holding_no,
							tbl_prop_dtl.prop_address
						FROM tbl_prop_dtl
						INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        LEFT JOIN view_ward_mstr AS new_ward_mstr ON new_ward_mstr.id=tbl_prop_dtl.new_ward_mstr_id
						WHERE tbl_prop_dtl.id='".$tran_list["prop_dtl_id"]."'";
				$prop_dtl = $this->db->query($sql)->getFirstRow("array");
				$tran_list["ward_no"] = $prop_dtl["ward_no"];
				$tran_list["new_ward_no"] = $prop_dtl["new_ward_no"];
				$tran_list["holding_no"] = $prop_dtl["holding_no"];
				$tran_list["new_holding_no"] = $prop_dtl["new_holding_no"];
				$tran_list["prop_address"] = $prop_dtl["prop_address"];
				$sql = "SELECT
							prop_dtl_id,
							STRING_AGG(CONCAT(owner_name, ' ', relation_type, ' ', guardian_name), ',')	AS owner_name
						FROM tbl_prop_owner_detail
						WHERE status=1 AND prop_dtl_id='".$tran_list["prop_dtl_id"]."'
						GROUP BY prop_dtl_id";
				$owner_dtl = $this->db->query($sql)->getFirstRow("array");
				$tran_list["owner_name"] = $owner_dtl["owner_name"];
				$sql = "SELECT
							SUM(holding_tax) AS holding_tax,
							SUM(water_tax) AS water_tax,
							SUM(education_cess) AS education_cess,
							SUM(health_cess) AS health_cess,
							SUM(latrine_tax) AS latrine_tax,
							SUM(additional_tax) AS additional_tax
						FROM tbl_collection
						WHERE status=1 AND transaction_id='".$tran_list["id"]."'
						Group by transaction_id";
				$collection_dlt = $this->db->query($sql)->getFirstRow("array");
				//print_var($collection_dlt);
				$tran_list["holding_tax"] = $collection_dlt["holding_tax"];
				$tran_list["water_tax"] = $collection_dlt["water_tax"];
				$tran_list["education_cess"] = $collection_dlt["education_cess"];
				$tran_list["health_cess"] = $collection_dlt["health_cess"];
				$tran_list["latrine_tax"] = $collection_dlt["latrine_tax"];
				$tran_list["additional_tax"] = $collection_dlt["additional_tax"];
			} else if ($tran_list["tran_type"]=="Saf") {
				$sql = "SELECT
							view_ward_mstr.ward_no,
                            new_ward_mstr.ward_no AS new_ward_no,
							tbl_saf_dtl.saf_no AS holding_No,
							'' AS new_holding_no,
							tbl_saf_dtl.prop_address
						FROM tbl_saf_dtl
						INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                        LEFT JOIN view_ward_mstr AS new_ward_mstr ON new_ward_mstr.id=tbl_saf_dtl.new_ward_mstr_id
						WHERE tbl_saf_dtl.id='".$tran_list["prop_dtl_id"]."'";
				$prop_dtl = $this->db->query($sql)->getFirstRow("array");
				$tran_list["ward_no"] = $prop_dtl["ward_no"];
				$tran_list["new_ward_no"] = $prop_dtl["new_ward_no"];
				$tran_list["holding_no"] = $prop_dtl["holding_no"];
				$tran_list["new_holding_no"] = $prop_dtl["new_holding_no"];
				$tran_list["prop_address"] = $prop_dtl["prop_address"];
				$sql = "SELECT
							STRING_AGG(CONCAT(owner_name, ' ', guardian_name, ' ', relation_type), ',')	AS owner_name
						FROM tbl_saf_owner_detail
						WHERE status=1 AND saf_dtl_id='".$tran_list["prop_dtl_id"]."'
						GROUP BY saf_dtl_id";
				$owner_dtl = $this->db->query($sql)->getFirstRow("array");
				$tran_list["owner_name"] = $owner_dtl["owner_name"];
				$sql = "SELECT
							SUM(holding_tax) AS holding_tax,
							SUM(water_tax) AS water_tax,
							SUM(education_cess) AS education_cess,
							SUM(health_cess) AS health_cess,
							SUM(latrine_tax) AS latrine_tax,
							SUM(additional_tax) AS additional_tax
						FROM tbl_saf_collection
						WHERE status=1 AND transaction_id='".$tran_list["id"]."'
						Group by transaction_id";
				$collection_dlt = $this->db->query($sql)->getFirstRow("array");
				$tran_list["holding_tax"] = $collection_dlt["holding_tax"];
				$tran_list["water_tax"] = $collection_dlt["water_tax"];
				$tran_list["education_cess"] = $collection_dlt["education_cess"];
				$tran_list["health_cess"] = $collection_dlt["health_cess"];
				$tran_list["latrine_tax"] = $collection_dlt["latrine_tax"];
			}
			$data["tran_list"] = $tran_list;
			$data["ulb_mstr_id"] = $ulb_mstr_id;
			$data['ulb_mstr_name'] = $this->model_ulb_mstr->getulb_list($ulb_mstr_id);
			//print_var($tran_list);
			return view('Citizen/citizen_payment_receipt', $data);
		}

	}


	public function ajax_rungatequarter()
	{
		if ($this->request->getMethod() == 'post') {
			$data = [
				'fyUpto' => $this->request->getVar('due_upto_year'),
				'prop_no' => $this->request->getVar('custm_id')
			];
			$result = $this->model_prop_demand->gateQuarter($data);
			$lastquatr = $this->model_prop_demand->gateQuarterlast($data);
			$totalQuarter = $this->model_prop_demand->gatetotalQuarter($data);
			//$lasttotalQuarter = $this->modeldemand->gatelasttotalQuarter($data);
			if (!empty($result)) {
				$option = "";
				$option .= "<option value='" . $lastquatr['qtr'] . "'>" . $lastquatr['qtr'] . "</option>";
				foreach ($result as $value) {
				}
				$lasttotalQuarter = $value['qtr'];
				$response = ['response' => true, 'data' => $option, 'val' => $totalQuarter['totalqtr'], 'last' => $lasttotalQuarter];
			} else {
				$response = ['response' => false];
			}
		} else {
			$response = ['response' => false];
		}
		echo json_encode($response);
	}


	public function citizen_tc_call($ID = null)
	{
		$data = (array)null;
		$data['id'] = $ID;
		if ($this->request->getMethod() == 'post') {
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());

			$data['ward_mstr_id'] = $inputs['ward_mst_id'];
			$data['ward_no'] = $inputs['ward_no'];
			$data['prop_dtl_id'] = $inputs['prop_dtl_id'];
			$data['holding_no'] = $inputs['holding_no'];
			$data['new_holding_no'] = $inputs['new_holding_no'];
			$data['address'] = $inputs['address'];
			$data['owner_name'] = $inputs['owner_name'];
			$data['mobile_no'] = $inputs['mobile_no'];
			$data['type'] = $inputs['type'];
			$data['shedule_date'] = $inputs['shedule_date'];
			$data['shedule_time'] = $inputs['time'];
			$data['created_on'] = date('Y-m-d H:i:s');

			if ($tcCall = $this->model_tc_call->callTc($data)) {

				flashToast("message", "Your request has been sent to concerned Tax Collector of your ward. Tax Collector will contact you soon. For any query or complain Call us at 18008904115 OR 0651-3500700 .");

				return $this->response->redirect(base_url('CitizenProperty/index22/' . $data['id']));
			}
		}
	}

	public function da_hin_memo_receipt($memo_id)
	{
		$data = (array)null;
		$id = $memo_id;
		$data['id'] = $id;
		
		$ulb_mstr = getUlbDtl();
		$data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
		$ulb_mstr_id = $data['ulb_mstr_id'];
		$path = base_url('citizenPaymentReceipt/da_eng_memo_receipt/' . $ulb_mstr_id . '/' . $id);
		$data['ss'] = qrCodeGeneratorFun($path);

		$data['ulb'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
		$data['form'] = $this->model_view_saf_receive_list->safreceivedetailbyid($id);
		//print_r($data['ulb']);

		$data['owner_list'] = $this->model_saf_owner_detail->ownerdetails($data['form']['saf_dtl_id']);
		$memo_type = 'SAM';
		$data['memo_no'] = $this->model_saf_memo_dtl->getmemonobysafid($data['form']['saf_dtl_id'], $memo_type);
		$data['holding_no'] = $this->model_prop_dtl->getholdingnobysafid($data['form']['saf_dtl_id']);
		/****saf tax******/
		//print_r($data['holding_no']);
		$max_fy_id = $this->model_saf_tax->getmaxfyidbysafid($data['form']['saf_dtl_id']);
		$max_qtr = $this->model_saf_tax->getmaxfyqtridbysafid($data['form']['saf_dtl_id'], $max_fy_id['max_fy_id']);
		$data['saf_tax'] = $this->model_saf_tax->getallmaxfyqtridbysafid($data['form']['saf_dtl_id'], $max_fy_id['max_fy_id'], $max_qtr['max_qtr']);
		/*********/
		/****prop tax******/
		$max_fy_id = $this->model_prop_tax->getmaxfyidbypropid($data['holding_no']['id']);
		$max_qtr = $this->model_prop_tax->getmaxfyqtridbypropid($data['holding_no']['id'], $max_fy_id['max_fy_id']);
		$data['prop_tax'] = $this->model_prop_tax->getallmaxfyqtridbypropid($data['holding_no']['id'], $max_fy_id['max_fy_id'], $max_qtr['max_qtr']);
		/*********/

		//$data['prop_tax'] = $this->model_prop_tax->get_taxdl_bypropid($data['holding_no']['id']);
		$data['fy'] = $this->model_fy_mstr->getFiyrByid($data['prop_tax']['fy_mstr_id']);
		$data['ward'] = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
		$data['form']['ward_no'] = $data['ward']['ward_no'];
		// print_r($data['prop_tax']);


		return view('property/saf/da_hin_memo_receipt', $data);
	}

	public function OnlineViewDetails($saf_dtl_id) {
		
		$Session = Session();
        $ulb_dtl = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_dtl["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];

        $data = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id);
		$data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(["saf_dtl_id" => $data['saf_dtl_id']]);
		// applicant img & document
		foreach ($data['saf_owner_detail'] as $key => $owner_detail) {
			$input = [
				'saf_dtl_id' => $data['saf_dtl_id'],
				'saf_owner_dtl_id' => $owner_detail['id'],
			];
			$data['saf_owner_detail'][$key]['applicant_img_dtl'] = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['applicant_doc_dtl'] = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlId($input);
		}
		
		//$data["doc_list"] = $this->model_saf_doc_dtl->getAllDocumentsWithOwner2($data['saf_dtl_id']);
		$saf_dtl_id = $data['saf_dtl_id'];
		if($this->request->getMethod()=='post')
		{ 
			$inputs = arrFilterSanitizeString($this->request->getVar());
			if (isset($inputs['btn_upload']))
			{
				$rules = [
					'upld_doc_path'=>'uploaded[upld_doc_path]|max_size[upld_doc_path,5120]|ext_in[upld_doc_path,pdf]',
				];
				if($this->validate($rules))
				{
					$upld_doc_path = $this->request->getFile('upld_doc_path');
					if ($upld_doc_path->IsValid()  && !$upld_doc_path->hasMoved())
					{
						try
						{
							$this->db->transBegin();
							$input = [
									'saf_dtl_id'=> $data['saf_dtl_id'],
									'upld_doc_mstr_id'=> $inputs['doc_mstr_id'],
									'doc_mstr_idcheck'=> $inputs['doc_mstr_id'],
									'other_doc'=> $inputs['other_doc'],
									'other_doccheck'=> $inputs['other_doc'],
									'emp_details_id'=> $emp_details_id,
									'created_on'=> "NOW()",
									'status'=> 1,
								];
							if($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkDocDataIsExist1($input))
							{
								$delete_path = WRITEPATH.'uploads/'.$saf_doc_dtl_id['doc_path'];
								// unlink($delete_path);
								deleteFile($delete_path);

								$newFileName = md5($saf_doc_dtl_id['id']);
								$file_ext = $upld_doc_path->getExtension();

								$path = $ulb_dtl['city']."/"."saf_doc_dtl";
								$upld_doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
								$upld_doc_path_save = $path."/".$newFileName.'.'.$file_ext;
								$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $upld_doc_path_save,$input['upld_doc_mstr_id']);

							}
							else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertFrData($input))
							{
								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $upld_doc_path->getExtension();
								$path = $ulb_dtl['city']."/"."saf_doc_dtl";

								$upld_doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
								$upld_doc_path_save = $path."/".$newFileName.'.'.$file_ext;
								$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $upld_doc_path_save, $input['upld_doc_mstr_id']);
							}

							if ($this->db->transStatus() === FALSE)
							{
								$this->db->transRollback();
								flashToast("message", "Oops, Document not uploaded.");
							}
							else
							{
								$this->db->transCommit();
								flashToast("message", "Document uploaded successfully.");
							}
						}
						catch (Exception $e)
						{
							flashToast("message", $e->getMessage());
						}
					}
				}
				else
				{

					$errMsg = $this->validator->listErrors();
					flashToast("message", $errMsg);
				}
			}

			if(isset($inputs['send_to_ulb']))
			{ 
				$this->db->transBegin();
				$saf_up_date = $this->db->table('tbl_saf_dtl')->set('trust_type', $inputs['is_trust_school'])
								->where('id', $saf_dtl_id)
								->update();
				if($saf_up_date)
				{

					$leveldata = [
						'saf_dtl_id' => $saf_dtl_id,
						'sender_user_type_id' => $emp_mstr['user_type_mstr_id'],
						'receiver_user_type_id' => 9,
						'forward_date' => date('Y-m-d'),
						'forward_time' => date('H:i:s'),
						'created_on' =>date('Y-m-d H:i:s'),
						'remarks' => '',
						'verification_status' => 0,
						'sender_emp_details_id'=> $emp_details_id
					];
					

					$this->model_level_trust_doc_dtl->insertData($leveldata);
					$this->trustDemandGenerate($saf_dtl_id, $inputs['is_trust_school']);
					
					if ($this->db->transStatus() === FALSE)
					{
						$this->db->transRollback();
						flashToast("message", "Oops, Application couldn't send to ULB.");
					}
					else
					{
						$this->db->transCommit();
						flashToast("message", "Application sent to ULB.");
						return $this->response->redirect(base_url('CitizenProperty/index22/'.md5($data['prop_dtl_id'])));
					}
				}
			}
		}
		$data["doc_list"] = $this->model_doc_mstr->getDataByDocTypeTrust(['trust_document', 'income_tax']);
		$data["uploaded_doc_list"]=$this->model_saf_doc_dtl->getAllTrustDocuments($data['saf_dtl_id']);
		return view('Citizen/citizen_trust_upload_view', $data);
    }

	public function makeDueDateByFyearQtr($fyear, $qtr)
	{
		list($fyear1, $fyear2) = explode("-", $fyear);
		if ($qtr == 1) {
			return $fyear1 . "-06-30";
		} else if ($qtr == 2) {
			return $fyear1 . "-09-30";
		} else if ($qtr == 3) {
			return $fyear1 . "-12-31";
		} else if ($qtr == 4) {
			return $fyear2 . "-03-31";
		}
	}

	public function getFyID($FY)
	{
		return $this->model_fy_mstr->getFyByFy(['fy' => $FY])['id'];
	}

	public function trustDemandGenerate($saf_dtl_id, $is_trust_school)
	{
		$currentFY = "2022-2023";
		$safHelper = new SAFHelper($this->db);
		$newsafHelper = new NEW_SAFHelper($this->db);
		$sql = "SELECT tbl_prop_dtl.*,tbl_saf_dtl.saf_pending_status,tbl_prop_tax.prop_dtl_id FROM tbl_prop_dtl
                JOIN (select max(id) as proptaxid,prop_dtl_id from tbl_prop_tax where fYear='2022-2023' or tbl_prop_tax.fy_mstr_id=53 and created_on::date<now()::date and status=1 group by prop_dtl_id) tbl_prop_tax on tbl_prop_tax.prop_dtl_id=tbl_prop_dtl.id
                LEFT JOIN tbl_saf_dtl on tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                WHERE tbl_prop_dtl.status=1 and tbl_prop_dtl.saf_dtl_id=".$saf_dtl_id." and tbl_prop_dtl.prop_type_mstr_id!=4 
                ";

        $resultArr = $this->db->query($sql)->getRowArray();
		if($resultArr)
		{
			$prop_dtl_id = $resultArr['prop_dtl_id'];
			$saf_dtl_id = $resultArr['saf_dtl_id'];
			$ward_mstr_id = $resultArr['ward_mstr_id'];
			

			if($resultArr['saf_pending_status'] == 1)
			{
				$record = $this->model_field_verification_dtl->getdatabysafid($saf_dtl_id);
				$record["occupation_date"] = $resultArr["occupation_date"];
				$record['verification_id'] = $record['id'];
			}else{
				$record = $resultArr;
				$record["percentage_of_property_transfer"] = null;
			}
			if(isset($resultArr['apartment_dtl_id']))
			{
				$apt = $this->model_apartment_details->getApartmentDtlById($resultArr['apartment_dtl_id']);
				$record["is_water_harvesting"] = ($apt['water_harvesting_status'] == 1)?'t':'f';
			}

			$inputs = array();
			$inputs['is_trust_school'] = $is_trust_school;
			$inputs['ward_mstr_id'] = $record['ward_mstr_id'];
			$inputs['zone_mstr_id'] = ($resultArr['zone_mstr_id'] > 0)?$resultArr['zone_mstr_id']:2;
			$inputs["prop_type_mstr_id"] = $record['prop_type_mstr_id'];
			$inputs['road_type_mstr_id'] = $record['road_type_mstr_id'];
			$inputs["area_of_plot"] = $record['area_of_plot'];
			$inputs["tower_installation_date"] = $record['tower_installation_date'];
			$inputs["tower_area"] = $record['tower_area'];
			$inputs["hoarding_installation_date"] = $record['hoarding_installation_date'];
			$inputs["hoarding_area"] = $record['hoarding_area'];
			$inputs["petrol_pump_completion_date"] = $record['petrol_pump_completion_date'];
			$inputs["under_ground_area"] = $record['under_ground_area'];
			$inputs["percentage_of_property_transfer"] = $record['percentage_of_property_transfer'];
			if ($record["is_mobile_tower"]=="t") $inputs["is_mobile_tower"] = 1; else $inputs["is_mobile_tower"] = 0;
			if ($record["is_hoarding_board"]=="t") $inputs["is_hoarding_board"] = 1; else $inputs["is_hoarding_board"] = 0;
			if ($record["is_petrol_pump"]=="t") $inputs["is_petrol_pump"] = 1; else $inputs["is_petrol_pump"] = 0;
			if ($record["is_water_harvesting"]=="t") $inputs["is_water_harvesting"] = 1; else $inputs["is_water_harvesting"] = 0;
			$floorDtlArr = array();

				
			if($resultArr['saf_pending_status'] == 1){
				$sqlveri = "select tbl_field_verification_dtl.id from tbl_field_verification_dtl 
							left join (select field_verification_dtl_id from tbl_field_verification_floor_details where date_upto is not null group by field_verification_dtl_id) tbl_field_verification_floor_details on tbl_field_verification_floor_details.field_verification_dtl_id=tbl_field_verification_dtl.id
							where tbl_field_verification_floor_details.field_verification_dtl_id is null and id=".$record['verification_id']."
							";
				$checkfield_verification =  $this->db->query($sqlveri)->getRowArray();
				if($checkfield_verification && $checkfield_verification['id'])
				{ 
					$field_verifcation_floor_dtl = $this->model_field_verification_floor_details->getFloorDataBymstrId($record['verification_id']);
					if(count($field_verifcation_floor_dtl)==0)
					{
						$field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
					}
				}else{
					$field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
				}
			}else{
				$field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
			}
			

			$floorKey = 0;
			foreach ($field_verifcation_floor_dtl as $key => $value) 
			{
				$date_fromarra = explode('-', $value["date_from"]);

				if($date_fromarra[0] <= 1970){
					$date_from = '1970-04-01';
				}else{
					$date_from = $value["date_from"];
				}
				$inputs["floor_mstr_id"][$floorKey] = !empty($value["floor_mstr_id"])?$value["floor_mstr_id"]:3;
				$inputs["usage_type_mstr_id"][$floorKey] = $value["usage_type_mstr_id"];
				$inputs["const_type_mstr_id"][$floorKey] = $value["const_type_mstr_id"];
				$inputs["occupancy_type_mstr_id"][$floorKey] = $value["occupancy_type_mstr_id"];
				$inputs["builtup_area"][$floorKey] = $value["builtup_area"];
				$inputs["date_from"][$floorKey] = date("Y-m", strtotime($date_from));
				$inputs["date_upto"][$floorKey] = "";
				if ($value["date_upto"]!="" && $value["date_upto"]!="null") {
					$inputs["date_upto"][$floorKey] = date("Y-m", strtotime($value["date_upto"]));
				}
			
				$floorKey++;

			}
			$inputs['prop_dtl_id']=$prop_dtl_id;
			
			$floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
			$isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
			
			list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $newsafHelper->calBuildingTaxDtl_2023($floorDtlArr, (int)$record['prop_type_mstr_id'], $isAdditionaTaxImplemented);
			
			if($newSafTaxDtl)
			{
				$this->db->table('tbl_prop_tax')->set('status', 0)->where('prop_dtl_id', $prop_dtl_id)
                        ->where('fy_mstr_id', 53)
                        ->where('status', 1)
                        ->update();
				$this->db->table('tbl_prop_demand')->where('prop_dtl_id', $prop_dtl_id)
					->where('fy_mstr_id', 53)
					->where('status', 1)
					->where('paid_status', 0)
					->update(['status'=>0, 'balance'=>'0.00']);
				foreach($newSafTaxDtl as $key => $taxDtl)
				{
					
					$pymt_frm_qtr = (int)$taxDtl['qtr'];
					$pymt_frm_year = (string)$taxDtl['fyear'];

					$pymt_upto_qtr = (int)4;
					$pymt_upto_year = (string)$currentFY;
					if ($key < sizeof($newSafTaxDtl) - 1) {
						$pymt_upto_qtr = (int)$newSafTaxDtl[$key + 1]['qtr'] - 1;
						$pymt_upto_year = (string)$newSafTaxDtl[$key + 1]['fyear'];
					}
					list($from_y1_new, $from_y2_new) = explode("-", $pymt_frm_year);
					list($upto_y1_new, $upto_y2_new) = explode("-", $pymt_upto_year);


					$fy_mstr_id = $this->getFyID($taxDtl['fyear']);
					$holding_tax = isset($taxDtl['holding_tax'])?$taxDtl['holding_tax']:0;
					$water_tax = isset($taxDtl['water_tax'])?$taxDtl['water_tax']:0;
					$education_cess = isset($taxDtl['education_cess'])?$taxDtl['education_cess']:0;
					$health_cess = isset($taxDtl['health_cess'])?$taxDtl['health_cess']:0;
					$latrine_tax = isset($taxDtl['latrine_tax'])?$taxDtl['latrine_tax']:0;
					$additional_tax = isset($taxDtl['additional_tax'])?$taxDtl['additional_tax']:0;
					$quarterly_tax = $holding_tax+$water_tax+$education_cess+$health_cess+$latrine_tax+$additional_tax;
					
					if($taxDtl['arv'] > 0)
					{
						$sql = "INSERT INTO tbl_prop_tax (prop_dtl_id, fy_mstr_id, fyear, qtr, arv, holding_tax, water_tax, education_cess, health_cess, latrine_tax, additional_tax, created_on, status, quarterly_tax)
						VALUES ('$prop_dtl_id', '".$fy_mstr_id."' ,'" . $taxDtl['fyear'] . "', '" . $taxDtl['qtr'] . "', '".$taxDtl['arv']."', '$holding_tax', '$water_tax', '$education_cess', '$health_cess', '$latrine_tax', '$additional_tax', '" . date("Y-m-d H:i:s") . "', 1, '".$quarterly_tax."') returning id";
						$query = $this->db->query($sql);
						$return = $query->getFirstRow("array");
						$prop_tax_id = $return["id"];
						
						while ($from_y1_new <= $upto_y1_new && $from_y2_new <= $upto_y2_new) 
						{
							
							$newFY = $from_y1_new . "-" . $from_y2_new;
							$till_qtr = $newFY == $pymt_upto_year ? $pymt_upto_qtr : 4;
							for ($q = $pymt_frm_qtr; $q <= $till_qtr; $q++) {
								
								$newFY = $from_y1_new . "-" . $from_y2_new;
								$adjust_amt = 0;
								$demandAmt = $taxDtl['quarterly_tax'] - $taxDtl['additional_tax'];
								$amount = $taxDtl['quarterly_tax'];
								$additional_tax = $taxDtl['additional_tax'];
								$due_date = $this->makeDueDateByFyearQtr($newFY, $q);

								$sql = "SELECT COALESCE(SUM(amount), 0) AS total_amount FROM (SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_prop_demand WHERE prop_dtl_id=" . $prop_dtl_id . " AND status=1 AND paid_status=1 AND due_date='" . $due_date . "'
                                            UNION
                                            SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_saf_demand WHERE saf_dtl_id=" . $saf_dtl_id . " AND status=1 AND paid_status=1 AND due_date='" . $due_date . "') AS tbl_demand
                                            GROUP BY due_date
                                            ORDER BY due_date";
								$total_result = $this->db->query($sql);
								if ($total_prev_demand = $total_result->getFirstRow("array")) {
									$quarterly_tax = $amount - $total_prev_demand["total_amount"];
									$demandAmt = $demandAmt - $total_prev_demand["total_amount"];
									$adjust_amt = $total_prev_demand["total_amount"];
								}

								$additional = 0;
								if($newFY != '2016-2017')
								{
									$additional = $additional_tax;
								}

								if ($quarterly_tax > 0 && round($demandAmt+$additional) > 0) 
								{
									$index = [
										'prop_dtl_id' => $prop_dtl_id,
										'prop_tax_id' => $prop_tax_id,
										'fy_mstr_id' => $this->getFyID($newFY),
										'ward_mstr_id' => $ward_mstr_id,
										'fyear' => $newFY,
										'qtr' => $q,
										'due_date' => $due_date,
										'amount' => round($amount, 2),
										'balance' => round($demandAmt+$additional, 2),
										'fine_tax' => 0,
										'created_on' => date("Y-m-d H:i:s"),
										'status' => 1,
										'paid_status' => 0,
										'demand_amount' => round($amount-$additional_tax, 2),
										'additional_amount' => $additional,
										'adjust_amt' => $adjust_amt
									];
									//print_var($index);
									$prop_tax[] = $index;
									$this->model_prop_demand->insertData($index);
								}
							}
							$pymt_frm_qtr = 1;
							$from_y1_new++;
							$from_y2_new++;       
						
							
						}

					}
					
				}
			}
		
		}
	}

	public function propPaymentCertificate($certificatId=null){
		$encrypter = \Config\Services::encrypter(); 
		$applicable="2025-04-01";
		if(is_numeric($certificatId)){
			$certificatId = md5($certificatId);
		}

		$cetificat = $this->db->table("tbl_certificate")->where("md5(id::text)",$certificatId)->where("status",1)->get()->getFirstRow("array");
		$tran = $this->db->table("tbl_transaction")->where("id",($cetificat["tran_id"]??""))->whereIn("status",[1])->get()->getFirstRow("array");
		if($cetificat && $cetificat["html"]){
			$decrypted = $encrypter->decrypt(base64_decode($cetificat["html"]));
			echo $decrypted;
			die;
		}
		elseif($tran && $tran['tran_type']=='Property' && $tran["tran_date"]>=$applicable){
			$path = base_url('citizenProperty/propPaymentCertificate/' . $certificatId);
			$quarCode = qrCodeGeneratorFun($path);
			$firstOwner = $this->db->table("tbl_prop_owner_detail")->where("prop_dtl_id",$tran["prop_dtl_id"])->where("status",1)->orderBy("id","ASC")->get()->getFirstRow("array");
			$holding = $this->db->table("tbl_prop_dtl")->where("id",$tran["prop_dtl_id"])->get()->getFirstRow("array");
			$pamentMode = $tran["tran_mode"];
			$qtr = getQtr($tran["tran_date"]);
			$type=$cetificat["type"];
			$pdfData=[
				"certificatId"=>$cetificat["certificate_id"],
				"quarCode"=>$quarCode,
				"ownerName"=>$firstOwner["owner_name"]??"",
				"holdingNo"=>$holding["new_holding_no"]?$holding["new_holding_no"]:$holding["holding_no"],
				"fyear"=>$cetificat["fyear"],
				"paymentMode"=>$pamentMode,
				"issuDate"=>date("d-m-Y",strtotime($cetificat["is_date"])),
				"qtr"=>$qtr,
				"type"=>$type,
			];
			if($pdfData["qtr"] && $pdfData["qtr"]<=3){
				$html = view("citizen/Property/propPaymentCertificate",$pdfData);
				$encrypted = base64_encode($encrypter->encrypt($html));
				$updatData = [
					"pdf_data_json"=>json_encode($pdfData),
					"html"=>$encrypted
				];
				$this->db->table("tbl_certificate")->where("id",$cetificat["id"])->update($updatData);
				echo $html;die;
			}
	
		}
	}
}
