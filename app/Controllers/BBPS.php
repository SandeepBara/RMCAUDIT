<?php namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use App\Models\model_bhim;
use App\Models\model_prop_dtl;
use App\Models\model_prop_demand;
use App\Models\model_collection;
use App\Models\model_transaction;
use App\Models\model_transaction_fine_rebet_details;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_tax;
use App\Models\model_online_pay_request;
use App\Models\model_online_pay_response;
use Exception;
use Predis\Client;

class BBPS extends BaseController
{
    use ResponseTrait;
	protected $db_property;
    protected $model_bhim;
	protected $model_prop_dtl;
    protected $model_prop_demand;
	protected $model_collection;
    protected $model_transaction;
	protected $model_transaction_fine_rebet_details;
    protected $model_online_pay_request;
    protected $model_online_pay_response;
	public function __construct()
    {
        helper(['db_helper', 'utility_helper']);
    }

    public function propDBConn() {
        $this->db_property = db_connect(dbConfig("property"));
        $this->model_bhim = new model_bhim($this->db_property);
		$this->model_prop_demand = new model_prop_demand($this->db_property);
		$this->modelpropcoll = new model_collection($this->db_property);
		$this->modelprop = new model_prop_dtl($this->db_property);
        $this->model_transaction = new model_transaction($this->db_property);
		$this->modelpenalty = new model_transaction_fine_rebet_details($this->db_property);
		$this->model_prop_owner_detail = new model_prop_owner_detail($this->db_property);
		$this->model_prop_tax = new model_prop_tax($this->db_property);
        $this->model_online_pay_request = new model_online_pay_request($this->db_property);
        $this->model_online_pay_response = new model_online_pay_response($this->db_property);
    }

    public function index() {
        $inputs = $this->request->getJSON();
        if (!isset($inputs->ulb_code) || trim($inputs->ulb_code)!="1999") {  //ulb code creadential matched
            $errors = "ULB code is not matched !!";
            return $this->fail($errors, 400);
        }
        if (isset($inputs->username) && isset($inputs->password)) {
            return $this->getCustomerDtl($inputs);
        }
        if (isset($inputs->amount) 
                && isset($inputs->ts)
                && isset($inputs->txnReferenceId)
                && isset($inputs->billertxnReferenceId)
                && isset($inputs->paymentMode)) {
            return $this->payCustomerBill();
        }
        if (isset($inputs->txnReferenceId)) {
            return $this->getCustomerBillStatus();
        }
        // database connection
        // holding details find query
    }

    private function getCustomerDtl($inputs) {
        if (!isset($inputs->username) || trim($inputs->username)!="rmcbbps"
                || !isset($inputs->password) || trim($inputs->password)!="@Bbps#19992018RMC") { //username & password creadential matched
            $errors = "creadential is not matched !!";
            return $this->fail($errors, 400);
        }
        if (!isset($inputs->customerId) || trim($inputs->customerId)=="") { //holding no velidation
            $errors = "customerId required.";
            return $this->fail($errors, 400);
        }
        $this->propDBConn();
        if ($holdingResult = $this->model_bhim->searchHoldingDtl($inputs->customerId)) {
            try {
                $input = [
                    'fy' => '2022-2023',
                    'qtr' => 4,
                    'prop_dtl_id' => $holdingResult->id,
                    'user_id'=> 0,
                ];
				if ($DuesYear = $this->model_prop_demand->geDuesYear($holdingResult->id)) {
                    $dueDate  = getDateFromFyAndQtr($DuesYear["max_year"], $DuesYear["max_quarter"]);
					$DuesDetails=$this->model_prop_demand->getPropDemandAmountDetails($input);
                    $payableAmount = round($DuesDetails["PayableAmount"]);
					$input = [
                        "prop_dtl_id" => $input["prop_dtl_id"],
                        "module" => "Property",
                        "merchant_id" => 0,
                        "from_fy_mstr_id" => $DuesYear["min_fy_id"],
                        "from_fy" => $DuesYear["min_year"],
                        "from_qtr" => $DuesYear["min_quarter"],
                        "upto_fy_mstr_id" => $DuesYear["max_fy_id"],
                        "upto_fy" => $input["fy"],
                        "upto_qtr" => $input["qtr"],
                        "demand_amt" => $DuesDetails["DemandAmount"],
                        "penalty_amt" => ($DuesDetails["OnePercentPnalty"] + $DuesDetails["OtherPenalty"]),
                        "discount"=> ($DuesDetails["RebateAmount"] + $DuesDetails["AdvanceAmount"]),
                        "payable_amt" => $payableAmount,
                        "ulb_code" => $inputs->ulb_code
                    ];
                    
					$pg_mas_id = $this->model_online_pay_request->pay_request($input);
                    $billNumber = "BBPS-BILL-".str_pad($pg_mas_id, 5, "0", STR_PAD_LEFT);
                    $billertxnReferenceId = date("his").$pg_mas_id.date("ymd");
                    $updateInput = [
                        "bill_number" => $billNumber,
                        "biller_txn_reference_id" => $billertxnReferenceId
                    ];
                    $this->model_online_pay_request->updateRecord($updateInput, $pg_mas_id);
                    $responseData  = [
                        "ulb_code" => "1999",
                        "customerId" => $inputs->customerId,
                        "amount" => $payableAmount,
                        "billertxnReferenceId" => $billertxnReferenceId,
                        "billNumber" => $billNumber,
                        "customerName" => $holdingResult->owner_name,
                        "billPeriod" => "QUARTERLY",
                        "billDate" => date("d-m-Y"), 
                        "dueDate" => date("d-m-Y", strtotime($dueDate)),
                        "status" => "Accept", 
                        "status_code" => "001", 
                        "reject_reason" => "",
                    ];
                    return $this->respond(($responseData), 200);
				}
                $responseData  = [
                    "ulb_code" => "1999",
                    "customerId" => $inputs->customerId,
                    "amount" => 0,
                    "customerName" => $holdingResult->owner_name,
                    "billPeriod" => "QUARTERLY",
                    "billDate" => date("d-m-Y"), 
                    "status" => "Accept", 
                    "status_code" => "001", 
                    "reject_reason" => "", 
                ];
                return $this->respond(($responseData), 200);
			}
			catch(Exception $e){
				$errors = "Oops, error occurred  !!";
                return $this->fail($errors, 400);
			}
        } else {
            $errors = "holding no. not found.";
            return $this->respond(($errors), 200);
        }
    }

    private function payCustomerBill() {
        $JSONdata = $this->request->getJSON();
        // start validation
        if (!isset($JSONdata->amount) || trim($JSONdata->amount)=="") {
            $errors = "The amount field is required.";
            return $this->fail($errors, 400);
        }
        if (!isset($JSONdata->ts) || trim($JSONdata->ts)=="") {
            $errors = "The ts field is required.";
            return $this->fail($errors, 400);
        }
        if (!isset($JSONdata->txnReferenceId) || trim($JSONdata->txnReferenceId)=="") {
            $errors = "The txnReferenceId field is required.";
            return $this->fail($errors, 400);
        }
        if (!isset($JSONdata->billertxnReferenceId) || trim($JSONdata->billertxnReferenceId)=="") {
            $errors = "The billertxnReferenceId field is required.";
            return $this->fail($errors, 400);
        }
        if (!isset($JSONdata->paymentMode) || trim($JSONdata->paymentMode)=="") {
            $errors = "The paymentMode field is required.";
            return $this->fail($errors, 400);
        }
        // end validation
        $this->propDBConn();
        $input = [
            "billertxnReferenceId" => $JSONdata->billertxnReferenceId,
            "amount" => $JSONdata->amount,
        ];
        if ($result = $this->model_online_pay_request->getRecordBYRefNo($input)) {
            $input = [
                "online_payment_id" => $result->id,
                "prop_dtl_id" => $result->prop_dtl_id,
                "module" => $result->module,
                "payment_mode" => $JSONdata->paymentMode,
                "payable_amt" => $JSONdata->amount,
                "bill_number" => $result->bill_number,
                "biller_txn_reference_id" => $result->biller_txn_reference_id,
                "txn_reference_id" => $JSONdata->txnReferenceId,
                "source" => "ICICI",
                "created_on" => date("Y-m-d H:i:s"),
                "status" => 1
            ];
            // check payment done or not depends on reference no
            if ($payResponseResult = $this->model_online_pay_response->getRecordBYPayId($input["online_payment_id"])) {
                $result = [
                    "ulb_code" => $JSONdata->ulb_code,
                    "txn_id" => $payResponseResult->bill_number,
                    "customerId" => $result->new_holding_no,
                    "bbps_txn_id" => "BBPS".$payResponseResult->biller_txn_reference_id,
                    "status" => "Rejected",
                    "status_code" => "002",
                    "reject_reason" => "Payment already done.", 
                    "approval_txn_id" => $payResponseResult->tran_no
                ];
                return $this->respond($result, 200);
            } else {
                $this->db_property->transBegin();
                $data = [
                    "prop_dtl_id"=> $result->prop_dtl_id,
                    "fy"=> $result->upto_fy,
                    "qtr"=> $result->upto_qtr,
                    "user_id"=> 0,
                    "payment_mode"=> "Online",
                    "remarks"=> null,
                    "total_payable_amount"=> 0,
                ];
                $trxn_id=$this->model_transaction->prop_pay_now($data, []);
                $input["transaction_id"] = $trxn_id;
                $input["request_payload"] = json_encode($JSONdata);
                $this->model_online_pay_response->pay_response($input);
                $transaction_no = "";
                if ($transaction_result = $this->model_online_pay_response->getTranNo($trxn_id)) {
                    $transaction_no = $transaction_result->tran_no;
                }
                if($this->db_property->transStatus() === FALSE) {
                    $errors = "Opps, Something wrong payment is denied";
                    return $this->fail($errors, 400);
                } else {
                    $this->db_property->transCommit();
                    $respond = [
                        "ulb_code" => $JSONdata->ulb_code,
                        "txn_id" => $result->bill_number,
                        "customerId" =>  $result->new_holding_no,
                        "bbps_txn_id" =>  $result->biller_txn_reference_id,
                        "status" => "Success", 
                        "status_code" => "000",
                        "reject_reason" => "", 
                        "approval_txn_id" => $transaction_no
                    ];
                    return $this->respond($respond, 200);
                } 
                return $this->respond($result, 200);
            }
        }
        // Request data is not match (with my transaction)
        $respond = [
            "ulb_code" => $JSONdata->ulb_code,
            "txn_id" => $JSONdata->txnReferenceId,
            "bbps_txn_id" =>  $JSONdata->billertxnReferenceId,
            "status" => "Rejected",
            "status_code" => "002",
            "reject_reason" => "Request data is not match !!!", 
        ];
        return $this->fail($respond, 400);
    }

    private function getCustomerBillStatus() {
        $JSONdata = $this->request->getJSON();  
        if (!isset($JSONdata->txnReferenceId) || trim($JSONdata->txnReferenceId)=="") {  //ulb code creadential matched
            $errors = "The txnReferenceId field is required.";
            return $this->fail($errors, 400);
        }
        $this->propDBConn();
        
        if ($result = $this->model_online_pay_response->getRefStatus($JSONdata->txnReferenceId)) {
            if ($result->online_pay_response_id=="" || $result->tran_no==NULL) {
                $respond = [
                    "ulb_code" => $JSONdata->ulb_code,
                    "bbps_txn_id" => $JSONdata->txnReferenceId,
                    "status" => "Rejected",
                    "status_code" => "002",
                    "reject_reason" => "Payment uncomplete !!"
                ];
                return $this->respond($respond, 200);
            }
            $respond = [
                "ulb_code" => $JSONdata->ulb_code,
                "txn_id" => $result->biller_txn_reference_id,
                "customerId" => $result->new_holding_no,
                "bbps_txn_id" => $JSONdata->txnReferenceId,
                "status" => "Accept",
                "status_code" => "001",
                "reject_reason" => "",
                "approval_txn_id" => $result->tran_no
            ];
            return $this->respond($respond, 200);
        }
        $respond = [
            "ulb_code" => "1999",
            "bbps_txn_id" => $JSONdata->txnReferenceId,
            "status" => "Rejected",
            "status_code" => "002",
            "reject_reason" => "Reference id not found !!!"
        ];
        return $this->respond($respond, 200);
    }
}
