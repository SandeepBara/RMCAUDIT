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
use App\Models\model_tc_call;
use App\Models\model_cheque_details;
use App\Models\model_collection;
use App\Models\model_emp_details;
use App\Models\model_saf_dtl;
use App\Models\model_transaction_fine_rebet_details;
use App\Models\model_bank_recancilation;
use App\Models\model_penalty_dtl;
use App\Models\model_view_ward_permission;
use App\Models\model_datatable;


class Mobi extends MobiController
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
	protected $model_tc_call;
	protected $model_cheque_details;
	protected $model_collection;
	protected $model_emp_details;
	protected $model_saf_dtl;
	protected $model_transaction_fine_rebet_details;

	protected $model;
	protected $modelUlb;
	protected $modelemp;
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
	protected $modelassess;
	protected $modelpenalty;
	protected $model_bank_recancilation;
	protected $model_penalty_dtl;
	protected $model_view_ward_permission;
	protected $model_datatable;
	protected $model_dttable;

	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper','form', 'utility_helper']);

        if($db_name = dbConfig("property")){

			$this->db = db_connect($db_name);
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system);
        }

		$this->model = new model_ward_mstr($this->dbSystem);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->modelemp = new model_emp_details($this->dbSystem);
		$this->modelfy = new model_fy_mstr($this->dbSystem);
		$this->model_tc_call = new model_tc_call($this->dbSystem);
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
		$this->modelassess = new model_saf_dtl($this->db);
		$this->modelpenalty = new model_transaction_fine_rebet_details($this->db);
		$this->model_bank_recancilation = new model_bank_recancilation($this->db);
		$this->model_penalty_dtl = new model_penalty_dtl($this->db);
		$this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
		$this->model_datatable = new model_datatable($this->db);
		$this->model_dttable = new model_datatable($this->db);
    }

	/*public function index()
	{


		return view('mobile/login');
	}
	*/

	public function home()
	{
		$data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data["user_type_mstr_id"]=$emp_mstr["user_type_mstr_id"];
		//$data['inbox_list'] = $this->model_tc_call->inbox_list($emp_details_id,$user_type_mstr_id);
		//print_r($data['inbox_list']);
		return view('mobile/index',$data);
	}

	public function inbox_details()
	{
		$data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $data["user_type_mstr_id"]=$user_type_mstr_id;

		$sql = "select tbl_tc_call.*,tbl_tc_call_inbox.subject from tbl_tc_call
			left join tbl_tc_call_inbox on tbl_tc_call_inbox.tc_call_id=tbl_tc_call.id
			where tbl_tc_call.status=1 and tbl_tc_call.ward_mst_id In(select ward_mstr_id
			from tbl_ward_permission where emp_details_id=".$emp_details_id.") and
			tbl_tc_call_inbox.to_user_type_id=".$user_type_mstr_id;
		//print_var($sql);die;
		$result = $this->model_datatable->getDatatable($sql);
		$data['posts'] = $result['result'];
		$data['pager'] = $result['count'];

		return view('mobile/inbox_details',$data);
	}

	public function accept_request()
	{
		$data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
		//print_r($emp_mstr);
        $data['emp_details_id'] = $emp_mstr["id"];
        $data['date_time'] = date('Y-m-d H:i:s');
		helper(['form']);
        if($this->request->getMethod()=='post')
		{
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$data['holding_no']=$inputs['holding_no'];
			$data['new_holding_no']=$inputs['new_holding_no'];
			$data['mobile_no']="91".$inputs["mobile_no"];
			$data['where']="(holding_no ='".$data['holding_no']."' or new_holding_no = '".$data['new_holding_no']."')";
			$accept_request = $this->model_tc_call->accept_request($data);
			//print_r($data);
			if($accept_request){
				$chatApiToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE2MjQ3MDcyMjMsInVzZXIiOiI5MTk1MDgyOTcxNzIifQ.upGRrPSbe8amWIiLWOs0YVWMuhrUuW0Jd3SBtSAYG5s"; // Get it from https://www.phphive.info/255/get-whatsapp-password/
				$number =$data['mobile_no']; // Number
				$message = "Your request is accepted. TC name *".$emp_mstr['emp_name']."* and mobile number ".$emp_mstr['personal_phone_no']." will visit on time."; // Message
				$curl = curl_init();
				curl_setopt_array($curl, array(
				CURLOPT_URL => 'http://chat-api.phphive.info/message/send/text',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS =>json_encode(array("jid"=> $number."@s.whatsapp.net", "message" => $message)),
				CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer '.$chatApiToken,
				'Content-Type: application/json'
				),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				//echo $response;
			}
		}
		return $this->response->redirect(base_url('Mobi/inbox_details/'));
	}

	public function accepted_history()
	{

		$data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['emp_details_id'] = $emp_mstr["id"];
		//print_r($data['emp_details_id']);
		helper(['form']);
        if($this->request->getMethod()=='post'){
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$data['holding_no']=$inputs['holding_no'];
			$data['new_holding_no']=$inputs['new_holding_no'];
			$data['remarks']=$inputs['visit_remarks'];
			$data['where']="(holding_no ilike '%".$data['holding_no']."%' or new_holding_no ilike '%".$data['new_holding_no']."%')";
			$data['close_process'] = $this->model_tc_call->close_process($data);
			return $this->response->redirect(base_url('Mobi/accepted_history/'));
		}

		$sql = "select tbl_tc_call.*,tbl_tc_call_inbox.accepted_date_time,tbl_tc_call_inbox.remarks,
			tbl_tc_call_inbox.subject from tbl_tc_call
			left join tbl_tc_call_inbox on tbl_tc_call_inbox.tc_call_id=tbl_tc_call.id
			where tbl_tc_call_inbox.accepted_by_emp_id=".$data['emp_details_id'];

		$result = $this->model_datatable->getDatatable($sql);
		$data['accepted_history'] = $result['result'];
		$data['pager'] = $result['count'];
		//print_r($data['accepted_history']);
		return view('mobile/accepted_history',$data);
	}

	public function mobileMenu($id=null)
	{

		$data=array();
		$Session = Session();
		$emp_mstr = $Session->get("emp_details");
		$data["user_type_mstr_id"]= $emp_mstr["user_type_mstr_id"];

        $data['id'] = $id;
		//print_r($data['id']);
        if($data['id']=="property"){
			return view('mobile/property', $data);
		}
		elseif($data['id']=="water"){
			return view('mobile/water', $data);
		}
		elseif($data['id']=="trade"){
			return view('mobile/trade', $data);
		}
		elseif($data['id']=="advertisment"){
			return view('mobile/advertisment', $data);
		}
	}



	public function search_Property_Tax()
	{
		$data =(array)null;
		$Session = Session();
        if($this->request->getMethod()=='post')
		{
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$data['ward_mstr_id']=$inputs['ward_mstr_id'];
			$data['keyword']=$inputs['keyword'];
			$where=null;
			if($data['ward_mstr_id']!="")
			{
				$where=" and ward_mstr_id=$data[ward_mstr_id]";
			}
			if($data['keyword']!="")
			{
				$where.=" and (mobile_no ilike '%".$data['keyword']."%' or holding_no ilike '%".$data['keyword']."%' or new_holding_no ilike '%".$data['keyword']."%' or owner_name ilike '%".$data['keyword']."%')";
			}

			$Session->set('where', $where);
			$Session->set('ward_mstr_id', $data['ward_mstr_id']);
			$Session->set('keyword', $data['keyword']);
		}
		return $this->response->redirect(base_url('Mobi/list_of_Property/'));
	}


	public function list_of_Property()
	{
		/* $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
		$data = [];
		$where = "";
		$data = $this->request->getVar();
		if (!empty($data)) {
			if(isset($data['ward_mstr_id']) && $data['ward_mstr_id']!="") {
				$where=" and ward_mstr_id=$data[ward_mstr_id]";
			}
			if(isset($data['keyword']) && $data['keyword']!="") {
				$where.=" and (mobile_no ilike '%".$data['keyword']."%' or holding_no ilike '%".$data['keyword']."%' or new_holding_no ilike '%".$data['keyword']."%' or owner_name ilike '%".$data['keyword']."%')";
			}
		}
		if ($where!="") {
			$sql = "SELECT
						tbl_prop_dtl.id AS prop_dtl_id,
						view_ward_mstr.ward_no,
						(CASE WHEN tbl_prop_dtl.new_holding_no='' THEN tbl_prop_dtl.holding_no ELSE tbl_prop_dtl.new_holding_no END) AS holding_no,
						owner_dtl.owner_name,
						owner_dtl.mobile_no,
						tbl_prop_dtl.prop_address,
						tbl_prop_dtl.khata_no,
						tbl_prop_dtl.plot_no,
						tbl_prop_dtl.status
					FROM tbl_prop_dtl
					LEFT JOIN view_ward_mstr ON tbl_prop_dtl.ward_mstr_id = view_ward_mstr.id
					INNER JOIN (SELECT prop_dtl_id, string_agg(owner_name::text, ','::text) AS owner_name, string_agg(mobile_no::text, ','::text) AS mobile_no FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id
					".$where;
								
			$result = $this->model_dttable->getDatatable($sql);
			$data['posts'] = $result['result'];
			$data['pager'] = $result['count'];
			$data['offset'] = $result['offset'];
		} */
		$data = $inputs = arrFilterSanitizeString($this->request->getVar());
		$session = Session();
		$emp_mstr = $session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
		
		if ((isset($data['forward']) && $data['forward'] != "") && (isset($data['cmd'])  && $data['cmd'] == "clr")) {
			$session->set('forward', $this->request->getVar('forward'));
			return $this->response->redirect(base_url('jsk/jsk_Search_Property'));
		}

		$wardWhere = "";
		$ownerWhere = "";
		$holdingWhere = "";
		if (isset($data['by_holding_owner_dtl']) && !in_array(strtoupper($data['by_holding_owner_dtl']), ["BY_15_HOLDING", "BY_HOLDING", "BY_OWNER", "BY_ADDRESS"])) {
			return $this->response->redirect(base_url('err/err'));
		}
		if (isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']!='') {
			if ($data['ward_mstr_id'] != null && $data['ward_mstr_id']!='') {
				$wardWhere = " AND ward_mstr_id='".$data['ward_mstr_id']."'";
			}
			if (strtoupper($data['by_holding_owner_dtl'])=="BY_HOLDING") {
				$holdingWhere = " WHERE UPPER(holding_no)='".strtoupper($data['keyword'])."'";
			} else if (strtoupper($data['by_holding_owner_dtl'])=="BY_15_HOLDING") {
				$holdingWhere = " WHERE UPPER(new_holding_no)='".strtoupper($data['keyword'])."'";
			} else if (strtoupper($data['by_holding_owner_dtl'])=="BY_ADDRESS") {
				$holdingWhere = " WHERE prop_address ILIKE '%" . $data['keyword'] . "%'";
			} else {
				$ownerWhere = " AND (mobile_no::TEXT ILIKE '%" . $data['keyword']."%' OR owner_name ILIKE '%".$data['keyword']."%')";
			}
		}
		if ($wardWhere !='' || $holdingWhere != '' || $ownerWhere != '') {
			//$sql = "select * from view_prop_dtl_owner_ward_prop_type_ownership_type where 1=1 " . $where;
			$sql = "SELECT 
						tbl_prop_dtl.id AS prop_dtl_id,
						tbl_prop_dtl.prop_address,
						tbl_prop_dtl.holding_no,
						tbl_prop_dtl.new_holding_no,
						tbl_prop_dtl.khata_no,
						tbl_prop_dtl.plot_no,
						prop_owner.owner_name,
						prop_owner.mobile_no,
						view_ward_mstr.ward_no,
						tbl_prop_dtl.status
					FROM tbl_prop_dtl
					INNER JOIN view_ward_mstr ON tbl_prop_dtl.ward_mstr_id = view_ward_mstr.id ".$wardWhere."
					INNER JOIN (
						SELECT tbl_prop_owner_detail.prop_dtl_id,
							string_agg((tbl_prop_owner_detail.owner_name)::text, ', '::text) AS owner_name,
							string_agg((tbl_prop_owner_detail.mobile_no)::text, ', '::text) AS mobile_no
						FROM tbl_prop_owner_detail WHERE status=1 ".$ownerWhere."
						GROUP BY tbl_prop_owner_detail.prop_dtl_id
					) AS prop_owner ON prop_owner.prop_dtl_id = tbl_prop_dtl.id
					".$holdingWhere." and tbl_prop_dtl.status=1";
			$result = $this->model_datatable->getDatatable($sql);
			$data['posts'] = $result['result'];
			$data['offset'] = $result['offset'];
			$data['pager'] = $result['count'];
		}
		//print_var($data['result']);
		$data['ward'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
		return view('mobile/Property/Property/Property_List', $data);
	}


	public function full($prop_dtl_id_md5)
	{
		$data=(array)null;
        $Session = Session();
        
        $prop = $this->modelprop->get_prop_full_details($prop_dtl_id_md5);
        $prop = $prop['get_prop_full_details'];
        $data=json_decode($prop, true);
		// print_var($data);
		// return;
		$this->cachePage(120);
		return view('mobile/Property/Property/full', $data);
	}
	
	public function confirm_payment($prop_dtl_id_md5)
	{
		$data =(array)null;
        $Session = Session();

		$prop = $this->modelprop->get_prop_full_details($prop_dtl_id_md5);
        $prop = $prop['get_prop_full_details'];
        $data=json_decode($prop, true);
		$data["fy_demand"] = $this->modeldemand->fydemand($data['prop_dtl_id']);

		return view('mobile/Property/Property/confirm_payment', $data);
	}

	public function confirm_payment_old($prop_dtl_id_md5)
	{
		$data =(array)null;
        $Session = Session();

		$ulb_mstr = $Session->get("ulb_dtl");
		$data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
		$emp_mstr = $Session->get("emp_details");
		$data['emp_details_id'] = $emp_mstr["id"];
		$data['id']=$prop_dtl_id_md5;

		$data['basic_details'] = $this->modelprop->basic_details($data);
		$data["prop_dtl_id"] = $data['basic_details']['prop_dtl_id'];
		$data['owner_details'] = $this->modelowner->owner_details($data);
		$data['tax_list'] = $this->modeltax->tax_list($data['prop_dtl_id']);
		$data["fy_demand"] = $this->modeldemand->fydemand($data['prop_dtl_id']);

		return view('mobile/Property/Property/confirm_payment', $data);
	}


	public function property_due_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		$data['basic_details'] = $this->modelprop->basic_details($data);
		$data['owner_details'] = $this->modelowner->owner_details($data);
		$data['tax_list'] = $this->modeltax->tax_list($data['basic_details']['prop_dtl_id']);
		$data['demand_detail'] = $this->modeldemand->demand_detail($data);
		return view('mobile/Property/Property/property_due_details',$data);
	}

	public function property_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		$data['basic_details'] = $this->modelprop->basic_details($data);
		if ( $owner_details = $this->modelowner->owner_details($data) ) {
			$data['owner_details'] = $owner_details;
		}
		if ( $tax_list = $this->modeltax->tax_list($data['basic_details']['prop_dtl_id']) ) {
			$data['tax_list'] = $tax_list;
		}
		if ( $demand_detail = $this->modeldemand->demand_detail($data) ) {
			$data['demand_detail'] = $demand_detail;
		}
		if ( $occupancy_detail = $this->modelfloor->occupancy_detail($data['basic_details']['prop_dtl_id']) ) {
			$data['occupancy_detail'] = $occupancy_detail;
		}
		if ( $payment_detail = $this->modelpay->jskProp_payment_detail($data['basic_details']['prop_dtl_id']) ) {
			$data['payment_detail'] = $payment_detail;
		}
		//print_r($data['payment_detail']);
		return view('mobile/Property/Property/property_details', $data);
	}

	public function payment_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		$data['basic_details'] = $this->modelprop->basic_details($data);
		$data['owner_details'] = $this->modelowner->owner_details($data);
		if ( $tax_list = $this->modeltax->tax_list($data['basic_details']['prop_dtl_id']) ) {
			$data['tax_list'] = $tax_list;
		}
		if ( $payment_detail = $this->modelpay->payment_detail($data['basic_details']['prop_dtl_id']) ) {
			$data['payment_detail'] = $payment_detail;
		}
		//print_r($data['payment_detail']);
		return view('mobile/Property/Property/payment_details', $data);
	}



	public function payment_tc_receipt($transaction_id)
	{
		$data =(array)null;
		$Session = Session();

		$data['transaction_id']=$transaction_id;
		$ulb_mstr = $Session->get("ulb_dtl");
		$ulb_mstr_id = $ulb_mstr['ulb_mstr_id'];
		$data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
		$data['tran_mode_dtl'] = $this->modelpay->getTrandtlList($data['transaction_id']);
		
		$data['citizenName'] = $this->modelprop->basic_details(["id"=> md5($data['tran_mode_dtl']["prop_dtl_id"])]);
		$data['citizenName']["owner_name"] = $this->modelowner->citizenName(md5($data['tran_mode_dtl']['prop_dtl_id']))["owner_name"];
		//print_var($data['citizenName']);
		if(in_array($data['tran_mode_dtl']['tran_mode'], ["CHEQUE", "DD"]))
		{
			$data['chqDD_details'] = $this->modelchqDD->mode_dtl(md5($data['tran_mode_dtl']["id"]));
			
		}
		$data['emp_dtls'] = $this->modelemp->emp_dtls($data['tran_mode_dtl']['tran_by_emp_details_id']);
		
		return view('mobile/Property/Property/payment_tax_receipt', $data);
	}


	public function reports_menu()
	{
		return view('mobile/report/reports_menu');
	}

	public function ajax_gatequarter()
    {
		if($this->request->getMethod()=='post'){
			$data = [
					'fyUpto' => $this->request->getVar('due_upto_year'),
					'prop_no' => $this->request->getVar('custm_id')
					];
			$result = $this->modeldemand->gateQuarter($data);
			$totalQuarter = $this->modeldemand->gatetotalQuarter($data);
			//$lasttotalQuarter = $this->modeldemand->gatelasttotalQuarter($data);
			if(!empty($result)){
				$option = "";
				$option .= "<option value=''>Select Quarter</option>";
				foreach ($result as $value) {
					$option .= "<option value='".$value['qtr']."'>".$value['qtr']."</option>";
				}
				$lasttotalQuarter = $value['qtr'];
				$response = ['response'=>true, 'data'=>$option, 'val'=>$totalQuarter['totalqtr'], 'last'=>$lasttotalQuarter];
			}else{
				$response = ['response'=>false];
			}
		}else{
			$response = ['response'=>false];
		}
		echo json_encode($response);

    }


	public function ajax_rungatequarter()
    {
		if($this->request->getMethod()=='post'){
			$data = [
					'fyUpto' => $this->request->getVar('due_upto_year'),
					'prop_no' => $this->request->getVar('custm_id')
					];
			$result = $this->modeldemand->gateQuarter($data);
			$lastquatr = $this->modeldemand->gateQuarterlast($data);
			$totalQuarter = $this->modeldemand->gatetotalQuarter($data);
			//$lasttotalQuarter = $this->modeldemand->gatelasttotalQuarter($data);
			if(!empty($result)){
				$option = "";
				$option .= "<option value='".$lastquatr['qtr']."'>".$lastquatr['qtr']."</option>";
				foreach ($result as $value) {
					$option .= "<option value='".$value['qtr']."'>".$value['qtr']."</option>";
				}
				$lasttotalQuarter = $value['qtr'];
				$response = ['response'=>true, 'data'=>$option, 'val'=>$totalQuarter['totalqtr'], 'last'=>$lasttotalQuarter];
			}else{
				$response = ['response'=>false];
			}
		}else{
			$response = ['response'=>false];
		}
		echo json_encode($response);

    }



}
