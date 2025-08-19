<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_transaction;
use App\Models\model_prop_demand;
use App\Models\model_collection;
use App\Models\model_prop_dtl;
use App\Models\model_transaction_fine_rebet_details;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_tax;
use App\Models\model_ccavanue_pay_request;
use App\Models\model_ccavanue_pay_response;

use Exception;

class payOnline extends HomeController
{
	protected $db;
	protected $dbSystem;
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_fy_mstr;
	protected $model_transaction;
	protected $model_prop_demand;
	protected $model_collection;
	protected $model_prop_dtl;
	protected $model_transaction_fine_rebet_details;
	protected $modelprop;
	protected $model_ccavanue_pay_request;
	protected $model_ccavanue_pay_response;

	protected $api_key_id = "";
	protected $api_secret = "";

	public function __construct()
    {
		// ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper', 'ccavanue_helper']);
		session()->set('ulb_dtl', getUlbDtl());

		

		if($db_name = dbConfig("property"))
		{
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
		$this->model_transaction = new model_transaction($this->db);
		$this->model_prop_demand = new model_prop_demand($this->db);
		$this->modelpropcoll = new model_collection($this->db);
		$this->modelprop = new model_prop_dtl($this->db);
		$this->modelpenalty = new model_transaction_fine_rebet_details($this->db);
		$this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
		$this->model_prop_tax = new model_prop_tax($this->db);
		$this->model_ccavanue_pay_request = new model_ccavanue_pay_request($this->db);
		$this->model_ccavanue_pay_response = new model_ccavanue_pay_response($this->db);

		$this->CCA_MERCHANT_ID = getenv("CCA_MERCHANT_ID");
		$this->CCA_ACCESS_CODE = getenv("CCA_ACCESS_CODE");
        $this->CCA_WORKING_KEY = getenv("CCA_WORKING_KEY");
    }

	function __destruct() {
		if(isset($this->db)) $this->db->close();
		if(isset($this->dbSystem)) $this->dbSystem->close();
	}

	# Property

	public function propertyPaymentProceed($prop_dtl_id_MD5)
	{
		try {
			$currentTime = date("Y-m-d H:i:s");
            $fiveMituts = date("Y-m-d H:i:s",strtotime($currentTime."-60 seconds "));
            
            $sql = "SELECT * FROM tbl_hdfc_request WHERE md5(prop_dtl_id::TEXT) = '".$prop_dtl_id_MD5."'
                        AND module = 'Property'
                        AND created_on >= '".$fiveMituts."'";
            $checkRequest = $this->db->query($sql)->getFirstRow("array");  
            if($checkRequest)
            {
                throw new Exception("Please Wait ".date("s",(strtotime($checkRequest["created_on"])-strtotime($fiveMituts)))." Seconds After first transection");
            }
			$data =(array)null;
			$data["id"]=$prop_dtl_id_MD5;
			$data["basic_details"] = $this->modelprop->basic_details(["id"=> $prop_dtl_id_MD5]);
			$appNo = $data["basic_details"]["new_holding_no"];
			$data["prop_dtl_id"] = $data['basic_details']['prop_dtl_id'];
			$data['owner_details'] = $this->model_prop_owner_detail->owner_details($data);
			
			// $data['tax_list'] = $this->model_prop_tax->tax_list($data['prop_dtl_id']);
			if ($tax_list = $this->model_prop_tax->tax_list($data['prop_dtl_id'])) {
				$data['tax_list'] = $tax_list;
				asort($data['tax_list']);
			}

			$data["DuesYear"]=$this->model_prop_demand->geDuesYear($data["prop_dtl_id"]);
			if($data["DuesYear"])
			{
				$input = [
					'fy' => $data["DuesYear"]["max_year"],
					'qtr' => $data["DuesYear"]["max_quarter"],
					'prop_dtl_id' => $data['prop_dtl_id'],
					'user_id'=> 0,
					];
				$data["DuesDetails"]=$this->model_prop_demand->getPropDemandAmountDetails($input);
				
				if($data["DuesDetails"]["PayableAmount"]<=0)
				{
					throw new Exception("Payable amount should be atleast 11 rupee");
				}

				$ccRevenue = getOderId(1);
				
				$orderId = $ccRevenue["orderId"];
				$merchent_id = $ccRevenue["merchantId"];
				$working_key = getWorkingKey(1);
				$access_code = getAccessCode(1);
				$redirectUrl = base_url('payOnline/paymentSuccess/'.$prop_dtl_id_MD5);
				$cancelUrl = base_url('payOnline/paymentFailed/'.$prop_dtl_id_MD5);
				$billing_mobile_no = '';

				$input = [
						"order_id" => $orderId,
						"merchant_id"=> $merchent_id,
						"prop_dtl_id"=> $data["prop_dtl_id"],
						"module"=> "Property",
						"from_fy_mstr_id"=> $data["DuesYear"]["min_fy_id"],
						"from_fy"=> $data["DuesYear"]["min_year"],
						"from_qtr"=> $data["DuesYear"]["min_quarter"],
						"upto_fy_mstr_id"=> $data["DuesYear"]["max_fy_id"],
						"upto_fy"=> $data["DuesYear"]["max_year"],
						"upto_qtr"=> $data["DuesYear"]["max_quarter"],
						"demand_amt"=> $data["DuesDetails"]["DemandAmount"],
						"penalty_amt"=> ($data["DuesDetails"]["OnePercentPnalty"] + $data["DuesDetails"]["OtherPenalty"]),
						"discount"=> ($data["DuesDetails"]["RebateAmount"] + $data["DuesDetails"]["AdvanceAmount"]),
						"payable_amt"=> round($data["DuesDetails"]["PayableAmount"]),
						"redirect_url" => $redirectUrl,
						"failure_url" => $cancelUrl,
						"status" => 1

					];
				
				$data["pg_mas_id"]=$this->model_ccavanue_pay_request->pay_request($input);
					
				CCAvanuePay($merchent_id, $access_code, $working_key, $orderId, round($data["DuesDetails"]["PayableAmount"]), $redirectUrl, $cancelUrl, $billing_mobile_no,$appNo);
			} 
		}catch (\Exception $e) {
			flashToast("payment", $e->getMessage());
			flashToast("message", $e->getMessage());
			return redirect()->back()->with('error', $e->getMessage());
		}
	}



	public function paymentSuccess($prop_dtl_id_MD5)
	{
		$data =(array)null;

		try
		{
			$decript = decrypt($this->request->getVar("encResp"),getWorkingKey(1));
			
			$order_status= $decript["order_status"];
			if($order_status != 'Success')
			{
				return $this->paymentFailed($prop_dtl_id_MD5);
			}

			$where = [
					"order_id" => $decript["order_id"],
					"md5(prop_dtl_id::text)" => $prop_dtl_id_MD5,
					"module" => 'Property'
			];

			$request=$this->model_ccavanue_pay_request->getRecord($where);

			if(!$request || round($decript['amount']) != round($request['payable_amt'])){
				throw new Exception("Payment gateway order Id or amount is not matching");
			}

			$input=[
					"request_id"=> $request['id'],
					"prop_dtl_id"=> $request['prop_dtl_id'],
					"module"=> $request['module'],
					"payable_amt"=> $request["payable_amt"],
					"ip_address"=> get_client_ip(),
					"merchant_id"=> $request["merchant_id"],
					"order_id"=> $request["order_id"],
					"tracking_id"=> $decript["tracking_id"],
					"bank_ref_no"=> $decript["bank_ref_no"],
					"error_code"=> $decript["status_code"],
					"error_desc" => $decript['failure_message'],
					"error_source"=> null,
					"error_step"=> null,
					"error_reason"=> $decript['status_message'],
					"respons_data"=> json_encode($decript),
					"status"=> 1
			];
			$data=[
				"prop_dtl_id"=> $request['prop_dtl_id'],
				"fy"=> $request["upto_fy"],
				"qtr"=> $request["upto_qtr"],
				"user_id"=> 0,
				"payment_mode"=> "Online",
				"remarks"=> null,
				"total_payable_amount"=> $request["payable_amt"],
			];

			$this->db->transBegin();
			$this->model_ccavanue_pay_response->pay_response($input);
			$trxn_id = $this->model_transaction->prop_pay_now($data, []);

			if($this->db->transStatus() === FALSE)
			{
				$this->db->transRollback();
				flashToast("message", "Some error occured, Transaction process has bee rollback!!!");
				return $this->response->redirect(base_url('CitizenProperty/Citizen_confirm_payment/'.$prop_dtl_id_MD5));
			}
			else
			{
				$this->db->transCommit();
				$message = "Order No.: ".$request["order_id"].", Amount: ".$request["payable_amt"];
				flashToast("message",  $message. "Transaction successfull !!!");
				return $this->response->redirect(base_url('onlinePay/payment_online_receipt/'.md5($trxn_id)));
			}
		}
		catch (Exception $e)
        {
            flashToast("message", $e->getMessage());
            return $this->response->redirect(base_url('CitizenProperty/Citizen_confirm_payment/'.$prop_dtl_id_MD5));
        }
	}

	public function paymentFailed($prop_dtl_id_MD5)
	{
		$decript = decrypt($this->request->getVar("encResp"),getWorkingKey(1));
		$order_status= $decript["order_status"];
		if($order_status == 'Success')
		{
			return $this->paymentSuccess($prop_dtl_id_MD5);
		}

		$where = [
				"order_id" => $decript["order_id"],
				"md5(prop_dtl_id::text)" => $prop_dtl_id_MD5,
				"module" => 'Property'
		];
		$request=$this->model_ccavanue_pay_request->getRecord($where);
		$input=[
				"request_id"=> $request['id'],
				"prop_dtl_id"=> $request['prop_dtl_id'],
				"module"=> $request['module'],
				"payable_amt"=> $request["payable_amt"],
				"ip_address"=> get_client_ip(),
				"merchant_id"=> $request["merchant_id"],
				"order_id"=> $request["order_id"],
				"tracking_id"=> $decript["tracking_id"],
				"bank_ref_no"=> $decript["bank_ref_no"],
				"error_code"=> $decript["status_code"],
				"error_desc" => $decript['failure_message'],
				"error_source"=> null,
				"error_step"=> null,
				"error_reason"=> $decript['status_message'],
				"respons_data"=> json_encode($decript),
				"status"=> 1
		];


		$this->db->transBegin();
		$this->model_ccavanue_pay_response->pay_response($input);
		flashToast("message", "Oops, Something went wrong, payment failed");

		if($request["module"]=="Property")
			return $this->response->redirect(base_url('CitizenProperty/Citizen_confirm_payment/'.$prop_dtl_id_MD5));
		else
			return $this->response->redirect(base_url('CitizenDtl/citizen_saf_payment_details'));
	}


	public function payment_online_receipt($tran_no=null)
	{
		$data =(array)null;
		$ulb_mstr_id = 1;
		//$path=base_url('citizenPaymentReceipt/payment_online_receipt/'.$ulb_mstr_id.'/'.$tran_no);
		$path=base_url('citizenPaymentReceipt/payment_jsk_receipt/'.$ulb_mstr_id.'/'.$tran_no);
		$data['ss']=qrCodeGeneratorFun($path);
		$data['tran_no']=$tran_no;
		$ulb_mstr_id = 1;
		$data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
		$data['tran_mode_dtl'] = $this->model_transaction->getTrandtlList($data['tran_no']);
		//$data['coll_dtl'] = $this->modelsafcoll->collection_dtl($data['tran_no']);
		$data['fyFrom'] = $this->modelfy->getfyFromList($data['tran_mode_dtl']['from_fy_mstr_id']);
		$data['fyUpto'] = $this->modelfy->getfyUptoList($data['tran_mode_dtl']['upto_fy_mstr_id']);
		$data['holdingward'] = $this->modelprop->getholdWard(md5($data['tran_mode_dtl']['prop_dtl_id']));
		$data['basic_details'] = $this->modelprop->basic_dtl(md5($data['tran_mode_dtl']['prop_dtl_id']));

		$data['coll_dtl'] = $this->modelpropcoll->collection_propdtl($data['tran_mode_dtl']['id']);
		$data['penalty_dtl'] = $this->modelpenalty->penalty_dtl($data['tran_mode_dtl']['id']);
		//print_var($data);
		//die();
		return view('citizen/payment_Onlinereceipt', $data);
	}

	// public function Ajax_getOnlinePropPayableAmount()
	// {
	// 	$data=(array)null;
	// 	$Session = Session();
	// 	$ulb = $Session->get('ulb_dtl');

	// 	$out = ["status"=> false, "message"=> "Invalid method"];
	// 	if($this->request->getMethod()=='post'){


	// 		$input = [
	// 			'fy' => $this->request->getVar('fy'),
	// 			'qtr' => $this->request->getVar('qtr'),
	// 			'prop_dtl_id' => $this->request->getVar('prop_dtl_id'),
	// 			'user_id'=> 0,
	// 			];


	// 		//print_var($data);exit;
	// 		$data["DuesYear"]=$this->model_prop_demand->geDuesYear($input["prop_dtl_id"]);
	// 		try{
	// 			if($data["DuesYear"])
	// 			{
	// 				$data["DuesDetails"]=$this->model_prop_demand->getPropDemandAmountDetails($input);



	// 				$input = [
	// 						"prop_dtl_id"=> $input["prop_dtl_id"],
	// 						"module"=> "Property",
	// 						"merchant_id"=> 0,
	// 						"from_fy_mstr_id"=> $data["DuesYear"]["min_fy_id"],
	// 						"from_fy"=> $data["DuesYear"]["min_year"],
	// 						"from_qtr"=> $data["DuesYear"]["min_quarter"],
	// 						"upto_fy_mstr_id"=> $data["DuesYear"]["max_fy_id"], // gadbad hai
	// 						"upto_fy"=> $input["fy"],
	// 						"upto_qtr"=> $input["qtr"],
	// 						"demand_amt"=> $data["DuesDetails"]["DemandAmount"],
	// 						"penalty_amt"=> ($data["DuesDetails"]["OnePercentPnalty"] + $data["DuesDetails"]["OtherPenalty"]),
	// 						"discount"=> ($data["DuesDetails"]["RebateAmount"] + $data["DuesDetails"]["AdvanceAmount"]),
	// 						"payable_amt"=> round($data["DuesDetails"]["PayableAmount"]),
	// 					];
	// 				$data["pg_mas_id"]=$this->model_razor_pay_request->pay_request($input);


	// 				helper('rozarpay_helper');
	// 				includeRazorLibrary();
	// 				$this->api = new \Razorpay\Api\Api($this->api_key_id, $this->api_secret);
	// 				$order_id = $this->api->order->create(array(
	// 						'receipt' => $data["pg_mas_id"],
	// 						'amount' => round($data["DuesDetails"]["PayableAmount"])*100,
	// 						'currency' => 'INR'
	// 					)
	// 				);
	// 				//print_var($order_id["id"]);
	// 				$this->model_razor_pay_request->updateRecord(["order_id"=> $order_id["id"]], $data["pg_mas_id"]);

	// 				$razorpay_param=[
	// 						"key"=> $this->api_key_id,
	// 						"order_id"=> $order_id["id"],
	// 						"amount"=> $data["DuesDetails"]["PayableAmount"]*100,
	// 						"currency"=> "INR",
	// 						"name"=> $ulb["ulb_name"], // ULB Name
	// 						"description"=> "SAF Tax Payment",
	// 						"pg_mas_id"=> $data["pg_mas_id"],
	// 				];

	// 				$out = ["status"=> true, "data"=> $razorpay_param, "message"=> ""];
	// 			}
	// 		}
	// 		catch(Exception $e){
	// 			$out = ["status"=> false, "data"=> [], "message"=> "Exception: ".$e->getMessage()];
	// 		}

	// 	}
	// 	echo json_encode($out);
	// }


	public function CCAvanueRequestResponseStatus()
	{
		
		try{
			
			$working_key = getWorkingKey(); 
			$access_code = getAccessCode();

			$merchant_json_data =
			array(
				'order_no' => 'Order_1050823012151084B79GTyXR'
				
			);
			
			
			$status = CheckReqResp($merchant_json_data, $working_key, $access_code);
			print_var($status);
			echo 'Status revert is: ' . $status.'<pre>';
			$obj = json_decode($status);
			print_r($obj);
		}
		catch (Exception $e)
        {
            flashToast("message", $e->getMessage());
        }
		
	}
}
