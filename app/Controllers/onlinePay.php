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
use App\Models\model_razor_pay_response;
use App\Models\model_razor_pay_request;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_tax;

use Razorpay\Api\Api;
use Exception;

class onlinePay extends HomeController
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
	protected $model_razor_pay_response;
	protected $model_razor_pay_request;
	protected $modelprop;

	protected $api_key_id = "";
	protected $api_secret = "";

	public function __construct()
    {

        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper', 'rozarpay_helper']);

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
		$this->model_razor_pay_response = new model_razor_pay_response($this->db);
		$this->model_razor_pay_request = new model_razor_pay_request($this->db);
		$this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
		$this->model_prop_tax = new model_prop_tax($this->db);

		$this->api_key_id = getenv("razorpay.api_key_id");
		$this->api_secret = getenv("razorpay.api_secret_key");
    }

	function __destruct() {
		if(isset($this->db)) $this->db->close();
		if(isset($this->dbSystem)) $this->dbSystem->close();
	}

	# Property

	public function propertyPaymentProceed($prop_dtl_id_MD5)
	{
		$data =(array)null;
		$data["id"]=$prop_dtl_id_MD5;
		$data["basic_details"] = $this->modelprop->basic_details(["id"=> $prop_dtl_id_MD5]);
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


			$input = [
					"prop_dtl_id"=> $data["prop_dtl_id"],
					"module"=> "Property",
					"merchant_id"=> 0,
					"from_fy_mstr_id"=> $data["DuesYear"]["min_fy_id"],
					"from_fy"=> $data["DuesYear"]["min_year"],
					"from_qtr"=> $data["DuesYear"]["min_quarter"],
					"upto_fy_mstr_id"=> $data["DuesYear"]["max_fy_id"],
					"upto_fy"=> $data["DuesYear"]["max_year"],
					"upto_qtr"=> $data["DuesYear"]["max_quarter"],
					"demand_amt"=> $data["DuesDetails"]["DemandAmount"],
					"penalty_amt"=> ($data["DuesDetails"]["OnePercentPnalty"] + $data["DuesDetails"]["OtherPenalty"]),
					"discount"=> ($data["DuesDetails"]["RebateAmount"] + $data["DuesDetails"]["AdvanceAmount"]),
					"payable_amt"=> $data["DuesDetails"]["PayableAmount"],
				];
			$data["pg_mas_id"]=$this->model_razor_pay_request->pay_request($input);

			try {
				helper('rozarpay_helper');
				includeRazorLibrary();
				$this->api = new \Razorpay\Api\Api($this->api_key_id, $this->api_secret);
				$data["order_id"] = $this->api->order->create(array(
						'receipt' => $data["pg_mas_id"],
						'amount' => $data["DuesDetails"]["PayableAmount"]*100,
						'currency' => 'INR'
					)
				);
			} catch (\Exception $e) {
					//echo "The amount exceeds the maximum payment limit of the payment gateway";
					if($e->getMessage() == 'Amount exceeds maximum amount allowed.')
					{
						echo "Razorpay\Api\Errors\BadRequestError: Amount exceeds maximum amount allowed.";
					}else{
						echo $e->getMessage();
					}
					die();
			}
			return view('Citizen/pay_demand_online', $data);
		}
	}

	public function paymentSuccess($prop_dtl_id, $pg_mas_id, $razorpay_payment_id, $razorpay_order_id, $razorpay_signature)
	{
		$data =(array)null;

		try
		{
			//validate razor pay signature
			includeRazorLibrary();
			$api = new \Razorpay\Api\Api($this->api_key_id, $this->api_secret);
			$attributes  = array('razorpay_signature'  => $razorpay_signature,  'razorpay_payment_id'  => $razorpay_payment_id ,  'razorpay_order_id' => $razorpay_order_id);
			$order  = $api->utility->verifyPaymentSignature($attributes);


			$request=$this->model_razor_pay_request->getRecord($pg_mas_id);

			if($request["order_id"]!=$razorpay_order_id){
				throw new Exception("Payment gateway order Id is not matching");
			}

			$input=[
					"razorpay_payment_id"=> $pg_mas_id,
					"prop_dtl_id"=> $prop_dtl_id,
					"module"=> "Property",
					"payable_amt"=> $request["payable_amt"],
					"ip_address"=> get_client_ip(),
					"merchant_id"=> 0,
					"razorpay_order_id"=> $razorpay_order_id,
					"razorpay_signature"=> $razorpay_signature,
					"code"=> null,
					"description"=> null,
					"source"=> null,
					"reason"=> null,
					"order_id"=> $razorpay_order_id,
					"payment_id"=> $razorpay_payment_id,
			];
			$data=[
				"prop_dtl_id"=> $prop_dtl_id,
				"fy"=> $request["upto_fy"],
				"qtr"=> $request["upto_qtr"],
				"user_id"=> 0,
				"payment_mode"=> "Online",
				"remarks"=> null,
				"total_payable_amount"=> 0,
			];

			$this->db->transBegin();
			$this->model_razor_pay_response->pay_response($input);
			$trxn_id=$this->model_transaction->prop_pay_now($data, []);

			if($this->db->transStatus() === FALSE)
			{
				$this->db->transRollback();
				flashToast("message", "Some error occured, Transaction process has bee rollback!!!");
				return $this->response->redirect(base_url('CitizenProperty/Citizen_confirm_payment/'.md5($prop_dtl_id)));
			}
			else
			{
				$this->db->transCommit();
				flashToast("message", "Transaction successfull !!!");
				return $this->response->redirect(base_url('onlinePay/payment_online_receipt/'.md5($trxn_id)));
			}
		}
		catch (Exception $e)
        {
            flashToast("message", $e->getMessage());
            return $this->response->redirect(base_url('CitizenProperty/Citizen_confirm_payment/'.md5($prop_dtl_id)));
        }
	}

	public function paymentFailed($prop_dtl_id, $pg_mas_id, $code=null, $description=null, $source=null, $step=null, $reason=null, $order_id=null, $payment_id=null)
	{
		$request=$this->model_razor_pay_request->getRecord($pg_mas_id);
		$input=[
					"razorpay_payment_id"=> $pg_mas_id,
					"prop_dtl_id"=> $prop_dtl_id,
					"module"=> $request["module"],
					"payable_amt"=> $request["payable_amt"],
					"ip_address"=> get_client_ip(),
					"merchant_id"=> 0,
					"razorpay_order_id"=> $order_id,
					"razorpay_signature"=> null,
					"code"=> $code,
					"description"=> $description,
					"source"=> $source,
					"reason"=> $reason,
					"order_id"=> $order_id,
					"payment_id"=> $payment_id,
			];
		$this->model_razor_pay_response->pay_response($input);
		flashToast("message", "Oops, Something went wrong, payment failed");

		if($request["module"]=="Property")
		return $this->response->redirect(base_url('onlinePay/propertyPaymentProceed/'.md5($prop_dtl_id)));
		else
		return $this->response->redirect(base_url('CitizenDtl/citizen_saf_payment_details'));
	}

	public function payment_online_receipt($tran_no=null)
	{
		$data =(array)null;
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		//$path=base_url('citizenPaymentReceipt/payment_online_receipt/'.$ulb_mstr_id.'/'.$tran_no);
		$path=base_url('citizenPaymentReceipt/payment_jsk_receipt/'.$ulb_mstr_id.'/'.$tran_no);
		$data['ss']=qrCodeGeneratorFun($path);
		$data['tran_no']=$tran_no;
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
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

	public function Ajax_getOnlinePropPayableAmount(){
		$data=(array)null;
		$Session = Session();
		$ulb = $Session->get('ulb_dtl');

		$out = ["status"=> false, "message"=> "Invalid method"];
		if($this->request->getMethod()=='post'){


			$input = [
				'fy' => $this->request->getVar('fy'),
				'qtr' => $this->request->getVar('qtr'),
				'prop_dtl_id' => $this->request->getVar('prop_dtl_id'),
				'user_id'=> 0,
				];


			//print_var($data);exit;
			$data["DuesYear"]=$this->model_prop_demand->geDuesYear($input["prop_dtl_id"]);
			try{
				if($data["DuesYear"])
				{
					$data["DuesDetails"]=$this->model_prop_demand->getPropDemandAmountDetails($input);



					$input = [
							"prop_dtl_id"=> $input["prop_dtl_id"],
							"module"=> "Property",
							"merchant_id"=> 0,
							"from_fy_mstr_id"=> $data["DuesYear"]["min_fy_id"],
							"from_fy"=> $data["DuesYear"]["min_year"],
							"from_qtr"=> $data["DuesYear"]["min_quarter"],
							"upto_fy_mstr_id"=> $data["DuesYear"]["max_fy_id"], // gadbad hai
							"upto_fy"=> $input["fy"],
							"upto_qtr"=> $input["qtr"],
							"demand_amt"=> $data["DuesDetails"]["DemandAmount"],
							"penalty_amt"=> ($data["DuesDetails"]["OnePercentPnalty"] + $data["DuesDetails"]["OtherPenalty"]),
							"discount"=> ($data["DuesDetails"]["RebateAmount"] + $data["DuesDetails"]["AdvanceAmount"]),
							"payable_amt"=> round($data["DuesDetails"]["PayableAmount"]),
						];
					$data["pg_mas_id"]=$this->model_razor_pay_request->pay_request($input);


					helper('rozarpay_helper');
					includeRazorLibrary();
					$this->api = new \Razorpay\Api\Api($this->api_key_id, $this->api_secret);
					$order_id = $this->api->order->create(array(
							'receipt' => $data["pg_mas_id"],
							'amount' => round($data["DuesDetails"]["PayableAmount"])*100,
							'currency' => 'INR'
						)
					);
					//print_var($order_id["id"]);
					$this->model_razor_pay_request->updateRecord(["order_id"=> $order_id["id"]], $data["pg_mas_id"]);

					$razorpay_param=[
							"key"=> $this->api_key_id,
							"order_id"=> $order_id["id"],
							"amount"=> $data["DuesDetails"]["PayableAmount"]*100,
							"currency"=> "INR",
							"name"=> $ulb["ulb_name"], // ULB Name
							"description"=> "SAF Tax Payment",
							"pg_mas_id"=> $data["pg_mas_id"],
					];

					$out = ["status"=> true, "data"=> $razorpay_param, "message"=> ""];
				}
			}
			catch(Exception $e){
				$out = ["status"=> false, "data"=> [], "message"=> "Exception: ".$e->getMessage()];
			}

		}
		echo json_encode($out);
	}
}
