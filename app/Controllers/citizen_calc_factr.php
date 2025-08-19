<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_usage_type_mstr;
use App\Models\model_occupancy_type_mstr;
use App\Models\model_arr_building_mstr;
use App\Models\model_arr_old_building_mstr;
use App\Models\model_arr_vacant_mstr;
use App\Models\model_arr_new_vacant_mstr;
use App\Models\model_capital_value_rate;


class citizen_calc_factr extends HomeController
{
	protected $model_usage_type_mstr;
	protected $model_occupancy_type_mstr;
	protected $model_arr_building_mstr;
	protected $model_arr_vacant_mstr;
	protected $model_arr_new_vacant_mstr;
	protected $model_arr_old_building_mstr;
	protected $model_capital_value_rate;

    public function __construct(){
		
        parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'sms_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);
        }
		$this->model_usage_type_mstr = new model_usage_type_mstr($this->db);
		$this->model_occupancy_type_mstr = new model_occupancy_type_mstr($this->db);
		$this->model_arr_building_mstr = new model_arr_building_mstr($this->db);
		$this->model_arr_vacant_mstr = new model_arr_vacant_mstr($this->db);
		$this->model_arr_new_vacant_mstr = new model_arr_new_vacant_mstr($this->db);
		$this->model_arr_old_building_mstr = new model_arr_old_building_mstr($this->db);
		$this->model_capital_value_rate = new model_capital_value_rate($this->db);
    }

	public function getVacantLandRentalRateFactor(){
		if($rentaVacantLandRateFactorList = $this->model_arr_vacant_mstr->getJoinVacantLandRateByRoadType()) {
			$data['rentaVacantLandRateFactorList'] = $rentaVacantLandRateFactorList;
			return view('property/calc_factor/vacant_land_rental_rate_factor', $data);
		}
	}

	public function getVacantLandRentalNewRateFactor(){
		return view('property/calc_factor/vacant_land_rental_new_rate_factor');
		/* if($rentaVacantLandRateFactorList = $this->model_arr_new_vacant_mstr->getJoinVacantLandRateByRoadType()) {
			$data['rentaVacantLandRateFactorList'] = $rentaVacantLandRateFactorList;
			return view('property/calc_factor/vacant_land_rental_new_rate_factor', $data);
		} */
	}

	public function citizengetUsageFactor(){
		if($usageTypeFactorList = $this->model_usage_type_mstr->getUsageTypeFactorList()) {
			$data['usageTypeFactorList'] = $usageTypeFactorList;
			return view('citizen/SAF/usage_factor', $data);
		}
	}

	public function citizengetUsageFactorCV() {
		if($usageTypeFactorList = $this->model_usage_type_mstr->getUsageTypeFactorCVList()) {
			$data['usageTypeFactorList'] = $usageTypeFactorList;
			return view('citizen/SAF/usage_factor_cv', $data);
		}
	}
	
	public function citizengetOccupancyFactor(){
		if($occupancyTypeFactorList = $this->model_occupancy_type_mstr->getOccupancyTypeList()) {
			$data['occupancyTypeFactorList'] = $occupancyTypeFactorList;
			return view('citizen/SAF/occupancy_factor', $data);
		}
	}
	
	public function citizengetRentalRateFactor(){
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
			return view('citizen/SAF/rental_rate_factor', $data);
		}
	}
	
	
	public function citizengetOldRuleRentalRateFactor(){
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
			return view('citizen/SAF/old_rule_rental_rate_factor', $data);
		}
	}

	// public function citizengetCapitalValueRateFactor(){
	// 	if($result = $this->model_capital_value_rate->showCapitalRate()) {
	// 		return view('citizen/SAF/capital_value_rate_factor', ["capital_rate"=>$result]);
	// 	}
	// }
	public function citizengetCapitalValueRateFactor($ward_no){
		
		if($ward_no!=null){
			
			if($result = $this->model_capital_value_rate->showCapitalRateByWardNo($ward_no)) {
				// echo"<pre>";
				// print_r($result);
				// die;
				return view('citizen/SAF/capital_value_rate_factor_by_id', ["capital_rate"=>$result]);
			}
		}
		
	}
	public function citizengetCapitalValueRateFactor24($ward_no){

		if($ward_no!=null){

			if($result = $this->model_capital_value_rate->showCapitalRateByWardNo24($ward_no)) {
				// echo"<pre>";
				// print_r($result);
				// die;
				return view('citizen/SAF/capital_value_rate_factor_by_id', ["capital_rate"=>$result]);
			}
		}
	}
	public function citizengetMatrixFactorRate(){
		//if($result = $this->model_capital_value_rate->showCapitalRate()) {
			return view('citizen/SAF/capital_matrix_factor');
		//}
	}
}
?>
