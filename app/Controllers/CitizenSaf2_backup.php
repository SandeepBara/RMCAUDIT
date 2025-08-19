<?php 
namespace App\Controllers;

use App\Controllers\SAF\SAFHelper;
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
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;

class CitizenSaf2 extends HomeController
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
	protected $model_doc_mstr;
	protected $model_saf_doc_dtl;
	protected $model_view_saf_doc_dtl;
	protected $model_level_pending_dtl;
	protected $model_view_saf_dtl;
	protected $model_transaction;
	protected $model_prop_floor_details;

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
    }

    public function addUpdate($param = null)
	{
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$emp_details_id = 0;

        $data = arrFilterSanitizeString($this->request->getVar());
        $data['assessmentType'] = "";
        $data['ulb_address'] = $this->model_ulb_mstr->getAddressById(['ulb_mstr_id'=>$ulb_mstr_id]);
		$data['wardList'] = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $safHelper = new SAFHelper();
        $safMstrDtl = $safHelper->getSafMstrDtl();
        $data['transferModeList'] = json_decode($safMstrDtl['transfer_mode_mstr'], true);
        $data['ownershipTypeList'] = json_decode($safMstrDtl['ownership_type_mstr'], true);
        $data['propTypeList'] = json_decode($safMstrDtl['prop_type_mstr'], true);
        $data['roadTypeList'] = json_decode($safMstrDtl['road_type_mstr'], true);
        $data['floorList'] = json_decode($safMstrDtl['floor_mstr'], true);
        $data['usageTypeList'] = json_decode($safMstrDtl['usage_type_mstr'], true);
        $data['occupancyTypeList'] = json_decode($safMstrDtl['occupancy_type_mstr'], true);
        $data['constTypeList'] = json_decode($safMstrDtl['const_type_mstr'], true);

        if($this->request->getMethod()=='post') {
			try {
				$inputs = arrFilterSanitizeString($this->request->getVar());
                if(isset($_POST['btn_back'])) {

                } else if(isset($_POST['btn_review'])) {
                    if ($inputs['prop_type_mstr_id']==4) { // vacant land cal
                        // vacant land details
                        $vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
                        //echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Vacant Floor Details >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";
                        //print_var($vacantDtlArr);
                        $safTaxDtl = $safHelper->calVacantTaxDtl($vacantDtlArr);
                        //echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Tax Details >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";
                    // print_var($safTaxDtl);
                        $data['vacant_dtl'] = $vacantDtlArr;
                        $data['safTaxDtl'] = $safTaxDtl;
                    } else { // building cal
                        //echo ">> Floor Details >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ";
                        $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                        $data['floorDtlArr'] = $floorDtlArr;
                        //print_var($floorDtlArr);
                        //return;
                        
                        $isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
                        //echo ">> TAX DETAILS >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ";
                        list($safTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calBuildingTaxDtl($floorDtlArr, $inputs['prop_type_mstr_id'], $isAdditionaTaxImplemented);
                        $data['safTaxDtl'] = $safTaxDtl;
                        $data["old_rule_arv_sub"] = $old_rule_arv_sub;
                        $data["new_rule_arv_sub"] = $new_rule_arv_sub;
                        $data["cv_rule_arv_sub"] = $cv_rule_arv_sub;
                        
                        //print_var($data["safTaxDtl"]);
                        /* print_var($data["old_rule_arv_sub"]);
                        print_var($data["new_rule_arv_sub"]);
                        print_var($data["cv_rule_arv_sub"]);
                        return; */
                        

                        //echo ">> DEMAND DETAILS >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ";
                        //$demandDtl = $safHelper->calSafDemand($safTaxDtl);
                        //print_var($demandDtl);
                    }
                    return view('Citizen/Saf/saf_add_update_review2', $data);
                } else if(isset($_POST['btn_submit'])) {
                    $inputs['ulb_mstr_id'] = $ulb_mstr_id;
                    $inputs['emp_details_id'] = $emp_details_id;

                    /* if($saf_dtl_id = $this->addUpdateSubmit($inputs))
                    {
                        $LINK = base_url('CitizenSaf/CitizenDtlView/'.md5($saf_dtl_id));
                        return redirect()->to($LINK);
                    } */
                }
                return;
            }catch(Exception $e)
			{
                echo $e->getMessage();
                return;
				//
			}
        } else {
            return view('citizen/SAF/saf_add_update2', $data);
        }
    }
}