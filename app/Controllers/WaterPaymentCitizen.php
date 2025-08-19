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
use Razorpay\Api\Api;
use App\Models\Citizensw_water_model;
use App\Models\Siginsw_water_model;
use Exception;

class WaterPaymentCitizen extends HomeController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
    protected $model_ulb_mstr;
    protected $user_type;
    protected $emp_id;
    //protected $db_name;
    protected $Citizensw_water_model;
    protected $Siginsw_water_model;
    protected $payment_model;
    
    
    public function __construct()
    {
 
        $session=session();
        $get_emp_details=$session->get('emp_details');
        $this->emp_id=$get_emp_details['id'];
        $this->user_type=$get_emp_details['user_type_mstr_id'];

        parent::__construct();
        helper(['db_helper', 'form','form_helper', 'qr_code_generator_helper','sms_helper', 'rozarpay_helper']);
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        // if($db_name = dbConfig("property"))
        // {
        //     $this->property_db = db_connect($db_name);
        // }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        
        //$db_name = db_connect("db_rmc_property"); 
        
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


    }
    public function __destruct()
    {
        if($this->db)
        {
            $this->db->close();            
        }
        if($this->property_db)
        {
            $this->property_db->close();
        }
        if( $this->dbSystem )
        {
            $this->dbSystem->close();
        }
    }
    
    public function index($water_conn_id)
    {
        $data=array();
        helper(['form']);
        $session=session();
        $get_ulb_id=$session->get('ulb_dtl')??getUlbDtl();;
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];
        $this->conn_fee->conn_fee_charge($water_conn_id);
        return view('citizen/water/water_connection_fee_view',$data);
    }

    public function payment($water_conn_id)
    {
        $data=array();
        $data['curr_date']=date('Y-m-d');
        $data['user_type']=$this->user_type;
        
        $data['consumer_details']=$this->apply_waterconn_model->water_conn_details($water_conn_id);


        $data['owner_details']=$this->apply_waterconn_model->water_owner_details($water_conn_id);

        $data['water_conn_id']=$water_conn_id;
        
        $water_conn_details= $this->conn_fee->fetch_water_con_details($water_conn_id);
        //print_r($get_rate_id);

        $data['dues']= $this->conn_charge_model->due_exists($water_conn_id);


         $rate_id=$water_conn_details['water_fee_mstr_id'];
        
         $data['application_no']=$water_conn_details['application_no'];

         $data['conn_fee_charge']=$this->conn_fee->conn_fee_charge($water_conn_id);

         
         //$data['penalty_installment']=$this->penalty_installment_model->getUnpaidInstallment($water_conn_id);
         $data['penalty']=$this->penalty_installment_model->getUnpaidInstallmentSum($water_conn_id);

         # cheque bounce penalty
         $data['other_penalty']=$this->WaterPenaltyModel->getUnpaidPenaltySum($water_conn_id);

         $data['application_status']=$this->apply_conn->application_status($water_conn_id);
         
         
         $rebate_details=$this->payment_model->get_rebate_details($water_conn_id);
         
         $data['rebate']=$rebate_details['rebate'];

         # Regularizaton
         if($data['consumer_details']['connection_type_id'] == 2)
         $data['rebate'] += (($data['penalty']/100) * 10); // 10 % Off in whole payment of penalty

         $data['total_amount']=$data['conn_fee_charge']['conn_fee']+$data['penalty']+$data['other_penalty']-$data['rebate'];
         
         
         

         $data['transaction_details']=$this->transaction_model->get_all_transactions($water_conn_id);
         
        return view('citizen/water/water_proceed_payment', $data);
        
    }

    public function getTotalPayable()
    {   
        if($this->request->getMethod()=='post')
        {   
           $installment_upto_id=$_POST['installment_upto_id'];
           $apply_connection_id=$_POST['apply_connection_id'];
            

            $get_penalty=$this->penalty_installment_model->getSum($apply_connection_id,$installment_upto_id);
            
            $penalty=$get_penalty['installment_amount'];
            $total_count_left=$get_penalty['count'];
            $installment_rebate=0;

            if($penalty>0 and $total_count_left>=3)
            {   
                $count=$this->penalty_installment_model->countExistsUnpaidInstallmentafterId($apply_connection_id,$installment_upto_id);

                $penalty_for_rebate=$this->penalty_installment_model->getPenaltyforRebate($apply_connection_id);
                
                if($count==0)
                {
                    $installment_rebate=(10*$penalty_for_rebate)/100;
                }
                else
                {
                    $installment_rebate=0;
                }


            }
            
             $result=["penalty"=>$penalty,"installment_rebate"=>$installment_rebate];
             //print_r($result);
            
             return json_encode($result);

        }
        
    }
    
    public function handleRazorPayRequest()
    {
        $data=array();
        if($this->request->getMethod()=='post')
        {
            $inputs=arrFilterSanitizeString($this->request->getVar());
            $water_conn_id=$apply_connection_id=$inputs['apply_connection_id'];
            $app_details=$this->apply_waterconn_model->water_conn_details($apply_connection_id);
            $_SESSION['water_conn_id']=$apply_connection_id;
            # Fee Charge Calculating start
            $data['conn_fee_charge']=$this->conn_fee->conn_fee_charge($apply_connection_id);
            
            $data['penalty']=$this->penalty_installment_model->getUnpaidInstallmentSum($water_conn_id);

            # cheque bounce penalty
            $data['other_penalty']=$this->WaterPenaltyModel->getUnpaidPenaltySum($water_conn_id);

            $data['application_status']=$this->apply_conn->application_status($water_conn_id);

            $rebate_details=$this->payment_model->get_rebate_details($water_conn_id);

            $data['rebate']=$rebate_details['rebate'];

            # Regularizaton
            if($app_details['connection_type_id'] == 2)
            $data['rebate'] += (($data['penalty']/100) * 10); // 10 % Off in whole payment of penalty

            $data['amount']=$data['conn_fee_charge']['conn_fee']+$data['penalty']+$data['other_penalty']-$data['rebate'];
            
            # Fee Charge Calculating end
            
            $get_owner_details=$this->apply_waterconn_model->water_owner_details($apply_connection_id);
            
            $data['owner_name']=$get_owner_details[0]['applicant_name'];
            $data['mobile_no']=$get_owner_details[0]['mobile_no'];
            $data['email_id']=$get_owner_details[0]['email_id'];
            
            $razor_pay=array();
            $razor_pay['payment_from']='Connection';
            $razor_pay['related_id']=$app_details['id'];
            $razor_pay['amount']=$data['amount'];
            $razor_pay['created_on']=date('Y-m-d H:i:s');
            $razor_pay['ip_address']=$_SERVER['REMOTE_ADDR'];

            if($data['amount']<=0)
            {
                flashToast("payment", "Payable amount shold be atleast 1 rupee. !!!");
                return $this->response->redirect(base_url('WaterPaymentCitizen/payment/'.($water_conn_id)));
            }
            $api_key_id = getenv("razorpay.api_key_id");
            $api_secret = getenv("razorpay.api_secret_key");
            $api = new Api($api_key_id, $api_secret);
            $amount=$razor_pay['amount']*100;// Amount is in currency subunits. Hence, 50000 refers to 50000 paise
            $order_id = $api->order->create(array('receipt' => '123', 'amount' => $amount, 'currency' => 'INR'));
            $data["order_id"] = $order_id["id"];
            $razor_pay["razorpay_order_id"] = $order_id["id"];
            $data["pg_mas_id"]=md5($this->WaterRazorPayModel->insertData($razor_pay));
            return view('citizen/water/pay_demand_online', $data);
            
        }
        else
        {
            return $this->response->redirect(base_url('WaterSearchConsumerCitizen/index/search'));
        }
    }

    // call from payment page after successsfull online payment

    public function proceed_payment($pg_mas_id, $razorpay_payment_id, $razorpay_order_id, $razorpay_signature)
    {
        try
        {   //print_var(getenv('razorpay.api_key_id'));die;
            
            $api_key_id = getenv("razorpay.api_key_id");
            $api_secret = getenv("razorpay.api_secret_key");
            $RazorpayAPi = new Api($api_key_id, $api_secret);
            $attributes  = array('razorpay_signature'  => $razorpay_signature,  'razorpay_payment_id'  => $razorpay_payment_id ,  'razorpay_order_id' => $razorpay_order_id);
            $order  = $RazorpayAPi->utility->verifyPaymentSignature($attributes);            
            
            // $pg_request = $this->WaterRazorPayModel->getData(md5($pg_mas_id));
            $pg_request = $this->WaterRazorPayModel->getData($pg_mas_id);            
            if(empty($pg_request) || $pg_request['razorpay_order_id']!=$razorpay_order_id)
            {
                throw new Exception('Payment Faild due to Invalide Order No.');
            }
            $apply_connection_id=md5($pg_request["related_id"]);
            $water_conn_id=md5($pg_request["related_id"]);

            $param=[
                        // "pg_mas_id"=> $pg_mas_id, 
                        "pg_mas_id"=>  $pg_request['id'], 
                        "razorpay_payment_id"=> $razorpay_payment_id, 
                        "razorpay_order_id"=> $razorpay_order_id,
                        "razorpay_signature"=> $razorpay_signature,
                        "ip_address"=> $_SERVER['REMOTE_ADDR'],
                        "status"=>1
                    ];
            $response_id=$this->WaterRazorPayModel->UpdateRazorPayTable($param);
            if($response_id)
            {
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
                //print_var($input['conn_fee_charge']['charge_for']);die;
                $this->db->transBegin();

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

               

                $trans_arr=array();
                $trans_arr['ward_mstr_id']=$ward_id;
                $trans_arr['transaction_type']=$payment_for;
                $trans_arr['transaction_date']=date('Y-m-d');
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
                $trans_arr['ip_address']=$_SERVER['REMOTE_ADDR'];
                
                
                
                $check_trans_exist=$this->payment_model->check_transaction_exist($water_conn_id, $total_paid_amount);
                //print_var($check_trans_exist);die;
                

                if($check_trans_exist==0 && round($total_paid_amount)==round($pg_request["amount"]))
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

                            /*  $level_pending_arr=array();
                            $level_pending_arr['apply_connection_id']=$water_conn_id;
                            $level_pending_arr['sender_user_type_id']=13;
                            $level_pending_arr['receiver_user_type_id']=14;
                            $level_pending_arr['created_on']=date('Y-m-d H:i:s');
                            $level_pending_arr['emp_details_id']=$emp_id;

                            $this->payment_model->insert_level_pending($level_pending_arr);
                            */



                            $level_pending_arr=array();
                            $level_pending_arr['apply_connection_id']=$water_conn_id;
                            $level_pending_arr['sender_user_type_id']=13;
                            $level_pending_arr['receiver_user_type_id']=14;
                            $level_pending_arr['created_on']=date('Y-m-d H:i:s');
                            $level_pending_arr['emp_details_id']=0;
                            $this->payment_model->insert_level_pending($level_pending_arr);

                            $this->site_ins_model->update_site_ins_pay_status($water_conn_id);
                            $sql = "UPDATE tbl_level_pending SET forward_date='".date('Y-m-d')."',
                                        forward_time='".date('H:i:s')."',
                                        remarks='Aouto Forword',
                                        verification_status=1,
                                        receiver_user_id = 0
                                        FROM
                                        (
                                            SELECT id
                                            FROM tbl_level_pending
                                            WHERE verification_status=0 
                                                AND status !=0
                                                AND apply_connection_id = $water_conn_id
                                                AND receiver_user_type_id = 13  
                                            ORDER BY id DESC
                                            LIMIT 1                                                  
                                        ) subquery
                                WHERE tbl_level_pending.verification_status=0 
                                    AND tbl_level_pending.apply_connection_id = $water_conn_id
                                    AND tbl_level_pending.receiver_user_type_id = 13
                                    AND tbl_level_pending.id = subquery.id
                                        ";
                            // print_var($sql);die;
                            $this->db->query($sql);

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
                    
                    if($this->db->transStatus() === FALSE)
                    {
                        $this->db->transRollback();
                        die("Something went wrong in payment!!!");
                        flashToast("payment", "Something went wrong in payment!!!");
                        
                        return $this->response->redirect(base_url('WaterPaymentCitizen/payment/'.md5($water_conn_id)));
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
                else
                {
                    if($check_trans_exist!=0)
                    {
                        flashToast("payment", "Transaction Already Done!!!");
                        return $this->response->redirect(base_url('WaterPaymentCitizen/payment/'.md5($water_conn_id)));
                    }
                    if($total_paid_amount!=$pg_request["amount"])
                    {
                        //echo $total_paid_amount, $pg_request["amount"];exit;
                        flashToast("payment", "Paid Amount Miss-matching with demand amount!!!");
                        return $this->response->redirect(base_url('WaterPaymentCitizen/payment/'.md5($water_conn_id)));
                    }
                }
            }
        }
        catch (Exception $e)
        {
            flashToast("payment", $e->getMessage()); 
            return $this->response->redirect(base_url('WaterPaymentCitizen/payment/'.($_SESSION['water_conn_id'])));
        }

        
    }

    /* public function proceed_payment_old($pg_mas_id, $razorpay_payment_id, $razorpay_order_id, $razorpay_signature)
    {
        $data=array();
        $session=session();
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];
        $user_type_mstr_id=$get_emp_details['user_type_mstr_id'];
        $this->get_ulb_detail=$session->get('ulb_dtl');
        
        $param=[
                    "pg_mas_id"=> $pg_mas_id, 
                    "razorpay_payment_id"=> $razorpay_payment_id, 
                    "razorpay_order_id"=> $razorpay_order_id,
                    "razorpay_signature"=> $razorpay_signature,
                    "ip_address"=> $_SERVER['REMOTE_ADDR'],
                ];
        $response_id=$this->WaterRazorPayModel->UpdateRazorPayTable($param);
        if($response_id)
        {
            $this->db->transBegin();
            $inputs=arrFilterSanitizeString($this->request->getVar());
            
            
			$get_razor_pay_req_dtls=$this->WaterRazorPayModel->getData($pg_mas_id);
            $apply_connection_id=$get_razor_pay_req_dtls['related_id'];
			
            //$payment_mode=$inputs['payment_mode'];
            
            $get_water_conn_id=$this->water_conn_dtls->fetch_water_con_details($water_conn_id);

            $water_conn_id=$get_water_conn_id['id'];
            $ward_id=$get_water_conn_id['ward_id'];
            $doc_status=$get_water_conn_id['doc_status'];
			$connection_type_id=$get_water_conn_id['connection_type_id'];
            $application_no=$get_water_conn_id['application_no'];
			

            $penalty=$this->WaterPenaltyModel->getUnpaidPenaltySum($water_conn_id);
            $other_penalty=$penalty;

            # Regularization
            if($connection_type_id==2)
            {
                $penalty_installment_amount=$this->penalty_installment_model->getPenaltyforRebate(md5($water_conn_id));
                $pay_full=1; //Full payment incase of online payment
                if($pay_full==1)
                {
                    $penalty+=$this->penalty_installment_model->getUnpaidInstallmentSum(md5($water_conn_id));
                    $installment_rebate=(10*$penalty_installment_amount)/100;
                }
               
            }
            // echo "sss".$penalty_installment_amount.'-'.$installment_rebate;
            // echo $penalty;
           
            $total_paid_amount=round($inputs['conn_fee']+$penalty-$installment_rebate);
            //$get_diff_penalty=$this->payment_model->get_penalty_details(md5($water_conn_id));
            //$diff_penalty=$get_diff_penalty['penalty'];


            $status=1;


            $trans_arr=array();
            $trans_arr['ward_mstr_id']=$ward_id;
            $trans_arr['transaction_type']=$payment_for;
            $trans_arr['transaction_date']=date('Y-m-d');
            $trans_arr['related_id']=$water_conn_id;
            $trans_arr['payment_mode']='Online';
            $trans_arr['penalty']=$penalty;
            $trans_arr['rebate']=$rebate+$installment_rebate;
            $trans_arr['paid_amount']=$total_paid_amount;
            $trans_arr['total_amount']=$total_amount;
            $trans_arr['emp_details_id']=$emp_id;
            $trans_arr['created_on']=date('Y-m-d H:i:s');
            $trans_arr['status']=$status;
            $trans_arr['payment_from']="Online";
            $trans_arr['ip_address']=$get_emp_details['ip_address'];
            
            
            
            //print_r($trans_arr);
            $check_trans_exist=$this->payment_model->check_transaction_exist($water_conn_id, $total_paid_amount);
            
            

            if($check_trans_exist==0)
            {
                $transaction_id=$this->payment_model->insert_transaction($trans_arr);
                if($transaction_id)
                {


                    $trans_no="WONLTXN".$insert_id.date('YmdHis');
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
                        $level_pending['emp_details_id']=$emp_id;
                        

                       $this->payment_model->insert_level_pending($level_pending);
                       
                    }
                    

                    if($payment_for=='Site Inspection' and $status==1)
                    {
                                             


                        $this->site_ins_model->update_site_ins_pay_status($water_conn_id);

                    }
                    // print_r($data['connection_dtls']);

                    

                }

            

                if($this->db->transStatus() === FALSE)
                {
                    $this->db->transRollback();
                    flashToast("payment", "Something went wrong in payment!!!");
                    return $this->response->redirect(base_url('WaterPaymentCitizen/payment/'.md5($water_conn_id)));
                }
                else
                {
                    $this->db->transCommit();
                    $mobile_no=$this->search_applicant_mobile_model->getMobileNo(md5($water_conn_id));
                    $sms="Your Water Connection Payment of Rs. ".$total_paid_amount." for Application No.  ".$application_no." is successfully done. ".$this->get_ulb_detail['ulb_name'];
                    SMSJHGOVT($mobile_no,$sms);
                    return $this->response->redirect(base_url('WaterPaymentCitizen/view_transaction_receipt/'.md5($water_conn_id).'/'.md5($transaction_id)));
                }
           }
           else
           {
               flashToast("payment", "Transaction Already Done!!!");
                return $this->response->redirect(base_url('WaterPaymentCitizen/payment/'.md5($water_conn_id)));
           }

        }
    } 
    */

    public function paymentFailed($pg_mas_id, $razorpay_payment_id, $razorpay_order_id, $error_code, $error_desc, $error_source, $error_step, $error_reason)
    {
        // $pg_request = $this->WaterRazorPayModel->getData(md5($pg_mas_id));
        $pg_request = $this->WaterRazorPayModel->getData($pg_mas_id);
        $apply_connection_id=md5($pg_request["related_id"]);
        $water_conn_id=md5($pg_request["related_id"]);

        $param=[
                    // "pg_mas_id"=> $pg_mas_id, 
                    "pg_mas_id"=> $pg_request['id'],
                    "razorpay_payment_id"=> $razorpay_payment_id, 
                    "razorpay_order_id"=> $razorpay_order_id,
                    "error_code"=> $error_code,
                    "error_desc"=> $error_desc,
                    "error_source"=> $error_source,
                    "error_step"=> $error_step,
                    "error_reason"=> $error_reason,
                    "ip_address"=> $_SERVER['REMOTE_ADDR'],
                    "status"=>0
                ];
        $response_id=$this->WaterRazorPayModel->UpdateRazorPayTable($param);
        if($response_id)
        {
            flashToast("payment", "Oops, Payment Failed!!!");
            return $this->response->redirect(base_url('WaterPaymentCitizen/payment/'.($water_conn_id)));
        }
    }

    public function view_transaction_receipt($water_conn_id, $transaction_id)
    {   
        $data=array();
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']??getUlbDtl()['ulb_mstr_id']; 
        $path=base_url('citizenPaymentReceipt/view_transaction_receipt/'.$ulb_mstr_id.'/'.$water_conn_id.'/'.$transaction_id);
        $data["path"] = $path;
        $data['ss']=qrCodeGeneratorFun($path);
        $data['transaction_id']=$transaction_id;         
        $data['water_conn_id']=$water_conn_id;

        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);

        $data['applicant_details']=$this->payment_model->fetch_all_application_data($water_conn_id);

        $data['transaction_details']=$this->payment_model->transaction_details($transaction_id);
        
        if($data['transaction_details']['emp_details_id'] != 0)
        $data['emp_dtls'] = $this->modelemp->emp_dtls($data['transaction_details']['emp_details_id']);
        else
        $data['emp_dtls'] = [];
        $data['ulb_id']=$ulb_mstr_id;
        //print_var($data['transaction_details']);
        //print_var($this->user_type);
        $data['user_type']=$this->user_type;
        $data['applicant_basic_details']=$this->applicant_details->getApplicantsName($water_conn_id);
        
        # Tax Collector - 5
        if($this->user_type!=5)
        {
            $data["header"]='Citizen';
            return view('water/water_connection/payment_receipt',$data);  
        }
        else
        {
            # Tax Collector
            return view('mobile/water/payment_conn_tax_receipt',$data);  
        }
        
        
    }
    

    public function penaltyRebate()
    {
        if($this->request->getMethod()=='post')
        {
            $water_conn_id=$_POST['apply_connection_id'];
            $penalty=$this->penalty_installment_model->getPenaltyforRebate($water_conn_id);
            if($penalty>0)
            {
                $installment_rebate=(10*$penalty)/100;
            }
            else
            {
                $installment_rebate=0;
            }
            $installment_rebate=["installment_rebate"=>$installment_rebate];
            return json_encode($installment_rebate);
            
        }
    }
  
    
}
