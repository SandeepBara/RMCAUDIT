<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\WaterPropertyModel;
use App\Models\WaterConnectionThroughModel;
use App\Models\WaterConnectionTypeModel;
use App\Models\WaterPipelineModel;
use App\Models\model_ward_mstr;
use App\Models\PropertyModel;
use App\Models\WaterConnectionDetailsViewModel;
use App\Models\StateModel;
use App\Models\DistrictModel;
use App\Models\WaterUpdateApplicationModel;



class WaterUpdateApplicationNew extends AlphaController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
    
    //protected $db_name;
    protected $apply_wtrconn_model;
    protected $water_property_model;
    protected $conn_through_model;
    protected $conn_type_model;
    protected $pipeline_model;
    protected $ward_model;
    protected $property_model;
    protected $conn_view_model;
    protected $state_model;
    protected $district_model;
    protected $update_water_app_model;
    
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
        $this->conn_view_model=new WaterConnectionDetailsViewModel($this->db);
        $this->state_model=new StateModel($this->dbSystem);
        $this->district_model=new DistrictModel($this->dbSystem);
        $this->update_water_app_model=new WaterUpdateApplicationModel($this->db);

    }
    
    public function index($water_conn_id)
    {
        $data=array();
        $session=session();
        $get_ulb_id=$session->get('ulb_dtl');
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];

        $data['property_type_list']=$this->water_property_model->property_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
        $data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
        $data['ward_list']=$this->ward_model->getWardList($get_ulb_id);

        $data['state_list']=$this->state_model->getstateList();
        //echo $water_conn_id;
        $data['water_conn_id']=$water_conn_id;
        $data['connection_dtls']=$this->conn_view_model->fetch_water_con_details($water_conn_id);
        //print_var($data['connection_dtls']);
        $data['owner_details']=$this->apply_wtrconn_model->water_owner_details($water_conn_id);
        return view('water/water_connection/update_application', $data);
    }



    public function delete_owner()
    {
        if($this->request->getMethod()=='post')
        {
           $owner_id=$this->request->getVar('owner_id');
           $water_conn_id=$this->request->getVar('water_conn_id');
           
           if($owner_id!="")
           {

                $count_owner=$this->update_water_app_model->count_owner($water_conn_id);

                if($count_owner>1)
                {
                    $run=$this->update_water_app_model->del_owner($owner_id);
                    if($run)
                    {
                        $response=['response'=>true];

                    }
                }
                else
                {
                    $response=['response'=>false];
                }
                

               return json_encode($response);

           }
        }
    }
    
}
