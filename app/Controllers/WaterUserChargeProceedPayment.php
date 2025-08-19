<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_water_consumer;
use App\Models\model_ward_mstr;
use App\Models\water_consumer_details_model;
use App\Models\WaterConsumerDemandModel;
use App\Models\Water_Transaction_Model;
use App\Models\WaterSearchConsumerMobileModel;
use App\Models\WaterPaymentModel;



class WaterUserChargeProceedPayment extends AlphaController
{
    protected $water;
    // protected $db;
    protected $dbSystem;
    protected $model_view_water_consumer;
    protected $model_ward_mstr;
    protected $ulb_id;
    protected $emp_id;
    

    public function __construct(){
        
        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];


        parent::__construct();
        helper(['db_helper','form']);
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

    }
    
    public function pay_payment($consumer_id=null)
    {
        
        $data=array();
       

        $data['due_details']=$this->consumer_demand_model->due_demand($consumer_id);

        $data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);

        // print_r($data['consumer_detailss']);

        $data['consumer_owner_details']=$this->consumer_details_model->consumerDetailsbyMd5($consumer_id);
        
        
        $data['due_from']=$this->consumer_demand_model->getDueFrom($consumer_id);
        
        $penalty_details=$this->payment_model->get_penalty_details($water_conn_id);
        // echo ($data['penalty_details']['penalty']);

         $data['penalty']=$penalty_details['penalty'];


         $rebate_details=$this->payment_model->get_rebate_details($water_conn_id);

        // echo $data['rebate_details']['rebate'];
         $data['rebate']=$rebate_details['rebate'];

        
        return view('water/water_connection/water_user_charge_proceed_payment',$data);

        
    }

    

}
?>
