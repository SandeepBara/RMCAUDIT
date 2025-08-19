<?php 
namespace App\Controllers;

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
use App\Models\model_demand_adjustment;
use App\Models\model_saf_collection;
use App\Models\model_datatable;
use App\Models\model_system_name;
use App\Models\model_advance_mstr;

class amount_adjustment extends AlphaController
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
	protected $model_demand_adjustment;
	protected $modelprop;
	protected $model_saf_collection;
	protected $model_datatable;
	protected $model_system_name;
	protected $modeladjust;
	protected $model_advance_mstr;

	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper', 'utility_helper', 'form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
		
        helper(['form']);
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
		$this->modeladjust = new model_demand_adjustment($this->db);
		$this->modelsafcoll = new model_saf_collection($this->db);
		$this->model_datatable = new model_datatable($this->db);
		$this->model_system_name = new model_system_name($this->dbSystem);
		$this->model_advance_mstr = new model_advance_mstr($this->db);
    }

	function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
	}
	
	public function search_property()
	{
		$data =(array)null;
		$Session = Session();
        if($this->request->getMethod()=='post')
		{
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$data['keyword']=$inputs['keyword'];
			if($data['keyword']!=null)
			{
				$where=" and (holding_no ilike '%".$data['keyword']."%' or new_holding_no ilike '%".$data['keyword']."%')";
			}
			$Session->set('keyword', $inputs['keyword']);
			$Session->set('where', $where);
		}
		return $this->response->redirect(base_url('amount_adjustment/adjust_Property'));
	}
	
	public function adjust_Property()
	{
		$data =(array)null;
		$Session = Session();
		$where = $Session->get('where');
		$ulb = $Session->get('ulb_dtl');
		if($where!=null)
		{
			$sql = "select * from view_prop_dtl_owner_ward_prop_type_ownership_type where status=1 ".$where;
			$result = $this->model_datatable->getDatatable($sql);
			$data['result'] = $result['result'];
			$data['offset'] = $result['offset'];
			$data['pager'] = $result['count'];
		}
		$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=> $ulb["ulb_mstr_id"]]);
		$data["keyword"] = $Session->get('keyword');
		return view('property/adjust_Property', $data);
	}
	
	public function adjust_Property_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		$Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
		$data['user_type_id'] = $emp_mstr["user_type_mstr_id"];
		
		$data['basic_details'] = $this->modelprop->basic_details($data);
		if ( $owner_details = $this->modelowner->owner_details($data) ) {
			$data['owner_details'] = $owner_details;
		}
		if ( $tax_list = $this->modeltax->tax_list($data['basic_details']['prop_dtl_id']) ) {
			$data['tax_list'] = $tax_list;
		}
		if ( $payment_detail_prop = $this->modelpay->payment_adjust_detail($data['basic_details']['prop_dtl_id']) ) {
			$data['payment_detail'] = $payment_detail_prop;
		}
		if ( $payment_detail_saf = $this->modelpay->payment_detail_saf($data['basic_details']['saf_dtl_id']) ) {
			$data['payment_detail_saf'] = $payment_detail_saf;
		}
		//$data['tran_adjust_id'] = $this->model_amount_adjustment->adjust_amount_id($data['id']);
		$data['transaction_adjust'] = $this->model_advance_mstr->adjust_amount($data['id']);
		$data['rest_amnt'] = $data['transaction_adjust']['total_amnt'] - $data['transaction_adjust']['rest_amnt'];
		
		return view('property/adjust_Property_details', $data);
	}

	public function transaction_adjust($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		$Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
		
		$tran_dtl = $this->modelpay->adjust_tran_dtl($data['id']);
		
		$data['prop_dtl_id']=$tran_dtl['prop_dtl_id'];
		$data['amount']=$tran_dtl['payable_amt'];
		$data['transaction_id']=$tran_dtl['id'];
		$data['created_on']=date('Y-m-d H:i:s');
		$data['reason']="Double Transaction";
		$data['emp_details_id']=$emp_mstr["id"];
		if($this->model_advance_mstr->transaction_adjust($data)){
			return $this->response->redirect(base_url('amount_adjustment/adjust_Property_details/'.md5($data['prop_dtl_id'])));
		}
	}
	
	
	public function amount_adjust($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		$Session = Session();
        $emp_mstr = $Session->get("emp_details");
		
		if($this->request->getMethod()=='post')
		{
			$data = [
					'prop_dtl_id' => $this->request->getVar('prop_id'),
					'amount' => $this->request->getVar('amount'),
					'reason' => $this->request->getVar('reason'),
					'remarks' => $this->request->getVar('remarks'),
				];
			$data['created_on']=date('Y-m-d H:i:s');
			$data['emp_details_id']=$emp_mstr["id"];
			$amount_adjust_id = $this->model_advance_mstr->adjust_amount_insert($data);
			$doc_file = $this->request->getFile('bill_doc_path');
			
			if ($doc_file->IsValid() && !$doc_file->hasMoved()){
				$file_ext = $doc_file->getExtension();
				$ulb_dtl = $Session->get('ulb_dtl');
				$city = $ulb_dtl['city'];
				$newFileNamee = md5($amount_adjust_id).".".$file_ext;
				$path = "amount_adjust";
				if ($doc_file->move(WRITEPATH . 'uploads/'.$city.'/'.$path.'/', $newFileNamee )) {
					$doc_path = $city.'/'.$path."/".$newFileNamee;
					if ($this->model_advance_mstr->adjust_amount_update($amount_adjust_id, $doc_path)) {
						return $this->response->redirect(base_url('amount_adjustment/adjust_Property_details/'.md5($data['prop_dtl_id'])));
					}
				}
			}
		}
	}
	
	
	

}
