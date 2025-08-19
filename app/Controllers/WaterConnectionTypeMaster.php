<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterConnectionTypeModel;


class WaterConnectionTypeMaster extends AlphaController
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
        
        $this->conn_type_model=new WaterConnectionTypeModel($this->db);

        
    }
    
    public function index()
    {
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();

        return view('water/master/connection_type_list',$data);
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

                    'connection_type'=>'required',
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/connection_type_master',$data);
                }
                else
                {


                    //store the data
                    $data = [
                        'connection_type' => $this->request->getVar('connection_type'),
                      
                         
                       
                    ];
                    $connection_type = $this->request->getVar('connection_type');
                  

                     $data_exist=$this->conn_type_model->checkdata($connection_type);
                  
                    if($data_exist)
                        {

                        echo "<script>alert('Data Already Exists');</script>";
                        return view('WaterConnectionTypeMaster',$data);
                     }
                    else{

                       
                        $conn_type_mstr=array();
                        $conn_type_mstr['connection_type']=$connection_type;
                     
                        if($insert_last_id = $this->conn_type_model->insertData($conn_type_mstr)){
                           return $this->response->redirect(base_url('WaterConnectionTypeMaster'));
                        }
                        else{
                        return $this->response->redirect(base_url('WaterConnectionTypeMaster'));
                        }

                    }
                }

            }
            else
            {


                $rules=[

                    'connection_type'=>'required',
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/connection_type_master',$data);

                }
                else
                {


                    //store the data
                    $data = [
                        'connection_type' => $this->request->getVar('connection_type'),
                        
                         
                       
                    ];
                    $connection_type = $this->request->getVar('connection_type');
                 
                    $id=$this->request->getVar('id');

                    $data_exist=$this->conn_type_model->checkdata($connection_type);
                  
                    if($data_exist)
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('water/master/pipeline_master',$data);
                     }
                    else{

                       
                        $conn_type_mstr=array();
                        $conn_type_mstr['connection_type']=$connection_type;
                        $conn_type_mstr['id']=$id;
                       

                        if($insert_last_id = $this->conn_type_model->updateData($conn_type_mstr)){
                          
                            return $this->response->redirect(base_url('WaterConnectionTypeMaster'));
                        }
                        else{

                         return $this->response->redirect(base_url('WaterConnectionTypeMaster'));
                        }

                    }
                }

            }
        }
        else if(isset($id))
        {
           
            $getData=$this->conn_type_model->getData($id);
            //print_r($getData);
            $data['connection_type']=$getData['connection_type'];
         
            $data['id']=$getData['id'];
            return view('water/master/connection_type_master',$data);
        }  
        else
        {   

            return view('water/master/connection_type_master');
        }
    }

    public function delete($id)
    {
        $this->conn_type_model->deleteData($id);
        return $this->response->redirect(base_url('WaterConnectionTypeMaster'));
    }
    
    
}
