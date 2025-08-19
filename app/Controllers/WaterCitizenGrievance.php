<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\GrievanceModel;
use App\Models\PropertyModel;
use App\Models\model_saf_dtl;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\model_water_consumer;



class WaterCitizenGrievance extends AlphaController
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
        if($db_name = dbConfig("property"))
        {
        	$this->property = db_connect($db_name);
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
         /*if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }*/
        
        $this->ward_model = new model_ward_mstr($this->dbSystem);
        $this->grievance_model=new GrievanceModel($this->dbSystem);
        $this->property_model=new PropertyModel($this->property);
        $this->saf_model=new model_saf_dtl($this->property);
        $this->water_conn_model=new WaterApplyNewConnectionModel($this->water);
        $this->consumer_model=new model_water_consumer($this->water);
    }
    
    public function index()
    {   
        
        $data=array();
        $data['property_type_list']=$this->water_property_model->property_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);

        if($this->request->getMethod()=='post')
        {	
        	$inputs=arrFilterSanitizeString($this->request->getVar());
        

        	

        }

        return view('water/water_connection/add_existing_consumer',$data);
     	
    }
  	
	
	
    
}
?>
