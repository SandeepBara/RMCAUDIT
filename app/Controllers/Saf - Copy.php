<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_ward_mstr;
use App\Models\model_view_ward_mapping_mstr;
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
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_demand;
use App\Models\model_payment_adjust;
use App\Models\model_saf_distributed_dtl;
use App\Models\model_transaction;
use Exception;

class Saf extends AlphaController
{
	protected $db;
	protected $dbSystem;
	protected $model_ulb_mstr;
	protected $model_fy_mstr;
	protected $model_ward_mstr;
	protected $model_view_ward_mapping_mstr;
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
	protected $model_prop_dtl;
	protected $model_prop_owner_detail;
	protected $model_prop_demand;
	protected $model_payment_adjust;
	protected $model_saf_distributed_dtl;
	protected $model_transaction;

    public function __construct(){
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
		$this->model_view_ward_mapping_mstr = new model_view_ward_mapping_mstr($this->dbSystem);
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
		$this->model_prop_dtl = new model_prop_dtl($this->db);
		$this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
		$this->model_prop_demand = new model_prop_demand($this->db);
		$this->model_payment_adjust = new model_payment_adjust($this->db);
		$this->model_saf_distributed_dtl = new model_saf_distributed_dtl($this->db);
		$this->model_transaction = new model_transaction($this->db);
    }

	public function backOfficeSAFUpdate($ID=NULL) {
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$emp_details_id = $_SESSION['emp_details']['id'];

		if ($this->request->getMethod()=='post') {
            try {
				$inputs = arrFilterSanitizeString($this->request->getVar());
				$inputs['saf_dtl_id'] = $ID;
				if ($this->model_saf_dtl->updateSAFDtlByBackOffice($inputs) ) {
					if (isset($inputs['is_corr_add_differ'])) {
						//$inputs['is_corr_add_differ'] = true;
						$inputs['corr_address'] = $inputs['corr_address'];
						$inputs['corr_city'] = $inputs['corr_city'];
						$inputs['corr_dist'] = $inputs['corr_dist'];
						$inputs['corr_state'] = $inputs['corr_state'];
						$inputs['corr_pin_code'] = $inputs['corr_pin_code'];
					} else {
						//$inputs['is_corr_add_differ'] = false;
						$inputs['corr_address'] = $inputs['prop_address'];
						$inputs['corr_city'] = $inputs['prop_city'];
						$inputs['corr_dist'] = $inputs['prop_dist'];
						$inputs['corr_state'] = $inputs['prop_state'];
						$inputs['corr_pin_code'] = $inputs['prop_pin_code'];
					}
					$this->model_saf_dtl->updateSAFDtlCorrAddByBackOffice($inputs);

					if (isset($inputs['is_transaction']) && $inputs['is_transaction']==0 && isset($inputs['owner_name'])) {
						for($i=0; $i<sizeof($inputs['owner_name']); $i++) {
							$input = [
								'saf_dtl_id'=>$ID,
								'saf_owner_detail_id'=>$inputs['saf_owner_detail_id'][$i],
								'owner_name'=>$inputs['owner_name'][$i],
								'guardian_name'=>$inputs['guardian_name'][$i],
								'relation_type'=>($inputs['relation_type'][$i]!="")?$inputs['relation_type'][$i]:null,
								'mobile_no'=>($inputs['mobile_no'][$i]!="")?$inputs['mobile_no'][$i]:null,
								'email'=>($inputs['email'][$i]!="")?$inputs['email'][$i]:null,
								'pan_no'=>($inputs['pan_no'][$i]!="")?$inputs['pan_no'][$i]:null,
								'aadhar_no'=>($inputs['aadhar_no'][$i]!="")?$inputs['aadhar_no'][$i]:null
							];
							$this->model_saf_owner_detail->updateSAFOwnerDtlByBackOffice($input);
						}
					}

					flashToast('saf_due_details', "Update Successfully.");
					return redirect()->to(base_url('safDemandPayment/saf_due_details/'.$ID));

				} else {
					flashToast('saf_back_office_update', "Oops! Something went wrong. Error");
					return redirect()->to(base_url('saf/backOfficeSAFUpdate/'.$ID));
				}
			} catch (Exception $e) {
				flashToast('saf_back_office_update', "Oops! Something went wrong. Error");
				return redirect()->to(base_url('saf/backOfficeSAFUpdate/'.$ID));
			}
		} else {
			$ulb_address = $this->model_ulb_mstr->getAddressById(['ulb_mstr_id'=>$ulb_mstr_id]);
			$wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
			$transferModeList = $this->model_transfer_mode_mstr->getTransferModeList();
			$ownershipTypeList = $this->model_ownership_type_mstr->getOwnershipTypeList();
			$propTypeList = $this->model_prop_type_mstr->getPropTypeList();
			$roadTypeList = $this->model_road_type_mstr->getRoadTypeList();
			$floorList = $this->model_floor_mstr->getFloorList();
			$usageTypeList = $this->model_usage_type_mstr->getUsageTypeList();
			$occupancyTypeList = $this->model_occupancy_type_mstr->getOccupancyTypeList();
			$constTypeList = $this->model_const_type_mstr->getConstTypeList();

			if ($data = $this->model_saf_dtl->getSafDtlByMd5ID($ID) ) {

				if ($owner_dtl_list = $this->model_saf_owner_detail->getOwnerdtlBySAFId(['saf_dtl_id' => $data['id']]) ) {
					$data['owner_dtl_list'] = $owner_dtl_list;
				}
				if($data['prop_type_mstr_id'] != 4) {
					if ($floor_dtl_list = $this->model_saf_floor_details->getDataBySafDtlId(['saf_dtl_id' => $data['id']]) ) {
						$data['floor_dtl_list'] = $floor_dtl_list;
					}
				}

				$data['is_transaction'] = false;
				if ($floor_dtl_list = $this->model_transaction->checkSafDtlIsPaymentOrNot(['prop_dtl_id' => $data['id']]) ) {
					$data['is_transaction'] = true;
				}

				$data['ulb_address'] = $ulb_address;
				$data['wardList'] = $wardList;
				$data['transferModeList'] = $transferModeList;
				$data['ownershipTypeList'] = $ownershipTypeList;
				$data['roadTypeList'] = $roadTypeList;
				$data['propTypeList'] = $propTypeList;
				$data['floorList'] = $floorList;
				$data['usageTypeList'] = $usageTypeList;
				$data['occupancyTypeList'] = $occupancyTypeList;
				$data['constTypeList'] = $constTypeList;

				return view('property/saf/saf_back_office_update', $data);
			}
		}

	}

	public function searchSafDtl()
	{
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];

		$ulb_address = $this->model_ulb_mstr->getAddressById(['ulb_mstr_id'=>$ulb_mstr_id]);
		$wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
		if ($this->request->getMethod()=='post') {
            try
            {
				$inputs = arrFilterSanitizeString($this->request->getVar());
				$data = $inputs;
				$data['wardList'] = $wardList;
				if ($inputs['saf_no'] == "")
				{
					$input = [
						'ward_mstr_id'=>$inputs['ward_mstr_id'],
						'owner_name'=>$inputs['owner_name'],
						'mobile_no'=>$inputs['mobile_no']
					];
					if ($searchList = $this->model_saf_owner_detail->joinSafDtlByNameMobile($input))
					{
						$data['searchList'] = $searchList;
					}
					else
					{
						$data['errors'] = "Data not found !!!";
					}
				}
				else
				{
					$input = [
						'ward_mstr_id'=>$inputs['ward_mstr_id'],
						'saf_no'=>$inputs['saf_no']
					];
					if ($searchList = $this->model_saf_dtl->joinSafDtlBySafNo($input))
					{
						$data['searchList'] = $searchList;
					}
					else
					{
						$data['errors'] = "Data not found !!!";
					}
				}
				return view('property/saf/saf_search_for_update', $data);
            } catch(Exception $e) {
                echo $e->getMessage();
            }
        } else {
			$data['wardList'] = $wardList;
			return view('property/saf/saf_search_for_update', $data);
		}
	}


	public function searchDistributedDtl($ID = NULL){
		if (!is_null($ID)) {
			$input = [
				'saf_distributed_dtl_id'=>$ID
			];
			if ($results = $this->model_saf_distributed_dtl->getDetailsBySafDistributedDtlId($input)) {
				flashToast('saf_no_encrypted', $results['saf_no']);
				return redirect()->to(base_url('saf/addupdate'));
			} else {
				$data['validation']['err'] = " SAF does't exist !!!";
				return view('property/saf/saf_search_distributed_dtl', $data);
			}
		} else {
			if ($this->request->getMethod()=='post') {
				try {
					$inputs = arrFilterSanitizeString($this->request->getVar());
					$data = $inputs;
					$input = [
						'saf_no'=>$inputs['saf_no']
					];
					if ($results = $this->model_saf_distributed_dtl->getDetailsBySAFNo($input)) {

						$input = ['saf_no'=>$results['saf_no']];
						if ( !$this->model_saf_dtl->checkSafNoExistOrNot($input)) {
							$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
							$emp_details_id = $_SESSION['emp_details']['id'];
							$wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
							$data['wardList'] = $wardList;
							$data['saf_distributed_dtl_list'] = $results;
						} else {
							$data['validation']['err'] = $inputs['saf_no']." = Already entry !!!";
						}
					} else {
						$data['validation']['err'] = $inputs['saf_no']." = does't exist !!!";
					}
					return view('property/saf/saf_search_distributed_dtl', $data);
				} catch(Exception $e) {
					echo $e->getMessage();
				}
			} else {
				return view('property/saf/saf_search_distributed_dtl');
			}
		}
	}
    public function addUpdateSubmit($inputs) {
		try{
			$this->db->transBegin();

			$ulb_mstr_id = $inputs['ulb_mstr_id'];

			$created_on = $updated_on = date('Y-m-d H:i:s');

			$isReassessment = false;
			$prop_dtl_id = 0;
			$prop_entry_type = "";
			$old_saf_dtl_id = 0;

			$assessment_type = "New Assessment";
			if ( $inputs["has_previous_holding_no"]==1 && $inputs["is_owner_changed"]==0 ) {
				$assessment_type = "Reassessment";
			} else if( $inputs["has_previous_holding_no"]==1 && $inputs["is_owner_changed"]=="" ) {
				$assessment_type = "Reassessment";
			} else if( $inputs["has_previous_holding_no"]==1 && $inputs["is_owner_changed"]==1 ) {
				$assessment_type = "Mutation";
			}


			if ( $inputs["has_previous_holding_no"]==1 && $inputs["is_owner_changed"]==0 ) {
				$input = ['holding_no'=>$inputs['previous_holding_no']];
				if($prop_dtl = $this->model_prop_dtl->getPropIdByHodingNoEntryType($input)) {
						$prop_dtl_id = $prop_dtl['id'];
						$prop_entry_type = $prop_dtl['entry_type'];
						if($saf_dtl = $this->model_saf_dtl->getSafIdByPropId(['prop_dtl_id'=>$prop_dtl_id])) {
							$old_saf_dtl_id = $saf_dtl['id'];
						}
				}
				$isReassessment = true;
			}

			$saf_distributed_dtl_id = 0;
			if ($inputs['saf_no']=="") {
				$owner_name = "";
				$phone_no = "";
				if ( $inputs['has_previous_holding_no']==0 || ($inputs['has_previous_holding_no']==1 && $inputs['is_owner_changed']==1) ) {
					for ( $i=0; $i < 1; $i++ ) {
						$owner_name = $inputs['owner_name'][$i];
						$phone_no = $inputs['mobile_no'][$i];
					}
				} else {
					for($i=0; $i < 1; $i++){
						$owner_name = $inputs['prev_owner_name'][$i];
						$phone_no = $inputs['prev_mobile_no'][$i];
					}
				}

				$input = [
					"saf_no"=>"",
					"ward_mstr_id"=>$inputs["ward_mstr_id"],
					"owner_name"=>$owner_name,
					"phone_no"=>$phone_no,
					"owner_address"=>$inputs['prop_address'],
					"survey_by_emp_details_id"=>0,
					"created_on"=>$created_on,
					"status"=>'1'
				];
				$saf_distributed_dtl_id = $this->model_saf_distributed_dtl->insertData($input);
				$wardNo = $this->model_ward_mstr->getWardNoById(['ulb_mstr_id'=>$ulb_mstr_id, 'ward_mstr_id'=>$inputs['ward_mstr_id']])['ward_no'];
				//$url_short_name = $this->model_ulb_mstr->getulb_list($_SESSION['ulb_dtl']['ulb_mstr_id'])['short_ulb_name'];
				$saf_no = "SAF".$wardNo.$saf_distributed_dtl_id.date("s");
				$inputs['saf_no'] = $saf_no;
				$input = [
					'saf_distributed_dtl_id'=>$saf_distributed_dtl_id,
					'saf_no'=>$saf_no,
				];
				$this->model_saf_distributed_dtl->updateSafNoById($input);
			}else{
				$saf_distributed_dtl_id = $this->model_saf_distributed_dtl->getDetailsBySAFNo(['saf_no'=>$inputs['saf_no']])['id'];
			}

			$holding_type = "";
			if ($inputs['prop_type_mstr_id']==4) {
				if ( $inputs['ownership_type_mstr_id']==1 ) {
					$holding_type = "PURE_RESIDENCIAL";
				} else if ( $inputs['ownership_type_mstr_id']==6
							|| $inputs['ownership_type_mstr_id']==7
							|| $inputs['ownership_type_mstr_id']==8
							|| $inputs['ownership_type_mstr_id']==9 ) {
					$holding_type = "PURE_GOVERNMENT";
				} else if ( $inputs['ownership_type_mstr_id']==3
							|| $inputs['ownership_type_mstr_id']==4 ) {
					$holding_type = "PURE_RELIGIOUS";
				} else if ( $inputs['ownership_type_mstr_id']==5
							|| $inputs['ownership_type_mstr_id']==10
							|| $inputs['ownership_type_mstr_id']==11
							|| $inputs['ownership_type_mstr_id']==12
							|| $inputs['ownership_type_mstr_id']==13
							|| $inputs['ownership_type_mstr_id']==14
							|| $inputs['ownership_type_mstr_id']==15 ) {
					$holding_type = "PURE_COMMERCIAL";
				}
			} else {
				if ( $inputs['ownership_type_mstr_id']==3 || $inputs['ownership_type_mstr_id']==4 ) {
					$holding_type = "PURE_RELIGIOUS";
				} else {
					$RESIDENCIAL = false;
					$COMMERCIAL = false;
					$GOVERNMENT = false;
					for($i=0; $i<sizeof($inputs['floor_mstr_id']); $i++){
						if ( $inputs['usage_type_mstr_id'][$i]==1 ) {
							$RESIDENCIAL = true;
						} else if ( $inputs['usage_type_mstr_id'][$i]==7
									|| $inputs['usage_type_mstr_id'][$i]==9) {
							$GOVERNMENT = true;
						} else if ( $inputs['usage_type_mstr_id'][$i]==2
									|| $inputs['usage_type_mstr_id'][$i]==3
									|| $inputs['usage_type_mstr_id'][$i]==4
									|| $inputs['usage_type_mstr_id'][$i]==5
									|| $inputs['usage_type_mstr_id'][$i]==6
									|| $inputs['usage_type_mstr_id'][$i]==8
									|| $inputs['usage_type_mstr_id'][$i]==10
									|| $inputs['usage_type_mstr_id'][$i]==11
									|| $inputs['usage_type_mstr_id'][$i]==12
									|| $inputs['usage_type_mstr_id'][$i]==13
									|| $inputs['usage_type_mstr_id'][$i]==14
									|| $inputs['usage_type_mstr_id'][$i]==15 ) {
							$COMMERCIAL = true;
						}
					}

					if( $RESIDENCIAL==true && $GOVERNMENT==false && $COMMERCIAL==false ) {
						$holding_type = "PURE_RESIDENCIAL";
					} else if( $RESIDENCIAL==false && $GOVERNMENT==false && $COMMERCIAL==true ) {
						$holding_type = "PURE_COMMERCIAL";
					} else if( $RESIDENCIAL==false && $GOVERNMENT==true && $COMMERCIAL==false ) {
						$holding_type = "PURE_GOVERNMENT";
					} else if( ($RESIDENCIAL==true || $COMMERCIAL==true) && $GOVERNMENT==true ) {
						$holding_type = "MIX_GOVERNMENT";
					} else if( $RESIDENCIAL==true && $GOVERNMENT==false && $COMMERCIAL==true ) {
						$holding_type = "MIX_COMMERCIAL";
					}
				}
			}

			$input = [
				'apply_date'=>date("Y-m-d"),
				'assessment_type'=>$assessment_type,
				'holding_type'=>$holding_type,
				'has_previous_holding_no'=>$inputs['has_previous_holding_no'],
				'previous_holding_id'=>($inputs['has_previous_holding_no']==1)?$prop_dtl_id:null,
				'is_owner_changed'=>($inputs['is_owner_changed']=="")?null:$inputs['is_owner_changed'],
				'transfer_mode_mstr_id'=>($inputs['is_owner_changed']==1)?$inputs['transfer_mode_mstr_id']:null,
				'saf_no'=>$inputs['saf_no'],
				'holding_no'=>($inputs['has_previous_holding_no']==1)?$inputs['previous_holding_no']:null,
				'saf_distributed_dtl_id'=>$saf_distributed_dtl_id,
				'ward_mstr_id'=>$inputs['ward_mstr_id'],
				'new_ward_mstr_id'=>$inputs['new_ward_mstr_id'],
				'ownership_type_mstr_id'=>$inputs['ownership_type_mstr_id'],
				'prop_type_mstr_id'=>$inputs['prop_type_mstr_id'],
				'zone_mstr_id'=>$inputs['zone_mstr_id'],
				'appartment_name'=>$inputs['appartment_name'],
				'flat_registry_date'=>($inputs['flat_registry_date']=="")?NULL:$inputs['flat_registry_date'],
				'no_electric_connection'=>($inputs['prop_type_mstr_id']==2 && $inputs['no_electric_connection']==true)?true:false,
				'elect_consumer_no'=>null,
				'elect_acc_no'=>null,
				'elect_bind_book_no'=>null,
				'elect_cons_category'=>null,
				'building_plan_approval_no'=>null,
				'building_plan_approval_date'=>null,
				'water_conn_no'=>null,
				'water_conn_date'=>null,
				'khata_no'=>null,
				'plot_no'=>null,
				'village_mauja_name'=>null,
				'road_type_mstr_id'=>$inputs['road_type_mstr_id'],
				'area_of_plot'=>$inputs['area_of_plot'],
				'prop_address'=>$inputs['prop_address'],
				'prop_city'=>$inputs['prop_city'],
				'prop_dist'=>$inputs['prop_dist'],
				'prop_state'=>$inputs['prop_state'],
				'prop_pin_code'=>$inputs['prop_pin_code'],
				'is_corr_add_differ'=>false,
				'corr_address'=>null,
				'corr_city'=>null,
				'corr_dist'=>null,
				'corr_state'=>null,
				'corr_pin_code'=>null,
				'is_mobile_tower'=>$inputs['is_mobile_tower'],
				'tower_area'=>($inputs['is_mobile_tower']==1)?$inputs['tower_area']:0,
				'tower_installation_date'=>($inputs['is_mobile_tower']==1)?$inputs['tower_installation_date']:null,
				'is_hoarding_board'=>$inputs['is_hoarding_board'],
				'hoarding_area'=>($inputs['is_hoarding_board']==1)?$inputs['hoarding_area']:0,
				'hoarding_installation_date'=>($inputs['is_hoarding_board']==1)?$inputs['hoarding_installation_date']:null,
				'is_petrol_pump'=>($inputs['prop_type_mstr_id']==4)?null:$inputs['is_petrol_pump'],
				'under_ground_area'=>($inputs['is_petrol_pump']==1)?$inputs['under_ground_area']:0,
				'petrol_pump_completion_date'=>($inputs['is_petrol_pump']==1)?$inputs['petrol_pump_completion_date']:null,
				'is_water_harvesting'=>($inputs['prop_type_mstr_id']==4)?null:$inputs['is_water_harvesting'],
				'land_occupation_date'=>($inputs['prop_type_mstr_id']==4)?$inputs['land_occupation_date']:null,
				'payment_status'=>0,
				'doc_verify_status'=>0,
				'field_verify_status'=>0,
				'emp_details_id'=>$inputs['emp_details_id'],
				'ip_address'=>$inputs['ip_address'],
				'created_on'=>$created_on,
				'status'=>1
			];
			$saf_dtl_id = $this->model_saf_dtl->insertData($input);

			//$saf_dtl_id = 2;

			if ( $inputs['has_previous_holding_no']==0 || ($inputs['has_previous_holding_no']==1 && $inputs['is_owner_changed']==1) ) {
				for ( $i=0; $i < sizeof($inputs['owner_name']); $i++ ) {
					$input = [
						'saf_dtl_id'=>$saf_dtl_id,
						'owner_name'=>$inputs['owner_name'][$i],
						'guardian_name'=>$inputs['guardian_name'][$i],
						'relation_type'=>$inputs['relation_type'][$i],
						'mobile_no'=>$inputs['mobile_no'][$i],
						'aadhar_no'=>($inputs['aadhar_no'][$i]!="")?$inputs['aadhar_no'][$i]:null,
						'pan_no'=>$inputs['pan_no'][$i],
						'email'=>$inputs['email'][$i],
						'emp_details_id'=>$inputs['emp_details_id'],
						'created_on'=>$created_on,
						'status'=>1
					];
					$this->model_saf_owner_detail->insertData($input);
				}
			} else {
				for($i=0; $i<sizeof($inputs['prev_owner_name']); $i++){
					$input = [
						'saf_dtl_id'=>$saf_dtl_id,
						'owner_name'=>$inputs['prev_owner_name'][$i],
						'guardian_name'=>$inputs['prev_guardian_name'][$i],
						'relation_type'=>$inputs['prev_relation_type'][$i],
						'mobile_no'=>$inputs['prev_mobile_no'][$i],
						'aadhar_no'=>($inputs['prev_aadhar_no'][$i]!="")?$inputs['prev_aadhar_no'][$i]:null,
						'pan_no'=>$inputs['prev_pan_no'][$i],
						'email'=>$inputs['prev_email'][$i],
						'emp_details_id'=>$inputs['emp_details_id'],
						'created_on'=>$created_on,
						'status'=>1
					];
					$this->model_saf_owner_detail->insertData($input);
				}
			}

			if ($inputs['prop_type_mstr_id']!=4) {
				for($i=0; $i<sizeof($inputs['floor_mstr_id']); $i++){
					if ($inputs['usage_type_mstr_id'][$i]==1) {
						$carpet_area = (($inputs['builtup_area'][$i]*70)/100);
					} else {
						$carpet_area = (($inputs['builtup_area'][$i]*80)/100);
					}
					$input = [
						'saf_dtl_id'=>$saf_dtl_id,
						'floor_mstr_id'=>$inputs['floor_mstr_id'][$i],
						'usage_type_mstr_id'=>$inputs['usage_type_mstr_id'][$i],
						'occupancy_type_mstr_id'=>$inputs['occupancy_type_mstr_id'][$i],
						'const_type_mstr_id'=>$inputs['const_type_mstr_id'][$i],
						'builtup_area'=>$inputs['builtup_area'][$i],
						'carpet_area'=>$carpet_area,
						'date_from'=>$inputs['date_from'][$i]."-01",
						'date_upto'=>($inputs['date_upto'][$i]!="")?$inputs['date_upto'][$i]."-01":null,
						'emp_details_id'=>$inputs['emp_details_id'],
						'created_on'=>$created_on,
						'status'=>1
					];
					$this->model_saf_floor_details->insertData($input);
				}
			}
			// ARV & TAX CALCULATION
			if($inputs['prop_type_mstr_id']==4){

				// current fimamcial year
				$currentFY = getFY();
				$currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];

				// date of effect
				$yrOfEffect_16_17_FY = getFY("2016-04-01");
				$yrOfEffect_16_17_FyID = $this->model_fy_mstr->getFyByFy(['fy'=>$yrOfEffect_16_17_FY])['id'];

				// vacant land details
				$vacantlandArea = ($inputs['area_of_plot']*40.5);
				$mobileTowerArea =  $hoardingBoardArea = 0;

				$land_occupation_date = $inputs['land_occupation_date'];

				$isVacantLand = $isMobileTower = $isHoldingBoard = false;
				$vacand_land_qtr = $mobile_tower_qtr = $hoarding_board_qtr = 0;
				// date of effect
				//$yrOfEffectFY = getFY("2016-04-01");
				//$yrOfEffectFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$yrOfEffectFY])['id'];
				$FromFixEffectFyID =  $yrOfEffect_16_17_FyID;



				// acquisition fy
				$acquisitionFY = getFY($inputs['land_occupation_date']);
				$acquisitionFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$acquisitionFY])['id'];
				if ( $yrOfEffect_16_17_FyID > $acquisitionFyID ) {
					$acquisitionFyID = $yrOfEffect_16_17_FyID;
					$inputs['land_occupation_date'] = "2016-04-01";
				}
				if ( $yrOfEffect_16_17_FyID < $acquisitionFyID ) {
					$FromFixEffectFyID = $acquisitionFyID;
				}

				$MM = date("m", strtotime($inputs['land_occupation_date']));
				if($MM>=1 && 3>=$MM){ // X1
					$temp_qtr = 4;
				}else if($MM>=4 && 6>=$MM){ // X4
					$temp_qtr = 1;
				}else if($MM>=7 && 9>=$MM){ // X3
					$temp_qtr = 2;
				}else if($MM>=10 && 12>=$MM){ // X2
					$temp_qtr = 3;
				}else{

				}
				$isVacantLand = true;
				$vacand_land_qtr = $temp_qtr;

				if($inputs['is_mobile_tower']==1){

					$mobileTowerFY = getFY($inputs['tower_installation_date']);
					$mobileTowerFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$mobileTowerFY])['id'];
					if($FromFixEffectFyID>$mobileTowerFyID){
						$mobileTowerFyID = $FromFixEffectFyID;
						$inputs['tower_installation_date'] = "2016-04-01";
					}
					/* if($currentFyID<$mobileTowerFyID){
						$currentFyID = $mobileTowerFyID;
					} */
					$MM = date("m", strtotime($inputs['tower_installation_date']));
					if($MM>=1 && 3>=$MM){ // X1
						$temp_qtr = 4;
					}else if($MM>=4 && 6>=$MM){ // X4
						$temp_qtr = 1;
					}else if($MM>=7 && 9>=$MM){ // X3
						$temp_qtr = 2;
					}else if($MM>=10 && 12>=$MM){ // X2
						$temp_qtr = 3;
					}else{

					}
					$mobileTowerArea = $inputs['tower_area']*0.092903;
					$isMobileTower = true;
					$mobile_tower_qtr = $temp_qtr;
				}
				if($inputs['is_hoarding_board']==1){
					$hoardinBoardFY = getFY($inputs['hoarding_installation_date']);
					$hoardinBoardFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$hoardinBoardFY])['id'];
					if($yrOfEffect_16_17_FyID>$hoardinBoardFyID){
						$hoardinBoardFyID = $FromFixEffectFyID;
						$inputs['hoarding_installation_date'] = "2016-04-01";
					}
					/* if($currentFyID<$hoardinBoardFyID){
						$currentFyID = $hoardinBoardFyID;
					} */
					$MM = date("m", strtotime($inputs['hoarding_installation_date']));
					if($MM>=1 && 3>=$MM){ // X1
						$temp_qtr = 4;
					}else if($MM>=4 && 6>=$MM){ // X4
						$temp_qtr = 1;
					}else if($MM>=7 && 9>=$MM){ // X3
						$temp_qtr = 2;
					}else if($MM>=10 && 12>=$MM){ // X2
						$temp_qtr = 3;
					}else{

					}
					$hoardingBoardArea = $inputs['hoarding_area']*0.092903;
					$isHoldingBoard = true;
					$hoarding_board_qtr = $temp_qtr;
				}

				$getFyList = $this->model_fy_mstr->getFyByIdRangeAsc(['fromId'=>$FromFixEffectFyID, 'toId'=>$currentFyID]);

				$vacantLandDtl = [];
				$isSafVacantLandMH = false;
				$vacantLandMHDtl = [];
				$vacantLandMHDtlIncreament = 0;
				$safTaxDtl = [];
				$safTaxIncreament = 0;

				$mobileTowerOneTimeImpliment = false;
				$hoadingBoardOneTimeImpliment = false;
				foreach ($getFyList as $fyVal) {
					$dateOfEffect = (explode("-", $fyVal['fy'])[1])."-04-01";

					$vacand_land_qtr_temp = $mobile_tower_qtr_temp = $hoardin_board_qtr_temp = 0;
					$isVacantLandTemp = $isMobileTowerTemp = $isHoldingBoardTemp = false;
					$isMobileTowerIncreaseTemp = $isHoldingBoardIncreaseTemp = false;

					$isExist = false;
					$totalTax = 0;

					$lastArvDtl = [];
					$lastIncreament = -1;

					// vacand land
					if($fyVal['id']==$acquisitionFyID){
						$isVacantLandTemp = true;
						$vacand_land_qtr_temp = $vacand_land_qtr;
					}
					// mobile tower
					if($isMobileTower==true){
						if($fyVal['id']==$mobileTowerFyID){
							$isMobileTowerTemp = true;
							$mobile_tower_qtr_temp = $mobile_tower_qtr;
						}
						if($fyVal['id']>$mobileTowerFyID){
							$isMobileTowerIncreaseTemp = true;
						}
					}
					// Hording Board
					if($isHoldingBoard==true){
						if($fyVal['id']==$hoardinBoardFyID){
							$isHoldingBoardTemp = true;
							$hoarding_board_qtr_temp = $hoarding_board_qtr;
						}
						if($fyVal['id']>$hoardinBoardFyID){
							$isHoldingBoardIncreaseTemp = true;
						}
					}

					if($isVacantLandTemp || $isMobileTowerTemp || $isHoldingBoardTemp){
						$sendInput = ['road_type_mstr_id'=>$inputs['road_type_mstr_id'], 'date_of_effect'=>$dateOfEffect];
						$mrr = $this->model_arr_vacant_mstr->getMRRCalRate($sendInput)['rate'];
						if(!$mrr){ $mrr = 0; }

						$arrShort = array('vacand'=>$vacand_land_qtr_temp, 'mobile'=>$mobile_tower_qtr_temp, 'hording'=>$hoarding_board_qtr_temp);
						asort($arrShort);

						foreach($arrShort as $keyy=>$x_Qtr) {
							if($keyy=="vacand" && $x_Qtr!=0){
								$isExist = true;
								$calVacandLandArea = $vacantlandArea;
								$vacandLandTax = 0;
								if($isMobileTowerTemp==true && $x_Qtr==$mobile_tower_qtr_temp){
									$calVacandLandArea = $calVacandLandArea-$mobileTowerArea;
								}
								if($isHoldingBoardTemp==true && $x_Qtr==$hoarding_board_qtr_temp){
									$calVacandLandArea = $calVacandLandArea-$hoardingBoardArea;
								}
								$vacantLandTax = $calVacandLandArea*$mrr;
								$totalTax += $vacantLandTax;

								$vacantLandDtl = [
									'vacant_land_area_sqm'=>$vacantlandArea,
									'applied_rate'=>$mrr,
									'yearly_holding_tax'=>round($vacantlandArea*$mrr),
									'qtr_holding_tax'=>round(($vacantlandArea*$mrr)/4),
									'vacant_land_area_sqft'=>($vacantlandArea*0.092903),
									'fy'=> $fyVal['fy'],
									'qtr'=> $x_Qtr,
								];

								$lastIncreament++;
								$lastArvDtl[$lastIncreament] = [
									'fyID'=> $fyVal['id'],
									'fy'=> $fyVal['fy'],
									'qtr'=> $x_Qtr,
									'arv'=>0,
									'holding_tax_yearly'=>$totalTax
								];
							}

							if($keyy=="mobile" && $x_Qtr!=0){
								if (!$mobileTowerOneTimeImpliment){
									$mobileTowerOneTimeImpliment = true;
									$hordingBoardTax = 0;
									if ($isVacantLandTemp==false || $x_Qtr!=$vacand_land_qtr_temp) {
										$calVacandLandArea = $vacantlandArea;
										if($isHoldingBoardIncreaseTemp==true && $x_Qtr!=$mobile_tower_qtr_temp && $hoadingBoardOneTimeImpliment==true){
											$calVacandLandArea = $calVacandLandArea-$hoardingBoardArea;
											$hordingBoardTax = $hoardingBoardArea*$mrr*1.5;
										}
										$calVacandLandArea = $calVacandLandArea-$mobileTowerArea;
										$vacantLandTax = $calVacandLandArea*$mrr*1;
										$totalTax = $vacantLandTax;
									}
									$mobileTowerTax = $mobileTowerArea*$mrr*1.5;
									$totalTax += $mobileTowerTax+$hordingBoardTax;

									$isExist = true;
									foreach($lastArvDtl as $key => $mobileVal){
										if($mobileVal['fyID']==$fyVal['id']
											&& $mobileVal['qtr']==$x_Qtr){

											$isExist = false;
											$lastArvDtl[$lastIncreament] = [
												'fyID'=> $fyVal['id'],
												'fy'=> $fyVal['fy'],
												'qtr'=> $x_Qtr,
												'arv'=>0,
												'holding_tax_yearly'=>$totalTax
											];
										}
									}
									if($isExist){
										$lastIncreament++;
										$lastArvDtl[$lastIncreament] = [
											'fyID'=> $fyVal['id'],
											'fy'=> $fyVal['fy'],
											'qtr'=> $x_Qtr,
											'arv'=>0,
											'holding_tax_yearly'=>$totalTax
										];
									}

									$isSafVacantLandMH = true;
									$vacantLandMHDtl[$vacantLandMHDtlIncreament] = [
										'type'=>'MOBILE TOWER',
										'area_sqm'=>$mobileTowerArea,
										'usage_factor'=>$mrr,
										'occupancy_factor'=>1.5,
										'fy'=> $fyVal['fy'],
										'qtr'=> $x_Qtr,
										'yearly_tax'=>$mobileTowerTax
									];
									$vacantLandMHDtlIncreament++;
								}
							}

							if($keyy=="hording" && $x_Qtr!=0){
								if (!$hoadingBoardOneTimeImpliment){
									$hoadingBoardOneTimeImpliment = true;
									$mobileTowerTax = 0;
									if ($isVacantLandTemp==false || $x_Qtr!=$vacand_land_qtr_temp) {
										$calVacandLandArea = $vacantlandArea;
										if($isMobileTowerIncreaseTemp==true && $x_Qtr!=$mobile_tower_qtr_temp && $mobileTowerOneTimeImpliment==true){
											$calVacandLandArea = $calVacandLandArea-$mobileTowerArea;
											$mobileTowerTax = $mobileTowerArea*$mrr*1.5;
										}
										$calVacandLandArea = $calVacandLandArea-$hoardingBoardArea;
										$vacantLandTax = $calVacandLandArea*$mrr*1;
										$totalTax = $vacantLandTax;
									}
									$hordingBoardTax = $hoardingBoardArea*$mrr*1.5;
									$totalTax += $hordingBoardTax+$mobileTowerTax;

									$isExist = true;
									foreach($lastArvDtl as $key => $mobileVal){
										if($mobileVal['fyID']==$fyVal['id']
											&& $mobileVal['qtr']==$x_Qtr){

											$isExist = false;
											$lastArvDtl[$lastIncreament] = [
												'fyID'=> $fyVal['id'],
												'fy'=> $fyVal['fy'],
												'qtr'=> $x_Qtr,
												'arv'=>0,
												'holding_tax_yearly'=>$totalTax
											];

										}
									}
									if($isExist){
										$lastIncreament++;
										$lastArvDtl[$lastIncreament] = [
											'fyID'=> $fyVal['id'],
											'fy'=> $fyVal['fy'],
											'qtr'=> $x_Qtr,
											'arv'=>0,
											'holding_tax_yearly'=>$totalTax
										];
									}
									$isSafVacantLandMH = true;
									$vacantLandMHDtl[$vacantLandMHDtlIncreament] = [
										'type'=>'HOARDING BOARD',
										'area_sqm'=>$hoardingBoardArea,
										'usage_factor'=>$mrr,
										'occupancy_factor'=>1.5,
										'fy'=> $fyVal['fy'],
										'qtr'=> $x_Qtr,
										'yearly_tax'=>$hordingBoardTax
									];
									$vacantLandMHDtlIncreament++;
								}
							}
						}
					}
					foreach ($lastArvDtl as $key => $value){
						$safTaxDtl[$safTaxIncreament] = [
							'fyID'=> $fyVal['id'],
							'fy'=> $fyVal['fy'],
							'qtr'=> $x_Qtr,
							'arv'=>0,
							'holding_tax_yearly'=>round($totalTax, 2),
							'holding_tax_qtr'=>round(($totalTax/4), 2)
						];
						$safTaxIncreament++;
					}
				} // end financial year foreach
				$data['vacantLandDtl'] = $vacantLandDtl;
				$data['safTaxDtl'] = $safTaxDtl;
				$data['isSafVacantLandMH'] = $isSafVacantLandMH;
				$data['vacantLandMHDtl'] = $vacantLandMHDtl;

				// insert tax details
				$currentFY = getFY();
				$currentFyId = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
				$safTaxDtlLen = sizeof($safTaxDtl);
				$i=0;

				$last_fy_mstr_id = 0;
				$last_qtr = 0;
				$last_holding_tax = 0;

				$next_fy_mstr_id = 0;
				$next_qtr = 0;
				$next_holding_tax = 0;
				$holding_tax = 0;

				$adjustment_amt = 0;
				foreach ($safTaxDtl as $key=>$safTax) {
					$i++;
					$input = [
						'saf_dtl_id'=>$saf_dtl_id,
						'fy_mstr_id'=>$safTax['fyID'],
						'qtr'=>$safTax['qtr'],
						'arv'=>0,
						'holding_tax'=>$safTax['holding_tax_qtr'],
						'water_tax'=>0,
						'education_cess'=>0,
						'health_cess'=>0,
						'latrine_tax'=>0,
						'created_on'=>$created_on,
						'status'=>1
					];
					$saf_tax_id = $this->model_saf_tax->insertData($input);

					$holding_tax_qtr = $safTax['holding_tax_qtr'];
					$amount_qtr = $holding_tax_qtr;

					if($safTaxDtlLen==$i){
						$next_fy_mstr_id = $currentFyID;
						$next_qtr = 4;
					}else{
						$next_fy_mstr_id = $safTaxDtl[$key+1]['fyID'];
						$next_qtr = $safTaxDtl[$key+1]['qtr'];
					}
					for( $j = $safTax['fyID']; $j <= $next_fy_mstr_id; $j++){
						$zz = 1;
						if ($j==$safTax['fyID']){
							$zz = $safTax['qtr'];
						}
						$zzz = 4;
						if ($j==$next_qtr){
							$zzz = $safTax['qtr'];
						}
						for ( $z = $zz; $z <= $zzz; $z++ ){
							if ( $next_fy_mstr_id==$j && $z==$next_qtr ){

								if($next_fy_mstr_id==$currentFyID && $next_qtr==4){
									if( $isReassessment==true ) {
										$inputCheckTotal = [
											'prop_dtl_id'=>$prop_dtl_id,
											'saf_dtl_id'=>$old_saf_dtl_id,
											'fy_mstr_id'=>$j,
											'qtr'=>$z
										];

										$saf_prop_demand = 0;
										if ($old_saf_dtl_id!=0) {
											if ( $saf_demand_dtl = $this->model_saf_demand->getSumDemandBySafDtlIdFyIdQtr($inputCheckTotal) ) {
												$saf_prop_demand += $saf_demand_dtl['amount'];
											}
										}

										if ( $prop_demand_dtl = $this->model_prop_demand->getSumDemandByPropDtlIdFyIdQtr($inputCheckTotal) ) {
											$saf_prop_demand += $prop_demand_dtl['amount'];
										}

										if ( $saf_prop_demand > 0 ) {
											if( $saf_prop_demand < $amount_qtr ) {
												// greater
												$remaining_amt = $amount_qtr - $saf_prop_demand;
												$input = [
													'saf_dtl_id'=>$saf_dtl_id,
													'saf_tax_id'=>$saf_tax_id,
													'fy_mstr_id'=>$j,
													'qtr'=>$z,
													'amount'=>$remaining_amt,
													'balance'=>$remaining_amt,
													'fine_tax'=>0,
													'created_on'=>$created_on,
													'status'=>1
												];
												$this->model_saf_demand->insertData($input);

											} else if( $saf_prop_demand > $amount_qtr ) {
												// smallest
												$remaining_amt = $saf_prop_demand - $amount_qtr;
												$adjustment_amt += $remaining_amt;
											}
										} else {
											$input = [
												'saf_dtl_id'=>$saf_dtl_id,
												'saf_tax_id'=>$saf_tax_id,
												'fy_mstr_id'=>$j,
												'qtr'=>$z,
												'amount'=>$amount_qtr,
												'balance'=>$amount_qtr,
												'fine_tax'=>0,
												'created_on'=>$created_on,
												'status'=>1
											];
											$this->model_saf_demand->insertData($input);
										}
									} else {
										$input = [
											'saf_dtl_id'=>$saf_dtl_id,
											'saf_tax_id'=>$saf_tax_id,
											'fy_mstr_id'=>$j,
											'qtr'=>$z,
											'amount'=>$amount_qtr,
											'balance'=>$amount_qtr,
											'fine_tax'=>0,
											'created_on'=>$created_on,
											'status'=>1
										];
										$this->model_saf_demand->insertData($input);
									}
								}else{
									break;
								}
							}else{
								if( $isReassessment==true ) {
									$inputCheckTotal = [
										'prop_dtl_id'=>$prop_dtl_id,
										'saf_dtl_id'=>$old_saf_dtl_id,
										'fy_mstr_id'=>$j,
										'qtr'=>$z
									];

									$saf_prop_demand = 0;
									if ($old_saf_dtl_id!=0) {
										if ( $saf_demand_dtl = $this->model_saf_demand->getSumDemandBySafDtlIdFyIdQtr($inputCheckTotal) ) {
											$saf_prop_demand += $saf_demand_dtl['amount'];
										}
									}

									if ( $prop_demand_dtl = $this->model_prop_demand->getSumDemandByPropDtlIdFyIdQtr($inputCheckTotal) ) {
										$saf_prop_demand += $prop_demand_dtl['amount'];
									}

									if ( $saf_prop_demand > 0 ) {
										if( $saf_prop_demand < $amount_qtr ) {
											// greater
											$remaining_amt = $amount_qtr - $saf_prop_demand;
											$input = [
												'saf_dtl_id'=>$saf_dtl_id,
												'saf_tax_id'=>$saf_tax_id,
												'fy_mstr_id'=>$j,
												'qtr'=>$z,
												'amount'=>$remaining_amt,
												'balance'=>$remaining_amt,
												'fine_tax'=>0,
												'created_on'=>$created_on,
												'status'=>1
											];
											$this->model_saf_demand->insertData($input);

										} else if( $saf_prop_demand > $amount_qtr ) {
											// smallest
											$remaining_amt = $saf_prop_demand - $amount_qtr;
											$adjustment_amt += $remaining_amt;
										}
									} else {
										$input = [
											'saf_dtl_id'=>$saf_dtl_id,
											'saf_tax_id'=>$saf_tax_id,
											'fy_mstr_id'=>$j,
											'qtr'=>$z,
											'amount'=>$amount_qtr,
											'balance'=>$amount_qtr,
											'fine_tax'=>0,
											'created_on'=>$created_on,
											'status'=>1
										];
										$this->model_saf_demand->insertData($input);
									}
								} else {
									$input = [
										'saf_dtl_id'=>$saf_dtl_id,
										'saf_tax_id'=>$saf_tax_id,
										'fy_mstr_id'=>$j,
										'qtr'=>$z,
										'amount'=>$amount_qtr,
										'balance'=>$amount_qtr,
										'fine_tax'=>0,
										'created_on'=>$created_on,
										'status'=>1
									];
									$this->model_saf_demand->insertData($input);
								}
							}
						}
					}
				}
				if( $isReassessment==true ) {
					if($prop_entry_type=="legacy") {
						if( $adjustment_amt > 0 ) {
							$input = [
								'prop_dtl_id'=>$prop_dtl_id,
								'advance_amt'=>round($adjustment_amt, 2),
								'created_on'=>$created_on,
								'status'=>1
							];
							$this->model_payment_adjust->insertData($input);
						}
					}
				}

			}else{
				// building

				$currentFY = getFY();
				$currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];

				$taxEffectedFrom = date('Y-04-01', strtotime('-12 year'));
				$taxEffectedFromFY = getFY($taxEffectedFrom);
				$taxEffectedFromFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$taxEffectedFromFY])['id'];
				//$dateOfEffect = (explode("-", $currentFY)[1])."-04-01";
				//$tax = $currentFyID-12;

				$yrOfEffect_16_17_FY = getFY("2016-04-01");
				$yrOfEffect_16_17_FyID = $this->model_fy_mstr->getFyByFy(['fy'=>$yrOfEffect_16_17_FY])['id'];

				$is_16_17_1st_qtr_tax_implement = false;

				$floorDtlArr = [];
				$j = 0;
				for($i=0; $i<sizeof($inputs['floor_mstr_id']); $i++){

					$floorDateFromFY = getFY($inputs['date_from'][$i]);
					$floorDateFromFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$floorDateFromFY])['id'];
					$MM = date("m", strtotime($inputs['date_from'][$i]));
					if($MM>=1 && 3>=$MM){ // X1
						$temp_qtr = 4;
					}else if($MM>=4 && 6>=$MM){ // X4
						$temp_qtr = 1;
					}else if($MM>=7 && 9>=$MM){ // X3
						$temp_qtr = 2;
					}else if($MM>=10 && 12>=$MM){ // X2
						$temp_qtr = 3;
					}

					$floorDateUptoFyID = 0;
					$floorDateUptoQtr = 0;
					$floorDateUptoQtrTemp = 0;
					if ($inputs['date_upto'][$i]<>"") {
						$floorDateUptoFY = getFY($inputs['date_upto'][$i]);
						$floorDateUptoFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$floorDateUptoFY])['id'];
						$MM = date("m", strtotime($inputs['date_upto'][$i]));
						if($MM>=1 && 3>=$MM){ // X1
							$floorDateUptoQtr = 4;
						}else if($MM>=4 && 6>=$MM){ // X4
							$floorDateUptoQtr = 1;
						}else if($MM>=7 && 9>=$MM){ // X3
							$floorDateUptoQtr = 2;
						}else if($MM>=10 && 12>=$MM){ // X2
							$floorDateUptoQtr = 3;
						}
						$floorDateUptoQtrTemp = $floorDateUptoQtr;
					}

					if ($inputs['date_from'][$i]."-01" < $taxEffectedFrom) {
						$floorDateFromFyID = $taxEffectedFromFyID;
						$temp_qtr = 1;
					}

					$floorDtlArr[$j] = [
						'type'=>'floor',
						'floor_mstr_id'=>$inputs['floor_mstr_id'][$i],
						'usage_type_mstr_id'=>$inputs['usage_type_mstr_id'][$i],
						'occupancy_type_mstr_id'=>$inputs['occupancy_type_mstr_id'][$i],
						'const_type_mstr_id'=>$inputs['const_type_mstr_id'][$i],
						'builtup_area'=>$inputs['builtup_area'][$i],
						'date_from'=>$inputs['date_from'][$i],
						'date_upto'=>$inputs['date_upto'][$i],
						'fy_mstr_id'=>$floorDateFromFyID,
						'qtr'=>$temp_qtr,
						'upto_fy_mstr_id'=>$floorDateUptoFyID,
						'upto_qtr'=>$floorDateUptoQtr,
						'operator'=>'+'
					];
					$j++;

					if ( $floorDateUptoFyID<>0 && $floorDateUptoQtr<>0 ) {
						if ( $floorDateUptoQtr==4 ) {
							$floorDateUptoQtr = 1;
							$floorDateUptoFyID = $floorDateUptoFyID+1;
						}else {
							$floorDateUptoQtr = $floorDateUptoQtr+1;
						}
						$date_upto = $inputs['date_upto'][$i];
						if ( $floorDateUptoQtrTemp==1 ) {
							$date_upto = date("Y", strtotime($inputs['date_upto'][$i]))."-09";
						} else if ( $floorDateUptoQtrTemp==2 ) {
							$date_upto = date("Y", strtotime($inputs['date_upto'][$i]))."-12";
						} else if ( $floorDateUptoQtrTemp==3 ) {
							$YYYY = date("Y", strtotime($inputs['date_upto'][$i]));
							$YYYY = $YYYY+1;
							$date_upto = $YYYY."-03";
						} else if ( $floorDateUptoQtrTemp==4 ) {
							$date_upto = date("Y", strtotime($inputs['date_upto'][$i]))."-06";
						}
						$floorDtlArr[$j] = [
							'type'=>'floor',
							'floor_mstr_id'=>$inputs['floor_mstr_id'][$i],
							'usage_type_mstr_id'=>$inputs['usage_type_mstr_id'][$i],
							'occupancy_type_mstr_id'=>$inputs['occupancy_type_mstr_id'][$i],
							'const_type_mstr_id'=>$inputs['const_type_mstr_id'][$i],
							'builtup_area'=>$inputs['builtup_area'][$i],
							'date_from'=>$date_upto,
							'date_upto'=>$date_upto,
							'fy_mstr_id'=>$floorDateUptoFyID,
							'qtr'=>$floorDateUptoQtr,
							'upto_fy_mstr_id'=>$floorDateUptoFyID,
							'upto_qtr'=>$floorDateUptoQtr,
							'operator'=>'-'
						];
						$j++;
					}
				}

				$mobileTowerFyID = 0;
				$mobileTowerQtr = 0;
				if($inputs['is_mobile_tower']==1){
					$mobileTowerFY = getFY($inputs['tower_installation_date']);
					$mobileTowerFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$mobileTowerFY])['id'];
					$MM = date("m", strtotime($inputs['tower_installation_date']));
					if($MM>=1 && 3>=$MM){ // X1
						$temp_qtr = 4;
					}else if($MM>=4 && 6>=$MM){ // X4
						$temp_qtr = 1;
					}else if($MM>=7 && 9>=$MM){ // X3
						$temp_qtr = 2;
					}else if($MM>=10 && 12>=$MM){ // X2
						$temp_qtr = 3;
					}
					$mobileTowerQtr = $temp_qtr;
					if( $yrOfEffect_16_17_FyID==$mobileTowerFyID && $temp_qtr==1 ) {
						$is_16_17_1st_qtr_tax_implement = true;
					}
					$date_from = "2016-04";
					if( date("Y-m-01", strtotime($inputs['tower_installation_date'])) > "2016-04-01" ) {
						$date_from = date("Y-m", strtotime($inputs['tower_installation_date']));
					} else {
						$mobileTowerFyID = $yrOfEffect_16_17_FyID;
						$mobileTowerQtr = 1;
					}

					$floorDtlArr[$j] = [
						'type'=>'mobile',
						'floor_mstr_id'=>0,
						'usage_type_mstr_id'=>0,
						'occupancy_type_mstr_id'=>0,
						'const_type_mstr_id'=>1,
						'builtup_area'=>$inputs['tower_area'],
						'date_from'=>$date_from,
						'date_upto'=>"",
						'fy_mstr_id'=>$mobileTowerFyID,
						'qtr'=>$mobileTowerQtr,
						'upto_fy_mstr_id'=>0,
						'upto_qtr'=>0,
						'operator'=>'+'
					];
					$j++;
				}

				$hoardinBoardFyID = 0;
				$hoardinBoardQtr = 0;
				if($inputs['is_hoarding_board']==1){
					$hoardinBoardFY = getFY($inputs['hoarding_installation_date']);
					$hoardinBoardFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$hoardinBoardFY])['id'];
					$MM = date("m", strtotime($inputs['hoarding_installation_date']));
					if($MM>=1 && 3>=$MM){ // X1
						$temp_qtr = 4;
					}else if($MM>=4 && 6>=$MM){ // X4
						$temp_qtr = 1;
					}else if($MM>=7 && 9>=$MM){ // X3
						$temp_qtr = 2;
					}else if($MM>=10 && 12>=$MM){ // X2
						$temp_qtr = 3;
					}
					$hoardinBoardQtr = $temp_qtr;
					/* if( $yrOfEffect_16_17_FyID==$hoardinBoardFyID && $temp_qtr==1 ) {
						$is_16_17_1st_qtr_tax_implement = true;
					} */

					$date_from = "2016-04";
					if( date("Y-m-01", strtotime($inputs['hoarding_installation_date'])) > "2016-04-01" ) {
						$date_from = date("Y-m", strtotime($inputs['hoarding_installation_date']));
					} else {
						$hoardinBoardFyID = $yrOfEffect_16_17_FyID;
						$hoardinBoardQtr = 1;
					}

					$floorDtlArr[$j] = [
						'type'=>'hoarding',
						'floor_mstr_id'=>0,
						'usage_type_mstr_id'=>0,
						'occupancy_type_mstr_id'=>0,
						'const_type_mstr_id'=>1,
						'builtup_area'=>$inputs['hoarding_area'],
						'date_from'=>$date_from,
						'date_upto'=>"",
						'fy_mstr_id'=>$hoardinBoardFyID,
						'qtr'=>$hoardinBoardQtr,
						'upto_fy_mstr_id'=>0,
						'upto_qtr'=>0,
						'operator'=>'+'
					];
					$j++;
				}

				$petrolPumpFyID = 0;
				$petrolPumpQtr = 0;
				if($inputs['is_petrol_pump']==1 && $inputs['prop_type_mstr_id']!=4){
					$petrolPumpFY = getFY($inputs['petrol_pump_completion_date']);
					$petrolPumpFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$petrolPumpFY])['id'];
					$MM = date("m", strtotime($inputs['petrol_pump_completion_date']));
					if($MM>=1 && 3>=$MM){ // X1
						$temp_qtr = 4;
					}else if($MM>=4 && 6>=$MM){ // X4
						$temp_qtr = 1;
					}else if($MM>=7 && 9>=$MM){ // X3
						$temp_qtr = 2;
					}else if($MM>=10 && 12>=$MM){ // X2
						$temp_qtr = 3;
					}
					$petrolPumpQtr = $temp_qtr;

					/* if( $yrOfEffect_16_17_FyID==$petrolPumpFyID && $temp_qtr==1 ) {
						$is_16_17_1st_qtr_tax_implement = true;
					} */
					$date_from = "2016-04";
					if( date("Y-m-01", strtotime($inputs['petrol_pump_completion_date'])) > "2016-04-01" ) {
						$date_from = date("Y-m", strtotime($inputs['petrol_pump_completion_date']));
					} else {
						$petrolPumpFyID = $yrOfEffect_16_17_FyID;
						$petrolPumpQtr = 1;
					}

					$floorDtlArr[$j] = [
						'type'=>'petrol',
						'floor_mstr_id'=>0,
						'usage_type_mstr_id'=>0,
						'occupancy_type_mstr_id'=>0,
						'const_type_mstr_id'=>1,
						'builtup_area'=>$inputs['under_ground_area'],
						'date_from'=>$date_from,
						'date_upto'=>"",
						'fy_mstr_id'=>$petrolPumpFyID,
						'qtr'=>$petrolPumpQtr,
						'upto_fy_mstr_id'=>0,
						'upto_qtr'=>0,
						'operator'=>'+'
					];
					$j++;
				}

				usort($floorDtlArr, 'floor_date_compare');

				$isWaterHarvesting = false;
				$area_of_plot = ($inputs['area_of_plot']*40.5);
				if($area_of_plot > 300){
					$isWaterHarvesting = true;
					if($inputs['is_water_harvesting']==1){
						$isWaterHarvesting = false;
					}
				}

				$FromEffectFYID = 0;
				$prop_type_mstr_arr = array(1,5);
				if(in_array($inputs["prop_type_mstr_id"], $prop_type_mstr_arr)){
						$FromEffectFYID = $yrOfEffect_16_17_FyID;
				}else{
					$FromEffectFYID = $currentFyID-12;
				}

				$getFyList = $this->model_fy_mstr->getFyByIdRangeAsc(['fromId'=>$FromEffectFYID, 'toId'=>$currentFyID]);

				$safTaxDtl = [];
				$safTaxIncreament = 0;
				foreach ($getFyList as $fyVal) {
					$totalArv = 0;
					$totalArvReduce = 0;
					$dateOfEffect = (explode("-", $fyVal['fy'])[1])."-04-01";

					$lastArvDtl = [];
					$lastIncreament = -1;
					$lastQtr = 0;
					$jj = 0;
					foreach ($floorDtlArr as $key => $floorDtl) {

						$floorDateFromFyID = $floorDtl['fy_mstr_id'];

						if ($fyVal['id']>=$floorDateFromFyID ){
							$floorDateUptoFyID = $currentFyID;
							if ($floorDtl['date_upto']!="") {
								$floorDateUptoFY = getFY($floorDtl['date_upto']);
								$floorDateUptoFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$floorDateUptoFY])['id'];
							}

							if ($fyVal['id']<=$floorDateUptoFyID) {
								$isArrear = false;
								if ($yrOfEffect_16_17_FyID > $fyVal['id']) {
									if ($floorDtl['type']=="floor") {
										$isArrear = true;
										$carperArea = $floorDtl['builtup_area'];

										$usage_type_mstr_id = 2;
										if ($floorDtl['occupancy_type_mstr_id']==1
											&& $floorDtl['usage_type_mstr_id']==1) {
											$usage_type_mstr_id = 1;
										}
										$sendInput = [
											'usage_type_mstr_id'=>$usage_type_mstr_id,
											'const_type_mstr_id'=>$floorDtl['const_type_mstr_id'],
											'zone_mstr_id'=>$inputs['zone_mstr_id']
										];
										$mrrDtl = $this->model_arr_old_building_mstr->getMRRCalRate($sendInput);
										$mrr = 0;
										$arr_building_id = 0;
										if ($mrrDtl){
											$mrr = $mrrDtl['rate'];
											$arr_building_id = $mrrDtl['id'];
										}
										$arv = $carperArea*$mrr;

										//echo $carperArea."x".$mrr." = ".$arv." => ".$floorDtl['date_from']."<br />";

										$arvRebate = 0;
										if ($floorDtl['type']=="floor") {
											if ($usage_type_mstr_id==1) {
												$arvRebate += ($arv*30)/100;
											} else if ($usage_type_mstr_id==2) {
												$arvRebate += ($arv*15)/100;
											}
											if ($inputs["prop_type_mstr_id"]==2
												&& $floorDtl['occupancy_type_mstr_id']==1
												&& $floorDtl['usage_type_mstr_id']==1) {
												$rebate_date = $floorDtl['date_from']."-01";
												if("1942-04-01">$rebate_date){
													if($arv!=0){
														$arvRebate += (($arv*10)/100);
													}
												}
											}
										}
										$arv -= $arvRebate;
										if ( $floorDtl['operator']=="+" ) {
											$totalArv += $arv;
										} else if ( $floorDtl['operator']=="-" ) {
											$totalArv -= $arv;
										}

										if ($fyVal['id']==$floorDateFromFyID) {

											if ( $floorDtl['type']=="floor" && $floorDtl['operator']=="+" ) {
												$inputDtl = [
													'saf_dtl_id'=>$saf_dtl_id,
													'floor_mstr_id'=>$floorDtl['floor_mstr_id'],
													'usage_type_mstr_id'=>$floorDtl['usage_type_mstr_id'],
													'const_type_mstr_id'=>$floorDtl['const_type_mstr_id'],
													'occupancy_type_mstr_id'=>$floorDtl['occupancy_type_mstr_id'],
													'builtup_area'=>$floorDtl['builtup_area'],
													'carpet_area'=>$carperArea,
													'date_from'=>$floorDtl['date_from']."-01",
													'date_upto'=>($floorDtl['date_upto']!="")?$floorDtl['date_upto']."-01":null,
													'arr_building_type'=>'tbl_arr_old_building_mstr',
													'arr_building_id'=>$arr_building_id,
													'arr_building_rate'=>$mrr,
													'usage_type_dtl_id'=>0,
													'usage_type_rate'=>0,
													'occupancy_type_rate'=>0,
													'arv'=>$arv,
													'fy_mstr_id'=>$fyVal['id'],
													'qtr'=>$temp_qtr,
													'emp_details_id'=>$inputs['emp_details_id'],
													'entry_type'=>'FROM_SAF',
													'created_on'=>$created_on,
													'status'=>1
												];
												$this->model_saf_floor_arv_dtl->insertData($inputDtl);
											}

											$temp_qtr = $floorDtl['qtr'];

											if ($lastQtr!=$temp_qtr) {
												$lastQtr=$temp_qtr;
												$lastIncreament++;
												$lastArvDtl[$lastIncreament] = [
													'fyID'=> $fyVal['id'],
													'arv'=>round($totalArv, 2),
													'qtr'=>$temp_qtr
												];
											}else{
												$lastArvDtl[$lastIncreament] = [
													'fyID'=> $fyVal['id'],
													'arv'=>round($totalArv, 2),
													'qtr'=>$temp_qtr
												];
											}
										}
									} // only floor effected
								} // old rule effected if condition

								if ($yrOfEffect_16_17_FyID <= $fyVal['id']) {
									if (!$isArrear) {

										if ( $fyVal['id']==$yrOfEffect_16_17_FyID ) {

											if ( !$is_16_17_1st_qtr_tax_implement ) {
												$oldARVTotal = 0;
												foreach ($floorDtlArr as $key => $floorDtlTemp) {
													if ($floorDtlTemp['type']=="floor") {
														$floorDateFromFyIDTemp = $floorDtlTemp['fy_mstr_id'];

														if ($yrOfEffect_16_17_FyID > $floorDateFromFyIDTemp) {
															$floorDateUptoFyID = $currentFyID;
															if ($floorDtlTemp['date_upto']!="") {
																$floorDateUptoFY = getFY($floorDtlTemp['date_upto']);
																$floorDateUptoFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$floorDateUptoFY])['id'];
															}

															if ($yrOfEffect_16_17_FyID <= $floorDateUptoFyID) {

																$afr = $this->model_occupancy_type_mstr->getOccupancyMultFactById($floorDtlTemp['occupancy_type_mstr_id'])['mult_factor'];
																if(!$afr){ $afr = 0;}
																if($floorDtlTemp['usage_type_mstr_id']==1){
																	$carperArea = (($floorDtlTemp['builtup_area']*70)/100);
																}else{
																	$carperArea = (($floorDtlTemp['builtup_area']*80)/100);
																}
																$sendInput = ['usage_type_mstr_id'=>$floorDtlTemp['usage_type_mstr_id']];
																$mfDtl = $this->model_usage_type_dtl->getUsageTypeMultFact($sendInput);
																$mf = 0;
																$usage_type_dtl_id = 0;
																if($mfDtl){
																	$mf = $mfDtl['mult_factor'];
																	$usage_type_dtl_id = $mfDtl['id'];
																}

																$sendInput = ['road_type_mstr_id'=>$inputs['road_type_mstr_id'], 'const_type_mstr_id'=>$floorDtlTemp['const_type_mstr_id'], 'date_of_effect'=>$dateOfEffect];
																$mrrDtl = $this->model_arr_building_mstr->getMRRCalRate($sendInput);
																$mrr = 0;
																$arr_building_id = 0;
																if ($mrrDtl){
																	$mrr = $mrrDtl['cal_rate'];
																	$arr_building_id = $mrrDtl['id'];
																}

																$arv = $afr*$mf*$carperArea*$mrr;
																//echo $afr."x".$mf."x".$carperArea."x".$mrr." = ".$arv." => ".$floorDtlTemp['date_from']."<br />";
																if ($floorDtl['type']=="floor") {
																	if($inputs["prop_type_mstr_id"]==2
																		&& $floorDtlTemp['occupancy_type_mstr_id']==1
																		&& $floorDtlTemp['usage_type_mstr_id']==1){
																		$rebate_date = $floorDtlTemp['date_from']."-01";
																		if("1942-04-01">$rebate_date){
																			if($arv!=0){
																				$arvRebate = (($arv*10)/100);
																				$arv = $arv - $arvRebate;
																			}
																		}
																	}
																}
																if ( $arv!=0 ) {
																	if ( $floorDtl['type']=="floor" && $floorDtlTemp['operator']=="+" ) {
																		$inputDtl = [
																			'saf_dtl_id'=>$saf_dtl_id,
																			'floor_mstr_id'=>$floorDtl['floor_mstr_id'],
																			'usage_type_mstr_id'=>$floorDtl['usage_type_mstr_id'],
																			'const_type_mstr_id'=>$floorDtl['const_type_mstr_id'],
																			'occupancy_type_mstr_id'=>$floorDtl['occupancy_type_mstr_id'],
																			'builtup_area'=>$floorDtl['builtup_area'],
																			'carpet_area'=>$carperArea,
																			'date_from'=>$floorDtl['date_from']."-01",
																			'date_upto'=>($floorDtl['date_upto']!="")?$floorDtl['date_upto']."-01":null,
																			'arr_building_type'=>'tbl_arr_building_mstr',
																			'arr_building_id'=>$arr_building_id,
																			'arr_building_rate'=>$mrr,
																			'usage_type_dtl_id'=>$usage_type_dtl_id,
																			'usage_type_rate'=>$mf,
																			'occupancy_type_rate'=>$afr,
																			'arv'=>$arv,
																			'fy_mstr_id'=>$fyVal['id'],
																			'qtr'=>1,
																			'emp_details_id'=>$inputs['emp_details_id'],
																			'entry_type'=>'FROM_SAF',
																			'created_on'=>$created_on,
																			'status'=>1
																		];
																		$this->model_saf_floor_arv_dtl->insertData($inputDtl);
																	}
																	if ( $floorDtlTemp['operator']=="+" ) {
																		$oldARVTotal += $arv;
																	} else if ( $floorDtlTemp['operator']=="-" ) {
																		$oldARVTotal -= $arv;
																	}
																}
															}
														}
													}
												}
												if ($oldARVTotal > 0) {
													$safTax = $oldARVTotal;
													$holding_tax = $safTax*0.02;
													$additional_tax = 0;
													if ($isWaterHarvesting==0) {
														$waterHarvestingTax = $holding_tax*1.5;
														$additional_tax = $waterHarvestingTax - $holding_tax;
														if($additional_tax!=0){
															$additional_tax = round(($additional_tax/4), 2);
														}
													}
													if($holding_tax!=0){
														$holding_tax = round(($holding_tax/4), 2);
													}

													$lastIncreament++;
													$lastArvDtl[$lastIncreament] = [
														'fyID'=> $fyVal['id'],
														'arv'=>$oldARVTotal,
														'qtr'=>1
													];

													$is_16_17_1st_qtr_tax_implement = true;
												}
											} // if new rule is implimented or not
										} // end if old rule is not implimented in new rule

										if ($floorDtl['type']=="floor") {
											$afr = $this->model_occupancy_type_mstr->getOccupancyMultFactById($floorDtl['occupancy_type_mstr_id'])['mult_factor'];
											if(!$afr){ $afr = 0;}
										} else {
											$afr = 1.5;
										}

										if ($floorDtl['type']=="floor") {
											if($floorDtl['usage_type_mstr_id']==1){
												$carperArea = (($floorDtl['builtup_area']*70)/100);
											}else{
												$carperArea = (($floorDtl['builtup_area']*80)/100);
											}
										} else {
											$carperArea = $floorDtl['builtup_area'];
										}
										if ($floorDtl['type']=="floor") {
											$sendInput = ['usage_type_mstr_id'=>$floorDtl['usage_type_mstr_id']];
											$mfDtl = $this->model_usage_type_dtl->getUsageTypeMultFact($sendInput);
											$mf = 0;
											$usage_type_dtl_id = 0;
											if($mfDtl){
												$mf = $mfDtl['mult_factor'];
												$usage_type_dtl_id = $mfDtl['id'];
											}
										} else {
											$mf = 1.5;
											$usage_type_dtl_id = 13;
										}

										$sendInput = ['road_type_mstr_id'=>$inputs['road_type_mstr_id'], 'const_type_mstr_id'=>$floorDtl['const_type_mstr_id'], 'date_of_effect'=>$dateOfEffect];
										$mrrDtl = $this->model_arr_building_mstr->getMRRCalRate($sendInput);
										$mrr = 0;
										$arr_building_id = 0;
										if ($mrrDtl){
											$mrr = $mrrDtl['cal_rate'];
											$arr_building_id = $mrrDtl['id'];
										}

										$arv = $afr*$mf*$carperArea*$mrr;
										//echo $afr."x".$mf."x".$carperArea."x".$mrr." = ".$arv." => ".$floorDtl['date_from']."<br />";
										if ($floorDtl['type']=="floor") {
											if($inputs["prop_type_mstr_id"]==2
												&& $floorDtl['occupancy_type_mstr_id']==1
												&& $floorDtl['usage_type_mstr_id']==1){
												$rebate_date = $floorDtl['date_from']."-01";
												if("1942-04-01">$rebate_date){
													if($arv!=0){
														$arvRebate = (($arv*10)/100);
														$arv = $arv - $arvRebate;
													}
												}
											}
										}
										if ( $arv!=0 ) {
											if ( $floorDtl['operator']=="+" ) {
												$totalArv += $arv;
											} else if ( $floorDtl['operator']=="-" ) {
												$totalArv -= $arv;
											}
										}

										if($fyVal['id']==$floorDateFromFyID){

											$temp_qtr = $floorDtl['qtr'];

											if ( $floorDtl['type']=="floor" && $floorDtl['operator']=="+" ) {
												$inputDtl = [
													'saf_dtl_id'=>$saf_dtl_id,
													'floor_mstr_id'=>$floorDtl['floor_mstr_id'],
													'usage_type_mstr_id'=>$floorDtl['usage_type_mstr_id'],
													'const_type_mstr_id'=>$floorDtl['const_type_mstr_id'],
													'occupancy_type_mstr_id'=>$floorDtl['occupancy_type_mstr_id'],
													'builtup_area'=>$floorDtl['builtup_area'],
													'carpet_area'=>$carperArea,
													'date_from'=>$floorDtl['date_from']."-01",
													'date_upto'=>($floorDtl['date_upto']!="")?$floorDtl['date_upto']."-01":null,
													'arr_building_type'=>'tbl_arr_building_mstr',
													'arr_building_id'=>$arr_building_id,
													'arr_building_rate'=>$mrr,
													'usage_type_dtl_id'=>$usage_type_dtl_id,
													'usage_type_rate'=>$mf,
													'occupancy_type_rate'=>$afr,
													'arv'=>$arv,
													'fy_mstr_id'=>$fyVal['id'],
													'qtr'=>$temp_qtr,
													'emp_details_id'=>$inputs['emp_details_id'],
													'entry_type'=>'FROM_SAF',
													'created_on'=>$created_on,
													'status'=>1
												];
												$this->model_saf_floor_arv_dtl->insertData($inputDtl);
											}

											$isExist = true;
											foreach($lastArvDtl as $key => $tempLastArvDtl){
												if($tempLastArvDtl['fyID']==$fyVal['id']
													&& $tempLastArvDtl['qtr']==$temp_qtr){

													$isExist = false;
													$lastArvDtl[$key] = [
														'fyID'=> $fyVal['id'],
														'arv'=>$totalArv,
														'qtr'=>$temp_qtr
													];
												}
											}
											if($isExist){
												$lastIncreament++;
												$lastArvDtl[$lastIncreament] = [
													'fyID'=> $fyVal['id'],
													'arv'=>$totalArv,
													'qtr'=>$temp_qtr
												];
											}
										}

									}
								} // new rule effected
							}
						}
					} //end floorDtlArr foreach loop

					foreach($lastArvDtl as $key => $value){
						$holding_tax = 0;
						$water_tax = 0;
						$education_cess = 0;
						$health_cess = 0;
						$latrine_tax = 0;
						$additional_tax = 0;
						$safTaxQtr = $value['arv'];
						if($yrOfEffect_16_17_FyID > $fyVal['id']){
							$holding_tax = $safTaxQtr*0.125;
							if($holding_tax!=0){
								$holding_tax = round(($holding_tax/4), 2);
							}
							$water_tax = $safTaxQtr*0.075;
							if($water_tax!=0){
								$water_tax = round(($water_tax/4), 2);
							}
							$education_cess = $safTaxQtr*0.05;
							if($education_cess!=0){
								$education_cess = round(($education_cess/4), 2);
							}
							$health_cess = $safTaxQtr*0.0625;
							if($health_cess!=0){
								$health_cess = round(($health_cess/4), 2);
							}
							$latrine_tax = $safTaxQtr*0.075;
							if($latrine_tax!=0){
								$latrine_tax = round(($latrine_tax/4), 2);
							}
						}else{
							$holding_tax = $safTaxQtr*0.02;
							if($isWaterHarvesting) {
								$waterHarvestingTax = $holding_tax*1.5;
								$additional_tax = $waterHarvestingTax - $holding_tax;
								if($additional_tax!=0){
									$additional_tax = round(($additional_tax/4), 2);
								}
							}
							if($holding_tax!=0){
								$holding_tax = round(($holding_tax/4), 2);
							}
						}
						$safTaxDtl[$safTaxIncreament] = [
							'fyID'=>$fyVal['id'],
							'fy'=> $fyVal['fy'],
							'arv'=>round($value['arv'], 2),
							'qtr'=>$value['qtr'],
							'holding_tax'=>$holding_tax,
							'water_tax'=>$water_tax,
							'education_cess'=>$education_cess,
							'health_cess'=>$health_cess,
							'latrine_tax'=>$latrine_tax,
							'additional_tax'=>$additional_tax
						];
						$safTaxIncreament++;
					}
				} // end foreach loop to financial year
				// insert tax details

				$safTaxDtlLen = sizeof($safTaxDtl);

				$i=0;

				$last_fy_mstr_id = 0;
				$last_qtr = 0;
				$last_holding_tax = 0;

				$next_fy_mstr_id = 0;
				$next_qtr = 0;
				$next_holding_tax = 0;
				$holding_tax = 0;

				$adjustment_amt = 0;
				$last_saf_tax_id = 0;
				$last_amount = 0;
				foreach ($safTaxDtl as $key=>$safTax) {
					if ( $safTax['arv']==0) {
						$saf_tax_id = $last_saf_tax_id;
						$amount = $last_amount;
					} else {
						$i++;
						$input = [
							'saf_dtl_id'=>$saf_dtl_id,
							'fy_mstr_id'=>$safTax['fyID'],
							'qtr'=>$safTax['qtr'],
							'arv'=>$safTax['arv'],
							'holding_tax'=>$safTax['holding_tax'],
							'water_tax'=>$safTax['water_tax'],
							'education_cess'=>$safTax['education_cess'],
							'health_cess'=>$safTax['health_cess'],
							'latrine_tax'=>$safTax['latrine_tax'],
							'additional_tax'=>$safTax['additional_tax'],
							'created_on'=>$created_on,
							'status'=>1
						];
						$last_saf_tax_id = $saf_tax_id = $this->model_saf_tax->insertData($input);

						$last_amount = $amount = $safTax['holding_tax']+$safTax['water_tax']+$safTax['education_cess']+$safTax['health_cess']+$safTax['latrine_tax']+$safTax['additional_tax'];
					}
						$amount_qtr = $amount;
						if($safTaxDtlLen==$i){
							$next_fy_mstr_id = $currentFyID;
							$next_qtr = 4;
						}else{
							$next_fy_mstr_id = $safTaxDtl[$key+1]['fyID'];
							$next_qtr = $safTaxDtl[$key+1]['qtr'];
						}
						for( $j = $safTax['fyID']; $j <= $next_fy_mstr_id; $j++){
							$zz = 1;
							if ($j==$safTax['fyID']){
								$zz = $safTax['qtr'];
							}
							$zzz = 4;
							if ($j==$next_qtr){
								$zzz = $safTax['qtr'];
							}
							for ( $z = $zz; $z <= $zzz; $z++ ) {
								if ( $next_fy_mstr_id==$j && $z==$next_qtr ){
									if($next_fy_mstr_id==$currentFyID && $next_qtr==4){
										if( $isReassessment==true ) {
											$inputCheckTotal = [
												'prop_dtl_id'=>$prop_dtl_id,
												'saf_dtl_id'=>$old_saf_dtl_id,
												'fy_mstr_id'=>$j,
												'qtr'=>$z
											];

											$saf_prop_demand = 0;
											if ($old_saf_dtl_id!=0) {
												if ( $saf_demand_dtl = $this->model_saf_demand->getSumDemandBySafDtlIdFyIdQtr($inputCheckTotal) ) {
													$saf_prop_demand += $saf_demand_dtl['amount'];
												}
											}

                                            if ( $prop_demand_dtl = $this->model_prop_demand->getSumDemandByPropDtlIdFyIdQtr($inputCheckTotal) ) {
                                                $saf_prop_demand += $prop_demand_dtl['amount'];
											}

											if ( $saf_prop_demand > 0 ) {
												if( $saf_prop_demand < $amount_qtr ) {
													// greater
													$remaining_amt = round($amount_qtr - $saf_prop_demand);
													$input = [
														'saf_dtl_id'=>$saf_dtl_id,
														'saf_tax_id'=>$saf_tax_id,
														'fy_mstr_id'=>$j,
														'qtr'=>$z,
														'amount'=>$remaining_amt,
														'balance'=>$remaining_amt,
														'fine_tax'=>0,
														'created_on'=>$created_on,
														'status'=>1
													];
													$this->model_saf_demand->insertData($input);
												} else if( $saf_prop_demand > $amount_qtr ) {
													// smallest
													$remaining_amt = round($saf_prop_demand - $amount_qtr);
													$adjustment_amt += $remaining_amt;
												}
											} else {
												$input = [
													'saf_dtl_id'=>$saf_dtl_id,
													'saf_tax_id'=>$saf_tax_id,
													'fy_mstr_id'=>$j,
													'qtr'=>$z,
													'amount'=>$amount_qtr,
													'balance'=>$amount_qtr,
													'fine_tax'=>0,
													'created_on'=>$created_on,
													'status'=>1
												];
												$this->model_saf_demand->insertData($input);
											}
										} else {
											$input = [
												'saf_dtl_id'=>$saf_dtl_id,
												'saf_tax_id'=>$saf_tax_id,
												'fy_mstr_id'=>$j,
												'qtr'=>$z,
												'amount'=>$amount_qtr,
												'balance'=>$amount_qtr,
												'fine_tax'=>0,
												'created_on'=>$created_on,
												'status'=>1
											];
											$this->model_saf_demand->insertData($input);
										}
									}else{
										break;
									}
								} else {
									if( $isReassessment==true ) {
										$inputCheckTotal = [
											'prop_dtl_id'=>$prop_dtl_id,
											'saf_dtl_id'=>$old_saf_dtl_id,
											'fy_mstr_id'=>$j,
											'qtr'=>$z
										];

										$saf_prop_demand = 0;
										if ($old_saf_dtl_id!=0) {
											if ( $saf_demand_dtl = $this->model_saf_demand->getSumDemandBySafDtlIdFyIdQtr($inputCheckTotal) ) {
												$saf_prop_demand += $saf_demand_dtl['amount'];
											}
										}

										if ( $prop_demand_dtl = $this->model_prop_demand->getSumDemandByPropDtlIdFyIdQtr($inputCheckTotal) ) {
											$saf_prop_demand += $prop_demand_dtl['amount'];
										}

										if ( $saf_prop_demand > 0 ) {

											if( $saf_prop_demand < $amount_qtr ) {
												// greater
												$remaining_amt = $amount_qtr - $saf_prop_demand;
												$input = [
													'saf_dtl_id'=>$saf_dtl_id,
													'saf_tax_id'=>$saf_tax_id,
													'fy_mstr_id'=>$j,
													'qtr'=>$z,
													'amount'=>$remaining_amt,
													'balance'=>$remaining_amt,
													'fine_tax'=>0,
													'created_on'=>$created_on,
													'status'=>1
												];
												$this->model_saf_demand->insertData($input);
											} else if( $saf_prop_demand > $amount_qtr ) {
												// smallest
												$remaining_amt = ($saf_prop_demand - $amount_qtr);
												$adjustment_amt += $remaining_amt;
											}
										} else {
											$input = [
												'saf_dtl_id'=>$saf_dtl_id,
												'saf_tax_id'=>$saf_tax_id,
												'fy_mstr_id'=>$j,
												'qtr'=>$z,
												'amount'=>$amount_qtr,
												'balance'=>$amount_qtr,
												'fine_tax'=>0,
												'created_on'=>$created_on,
												'status'=>1
											];
											$this->model_saf_demand->insertData($input);
										}
									} else {
										$input = [
											'saf_dtl_id'=>$saf_dtl_id,
											'saf_tax_id'=>$saf_tax_id,
											'fy_mstr_id'=>$j,
											'qtr'=>$z,
											'amount'=>$amount_qtr,
											'balance'=>$amount_qtr,
											'fine_tax'=>0,
											'created_on'=>$created_on,
											'status'=>1
										];
										$this->model_saf_demand->insertData($input);
									}
								}
							}
						}
				}
				if( $isReassessment==true ) {
					if($prop_entry_type=="legacy") {
						if( $adjustment_amt > 0 ) {
							$input = [
								'prop_dtl_id'=>$prop_dtl_id,
								'advance_amt'=>round($adjustment_amt, 2),
								'created_on'=>$created_on,
								'status'=>1
							];
							$this->model_payment_adjust->insertData($input);
						}
					}
				}
			} // end building calculation details
			if($this->db->transStatus() === FALSE){
				$this->db->transRollback();
				return false;
			}else{
				$this->db->transCommit();
				return $saf_dtl_id;
				//return false;
			}
		}catch(Exception $e){
			//echo $e->getMessage();
		}
	}

	public function addUpdate()
	{
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$emp_details_id = $_SESSION['emp_details']['id'];
		$ip_address = $_SESSION['emp_details']['ip_address'];

		$ulb_address = $this->model_ulb_mstr->getAddressById(['ulb_mstr_id'=>$ulb_mstr_id]);
		$wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
		$transferModeList = $this->model_transfer_mode_mstr->getTransferModeList();
		$ownershipTypeList = $this->model_ownership_type_mstr->getOwnershipTypeList();
		$propTypeList = $this->model_prop_type_mstr->getPropTypeList();
		$roadTypeList = $this->model_road_type_mstr->getRoadTypeList();
		$floorList = $this->model_floor_mstr->getFloorList();
		$usageTypeList = $this->model_usage_type_mstr->getUsageTypeList();
		$occupancyTypeList = $this->model_occupancy_type_mstr->getOccupancyTypeList();
		$constTypeList = $this->model_const_type_mstr->getConstTypeList();

		$data['ulb_address'] = $ulb_address;
		$data['wardList'] = $wardList;
		$data['transferModeList'] = $transferModeList;
		$data['ownershipTypeList'] = $ownershipTypeList;
		$data['roadTypeList'] = $roadTypeList;
		$data['propTypeList'] = $propTypeList;
		$data['floorList'] = $floorList;
		$data['usageTypeList'] = $usageTypeList;
		$data['occupancyTypeList'] = $occupancyTypeList;
		$data['constTypeList'] = $constTypeList;

		if($this->request->getMethod()=='post'){
			try{
				$inputs = arrFilterSanitizeString($this->request->getVar());
				$errMsg = validateSafMobileAddUpdate($inputs);

				if ( empty($errMsg) ) {
					if ( $inputs["has_previous_holding_no"]==1 ){
						$isHoldingNoExist = false;
						$isPaymentDtlCleared = false;
						$input = [
							'holding_no'=>$inputs['previous_holding_no']
						];
						if($prop_dtl = $this->model_prop_dtl->getPropIdByHodingNoEntryType($input)){
							$input = [
								'prop_dtl_id'=>$prop_dtl['id']
							];
							$isHoldingNoExist = true;
							if(!$this->model_prop_demand->getIsDemandClearedByPropDtlId($input)){
								$isPaymentDtlCleared = true;
							}
						}
						if	($isHoldingNoExist==false) {
							$errMsg["holding_no"] = "holding no does not exist !!!";
						}
						if( $inputs["has_previous_holding_no"]==1 && $inputs["is_owner_changed"]==1 ){
							if	($isPaymentDtlCleared==false) {
								$errMsg["isPrevPaymentCleared"] = $inputs["previous_holding_no"].", please clear tax !!!";
							}
						}
					}
				}
				if (empty($errMsg))
				{
					$data = $inputs;
					$data['wardList'] = $wardList;
					$data['transferModeList'] = $transferModeList;
					$data['ownershipTypeList'] = $ownershipTypeList;
					$data['roadTypeList'] = $roadTypeList;
					$data['propTypeList'] = $propTypeList;
					$data['floorList'] = $floorList;
					$data['usageTypeList'] = $usageTypeList;
					$data['occupancyTypeList'] = $occupancyTypeList;
					$data['constTypeList'] = $constTypeList;
					if(isset($_POST['btn_back']))
					{
						$data['ulb_address'] = $ulb_address;
						return view('property/saf/saf_add_update', $data);
					}
					else if(isset($_POST['btn_review']))
					{

						if($inputs['prop_type_mstr_id']==4)
						{

							// current fimamcial year
							$currentFY = getFY();
							$currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];

							// date of effect
							$yrOfEffect_16_17_FY = getFY("2016-04-01");
							$yrOfEffect_16_17_FyID = $this->model_fy_mstr->getFyByFy(['fy'=>$yrOfEffect_16_17_FY])['id'];

							// vacant land details
							$vacantlandArea = ($inputs['area_of_plot']*40.5);
							$mobileTowerArea =  $hoardingBoardArea = 0;

							$land_occupation_date = $inputs['land_occupation_date'];

							$isVacantLand = $isMobileTower = $isHoldingBoard = false;
							$vacand_land_qtr = $mobile_tower_qtr = $hoarding_board_qtr = 0;
							// date of effect
							//$yrOfEffectFY = getFY("2016-04-01");
							//$yrOfEffectFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$yrOfEffectFY])['id'];
							$FromFixEffectFyID =  $yrOfEffect_16_17_FyID;

							// acquisition fy
							$acquisitionFY = getFY($inputs['land_occupation_date']);
							$acquisitionFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$acquisitionFY])['id'];
							if ( $yrOfEffect_16_17_FyID > $acquisitionFyID ) {
								$acquisitionFyID = $yrOfEffect_16_17_FyID;
								$inputs['land_occupation_date'] = "2016-04-01";
							}
							if ( $yrOfEffect_16_17_FyID < $acquisitionFyID ) {
								$FromFixEffectFyID = $acquisitionFyID;
							}

							$MM = date("m", strtotime($inputs['land_occupation_date']));
							if($MM>=1 && 3>=$MM){ // X1
								$temp_qtr = 4;
							}else if($MM>=4 && 6>=$MM){ // X4
								$temp_qtr = 1;
							}else if($MM>=7 && 9>=$MM){ // X3
								$temp_qtr = 2;
							}else if($MM>=10 && 12>=$MM){ // X2
								$temp_qtr = 3;
							}else{

							}
							$isVacantLand = true;
							$vacand_land_qtr = $temp_qtr;

							if($inputs['is_mobile_tower']==1){

								$mobileTowerFY = getFY($inputs['tower_installation_date']);
								$mobileTowerFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$mobileTowerFY])['id'];
								if($FromFixEffectFyID>$mobileTowerFyID){
									$mobileTowerFyID = $FromFixEffectFyID;
									$inputs['tower_installation_date'] = "2016-04-01";
								}
								/* if($currentFyID<$mobileTowerFyID){
									$currentFyID = $mobileTowerFyID;
								} */
								$MM = date("m", strtotime($inputs['tower_installation_date']));
								if($MM>=1 && 3>=$MM){ // X1
									$temp_qtr = 4;
								}else if($MM>=4 && 6>=$MM){ // X4
									$temp_qtr = 1;
								}else if($MM>=7 && 9>=$MM){ // X3
									$temp_qtr = 2;
								}else if($MM>=10 && 12>=$MM){ // X2
									$temp_qtr = 3;
								}else{

								}
								$mobileTowerArea = $inputs['tower_area']*0.092903;
								$isMobileTower = true;
								$mobile_tower_qtr = $temp_qtr;
							}
							if($inputs['is_hoarding_board']==1){
								$hoardinBoardFY = getFY($inputs['hoarding_installation_date']);
								$hoardinBoardFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$hoardinBoardFY])['id'];
								if($yrOfEffect_16_17_FyID>$hoardinBoardFyID){
									$hoardinBoardFyID = $FromFixEffectFyID;
									$inputs['hoarding_installation_date'] = "2016-04-01";
								}
								/* if($currentFyID<$hoardinBoardFyID){
									$currentFyID = $hoardinBoardFyID;
								} */
								$MM = date("m", strtotime($inputs['hoarding_installation_date']));
								if($MM>=1 && 3>=$MM){ // X1
									$temp_qtr = 4;
								}else if($MM>=4 && 6>=$MM){ // X4
									$temp_qtr = 1;
								}else if($MM>=7 && 9>=$MM){ // X3
									$temp_qtr = 2;
								}else if($MM>=10 && 12>=$MM){ // X2
									$temp_qtr = 3;
								}else{

								}
								$hoardingBoardArea = $inputs['hoarding_area']*0.092903;
								$isHoldingBoard = true;
								$hoarding_board_qtr = $temp_qtr;
							}

							$getFyList = $this->model_fy_mstr->getFyByIdRangeAsc(['fromId'=>$FromFixEffectFyID, 'toId'=>$currentFyID]);

							$vacantLandDtl = [];
							$isSafVacantLandMH = false;
							$vacantLandMHDtl = [];
							$vacantLandMHDtlIncreament = 0;
							$safTaxDtl = [];
							$safTaxIncreament = 0;

							$mobileTowerOneTimeImpliment = false;
							$hoadingBoardOneTimeImpliment = false;
							foreach ($getFyList as $fyVal) {
								$dateOfEffect = (explode("-", $fyVal['fy'])[1])."-04-01";

								$vacand_land_qtr_temp = $mobile_tower_qtr_temp = $hoardin_board_qtr_temp = 0;
								$isVacantLandTemp = $isMobileTowerTemp = $isHoldingBoardTemp = false;
								$isMobileTowerIncreaseTemp = $isHoldingBoardIncreaseTemp = false;

								$isExist = false;
								$totalTax = 0;

								$lastArvDtl = [];
								$lastIncreament = -1;

								// vacand land
								if($fyVal['id']==$acquisitionFyID){
									$isVacantLandTemp = true;
									$vacand_land_qtr_temp = $vacand_land_qtr;
								}
								// mobile tower
								if($isMobileTower==true){
									if($fyVal['id']==$mobileTowerFyID){
										$isMobileTowerTemp = true;
										$mobile_tower_qtr_temp = $mobile_tower_qtr;
									}
									if($fyVal['id']>=$mobileTowerFyID){
										$isMobileTowerIncreaseTemp = true;
									}
								}
								// Hording Board
								if($isHoldingBoard==true){
									if($fyVal['id']==$hoardinBoardFyID){
										$isHoldingBoardTemp = true;
										$hoarding_board_qtr_temp = $hoarding_board_qtr;
									}
									if($fyVal['id']>$hoardinBoardFyID){
										$isHoldingBoardIncreaseTemp = true;
									}
								}

								if($isVacantLandTemp || $isMobileTowerTemp || $isHoldingBoardTemp){
									$sendInput = ['road_type_mstr_id'=>$inputs['road_type_mstr_id'], 'date_of_effect'=>$dateOfEffect];
									$mrr = $this->model_arr_vacant_mstr->getMRRCalRate($sendInput)['rate'];
									if(!$mrr){ $mrr = 0; }

									$arrShort = array('vacand'=>$vacand_land_qtr_temp, 'mobile'=>$mobile_tower_qtr_temp, 'hording'=>$hoarding_board_qtr_temp);
									
									asort($arrShort);
									print_var($arrShort);
									foreach($arrShort as $keyy=>$x_Qtr)
									{
										if($keyy=="vacand" && $x_Qtr!=0 && $x_Qtr!=null)
										{
											$isExist = true;
											$calVacandLandArea = $vacantlandArea;
											$vacandLandTax = 0;
											if($isMobileTowerTemp==true && $x_Qtr==$mobile_tower_qtr_temp){
												$calVacandLandArea = $calVacandLandArea-$mobileTowerArea;
											}
											if($isHoldingBoardTemp==true && $x_Qtr==$hoarding_board_qtr_temp){
												$calVacandLandArea = $calVacandLandArea-$hoardingBoardArea;
											}
											$vacantLandTax = $calVacandLandArea*$mrr;
											$totalTax += $vacantLandTax;

											$vacantLandDtl = [
												'vacant_land_area_sqm'=>$vacantlandArea,
												'applied_rate'=>$mrr,
												'yearly_holding_tax'=>round(($vacantlandArea*$mrr), 2),
												'qtr_holding_tax'=>round((($vacantlandArea*$mrr)/4), 2),
												'vacant_land_area_sqft'=>($vacantlandArea*0.092903),
												'fy'=> $fyVal['fy'],
												'qtr'=> $x_Qtr,
											];

											$lastIncreament++;
											$lastArvDtl[$lastIncreament] = [
												'fyID'=> $fyVal['id'],
												'fy'=> $fyVal['fy'],
												'qtr'=> $x_Qtr,
												'arv'=>0,
												'holding_tax_yearly'=>$totalTax
											];
											print_var($lastArvDtl);
										}

										if($keyy=="mobile" && $x_Qtr!=0 && $x_Qtr!=null)
										{
											if (!$mobileTowerOneTimeImpliment){
												$mobileTowerOneTimeImpliment = true;
												$hordingBoardTax = 0;
												if ($isVacantLandTemp==false || $x_Qtr!=$vacand_land_qtr_temp) {
													$calVacandLandArea = $vacantlandArea;
													if($isHoldingBoardIncreaseTemp==true && $x_Qtr!=$mobile_tower_qtr_temp && $hoadingBoardOneTimeImpliment==true){
														$calVacandLandArea = $calVacandLandArea-$hoardingBoardArea;
														$hordingBoardTax = $hoardingBoardArea*$mrr*1.5;
													}
													$calVacandLandArea = $calVacandLandArea-$mobileTowerArea;
													$vacantLandTax = $calVacandLandArea*$mrr*1;
													$totalTax = $vacantLandTax;
												}
												$mobileTowerTax = $mobileTowerArea*$mrr*1.5;
												$totalTax += $mobileTowerTax+$hordingBoardTax;

												$isExist = true;
												foreach($lastArvDtl as $key => $mobileVal){
													if($mobileVal['fyID']==$fyVal['id']
														&& $mobileVal['qtr']==$x_Qtr){

														$isExist = false;
														$lastArvDtl[$lastIncreament] = [
															'fyID'=> $fyVal['id'],
															'fy'=> $fyVal['fy'],
															'qtr'=> $x_Qtr,
															'arv'=>0,
															'holding_tax_yearly'=>$totalTax
														];
													}
												}
												if($isExist){
													$lastIncreament++;
													$lastArvDtl[$lastIncreament] = [
														'fyID'=> $fyVal['id'],
														'fy'=> $fyVal['fy'],
														'qtr'=> $x_Qtr,
														'arv'=>0,
														'holding_tax_yearly'=>$totalTax
													];
												}

												$isSafVacantLandMH = true;
												$vacantLandMHDtl[$vacantLandMHDtlIncreament] = [
													'type'=>'MOBILE TOWER',
													'area_sqm'=>$mobileTowerArea,
													'usage_factor'=>$mrr,
													'occupancy_factor'=>1.5,
													'fy'=> $fyVal['fy'],
													'qtr'=> $x_Qtr,
													'yearly_tax'=>round($mobileTowerTax, 2)
												];
												$vacantLandMHDtlIncreament++;
											}
										}

										if($keyy=="hording" && $x_Qtr!=0 && $x_Qtr!=null)
										{
											if (!$hoadingBoardOneTimeImpliment){
												$hoadingBoardOneTimeImpliment = true;
												$mobileTowerTax = 0;
												if ($isVacantLandTemp==false || $x_Qtr!=$vacand_land_qtr_temp) {
													$calVacandLandArea = $vacantlandArea;
													if($isMobileTowerIncreaseTemp==true && $x_Qtr!=$mobile_tower_qtr_temp && $mobileTowerOneTimeImpliment==true){
														$calVacandLandArea = $calVacandLandArea-$mobileTowerArea;
														$mobileTowerTax = $mobileTowerArea*$mrr*1.5;
													}
													$calVacandLandArea = $calVacandLandArea-$hoardingBoardArea;
													$vacantLandTax = $calVacandLandArea*$mrr*1;
													$totalTax = $vacantLandTax;
												}
												$hordingBoardTax = $hoardingBoardArea*$mrr*1.5;
												$totalTax += $hordingBoardTax+$mobileTowerTax;

												$isExist = true;
												foreach($lastArvDtl as $key => $mobileVal){
													if($mobileVal['fyID']==$fyVal['id']
														&& $mobileVal['qtr']==$x_Qtr){

														$isExist = false;
														$lastArvDtl[$lastIncreament] = [
															'fyID'=> $fyVal['id'],
															'fy'=> $fyVal['fy'],
															'qtr'=> $x_Qtr,
															'arv'=>0,
															'holding_tax_yearly'=>$totalTax
														];

													}
												}
												if($isExist){
													$lastIncreament++;
													$lastArvDtl[$lastIncreament] = [
														'fyID'=> $fyVal['id'],
														'fy'=> $fyVal['fy'],
														'qtr'=> $x_Qtr,
														'arv'=>0,
														'holding_tax_yearly'=>$totalTax
													];
												}
												$isSafVacantLandMH = true;
												$vacantLandMHDtl[$vacantLandMHDtlIncreament] = [
													'type'=>'HOARDING BOARD',
													'area_sqm'=>$hoardingBoardArea,
													'usage_factor'=>$mrr,
													'occupancy_factor'=>1.5,
													'fy'=> $fyVal['fy'],
													'qtr'=> $x_Qtr,
													'yearly_tax'=>round($hordingBoardTax, 2)
												];
												$vacantLandMHDtlIncreament++;
											}
										}
									}
									
								}
								
								foreach ($lastArvDtl as $key => $value){
									$safTaxDtl[$safTaxIncreament] = [
										'fyID'=> $fyVal['id'],
										'fy'=> $fyVal['fy'],
										'qtr'=> $value['qtr'],
										'arv'=>0,
										'holding_tax_yearly'=>round($value['holding_tax_yearly'], 2),
										'holding_tax_qtr'=>round(($value['holding_tax_yearly']/4), 2)
									];
									$safTaxIncreament++;
								}
							} // end financial year foreach
							
							$data['isCurrentFinancialYearEffected'] = true;
							$data['vacantLandDtl'] = $vacantLandDtl;
							$data['safTaxDtl'] = $safTaxDtl;
							$data['isSafVacantLandMH'] = $isSafVacantLandMH;
							$data['vacantLandMHDtl'] = $vacantLandMHDtl;
						}
						else
						{
							// building
							$currentFY = getFY();
							$currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];

							$taxEffectedFrom = date('Y-04-01', strtotime('-12 year'));
							$taxEffectedFromFY = getFY($taxEffectedFrom);
							$taxEffectedFromFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$taxEffectedFromFY])['id'];
							//$dateOfEffect = (explode("-", $currentFY)[1])."-04-01";
							//$tax = $currentFyID-12;

							$yrOfEffect_16_17_FY = getFY("2016-04-01");
					    	$yrOfEffect_16_17_FyID = $this->model_fy_mstr->getFyByFy(['fy'=>$yrOfEffect_16_17_FY])['id'];

							$is_16_17_1st_qtr_tax_implement = false;

							$floorDtlArr = [];
							$j = 0;
							for($i=0; $i<sizeof($inputs['floor_mstr_id']); $i++){

								$floorDateFromFY = getFY($inputs['date_from'][$i]);
								$floorDateFromFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$floorDateFromFY])['id'];
								$MM = date("m", strtotime($inputs['date_from'][$i]));
								if($MM>=1 && 3>=$MM){ // X1
									$temp_qtr = 4;
								}else if($MM>=4 && 6>=$MM){ // X4
									$temp_qtr = 1;
								}else if($MM>=7 && 9>=$MM){ // X3
									$temp_qtr = 2;
								}else if($MM>=10 && 12>=$MM){ // X2
									$temp_qtr = 3;
								}

								$floorDateUptoFyID = 0;
								$floorDateUptoQtr = 0;
								$floorDateUptoQtrTemp = 0;
								if ($inputs['date_upto'][$i]<>"") {
									$floorDateUptoFY = getFY($inputs['date_upto'][$i]);
									$floorDateUptoFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$floorDateUptoFY])['id'];
									$MM = date("m", strtotime($inputs['date_upto'][$i]));
									if($MM>=1 && 3>=$MM){ // X1
										$floorDateUptoQtr = 4;
									}else if($MM>=4 && 6>=$MM){ // X4
										$floorDateUptoQtr = 1;
									}else if($MM>=7 && 9>=$MM){ // X3
										$floorDateUptoQtr = 2;
									}else if($MM>=10 && 12>=$MM){ // X2
										$floorDateUptoQtr = 3;
									}
									$floorDateUptoQtrTemp = $floorDateUptoQtr;
								}

								if ($inputs['date_from'][$i]."-01" < $taxEffectedFrom) {
									$floorDateFromFyID = $taxEffectedFromFyID;
									$temp_qtr = 1;
								}

								$floorDtlArr[$j] = [
									'type'=>'floor',
									'floor_mstr_id'=>$inputs['floor_mstr_id'][$i],
									'usage_type_mstr_id'=>$inputs['usage_type_mstr_id'][$i],
									'occupancy_type_mstr_id'=>$inputs['occupancy_type_mstr_id'][$i],
									'const_type_mstr_id'=>$inputs['const_type_mstr_id'][$i],
									'builtup_area'=>$inputs['builtup_area'][$i],
									'date_from'=>$inputs['date_from'][$i],
									'date_upto'=>$inputs['date_upto'][$i],
									'fy_mstr_id'=>$floorDateFromFyID,
									'qtr'=>$temp_qtr,
									'upto_fy_mstr_id'=>$floorDateUptoFyID,
									'upto_qtr'=>$floorDateUptoQtr,
									'operator'=>'+'
								];
								$j++;

								if ( $floorDateUptoFyID<>0 && $floorDateUptoQtr<>0 ) {
									if ( $floorDateUptoQtr==4 ) {
										$floorDateUptoQtr = 1;
										$floorDateUptoFyID = $floorDateUptoFyID+1;
									}else {
										$floorDateUptoQtr = $floorDateUptoQtr+1;
									}
									$date_upto = $inputs['date_upto'][$i];
									if ( $floorDateUptoQtrTemp==1 ) {
										$date_upto = date("Y", strtotime($inputs['date_upto'][$i]))."-09";
									} else if ( $floorDateUptoQtrTemp==2 ) {
										$date_upto = date("Y", strtotime($inputs['date_upto'][$i]))."-12";
									} else if ( $floorDateUptoQtrTemp==3 ) {
										$YYYY = date("Y", strtotime($inputs['date_upto'][$i]));
										$YYYY = $YYYY+1;
										$date_upto = $YYYY."-03";
									} else if ( $floorDateUptoQtrTemp==4 ) {
										$date_upto = date("Y", strtotime($inputs['date_upto'][$i]))."-06";
									}
									$floorDtlArr[$j] = [
										'type'=>'floor',
										'floor_mstr_id'=>$inputs['floor_mstr_id'][$i],
										'usage_type_mstr_id'=>$inputs['usage_type_mstr_id'][$i],
										'occupancy_type_mstr_id'=>$inputs['occupancy_type_mstr_id'][$i],
										'const_type_mstr_id'=>$inputs['const_type_mstr_id'][$i],
										'builtup_area'=>$inputs['builtup_area'][$i],
										'date_from'=>$date_upto,
										'date_upto'=>$date_upto,
										'fy_mstr_id'=>$floorDateUptoFyID,
										'qtr'=>$floorDateUptoQtr,
										'upto_fy_mstr_id'=>$floorDateUptoFyID,
										'upto_qtr'=>$floorDateUptoQtr,
										'operator'=>'-'
									];
									$j++;
								}
							}

							$mobileTowerFyID = 0;
							$mobileTowerQtr = 0;
							if($inputs['is_mobile_tower']==1){
								$mobileTowerFY = getFY($inputs['tower_installation_date']);
								$mobileTowerFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$mobileTowerFY])['id'];
								$MM = date("m", strtotime($inputs['tower_installation_date']));
								if($MM>=1 && 3>=$MM){ // X1
									$temp_qtr = 4;
								}else if($MM>=4 && 6>=$MM){ // X4
									$temp_qtr = 1;
								}else if($MM>=7 && 9>=$MM){ // X3
									$temp_qtr = 2;
								}else if($MM>=10 && 12>=$MM){ // X2
									$temp_qtr = 3;
								}
								$mobileTowerQtr = $temp_qtr;
								if( $yrOfEffect_16_17_FyID==$mobileTowerFyID && $temp_qtr==1 ) {
									$is_16_17_1st_qtr_tax_implement = true;
								}
								$date_from = "2016-04";
								if( date("Y-m-01", strtotime($inputs['tower_installation_date'])) > "2016-04-01" ) {
									$date_from = date("Y-m", strtotime($inputs['tower_installation_date']));
								} else {
									$mobileTowerFyID = $yrOfEffect_16_17_FyID;
									$mobileTowerQtr = 1;
								}

								$floorDtlArr[$j] = [
									'type'=>'mobile',
									'floor_mstr_id'=>0,
									'usage_type_mstr_id'=>0,
									'occupancy_type_mstr_id'=>0,
									'const_type_mstr_id'=>1,
									'builtup_area'=>$inputs['tower_area'],
									'date_from'=>$date_from,
									'date_upto'=>"",
									'fy_mstr_id'=>$mobileTowerFyID,
									'qtr'=>$mobileTowerQtr,
									'upto_fy_mstr_id'=>0,
									'upto_qtr'=>0,
									'operator'=>'+'
								];
								$j++;
							}

							$hoardinBoardFyID = 0;
							$hoardinBoardQtr = 0;
							if($inputs['is_hoarding_board']==1){
								$hoardinBoardFY = getFY($inputs['hoarding_installation_date']);
								$hoardinBoardFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$hoardinBoardFY])['id'];
								$MM = date("m", strtotime($inputs['hoarding_installation_date']));
								if($MM>=1 && 3>=$MM){ // X1
									$temp_qtr = 4;
								}else if($MM>=4 && 6>=$MM){ // X4
									$temp_qtr = 1;
								}else if($MM>=7 && 9>=$MM){ // X3
									$temp_qtr = 2;
								}else if($MM>=10 && 12>=$MM){ // X2
									$temp_qtr = 3;
								}
								$hoardinBoardQtr = $temp_qtr;
								/* if( $yrOfEffect_16_17_FyID==$hoardinBoardFyID && $temp_qtr==1 ) {
									$is_16_17_1st_qtr_tax_implement = true;
								} */

								$date_from = "2016-04";
								if( date("Y-m-01", strtotime($inputs['hoarding_installation_date'])) > "2016-04-01" ) {
									$date_from = date("Y-m", strtotime($inputs['hoarding_installation_date']));
								} else {
									$hoardinBoardFyID = $yrOfEffect_16_17_FyID;
									$hoardinBoardQtr = 1;
								}

								$floorDtlArr[$j] = [
									'type'=>'hoarding',
									'floor_mstr_id'=>0,
									'usage_type_mstr_id'=>0,
									'occupancy_type_mstr_id'=>0,
									'const_type_mstr_id'=>1,
									'builtup_area'=>$inputs['hoarding_area'],
									'date_from'=>$date_from,
									'date_upto'=>"",
									'fy_mstr_id'=>$hoardinBoardFyID,
									'qtr'=>$hoardinBoardQtr,
									'upto_fy_mstr_id'=>0,
									'upto_qtr'=>0,
									'operator'=>'+'
								];
								$j++;
							}

							$petrolPumpFyID = 0;
							$petrolPumpQtr = 0;
							if($inputs['is_petrol_pump']==1 && $inputs['prop_type_mstr_id']!=4){
								$petrolPumpFY = getFY($inputs['petrol_pump_completion_date']);
								$petrolPumpFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$petrolPumpFY])['id'];
								$MM = date("m", strtotime($inputs['petrol_pump_completion_date']));
								if($MM>=1 && 3>=$MM){ // X1
									$temp_qtr = 4;
								}else if($MM>=4 && 6>=$MM){ // X4
									$temp_qtr = 1;
								}else if($MM>=7 && 9>=$MM){ // X3
									$temp_qtr = 2;
								}else if($MM>=10 && 12>=$MM){ // X2
									$temp_qtr = 3;
								}
								$petrolPumpQtr = $temp_qtr;

								/* if( $yrOfEffect_16_17_FyID==$petrolPumpFyID && $temp_qtr==1 ) {
									$is_16_17_1st_qtr_tax_implement = true;
								} */
								$date_from = "2016-04";
								if( date("Y-m-01", strtotime($inputs['petrol_pump_completion_date'])) > "2016-04-01" ) {
									$date_from = date("Y-m", strtotime($inputs['petrol_pump_completion_date']));
								} else {
									$petrolPumpFyID = $yrOfEffect_16_17_FyID;
									$petrolPumpQtr = 1;
								}

								$floorDtlArr[$j] = [
									'type'=>'petrol',
									'floor_mstr_id'=>0,
									'usage_type_mstr_id'=>0,
									'occupancy_type_mstr_id'=>0,
									'const_type_mstr_id'=>1,
									'builtup_area'=>$inputs['under_ground_area'],
									'date_from'=>$date_from,
									'date_upto'=>"",
									'fy_mstr_id'=>$petrolPumpFyID,
									'qtr'=>$petrolPumpQtr,
									'upto_fy_mstr_id'=>0,
									'upto_qtr'=>0,
									'operator'=>'+'
								];
								$j++;
							}

							usort($floorDtlArr, 'floor_date_compare');

							/* echo "<pre>";
							print_r($floorDtlArr);
							echo "</pre>"; */

							$isWaterHarvesting = false;
							$area_of_plot = ($inputs['area_of_plot']*40.5);
							if($area_of_plot > 300){
								$isWaterHarvesting = true;
								if($inputs['is_water_harvesting']==1){
									$isWaterHarvesting = false;
								}
							}

							$FromEffectFYID = 0;
							$prop_type_mstr_arr = array(1,5);
					    	if(in_array($inputs["prop_type_mstr_id"], $prop_type_mstr_arr)){
					    			$FromEffectFYID = $yrOfEffect_16_17_FyID;
					    	}else{
								$FromEffectFYID = $currentFyID-12;
					    	}

							$getFyList = $this->model_fy_mstr->getFyByIdRangeAsc(['fromId'=>$FromEffectFYID, 'toId'=>$currentFyID]);
							$isSafOldRuleArv = false;
							$safOldRuleArv = [];
							$safOldRuleArvIncreament = 0;
							$isSafNewRuleArv = false;
							$safNewRuleArv = [];
							$safNewRuleArvIncreament = 0;
							$isSafMHPArv = false;
							$safMHPArv = [];
							$safMHPArvIncreament = 0;
							$isCurrentFinancialYearEffected = false;
							$safTaxIncreament = 0;
							foreach ($getFyList as $fyVal) {
								$totalArv = 0;
								$totalArvReduce = 0;
								$dateOfEffect = (explode("-", $fyVal['fy'])[1])."-04-01";

								$lastArvDtl = [];
								$lastIncreament = -1;
								$lastQtr = 0;
								$jj = 0;

								foreach ($floorDtlArr as $key => $floorDtl) {

									$floorDateFromFyID = $floorDtl['fy_mstr_id'];

									if ($fyVal['id']>=$floorDateFromFyID ){
										$floorDateUptoFyID = $currentFyID;
										if ($floorDtl['date_upto']!="") {
											$floorDateUptoFyID = $floorDtl['upto_fy_mstr_id'];
										}
										if ($fyVal['id']<=$floorDateUptoFyID) {

											$isArrear = false;
											if ($yrOfEffect_16_17_FyID > $fyVal['id']) {
												if ($floorDtl['type']=="floor") {
													$isArrear = true;
													$carperArea = $floorDtl['builtup_area'];

													$usage_type_mstr_id = 2;
													if ($floorDtl['occupancy_type_mstr_id']==1
														&& $floorDtl['usage_type_mstr_id']==1) {
														$usage_type_mstr_id = 1;
													}
													$sendInput = [
														'usage_type_mstr_id'=>$usage_type_mstr_id,
														'const_type_mstr_id'=>$floorDtl['const_type_mstr_id'],
														'zone_mstr_id'=>$inputs['zone_mstr_id']
													];
													$mrr = $this->model_arr_old_building_mstr->getMRRCalRate($sendInput)['rate'];
													if (!$mrr){ $mrr = 0; }
													$arv = $carperArea*$mrr;

													//echo $carperArea."x".$mrr." = ".$arv." => ".$floorDtl['date_from']."<br />";

													$arvRebate = 0;
													if ($floorDtl['type']=="floor") {
														if ($usage_type_mstr_id==1) {
															$arvRebate += ($arv*30)/100;
														} else if ($usage_type_mstr_id==2) {
															$arvRebate += ($arv*15)/100;
														}
														if ($inputs["prop_type_mstr_id"]==2
															&& $floorDtl['occupancy_type_mstr_id']==1
															&& $floorDtl['usage_type_mstr_id']==1) {
															$rebate_date = $floorDtl['date_from']."-01";
															if ("1942-04-01">$rebate_date) {
																if ( $arv!=0 ) {
																	$arvRebate += (($arv*10)/100);
																}
															}
														}
													}
													$arv -= $arvRebate;
													if ( $floorDtl['operator']=="+" ) {
														$totalArv += $arv;
													} else if ( $floorDtl['operator']=="-" ) {
														$totalArv -= $arv;
													}

													//echo "<br />";

													//echo $carperArea."x".$mrr." = , ARV => ".$floorDtl['operator'].$arv.", Total ARV => ".$totalArv.", date_from => ".$floorDtl['date_from']."FY => ".$fyVal['id']."<br />";

													if ($fyVal['id']==$floorDateFromFyID) {
														$temp_qtr = $floorDtl['qtr'];

														if ( $floorDtl['operator']=="+" ) {
															$isSafOldRuleArv = true;
															$safOldRuleArv[$safOldRuleArvIncreament] = [
																'usage_type'=>$floorDtl['usage_type_mstr_id'],
																'rental_rate'=>$mrr,
																'buildup_area'=>$carperArea,
																'qtr'=>$temp_qtr,
																'fy'=>$fyVal['fy'],
																'arv'=>round($arv, 2)

															];
															$safOldRuleArvIncreament++;
														}
														if ($lastQtr!=$temp_qtr) {
															$lastQtr=$temp_qtr;
															$lastIncreament++;
															$lastArvDtl[$lastIncreament] = [
																'fyID'=> $fyVal['id'],
																'arv'=>round($totalArv, 2),
																'qtr'=>$temp_qtr
															];
														}else{
															$lastArvDtl[$lastIncreament] = [
																'fyID'=> $fyVal['id'],
																'arv'=>round($totalArv, 2),
																'qtr'=>$temp_qtr
															];
														}
													}
												} // only floor effected
											} // old rule effected if condition

											if ($yrOfEffect_16_17_FyID <= $fyVal['id']) {
												if (!$isArrear) {

													if ( $fyVal['id']==$yrOfEffect_16_17_FyID ) {
														if ( !$is_16_17_1st_qtr_tax_implement ) {
															$oldARVTotal = 0;
															foreach ($floorDtlArr as $key => $floorDtlTemp) {
																if ($floorDtlTemp['type']=="floor") {
																	$floorDateFromFyIDTemp = $floorDtlTemp['fy_mstr_id'];

																	if ($yrOfEffect_16_17_FyID > $floorDateFromFyIDTemp) {

																		$isCurrentFinancialYearEffected = true;

																		$floorDateUptoFyID = $currentFyID;
																		if ($floorDtlTemp['date_upto']!="") {
																			$floorDateUptoFY = getFY($floorDtlTemp['date_upto']);
																			$floorDateUptoFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$floorDateUptoFY])['id'];
																		}

																		if ($yrOfEffect_16_17_FyID <= $floorDateUptoFyID) {

																			$afr = $this->model_occupancy_type_mstr->getOccupancyMultFactById($floorDtlTemp['occupancy_type_mstr_id'])['mult_factor'];
																			if(!$afr){ $afr = 0;}
																			if($floorDtlTemp['usage_type_mstr_id']==1){
																				$carperArea = (($floorDtlTemp['builtup_area']*70)/100);
																			}else{
																				$carperArea = (($floorDtlTemp['builtup_area']*80)/100);
																			}
																			$sendInput = ['usage_type_mstr_id'=>$floorDtlTemp['usage_type_mstr_id']];
																			$mf = $this->model_usage_type_dtl->getUsageTypeMultFact($sendInput)['mult_factor'];
																			if(!$mf){ $mf = 0;}

																			$sendInput = ['road_type_mstr_id'=>$inputs['road_type_mstr_id'], 'const_type_mstr_id'=>$floorDtlTemp['const_type_mstr_id'], 'date_of_effect'=>$dateOfEffect];
																			$mrr = $this->model_arr_building_mstr->getMRRCalRate($sendInput)['cal_rate'];
																			if(!$mrr){ $mrr = 0; }

																			$arv = $afr*$mf*$carperArea*$mrr;
																			//echo $afr."x".$mf."x".$carperArea."x".$mrr." = ".$arv." => ".$floorDtlTemp['date_from']."<br />";
																			if ($floorDtl['type']=="floor") {
																				if($inputs["prop_type_mstr_id"]==2
																					&& $floorDtlTemp['occupancy_type_mstr_id']==1
																					&& $floorDtlTemp['usage_type_mstr_id']==1){
																					$rebate_date = $floorDtlTemp['date_from']."-01";
																					if("1942-04-01">$rebate_date){
																						if($arv!=0){
																							$arvRebate = (($arv*10)/100);
																							$arv = $arv - $arvRebate;
																						}
																					}
																				}
																			}
																			if ( $arv!=0 ) {
																				if ( $floorDtlTemp['operator']=="+" ) {
																					$isSafNewRuleArv = true;
																					$safNewRuleArv[$safNewRuleArvIncreament] = [
																						'usage_factor'=>$mf,
																						'occupancy_factor'=>$afr,
																						'rental_rate'=>$mrr,
																						'carpet_area'=>$carperArea,
																						'qtr'=>1,
																						'fy'=>$fyVal['fy'],
																						'arv'=>round($arv, 2)
																					];
																					$safNewRuleArvIncreament++;
																				}
																				if ( $floorDtl['operator']=="+" ) {
																					$oldARVTotal += $arv;
																				} else if ( $floorDtl['operator']=="-" ) {
																					$oldARVTotal -= $arv;
																				}
																				//$oldARVTotal += $arv;
																			}
																		}
																	}
																}
															}
															if ($oldARVTotal > 0) {
																$safTax = $oldARVTotal;
																$holding_tax = $safTax*0.02;
																$additional_tax = 0;
																if ($isWaterHarvesting==0) {
																	$waterHarvestingTax = $holding_tax*1.5;
																	$additional_tax = $waterHarvestingTax - $holding_tax;
																	if($additional_tax!=0){
																		$additional_tax = round(($additional_tax/4), 2);
																	}
																}
																if($holding_tax!=0){
																	$holding_tax = round(($holding_tax/4), 2);
																}

																$isSafNewRuleArv = true;
																$lastIncreament++;
																$lastArvDtl[$lastIncreament] = [
																	'fyID'=> $fyVal['id'],
																	'arv'=>$oldARVTotal,
																	'qtr'=>1
																];
																$is_16_17_1st_qtr_tax_implement = true;
															}
														} // if new rule is implimented or not
													} // end if old rule is not implimented in new rule

													if ($floorDtl['type']=="floor") {
														$afr = $this->model_occupancy_type_mstr->getOccupancyMultFactById($floorDtl['occupancy_type_mstr_id'])['mult_factor'];
														if(!$afr){ $afr = 0;}
													} else {
														$afr = 1.5;
													}

													if ($floorDtl['type']=="floor") {
														if($floorDtl['usage_type_mstr_id']==1){
															$carperArea = (($floorDtl['builtup_area']*70)/100);
														}else{
															$carperArea = (($floorDtl['builtup_area']*80)/100);
														}
													} else {
														$carperArea = $floorDtl['builtup_area'];
													}
													if ($floorDtl['type']=="floor") {
														$sendInput = ['usage_type_mstr_id'=>$floorDtl['usage_type_mstr_id']];
														$mf = $this->model_usage_type_dtl->getUsageTypeMultFact($sendInput)['mult_factor'];
														if(!$mf){ $mf = 0;}
													} else {
														$mf = 1.5;
													}

													$sendInput = ['road_type_mstr_id'=>$inputs['road_type_mstr_id'], 'const_type_mstr_id'=>$floorDtl['const_type_mstr_id'], 'date_of_effect'=>$dateOfEffect];
													$mrr = $this->model_arr_building_mstr->getMRRCalRate($sendInput)['cal_rate'];
													if(!$mrr){ $mrr = 0; }

													$arv = $afr*$mf*$carperArea*$mrr;
													//echo $afr."x".$mf."x".$carperArea."x".$mrr." = ".$arv." => ".$floorDtl['date_from']."<br />";
													if ($floorDtl['type']=="floor") {
														if($inputs["prop_type_mstr_id"]==2
															&& $floorDtl['occupancy_type_mstr_id']==1
															&& $floorDtl['usage_type_mstr_id']==1){
															$rebate_date = $floorDtl['date_from']."-01";
															if("1942-04-01">$rebate_date){
																if($arv!=0){
																	$arvRebate = (($arv*10)/100);
																	$arv = $arv - $arvRebate;
																}
															}
														}
													}
													//$totalArv += $arv;
													if ( $floorDtl['operator']=="+" ) {
														$totalArv += $arv;
													} else if ( $floorDtl['operator']=="-" ) {
														$totalArv -= $arv;
													}

													if($fyVal['id']==$floorDateFromFyID){
														$isCurrentFinancialYearEffected = true;
														/* echo "TYPE : ".$floorDtl['type'].", floor : ".$floorDtl['floor_mstr_id'].", date_from : ".$floorDtl['date_from'].", date_upto : ".$floorDtl['date_upto'];
														//echo "<br />"; */
														$temp_qtr = $floorDtl['qtr'];
														//echo $afr."x".$mf."x".$carperArea."x".$mrr."<br />";

														if ($floorDtl['type']=="floor") {
															if ( $floorDtl['operator']=="+" ) {
																$isSafNewRuleArv = true;
																$safNewRuleArv[$safNewRuleArvIncreament] = [
																	'usage_factor'=>$mf,
																	'occupancy_factor'=>$afr,
																	'rental_rate'=>$mrr,
																	'carpet_area'=>$carperArea,
																	'qtr'=>$temp_qtr,
																	'fy'=>$fyVal['fy'],
																	'arv'=>round($arv, 2)
																];
																$safNewRuleArvIncreament++;
															}
														} else {
															//echo "TYPE : ".$floorDtl['type'].", floor : ".$floorDtl['floor_mstr_id'].", date_from : ".$floorDtl['date_from'].", date_upto : ".$floorDtl['date_upto'];
															//echo "<br />";
															if ($floorDtl['type']=="mobile") {
																$isSafMHPArv = true;
																$safMHPArv[$safMHPArvIncreament] = [
																	'type'=>'Mobile Tower',
																	'area_type'=>"Total Area Covered by Mobile Tower & its
																					Supporting Equipments & Accessories (in Sq. Ft.)",
																	'usage_factor'=>1.5,
																	'occupancy_factor'=>1.5,
																	'rental_rate'=>$mrr,
																	'area'=>$carperArea,
																	'qtr'=>$temp_qtr,
																	'fy'=>$fyVal['fy'],
																	'arv'=>round($arv, 2)
																];
																$safMHPArvIncreament++;
															}
															if ($floorDtl['type']=="hoarding") {
																$isSafMHPArv = true;
																$safMHPArv[$safMHPArvIncreament] = [
																	'type'=>'Hoarding Board',
																	'area_type'=>"Total Area of Wall / Roof / Land (in Sq. Ft.)",
																	'usage_factor'=>1.5,
																	'occupancy_factor'=>1.5,
																	'rental_rate'=>$mrr,
																	'area'=>$carperArea,
																	'qtr'=>$temp_qtr,
																	'fy'=>$fyVal['fy'],
																	'arv'=>round($arv, 2)
																];
																$safMHPArvIncreament++;
															}
															if ($floorDtl['type']=="petrol") {
																$isSafMHPArv = true;
																$safMHPArv[$safMHPArvIncreament] = [
																	'type'=>'Petrol Pump',
																	'area_type'=>"Underground Storage Area",
																	'usage_factor'=>1.5,
																	'occupancy_factor'=>1.5,
																	'rental_rate'=>$mrr,
																	'area'=>$carperArea,
																	'qtr'=>$temp_qtr,
																	'fy'=>$fyVal['fy'],
																	'arv'=>round($arv, 2)
																];
																$safMHPArvIncreament++;
															}
														}


														$isExist = true;
														foreach($lastArvDtl as $key => $tempLastArvDtl){
															if($tempLastArvDtl['fyID']==$fyVal['id']
																&& $tempLastArvDtl['qtr']==$temp_qtr){

																$isExist = false;
																$lastArvDtl[$key] = [
																	'fyID'=> $fyVal['id'],
																	'arv'=>$totalArv,
																	'qtr'=>$temp_qtr
																];
															}
														}
														if($isExist){
															$lastIncreament++;
															$lastArvDtl[$lastIncreament] = [
																'fyID'=> $fyVal['id'],
																'arv'=>$totalArv,
																'qtr'=>$temp_qtr
															];
														}

													}

												}
											} // new rule effected
										}


									}


								} //end floorDtlArr foreach loop

								foreach($lastArvDtl as $key => $value){
									//if ($value['arv']>0) {
										$holding_tax = 0;
										$water_tax = 0;
										$education_cess = 0;
										$health_cess = 0;
										$latrine_tax = 0;
										$additional_tax = 0;
										$safTaxQtr = $value['arv'];
										if($yrOfEffect_16_17_FyID > $fyVal['id']){
											$holding_tax = $safTaxQtr*0.125;
											if($holding_tax!=0){
												$holding_tax = round(($holding_tax/4), 2);
											}
											$water_tax = $safTaxQtr*0.075;
											if($water_tax!=0){
												$water_tax = round(($water_tax/4), 2);
											}
											$education_cess = $safTaxQtr*0.05;
											if($education_cess!=0){
												$education_cess = round(($education_cess/4), 2);
											}
											$health_cess = $safTaxQtr*0.0625;
											if($health_cess!=0){
												$health_cess = round(($health_cess/4), 2);
											}
											$latrine_tax = $safTaxQtr*0.075;
											if($latrine_tax!=0){
												$latrine_tax = round(($latrine_tax/4), 2);
											}
										}else{
											$holding_tax = $safTaxQtr*0.02;
											if($isWaterHarvesting){
												$waterHarvestingTax = $holding_tax*1.5;
												$additional_tax = $waterHarvestingTax - $holding_tax;
												if($additional_tax!=0){
													$additional_tax = round(($additional_tax/4), 2);
												}
											}
											if($holding_tax!=0){
												$holding_tax = round(($holding_tax/4), 2);
											}
										}
										$safTaxDtl[$safTaxIncreament] = [
											'fyID'=>$fyVal['id'],
											'fy'=> $fyVal['fy'],
											'arv'=>round($value['arv'], 2),
											'qtr'=>$value['qtr'],
											'holding_tax'=>$holding_tax,
											'water_tax'=>$water_tax,
											'education_cess'=>$education_cess,
											'health_cess'=>$health_cess,
											'latrine_tax'=>$latrine_tax,
											'additional_tax'=>$additional_tax
										];
										$safTaxIncreament++;
									//}
								}
							} // end getFyList foreach loop

							$data['isCurrentFinancialYearEffected'] = $isCurrentFinancialYearEffected;

							$data['isSafOldRuleArv'] =  $isSafOldRuleArv;
							$data['safOldRuleArv'] =  $safOldRuleArv;

							$data['isSafNewRuleArv'] =  $isSafNewRuleArv;
							$data['safNewRuleArv'] =  $safNewRuleArv;

							$data['isSafMHPArv'] =  $isSafMHPArv;
							$data['safMHPArv'] =  $safMHPArv;

							$data['safTaxDtl'] = $safTaxDtl;
						} // end building calculation details
						return view('property/saf/saf_add_update_review', $data);

					}
					else if(isset($_POST['btn_submit']))
					{
						$inputs['ulb_mstr_id'] = $ulb_mstr_id;
						$inputs['emp_details_id'] = $emp_details_id;
						$inputs['ip_address'] = $ip_address;

						if ($saf_dtl_id = $this->addUpdateSubmit($inputs) )
						{
							$LINK = base_url('safDemandPayment/saf_property_details/'.md5($saf_dtl_id));
							return redirect()->to($LINK);
						}
					}
				}
				else
				{
					$data = $inputs;
					$data['validation'] = $errMsg;
					$data['ulb_address'] = $ulb_address;
					$data['wardList'] = $wardList;
					$data['transferModeList'] = $transferModeList;
					$data['ownershipTypeList'] = $ownershipTypeList;
					$data['roadTypeList'] = $roadTypeList;
					$data['propTypeList'] = $propTypeList;
					$data['floorList'] = $floorList;
					$data['usageTypeList'] = $usageTypeList;
					$data['occupancyTypeList'] = $occupancyTypeList;
					$data['constTypeList'] = $constTypeList;
					return view('property/saf/saf_add_update', $data);
				}
			}
			catch(Exception $e)
			{
				//echo $e->getMessage();
			}
		}else{
			return view('property/saf/saf_add_update', $data);
		}
	}

	public function getPrevHoldingDtl(){
		if($this->request->getMethod()=='post'){
			try{
				$inputs = arrFilterSanitizeString($this->request->getVar());
				 $input = [
					'holding_no'=>$inputs['previous_holding_no']
				];
				if($prop_dtl = $this->model_prop_dtl->getPropIdByHodingNoEntryType($input)){
					$input = [
						'prop_dtl_id'=>$prop_dtl['id']
					];
					if($prop_owner_dtl = $this->model_prop_owner_detail->getPropOwnerDtlByPropDtlId($input)){
						$isPaymentCleared = "NO";
						if(!$this->model_prop_demand->getIsDemandClearedByPropDtlId($input)){
							$isPaymentCleared = "YES";
						}
						$response = ["response"=>true, "payment_dtl"=>$isPaymentCleared, "data"=>$prop_owner_dtl];
					}else{
						$response = ["response"=>false, "data"=>"previous owner Details does not exist !!"];
					}
				}else{
					$response = ["response"=>false, "data"=>"previous holder details does not exist !!"];
				}
				echo json_encode($response);
			}catch(Exception $e){

			}
		}else{
			$response = ["response"=>false, "data"=>"it's not a post method !!"];
			echo json_encode($response);
		}
	}

	public function getNewWardDtlByOldWard() {
		if($this->request->getMethod()=='post'){
			try{
				$inputs = arrFilterSanitizeString($this->request->getVar());
				if($old_ward_dtl = $this->model_ward_mstr->getWardNoByOldWardId($inputs['old_ward_mstr_id'])) {
					$ward_split = str_split($old_ward_dtl['ward_no']);
					$number_ward = "";
					foreach($ward_split AS $val) {
						if(is_numeric($val)){
							$number_ward .= $val;
						} ELSE {
							break;
						}
					}
					if($old_ward_dtl = $this->model_ward_mstr->getWardNoByOldWardNo($number_ward)) {
						if($new_ward_list = $this->model_view_ward_mapping_mstr->getNewWardLIstByOldWardMstrId($old_ward_dtl['id'])){
							$option = "<option value=''>== SELECT ==</option>";
							foreach($new_ward_list as $value) {
								$option .= "<option value='".$value['id']."'>".$value['ward_no']."</option>";
							}
							$response = ['response'=>true, 'data'=>$option];
							echo json_encode($response);
						} else {
							$response = ['response'=>false];
							echo json_encode($response);
						}
					} else {
						$response = ['response'=>false];
						echo json_encode($response);
					}
				} else {
					$response = ['response'=>false];
					echo json_encode($response);
				}
			}catch (Exception $e){

			}
		} else {
			$response = ['response'=>false];
			echo json_encode($response);
		}
	}
	public function getUsageFactor(){
		if($usageTypeFactorList = $this->model_usage_type_mstr->getUsageTypeFactorList()) {
			$data['usageTypeFactorList'] = $usageTypeFactorList;
			return view('property/calc_factor/usage_factor', $data);
		}
	}

	
	public function getOccupancyFactor(){
		if($occupancyTypeFactorList = $this->model_occupancy_type_mstr->getOccupancyTypeList()) {
			$data['occupancyTypeFactorList'] = $occupancyTypeFactorList;
			return view('property/calc_factor/occupancy_factor', $data);
		}
	}
	
	
	public function getRentalRateFactor(){
		$rentalRateFactorList = [];
		$input = ['const_type_mstr_id'=>1];
		if($result = $this->model_arr_building_mstr->getJoinRateByRoadConsType($input)) {
			$rentalRateFactorList[0] = $result;
		}
		$input = ['const_type_mstr_id'=>2];
		if($result = $this->model_arr_building_mstr->getJoinRateByRoadConsType($input)) {
			$rentalRateFactorList[1] = $result;

		}
		$input = ['const_type_mstr_id'=>3];
		if($result = $this->model_arr_building_mstr->getJoinRateByRoadConsType($input)) {
			$rentalRateFactorList[2] = $result;
		}
		if (!empty($rentalRateFactorList) ) {
			$data['rentalRateFactorList'] = $rentalRateFactorList;
			return view('property/calc_factor/rental_rate_factor', $data);
		}
	}
	
	

	public function getOldRuleRentalRateFactor(){
		$rentalOldRuleRateFactorList = [];
		$input = ['usage_type_mstr_id'=>1];
		if($result = $this->model_arr_old_building_mstr->getJoinOldRuleRateByRoadConsType($input)) {
			$rentalOldRuleRateFactorList[0] = $result;
		}
		$input = ['usage_type_mstr_id'=>2];
		if($result = $this->model_arr_old_building_mstr->getJoinOldRuleRateByRoadConsType($input)) {
			$rentalOldRuleRateFactorList[1] = $result;

		}
		if (!empty($rentalOldRuleRateFactorList) ) {
			$data['rentalOldRuleRateFactorList'] = $rentalOldRuleRateFactorList;
			return view('property/calc_factor/old_rule_rental_rate_factor', $data);
		}
	}
	
	
	public function getVacantLandRentalRateFactor(){
		if($rentaVacantLandRateFactorList = $this->model_arr_vacant_mstr->getJoinVacantLandRateByRoadType()) {
			$data['rentaVacantLandRateFactorList'] = $rentaVacantLandRateFactorList;
			return view('property/calc_factor/vacant_land_rental_rate_factor', $data);
		}
	}


}
?>
