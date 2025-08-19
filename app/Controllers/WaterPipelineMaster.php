<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterPipelineModel;


class WaterPipelineMaster extends AlphaController
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
        
        $this->pipeline_model=new WaterPipelineModel($this->db);

        
    }
    
    public function index()
    {
        $data['pipeline_list']=$this->pipeline_model->pipeline_list();

        return view('water/master/pipeline_list',$data);
    }
    
    public function create($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post')
        {
            if($this->request->getVar('id')=="")
            {


                $rules=[

                    'pipeline_type'=>'required',
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/pipeline_master',$data);
                }
                else
                {


                    //store the data
                    $data = [
                        'pipeline_type' => $this->request->getVar('pipeline_type'),
                      
                         
                       
                    ];
                    $data['pipeline_type'] = $this->request->getVar('pipeline_type');
                  

                     $data_exist=$this->pipeline_model->checkdata($data);
                  
                    if($data_exist)
                        {

                        echo "<script>alert('Data Already Exists');</script>";
                        return view('WaterPipelineMaster',$data);
                     }
                    else{

                       
                        $pipeline_mstr=array();
                        $pipeline_mstr['pipeline_type']=$data['pipeline_type'];
                     
                        if($insert_last_id = $this->pipeline_model->insertData($pipeline_mstr)){
                           return $this->response->redirect(base_url('WaterPipelineMaster'));
                        }
                        else{
                        return $this->response->redirect(base_url('WaterPipelineMaster'));
                        }

                    }
                }

            }
            else
            {


                $rules=[

                    'pipeline_type'=>'required',
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/pipeline_master',$data);

                }
                else
                {


                    //store the data
                    $data = [
                        'pipeline_type' => $this->request->getVar('pipeline_type'),
                        
                         
                       
                    ];
                    $data['pipeline_type'] = $this->request->getVar('pipeline_type');
                 
                    $data['id']=$this->request->getVar('id');

                    $data_exist=$this->pipeline_model->checkdata($data);
                  
                    if($data_exist)
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('water/master/pipeline_master',$data);
                     }
                    else{

                       
                        $pipeline_mstr=array();
                        $pipeline_mstr['pipeline_type']=$data['pipeline_type'];
                        $pipeline_mstr['id']=$data['id'];
                       

                        if($insert_last_id = $this->pipeline_model->updateData($pipeline_mstr)){
                          
                            return $this->response->redirect(base_url('WaterPipelineMaster'));
                        }
                        else{

                         return $this->response->redirect(base_url('WaterPipelineMaster'));
                        }

                    }
                }

            }
        }
        else if(isset($id))
        {
           
            $getData=$this->pipeline_model->getData($id);
            //print_r($getData);
            $data['pipeline_type']=$getData['pipeline_type'];
         
            $data['id']=$getData['id'];
            return view('water/master/pipeline_master',$data);
        }  
        else
        {   

            return view('water/master/pipeline_master');
        }
    }

    public function delete($id)
    {
        $this->pipeline_model->deleteData($id);
        return $this->response->redirect(base_url('WaterPipelineMaster'));
    }
    
    
}
