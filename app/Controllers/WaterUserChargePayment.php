<?php 
namespace App\Controllers;
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
use App\Models\WaterSearchConsumerMobileModel;
use App\Models\WaterPenaltyInstallmentModel;
use App\Models\WaterPenaltyModel;
// ----------21-01-22---------------
use App\Models\model_view_water_consumer;
use App\Models\model_visiting_dtl;
use App\Models\WaterConnectionChargeModel;
use App\Models\WaterMeterStatusModel;


class WaterUserChargePayment extends AlphaController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
    protected $model_ulb_mstr;

    protected $conn_fee;
    protected $water_conn_dtls;
    protected $apply_waterconn_model;
    protected $apply_conn;
    protected $payment_model;
    protected $transaction_model;
    protected $modelUlb;
    protected $site_ins_model;
    protected $consumer_model;
    protected $modelemp;
    protected $collection_model;
    protected $demand_model;
    protected $consumer_details_model;
    protected $consumer_owner_details;
    protected $search_consumer_mobile_model;
    protected $penalty_installment_model;
    protected $WaterPenaltyModel;
    protected $WaterConsumerDemandModel;
    protected $Water_Transaction_Model;
    protected $model_visiting_dtl;
    
    public function __construct()
    {
        parent::__construct();
        helper(['db_helper', 'form', 'qr_code_generator_helper','sms_helper']);
        if($db_name = dbConfig("water"))
        {
            //echo $db_name;
            $this->db = db_connect($db_name);
        }
        if($db_name = dbConfig("property"))
        {
            $this->property_db = db_connect($db_name);
        }
        if($db_system = dbSystem())
        {
            $this->dbSystem = db_connect($db_system); 
        }
        
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->conn_fee=new WaterViewConnectionFeeModel($this->db);
        $this->water_conn_dtls=new WaterConnectionDetailsViewModel($this->db);
        $this->apply_waterconn_model=new WaterApplyNewConnectionModel($this->db);
        $this->apply_conn=new WaterApplyNewConnection();
        $this->payment_model=new WaterPaymentModel($this->db);
        $this->Water_Transaction_Model=new Water_Transaction_Model($this->db);
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->site_ins_model=new WaterSiteInspectionModel($this->db);
        $this->consumer_model=new model_water_consumer($this->db);
        $this->modelemp = new model_emp_details($this->dbSystem);
        $this->collection_model=new WaterConsumerCollectionModel($this->db);
        $this->WaterConsumerDemandModel=new WaterConsumerDemandModel($this->db);
        $this->consumer_details_model=new WaterViewConsumerModel($this->db);
        $this->consumer_owner_details=new water_consumer_details_model($this->db);
        $this->search_consumer_mobile_model=new WaterSearchConsumerMobileModel($this->db);
        // print_r($this->apply_conn);
        $this->penalty_installment_model=new WaterPenaltyInstallmentModel($this->db);        
        $this->conn_charge_model=new WaterConnectionChargeModel($this->db);
        $this->WaterPenaltyModel=new WaterPenaltyModel($this->db);
        // ----------21-01-22---------------
        $this->model_view_water_consumer = new model_view_water_consumer($this->db);
		$this->meter_status_model = new WaterMeterStatusModel($this->db);

        $this->model_visiting_dtl = new model_visiting_dtl($this->dbSystem);
        
    }
    


    public function payment_details($consumer_id) 
    {
        $data=array();
        //echo date('Y');
        
        $curr_month=date('m');
        if($curr_month==01 or $curr_month==02 or $curr_month==03)
        {
           $curr_year=date('Y')-1;
        }
        else
        {
            $curr_year=date('Y');
        }

        $next_year=$curr_year+1;
        $start_year=$curr_year.'-04-01';
        $end_year=$next_year.'-03-31';
        
        $data['consumer_dtls']=$this->search_consumer_mobile_model->getConsumerDetailsbyId($consumer_id);
        if(empty($data['consumer_dtls']) || count($data['consumer_dtls'])==0)
            $data['consumer_dtls']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);
        
        $this->WaterConsumerDemandModel->impose_penalty($data['consumer_dtls']['consumer_id']);
        $data['demand_list']=$this->WaterConsumerDemandModel->due_demand($consumer_id);
        $data["consumer_id"] = $data['consumer_dtls']["id"];
  
        $curent_demand = !empty($data['demand_list'])?array_reverse($data['demand_list'])[0]:[];        
        $priv_2_year = !empty($data['demand_list'])?date('Y-m',strtotime($curent_demand['demand_upto'].'-2 years')):date('Y-m');  
        
        $data['connection_dtls']=$this->meter_status_model->getLastConnectionDetails($data['consumer_dtls']['id']);
        /*
        if(isset($data['connection_dtls']['connection_type'])  && !empty($data['demand_list']) ) //&& $data['connection_dtls']['connection_type']!=3 && date('Y-m',strtotime($curent_demand['demand_upto']))==date('Y-m') 
        {
            $from_month= array_filter($data['demand_list'],function  ($val) use ($priv_2_year){
                if(date('Y-m',strtotime($val['demand_upto']))<=$priv_2_year)
                {                    
                   return $val;
                }
            }); 
            $temp = array_filter($data['demand_list'],function  ($val) use ($priv_2_year){
                
                    if(date('Y-m',strtotime($val['demand_upto']))==$priv_2_year)
                    {
                        return $val;                       
                    }
            });            
            if(!empty($from_month))
            {
                if(empty($temp))
                {
                    $from_month[]=$curent_demand;
                }
                $data['from_month'] = $from_month;
            }
            
        }
        */
        $limit2000 = 0;
        $args =array();
        $Session=Session();
        $emp=$Session->get('emp_details');
        foreach(array_reverse($data['demand_list']) as $val)
        { 
            // if($data['consumer_dtls']["id"]==34890 || $data['consumer_dtls']["id"]==35179 || $data['consumer_dtls']["id"]==35002 || $data['consumer_dtls']["id"]== 35316 || $data['consumer_dtls']["id"]==35005 || $data['consumer_dtls']["id"]==65181 )
            // {
            //     continue;
            // }
            // if($limit2000<2000)
            // {                
            //     $limit2000 += ($val['amount']+$val['penalty']);
                
            // }
            // else
            // {
            //     $args[] = $val;
            // }
            $args[] = $val;
        }
        if(!empty($args))
        {
            $data['from_month'] = $args;
        }
        
        return view('water/water_connection/make_payment', $data);
    }

    public function getAmountPayable()
    {
        $out=["message"=> "something went wrong", "status"=> false];
        $demand_from = null;
        if($this->request->getMethod()=='post')
        {
            $inputs=arrFilterSanitizeString($this->request->getVar());
            if(isset($inputs['demand_from']))
            {
                $demand_from = $inputs['demand_from'];
            }

            $demand=$this->WaterConsumerDemandModel->getAmountPayable($inputs["consumer_id"], $inputs["demand_upto"],$demand_from);
            
            # cheque bounce charge
            $advance = $this->WaterPenaltyModel->getAdvance($inputs["consumer_id"]);            
            $demand["balance"] = $advance['balance']??0;
            $demand["other_penalty"]=$this->WaterPenaltyModel->getUnpaidPenaltySum(md5($inputs["consumer_id"]), 'Consumer');
            $demand["rebate"]=0.00; // Not In Use
            $demand["balance_amount"]+=$demand["other_penalty"];
            $out=["message"=> "", "status"=> true, "data"=> $demand];
        }

        echo json_encode($out);
    }

    public function water_pay_now()
    {
        $out=["message"=> "something went wrong", "status"=> false];
        
        if($this->request->getMethod()=='post')
        {
            $Session=Session();
            $emp=$Session->get('emp_details');
            
            $inputs=arrFilterSanitizeString($this->request->getVar());
            $consumer_id=$inputs["consumer_id"];
            $demand_from=$inputs["demand_from"];
            $demand_upto=$inputs["demand_upto"];
            $ward_mstr_id=$inputs["ward_mstr_id"];
            $payment_mode=$inputs["payment_mode"];
            $payment_from=$inputs["payment_from"];

            $demand=$this->WaterConsumerDemandModel->getAmountPayable($consumer_id, $demand_upto,$demand_from);
            // print_var($demand);die;
            $advance = $this->WaterPenaltyModel->getAdvance($inputs["consumer_id"]);            
            $adjust_amount = 0;
            $payable_amount=null;           
            $total_demand_amount = $demand["balance_amount"]!=0?$demand["balance_amount"]:($demand["amount"]+$demand["penalty"]);
            if($advance)
            {
                $payable_amount = $total_demand_amount - $advance['balance']??0;
                $adjust_amount = $advance['balance']??0;
            }

            if($payable_amount<=0 && $advance)
            {
                $payable_amount = 0;
                $adjust_amount = $total_demand_amount;
            }

            $djust_arr=[];
            $djust_arr['related_id']= $consumer_id ;
            $djust_arr['amount']= round($adjust_amount) ;
            $djust_arr['module']= 'consumer' ;
            $djust_arr['user_id']= $emp['id'] ; 

            $trans_arr=array();
            $trans_arr['ward_mstr_id']=$ward_mstr_id;
            $trans_arr['ip_address']='127.0.0.1';
            $trans_arr['transaction_type']="Demand Collection";
            $trans_arr['transaction_date']=date('Y-m-d');
            $trans_arr['related_id']=$consumer_id;
            $trans_arr['payment_mode']=$payment_mode;
            $trans_arr['penalty']=round($demand["penalty"]);
            $trans_arr['rebate']=0.00;
            // $trans_arr['paid_amount']=$demand["balance_amount"]!=0?$demand["balance_amount"]:($demand["amount"]+$demand["penalty"]);
            $trans_arr['paid_amount']=round($payable_amount);
            $trans_arr['total_amount']=round($demand["amount"]); //demand amount
            $trans_arr['status']=1;
            $trans_arr['emp_details_id']=$emp['id'];
            $trans_arr['created_on']=date('Y-m-d H:i:s');
            $trans_arr['from_month']=$demand_from;
            $trans_arr['upto_month']=$demand['demand_upto']??$demand_upto;
            $trans_arr['payment_from']=$payment_from;
            $trans_arr['remarks']=$inputs["remarks"];

            $other=[];
            $other["other_penalty"]=$this->WaterPenaltyModel->getUnpaidPenaltySum(md5($inputs["consumer_id"]), 'Consumer');
            $other["demand_id"]=$inputs["demand_id"];

            $cheque_dtl=[];
            if(in_array($payment_mode, ["CHEQUE", "DD","NEFT",'RTGS'])){
                $cheque_dtl=[
                    "cheque_no"=> $inputs["cheque_no"],
                    "cheque_date"=> $inputs["cheque_date"],
                    "bank_name"=> $inputs["bank_name"],
                    "branch_name"=> $inputs["branch_name"]
                ];
                $trans_arr['status']=2;
            }
            
            $this->db->transBegin();
            $this->dbSystem->transBegin();
            $transaction_id=$this->Water_Transaction_Model->water_pay_now($trans_arr, $other, $cheque_dtl);

            $consumer_details=$this->consumer_details_model->consumerDetails($consumer_id);
            $vistingRepostInput = waterConsumerTranVisit($consumer_details,$transaction_id,$this->request->getVar());           
            $visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);
            
            if(isset($advance['balance']) && $advance['balance']>0)
            {
                $djust_arr['transaction_id'] = $transaction_id;           
                $this->WaterPenaltyModel->insert_tbl_adjustment_mstr($djust_arr);
            }

            if($this->db->transStatus() === FALSE || is_null($payable_amount))
            {
                $this->db->transRollback();
                $this->dbSystem->transRollback();
                $out=["message"=> "Transaction failed", "status"=> false];
            }
            else
            {
                //$this->db->transRollback();
                $this->db->transCommit();
                $this->dbSystem->transCommit();
                if($payment_from=="JSK")
                $out=["message"=> "Transaction success", "status"=> true, "url"=> base_url('WaterUserChargePayment/payment_tc_receipt/'.md5($consumer_id).'/'.md5($transaction_id)) ];
                else if($payment_from=="TC")
                $out=["message"=> "Transaction success", "status"=> true, "url"=> base_url('WaterUserChargePaymentMobile/payment_tc_receipt/'.md5($consumer_id).'/'.md5($transaction_id)) ];

            }
        }
        echo json_encode($out);
    }



    public function pay_user_charge() 
    {
        $data=array();
        $session=session();
        $get_emp_details=$session->get('emp_details');
        $get_ulb_detail=$session->get('ulb_dtl');
       // print_r($get_emp_details);
        $emp_id=$get_emp_details['id'];
        $user_type_mstr_id=$get_emp_details['user_type_mstr_id'];
        //echo $get_emp_details['ip_address'];
        
        
        

        if($this->request->getMethod()=='post')
        {
            $this->db->transBegin();
            $inputs=arrFilterSanitizeString($this->request->getVar());
            //
            //echo '<pre>';print_r($inputs);echo '</pre>'; die();
            

            $curr_date=date('Y-m-d');
            $payment_mode=$inputs['payment_mode'];
            $amount=$inputs['amount'];
            $penalty=$inputs['penalty'];
            $rebate=$inputs['rebate'];
            $consumer_id=$inputs['consumer_id'];
            $month=$inputs['month'];
            $due_from=$inputs['due_from'];
            $ward_mstr_id=$inputs['ward_mstr_id'];
            $demand_id=$inputs['demand_id'];
            //$penalty_installment_upto_id=$inputs['penalty_installment_upto_id'];
            $penalty_installment_upto_id=isset($inputs['penalty_installment_upto_id'])?$inputs['penalty_installment_upto_id']:0;
            $water_conn_id=$inputs['apply_connection_id'];
            $consumer_no=$inputs['consumer_no'];

            
            $penalty_installment=0;
            $rebate=0;

            
            
            // echo "sss".$penalty_installment_amount.'-'.$installment_rebate;
            // echo $penalty;
            if($penalty_installment_upto_id>0)
            {
                $penalty_installment=$this->penalty_installment_model->getInstallmentId(md5($water_conn_id),$penalty_installment_upto_id);
                $penalty_installment=$penalty_installment['installment_amount'];
            }
            
            
            
            $due_from=$this->WaterConsumerDemandModel->getDueFrom(md5($consumer_id));

            $rules=[
                    'payment_mode'=>'required|alpha',
                    'amount' =>'required|numeric',
                    'month' =>'required',
                 ];

             
              

            if(!$this->validate($rules))
            {

                $data['validation']=$this->validator;              
                return view('water/water_connection/payment_details',$data);

            }
            else
            {
                
                
                $curr_month=date('m');
                if($curr_month==01 or $curr_month==02 or $curr_month==03)
                {
                    $curr_year=date('Y')-1;
                }
                else
                {
                    $curr_year=date('Y');
                }

                $next_year=$curr_year+1;
                $curr_fin_year=$curr_year.'-'.$next_year;
                $start_year=$curr_year.'-04-01';
                $end_year=$next_year.'-03-31';
                $demand_upto=$month;
				$meter_status = $this->meter_status_model->getLastConnectionDetails($consumer_id);
                $fixt_penalty=0;
                $demand = $this->WaterConsumerDemandModel->privDemantfromDate(md5($consumer_id),$demand_upto);
                $total_demane =  $demand['payable_amount']??0;
                if(!empty($meter_status)&& $meter_status['connection_type']==3)
                {
                    $demand = $this->WaterConsumerDemandModel->privDemantfromDate(md5($consumer_id),$demand_upto);
                    $tot_demand =$total_demane;
                    $copair_month = date('Y-m',strtotime($demand_upto));
                    if(date('Y-m')>$copair_month)
                    {
                        $fixt_penalty = ($tot_demand/100)*10;
                    }
                    
                }
            


                if($demand_upto<$start_year) 
                {
                    $due_from=$this->WaterConsumerDemandModel->getDueFrom(md5($consumer_id));
                    $payment_details=$this->WaterConsumerDemandModel->getAmountPayablePreviousYear(md5($consumer_id),$demand_upto,$start_year,$end_year);
                }
                else
                {   
                    $due_from=$this->WaterConsumerDemandModel->getDueFromCurrentYear(md5($consumer_id),$start_year);
                    $payment_details=$this->WaterConsumerDemandModel->getAmountPayableCurrentYear(md5($consumer_id),$demand_upto,$start_year,$end_year);
                }
            
                $demand_id=$payment_details['demand_id'];
                $where=" and demand_upto<='$demand_upto' ";
                $interest_penalty=$this->WaterConsumerDemandModel->onePointFivePercentPenalty(md5($consumer_id),$where);
				$interest_penalty+= $fixt_penalty;
                # cheque bounce charge
                $other_penalty=$this->WaterPenaltyModel->getUnpaidPenaltySum(md5($consumer_id), 'Consumer');
                $total_penalty=$other_penalty+$interest_penalty+$penalty_installment;
                if($total_penalty != $penalty)
                {
    
                    flashToast("payment", "Penalty amount miss-match!!!");
                    return $this->response->redirect(base_url('WaterUserChargePayment/payment_details/'.md5($consumer_id)));
                }
            
                $status=1;
                if($payment_mode!='CASH')
                {
                    $status=2;
                    $cheque_no=$inputs['cheque_no'];
                    $cheque_dt=$inputs['cheque_date'];
                    $bank_name=$inputs['bank_name'];
                    $branch_name=$inputs['branch_name'];
                }
                $payable_amount=$amount+$total_penalty-$rebate;
                
                $trans_arr=array();
                $trans_arr['ward_mstr_id']=$ward_mstr_id;
                $trans_arr['ip_address']=$get_emp_details['ip_address'];
                $trans_arr['transaction_type']="Demand Collection";
                $trans_arr['transaction_date']=date('Y-m-d');
                $trans_arr['related_id']=$consumer_id;
                $trans_arr['payment_mode']=$payment_mode;
                $trans_arr['penalty']=$other_penalty+$interest_penalty+$penalty_installment;
                $trans_arr['rebate']=$rebate;
                $trans_arr['paid_amount']=round($payable_amount);
                $trans_arr['total_amount']=$amount; //demand amount
                $trans_arr['emp_details_id']=$emp_id;
                $trans_arr['created_on']=date('Y-m-d H:i:s');
                $trans_arr['status']=$status;
                $trans_arr['from_month']=$due_from;
                $trans_arr['upto_month']=$month;
                $trans_arr['payment_from']="JSK";
                
                
            
                //print_r($trans_arr);
                //exit();
                $check_trans_exist=$this->payment_model->check_transaction_exist_consumer($consumer_id,round($payable_amount));
            
            
            
                if($check_trans_exist==0)
                {
                    
                    $transaction_id=$this->payment_model->insert_transaction($trans_arr);
                

                    if($transaction_id)
                    {
                        $trans_no="WTRAN".$transaction_id.date('dmyhis');
                        $this->payment_model->update_trans_no($trans_no, $transaction_id);

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

                        if($penalty_installment>0)
                        {
                            $trans_fine=array();
                            $trans_fine['transaction_id']=$transaction_id;
                            $trans_fine['head_name']="Installment";
                            $trans_fine['amount']=$penalty_installment;
                            $trans_fine['value_add_minus']="+";
                            $trans_fine['created_on']=date('Y-m-d H:i:s');
                            $trans_fine['status']=1;
                            $this->payment_model->insert_fine_rebate($trans_fine);
                        }

                        if($other_penalty>0)
                        {
                            $trans_fine=array();
                            $trans_fine['transaction_id']=$transaction_id;
                            $trans_fine['head_name']="Cheque Bounce Charge";
                            $trans_fine['amount']=$other_penalty;
                            $trans_fine['value_add_minus']="+";
                            $trans_fine['created_on']=date('Y-m-d H:i:s');
                            $trans_fine['status']=1;
                            $this->payment_model->insert_fine_rebate($trans_fine);
                        }
                        if($interest_penalty>0)
                        {
                            $trans_fine=array();
                            $trans_fine['transaction_id']=$transaction_id;
                            $trans_fine['head_name']="1.5% Penalty";
                            $trans_fine['amount']=$interest_penalty;
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
                        $coll=$this->collection_model->insertData($transaction_id,$demand_id,$emp_id,$consumer_id);
                        //print_r($coll);

                    

                        if($penalty_installment_upto_id!="")
                        {
                        
                            $penalty_installment_amount=$this->penalty_installment_model->getPenaltyforRebate(md5($water_conn_id));
                        
                        
                            $get_installment_details=$this->penalty_installment_model->getInstallmentId(md5($water_conn_id),$penalty_installment_upto_id);
                            //print_r($get_installment_details);
                            $penalty_installment_id=$get_installment_details['intallment_id'];
                            $total_count_left=$get_installment_details['count'];
                        
                            //$penalty_installment_amount=$get_installment_details['installment_amount'];
                            $this->penalty_installment_model->updateInstallment($penalty_installment_id,$transaction_id);
                            //echo $penalty_installment_upto_id;

                            $count=$this->penalty_installment_model->countExistsUnpaidInstallmentafterId(md5($water_conn_id),$penalty_installment_upto_id);

                            if($count==0 and $total_count_left>0)
                            {
                                $installment_rebate=(10*$penalty_installment_amount)/100;
                            }
                            else
                            {
                                $installment_rebate=0;
                            }

                            if($penalty_installment_upto_id>0)
                            {   
                            
                            $penalty_installment=$this->penalty_installment_model->getInstallmentDetails(md5($water_conn_id),$penalty_installment_upto_id,$transaction_id);
                            //print_r($penalty_installment);

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

                    
                        
                        if($coll)
                        {

                            $this->WaterConsumerDemandModel->update_demand_status($consumer_id,$demand_id);

                            $getdue=$this->WaterConsumerDemandModel->getDueAmount($consumer_id);
                            $due_amount=$getdue['due_amount'];
                            $this->payment_model->update_due_amount($transaction_id,$due_amount);
                            $min_demand_id=$this->collection_model->getMinDemandId($transaction_id);
                            $max_demand_id=$this->collection_model->getMaxDemandId($transaction_id);
                            $get_demand_from=$this->WaterConsumerDemandModel->getDemandFrom($min_demand_id);
                            $get_demand_upto=$this->WaterConsumerDemandModel->getDemandUpto($max_demand_id);
                            $this->payment_model->updateDemandFromandUpto($transaction_id,$get_demand_from,$get_demand_upto);
                        }
                    }
                

                    if($this->db->transStatus() === FALSE)
                    {
                        $this->db->transRollback();
                        flashToast("payment", "Something errordue to payment!!!");
                        $session=session();
                        $get_emp_details=$session->get('emp_details');
                        $emp_id=$get_emp_details['id'];
                        $user_type_mstr_id=$get_emp_details['user_type_mstr_id'];
                        

                    
                        return $this->response->redirect(base_url('WaterUserChargePayment/payment_details/'.md5($consumer_id)));
                

                    }
                    else
                    {
                        
                        $this->db->transCommit();

                        //echo $water_conn_id.'-'.$transaction_id;
                        $mobile_no=$this->search_consumer_mobile_model->getMobileNo(md5($consumer_id));
                        if($mobile_no!="" || $mobile_no!=0)
                        {
                            $sms="Your Water User Charge Payment of Rs. ".$payable_amount." for Consumer No.  ".$consumer_no." is successfully done. ".$get_ulb_detail['ulb_name'];
                            SMSJHGOVT($mobile_no,$sms);
                            
                            //exit();
                        }
                    

                        return $this->response->redirect(base_url('WaterUserChargePayment/payment_tc_receipt/'.md5($consumer_id).'/'.md5($transaction_id)));
                    }
                


                }
                else
                {
                    flashToast("payment", "Transaction Already Done!!!");
                    return $this->response->redirect(base_url('WaterUserChargePayment/payment_details/'.md5($consumer_id)));
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
        //echo($consumer_id);
        $path=base_url('citizenPaymentReceipt/view_transaction_receipt/'.$ulb_mstr_id.'/'.$consumer_id.'/'.$transaction_id);
        $data['ss']=qrCodeGeneratorFun($path);
        $data['transaction_id']=$transaction_id;
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $data['consumer_id']=$consumer_id;
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $data['consumer_details']=$this->consumer_details_model->consumerDetails($consumer_id);
        $data['consumer_owner_details']=$this->consumer_owner_details->getConsumerDetailsbyMd5($consumer_id);
        $data['transaction_details']=$this->payment_model->transaction_details($transaction_id);
        $data['emp_dtls'] = $this->modelemp->emp_dtls($data['transaction_details']['emp_details_id']);
        $data['applicant_details']='';
        $data['meter_reading']=$this->payment_model->meter_reding_for_recipt($data['transaction_details']['id']);
        $data['adjustment_amount'] =  $this->WaterPenaltyModel->get_tbl_adjustment_mstr($data['consumer_details']['id'],$data['transaction_details']['id']);
        $data['advance_amount'] =  $this->WaterPenaltyModel->get_tbl_advance_mstr($data['consumer_details']['id'],$data['transaction_details']['id']);
        if(count($data['consumer_details'])>0)
        {   
            if(!empty($data['consumer_details']['apply_connection_id']))
            {
                    $where = array('id'=>$data['consumer_details']['apply_connection_id'],
                                'status!='=>0
                                );
                $data['applicant_details'] = $this->apply_waterconn_model->getDataNew($where,array('*'),'view_water_application_details');
            }
            else
            {
                $where = array('id'=>$data['consumer_details']['id'],
                                'status!='=>0
                                );
                $data['applicant_details'] = $this->apply_waterconn_model->getDataNew($where,array('*'),'tbl_consumer');
            }
        }
        //echo"<pre>";print_r($data);echo"</pre>";
        return view('water/water_connection/user_charge_payment_receipt',$data);
    }

   
   



}
