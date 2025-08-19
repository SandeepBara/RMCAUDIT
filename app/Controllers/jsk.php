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
use App\Models\model_adjustment_mstr;
use App\Models\model_visiting_dtl;
use App\Models\model_view_ward_mapping_mstr;
use App\Models\Property\PropPhysicalVerification;
use App\Models\Property\PropPhysicalVerificationFloor;
use App\Traits\PropertyTrait;
use Exception;

class jsk extends AlphaController
{
	
	use PropertyTrait;
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
	protected $modeltax;
	protected $modeldemand;
	protected $modelpay;
	protected $modeladjustment;
	protected $model_advance_mstr;
	protected $model_adjustment_mstr;
	protected $modelowner;
	protected $modelpropcoll;
	protected $modelassess;
	protected $modelchqDD;
	protected $model_visiting_dtl;

	protected $model_view_ward_mapping_mstr;
	protected $PropPhysicalVerification;
	protected $PropPhysicalVerificationFloor;
	
	public function __construct()
	{
		parent::__construct();
		helper(['db_helper', 'qr_code_generator_helper', 'utility_helper','form_helper',"sms_helper",'php_office_helper']);
		if ($db_name = dbConfig("property")) {
			$this->db = db_connect($db_name);
		}
		if ($db_system = dbSystem()) {
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
		$this->model_advance_mstr = new model_advance_mstr($this->db);
		$this->model_adjustment_mstr = new model_adjustment_mstr($this->db);
		$this->model_datatable = new model_datatable($this->db);
		$this->model_system_name = new model_system_name($this->dbSystem);
		$this->model_visiting_dtl = new model_visiting_dtl($this->dbSystem);
		$this->model_view_ward_mapping_mstr = new model_view_ward_mapping_mstr($this->dbSystem);

		$this->PropPhysicalVerification = new PropPhysicalVerification($this->db);
		$this->PropPhysicalVerificationFloor = new PropPhysicalVerificationFloor($this->db);
	}

	function __destruct() {
		if(isset($this->db)) $this->db->close();
		if(isset($this->dbSystem)) $this->dbSystem->close();
	}

	public function propertyPaymentProceed($prop_dtl_id_MD5)
	{
		$data=(array)null;
        $Session = Session();
        
        $prop = $this->modelprop->get_prop_full_details($prop_dtl_id_MD5);
        // return;
        $prop = $prop['get_prop_full_details'];
        $data=json_decode($prop, true);
		$data["fy_demand"] = $this->modeldemand->fydemand($data['prop_dtl_id']);

		$data["ulb"] = $Session->get("ulb_dtl");
		$data["emp_details"] = $Session->get("emp_details");
		//$this->cachePage(30);
		return view('property/jsk/propertyPaymentProceed', $data);
	}

	public function Ajax_getTCQtr()
	{
		$response = ['response' => false];
		if ($this->request->getMethod() == 'post') {
			$data = [
				'fy_mstr_id' => $this->request->getVar('fy_mstr_id'),
				'prop_dtl_id' => $this->request->getVar('prop_dtl_id'),
			];
			$result = $this->modeldemand->getDistinctQtr($data);
			$option = null;
			if (!empty($result)) {
				foreach ($result as $value) {
					$option .= '<option value="' . $value['qtr'] . '">' . $value['qtr'] . '</option>';
					break;
				}
			}
			$response = ['response' => true, 'data' => $option];
		}
		echo json_encode($response);
	}


	public function Ajax_getQtr()
	{
		$response = ['response' => false];
		if ($this->request->getMethod() == 'post') {
			$data = [
				'fy_mstr_id' => $this->request->getVar('fy_mstr_id'),
				'prop_dtl_id' => $this->request->getVar('prop_dtl_id'),
			];
			$result = $this->modeldemand->getDistinctQtr($data);
			$option = null;
			if (!empty($result)) {
				foreach ($result as $value) {
					$option .= '<option value="' . $value['qtr'] . '">' . $value['qtr'] . '</option>';
					//break;
				}
			}
			$response = ['response' => true, 'data' => $option];
		}
		echo json_encode($response);
	}

	public function Ajax_getPropPayableAmount_old()
	{
		$Session = Session();
		$emp_mstr = $Session->get("emp_details");
		$user_id = $emp_mstr["id"];

		$response = ['response' => false];
		if ($this->request->getMethod() == 'post') {
			$input = [
				'fy' => $this->request->getVar('fy'),
				'qtr' => $this->request->getVar('qtr'),
				'prop_dtl_id' => $this->request->getVar('prop_dtl_id'),
				'user_id' => $user_id,
			];
			$data = $this->modeldemand->getPropDemandAmountDetails($input);
			$out = '<tr>
						<td class="pull-right">Demand Amount</td>
						<td>' . $data['DemandAmount'] . '</td>
						<td class="pull-right">Rebate</td>
						<td>' . $data['RebateAmount'] . '</td>
					</tr>
					<tr>
						<td class="pull-right"></td>
						<td></td>
						<td class="pull-right">Special Rebate</td>
						<td>' . $data['SpecialRebateAmount'] . '</td>
					</tr>
					<tr>
						<td class="pull-right">Other Penalty</td>
						<td>' . $data['OtherPenalty'] . '</td>
						<td class="pull-right">	1 % Interest </td>
						<td>' . $data['OnePercentPnalty'] . '</td>
					</tr>
					<tr>
						<td class="pull-right">Advance</td>
						<td>' . $data['AdvanceAmount'] . '</td>
						<td class="pull-right text-success">Total Paybale Amount</td>
						<td class="text-success" id="total_payable_amount_temp">' . round($data['PayableAmount']) . '.00</td>
					</tr>';
			$response = ['response' => true, 'data' => $data, 'html_data' => $out];
		}
		echo json_encode($response);
	}

	public function Ajax_getPropPayableAmount()
	{
		$Session = Session();
		$emp_mstr = $Session->get("emp_details");
		$user_id = $emp_mstr["id"];

		$response = ['response' => false];
		if ($this->request->getMethod() == 'post') {
			$input = [
				'fy' => $this->request->getVar('fy'),
				'qtr' => $this->request->getVar('qtr'),
				'prop_dtl_id' => $this->request->getVar('prop_dtl_id'),
				'user_id' => $user_id,
			];
			$data = $this->modeldemand->getPropDemandAmountDetails($input);
			$out = '<tr>
						<td class="pull-right">Demand Amount</td>
						<td>' . $data['DemandAmount'] . '</td>
						<td class="pull-right">Rebate</td>
						<td>' . $data['RebateAmount'] . '</td>
					</tr>
					<tr>
						<td class="pull-right"></td>
						<td></td>
						<td class="pull-right">Special Rebate</td>
						<td>' . $data['SpecialRebateAmount'] . '</td>
					</tr>
					<tr>
						<td class="pull-right">Other Penalty</td>
						<td>' . $data['OtherPenalty'] . '</td>
						<td class="pull-right">	1 % Interest </td>
						<td>' . $data['OnePercentPnalty'] . '</td>
					</tr>
					<tr>
						<td class="pull-right">	
							<i class="fa fa-info-circle" data-placement="bottom" data-toggle="modal" data-target="#forward_backward_model" title="Penalty Calculation Rule">
							</i> 							
							Notice Penalty 
						</td>
						<td>' . $data['noticePenalty'] . '</td>
						<td class="pull-right">2% Addition Penalty</td>
						<td>' . $data['noticePenaltyTwoPer'] . '</td>
						
					</tr>
					<tr>
						<td class="pull-right">Advance</td>
						<td>' . $data['AdvanceAmount'] . '</td>
						<td class="pull-right text-success">Total Paybale Amount</td>
						<td class="text-success" id="total_payable_amount_temp">' . round($data['PayableAmount']) . '.00</td>
					</tr>';

			$model='
					<div style="font-size:small;">
						<!-- models -->
						
						<!-- ==================== -->
						<div id="forward_backward_model" class="modal fade" role="dialog" >
							<div class="modal-dialog text-center" style=" width:60%">
								<!-- Modal content-->
								<div class="modal-content">
									<div class="modal-header" style="background-color: #25476a;">
										<button type="button" class="close" style="color: white; font-size:30px;" data-dismiss="modal">&times;</button>
										<h4 class="modal-title" style="color: white;text-align:center;"><span id="action_title"> Notice Penalty Calculation</span> </h4>
									</div>
									
									<div class="modal-body">                               
										<div class="row">
											<div class="col-md-12 has-success pad-btm">
												<a class="btn btn-primary btn-sm pull-right" href="'.base_url("/public/assets/Rules/property_notice_rule.pdf").'" target="blank">Download</a>
												<br>
												<img style="width:100%; height:100%;" src="'.base_url("/public/assets/Rules/property_notice_rule.png").'"/>
											</div>
											<div class="col-md-12 has-success pad-btm">
												
												<table class="tbl tbl-border" style="width:100%;">
													<tr>
														<td class="pull-right">Arrear Demand : </td>
														<td> <strong>' . $data["noticePenaltyBifurcation"]["ArrearDemand"] . '</strong></td>
														<td class="pull-right">	1 % Interest : </td>
														<td> <strong>' . $data["noticePenaltyBifurcation"]['OnePercentPnalty'] . '</strong> </td>
													</tr>
													<tr>
														<td class="pull-right">Notice Receiving Date</td>
														<td> <strong>' . $data["noticePenaltyBifurcation"]["NoticeServedDate"] . '</strong></td>
														<td class="pull-right">Notice Date : </td>
														<td> <strong>' . $data["noticePenaltyBifurcation"]["NoticeDate"] . '</strong></td>
														
													</tr>
													<tr>
														<td class="pull-right">Day Diff : </td>
														<td> <strong>' . $data["noticePenaltyBifurcation"]["DayDiff"] . '</strong></td>
														<td class="pull-right">	Notice Penalty Apply : </td>
														<td> <strong>' . (($data["noticePenaltyBifurcation"]['noticePer']??0)*100) . ' % </strong></td>
													</tr>
													<tr>
														<td class="pull-right">	Notice Penalty : </td>
														<td> <strong>' . $data["noticePenaltyBifurcation"]['noticePenalty'] . '</strong></td>																
														<td class="pull-right"> 2% Addition Penalty	 : </td>
														<td><strong>' . $data["noticePenaltyBifurcation"]['noticePenaltyTwoPer'] . '</strong></td>	
													</tr>
												<table>
											</div>                                    
										</div>
										<div class="row">
											<div class="col-md-4">
											</div>
											<div class="col-md-4">
												
											</div>                                    
										</div>
									</div>

								</div>
							</div>
						</div>
						<!-- =================== -->
						
						<!-- end models -->
					</div>			
			';
			$response = ['response' => true, 'data' => $data, 'html_data' => $out,"html_model_data"=>$model];
		}
		echo json_encode($response);
	}


	public function Ajax_prop_pay_now()
	{
		$Session = Session();
		$emp_mstr = $Session->get("emp_details");

		$user_id = $emp_mstr["id"];
		$user_type_mstr_id = $emp_mstr["user_type_mstr_id"];

		$response = ['response' => false];
		$inputs = filterSanitizeStringtoUpper($this->request->getVar());
		if ($this->request->getMethod() == 'post') {
			$cheque_dtl = (array)null;
			$data = [
				"prop_dtl_id" => $inputs["prop_dtl_id"],
				"fy" => $inputs["fy"],
				"qtr" => $inputs["qtr"],
				"user_id" => $user_id,
				"payment_mode" => $inputs["payment_mode"],
				"remarks" => $inputs["remarks"],
				"total_payable_amount" => $inputs["total_payable_amount"],
			];


			if (in_array($inputs["payment_mode"], ["CHEQUE", "DD","NEFT"]))
				$cheque_dtl = [
					"bank_name" => $inputs["bank_name"],
					"branch_name" => $inputs["branch_name"],
					"cheque_no" => $inputs["cheque_no1"],
					"cheque_date" => $inputs["cheque_date"],
				];

			
			// $this->db->transBegin();
			$trxn_id = $this->modelpay->prop_pay_now($data, $cheque_dtl);
			//$this->db->transRollback();
			if ($trxn_id) {
				$this->PaymentSms($trxn_id);
				if ($user_type_mstr_id == 5) //Agency Tax Collector
				{
					$application = $this->modelprop->getPropIdHodingNoWardByMD5ID(["id"=>md5($inputs["prop_dtl_id"])]);							
					$vistingRepostInput = propTranVisit($application,$trxn_id??0,$this->request->getVar());          
					$visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);
					$redirect_to = base_url() . "/mobi/payment_tc_receipt/" . md5($trxn_id);
				}
				else
					$redirect_to = base_url() . "/jsk/payment_jsk_receipt/" . md5($trxn_id);

				if ($trxn_id)
					$response = [
						'response' => true,
						'url' => $redirect_to,
					];
			} else {
				$response = [
					'response' => false,
					'message' => "Sorry, Trxn could not complete",
				];
			}
		}

		echo json_encode($response);
	}

	public function PaymentSms($tranId){
		$tran = $this->db->query("select * from tbl_transaction where id =".$tranId)->getFirstRow("array");
		$propOWners = $this->db->query("select tbl_prop_dtl.id,tbl_prop_dtl.holding_no,tbl_prop_dtl.new_holding_no,
												tbl_prop_owner_detail.owner_name,tbl_prop_owner_detail.mobile_no 
											from tbl_prop_dtl 
											join tbl_prop_owner_detail 
												on tbl_prop_owner_detail.prop_dtl_id = tbl_prop_dtl.id 
											where tbl_prop_dtl.id = ".$tran["prop_dtl_id"])
											->getResultArray();
		foreach($propOWners as $val){
			$val["amount"] = $tran["payable_amt"];
			$val["from_fyear"] = $tran["from_fyear"];
			$val["from_qtr"] = $tran["from_qtr"];
			$val["upto_fyear"] = $tran["upto_fyear"];
			$val["upto_qtr"] = $tran["upto_qtr"];
			$val["emp_id"] = $tran["tran_by_emp_details_id"];
			$val["new_holding_no"] = trim($val["new_holding_no"])?$val["new_holding_no"]:$val["holding_no"];
			$template = Property($val,"Holding Payment");
			if($template["status"]){
				$smsInput=[
					"emp_id"=>$val["emp_id"],
					"ref_id"=>$val["id"],
					"ref_type"=>"tbl_prop_dtl",
					"mobile_no"=>$val["mobile_no"],
					"purpose"=>"Holding Payment",
					"template_id"=>$template["temp_id"],
					"message"=>$template["sms"],
				];
				$smsId = insert_sms_log($this->db,"tbl_sms_log",$smsInput);
				$respnon = send_sms($smsInput["mobile_no"],$smsInput["message"],$smsInput["template_id"]);				
				if($smsId){
					$where = ["id"=>$smsId];
					$updateData = ['response' => $respnon['response'], 'smgid' => $respnon['msg']];
					update_sms_log($this->db,"tbl_sms_log",$where,$updateData);
				}
			}
		}
	}


	public function jsk_Property_Tax()
	{
		$data = (array)null;
		$Session = Session();
		if ($this->request->getVar('cmd') && $this->request->getVar('cmd') == "clr") {
			$Session->remove('keyword');
			$Session->remove('ward_mstr_id');
			$Session->remove('where');
		}
		if ($this->request->getVar('forward') && $this->request->getVar('forward') != "") {
			$Session->set('forward', $this->request->getVar('forward'));
		}

		if ($this->request->getMethod() == 'post') {
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$data['keyword'] = $inputs['keyword'];
			$data['ward_mstr_id'] = $inputs['ward_mstr_id'];
			if (($data['ward_mstr_id'] != null || $data['ward_mstr_id']!='')  && ($data['keyword'] != null || $data['keyword'] != '')) {
				$where = " and old_ward_mstr_id='" . $data['ward_mstr_id'] . "'" . " and (mobile_no ilike '%" . $data['keyword'] . "%' or holding_no ilike '%" . $data['keyword'] . "%' or new_holding_no ilike '%" . $data['keyword'] . "%' or owner_name ilike '%" . $data['keyword'] . "%' or prop_address ilike '%" . $data['keyword'] . "%')";
			} else if (($data['ward_mstr_id'] != null || $data['ward_mstr_id']!='') && ($data['keyword'] == null || $data['keyword'] == '')) {
				$where = "and old_ward_mstr_id='" . $data['ward_mstr_id'] . "'";
				// echo "inside ward only";
				// echo "<br/>".$where;
				// return;
			} else {
				$where = "and (mobile_no ilike '%" . $data['keyword'] . "%' or holding_no ilike '%" . $data['keyword'] . "%' or new_holding_no ilike '%" . $data['keyword'] . "%' or owner_name ilike '%" . $data['keyword'] . "%' or prop_address ilike '%" . $data['keyword'] . "%')";
			}

			$Session->set('keyword', $inputs['keyword']);
			$Session->set('ward_mstr_id', $inputs['ward_mstr_id']);
			$Session->set('where', $where);
		}

		return $this->response->redirect(base_url('jsk/jsk_Search_Property'));
	}

	public function jsk_Search_Property()
	{
		$data = $inputs = arrFilterSanitizeString($this->request->getVar());
		$session = Session();
		$uldDtl = $session->get("ulb_dtl");
		
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
				$holdingWhere = " WHERE new_holding_no='".strtoupper($data['keyword'])."'";
			} else if (strtoupper($data['by_holding_owner_dtl'])=="BY_ADDRESS") {
				$holdingWhere = " WHERE prop_address ~* '" . $data['keyword'] . "'";
			} else {
				$ownerWhere = " AND (mobile_no::TEXT ~* '" . $data['keyword']."' OR owner_name ~* '".$data['keyword']."' OR aadhar_no::text ~* '".$data['keyword']."')";
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
					".$holdingWhere;
			$result = $this->model_datatable->getDatatable($sql);
			$data['result'] = $result['result'];
			$data['offset'] = $result['offset'];
			$data['pager'] = $result['count'];
		}
		//$data['ward'] = $this->model->getWardList(['ulb_mstr_id' => $uldDtl["ulb_mstr_id"]]);
		$data['ward'] = $this->model->getWardListWithSession(['ulb_mstr_id' => $uldDtl["ulb_mstr_id"]], $session);
		$data["forward"] = $session->get('forward');
		return view('property/jsk/jsk_Property_List', $data);
	}

	public function holding_demand_print_old($prop_id = null) {
		if ($prop_id!=null) {
			$sql = "SELECT
						tbl_prop_dtl.id,
						view_ward_mstr.ward_no,
						new_ward.ward_no AS new_ward_no,
						tbl_prop_dtl.holding_no,
						tbl_prop_dtl.new_holding_no,
						tbl_prop_dtl.prop_address,
						owner_dtl.owner_name,
						owner_dtl.mobile_no,
						tax_dtl.tax_dtl_temp,
						demand_dtl.demand_dtl_temp,
						demand_dtl.t_balance,
						demand_dtl.additional_amount,
						demand_dtl.adjust_amt
					FROM tbl_prop_dtl
					INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
					left JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_prop_dtl.new_ward_mstr_id
					INNER JOIN (
						SELECT
							prop_dtl_id,
							STRING_AGG(owner_name, ',') AS owner_name,
							STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
						FROM tbl_prop_owner_detail
						GROUP BY prop_dtl_id
					) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id
					INNER JOIN (
						SELECT 
							prop_dtl_id,
							json_agg(json_build_object('qtr', qtr, 'fy_mstr_id', fy_mstr_id, 'fyear', fyear, 'quarterly_tax', (holding_tax+water_tax+education_cess+health_cess+latrine_tax+additional_tax)) ORDER BY id ASC) AS tax_dtl_temp
						FROM tbl_prop_tax
						GROUP BY prop_dtl_id
					) AS tax_dtl ON tax_dtl.prop_dtl_id=tbl_prop_dtl.id
					LEFT JOIN (
						SELECT 
							prop_dtl_id,
							json_agg(json_build_object('due_date', due_date, 'qtr', qtr, 'fy_mstr_id', fy_mstr_id, 'fyear', fyear, 'amount', amount, 'balance', balance, 'demand_amount', demand_amount, 'additional_amount', additional_amount, 'adjust_amt', adjust_amt) ORDER BY due_date ASC) AS demand_dtl_temp,
							SUM(balance) AS t_balance,
							SUM(additional_amount) AS additional_amount,
							SUM(adjust_amt) AS adjust_amt
						FROM tbl_prop_demand
						WHERE status=1 AND paid_status=0
						GROUP BY prop_dtl_id
					) AS demand_dtl ON demand_dtl.prop_dtl_id=tbl_prop_dtl.id
					WHERE 
						tbl_prop_dtl.id='".$prop_id."'";
			if ($result = $this->db->query($sql)->getFirstRow("array")) {
				$result["tax_dtl"] = json_decode($result["tax_dtl_temp"], true);
				$uptoIndex = count($result["tax_dtl"])-1;
				$result["quarterly_tax"] = $result["tax_dtl"][$uptoIndex]["quarterly_tax"];

				$result["demand_dtl"] = json_decode($result["demand_dtl_temp"], true);
				$result["demand_from_qtr"] = $result["demand_dtl"][0]["qtr"];
				$result["demand_from_fy"] = $result["demand_dtl"][0]["fyear"];
				$uptoIndex = count($result["demand_dtl"])-1;
				$result["demand_upto_qtr"] = $result["demand_dtl"][$uptoIndex]["qtr"];
				$result["demand_upto_fy"] = $result["demand_dtl"][$uptoIndex]["fyear"];
				
				$input = [
					'fy' => $result["demand_upto_fy"],
					'qtr' => $result["demand_upto_qtr"],
					'prop_dtl_id' => $result["id"],
					'user_id' => 2,
				];
				$result["payment_dtl"] = $this->modeldemand->getPropDemandAmountDetails($input);
				//print_var($result);
				//$this->cachePage(30);
				return view('property/demand_receipt', $result);
			} else {
				echo "invalid";
			}
		}
	}

	public function holding_demand_print($prop_id = null) {
		if ($prop_id!=null) {
			$sql = "SELECT
						tbl_prop_dtl.id,
						view_ward_mstr.ward_no,
						new_ward.ward_no AS new_ward_no,
						tbl_prop_dtl.holding_no,
						tbl_prop_dtl.new_holding_no,
						tbl_prop_dtl.prop_address,
						owner_dtl.owner_name,
						owner_dtl.mobile_no,
						owner_dtl.email,
						tax_dtl.tax_dtl_temp,
						demand_dtl.demand_dtl_temp,
						demand_dtl.t_balance,
						demand_dtl.additional_amount,
						demand_dtl.adjust_amt
					FROM tbl_prop_dtl
					INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
					left JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_prop_dtl.new_ward_mstr_id
					INNER JOIN (
						SELECT
							prop_dtl_id,
							STRING_AGG(owner_name, ',') AS owner_name,
							STRING_AGG(mobile_no::TEXT, ',') AS mobile_no,
							STRING_AGG(email::TEXT, ',') AS email
						FROM tbl_prop_owner_detail
						GROUP BY prop_dtl_id
					) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id
					INNER JOIN (
						SELECT 
							prop_dtl_id,
							json_agg(json_build_object('qtr', qtr, 'fy_mstr_id', fy_mstr_id, 'fyear', fyear, 'quarterly_tax', (holding_tax+water_tax+education_cess+health_cess+latrine_tax+additional_tax)) ORDER BY id ASC) AS tax_dtl_temp
						FROM tbl_prop_tax
						GROUP BY prop_dtl_id
					) AS tax_dtl ON tax_dtl.prop_dtl_id=tbl_prop_dtl.id
					LEFT JOIN (
						SELECT 
							prop_dtl_id,
							json_agg(json_build_object('due_date', due_date, 'qtr', qtr, 'fy_mstr_id', fy_mstr_id, 'fyear', fyear, 'amount', amount, 'balance', balance, 'demand_amount', demand_amount, 'additional_amount', additional_amount, 'adjust_amt', adjust_amt) ORDER BY due_date ASC) AS demand_dtl_temp,
							SUM(balance) AS t_balance,
							SUM(additional_amount) AS additional_amount,
							SUM(adjust_amt) AS adjust_amt
						FROM tbl_prop_demand
						WHERE status=1 AND paid_status=0
						GROUP BY prop_dtl_id
					) AS demand_dtl ON demand_dtl.prop_dtl_id=tbl_prop_dtl.id
					WHERE 
						tbl_prop_dtl.id='".$prop_id."'";
			if ($result = $this->db->query($sql)->getFirstRow("array")) {				
				$result["tax_dtl"] = json_decode($result["tax_dtl_temp"], true);
				$uptoIndex = count($result["tax_dtl"])-1;
				$result["quarterly_tax"] = $result["tax_dtl"][$uptoIndex]["quarterly_tax"];

				$result["demand_dtl"] = json_decode($result["demand_dtl_temp"], true);
				
				
				$result["demand_from_qtr"] = $result["demand_dtl"][0]["qtr"];
				$result["demand_from_fy"] = $result["demand_dtl"][0]["fyear"];
				$uptoIndex = count($result["demand_dtl"])-1;
				$result["demand_upto_qtr"] = $result["demand_dtl"][$uptoIndex]["qtr"];
				$result["demand_upto_fy"] = $result["demand_dtl"][$uptoIndex]["fyear"];
				
				$input = [
					'fy' => $result["demand_upto_fy"],
					'qtr' => $result["demand_upto_qtr"],
					'prop_dtl_id' => $result["id"],
					'user_id' => 2,
				];
				$result["payment_dtl"] = $this->modeldemand->getPropDemandAmountDetails($input);
				// print_var($result["payment_dtl"]);
				$arrear = array_filter($result["demand_dtl"],function($val){
					return ($val["fyear"]<getFY()) ?true:false;
				});
				$current = array_filter($result["demand_dtl"],function($val){
					return $val["fyear"] == getFY() ? true:false ;
				});
				if($current){
					$result["current"] = [
						"demand_from_fy"=> min(array_column($current, 'fyear')),
						"demand_from_qtr"=>min(array_column($current, 'qtr')),
						"demand_upto_fy"=> max(array_column($current, 'fyear')),
						"demand_upto_qtr"=>max(array_column($current, 'qtr')),
						"qtr_tax"=>array_sum(array_column($current, 'balance')) - array_sum(array_column($current, 'additional_amount')),
						"additional_amount"=>array_sum(array_column($current, 'additional_amount')),
						"balance"=>array_sum(array_column($current, 'balance')),
						"total_qtr"=>count(array_column($current, 'qtr')),
					];
				}
				if($arrear){
					$result["arrear"] = [
						"demand_from_fy"=> min(array_column($arrear, 'fyear')),
						"demand_from_qtr"=>min(array_column($arrear, 'qtr')),
						"demand_upto_fy"=> max(array_column($arrear, 'fyear')),
						"demand_upto_qtr"=>max(array_column($arrear, 'qtr')),
						"qtr_tax"=>array_sum(array_column($arrear, 'balance')) - array_sum(array_column($arrear, 'additional_amount')),
						"additional_amount"=>array_sum(array_column($arrear, 'additional_amount')),
						"balance"=>array_sum(array_column($arrear, 'balance')),
						"total_qtr"=>count(array_column($arrear, 'qtr')),
					];
				}
				// print_var($result);die;
				//$this->cachePage(30);
				return view('property/demand_receipt2', $result);
			} else {
				echo "invalid";
			}
		}
	}


	public function jsk_due_details($prop_dtl_id_MD5)
	{
		$data = (array)null;
		$Session = Session();

		$prop = $this->modelprop->get_prop_full_details($prop_dtl_id_MD5);
		$prop = $prop['get_prop_full_details'];
		$data = json_decode($prop, true);

		//print_var($data);
		$data['paid_demand'] = $this->modeldemand->getpaidid_by_propdtlid($data['prop_dtl_id']);
		$data['demand_upd_exist'] = $this->model_legacy_demand_update->getid_by_propdtlid($data['prop_dtl_id']);

		$data["ulb"] = $Session->get("ulb_dtl");
		$emp_mstr = $Session->get("emp_details");
		$data['user_type_id'] = $emp_mstr["user_type_mstr_id"];
		$data['prop_dtl_id_MD5'] = $prop_dtl_id_MD5;

		$data['basic_details_data']=array(
            'ward_no'=> isset($data['ward_no'])?$data['ward_no']:'N/A',
            'new_holding_no'=> isset($data['new_holding_no'])?$data['new_holding_no']:'N/A',
            'new_ward_no'=> isset($data['new_ward_no'])?$data['new_ward_no']:'N/A',
            'holding_no'=> isset($data['holding_no'])?$data['holding_no']:'N/A',
            'assessment_type'=> isset($data['assessment_type'])?$data['assessment_type']:'N/A',
            'plot_no'=> isset($data['plot_no'])?$data['plot_no']:'N/A',
            'property_type'=> isset($data['property_type'])?$data['property_type']:'N/A',
            'area_of_plot'=> isset($data['area_of_plot'])?$data['area_of_plot']:'N/A',
            'ownership_type'=> isset($data['ownership_type'])?$data['ownership_type']:'N/A',
            'is_water_harvesting'=> isset($data['is_water_harvesting'])?$data['is_water_harvesting']:'N/A',
            'holding_type'=> isset($data['holding_type'])?$data['holding_type']:'N/A',
            'prop_address'=> isset($data['prop_address'])?$data['prop_address']:'N/A',
            'road_type'=> isset($data['road_type'])?$data['road_type']:'N/A',
            'zone_mstr_id'=> isset($data['zone_mstr_id'])?$data['zone_mstr_id']:'N/A',
            'entry_type'=> isset($data['entry_type'])?$data['entry_type']:'N/A',
            'flat_registry_date'=> isset($data['flat_registry_date'])?$data['flat_registry_date']:'N/A',
            'created_on'=> isset($data['created_on'])?$data['created_on']:'N/A',
            'prop_type_mstr_id'=> isset($data['prop_type_mstr_id'])?$data['prop_type_mstr_id']:'N/A',
            'appartment_name'=> isset($data['appartment_name'])?$data['appartment_name']:'N/A',
            'apt_code'=> isset($data['apt_code'])?$data['apt_code']:'N/A',
            'prop_type'=> 'prop'

        );
		// print_var($data);
		// return;
		//$this->cachePage(60);
		return view('property/jsk/jsk_due_details', $data);
	}

	public function jsk_property_details($id = null)
	{
		$data = (array)null;
		$data['id'] = $id;
		//print_r($data);
		$data['basic_details'] = $this->modelprop->basic_details($data);
		if ($owner_details = $this->modelowner->owner_details($data)) {
			$data['owner_details'] = $owner_details;
		}
		if ($tax_list = $this->modeltax->tax_list($data['basic_details']['prop_dtl_id'])) {
			$data['tax_list'] = $tax_list;
		}
		if ($demand_detail = $this->modeldemand->demand_detail($data)) {
			$data['demand_detail'] = $demand_detail;
		}
		if ($occupancy_detail = $this->modelfloor->occupancy_detail($data['basic_details']['prop_dtl_id'])) {
			$data['occupancy_detail'] = $occupancy_detail;
		}
		if ($payment_detail = $this->modelpay->jskProp_payment_detail($data['basic_details']['prop_dtl_id'])) {
			$data['payment_detail'] = $payment_detail;
		}
		$data['paid_demand'] = $this->modeldemand->getpaidid_by_propdtlid($data['basic_details']['prop_dtl_id']);
		$data['demand_upd_exist'] = $this->model_legacy_demand_update->getid_by_propdtlid($data['basic_details']['prop_dtl_id']);
		//print_r($data['basic_details']);
		return view('property/jsk/jsk_property_details', $data);
	}

	public function jsk_payment_details($id = null)
	{
		$data = (array)null;
		$data['id'] = $id;
		$data['basic_details'] = $this->modelprop->basic_details($data);
		$data['owner_details'] = $this->modelowner->owner_details($data);
		if ($tax_list = $this->modeltax->tax_list($data['basic_details']['prop_dtl_id'])) {
			$data['tax_list'] = $tax_list;
		}
		if ($payment_detail_prop = $this->modelpay->payment_detail($data['basic_details']['prop_dtl_id'])) {
			$data['payment_detail'] = $payment_detail_prop;
		}
		if ($payment_detail_saf = $this->modelpay->payment_detail_saf($data['basic_details']['saf_dtl_id'])) {
			$data['payment_detail_saf'] = $payment_detail_saf;
		}
		$data['paid_demand'] = $this->modeldemand->getpaidid_by_propdtlid($data['basic_details']['prop_dtl_id']);
		$data['demand_upd_exist'] = $this->model_legacy_demand_update->getid_by_propdtlid($data['basic_details']['prop_dtl_id']);
		return view('property/jsk/jsk_payment_details', $data);
	}

	public function jsk_confirm_payment($id = null)
	{
		$data = (array)null;
		$Session = Session();

		$ulb_mstr = $Session->get("ulb_dtl");
		$data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
		$emp_mstr = $Session->get("emp_details");
		$data['emp_details_id'] = $emp_mstr["id"];
		$data['emp_type_id'] = $emp_mstr["user_type_mstr_id"];
		$data['id'] = $id;

		if ($id <> null) {
			helper(['form']);
			if ($this->request->getMethod() == 'post') {
				$this->db->transBegin();
				$data = [
					'custm_id' => $this->request->getVar('custm_id'),
					'due_upto_year' => $this->request->getVar('due_upto_year'),
					'date_upto_qtr' => $this->request->getVar('date_upto_qtr'),
					'total_rebate' => $this->request->getVar('total_rebate'),
					//'total_payabl' => $this->request->getVar('total_payabl'),
					'payment_mode' => $this->request->getVar('payment_mode'),
					'from_fy_year' => $this->request->getVar('from_fy_year'),
					'from_fy_qtr' => $this->request->getVar('from_fy_qtr'),
					'ful_qtr' => $this->request->getVar('ful_qtr'),
					'total_qrt' => $this->request->getVar('total_qrt'),
					'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
					'chq_date' => $this->request->getVar('chq_date'),
					'chq_no' => $this->request->getVar('chq_no'),
					'bank_name' => $this->request->getVar('bank_name'),
					'branch_name' => $this->request->getVar('branch_name'),
					'emp_details_id' => $emp_mstr["id"]

				];

				$session = session();
				$data['emp_details'] = $session->get('emp_details');
				$data['emp_type_id'] = $data['emp_details']["user_type_mstr_id"];
				$data['date'] = date('Y-m-d');
				$data["tran_by_type"] = "JSK";


				$from_fyear = $this->modelfy->getFyearByFyid($data['from_fy_year']);
				$data['from_fyear'] = $from_fyear['fy'];
				$upto_fyear = $this->modelfy->getFyearByFyid($data['due_upto_year']);
				$data['upto_fyear'] = $upto_fyear['fy'];
				if ($data['payment_mode'] == 1) {
					$data['tran_mode'] = "CASH";
				} elseif ($data['payment_mode'] == 2) {
					$data['tran_mode'] = "CHEQUE";
				} elseif ($data['payment_mode'] == 3) {
					$data['tran_mode'] = "DD";
				}

				$difference_Penalty = $this->model_penalty_dtl->difference_Penalty($data['custm_id']);
				if ($difference_Penalty['penalty_amt'] != "") {
					$difference_Penalty = $difference_Penalty['penalty_amt'];
				} else {
					$difference_Penalty = 0;
				}

				$data['difference_Penalty'] = $difference_Penalty;
				$data['bank_reCancel'] = $this->model_bank_recancilation->bank_reCancel($data['custm_id']);
				if ($data['bank_reCancel']['amount'] != "") {
					$bank_reCancel = $data['bank_reCancel']['amount'];
				} else {
					$bank_reCancel = 0;
				}

				$rebate_demand = 0;
				$dif_qtr = 0;
				$tol_mnth = $data['ful_qtr'] * 3;
				$j = 0;
				$crnt_dm = date('m');
				if ($crnt_dm == 01 || $crnt_dm == 02 || $crnt_dm == 03) {
					$crnt_dm = $crnt_dm + 9;
					$crnt_dm = (12 - $crnt_dm);
					$tol_mnth = $tol_mnth - $crnt_dm;
				} else {
					$crnt_dm = (12 - $crnt_dm) + 3;
					$tol_mnth = $tol_mnth - $crnt_dm;
				}
				$tol_mnths = $tol_mnth;
				//print_r($tol_mnths);
				//$data["total_qrt_pnlty"] = 0;
				$data["deman_am"] = 0;
				$data['tol_pently'] = 0;

				$data['demand_amn'] = $this->modeldemand->demand_amnt($data["custm_id"]);
				//print_r($data['demand_amn']);
				for ($i = 1; $i <= $data['total_qrt']; $i++) {
					$dem_am = $data['demand_amn'][$i - 1]["balance"];
					$dif_qtr = $dif_qtr + 3;
					$dem_fyids = $data['demand_amn'][$i - 1]["fy_id"];
					if ($dem_fyids >= 49) {
						if ($tol_mnth >= 3) {
							$each_penlty = ($dem_am / 100) * ($tol_mnths - $dif_qtr);
							if ($each_penlty > 0) {
								$data['tol_pently'] = $data['tol_pently'] + $each_penlty;
							} else {
								$data['tol_pently'] = $data['tol_pently'];
							}
						} else {
							$data['tol_pently'] = $data['tol_pently'];
						}
					} else {
						$data['tol_pently'] = $data['tol_pently'];
					}

					$data["deman_am"] = $data["deman_am"] + $data['demand_amn'][$i - 1]["balance"];
				}

				$crnt_dm_for_rdt = date('m');
				if ($crnt_dm_for_rdt == '04' || $crnt_dm_for_rdt == '05' || $crnt_dm_for_rdt == '06') {
					$from_year = date("Y");
					$to_year = $from_year + 01;
					$fy = $from_year . '-' . $to_year;
					$data['fy_id'] = $this->modelfy->getFiidByfyyr($fy);
					if ($data['total_qrt'] >= 4) {
						if ($data['fy_id']['id'] == $data['due_upto_year']) {
							if ($data['date_upto_qtr'] == 4) {
								$data['demand_rbt'] = $this->modeldemand->demand_rebet($data["custm_id"], $data['fy_id']['id']);
								$rebate = ($data['demand_rbt']['sum'] / 100) * 5;
							} else {
								$rebate = 0;
							}
						} else {
							$rebate = 0;
						}
					} else {
						$rebate = 0;
					}
				} else {
					$rebate = 0;
				}
				$data['rebate'] = $rebate;

				$data["total_pabl"] = ($data["deman_am"] + $data['tol_pently'] + $difference_Penalty + $bank_reCancel) - $data['rebate'];

				if ($data['emp_type_id'] != 4) {
					$data["total_pa_onjsk"] = ($data['deman_am'] / 100) * 2.5;
					$data["total_payabl"] = $data["total_pabl"] - $data["total_pa_onjsk"];
				} else if ($data['payment_mode'] == 4) {
					$data["total_pa_onjsk"] = ($data['deman_am'] / 100) * 5;
					$data["total_payabl"] = $data["total_pabl"] - $data["total_pa_onjsk"];
				} else {
					$data["total_payabl"] = $data["total_pabl"];
				}

				$round = round($data["total_payabl"]);
				$data["round_off"] = $round - $data["total_payabl"];
				$advn = $this->modeladjustment->advance($data["custm_id"]);
				$advc = $advn['arear_amt'];
				$pable_amnt = $advc + $data["total_payabl"];
				if ($advc > 0) {
					if ($advc > $data["total_payabl"]) {
						$data['advc_adjst'] = $data["total_payabl"];
						$advn = $this->model_payment_adjust->advance_adjst($data);
					} else {
						$data['advc_adjst'] = $advc;
						$advn = $this->model_payment_adjust->advance_adjst($data);
					}
				}

				$data['current_date'] = date("Y-m-d");
				$data['checkPayment'] = $this->modelpay->checkpropPayment($data);
				if ($data['checkPayment']) {
					flashToast("jsk_confirm_payment", "Same Day More Than One Time Payment Cann't Done!!!");
					return $this->response->redirect(base_url('jsk/jsk_confirm_payment/' . md5($data['custm_id'])));
				} else {
					$data['insertPayment'] = $this->modelpay->insertPayment($data);
					//print_r($data["insertPayment"]);
					//die();
					if ($data['insertPayment']) {
						if ($data["payment_mode"] == '2' || $data["payment_mode"] == '3') {
							$chqDDdetails = $this->modelchqDD->chqDDdetails($data);
						}

						if ($data['tran_adj'] = $this->model_advance_mstr->adjust_amount(md5($data['custm_id']))) {
							if ($rst_adv = $this->model_adjustment_mstr->check_rest_advnce($data['custm_id'])) {
								$rest_advnce_update = $this->model_adjustment_mstr->rest_advnce_update($data);
							} else {
								$data['advance'] = $data['tran_adj']['total_amnt'] - $data['tran_adj']['rest_amnt'];
								$rest_advnce_insrt = $this->model_adjustment_mstr->rest_advnce_insrt($data);
							}
						}

						if ($pable_amnt > $data["total_payabl"]) {
							$data['advance_amount'] = $pable_amnt - $data["total_payabl"];
							$data['payment_adjustment'] = $this->modeladjustment->payment_adjustment($data);
							$data['demand_id'] = $this->modeldemand->demand_id($data);
							for ($i = 1; $i <= $data['total_qrt']; $i++) {
								$data['resultid'] = $data['demand_id'][$j++];
								$demand_am = $demand_amnt[$i - 1]["balance"];
								$dif_qtr = $dif_qtr + 3;
								$each_penlty = ($demand_am / 100) * ($tol_mnth - $dif_qtr);
								if ($each_penlty > 0) {
									$data['tol_pent'] = $each_penlty;
								} else {
									$data['tol_pent'] = 0;
								}

								$data['updatedemandPayment'] = $this->modeldemand->updatedemandPayment($data);

								$data['demandCollection'] = $this->modelpropcoll->demandCollection($data);
							}
						} elseif ($pable_amnt < $data["total_payabl"]) {
							$pay_amount = $pable_amnt;
							$data['demand_id'] = $this->modeldemand->demand_id($data);
							for ($i = 1; $i <= $data['total_qrt']; $i++) {
								$data['resultid'] = $data['demand_id'][$j++];
								$demand_am = $data['demand_amnt'][$i - 1]["balance"];

								if ($tol_mnth >= 3) {
									$dif_qtr = $dif_qtr + 3;
									$each_penlty = ($demand_am / 100) * ($tol_mnth - $dif_qtr);
									$data['tol_pent'] = $each_penlty;
									if ($tol_pent < 0) {
										$data['tol_pent'] = 0;
									}
								} else {
									$data['tol_pent'] = 0;
								}

								if ($pay_amount > 0) {


									if ($pay_amount > $demand_am) {
										$pay_amount = $pay_amount - ($demand_am + $data['tol_pent']);

										$data['updatedemandPayment'] = $this->modeldemand->updatedemandPayment($data);

										$data['demandCollection'] = $this->modelpropcoll->demandCollection($data);
									}
								} else {
									$al_tax_id = $this->modeltax->al_tax_id($data);

									$percent_balance = ($pay_amount / $demand_am) * 100;
									$holding_tax = ($al_tax_id['holding_tax'] / 100) * $percent_balance;
									$water_tax = ($al_tax_id['water_tax'] / 100) * $percent_balance;
									$education_cess = ($al_tax_id['education_cess'] / 100) * $percent_balance;
									$health_cess = ($al_tax_id['health_cess'] / 100) * $percent_balance;
									$lighting_tax = ($al_tax_id['lighting_tax'] / 100) * $percent_balance;
									$latrine_tax = ($al_tax_id['latrine_tax'] / 100) * $percent_balance;
									$harvest_tax = ($al_tax_id['additional_tax'] / 100) * $percent_balance;
									//print_r($pay_amount);
									$data['balance'] = ($demand_am + $tol_pent) - $pay_amount;
									$pay_amount = 0;

									$data['updatedemandPayment'] = $this->modeldemand->updatedemandPaymentblnc($data);

									$data['demandCollection'] = $this->modelpropcoll->demandCollection($data);
								}
							}
						} else {
							$data['demand_id'] = $this->modeldemand->demand_id($data);
							$dif_qtr = 0;
							$data['tol_pents'] = 0;
							for ($i = 1; $i <= $data['total_qrt']; $i++) {
								$data['resultid'] = $data['demand_id'][$i - 1];
								$dem_am = $data['demand_amn'][$i - 1]["balance"];
								$dif_qtr = $dif_qtr + 3;
								$dem_fyids = $data['demand_amn'][$i - 1]["fy_id"];
								if ($dem_fyids >= 49) {
									if ($tol_mnth >= 3) {
										$each_penlty = ($dem_am / 100) * ($tol_mnths - $dif_qtr);
										if ($each_penlty > 0) {
											$data['tol_pent'] = $each_penlty;
										} else {
											$data['tol_pent'] = $data['tol_pents'];
										}
									} else {
										$data['tol_pent'] = $data['tol_pents'];
									}
								} else {
									$data['tol_pent'] = $data['tol_pents'];
								}

								$data['pntmnth'] = $tol_mnths - $dif_qtr;
								$data["date_cls"] = date("Y-m-d H:i:s");
								$data['updatedemandPayment'] = $this->modeldemand->updatedemandPayment($data);

								$data['demandCollection'] = $this->modelpropcoll->demandCollection($data);
							}
						}

						if ($data["tol_pently"] > 0) {
							$data['head_name'] = "1% Monthly Penalty";
							$data['fine_rebet_amount'] = $data["tol_pently"];
							$data['add_minus'] = "Add";

							$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
							//print_r($data['fine_rebet_details']);

						}
						if ($data["rebate"] > 0) {
							$data['head_name'] = "First Quartare Discount";
							$data['fine_rebet_amount'] = $data["rebate"];
							$data['add_minus'] = "Minus";

							$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
						}

						if ($data['total_pa_onjsk'] > 0) {
							$data['head_name'] = "Rebate From Jsk";
							$data['fine_rebet_amount'] = $data['total_pa_onjsk'];
							$data['add_minus'] = "Minus";

							$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
						}

						if ($difference_Penalty > 0) {
							$data['head_name'] = "Difference Penalty";
							$data['fine_rebet_amount'] = $difference_Penalty;
							$data['add_minus'] = "Add";

							$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
							//print_r($data['fine_rebet_details']);
							$this->model_penalty_dtl->Updatedifference_Penalty($data);
						}
						if ($bank_reCancel > 0) {
							$data['chequePaymentDone'] = $this->model_bank_recancilation->chequePaymentDone($data);
						}

						$data['assessment_type'] = $this->modelassess->assessment_type($data['custm_id']);
						if ($data['assessment_type'] == "Reassessment") {
							$data['prop_hold'] = $this->modelprop->prop_hold($data['custm_id']);
						}
					}
				}
				if ($this->db->transStatus() === FALSE) {
					$this->db->transRollback();
					flashToast("jsk_confirm_payment", "Something error due to payment!!!");
					return $this->response->redirect(base_url('jsk/jsk_confirm_payment/' . md5($data['custm_id'])));
				} else {
					$this->db->transCommit();
					return $this->response->redirect(base_url('jsk/payment_jsk_receipt/' . md5($data['insertPayment'])));
				}
			} else {
				$data = $this->modelprop->getPropDtlByMD5PropDtlId($id);
				$data['prop_owner_detail'] = $this->modelowner->getPropOwnerDtlByProp_dtlId(['prop_dtl_id' => $data['prop_dtl_id']]);
				$data['prop_tax_list'] = $this->modeltax->getPropTaxDtlByPropDtlId(['prop_dtl_id' => $data['prop_dtl_id']]);

				$data['demand_detail'] = $this->modeldemand->demand_detail(["id" => $id]);
				if ($fydemand = $this->modeldemand->fydemand($data['prop_dtl_id'])) {
					$data['fydemand'] = $fydemand;
				}
				if ($bank_reCancel = $this->model_bank_recancilation->bank_reCancel($data['prop_dtl_id'])) {
					$data['bank_reCancel'] = $bank_reCancel;
				}

				$mnth = date("m");
				$from_year = date("Y");
				$to_year = $from_year + 01;
				if ($mnth >= 4 || $mnth < 4) {
					$fy = $from_year . '-' . $to_year;
					$data['fy_id'] = $this->modelfy->getFiidByfyyr($fy);
				}

				if ($difference_Penalty = $this->model_penalty_dtl->difference_Penalty($data['prop_dtl_id'])) {
					$data['difference_Penalty'] = $difference_Penalty;
				}
				//$data['advance'] = $this->modeladjustment->advance_amnt($data['prop_dtl_id']);
				$data['transaction_adjust'] = $this->model_advance_mstr->adjust_amount(md5($data['prop_dtl_id']));
				$data['advance'] = $data['transaction_adjust']['total_amnt'] - $data['transaction_adjust']['rest_amnt'];
				//print_r($data['advance']);
				$data['tran_mode'] = $this->modeltran->getTranModeList();
				$data["ulb"] = $Session->get("ulb_dtl");
				$data["id"] = $id;
				$data['emp_type_id'] = $emp_mstr["user_type_mstr_id"];
				return view('Property/jsk/jsk_confirm_payment', $data);
			}
		}
	}

	public function payment_jsk_receipt($tran_no = null)
	{
		$data = (array)null;
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$path = base_url('citizenPaymentReceipt/payment_jsk_receipt/' . $ulb_mstr_id . '/' . $tran_no);
		$data['ss'] = qrCodeGeneratorFun($path);
		$data['tran_no'] = $tran_no;
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
		$data['tran_mode_dtl'] = $this->modelpay->getTrandtlList($data['tran_no']);
		$data['advance_amt'] = $this->model_advance_mstr->advance_amnt2023($data['tran_mode_dtl']['id']);
		//$data['coll_dtl'] = $this->modelsafcoll->collection_dtl($data['tran_no']);
		$data['fyFrom'] = $this->modelfy->getfyFromList($data['tran_mode_dtl']['from_fy_mstr_id']);
		$data['fyUpto'] = $this->modelfy->getfyUptoList($data['tran_mode_dtl']['upto_fy_mstr_id']);
		$data['payMode'] = $this->modeltran->getpayModeList($data['tran_mode_dtl']['tran_mode_mstr_id']);
		//$data['advance'] = $this->model_adjustment_mstr->rst_advnc_amnt($data['tran_mode_dtl']);
		
		
		//$data['rst_amnt'] = $data['advance']['total_amnt'] - $data['advance']['rest_amnt'];

		if ($data['tran_mode_dtl']['tran_type'] == 'Property') {
			$data['coll_dtl'] = $this->modelpropcoll->collection_propdtl($data['tran_mode_dtl']['id']);
			$data['holdingward'] = $this->modelprop->getholdWard(md5($data['tran_mode_dtl']['prop_dtl_id']));
			$data['basic_details'] = $this->modelprop->basic_dtl(md5($data['tran_mode_dtl']['prop_dtl_id']));
			//print_var($data['basic_details']);
			$data['basic_receipt_details'] = $this->modelprop->basic_receipt_dtl(md5($data['tran_mode_dtl']['prop_dtl_id']));
		} elseif ($data['tran_mode_dtl']['tran_type'] == 'Saf') {
			$data['coll_dtl'] = $this->modelsafcoll->collection_dtl($data['tran_mode_dtl']['id']);
			$data['holdingward'] = $this->modelassess->getholdWard(md5($data['tran_mode_dtl']['prop_dtl_id']));
			$data['basic_details'] = $this->modelassess->basic_dtl(md5($data['tran_mode_dtl']['prop_dtl_id']));
			$data['basic_receipt_details'] = $this->modelassess->basic_receipt_dtl(md5($data['tran_mode_dtl']['prop_dtl_id']));
		}
		if ($data['payMode']['id'] == 2 || $data['payMode']['id'] == 3) {
			$data['chqDD_details'] = $this->modelchqDD->mode_dtl($data['tran_no']);
		}
		$data['penalty_dtl'] = $this->modelpenalty->penalty_dtl($data['tran_mode_dtl']['id']);
		$data['system_name'] = $this->model_system_name->system_name($data['tran_mode_dtl']['tran_date']);
		//print_var($data['ulb_mstr_name']);
		// print_var($data);
		// $data['basic_details'][0]['new_holding_no']='';
		return view('Property/jsk/payment_receipt', $data);
	}
	
	public function rmcPaymentReceipt($trxn_id_md5)
	{
		$data = (array)null;
		$Session = Session();
		$data["ulb"] = $Session->get("ulb_dtl");

		$path = base_url('citizenPaymentReceipt/payment_jsk_receipt/' . $data["ulb"]["ulb_mstr_id"] . '/' . $trxn_id_md5);
		$data['qr_code'] = qrCodeGeneratorFun($path);
		$data['trxn'] = $this->modelpay->getTrandtlList($trxn_id_md5);
		
		$data['coll_dtl'] = $this->modelpropcoll->Transaction_Collection_Details($data['trxn']);
		if ($data['trxn']['tran_type'] == 'Property') {
			
			$data['prop_dtl'] = $this->modelprop->get_prop_dtl($data['trxn']['prop_dtl_id']);
				$data['basic_receipt_details'] = $this->modelprop->basic_receipt_dtl(md5($data['trxn']['prop_dtl_id']));
		}
		elseif ($data['trxn']['tran_type'] == 'Saf') {

			$data['prop_dtl'] = $this->modelassess->get_saf_dtl($data['trxn']['prop_dtl_id']);
			$data['basic_receipt_details'] = $this->modelassess->basic_receipt_dtl(md5($data['trxn']['prop_dtl_id']));
		}

		
		if (in_array(strtoupper($data["trxn"]["tran_mode"]), ["CHEQUE", "DD"])) {
			
			$data['cheque_dtl'] = $this->modelchqDD->mode_dtl(md5($data['trxn']['id']));
		}
		$data['penalty_dtl'] = $this->modelpenalty->penalty_dtl($data['trxn']['id']);
		$data['system_name'] = $this->model_system_name->system_name($data['trxn']['tran_date']);
		// $data['basic_receipt_details'] = $this->modelprop->basic_receipt_dtl($data['trxn']['prop_dtl_id']);
		// print_var($data);
		return view('Property/jsk/rmcPaymentReceipt', $data);
	}
	//--------------------------------------------------------------------

	public function ajax_gatequarter()
	{
		if ($this->request->getMethod() == 'post') {
			$data = [
				'fyUpto' => $this->request->getVar('due_upto_year'),
				'prop_no' => $this->request->getVar('custm_id')
			];
			$result = $this->modeldemand->gateQuarter($data);
			$totalQuarter = $this->modeldemand->gatetotalQuarter($data);
			//$lasttotalQuarter = $this->modeldemand->gatelasttotalQuarter($data);
			if (!empty($result)) {
				$option = "";
				$option .= "<option value=''>Select Quarter</option>";
				foreach ($result as $value) {
					$option .= "<option value='" . $value['qtr'] . "'>" . $value['qtr'] . "</option>";
				}
				$lasttotalQuarter = $value['qtr'];
				$response = ['response' => true, 'data' => $option, 'val' => $totalQuarter['totalqtr'], 'last' => $lasttotalQuarter];
			} else {
				$response = ['response' => false];
			}
		} else {
			$response = ['response' => false];
		}
		echo json_encode($response);
	}



	public function ajax_rungatequarter()
	{
		if ($this->request->getMethod() == 'post') {
			$data = [
				'fyUpto' => $this->request->getVar('due_upto_year'),
				'prop_no' => $this->request->getVar('custm_id')
			];
			$result = $this->modeldemand->gateQuarter($data);
			$lastquatr = $this->modeldemand->gateQuarterlast($data);
			$totalQuarter = $this->modeldemand->gatetotalQuarter($data);
			//$lasttotalQuarter = $this->modeldemand->gatelasttotalQuarter($data);
			if (!empty($result)) {
				$option = "";
				$option .= "<option value='" . $lastquatr['qtr'] . "'>" . $lastquatr['qtr'] . "</option>";
				foreach ($result as $value) {
					$option .= "<option value='" . $value['qtr'] . "'>" . $value['qtr'] . "</option>";
				}
				$lasttotalQuarter = $value['qtr'];
				$response = ['response' => true, 'data' => $option, 'val' => $totalQuarter['totalqtr'], 'last' => $lasttotalQuarter];
			} else {
				$response = ['response' => false];
			}
		} else {
			$response = ['response' => false];
		}
		echo json_encode($response);
	}


	public function physicalSurvay(){
		try{
			$Session = Session();
			$emp_details = $Session->get("emp_details");
			$safHelper = new \App\Controllers\SAF\SAFHelper($this->db);
			$data = [];
			if(strtolower($this->request->getMethod())=="post"){
				$inputData = $this->request->getVar();
				if(isset($inputData["btn_search"])){
					$holdingDtlSql ="select * from tbl_prop_dtl where new_holding_no = '".$inputData["holding_no"]."' and status =1 order by id desc";
					$holding = $this->db->query($holdingDtlSql)->getFirstRow("array"); 
					if(!$holding){
						$holdingDtlSql ="select * from tbl_prop_dtl where holding_no = '".$inputData["holding_no"]."' and status =1 order by id desc";
						$holding = $this->db->query($holdingDtlSql)->getFirstRow("array"); 
					}
					if(!$holding){
						flashToast("message","Holding Not Found");
						return redirect()->back();
					}
					$test = $this->PropPhysicalVerification->where("prop_dtl_id",$holding["id"])->get()->getFirstRow("array");
					if($test){
						flashToast("message","This Holding Have Already Verified");
						return redirect()->back();
					}
	
					// Master queries
					$wardSql = "select * from view_ward_mstr where status = 1 order by id ";
					$ownerShipSql = "select * from tbl_ownership_type_mstr where status = 1 order by id";
					$propertySql = "select * from tbl_prop_type_mstr where status = 1 order by id";
					$roadSql = "select * from tbl_road_type_mstr where status = 1 order by id";
					$apartmentSql = "select * from tbl_apartment_details where status = 1 order by id";
	
					$floorTypeSql = "SELECT * FROM tbl_floor_mstr where status = 1  ORDER BY id";
					$usageTypeSql = "SELECT * FROM tbl_usage_type_mstr where status = 1  ORDER BY id";
					$constructionTypeSql = "SELECT * FROM tbl_const_type_mstr where status = 1  ORDER BY id";
					$occupancyTypeSql = "SELECT * FROM tbl_occupancy_type_mstr where status = 1 ORDER BY id";
	
					$ulbEmpSql = "select * from view_emp_details where lock_status = 0 and user_for='ULB' order by id";
					$agencyEmpSql = "select * from view_emp_details where lock_status = 0 and user_for='AGENCY' and id!=1 order by id";
					$ulbEmpList = $this->dbSystem->query($ulbEmpSql)->getResultArray();
					$agencyEmpList = $this->dbSystem->query($agencyEmpSql)->getResultArray();
					$data["ulbEmpList"] = $ulbEmpList;
					$data["agencyEmpList"] = $agencyEmpList;
	
					$wardList = $this->db->query($wardSql)->getResultArray();
					$ownerShipList = $this->db->query($ownerShipSql)->getResultArray();
					$propertyTypeList = $this->db->query($propertySql)->getResultArray();
					$roadList = $this->db->query($roadSql)->getResultArray();
					$apartmentList = $this->db->query($apartmentSql)->getResultArray();
					$zoneList = [
						["id"=>1,"zone"=>"Zone 1"],
						["id"=>2,"zone"=>"Zone 2"],
					];
					$newWardList = $this->model_view_ward_mapping_mstr->getNewWardLIstByOldWardMstrId($holding['ward_mstr_id']);
					
					$floorTypeList = $this->db->query($floorTypeSql)->getResultArray();
					$usageTypeList = $this->db->query($usageTypeSql)->getResultArray();
					$constructionTypeList = $this->db->query($constructionTypeSql)->getResultArray();
					$occupancyTypeList = $this->db->query($occupancyTypeSql)->getResultArray();
	
					$holding["master_data"]=[
						"wardList"=>$wardList,
						"newWardList"=>$newWardList,
						"ownerShipList"=>$ownerShipList,
						"propertyTypeList"=>$propertyTypeList,
						"roadList"=>$roadList,
						"apartmentList"=>$apartmentList,
						"zoneList"=>$zoneList,
						"floorTypeList"=>$floorTypeList,
						"usageTypeList"=>$usageTypeList,
						"constructionTypeList"=>$constructionTypeList,
						"occupancyTypeList"=>$occupancyTypeList,
					];
	
					$holding = $this->adjustHoldingValue($holding);
					$ownerSql = "select * from  tbl_prop_owner_detail where status = 1 and prop_dtl_id=".$holding["id"]." order by id ASC";
					$holding["owners"] = $this->db->query($ownerSql)->getResultArray();
					$floorSql = "select * from tbl_prop_floor_details where status =1 and prop_dtl_id=".$holding["id"]." order by id ASC";
					$holding["floors"]=$this->adjustFloor($this->db->query($floorSql)->getResultArray());
	
					$holding["no_electric_connection"] = $holding["no_electric_connection"]=="t" ? true : false;
					$holding["is_mobile_tower"] = $holding["is_mobile_tower"]=="t" ? true : false;
					$holding["is_hoarding_board"] = $holding["is_hoarding_board"]=="t" ? true : false;
					$holding["is_petrol_pump"] = $holding["is_petrol_pump"]=="t" ? true : false;
					$holding["is_water_harvesting"] = $holding["is_water_harvesting"]=="t" ? true : false;
					$inputs = $holding;
	
	
					if($inputs["prop_type_mstr_id"]==4){
						if ($inputs['road_type_mstr_id']!=4) {
							$vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
						} else {
							$vacantDtlArr = $safHelper->makeVacantWithNoRoadFloorDtlArr($inputs);
						}
						list($safTaxDtl, $new_rule_sub, $cv_rule_sub, $cv_vacant_land_dtl) = $safHelper->calVacantTaxDtl($vacantDtlArr);
						
						foreach ($cv_rule_sub as $value) {
							if ($value["type"]=="Vacant Land") {
								$floor["floor_name"]=$value["type"];
								$floor["builtup_area"]=$value["area_sqm"];
								$floor["cv_rate"] = $value['vacant_land_rate'];
								$floor["occupancy_rate"] = $value['occupancy_factor'];
								$floor["tax_percent"] = 1;
								$floor["calculation_factor"] = 1;
								$floor["matrix_factor_rate"] = 1;
								$floor["holding_tax"] = $value['yearly_cv_tax'];
								$floor["quaterly_holding_tax"] = $value['yearly_cv_tax']/4;
								$floor["rwh_tax"] = null;
								// $holding["floor_wise_tax"][] = $floor;
							}
						}
						
					}else{
						foreach($holding["floors"] as $floor){
							$inputs["usage_type_mstr_id"][] = $floor["usage_type_mstr_id"] ;
							$inputs["floor_mstr_id"][] = $floor["floor_mstr_id"] ;
							$inputs["const_type_mstr_id"][] = $floor["const_type_mstr_id"];
							$inputs["occupancy_type_mstr_id"][] = $floor["occupancy_type_mstr_id"]  ;
							$inputs["builtup_area"][] = $floor["builtup_area"];
							$inputs["date_from"][] = date("Y-m", strtotime($floor["date_from"]));
							$inputs["date_upto"][] = ($floor["date_upto"] == "") ? "" : date("Y-m", strtotime($floor["date_upto"]));
						}
						$floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
						$isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
						list($safTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calBuildingTaxDtl($floorDtlArr, $inputs['prop_type_mstr_id'], $isAdditionaTaxImplemented);
						foreach($holding["floors"] as $key =>$floor){
							$taxDtl =$floorDtlArr[$key];
							$floor["cv_rate"] = $taxDtl['cv_2024_cal_method']['cvr'];
							$floor["occupancy_rate"] = $taxDtl['cv_2024_cal_method']['occupancy_rate'];
							$floor["tax_percent"] = ($taxDtl['cv_2024_cal_method']['resi_comm_type_rate']*100)??"";
							$floor["calculation_factor"] = $taxDtl['cv_2024_cal_method']['calculation_factor'];
							$floor["matrix_factor_rate"] = $taxDtl['cv_2024_cal_method']['matrix_factor_rate'];
							$floor["holding_tax"] = $taxDtl['cv24'];
							$floor["quaterly_holding_tax"] = $taxDtl['cv24']/4;
							$floor["rwh_tax"] = $taxDtl['additional_tax'];
							$holding["floor_wise_tax"][] = $floor;
	
						}
					}
					$data["holding"]=$holding;
					// print_var($holding["floor_wise_tax"]);
				}elseif(isset($inputData["submit_statement"])){
					$test = $this->PropPhysicalVerification->where("prop_dtl_id",$inputData["prop_dtl_id"])->get()->getFirstRow("array");
					if($test){
						flashToast("message","This Holding Have Already Verified");
						return redirect()->back();
					}
					
					$additionAlDoc = [];
					foreach($this->request->getFiles() as $key=> $file){
						if(in_array($key,["leftImage","rightImage","frontImage","rwhImage"]) && $file->IsValid() && !$file->hasMoved()){
							
							$file_ext = $file->getExtension();
							$ulb_dtl = $Session->get('ulb_dtl');
							$city = $ulb_dtl['city'];
							$newFileNamee = $inputData["prop_dtl_id"]."_".$emp_details["id"].$key.".".$file_ext;
							$path = "$city/PhysicalVerification/";
							if ($file->move(WRITEPATH . "uploads/".$path, $newFileNamee )) {
								$doc_path = $path."/".$newFileNamee;
								$inputData[$key]=$doc_path;
							}
	
						}
						if(!in_array($key,["leftImage","rightImage","frontImage","rwhImage"]) && is_array($file)){
							foreach($file as $key=>$addition){
								$addition = $addition["file"];
								$name = $inputData["additionalDoc"][$key]["name"]??"";
								if( $addition->IsValid() && !$addition->hasMoved()){
									$file_ext = $addition->getExtension();
									$ulb_dtl = $Session->get('ulb_dtl');
									$city = $ulb_dtl['city'];
									$newFileNamee = $inputData["prop_dtl_id"]."_".$emp_details["id"]."_addition_".$key.".".$file_ext;
									$path = "$city/PhysicalVerification/";
									if ($addition->move(WRITEPATH . "uploads/".$path, $newFileNamee )) {
										$doc_path = $path."/".$newFileNamee;
										$additionAlDoc[$key]["doc_path"]=$doc_path;
										$additionAlDoc[$key]["doc_name"]=$name;
									}
	
								}
							}
						}
						
					}
					// Normalize a boolean string input (e.g. from a <select>) to PHP boolean
					$toBool = fn($val) => filter_var($val, FILTER_VALIDATE_BOOLEAN);
	
					// Conditional field fetch based on boolean flag
					$getConditional = function ($flag, $key) use ($inputData, $toBool) {
						return $toBool($inputData[$flag] ?? false) ? ($inputData[$key] ?? null) : null;
					};
	
	
					$phVerificationData = [
						"prop_dtl_id" => $inputData["prop_dtl_id"] ?? null,
						"verification_date" => $inputData["date"] ?? null,
						"ward_mstr_id" => $inputData["ward_mstr_id"] ?? null,
						"physical_ward_mstr_id" => $inputData["physical_ward_mstr_id"] ?? null,
						"new_ward_mstr_id" => $inputData["new_ward_mstr_id"] ?? null,
						"physical_new_ward_mstr_id" => $inputData["physical_new_ward_mstr_id"] ?? null,
						"zone_mstr_id" => $inputData["zone_mstr_id"] ?? null,
						"physical_zone_mstr_id" => $inputData["physical_zone_mstr_id"] ?? null,
						"prop_type_mstr_id" => $inputData["prop_type_mstr_id"] ?? null,
						"physical_prop_type_mstr_id" => $inputData["physical_prop_type_mstr_id"] ?? null,
						"area_of_plot" => $inputData["area_of_plot"] ?? null,
						"physical_area_of_plot" => $inputData["physical_area_of_plot"] ?? null,
						"road_type_mstr_id" => $inputData["road_type_mstr_id"] ?? null,
						"physical_road_type_mstr_id" => $inputData["physical_road_type_mstr_id"] ?? null,
	
						"is_hoarding_board" => $toBool($inputData["is_hoarding_board"] ?? false),
						"physical_is_hoarding_board" => $toBool($inputData["physical_is_hoarding_board"] ?? false),
						"hoarding_installation_date" => $inputData["hoarding_installation_date"] ?? null,
						"physical_hoarding_installation_date" => $getConditional("physical_is_hoarding_board", "physical_hoarding_installation_date"),
						"hoarding_area" => $inputData["hoarding_area"] ?? null,
						"physical_hoarding_area" => $getConditional("physical_is_hoarding_board", "physical_hoarding_area"),
	
						"is_mobile_tower" => $toBool($inputData["is_mobile_tower"] ?? false),
						"physical_is_mobile_tower" => $toBool($inputData["physical_is_mobile_tower"] ?? false),
						"tower_installation_date" => $inputData["tower_installation_date"] ?? null,
						"physical_tower_installation_date" => $getConditional("physical_is_mobile_tower", "physical_tower_installation_date"),
						"tower_area" => $inputData["tower_area"] ?? null,
						"physical_tower_area" => $getConditional("physical_is_mobile_tower", "physical_tower_area"),
	
						"is_petrol_pump" => $toBool($inputData["is_petrol_pump"] ?? false),
						"physical_is_petrol_pump" => $toBool($inputData["physical_is_petrol_pump"] ?? false),
						"petrol_pump_completion_date" => $inputData["petrol_pump_completion_date"] ?? null,
						"physical_petrol_pump_completion_date" => $getConditional("physical_is_petrol_pump", "physical_petrol_pump_completion_date"),
						"under_ground_area" => $inputData["under_ground_area"] ?? null,
						"physical_under_ground_area" => $getConditional("physical_is_petrol_pump", "physical_under_ground_area"),
	
						"is_water_harvesting" => $toBool($inputData["is_water_harvesting"] ?? false),
						"physical_is_water_harvesting" => $toBool($inputData["physical_is_water_harvesting"] ?? false),
						"occupation_date" => $inputData["occupation_date"] ?? null,
						"physical_occupation_date" => $inputData["physical_occupation_date"] ?? null,
	
						// Images
						"left_image" => $inputData["leftImage"] ?? null,
						"left_image_latitude" => $inputData["latitude_leftImage"] ?? null,
						"left_image_longitude" => $inputData["longitude_leftImage"] ?? null,
						"right_image" => $inputData["rightImage"] ?? null,
						"right_image_latitude" => $inputData["latitude_rightImage"] ?? null,
						"right_image_longitude" => $inputData["longitude_rightImage"] ?? null,
						"front_image" => $inputData["frontImage"] ?? null,
						"front_image_latitude" => $inputData["latitude_frontImage"] ?? null,
						"front_image_longitude" => $inputData["longitude_frontImage"] ?? null,
	
						"rwh_image" => $getConditional("physical_is_water_harvesting", "rwhImage"),
						"rwh_image_latitude" => $getConditional("physical_is_water_harvesting", "latitude_rwhImage"),
						"rwh_image_longitude" => $getConditional("physical_is_water_harvesting", "longitude_rwhImage"),
	
						// Employee
						"ulb_emp_id" => $inputData["ulb_emp_id"] ?? null,
						"ulb_emp_mobile_no" => $inputData["ulb_emp_mobile_no"] ?? null,
						"agency_emp_id" => $inputData["agency_emp_id"] ?? null,
						"agency_emp_mobile_no" => $inputData["agency_emp_mobile_no"] ?? null,
						"user_id" => $emp_details["id"] ?? null,
					];
					// Replace blank strings with null
					array_walk($phVerificationData, function (&$value) {
						if ($value === "") {
							$value = null;
						}
					});
	
					$this->db->transBegin();
					$id = $this->PropPhysicalVerification->store($phVerificationData);
					
					
					foreach($inputData["floors"] as $floor){
						$floorInput=[
							"tbl_prop_physical_verification_id"=>$id,
							"prop_floor_id"=>$floor["id"]??null,
							"floor_mstr_id"=>$floor["floor_mstr_id"]??null,
							"usage_type_mstr_id"=>$floor["usage_type_mstr_id"]??null,
							"physical_usage_type_mstr_id"=>$floor["physical_usage_type_mstr_id"]??null,
							"occupancy_type_mstr_id"=>$floor["occupancy_type_mstr_id"]??null,
							"physical_occupancy_type_mstr_id"=>$floor["physical_occupancy_type_mstr_id"]??null,
							"const_type_mstr_id"=>$floor["const_type_mstr_id"]??null,
							"physical_const_type_mstr_id"=>$floor["physical_const_type_mstr_id"]??null,
							"builtup_area"=>$floor["builtup_area"]??null,
							"physical_builtup_area"=>$floor["physical_builtup_area"]??null,
							"date_from"=>$floor["date_from"]??null,
							"physical_date_from"=>isset($floor["physical_date_from"]) && $floor["physical_date_from"] ? $floor["physical_date_from"]."-01":null,
							"date_upto"=>$floor["date_upto"]??null,
							"physical_date_upto"=>isset($floor["physical_date_upto"]) && $floor["physical_date_upto"] ? $floor["physical_date_upto"]."-01" : null,
							"cv_rate"=>$floor["cv_rate"]??null,
							"physical_cv_rate"=>$floor["physical_cv_rate"]??null,
							"occupancy_rate"=>$floor["occupancy_rate"]??null,
							"physical_occupancy_rate"=>$floor["physical_occupancy_rate"]??null,
							"tax_percent"=>$floor["tax_percent"]??null,
							"physical_tax_percent"=>$floor["physical_tax_percent"]??null,
							"calculation_factor"=>$floor["calculation_factor"]??null,
							"physical_calculation_factor"=>$floor["physical_calculation_factor"]??null,
							"matrix_factor_rate"=>$floor["matrix_factor_rate"]??null,
							"physical_matrix_factor_rate"=>$floor["physical_matrix_factor_rate"]??null,
							"holding_tax"=>$floor["holding_tax"]??null,
							"physical_holding_tax"=>$floor["physical_holding_tax"]??null,
							"quaterly_holding_tax"=>$floor["quaterly_holding_tax"]??null,
							"physical_quaterly_holding_tax"=>$floor["physical_quaterly_holding_tax"]??null,
							"rwh_tax"=>$floor["rwh_tax"]??null,
							"physical_rwh_tax"=>$floor["physical_rwh_tax"]??null,
						];
						// Replace blank strings with null
						array_walk($floorInput, function (&$value) {
							if ($value === "") {
								$value = null;
							}
						});
						$floorId=$this->PropPhysicalVerificationFloor->store($floorInput);
					}
					if($inputData["isExtraFloorAdd"]){
						foreach($inputData["newFloors"] as $floor){
							$floorInput=[
								"tbl_prop_physical_verification_id"=>$id,
								"prop_floor_id"=>$floor["id"]??null,
								"floor_mstr_id"=>$floor["floor_mstr_id"]??null,
								// "usage_type_mstr_id"=>$floor["usage_type_mstr_id"]??null,
								"physical_usage_type_mstr_id"=>$floor["usage_type_mstr_id"]??null,
								// "occupancy_type_mstr_id"=>$floor["occupancy_type_mstr_id"]??null,
								"physical_occupancy_type_mstr_id"=>$floor["occupancy_type_mstr_id"]??null,
								// "const_type_mstr_id"=>$floor["const_type_mstr_id"]??null,
								"physical_const_type_mstr_id"=>$floor["const_type_mstr_id"]??null,
								// "builtup_area"=>$floor["builtup_area"]??null,
								"physical_builtup_area"=>$floor["builtup_area"]??null,
								// "date_from"=>isset($floor["date_from"]) && $floor["date_from"] ?$floor["date_from"]."-01" :null,
								"physical_date_from"=>isset($floor["date_from"]) && $floor["date_from"] ? $floor["date_from"]."-01":null,
								// "date_upto"=>isset($floor["date_upto"]) && $floor["date_upto"] ?$floor["date_upto"]."-01" :null,
								"physical_date_upto"=>isset($floor["date_upto"]) && $floor["date_upto"] ? $floor["date_upto"]."-01" : null,
								"cv_rate"=>$floor["cv_rate"]??null,
								"physical_cv_rate"=>$floor["physical_cv_rate"]??null,
								"occupancy_rate"=>$floor["occupancy_rate"]??null,
								"physical_occupancy_rate"=>$floor["physical_occupancy_rate"]??null,
								"tax_percent"=>$floor["tax_percent"]??null,
								"physical_tax_percent"=>$floor["physical_tax_percent"]??null,
								"calculation_factor"=>$floor["calculation_factor"]??null,
								"physical_calculation_factor"=>$floor["physical_calculation_factor"]??null,
								"matrix_factor_rate"=>$floor["matrix_factor_rate"]??null,
								"physical_matrix_factor_rate"=>$floor["physical_matrix_factor_rate"]??null,
								"holding_tax"=>$floor["holding_tax"]??null,
								"physical_holding_tax"=>$floor["physical_holding_tax"]??null,
								"quaterly_holding_tax"=>$floor["quaterly_holding_tax"]??null,
								"physical_quaterly_holding_tax"=>$floor["physical_quaterly_holding_tax"]??null,
								"rwh_tax"=>$floor["rwh_tax"]??null,
								"physical_rwh_tax"=>$floor["physical_rwh_tax"]??null,
							];
							array_walk($floorInput, function (&$value) {
								if ($value === "") {
									$value = null;
								}
							});
							$floorId=$this->PropPhysicalVerificationFloor->store($floorInput);
						}
					}
					foreach($additionAlDoc as $val){
						$files=$val;
						$files["physical_id"]=$id;
						// Replace blank strings with null
						array_walk($files, function (&$value) {
							if ($value === "") {
								$value = null;
							}
						});
						$floorId=$this->db->table("tbl_prop_physical_addition_doc")->insert($files);
					}
					$additionEmp = $this->request->getVar("addition")??[];
					foreach($additionEmp as $val){
						$insertData=[
							"physical_id"=>$id,
							"user_id"=>$val["emp"]["userId"]??null,
							"mobile_no"=>$val["emp"]["mobileNo"]??null,
						];
						$floorId=$this->db->table("tbl_prop_physical_addition_users")->insert($insertData);
					}
					if($this->db->transStatus() === TRUE){
						$this->db->transCommit();
						flashToast("message","Data Store");
						
						return redirect()->to(base_url("jsk/physicalSurvay"));
					}
					$this->db->transRollback();
					flashToast("error","Data not Store");
					return redirect()->back();
				}
			}
			return view("property\physicalVerification",$data);
		}catch(Exception $e){
			flashToast("error","Server Error");
			return redirect()->back();
		}
	}
	
	public function getUserListForAdditional(){
		try{
			$empSql = "select * from view_emp_details where lock_status = 0  order by id";
			$userList = $this->dbSystem->query($empSql)->getResultArray();
			return json_encode(["status"=>true,"message"=>"User Fetched","data"=>$userList]);
		}catch(Exception $e){
			return json_encode(["status"=>false,"message"=>"server Error"]);
		}
	}
	
	public function physicalVerificationSearch(){
		$session = Session();
		$emp_details = $session->get("emp_details");
		$ulbDtl = $session->get("ulb_dtl");
		$input = $this->request->getVar();
		$data=$input;
		if($input){
			$sql = " WITH floor_comp AS (
						SELECT 
							tbl_prop_physical_verification_id, 
							(physical_usage_type_mstr_id != usage_type_mstr_id) AS usage_type_comp,
							(physical_occupancy_type_mstr_id != occupancy_type_mstr_id) AS occupancy_type_comp,
							(physical_const_type_mstr_id != const_type_mstr_id) AS const_type_comp,
							(physical_builtup_area != builtup_area) AS builtup_area_comp,
							(physical_date_from != date_from) AS date_from_comp
						FROM tbl_prop_physical_verification_floors
						WHERE status = 1
					),
					single_row as (
						SELECT 
							tbl_prop_physical_verification_id,
							BOOL_OR(usage_type_comp) AS usage_type_comp,
							BOOL_OR(occupancy_type_comp) AS occupancy_type_comp,
							BOOL_OR(const_type_comp) AS const_type_comp,
							BOOL_OR(builtup_area_comp) AS builtup_area_comp,
							BOOL_OR(date_from_comp) AS date_from_comp
						FROM floor_comp
						GROUP BY tbl_prop_physical_verification_id
						ORDER BY tbl_prop_physical_verification_id
					)
					
					Select tbl_prop_physical_verifications.*,
						w.ward_no,phw.ward_no as physical_ward_no,
						nw.ward_no as new_ward_no,phnw.ward_no as physical_new_ward_no,
						pm.property_type, phpm.property_type as physical_property_type,
						ulb_emp.emp_name as ulb_emp_name,agency_emp.emp_name as agency_emp_name,
						tbl_prop_dtl.holding_no,tbl_prop_dtl.new_holding_no,single_row.*
					From tbl_prop_physical_verifications
					JOIN tbl_prop_dtl on tbl_prop_dtl.id = tbl_prop_physical_verifications.prop_dtl_id
					LEFT JOIN single_row on single_row.tbl_prop_physical_verification_id=tbl_prop_physical_verifications.id
					LEFT JOIN view_ward_mstr w on w.id = tbl_prop_physical_verifications.ward_mstr_id
					LEFT JOIN view_ward_mstr as phw on phw.id = tbl_prop_physical_verifications.physical_ward_mstr_id
					LEFT JOIN view_ward_mstr nw on nw.id = tbl_prop_physical_verifications.new_ward_mstr_id
					LEFT JOIN view_ward_mstr as phnw on phnw.id = tbl_prop_physical_verifications.physical_new_ward_mstr_id 
					LEFT JOIN tbl_prop_type_mstr pm on pm.id = tbl_prop_physical_verifications.prop_type_mstr_id
					LEFT JOIN tbl_prop_type_mstr as phpm on phpm.id = tbl_prop_physical_verifications.physical_prop_type_mstr_id
					LEFT JOIN view_emp_details as ulb_emp ON ulb_emp.id = tbl_prop_physical_verifications.ulb_emp_id
					LEFT JOIN view_emp_details as agency_emp ON agency_emp.id = tbl_prop_physical_verifications.agency_emp_id
					WHERE tbl_prop_physical_verifications.status!=0
			";
			
			if(isset($input["fromDate"]) || isset($input["uptoDate"])){
				if($input["fromDate"]??false && $input["uptoDate"]??false){
					$sql .=" AND tbl_prop_physical_verifications.verification_date BETWEEN '".$input["fromDate"]."' AND '".$input["uptoDate"]."' ";
				}elseif($input["fromDate"]??false){
					$sql .=" AND tbl_prop_physical_verifications.verification_date >= '".$input["fromDate"]."' ";
				}
				elseif($input["uptoDate"]??false){
					$sql .=" AND tbl_prop_physical_verifications.verification_date <= '".$input["uptoDate"]."' ";
				}
			}
			if($input["wardId"]??false){
				$wardId = implode(",",is_array($input["wardId"])?$input["wardId"]:[$input["wardId"]]);
				$sql .=" AND tbl_prop_physical_verifications.ward_mstr_id IN (".$wardId.") ";
			}
			if($input["is_property_type_change"]??false){
				$test=$input["is_property_type_change"]=="Yes"?1:0;
				$sql .=" AND (tbl_prop_physical_verifications.prop_type_mstr_id !=  tbl_prop_physical_verifications.physical_prop_type_mstr_id ) = $test::bool";
			}
			if($input["is_old_ward_change"]??false){
				$test=$input["is_old_ward_change"]=="Yes"?1:0;
				$sql .=" AND (tbl_prop_physical_verifications.ward_mstr_id !=  tbl_prop_physical_verifications.physical_ward_mstr_id ) = $test::bool ";
			}
			if($input["is_new_ward_change"]??false){
				$test=$input["is_new_ward_change"]=="Yes"?1:0;
				$sql .=" AND (tbl_prop_physical_verifications.new_ward_mstr_id !=  tbl_prop_physical_verifications.physical_new_ward_mstr_id ) = $test::bool ";
			}
			if($input["is_usage_type_change"]??false){
				$test=$input["is_usage_type_change"]=="Yes"?1:0;
				$sql .=" AND (single_row.usage_type_comp) = $test::bool ";
			}
			if($input["is_occupancy_type_change"]??false){
				$test=$input["is_occupancy_type_change"]=="Yes"?1:0;
				$sql .=" AND (single_row.occupancy_type_comp) = $test::bool ";
			}
			if($input["is_builtup_area_change"]??false){
				$test=$input["is_builtup_area_change"]=="Yes"?1:0;
				$sql .=" AND (single_row.builtup_area_comp) = $test::bool ";
			}
			if($input["export"]??false){
				$result = $this->model_datatable->getRecords($sql, false);
				$records = [];
                if ($result) {
                    foreach ($result as $key => $tran_dtl) {
                        $records[] = [
                            's_no' => $key+1,
                            'holding_no' => $tran_dtl['holding_no'],
                            'new_holding_no' => $tran_dtl['new_holding_no'],
                            'verification_date' => $tran_dtl['verification_date'],
                            "ward_no" => $tran_dtl["ward_no"],
							"physical_ward_no"=>$tran_dtl['physical_ward_no'],
							"new_ward_no"=>$tran_dtl["new_ward_no"],
							"physical_new_ward_no"=>$tran_dtl["physical_new_ward_no"],
							"property_type"=>$tran_dtl["property_type"],
							"physical_property_type"=>$tran_dtl["physical_property_type"],
							"area_of_plot"=>$tran_dtl["area_of_plot"],
							"physical_area_of_plot"=>$tran_dtl["physical_area_of_plot"],
                            'ulb_emp_name' => $tran_dtl['ulb_emp_name'],
                            'agency_emp_name' => $tran_dtl['agency_emp_name'],
                        ];
                    }
                }
				phpOfficeLoad();
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $activeSheet = $spreadsheet->getActiveSheet();
                $activeSheet->setCellValue('A1', 'Sl No.');
                $activeSheet->setCellValue('B1', 'Holding No');
                $activeSheet->setCellValue('C1', '15 Digit Unique House No.');
                $activeSheet->setCellValue('D1', 'Verification Date');
                $activeSheet->setCellValue('E1', 'Actual Old Ward No');
				$activeSheet->setCellValue('F1', 'PV Old Ward No');
				$activeSheet->setCellValue('G1', 'Actual New Ward No');
				$activeSheet->setCellValue('H1', 'PV New Ward No');
				$activeSheet->setCellValue('I1', 'Actual Property Type');
				$activeSheet->setCellValue('J1', 'PV Property Type');
				$activeSheet->setCellValue('K1', 'Actual Area of Plot (in decimal)');
				$activeSheet->setCellValue('L1', 'PV Area of Plot (in decimal)');
                $activeSheet->setCellValue('M1', 'ULB EMP');
                $activeSheet->setCellValue('N1', 'Agency EMP');


                $activeSheet->fromArray($records, NULL, 'A3');

                $filename = "physicalVerificationDone" . date('Ymd-hisa') . ".xlsx";
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');
				exit();
			}
			
			$result = $this->model_datatable->getDatatable($sql);
			$data['result'] = $result['result'];
			$data['offset'] = $result['offset'];
			$data['pager'] = $result['count'];
		}
		$data['ward'] = $this->model->getWardListWithSession(['ulb_mstr_id' => $uldDtl["ulb_mstr_id"]], $session);
		$data["forward"] = $session->get('forward');
		return view("property\physicalVerificationSearch",$data);
	}
	
	public function viewSurvay($id){
		try{
			$safHelper = new \App\Controllers\SAF\SAFHelper($this->db);
			$survay = $this->PropPhysicalVerification;
			if(!is_numeric($id)){
				$survay->where("md5(id::text)",$id);
			}else{
				$survay->where("id",$id);
			}
			$survay = $survay->get()->getFirstRow("array");
			if(!$survay){
				flashToast("message","Data Not Found");
				return redirect()->back();
			}
			$survay["is_mobile_tower"] = $survay["is_mobile_tower"]=="t" ? true : false;
			$survay["is_hoarding_board"] = $survay["is_hoarding_board"]=="t" ? true : false;
			$survay["is_petrol_pump"] = $survay["is_petrol_pump"]=="t" ? true : false;
			$survay["is_water_harvesting"] = $survay["is_water_harvesting"]=="t" ? true : false;
			
			$survay["physical_is_mobile_tower"] = $survay["physical_is_mobile_tower"]=="t" ? true : false;
			$survay["physical_is_hoarding_board"] = $survay["physical_is_hoarding_board"]=="t" ? true : false;
			$survay["physical_is_petrol_pump"] = $survay["physical_is_petrol_pump"]=="t" ? true : false;
			$survay["physical_is_water_harvesting"] = $survay["physical_is_water_harvesting"]=="t" ? true : false;
	
	
			$survay = $this->adjustHoldingPhysicalValue($this->adjustHoldingValue($survay));
			$holdingDtlSql ="select * from tbl_prop_dtl where id = ".$survay["prop_dtl_id"];
			$holding = $this->db->query($holdingDtlSql)->getFirstRow("array"); 
	
			$holding["no_electric_connection"] = $holding["no_electric_connection"]=="t" ? true : false;
			$holding["is_mobile_tower"] = $holding["is_mobile_tower"]=="t" ? true : false;
			$holding["is_hoarding_board"] = $holding["is_hoarding_board"]=="t" ? true : false;
			$holding["is_petrol_pump"] = $holding["is_petrol_pump"]=="t" ? true : false;
			$holding["is_water_harvesting"] = $holding["is_water_harvesting"]=="t" ? true : false;
	
			$survay["holding"]=$holding;
			$survayInputs = [];
			$patern = "/^physical_/"; // regex to match keys starting with 'physical_'
	
			foreach ($survay as $key => $val) {
				if (preg_match($patern, $key)) {
					// Remove 'physical_' prefix from the key
					$newKey = preg_replace($patern, '', $key);
					$survayInputs[$newKey] = $val;
				}
			}
			
	
			$ulbEmpSql = "select * from view_emp_details where id = ".$survay['ulb_emp_id'];
			$agencyEmpSql = "select * from view_emp_details where id = ".$survay['agency_emp_id'];
			$ulbEmp = $this->dbSystem->query($ulbEmpSql)->getFirstRow("array");
			$agencyEmp = $this->dbSystem->query($agencyEmpSql)->getFirstRow("array");
			$survay["ulb_emp"]=$ulbEmp;
			$survay["agency_emp"]=$agencyEmp;
	
			$verificationDtl = $this->PropPhysicalVerificationFloor->where("tbl_prop_physical_verification_id",$survay["id"])->get()->getResultArray();
			$verificationDtl = $this->adjustPhysicalFloor($this->adjustFloor($verificationDtl));
			
			$holding=array_merge($holding,$survayInputs);
			$inputs = $holding;
			if($inputs["prop_type_mstr_id"]==4){
				if ($inputs['road_type_mstr_id']!=4) {
					$vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
				} else {
					$vacantDtlArr = $safHelper->makeVacantWithNoRoadFloorDtlArr($inputs);
				}
				list($safTaxDtl, $new_rule_sub, $cv_rule_sub, $cv_vacant_land_dtl) = $safHelper->calVacantTaxDtl($vacantDtlArr);
				
				foreach ($cv_rule_sub as $value) {
					if ($value["type"]=="Vacant Land") {
						$floor["floor_name"]=$value["type"];
						$floor["builtup_area"]=$value["area_sqm"];
						$floor["cv_rate"] = $value['vacant_land_rate'];
						$floor["occupancy_rate"] = $value['occupancy_factor'];
						$floor["tax_percent"] = 1;
						$floor["calculation_factor"] = 1;
						$floor["matrix_factor_rate"] = 1;
						$floor["holding_tax"] = $value['yearly_cv_tax'];
						$floor["quaterly_holding_tax"] = $value['yearly_cv_tax']/4;
						$floor["rwh_tax"] = null;
						// $holding["floor_wise_tax"][] = $floor;
					}
				}
				
			}else{
				foreach($verificationDtl as $floor){
					$inputs["usage_type_mstr_id"][] = $floor["physical_usage_type_mstr_id"] ;
					$inputs["floor_mstr_id"][] = $floor["physical_floor_mstr_id"] ;
					$inputs["const_type_mstr_id"][] = $floor["physical_const_type_mstr_id"];
					$inputs["occupancy_type_mstr_id"][] = $floor["physical_occupancy_type_mstr_id"]  ;
					$inputs["builtup_area"][] = $floor["physical_builtup_area"];
					$inputs["date_from"][] = date("Y-m", strtotime($floor["physical_date_from"]));
					$inputs["date_upto"][] = ($floor["physical_date_upto"] == "") ? "" : date("Y-m", strtotime($floor["physical_date_upto"]));
				}
				$floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
				$isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
				list($safTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calBuildingTaxDtl($floorDtlArr, $inputs['prop_type_mstr_id'], $isAdditionaTaxImplemented);
				foreach($verificationDtl as $key => &$floor){
					$taxDtl =$floorDtlArr[$key];
					$floor["physical_cv_rate"] = $taxDtl['cv_2024_cal_method']['cvr'];
					$floor["physical_occupancy_rate"] = $taxDtl['cv_2024_cal_method']['occupancy_rate'];
					$floor["physical_tax_percent"] = ($taxDtl['cv_2024_cal_method']['resi_comm_type_rate']*100)??"";
					$floor["physical_calculation_factor"] = $taxDtl['cv_2024_cal_method']['calculation_factor'];
					$floor["physical_matrix_factor_rate"] = $taxDtl['cv_2024_cal_method']['matrix_factor_rate'];
					$floor["physical_holding_tax"] = $taxDtl['cv24'];
					$floor["physical_quaterly_holding_tax"] = $taxDtl['cv24']/4;
					$floor["physical_rwh_tax"] = $taxDtl['additional_tax'];
	
				}
			}
			$oldFloor=[];
			$newFloor=[];
			foreach($verificationDtl as $item){
				
				if($item["prop_floor_id"]){
					$oldFloor[]=$item;
				}else{
					$newFloor[]=$item;
				}
	
			}
			$survay["oldFloor"]=$oldFloor;
			$survay["newFloor"]=$newFloor;	
			// print_var($survay);		
			return view("property\physicalVerificationView",$survay);
	
		}catch(Exception $e){
			flashToast("error","Server Error");
			return redirect()->back();
		}
	}
}
