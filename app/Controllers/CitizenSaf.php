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
use App\Models\model_doc_mstr;
use App\Models\model_saf_doc_dtl;
use App\Models\model_view_saf_doc_dtl;
use App\Models\model_level_pending_dtl;
use App\Models\model_view_saf_dtl;
use App\Models\model_transaction;
use App\Models\model_prop_floor_details;
use App\Models\model_apartment_mstr;
use Exception;
use App\Traits\PropertyTrait;

class CitizenSaf extends HomeController
{
	use PropertyTrait;
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
	protected $model_doc_mstr;
	protected $model_saf_doc_dtl;
	protected $model_view_saf_doc_dtl;
	protected $model_level_pending_dtl;
	protected $model_view_saf_dtl;
	protected $model_transaction;
	protected $model_prop_floor_details;
	protected $model_apartment_mstr;

    public function __construct()
	{
        parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'sms_helper', 'utility_helper']);
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
		$this->model_doc_mstr = new model_doc_mstr($this->db);
		$this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
		$this->model_view_saf_doc_dtl = new model_view_saf_doc_dtl($this->db);
		$this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
		$this->model_transaction = new model_transaction($this->db);
		$this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
		$this->model_prop_floor_details = new model_prop_floor_details($this->db);
		$this->model_apartment_mstr = new model_apartment_mstr($this->db);
    }

	function __destruct() {
		if (isset($this->db)) $this->db->close();
		if (isset($this->dbSystem)) $this->dbSystem->close();
	}

	public function sendApplicantOptInMobile()
	{
		if($this->request->getMethod()=='post')
		{
			$inputs = arrFilterSanitizeString($this->request->getVar());
			$session = session();
			$randomNumber = rand(1000, 9999); 
			$otp = $session->set("saf_opt", $randomNumber);
			if($session->has("saf_opt"))
			{
				$message="Your OTP to proceed SAF Application is: $randomNumber, SUDA";
				//$message="Your OTP to proceed SAF Application is: $randomNumber";
				$response=SMSJHGOVT($inputs['mobile_no'], $message, "1307162359702123175");
				
				if($response["status"]=="failure")
				{
					$response = ["response"=>false, "data"=>$response["warnings"][0]["message"]];
				}
				else
				{
					$response = ["response"=>true];
				}
				echo json_encode($response);
			}
		} 
	}

	public function applicantOptVerify()
	{
		if($this->request->getMethod()=='post'){
			$inputs = arrFilterSanitizeString($this->request->getVar());
			$session = session();
			if ($session->has("saf_opt")) {
				$session_otp = $session->get("saf_opt");
				$get_otp = $inputs["otp"];
				if ($session_otp==$get_otp) {
					$response = ["response"=>true];
				} else {
					$response = ["response"=>false];
				}
			}else {
				$response = ["response"=>false];
			}
			echo json_encode($response);
		}
	}

	
	public function safmanual()
	{
		if($this->request->getMethod()=='post')
		{
			try
			{
				$inputs = arrFilterSanitizeString($this->request->getVar());
				$input = [
                    'holding_id'=>$inputs['holding_id'],
					'ward_mstr_id'=>$inputs['ward_mstr_id'],
					'holding_no'=>$inputs['holding_no']
                ];
                
				if($prop_dtl = $this->model_prop_dtl->getPropIdByWardNoHodingNo($input)){
					$input = [
						'prop_dtl_id'=>$prop_dtl['id']
					];
					$isPaymentCleared = "NO";
					if (!$this->model_prop_demand->getIsDemandClearedByPropDtlId($input)) {
						$isPaymentCleared = "YES";
					}
					$prop_dtl['isPaymentCleared'] = $isPaymentCleared;

					$process = false;
					
					if(isset($inputs['YES']))
					{
						$process = true;
						$assessmentType = "Re-Assessment";
					}
					else if(isset($inputs['NO']))
					{
						if($isPaymentCleared=="YES")
						{
							$process = true;
							$assessmentType = "Mutation";
						}
					}
					if ($process) {
						$parameter = md5($inputs['holding_id'])."::".hashEncrypt($assessmentType);
						return redirect()->to(base_url('CitizenSaf/AddUpdate/'.$parameter));
					} else {
						flashToast('safmanual', "Please clear your dues before proceeding...");
						return redirect()->to(base_url('CitizenSaf/safmanual'));
					}
				}
			}catch(Exception $e){

			}
		} else {
			$ulb_mstr_id = 1;//$_SESSION['ulb_dtl']['ulb_mstr_id'];
			$wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
			$data['wardList'] = $wardList;
			return view('Citizen/SAF/safmanual', $data);
		}
	}

	public function searchApplication()
	{
		//$session = session();		
		$data = [];
		if($this->request->getMethod()=='post'){
			try{
				$inputs = arrFilterSanitizeString($this->request->getVar());
				$data = $inputs;
				$loginCaptchaSessionCitizen = cGetCookie("loginCaptchaSessionCitizen");
				cDeleteCookie("loginCaptchaSessionCitizen");
				if ($loginCaptchaSessionCitizen) {
					if($loginCaptchaSessionCitizen==$inputs['captcha_code']) {
						if ($inputs['saf_no']=="") {
							$data['saf_no_err'] = "Acknowledgement no field is required !!!";
						} else if($saf_dtl = $this->model_view_saf_dtl->getSafDtlBySafno($inputs['saf_no'])) {
							$holding = $this->model_prop_dtl->getHoldingDtlBySafDtlId(['saf_dtl_id' => $saf_dtl['id']]);
							if(isset($holding) && $holding['status']==0){
								$data['saf_no_err'] = "Holding Deactivated !!!";
							}else{
								cSetCookie('saf_dtl', $saf_dtl);
								return redirect()->to(base_url('CitizenDtl/my_application'));
							}

						} else {
							$data['saf_no_err'] = "Acknowledgement no does not match !!!";
						}
					} else {
						$data['captcha_err'] = "Captcha code does not match !!!";
					}
				} else {
					$data['captcha_err'] = "Captcha code does not match !!!";
				}
			} catch(Exception $e) {}
		}
		//$session->set('captcha_code', $data['captcha_code']);
		return view('Citizen/SAF/searchApplication', $data);
	}

    
    public function getDtlByPrevHoldingNo()
	{
        if($this->request->getMethod()=='post'){
			try{
				$inputs = arrFilterSanitizeString($this->request->getVar());
				
				$input = [
                    'ward_mstr_id'=>$inputs['ward_mstr_id'],
					'holding_no'=>$inputs['holding_no']
                ];
                
				if($prop_dtl = $this->model_prop_dtl->getPropIdByWardNoHodingNo($input)){
					$isPaymentCleared = "NO";
					if (!$this->model_prop_demand->getIsDemandClearedByPropDtlId($input)) {
						$isPaymentCleared = "YES";
					}
					$data['prop_dtl'] = $prop_dtl;
					$data['payment_dtl'] = $isPaymentCleared;
					$response = ["response"=>true, "data"=>$data];
				}else{
					$response = ["response"=>false, "data"=>"previous holding details does not exist !!"];
				}
				echo json_encode($response);
			}catch(Exception $e){

			}
		}else{
			$response = ["response"=>false, "data"=>"it's not a post method !!"];
			echo json_encode($response);
		}
    }

    public function addUpdateSubmit($inputs)
	{
		try
		{
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


			if ( $inputs["has_previous_holding_no"]==1)
			{
				$input = ['holding_no'=> $inputs['previous_holding_no']];
				if($prop_dtl = $this->model_prop_dtl->getPropIdByHodingNoEntryType($input)) {

						$prop_dtl_id = $prop_dtl['id'];
						$prop_entry_type = $prop_dtl['entry_type'];
						if ($saf_dtl = $this->model_saf_dtl->getSafIdByPropId(['prop_dtl_id'=>$prop_dtl_id])) {
							$old_saf_dtl_id = $saf_dtl['id'];
						}
				}
				$isReassessment = true;
			}

			if (isset($inputs['is_corr_add_differ']) && $inputs['is_corr_add_differ']==1) {
				$corr_address= $inputs['corr_address'];
				$corr_city = $inputs['corr_city'];
				$corr_dist = $inputs['corr_dist']; 
				$corr_state = $inputs['corr_state'];
				$corr_pin_code = $inputs['corr_pin_code'];
			} else {
				$corr_address= $inputs['prop_address'];
				$corr_city = $inputs['prop_city'];
				$corr_dist = $inputs['prop_dist']; 
				$corr_state = $inputs['prop_dist'];
				$corr_pin_code = $inputs['prop_pin_code'];
			}
			if ($inputs['building_plan_approval_date']=="") {
				$inputs['building_plan_approval_date'] = null;
			}
			if ($inputs['water_conn_date']=="") {
				$inputs['water_conn_date'] = null;
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
				$count=$this->model_saf_dtl->CountTotalSAFInWard($inputs['ward_mstr_id'], $assessment_type);
				//$url_short_name = $this->model_ulb_mstr->getulb_list($_SESSION['ulb_dtl']['ulb_mstr_id'])['short_ulb_name'];


				if($assessment_type=="New Assessment")
				{
					$assessmentId="01";
				}
				else if($assessment_type=="Reassessment")
				{
					$assessmentId="02";
				}
				else
				{
					$assessmentId="03";
				}
				
				$saf_no = "SAF"."/".$assessmentId."/".str_pad($wardNo, 3, 0, STR_PAD_LEFT)."/".++$count;
				
				$inputs['saf_no'] = $saf_no;
				$input = [
					'saf_distributed_dtl_id'=>$saf_distributed_dtl_id,
					'saf_no'=>$saf_no,
				];
				$this->model_saf_distributed_dtl->updateSafNoById($input);
			}
			else
			{
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
							|| $inputs['ownership_type_mstr_id']==4 
							|| $inputs['ownership_type_mstr_id']==5 
							|| $inputs['ownership_type_mstr_id']==10 
							|| $inputs['ownership_type_mstr_id']==11 
							|| $inputs['ownership_type_mstr_id']==12 
							|| $inputs['ownership_type_mstr_id']==13 
							|| $inputs['ownership_type_mstr_id']==14
							|| $inputs['ownership_type_mstr_id']==15 ) {
					$holding_type = "PURE_COMMERCIAL";
				}
			} else {
				$RESIDENCIAL = false; 
				$COMMERCIAL = false; $GOVERNMENT = false;
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
								|| $inputs['usage_type_mstr_id'][$i]==13 ) {
						$COMMERCIAL = true;
					}
				}
				if( $RESIDENCIAL==true ) { echo "RESIDENCIAL<br />";}
				if( $GOVERNMENT==true ) { echo "GOVERNMENT<br />";}
				if( $COMMERCIAL==true ) { echo "COMMERCIAL<br />";}

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

			$no_electric_connection = false;
			if($inputs['prop_type_mstr_id']!=4)
			{
				$no_electric_connection = true;
				if($inputs['prop_type_mstr_id']==2 && !isset($inputs['no_electric_connection']))
				{
					$no_electric_connection = false;
				}
			}
			
			$input = [
				'apply_date'=>date("Y-m-d"),
				'assessment_type'=>$assessment_type,
				'holding_type'=>$holding_type,
				'has_previous_holding_no'=>$inputs['has_previous_holding_no'],
				'previous_holding_id'=>($inputs['has_previous_holding_no']==1)?$prop_dtl_id:null,
				'is_owner_changed'=>($inputs['is_owner_changed']=="")?null:$inputs['is_owner_changed'],
				'transfer_mode_mstr_id'=>($inputs['is_owner_changed']=="1")?$inputs['transfer_mode_mstr_id']:null,
				'saf_no'=>$inputs['saf_no'],
				'holding_no'=>($inputs['has_previous_holding_no']==1)?$inputs['previous_holding_no']:null,
				'saf_distributed_dtl_id'=>$saf_distributed_dtl_id,
				'ward_mstr_id'=>$inputs['ward_mstr_id'], 
				'ownership_type_mstr_id'=>$inputs['ownership_type_mstr_id'], 
				'prop_type_mstr_id'=>$inputs['prop_type_mstr_id'], 
				'zone_mstr_id'=>$inputs['zone_mstr_id'],
				'appartment_name'=>$inputs['appartment_name'], 
				'flat_registry_date'=>($inputs['prop_type_mstr_id']==3 && $inputs['flat_registry_date']!="")?$inputs['flat_registry_date']:NULL,
				'no_electric_connection'=>$no_electric_connection, 
				'elect_consumer_no'=>(!isset($inputs['no_electric_connection']))?$inputs['elect_consumer_no']:"", 
				'elect_acc_no'=>(!isset($inputs['no_electric_connection']))?$inputs['elect_acc_no']:"", 
				'elect_bind_book_no'=>(!isset($inputs['no_electric_connection']))?$inputs['elect_bind_book_no']:"", 
				'elect_cons_category'=>(!isset($inputs['no_electric_connection']))?$inputs['elect_cons_category']:"", 
				'building_plan_approval_no'=>$inputs['building_plan_approval_no'], 
				'building_plan_approval_date'=>$inputs['building_plan_approval_date'], 
				'water_conn_no'=>$inputs['water_conn_no'],
				'water_conn_date'=>$inputs['water_conn_date'], 
				'khata_no'=>$inputs['khata_no'], 
				'plot_no'=>$inputs['plot_no'], 
				'village_mauja_name'=>$inputs['village_mauja_name'], 
				'road_type_mstr_id'=>$inputs['road_type_mstr_id'], 
				'area_of_plot'=>$inputs['area_of_plot'], 
				'prop_address'=>$inputs['prop_address'], 
				'prop_city'=>$inputs['prop_city'], 
				'prop_dist'=>$inputs['prop_dist'],
				'prop_state'=>$inputs['prop_state'],
				'prop_pin_code'=>$inputs['prop_pin_code'],
				'is_corr_add_differ'=>(isset($inputs['is_corr_add_differ']) && $inputs['is_corr_add_differ']==1)?true:false,
				'corr_address'=>$corr_address, 
				'corr_city'=>$corr_city, 
				'corr_dist'=>$corr_dist,
				'corr_state'=>$corr_state,
				'corr_pin_code'=>$corr_pin_code, 
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
				'created_on'=>$created_on, 
				'status'=>1
			];
			$saf_dtl_id = $this->model_saf_dtl->insertData($input);
			
			//$saf_dtl_id = 2;
			
			if ( $inputs['has_previous_holding_no']==0 || ($inputs['has_previous_holding_no']==1 && $inputs['is_owner_changed']==1))
			{
				for($i=0; $i < sizeof($inputs['owner_name']); $i++)
				{
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
			}
			else
			{
				for($i=0; $i<sizeof($inputs['prev_owner_name']); $i++)
				{
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

			if ($inputs['prop_type_mstr_id']!=4)
			{
				for($i=0; $i<sizeof($inputs['floor_mstr_id']); $i++)
				{
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
	
	public function addUpdate($param = null)
	{
		$assessmentType = "";
		if($param!=null)
		{
			$paramExplode = explode("::", $param);
			$ID = $paramExplode[0];
			$assessmentType = hashDecrypt($paramExplode[1]);
			$prop_dtl_list = $this->model_prop_dtl->getPropIdHodingNoWardByMD5ID(['id'=>$ID]);
			$prop_owner_dtl_list = $this->model_prop_owner_detail->getPropOwnerDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_list['id']]);
		}
		
		$ulb_mstr_id = 1;//$_SESSION['ulb_dtl']['ulb_mstr_id'];
		$emp_details_id = 0;

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

		if($assessmentType!="")
		{
			$data['holding_no'] = $prop_dtl_list['holding_no'];
			$data['ward_mstr_id'] = $prop_dtl_list['ward_mstr_id'];
			$data['prop_owner_dtl_list'] = $prop_owner_dtl_list;

			// by hayat
			// for selected in case of mutation/reassessment
			foreach($prop_dtl_list as $key=>$value)
			{
				if($key=="is_mobile_tower")
				$data[$key]=($value=="t")?"1":"0";
				else if($key=="is_hoarding_board")
				$data[$key]=($value=="t")?"1":"0";
				else if($key=="is_petrol_pump")
				$data[$key]=($value=="t")?"1":"0";
				else if($key=="is_water_harvesting")
				$data[$key]=($value=="t")?"1":"0";
				
				else
				$data[$key]=$value;

			}
			$i=0;
			$prop_floor=$this->model_prop_floor_details->getFloorByPropId(["prop_dtl_id"=> $prop_dtl_list['id']]);
			foreach($prop_floor as $row)
			{
				
				$data["floor_mstr_id"][$i]=$row['floor_mstr_id'];
				$data["usage_type_mstr_id"][$i]=$row['usage_type_mstr_id'];
				$data["occupancy_type_mstr_id"][$i]=$row['occupancy_type_mstr_id'];
				$data["const_type_mstr_id"][$i]=$row['const_type_mstr_id'];
				$data["builtup_area"][$i]=$row['builtup_area'];
				$data["date_from"][$i]=date("Y-m", strtotime($row['date_from']));
				//$data["date_upto"[$i]=date("Y-m", strtotime($row['date_upto']));
				++$i;
			}
			
			if($assessmentType=="Re-Assessment")
			{
				$i=0;
				foreach($prop_owner_dtl_list as $row)
				{
					$data["owner_name"][$i]=$row["owner_name"];
					$data["guardian_name"][$i]=$row["guardian_name"];
					$data["relation_type"][$i]=$row["relation_type"];
					$data["mobile_no"][$i]=$row["mobile_no"];
					$data["aadhar_no"][$i]=$row["aadhar_no"];
					$data["pan_no"][$i]=$row["pan_no"];
					$data["email"][$i]=$row["email"];
					++$i;					
				}
			}
			
			
		}
		$data['assessmentType'] = $assessmentType;
		$data['ulb_address'] = $ulb_address;
		$data['wardList'] = $wardList;
		$data['transferModeList'] = $transferModeList;
		
		$data['param'] = $param;
		$data['ownershipTypeList'] = $ownershipTypeList;
		$data['roadTypeList'] = $roadTypeList;
		$data['propTypeList'] = $propTypeList;
		$data['floorList'] = $floorList;
		$data['usageTypeList'] = $usageTypeList;
		$data['occupancyTypeList'] = $occupancyTypeList;
		$data['constTypeList'] = $constTypeList;

		if($this->request->getMethod()=='post')
		{
			try
			{
				$inputs = arrFilterSanitizeString($this->request->getVar());
				$errMsg = validateSafAddUpdate($inputs);
				if(empty($errMsg))
				{
					if ( $inputs["has_previous_holding_no"]==1 )
					{
						$isHoldingNoExist = false;
						$isPaymentDtlCleared = false;
						$input = [
							'holding_no'=>$inputs['previous_holding_no']
						];
						if($prop_dtl = $this->model_prop_dtl->getPropIdByHodingNoEntryType($input))
						{
							$input = [
								'prop_dtl_id'=>$prop_dtl['id']
							];
							$isHoldingNoExist = true;
							if(!$this->model_prop_demand->getIsDemandClearedByPropDtlId($input))
							{
								$isPaymentDtlCleared = true;
							}						
						}
						
						if($isHoldingNoExist==false)
						{
							$errMsg["holding_no"] = "holding no does not exist !!!";
						}
						if($inputs["has_previous_holding_no"]==1 && $inputs["is_owner_changed"]==0)
						{
							if($isPaymentDtlCleared==false)
							{
								$errMsg["isPrevPaymentCleared"] = $inputs["previous_holding_no"].", please clear tax !!!";
							}
						}
					}
				}
				
				if(empty($errMsg))
				{
					if(isset($_POST['btn_submit'])){
						$session = session();
						if ($session->has("saf_opt")) {
							$session_otp = $session->get("saf_opt");
							$get_otp = $inputs["otp"];
							if ($session_otp!=$get_otp) {
								$errMsg["opt"] = "OTP does not match !!!";
							}
						} else {
							$errMsg["opt"] = "OTP does not match !!!";
						}
					}
				}
				if (empty($errMsg)) {
					$data = $inputs;
					$data['param'] = $param;
					if ($assessmentType!="") {
						$data['holding_no'] = $prop_dtl_list['holding_no'];
						$data['ward_mstr_id'] = $prop_dtl_list['ward_mstr_id'];
						$data['prop_owner_dtl_list'] = $prop_owner_dtl_list;
					}
					$data['assessmentType'] = $assessmentType;
					$data['ulb_address'] = $ulb_address;
					$data['transferModeList'] = $transferModeList;
					
					$data['wardList'] = $wardList;
					$data['ownershipTypeList'] = $ownershipTypeList;
					$data['roadTypeList'] = $roadTypeList;
					$data['propTypeList'] = $propTypeList;
					$data['floorList'] = $floorList;
					$data['usageTypeList'] = $usageTypeList;
					$data['occupancyTypeList'] = $occupancyTypeList;
					$data['constTypeList'] = $constTypeList;
					if(isset($_POST['btn_back']))
					{
						return view('citizen/saf/saf_add_update', $data);
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
								
								if($isVacantLandTemp || $isMobileTowerTemp || $isHoldingBoardTemp)
								{
									$sendInput = ['road_type_mstr_id'=> $inputs['road_type_mstr_id'], 'date_of_effect'=> $dateOfEffect];
									$mrr = $this->model_arr_vacant_mstr->getMRRCalRate($sendInput)['rate'];
									if(!$mrr){ $mrr = 0; }

									$arrShort = array('vacand'=>$vacand_land_qtr_temp, 'mobile'=>$mobile_tower_qtr_temp, 'hording'=>$hoarding_board_qtr_temp);
									asort($arrShort);

									foreach($arrShort as $keyy=>$x_Qtr)
									{
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
													'yearly_tax'=>round($mobileTowerTax, 2)
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
																			if (!$afr){ $afr = 0;}
																			if ($floorDtlTemp['usage_type_mstr_id']==1){
																				$carperArea = (($floorDtlTemp['builtup_area']*70)/100);
																			} else {
																				$carperArea = (($floorDtlTemp['builtup_area']*80)/100);
																			}
																			$sendInput = ['usage_type_mstr_id'=>$floorDtlTemp['usage_type_mstr_id']];
																			$mf = $this->model_usage_type_dtl->getUsageTypeMultFact($sendInput)['mult_factor'];
																			if (!$mf){ $mf = 0;}

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
						
						return view('Citizen/Saf/saf_add_update_review', $data);

					}
					else if(isset($_POST['btn_submit']))
					{
						$inputs['ulb_mstr_id'] = $ulb_mstr_id;
						$inputs['emp_details_id'] = $emp_details_id;

						if($saf_dtl_id = $this->addUpdateSubmit($inputs))
						{
							$LINK = base_url('CitizenSaf/CitizenDtlView/'.md5($saf_dtl_id));
							return redirect()->to($LINK);
						}
					}
				}
				else
				{
					$data = $inputs;
					$data['param'] = $param;
					if ($assessmentType!="") {
						$data['holding_no'] = $prop_dtl_list['holding_no'];
						$data['prop_owner_dtl_list'] = $prop_owner_dtl_list;
					}
					$data['assessmentType'] = $assessmentType;
					if ($assessmentType=="Re-Assessment") {
						$data['transferModeList'] = $transferModeList;
					}
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
					return view('Citizen/Saf/saf_add_update', $data);
				}
			}
			catch(Exception $e)
			{
				//echo $e->getMessage();
			}
		}
		else
		{
			return view('citizen/SAF/saf_add_update', $data);
		}
	}

	public function getNewWardDtlByOldWard()
	{
		if($this->request->getMethod()=='post')
		{
			try
			{
				$inputs = arrFilterSanitizeString($this->request->getVar());
				$new_ward = isset($inputs['new_ward_mstr_id'])?$inputs['new_ward_mstr_id']:0;
				if($old_ward_dtl = $this->model_ward_mstr->getWardNoByOldWardId($inputs['old_ward_mstr_id']))
				{
					$ward_split = str_split($old_ward_dtl['ward_no']);
					$number_ward = "";
					foreach($ward_split AS $val)
					{
						if(is_numeric($val))
						{
							$number_ward .= $val;
						}
						else
						{
							break;
						}
					}
					if($old_ward_dtl = $this->model_ward_mstr->getWardNoByOldWardNo($number_ward)) {
						if($new_ward_list = $this->model_view_ward_mapping_mstr->getNewWardLIstByOldWardMstrId($old_ward_dtl['id'])){
							if($new_ward!=""){
								$option = "<option value=''>== SELECT ==</option>";
								foreach($new_ward_list as $value) {
									$select = (isset($new_ward) && $new_ward!="")?($new_ward==$value['id'])?"SELECTED":"":"";
									$option .= "<option value='".$value['id']."' ".$select.">".$value['ward_no']."</option>";
								}
							}else{
								$option = "";
								if (sizeof($new_ward_list)>1) {
									$option = "<option value=''>== SELECT ==</option>";
								}
								foreach($new_ward_list as $value) {
									
									$option .= "<option value='".$value['id']."'>".$value['ward_no']."</option>";
								}
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
			}
			catch (Exception $e)
			{

			}
		}
		else
		{
			$response = ['response'=>false];
			echo json_encode($response);
		}
	}
	
	public function getPrevHoldingDtl(){
		if($this->request->getMethod()=='post'){
			try{
				$inputs = arrFilterSanitizeString($this->request->getVar());
				
				 $input = [
					'holding_no'=>$inputs['previous_holding_no']
				];
				if($prop_dtl = $this->model_prop_dtl->getPropIdByHodingNo($input)){
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

	public function CitizenDtlView($ID=null) {
        $data =(array)null;
		$data['id']=$ID;
		//print_r($data);
		if($data['basic_details'] = $this->model_saf_dtl->basic_details($data)) {
			$data['saf_dtl'] = $this->model_saf_dtl->Saf_details_md5($ID);
			$data['owner_details'] = $this->model_saf_owner_detail->ownerdetails($data['basic_details']['saf_dtl_id']);
			//print_r($data['saf_dtl']);
			$data['tax_list'] = $this->model_saf_tax->tax_list($data['basic_details']['saf_dtl_id']);
			$data['demand_detail'] = $this->model_saf_demand->demand_detail($data);

			$data['occupancy_detail'] = $this->model_saf_floor_details->occupancy_detail($data['basic_details']['saf_dtl_id']);
			//print_r($data['basic_details']);
			//$data['payment_detail'] = $this->model_transaction->payment_detail($data['basic_details']['saf_dtl_id']);
			/******* verification code ends**********/
			return view('Citizen/Saf/saf_view', $data);
		}
	}
	
	public function documentUpload($ID=null) {
		
		if ($ID!=null) {
            $errMsg = [];
            if ($saf = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($ID)) {
				//print_r($saf);
                $session = session();
                $ulb_mstr = $session->get("ulb_dtl");
                $emp_details = $session->get("emp_details");
				$temp = true;

				

                if ($this->request->getMethod()=='post') {
					$ulb_dtl = $this->model_ulb_mstr->getulb_list($ulb_mstr["ulb_mstr_id"]);
                    $inputs = arrFilterSanitizeString($this->request->getVar());
                    $created_on = date("Y-m-d h:i:s");
					
                    if (isset($inputs['btn_owner_doc_upload'])) {
                        $rules=[
                            'applicant_image_file'=>'uploaded[applicant_image_file]|max_size[applicant_image_file,5120]|ext_in[applicant_image_file,png,jpg,jpeg]',
                            'applicant_doc_file'=>'uploaded[applicant_doc_file]|max_size[applicant_doc_file,5120]|ext_in[applicant_doc_file,png,jpg,jpeg,pdf]',
                            
                        ];
						
						if ($this->validate($rules)) {
                            $applicant_image_file = $this->request->getFile('applicant_image_file');
                            $applicant_doc_file = $this->request->getFile('applicant_doc_file');
                            if ($applicant_image_file->IsValid() 
                                && !$applicant_image_file->hasMoved()
                                && $applicant_doc_file->IsValid() 
                                && !$applicant_doc_file->hasMoved()) {
                                try {
                                    $this->db->transBegin();
                                    $input = [
                                        'saf_dtl_id'=>$saf['saf_dtl_id'],
                                        'saf_owner_dtl_id'=>$inputs['saf_owner_dtl_id'],
                                        'owner_doc_mstr_id'=>$inputs['owner_doc_mstr_id'],
                                        'emp_details_id'=> $emp_details['id'],
                                        'created_on'=>$created_on,
                                        'status'=>2
                                    ];
									
								if($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkOwnerImgDataIsExistBySafOwnerDtlId($input)){
									
									$delete_path = WRITEPATH.'uploads/'.$saf_doc_dtl_id['doc_path'];
									// unlink($delete_path);
									deleteFile($delete_path);

									$newFileName = md5($saf_doc_dtl_id['id']);
									$file_ext = $applicant_image_file->getExtension();

									$path = $ulb_dtl['city']."/"."applicant_image";
									$owner_img_path = $path."/".$newFileName.'.'.$file_ext;
									if($applicant_image_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
										$this->model_saf_doc_dtl->updatetransImgpathById($saf_doc_dtl_id['id'], $owner_img_path);
									}
								} else if($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerImgData($input)){
									$newFileName = md5($saf_doc_dtl_id);
									$file_ext = $applicant_image_file->getExtension();

									$path = $ulb_dtl['city']."/"."applicant_image";
									$owner_img_path = $path."/".$newFileName.'.'.$file_ext;
									if($applicant_image_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
										$this->model_saf_doc_dtl->updatetransImgpathById($saf_doc_dtl_id, $owner_img_path);
									}
								}
									
								if($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkOwnerDocDataIsExistBySafOwnerDtlId($input)){

									$delete_path = WRITEPATH.'uploads/'.$saf_doc_dtl_id['doc_path'];
									// unlink($delete_path);
									deleteFile($delete_path);

									$newFileName = md5($saf_doc_dtl_id['id']);
									$file_ext = $applicant_doc_file->getExtension();

									$path = $ulb_dtl['city']."/"."saf_doc_dtl";
									$owner_doc_path = $path."/".$newFileName.'.'.$file_ext;
									if($applicant_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
										$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $owner_doc_path, $input['owner_doc_mstr_id']);
									}
								} else if($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerData($input)){
									
									$newFileName = md5($saf_doc_dtl_id);
									$file_ext = $applicant_doc_file->getExtension();

									$path = $ulb_dtl['city']."/"."saf_doc_dtl";
									$owner_doc_path = $path."/".$newFileName.'.'.$file_ext;
									if($applicant_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
										$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $owner_doc_path, $input['owner_doc_mstr_id']);
									}
								}
									
								if ($this->db->transStatus() === FALSE) {
                                        $this->db->transRollback();
                                    } else {
                                        $this->db->transCommit();
                                    }
                                } catch (Exception $e) { }
                            } else {
                                $errMsg = "<ul><li>something errors in owner details.</li></ul>";
                            }
                        } else {
                            $errMsg = $this->validator->listErrors();
                        }
                    }
					
					if (isset($inputs['btn_upload'])) {
                        $rules = [
                            'upld_doc_path'=>'uploaded[upld_doc_path]|max_size[upld_doc_path,5120]|ext_in[upld_doc_path,pdf]',
                            
                        ];
                        if ($this->validate($rules)) {
                            $upld_doc_path = $this->request->getFile('upld_doc_path');
                            if ($upld_doc_path->IsValid() 
                                && !$upld_doc_path->hasMoved()) {
                                try {
                                    $this->db->transBegin();
                                    $input = [
                                        'saf_dtl_id'=>$saf['saf_dtl_id'],
                                        'upld_doc_mstr_id'=>$inputs['upld_doc_mstr_id'],
                                        'emp_details_id'=> $emp_details['id'],
                                        'created_on'=>$created_on,
                                        'status'=>2,
                                        
                                    ];
									
									if($input['upld_doc_mstr_id']==0){
										$input['doc_mstr_idcheck']=[0];
										$input['other_doccheck']='saf_form';
									}elseif(($input['upld_doc_mstr_id']>=2 && $input['upld_doc_mstr_id']<=5) || ($input['upld_doc_mstr_id']>=18 && $input['upld_doc_mstr_id']<=20) || $input['upld_doc_mstr_id']==7){
										$input['doc_mstr_idcheck']=[2,3,4,5,7,18,19,20];
										$input['other_doccheck']='';
									}elseif(($input['upld_doc_mstr_id']>=15 && $input['upld_doc_mstr_id']<=17) || $input['upld_doc_mstr_id']==6 || $input['upld_doc_mstr_id']==10 || $input['upld_doc_mstr_id']==13 || $input['upld_doc_mstr_id']==22){
										$input['doc_mstr_idcheck']=[6,10,13,15,16,17,22];
										$input['other_doccheck']='';
									}elseif($input['upld_doc_mstr_id']==23){
										$input['doc_mstr_idcheck']=[23];
										$input['other_doccheck']='';
									}elseif($input['upld_doc_mstr_id']==24){
										$input['doc_mstr_idcheck']=[24];
										$input['other_doccheck']='';
									}elseif($input['upld_doc_mstr_id']==9){
										$input['doc_mstr_idcheck']=[9];
										$input['other_doccheck']='';
									}
									
                                    if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkDocDataIsExist($input)) {
									
										$delete_path = WRITEPATH.'uploads/'.$saf_doc_dtl_id['doc_path'];
                                        // unlink($delete_path);
										deleteFile($delete_path);

                                        $newFileName = md5($saf_doc_dtl_id['id']);
                                        $file_ext = $upld_doc_path->getExtension();

                                        $path = $ulb_dtl['city']."/"."saf_doc_dtl";
										$upld_doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
                                        $upld_doc_path_save = $path."/".$newFileName.'.'.$file_ext;
                                        $this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $upld_doc_path_save,$input['upld_doc_mstr_id']);
										
                                    } else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertFrData($input)) {
                                        
										$newFileName = md5($saf_doc_dtl_id);
                                        $file_ext = $upld_doc_path->getExtension();
                                        $path = $ulb_dtl['city']."/"."saf_doc_dtl";

                                        $upld_doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
                                        $upld_doc_path_save = $path."/".$newFileName.'.'.$file_ext;
                                        $this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $upld_doc_path_save, $input['upld_doc_mstr_id']);
                                    }
                                    if ($this->db->transStatus() === FALSE) {
                                        $this->db->transRollback();
                                    } else {
                                        $this->db->transCommit();
                                    }
                                } catch (Exception $e) { }

                            } else {
                                $errMsg = "<ul><li>something errors in SAF form details.</li></ul>";
                            }
                        } else {
                            $errMsg = $this->validator->listErrors();
                        }
                    }
					
						
                }
					$data = $saf;
					$temp = true;
					$data['errMsg'] = $errMsg;
					$input = ['saf_dtl_id'=>$data['saf_dtl_id']];
					$data['owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlOrberByIdAscBySAFId($input);
					foreach($data['owner_detail'] as $key => $value){
						$input = ['saf_dtl_id'=>$data['saf_dtl_id'], 'saf_owner_dtl_id'=>$value['id']];
						$applicant_img_dtl = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlIdFinal($input);
						
						if(!$applicant_img_dtl || $applicant_img_dtl['status'] != 2){
							$temp = false;
						}
						$data['owner_detail'][$key]['applicant_img_dtl'] = $applicant_img_dtl;

						$applicant_doc_dtl = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlIdFinal($input);
						if(!$applicant_doc_dtl || $applicant_doc_dtl['status'] != 2){
							$temp = false;
						}
						$data['owner_detail'][$key]['applicant_doc_dtl'] = $applicant_doc_dtl;
					}

					$input = ['saf_dtl_id'=>$data['saf_dtl_id']];
					// SAF Form
					//$data['saf_form'] = $this->model_saf_doc_dtl->getSafFormBySafDtlIdFinal($input);
					$data['owner_doc_list'] = $this->model_doc_mstr->getDataByDocType('other');
					//print_r($data['prop_type_mstr_id']);
					if ($data['prop_type_mstr_id']==1) {
						$data['super_structure_doc_list'] = $this->model_doc_mstr->getDataByDocType('super_structure_doc');
						//print_r($data['super_structure_doc_list']);
					} else if ($data['prop_type_mstr_id']==3) {
						$data['flat_doc_list'] = $this->model_doc_mstr->getFlatDocListData();
					} else {
						$data['transfer_mode_doc_list'] = $this->model_doc_mstr->getDataByDocType('transfer_mode');
						$data['property_type_doc_list'] = $this->model_doc_mstr->getDataByDocType('property_type');
					}

					if ($data['prop_type_mstr_id']==1) {
						// super structure (Electricity Bill)
						if ($super_structure_doc_dtl = $this->model_saf_doc_dtl->getSuperStructureDocDtlBySafDtlId($input)) {
							if($super_structure_doc_dtl['status'] != 2){
								$temp = false;
							}
							$data['super_structure_doc_dtl'] = $super_structure_doc_dtl;
						}else{
							$temp = false;
						}
					} else if ($data['prop_type_mstr_id']==3) {
						// flat dtl (Possession Certificate)
						if ($flat_doc_dtl = $this->model_saf_doc_dtl->getFlatDtlBySafDtlId($input)) {
							if($flat_doc_dtl['status'] != 2){
								$temp = false;
							}
							$data['flat_doc_dtl'] = $flat_doc_dtl;
						}else{
							$temp = false;
						}
					} else {
						// other document like (transfer mode, property type)
						if ($transfer_mode_doc_dtl = $this->model_saf_doc_dtl->getTransferModeDocDtlBySafDtlId($input)) {
							if($transfer_mode_doc_dtl['status'] != 2){
								$temp = false;
							}
							$data['transfer_mode_doc_dtl'] = $transfer_mode_doc_dtl;
							
						}else{
							$temp = false;
						}
						if ($property_type_doc_dtl = $this->model_saf_doc_dtl->getPropertyTypeDocDtlBySafDtlId($input)) {
							if($property_type_doc_dtl['status'] != 2){
								$temp = false;
							}
							$data['property_type_doc_dtl'] = $property_type_doc_dtl;
						}else{
							$temp = false;
						}
					}
					
					
					// no electric connection (form-I)
					if ($data['no_electric_connection']=='t') {
						$data['no_electric_connection_doc_list'] = $this->model_doc_mstr->getDataByDocType('no_elect_connection');
						if ($no_electric_connection_doc_dtl = $this->model_saf_doc_dtl->getNoElectConnectionDtlBySafDtlId($input)) {
							if($no_electric_connection_doc_dtl['status'] != 2){
								$temp = false;
							}
						$data['no_electric_connection_doc_dtl'] = $no_electric_connection_doc_dtl;
						}else{
							$temp = true;
						}
					}
					
					//$data['message'] = $msg;	
					$data['show_rmc_btn'] = $temp;
					
				return view('citizen/SAF/saf_document_upload', $data);
			} else {
				echo "Invalid parameters";
			}
			
		}    
       
    }
	
	public function payTax($id=null) {
		$data =(array)null;
        $data['saf_dtl_id']=$id;
		$leveldata = [
				'saf_dtl_id' => $data['saf_dtl_id'],
				'sender_user_type_id' => 11,
				'receiver_user_type_id' => 6,
				'forward_date' => date('Y-m-d'),
				'forward_time' => date('H:i:s'),
				'created_on' =>date('Y-m-d H:i:s'),
				'remarks' => 'Document Uploaded',
				'verification_status' => 0
			];
		//print_r($leveldata);
		if($level_pending_insrt=$this->model_level_pending_dtl->insrtlevelpendingdtl($leveldata)){
		
			if($this->model_saf_doc_dtl->updatestatusDocUpload($data)){
			
				if($doc_upload_stts=$this->model_saf_dtl->update_doc_upload_status($data)){
					return $this->response->redirect(base_url('CitizenPropertySAF/citizen_saf_confirm_payment/'.md5($id).''));
				}
			}
		}
	}

	public function documentView($id=null) {
		
        $data =(array)null;
        $data['id']=$id;
        $session = session();
        $ulb_mstr = $session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $data['basic_details'] = $this->model_saf_dtl->basic_details($data);
        $data['form'] = $this->model_saf_dtl->Saf_details_md5($id);

        $data['owner_list'] = $this->model_saf_owner_detail->ownerdetails($data['form']['id']);
        $verify_status='0';
        foreach($data['owner_list'] as $key => $value){
			$app_other_doc='applicant_image';
			$data['owner_list'][$key]['saf_owner_img_list'] = $this->model_saf_doc_dtl->get_ownerimgdetails_by_safid($data['form']['id'],$value['id'],$app_other_doc);
			$app_doc_type="other";
			$data['owner_list'][$key]['saf_owner_doc_list'] = $this->model_view_saf_doc_dtl->safownerdocdetbyid($data['form']['id'],$value['id'],$app_doc_type);
		}

        $fr_doc_type="saf_form";
        $data['owner_saf_form'] = $this->model_saf_doc_dtl->get_ownersafform_by_safid($data['form']['id'],$fr_doc_type);
        $tr_doc_type="transfer_mode";
        $data['prop_tr_mode_document'] = $this->model_view_saf_doc_dtl->uploaded_doc_list_by_safid($data['form']['id'],$tr_doc_type);
        $pr_doc_type="property_type";
        $data['prop_pr_mode_document'] = $this->model_view_saf_doc_dtl->uploaded_doc_list_by_safid($data['form']['id'],$pr_doc_type);
        $data['dl_remarks'] = $this->model_level_pending_dtl->dl_remarks_by_saf_id($data['form']['saf_dtl_id']);
           //print_r($data);

        /******* verification code ends**********/
        return view('citizen/saf/bo_doc_upload_saf_view', $data);
    }

	public function getApartmentListByWard()
	{
		if($this->request->getMethod()=='post')
		{
			try
			{
				$inputs = arrFilterSanitizeString($this->request->getVar());
				if($apartment_list = $this->model_apartment_mstr->getApartmentByWard($inputs['old_ward_mstr_id']))
				{
					$option = "<option value=''>== SELECT ==</option>";
					foreach($apartment_list as $value) {
						$option .= "<option value='".$value['id']."' data-option='".$value['water_harvesting_status']."'>".$value['apartment_name']."</option>";
					}
					$response = ['response'=>true, 'data'=>$option];
					echo json_encode($response);

				} else {
					$response = ['response'=>false];
					echo json_encode($response);
				}
			}
			catch (Exception $e)
			{

			}
		}
		else
		{
			$response = ['response'=>false];
			echo json_encode($response);
		}
	}


	public function TaxCalculate(){
		$safHelper = new \App\Controllers\SAF\SAFHelper($this->db);
		try{
			$requestData = $this->request->getVar();
			$newRequstData = $this->replaceHoldingByPhysicalVal($requestData);
			$holding = $requestData; 
			$holding = array_merge($holding,$newRequstData);
			$holding["no_electric_connection"] = in_array($holding["no_electric_connection"],["t",1,"Yes","YES","yes","TRUE","true"]) ? true : false;
			$holding["is_mobile_tower"] = in_array($holding["is_mobile_tower"],["t",1,"Yes","YES","yes","TRUE","true"]) ? true : false;
			$holding["is_hoarding_board"] = in_array($holding["is_hoarding_board"],["t",1,"Yes","YES","yes","TRUE","true"]) ? true : false;
			$holding["is_petrol_pump"] = in_array($holding["is_petrol_pump"],["t",1,"Yes","YES","yes","TRUE","true"]) ? true : false;
			$holding["is_water_harvesting"] = in_array($holding["is_water_harvesting"],["t",1,"Yes","YES","yes","TRUE","true"]) ? true : false;
			if(isset($holding["isExtraFloorAdd"]) && $holding["isExtraFloorAdd"]){
				foreach($holding["newFloors"] as $newFloor){
					$holding["floors"][]=$newFloor;
				}
			}
			$inputs = $holding;

			if($inputs["prop_type_mstr_id"]==4){
				if ($inputs['road_type_mstr_id']!=4) {
					$vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
				} else {
					$vacantDtlArr = $safHelper->makeVacantWithNoRoadFloorDtlArr($inputs);
				}
				list($safTaxDtl, $new_rule_sub, $cv_rule_sub, $cv_vacant_land_dtl) = $safHelper->calVacantTaxDtl($vacantDtlArr);
				
				foreach ($cv_rule_sub as $value) {
					if ($value["type"]=="Vacant Land") {
						$floor["floor_name"]=$value["type"];
						$floor["builtup_area"]=$value["area_sqm"];
						$floor["cv_rate"] = $value['vacant_land_rate'];
						$floor["occupancy_rate"] = $value['occupancy_factor'];
						$floor["tax_percent"] = 1;
						$floor["calculation_factor"] = 1;
						$floor["matrix_factor_rate"] = 1;
						$floor["holding_tax"] = $value['yearly_cv_tax'];
						$floor["quaterly_holding_tax"] = $value['yearly_cv_tax']/4;
						$floor["rwh_tax"] = null;
						// $holding["floor_wise_tax"][] = $floor;
					}
				}
				
			}else{
				foreach($holding["floors"] as $floor){
					$inputs["usage_type_mstr_id"][] = $floor["usage_type_mstr_id"] ;
					$inputs["floor_mstr_id"][] = $floor["floor_mstr_id"] ;
					$inputs["const_type_mstr_id"][] = $floor["const_type_mstr_id"];
					$inputs["occupancy_type_mstr_id"][] = $floor["occupancy_type_mstr_id"]  ;
					$inputs["builtup_area"][] = $floor["builtup_area"];
					$inputs["date_from"][] = date("Y-m", strtotime($floor["date_from"]));
					$inputs["date_upto"][] = ($floor["date_upto"] == "") ? "" : date("Y-m", strtotime($floor["date_upto"]));
				}
				
				$floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
				$isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
				list($safTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calBuildingTaxDtl($floorDtlArr, $inputs['prop_type_mstr_id'], $isAdditionaTaxImplemented);
				
				foreach($holding["floors"] as $key => $floor){
					$taxDtl =$floorDtlArr[$key];
					$floor["cv_rate"] = $taxDtl['cv_2024_cal_method']['cvr'];
					$floor["occupancy_rate"] = $taxDtl['cv_2024_cal_method']['occupancy_rate'];
					$floor["tax_percent"] = ($taxDtl['cv_2024_cal_method']['resi_comm_type_rate']*100)??"";
					$floor["calculation_factor"] = $taxDtl['cv_2024_cal_method']['calculation_factor'];
					$floor["matrix_factor_rate"] = $taxDtl['cv_2024_cal_method']['matrix_factor_rate'];
					$floor["holding_tax"] = round($taxDtl['cv24'],2);
					$floor["quaterly_holding_tax"] = round($taxDtl['cv24']/4,2);
					$floor["rwh_tax"] = $taxDtl['additional_tax'];
					
					$holding["floor_wise_tax"][] = $floor;
					$holding["floors"][$key]=$floor;

				}
			}
			return json_encode(["status"=>true,"message"=>"Tax Calculated","data"=>$holding]);
		}catch(Exception $e){
			return json_encode(["status"=>false,"message"=>"server Error","data"=>$e]);
		}
	}

	
}
?>
