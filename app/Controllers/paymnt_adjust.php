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
use App\Models\model_demand_adjustment;

class paymnt_adjust extends AlphaController
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
	protected $model_demand_adjustment;

	protected $model;
	protected $modelUlb;
	protected $modelfy;
	protected $modelprop;
	protected $modelowner;
	protected $modeltax;
	protected $modeldemand;
	protected $modelfloor;
	protected $modelpay;
	protected $modeltran;
	protected $modeladjustment;
	protected $modelchqDD;
	protected $modelpropcoll;
	protected $modeladjust;

	public function __construct()
	{
		parent::__construct();
		helper(['db_helper', 'form']);
		if ($db_name = dbConfig("property"))
		{
			//echo $db_name;
			$this->db = db_connect($db_name);
		}
		if($db_system = dbSystem())
		{
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
		$this->modeltran = new model_tran_mode_mstr($this->db);
		$this->modeladjustment = new model_payment_adjustment($this->db);
		$this->modelchqDD = new model_cheque_details($this->db);
		$this->modelpropcoll = new model_collection($this->db);
		$this->modeladjust = new model_demand_adjustment($this->db);
	}


	public function search_adjust_Property_List()
	{
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$data = (array)null;
		if ($this->request->getMethod() == 'post') {
			$data = [
				'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
				'keyword' => $this->request->getVar('keyword')
			];
			if ($data['ward_mstr_id'] != "" and $data['keyword'] != "") {
				$where = "ward_mstr_id='" . $data['ward_mstr_id'] . "' and (mobile_no ilike '%" . $data['keyword'] . "%' or holding_no ilike '%" . $data['keyword'] . "%' or new_holding_no ilike '%" . $data['keyword'] . "%' or owner_name ilike '%" . $data['keyword'] . "%')";
			} else if ($data['keyword'] != "" and $data['ward_mstr_id'] == "") {
				$where = "(mobile_no ilike '%" . $data['keyword'] . "%' or holding_no ilike '%" . $data['keyword'] . "%' or new_holding_no ilike '%" . $data['keyword'] . "%' or owner_name ilike '%" . $data['keyword'] . "%')";
			}
			if ($emp_details = $this->modelprop->consumer_details($where)) {
				$data['emp_details'] = $emp_details;
			}
			//print_r($data['emp_details']);
			if ($pay_status = $this->modeladjust->pay_status($data["emp_details"][0])) {
				if ($pay_status != "") {
					$data['pay_status'] = $pay_status;
				} else {
					if ($pay_statustrans = $this->modelpay->pay_statustrans($data["emp_details"][0])) {
						$data['pay_status'] = $pay_statustrans;
					}
				}
			}
			//print_r($pay_status);
			$data['ward'] = $this->model->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
			return view('property/search_adjust_Property_List', $data);
		} else {
			$data['ward'] = $this->model->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
			return view('property/search_adjust_Property_List', $data);
		}
	}

	public function demand_adjust($prop_dtl_id = null)
	{
		if($inputs = $this->modelprop->getPropDtlByMD5PropDtlId($prop_dtl_id))
		{
			// print_var($inputs);
			// return;

			$inputs['prop_demand_list'] = $this->modeldemand->getFullDemandDtlByPropDtlId(['prop_dtl_id'=>$inputs['prop_dtl_id']]);

			if ($this->request->getMethod() == 'post') 
			{
				$inputs['upto_fy_mstr_id'] = $this->request->getVar('upto_fy_mstr_id');
				$inputs['upto_qtr'] = $this->request->getVar('upto_qtr');
				$inputs['bill_doc_path'] = $this->request->getVar('bill_doc_path');
				$inputs['remark'] = $this->request->getVar('remark');
				
				foreach ($inputs['prop_demand_list'] as $demand_dtl) {
					if ($demand_dtl['paid_status']==0) {
						$inputs['from_fy_mstr_id'] = $demand_dtl['fy_mstr_id'];
						$inputs['from_qtr'] = $demand_dtl['qtr'];
						break;
					}
				}
				
				$inputs['total_amount'] = $this->modeldemand->getDemandDeactivatedTotalAmount($inputs)['amount'];
				$this->modeldemand->citizen_demand_dtl($inputs);

				$data['created_on'] = date("Y-m-d H:i:s");
				$session = session();
				$inputs['deactivated_by_emp_dtl_id'] = $session->get('emp_details')['id'];
				$demand_adjustment_id = $this->modeladjust->adjust_demand($inputs);
				$doc_file = $this->request->getFile('bill_doc_path');
				if ($doc_file->IsValid() && !$doc_file->hasMoved()) {
					$file_ext = $doc_file->getExtension();
					$ulb_dtl = $session->get('ulb_dtl');
					$city = $ulb_dtl['city'];
					$newFileNamee = md5($demand_adjustment_id).".".$file_ext;
					$path = "payment_adjust";
					if ($doc_file->move(WRITEPATH . 'uploads/'.$city.'/'.$path.'/', $newFileNamee )) {
						$doc_path = $city.'/'.$path."/".$newFileNamee;
						if ($this->modeladjust->update_doc_path($demand_adjustment_id, $doc_path)) {
							return $this->response->redirect(base_url('jsk/jsk_due_details/'.$prop_dtl_id));
						}
					}
				}
			}
			$data=$inputs;
			$inputs['prop_owner_detail'] = $this->modelowner->getPropOwnerDtlByProp_dtlId(['prop_dtl_id'=>$inputs['prop_dtl_id']]);
			$inputs['prop_floor_details'] = $this->modelfloor->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$inputs['prop_dtl_id']]);
            $inputs['prop_tax_list'] = $this->modeltax->getPropTaxDtlByPropDtlId(['prop_dtl_id'=>$inputs['prop_dtl_id']]);
			if ($fydemand = $this->modeldemand->fydemand($inputs['prop_dtl_id'])) {
				$inputs['fydemand'] = $fydemand;
			}

			$inputs['basic_details_data']=array(
				'ward_no'=> isset($inputs['ward_no'])?$inputs['ward_no']:'N/A',
				'new_holding_no'=> isset($inputs['new_holding_no'])?$inputs['new_holding_no']:'N/A',
				'new_ward_no'=> isset($inputs['new_ward_no'])?$inputs['new_ward_no']:'N/A',
				'holding_no'=> isset($inputs['holding_no'])?$inputs['holding_no']:'N/A',
				'assessment_type'=> isset($inputs['assessment_type'])?$inputs['assessment_type']:'N/A',
				'plot_no'=> isset($inputs['plot_no'])?$inputs['plot_no']:'N/A',
				'property_type'=> isset($inputs['property_type'])?$inputs['property_type']:'N/A',
				'area_of_plot'=> isset($inputs['area_of_plot'])?$inputs['area_of_plot']:'N/A',
				'ownership_type'=> isset($inputs['ownership_type'])?$inputs['ownership_type']:'N/A',
				'is_water_harvesting'=> isset($inputs['is_water_harvesting'])?$inputs['is_water_harvesting']:'N/A',
				'holding_type'=> isset($inputs['holding_type'])?$inputs['holding_type']:'N/A',
				'prop_address'=> isset($inputs['prop_address'])?$inputs['prop_address']:'N/A',
				'road_type'=> isset($inputs['road_type'])?$inputs['road_type']:'N/A',
				'zone_mstr_id'=> isset($inputs['zone_mstr_id'])?$inputs['zone_mstr_id']:'N/A',
				'entry_type'=> isset($inputs['entry_type'])?$inputs['entry_type']:'N/A',
				'flat_registry_date'=> isset($inputs['flat_registry_date'])?$inputs['flat_registry_date']:'N/A',
				'created_on'=> isset($inputs['created_on'])?$inputs['created_on']:'N/A',
				'prop_type_mstr_id'=> isset($inputs['prop_type_mstr_id'])?$inputs['prop_type_mstr_id']:'N/A',
				'appartment_name'=> isset($inputs['appartment_name'])?$inputs['appartment_name']:'N/A',
				'apt_code'=> isset($inputs['apt_code'])?$inputs['apt_code']:'N/A',
				'prop_type'=> 'prop'
	
			);
			// echo "<pre>";
			// print_var($inputs);
			// return;
			return view('property/demand_adjust', $inputs);
		}
	}

	public function ajax_gatequarter()
	{

		if ($this->request->getMethod() == 'post') {
			$data = [
				'fyUpto' => $this->request->getVar('upto_fy_mstr_id'),
				'prop_no' => $this->request->getVar('prop_dtl_id')
			];

			$result = $this->modeldemand->gateQuarter($data);

			if (!empty($result)) {
				$result = json_decode(json_encode($result), true);
				$option = "";
				$option .= "<option value=''>Select Quarter</option>";
				foreach ($result as $value) {
					$option .= "<option value='" . $value['qtr'] . "'>" . $value['qtr'] . "</option>";
				}
				$response = ['response' => true, 'data' => $option];
			} else {
				$response = ['response' => false];
			}
		} else {
			$response = ['response' => false];
		}
		echo json_encode($response);
	}
}
