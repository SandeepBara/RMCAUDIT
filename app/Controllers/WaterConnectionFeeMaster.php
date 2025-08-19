<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterConnectionFeeModel;
use App\Models\WaterPropertyModel;
use App\Models\WaterPipelineModel;
use App\Models\WaterConnectionTypeModel;
use App\Models\WaterConnectionThroughModel;



class WaterConnectionFeeMaster extends AlphaController
{
    protected $db;
    protected $dbSystem;
    
    //protected $db_name;
    
    
    public function __construct()
    {
        parent::__construct();
        helper(['db_helper']);
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
        
        helper(['form']);
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->conn_fee_model=new WaterConnectionFeeModel($this->db);
        $this->property_model=new WaterPropertyModel($this->db);
        $this->pipeline_model=new WaterPipelineModel($this->db);
        $this->conn_type_model=new WaterConnectionTypeModel($this->db);
        $this->conn_through_model=new WaterConnectionThroughModel($this->db);
        
    }
    
    public function index()
    {
        $data['conn_fee_list']=$this->conn_fee_model->conn_fee_list();
        //print_r($data['conn_fee_list']);
        return view('water/master/connection_fee_list',$data);
    }
    
    public function create($id=null)
    {


        $data =(array)null;
        helper(['form']);
        $session=session();
        $emp_details=$session->get('emp_details');
        $session_user_id=$emp_details['id'];
        $data['property_type_list']=$this->property_model->property_list();
        $data['pipeline_list']=$this->pipeline_model->pipeline_list();
        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['curr_date']=date('Y-m-d');

        if($this->request->getMethod()=='post')
        {
            if($this->request->getVar('id')=="")
            {

                    $data['connection_through_id']= $this->request->getVar('connection_through_id');
                    $data['property_type_id']= $this->request->getVar('property_type_id');
                    $data['connection_type_id']= $this->request->getVar('connection_type_id');
                    $data['pipeline_type_id']= $this->request->getVar('pipeline_type_id');
                    $data['category']= $this->request->getVar('category');
                    $data['proc_fee']= $this->request->getVar('proc_fee');
                    $data['sec_fee']= $this->request->getVar('sec_fee');
                    $data['app_fee']= $this->request->getVar('app_fee');
                    $data['reg_fee']= $this->request->getVar('reg_fee');
                    $data['conn_fee']= $this->request->getVar('conn_fee');
                    $data['effect_date']= $this->request->getVar('effect_date');


                $rules=[

                    'connection_through_id'=>'required',
                    'property_type_id'=>'required',
                    'connection_type_id'=>'required',
                    'pipeline_type_id'=>'required',
                    'category'=>'required',
                    'proc_fee'=>'required',
                    'sec_fee'=>'required',
                    'app_fee'=>'required',
                    'reg_fee'=>'required',
                    'conn_fee'=>'required',
                    'effect_date'=>'required',
                   
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/connection_fee_master',$data);
                }
                else
                {
                      //store the data
                
                    
                    $data_exist=$this->conn_fee_model->checkdata($data);
                  
                     if($data_exist)
                     {
                        
                        echo "<script>alert('Data Already Exists');</script>";
                       return view('water/master/connection_fee_master',$data);

                     }
                    else
                    {

                       
                        $conn_fee_mstr=array();
                        $conn_fee_mstr['connection_through_id']=$data['connection_through_id'];
                        $conn_fee_mstr['property_type_id']=$data['property_type_id'];
                        $conn_fee_mstr['connection_type_id']=$data['connection_type_id'];
                        $conn_fee_mstr['pipeline_type_id']=$data['pipeline_type_id'];
                        $conn_fee_mstr['category']=$data['category'];
                        $conn_fee_mstr['proc_fee']=$data['proc_fee'];
                        $conn_fee_mstr['sec_fee']=$data['sec_fee'];
                        $conn_fee_mstr['app_fee']=$data['app_fee'];
                        $conn_fee_mstr['reg_fee']=$data['reg_fee'];
                        $conn_fee_mstr['conn_fee']=$data['conn_fee'];
                        $conn_fee_mstr['effect_date']=$data['effect_date'];
                        
                        
                        if($insert_last_id = $this->conn_fee_model->insertData($conn_fee_mstr))
                        {
                           return $this->response->redirect(base_url('WaterConnectionFeeMaster'));
                        }
                        else{
                       return $this->response->redirect(base_url('WaterConnectionFeeMaster'));
                        }

                    }
                }

            }
            else
            {


                $rules=[

                    'connection_through_id'=>'required',
                    'property_type_id'=>'required',
                    'connection_type_id'=>'required',
                    'pipeline_type_id'=>'required',
                    'category'=>'required',
                    'proc_fee'=>'required',
                    'sec_fee'=>'required',
                    'app_fee'=>'required',
                    'reg_fee'=>'required',
                    'conn_fee'=>'required',
                    'effect_date'=>'required',
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/connection_fee_master',$data);
                }
                else
                {


                    //store the data
                    $data['connection_through_id']= $this->request->getVar('connection_through_id');
                    $data['property_type_id']= $this->request->getVar('property_type_id');
                    $data['connection_type_id']= $this->request->getVar('connection_type_id');
                    $data['pipeline_type_id']= $this->request->getVar('pipeline_type_id');
                    $data['category']= $this->request->getVar('category');
                    $data['proc_fee']= $this->request->getVar('proc_fee');
                    $data['sec_fee']= $this->request->getVar('sec_fee');
                    $data['app_fee']= $this->request->getVar('app_fee');
                    $data['reg_fee']= $this->request->getVar('reg_fee');
                    $data['conn_fee']= $this->request->getVar('conn_fee');
                    $data['effect_date']= $this->request->getVar('effect_date');
                    
                    
                    $data['id']=$this->request->getVar('id');

                    $data_exist=$this->conn_fee_model->checkdata($data);
                   
                    if($data_exist)
                    {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('water/master/connection_fee_master',$data);
                    }
                    else{

                        
                        $conn_fee_mstr=array();
                        $conn_fee_mstr['connection_through_id']=$data['connection_through_id'];
                        $conn_fee_mstr['property_type_id']=$data['property_type_id'];
                        $conn_fee_mstr['connection_type_id']=$data['connection_type_id'];
                        $conn_fee_mstr['pipeline_type_id']=$data['pipeline_type_id'];
                        $conn_fee_mstr['category']=$data['category'];
                        $conn_fee_mstr['proc_fee']=$data['proc_fee'];
                        $conn_fee_mstr['sec_fee']=$data['sec_fee'];
                        $conn_fee_mstr['app_fee']=$data['app_fee'];
                        $conn_fee_mstr['reg_fee']=$data['reg_fee'];
                        $conn_fee_mstr['conn_fee']=$data['conn_fee'];
                        $conn_fee_mstr['effect_date']=$data['effect_date'];
                        $conn_fee_mstr['id']=$data['id'];
                        
                        
                        if($insert_last_id = $this->conn_fee_model->updateData($conn_fee_mstr)){
                          
                            return $this->response->redirect(base_url('WaterConnectionFeeMaster'));
                        }
                        else{

                         return $this->response->redirect(base_url('WaterConnectionFeeMaster'));
                        }

                    }
                }

            }
        }
        else if(isset($id))
        {
           
            $getData=$this->conn_fee_model->getData($id);
            //print_r($getData);
            $data['pipeline_type_id']=$getData['pipeline_type_id'];
            $data['property_type_id']=$getData['property_type_id'];
            $data['connection_type_id']=$getData['connection_type_id'];
            $data['connection_through_id']=$getData['connection_through_id'];
            $data['category']=$getData['category'];
            $data['proc_fee']=$getData['proc_fee'];
            $data['sec_fee']=$getData['sec_fee'];
            $data['reg_fee']=$getData['reg_fee'];
            $data['app_fee']=$getData['app_fee'];
            $data['conn_fee']=$getData['conn_fee'];
            $data['effect_date']=$getData['effect_date'];
           
            
            $data['id']=$getData['id'];
            return view('water/master/connection_fee_master',$data);
        }  
        else
        {   

           
            //print_r($data['property_type']);
            return view('water/master/connection_fee_master',$data);
        }
    }

    public function delete($id)
    {
        $this->conn_fee_model->deleteData($id);
        return $this->response->redirect(base_url('WaterConnectionFeeMaster'));
    }
    
    
}
