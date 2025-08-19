<?php namespace App\Controllers;


use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_tax;
use App\Models\model_prop_demand;
use App\Models\model_prop_floor_details;
use App\Models\model_transaction;
use App\Models\model_tran_mode_mstr;
use App\Models\model_payment_adjustment;
use App\Models\model_fy_mstr;
use App\Models\model_cheque_details;
use App\Models\model_collection;
use App\Models\model_saf_dtl;
use App\Models\model_bank_recancilation;
use App\Models\model_transaction_fine_rebet_details;
use App\Models\model_legacy_demand_update;
use App\Models\model_penalty_dtl;

class Ajax_pagination extends AlphaController
{
	protected $db;
	protected $dbSystem;
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_prop_dtl;
	protected $model_prop_owner_detail;
	protected $model_prop_tax;
	protected $model_prop_demand;
	protected $model_prop_floor_details;
	protected $model_transaction;
	protected $model_tran_mode_mstr;
	protected $model_payment_adjustment;
	protected $model_fy_mstr;
	protected $model_cheque_details;
	protected $model_collection;
	protected $model_saf_dtl;
	protected $model_bank_recancilation;
	protected $model_transaction_fine_rebet_details;
    protected $model_legacy_demand_update;
	protected $model_penalty_dtl;

	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper']);
    	//	$this->load->library('phpqrcode/qrlib');
        if($db_name = dbConfig("property")){
			//echo $db_name;
            $this->db = db_connect($db_name);
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system);
        }
		
        helper(['form']);
		//$db_name = db_connect("db_rmc_property");		
        $this->model = new model_ward_mstr($this->dbSystem);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->modelfy = new model_fy_mstr($this->dbSystem);
		$this->Ajax_pagination_model = new model_prop_dtl($this->db);
		$this->modelowner = new model_prop_owner_detail($this->db);
		$this->modeltax = new model_prop_tax($this->db);
		$this->modeldemand = new model_prop_demand($this->db);
		$this->modelfloor = new model_prop_floor_details($this->db);
		$this->modelpay = new model_transaction($this->db);
		$this->modelassess = new model_saf_dtl($this->db);
		$this->modeltran = new model_tran_mode_mstr($this->db);
		$this->modeladjustment = new model_payment_adjustment($this->db);
		$this->modelchqDD = new model_cheque_details($this->db);
		$this->modelpropcoll = new model_collection($this->db);
		$this->model_bank_recancilation = new model_bank_recancilation($this->db);
		$this->modelpenalty = new model_transaction_fine_rebet_details($this->db);
        $this->model_legacy_demand_update = new model_legacy_demand_update($this->db);
		$this->model_penalty_dtl = new model_penalty_dtl($this->db);
    }
	
	
	
	public function pagination()
	{
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
			echo "jhfbvhjf";
			$data = [
                        /*'previous_ward_mstr_id' => $this->request->getVar('previous_ward_mstr_id'),*/
						'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
						'holding_no' => $this->request->getVar('holding_no')
						/*'house_no' => $this->request->getVar('house_no')*/
                    ];
			$data['emp_details'] = $this->Ajax_pagination_model->consumer_details($data);
			$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
			return view('property/jsk/ajax_pagination', $data);
		} else{
		$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
		$data['emp_details'] = $this->Ajax_pagination_model->PropDetails();
		return view('property/jsk/ajax_pagination', $data);
		}
	}
	
	
}
