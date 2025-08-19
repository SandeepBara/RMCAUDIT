<?php
namespace App\Controllers;
use App\Models\model_transaction;
use App\Models\model_razor_pay_response;
use App\Models\model_razor_pay_request;
use Razorpay\Api\Api;
use Exception;

class propOnlineRequest extends AlphaController
{
	protected $db;
    protected $model_razor_pay_response;
    protected $model_razor_pay_request;
    protected $model_transaction;

    public function __construct()
    {
        helper(['db_helper']);
        $this->db = db_connect('db_rmc_property');
        $this->model_transaction = new model_transaction($this->db);
        $this->model_razor_pay_response = new model_razor_pay_response($this->db);
		$this->model_razor_pay_request = new model_razor_pay_request($this->db);
    }

    function __destruct() {
		if (isset($this->db)) $this->db->close();
	}


    public function VerifyPaymentIsDone() {
        if($this->request->getMethod()=='post')
        {
            $order_id = $this->request->getVar("order_id");
            $prop_dtl_id = $this->request->getVar("prop_dtl_id");
            $sql = "SELECT
                        tbl_prop_dtl.holding_no,
                        tbl_prop_dtl.new_holding_no,
                        tbl_razor_pay_request.*
                    FROM tbl_razor_pay_request
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_razor_pay_request.prop_dtl_id
                    LEFT JOIN tbl_razor_pay_response ON tbl_razor_pay_response.razorpay_payment_id = tbl_razor_pay_request.id
                    WHERE
                        tbl_razor_pay_request.status=1
                        AND tbl_razor_pay_request.module='Property'
                        AND tbl_razor_pay_request.order_id IS NOT NULL
                        AND tbl_razor_pay_response.id IS NULL
                        AND tbl_razor_pay_request.id=".$order_id;
            if ($razorPayResult = $this->db->query($sql)->getFirstRow("array")) {
                //return json_encode($result);
                try {    
                    $api_key_id = getenv("razorpay.api_key_id");
                    $api_secret = getenv("razorpay.api_secret_key");
                    $orderId = $razorPayResult["order_id"];

                    $api = new Api($api_key_id, $api_secret);
                    $razorPayStatus = $api->order->fetch($orderId)->payments();
                    if(isset($razorPayStatus->items[0])) {
                        if ($razorPayStatus->items[0]->status=="captured") {
                            $input=[
                                "razorpay_payment_id"=> $razorPayResult["id"],
                                "prop_dtl_id"=> $razorPayResult["prop_dtl_id"],
                                "module"=> "Property",
                                "payable_amt"=> number_format((float) round(($razorPayStatus->items[0]->amount/100), 2), 2, '.', ''),
                                "ip_address"=> "By Scheduler",
                                "merchant_id"=> 0,
                                "razorpay_order_id"=> $razorPayStatus["order_id"],
                                "razorpay_signature"=> "",
                                "code"=> null,
                                "description"=> null,
                                "source"=> null,
                                "reason"=> null,
                                "order_id"=> $razorPayStatus->items[0]->order_id,
                                "payment_id"=> $razorPayStatus->items[0]->id,
                            ];
                            $data=[
                                "prop_dtl_id"=> $razorPayResult["prop_dtl_id"],
                                "fy"=> $razorPayResult["upto_fy"],
                                "qtr"=> $razorPayResult["upto_qtr"],
                                "user_id"=> 0,
                                "payment_mode"=> "Online",
                                "remarks"=> null,
                                "total_payable_amount"=> 0,
                                "payment_date"=> date("Y-m-d", strtotime($razorPayResult["created_on"]))
                            ];
                            $this->db->transBegin();
                            $this->model_razor_pay_response->pay_response($input);
                            $trxn_id=$this->model_transaction->prop_pay_now_online($data, []);

                            if($this->db->transStatus() === FALSE) {
                                $this->db->transRollback();
                                $response = [
                                    "status"=>"failed",
                                    "msg"=>"Rollback trigger"
                                ];
                            } else {
                                $this->db->transCommit();
                                $response = [
                                    "status"=>"captured",
                                    "msg"=>"successfull this payment"
                                ];
                            }
                            return json_encode($response);
                        } else if ($razorPayStatus->items[0]->status=="failed") {
                            $input=[
                                "razorpay_payment_id"=> $razorPayResult["id"],
                                "prop_dtl_id"=> $razorPayResult["prop_dtl_id"],
                                "module"=> "Property",
                                "payable_amt"=> number_format((float) round(($razorPayStatus->items[0]->amount/100), 2), 2, '.', ''),
                                "ip_address"=> "By Scheduler",
                                "merchant_id"=> 0,
                                "razorpay_order_id"=> $razorPayStatus["order_id"],
                                "razorpay_signature"=> "",
                                "code"=> $razorPayStatus->items[0]->error_code,
                                "description"=> $razorPayStatus->items[0]->error_description,
                                "source"=> $razorPayStatus->items[0]->error_source,
                                "reason"=> $razorPayStatus->items[0]->error_reason,
                                "order_id"=> $razorPayStatus->items[0]->order_id,
                                "payment_id"=> $razorPayStatus->items[0]->id
                            ];
                            $this->model_razor_pay_response->pay_response($input);
                            $response = [
                                "status"=>"failed",
                                "msg"=>"failed this payment"
                            ];
                            return json_encode($response);
                        } 
                    } else {
                        $input=[
                            "razorpay_payment_id"=> $razorPayResult["id"],
                            "prop_dtl_id"=> $razorPayResult["prop_dtl_id"],
                            "module"=> "Property",
                            "payable_amt"=> $razorPayResult["payable_amt"],
                            "ip_address"=> "By Scheduler",
                            "merchant_id"=> 0,
                            "razorpay_order_id"=> $razorPayResult["order_id"],
                            "razorpay_signature"=> "",
                            "code"=> null,
                            "description"=> 'Payment failed',
                            "source"=> null,
                            "reason"=> 'payment_failed',
                            "order_id"=> $razorPayResult["order_id"],
                            "payment_id" => "Order Id Not Found in Payment Gateway.",
                            "status" => 1
                        ];
                        $this->model_razor_pay_response->pay_response($input);
                    }
                } catch (Exception $e) {
                    //$e->getMessage();
                    $response = [
                        "status"=>"failed",
                        "msg"=>$e->getMessage()
                    ];
                    return json_encode($response);
                }
            } else {
                $response = [
                    "status"=>"failed",
                    "msg"=>"this request is not found on database"
                ];
                return json_encode($response);
            }
        }
        
    }
    // Code added on 11-05-2022
    


}