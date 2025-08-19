<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\ModelTradeLicense;
use App\Models\TradeRazorPayModel;
use App\Models\model_firm_owner_name;
use App\Models\TradeTransactionModel;
use App\Models\model_trade_transaction_fine_rebet_details;
use App\Models\TradeApplyLicenceModel;
use App\Controllers\TradeCitizen;
use App\Models\TradeApplicationTypeMstrModel;
use App\Models\TradeFirmOwnerModel;
use App\Models\model_trade_sms_log;
use Razorpay\Api\Api;

use App\Models\model_ulb_mstr;
use App\Models\model_ward_mstr;
use App\Models\Citizensw_trade_model;

use Exception;

class TradePaymentCitizen extends HomeController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
    protected $model_ulb_mstr;
    protected $user_type;
    protected $ModelTradeLicense;
    protected $model_firm_owner_name;
    protected $TradeTransactionModel;
    protected $TradeRazorPayModel;
    protected $model_trade_transaction_fine_rebet_details;
    protected $TradeApplyLicenceModel;
    protected $TradeCitizenController;

    protected $tradeapplicationtypemstrmodel;
    protected $TradeFirmOwnerModel;
    protected $Citizensw_trade_model;
    
    
    public function __construct()
    {
        $session=session();
        parent::__construct();

        helper(['db_helper', 'form', 'qr_code_generator_helper','form_helper','sms_helper', 'rozarpay_helper']);
        if($db_name = dbConfig("trade"))
        {
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem())
        {
            $this->dbSystem = db_connect($db_system); 
        }
        
        $this->ModelTradeLicense= new ModelTradeLicense($this->db);
        $this->TradeRazorPayModel= new TradeRazorPayModel($this->db);
        $this->model_firm_owner_name = new model_firm_owner_name($this->db);
        $this->TradeTransactionModel= new TradeTransactionModel($this->db);
        $this->model_trade_transaction_fine_rebet_details = new model_trade_transaction_fine_rebet_details($this->db);
        $this->TradeCitizenController= new TradeCitizen($this->db);
        $this->TradeApplyLicenceModel=new TradeApplyLicenceModel($this->db);

        $this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->db);
        $this->TradeFirmOwnerModel = new TradeFirmOwnerModel($this->db);
        $this->model_trade_sms_log=new model_trade_sms_log($this->db);
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr=new model_ward_mstr($this->dbSystem);
        $this->Citizensw_trade_model = new Citizensw_trade_model($this->db); 
    }

    public function handleRazorPayRequest($apply_licence_id)
    {
        return $this->response->redirect(base_url('TradeHDFCOnlinePayment/handleOnlineRequest/'.$apply_licence_id));
        
        $data["license"] = $this->ModelTradeLicense->apply_licence_md5($apply_licence_id);
        $data["owner"] = $this->model_firm_owner_name->applicantdetl($apply_licence_id);
        $data["owner_name"] =$data["owner"][0]['owner_name'] ?? NULL;
        $data["mobile_no"] =$data["owner"][0]['mobile'] ?? NULL;
        $data["email_id"] =$data["owner"][0]['emailid'] ?? NULL;
        $_SESSION['apply_licence_id']=$data["license"]["id"]; 
        # Calculating rate
        if($data['license']['payment_status']==0)
        {   
            $denial_amount = 0;
            $sql_notice = "select * from tbl_denial_notice where md5(apply_id::text) = '$apply_licence_id' and status = 2";
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
            $data['amount']=$rate_data['total_charge']+$denial_amount;
            
            $razor_pay=array();
            $razor_pay['payment_from']=$data['license']['application_type'];
            $razor_pay['apply_licence_id']=$data["license"]['id'];
            $razor_pay['amount']=$rate_data['total_charge'];
            $razor_pay['created_on']=date('Y-m-d H:i:s');
            $razor_pay['ip_address']=$_SERVER['REMOTE_ADDR'];

            $api_key_id = getenv("razorpay.api_key_id");
            $api_secret = getenv("razorpay.api_secret_key");
            $api = new Api($api_key_id, $api_secret);
            $amount=$razor_pay['amount']*100;// Amount is in currency subunits. Hence, 50000 refers to 50000 paise
            $order_id = $api->order->create(array('receipt' => '123', 'amount' => $amount, 'currency' => 'INR'));
            $data["order_id"] = $order_id["id"];
            $razor_pay["razorpay_order_id"] = $order_id["id"];
            $data["pg_mas_id"]=md5($this->TradeRazorPayModel->insertData($razor_pay));
            $data['ammount']=$amount;
            return view('citizen/trade/pay_demand_online', $data);
        }
        else
        {
            flashToast("message", "Payment already done!!!");
            return $this->response->redirect(base_url('TradeCitizen/doc_upload/'.($apply_licence_id)));
        }
    }

    # call from payment page after successsfull online payment
    public function proceed_payment($pg_mas_id, $razorpay_payment_id, $razorpay_order_id, $razorpay_signature)
    {
        // print_var(session()->get('ulb_dtl'));die();
        try
        { 
            includeRazorLibrary();
            $api_key_id =getenv("razorpay.api_key_id");
            $api_secret = getenv("razorpay.api_secret_key");
            $RazorpayAPi = new Api($api_key_id, $api_secret);
            $attributes  = array('razorpay_signature'  => $razorpay_signature,  'razorpay_payment_id'  => $razorpay_payment_id ,  'razorpay_order_id' => $razorpay_order_id);
            $order  = $RazorpayAPi->utility->verifyPaymentSignature($attributes);

            $pg_request = $this->TradeRazorPayModel->getData($pg_mas_id);
            // print_var($razorpay_order_id);           
            if($razorpay_order_id!=$pg_request['razorpay_order_id'])
            {
                throw new Exception("Invalid Order No", 1);                
            } 
            $data["license"] = $this->ModelTradeLicense->apply_licence_md5(md5($pg_request["apply_licence_id"]));
            // echo $data['license']['nature_of_bussiness'];
            // print_var($data['license']);
            // die();

            $param=[
                // "pg_mas_id"=> $pg_mas_id, 
                "pg_mas_id"=>  $pg_request['id'], 
                "razorpay_payment_id"=> $razorpay_payment_id, 
                "razorpay_order_id"=> $razorpay_order_id,
                "razorpay_signature"=> $razorpay_signature,
                "ip_address"=> $_SERVER['REMOTE_ADDR'],
                "status"=>1,
            ];
            $response_id=$this->TradeRazorPayModel->UpdateRazorPayTable($param);
            $ap_id = $data["license"]['id'];
            # Calculating rate
            {
                $denial_amount = 0;
                $sql_notice = "select * from tbl_denial_notice where md5(apply_id::text) = md5($ap_id::text) and status = 2";
                $noticeDetails = $this->TradeTransactionModel->row_query($sql_notice,array())[0]??[]; 
                if(!empty($noticeDetails))
                {
                    $denial_amount = getDenialAmountTrade($noticeDetails['created_on'],date('Y-m-d'));
                
                }
                $args['areasqft']=(float)$data["license"]['area_in_sqft'];
                $args['applytypeid']=$data["license"]["application_type_id"];
                $args['estdate']=$data["license"]['application_type_id']==1?$data["license"]["establishment_date"]:$data["license"]['valid_from'];
                $args['tobacco_status']=$data["license"]["tobacco_status"];
                $args['licensefor']=$data["license"]["licence_for_years"];
                $args['nature_of_business'] = $data['license']['nature_of_bussiness'];
                $rate_data=$this->TradeCitizenController->getcharge($args);
                $rate_data= json_decode(json_encode(json_decode($rate_data)), true);
            }
           
            $sql_prive_trnas = "select * from tbl_transaction where related_id = $ap_id  order by id desc limit 1";
            $prive_transaction=$this->TradeTransactionModel->row_query($sql_prive_trnas)[0]??[];
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id(md5($data["license"]['application_type_id']));
            $transact_arr=array();
            $transact_arr['related_id']=$data["license"]['id'];
            $transact_arr['ward_mstr_id']=$data["license"]["ward_mstr_id"];
            $transact_arr['transaction_type']=$data["application_type"]["application_type"];
            $transact_arr['transaction_date']=date('Y-m-d');
            $transact_arr['payment_mode']='Online';                          
            $transact_arr['paid_amount']=$rate_data['total_charge']+$denial_amount;
            $transact_arr['penalty']=$rate_data['penalty']+$denial_amount+$rate_data['arear_amount'];
            $transact_arr['status']=1; 
            $transact_arr['emp_details_id']='0';                                    
            $transact_arr['created_on']=date('Y-m-d H:i:s');
            $transact_arr['ip_address']=$_SERVER['REMOTE_ADDR'];  

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
            $sw['denial_amount']=$denial_amount;
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
            if(!empty($prive_transaction) and $prive_transaction['status']==3 and $prive_transaction['verify_status']==1)
            {
                
                return $this->response->redirect(base_url('TradeCitizen/view_transaction_receipt/'.md5($ap_id).'/'.md5($transaction_id)));
            }
            return $this->response->redirect(base_url('TradeCitizen/doc_upload/'.md5($data["license"]["id"]).'/'.md5($transaction_id)));
        }
        catch(Exception $e)
        {
            flashToast("message", $e->getMessage());
            //print_var($e->getMessage());
            return $this->response->redirect(base_url('TradeCitizen/doc_upload/'.md5($_SESSION['apply_licence_id'])));
        }
    }

    
    // call from payment page after successsfull online payment
    public function paymentFailed($pg_mas_id, $razorpay_payment_id, $razorpay_order_id, $error_code, $error_desc, $error_source, $error_step, $error_reason)
    {
        $pg_request = $this->TradeRazorPayModel->getData($pg_mas_id);
        $data["license"] = $this->ModelTradeLicense->apply_licence_md5(md5($pg_request["apply_licence_id"]));

        $param=[
            "pg_mas_id"=> $pg_request['id'], 
            "razorpay_payment_id"=> $razorpay_payment_id, 
            "razorpay_order_id"=> $razorpay_order_id,
            "error_code"=> $error_code,
            "error_desc"=> $error_desc,
            "error_source"=> $error_source,
            "error_step"=> $error_step,
            "error_reason"=> $error_reason,
            "ip_address"=> $_SERVER['REMOTE_ADDR'],
            "status"=>0,
        ];
        $response_id=$this->TradeRazorPayModel->UpdateRazorPayTable($param);
        if($response_id)
        {
            flashToast("message", "Oops, Payment Failed!!!");
            return $this->response->redirect(base_url('TradeCitizen/doc_upload/'.md5($data["license"]["id"])));
        }
    }
}
