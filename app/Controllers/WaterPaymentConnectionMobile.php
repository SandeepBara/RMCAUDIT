<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterViewConnectionFeeModel;
use App\Models\WaterConnectionDetailsViewModel;
use App\Models\WaterApplyNewConnectionModel;
use App\Controllers\WaterApplyNewConnection;
use App\Models\WaterPaymentModel;
use App\Models\Water_Transaction_Model;
use App\Models\model_ulb_mstr;
use App\Models\WaterSiteInspectionModel;
use App\Models\model_emp_details;
use App\Models\water_applicant_details_model;
use App\Models\WaterConnectionChargeModel;
use App\Models\WaterPenaltyInstallmentModel;
use App\Models\WaterSearchApplicantsMobileModel;
use App\Models\WaterPenaltyModel;

use App\Models\WaterMobileModel;
use App\Models\Citizensw_water_model;


class WaterPaymentConnectionMobile extends AlphaController
{

    protected $db;
    protected $property_db;
    protected $dbSystem;
	protected $model_ulb_mstr;
    protected $user_type;
    //protected $db_name;
    protected $conn_fee;
    protected $water_conn_dtls;
    protected $apply_waterconn_model;
    protected $apply_conn;
    protected $payment_model;
    protected $transaction_model;
    protected $modelUlb;
    protected $site_ins_model;
    protected $modelemp;
    protected $applicant_details;
    protected $conn_charge_model;
    protected $penalty_installment_model;
    protected $search_applicant_mobile_model;
    protected $WaterPenaltyModel;
    protected $Citizensw_water_model;

    // protected $conn_fee;
    // protected $water_conn_dtls;
    // protected $apply_waterconn_model;
    // protected $apply_conn;
    // protected $payment_model;
    // protected $transaction_model;
    // protected $modelUlb;
    // protected $site_ins_model;
    // protected $modelemp;
    // protected $applicant_details;
    // protected $conn_charge_model;
    // protected $penalty_installment_model;
    // protected $search_applicant_mobile_model;
    // protected $WaterPenaltyModel;
    
    
    public function __construct()
    {

        $session=session();
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];
        $this->user_type=$get_emp_details['user_type_mstr_id'];
        $this->get_ulb_detail=$session->get('ulb_dtl');
        
        parent::__construct();
        helper(['db_helper', 'form', 'qr_code_generator_helper','sms_helper']);
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_name = dbConfig("property"))
        {
            $this->property_db = db_connect($db_name);
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->conn_fee=new WaterViewConnectionFeeModel($this->db);
        $this->water_conn_dtls=new WaterConnectionDetailsViewModel($this->db);
        $this->apply_waterconn_model=new WaterApplyNewConnectionModel($this->db);
        $this->apply_conn=new WaterApplyNewConnection();
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
        // print_r($this->apply_conn);
        $this->WaterMobileModel=new WaterMobileModel($this->db);
        $this->Citizensw_water_model = new Citizensw_water_model($this->db);

    }
    
    public function index($water_conn_id)
    {

        $data=array();
        helper(['form']);
        $session=session();
        $get_ulb_id=$session->get('ulb_dtl');
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];

           // $water_conn_id=$inputs['water_conn_id'];

        $this->conn_fee->conn_fee_charge($water_conn_id);


        return view('water/water_connection/water_connection_fee_view',$data);
        

    }
    public function payment($water_conn_id)
    {	


        
        $data=array();
        $data['curr_date']=date('Y-m-d');
        $data['user_type']=$this->user_type;
        
        $data['consumer_details']=$this->apply_waterconn_model->water_conn_details($water_conn_id);


        $data['owner_details']=$this->apply_waterconn_model->water_owner_details($water_conn_id);

        //print_r($data['owner_details']);
        $data['water_conn_id']=$water_conn_id;
        
        $water_conn_details= $this->conn_fee->fetch_water_con_details($water_conn_id);
        //print_r($get_rate_id);
        

        $data['dues']= $this->conn_charge_model->due_exists($water_conn_id);


         $rate_id=$water_conn_details['water_fee_mstr_id'];

         $data['application_no']=$water_conn_details['application_no'];

         $data['conn_fee_charge']=$this->conn_fee->conn_fee_charge($water_conn_id);

         
         $data['penalty_installment']=$this->penalty_installment_model->getUnpaidInstallment($water_conn_id);
         
         $data['penalty']=$this->penalty_installment_model->getUnpaidInstallmentSum($water_conn_id);
         if(empty($data['conn_fee_charge']) && $data['penalty']>0)
         {
             $data['conn_fee_charge']['conn_fee']=0;
             $data['conn_fee_charge']['charge_for']='Penlaty Instalment';
         }
         # cheque bounce penalty
		 $data['other_penalty']=$this->WaterPenaltyModel->getUnpaidPenaltySum($water_conn_id);

         $data['application_status']=$this->apply_conn->application_status($water_conn_id);
         
        // $penalty_details=$this->payment_model->get_penalty_details($water_conn_id);
        // echo ($data['penalty_details']['penalty']);

        // $data['penalty']=$penalty_details['penalty'];
         
         
         $rebate_details=$this->payment_model->get_rebate_details($water_conn_id);
         
         // echo $data['rebate_details']['rebate'];
         $data['rebate']=$rebate_details['rebate'];

         $data['total_amount']=$data['conn_fee_charge']['conn_fee']+$data['penalty']+$data['other_penalty']-$data['rebate'];
         
         
         // print_r($data['conn_fee_charge_details']);
         // echo $water_conn_id;

         $data['transaction_details']=$this->transaction_model->get_all_transactions($water_conn_id);
         
         //print_r($data['transaction_details']);
         
        return view('mobile/water/water_proceed_payment',$data);
            
    }

   /* public function getTotalPayable()
    {
        if($this->request->getMethod()=='post')
        {
           $installment_upto_id=$_POST['installment_upto_id'];
           $apply_connection_id=$_POST['apply_connection_id'];
           $get_diff_penalty=$this->payment_model->get_penalty_details($apply_connection_id);
           $diff_penalty=$get_diff_penalty['penalty'];

            $penalty=$this->penalty_installment_model->getSum($apply_connection_id,$installment_upto_id);
            if($penalty>0)
            {
            	$count=$this->penalty_installment_model->countExistsUnpaidInstallmentafterId($apply_connection_id,$installment_upto_id);

                $penalty_for_rebate=$this->penalty_installment_model->getUnpaidInstallmentSum($apply_connection_id);
                
            	if($count==0)
            	{
            		$installment_rebate=(10*$penalty_for_rebate)/100;
            	}
            	else
            	{
            		$installment_rebate=0;
            	}

            	$result=["penalty"=>$penalty+$diff_penalty,"installment_rebate"=>$installment_rebate];
                return json_encode($result);
            }
        }
        
    }*/

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



    public function proceed_payment() // call from payment page in view
    {
        $data=array();
        $session=session();
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];
        $user_type_mstr_id=$get_emp_details['user_type_mstr_id'];

        if($this->request->getMethod()=='post')
        {
            $this->db->transBegin();
            $inputs=arrFilterSanitizeString($this->request->getVar());
            // print_r($inputs);

            //-------------sandeep--------------//
            $water_conn_id_md5 = $this->request->getPost('water_conn_id');
            $sql_my="select * 
                     from tbl_site_inspection 
                     where scheduled_status = 1 
                        and md5(apply_connection_id::text) = '$water_conn_id_md5' 
                        and verified_by = 'JuniorEngineer' and payment_status = 0 order by id desc ";
            $data['si_verify_dtls']=$this->WaterMobileModel->getDataRowQuery2($sql_my);
            
            $si_verify_id='';
            if(!empty($data['si_verify_dtls']['result']))
                $si_verify_id = $data['si_verify_dtls']['result'][0]['id'];             
            
            //-------------end-----------------//

            //print_var($si_verify_id);die;
            $payment_mode=$inputs['payment_mode'];
            $total_paid_amount=$inputs['total_amount'];
            $total_amount=$inputs['conn_fee'];
            
            $rebate=$inputs['rebate'];
            $water_conn_id=$inputs['water_conn_id'];
            $payment_for=$inputs['payment_for'];
            $penalty_installment_upto_id=$inputs['penalty_installment_upto_id'];
            
            $connection_type_id=$_POST['connection_type_id'];

            $get_water_conn_id=$this->water_conn_dtls->fetch_water_con_details($water_conn_id);
            
            $penalty=$this->WaterPenaltyModel->getUnpaidPenaltySum($water_conn_id);
            $other_penalty=$penalty;
            
            $water_conn_id=$get_water_conn_id['id'];
            $ward_id=$get_water_conn_id['ward_id'];
            $doc_status=$get_water_conn_id['doc_status'];
            $application_no=$get_water_conn_id["application_no"];
            
            $installment_rebate=0;

            $conn_payment_status = $get_water_conn_id['payment_status'];
            
            # Regularization
            if($connection_type_id==2)
            {
                $pay_full=$_POST['pay_full'];
	            $penalty_installment_amount=$this->penalty_installment_model->getPenaltyforRebate(md5($water_conn_id));

	            if($pay_full==1)
	            {
	                $penalty+=$this->penalty_installment_model->getUnpaidInstallmentSum(md5($water_conn_id));
	                $installment_rebate=in_array($conn_payment_status,[0,null])?((10*$penalty_installment_amount)/100):0;
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
	                    $installment_rebate=in_array($conn_payment_status,[0,null])?((10*$penalty_installment_amount)/100):0;
	                }
	                else
	                {
	                    $installment_rebate=0;
	                }
	                
	            }

        	}
            // echo "sss".$penalty_installment_amount.'-'.$installment_rebate;
            //echo $penalty;
           
            $total_paid_amount=round($inputs['conn_fee']+$penalty-$installment_rebate);
            //$get_diff_penalty=$this->payment_model->get_penalty_details(md5($water_conn_id));
            //$diff_penalty=$get_diff_penalty['penalty'];


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
            $trans_arr['ward_mstr_id']=$ward_id;
            $trans_arr['ip_address']=$get_emp_details['ip_address'];
            $trans_arr['transaction_type']=$payment_for;
            $trans_arr['transaction_date']=date('Y-m-d');
            $trans_arr['related_id']=$water_conn_id;
            $trans_arr['payment_mode']=$payment_mode;
            $trans_arr['penalty']=$penalty;
            $trans_arr['rebate']=$rebate+$installment_rebate;
            $trans_arr['paid_amount']=$total_paid_amount;
            $trans_arr['total_amount']=$total_amount;
            $trans_arr['emp_details_id']=$emp_id;
            $trans_arr['created_on']=date('Y-m-d H:i:s');
            $trans_arr['status']=$status;
            $trans_arr['payment_from']="TC";
            
            //print_r($trans_arr);exit;
            $check_trans_exist=$this->payment_model->check_transaction_exist($water_conn_id,$total_paid_amount);
            if($check_trans_exist==0)
            {
                $transaction_id=$this->payment_model->insert_transaction($trans_arr);
                if($transaction_id)
                {


                    $trans_no="WTRAN".$transaction_id.date('YmdHis');
                    $this->payment_model->update_trans_no($trans_no,$transaction_id);
                    $this->payment_model->update_conn_charge_paid_status($water_conn_id,$transaction_id,$payment_for);
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
                            $this->penalty_installment_model->updateInstallment($penalty_installment_id,$transaction_id);
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
                        $this->WaterPenaltyModel->updateUnpaidPenalty($water_conn_id, 'Applicant');

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
                    

                    if($payment_for=='Site Inspection' and $status==1 && $si_verify_id!='')
                    {
                        
                        /*  $level_pending_arr=array();
                            $level_pending_arr['apply_connection_id']=$water_conn_id;
                            $level_pending_arr['sender_user_type_id']=13;
                            $level_pending_arr['receiver_user_type_id']=14;
                            $level_pending_arr['created_on']=date('Y-m-d H:i:s');
                            $level_pending_arr['emp_details_id']=$emp_id;

                            $this->payment_model->insert_level_pending($level_pending_arr);
                        */
                        


                        //$this->site_ins_model->update_site_ins_pay_status($water_conn_id);

                        $sql_my="update tbl_site_inspection set payment_status = 1
                                    where scheduled_status = 1 
                                        and md5(apply_connection_id::text) = '$water_conn_id_md5' 
                                        and verified_by = 'JuniorEngineer' 
                                        and payment_status = 0 
                                        and id = $si_verify_id ";
                                
                        $this->WaterMobileModel->getDataRowQuery2($sql_my); 

                    }
                    // print_r($data['connection_dtls']);

                    

                }

                #------------sws push------------------
                $sws_whare = ['apply_connection_id'=>$water_conn_id];
                $sws = $this->Citizensw_water_model->getData($sws_whare);
                if($get_water_conn_id['apply_from']=='sws' && !empty($sws))
                {                    
                    $sw = [];
                    $sw['sw_stage']= 2 ;                                             
                    $sw['total_amount']=$trans_arr['paid_amount'] ;
                    $where_sw = ['apply_connection_id'=>$trans_arr['related_id'],'id'=> $sws['id']];                            
                    $this->Citizensw_water_model->updateData($sw,$where_sw);

                    $push_sw=array();
                    $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
                    $path=base_url('citizenPaymentReceipt/view_transaction_receipt/'.$ulb_mstr_id.'/'.$water_conn_id.'/'.$transaction_id);
                    $push_sw['application_stage']=11;
                    $push_sw['status']='Payment Done via Online of '.$total_paid_amount.'-/Rs';
                    $push_sw['acknowledgment_no']=$get_water_conn_id['application_no'];
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
                    // print_var($resp);
                    $respons_data=[];
                    $respons_data['apply_connection_id']=$trans_arr['related_id'];
                    $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                    'data'=>$push_sw]);
                    $respons_data['tbl_single_window_id']=$sws['id'];
                    $respons_data['emp_id']=$emp_id;
                    $respons_data['response_status']=json_encode($resp);
                    $this->Citizensw_water_model->insertResponse($respons_data);
                }
                //die;
                #--------------------------------------

                if($this->db->transStatus() === FALSE)
                {
                    $this->db->transRollback();
                    flashToast("payment", "Something went wrong in payment!!!");
                    return $this->response->redirect(base_url('WaterPaymentConnectionMobile/payment/'.md5($water_conn_id)));
                }
                else
                {   
                    $this->db->transCommit();

                    //echo $water_conn_id.'-'.$transaction_id;
                    $mobile_no=$this->search_applicant_mobile_model->getMobileNo(md5($water_conn_id));
                    $sms="Your Water Connection Payment of Rs. ".$total_paid_amount." for Application No.  ".$application_no." is successfully done. ".$this->get_ulb_detail['ulb_name'];
                    SMSJHGOVT($mobile_no,$sms);

                    return $this->response->redirect(base_url('WaterPaymentConnectionMobile/view_transaction_receipt/'.md5($water_conn_id).'/'.md5($transaction_id)));
                }

            }
           else
           {
               flashToast("payment", "Transaction Already Done!!!");
                return $this->response->redirect(base_url('WaterPaymentConnectionMobile/payment/'.md5($water_conn_id)));
           }

        }

            
            

    }


    public function view_transaction_receipt($water_conn_id,$transaction_id)
    {   

        $data=array();
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
        $path=base_url('citizenPaymentReceipt/view_transaction_receipt/'.$ulb_mstr_id.'/'.$water_conn_id.'/'.$transaction_id);
		$data['ss']=qrCodeGeneratorFun($path);
		$data['transaction_id']=$transaction_id;
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data['water_conn_id']=$water_conn_id;

		$data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);

        $data['applicant_details']=$this->payment_model->fetch_all_application_data($water_conn_id);

        $data['transaction_details']=$this->payment_model->transaction_details($transaction_id);
       
        $data['emp_dtls'] = $this->modelemp->emp_dtls($data['transaction_details']['emp_details_id']);

        $data['applicant_basic_details']=$this->applicant_details->getApplicantsName($water_conn_id);
        
       return view('mobile/water/payment_conn_tax_receipt',$data);  
        
        
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
