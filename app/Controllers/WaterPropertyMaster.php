<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterPropertyModel;


class WaterPropertyMaster extends AlphaController
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
        
        $this->property_model=new WaterPropertyModel($this->db);

        
    }
    
    public function index()
    {
       
        $data['property_list']=$this->property_model->property_list();
        return view('water/master/property_list',$data);
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

                    'property_type'=>'required',
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/property_master',$data);
                }
                else
                {


                    //store the data
                    $data = [
                        'property_type' => $this->request->getVar('property_type'),
                      
                         
                       
                    ];
                    $property_type = $this->request->getVar('property_type');
                  

                     $data_exist=$this->property_model->checkdata($property_type);
                  
                    if($data_exist)
                        {

                        echo "<script>alert('Data Already Exists');</script>";
                        return view('WaterPropertyMaster',$data);
                     }
                    else{

                       
                        $property_mstr=array();
                        $property_mstr['property_type']=$property_type;
                     
                        if($insert_last_id = $this->property_model->insertData($property_mstr)){
                          return $this->response->redirect(base_url('WaterPropertyMaster'));
                        }
                        else{
                        return $this->response->redirect(base_url('WaterPropertyMaster'));
                        }

                    }
                }

            }
            else
            {


                $rules=[

                    'property_type'=>'required',
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/property_master',$data);

                }
                else
                {


                    //store the data
                    $data = [
                        'property_type' => $this->request->getVar('property_type'),
                        
                          ];

                    $property_type = $this->request->getVar('property_type');
                 
                    $id=$this->request->getVar('id');

                    $data_exist=$this->property_model->checkdata($property_type);
                  
                    if($data_exist)
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('water/master/property_master',$data);
                     }
                    else{

                       
                        $property_mstr=array();
                        $property_mstr['property_type']=$property_type;
                        $property_mstr['id']=$id;
                       

                        if($insert_last_id = $this->property_model->updateData($property_mstr)){
                          
                          return $this->response->redirect(base_url('WaterPropertyMaster'));
                        }
                        else{

                         return $this->response->redirect(base_url('WaterPropertyMaster'));
                        }

                    }
                }

            }
        }
        else if(isset($id))
        {
           
            $getData=$this->property_model->getData($id);
            //print_r($getData);
            $data['property_type']=$getData['property_type'];
         
            $data['id']=$getData['id'];
            return view('water/master/property_master',$data);
        }  
        else
        {   

            return view('water/master/property_master');
        }
    }

    public function delete($id)
    {
        $this->property_model->deleteData($id);  
        return $this->response->redirect(base_url('WaterPropertyMaster'));
    }
    
    
}
