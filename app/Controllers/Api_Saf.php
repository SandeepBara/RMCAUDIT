<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_ward_mstr;
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


class Api_Saf extends Controller {

    protected $db;
    protected $dbSystem;
	protected $model_ulb_mstr;
	protected $model_fy_mstr;
	protected $model_ward_mstr;
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
    
    public function __construct() {
        //parent::__construct();
    	helper(['url', 'db_helper']);
        //if($db_name = dbConfig("property")){
            $this->db = db_connect("db_rmc_property");
        //}
        //if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect("db_system"); 
        //}
		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
		$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
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
    }

    public function SAFSearchBySAFNoNameMobile() {
        // code by xxx commented line no. 98
        // return json_encode(['asdasd'=>5454]);
        die();
        if ($this->request->getMethod()=='post') {
            try {
                $inputs = arrFilterSanitizeString($this->request->getVar());

                $builder = $this->db->table('tbl_saf_dtl');
                $builder = $builder->select('tbl_saf_dtl.saf_no AS saf_no, tbl_saf_owner_detail.owner_name AS owner_name, tbl_saf_owner_detail.mobile_no AS mobile_no');
                $builder = $builder->join('tbl_saf_owner_detail', 'tbl_saf_owner_detail.saf_dtl_id = tbl_saf_dtl.id');
                if ($inputs['saf_no'] == "") {
                    $builder = $builder->like('tbl_saf_owner_detail.owner_name ', $inputs['owner_name']);
                    $builder = $builder->like('tbl_saf_owner_detail.mobile_no ', $inputs['mobile_no']);
                } else {
                    $builder = $builder->like('tbl_saf_dtl.saf_no ', $inputs['saf_no']);
                }
                $builder = $builder->get();
                $data = $builder->getResultArray();
                echo "<pre>";
                print_r($data);
            } catch(Exception $e) {
                echo $e->getMessage();
            }
        } 
    }
}