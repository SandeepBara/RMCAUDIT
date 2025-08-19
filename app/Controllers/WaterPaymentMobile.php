<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterSearchConsumerMobileModel;
use App\Models\WaterPaymentModel;
use App\Models\WaterPenaltyModel;
use App\Models\WaterMeterStatusModel;

class WaterPaymentMobile extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_view_water_consumer;
    protected $model_ward_mstr;
    protected $emp_id;
    protected $demand_model;
    protected $search_consumer_mobile_model;
    protected $payment_model;
    protected $WaterPenaltyModel;
    protected $meter_status_model;

    public function __construct()
    {	

        $session=session();
        $emp_details_id=$session->get('emp_details');
        $this->emp_id=$emp_details_id['id'];

        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("water")){
            $this->db = db_connect($db_name);   

        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        
        $this->demand_model = new WaterConsumerDemandModel($this->db);
        $this->search_consumer_mobile_model=new WaterSearchConsumerMobileModel($this->db);
        $this->payment_model=new WaterPaymentModel($this->db);
        $this->WaterPenaltyModel=new WaterPenaltyModel($this->db);
		$this->meter_status_model = new WaterMeterStatusModel($this->db);
    }
    
    public function pay_payment($consumer_id)
    {
        
        $data=array();
        $data['consumer_dtls']=$this->search_consumer_mobile_model->getConsumerDetailsbyId($consumer_id);

        $data['due_details']=$this->demand_model->due_demand($consumer_id);
        $data['due_from']=$this->demand_model->getDueFrom($consumer_id);
        
        $penalty_details=$this->payment_model->get_penalty_details($water_conn_id);
        // echo ($data['penalty_details']['penalty']);

         $data['penalty']=$penalty_details['penalty'];


         $rebate_details=$this->payment_model->get_rebate_details($water_conn_id);

        // echo $data['rebate_details']['rebate'];
         $data['rebate']=$rebate_details['rebate'];
         
            

        return view("mobile/water/payment_details", $data);
        
    }
    
    function get_amount()
    {
        if($this->request->getMethod()=='post')
        {
            $demand_upto=$this->request->getVar("demand_upto");
            $consumer_id=$this->request->getVar("consumer_id");
			$meter_status = $this->meter_status_model->getLastConnectionDetails($consumer_id);

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
			
			$fixt_penalty=0;
            $demand = $this->demand_model->privDemantfromDate(md5($consumer_id),$demand_upto);
            $total_demane =  $demand['payable_amount']??0;
            if(!empty($meter_status)&& $meter_status['connection_type']==3)
            {
                $demand = $this->demand_model->privDemantfromDate(md5($consumer_id),$demand_upto);
                $tot_demand =$total_demane;
                $copair_month = date('Y-m',strtotime($demand_upto));
                if(date('Y-m')>$copair_month)
                {
                    $fixt_penalty = ($tot_demand/100)*10;
                }
                
            }

            if($demand_upto<$start_year) 
            {
               
                $payment_details=$this->demand_model->getAmountPayablePreviousYear(md5($consumer_id),$demand_upto,$start_year,$end_year);
            }
            else
            {
              
                $payment_details=$this->demand_model->getAmountPayableCurrentYear(md5($consumer_id),$demand_upto,$start_year,$end_year);
            }
            $where=" and demand_upto<='$demand_upto'";
            $interest_penalty=$this->demand_model->onePointFivePercentPenalty(md5($consumer_id),$where);
			$interest_penalty+= $fixt_penalty;

            # cheque bounce charge
            $interest_penalty+=$this->WaterPenaltyModel->getUnpaidPenaltySum(md5($consumer_id), 'Consumer');



            if($payment_details)
            {
                $response=["response"=>true,"amount"=>$payment_details['payable_amount'],"demand_id"=>$payment_details['demand_id'],"interest_penalty"=>$interest_penalty];
            }
            else
            {
                $response=["response"=>false];
            }
			
			//print_r($response);

            return json_encode($response);
        }
    }


}
?>
