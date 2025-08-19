<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterFerruleTypeModel;
use App\Models\WaterPropertyModel;



class WaterFerruleTypeMaster extends AlphaController
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
        
        $this->ferrule_type_model=new WaterFerruleTypeModel($this->db);
        $this->property_model=new WaterPropertyModel($this->db);
        
    }
    
    public function index()
    {
        $data['ferrule_type_list']=$this->ferrule_type_model->ferrule_type_list();

        return view('water/master/FerruleType_list',$data);
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

                   
                    'ferrule_type'=>'required',
                    'from_area'=>'required',
                    'upto_area'=>'required',
                  
                   
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/ferrule_type_master',$data);
                }
                else
                {

                   
                    //store the data
                
                 
                    $data['from_area']= $this->request->getVar('from_area');
                    $data['upto_area']= $this->request->getVar('upto_area');
                    $data['ferrule_type']= $this->request->getVar('ferrule_type');
                   

                     $data_exist=$this->ferrule_type_model->checkdata($data);
                  
                     if($data_exist)
                     {
                        
                        echo "<script>alert('Data Already Exists');</script>";
                       return view('water/master/ferrule_type_master',$data);
                     }
                    else{

                       
                       
                        $ferrule_type=array();
                      
                        $ferrule_type['ferrule_type']=$data['ferrule_type'];
                        $ferrule_type['from_area']=$data['from_area'];
                        $ferrule_type['upto_area']=$data['upto_area'];
                        $ferrule_type['emp_details_id']=$session_user_id;
                        $ferrule_type['created_on']=date('Y-m-d H:i:s');
                        
                  

                        if($insert_last_id = $this->ferrule_type_model->insertData($ferrule_type)){
                           return $this->response->redirect(base_url('WaterFerruleTypeMaster'));
                        }
                        else{
                       return $this->response->redirect(base_url('WaterFerruleTypeMaster'));
                        }

                    }
                }

            }
            else
            {


                 $rules=[

                  
                    
                    'ferrule_type'=>'required',
                    'from_area'=>'required',
                    'upto_area'=>'required',
                    
                   
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/ferrule_type_master',$data);
                }
                else
                {


                    $data['from_area']= $this->request->getVar('from_area');
                    $data['upto_area']= $this->request->getVar('upto_area');
                    $data['ferrule_type']= $this->request->getVar('ferrule_type');
                   
                    $data['id']= $this->request->getVar('id');
                    
                    
                   
                    $id=$this->request->getVar('id');

                    $data_exist=$this->ferrule_type_model->checkdata($data);
                  
                    if($data_exist)
                    {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('water/master/pipeline_master',$data);
                     }
                    else{

                        $ferrule_type=array();
                    
                        $ferrule_type['from_area']=$data['from_area'];
                        $ferrule_type['upto_area']=$data['upto_area'];
                        $ferrule_type['ferrule_type']=$data['ferrule_type'];
                        $ferrule_type['emp_details_id']=$session_user_id;
                        $ferrule_type['created_on']=date('Y-m-d H:i:s');
                        $ferrule_type['id']=$data['id'];

                        if($insert_last_id = $this->ferrule_type_model->updateData($ferrule_type)){
                          
                            return $this->response->redirect(base_url('WaterFerruleTypeMaster'));
                        }
                        else{

                         return $this->response->redirect(base_url('WaterFerruleTypeMaster'));
                        }

                    }
                }

            }
        }
        else if(isset($id))
        {
           
            $getData=$this->ferrule_type_model->getData($id);
            //print_r($getData);
          
            $data['from_area']=$getData['from_area'];
            $data['upto_area']=$getData['upto_area'];
            $data['ferrule_type']=$getData['ferrule_type'];
            $data['id']=$getData['id'];
            return view('water/master/ferrule_type_master',$data);
        }  
        else
        {   

            return view('water/master/ferrule_type_master',$data);
        }
    }

    public function delete($id)
    {
        $this->ferrule_type_model->deleteData($id);
        return $this->response->redirect(base_url('WaterFerruleTypeMaster'));
    }
    
    
}
