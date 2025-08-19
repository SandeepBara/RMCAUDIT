<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterViewConnectionFeeModel;
use App\Models\WaterConnectionDetailsViewModel;
use App\Models\WaterApplyNewConnectionModel;
use App\Controllers\WaterApplyNewConnection;
use App\Models\WaterPenaltyModel;

class WaterViewConnectionCharge extends AlphaController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
    protected $emp_id;
    protected $user_type;
    //protected $db_name;
    protected $conn_fee;
    protected $water_conn_dtls;
    protected $apply_waterconn_model;
    protected $apply_conn;
    protected $WaterPenaltyModel;
    
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
        $this->WaterPenaltyModel=new WaterPenaltyModel($this->db);
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
            

            return view('water/water_connection/water_connection_fee_view',$data);
      

    }
    
    public function fee_charge($water_conn_id)
    {
        $data=array();
        $data['consumer_details']=$this->apply_waterconn_model->water_conn_details($water_conn_id);
        //print_var($data["consumer_details"]);
        $data['owner_details']=$this->apply_waterconn_model->water_owner_details($water_conn_id);
        


        $data['conn_fee_charge']=$this->conn_fee->conn_fee_charge($water_conn_id);

        # cheque bounce penalty
        $data['other_penalty']=$this->WaterPenaltyModel->getUnpaidPenaltySum($water_conn_id);
        $data['conn_fee_charge']["penalty"] += $data['other_penalty'];
        $data['conn_fee_charge']["amount"] += $data['other_penalty'];

        $data['user_type']=$this->user_type;
        $data['water_conn_id']=$water_conn_id;
        return view('water/water_connection/water_connection_fee_view',$data);
            
    }
   
  
    
}
