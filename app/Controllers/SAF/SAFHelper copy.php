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

class SAFHelperCopy extends Controller
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

	public function getNewWardByOldWardID($oldWardId) {
		$newWardList = NULL;
		if($old_ward_dtl = $this->model_ward_mstr->getWardNoByOldWardId($oldWardId)) {
			$ward_no = multiExplode(["A", "a", "/"], $old_ward_dtl["ward_no"])[0];
			if($old_ward_dtl = $this->model_ward_mstr->getWardNoByOldWardNo($ward_no)) {
				$newWardList = $this->model_view_ward_mapping_mstr->getNewWardLIstByOldWardMstrId($old_ward_dtl['id']);
			}
		}
		return $newWardList;
	}

    public function getSafMstrDtl() {
    	$client = new \Predis\Client();
		//$client->del("saf_master_list");
		$saf_master_list = $client->get("saf_master_list");
		if (!$saf_master_list) {

	        $sql = "WITH tbl_transfer_mode_mstr AS (SELECT json_agg(tbl_transfer_mode_mstr ORDER BY id) AS transfer_mode_mstr FROM tbl_transfer_mode_mstr WHERE status=1),
	                    tbl_ownership_type_mstr AS (SELECT json_agg(tbl_ownership_type_mstr ORDER BY id) AS ownership_type_mstr FROM tbl_ownership_type_mstr WHERE status=1),
	                    tbl_prop_type_mstr AS (SELECT json_agg(tbl_prop_type_mstr ORDER BY id) AS prop_type_mstr FROM tbl_prop_type_mstr WHERE status=1),
	                    tbl_road_type_mstr AS (SELECT json_agg(tbl_road_type_mstr ORDER BY id) AS road_type_mstr FROM tbl_road_type_mstr WHERE status=1),
	                    tbl_floor_mstr AS (SELECT json_agg(tbl_floor_mstr ORDER BY id) AS floor_mstr FROM tbl_floor_mstr WHERE status=1),
	                    tbl_usage_type_mstr AS (SELECT json_agg(tbl_usage_type_mstr ORDER BY id) AS usage_type_mstr FROM tbl_usage_type_mstr WHERE status=1),
	                    tbl_occupancy_type_mstr AS (SELECT json_agg(tbl_occupancy_type_mstr ORDER BY id) AS occupancy_type_mstr FROM tbl_occupancy_type_mstr WHERE status=1),
	                    tbl_const_type_mstr AS (SELECT json_agg(tbl_const_type_mstr ORDER BY id) AS const_type_mstr FROM tbl_const_type_mstr WHERE status=1),
						tbl_apartment_details AS (SELECT json_agg(tbl_apartment_details ORDER BY id) AS apartment_dtl FROM tbl_apartment_details WHERE status=1)
	                    SELECT * FROM tbl_transfer_mode_mstr, tbl_ownership_type_mstr, 
	                    tbl_prop_type_mstr, tbl_road_type_mstr, tbl_floor_mstr, 
	                    tbl_usage_type_mstr, tbl_occupancy_type_mstr, tbl_const_type_mstr, tbl_apartment_details";
	        $fetch = $this->db->query($sql);
	        $saf_master_list = $fetch->getFirstRow("array");
			$client->set("saf_master_list", json_encode($saf_master_list));
	    } else {
			return json_decode($saf_master_list, true);
		}
    }

	public function makeFirstDateByFyearQtr($fyear, $qtr) {
		list($fyear1, $fyear2) = explode("-", $fyear);
		if ($qtr==1) {
			return $fyear1."-04-01";
		} else if ($qtr==2) {
			return $fyear1."-07-01";
		} else if ($qtr==3) {
			return $fyear1."-10-01";
		} else if ($qtr==4) {
			return $fyear2."-01-01";
		}
	}

	public function makeDueDateByFyearQtr($fyear, $qtr) {
		list($fyear1, $fyear2) = explode("-", $fyear);
		if ($qtr==1) {
			return $fyear1."-06-30";
		} else if ($qtr==2) {
			return $fyear1."-09-30";
		} else if ($qtr==3) {
			return $fyear1."-12-31";
		} else if ($qtr==4) {
			return $fyear2."-03-31";
		}
	}

	public function makeDueDateByDate($byDate) {
		$fy = getFY($byDate);
		$qtr = $this->getQtr($byDate);
		return $this->makeDueDateByFyearQtr($fy, $qtr);
	}

	public function getFyID($FY) {
        return $this->model_fy_mstr->getFyByFy(['fy'=>$FY])['id'];
	}

	public function getQtr($date) {
		$MM = date("m", strtotime($date));
		if ($MM>=1 && 3>=$MM) { // X1
			return 4;
		} else if ($MM>=4 && 6>=$MM) { // X4
			return 1;
		} else if ($MM>=7 && 9>=$MM) { // X3
			return 2;
		} else if ($MM>=10 && 12>=$MM) { // X2
			return 3;
		} else {
			return 0;
		}
	}

	public function checkIsAdditionaTaxImplemented($is_water_harvesting, $prop_type_mstr_id, $area_of_plot) {
		$isAdditionaTaxImplemented = FALSE;

		if ($is_water_harvesting==0) {
			$isAdditionaTaxImplemented = TRUE;
			if (in_array($prop_type_mstr_id, [1, 2 ,5])) {
				$area_of_plot = $area_of_plot*435.6;
				if ($area_of_plot < 3228) {
					$isAdditionaTaxImplemented = FALSE;
				}
			} // flat is always check water harvesting is true or false other case check less than 3228 sq ft
		}
		return $isAdditionaTaxImplemented;
	}

	public function makeBuildingFloorDtlArr($inputs) {
		$ward_no = $this->db->table("view_ward_mstr")
							->select("split_part(split_part(ward_no, 'A', 1), '/', 1) AS ward_no")
							->where("id", $inputs['ward_mstr_id'])
							->get()
            				->getFirstRow("array")["ward_no"];

		$taxEffectedFrom = date('Y-04-01', strtotime('-12 year'));
		$taxEffectedFromFY = getFY($taxEffectedFrom);
		
		$floorDtlArr = [];
		
		for ($i=0; $i<sizeof($inputs['floor_mstr_id']); $i++) {
			$floorDateFromFY = getFY($inputs['date_from'][$i]);
			$floorDateFromFyID = $this->getFyID($floorDateFromFY);
			$temp_qtr = $this->getQtr($inputs['date_from'][$i]);

			$floorDateUptoFY = "";
			$floorDateUptoFyID = 0;
			$floorDateUptoQtr = 0;
			if ($inputs['date_upto'][$i]<>"") {
				$floorDateUptoFY = getFY($inputs['date_upto'][$i]);
				$floorDateUptoFyID = $this->getFyID($floorDateUptoFY);
				$floorDateUptoQtr = $this->getQtr($inputs['date_upto'][$i]);
			}
			$date_from = $inputs['date_from'][$i];
			if ($inputs['date_from'][$i]."-01" < $taxEffectedFrom) {
				$date_from = date("Y-m", strtotime($taxEffectedFrom));
				$floorDateFromFY = $taxEffectedFromFY;
				$temp_qtr = 1;
			}
			// old calc rate
			$usage_type_mstr_id = 2;
			if ($inputs['occupancy_type_mstr_id'][$i]==1 && $inputs['usage_type_mstr_id'][$i]==1) { $usage_type_mstr_id = 1; }
			$sendInput = ['usage_type_mstr_id'=>$usage_type_mstr_id, 'const_type_mstr_id'=>$inputs['const_type_mstr_id'][$i], 'zone_mstr_id'=>$inputs['zone_mstr_id']];
			$mrr_old = $this->model_arr_old_building_mstr->getMRRCalRate($sendInput)['rate'];
			$old_arv = $inputs['builtup_area'][$i]*$mrr_old;
			// IN old rule less some percentage in arv (Residential - 30%, Commercial - 15%)
			$rebate_on_arv = (int)15;
			if ($usage_type_mstr_id==1) {
				$rebate_on_arv = (int)30;   
			}
			//Holding older than 25 years (as on 1967-68) - 10% Own occupation (INDEPENDENT BUILDING)
			if ($inputs["prop_type_mstr_id"]==2 && $inputs['occupancy_type_mstr_id'][$i]==1 && $inputs['usage_type_mstr_id'][$i]==1) {
				$rebate_date = $inputs['date_from'][$i]."-01";
				if ("1942-04-01" > $rebate_date) {
						$rebate_on_arv += 10;
				}
			}
			if ($old_arv!=0) {
				$old_arv = $old_arv-(($old_arv*$rebate_on_arv)/100);
			}
			// end old calc rate
			//---------------------------------------------------------//
			// new calc rate
			$afr = $this->model_occupancy_type_mstr->getOccupancyMultFactById($inputs['occupancy_type_mstr_id'][$i])['mult_factor'];
			$sendInput = ['usage_type_mstr_id'=>$inputs['usage_type_mstr_id'][$i]];
			$usageTypeMultFact = $this->model_usage_type_dtl->getUsageTypeMultFact($sendInput);
			$mf = $usageTypeMultFact['mult_factor'];
			$cv_mf = $usageTypeMultFact['cv_mult_factor'];
			$sendInput = ['road_type_mstr_id'=>$inputs['road_type_mstr_id'], 'const_type_mstr_id'=>$inputs['const_type_mstr_id'][$i], 'date_of_effect'=>'2016-04-01'];
			$mrr = $this->model_arr_building_mstr->getMRRCalRate($sendInput)['cal_rate'];
			$carperArea = 0;
			if ($inputs['usage_type_mstr_id'][$i]==1) $carperArea = (($inputs['builtup_area'][$i]*70)/100); else $carperArea = (($inputs['builtup_area'][$i]*80)/100);
			$new_arv = $afr*$mf*$carperArea*$mrr;
			// end new calc rate
			//---------------------------------------------------------//
			// capital value calc rate			
			$property_type = "DLX_APARTMENT";
			if (in_array($inputs['prop_type_mstr_id'], ["1", "2", "5"])) { // 1=Super, 2=Independent, 3=Occupied
				$property_type = ($inputs['const_type_mstr_id'][$i]==1)?"BUILDING_PAKKA":"BUILDING_KACCHA";
			}
			//$ward_mstr_id = $inputs['new_ward_mstr_id'];
			$road_type_mstr_id = ($inputs['road_type_mstr_id']=="1")?1:0;
			$usage_type = ($inputs['usage_type_mstr_id'][$i])=="1"?"RESIDENTIAL":"COMMERCIAL";
			$cvr2022  = $this->model_capital_value_rate->getCVR2022($property_type, $ward_no, $road_type_mstr_id, $usage_type)["rate"];
			
			$occupancyTypeRate = (float)($inputs['occupancy_type_mstr_id'][$i]==1)?1:1.5;
			//$resiCommTypeRate = (float)($usage_type=="RESIDENTIAL")?0.00075:0.0015;
			$resiCommTypeRate = 0;
			if ($inputs['usage_type_mstr_id'][$i]==44) {
				$resiCommTypeRate = (float)0.00075;
			} else if ($usage_type=="RESIDENTIAL") {
				$resiCommTypeRate = (float)0.00075;
			} else {
				if ($inputs['builtup_area'][$i]>25000 && in_array($inputs['usage_type_mstr_id'][$i], [2, 4, 13, 17, 20, 22])) {
					$resiCommTypeRate = (float)0.0020;
				} else {
					$resiCommTypeRate = (float)0.0015;
				}
			}
			$matrixFactorRate = 1;
			if ($inputs['usage_type_mstr_id'][$i]==1) {
				if ($road_type_mstr_id==0 && $property_type=="DLX_APARTMENT") {
					$matrixFactorRate = 0.8;
				} else if ($road_type_mstr_id==1 && $property_type=="BUILDING_KACCHA") {
					$matrixFactorRate = 0.5;
				} else if ($road_type_mstr_id==0 && $property_type=="BUILDING_PAKKA") {
					$matrixFactorRate = 0.8;
				} else if ($road_type_mstr_id==0 && $property_type=="BUILDING_KACCHA") {
					$matrixFactorRate = 0.4;
				}
			}
			$cv2022 = $cvr2022*$inputs['builtup_area'][$i]*$occupancyTypeRate*$resiCommTypeRate*$cv_mf*$matrixFactorRate;
			
			// end capital value calc rate
			$floorDtlArr[] = [
				'type'=>'floor',
				'floor_mstr_id'=>$inputs['floor_mstr_id'][$i],
				'usage_type_mstr_id'=>$inputs['usage_type_mstr_id'][$i],
				'occupancy_type_mstr_id'=>$inputs['occupancy_type_mstr_id'][$i],
				'const_type_mstr_id'=>$inputs['const_type_mstr_id'][$i],
				'builtup_area'=>$inputs['builtup_area'][$i],
				'carper_area'=>$carperArea,
				'date_from'=>$date_from,
				'fyear'=>$floorDateFromFY,
				'fy_mstr_id'=>$floorDateFromFyID,
				'qtr'=>$temp_qtr,
				'date_upto'=>$inputs['date_upto'][$i],
				'upto_fy_mstr_id'=>$floorDateUptoFyID,
				'upto_qtr'=>$floorDateUptoQtr,
				'old_arv'=>$old_arv,
				'new_arv'=>$new_arv,
				'cv'=>$cv2022,
				'operator'=>'+',
				'old_arv_cal_method'=>['rental_rate'=>$mrr_old, 'buildup_area'=>$inputs['builtup_area'][$i], 'rebate_on_arv'=>$rebate_on_arv],
				'new_arv_cal_method'=>['usage_factor'=>$mf, 'occupancy_factor'=>$afr, 'rental_rate'=>$mrr, 'carper_area'=>$carperArea],
				'cv_2022_cal_method'=>['cvr'=>$cvr2022, 'buildup_area'=>$inputs['builtup_area'][$i], 'occupancy_rate'=>$occupancyTypeRate, 'resi_comm_type_rate'=>$resiCommTypeRate, 'calculation_factor'=>$cv_mf,'matrix_factor_rate'=>$matrixFactorRate]
			];

			if ( $floorDateUptoFyID<>0 && $floorDateUptoQtr<>0 ) {
				if ( $floorDateUptoQtr==4 ) {
					$floorDateUptoQtr = 1;
					$floorDateUptoFyID = $floorDateUptoFyID+1;
					list($tempFy1, $tempFy2) = explode('-', $floorDateUptoFY);
					$floorDateUptoFY = ($tempFy1+1)."-".($tempFy2+1);
				}else {
					$floorDateUptoQtr = $floorDateUptoQtr+1;
				}
				$date_upto = $inputs['date_upto'][$i];
				if ($floorDateUptoQtr==1) {
					$date_upto = date("Y", strtotime($inputs['date_upto'][$i]))."-06";
				} else if ($floorDateUptoQtr==2) {
					$date_upto = date("Y", strtotime($inputs['date_upto'][$i]))."-09";
				} else if ($floorDateUptoQtr==3) {
					$date_upto = date("Y", strtotime($inputs['date_upto'][$i]))."-09";
				} else if ($floorDateUptoQtr==4) {
					$YYYY = date("Y", strtotime($inputs['date_upto'][$i]));
					$YYYY = $YYYY+1;
					$date_upto = $YYYY."-03";
				}
				$floorDtlArr[] = [
					'type'=>'floor',
					'floor_mstr_id'=>$inputs['floor_mstr_id'][$i],
					'usage_type_mstr_id'=>$inputs['usage_type_mstr_id'][$i],
					'occupancy_type_mstr_id'=>$inputs['occupancy_type_mstr_id'][$i],
					'const_type_mstr_id'=>$inputs['const_type_mstr_id'][$i],
					'builtup_area'=>$inputs['builtup_area'][$i],
					'carper_area'=>$carperArea,
					'date_from'=>$date_upto,
					'fyear'=>$floorDateUptoFY,
					'fy_mstr_id'=>$floorDateUptoFyID,
					'qtr'=>$floorDateUptoQtr,
					'date_upto'=>$date_upto,
					'upto_fy_mstr_id'=>$floorDateUptoFyID,
					'upto_qtr'=>$floorDateUptoQtr,
					'old_arv'=>$old_arv,
					'new_arv'=>$new_arv,
					'cv'=>$cv2022,
					'operator'=>'-',
					'old_arv_cal_method'=>['rental_rate'=>$mrr_old, 'buildup_area'=>$inputs['builtup_area'][$i], 'rebate_on_arv'=>$rebate_on_arv],
					'new_arv_cal_method'=>['usage_factor'=>$mf, 'occupancy_factor'=>$afr, 'rental_rate'=>$mrr, 'carper_area'=>$carperArea],
					'cv_2022_cal_method'=>['cvr'=>$cvr2022, 'buildup_area'=>$inputs['builtup_area'][$i], 'occupancy_rate'=>$occupancyTypeRate, 'resi_comm_type_rate'=>$resiCommTypeRate, 'calculation_factor'=>$cv_mf, 'matrix_factor_rate'=>$matrixFactorRate]
				];
			}
		}

		if ($inputs['is_mobile_tower']==1) {
			if(date("Y-m-01", strtotime($inputs['tower_installation_date'])) > "2016-04-01") {
				$date_from = date("Y-m", strtotime($inputs['tower_installation_date']));
				$fy = getFY($inputs['tower_installation_date']);
				$qtr = $this->getQtr($date_from);
			} else {
				$date_from = "2016-04";
				$fy = "2016-2017";
				$qtr = 1;
			}

			// new calc rate
			$sendInput = ['road_type_mstr_id'=>$inputs['road_type_mstr_id'], 'const_type_mstr_id'=>1, 'date_of_effect'=>'2016-04-01'];
			$mrr = $this->model_arr_building_mstr->getMRRCalRate($sendInput)['cal_rate'];
			//$carperArea = (($inputs['tower_area']*80)/100);
			$carperArea = $inputs['tower_area'];
			$new_arv = 2.25*$carperArea*$mrr; //echo "1.5 * 1.5 * $carperArea * $mrr = ".$new_arv. >> mobile arv;
			// end new calc rate
			//---------------------------------------------------------//
			// capital value calc rate			
			$property_type = "BUILDING_PAKKA";
			$road_type_mstr_id = ($inputs['road_type_mstr_id']=="1")?1:0;
			$usage_type = "COMMERCIAL";
			$cvr2022  = $this->model_capital_value_rate->getCVR2022($property_type, $ward_no, $road_type_mstr_id, $usage_type)["rate"];
			
			$occupancyTypeRate = (float)1.5;
			$resiCommTypeRate = (float)0.0015;
			$matrixFactorRate = 1;
			$cv2022 = $cvr2022*$inputs['tower_area']*$occupancyTypeRate*$resiCommTypeRate*$matrixFactorRate;
			
			// end capital value calc rate
			
			$floorDtlArr[] = [
				'type'=>'mobile',
				'area_type'=>"Total Area Covered by Mobile Tower & its Supporting Equipments & Accessories (in Sq. Ft.)",
				'floor_mstr_id'=>0,
				'usage_type_mstr_id'=>0,
				'occupancy_type_mstr_id'=>0,
				'const_type_mstr_id'=>1,
				'builtup_area'=>$inputs['tower_area'],
				'carper_area'=>$carperArea,
				'date_from'=>$date_from,
				'fyear'=>$fy,
				'qtr'=>$qtr,
				'date_upto'=>"",
				'upto_fy_mstr_id'=>0,
				'upto_qtr'=>0,
				'old_arv'=>0,
				'cv'=>$cv2022,
				'new_arv'=>$new_arv,
				'operator'=>'+',
				'old_arv_cal_method'=>null,
				'new_arv_cal_method'=>['usage_factor'=>1.5, 'occupancy_factor'=>1.5, 'rental_rate'=>$mrr, 'carper_area'=>$carperArea],
				'cv_2022_cal_method'=>['cvr'=>$cvr2022, 'buildup_area'=>$inputs['tower_area'], 'occupancy_rate'=>$occupancyTypeRate, 'resi_comm_type_rate'=>$resiCommTypeRate, 'matrix_factor_rate'=>$matrixFactorRate]
			];
		}

		if ($inputs['is_hoarding_board']==1) {
			if(date("Y-m-01", strtotime($inputs['hoarding_installation_date'])) > "2016-04-01") {
				$date_from = date("Y-m", strtotime($inputs['hoarding_installation_date']));
				$fy = getFY($inputs['hoarding_installation_date']);
				$qtr = $this->getQtr($date_from);
			} else {
				$date_from = "2016-04";
				$fy = "2016-2017";
				$qtr = 1;
			}

			// new calc rate
			$sendInput = ['road_type_mstr_id'=>$inputs['road_type_mstr_id'], 'const_type_mstr_id'=>1, 'date_of_effect'=>'2016-04-01'];
			$mrr = $this->model_arr_building_mstr->getMRRCalRate($sendInput)['cal_rate'];
			//$carperArea = (($inputs['hoarding_area']*80)/100);
			$carperArea = $inputs['hoarding_area'];
			$new_arv = 2.25*$carperArea*$mrr; //echo "1.5 * 1.5 * $carperArea * $mrr = ".$new_arv. >> hoarding arv;
			// end new calc rate
			//---------------------------------------------------------//
			// capital value calc rate			
			$property_type = "BUILDING_PAKKA";
			//$ward_mstr_id = $inputs['new_ward_mstr_id'];
			$road_type_mstr_id = ($inputs['road_type_mstr_id']=="1")?1:0;
			$usage_type = "COMMERCIAL";
			$cvr2022  = $this->model_capital_value_rate->getCVR2022($property_type, $ward_no, $road_type_mstr_id, $usage_type)["rate"];
			
			$occupancyTypeRate = (float)1.5;
			$resiCommTypeRate = (float)0.0015;
			$matrixFactorRate = 1;
			$cv2022 = $cvr2022*$inputs['hoarding_area']*$occupancyTypeRate*$resiCommTypeRate*$matrixFactorRate;
			// end capital value calc rate
			
			$floorDtlArr[] = [
				'type'=>'hoarding',
				'area_type'=>"Total Area of Wall / Roof / Land (in Sq. Ft.)",
				'floor_mstr_id'=>0,
				'usage_type_mstr_id'=>0,
				'occupancy_type_mstr_id'=>0,
				'const_type_mstr_id'=>1,
				'builtup_area'=>$inputs['hoarding_area'],
				'carper_area'=>$carperArea,
				'date_from'=>$date_from,
				'date_upto'=>"",
				'fyear'=>$fy,
				'qtr'=>$qtr,
				'upto_fy_mstr_id'=>0,
				'upto_qtr'=>0,
				'old_arv'=>0,
				'new_arv'=>$new_arv,
				'cv'=>$cv2022,
				'operator'=>'+',
				'old_arv_cal_method'=>null,
				'new_arv_cal_method'=>['usage_factor'=>1.5, 'occupancy_factor'=>1.5, 'rental_rate'=>$mrr, 'carper_area'=>$carperArea],
				'cv_2022_cal_method'=>['cvr'=>$cvr2022, 'buildup_area'=>$inputs['hoarding_area'], 'occupancy_rate'=>$occupancyTypeRate, 'resi_comm_type_rate'=>$resiCommTypeRate, 'matrix_factor_rate'=>$matrixFactorRate]
			];
		}

		if($inputs['is_petrol_pump']==1 && $inputs['prop_type_mstr_id']!=4){
			if(date("Y-m-01", strtotime($inputs['petrol_pump_completion_date'])) > "2016-04-01") {
				$date_from = date("Y-m", strtotime($inputs['petrol_pump_completion_date']));
				$fy = getFY($inputs['petrol_pump_completion_date']);
				$qtr = $this->getQtr($date_from);
			} else {
				$date_from = "2016-04";
				$fy = "2016-2017";
				$qtr = 1;
			}

			// new calc rate
			$sendInput = ['road_type_mstr_id'=>$inputs['road_type_mstr_id'], 'const_type_mstr_id'=>1, 'date_of_effect'=>'2016-04-01'];
			$mrr = $this->model_arr_building_mstr->getMRRCalRate($sendInput)['cal_rate'];
			//$carperArea = (($inputs['under_ground_area']*80)/100);
			$carperArea = $inputs['under_ground_area'];
			$new_arv = 2.25*$carperArea*$mrr; //echo "1.5 * 1.5 * $carperArea * $mrr = ".$new_arv. >> petrol arv;
			// end new calc rate
			//---------------------------------------------------------//
			// capital value calc rate			
			$property_type = "BUILDING_PAKKA";
			//$ward_mstr_id = $inputs['new_ward_mstr_id'];
			$road_type_mstr_id = ($inputs['road_type_mstr_id']=="1")?1:0;
			$usage_type = "COMMERCIAL";
			$cvr2022  = $this->model_capital_value_rate->getCVR2022($property_type, $ward_no, $road_type_mstr_id, $usage_type)["rate"];
			
			$occupancyTypeRate = (float)1.5;
			$resiCommTypeRate = (float)0.0015;
			$matrixFactorRate = 1;
			$cv2022 = $cvr2022*$inputs['under_ground_area']*$occupancyTypeRate*$resiCommTypeRate*$matrixFactorRate;
			// end capital value calc rate
			
			$floorDtlArr[] = [
				'type'=>'petrol',
				'area_type'=>"Underground Storage Area",
				'floor_mstr_id'=>0,
				'usage_type_mstr_id'=>0,
				'occupancy_type_mstr_id'=>0,
				'const_type_mstr_id'=>1,
				'builtup_area'=>$inputs['under_ground_area'],
				'carper_area'=>$carperArea,
				'date_from'=>$date_from,
				'date_upto'=>"",
				'fyear'=>$fy,
				'qtr'=>$qtr,
				'upto_fy_mstr_id'=>0,
				'upto_qtr'=>0,
				'old_arv'=>0,
				'new_arv'=>$new_arv,
				'cv'=>$cv2022,
				'operator'=>'+',
				'old_arv_cal_method'=>null,
				'new_arv_cal_method'=>['usage_factor'=>1.5, 'occupancy_factor'=>1.5, 'rental_rate'=>$mrr, 'carper_area'=>$carperArea],
				'cv_2022_cal_method'=>['cvr'=>$cvr2022, 'buildup_area'=>$inputs['under_ground_area'], 'occupancy_rate'=>$occupancyTypeRate, 'resi_comm_type_rate'=>$resiCommTypeRate, 'matrix_factor_rate'=>$matrixFactorRate]
			];
		}

		usort($floorDtlArr, 'floor_date_compare');
		return $floorDtlArr;
	}

	public function makeVacantWithNoRoadFloorDtlArr($inputs) {
		$vacantDtlArr = [];
		$vacantDtlArrIndex = 0;
		$VL_EffectedFrom = "2016-04-01";
		$VL_EffectedFromFY = "2016-2017";
		$VL_EffectedFromQtr = 1;
		if ($inputs["land_occupation_date"] > "2016-04-01") {
			$VL_EffectedFrom = $inputs["land_occupation_date"];
			$VL_EffectedFromFY = getFY($VL_EffectedFrom);
			$VL_EffectedFromQtr = (int)$this->getQtr($VL_EffectedFrom);
		}
		
		// old rule calc rate	
		$sendInput = ['road_type_mstr_id'=>$inputs['road_type_mstr_id'], 'date_of_effect'=>"2016-04-01"];
		$vacantLandRate = $this->model_arr_vacant_mstr->getMRRCalRate($sendInput)['rate'];
		// end old rule calc rate	

		// new rule calc rate	
		$road_type_mstr_id = ($inputs['road_type_mstr_id']=="1" || $inputs['road_type_mstr_id']=="4")?$inputs['road_type_mstr_id']:0;

		$sendInput = ['road_type_mstr_id'=>$road_type_mstr_id, 'date_of_effect'=>"2022-04-01"];
		$vacantLandNewRate = $this->model_arr_new_vacant_mstr->getNewMRRCalRate($sendInput)['rate'];
		// end new rule calc rate	
		
		$mobileHoardingDtl = [];
		
		$vacantlandAreaINDecimal = $inputs['area_of_plot'];
		$vacantlandAreaINSqMeter = ($inputs['area_of_plot']*40.5); // convert decimal to sqm
		$vacantlandAreaINSqFt = ($inputs['area_of_plot']*435.6); // convert decimal to sqft
		//$yearly_tax = $vacantlandAreaINSqMeter*$vacantLandRate;
		//$yearly_new_tax = $vacantlandAreaINSqMeter*$vacantLandNewRate; // as per CV rule

		$vacantlandAreaINAcre = $vacantlandAreaINDecimal/100;
		$yearly_tax = $vacantlandAreaINAcre*$vacantLandRate;
		$yearly_new_tax = $vacantlandAreaINAcre*$vacantLandNewRate;

		$vacantDtlArr[$vacantDtlArrIndex] = [
			"type"=>"Vacant Land",
			"effected_from"=>$VL_EffectedFrom,
			"area_decimal"=>$vacantlandAreaINDecimal,
			"area_sqm"=>$vacantlandAreaINSqMeter,
			"area_sqft"=>$vacantlandAreaINSqFt,
			"fyear"=>$VL_EffectedFromFY,
			"qtr"=>$VL_EffectedFromQtr,
			'arv'=>0,
			'yearly_tax'=>$yearly_tax,
			'yearly_cv_tax'=>$yearly_new_tax,
			'new_tax_cal_method'=>["area_in_sqm"=>$vacantlandAreaINAcre, "vacant_land_rate"=>$vacantLandRate, "occupancy_factor"=>1],
			'cv_2022_cal_method'=>["area_in_sqm"=>$vacantlandAreaINAcre, "vacant_land_rate"=>$vacantLandNewRate, "occupancy_factor"=>1]
		];
		$vacantDtlArrIndex++;
		
		$VMHDtlArr = $vacantDtlArr;
		usort($VMHDtlArr, 'vacant_date_compare2');
		//print_var($VMHDtlArr);
		return $VMHDtlArr;
	}

	public function makeVacantFloorDtlArr($inputs) {
		$ward_no = $this->db->table("view_ward_mstr")
							->select("split_part(split_part(ward_no, 'A', 1), '/', 1) AS ward_no")
							->where("id", $inputs['ward_mstr_id'])
							->get()
            				->getFirstRow("array")["ward_no"];

		$vacantDtlArr = [];
		$vacantDtlArrIndex = 0;
		$VL_EffectedFrom = "2016-04-01";
		$VL_EffectedFromFY = "2016-2017";
		$VL_EffectedFromQtr = 1;
		if ($inputs["land_occupation_date"] > "2016-04-01") {
			$VL_EffectedFrom = $inputs["land_occupation_date"];
			$VL_EffectedFromFY = getFY($VL_EffectedFrom);
			$VL_EffectedFromQtr = (int)$this->getQtr($VL_EffectedFrom);
		}
		
		// old rule calc rate	
		$sendInput = ['road_type_mstr_id'=>$inputs['road_type_mstr_id'], 'date_of_effect'=>"2016-04-01"];
		$vacantLandRate = $this->model_arr_vacant_mstr->getMRRCalRate($sendInput)['rate'];
		// end old rule calc rate	

		// new rule calc rate	
		$road_type_mstr_id = ($inputs['road_type_mstr_id']=="1")?1:0;
		$sendInput = ['road_type_mstr_id'=>$road_type_mstr_id, 'date_of_effect'=>"2022-04-01"];
		$vacantLandNewRate = $this->model_arr_new_vacant_mstr->getNewMRRCalRate($sendInput)['rate'];
		// end new rule calc rate	
		// capital value calc rate			
		$MH_property_type = "BUILDING_PAKKA";
		$MH_usage_type = "COMMERCIAL";
		$MH_cvr2022  = $this->model_capital_value_rate->getCVR2022($MH_property_type, $ward_no, $road_type_mstr_id, $MH_usage_type)["rate"];
		
		$mobileHoardingDtl = [];
		$MT_EffectedFrom = NULL;
		$MT_EffectedFromFY = NULL;
		$MT_EffectedFromQtr = NULL;
		if ($inputs['is_mobile_tower']==1) {
			if ($inputs["tower_installation_date"] > $VL_EffectedFrom) {
				$MT_EffectedFrom = $inputs["tower_installation_date"];
				$MT_EffectedFromFY = getFY($MT_EffectedFrom);
				$MT_EffectedFromQtr = (int)$this->getQtr($MT_EffectedFrom);
			} else {
				$MT_EffectedFrom = $VL_EffectedFrom;
				$MT_EffectedFromFY = $VL_EffectedFromFY;
				$MT_EffectedFromQtr = (int)$VL_EffectedFromQtr;
			}
			$areaInSqm = round(($inputs['tower_area']*0.092903), 2); // convert sqft to sqm;
			$yearly_tax = $areaInSqm*$vacantLandRate*1.5; // as per old rule
			//$yearly_new_tax = $areaInSqm*$vacantLandNewRate*1.5; // as per CV rule

			$occupancyTypeRate = (float)1.5;
			$resiCommTypeRate = (float)0.0015;
			$matrixFactorRate = 1;
			$yearly_new_tax = $MH_cvr2022*$inputs['tower_area']*$occupancyTypeRate*$resiCommTypeRate*$matrixFactorRate;

			$mobileHoardingDtl[] = [
					"type"=>"MOBILE TOWER",
					"effected_from"=>$MT_EffectedFrom,
					"area_sqm"=>$areaInSqm,
					"area_sqft"=>$inputs['tower_area'],
					"fyear"=>$MT_EffectedFromFY,
					"qtr"=>$MT_EffectedFromQtr,
					'arv'=>0,
					'yearly_tax'=>$yearly_tax,
					'yearly_cv_tax'=>$yearly_new_tax,
					'new_tax_cal_method'=>["area_in_sqm"=>$areaInSqm, "vacant_land_rate"=>$vacantLandRate, "occupancy_factor"=>1.5],
					'cv_2022_cal_method'=>['cvr'=>$MH_cvr2022, 'buildup_area'=>$inputs['tower_area'], 'occupancy_rate'=>$occupancyTypeRate, 'resi_comm_type_rate'=>$resiCommTypeRate, 'matrix_factor_rate'=>$matrixFactorRate]
				];
		}
		
		$HB_EffectedFrom = NULL;
		$HB_EffectedFromFY = NULL;
		$HB_EffectedFromQtr = NULL;
		if ($inputs['is_hoarding_board']==1) {
			if ($inputs["hoarding_installation_date"] > $VL_EffectedFrom) {
				$HB_EffectedFrom = $inputs["hoarding_installation_date"];
				$HB_EffectedFromFY = getFY($HB_EffectedFrom);
				$HB_EffectedFromQtr = $this->getQtr($HB_EffectedFrom);
			} else {
				$HB_EffectedFrom = $VL_EffectedFrom;
				$HB_EffectedFromFY = $VL_EffectedFromFY;
				$HB_EffectedFromQtr = $VL_EffectedFromQtr;
			}
			
			$areaInSqm = round(($inputs['hoarding_area']*0.092903), 2); // convert sqft to sqm;
			$yearly_tax = $areaInSqm*$vacantLandRate*1.5; // as per old rule
			
			$occupancyTypeRate = (float)1.5;
			$resiCommTypeRate = (float)0.0015;
			$matrixFactorRate = 1;
			$yearly_new_tax = $MH_cvr2022*$inputs['hoarding_area']*$occupancyTypeRate*$resiCommTypeRate*$matrixFactorRate;

			$mobileHoardingDtl[] = [
									"type"=>"HOARDING BOARD",
									"effected_from"=>$HB_EffectedFrom,
									"area_sqm"=>$areaInSqm,
									"area_sqft"=>$inputs['hoarding_area'],
									"fyear"=>$HB_EffectedFromFY,
									"qtr"=>$HB_EffectedFromQtr,
									'arv'=>0,
									'yearly_tax'=>$yearly_tax,
									'yearly_cv_tax'=>$yearly_new_tax,
									'new_tax_cal_method'=>["area_in_sqm"=>$areaInSqm, "vacant_land_rate"=>$vacantLandRate, "occupancy_factor"=>1.5],
									'cv_2022_cal_method'=>['cvr'=>$MH_cvr2022, 'buildup_area'=>$inputs['hoarding_area'], 'occupancy_rate'=>$occupancyTypeRate, 'resi_comm_type_rate'=>$resiCommTypeRate, 'matrix_factor_rate'=>$matrixFactorRate]
								];
		}
		if (!empty($mobileHoardingDtl)) {
			usort($mobileHoardingDtl, 'vacant_date_compare2');
		}
		
		$vacantlandAreaINDecimal = $inputs['area_of_plot'];
		$vacantlandAreaINSqMeter = ($inputs['area_of_plot']*40.5); // convert decimal to sqm
		$vacantlandAreaINSqFt = ($inputs['area_of_plot']*435.6); // convert decimal to sqft
		if (!empty($mobileHoardingDtl)) {
			foreach ($mobileHoardingDtl as $key => $MHDtl) {
				if ($MHDtl["fyear"]==$VL_EffectedFromFY && $MHDtl["qtr"]==$VL_EffectedFromQtr) {
					$vacantlandAreaINDecimal -= $MHDtl["area_sqft"]/435.6;
					$vacantlandAreaINSqMeter -= $MHDtl["area_sqm"];
					$vacantlandAreaINSqFt -= $MHDtl["area_sqft"];
				}
			}
		}
		$yearly_tax = $vacantlandAreaINSqMeter*$vacantLandRate;
		$yearly_new_tax = $vacantlandAreaINSqMeter*$vacantLandNewRate; // as per CV rule
		$vacantDtlArr[$vacantDtlArrIndex] = [
			"type"=>"Vacant Land",
			"effected_from"=>$VL_EffectedFrom,
			"area_decimal"=>$vacantlandAreaINDecimal,
			"area_sqm"=>$vacantlandAreaINSqMeter,
			"area_sqft"=>$vacantlandAreaINSqFt,
			"fyear"=>$VL_EffectedFromFY,
			"qtr"=>$VL_EffectedFromQtr,
			'arv'=>0,
			'yearly_tax'=>$yearly_tax,
			'yearly_cv_tax'=>$yearly_new_tax,
			'new_tax_cal_method'=>["area_in_sqm"=>$vacantlandAreaINSqMeter, "vacant_land_rate"=>$vacantLandRate, "occupancy_factor"=>1],
			'cv_2022_cal_method'=>["area_in_sqm"=>$vacantlandAreaINSqMeter, "vacant_land_rate"=>$vacantLandNewRate, "occupancy_factor"=>1]
		];
		$vacantDtlArrIndex++;
		/* print_var($vacantDtlArr);
		echo "1111>"; */
		foreach ($mobileHoardingDtl as $key => $MHDtl) {
			/* print_var($MHDtl);
				echo $key.">"; */
			if ($MHDtl["fyear"]!=$vacantDtlArr[sizeof($vacantDtlArr)-1]["fyear"] || $MHDtl["qtr"]!=$vacantDtlArr[sizeof($vacantDtlArr)-1]["qtr"]) {
				
				$vacantlandAreaINSqMeter -= $MHDtl["area_sqm"];
				$vacantlandAreaINDecimal -= $MHDtl["area_sqft"]/435.6;

				$yearly_tax = $vacantlandAreaINSqMeter*$vacantLandRate;
				$yearly_new_tax = $vacantlandAreaINSqMeter*$vacantLandNewRate; // as per CV rule
				$vacantDtlArr[$vacantDtlArrIndex] = [
					"type"=>"Vacant Land",
					"effected_from"=>$MHDtl["effected_from"],
					"area_decimal"=>$vacantlandAreaINDecimal,
					"area_sqm"=>$vacantlandAreaINSqMeter,
					"area_sqft"=>$vacantlandAreaINSqFt,
					"fyear"=>$MHDtl["fyear"],
					"qtr"=>$MHDtl["qtr"],
					'arv'=>0,
					'yearly_tax'=>$yearly_tax,
					'yearly_cv_tax'=>$yearly_new_tax,
					'new_tax_cal_method'=>["area_in_sqm"=>$vacantlandAreaINSqMeter, "vacant_land_rate"=>$vacantLandRate, "occupancy_factor"=>1],
					'cv_2022_cal_method'=>["area_in_sqm"=>$vacantlandAreaINSqMeter, "vacant_land_rate"=>$vacantLandNewRate, "occupancy_factor"=>1]
				];
				$vacantDtlArrIndex++;
			}
		}
		$VMHDtlArr = $vacantDtlArr;
		foreach ($mobileHoardingDtl as $key => $MHDtl) {
			$VMHDtlArr[] = $MHDtl;
		}
		usort($VMHDtlArr, 'vacant_date_compare2');
		//print_var($VMHDtlArr);
		return $VMHDtlArr;
	}

	public function calVacantTaxDtl($vacantDtlArr) {
		/* print_var($vacantDtlArr);
		die(); */
		$lastVacantKey = 0;
		foreach ($vacantDtlArr as $key => $value) {
			if ($value["type"]=="Vacant Land") {
				$lastVacantKey = $key;
			}
		}
		$safTaxDtl = [];
		$taxCount = (int)0;

		$new_rule_arv_sub = [];
		$cv_rule_arv_sub = [];
		$cv_vacant_land_dtl = [];
		foreach ($vacantDtlArr AS $key=>$arvDtl) {
			/* if ($arvDtl["type"]=="Vacant Land") {
				$cv_vacant_land_dtl = $arvDtl;
			} */
			$yyyyMMDDFloor = date("Y-m", strtotime($arvDtl['effected_from']))."-01";

			if ("2022-04-01" > $yyyyMMDDFloor) {
				$new_rule_arv_sub[] = [
					"type"=>$arvDtl['type'],
					"effected_from"=>$arvDtl['effected_from'],
					"fyear"=>$arvDtl['fyear'],
					"qtr"=>$arvDtl['qtr'],
					"yearly_tax"=>$arvDtl['yearly_tax'],
					"area_sqm"=>$arvDtl["new_tax_cal_method"]['area_in_sqm'],
					"vacant_land_rate"=>$arvDtl["new_tax_cal_method"]['vacant_land_rate'],
					"occupancy_factor"=>$arvDtl["new_tax_cal_method"]['occupancy_factor'],
					"operator"=>"+"
				];
				if ($key==$lastVacantKey && $arvDtl['type']=="Vacant Land") {
					$cv_rule_arv_sub[] = [
						"type"=>$arvDtl['type'],
						"effected_from"=>"2022-06-30",
						"fyear"=>"2022-2023",
						"qtr"=>1,
						"yearly_cv_tax"=>$arvDtl['yearly_cv_tax'],
						"area_sqm"=>$arvDtl["cv_2022_cal_method"]['area_in_sqm'],
						"vacant_land_rate"=>$arvDtl["cv_2022_cal_method"]['vacant_land_rate'],
						"occupancy_factor"=>$arvDtl["cv_2022_cal_method"]['occupancy_factor'],
						"operator"=>"+"
					];
				} else if ($arvDtl['type']!="Vacant Land") {
					$cv_rule_arv_sub[] = [
						"type"=>$arvDtl['type'],
						"effected_from"=>"2022-06-30",
						"fyear"=>"2022-2023",
						"qtr"=>1,
						"yearly_cv_tax"=>$arvDtl['yearly_cv_tax'],
						"cvr"=>$arvDtl["cv_2022_cal_method"]['cvr'],
						"area_sqm"=>$arvDtl["cv_2022_cal_method"]['buildup_area'],
						"resi_comm_type_rate"=>$arvDtl["cv_2022_cal_method"]['resi_comm_type_rate'],
						"occupancy_factor"=>$arvDtl["cv_2022_cal_method"]['occupancy_rate'],
						"matrix_factor_rate"=>$arvDtl["cv_2022_cal_method"]['matrix_factor_rate'],
						"operator"=>"+"
					];
				}
				
			} else if ($yyyyMMDDFloor >= "2022-04-01") {
				if ($key==$lastVacantKey && $arvDtl['type']=="Vacant Land") {
					$cv_rule_arv_sub[] = [
						"type"=>$arvDtl['type'],
						"effected_from"=>$this->makeDueDateByFyearQtr($arvDtl['fyear'], $arvDtl['qtr']),
						"fyear"=>"2022-2023",
						"qtr"=>1,
						"yearly_cv_tax"=>$arvDtl['yearly_cv_tax'],
						"area_sqm"=>$arvDtl["cv_2022_cal_method"]['area_in_sqm'],
						"vacant_land_rate"=>$arvDtl["cv_2022_cal_method"]['vacant_land_rate'],
						"occupancy_factor"=>$arvDtl["cv_2022_cal_method"]['occupancy_factor'],
						"operator"=>"+"
					];
				} else if ($arvDtl['type']!="Vacant Land") {
					$cv_rule_arv_sub[] = [
						"type"=>$arvDtl['type'],
						"effected_from"=>$this->makeDueDateByFyearQtr($arvDtl['fyear'], $arvDtl['qtr']),
						"fyear"=>$arvDtl['fyear'],
						"qtr"=>$arvDtl['qtr'],
						"yearly_cv_tax"=>$arvDtl['yearly_cv_tax'],
						"cvr"=>$arvDtl["cv_2022_cal_method"]['cvr'],
						"area_sqm"=>$arvDtl["cv_2022_cal_method"]['buildup_area'],
						"resi_comm_type_rate"=>$arvDtl["cv_2022_cal_method"]['resi_comm_type_rate'],
						"occupancy_factor"=>$arvDtl["cv_2022_cal_method"]['occupancy_rate'],
						"matrix_factor_rate"=>$arvDtl["cv_2022_cal_method"]['matrix_factor_rate'],
						"operator"=>"+"
					];
				}
			}
		}

		/* print_var($cv_rule_arv_sub);
		die(); */
		
		$grouped_array = array();
		foreach ($new_rule_arv_sub as $element) {
			$grouped_array[$element['effected_from']][] = $element;
		}
		
		foreach ($grouped_array AS $key=>$new_rule_arv_sub2) {
			$effectQtr = (int)0;
			$effectFy = "";
			$new_arv_total = 0;
			foreach ($new_rule_arv_sub2 AS $key=>$arvDtl) {
				$new_arv_total += $arvDtl["yearly_tax"];
				$quarterly_tax = round((($new_arv_total)/4), 2);
				if ($arvDtl['qtr']!=$effectQtr || $arvDtl['fyear']!=$effectFy) {
					$effectQtr = (int)$arvDtl['qtr']; $effectFy = $arvDtl['fyear'];
					$safTaxDtl[$taxCount++] = ["rule_type"=>"NEW_RULE", "effected_from"=>$arvDtl['effected_from'], "fyear"=>$effectFy, "qtr"=>$effectQtr, "arv"=>$new_arv_total, "yearly_tax"=>$new_arv_total, "holding_tax"=>$quarterly_tax, "quarterly_tax"=>$quarterly_tax, "additional_tax"=>0];
				} else {
					$safTaxDtl[$taxCount-1] = ["rule_type"=>"NEW_RULE", "effected_from"=>$arvDtl['effected_from'], "fyear"=>$effectFy, "qtr"=>$effectQtr, "arv"=>$new_arv_total, "yearly_tax"=>$new_arv_total, "holding_tax"=>$quarterly_tax, "quarterly_tax"=>$quarterly_tax, "additional_tax"=>0];
				}
			}
		}
		

		
		$grouped_array = array();
		foreach ($cv_rule_arv_sub as $element) {
			$grouped_array[$element['effected_from']][] = $element;
		}
		
		foreach ($grouped_array AS $key=>$cv_rule_arv_sub2) {
			$effectQtr = (int)0;
			$effectFy = "";
			$new_arv_total = 0;
			foreach ($cv_rule_arv_sub2 AS $key=>$arvDtl) {
				$new_arv_total += $arvDtl["yearly_cv_tax"];
				$quarterly_tax = round((($new_arv_total)/4), 2);
				if ($arvDtl['qtr']!=$effectQtr || $arvDtl['fyear']!=$effectFy) {
					$effectQtr = (int)$arvDtl['qtr']; $effectFy = $arvDtl['fyear'];
					$safTaxDtl[$taxCount++] = ["rule_type"=>"CV_RULE", "effected_from"=>$arvDtl['effected_from'], "fyear"=>$effectFy, "qtr"=>$effectQtr, "arv"=>$new_arv_total, "yearly_tax"=>$new_arv_total, "holding_tax"=>$quarterly_tax, "quarterly_tax"=>$quarterly_tax, "additional_tax"=>0];
				} else {
					$safTaxDtl[$taxCount-1] = ["rule_type"=>"CV_RULE", "effected_from"=>$arvDtl['effected_from'], "fyear"=>$effectFy, "qtr"=>$effectQtr, "arv"=>$new_arv_total, "yearly_tax"=>$new_arv_total, "holding_tax"=>$quarterly_tax, "quarterly_tax"=>$quarterly_tax, "additional_tax"=>0];
				}
			}
		}		
		return [$safTaxDtl, $new_rule_arv_sub, $cv_rule_arv_sub, $cv_vacant_land_dtl];
	}

	public function calBuildingTaxDtl($floorDtlArr, $prop_type_mstr_id, $isAdditionaTaxImplemented) {
		$old_rule_arv_sub = [];
        $new_rule_arv_sub = [];
		$cv_rule_arv_sub = [];
		$vacantLandAdded = 0;
		// cal sub old/new rule arv
		foreach ($floorDtlArr as $key => $floorDtl) {
			$yyyyMMDDFloor = $floorDtl['date_from']."-01";
			$date_upto_floor = ($floorDtl['date_upto']=="")?"":$floorDtl['date_upto']."-01";
			
			if ("2016-04-01" > $yyyyMMDDFloor) {
				if ($prop_type_mstr_id!=1 && $prop_type_mstr_id!=5) {
					$old_rule_arv_sub[] = [
										"fyear"=>$floorDtl['fyear'],
										"qtr"=>$floorDtl['qtr'],
										"arv"=>$floorDtl['old_arv'],
										"operator"=>$floorDtl['operator']
									];
				}
				if ($date_upto_floor=="" || $date_upto_floor>="2016-04-01") {
					$new_rule_arv_sub[] = [
										"fyear"=>"2016-2017",
										"qtr"=>1,
										"arv"=>$floorDtl['new_arv'],
										"operator"=>$floorDtl['operator']
									];
				}
				if ($date_upto_floor=="" || $date_upto_floor>="2022-04-01") {
					$cv_rule_arv_sub[] = [
									"fyear"=>"2022-2023",
									"qtr"=>1,
									"arv"=>$floorDtl['cv'],
									"operator"=>$floorDtl['operator']
								];
				}
			} else if ($yyyyMMDDFloor >= "2016-04-01" && "2022-04-01" > $yyyyMMDDFloor) {
				$new_rule_arv_sub[] = [
										"fyear"=>$floorDtl['fyear'],
										"qtr"=>$floorDtl['qtr'],
										"arv"=>$floorDtl['new_arv'],
										"operator"=>$floorDtl['operator']
									];
				if ($date_upto_floor=="" || $date_upto_floor>="2022-04-01") {
					$cv_rule_arv_sub[] = [
										"fyear"=>"2022-2023",
										"qtr"=>1,
										"arv"=>$floorDtl['cv'],
										"operator"=>$floorDtl['operator']
									];
				}
			} else if ($yyyyMMDDFloor >= "2022-04-01") {
				if ($floorDtl['type']=="vacant" && $vacantLandAdded==0) {
					$vacantLandAdded++;
					$cv_rule_arv_sub[] = [
									"VACANT_TYPE"=>"VACANT",
									"fyear"=>$floorDtl['fyear'],
									"qtr"=>$floorDtl['qtr'],
									"arv"=>$floorDtl['cv'],
									"operator"=>$floorDtl['operator']
								];
				} else {
					$cv_rule_arv_sub[] = [
											"fyear"=>$floorDtl['fyear'],
											"qtr"=>$floorDtl['qtr'],
											"arv"=>$floorDtl['cv'],
											"operator"=>$floorDtl['operator']
										];
				}
			}
		}

		$effectQtr = (int)0;
		$effectFy = "";
		$old_arv_total = 0;
		$safTaxDtl = [];
		$taxCount = (int)0;
		foreach ($old_rule_arv_sub AS $arvDtl) {
			if ( $arvDtl['operator']=="+" ) {
				$old_arv_total += $arvDtl["arv"];
			} else if ( $arvDtl['operator']=="-" ) {
				$old_arv_total -= $arvDtl["arv"];
			}
			$holding_tax = 0;
			$water_tax = 0;
			$education_cess = 0;
			$health_cess = 0;
			$latrine_tax = 0;
			$holding_tax = $old_arv_total*0.125;
			if ($holding_tax>0) $holding_tax = round(($holding_tax/4), 2);

			$water_tax = $old_arv_total*0.075;
			if ($water_tax>0) $water_tax = round(($water_tax/4), 2);

			$education_cess = $old_arv_total*0.05;
			if ($education_cess>0) $education_cess = round(($education_cess/4), 2);

			$health_cess = $old_arv_total*0.0625;
			if ($health_cess>0) $health_cess = round(($health_cess/4), 2);

			$latrine_tax = $old_arv_total*0.075;
			if ($latrine_tax>0) $latrine_tax = round(($latrine_tax/4), 2);
			
			$quarterly_tax = round(($holding_tax+$water_tax+$education_cess+$health_cess+$latrine_tax), 2);

				if ($arvDtl['qtr']!=$effectQtr || $arvDtl['fyear']!=$effectFy) {
					$effectQtr = (int)$arvDtl['qtr']; $effectFy = $arvDtl['fyear'];
					if ($quarterly_tax > 0) {
						$safTaxDtl[$taxCount++] = ["rule_type"=>"OLD_RULE", "fyear"=>$effectFy, "qtr"=>$effectQtr, "arv"=>$old_arv_total, "holding_tax"=>$holding_tax, "water_tax"=>$water_tax, "education_cess"=>$education_cess, "health_cess"=>$health_cess, "latrine_tax"=>$latrine_tax, "additional_tax"=>0, "quarterly_tax"=>$quarterly_tax];
					}
				} else {
					$safTaxDtl[$taxCount-1] = ["rule_type"=>"OLD_RULE","fyear"=>$effectFy, "qtr"=>$effectQtr, "arv"=>$old_arv_total, "holding_tax"=>$holding_tax, "water_tax"=>$water_tax, "education_cess"=>$education_cess, "health_cess"=>$health_cess, "latrine_tax"=>$latrine_tax, "additional_tax"=>0, "quarterly_tax"=>$quarterly_tax];
				}
		}
		$effectQtr = (int)0;
		$effectFy = "";
		$new_arv_total = 0;
		foreach ($new_rule_arv_sub AS $key=>$arvDtl) {
			if ( $arvDtl['operator']=="+" ) {
				$new_arv_total += $arvDtl["arv"];
			} else if ( $arvDtl['operator']=="-" ) {
				$new_arv_total -= $arvDtl["arv"];
			}
			$holding_tax = round((($new_arv_total*0.02)/4), 2);
			$additional_tax = 0;
			if ($isAdditionaTaxImplemented==TRUE) {
				$additional_tax = ($new_arv_total*0.02)/4;
				if ($additional_tax > 0)
					$additional_tax = round((($additional_tax*1.5)-$additional_tax), 2);
			}
			$quarterly_tax = $holding_tax+$additional_tax;
				if ($arvDtl['qtr']!=$effectQtr || $arvDtl['fyear']!=$effectFy) {
					$effectQtr = (int)$arvDtl['qtr']; $effectFy = $arvDtl['fyear'];
					$safTaxDtl[$taxCount++] = ["rule_type"=>"NEW_RULE", "fyear"=>$effectFy, "qtr"=>$effectQtr, "arv"=>$new_arv_total, "holding_tax"=>$holding_tax, "additional_tax"=>$additional_tax, "quarterly_tax"=>$quarterly_tax];
				} else {
					$safTaxDtl[$taxCount-1] = ["rule_type"=>"NEW_RULE", "fyear"=>$effectFy, "qtr"=>$effectQtr, "arv"=>$new_arv_total, "holding_tax"=>$holding_tax, "additional_tax"=>$additional_tax, "quarterly_tax"=>$quarterly_tax];
				}
		}
		$effectQtr = (int)0;
		$effectFy = "";
		$cv_arv_total = 0;
		foreach ($cv_rule_arv_sub AS $key=>$arvDtl) {
			if ( $arvDtl['operator']=="+" ) {
				$cv_arv_total += $arvDtl["arv"];
			} else if ( $arvDtl['operator']=="-" ) {
				$cv_arv_total -= $arvDtl["arv"];
			}
			$holding_tax = round(($cv_arv_total/4), 2);
			$additional_tax = 0;
			if ($isAdditionaTaxImplemented==TRUE) {
				$additional_tax = $cv_arv_total/4;
				if ($additional_tax > 0)
					$additional_tax = round((($additional_tax*1.5)-$additional_tax), 2);
			}
			$quarterly_tax = $holding_tax+$additional_tax;
				if ($arvDtl['qtr']!=$effectQtr || $arvDtl['fyear']!=$effectFy) {
					$effectQtr = (int)$arvDtl['qtr']; $effectFy = $arvDtl['fyear'];
					$safTaxDtl[$taxCount++] = ["rule_type"=>"CV_RULE", "fyear"=>$effectFy, "qtr"=>$effectQtr, "arv"=>$cv_arv_total, "holding_tax"=>$holding_tax, "additional_tax"=>$additional_tax, "quarterly_tax"=>$quarterly_tax];
				} else {
					$safTaxDtl[$taxCount-1] = ["rule_type"=>"CV_RULE", "fyear"=>$effectFy, "qtr"=>$effectQtr, "arv"=>$cv_arv_total, "holding_tax"=>$holding_tax, "additional_tax"=>$additional_tax, "quarterly_tax"=>$quarterly_tax];
				}
		}
		return [$safTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub];
	}

	public function calSafDemand($safTaxDtl, $saf_dtl_id, $prev_prop_dtl_id) {
		$currentFY = getFY();
		$currentFY = "2022-2023";

		// get prev saf dtl
		$p_saf_dtl_id = 0;
		$mutationReassessmentLastDueDate = "";
		if ($prev_prop_dtl_id) {
			$sql = "SELECT saf_dtl_id FROM tbl_prop_dtl WHERE id=".$prev_prop_dtl_id;
			$p_saf_dtl = $this->db->query($sql);
			$p_saf_dtl = $p_saf_dtl->getFirstRow("array");
			$p_saf_dtl_id = ($p_saf_dtl["saf_dtl_id"]=="")?0:$p_saf_dtl["saf_dtl_id"];
			if($prevPropDemandDtl = $this->model_prop_demand->getLastGeneratedPropDemand($prev_prop_dtl_id, $p_saf_dtl_id)) {
				$mutationReassessmentLastDueDate = $prevPropDemandDtl["due_date"];
			}
			//die();
		}

		
		// get prev saf dtl end

		$demandDtl = [];
		foreach ($safTaxDtl AS $key=>$taxDtl) {
			$pymt_frm_qtr = (int)$taxDtl['qtr'];
			$pymt_frm_year = (String)$taxDtl['fyear'];

			$pymt_upto_qtr = (int)4;
			$pymt_upto_year = (String)$currentFY;
			if ($key < sizeof($safTaxDtl)-1) {
				$pymt_upto_qtr = (int)$safTaxDtl[$key+1]['qtr']-1;
				$pymt_upto_year = (String)$safTaxDtl[$key+1]['fyear'];
			}
			list($from_y1_new, $from_y2_new) = explode("-", $pymt_frm_year);
			list($upto_y1_new, $upto_y2_new) = explode("-", $pymt_upto_year);

			if ($taxDtl['arv']>=0) {
			// tax insert code 
				$arv = $holding_tax = $water_tax = $education_cess = $health_cess = $latrine_tax = $additional_tax = $quarterly_tax = 0;
				if ($taxDtl["rule_type"]=="OLD_RULE") {
					$arv = $taxDtl["arv"];
					$holding_tax = $taxDtl["holding_tax"];
					$water_tax = $taxDtl["water_tax"];
					$education_cess = $taxDtl["education_cess"];
					$health_cess = $taxDtl["health_cess"];
					$latrine_tax = $taxDtl["latrine_tax"];
					$additional_tax = $taxDtl["additional_tax"];
					$quarterly_tax = $taxDtl["quarterly_tax"];
				} else if ($taxDtl["rule_type"]!="OLD_RULE") {
					$arv = $taxDtl["arv"];
					$holding_tax = $taxDtl["holding_tax"];
					$additional_tax = $taxDtl["additional_tax"];
					$quarterly_tax = $taxDtl["quarterly_tax"];
				}
				$input = [
					'saf_dtl_id'=>$saf_dtl_id, 'fyear'=>$taxDtl['fyear'], 'qtr'=>$taxDtl['qtr'], 'arv'=>$arv, 'holding_tax'=>$holding_tax, 'water_tax'=>$water_tax, 'education_cess'=>$education_cess, 'health_cess'=>$health_cess, 'latrine_tax'=>$latrine_tax, 'quarterly_tax'=>$quarterly_tax, 'created_on'=>date("Y-m-d H:i:s"), 'status'=>1
				];
				//$saf_tax_id = $this->model_saf_tax->insertData($input);
				$sql = "INSERT INTO tbl_saf_tax (saf_dtl_id, fyear, qtr, arv, holding_tax, water_tax, education_cess, health_cess, latrine_tax, additional_tax, quarterly_tax, created_on, status)
				VALUES ('$saf_dtl_id', '".$taxDtl['fyear']."', '".$taxDtl['qtr']."', '$arv', '$holding_tax', '$water_tax', '$education_cess', '$health_cess', '$latrine_tax', '$additional_tax', '$quarterly_tax', '".date("Y-m-d H:i:s")."', 1) returning id";
				$query = $this->db->query($sql);
				$return = $query->getFirstRow("array");
				$saf_tax_id = $return["id"];
				// end tax insert code 
				while ($from_y1_new <= $upto_y1_new && $from_y2_new <= $upto_y2_new) {
					$newFY = $from_y1_new."-".$from_y2_new;
					$till_qtr = $newFY == $pymt_upto_year ? $pymt_upto_qtr : 4;
					for ($q = $pymt_frm_qtr; $q <= $till_qtr; $q++) {
						$newFY = $from_y1_new."-".$from_y2_new;
						$totalDemandAmt = $taxDtl['quarterly_tax'];
						$totalBalanceAmt = $taxDtl['quarterly_tax'];
						$additional_tax = $taxDtl['additional_tax'];
						$demand_amount = $taxDtl['quarterly_tax']-$taxDtl['additional_tax'];
						$adjust_type = "";
						$adjust_amt = 0;
						$due_date = $this->makeDueDateByFyearQtr($newFY, $q);
						$demandDtlTemp = [
							"fyear"=>$newFY,
							"qtr"=>$q,
							"due_date"=>$due_date,
							"demand"=>$totalDemandAmt,
							"balance"=>$totalBalanceAmt,
							"additional_tax"=>$taxDtl['additional_tax'],
							"total_tax"=>$taxDtl['quarterly_tax']
						];
						$demandDtl[] = $demandDtlTemp;
						
						if ($prev_prop_dtl_id!="") {
							$input = [
								'prop_dtl_id'=>$prev_prop_dtl_id,
								'fyear'=>$newFY,
								'qtr'=>$q,
								'due_date'=>$due_date,
								'amount'=>$totalDemandAmt,
								'balance'=>$totalBalanceAmt,
								'demand_amount'=>$demand_amount,
								'additional_amount'=>$additional_tax,
								'fine_tax'=>0
							];
							if($propDemandDtl = $this->model_prop_demand->getPropDemandSumByDueDate($input, $p_saf_dtl_id)) {
								$adjust_amt = ($propDemandDtl["amount"]>$totalDemandAmt)?$totalDemandAmt:$propDemandDtl["amount"];
								$totalBalanceAmt = $totalBalanceAmt-$adjust_amt;
								$adjust_type = ($adjust_amt>0)?"Advance":"";
							}
						}
						if ($totalDemandAmt>0) {
							$input = [
								'saf_dtl_id'=>$saf_dtl_id,
								'saf_tax_id'=>$saf_tax_id,
								'fyear'=>$newFY,
								'qtr'=>$q,
								'due_date'=>$due_date,
								'amount'=>$totalDemandAmt,
								'balance'=>$totalBalanceAmt,
								'demand_amount'=>$demand_amount,
								'additional_amount'=>$additional_tax,
								'adjust_type'=>$adjust_type,
								'adjust_amt'=>$adjust_amt,
								'created_on'=>date("Y-m-d H:i:s"),
								'status'=>1
							];
							if ($mutationReassessmentLastDueDate=="" || $mutationReassessmentLastDueDate<=$due_date) {
								$this->model_saf_demand->insertData($input);
							}
						}
					}
					$pymt_frm_qtr = 1;
					$from_y1_new++; 
					$from_y2_new++;
				}
			}
		}
		return $demandDtl;
	}

	public function saveSafData($inputs) {
		$ulb_mstr_id = 1;
		$assessment_type = "New Assessment";
		$assessmentId = "01";
		if ( $inputs["has_previous_holding_no"]==1 && $inputs["is_owner_changed"]==0 ) {
			$assessment_type = "Reassessment";
			$assessmentId = "02";
		} else if( $inputs["has_previous_holding_no"]==1 && $inputs["is_owner_changed"]==1 ) {
			$assessment_type = "Mutation";
			$assessmentId = "03";
		}
		$holding_type = "";
		if ($inputs['prop_type_mstr_id']==4) {
			if ( $inputs['ownership_type_mstr_id']==1 ) {
				$holding_type = "PURE_RESIDENTIAL";
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
			$RESIDENCIAL = false;  $COMMERCIAL = false; $GOVERNMENT = false;
			for($i=0; $i<sizeof($inputs['floor_mstr_id']); $i++){
				if ( $inputs['usage_type_mstr_id'][$i]==1 ) {
					$RESIDENCIAL = true;
				} else if ( $inputs['usage_type_mstr_id'][$i]==7 
							|| $inputs['usage_type_mstr_id'][$i]==9) {
					$GOVERNMENT = true;
				} else {
					$COMMERCIAL = true;
				}
			}

			if( $RESIDENCIAL==true && $GOVERNMENT==false && $COMMERCIAL==false ) {
				$holding_type = "PURE_RESIDENTIAL";
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
		$no_electric_connection = false;
		if($inputs['prop_type_mstr_id']!=4)
		{
			$no_electric_connection = true;
			if($inputs['prop_type_mstr_id']==2 && !isset($inputs['no_electric_connection']))
			{
				$no_electric_connection = false;
			}
		}

		$wardNo = $this->model_ward_mstr->getWardNoById(['ulb_mstr_id'=>$ulb_mstr_id, 'ward_mstr_id'=>$inputs['ward_mstr_id']])['ward_no'];
		$count=$this->model_saf_dtl->CountTotalSAFInWard($inputs['ward_mstr_id'], $assessment_type);
		$count = ++$count;
		$count = str_pad($count, 5, "0", STR_PAD_LEFT)."";
		
		$saf_no = "SAF"."/".$assessmentId."/".str_pad($wardNo, 3, 0, STR_PAD_LEFT)."/".$count;//."/".rand(100,999);
		// echo "apartment_details_id ".$inputs['apartment_details_id'];
		/* print_var($inputs);
		die(); */
		$input = [
			'apply_date'=>date("Y-m-d"),
			'assessment_type'=>$assessment_type,
			'holding_type'=>$holding_type,
			'has_previous_holding_no'=>$inputs['has_previous_holding_no'],
			'previous_holding_id'=>($inputs['has_previous_holding_no']==1)?$inputs["prev_prop_dtl_id"]:null,
			'is_owner_changed'=>($inputs['is_owner_changed']=="")?null:$inputs['is_owner_changed'],
			'transfer_mode_mstr_id'=>($inputs['is_owner_changed']=="1")?$inputs['transfer_mode_mstr_id']:null,
			'saf_no'=>$saf_no,
			'holding_no'=>($inputs['has_previous_holding_no']==1)?$inputs['holding_no']:null,
			'ward_mstr_id'=>$inputs['ward_mstr_id'], 
			'new_ward_mstr_id'=>$inputs['new_ward_mstr_id'],
			'ownership_type_mstr_id'=>$inputs['ownership_type_mstr_id'], 
			'prop_type_mstr_id'=>$inputs['prop_type_mstr_id'], 
			'zone_mstr_id'=>$inputs['zone_mstr_id'],
			'appartment_name'=>'',
			'apartment_details_id'=>($inputs['prop_type_mstr_id']==3 && $inputs['apartment_details_id']!="")?$inputs['apartment_details_id']:NULL,
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
			'is_petrol_pump'=>($inputs['prop_type_mstr_id']==4 || $inputs['prop_type_mstr_id']==3)?null:$inputs['is_petrol_pump'], 
			'under_ground_area'=>($inputs['is_petrol_pump']==1)?$inputs['under_ground_area']:0, 
			'petrol_pump_completion_date'=>($inputs['is_petrol_pump']==1)?$inputs['petrol_pump_completion_date']:null, 
			'is_water_harvesting'=>($inputs['prop_type_mstr_id']==4)?null:$inputs['is_water_harvesting'],
			'land_occupation_date'=>($inputs['prop_type_mstr_id']==4)?$inputs['land_occupation_date']:null,
			'payment_status'=>0,
			'doc_verify_status'=>0,
			'field_verify_status'=>0,
			'emp_details_id'=>$inputs['emp_details_id'], 
			'created_on'=>date("Y-m-d"), 
			'status'=>1
		];
		// echo "<br/>";
		// $d=false;
		// print($d);
		// print("is_p  ".$inputs['is_petrol_pump']);
		$saf_dtl_id = $this->model_saf_dtl->insertData($input);
		$sql_json = " INSERT INTO tbl_saf_json_dtl (saf_dtl_id, saf_dtl, status) VALUES (".$saf_dtl_id.", get_json_saf_dtl(".$saf_dtl_id."), 1);";
		$this->db->query($sql_json);

		if ( $inputs['has_previous_holding_no']==0 || ($inputs['has_previous_holding_no']==1 && $inputs['is_owner_changed']==1)) {
			for($i=0; $i < sizeof($inputs['owner_name']); $i++) {
				$input = [
					'saf_dtl_id'=>$saf_dtl_id,
					'owner_name'=>$inputs['owner_name'][$i],
					'gender'=>$inputs['gender'][$i],
					'dob'=>$inputs['dob'][$i],
					'guardian_name'=>$inputs['guardian_name'][$i],
					'relation_type'=>$inputs['relation_type'][$i],
					'mobile_no'=>($inputs['mobile_no'][$i]!="")?$inputs['mobile_no'][$i]:null,
					'aadhar_no'=>($inputs['aadhar_no'][$i]!="")?$inputs['aadhar_no'][$i]:null,
					'pan_no'=>$inputs['pan_no'][$i],
					'email'=>$inputs['email'][$i],
					'is_armed_force'=>($inputs['is_armed_force'][$i]=="YES")?true:false,
					'is_specially_abled'=>($inputs['is_specially_abled'][$i]=="YES")?true:false,
					'emp_details_id'=>$inputs['emp_details_id'],
					'created_on'=>date("Y-m-d H:i:s"),
					'status'=>1
				];
				$this->model_saf_owner_detail->insertData($input);
			}
		} else {
			$prev_owner_json = [];
			for($i=0; $i<sizeof($inputs['prev_owner_name']); $i++) {
				$input = [
					'saf_dtl_id'=>$saf_dtl_id,
					'owner_name'=>$inputs['prev_owner_name'][$i],
					'gender'=>$inputs['prev_gender'][$i],
					'dob'=>$inputs['prev_dob'][$i],
					'guardian_name'=>$inputs['prev_guardian_name'][$i],
					'relation_type'=>$inputs['prev_relation_type'][$i],
					'mobile_no'=>($inputs['prev_mobile_no'][$i]!="")?$inputs['prev_mobile_no'][$i]:null,
					'aadhar_no'=>($inputs['prev_aadhar_no'][$i]!="")?$inputs['prev_aadhar_no'][$i]:null,
					'pan_no'=>$inputs['prev_pan_no'][$i],
					'email'=>$inputs['prev_email'][$i],
					'is_armed_force'=>($inputs['prev_is_armed_force'][$i]=="YES")?true:false,
					'is_specially_abled'=>($inputs['prev_is_specially_abled'][$i]=="YES")?true:false,
					'emp_details_id'=>$inputs['emp_details_id'],
					'created_on'=>date("Y-m-d H:i:s"),
					'status'=>1
				];
				$this->model_saf_owner_detail->insertData($input);
				$prev_owner_json[] = $input;
			}
			$sql_json = "UPDATE tbl_saf_json_dtl SET saf_previous_owner_dtl='".json_encode($prev_owner_json)."' WHERE saf_dtl_id=".$saf_dtl_id.";";
			$this->db->query($sql_json);
		}

		if ($inputs['prop_type_mstr_id']!=4) {
			for($i=0; $i<sizeof($inputs['floor_mstr_id']); $i++) {
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
					'created_on'=>date("Y-m-d H:i:s"),
					'status'=>1,
					'prop_floor_details_id'=>(isset($inputs['prop_floor_details_id'][$i]) && $inputs['prop_floor_details_id'][$i]!="")?$inputs['prop_floor_details_id'][$i]:null,
				];
				$this->model_saf_floor_details->insertData($input);
			}
		}
		return $saf_dtl_id;
	}

	public function savegovtSafData($inputs) {
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
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
		return $govt_saf_dtl_id;
	}

	public function calGovtSafDemand($govtSafTaxDtl, $govt_saf_dtl_id) {
		$currentFY = getFY();
		$currentFY = "2022-2023";

		// get prev saf dtl
		$p_govt_saf_dtl_id = 0;
		$prev_prop_dtl_id = "";
		$sql = "SELECT * FROM tbl_govt_saf_dtl WHERE id=".$govt_saf_dtl_id;
		$prop_dtl = $this->db->query($sql);
		if ($prop_dtl = $prop_dtl->getFirstRow("array")) {
			if ($prop_dtl["prop_dtl_id"]!="") {
				$prev_prop_dtl_id = $prop_dtl["prop_dtl_id"];
				$sql = "SELECT govt_saf_dtl_id FROM tbl_prop_dtl WHERE id=".$prev_prop_dtl_id;
				$p_govt_saf_dtl = $this->db->query($sql);
				$p_govt_saf_dtl = $p_govt_saf_dtl->getFirstRow("array");
				$p_govt_saf_dtl_id = ($p_govt_saf_dtl["govt_saf_dtl_id"]=="")?0:$p_govt_saf_dtl["govt_saf_dtl_id"];
			}
		}
		// get prev saf dtl end

		$demandDtl = [];
		foreach ($govtSafTaxDtl AS $key=>$taxDtl) {
			$pymt_frm_qtr = (int)$taxDtl['qtr'];
			$pymt_frm_year = (String)$taxDtl['fyear'];

			$pymt_upto_qtr = (int)4;
			$pymt_upto_year = (String)$currentFY;
			if ($key < sizeof($govtSafTaxDtl)-1) {
				$pymt_upto_qtr = (int)$govtSafTaxDtl[$key+1]['qtr']-1;
				$pymt_upto_year = (String)$govtSafTaxDtl[$key+1]['fyear'];
			}
			list($from_y1_new, $from_y2_new) = explode("-", $pymt_frm_year);
			list($upto_y1_new, $upto_y2_new) = explode("-", $pymt_upto_year);

			if ($taxDtl['arv']>=0) {
			// tax insert code 
				$arv = $holding_tax = $water_tax = $education_cess = $health_cess = $latrine_tax = $additional_tax = $quarterly_tax = 0;
				if ($taxDtl["rule_type"]=="OLD_RULE") {
					$arv = $taxDtl["arv"];
					$holding_tax = $taxDtl["holding_tax"];
					$water_tax = $taxDtl["water_tax"];
					$education_cess = $taxDtl["education_cess"];
					$health_cess = $taxDtl["health_cess"];
					$latrine_tax = $taxDtl["latrine_tax"];
					$additional_tax = $taxDtl["additional_tax"];
					$quarterly_tax = $taxDtl["quarterly_tax"];
				} else if ($taxDtl["rule_type"]!="OLD_RULE") {
					$arv = $taxDtl["arv"];
					$holding_tax = $taxDtl["holding_tax"];
					$additional_tax = $taxDtl["additional_tax"];
					$quarterly_tax = $taxDtl["quarterly_tax"];
				}
				$input = [
					'saf_dtl_id'=>$govt_saf_dtl_id, 'fyear'=>$taxDtl['fyear'], 'qtr'=>$taxDtl['qtr'], 'arv'=>$arv, 'holding_tax'=>$holding_tax, 'water_tax'=>$water_tax, 'education_cess'=>$education_cess, 'health_cess'=>$health_cess, 'latrine_tax'=>$latrine_tax, 'quarterly_tax'=>$quarterly_tax, 'created_on'=>date("Y-m-d H:i:s"), 'status'=>1
				];
				//$saf_tax_id = $this->model_saf_tax->insertData($input);
				$sql = "INSERT INTO tbl_govt_saf_tax_dtl (govt_saf_dtl_id, fyear, qtr, arv, holding_tax, water_tax, education_cess, health_cess, latrine_tax, additional_tax, quarterly_tax, created_on, status)
				VALUES ('$govt_saf_dtl_id', '".$taxDtl['fyear']."', '".$taxDtl['qtr']."', '$arv', '$holding_tax', '$water_tax', '$education_cess', '$health_cess', '$latrine_tax', '$additional_tax', '$quarterly_tax', '".date("Y-m-d H:i:s")."', 1) returning id";
				$query = $this->db->query($sql);
				$return = $query->getFirstRow("array");
				$govt_saf_tax_id = $return["id"];
				
				// end tax insert code 
				while ($from_y1_new <= $upto_y1_new && $from_y2_new <= $upto_y2_new) {
					$newFY = $from_y1_new."-".$from_y2_new;
					$till_qtr = $newFY == $pymt_upto_year ? $pymt_upto_qtr : 4;
					for ($q = $pymt_frm_qtr; $q <= $till_qtr; $q++) {
						$newFY = $from_y1_new."-".$from_y2_new;
						$totalDemandAmt = $taxDtl['quarterly_tax'];
						$additional_tax = $taxDtl['additional_tax'];
						$demand_amount = $taxDtl['quarterly_tax']-$taxDtl['additional_tax'];
						$due_date = $this->makeDueDateByFyearQtr($newFY, $q);
						$demandDtlTemp = [
							"fyear"=>$newFY,
							"qtr"=>$q,
							"due_date"=>$due_date,
							"demand"=>$totalDemandAmt,
							"additional_tax"=>$taxDtl['additional_tax'],
							"total_tax"=>$taxDtl['quarterly_tax']
						];
						$demandDtl[] = $demandDtlTemp;
						
						if ($prev_prop_dtl_id!="") {
							$input = [
								'prop_dtl_id'=>$prev_prop_dtl_id,
								'fyear'=>$newFY,
								'qtr'=>$q,
								'due_date'=>$due_date,
								'amount'=>$totalDemandAmt,
								'balance'=>$totalDemandAmt,
								'demand_amount'=>$demand_amount,
								'additional_amount'=>$additional_tax,
								'fine_tax'=>0
							];
							if($propDemandDtl = $this->model_prop_demand->getPropDemandSumByDueDate($input, $p_govt_saf_dtl_id)) {
								$totalDemandAmt = $totalDemandAmt-$propDemandDtl["amount"];
							}
						}
						if ($totalDemandAmt>0) {
							$sql = "SELECT id FROM view_fy_mstr WHERE fy='".$newFY."' LIMIT 1";
							$builder=$this->db->query($sql);
                			$fy_mstr_id = $builder->getFirstRow("array")["id"];
							$input = [
								'govt_saf_dtl_id'=>$govt_saf_dtl_id,
								'govt_saf_tax_dtl_id'=>$govt_saf_tax_id,
								'fy_mstr_id'=>$fy_mstr_id,
								'fyear'=>$newFY,
								'qtr'=>$q,
								'due_date'=>$due_date,
								'amount'=>$totalDemandAmt,
								'balance'=>$totalDemandAmt,
								'demand_amount'=>$demand_amount,
								'additional_holding_tax'=>$additional_tax,
								'adjust_amount'=>0,
								'paid_status'=>0,
								'created_on'=>date("Y-m-d H:i:s"),
								'status'=>1
							];
							$this->model_govt_saf_demand->insertData($input);
						}
					}
					$pymt_frm_qtr = 1;
					$from_y1_new++; 
					$from_y2_new++;
				}
			}
		}
		return $demandDtl;
	}

	public function calcDiffPanelty($safTaxDtl, $newSafTaxDtl){
		//print_var($safTaxDtl);
		//print_var($newSafTaxDtl);
        $i = 1;
        $diffTax = [];
        foreach($newSafTaxDtl AS $key => $newList) {
            //$_fy_id = $newList['fyID'];
            $_fy = $newList['fyear'];
            $_qtr = $newList['qtr'];
            $_arv = $newList['arv'];

			$_holding_tax = $newList['holding_tax'];
			$_water_tax = 0;
			$_education_cess = 0;
			$_health_cess = 0;
			$_latrine_tax = 0;
			$_additional_tax = $newList['additional_tax'];
			if ($newList["rule_type"]=="OLD_RULE") {
				$_water_tax = $newList['water_tax'];
				$_education_cess = $newList['education_cess'];
				$_health_cess = $newList['health_cess'];
				$_latrine_tax = $newList['latrine_tax'];
			}
            

            foreach($safTaxDtl AS $key => $oldList) {
				if ($oldList['fyear']==$_fy && $oldList['qtr']==$_qtr) {
                    $_arv -= $oldList['arv'];
                    $_holding_tax -= $oldList['holding_tax'];
                    $_water_tax -= $oldList['water_tax'];
                    $_education_cess -= $oldList['education_cess'];
                    $_health_cess -= $oldList['health_cess'];
                    $_latrine_tax -= $oldList['latrine_tax'];
                    $_additional_tax -= $oldList['additional_tax'];
                }
            }
            if ($_arv>0) {
                $diffTax[$i]['fyear'] = $_fy;
                $diffTax[$i]['qtr'] = $_qtr;
                $diffTax[$i]['arv'] = $_arv;
                $diffTax[$i]['holding_tax'] = $_holding_tax;
                $diffTax[$i]['water_tax'] = $_water_tax;
                $diffTax[$i]['education_cess'] = $_education_cess;
                $diffTax[$i]['health_cess'] = $_health_cess;
                $diffTax[$i]['latrine_tax'] = $_latrine_tax;
                $diffTax[$i]['additional_tax'] = $_additional_tax;
				//print_var($diffTax[$i]);
                $i++;
				
            }
        }
        return $diffTax;
    }

	public function calDiffSafDemand($safTaxDtl, $saf_dtl_id, $prop_dtl_id) {
		$currentFY = getFY();
		$currentFY = "2022-2023";

		$demandDtl = [];
		foreach ($safTaxDtl AS $key=>$taxDtl) {
			$pymt_frm_qtr = (int)$taxDtl['qtr'];
			$pymt_frm_year = (String)$taxDtl['fyear'];

			$pymt_upto_qtr = (int)4;
			$pymt_upto_year = (String)$currentFY;
			if ($key < sizeof($safTaxDtl)-1) {
				$pymt_upto_qtr = (int)$safTaxDtl[$key+1]['qtr']-1;
				$pymt_upto_year = (String)$safTaxDtl[$key+1]['fyear'];
			}
			list($from_y1_new, $from_y2_new) = explode("-", $pymt_frm_year);
			list($upto_y1_new, $upto_y2_new) = explode("-", $pymt_upto_year);

			if ($taxDtl['arv']>=0) {
			// tax insert code
				$arv = $holding_tax = $water_tax = $education_cess = $health_cess = $latrine_tax = $additional_tax = $quarterly_tax = 0;
				if ($taxDtl["rule_type"]=="OLD_RULE") {
					$arv = $taxDtl["arv"];
					$holding_tax = $taxDtl["holding_tax"];
					$water_tax = $taxDtl["water_tax"];
					$education_cess = $taxDtl["education_cess"];
					$health_cess = $taxDtl["health_cess"];
					$latrine_tax = $taxDtl["latrine_tax"];
					$additional_tax = $taxDtl["additional_tax"];
					$quarterly_tax = $taxDtl["quarterly_tax"];
				} else if ($taxDtl["rule_type"]!="OLD_RULE") {
					$arv = $taxDtl["arv"];
					$holding_tax = $taxDtl["holding_tax"];
					$additional_tax = $taxDtl["additional_tax"];
					$quarterly_tax = $taxDtl["quarterly_tax"];
				}
				$input = [
					'prop_dtl_id'=>$prop_dtl_id, 'fyear'=>$taxDtl['fyear'], 'qtr'=>$taxDtl['qtr'], 'arv'=>$arv, 'holding_tax'=>$holding_tax, 'water_tax'=>$water_tax, 'education_cess'=>$education_cess, 'health_cess'=>$health_cess, 'latrine_tax'=>$latrine_tax, 'created_on'=>date("Y-m-d H:i:s"), 'status'=>1
				];
				//echo "<br />Tax Query<br /><br />";
				//$prop_tax_id = $this->model_prop_tax->insertData($input);
				
				$sql = "INSERT INTO tbl_prop_tax (prop_dtl_id, fyear, qtr, arv, holding_tax, water_tax, education_cess, health_cess, latrine_tax, additional_tax, created_on, status)
				VALUES ('$prop_dtl_id', '".$taxDtl['fyear']."', '".$taxDtl['qtr']."', '$arv', '$holding_tax', '$water_tax', '$education_cess', '$health_cess', '$latrine_tax', '$additional_tax', '".date("Y-m-d H:i:s")."', 1) returning id";
				$query = $this->db->query($sql);
				$return = $query->getFirstRow("array");
				$prop_tax_id = $return["id"];
				
				// end tax insert code 
				while ($from_y1_new <= $upto_y1_new && $from_y2_new <= $upto_y2_new) {
					$newFY = $from_y1_new."-".$from_y2_new;
					$till_qtr = $newFY == $pymt_upto_year ? $pymt_upto_qtr : 4;
					for ($q = $pymt_frm_qtr; $q <= $till_qtr; $q++) {
						$newFY = $from_y1_new."-".$from_y2_new;
						$demandAmt = $taxDtl['quarterly_tax']-$taxDtl['additional_tax'];
						$due_date = $this->makeDueDateByFyearQtr($newFY, $q);
						$demandDtlTemp = [
							"fyear"=>$newFY,
							"qtr"=>$q,
							'due_date'=>$due_date,
							"demand"=>$demandAmt,
							"additional_tax"=>$taxDtl['additional_tax'],
							"total_tax"=>$taxDtl['quarterly_tax']
						];
						$demandDtl[] = $demandDtlTemp;
						
						if ($prop_tax_id!="") {
							$input = [
								'prop_dtl_id'=>$prop_dtl_id,
								'fyear'=>$newFY,
								'qtr'=>$q,
								'due_date'=>$due_date,
								'amount'=>$demandAmt,
								'balance'=>$demandAmt,
								'fine_tax'=>0
							];
							//echo "<br />CAL DEMAND<br />";
							$sql = "SELECT COALESCE(SUM(amount), 0) AS total_amount FROM (SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_prop_demand WHERE prop_dtl_id=".$prop_dtl_id." AND status=1 AND paid_status IN (0,1) AND due_date='".$due_date."'
									UNION
									SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_saf_demand WHERE saf_dtl_id=".$saf_dtl_id." AND status=1 AND paid_status=1 AND due_date='".$due_date."') AS tbl_demand
									GROUP BY due_date
									ORDER BY due_date";
							$total_result = $this->db->query($sql);
							if ($total_prev_demand = $total_result->getFirstRow("array")) {
								$demandAmt = $demandAmt-$total_prev_demand["total_amount"];
							}
						}
						if ($demandAmt>0) {
							$input = [
								'prop_dtl_id'=>$prop_dtl_id,
								'prop_tax_id'=>$prop_tax_id,
								'fyear'=>$newFY,
								'qtr'=>$q,
								'due_date'=>$due_date,
								'amount'=>$demandAmt,
								'balance'=>$demandAmt,
								'fine_tax'=>0,
								'created_on'=>date("Y-m-d H:i:s"),
								'status'=>1
							];
							$this->model_prop_demand->insertData($input);
							//echo "<br />Demand Query<br />";
							//echo $this->db->getLastQuery();
						}
					}
					$pymt_frm_qtr = 1;
					$from_y1_new++; 
					$from_y2_new++;
				}
			}
		}
		return $demandDtl;
	}
}