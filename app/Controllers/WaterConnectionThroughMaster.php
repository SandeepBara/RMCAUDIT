<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterConnectionThroughModel;


class WaterConnectionThroughMaster extends AlphaController
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
        
        $this->conn_through_model=new WaterConnectionThroughModel($this->db);

        
    }
    
    public function index()
    {
        $data['conn_through_list']=$this->conn_through_model->conn_through_list();

        return view('water/master/connection_through_list',$data);
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

                    'connection_through'=>'required',
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/connection_through_master',$data);
                }
                else
                {


                    //store the data
                    $data = [
                        'connection_through' => $this->request->getVar('connection_through'),
                      
                         
                       
                    ];
                    $connection_through = $this->request->getVar('connection_through');
                  

                     $data_exist=$this->conn_through_model->checkdata($connection_through);
                  
                    if($data_exist)
                        {

                        echo "<script>alert('Data Already Exists');</script>";
                        return view('WaterConnectionThroughMaster',$data);
                     }
                    else{

                       
                        $conn_through_mstr=array();
                        $conn_through_mstr['connection_through']=$connection_through;
                     
                        if($insert_last_id = $this->conn_through_model->insertData($conn_through_mstr)){
                           return $this->response->redirect(base_url('WaterConnectionThroughMaster'));
                        }
                        else{
                        return $this->response->redirect(base_url('WaterConnectionThroughMaster'));
                        }

                    }
                }

            }
            else
            {


                $rules=[

                    'connection_through'=>'required',
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/connection_through_master',$data);

                }
                else
                {


                    //store the data
                    $data = [
                        'connection_through' => $this->request->getVar('connection_through'),
                        
                         
                       
                    ];
                    $connection_through = $this->request->getVar('connection_through');
                 
                    $id=$this->request->getVar('id');

                    $data_exist=$this->conn_through_model->checkdata($connection_through);
                  
                    if($data_exist)
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('water/master/pipeline_master',$data);
                     }
                    else{

                       
                        $conn_through_mstr=array();
                        $conn_through_mstr['connection_through']=$connection_through;
                        $conn_through_mstr['id']=$id;
                       

                        if($insert_last_id = $this->conn_through_model->updateData($conn_through_mstr)){
                          
                            return $this->response->redirect(base_url('WaterConnectionThroughMaster'));
                        }
                        else{

                         return $this->response->redirect(base_url('WaterConnectionThroughMaster'));
                        }

                    }
                }

            }
        }
        else if(isset($id))
        {
           
            $getData=$this->conn_through_model->getData($id);
            //print_r($getData);
            $data['connection_through']=$getData['connection_through'];
         
            $data['id']=$getData['id'];
            return view('water/master/connection_through_master',$data);
        }  
        else
        {   

            return view('water/master/connection_through_master');
        }
    }

    public function delete($id)
    {
        $this->conn_through_model->deleteData($id);
        return $this->response->redirect(base_url('WaterConnectionThroughMaster'));
    }
    
    
}
