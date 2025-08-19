<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterRoadAppartmentFeeModel;
use App\Models\WaterPropertyModel;



class WaterRoadAppartmetFeeMaster extends AlphaController
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
        
        $this->road_app_fee_model=new WaterRoadAppartmentFeeModel($this->db);
        $this->property_model=new WaterPropertyModel($this->db);
        
    }
    
    public function index()
    {
        $data['road_app_fee_list']=$this->road_app_fee_model->road_app_fee_list();

        return view('water/master/RoadAppartmentFee_list',$data);
    }
    
    public function create($id=null)
    {


        $data =(array)null;
        helper(['form']);
        $session=session();
        $emp_details=$session->get('emp_details');
        $session_user_id=$emp_details['id'];
   
        if($this->request->getMethod()=='post')
        {
            if($this->request->getVar('id')=="")
            {


                $rules=[

                   
                    'road_fee'=>'required',
                    'appartment_fee'=>'required',
                    'effect_date'=>'required',
                  
                   
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/RoadAppartmentFee_master',$data);
                }
                else
                {

                   
                    //store the data
                
                 
                    $data['road_fee']= $this->request->getVar('road_fee');
                    $data['appartment_fee']= $this->request->getVar('appartment_fee');
                    $data['effect_date']= $this->request->getVar('effect_date');
                   

                     $data_exist=$this->road_app_fee_model->checkdata($data);
                  
                     if($data_exist)
                     {
                        
                        echo "<script>alert('Data Already Exists');</script>";
                       return view('water/master/RoadAppartmentFee_master',$data);
                     }
                    else{

                       
                       
                        $road_app_fee_mstr=array();
                      
                        $road_app_fee_mstr['road_fee']=$data['road_fee'];
                        $road_app_fee_mstr['appartment_fee']=$data['appartment_fee'];
                        $road_app_fee_mstr['effect_date']=$data['effect_date'];
                        $road_app_fee_mstr['emp_details_id']=$session_user_id;
                        $road_app_fee_mstr['created_on']=date('Y-m-d H:i:s');
                        
                  

                        if($insert_last_id = $this->road_app_fee_model->insertData($road_app_fee_mstr)){
                           return $this->response->redirect(base_url('WaterRoadAppartmetFeeMaster'));
                        }
                        else{
                       return $this->response->redirect(base_url('WaterRoadAppartmetFeeMaster'));
                        }

                    }
                }

            }
            else
            {


                 $rules=[

                  
                    
                    'road_fee'=>'required',
                    'appartment_fee'=>'required',
                    'effect_date'=>'required',
                  
                    
                   
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/RoadAppartmentFee_master',$data);
                }
                else
                {


                    $data['road_fee']= $this->request->getVar('road_fee');
                    $data['appartment_fee']= $this->request->getVar('appartment_fee');
                    $data['effect_date']= $this->request->getVar('effect_date');
                   
                    $data['id']= $this->request->getVar('id');
                    
                    
                   
                    $id=$this->request->getVar('id');

                    $data_exist=$this->road_app_fee_model->checkdata($data);
                  
                    if($data_exist)
                    {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('water/master/pipeline_master',$data);
                     }
                    else{

                        $road_app_fee_mstr=array();
                    
                        $road_app_fee_mstr['road_fee']=$data['road_fee'];
                        $road_app_fee_mstr['appartment_fee']=$data['appartment_fee'];
                        $road_app_fee_mstr['effect_date']=$data['effect_date'];
                        $road_app_fee_mstr['emp_details_id']=$session_user_id;
                        $road_app_fee_mstr['created_on']=date('Y-m-d H:i:s');
                        $road_app_fee_mstr['id']=$data['id'];

                        if($insert_last_id = $this->road_app_fee_model->updateData($road_app_fee_mstr)){
                          
                            return $this->response->redirect(base_url('WaterRoadAppartmetFeeMaster'));
                        }
                        else{

                         return $this->response->redirect(base_url('WaterRoadAppartmetFeeMaster'));
                        }

                    }
                }

            }
        }
        else if(isset($id))
        {
           
            $getData=$this->road_app_fee_model->getData($id);
            //print_r($getData);
          
            $data['road_fee']=$getData['road_fee'];
            $data['appartment_fee']=$getData['appartment_fee'];
            $data['effect_date']=$getData['effect_date'];
            $data['id']=$getData['id'];
            return view('water/master/RoadAppartmentFee_master',$data);
        }  
        else
        {   

            return view('water/master/RoadAppartmentFee_master',$data);
        }
    }

    public function delete($id)
    {
        $this->road_app_fee_model->deleteData($id);
        return $this->response->redirect(base_url('WaterRoadAppartmetFeeMaster'));
    }
    
    
}
