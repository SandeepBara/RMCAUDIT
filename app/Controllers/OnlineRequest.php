<?php 
namespace App\Controllers;

use App\Models\model_transaction;
use App\Models\model_razor_pay_response;
use App\Models\model_razor_pay_request;
use CodeIgniter\Controller;
use Razorpay\Api\Api;

use App\Models\TradeRazorPayModel;
use App\Models\ModelTradeLicense;
use App\Controllers\TradeCitizen;
use App\Models\TradeApplicationTypeMstrModel;
use App\Models\TradeTransactionModel;
use App\Models\model_trade_transaction_fine_rebet_details;
use App\Models\TradeApplyLicenceModel;
use App\Models\model_ulb_mstr;
use App\Models\model_ward_mstr;

use App\Models\WaterRazorPayModel;
use App\Models\model_view_water_consumer;
use App\Models\WaterPaymentModel;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterPenaltyModel;
use App\Models\Water_Transaction_Model;
// -------------------------------------------------------
use App\Models\WaterViewConnectionFeeModel;
use App\Models\WaterPenaltyInstallmentModel;
use App\Controllers\WaterApplyNewConnectionCitizen;
use App\Models\WaterConnectionDetailsViewModel;
use App\Models\WaterSiteInspectionModel;
use App\Models\WaterMobileModel;
use App\Models\Citizensw_water_model;
use App\Models\model_prop_demand;
use App\Models\model_trade_sms_log;
use App\Models\model_water_sms_log;
use App\Models\Siginsw_water_model;

use Exception;

class OnlineRequest extends Controller {

    protected $db;
    protected $model_razor_pay_response;
    protected $model_razor_pay_request;
    protected $model_transaction;

    protected $TradeRazorPayModel;
    protected $ModelTradeLicense;
    protected $TradeCitizenController;
    protected $tradeapplicationtypemstrmodel;
    protected $TradeTransactionModel;
    protected $model_trade_transaction_fine_rebet_details;
    protected $TradeApplyLicenceModel;
    protected $model_ulb_mstr;
    protected $model_ward_mstr;

    protected $WaterRazorPayModel;
    protected $model_view_water_consumer;
    protected $payment_model;
    protected $demand_model;
    protected $WaterPenaltyModel;
    protected $trans_model;
    // -------------------
    protected $conn_fee;
    protected $penalty_installment_model;
    protected $apply_conn;
    protected $water_conn_dtls;
    protected $site_ins_model;
    protected $WaterMobileModel;
    protected $Citizensw_water_model;
    protected $Siginsw_water_model;
    protected $dbSystem;
    protected $sms_log;
    protected $model_prop_demand;
    protected $water_sms_log;
    protected $model_trade_sms_log;
    protected $modelUlb;


    public function __construct()
    {
        helper(['db_helper','form_helper','sms_helper']);
        $this->db= db_connect('db_rmc_property');
        $this->sms_log = new model_water_sms_log($this->db);

    }

    public function property()
    {

        $this->db = db_connect('db_rmc_property');
        $this->model_transaction = new model_transaction($this->db);
        $this->model_razor_pay_response = new model_razor_pay_response($this->db);
		$this->model_razor_pay_request = new model_razor_pay_request($this->db);
       /*  $api_key_id = getenv("razorpay.api_key_id");
        $api_secret = getenv("razorpay.api_secret_key");
        $orderId = "order_JxtgocelbETn4Y";
        $api = new Api($api_key_id, $api_secret);
        $razorPayStatus = $api->order->fetch($orderId)->payments();
        print_var($razorPayStatus);
        die(); */
        $paymentDate = date("Y-m-d");
        if ($this->request->getVar("date")) {
            $paymentDate = $this->request->getVar("date");
        }
        $sql = "SELECT
                    tbl_prop_dtl.holding_no,
                    tbl_prop_dtl.new_holding_no,
                    tbl_razor_pay_request.*
                FROM tbl_razor_pay_request
                INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_razor_pay_request.prop_dtl_id
                LEFT JOIN tbl_transaction ON tbl_transaction.prop_dtl_id=tbl_prop_dtl.id 
                                                AND tbl_transaction.tran_type='Property' 
                                                AND tbl_razor_pay_request.created_on::DATE=tbl_transaction.tran_date
                LEFT JOIN tbl_razor_pay_response ON tbl_razor_pay_response.razorpay_payment_id = tbl_razor_pay_request.id
                WHERE
                    tbl_razor_pay_request.status=1
                    AND tbl_razor_pay_request.module='Property'
                    AND tbl_razor_pay_request.created_on::DATE='".$paymentDate."'
                    AND tbl_razor_pay_request.order_id IS NOT NULL
                    AND tbl_transaction.id IS NULL
                    AND tbl_razor_pay_response.id IS NULL
                ORDER BY tbl_razor_pay_request.id ASC";
        echo "<table style='width: 100%;'>";
            echo "<tr>";
                echo "<td>#</td>";
                echo "<td>ID</td>";
                echo "<td>Date</td>";
                echo "<td>Holding No</td>";
                echo "<td>Payable Amount</td>";
                echo "<td>Order Id</td>";
                echo "<td>Status</td>";
            echo "</tr>";
        if ($result = $this->db->query($sql)->getResultArray()) {
            foreach ($result as $key => $propValue) {
                echo "<tr>";
                    echo "<td>".($key+1)."</td>";
                    echo "<td>".$propValue["id"]."</td>";
                    echo "<td>".$propValue["created_on"]."</td>";
                    echo "<td>".$propValue["holding_no"]." <-> ".$propValue["new_holding_no"]."</td>";
                    echo "<td>".$propValue["payable_amt"]."</td>";
                    echo "<td>".$propValue["order_id"]."</td>";
                
                try {    
                    $api_key_id = getenv("razorpay.api_key_id");
                    $api_secret = getenv("razorpay.api_secret_key");
                    $orderId = $propValue["order_id"];

                    $api = new Api($api_key_id, $api_secret);
                    $razorPayStatus = $api->order->fetch($orderId)->payments();
                    if(isset($razorPayStatus->items[0])) {
                        if ($razorPayStatus->items[0]->status=="captured") {
                            $input=[
                                "razorpay_payment_id"=> $propValue["id"],
                                "prop_dtl_id"=> $propValue["prop_dtl_id"],
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
                                "prop_dtl_id"=> $propValue["prop_dtl_id"],
                                "fy"=> $propValue["upto_fy"],
                                "qtr"=> $propValue["upto_qtr"],
                                "user_id"=> 0,
                                "payment_mode"=> "Online",
                                "remarks"=> null,
                                "total_payable_amount"=> 0,
                                "payment_date"=> date("Y-m-d", strtotime($propValue["created_on"]))
                            ];
                            $this->db->transBegin();
                            $this->model_razor_pay_response->pay_response($input);
                            $trxn_id=$this->model_transaction->prop_pay_now_online($data, []);

                            if($this->db->transStatus() === FALSE) {
                                $this->db->transRollback();
                            } else {
                                $this->db->transCommit();
                            }
                        } else if ($razorPayStatus->items[0]->status=="failed") {
                            $input=[
                                "razorpay_payment_id"=> $propValue["id"],
                                "prop_dtl_id"=> $propValue["prop_dtl_id"],
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
                        }
                        echo "<td style='color: green'>Transaction ".$razorPayStatus->items[0]->status."</td>";
                    } else {
                        $input=[
                            "razorpay_payment_id"=> $propValue["id"],
                            "prop_dtl_id"=> $propValue["prop_dtl_id"],
                            "module"=> "Property",
                            "payable_amt"=> $propValue["payable_amt"],
                            "ip_address"=> "By Scheduler",
                            "merchant_id"=> 0,
                            "razorpay_order_id"=> $propValue["order_id"],
                            "razorpay_signature"=> "",
                            "code"=> null,
                            "description"=> 'Payment failed',
                            "source"=> null,
                            "reason"=> 'payment_failed',
                            "order_id"=> $propValue["order_id"],
                            "payment_id" => "Order Id Not Found in Payment Gateway.",
                            "status" => 1
                        ];
                        $this->model_razor_pay_response->pay_response($input);
                        echo "<td style='color: blue'>Order Id Not Found in Payment Gateway.</td>";
                    }
                } catch (\Exception $e) {
                        echo "<td style='color: red'>".$e->getMessage()."</td>";
                }
                echo "</tr>";
            }
        }
        echo "</table>";
    }

    public function menuListDel() {
        $client = new \Predis\Client();
        $client->del("menu_list_36");
        $client->del("menu_list_15");
        $client->del("menu_list_3");
        $client->del("menu_list_17");
        $client->del("menu_list_7");
        $client->del("menu_list_16");
        $client->del("menu_list_13");
        $client->del("menu_list_23");
        $client->del("menu_list_10");
        $client->del("menu_list_18");
        $client->del("menu_list_9");
        $client->del("menu_list_24");
        $client->del("menu_list_14");
        $client->del("menu_list_6");
        $client->del("menu_list_20");
        $client->del("menu_list_1");
        $client->del("menu_list_12");
        $client->del("menu_list_8");
        $client->del("menu_list_4");
        $client->del("menu_list_11");
        $client->del("menu_list_19");
        $client->del("menu_list_2");
    }

    #-------trade-----------------------------------
    public function Trade()
    {

        $this->db = db_connect('db_rmc_trade');
        if($db_system = dbSystem())
        {
            $this->dbSystem = db_connect($db_system); 
        }
        $this->model_transaction = new model_transaction($this->db);
        $this->model_razor_pay_response = new model_razor_pay_response($this->db);
        $this->TradeRazorPayModel= new TradeRazorPayModel($this->db);
        $this->ModelTradeLicense= new ModelTradeLicense($this->db);
        $this->TradeCitizenController= new TradeCitizen($this->db);
        $this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->db);        
        $this->TradeTransactionModel= new TradeTransactionModel($this->db);        
        $this->model_trade_transaction_fine_rebet_details = new model_trade_transaction_fine_rebet_details($this->db);
        $this->TradeApplyLicenceModel=new TradeApplyLicenceModel($this->db);
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr=new model_ward_mstr($this->dbSystem);
       
        
        $paymentDate = date("Y-m-d");
        if ($this->request->getVar("date")) 
        {
            $paymentDate = $this->request->getVar("date");
        }
        $sql =" SELECT
                    tbl_apply_licence.application_no,
                    tbl_apply_licence.license_no,
                    tbl_razor_pay_request.*,
                    tbl_razor_pay_request.razorpay_order_id as order_id,
                    tbl_razor_pay_request.amount as payable_amt
                FROM tbl_razor_pay_request
                INNER JOIN tbl_apply_licence ON tbl_apply_licence.id=tbl_razor_pay_request.apply_licence_id
                LEFT JOIN tbl_razor_pay_response ON tbl_razor_pay_response.razor_pay_request_id = tbl_razor_pay_request.id
                WHERE
                    tbl_razor_pay_request.status=2
                    AND tbl_razor_pay_request.created_on::DATE='".$paymentDate."'
                    AND tbl_razor_pay_request.razorpay_order_id IS NOT NULL
                    AND tbl_razor_pay_response.id IS NULL
                ORDER BY tbl_razor_pay_request.id ASC
            ";        
        echo "<table style='width: 100%;'>";
            echo "<tr>";
                echo "<td>#</td>";
                echo "<td>ID</td>";
                echo "<td>Date</td>";
                echo "<td>Application No</td>";
                echo "<td>Payment For</td>";
                echo "<td>Payable Amount</td>";
                echo "<td>Order Id</td>";
                echo "<td>Status</td>";
            echo "</tr>";
        if ($result = $this->db->query($sql)->getResultArray()) 
        {
            foreach ($result as $key => $propValue) 
            {
                echo "<tr>";
                    echo "<td>".($key+1)."</td>";
                    echo "<td>".$propValue["id"]."</td>";
                    echo "<td>".$propValue["created_on"]."</td>";
                    echo "<td>".$propValue["application_no"]." <-> ".$propValue["license_no"]."</td>";
                    echo "<td>".$propValue["payment_from"]."</td>";
                    echo "<td>".$propValue["payable_amt"]."</td>";
                    echo "<td>".$propValue["order_id"]."</td>";
                
                try 
                {    
                    $api_key_id = getenv("razorpay.api_key_id");
                    $api_secret = getenv("razorpay.api_secret_key");
                    //$orderId = "order_JsOZ9MPJf5dO6C";
                    $orderId = $propValue["order_id"];
                    // die($api_key_id);
                    $api = new Api($api_key_id, $api_secret);
                    $razorPayStatus = $api->order->fetch($orderId)->payments();
                    if(isset($razorPayStatus->items[0])) 
                    {
                        if ($razorPayStatus->items[0]->status=="captured") 
                        {
                            $input=[
                                "request_id"=> $propValue["id"],
                                "apply_licence_id"=> $propValue["apply_licence_id"],
                                "merchant_id"=> $propValue["merchant_id"],
                                "amount"=> number_format((float) round(($razorPayStatus->items[0]->amount/100), 2), 2, '.', ''),
                                "ip_address"=> "By Scheduler",
                                "order_id"=> $propValue["order_id"],
                                "razorpay_payment_id"=>$razorPayStatus->items[0]->id,
                                "razorpay_signature"=> $razorPayStatus->items[0]->razorpay_signature??null,
                                "code"=> null,
                                "description"=> null,
                                "source"=> null,
                                "reason"=> null,
                                "order_id"=> $razorPayStatus->items[0]->order_id,
                            ]; 
                            $this->db->transBegin();
                            $this->model_razor_pay_response->pay_responseTrade($input);

                            #--------------------------------------------------------------
                            $data["license"] = $this->ModelTradeLicense->apply_licence_md5(md5($propValue["apply_licence_id"]));
                            # Calculating rate
                            {
                                $denial_amount = 0;
                                $sql_notice = "select * from tbl_denial_notice where apply_id =".$propValue["apply_licence_id"]." and status = 2";

                                $noticeDetails = $this->TradeTransactionModel->row_query($sql_notice,array())[0]??[];

                                if(!empty($noticeDetails))
                                {
                                    $denial_amount = getDenialAmountTrade($noticeDetails['created_on'],date('Y-m-d',strtotime($propValue["created_on"])));
                                
                                }
                                $args['areasqft']=(float)$data["license"]['area_in_sqft'];
                                $args['applytypeid']=$data["license"]["application_type_id"];
                                $args['estdate']=$data["license"]['application_type_id']==1?$data["license"]["establishment_date"]:$data["license"]['valid_from'];
                                $args['tobacco_status']=$data["license"]["tobacco_status"];
                                $args['licensefor']=$data["license"]["licence_for_years"];
                                $args['nature_of_business'] = $data['license']['nature_of_bussiness'];
                                $args['curdate'] = date('Y-m-d',strtotime($propValue["created_on"]));
                                $rate_data=$this->TradeCitizenController->getcharge($args);
                                $rate_data= json_decode(json_encode(json_decode($rate_data)), true);
                            }

                            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id(md5($data["license"]['application_type_id']));

                            $transact_arr=array();
                            $transact_arr['related_id']=$data["license"]['id'];
                            $transact_arr['ward_mstr_id']=$data["license"]["ward_mstr_id"];
                            $transact_arr['transaction_type']=$data["application_type"]["application_type"]??null;
                            $transact_arr['transaction_date']= date('Y-m-d',strtotime($propValue["created_on"]));
                            $transact_arr['payment_mode']='Online';                          
                            $transact_arr['paid_amount']=$rate_data['total_charge']+$denial_amount;
                            $transact_arr['penalty']=$rate_data['penalty']+$denial_amount+$rate_data['arear_amount'];
                            $transact_arr['status']=1; 
                            $transact_arr['emp_details_id']='0';                                    
                            $transact_arr['created_on']=date('Y-m-d H:i:s');
                            $transact_arr['ip_address']=$input['ip_address'];  

                            $transaction_id = $this->TradeTransactionModel->insertPayment($transact_arr); 
                            //echo $transaction_id;
                            
                            $trafinerebate = array();
                            $trafinerebate['transaction_id']=$transaction_id;
                            $trafinerebate['head_name']='Delay Apply License';
                            $trafinerebate['amount']=$rate_data['penalty'];
                            $trafinerebate['value_add_minus']='Add';
                            $trafinerebate['created_on']=date('Y-m-d H:i:s');
                            $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);
                            $denialAmount=$denial_amount+$rate_data['arear_amount'];
                            if($denialAmount > 0)
                            {
                                $denial = array();  // denial insert
                                $denial['transaction_id']=$transaction_id;
                                $denial['head_name']='Denial Apply';
                                $denial['amount']=$denialAmount;
                                $denial['value_add_minus']='Add';
                                $denial['created_on']=date('Y-m-d H:i:s');
                                $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($denial);
                            }
                            $this->TradeApplyLicenceModel->updateApplyLicencePaymentStatusClear($data["license"]["id"]);
                            # Update Provisional No
                            if($data["application_type"]["application_type"]==1)
                            {

                                $get_ulb_id=session()->get('ulb_dtl');
                                $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($get_ulb_id["ulb_mstr_id"]);
                                
                                $warddet=$this->model_ward_mstr->getWardNoBywardId($data["license"]["ward_mstr_id"]);
                                $ward_no=$warddet["ward_no"];
                                $short_ulb_name=$data["ulb_mstr_name"]["short_ulb_name"];
                                $prov_no=$short_ulb_name.$ward_no.date('mdy').$data["license"]['id'];
                                $this->TradeApplyLicenceModel->update_prov_no($data["license"]['id'], $prov_no);   
                            }
                            #---------------------------------------------------------------
                            

                            if($this->db->transStatus() === FALSE) 
                            {
                                $this->db->transRollback();
                            } 
                            else 
                            {
                                $this->db->transCommit();
                            }
                        } 
                        else if ($razorPayStatus->items[0]->status=="failed") 
                        {
                            $input=[
                                "request_id"=> $propValue["id"],
                                "apply_licence_id"=> $propValue["apply_licence_id"],
                                "merchant_id"=> $propValue["merchant_id"],
                                "amount"=> number_format((float) round(($razorPayStatus->items[0]->amount/100), 2), 2, '.', ''),
                                "ip_address"=> "By Scheduler",
                                "order_id"=> $razorPayStatus["order_id"]??null,
                                "razorpay_signature"=> "",
                                "error_code"=> $razorPayStatus->items[0]->error_code,
                                "error_desc"=> $razorPayStatus->items[0]->error_description,
                                "error_source"=> $razorPayStatus->items[0]->error_source,
                                "error_step"=> $razorPayStatus->items[0]->step,
                                "error_reason"=> $razorPayStatus->items[0]->error_reason,
                                "order_id"=> $razorPayStatus->items[0]->order_id,
                                "razorpay_payment_id"=> $razorPayStatus->items[0]->id
                            ];
                            $this->model_razor_pay_response->pay_responseTrade($input);
                        }
                        echo "<td style='color: green'>Transaction ".$razorPayStatus->items[0]->status."</td>";
                    } 
                    else 
                    {
                        $input=[
                            "request_id"=> $propValue["id"],
                            "apply_licence_id"=> $propValue["apply_licence_id"],
                            "merchant_id"=> $propValue["merchant_id"],
                            "amount"=> $propValue["amount"],
                            "ip_address"=> "By Scheduler",
                            "order_id"=> $propValue["order_id"],
                            "razorpay_signature"=> "",
                            "error_code"=> null,
                            "error_desc"=> 'Payment failed',
                            "error_source"=> null,
                            "error_reason"=> 'payment_failed',
                            "order_id"=> $propValue["order_id"],
                            "razorpay_payment_id" => "Order Id Not Found in Payment Gateway.",
                            "status" => 1
                        ];
                        $this->model_razor_pay_response->pay_responseTrade($input);
                        echo "<td style='color: blue'>Order Id Not Found in Payment Gateway.</td>";
                    }
                } 
                catch (\Exception $e) 
                {
                        echo "<td style='color: red'>".$e->getMessage() ." file-".$e->getFile()." Line-".$e->getLine()."</td>";
                }
                echo "</tr>";
            }
        }
        echo "</table>";
    }
    public function testApi($id) 
    {

        $this->db = db_connect('db_rmc_trade');
        if($db_system = dbSystem())
        {
            $this->dbSystem = db_connect($db_system); 
        }
        $this->model_transaction = new model_transaction($this->db);
        $this->model_razor_pay_response = new model_razor_pay_response($this->db);
        $this->TradeRazorPayModel= new TradeRazorPayModel($this->db);
        $this->ModelTradeLicense= new ModelTradeLicense($this->db);
        $this->TradeCitizenController= new TradeCitizen($this->db);
        $this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->db);        
        $this->TradeTransactionModel= new TradeTransactionModel($this->db);        
        $this->model_trade_transaction_fine_rebet_details = new model_trade_transaction_fine_rebet_details($this->db);
        $this->TradeApplyLicenceModel=new TradeApplyLicenceModel($this->db);
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr=new model_ward_mstr($this->dbSystem);
        
        $sql =" SELECT
                    tbl_apply_licence.application_no,
                    tbl_apply_licence.license_no,
                    tbl_razor_pay_request.*,
                    tbl_razor_pay_request.razorpay_order_id as order_id,
                    tbl_razor_pay_request.amount as payable_amt
                FROM tbl_razor_pay_request
                INNER JOIN tbl_apply_licence ON tbl_apply_licence.id=tbl_razor_pay_request.apply_licence_id
                LEFT JOIN tbl_razor_pay_response ON tbl_razor_pay_response.razor_pay_request_id = tbl_razor_pay_request.id
                WHERE
                    tbl_razor_pay_request.status=2
                    AND tbl_razor_pay_request.id= $id
                    AND tbl_razor_pay_request.razorpay_order_id IS NOT NULL
                    AND tbl_razor_pay_response.id IS NULL
                ORDER BY tbl_razor_pay_request.id ASC
            ";        
        echo "<table style='width: 100%;'>";
            echo "<tr>";
                echo "<td>#</td>";
                echo "<td>ID</td>";
                echo "<td>Date</td>";
                echo "<td>Application No</td>";
                echo "<td>Payment For</td>";
                echo "<td>Payable Amount</td>";
                echo "<td>Order Id</td>";
                echo "<td>Status</td>";
            echo "</tr>";
        if ($result = $this->db->query($sql)->getResultArray()) 
        {
            foreach ($result as $key => $propValue) 
            {
                echo "<tr>";
                    echo "<td>".($key+1)."</td>";
                    echo "<td>".$propValue["id"]."</td>";
                    echo "<td>".$propValue["created_on"]."</td>";
                    echo "<td>".$propValue["application_no"]." <-> ".$propValue["license_no"]."</td>";
                    echo "<td>".$propValue["payment_from"]."</td>";
                    echo "<td>".$propValue["payable_amt"]."</td>";
                    echo "<td>".$propValue["order_id"]."</td>";                
                try 
                {    
                    $api_key_id = getenv("razorpay.api_key_id");
                    $api_secret = getenv("razorpay.api_secret_key");
                    $orderId = $propValue["order_id"];
                    // die($api_key_id);
                    $api = new Api($api_key_id, $api_secret);
                    $razorPayStatus = $api->order->fetch($orderId)->payments();
                    if(isset($razorPayStatus->items[0])) 
                    {
                        if ($razorPayStatus->items[0]->status=="captured") 
                        {
                            $input=[
                                "request_id"=> $propValue["id"],
                                "apply_licence_id"=> $propValue["apply_licence_id"],
                                "merchant_id"=> $propValue["merchant_id"],
                                "amount"=> number_format((float) round(($razorPayStatus->items[0]->amount/100), 2), 2, '.', ''),
                                "ip_address"=> "By Scheduler",
                                "order_id"=> $propValue["order_id"],
                                "razorpay_payment_id"=>$razorPayStatus->items[0]->id,
                                "razorpay_signature"=> $razorPayStatus->items[0]->razorpay_signature??null,
                                "code"=> null,
                                "description"=> null,
                                "source"=> null,
                                "reason"=> null,
                                "order_id"=> $razorPayStatus->items[0]->order_id,
                            ]; 
                            $this->db->transBegin();
                            $this->model_razor_pay_response->pay_responseTrade($input);

                            #--------------------------------------------------------------
                            $data["license"] = $this->ModelTradeLicense->apply_licence_md5(md5($propValue["apply_licence_id"]));
                            # Calculating rate
                            {
                                $denial_amount = 0;
                                $sql_notice = "select * from tbl_denial_notice where apply_id =".$propValue["apply_licence_id"]." and status = 2";

                                $noticeDetails = $this->TradeTransactionModel->row_query($sql_notice,array())[0]??[];

                                if(!empty($noticeDetails))
                                {
                                    $denial_amount = getDenialAmountTrade($noticeDetails['created_on'],date('Y-m-d',strtotime($propValue["created_on"])));
                                
                                }
                                $args['areasqft']=(float)$data["license"]['area_in_sqft'];
                                $args['applytypeid']=$data["license"]["application_type_id"];
                                $args['estdate']=$data["license"]['application_type_id']==1?$data["license"]["establishment_date"]:$data["license"]['valid_from'];
                                $args['tobacco_status']=$data["license"]["tobacco_status"];
                                $args['licensefor']=$data["license"]["licence_for_years"];
                                $args['nature_of_business'] = $data['license']['nature_of_bussiness'];
                                $args['curdate'] = date('Y-m-d',strtotime($propValue["created_on"]));
                                $rate_data=$this->TradeCitizenController->getcharge($args);
                                $rate_data= json_decode(json_encode(json_decode($rate_data)), true);
                            }

                            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id(md5($data["license"]['application_type_id']));

                            $transact_arr=array();
                            $transact_arr['related_id']=$data["license"]['id'];
                            $transact_arr['ward_mstr_id']=$data["license"]["ward_mstr_id"];
                            $transact_arr['transaction_type']=$data["application_type"]["application_type"]??null;
                            $transact_arr['transaction_date']= date('Y-m-d',strtotime($propValue["created_on"]));
                            $transact_arr['payment_mode']='Online';                          
                            $transact_arr['paid_amount']=$rate_data['total_charge']+$denial_amount;
                            $transact_arr['penalty']=$rate_data['penalty']+$denial_amount+$rate_data['arear_amount'];
                            $transact_arr['status']=1; 
                            $transact_arr['emp_details_id']='0';                                    
                            $transact_arr['created_on']=date('Y-m-d H:i:s');
                            $transact_arr['ip_address']=$input['ip_address'];  

                            $transaction_id = $this->TradeTransactionModel->insertPayment($transact_arr); 
                            //echo $transaction_id;
                            
                            $trafinerebate = array();
                            $trafinerebate['transaction_id']=$transaction_id;
                            $trafinerebate['head_name']='Delay Apply License';
                            $trafinerebate['amount']=$rate_data['penalty'];
                            $trafinerebate['value_add_minus']='Add';
                            $trafinerebate['created_on']=date('Y-m-d H:i:s');
                            $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);
                            $denialAmount=$denial_amount+$rate_data['arear_amount'];
                            if($denialAmount > 0)
                            {
                                $denial = array();  // denial insert
                                $denial['transaction_id']=$transaction_id;
                                $denial['head_name']='Denial Apply';
                                $denial['amount']=$denialAmount;
                                $denial['value_add_minus']='Add';
                                $denial['created_on']=date('Y-m-d H:i:s');
                                $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($denial);
                            }
                            $this->TradeApplyLicenceModel->updateApplyLicencePaymentStatusClear($data["license"]["id"]);
                            # Update Provisional No
                            if($data["application_type"]["application_type"]==1)
                            {

                                $get_ulb_id=session()->get('ulb_dtl');
                                $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($get_ulb_id["ulb_mstr_id"]);
                                
                                $warddet=$this->model_ward_mstr->getWardNoBywardId($data["license"]["ward_mstr_id"]);
                                $ward_no=$warddet["ward_no"];
                                $short_ulb_name=$data["ulb_mstr_name"]["short_ulb_name"];
                                $prov_no=$short_ulb_name.$ward_no.date('mdy').$data["license"]['id'];
                                $this->TradeApplyLicenceModel->update_prov_no($data["license"]['id'], $prov_no);   
                            }
                            #---------------------------------------------------------------
                            

                            if($this->db->transStatus() === FALSE) 
                            {
                                $this->db->transRollback();
                            } 
                            else 
                            {
                                $this->db->transCommit();
                            }
                        } 
                        else if ($razorPayStatus->items[0]->status=="failed") 
                        {
                            $input=[
                                "request_id"=> $propValue["id"],
                                "apply_licence_id"=> $propValue["apply_licence_id"],
                                "merchant_id"=> $propValue["merchant_id"],
                                "amount"=> number_format((float) round(($razorPayStatus->items[0]->amount/100), 2), 2, '.', ''),
                                "ip_address"=> "By Scheduler",
                                "order_id"=> $razorPayStatus["order_id"]??null,
                                "razorpay_signature"=> "",
                                "error_code"=> $razorPayStatus->items[0]->error_code,
                                "error_desc"=> $razorPayStatus->items[0]->error_description,
                                "error_source"=> $razorPayStatus->items[0]->error_source,
                                "error_step"=> $razorPayStatus->items[0]->step,
                                "error_reason"=> $razorPayStatus->items[0]->error_reason,
                                "order_id"=> $razorPayStatus->items[0]->order_id,
                                "razorpay_payment_id"=> $razorPayStatus->items[0]->id
                            ];
                            $this->model_razor_pay_response->pay_responseTrade($input);
                        }
                        echo "<td style='color: green'>Transaction ".$razorPayStatus->items[0]->status."</td>";
                    } 
                    else 
                    {
                        $input=[
                            "request_id"=> $propValue["id"],
                            "apply_licence_id"=> $propValue["apply_licence_id"],
                            "merchant_id"=> $propValue["merchant_id"],
                            "amount"=> $propValue["amount"],
                            "ip_address"=> "By Scheduler",
                            "order_id"=> $propValue["order_id"],
                            "razorpay_signature"=> "",
                            "error_code"=> null,
                            "error_desc"=> 'Payment failed',
                            "error_source"=> null,
                            "error_reason"=> 'payment_failed',
                            "order_id"=> $propValue["order_id"],
                            "razorpay_payment_id" => "Order Id Not Found in Payment Gateway.",
                            "status" => 1
                        ];
                        $this->model_razor_pay_response->pay_responseTrade($input);
                        echo "<td style='color: blue'>Order Id Not Found in Payment Gateway.</td>";
                    }
                } 
                catch (\Exception $e) 
                {
                        echo "<td style='color: red'>".$e->getMessage() ." file-".$e->getFile()." Line-".$e->getLine()."</td>";
                }
                echo "</tr>";
            }
        }
        echo "</table>";
    }

    public function ClearPayment()
    { 
        try
        {
            $this->db = db_connect('db_rmc_trade');
            $data=array();
            $inputs= $this->request->getVar();
            $where = " ";
            if(isset($inputs['date']) && !empty($inputs['date']))
            {
                $where .= " AND tbl_razor_pay_request.created_on::DATE='".$inputs['date']."'";
            }
            elseif(!isset($inputs['date']))
            {
                $where .= " AND tbl_razor_pay_request.created_on::DATE='".date('Y-m-d')."'";
            }
            if(isset($inputs['application_no']) && !empty(trim($inputs['application_no'])))
            {
                $where .=" AND tbl_apply_licence.application_no ILIKE '%".trim($inputs['application_no'])."%'";
            }
            if(isset($inputs['order_id']) && !empty(trim($inputs['order_id'])))
            {
                $where .=" AND tbl_razor_pay_request.razorpay_order_id = '".trim($inputs['order_id'])."'";
            }
            $sql =" SELECT
                        tbl_apply_licence.application_no,
                        tbl_apply_licence.license_no,
                        tbl_razor_pay_request.*,
                        tbl_razor_pay_request.razorpay_order_id as order_id,
                        tbl_razor_pay_request.amount as payable_amt
                    FROM tbl_razor_pay_request
                    INNER JOIN tbl_apply_licence ON tbl_apply_licence.id=tbl_razor_pay_request.apply_licence_id
                    LEFT JOIN tbl_razor_pay_response ON tbl_razor_pay_response.razor_pay_request_id = tbl_razor_pay_request.id
                    WHERE
                        tbl_razor_pay_request.status=2
                        AND tbl_razor_pay_request.razorpay_order_id IS NOT NULL
                        AND tbl_razor_pay_response.id IS NULL
                        $where
                    ORDER BY tbl_razor_pay_request.id ASC
            ";
            // print_var($sql);
            $result = $this->db->query($sql)->getResultArray();
            $data=$inputs;
            $data['result']= $result ;

            return view("Trade/ClearPayment",$data);


        }
        catch( Exception $e)
        {
            echo $e->getMessage();
        }
    }
    #------- End trade-----------------------------------
    #---------Water --------------------------------
    public function whaterClearPayment()
    {
        try
        {
            $this->db = db_connect('db_rmc_water');
            $data=array();
            $inputs= $this->request->getVar();
            $where = " ";
            $type="type_c";            
            if(isset($inputs['date']) && !empty($inputs['date']))
            {
                $where .= " AND tbl_razor_pay_request.created_on::DATE='".$inputs['date']."'";
            }
            elseif(!isset($inputs['date']))
            {
                $where .= " AND tbl_razor_pay_request.created_on::DATE='".date('Y-m-d')."'";
            }
            if(isset($inputs['type']) && $inputs['type'] =='type_a')
            {
                $where .=" AND tbl_razor_pay_request.payment_from ='Connection' ";
                $tbl = " tbl_apply_water_connection " ;
                if(isset($inputs['application_no']) && !empty(trim($inputs['application_no'])))
                {
                    $where .="  AND ".$tbl.".application_no ILIKE '%".trim($inputs['application_no'])."%'";
                }
            }
            else
            {
                $where .=" AND tbl_razor_pay_request.payment_from ='Demand Collection' ";
                $tbl = " tbl_consumer ";
                $inputs['type']="type_c";
                if(isset($inputs['application_no']) && !empty(trim($inputs['application_no'])))
                {
                    $where .="  AND ".$tbl.".consumer_no ILIKE '%".trim($inputs['application_no'])."%'";
                }
            }
           
            if(isset($inputs['order_id']) && !empty(trim($inputs['order_id'])))
            {
                $where .=" AND tbl_razor_pay_request.razorpay_order_id = '".trim($inputs['order_id'])."'";
            }
            $sql =" SELECT
                        ".(trim($tbl)=="tbl_apply_water_connection"?($tbl.".application_no"): $tbl.".consumer_no as application_no").",
                        tbl_razor_pay_request.*,
                        tbl_razor_pay_request.razorpay_order_id as order_id,
                        tbl_razor_pay_request.amount as payable_amt
                    FROM tbl_razor_pay_request
                    INNER JOIN ".$tbl." ON ".$tbl.".id=tbl_razor_pay_request.related_id
                    LEFT JOIN tbl_razor_pay_response ON tbl_razor_pay_response.razor_pay_request_id = tbl_razor_pay_request.id
                        AND tbl_razor_pay_response.status = 0 
                    WHERE
                        tbl_razor_pay_request.status=2
                        AND tbl_razor_pay_request.razorpay_order_id IS NOT NULL
                        AND (tbl_razor_pay_response.id IS NULL OR tbl_razor_pay_response.status = 0 )
                        $where
                    ORDER BY tbl_razor_pay_request.id ASC
            ";
            // print_var($sql);
            $result = $this->db->query($sql)->getResultArray();
            $data=$inputs;
            $data['result']= $result ;

            return view("Water/ClearPayment",$data);


        }
        catch( Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function watertestApi($id,$type) 
    {

        $this->db = db_connect('db_rmc_water');
        if($db_system = dbSystem())
        {
            $this->dbSystem = db_connect($db_system); 
        }
        $this->WaterRazorPayModel=new WaterRazorPayModel($this->db);       
        $this->model_ward_mstr=new model_ward_mstr($this->dbSystem);
        $this->model_view_water_consumer = new model_view_water_consumer($this->db);
        $this->payment_model=new WaterPaymentModel($this->db);
        $this->trans_model=new Water_Transaction_Model($this->db);

        $this->conn_fee=new WaterViewConnectionFeeModel($this->db);
        $this->penalty_installment_model=new WaterPenaltyInstallmentModel($this->db);
        $this->WaterPenaltyModel=new WaterPenaltyModel($this->db);
        $this->apply_conn=new WaterApplyNewConnectionCitizen();
        $this->water_conn_dtls=new WaterConnectionDetailsViewModel($this->db);
        $this->site_ins_model=new WaterSiteInspectionModel($this->db);
        $this->WaterMobileModel=new WaterMobileModel($this->db);
        $this->Citizensw_water_model = new Citizensw_water_model($this->db);
        $this->Siginsw_water_model = new Siginsw_water_model($this->db);

        $where =" ";
        if($type=='type_a')
        {
            $where .=" AND tbl_razor_pay_request.payment_from ='Connection' ";
            $tbl = " tbl_apply_water_connection " ;
        }
        else
        {
            $where .=" AND tbl_razor_pay_request.payment_from ='Demand Collection' ";
            $tbl = " tbl_consumer ";
        }
        
        $sql =" SELECT
                    ".(trim($tbl)=="tbl_apply_water_connection"?($tbl.".application_no"): $tbl.".consumer_no as application_no").",
                    tbl_razor_pay_request.*,
                    tbl_razor_pay_request.razorpay_order_id as order_id,
                    tbl_razor_pay_request.amount as payable_amt
                FROM tbl_razor_pay_request
                INNER JOIN ".$tbl." ON ".$tbl.".id=tbl_razor_pay_request.related_id
                LEFT JOIN tbl_razor_pay_response ON tbl_razor_pay_response.razor_pay_request_id = tbl_razor_pay_request.id 
                    AND tbl_razor_pay_response.status = 0 
                WHERE
                    tbl_razor_pay_request.status=2
                    AND tbl_razor_pay_request.id= $id
                    AND tbl_razor_pay_request.razorpay_order_id IS NOT NULL
                    AND (tbl_razor_pay_response.id IS NULL OR tbl_razor_pay_response.status = 0 )
                ORDER BY tbl_razor_pay_request.id ASC
            ";        
        echo "<table style='width: 100%;'>";
            echo "<tr>";
                echo "<td>#</td>";
                echo "<td>ID</td>";
                echo "<td>Date</td>";
                echo "<td>".($type=='type_a'?"Application":"Consumer")." No</td>";
                echo "<td>Payment For</td>";
                echo "<td>Payable Amount</td>";
                echo "<td>Order Id</td>";
                echo "<td>Status</td>";
            echo "</tr>";
        if ($result = $this->db->query($sql)->getResultArray()) 
        {
            foreach ($result as $key => $propValue) 
            {
                echo "<tr>";
                    echo "<td>".($key+1)."</td>";
                    echo "<td>".$propValue["id"]."</td>";
                    echo "<td>".$propValue["created_on"]."</td>";
                    echo "<td>".$propValue["application_no"]."</td>";
                    echo "<td>".$propValue["payment_from"]."</td>";
                    echo "<td>".$propValue["payable_amt"]."</td>";
                    echo "<td>".$propValue["order_id"]."</td>";                
                try 
                {    
                    $api_key_id = getenv("razorpay.api_key_id");
                    $api_secret = getenv("razorpay.api_secret_key");
                    // $api_key_id= 'rzp_live_cZx1o6KuDg9ofu';
                    // $api_secret = 'quJhWVOQSBcDXTZ8vva5PW0n';
                    $orderId = $propValue["order_id"];
                    //die($api_key_id);
                    $api = new Api($api_key_id, $api_secret);
                    $razorPayStatus = $api->order->fetch($orderId)->payments();
                    // print_var( $orderId);
                    // die;
                    $idddd='';
                    if(isset($razorPayStatus->items[0])) 
                    {
                        if ($razorPayStatus->items[0]->status=="captured") 
                        {
                            $input=[                                
                                "pg_mas_id"=> $propValue["id"],
                                "merchant_id"=> $propValue["merchant_id"],
                                "amount"=> number_format((float) round(($razorPayStatus->items[0]->amount/100), 2), 2, '.', ''),
                                "ip_address"=> "By Scheduler",
                                "REMOTE_ADDR" => "By Scheduler",
                                "razorpay_order_id"=> $propValue["razorpay_order_id"],
                                "razorpay_payment_id"=>$razorPayStatus->items[0]->id,
                                "razorpay_signature"=> $razorPayStatus->items[0]->razorpay_signature??null,
                                "code"=> null,
                                "description"=> null,
                                "source"=> null,
                                "reason"=> null,
                                "order_id"=> $razorPayStatus->items[0]->order_id,
                                "status"=> 1,
                            ]; 
                            $this->db->transBegin();
                            $response_id=$this->WaterRazorPayModel->UpdateRazorPayTable($input);
                            // print($response_id);die;

                            #--------------------------------------------------------------
                            if($response_id)
                            { 
                               $idddd =  $this->WaterRazorPayModel->updataSatusReponse($response_id);
                               
                                if($type=='type_c')
                                {

                                    $consumer_id = $propValue["related_id"];
                                    $consumer_details=$this->model_view_water_consumer->waterConsumerDetailsById(md5($consumer_id));
                                    $penalty_details=$this->payment_model->get_penalty_details($consumer_id, "CONSUMER");
                                    $rebate_details=$this->payment_model->get_rebate_details($consumer_id);                
                                    
                                    $amount=$input['amount'];
                                    $penalty=$penalty_details['penalty'];
                                    $rebate=$rebate_details['rebate'];;
    
                                    $period=explode("--", $propValue["demand_id"]);
                                    $due_from=$period[0];       // demand from
                                    $month=$period[1];    // demand upto
                                    $ward_mstr_id=$consumer_details['ward_mstr_id'];
    
                                    $total_amount=$propValue['amount'];
    
                                    $arge['consumer_id']=$consumer_id;
                                    $arge['demand_upto']=$month;
                                    $getAmountPayable = $this->getAmountPayable($arge);            
                                    $getAmountPayable=json_decode($getAmountPayable,true);                         
                                    if($getAmountPayable['status']!=true)
                                    {
                                        //flashToast("message", "Payable Amount Not Calculated Please Visit Nearest Branch. !!!");
                                        throw new Exception("Payable Amount Not Calculated Please Visit Nearest Branch. !!!");                    
                                    }
                                    if((int)$getAmountPayable['data']['balance_amount']!=0)
                                        $data['amount'] =  $getAmountPayable['data']['balance_amount']-$getAmountPayable['data']['rebate'];
                                    else
                                        $data['amount'] =  $getAmountPayable['data']['amount']+$getAmountPayable['data']['penalty']+$getAmountPayable['data']['other_penalty']-$getAmountPayable['data']['rebate'];
                                    
                                    if(round($data['amount'])!=round($propValue['amount']))
                                    {
                                        throw new Exception("Payable Amount Missmatch . !!!");
                                    }
                                    $penalty = $getAmountPayable['data']['penalty']+$getAmountPayable['data']['other_penalty'];
                                    $rebate =$getAmountPayable['data']['rebate'];
                                    $paid_amount = $data['amount'];
                                    $total_amount = $getAmountPayable['data']['amount'];
    
                                    $trans_arr=array();
                                    $trans_arr['ward_mstr_id']=$ward_mstr_id;
                                    $trans_arr['transaction_type']="Demand Collection";
                                    $trans_arr['transaction_date']=date('Y-m-d',strtotime($propValue["created_on"]))??date('Y-m-d');
                                    $trans_arr['related_id']=$consumer_id;
                                    $trans_arr['payment_mode']='Online';
                                    $trans_arr['penalty']=$penalty;
                                    $trans_arr['rebate']=$rebate;
                                    $trans_arr['paid_amount']=$paid_amount;
                                    $trans_arr['total_amount']=$total_amount;
                                    $trans_arr['emp_details_id']=0;
                                    $trans_arr['created_on']=date('Y-m-d H:i:s');
                                    $trans_arr['status']=1;
                                    $trans_arr['from_month']=$due_from;
                                    $trans_arr['upto_month']=$month;
                                    $trans_arr['ip_address']=$input['ip_address'];
                                    
                                    $other=[];
                                    $other["other_penalty"]=$getAmountPayable['data']['other_penalty'];
                                    $other["demand_id"]=$getAmountPayable['data']["demand_id"];
                                    $transaction_id=$this->trans_model->water_pay_now($trans_arr, $other);
                                   
                                }
                                elseif($type=='type_a')
                                {
                                    $apply_connection_id=md5($propValue["related_id"]);
                                    $water_conn_id=md5($propValue["related_id"]);
                                    # Fee Charge Calculating start
                                    $input['conn_fee_charge']=$this->conn_fee->conn_fee_charge($apply_connection_id);
                                    
                                    $input['penalty_installment']=$this->penalty_installment_model->getUnpaidInstallment($water_conn_id);
                                    $input['penalty']=$this->penalty_installment_model->getUnpaidInstallmentSum($water_conn_id);

                                    # cheque bounce penalty
                                    $input['other_penalty']=$this->WaterPenaltyModel->getUnpaidPenaltySum($water_conn_id);
                                    $input['application_status']=$this->apply_conn->application_status($water_conn_id);
                                    $rebate_details=$this->payment_model->get_rebate_details($water_conn_id);
                                    $input['rebate']=$rebate_details['rebate'];
                                    $input['amount']=$input['conn_fee_charge']['conn_fee']+$input['penalty']+$input['other_penalty']-$input['rebate'];
                                    # Fee Charge Calculating end
                                    $water_con=$this->conn_fee->fetch_water_con_details($water_conn_id);
                                    

                                    $total_paid_amount=$input['amount'];
                                    $total_amount=$input['conn_fee_charge']['conn_fee'];

                                    $rebate=$input['rebate'];
                                    //$penalty_installment_upto_id=$input['penalty_installment_upto_id'];
                                    
                                    $connection_type_id=$water_con['connection_type_id'];
                                    $application_no=$water_con['application_no'];
                                    $payment_for = $input['conn_fee_charge']['charge_for'];
                                    $status=1;
                                    $get_water_conn_id=$this->water_conn_dtls->fetch_water_con_details($water_conn_id);
                                    
                                    $penalty=$this->WaterPenaltyModel->getUnpaidPenaltySum($water_conn_id);
                                    $other_penalty=$penalty;
                                    $water_conn_id=$get_water_conn_id['id'];
                                    $ward_id=$get_water_conn_id['ward_id'];
                                    $doc_status=$get_water_conn_id['doc_status'];
                                    $installment_rebate=0;
                                    
                                    # Regularization
                                    if($connection_type_id==2)
                                    {
                                        $pay_full = 1; // In case of online payment full pay installemt fine
                                        $penalty_installment_amount=$this->penalty_installment_model->getPenaltyforRebate(md5($water_conn_id));

                                        if($pay_full==1)
                                        {
                                            $penalty+=$this->penalty_installment_model->getUnpaidInstallmentSum(md5($water_conn_id));
                                            $installment_rebate=(10*$penalty_installment_amount)/100;
                                        }
                                        else
                                        {
                                            $get_installment_details=$this->penalty_installment_model->getInstallmentId(md5($water_conn_id),$penalty_installment_upto_id);
                                            //print_r($get_installment_details);
                                            $penalty_installment_id=$get_installment_details['intallment_id'];
                                            $total_count_left=$get_installment_details['count'];
                                            
                                            $penalty+=$get_installment_details['installment_amount'];
                                            
                                            $count=$this->penalty_installment_model->countExistsUnpaidInstallmentafterId(md5($water_conn_id),$penalty_installment_upto_id);

                                            if($count==0 and $total_count_left>0)
                                            {
                                                $installment_rebate=(10*$penalty_installment_amount)/100;
                                            }
                                            else
                                            {
                                                $installment_rebate=0;
                                            }
                                            
                                        }

                                    }
                                
                                    $total_paid_amount=round($input['conn_fee_charge']['conn_fee']+$penalty-$installment_rebate);
                                    // print_var($input['conn_fee_charge']);
                                    // print_var($penalty);
                                    // print_var($installment_rebate);
                                    // print_var("con".$connection_type_id);die;
                                

                                    $trans_arr=array();
                                    $trans_arr['ward_mstr_id']=$ward_id;
                                    $trans_arr['transaction_type']=$payment_for;
                                    $trans_arr['transaction_date']=date('Y-m-d',strtotime($propValue["created_on"]))??date('Y-m-d');
                                    $trans_arr['related_id']=$water_conn_id;
                                    $trans_arr['payment_mode']='Online';
                                    $trans_arr['penalty']=$penalty;
                                    $trans_arr['rebate']=$rebate+$installment_rebate;
                                    $trans_arr['paid_amount']=$total_paid_amount;
                                    $trans_arr['total_amount']=$total_amount;
                                    $trans_arr['emp_details_id']=0;
                                    $trans_arr['created_on']=date('Y-m-d H:i:s');
                                    $trans_arr['status']=$status;
                                    $trans_arr['payment_from']='Online';
                                    $trans_arr['ip_address']=$input['ip_address'];
                                    
                                    
                                    
                                    $check_trans_exist=$this->payment_model->check_transaction_exist($water_conn_id, $total_paid_amount);
                                   
                                    if($check_trans_exist==0 && round($total_paid_amount)==round($propValue["amount"]))
                                    {
                                        $transaction_id=$this->payment_model->insert_transaction($trans_arr);
                                        if($transaction_id)
                                        {
                                            $trans_no="WTRANO".$transaction_id.date('YmdHis');
                                            $this->payment_model->update_trans_no($trans_no,$transaction_id);
                                            $this->payment_model->update_conn_charge_paid_status($water_conn_id,$transaction_id,$payment_for);

                                        
                                            if($connection_type_id==2)
                                            {
                                                if($pay_full==1)
                                                {
                                                    $this->penalty_installment_model->updateFullInstallment($water_conn_id,$transaction_id);
                                                    $unpaid_installment_loop=$this->penalty_installment_model->getInstallmentDetailsbyApplyConnectionId(md5($water_conn_id),$transaction_id);
                                                    foreach($unpaid_installment_loop as $val1)
                                                    {   
                                                        $trans_rebate=array();
                                                        $trans_rebate['apply_connection_id']=$water_conn_id;
                                                        $trans_rebate['transaction_id']=$transaction_id;
                                                        $trans_rebate['head_name']=$val1['penalty_head'];
                                                        $trans_rebate['amount']=$val1['installment_amount'];
                                                        $trans_rebate['value_add_minus']="+";
                                                        $trans_rebate['created_on']=date('Y-m-d H:i:s');
                                                        $trans_rebate['status']=1;
                                                        
                                                        $this->payment_model->insert_fine_rebate($trans_rebate);

                                                    }
                                                }
                                                else
                                                {
                                                    $penalty_installment_upto_id=$this->penalty_installment_model->updateInstallment($penalty_installment_id,$transaction_id);
                                                    if($penalty_installment_upto_id>0)
                                                    {
                                                        $penalty_installment=$this->penalty_installment_model->getInstallmentDetails(md5($water_conn_id),$penalty_installment_upto_id,$transaction_id);
                                                        foreach($penalty_installment as $val)
                                                        {
                                                            $trans_rebate=array();
                                                            $trans_rebate['apply_connection_id']=$water_conn_id;
                                                            $trans_rebate['transaction_id']=$transaction_id;
                                                            $trans_rebate['head_name']=$val['penalty_head'];
                                                            $trans_rebate['amount']=$val['installment_amount'];
                                                            $trans_rebate['value_add_minus']="+";
                                                            $trans_rebate['created_on']=date('Y-m-d H:i:s');
                                                            $trans_rebate['status']=1;
                                                            $this->payment_model->insert_fine_rebate($trans_rebate);
                                                        }

                                                    }
                                                }
                                            }

                                            if($rebate>0)
                                            {
                                                $trans_rebate=array();
                                                $trans_rebate['apply_connection_id']=$water_conn_id;
                                                $trans_rebate['transaction_id']=$transaction_id;
                                                $trans_rebate['head_name']="Rebate";
                                                $trans_rebate['amount']=$rebate;
                                                $trans_rebate['value_add_minus']="-";
                                                $trans_rebate['created_on']=date('Y-m-d H:i:s');
                                                $trans_rebate['status']=1;

                                                $this->payment_model->insert_fine_rebate($trans_rebate);
                                            }
                                        
                                            if($installment_rebate>0)
                                            {
                                                $trans_rebate=array();
                                                $trans_rebate['apply_connection_id']=$water_conn_id;
                                                $trans_rebate['transaction_id']=$transaction_id;
                                                $trans_rebate['head_name']="Installment Rebate";
                                                $trans_rebate['amount']=$installment_rebate;
                                                $trans_rebate['value_add_minus']="-";
                                                $trans_rebate['created_on']=date('Y-m-d H:i:s');
                                                $trans_rebate['status']=1;
                                                
                                                $this->payment_model->insert_fine_rebate($trans_rebate);
                                            }
                                            
                                            if($other_penalty>0)
                                            {
                                                $trans_rebate=array();
                                                $trans_rebate['apply_connection_id']=$water_conn_id;
                                                $trans_rebate['transaction_id']=$transaction_id;
                                                $trans_rebate['head_name']="Cheque Bounce Charge";
                                                $trans_rebate['amount']=$other_penalty;
                                                $trans_rebate['value_add_minus']="+";
                                                $trans_rebate['created_on']=date('Y-m-d H:i:s');
                                                $trans_rebate['status']=1;
                                                
                                                $this->payment_model->insert_fine_rebate($trans_rebate);
                                                
                                                # update status of cheque bounce charge
                                                $this->WaterPenaltyModel->updateUnpaidPenalty($water_conn_id);
                                            }
                                            
                                            if($payment_for=='New Connection')
                                            {
                                                $this->payment_model->update_payment_status($water_conn_id,$status);
                                            }



                                            if($doc_status==1 and $status==1 and $payment_for=='New Connection')
                                            {
                                                $level_pending=array();
                                                $level_pending['apply_connection_id']=$water_conn_id;
                                                $level_pending['sender_user_type_id']=0;
                                                $level_pending['receiver_user_type_id']=12;
                                                $level_pending['created_on']=date('Y-m-d H:i:s');
                                                $level_pending['emp_details_id']= 0;
                                                $this->payment_model->insert_level_pending($level_pending);
                                            }


                                            if($payment_for=='Site Inspection' and $status==1)
                                            {
                                                $this->site_ins_model->update_site_ins_pay_status($water_conn_id);

                                            }
                                        }

                                        if($payment_for=='Site Inspection' and $status==1)
                                        {
                                            $sql_my="select * 
                                                    from tbl_site_inspection 
                                                    where scheduled_status = 1 
                                                        and apply_connection_id = $water_conn_id 
                                                        and verified_by = 'JuniorEngineer' and payment_status = 0 order by id desc ";
                                            $data['si_verify_dtls']=$this->WaterMobileModel->getDataRowQuery2($sql_my);
                                            
                                            $si_verify_id='';
                                            if(!empty($data['si_verify_dtls']['result']))
                                                $si_verify_id = $data['si_verify_dtls']['result'][0]['id'];
                                            if(!empty($si_verify_id))
                                            {
                                                $sql_my="update tbl_site_inspection set payment_status = 1
                                                            where scheduled_status = 1 
                                                            and apply_connection_id = $water_conn_id 
                                                            and verified_by = 'JuniorEngineer' 
                                                            and payment_status = 0 
                                                            and id = $si_verify_id ";
                                                    
                                                $this->WaterMobileModel->getDataRowQuery2($sql_my);
                                            }

                                        }
                                        
                                        if(in_array($water_con['apply_from'],['sws','swsc']))
                                        {
                                            $sws_whare = ['apply_connection_id'=>$water_conn_id];
                                            $sws = $this->Citizensw_water_model->getData($sws_whare);
                                            if(!empty($sws))
                                            {
                                                $sw = [];
                                                $sw['sw_stage']= 2 ;                                                    
                                                $sw['total_amount']=$trans_arr['paid_amount'] ;
                                                $where_sw = ['apply_connection_id'=>$trans_arr['related_id'],'id'=> $sws['id']];                            
                                                $this->Citizensw_water_model->updateData($sw,$where_sw);

                                                $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']??getUlbDtl()['ulb_mstr_id']; 
                                                $path=base_url('citizenPaymentReceipt/view_transaction_receipt/'.$ulb_mstr_id.'/'.$water_conn_id.'/'.$transaction_id);

                                                if($water_con['apply_from']=='sws')
                                                {
                                                    $push_sw=array();                                
                                                    $push_sw['application_stage']=11;
                                                    $push_sw['status']='Payment Done via Online of '.$total_paid_amount.'-/Rs';
                                                    $push_sw['acknowledgment_no']=$water_con['application_no'];
                                                    $push_sw['service_type_id']=$sws['service_id'];
                                                    $push_sw['caf_unique_no']=$sws['caf_no'];
                                                    $push_sw['department_id']=$sws['department_id'];
                                                    $push_sw['Swsregid']=$sws['cust_id'];
                                                    $push_sw['payable_amount ']=$total_paid_amount;
                                                    $push_sw['payment_validity']='';
                                                    $push_sw['payment_other_details']='';
                                                    $push_sw['certificate_url']=$path;
                                                    $push_sw['approval_date']='';
                                                    $push_sw['expire_date']='';
                                                    $push_sw['licence_no']='';
                                                    $push_sw['certificate_no']='';
                                                    $push_sw['customer_id']=$sws['cust_id'];
                                                    $post_url = getenv('single_indow_push_url');
                                                    $http = getenv('single_indow_push_http');
                                                    $resp = httpPostJson($post_url,$push_sw,$http);
                                                    $respons_data=[];
                                                    $respons_data['apply_connection_id']=$trans_arr['related_id'];
                                                    $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                                                    'data'=>$push_sw]);
                                                    $respons_data['tbl_single_window_id']=$sws['id'];
                                                    $respons_data['emp_id']=null;
                                                    $respons_data['response_status']=json_encode($resp);
                                                    $this->Citizensw_water_model->insertResponse($respons_data);
                                                }
                                                elseif($water_con['apply_from']=='swsc')
                                                {
                                                    $emp_id = $this->emp_id;
                                                    $ip = $this->request->getIPAddress();
                                                    $login = $this->Siginsw_water_model->loginSinglewindowCitizen($emp_id,$ip);
                                                    if(isset($login['status']) && $login['status']=="Success")
                                                    {
                                                        $update_window_singin = [
                                                            "apply_connection_id" =>$trans_arr['related_id'],
                                                            "tbl_single_window_id" => $sws['id'],                                    
                        
                                                        ];
                                                        $where_sigin = [
                                                            "id"=>$login['single_window_singin_id']
                                                        ];
                                                        $this->Siginsw_water_model->updateData($update_window_singin,$where_sigin);
                        
                                                        $push_sw=array();
                                                        $push_sw['application_stage']=2;
                                                        $push_sw['current_status']='Payment Done via Online of '.$total_paid_amount.'-/Rs';;
                        
                                                        $push_sw['caf_no']=$sws['caf_no'];
                                                        $push_sw['sws_reference_no']=$sws['department_id'];
                                                        $push_sw['dept_reference_no']=$application_no;
                                                        $push_sw['service_id']=$sws['service_id'];
                                                        $push_sw['submission_date']=date('Y-m-d');
                                                        $push_sw['approval_no']='';
                                                        $push_sw['approval_date']='';
                                                        $push_sw['certificate_type']='URL';
                                                        $push_sw['certificate_url ']=$path;
                                                        $push_sw['valid_upto'] = '';
                        
                                                        $post_url =getenv('citizen_single_indow_push_url');
                                                        $http = getenv('citizen_single_indow_push_http');
                        
                                                        $resp = httpPostHeaderJson($post_url,$push_sw,$login['token'],$http);  
                                                        
                                                        
                                                        $respons_data=[];
                                                        $respons_data['apply_connection_id']=$trans_arr['related_id'];
                                                        $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                                                        'data'=>$push_sw]);
                                                        $respons_data['tbl_single_window_id']=$sws['id'];
                                                        $respons_data['emp_id']=null;
                                                        $respons_data['response_status']=json_encode($resp);
                                                        $respons_data['token']          = $login['token'];
                                                        $respons_data['tbl_single_window_singin'] = $login['single_window_singin_id'];
                                                        $this->Citizensw_water_model->insertResponse($respons_data);
                                                    }

                                                }
                                            }
                                        }
                                        

                                    }
                                    else
                                    {
                                        if($check_trans_exist!=0)
                                        {
                                           throw new Exception( "Transaction Already Done!!!");
                                        }
                                        if($total_paid_amount!=$propValue["amount"])
                                        {
                                            throw new Exception("Paid Amount Miss-matching with demand amount!!! $total_paid_amount ....".$propValue["amount"]);                                            
                                        }
                                    }  
                                }
                            }
                            #---------------------------------------------------------------
                            

                            if($this->db->transStatus() === FALSE) 
                            {
                                // die("rolback");
                                $this->db->transRollback();
                            } 
                            else 
                            {
                                // die("commit");
                                $this->db->transCommit();
                            }
                        } 
                        else if ($razorPayStatus->items[0]->status=="failed") 
                        {
                            $input=[
                                "razorpay_order_id"=>$propValue["razorpay_order_id"],
                                "pg_mas_id"=> $propValue["id"],
                                "merchant_id"=> $propValue["merchant_id"],
                                "amount"=> number_format((float) round(($razorPayStatus->items[0]->amount/100), 2), 2, '.', ''),
                                "ip_address"=> "By Scheduler",
                                "REMOTE_ADDR" => "By Scheduler",
                                "order_id"=> $razorPayStatus["order_id"]??null,
                                "razorpay_signature"=> $razorPayStatus->items[0]->razorpay_signature??null,
                                "error_code"=> $razorPayStatus->items[0]->error_code,
                                "error_desc"=> $razorPayStatus->items[0]->error_description,
                                "error_source"=> $razorPayStatus->items[0]->error_source,
                                "error_step"=> $razorPayStatus->items[0]->step,
                                "error_reason"=> $razorPayStatus->items[0]->error_reason,
                                "razorpay_order_id"=> $razorPayStatus->items[0]->order_id,
                                "razorpay_payment_id"=> $razorPayStatus->items[0]->id,
                                "status"=> 2
                            ];
                            $response_id= $this->WaterRazorPayModel->UpdateRazorPayTable($input);
                            if($response_id)
                            {
                                $this->WaterRazorPayModel->updataSatusReponse($response_id);
                            }
                        }
                        echo "<td style='color: green'>Transaction ".$razorPayStatus->items[0]->status."</td>";
                    } 
                    else 
                    {
                        $input=[
                            "razorpay_order_id"=> $propValue["razorpay_order_id"],
                            "razorpay_payment_id"=>$propValue["razorpay_order_id"],
                            "pg_mas_id"=> $propValue["id"],
                            "merchant_id"=> $propValue["merchant_id"],
                            "amount"=> $propValue["amount"],
                            "ip_address"=> "By Scheduler",
                            "REMOTE_ADDR" => "By Scheduler",
                            "order_id"=> $propValue["order_id"],
                            "razorpay_signature"=> "",
                            "error_code"=> null,
                            "error_desc"=> 'Payment failed',
                            "order_id"=> $propValue["order_id"],
                            "error_reason" => "Order Id Not Found in Payment Gateway.",
                            "status" => 2
                        ];
                        $response_id=$this->WaterRazorPayModel->UpdateRazorPayTable($input);
                        if($response_id)
                        {
                            $this->WaterRazorPayModel->updataSatusReponse($response_id);
                        }
                        echo "<td style='color: blue'>Order Id Not Found in Payment Gateway.</td>";
                    }
                } 
                catch (\Exception $e) 
                {
                    echo "<td style='color: red'>".$e->getMessage() ." file-".$e->getFile()." Line-".$e->getLine()."</td>";
                }
                echo "</tr>";
            }
        }
        echo "</table>";
    }

    public function getAmountPayable($args=array())
    {
        $this->db = db_connect('db_rmc_water');
        if($db_system = dbSystem())
        {
            $this->dbSystem = db_connect($db_system); 
        }
        $this->demand_model=new WaterConsumerDemandModel($this->db);
        $this->WaterPenaltyModel=new WaterPenaltyModel($this->db);

        $out=["message"=> "something went wrong", "status"=> false];
        $demand_from = null;        
        if(!empty($args) ||$this->request->getMethod()=='post')
        {
            if(!empty($args))
            {
                $inputs = $args;  
            }
            else
            {

                $inputs=arrFilterSanitizeString($this->request->getVar());
            }
            if(isset($inputs['demand_from']))
            {
                $demand_from = $inputs['demand_from'];
            }
            $demand=$this->demand_model->getAmountPayable($inputs["consumer_id"], $inputs["demand_upto"],$demand_from);
            
            # cheque bounce charge
            $demand["other_penalty"]=$this->WaterPenaltyModel->getUnpaidPenaltySum(md5($inputs["consumer_id"]), 'Consumer');
            $demand["rebate"]=0.00; // Not In Use
            $demand["balance_amount"]+=$demand["other_penalty"];
            $out=["message"=> "", "status"=> true, "data"=> $demand];
        }
        return json_encode($out);
    }
    #---------End Water --------------------------------

    public function bulk_sms_send()
    {
        $data = (array)null;
        if($this->request->getMethod()=='post')
        {
            $inputs=arrFilterSanitizeString($this->request->getVar());
            $data["module"] = $inputs["module"];
            if(strtoupper($inputs["module"])=="PROPERTY")
            {
                $this->db = db_connect(dbConfig("property"));
                $this->sms_log = new model_water_sms_log($this->db);
                $this->model_prop_demand = new model_prop_demand($this->db);
                $sql ="select distinct demand.*,owner.mobile_no,p.new_holding_no ,
                        owner.owner_name, view_ward_mstr.ward_no,
                        'Ranchi Municipal Corporation' as ulb_name
                        from tbl_prop_dtl p
                        JOIN tbl_prop_owner_detail owner on owner.prop_dtl_id=p.id
                        JOIN (
                                select prop_dtl_id,min(fyear) as from_fyear,min(qtr) as from_qtr,max(fyear) as upto_fyear,
                                    max(qtr) as upto_qtr,ROUND(sum(balance), 2) as demand_amt 
                                from tbl_prop_demand 
                                where status=1 and paid_status=0 
                                group by prop_dtl_id
                            ) as demand on demand.prop_dtl_id=p.id
                        LEFT JOIN tbl_sms_log sms on sms.ref_id=p.id and sms.ref_type='tbl_prop_dtl' and (sms.response='true' and to_char(stampdate, 'YYYY-MM') = to_char(now(), 'YYYY-MM'))
                        LEFT JOIN view_ward_mstr on view_ward_mstr.id = p.ward_mstr_id 
                        where p.status=1 and owner.status=1 
                            and CHARACTER_LENGTH(owner.mobile_no::text)=10 
                            and CHARACTER_LENGTH(new_holding_no)>0 and demand_amt>0 
                            and sms.ref_id is null 
                        limit 100
                        ";
                        
                $aplication = $this->db->query($sql)->getResultArray();
                
                $sl = 0;
                $table =  "<table class = 'table table-striped table-bordered' style='width: 100%;'>";
                $table .= "<tr>
                            <td>#</td>
                            <td>ID</td>
                            <td>Application No</td>
                            <td>Mobile No</td>
                            <td>due From</td>
                            <td>due Upto</td>
                            <td>Amount</td>
                            <td>Status</td>
                        </tr>";
                $breck = true;
                $smgid ="";
                foreach($aplication as $key=>$val)
                {
                    $data["DuesYear"] = $this->model_prop_demand->geDuesYear($val["prop_dtl_id"]);
                    if ($data["DuesYear"]) 
                    {
                        $input = [
                            'fy' => $data["DuesYear"]["max_year"],
                            'qtr' => $data["DuesYear"]["max_quarter"],
                            'prop_dtl_id' => $val['prop_dtl_id'],
                            'user_id' => 0,
                        ];
                        $data["DuesDetails"] = $this->model_prop_demand->getPropDemandAmountDetails($input);

                    }
					$amount = $data["DuesDetails"]["DemandAmount"]+$data["DuesDetails"]["OnePercentPnalty"];
                    // $sms = Property(["amount"=>$data["DuesDetails"]["PayableAmount"],"qtr"=>($data["DuesYear"]["max_year"]??"")."(".($data["DuesYear"]["max_quarter"]??"").")","holding_no"=>$val["new_holding_no"],"ulb_name"=>$val["ulb_name"]],'Holding Demand');
                    $sms = Property(["owner_name"=>($val["owner_name"]??"Citizen"),"holding_no"=>$val["new_holding_no"],"ward_no"=>$val["ward_no"],"amount"=>$amount,"ulb_name"=>$val["ulb_name"]],'Holding Demand Res');	
                    // print_var($val["demand_amt"]);
                    // print_var($sms);
                    if($sms['status']==true)
                    {
                        {
                            $mobile = '';
                            $mobile=$val['mobile_no'];
                            $message=$sms['sms']; 
                            // print_var($message);die;
                            $templateid=$sms['temp_id'];
                            $sms_log_data = ['emp_id'=>1,
                                            'ref_id'=>$val["prop_dtl_id"],
                                            'ref_type'=>'tbl_prop_dtl',
                                            'mobile_no'=>$mobile,
                                            'purpose'=>"Holding Demand bulk",
                                            'template_id'=>$templateid,
                                            'message'=>$message
                            ];
                            $sms_id =  $this->sms_log->insert_sms_log( $sms_log_data);
                            $res = send_sms($mobile,$message, $templateid);
                            
                            if($res)
                            {
                                $breck = $res['response'];
                                $smgid = $res['msg'];
                                $update_sms_log = ['response'=>$res['response'],'smgid'=>$res['msg']];
                                $up = $this->sms_log->update_sms_log(['id'=>$sms_id],$update_sms_log); 
                                
                            } 
                             $temp = "<tr>
                                            <td>".(++$sl)."</td>
                                            <td>".$val["prop_dtl_id"]."</td>
                                            <td>".$val["new_holding_no"]."</td>
                                            <td>".$val["mobile_no"]."</td>
                                            <td>".($data["DuesYear"]["min_year"]??"")."(".($data["DuesYear"]["min_quarter"]??"").")"."</td>
                                            <td>".($data["DuesYear"]["max_year"]??"")."(".($data["DuesYear"]["max_quarter"]??"").")"."</td>
                                            <td>".$amount."</td>
                                            <td>".$res['response']."</td>
                                        </tr> ";
                            $table.=$temp;
    
                        }
                    }
                    if(trim($smgid)=="Error : 418, Daily SMS limit reached!")
                    {
                        break;
                    }
                }
                $temp="</table>";
                $table.=$temp; 
                $data["table"]=$table;
                $data["total"]=$sl;
            }
            if(strtoupper($inputs["module"])=="WATER")
            {
                $this->db = db_connect('db_rmc_water');
                $this->water_sms_log = new model_water_sms_log($this->db);
                $sql = "select tbl_consumer.id,consumer_no,demand.*,'Ranchi Municipal Corporation' as ulb_name,'1800 8904115' as toll_free_no1 
                        from tbl_consumer
                        join(
                            SELECT DISTINCT(consumer_id) AS consumer_id,(SUM(COALESCE(amount,0)) + sum(COALESCE(penalty,0)) )AS amount,
                                min(demand_from) as demand_from, max(demand_upto) as demand_upto
                            from tbl_consumer_demand 
                            where status =1 and paid_status =0 and amount > 0
                            group by consumer_id
                        )demand on  demand.consumer_id = tbl_consumer.id
                        LEFT JOIN tbl_sms_log sms on sms.ref_id=tbl_consumer.id and sms.ref_type='tbl_consumer' and (sms.response='true' and to_char(stampdate, 'YYYY-MM') = to_char(now(), 'YYYY-MM'))
                        WHERE tbl_consumer.status = 1 
                            and sms.ref_id is null 
                        limit 100
                        ";
                $aplication = $this->db->query($sql)->getResultArray();
                $sl = 0;
                $table =  "<table class = 'table table-striped table-bordered' style='width: 100%;'>";
                $table .= "<tr>
                            <td>#</td>
                            <td>ID</td>
                            <td>Application No</td>
                            <td>Mobile No</td>
                            <td>Ownere Name</td>
                            <td>due From</td>
                            <td>due Upto</td>
                            <td>Status</td>
                        </tr>";
                $breck = true;
                $smgid ="";
                foreach($aplication as $key => $val)
                {
                    $sql = "select applicant_name AS owner_name, mobile_no
                            from tbl_consumer_details 
                            where status = 1 and CHARACTER_LENGTH(mobile_no::text)=10
                            and consumer_id =".$val['id']." limit 1";
                    $owners = $this->db->query($sql)->getResultArray();
                    $sms = Water($val,'Consumer Demand');		 
                    if($sms['status']==true)
                    {
                        foreach ($owners as $val2 )
                        {
                            $mobile = '';
                            $mobile=$val2['mobile_no'];
                            $message=$sms['sms']; 
                            $templateid=$sms['temp_id'];
                            $sms_log_data = ['emp_id'=>1,
                                            'ref_id'=>$val["id"],
                                            'ref_type'=>'tbl_consumer',
                                            'mobile_no'=>$mobile,
                                            'purpose'=>"Consumer Demand bulk",
                                            'template_id'=>$templateid,
                                            'message'=>$message
                            ];
                            $sms_id =  $this->water_sms_log->insert_sms_log( $sms_log_data);
                            $res = send_sms($mobile,$message, $templateid);
                            
                            if($res)
                            {
                                $breck = $res['response'];
                                $smgid = $res['msg'];
                                $update_sms_log = ['response'=>$res['response'],'smgid'=>$res['msg']];
                                $up = $this->water_sms_log->update_sms_log(['id'=>$sms_id],$update_sms_log); 
                                
                            } 

                            $temp = "<tr>
                                            <td>".(++$sl)."</td>
                                            <td>".$val["id"]."</td>
                                            <td>".$val["consumer_no"]."</td>
                                            <td>".$val2["mobile_no"]."</td>
                                            <td>".$val2["owner_name"]."</td>
                                            <td>".$val["demand_from"]."</td>
                                            <td>".$val["demand_upto"]."</td>
                                            <td>".$res['response']."</td>
                                        </tr> ";
                            $table.=$temp;
    
                        }
                    }
                    if(trim($smgid)=="Error : 418, Daily SMS limit reached!")
                    {
                        break;
                    }
                }
                $temp="</table>";
                $table.=$temp; 
                $data["table"]=$table;
                $data["total"]=$sl;
                
            }
            if(strtoupper($inputs["module"])=="TRADE")
            {
                $this->db = db_connect('db_rmc_trade');
                $this->model_trade_sms_log = new model_trade_sms_log($this->db);
                $sql = "SELECT tbl_apply_licence.id,license_no AS licence_no,valid_upto AS exp_date,valid_from AS date_from, 
                            'Ranchi Municipal Corporation' as ulb_name,'1800 8904115' as toll_free_no1 
                        FROM tbl_apply_licence
                        LEFT JOIN tbl_sms_log sms on sms.ref_id=tbl_apply_licence.id and sms.ref_type='tbl_apply_licence' and (sms.response='true' and to_char(stampdate, 'YYYY-MM') = to_char(now(), 'YYYY-MM'))
                        WHERE update_status =0 AND pending_status=5 AND tbl_apply_licence.status =1 
                            AND valid_upto<=now()::date 
                            and sms.ref_id is null 
                        limit 100
                        ";
                $aplication = $this->db->query($sql)->getResultArray();
                $sl = 0;
                $table =  "<table style='width: 100%;'>";
                $table .= "<tr>
                            <td>#</td>
                            <td>ID</td>
                            <td>Application No</td>
                            <td>Mobile No</td>
                            <td>Ownere Name</td>
                            <td>Valid upto</td>
                            <td>Status</td>
                        </tr>";
                $breck = true;
                $smgid ="";
                foreach($aplication as $key => $val)
                {
                    $sql = "select owner_name,mobile 
                            from tbl_firm_owner_name 
                            where status = 1 
                            and apply_licence_id=".$val['id'];
                    $owners = $this->db->query($sql)->getResultArray();
                    $sms = Trade($val,"License expired");                    
                    if($sms['status']==true)
                    {
                        foreach($owners as $val2)
                        {
                            $message= $sms['sms'];
                            $templateid= $sms['temp_id'];
                            $sms_data = [
                                'emp_id'=>1,
                                'ref_id'=>$val["id"],
                                'ref_type'=>'tbl_apply_licence',
                                'mobile_no'=>$val2['mobile'],
                                'purpose'=>strtoupper('License expired'),
                                'template_id'=>$templateid,
                                'message'=>$message                                
                                ];
                            $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                            if($sms_id)
                            {
                                $res=send_sms($val2['mobile'], $message, $templateid);                                            
                                if($res)
                                {
                                    $breck = $res['response'];
                                    $smgid = $res['msg'];
                                    $update=[
                                        'response'=>$res['response'],
                                        'smgid'=>$res['msg'],
                                    ];
                                    $where =['id'=>$sms_id];
                                    $update = $this->model_trade_sms_log->update_sms_log($where,$update);
                                }
                            }
                           
                            $temp = "<tr>
                                            <td>".(++$sl)."</td>
                                            <td>".$val["id"]."</td>
                                            <td>".$val["licence_no"]."</td>
                                            <td>".$val2["mobile"]."</td>
                                            <td>".$val2["owner_name"]."</td>
                                            <td>".$val["exp_date"]."</td>
                                            <td>".$res['response']."</td>
                                        </tr> ";
                            $table.=$temp;
                            
                        }

                    }
                    if(trim($smgid)=="Error : 418, Daily SMS limit reached!")
                    {
                        break;
                    }

                }
                $temp="</table>";
                $table.=$temp; 
                $data["table"]=$table;
                $data["total"]=$sl;
                
            }
            
        }
        return view('report/bulk_sms',$data);

    }

    // public function bulkSmsReminderDrop()
    // {
    //     $data = (array)null;
    //     try{
    //         if($this->request->getMethod()=='post'){
    //             $inputs=arrFilterSanitizeString($this->request->getVar());
    //             $data["module"] = $inputs["module"];
    //             $this->sendPropertySms();
    //         }
    //         return view('report/reminder_sms',$data);
    //     }
    //     catch(Exception $e)
    //     {
    //         flashToast('consumer',$this->validator->getErrors());                
    //         return redirect()->back()->with('error',$this->validator->getErrors());
    //     }
        
    // }

    public function bulkSmsReminderDrop()
    {
        $this->db = db_connect(dbConfig());
        $data = (array)null;
        $data["wardList"] = $this->db->query("select * from tbl_ward_mstr where ulb_mstr_id =1 and status = 1")->getResultArray();
        
        try{
            if($this->request->getMethod()=='post'){
                $inputs=arrFilterSanitizeString($this->request->getVar());
                $data["module"] = $inputs["module"];
                $this->sendPropertySms();
            }
            return view('report/reminder_sms',$data);
        }
        catch(Exception $e)
        {
            flashToast('consumer',$this->validator->getErrors());                
            return redirect()->back()->with('error',$this->validator->getErrors());
        }
        
    }
    
    // public function sendPropertySms()
    // {
    //     $data = [];
    //     $Session=Session();
    //     $emp=$Session->get('emp_details');
    //     $this->db = db_connect(dbConfig("property"));
    //     $this->sms_log = new model_water_sms_log($this->db);
    //     $inputs = arrFilterSanitizeString($this->request->getVar());
    //     $fromDate =$uptoDate=date("Y-m-d");
    //     $limit = "";
    //     if(isset($inputs["from_date"])){
    //         $fromDate = $inputs["from_date"];
    //     }
    //     if(isset($inputs["to_date"])){
    //         $uptoDate = $inputs["to_date"];
    //     }
    //     if(isset($inputs["limit"])){
    //         $limit = $inputs["limit"];
    //     }
        
    //     try{
    //         $sql ="With pending_demand as(
    //                 select distinct tbl_prop_demand.prop_dtl_id
    //                 from tbl_prop_demand 
    //                 join tbl_prop_dtl on tbl_prop_dtl.id = tbl_prop_demand.prop_dtl_id
    //                 LEFT JOIN tbl_sms_log sms on sms.ref_id=tbl_prop_demand.prop_dtl_id and sms.ref_type='tbl_prop_dtl'
    //                     and sms.response='true' and cast(stampdate as date) between '$fromDate' and '$uptoDate'
    //                     and sms.purpose ='Holding Demand Riminder Bulk'
    //                 where tbl_prop_demand.status=1 and paid_status=0 --and tbl_prop_demand.prop_dtl_id = 2
    //                 and sms.id is null and tbl_prop_dtl.new_holding_no !=''
    //                 ".($limit ? " LIMIT $limit ":"")." 
    //             )
    //             select tbl_prop_dtl.id,tbl_prop_dtl.holding_no,new_holding_no,tbl_prop_owner_detail.owner_name,tbl_prop_owner_detail.mobile_no,
    //                 view_ward_mstr.ward_no,
    //                 'Ranchi Municipal Corporation' as ulb_name
    //             from tbl_prop_dtl
    //             join pending_demand on pending_demand.prop_dtl_id = tbl_prop_dtl.id
    //             left join tbl_prop_owner_detail on tbl_prop_owner_detail.prop_dtl_id = tbl_prop_dtl.id 
    //                 and tbl_prop_owner_detail.status =1 
    //             left join view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
    //             where tbl_prop_dtl.status =1
    //             order by tbl_prop_dtl.id ASC    
    //             ";
    //         $application = $this->db->query($sql)->getResultArray();
            
    //         $table =  "<table class = 'table table-striped table-bordered' style='width: 100%;'>";
    //         $table .= "<tr>
    //                     <td>#</td>
    //                     <td>Application No</td>
    //                     <td>Mobile No</td>
    //                     <td>Owner Name</td>
    //                     <td>SMS</td>
    //                     <td>Status</td>
    //                 </tr>";
    //         $tr="";
    //         $sl =0;
    //         $currentProp = 0;
    //         foreach($application as  $key =>$val){
    //             if($currentProp!=$val["id"]){
    //                 $sl++;
    //             }
    //             $_REQUEST["otpVerification"] = true;
    //             $propDueDtl = (new \App\Controllers\CitizenProperty())->index22(md5($val["id"]));
	// 		    $PayableAmount = $propDueDtl["total_amount"]??0;
    //             $sms =Property(["owner_name"=>($val["owner_name"]??"Citizen"),"holding_no"=>($val["new_holding_no"]?$val["new_holding_no"]:$val["holding_no"]),"ward_no"=>$val["ward_no"],"amount"=>$PayableAmount,"ulb_name"=>$val["ulb_name"]],'Holding Demand Res');
    //             $message = $sms['sms']; 
    //             $templateid=$sms['temp_id'];
    //             $mobile = $val["mobile_no"];
    //             $response = send_sms($mobile,$message,$templateid);
    //             $sms_log_data = ['emp_id'=>$emp["id"]??null,
    //                             'ref_id'=>$val["id"],
    //                             'ref_type'=>'tbl_prop_dtl',
    //                             'mobile_no'=>$mobile,
    //                             'purpose'=>"Holding Demand Riminder Bulk",
    //                             'template_id'=>$templateid,
    //                             'message'=>$message,
    //                             "response"=>$response['response'],
    //                             'smgid'=>$response['msg']
    //             ];
                
    //             $sms_id =  $this->sms_log->insert_sms_log( $sms_log_data);
    //             $tr .= "<tr>";
    //             $tr .= "<td>".($key+1)."</td>";
    //             $tr .= "<td>".($val["new_holding_no"]?$val["new_holding_no"]:$val["holding_no"])."</td>";
    //             $tr .= "<td>".$val["mobile_no"]."</td>";
    //             $tr .= "<td>".$val["owner_name"]."</td>";
    //             $tr .= "<td>".$message."</td>";
    //             $tr .= "<td>".($response["response"]??"")."</td>";
    //             $tr .= "</tr>";

    //         }
    //         $table.=$tr."</table>";
    //         $data["table"]=$table;
    //         $data["total"]=$sl;
    //         $data["status"]=true;
    //         // echo json_encode($data);
    //     }catch(Exception $e){            
    //         $data["status"]=false;
    //         $data["message"]=$e->getMessage();
    //     }
    //     echo json_encode($data);
    // }

    //ADDED ON 09/06/2024

    public function sendPropertySms()
    {
        $data = [];
        $Session=Session();
        $emp=$Session->get('emp_details');
        $this->db = db_connect(dbConfig("property"));
        $this->sms_log = new model_water_sms_log($this->db);
        $inputs = arrFilterSanitizeString($this->request->getVar());
        $fromDate =$uptoDate=date("Y-m-d");
        $ward_id = $limit = "";
        if(isset($inputs["from_date"])){
            $fromDate = $inputs["from_date"];
        }
        if(isset($inputs["to_date"])){
            $uptoDate = $inputs["to_date"];
        }
        if(isset($inputs["limit"])){
            $limit = $inputs["limit"];
        }
        if(isset($inputs["ward_id"])){
            $ward_id = $inputs["ward_id"];
        }

        $table = "";
        $tr="";
        $sl =0;
        $currentProp = 0;
        try{
            $sql ="With pending_demand as(
                    select distinct tbl_prop_demand.prop_dtl_id
                    from tbl_prop_demand 
                    join tbl_prop_dtl on tbl_prop_dtl.id = tbl_prop_demand.prop_dtl_id
                    LEFT JOIN tbl_sms_log sms on sms.ref_id=tbl_prop_demand.prop_dtl_id and sms.ref_type='tbl_prop_dtl'
                        and sms.response='true' and cast(stampdate as date) between '$fromDate' and '$uptoDate'
                        and sms.purpose ='Holding Demand Riminder Bulk'
                    where tbl_prop_demand.status=1 and paid_status=0 
                    and sms.id is null and tbl_prop_dtl.new_holding_no !=''
                    ".($ward_id ? " AND tbl_prop_dtl.ward_mstr_id= $ward_id ":"")."
                    ".($limit ? " LIMIT $limit ":"")." 
                )
                select tbl_prop_dtl.id,tbl_prop_dtl.holding_no,new_holding_no,tbl_prop_owner_detail.owner_name,tbl_prop_owner_detail.mobile_no,
                    view_ward_mstr.ward_no,
                    'Ranchi Municipal Corporation' as ulb_name
                from tbl_prop_dtl
                join pending_demand on pending_demand.prop_dtl_id = tbl_prop_dtl.id
                left join tbl_prop_owner_detail on tbl_prop_owner_detail.prop_dtl_id = tbl_prop_dtl.id 
                    and tbl_prop_owner_detail.status =1 
                left join view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                where tbl_prop_dtl.status =1
                order by tbl_prop_dtl.id ASC    
                ";
            $application = $this->db->query($sql)->getResultArray();
            
            $table =  "<table class = 'table table-striped table-bordered' style='width: 100%;'>";
            $table .= "<tr>
                        <td>#</td>
                        <td>Application No</td>
                        <td>Mobile No</td>
                        <td>Owner Name</td>
                        <td>SMS</td>
                        <td>Status</td>
                    </tr>";
            
            foreach($application as  $key =>$val){
                if($currentProp!=$val["id"]){
                    $sl++;
                }
                $_REQUEST["otpVerification"] = true;
                $propDueDtl = (new \App\Controllers\CitizenProperty())->index22(md5($val["id"]));
			    $PayableAmount = $propDueDtl["total_amount"]??0;
                $sms =Property(["owner_name"=>($val["owner_name"]??"Citizen"),"holding_no"=>($val["new_holding_no"]?$val["new_holding_no"]:$val["holding_no"]),"ward_no"=>$val["ward_no"],"amount"=>$PayableAmount,"ulb_name"=>$val["ulb_name"]],'Holding Demand Res');
                $message = $sms['sms']; 
                $templateid=$sms['temp_id'];
                $mobile = $val["mobile_no"];
                $response = send_sms($mobile,$message,$templateid);
                $sms_log_data = ['emp_id'=>$emp["id"]??null,
                                'ref_id'=>$val["id"],
                                'ref_type'=>'tbl_prop_dtl',
                                'mobile_no'=>$mobile,
                                'purpose'=>"Holding Demand Riminder Bulk",
                                'template_id'=>$templateid,
                                'message'=>$message,
                                "response"=>$response['response'],
                                'smgid'=>$response['msg']
                ];
                
                $sms_id =  $this->sms_log->insert_sms_log( $sms_log_data);
                $tr .= "<tr>";
                $tr .= "<td>".($key+1)."</td>";
                $tr .= "<td>".($val["new_holding_no"]?$val["new_holding_no"]:$val["holding_no"])."</td>";
                $tr .= "<td>".$val["mobile_no"]."</td>";
                $tr .= "<td>".$val["owner_name"]."</td>";
                $tr .= "<td>".$message."</td>";
                $tr .= "<td>".($response["response"]??"")."</td>";
                $tr .= "</tr>";

            }
            $table.=$tr."</table>";
            $data["table"]=$table;
            $data["total"]=$sl;
            $data["status"]=true;
        }catch(Exception $e){
            $table.=$tr."</table>";
            $data["table"]=$table;
            $data["total"]=$sl;
            $data["status"]=false;
            $data["message"]=$e->getMessage();
        }        
        echo json_encode($data);
    }
}

?>
