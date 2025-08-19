<?php
namespace App\Controllers;
use CodeIgniter\Controller;


use App\Models\model_ward_mstr;
use App\Models\TradeApplyLicenceModel;
use App\Models\model_firm_owner_name;
use App\Models\TradeFirmOwnerModel;
use App\Models\TradeTransactionModel;
use App\Models\model_ulb_mstr;
use App\Models\tradeapplicationtypemstrmodel;
use App\Models\model_trade_transaction_fine_rebet_details;
use App\Models\Citizensw_trade_model;
use App\Models\model_trade_sms_log;
use App\Models\ModelTradeHDFCOnlineRequest;
use App\Models\ModelTradeHDFCOnlineResponse;
use App\Models\ModelTradeLicense;
use Exception;

class TradeHDFCOnlinePayment extends HomeController
{
    private $db;
    private $dbSystem;
    private $ulb_id;
    private $emp_id;    
    private $user_type_id;
    private $ward_model;
    private $TradeApplyLicenceModel;
    private $model_application_doc;
    private $model_firm_owner_name;
    private $model_trade_level_pending_dtl;
    private $TradeFirmOwnerModel;
    private $TradeTradeItemsModel;
    private $TradeTransactionModel;
    private $model_ward_mstr;
    private $model_ulb_mstr;
    private $TradeViewApplyLicenceOwnerModel;
    private $TradeChequeDtlModel;
    private $statemodel;
    private $tradefirmtypemstrmodel;
    private $tradeapplicationtypemstrmodel;
    private $tradeownershiptypemstrmodel;
    private $tradeitemsmstrmodel;
    private $tradelicenceratemodel;
    private $model_saf_dtl;
    private $model_prop_dtl;
    private $modelUlb;
    private $model_trade_licence;
    private $model_trade_licence_owner_name;
    private $model_trade_view_licence_trade_items;
    private $model_trade_licence_validity;
    private $model_trade_document;
    private $model_trade_transaction_fine_rebet_details;
    private $districtmodel;
    private $TradeCategoryTypeModel;
    private $model_apply_licence;
    private $trade_view_application_doc_model;
    private $model_view_trade_licence;
    private $TradeApplyDenialModel;
    private $model_trade_items_mstr;
    private $model_prop_owner_detail;
    private $Citizensw_trade_model;
    private $ModelTradeLicense;
    private $TradeCitizenController;
    private $model_trade_sms_log;

    private $ModelHDFCOnlineRequest;
    private $ModelHDFCOnlineResponse;


    public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper', 'ccavanue_helper',"form_helper","sms_helper"]);

		if ($db_name = dbConfig("trade")) 
        {
            //echo $db_name;
            $this->db = db_connect($db_name);
        }
        if ($db_system = dbSystem()) 
        {
            $this->dbSystem = db_connect($db_system);
        }

        $session=session();
        $get_emp_details = $session->get('emp_details');
        $this->emp_id = $get_emp_details['id'];
        $this->user_type_id = $get_emp_details['user_type_mstr_id'];

        $this->ModelTradeLicense = new ModelTradeLicense($this->db);
        $this->model_firm_owner_name = new model_firm_owner_name($this->db);
        $this->TradeTransactionModel= new TradeTransactionModel($this->db);
        $this->model_trade_transaction_fine_rebet_details = new model_trade_transaction_fine_rebet_details($this->db);
        $this->TradeCitizenController= new TradeCitizen();
        $this->TradeApplyLicenceModel=new TradeApplyLicenceModel($this->db);
        $this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->db);
        $this->TradeFirmOwnerModel = new TradeFirmOwnerModel($this->db);
        $this->model_trade_sms_log=new model_trade_sms_log($this->db);
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->Citizensw_trade_model = new Citizensw_trade_model($this->db);
        
        $this->ModelHDFCOnlineRequest = new ModelTradeHDFCOnlineRequest($this->db);
        $this->ModelHDFCOnlineResponse = new ModelTradeHDFCOnlineResponse($this->db);
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

    public function handleOnlineRequest($MD5apply_licence_id)
    {
        try{
            $currentTime = date("Y-m-d H:i:s");
            $fiveMituts = date("Y-m-d H:i:s",strtotime($currentTime."-60 seconds "));
            
            $sql = "SELECT * FROM tbl_hdfc_request WHERE md5(ref_id::TEXT) = '".$MD5apply_licence_id."'
                        AND ref_tbl = 'tbl_apply_licence'
                        AND created_on >= '".$fiveMituts."'";
            $test = $this->db->query($sql)->getFirstRow("array");  
            if($test)
            {
                throw new Exception("Please Wait ".date("s",(strtotime($test["created_on"])-strtotime($fiveMituts)))." Seconds After Fist transection");
            }
            $data["license"] = $this->ModelTradeLicense->apply_licence_md5($MD5apply_licence_id);
            $data["owner"] = $this->model_firm_owner_name->applicantdetl($MD5apply_licence_id);
            $data["owner_name"] =$data["owner"][0]['owner_name'] ?? NULL;
            $data["mobile_no"] =$data["owner"][0]['mobile'] ?? NULL;
            $data["email_id"] =$data["owner"][0]['emailid'] ?? NULL;
            $_SESSION['apply_licence_id']=$data["license"]["id"]; 
            # Calculating rate
            
            if($data['license']['payment_status']==0)
            {   
                $denial_amount = 0;
                $sql_notice = "select * from tbl_denial_notice where md5(apply_id::text) = '$MD5apply_licence_id' and status = 2";
                $noticeDetails = $this->TradeTransactionModel->row_query($sql_notice,array())[0]??[]; 
                if(!empty($noticeDetails))
                {
                    $denial_amount = getDenialAmountTrade($noticeDetails['created_on'],date('Y-m-d'));
                   
                }
                $args['areasqft']=(float)$data["license"]['area_in_sqft'];
                $args['applytypeid']=$data["license"]["application_type_id"];
                $args['estdate']=$data["license"]['application_type_id']==1? $data["license"]["establishment_date"]: $data["license"]['valid_from'];
                $args['tobacco_status']=$data["license"]["tobacco_status"];
                $args['licensefor']=$data["license"]["licence_for_years"];
                $args['apply_licence_id']=$data["license"]["id"];
                $args['nature_of_business'] = $data['license']['nature_of_bussiness'];

                $rate_data=$this->TradeCitizenController->getcharge($args);
                $rate_data= json_decode(json_encode(json_decode($rate_data)), true);
                // print_var($rate_data);die;
                if(!$rate_data["response"])
                {
                    throw new Exception("Rate Not Calculated");
                }
                if($rate_data['total_charge']<=0)
                {
                    throw new Exception("Payable amount shold be atleast 1 rupee. !!!");
                }
                
                $amount = $rate_data['total_charge']+$denial_amount;
                $data['amount'] = $amount;

                $ccRevenue = getOderId(3);				
                $orderId = $ccRevenue["orderId"];
                $merchent_id = $ccRevenue["merchantId"];
                $working_key = getWorkingKey(3);
                $access_code = getAccessCode(3);             
                $redirectUrl = base_url('TradeHDFCOnlinePayment/paySuccess/'.$MD5apply_licence_id);
                $cancelUrl = base_url('TradeHDFCOnlinePayment/paymentFailed/'.$MD5apply_licence_id);
                $billing_mobile_no = $data['mobile_no'];
                $inputs = [
                    "order_id"      =>  $orderId,
                    "merchant_id"   =>  $merchent_id,
                    "ref_id"        =>  $data["license"]['id'],
                    "ref_tbl"       =>  "tbl_apply_licence",
                    'payment_from'  =>  $data['license']['application_type'],
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
                flashToast("message", "Payment already done!!!");
                return $this->response->redirect(base_url('TradeCitizen/doc_upload/'.($MD5apply_licence_id)));
            }
        }
        catch(Exception $e)
        {
            flashToast("payment", $e->getMessage()); 
            flashToast("message", $e->getMessage());                       
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function paySuccess($MD5apply_licence_id)
    {
        try
        { 
            // $this->db->transBegin();
            $decript = decrypt($this->request->getVar("encResp"),getWorkingKey(3));
            $order_status = $decript["order_status"];
            $pg_request = $this->ModelHDFCOnlineRequest->getRequestDataByOderId($decript["order_id"]);
            $data["license"] = $this->ModelTradeLicense->apply_licence_md5($MD5apply_licence_id);
            if($order_status!="Success")
            {
                return $this->paymentFailed($MD5apply_licence_id);
            }

            if(!$pg_request || !$data["license"] || $pg_request['ref_id'] !=$data["license"]["id"] || $pg_request['ref_tbl'] !="tbl_apply_licence" )
            {
                throw new Exception("Payment Faild due to Invalide Order No.");
            } 
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id(md5($data["license"]['application_type_id']));   
            if(($data["application_type"]["application_type"]??"")!=$data['license']['application_type'])  
            {
                throw new Exception("Invalide Application Type.");
            } 
            $denial_amount = 0;
            $sql_notice = "select * from tbl_denial_notice where md5(apply_id::text) = '$MD5apply_licence_id' and status = 2";
            $noticeDetails = $this->TradeTransactionModel->row_query($sql_notice,array())[0]??[]; 
            if(!empty($noticeDetails))
            {
                $denial_amount = getDenialAmountTrade($noticeDetails['created_on'],date('Y-m-d'));
                
            }
            $args['areasqft']=(float)$data["license"]['area_in_sqft'];
            $args['applytypeid']=$data["license"]["application_type_id"];
            $args['estdate']=$data["license"]['application_type_id']==1? $data["license"]["establishment_date"]: $data["license"]['valid_from'];
            $args['tobacco_status']=$data["license"]["tobacco_status"];
            $args['licensefor']=$data["license"]["licence_for_years"];
            $args['apply_licence_id']=$data["license"]["id"];
            $args['nature_of_business'] = $data['license']['nature_of_bussiness'];

            $rate_data=$this->TradeCitizenController->getcharge($args);
            $rate_data= json_decode(json_encode(json_decode($rate_data)), true);
            // print_var($rate_data);die;
            if(!$rate_data["response"])
            {
                throw new Exception("Rate Not Calculated");
            }
            if(($rate_data['total_charge']+$denial_amount)!=$decript["amount"])
            {
                throw new Exception("Paid Amount Miss-matching with demand amount!!!");
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
            
            $ap_id = $data["license"]['id'];            
           
            $sql_prive_trnas = "select * 
                                from tbl_transaction 
                                where related_id = $ap_id  
                                order by id desc limit 1";
            $prive_transaction=$this->TradeTransactionModel->row_query($sql_prive_trnas)[0]??[];
            
            $transact_arr=array();
            $transact_arr['related_id']=$data["license"]['id'];
            $transact_arr['ward_mstr_id']=$data["license"]["ward_mstr_id"];
            $transact_arr['transaction_type']=$data["application_type"]["application_type"];
            $transact_arr['transaction_date']=date('Y-m-d');
            $transact_arr['payment_mode']='Online';                          
            $transact_arr['paid_amount']=$rate_data['total_charge']+$denial_amount;
            $transact_arr['penalty']=$rate_data['penalty']+$rate_data['arear_amount']+$denial_amount;
            $transact_arr['status']=1; 
            $transact_arr['emp_details_id']='0';                                    
            $transact_arr['created_on']=date('Y-m-d H:i:s');
            $transact_arr['ip_address']=$_SERVER['REMOTE_ADDR'];  

            $transaction_id = $this->TradeTransactionModel->insertPayment($transact_arr); 
            //echo $transaction_id;
            $responseInput["tran_id"] = $transaction_id;

            $request_update = $this->ModelHDFCOnlineRequest->updateData($pg_request["id"],$param);
            $response_id = $this->ModelHDFCOnlineResponse->insertData($responseInput);

            $trafinerebate = array();
            $trafinerebate['transaction_id']=$transaction_id;
            $trafinerebate['head_name']='Delay Apply License';
            $trafinerebate['amount']=$rate_data['penalty'];
            $trafinerebate['value_add_minus']='Add';
            $trafinerebate['created_on']=date('Y-m-d H:i:s');
            $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);
            $denialAmount=$rate_data['arear_amount']+$denial_amount;
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

            /**********sms send testing code *************/
            
            if($data["application_type"]["id"]<>4)
            {
                $owner_for_sms = $this->TradeFirmOwnerModel->getdatabyid_md5(md5($data["license"]["id"]));
                $transaction_no = $this->TradeTransactionModel->transaction_details(md5($transaction_id))['transaction_no'];
                $sms = Trade(array('ammount'=>$transact_arr['paid_amount'],'application_no'=>$data["license"]["application_no"],'ref_no'=>$transaction_no),'Payment done');
                if($sms['status']==true)
                {
                    foreach($owner_for_sms as $val)
                    {
                        $message= $sms['sms'];
                        $templateid= $sms['temp_id'];
                        $sms_data = [                            
                            'ref_id'=>$data["license"]["id"],
                            'ref_type'=>'tbl_apply_licence',
                            'mobile_no'=>$val['mobile'],
                            'purpose'=>strtoupper($data["application_type"]["application_type"]." trade_SI"),
                            'template_id'=>$templateid,
                            'message'=>$message                                
                            ];
                        $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                        if($sms_id)
                        {
                            //$res=SMSJHGOVT("7050180186", $message, $templateid);
                            $res=send_sms($val['mobile'], $message, $templateid);//print_var($res);
                            if($res)
                            {
                                $update=[
                                    'response'=>$res['response'],
                                    'smgid'=>$res['msg'],
                                ];
                                $where =['id'=>$sms_id];
                                $update = $this->model_trade_sms_log->update_sms_log($where,$update);
                            }
                        }

                        
                    }

                }
                // $this->db->transRollback();                    
                // print_var($sms);die; 
            }
            /***********end sms send*********************/ 
            #------------sws push------------------
            $sw = [];
            $sw['application_statge']= 1 ;
            $sw['amount'] = $rate_data['total_charge'];
            $sw['sw_status']=1 ;
            $sw['arrear_amount']=$rate_data['arear_amount'];
            // $sw['denial_amount']=$denial_amount;
            $sw['rejection_fine']=0 ;
            $sw['total_amount']=$transact_arr['paid_amount'] ;
            $where_sw = ['apply_license_id'=>$transact_arr['related_id']];
            $get_ws = $this->Citizensw_trade_model->getData($where_sw);
            //print_var($get_ws);die;
            if($data["license"]['apply_from']=='sws' && !empty($get_ws))
            {
                $where_sw = ['apply_license_id'=>$transact_arr['related_id'],'id'=> $get_ws['id']];
                $this->Citizensw_trade_model->updateData($sw,$where_sw);
                $push_sw=array();
                $path = base_url('citizenPaymentReceipt/view_trade_transaction_receipt/' . $this->ulb_id . '/' . md5($data["license"]['id']) . '/' . md5($transaction_id));
                $push_sw['application_stage']=11;
                $push_sw['status']='Payment Done via Online of '.$transact_arr['paid_amount'].'-/Rs';
                $push_sw['acknowledgment_no']=$data["license"]['application_no'];
                $push_sw['service_type_id']=$get_ws['service_id'];
                $push_sw['caf_unique_no']=$get_ws['caf_no'];
                $push_sw['department_id']=$get_ws['department_id'];
                $push_sw['Swsregid']=$get_ws['cust_id'];
                $push_sw['payable_amount ']=$transact_arr['paid_amount'];
                $push_sw['payment_validity']='';
                $push_sw['payment_other_details']='';
                $push_sw['certificate_url']=$path;
                $push_sw['approval_date']=$data["license"]['valid_from'];
                $push_sw['expire_date']=$data["license"]['valid_upto'];
                $push_sw['licence_no']=$data["license"]['license_no'];
                $push_sw['certificate_no']=$data["license"]['provisional_license_no'];
                $push_sw['customer_id']=$get_ws['cust_id'];
                $post_url = getenv('single_indow_push_url');
                $http = getenv('single_indow_push_http');
                $resp = httpPostJson($post_url,$push_sw,$http);

                $respons_data=[];
                $respons_data['apply_license_id']=$transact_arr['related_id'];
                $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                            'data'=>$push_sw]);
                $respons_data['tbl_single_window_id']=$get_ws['id'];
                $respons_data['emp_id']=null;
                $respons_data['response_status']=json_encode($resp);
                $this->Citizensw_trade_model->insertResponse($respons_data);
                // print_var($push_sw);
                // print_var($resp);die;

            }            
            #--------------------------------------

            flashToast("message", "Payment successfull!!!");
            if(!empty($prive_transaction) && $prive_transaction['status']==3 && $prive_transaction['verify_status']==1)
            {                
                return $this->response->redirect(base_url('TradeCitizen/view_transaction_receipt/'.$MD5apply_licence_id.'/'.md5($transaction_id)));
            }
            return $this->response->redirect(base_url('TradeCitizen/doc_upload/'.$MD5apply_licence_id.'/'.md5($transaction_id)));
        }
        catch(Exception $e)
        {
            flashToast("message", $e->getMessage());
            flashToast("payment", $e->getMessage());             
            return $this->response->redirect(base_url('TradeCitizen/doc_upload/'.$MD5apply_licence_id));
        }
    }

    public function paymentFailed($MD5apply_licence_id)    
    {
        $decript = decrypt($this->request->getVar("encResp"),getWorkingKey(3));            
        $order_status = $decript["order_status"];
        if($order_status =="Success")
        {
            return $this->paySuccess($MD5apply_licence_id);
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
        return $this->response->redirect(base_url('TradeCitizen/doc_upload/'.$MD5apply_licence_id));
       
    }
}