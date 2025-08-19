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

class PropertyBulkPaymentReceipt extends AlphaController
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
    	helper(['db_helper', 'qr_code_generator_helper','form']);
    	//	$this->load->library('phpqrcode/qrlib');
        if($db_name = dbConfig("property")){
			//echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
		//$db_name = db_connect("db_rmc_property");		
        $this->model = new model_ward_mstr($this->dbSystem);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->modelfy = new model_fy_mstr($this->dbSystem);
		$this->modelprop = new model_prop_dtl($this->db);
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
	public function bulkPrint()
	{
		$data =(array)null;
		$printAllData=[];
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		//print_r($data['penalty_dtl']);
		//return view('Property/jsk/payment_receipt', $data);
		//propertyBulkPaymentReceipt.php
		//propertyBulkPrint.php
		if($this->request->getMethod()=="post"){
			$inputs = arrFilterSanitizeString($this->request->getVar());  
			$data['from_date'] = $inputs['from_date'];
			$data['to_date'] = $inputs['to_date'];
			if($printData = $this->modelpay->getBulkPrintData($data)){
				$data['len'] = sizeof($printData);
				foreach ($printData as $key => $value) {
					$path=base_url('citizenPaymentReceipt/payment_jsk_receipt/'.$ulb_mstr_id.'/'.md5($value['id']));
					$printAllData[$key]['ss'] = qrCodeGeneratorFun($path);
					$printAllData[$key]['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
					$data['tran_no'] = md5($value['id']);
					$printAllData[$key]['tran_mode_dtl'] = $this->modelpay->getTrandtlList($data);
					$printAllData[$key]['fyFrom'] = $this->modelfy->getfyFromList($printAllData[$key]['tran_mode_dtl']['from_fy_mstr_id']);
					$printAllData[$key]['fyUpto'] = $this->modelfy->getfyUptoList($printAllData[$key]['tran_mode_dtl']['upto_fy_mstr_id']);
					$printAllData[$key]['payMode'] = $this->modeltran->getpayModeList($printAllData[$key]['tran_mode_dtl']['tran_mode_mstr_id']);
					$printAllData[$key]['holdingward'] = $this->modelprop->getholdWard(md5($printAllData[$key]['tran_mode_dtl']['prop_dtl_id']));
					$printAllData[$key]['basic_details'] = $this->modelprop->basic_dtl(md5($printAllData[$key]['tran_mode_dtl']['prop_dtl_id']));
					if($value['tran_mode_mstr_id']==2 || $value['tran_mode_mstr_id']==3){
						$printAllData[$key]['chqDD_details'] = $this->modelchqDD->mode_dtl(md5($value['id']));
					}
					$printAllData[$key]['coll_dtl'] = $this->modelpropcoll->collection_propdtl($printAllData[$key]['tran_mode_dtl']['id']);
					$printAllData[$key]['penalty_dtl'] = $this->modelpenalty->penalty_dtl($printAllData[$key]['tran_mode_dtl']['id']);
				}
				$data['printAllData'] = $printAllData;
				return view('property/propertyBulkPrint',$data);
			}else{
				return view('property/propertyBulkPaymentReceipt',$data);
			}
		}else{
			return view('property/propertyBulkPaymentReceipt');
		}
	}
}
