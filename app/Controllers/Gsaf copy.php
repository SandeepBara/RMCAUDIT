<?php namespace App\Controllers;
use App\Controllers\SAF\SAFHelper;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_g_saf;
use App\Models\model_ward_mstr;
use App\Models\model_colony_type_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_floor_mstr;
use App\Models\model_usage_type_mstr;
use App\Models\model_usage_type_dtl;
use App\Models\model_occupancy_type_mstr;
use App\Models\model_const_type_mstr;
use App\Models\model_govt_building_type_mstr;
use App\Models\model_prop_usage_type_mstr;
use App\Models\model_road_type_mstr;

use App\Models\model_arr_old_building_mstr;
use App\Models\model_arr_building_mstr;

use App\Models\model_govt_saf_dtl;
use App\Models\model_govt_saf_officer_dtl;
use App\Models\model_govt_saf_floor_dtl;
use App\Models\model_govt_saf_tax;
use App\Models\model_govt_saf_demand;
use App\Models\model_prop_dtl;
use Exception;

class Gsaf extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_ulb_mstr;
	protected $model_fy_mstr;
    protected $model_g_saf;
    protected $model_ward_mstr;
	protected $model_colony_type_mstr;
    protected $model_prop_type_mstr;
    protected $model_floor_mstr;
    protected $model_usage_type_mstr;
    protected $model_usage_type_dtl;
    protected $model_occupancy_type_mstr;
    protected $model_const_type_mstr;
    protected $model_govt_building_type_mstr;
    protected $model_prop_usage_type_mstr;
    protected $model_road_type_mstr;
    protected $model_arr_old_building_mstr;
    protected $model_arr_building_mstr;
    protected $model_govt_saf_dtl;
	protected $model_govt_saf_officer_dtl;
	protected $model_govt_saf_floor_dtl;
	protected $model_govt_saf_tax;
	protected $model_govt_saf_demand;
    protected $model_prop_dtl;


    public function __construct()
	{
        parent::__construct();
     	helper(['db_helper', 'utility_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);
        }
		
        if ($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name);
        }
        $this->model_g_saf = new model_g_saf($this->db);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
		$this->model_colony_type_mstr = new model_colony_type_mstr($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_floor_mstr = new model_floor_mstr($this->db);
        $this->model_usage_type_mstr = new model_usage_type_mstr($this->db);
        $this->model_usage_type_dtl = new model_usage_type_dtl($this->db);
        $this->model_occupancy_type_mstr = new model_occupancy_type_mstr($this->db);
        $this->model_const_type_mstr = new model_const_type_mstr($this->db);
        $this->model_govt_building_type_mstr = new model_govt_building_type_mstr($this->db);
        $this->model_prop_usage_type_mstr = new model_prop_usage_type_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);

        $this->model_arr_old_building_mstr = new model_arr_old_building_mstr($this->db);
        $this->model_arr_building_mstr = new model_arr_building_mstr($this->db);

        $this->model_govt_saf_dtl = new model_govt_saf_dtl($this->db);
        $this->model_govt_saf_officer_dtl = new model_govt_saf_officer_dtl($this->db);
		$this->model_govt_saf_floor_dtl = new model_govt_saf_floor_dtl($this->db);
        $this->model_govt_saf_tax = new model_govt_saf_tax($this->db);
        $this->model_govt_saf_demand = new model_govt_saf_demand($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
    }


	public function colonyAssessment()
	{
		
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$emp_details_id = $_SESSION['emp_details']['id'];
		$ip_address = $_SESSION['emp_details']['ip_address'];

		$colonyList = $this->model_colony_type_mstr->getColonyList();
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $propTypeList = $this->model_prop_type_mstr->getPropTypeList();
        $floorList = $this->model_floor_mstr->getFloorList();
        $usageTypeList = $this->model_usage_type_mstr->getUsageTypeList();
        $occupancyTypeList = $this->model_occupancy_type_mstr->getOccupancyTypeList();
        $constTypeList = $this->model_const_type_mstr->getConstTypeList();
        $govtBuildTypeList = $this->model_govt_building_type_mstr->getGovtBuilTypeList();
        $propUsageTypeList = $this->model_prop_usage_type_mstr->getPropUsageTypeList();
        $roadTypeList = $this->model_road_type_mstr->getRoadTypeList();

        $data['colonyList'] = $colonyList;
		$data['propTypeList'] = $propTypeList;
		$data['wardList'] = $wardList;
		$data['floorList'] = $floorList;
		$data['usageTypeList'] = $usageTypeList;
		$data['occupancyTypeList'] = $occupancyTypeList;
		$data['constTypeList'] = $constTypeList;
		$data['govtBuildTypeList'] = $govtBuildTypeList;
		$data['propUsageTypeList'] = $propUsageTypeList;
		$data['roadTypeList'] = $roadTypeList;

		if($this->request->getMethod()=='post'){
			
		}
		else
		{
			return view('property/gsaf/gcsaf_add_update_one', $data);
		}
	}

    public function ajaxPropDtlIdAddressDtlByHoldingWardNo()
	{
        if($this->request->getMethod()=='post'){
			try{
				$inputs = arrFilterSanitizeString($this->request->getVar());
                if ($data = $this->model_prop_dtl->getPropDtlIdAddressDtlByHoldingWardNo($inputs) ) {
                    $response = ['response'=> true, 'data'=>$data];
                } else {
                    $response = ['response'=> false];
                }
                return json_encode($response);
            }catch (Exception $e) {

            }
        }
    }

    public function addUpdateSubmit($inputs)
	{
		try{
			$this->db->transBegin();

			$apply_date = date('Y-m-d');
			$created_on = $updated_on = date('Y-m-d H:i:s');
			$isReassessment = false;
			$prop_dtl_id = 0;
			$prop_entry_type = "";
			$old_saf_dtl_id = 0;

            $assessment_type = "New Assessment";
            if ($inputs['holding_no']!="") {
                $isReassessment = true;
                $assessment_type = "Reassessment";
            }

            $ward_no = $this->model_ward_mstr->getWardNoById(['ward_mstr_id'=>$inputs['ward_mstr_id']])['ward_no'];
            $ward_no = explode('/', $ward_no)[0];
            $lastApplicationNo = $this->model_govt_saf_dtl->getLastApplicationNoByGovtSafDtlId(['ward_mstr_id'=>$inputs['ward_mstr_id']]);
            if($lastApplicationNo['application_no']==null) {
                $application_no_pos_4 = "0001";
            } else {
                $application_no_pos_4 = explode('/', $lastApplicationNo['application_no']);
				$application_no_pos_4 = end($application_no_pos_4);
                $application_no_pos_4 = str_pad(++$application_no_pos_4, 4, "0", STR_PAD_LEFT);
            }
            $application_no_pos_2 = ($isReassessment==true)?"02":"01";
            $application_no = "GBSAF/".$application_no_pos_2."/".str_pad($ward_no, 2, "0", STR_PAD_LEFT)."/".$application_no_pos_4;

			$input = [
				'assessment_type'=>$assessment_type,
                'application_no'=>$application_no,
                'building_colony_name'=>$inputs['building_colony_name'],
                'building_colony_address'=>$inputs['building_colony_address'],
                'office_name'=>$inputs['office_name'],
                'ward_mstr_id'=>$inputs['ward_mstr_id'],
                'holding_no'=>($inputs['holding_no']!="")?$inputs['holding_no']:null,
                'prop_dtl_id'=>($inputs['prop_dtl_id']!="")?$inputs['prop_dtl_id']:null,
                'colony_mstr_id'=>NULL,
                'application_type'=>'Govt. Building Self Assessment Form (GBSAF)',
                'prop_type_mstr_id'=>2,
				'govt_building_type_mstr_id'=>$inputs['govt_building_type_mstr_id'],
				'prop_usage_type_mstr_id'=>$inputs['prop_usage_type_mstr_id'],
				'zone_mstr_id'=>$inputs['zone_mstr_id'],
                'road_type_mstr_id'=>$inputs['road_type_mstr_id'],

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
                'is_transaction_done'=>0,
				'doc_verify_status'=>0,
				'emp_details_id'=> $inputs['emp_details_id'],
				'ip_address'=> $inputs['ip_address'],
				'created_on'=>$created_on,
                'updated_on'=>$updated_on,
				'status'=>1,
                'app_status'=>1,
                'is_nigam_data'=>0,
                'application_mode'=>'SRI',
				'apply_date'=>$apply_date
			];

			$govt_saf_dtl_id = $this->model_govt_saf_dtl->insertData($input);
			

            $input = [
				'govt_saf_dtl_id'=>$govt_saf_dtl_id,
                'officer_name'=>$inputs['office_name'],
                'designation'=>$inputs['designation'],
                'address'=>$inputs['address'],
            ];
			
            $this->model_govt_saf_officer_dtl->insertData($input);

            for($i=0; $i<sizeof($inputs['floor_mstr_id']); $i++){
                if ($inputs['usage_type_mstr_id'][$i]==1) {
                    $carpet_area = (($inputs['builtup_area'][$i]*70)/100);
                } else {
                    $carpet_area = (($inputs['builtup_area'][$i]*80)/100);
                }
                $input = [
                    'govt_saf_dtl_id'=>$govt_saf_dtl_id,
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
                $this->model_govt_saf_floor_dtl->insertData($input);
            }


			// ARV & TAX CALCULATION
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
				//print_r($yrOfEffect_16_17_FyID);

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
				if($inputs['is_water_harvesting']==1){
					$isWaterHarvesting = true;
				}
				$area_of_plot = ($inputs['area_of_plot']*40.5);
				/*if($area_of_plot > 300){
					$isWaterHarvesting = true;
					if($inputs['is_water_harvesting']==1){
						$isWaterHarvesting = false;
					}
				}*/

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
													'govt_saf_dtl_id'=>$govt_saf_dtl_id,
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
												//$this->model_saf_floor_arv_dtl->insertData($inputDtl);
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
																			'govt_saf_dtl_id'=>$govt_saf_dtl_id,
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
																		//$this->model_saf_floor_arv_dtl->insertData($inputDtl);
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
													'govt_saf_dtl_id'=>$govt_saf_dtl_id,
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
												//$this->model_saf_floor_arv_dtl->insertData($inputDtl);
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
							if($isWaterHarvesting==false){
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
				$last_govt_saf_tax_id = 0;
				$last_amount = 0;
				foreach ($safTaxDtl as $key=>$safTax) {
					if ( $safTax['arv']==0) {
						$govt_saf_tax_dtl_id = $last_govt_saf_tax_id;
						$amount = $last_amount;
					} else {
						$i++;
						$input = [
							'govt_saf_dtl_id'=>$govt_saf_dtl_id,
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
						$last_govt_saf_tax_id = $govt_saf_tax_dtl_id = $this->model_govt_saf_tax->insertData($input);


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
						for( $j = $safTax['fyID']; $j <= $next_fy_mstr_id; $j++) {
							$zz = 1;
							if ($j==$safTax['fyID']) {
								$zz = $safTax['qtr'];
							}
							$zzz = 4;
							if ($j==$next_qtr) {
								$zzz = $safTax['qtr'];
							}
                            $fyData = getFyFromFyListByFyID($getFyList, $j);

							for ( $z = $zz; $z <= $zzz; $z++ ) {
                                $due_date = getDateFromFyAndQtr($fyData, $z);
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
												if ( $saf_demand_dtl = $this->model_govt_saf_demand->getSumDemandBySafDtlIdFyIdQtr($inputCheckTotal) ) {
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
														'govt_saf_dtl_id'=>$govt_saf_dtl_id,
														'govt_saf_tax_dtl_id'=>$govt_saf_tax_dtl_id,
														'fy_mstr_id'=>$j,
														'qtr'=>$z,
														'amount'=>$remaining_amt,
														'balance'=>$remaining_amt,
                                                        'due_date'=>$due_date,
														'fine_tax'=>0,
														'created_on'=>$created_on,
														'status'=>1
													];
													$this->model_govt_saf_demand->insertData($input);
												} else if( $saf_prop_demand > $amount_qtr ) {
													// smallest
													$remaining_amt = round($saf_prop_demand - $amount_qtr);
													$adjustment_amt += $remaining_amt;
												}
											} else {
												$input = [
													'govt_saf_dtl_id'=>$govt_saf_dtl_id,
													'govt_saf_tax_dtl_id'=>$govt_saf_tax_dtl_id,
													'fy_mstr_id'=>$j,
													'qtr'=>$z,
													'amount'=>$amount_qtr,
													'balance'=>$amount_qtr,
                                                    'due_date'=>$due_date,
													'fine_tax'=>0,
													'created_on'=>$created_on,
													'status'=>1
												];
												$this->model_govt_saf_demand->insertData($input);
											}
										} else {
											$input = [
												'govt_saf_dtl_id'=>$govt_saf_dtl_id,
												'govt_saf_tax_dtl_id'=>$govt_saf_tax_dtl_id,
												'fy_mstr_id'=>$j,
												'qtr'=>$z,
												'amount'=>$amount_qtr,
												'balance'=>$amount_qtr,
                                                'due_date'=>$due_date,
												'fine_tax'=>0,
												'created_on'=>$created_on,
												'status'=>1
											];
											$this->model_govt_saf_demand->insertData($input);
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
											if ( $saf_demand_dtl = $this->model_govt_saf_demand->getSumDemandBySafDtlIdFyIdQtr($inputCheckTotal) ) {
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
													'govt_saf_dtl_id'=>$govt_saf_dtl_id,
													'govt_saf_tax_dtl_id'=>$govt_saf_tax_dtl_id,
													'fy_mstr_id'=>$j,
													'qtr'=>$z,
													'amount'=>$remaining_amt,
													'balance'=>$remaining_amt,
                                                    'due_date'=>$due_date,
													'fine_tax'=>0,
													'created_on'=>$created_on,
													'status'=>1
												];
												$this->model_govt_saf_demand->insertData($input);
											} else if( $saf_prop_demand > $amount_qtr ) {
												// smallest
												$remaining_amt = ($saf_prop_demand - $amount_qtr);
												$adjustment_amt += $remaining_amt;
											}
										} else {
											$input = [
												'govt_saf_dtl_id'=>$govt_saf_dtl_id,
												'govt_saf_tax_dtl_id'=>$govt_saf_tax_dtl_id,
												'fy_mstr_id'=>$j,
												'qtr'=>$z,
												'amount'=>$amount_qtr,
												'balance'=>$amount_qtr,
                                                'due_date'=>$due_date,
												'fine_tax'=>0,
												'created_on'=>$created_on,
												'status'=>1
											];
											$this->model_govt_saf_demand->insertData($input);
										}
									} else {
										$input = [
											'govt_saf_dtl_id'=>$govt_saf_dtl_id,
											'govt_saf_tax_dtl_id'=>$govt_saf_tax_dtl_id,
											'fy_mstr_id'=>$j,
											'qtr'=>$z,
											'amount'=>$amount_qtr,
											'balance'=>$amount_qtr,
                                            'due_date'=>$due_date,
											'fine_tax'=>0,
											'created_on'=>$created_on,
											'status'=>1
										];
										$this->model_govt_saf_demand->insertData($input);
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
							//$this->model_payment_adjust->insertData($input);
						}
					}
				}
		    // end building calculation details
			if($this->db->transStatus() === FALSE){
				$this->db->transRollback();
				return false;
			}else{
				$this->db->transCommit();
				return $govt_saf_dtl_id;
				//return false;
			}
		}catch(Exception $e){
			echo $e->getMessage();
			echo $e->getFile();
			echo $e->getLine();
		}
	}

	public function add_update() {
		$safHelper = new SAFHelper($this->db);
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$emp_details_id = $_SESSION['emp_details']['id'];
		$ip_address = $_SESSION['emp_details']['ip_address'];

        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $propTypeList = $this->model_prop_type_mstr->getPropTypeList();
        $floorList = $this->model_floor_mstr->getFloorList();
        $usageTypeList = $this->model_usage_type_mstr->getUsageTypeList();
        $occupancyTypeList = $this->model_occupancy_type_mstr->getOccupancyTypeList();
        $constTypeList = $this->model_const_type_mstr->getConstTypeList();
        $govtBuildTypeList = $this->model_govt_building_type_mstr->getGovtBuilTypeList();
        $propUsageTypeList = $this->model_prop_usage_type_mstr->getPropUsageTypeList();
        $roadTypeList = $this->model_road_type_mstr->getRoadTypeList();

		$inputs = [];
		if($this->request->getMethod()=='post') {
			$inputs = arrFilterSanitizeString($this->request->getVar());
            $data = $inputs;
		}
        $data['wardList'] = $wardList;
        $data['propTypeList'] = $propTypeList;
        $data['floorList'] = $floorList;
        $data['usageTypeList'] = $usageTypeList;
        $data['occupancyTypeList'] = $occupancyTypeList;
        $data['constTypeList'] = $constTypeList;
        $data['govtBuildTypeList'] = $govtBuildTypeList;
        $data['propUsageTypeList'] = $propUsageTypeList;
        $data['roadTypeList'] = $roadTypeList;
		

        if($this->request->getMethod()=='post') {
			if (isset($_POST['btn_back'])) {
				return view('property/gsaf/gsaf_add_update', $data);
			} else if(isset($_POST['btn_review'])) {
				$floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);					
				$data['floorDtlArr'] = $floorDtlArr;
				
				
				$isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
				
				list($safTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calBuildingTaxDtl($floorDtlArr, $inputs['prop_type_mstr_id'], $isAdditionaTaxImplemented);
				$data['safTaxDtl'] = $safTaxDtl;
				$data["old_rule_arv_sub"] = $old_rule_arv_sub;
				$data["new_rule_arv_sub"] = $new_rule_arv_sub;
				$data["cv_rule_arv_sub"] = $cv_rule_arv_sub;
				
				return view('property/gsaf/gsaf_add_update_review',$data);
			} else if(isset($_POST['btn_submit'])) {
				$inputs['emp_details_id'] = $emp_details_id;
				$inputs['ip_address'] = $ip_address;

				/* if ($govt_saf_dtl_id = $this->addUpdateSubmit($inputs) ) {
					$LINK = base_url('govsafDetailPayment/gov_saf_due_details/'.md5($govt_saf_dtl_id));
					return redirect()->to($LINK);
				} */

				$this->db->transBegin();
				$floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
				$isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
				list($safTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calBuildingTaxDtl($floorDtlArr, $inputs['prop_type_mstr_id'], $isAdditionaTaxImplemented);

				$govt_saf_dtl_id = $safHelper->savegovtSafData($inputs);
				$safHelper->calSafDemand($safTaxDtl, $govt_saf_dtl_id, $inputs["prev_prop_dtl_id"]);
				if ($this->db->transStatus() === false) {
					$this->db->transRollback();
				} else {
					$this->db->transCommit();
					$LINK = base_url('govsafDetailPayment/gov_saf_due_details/'.md5($govt_saf_dtl_id));
					return redirect()->to($LINK);
				}
			}
		} else {
			return view('property/gsaf/gsaf_add_update', $data);
		}
	}

	public function add_update2()
	{

    	$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$emp_details_id = $_SESSION['emp_details']['id'];
		$ip_address = $_SESSION['emp_details']['ip_address'];

        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $propTypeList = $this->model_prop_type_mstr->getPropTypeList();
        $floorList = $this->model_floor_mstr->getFloorList();
        $usageTypeList = $this->model_usage_type_mstr->getUsageTypeList();
        $occupancyTypeList = $this->model_occupancy_type_mstr->getOccupancyTypeList();
        $constTypeList = $this->model_const_type_mstr->getConstTypeList();
        $govtBuildTypeList = $this->model_govt_building_type_mstr->getGovtBuilTypeList();
        $propUsageTypeList = $this->model_prop_usage_type_mstr->getPropUsageTypeList();
        $roadTypeList = $this->model_road_type_mstr->getRoadTypeList();

        $data['wardList'] = $wardList;
        $data['propTypeList'] = $propTypeList;
        $data['floorList'] = $floorList;
        $data['usageTypeList'] = $usageTypeList;
        $data['occupancyTypeList'] = $occupancyTypeList;
        $data['constTypeList'] = $constTypeList;
        $data['govtBuildTypeList'] = $govtBuildTypeList;
        $data['propUsageTypeList'] = $propUsageTypeList;
        $data['roadTypeList'] = $roadTypeList;
		

        if($this->request->getMethod()=='post')
		{
			// try
			// {
				$inputs = arrFilterSanitizeString($this->request->getVar());
                $data = $inputs;
                $data['wardList'] = $wardList;
                $data['propTypeList'] = $propTypeList;
                $data['floorList'] = $floorList;
                $data['usageTypeList'] = $usageTypeList;
                $data['occupancyTypeList'] = $occupancyTypeList;
                $data['constTypeList'] = $constTypeList;
                $data['govtBuildTypeList'] = $govtBuildTypeList;
                $data['propUsageTypeList'] = $propUsageTypeList;
                $data['roadTypeList'] = $roadTypeList;

                if(isset($_POST['btn_back']))
				{
                    return view('property/gsaf/gsaf_add_update', $data);
                }
				if(isset($_POST['btn_review']))
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
						if($inputs['is_water_harvesting']==1){
							$isWaterHarvesting = true;
						}
                        /*if($area_of_plot > 300){
                            $isWaterHarvesting = true;
                        }*/
						//echo $isWaterHarvesting;

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
                                        if($isWaterHarvesting==false){
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
						// print_var($data);
						// return;
                        // end building calculation details
                    return view('property/gsaf/gsaf_add_update_review',$data);
                }
				else if(isset($_POST['btn_submit'])) {
					$inputs['emp_details_id'] = $emp_details_id;
					$inputs['ip_address'] = $ip_address;

                    if ($govt_saf_dtl_id = $this->addUpdateSubmit($inputs) ) {
                        $LINK = base_url('govsafDetailPayment/gov_saf_due_details/'.md5($govt_saf_dtl_id));
                        return redirect()->to($LINK);
                    }
                }
            // } catch(Exception $e) {
			// 	echo $e->getMessage();
			// }
        }
		else
		{
            return view('property/gsaf/gsaf_add_update',$data);
        }
	}

    public function create($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") // insert
            {
            $rules=[
                'colony_name'=>'required',
                'colony_address'=>'required',
            ];
            if(!$this->validate($rules)){
                $data['validation']=$this->validator;
            }
            else
              {
              //store the data
                    $data = [
                        'colony_name' => $this->request->getVar('colony_name'),
                        'colony_address' => $this->request->getVar('colony_address'),
                    ];
                    $data['data_exist']=$this->model_colony_type_mstr->checkdata($data);
                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/gsaf_add_update',$data);
                         }
                    else
                    {
                        if($insert_last_id = $this->model_colony_type_mstr->insertData($data)){
                          flashToast('colony', "Data Inserted  Successfully.");
                         return $this->response->redirect(base_url('Gsaf/create'));
              					}
              					else{
              						return view('master/colony',$data);
              					}
                    }
                            }
                        }
                        else
                        {
                //update code
                $rules=[
                  'colony_name'=>'required',
                  'colony_address'=>'required',
                ];
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                        'colony_name' => $this->request->getVar('colony_name'),
                        'colony_address' => $this->request->getVar('colony_address'),
						            'id' => $this->request->getVar('id')
                    ];
                    $data['data_exist']=$this->model_colony_type_mstr->checkupdatedata($data);
                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('master/ownership_type_add_update',$data);
                     }
                    else{
                        if($updaterow = $this->model_colony_type_mstr->updatedataById($data)){
                          flashToast('colony', "Data Updated  Successfully.");
                         	return $this->response->redirect(base_url('Colony/colonylist'));
            						}
            						else{
            							return view('master/colony_add_update',$data);
            						}
                                }

                            }
                        }
                    }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            $data['colony']=$this->model_colony_type_mstr->getdatabyid($id);
            return view('master/colony_add_update',$data);

        }
        else
        {
            $data['title']="Add";
            return view('master/colony_add_update',$data);
		}
    }

    public function delete($id=null)
    {
        $data['usage']=$this->model_colony_type_mstr->deletedataById($id);
        flashToast('colony', "Data Deleted  Successfully.");
        return $this->response->redirect(base_url('Colony/colonylist'));
    }


    }
