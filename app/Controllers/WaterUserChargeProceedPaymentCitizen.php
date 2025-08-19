<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_water_consumer;
use App\Models\model_ward_mstr;
use App\Models\water_consumer_details_model;
use App\Models\WaterConsumerDemandModel;
use App\Models\Water_Transaction_Model;
use App\Models\WaterSearchConsumerMobileModel;
use App\Models\WaterPaymentModel;
use App\Models\WaterConsumerCollectionModel;
use App\Models\WaterRazorPayModel;
use CodeIgniter\Database\ConnectionInterface;
use Exception;
use App\Models\WaterPenaltyModel;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Razorpay\Api\Api;

class WaterUserChargeProceedPaymentCitizen extends HomeController
{
    protected $water;
    // protected $db;
    protected $dbSystem;
    protected $model_ward_mstr;
    protected $ulb_id;
    protected $emp_id;
    protected $model_view_water_consumer;
    protected $ward_model;
    protected $consumer_details_model;
    protected $consumer_demand_model;
    protected $trans_model;
    protected $search_consumer_mobile_model;
    protected $payment_model;
    protected $collection_model;
    protected $WaterRazorPayModel;
    protected $demand_model;
    protected $WaterPenaltyModel;

    public function __construct()
    {
        parent::__construct();
        helper(['db_helper','form', 'rozarpay_helper']);
        $session=session();
        $ulb_details=$session->get('ulb_dtl')??getUlbDtl();
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];

        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name);            
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
         /*if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }*/
        $this->model_view_water_consumer = new model_view_water_consumer($this->water);
        $this->ward_model = new model_ward_mstr($this->dbSystem);
        $this->consumer_details_model=new water_consumer_details_model($this->water);

        $this->consumer_demand_model=new WaterConsumerDemandModel($this->water);
        $this->trans_model=new Water_Transaction_Model($this->water);
        $this->search_consumer_mobile_model=new WaterSearchConsumerMobileModel($this->water);

        $this->payment_model=new WaterPaymentModel($this->water);
        $this->collection_model=new WaterConsumerCollectionModel($this->water);
        $this->demand_model=new WaterConsumerDemandModel($this->water);
        $this->WaterRazorPayModel=new WaterRazorPayModel($this->water);
        $this->WaterPenaltyModel=new WaterPenaltyModel($this->water);

    }

    public function __destruct()
    {
        if($this->water) $this->water->close();
        if($this->dbSystem) $this->dbSystem->close();
    }
    
    public function pay_payment($consumer_id=null)
    {
        // die("");
        $data=array();               

        $data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);        
        $this->consumer_demand_model->impose_penalty($data['consumer_details']['id']); 
        $data['due_details']=$this->consumer_demand_model->due_demand($consumer_id);
        //print_var($data['due_details']);die;
        $data['consumer_owner_details']=$this->consumer_details_model->consumerDetailsbyMd5($consumer_id);        
        
        $data['due_from']=$this->consumer_demand_model->getDueFrom($consumer_id);
        
        $water_conn_id=$data['consumer_details']["apply_connection_id"];
        $penalty_details=$this->payment_model->get_penalty_details($consumer_id, "CONSUMER");
        
        $data['penalty']=$penalty_details['penalty'];

        $rebate_details=$this->payment_model->get_rebate_details($consumer_id);

        $data['rebate']=$rebate_details['rebate'];
        //print_var($data);
        //die;
        return view('citizen/water/water_user_charge_proceed_payment', $data);

    }
	
    
    public function handleRazorPayRequest()
    {
        $data=array();
        if($this->request->getMethod()=='post')
        {
            $inputs=arrFilterSanitizeString($this->request->getVar());
            $data['downloadReceipt']=$inputs['downloadReceipt'] ?? 'false';
            $consumer_id=$inputs['consumer_id'];
            $_SESSION['consumer_id']=$consumer_id;
            # Fee Charge Calculating start

            $generation_date=$inputs['month'];
            $due=$this->consumer_demand_model->getDuebyMonth(md5($consumer_id), $generation_date);
            $data['amount']=$due['amount'];
            $arge['consumer_id']=$inputs['consumer_id'];
            $arge['demand_upto']=$inputs['month'];
            $getAmountPayable = $this->getAmountPayable($arge);            
            $getAmountPayable=json_decode($getAmountPayable,true);             
            if($getAmountPayable['status']!=true)
            {
                flashToast("message", "Payable Amount Not Calculated Please Visit Nearest Branch. !!!");
                return $this->response->redirect(base_url('WaterUserChargeProceedPaymentCitizen/pay_payment/'.md5($consumer_id)));
            }
            if((int)$getAmountPayable['data']['balance_amount']!=0)
                $data['amount'] =  $getAmountPayable['data']['balance_amount']-$getAmountPayable['data']['rebate'];
            else
                $data['amount'] =  $getAmountPayable['data']['amount']+$getAmountPayable['data']['penalty']+$getAmountPayable['data']['other_penalty']-$getAmountPayable['data']['rebate'];
            $penalty_details=$this->payment_model->get_penalty_details($consumer_id, "CONSUMER");
            //$data['amount']+= $penalty_details['penalty'];
            
            $rebate_details=$this->payment_model->get_rebate_details($consumer_id);
            //$data['amount']-= $rebate_details['rebate'];
            # Fee Charge Calculating end
            
            $get_owner_details=$this->consumer_details_model->consumerDetailsbyMd5(md5($consumer_id));
            
            $data['owner_name']= sizeof($get_owner_details)>0?$get_owner_details[0]['applicant_name']:'';
            $data['mobile_no']=sizeof($get_owner_details)>0?$get_owner_details[0]['mobile_no']:'';
            $data['email_id']=null;
            
            $razor_pay=array();
            $razor_pay['payment_from']='Demand Collection';
            $razor_pay['related_id']=$consumer_id;
            $razor_pay['demand_id']=$inputs["due_from"]."--".$generation_date;
            $razor_pay['amount']=$data['amount'];
            $razor_pay['created_on']=date('Y-m-d H:i:s');
            $razor_pay['ip_address']=$_SERVER['REMOTE_ADDR'];
            //print_var($data['amount']);die;
            if($data['amount']<=0)
            {
                flashToast("message", "Payable amount shold be atleast 1 rupee. !!!");
                return $this->response->redirect(base_url('WaterUserChargeProceedPaymentCitizen/pay_payment/'.md5($consumer_id)));
            }
            $api_key_id = getenv("razorpay.api_key_id");
            $api_secret = getenv("razorpay.api_secret_key");
            $api = new Api($api_key_id, $api_secret);
            $amount=$razor_pay['amount']*100;   # Amount is in currency subunits. Hence, 50000 refers to 50000 paise
            $order_id = $api->order->create(array('receipt' => '123', 'amount' => $amount, 'currency' => 'INR'));
            $data["order_id"] = $order_id["id"];
            $razor_pay["razorpay_order_id"] = $order_id["id"];

            $data["pg_mas_id"]=$this->WaterRazorPayModel->insertData($razor_pay);
            return view('citizen/water/pay_usercharge_online', $data);
        }
    }

    
    public function proceed_payment($pg_mas_id, $razorpay_payment_id, $razorpay_order_id, $razorpay_signature, $downloadReceipt)
    {
        try
        {
            includeRazorLibrary();
            $api_key_id = getenv("razorpay.api_key_id");
            $api_secret = getenv("razorpay.api_secret_key");
            $RazorpayAPi = new \Razorpay\Api\Api($api_key_id, $api_secret);
            $attributes  = array('razorpay_signature'  => $razorpay_signature,  'razorpay_payment_id'  => $razorpay_payment_id ,  'razorpay_order_id' => $razorpay_order_id);
            $order  = $RazorpayAPi->utility->verifyPaymentSignature($attributes);
            
            $pg_request = $this->WaterRazorPayModel->getData(md5($pg_mas_id));
            if(empty($pg_request) || $pg_request['razorpay_order_id']!= $razorpay_order_id)
            {
                throw new Exception('Payment Faild due to Invalide Order No.');
            }
            $consumer_id=$pg_request["related_id"];
            $water_conn_id=md5($pg_request["related_id"]);

            $param=[
                        "pg_mas_id"=> $pg_mas_id, 
                        "razorpay_payment_id"=> $razorpay_payment_id, 
                        "razorpay_order_id"=> $razorpay_order_id,
                        "razorpay_signature"=> $razorpay_signature,
                        "ip_address"=> $_SERVER['REMOTE_ADDR'],
                        'status'=>1,
                    ];
            $response_id=$this->WaterRazorPayModel->UpdateRazorPayTable($param);
            if($response_id)
            {
                $consumer_details=$this->model_view_water_consumer->waterConsumerDetailsById(md5($consumer_id));
                $penalty_details=$this->payment_model->get_penalty_details($consumer_id, "CONSUMER");
                $rebate_details=$this->payment_model->get_rebate_details($consumer_id);                
                
                $amount=$pg_request['amount'];
                $penalty=$penalty_details['penalty'];
                $rebate=$rebate_details['rebate'];
                $consumer_id=$pg_request['related_id'];

                $period=explode("--", $pg_request["demand_id"]);
                $due_from=$period[0];       // demand from
                $month=$period[1];    // demand upto
                $ward_mstr_id=$consumer_details['ward_mstr_id'];

                $total_amount=$pg_request['amount'];

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
                
                if(round($data['amount'])!=round($pg_request['amount']))
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
                $trans_arr['transaction_date']=date('Y-m-d');
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
                
                $other=[];
                $other["other_penalty"]=$getAmountPayable['data']['other_penalty'];
                $other["demand_id"]=$getAmountPayable['data']["demand_id"];
                //$this->water->transBegin();
                $transaction_id=$this->trans_model->water_pay_now($trans_arr, $other);

                // $transaction_id=$this->payment_model->insert_transaction($trans_arr);
                //die($transaction_id);
                
                if($transaction_id)
                {
                    // $trans_no="WTRAN".$transaction_id.date('YmdHis');
                    // $this->payment_model->update_trans_no($trans_no,$transaction_id);

                    // if($penalty>0)
                    // {
                    //     $trans_fine=array();
                    //     $trans_fine['transaction_id']=$transaction_id;
                    //     $trans_fine['head_name']="Penalty";
                    //     $trans_fine['amount']=$penalty;
                    //     $trans_fine['value_add_minus']="+";
                    //     $trans_fine['created_on']=date('Y-m-d H:i:s');
                    //     $trans_fine['status']=1;
                    //     $this->payment_model->insert_fine_rebate($trans_fine);
                    // }
                    // if($rebate>0)
                    // {
                    //     $trans_rebate=array();
                    //     $trans_rebate['transaction_id']=$transaction_id;
                    //     $trans_rebate['head_name']="Penalty";
                    //     $trans_rebate['amount']=$penalty;
                    //     $trans_rebate['value_add_minus']="+";
                    //     $trans_rebate['created_on']=date('Y-m-d H:i:s');
                    //     $trans_rebate['status']=1;
                    //     $this->payment_model->insert_fine_rebate($trans_rebate);
                    // }
                    // $insert_coll=array();
                    // $coll=$this->collection_model->insertCollectionData($transaction_id, $month, $emp_id=0, $consumer_id);
                    // if($coll)
                    // {
                    //     $this->consumer_demand_model->update_demand_statusCollection($consumer_id, $month);
                    // }
                    return $this->response->redirect(base_url('WaterUserChargePaymentCitizen/payment_tc_receipt/'.md5($consumer_id).'/'.md5($transaction_id).'/'.$downloadReceipt));
                }
            }
        }
        catch (Exception $e)
        {
            $consumer_id=$_SESSION['consumer_id'];
            flashToast("message", $e->getMessage());            
            return $this->response->redirect(base_url('WaterUserChargeProceedPaymentCitizen/pay_payment/'.md5($consumer_id)));
        }
    }

    
    public function paymentFailed($pg_mas_id, $razorpay_payment_id, $razorpay_order_id, $error_code, $error_desc, $error_source, $error_step, $error_reason)
    {
        $pg_request = $this->WaterRazorPayModel->getData(md5($pg_mas_id));
        $consumer_id=$pg_request["related_id"];
        $param=[
                    "pg_mas_id"=> $pg_mas_id, 
                    "razorpay_payment_id"=> $razorpay_payment_id, 
                    "razorpay_order_id"=> $razorpay_order_id,
                    "error_code"=> $error_code,
                    "error_desc"=> $error_desc,
                    "error_source"=> $error_source,
                    "error_step"=> $error_step,
                    "error_reason"=> $error_reason,
                    'status'=>0,
                    "ip_address"=> $_SERVER['REMOTE_ADDR'],
            ];
        $response_id=$this->WaterRazorPayModel->UpdateRazorPayTable($param);
        if($response_id)
        {
            flashToast("message", "Oops, Payment Failed!!!");
            return $this->response->redirect(base_url('WaterUserChargeProceedPaymentCitizen/pay_payment/'.md5($consumer_id)));
        }
    }

    

    public function pay_user_charge()
    {
        $data=array();
        $session=session();
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];
        $user_type_mstr_id=$get_emp_details['user_type_mstr_id'];
        

        if($this->request->getMethod()=='post')
        {

            $inputs=arrFilterSanitizeString($this->request->getVar());
            print_var($inputs);exit;

            $curr_date=date('Y-m-d');
            $payment_mode=$inputs['payment_mode'];
            $amount=$inputs['amount'];
            $penalty=$inputs['penalty'];
            $rebate=$inputs['rebate'];
            $consumer_id=$inputs['consumer_id'];
            $month=$inputs['month'];
            $due_from=$inputs['due_from'];
            $ward_mstr_id=$inputs['ward_mstr_id'];

            $total_amount=$amount+$penalty-$rebate;

            $rules=[
                    'payment_mode'=>'required|alpha',
                    'amount' =>'required|numeric',
                    'month' =>'required',
                ];

             
              

            if(!$this->validate($rules))
            {
                $data['validation']=$this->validator;              
                return view('mobile/water/payment_details',$data);
            }
            else
            {
                $status=1;
                if($payment_mode!='CASH')
                {
                    $status=2;
                    $cheque_no=$inputs['cheque_no'];
                    $cheque_dt=$inputs['cheque_date'];
                    $bank_name=$inputs['bank_name'];
                    $branch_name=$inputs['branch_name'];
                }

                $trans_arr=array();
                $trans_arr['ward_mstr_id']=$ward_mstr_id;
                $trans_arr['transaction_type']="Demand Collection";
                $trans_arr['transaction_date']=date('Y-m-d');
                $trans_arr['related_id']=$consumer_id;
                $trans_arr['payment_mode']=$payment_mode;
                $trans_arr['penalty']=$penalty;
                $trans_arr['rebate']=$rebate;
                $trans_arr['paid_amount']=$total_amount;
                $trans_arr['total_amount']=$total_paid_amount;
                $trans_arr['emp_details_id']=$emp_id;
                $trans_arr['created_on']=date('Y-m-d H:i:s');
                $trans_arr['status']=$status;
                $trans_arr['from_month']=$due_from;
                $trans_arr['upto_month']=$month;
                $trans_arr['total_amount']=$amount;
                
                //print_r($trans_arr);
                $transaction_id=$this->payment_model->insert_transaction($trans_arr);
                

                if($transaction_id)
                {
                    $trans_no="WTRAN".$insert_id.date('YmdHis');
                    $this->payment_model->update_trans_no($trans_no,$transaction_id);
                
                    if($payment_mode!='CASH')
                    {


                        $chq_arr=array();
                        $chq_arr['transaction_id']=$transaction_id;
                        $chq_arr['cheque_no']=$cheque_no;
                        $chq_arr['cheque_date']=$cheque_dt;
                        $chq_arr['bank_name']=$bank_name;
                        $chq_arr['branch_name']=$branch_name;
                        $chq_arr['emp_details_id']=$emp_id;
                        $chq_arr['created_on']=date('Y-m-d H:i:s');
                        $chq_arr['status']=2;

                        $this->payment_model->insert_cheque_details($chq_arr);

                    }

                    if($penalty>0)
                    {
                        $trans_fine=array();
                        $trans_fine['transaction_id']=$transaction_id;
                        $trans_fine['head_name']="Penalty";
                        $trans_fine['amount']=$penalty;
                        $trans_fine['value_add_minus']="+";
                        $trans_fine['created_on']=date('Y-m-d H:i:s');
                        $trans_fine['status']=1;
                        $this->payment_model->insert_fine_rebate($trans_fine);
                    }
                    if($rebate>0)
                    {
                        $trans_rebate=array();
                        $trans_rebate['transaction_id']=$transaction_id;
                        $trans_rebate['head_name']="Penalty";
                        $trans_rebate['amount']=$penalty;
                        $trans_rebate['value_add_minus']="+";
                        $trans_rebate['created_on']=date('Y-m-d H:i:s');
                        $trans_rebate['status']=1;
                        $this->payment_model->insert_fine_rebate($trans_rebate);
                    }
                    $insert_coll=array();
                    $coll=$this->collection_model->insertData($transaction_id,$month,$emp_id);
                    if($coll)
                    {
                        $this->demand_model->update_demand_status($consumer_id, $month);
                    }
                }
            }
        }
        return $this->response->redirect(base_url('WaterUserChargePaymentCitizen/payment_tc_receipt/'.md5($consumer_id).'/'.md5($transaction_id)));
    }

    public function get_amount()
    {
        if($this->request->getMethod()=='post')
        {
            $generation_date=$this->request->getVar("generation_date");
            $consumer_id=$this->request->getVar("consumer_id");

            $due=$this->consumer_demand_model->getDuebyMonth(md5($consumer_id), $generation_date);
            //print_r($due);
            if($due)
            {
                $response=["response"=>true,"amount"=>$due['amount']];
            }
            else
            {
                $response=["response"=>false];
            }

            return json_encode($response);
        }
    }
    
    // This controller for holding

    function fetchWaterData()
    {
        $return=["status"=> false];
        if($this->request->getMethod()=='post')
        {
            $inputs=arrFilterSanitizeString($this->request->getVar());
            $prop_dtl_id=$inputs['prop_dtl_id'];

            $consumerDtl=$this->consumer_details_model->getConsumerDataByPropId($prop_dtl_id);
            if(!empty($consumerDtl))
            {
                for($i=0; $i<sizeof($consumerDtl); $i++)
                {
                    $due=$this->consumer_demand_model->getTotalDues(md5($consumerDtl[$i]['id']));
                    $data['amount']=$due['amount'];
                    
                    $penalty_details=$this->payment_model->get_penalty_details($consumerDtl[$i]['id'], "CONSUMER");
                    $data['amount']= $penalty_details['penalty'];
                    
                    $rebate_details=$this->payment_model->get_rebate_details($consumerDtl[$i]['id']);
                    $data['amount']= $rebate_details['rebate'];

                    $toalPayableAmount=($due['amount']+$penalty_details['penalty']) - $rebate_details['rebate'];
                    $demandPeriod=$this->consumer_demand_model->getMaxFrom(md5($consumerDtl[$i]['id']));

                    $consumerDtl[$i]["consumer_id_MD5"]=md5($consumerDtl[$i]['id']);
                    $consumerDtl[$i]["demand_from"]=$demandPeriod['demand_from'];
                    $consumerDtl[$i]["demand_upto"]=$demandPeriod['demand_upto'];
                    $consumerDtl[$i]["toalPayableAmount"]=$toalPayableAmount;
                }
                $return=["status"=> true, "data"=> $consumerDtl];
            }
        }
        echo json_encode($return);
    }

    public function getAmountPayable($args=array())
    {
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
            //print_var($inputs);die;
            //print_var($this->WaterConsumerDemandModel->get_demand_with_penalty($inputs["consumer_id"], $inputs["demand_upto"]));
            $demand=$this->demand_model->getAmountPayable($inputs["consumer_id"], $inputs["demand_upto"],$demand_from);
            
            # cheque bounce charge
            $demand["other_penalty"]=$this->WaterPenaltyModel->getUnpaidPenaltySum(md5($inputs["consumer_id"]), 'Consumer');
            $demand["rebate"]=0.00; // Not In Use
            $demand["balance_amount"]+=$demand["other_penalty"];
            $out=["message"=> "", "status"=> true, "data"=> $demand];
        }
        // if($this->request->getMethod()=='post')
        // {
        //     echo json_encode($out);
        // }
        // else
        return json_encode($out);
    }
}
?>
