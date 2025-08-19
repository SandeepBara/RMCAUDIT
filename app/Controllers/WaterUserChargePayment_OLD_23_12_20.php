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
use App\Models\model_water_consumer;
use App\Models\water_consumer_details_model;
use App\Models\model_emp_details;
use App\Models\WaterConsumerCollectionModel;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterViewConsumerModel;


class WaterUserChargePayment extends AlphaController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
	protected $model_ulb_mstr;
    
    //protected $db_name;
    
    
    public function __construct()
    {
        parent::__construct();
        helper(['db_helper', 'form', 'qr_code_generator_helper']);
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
        $this->consumer_model=new model_water_consumer($this->db);
        $this->modelemp = new model_emp_details($this->dbSystem);
        $this->collection_model=new WaterConsumerCollectionModel($this->db);
        $this->demand_model=new WaterConsumerDemandModel($this->db);
        $this->consumer_details_model=new WaterViewConsumerModel($this->db);
        $this->consumer_owner_details=new water_consumer_details_model($this->db);

       // print_r($this->apply_conn);

    }
    

    public function pay_user_charge() 
    {

        $data=array();
        $session=session();
        $get_emp_details=$session->get('emp_details');
       // print_r($get_emp_details);
        $emp_id=$get_emp_details['id'];
        $user_type_mstr_id=$get_emp_details['user_type_mstr_id'];
        //echo $get_emp_details['ip_address'];

      

        if($this->request->getMethod()=='post')
        {
            $this->db->transBegin();
            $inputs=arrFilterSanitizeString($this->request->getVar());
             //print_r($inputs);

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
            $trans_arr['ip_address']=$get_emp_details['ip_address'];
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
                    $this->demand_model->update_demand_status($consumer_id,$month);
                }


              

                

            }
            

              if($this->db->transStatus() === FALSE){
                $this->db->transRollback();
                flashToast("payment", "Something errordue to payment!!!");
                $session=session();
                $get_emp_details=$session->get('emp_details');
                $emp_id=$get_emp_details['id'];
                $user_type_mstr_id=$get_emp_details['user_type_mstr_id'];
                

                if($user_type_mstr_id==5)
                {
                        return $this->response->redirect(base_url('WaterViewConsumerMobile/view/'.md5($consumer_id)));
                }
                else
                {
                        return $this->response->redirect(base_url('WaterUserChargeProceedPayment/pay_payment/'.md5($consumer_id)));
                }

                }else{
                    
                    $this->db->transCommit();

                    //echo $water_conn_id.'-'.$transaction_id;

                    return $this->response->redirect(base_url('WaterUserChargePayment/payment_tc_receipt/'.md5($consumer_id).'/'.md5($transaction_id)));
                }


            }
        }
      //  return $this->response->redirect(base_url('WaterUserChargePayment/payment_tc_receipt/'.md5($consumer_id).'/'.md5($transaction_id)));
    }

    public function payment_tc_receipt($consumer_id=NULL,$transaction_id=null)
    {
        $data=array();
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $session=session();
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];
        $user_type_mstr_id=$get_emp_details['user_type_mstr_id'];
        
		$path=base_url('citizenPaymentReceipt/payment_jsk_receipt/'.$ulb_mstr_id.'/'.$transaction_id);
		$data['ss']=qrCodeGeneratorFun($path);
        $data['transaction_id']=$transaction_id;
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
        $data['consumer_id']=$consumer_id;
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $data['consumer_details']=$this->consumer_details_model->consumerDetails($consumer_id);
        $data['consumer_owner_details']=$this->consumer_owner_details->getConsumerDetailsbyMd5($consumer_id);
        $data['transaction_details']=$this->payment_model->transaction_details($transaction_id);
        $data['emp_dtls'] = $this->modelemp->emp_dtls($data['transaction_details']['emp_details_id']);
        if($user_type_mstr_id==5)
        {
            return view('mobile/water/payment_tax_receipt', $data);
        }
        else
        {
            return view('water/water_connection/user_charge_payment_receipt',$data);
        }
    }



}
