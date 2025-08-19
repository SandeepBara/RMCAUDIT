<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\WaterPropertyModel;
use App\Models\WaterConnectionThroughModel;
use App\Models\WaterConnectionTypeModel;
use App\Models\WaterPipelineModel;
use App\Models\model_ward_mstr;
use App\Models\PropertyModel;



class WaterProceedPayment extends AlphaController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
    
    //protected $db_name;
    
    
    public function __construct()
    {
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
        
        $this->apply_wtrconn_model=new WaterApplyNewConnectionModel($this->db);
        $this->water_property_model=new WaterPropertyModel($this->db);
        $this->conn_through_model=new WaterConnectionThroughModel($this->db);
        $this->conn_type_model=new WaterConnectionTypeModel($this->db);
        $this->pipeline_model=new WaterPipelineModel($this->db);
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->property_model=new PropertyModel($this->property_db);
    }
    
    public function index()
    {

        $data=array();
        helper(['form']);
        $session=session();
        $get_ulb_id=$session->get('ulb_dtl');
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];

        if($this->request->getMethod()=='post')
        {

            $inputs=arrFilterSanitizeString($this->request->getVar());
            //print_r($inputs);
            $water_conn_id=$inputs['water_conn_id'];


            if($this->request->getVar('submit')=="update_application")
            {
                echo "block1";
            }
            else if($this->request->getVar('submit')=="upload_documents")
            {
                echo "block2";
            }
            else if($this->request->getVar('submit')=="proceed_payment")
            {
                echo "block3";
            }
            else if($this->request->getVar('submit')=="view_connection_fee")
            {
                echo "block4";
            }
         
        }
        
        else
        {   

             return view('water/water_connection/applywaterconnection',$data);
        }


    }

  
    
}
