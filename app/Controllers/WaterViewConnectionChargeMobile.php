<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterViewConnectionFeeModel;
use App\Models\WaterConnectionDetailsViewModel;
use App\Models\WaterApplyNewConnectionModel;
use App\Controllers\WaterApplyNewConnection;


class WaterViewConnectionChargeMobile extends MobiController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
    protected $emp_id;
    protected $user_type;
    //protected $db_name;
    
    
    public function __construct()
    {   

        $session=session();
        $get_emp_details=$session->get('emp_details');
        $this->emp_id=$get_emp_details['id'];
        $this->user_type=$get_emp_details['user_type_mstr_id'];

        parent::__construct();
        helper(['db_helper', 'form']);
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
       // print_r($this->apply_conn);

    }
    
    public function index($water_conn_id)
    {

        $data=array();
        helper(['form']);
        $session=session();
        $get_ulb_id=$session->get('ulb_dtl');
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];
        $user_type=$get_emp_details['user_type_mstr_id'];
        

           // $water_conn_id=$inputs['water_conn_id'];

            $this->conn_fee->conn_fee_charge($water_conn_id);
            

            return view('mobile/water/water_connection_fee_view',$data);
      
            
    }
    public function fee_charge($water_conn_id)
    {
       
        $data=array();
        $data['user_type']=$this->user_type;

        $data['consumer_details']=$this->apply_waterconn_model->water_conn_details($water_conn_id);
        //print_r($data);
        $data['water_conn_id']=$water_conn_id;
        
        $water_conn_details= $this->conn_fee->fetch_water_con_details($water_conn_id);
        //print_r($get_rate_id);

         $rate_id=$water_conn_details['water_fee_mstr_id'];

         $data['application_no']=$water_conn_details['application_no'];

         $data['conn_fee_charge']=$this->conn_fee->conn_fee_charge($water_conn_id);
        // print_r($data);

        
        
         $data['application_status']=$this->apply_conn->application_status($water_conn_id);
         
        // print_r($data['conn_fee_charge_details']);

         return view('mobile/water/water_connection_fee_view',$data);
            
    }
   
  
    
}
