<?php
namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\WaterViewConnectionFeeModel;
use App\Models\WaterConnectionDetailsViewModel;
use App\Models\WaterApplyNewConnectionModel;
use App\Controllers\WaterApplyNewConnectionCitizen;
use App\Models\WaterPaymentModel;
use App\Models\Water_Transaction_Model;
use App\Models\model_ulb_mstr;
use App\Models\WaterSiteInspectionModel;
use App\Models\model_emp_details;
use App\Models\water_applicant_details_model;
use App\Models\WaterConnectionChargeModel;
use App\Models\WaterPenaltyInstallmentModel;
use App\Models\WaterSearchApplicantsMobileModel;
use App\Models\WaterRazorPayModel;
use App\Models\WaterPenaltyModel;

use App\Models\WaterMobileModel;
use App\Models\Citizensw_water_model;
use App\Models\model_view_water_consumer;
use App\Models\model_ward_mstr;
use App\Models\ModelHDFCOnlineRequest;
use App\Models\ModelHDFCOnlineResponse;
use App\Models\Siginsw_water_model;
use App\Models\water_consumer_details_model;
use App\Models\WaterConsumerCollectionModel;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterSearchConsumerMobileModel;
use Exception;
class WaterHDFCOnlinePayment extends HomeController
{
    private $db;
    private $dbSystem;
    private $emp_id;
    private $user_type_id;
    private $conn_fee;
    private $water_conn_dtls;
    private $apply_waterconn_model;
    private $apply_conn;
    private $payment_model;
    private $transaction_model;
    private $site_ins_model;
    private $penalty_installment_model;
    private $search_applicant_mobile_model;
    private $WaterPenaltyModel;
    private $WaterMobileModel;
    private $Citizensw_water_model;
    private $Siginsw_water_model;
    private $ModelHDFCOnlineRequest;
    private $ModelHDFCOnlineResponse;

    private $consumer_demand_model;
    private $WaterUserChargeProceedPaymentCitizeController;
    private $model_view_water_consumer ;
    private $consumer_details_model   ;


    public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper', 'ccavanue_helper',"form_helper","sms_helper"]);

		if($db_name = dbConfig("water"))
        {
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem())
		{
            $this->dbSystem = db_connect($db_system);
        }

        $session=session();
        $get_emp_details=$session->get('emp_details');
        $this->emp_id=$get_emp_details['id'];
        $this->user_type_id=$get_emp_details['user_type_mstr_id'];

        $this->conn_fee=new WaterViewConnectionFeeModel($this->db);
        $this->water_conn_dtls=new WaterConnectionDetailsViewModel($this->db);
        $this->apply_waterconn_model=new WaterApplyNewConnectionModel($this->db);
        $this->apply_conn=new WaterApplyNewConnectionCitizen();
        $this->payment_model=new WaterPaymentModel($this->db);
        $this->transaction_model=new Water_Transaction_Model($this->db);
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->site_ins_model=new WaterSiteInspectionModel($this->db);
        $this->modelemp = new model_emp_details($this->dbSystem);
        $this->applicant_details=new water_applicant_details_model($this->db);
        $this->conn_charge_model=new WaterConnectionChargeModel($this->db);
        $this->penalty_installment_model=new WaterPenaltyInstallmentModel($this->db);
        $this->search_applicant_mobile_model=new WaterSearchApplicantsMobileModel($this->db);
        $this->WaterPenaltyModel=new WaterPenaltyModel($this->db);
        $this->WaterRazorPayModel=new WaterRazorPayModel($this->db);
        $this->WaterMobileModel=new WaterMobileModel($this->db);
        $this->Citizensw_water_model = new Citizensw_water_model($this->db);
        $this->Siginsw_water_model = new Siginsw_water_model($this->db);
        $this->ModelHDFCOnlineRequest = new ModelHDFCOnlineRequest($this->db);
        $this->ModelHDFCOnlineResponse = new ModelHDFCOnlineResponse($this->db);

        $this->WaterUserChargeProceedPaymentCitizeController = new WaterUserChargeProceedPaymentCitizen();
        $this->consumer_demand_model     =   new WaterConsumerDemandModel($this->db);
        $this->model_view_water_consumer = new model_view_water_consumer($this->db);
        $this->consumer_details_model    = new water_consumer_details_model($this->db);
        
    }
    public function __destruct()
    {
        if($this->db)
        {
            $this->db->close();            
        }
        if( $this->dbSystem )
        {
            $this->dbSystem->close();
        }
    }

    public function handleOnlineRequest($MD5water_conn_id)
    {
        try{            
            $currentTime = date("Y-m-d H:i:s");
            $fiveMituts = date("Y-m-d H:i:s",strtotime($currentTime."-60 seconds "));
            
            $sql = "SELECT * FROM tbl_hdfc_request WHERE md5(ref_id::TEXT) = '".$MD5water_conn_id."'
                        AND ref_tbl = 'tbl_apply_water_connection'
                        AND created_on >= '".$fiveMituts."'";
            $test = $this->db->query($sql)->getFirstRow("array");  
            if($test)
            {
                throw new Exception("Please Wait ".date("s",(strtotime($test["created_on"])-strtotime($fiveMituts)))." Seconds After Fist transection");
            }   
            $app_details=$this->apply_waterconn_model->water_conn_details($MD5water_conn_id);
            if(!$app_details)
            {
                throw new Exception("Application Not Found");
            }
            $_SESSION['water_conn_id']=$MD5water_conn_id;
            # Fee Charge Calculating start
            $data['conn_fee_charge']=$this->conn_fee->conn_fee_charge($MD5water_conn_id);
               
            $data['penalty']=$this->penalty_installment_model->getUnpaidInstallmentSum($MD5water_conn_id);
            
            # cheque bounce penalty
            $data['other_penalty']=$this->WaterPenaltyModel->getUnpaidPenaltySum($MD5water_conn_id);
    
            $data['application_status']=$this->apply_conn->application_status($MD5water_conn_id);
            
            $rebate_details=$this->payment_model->get_rebate_details($MD5water_conn_id);
    
            $data['rebate']=$rebate_details['rebate'];
    
            # Regularizaton
            if($app_details['connection_type_id'] == 2){
                $data['rebate'] += (($data['penalty']/100) * 10); // 10 % Off in whole payment of penalty
            }
    
            $data['amount']=$data['conn_fee_charge']['conn_fee']+$data['penalty']+$data['other_penalty']-$data['rebate'];
            
            # Fee Charge Calculating end
            if($data['amount']<=0)
            {
                throw new Exception("Payable amount shold be atleast 11 rupee. !!!");
            }
            
            $get_owner_details=$this->apply_waterconn_model->water_owner_details($MD5water_conn_id);
            
            $data['owner_name']=$get_owner_details[0]['applicant_name'];
            $data['mobile_no']=$get_owner_details[0]['mobile_no'];
            $data['email_id']=$get_owner_details[0]['email_id'];
    
            
            $ccRevenue = getOderId(2);		
            $orderId = $ccRevenue["orderId"];
            $merchent_id = $ccRevenue["merchantId"];
            $working_key = getWorkingKey(2);
            $access_code = getAccessCode(2);
            // $orderId = "Order_".date('dmyhism').rand(0,5);              
            $redirectUrl = base_url('WaterHDFCOnlinePayment/paySuccess/'.$MD5water_conn_id);
            $cancelUrl = base_url('WaterHDFCOnlinePayment/paymentFailed/'.$MD5water_conn_id);
            // $redirectUrl = base_url('WaterHDFCOnlinePayment/showResponse/'.$MD5water_conn_id);
            // $cancelUrl = base_url('WaterHDFCOnlinePayment/showResponse/'.$MD5water_conn_id);
            $billing_mobile_no = $data['mobile_no'];
            $inputs = [
                "order_id"      =>  $orderId,
                "merchant_id"   =>  $merchent_id,
                "ref_id"        =>  $app_details["id"],
                "ref_tbl"       =>  "tbl_apply_water_connection",
                'payment_from'  =>  'Connection',
                "amount"        =>  $data['amount'],
                "redirect_url"   =>  $redirectUrl,
                "failiear_url"   =>  $cancelUrl,
                "ip_address"    => $_SERVER['REMOTE_ADDR'],
            ];
            $id = $this->ModelHDFCOnlineRequest->insertData($inputs);            
            CCAvanuePay($merchent_id, $access_code, $working_key, $orderId, $data['amount'], $redirectUrl, $cancelUrl, $billing_mobile_no);

        }
        catch(Exception $e)
        {            
            flashToast("payment", $e->getMessage()); 
            flashToast("message", $e->getMessage());             
            return redirect()->back()->with('error', $e->getMessage());
        }
        return $this->response->redirect(base_url('WaterApplyNewConnectionCitizen/search'));
        
    }

    public function showResponse($MD5water_conn_id)
    {
        $decript = decrypt($this->request->getVar("encResp"),getWorkingKey()); 
        $decript["redirect_url"] = base_url('WaterHDFCOnlinePayment/paySuccess/'.$MD5water_conn_id);
        $decript["cancel_url"] = base_url('WaterHDFCOnlinePayment/paymentFailed/'.$MD5water_conn_id);
        modelShow($decript);
        die;
    }

    public function paySuccess($MD5water_conn_id)
    {
        try
        {   
            $app_details=$this->apply_waterconn_model->water_conn_details($MD5water_conn_id);            
            $decript = decrypt($this->request->getVar("encResp"),getWorkingKey(2));                        
            $order_status = $decript["order_status"];            
            $pg_request = $this->ModelHDFCOnlineRequest->getRequestDataByOderId($decript["order_id"]);
            
            if($order_status!="Success")
            {
                return $this->paymentFailed($MD5water_conn_id);
            }
            if(!$pg_request || !$app_details || $pg_request['ref_id'] !=$app_details["id"] || $pg_request['ref_tbl'] !="tbl_apply_water_connection")
            {
                throw new Exception("Payment Faild due to Invalide Order No.");
            }

            $app_details=$this->apply_waterconn_model->water_conn_details($MD5water_conn_id);            
            if(!$app_details)
            {
                throw new Exception("Application Not Found");
            }
            $_SESSION['water_conn_id']=$MD5water_conn_id;
            # Fee Charge Calculating start
            $data['conn_fee_charge']=$this->conn_fee->conn_fee_charge($MD5water_conn_id);
            $payment_for    =   $data['conn_fee_charge']['charge_for'];
            $doc_status =   $app_details['doc_status'];
            $application_no   =   $app_details['application_no']; 
            $water_conn_id    =   $app_details['id'];
            $ward_id          =   $app_details['ward_id'];  
            $data['penalty']=$this->penalty_installment_model->getUnpaidInstallmentSum($MD5water_conn_id);
            
            # cheque bounce penalty
            $data['other_penalty']=$this->WaterPenaltyModel->getUnpaidPenaltySum($MD5water_conn_id);
    
            $data['application_status']=$this->apply_conn->application_status($MD5water_conn_id);
            
            $rebate_details=$this->payment_model->get_rebate_details($MD5water_conn_id);
    
            $data['rebate']=$rebate_details['rebate'];
    
            # Regularizaton
            if($app_details['connection_type_id'] == 2){
                $data['rebate'] += (($data['penalty']/100) * 10); // 10 % Off in whole payment of penalty
            }
            $penalty_installment_amount = $this->penalty_installment_model->getPenaltyforRebate(md5($water_conn_id));
            // $count = $this->penalty_installment_model->countExistsUnpaidInstallmentafterId(md5($water_conn_id), $penalty_installment_upto_id);
            $pay_full = 1;
            $installment_rebate=0;
            if($pay_full==1 )
            {                
                $penalty_installment_amount = $this->penalty_installment_model->getUnpaidInstallmentSum(md5($water_conn_id));
                $installment_rebate = (10*$penalty_installment_amount)/100;
            }
            $status  =   1;
            $penalty = $data['penalty'];
            $rebate = $data['rebate'];
            $other_penalty = $data['other_penalty'];
            $total_amount =   $data['conn_fee_charge']['conn_fee'];
            $total_paid_amount = $data['conn_fee_charge']['conn_fee']+$data['penalty']+$data['other_penalty']-$data['rebate'];
            $connection_type_id =   $app_details['connection_type_id'];
            

            $check_trans_exist=$this->payment_model->check_transaction_exist($water_conn_id, $total_paid_amount);
            
            if(round($total_paid_amount)!=round($pg_request["amount"]))
            {
                throw new Exception("Paid Amount Miss-matching with demand amount!!!");
            }
            if($check_trans_exist!=0)
            {
                throw new Exception("Transaction Already Done!!!");
            }

            $param=[                 
                "status" => 1
            ];

            $responseInput = [
                "request_id"    => $pg_request['id'],
                "order_id"      => $decript["order_id"], 
                "merchant_id"   => $pg_request["merchant_id"],
                "ref_id"        => $pg_request["ref_id"],
                "ref_tbl"       => $pg_request["ref_tbl"],
                "amount"        => $decript["amount"],
                "tracking_id"   => $decript["tracking_id"],
                "bank_ref_no"   => $decript["bank_ref_no"],
                "error_code"    => $decript["status_code"],
                "error_desc"    => $decript["error_desc"]??NULL,
                "error_source"  => $decript["error_source"]??NULL,
                "error_step"    => $decript["error_step"]??NULL,
                "error_reason"  => $decript["status_message"],
                "respons_data"  => json_encode($this->request->getVar()),
                "ip_address"    => $_SERVER['REMOTE_ADDR'],
                "status"        => 1
            ];

            $trans_arr=array();
            $trans_arr['ward_mstr_id']=$ward_id;
            $trans_arr['transaction_type']=$payment_for;
            $trans_arr['transaction_date']=date('Y-m-d');
            $trans_arr['related_id']=$water_conn_id;
            $trans_arr['payment_mode']='Online';
            $trans_arr['penalty']=$penalty;
            $trans_arr['rebate']=$rebate;
            $trans_arr['paid_amount']=$total_paid_amount;
            $trans_arr['total_amount']=$total_amount;
            $trans_arr['emp_details_id']=0;
            $trans_arr['created_on']=date('Y-m-d H:i:s');
            $trans_arr['status'] = $status;
            $trans_arr['payment_from']='Online';
            $trans_arr['ip_address']=$_SERVER['REMOTE_ADDR'];

            $this->db->transBegin();
            
            $update = $this->ModelHDFCOnlineRequest->updateData($pg_request["id"],$param);
            $transaction_id=$this->payment_model->insert_transaction($trans_arr);
            $responseInput["tran_id"] = $transaction_id;
            $response_id = $this->ModelHDFCOnlineResponse->insertData($responseInput);
            if(!$transaction_id)
            {
                throw new Exception("Somthig Went Wrong");
            }
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
                // else
                // {
                //     $penalty_installment_upto_id = $this->penalty_installment_model->updateInstallment($penalty_installment_id,$transaction_id);
                //     if($penalty_installment_upto_id>0)
                //     {
                //         $penalty_installment = $this->penalty_installment_model->getInstallmentDetails(md5($water_conn_id),$penalty_installment_upto_id,$transaction_id);
                //         foreach($penalty_installment as $val)
                //         {
                //             $trans_rebate=array();
                //             $trans_rebate['apply_connection_id']=$water_conn_id;
                //             $trans_rebate['transaction_id']=$transaction_id;
                //             $trans_rebate['head_name']=$val['penalty_head'];
                //             $trans_rebate['amount']=$val['installment_amount'];
                //             $trans_rebate['value_add_minus']="+";
                //             $trans_rebate['created_on']=date('Y-m-d H:i:s');
                //             $trans_rebate['status']=1;
                //             $this->payment_model->insert_fine_rebate($trans_rebate);
                //         }

                //     }
                // }
            }
        
            if($rebate>0)
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
                $sql_my="select * 
                        from tbl_site_inspection 
                        where scheduled_status = 1 
                            and apply_connection_id = $water_conn_id 
                            and verified_by = 'JuniorEngineer' 
                            and payment_status = 0 
                        order by id desc ";
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
           
            if(in_array($app_details['apply_from'],['sws','swsc']))
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

                    if($app_details['apply_from']=='sws')
                    {
                        $push_sw=array();                                
                        $push_sw['application_stage']=11;
                        $push_sw['status']='Payment Done via Online of '.$total_paid_amount.'-/Rs';
                        $push_sw['acknowledgment_no']=$app_details['application_no'];
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
                    elseif($app_details['apply_from']=='swsc')
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

                            $post_url ="citizen/api/v1/default/status";//getenv('citizen_single_indow_push_url');
                            $http = "http://59.145.222.58:8080/";//getenv('citizen_single_indow_push_http');

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
            
            if($this->db->transStatus() === FALSE)
            {
                $this->db->transRollback();
                throw new Exception("Something went wrong in payment!!!");
            }
            else
            {                
                $this->db->transCommit();
                $mobile_no=$this->search_applicant_mobile_model->getMobileNo(md5($water_conn_id));
                $session=session();
                $get_ulb_detail=$session->get('ulb_dtl')??getUlbDtl();
                $sms="Your Water Connection Payment of Rs. ".$total_paid_amount." for Application No.  ".$application_no." is successfully done. ".$get_ulb_detail['ulb_name'];
                SMSJHGOVT($mobile_no,$sms);
                
                return $this->response->redirect(base_url('WaterPaymentCitizen/view_transaction_receipt/'.md5($water_conn_id).'/'.md5($transaction_id)));
            }
        
        }
        catch (Exception $e)
        {
            $this->db->transRollback();
            flashToast("payment", $e->getMessage()); 
            flashToast("message", $e->getMessage()); 
            return $this->response->redirect(base_url('WaterPaymentCitizen/payment/'.$MD5water_conn_id));
        }

        
    }

    public function paymentFailed($MD5water_conn_id)
    {
        $decript = decrypt($this->request->getVar("encResp"),getWorkingKey(2));            
        $order_status = $decript["order_status"];
        if($order_status =="Success")
        {
            return $this->paySuccess($MD5water_conn_id);
        }
        $pg_request = $this->ModelHDFCOnlineRequest->getRequestDataByOderId($decript["order_id"]);
        
        $param=[
            "error_reason"  => $decript["status_message"],         
            "status"        => 3
        ];

        $responseInput = [
                    "request_id"    => $pg_request['id'],
                    "order_id"      => $decript["order_id"], 
                    "merchant_id"   => $pg_request["merchant_id"],
                    "ref_id"        => $pg_request["ref_id"],
                    "ref_tbl"       => $pg_request["ref_tbl"],
                    "amount"        => $decript["amount"],
                    "tracking_id"   => $decript["tracking_id"],
                    "bank_ref_no"   => $decript["bank_ref_no"],
                    "error_code"    => $decript["status_code"],
                    "error_desc"    => $decript["error_desc"]??NULL,
                    "error_source"  => $decript["error_source"]??NULL,
                    "error_step"    => $decript["error_step"]??NULL,
                    "error_reason"  => $decript["status_message"],
                    "respons_data"  => json_encode($this->request->getVar()),
                    "ip_address"    => $_SERVER['REMOTE_ADDR'],
                    "status"        => 3
                ];
        $update = $this->ModelHDFCOnlineRequest->updateData($pg_request["id"],$param);
        $response_id = $this->ModelHDFCOnlineResponse->insertData($responseInput);
        flashToast("payment", "Oops, Payment Failed!!!");
        flashToast("message", "Oops, Payment Failed!!!"); 
        return $this->response->redirect(base_url('WaterPaymentCitizen/payment/'.$MD5water_conn_id));
       
    }

    #============ consumer Payment ==================

    public function handleConsumerOnlineRequest($MD5conn_id)
    {
        try{        
            $data=array();
            if($this->request->getMethod()=='post')
            {
                $currentTime = date("Y-m-d H:i:s");
                $fiveMituts = date("Y-m-d H:i:s",strtotime($currentTime."-60 seconds "));
                
                $sql = "SELECT * FROM tbl_hdfc_request WHERE md5(ref_id::TEXT) = '".$MD5conn_id."'
                            AND ref_tbl = 'tbl_consumer'
                            AND created_on >= '".$fiveMituts."'";
                $test = $this->db->query($sql)->getFirstRow("array");  
                if($test)
                {
                    throw new Exception("Please Wait ".date("s",(strtotime($test["created_on"])-strtotime($fiveMituts)))." Seconds After Fist transection");
                } 
                $inputs = arrFilterSanitizeString($this->request->getVar());
                $data['downloadReceipt'] = $inputs['downloadReceipt'] ?? 'false';
                $consumer_id = $inputs['consumer_id'];
                $_SESSION['consumer_id']=$MD5conn_id;
                # Fee Charge Calculating start

                $generation_date = $inputs['month'];
                $due = $this->consumer_demand_model->getDuebyMonth(md5($consumer_id), $generation_date);
                $data['amount'] = $due['amount'];
                $arge['consumer_id'] = $inputs['consumer_id'];
                $arge['demand_upto'] = $inputs['month'];
                $getAmountPayable = $this->WaterUserChargeProceedPaymentCitizeController->getAmountPayable($arge);            
                $getAmountPayable = json_decode($getAmountPayable,true);             
                if($getAmountPayable['status']!=true)
                {
                    throw new Exception("Payable Amount Not Calculated Please Visit Nearest Branch. !!!");
                }
                if(MD5($consumer_id) != $MD5conn_id)
                {
                    throw new Exception("Invalide Reqeust. !!!");
                }
                
                if((int)$getAmountPayable['data']['balance_amount']!=0)
                {
                    $data['amount'] =  $getAmountPayable['data']['balance_amount']-$getAmountPayable['data']['rebate'];
                }
                else
                {
                    $data['amount'] =  $getAmountPayable['data']['amount']+$getAmountPayable['data']['penalty']+$getAmountPayable['data']['other_penalty']-$getAmountPayable['data']['rebate'];
                }

                $get_owner_details=$this->consumer_details_model->consumerDetailsbyMd5(md5($consumer_id));
                
                $data['owner_name']= sizeof($get_owner_details)>0?$get_owner_details[0]['applicant_name']:'';
                $data['mobile_no']=sizeof($get_owner_details)>0?$get_owner_details[0]['mobile_no']:'';
                $data['email_id']=null;
                
                if($data['amount']<=0)
                {
                    throw new Exception("Payable amount shold be atleast 1 rupee. !!!");
                    
                }

                
                $ccRevenue = getOderId(2);				
                $orderId = $ccRevenue["orderId"];
                $merchent_id = $ccRevenue["merchantId"];
                $working_key = getWorkingKey(2);
                $access_code = getAccessCode(2);              
                $redirectUrl = base_url('WaterHDFCOnlinePayment/ConsumerPaySuccess/'.$MD5conn_id);
                $cancelUrl = base_url('WaterHDFCOnlinePayment/ConsumerPaymentFailed/'.$MD5conn_id);
                $billing_mobile_no = "";$data['mobile_no'];
                $inputs = [
                    "order_id"      =>  $orderId,
                    "merchant_id"   =>  $merchent_id,
                    "ref_id"        =>  $consumer_id,
                    "ref_tbl"       =>  "tbl_consumer",
                    'payment_from'  =>  'Demand Collection',
                    "demand_ids"    =>  $inputs["due_from"]."--".$generation_date,
                    "amount"        =>  $data['amount'],
                    "redirect_url"   =>  $redirectUrl,
                    "failiear_url"   =>  $cancelUrl,
                    "ip_address"    => $_SERVER['REMOTE_ADDR'],
                ];                
                $id = $this->ModelHDFCOnlineRequest->insertData($inputs);
                CCAvanuePay($merchent_id, $access_code, $working_key, $orderId, $data['amount'], $redirectUrl, $cancelUrl, $billing_mobile_no);
            
            }
            else
            {
                return $this->response->redirect(base_url('WaterUserChargeProceedPaymentCitizen/pay_payment/'.$MD5conn_id));
            }
        }
        catch(Exception $e)
        {
            flashToast("message", $e->getMessage());            
            return $this->response->redirect(base_url('WaterUserChargeProceedPaymentCitizen/pay_payment/'.$MD5conn_id));
        }
    }

    public function ConsumerPaySuccess($MD5conn_id)
    {
        try
        {            
            if(strtoUpper($this->request->getMethod()) !="POST")
            {
                throw new Exception("Only Post Allow");
            }
            $app_details=$this->apply_waterconn_model->water_conn_details($MD5conn_id);            
            $decript = decrypt($this->request->getVar("encResp"),getWorkingKey(2));            
            $order_status = $decript["order_status"];            
            $pg_request = $this->ModelHDFCOnlineRequest->getRequestDataByOderId($decript["order_id"]);
            // print_var($decript);die;
            if($order_status!="Success")
            {                
                return $this->ConsumerPaymentFailed($MD5conn_id);
            }
            if(!$pg_request || !$app_details || $pg_request['ref_id'] !=$app_details["id"] || $pg_request['ref_tbl'] !="tbl_consumer")
            {                
                throw new Exception("Payment Faild due to Invalide Order No.");
            }
            
            $consumer_id=$pg_request["ref_id"];
            $water_conn_id=md5($pg_request["ref_id"]);

            $param=[                 
                "status" => 1
            ];

            $responseInput = [
                "request_id"    => $pg_request['id'],
                "order_id"      => $decript["order_id"], 
                "merchant_id"   => $pg_request["merchant_id"],
                "ref_id"        => $pg_request["ref_id"],
                "ref_tbl"       => $pg_request["ref_tbl"],
                "amount"        => $decript["amount"],
                "tracking_id"   => $decript["tracking_id"],
                "bank_ref_no"   => $decript["bank_ref_no"],
                "error_code"    => $decript["status_code"],
                "error_desc"    => $decript["error_desc"]??NULL,
                "error_source"  => $decript["error_source"]??NULL,
                "error_step"    => $decript["error_step"]??NULL,
                "error_reason"  => $decript["status_message"],
                "respons_data"  => json_encode($this->request->getVar()),
                "ip_address"    => $_SERVER['REMOTE_ADDR'],
                "status"        => 1
            ];
            
            // $this->db->transBegin();
            
            
            $consumer_details=$this->model_view_water_consumer->waterConsumerDetailsById(md5($consumer_id));
            $penalty_details=$this->payment_model->get_penalty_details($consumer_id, "CONSUMER");
            $rebate_details=$this->payment_model->get_rebate_details($consumer_id);                
            
            $amount=$pg_request['amount'];
            $penalty=$penalty_details['penalty'];
            $rebate=$rebate_details['rebate'];
            $consumer_id=$pg_request['ref_id'];
            
            $period=explode("--", $pg_request["demand_ids"]);
            $due_from=$period[0];       // demand from
            $month=$period[1];    // demand upto
            $ward_mstr_id=$consumer_details['ward_mstr_id'];

            $total_amount=$pg_request['amount'];

            $arge['consumer_id']=$consumer_id;
            $arge['demand_upto']=$month;

            $getAmountPayable = $this->WaterUserChargeProceedPaymentCitizeController->getAmountPayable($arge);            
            $getAmountPayable=json_decode($getAmountPayable,true);                         
            if($getAmountPayable['status']!=true)
            {
                throw new Exception("Payable Amount Not Calculated Please Visit Nearest Branch. !!!");                    
            }
            if((int)$getAmountPayable['data']['balance_amount']!=0)
            {
                $data['amount'] =  $getAmountPayable['data']['balance_amount']-$getAmountPayable['data']['rebate'];
            }
            else
            {
                $data['amount'] =  $getAmountPayable['data']['amount']+$getAmountPayable['data']['penalty']+$getAmountPayable['data']['other_penalty']-$getAmountPayable['data']['rebate'];
            }
            
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
            $transaction_id=$this->transaction_model->water_pay_now($trans_arr, $other);
            $update = $this->ModelHDFCOnlineRequest->updateData($pg_request["id"],$param);
            $responseInput["tran_id"] = $transaction_id;
            $response_id = $this->ModelHDFCOnlineResponse->insertData($responseInput);
            if($transaction_id)
            {              
                return $this->response->redirect(base_url('WaterUserChargePaymentCitizen/payment_tc_receipt/'.md5($consumer_id).'/'.md5($transaction_id).'/'.($downloadReceipt??true)));
            }
        }
        catch (Exception $e)
        {
            flashToast("message", $e->getMessage()); 
            return $this->response->redirect(base_url('WaterUserChargeProceedPaymentCitizen/pay_payment/'.$MD5conn_id));
        }
    }

    public function ConsumerPaymentFailed($MD5conn_id)
    {
        $decript = decrypt($this->request->getVar("encResp"),getWorkingKey(2));            
        $order_status = $decript["order_status"];
        
        if($order_status =="Success")
        {
            return $this->paySuccess($MD5conn_id);
        }
        $pg_request = $this->ModelHDFCOnlineRequest->getRequestDataByOderId($decript["order_id"]);
        
        $param=[
            "error_reason"  => $decript["status_message"],         
            "status"        => 3
        ];

        $responseInput = [
                    "request_id"    => $pg_request['id'],
                    "order_id"      => $decript["order_id"], 
                    "merchant_id"   => $pg_request["merchant_id"],
                    "ref_id"        => $pg_request["ref_id"],
                    "ref_tbl"       => $pg_request["ref_tbl"],
                    "amount"        => $decript["amount"],
                    "tracking_id"   => $decript["tracking_id"],
                    "bank_ref_no"   => $decript["bank_ref_no"],
                    "error_code"    => $decript["status_code"],
                    "error_desc"    => $decript["error_desc"]??NULL,
                    "error_source"  => $decript["error_source"]??NULL,
                    "error_step"    => $decript["error_step"]??NULL,
                    "error_reason"  => $decript["status_message"],
                    "respons_data"  => json_encode($this->request->getVar()),
                    "ip_address"    => $_SERVER['REMOTE_ADDR'],
                    "status"        => 3
                ];
        
        $update = $this->ModelHDFCOnlineRequest->updateData($pg_request["id"],$param);
        $response_id = $this->ModelHDFCOnlineResponse->insertData($responseInput);
        flashToast("payment", "Oops, Payment Failed!!!");
        flashToast("message", "Oops, Payment Failed!!!"); 
        return $this->response->redirect(base_url('WaterUserChargeProceedPaymentCitizen/pay_payment/'.$MD5conn_id));
       
    }

    #============ end Consumer Payment===============
}